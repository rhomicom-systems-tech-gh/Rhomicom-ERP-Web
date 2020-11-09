<?php
//
////CORE BANKING
//TELLER OPERAATIONS
//WITHDRAWAL TRANSACTIONS
$canSeeOthrBrnchTrns = test_prmssns($dfltPrvldgs[297], $ModuleName);
$prsnid = $_SESSION['PRSN_ID'];

function getCorporateSignatory($acctNo, $signSrcType) {
    $strSql = "";
    if ($signSrcType == "Individual Customers") {
        $strSql = "SELECT distinct trim(c.title || ' ' || c.sur_name || 
                ', ' || c.first_name || ' ' || c.other_names) fullname 
            FROM mcf.mcf_accounts a, mcf.mcf_account_signatories b, mcf.mcf_customers_ind c 
            WHERE (b.end_date IS NULL OR now() <= to_timestamp(b.end_date,'YYYY-MM-DD'))
            AND a.account_id = b.account_id AND b.person_cust_id = c.cust_id
            AND account_number = '" . loc_db_escape_string($acctNo) . "'";
    } else {
        $strSql = "SELECT distinct trim(c.title || ' ' || c.sur_name || 
                ', ' || c.first_name || ' ' || c.other_names) fullname 
            FROM mcf.mcf_accounts a, mcf.mcf_account_signatories b, mcf.mcf_prsn_names_nos c
            WHERE (b.end_date IS NULL OR now() <= to_timestamp(b.end_date,'YYYY-MM-DD'))
            AND a.account_id = b.account_id AND b.person_cust_id = c.person_id
            AND account_number = '" . loc_db_escape_string($acctNo) . "'";
    }

    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return "";
}

function getAccountSignatories($acctNo) {
    $strSql = "";
    $custType = "";
    $strSql1 = "SELECT cust_type FROM mcf.mcf_accounts WHERE account_number = '" . loc_db_escape_string($acctNo) . "'";
    $result1 = executeSQLNoParams($strSql1);
    while ($row1 = loc_db_fetch_array($result1)) {
        $custType = $row1[0];
    }
    if ($custType == "Individual" || $custType == "Joint-Individual") {
        $strSql = "SELECT acct_sign_id, (trim(c.title || ' ' || c.sur_name || 
                ', ' || c.first_name || ' ' || c.other_names)) fullname, person_cust_id, to_sign_mndtry, b.source_type  
            FROM mcf.mcf_accounts a, mcf.mcf_account_signatories b, mcf.mcf_customers_ind c 
            WHERE (b.end_date IS NULL OR now() <= to_timestamp(b.end_date,'YYYY-MM-DD'))
            AND a.account_id = b.account_id AND b.person_cust_id = c.cust_id
            AND account_number = '" . loc_db_escape_string($acctNo) . "'";
    } else if ($custType == "Corporate") {
        $strSql = "SELECT acct_sign_id, mcf.get_corporate_signatory('$acctNo', source_type, b.person_cust_id), b.person_cust_id, to_sign_mndtry, b.source_type  
            FROM mcf.mcf_accounts a, mcf.mcf_account_signatories b
            WHERE (b.end_date IS NULL OR now() <= to_timestamp(b.end_date,'YYYY-MM-DD'))
            AND a.account_id = b.account_id
            AND account_number = '" . loc_db_escape_string($acctNo) . "'";
    } else {/* if ($custType == "Group") */
        $strSql = "SELECT acct_sign_id, (trim(c.title || ' ' || c.sur_name || 
                ', ' || c.first_name || ' ' || c.other_names)) fullname, person_cust_id, to_sign_mndtry, b.source_type  
            FROM mcf.mcf_accounts a, mcf.mcf_account_signatories b, mcf.mcf_customers_ind c 
            WHERE (b.end_date IS NULL OR now() <= to_timestamp(b.end_date,'YYYY-MM-DD'))
            AND a.account_id = b.account_id AND b.person_cust_id = c.cust_id
            AND account_number = '" . loc_db_escape_string($acctNo) . "'";
    }
    $result = executeSQLNoParams($strSql);
    return $result;
}

function getChequeAccntNo($chequeNo) {
    $strSql = "SELECT mcf.get_cust_accnt_num(COALESCE(mcf.get_hsechq_acntid('" . loc_db_escape_string($chequeNo) . "'),-1)) ";
    //echo $strSql;
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return "";
}

function getChequeAccntID($chequeNo) {
    $strSql = "SELECT COALESCE(mcf.get_hsechq_acntid('" . loc_db_escape_string($chequeNo) . "'),-1) ";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -1;
}

function getChequeID($chequeNo, $chequeType) {
    $strSql = "SELECT COALESCE(mcf.get_chq_id('" . loc_db_escape_string($chequeNo) .
            "', '" . loc_db_escape_string($chequeType) . "'),-1) ";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -1;
}

function getAccountStatus($accntID) {
    $strSql = "SELECT COALESCE(mcf.get_cust_accnt_status(" . loc_db_escape_string($accntID) . "'),'') ";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return "";
}

function getLnkdChequeAccntTrnsID($lnkdChqID) {
    $strSql = "SELECT acct_trns_id from mcf.mcf_cust_account_transactions "
            . "WHERE lnkd_chq_trns_id=" . $lnkdChqID;
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -1;
}

function get_OneCustAccntHstryNav($pkeyID, $offset, $limit_size) {
    global $gnrlTrnsDteYMDHMS;
    $whereCls = "";
    $balDte = substr($gnrlTrnsDteYMDHMS, 0, 10);
    $strSql = "SELECT a.acct_trns_id, b.account_number, b.account_title, a.trns_date, b.currency_id, a.trns_type, " .
            "a.amount, COALESCE(a.status,'Not Submitted'), a.account_id, c.mapped_lov_crncy_id, gst.get_pssbl_val(c.mapped_lov_crncy_id) crncy_nm,"
            . "to_char(to_timestamp(a.trns_date, 'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS'), a.trns_no, "
            . "a.value_date, a.branch_id, a.doc_type, a.doc_no, a.trns_person_name, a.trns_person_tel_no, "
            . "a.trns_person_address, a.trns_person_id_type, a.trns_person_id_number, a.trns_person_type, a.status, "
            . "a.debit_or_credit, a.authorized_by_person_id, a.autorization_date, a.amount_cash, "
            . "a.voided_acct_trns_id, a.voided_trns_type, a.reversal_reason, a.description, a.created_by, "
            . "b.branch_id, prs.get_prsn_name(a.authorized_by_person_id), a.approval_limit_id, d.max_amount, 
              CASE WHEN (a.status NOT IN ('Authorized','Paid','Received','Void','Historic')) or a.status IS NULL THEN mcf.get_cstacnt_unclrd_bals(a.account_id, '" . $balDte . "') ELSE a.unclrdbal+mcf.get_trns_crnt_prtn(a.acct_trns_id) END, 
              CASE WHEN (a.status NOT IN ('Authorized','Paid','Received','Void','Historic')) or a.status IS NULL THEN mcf.get_cstacnt_avlbl_bals(a.account_id, '" . $balDte . "') ELSE a.clrdbal+mcf.get_trns_avlbl_prtn(a.acct_trns_id) END, 
              b.account_type, 
              CASE WHEN a.acctstatus='' THEN b.status ELSE a.acctstatus END, 
              CASE WHEN a.acctcustomer='' THEN mcf.get_customer_name(b.cust_type, b.cust_id) ELSE a.acctcustomer END, 
                b.prsn_type_or_entity, a.acctlien, 
                CASE WHEN a.mandate='' THEN b.mandate ELSE a.mandate END, a.wtdrwllimitno, a.wtdrwllimitamt, a.wtdrwllimittype,
                org.get_site_name(a.branch_id::integer) " .
            "FROM mcf.mcf_cust_account_transactions a "
            . "LEFT OUTER JOIN mcf.mcf_accounts b ON (a.account_id = b.account_id) "
            . "LEFT OUTER JOIN mcf.mcf_currencies c ON (b.currency_id = c.crncy_id) "
            . "LEFT OUTER JOIN vms.vms_authorizers_limit d ON (a.approval_limit_id = d.authorizer_limit_id) "
            . "LEFT OUTER JOIN mcf.mcf_prdt_savings e ON (e.svngs_product_id = b.product_type_id) "
            . "WHERE ((a.account_id = " . $pkeyID . ")$whereCls) ORDER BY a.trns_date DESC, acct_trns_id DESC  LIMIT $limit_size OFFSET " . abs($offset * $limit_size);
    //echo $strSql;
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_OneCustAccntHstrytNavTtl($pkeyID) {
    $whereCls = "";
    $strSql = "SELECT count(1) " .
            "FROM mcf.mcf_cust_account_transactions a "
            . "LEFT OUTER JOIN mcf.mcf_accounts b ON (a.account_id = b.account_id) "
            . "LEFT OUTER JOIN mcf.mcf_currencies c ON (b.currency_id = c.crncy_id) "
            . "LEFT OUTER JOIN vms.vms_authorizers_limit d ON (a.approval_limit_id = d.authorizer_limit_id) "
            . "LEFT OUTER JOIN mcf.mcf_prdt_savings e ON (e.svngs_product_id = b.product_type_id) "
            . "WHERE ((a.account_id = " . $pkeyID . ")$whereCls)";
    //echo $strSql;
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return 0;
}

//TABLE mcf.mcf_standing_orders
//TABLE mcf.mcf_standing_orders
function createStandingOrder($srcAccountId, $destType, $destAcctOrWalletNo, $transferType, $amount, $frqncyNo, $frqncyType, $startDate, $endDate, $extnlBankId, $extnlBranchId, $extnlBnfcryName, $extnlBnfcryPstlAddrs, $branchID, $entrdCrncyID, $trnsExchngRate, $trnsfrOrdrDesc, $trnsfrOrderNum, $dateStr, $dflt_fees_gl_acnt_id) {
    global $usrID;
    global $prsnid;
    if ($startDate != "") {
        $startDate = cnvrtDMYToYMD($startDate);
    }
    if ($endDate != "") {
        $endDate = cnvrtDMYToYMD($endDate);
    } else {
        $endDate = "4000-12-31";
    }
    $insSQL = "INSERT INTO mcf.mcf_standing_orders(
            src_account_id, dest_type, dest_acct_or_wallet_no, 
            transfer_type, amount, frqncy_no, frqncy_type, start_date, end_date, 
            extnl_bank_id, extnl_branch_id, extnl_bnfcry_name, extnl_bnfcry_pstl_addrs, 
            created_by, creation_date, last_update_by, last_update_date, 
            branch_id, currency_id, negotiated_exch_rate, rmrk_narration, 
            status, authorized_by_person_id, autorization_date, approval_limit_id, 
            cheque_slip_no, dflt_gl_acnt_id)
    VALUES (" . $srcAccountId .
            ",'" . loc_db_escape_string($destType) .
            "','" . loc_db_escape_string($destAcctOrWalletNo) .
            "','" . loc_db_escape_string($transferType) .
            "', " . $amount .
            ", " . $frqncyNo .
            ",'" . loc_db_escape_string($frqncyType) .
            "','" . $startDate .
            "','" . $endDate .
            "'," . $extnlBankId .
            ", " . $extnlBranchId .
            " ,'" . loc_db_escape_string($extnlBnfcryName) .
            "','" . loc_db_escape_string($extnlBnfcryPstlAddrs) .
            "'," . $usrID .
            ",'" . $dateStr .
            "'," . $usrID .
            ",'" . $dateStr .
            "', (SELECT pasn.get_prsn_siteid($prsnid)), " . $entrdCrncyID .
            ", " . $trnsExchngRate .
            ",'" . loc_db_escape_string($trnsfrOrdrDesc) .
            "','Not Submitted', -1,'', -1, '" . loc_db_escape_string($trnsfrOrderNum) .
            "'," . $dflt_fees_gl_acnt_id . ")";

    $result = execUpdtInsSQL($insSQL);
    return $result;
}

function updateStandingOrder($stndnOrderId, $srcAccountId, $destType, $destAcctOrWalletNo, $transferType, $amount, $frqncyNo, $frqncyType, $startDate, $endDate, $extnlBankId, $extnlBranchId, $extnlBnfcryName, $extnlBnfcryPstlAddrs, $entrdCrncyID, $trnsExchngRate, $trnsfrOrdrDesc, $trnsfrOrderNum, $dateStr, $dflt_fees_gl_acnt_id) {
    global $usrID;
    if ($startDate != "") {
        $startDate = cnvrtDMYToYMD($startDate);
    }
    if ($endDate != "") {
        $endDate = cnvrtDMYToYMD($endDate);
    } else {
        $endDate = "4000-12-31";
    }
    $insSQL = "UPDATE mcf.mcf_standing_orders SET
            src_account_id =" . $srcAccountId .
            ", dest_type ='" . loc_db_escape_string($destType) . "',
            dest_acct_or_wallet_no ='" . loc_db_escape_string($destAcctOrWalletNo) . "', 
            transfer_type ='" . loc_db_escape_string($transferType) .
            "', amount =" . $amount .
            ", frqncy_no=" . $frqncyNo .
            ", frqncy_type = '" . loc_db_escape_string($frqncyType) .
            "', start_date ='" . $startDate .
            "', end_date ='" . $endDate .
            "', extnl_bank_id =" . $extnlBankId .
            ", extnl_branch_id =" . $extnlBranchId .
            ", extnl_bnfcry_name ='" . loc_db_escape_string($extnlBnfcryName) .
            "', extnl_bnfcry_pstl_addrs ='" . loc_db_escape_string($extnlBnfcryPstlAddrs) .
            "', last_update_by=" . $usrID .
            ", last_update_date = '" . $dateStr .
            "', currency_id=" . $entrdCrncyID .
            ", negotiated_exch_rate=" . $trnsExchngRate .
            ", rmrk_narration='" . loc_db_escape_string($trnsfrOrdrDesc) .
            "', cheque_slip_no='" . loc_db_escape_string($trnsfrOrderNum) .
            "', dflt_gl_acnt_id=" . $dflt_fees_gl_acnt_id . " WHERE stndn_order_id=" . $stndnOrderId;
    $result = execUpdtInsSQL($insSQL);
    return $result;
}

function deleteStandingOrder($stndnOrderId, $extrInfo = "") {
    $selSQL = "Select count(1) from mcf.mcf_standing_order_executions WHERE stndn_order_id = " . $stndnOrderId . "";
    $result = executeSQLNoParams($selSQL);
    $trnsCnt = 0;
    $affctd1 = 0;
    $affctd2 = 0;
    $affctd3 = 0;
    $affctd4 = 0;
    while ($row = loc_db_fetch_array($result)) {
        $trnsCnt = $row[0];
    }
    if ($trnsCnt <= 0) {
        $insSQL = "DELETE FROM mcf.stndn_order_misc_id WHERE stndn_order_id = " . $stndnOrderId;
        $affctd1 = execUpdtInsSQL($insSQL, "Trnsfr/Order No.:" . $extrInfo);
        $insSQL = "DELETE FROM mcf.mcf_stnd_ordr_sources WHERE stndn_order_id = " . $stndnOrderId;
        $affctd2 = execUpdtInsSQL($insSQL, "Trnsfr/Order No.:" . $extrInfo);
        $insSQL = "DELETE FROM mcf.mcf_stnd_ordr_dstntns WHERE stndn_order_id = " . $stndnOrderId;
        $affctd3 = execUpdtInsSQL($insSQL, "Trnsfr/Order No.:" . $extrInfo);
        $insSQL = "DELETE FROM mcf.mcf_standing_orders WHERE stndn_order_id = " . $stndnOrderId;
        $affctd4 = execUpdtInsSQL($insSQL, "Trnsfr/Order No.:" . $extrInfo);
    }
    if ($affctd4 > 0) {
        $dsply = "Successfully Deleted the ff Records-";
        $dsply .= "<br/>$affctd1 Miscellaneous Transaction(s)!";
        $dsply .= "<br/>$affctd2 Transfer/Order Source(s)!";
        $dsply .= "<br/>$affctd3 Transfer/Order Destination(s)!";
        $dsply .= "<br/>$affctd4 Transfer/Order(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Deleted<br/>Cannot Delete Executed Transfers/Orders!";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function deleteStandingOrderDstns($stndnOrderDstId, $extrInfo = "") {
    $selSQL = "Select count(1) from mcf.mcf_standing_order_executions WHERE stndn_order_dst_id = " . $stndnOrderDstId . "";
    $result = executeSQLNoParams($selSQL);
    $trnsCnt = 0;
    $affctd1 = 0;
    $affctd3 = 0;
    while ($row = loc_db_fetch_array($result)) {
        $trnsCnt = $row[0];
    }
    if ($trnsCnt <= 0) {
        $insSQL = "DELETE FROM mcf.mcf_stnd_ordr_dstntns WHERE stndn_order_dst_id = " . $stndnOrderDstId;
        $affctd1 = execUpdtInsSQL($insSQL, "Trnsfr/Order No.:" . $extrInfo);
    }
    if ($affctd3 > 0) {
        $dsply = "Successfully Deleted the ff Records-";
        $dsply .= "<br/>$affctd1 Transfer/Order Destination(s)!";
        $dsply .= "<br/>$affctd3 Transfer/Order(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Deleted<br/>Cannot Delete Executed Transfer/Order Destinations!";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function getStandingNwOrderID($dateStr, $usrID, $destAcctOrWalletNo) {
    $sqlStr = "SELECT stndn_order_id FROM mcf.mcf_standing_orders WHERE 1=1 AND creation_date = '" . $dateStr .
            "' AND created_by = " . $usrID . " AND dest_acct_or_wallet_no = '" . loc_db_escape_string($destAcctOrWalletNo) . "'";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return -1;
}

function getStandingOrderID($trnsfrOrderNum) {
    $sqlStr = "SELECT stndn_order_id FROM mcf.mcf_standing_orders WHERE cheque_slip_no = '" . loc_db_escape_string($trnsfrOrderNum) . "'";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return -1;
}

function getStandingOrderTblTtl($branchID, $searchFor, $searchIn, $orgID, $searchAll) {
    $extra1 = "";
    $extra2 = "";

    if ($searchAll == true) {
        $extra1 = "or 1 = 1";
    }

    $strSql = "";
    $whrcls = "";
    if ($searchIn == "Destination Type") {
        $whrcls = " AND (a.dest_type ilike '" . loc_db_escape_string($searchFor) . "')";
    } else if ($searchIn == "Description") {
        $whrcls = " AND (b.account_title ilike '" . loc_db_escape_string($searchFor) . "')";
    }

    if ($branchID != "" && is_numeric($branchID) === TRUE) {
        $whrcls .= " AND a.branch_id = $branchID ";
    }

    $strSql = "SELECT count(1) " .
            "FROM mcf.mcf_standing_orders a, mcf.mcf_accounts b WHERE a.src_account_id = b.account_id AND (1 = 1 AND (1 = 1 " . $extra1 . ")" . $whrcls . $extra2 .
            ")";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return 0;
}

function getStandingOrderTbl($branchID, $searchFor, $searchIn, $offset, $limit_size, $orgID, $searchAll, $sortBy) {
    $extra1 = "";

    if ($searchAll == true) {
        $extra1 = "or 1 = 1";
    }

    $strSql = "";
    $whrcls = "";
    if ($searchIn == "Destination Type") {
        $whrcls = " AND (a.dest_type ilike '" . loc_db_escape_string($searchFor) . "')";
    } else if ($searchIn == "Account Title") {
        $whrcls = " AND (b.account_title ilike '" . loc_db_escape_string($searchFor) . "')";
    }

    if ($branchID != "" && is_numeric($branchID) === TRUE) {
        $whrcls .= " AND a.branch_id = $branchID ";
    }

    if ($sortBy == "Destination Type ASC") {
        $ordrBy = "a.dest_type ASC";
    } else if ($sortBy == "Account Title ASC") {
        $ordrBy = "b.account_title DESC";
    } else if ($sortBy == "Account Title DESC") {
        $ordrBy = "b.account_title DESC";
    }

    $strSql = "SELECT stndn_order_id, b.account_title, a.src_account_id, a.dest_type, dest_acct_or_wallet_no, 
       transfer_type, a.amount, a.frqncy_no, a.frqncy_type, a.start_date, a.end_date, 
       extnl_bank_id, extnl_branch_id, extnl_bnfcry_name, extnl_bnfcry_pstl_addrs, a.branch_id
  FROM mcf.mcf_standing_orders a, mcf.mcf_accounts b WHERE a.src_account_id = b.account_id
  WHERE (1 = 1 AND (1 = 1 " . $extra1 . ")" . $whrcls .
            ") ORDER BY " . $ordrBy . " LIMIT " . $limit_size .
            " OFFSET " . abs($offset * $limit_size);
    $result = executeSQLNoParams($strSql);
    //var_dump($strSql);
    return $result;
}

function get_OneStndOrdrDstns($ordrID) {
    $strSql = "SELECT a.stndn_order_dst_id, a.stndn_order_id, a.dest_acct_or_wallet_no, a.amount, 
       a.extnl_bank_id,
       mcf.get_bank_code(a.extnl_bank_id) || '.' || mcf.get_bank_name(a.extnl_bank_id) bnknm,
       a.extnl_branch_id, 
       mcf.get_branch_code(a.extnl_branch_id) || '.' || mcf.get_branch_name(a.extnl_branch_id) brnchnm, 
       a.extnl_bnfcry_name, a.extnl_bnfcry_pstl_addrs, a.created_by, a.creation_date, a.last_update_by, a.last_update_date,
       a.dest_type, a.transfer_type
  FROM mcf.mcf_stnd_ordr_dstntns a
         WHERE (a.stndn_order_id=" . $ordrID . ")";
    $result = executeSQLNoParams($strSql);
    return $result;
}

function getStndngOrderDstID($stndOrdrID, $dstAcNum, $dtsType, $trnsfrType) {
    $sqlStr = "SELECT stndn_order_dst_id FROM mcf.mcf_stnd_ordr_dstntns "
            . "WHERE stndn_order_id=" . loc_db_escape_string($stndOrdrID) .
            " and dest_acct_or_wallet_no = '" . loc_db_escape_string($dstAcNum) .
            "' and dest_type='" . loc_db_escape_string($dtsType) .
            "' and transfer_type='" . loc_db_escape_string($trnsfrType) .
            "'";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -1;
}

function createOrderDstAcnt($trnsfrOdrID, $destType, $destAcNo, $trnsfrType
, $trnsfrAmnt, $extrnlBnkID, $extrnlBrnchID, $extrnlBnfcryNm, $extrnlBnfcryAdrs, $dateStr) {
    global $usrID;
    //$dateStr = getDB_Date_time();
    $insSQL = "INSERT INTO mcf.mcf_stnd_ordr_dstntns(
            stndn_order_id, dest_type, dest_acct_or_wallet_no, 
            transfer_type, amount, extnl_bank_id, extnl_branch_id, extnl_bnfcry_name, 
            extnl_bnfcry_pstl_addrs, created_by, creation_date, last_update_by, 
            last_update_date) " .
            "VALUES (" . $trnsfrOdrID .
            ", '" . loc_db_escape_string($destType) .
            "', '" . loc_db_escape_string($destAcNo) .
            "', '" . loc_db_escape_string($trnsfrType) .
            "', " . loc_db_escape_string($trnsfrAmnt) .
            ", " . loc_db_escape_string($extrnlBnkID) .
            ", " . loc_db_escape_string($extrnlBrnchID) .
            ", '" . loc_db_escape_string($extrnlBnfcryNm) .
            "', '" . loc_db_escape_string($extrnlBnfcryAdrs) .
            "', " . $usrID . ",'" . $dateStr . "'," . $usrID . ",'" . $dateStr .
            "')";
    return execUpdtInsSQL($insSQL);
}

function updateOrderDstAcnt($lnDstID, $trnsfrOdrID, $destType, $destAcNo, $trnsfrType
, $trnsfrAmnt, $extrnlBnkID, $extrnlBrnchID, $extrnlBnfcryNm, $extrnlBnfcryAdrs, $dateStr) {
    global $usrID;
    //$dateStr = getDB_Date_time();
    $updtSQL = "UPDATE mcf.mcf_stnd_ordr_dstntns
           SET stndn_order_id=" . $trnsfrOdrID .
            ", dest_type='" . loc_db_escape_string($destType) .
            "', dest_acct_or_wallet_no='" . loc_db_escape_string($destAcNo) .
            "', transfer_type='" . loc_db_escape_string($trnsfrType) .
            "', amount=" . $trnsfrAmnt .
            ", extnl_bank_id=" . $extrnlBnkID .
            ", extnl_branch_id=" . $extrnlBrnchID .
            ", extnl_bnfcry_name='" . loc_db_escape_string($extrnlBnfcryNm) .
            "', extnl_bnfcry_pstl_addrs='" . loc_db_escape_string($extrnlBnfcryAdrs) .
            "', last_update_by=" . $usrID .
            ", last_update_date='" . $dateStr . "' WHERE stndn_order_dst_id = " . $lnDstID;
    return execUpdtInsSQL($updtSQL);
}

function createOrderExtraSrcTrns($trnsfrOdrID, $trnsType, $custAcntID, $rmrkDesc
, $trnsfrAmnt, $crncyID, $crncyRate, $dateStr) {
    global $usrID;
    //$dateStr = getDB_Date_time();
    $insSQL = "INSERT INTO mcf.mcf_stnd_ordr_sources(
            stndn_order_id, account_id, 
            trns_type, description, amount, entered_crncy_id, accnt_crncy_rate, 
            created_by, creation_date, last_update_by, last_update_date) " .
            "VALUES (" . $trnsfrOdrID .
            ", " . $custAcntID .
            ", '" . loc_db_escape_string($trnsType) .
            "', '" . loc_db_escape_string($rmrkDesc) .
            "', " . loc_db_escape_string($trnsfrAmnt) .
            ", " . loc_db_escape_string($crncyID) .
            ", " . loc_db_escape_string($crncyRate) .
            ", " . $usrID . ", '" . $dateStr . "', " . $usrID . ", '" . $dateStr .
            "')";
    return execUpdtInsSQL($insSQL);
}

function updateOrderExtraSrcTrns($lnMiscID, $trnsfrOdrID, $trnsType, $custAcntID, $rmrkDesc
, $trnsfrAmnt, $crncyID, $crncyRate, $dateStr) {
    global $usrID;
    //$dateStr = getDB_Date_time();
    $updtSQL = "UPDATE mcf.mcf_stnd_ordr_sources
           SET stndn_order_id=" . $trnsfrOdrID .
            ", account_id=" . $custAcntID .
            ", trns_type='" . loc_db_escape_string($trnsType) .
            "', description='" . loc_db_escape_string($rmrkDesc) .
            "', amount=" . $trnsfrAmnt .
            ", entered_crncy_id=" . $crncyID .
            ", accnt_crncy_rate=" . $crncyRate .
            ", last_update_by=" . $usrID .
            ", last_update_date='" . $dateStr . "' WHERE stndn_order_src_id = " . $lnMiscID;
    return execUpdtInsSQL($updtSQL);
}

function deleteOrderExtraSrcTrns($stndnOrderMiscId, $extrInfo = "") {
    $selSQL = "Select count(1) from mcf.mcf_standing_order_executions WHERE stndn_order_src_id = " . $stndnOrderMiscId . "";
    $result = executeSQLNoParams($selSQL);
    $trnsCnt = 0;
    $affctd1 = 0;
    $affctd3 = 0;
    while ($row = loc_db_fetch_array($result)) {
        $trnsCnt = $row[0];
    }
    if ($trnsCnt <= 0) {
        $insSQL = "DELETE FROM mcf.mcf_stnd_ordr_sources WHERE stndn_order_src_id = " . $stndnOrderMiscId;
        $affctd1 = execUpdtInsSQL($insSQL, "Trnsfr/Order No.:" . $extrInfo);
    }
    if ($affctd1 > 0) {
        $dsply = "Successfully Deleted the ff Records-";
        $dsply .= "<br/>$affctd1 Transfer/Order Extra Source Account(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Deleted<br/>Cannot Delete Executed Transfer/Order Extra Source Account!";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function get_OneStndOrdrSources($ordrID) {
    $strSql = "SELECT a.stndn_order_src_id, a.stndn_order_id, a.account_id, 
        mcf.get_cust_accnt_num(a.account_id) accnum, 
        mcf.get_cust_accnt_name(a.account_id) cstmr_accnt,
        a.amount, a.trns_type, a.description, a.entered_crncy_id, 
                        b.mapped_lov_crncy_id, 
                        gst.get_pssbl_val(b.mapped_lov_crncy_id) crncy_nm,
                a.accnt_crncy_rate
            FROM mcf.mcf_stnd_ordr_sources a        
            LEFT OUTER JOIN mcf.mcf_currencies b ON (a.entered_crncy_id = b.crncy_id) 
         WHERE (a.stndn_order_id=" . $ordrID . " and a.stndn_order_id>0)";
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_OneStndOrdrMisc($ordrID) {
    $strSql = "SELECT a.stndn_order_misc_id, a.stndn_order_id, a.account_id, 
        mcf.get_cust_accnt_num(a.account_id) accnum, 
        mcf.get_cust_accnt_name(a.account_id) cstmr_accnt,
        a.amount, a.trns_type, a.description, a.entered_crncy_id, 
                        b.mapped_lov_crncy_id, 
                        gst.get_pssbl_val(b.mapped_lov_crncy_id) crncy_nm,
                        a.balancing_gl_accnt_id,
                accb.get_accnt_num(a.balancing_gl_accnt_id) || '.' || accb.get_accnt_name(a.balancing_gl_accnt_id) gl_acc_nm,
                mcf.shd_MiscBe_DbtOrDrdt(a.balancing_gl_accnt_id, a.trns_type) shdDbtCrdtGl, 
                mcf.shd_MiscBe_IOrD(a.balancing_gl_accnt_id, a.trns_type) shdIoD,
                a.accnt_crncy_rate
            FROM mcf.mcf_standing_order_misc a        
            LEFT OUTER JOIN mcf.mcf_currencies b ON (a.entered_crncy_id = b.crncy_id) 
         WHERE (a.stndn_order_id=" . $ordrID . " and a.stndn_order_id>0)";
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_OneBulkMiscTrns($hdrID) {
    $strSql = "SELECT a.stndn_order_misc_id, a.bulk_trns_hdr_id, a.account_id, 
        mcf.get_cust_accnt_num(a.account_id) accnum, 
        mcf.get_cust_accnt_name(a.account_id) cstmr_accnt,
        a.amount, a.trns_type, a.description, a.entered_crncy_id, 
                        b.mapped_lov_crncy_id, 
                        gst.get_pssbl_val(b.mapped_lov_crncy_id) crncy_nm,
                        a.balancing_gl_accnt_id,
                accb.get_accnt_num(a.balancing_gl_accnt_id) || '.' || accb.get_accnt_name(a.balancing_gl_accnt_id) gl_acc_nm,
                mcf.shd_MiscBe_DbtOrDrdt(a.balancing_gl_accnt_id, a.trns_type) shdDbtCrdtGl, 
                mcf.shd_MiscBe_IOrD(a.balancing_gl_accnt_id, a.trns_type) shdIoD,
                a.accnt_crncy_rate
            FROM mcf.mcf_standing_order_misc a        
            LEFT OUTER JOIN mcf.mcf_currencies b ON (a.entered_crncy_id = b.crncy_id) 
         WHERE (a.bulk_trns_hdr_id=" . $hdrID . " and a.bulk_trns_hdr_id>0)";
    $result = executeSQLNoParams($strSql);
    return $result;
}

function createOrderMiscTrns($trnsfrOdrID, $trnsType, $custAcntID, $rmrkDesc
, $trnsfrAmnt, $crncyID, $crncyRate, $subTrnsTyp, $glAcntID, $dateStr, $blkHdrID = -1) {
    global $usrID;
    //$dateStr = getDB_Date_time();
    $insSQL = "INSERT INTO mcf.mcf_standing_order_misc(
            stndn_order_id, bulk_trns_hdr_id, account_id, 
            trns_type, description, amount, entered_crncy_id, accnt_crncy_rate, 
            sub_trns_type, balancing_gl_accnt_id, created_by, creation_date, 
            last_update_by, last_update_date) " .
            "VALUES (" . $trnsfrOdrID .
            ", " . $blkHdrID . ", " . $custAcntID .
            ", '" . loc_db_escape_string($trnsType) .
            "', '" . loc_db_escape_string($rmrkDesc) .
            "', " . loc_db_escape_string($trnsfrAmnt) .
            ", " . loc_db_escape_string($crncyID) .
            ", " . loc_db_escape_string($crncyRate) .
            ", '" . loc_db_escape_string($subTrnsTyp) .
            "', " . loc_db_escape_string($glAcntID) .
            ", " . $usrID . ", '" . $dateStr . "', " . $usrID . ", '" . $dateStr .
            "')";
    return execUpdtInsSQL($insSQL);
}

function updateOrderMiscTrns($lnMiscID, $trnsfrOdrID, $trnsType, $custAcntID, $rmrkDesc
, $trnsfrAmnt, $crncyID, $crncyRate, $subTrnsTyp, $glAcntID, $dateStr, $blkHdrID = -1) {
    global $usrID;
    //$dateStr = getDB_Date_time();
    $updtSQL = "UPDATE mcf.mcf_standing_order_misc
           SET stndn_order_id=" . $trnsfrOdrID .
            ", bulk_trns_hdr_id=" . $blkHdrID .
            ", account_id=" . $custAcntID .
            ", trns_type='" . loc_db_escape_string($trnsType) .
            "', description='" . loc_db_escape_string($rmrkDesc) .
            "', amount=" . $trnsfrAmnt .
            ", entered_crncy_id=" . $crncyID .
            ", accnt_crncy_rate=" . $crncyRate .
            ", sub_trns_type='" . loc_db_escape_string($subTrnsTyp) .
            "', balancing_gl_accnt_id=" . loc_db_escape_string($glAcntID) .
            ", last_update_by=" . $usrID .
            ", last_update_date='" . $dateStr . "' WHERE stndn_order_misc_id = " . $lnMiscID;
    return execUpdtInsSQL($updtSQL);
}

function deleteOrderMiscTrns($stndnOrderMiscId, $extrInfo = "") {
    $selSQL = "Select count(1) from mcf.mcf_standing_order_executions WHERE stndn_order_misc_id = " . $stndnOrderMiscId . "";
    $result = executeSQLNoParams($selSQL);
    $trnsCnt = 0;
    $affctd1 = 0;
    $affctd3 = 0;
    while ($row = loc_db_fetch_array($result)) {
        $trnsCnt = $row[0];
    }
    if ($trnsCnt <= 0) {
        $insSQL = "DELETE FROM mcf.mcf_standing_order_misc WHERE stndn_order_misc_id = " . $stndnOrderMiscId;
        $affctd1 = execUpdtInsSQL($insSQL, "Trnsfr/Order No.:" . $extrInfo);
    }
    if ($affctd1 > 0) {
        $dsply = "Successfully Deleted the ff Records-";
        $dsply .= "<br/>$affctd1 Transfer/Order Misc. Trn(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Deleted<br/>Cannot Delete Executed Transfer/Order Misc. Trns!";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function deleteBulkMiscTrns($stndnOrderMiscId, $extrInfo = "") {
    $batchID = (float) getGnrlRecNm("mcf.mcf_standing_order_misc", "stndn_order_misc_id", "bulk_trns_hdr_id", $stndnOrderMiscId);
    $selSQL = "Select count(1) from mcf.mcf_bulk_trns_hdr WHERE bulk_trns_hdr_id = " . $batchID . " and status IN ('Authorized','Initiated','Processed','Reviewed','Void')";
    $result = executeSQLNoParams($selSQL);
    $trnsCnt = 0;
    $affctd1 = 0;
    $affctd3 = 0;
    while ($row = loc_db_fetch_array($result)) {
        $trnsCnt = $row[0];
    }
    if ($trnsCnt <= 0) {
        $insSQL = "DELETE FROM mcf.mcf_standing_order_misc WHERE stndn_order_misc_id = " . $stndnOrderMiscId;
        $affctd1 = execUpdtInsSQL($insSQL, "Batch No.:" . $extrInfo);
    }
    if ($affctd1 > 0) {
        $dsply = "Successfully Deleted the ff Records-";
        $dsply .= "<br/>$affctd1 Batch Misc. Trn(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Deleted<br/>Cannot Delete Submitted Batch Misc. Trns!";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

/* * END Standing Orders FXNS* */
/* NEW TELLERING CODES */

function getAuthrzrLmtID($prsnID, $siteID, $trnsTyp, $crncyID, $minAmnt, $maxAmnt) {
    $strSql = "SELECT a.authorizer_limit_id 
        FROM vms.vms_authorizers_limit a
        WHERE ((a.authorizer_person_id = $prsnID) "
            . "and (a.site_id = $siteID) "
            . "and (a.currency_id = $crncyID) "
            . "and (a.min_amount = $minAmnt) "
            . "and (a.max_amount = $maxAmnt) "
            . "and (a.transaction_type ='" . loc_db_escape_string($trnsTyp) . "')) ";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return -1;
}

function deleteDocGLInfcLns($docID, $srcDocType) {
    $delSQL = "DELETE FROM mcf.mcf_gl_interface WHERE src_doc_id = " .
            $docID . " and src_doc_typ ilike '%" . loc_db_escape_string($srcDocType) . "%' and gl_batch_id = -1";
    return execUpdtInsSQL($delSQL);
}

function deleteGLInfcLine($intfcID) {
    $delSQL = "DELETE FROM mcf.mcf_gl_interface WHERE interface_id = " .
            $intfcID . " and gl_batch_id = -1";
    return execUpdtInsSQL($delSQL);
}

function getDocGLInfcLns($docID, $srcDocType) {
    $strSql = "SELECT * FROM mcf.mcf_gl_interface WHERE src_doc_id = " .
            $docID . " and src_doc_typ ilike '%" . loc_db_escape_string($srcDocType) . "%' and gl_batch_id != -1";
    return executeSQLNoParams($strSql);
}

function createMCFTrnsGLIntFcLn($accntid, $trnsdesc, $dbtamnt, $trnsdte, $crncyid, $crdtamnt, $netamnt, $srcDocTyp, $srcDocID, $srcDocLnID, $dateStr, $trnsLnTyp, $trnsSrc, $entrdAMnt, $entrdCrncyID, $acctCrncyAmnt, $acctCrncyID, $funcCrncyRate, $acntCrncyRate) {
    global $usrID;
    global $gnrlTrnsDteYMDHMS;
    if ($accntid <= 0) {
        return;
    }
    /* if ($trnsdte != "") {
      $trnsdte = cnvrtDMYTmToYMDTm($trnsdte);
      } */
    if ($dateStr != "") {
        $dateStr = cnvrtDMYTmToYMDTm($dateStr);
    }
    $insSQL = "INSERT INTO mcf.mcf_gl_interface(
            accnt_id, transaction_desc, dbt_amount, trnsctn_date, 
            func_cur_id, created_by, creation_date, crdt_amount, last_update_by, 
            last_update_date, net_amount, gl_batch_id, src_doc_typ, src_doc_id, 
            src_doc_line_id, trns_ln_type, trns_source, entered_amnt, entered_amt_crncy_id, 
            accnt_crncy_amnt, accnt_crncy_id, func_cur_exchng_rate, accnt_cur_exchng_rate) " .
            "VALUES (" . $accntid .
            ", '" . loc_db_escape_string($trnsdesc) .
            "', " . $dbtamnt .
            ", '" . loc_db_escape_string($gnrlTrnsDteYMDHMS) .
            "', " . $crncyid .
            ", " . $usrID .
            ", '" . $dateStr .
            "', " . $crdtamnt .
            ", " . $usrID .
            ", '" . $dateStr .
            "', " . $netamnt .
            ", -1, '" . loc_db_escape_string($srcDocTyp) .
            "', " . $srcDocID .
            ", " . $srcDocLnID .
            ", '" . $trnsLnTyp .
            "', '" . $trnsSrc .
            "', " . $entrdAMnt .
            ", " . $entrdCrncyID .
            ", " . $acctCrncyAmnt .
            ", " . $acctCrncyID .
            ", " . $funcCrncyRate .
            ", " . $acntCrncyRate .
            ")";
    return execUpdtInsSQL($insSQL);
}

function updateMCFTrnsGLIntFcLn($intrfcLineID, $accntid, $trnsdesc, $dbtamnt, $trnsdte, $crncyid, $crdtamnt, $netamnt, $srcDocTyp, $srcDocID, $srcDocLnID, $dateStr, $trnsLnTyp, $trnsSrc, $entrdAMnt, $entrdCrncyID, $acctCrncyAmnt, $acctCrncyID, $funcCrncyRate, $acntCrncyRate) {
    global $usrID;
    global $gnrlTrnsDteYMDHMS;
    if ($accntid <= 0) {
        return;
    }
    if ($trnsdte != "") {
        $trnsdte = cnvrtDMYTmToYMDTm($trnsdte);
    }
    if ($dateStr != "") {
        $dateStr = cnvrtDMYTmToYMDTm($dateStr);
    }
    $insSQL = "UPDATE mcf.mcf_gl_interface
            SET accnt_id=" . $accntid .
            ", transaction_desc='" . loc_db_escape_string($trnsdesc) .
            "', dbt_amount=" . $dbtamnt .
            ", trnsctn_date='" . loc_db_escape_string($trnsdte) .
            "', func_cur_id=" . $crncyid .
            ", crdt_amount=" . $crdtamnt .
            ", last_update_by=" . $usrID .
            ", last_update_date='" . $dateStr .
            "', net_amount=" . $netamnt .
            ", entered_amnt=" . $entrdAMnt .
            ", entered_amt_crncy_id=" . $entrdCrncyID .
            ", accnt_crncy_amnt=" . $acctCrncyAmnt .
            ", accnt_crncy_id=" . $acctCrncyID .
            ", func_cur_exchng_rate=" . $funcCrncyRate .
            ", accnt_cur_exchng_rate=" . $acntCrncyRate .
            " WHERE interface_id=" . $intrfcLineID . " and gl_batch_id<=0";
    // and trns_source='USR'
    return execUpdtInsSQL($insSQL);
}

function deleteMCFTrnsGLIntFcLn($intrfcLineID, $intrfcDesc) {
    $delSQL = "DELETE FROM mcf.mcf_gl_interface WHERE interface_id = " . $intrfcLineID . " and gl_batch_id<=0 and trns_source!='SYS'";
    return execUpdtInsSQL($delSQL, $intrfcDesc);
}

function rvrsImprtdIntrfcTrns($docID, $doctype) {
    $result = getDocGLInfcLns($docID, $doctype);
    $dateStr = getFrmtdDB_Date_time();
    while ($row = loc_db_fetch_array($result)) {
        $accntID = (int) $row[1];
        $dbtamount = (float) $row[3];
        $crdtamount = (float) $row[8];
        $crncy_id = (int) $row[5];
        $netamnt = (float) $row[11];
        $srcDocID = (float) $row[14];
        $srcDocLnID = (float) $row[15];
        $trnsdte = $dateStr;
        $trnsLnTyp = $row[16];
        $trnsSrc = $row[17];
        $entrdAMnt = (float) $row[18];
        $entrdCrncyID = (int) $row[19];
        $acctCrncyAmnt = (float) $row[20];
        $acctCrncyID = (int) $row[21];
        $funcCrncyRate = (float) $row[22];
        $acntCrncyRate = (float) $row[23];
        createMCFTrnsGLIntFcLn($accntID, "(Reversal)" . $row[2], -1 * $dbtamount, $trnsdte, $crncy_id, -1 * $crdtamount, -1 * $netamnt, $row[13], $srcDocID, $srcDocLnID, $dateStr, $trnsLnTyp, $trnsSrc, $entrdAMnt, $entrdCrncyID, $acctCrncyAmnt, $acctCrncyID, $funcCrncyRate, $acntCrncyRate);
    }
    return true;
}

function get_One_MCFTrnsHdr($dochdrID) {
    global $prsnid;
    $strSql = "SELECT a.acct_trns_id, a.trns_date, a.account_id, a.trns_type, a.description, 
       a.amount, a.value_date, a.branch_id, a.doc_no, 
       CASE WHEN a.trns_person_name='' THEN a.acctcustomer ELSE a.trns_person_name END trns_person_name, a.trns_person_tel_no, 
       a.trns_person_address, a.trns_person_id_type, a.trns_person_id_number, 
       a.trns_person_type, a.status, a.doc_type, a.debit_or_credit, 
       a.authorized_by_person_id, a.autorization_date, a.trns_no, 
       org.get_accnt_id_brnch_eqv(b.branch_id, d.deposit_cash_crdt_accnt_id), 
       org.get_accnt_id_brnch_eqv(b.branch_id, d.withdrawal_dbt_accnt_id),
       e.mapped_lov_crncy_id, a.accnt_crncy_rate, 
       org.get_dflt_accnt_id($prsnid, d.deposit_cash_dbt_accnt_id), 
       org.get_accnt_id_brnch_eqv(b.branch_id, d.deposit_cash_crdt_accnt_id), 
       org.get_accnt_id_brnch_eqv(b.branch_id, d.withdrawal_dbt_accnt_id), 
       org.get_dflt_accnt_id($prsnid, d.withdrawal_crdt_accnt_id), 
       org.get_dflt_accnt_id($prsnid, d.deposit_cheque_dbt_accnt_id), 
       org.get_accnt_id_brnch_eqv(b.branch_id, d.deposit_cheque_crdt_accnt_id), 
       org.get_accnt_id_brnch_eqv(b.branch_id, d.entry_fees_dbt_accnt_id), 
       org.get_accnt_id_brnch_eqv(b.branch_id, d.entry_fees_crdt_accnt_id), 
       org.get_accnt_id_brnch_eqv(b.branch_id, d.close_fees_dbt_accnt_id), 
       org.get_accnt_id_brnch_eqv(b.branch_id, d.close_fees_crdt_accnt_id), 
       org.get_accnt_id_brnch_eqv(b.branch_id, d.reopen_fees_dbt_accnt_id), 
       org.get_accnt_id_brnch_eqv(b.branch_id, d.reopen_fees_crdt_accnt_id), 
       org.get_accnt_id_brnch_eqv(b.branch_id, d.wthdrwl_pnlty_pcnt_dbt_accnt_id), 
       org.get_accnt_id_brnch_eqv(b.branch_id, d.wthdrwl_pnlty_pcnt_crdt_accnt_id), 
       org.get_accnt_id_brnch_eqv(b.branch_id, d.wthdrwl_pnlty_pcnt_dbt_accnt_id), 
       org.get_accnt_id_brnch_eqv(b.branch_id, d.wthdrwl_pnlty_pcnt_crdt_accnt_id), 
       org.get_accnt_id_brnch_eqv(b.branch_id, d.invstmnt_liability_accnt_id), 
       org.get_accnt_id_brnch_eqv(b.branch_id, d.invstmnt_fee_crdt_accnt_id), 
       org.get_accnt_id_brnch_eqv(b.branch_id, d.cot_amnt_flat_dbt_accnt_id), 
       org.get_accnt_id_brnch_eqv(b.branch_id, d.cot_amnt_flat_crdt_accnt_id), 
       org.get_accnt_id_brnch_eqv(b.branch_id, d.accrued_interest_dbt_accnt_id), 
       org.get_accnt_id_brnch_eqv(b.branch_id, d.accrued_interest_crdt_accnt_id), 
       org.get_accnt_id_brnch_eqv(b.branch_id, d.interest_payment_dbt_accnt_id), 
       org.get_accnt_id_brnch_eqv(b.branch_id, d.interest_payment_crdt_accnt_id),
       (Select COALESCE(max(z.cage_shelve_id),-1) from mcf.mcf_account_trns_cash_analysis z where z.acct_trns_id=a.acct_trns_id) maxx_cg_id,
       a.sub_trns_type, a.balancing_gl_accnt_id, mcf.shd_MiscBe_IOrD(a.balancing_gl_accnt_id, a.trns_type) shdIoD,
                        mcf.get_cust_accnt_num(a.account_id) srcAcntNum,
                        mcf.get_cust_accnt_name(a.account_id) srcAcntName,
                        a.amount_cash
    FROM mcf.mcf_cust_account_transactions a 
    LEFT OUTER JOIN mcf.mcf_accounts b ON (a.account_id = b.account_id)
    LEFT OUTER JOIN mcf.mcf_prdt_savings c ON (b.product_type_id = c.svngs_product_id)
    LEFT OUTER JOIN mcf.mcf_prdt_savings_stdevnt_accntn d ON (c.svngs_product_id = d.svngs_product_id) 
    LEFT OUTER JOIN mcf.mcf_currencies e ON(a.entered_crncy_id = e.crncy_id)
  WHERE a.acct_trns_id = " . $dochdrID;
    //echo $strSql;
    return executeSQLNoParams($strSql);
}

function get_One_MCFTrnsLines($dochdrID) {
    global $prsnid;
    $strSql = "SELECT a.cash_analysis_id, a.acct_trns_id, a.denomination_id, a.unit_value, 
                    a.qty, b.mapped_lov_crncy_id, c.vault_item_id, c.display_name, d.item_type, 
                a.cage_shelve_id, a.vault_id, a.item_state, a.accnt_crncy_rate, 
                org.get_dflt_accnt_id($prsnid, e.inv_asset_acct_id), 
                e.cage_shelve_mngr_id
                FROM mcf.mcf_account_trns_cash_analysis a, mcf.mcf_currencies b,  
                        mcf.mcf_currency_denominations c, inv.inv_itm_list d, inv.inv_shelf e
                    WHERE  a.acct_trns_id = $dochdrID and a.denomination_id = c.crncy_denom_id "
            . "and b.crncy_id=c.crncy_id and c.vault_item_id=d.item_id  and a.cage_shelve_id = e.line_id and a.qty !=0 "
            . "ORDER BY a.cash_analysis_id, a.denomination_id";
    return executeSQLNoParams($strSql);
}

function get_Full_MCFTrnsLines($dochdrID) {
    $strSql = "SELECT a.cash_analysis_id, a.acct_trns_id, a.denomination_id, a.unit_value, 
                    a.qty, b.mapped_lov_crncy_id, c.vault_item_id, c.display_name, d.item_type, 
                a.vault_id, a.cage_shelve_id, a.item_state, a.accnt_crncy_rate, e.inv_asset_acct_id, e.cage_shelve_mngr_id
                FROM mcf.mcf_account_trns_cash_analysis a, mcf.mcf_currencies b,  
                        mcf.mcf_currency_denominations c, inv.inv_itm_list d, inv.inv_shelf e
                    WHERE  a.acct_trns_id = $dochdrID and a.denomination_id = c.crncy_denom_id "
            . "and b.crncy_id=c.crncy_id and c.vault_item_id=d.item_id  and a.cage_shelve_id = e.line_id "
            . "ORDER BY a.cash_analysis_id, a.denomination_id";
    return executeSQLNoParams($strSql);
}

function validateMCFAccntng($docHdrID, &$errMsg) {
    global $orgID;
    global $usrID;
    global $trnsTypes;
    global $trnsTypeABRV;
    $onlyValidate = "YES";
    $dateStr = getFrmtdDB_Date_time();
    $doctype = "";
    $docNum = "";
    $cstmrNm = "";
    $docDesc = "";
    $rsltAcc = get_One_MCFTrnsHdr($docHdrID);
    $entrdRate = 0;
    $srcStoreID = -1;
    //Get from one logged on
    $srcCageID = -1;
    $destStoreID = -1;
    $destCageID = -1;
    $srcInvAcntID = -1;
    $destInvAcntID = -1;
    $lbltyAccntID = -1;
    $cogsID = -1;
    $salesRevID = -1;
    $expnsID = -1;
    $itmState = "Issuable";
    $succs = false;
    $chqClrngAccntID = -1;
    $trnsAmntTtl = 0;
    $acntTrnsID = -1;
    $lnCntr = 0;
    $docEntrdCrncyID = -1;
    $docEntrdRate = 0;
    $errMsg = "";
    $nwMsg = "";
    //$mapdCrncyID = (int) getGnrlRecNm("mcf.mcf_currencies", "crncy_id", "mapped_lov_crncy_id", $crncyID);
    if ($rwAcc = loc_db_fetch_array($rsltAcc)) {
        $doctype = $rwAcc[3];
        $docNum = $rwAcc[20];
        $cstmrNm = $rwAcc[9];
        $docDesc = $rwAcc[4];
        $entrdRate = (float) 1;
        $acntTrnsID = (float) $rwAcc[0];
        $trnsAmntTtl = (float) $rwAcc[5];
        $docEntrdCrncyID = (float) $rwAcc[23];
        $docEntrdRate = (float) $rwAcc[24];
        if ($doctype == "WITHDRAWAL" || $doctype == "LOAN_PYMNT") {
            $lbltyAccntID = (int) $rwAcc[21]; //was 22
            $chqClrngAccntID = (int) $rwAcc[29];
        } else {
            $lbltyAccntID = (int) $rwAcc[21];
            $chqClrngAccntID = (int) $rwAcc[29];
        }
        if ($lbltyAccntID <= 0) {
            $lbltyAccntID = get_DfltSalesLbltyAcnt($orgID);
        }
        if ($chqClrngAccntID <= 0) {
            $chqClrngAccntID = get_DfltCheckAcnt($orgID);
        }
    }
    $fnccurid = getOrgFuncCurID($orgID);
    $result = get_One_MCFTrnsLines($docHdrID);
    while ($row = loc_db_fetch_array($result)) {
        $succs = false;
        $lnCntr++;
        $itmID = (int) $row[6];
        $itmDesc = $row[7];
        $crncyID = (int) $row[5];
        $qty = (float) $row[4];
        $price = (float) $row[3];
        $lineid = (float) $row[0];
        $itmType = $row[8];
        $trnsLnTyp = "";
        $trnsSrc = "SYS";
        $isTrnsPymnt = false;
        if ($doctype == "WITHDRAWAL" || $doctype == "LOAN_PYMNT") {
            if ($qty >= 0) {
                $srcCageID = (int) $row[9];
                $srcStoreID = (int) $row[10];
                $srcInvAcntID = (int) $row[13];
                $srcItmState = $row[11];
            } else {
                $destCageID = (int) $row[9];
                $destStoreID = (int) $row[10];
                $destInvAcntID = (int) $row[13];
                $destItmState = $row[11];
            }
        } else {
            if ($qty < 0) {
                $srcCageID = (int) $row[9];
                $srcStoreID = (int) $row[10];
                $srcInvAcntID = (int) $row[13];
                $srcItmState = $row[11];
            } else {
                $destCageID = (int) $row[9];
                $destStoreID = (int) $row[10];
                $destInvAcntID = (int) $row[13];
                $destItmState = $row[11];
            }
        }
        $srcVltCageNm = getGnrlRecNm("inv.inv_itm_subinventories", "subinv_id", "subinv_name", $srcStoreID) . " (" .
                getGnrlRecNm("inv.inv_shelf", "line_id", "shelve_name", $srcCageID) . ")";
        $destVltCageNm = getGnrlRecNm("inv.inv_itm_subinventories", "subinv_id", "subinv_name", $destStoreID) . " (" .
                getGnrlRecNm("inv.inv_shelf", "line_id", "shelve_name", $destCageID) . ")";
        if ($itmID > 0) {
            $nwMsg = "";
            $succs = generateItmAccntng($fnccurid, $qty, $doctype, $docHdrID, $srcInvAcntID, $destInvAcntID, $lbltyAccntID, $expnsID, $cogsID, $salesRevID, $price, $crncyID, $entrdRate, $lineid, $dateStr, $docNum, $cstmrNm, $docDesc, $itmDesc, $itmType, $trnsLnTyp, $trnsSrc, $isTrnsPymnt, $srcVltCageNm, $destVltCageNm, $nwMsg, $onlyValidate);
            //echo $succs ? "YES0" : "NO0" . $nwMsg;
            if ($succs === false) {
                $errMsg .= $nwMsg;
                return $succs;
            }
        }
        if ($succs === false) {
            $errMsg .= $nwMsg;
            break;
        }
    }
    if ($lnCntr <= 0) {
        $succs = true;
    }
    if ($succs === true) {
        $succs = false;
        $lnCntr1 = 0;
        $result = getAccountTrnsChqs($docHdrID);
        while ($row = loc_db_fetch_array($result)) {
            $succs = false;
            $lnCntr1++;
            $clearedQty = 0;
            $unClearedQty = 0;
            $lienQty = 0;
            $chqType = $row[9];
            $lineid = (float) $row[0];
            $isSrcAcntBalsOK = true;
            $itmType = $chqType;
            $docTypPrfx = $trnsTypeABRV[findArryIdx($trnsTypes, $doctype)];
            $createdBy = (float) $row[17];
            $chqAmnt = ((float) $row[7]);
            $entrdRate = ((float) $row[15]);
            $entrdCrncyID = ((float) $row[13]);
            $chqNum = $row[6];
            $trnsLnTyp = "";
            $trnsSrc = "SYS";
            if ($chqType == "In-House") {
                $srcAccntID = (float) $row[16];
                $srcAcntTrnsID = (float) $row[22];
                $isSrcAcntBalsOK = createMCFAccntng($srcAcntTrnsID, $nwMsg);
                //echo $isSrcAcntBalsOK ? "YES-OK" : "NO-OK";
                if ($isSrcAcntBalsOK === false) {
                    $errMsg .= $nwMsg;
                }
            } else {
                $unClearedQty = ((float) $row[7]) * ((float) $row[15]);
            }
            if ($lbltyAccntID > 0 && $chqClrngAccntID > 0 && $isSrcAcntBalsOK === true/* && $usrID == $createdBy */) {
                $succs = generateChqAccntng($docHdrID, $doctype, $fnccurid, $lbltyAccntID, $chqClrngAccntID, $chqAmnt, $entrdCrncyID, $entrdRate, $lineid, $dateStr, $docNum, $cstmrNm, $docDesc, $chqNum, $chqType, $trnsLnTyp, $trnsSrc, $nwMsg, $onlyValidate);
                //echo $succs ? "YES1" : "NO1";
            }
            if ($succs === false) {
                $errMsg .= $nwMsg;
                break;
            }
        }
        //$lnCntr would have set it to true already
        if ($lnCntr1 <= 0) {
            $succs = true;
        }
        $lnCntr += $lnCntr1;
    }
    if ($lnCntr <= 0) {
        //$chqClrngAccntID or ach clearing account must be varried based on transaction Type (Cheques/Standig Order/Transfers)
        $unClearedQty = 0;
        $lienQty = 0;
        $succs = false;
        $clearedQty = $trnsAmntTtl;
        $lineid = $acntTrnsID;
        $itmType = "Services";
        if ($doctype == "") {
            $docTypPrfx = "BATCH";
        } else {
            $docTypPrfx = $trnsTypeABRV[findArryIdx($trnsTypes, $doctype)];
        }
        $trnsLnTyp = "";
        $trnsSrc = "SYS";
        $entrdCrncyID = $docEntrdCrncyID;
        $entrdRate = $docEntrdRate;
        //$errMsg .= "entrdCrncyID:" . $entrdCrncyID . ":lbltyAccntID:" . $lbltyAccntID . ":chqClrngAccntID:" . $chqClrngAccntID . ":fnccurid:" . $fnccurid;
        if ($lbltyAccntID > 0 && $chqClrngAccntID > 0) {
            $succs = generateChqAccntng($docHdrID, $doctype, $fnccurid, $lbltyAccntID, $chqClrngAccntID, $trnsAmntTtl, $entrdCrncyID, $entrdRate, $lineid, $dateStr, $docNum, $cstmrNm, $docDesc, $docNum, $doctype, $trnsLnTyp, $trnsSrc, $nwMsg, $onlyValidate);
            //echo $succs ? "YES2" : "NO2";
            if ($succs === false) {
                $errMsg .= $nwMsg;
            }
        }
    }
    //echo $errMsg . "<br/>";
    //$a=1/0;
    return $succs;
}

function createMCFAccntng($docHdrID, &$errMsg) {
    global $orgID;
    global $usrID;
    global $trnsTypes;
    global $trnsTypeABRV;

    $dateStr = getFrmtdDB_Date_time();
    $doctype = "";
    $docNum = "";
    $cstmrNm = "";
    $docDesc = "";
    $rsltAcc = get_One_MCFTrnsHdr($docHdrID);
    $entrdRate = 0;
    $srcStoreID = -1;
    //Get from one logged on
    $srcCageID = -1;
    $destStoreID = -1;
    $destCageID = -1;
    $srcInvAcntID = -1;
    $destInvAcntID = -1;
    $lbltyAccntID = -1;
    $cogsID = -1;
    $salesRevID = -1;
    $expnsID = -1;
    $itmState = "Issuable";
    $succs = false;
    $chqClrngAccntID = -1;
    $trnsAmntTtl = 0;
    $acntTrnsID = -1;
    $lnCntr = 0;
    $docEntrdCrncyID = -1;
    $miscTrnsBalsGlAcntID = -1;
    $miscSubType = "";
    $docEntrdRate = 0;
    $errMsg = "";
    $nwMsg = "";
    $detaildTrnsDesc = "";
    $dbtAccntIncrsOrDcrs = "";
    //$mapdCrncyID = (int) getGnrlRecNm("mcf.mcf_currencies", "crncy_id", "mapped_lov_crncy_id", $crncyID);
    if ($rwAcc = loc_db_fetch_array($rsltAcc)) {
        $doctype = $rwAcc[3];
        $docNum = $rwAcc[20];
        $cstmrNm = $rwAcc[9];
        $docDesc = $rwAcc[4];
        $entrdRate = (float) 1;
        $acntTrnsID = (float) $rwAcc[0];
        $trnsAmntTtl = (float) $rwAcc[5];
        $docEntrdCrncyID = (float) $rwAcc[23];
        $docEntrdRate = (float) $rwAcc[24];
        if ($doctype == "WITHDRAWAL" || $doctype == "LOAN_PYMNT") {
            $lbltyAccntID = (int) $rwAcc[21]; //was 22
            $chqClrngAccntID = (int) $rwAcc[29];
        } else {
            $lbltyAccntID = (int) $rwAcc[21];
            $chqClrngAccntID = (int) $rwAcc[29];
        }
        if ($lbltyAccntID <= 0) {
            $lbltyAccntID = get_DfltSalesLbltyAcnt($orgID);
        }
        if ($chqClrngAccntID <= 0) {
            $chqClrngAccntID = get_DfltCheckAcnt($orgID);
        }
        $miscSubType = $rwAcc[50];
        $miscTrnsBalsGlAcntID = (int) $rwAcc[51];
        $detaildTrnsDesc = "Balancing Leg:" . $docDesc;
        $dbtAccntIncrsOrDcrs = $rwAcc[52];
    }
    $fnccurid = getOrgFuncCurID($orgID);
    $result = get_One_MCFTrnsLines($docHdrID);
    deleteDocGLInfcLns($docHdrID, $doctype);
    rvrsImprtdIntrfcTrns($docHdrID, $doctype);
    while ($row = loc_db_fetch_array($result)) {
        $succs = false;
        $lnCntr++;
        $itmID = (int) $row[6];
        $itmDesc = $row[7];
        $crncyID = (int) $row[5];
        $qty = (float) $row[4];
        $price = (float) $row[3];
        $lineid = (float) $row[0];
        $itmType = $row[8];
        $trnsLnTyp = "";
        $trnsSrc = "SYS";
        $isTrnsPymnt = false;

        if ($doctype == "WITHDRAWAL" || $doctype == "LOAN_PYMNT") {
            if ($qty >= 0) {
                $srcCageID = (int) $row[9];
                $srcStoreID = (int) $row[10];
                $srcInvAcntID = (int) $row[13];
                $srcItmState = $row[11];
            } else {
                $destCageID = (int) $row[9];
                $destStoreID = (int) $row[10];
                $destInvAcntID = (int) $row[13];
                $destItmState = $row[11];
            }
        } else {
            if ($qty < 0) {
                $srcCageID = (int) $row[9];
                $srcStoreID = (int) $row[10];
                $srcInvAcntID = (int) $row[13];
                $srcItmState = $row[11];
            } else {
                $destCageID = (int) $row[9];
                $destStoreID = (int) $row[10];
                $destInvAcntID = (int) $row[13];
                $destItmState = $row[11];
            }
        }
        $srcVltCageNm = getGnrlRecNm("inv.inv_itm_subinventories", "subinv_id", "subinv_name", $srcStoreID) . " (" .
                getGnrlRecNm("inv.inv_shelf", "line_id", "shelve_name", $srcCageID) . ")";
        $destVltCageNm = getGnrlRecNm("inv.inv_itm_subinventories", "subinv_id", "subinv_name", $destStoreID) . " (" .
                getGnrlRecNm("inv.inv_shelf", "line_id", "shelve_name", $destCageID) . ")";
        if ($itmID > 0) {
            $nwMsg = "";
            $succs = generateItmAccntng($fnccurid, $qty, $doctype, $docHdrID, $srcInvAcntID, $destInvAcntID, $lbltyAccntID, $expnsID, $cogsID, $salesRevID, $price, $crncyID, $entrdRate, $lineid, $dateStr, $docNum, $cstmrNm, $docDesc, $itmDesc, $itmType, $trnsLnTyp, $trnsSrc, $isTrnsPymnt, $srcVltCageNm, $destVltCageNm, $nwMsg);
            //echo $succs ? "YES0" : "NO0";
            if ($succs === false) {
                $errMsg .= $nwMsg;
                return $succs;
            }
        }
        if ($succs === false) {
            $errMsg .= $nwMsg;
            break;
        }
    }
    if ($lnCntr <= 0) {
        $succs = true;
    }
    if ($succs === true) {
        $succs = false;
        $lnCntr1 = 0;
        $result = getAccountTrnsChqs($docHdrID);
        while ($row = loc_db_fetch_array($result)) {
            $succs = false;
            $lnCntr1++;
            $clearedQty = 0;
            $unClearedQty = 0;
            $lienQty = 0;
            $chqType = $row[9];
            $lineid = (float) $row[0];
            $isSrcAcntBalsOK = true;
            $itmType = $chqType;
            $docTypPrfx = $trnsTypeABRV[findArryIdx($trnsTypes, $doctype)];
            $createdBy = (float) $row[17];
            $chqAmnt = ((float) $row[7]);
            $entrdRate = ((float) $row[15]);
            $entrdCrncyID = ((float) $row[13]);
            $chqNum = $row[6];
            $trnsLnTyp = "";
            $trnsSrc = "SYS";
            if ($chqType == "In-House") {
                $srcAccntID = (float) $row[16];
                $srcAcntTrnsID = (float) $row[22];
                $isSrcAcntBalsOK = createMCFAccntng($srcAcntTrnsID, $nwMsg);
                //echo $isSrcAcntBalsOK ? "YES-OK" : "NO-OK";
                if ($isSrcAcntBalsOK === false) {
                    $errMsg .= $nwMsg;
                }
            } else {
                $unClearedQty = ((float) $row[7]) * ((float) $row[15]);
            }
            if ($lbltyAccntID > 0 && $chqClrngAccntID > 0 && $isSrcAcntBalsOK === true/* && $usrID == $createdBy */) {
                $succs = generateChqAccntng($docHdrID, $doctype, $fnccurid, $lbltyAccntID, $chqClrngAccntID, $chqAmnt, $entrdCrncyID, $entrdRate, $lineid, $dateStr, $docNum, $cstmrNm, $docDesc, $chqNum, $chqType, $trnsLnTyp, $trnsSrc, $nwMsg);
                //echo $succs ? "YES1" : "NO1";
            }
            if ($succs === false) {
                $errMsg .= $nwMsg;
                break;
            }
        }
        //$lnCntr would have set it to true already
        if ($lnCntr1 <= 0) {
            $succs = true;
        }
        $lnCntr += $lnCntr1;
    }
    if ($lnCntr <= 0) {
        //$chqClrngAccntID or ach clearing account must be varried based on transaction Type (Cheques/Standig Order/Transfers)
        $unClearedQty = 0;
        $lienQty = 0;
        $succs = false;
        $clearedQty = $trnsAmntTtl;
        $lineid = $acntTrnsID;
        $itmType = "Services";
        $docTypPrfx = $trnsTypeABRV[findArryIdx($trnsTypes, $doctype)];
        $trnsLnTyp = "";
        $trnsSrc = "SYS";
        $entrdCrncyID = $docEntrdCrncyID;
        $entrdRate = $docEntrdRate;
        $crdtAccntIncrsOrDcrs = "D";
        if ($doctype == "DEPOSIT" || $doctype == "LOAN_REPAY") {
            $crdtAccntIncrsOrDcrs = "I";
        }
        if ($miscSubType == "MISC_TRANS" && $miscTrnsBalsGlAcntID > 0) {
            $succs = generateGnrlAccntng($fnccurid, $trnsAmntTtl, $doctype, $docHdrID, $miscTrnsBalsGlAcntID, $dbtAccntIncrsOrDcrs, $lbltyAccntID, $crdtAccntIncrsOrDcrs, $entrdCrncyID, $lineid, $dateStr, $detaildTrnsDesc, $trnsLnTyp, $trnsSrc, $nwMsg);
            //echo $succs ? "YES2" : "NO2";
            if ($succs === false) {
                $errMsg .= $nwMsg;
            }
        } else if ($lbltyAccntID > 0 && $chqClrngAccntID > 0) {
            $succs = generateChqAccntng($docHdrID, $doctype, $fnccurid, $lbltyAccntID, $chqClrngAccntID, $trnsAmntTtl, $entrdCrncyID, $entrdRate, $lineid, $dateStr, $docNum, $cstmrNm, $docDesc, $docNum, $doctype, $trnsLnTyp, $trnsSrc, $nwMsg);
            //echo $succs ? "YES2" : "NO2";
            if ($succs === false) {
                $errMsg .= $nwMsg;
            }
        }
    }
    //echo $errMsg . "<br/>";
    //$a=1/0;
    return $succs;
}

function sendToMCFGLIntrfcMnl($accntID, $incrsDcrs, $amount, $trns_date, $trns_desc, $crncy_id, $dateStr, $srcDocTyp, $srcDocID, $srcDocLnID, $trnsLnTyp, $trnsSrc, $entrdAMnt, $entrdCrncyID, $acctCrncyAmnt, $acctCrncyID, $funcCrncyRate, $acntCrncyRate) {
    $netamnt = dbtOrCrdtAccntMultiplier($accntID, $incrsDcrs) * $amount;
    $py_dbt_ln = -1;
    $py_crdt_ln = -1;
    if (dbtOrCrdtAccnt($accntID, $incrsDcrs) == "Debit") {
        if ($py_dbt_ln <= 0) {
            createMCFTrnsGLIntFcLn($accntID, $trns_desc, $amount, $trns_date, $crncy_id, 0, $netamnt, $srcDocTyp, $srcDocID, $srcDocLnID, $dateStr, $trnsLnTyp, $trnsSrc, $entrdAMnt, $entrdCrncyID, $acctCrncyAmnt, $acctCrncyID, $funcCrncyRate, $acntCrncyRate);
        }
    } else {
        if ($py_crdt_ln <= 0) {
            createMCFTrnsGLIntFcLn($accntID, $trns_desc, 0, $trns_date, $crncy_id, $amount, $netamnt, $srcDocTyp, $srcDocID, $srcDocLnID, $dateStr, $trnsLnTyp, $trnsSrc, $entrdAMnt, $entrdCrncyID, $acctCrncyAmnt, $acctCrncyID, $funcCrncyRate, $acntCrncyRate);
        }
    }
    return true;
}

function generateItmAccntng($fnccurid, $qty, $doctype, $docHdrID, $srcInvAcntID, $destInvAcntID, $lbltyAccntID, $expnsID, $cogsID, $salesRevID, $price, $entrdCrncyID, $entrdRate, $lineid, $dateStr, $docNum, $cstmrNm, $docDesc, $itmDesc, $itmType, $trnsLnTyp, $trnsSrc, $isTrnsPymnt, $srcVltCageNm, $destVltCageNm, &$errMsg, $onlyValidate = "NO") {
    global $gnrlTrnsDteDMYHMS;
    if ($cstmrNm == "") {
        $cstmrNm = "Unspecified Customer";
    }
    if ($docDesc == "") {
        $docDesc = "Unstated Purpose";
    }
    $succs = false;
//Get Lst Exchage Rates and A
    $acctCrncyAmnt = 0;
    $acctCrncyID = -1;
    $funcCrncyRate = round(get_LtstExchRate($entrdCrncyID, $fnccurid, $dateStr), 15);
    $acntCrncyRate = 1.00;
    $entrdAMnt = round($qty * $price, 2);
    if (($doctype == "DEPOSIT" || $doctype == "LOAN_REPAY") && $qty < 0) {
        $destInvAcntID = $srcInvAcntID;
    }
    if (($doctype == "WITHDRAWAL" || $doctype == "LOAN_PYMNT") && $qty < 0) {
        $srcInvAcntID = $destInvAcntID;
    }
    if ($doctype == "DEPOSIT" && $qty > 0) {
        if ($destInvAcntID <= 0 || $lbltyAccntID <= 0) {
            $errMsg .= "Destination Cage Account and Customer Liability Accounts must be setup!<br/>";
            return false;
        }
    } else if ($doctype == "WITHDRAWAL" || $doctype == "LOAN_PYMNT") {
        if ($srcInvAcntID <= 0) {
            $errMsg .= "Source Cage Account must be setup!<br/>";
            return false;
        }
        if ((($doctype == "WITHDRAWAL" || $doctype == "LOAN_PYMNT") && $lbltyAccntID <= 0)) {
            $errMsg .= "Customer Liability Accounts must be setup!<br/>";
            return false;
        }
    }
    if (strpos($itmType, "Vault") !== FALSE) {
        $ttlCstPrice = $qty * $price * $funcCrncyRate;
        if ($doctype == "DEPOSIT") {
            $acctCrncyID = (int) getGnrlRecNm("accb.accb_chart_of_accnts", "accnt_id", "crncy_id", $destInvAcntID);
            if ($entrdCrncyID !== $acctCrncyID) {
                $errMsg .= "Transaction Currency ID is not the same as the Destination Cage Account!<br/><br/>";
                return false;
            }
        } else if ($doctype == "WITHDRAWAL" || $doctype == "LOAN_PYMNT") {
            $acctCrncyID = (int) getGnrlRecNm("accb.accb_chart_of_accnts", "accnt_id", "crncy_id", $srcInvAcntID);
            if ($entrdCrncyID !== $acctCrncyID) {
                $errMsg .= "Transaction Currency ID is not the same as the Source Cage Account!<br/><br/>";
                return false;
            }
        }
    }
    if ($onlyValidate == "YES") {
        return true;
    }
    if (strpos($itmType, "Vault") !== FALSE) {
        $ttlCstPrice = $qty * $price * $funcCrncyRate;
        if ($doctype == "DEPOSIT") {
            $acctCrncyID = (int) getGnrlRecNm("accb.accb_chart_of_accnts", "accnt_id", "crncy_id", $destInvAcntID);
            $acntCrncyRate = round(1, 15);
            $acctCrncyAmnt = round($entrdAMnt * $acntCrncyRate, 2);
            $succs = sendToMCFGLIntrfcMnl($destInvAcntID, "I", $ttlCstPrice, $gnrlTrnsDteDMYHMS, $docNum . "-Deposit of " . $itmDesc . " into " . $destVltCageNm . " by $cstmrNm (" . $docDesc . "-" . $doctype . ")", $entrdCrncyID, $dateStr, $doctype, $docHdrID, $lineid, $trnsLnTyp, $trnsSrc, $entrdAMnt, $entrdCrncyID, $acctCrncyAmnt, $acctCrncyID, $funcCrncyRate, $acntCrncyRate);
            if (!$succs) {
                return $succs;
            } else {
                $acctCrncyID = (int) getGnrlRecNm("accb.accb_chart_of_accnts", "accnt_id", "crncy_id", $lbltyAccntID);
                $acntCrncyRate = round(get_LtstExchRate($entrdCrncyID, $acctCrncyID, $gnrlTrnsDteDMYHMS), 15);
                $acctCrncyAmnt = round($entrdAMnt * $acntCrncyRate, 2);
                $succs = sendToMCFGLIntrfcMnl(
                        $lbltyAccntID, "I", $ttlCstPrice, $gnrlTrnsDteDMYHMS, $docNum . "-Deposit of " . $itmDesc . " into " . $destVltCageNm . " by $cstmrNm (" . $docDesc . "-" . $doctype . ")", $entrdCrncyID, $dateStr, $doctype, $docHdrID, $lineid, $trnsLnTyp, $trnsSrc, $entrdAMnt, $entrdCrncyID, $acctCrncyAmnt, $acctCrncyID, $funcCrncyRate, $acntCrncyRate);
                if (!$succs) {
                    return $succs;
                }
            }
        } else if ($doctype == "WITHDRAWAL" || $doctype == "LOAN_PYMNT") {
            $acctCrncyID = (int) getGnrlRecNm("accb.accb_chart_of_accnts", "accnt_id", "crncy_id", $srcInvAcntID);
            $acntCrncyRate = round(1, 15);
            $acctCrncyAmnt = round($entrdAMnt * $acntCrncyRate, 2);
            if ($fnccurid != $acctCrncyID) {
                $fncCurBals = getAccntLstDailyNetBals($srcInvAcntID, $gnrlTrnsDteDMYHMS);
                $accCurBals = getAccntCrncyLstDlyNetBals($srcInvAcntID, $gnrlTrnsDteDMYHMS);
                if ($accCurBals <= 0) {
                    $accCurBals = $fncCurBals;
                }
                $avrgStckCost = round($fncCurBals / $accCurBals, 15);
                $ttlCstPrice = $qty * $price * $avrgStckCost;
                $funcCrncyRate = $avrgStckCost;
            }
            $succs = sendToMCFGLIntrfcMnl(
                    $srcInvAcntID, "D", $ttlCstPrice, $gnrlTrnsDteDMYHMS, $docNum . "-Removal of " . $itmDesc . " from " . $srcVltCageNm . " (" . $docDesc . "-" . $doctype . ")", $entrdCrncyID, $dateStr, $doctype, $docHdrID, $lineid, $trnsLnTyp, $trnsSrc, $entrdAMnt, $entrdCrncyID, $acctCrncyAmnt, $acctCrncyID, $funcCrncyRate, $acntCrncyRate);
            if (!$succs) {
                return $succs;
            } else {
                if ($doctype == "WITHDRAWAL" || $doctype == "LOAN_PYMNT") {
                    $acctCrncyID = (int) getGnrlRecNm("accb.accb_chart_of_accnts", "accnt_id", "crncy_id", $lbltyAccntID);
                    $acntCrncyRate = round(get_LtstExchRate($entrdCrncyID, $acctCrncyID, $dateStr), 15);
                    $acctCrncyAmnt = round($entrdAMnt * $acntCrncyRate, 2);
                    $succs = sendToMCFGLIntrfcMnl(
                            $lbltyAccntID, "D", $ttlCstPrice, $gnrlTrnsDteDMYHMS, $docNum . "-Withdrawal of " . $itmDesc . " from " . $srcVltCageNm . " by $cstmrNm (" . $docDesc . "-" . $doctype . ")", $entrdCrncyID, $dateStr, $doctype, $docHdrID, $lineid, $trnsLnTyp, $trnsSrc, $entrdAMnt, $entrdCrncyID, $acctCrncyAmnt, $acctCrncyID, $funcCrncyRate, $acntCrncyRate);
                    if (!$succs) {
                        return $succs;
                    }
                }
            }
        }
    }
    return $succs;
}

function generateChqAccntng($docHdrID, $doctype, $fnccurid, $cstmrlbltyAccntID, $chqDepAccntID, $chqAmnt, $entrdCrncyID, $entrdRate, $lineid, $dateStr, $docNum, $cstmrNm, $docDesc, $chqNum, $chqType, $trnsLnTyp, $trnsSrc, &$errMsg, $onlyValidate = "NO") {
    global $gnrlTrnsDteDMYHMS;
    if ($cstmrNm == "") {
        $cstmrNm = "Unspecified Customer";
    }
    if ($docDesc == "") {
        $docDesc = "Unstated Purpose";
    }
    $succs = true;
    //Get Lst Exchage Rates and A
    $acctCrncyAmnt = 0;
    $acctCrncyID = -1;
    $funcCrncyRate = round(get_LtstExchRate($entrdCrncyID, $fnccurid, $gnrlTrnsDteDMYHMS), 15);
    $acntCrncyRate = 1.00;
    $entrdAMnt = round($chqAmnt, 2);
    if ($chqDepAccntID <= 0 || $cstmrlbltyAccntID <= 0) {
        $errMsg .= "Cheque Deposit and Customer Liability Accounts must be setup!<br/>";
        return false;
    }


    if ($onlyValidate == "YES") {
        return true;
    }
    if ($doctype == "DEPOSIT") {
        $ttlCstPrice = $entrdAMnt * $funcCrncyRate;
        $acctCrncyID = (int) getGnrlRecNm("accb.accb_chart_of_accnts", "accnt_id", "crncy_id", $chqDepAccntID);
        $acntCrncyRate = round(get_LtstExchRate($entrdCrncyID, $acctCrncyID, $gnrlTrnsDteDMYHMS), 15);
        $acctCrncyAmnt = round($entrdAMnt * $acntCrncyRate, 2);
        $succs = sendToMCFGLIntrfcMnl($chqDepAccntID, "I", $ttlCstPrice, $gnrlTrnsDteDMYHMS, $docNum . "-Deposit of " . $chqType . "-" . $chqNum . " by $cstmrNm (" . $docDesc . "-" . $doctype . ")", $entrdCrncyID, $dateStr, $doctype, $docHdrID, $lineid, $trnsLnTyp, $trnsSrc, $entrdAMnt, $entrdCrncyID, $acctCrncyAmnt, $acctCrncyID, $funcCrncyRate, $acntCrncyRate);
        //$errMsg .= "entrdCrncyID:" . $entrdCrncyID . ":lbltyAccntID:" . $lbltyAccntID . ":chqClrngAccntID:" . $chqClrngAccntID . ":fnccurid:" . $fnccurid;
        //echo $succs?"YES-A11":"NO-A11";
        if (!$succs) {
            return $succs;
        } else {
            $acctCrncyID = (int) getGnrlRecNm("accb.accb_chart_of_accnts", "accnt_id", "crncy_id", $cstmrlbltyAccntID);
            $acntCrncyRate = round(get_LtstExchRate($entrdCrncyID, $acctCrncyID, $gnrlTrnsDteDMYHMS), 15);
            $acctCrncyAmnt = round($entrdAMnt * $acntCrncyRate, 2);
            $succs = sendToMCFGLIntrfcMnl(
                    $cstmrlbltyAccntID, "I", $ttlCstPrice, $gnrlTrnsDteDMYHMS, $docNum . "-Deposit of " . $chqType . "-" . $chqNum . " by $cstmrNm (" . $docDesc . "-" . $doctype . ")", $entrdCrncyID, $dateStr, $doctype, $docHdrID, $lineid, $trnsLnTyp, $trnsSrc, $entrdAMnt, $entrdCrncyID, $acctCrncyAmnt, $acctCrncyID, $funcCrncyRate, $acntCrncyRate);
            //echo $succs?"YES-A12":"NO-A12";
            if (!$succs) {
                return $succs;
            }
        }
    } else if ($doctype == "WITHDRAWAL" || $doctype == "LOAN_PYMNT") {
        $ttlCstPrice = $entrdAMnt * $funcCrncyRate;
        $acctCrncyID = (int) getGnrlRecNm("accb.accb_chart_of_accnts", "accnt_id", "crncy_id", $chqDepAccntID);
        $acntCrncyRate = round(get_LtstExchRate($entrdCrncyID, $acctCrncyID, $gnrlTrnsDteDMYHMS), 15);
        $acctCrncyAmnt = round($entrdAMnt * $acntCrncyRate, 2);
        $succs = sendToMCFGLIntrfcMnl($chqDepAccntID, "D", $ttlCstPrice, $gnrlTrnsDteDMYHMS, $docNum . "-Withdrawal of " . $chqType . "-" . $chqNum . " by $cstmrNm (" . $docDesc . "-" . $doctype . ")", $entrdCrncyID, $dateStr, $doctype, $docHdrID, $lineid, $trnsLnTyp, $trnsSrc, $entrdAMnt, $entrdCrncyID, $acctCrncyAmnt, $acctCrncyID, $funcCrncyRate, $acntCrncyRate);
        //echo $succs?"YES-B11":"NO-B11";
        if (!$succs) {
            return $succs;
        } else {
            $acctCrncyID = (int) getGnrlRecNm("accb.accb_chart_of_accnts", "accnt_id", "crncy_id", $cstmrlbltyAccntID);
            $acntCrncyRate = round(get_LtstExchRate($entrdCrncyID, $acctCrncyID, $gnrlTrnsDteDMYHMS), 15);
            $acctCrncyAmnt = round($entrdAMnt * $acntCrncyRate, 2);
            $succs = sendToMCFGLIntrfcMnl(
                    $cstmrlbltyAccntID, "D", $ttlCstPrice, $gnrlTrnsDteDMYHMS, $docNum . "-Withdrawal of " . $chqType . "-" . $chqNum . " by $cstmrNm (" . $docDesc . "-" . $doctype . ")", $entrdCrncyID, $dateStr, $doctype, $docHdrID, $lineid, $trnsLnTyp, $trnsSrc, $entrdAMnt, $entrdCrncyID, $acctCrncyAmnt, $acctCrncyID, $funcCrncyRate, $acntCrncyRate);
            //echo $succs?"YES-B12":"NO-B12";
            if (!$succs) {
                return $succs;
            }
        }
    }
    return $succs;
}

function generateClearChqAccntng($docHdrID, $doctype, $fnccurid, $cashAccntID, $chqDepAccntID, $chqAmnt, $entrdCrncyID, $entrdRate, $lineid, $dateStr, $docNum, $cstmrNm, $docDesc, $chqNum, $chqType, $trnsLnTyp, $trnsSrc, &$errMsg) {
    global $gnrlTrnsDteDMYHMS;
    if ($cstmrNm == "") {
        $cstmrNm = "Unspecified Customer";
    }
    if ($docDesc == "") {
        $docDesc = "Unstated Purpose";
    }
    $succs = true;
    //Get Lst Exchage Rates and A
    $acctCrncyAmnt = 0;
    $acctCrncyID = -1;
    $funcCrncyRate = round(get_LtstExchRate($entrdCrncyID, $fnccurid, $gnrlTrnsDteDMYHMS), 15);
    $acntCrncyRate = 1.00;
    $entrdAMnt = round($chqAmnt, 2);
    if ($chqDepAccntID <= 0 || $cashAccntID <= 0) {
        $errMsg .= "Cheque Deposit and Cash Accounts must be setup!<br/>";
        return false;
    }
    if ($doctype == "DEPOSIT") {
        $ttlCstPrice = $entrdAMnt * $funcCrncyRate;
        $acctCrncyID = (int) getGnrlRecNm("accb.accb_chart_of_accnts", "accnt_id", "crncy_id", $chqDepAccntID);
        $acntCrncyRate = round(get_LtstExchRate($entrdCrncyID, $acctCrncyID, $gnrlTrnsDteDMYHMS), 15);
        $acctCrncyAmnt = round($entrdAMnt * $acntCrncyRate, 2);
        $succs = sendToMCFGLIntrfcMnl($chqDepAccntID, "D", $ttlCstPrice, $gnrlTrnsDteDMYHMS, $docNum . "-Cheque Clearing for Deposit of " . $chqType . "-" . $chqNum . " by $cstmrNm (" . $docDesc . "-" . $doctype . ")", $entrdCrncyID, $dateStr, $doctype, $docHdrID, $lineid, $trnsLnTyp, $trnsSrc, $entrdAMnt, $entrdCrncyID, $acctCrncyAmnt, $acctCrncyID, $funcCrncyRate, $acntCrncyRate);
        //$errMsg .= "entrdCrncyID:" . $entrdCrncyID . ":lbltyAccntID:" . $lbltyAccntID . ":chqClrngAccntID:" . $chqClrngAccntID . ":fnccurid:" . $fnccurid;
        //echo $succs?"YES-A11":"NO-A11";
        if (!$succs) {
            return $succs;
        } else {
            $acctCrncyID = (int) getGnrlRecNm("accb.accb_chart_of_accnts", "accnt_id", "crncy_id", $cashAccntID);
            $acntCrncyRate = round(get_LtstExchRate($entrdCrncyID, $acctCrncyID, $gnrlTrnsDteDMYHMS), 15);
            $acctCrncyAmnt = round($entrdAMnt * $acntCrncyRate, 2);
            $succs = sendToMCFGLIntrfcMnl(
                    $cashAccntID, "I", $ttlCstPrice, $gnrlTrnsDteDMYHMS, $docNum . "-Cheque Clearing for Deposit of " . $chqType . "-" . $chqNum . " by $cstmrNm (" . $docDesc . "-" . $doctype . ")", $entrdCrncyID, $dateStr, $doctype, $docHdrID, $lineid, $trnsLnTyp, $trnsSrc, $entrdAMnt, $entrdCrncyID, $acctCrncyAmnt, $acctCrncyID, $funcCrncyRate, $acntCrncyRate);
            //echo $succs?"YES-A12":"NO-A12";
            if (!$succs) {
                return $succs;
            }
        }
    } else if ($doctype == "WITHDRAWAL" || $doctype == "LOAN_PYMNT") {
        $ttlCstPrice = $entrdAMnt * $funcCrncyRate;
        $acctCrncyID = (int) getGnrlRecNm("accb.accb_chart_of_accnts", "accnt_id", "crncy_id", $chqDepAccntID);
        $acntCrncyRate = round(get_LtstExchRate($entrdCrncyID, $acctCrncyID, $gnrlTrnsDteDMYHMS), 15);
        $acctCrncyAmnt = round($entrdAMnt * $acntCrncyRate, 2);
        $succs = sendToMCFGLIntrfcMnl($chqDepAccntID, "I", $ttlCstPrice, $gnrlTrnsDteDMYHMS, $docNum . "-Cheque Clearing for Withdrawal of " . $chqType . "-" . $chqNum . " by $cstmrNm (" . $docDesc . "-" . $doctype . ")", $entrdCrncyID, $dateStr, $doctype, $docHdrID, $lineid, $trnsLnTyp, $trnsSrc, $entrdAMnt, $entrdCrncyID, $acctCrncyAmnt, $acctCrncyID, $funcCrncyRate, $acntCrncyRate);
        //echo $succs?"YES-B11":"NO-B11";
        if (!$succs) {
            return $succs;
        } else {
            $acctCrncyID = (int) getGnrlRecNm("accb.accb_chart_of_accnts", "accnt_id", "crncy_id", $cashAccntID);
            $acntCrncyRate = round(get_LtstExchRate($entrdCrncyID, $acctCrncyID, $gnrlTrnsDteDMYHMS), 15);
            $acctCrncyAmnt = round($entrdAMnt * $acntCrncyRate, 2);
            $succs = sendToMCFGLIntrfcMnl(
                    $cashAccntID, "D", $ttlCstPrice, $gnrlTrnsDteDMYHMS, $docNum . "-Cheque Clearing for Withdrawal of " . $chqType . "-" . $chqNum . " by $cstmrNm (" . $docDesc . "-" . $doctype . ")", $entrdCrncyID, $dateStr, $doctype, $docHdrID, $lineid, $trnsLnTyp, $trnsSrc, $entrdAMnt, $entrdCrncyID, $acctCrncyAmnt, $acctCrncyID, $funcCrncyRate, $acntCrncyRate);
            //echo $succs?"YES-B12":"NO-B12";
            if (!$succs) {
                return $succs;
            }
        }
    }
    return $succs;
}

function getTrnsCshAmnt($docHdrID) {
    $selSQL = "SELECT (amount - amount_cash) net_amnt
        FROM mcf.mcf_cust_account_transactions a
        WHERE a.acct_trns_id=" . $docHdrID . " 
      and ((SELECT count(z.cash_analysis_id) FROM mcf.mcf_account_trns_cash_analysis z WHERE a.acct_trns_id=z.acct_trns_id and z.qty!=0)<=0 
            and (Select count(x.trns_cheque_id) from mcf.mcf_cust_account_trns_cheques x where x.acct_trns_id=a.acct_trns_id)<=0 
            and a.loan_rpmnt_src_acct_id<=0
            and a.lnkd_mscl_trns_id<=0)";
    $result1 = executeSQLNoParams($selSQL);
    while ($row = loc_db_fetch_array($result1)) {
        return round((float) $row[0], 2);
    }
    return 0;
}

function getTrnsCshVsTllrDiff($docHdrID) {
    $selSQL = "SELECT (amount
        - COALESCE((Select sum(y.amount*y.accnt_crncy_rate) from mcf.mcf_cust_account_trns_cheques y where y.acct_trns_id=a.acct_trns_id),0) 
        - COALESCE((Select sum(y.unit_value*y.qty) from mcf.mcf_account_trns_cash_analysis y where y.acct_trns_id=a.acct_trns_id),0)) net_amnt
        FROM mcf.mcf_cust_account_transactions a
        WHERE a.acct_trns_id=" . $docHdrID . " 
      and ((SELECT count(z.cash_analysis_id) FROM mcf.mcf_account_trns_cash_analysis z WHERE a.acct_trns_id=z.acct_trns_id and z.qty!=0)>0 
            or (Select count(x.trns_cheque_id) from mcf.mcf_cust_account_trns_cheques x where x.acct_trns_id=a.acct_trns_id)>0)";
    $result1 = executeSQLNoParams($selSQL);
    while ($row = loc_db_fetch_array($result1)) {
        return round((float) $row[0], 2);
    }
    return 0;
}

function getDenomAmtVsTllrBals($docHdrID) {
    $selSQL = "Select count(1) from 
        (Select a.acct_trns_id, a.unit_value, a.qty, 
CASE WHEN c.trns_type='WITHDRAWAL' OR c.trns_type = 'REPAYMENT' OR c.trns_type = 'INVESTMENT' THEN
-1*a.qty*a.unit_value
ELSE
a.qty*a.unit_value
END amount,
vms.get_ltst_stock_bals1(
    a.vault_id,
    a.cage_shelve_id,
    b.vault_item_id,
    a.item_state,
    to_char(now(),'YYYY-MM-DD')) itm_bals_b4
    FROM mcf.mcf_account_trns_cash_analysis a,
    mcf.mcf_currency_denominations b,
    mcf.mcf_cust_account_transactions c
Where a.acct_trns_id=" . $docHdrID . "
and a.acct_trns_id=c.acct_trns_id
and a.denomination_id=b.crncy_denom_id
and a.qty!=0) tbl1
WHERE (tbl1.amount+tbl1.itm_bals_b4)<0";
    $result1 = executeSQLNoParams($selSQL);
    while ($row = loc_db_fetch_array($result1)) {
        return (int) $row[0];
    }
    return 0;
}

function isMCFTrnsQtyVld($docHdrID, &$errMsg) {
    global $gnrlTrnsDteDMYHMS;
    $dateStr = $gnrlTrnsDteDMYHMS;
    $errMsg = "";
    if (getTrnsCshVsTllrDiff($docHdrID) != 0) {
        $succs = false;
        $errMsg .= "Transaction Total doesn't agree with Cash or Cheque Breakdown Details!<br/>";
        return $succs;
    }
    if (getDenomAmtVsTllrBals($docHdrID) > 0) {
        $succs = false;
        $errMsg .= "Denominational Qty can't be supported by current Teller's Balance!<br/>";
        return $succs;
    }
    if (getTrnsCshAmnt($docHdrID) != 0) {
        $succs = false;
        $errMsg .= "Cash and Cheque Breakdown Details cannot be both Empty for such Transaction Type!<br/>";
        return $succs;
    }
    return true;
    /* $doctype = "";
      $docNum = "";
      $cstmrNm = "";
      $docDesc = "";
      $srcStoreID = -1; //Get from one logged on
      $srcCageID = -1;
      $destStoreID = -1;
      $destCageID = -1;
      $srcInvAcntID = -1;
      $destInvAcntID = -1;
      $lbltyAccntID = -1;
      $srcItmState = "Issuable";
      $destItmState = "Issuable";
      $rsltAcc = get_One_MCFTrnsHdr($docHdrID);
      if ($rwAcc = loc_db_fetch_array($rsltAcc)) {
      $doctype = $rwAcc[3];
      $docNum = $rwAcc[20];
      $cstmrNm = $rwAcc[9];
      $docDesc = $rwAcc[4];
      if ($doctype == "WITHDRAWAL" || $doctype == "LOAN_PYMNT") {
      $lbltyAccntID = (int) $rwAcc[22];
      } else {
      $lbltyAccntID = (int) $rwAcc[21];
      }
      }
      //echo $srcCageID . ":" . $destCageID . ":" . $prsnid . ":" . $docHdrID;
      $succs = false;
      $lnCntr = 0;
      $result = get_One_MCFTrnsLines($docHdrID);
      while ($row = loc_db_fetch_array($result)) {
      $lnCntr++;
      $cshAnlsID = (float) $row[0];
      $itemID = (int) $row[6];
      $isdlvrd = false;
      $qty = (float) $row[4];
      if ($doctype == "WITHDRAWAL" || $doctype == "LOAN_PYMNT") {
      if ($qty >= 0) {
      $srcCageID = (int) $row[9];
      $srcStoreID = (int) $row[10];
      $srcInvAcntID = (int) $row[13];
      $srcItmState = $row[11];
      } else {
      $destCageID = (int) $row[9];
      $destStoreID = (int) $row[10];
      $destInvAcntID = (int) $row[13];
      $destItmState = $row[11];
      }
      } else {
      if ($qty < 0) {
      $srcCageID = (int) $row[9];
      $srcStoreID = (int) $row[10];
      $srcInvAcntID = (int) $row[13];
      $srcItmState = $row[11];
      } else {
      $destCageID = (int) $row[9];
      $destStoreID = (int) $row[10];
      $destInvAcntID = (int) $row[13];
      $destItmState = $row[11];
      }
      }

      if ($itemID > 0 && $destStoreID > 0 && $destCageID > 0 && $isdlvrd == false) {
      $succs = isStockQtyAvlbl($destStoreID, $destCageID, $itemID, $destItmState, abs($qty), $dateStr, "I");
      //echo "<br/>12succs;$succs;ItemID;".$itemID.":".$srcCageID . ":" .$srcStoreID.":". $destCageID . ":" . $destStoreID . ":DocType:" . $doctype." END<br/>";
      }
      if ($itemID > 0 && $srcStoreID > 0 && $srcCageID && $isdlvrd == false) {
      $succs = isStockQtyAvlbl($srcStoreID, $srcCageID, $itemID, $srcItmState, abs($qty), $dateStr, "D");
      }
      if ($succs === false) {
      break;
      }
      }
      if ($lnCntr <= 0) {
      $succs = true;
      }
      //echo "<br/>34succs;$succs;ItemID;".$itemID.":".$srcCageID . ":" .$srcStoreID.":". $destCageID . ":" . $destStoreID . ":DocType:" . $doctype." END<br/>";
      if ($succs == true) {
      $succs = validateMCFAccntng($docHdrID, $errMsg);
      //echo "<br/>56succs;$succs;ItemID;".$itemID.":".$srcCageID . ":" .$srcStoreID.":". $destCageID . ":" . $destStoreID . ":DocType:" . $doctype." END<br/>";
      }
      return $succs; */
}

function updateMCFStckBals($docHdrID) {
    global $trnsTypes;
    global $trnsTypeABRV;
    global $prsnid;
    global $gnrlTrnsDteDMYHMS;
    $errMsg = "";
    $dateStr = $gnrlTrnsDteDMYHMS; //getFrmtdDB_Date_time();
    $doctype = "";
    $docNum = "";
    $cstmrNm = "";
    $docDesc = "";
    $rsltAcc = get_One_MCFTrnsHdr($docHdrID);
    if ($rwAcc = loc_db_fetch_array($rsltAcc)) {
        $doctype = $rwAcc[3];
        $docNum = $rwAcc[20];
        $cstmrNm = $rwAcc[9];
        $docDesc = $rwAcc[4];
    }
    $succs = FALSE;
    $lnCntr = 0;
    $result = get_One_MCFTrnsLines($docHdrID);
    while ($row = loc_db_fetch_array($result)) {
        $itemID = (int) $row[6];
        $isdlvrd = false;
        $srcStoreID = -1; //Get from one logged on
        $srcCageID = -1;
        $destStoreID = -1;
        $destCageID = -1;
        $srcInvAcntID = -1;
        $destInvAcntID = -1;
        $cageMngrID = (float) $row[14];
        $srcItmState = "Issuable";
        $destItmState = "Issuable";

        /* if ($doctype == "WITHDRAWAL" || $doctype == "LOAN_PYMNT") {
          $srcCageID = (int) $row[9];
          $srcStoreID = (int) $row[10];
          $srcInvAcntID = (int) $row[13];
          $srcItmState = $row[11];
          } else {
          $destCageID = (int) $row[9];
          $destStoreID = (int) $row[10];
          $destInvAcntID = (int) $row[13];
          $destItmState = $row[11];
          } */
        $qty = (float) $row[4];
        if ($doctype == "WITHDRAWAL" || $doctype == "LOAN_PYMNT") {
            if ($qty >= 0) {
                $srcCageID = (int) $row[9];
                $srcStoreID = (int) $row[10];
                $srcInvAcntID = (int) $row[13];
                $srcItmState = $row[11];
            } else {
                $destCageID = (int) $row[9];
                $destStoreID = (int) $row[10];
                $destInvAcntID = (int) $row[13];
                $destItmState = $row[11];
            }
        } else {
            if ($qty < 0) {
                $srcCageID = (int) $row[9];
                $srcStoreID = (int) $row[10];
                $srcInvAcntID = (int) $row[13];
                $srcItmState = $row[11];
            } else {
                $destCageID = (int) $row[9];
                $destStoreID = (int) $row[10];
                $destInvAcntID = (int) $row[13];
                $destItmState = $row[11];
            }
        }
        $lineid = (float) $row[0];
        $itmType = $row[8];
        $docTypPrfx = $trnsTypeABRV[findArryIdx($trnsTypes, $doctype)];
//echo "prsnid:".$prsnid.":cageMngrID:".$cageMngrID.":doctype:".$doctype;
//$abs=1/0;
//return FALSE;
        /* if ($prsnid != $cageMngrID) {
          $succs = FALSE;
          return FALSE;
          } */
        if ($itemID > 0 && $destStoreID > 0 && $destCageID > 0 && $isdlvrd == false/* && $prsnid == $cageMngrID */) {
            $succs = updateItemBalances($destStoreID, $destCageID, $itemID, $itmType, $destItmState, abs($qty), "I", $docTypPrfx, $lineid, $dateStr, $errMsg);
        }
        if ($itemID > 0 && $srcStoreID > 0 && $srcCageID && $isdlvrd == false /* && $prsnid == $cageMngrID */) {
            $succs = updateItemBalances($srcStoreID, $srcCageID, $itemID, $itmType, $srcItmState, abs($qty), "D", $docTypPrfx, $lineid, $dateStr, $errMsg);
        }
        /* if ($doctype == "DEPOSIT" || $doctype == "LOAN_REPAY") {
          } else if ($doctype == "WITHDRAWAL" || $doctype == "LOAN_PYMNT") {
          } */
    }
    if ($lnCntr <= 0) {
        $succs = true;
    }
    return $succs;
}

function isBulkTrnsQtyVld($docHdrID, &$errMsg) {
    global $gnrlTrnsDteDMYHMS;
    $dateStr = $gnrlTrnsDteDMYHMS; //getFrmtdDB_Date_time();
    $doctype = "";
    $docNum = "";
    $cstmrNm = "";
    $docDesc = "";
    $srcStoreID = -1; //Get from one logged on
    $srcCageID = -1;
    $destStoreID = -1;
    $destCageID = -1;
    $srcInvAcntID = -1;
    $destInvAcntID = -1;
    $lbltyAccntID = -1;
    $srcItmState = "Issuable";
    $destItmState = "Issuable";
    $crncyID = -1;
    $docTTlAmnt = 0;
    $shdDoCashless = 0;
    $rsltAcc = get_One_BatchTrnsHdr($docHdrID);
    if ($rwAcc = loc_db_fetch_array($rsltAcc)) {
        $docNum = $rwAcc[1];
        $cstmrNm = $rwAcc[7];
        $docDesc = $rwAcc[5];
        $crncyID = (int) $rwAcc[22];
        $docTTlAmnt = (float) $rwAcc[9];
        $shdDoCashless = (int) $rwAcc[32];
    }
    //echo $srcCageID . ":" . $destCageID . ":" . $prsnid . ":" . $docHdrID;
    $succs = false;
    $lnCntr = 0;
    if ($shdDoCashless == 0) {
        $result = get_One_BulkTrnsTllrLns($docHdrID);
        while ($row = loc_db_fetch_array($result)) {
            $lnCntr++;
            $srcStoreID = -1; //Get from one logged on
            $srcCageID = -1;
            $destStoreID = -1;
            $destCageID = -1;
            $srcInvAcntID = -1;
            $srcItmState = "Issuable";
            $destItmState = "Issuable";
            $itemID = (int) $row[6];
            $isdlvrd = false;
            $qty = (float) $row[4];
            $lnCrncyID = (int) $row[15];
            if ($lnCrncyID !== $crncyID) {
                $succs = false;
                break;
            }
            if ($qty < 0) {
                $srcCageID = (int) $row[9];
                $srcStoreID = (int) $row[10];
                $srcInvAcntID = (int) $row[13];
                $srcItmState = $row[11];
            } else {
                $destCageID = (int) $row[9];
                $destStoreID = (int) $row[10];
                $destInvAcntID = (int) $row[13];
                $destItmState = $row[11];
            }
//echo "<br/>ItemID;".$itemID.":".$srcCageID . ":" .$srcStoreID.":". $destCageID . ":" . $destStoreID . ":DocType:" . $doctype." END<br/>";
            if ($itemID > 0 && $destStoreID > 0 && $destCageID > 0 && $isdlvrd == false) {
                $succs = isStockQtyAvlbl($destStoreID, $destCageID, $itemID, $destItmState, abs($qty), $dateStr, "I");
            }
            if ($itemID > 0 && $srcStoreID > 0 && $srcCageID && $isdlvrd == false) {
                $succs = isStockQtyAvlbl($srcStoreID, $srcCageID, $itemID, $srcItmState, abs($qty), $dateStr, "D");
            }
            /* if ($docTTlAmnt >= 0) {
              } else if ($docTTlAmnt < 0) {
              } */
            if ($succs === false) {
                break;
            }
        }
    }
    if ($lnCntr <= 0) {
        $succs = true;
    }
    $errMsg = "";
    if ($succs === true) {
        $chqPlsTllr = getBulkAcntTrnsChqsSum($docHdrID, -1) + get_BulkCashAnalysisTtlAmount($docHdrID, $crncyID);
        $chqPlsCash = getBulkAcntTrnsChqsSum($docHdrID, -1) + get_One_BlkCashTrnsSum($docHdrID);
        $chqPlsCashMisc = getBulkAcntTrnsChqsSum($docHdrID, -1) + get_One_BlkCashTrnsSum($docHdrID) + get_One_BlkMiscTrnsSum($docHdrID);
        //$errMsg = "chqPlsCash:" . $chqPlsCash . ":chqPlsTllr:" . $chqPlsTllr . ":docTTlAmnt:" . $docTTlAmnt;
        if ($shdDoCashless == 0) {
            if (!($docTTlAmnt == $chqPlsTllr && $docTTlAmnt == $chqPlsCash)) {
                $succs = false;
                $errMsg = "<span style=\"color:red;\">Please Re-Enter Teller Till Total:" . $chqPlsTllr . "</span>";
                $delSQL = "DELETE FROM mcf.mcf_account_trns_cash_analysis WHERE bulk_trns_hdr_id=" . $docHdrID . " and mcf.get_blktrns_status(" . $docHdrID . ") IN ('Not Submitted','Withdrawn','Rejected')";
                execUpdtInsSQL($delSQL);
            }
        } else {
            if (!($docTTlAmnt == $chqPlsCashMisc)) {
                $errMsg = "<span style=\"color:red;\">Transaction Header Total Amount must agree with Transaction Lines Total!</span>";
                $succs = false;
            }
        }
        $verifyStr = verifyBatchTrns($docHdrID);
        $errMsg .= $verifyStr;
// . " succs:" . $succs . ":" . $chqPlsTllr . ":" . $chqPlsCash . ":" . $docTTlAmnt;
        if (strpos($verifyStr, "Successfully Verified a Total of 1 Batch") === FALSE) {
            $succs = false;
        }
    }
//echo $lnCntr."|succs:".$succs.":".true."|".false;
//$abc=1/0;
    return $succs;
}

/* function updateBulkStckBals($docHdrID) {
  global $trnsTypes;
  global $trnsTypeABRV;
  global $prsnid;
  global $gnrlTrnsDteDMYHMS;
  $errMsg = "";
  $dateStr = $gnrlTrnsDteDMYHMS; //getFrmtdDB_Date_time();
  $doctype = "";
  $docNum = "";
  $cstmrNm = "";
  $docDesc = "";
  $crncyID = -1;
  $docTTlAmnt = 0;
  $rsltAcc = get_One_BatchTrnsHdr($docHdrID);
  if ($rwAcc = loc_db_fetch_array($rsltAcc)) {
  $docNum = $rwAcc[1];
  $cstmrNm = $rwAcc[7];
  $docDesc = $rwAcc[5];
  $crncyID = (int) $rwAcc[22];
  $docTTlAmnt = (float) $rwAcc[9];
  }
  $succs = FALSE;
  $lnCntr = 0;
  //CALL POSTGRE FUNCTION TO ACCOUNT FOR ALL CASH AND CHEQUE ENTRIES BE4 UPDATE TELLER'S CAGE
  $result = get_One_BulkTrnsTllrLns($docHdrID);
  while ($row = loc_db_fetch_array($result)) {
  $itemID = (int) $row[6];
  $isdlvrd = false;
  $srcStoreID = -1; //Get from one logged on
  $srcCageID = -1;
  $destStoreID = -1;
  $destCageID = -1;
  $srcInvAcntID = -1;
  $destInvAcntID = -1;
  $cageMngrID = (float) $row[14];
  $srcItmState = "Issuable";
  $destItmState = "Issuable";
  $qty = (float) $row[4];
  $lineid = (float) $row[0];
  $itmType = $row[8];
  $docTypPrfx = $trnsTypeABRV[findArryIdx($trnsTypes, $doctype)];
  if ($docTTlAmnt < 0) {
  if ($qty >= 0) {
  $srcCageID = (int) $row[9];
  $srcStoreID = (int) $row[10];
  $srcInvAcntID = (int) $row[13];
  $srcItmState = $row[11];
  } else {
  $destCageID = (int) $row[9];
  $destStoreID = (int) $row[10];
  $destInvAcntID = (int) $row[13];
  $destItmState = $row[11];
  }
  } else {
  if ($qty < 0) {
  $srcCageID = (int) $row[9];
  $srcStoreID = (int) $row[10];
  $srcInvAcntID = (int) $row[13];
  $srcItmState = $row[11];
  } else {
  $destCageID = (int) $row[9];
  $destStoreID = (int) $row[10];
  $destInvAcntID = (int) $row[13];
  $destItmState = $row[11];
  }
  }
  //echo "prsnid:".$prsnid.":cageMngrID:".$cageMngrID.":doctype:".$doctype;
  //$abs=1/0;
  //return FALSE;
  if ($prsnid != $cageMngrID) {
  $succs = FALSE;
  return FALSE;
  }
  if ($docTTlAmnt >= 0) {
  if ($itemID > 0 && $destStoreID > 0 && $destCageID > 0 && $isdlvrd == false && $prsnid == $cageMngrID) {
  $succs = updateItemBalances($destStoreID, $destCageID, $itemID, $itmType, $destItmState, $qty, "I", $docTypPrfx, $lineid,
  $dateStr, $errMsg);
  }
  } else if ($docTTlAmnt < 0) {
  if ($itemID > 0 && $srcStoreID > 0 && $srcCageID && $isdlvrd == false && $prsnid == $cageMngrID) {
  $succs = updateItemBalances($srcStoreID, $srcCageID, $itemID, $itmType, $srcItmState, $qty, "D", $docTypPrfx, $lineid,
  $dateStr, $errMsg);
  }
  }
  }
  if ($lnCntr <= 0) {
  $succs = true;
  }
  return $succs;
  } */

function updateItemBalances($vltID, $cageID, $itemID, $itmType, $itmState, $qnty, $actTyp, $docTypPrfx, $docLnID, $dateStr, &$errMsg) {
    $succs = false;
    if (strpos($itmType, "Inventory") !== FALSE || strpos($itmType, "Fixed Assets") !== FALSE || strpos($itmType, "Vault") !== FALSE) {
        if ($actTyp == "D") {
            $succs = postStockQty($vltID, $cageID, $itemID, $itmState, -1 * $qnty, $dateStr, $docTypPrfx . "-" . $docLnID) > 0 ? true : false;
        } else {
            $succs = postStockQty($vltID, $cageID, $itemID, $itmState, $qnty, $dateStr, $docTypPrfx . "-" . $docLnID) > 0 ? true : false;
        }
    }
    if (!$succs) {
        $errMsg = "Item ID:" . $itemID . ":Qty:" . $qnty . " will cause negative balance!";
    }
    return $succs;
}

function postStockQty($vltID, $cageID, $itemID, $itmState, $totQty, $trnsDate, $src_trsID) {
    $affctd = 0;
    $dailybalID = getStockDailyBalsID($vltID, $cageID, $itemID, $itmState, $trnsDate);
    $lstTotBals = getStockLstTotBls($vltID, $cageID, $itemID, $itmState, $trnsDate);
    if ($dailybalID <= 0) {
        if (($lstTotBals + $totQty) >= 0) {
            $affctd += createStckDailyBals($vltID, $cageID, $itemID, $itmState, $lstTotBals, $trnsDate, ",");
            $affctd += updtStckDailyBals($vltID, $cageID, $itemID, $itmState, $totQty, $trnsDate, "Do", $src_trsID);
        }
    } else {
        if (($lstTotBals + $totQty) >= 0) {
            $affctd += updtStckDailyBals($vltID, $cageID, $itemID, $itmState, $totQty, $trnsDate, "Do", $src_trsID);
        }
    }
    return $affctd;
}

function isStockQtyAvlbl($vltID, $cageID, $itemID, $itmState, $totQty, $trnsDate, $actionType) {
    $lstTotBals = getStockLstTotBls($vltID, $cageID, $itemID, $itmState, $trnsDate);
//echo $itemID . ":" . $itmState . ":" . $lstTotBals . ":" . $totQty;
    if ($actionType == "I") {
        if (((float) $lstTotBals + (float) $totQty) < 0) {
            return false;
        }
    } else {
        if (((float) $lstTotBals - (float) $totQty) < 0) {
            return false;
        }
    }
    return true;
}

function getStockDailyBalsID($vltID, $cageID, $itemID, $itmState, $balsDate) {
    if ($balsDate != "") {
        $balsDate = cnvrtDMYTmToYMDTm($balsDate);
    }
    if (strlen($balsDate) > 10) {
        $balsDate = substr($balsDate, 0, 10);
    }
    $strSql = "SELECT a.bal_id " .
            "FROM vms.vms_stock_daily_bals a " .
            "WHERE(to_timestamp(a.bals_date,'YYYY-MM-DD') =  to_timestamp('" . $balsDate .
            "','YYYY-MM-DD') and a.store_vault_id = " . $vltID .
            " and a.cage_shelve_id= " . $cageID .
            " and a.item_id=" . $itemID .
            " and a.itm_state_clsfctn = '" . loc_db_escape_string($itmState) .
            "')";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -1;
}

function getStockLstTotBls($vltID, $cageID, $itemID, $itmState, $balsDate) {
    if ($balsDate != "") {
        $balsDate = cnvrtDMYTmToYMDTm($balsDate);
    }
    if (strlen($balsDate) > 10) {
        $balsDate = substr($balsDate, 0, 10);
    }
    $strSql = "SELECT COALESCE(a.stock_tot_qty,0) " .
            "FROM vms.vms_stock_daily_bals a " .
            "WHERE(to_timestamp(a.bals_date,'YYYY-MM-DD') <=  to_timestamp('" . $balsDate .
            "','YYYY-MM-DD') and store_vault_id = " . $vltID .
            " and cage_shelve_id = " . $cageID .
            " and item_id = " . $itemID .
            " and itm_state_clsfctn = '" . loc_db_escape_string($itmState) .
            "') ORDER BY to_timestamp(a.bals_date,'YYYY-MM-DD') DESC LIMIT 1 OFFSET 0";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return 0.00;
}

function createStckDailyBals($vltID, $cageID, $itemID, $itmState, $totQty, $balsDate, $src_trnsID) {
    global $usrID;
    if ($balsDate != "") {
        $balsDate = cnvrtDMYTmToYMDTm($balsDate);
    }
    if (strlen($balsDate) > 10) {
        $balsDate = substr($balsDate, 0, 10);
    }
    $dateStr = getDB_Date_time();
    $itmUntVal = (float) getGnrlRecNm("inv.inv_itm_list", "item_id", "orgnl_selling_price", $itemID);
    $insSQL = "INSERT INTO vms.vms_stock_daily_bals(
            store_vault_id, cage_shelve_id, item_id, stock_tot_qty, 
            source_trns_ids, bals_date, created_by, creation_date, last_update_by, 
            last_update_date, itm_state_clsfctn, unit_value) " .
            "VALUES (" . $vltID .
            ", " . $cageID .
            ", " . $itemID .
            ", " . $totQty .
            ", '" . $src_trnsID .
            "', '" . $balsDate .
            "', " . $usrID .
            ", '" . $dateStr .
            "', " . $usrID .
            ", '" . $dateStr .
            "', '" . loc_db_escape_string($itmState) .
            "'," . $itmUntVal . ")";
    return execUpdtInsSQL($insSQL);
}

function updtStckDailyBals($vltID, $cageID, $itemID, $itmState, $totQty, $balsDate, $act_typ, $src_trnsID) {
    global $usrID;
    if ($balsDate != "") {
        $balsDate = cnvrtDMYTmToYMDTm($balsDate);
    }
    if (strlen($balsDate) > 10) {
        $balsDate = substr($balsDate, 0, 10);
    }
    $dateStr = getDB_Date_time();
    $updtSQL = "";
    if ($act_typ == "Undo") {
        $updtSQL = "UPDATE vms.vms_stock_daily_bals " .
                "SET last_update_by = " . $usrID .
                ", last_update_date = '" . $dateStr .
                "', stock_tot_qty = COALESCE(stock_tot_qty,0) - " . $totQty .
                ", source_trns_ids = COALESCE(replace(source_trns_ids, '," . $src_trnsID . ",', ','),',')" .
                " WHERE (to_timestamp(bals_date,'YYYY-MM-DD') >=  to_timestamp('" . $balsDate .
                "','YYYY-MM-DD') and store_vault_id = " . $vltID .
                " and cage_shelve_id = " . $cageID .
                " and item_id = " . $itemID .
                " and itm_state_clsfctn = '" . loc_db_escape_string($itmState) .
                "')";
    } else {
        $updtSQL = "UPDATE vms.vms_stock_daily_bals " .
                "SET last_update_by = " . $usrID .
                ", last_update_date = '" . $dateStr .
                "', stock_tot_qty = COALESCE(stock_tot_qty,0) + " . $totQty .
                ", source_trns_ids = COALESCE(source_trns_ids,',') || '" . $src_trnsID . ",'" .
                " WHERE (to_timestamp(bals_date,'YYYY-MM-DD') >=  to_timestamp('" . $balsDate .
                "','YYYY-MM-DD') and store_vault_id = " . $vltID .
                " and cage_shelve_id = " . $cageID .
                " and item_id = " . $itemID .
                " and itm_state_clsfctn = '" . loc_db_escape_string($itmState) .
                "')";
    }
    return execUpdtInsSQL($updtSQL);
}

function isMCFTrnsCstmrAcntAmtVld($docHdrID) {
    global $gnrlTrnsDteDMYHMS;
    $dateStr = $gnrlTrnsDteDMYHMS; //getFrmtdDB_Date_time();
    $doctype = "";
    $docNum = "";
    $cstmrNm = "";
    $docDesc = "";
    $accntID = -1;
    $trnsAmntTtl = 0;
    $trnsAmntCash = 0;
    $clearedQty = 0;
    $unClearedQty = 0;
    $lienQty = 0;
    $succs = false;
    $rsltAcc = get_One_MCFTrnsHdr($docHdrID);
    if ($rwAcc = loc_db_fetch_array($rsltAcc)) {
        $doctype = $rwAcc[3];
        $docNum = $rwAcc[20];
        $cstmrNm = $rwAcc[9];
        $docDesc = $rwAcc[4];
        $accntID = (float) $rwAcc[2];
        $trnsAmntTtl = (float) $rwAcc[5] * (float) $rwAcc[24];
        $trnsAmntCash = (float) $rwAcc[5] * (float) $rwAcc[54];
    }
    $lnCntr = 0;
    /* if ($trnsAmntCash != 0) {
      $unClearedQty = 0;
      $lienQty = 0;
      $succs = false;
      $clearedQty = $trnsAmntCash - $lienQty;
      //echo $accntID . ":RAW POSTING:" . $clearedQty . ":" . $unClearedQty . "<br/>";
      if ($doctype == "DEPOSIT" || $doctype == "LOAN_REPAY") {
      if ($accntID > 0) {
      $succs = true; //isCstmrAcntAmtAvlbl($accntID, $clearedQty, $unClearedQty, $lienQty, $dateStr, "I");
      }
      } else if ($doctype == "WITHDRAWAL" || $doctype == "LOAN_PYMNT") {
      if ($accntID > 0) {
      $succs = isCstmrAcntAmtAvlbl($accntID, $clearedQty, $unClearedQty, $lienQty, $dateStr, "D");
      if ($succs === false) {
      return $succs;
      }
      }
      }
      $lnCntr++;
      } */
    /* $result = get_One_MCFTrnsLines($docHdrID);
      while ($row = loc_db_fetch_array($result)) {
      $succs = false;
      $lnCntr++;
      $isdlvrd = false;
      $clearedQty = ((float) $row[4] * (float) $row[3] * (float) $row[12]) - $lienQty;
      if ($doctype == "DEPOSIT") {
      if ($accntID > 0 && $isdlvrd == false) {
      $succs = true; //isCstmrAcntAmtAvlbl($accntID, $clearedQty, $unClearedQty, $lienQty, $dateStr, "I");
      }
      } else if ($doctype == "WITHDRAWAL" || $doctype == "LOAN_PYMNT") {
      if ($accntID > 0 && $isdlvrd == false) {
      $succs = isCstmrAcntAmtAvlbl($accntID, $clearedQty, $unClearedQty, $lienQty, $dateStr, "D");
      }
      }
      if ($succs === false) {
      break;
      }
      } */
    if ($lnCntr <= 0) {
        $succs = true;
    }
    //echo $succs;
    //$abc=1/0;
    /* if ($doctype == "DEPOSIT" && $succs == true) {
      $lnCntr1 = 0;
      $result = getAccountTrnsChqs($docHdrID);
      while ($row = loc_db_fetch_array($result)) {
      $lnCntr1++;
      $clearedQty = 0;
      $unClearedQty = 0;
      $lienQty = 0;
      $chqType = $row[9];
      $succs = false;
      $isSrcAcntBalsOK = true;
      $srcAccntID = -1;
      //echo $chqType;
      //$abc = 1 / 0;
      if ($chqType == "In-House") {
      $srcAccntID = (float) $row[16];
      $srcAcntTrnsID = (float) $row[22];
      $isSrcAcntBalsOK = isMCFTrnsCstmrAcntAmtVld($srcAcntTrnsID);
      if ($isSrcAcntBalsOK === false) {
      $unClearedQty = ((float) $row[7]) * ((float) $row[15]);
      } else {
      $clearedQty = (((float) $row[7]) * ((float) $row[15])) - $lienQty;
      }
      } else {
      $unClearedQty = ((float) $row[7]) * ((float) $row[15]);
      }
      //echo $accntID . ":" . $chqType . ":" . $clearedQty . ":" . $unClearedQty . ":isSrcAcntBalsOK:" . ($isSrcAcntBalsOK ? "YES" : "NO") . "<br/>";
      if ($accntID > 0 && $srcAccntID > 0 && $isSrcAcntBalsOK == true) {
      $succs = isCstmrAcntAmtAvlbl($accntID, $clearedQty, $unClearedQty, $lienQty, $dateStr, "I");
      } else if ($accntID > 0 && $isSrcAcntBalsOK == true) {
      $succs = true;
      }
      if ($succs === false) {
      break;
      }
      }
      //$lnCntr would have set it to true already
      if ($lnCntr1 <= 0) {
      $succs = true;
      }
      $lnCntr += $lnCntr1;
      } */
    if ($lnCntr <= 0) {
        $unClearedQty = 0;
        $lienQty = 0;
        $succs = false;
        $clearedQty = $trnsAmntTtl - $lienQty;
        //echo $accntID . ":RAW POSTING:" . $clearedQty . ":" . $unClearedQty . "<br/>";
        if ($doctype == "DEPOSIT" || $doctype == "LOAN_REPAY") {
            if ($accntID > 0) {
                $succs = true; //isCstmrAcntAmtAvlbl($accntID, $clearedQty, $unClearedQty, $lienQty, $dateStr, "I");
            }
        } else if ($doctype == "WITHDRAWAL" || $doctype == "LOAN_PYMNT" || $doctype == "INVESTMENT") {
            if ($accntID > 0) {
                $succs = isCstmrAcntAmtAvlbl($accntID, $clearedQty, $unClearedQty, $lienQty, $dateStr, "D");
            }
        }
    }
    //echo $succs ? "YES" : "NO";
    //$abc = 1 / 0;
    //$succs = false;
    return $succs;
}

function updateMCFCstmrAcntBals($docHdrID) {
    global $trnsTypes;
    global $trnsTypeABRV;
    global $usrID;
    global $gnrlTrnsDteDMYHMS;
    $errMsg = "";
    $dateStr = $gnrlTrnsDteDMYHMS; //getFrmtdDB_Date_time();
    $doctype = "";
    $docNum = "";
    $cstmrNm = "";
    $docDesc = "";
    $accntID = -1;
    $rsltAcc = get_One_MCFTrnsHdr($docHdrID);
    $clearedQty = 0;
    $unClearedQty = 0;
    $lienQty = 0;
    $trnsAmntTtl = 0;
    $acntTrnsID = -1;
    if ($rwAcc = loc_db_fetch_array($rsltAcc)) {
        $doctype = $rwAcc[3];
        $docNum = $rwAcc[20];
        $cstmrNm = $rwAcc[9];
        $docDesc = $rwAcc[4];
        $accntID = (float) $rwAcc[2];
        $acntTrnsID = (float) $rwAcc[0];
        $trnsAmntTtl = (float) $rwAcc[5] * (float) $rwAcc[24];
    }
    $succs = false;
    $lnCntr = 0;
    $result = get_One_MCFTrnsLines($docHdrID);
    while ($row = loc_db_fetch_array($result)) {
        $isdlvrd = false;
        $succs = false;
        $lnCntr++;
        $clearedQty = ((float) $row[4] * (float) $row[3] * (float) $row[12]) - $lienQty;
        $lineid = (float) $row[0];
        $itmType = $row[8];
        $docTypPrfx = $trnsTypeABRV[findArryIdx($trnsTypes, $doctype)];
        if ($doctype == "DEPOSIT" || $doctype == "LOAN_REPAY") {
            if ($accntID > 0 && $isdlvrd == false) {
                $succs = updateCstmrAcntBalances($accntID, $clearedQty, $unClearedQty, $lienQty, $itmType, "I", $docTypPrfx, $lineid, $dateStr, $errMsg);
            }
        } else if ($doctype == "WITHDRAWAL" || $doctype == "LOAN_PYMNT") {
            if ($accntID > 0 && $isdlvrd == false) {
                $succs = updateCstmrAcntBalances($accntID, $clearedQty, $unClearedQty, $lienQty, $itmType, "D", $docTypPrfx, $lineid, $dateStr, $errMsg);
            }
        }
        if ($succs === false) {
            break;
        }
    }
    if ($lnCntr <= 0) {
        $succs = true;
    }
    if ($doctype == "DEPOSIT" && $succs == true) {
        $lnCntr1 = 0;
        $result = getAccountTrnsChqs($docHdrID);
        while ($row = loc_db_fetch_array($result)) {
            $succs = false;
            $lnCntr1++;
            $clearedQty = 0;
            $unClearedQty = 0;
            $lienQty = 0;
            $chqType = $row[9];
            $lineid = (float) $row[0];
            $isSrcAcntBalsOK = true;
            $itmType = $chqType;
            $docTypPrfx = $trnsTypeABRV[findArryIdx($trnsTypes, $doctype)];
            $createdBy = (float) $row[17];
            if ($chqType == "In-House") {
                $srcAccntID = (float) $row[16];
                $srcAcntTrnsID = (float) $row[22];
                $isSrcAcntBalsOK = updateMCFCstmrAcntBals($srcAcntTrnsID);
                if ($isSrcAcntBalsOK === false) {
                    $unClearedQty = ((float) $row[7]) * ((float) $row[15]);
                } else {
                    $clearedQty = (((float) $row[7]) * ((float) $row[15])) - $lienQty;
                }
            } else {
                $unClearedQty = ((float) $row[7]) * ((float) $row[15]);
            }
            if ($accntID > 0 && $isSrcAcntBalsOK == true /* && $usrID == $createdBy */) {
                $succs = updateCstmrAcntBalances($accntID, $clearedQty, $unClearedQty, $lienQty, $itmType, "I", $docTypPrfx, $lineid, $dateStr, $errMsg);
            }
            if ($succs == false) {
                break;
            }
        }
        //$lnCntr would have set it to true already
        if ($lnCntr1 <= 0) {
            $succs = true;
        }
        $lnCntr += $lnCntr1;
    }
    if ($lnCntr <= 0) {
        $unClearedQty = 0;
        $lienQty = 0;
        $succs = false;
        $clearedQty = $trnsAmntTtl - $lienQty;
        $lineid = $acntTrnsID;
        $itmType = "Services";
        $docTypPrfx = $trnsTypeABRV[findArryIdx($trnsTypes, $doctype)];
        if ($doctype == "DEPOSIT" || $doctype == "LOAN_REPAY") {
            if ($accntID > 0) {
                $succs = updateCstmrAcntBalances($accntID, $clearedQty, $unClearedQty, $lienQty, $itmType, "I", $docTypPrfx, $lineid, $dateStr, $errMsg);
            }
        } else if ($doctype == "WITHDRAWAL" || $doctype == "LOAN_PYMNT") {
            if ($accntID > 0) {
                $succs = updateCstmrAcntBalances($accntID, $clearedQty, $unClearedQty, $lienQty, $itmType, "D", $docTypPrfx, $lineid, $dateStr, $errMsg);
            }
        }
    }
    return $succs;
}

function isClearChqTrnsAcntsAmtVld($docHdrID, $trnsChqID, $amntMltplier) {
    global $gnrlTrnsDteDMYHMS;
    $dateStr = $gnrlTrnsDteDMYHMS; //getFrmtdDB_Date_time();
    $doctype = "";
    $docNum = "";
    $cstmrNm = "";
    $docDesc = "";
    $accntID = -1;
    $rsltAcc = get_One_MCFTrnsHdr($docHdrID);
    if ($rwAcc = loc_db_fetch_array($rsltAcc)) {
        $doctype = $rwAcc[3];
        $docNum = $rwAcc[20];
        $cstmrNm = $rwAcc[9];
        $docDesc = $rwAcc[4];
        $accntID = (float) $rwAcc[2];
    }
    $succs = false;
    $clearedQty = 0;
    $unClearedQty = 0;
    $lienQty = 0;
//echo $accntID . ":" . $docHdrID . ":" . $doctype . ":" . $docNum;
    $lnCntr = 0;
    if ($doctype == "DEPOSIT") {
        $lnCntr = 0;
        $result = getAccountTrnsChqs($docHdrID, $trnsChqID);
        while ($row = loc_db_fetch_array($result)) {
            $succs = FALSE;
            $lnCntr++;
            $clearedQty = 0;
            $unClearedQty = 0;
            $lienQty = 0;
            $chqType = $row[9];
            $isSrcAcntBalsOK = TRUE;
            $chqAccntID = -1;
            if ($chqType == "In-House") {
                $chqAccntID = (float) $row[19];
                $clearedQty = ((float) $row[7]) * ((float) $row[15]) * $amntMltplier;
                $unClearedQty = -1 * ((float) $row[7]) * ((float) $row[15]) * $amntMltplier;
                $isSrcAcntBalsOK = isCstmrAcntAmtAvlbl($chqAccntID, $clearedQty, $unClearedQty, $lienQty, $dateStr, "D");
            } else {
                $clearedQty = ((float) $row[7]) * ((float) $row[15]) * $amntMltplier;
                $unClearedQty = -1 * ((float) $row[7]) * ((float) $row[15]) * $amntMltplier;
            }
            if ($accntID > 0 && $isSrcAcntBalsOK == TRUE) {
                $succs = isCstmrAcntAmtAvlbl($accntID, $clearedQty, $unClearedQty, $lienQty, $dateStr, "I");
            } else {
                $succs = $isSrcAcntBalsOK;
            }
        }
//echo $lnCntr;
//$abc=1/0;
        if ($lnCntr <= 0) {
            $succs = true;
        }
    }
    return $succs;
}

function clearChqTrnsCstmrAcntBals($docHdrID, $trnsChqID, $amntMltplier) {
    global $trnsTypes;
    global $trnsTypeABRV;
    global $gnrlTrnsDteDMYHMS;
    $errMsg = "";
    $dateStr = $gnrlTrnsDteDMYHMS; //getFrmtdDB_Date_time();
    $doctype = "";
    $docNum = "";
    $cstmrNm = "";
    $docDesc = "";
    $accntID = -1;
    $rsltAcc = get_One_MCFTrnsHdr($docHdrID);
    $clearedQty = 0;
    $unClearedQty = 0;
    $lienQty = 0;
    if ($rwAcc = loc_db_fetch_array($rsltAcc)) {
        $doctype = $rwAcc[3];
        $docNum = $rwAcc[20];
        $cstmrNm = $rwAcc[9];
        $docDesc = $rwAcc[4];
        $accntID = (float) $rwAcc[2];
    }
    $succs = false;
    $lnCntr = 0;
    if ($lnCntr <= 0) {
        $succs = true;
    }
    if ($doctype == "DEPOSIT" && $succs === true) {
        $lnCntr = 0;
        $result = getAccountTrnsChqs($docHdrID, $trnsChqID);
        while ($row = loc_db_fetch_array($result)) {
            $succs = false;
            $lnCntr++;
            $clearedQty = 0;
            $unClearedQty = 0;
            $lienQty = 0;
            $chqType = $row[9];
            $lineid = (float) $row[0];
            $isSrcAcntBalsOK = true;
            $itmType = $chqType;
            $docTypPrfx = $trnsTypeABRV[findArryIdx($trnsTypes, $doctype)];
            $createdBy = (float) $row[17];
            $chqAccntID = -1;
            if ($chqType == "In-House") {
                $chqAccntID = (float) $row[19];
                $clearedQty = ((float) $row[7]) * ((float) $row[15]) * $amntMltplier;
                $unClearedQty = -1 * ((float) $row[7]) * ((float) $row[15]) * $amntMltplier;
                $isSrcAcntBalsOK = isCstmrAcntAmtAvlbl($chqAccntID, $clearedQty, $unClearedQty, $lienQty, $dateStr, "D");
            } else {
                $clearedQty = ((float) $row[7]) * ((float) $row[15]) * $amntMltplier;
                $unClearedQty = -1 * ((float) $row[7]) * ((float) $row[15]) * $amntMltplier;
            }
            if ($accntID > 0 && $isSrcAcntBalsOK === true) {
                if ($chqAccntID > 0) {
                    $succs = updateCstmrAcntBalances($chqAccntID, $clearedQty, $unClearedQty, $lienQty, $itmType, "D", $docTypPrfx, $lineid, $dateStr, $errMsg);
                } else if ($chqType != "In-House") {
                    $succs = true;
                }
                if ($succs === true) {
                    $succs = updateCstmrAcntBalances($accntID, $clearedQty, $unClearedQty, $lienQty, $itmType, "I", $docTypPrfx, $lineid, $dateStr, $errMsg);
                }
            }
        }
        if ($lnCntr <= 0) {
            $succs = true;
        }
    }
    return $succs;
}

function createClearChqAccntng($docHdrID, $trnsChqID, $amntMltplier, &$errMsg) {
    global $orgID;
    global $usrID;
    global $trnsTypes;
    global $trnsTypeABRV;
    global $gnrlTrnsDteDMYHMS;

    $dateStr = $gnrlTrnsDteDMYHMS; //getFrmtdDB_Date_time();
    $doctype = "";
    $docNum = "";
    $cstmrNm = "";
    $docDesc = "";
    $rsltAcc = get_One_MCFTrnsHdr($docHdrID);
    $entrdRate = 0;
    //$elctrncCashAccntID = -1;
    $succs = false;
    $chqClrngAccntID = -1;
    $trnsAmntTtl = 0;
    $acntTrnsID = -1;
    $lnCntr = 0;
    $docEntrdCrncyID = -1;
    $docEntrdRate = 0;
    $errMsg .= "";
    $nwMsg = "";
    $elctrncCashAccntID = (int) getGnrlRecNm("mcf.mcf_cust_account_trns_cheques", "trns_cheque_id", "gl_acnt_id_to_clear_into", $trnsChqID);
    if ($rwAcc = loc_db_fetch_array($rsltAcc)) {
        $doctype = $rwAcc[3];
        $docNum = $rwAcc[20];
        $cstmrNm = $rwAcc[9];
        $docDesc = $rwAcc[4];
        $entrdRate = (float) 1;
        $acntTrnsID = (float) $rwAcc[0];
        $trnsAmntTtl = ((float) $rwAcc[5]) * $amntMltplier;
        $docEntrdCrncyID = (float) $rwAcc[23];
        $docEntrdRate = (float) $rwAcc[24];
        if ($elctrncCashAccntID <= 0) {
            $elctrncCashAccntID = (int) $rwAcc[25];
        }
        $chqClrngAccntID = (int) $rwAcc[29];
        if ($elctrncCashAccntID <= 0) {
            $elctrncCashAccntID = get_DfltCashAcnt($orgID);
        }
        if ($chqClrngAccntID <= 0) {
            $chqClrngAccntID = get_DfltCheckAcnt($orgID);
        }
    }
    $fnccurid = getOrgFuncCurID($orgID);
    //deleteDocGLInfcLns($docHdrID, $doctype);
    //rvrsImprtdIntrfcTrns($docHdrID, $doctype);
    if ($doctype == "DEPOSIT") {
        $lnCntr1 = 0;
        $result = getAccountTrnsChqs($docHdrID, $trnsChqID);
        while ($row = loc_db_fetch_array($result)) {
            $succs = false;
            $lnCntr1++;
            $clearedQty = 0;
            $unClearedQty = 0;
            $lienQty = 0;
            $chqType = $row[9];
            $lineid = (float) $row[0];
            $isSrcAcntBalsOK = true;
            $itmType = $chqType;
            $docTypPrfx = $trnsTypeABRV[findArryIdx($trnsTypes, $doctype)];
            $createdBy = (float) $row[17];
            $chqAmnt = ((float) $row[7]) * $amntMltplier;
            $entrdRate = ((float) $row[15]);
            $entrdCrncyID = ((float) $row[13]);
            $chqNum = $row[6];
            $trnsLnTyp = "";
            $trnsSrc = "SYS";
            if ($chqType == "In-House") {
                $srcAcntTrnsID = (float) $row[22];
                $isSrcAcntBalsOK = createClearChqAccntng($srcAcntTrnsID, $trnsChqID, $nwMsg);
                //echo $isSrcAcntBalsOK ? "YES-OK" : "NO-OK";
                if ($isSrcAcntBalsOK === false) {
                    $errMsg .= $nwMsg;
                }
            } else {
                $unClearedQty = ((float) $row[7]) * ((float) $row[15]);
            }
            // && $usrID == $createdBy
            if ($elctrncCashAccntID > 0 && $chqClrngAccntID > 0 && $isSrcAcntBalsOK === true) {
                $succs = generateClearChqAccntng($docHdrID, $doctype, $fnccurid, $elctrncCashAccntID, $chqClrngAccntID, $chqAmnt, $entrdCrncyID, $entrdRate, $lineid, $dateStr, $docNum, $cstmrNm, $docDesc, $chqNum, $chqType, $trnsLnTyp, $trnsSrc, $nwMsg);
                //echo $succs ? "YES1" : "NO1";
            } else {
                $errMsg .= "Default Cash and Check Clearing Accounts not Defined!";
            }
            if ($succs === false) {
                $errMsg .= $nwMsg;
                break;
            }
        }
        //$lnCntr would have set it to true already
        if ($lnCntr1 <= 0) {
            $succs = true;
        }
        $lnCntr += $lnCntr1;
    }
    if ($lnCntr <= 0) {
        //$chqClrngAccntID or ach clearing account must be varried based on transaction Type (Cheques/Standig Order/Transfers)
        $unClearedQty = 0;
        $lienQty = 0;
        $succs = false;
        $clearedQty = $trnsAmntTtl;
        $lineid = $acntTrnsID;
        $itmType = "Services";
        $docTypPrfx = $trnsTypeABRV[findArryIdx($trnsTypes, $doctype)];
        $trnsLnTyp = "";
        $trnsSrc = "SYS";
        $entrdCrncyID = $docEntrdCrncyID;
        $entrdRate = $docEntrdRate;
        //$errMsg .= "entrdCrncyID:" . $entrdCrncyID . ":lbltyAccntID:" . $lbltyAccntID . ":chqClrngAccntID:" . $chqClrngAccntID . ":fnccurid:" . $fnccurid;
        if ($elctrncCashAccntID > 0 && $chqClrngAccntID > 0) {
            $succs = generateClearChqAccntng($docHdrID, $doctype, $fnccurid, $elctrncCashAccntID, $chqClrngAccntID, $trnsAmntTtl, $entrdCrncyID, $entrdRate, $lineid, $dateStr, $docNum, $cstmrNm, $docDesc, $docNum, $doctype, $trnsLnTyp, $trnsSrc, $nwMsg);
            //echo $succs ? "YES2" : "NO2";
            if ($succs === false) {
                $errMsg .= $nwMsg;
            }
        } else {
            $errMsg .= "Default Cash and Check Clearing Accounts not Defined!";
        }
    }
    //echo $errMsg . "<br/>";
    //$a=1/0;
    return $succs;
}

function updateCstmrAcntBalances($accntID, $clearedQty, $unClearedQty, $lienQty, $itmType, $actTyp, $docTypPrfx, $docLnID, $dateStr, &$errMsg) {
    $succs = false;
    if ($actTyp == "D") {
        $succs = postCstmrAcntAmt($accntID, -1 * $clearedQty, -1 * $unClearedQty, -1 * $lienQty, $dateStr, $docTypPrfx . "-" . $docLnID) > 0 ? true : false;
    } else {
        $succs = postCstmrAcntAmt($accntID, $clearedQty, $unClearedQty, $lienQty, $dateStr, $docTypPrfx . "-" . $docLnID) > 0 ? true : false;
    }
    if (!$succs) {
        $errMsg = "Act Type:" . $actTyp . ":Item Type:" . $itmType . ":Account ID:" . $accntID . ":Cleared Amt:" . $clearedQty . ":UnCleared Amt:" . $unClearedQty . " will cause negative balance!";
    }
    return $succs;
}

function postCstmrAcntAmt($accntID, $clearedQty, $unClearedQty, $lienQty, $trnsDate, $src_trsID) {
    $trnsDate = getStartOfDayDMYHMS();
    $affctd = 0;
    $dailybalID = getCstmrAcntDailyBalsID($accntID, $trnsDate);
    $lstClrdTotBals = getCstmrAcntLstClrdTotBls($accntID, $trnsDate);
    $lstUnClrdTotBals = getCstmrAcntLstUnClrdTotBls($accntID, $trnsDate);
    $lstLienBals = getCstmrAcntLstLienBls($accntID, $trnsDate);
    if ($dailybalID <= 0) {
        //if (($lstClrdTotBals + $clearedQty) >= 0 && ($lstUnClrdTotBals + $unClearedQty) >= 0 /* && ($lstLienBals + $lienQty) >= 0 */) {
        $affctd += createCstmrAcntDailyBals($accntID, $lstClrdTotBals, $lstUnClrdTotBals, $lstLienBals, $trnsDate, ",");
        $affctd += updtCstmrAcntDailyBals($accntID, $clearedQty, $unClearedQty, $lienQty, $trnsDate, "Do", $src_trsID);
        //}
    } else {
        //if (($lstClrdTotBals + $clearedQty) >= 0 && ($lstUnClrdTotBals + $unClearedQty) >= 0 /* && ($lstLienBals + $lienQty) >= 0 */) {
        $affctd += updtCstmrAcntDailyBals($accntID, $clearedQty, $unClearedQty, $lienQty, $trnsDate, "Do", $src_trsID);
        //}
    }
    return $affctd;
}

function isCstmrAcntAmtAvlbl($accntID, $clearedQty, $unClearedQty, $lienQty, $trnsDate, $actionType) {
    $lstClrdTotBals = getCstmrAcntLstClrdTotBls($accntID, $trnsDate);
    $lstUnClrdTotBals = getCstmrAcntLstUnClrdTotBls($accntID, $trnsDate);
    $lstLienBals = getCstmrAcntLstLienBls($accntID, $trnsDate);
    $canOvdrw = canCstmrAcntOvrdrw($accntID);
    //echo $accntID . ":" . $actionType . ":" . $lstClrdTotBals . ":" . $lstUnClrdTotBals;
    if ($actionType == "I") {
        return true;
        /* if (((float) $lstClrdTotBals + (float) $clearedQty) < 0) {
          return false;
          }
          if (((float) $lstUnClrdTotBals + (float) $unClearedQty) < 0) {
          return false;
          }
          if (((float) $lstLienBals + (float) $lienQty) < 0) {
          return false;
          } */
    } else {
        if (((float) $lstClrdTotBals - (float) $clearedQty) < 0 && ((float) $clearedQty) > 0 && $canOvdrw == "No") {
            return false;
        } else {
            return true;
        }
        /* if (((float) $lstUnClrdTotBals - (float) $unClearedQty) < 0) {
          return false;
          }
          if (((float) $lstLienBals - (float) $lienQty) < 0) {
          return false;
          } */
    }
    return true;
}

function getCstmrAcntDailyBalsID($accntID, $balsDate) {
    if ($balsDate != "") {
        $balsDate = cnvrtDMYTmToYMDTm($balsDate);
    }
    if (strlen($balsDate) > 10) {
        $balsDate = substr($balsDate, 0, 10);
    }
    $strSql = "SELECT a.acct_bal_id " .
            "FROM mcf.mcf_account_daily_bals a " .
            "WHERE(to_timestamp(a.bal_date,'YYYY-MM-DD') =  to_timestamp('" . $balsDate .
            "','YYYY-MM-DD') and a.account_id = " . $accntID .
            ")";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -1;
}

function getCstmrAcntLstClrdTotBls($accntID, $balsDate) {
    if ($balsDate != "") {
        $balsDate = cnvrtDMYTmToYMDTm($balsDate);
    }
    if (strlen($balsDate) > 10) {
        $balsDate = substr($balsDate, 0, 10);
    }
    $strSql = "SELECT COALESCE(a.available_balance,0) " .
            "FROM mcf.mcf_account_daily_bals a " .
            "WHERE(to_timestamp(a.bal_date,'YYYY-MM-DD') <=  to_timestamp('" . $balsDate .
            "','YYYY-MM-DD') and account_id = " . $accntID .
            ") ORDER BY to_timestamp(a.bal_date,'YYYY-MM-DD') DESC LIMIT 1 OFFSET 0";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return 0.00;
}

function getCstmrAcntLstUnClrdTotBls($accntID, $balsDate) {
    if ($balsDate != "") {
        $balsDate = cnvrtDMYTmToYMDTm($balsDate);
    }
    if (strlen($balsDate) > 10) {
        $balsDate = substr($balsDate, 0, 10);
    }
    $strSql = "SELECT COALESCE(a.uncleared_funds,0) " .
            "FROM mcf.mcf_account_daily_bals a " .
            "WHERE(to_timestamp(a.bal_date,'YYYY-MM-DD') <=  to_timestamp('" . $balsDate .
            "','YYYY-MM-DD') and account_id = " . $accntID .
            ") ORDER BY to_timestamp(a.bal_date,'YYYY-MM-DD') DESC LIMIT 1 OFFSET 0";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return 0.00;
}

function getCstmrAcntLstLienBls($accntID, $balsDate) {
    if ($balsDate != "") {
        $balsDate = cnvrtDMYTmToYMDTm($balsDate);
    }
    if (strlen($balsDate) > 10) {
        $balsDate = substr($balsDate, 0, 10);
    }
    $strSql = "SELECT COALESCE(a.lien_amount, 0) " .
            "FROM mcf.mcf_account_daily_bals a " .
            "WHERE(to_timestamp(a.bal_date,'YYYY-MM-DD') <=  to_timestamp('" . $balsDate .
            "','YYYY-MM-DD') and account_id = " . $accntID .
            ") ORDER BY to_timestamp(a.bal_date,'YYYY-MM-DD') DESC LIMIT 1 OFFSET 0";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return 0.00;
}

function canCstmrAcntOvrdrw($accntID) {
    $strSql = "SELECT mcf.can_cust_accnt_ovdrw(" . $accntID . ")";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return "No";
}

function createCstmrAcntDailyBals($accntID, $clearedQty, $unClearedQty, $lienQty, $balsDate, $src_trnsID) {
    global $usrID;
    global $gnrlTrnsDteYMDHMS;

    /* if ($balsDate != "") {
      $balsDate = cnvrtDMYTmToYMDTm($balsDate);
      }
      if (strlen($balsDate) > 10) {
      $balsDate = substr($balsDate, 0, 10);
      } */
    $dateStr = getDB_Date_time();
    $insSQL = "INSERT INTO mcf.mcf_account_daily_bals(
            bal_date, account_id, 
            created_by, creation_date, last_update_by, last_update_date, 
            src_acct_trns_ids, lien_amount, 
            available_balance, uncleared_funds) " .
            "VALUES ('" . substr($gnrlTrnsDteYMDHMS, 0, 10) .
            "', " . $accntID .
            ", " . $usrID .
            ", '" . $dateStr .
            "', " . $usrID .
            ", '" . $dateStr .
            "', '" . $src_trnsID .
            "'," . $lienQty .
            "," . $clearedQty .
            "," . $unClearedQty . ")";
    return execUpdtInsSQL($insSQL);
}

function updtCstmrAcntDailyBals($accntID, $clearedQty, $unClearedQty, $lienQty, $balsDate, $act_typ, $src_trnsID) {
    global $usrID;
    global $gnrlTrnsDteYMDHMS;
    /* if ($balsDate != "") {
      $balsDate = cnvrtDMYTmToYMDTm($balsDate);
      }
      if (strlen($balsDate) > 10) {
      $balsDate = substr($balsDate, 0, 10);
      } */
    $dateStr = getDB_Date_time();
    $updtSQL = "";
    if ($act_typ == "Undo") {
        $updtSQL = "UPDATE mcf.mcf_account_daily_bals " .
                "SET last_update_by = " . $usrID .
                ", last_update_date = '" . $dateStr .
                "', available_balance = COALESCE(available_balance,0) - " . $clearedQty .
                ", uncleared_funds = COALESCE(uncleared_funds,0) - " . $unClearedQty .
                ", lien_amount = COALESCE(lien_amount,0) - " . $lienQty .
                ", src_acct_trns_ids = COALESCE(replace(src_acct_trns_ids, '," . $src_trnsID . ",', ','),',')" .
                " WHERE (to_timestamp(bal_date,'YYYY-MM-DD') >=  to_timestamp('" . substr($gnrlTrnsDteYMDHMS, 0, 10) .
                "','YYYY-MM-DD') "
                . "and account_id = " . $accntID . ")";
    } else {
        $updtSQL = "UPDATE mcf.mcf_account_daily_bals " .
                "SET last_update_by = " . $usrID .
                ", last_update_date = '" . $dateStr .
                "', available_balance = COALESCE(available_balance,0) + " . $clearedQty .
                ", uncleared_funds = COALESCE(uncleared_funds,0) + " . $unClearedQty .
                ", lien_amount = COALESCE(lien_amount,0) + " . $lienQty .
                ", src_acct_trns_ids = COALESCE(replace(src_acct_trns_ids, '," . $src_trnsID . ",', ','),',')" .
                " WHERE (to_timestamp(bal_date,'YYYY-MM-DD') >= to_timestamp('" . substr($gnrlTrnsDteYMDHMS, 0, 10) .
                "','YYYY-MM-DD') "
                . "and account_id = " . $accntID . ")";
    }
    return execUpdtInsSQL($updtSQL);
}

function getMCFTrnsAttchMnts($acntTrnsId) {
    global $ftp_base_db_fldr;
    $sqlStr = "SELECT string_agg(REPLACE(a.attchmnt_desc,';',','),';') attchmnt_desc, 
string_agg(REPLACE('" . $ftp_base_db_fldr . "/Mcf/Transactions/' || a.file_name,';',','),';') file_name 
  FROM mcf.mcf_doc_attchmnts a 
  WHERE attchmnt_src IN ('WITHDRAWAL','DEPOSIT') and src_id=" . $acntTrnsId;
    $result = executeSQLNoParams($sqlStr);
    return $result;
}

function getMCFOrdrAttchMnts($acntTrnsId) {
    global $ftp_base_db_fldr;
    $sqlStr = "SELECT string_agg(REPLACE(a.attchmnt_desc,';',','),';') attchmnt_desc, 
string_agg(REPLACE('" . $ftp_base_db_fldr . "/Mcf/Transactions/' || a.file_name,';',','),';') file_name 
  FROM mcf.mcf_doc_attchmnts a 
  WHERE attchmnt_src IN ('Transfer/Order') and src_id=" . $acntTrnsId;
    $result = executeSQLNoParams($sqlStr);
    return $result;
}

function getBulkTrnsAttchMnts($acntTrnsId) {
    global $ftp_base_db_fldr;
    $sqlStr = "SELECT string_agg(REPLACE(a.attchmnt_desc,';',','),';') attchmnt_desc, 
string_agg(REPLACE('" . $ftp_base_db_fldr . "/Mcf/Transactions/' || a.file_name,';',','),';') file_name 
  FROM mcf.mcf_doc_attchmnts a 
  WHERE attchmnt_src IN ('Bulk/Batch Transactions') and src_id=" . $acntTrnsId;
    $result = executeSQLNoParams($sqlStr);
    return $result;
}

function updateMCFTrnsStatus($trnsHdrId, $nwApprvlStatus, $cur_prsnid = -1) {
    global $usrID;
    global $prsnid;
    $extrUpdate = "";
    $datestr = getDB_Date_time();
    if ($nwApprvlStatus == "Authorized") {
        $cur_prsnid = $prsnid;
        $extrUpdate = ", autorization_date = '" . $datestr . "', authorized_by_person_id = " . $cur_prsnid . "";
    }
    $updSQL = "UPDATE mcf.mcf_cust_account_transactions
            SET status = '" . loc_db_escape_string($nwApprvlStatus) . "',
                last_update_by = $usrID,
                last_update_date = '$datestr'" . $extrUpdate . "
            WHERE acct_trns_id = $trnsHdrId";
    $affctd = execUpdtInsSQL($updSQL);
    if ($nwApprvlStatus == "Paid") {
        $nwApprvlStatus = "Received";
    } else if ($nwApprvlStatus == "Received") {
        $nwApprvlStatus = "Paid";
    }
    $updSQL1 = "UPDATE mcf.mcf_cust_account_transactions
            SET status = '" . loc_db_escape_string($nwApprvlStatus) . "',
                last_update_by = $usrID,
                last_update_date = '$datestr'" . $extrUpdate . "
            WHERE lnkd_chq_trns_id>0 and lnkd_chq_trns_id IN (Select trns_cheque_id from mcf.mcf_cust_account_trns_cheques WHERE acct_trns_id = $trnsHdrId)";
    $affctd += execUpdtInsSQL($updSQL1);
    return $affctd;
}

function updateMCFTrnsAtzrLmtID($trnsHdrId, $athrzrLmtID) {
    global $usrID;
    $datestr = getDB_Date_time();
    $updSQL = "UPDATE mcf.mcf_cust_account_transactions
            SET last_update_by = $usrID,
                last_update_date = '$datestr',
                approval_limit_id = $athrzrLmtID
            WHERE acct_trns_id = $trnsHdrId";
    $affctd = execUpdtInsSQL($updSQL);
    $updSQL1 = "UPDATE mcf.mcf_cust_account_transactions
            SET last_update_by = $usrID,
                last_update_date = '$datestr',
                approval_limit_id = $athrzrLmtID
            WHERE lnkd_chq_trns_id>0 and lnkd_chq_trns_id IN (Select trns_cheque_id from mcf.mcf_cust_account_trns_cheques WHERE acct_trns_id = $trnsHdrId)";
    $affctd += execUpdtInsSQL($updSQL1);
    return $affctd;
}

function updateMCFOrdrStatus($trnsHdrId, $nwApprvlStatus, $cur_prsnid = -1) {
    global $usrID;
    global $prsnid;
    $extrUpdate = "";
    $datestr = getDB_Date_time();
    if ($nwApprvlStatus == "Authorized") {
        $cur_prsnid = $prsnid;
        $extrUpdate = ", autorization_date = '" . $datestr . "', authorized_by_person_id = " . $cur_prsnid . "";
    }
    $updSQL = "UPDATE mcf.mcf_standing_orders
            SET status = '" . loc_db_escape_string($nwApprvlStatus) . "',
                last_update_by = $usrID,
                last_update_date = '$datestr'" . $extrUpdate . "
            WHERE stndn_order_id = $trnsHdrId";
    $affctd = execUpdtInsSQL($updSQL);
    return $affctd;
}

function updateMCFOrdrAtzrLmtID($trnsHdrId, $athrzrLmtID) {
    global $usrID;
    $datestr = getDB_Date_time();
    $updSQL = "UPDATE mcf.mcf_standing_orders
            SET last_update_by = $usrID,
                last_update_date = '$datestr',
                approval_limit_id = $athrzrLmtID
            WHERE stndn_order_id = $trnsHdrId";
    $affctd = execUpdtInsSQL($updSQL);
    return $affctd;
}

function updateBulkTrnsStatus($trnsHdrId, $nwApprvlStatus, $cur_prsnid = -1) {
    global $usrID;
    global $prsnid;
    $extrUpdate = "";
    $datestr = getDB_Date_time();
    if ($nwApprvlStatus == "Authorized") {
        $cur_prsnid = $prsnid;
        $extrUpdate = ", autorization_date = '" . $datestr . "', authorized_by_person_id = " . $cur_prsnid . "";
    }
    $updSQL = "UPDATE mcf.mcf_bulk_trns_hdr
            SET status = '" . loc_db_escape_string($nwApprvlStatus) . "',
                last_update_by = $usrID,
                last_update_date = '$datestr'" . $extrUpdate . "
            WHERE bulk_trns_hdr_id = $trnsHdrId";
    $affctd = execUpdtInsSQL($updSQL);
    return $affctd;
}

function updateBulkTrnsAtzrLmtID($trnsHdrId, $athrzrLmtID) {
    global $usrID;
    $datestr = getDB_Date_time();
    $updSQL = "UPDATE mcf.mcf_bulk_trns_hdr
            SET last_update_by = $usrID,
                last_update_date = '$datestr',
                approval_limit_id = $athrzrLmtID
            WHERE bulk_trns_hdr_id = $trnsHdrId";
    $affctd = execUpdtInsSQL($updSQL);
    return $affctd;
}

function getNextMCFApprvrs($siteID, $trnsTyp, $crncyID, $trnsAmnt) {
    $strSql = "SELECT a.authorizer_person_id, a.authorizer_limit_id 
        FROM vms.vms_authorizers_limit a
        WHERE ((a.site_id = $siteID or a.site_id<=0) "
            . "and (a.currency_id = $crncyID or a.currency_id<=0) "
            . "and ($trnsAmnt>=a.min_amount) "
            . "and ($trnsAmnt<=a.max_amount) "
            . "and (a.transaction_type ='" . loc_db_escape_string($trnsTyp) . "' or a.transaction_type='') and is_enabled='1') ";
    //echo $strSql;
    $result = executeSQLNoParams($strSql);
    return $result;
}

function isTrnsWthnCageMngrsLmt($cageID, $trnsAmnt, $trnsID = -1, $trnsTyp = "RECEIPT") {
    $strSql = "SELECT a.cage_shelve_mngr_id, -1, "
            . "(select count(1) from mcf.mcf_cust_account_trns_cheques where acct_trns_id=" . loc_db_escape_string($trnsID) . ") chqCnt
        FROM inv.inv_shelf a
        WHERE ((a.line_id = $cageID and $cageID > 0) and abs($trnsAmnt)>=0 "
            . "and ((CASE WHEN '" . loc_db_escape_string($trnsTyp) . "'='ISSUE' "
            . "THEN a.managers_wthdrwl_limit ELSE a.managers_deposit_limit END)>=abs($trnsAmnt)) "
            . "and (select count(1) from mcf.mcf_cust_account_trns_cheques where acct_trns_id=" . loc_db_escape_string($trnsID) . ")<=0) ";
    $result = executeSQLNoParams($strSql);
    return $result;
}

function mcfTrnsReqMsgActns($routingID = -1, $inptSlctdRtngs = "", $actionToPrfrm = "Initiate", $srcDocID = -1, $srcDocType = "Banking Transactions") {
    global $app_url;
    global $admin_name;
    global $trnsTypes;
    global $trnsTypesSlf;
global $isWflMailAllwd;
    global $isWflMailAllwdID;
    
    $userID = $_SESSION['USRID'];
    $user_Name = $_SESSION['UNAME'];
    $cur_prsnid = $_SESSION['PRSN_ID'];
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
        if ($srcDocID > 0) {
            $rqstHdrStatus = getGnrlRecNm("mcf.mcf_cust_account_transactions", "acct_trns_id", "status", $srcDocID);
            if ($rqstHdrStatus == 'Authorized' || $rqstHdrStatus == 'Paid' || ($rqstHdrStatus == 'Initiated' && $actionToPrfrm == "Initiate" && $routingID <= 0)) {
                return "<span style=\"color:red;font-weight:bold;\">|ERROR| Nothing to $actionToPrfrm!</span>";
            }
        }
        if ($actionToPrfrm == "Initiate" && $srcDocID > 0) {
            $msg_id = getWkfMsgID();
            $appID = getAppID('Banking Transactions', 'Banking');
//Requestor
            $prsnid = $fromPrsnID;
            $fullNm = $usrFullNm;
            $prsnLocID = getPersonLocID($prsnid);

            $athrzrLmtID = -1;
            $siteID = -1;
            $trnsTyp = "";
            $trnsTypSlf = "";
            $mxCageID = -1;
            $crncyID = "";
            $trnsAmnt = "";
            $miscTrnsType = "";
            $AcNum = "";
            $AcntTitle = "";
            $rslt1 = get_One_MCFTrnsHdr($srcdocid);
            while ($rw1 = loc_db_fetch_array($rslt1)) {
                $siteID = (int) $rw1[7];
                $trnsTyp = $rw1[3];
                $trnsTypSlf = $trnsTypesSlf[findArryIdx($trnsTypes, $trnsTyp)];
                $crncyID = (int) $rw1[23];
                $trnsAmnt = (float) $rw1[5];
                $mxCageID = (float) $rw1[49];
                $miscTrnsType = $rw1[50];
                $AcNum = $rw1[53];
                $AcntTitle = $rw1[54];
            }
//Message Header & Details
            $msghdr = "$fullNm ($prsnLocID) Requests for approval of a " . $trnsTyp . " Transaction Ac/No.:$AcNum Ac/Title: $AcntTitle";
            $msgbody = "BANKING TRANSACTION REQUEST ON ($reqestDte):- "
                    . "A BANKING Transaction Request has been submitted by $fullNm ($prsnLocID) with regards to Ac/No.:$AcNum Ac/Title: $AcntTitle"
                    . "<br/>Please open the attached Work Document and attend to this Request.
                      <br/>Thank you.";
            $msgtyp = "Work Document";
            $msgsts = "0";
            $hrchyid = 1; //Get hierarchy ID
            $rslt = getMCFTrnsAttchMnts($srcDocID);
            $attchmnts = ""; //Get Attachments
            $attchmnts_desc = ""; //Get Attachments
            while ($rw = loc_db_fetch_array($rslt)) {
                $attchmnts = $rw[1];
                $attchmnts_desc = $rw[0];
            }

            createWkfMsg($msg_id, $msghdr, $msgbody, $userID, $appID, $msgtyp, $msgsts, $srcdoctyp, $srcdocid, $hrchyid, $attchmnts, $attchmnts_desc);
//Get Hierarchy Members
            $result = null;
            if ($mxCageID > 0 && $miscTrnsType != "MISC_TRANS") {
                $result = isTrnsWthnCageMngrsLmt($mxCageID, $trnsAmnt, $srcdocid, $trnsTypSlf);
                if (loc_db_num_rows($result) <= 0) {
                    $result = getNextMCFApprvrs($siteID, $trnsTyp, $crncyID, $trnsAmnt);
                }
            } else {
                $result = getNextMCFApprvrs($siteID, $trnsTyp, $crncyID, $trnsAmnt);
            }
            $prsnsFnd = 0;
            $lastPrsnID = "|";
            $paramRepsNVals = $prmID . "~" . $msgBatchID . "|-190~HTML";
            while ($row = loc_db_fetch_array($result)) {
                $athrzrLmtID = (float) $row[1];
                $toPrsnID = (float) $row[0];
                $prsnsFnd++;
                if ($toPrsnID > 0) {
                    if ($cur_prsnid == $toPrsnID && $miscTrnsType != "MISC_TRANS") {
                        //updateMCFTrnsStatus($srcdocid, "Authorized");
                        updateMCFTrnsAtzrLmtID($srcdocid, $athrzrLmtID);
                        $msg = payMCFTrnsRqst($srcdocid, "Yes");
                        return $msg;
                    } else {
                        routWkfMsg($msg_id, $prsnid, $toPrsnID, $userID, 'Initiated', 'Open;Reject;Request for Information;Authorize');
                        $dsply = '<div style="text-align:center;font-weight:bold;font-size:14px;color:blue;">SUCCESS!</br>Your request has been submitted for Approval. Thank you!</div>';
                        $msg = $dsply;
                        //Begin Email Sending Process
                        /*if (strtoupper($isWflMailAllwd) == "YES" && $isWflMailAllwdID > 0) {
                         $msgBatchID = getMsgBatchID();                    
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
                          } }*/
                    }
                }
            }
            if ($prsnsFnd <= 0) {
                $dsply .= "|ERROR|-No Approval Hierarchy Found<br/>";
                $msg = "<p style = \"text-align:left; color:#ff0000;\"><span style=\"font-style:italic;font-weight:bold;\">$dsply</span></p>";
            } else {
//Update Request Status to In Process
                updateMCFTrnsStatus($srcdocid, "Initiated");
                updateMCFTrnsAtzrLmtID($srcdocid, $athrzrLmtID);
            }
        } else {
            $dsply .= "|ERROR|-Update Failed! No Workflow Document(s) Generated<br/>";
            $msg = "<p style = \"text-align:left; color:#ff0000;\"><span style=\"font-style:italic;font-weight:bold;\">$dsply</span></p>";
        }
    } else {
        $orgnlToPrsnID = -1;
        if ($routingID > 0) {
            $oldMsgbodyAddOn = "";
            $reslt1 = getWkfMsgRtngData($routingID);
            while ($row = loc_db_fetch_array($reslt1)) {
                $rtngMsgID = (float) $row[0];
                $msg_id = $rtngMsgID;
                $curPrsnsLevel = (float) $row[18];
                $isActionDone = $row[9];
                $oldMsgbodyAddOn = $row[17];
                $orgnlToPrsnID = (float) $row[2];
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
            if ($srcDocID > 0) {
                $rqstHdrStatus = getGnrlRecNm("mcf.mcf_cust_account_transactions", "acct_trns_id", "status", $srcDocID);
                if (($rqstHdrStatus == 'Authorized' || $rqstHdrStatus == 'Paid') && $actionToPrfrm != "Open") {
                    return "<span style=\"color:red;font-weight:bold;\">|ERROR| Nothing to $actionToPrfrm!</span>";
                }
            }
            $srcdoctyp = $srcDocType;
            $srcdocid = $srcDocID;
            $orgnlPrsnID = getUserPrsnID1($orgnlPrsnUsrID);
            if ($isActionDone == '0') {
                if ($actionToPrfrm == "Open") {
                    getMCFTrnsRdOnlyDsply($srcDocID, $srcDocType);
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
                    $affctd4 += updateMCFTrnsStatus($srcdocid, "Rejected");

//Begin Email Sending Process                    
                    /* $result = getEmlDetailsAftrActn($srcdoctyp, $srcdocid);
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
                      }
                      } */
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
                    if ($orgnlPrsnID == $cur_prsnid && $orgnlPrsnID > 0) {
                        $affctd += updtWkfMsgRtngUsngLvl($rtngMsgID, $curPrsnsLevel, $fromPrsnID, "Withdrawn", "None", $userID);
                        if ($apprvrCmmnts == "") {
                            $apprvrCmmnts = "Withdrawal by Self";
                        }
                        $datestr = getFrmtdDB_Date_time();
                        $msgbodyAddOn = "";
                        $msgbodyAddOn .= "WITHDRAWAL ON $datestr:- This document has been Withdraw by $usrFullNm with the ff Message:<br/>";
                        $msgbodyAddOn .= $apprvrCmmnts . "<br/><br/>";
                        $affctd1 += updtWkfMsgRtngCmnts($routingID, $msgbodyAddOn, $userID);
                        $msgbodyAddOn .= $oldMsgbodyAddOn;

                        updateWkfMsgStatus($rtngMsgID, "1", $userID);
                        updtWkfMsgAllUnclsdRtng($rtngMsgID, $fromPrsnID, "Closed", "None", $userID);

//Message Header & Details
                        $msghdr = "WITHDRAWAL - " . $msgTitle;
                        $msgbody = $msgBdy; //$msgbodyAddOn. "ORIGINAL MESSAGE :<br/><br/>" .
                        $msgtyp = "Informational";
                        $msgsts = "0";
//$msg_id = getWkfMsgID();
                        $affctd2 += updateWkfMsg($msg_id, $msghdr, $msgbody, $userID, $appID, $msgtyp, $msgsts, $srcdoctyp, $srcdocid, $hrchyid, $attchmnts, $attchmnts_desc);
                        $affctd3 += routWkfMsg($msg_id, $fromPrsnID, $orgnlPrsnID, $userID, "Initiated", "Acknowledge;Open", 1, $msgbodyAddOn);
                        $affctd4 += updateMCFTrnsStatus($srcdocid, "Withdrawn");

//Begin Email Sending Process                    
                        /* $result = getEmlDetailsAftrActn($srcdoctyp, $srcdocid);
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
                          }
                          } */
                    }
                    if ($affctd > 0) {
                        $dsply .= "<br/>Status of $affctd Workflow Document(s) successfully updated to Withdrawn!";
                        $dsply .= "<br/>$affctd1 Workflow Document(s) Message Body Successfully Updated!";
//$dsply .= "<br/>$affctd2 New Workflow Document(s) Message Body Successfully Created!";
                        $dsply .= "<br/>$affctd3 Workflow Document(s) Successfully Re-Routed to Original Sender!";
                        $dsply .= "<br/>$affctd4 Request Status Successfully Updated!";
                        $msg = "<p style = \"text-align:left; color:#32CD32;\"><span style=\"font-style:italic;font-weight:bold;\">$dsply</span></p>"; //#32CD32
                    } else {
                        $dsply .= "<br/>|ERROR|-Update Failed! No Workflow Document(s) Withdrawn";
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
//$affctd4+=updatePrsDataChangeReq($srcdocid, "Rejected");
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
                    $affctd3 += routWkfMsg($msg_id, $fromPrsnID, $nwPrsnID, $userID, "Initiated", 'Open;Reject;Request for Information;Authorize', $curPrsnsLevel, $msgbodyAddOn);
//$affctd4+=updatePrsDataChangeReq($srcdocid, "Rejected");
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
//$dsply .= "<br/>$affctd4 Appointment Status Successfully Updated!";
                        $msg = "<p style = \"text-align:left; color:#32CD32;\"><span style=\"font-style:italic;font-weight:bold;\">$dsply</span></p>"; //#32CD32
                    } else {
                        $dsply .= "<br/>|ERROR|-Update Failed! No Workflow Document(s) Worked On";
                        $msg = "<p style = \"text-align:left; color:#ff0000;\"><span style=\"font-style:italic;font-weight:bold;\">$dsply</span></p>";
                    }
                } else if ($actionToPrfrm == "Authorize") {
                    $affctd = 0;
                    if ($orgnlToPrsnID == $cur_prsnid && $orgnlToPrsnID > 0) {
                        $newStatus = "Reviewed";
                        $newVldtyStatus = "Validated";
                        $nxtStatus = "Open;Reject;Request for Information;Authorize";
                        $nxtApprvr = "Next Approver";
                        $nxtPrsnID = -1;
                        $shdActnBeDone = FALSE;
                        if ($nxtPrsnID <= 0) {
                            $dsply .= ""; // "Start Banking Trans. Item Balance Update and Accounting Entries Generation<br/>";
                            $shdActnBeDone = isMCFTrnsQtyVld($srcDocID, $dsply) && isMCFTrnsCstmrAcntAmtVld($srcDocID);
                            if ($shdActnBeDone === true) {
                                $dsply = ""; // "Item Balance && Customer Balance Verified<br/>";
                                $newStatus = "Authorized";
                                $nxtStatus = "Open;Acknowledge";
                                $newVldtyStatus = "Validated";
                            } else {
                                $dsply .= "<i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>Error Occured!<br/>Kindly ensure that you have the Requested Quantities in the various Source Cages as well as the Customer's Account and that you have Permission to Perform this Function 1.0!";
                                $newVldtyStatus = "Not Validated";
                            }
                        }
                        if ($shdActnBeDone === true) {
                            $affctd += updtWkfMsgRtngUsngLvl($rtngMsgID, $curPrsnsLevel, $fromPrsnID, $newStatus, $nxtStatus, $userID);
//$affctd1+= updateWkfMsgBdy($rtngMsgID, $msgbodyAddOn, $userID);
                            $datestr = getFrmtdDB_Date_time();
                            $msgbodyAddOn = "";
                            $msgbodyAddOn .= strtoupper($newStatus) . " ON $datestr:- This document has been $newStatus by $usrFullNm <br/><br/>";
//$msgbodyAddOn.=$apprvrCmmnts . "<br/><br/>";
                            $affctd1 += updtWkfMsgRtngCmnts($routingID, $msgbodyAddOn, $userID);
                            $msgbodyAddOn .= $oldMsgbodyAddOn;
                            updtWkfMsgAllUnclsdRtng($rtngMsgID, $fromPrsnID, "Closed", "None", $userID);
//Message Header & Details
                            if ($nxtPrsnID <= 0) {
                                $nxtApprvr = "Original Person";
                                updateWkfMsgStatus($rtngMsgID, "1", $userID);
                                $msghdr = "AUTHORIZED - " . $msgTitle;
                                $msgbody = $msgBdy;
                                $msgtyp = "Informational";
                                $msgsts = "0";
//$msg_id = getWkfMsgID();
                                $curPrsnsLevel += 1;
                                $affctd2 += updateWkfMsg($msg_id, $msghdr, $msgbody, $userID, $appID, $msgtyp, $msgsts, $srcdoctyp, $srcdocid, $hrchyid, $attchmnts, $attchmnts_desc);
                                $affctd3 += routWkfMsg($msg_id, $fromPrsnID, $orgnlPrsnID, $userID, $newStatus, $nxtStatus, $curPrsnsLevel, $msgbodyAddOn);
                            } else {
                                $msghdr = $msgTitle;
                                $msgbody = $msgBdy;
                                $msgtyp = "Work Document";
                                $msgsts = "0";
//$msg_id = getWkfMsgID();
                                $curPrsnsLevel += 1;
                                $affctd2 += updateWkfMsg($msg_id, $msghdr, $msgbody, $userID, $appID, $msgtyp, $msgsts, $srcdoctyp, $srcdocid, $hrchyid, $attchmnts, $attchmnts_desc);
                                $affctd3 += routWkfMsg($msg_id, $fromPrsnID, $nxtPrsnID, $userID, $newStatus, $nxtStatus, $curPrsnsLevel, $msgbodyAddOn);
                            }
                            $affctd4 += updateMCFTrnsStatus($srcdocid, $newStatus, $cur_prsnid);
                            /* if ($nxtPrsnID <= 0) {
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
                              createMessageQueue($msgBatchID, trim(str_replace(";", ",", $to), ";, "), "", "", $message, $subject, "",
                              "Email");
                              //sendEMail(trim(str_replace(";", ",", $to), ","), $nameto, $subject, $message, $errMsg, "", "", "", $admin_name);
                              }
                              break;
                              }
                              } */
                        }
                    }
                    if ($affctd > 0) {
                        $dsply .= "<br/>Status of $affctd Workflow Document(s) successfully updated to $newStatus!";
                        $dsply .= "<br/>$affctd1 Workflow Document(s) Message Body Successfully Updated!";
//$dsply .= "<br/>$affctd2 New Workflow Document(s) Message Body Successfully Created!";
                        $dsply .= "<br/>$affctd3 Workflow Document(s) Successfully Re-Routed to $nxtApprvr!";
                        $dsply .= "<br/>$affctd4 Request Status Successfully Updated!";
                        $msg = "<p style = \"text-align:left; color:#32CD32;\"><span style=\"font-style:italic;font-weight:bold;\">$dsply</span></p>"; //#32CD32
                    } else if ($dsply == "") {
                        $dsply .= "<br/>|ERROR|-Update Failed! No Workflow Document(s) Worked On<br/>Sorry, you're not assigned to perform this Function!";
                        $msg = "<p style = \"text-align:left; color:#ff0000;\"><span style=\"font-style:italic;font-weight:bold;\">$dsply</span></p>";
                    } else {
                        $dsply .= "<br/>|ERROR|-Update Failed! No Workflow Document(s) Worked On!";
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

function withdrawMCFTrnsRqst($hdrid) {
    $msg = "";
    $lnkdChqTrnsId = (float) getGnrlRecNm("mcf.mcf_cust_account_transactions", "acct_trns_id", "lnkd_chq_trns_id", $hdrid);
    $lnkdOrdrExctnId = (float) getGnrlRecNm("mcf.mcf_cust_account_transactions", "acct_trns_id", "lnkd_ordr_exctn_id", $hdrid);
    $lnkdMsclTrnsId = (float) getGnrlRecNm("mcf.mcf_cust_account_transactions", "acct_trns_id", "lnkd_mscl_trns_id", $hdrid);
    $lnkdBulkTrnsId = (float) getGnrlRecNm("mcf.mcf_cust_account_transactions", "acct_trns_id", "bulk_trns_hdr_id", $hdrid);
    if ($lnkdBulkTrnsId > 0 && $lnkdChqTrnsId > 0 || $lnkdOrdrExctnId > 0 || ($lnkdMsclTrnsId > 0 && $lnkdMsclTrnsId != $hdrid)) {
        return "<span style=\"color:red;\">|ERROR| Withdrawal must be done from the Linked Source Transaction!</span>";
    }
    $rqstHdrStatus = getGnrlRecNm("mcf.mcf_cust_account_transactions", "acct_trns_id", "status", $hdrid);
    if ($rqstHdrStatus == 'Authorized' || $rqstHdrStatus == 'Paid' || $rqstHdrStatus == 'Received') {
        return "<span style=\"color:red;\">|ERROR| Nothing to Withdraw!</span>";
    }
    $srcDocID = $hdrid;
    $srcDocType = "Banking Transactions";
    $inptSlctdRtngs = "";
    $actionToPrfrm = "Withdraw";
    $selSQL = "SELECT MAX(b.routing_id)
  FROM wkf.wkf_actual_msgs_hdr a, wkf.wkf_actual_msgs_routng b
  WHERE a.msg_id=b.msg_id and a.src_doc_type='" . $srcDocType . "' 
  and a.src_doc_id=" . $hdrid;
    $result1 = executeSQLNoParams($selSQL);
    while ($row = loc_db_fetch_array($result1)) {
        $routingID = $row[0];
        $actionToPrfrm = "Withdraw";
        if ($routingID > 0) {
            $msg = mcfTrnsReqMsgActns($routingID, $inptSlctdRtngs, $actionToPrfrm, $srcDocID, $srcDocType);
        }
    }
    if ($msg == "") {
        $msg = "<span style=\"color:red;\">|ERROR| Nothing to Withdraw!</span>";
    }
    return $msg;
}

function authorizeMCFTrnsRqst($hdrid) {
    global $prsnid;
    $msg = "";
    $lnkdChqTrnsId = (float) getGnrlRecNm("mcf.mcf_cust_account_transactions", "acct_trns_id", "lnkd_chq_trns_id", $hdrid);
    $lnkdOrdrExctnId = (float) getGnrlRecNm("mcf.mcf_cust_account_transactions", "acct_trns_id", "lnkd_ordr_exctn_id", $hdrid);
    $lnkdMsclTrnsId = (float) getGnrlRecNm("mcf.mcf_cust_account_transactions", "acct_trns_id", "lnkd_mscl_trns_id", $hdrid);
    $lnkdBulkTrnsId = (float) getGnrlRecNm("mcf.mcf_cust_account_transactions", "acct_trns_id", "bulk_trns_hdr_id", $hdrid);
    if ($lnkdBulkTrnsId > 0 && $lnkdChqTrnsId > 0 || $lnkdOrdrExctnId > 0 || ($lnkdMsclTrnsId > 0 && $lnkdMsclTrnsId != $hdrid)) {
        return "<span style=\"color:red;\">|ERROR| Authorizaton must be done from the Linked Source Transaction!</span>";
    }
    $rqstHdrStatus = getGnrlRecNm("mcf.mcf_cust_account_transactions", "acct_trns_id", "status", $hdrid);
    if ($rqstHdrStatus == 'Authorized' || $rqstHdrStatus == 'Paid' || $rqstHdrStatus == 'Received') {
        return "<span style=\"color:red;\">|ERROR| Nothing to Authorize!</span>";
    }
    $srcDocID = $hdrid;
    $srcDocType = "Banking Transactions";
    $inptSlctdRtngs = "";
    $actionToPrfrm = "Authorize";
    $selSQL = "SELECT MAX(b.routing_id)
  FROM wkf.wkf_actual_msgs_hdr a, wkf.wkf_actual_msgs_routng b
  WHERE a.msg_id=b.msg_id and a.src_doc_type='" . $srcDocType . "' 
  and a.src_doc_id=" . $hdrid . " and b.to_prsn_id = " . $prsnid;
    $result1 = executeSQLNoParams($selSQL);
    while ($row = loc_db_fetch_array($result1)) {
        $routingID = $row[0];
        $actionToPrfrm = "Authorize";
        if ($routingID > 0) {
            $msg = mcfTrnsReqMsgActns($routingID, $inptSlctdRtngs, $actionToPrfrm, $srcDocID, $srcDocType);
        }
    }
    if ($msg == "") {
        $msg = "<span style=\"color:red;\">|ERROR| Nothing to Authorize!</span>";
    }
    return $msg;
}

function payMCFTrnsRqst($hdrid, $frmTllrDrct = "No") {
    global $usrID;
    global $orgID;
    $isProcessing = (float) getGnrlRecNm("mcf.mcf_cust_account_transactions", "acct_trns_id", "processing_ongoing", $hdrid);
    if ($isProcessing == "0") {
        execUpdtInsSQL("UPDATE mcf.mcf_cust_account_transactions SET processing_ongoing = '1' WHERE acct_trns_id = " . $hdrid);
        $sqlStr = "select mcf.process_tllr_trns(" . $hdrid . "," . $usrID . ",to_char(now(),'YYYY-MM-DD HH24:MI:SS')," . $orgID . ",-1) ";
        $result = executeSQLNoParams($sqlStr);
        while ($row = loc_db_fetch_array($result)) {
            $msg = $row[0];
            if (strpos($msg, "Success") !== FALSE) {
                $msg = "<span style=\"color:green;font-weight:bold;\">" . $msg . "</span>";
            } else {
                $msg = "<span style=\"color:red;font-weight:bold;\">" . $msg . "</span>";
            }
            return $msg;
        }
        return "";
    }
    return "<span style=\"color:red;font-weight:bold;\">Transaction is already being processed!</span>";
    /* $lnkdChqTrnsId = (float) getGnrlRecNm("mcf.mcf_cust_account_transactions", "acct_trns_id", "lnkd_chq_trns_id", $hdrid);
      $lnkdOrdrExctnId = (float) getGnrlRecNm("mcf.mcf_cust_account_transactions", "acct_trns_id", "lnkd_ordr_exctn_id", $hdrid);
      $lnkdMsclTrnsId = (float) getGnrlRecNm("mcf.mcf_cust_account_transactions", "acct_trns_id", "lnkd_mscl_trns_id", $hdrid);
      $lnkdBulkTrnsId = (float) getGnrlRecNm("mcf.mcf_cust_account_transactions", "acct_trns_id", "bulk_trns_hdr_id", $hdrid);
      if ($lnkdBulkTrnsId > 0 && $lnkdChqTrnsId > 0 || $lnkdOrdrExctnId > 0 || ($lnkdMsclTrnsId > 0 && $lnkdMsclTrnsId != $hdrid)) {
      return "<span style=\"color:red;\">|ERROR| Transaction Finalization must be done from the Linked Source Transaction!</span>";
      }
      $loanRqstID = -1;
      $disbmntDetID = getGnrlRecNm("mcf.mcf_cust_account_transactions", "acct_trns_id", "disbmnt_det_id", $hdrid);
      if ($disbmntDetID !== "") {
      $loanRqstID = (int) getGnrlRecNm("mcf.mcf_loan_disbursement_det", "disbmnt_det_id", "loan_rqst_id", (int) $disbmntDetID);
      }

      $apprvrStatus = 'Paid';
      $rqstHdrStatus = getGnrlRecNm("mcf.mcf_cust_account_transactions", "acct_trns_id", "status", $hdrid);
      $rqstTrnsType = getGnrlRecNm("mcf.mcf_cust_account_transactions", "acct_trns_id", "trns_type", $hdrid);
      if ($rqstHdrStatus == 'Paid' || $rqstHdrStatus == 'Received' || $hdrid <= 0) {
      return "<span style=\"color:red;\">|ERROR| No Payment to Process!</span>";
      }
      if ($rqstTrnsType == "DEPOSIT") {
      $apprvrStatus = 'Received';
      }
      $srcDocID = $hdrid;
      $nwMsg = "";
      $dsply = ""; // "Start Banking Trans. Item Balance Update and Accounting Entries Generation<br/>";
      $shdActnBeDone = ($frmTllrDrct == "Yes") ? true : isMCFTrnsQtyVld($srcDocID, $dsply) && isMCFTrnsCstmrAcntAmtVld($srcDocID);
      if ($shdActnBeDone === true) {
      $dsply .= ""; // "Item Balance && Customer Balance Verified<br/>";
      $shdActnBeDone = updateMCFStckBals($srcDocID);
      if ($shdActnBeDone === true) {
      $dsply .= "<span style=\"color:green;font-weight:bold;\">Currency Stock Balance Updated</span><br/>";
      $shdActnBeDone = updateMCFCstmrAcntBals($srcDocID);
      if ($shdActnBeDone === true) {
      $dsply .= "<span style=\"color:green;font-weight:bold;\">Customer Account Balance Updated</span><br/>";
      $nwMsg = "";
      $shdActnBeDone = createMCFAccntng($srcDocID, $nwMsg);
      if ($shdActnBeDone === FALSE) {
      $dsply .= "<span style=\"color:red;font-weight:bold;\">$nwMsg</span>";
      $dsply .= "<span style=\"color:red;font-weight:bold;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>Error Occured!Failed to Create Accounting!</span>";
      } else {
      $dsply .= "<span style=\"color:green;font-weight:bold;\">Accounting Created<br/></span>";
      $voidedTrnsID = (float) getGnrlRecNm("mcf.mcf_cust_account_transactions", "acct_trns_id", "voided_acct_trns_id", $hdrid);
      if ($voidedTrnsID > 0) {
      updateMCFTrnsStatus($voidedTrnsID, "Void");
      updateLoanRqstIsPaidStatus($loanRqstID, 0);
      } else {
      updateLoanRqstIsPaidStatus($loanRqstID, 1);
      }
      updateMCFTrnsStatus($hdrid, $apprvrStatus);
      }
      } else {
      $dsply .= "<span style=\"color:red;font-weight:bold;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>Error Occured!<br/>Customer Balance could not be updated 2.2!<br/>$nwMsg</span>";
      }
      } else {
      $dsply .= "<span style=\"color:red;font-weight:bold;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>Error Occured!<br/>Kindly ensure that you have the Requested Quantities in the various Source Cages as well as the Customer's Account and that you have Permission to perform this Action on this Cage/Till 2.1!</span>";
      }
      } else {
      $dsply .= "<span style=\"color:red;font-weight:bold;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>Error Occured!<br/>Kindly ensure that you have the Requested Quantities in the various Source Cages as well as the Customer's Account 2.0!</span>";
      }
      $msg = $dsply;
      return $msg; */
}

function mcfTrnsReqMsgActnsBulk($routingID = -1, $inptSlctdRtngs = "", $actionToPrfrm = "Initiate", $srcDocID = -1, $srcDocType = "Bulk/Batch Transactions") {
    global $app_url;
    global $admin_name;
    global $trnsTypes;
    global $trnsTypesSlf;
global $isWflMailAllwd;
    global $isWflMailAllwdID;

    $userID = $_SESSION['USRID'];
    $user_Name = $_SESSION['UNAME'];
    $cur_prsnid = $_SESSION['PRSN_ID'];
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
        if ($srcDocID > 0) {
            $rqstHdrStatus = getGnrlRecNm("mcf.mcf_bulk_trns_hdr", "bulk_trns_hdr_id", "status", $srcDocID);
            if ($rqstHdrStatus == 'Authorized' || $rqstHdrStatus == 'Processed' || ($rqstHdrStatus == 'Initiated' && $actionToPrfrm == "Initiate" && $routingID <= 0)) {
                return "<span style=\"color:red;font-weight:bold;\">|ERROR| Nothing to $actionToPrfrm!</span>";
            }
        }
        if ($actionToPrfrm == "Initiate" && $srcDocID > 0) {
            $msg_id = getWkfMsgID();
            $appID = getAppID('Bulk/Batch Transactions', 'Banking');
            //Requestor
            $prsnid = $fromPrsnID;
            $fullNm = $usrFullNm;
            $prsnLocID = getPersonLocID($prsnid);

            $athrzrLmtID = -1;
            $siteID = -1;
            $trnsTyp = "Bulk/Batch Transactions";
            $crncyID = "";
            $trnsAmnt = "";
            $rltnsMngr = "";
            $rslt1 = get_One_BatchTrnsHdr($srcdocid);
            while ($rw1 = loc_db_fetch_array($rslt1)) {
                $siteID = (int) $rw1[3];
                $crncyID = (int) $rw1[29];
                $trnsAmnt = (float) $rw1[9];
                $rltnsMngr = $rw1[7] . " (" . $rw1[8] . ")";
            }
            //Message Header & Details
            $msghdr = "$fullNm ($prsnLocID) Requests for approval of a Bulk/Batch Transactions R/O: $rltnsMngr";
            $msgbody = "BULK/BATCH TRANSACTION REQUEST ON ($reqestDte):- "
                    . "A BULK/BATCH Transaction Request has been submitted by $fullNm ($prsnLocID) on behalf of Accounts Relations Officer: $rltnsMngr"
                    . "<br/>Please open the attached Work Document and attend to this Request.
                      <br/>Thank you.";
            $msgtyp = "Work Document";
            $msgsts = "0";
            $hrchyid = 1; //Get hierarchy ID
            $rslt = getBulkTrnsAttchMnts($srcDocID);
            $attchmnts = ""; //Get Attachments
            $attchmnts_desc = ""; //Get Attachments
            while ($rw = loc_db_fetch_array($rslt)) {
                $attchmnts = $rw[1];
                $attchmnts_desc = $rw[0];
            }

            createWkfMsg($msg_id, $msghdr, $msgbody, $userID, $appID, $msgtyp, $msgsts, $srcdoctyp, $srcdocid, $hrchyid, $attchmnts, $attchmnts_desc);
//Get Hierarchy Members
            $result = getNextMCFApprvrs($siteID, $trnsTyp, $crncyID, $trnsAmnt);
            $prsnsFnd = 0;
            $lastPrsnID = "|";
            $paramRepsNVals = $prmID . "~" . $msgBatchID . "|-190~HTML";
            while ($row = loc_db_fetch_array($result)) {
                $athrzrLmtID = (float) $row[1];
                $toPrsnID = (float) $row[0];
                $prsnsFnd++;
                if ($toPrsnID > 0) {
                    routWkfMsg($msg_id, $prsnid, $toPrsnID, $userID, 'Initiated', 'Open;Reject;Request for Information;Authorize');
                    $dsply = '<div style="text-align:center;font-weight:bold;font-size:14px;color:blue;">SUCCESS!</br>Your request has been submitted for Approval. Thank you!</div>';
                    $msg = $dsply;
                    //Begin Email Sending Process                    
                    /* if (strtoupper($isWflMailAllwd) == "YES" && $isWflMailAllwdID > 0) {
                        $msgBatchID = getMsgBatchID();
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
                      }} */
                }
            }
            if ($prsnsFnd <= 0) {
                $dsply .= "|ERROR|-No Approval Hierarchy Found<br/>";
                $msg = "<p style = \"text-align:left; color:#ff0000;\"><span style=\"font-style:italic;font-weight:bold;\">$dsply</span></p>";
            } else {
//Update Request Status to In Process
                updateBulkTrnsStatus($srcdocid, "Initiated");
                updateBulkTrnsAtzrLmtID($srcdocid, $athrzrLmtID);
            }
        } else {
            $dsply .= "|ERROR|-Update Failed! No Workflow Document(s) Generated<br/>";
            $msg = "<p style = \"text-align:left; color:#ff0000;\"><span style=\"font-style:italic;font-weight:bold;\">$dsply</span></p>";
        }
    } else {
        $orgnlToPrsnID = -1;
        if ($routingID > 0) {
            $oldMsgbodyAddOn = "";
            $reslt1 = getWkfMsgRtngData($routingID);
            while ($row = loc_db_fetch_array($reslt1)) {
                $rtngMsgID = (float) $row[0];
                $msg_id = $rtngMsgID;
                $curPrsnsLevel = (float) $row[18];
                $isActionDone = $row[9];
                $oldMsgbodyAddOn = $row[17];
                $orgnlToPrsnID = (float) $row[2];
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
            if ($srcDocID > 0) {
                $rqstHdrStatus = getGnrlRecNm("mcf.mcf_cust_account_transactions", "acct_trns_id", "status", $srcDocID);
                if (($rqstHdrStatus == 'Authorized' || $rqstHdrStatus == 'Processed') && $actionToPrfrm != "Open") {
                    return "<span style=\"color:red;font-weight:bold;\">|ERROR| Nothing to $actionToPrfrm!</span>";
                }
            }
            $srcdoctyp = $srcDocType;
            $srcdocid = $srcDocID;
            $orgnlPrsnID = getUserPrsnID1($orgnlPrsnUsrID);
            if ($isActionDone == '0') {
                if ($actionToPrfrm == "Open") {
                    getBulkTrnsRdOnlyDsply($srcDocID, $srcDocType);
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
                    $affctd4 += updateBulkTrnsStatus($srcdocid, "Rejected");

//Begin Email Sending Process                    
                    /* $result = getEmlDetailsAftrActn($srcdoctyp, $srcdocid);
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
                      }
                      } */
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
                    if ($orgnlPrsnID == $cur_prsnid && $orgnlPrsnID > 0) {
                        $affctd += updtWkfMsgRtngUsngLvl($rtngMsgID, $curPrsnsLevel, $fromPrsnID, "Withdrawn", "None", $userID);
                        if ($apprvrCmmnts == "") {
                            $apprvrCmmnts = "Withdrawal by Self";
                        }
                        $datestr = getFrmtdDB_Date_time();
                        $msgbodyAddOn = "";
                        $msgbodyAddOn .= "WITHDRAWAL ON $datestr:- This document has been Withdraw by $usrFullNm with the ff Message:<br/>";
                        $msgbodyAddOn .= $apprvrCmmnts . "<br/><br/>";
                        $affctd1 += updtWkfMsgRtngCmnts($routingID, $msgbodyAddOn, $userID);
                        $msgbodyAddOn .= $oldMsgbodyAddOn;

                        updateWkfMsgStatus($rtngMsgID, "1", $userID);
                        updtWkfMsgAllUnclsdRtng($rtngMsgID, $fromPrsnID, "Closed", "None", $userID);

//Message Header & Details
                        $msghdr = "WITHDRAWAL - " . $msgTitle;
                        $msgbody = $msgBdy; //$msgbodyAddOn. "ORIGINAL MESSAGE :<br/><br/>" .
                        $msgtyp = "Informational";
                        $msgsts = "0";
//$msg_id = getWkfMsgID();
                        $affctd2 += updateWkfMsg($msg_id, $msghdr, $msgbody, $userID, $appID, $msgtyp, $msgsts, $srcdoctyp, $srcdocid, $hrchyid, $attchmnts, $attchmnts_desc);
                        $affctd3 += routWkfMsg($msg_id, $fromPrsnID, $orgnlPrsnID, $userID, "Initiated", "Acknowledge;Open", 1, $msgbodyAddOn);
                        $affctd4 += updateBulkTrnsStatus($srcdocid, "Withdrawn");

//Begin Email Sending Process                    
                        /* $result = getEmlDetailsAftrActn($srcdoctyp, $srcdocid);
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
                          }
                          } */
                    }
                    if ($affctd > 0) {
                        $dsply .= "<br/>Status of $affctd Workflow Document(s) successfully updated to Withdrawn!";
                        $dsply .= "<br/>$affctd1 Workflow Document(s) Message Body Successfully Updated!";
//$dsply .= "<br/>$affctd2 New Workflow Document(s) Message Body Successfully Created!";
                        $dsply .= "<br/>$affctd3 Workflow Document(s) Successfully Re-Routed to Original Sender!";
                        $dsply .= "<br/>$affctd4 Request Status Successfully Updated!";
                        $msg = "<p style = \"text-align:left; color:#32CD32;\"><span style=\"font-style:italic;font-weight:bold;\">$dsply</span></p>"; //#32CD32
                    } else {
                        $dsply .= "<br/>|ERROR|-Update Failed! No Workflow Document(s) Withdrawn";
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
//$affctd4+=updatePrsDataChangeReq($srcdocid, "Rejected");
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
                    $affctd3 += routWkfMsg($msg_id, $fromPrsnID, $nwPrsnID, $userID, "Initiated", 'Open;Reject;Request for Information;Authorize', $curPrsnsLevel, $msgbodyAddOn);
//$affctd4+=updatePrsDataChangeReq($srcdocid, "Rejected");
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
//$dsply .= "<br/>$affctd4 Appointment Status Successfully Updated!";
                        $msg = "<p style = \"text-align:left; color:#32CD32;\"><span style=\"font-style:italic;font-weight:bold;\">$dsply</span></p>"; //#32CD32
                    } else {
                        $dsply .= "<br/>|ERROR|-Update Failed! No Workflow Document(s) Worked On";
                        $msg = "<p style = \"text-align:left; color:#ff0000;\"><span style=\"font-style:italic;font-weight:bold;\">$dsply</span></p>";
                    }
                } else if ($actionToPrfrm == "Authorize") {
                    $affctd = 0;
                    $dsply .= "orgnlToPrsnID:" . $orgnlToPrsnID . ":cur_prsnid:" . $cur_prsnid;
                    if ($orgnlToPrsnID == $cur_prsnid && $orgnlToPrsnID > 0) {
                        $newStatus = "Reviewed";
                        $newVldtyStatus = "Validated";
                        $nxtStatus = "Open;Reject;Request for Information;Authorize";
                        $nxtApprvr = "Next Approver";
                        $nxtPrsnID = -1;
                        $shdActnBeDone = FALSE;
                        if ($nxtPrsnID <= 0) {
                            $dsply = ""; // "Start Banking Trans. Item Balance Update and Accounting Entries Generation<br/>";
                            $shdActnBeDone = isBulkTrnsQtyVld($srcDocID, $dsply);
                            if ($shdActnBeDone === true) {
                                $dsply = ""; // "Item Balance && Customer Balance Verified<br/>";
                                $newStatus = "Authorized";
                                $nxtStatus = "Open;Acknowledge";
                                $newVldtyStatus = "Validated";
                            } else {
                                $dsply .= "<i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>Error Occured!<br/>Kindly ensure that you have the Requested Quantities in the various Source Cages as well as the Customer's Account 3.0!";
                                $newVldtyStatus = "Not Validated";
                            }
                        }
                        if ($shdActnBeDone === true) {
                            $affctd += updtWkfMsgRtngUsngLvl($rtngMsgID, $curPrsnsLevel, $fromPrsnID, $newStatus, $nxtStatus, $userID);
//$affctd1+= updateWkfMsgBdy($rtngMsgID, $msgbodyAddOn, $userID);
                            $datestr = getFrmtdDB_Date_time();
                            $msgbodyAddOn = "";
                            $msgbodyAddOn .= strtoupper($newStatus) . " ON $datestr:- This document has been $newStatus by $usrFullNm <br/><br/>";
//$msgbodyAddOn.=$apprvrCmmnts . "<br/><br/>";
                            $affctd1 += updtWkfMsgRtngCmnts($routingID, $msgbodyAddOn, $userID);
                            $msgbodyAddOn .= $oldMsgbodyAddOn;
                            updtWkfMsgAllUnclsdRtng($rtngMsgID, $fromPrsnID, "Closed", "None", $userID);
//Message Header & Details
                            if ($nxtPrsnID <= 0) {
                                $nxtApprvr = "Original Person";
                                updateWkfMsgStatus($rtngMsgID, "1", $userID);
                                $msghdr = "AUTHORIZED - " . $msgTitle;
                                $msgbody = $msgBdy;
                                $msgtyp = "Informational";
                                $msgsts = "0";
//$msg_id = getWkfMsgID();
                                $curPrsnsLevel += 1;
                                $affctd2 += updateWkfMsg($msg_id, $msghdr, $msgbody, $userID, $appID, $msgtyp, $msgsts, $srcdoctyp, $srcdocid, $hrchyid, $attchmnts, $attchmnts_desc);
                                $affctd3 += routWkfMsg($msg_id, $fromPrsnID, $orgnlPrsnID, $userID, $newStatus, $nxtStatus, $curPrsnsLevel, $msgbodyAddOn);
                            } else {
                                $msghdr = $msgTitle;
                                $msgbody = $msgBdy;
                                $msgtyp = "Work Document";
                                $msgsts = "0";
//$msg_id = getWkfMsgID();
                                $curPrsnsLevel += 1;
                                $affctd2 += updateWkfMsg($msg_id, $msghdr, $msgbody, $userID, $appID, $msgtyp, $msgsts, $srcdoctyp, $srcdocid, $hrchyid, $attchmnts, $attchmnts_desc);
                                $affctd3 += routWkfMsg($msg_id, $fromPrsnID, $nxtPrsnID, $userID, $newStatus, $nxtStatus, $curPrsnsLevel, $msgbodyAddOn);
                            }
                            $affctd4 += updateBulkTrnsStatus($srcdocid, $newStatus, $cur_prsnid);
                            /* if ($nxtPrsnID <= 0) {
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
                              createMessageQueue($msgBatchID, trim(str_replace(";", ",", $to), ";, "), "", "", $message, $subject, "",
                              "Email");
                              //sendEMail(trim(str_replace(";", ",", $to), ","), $nameto, $subject, $message, $errMsg, "", "", "", $admin_name);
                              }
                              break;
                              }
                              } */
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

function withdrawBulkTrnsRqst($hdrid) {
    $msg = "";
    $rqstHdrStatus = getGnrlRecNm("mcf.mcf_bulk_trns_hdr", "bulk_trns_hdr_id", "status", $hdrid);
    if ($rqstHdrStatus == 'Authorized') {
        return "<span style=\"color:red;\">|ERROR| Nothing to Withdraw!</span>";
    }
    $srcDocID = $hdrid;
    $srcDocType = "Bulk/Batch Transactions";
    $inptSlctdRtngs = "";
    $actionToPrfrm = "Withdraw";
    $selSQL = "SELECT MAX(b.routing_id)
  FROM wkf.wkf_actual_msgs_hdr a, wkf.wkf_actual_msgs_routng b
  WHERE a.msg_id=b.msg_id and a.src_doc_type='" . $srcDocType . "' 
  and a.src_doc_id=" . $hdrid;
    $result1 = executeSQLNoParams($selSQL);
    while ($row = loc_db_fetch_array($result1)) {
        $routingID = $row[0];
        $actionToPrfrm = "Withdraw";
        if ($routingID > 0) {
            $msg = mcfTrnsReqMsgActnsBulk($routingID, $inptSlctdRtngs, $actionToPrfrm, $srcDocID, $srcDocType);
        }
    }
    if ($msg == "") {
        $msg = "<span style=\"color:red;\">|ERROR| Nothing to Withdraw!</span>";
    }
    return $msg;
}

function authorizeBulkTrnsRqst($hdrid) {
    global $prsnid;
    $msg = "";
    $rqstHdrStatus = getGnrlRecNm("mcf.mcf_bulk_trns_hdr", "bulk_trns_hdr_id", "status", $hdrid);
    if ($rqstHdrStatus == 'Authorized' || $rqstHdrStatus == 'Processed') {
        return "<span style=\"color:red;\">|ERROR| Nothing to Authorize!</span>";
    }
    $srcDocID = $hdrid;
    $srcDocType = "Bulk/Batch Transactions";
    $inptSlctdRtngs = "";
    $actionToPrfrm = "Authorize";
    $selSQL = "SELECT MAX(b.routing_id)
  FROM wkf.wkf_actual_msgs_hdr a, wkf.wkf_actual_msgs_routng b
  WHERE a.msg_id=b.msg_id and a.src_doc_type='" . $srcDocType . "' 
  and a.src_doc_id=" . $hdrid . " and b.to_prsn_id = " . $prsnid;
    $result1 = executeSQLNoParams($selSQL);
    while ($row = loc_db_fetch_array($result1)) {
        $routingID = $row[0];
        $actionToPrfrm = "Authorize";
        if ($routingID > 0) {
            $msg = mcfTrnsReqMsgActnsBulk($routingID, $inptSlctdRtngs, $actionToPrfrm, $srcDocID, $srcDocType);
        }
    }
    if ($msg == "") {
        $msg = "<span style=\"color:red;\">|ERROR| Nothing to Authorize!</span>";
    }
    return $msg;
}

function clearChqTrnsRqst($trnsChqID, $actionTyp) {
    /*     * NEW 13062017 * */
    global $usrID;
    $rqstIsCleared = getGnrlRecNm("mcf.mcf_cust_account_trns_cheques", "trns_cheque_id", "is_cleared", $trnsChqID);
    $rqstHdrID = (float) getGnrlRecNm("mcf.mcf_cust_account_trns_cheques", "trns_cheque_id", "acct_trns_id", $trnsChqID);
    $chqType = getGnrlRecNm("mcf.mcf_cust_account_trns_cheques", "trns_cheque_id", "cheque_type", $trnsChqID);
    if (($rqstHdrID <= 0 || $rqstIsCleared == '1') && $actionTyp == "Clear") {
        return "<span style=\"color:red;\">|ERROR| No Uncleared Cheque to Process!</span>";
    } else if (($rqstHdrID <= 0 || $rqstIsCleared != '1') && $actionTyp == "UnClear") {
        return "<span style=\"color:red;\">|ERROR| No Cleared Cheque to Process!</span>";
    }
    $isCLrd = "0";
    $amntMltplier = -1;
    $dateStr1 = "";
    if ($actionTyp == "Clear") {
        $isCLrd = "1";
        $amntMltplier = 1;
        $dateStr1 = getDB_Date_time();
    }
    if ($chqType == "In-House") {
        $rslts = execUpdtInsSQL("UPDATE mcf.mcf_cust_account_trns_cheques
                                        SET last_update_by=" . $usrID . ", 
                                            last_update_date='" . $dateStr1 . "', 
                                            is_cleared='" . $isCLrd . "', 
                                            date_cleared='" . $dateStr1 . "'
                                      WHERE trns_cheque_id=" . $trnsChqID);

        return "<span style=\"color:green;font-weight:bold;\">$rslts In-House Cheque Transaction Status Updated!<br/></span>";
    }
    $srcDocID = $rqstHdrID;
    $dsply = "Start Cheque Trans. Account Balance Update and Accounting Entries Generation<br/>";
    $shdActnBeDone = isClearChqTrnsAcntsAmtVld($srcDocID, $trnsChqID, $amntMltplier);
    if ($shdActnBeDone === true) {
        $dsply .= "Customer Balance Verified<br/>";
        $shdActnBeDone = true;
        if ($shdActnBeDone === true) {
            $shdActnBeDone = clearChqTrnsCstmrAcntBals($srcDocID, $trnsChqID, $amntMltplier);
            if ($shdActnBeDone === true) {
                $dsply .= "<span style=\"color:green;font-weight:bold;\">Customer Balance Updated<br/></span>";
                $nwMsg = "";
                $shdActnBeDone = createClearChqAccntng($srcDocID, $trnsChqID, $amntMltplier, $nwMsg);
                if ($shdActnBeDone === FALSE) {
                    $dsply .= "<span style=\"color:red;font-weight:bold;\">$nwMsg</span>";
                    $dsply .= "<span style=\"color:red;font-weight:bold;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>Error Occured!<br/>Failed to Create Accounting!</span>";
                } else {
                    $dsply .= "<span style=\"color:green;font-weight:bold;\">Accounting Created<br/></span>";
                    execUpdtInsSQL("UPDATE mcf.mcf_cust_account_trns_cheques
                                        SET last_update_by=" . $usrID . ", 
                                            last_update_date='" . $dateStr1 . "', 
                                            is_cleared='" . $isCLrd . "', 
                                            date_cleared='" . $dateStr1 . "'
                                      WHERE trns_cheque_id=" . $trnsChqID);
                }
            }
        } else {
            $dsply .= "<span style=\"color:red;font-weight:bold;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>Error Occured!<br/>Kindly ensure that you have the Requested Quantities in the various Source Cages as well as the Customer's Account and that you have Permission to perform this Action on this Cage/Till 4.0!</span>";
        }
    } else {
        $dsply .= "<span style=\"color:red;font-weight:bold;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>Error Occured!<br/>Kindly ensure that you have the Requested Quantities in the various Source Cages as well as the Customer's Account 5.0!</span>";
    }
    $msg = $dsply;
    return $msg;
}

function getMCFMxRoutingID($srcDocID, $srcDocType = "Banking Transactions", $toPrsnID = -1) {
    $extWhre = "";
    if ($toPrsnID > 0) {
        $extWhre = " and b.to_prsn_id = " . $toPrsnID;
    }
    $selSQL = "SELECT MAX(b.routing_id)
  FROM wkf.wkf_actual_msgs_hdr a, wkf.wkf_actual_msgs_routng b
  WHERE a.msg_id=b.msg_id and a.src_doc_type='" . $srcDocType . "' 
  and a.src_doc_id=" . $srcDocID . $extWhre;
    $result1 = executeSQLNoParams($selSQL);
    while ($row = loc_db_fetch_array($result1)) {
        $routingID = (float) $row[0];
        return $routingID;
    }
    return -1;
}

function mcfTrnsfrOrdrReqMsgActns($routingID = -1, $inptSlctdRtngs = "", $actionToPrfrm = "Initiate", $srcDocID = -1, $srcDocType = "Transfer Transactions") {
    global $app_url;
    global $admin_name;
global $isWflMailAllwd;
    global $isWflMailAllwdID;
    $userID = $_SESSION['USRID'];
    $user_Name = $_SESSION['UNAME'];
    $cur_prsnid = $_SESSION['PRSN_ID'];
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
        if ($srcDocID > 0) {
            $rqstHdrStatus = getGnrlRecNm("mcf.mcf_standing_orders", "stndn_order_id", "status", $srcDocID);
            if ($rqstHdrStatus == 'Authorized' || ($rqstHdrStatus == 'Initiated' && $actionToPrfrm == "Initiate" && $routingID <= 0)) {
                return "<span style=\"color:red;font-weight:bold;\">|ERROR| Nothing to $actionToPrfrm!</span>";
            }
        }
        if ($actionToPrfrm == "Initiate" && $srcDocID > 0) {
            $msg_id = getWkfMsgID();
            $appID = getAppID('Transfer Transactions', 'Banking');
//Requestor
            $prsnid = $fromPrsnID;
            $fullNm = $usrFullNm;
            $prsnLocID = getPersonLocID($prsnid);

            $athrzrLmtID = -1;
            $siteID = -1;
            $trnsTyp = "";
            $crncyID = "";
            $trnsAmnt = "";
            $AcNum = "";
            $AcntTitle = "";
            $rslt1 = get_One_AcntTrnsfrHdr($srcdocid);
            while ($rw1 = loc_db_fetch_array($rslt1)) {
                $siteID = (int) $rw1[22];
                $trnsTyp = "Account Transfers";
                $crncyID = (int) $rw1[31];
                $trnsAmnt = (float) $rw1[7];
                $AcNum = $rw1[2];
                $AcntTitle = $rw1[3];
            }
//Message Header & Details
            $msghdr = "$fullNm ($prsnLocID) Requests for approval of a Transfer/Standing Order Transaction Ac/No.: $AcNum Ac/Title: $AcntTitle";
            $msgbody = "TRANSFER TRANSACTION REQUEST ON ($reqestDte):- "
                    . "A Transfer/Standing Order Transaction Request has been submitted by $fullNm ($prsnLocID) regarding Ac/No.: $AcNum Ac/Title: $AcntTitle"
                    . "<br/>Please open the attached Work Document and attend to this Request.
                      <br/>Thank you.";
            $msgtyp = "Work Document";
            $msgsts = "0";
            $hrchyid = 1; //Get hierarchy ID
            $rslt = getMCFOrdrAttchMnts($srcDocID);
            $attchmnts = ""; //Get Attachments
            $attchmnts_desc = ""; //Get Attachments
            while ($rw = loc_db_fetch_array($rslt)) {
                $attchmnts = $rw[1];
                $attchmnts_desc = $rw[0];
            }
            createWkfMsg($msg_id, $msghdr, $msgbody, $userID, $appID, $msgtyp, $msgsts, $srcdoctyp, $srcdocid, $hrchyid, $attchmnts, $attchmnts_desc);
            //Get Hierarchy Members
            $result = getNextMCFApprvrs($siteID, $trnsTyp, $crncyID, $trnsAmnt);
            $prsnsFnd = 0;
            $lastPrsnID = "|";
            $paramRepsNVals = $prmID . "~" . $msgBatchID . "|-190~HTML";
            while ($row = loc_db_fetch_array($result)) {
                $athrzrLmtID = (float) $row[1];
                $toPrsnID = (float) $row[0];
                $prsnsFnd++;
                if ($toPrsnID > 0) {
                    routWkfMsg($msg_id, $prsnid, $toPrsnID, $userID, 'Initiated', 'Open;Reject;Request for Information;Authorize');
                    $dsply = '<div style="text-align:center;font-weight:bold;font-size:14px;color:blue;">SUCCESS!</br>Your request has been submitted for Approval. Thank you!</div>';
                    $msg = $dsply;
                    //Begin Email Sending Process                    
                    /*if (strtoupper($isWflMailAllwd) == "YES" && $isWflMailAllwdID > 0) {
                        $msgBatchID = getMsgBatchID();
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
                      }} */
                }
            }
            if ($prsnsFnd <= 0) {
                $dsply .= "|ERROR|-No Approval Hierarchy Found<br/>";
                $msg = "<p style = \"text-align:left; color:#ff0000;\"><span style=\"font-style:italic;font-weight:bold;\">$dsply</span></p>";
            } else {
                //Update Request Status to In Process
                updateMCFOrdrStatus($srcdocid, "Initiated");
                updateMCFOrdrAtzrLmtID($srcdocid, $athrzrLmtID);
            }
        } else {
            $dsply .= "|ERROR|-Update Failed! No Workflow Document(s) Generated<br/>";
            $msg = "<p style = \"text-align:left; color:#ff0000;\"><span style=\"font-style:italic;font-weight:bold;\">$dsply</span></p>";
        }
    } else {
        $orgnlToPrsnID = -1;
        if ($routingID > 0) {
            $oldMsgbodyAddOn = "";
            $reslt1 = getWkfMsgRtngData($routingID);
            while ($row = loc_db_fetch_array($reslt1)) {
                $rtngMsgID = (float) $row[0];
                $msg_id = $rtngMsgID;
                $curPrsnsLevel = (float) $row[18];
                $isActionDone = $row[9];
                $oldMsgbodyAddOn = $row[17];
                $orgnlToPrsnID = (float) $row[2];
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
            if ($srcDocID > 0) {
                $rqstHdrStatus = getGnrlRecNm("mcf.mcf_standing_orders", "stndn_order_id", "status", $srcDocID);
                if (($rqstHdrStatus == 'Authorized') && $actionToPrfrm != "Open") {
                    return "<span style=\"color:red;font-weight:bold;\">|ERROR| Nothing to $actionToPrfrm!</span>";
                }
            }
            $srcdoctyp = $srcDocType;
            $srcdocid = $srcDocID;
            $orgnlPrsnID = getUserPrsnID1($orgnlPrsnUsrID);
            if ($isActionDone == '0') {
                if ($actionToPrfrm == "Open") {
                    getMCFOrdrRdOnlyDsply($srcDocID);
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
                    $affctd4 += updateMCFOrdrStatus($srcdocid, "Rejected");

//Begin Email Sending Process                    
                    /* $result = getEmlDetailsAftrActn($srcdoctyp, $srcdocid);
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
                      }
                      } */
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
                    if ($orgnlPrsnID == $cur_prsnid && $orgnlPrsnID > 0) {
                        $affctd += updtWkfMsgRtngUsngLvl($rtngMsgID, $curPrsnsLevel, $fromPrsnID, "Withdrawn", "None", $userID);
                        if ($apprvrCmmnts == "") {
                            $apprvrCmmnts = "Withdrawal by Self";
                        }
                        $datestr = getFrmtdDB_Date_time();
                        $msgbodyAddOn = "";
                        $msgbodyAddOn .= "WITHDRAWAL ON $datestr:- This document has been Withdraw by $usrFullNm with the ff Message:<br/>";
                        $msgbodyAddOn .= $apprvrCmmnts . "<br/><br/>";
                        $affctd1 += updtWkfMsgRtngCmnts($routingID, $msgbodyAddOn, $userID);
                        $msgbodyAddOn .= $oldMsgbodyAddOn;

                        updateWkfMsgStatus($rtngMsgID, "1", $userID);
                        updtWkfMsgAllUnclsdRtng($rtngMsgID, $fromPrsnID, "Closed", "None", $userID);

//Message Header & Details
                        $msghdr = "WITHDRAWAL - " . $msgTitle;
                        $msgbody = $msgBdy; //$msgbodyAddOn. "ORIGINAL MESSAGE :<br/><br/>" .
                        $msgtyp = "Informational";
                        $msgsts = "0";
//$msg_id = getWkfMsgID();
                        $affctd2 += updateWkfMsg($msg_id, $msghdr, $msgbody, $userID, $appID, $msgtyp, $msgsts, $srcdoctyp, $srcdocid, $hrchyid, $attchmnts, $attchmnts_desc);
                        $affctd3 += routWkfMsg($msg_id, $fromPrsnID, $orgnlPrsnID, $userID, "Initiated", "Acknowledge;Open", 1, $msgbodyAddOn);
                        $affctd4 += updateMCFOrdrStatus($srcdocid, "Withdrawn");

//Begin Email Sending Process                    
                        /* $result = getEmlDetailsAftrActn($srcdoctyp, $srcdocid);
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
                          }
                          } */
                    }
                    if ($affctd > 0) {
                        $dsply .= "<br/>Status of $affctd Workflow Document(s) successfully updated to Withdrawn!";
                        $dsply .= "<br/>$affctd1 Workflow Document(s) Message Body Successfully Updated!";
//$dsply .= "<br/>$affctd2 New Workflow Document(s) Message Body Successfully Created!";
                        $dsply .= "<br/>$affctd3 Workflow Document(s) Successfully Re-Routed to Original Sender!";
                        $dsply .= "<br/>$affctd4 Request Status Successfully Updated!";
                        $msg = "<p style = \"text-align:left; color:#32CD32;\"><span style=\"font-style:italic;font-weight:bold;\">$dsply</span></p>"; //#32CD32
                    } else {
                        $dsply .= "<br/>|ERROR|-Update Failed! No Workflow Document(s) Withdrawn";
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
//$affctd4+=updatePrsDataChangeReq($srcdocid, "Rejected");
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
                    $affctd3 += routWkfMsg($msg_id, $fromPrsnID, $nwPrsnID, $userID, "Initiated", 'Open;Reject;Request for Information;Authorize', $curPrsnsLevel, $msgbodyAddOn);
//$affctd4+=updatePrsDataChangeReq($srcdocid, "Rejected");
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
//$dsply .= "<br/>$affctd4 Appointment Status Successfully Updated!";
                        $msg = "<p style = \"text-align:left; color:#32CD32;\"><span style=\"font-style:italic;font-weight:bold;\">$dsply</span></p>"; //#32CD32
                    } else {
                        $dsply .= "<br/>|ERROR|-Update Failed! No Workflow Document(s) Worked On";
                        $msg = "<p style = \"text-align:left; color:#ff0000;\"><span style=\"font-style:italic;font-weight:bold;\">$dsply</span></p>";
                    }
                } else if ($actionToPrfrm == "Authorize") {
                    $affctd = 0;
                    if ($orgnlToPrsnID == $cur_prsnid && $orgnlToPrsnID > 0) {
                        $newStatus = "Reviewed";
                        $newVldtyStatus = "Validated";
                        $nxtStatus = "Open;Reject;Request for Information;Authorize";
                        $nxtApprvr = "Next Approver";
                        $nxtPrsnID = -1;
                        $shdActnBeDone = true;
                        if ($nxtPrsnID <= 0) {
                            $dsply .= "";
                            $shdActnBeDone = true;
                            if ($shdActnBeDone === true) {
                                $dsply = "";
                                $newStatus = "Authorized";
                                $nxtStatus = "Open;Acknowledge";
                                $newVldtyStatus = "Validated";
                            } else {
                                $dsply .= "<i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>Error Occured!<br/>Kindly ensure that you have the Requested Quantities in the various Source Cages as well as the Customer's Account 6.0!";
                                $newVldtyStatus = "Not Validated";
                            }
                        }
                        if ($shdActnBeDone === true) {
                            $affctd += updtWkfMsgRtngUsngLvl($rtngMsgID, $curPrsnsLevel, $fromPrsnID, $newStatus, $nxtStatus, $userID);
//$affctd1+= updateWkfMsgBdy($rtngMsgID, $msgbodyAddOn, $userID);
                            $datestr = getFrmtdDB_Date_time();
                            $msgbodyAddOn = "";
                            $msgbodyAddOn .= strtoupper($newStatus) . " ON $datestr:- This document has been $newStatus by $usrFullNm <br/><br/>";
//$msgbodyAddOn.=$apprvrCmmnts . "<br/><br/>";
                            $affctd1 += updtWkfMsgRtngCmnts($routingID, $msgbodyAddOn, $userID);
                            $msgbodyAddOn .= $oldMsgbodyAddOn;
                            updtWkfMsgAllUnclsdRtng($rtngMsgID, $fromPrsnID, "Closed", "None", $userID);
//Message Header & Details
                            if ($nxtPrsnID <= 0) {
                                $nxtApprvr = "Original Person";
                                updateWkfMsgStatus($rtngMsgID, "1", $userID);
                                $msghdr = "AUTHORIZED - " . $msgTitle;
                                $msgbody = $msgBdy;
                                $msgtyp = "Informational";
                                $msgsts = "0";
//$msg_id = getWkfMsgID();
                                $curPrsnsLevel += 1;
                                $affctd2 += updateWkfMsg($msg_id, $msghdr, $msgbody, $userID, $appID, $msgtyp, $msgsts, $srcdoctyp, $srcdocid, $hrchyid, $attchmnts, $attchmnts_desc);
                                $affctd3 += routWkfMsg($msg_id, $fromPrsnID, $orgnlPrsnID, $userID, $newStatus, $nxtStatus, $curPrsnsLevel, $msgbodyAddOn);
                            } else {
                                $msghdr = $msgTitle;
                                $msgbody = $msgBdy;
                                $msgtyp = "Work Document";
                                $msgsts = "0";
//$msg_id = getWkfMsgID();
                                $curPrsnsLevel += 1;
                                $affctd2 += updateWkfMsg($msg_id, $msghdr, $msgbody, $userID, $appID, $msgtyp, $msgsts, $srcdoctyp, $srcdocid, $hrchyid, $attchmnts, $attchmnts_desc);
                                $affctd3 += routWkfMsg($msg_id, $fromPrsnID, $nxtPrsnID, $userID, $newStatus, $nxtStatus, $curPrsnsLevel, $msgbodyAddOn);
                            }
                            $affctd4 += updateMCFOrdrStatus($srcdocid, $newStatus, $cur_prsnid);
                            /* if ($nxtPrsnID <= 0) {
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
                              createMessageQueue($msgBatchID, trim(str_replace(";", ",", $to), ";, "), "", "", $message, $subject, "",
                              "Email");
                              //sendEMail(trim(str_replace(";", ",", $to), ","), $nameto, $subject, $message, $errMsg, "", "", "", $admin_name);
                              }
                              break;
                              }
                              } */
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

function withdrawMCFOrdrRqst($hdrid) {
    $msg = "";
    $rqstHdrStatus = getGnrlRecNm("mcf.mcf_standing_orders", "stndn_order_id", "status", $hdrid);
    if ($rqstHdrStatus == 'Authorized') {
        return "<span style=\"color:red;\">|ERROR| Nothing to Withdraw!</span>";
    }
    $srcDocID = $hdrid;
    $srcDocType = "Transfer Transactions";
    $inptSlctdRtngs = "";
    $actionToPrfrm = "Withdraw";
    $selSQL = "SELECT MAX(b.routing_id)
  FROM wkf.wkf_actual_msgs_hdr a, wkf.wkf_actual_msgs_routng b
  WHERE a.msg_id=b.msg_id and a.src_doc_type='" . $srcDocType . "' 
  and a.src_doc_id=" . $hdrid;
    $result1 = executeSQLNoParams($selSQL);
    while ($row = loc_db_fetch_array($result1)) {
        $routingID = $row[0];
        $actionToPrfrm = "Withdraw";
        if ($routingID > 0) {
            $msg = mcfTrnsfrOrdrReqMsgActns($routingID, $inptSlctdRtngs, $actionToPrfrm, $srcDocID, $srcDocType);
        }
    }
    if ($msg == "") {
        $msg = "<span style=\"color:red;\">|ERROR| Nothing to Withdraw!</span>";
    }
    return $msg;
}

function cancelMCFOrdrRqst($hdrid) {
    $msg = "";
    $rqstHdrStatus = getGnrlRecNm("mcf.mcf_standing_orders", "stndn_order_id", "status", $hdrid);
    if ($rqstHdrStatus != 'Authorized' && $rqstHdrStatus != 'Executed') {
        return "<span style=\"color:red;\">|ERROR| Nothing to Stop/Cancel!</span>";
    }
    $affctd = execUpdtInsSQL("UPDATE mcf.mcf_standing_orders SET status='Cancelled' WHERE stndn_order_id=" . $hdrid);
    $msg = "<span style=\"color:green;font-weight:bold;\">" . $affctd . " Transfer(s)/Orders Successfully Stopped!</span>";
    return $msg;
}

function authorizeMCFOrdrRqst($hdrid) {
    global $prsnid;
    $msg = "";
    $rqstHdrStatus = getGnrlRecNm("mcf.mcf_standing_orders", "stndn_order_id", "status", $hdrid);
    if ($rqstHdrStatus == 'Authorized') {
        return "<span style=\"color:red;\">|ERROR| Nothing to Authorize!</span>";
    }
    $srcDocID = $hdrid;
    $srcDocType = "Transfer Transactions";
    $inptSlctdRtngs = "";
    $actionToPrfrm = "Authorize";
    $selSQL = "SELECT MAX(b.routing_id)
  FROM wkf.wkf_actual_msgs_hdr a, wkf.wkf_actual_msgs_routng b
  WHERE a.msg_id=b.msg_id and a.src_doc_type='" . $srcDocType . "' 
  and a.src_doc_id=" . $hdrid . " and b.to_prsn_id = " . $prsnid;
    $result1 = executeSQLNoParams($selSQL);
    while ($row = loc_db_fetch_array($result1)) {
        $routingID = $row[0];
        $actionToPrfrm = "Authorize";
        if ($routingID > 0) {
            $msg = mcfTrnsfrOrdrReqMsgActns($routingID, $inptSlctdRtngs, $actionToPrfrm, $srcDocID, $srcDocType);
        }
    }
    if ($msg == "") {
        $msg = "<span style=\"color:red;\">|ERROR| Nothing to Authorize!</span>";
    }
    return $msg;
}

function get_MCFGlIntrfc($searchWord, $searchIn, $offset, $limit_size, $orgID, $dte1, $dte2, $notgonetogl, $imblcnTrns, $usrTrns, $lowVal, $highVal) {
    if ($dte1 != "") {
        $dte1 = cnvrtDMYTmToYMDTm($dte1);
    }
    if ($dte2 != "") {
        $dte2 = cnvrtDMYTmToYMDTm($dte2);
    }
    $strSql = "";
    $to_gl = "";
    $imblnce_trns = "";
    $whereCls = "";
    $usrTrnsSql = "";
    $amntCls = "";
    if ($lowVal != 0 || $highVal != 0) {
        $amntCls = " and ((dbt_amount !=0 and dbt_amount between " . $lowVal . " and " . $highVal .
                ") or (crdt_amount !=0 and crdt_amount between " . $lowVal . " and " . $highVal . "))";
    }
    if ($usrTrns) {
        $usrTrnsSql = " and (trns_source !='SYS') ";
    }
    if ($imblcnTrns) {
        $imblnce_trns = " and (a.interface_id IN (select MAX(v.interface_id)
      from  mcf.mcf_gl_interface v
      group by v.src_doc_typ, v.src_doc_id, v.src_doc_line_id, substring(v.trnsctn_date from 0 for 11)
      having COALESCE(SUM(v.dbt_amount),0)-COALESCE(SUM(v.crdt_amount),0) != 0)
      or a.interface_id IN (select MIN(v.interface_id)
      from  mcf.mcf_gl_interface v
      group by v.src_doc_typ, v.src_doc_id, v.src_doc_line_id, substring(v.trnsctn_date from 0 for 11)
      having COALESCE(SUM(v.dbt_amount),0)-COALESCE(SUM(v.crdt_amount),0) != 0))";
        /*
          (select MAX(v.interface_id)
          from  mcf.mcf_gl_interface v
          group by v.src_doc_typ, v.src_doc_id, abs(v.net_amount), v.src_doc_line_id, substring(v.trnsctn_date from 1 for 10)
          having count(v.src_doc_line_id) %2 != 0)
         * or v.src_doc_id<=0 or v.src_doc_id IS NULL 
          or vms.get_src_doc_num(v.src_doc_id,v.src_doc_typ) IS NULL
          or vms.get_src_doc_num(v.src_doc_id,v.src_doc_typ)='')) */
    }
    if ($notgonetogl) {
        $to_gl = " and (gl_batch_id <= 0)";
    }
    if ($searchIn == "Account Name") {
        $whereCls = "(b.accnt_name ilike '" . loc_db_escape_string($searchWord) .
                "') and ";
    } else if ($searchIn == "Account Number") {
        $whereCls = "(b.accnt_num ilike '" . loc_db_escape_string($searchWord) .
                "') and ";
    } else if ($searchIn == "Source") {
        $whereCls = "(a.src_doc_typ ilike '" . loc_db_escape_string($searchWord) .
                "') and ";
    } else if ($searchIn == "Transaction Description") {
        $whereCls = "(a.transaction_desc ilike '" . loc_db_escape_string($searchWord) .
                "') and ";
    }
    $strSql = "SELECT a.accnt_id, b.accnt_num, b.accnt_name, a.transaction_desc, 
to_char(to_timestamp(a.trnsctn_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS'), a.dbt_amount, " .
            "a.crdt_amount, a.src_doc_line_id, a.src_doc_typ, a.gl_batch_id, " .
            "(select d.batch_name from accb.accb_trnsctn_batches d where d.batch_id = a.gl_batch_id) btch_nm, " .
            "a.interface_id, a.func_cur_id, a.src_doc_id, vms.get_src_doc_num(a.src_doc_id,a.src_doc_typ), "
            . "gst.get_pssbl_val(a.func_cur_id), a.trns_source " .
            "FROM mcf.mcf_gl_interface a, accb.accb_chart_of_accnts b " .
            "WHERE ((a.accnt_id = b.accnt_id) and " . $whereCls . "(b.org_id = " . $orgID . ")" . $to_gl .
            $imblnce_trns . $usrTrnsSql . $amntCls . " and (to_timestamp(a.trnsctn_date,'YYYY-MM-DD HH24:MI:SS') between to_timestamp('" . $dte1 .
            "','YYYY-MM-DD HH24:MI:SS') AND to_timestamp('" . $dte2 . "','YYYY-MM-DD HH24:MI:SS'))) " .
            "ORDER BY a.interface_id DESC LIMIT " . $limit_size . " OFFSET " . abs($offset * $limit_size);
    //echo $strSql;
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_MCFGlIntrfcTtl($searchWord, $searchIn, $orgID, $dte1, $dte2, $notgonetogl, $imblcnTrns, $usrTrns, $lowVal, $highVal) {
    global $gnrlTrnsDteYMDHMS;
    execUpdtInsSQL("UPDATE mcf.mcf_gl_interface SET trnsctn_date='" . $gnrlTrnsDteYMDHMS . "' WHERE trnsctn_date=''");
    if ($dte1 != "") {
        $dte1 = cnvrtDMYTmToYMDTm($dte1);
    }
    if ($dte2 != "") {
        $dte2 = cnvrtDMYTmToYMDTm($dte2);
    }
    $strSql = "";
    $to_gl = "";
    $imblnce_trns = "";
    $whereCls = "";
    $usrTrnsSql = "";
    $amntCls = "";
    if ($lowVal != 0 || $highVal != 0) {
        $amntCls = " and ((dbt_amount !=0 and dbt_amount between " . $lowVal . " and " . $highVal .
                ") or (crdt_amount !=0 and crdt_amount between " . $lowVal . " and " . $highVal . "))";
    }
    if ($usrTrns) {
        $usrTrnsSql = " and (trns_source !='SYS') ";
    }

    if ($imblcnTrns) {
        $imblnce_trns = " and (a.interface_id IN (select MAX(v.interface_id)
      from  mcf.mcf_gl_interface v
      group by v.src_doc_typ, v.src_doc_id, v.src_doc_line_id, substring(v.trnsctn_date from 0 for 11)
      having COALESCE(SUM(v.dbt_amount),0)-COALESCE(SUM(v.crdt_amount),0) != 0)
      or a.interface_id IN (select MIN(v.interface_id)
      from  mcf.mcf_gl_interface v
      group by v.src_doc_typ, v.src_doc_id, v.src_doc_line_id, substring(v.trnsctn_date from 0 for 11)
      having COALESCE(SUM(v.dbt_amount),0)-COALESCE(SUM(v.crdt_amount),0) != 0))";
        /* or v.src_doc_id<=0 or v.src_doc_id IS NULL 
          or vms.get_src_doc_num(v.src_doc_id,v.src_doc_typ) IS NULL
          or vms.get_src_doc_num(v.src_doc_id,v.src_doc_typ)='')) */
    }

    if ($notgonetogl) {
        $to_gl = " and (gl_batch_id <= 0)";
    }

    if ($searchIn == "Account Name") {
        $whereCls = "(b.accnt_name ilike '" . loc_db_escape_string($searchWord) .
                "') and ";
    } else if ($searchIn == "Account Number") {
        $whereCls = "(b.accnt_num ilike '" . loc_db_escape_string($searchWord) .
                "') and ";
    } else if ($searchIn == "Source") {
        $whereCls = "(a.src_doc_typ ilike '" . loc_db_escape_string($searchWord) .
                "') and ";
    } else if ($searchIn == "Transaction Description") {
        $whereCls = "(a.transaction_desc ilike '" . loc_db_escape_string($searchWord) .
                "') and ";
    }
    $strSql = "SELECT count(1) " .
            "FROM mcf.mcf_gl_interface a, accb.accb_chart_of_accnts b " .
            "WHERE ((a.accnt_id = b.accnt_id) and " . $whereCls . "(b.org_id = " . $orgID . ")" . $to_gl .
            $imblnce_trns . $usrTrnsSql . $amntCls . " and (to_timestamp(a.trnsctn_date,'YYYY-MM-DD HH24:MI:SS') between to_timestamp('" . $dte1 .
            "','YYYY-MM-DD HH24:MI:SS') AND to_timestamp('" . $dte2 . "','YYYY-MM-DD HH24:MI:SS'))) ";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return 0;
}

function get_OneMCFGlIntrfcDet($intrfcID) {
    $strSql = "SELECT a.accnt_id, b.accnt_num, b.accnt_name, a.transaction_desc, 
to_char(to_timestamp(a.trnsctn_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS'), a.dbt_amount, " .
            "a.crdt_amount, a.src_doc_line_id, a.src_doc_typ, a.gl_batch_id, " .
            "(select d.batch_name from accb.accb_trnsctn_batches d where d.batch_id = a.gl_batch_id) btch_nm, a.interface_id, a.func_cur_id, " .
            "a.src_doc_id, vms.get_src_doc_num(a.src_doc_id,a.src_doc_typ), gst.get_pssbl_val(a.func_cur_id), 
             a.trns_source, a.net_amount, a.entered_amnt, a.entered_amt_crncy_id, 
             gst.get_pssbl_val(a.entered_amt_crncy_id), a.accnt_crncy_amnt, a.accnt_crncy_id, 
             gst.get_pssbl_val(a.accnt_crncy_id), 
             a.func_cur_exchng_rate, a.accnt_cur_exchng_rate " .
            "FROM mcf.mcf_gl_interface a, accb.accb_chart_of_accnts b " .
            "WHERE ((a.accnt_id = b.accnt_id) and (a.interface_id = " . $intrfcID . "))";
    $result = executeSQLNoParams($strSql);
    return $result;
}

function getMCFTrnsRdOnlyDsply($sbmtdTrnsHdrID, $trnsType) {
    global $canview;
    if (!$canview) {
        restricted();
        exit();
    }
    $trnsStatus = "Not Submitted";
    $rqstatusColor = "red";
    $gnrtdTrnsNo = "";
    $gnrtdTrnsDate = date('d-M-Y H:i:s');
    $prprdBy = "";
    $trnsType = "";
    $acctTitle = "";
    $unclrdBal = 0;
    $clrdBal = 0;
    $docType = "";
    $docNum = "";
    $trnsAmount = 0;
    $cashAmount = 0;
    $trnsDesc = "";
    $acctNo = "";
    $accntID = -1;
    $acctStatus = "";
    $acctCrncy = "";
    $crncyID = -1;
    $crncyIDNm = "";
    $acctType = "";
    $acctCustomer = "";
    $prsnTypeEntity = "";
    $acctLien = 0;
    $mandate = "";
    $authorizer = "";
    $aprvLimit = 0;
    $wtdrwlLimitNo = 0;
    $wtdrwlLimitAmt = 0;
    $wtdrwlLimitType = "";
    $voidedTrnsHdrID = -1;
    $voidedTrnsType = "";
    $dbOrCrdt = "CR";
    $trnsPersonName = "";
    $trnsPersonTelNo = "";
    $trnsPersonAddress = "";
    $trnsPersonIDType = "";
    $trnsPersonIDNumber = "";
    $trnsPersonType = "";
    $mkReadOnly = "";
    $mkRmrkReadOnly = "";
    $vwOrAdd = "VIEW";
    $pageCaption = "<span style=\"font-weight:bold;\">TOTAL " . $trnsType . ": </span><span style=\"font-weight:bold;color:blue;\" id=\"tllrTrnsAmntTtlFld\">" . $acctCrncy . " " . number_format($trnsAmount, 2) . "</span>";
    $brnchLocID = -1;
    $brnchLoc = "";
    $accntBrnchLocID = -1;
    $accntBrnchLoc = "";

    $loanRepayType = "";
    $loanRpmntSrcAcctID = -1;
    $loanRpmntSrcAcct = "";
    $loanRpmntSrcAmnt = 0.00;
    $bnkCustomerID = -1;

    $unclrdColor = "blue";
    $clrdColor = "blue";
    $subPgNo = "3.1.1";
    $trnsByNm = "Withdrawer's Details";
    $trnsByNm1 = "Withdrawal By:";
    $dsplyMndt = "";
    if ($sbmtdTrnsHdrID > 0) {
//Important! Must Check if One also has prmsn to Edit brought Trns Hdr ID
        $result = get_OneCustAccntTrnsDet_LoanRpmnt($sbmtdTrnsHdrID);
        if ($row = loc_db_fetch_array($result)) {
            $trnsType = $row[5];
            if (strpos($trnsType, "DEPOSIT") !== FALSE) {
                $subPgNo = "3.1.2";
                $trnsByNm = "Depositor's Details";
                $trnsByNm1 = "Deposited By:";
                $dsplyMndt = "display:none;";
            }
            $trnsStatus = $row[7];
            $voidedTrnsHdrID = (float) $row[28];
            $voidedTrnsType = $row[29];
            if ($trnsStatus == "Not Submitted" || $trnsStatus == "Withdrawn" || $trnsStatus == "Rejected") {
                $rqstatusColor = "red";
                if ($voidedTrnsHdrID <= 0) {
                    $mkReadOnly = "readonly=\"true\"";
                    $mkRmrkReadOnly = "readonly=\"true\"";
                } else {
                    $mkReadOnly = "readonly=\"true\"";
                    $mkRmrkReadOnly = "readonly=\"true\"";
                    $vwOrAdd = "VIEW";
                }
            } else if ($trnsStatus != "Authorized" && $trnsStatus != "Paid" && $trnsStatus != "Received" && $trnsStatus != "Void") {
                $mkReadOnly = "readonly=\"true\"";
                $mkRmrkReadOnly = "readonly=\"true\"";
                $rqstatusColor = "brown";
                $vwOrAdd = "VIEW";
            } else if ($trnsStatus == "Void") {
                $mkReadOnly = "readonly=\"true\"";
                $mkRmrkReadOnly = "readonly=\"true\"";
                $rqstatusColor = "red";
                $vwOrAdd = "VIEW";
            } else {
                $rqstatusColor = "green";
                $mkReadOnly = "readonly=\"true\"";
                $mkRmrkReadOnly = "readonly=\"true\"";
                $vwOrAdd = "VIEW";
            }
            $gnrtdTrnsNo = $row[12];
            $gnrtdTrnsDate = $row[11];
            $uName11 = getUserName((float) $row[32]);
            $prprdBy = "<span style=\"color:blue;font-weight:bold;\">" . $uName11 . "@" . $gnrtdTrnsDate . "</span>";
            $brnchLocID = (int) $row[14];
            $brnchLoc = getGnrlRecNm("org.org_sites_locations", "location_id", "location_code_name", $brnchLocID);
            $accntBrnchLocID = (int) $row[33];
            $accntBrnchLoc = getGnrlRecNm("org.org_sites_locations", "location_id", "REPLACE(location_code_name || '.' || site_desc, '.' || location_code_name,'')", $accntBrnchLocID);
            $crncyID = (int) $row[9];
            $crncyIDNm = $row[10];
            if ($voidedTrnsHdrID <= 0) {
                $trnsDesc = $row[31];
            } else {
                $trnsDesc = $row[30];
            }
            $acctTitle = $row[2];
            $docType = $row[15];
            $docNum = $row[16];
            $trnsAmount = $row[6];
            $cashAmount = $row[27];
            $acctNo = $row[1];
            $accntID = (int) $row[8];
            $acctCrncy = $row[10];
            $authorizer = $row[34];
            $aprvLimit = (float) $row[36];
            $dbOrCrdt = $row[24];
            $pageCaption = "<span style=\"font-weight:bold;\">TOTAL " . $trnsType . ": </span><span style=\"font-weight:bold;color:blue;\" id=\"tllrTrnsAmntTtlFld\">" . $acctCrncy . " " . number_format($trnsAmount, 2) . "</span>";
            $unclrdBal = (float) $row[37];
            $clrdBal = (float) $row[38];
            if ($unclrdBal > 0) {
                $unclrdColor = "green";
            } else {
                $unclrdColor = "red";
            }
            if ($clrdBal > 0) {
                $clrdColor = "green";
            } else {
                $clrdColor = "red";
            }
            $acctType = $row[39];
            $acctStatus = $row[40];
            $acctCustomer = $row[41];
            $prsnTypeEntity = $row[42];
            $acctLien = (float) $row[43];
            $mandate = $row[44];

            $wtdrwlLimitNo = $row[45];
            $wtdrwlLimitAmt = $row[46];
            $wtdrwlLimitType = $row[47];

            $trnsPersonName = $row[17];
            $trnsPersonTelNo = $row[18];
            $trnsPersonAddress = $row[19];
            $trnsPersonIDType = $row[20];
            $trnsPersonIDNumber = $row[21];
            $trnsPersonType = $row[22];
            $loanRepayType = $row[53];
            $loanRpmntSrcAcctID = $row[54];
            $loanRpmntSrcAcct = getGnrlRecNm("mcf.mcf_accounts", "account_id", "account_number", $row[54]);
            $loanRpmntSrcAmnt = number_format($row[55], 2);
            $bnkCustomerID = $row[56];
        }
    } else {
        restricted();
        exit();
    }
    $routingID = getMCFLoanTrnsMxRoutingID($sbmtdTrnsHdrID);
    $trnsNum = str_replace(" ()", "", " (" . $gnrtdTrnsNo . ")@" . $brnchLoc);
    ?>
    <fieldset class="" style="padding: 5px 2px 5px 2px !important;">
        <legend class="basic_person_lg1" style="color: #003245"><?php echo $pageCaption; ?></legend>
        <input class="form-control" id="addOrEditForm" type = "hidden" placeholder="addOrEditForm" value="Add"/>    
        <input class="form-control" id="mcfTlrTrnsType" type = "hidden" value="<?php echo $trnsType; ?>"/>                      
        <div class="row">                  
            <div class="col-md-12">
                <div class="custDiv" style="border:none !important; padding:0px !important;">
                    <div id="prflHomeEDT" style="border:none !important;">  
                        <div class="row" style="margin: 0px 0px 5px 0px !important;">
                            <div class="col-md-6" style="padding:0px 1px 0px 1px !important;">
                                <div class="form-group form-group-sm">
                                    <div class="col-md-6" style="padding:0px 1px 0px 1px !important;">
                                        <div class="input-group">
                                            <input class="form-control rqrdFld" id="acctNoFind" style="font-size: 13px !important;font-weight: bold !important;" placeholder="Enter Account Number" type = "text" min="0" placeholder="" value="<?php echo $acctNo; ?>" <?php echo $mkReadOnly; ?>/>
                                            <input type="hidden" id="acctNoFindAccId" value="<?php echo ''; ?>">
                                            <input type="hidden" id="acctNoFindRawTxt" value="<?php echo ''; ?>">
                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getCustAcctsForm('myFormsModalCB', 'myFormsModalBodyCB', 'myFormsModalTitleCB', 'View Customer Account', 13, 2.1, 0, 'VIEW', <?php echo $accntID; ?>, 'custAcctTable', '', '<?php echo $acctNo; ?>');" data-toggle="tooltip" data-placement="bottom" title = "Latest Account Information">
                                                <span class="glyphicon glyphicon-info-sign"></span>
                                            </label>
                                        </div>
                                        <input class="form-control" id="acctTrnsId" placeholder="Transaction ID" type = "hidden" placeholder="" value="<?php echo $sbmtdTrnsHdrID; ?>"/>
                                    </div>
                                    <div class="col-md-6" style="padding:0px 1px 0px 0px !important;">
                                        <div style="float:left;">
                                            <button type="button" class="btn btn-default btn-sm" style="height: 30px;" onclick="getOneMcfDocsForm('<?php echo $trnsType; ?>', 140);" data-toggle="tooltip" data-placement="bottom" title = "Attached Documents">
                                                <img src="cmn_images/adjunto.png" style="height:17px; width:auto; position: relative; vertical-align: middle;">
                                                Attachments
                                            </button>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-info btn-sm" style="height: 30px !important;" onclick="">
                                                    My Tills
                                                </button>
                                                <button class="btn btn-info dropdown-toggle btn-sm" style="height:30px !important;" type="button" data-toggle="dropdown">
                                                    <span class="caret"></span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>                                               
                            </div>
                            <div class="col-md-6" style="padding:0px 0px 0px 1px !important;">
                                <div style="padding:0px 1px 0px 15px !important;float:left;">
                                    <button type="button" class="btn btn-default btn-sm" style="height:30px;" id="myVmsTrnsStatusBtn">
                                        <span style="font-weight:bold;">Status: </span><span style="color:<?php echo $rqstatusColor; ?>;font-weight: bold;"><?php echo $trnsStatus; ?></span>
                                    </button>
                                </div>
                                <div style="padding:0px 1px 0px 1px !important;float:right;">
                                    <?php
                                    if ($trnsStatus == "Not Submitted" || $trnsStatus == "Withdrawn" || $trnsStatus == "Rejected") {
                                        
                                    } else {
                                        ?>
                                        <button type="button" class="btn btn-default btn-sm" style="height:30px;" onclick="checkWkfRqstStatus(<?php echo $routingID; ?>, 'Teller Trns. Authorization Progress History');"><img src="cmn_images/workflow.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;" data-toggle="tooltip" title="Authorization Progress History">Progress&nbsp;</button>
                                        <?php
                                    }
                                    if ($trnsStatus == "Received" || $trnsStatus == "Paid") {
                                        $reportTitle = "Deposit Transaction Receipt";
                                        if ($trnsStatus == "Paid") {
                                            $reportTitle = "Withdrawal Transaction";
                                        }
                                        $reportName = "Teller Transaction Receipt (A4)";
                                        $rptID = getRptID($reportName);
                                        $prmID1 = getParamIDUseSQLRep("{:trns_id}", $rptID);
                                        $prmID2 = getParamIDUseSQLRep("{:documentTitle}", $rptID);
                                        $invcID = $sbmtdTrnsHdrID;
                                        $paramRepsNVals = $prmID1 . "~" . $invcID . "|" . $prmID2 . "~" . $reportTitle . "|-190~PDF";
                                        $paramStr = urlencode($paramRepsNVals);
                                        ?>
                                        <button type="button" class="btn btn-default btn-sm" style="height:30px;" title="Print Voucher" onclick="getSilentRptsRnSts(<?php echo $rptID; ?>, -1, '<?php echo $paramStr; ?>');">
                                            <img src="cmn_images/printer-icon.png" style="left: 0.5%; padding-right: 5px; height:17px; width:17px; position: relative; vertical-align: middle;">PRINT</button>                                            
                                    <?php } ?>
                                </div>
                            </div>
                        </div>                                          
                        <form class="form-horizontal">
                            <div class="row">
                                <div class="col-lg-6"> 
                                    <fieldset class=""><legend class="basic_person_lg1">TRNS.<?php echo $trnsNum; ?></legend>
                                        <div class="form-group form-group-sm">
                                            <label for="acctTitle" class="control-label col-md-4">Account Title:</label>
                                            <div  class="col-md-8">
                                                <input class="form-control" id="acctTitle" type = "text" placeholder="" value="<?php echo $acctTitle; ?>" readonly="readonly"/>
                                                <input class="form-control" id="ttlDocAmntVal" type = "hidden" placeholder="" value="<?php echo $trnsAmount; ?>" readonly="readonly"/>
                                            </div>
                                        </div> 
                                        <div class="form-group form-group-sm">
                                            <label for="unclrdBal" class="control-label col-md-4">Current Balance (B4):</label>
                                            <div  class="col-md-8">
                                                <div class="input-group">
                                                    <span class="input-group-addon" id="unclrdBalCrncy" style="font-weight:bold;"><?php echo $acctCrncy; ?></span>
                                                    <input id="unclrdBal" class="form-control"  type = "text" placeholder="" aria-describedby="unclrdBalCrncy" readonly="readonly" style="font-weight: bold;color:<?php echo $unclrdColor; ?>;font-size:16px !important;" placeholder="" value="<?php
                                                    echo number_format($unclrdBal, 2);
                                                    ?>"/>
                                                </div>                                                                
                                            </div>                                                            
                                        </div>                                                        
                                        <div class="form-group form-group-sm">
                                            <label for="clrdBal" class="control-label col-md-4">Available Balance (B4):</label>
                                            <div  class="col-md-8">
                                                <div class="input-group">
                                                    <span class="input-group-addon" id="clrdBalCrncy" style="font-weight:bold;"><?php echo $acctCrncy; ?></span>
                                                    <input id="clrdBal" class="form-control"  type = "text" placeholder="" style="font-weight: bold;color:<?php echo $clrdColor; ?>;font-size:16px !important;" type = "text" placeholder="" value="<?php
                                                    echo number_format($clrdBal, 2);
                                                    ?>" aria-describedby="clrdBalCrncy" readonly="readonly"/>
                                                </div>                                                                
                                            </div>                                                            
                                        </div> 
                                        <div class="form-group form-group-sm">
                                            <label for="docType" class="control-label col-md-4">Document Type:</label>
                                            <div class="col-md-4">
                                                <select class="form-control" id="docType" <?php echo $mkReadOnly; ?> onchange="mcfTrnsDocTypeChng();">
                                                    <?php
                                                    $brghtStr = "";
                                                    $isDynmyc = FALSE;
                                                    $titleRslt = getLovValues("%", "Both", 0, 100, $brghtStr, getLovID("MCF Deposit Document Types"), $isDynmyc, -1, "", "");
                                                    while ($titleRow = loc_db_fetch_array($titleRslt)) {
                                                        $selectedTxt = "";
                                                        if ($titleRow[0] == $docType) {
                                                            $selectedTxt = "selected";
                                                        }
                                                        ?>
                                                        <option value="<?php echo $titleRow[0]; ?>" <?php echo $selectedTxt; ?>><?php echo $titleRow[0]; ?></option>
                                                        <?php
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <div  class="col-md-4" style="padding-left: 1px !important;">
                                                <input class="form-control" id="docNum" type = "text" placeholder="Document No" value="<?php echo $docNum; ?>" <?php echo $mkReadOnly; ?>/>
                                            </div>
                                        </div>
                                        <div class="form-group form-group-sm">
                                            <label for="loanRepayType" class="control-label col-md-4">Repayment Type:</label>
                                            <div class="col-md-8">
                                                <select class="form-control" id="loanRepayType" <?php echo $mkReadOnly; ?> onchange="">
                                                    <?php
                                                    $selectedTxtRpymnt = "";
                                                    $selectedTxtStlmnt = "";
                                                    if ($loanRepayType == "REPAYMENT") {
                                                        $selectedTxtRpymnt = "selected";
                                                    } else if ($loanRepayType == "SETTLEMENT") {
                                                        $selectedTxtStlmnt = "selected";
                                                    }
                                                    ?>
                                                    <option value="REPAYMENT" <?php echo $selectedTxtRpymnt; ?>>REPAYMENT</option>
                                                    <option value="SETTLEMENT" <?php echo $selectedTxtStlmnt; ?>>SETTLEMENT</option>
                                                </select>
                                            </div>
                                        </div>                                                        
                                        <div class="form-group form-group-sm">
                                            <label for="trnsAmount"  style="font-size: 20px !important;" class="control-label col-md-4">Amount:</label>
                                            <input class="form-control" id="trnsAmntRaw" type = "hidden" min="0" placeholder="Amount Raw" value="<?php echo $cashAmount;
                                                    ?>"/>
                                            <input class="form-control" id="trnsAmntRaw1" type = "hidden" value="<?php
                                            echo $cashAmount;
                                            ?>"/>
                                            <div class="col-md-6">
                                                <div class="input-group input-group-lg">
                                                    <label class="btn btn-info btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Currencies', '', '', '', 'radio', true, '<?php echo $crncyIDNm; ?>', 'mcfPymtCrncyNm', '', 'clear', 0, '', function () {
                                                                    $('#trnsAmountCrncy').html($('#mcfPymtCrncyNm').val());

                                                                });">
                                                        <span class="" style="font-size: 20px !important;" id="trnsAmountCrncy"><?php echo $crncyIDNm; ?> </span>
                                                    </label>
                                                    <input type="hidden" id="mcfPymtCrncyNm" value="<?php echo $crncyIDNm; ?>">
                                                    <input class="form-control" style="height:46px !important; font-size: 20px !important;text-align: right !important;" id="trnsAmount" type = "text" placeholder="" value="<?php
                                                    echo number_format($cashAmount, 2);
                                                    ?>" aria-describedby="trnsAmountCrncy" readonly="readonly"/>                                                               
                                                </div>                                                                    
                                            </div>
                                            <div class="col-md-2" style="padding-left:0px !important;">
                                                <button type="button" class="btn btn-default btn-lg" onclick="getCashBreakdown('myFormsModaly', 'myFormsModalyBody', 'myFormsModalyTitle', 'cashDenominationsForm', '', 'Cash Breakdown', 14, '<?php echo $subPgNo; ?>', 2, '<?php echo $vwOrAdd; ?>');" style="width:100% !important;height: 46px !important;" title="Cash Breakdown">
                                                    <img src="cmn_images/cash_breakdown.png" style="left: 0.5%; padding-right: 5px; height:35px; width:auto; position: relative; vertical-align: middle;">
                                                </button>
                                            </div>
                                        </div>   
                                        <div class="form-group form-group-sm">
                                            <label for="trnsDesc" class="control-label col-md-4">Narration:</label>
                                            <div  class="col-md-8">
                                                <textarea class="form-control" id="trnsDesc" cols="2" placeholder="Narration/Remarks" rows="2" <?php echo $mkRmrkReadOnly; ?>><?php echo $trnsDesc; ?></textarea>
                                            </div>
                                        </div> 
                                    </fieldset>   
                                </div>                                                
                                <div class="col-lg-6">
                                    <fieldset class=""><legend class="basic_person_lg1">Account Details</legend>
                                        <div class="form-group form-group-sm">
                                            <label for="acctNo" class="control-label col-md-4">Account Number:</label>
                                            <div class="col-md-8">
                                                <input class="form-control" id="acctNo" type = "text" placeholder="" value="<?php echo $acctNo; ?>" readonly="readonly"/>  
                                                <input class="form-control" id="acctID" placeholder="Account ID" type = "hidden" placeholder="" value="<?php echo $accntID; ?>"/>
                                            </div>
                                        </div>
                                        <div class="form-group form-group-sm">
                                            <label for="acctStatus" class="control-label col-md-4">Account Status:</label>
                                            <div  class="col-md-8">
                                                <input class="form-control" id="acctStatus" type = "text" placeholder="" value="<?php echo $acctStatus; ?>" readonly="readonly"/>
                                            </div>
                                        </div>
                                        <div class="form-group form-group-sm">
                                            <label for="acctCrncy" class="control-label col-md-4">Currency:</label>
                                            <div  class="col-md-8">
                                                <input class="form-control" id="acctCrncy" type = "text" placeholder="" value="<?php echo $acctCrncy; ?>" readonly="readonly"/>
                                            </div>
                                        </div>
                                        <div class="form-group form-group-sm">
                                            <label for="acctType" class="control-label col-md-4">Account Type:</label>
                                            <div  class="col-md-8">
                                                <input class="form-control" id="acctType" type = "text" placeholder="" value="<?php echo $acctType; ?>" readonly="readonly"/>
                                            </div>
                                        </div>
                                        <div class="form-group form-group-sm">
                                            <label for="acctCustomer" class="control-label col-md-4">Customer:</label>
                                            <div  class="col-md-8">
                                                <input class="form-control" id="acctCustomer" type = "text" placeholder="" value="<?php echo $acctCustomer; ?>" readonly="readonly"/>
                                            </div>
                                        </div>
                                        <div style="display:none !important;" class="form-group form-group-sm">
                                            <label for="prsnTypeEntity" class="control-label col-md-4">Person Type/Entity:</label>
                                            <div  class="col-md-8">
                                                <input class="form-control" id="prsnTypeEntity" type = "text" placeholder="" value="<?php echo $prsnTypeEntity; ?>" readonly="readonly"/>
                                            </div>
                                        </div>
                                        <div class="form-group form-group-sm">
                                            <label for="acctBranch" class="control-label col-md-4">Branch:</label>
                                            <div class="col-md-8">
                                                <input class="form-control" id="acctBranch" type = "text" placeholder="" value="<?php echo $accntBrnchLoc; ?>" readonly="readonly"/>
                                            </div>
                                        </div>
                                        <div class="form-group form-group-sm">
                                            <label for="acctLien" class="control-label col-md-4">Lien on Account:</label>
                                            <div  class="col-md-8">
                                                <input class="form-control" id="acctLien" type = "text" placeholder="" value="<?php
                                                echo number_format($acctLien, 2);
                                                ?>" readonly="readonly"/>
                                            </div>
                                        </div>
                                        <div class="form-group form-group-sm">
                                            <label for="loanRpmntSrcAcct" class="control-label col-md-4">Repayment Source:</label>
                                            <div  class="col-md-8">
                                                <div class="input-group col-md-12">
                                                    <div  class="col-md-7" style="padding-left:0px !important; padding-right: 0px !important; ">
                                                        <div class="input-group">
                                                            <input type="text" class="form-control" aria-label="..." id="loanRpmntSrcAcct" value="<?php echo $loanRpmntSrcAcct; ?>" readonly>
                                                            <input type="hidden" id="loanRpmntSrcAcctID" value="<?php echo $loanRpmntSrcAcctID; ?>">
                                                            <input type="hidden" id="bnkCustomerID" value="<?php echo $bnkCustomerID; ?>">
                                                            <label class="btn btn-primary btn-file input-group-addon" onclick="listCustAccountsForRpmnt();">
                                                                <span class="glyphicon glyphicon-th-list"></span>
                                                            </label>
                                                            <label class="btn btn-primary btn-file input-group-addon" onclick="javascript:$('#loanRpmntSrcAcctID').val(-1); $('#loanRpmntSrcAcct').val('');">
                                                                <span class="glyphicon glyphicon-remove"></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div  class="col-md-5" style="padding-right: 0px !important; padding-left:2px !important;">
                                                        <div class="input-group">
                                                            <label  for="loanRpmntSrcAmnt" class="btn btn-primary btn-file input-group-addon" onclick="">
                                                                GHS
                                                            </label>
                                                            <input type="text" class="form-control" aria-label="..." id="loanRpmntSrcAmntDsply"  style="font-weight: bold; font-size: 14px !important;"  <?php echo $mkReadOnly; ?> onblur="formatAmount('loanRpmntSrcAmnt', 'loanRpmntSrcAmntDsply');" value="<?php echo $loanRpmntSrcAmnt; ?>">
                                                            <input type="hidden" readonly="readonly" class="form-control rqrdFld" aria-label="..." id="loanRpmntSrcAmnt" value="<?php echo $row[55]; ?>" <?php echo $mkReadOnly; ?>>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>
                                </div>
                            </div> 
                            <?php
                            if (strpos($trnsType, "DEPOSIT") !== FALSE) {
                                ?>
                                <fieldset class=""><legend class="basic_person_lg1" style="color: #003245">Cheque Details</legend>                                
                                    <div class="row">
                                        <div class="col-md-12" id="oneVmsTrnsLnsTblSctn">
                                            <table class="table table-striped table-bordered table-responsive" id="oneVmsTrnsLnsTable" cellspacing="0" width="100%" style="width:100%;min-width: 900px !important;">
                                                <thead>
                                                    <tr>
                                                        <th>No.</th>
                                                        <th style="display:none;">Type</th>
                                                        <th class="extnl">Bank</th>
                                                        <th class="extnl">Branch</th>
                                                        <th>CHQ. No.</th>
                                                        <th>CHQ. Date</th>
                                                        <th>CUR.</th>
                                                        <th>CHQ. Amount</th>
                                                        <th>Rate</th>
                                                        <th>Value Date</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $acntChqsRslt = getAccountTrnsChqs($sbmtdTrnsHdrID);
                                                    $cntr = 0;
                                                    while ($rwChqs = loc_db_fetch_array($acntChqsRslt)) {
                                                        $cntr++;
                                                        $chqType = $rwChqs[9];
                                                        $inptType = "text";
                                                        $dsplyLable = "";
                                                        $dsplySpan = "display:none;";
                                                        if ($chqType != "External") {
                                                            $inptType = "hidden";
                                                            $dsplyLable = "display:none;";
                                                            $dsplySpan = "";
                                                        }
                                                        ?>
                                                        <tr id="oneVmsTrnsRow_<?php echo $cntr; ?>">
                                                            <td class="lovtd"><span class=""><?php echo $cntr; ?></span></td>
                                                            <td class="lovtd" style="display:none;">
                                                                <div class="input-group" style="width:100% !important;">
                                                                    <input type="text" class="form-control" aria-label="..." id="oneVmsTrnsRow<?php echo $cntr; ?>_chqType" name="oneVmsTrnsRow<?php echo $cntr; ?>_chqType" value="<?php echo $rwChqs[9]; ?>" readonly="true" style="width:100% !important;">
                                                                    <input type="hidden" class="form-control" aria-label="..." id="oneVmsTrnsRow<?php echo $cntr; ?>_chqTypeID" value="<?php echo $rwChqs[11]; ?>" style="width:100% !important;">                                              
                                                                    <input type="hidden" class="form-control" aria-label="..." id="oneVmsTrnsRow<?php echo $cntr; ?>_TrnsLnID" value="<?php echo $rwChqs[0]; ?>" style="width:100% !important;">                                                                   
                                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'MCF Deposit Cheque Types', '', '', '', 'radio', true, '', 'oneVmsTrnsRow<?php echo $cntr; ?>_chqTypeID', 'oneVmsTrnsRow<?php echo $cntr; ?>_chqType', 'clear', 1, '', function () {
                                                                                            //afterVMSItemSlctn('oneVmsTrnsRow_<?php echo $cntr; ?>');
                                                                                            var sltdVal = $('#oneVmsTrnsRow<?php echo $cntr; ?>_chqType').val();
                                                                                            if (sltdVal == 'In-House') {
                                                                                                $('.extnl').css('display', 'none');
                                                                                                $('#oneVmsTrnsRow<?php echo $cntr; ?>_bnkNm').val('');
                                                                                                $('#oneVmsTrnsRow<?php echo $cntr; ?>_bnkID').val('');
                                                                                                $('oneVmsTrnsRow<?php echo $cntr; ?>_brnchNm').val('');
                                                                                                $('oneVmsTrnsRow<?php echo $cntr; ?>_brnchiD').val('');
                                                                                            } else {
                                                                                                $('.extnl').css('display', 'table-cell');
                                                                                            }

                                                                                        });">
                                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                                    </label>
                                                                </div> 
                                                            </td>
                                                            <td class="lovtd extnl">
                                                                <div class="input-group" style="width:100% !important;">
                                                                    <input type="<?php echo $inptType; ?>" class="form-control" aria-label="..." id="oneVmsTrnsRow<?php echo $cntr; ?>_bnkNm" name="oneVmsTrnsRow<?php echo $cntr; ?>_bnkNm" value="<?php echo $rwChqs[3]; ?>" readonly="true" style="width:100% !important;">
                                                                    <input type="hidden" class="form-control" aria-label="..." id="oneVmsTrnsRow<?php echo $cntr; ?>_bnkID" value="<?php echo $rwChqs[2]; ?>" style="width:100% !important;">                                               
                                                                    <label style="<?php echo $dsplyLable; ?>" class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'MCF All Banks', 'gnrlOrgID', '', '', 'radio', true, '', 'oneVmsTrnsRow<?php echo $cntr; ?>_bnkID', 'oneVmsTrnsRow<?php echo $cntr; ?>_bnkNm', 'clear', 1, '', function () {
                                                                                            //afterVMSItemSlctn('oneVmsTrnsRow_<?php echo $cntr; ?>');
                                                                                        });">
                                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                                    </label>
                                                                </div> 
                                                                <span style="<?php echo $dsplySpan; ?>"><?php echo $chqType; ?></span>
                                                            </td>
                                                            <td class="lovtd extnl">
                                                                <div class="input-group" style="width:100% !important;">
                                                                    <input type="<?php echo $inptType; ?>" class="form-control" aria-label="..." id="oneVmsTrnsRow<?php echo $cntr; ?>_brnchNm" name="oneVmsTrnsRow<?php echo $cntr; ?>_brnchNm" value="<?php echo $rwChqs[5]; ?>" readonly="true" style="width:100% !important;">
                                                                    <input type="hidden" class="form-control" aria-label="..." id="oneVmsTrnsRow<?php echo $cntr; ?>_brnchID" value="<?php echo $rwChqs[4]; ?>" style="width:100% !important;">                                              
                                                                    <label style="<?php echo $dsplyLable; ?>" class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Bank Branches MCF', 'oneVmsTrnsRow<?php echo $cntr; ?>_bnkID', '', '', 'radio', true, '', 'oneVmsTrnsRow<?php echo $cntr; ?>_brnchID', 'oneVmsTrnsRow<?php echo $cntr; ?>_brnchNm', 'clear', 1, '');">
                                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                                    </label>
                                                                </div> 
                                                                <span style="<?php echo $dsplySpan; ?>"><?php echo $chqType; ?></span> 
                                                            </td> 
                                                            <td class="lovtd">
                                                                <input type="number" class="form-control" aria-label="..." id="oneVmsTrnsRow<?php echo $cntr; ?>_chqNo" name="oneVmsTrnsRow<?php echo $cntr; ?>_chqNo" value="<?php echo $rwChqs[6]; ?>" <?php echo $mkReadOnly; ?>>                                                    
                                                            </td>
                                                            <td class="lovtd">  
                                                                <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                                                    <input type="text" class="form-control" size="16" aria-label="..." id="oneVmsTrnsRow<?php echo $cntr; ?>_chqDte" name="oneVmsTrnsRow<?php echo $cntr; ?>_chqDte" value="<?php echo $rwChqs[10]; ?>" readonly="true">                                                                    
                                                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                                </div>
                                                            </td>                                                        
                                                            <td class="lovtd">
                                                                <div class="" style="width:100% !important;">
                                                                    <input type="hidden" class="form-control" aria-label="..." id="oneVmsTrnsRow<?php echo $cntr; ?>_chqCurNm" name="oneVmsTrnsRow<?php echo $cntr; ?>_chqCurNm" value="<?php echo $rwChqs[14]; ?>" readonly="true" style="width:100% !important;">
                                                                    <label class="btn btn-primary btn-file" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Currencies', '', '', '', 'radio', true, '', 'oneVmsTrnsRow<?php echo $cntr; ?>_chqCurNm', '', 'clear', 1, '', function () {
                                                                                            $('#oneVmsTrnsRow<?php echo $cntr; ?>_chqCurNm1').html($('#oneVmsTrnsRow<?php echo $cntr; ?>_chqCurNm').val());

                                                                                        });">
                                                                        <span class="" id="oneVmsTrnsRow<?php echo $cntr; ?>_chqCurNm1"><?php echo $rwChqs[14]; ?></span>
                                                                    </label>
                                                                </div>                                              
                                                            </td>
                                                            <td class="lovtd">
                                                                <input type="text" class="form-control chqValCls" aria-label="..." id="oneVmsTrnsRow<?php echo $cntr; ?>_chqVal" name="oneVmsTrnsRow<?php echo $cntr; ?>_chqVal" value="<?php
                                                                echo number_format((float) $rwChqs[7], 2);
                                                                ?>" style="width:100% !important;text-align: right;" <?php echo $mkReadOnly; ?>/>                                                    
                                                            </td>
                                                            <td class="lovtd">
                                                                <input type="number" class="form-control chqRatesCls" aria-label="..." id="oneVmsTrnsRow<?php echo $cntr; ?>_exchngRate" name="oneVmsTrnsRow<?php echo $cntr; ?>_exchngRate" value="<?php
                                                                echo number_format((float) $rwChqs[15], 4);
                                                                ?>" <?php echo $mkReadOnly; ?>>                                                    
                                                            </td>
                                                            <td class="lovtd">  
                                                                <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                                                    <input type="text" class="form-control" size="16" aria-label="..." id="oneVmsTrnsRow<?php echo $cntr; ?>_chqValDte" name="oneVmsTrnsRow<?php echo $cntr; ?>_chqValDte" value="<?php echo $rwChqs[8]; ?>" readonly="true">                                                                    
                                                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                                </div>
                                                            </td>
                                                        </tr>                                                    
                                                    <?php } ?>
                                                </tbody>
                                            </table>   
                                        </div>
                                    </div>
                                </fieldset>
                                <?php
                            }
                            ?>
                            <div class="row">
                                <div class="col-lg-6">
                                    <fieldset class=""><legend class="basic_person_lg1"><?php echo $trnsByNm; ?></legend>
                                        <div class="form-group form-group-sm">
                                            <label for="trnsPersonType" class="control-label col-lg-4"><?php echo $trnsByNm1; ?>:</label>
                                            <div  class="col-lg-8">
                                                <?php
                                                $chkdSelf = "";
                                                $chkdOthers = "checked=\"\"";
                                                if ($trnsPersonType == "Self") {
                                                    $chkdOthers = "";
                                                    $chkdSelf = "checked=\"\"";
                                                }
                                                ?>
                                                <label class="radio-inline"><input type="radio" name="trnsPersonType" onclick="trnsPrsnTypeC
                                                            hng();" value="Self" <?php echo $chkdSelf; ?>>Self</label>
                                                <label class="radio-inline"><input type="radio" name="trnsPersonType" onclick="trnsPrsnTypeC
                                                            hng();" value="Others" <?php echo $chkdOthers; ?>>Others</label>                                                               
                                            </div>
                                        </div>
                                        <div class="form-group form-group-sm">
                                            <label for="trnsPersonName" class="control-label col-md-4">Person Name:</label>
                                            <div class="col-md-8">
                                                <input class="form-control" id="trnsPersonName" type = "text" placeholder="" value="<?php echo $trnsPersonName; ?>" <?php echo $mkReadOnly; ?>/>
                                            </div>
                                        </div>
                                        <div class="form-group form-group-sm">
                                            <label for="trnsPersonTelNo" class="control-label col-md-4">Mobile No:</label>
                                            <div class="col-md-8">
                                                <input class="form-control" id="trnsPersonTelNo" type = "text" placeholder="" value="<?php echo $trnsPersonTelNo; ?>" <?php echo $mkReadOnly; ?>/>
                                            </div>
                                        </div>
                                    </fieldset>
                                </div>
                                <div class="col-lg-6">
                                    <fieldset class=""><legend class="basic_person_lg1"><?php echo $trnsByNm; ?></legend>                                                        
                                        <div class="form-group form-group-sm">
                                            <label for="trnsPersonAddress" class="control-label col-md-4">Address:</label>
                                            <div class="col-md-8">
                                                <input class="form-control" id="trnsPersonAddress" type = "text" placeholder="" value="<?php echo $trnsPersonAddress; ?>" <?php echo $mkReadOnly; ?>/>
                                            </div>
                                        </div>
                                        <div class="form-group form-group-sm">
                                            <label for="trnsPersonIDType" class="control-label col-md-4">ID Type:</label>
                                            <div class="col-md-8">
                                                <select class="form-control" id="trnsPersonIDType" <?php echo $mkReadOnly; ?>>  
                                                    <option value="">Please Select...</option>
                                                    <?php
                                                    $brghtStr = "";
                                                    $isDynmyc = FALSE;
                                                    $titleRslt = getLovValues("%", "Both", 0, 100, $brghtStr, getLovID("National ID Types"), $isDynmyc, -1, "", "");
                                                    while ($titleRow = loc_db_fetch_array($titleRslt)) {
                                                        $selectedTxt = "";
                                                        if ($trnsPersonIDType == $titleRow[0]) {
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
                                            <label for="trnsPersonIDNumber" class="control-label col-md-4">ID Number:</label>
                                            <div class="col-md-8">
                                                <input class="form-control" id="trnsPersonIDNumber" type = "text" placeholder="" value="<?php echo $trnsPersonIDNumber; ?>" <?php echo $mkReadOnly; ?>/>
                                            </div>
                                        </div>
                                    </fieldset>
                                </div>
                                <div class="col-lg-12" style="<?php echo $dsplyMndt; ?>"> 
                                    <fieldset class="" style=""><legend class="basic_person_lg1" id="docTypeDtls">Mandate and Signatory(s)</legend> 
                                        <div class="col-md-12" style="padding:0px !important;">
                                            <div class="form-group form-group-sm">
                                                <label for="mandate" class="control-label col-md-4">Account Mandate:</label>
                                                <div  class="col-md-8">
                                                    <input class="form-control" id="mandate" type = "text" placeholder="" value="<?php echo $mandate; ?>" readonly="readonly"/>
                                                </div>
                                            </div>
                                            <table id="acctSignatoriesTblAdd" class="table table-striped table-bordered table-responsive" cellspacing="0" width="100%" style="width:100%;">
                                                <thead>
                                                    <tr>
                                                        <th>No.</th>
                                                        <th>Signatory Name</th>
                                                        <th style="text-align: center !important;">Signed?</th>
                                                        <th>&nbsp;</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="acctSignatoriesTblTbody">
                                                    <?php
                                                    $acntMndteRslt = getAccountSignatories($acctNo);
                                                    $cntr = 0;
                                                    while ($rwMndte = loc_db_fetch_array($acntMndteRslt)) {
                                                        $cntr++;
                                                        ?>
                                                        <tr id="acctSignatoriesTblAddRow_<?php echo $cntr; ?>">  
                                                            <td class="lovtd"><?php echo $cntr; ?></td>
                                                            <td class="lovtd" id="acctSignatoriesTblAddRow<?php echo $cntr; ?>_name"><?php echo $rwMndte[1]; ?></td>
                                                            <td class="lovtd" style="text-align: center !important;">
                                                                <?php
                                                                $isChkd = "";
                                                                if (wasAccntSignUsed($rwMndte[0], $sbmtdTrnsHdrID) > 0) {
                                                                    $isChkd = "checked=\"\"";
                                                                }
                                                                ?>
                                                                <input type="checkbox" class="form-check-input" <?php echo $isChkd; ?>>
                                                            </td>
                                                            <td class="lovtd">
                                                                <button type="button" class="btn btn-info btn-sm" onclick="viewSignatoryForm('myLovModal', 'myLovModalBody', 'myLovModalTitle', 'acctSignatoryForm', '', 'Signatory', 13, 2.1, 5, ' VIEW',<?php echo $rwMndte[0]; ?>);" style="padding:2px !important;">View Signatory</button>
                                                            </td>
                                                            <td  class="lovtd" style="display:none;" id="acctSignatoriesTblAddRow<?php echo $cntr; ?>_ID"><?php echo $rwMndte[0]; ?></td>                            
                                                        </tr>
                                                    <?php } ?>
                                                </tbody>
                                            </table>
                                        </div> 
                                    </fieldset>
                                </div>                                                                 
                            </div> 
                            <div class="row">
                                <div class="col-lg-12"> 
                                    <fieldset class="" style=""><legend class="basic_person_lg1" id="docTypeDtls">Historic Account Transactions</legend> 
                                        <div  class="col-md-12" style="padding:0px !important;">
                                            <table id="acctHistoryTblAdd" class="table table-striped table-bordered table-responsive" cellspacing="0" width="100%" style="width:100%;">
                                                <thead>
                                                    <tr>
                                                        <th>&nbsp;</th>
                                                        <th>Date</th>
                                                        <th>Transaction Type & Description</th>
                                                        <th>Trns. No.</th>
                                                        <th style="text-align:right;min-width: 120px;">Amount</th>
                                                        <th style="text-align:right;min-width: 120px;">Current Bals. (After Trns.)</th>
                                                        <th style="text-align:right;min-width: 120px;">Available Bals. (After Trns.)</th>
                                                        <th>Status</th>
                                                        <th>Authorizer</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="acctHistoryTblTbody">
                                                    <?php
                                                    $acntHstryRslt = get_OneCustAccntHstry($accntID);
                                                    while ($rwHstry = loc_db_fetch_array($acntHstryRslt)) {
                                                        ?>
                                                        <tr>
                                                            <td class="lovtd">
                                                                <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Transaction Details" onclick="getOneVmsTrnsForm(<?php echo $rwHstry[0]; ?>, '<?php echo $rwHstry[5]; ?>', 30, 'ShowDi alog',<?php echo $vwtyp; ?>);" style="padding:2px !important;">
                                                                    <img src="cmn_images/kghostview.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                </button>
                                                            </td>
                                                            <td class="lovtd"><?php echo $rwHstry[11]; ?></td>
                                                            <td class="lovtd"><?php echo $rwHstry[5] . " @" . $rwHstry[48] . " - " . $rwHstry[31] . " [" . $rwHstry[15] . " - " . $rwHstry[16] . "]"; ?></td>
                                                            <td class="lovtd"><?php echo $rwHstry[12]; ?></td>
                                                            <td class="lovtd" style="text-align:right;font-weight:bold;"><?php
                                                                echo $rwHstry[10] . " " . number_format((float) $rwHstry[6], 2);
                                                                ?></td>
                                                            <td class="lovtd" style="text-align:right;font-weight:bold;color:blue;"><?php
                                                                echo $rwHstry[10] . " " . number_format((float) $rwHstry[37], 2);
                                                                ?></td>
                                                            <td class="lovtd" style="text-align:right;font-weight:bold;color:blue;"><?php
                                                                echo $rwHstry[10] . " " . number_format((float) $rwHstry[38], 2);
                                                                ?></td>
                                                            <td class="lovtd"><?php echo $rwHstry[7]; ?></td>
                                                            <td class="lovtd"><?php echo $rwHstry[34]; ?></td>
                                                        </tr>
                                                    <?php } ?>
                                                </tbody>
                                            </table>
                                        </div> 
                                    </fieldset>
                                </div>                                                                
                            </div>                                              
                        </form>  
                    </div>                          
                </div>                         
            </div>                
        </div> 
    </fieldset>
    <?php
}

function getMCFOrdrRdOnlyDsply($sbmtdTrnsfrHdrID) {
    global $canview;
    global $gnrlTrnsDteYMDHMS;
    global $usrID;
    global $prsnid;
    global $fnccurnm;
    global $fnccurid;
    global $orgID;
    if (!$canview) {
        restricted();
        exit();
    }
    $canAddTrns = false;
    $canEdtTrns = false;
    $canDelTrns = false;
    $canAthrz = false;
    $orgnlTrnsfrHdrID = $sbmtdTrnsfrHdrID;
    $rqStatus = "Not Submitted";
    $rqstatusColor = "red";
    $dte = str_replace("-", "", substr($gnrlTrnsDteYMDHMS, 2, 8)) . "-" . date('His'); //date('ymdHis');
    $usrTrnsCode = getGnrlRecNm("sec.sec_users", "user_id", "code_for_trns_nums", $usrID);
    $trnsfrOrderNum = "TRNSFR-" . $usrTrnsCode . "-" . $dte;

    $gnrtdStrtDate = date('d-M-Y');
    $trnsfrOrderStrtDate = $gnrtdStrtDate;
    $brnchLocID = getLatestSiteID($prsnid);
    $brnchLoc = getGnrlRecNm("org.org_sites_locations", "location_id", "REPLACE(location_code_name || '.' || site_desc, '.' || location_code_name,'')", $brnchLocID);
    $trnsfrOrderCrncyNm = $fnccurnm;
    $trnsfrOrderCrncyID = $fnccurid;
    $athrztnStatus = "<span style=\"color:red;font-weight:bold;\">Not Submitted</span>";
    $trnsfrOrdrDesc = "";
    $trnsfrOrdrTtlAmnt = 0;
    $mkReadOnly = "";
    $mkRmrkReadOnly = "";
    $trnsExchngRate = 1;
    $trnsfrOrderAcntNm = "";
    $trnsfrOrderAcntID = -1;
    $trnsfrOrderDstTyp = "";
    $trnsfrOrderDstAcntID = -1;
    $trnsfrOrderDstAcNum = "";
    $trnsfrOrderTrsfrTyp = "In-House";
    $trnsfrOrderFrqncyNo = 1;
    $trnsfrOrderFrqncyTyp = "LifeTime";
    $trnsfrOrderEndDate = "";
    $trnsfrOrderBnkName = "";
    $trnsfrOrderBnkID = -1;
    $trnsfrOrderBrnchName = "";
    $trnsfrOrderBrnchID = -1;
    $trnsfrOrderBnfcryNm = "";
    $trnsfrOrderBnfcryAdrs = "";
    $dfltMiscGlAcntID = -1;
    $dfltMiscGlAcntNo = "";
    if ($sbmtdTrnsfrHdrID > 0) {
        //Important! Must Check if One also has prmsn to Edit brought Trns Hdr ID
        $result = get_One_AcntTrnsfrHdr($sbmtdTrnsfrHdrID);
        if ($row = loc_db_fetch_array($result)) {
            $rqStatus = $row[26];
            if ($rqStatus == "Not Submitted" || $rqStatus == "Withdrawn" || $rqStatus == "Rejected") {
                $rqstatusColor = "red";
                $mkReadOnly = "";
                $mkRmrkReadOnly = "";
            } else if ($rqStatus != "Authorized") {
                $mkReadOnly = "readonly=\"true\"";
                $mkRmrkReadOnly = "readonly=\"true\"";
                $rqstatusColor = "brown";
            } else {
                $rqstatusColor = "green";
                $mkReadOnly = "readonly=\"true\"";
                $mkRmrkReadOnly = "readonly=\"true\"";
            }
            $trnsfrOrderNum = $row[30];
            $brnchLocID = (float) $row[22];
            $brnchLoc = getGnrlRecNm("org.org_sites_locations", "location_id", "REPLACE(location_code_name || '.' || site_desc, '.' || location_code_name,'')", $brnchLocID);
            $trnsfrOrderCrncyNm = $row[32];
            $trnsfrOrderCrncyID = (float) $row[31];
            $athrztnStatus = "<span style=\"color:red;font-weight:bold;\">$rqStatus</span>";
            $trnsfrOrdrDesc = $row[25];
            $trnsfrOrdrTtlAmnt = (float) $row[7];
            $trnsExchngRate = (float) $row[24];
            $trnsfrOrderAcntNm = $row[2];
            $trnsfrOrderAcntID = (float) $row[1];
            $trnsfrOrderDstTyp = $row[4];
            $trnsfrOrderDstAcNum = $row[5];
            $trnsfrOrderTrsfrTyp = $row[6];
            if ($trnsfrOrderDstTyp == "Bank Account" && $trnsfrOrderTrsfrTyp == "In-House") {
                $trnsfrOrderDstAcntID = getCustAcctIdDtaImprt($trnsfrOrderDstAcNum, $orgID);
            }
            $trnsfrOrderFrqncyNo = (float) $row[8];
            $trnsfrOrderFrqncyTyp = $row[9];
            $trnsfrOrderStrtDate = $row[10];
            $trnsfrOrderEndDate = $row[11];
            $trnsfrOrderBnkName = $row[13];
            $trnsfrOrderBnkID = (float) $row[12];
            $trnsfrOrderBrnchName = $row[15];
            $trnsfrOrderBrnchID = (float) $row[14];
            $trnsfrOrderBnfcryNm = $row[16];
            $trnsfrOrderBnfcryAdrs = $row[17];
            $dfltMiscGlAcntID = (float) $row[33];
            $dfltMiscGlAcntNo = $row[34];
        }
    } else {
        $sbmtdTrnsfrHdrID = -1;
    }

    $canEdtOrdr = $canAddTrns && ($rqStatus == "Not Submitted" || $rqStatus == "Withdrawn" || $rqStatus == "Rejected");
    $routingID = getTrnsfrMxRoutingID($sbmtdTrnsfrHdrID);
    $reportTitle = "VMS Transaction Voucher";
    $reportName = "VMS Transaction Voucher";
    $rptID = getRptID($reportName);
    $prmID1 = getParamIDUseSQLRep("{:trns_id}", $rptID);
    $prmID2 = getParamIDUseSQLRep("{:documentTitle}", $rptID);
    $trnsID = $sbmtdTrnsfrHdrID;
    $paramRepsNVals = $prmID1 . "~" . $trnsID . "|" . $prmID2 . "~" . $reportTitle . "|-130~" . $reportTitle . "|-190~PDF";
    $paramStr = urlencode($paramRepsNVals);

    $reportTitle1 = "Execute Standing Order/Transfer";
    $reportName1 = "Execute Standing Order/Transfer";
    $rptID1 = getRptID($reportName1);
    $prmID11 = getParamIDUseSQLRep("{:ordr_trnsfr_id}", $rptID1);
    $trnsID1 = $sbmtdTrnsfrHdrID;
    $paramRepsNVals1 = $prmID11 . "~" . $trnsID1 . "|-130~" . $reportTitle1 . "|-190~PDF";
    $paramStr1 = urlencode($paramRepsNVals1);
    ?>
    <form class="form-horizontal" id="oneVmsTrnsEDTForm">
        <div class="row" style="margin: 0px 0px 10px 0px !important;">
            <div class="col-md-6" style="padding:0px 15px 0px 0px !important;">
                <div class="" style="padding:0px 0px 0px 0px;float:left !important;">                               
                    <button type="button" class="btn btn-default btn-sm" style="" id="myVmsTrnsStatusBtn"><span style="font-weight:bold;">Status: </span><span style="color:<?php echo $rqstatusColor; ?>;font-weight: bold;"><?php echo $rqStatus; ?></span></button>                
                    <button type="button" class="btn btn-default" style=""  onclick="getSilentRptsRnSts(<?php echo $rptID; ?>, -1, '<?php echo $paramStr; ?>', function () {
                                    getOneAcntTrnsfrForm(-1, 'Transfer/Order', 'ReloadDialog');
                                });" style="width:100% !important;">
                        <img src="cmn_images/pdf.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">
                        Print Order
                    </button>                                             
                    <button type="button" class="btn btn-default btn-sm" style="height: 30px;" onclick="getOneMcfDocsForm_Gnrl(<?php echo $sbmtdTrnsfrHdrID; ?>, 'Transfer/Order', 140, 'Transfer/Order Attached Documents');" data-toggle="tooltip" data-placement="bottom" title = "Attached Documents">
                        <img src="cmn_images/adjunto.png" style="height:17px; width:auto; position: relative; vertical-align: middle;">
                        Attachments
                    </button>                                
                    <button type="button" class="btn btn-default btn-sm" style="height:30px;" onclick="getOneAcntTrnsfrForm(-1, 'Transfer/Order', 'ReloadDialog');" data-toggle="tooltip" title="Reload Transfer/Order">
                        <img src="cmn_images/refresh.bmp" style="padding-right: 2px; height:17px; width:auto; position: relative; vertical-align: middle;">
                        Refresh
                    </button>
                </div>
            </div> 
            <div class="col-md-6" style="padding:0px 0px 0px 0px !important;">
                <div class="" style="padding:0px 0px 0px 0px;float:right !important;"> 
                    <?php
                    if ($rqStatus == "Authorized") {
                        ?>   
                        <button type="button" class="btn btn-default btn-sm" style="" onclick="checkWkfRqstStatus(<?php echo $routingID; ?>, 'Transfer Authorization Progress History');"><img src="cmn_images/workflow.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;" data-toggle="tooltip" title="Authorization Progress History">Progress&nbsp;</button>  
                    <?php } else { ?>                                    
                        <button type="button" class="btn btn-default btn-sm" style="" onclick="checkWkfRqstStatus(<?php echo $routingID; ?>, 'Transfer Authorization Progress History');"><img src="cmn_images/workflow.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;" data-toggle="tooltip" title="Authorization Progress History">Progress&nbsp;</button>  
                    <?php } ?>
                </div>
            </div>                    
        </div>
        <fieldset class="basic_person_fs2" style="min-height:50px !important;">
            <!--<legend class="basic_person_lg">Transaction Header Information</legend>-->
            <div class="row" style="margin-top:5px;">
                <div class="col-md-4">
                    <div class="form-group">
                        <div class="col-md-4" style="padding:5px 5px 0px 15px !important;">
                            <label style="margin-bottom:0px !important;">Order Number:</label>
                        </div>
                        <div class="col-md-8">
                            <input type="text" class="form-control" aria-label="..." id="trnsfrOrderNum" name="trnsfrOrderNum" value="<?php echo $trnsfrOrderNum; ?>" readonly="true">
                            <input class="form-control" type="hidden" id="trnsfrOrderID" value="<?php echo $sbmtdTrnsfrHdrID; ?>"/>
                            <input type="hidden" id="gnrlOrderOrgID" value="<?php echo $orgID; ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="trnsfrOrderAcntNm" class="control-label col-md-4">Source Account:</label>
                        <div class="col-md-8">                                        
                            <div class="input-group">
                                <input class="form-control rqrdFld" id="acctNoFind" style="font-size: 13px !important;font-weight: bold !important;" placeholder="Enter Account Number" type = "text" min="0" placeholder="" value="<?php echo $trnsfrOrderAcntNm; ?>" <?php echo $mkReadOnly; ?>/>
                                <input type="hidden" id="trnsfrOrderAcntID" value="<?php echo $trnsfrOrderAcntID; ?>">
                                <input type="hidden" id="acctNoFindRawTxt" value="<?php echo ''; ?>">
                                <?php if ($rqStatus != "Authorized" && $rqStatus != "Cancelled") { ?>
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'MCF All Bank Accounts', '', '', '', 'radio', true, '', 'trnsfrOrderAcntID', 'acctNoFindRawTxt', 'clear', 1, '', function () {
                                                        $('#acctNoFind').val($('#acctNoFindRawTxt').val().split(' [')[0]);
                                                    });">
                                        <span class="glyphicon glyphicon-th-list"></span>
                                    </label>
                                <?php } ?>
                                <label class="btn btn-primary btn-file input-group-addon" onclick="getCustAcctsForm('myFormsModalCA1', 'myFormsModalBodyCA1', 'myFormsModalTitleCA1', 'View Customer Account', 13, 2.1, 0, 'VIEW', <?php echo $trnsfrOrderAcntID; ?>, 'custAcctTable', '', '<?php echo $trnsfrOrderAcntNm; ?>');" data-toggle="tooltip" data-placement="bottom" title = "Latest Account Information">
                                    <span class="glyphicon glyphicon-info-sign"></span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="trnsfrOrderTrsfrTyp" class="control-label col-md-4" style="padding:0px 5px 0px 15px !important;">Transfer Method:</label>
                        <div  class="col-md-8">
                            <select class="form-control" id="trnsfrOrderTrsfrTyp" <?php echo $mkReadOnly; ?> onchange="onChangeTrnfrTyp();">
                                <?php
                                $valslctdArry = array("", "", "", "");
                                $srchInsArrys = array("Interbank", "In-House", "Cheque Mailing", "Swift");
                                $valsArrys = array("Interbank", "In-House", "Cheque Mailing", "Swift");
                                for ($z = 0; $z < count($srchInsArrys); $z++) {
                                    if ($trnsfrOrderTrsfrTyp == $valsArrys[$z]) {
                                        $valslctdArry[$z] = "selected";
                                    }
                                    ?>
                                    <option value="<?php echo $valsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="trnsfrOrderDstTyp" class="control-label col-md-4" style="padding:5px 5px 0px 15px !important;">Destination Type:</label>
                        <div  class="col-md-8">
                            <select class="form-control" id="trnsfrOrderDstTyp" <?php echo $mkReadOnly; ?> onchange="onChangeTrnfrTyp();">
                                <?php
                                $valslctdArry = array("", "", "");
                                $srchInsArrys = array("Bank Account", "Mobile Money Wallet", "Money Transfer Token");
                                $valsArrys = array("Bank Account", "Mobile Money Wallet", "Money Transfer Token");
                                for ($z = 0; $z < count($srchInsArrys); $z++) {
                                    if ($trnsfrOrderDstTyp == $valsArrys[$z]) {
                                        $valslctdArry[$z] = "selected";
                                    }
                                    ?>
                                    <option value="<?php echo $valsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-4" style="padding:0px 5px 0px 15px !important;">
                            <label style="margin-bottom:0px !important;">Account / Wallet / Token No.:</label>
                        </div>
                        <div class="col-md-8">                                        
                            <div class="input-group">
                                <input class="form-control rqrdFld" id="trnsfrOrderDstAcNum" style="font-size: 13px !important;font-weight: bold !important;" placeholder="" type = "text" min="0" placeholder="" value="<?php echo $trnsfrOrderDstAcNum; ?>" <?php echo $mkReadOnly; ?>/>
                                <input type="hidden" id="trnsfrOrderDstAcntID" value="<?php echo $trnsfrOrderDstAcntID; ?>">
                                <input type="hidden" id="acctNoFindRawTxt1" value="<?php echo ''; ?>">
                                <?php if ($rqStatus != "Authorized" && $rqStatus != "Cancelled") { ?>
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'MCF All Bank Accounts', '', '', '', 'radio', true, '', 'trnsfrOrderDstAcntID', 'acctNoFindRawTxt1', 'clear', 1, '', function () {
                                                        $('#trnsfrOrderDstAcNum').val($('#acctNoFindRawTxt1').val().split(' [')[0]);
                                                        $('#trnsfrOrderBnfcryNm').val(rhotrim($('#acctNoFindRawTxt1').val().split(' [')[1], '] '));
                                                    });">
                                        <span class="glyphicon glyphicon-th-list"></span>
                                    </label>
                                <?php } ?>
                                <label class="btn btn-primary btn-file input-group-addon" onclick="getCustAcNoInfoForm('myFormsModalCA1', 'myFormsModalBodyCA1', 'myFormsModalTitleCA1', 'View Customer Account', 13, 2.1, 0, 'VIEW', <?php echo $trnsfrOrderDstAcntID; ?>, 'custAcctTable', '', '<?php echo $trnsfrOrderDstAcNum; ?>', 'trnsfrOrderDstAcNum');" data-toggle="tooltip" data-placement="bottom" title = "Latest Account Information">
                                    <span class="glyphicon glyphicon-info-sign"></span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group form-group-sm">
                        <label for="trnsfrOrdrTtlAmnt"  style="font-size: 20px !important;" class="control-label col-md-4">Amount:</label>
                        <div  class="col-md-8">
                            <div class="input-group input-group-md">
                                <label class="btn btn-info btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Currencies', '', '', '', 'radio', true, '<?php echo $trnsfrOrderCrncyNm; ?>', 'trnsfrOrderCrncyNm', '', 'clear', 0, '', function () {
                                                $('#trnsAmountCrncy').html($('#trnsfrOrderCrncyNm').val());
                                            });">
                                    <span class="" style="font-size: 20px !important;" id="trnsAmountCrncy"><?php echo $trnsfrOrderCrncyNm; ?> </span>
                                </label>
                                <input type="hidden" id="trnsfrOrderCrncyNm" value="<?php echo $trnsfrOrderCrncyNm; ?>">
                                <input class="form-control rqrdFld" style="height:40px !important; font-size: 20px !important;text-align: right !important;" id="trnsfrOrdrTtlAmnt" type = "text" placeholder="" value="<?php
                                echo number_format($trnsfrOrdrTtlAmnt, 2);
                                ?>" aria-describedby="trnsAmountCrncy" <?php echo $mkReadOnly; ?>/>                                                               
                            </div>                                                                    
                        </div>
                    </div>                               
                </div>
                <div class="col-md-4">     
                    <div class="form-group form-group-sm">
                        <label for="trnsExchngRate" class="control-label col-md-4">Rate:</label>
                        <div  class="col-md-8">
                            <input class="form-control rqrdFld" id="trnsExchngRate" type="number" value="<?php
                            echo number_format($trnsExchngRate, 5);
                            ?>" style="font-size: 20px !important;text-align: right !important;" <?php echo $mkRmrkReadOnly; ?>/>
                        </div>
                    </div>   
                    <div class="form-group form-group-sm">
                        <label for="trnsfrOrdrDesc" class="control-label col-md-4">Narration:</label>
                        <div  class="col-md-8">
                            <textarea class="form-control rqrdFld" id="trnsfrOrdrDesc" cols="2" placeholder="Narration/Remarks" rows="2" <?php echo $mkRmrkReadOnly; ?>><?php echo $trnsfrOrdrDesc; ?></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-4">
                            <label style="margin-bottom:0px !important;">Frequency (Every):</label>
                        </div>
                        <div class="col-md-4" style="padding:0px 2px 0px 15px !important;">
                            <input type="number" class="form-control rqrdFld" aria-label="..." id="trnsfrOrderFrqncyNo" name="trnsfrOrderFrqncyNo" value="<?php echo $trnsfrOrderFrqncyNo; ?>" style="width:100% !important;" <?php echo $mkReadOnly; ?>>
                        </div>
                        <div class="col-md-4" style="padding:0px 15px 0px 2px !important;">
                            <select class="form-control" id="trnsfrOrderFrqncyTyp" <?php echo $mkReadOnly; ?> onchange="">
                                <?php
                                $valslctdArry = array("", "", "", "", "");
                                $srchInsArrys = array("Day(s)", "Week(s)", "Month(s)", "Year(s)", 'LifeTime');
                                $valsArrys = array("Day", "Week", "Month", "Year", 'LifeTime');
                                for ($z = 0; $z < count($srchInsArrys); $z++) {
                                    if ($trnsfrOrderFrqncyTyp == $valsArrys[$z]) {
                                        $valslctdArry[$z] = "selected";
                                    }
                                    ?>
                                    <option value="<?php echo $valsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-4">
                            <label style="margin-bottom:0px !important;">Start Date:</label>
                        </div>
                        <div class="col-md-8">
                            <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                <input class="form-control" size="16" type="text" id="trnsfrOrderStrtDate" name="trnsfrOrderStrtDate" value="<?php
                                echo substr($trnsfrOrderStrtDate, 0, 11);
                                ?>" placeholder="Start Date">
                                <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-4">
                            <label style="margin-bottom:0px !important;">End Date:</label>
                        </div>
                        <div class="col-md-8">
                            <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                <input class="form-control" size="16" type="text" id="trnsfrOrderEndDate" name="trnsfrOrderEndDate" value="<?php
                                echo substr($trnsfrOrderEndDate, 0, 11);
                                ?>" placeholder="End Date">
                                <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-4">
                            <label style="margin-bottom:0px !important;margin-top:5px !important;">Location:</label>
                        </div>
                        <div class="col-md-8">
                            <input type="text" class="form-control" aria-label="..." id="brnchLoc" name="brnchLoc" value="<?php echo $brnchLoc; ?>" readonly="true">
                            <input type="hidden" id="brnchLocID" value="<?php echo $brnchLocID; ?>">
                        </div>
                    </div>
                </div>
                <div class = "col-md-4">
                    <div class = "form-group">
                        <div class = "col-md-4">
                            <label style="margin-bottom:0px !important;">Bank Name</label>
                        </div>
                        <div class = "col-md-8">
                            <div class="input-group" style="width:100% !important;">
                                <input type="text" class="form-control" aria-label="..." id="trnsfrOrderBnkName" name="trnsfrOrderBnkName" value="<?php echo $trnsfrOrderBnkName; ?>" readonly="true">
                                <input type="hidden" class="form-control" aria-label="..." id="trnsfrOrderBnkID" value="<?php echo $trnsfrOrderBnkID; ?>">                                               
                                <label style="" class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'MCF All Banks', 'gnrlOrderOrgID', '', '', 'radio', true, '', 'trnsfrOrderBnkID', 'trnsfrOrderBnkName', 'clear', 1, '', function () {

                                            });">
                                    <span class="glyphicon glyphicon-th-list"></span>
                                </label>
                            </div>
                        </div>
                    </div>   
                    <div class = "form-group">
                        <div class = "col-md-4">
                            <label style="margin-bottom:0px !important;">Branch Name:</label>
                        </div>
                        <div class = "col-md-8">
                            <div class="input-group" style="width:100% !important;">
                                <input type="text" class="form-control" aria-label="..." id="trnsfrOrderBrnchName" name="trnsfrOrderBrnchName" value="<?php echo $trnsfrOrderBrnchName; ?>" readonly="true">
                                <input type="hidden" class="form-control" aria-label="..." id="trnsfrOrderBrnchID" value="<?php echo $trnsfrOrderBrnchID; ?>">                                              
                                <label style="" class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Bank Branches MCF', 'trnsfrOrderBnkID', '', '', 'radio', true, '', 'trnsfrOrderBrnchID', 'trnsfrOrderBrnchName', 'clear', 1, '');">
                                    <span class="glyphicon glyphicon-th-list"></span>
                                </label>
                            </div>
                        </div>
                    </div>                             
                    <div class = "form-group">
                        <div class = "col-md-4" style="padding:0px 5px 0px 15px !important;">
                            <label style="margin-bottom:0px !important;">Beneficiary Name:</label>
                        </div>
                        <div class = "col-md-8">
                            <input type="text" class="form-control rqrdFld" aria-label="..." id="trnsfrOrderBnfcryNm" name="trnsfrOrderBnfcryNm" value="<?php echo $trnsfrOrderBnfcryNm; ?>" style="width:100% !important;" <?php echo $mkReadOnly; ?>>
                        </div>
                    </div>
                    <div class="form-group form-group-sm">
                        <label for="trnsfrOrderBnfcryAdrs" class="control-label col-md-4">Beneficiary Contact Information:</label>
                        <div  class="col-md-8">
                            <textarea class="form-control" id="trnsfrOrderBnfcryAdrs" cols="2" placeholder="Contact Information" rows="3" <?php echo $mkRmrkReadOnly; ?>><?php echo $trnsfrOrderBnfcryAdrs; ?></textarea>
                        </div>
                    </div>
                    <div class="form-group form-group-sm">
                        <label for="dfltMiscGlAcnt" class="control-label col-md-4">Default GL Account for Charges:</label>
                        <div  class="col-md-8">
                            <div class="input-group">
                                <input class="form-control" id="dfltMiscGlAcntNo" style="font-size: 13px !important;font-weight: bold !important;" placeholder="Enter GL Account Number" type = "text" min="0" placeholder="" value="<?php echo $dfltMiscGlAcntNo; ?>" readonly="true"/>
                                <input type="hidden" id="dfltMiscGlAcntID" value="<?php echo $dfltMiscGlAcntID; ?>">
                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Accounts', '', '', '', 'radio', true, '', 'dfltMiscGlAcntID', 'dfltMiscGlAcntNo', 'clear', 1, '', function () {});">
                                    <span class="glyphicon glyphicon-th-list"></span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </fieldset>
        <fieldset class="">
            <!--<legend class="basic_person_lg">Transfer/Order Executions</legend>-->
            <div class="row">
                <div class="col-md-12">
                    <ul class="nav nav-tabs" style="margin-top:1px !important;">
                        <li class="active"><a data-toggle="tabajxtrnsfrs" data-rhodata="" href="#trnsfrOrdrExctns" id="trnsfrOrdrExctnstab" style="padding: 3px 10px !important;">Transfer/Order Executions</a></li>
                        <li><a data-toggle="tabajxtrnsfrs" data-rhodata="" href="#trnsfrExtrDstntns" id="trnsfrExtrDstntnstab" style="padding: 3px 10px !important;">Extra Destination Accounts</a></li>
                        <li><a data-toggle="tabajxtrnsfrs" data-rhodata="" href="#trnsfrExtrSources" id="trnsfrExtrSourcestab" style="padding: 3px 10px !important;">Extra Source Accounts</a></li>
                        <li><a data-toggle="tabajxtrnsfrs" data-rhodata="" href="#trnsfrMiscTrns" id="trnsfrMiscTrnstab" style="padding: 3px 10px !important;">Miscellaneous Transactions</a></li>
                    </ul>
                    <div class="custDiv" style="padding:0px !important;min-height: 40px !important;" id="trnsfrOrderExctnsTblSctn"> 
                        <div class="tab-content" style="padding:5px !important;padding-top:7px !important;">
                            <div id="trnsfrOrdrExctns" class="tab-pane fadein active" style="border:none !important;padding:0px !important;">
                                <div class="row">
                                    <div class="col-md-12">
                                        <table class="table table-striped table-bordered table-responsive" id="trnsfrOrderExctnsTbl" cellspacing="0" width="100%" style="width:100%;min-width: 900px !important;">
                                            <thead>
                                                <tr>
                                                    <th style="">No.</th>
                                                    <th style="min-width:150px;">Source Account</th>
                                                    <th style="">Destination Type</th>
                                                    <th style="min-width:150px;">Destination Account</th>
                                                    <th style="text-align: right;max-width:70px;width:70px;">Currency</th>
                                                    <th style="text-align: right;">Amount</th>
                                                    <th style="text-align: center;max-width:100px;width:100px;">Was Transfer Successful?</th>
                                                    <th style="min-width:300px;width:350px">Remarks</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $cntr = 0;
                                                $resultRw = get_One_TrnsfrExctns($sbmtdTrnsfrHdrID);
                                                while ($rowRw = loc_db_fetch_array($resultRw)) {
                                                    $ordrExctnID = (float) $rowRw[0];
                                                    $srcAcntName = $rowRw[2];
                                                    $destType = $rowRw[5];
                                                    $destAcntName = $rowRw[4];
                                                    $crncyName = $rowRw[14];
                                                    $trnsfrAmnt = (float) $rowRw[12];
                                                    $wasSccfl = $rowRw[6] == "1" ? "YES" : "NO";
                                                    $rmrksDesc = $rowRw[7];
                                                    $style1 = "font-weight:bold;color:red;";
                                                    if ($rowRw[6] == "1") {
                                                        $style1 = "font-weight:bold;color:green;";
                                                    }
                                                    $cntr += 1;
                                                    ?>
                                                    <tr id="trnsfrOrderExctnsRow_<?php echo $cntr; ?>">                                    
                                                        <td class="lovtd"><span><?php echo ($cntr); ?></span></td>
                                                        <td class="lovtd">
                                                            <input type="hidden" class="form-control" aria-label="..." id="trnsfrOrderExctnsRow<?php echo $cntr; ?>_ExctnID" value="<?php echo $ordrExctnID; ?>" style="width:100% !important;">                                                      
                                                            <span class=""><?php echo $srcAcntName; ?></span>                                              
                                                        </td> 
                                                        <td class="lovtd">
                                                            <span class=""><?php echo $destType; ?></span>
                                                        </td> 
                                                        <td class="lovtd">
                                                            <span class=""><?php echo $destAcntName; ?></span>
                                                        </td>                                                
                                                        <td class="lovtd" style="text-align: right;max-width:70px;width:70px;">
                                                            <span style="font-weight:bold;"><?php echo $crncyName; ?></span>
                                                        </td>
                                                        <td class="lovtd" style="text-align: right;">
                                                            <span style="font-weight:bold;"><?php echo number_format($trnsfrAmnt, 2); ?></span>
                                                        </td>
                                                        <td class="lovtd" style="text-align: center;max-width:100px;width:100px;">
                                                            <span style="<?php echo $style1; ?>"><?php echo $wasSccfl; ?></span>
                                                        </td>
                                                        <td class="lovtd">
                                                            <span class=""><?php echo $rmrksDesc; ?></span>
                                                        </td>
                                                    </tr>
                                                    <?php
                                                }
                                                ?>
                                            </tbody>
                                        </table> 
                                    </div>
                                </div>
                            </div> 
                            <div id="trnsfrExtrDstntns" class="tab-pane fadein hideNotice" style="border:none !important;padding:0px !important;">
                                <div class="row">
                                    <div class="col-md-12">
                                        <button id="refreshDstnAcntBtn" type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="getOneAcntTrnsfrForm(-1, 'Transfer/Order', 'ReloadDialog');" data-toggle="tooltip" data-placement="bottom" title = "Reload Transfers/Orders">
                                            <img src="cmn_images/refresh.bmp" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                            Refresh
                                        </button>
                                        <button id="infoDstnAcntBtn" type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="">
                                            <span style="color:red;font-family:georgia;font-style:italic;font-weight:bold;">NB:The entered amounts here will each be DEDUCTED from the SOURCE ACCOUNT above!</span>
                                        </button>
                                    </div>                                            
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <table class="table table-striped table-bordered table-responsive" id="oneTrnsfrDstntnsTable" cellspacing="0" width="100%" style="width:100%;min-width: 300px !important;">
                                            <thead>
                                                <tr>
                                                    <th style="max-width: 45px;width: 45px;">No.</th>
                                                    <th style="max-width: 100px;width: 100px;">Transfer Method</th>
                                                    <th style="max-width: 100px;width: 100px;">Destination Type</th>
                                                    <th style="max-width: 200px;width: 200px;">Destination Account / Wallet No.</th>
                                                    <th style="text-align:right;max-width: 100px;width: 100px;">Amount</th>
                                                    <th style="max-width: 200px;width: 200px;">Bank Name</th>
                                                    <th style="max-width: 180px;width: 180px;">Branch Name</th>
                                                    <th style="max-width: 180px;width: 180px;">Beneficiary Name</th>
                                                    <th style="max-width: 30px;width: 30px;">&nbsp;</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $rslt = get_OneStndOrdrDstns($sbmtdTrnsfrHdrID);
                                                $cntrUsr = 0;
                                                while ($rwLn = loc_db_fetch_array($rslt)) {
                                                    $cntrUsr++;
                                                    $ordrDstID = (float) $rwLn[0];
                                                    $trnsfrMthd = $rwLn[15];
                                                    $dstnType = $rwLn[14];
                                                    $dstnAccount = $rwLn[2];
                                                    $dstnAccountID = $rwLn[2];
                                                    $dstnAmnt = (float) $rwLn[3];
                                                    $dstBnkID = $rwLn[4];
                                                    $dstBnkNm = $rwLn[5];
                                                    $dstBrnchID = $rwLn[6];
                                                    $dstBrnchNm = $rwLn[7];
                                                    $dstBnfcry = $rwLn[8];
                                                    $dstBnfcryAdrs = $rwLn[9];
                                                    ?>
                                                    <tr id="oneTrnsfrDstntnsRow_<?php echo $cntrUsr; ?>">                                    
                                                        <td class="lovtd"><span><?php echo ($cntrUsr); ?></span></td>
                                                        <td class="lovtd">
                                                            <input type="hidden" class="form-control" aria-label="..." id="oneTrnsfrDstntnsRow<?php echo $cntrUsr; ?>_LineID" value="<?php echo $ordrDstID; ?>">
                                                            <?php if ($canEdtOrdr === true) { ?>
                                                                <select class="form-control" id="oneTrnsfrDstntnsRow<?php echo $cntrUsr; ?>_TrsfrTyp" <?php echo $mkReadOnly; ?> onchange="onChangeTrnfrTyp1('oneTrnsfrDstntnsRow<?php echo $cntrUsr; ?>');">
                                                                    <?php
                                                                    $valslctdArry = array("", "", "", "");
                                                                    $srchInsArrys = array("Interbank", "In-House", "Cheque Mailing", "Swift");
                                                                    $valsArrys = array("Interbank", "In-House", "Cheque Mailing", "Swift");
                                                                    for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                                        if ($trnsfrMthd == $valsArrys[$z]) {
                                                                            $valslctdArry[$z] = "selected";
                                                                        }
                                                                        ?>
                                                                        <option value="<?php echo $valsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            <?php } else { ?>
                                                                <span><?php echo $trnsfrMthd; ?></span>
                                                            <?php } ?> 
                                                        </td>
                                                        <td class="lovtd">
                                                            <?php if ($canEdtOrdr === true) { ?>
                                                                <select class="form-control" id="oneTrnsfrDstntnsRow<?php echo $cntrUsr; ?>_DstTyp" <?php echo $mkReadOnly; ?> onchange="onChangeTrnfrTyp1('oneTrnsfrDstntnsRow<?php echo $cntrUsr; ?>');">
                                                                    <?php
                                                                    $valslctdArry = array("", "", "");
                                                                    $srchInsArrys = array("Bank Account", "Mobile Money Wallet", "Money Transfer Token");
                                                                    $valsArrys = array("Bank Account", "Mobile Money Wallet", "Money Transfer Token");
                                                                    for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                                        if ($dstnType == $valsArrys[$z]) {
                                                                            $valslctdArry[$z] = "selected";
                                                                        }
                                                                        ?>
                                                                        <option value="<?php echo $valsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            <?php } else { ?>
                                                                <span><?php echo $dstnType; ?></span>
                                                            <?php } ?> 
                                                        </td>
                                                        <td class="lovtd">
                                                            <?php if ($canEdtOrdr === true) { ?>                                      
                                                                <div class="input-group">
                                                                    <input type="text" class="form-control rqrdFld" aria-label="..." id="oneTrnsfrDstntnsRow<?php echo $cntrUsr; ?>_DstAcntNo" value="<?php echo $dstnAccount; ?>" style="width:100%;" onblur="afterAcntSlctn('oneTrnsfrDstntnsRow_<?php echo $cntrUsr; ?>', '_BnfcryNm');" onfocusout="afterAcntSlctn('oneTrnsfrDstntnsRow_<?php echo $cntrUsr; ?>','_BnfcryNm');">
                                                                    <input type="hidden" id="oneTrnsfrDstntnsRow<?php echo $cntrUsr; ?>_DstAcntID" value="<?php echo $dstnAccountID; ?>">                                            
                                                                    <input type="hidden" id="oneTrnsfrDstntnsRow<?php echo $cntrUsr; ?>_AcRawTxt1" value="<?php echo ''; ?>">
                                                                    <?php if ($rqStatus != "Authorized" && $rqStatus != "Cancelled") { ?>
                                                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'MCF All Bank Accounts', '', '', '', 'radio', true, '', 'oneTrnsfrDstntnsRow<?php echo $cntrUsr; ?>_DstAcntID', 'oneTrnsfrDstntnsRow<?php echo $cntrUsr; ?>_AcRawTxt1', 'clear', 1, '', function () {
                                                                                                    $('#oneTrnsfrDstntnsRow<?php echo $cntrUsr; ?>_DstAcntNo').val($('#oneTrnsfrDstntnsRow<?php echo $cntrUsr; ?>_AcRawTxt1').val().split(' [')[0]);
                                                                                                    $('#oneTrnsfrDstntnsRow<?php echo $cntrUsr; ?>_BnfcryNm').val(rhotrim($('#oneTrnsfrDstntnsRow<?php echo $cntrUsr; ?>_AcRawTxt1').val().split(' [')[1], '] '));
                                                                                                });">
                                                                            <span class="glyphicon glyphicon-th-list"></span>
                                                                        </label>
                                                                    <?php } ?>
                                                                </div>
                                                            <?php } else { ?>
                                                                <span><?php echo $dstnAccount; ?></span>
                                                            <?php } ?> 
                                                        </td>
                                                        <td class="lovtd">
                                                            <?php if ($canEdtOrdr === true) { ?>
                                                                <input type="text" class="form-control rqrdFld" aria-label="..." id="oneTrnsfrDstntnsRow<?php echo $cntrUsr; ?>_DstAmnt" value="<?php
                                                                echo number_format($dstnAmnt, 2);
                                                                ?>" style="width:100%;text-align:right;font-weight:bold;">
                                                                   <?php } else { ?>
                                                                <span  style="text-align:right;font-weight:bold;"><?php echo number_format($dstnAmnt, 2); ?></span>
                                                            <?php } ?> 
                                                        </td>
                                                        <td class="lovtd">
                                                            <?php if ($canEdtOrdr === true) { ?>
                                                                <div class="input-group" style="width:100% !important;">
                                                                    <input type="text" class="form-control" aria-label="..." id="oneTrnsfrDstntnsRow<?php echo $cntrUsr; ?>_BnkNm" name="oneTrnsfrDstntnsRow<?php echo $cntrUsr; ?>_BnkNm" value="<?php echo $dstBnkNm; ?>" readonly="true">
                                                                    <input type="hidden" class="form-control" aria-label="..." id="oneTrnsfrDstntnsRow<?php echo $cntrUsr; ?>_BnkID" value="<?php echo $dstBnkID; ?>">                                               
                                                                    <label style="" class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'MCF All Banks', 'gnrlOrderOrgID', '', '', 'radio', true, '', 'oneTrnsfrDstntnsRow<?php echo $cntrUsr; ?>_BnkID', 'oneTrnsfrDstntnsRow<?php echo $cntrUsr; ?>_BnkNm', 'clear', 1, '', function () {

                                                                                        });">
                                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                                    </label>
                                                                </div>
                                                            <?php } else { ?>
                                                                <span><?php echo $dstBnkNm; ?></span>
                                                            <?php } ?> 
                                                        </td>
                                                        <td class="lovtd">
                                                            <?php if ($canEdtOrdr === true) { ?>
                                                                <div class="input-group" style="width:100% !important;">
                                                                    <input type="text" class="form-control" aria-label="..." id="oneTrnsfrDstntnsRow<?php echo $cntrUsr; ?>_BrnchNm" name="oneTrnsfrDstntnsRow<?php echo $cntrUsr; ?>_BrnchNm" value="<?php echo $dstBrnchNm; ?>" readonly="true">
                                                                    <input type="hidden" class="form-control" aria-label="..." id="oneTrnsfrDstntnsRow<?php echo $cntrUsr; ?>_BrnchID" value="<?php echo $dstBrnchID; ?>">                                              
                                                                    <label style="" class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Bank Branches MCF', 'oneTrnsfrDstntnsRow<?php echo $cntrUsr; ?>_BnkID', '', '', 'radio', true, '', 'oneTrnsfrDstntnsRow<?php echo $cntrUsr; ?>_BrnchID', 'oneTrnsfrDstntnsRow<?php echo $cntrUsr; ?>_BrnchNm', 'clear', 1, '');">
                                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                                    </label>
                                                                </div>
                                                            <?php } else { ?>
                                                                <span><?php echo $dstBrnchNm; ?></span>
                                                            <?php } ?> 
                                                        </td>
                                                        <td class="lovtd">
                                                            <?php if ($canEdtOrdr === true) { ?>                                                                        
                                                                <div class="input-group">
                                                                    <input type="text" class="form-control" aria-label="..." id="oneTrnsfrDstntnsRow<?php echo $cntrUsr; ?>_BnfcryNm" value="<?php echo $dstBnfcry; ?>" style="width:100%;" readonly="true">                                                                                                                                                                                                                  
                                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getCustAcctsInfo2Form('myFormsModalCA1', 'myFormsModalBodyCA1', 'myFormsModalTitleCA1', 'View Customer Account', 13, 2.1, 0, 'VIEW', 'oneTrnsfrDstntnsRow<?php echo $cntrUsr; ?>_DstAcntID', 'custAcctTable', '', 'oneTrnsfrDstntnsRow<?php echo $cntrUsr; ?>_DstAcntNo');" data-toggle="tooltip" data-placement="bottom" title = "Latest Account Information">
                                                                        <span class="glyphicon glyphicon-info-sign"></span>
                                                                    </label>
                                                                </div>                                                                       
                                                            <?php } else { ?>
                                                                <span><?php echo $dstBnfcry; ?></span>
                                                            <?php } ?> 
                                                        </td>
                                                        <td class="lovtd">
                                                            <?php if ($canEdtOrdr === true) { ?>
                                                                <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delAcntTrnsfrHdrDst('oneTrnsfrDstntnsRow_<?php echo $cntrUsr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Transfer Destination">
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
                            </div>
                            <div id="trnsfrExtrSources" class="tab-pane fadein hideNotice" style="border:none !important;padding:0px !important;">
                                <?php
                                $entrdCrncyNm = $fnccurnm;
                                ?>
                                <div class="row">
                                    <div class="col-md-12">
                                        <button id="refreshSourceAcntBtn" type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="getOneAcntTrnsfrForm(-1, 'Transfer/Order', 'ReloadDialog');" data-toggle="tooltip" data-placement="bottom" title = "Reload Transfers/Orders">
                                            <img src="cmn_images/refresh.bmp" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                            Refresh
                                        </button>
                                        <button id="infoSourceAcntBtn" type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="">
                                            <span style="color:red;font-family:georgia;font-style:italic;font-weight:bold;">NB:The entered amounts here will each be DEPOSITED into the DESTINATION ACCOUNT above!</span>
                                        </button>
                                    </div>                                            
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <table class="table table-striped table-bordered table-responsive" id="oneTrnsfrExtrSourcesTable" cellspacing="0" width="100%" style="width:100%;min-width: 300px !important;">
                                            <thead>
                                                <tr>
                                                    <th style="max-width: 45px;width: 45px;">No.</th>
                                                    <th style="max-width: 100px;width: 100px;">Transaction Type</th>
                                                    <th style="max-width: 200px;width: 200px;">In-House Account No.</th>
                                                    <th style="text-align:right;max-width: 100px;width: 100px;">Amount</th>
                                                    <th style="max-width: 45px;width: 45px;display:none;">CUR.</th>
                                                    <th style="max-width: 200px;width: 200px;">Narration</th>
                                                    <th style="text-align:right;max-width: 100px;width: 100px;display:none;">Rate</th>
                                                    <th style="max-width: 200px;width: 200px;">In-House Account Title</th>
                                                    <th style="max-width: 30px;width: 30px;">&nbsp;</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $rslt = get_OneStndOrdrSources($sbmtdTrnsfrHdrID);
                                                $cntrUsr = 0;
                                                while ($rwLn = loc_db_fetch_array($rslt)) {
                                                    $cntrUsr++;
                                                    $ordrMiscID = (float) $rwLn[0];
                                                    $rmrkDesc = $rwLn[7];
                                                    $trnsType = $rwLn[6];
                                                    $dstnAccountNm = $rwLn[4];
                                                    $dstnAccount = $rwLn[3];
                                                    $dstnAccountID = (float) $rwLn[2];
                                                    $dstnAmnt = (float) $rwLn[5];
                                                    $dstCrncyID = (float) $rwLn[8];
                                                    $entrdCrncyNm = $rwLn[10];
                                                    $dstnRate = (float) $rwLn[11];
                                                    ?>
                                                    <tr id="oneTrnsfrExtrSourcesRow_<?php echo $cntrUsr; ?>">                                    
                                                        <td class="lovtd"><span><?php echo ($cntrUsr); ?></span></td>
                                                        <td class="lovtd">
                                                            <input type="hidden" class="form-control" aria-label="..." id="oneTrnsfrExtrSourcesRow<?php echo $cntrUsr; ?>_LineID" value="<?php echo $ordrMiscID; ?>" onblur="afterAcntSlctn('oneTrnsfrExtrSourcesRow_<?php echo $cntr; ?>');" onfocusout="afterAcntSlctn('oneTrnsfrExtrSourcesRow_<?php echo $cntr; ?>');">
                                                            <?php if ($canEdtOrdr === true) { ?>
                                                                <select class="form-control" id="oneTrnsfrExtrSourcesRow<?php echo $cntrUsr; ?>_TrnsTyp" <?php echo $mkReadOnly; ?> onchange="">
                                                                    <?php
                                                                    $valslctdArry = array("");
                                                                    $srchInsArrys = array("DEBIT");
                                                                    $valsArrys = array("WITHDRAWAL");
                                                                    for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                                        if ($trnsType == $valsArrys[$z]) {
                                                                            $valslctdArry[$z] = "selected";
                                                                        }
                                                                        ?>
                                                                        <option value="<?php echo $valsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            <?php } else { ?>
                                                                <span><?php echo $trnsType; ?></span>
                                                            <?php } ?> 
                                                        </td>
                                                        <td class="lovtd">
                                                            <?php if ($canEdtOrdr === true) { ?>                                      
                                                                <div class="input-group">
                                                                    <input type="text" class="form-control rqrdFld" aria-label="..." id="oneTrnsfrExtrSourcesRow<?php echo $cntrUsr; ?>_DstAcntNo" value="<?php echo $dstnAccount; ?>" style="width:100%;">
                                                                    <input type="hidden" id="oneTrnsfrExtrSourcesRow<?php echo $cntrUsr; ?>_DstAcntID" value="<?php echo $dstnAccountID; ?>">                                            
                                                                    <input type="hidden" id="oneTrnsfrExtrSourcesRow<?php echo $cntrUsr; ?>_AcRawTxt1" value="<?php echo ''; ?>">
                                                                    <?php if ($rqStatus != "Authorized" && $rqStatus != "Cancelled") { ?>
                                                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'MCF All Bank Accounts', '', '', '', 'radio', true, '', 'oneTrnsfrExtrSourcesRow<?php echo $cntrUsr; ?>_DstAcntID', 'oneTrnsfrExtrSourcesRow<?php echo $cntrUsr; ?>_AcRawTxt1', 'clear', 1, '', function () {
                                                                                                    $('#oneTrnsfrExtrSourcesRow<?php echo $cntrUsr; ?>_DstAcntNo').val($('#oneTrnsfrExtrSourcesRow<?php echo $cntrUsr; ?>_AcRawTxt1').val().split(' [')[0]);
                                                                                                    $('#oneTrnsfrExtrSourcesRow<?php echo $cntrUsr; ?>_AcntTitle').val(rhotrim($('#oneTrnsfrExtrSourcesRow<?php echo $cntrUsr; ?>_AcRawTxt1').val().split(' [')[1], '] '));
                                                                                                });">
                                                                            <span class="glyphicon glyphicon-th-list"></span>
                                                                        </label>
                                                                    <?php } ?>
                                                                </div>
                                                            <?php } else { ?>
                                                                <span><?php echo $dstnAccount; ?></span>
                                                            <?php } ?> 
                                                        </td>
                                                        <td class="lovtd">
                                                            <?php if ($canEdtOrdr === true) { ?>
                                                                <input type="text" class="form-control" aria-label="..." id="oneTrnsfrExtrSourcesRow<?php echo $cntrUsr; ?>_DstAmnt" value="<?php
                                                                echo number_format($dstnAmnt, 2);
                                                                ?>" style="width:100%;text-align:right;font-weight:bold;">
                                                                   <?php } else { ?>
                                                                <span  style="text-align:right;font-weight:bold;"><?php echo number_format($dstnAmnt, 2); ?></span>
                                                            <?php } ?> 
                                                        </td>                                                  
                                                        <td class="lovtd" style="display:none;">
                                                            <div class="" style="width:100% !important;">
                                                                <input type="hidden" class="form-control" aria-label="..." id="oneTrnsfrExtrSourcesRow<?php echo $cntrUsr; ?>_TrnsCurNm" name="oneTrnsfrExtrSourcesRow<?php echo $cntrUsr; ?>_TrnsCurNm" value="<?php echo $entrdCrncyNm; ?>" readonly="true" style="width:100% !important;">
                                                                <label class="btn btn-primary btn-file" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Currencies', '', '', '', 'radio', true, '', 'oneTrnsfrExtrSourcesRow<?php echo $cntrUsr; ?>_TrnsCurNm', '', 'clear', 1, '', function () {
                                                                                    $('#oneTrnsfrExtrSourcesRow<?php echo $cntrUsr; ?>_TrnsCurNm1').html($('#oneTrnsfrExtrSourcesRow<?php echo $cntrUsr; ?>_TrnsCurNm').val());
                                                                                });">
                                                                    <span class="" id="oneTrnsfrExtrSourcesRow<?php echo $cntrUsr; ?>_TrnsCurNm1"><?php echo $entrdCrncyNm; ?></span>
                                                                </label>
                                                            </div>                                              
                                                        </td>
                                                        <td class="lovtd">
                                                            <?php if ($canEdtOrdr === true) { ?>
                                                                <input type="text" class="form-control" aria-label="..." id="oneTrnsfrExtrSourcesRow<?php echo $cntrUsr; ?>_Rmrks" value="<?php echo $rmrkDesc; ?>" style="width:100%;">                                                                       
                                                            <?php } else { ?>
                                                                <span><?php echo $rmrkDesc; ?></span>
                                                            <?php } ?> 
                                                        </td> 
                                                        <td class="lovtd" style="display:none;">
                                                            <?php if ($canEdtOrdr === true) { ?>
                                                                <input type="text" class="form-control" aria-label="..." id="oneTrnsfrExtrSourcesRow<?php echo $cntrUsr; ?>_Rate" value="<?php
                                                                echo number_format($dstnRate, 4);
                                                                ?>" style="width:100%;text-align:right;font-weight:bold;">
                                                                   <?php } else { ?>
                                                                <span  style="text-align:right;font-weight:bold;"><?php echo number_format($dstnRate, 4); ?></span>
                                                            <?php } ?> 
                                                        </td> 
                                                        <td class="lovtd">
                                                            <?php if ($canEdtOrdr === true) { ?>
                                                                <div class="input-group">
                                                                    <input type="text" class="form-control" aria-label="..." id="oneTrnsfrExtrSourcesRow<?php echo $cntrUsr; ?>_AcntTitle" value="<?php echo $dstnAccountNm; ?>" style="width:100%;" readonly="true">                                                                                                                                                                                                                  
                                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getCustAcctsInfo2Form('myFormsModalCA1', 'myFormsModalBodyCA1', 'myFormsModalTitleCA1', 'View Customer Account', 13, 2.1, 0, 'VIEW', 'oneTrnsfrExtrSourcesRow<?php echo $cntrUsr; ?>_DstAcntID', 'custAcctTable', '', 'oneTrnsfrExtrSourcesRow<?php echo $cntrUsr; ?>_DstAcntNo');" data-toggle="tooltip" data-placement="bottom" title = "Latest Account Information">
                                                                        <span class="glyphicon glyphicon-info-sign"></span>
                                                                    </label>
                                                                </div>
                                                            <?php } else { ?>
                                                                <span><?php echo $dstnAccountNm; ?></span>
                                                            <?php } ?> 
                                                        </td>
                                                        <td class="lovtd">
                                                            <?php if ($canEdtOrdr === true) { ?>
                                                                <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delAcntTrnsfrHdrSrc('oneTrnsfrExtrSourcesRow_<?php echo $cntrUsr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Miscellaneous Trns.">
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
                            </div>
                            <div id="trnsfrMiscTrns" class="tab-pane fadein hideNotice" style="border:none !important;padding:0px !important;">
                                <?php
                                $entrdCrncyNm = $fnccurnm;
                                ?>
                                <div class="row">
                                    <div class="col-md-12">
                                        <button id="refreshDstnAcntBtn" type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="getOneAcntTrnsfrForm(-1, 'Transfer/Order', 'ReloadDialog');" data-toggle="tooltip" data-placement="bottom" title = "Reload Transfers/Orders">
                                            <img src="cmn_images/refresh.bmp" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                            Refresh
                                        </button>
                                    </div>                                            
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <table class="table table-striped table-bordered table-responsive" id="oneTrnsfrMiscTrnsTable" cellspacing="0" width="100%" style="width:100%;min-width: 300px !important;">
                                            <thead>
                                                <tr>
                                                    <th style="max-width: 45px;width: 45px;">No.</th>
                                                    <th style="max-width: 100px;width: 100px;">Transaction Type</th>
                                                    <th style="max-width: 200px;width: 200px;">In-House Account No.</th>
                                                    <th style="text-align:right;max-width: 100px;width: 100px;">Amount</th>
                                                    <th style="max-width: 45px;width: 45px;">CUR.</th>
                                                    <th style="max-width: 200px;width: 200px;">Narration</th>
                                                    <th style="max-width: 200px;width: 200px;">GL Account</th>
                                                    <th style="text-align:right;max-width: 100px;width: 100px;">Rate</th>
                                                    <th style="max-width: 200px;width: 200px;">In-House Account Title</th>
                                                    <th style="max-width: 30px;width: 30px;">&nbsp;</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $rslt = get_OneStndOrdrMisc($sbmtdTrnsfrHdrID);
                                                $cntrUsr = 0;
                                                while ($rwLn = loc_db_fetch_array($rslt)) {
                                                    $cntrUsr++;
                                                    $ordrMiscID = (float) $rwLn[0];
                                                    $rmrkDesc = $rwLn[7];
                                                    $trnsType = $rwLn[6];
                                                    $dstnAccountNm = $rwLn[4];
                                                    $dstnAccount = $rwLn[3];
                                                    $dstnAccountID = (float) $rwLn[2];
                                                    $dstnAmnt = (float) $rwLn[5];
                                                    $dstCrncyID = (float) $rwLn[8];
                                                    $entrdCrncyNm = $rwLn[10];
                                                    $miscOrdrGlAcntID = (float) $rwLn[11];
                                                    $miscOrdrGlAcntNm = $rwLn[12];
                                                    $dstnRate = (float) $rwLn[15];
                                                    ?>
                                                    <tr id="oneTrnsfrMiscTrnsRow_<?php echo $cntrUsr; ?>">                                    
                                                        <td class="lovtd"><span><?php echo ($cntrUsr); ?></span></td>
                                                        <td class="lovtd">
                                                            <input type="hidden" class="form-control" aria-label="..." id="oneTrnsfrMiscTrnsRow<?php echo $cntrUsr; ?>_LineID" value="<?php echo $ordrMiscID; ?>" onblur="afterAcntSlctn('oneTrnsfrMiscTrnsRow_<?php echo $cntr; ?>');" onfocusout="afterAcntSlctn('oneTrnsfrMiscTrnsRow_<?php echo $cntr; ?>');">
                                                            <?php if ($canEdtOrdr === true) { ?>
                                                                <select class="form-control" id="oneTrnsfrMiscTrnsRow<?php echo $cntrUsr; ?>_TrnsTyp" <?php echo $mkReadOnly; ?> onchange="">
                                                                    <?php
                                                                    $valslctdArry = array("", "");
                                                                    $srchInsArrys = array("CREDIT", "DEBIT");
                                                                    $valsArrys = array("DEPOSIT", "WITHDRAWAL");
                                                                    for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                                        if ($trnsType == $valsArrys[$z]) {
                                                                            $valslctdArry[$z] = "selected";
                                                                        }
                                                                        ?>
                                                                        <option value="<?php echo $valsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            <?php } else { ?>
                                                                <span><?php echo $trnsType; ?></span>
                                                            <?php } ?> 
                                                        </td>
                                                        <td class="lovtd">
                                                            <?php if ($canEdtOrdr === true) { ?>                                      
                                                                <div class="input-group">
                                                                    <input type="text" class="form-control rqrdFld" aria-label="..." id="oneTrnsfrMiscTrnsRow<?php echo $cntrUsr; ?>_DstAcntNo" value="<?php echo $dstnAccount; ?>" style="width:100%;">
                                                                    <input type="hidden" id="oneTrnsfrMiscTrnsRow<?php echo $cntrUsr; ?>_DstAcntID" value="<?php echo $dstnAccountID; ?>">                                            
                                                                    <input type="hidden" id="oneTrnsfrMiscTrnsRow<?php echo $cntrUsr; ?>_AcRawTxt1" value="<?php echo ''; ?>">
                                                                    <?php if ($rqStatus != "Authorized" && $rqStatus != "Cancelled") { ?>
                                                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'MCF All Bank Accounts', '', '', '', 'radio', true, '', 'oneTrnsfrMiscTrnsRow<?php echo $cntrUsr; ?>_DstAcntID', 'oneTrnsfrMiscTrnsRow<?php echo $cntrUsr; ?>_AcRawTxt1', 'clear', 1, '', function () {
                                                                                                    $('#oneTrnsfrMiscTrnsRow<?php echo $cntrUsr; ?>_DstAcntNo').val($('#oneTrnsfrMiscTrnsRow<?php echo $cntrUsr; ?>_AcRawTxt1').val().split(' [')[0]);
                                                                                                    $('#oneTrnsfrMiscTrnsRow<?php echo $cntrUsr; ?>_AcntTitle').val(rhotrim($('#oneTrnsfrMiscTrnsRow<?php echo $cntrUsr; ?>_AcRawTxt1').val().split(' [')[1], '] '));
                                                                                                });">
                                                                            <span class="glyphicon glyphicon-th-list"></span>
                                                                        </label>
                                                                    <?php } ?>
                                                                </div>
                                                            <?php } else { ?>
                                                                <span><?php echo $dstnAccount; ?></span>
                                                            <?php } ?> 
                                                        </td>
                                                        <td class="lovtd">
                                                            <?php if ($canEdtOrdr === true) { ?>
                                                                <input type="text" class="form-control" aria-label="..." id="oneTrnsfrMiscTrnsRow<?php echo $cntrUsr; ?>_DstAmnt" value="<?php
                                                                echo number_format($dstnAmnt, 2);
                                                                ?>" style="width:100%;text-align:right;font-weight:bold;">
                                                                   <?php } else { ?>
                                                                <span  style="text-align:right;font-weight:bold;"><?php echo number_format($dstnAmnt, 2); ?></span>
                                                            <?php } ?> 
                                                        </td>                                                  
                                                        <td class="lovtd">
                                                            <div class="" style="width:100% !important;">
                                                                <input type="hidden" class="form-control" aria-label="..." id="oneTrnsfrMiscTrnsRow<?php echo $cntrUsr; ?>_TrnsCurNm" name="oneTrnsfrMiscTrnsRow<?php echo $cntrUsr; ?>_TrnsCurNm" value="<?php echo $entrdCrncyNm; ?>" readonly="true" style="width:100% !important;">
                                                                <label class="btn btn-primary btn-file" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Currencies', '', '', '', 'radio', true, '', 'oneTrnsfrMiscTrnsRow<?php echo $cntrUsr; ?>_TrnsCurNm', '', 'clear', 1, '', function () {
                                                                                    $('#oneTrnsfrMiscTrnsRow<?php echo $cntrUsr; ?>_TrnsCurNm1').html($('#oneTrnsfrMiscTrnsRow<?php echo $cntrUsr; ?>_TrnsCurNm').val());
                                                                                });">
                                                                    <span class="" id="oneTrnsfrMiscTrnsRow<?php echo $cntrUsr; ?>_TrnsCurNm1"><?php echo $entrdCrncyNm; ?></span>
                                                                </label>
                                                            </div>                                              
                                                        </td>
                                                        <td class="lovtd">
                                                            <?php if ($canEdtOrdr === true) { ?>
                                                                <input type="text" class="form-control rqrdFld" aria-label="..." id="oneTrnsfrMiscTrnsRow<?php echo $cntrUsr; ?>_Rmrks" value="<?php echo $rmrkDesc; ?>" style="width:100%;">                                                                       
                                                            <?php } else { ?>
                                                                <span><?php echo $rmrkDesc; ?></span>
                                                            <?php } ?> 
                                                        </td>
                                                        <td class="lovtd">
                                                            <?php if ($canEdtOrdr === true) { ?>
                                                                <div class="input-group">
                                                                    <input class="form-control rqrdFld" id="oneTrnsfrMiscTrnsRow<?php echo $cntrUsr; ?>_GlAcntNum" style="font-size: 13px !important;font-weight: bold !important;" placeholder="Enter GL Account Number" type = "text" min="0" placeholder="" value="<?php echo $miscOrdrGlAcntNm; ?>" readonly="true"/>
                                                                    <input type="hidden" id="oneTrnsfrMiscTrnsRow<?php echo $cntrUsr; ?>_GlAcntID" value="<?php echo $miscOrdrGlAcntID; ?>">
                                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Accounts', '', '', '', 'radio', true, '', 'oneTrnsfrMiscTrnsRow<?php echo $cntrUsr; ?>_GlAcntID', 'oneTrnsfrMiscTrnsRow<?php echo $cntrUsr; ?>_GlAcntNum', 'clear', 1, '', function () {});">
                                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                                    </label>
                                                                </div>
                                                            <?php } else { ?>
                                                                <span  style="text-align:right;font-weight:bold;"><?php echo $miscOrdrGlAcntNm; ?></span>
                                                            <?php } ?> 
                                                        </td> 
                                                        <td class="lovtd">
                                                            <?php if ($canEdtOrdr === true) { ?>
                                                                <input type="text" class="form-control" aria-label="..." id="oneTrnsfrMiscTrnsRow<?php echo $cntrUsr; ?>_Rate" value="<?php
                                                                echo number_format($dstnRate, 4);
                                                                ?>" style="width:100%;text-align:right;font-weight:bold;">
                                                                   <?php } else { ?>
                                                                <span  style="text-align:right;font-weight:bold;"><?php echo number_format($dstnRate, 4); ?></span>
                                                            <?php } ?> 
                                                        </td> 
                                                        <td class="lovtd">
                                                            <?php if ($canEdtOrdr === true) { ?>
                                                                <div class="input-group">
                                                                    <input type="text" class="form-control" aria-label="..." id="oneTrnsfrMiscTrnsRow<?php echo $cntrUsr; ?>_AcntTitle" value="<?php echo $dstnAccountNm; ?>" style="width:100%;" readonly="true">                                                                                                                                                                                                                  
                                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getCustAcctsInfo2Form('myFormsModalCA1', 'myFormsModalBodyCA1', 'myFormsModalTitleCA1', 'View Customer Account', 13, 2.1, 0, 'VIEW', 'oneTrnsfrMiscTrnsRow<?php echo $cntrUsr; ?>_DstAcntID', 'custAcctTable', '', 'oneTrnsfrMiscTrnsRow<?php echo $cntrUsr; ?>_DstAcntNo');" data-toggle="tooltip" data-placement="bottom" title = "Latest Account Information">
                                                                        <span class="glyphicon glyphicon-info-sign"></span>
                                                                    </label>
                                                                </div>
                                                            <?php } else { ?>
                                                                <span><?php echo $dstnAccountNm; ?></span>
                                                            <?php } ?> 
                                                        </td>
                                                        <td class="lovtd">
                                                            <?php if ($canEdtOrdr === true) { ?>
                                                                <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delAcntTrnsfrHdrMisc('oneTrnsfrMiscTrnsRow_<?php echo $cntrUsr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Miscellaneous Trns.">
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
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </fieldset>
    </form>
    <?php
}

function getBulkTrnsRdOnlyDsply($sbmtdBatchHdrID, $trnsType) {
    global $canview;
    global $gnrlTrnsDteDMYHMS;
    global $gnrlTrnsDteYMDHMS;
    global $uName;
    global $fnccurnm;
    global $fnccurid;
    global $prsnid;
    global $orgID;
    global $usrID;
    if (!$canview) {
        restricted();
        exit();
    }
    $canAddTrns = false;
    $canEdtTrns = false;
    $canDelTrns = false;
    $sbmtdRltnPrsnID = -1;
    $pAcctID = -1;
    $sbmtdTrnsCrncyNm = "";
    if ($sbmtdBatchHdrID <= 0) {
        restricted();
        exit();
    }
    $shdDoCashless = 0;
    $orgnlBatchHdrID = $sbmtdBatchHdrID;
    $rqStatus = "Not Submitted";
    $actType = "";
    $rqstatusColor = "red";
    $dte = date('ymdHis');
    $usrTrnsCode = getGnrlRecNm("sec.sec_users", "user_id", "code_for_trns_nums", $usrID);
    $gnrtdTrnsNo = "BATCH-" . $usrTrnsCode . "-" . $dte;

    $gnrtdTrnsDate = $gnrlTrnsDteDMYHMS; //date('d-M-Y H:i:s');
    $gnrtdTrnsDate1 = $gnrlTrnsDteYMDHMS; //date('Y-m-d H:i:s');
    $prprdBy = "<span style=\"color:blue;font-weight:bold;\">" . $uName . "@" . $gnrtdTrnsDate . "</span>";
    $brnchLocID = -1;
    $brnchLoc = "";
    //$crncyID = $fnccurid;
    $crncyIDNm = $fnccurnm;
    if ($sbmtdTrnsCrncyNm != "") {
        $crncyIDNm = $sbmtdTrnsCrncyNm;
    }
    $crncyID = (int) getGnrlRecNm("mcf.mcf_currencies", "mapped_lov_crncy_id", "crncy_id", $fnccurid);
    $destVltID = -1;
    $destCageID = -1;
    $destVltNm = "";
    $destCageNm = "";
    $athrztnStatus = "<span style=\"color:red;font-weight:bold;\">Not Submitted</span>";
    $voidedBatchHdrID = -1;
    $vmsTrnsDesc = "";
    $vmsTrnsTtlAmnt = 0;
    $mkReadOnly = "";
    $mkRmrkReadOnly = "";
    $exchangeRate = 0;
    $vmsTrnsPrsn = "N/A";
    if ($sbmtdRltnPrsnID <= 0) {
        $sbmtdRltnPrsnID = $prsnid;
    }
    $vmsOffctStaffLocID = getPersonLocID($sbmtdRltnPrsnID);
    $vmsOffctStaff = getPrsnFullNm($sbmtdRltnPrsnID);
    $vmsOffctStaffPrsID = $sbmtdRltnPrsnID;
    $extrInputFlds = "";
    $capturedItemIDs = "";
    $dfltDstItemState = "Issuable";
    $dfltMiscGlAcntID = -1;
    $dfltMiscGlAcntNo = "";
    if ($sbmtdBatchHdrID > 0) {
        $result = get_One_BatchTrnsHdr($sbmtdBatchHdrID);
        if ($row = loc_db_fetch_array($result)) {
            $rqStatus = $row[10];
            $voidedBatchHdrID = (float) $row[14];
            if ($rqStatus == "Not Submitted" || $rqStatus == "Withdrawn" || $rqStatus == "Rejected") {
                $rqstatusColor = "red";
                if ($voidedBatchHdrID <= 0) {
                    $mkReadOnly = "";
                    $mkRmrkReadOnly = "";
                } else {
                    $mkReadOnly = "readonly=\"true\"";
                    $mkRmrkReadOnly = "";
                }
            } else if ($rqStatus != "Authorized") {
                $mkReadOnly = "readonly=\"true\"";
                $mkRmrkReadOnly = "readonly=\"true\"";
                $rqstatusColor = "brown";
            } else {
                $rqstatusColor = "green";
                $mkReadOnly = "readonly=\"true\"";
                $mkRmrkReadOnly = "readonly=\"true\"";
            }
            $gnrtdTrnsNo = $row[1];
            $gnrtdTrnsDate = $row[2];
            $uName11 = getUserName((float) $row[25]);
            $prprdBy = "<span style=\"color:blue;font-weight:bold;\">" . $uName11 . "@" . $gnrtdTrnsDate . "</span>";
            $brnchLocID = (int) $row[3];
            $brnchLoc = $row[4];
            $crncyID = (int) $row[22];
            $crncyIDNm = $row[23];
            if ($crncyID <= 0) {
                $crncyID = $fnccurid;
                $crncyIDNm = $fnccurnm;
            }
            $destVltID = (int) $row[17];
            $destCageID = (int) $row[19];
            $dfltDstItemState = $row[21];
            $destVltNm = $row[18];
            $destCageNm = $row[20];
            if ($row[10] == "Authorized") {
                $athrztnStatus = "<span style=\"color:green;font-weight:bold;\">" . $row[10] . "</span>";
            } else {
                $athrztnStatus = "<span style=\"color:red;font-weight:bold;\">" . $row[10] . "</span>";
            }
            if ($voidedBatchHdrID <= 0) {
                $vmsTrnsDesc = $row[5];
            } else {
                $vmsTrnsDesc = $row[15];
            }
            $vmsTrnsTtlAmnt = (float) $row[9];
            $exchangeRate = $row[24];
            $vmsOffctStaffLocID = $row[8];
            $vmsOffctStaff = $row[7];
            $vmsOffctStaffPrsID = (float) $row[6];
            $dfltMiscGlAcntID = (float) $row[30];
            $dfltMiscGlAcntNo = $row[31];
            $shdDoCashless = (int) $row[32];
            if ($sbmtdTrnsCrncyNm != "" && $crncyIDNm != $sbmtdTrnsCrncyNm && ($rqStatus == "Not Submitted" || $rqStatus == "Withdrawn" || $rqStatus == "Rejected")) {
                $crncyIDNm = $sbmtdTrnsCrncyNm;
                $crncyIDt = getPssblValID($crncyIDNm, getLovID("Currencies"));
                $crncyID = (int) getGnrlRecNm("mcf.mcf_currencies", "mapped_lov_crncy_id", "crncy_id", $crncyIDt);
                execUpdtInsSQL("UPDATE mcf.mcf_bulk_trns_hdr SET crncy_id = " . $crncyID .
                        " WHERE bulk_trns_hdr_id = " . $sbmtdBatchHdrID, "Front-End Change:");
                $delSQL = "DELETE FROM mcf.mcf_account_trns_cash_analysis WHERE bulk_trns_hdr_id=" . $sbmtdBatchHdrID .
                        " and denomination_id NOT IN (SELECT crncy_denom_id FROM mcf.mcf_currency_denominations WHERE crncy_id = $crncyID)";
                execUpdtInsSQL($delSQL, "Front-End Change:");
            }
        }
    }
    /* Add */
    //$sbmtdBatchHdrID 
    $mcfPymtCrncyNm = isset($_POST['mcfPymtCrncyNm']) ? cleanInputData($_POST['mcfPymtCrncyNm']) : $fnccurnm;
    if ($mcfPymtCrncyNm == "") {
        $mcfPymtCrncyNm = $crncyIDNm;
    }
    $mcfPymtCrncyID = getPssblValID($mcfPymtCrncyNm, getLovID("Currencies"));
    $mcfCrncyID = (int) getGnrlRecNm("mcf.mcf_currencies", "mapped_lov_crncy_id", "crncy_id", $mcfPymtCrncyID);

    $usedVltID = $destVltID;
    $usedCageID = $destCageID;
    $usedCageNm = $destCageNm;
    $usedVltNm = $destVltNm;
    $itemState = $dfltDstItemState;
    $dsplyBalance = "";
    $usedInvAcntID = -1;
    $dfltItemState = $dfltDstItemState;
    if ($usedVltID <= 0 && $usedCageID <= 0) {
        $pID = getLatestCage($prsnid, $usedCageID, $usedVltID, $usedInvAcntID, $mcfPymtCrncyID);
        $usedCageNm = getGnrlRecNm("inv.inv_shelf", "line_id", "shelve_name", $usedCageID);
        $usedVltNm = getGnrlRecNm("inv.inv_itm_subinventories", "subinv_id", "subinv_name", $usedVltID);
        $dfltItemState = getGnrlRecNm("inv.inv_shelf", "line_id", "dflt_item_state", $usedCageID);
        $dfltDstItemState = $dfltItemState;
        $itemState = $dfltItemState;
        $destVltID = $usedVltID;
        $destCageID = $usedCageID;
        $destVltNm = $usedVltNm;
        $destCageNm = $usedCageNm;
    }
    $routingID = getMCFMxRoutingID($sbmtdBatchHdrID, "Bulk/Batch Transactions");
    $reportTitle = "Batch Transaction Report";
    $reportName = "Batch Transaction Report";
    $rptID = getRptID($reportName);
    $prmID1 = getParamIDUseSQLRep("{:P_BATCH_ID}", $rptID);
    $prmID2 = getParamIDUseSQLRep("{:documentTitle}", $rptID);
    $trnsID = $sbmtdBatchHdrID;
    $paramRepsNVals = $prmID1 . "~" . $trnsID . "|" . $prmID2 . "~" . $reportTitle . "|-130~" . $reportTitle . "|-190~PDF";
    $paramStr = urlencode($paramRepsNVals);

    $reportTitle1 = "Process Batch Transaction";
    $reportName1 = "Process Batch Transaction";
    $rptID1 = getRptID($reportName1);
    $prmID11 = getParamIDUseSQLRep("{:batch_id}", $rptID1);
    //$prmID31 = getParamIDUseSQLRep("{:p_msg_rtng_id}", $rptID1);
    $prmID21 = getParamIDUseSQLRep("{:documentTitle}", $rptID1);
    $trnsID1 = $sbmtdBatchHdrID;
    $paramRepsNVals1 = $prmID11 . "~" . $trnsID1 . "|" . $prmID21 . "~" . $reportTitle1 . "|-130~" . $reportTitle1 . "|-190~PDF";
    $paramStr1 = urlencode($paramRepsNVals1);
    $isCashTabActv = "active";
    $isNonCashTabActv = "";
    $shwCashTab = "";
    $shwNonCashTab = "";
    if ($shdDoCashless == 1) {
        $shwCashTab = "display:none;";
        $shwNonCashTab = "";
    }
    ?>
    <form class="form-horizontal" id="oneVmsTrnsEDTForm">
        <div class="row" style="margin: 0px 0px 10px 0px !important;">
            <div class="col-md-6" style="padding:0px 15px 0px 0px !important;">
                <div class="" style="padding:0px 0px 0px 0px;float:left !important;">                               
                    <button type="button" class="btn btn-default btn-sm" style="" id="myVmsTrnsStatusBtn"><span style="font-weight:bold;">Status: </span><span style="color:<?php echo $rqstatusColor; ?>;font-weight: bold;"><?php echo $rqStatus; ?></span></button>
                    <?php //if ($rqStatus == "Authorized") {                                        ?>
                    <button type="button" class="btn btn-default" style=""  onclick="getSilentRptsRnSts(<?php echo $rptID; ?>, -1, '<?php echo $paramStr; ?>');" style="width:100% !important;">
                        <img src="cmn_images/pdf.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">
                        Print Batch
                    </button>
                    <button type="button" class="btn btn-default" style="" onclick="getOneBulkTrnsForm(<?php echo $sbmtdBatchHdrID; ?>, 0, 'ReloadDialog', <?php echo $vmsOffctStaffPrsID; ?>);" data-toggle="tooltip" data-placement="bottom" title = "Reload Batch">
                        <img src="cmn_images/refresh.bmp" style="left: 0.5%; height:17px; width:auto; position: relative; vertical-align: middle;">
                    </button>
                    <?php //}                                             ?>
                </div>
            </div> 
            <div class="col-md-6" style="padding:0px 0px 0px 0px !important;">
                <div class="" style="padding:0px 0px 0px 0px;float:right !important;"> 
                    <?php
                    if ($rqStatus == "Authorized") {
                        ?>   
                        <button type="button" class="btn btn-default btn-sm" style="" onclick="checkWkfRqstStatus(<?php echo $routingID; ?>, 'Batch Trns. Authorization Progress History');"><img src="cmn_images/workflow.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;" data-toggle="tooltip" title="Authorization Progress History">Progress&nbsp;</button>  
                    <?php } else {
                        ?>
                        <button type="button" class="btn btn-default btn-sm" style="" onclick="checkWkfRqstStatus(<?php echo $routingID; ?>, 'Batch Trns. Authorization Progress History');"><img src="cmn_images/workflow.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;" data-toggle="tooltip" title="Authorization Progress History">Progress&nbsp;</button>  
                    <?php }
                    ?>
                </div>
            </div>                    
        </div>
        <fieldset class="basic_person_fs2" style="min-height:50px !important;">
            <!--<legend class="basic_person_lg">Transaction Header Information</legend>-->
            <div class="row" style="margin-top:5px;">
                <div class="col-md-4">
                    <div class="form-group">
                        <div class="col-md-2">
                            <label style="margin-bottom:0px !important;">Batch:</label>
                        </div>
                        <div class="col-md-6" style="padding:0px 1px 0px 15px;">
                            <input type="text" class="form-control" aria-label="..." id="vmsTrnsNum" name="vmsTrnsNum" value="<?php echo $gnrtdTrnsNo; ?>" readonly="true">
                            <input class="form-control" type="hidden" id="vmsTrnsHdrID" value="<?php echo $sbmtdBatchHdrID; ?>"/>
                            <input class="form-control" type="hidden" id="vmsVoidedTrnsHdrID" value="<?php echo $voidedBatchHdrID; ?>"/>
                            <input class="form-control" type="hidden" id="shdDoCashless" value="<?php echo $shdDoCashless; ?>"/>
                            <input class="form-control" type="hidden" id="newVMSHdrID" value="-1"/>
                            <input type="hidden" id="gnrlVmsOrgID" value="<?php echo $orgID; ?>">
                        </div>
                        <div class="col-md-4" style="padding:0px 15px 0px 1px;">
                            <input type="text" class="form-control" aria-label="..." id="vmsTrnsDate" name="vmsTrnsDate" value="<?php echo $gnrtdTrnsDate; ?>" readonly="true" style="font-size:9px;">                                    
                        </div>
                    </div>
                    <div class = "form-group">
                        <div class = "col-md-2">
                            <label style="margin-bottom:0px !important;margin-top:5px !important;">Location:</label>
                        </div>
                        <div class = "col-md-10">
                            <input type="text" class="form-control" aria-label="..." id="vmsBrnchLoc" name="vmsBrnchLoc" value="<?php echo $brnchLoc; ?>" readonly="true">
                            <input type="hidden" id="vmsBrnchLocID" value="<?php echo $brnchLocID; ?>">
                        </div>
                    </div>
                </div>
                <div class="col-md-4">                                                               
                    <div class="form-group">
                        <div class="col-md-4">
                            <label style="margin-bottom:0px !important;">Remark / Narration:</label>
                        </div>
                        <div class="col-md-8">
                            <div class="input-group">
                                <textarea class="form-control rqrdFld input-group-addon" rows="2" id="vmsTrnsDesc" name="vmsTrnsDesc" <?php echo $mkRmrkReadOnly; ?> style="text-align:left !important;"><?php echo $vmsTrnsDesc; ?></textarea>
                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'VMS Transaction Classifications', '', '', '', 'radio', true, '', '', 'vmsTrnsDesc', 'clear', 1, '');">
                                    <span class="glyphicon glyphicon-th-list"></span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class = "form-group">
                        <div class = "col-md-4">
                            <label style="margin-bottom:0px !important;">Relations Manager:</label>
                        </div>
                        <div class = "col-md-8">                                        
                            <div class="input-group" style="width:100% !important;">
                                <input type="text" class="form-control rqrdFld" aria-label="..." id="vmsOffctStaff" name="vmsOffctStaff" value="<?php echo $vmsOffctStaff; ?>" readonly="true" style="width:100% !important;">
                                <input type="hidden" class="form-control" aria-label="..." id="vmsOffctStaffLocID" value="<?php echo $vmsOffctStaffLocID; ?>" style="width:100% !important;">                                                    
                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Active Persons', 'gnrlVmsOrgID', '', '', 'radio', true, '', 'vmsOffctStaffLocID', 'vmsOffctStaff', 'clear', 1, '');">
                                    <span class="glyphicon glyphicon-th-list"></span>
                                </label>
                            </div> 
                        </div>
                    </div>
                </div>
                <div class = "col-md-4">
                    <div class="form-group">
                        <div class="col-md-4">
                            <label style="margin-bottom:0px !important;color:blue;margin-top:5px;">Total Amount:</label>
                        </div>
                        <div class="col-md-8">
                            <div class="input-group">                                    
                                <input type="text" class="form-control" aria-label="..." id="vmsTrnsCrncyNm" name="vmsTrnsCrncyNm" value="<?php echo $crncyIDNm; ?>" readonly="true" style="width:40px;max-width:40px;">    
                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Currencies', '', '', '', 'radio', true, '<?php echo $crncyIDNm; ?>', 'vmsTrnsCrncyNm', '', 'clear', 0, '', function () {
                                                getOneBulkTrnsForm(<?php echo $sbmtdBatchHdrID; ?>, 0, 'ReloadDialog', <?php echo $vmsOffctStaffPrsID; ?>);
                                            });"> 
                                    <span class="glyphicon glyphicon-th-list"></span>
                                </label> 
                                <input class="form-control rqrdFld vmsTtlAmt" type="text" id="ttlVMSDocAmntVal" value="<?php
                                echo number_format($vmsTrnsTtlAmnt, 2);
                                ?>"  style="font-weight:bold;" onchange="fmtAsNumber('ttlVMSDocAmntVal');" <?php echo $mkReadOnly; ?>/>
                            </div>
                        </div>
                    </div>                                
                    <div class="form-group form-group-sm">
                        <label for="dfltMiscGlAcnt" class="control-label col-md-4">Default GL Acc No.(Charges):</label>
                        <div  class="col-md-8">
                            <div class="input-group">
                                <input class="form-control" id="dfltMiscGlAcntNo" style="font-size: 13px !important;font-weight: bold !important;" placeholder="Enter GL Account Number" type = "text" min="0" placeholder="" value="<?php echo $dfltMiscGlAcntNo; ?>" readonly="true"/>
                                <input type="hidden" id="dfltMiscGlAcntID" value="<?php echo $dfltMiscGlAcntID; ?>">
                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Accounts', '', '', '', 'radio', true, '', 'dfltMiscGlAcntID', 'dfltMiscGlAcntNo', 'clear', 1, '', function () {});">
                                    <span class="glyphicon glyphicon-th-list"></span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </fieldset>
        <fieldset class="">
            <div class="row">
                <div class="col-md-12">
                    <ul class="nav nav-tabs" style="margin-top:1px !important;">
                        <li class="<?php echo $isCashTabActv; ?>"><a data-toggle="tabajxblktrns" data-rhodata="" href="#blkTrnsCash" id="blkTrnsCashtab" style="padding: 3px 10px !important;">Cash Transactions</a></li>
                        <li class="<?php echo $isNonCashTabActv; ?>"><a data-toggle="tabajxblktrns" data-rhodata="" href="#blkTrnsCheques" id="blkTrnsChequestab" style="padding: 3px 10px !important;<?php echo $shwNonCashTab; ?>">Cheque Deposit Transactions</a></li>
                        <li><a data-toggle="tabajxblktrns" data-rhodata="" href="#blkTrnsTllrTill" id="blkTrnsTllrTilltab" style="padding: 3px 10px !important;<?php echo $shwCashTab; ?>">Teller's Till/Cage Transaction</a></li>
                        <li><a data-toggle="tabajxblktrns" data-rhodata="" href="#blkTrnsMisc" id="blkTrnsMisctab" style="padding: 3px 10px !important;<?php echo $shwNonCashTab; ?>">Miscellaneous Transactions</a></li>
                    </ul>  
                    <div class="custDiv" style="padding:0px !important;min-height: 40px !important;"> 
                        <div class="tab-content" style="padding:5px !important;padding-top:7px !important;">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="col-md-6" style="padding:0px 0px 0px 0px !important;">
                                        <div class="col-md-12" style="padding:0px 0px 0px 0px !important;float:left;">
                                            <?php
                                            $dsplyNwLine = "";
                                            $acctCrncy = $crncyIDNm;
                                            ?> 
                                            <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="getOneMcfDocsForm_Gnrl(<?php echo $sbmtdBatchHdrID; ?>, 'Bulk/Batch Trasactions', 140, 'Batch Transaction Attachments');" data-toggle="tooltip" data-placement="bottom" title = "Attached Documents">
                                                <img src="cmn_images/adjunto.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                            </button>
                                            <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="getOneBulkTrnsForm(<?php echo $sbmtdBatchHdrID; ?>, 0, 'ReloadDialog', <?php echo $vmsOffctStaffPrsID; ?>);" data-toggle="tooltip" data-placement="bottom" title = "Load Defaults">
                                                <img src="cmn_images/refresh.bmp" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                            </button>
                                            <button type="button" class="btn btn-default btn-sm" style="height:30px;min-height:30px;margin-bottom: 5px;">
                                                <span style="font-weight:bold;color:black;">Total: </span>
                                                <span style="color:red;font-weight: bold;" id="myCptrdValsTtlBtn"><?php echo $crncyIDNm; ?> 
                                                    <?php
                                                    echo number_format($vmsTrnsTtlAmnt, 2);
                                                    ?>
                                                </span>
                                                <input type="hidden" id="myCptrdValsTtlVal" value="<?php echo $vmsTrnsTtlAmnt; ?>">
                                            </button>
                                        </div>   
                                    </div>                                 
                                    <div class="col-md-6">
                                        <div class="form-group form-group-sm">
                                            <div class = "col-md-2" style="padding:0px 0px 0px 0px !important;<?php echo $shwCashTab; ?>">
                                                <label style="margin-bottom:0px !important;padding:2px 0px 0px 5px !important;">Teller's Cage:&nbsp;</label>
                                            </div>
                                            <div class = "col-md-5" style="padding:0px 0px 0px 0px !important;<?php echo $shwCashTab; ?>">
                                                <div class="input-group">
                                                    <input type="text" class="form-control <?php echo $isSrcDstCagesRqrd; ?>" aria-label="..." id="vmsDfltDestVlt" name="vmsDfltDestVlt" value="<?php echo $destVltNm; ?>" readonly="true">
                                                    <input type="hidden" id="vmsDfltDestVltID" value="<?php echo $destVltID; ?>">
                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'VMS Vaults', 'gnrlVmsOrgID', '', '', 'radio', true, '<?php echo $destVltID; ?>', 'vmsDfltDestVltID', 'vmsDfltDestVlt', 'clear', 1, '');">
                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class = "col-md-5" style="padding:0px 0px 0px 0px !important;<?php echo $shwCashTab; ?>">
                                                <div class="input-group">
                                                    <input type="text" class="form-control <?php echo $isSrcDstCagesRqrd; ?>" aria-label="..." id="vmsDfltDestCage" name="vmsDfltDestCage" value="<?php echo $destCageNm; ?>" readonly="true">
                                                    <input type="hidden" id="vmsDfltDestCageID" value="<?php echo $destCageID; ?>">
                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'VMS Vault Cages', 'vmsDfltDestVltID', 'vmsTrnsCrncyNm', '', 'radio', true, '<?php echo $destCageID; ?>', 'vmsDfltDestCageID', 'vmsDfltDestCage', 'clear', 1, '', function () {
                                                                    //getOneBulkTrnsForm(<?php echo $sbmtdBatchHdrID; ?>, 0, 'ReloadDialog', <?php echo $vmsOffctStaffPrsID; ?>);
                                                                });">
                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                    </label>
                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getMcfDynmcCageFnPos('vmsDfltDestCageID', 7);">
                                                        <span class="glyphicon glyphicon-info-sign"></span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="custDiv" style="padding:0px !important;min-height: 40px !important;" id="oneVmsTrnsLnsTblSctn"> 
                        <div class="tab-content" style="padding:5px !important;padding-top:7px !important;">
                            <div id="blkTrnsCash" class="tab-pane fadein active" style="border:none !important;padding:0px !important;">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-md-12" id="oneVmsBCashTrnsLnsTblSctn">
                                                <table class="table table-striped table-bordered table-responsive" id="oneVmsBCashTrnsLnsTable" cellspacing="0" width="100%" style="width:100%;min-width: 900px !important;">
                                                    <thead>
                                                        <tr>
                                                            <th>No.</th>
                                                            <th style="max-width:60px;width:60px;">Transaction Type</th>
                                                            <th>Account Number&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                                            <th>Trns. Amount</th>
                                                            <th style="display:none;">CUR.</th>
                                                            <th style="max-width:60px;width:60px;">Doc Type</th>
                                                            <th>Voucher/Slip No.</th>
                                                            <th style="display:none;">Rate</th>
                                                            <th>Balance After Trns.</th>
                                                            <th style="display:none;">Mandate</th>
                                                            <th style="max-width: 200px;width: 200px;">In-House Account Title</th>
                                                            <th>&nbsp;</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        $acntChqsRslt = get_One_BlkCashTrnsHdr($sbmtdBatchHdrID);
                                                        $cntr = 0;
                                                        $docType = "Paperless";
                                                        $docNum = "";
                                                        while ($rwChqs = loc_db_fetch_array($acntChqsRslt)) {
                                                            $cntr++;
                                                            $trnsID = (float) $rwChqs[0];
                                                            $trnsType = $rwChqs[3];
                                                            $trnsNo = $rwChqs[20];
                                                            $dstnAccountID = $rwChqs[2];
                                                            $dstnAccount = $rwChqs[50];
                                                            $dstnAccountNm = $rwChqs[54];
                                                            $docType = $rwChqs[16];
                                                            $docNum = $rwChqs[8];
                                                            $entrdCrncyID = $rwChqs[23];
                                                            $entrdCrncyNm = $rwChqs[51];
                                                            $entrdAmnt = $rwChqs[5];
                                                            $entrdRate = $rwChqs[24];
                                                            $mdateTxt = $rwChqs[52];
                                                            if (trim($mdateTxt) == "") {
                                                                $mdateTxt = "Not Applicable";
                                                            }
                                                            $balsAfta = (float) $rwChqs[53];
                                                            $balsAftaD = $balsAfta + ($entrdAmnt * $entrdRate);
                                                            if ($trnsType == "WITHDRAWAL") {
                                                                $balsAftaD = $balsAfta - ($entrdAmnt * $entrdRate);
                                                            }
                                                            ?>
                                                            <tr id="oneVmsBCashTrnsRow_<?php echo $cntr; ?>">
                                                                <td class="lovtd"><span class=""><?php echo $cntr; ?></span></td>
                                                                <td class="lovtd">
                                                                    <select class="form-control" id="oneVmsBCashTrnsRow<?php echo $cntr; ?>_TrnsType" <?php echo $mkReadOnly; ?>  onchange="calcBlkCshRowsTtlVals();">
                                                                        <?php
                                                                        $valslctdArry = array("", ""/* , "" */);
                                                                        $srchInsArrys = array("DEPOSIT", "WITHDRAWAL"/* , "LOAN REPAY" */);
                                                                        $valsArrys = array("DEPOSIT", "WITHDRAWAL"/* , "LOAN_REPAY" */);
                                                                        for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                                            if ($trnsType == $valsArrys[$z]) {
                                                                                $valslctdArry[$z] = "selected";
                                                                            }
                                                                            ?>
                                                                            <option value="<?php echo $valsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                                                        <?php } ?>
                                                                    </select>
                                                                </td>
                                                                <td class="lovtd">
                                                                    <?php if ($canEdtTrns === true) { ?>                                      
                                                                        <div class="input-group">
                                                                            <input type="text" class="form-control blkCshAcnt" aria-label="..." id="oneVmsBCashTrnsRow<?php echo $cntr; ?>_DstAcntNo" value="<?php echo $dstnAccount; ?>" style="width:100%;" onkeypress="blkTrnsFormTtlFldKeyPress(event, 'oneVmsBCashTrnsRow<?php echo $cntr; ?>_DstAcntNo', 'oneVmsBCashTrnsLnsTable', 'blkCshAcnt');" onblur="afterBulkAcntSlctn('oneVmsBCashTrnsRow_<?php echo $cntr; ?>');"  onfocusout="afterBulkAcntSlctn('oneVmsBCashTrnsRow_<?php echo $cntr; ?>');">
                                                                            <input type="hidden" id="oneVmsBCashTrnsRow<?php echo $cntr; ?>_DstAcntID" value="<?php echo $dstnAccountID; ?>">                                            
                                                                            <input type="hidden" id="oneVmsBCashTrnsRow<?php echo $cntr; ?>_LineID" value="<?php echo $trnsID; ?>">                                            
                                                                            <input type="hidden" id="oneVmsBCashTrnsRow<?php echo $cntr; ?>_AcRawTxt1" value="<?php echo ''; ?>">
                                                                            <?php if ($rqStatus != "Authorized" && $rqStatus != "Cancelled") { ?>
                                                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'MCF All Bank Accounts', 'allOtherInputOrgID', 'vmsBrnchLocID', '', 'radio', true, '', 'oneVmsBCashTrnsRow<?php echo $cntr; ?>_DstAcntID', 'oneVmsBCashTrnsRow<?php echo $cntr; ?>_AcRawTxt1', 'clear', 1, '', function () {
                                                                                                            $('#oneVmsBCashTrnsRow<?php echo $cntr; ?>_DstAcntNo').val($('#oneVmsBCashTrnsRow<?php echo $cntr; ?>_AcRawTxt1').val().split(' [')[0]);
                                                                                                            $('#oneVmsBCashTrnsRow<?php echo $cntr; ?>_AcntTitle').val(rhotrim($('#oneVmsBCashTrnsRow<?php echo $cntr; ?>_AcRawTxt1').val().split(' [')[1], '] '));
                                                                                                            afterBulkAcntSlctn('oneVmsBCashTrnsRow_<?php echo $cntr; ?>');
                                                                                                        });">
                                                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                                                </label>
                                                                            <?php } ?>
                                                                        </div>
                                                                    <?php } else { ?>
                                                                        <span><?php echo $dstnAccount; ?></span>
                                                                    <?php } ?> 
                                                                </td> 
                                                                <td class="lovtd">
                                                                    <input type="text" class="form-control chqValCls rqrdFld blkCshAmnt" aria-label="..." id="oneVmsBCashTrnsRow<?php echo $cntr; ?>_chqVal" name="oneVmsBCashTrnsRow<?php echo $cntr; ?>_chqVal" value="<?php
                                                                    echo number_format((float) $entrdAmnt, 2);
                                                                    ?>" style="width:100% !important;text-align: right;" <?php echo $mkReadOnly; ?> onkeypress="blkTrnsFormTtlFldKeyPress(event, 'oneVmsBCashTrnsRow<?php echo $cntr; ?>_chqVal', 'oneVmsBCashTrnsLnsTable', 'blkCshAmnt');" onchange="calcBlkCshRowsTtlVals();"/>                                                    
                                                                </td>                                                   
                                                                <td class="lovtd" style="display:none;">
                                                                    <div class="" style="width:100% !important;">
                                                                        <input type="hidden" class="form-control" aria-label="..." id="oneVmsBCashTrnsRow<?php echo $cntr; ?>_TrnsCurNm" name="oneVmsBCashTrnsRow<?php echo $cntr; ?>_TrnsCurNm" value="<?php echo $entrdCrncyNm; ?>" readonly="true" style="width:100% !important;">
                                                                        <label class="btn btn-primary btn-file" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Currencies', '', '', '', 'radio', true, '', 'oneVmsBCashTrnsRow<?php echo $cntr; ?>_TrnsCurNm', '', 'clear', 1, '', function () {
                                                                                            $('#oneVmsBCashTrnsRow<?php echo $cntr; ?>_TrnsCurNm1').html($('#oneVmsBCashTrnsRow<?php echo $cntr; ?>_TrnsCurNm').val());
                                                                                        });">
                                                                            <span class="" id="oneVmsBCashTrnsRow<?php echo $cntr; ?>_TrnsCurNm1"><?php echo $entrdCrncyNm; ?></span>
                                                                        </label>
                                                                    </div>                                              
                                                                </td>
                                                                <td class="lovtd">
                                                                    <select class="form-control" id="oneVmsBCashTrnsRow<?php echo $cntr; ?>_DocType" <?php echo $mkReadOnly; ?> onchange="blkTrnsDocTypeChng('oneVmsBCashTrnsRow_<?php echo $cntr; ?>');">
                                                                        <?php
                                                                        $brghtStr = "";
                                                                        $isDynmyc = FALSE;
                                                                        $titleRslt = getLovValues("%", "Both", 0, 100, $brghtStr, getLovID("All MCF Document Types"), $isDynmyc, -1, "", "");
                                                                        while ($titleRow = loc_db_fetch_array($titleRslt)) {
                                                                            $selectedTxt = "";
                                                                            if ($titleRow[0] == $docType) {
                                                                                $selectedTxt = "selected";
                                                                            }
                                                                            ?>
                                                                            <option value="<?php echo $titleRow[0]; ?>" <?php echo $selectedTxt; ?>><?php echo $titleRow[0]; ?></option>
                                                                            <?php
                                                                        }
                                                                        ?>
                                                                    </select>
                                                                </td>
                                                                <td class="lovtd">
                                                                    <input type="text" class="form-control rqrdFld blkCshDocNm" aria-label="..." id="oneVmsBCashTrnsRow<?php echo $cntr; ?>_DocNo" name="oneVmsBCashTrnsRow<?php echo $cntr; ?>_DocNo" value="<?php echo $docNum; ?>" <?php echo $mkReadOnly; ?> onkeypress="blkTrnsFormTtlFldKeyPress(event, 'oneVmsBCashTrnsRow<?php echo $cntr; ?>_DocNo', 'oneVmsBCashTrnsLnsTable', 'blkCshDocNm');">                                                    
                                                                </td>   
                                                                <td class="lovtd" style="display:none;">
                                                                    <input type="text" class="form-control chqRatesCls rqrdFld blkCshRate" aria-label="..." id="oneVmsBCashTrnsRow<?php echo $cntr; ?>_exchngRate" name="oneVmsBCashTrnsRow<?php echo $cntr; ?>_exchngRate" value="<?php
                                                                    echo number_format((float) $entrdRate, 4);
                                                                    ?>" <?php echo $mkReadOnly; ?> onkeypress="blkTrnsFormTtlFldKeyPress(event, 'oneVmsBCashTrnsRow<?php echo $cntr; ?>_exchngRate', 'oneVmsBCashTrnsLnsTable', 'blkCshRate');" onchange="calcBlkCshRowsTtlVals();">                                                    
                                                                </td>  
                                                                <td class="lovtd">
                                                                    <input type="text" class="form-control chqBlAftaCls" aria-label="..." id="oneVmsBCashTrnsRow<?php echo $cntr; ?>_BalsAftaD" name="oneVmsBCashTrnsRow<?php echo $cntr; ?>_BalsAftaD" value="<?php
                                                                    echo number_format((float) $balsAftaD, 2);
                                                                    ?>" readonly="true" style="width:100%;text-align:right;font-weight:bold;color:blue;">   
                                                                    <input type="hidden" class="form-control" aria-label="..." id="oneVmsBCashTrnsRow<?php echo $cntr; ?>_BalsAfta" name="oneVmsBCashTrnsRow<?php echo $cntr; ?>_BalsAfta" value="<?php
                                                                    echo number_format((float) $balsAfta, 2);
                                                                    ?>" readonly="true">                                                    
                                                                </td>
                                                                <?php if ($trnsType == "WITHDRAWAL") { ?>
                                                                    <td class="lovtd" style="display:none;">
                                                                        <div class="" style="width:100% !important;">
                                                                            <input type="hidden" class="form-control" aria-label="..." id="oneVmsBCashTrnsRow<?php echo $cntr; ?>_chqMandate" name="oneVmsBCashTrnsRow<?php echo $cntr; ?>_chqMandate" value="<?php echo $mdateTxt; ?>" readonly="true" style="width:100% !important;">
                                                                            <label class="btn btn-primary btn-file" onclick="getGnrlAcntSgntryForm1('oneVmsBCashTrnsRow_<?php echo $cntr; ?>');" id="oneVmsBCashTrnsRow<?php echo $cntr; ?>_chqMandateLbl">
                                                                                <span class="">Mandate</span>
                                                                            </label>
                                                                        </div>                                              
                                                                    </td>
                                                                <?php } else { ?>
                                                                    <td class="lovtd" style="display:none;">
                                                                        <div class="" style="width:100% !important;">
                                                                            <input type="hidden" class="form-control" aria-label="..." id="oneVmsBCashTrnsRow<?php echo $cntr; ?>_chqMandate" name="oneVmsBCashTrnsRow<?php echo $cntr; ?>_chqMandate" value="<?php echo $mdateTxt; ?>" readonly="true" style="width:100% !important;">
                                                                            <label class="btn btn-primary btn-file" onclick="getGnrlAcntSgntryForm1('oneVmsBCashTrnsRow_<?php echo $cntr; ?>');" id="oneVmsBCashTrnsRow<?php echo $cntr; ?>_chqMandateLbl">
                                                                                <span class="">Ext. Info</span>
                                                                            </label>
                                                                        </div>                                              
                                                                    </td>
                                                                <?php } ?>
                                                                <td class="lovtd">
                                                                    <div class="input-group">
                                                                        <input type="text" class="form-control" aria-label="..." id="oneVmsBCashTrnsRow<?php echo $cntr; ?>_AcntTitle" value="<?php echo $dstnAccountNm; ?>" style="width:100%;" readonly="true">                                                                                                                                                                                                                  
                                                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getCustAcctsInfo2Form('myFormsModalCA1', 'myFormsModalBodyCA1', 'myFormsModalTitleCA1', 'View Customer Account', 13, 2.1, 0, 'VIEW', 'oneVmsBCashTrnsRow<?php echo $cntr; ?>_DstAcntID', 'custAcctTable', '', 'oneVmsBCashTrnsRow<?php echo $cntr; ?>_DstAcntNo');" data-toggle="tooltip" data-placement="bottom" title = "Latest Account Information">
                                                                            <span class="glyphicon glyphicon-info-sign"></span>
                                                                        </label>
                                                                    </div>                                                                                                                                                    
                                                                </td>
                                                                <td class="lovtd">                                                                                    
                                                                    <?php if ($rqStatus == "Not Submitted" || $rqStatus == "Withdrawn" || $rqStatus == "Rejected") { ?>
                                                                        <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delBulkCashTrnsMain('oneVmsBCashTrnsRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Trns. Line">
                                                                            <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                                        </button>
                                                                    <?php } ?>
                                                                </td>
                                                            </tr>                                                    
                                                        <?php } ?>
                                                        <?php
                                                        if (($rqStatus == "Not Submitted" || $rqStatus == "Withdrawn" || $rqStatus == "Rejected")) {
                                                            $acntChqsRslt = get_One_BlkCashTrnsHdr1($sbmtdBatchHdrID, $vmsOffctStaffPrsID);
                                                            while ($rwChqs = loc_db_fetch_array($acntChqsRslt)) {
                                                                $cntr++;
                                                                $trnsID = (float) $rwChqs[0];
                                                                $trnsType = $rwChqs[3];
                                                                $trnsNo = $rwChqs[20];
                                                                $dstnAccountID = $rwChqs[2];
                                                                $dstnAccount = $rwChqs[50];
                                                                $docType = $rwChqs[16];
                                                                $docNum = $rwChqs[8];
                                                                $entrdCrncyID = $rwChqs[23];
                                                                $entrdCrncyNm = $rwChqs[51];
                                                                $entrdAmnt = $rwChqs[5];
                                                                $entrdRate = $rwChqs[24];
                                                                $mdateTxt = $rwChqs[52];
                                                                $balsAfta = (float) $rwChqs[53];
                                                                $balsAftaD = $balsAfta + ($entrdAmnt * $entrdRate);
                                                                if ($trnsType == "WITHDRAWAL") {
                                                                    $balsAftaD = $balsAfta - ($entrdAmnt * $entrdRate);
                                                                }
                                                                ?>
                                                                <tr id="oneVmsBCashTrnsRow_<?php echo $cntr; ?>">
                                                                    <td class="lovtd"><span class=""><?php echo $cntr; ?></span></td>
                                                                    <td class="lovtd">
                                                                        <select class="form-control" id="oneVmsBCashTrnsRow<?php echo $cntr; ?>_TrnsType" <?php echo $mkReadOnly; ?>  onchange="calcBlkCshRowsTtlVals();">
                                                                            <?php
                                                                            $valslctdArry = array("", ""/* , "" */);
                                                                            $srchInsArrys = array("CREDIT", "DEBIT"/* , "LOAN REPAY" */);
                                                                            $valsArrys = array("DEPOSIT", "WITHDRAWAL"/* , "LOAN_REPAY" */);
                                                                            for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                                                if ($trnsType == $valsArrys[$z]) {
                                                                                    $valslctdArry[$z] = "selected";
                                                                                }
                                                                                ?>
                                                                                <option value="<?php echo $valsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                                                            <?php } ?>
                                                                        </select>
                                                                    </td>
                                                                    <td class="lovtd">
                                                                        <?php if ($canEdtTrns === true) { ?>                                      
                                                                            <div class="input-group">
                                                                                <input type="text" class="form-control blkCshAcnt" aria-label="..." id="oneVmsBCashTrnsRow<?php echo $cntr; ?>_DstAcntNo" value="<?php echo $dstnAccount; ?>" style="width:100%;" onkeypress="blkTrnsFormTtlFldKeyPress(event, 'oneVmsBCashTrnsRow<?php echo $cntr; ?>_DstAcntNo', 'oneVmsBCashTrnsLnsTable', 'blkCshAcnt');" onblur="afterBulkAcntSlctn('oneVmsBCashTrnsRow_<?php echo $cntr; ?>');" onfocusout="afterBulkAcntSlctn('oneVmsBCashTrnsRow_<?php echo $cntr; ?>');">
                                                                                <input type="hidden" id="oneVmsBCashTrnsRow<?php echo $cntr; ?>_DstAcntID" value="<?php echo $dstnAccountID; ?>">                                            
                                                                                <input type="hidden" id="oneVmsBCashTrnsRow<?php echo $cntr; ?>_LineID" value="<?php echo $trnsID; ?>">                                            
                                                                                <input type="hidden" id="oneVmsBCashTrnsRow<?php echo $cntr; ?>_AcRawTxt1" value="<?php echo ''; ?>">
                                                                                <?php if ($rqStatus != "Authorized" && $rqStatus != "Cancelled") { ?>
                                                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'MCF All Bank Accounts', 'allOtherInputOrgID', 'vmsBrnchLocID', '', 'radio', true, '', 'oneVmsBCashTrnsRow<?php echo $cntr; ?>_DstAcntID', 'oneVmsBCashTrnsRow<?php echo $cntr; ?>_AcRawTxt1', 'clear', 1, '', function () {
                                                                                                                    $('#oneVmsBCashTrnsRow<?php echo $cntr; ?>_DstAcntNo').val($('#oneVmsBCashTrnsRow<?php echo $cntr; ?>_AcRawTxt1').val().split(' [')[0]);
                                                                                                                    afterBulkAcntSlctn('oneVmsBCashTrnsRow_<?php echo $cntr; ?>');
                                                                                                                });">
                                                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                                                    </label>
                                                                                <?php } ?>
                                                                            </div>
                                                                        <?php } else { ?>
                                                                            <span><?php echo $dstnAccount; ?></span>
                                                                        <?php } ?> 
                                                                    </td> 
                                                                    <td class="lovtd">
                                                                        <input type="text" class="form-control chqValCls rqrdFld blkCshAmnt" aria-label="..." id="oneVmsBCashTrnsRow<?php echo $cntr; ?>_chqVal" name="oneVmsBCashTrnsRow<?php echo $cntr; ?>_chqVal" value="<?php
                                                                        echo number_format((float) $entrdAmnt, 2);
                                                                        ?>" style="width:100% !important;text-align: right;" <?php echo $mkReadOnly; ?> onkeypress="blkTrnsFormTtlFldKeyPress(event, 'oneVmsBCashTrnsRow<?php echo $cntr; ?>_chqVal', 'oneVmsBCashTrnsLnsTable', 'blkCshAmnt');" onchange="calcBlkCshRowsTtlVals();"/>                                                    
                                                                    </td>                                                   
                                                                    <td class="lovtd">
                                                                        <div class="" style="width:100% !important;">
                                                                            <input type="hidden" class="form-control" aria-label="..." id="oneVmsBCashTrnsRow<?php echo $cntr; ?>_TrnsCurNm" name="oneVmsBCashTrnsRow<?php echo $cntr; ?>_TrnsCurNm" value="<?php echo $entrdCrncyNm; ?>" readonly="true" style="width:100% !important;">
                                                                            <label class="btn btn-primary btn-file" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Currencies', '', '', '', 'radio', true, '', 'oneVmsBCashTrnsRow<?php echo $cntr; ?>_TrnsCurNm', '', 'clear', 1, '', function () {
                                                                                                    $('#oneVmsBCashTrnsRow<?php echo $cntr; ?>_TrnsCurNm1').html($('#oneVmsBCashTrnsRow<?php echo $cntr; ?>_TrnsCurNm').val());
                                                                                                });">
                                                                                <span class="" id="oneVmsBCashTrnsRow<?php echo $cntr; ?>_TrnsCurNm1"><?php echo $entrdCrncyNm; ?></span>
                                                                            </label>
                                                                        </div>                                              
                                                                    </td>
                                                                    <td class="lovtd">
                                                                        <select class="form-control" id="oneVmsBCashTrnsRow<?php echo $cntr; ?>_DocType" <?php echo $mkReadOnly; ?> onchange="blkTrnsDocTypeChng('oneVmsBCashTrnsRow_<?php echo $cntr; ?>');">
                                                                            <?php
                                                                            $brghtStr = "";
                                                                            $isDynmyc = FALSE;
                                                                            $titleRslt = getLovValues("%", "Both", 0, 100, $brghtStr, getLovID("All MCF Document Types"), $isDynmyc, -1, "", "");
                                                                            while ($titleRow = loc_db_fetch_array($titleRslt)) {
                                                                                $selectedTxt = "";
                                                                                if ($titleRow[0] == $docType) {
                                                                                    $selectedTxt = "selected";
                                                                                }
                                                                                ?>
                                                                                <option value="<?php echo $titleRow[0]; ?>" <?php echo $selectedTxt; ?>><?php echo $titleRow[0]; ?></option>
                                                                                <?php
                                                                            }
                                                                            ?>
                                                                        </select>
                                                                    </td>
                                                                    <td class="lovtd">
                                                                        <input type="text" class="form-control rqrdFld blkCshDocNm" aria-label="..." id="oneVmsBCashTrnsRow<?php echo $cntr; ?>_DocNo" name="oneVmsBCashTrnsRow<?php echo $cntr; ?>_DocNo" value="<?php echo $docNum; ?>" <?php echo $mkReadOnly; ?> onkeypress="blkTrnsFormTtlFldKeyPress(event, 'oneVmsBCashTrnsRow<?php echo $cntr; ?>_DocNo', 'oneVmsBCashTrnsLnsTable', 'blkCshDocNm');">                                                    
                                                                    </td>   
                                                                    <td class="lovtd">
                                                                        <input type="text" class="form-control chqRatesCls rqrdFld blkCshRate" aria-label="..." id="oneVmsBCashTrnsRow<?php echo $cntr; ?>_exchngRate" name="oneVmsBCashTrnsRow<?php echo $cntr; ?>_exchngRate" value="<?php
                                                                        echo number_format((float) $entrdRate, 4);
                                                                        ?>" <?php echo $mkReadOnly; ?> onkeypress="blkTrnsFormTtlFldKeyPress(event, 'oneVmsBCashTrnsRow<?php echo $cntr; ?>_exchngRate', 'oneVmsBCashTrnsLnsTable', 'blkCshRate');" onchange="calcBlkCshRowsTtlVals();">                                                    
                                                                    </td>  
                                                                    <td class="lovtd">
                                                                        <input type="text" class="form-control chqBlAftaCls" aria-label="..." id="oneVmsBCashTrnsRow<?php echo $cntr; ?>_BalsAftaD" name="oneVmsBCashTrnsRow<?php echo $cntr; ?>_BalsAftaD" value="<?php
                                                                        echo number_format((float) $balsAftaD, 2);
                                                                        ?>" readonly="true" style="width:100%;text-align:right;font-weight:bold;color:blue;">     
                                                                        <input type="hidden" class="form-control" aria-label="..." id="oneVmsBCashTrnsRow<?php echo $cntr; ?>_BalsAfta" name="oneVmsBCashTrnsRow<?php echo $cntr; ?>_BalsAfta" value="<?php
                                                                        echo number_format((float) $balsAfta, 2);
                                                                        ?>" readonly="true">                                                   
                                                                    </td>
                                                                    <?php if ($trnsType == "WITHDRAWAL") { ?>
                                                                        <td class="lovtd">
                                                                            <div class="" style="width:100% !important;">
                                                                                <input type="hidden" class="form-control" aria-label="..." id="oneVmsBCashTrnsRow<?php echo $cntr; ?>_chqMandate" name="oneVmsBCashTrnsRow<?php echo $cntr; ?>_chqMandate" value="<?php echo $mdateTxt; ?>" readonly="true" style="width:100% !important;">
                                                                                <label class="btn btn-primary btn-file" onclick="getGnrlAcntSgntryForm('oneVmsBCashTrnsRow_<?php echo $cntr; ?>');" id="oneVmsBCashTrnsRow<?php echo $cntr; ?>_chqMandateLbl">
                                                                                    <span class="">Mandate</span>
                                                                                </label>
                                                                            </div>                                              
                                                                        </td>
                                                                    <?php } else { ?>
                                                                        <td class="lovtd">
                                                                            <div class="" style="width:100% !important;">
                                                                                <input type="hidden" class="form-control" aria-label="..." id="oneVmsBCashTrnsRow<?php echo $cntr; ?>_chqMandate" name="oneVmsBCashTrnsRow<?php echo $cntr; ?>_chqMandate" value="<?php echo $mdateTxt; ?>" readonly="true" style="width:100% !important;">
                                                                                <label class="btn btn-primary btn-file" onclick="getGnrlAcntSgntryForm('oneVmsBCashTrnsRow_<?php echo $cntr; ?>');" id="oneVmsBCashTrnsRow<?php echo $cntr; ?>_chqMandateLbl">
                                                                                    <span class="">Ext. Info</span>
                                                                                </label>
                                                                            </div>                                              
                                                                        </td>
                                                                    <?php } ?>
                                                                    <td class="lovtd">                                                                                    
                                                                        <?php if ($rqStatus == "Not Submitted" || $rqStatus == "Withdrawn" || $rqStatus == "Rejected") { ?>
                                                                            <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delBulkCashTrnsMain('oneVmsBCashTrnsRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Trns. Line">
                                                                                <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                                            </button>
                                                                        <?php } ?>
                                                                    </td>
                                                                </tr>  
                                                                <?php
                                                            }
                                                        }
                                                        ?>
                                                    </tbody>
                                                </table>   
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>                                    
                            <div id="blkTrnsCheques" class="tab-pane fade hideNotice" style="border:none !important;padding:0px !important;<?php echo $shwNonCashTab; ?>">
                                <div class="row">
                                    <div class="col-md-12">
                                        <?php
                                        $dsplyNwLine = "";
                                        $acctCrncy = $crncyIDNm;
                                        ?>
                                        <div class="row">
                                            <div class="col-md-12" id="oneVmsTrnsLnsTblSctn">
                                                <table class="table table-striped table-bordered table-responsive" id="oneVmsTrnsLnsTable" cellspacing="0" width="100%" style="width:100%;min-width: 900px !important;">
                                                    <thead>
                                                        <tr>
                                                            <th>No.</th>
                                                            <th>Account Number</th>
                                                            <th style="display:none;">Type</th>
                                                            <th class="extnl">Bank</th>
                                                            <th class="extnl">Branch</th>
                                                            <th>CHQ. No.</th>
                                                            <th>CHQ. Date</th>
                                                            <th>CUR.</th>
                                                            <th>CHQ. Amount</th>
                                                            <th>Rate</th>
                                                            <!--<th>Value Date</th>-->
                                                            <th>Mandate</th>
                                                            <th>&nbsp;</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        $acntChqsRslt = getBulkAccountTrnsChqs($sbmtdBatchHdrID);
                                                        $cntr = 0;
                                                        while ($rwChqs = loc_db_fetch_array($acntChqsRslt)) {
                                                            $cntr++;
                                                            $chqType = $rwChqs[9];
                                                            $inptType = "text";
                                                            $dsplyLable = "";
                                                            $dsplySpan = "display:none;";
                                                            $dstnAccountID = $rwChqs[16];
                                                            $dstnAccount = $rwChqs[24];
                                                            if ($chqType != "External") {
                                                                $inptType = "hidden";
                                                                $dsplyLable = "display:none;";
                                                                $dsplySpan = "";
                                                            }
                                                            ?>
                                                            <tr id="oneVmsTrnsRow_<?php echo $cntr; ?>">
                                                                <td class="lovtd"><span class=""><?php echo $cntr; ?></span></td>
                                                                <td class="lovtd">
                                                                    <?php if ($canEdtOrdr === true) { ?>                                      
                                                                        <div class="input-group">
                                                                            <input type="text" class="form-control blkCshAcnt" aria-label="..." id="oneVmsTrnsRow<?php echo $cntr; ?>_DstAcntNo" value="<?php echo $dstnAccount; ?>" style="width:100%;" onkeypress="blkTrnsFormTtlFldKeyPress(event, 'oneVmsTrnsRow<?php echo $cntr; ?>_DstAcntNo', 'oneVmsTrnsLnsTable', 'blkCshAcnt');">
                                                                            <input type="hidden" id="oneVmsTrnsRow<?php echo $cntr; ?>_DstAcntID" value="<?php echo $dstnAccountID; ?>">                                            
                                                                            <input type="hidden" id="oneVmsTrnsRow<?php echo $cntr; ?>_AcRawTxt1" value="<?php echo ''; ?>">
                                                                            <?php if ($rqStatus != "Authorized" && $rqStatus != "Cancelled") { ?>
                                                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'MCF All Bank Accounts', 'allOtherInputOrgID', 'vmsBrnchLocID', '', 'radio', true, '', 'oneVmsTrnsRow<?php echo $cntr; ?>_DstAcntID', 'oneVmsTrnsRow<?php echo $cntr; ?>_AcRawTxt1', 'clear', 1, '', function () {
                                                                                                            $('#oneVmsTrnsRow<?php echo $cntr; ?>_DstAcntNo').val($('#oneVmsTrnsRow<?php echo $cntr; ?>_AcRawTxt1').val().split(' [')[0]);
                                                                                                        });">
                                                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                                                </label>
                                                                            <?php } ?>
                                                                        </div>
                                                                    <?php } else { ?>
                                                                        <span><?php echo $dstnAccount; ?></span>
                                                                    <?php } ?> 
                                                                </td>
                                                                <td class="lovtd" style="display:none;">
                                                                    <div class="input-group" style="width:100% !important;">
                                                                        <input type="text" class="form-control" aria-label="..." id="oneVmsTrnsRow<?php echo $cntr; ?>_chqType" name="oneVmsTrnsRow<?php echo $cntr; ?>_chqType" value="<?php echo $rwChqs[9]; ?>" readonly="true" style="width:100% !important;">
                                                                        <input type="hidden" class="form-control" aria-label="..." id="oneVmsTrnsRow<?php echo $cntr; ?>_chqTypeID" value="<?php echo $rwChqs[11]; ?>" style="width:100% !important;">                                              
                                                                        <input type="hidden" class="form-control" aria-label="..." id="oneVmsTrnsRow<?php echo $cntr; ?>_TrnsLnID" value="<?php echo $rwChqs[0]; ?>" style="width:100% !important;">                                                                   
                                                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'MCF Deposit Cheque Types', '', '', '', 'radio', true, '', 'oneVmsTrnsRow<?php echo $cntr; ?>_chqTypeID', 'oneVmsTrnsRow<?php echo $cntr; ?>_chqType', 'clear', 1, '', function () {
                                                                                            //afterVMSItemSlctn('oneVmsTrnsRow_<?php echo $cntr; ?>');
                                                                                            var sltdVal = $('#oneVmsTrnsRow<?php echo $cntr; ?>_chqType').val();
                                                                                            if (sltdVal == 'In-House') {
                                                                                                $('.extnl').css('display', 'none');
                                                                                                $('#oneVmsTrnsRow<?php echo $cntr; ?>_bnkNm').val('');
                                                                                                $('#oneVmsTrnsRow<?php echo $cntr; ?>_bnkID').val('');
                                                                                                $('oneVmsTrnsRow<?php echo $cntr; ?>_brnchNm').val('');
                                                                                                $('oneVmsTrnsRow<?php echo $cntr; ?>_brnchiD').val('');
                                                                                            } else {
                                                                                                $('.extnl').css('display', 'table-cell');
                                                                                            }
                                                                                        });">
                                                                            <span class="glyphicon glyphicon-th-list"></span>
                                                                        </label>
                                                                    </div> 
                                                                </td>
                                                                <td class="lovtd extnl">
                                                                    <div class="input-group" style="width:100% !important;">
                                                                        <input type="<?php echo $inptType; ?>" class="form-control" aria-label="..." id="oneVmsTrnsRow<?php echo $cntr; ?>_bnkNm" name="oneVmsTrnsRow<?php echo $cntr; ?>_bnkNm" value="<?php echo $rwChqs[3]; ?>" readonly="true" style="width:100% !important;">
                                                                        <input type="hidden" class="form-control" aria-label="..." id="oneVmsTrnsRow<?php echo $cntr; ?>_bnkID" value="<?php echo $rwChqs[2]; ?>" style="width:100% !important;">                                               
                                                                        <label style="<?php echo $dsplyLable; ?>" class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'MCF All Banks', 'gnrlOrgID', '', '', 'radio', true, '', 'oneVmsTrnsRow<?php echo $cntr; ?>_bnkID', 'oneVmsTrnsRow<?php echo $cntr; ?>_bnkNm', 'clear', 1, '', function () {
                                                                                            //afterVMSItemSlctn('oneVmsTrnsRow_<?php echo $cntr; ?>');
                                                                                        });">
                                                                            <span class="glyphicon glyphicon-th-list"></span>
                                                                        </label>
                                                                    </div> 
                                                                    <span style="<?php echo $dsplySpan; ?>padding-left: 4px;"><?php echo $chqType; ?></span>
                                                                </td>
                                                                <td class="lovtd extnl">
                                                                    <div class="input-group" style="width:100% !important;">
                                                                        <input type="<?php echo $inptType; ?>" class="form-control" aria-label="..." id="oneVmsTrnsRow<?php echo $cntr; ?>_brnchNm" name="oneVmsTrnsRow<?php echo $cntr; ?>_brnchNm" value="<?php echo $rwChqs[5]; ?>" readonly="true" style="width:100% !important;">
                                                                        <input type="hidden" class="form-control" aria-label="..." id="oneVmsTrnsRow<?php echo $cntr; ?>_brnchID" value="<?php echo $rwChqs[4]; ?>" style="width:100% !important;">                                              
                                                                        <label style="<?php echo $dsplyLable; ?>" class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Bank Branches MCF', 'oneVmsTrnsRow<?php echo $cntr; ?>_bnkID', '', '', 'radio', true, '', 'oneVmsTrnsRow<?php echo $cntr; ?>_brnchID', 'oneVmsTrnsRow<?php echo $cntr; ?>_brnchNm', 'clear', 1, '');">
                                                                            <span class="glyphicon glyphicon-th-list"></span>
                                                                        </label>
                                                                    </div> 
                                                                    <span style="<?php echo $dsplySpan; ?>padding-left: 4px;"><?php echo $chqType; ?></span> 
                                                                </td> 
                                                                <td class="lovtd">
                                                                    <input type="text" class="form-control rqrdFld blkCshDocNm" aria-label="..." id="oneVmsTrnsRow<?php echo $cntr; ?>_chqNo" name="oneVmsTrnsRow<?php echo $cntr; ?>_chqNo" value="<?php echo $rwChqs[6]; ?>" <?php echo $mkReadOnly; ?> onkeypress="blkTrnsFormTtlFldKeyPress(event, 'oneVmsTrnsRow<?php echo $cntr; ?>_chqNo', 'oneVmsTrnsLnsTable', 'blkCshDocNm');">                                                    
                                                                </td>
                                                                <td class="lovtd">  
                                                                    <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                                                        <input type="text" class="form-control" size="16" aria-label="..." id="oneVmsTrnsRow<?php echo $cntr; ?>_chqDte" name="oneVmsTrnsRow<?php echo $cntr; ?>_chqDte" value="<?php echo $rwChqs[10]; ?>" readonly="true">                                                                    
                                                                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                                    </div>
                                                                </td>                                                        
                                                                <td class="lovtd">
                                                                    <div class="" style="width:100% !important;">
                                                                        <input type="hidden" class="form-control" aria-label="..." id="oneVmsTrnsRow<?php echo $cntr; ?>_chqCurNm" name="oneVmsTrnsRow<?php echo $cntr; ?>_chqCurNm" value="<?php echo $rwChqs[14]; ?>" readonly="true" style="width:100% !important;">
                                                                        <label class="btn btn-primary btn-file" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Currencies', '', '', '', 'radio', true, '', 'oneVmsTrnsRow<?php echo $cntr; ?>_chqCurNm', '', 'clear', 1, '', function () {
                                                                                            $('#oneVmsTrnsRow<?php echo $cntr; ?>_chqCurNm1').html($('#oneVmsTrnsRow<?php echo $cntr; ?>_chqCurNm').val());
                                                                                        });">
                                                                            <span class="" id="oneVmsTrnsRow<?php echo $cntr; ?>_chqCurNm1"><?php echo $rwChqs[14]; ?></span>
                                                                        </label>
                                                                    </div>                                              
                                                                </td>
                                                                <td class="lovtd">
                                                                    <input type="text" class="form-control chqValCls rqrdFld" aria-label="..." id="oneVmsTrnsRow<?php echo $cntr; ?>_chqVal" name="oneVmsTrnsRow<?php echo $cntr; ?>_chqVal" value="<?php
                                                                    echo number_format((float) $rwChqs[7], 2);
                                                                    ?>" style="width:100% !important;text-align: right;" <?php echo $mkReadOnly; ?> onkeypress="blkTrnsFormTtlFldKeyPress(event, 'oneVmsTrnsRow<?php echo $cntr; ?>_chqVal', 'oneVmsTrnsLnsTable', 'chqValCls');"/>                                                    
                                                                </td>
                                                                <td class="lovtd">
                                                                    <input type="text" class="form-control chqRatesCls rqrdFld" aria-label="..." id="oneVmsTrnsRow<?php echo $cntr; ?>_exchngRate" name="oneVmsTrnsRow<?php echo $cntr; ?>_exchngRate" value="<?php
                                                                    echo number_format((float) $rwChqs[15], 4);
                                                                    ?>" <?php echo $mkReadOnly; ?> onkeypress="blkTrnsFormTtlFldKeyPress(event, 'oneVmsTrnsRow<?php echo $cntr; ?>_exchngRate', 'oneVmsTrnsLnsTable', 'chqRatesCls');">                                                    
                                                                </td> 
                                                                <?php if ($chqType == "In-House") { ?>
                                                                    <td class="lovtd">
                                                                        <div class="" style="width:100% !important;">
                                                                            <input type="hidden" class="form-control" aria-label="..." id="oneVmsTrnsRow<?php echo $cntr; ?>_chqMandate" name="oneVmsTrnsRow<?php echo $cntr; ?>_chqMandate" value="<?php echo $rwChqs[23] ?>" readonly="true" style="width:100% !important;">
                                                                            <label class="btn btn-primary btn-file" onclick="getGnrlAcntSgntryForm('oneVmsTrnsRow_<?php echo $cntr; ?>');" id="oneVmsTrnsRow<?php echo $cntr; ?>_chqMandateLbl">
                                                                                <span class="">Mandate</span>
                                                                            </label>
                                                                        </div>                                              
                                                                    </td>
                                                                <?php } else { ?>
                                                                    <td class="lovtd">
                                                                        <div class="" style="width:100% !important;">
                                                                            <input type="hidden" class="form-control" aria-label="..." id="oneVmsTrnsRow<?php echo $cntr; ?>_chqMandate" name="oneVmsTrnsRow<?php echo $cntr; ?>_chqMandate" value="<?php echo $rwChqs[23] ?>" readonly="true" style="width:100% !important;">
                                                                            <label class="btn btn-primary btn-file" onclick="getGnrlAcntSgntryForm('oneVmsTrnsRow_<?php echo $cntr; ?>');" id="oneVmsTrnsRow<?php echo $cntr; ?>_chqMandateLbl">
                                                                                <span class="">Ext. Info</span>
                                                                            </label>
                                                                        </div>                                              
                                                                    </td>
                                                                <?php } ?>
                                                                <td class="lovtd">
                                                                    <?php if ($trnsStatus == "Not Submitted" || $trnsStatus == "Withdrawn" || $trnsStatus == "Rejected") { ?>
                                                                        <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delBulkTrnsChqLine('oneVmsTrnsRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Trns. Line">
                                                                            <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                                        </button>
                                                                    <?php } ?>
                                                                </td>
                                                            </tr>                                                    
                                                        <?php } ?>
                                                    </tbody>
                                                </table>   
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>                                   
                            <div id="blkTrnsTllrTill" class="tab-pane fade hideNotice" style="border:none !important;padding:0px !important;<?php echo $shwCashTab; ?>">
                                <div class="row">
                                    <div class="col-md-12">
                                        <form class="form-horizontal" id="cashDenominationsForm" style="padding:1px 5px 5px 5px;">
                                            <input type="hidden" class="form-control" aria-label="..." id="cashDenominationsID" value="">
                                            <input type="hidden" class="form-control" aria-label="..." id="cashDenominationsTtl" value="">
                                            <input type="hidden" class="form-control" aria-label="..." id="cashDenominationsTtlRaw" value="">
                                            <input type="hidden" class="form-control" aria-label="..." id="cashDenominationsTtlRaw1" value="">
                                            <input type="hidden" class="form-control" aria-label="..." id="cashDenominationsTtlFmtd" value="">
                                            <div class="row">
                                                <div class="col-md-12"> 
                                                    <fieldset style="padding: 1px !important;">
                                                        <span style="color:red;font-style: italic;font-weight:bold;font-family: times;">&nbsp;NB: Use Negative QTYs or Amounts to record refunds!</span>
                                                        <table id="cashBreakdownTblEDT" class="table table-striped table-bordered table-responsive" cellspacing="0" width="100%" style="width:100%;">
                                                            <thead>
                                                                <tr>
                                                                    <th style="max-width:100px;width:100px;">Type</th>
                                                                    <th style="max-width:100px;width:100px;">Denom.</th>
                                                                    <th style="max-width:100px;width:100px;">Pieces</th>
                                                                    <th>Total Amount</th>
                                                                    <th style="display:none;">Value</th>
                                                                    <th style="display:none;">Unit Value</th>                                           
                                                                    <th style="display:none;">...</th>
                                                                    <th style="display:none;">...</th>
                                                                    <th style="display:none;">...</th>
                                                                    <th>Exchange Rate</th>
                                                                    <th style="<?php echo $dsplyBalance; ?>">Running Balance</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php
                                                                $dateStr = getDB_Date_time();
                                                                //check existence of account transaction
                                                                $exists = checkExstncOfBulkCashBrkdwn($sbmtdBatchHdrID, $orgID);
                                                                /* if ($exists > 0) {
                                                                  $delSQL = "DELETE FROM mcf.mcf_account_trns_cash_analysis WHERE bulk_trns_hdr_id=" . $sbmtdBatchHdrID .
                                                                  " and denomination_id NOT IN (SELECT crncy_denom_id FROM mcf.mcf_currency_denominations WHERE crncy_id = $mcfCrncyID)";
                                                                  execUpdtInsSQL($delSQL);
                                                                  $exists = checkExstncOfBulkCashBrkdwn($sbmtdBatchHdrID, $orgID);
                                                                  } */
                                                                if ($exists <= 0) {
                                                                    createBulkCashBreakdownInit($sbmtdBatchHdrID, $mcfCrncyID, $usedVltID, $usedCageID, $dfltItemState);
                                                                }
                                                                $mcfAccntCrncyID = getAccountLovCrncyID($pAcctID);
                                                                $dateStr1 = getFrmtdDB_Date_time();
                                                                $exchangeRate1 = round(get_LtstBNKExchRate($mcfPymtCrncyID, $mcfAccntCrncyID, $dateStr1), 15);
                                                                ?>
                                                            <input class="form-control" id="initAcctTrnsId" placeholder="Init Account Trns ID" type = "hidden" placeholder="" value="<?php echo $sbmtdBatchHdrID; ?>"/>
                                                            <?php
                                                            $result1 = get_BulkCashAnalysis($sbmtdBatchHdrID, $mcfCrncyID);
                                                            $cntr = 0;
                                                            $ttlRows = loc_db_num_rows($result1);
                                                            $ttlAmount = 0;
                                                            $ttlQty = 0;
                                                            while ($row1 = loc_db_fetch_array($result1)) {
                                                                $cntr++;
                                                                $ttlVal = 0;
                                                                if ($row1[1] == 'Coin') {
                                                                    if ((float) $row1[7] == 0) {
                                                                        $ttlVal = "";
                                                                    } else {
                                                                        $ttlVal = number_format($row1[7], 2);
                                                                        $ttlAmount = $ttlAmount + (float) $row1[7];
                                                                    }
                                                                } else {
                                                                    if ((float) $row1[7] == 0) {
                                                                        $ttlVal = "";
                                                                    } else {
                                                                        $ttlVal = number_format($row1[7]);
                                                                        $ttlAmount = $ttlAmount + (float) $row1[7];
                                                                    }
                                                                }
                                                                if ($row1[11] != 1 && $row1[11] != 0) {
                                                                    $exchangeRate = number_format($row1[11], 4);
                                                                } else {
                                                                    $exchangeRate = number_format($exchangeRate1, 6);
                                                                }
                                                                $runningBalance = (float) $row1[12];
                                                                $ttlQty += (float) $row1[6];
                                                                if ($ttlVal != "" && ((int) $row1[9]) > 0) {
                                                                    $usedVltID = (int) $row1[8];
                                                                    $usedCageID = (int) $row1[9];
                                                                    $itemState = $row1[10];
                                                                    $usedCageNm = getGnrlRecNm("inv.inv_shelf", "line_id", "shelve_name", $usedCageID);
                                                                    $usedVltNm = getGnrlRecNm("inv.inv_itm_subinventories", "subinv_id", "subinv_name", $usedVltID);
                                                                }
                                                                ?>
                                                                <tr id="cashBreakdownRow_<?php echo $cntr; ?>">
                                                                    <td class="lovtd"><?php echo $row1[1]; ?></td>
                                                                    <td class="lovtd"><?php echo $row1[2]; ?></td>
                                                                    <td class="lovtd"><input class="cbQty form-control" onchange="calcCashBreadownRowVal('cashBreakdownRow_<?php echo $cntr; ?>', 'cbQty');" onfocus="initCashBreakdownForm(<?php echo $cntr; ?>, <?php echo $ttlRows; ?>);" id="cashBreakdownRow<?php echo $cntr; ?>_denomQty" type = "number" min="0" placeholder="Quantity" value="<?php echo $row1[6]; ?>" style="text-align: right;width:100% !important;" onkeypress="blkTrnsFormTtlFldKeyPress(event, 'cashBreakdownRow<?php echo $cntr; ?>_denomQty', 'cashBreakdownTblEDT', 'cbQty');"/></td>
                                                                    <td class="lovtd"><input class="cbTTlAmnt form-control" onchange="calcCashBreadownRowVal('cashBreakdownRow_<?php echo $cntr; ?>', 'cbTTlAmnt');" onfocus="initCashBreakdownForm(<?php echo $cntr; ?>, <?php echo $ttlRows; ?>);" id="cashBreakdownRow<?php echo $cntr; ?>_ttlAmnt" type = "text" placeholder="Total Amount" value="<?php echo $ttlVal; ?>" style="text-align: right;width:100% !important;" onkeypress="blkTrnsFormTtlFldKeyPress(event, 'cashBreakdownRow<?php echo $cntr; ?>_ttlAmnt', 'cashBreakdownTblEDT', 'cbTTlAmnt');"/></td>                                                    
                                                                    <td class="lovtd" style="display:none;"><?php echo $ttlVal; ?></td>
                                                                    <td id="cashBreakdownRow<?php echo $cntr; ?>_value" class="lovtd" style="display:none;"><?php echo $row1[3]; ?></td>
                                                                    <td id="cashBreakdownRow<?php echo $cntr; ?>_denomID" class="lovtd" style="display:none;"><?php echo $row1[0]; ?></td>
                                                                    <td id="cashBreakdownRow<?php echo $cntr; ?>_cashAnalysisID" class="lovtd" style="display:none;"><?php echo $row1[5]; ?></td>
                                                                    <td id="cashBreakdownRow<?php echo $cntr; ?>_ttlAmntRaw" class="lovtd" style="display:none;"><?php echo $row1[7]; ?></td>
                                                                    <td class="lovtd"><input class="cbExchngRate form-control" onchange="calcCashBreadownRowVal('cashBreakdownRow_<?php echo $cntr; ?>', 'cbExchngRate');" onfocus="initCashBreakdownForm(<?php echo $cntr; ?>, <?php echo $ttlRows; ?>);" id="cashBreakdownRow<?php echo $cntr; ?>_ExchngRate" type = "number" placeholder="Exchange Rate" value="<?php echo $exchangeRate; ?>" style="text-align: right;width:100% !important;" onkeypress="blkTrnsFormTtlFldKeyPress(event, 'cashBreakdownRow<?php echo $cntr; ?>_ExchngRate', 'cashBreakdownTblEDT', 'cbExchngRate');"/></td>  
                                                                    <td class="lovtd" style="<?php echo $dsplyBalance; ?>">
                                                                        <input type="text" class="form-control cbRnngBal" aria-label="..." id="cashBreakdownRow<?php echo $cntr; ?>_RnngBal" name="cashBreakdownRow<?php echo $cntr; ?>_RnngBal" value="<?php
                                                                        echo number_format($runningBalance, 2);
                                                                        ?>" style="width:100% !important;text-align: right;font-weight:bold;color:blue;" readonly="true"> 
                                                                               <?php
                                                                               if (strpos($capturedItemIDs, ";" . $row1[0] . ";") === FALSE) {
                                                                                   $capturedItemIDs = ";" . $row1[0] . ";";
                                                                                   $extrInputFlds .= "<input type=\"hidden\" id=\"cashBreakdownDenom_" . $row1[0] . "\" name=\"cashBreakdownDenom_" . $row1[0] . "\" value=\"" . number_format($runningBalance, 2) . "\" readonly=\"true\">";
                                                                               }
                                                                               ?>
                                                                    </td>
                                                                </tr>
                                                            <?php } ?>
                                                            </tbody>
                                                            <tfoot>                                                            
                                                                <tr>
                                                                    <th>&nbsp;</th>
                                                                    <th>TOTALS:</th>
                                                                    <th style="text-align: right;">
                                                                        <?php
                                                                        echo "<span style=\"color:blue;\" id=\"mcfCptrdCbQtyTtlBtn\">" . number_format($ttlQty, 0, '.', ',') . "</span>";
                                                                        ?>
                                                                        <input type="hidden" id="myCptrdQtyTtlVal" value="<?php echo $ttlQty; ?>">
                                                                    </th>
                                                                    <th style="text-align: right;">
                                                                        <?php
                                                                        echo "<span style=\"color:red;font-weight:bold;font-size:14px;\" id=\"mcfCptrdCbValsTtlBtn\">" . number_format($ttlAmount, 2, '.', ',') . "</span>";
                                                                        ?>
                                                                        <input type="hidden" id="myCptrdUmValsTtlVal" value="<?php echo $ttlAmount; ?>">
                                                                    </th>
                                                                    <th style="display:none;">&nbsp;</th>
                                                                    <th style="display:none;">&nbsp;</th>                                           
                                                                    <th style="display:none;">&nbsp;</th>
                                                                    <th style="display:none;">&nbsp;</th>
                                                                    <th style="display:none;">&nbsp;</th>
                                                                    <th>&nbsp;</th>
                                                                    <th style="<?php echo $dsplyBalance; ?>">&nbsp;</th>
                                                                </tr>
                                                            </tfoot>
                                                        </table>
                                                        <div id="rnngBalsMcfHiddenFields">
                                                            <?php echo $extrInputFlds; ?>
                                                        </div> 
                                                    </fieldset>
                                                </div>
                                            </div>
                                        </form>

                                    </div>
                                </div>
                            </div>
                            <div id="blkTrnsMisc" class="tab-pane fadein hideNotice" style="border:none !important;padding:0px !important;<?php echo $shwNonCashTab; ?>">                                            
                                <div class="row">
                                    <div class="col-md-12">
                                        <table class="table table-striped table-bordered table-responsive" id="oneTrnsfrMiscTrnsTable" cellspacing="0" width="100%" style="width:100%;min-width: 300px !important;">
                                            <thead>
                                                <tr>
                                                    <th style="max-width: 45px;width: 45px;">No.</th>
                                                    <th style="max-width: 100px;width: 100px;">Transaction Type</th>
                                                    <th style="max-width: 200px;width: 200px;">In-House Account No.</th>
                                                    <th style="text-align:right;max-width: 100px;width: 100px;">Amount</th>
                                                    <th style="max-width: 45px;width: 45px;display:none;">CUR.</th>
                                                    <th style="max-width: 200px;width: 200px;">Narration</th>
                                                    <th style="max-width: 200px;width: 200px;">GL Account</th>
                                                    <th style="text-align:right;max-width: 100px;width: 100px;display:none;">Rate</th>
                                                    <th style="max-width: 200px;width: 200px;">In-House Account Title</th>
                                                    <th style="max-width: 30px;width: 30px;">&nbsp;</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $rslt = get_OneBulkMiscTrns($sbmtdBatchHdrID);
                                                $cntrUsr = 0;
                                                while ($rwLn = loc_db_fetch_array($rslt)) {
                                                    $cntrUsr++;
                                                    $ordrMiscID = (float) $rwLn[0];
                                                    $rmrkDesc = $rwLn[7];
                                                    $trnsType = $rwLn[6];
                                                    $dstnAccountNm = $rwLn[4];
                                                    $dstnAccount = $rwLn[3];
                                                    $dstnAccountID = (float) $rwLn[2];
                                                    $dstnAmnt = (float) $rwLn[5];
                                                    $dstCrncyID = (float) $rwLn[8];
                                                    $entrdCrncyNm = $rwLn[10];
                                                    $miscOrdrGlAcntID = (float) $rwLn[11];
                                                    $miscOrdrGlAcntNm = $rwLn[12];
                                                    $dstnRate = (float) $rwLn[15];
                                                    ?>
                                                    <tr id="oneTrnsfrMiscTrnsRow_<?php echo $cntrUsr; ?>">                                    
                                                        <td class="lovtd"><span><?php echo ($cntrUsr); ?></span></td>
                                                        <td class="lovtd">
                                                            <input type="hidden" class="form-control" aria-label="..." id="oneTrnsfrMiscTrnsRow<?php echo $cntrUsr; ?>_LineID" value="<?php echo $ordrMiscID; ?>">
                                                            <?php if ($canEdtTrns === true) { ?>
                                                                <select class="form-control" id="oneTrnsfrMiscTrnsRow<?php echo $cntrUsr; ?>_TrnsTyp" <?php echo $mkReadOnly; ?> onchange="">
                                                                    <?php
                                                                    $valslctdArry = array("", "");
                                                                    $srchInsArrys = array("CREDIT", "DEBIT");
                                                                    $valsArrys = array("DEPOSIT", "WITHDRAWAL");
                                                                    for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                                        if ($trnsType == $valsArrys[$z]) {
                                                                            $valslctdArry[$z] = "selected";
                                                                        }
                                                                        ?>
                                                                        <option value="<?php echo $valsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            <?php } else { ?>
                                                                <span><?php echo $trnsType; ?></span>
                                                            <?php } ?> 
                                                        </td>
                                                        <td class="lovtd">
                                                            <?php if ($canEdtTrns === true) { ?>                                      
                                                                <div class="input-group">
                                                                    <input type="text" class="form-control rqrdFld" aria-label="..." id="oneTrnsfrMiscTrnsRow<?php echo $cntrUsr; ?>_DstAcntNo" value="<?php echo $dstnAccount; ?>" style="width:100%;" onblur="afterAcntSlctn('oneTrnsfrMiscTrnsRow_<?php echo $cntr; ?>');"  onfocusout="afterAcntSlctn('oneTrnsfrMiscTrnsRow_<?php echo $cntr; ?>');">
                                                                    <input type="hidden" id="oneTrnsfrMiscTrnsRow<?php echo $cntrUsr; ?>_DstAcntID" value="<?php echo $dstnAccountID; ?>">                                            
                                                                    <input type="hidden" id="oneTrnsfrMiscTrnsRow<?php echo $cntrUsr; ?>_AcRawTxt1" value="<?php echo ''; ?>">
                                                                    <?php if ($rqStatus != "Authorized" && $rqStatus != "Cancelled") { ?>
                                                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'MCF All Bank Accounts', '', '', '', 'radio', true, '', 'oneTrnsfrMiscTrnsRow<?php echo $cntrUsr; ?>_DstAcntID', 'oneTrnsfrMiscTrnsRow<?php echo $cntrUsr; ?>_AcRawTxt1', 'clear', 1, '', function () {
                                                                                                    $('#oneTrnsfrMiscTrnsRow<?php echo $cntrUsr; ?>_DstAcntNo').val($('#oneTrnsfrMiscTrnsRow<?php echo $cntrUsr; ?>_AcRawTxt1').val().split(' [')[0]);
                                                                                                    $('#oneTrnsfrMiscTrnsRow<?php echo $cntrUsr; ?>_AcntTitle').val(rhotrim($('#oneTrnsfrMiscTrnsRow<?php echo $cntrUsr; ?>_AcRawTxt1').val().split(' [')[1], '] '));
                                                                                                });">
                                                                            <span class="glyphicon glyphicon-th-list"></span>
                                                                        </label>
                                                                    <?php } ?>
                                                                </div>
                                                            <?php } else { ?>
                                                                <span><?php echo $dstnAccount; ?></span>
                                                            <?php } ?> 
                                                        </td>
                                                        <td class="lovtd">
                                                            <?php if ($canEdtTrns === true) { ?>
                                                                <input type="text" class="form-control" aria-label="..." id="oneTrnsfrMiscTrnsRow<?php echo $cntrUsr; ?>_DstAmnt" value="<?php
                                                                echo number_format($dstnAmnt, 2);
                                                                ?>" style="width:100%;text-align:right;font-weight:bold;">
                                                                   <?php } else { ?>
                                                                <span  style="text-align:right;font-weight:bold;"><?php echo number_format($dstnAmnt, 2); ?></span>
                                                            <?php } ?> 
                                                        </td>                                                  
                                                        <td class="lovtd" style="display:none;">
                                                            <div class="" style="width:100% !important;">
                                                                <input type="hidden" class="form-control" aria-label="..." id="oneTrnsfrMiscTrnsRow<?php echo $cntrUsr; ?>_TrnsCurNm" name="oneTrnsfrMiscTrnsRow<?php echo $cntrUsr; ?>_TrnsCurNm" value="<?php echo $entrdCrncyNm; ?>" readonly="true" style="width:100% !important;">
                                                                <label class="btn btn-primary btn-file" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Currencies', '', '', '', 'radio', true, '', 'oneTrnsfrMiscTrnsRow<?php echo $cntrUsr; ?>_TrnsCurNm', '', 'clear', 1, '', function () {
                                                                                    $('#oneTrnsfrMiscTrnsRow<?php echo $cntrUsr; ?>_TrnsCurNm1').html($('#oneTrnsfrMiscTrnsRow<?php echo $cntrUsr; ?>_TrnsCurNm').val());
                                                                                });">
                                                                    <span class="" id="oneTrnsfrMiscTrnsRow<?php echo $cntrUsr; ?>_TrnsCurNm1"><?php echo $entrdCrncyNm; ?></span>
                                                                </label>
                                                            </div>                                              
                                                        </td>
                                                        <td class="lovtd">
                                                            <?php if ($canEdtTrns === true) { ?>
                                                                <input type="text" class="form-control rqrdFld" aria-label="..." id="oneTrnsfrMiscTrnsRow<?php echo $cntrUsr; ?>_Rmrks" value="<?php echo $rmrkDesc; ?>" style="width:100%;">                                                                       
                                                            <?php } else { ?>
                                                                <span><?php echo $rmrkDesc; ?></span>
                                                            <?php } ?> 
                                                        </td>
                                                        <td class="lovtd">
                                                            <?php if ($canEdtTrns === true) { ?>
                                                                <div class="input-group">
                                                                    <input class="form-control rqrdFld" id="oneTrnsfrMiscTrnsRow<?php echo $cntrUsr; ?>_GlAcntNum" style="font-size: 13px !important;font-weight: bold !important;" placeholder="Enter GL Account Number" type = "text" min="0" placeholder="" value="<?php echo $miscOrdrGlAcntNm; ?>" readonly="true"/>
                                                                    <input type="hidden" id="oneTrnsfrMiscTrnsRow<?php echo $cntrUsr; ?>_GlAcntID" value="<?php echo $miscOrdrGlAcntID; ?>">
                                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Accounts', '', '', '', 'radio', true, '', 'oneTrnsfrMiscTrnsRow<?php echo $cntrUsr; ?>_GlAcntID', 'oneTrnsfrMiscTrnsRow<?php echo $cntrUsr; ?>_GlAcntNum', 'clear', 1, '', function () {});">
                                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                                    </label>
                                                                </div>
                                                            <?php } else { ?>
                                                                <span  style="text-align:right;font-weight:bold;"><?php echo $miscOrdrGlAcntNm; ?></span>
                                                            <?php } ?> 
                                                        </td> 
                                                        <td class="lovtd" style="display:none;">
                                                            <?php if ($canEdtTrns === true) { ?>
                                                                <input type="text" class="form-control" aria-label="..." id="oneTrnsfrMiscTrnsRow<?php echo $cntrUsr; ?>_Rate" value="<?php
                                                                echo number_format($dstnRate, 4);
                                                                ?>" style="width:100%;text-align:right;font-weight:bold;">
                                                                   <?php } else { ?>
                                                                <span  style="text-align:right;font-weight:bold;"><?php echo number_format($dstnRate, 4); ?></span>
                                                            <?php } ?> 
                                                        </td> 
                                                        <td class="lovtd">
                                                            <?php if ($canEdtTrns === true) { ?>
                                                                <div class="input-group">
                                                                    <input type="text" class="form-control" aria-label="..." id="oneTrnsfrMiscTrnsRow<?php echo $cntrUsr; ?>_AcntTitle" value="<?php echo $dstnAccountNm; ?>" style="width:100%;" readonly="true">                                                                                                                                                                                                                  
                                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getCustAcctsInfo2Form('myFormsModalCA1', 'myFormsModalBodyCA1', 'myFormsModalTitleCA1', 'View Customer Account', 13, 2.1, 0, 'VIEW', 'oneTrnsfrMiscTrnsRow<?php echo $cntrUsr; ?>_DstAcntID', 'custAcctTable', '', 'oneTrnsfrMiscTrnsRow<?php echo $cntrUsr; ?>_DstAcntNo');" data-toggle="tooltip" data-placement="bottom" title = "Latest Account Information">
                                                                        <span class="glyphicon glyphicon-info-sign"></span>
                                                                    </label>
                                                                </div>
                                                            <?php } else { ?>
                                                                <span><?php echo $dstnAccountNm; ?></span>
                                                            <?php } ?> 
                                                        </td>
                                                        <td class="lovtd">
                                                            <?php if ($canEdtTrns === true) { ?>
                                                                <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delBulkTrnsHdrMisc('oneTrnsfrMiscTrnsRow_<?php echo $cntrUsr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Miscellaneous Trns.">
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
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </fieldset>
    </form>
    <?php
}

function get_MCFAttachments($searchWord, $offset, $limit_size, $hdrID, $hdrType, &$attchSQL) {
    $strSql = "SELECT a.attchmnt_id, a.src_id, a.attchmnt_desc, a.file_name, a.attchmnt_src, a.file_type " .
            "FROM mcf.mcf_doc_attchmnts a " .
            "WHERE(a.attchmnt_desc ilike '" . loc_db_escape_string($searchWord) .
            "' and a.src_id = " . $hdrID .
            " and a.attchmnt_src='" . loc_db_escape_string($hdrType) .
            "') ORDER BY a.attchmnt_id LIMIT " . $limit_size .
            " OFFSET " . (abs($offset * $limit_size));
    $result = executeSQLNoParams($strSql);
    $attchSQL = $strSql;
    return $result;
}

function get_Total_MCFAttachments($searchWord, $hdrID, $hdrType) {
    $strSql = "SELECT count(1) " .
            "FROM mcf.mcf_doc_attchmnts a " .
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

function updateMCFDocFlNm($attchmnt_id, $file_name) {
    global $usrID;
    $dateStr = getDB_Date_time();
    $insSQL = "UPDATE mcf.mcf_doc_attchmnts SET file_name='"
            . loc_db_escape_string($file_name) .
            "', last_update_by=" . $usrID .
            ", last_update_date='" . $dateStr . "'
                WHERE attchmnt_id=" . $attchmnt_id;
    return execUpdtInsSQL($insSQL);
}

function getNewMCFDocID() {
    $strSql = "select nextval('mcf.mcf_doc_attchmnts_attchmnt_id_seq')";
    $result = executeSQLNoParams($strSql);
    if (loc_db_num_rows($result) > 0) {
        $row = loc_db_fetch_array($result);
        return $row[0];
    }
    return -1;
}

function createMCFDoc($attchmnt_id, $hdrid, $attchmnt_desc, $file_name, $fileType, $hdrType) {
    global $usrID;
    $dateStr = getDB_Date_time();
    $insSQL = "INSERT INTO mcf.mcf_doc_attchmnts(
            attchmnt_id, src_id, attchmnt_desc, file_name, created_by, 
            creation_date, last_update_by, last_update_date, file_type, attchmnt_src)
             VALUES (" . $attchmnt_id . ", " . $hdrid . ",'"
            . loc_db_escape_string($attchmnt_desc) . "','"
            . loc_db_escape_string($file_name) . "',"
            . $usrID . ",'" . $dateStr . "'," . $usrID .
            ",'" . $dateStr . "', '" . loc_db_escape_string($fileType) . "', '" . loc_db_escape_string($hdrType) . "')";
    return execUpdtInsSQL($insSQL);
}

function deleteMCFDoc($pkeyID, $docTrnsNum = "") {
    $insSQL = "DELETE FROM mcf.mcf_doc_attchmnts WHERE attchmnt_id = " . $pkeyID;
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

function uploadDaMCFTrnsDoc($attchmntID, $docTrsType, $docFileType, &$nwImgLoc, &$errMsg) {
    global $tmpDest;
    global $ftp_base_db_fldr;
    global $usrID;
    global $fldrPrfx;
    global $smplTokenWord1;

    $msg = "";
    $allowedExts = array('png', 'jpg', 'gif', 'jpeg', 'bmp', 'pdf', 'xls', 'xlsx',
        'doc', 'docx', 'ppt', 'pptx', 'txt', 'csv');

    if (isset($_FILES["daMCFAttchmnt"])) {
        $flnm = $_FILES["daMCFAttchmnt"]["name"];
        $temp = explode(".", $flnm);
        $extension = end($temp);
        if ($_FILES["daMCFAttchmnt"]["error"] > 0) {
            $msg .= "Return Code: " . $_FILES["daMCFAttchmnt"]["error"] . "<br>";
        } else {
            $msg .= "Uploaded File: " . $_FILES["daMCFAttchmnt"]["name"] . "<br>";
            $msg .= "Type: " . $_FILES["daMCFAttchmnt"]["type"] . "<br>";
            $msg .= "Size: " . round(($_FILES["daMCFAttchmnt"]["size"]) / (1024 * 1024), 2) . " MB<br>";
//$msg .= "Temp file: " . $_FILES["daMCFAttchmnt"]["tmp_name"] . "<br>";
            if ((($_FILES["daMCFAttchmnt"]["type"] == "image/gif") ||
                    ($_FILES["daMCFAttchmnt"]["type"] == "image/jpeg") ||
                    ($_FILES["daMCFAttchmnt"]["type"] == "image/jpg") ||
                    ($_FILES["daMCFAttchmnt"]["type"] == "image/pjpeg") ||
                    ($_FILES["daMCFAttchmnt"]["type"] == "image/x-png") ||
                    ($_FILES["daMCFAttchmnt"]["type"] == "image/png") ||
                    in_array($extension, $allowedExts)) &&
                    ($_FILES["daMCFAttchmnt"]["size"] < 10000000)) {
                $nwFileName = encrypt1($attchmntID . "." . $extension, $smplTokenWord1) . "." . $extension;
                $img_src = $fldrPrfx . $tmpDest . "$nwFileName";
                move_uploaded_file($_FILES["daMCFAttchmnt"]["tmp_name"], $img_src);
                $ftp_src = "";
                if ($docTrsType == "Individual Customers" || $docTrsType == "Corporate Customers" || $docTrsType == "Customer Groups" || $docTrsType == "Other Persons") {
                    if ($docFileType == "Picture") {
                        $ftp_src = $ftp_base_db_fldr . "/Mcf/Customers/Picture/$attchmntID" . "." . $extension;
                    } else if ($docFileType == "Signature") {
                        $ftp_src = $ftp_base_db_fldr . "/Mcf/Customers/Signature/$attchmntID" . "." . $extension;
                    } else if ($docFileType == "Thumbprint") {
                        $ftp_src = $ftp_base_db_fldr . "/Mcf/Customers/Thumbprint/$attchmntID" . "." . $extension;
                    } else {
                        $ftp_src = $ftp_base_db_fldr . "/Mcf/Customers/Attachment/$attchmntID" . "." . $extension;
                    }
                } else {
                    $ftp_src = $ftp_base_db_fldr . "/Mcf/Transactions/$attchmntID" . "." . $extension;
                }
                if (file_exists($img_src)) {
                    copy("$img_src", "$ftp_src");
                    $dateStr = getDB_Date_time();
                    $updtSQL = "UPDATE mcf.mcf_doc_attchmnts
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

function getLatestSiteID($prsnID) {
    $strSql = "SELECT pasn.get_prsn_siteid(" . $prsnID . ") ";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return -1;
}

function getLatestCage($prsnID, &$cageID, &$vltID, &$invAssetAcntID, $crncyID = -1) {
    $extrWhere = "";
    if ($crncyID > 0) {
        $extrWhere = " and (inv.get_shlv_act_crncy_id(line_id)=$crncyID)";
    }
    $strSql = "SELECT shelf_id, store_id, line_id, shelve_name, shelve_desc, 
       lnkd_cstmr_id, allwd_group_type, allwd_group_value, enabled_flag, 
       inv_asset_acct_id, cage_shelve_mngr_id
  FROM inv.inv_shelf WHERE cage_shelve_mngr_id>0 and cage_shelve_mngr_id = " . $prsnID . $extrWhere . " ORDER BY line_id DESC LIMIT 1 OFFSET 0";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        $cageID = (int) $row[2];
        $vltID = (int) $row[1];
        $invAssetAcntID = (int) $row[9];
        return $row[2];
    }
    return -1;
}

function getMcfCageMngrID($lineid) {
    $sqlStr = "select cage_shelve_mngr_id from inv.inv_shelf where line_id = " . loc_db_escape_string($lineid);
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -1;
}

function getMyCages($prsnID) {
    $strSql = "SELECT shelf_id, store_id, line_id, shelve_name, shelve_desc, 
       lnkd_cstmr_id, allwd_group_type, allwd_group_value, enabled_flag, 
       inv_asset_acct_id, cage_shelve_mngr_id
       FROM inv.inv_shelf WHERE cage_shelve_mngr_id>0 and cage_shelve_mngr_id = " . $prsnID .
            " ORDER BY line_id DESC LIMIT 10 OFFSET 0";
    $result = executeSQLNoParams($strSql);
    return $result;
}

//CORE BANKING
//TELLER OPERAATIONS
//WITHDRAWAL TRANSACTIONS
function createAccountTrnsBal($acctID, $dateStr) {
    global $usrID;
    global $gnrlTrnsDteYMDHMS;
    /* if (strlen($dateStr) > 10) {
      $dateStr = substr($gnrlTrnsDteYMDHMS, 0, 10);
      } */
    $crdtDte = getDB_Date_time();
    $insSQL = "INSERT INTO mcf.mcf_account_daily_bals(
                bal_date, account_id, lien_amount,
                available_balance, uncleared_funds,
                created_by, creation_date, last_update_by, last_update_date)
            VALUES ('" . substr($gnrlTrnsDteYMDHMS, 0, 10) . "'," . $acctID . ",0.00,0.00,0.00," . $usrID . ", '" .
            $crdtDte . "', " . $usrID . ", '" . $crdtDte . "')";
    return execUpdtInsSQL($insSQL);
}

function get_CustAcctTransactionsTtl($searchFor, $searchIn, $orgID, $searchAll, $trnsType, $subTrnsType = "", $qStrtDte = "", $qEndDte = "", $bulkHdrID = -1) {
    global $usrID;
    global $prsnid;
    global $orgID;
    global $canSeeOthrsTrns;
    global $canSeeOthrBrnchTrns;
    $extra1 = "";
    $extra2 = "";
    if ($subTrnsType != "") {
        $extra2 = " AND a.sub_trns_type ='" . loc_db_escape_string($subTrnsType) . "'";
    }
    if ($bulkHdrID > 0) {
        $extra2 .= " AND a.bulk_trns_hdr_id = " . loc_db_escape_string($bulkHdrID) . "";
    }
    if ($trnsType == "ALL") {
        $trnsType = "%";
    }
    if ($searchAll == true) {
        $extra1 = "or 1 = 1";
    }

    $strSql = "";
    $whrcls = "";
    if ($searchIn == "All") {
        $whrcls = " AND (b.account_number ilike '" . loc_db_escape_string($searchFor) . "'"
                . " or b.account_title ilike '" . loc_db_escape_string($searchFor) . "'"
                . " or a.trns_date ilike '" . loc_db_escape_string($searchFor) . "'"
                . " or a.trns_type ilike '" . loc_db_escape_string($searchFor) . "'"
                . " or a.status ilike '" . loc_db_escape_string($searchFor) . "'"
                . " or a.description ilike '" . loc_db_escape_string($searchFor) . "'"
                . " or a.trns_person_name ilike '" . loc_db_escape_string($searchFor) . "' or "
                . "a.trns_person_tel_no ilike '" . loc_db_escape_string($searchFor) . "' or "
                . "a.trns_person_address ilike '" . loc_db_escape_string($searchFor) . "' or "
                . "a.trns_person_id_type ilike '" . loc_db_escape_string($searchFor) . "' or "
                . "a.trns_person_id_number ilike '" . loc_db_escape_string($searchFor) . "' or "
                . "a.trns_person_type ilike '" . loc_db_escape_string($searchFor) . "' or "
                . "a.acctcustomer ilike '" . loc_db_escape_string($searchFor) . "' or "
                . "a.trns_no ilike '" . loc_db_escape_string($searchFor) . "')";
    } else if ($searchIn == "Account Number") {
        $whrcls = " AND (b.account_number ilike '" . loc_db_escape_string($searchFor) . "')";
    } else if ($searchIn == "Account Title") {
        $whrcls = " AND (b.account_title ilike '" . loc_db_escape_string($searchFor) . "')";
    } else if ($searchIn == "Transaction Date") {
        $whrcls = " AND (a.trns_date ilike '" . loc_db_escape_string($searchFor) . "')";
    } else if ($searchIn == "Transaction Type") {
        $whrcls = " AND (a.trns_type ilike '" . loc_db_escape_string($searchFor) . "')";
    } else if ($searchIn == "Transaction Number") {
        $whrcls = " AND (a.trns_no ilike '" . loc_db_escape_string($searchFor) . "')";
    } else if ($searchIn == "Status") {
        $whrcls = " AND (a.status ilike '" . loc_db_escape_string($searchFor) . "')";
    } else if ($searchIn == "Narration") {
        $whrcls = " AND (a.description ilike '" . loc_db_escape_string($searchFor) . "')";
    } else if ($searchIn == "Contact Information") {
        $whrcls = " AND (a.trns_person_name ilike '" . loc_db_escape_string($searchFor) . "' or "
                . "a.trns_person_tel_no ilike '" . loc_db_escape_string($searchFor) . "' or "
                . "a.trns_person_address ilike '" . loc_db_escape_string($searchFor) . "' or "
                . "a.trns_person_id_type ilike '" . loc_db_escape_string($searchFor) . "' or "
                . "a.trns_person_id_number ilike '" . loc_db_escape_string($searchFor) . "' or "
                . "a.trns_person_type ilike '" . loc_db_escape_string($searchFor) . "' or "
                . "a.acctcustomer ilike '" . loc_db_escape_string($searchFor) . "')";
    }
    if (!$canSeeOthrsTrns) {
        $whrcls .= " and (a.created_by = $usrID or a.last_update_by= $usrID or a.authorized_by_person_id=$prsnid)";
    }
    if (!$canSeeOthrBrnchTrns) {
        //$whrcls .= " and (a.branch_id IN (Select r.location_id from pasn.prsn_locations r where r.person_id=$prsnid and (now() between to_timestamp(r.valid_start_date,'YYYY-MM-DD') and to_timestamp(r.valid_end_date,'YYYY-MM-DD'))))";
        $whrcls .= " and (a.branch_id IN (Select r.location_id from org.org_sites_locations r where org.does_prsn_hv_crtria_id(" . $prsnid . ", r.allwd_group_value::bigint,r.allwd_group_type)>0))";
    }
    if ($qStrtDte != "") {
        $whrcls .= " AND (a.trns_date >= '" . loc_db_escape_string(cnvrtDMYTmToYMDTm($qStrtDte)) . "')";
    }
    if ($qEndDte != "") {
        $whrcls .= " AND (a.trns_date <= '" . loc_db_escape_string(cnvrtDMYTmToYMDTm($qEndDte)) . "')";
    }
    $strSql = "SELECT count(1) " .
            "FROM mcf.mcf_cust_account_transactions a, mcf.mcf_accounts b, mcf.mcf_currencies c "
            . "WHERE (a.account_id = b.account_id AND b.currency_id = c.crncy_id AND a.trns_type like '" . $trnsType . "' AND (a.org_id = " . $orgID . " " . $extra1 . ")" . $whrcls . $extra2 .
            ")";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return 0;
}

function get_CustAcctTransactions($searchFor, $searchIn, $offset, $limit_size, $orgID, $searchAll, $sortBy, $trnsType, $subTrnsType = "", $qStrtDte = "", $qEndDte = "", $bulkHdrID = -1) {
    $extra1 = "";
    $extra2 = "";
    if ($subTrnsType != "") {
        $extra2 .= " AND a.sub_trns_type = '" . loc_db_escape_string($subTrnsType) . "'";
    }
    if ($bulkHdrID > 0) {
        $extra2 .= " AND a.bulk_trns_hdr_id = " . loc_db_escape_string($bulkHdrID) . "";
    }
    global $usrID;
    global $prsnid;
    global $orgID;
    global $canSeeOthrsTrns;
    global $canSeeOthrBrnchTrns;
    if ($trnsType == "ALL") {
        $trnsType = "%";
    }
    if ($searchAll == true) {
        $extra1 = "or 1 = 1";
    }

    $whrcls = "";
    $ordrBy = "";
    if ($searchIn == "All") {
        $whrcls = " AND (b.account_number ilike '" . loc_db_escape_string($searchFor) . "'"
                . " or b.account_title ilike '" . loc_db_escape_string($searchFor) . "'"
                . " or a.trns_date ilike '" . loc_db_escape_string($searchFor) . "'"
                . " or a.trns_type ilike '" . loc_db_escape_string($searchFor) . "'"
                . " or a.status ilike '" . loc_db_escape_string($searchFor) . "'"
                . " or a.description ilike '" . loc_db_escape_string($searchFor) . "'"
                . " or a.trns_person_name ilike '" . loc_db_escape_string($searchFor) . "' or "
                . "a.trns_person_tel_no ilike '" . loc_db_escape_string($searchFor) . "' or "
                . "a.trns_person_address ilike '" . loc_db_escape_string($searchFor) . "' or "
                . "a.trns_person_id_type ilike '" . loc_db_escape_string($searchFor) . "' or "
                . "a.trns_person_id_number ilike '" . loc_db_escape_string($searchFor) . "' or "
                . "a.trns_person_type ilike '" . loc_db_escape_string($searchFor) . "' or "
                . "a.acctcustomer ilike '" . loc_db_escape_string($searchFor) . "' or "
                . "a.trns_no ilike '" . loc_db_escape_string($searchFor) . "')";
    } else if ($searchIn == "Account Number") {
        $whrcls = " AND (b.account_number ilike '" . loc_db_escape_string($searchFor) . "')";
    } else if ($searchIn == "Account Title") {
        $whrcls = " AND (b.account_title ilike '" . loc_db_escape_string($searchFor) . "')";
    } else if ($searchIn == "Transaction Date") {
        $whrcls = " AND (a.trns_date ilike '" . loc_db_escape_string($searchFor) . "')";
    } else if ($searchIn == "Transaction Type") {
        $whrcls = " AND (a.trns_type ilike '" . loc_db_escape_string($searchFor) . "')";
    } else if ($searchIn == "Transaction Number") {
        $whrcls = " AND (a.trns_no ilike '" . loc_db_escape_string($searchFor) . "')";
    } else if ($searchIn == "Status") {
        $whrcls = " AND (a.status ilike '" . loc_db_escape_string($searchFor) . "')";
    } else if ($searchIn == "Narration") {
        $whrcls = " AND (a.description ilike '" . loc_db_escape_string($searchFor) . "')";
    } else if ($searchIn == "Contact Information") {
        $whrcls = " AND (a.trns_person_name ilike '" . loc_db_escape_string($searchFor) . "' or "
                . "a.trns_person_tel_no ilike '" . loc_db_escape_string($searchFor) . "' or "
                . "a.trns_person_address ilike '" . loc_db_escape_string($searchFor) . "' or "
                . "a.trns_person_id_type ilike '" . loc_db_escape_string($searchFor) . "' or "
                . "a.trns_person_id_number ilike '" . loc_db_escape_string($searchFor) . "' or "
                . "a.trns_person_type ilike '" . loc_db_escape_string($searchFor) . "' or "
                . "a.acctcustomer ilike '" . loc_db_escape_string($searchFor) . "')";
    }
    if ($sortBy == "Transaction Date DESC") {
        $ordrBy = "a.trns_date DESC";
    } else if ($sortBy == "Account Number") {
        $ordrBy = "b.account_number ASC";
    } else if ($sortBy == "Account Title ASC") {
        $ordrBy = "b.account_title ASC";
    } else if ($sortBy == "Transaction Type ASC") {
        $ordrBy = "a.trns_type ASC";
    } else if ($sortBy == "Account Title DESC") {
        $ordrBy = "a.account_title DESC";
    } else {
        $ordrBy = "a.acct_trns_id DESC";
    }
    if (!$canSeeOthrsTrns) {
        $whrcls .= " and (a.created_by = $usrID or a.last_update_by= $usrID or a.authorized_by_person_id=$prsnid)";
    }
    if (!$canSeeOthrBrnchTrns) {
        $whrcls .= " and (a.branch_id IN (Select r.location_id from org.org_sites_locations r where org.does_prsn_hv_crtria_id(" . $prsnid . ", r.allwd_group_value::bigint,r.allwd_group_type)>0))";
    }
    if ($qStrtDte != "") {
        $whrcls .= " AND (a.trns_date >= '" . loc_db_escape_string(cnvrtDMYTmToYMDTm($qStrtDte)) . "')";
    }
    if ($qEndDte != "") {
        $whrcls .= " AND (a.trns_date <= '" . loc_db_escape_string(cnvrtDMYTmToYMDTm($qEndDte)) . "')";
    }
    $strSql = "SELECT acct_trns_id, b.account_number, b.account_title, a.trns_date, b.currency_id, trns_type, " .
            "a.amount, a.status, a.account_id, c.mapped_lov_crncy_id, gst.get_pssbl_val(c.mapped_lov_crncy_id) crncy_nm,"
            . "to_char(to_timestamp(a.trns_date, 'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS'), a.trns_no, "
            . "a.sub_trns_type, a.description||' '||a.reversal_reason,
       accb.get_accnt_num(a.balancing_gl_accnt_id) accnt_num, accb.get_accnt_name(a.balancing_gl_accnt_id) gl_acc_nm  " .
            "FROM mcf.mcf_cust_account_transactions a,  mcf.mcf_accounts b, mcf.mcf_currencies c "
            . "WHERE (a.account_id = b.account_id AND a.trns_type like '" . $trnsType .
            "' AND b.currency_id = c.crncy_id AND (a.org_id = " . $orgID . " " . $extra1 . ")" . $whrcls . $extra2 .
            ") ORDER BY " . $ordrBy . " LIMIT " . $limit_size .
            " OFFSET " . abs($offset * $limit_size);
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_BulkTransactionsTtl($searchFor, $searchIn, $qStrtDte = "", $qEndDte = "") {
    $extra1 = "";
    $extra2 = "";
    global $usrID;
    global $prsnid;
    global $orgID;
    global $canSeeOthrsTrns;
    global $canSeeOthrBrnchTrns;
    $whrcls = "";

    if ($searchIn == "All") {
        $whrcls = " AND (a.batch_number ilike '" . loc_db_escape_string($searchFor) . "'"
                . " or COALESCE(prs.get_prsn_name(a.lnkd_person_id) || ' (' || prs.get_prsn_loc_id(a.lnkd_person_id) || ')', '') ilike '" . loc_db_escape_string($searchFor) . "'"
                . " or to_char(to_timestamp(batch_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') ilike '" . loc_db_escape_string($searchFor) . "'"
                . " or a.status ilike '" . loc_db_escape_string($searchFor) . "'"
                . " or a.rmrk_cmmnt||a.reversal_reason ilike '" . loc_db_escape_string($searchFor) . "')";
    } else if ($searchIn == "Batch Number") {
        $whrcls = " AND (a.batch_number ilike '" . loc_db_escape_string($searchFor) . "')";
    } else if ($searchIn == "Relations Officer") {
        $whrcls = " AND (COALESCE(prs.get_prsn_name(a.lnkd_person_id) || ' (' || prs.get_prsn_loc_id(a.lnkd_person_id) || ')', '') ilike '" . loc_db_escape_string($searchFor) . "')";
    } else if ($searchIn == "Transaction Date") {
        $whrcls = " AND (to_char(to_timestamp(batch_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') ilike '" . loc_db_escape_string($searchFor) . "')";
    } else if ($searchIn == "Status") {
        $whrcls = " AND (a.status ilike '" . loc_db_escape_string($searchFor) . "')";
    } else if ($searchIn == "Narration") {
        $whrcls = " AND (a.rmrk_cmmnt||a.reversal_reason ilike '" . loc_db_escape_string($searchFor) . "')";
    }
    if (!$canSeeOthrsTrns) {
        $whrcls .= " and (a.created_by = $usrID or a.last_update_by= $usrID or a.authorized_by_person_id=$prsnid)";
    }
    if (!$canSeeOthrBrnchTrns) {
        $whrcls .= " and (a.branch_id IN (Select r.location_id from org.org_sites_locations r where org.does_prsn_hv_crtria_id(" . $prsnid . ", r.allwd_group_value::bigint,r.allwd_group_type)>0))";
    }
    if ($qStrtDte != "") {
        $whrcls .= " AND (a.batch_date >= '" . loc_db_escape_string(cnvrtDMYTmToYMDTm($qStrtDte)) . "')";
    }
    if ($qEndDte != "") {
        $whrcls .= " AND (a.batch_date <= '" . loc_db_escape_string(cnvrtDMYTmToYMDTm($qEndDte)) . "')";
    }
    $strSql = "SELECT count(1) " .
            "FROM mcf.mcf_bulk_trns_hdr a "
            . "WHERE ((a.org_id = " . $orgID . " " . $extra1 . ")" . $whrcls . $extra2 .
            ")";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return 0;
}

function get_BulkTransactions($searchFor, $searchIn, $offset, $limit_size, $qStrtDte = "", $qEndDte = "") {
    $extra1 = "";
    $extra2 = "";
    global $usrID;
    global $prsnid;
    global $orgID;
    global $canSeeOthrsTrns;
    global $canSeeOthrBrnchTrns;
    $whrcls = "";
    if ($searchIn == "All") {
        $whrcls = " AND (a.batch_number ilike '" . loc_db_escape_string($searchFor) . "'"
                . " or COALESCE(prs.get_prsn_name(a.lnkd_person_id) || ' (' || prs.get_prsn_loc_id(a.lnkd_person_id) || ')', '') ilike '" . loc_db_escape_string($searchFor) . "'"
                . " or to_char(to_timestamp(batch_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') ilike '" . loc_db_escape_string($searchFor) . "'"
                . " or a.status ilike '" . loc_db_escape_string($searchFor) . "'"
                . " or a.rmrk_cmmnt||a.reversal_reason ilike '" . loc_db_escape_string($searchFor) . "')";
    } else if ($searchIn == "Batch Number") {
        $whrcls = " AND (a.batch_number ilike '" . loc_db_escape_string($searchFor) . "')";
    } else if ($searchIn == "Relations Officer") {
        $whrcls = " AND (COALESCE(prs.get_prsn_name(a.lnkd_person_id) || ' (' || prs.get_prsn_loc_id(a.lnkd_person_id) || ')', '') ilike '" . loc_db_escape_string($searchFor) . "')";
    } else if ($searchIn == "Transaction Date") {
        $whrcls = " AND (to_char(to_timestamp(a.batch_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') ilike '" . loc_db_escape_string($searchFor) . "')";
    } else if ($searchIn == "Status") {
        $whrcls = " AND (a.status ilike '" . loc_db_escape_string($searchFor) . "')";
    } else if ($searchIn == "Narration") {
        $whrcls = " AND (a.rmrk_cmmnt||a.reversal_reason ilike '" . loc_db_escape_string($searchFor) . "')";
    }
    if (!$canSeeOthrsTrns) {
        $whrcls .= " and (a.created_by = $usrID or a.last_update_by= $usrID or a.authorized_by_person_id=$prsnid)";
    }
    if (!$canSeeOthrBrnchTrns) {
        $whrcls .= " and (a.branch_id IN (Select r.location_id from org.org_sites_locations r where org.does_prsn_hv_crtria_id(" . $prsnid . ", r.allwd_group_value::bigint,r.allwd_group_type)>0))";
    }
    if ($qStrtDte != "") {
        $whrcls .= " AND (a.batch_date >= '" . loc_db_escape_string(cnvrtDMYTmToYMDTm($qStrtDte)) . "')";
    }
    if ($qEndDte != "") {
        $whrcls .= " AND (a.batch_date <= '" . loc_db_escape_string(cnvrtDMYTmToYMDTm($qEndDte)) . "')";
    }
    $strSql = "SELECT a.bulk_trns_hdr_id, a.batch_number, 
        to_char(to_timestamp(a.batch_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') batch_date, 
        a.branch_id, org.get_site_code_desc(a.branch_id), a.rmrk_cmmnt, 
       a.lnkd_person_id, COALESCE(prs.get_prsn_name(a.lnkd_person_id) || ' (' || prs.get_prsn_loc_id(a.lnkd_person_id) || ')', '') lnkd_person,
       a.ttl_amount, a.status, a.authorized_by_person_id, 
       a.autorization_date, a.approval_limit_id, a.voided_batch_id, a.reversal_reason, 
       a.org_id, 
       a.dest_store_vault_id, inv.get_store_name(a.dest_store_vault_id) vltnm,
       a.dest_cage_shelve_id, inv.get_shelve_name(a.dest_cage_shelve_id) cagenm,
       a.crncy_id, gst.get_pssbl_val(mcf.get_mppd_lov_crncy_id(a.crncy_id)) crncy,
       a.avrg_exchange_rate, a.created_by, a.creation_date, a.last_update_by, a.last_update_date
   FROM mcf.mcf_bulk_trns_hdr a "
            . "WHERE ((a.org_id = " . $orgID . " " . $extra1 . ")" . $whrcls . $extra2 .
            ") ORDER BY a.bulk_trns_hdr_id DESC LIMIT " . $limit_size .
            " OFFSET " . abs($offset * $limit_size);
    //echo $strSql;
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_One_BatchTrnsHdr($trnsHdrID) {
    global $usrID;
    global $prsnid;
    global $canSeeOthrsTrns;
    $whereCls = "";
    /* if (!$canSeeOthrsTrns) {
      $whereCls .= " and (a.created_by = $usrID or a.last_update_by= $usrID or a.authorized_by_person_id=$prsnid)";
      }
     */
    $strSql = "SELECT a.bulk_trns_hdr_id, a.batch_number, 
        to_char(to_timestamp(a.batch_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') batch_date, 
        a.branch_id, org.get_site_code_desc(a.branch_id), a.rmrk_cmmnt, 
       a.lnkd_person_id, prs.get_prsn_name(a.lnkd_person_id) lnkd_person_nm,
       prs.get_prsn_loc_id(a.lnkd_person_id) lnkd_person_loc_id,
       a.ttl_amount, a.status, a.authorized_by_person_id, 
       a.autorization_date, a.approval_limit_id, a.voided_batch_id, a.reversal_reason, 
       a.org_id, 
       a.dest_store_vault_id, inv.get_store_name(a.dest_store_vault_id) vltnm,
       a.dest_cage_shelve_id, inv.get_shelve_name(a.dest_cage_shelve_id) cagenm,
       a.dflt_item_state, a.crncy_id, gst.get_pssbl_val(mcf.get_mppd_lov_crncy_id(a.crncy_id)) crncy,
       a.avrg_exchange_rate, a.created_by, a.creation_date, a.last_update_by, a.last_update_date, 
       mcf.get_mppd_lov_crncy_id(a.crncy_id) lovCrncyID, a.dflt_gl_acnt_id,
       accb.get_accnt_num(a.dflt_gl_acnt_id) || '.' || accb.get_accnt_name(a.dflt_gl_acnt_id) gl_acc_nm,
       is_batch_cashless
   FROM mcf.mcf_bulk_trns_hdr a
        WHERE ((a.bulk_trns_hdr_id = $trnsHdrID)$whereCls)";
    $result = executeSQLNoParams($strSql);
    return $result;
}

function getBatchTrnsHdrID($trnsNum) {
    $strSql = "SELECT a.bulk_trns_hdr_id 
        FROM mcf.mcf_bulk_trns_hdr a
        WHERE ((a.batch_number ='" . loc_db_escape_string($trnsNum) . "')) ORDER BY a.bulk_trns_hdr_id DESC ";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return -1;
}

function getNewBatchTrnsHdrID() {
    $sqlStr = "select nextval('mcf.mcf_bulk_trns_hdr_bulk_trns_hdr_id_seq'::regclass);";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -1;
}

function get_BulkCashAnalysisTtlAmount($acctTrnsId, $crncyID) {
    $strSql = "SELECT sum((value * b.qty)) " .
            " FROM mcf.mcf_currency_denominations a, mcf.mcf_account_trns_cash_analysis b " .
            " WHERE a.crncy_denom_id = b.denomination_id " .
            " AND a.is_enabled = 'YES' AND crncy_id = " . $crncyID .
            " AND b.acct_trns_id<=0 AND b.bulk_trns_hdr_id = " . $acctTrnsId;
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return 0;
}

function get_One_BulkTrnsTllrLns($dochdrID) {
    global $prsnid;
    $strSql = "SELECT a.cash_analysis_id, a.bulk_trns_hdr_id, a.denomination_id, a.unit_value, 
                    a.qty, b.mapped_lov_crncy_id, c.vault_item_id, c.display_name, d.item_type, 
                a.cage_shelve_id, a.vault_id, a.item_state, a.accnt_crncy_rate, 
                org.get_dflt_accnt_id($prsnid, e.inv_asset_acct_id), 
                e.cage_shelve_mngr_id, b.crncy_id
                FROM mcf.mcf_account_trns_cash_analysis a, mcf.mcf_currencies b,  
                        mcf.mcf_currency_denominations c, inv.inv_itm_list d, inv.inv_shelf e
                    WHERE  a.bulk_trns_hdr_id = $dochdrID AND a.acct_trns_id<=0 and a.denomination_id = c.crncy_denom_id "
            . "and b.crncy_id=c.crncy_id and c.vault_item_id=d.item_id  and a.cage_shelve_id = e.line_id and a.qty !=0 "
            . "ORDER BY a.cash_analysis_id, a.denomination_id";
    return executeSQLNoParams($strSql);
}

function get_BulkCashAnalysis($acctTrnsId, $crncyID) {
    $strSql = "SELECT a.crncy_denom_id, a.crncy_type, a.display_name, a.value, a.is_enabled, "
            . "b.cash_analysis_id, NULLIF(b.qty,0), NULLIF((a.value * b.qty),0), 
            b.vault_id, b.cage_shelve_id, b.item_state, b.accnt_crncy_rate,
            (vms.get_ltst_stock_bals(b.vault_id, b.cage_shelve_id, a.vault_item_id::integer, b.item_state) * a.value) rnngbals " .
            " FROM mcf.mcf_currency_denominations a, mcf.mcf_account_trns_cash_analysis b " .
            " WHERE a.crncy_denom_id = b.denomination_id " .
            " AND b.acct_trns_id<=0 AND a.is_enabled = 'YES' AND a.crncy_id = " . $crncyID .
            " AND b.bulk_trns_hdr_id>0 AND b.bulk_trns_hdr_id = $acctTrnsId " .
            " ORDER BY a.crncy_type desc, a.value desc";
    //echo $strSql;
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_BulkCashAnalysisRO($acctTrnsId, $crncyID) {
    $strSql = "SELECT a.crncy_denom_id, a.crncy_type, a.display_name, a.value, a.is_enabled, "
            . "b.cash_analysis_id, NULLIF(b.qty,0), NULLIF((a.value * b.qty),0), 
            b.vault_id, b.cage_shelve_id, b.item_state, b.accnt_crncy_rate, 
            CASE WHEN mcf.get_trns_status(b.acct_trns_id) IN ('Received','Paid') THEN b.cage_balance_b4_trns ELSE (vms.get_ltst_stock_bals(b.vault_id, b.cage_shelve_id, a.vault_item_id::integer, b.item_state) * a.value) END " .
            " FROM mcf.mcf_currency_denominations a, mcf.mcf_account_trns_cash_analysis b " .
            " WHERE a.crncy_denom_id = b.denomination_id " .
            " AND a.is_enabled = 'YES' AND a.crncy_id = " . $crncyID .
            " AND b.acct_trns_id<=0 AND b.bulk_trns_hdr_id = $acctTrnsId AND COALESCE(b.qty, 0)!=0 " .
            " ORDER BY a.crncy_type desc, a.value desc";
    //echo $strSql;
    $result = executeSQLNoParams($strSql);
//echo $strSql;                                                 
    return $result;
}

function checkExstncOfBulkCashBrkdwn($acctTrnsId, $orgID) {
    $strSql = "SELECT count(1) " .
            "FROM mcf.mcf_currency_denominations a, mcf.mcf_account_trns_cash_analysis b "
            . "WHERE a.crncy_denom_id = b.denomination_id AND b.bulk_trns_hdr_id = $acctTrnsId AND (a.org_id = $orgID) AND b.acct_trns_id<=0 ";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return 0;
}

function createBulkCashBreakdownInit($acctTrnsId, $crncyID, $vltID = -1, $cageID = -1, $itmState = "Issuable") {
    global $usrID;
    $dateStr = getDB_Date_time();
//GENERATE CUST ACCOUNT

    $insSQL = "INSERT INTO mcf.mcf_account_trns_cash_analysis(
                                                                    acct_trns_id, denomination_id, unit_value, 
                                                                    qty, created_by, creation_date, last_update_by, last_update_date, 
                                                                    vault_id, cage_shelve_id, item_state, accnt_crncy_rate, bulk_trns_hdr_id) " .
            " SELECT -1, crncy_denom_id, value, 0, $usrID, '$dateStr', $usrID, "
            . "'$dateStr', $vltID, $cageID, '" . loc_db_escape_string($itmState) . "',1, $acctTrnsId "
            . "FROM mcf.mcf_currency_denominations WHERE is_enabled = 'YES' AND crncy_id = $crncyID ORDER BY value";
//echo $insSQL;
    execUpdtInsSQL($insSQL);
}

function voidBulkCashBreakdownAnlsys($oldAcctTrnsId, $nwAcctTrnsId) {
    global $usrID;
    $dateStr = getDB_Date_time();
//GENERATE CUST ACCOUNT

    $insSQL = "INSERT INTO mcf.mcf_account_trns_cash_analysis(
            acct_trns_id, denomination_id, unit_value, 
            qty, created_by, creation_date, last_update_by, last_update_date, 
            vault_id, cage_shelve_id, item_state, accnt_crncy_rate, bulk_trns_hdr_id) " .
            " SELECT -1, denomination_id, unit_value, -1*qty , $usrID, '$dateStr', $usrID, '$dateStr', "
            . "vault_id, cage_shelve_id, item_state,accnt_crncy_rate, $nwAcctTrnsId "
            . "FROM mcf.mcf_account_trns_cash_analysis WHERE acct_trns_id<=0 AND bulk_trns_hdr_id = $oldAcctTrnsId ORDER BY unit_value";
//var_dump($insSQL);
    execUpdtInsSQL($insSQL);
}

function getBulkAccountTrnsChqs($acctTrnsId, $trnsChqID = -1) {
    $whrCls = "";
    if ($trnsChqID > 0) {
        $whrCls = " and a.trns_cheque_id=" . $trnsChqID;
    }
    $selSQL = "SELECT a.trns_cheque_id, 
                    a.acct_trns_id, 
                    a.cheque_bank_id, 
                    c.bank_name bnknm,
                    a.cheque_branch_id, 
                    d.branch_name brnchnm,
                    a.cheque_no, 
                    a.amount, 
                    to_char(to_timestamp(a.value_date,'YYYY-MM-DD'),'DD-Mon-YYYY'),  
                    a.cheque_type, 
                    to_char(to_timestamp(a.cheque_date,'YYYY-MM-DD'),'DD-Mon-YYYY'), 
                    a.cheque_type_id, 
                    a.cheque_crncy_id, 
                    b.mapped_lov_crncy_id, 
                    gst.get_pssbl_val(b.mapped_lov_crncy_id) crncy_nm, 
                    a.accnt_crncy_rate, 
                    a.house_chq_src_accnt_id, 
                    a.created_by, 
                    sec.get_usr_prsn_id(a.created_by),
                    mcf.get_hsechq_acntid(a.cheque_no) src_acnt_id, 
                    a.is_cleared, 
                    a.date_cleared, 
                    a.src_accnt_trns_id, 
                    a.cheque_mandate, 
                    mcf.get_cust_accnt_num(a.house_chq_src_accnt_id) acnt_num 
            FROM mcf.mcf_cust_account_trns_cheques a 
            LEFT OUTER JOIN mcf.mcf_currencies b ON (a.cheque_crncy_id = b.crncy_id) 
            LEFT OUTER JOIN mcf.mcf_all_banks c ON (a.cheque_bank_id = c.bank_id)  
            LEFT OUTER JOIN mcf.mcf_bank_branches d ON (a.cheque_branch_id = d.branch_id and d.bank_id = c.bank_id) 
            WHERE a.acct_trns_id<=0 AND a.bulk_trns_hdr_id>0 and a.bulk_trns_hdr_id = " . $acctTrnsId . $whrCls;
    return executeSQLNoParams($selSQL);
}

function getBulkAcntTrnsChqsSum($acctTrnsId, $trnsChqID = -1) {
    $whrCls = "";
    if ($trnsChqID > 0) {
        $whrCls = " and a.trns_cheque_id=" . $trnsChqID;
    }
    $selSQL = "SELECT SUM(a.amount*a.accnt_crncy_rate) amnt
            FROM mcf.mcf_cust_account_trns_cheques a 
            LEFT OUTER JOIN mcf.mcf_currencies b ON (a.cheque_crncy_id = b.crncy_id) 
            LEFT OUTER JOIN mcf.mcf_all_banks c ON (a.cheque_bank_id = c.bank_id)  
            LEFT OUTER JOIN mcf.mcf_bank_branches d ON (a.cheque_branch_id = d.branch_id and d.bank_id = c.bank_id) 
            WHERE a.acct_trns_id<=0 AND a.bulk_trns_hdr_id = " . $acctTrnsId . $whrCls;
    $result = executeSQLNoParams($selSQL);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return 0;
}

function verifyBatchTrns($batchId) {
    $selSQL = "SELECT mcf.verify_batch_trns(" . loc_db_escape_string($batchId) . ")";
    $result = executeSQLNoParams($selSQL);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return "";
}

function get_One_BlkCashTrnsHdr1($dochdrID, $relationOfficer) {
    global $prsnid;
    global $gnrlTrnsDteYMDHMS;
    $strSql = "SELECT a.acct_trns_id, a.trns_date, a.account_id, a.trns_type, a.description, 
       a.amount, a.value_date, a.branch_id, a.doc_no, a.trns_person_name, a.trns_person_tel_no, 
       a.trns_person_address, a.trns_person_id_type, a.trns_person_id_number, 
       a.trns_person_type, a.status, a.doc_type, a.debit_or_credit, 
       a.authorized_by_person_id, a.autorization_date, a.trns_no, 
       org.get_accnt_id_brnch_eqv(b.branch_id, d.deposit_cash_crdt_accnt_id), 
       org.get_accnt_id_brnch_eqv(b.branch_id, d.withdrawal_dbt_accnt_id),
       e.mapped_lov_crncy_id, a.accnt_crncy_rate, 
       org.get_dflt_accnt_id($prsnid, d.deposit_cash_dbt_accnt_id), 
       org.get_accnt_id_brnch_eqv(b.branch_id, d.deposit_cash_crdt_accnt_id), 
       org.get_accnt_id_brnch_eqv(b.branch_id, d.withdrawal_dbt_accnt_id), 
       org.get_dflt_accnt_id($prsnid, d.withdrawal_crdt_accnt_id), 
       org.get_dflt_accnt_id($prsnid, d.deposit_cheque_dbt_accnt_id), 
       org.get_accnt_id_brnch_eqv(b.branch_id, d.deposit_cheque_crdt_accnt_id), 
       org.get_accnt_id_brnch_eqv(b.branch_id, d.entry_fees_dbt_accnt_id), 
       org.get_accnt_id_brnch_eqv(b.branch_id, d.entry_fees_crdt_accnt_id), 
       org.get_accnt_id_brnch_eqv(b.branch_id, d.close_fees_dbt_accnt_id), 
       org.get_accnt_id_brnch_eqv(b.branch_id, d.close_fees_crdt_accnt_id), 
       org.get_accnt_id_brnch_eqv(b.branch_id, d.reopen_fees_dbt_accnt_id), 
       org.get_accnt_id_brnch_eqv(b.branch_id, d.reopen_fees_crdt_accnt_id), 
       org.get_accnt_id_brnch_eqv(b.branch_id, d.wthdrwl_pnlty_pcnt_dbt_accnt_id), 
       org.get_accnt_id_brnch_eqv(b.branch_id, d.wthdrwl_pnlty_pcnt_crdt_accnt_id), 
       org.get_accnt_id_brnch_eqv(b.branch_id, d.wthdrwl_pnlty_pcnt_dbt_accnt_id), 
       org.get_accnt_id_brnch_eqv(b.branch_id, d.wthdrwl_pnlty_pcnt_crdt_accnt_id), 
       org.get_accnt_id_brnch_eqv(b.branch_id, d.invstmnt_liability_accnt_id), 
       org.get_accnt_id_brnch_eqv(b.branch_id, d.invstmnt_fee_crdt_accnt_id), 
       org.get_accnt_id_brnch_eqv(b.branch_id, d.cot_amnt_flat_dbt_accnt_id), 
       org.get_accnt_id_brnch_eqv(b.branch_id, d.cot_amnt_flat_crdt_accnt_id), 
       org.get_accnt_id_brnch_eqv(b.branch_id, d.accrued_interest_dbt_accnt_id), 
       org.get_accnt_id_brnch_eqv(b.branch_id, d.accrued_interest_crdt_accnt_id), 
       org.get_accnt_id_brnch_eqv(b.branch_id, d.interest_payment_dbt_accnt_id), 
       org.get_accnt_id_brnch_eqv(b.branch_id, d.interest_payment_crdt_accnt_id),
       (Select COALESCE(max(z.cage_shelve_id),-1) from mcf.mcf_account_trns_cash_analysis z where z.acct_trns_id=a.acct_trns_id) maxx_cg_id, 
                    mcf.get_cust_accnt_num(a.account_id) acnt_num, 
                    gst.get_pssbl_val(e.mapped_lov_crncy_id) crncy_nm, a.mandate, 
       (CASE WHEN (a.status NOT IN ('Authorized','Paid','Received')) or a.status IS NULL THEN 
       mcf.get_cstacnt_avlbl_bals(a.account_id, '" . substr($gnrlTrnsDteYMDHMS, 0, 10) . "') ELSE a.clrdbal END)  clrdbal
    FROM mcf.mcf_cust_account_transactions a 
    LEFT OUTER JOIN mcf.mcf_accounts b ON (a.account_id = b.account_id)
    LEFT OUTER JOIN mcf.mcf_prdt_savings c ON (b.product_type_id = c.svngs_product_id)
    LEFT OUTER JOIN mcf.mcf_prdt_savings_stdevnt_accntn d ON (c.svngs_product_id = d.svngs_product_id) 
    LEFT OUTER JOIN mcf.mcf_currencies e ON(a.entered_crncy_id = e.crncy_id)
  WHERE a.bulk_trns_hdr_id>0 and a.bulk_trns_hdr_id = " . $dochdrID .
            " and b.relationship_officer='" . loc_db_escape_string($relationOfficer) .
            "' and a.account_id NOT IN (select z.account_id from mcf.mcf_cust_account_transactions z where z.bulk_trns_hdr_id = " . $dochdrID . ")";
    //echo $strSql;
    return executeSQLNoParams($strSql);
}

function get_One_BlkCashTrnsHdr($dochdrID) {
    global $prsnid;
    global $gnrlTrnsDteYMDHMS;
    $strSql1 = "";
    $strSql = "SELECT a.acct_trns_id, a.trns_date, a.account_id, a.trns_type, a.description, 
       a.amount, a.value_date, a.branch_id, a.doc_no, a.trns_person_name, a.trns_person_tel_no, 
       a.trns_person_address, a.trns_person_id_type, a.trns_person_id_number, 
       a.trns_person_type, a.status, a.doc_type, a.debit_or_credit, 
       a.authorized_by_person_id, a.autorization_date, a.trns_no, 
       org.get_accnt_id_brnch_eqv(b.branch_id, d.deposit_cash_crdt_accnt_id), 
       org.get_accnt_id_brnch_eqv(b.branch_id, d.withdrawal_dbt_accnt_id),
       e.mapped_lov_crncy_id, a.accnt_crncy_rate, 
       org.get_dflt_accnt_id($prsnid, d.deposit_cash_dbt_accnt_id), 
       org.get_accnt_id_brnch_eqv(b.branch_id, d.deposit_cash_crdt_accnt_id), 
       org.get_accnt_id_brnch_eqv(b.branch_id, d.withdrawal_dbt_accnt_id), 
       org.get_dflt_accnt_id($prsnid, d.withdrawal_crdt_accnt_id), 
       org.get_dflt_accnt_id($prsnid, d.deposit_cheque_dbt_accnt_id), 
       org.get_accnt_id_brnch_eqv(b.branch_id, d.deposit_cheque_crdt_accnt_id), 
       org.get_accnt_id_brnch_eqv(b.branch_id, d.entry_fees_dbt_accnt_id), 
       org.get_accnt_id_brnch_eqv(b.branch_id, d.entry_fees_crdt_accnt_id), 
       org.get_accnt_id_brnch_eqv(b.branch_id, d.close_fees_dbt_accnt_id), 
       org.get_accnt_id_brnch_eqv(b.branch_id, d.close_fees_crdt_accnt_id), 
       org.get_accnt_id_brnch_eqv(b.branch_id, d.reopen_fees_dbt_accnt_id), 
       org.get_accnt_id_brnch_eqv(b.branch_id, d.reopen_fees_crdt_accnt_id), 
       org.get_accnt_id_brnch_eqv(b.branch_id, d.wthdrwl_pnlty_pcnt_dbt_accnt_id), 
       org.get_accnt_id_brnch_eqv(b.branch_id, d.wthdrwl_pnlty_pcnt_crdt_accnt_id), 
       org.get_accnt_id_brnch_eqv(b.branch_id, d.wthdrwl_pnlty_pcnt_dbt_accnt_id), 
       org.get_accnt_id_brnch_eqv(b.branch_id, d.wthdrwl_pnlty_pcnt_crdt_accnt_id), 
       org.get_accnt_id_brnch_eqv(b.branch_id, d.invstmnt_liability_accnt_id), 
       org.get_accnt_id_brnch_eqv(b.branch_id, d.invstmnt_fee_crdt_accnt_id), 
       org.get_accnt_id_brnch_eqv(b.branch_id, d.cot_amnt_flat_dbt_accnt_id), 
       org.get_accnt_id_brnch_eqv(b.branch_id, d.cot_amnt_flat_crdt_accnt_id), 
       org.get_accnt_id_brnch_eqv(b.branch_id, d.accrued_interest_dbt_accnt_id), 
       org.get_accnt_id_brnch_eqv(b.branch_id, d.accrued_interest_crdt_accnt_id), 
       org.get_accnt_id_brnch_eqv(b.branch_id, d.interest_payment_dbt_accnt_id), 
       org.get_accnt_id_brnch_eqv(b.branch_id, d.interest_payment_crdt_accnt_id),
       (Select COALESCE(max(z.cage_shelve_id),-1) from mcf.mcf_account_trns_cash_analysis z where z.acct_trns_id=a.acct_trns_id) maxx_cg_id, 
                    mcf.get_cust_accnt_num(a.account_id) acnt_num, 
                    gst.get_pssbl_val(e.mapped_lov_crncy_id) crncy_nm, a.mandate, 
       (CASE WHEN (a.status NOT IN ('Authorized','Paid','Received')) or a.status IS NULL THEN 
       mcf.get_cstacnt_avlbl_bals(a.account_id, '" . substr($gnrlTrnsDteYMDHMS, 0, 10) . "') ELSE a.clrdbal END)  clrdbal,
           mcf.get_cust_accnt_name(a.account_id) acnt_name
    FROM mcf.mcf_cust_account_transactions a 
    LEFT OUTER JOIN mcf.mcf_accounts b ON (a.account_id = b.account_id)
    LEFT OUTER JOIN mcf.mcf_prdt_savings c ON (b.product_type_id = c.svngs_product_id)
    LEFT OUTER JOIN mcf.mcf_prdt_savings_stdevnt_accntn d ON (c.svngs_product_id = d.svngs_product_id) 
    LEFT OUTER JOIN mcf.mcf_currencies e ON(a.entered_crncy_id = e.crncy_id)
  WHERE a.bulk_trns_hdr_id>0 and a.bulk_trns_hdr_id = " . $dochdrID;
    //echo $strSql;
    return executeSQLNoParams($strSql);
}

function get_One_BlkCashTrnsSum($dochdrID) {
    $strSql = "SELECT SUM(CASE WHEN a.trns_type='WITHDRAWAL' THEN -1*a.amount*a.accnt_crncy_rate ELSE a.amount*a.accnt_crncy_rate END) amnt 
    FROM mcf.mcf_cust_account_transactions a 
    LEFT OUTER JOIN mcf.mcf_accounts b ON (a.account_id = b.account_id)
    LEFT OUTER JOIN mcf.mcf_prdt_savings c ON (b.product_type_id = c.svngs_product_id)
    LEFT OUTER JOIN mcf.mcf_prdt_savings_stdevnt_accntn d ON (c.svngs_product_id = d.svngs_product_id) 
    LEFT OUTER JOIN mcf.mcf_currencies e ON(a.entered_crncy_id = e.crncy_id)
  WHERE a.bulk_trns_hdr_id = " . $dochdrID;
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return 0;
}

function get_One_BlkMiscTrnsSum($dochdrID) {
    $strSql = "SELECT SUM(CASE WHEN a.trns_type='WITHDRAWAL' THEN -1*a.amount*a.accnt_crncy_rate ELSE a.amount*a.accnt_crncy_rate END) amnt 
    FROM mcf.mcf_standing_order_misc a 
    LEFT OUTER JOIN mcf.mcf_accounts b ON (a.account_id = b.account_id)
    LEFT OUTER JOIN mcf.mcf_prdt_savings c ON (b.product_type_id = c.svngs_product_id)
    LEFT OUTER JOIN mcf.mcf_prdt_savings_stdevnt_accntn d ON (c.svngs_product_id = d.svngs_product_id) 
    LEFT OUTER JOIN mcf.mcf_currencies e ON(a.entered_crncy_id = e.crncy_id)
  WHERE a.bulk_trns_hdr_id = " . $dochdrID;
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return 0;
}

function createBatchTrnsHdr($trnsHdrID, $trnsNum, $cmmntDesc, $voidedTrnsHdrID, $trnsSiteID, $trnsAmnt, $destStoreID, $destCageID, $crncyID, $dfltItmState, $exchngRate, $rvrslRsn = "", $vmsOffctStaffPrsID = -1, $dfltMiscGlAcntID = -1, $shdDoCashless = 0) {
    global $usrID;
    global $orgID;
    global $gnrlTrnsDteYMDHMS;
    $dateStr = getDB_Date_time();
    $insSQL = "INSERT INTO mcf.mcf_bulk_trns_hdr(
            bulk_trns_hdr_id, batch_number, batch_date, branch_id, rmrk_cmmnt, 
            lnkd_person_id, ttl_amount, status, authorized_by_person_id, 
            autorization_date, approval_limit_id, voided_batch_id, reversal_reason, 
            org_id, dest_store_vault_id, dest_cage_shelve_id, dflt_item_state, 
            crncy_id, avrg_exchange_rate, created_by, creation_date, last_update_by, 
            last_update_date, dflt_gl_acnt_id, is_batch_cashless) " .
            "VALUES (" . $trnsHdrID . ", '" . loc_db_escape_string($trnsNum) .
            "','" . loc_db_escape_string($gnrlTrnsDteYMDHMS) .
            "', " . $trnsSiteID .
            ", '" . loc_db_escape_string($cmmntDesc) .
            "', " . $vmsOffctStaffPrsID .
            ", " . $trnsAmnt .
            ", 'Not Submitted', -1, '',-1, " .
            $voidedTrnsHdrID . ", '" . loc_db_escape_string($rvrslRsn) .
            "', " . $orgID .
            ", " . $destStoreID .
            ", " . $destCageID .
            ", '" . loc_db_escape_string($dfltItmState) .
            "', " . $crncyID .
            ", " . $exchngRate .
            ", " . $usrID . ", '" . $dateStr .
            "', " . $usrID . ", '" . $dateStr .
            "', " . $dfltMiscGlAcntID . ",'" . loc_db_escape_string($shdDoCashless) . "')";
    return execUpdtInsSQL($insSQL);
}

function updateBatchTrnsHdr($trnsHdrID, $trnsNum, $cmmntDesc, $voidedTrnsHdrID, $trnsSiteID, $trnsAmnt, $destStoreID, $destCageID, $crncyID, $dfltItmState, $exchngRate, $rvrslRsn = "", $vmsOffctStaffPrsID = -1, $dfltMiscGlAcntID = -1, $shdDoCashless = 0) {
    global $usrID;
    global $gnrlTrnsDteYMDHMS;
    $dateStr = getDB_Date_time();
    $selSQL = "Select count(1) from mcf.mcf_bulk_trns_hdr WHERE status IN ('Authorized','Initiated','Reviewed') and bulk_trns_hdr_id = " . $trnsHdrID;
    $result = executeSQLNoParams($selSQL);
    $trnsCnt = 0;
    while ($row = loc_db_fetch_array($result)) {
        $trnsCnt = $row[0];
    }
    if ($trnsCnt <= 0 && $trnsHdrID > 0) {
        $insSQL = "UPDATE mcf.mcf_bulk_trns_hdr
            SET batch_number='" . loc_db_escape_string($trnsNum) .
                "', batch_date='" . loc_db_escape_string($gnrlTrnsDteYMDHMS) .
                "', branch_id=" . $trnsSiteID .
                ", dflt_gl_acnt_id=" . $dfltMiscGlAcntID .
                ", rmrk_cmmnt='" . loc_db_escape_string($cmmntDesc) .
                "', lnkd_person_id=" . $vmsOffctStaffPrsID .
                ", ttl_amount=" . $trnsAmnt .
                ", dest_store_vault_id=" . $destStoreID .
                ", dest_cage_shelve_id=" . $destCageID .
                ", dflt_item_state='" . loc_db_escape_string($dfltItmState) .
                "', crncy_id=" . $crncyID .
                ", avrg_exchange_rate=" . $exchngRate .
                ", last_update_by=" . $usrID .
                ", last_update_date='" . $dateStr .
                "', is_batch_cashless='" . loc_db_escape_string($shdDoCashless) .
                "' WHERE(bulk_trns_hdr_id = " . $trnsHdrID . ")";
        return execUpdtInsSQL($insSQL);
    } else {
        return 0;
    }
}

function updateBatchTrnsVoidRsn($trnsHdrID, $cmmntDesc) {
    global $usrID;
    $dateStr = getDB_Date_time();
    $selSQL = "Select count(1) from mcf.mcf_bulk_trns_hdr WHERE status IN ('Authorized','Initiated','Reviewed') and bulk_trns_hdr_id = " . $trnsHdrID;
    $result = executeSQLNoParams($selSQL);
    $trnsCnt = 0;
    while ($row = loc_db_fetch_array($result)) {
        $trnsCnt = $row[0];
    }
    if ($trnsCnt <= 0 && $trnsHdrID > 0) {
        $insSQL = "UPDATE mcf.mcf_bulk_trns_hdr 
            SET reversal_reason='" . loc_db_escape_string($cmmntDesc) .
                "', last_update_by=" . $usrID .
                ", last_update_date='" . $dateStr .
                "' WHERE(bulk_trns_hdr_id = " . $trnsHdrID . ")";
        return execUpdtInsSQL($insSQL);
    } else {
        return 0;
    }
}

function deleteBatchTrnsHdr($pkeyID, $extrInfo = "") {
    $selSQL = "Select count(1) from mcf.mcf_bulk_trns_hdr WHERE status IN ('Authorized','Initiated','Reviewed') and bulk_trns_hdr_id = " . $pkeyID;
    $result = executeSQLNoParams($selSQL);
    $trnsCnt = 0;
    $affctd1 = 0;
    $affctd2 = 0;
    $affctd3 = 0;
    $affctd4 = 0;
    $affctd5 = 0;
    while ($row = loc_db_fetch_array($result)) {
        $trnsCnt = $row[0];
    }
    if ($trnsCnt <= 0) {
        $insSQL = "DELETE FROM mcf.mcf_account_trns_cash_analysis WHERE bulk_trns_hdr_id = " . $pkeyID . "";
        $affctd1 = execUpdtInsSQL($insSQL, "Trns Number:" . $extrInfo);
        $insSQL = "DELETE FROM mcf.mcf_cust_account_trns_cheques WHERE bulk_trns_hdr_id = " . $pkeyID;
        $affctd3 = execUpdtInsSQL($insSQL, "Trns Number:" . $extrInfo);
        $insSQL = "DELETE FROM mcf.mcf_cust_account_transactions WHERE bulk_trns_hdr_id = " . $pkeyID . "";
        $affctd2 = execUpdtInsSQL($insSQL, "Trns Number:" . $extrInfo);
        $insSQL = "DELETE FROM mcf.mcf_bulk_trns_hdr WHERE bulk_trns_hdr_id = " . $pkeyID . "";
        $affctd5 = execUpdtInsSQL($insSQL, "Trns Number:" . $extrInfo);
    }
    if ($affctd5 > 0) {
        $dsply = "Successfully Deleted the ff Records-";
        $dsply .= "<br/>$affctd1 Denomination(s)!";
        $dsply .= "<br/>$affctd3 Cheque(s)!";
        $dsply .= "<br/>$affctd2 Cash Transaction(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Deleted<br/>Cannot Delete Authorized Transactions!";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function get_OneCustAccntTrnsDet_OLD($pkeyID) {
    global $usrID;
    global $prsnid;
    global $canSeeOthrsTrns;
    global $gnrlTrnsDteYMDHMS;
    $whereCls = "";
    $balDte = substr($gnrlTrnsDteYMDHMS, 0, 10);
    /* if (!$canSeeOthrsTrns) {
      $whereCls .= " and (a.created_by=$usrID or a.last_update_by=$usrID or a.approved_by_prsn_id=$prsnid)";
      } */
    $strSql = "SELECT a.acct_trns_id, b.account_number, b.account_title, a.trns_date, b.currency_id, a.trns_type, " .
            "a.amount, COALESCE(a.status,'Not Submitted'), a.account_id, c.mapped_lov_crncy_id, gst.get_pssbl_val(c.mapped_lov_crncy_id) crncy_nm,"
            . "to_char(to_timestamp(a.trns_date, 'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS'), a.trns_no, "
            . "a.value_date, a.branch_id, a.doc_type, a.doc_no, a.trns_person_name, a.trns_person_tel_no, "
            . "a.trns_person_address, a.trns_person_id_type, a.trns_person_id_number, a.trns_person_type, a.status, "
            . "a.debit_or_credit, a.authorized_by_person_id, a.autorization_date, a.amount_cash, "
            . "a.voided_acct_trns_id, a.voided_trns_type, a.reversal_reason, a.description, a.created_by, "
            . "b.branch_id, prs.get_prsn_name(a.authorized_by_person_id), a.approval_limit_id, d.max_amount, 
              CASE WHEN (a.status NOT IN ('Authorized','Paid','Received','Void','Historic')) or a.status IS NULL THEN mcf.get_cstacnt_unclrd_bals(a.account_id, '" . $balDte . "') ELSE a.unclrdbal END, 
              CASE WHEN (a.status NOT IN ('Authorized','Paid','Received','Void','Historic')) or a.status IS NULL THEN mcf.get_cstacnt_avlbl_bals(a.account_id, '" . $balDte . "') ELSE a.clrdbal END, 
              b.account_type, 
              CASE WHEN a.acctstatus='' THEN b.account_status ELSE a.acctstatus END, 
              CASE WHEN a.acctcustomer='' THEN mcf.get_customer_name(b.cust_type, b.cust_id) ELSE a.acctcustomer END, 
                b.prsn_type_or_entity, a.acctlien, 
                CASE WHEN a.mandate='' THEN b.mandate ELSE a.mandate END, a.wtdrwllimitno, a.wtdrwllimitamt, a.wtdrwllimittype, 
                a.accnt_crncy_rate, a.trns_has_other_lines, a.disbmnt_hdr_id, a.disbmnt_det_id, a.sub_trns_type, a.loan_rpmnt_type  " .
            "FROM mcf.mcf_cust_account_transactions a "
            . "LEFT OUTER JOIN mcf.mcf_accounts b ON (a.account_id = b.account_id) "
            . "LEFT OUTER JOIN mcf.mcf_currencies c ON (b.currency_id = c.crncy_id) "
            . "LEFT OUTER JOIN vms.vms_authorizers_limit d ON (a.approval_limit_id = d.authorizer_limit_id) "
            . "LEFT OUTER JOIN mcf.mcf_prdt_savings e ON (e.svngs_product_id = b.product_type_id) "
            . "WHERE ((a.acct_trns_id = " . $pkeyID . ")$whereCls)";
    $result = executeSQLNoParams($strSql);
    //var_dump($strSql);
    return $result;
}

function get_OneCustAccntTrnsDet($pkeyID) {
    global $usrID;
    global $prsnid;
    global $canSeeOthrsTrns;
    global $gnrlTrnsDteYMDHMS;
    $whereCls = "";
    $balDte = substr($gnrlTrnsDteYMDHMS, 0, 10);

    /* if (!$canSeeOthrsTrns) {
      $whereCls .= " and (a.created_by=$usrID or a.last_update_by=$usrID or a.authorized_by_person_id=$prsnid)";
      } */
    $strSql = "SELECT a.acct_trns_id, b.account_number, b.account_title, a.trns_date, b.currency_id, a.trns_type, " .
            "a.amount, COALESCE(a.status,'Not Submitted'), a.account_id, c.mapped_lov_crncy_id, gst.get_pssbl_val(c.mapped_lov_crncy_id) crncy_nm,"
            . "to_char(to_timestamp(a.trns_date, 'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS'), a.trns_no, "
            . "a.value_date, a.branch_id, a.doc_type, a.doc_no, a.trns_person_name, a.trns_person_tel_no, "
            . "a.trns_person_address, a.trns_person_id_type, a.trns_person_id_number, a.trns_person_type, a.status, "
            . "a.debit_or_credit, a.authorized_by_person_id, a.autorization_date, a.amount_cash, "
            . "a.voided_acct_trns_id, a.voided_trns_type, a.reversal_reason, a.description, a.created_by, "
            . "b.branch_id, prs.get_prsn_name(a.authorized_by_person_id), a.approval_limit_id, d.max_amount, 
              CASE WHEN (a.status NOT IN ('Authorized','Paid','Received','Void','Historic')) or a.status IS NULL THEN mcf.get_cstacnt_unclrd_bals(a.account_id, '" . $balDte . "') ELSE a.unclrdbal END, 
              CASE WHEN (a.status NOT IN ('Authorized','Paid','Received','Void','Historic')) or a.status IS NULL THEN mcf.get_cstacnt_avlbl_bals(a.account_id, '" . $balDte . "') ELSE a.clrdbal END, 
              b.account_type, 
              CASE WHEN a.acctstatus='' THEN b.account_status ELSE a.acctstatus END, 
              CASE WHEN a.acctcustomer='' THEN mcf.get_customer_name(b.cust_type, b.cust_id) ELSE a.acctcustomer END, 
                b.prsn_type_or_entity, a.acctlien, 
                CASE WHEN a.mandate='' THEN b.mandate ELSE a.mandate END, a.wtdrwllimitno, a.wtdrwllimitamt, a.wtdrwllimittype, 
                a.accnt_crncy_rate, a.trns_has_other_lines, a.disbmnt_hdr_id, a.disbmnt_det_id, a.sub_trns_type, 
                a.loan_rpmnt_type, a.loan_rpmnt_src_acct_id, a.loan_rpmnt_src_amount, b.cust_id, 
                a.balancing_gl_accnt_id,
                accb.get_accnt_num(a.balancing_gl_accnt_id) || '.' || accb.get_accnt_name(a.balancing_gl_accnt_id) gl_acc_nm,
                mcf.shd_MiscBe_DbtOrDrdt(a.balancing_gl_accnt_id, a.trns_type) shdDbtCrdtGl " .
            "FROM mcf.mcf_cust_account_transactions a "
            . "LEFT OUTER JOIN mcf.mcf_accounts b ON (a.account_id = b.account_id) "
            . "LEFT OUTER JOIN mcf.mcf_currencies c ON (b.currency_id = c.crncy_id) "
            . "LEFT OUTER JOIN vms.vms_authorizers_limit d ON (a.approval_limit_id = d.authorizer_limit_id) "
            . "LEFT OUTER JOIN mcf.mcf_prdt_savings e ON (e.svngs_product_id = b.product_type_id) "
            . "WHERE ((a.acct_trns_id = " . $pkeyID . ")$whereCls)";
    $result = executeSQLNoParams($strSql);
    //var_dump($strSql);
    return $result;
}

function get_OneCustAccntHstry($pkeyID) {
    $whereCls = "";
    $balDte = substr(getStartOfDayYMD(), 0, 10);
    $strSql = "SELECT a.acct_trns_id, b.account_number, b.account_title, a.trns_date, b.currency_id, a.trns_type, " .
            "a.amount, COALESCE(a.status,'Not Submitted'), a.account_id, c.mapped_lov_crncy_id, gst.get_pssbl_val(c.mapped_lov_crncy_id) crncy_nm,"
            . "to_char(to_timestamp(a.trns_date, 'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS'), a.trns_no, "
            . "a.value_date, a.branch_id, a.doc_type, a.doc_no, a.trns_person_name, a.trns_person_tel_no, "
            . "a.trns_person_address, a.trns_person_id_type, a.trns_person_id_number, a.trns_person_type, a.status, "
            . "a.debit_or_credit, a.authorized_by_person_id, a.autorization_date, a.amount_cash, "
            . "a.voided_acct_trns_id, a.voided_trns_type, a.reversal_reason, a.description, a.created_by, "
            . "b.branch_id, prs.get_prsn_name(a.authorized_by_person_id), a.approval_limit_id, d.max_amount, 
              CASE WHEN (a.status NOT IN ('Authorized','Paid','Received','Void','Historic')) or a.status IS NULL THEN mcf.get_cstacnt_unclrd_bals(a.account_id, '" . $balDte . "') ELSE a.unclrdbal+mcf.get_trns_crnt_prtn(a.acct_trns_id) END, 
              CASE WHEN (a.status NOT IN ('Authorized','Paid','Received','Void','Historic')) or a.status IS NULL THEN mcf.get_cstacnt_avlbl_bals(a.account_id, '" . $balDte . "') ELSE a.clrdbal+mcf.get_trns_avlbl_prtn(a.acct_trns_id) END, 
              b.account_type, 
              CASE WHEN a.acctstatus='' THEN b.account_status ELSE a.acctstatus END, 
              CASE WHEN a.acctcustomer='' THEN mcf.get_customer_name(b.cust_type, b.cust_id) ELSE a.acctcustomer END, 
                b.prsn_type_or_entity, a.acctlien, 
                CASE WHEN a.mandate='' THEN b.mandate ELSE a.mandate END, a.wtdrwllimitno, a.wtdrwllimitamt, a.wtdrwllimittype,
                org.get_site_name(a.branch_id::integer) " .
            "FROM mcf.mcf_cust_account_transactions a "
            . "LEFT OUTER JOIN mcf.mcf_accounts b ON (a.account_id = b.account_id) "
            . "LEFT OUTER JOIN mcf.mcf_currencies c ON (b.currency_id = c.crncy_id) "
            . "LEFT OUTER JOIN vms.vms_authorizers_limit d ON (a.approval_limit_id = d.authorizer_limit_id) "
            . "LEFT OUTER JOIN mcf.mcf_prdt_savings e ON (e.svngs_product_id = b.product_type_id) "
            . "WHERE ((a.account_id = " . $pkeyID . ")$whereCls) ORDER BY a.trns_date DESC, a.acct_trns_id DESC LIMIT 10 OFFSET 0";
//echo $strSql;      
    $result = executeSQLNoParams($strSql);
    return $result;
}

function getHistoricAccountTrns($acctNo) {
    global $gnrlTrnsDteYMDHMS;
    $balDte = substr($gnrlTrnsDteYMDHMS, 0, 10);
    $strSql = "SELECT a.acct_trns_id, b.account_number, b.account_title, a.trns_date, b.currency_id, a.trns_type, " .
            "a.amount, COALESCE(a.status,'Not Submitted'), a.account_id, c.mapped_lov_crncy_id, gst.get_pssbl_val(c.mapped_lov_crncy_id) crncy_nm,"
            . "to_char(to_timestamp(a.trns_date, 'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS'), COALESCE(a.trns_no,''), "
            . "a.value_date, a.branch_id, a.doc_type, a.doc_no, a.trns_person_name, a.trns_person_tel_no, "
            . "a.trns_person_address, a.trns_person_id_type, a.trns_person_id_number, a.trns_person_type, a.status, "
            . "a.debit_or_credit, a.authorized_by_person_id, a.autorization_date, a.amount_cash, "
            . "a.voided_acct_trns_id, a.voided_trns_type, a.reversal_reason, a.description, a.created_by, "
            . "b.branch_id, COALESCE(prs.get_prsn_name(a.authorized_by_person_id),''), a.approval_limit_id, d.max_amount, 
              CASE WHEN (a.status NOT IN ('Authorized','Paid','Received','Void','Historic')) or a.status IS NULL THEN mcf.get_cstacnt_unclrd_bals(a.account_id, '" . $balDte . "') ELSE a.unclrdbal+mcf.get_trns_crnt_prtn(a.acct_trns_id) END, 
              CASE WHEN (a.status NOT IN ('Authorized','Paid','Received','Void','Historic')) or a.status IS NULL THEN mcf.get_cstacnt_avlbl_bals(a.account_id, '" . $balDte . "') ELSE a.clrdbal+mcf.get_trns_avlbl_prtn(a.acct_trns_id) END, 
              b.account_type, 
              CASE WHEN a.acctstatus='' THEN b.account_status ELSE a.acctstatus END, 
              CASE WHEN a.acctcustomer='' THEN mcf.get_customer_name(b.cust_type, b.cust_id) ELSE a.acctcustomer END, 
                b.prsn_type_or_entity, a.acctlien, 
                CASE WHEN a.mandate='' THEN b.mandate ELSE a.mandate END, a.wtdrwllimitno, a.wtdrwllimitamt, a.wtdrwllimittype,
                org.get_site_name(a.branch_id::integer)  " .
            "FROM mcf.mcf_cust_account_transactions a "
            . "LEFT OUTER JOIN mcf.mcf_accounts b ON (a.account_id = b.account_id) "
            . "LEFT OUTER JOIN mcf.mcf_currencies c ON (b.currency_id = c.crncy_id) "
            . "LEFT OUTER JOIN vms.vms_authorizers_limit d ON (a.approval_limit_id = d.authorizer_limit_id) "
            . "LEFT OUTER JOIN mcf.mcf_prdt_savings e ON (e.svngs_product_id = b.product_type_id) "
            . "WHERE ((b.account_number = '" . loc_db_escape_string($acctNo) .
            "')) ORDER BY a.trns_date DESC, a.acct_trns_id DESC LIMIT 10 OFFSET 0";
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_OneCustAccntInfo($pkeyID) {
    global $gnrlTrnsDteYMDHMS;
    $balDte = substr($gnrlTrnsDteYMDHMS, 0, 10);
    $strSql = "SELECT b.account_id, b.account_number, b.account_title, b.currency_id,"
            . " c.mapped_lov_crncy_id, gst.get_pssbl_val(c.mapped_lov_crncy_id) crncy_nm,"
            . "b.branch_id, mcf.get_cstacnt_unclrd_bals(b.account_id, '" . $balDte . "'), 
              mcf.get_cstacnt_avlbl_bals(b.account_id, '" . $balDte . "'), 
              b.account_type, b.account_status, mcf.get_customer_name(b.cust_type, b.cust_id), 
                b.prsn_type_or_entity, mcf.get_cstacnt_lien_bals(b.account_id, '" . $balDte . "'), 
                b.mandate, e.withdrawal_limit_no, e.withdrawal_limit_amount, e.withdrawal_limit, 
                mcf.get_cstacnt_unclrd_funds(b.account_id, '" . $balDte . "'),b.cust_type, b.cust_id " .
            "FROM mcf.mcf_accounts b "
            . "LEFT OUTER JOIN mcf.mcf_currencies c ON (b.currency_id = c.crncy_id) "
            . "LEFT OUTER JOIN mcf.mcf_prdt_savings e ON (e.svngs_product_id = b.product_type_id) "
            . "WHERE ((b.account_id = " . $pkeyID . "))";
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_CurrencyDenoms($crncyID) {
    $strSql = "SELECT crncy_denom_id, crncy_type, display_name, value, is_enabled, vault_item_id,"
            . "inv.get_invitm_name(vault_item_id) itmnm " .
            " FROM mcf.mcf_currency_denominations a " .
            " WHERE is_enabled = 'YES' AND crncy_id = " . $crncyID .
            " ORDER BY crncy_type desc, value desc";
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_TransCashAnalysis($acctTrnsId, $crncyID) {
    $strSql = "SELECT a.crncy_denom_id, a.crncy_type, a.display_name, a.value, a.is_enabled, "
            . "b.cash_analysis_id, NULLIF(b.qty,0), NULLIF((a.value * b.qty),0), 
            b.vault_id, b.cage_shelve_id, b.item_state, b.accnt_crncy_rate,
            (vms.get_ltst_stock_bals(b.vault_id, b.cage_shelve_id, a.vault_item_id::integer, b.item_state) * a.value) rnngbals " .
            " FROM mcf.mcf_currency_denominations a, mcf.mcf_account_trns_cash_analysis b " .
            " WHERE a.crncy_denom_id = b.denomination_id " .
            " AND a.is_enabled = 'YES' AND a.crncy_id = " . $crncyID .
            " AND b.acct_trns_id>0 AND b.acct_trns_id = $acctTrnsId " .
            " ORDER BY a.crncy_type desc, a.value desc";
    //echo $strSql;
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_TransCashAnalysisRO($acctTrnsId, $crncyID) {
    $strSql = "SELECT a.crncy_denom_id, a.crncy_type, a.display_name, a.value, a.is_enabled, "
            . "b.cash_analysis_id, NULLIF(b.qty,0), NULLIF((a.value * b.qty),0), 
            b.vault_id, b.cage_shelve_id, b.item_state, b.accnt_crncy_rate, 
            CASE WHEN mcf.get_trns_status(b.acct_trns_id) IN ('Received','Paid') THEN b.cage_balance_b4_trns ELSE (vms.get_ltst_stock_bals(b.vault_id, b.cage_shelve_id, a.vault_item_id::integer, b.item_state) * a.value) END " .
            " FROM mcf.mcf_currency_denominations a, mcf.mcf_account_trns_cash_analysis b " .
            " WHERE a.crncy_denom_id = b.denomination_id " .
            " AND a.is_enabled = 'YES' AND a.crncy_id = " . $crncyID .
            " AND b.acct_trns_id>0 AND b.acct_trns_id = $acctTrnsId AND COALESCE(b.qty, 0)!=0 " .
            " ORDER BY a.crncy_type desc, a.value desc";
    //echo $strSql;
    $result = executeSQLNoParams($strSql);
//echo $strSql;                                                 
    return $result;
}

function get_TransCashAnalysisTtlAmount($acctTrnsId, $crncyID) {
    $strSql = "SELECT sum((value * b.qty)) " .
            " FROM mcf.mcf_currency_denominations a, mcf.mcf_account_trns_cash_analysis b " .
            " WHERE a.crncy_denom_id = b.denomination_id " .
            " AND a.is_enabled = 'YES' AND crncy_id = " . $crncyID .
            " AND b.acct_trns_id>0 AND acct_trns_id = " . $acctTrnsId;

    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return 0;
}

function createCashBreakdown($orgid, $acctTrnsId, $currDenomID, $qty, $unitValue, $dateStr) {
    global $usrID;
//GENERATE CUST ACCOUNT
    $insSQL = "INSERT INTO mcf.mcf_account_trns_cash_analysis( " .
            "acct_trns_id, denomination_id, unit_value, " .
            "qty, created_by, creation_date, last_update_by, last_update_date)" .
            "VALUES ( " . $acctTrnsId . ", " . $currDenomID . "," . $unitValue . ", " . $qty . ", " . $orgid .
            ", " . $usrID . ", '" . $dateStr . "', " . $usrID . ", '" . $dateStr . "')";
//var_dump($insSQL);
    execUpdtInsSQL($insSQL);
}

function updateBlkCashBreakdown($trnsCashAnalysisID, $qty, $dateStr, $trnsCashRate = 1, $usedVltID = -1, $usedCageID = -1, $usedItmState = "Issuable", $sbmtdBatchHdrID = -1) {
    global $usrID;
//GENERATE CUST ACCOUNT
    $insSQL = "UPDATE mcf.mcf_account_trns_cash_analysis SET " .
            "qty = " . $qty .
            ", last_update_by = $usrID" .
            ", last_update_date ='" . $dateStr .
            "', accnt_crncy_rate = " . $trnsCashRate .
            ", vault_id=" . $usedVltID .
            ", cage_shelve_id=" . $usedCageID .
            ", item_state='" . loc_db_escape_string($usedItmState) .
            "', bulk_trns_hdr_id=" . $sbmtdBatchHdrID .
            " WHERE cash_analysis_id = $trnsCashAnalysisID and acct_trns_id<=0 and bulk_trns_hdr_id>0";
//var_dump($insSQL);
    return execUpdtInsSQL($insSQL);
}

function updateCashBreakdown($trnsCashAnalysisID, $qty, $dateStr, $trnsCashRate = 1, $usedVltID = -1, $usedCageID = -1, $usedItmState = "Issuable") {
    global $usrID;
//GENERATE CUST ACCOUNT
    $insSQL = "UPDATE mcf.mcf_account_trns_cash_analysis SET " .
            "qty = " . $qty .
            ", last_update_by = $usrID" .
            ", last_update_date ='" . $dateStr .
            "', accnt_crncy_rate = " . $trnsCashRate .
            ", vault_id=" . $usedVltID .
            ", cage_shelve_id=" . $usedCageID .
            ", item_state='" . loc_db_escape_string($usedItmState) .
            "' WHERE cash_analysis_id = " . $trnsCashAnalysisID . " and bulk_trns_hdr_id<=0";
//var_dump($insSQL);
    return execUpdtInsSQL($insSQL);
}

function checkExstncOfCashDenomForTrnsID($trnsCashAnalysisID) {
    $strSql = "SELECT count(1) " .
            "FROM mcf.mcf_account_trns_cash_analysis b "
            . "WHERE cash_analysis_id = $trnsCashAnalysisID";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return 0;
}

function createInitAccountTrns($acctID, $dateStr, $trnsType) {
    global $usrID;
    global $prsnid;
    global $trnsTypeABRV;
    global $trnsTypes;
    global $gnrlTrnsDteYMDHMS;

//GENERATE CUST ACCOUNT    
    $dte = date('ymdHis');
    $usrTrnsCode = getGnrlRecNm("sec.sec_users", "user_id", "code_for_trns_nums", $usrID);
    $gnrtdTrnsNo = $trnsTypeABRV[findArryIdx($trnsTypes, $trnsType)] . "-" . $usrTrnsCode . "-" . $dte;
    $insSQL = "INSERT INTO mcf.mcf_cust_account_transactions( " .
            "account_id, trns_date, trns_type, branch_id, amount, debit_or_credit, "
            . "created_by, creation_date, last_update_by, last_update_date, trns_no)" .
            "VALUES(" . $acctID . ",'" . $gnrlTrnsDteYMDHMS . "','$trnsType',(SELECT pasn.get_prsn_siteid($prsnid)), 0.00,'DR'," .
            $usrID . ",'" . $dateStr . "'," . $usrID . ",'" . $dateStr . "', '" . loc_db_escape_string($gnrtdTrnsNo) . "')";
//var_dump($insSQL);
    execUpdtInsSQL($insSQL);
}

function getInitAccountTrnsID($acctID, $dateStr) {
    global $usrID;

    $strSql = "SELECT acct_trns_id FROM mcf.mcf_cust_account_transactions WHERE account_id = $acctID AND "
            . "creation_date = '" . $dateStr . "' AND created_by = $usrID";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return (int) $row[0];
    }
    return -1;
}

function checkExstncOfCashBrkdwnForTrnsID($acctTrnsId, $orgID) {
    $strSql = "SELECT count(1) " .
            "FROM mcf.mcf_currency_denominations a, mcf.mcf_account_trns_cash_analysis b "
            . "WHERE a.crncy_denom_id = b.denomination_id  AND b.acct_trns_id>0 AND acct_trns_id = $acctTrnsId AND (a.org_id = $orgID)";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return 0;
}

function createCashBreakdownInit($acctTrnsId, $crncyID, $vltID = -1, $cageID = -1, $itmState = "Issuable") {
    global $usrID;
    $dateStr = getDB_Date_time();
//GENERATE CUST ACCOUNT

    $insSQL = "INSERT INTO mcf.mcf_account_trns_cash_analysis(
                                                                    acct_trns_id, denomination_id, unit_value, 
                                                                    qty, created_by, creation_date, last_update_by, last_update_date, 
                                                                    vault_id, cage_shelve_id, item_state, accnt_crncy_rate) " .
            " SELECT $acctTrnsId, crncy_denom_id, value, 0, $usrID, '$dateStr', $usrID, "
            . "'$dateStr', $vltID, $cageID, '" . loc_db_escape_string($itmState) . "',1 "
            . "FROM mcf.mcf_currency_denominations WHERE is_enabled = 'YES' AND crncy_id = $crncyID ORDER BY value";
//echo $insSQL;
    execUpdtInsSQL($insSQL);
}

function voidCashBreakdownAnlsys($oldAcctTrnsId, $nwAcctTrnsId) {
    global $usrID;
    $dateStr = getDB_Date_time();
//GENERATE CUST ACCOUNT

    $insSQL = "INSERT INTO mcf.mcf_account_trns_cash_analysis(
            acct_trns_id, denomination_id, unit_value, 
            qty, created_by, creation_date, last_update_by, last_update_date, 
            vault_id, cage_shelve_id, item_state, accnt_crncy_rate) " .
            " SELECT $nwAcctTrnsId, denomination_id, unit_value, -1*qty , $usrID, '$dateStr', $usrID, '$dateStr', "
            . "vault_id, cage_shelve_id, item_state,accnt_crncy_rate "
            . "FROM mcf.mcf_account_trns_cash_analysis WHERE acct_trns_id = $oldAcctTrnsId and acct_trns_id>0 ORDER BY unit_value";
//var_dump($insSQL);
    execUpdtInsSQL($insSQL);
}

function getNewAccountTrnsID() {
    $sqlStr = "select nextval('mcf.mcf_cust_account_transactions_acct_trns_id_seq'::regclass);";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -1;
}

function createAccountTrns($acctID, $dateStr, $docType, $docNo, $desc, $dbOrCrdt, $trnsAmnt, $trnsType, $cashAmount = 0, $trnsPrsnType = "Self", $trnsPrsnNm = "", $trnsPrsnTel = "", $trnsPrsnAddrs = "", $trnsPrsnIDType = "", $trnsPrsnIDNo = "", $trnsNo = "", $status = "Not Submitted", $voidedTrnsID = -1, $voidedTrnsType = "", $rvrsldesc = "", $entrdCrncyID = -1, $accntCurRate = 1, $acctstatus = "", $acctcustomer = "", $acctlien = 0, $mandate = "", $wtdrwllimitno = -1, $wtdrwllimitamt = 0, $wtdrwllimittype = "", $trns_has_other_lines = "0", $disbmntHdrID = -1, $disbmntDetID = -1, $subDocType = "", $lnkdChqTrnsID = -1, $lnkdOrdExctnID = -1, $lnkdMscTrnsID = -1, $loanRepayType = "", $loanRpmntSrcAcctID = -1, $loanRpmntSrcAmnt = 0.00, $bulkTrnsHdrID = -1, $miscTrnsBalsGlAcntID = -1) {
    global $usrID;
    global $prsnid;
    global $orgID;
    global $gnrlTrnsDteYMDHMS;
    $insSQL = "INSERT INTO mcf.mcf_cust_account_transactions(
            trns_date, account_id, trns_type, description, 
            amount, value_date, branch_id, doc_no, trns_person_name, trns_person_tel_no, 
            trns_person_address, trns_person_id_type, trns_person_id_number, 
            trns_person_type, created_by, creation_date, last_update_by, 
            last_update_date, org_id, status, doc_type, debit_or_credit, 
            authorized_by_person_id, autorization_date, trns_no, amount_cash, 
            voided_acct_trns_id, voided_trns_type, reversal_reason, approval_limit_id, 
            unclrdbal, clrdbal, acctstatus, acctcustomer, acctlien, mandate, 
            wtdrwllimitno, wtdrwllimitamt, wtdrwllimittype, entered_crncy_id, 
            accnt_crncy_rate,trns_has_other_lines, disbmnt_hdr_id, disbmnt_det_id, 
            sub_trns_type, lnkd_chq_trns_id, lnkd_ordr_exctn_id, lnkd_mscl_trns_id,
            loan_rpmnt_type, loan_rpmnt_src_acct_id, loan_rpmnt_src_amount, bulk_trns_hdr_id, balancing_gl_accnt_id) " .
            "VALUES('" . $gnrlTrnsDteYMDHMS .
            "', " . $acctID .
            ",'" . loc_db_escape_string($trnsType) .
            "','" . loc_db_escape_string($desc) .
            "'," . $trnsAmnt .
            ",'" . substr($gnrlTrnsDteYMDHMS, 0, 10) .
            "', (SELECT pasn.get_prsn_siteid($prsnid))" .
            ", '" . loc_db_escape_string($docNo) .
            "', '" . loc_db_escape_string($trnsPrsnNm) .
            "', '" . loc_db_escape_string($trnsPrsnTel) .
            "', '" . loc_db_escape_string($trnsPrsnAddrs) .
            "', '" . loc_db_escape_string($trnsPrsnIDType) .
            "', '" . loc_db_escape_string($trnsPrsnIDNo) .
            "', '" . loc_db_escape_string($trnsPrsnType) .
            "'," . $usrID .
            ",'" . $dateStr .
            "'," . $usrID .
            ",'" . $dateStr .
            "'," . $orgID .
            ", '" . loc_db_escape_string($status) .
            "', '" . loc_db_escape_string($docType) .
            "', '" . loc_db_escape_string($dbOrCrdt) .
            "',-1,'', '" . loc_db_escape_string($trnsNo) .
            "'," . $cashAmount .
            "," . $voidedTrnsID .
            ", '" . loc_db_escape_string($voidedTrnsType) .
            "', '" . loc_db_escape_string($rvrsldesc) .
            "',-1, mcf.get_cstacnt_unclrd_bals(" . $acctID . ",'" . substr($gnrlTrnsDteYMDHMS, 0, 10) . "')" .
            ", mcf.get_cstacnt_avlbl_bals(" . $acctID . ",'" . substr($gnrlTrnsDteYMDHMS, 0, 10) . "')" .
            ", '" . loc_db_escape_string($acctstatus) .
            "', '" . loc_db_escape_string($acctcustomer) .
            "'," . $acctlien .
            ", '" . loc_db_escape_string($mandate) .
            "'," . $wtdrwllimitno .
            "," . $wtdrwllimitamt .
            ", '" . loc_db_escape_string($wtdrwllimittype) .
            "'," . $entrdCrncyID .
            "," . $accntCurRate .
            ",'" . $trns_has_other_lines .
            "'," . $disbmntHdrID .
            "," . $disbmntDetID . ", '" . loc_db_escape_string($subDocType) .
            "'," . $lnkdChqTrnsID . "," . $lnkdOrdExctnID . "," . $lnkdMscTrnsID . ",'$loanRepayType',"
            . "$loanRpmntSrcAcctID, $loanRpmntSrcAmnt, $bulkTrnsHdrID, $miscTrnsBalsGlAcntID)";
    return execUpdtInsSQL($insSQL);
}

function updateAccountTrns($acctID, $dateStr, $docType, $docNo, $desc, $dbOrCrdt, $trnsAmnt, $acctTrnsId, $cashAmount = 0, $trnsPrsnType = "Self", $trnsPrsnNm = "", $trnsPrsnTel = "", $trnsPrsnAddrs = "", $trnsPrsnIDType = "", $trnsPrsnIDNo = "", $status = "Not Submitted", $rvrsldesc = "", $entrdCrncyID = -1, $accntCurRate = 1, $acctstatus = "", $acctcustomer = "", $acctlien = 0, $mandate = "", $wtdrwllimitno = -1, $wtdrwllimitamt = 0, $wtdrwllimittype = "", $trnsNo = "", $disbmntHdrID = -1, $disbmntDetID = -1, $subDocType = "", $lnkdChqTrnsID = -1, $lnkdOrdExctnID = -1, $lnkdMscTrnsID = -1, $loanRepayType = "", $loanRpmntSrcAcctID = -1, $loanRpmntSrcAmnt = 0.00, $bulkTrnsHdrID = -1, $trnsType = "", $miscTrnsBalsGlAcntID = -1) {
    global $usrID;
    global $prsnid;
    global $orgID;
    global $gnrlTrnsDteYMDHMS;
    /* , trns_no = '" . loc_db_escape_string($trnsNo) .
      "', status='" . loc_db_escape_string($status) .
      "' */
    $insSQL = "UPDATE mcf.mcf_cust_account_transactions
            SET  trns_date='" . $gnrlTrnsDteYMDHMS .
            "', account_id=" . $acctID . ", description='" . loc_db_escape_string($desc) .
            "', amount=" . $trnsAmnt .
            ", value_date='" . substr($gnrlTrnsDteYMDHMS, 0, 10) .
            "', branch_id=(SELECT pasn.get_prsn_siteid($prsnid)), " .
            "org_id=" . $orgID . ", " .
            "doc_no='" . loc_db_escape_string($docNo) .
            "', trns_person_name='" . loc_db_escape_string($trnsPrsnNm) .
            "', trns_person_tel_no='" . loc_db_escape_string($trnsPrsnTel) .
            "', trns_person_address='" . loc_db_escape_string($trnsPrsnAddrs) .
            "', trns_person_id_type='" . loc_db_escape_string($trnsPrsnIDType) .
            "', trns_person_id_number='" . loc_db_escape_string($trnsPrsnIDNo) .
            "', trns_person_type='" . loc_db_escape_string($trnsPrsnType) .
            "', last_update_by=" . $usrID . ", 
                last_update_date='" . $dateStr . "', doc_type='" . loc_db_escape_string($docType) . "', 
                debit_or_credit='" . loc_db_escape_string($dbOrCrdt) . "', 
                amount_cash=" . $cashAmount . ",  
                reversal_reason='" . loc_db_escape_string($rvrsldesc) . "', 
                unclrdbal=mcf.get_cstacnt_unclrd_bals(" . $acctID . ",'" . substr($gnrlTrnsDteYMDHMS, 0, 10) . "'),
                clrdbal=mcf.get_cstacnt_avlbl_bals(" . $acctID . ",'" . substr($gnrlTrnsDteYMDHMS, 0, 10) . "'),
                acctstatus='" . loc_db_escape_string($acctstatus) . "', 
                acctcustomer='" . loc_db_escape_string($acctcustomer) . "', 
                acctlien=" . $acctlien . ", 
                mandate='" . loc_db_escape_string($mandate) . "', 
                wtdrwllimitno=" . $wtdrwllimitno . ", 
                wtdrwllimitamt=" . $wtdrwllimitamt . ", 
                wtdrwllimittype='" . loc_db_escape_string($wtdrwllimittype) . "',
                disbmnt_hdr_id = " . $disbmntHdrID . ",
                disbmnt_det_id = " . $disbmntDetID .
            ", sub_trns_type='" . loc_db_escape_string($subDocType) .
            "', loan_rpmnt_type='" . $loanRepayType .
            "', loan_rpmnt_src_acct_id=" . $loanRpmntSrcAcctID .
            ", loan_rpmnt_src_amount=" . $loanRpmntSrcAmnt .
            ", bulk_trns_hdr_id=" . $bulkTrnsHdrID .
            ", balancing_gl_accnt_id=" . $miscTrnsBalsGlAcntID;
    /* , lnkd_chq_trns_id=" . $lnkdChqTrnsID .
      ", lnkd_ordr_exctn_id=" . $lnkdOrdExctnID .
      ", lnkd_mscl_trns_id=" . $lnkdMscTrnsID; */
    if ($entrdCrncyID > 0 && $accntCurRate != 0) {
        $insSQL .= ", entered_crncy_id=" . $entrdCrncyID . ", accnt_crncy_rate=" . $accntCurRate;
    }
    if ($trnsType != "") {
        $insSQL .= ", trns_type='" . loc_db_escape_string($trnsType) . "'";
    }
    $insSQL .= " WHERE acct_trns_id = " . $acctTrnsId . " and (status NOT IN ('Authorized', 'Paid', 'Received','Initiated','Void','Historic') and processing_ongoing='0')";
    //echo $insSQL;
    return execUpdtInsSQL($insSQL);
}

function createAccountTrnsImprt($acctID, $trnsDteStr, $docType, $docNo, $desc, $dbOrCrdt, $trnsAmnt, $trnsType, $cashAmount = 0, $trnsPrsnType = "Self", $trnsPrsnNm = "", $trnsPrsnTel = "", $trnsPrsnAddrs = "", $trnsPrsnIDType = "", $trnsPrsnIDNo = "", $trnsNo = "", $status = "Not Submitted", $voidedTrnsID = -1, $voidedTrnsType = "", $rvrsldesc = "", $entrdCrncyID = -1, $accntCurRate = 1, $acctstatus = "", $acctcustomer = "", $acctlien = 0, $mandate = "", $wtdrwllimitno = -1, $wtdrwllimitamt = 0, $wtdrwllimittype = "", $trns_has_other_lines = "0", $dateStr = "", $disbmntHdrID = -1, $disbmntDetID = -1, $subDocType = "", $lnkdChqTrnsID = -1, $lnkdOrdExctnID = -1, $lnkdMscTrnsID = -1, $loanRepayType = "", $loanRpmntSrcAcctID = -1, $loanRpmntSrcAmnt = 0.00, $bulkTrnsHdrID = -1, $miscTrnsBalsGlAcntID = -1, $crntBals = 0, $avlblBals = 0) {
    global $usrID;
    global $prsnid;
    global $orgID;
    $strUpdt = "";
    if ($avlblBals == 0 && $crntBals == 0) {
        $strUpdt = ", mcf.get_cstacnt_unclrd_bals(" . $acctID . ",'" . substr($trnsDteStr, 0, 10) . "')" .
                ", mcf.get_cstacnt_avlbl_bals(" . $acctID . ",'" . substr($trnsDteStr, 0, 10) . "')";
    } else {
        $strUpdt = ", " . $crntBals . "" .
                ", " . $avlblBals . "";
    }
    $insSQL = "INSERT INTO mcf.mcf_cust_account_transactions(
            trns_date, account_id, trns_type, description, 
            amount, value_date, branch_id, doc_no, trns_person_name, trns_person_tel_no, 
            trns_person_address, trns_person_id_type, trns_person_id_number, 
            trns_person_type, created_by, creation_date, last_update_by, 
            last_update_date, org_id, status, doc_type, debit_or_credit, 
            authorized_by_person_id, autorization_date, trns_no, amount_cash, 
            voided_acct_trns_id, voided_trns_type, reversal_reason, approval_limit_id, 
            unclrdbal, clrdbal, acctstatus, acctcustomer, acctlien, mandate, 
            wtdrwllimitno, wtdrwllimitamt, wtdrwllimittype, entered_crncy_id, 
            accnt_crncy_rate,trns_has_other_lines, disbmnt_hdr_id, disbmnt_det_id, 
            sub_trns_type, lnkd_chq_trns_id, lnkd_ordr_exctn_id, lnkd_mscl_trns_id,
            loan_rpmnt_type, loan_rpmnt_src_acct_id, loan_rpmnt_src_amount, bulk_trns_hdr_id, balancing_gl_accnt_id) " .
            "VALUES('" . $trnsDteStr .
            "', " . $acctID .
            ",'" . loc_db_escape_string($trnsType) .
            "','" . loc_db_escape_string($desc) .
            "'," . $trnsAmnt .
            ",'" . substr($trnsDteStr, 0, 10) .
            "', (SELECT pasn.get_prsn_siteid($prsnid))" .
            ", '" . loc_db_escape_string($docNo) .
            "', '" . loc_db_escape_string($trnsPrsnNm) .
            "', '" . loc_db_escape_string($trnsPrsnTel) .
            "', '" . loc_db_escape_string($trnsPrsnAddrs) .
            "', '" . loc_db_escape_string($trnsPrsnIDType) .
            "', '" . loc_db_escape_string($trnsPrsnIDNo) .
            "', '" . loc_db_escape_string($trnsPrsnType) .
            "'," . $usrID .
            ",'" . $dateStr .
            "'," . $usrID .
            ",'" . $dateStr .
            "'," . $orgID .
            ", '" . loc_db_escape_string($status) .
            "', '" . loc_db_escape_string($docType) .
            "', '" . loc_db_escape_string($dbOrCrdt) .
            "',-1,'', '" . loc_db_escape_string($trnsNo) .
            "'," . $cashAmount .
            "," . $voidedTrnsID .
            ", '" . loc_db_escape_string($voidedTrnsType) .
            "', '" . loc_db_escape_string($rvrsldesc) .
            "',-1" . $strUpdt .
            ", '" . loc_db_escape_string($acctstatus) .
            "', '" . loc_db_escape_string($acctcustomer) .
            "'," . $acctlien .
            ", '" . loc_db_escape_string($mandate) .
            "'," . $wtdrwllimitno .
            "," . $wtdrwllimitamt .
            ", '" . loc_db_escape_string($wtdrwllimittype) .
            "'," . $entrdCrncyID .
            "," . $accntCurRate .
            ",'" . $trns_has_other_lines .
            "'," . $disbmntHdrID .
            "," . $disbmntDetID . ", '" . loc_db_escape_string($subDocType) .
            "'," . $lnkdChqTrnsID . "," . $lnkdOrdExctnID . "," . $lnkdMscTrnsID . ",'$loanRepayType',"
            . "$loanRpmntSrcAcctID, $loanRpmntSrcAmnt, $bulkTrnsHdrID, $miscTrnsBalsGlAcntID)";
    return execUpdtInsSQL($insSQL);
}

function createAccountTransationImprt($acctID, $trnsType, $trnsDesc, $status, $trnsAmnt, $crncyID, $dateStr, $trnsTme, $crntBals = 0, $avlblBals = 0) {
    global $usrID;
    global $orgID;
    global $trnsTypes;
    global $trnsTypeABRV;

    $docType = "Paperless";
    $trnsHasOtherLines = "0";
    $dte = date('ymdHis');
    $usrTrnsCode = getGnrlRecNm("sec.sec_users", "user_id", "code_for_trns_nums", $usrID);
    $gnrtdTrnsNo = $trnsTypeABRV[findArryIdx($trnsTypes, $trnsType)] . "-" . $usrTrnsCode . "-" . $dte . "-" . getRandomNum(100, 999);
    $docNum1 = $gnrtdTrnsNo;
    $dbOrCrdt = "DR";
    if ($trnsType == "DEPOSIT") {
        $dbOrCrdt = "CR";
    }
    $voidedTrnsID = -1;
    $voidedTrnsType = "";
    $rvrsldesc = "";
    $accntCurRate = 1;

    $acctstatus = "";
    $acctcustomer = "";
    $acctlien = 0;
    $mandate = "";
    $wtdrwllimitno = -1;
    $wtdrwllimitamt = 0;
    $wtdrwllimittype = "";

    $isSelf = "Self";
    $recCnt = 0;

    if ($acctID > 0) {
        $acntRslt = get_OneCustAccntInfo($acctID);
        while ($rowAcnt = loc_db_fetch_array($acntRslt)) {
            $acctstatus = $rowAcnt[10];
            $acctcustomer = $rowAcnt[11];
            $acctlien = (float) $rowAcnt[13];
            $mandate = $rowAcnt[14];
            $wtdrwllimitno = (int) $rowAcnt[15];
            $wtdrwllimitamt = (float) $rowAcnt[16];
            $wtdrwllimittype = $rowAcnt[17];
        }
        $recCnt = createAccountTrnsImprt($acctID, $trnsTme, $docType, $docNum1, $trnsDesc, $dbOrCrdt, $trnsAmnt, $trnsType, $trnsAmnt, $isSelf, "", "", "", "", "", $gnrtdTrnsNo, $status, $voidedTrnsID, $voidedTrnsType, $rvrsldesc, $crncyID, $accntCurRate, $acctstatus, $acctcustomer, $acctlien, $mandate, $wtdrwllimitno, $wtdrwllimitamt, $wtdrwllimittype, $trnsHasOtherLines, $dateStr, -1, -1, "", -1, -1, -1, "", -1, 0.00, -1, -1, $crntBals, $avlblBals);
    }
    return $recCnt;
}

function createBulkMiscTrnsImprt($dstLineID, $lnTrnsType, $lnDstAcNo, $lnDstAmnt, $lnDstGLAcntID, $lnDstRate, $lnDstRmrks, $lnDstCrncyNm, $dateStr, $sbmtdBatchHdrID) {
    global $orgID;
    $afftctd2 = 0;
    $lnDstAcID = getCustAcctIdDtaImprt($lnDstAcNo, $orgID);
    $lnDstCrncyID = getPssblValID($lnDstCrncyNm, getLovID("Currencies"));
    $entrdLnCrncyID = getGnrlRecNm("mcf.mcf_currencies", "mapped_lov_crncy_id", "crncy_id", $lnDstCrncyID);
    if ($dstLineID <= 0 && $lnDstAmnt > 0) {
        //Insert
        $afftctd2 += createOrderMiscTrns(-1, $lnTrnsType, $lnDstAcID, $lnDstRmrks, $lnDstAmnt, $entrdLnCrncyID, $lnDstRate, "MISC_TRANS", $lnDstGLAcntID, $dateStr, $sbmtdBatchHdrID);
    } else if ($dstLineID > 0 && $lnDstAmnt > 0) {
        $afftctd2 += updateOrderMiscTrns($dstLineID, -1, $lnTrnsType, $lnDstAcID, $lnDstRmrks, $lnDstAmnt, $entrdLnCrncyID, $lnDstRate, "MISC_TRANS", $lnDstGLAcntID, $dateStr, $sbmtdBatchHdrID);
    }
    return $afftctd2;
}

function updateAccountTrnsVoidRsn($acctTrnsId, $rvrsldesc, $dateStr) {
    global $usrID;
    $insSQL = "UPDATE mcf.mcf_cust_account_transactions
            SET last_update_by=" . $usrID . ", 
                last_update_date='" . $dateStr . "', 
                status='Not Submitted', 
                reversal_reason='" . loc_db_escape_string($rvrsldesc) .
            "' WHERE acct_trns_id = " . $acctTrnsId;
    execUpdtInsSQL($insSQL);
}

function createAccountTrnsSignatories($acctTrnsId, $acctSignId, $dateStr) {
    global $usrID;
    $insSQL = "INSERT INTO mcf.mcf_account_trns_signatories( " .
            "acct_trns_id, acct_sign_id, created_by, creation_date, last_update_by, last_update_date)" .
            "VALUES(" . $acctTrnsId . "," . $acctSignId . "," .
            $usrID . ",'" . $dateStr . "'," . $usrID . ",'" . $dateStr . "')";
    execUpdtInsSQL($insSQL);
}

function createAccountTrnsChqs($acctTrnsId, $chqTypeID, $bnkID, $brnchID, $chqNo, $chqDte, $chqVal, $chqValDte, $dateStr, $chqType, $chqCrncyID, $acntCurRate, $chqSrcAcntID, $chqMandate = "", $sbmtdBatchHdrID = -1) {
    global $usrID;
    execUpdtInsSQL("UPDATE mcf.mcf_cust_account_trns_cheques "
            . "SET cheque_date=to_char(to_timestamp(cheque_date,'DD-Mon-YYYY'),'YYYY-MM-DD'), "
            . "value_date=to_char(to_timestamp(value_date,'DD-Mon-YYYY'),'YYYY-MM-DD')  
            WHERE length(cheque_date)>10 and length(value_date)>10");


    $insSQL = "INSERT INTO mcf.mcf_cust_account_trns_cheques(
            acct_trns_id, cheque_bank_id, cheque_branch_id, 
            cheque_no, amount, created_by, creation_date, last_update_by, 
            last_update_date, value_date, cheque_type, cheque_date, cheque_type_id, 
            cheque_crncy_id, accnt_crncy_rate, house_chq_src_accnt_id, is_cleared, 
            date_cleared, src_accnt_trns_id, cheque_mandate, bulk_trns_hdr_id)
                VALUES(" . $acctTrnsId . "," . $bnkID . "," . $brnchID . ",'" . loc_db_escape_string($chqNo) . "'," . $chqVal . "," .
            $usrID . ",'" . $dateStr . "'," . $usrID . ",'" . $dateStr . "', '" . $chqValDte .
            "', '" . loc_db_escape_string($chqType) . "', '" . $chqDte .
            "', " . $chqTypeID . ", " . $chqCrncyID . ", " . $acntCurRate . "," . $chqSrcAcntID .
            ",'0','',-1, '" . loc_db_escape_string($chqMandate) . "'," . $sbmtdBatchHdrID . ")";
    return execUpdtInsSQL($insSQL);
}

function updateAccountTrnsChqs($TrnsLnId, $chqTypeID, $bnkID, $brnchID, $chqNo, $chqDte, $chqVal, $chqValDte, $dateStr, $chqType, $chqCrncyID, $acntCurRate, $chqSrcAcntID, $chqMandate = "", $sbmtdBatchHdrID = -1) {
    global $usrID;
    execUpdtInsSQL("UPDATE mcf.mcf_cust_account_trns_cheques "
            . "SET cheque_date=to_char(to_timestamp(cheque_date,'DD-Mon-YYYY'),'YYYY-MM-DD'), "
            . "value_date=to_char(to_timestamp(value_date,'DD-Mon-YYYY'),'YYYY-MM-DD')  
            WHERE length(cheque_date)>10 and length(value_date)>10");


    $insSQL = "UPDATE mcf.mcf_cust_account_trns_cheques
        SET cheque_bank_id=" . $bnkID .
            ", cheque_branch_id=" . $brnchID .
            ", cheque_no='" . loc_db_escape_string($chqNo) .
            "', amount=" . $chqVal .
            ", last_update_by=" . $usrID .
            ", last_update_date='" . $dateStr .
            "', value_date='" . $chqValDte .
            "', cheque_type='" . loc_db_escape_string($chqType) .
            "', cheque_date='" . $chqDte .
            "', cheque_type_id=" . $chqTypeID .
            ", cheque_crncy_id=" . $chqCrncyID .
            ", accnt_crncy_rate=" . $acntCurRate .
            ", house_chq_src_accnt_id=" . $chqSrcAcntID .
            ", cheque_mandate='" . loc_db_escape_string($chqMandate) . "', bulk_trns_hdr_id=" . $sbmtdBatchHdrID .
            " WHERE trns_cheque_id=" . $TrnsLnId . "";
    return execUpdtInsSQL($insSQL);
}

function voidAccountTrnsChqs($oldAcctTrnsId, $newAcctTrnsId, $dateStr) {
    global $usrID;
    $insSQL = "INSERT INTO mcf.mcf_cust_account_trns_cheques(
            acct_trns_id, cheque_bank_id, cheque_branch_id, 
            cheque_no, amount, created_by, creation_date, last_update_by, 
            last_update_date, value_date, cheque_type, cheque_date, cheque_type_id, 
            cheque_crncy_id, accnt_crncy_rate, house_chq_src_accnt_id, is_cleared, 
            date_cleared, src_accnt_trns_id, cheque_mandate)
               SELECT " . $newAcctTrnsId . ",cheque_bank_id,cheque_branch_id,cheque_no,-1*amount," .
            $usrID . ",'" . $dateStr . "'," . $usrID . ",'" . $dateStr . "', value_date, cheque_type, "
            . "cheque_date, cheque_type_id, cheque_crncy_id, accnt_crncy_rate,house_chq_src_accnt_id, '0', 
            '', -1, cheque_mandate "
            . "FROM mcf.mcf_cust_account_trns_cheques "
            . "WHERE acct_trns_id>0 and acct_trns_id=" . $oldAcctTrnsId;
    execUpdtInsSQL($insSQL);
}

function voidAccountTrnsSgntries($oldAcctTrnsId, $newAcctTrnsId, $dateStr) {
    global $usrID;
    $insSQL = "INSERT INTO mcf.mcf_account_trns_signatories(
            acct_trns_id, acct_sign_id, created_by, creation_date, 
            last_update_by, last_update_date)
               SELECT " . $newAcctTrnsId . ",acct_sign_id," .
            $usrID . ",'" . $dateStr . "'," . $usrID . ",'" . $dateStr . "' "
            . "FROM mcf.mcf_account_trns_signatories "
            . "WHERE acct_trns_id=" . $oldAcctTrnsId;
    execUpdtInsSQL($insSQL);
}

function getAccountTrnsChqs($acctTrnsId, $trnsChqID = -1) {
    $whrCls = "";
    if ($trnsChqID > 0) {
        $whrCls = " and a.trns_cheque_id=" . $trnsChqID;
    }
    $selSQL = "SELECT a.trns_cheque_id, 
                    a.acct_trns_id, 
                    a.cheque_bank_id, 
                    c.bank_name bnknm,
                    a.cheque_branch_id, 
                    d.branch_name brnchnm,
                    a.cheque_no, 
                    a.amount, 
                    to_char(to_timestamp(a.value_date,'YYYY-MM-DD'),'DD-Mon-YYYY'),  
                    a.cheque_type, 
                    to_char(to_timestamp(a.cheque_date,'YYYY-MM-DD'),'DD-Mon-YYYY'), 
                    a.cheque_type_id, 
                    a.cheque_crncy_id, 
                    b.mapped_lov_crncy_id, 
                    gst.get_pssbl_val(b.mapped_lov_crncy_id) crncy_nm, 
                    a.accnt_crncy_rate, 
                    a.house_chq_src_accnt_id, 
                    a.created_by, 
                    sec.get_usr_prsn_id(a.created_by),
                    mcf.get_hsechq_acntid(a.cheque_no) src_acnt_id, 
                    a.is_cleared, 
                    a.date_cleared, 
                    a.src_accnt_trns_id, 
                    a.cheque_mandate 
            FROM mcf.mcf_cust_account_trns_cheques a 
            LEFT OUTER JOIN mcf.mcf_currencies b ON (a.cheque_crncy_id = b.crncy_id) 
            LEFT OUTER JOIN mcf.mcf_all_banks c ON (a.cheque_bank_id = c.bank_id)  
            LEFT OUTER JOIN mcf.mcf_bank_branches d ON (a.cheque_branch_id = d.branch_id and d.bank_id = c.bank_id) 
            WHERE a.acct_trns_id>0 and a.acct_trns_id = " . $acctTrnsId . $whrCls;
    return executeSQLNoParams($selSQL);
}

function getAccountTrnsChqs1($trnsChqID) {
    $selSQL = "SELECT a.trns_cheque_id, 
                    a.acct_trns_id, 
                    a.cheque_bank_id, 
                    c.bank_name bnknm,
                    a.cheque_branch_id, 
                    d.branch_name brnchnm,
                    a.cheque_no, 
                    a.amount, 
                    to_char(to_timestamp(a.value_date,'YYYY-MM-DD'),'DD-Mon-YYYY'),  
                    a.cheque_type, 
                    to_char(to_timestamp(a.cheque_date,'YYYY-MM-DD'),'DD-Mon-YYYY'), 
                    a.cheque_type_id, 
                    a.cheque_crncy_id, 
                    b.mapped_lov_crncy_id, 
                    gst.get_pssbl_val(b.mapped_lov_crncy_id) crncy_nm, 
                    a.accnt_crncy_rate, 
                    a.house_chq_src_accnt_id, 
                    a.created_by, 
                    sec.get_usr_prsn_id(a.created_by),
                    mcf.get_hsechq_acntid(a.cheque_no) src_acnt_id, 
                    a.is_cleared, 
                    a.date_cleared, 
                    a.src_accnt_trns_id, 
                    a.cheque_mandate,
                    e.trns_date, 
                    e.account_id, 
                    e.trns_type, 
                    e.description, 
                    e.doc_no, 
                    e.trns_person_name, 
                    e.trns_person_tel_no, 
                    e.trns_person_address, 
                    e.trns_person_id_type, 
                    e.trns_person_id_number, 
                    e.trns_person_type, 
                    e.status, 
                    e.doc_type, 
                    e.debit_or_credit,  
                    e.trns_no, 
                    e.voided_acct_trns_id, 
                    e.voided_trns_type, 
                    e.reversal_reason,  
                    e.unclrdbal, 
                    e.clrdbal, 
                    e.acctstatus, 
                    e.acctcustomer, 
                    e.acctlien, 
                    e.mandate, 
                    e.wtdrwllimitno, 
                    e.wtdrwllimitamt, 
                    e.wtdrwllimittype, 
                    e.entered_crncy_id, 
                    e.accnt_crncy_rate, 
                    e.trns_has_other_lines, 
                    e.disbmnt_hdr_id, 
                    e.disbmnt_det_id, 
                    e.sub_trns_type
            FROM mcf.mcf_cust_account_trns_cheques a
            LEFT OUTER JOIN mcf.mcf_cust_account_transactions e ON (a.acct_trns_id = e.acct_trns_id)
            LEFT OUTER JOIN mcf.mcf_currencies b ON (a.cheque_crncy_id = b.crncy_id) 
            LEFT OUTER JOIN mcf.mcf_all_banks c ON (a.cheque_bank_id = c.bank_id)  
            LEFT OUTER JOIN mcf.mcf_bank_branches d ON (a.cheque_branch_id = d.branch_id and d.bank_id = c.bank_id) 
            WHERE a.trns_cheque_id=" . $trnsChqID;
    return executeSQLNoParams($selSQL);
}

function getAccountChqTrnsID($acntTrnsID, $acctID, $dateStr, $usrID, $chqNum) {

    $sqlStr = "SELECT trns_cheque_id FROM mcf.mcf_cust_account_trns_cheques WHERE 1=1 AND creation_date = '" . $dateStr .
            "' AND created_by = " . $usrID .
            " AND house_chq_src_accnt_id = " . $acctID .
            " AND cheque_no='" . loc_db_escape_string($chqNum) . "'" .
            " AND acct_trns_id=" . $acntTrnsID;

    $result = executeSQLNoParams($sqlStr);

    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }

    return -1;
}

function getBulkChqTrnsID($acntTrnsID, $acctID, $dateStr, $usrID, $chqNum) {

    $sqlStr = "SELECT trns_cheque_id FROM mcf.mcf_cust_account_trns_cheques WHERE 1=1 AND creation_date = '" . $dateStr .
            "' AND created_by = " . $usrID .
            " AND house_chq_src_accnt_id = " . $acctID .
            " AND cheque_no='" . loc_db_escape_string($chqNum) . "'" .
            " AND bulk_trns_hdr_id=" . $acntTrnsID;

    $result = executeSQLNoParams($sqlStr);

    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }

    return -1;
}

function getAccountChqTrnsID1($acntTrnsID, $acctID, $chqType, $chqNum) {

    $sqlStr = "SELECT trns_cheque_id FROM mcf.mcf_cust_account_trns_cheques WHERE 1=1 " .
            " AND house_chq_src_accnt_id = " . $acctID .
            " AND cheque_no='" . loc_db_escape_string($chqNum) . "'" .
            " AND cheque_type='" . loc_db_escape_string($chqType) . "'" .
            " AND acct_trns_id=" . $acntTrnsID;

    $result = executeSQLNoParams($sqlStr);

    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }

    return -1;
}

function getAccountTrnsID($acctID, $dateStr, $orgID, $usrID) {

    $sqlStr = "SELECT acct_trns_id FROM mcf.mcf_cust_account_transactions WHERE 1=1 AND creation_date = '" . $dateStr .
            "' AND created_by = " . $usrID .
            " AND account_id = " . $acctID;

    $result = executeSQLNoParams($sqlStr);

    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }

    return -1;
}

function getAccountDetails($acctNo) {
    $balDte = substr(getStartOfDayYMD(), 0, 10);
    $strSql = "SELECT b.account_id, b.account_number, b.account_status, b.currency_id, b.account_type, 
        mcf.get_customer_name(b.cust_type, b.cust_id) customer, b.cust_type, 
        b.prsn_type_or_entity, b.branch_id, b.account_title, b.mandate,"
            . " c.mapped_lov_crncy_id, gst.get_pssbl_val(c.mapped_lov_crncy_id) crncy_nm, org.get_site_code_desc(b.branch_id::integer), b.cust_id
  FROM mcf.mcf_accounts b 
  LEFT OUTER JOIN mcf.mcf_currencies c ON (b.currency_id = c.crncy_id) 
  WHERE b.account_number = '" . loc_db_escape_string($acctNo) . "'";

    $result = executeSQLNoParams($strSql);
    return $result;
}

function getAccountWithdrawalLimitInfo($acctNo) {

    $strSql = "SELECT distinct y.withdrawal_limit_no, coalesce(y.withdrawal_limit_amount,0.00) from mcf.mcf_accounts x, mcf.mcf_prdt_savings y
where x.product_type_id = y.svngs_product_id
and account_number = '" . loc_db_escape_string($acctNo) . "'";

    $result = executeSQLNoParams($strSql);
    return $result;
}

function validateAccountNo($acctNo) {
    global $usrID;

    $strSql = "SELECT account_id FROM mcf.mcf_accounts WHERE account_number = '" . loc_db_escape_string($acctNo) . "'";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return (int) $row[0];
    }
    return -1;
}

function wasAccntSignUsed($acct_sign_id, $acct_trns_id) {
    $strSql = "SELECT trns_signatory_id " .
            "FROM mcf.mcf_account_trns_signatories 
                WHERE acct_trns_id=" . $acct_trns_id . " AND acct_sign_id =" . $acct_sign_id;
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return -1;
}

function getAccountBal($acctNo, $balTyp) {
    $bal = "";
    if ($balTyp == "Cleared") {
        $bal = "available_balance";
    } else {
        $bal = "uncleared_funds+available_balance+lien_amount";
    }

    $strSql = "SELECT $bal " .
            "FROM mcf.mcf_account_daily_bals a, mcf.mcf_accounts c WHERE a.account_id = c.account_id 
  AND bal_date = (select max(bal_date) from mcf.mcf_account_daily_bals b
  where b.account_id = c.account_id) AND c.account_number = '" . loc_db_escape_string($acctNo) . "'";

//var_dump($strSql);
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return 0.00;
}

function getAccountLovCrncyID($acctID) {
    $strSql = "SELECT mcf.get_accnt_lov_crncy_id($acctID)";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return (int) $row[0];
    }
    return -1;
}

function deleteMCFTrnsHdr($pkeyID, $srcDocType, $extrInfo = "") {
    $selSQL = "Select count(1) from mcf.mcf_cust_account_transactions WHERE (status IN ('Authorized', 'Paid', 'Received','Initiated','Void','Reviewed') or processing_ongoing != '0') "
            . "and (acct_trns_id = " . $pkeyID . " or acct_trns_id IN "
            . "(Select c.src_accnt_trns_id FROM mcf.mcf_cust_account_trns_cheques c WHERE c.src_accnt_trns_id != c.acct_trns_id and c.src_accnt_trns_id =  " . $pkeyID . "))";
    $result = executeSQLNoParams($selSQL);
    $trnsCnt = 0;
    $affctd1 = 0;
    $affctd2 = 0;
    $affctd21 = 0;
    $affctd3 = 0;
    $affctd4 = 0;
    $affctd5 = 0;
    while ($row = loc_db_fetch_array($result)) {
        $trnsCnt = $row[0];
    }
    if ($trnsCnt <= 0) {
        $insSQL = "DELETE FROM mcf.mcf_gl_interface WHERE src_doc_id = " . $pkeyID . " and src_doc_typ = '" . loc_db_escape_string($srcDocType) . "'";
        $affctd1 = execUpdtInsSQL($insSQL, "Trns Number:" . $extrInfo . ":Type:" . $srcDocType);
        $insSQL = "DELETE FROM mcf.mcf_account_trns_cash_analysis WHERE acct_trns_id= " . $pkeyID . "";
        $affctd2 = execUpdtInsSQL($insSQL, "Trns Number:" . $extrInfo . ":Type:" . $srcDocType);
        $insSQL = "DELETE FROM mcf.mcf_account_trns_signatories WHERE acct_trns_id= " . $pkeyID . "";
        $affctd21 = execUpdtInsSQL($insSQL, "Trns Number:" . $extrInfo . ":Type:" . $srcDocType);
        $insSQL = "DELETE FROM mcf.mcf_cust_account_trns_cheques WHERE acct_trns_id = " . $pkeyID;
        $affctd3 = execUpdtInsSQL($insSQL, "Trns Number:" . $extrInfo . ":Type:" . $srcDocType);
        $insSQL = "DELETE FROM mcf.mcf_doc_attchmnts WHERE src_id = " . $pkeyID . " and attchmnt_src='" . loc_db_escape_string($srcDocType) . "'";
        $affctd4 = execUpdtInsSQL($insSQL, "Trns Number:" . $extrInfo . ":Type:" . $srcDocType);
        $insSQL = "DELETE FROM mcf.mcf_cust_account_transactions WHERE acct_trns_id = " . $pkeyID;
        $affctd5 = execUpdtInsSQL($insSQL, "Trns Number:" . $extrInfo . ":Type:" . $srcDocType);
    }
    if ($affctd5 > 0) {
        $dsply = "Successfully Deleted the ff Records-";
        $dsply .= "<br/>$affctd1 GL Interface Transaction(s)!";
        $dsply .= "<br/>$affctd2 Cash Breakdown(s)!";
        $dsply .= "<br/>$affctd21 Signatory(ies)!";
        $dsply .= "<br/>$affctd3 Cheque Line(s)!";
        $dsply .= "<br/>$affctd4 Doc. Attachment(s)!";
        $dsply .= "<br/>$affctd5 Bank Transaction(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Deleted<br/>Cannot Delete Initiated Transactions!<br/>Kindly Withdraw first!";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function deleteMCFTrnsLine($pkeyID, $srcDocType, $extrInfo = "") {
    $selSQL = "Select count(1) from mcf.mcf_gl_interface WHERE gl_batch_id>0 and src_doc_line_id = " . $pkeyID . " and trns_ln_type = '" . loc_db_escape_string($srcDocType) . "'";
    $result = executeSQLNoParams($selSQL);
    $trnsCnt = 0;
    $affctd1 = 0;
    $affctd2 = 0;
    $affctd3 = 0;
    while ($row = loc_db_fetch_array($result)) {
        $trnsCnt = $row[0];
    }
    if ($trnsCnt <= 0) {
        $insSQL = "DELETE FROM mcf.mcf_gl_interface WHERE src_doc_line_id = " . $pkeyID . " and trns_ln_type = '" . loc_db_escape_string($srcDocType) . "'";
        $affctd1 = execUpdtInsSQL($insSQL, "Trns Number:" . $extrInfo . ":Type:" . $srcDocType);
        $insSQL = "DELETE FROM mcf.mcf_cust_account_trns_cheques WHERE trns_cheque_id = " . $pkeyID;
        $affctd3 = execUpdtInsSQL($insSQL, "Trns Number:" . $extrInfo . ":Type:" . $srcDocType);
    }
    if ($affctd3 > 0) {
        $dsply = "Successfully Deleted the ff Records-";
        $dsply .= "<br/>$affctd1 GL Interface Transaction(s)!";
        $dsply .= "<br/>$affctd3 Cheque Line(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Deleted<br/>Cannot Delete Authorized Transactions!";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function get_VaultCages($pkID, $prsnID, $searchWord, $searchIn, $offset, $limit_size) {
    $whereCls = "";
    $extrWhere = "";
    global $orgID;
    global $fnccurid;
    if ($pkID > 0) {
        $extrWhere .= " and (org.does_prsn_hv_crtria_id(" . $prsnID . ", a.allwd_group_value::bigint,a.allwd_group_type)>0) and (a.store_id = $pkID)";
    }
    if ($searchIn == "Cage Name") {
        $whereCls = " and ((CASE WHEN a.shelve_name='' THEN gst.get_pssbl_val(a.shelf_id) ELSE a.shelve_name END) ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Cage Description") {
        $whereCls = " and ((CASE WHEN a.shelve_desc='' THEN gst.get_pssbl_val_desc(a.shelf_id) ELSE a.shelve_desc END) ilike '" . loc_db_escape_string($searchWord) . "')";
    }

    $strSql = "SELECT a.line_id mt, 
        CASE WHEN a.shelve_name='' THEN gst.get_pssbl_val(a.shelf_id) ELSE a.shelve_name END  \"code/name\", 
        CASE WHEN a.shelve_desc='' THEN gst.get_pssbl_val_desc(a.shelf_id) ELSE a.shelve_desc END \"description/comments\", 
        CASE WHEN a.enabled_flag='1' THEN 'Yes' ELSE 'No' END \"is_enabled?\", 
        a.shelf_id, 
        a.lnkd_cstmr_id, scm.get_cstmr_splr_name(a.lnkd_cstmr_id),  
        a.allwd_group_type, 
        a.allwd_group_value,
        a.inv_asset_acct_id, accb.get_accnt_num(a.inv_asset_acct_id) || '.' || accb.get_accnt_name(a.inv_asset_acct_id) asset_acc, 
        a.cage_shelve_mngr_id, (prs.get_prsn_name(a.cage_shelve_mngr_id) || ' (' || prs.get_prsn_loc_id(a.cage_shelve_mngr_id) || ')') prsn_nm,
        b.subinv_id, b.subinv_name,
        c.location_id, c.location_code_name,
        gst.get_pssbl_val(accb.get_accnt_crncy_id(a.inv_asset_acct_id)) crncy_nm,
        accb.get_accnt_crncy_id(a.inv_asset_acct_id) crncy_id,
        CASE WHEN accb.get_accnt_crncy_id(a.inv_asset_acct_id)=$fnccurid THEN accb.get_ltst_accnt_bals(a.inv_asset_acct_id, to_char(now(),'YYYY-MM-DD')) 
            ELSE accb.get_ltst_accnt_crncy_bals(a.inv_asset_acct_id, to_char(now(),'YYYY-MM-DD')) END acct_bals,
        vms.get_ltst_stock_bals1(a.store_id, a.line_id,-1,'',to_char(now(),'YYYY-MM-DD')) ttlCgAmnt
        FROM inv.inv_shelf a
        LEFT OUTER JOIN inv.inv_itm_subinventories b ON (b.subinv_id = a.store_id)
        LEFT OUTER JOIN org.org_sites_locations c ON (c.location_id = b.lnkd_site_id)
        WHERE (a.org_id=" . $orgID . " and b.lnkd_site_id>0 and a.cage_shelve_mngr_id>0" . $extrWhere . $whereCls . ") 
        ORDER BY a.shelve_name LIMIT " . $limit_size . " OFFSET " . abs($offset * $limit_size);
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_VaultCagesTtl($pkID, $prsnID, $searchWord, $searchIn) {
    $whereCls = "";
    $extrWhere = "";
    global $orgID;
    if ($pkID > 0) {
        $extrWhere .= " and (org.does_prsn_hv_crtria_id(" . $prsnID . ", a.allwd_group_value::bigint,a.allwd_group_type)>0) and (a.store_id = $pkID)";
    }
    if ($searchIn == "Cage Name") {
        $whereCls = " and (a.shelve_name ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Cage Description") {
        $whereCls = " and (a.shelve_desc ilike '" . loc_db_escape_string($searchWord) . "')";
    }
    $strSql = "SELECT count(1)  
            FROM inv.inv_shelf a
            LEFT OUTER JOIN inv.inv_itm_subinventories b ON (b.subinv_id = a.store_id)
            LEFT OUTER JOIN org.org_sites_locations c ON (c.location_id = b.lnkd_site_id) 
         WHERE (a.org_id=" . $orgID . " and b.lnkd_site_id>0 and a.cage_shelve_mngr_id>0" . $extrWhere . $whereCls . ")";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return 0;
}

function get_OneCageDet($pkID) {
    global $fnccurid;
    $strSql = "SELECT a.line_id mt, 
        CASE WHEN a.shelve_name='' THEN gst.get_pssbl_val(a.shelf_id) ELSE a.shelve_name END  \"code/name\", 
        CASE WHEN a.shelve_desc='' THEN gst.get_pssbl_val_desc(a.shelf_id) ELSE a.shelve_desc END \"description/comments\", 
        CASE WHEN a.enabled_flag='1' THEN 'Yes' ELSE 'No' END \"is_enabled?\", 
        a.shelf_id, 
        a.lnkd_cstmr_id, scm.get_cstmr_splr_name(a.lnkd_cstmr_id),  
        a.allwd_group_type, 
        a.allwd_group_value,
        a.inv_asset_acct_id, accb.get_accnt_num(a.inv_asset_acct_id) || '.' || accb.get_accnt_name(a.inv_asset_acct_id) asset_acc, 
        a.cage_shelve_mngr_id, (prs.get_prsn_name(a.cage_shelve_mngr_id) || ' (' || prs.get_prsn_loc_id(a.cage_shelve_mngr_id) || ')') prsn_nm,
        b.subinv_id, b.subinv_name,
        c.location_id, c.location_code_name,
        gst.get_pssbl_val(accb.get_accnt_crncy_id(a.inv_asset_acct_id)) crncy_nm,
        accb.get_accnt_crncy_id(a.inv_asset_acct_id) crncy_id,
        CASE WHEN accb.get_accnt_crncy_id(a.inv_asset_acct_id)=$fnccurid THEN accb.get_ltst_accnt_bals(a.inv_asset_acct_id, to_char(now(),'YYYY-MM-DD')) 
            ELSE accb.get_ltst_accnt_crncy_bals(a.inv_asset_acct_id, to_char(now(),'YYYY-MM-DD')) END acct_bals,
        vms.get_ltst_stock_bals1(a.store_id, a.line_id,-1,'',to_char(now(),'YYYY-MM-DD')) ttlCgAmnt,
        REPLACE(org.get_criteria_name(a.allwd_group_value::bigint,a.allwd_group_type),'Everyone','') group_name,
        a.managers_wthdrwl_limit,
        a.managers_deposit_limit,
        a.dflt_item_type,
        a.dflt_item_state
        FROM inv.inv_shelf a
        LEFT OUTER JOIN inv.inv_itm_subinventories b ON (b.subinv_id = a.store_id)
        LEFT OUTER JOIN org.org_sites_locations c ON (c.location_id = b.lnkd_site_id)
        WHERE (a.line_id=" . $pkID . ")";
    $result = executeSQLNoParams($strSql);
    return $result;
}

function getCageTillID($shlveNm, $vltid) {
    $sqlStr = "select line_id from inv.inv_shelf where lower(shelve_name) = '" .
            loc_db_escape_string(strtolower($shlveNm)) .
            "' and store_id = " . loc_db_escape_string($vltid);
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -1;
}

function createCageTill($orgid, $shelfID, $storeID, $shelfNm, $shelfDesc, $lnkdCstmrID, $allwdGrpType, $allwdGrpVal, $invAcntID, $cageMngrID, $dfltItmState, $mngrsWthdrwLmt, $mngrsDepLmt, $dfltType, $isenbled) {
    global $usrID;
    $dateStr = getDB_Date_time();
    $insSQL = "INSERT INTO inv.inv_shelf(
            shelf_id, store_id, created_by, creation_date, last_update_by, 
            last_update_date, org_id, shelve_name, shelve_desc, 
            lnkd_cstmr_id, allwd_group_type, allwd_group_value, enabled_flag, 
            inv_asset_acct_id, cage_shelve_mngr_id, dflt_item_state, managers_wthdrwl_limit, 
            managers_deposit_limit, dflt_item_type) " .
            "VALUES (" . loc_db_escape_string($shelfID) .
            ", " . loc_db_escape_string($storeID) .
            ", " . loc_db_escape_string($usrID) .
            ", '" . loc_db_escape_string($dateStr) .
            "', " . loc_db_escape_string($usrID) .
            ", '" . loc_db_escape_string($dateStr) .
            "', " . loc_db_escape_string($orgid) .
            ", '" . loc_db_escape_string($shelfNm) .
            "', '" . loc_db_escape_string($shelfDesc) .
            "', " . loc_db_escape_string($lnkdCstmrID) .
            ", '" . loc_db_escape_string($allwdGrpType) .
            "', '" . loc_db_escape_string($allwdGrpVal) .
            "', '" . loc_db_escape_string($isenbled) .
            "', " . loc_db_escape_string($invAcntID) .
            ", " . loc_db_escape_string($cageMngrID) .
            ", '" . loc_db_escape_string($dfltItmState) .
            "', " . loc_db_escape_string($mngrsWthdrwLmt) .
            ", " . loc_db_escape_string($mngrsDepLmt) .
            ", '" . loc_db_escape_string($dfltType) .
            "')";
    return execUpdtInsSQL($insSQL);
}

function updateCageTill($lineid, $shelfID, $storeID, $shelfNm, $shelfDesc, $lnkdCstmrID, $allwdGrpType, $allwdGrpVal, $invAcntID, $cageMngrID, $dfltItmState, $mngrsWthdrwLmt, $mngrsDepLmt, $dfltType, $isenbled) {
    global $usrID;
    $dateStr = getDB_Date_time();
    $insSQL = "UPDATE inv.inv_shelf SET shelf_id =" . loc_db_escape_string($shelfID) .
            ", store_id =" . loc_db_escape_string($storeID) .
            ", last_update_by = " . loc_db_escape_string($usrID) .
            ", last_update_date = '" . loc_db_escape_string($dateStr) .
            "', shelve_name = '" . loc_db_escape_string($shelfNm) .
            "', shelve_desc ='" . loc_db_escape_string($shelfDesc) .
            "', lnkd_cstmr_id = " . loc_db_escape_string($lnkdCstmrID) .
            ", allwd_group_type = '" . loc_db_escape_string($allwdGrpType) .
            "', allwd_group_value = '" . loc_db_escape_string($allwdGrpVal) .
            "', enabled_flag = '" . loc_db_escape_string($isenbled) .
            "', inv_asset_acct_id = " . loc_db_escape_string($invAcntID) .
            ", cage_shelve_mngr_id = " . loc_db_escape_string($cageMngrID) .
            ", dflt_item_state = '" . loc_db_escape_string($dfltItmState) .
            "', managers_wthdrwl_limit = " . loc_db_escape_string($mngrsWthdrwLmt) .
            ", managers_deposit_limit = " . loc_db_escape_string($mngrsDepLmt) .
            ", dflt_item_type = '" . loc_db_escape_string($dfltType) .
            "' WHERE line_id = " . $lineid;
    return execUpdtInsSQL($insSQL);
}

function deleteCageTill($pkeyID, $extrInfo = "") {
    $selSQL = "Select count(1) from mcf.mcf_account_trns_cash_analysis WHERE cage_shelve_id = " . $pkeyID;
    $result = executeSQLNoParams($selSQL);
    $trnsCnt = 0;
    while ($row = loc_db_fetch_array($result)) {
        $trnsCnt = (float) $row[0];
    }
    if ($trnsCnt > 0) {
        $dsply = "No Record Deleted<br/>Cannot Delete Cages/Tills used in Transactions!";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
    $selSQL1 = "Select count(1) from vms.vms_transaction_lines WHERE src_cage_shelve_id = " . $pkeyID . " or dest_cage_shelve_id = " . $pkeyID;
    $result1 = executeSQLNoParams($selSQL1);
    $trnsCnt1 = 0;
    while ($row = loc_db_fetch_array($result1)) {
        $trnsCnt1 = (float) $row[0];
    }
    if ($trnsCnt1 > 0) {
        $dsply = "No Record Deleted<br/>Cannot Delete Cages/Tills used in Transactions!";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
    $selSQL2 = "Select count(1) from vms.vms_transaction_pymnt WHERE src_cage_shelve_id = " . $pkeyID . " or dest_cage_shelve_id = " . $pkeyID;
    $result2 = executeSQLNoParams($selSQL2);
    $trnsCnt2 = 0;
    while ($row = loc_db_fetch_array($result2)) {
        $trnsCnt2 = (float) $row[0];
    }
    if ($trnsCnt2 > 0) {
        $dsply = "No Record Deleted<br/>Cannot Delete Cages/Tills used in Transactions!";
        return "<p style=\"text-align:left;color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
    $selSQL3 = "Select count(1) from vms.vms_stock_daily_bals WHERE cage_shelve_id = " . $pkeyID;
    $result3 = executeSQLNoParams($selSQL3);
    $trnsCnt3 = 0;
    while ($row = loc_db_fetch_array($result3)) {
        $trnsCnt3 = (float) $row[0];
    }
    if ($trnsCnt3 > 0) {
        $dsply = "No Record Deleted<br/>Cannot Delete Cages/Tills used in Transactions!";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
    $affctd1 = 0;
    if (($trnsCnt + $trnsCnt1 + $trnsCnt2 + $trnsCnt3) <= 0) {
        $insSQL = "DELETE FROM inv.inv_shelf WHERE line_id = " . $pkeyID;
        $affctd1 = execUpdtInsSQL($insSQL, "Cage/Till Name:" . $extrInfo);
    }
    if ($affctd1 > 0) {
        $dsply = "Successfully Deleted the ff Records-";
        $dsply .= "<br/>$affctd1 Cage(s)/Till(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function get_Currencies($searchFor, $searchIn, $offset, $limit_size, $orgID) {
    $wherecls = "";
    if ($searchIn === "Name") {
        $wherecls = "(a.iso_code ilike '" .
                loc_db_escape_string($searchFor) . "' or a.description ilike '" .
                loc_db_escape_string($searchFor) . "') and ";
    } else {
        $wherecls = "(a.description ilike '" .
                loc_db_escape_string($searchFor) . "') and ";
    }

    $strSql = "SELECT a.crncy_id, a.description, a.iso_code, a.is_enabled, a.mapped_lov_crncy_id,
        gst.get_pssbl_val(a.mapped_lov_crncy_id) mpdcrncy_nm
  FROM mcf.mcf_currencies a WHERE " . $wherecls .
            "(a.org_id = " . $orgID . ") "
            . "ORDER BY a.crncy_id DESC LIMIT " . $limit_size .
            " OFFSET " . abs($offset * $limit_size);
//echo $strSql;
    $result = executeSQLNoParams($strSql);

    return $result;
}

function get_CurrenciesTtl($searchFor, $searchIn, $orgID) {
    $wherecls = "";
    if ($searchIn === "Name") {
        $wherecls = "(a.iso_code ilike '" .
                loc_db_escape_string($searchFor) . "' or a.description ilike '" .
                loc_db_escape_string($searchFor) . "') and ";
    } else {
        $wherecls = "(a.description ilike '" .
                loc_db_escape_string($searchFor) . "') and ";
    }
    $strSql = "SELECT count(1) from mcf.mcf_currencies a WHERE " . $wherecls .
            "(org_id = " . $orgID . ")";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return 0;
}

function get_OneCrncyDet($crncyID) {
    $strSql = "SELECT a.crncy_id, a.description, a.iso_code, a.is_enabled, 
        a.mapped_lov_crncy_id, gst.get_pssbl_val(a.mapped_lov_crncy_id) mpdcrncy_nm
  FROM mcf.mcf_currencies a WHERE (a.crncy_id = " . $crncyID . ")";
    $result = executeSQLNoParams($strSql);
    return $result;
}

function getCrncyID($crncyISOCode, $orgid) {
    $sqlStr = "select crncy_id from mcf.mcf_currencies where lower(iso_code) = '" .
            loc_db_escape_string(strtolower($crncyISOCode)) . "' and org_id = " . loc_db_escape_string($orgid);
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -1;
}

function createCurrency($orgid, $isoCode, $crncydesc, $isenbled, $mppdCrncyID) {
    global $usrID;
    $dateStr = getDB_Date_time();
    $insSQL = "INSERT INTO mcf.mcf_currencies(
            description, iso_code, is_enabled, created_by, creation_date, 
            last_update_by, last_update_date, org_id, mapped_lov_crncy_id) " .
            "VALUES ('" . loc_db_escape_string($crncydesc) .
            "', '" . loc_db_escape_string($isoCode) .
            "', '" . loc_db_escape_string($isenbled) .
            "', " . loc_db_escape_string($usrID) .
            ", '" . loc_db_escape_string($dateStr) .
            "', " . loc_db_escape_string($usrID) .
            ", '" . loc_db_escape_string($dateStr) .
            "', " . loc_db_escape_string($orgid) .
            ", " . loc_db_escape_string($mppdCrncyID) .
            ")";
    return execUpdtInsSQL($insSQL);
}

function updateCurrency($crncyid, $isoCode, $crncydesc, $isenbled, $mppdCrncyID) {
    global $usrID;
    $dateStr = getDB_Date_time();
    $insSQL = "UPDATE mcf.mcf_currencies " .
            "SET iso_code='" . loc_db_escape_string($isoCode) .
            "', description='" . loc_db_escape_string($crncydesc) .
            "', is_enabled = '" . loc_db_escape_string($isenbled) .
            "', mapped_lov_crncy_id = " . $mppdCrncyID .
            ", last_update_by=" . $usrID .
            ", last_update_date='" . $dateStr .
            "' WHERE crncy_id = " . $crncyid;
    return execUpdtInsSQL($insSQL);
}

function createCurrencyDenom($crncyID, $denomVal, $denomtype, $denomdesc, $orgid, $isenbled, $lnkdItemID) {
    global $usrID;
    $dateStr = getDB_Date_time();
    $insSQL = "INSERT INTO mcf.mcf_currency_denominations(
            value, is_enabled, created_by, creation_date, 
            last_update_by, last_update_date, crncy_id, crncy_type, display_name, 
            org_id, vault_item_id) " .
            "VALUES (" . $denomVal .
            ", '" . loc_db_escape_string($isenbled) .
            "', " . loc_db_escape_string($usrID) .
            ", '" . loc_db_escape_string($dateStr) .
            "', " . loc_db_escape_string($usrID) .
            ", '" . loc_db_escape_string($dateStr) .
            "', " . $crncyID .
            ", '" . loc_db_escape_string($denomtype) .
            "', '" . loc_db_escape_string($denomdesc) .
            "', " . $orgid .
            ", " . $lnkdItemID .
            ")";
    return execUpdtInsSQL($insSQL);
}

function updateCurrencyDenom($denomid, $denomVal, $denomtype, $denomdesc, $isenbled, $lnkdItemID) {
    global $usrID;
    $dateStr = getDB_Date_time();
    $insSQL = "UPDATE mcf.mcf_currency_denominations
   SET value=" . $denomVal .
            ", is_enabled='" . loc_db_escape_string($isenbled) .
            "', last_update_by=" . $usrID .
            ", last_update_date='" . $dateStr .
            "', crncy_type='" . loc_db_escape_string($denomtype) .
            "', display_name='" . loc_db_escape_string($denomdesc) .
            "', vault_item_id=" . $lnkdItemID .
            " WHERE crncy_denom_id = " . $denomid;
    return execUpdtInsSQL($insSQL);
}

function deleteCurrency($pkeyID, $extrInfo = "") {
    $selSQL = "Select count(1) from mcf.mcf_cust_account_transactions WHERE entered_crncy_id = " . $pkeyID;
    $result = executeSQLNoParams($selSQL);
    $trnsCnt = 0;
    while ($row = loc_db_fetch_array($result)) {
        $trnsCnt = (float) $row[0];
    }
    if ($trnsCnt > 0) {
        $dsply = "No Record Deleted<br/>Cannot Delete Currencies used in Transactions!";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
    $selSQL1 = "Select count(1) from mcf.mcf_account_trns_cash_analysis WHERE denomination_id IN (select crncy_denom_id from mcf.mcf_currency_denominations where crncy_id = " . $pkeyID . ")";
    $result1 = executeSQLNoParams($selSQL1);
    $trnsCnt1 = 0;
    while ($row = loc_db_fetch_array($result1)) {
        $trnsCnt1 = (float) $row[0];
    }
    if ($trnsCnt1 > 0) {
        $dsply = "No Record Deleted<br/>Cannot Delete Currencies used in Transactions!";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
    $selSQL2 = "Select count(1) from mcf.mcf_cust_account_trns_cheques WHERE cheque_crncy_id = " . $pkeyID;
    $result2 = executeSQLNoParams($selSQL2);
    $trnsCnt2 = 0;
    while ($row = loc_db_fetch_array($result2)) {
        $trnsCnt2 = (float) $row[0];
    }
    if ($trnsCnt2 > 0) {
        $dsply = "No Record Deleted<br/>Cannot Delete Currencies used in Transactions!";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
    $affctd1 = 0;
    $affctd2 = 0;
    if (($trnsCnt + $trnsCnt1 + $trnsCnt2) <= 0) {
        $insSQL = "DELETE FROM mcf.mcf_currency_denominations WHERE crncy_id = " . $pkeyID . "";
        $affctd1 = execUpdtInsSQL($insSQL, "Currency Name:" . $extrInfo);
        $insSQL = "DELETE FROM mcf.mcf_currencies WHERE crncy_id = " . $pkeyID;
        $affctd2 = execUpdtInsSQL($insSQL, "Currency Name:" . $extrInfo);
    }
    if ($affctd2 > 0) {
        $dsply = "Successfully Deleted the ff Records-";
        $dsply .= "<br/>$affctd1 Currency Denomination(s)!";
        $dsply .= "<br/>$affctd2 Currency(ies)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function deleteCurrencyDenom($pkeyID, $extrInfo = "") {
    $selSQL1 = "Select count(1) from mcf.mcf_account_trns_cash_analysis WHERE denomination_id = " . $pkeyID . "";
    $result1 = executeSQLNoParams($selSQL1);
    $trnsCnt1 = 0;
    while ($row = loc_db_fetch_array($result1)) {
        $trnsCnt1 = (float) $row[0];
    }
    if ($trnsCnt1 > 0) {
        $dsply = "No Record Deleted<br/>Cannot Delete Currency Denominations used in Transactions!";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
    $affctd1 = 0;
    $affctd2 = 0;
    if (($trnsCnt1) <= 0) {
        $insSQL = "DELETE FROM mcf.mcf_currency_denominations WHERE crncy_id = " . $pkeyID . "";
        $affctd1 = execUpdtInsSQL($insSQL, "Currency Name:" . $extrInfo);
    }
    if ($affctd1 > 0) {
        $dsply = "Successfully Deleted the ff Records-";
        $dsply .= "<br/>$affctd1 Currency Denomination(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function get_DfltMCFAccnts($searchFor, $searchIn, $offset, $limit_size, $orgID) {
    $wherecls = "";
    if ($searchIn === "Transaction Type") {
        $wherecls = "(a.trans_type ilike '" .
                loc_db_escape_string($searchFor) . "') and ";
    } else {
        $wherecls = "(a.trans_type ilike '" .
                loc_db_escape_string($searchFor) . "' or a.trans_type_desc ilike '" .
                loc_db_escape_string($searchFor) . "' or (accb.get_accnt_num(a.dlft_gl_accnt_id)||'.'||accb.get_accnt_name(a.dlft_gl_accnt_id)) ilike '" .
                loc_db_escape_string($searchFor) . "' or (mcf.get_cust_accnt_num(a.dlft_customer_accnt_id)||'.'||mcf.get_cust_accnt_name(a.dlft_customer_accnt_id)) ilike '" .
                loc_db_escape_string($searchFor) . "') and ";
    }
    $strSql = "SELECT 
                a.dflt_accnt_id, 
                a.trans_type, 
                a.dlft_customer_accnt_id, 
                a.dlft_gl_accnt_id,
                accb.get_accnt_num(a.dlft_gl_accnt_id)||'.'||accb.get_accnt_name(a.dlft_gl_accnt_id) gl_accnt,
               mcf.get_cust_accnt_num(a.dlft_customer_accnt_id)||'.'||mcf.get_cust_accnt_name(a.dlft_customer_accnt_id) cstmr_accnt,
               CASE WHEN a.dlft_customer_accnt_id>0 THEN 'Customer Account' ELSE 'GL Account' END accnttype,
               a.trans_type_desc,
               a.is_enabled
            FROM mcf.mcf_trans_dflt_accnts a
            WHERE " . $wherecls .
            "(a.org_id = " . $orgID . ") "
            . "ORDER BY a.dflt_accnt_id DESC LIMIT " . $limit_size .
            " OFFSET " . abs($offset * $limit_size);
//echo $strSql;
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_DfltMCFAccntsTtl($searchFor, $searchIn, $orgID) {
    $wherecls = "";
    if ($searchIn === "Transaction Type") {
        $wherecls = "(a.trans_type ilike '" .
                loc_db_escape_string($searchFor) . "') and ";
    } else {
        $wherecls = "(a.trans_type ilike '" .
                loc_db_escape_string($searchFor) . "' or a.trans_type_desc ilike '" .
                loc_db_escape_string($searchFor) . "' or (accb.get_accnt_num(a.dlft_gl_accnt_id)||'.'||accb.get_accnt_name(a.dlft_gl_accnt_id)) ilike '" .
                loc_db_escape_string($searchFor) . "' or (mcf.get_cust_accnt_num(a.dlft_customer_accnt_id)||'.'||mcf.get_cust_accnt_name(a.dlft_customer_accnt_id)) ilike '" .
                loc_db_escape_string($searchFor) . "') and ";
    }
    $strSql = "SELECT 
                count(1)
            FROM mcf.mcf_trans_dflt_accnts a
            WHERE " . $wherecls .
            "(a.org_id = " . $orgID . ")";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return 0;
}

function deleteDfltAcnt($pkeyID, $extrInfo = "") {
    $insSQL = "DELETE FROM mcf.mcf_trans_dflt_accnts WHERE dflt_accnt_id = " . $pkeyID . "";
    $affctd1 = execUpdtInsSQL($insSQL, "Sub-Type:" . $extrInfo);
    if ($affctd1 > 0) {
        $dsply = "Successfully Deleted the ff Records-";
        $dsply .= "<br/>$affctd1 Default Account(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function getDfltAcntLineID($trnsTyp, $subTrnsType) {
    $strSql = "SELECT a.dflt_accnt_id 
        FROM mcf.mcf_trans_dflt_accnts a
        WHERE ((a.trans_type ='" . loc_db_escape_string($trnsTyp) .
            "') and (a.trans_type_desc ='" . loc_db_escape_string($subTrnsType) . "')) ";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -1;
}

function createMcfDfltAcnt($trnsType, $subTrnsType, $glAcntID, $cstmrAcntID, $isEnbld) {
    global $usrID;
    global $orgID;
    $dateStr = getDB_Date_time();
    $insSQL = "INSERT INTO mcf.mcf_trans_dflt_accnts(
            trans_type, dlft_customer_accnt_id, dlft_gl_accnt_id, 
            created_by, creation_date, last_update_by, last_update_date, 
            trans_type_desc, org_id, is_enabled) " .
            "VALUES ('" . loc_db_escape_string($trnsType) . "', " . $cstmrAcntID . ", " . $glAcntID . ", " . $usrID . ", '" . $dateStr .
            "', " . $usrID . ", '" . $dateStr .
            "', '" . loc_db_escape_string($subTrnsType) . "', " . $orgID . ", '" . loc_db_escape_string($isEnbld) . "')";
    return execUpdtInsSQL($insSQL);
}

function updateMcfDfltAcnt($lineID, $trnsType, $subTrnsType, $glAcntID, $cstmrAcntID, $isEnbld) {
    global $usrID;
    $dateStr = getDB_Date_time();
    $insSQL = "UPDATE mcf.mcf_trans_dflt_accnts
                SET trans_type='" . loc_db_escape_string($trnsType) . "'" .
            ", dlft_customer_accnt_id=" . $cstmrAcntID .
            ", dlft_gl_accnt_id=" . $glAcntID .
            ", trans_type_desc='" . loc_db_escape_string($subTrnsType) . "'" .
            ", is_enabled='" . loc_db_escape_string($isEnbld) .
            "', last_update_by=" . $usrID .
            ", last_update_date='" . $dateStr .
            "' WHERE(dflt_accnt_id = " . $lineID . ")";
    return execUpdtInsSQL($insSQL);
}

function get_EODs($searchFor, $searchIn, $offset, $limit_size, $orgID, $dte1, $dte2) {
    $wherecls = "";
    if ($searchIn === "EOD Record ID") {
        $wherecls = "(''||a.cob_record_id ilike '" .
                loc_db_escape_string($searchFor) . "' or ''||a.eod_prcs_run_id ilike '" .
                loc_db_escape_string($searchFor) . "') and ";
    } else {
        $wherecls = "(a.cob_status ilike '" .
                loc_db_escape_string($searchFor) . "' or a.remarks ilike '" .
                loc_db_escape_string($searchFor) . "' or ''||a.cob_record_id ilike '" .
                loc_db_escape_string($searchFor) . "') and ";
    }

    $strSql = "SELECT a.cob_record_id, 
                        a.eod_prcs_run_id, 
                        a.cob_run_date,
                       CASE WHEN a.cob_run_date !='' THEN to_char(to_timestamp(a.cob_run_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') ELSE '' END transdte, 
                       a.cob_status, 
                       a.start_of_day_date, 
                       to_char(to_timestamp(a.start_of_day_date,'YYYY-MM-DD'),'DD-Mon-YYYY') startday,
                       a.remarks,
                       a.next_start_of_day_date,
                       CASE WHEN a.next_start_of_day_date !='' THEN to_char(to_timestamp(a.next_start_of_day_date,'YYYY-MM-DD'),'DD-Mon-YYYY') ELSE '' END startday                       
  FROM mcf.mcf_cob_trns_records a WHERE " . $wherecls .
            "(a.org_id = " . $orgID . ") and (to_timestamp(a.start_of_day_date,'YYYY-MM-DD') >= to_timestamp('"
            . loc_db_escape_string($dte1) . "' ,'DD-Mon-YYYY HH24:MI:SS') AND to_timestamp(a.start_of_day_date,'YYYY-MM-DD') <=to_timestamp('" .
            loc_db_escape_string($dte2) . "' ,'DD-Mon-YYYY HH24:MI:SS'))"
            . "ORDER BY a.cob_record_id DESC LIMIT " . $limit_size .
            " OFFSET " . abs($offset * $limit_size);
//echo $strSql;
    $result = executeSQLNoParams($strSql);

    return $result;
}

function get_EODsTtl($searchFor, $searchIn, $orgID, $dte1, $dte2) {
    $wherecls = "";
    if ($searchIn === "EOD Record ID") {
        $wherecls = "(''||a.cob_record_id ilike '" .
                loc_db_escape_string($searchFor) . "' or ''||a.eod_prcs_run_id ilike '" .
                loc_db_escape_string($searchFor) . "') and ";
    } else {
        $wherecls = "(a.cob_status ilike '" .
                loc_db_escape_string($searchFor) . "' or a.remarks ilike '" .
                loc_db_escape_string($searchFor) . "' or ''||a.cob_record_id ilike '" .
                loc_db_escape_string($searchFor) . "') and ";
    }

    $strSql = "SELECT count(1) from mcf.mcf_cob_trns_records a WHERE " . $wherecls .
            "(a.org_id = " . $orgID . ") and (to_timestamp(a.start_of_day_date,'YYYY-MM-DD') >= to_timestamp('"
            . loc_db_escape_string($dte1) . "' ,'DD-Mon-YYYY HH24:MI:SS') AND to_timestamp(a.start_of_day_date,'YYYY-MM-DD') <=to_timestamp('" .
            loc_db_escape_string($dte2) . "' ,'DD-Mon-YYYY HH24:MI:SS'))";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return 0;
}

function createRate($rate_dte, $curFrom, $curFrmID, $curTo, $curToID, $scalefactor, $buyngRate) {
    global $usrID;
    $rate_dte = cnvrtDMYToYMD($rate_dte);
    $dateStr = getDB_Date_time();
    $insSQL = "INSERT INTO mcf.mcf_exchange_rates(
            conversion_date, currency_from, currency_from_id, currency_to, 
            currency_to_id, multiply_from_by, created_by, creation_date, 
            last_update_by, last_update_date, mltply_frm_by_whn_buyng) " .
            "VALUES ('" . loc_db_escape_string($rate_dte) .
            "', '" . loc_db_escape_string($curFrom) .
            "', " . $curFrmID .
            ", '" . $curTo .
            "', " . $curToID .
            ", " . $scalefactor .
            ", " . $usrID . ", '" . $dateStr .
            "', " . $usrID . ", '" . $dateStr .
            "', " . $buyngRate .
            ")";
    return execUpdtInsSQL($insSQL);
}

function updtRate($rateID, $rate_dte, $curFrom, $curFrmID, $curTo, $curToID, $scalefactor, $buyngRate) {
    global $usrID;
    $rate_dte = cnvrtDMYToYMD($rate_dte);
    $dateStr = getDB_Date_time();
    $insSQL = "UPDATE mcf.mcf_exchange_rates SET 
            conversion_date='" . loc_db_escape_string($rate_dte) .
            "', currency_from='" . loc_db_escape_string($curFrom) .
            "', currency_from_id=" . $curFrmID .
            ", last_update_by=" . $usrID . ", last_update_date='" . $dateStr .
            "', currency_to='" . loc_db_escape_string($curTo) .
            "', currency_to_id=" . $curToID .
            ", multiply_from_by = " . $scalefactor .
            ", mltply_frm_by_whn_buyng = " . $buyngRate .
            " WHERE rate_id = " . $rateID;
    return execUpdtInsSQL($insSQL);
}

function updtRateValue($rateID, $scalefactor, $buyngRate) {
    global $usrID;
    $dateStr = getDB_Date_time();
    $insSQL = "UPDATE mcf.mcf_exchange_rates SET 
            last_update_by=" . $usrID .
            ", last_update_date='" . $dateStr .
            "', multiply_from_by=" . $scalefactor .
            ", mltply_frm_by_whn_buyng = " . $buyngRate .
            " WHERE rate_id = " . $rateID;
    return execUpdtInsSQL($insSQL);
}

function deleteRate($valLnid, $rateDesc) {
    $delSQL = "DELETE FROM mcf.mcf_exchange_rates WHERE rate_id = " . $valLnid;
    return execUpdtInsSQL($delSQL, $rateDesc);
}

function doesRateExst($rateDte, $fromCur, $toCur) {
    $rateDte = cnvrtDMYToYMD($rateDte);
    $strSql = "SELECT rate_id 
            FROM mcf.mcf_exchange_rates WHERE currency_from='" . loc_db_escape_string($fromCur) .
            "' and currency_to='" . loc_db_escape_string($toCur) .
            "' and conversion_date='" . loc_db_escape_string($rateDte) .
            "'";
    $result = executeSQLNoParams($strSql);
    if (loc_db_num_rows($result) > 0) {
        return true;
    }
    return false;
}

function doesRateExst1($rateDte, $fromCur, $toCur) {
    $rateDte = cnvrtDMYToYMD($rateDte);
    $strSql = "SELECT rate_id 
                        FROM mcf.mcf_exchange_rates WHERE currency_from='" . loc_db_escape_string($fromCur) .
            "' and currency_to='" . loc_db_escape_string($toCur) .
            "' and conversion_date='" . loc_db_escape_string($rateDte) .
            "'";
    $result = executeSQLNoParams($strSql);
    if ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -1;
}

function get_ExchgCurrencies($funcCurCode) {
    $strSql = "SELECT pssbl_value_id, pssbl_value, pssbl_value_desc,
       is_enabled, allowed_org_ids
  FROM gst.gen_stp_lov_values WHERE pssbl_value != '" . loc_db_escape_string($funcCurCode) .
            "' and is_enabled='1' and value_list_id=" . getLovID("Currencies");
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_Rates($searchWord, $searchIn, $dte1, $dte2, $offset, $limit_size) {
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
        multiply_from_by, conversion_date, mltply_frm_by_whn_buyng 
        FROM mcf.mcf_exchange_rates a " .
            "WHERE((to_timestamp(conversion_date,'YYYY-MM-DD') >= to_timestamp('"
            . loc_db_escape_string($dte1) . "' ,'DD-Mon-YYYY HH24:MI:SS') AND to_timestamp(conversion_date,'YYYY-MM-DD') <=to_timestamp('" .
            loc_db_escape_string($dte2) . "' ,'DD-Mon-YYYY HH24:MI:SS'))" . $whrcls .
            ") ORDER BY conversion_date DESC, currency_from ASC LIMIT " . $limit_size .
            " OFFSET " . abs($offset * $limit_size);

    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_Total_Rates($searchWord, $searchIn, $dte1, $dte2) {
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

    $strSql = "SELECT count(1) FROM mcf.mcf_exchange_rates a " .
            "WHERE((to_timestamp(conversion_date,'YYYY-MM-DD HH24:MI:SS') >= to_timestamp('" .
            $dte1 . "' ,'DD-Mon-YYYY HH24:MI:SS') AND to_timestamp(conversion_date,'YYYY-MM-DD HH24:MI:SS') <=to_timestamp('" .
            $dte2 . "' ,'DD-Mon-YYYY HH24:MI:SS'))" . $whrcls . ")";

    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return 0;
}

function get_AllBanks($searchFor, $searchIn, $offset, $limit_size, $orgID) {
    $wherecls = "";
    if ($searchIn === "Bank Name") {
        $wherecls = "(a.bank_code ilike '" .
                loc_db_escape_string($searchFor) . "' or a.bank_name ilike '" .
                loc_db_escape_string($searchFor) . "') and ";
    } else {
        $wherecls = "(COALESCE(a.residential_address,'') ilike '" .
                loc_db_escape_string($searchFor) . "' or COALESCE(a.postal_address,'') ilike '" .
                loc_db_escape_string($searchFor) . "' or COALESCE(a.contact_nos,'') ilike '" .
                loc_db_escape_string($searchFor) . "' or COALESCE(a.email_address,'') ilike '" .
                loc_db_escape_string($searchFor) . "' or COALESCE(a.fax_no,'') ilike '" .
                loc_db_escape_string($searchFor) . "') and ";
    }

    $strSql = "SELECT bank_id, bank_name, bank_code, residential_address, postal_address, 
       contact_nos, email_address, fax_no, created_by, creation_date, 
       last_update_by, last_update_date, org_id
  FROM mcf.mcf_all_banks a WHERE " . $wherecls .
            "(a.org_id = " . $orgID . ") "
            . "ORDER BY a.bank_id DESC LIMIT " . $limit_size .
            " OFFSET " . abs($offset * $limit_size);
//echo $strSql;
    $result = executeSQLNoParams($strSql);

    return $result;
}

function get_AllBanksTtl($searchFor, $searchIn, $orgID) {
    $wherecls = "";
    if ($searchIn === "Bank Name") {
        $wherecls = "(a.bank_code ilike '" .
                loc_db_escape_string($searchFor) . "' or a.bank_name ilike '" .
                loc_db_escape_string($searchFor) . "') and ";
    } else {
        $wherecls = "(COALESCE(a.residential_address,'') ilike '" .
                loc_db_escape_string($searchFor) . "' or COALESCE(a.postal_address,'') ilike '" .
                loc_db_escape_string($searchFor) . "' or COALESCE(a.contact_nos,'') ilike '" .
                loc_db_escape_string($searchFor) . "' or COALESCE(a.email_address,'') ilike '" .
                loc_db_escape_string($searchFor) . "' or COALESCE(a.fax_no,'') ilike '" .
                loc_db_escape_string($searchFor) . "') and ";
    }
    $strSql = "SELECT count(1) FROM mcf.mcf_all_banks a WHERE " . $wherecls .
            "(a.org_id = " . $orgID . ")";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return 0;
}

function get_OneBankDet($pkID) {
    $sqlStr = "SELECT bank_id, bank_code, bank_name, residential_address, postal_address, 
       contact_nos, email_address, fax_no, created_by, creation_date, 
       last_update_by, last_update_date, org_id, iso_country_code, check_digits, 
       bank_swift_code, is_enabled
  FROM mcf.mcf_all_banks a WHERE (bank_id = $pkID)";
    $result = executeSQLNoParams($sqlStr);
    return $result;
}

function get_AllBankBrnchs($searchFor, $searchIn, $offset, $limit_size, $bnkID) {
    $wherecls = "";
    if ($searchIn === "Branch Name") {
        $wherecls = "(a.branch_code ilike '" .
                loc_db_escape_string($searchFor) . "' or a.branch_name ilike '" .
                loc_db_escape_string($searchFor) . "') and ";
    } else {
        $wherecls = "(COALESCE(a.residential_address,'') ilike '" .
                loc_db_escape_string($searchFor) . "' or COALESCE(a.address,'') ilike '" .
                loc_db_escape_string($searchFor) . "' or COALESCE(a.contact_nos,'') ilike '" .
                loc_db_escape_string($searchFor) . "' or COALESCE(a.branch_desc,'') ilike '" .
                loc_db_escape_string($searchFor) . "' or COALESCE(a.fax_no,'') ilike '" .
                loc_db_escape_string($searchFor) . "') and ";
    }

    $strSql = "SELECT branch_id, bank_id, branch_name, branch_code, residential_address, address, 
       contact_nos, branch_desc, fax_no, created_by, creation_date, 
       last_update_by, last_update_date, branch_swift_code, 
       is_enabled
  FROM mcf.mcf_bank_branches a WHERE " . $wherecls .
            "(a.bank_id = " . $bnkID . ") "
            . "ORDER BY a.branch_id DESC LIMIT " . $limit_size .
            " OFFSET " . abs($offset * $limit_size);
//echo $strSql;
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_AllBankBrnchsTtl($searchFor, $searchIn, $bnkID) {
    $wherecls = "";
    if ($searchIn === "Branch Name") {
        $wherecls = "(a.branch_code ilike '" .
                loc_db_escape_string($searchFor) . "' or a.branch_name ilike '" .
                loc_db_escape_string($searchFor) . "') and ";
    } else {
        $wherecls = "(COALESCE(a.residential_address,'') ilike '" .
                loc_db_escape_string($searchFor) . "' or COALESCE(a.address,'') ilike '" .
                loc_db_escape_string($searchFor) . "' or COALESCE(a.contact_nos,'') ilike '" .
                loc_db_escape_string($searchFor) . "' or COALESCE(a.branch_desc,'') ilike '" .
                loc_db_escape_string($searchFor) . "' or COALESCE(a.fax_no,'') ilike '" .
                loc_db_escape_string($searchFor) . "') and ";
    }
    $strSql = "SELECT count(1) FROM mcf.mcf_bank_branches a WHERE " . $wherecls .
            "(a.bank_id = " . $bnkID . ")";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return 0;
}

function createBank($orgid, $bnkCode, $bnkNm, $resAdrs, $pstlAdrs, $cntctNos, $email, $faxNo, $isoCntryCode, $chkDgts, $bnkSwftCode, $isenbled) {
    global $usrID;
    $dateStr = getDB_Date_time();
    $insSQL = "INSERT INTO mcf.mcf_all_banks(
            bank_name, bank_code, residential_address, postal_address, 
            contact_nos, email_address, fax_no, created_by, creation_date, 
            last_update_by, last_update_date, org_id, iso_country_code, check_digits, 
            bank_swift_code, is_enabled) " .
            "VALUES ('" . loc_db_escape_string($bnkNm) .
            "', '" . loc_db_escape_string($bnkCode) .
            "', '" . loc_db_escape_string($resAdrs) .
            "', '" . loc_db_escape_string($pstlAdrs) .
            "', '" . loc_db_escape_string($cntctNos) .
            "', '" . loc_db_escape_string($email) .
            "', '" . loc_db_escape_string($faxNo) .
            "', " . loc_db_escape_string($usrID) .
            ", '" . loc_db_escape_string($dateStr) .
            "', " . loc_db_escape_string($usrID) .
            ", '" . loc_db_escape_string($dateStr) .
            "', " . loc_db_escape_string($orgid) .
            ", '" . loc_db_escape_string($isoCntryCode) .
            "', '" . loc_db_escape_string($chkDgts) .
            "', '" . loc_db_escape_string($bnkSwftCode) .
            "', '" . loc_db_escape_string($isenbled) .
            "')";
    return execUpdtInsSQL($insSQL);
}

function updateBank($bankid, $bnkCode, $bnkNm, $resAdrs, $pstlAdrs, $cntctNos, $email, $faxNo, $isoCntryCode, $chkDgts, $bnkSwftCode, $isenbled) {
    global $usrID;
    $dateStr = getDB_Date_time();
    $insSQL = "UPDATE mcf.mcf_all_banks
                SET bank_name='" . loc_db_escape_string($bnkNm) .
            "', bank_code='" . loc_db_escape_string($bnkCode) .
            "', residential_address='" . loc_db_escape_string($resAdrs) .
            "', postal_address='" . loc_db_escape_string($pstlAdrs) .
            "', contact_nos='" . loc_db_escape_string($cntctNos) .
            "', email_address='" . loc_db_escape_string($email) .
            "', fax_no='" . loc_db_escape_string($faxNo) .
            "', last_update_by=" . $usrID .
            ", last_update_date='" . $dateStr .
            "', iso_country_code='" . loc_db_escape_string($isoCntryCode) .
            "', check_digits='" . loc_db_escape_string($chkDgts) .
            "', bank_swift_code='" . loc_db_escape_string($bnkSwftCode) .
            "', is_enabled='" . loc_db_escape_string($isenbled) .
            "' WHERE bank_id=" . $bankid;
    return execUpdtInsSQL($insSQL);
}

function createBankBranch($orgid, $brnchNm, $brnchCode, $pstlAdrs, $cntctNos, $faxNo, $resAdrs, $bnkID, $brcnhDesc, $brnchSwftCode, $isenbled) {
    global $usrID;
    $dateStr = getDB_Date_time();
    $insSQL = "INSERT INTO mcf.mcf_bank_branches(
            branch_name, branch_code, address, contact_nos, fax_no, 
            created_by, creation_date, last_update_by, last_update_date, 
            residential_address, org_id, bank_id, branch_desc, branch_swift_code, 
            is_enabled) " .
            "VALUES ('" . loc_db_escape_string($brnchNm) .
            "', '" . loc_db_escape_string($brnchCode) .
            "','" . loc_db_escape_string($pstlAdrs) .
            "','" . loc_db_escape_string($cntctNos) .
            "','" . loc_db_escape_string($faxNo) .
            "'," . loc_db_escape_string($usrID) .
            ", '" . loc_db_escape_string($dateStr) .
            "', " . loc_db_escape_string($usrID) .
            ", '" . loc_db_escape_string($dateStr) .
            "', '" . loc_db_escape_string($resAdrs) .
            "'," . $orgid .
            ", " . $bnkID .
            ",'" . loc_db_escape_string($brcnhDesc) .
            "', '" . loc_db_escape_string($brnchSwftCode) .
            "', '" . loc_db_escape_string($isenbled) .
            "')";
    return execUpdtInsSQL($insSQL);
}

function updateBankBranch($branchid, $brnchNm, $brnchCode, $pstlAdrs, $cntctNos, $faxNo, $resAdrs, $bnkID, $brcnhDesc, $brnchSwftCode, $isenbled) {
    global $usrID;
    $dateStr = getDB_Date_time();
    $insSQL = "UPDATE mcf.mcf_bank_branches
        SET branch_name='" . loc_db_escape_string($brnchNm) .
            "', branch_code='" . loc_db_escape_string($brnchCode) .
            "', address='" . loc_db_escape_string($pstlAdrs) .
            "', contact_nos='" . loc_db_escape_string($cntctNos) .
            "', fax_no='" . loc_db_escape_string($faxNo) .
            "', last_update_by=" . $usrID .
            ", last_update_date='" . $dateStr .
            "', residential_address='" . loc_db_escape_string($resAdrs) .
            "', bank_id=" . $bnkID .
            ", branch_desc='" . loc_db_escape_string($brcnhDesc) .
            "', branch_swift_code='" . loc_db_escape_string($brnchSwftCode) .
            "', is_enabled='" . loc_db_escape_string($isenbled) .
            "' WHERE branch_id=" . $branchid;
    return execUpdtInsSQL($insSQL);
}

function deleteBank($pkeyID, $extrInfo = "") {
    $selSQL = "Select count(1) from mcf.mcf_cust_account_trns_cheques WHERE cheque_bank_id = " . $pkeyID;
    $result = executeSQLNoParams($selSQL);
    $trnsCnt = 0;
    while ($row = loc_db_fetch_array($result)) {
        $trnsCnt = (float) $row[0];
    }
    if ($trnsCnt > 0) {
        $dsply = "No Record Deleted<br/>Cannot Delete Banks used in Transactions!";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
    $selSQL1 = "Select count(1) from mcf.mcf_standing_orders WHERE extnl_bank_id = " . $pkeyID;
    $result1 = executeSQLNoParams($selSQL1);
    $trnsCnt1 = 0;
    while ($row = loc_db_fetch_array($result1)) {
        $trnsCnt1 = (float) $row[0];
    }
    if ($trnsCnt1 > 0) {
        $dsply = "No Record Deleted<br/>Cannot Delete Banks used in Transactions!";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
    $affctd1 = 0;
    $affctd2 = 0;
    if (($trnsCnt + $trnsCnt1 + $trnsCnt2) <= 0) {
        $insSQL = "DELETE FROM mcf.mcf_bank_branches WHERE bank_id = " . $pkeyID . "";
        $affctd1 = execUpdtInsSQL($insSQL, "Bank Name:" . $extrInfo);
        $insSQL = "DELETE FROM mcf.mcf_all_banks WHERE bank_id = " . $pkeyID;
        $affctd2 = execUpdtInsSQL($insSQL, "Bank Name:" . $extrInfo);
    }
    if ($affctd2 > 0) {
        $dsply = "Successfully Deleted the ff Records-";
        $dsply .= "<br/>$affctd1 Bank Branch(es)!";
        $dsply .= "<br/>$affctd2 Bank(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function deleteBankBranch($pkeyID, $extrInfo = "") {
    $selSQL = "Select count(1) from mcf.mcf_cust_account_trns_cheques WHERE cheque_branch_id = " . $pkeyID;
    $result = executeSQLNoParams($selSQL);
    $trnsCnt = 0;
    while ($row = loc_db_fetch_array($result)) {
        $trnsCnt = (float) $row[0];
    }
    if ($trnsCnt > 0) {
        $dsply = "No Record Deleted<br/>Cannot Delete Bank Branches used in Transactions!";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
    $selSQL1 = "Select count(1) from mcf.mcf_standing_orders WHERE extnl_branch_id = " . $pkeyID;
    $result1 = executeSQLNoParams($selSQL1);
    $trnsCnt1 = 0;
    while ($row = loc_db_fetch_array($result1)) {
        $trnsCnt1 = (float) $row[0];
    }
    if ($trnsCnt1 > 0) {
        $dsply = "No Record Deleted<br/>Cannot Delete Bank Branches used in Transactions!";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
    $affctd1 = 0;
    $affctd2 = 0;
    if (($trnsCnt1) <= 0) {
        $insSQL = "DELETE FROM mcf.mcf_bank_branches WHERE branch_id = " . $pkeyID . "";
        $affctd1 = execUpdtInsSQL($insSQL, "Currency Name:" . $extrInfo);
    }
    if ($affctd1 > 0) {
        $dsply = "Successfully Deleted the ff Records-";
        $dsply .= "<br/>$affctd1 Bank Branch(es)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function get_UnclearedTrns($searchFor, $searchIn, $offset, $limit_size, $orgID, $dte1, $dte2, $notcleared, $lowVal, $highVal) {
    $wherecls = " and e.status IN ('Paid','Received') and e.voided_acct_trns_id <=0";
    if ($dte1 != "") {
        $dte1 = cnvrtDMYTmToYMDTm($dte1);
    }
    if ($dte2 != "") {
        $dte2 = cnvrtDMYTmToYMDTm($dte2);
    }
    if ($notcleared) {
        $wherecls .= " and (a.is_cleared != '1')";
    }
    if ($lowVal != 0 || $highVal != 0) {
        $wherecls .= " and (a.amount !=0 and a.amount between " . $lowVal . " and " . $highVal . ")";
    }
    if ($searchIn === "Cheque Number") {
        $wherecls .= " and (a.cheque_no ilike '" .
                loc_db_escape_string($searchFor) . "')";
    } else if ($searchIn === "Bank/Branch") {
        $wherecls .= " and (mcf.get_bank_name(a.cheque_bank_id)||mcf.get_bank_code(a.cheque_bank_id) ilike '" .
                loc_db_escape_string($searchFor) . "' or mcf.get_branch_name(a.cheque_branch_id)||mcf.get_branch_code(a.cheque_branch_id) ilike '" .
                loc_db_escape_string($searchFor) . "')";
    } else if ($searchIn === "Beneficiary") {
        $wherecls .= " and (mcf.get_cust_accnt_num(e.account_id)||'.'|| mcf.get_cust_accnt_name(e.account_id) ilike '" .
                loc_db_escape_string($searchFor) . "')";
    } else {
        $wherecls .= " and (COALESCE(a.cheque_type,'') ilike '" .
                loc_db_escape_string($searchFor) . "' or a.cheque_no ilike '" .
                loc_db_escape_string($searchFor) . "' or mcf.get_bank_name(a.cheque_bank_id)||mcf.get_bank_code(a.cheque_bank_id) ilike '" .
                loc_db_escape_string($searchFor) . "' or mcf.get_branch_name(a.cheque_branch_id)||mcf.get_branch_code(a.cheque_branch_id) ilike '" .
                loc_db_escape_string($searchFor) . "' or mcf.get_cust_accnt_num(e.account_id)||'.'|| mcf.get_cust_accnt_name(e.account_id) ilike '" .
                loc_db_escape_string($searchFor) . "')";
    }

    $strSql = "
        SELECT a.trns_cheque_id, 
        a.acct_trns_id, 
        a.cheque_bank_id, 
        c.bank_name bnknm,
        a.cheque_branch_id, 
        d.branch_name brnchnm,
        a.cheque_no, 
        a.amount, 
        to_char(to_timestamp(a.value_date,'YYYY-MM-DD'),'DD-Mon-YYYY') value_date,  
            a.cheque_type, 
            to_char(to_timestamp(a.cheque_date,'YYYY-MM-DD'),'DD-Mon-YYYY') cheque_date, 
            a.cheque_type_id, 
            a.cheque_crncy_id, 
            b.mapped_lov_crncy_id, 
            gst.get_pssbl_val(b.mapped_lov_crncy_id) crncy_nm, 
            a.accnt_crncy_rate, 
            a.house_chq_src_accnt_id, 
            a.created_by, 
            sec.get_usr_prsn_id(a.created_by), 
            a.is_cleared, 
            CASE WHEN a.date_cleared !='' THEN to_char(to_timestamp(a.date_cleared,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY') ELSE '' END date_cleared,
            e.account_id,
            mcf.get_cust_accnt_num(e.account_id)||'.'|| mcf.get_cust_accnt_name(e.account_id) acc_info
            FROM mcf.mcf_cust_account_trns_cheques a
            LEFT OUTER JOIN mcf.mcf_cust_account_transactions e ON (a.acct_trns_id=e.acct_trns_id)
            LEFT OUTER JOIN mcf.mcf_currencies b ON (a.cheque_crncy_id = b.crncy_id) 
            LEFT OUTER JOIN mcf.mcf_all_banks c ON (a.cheque_bank_id = c.bank_id)  
            LEFT OUTER JOIN mcf.mcf_bank_branches d ON (a.cheque_branch_id = d.branch_id and d.bank_id = c.bank_id) 
       WHERE (e.org_id=$orgID and (to_timestamp(a.value_date || ' 00:00:00','YYYY-MM-DD HH24:MI:SS') between to_timestamp('" . $dte1 .
            "','YYYY-MM-DD HH24:MI:SS') AND to_timestamp('" . $dte2 . "','YYYY-MM-DD HH24:MI:SS')))" . $wherecls .
            " ORDER BY a.trns_cheque_id DESC LIMIT " . $limit_size .
            " OFFSET " . abs($offset * $limit_size);
//echo $strSql;
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_UnclearedTrnsTtl($searchFor, $searchIn, $orgID, $dte1, $dte2, $notcleared, $lowVal, $highVal) {
    $wherecls = " and e.status IN ('Paid','Received') and e.voided_acct_trns_id <=0";
    if ($dte1 != "") {
        $dte1 = cnvrtDMYTmToYMDTm($dte1);
    }
    if ($dte2 != "") {
        $dte2 = cnvrtDMYTmToYMDTm($dte2);
    }
    if ($notcleared) {
        $wherecls .= " and (a.is_cleared !='1')";
    }
    if ($lowVal != 0 || $highVal != 0) {
        $wherecls .= " and (a.amount !=0 and a.amount between " . $lowVal . " and " . $highVal .
                ")";
    }
    if ($searchIn === "Cheque Number") {
        $wherecls .= " and (a.cheque_no ilike '" .
                loc_db_escape_string($searchFor) . "')";
    } else if ($searchIn === "Bank/Branch") {
        $wherecls .= " and (mcf.get_bank_name(a.cheque_bank_id)||mcf.get_bank_code(a.cheque_bank_id) ilike '" .
                loc_db_escape_string($searchFor) . "' or mcf.get_branch_name(a.cheque_branch_id)||mcf.get_branch_code(a.cheque_branch_id) ilike '" .
                loc_db_escape_string($searchFor) . "')";
    } else if ($searchIn === "Beneficiary") {
        $wherecls .= " and (mcf.get_cust_accnt_num(e.account_id)||'.'|| mcf.get_cust_accnt_name(e.account_id) ilike '" .
                loc_db_escape_string($searchFor) . "')";
    } else {
        $wherecls .= " and (COALESCE(a.cheque_type,'') ilike '" .
                loc_db_escape_string($searchFor) . "' or a.cheque_no ilike '" .
                loc_db_escape_string($searchFor) . "' or mcf.get_bank_name(a.cheque_bank_id)||mcf.get_bank_code(a.cheque_bank_id) ilike '" .
                loc_db_escape_string($searchFor) . "' or mcf.get_branch_name(a.cheque_branch_id)||mcf.get_branch_code(a.cheque_branch_id) ilike '" .
                loc_db_escape_string($searchFor) . "' or mcf.get_cust_accnt_num(e.account_id)||'.'|| mcf.get_cust_accnt_name(e.account_id) ilike '" .
                loc_db_escape_string($searchFor) . "')";
    }
    $strSql = "SELECT count(1) FROM mcf.mcf_cust_account_trns_cheques a
            LEFT OUTER JOIN mcf.mcf_cust_account_transactions e ON (a.acct_trns_id=e.acct_trns_id)
            LEFT OUTER JOIN mcf.mcf_currencies b ON (a.cheque_crncy_id = b.crncy_id) 
            LEFT OUTER JOIN mcf.mcf_all_banks c ON (a.cheque_bank_id = c.bank_id)  
            LEFT OUTER JOIN mcf.mcf_bank_branches d ON (a.cheque_branch_id = d.branch_id and d.bank_id = c.bank_id) 
       WHERE (e.org_id=$orgID and (to_timestamp(a.value_date || ' 00:00:00','YYYY-MM-DD HH24:MI:SS') between to_timestamp('" . $dte1 .
            "','YYYY-MM-DD HH24:MI:SS') AND to_timestamp('" . $dte2 . "','YYYY-MM-DD HH24:MI:SS')))" . $wherecls;
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return 0;
}

function get_UnclearedTrnsSUM($orgID) {
    $strSql = "
        SELECT 
            SUM(a.amount),
            a.cheque_crncy_id, 
            b.mapped_lov_crncy_id, 
            gst.get_pssbl_val(b.mapped_lov_crncy_id) crncy_nm
        FROM mcf.mcf_cust_account_trns_cheques a
            LEFT OUTER JOIN mcf.mcf_cust_account_transactions e ON (a.acct_trns_id=e.acct_trns_id)
            LEFT OUTER JOIN mcf.mcf_currencies b ON (a.cheque_crncy_id = b.crncy_id)
        WHERE (e.org_id=$orgID and a.is_cleared != '1')  
           GROUP BY 2,3,4
           ORDER BY 4";
//echo $strSql;
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_AcntTrnsfrs($searchFor, $searchIn, $offset, $limit_size, $orgID, $dte1, $dte2, $isRecurring, $isNonRecurring, $lowVal, $highVal, $qNonExecuted = false) {
    $wherecls = "";
    if ($dte1 != "") {
        $dte1 = cnvrtDMYTmToYMDTm($dte1);
    }
    if ($dte2 != "") {
        $dte2 = cnvrtDMYTmToYMDTm($dte2);
    }
    if ($isRecurring == $isNonRecurring) {
        $wherecls .= " and (1=1)";
    } else {
        if ($isRecurring) {
            $wherecls .= " and (a.frqncy_type != 'LifeTime')";
        }
        if ($isNonRecurring) {
            $wherecls .= " and (a.frqncy_type = 'LifeTime')";
        }
    }
    if ($qNonExecuted) {
        $wherecls .= " and ((Select count(1) from mcf.mcf_standing_order_executions z WHERE z.stndn_order_id = a.stndn_order_id and z.was_trnsfr_sccfl='1')<=0)";
    }
    if ($lowVal != 0 || $highVal != 0) {
        $wherecls .= " and (a.amount !=0 and a.amount between " . $lowVal . " and " . $highVal . ")";
    }
    if ($searchIn === "Account Number/Name") {
        $wherecls .= " and (e.account_title ilike '" .
                loc_db_escape_string($searchFor) . "' or e.account_number ilike '" .
                loc_db_escape_string($searchFor) . "' or a.dest_acct_or_wallet_no ilike '" .
                loc_db_escape_string($searchFor) . "' or a.extnl_bnfcry_name ilike '" .
                loc_db_escape_string($searchFor) . "')";
    } else if ($searchIn === "Bank/Branch") {
        $wherecls .= " and (mcf.get_bank_name(a.extnl_bank_id)||mcf.get_bank_code(a.extnl_bank_id) ilike '" .
                loc_db_escape_string($searchFor) . "' or mcf.get_branch_name(a.extnl_branch_id)||mcf.get_branch_code(a.extnl_branch_id) ilike '" .
                loc_db_escape_string($searchFor) . "')";
    } else {
        $wherecls .= " and (e.account_title ilike '" .
                loc_db_escape_string($searchFor) . "' or e.account_number ilike '" .
                loc_db_escape_string($searchFor) . "' or mcf.get_bank_name(a.extnl_bank_id)||mcf.get_bank_code(a.extnl_bank_id) ilike '" .
                loc_db_escape_string($searchFor) . "' or mcf.get_branch_name(a.extnl_branch_id)||mcf.get_branch_code(a.extnl_branch_id) ilike '" .
                loc_db_escape_string($searchFor) . "' or a.dest_type ilike '" .
                loc_db_escape_string($searchFor) . "' or a.dest_acct_or_wallet_no ilike '" .
                loc_db_escape_string($searchFor) . "' or a.transfer_type ilike '" .
                loc_db_escape_string($searchFor) . "' or a.frqncy_type ilike '" .
                loc_db_escape_string($searchFor) . "' or a.extnl_bnfcry_name ilike '" .
                loc_db_escape_string($searchFor) . "' or a.extnl_bnfcry_pstl_addrs ilike '" .
                loc_db_escape_string($searchFor) . "' or gst.get_pssbl_val(b.mapped_lov_crncy_id) ilike '" .
                loc_db_escape_string($searchFor) . "')";
    }

    $strSql = "SELECT a.stndn_order_id, 
                   a.src_account_id, 
                   a.dest_type, 
                   a.dest_acct_or_wallet_no, 
                   a.transfer_type, 
                   a.amount + COALESCE((select sum(z.amount) from mcf.mcf_stnd_ordr_dstntns z where z.stndn_order_id = a.stndn_order_id),0)
                    + COALESCE((select sum(x.amount) from mcf.mcf_stnd_ordr_sources x where x.stndn_order_id = a.stndn_order_id),0) amount, 
                   a.frqncy_no, 
                   a.frqncy_type, 
                   to_char(to_timestamp(a.start_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') start_date, 
                   to_char(to_timestamp(a.end_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') end_date, 
                   a.extnl_bank_id, 
                   mcf.get_bank_name(a.extnl_bank_id),
                   a.extnl_branch_id, 
                   mcf.get_branch_name(a.extnl_branch_id),
                   a.extnl_bnfcry_name, 
                   a.extnl_bnfcry_pstl_addrs, 
                    a.currency_id, 
                    b.mapped_lov_crncy_id, 
                    gst.get_pssbl_val(b.mapped_lov_crncy_id) crncy_nm, 
                    a.negotiated_exch_rate,
                    (select count(1) from mcf.mcf_standing_order_executions z where z.stndn_order_id = a.stndn_order_id and z.was_trnsfr_sccfl='1') sccfl_exctns, 
                    (select count(1) from mcf.mcf_standing_order_executions z where z.stndn_order_id = a.stndn_order_id and z.was_trnsfr_sccfl='0') incmplt_exctns,
                    a.created_by, 
                    a.creation_date, 
                    a.last_update_by, 
                    a.last_update_date,
                    e.account_number,
                    e.account_title,
                    a.status
        FROM mcf.mcf_standing_orders a        
            LEFT OUTER JOIN mcf.mcf_currencies b ON (a.currency_id = b.crncy_id) 
            LEFT OUTER JOIN mcf.mcf_accounts e ON (a.src_account_id = e.account_id)
       WHERE (e.org_id=$orgID and (to_timestamp(a.start_date,'YYYY-MM-DD HH24:MI:SS') between to_timestamp('" . $dte1 .
            "','YYYY-MM-DD HH24:MI:SS') AND to_timestamp('" . $dte2 . "','YYYY-MM-DD HH24:MI:SS')))" . $wherecls .
            " ORDER BY a.stndn_order_id DESC LIMIT " . $limit_size .
            " OFFSET " . abs($offset * $limit_size);
//echo $strSql;
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_AcntTrnsfrsTtl($searchFor, $searchIn, $orgID, $dte1, $dte2, $isRecurring, $isNonRecurring, $lowVal, $highVal, $qNonExecuted = false) {
    $wherecls = "";
    if ($dte1 != "") {
        $dte1 = cnvrtDMYTmToYMDTm($dte1);
    }
    if ($dte2 != "") {
        $dte2 = cnvrtDMYTmToYMDTm($dte2);
    }
    if ($isRecurring == $isNonRecurring) {
        $wherecls .= " and (1=1)";
    } else {
        if ($isRecurring) {
            $wherecls .= " and (a.frqncy_type != 'LifeTime')";
        }
        if ($isNonRecurring) {
            $wherecls .= " and (a.frqncy_type = 'LifeTime')";
        }
    }
    if ($qNonExecuted) {
        $wherecls .= " and ((Select count(1) from mcf.mcf_standing_order_executions z WHERE z.stndn_order_id = a.stndn_order_id and z.was_trnsfr_sccfl='1')<=0)";
    }
    if ($lowVal != 0 || $highVal != 0) {
        $wherecls .= " and (a.amount !=0 and a.amount between " . $lowVal . " and " . $highVal . ")";
    }
    if ($searchIn === "Account Number/Name") {
        $wherecls .= " and (e.account_title ilike '" .
                loc_db_escape_string($searchFor) . "' or e.account_number ilike '" .
                loc_db_escape_string($searchFor) . "' or a.dest_acct_or_wallet_no ilike '" .
                loc_db_escape_string($searchFor) . "' or a.extnl_bnfcry_name ilike '" .
                loc_db_escape_string($searchFor) . "')";
    } else if ($searchIn === "Bank/Branch") {
        $wherecls .= " and (mcf.get_bank_name(a.extnl_bank_id)||mcf.get_bank_code(a.extnl_bank_id) ilike '" .
                loc_db_escape_string($searchFor) . "' or mcf.get_branch_name(a.extnl_branch_id)||mcf.get_branch_code(a.extnl_branch_id) ilike '" .
                loc_db_escape_string($searchFor) . "')";
    } else {
        $wherecls .= " and (e.account_title ilike '" .
                loc_db_escape_string($searchFor) . "' or e.account_number ilike '" .
                loc_db_escape_string($searchFor) . "' or mcf.get_bank_name(a.extnl_bank_id)||mcf.get_bank_code(a.extnl_bank_id) ilike '" .
                loc_db_escape_string($searchFor) . "' or mcf.get_branch_name(a.extnl_branch_id)||mcf.get_branch_code(a.extnl_branch_id) ilike '" .
                loc_db_escape_string($searchFor) . "' or a.dest_type ilike '" .
                loc_db_escape_string($searchFor) . "' or a.dest_acct_or_wallet_no ilike '" .
                loc_db_escape_string($searchFor) . "' or a.transfer_type ilike '" .
                loc_db_escape_string($searchFor) . "' or a.frqncy_type ilike '" .
                loc_db_escape_string($searchFor) . "' or a.extnl_bnfcry_name ilike '" .
                loc_db_escape_string($searchFor) . "' or a.extnl_bnfcry_pstl_addrs ilike '" .
                loc_db_escape_string($searchFor) . "' or gst.get_pssbl_val(b.mapped_lov_crncy_id) ilike '" .
                loc_db_escape_string($searchFor) . "')";
    }

    $strSql = "SELECT count(a.stndn_order_id)
  FROM mcf.mcf_standing_orders a        
            LEFT OUTER JOIN mcf.mcf_currencies b ON (a.currency_id = b.crncy_id) 
            LEFT OUTER JOIN mcf.mcf_accounts e ON (a.src_account_id = e.account_id)
       WHERE (e.org_id=$orgID and (to_timestamp(a.start_date,'YYYY-MM-DD HH24:MI:SS') between to_timestamp('" . $dte1 .
            "','YYYY-MM-DD HH24:MI:SS') AND to_timestamp('" . $dte2 . "','YYYY-MM-DD HH24:MI:SS')))" . $wherecls;
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return 0;
}

function get_One_TrnsfrExctns($dochdrID) {
    $strSql = "SELECT a.stndn_order_exec_id, 
        a.src_acnt_trns_id,
        mcf.get_cust_accnt_num(b.account_id) ||'.'||mcf.get_cust_accnt_name(b.account_id) srcAcntName,
        a.ach_acnt_trns_id, 
       CASE WHEN a.ach_acnt_trns_id <=0 THEN d.dest_acct_or_wallet_no ||'.'||extnl_bnfcry_name WHEN a.stndn_order_misc_id<=0 THEN mcf.get_cust_accnt_num(c.account_id) ||'.'||mcf.get_cust_accnt_name(c.account_id) ELSE '' END destAcntName,
		d.dest_type,
        a.was_trnsfr_sccfl, 
        a.failure_reason, 
        a.created_by, 
        a.creation_date, 
        a.last_update_by, 
        a.last_update_date,
		b.amount,
		b.entered_crncy_id,
		e.iso_code
  FROM mcf.mcf_standing_order_executions a 
	   LEFT OUTER JOIN mcf.mcf_standing_orders d ON (a.stndn_order_id=d.stndn_order_id)
       LEFT OUTER JOIN mcf.mcf_cust_account_transactions b ON (a.src_acnt_trns_id = b.acct_trns_id) 
       LEFT OUTER JOIN mcf.mcf_cust_account_transactions c ON (a.ach_acnt_trns_id = c.acct_trns_id)
	   LEFT OUTER JOIN mcf.mcf_currencies e ON (e.crncy_id=b.entered_crncy_id)
  WHERE a.stndn_order_id=" . $dochdrID
            . "ORDER BY a.stndn_order_exec_id DESC LIMIT 10 OFFSET 0";
    return executeSQLNoParams($strSql);
}

function get_One_AcntTrnsfrHdr($trnsHdrID) {
    $whereCls = "";
    $strSql = "SELECT a.stndn_order_id, 
                        a.src_account_id,
                        mcf.get_cust_accnt_num(a.src_account_id) srcAcntNum,
                        mcf.get_cust_accnt_name(a.src_account_id) srcAcntName,
                        a.dest_type, 
                        a.dest_acct_or_wallet_no, 
                        a.transfer_type, 
                        a.amount, 
                        a.frqncy_no, 
                        a.frqncy_type, 
                        to_char(to_timestamp(a.start_date,'YYYY-MM-DD'),'DD-Mon-YYYY') start_date, 
                        CASE WHEN a.end_date='' THEN '' ELSE to_char(to_timestamp(a.end_date,'YYYY-MM-DD'),'DD-Mon-YYYY HH24:MI:SS') END end_date, 
                        a.extnl_bank_id, 
                        mcf.get_bank_code(a.extnl_bank_id) || '.' || mcf.get_bank_name(a.extnl_bank_id) bnknm,
                        a.extnl_branch_id, 
                        mcf.get_branch_code(a.extnl_branch_id) || '.' || mcf.get_branch_name(a.extnl_branch_id) brnchnm,
                        a.extnl_bnfcry_name, 
                        a.extnl_bnfcry_pstl_addrs, 
                        a.created_by, 
                        a.creation_date, 
                        a.last_update_by, 
                        a.last_update_date, 
                        a.branch_id, 
                        a.currency_id, 
                        a.negotiated_exch_rate, 
                        a.rmrk_narration, 
                        a.status,
                        a.authorized_by_person_id,
                        COALESCE(prs.get_prsn_name(a.authorized_by_person_id),'') athrzr_nm,
                        CASE WHEN a.autorization_date='' THEN '' ELSE to_char(to_timestamp(a.autorization_date,'YYYY-MM-DD'),'DD-Mon-YYYY HH24:MI:SS') END authorization_date,
                        a.cheque_slip_no, 
                        b.mapped_lov_crncy_id, 
                        gst.get_pssbl_val(b.mapped_lov_crncy_id) crncy_nm,
                        a.dflt_gl_acnt_id,
                        accb.get_accnt_num(a.dflt_gl_acnt_id) || '.' || accb.get_accnt_name(a.dflt_gl_acnt_id) gl_acc_nm
            FROM mcf.mcf_standing_orders a        
            LEFT OUTER JOIN mcf.mcf_currencies b ON (a.currency_id = b.crncy_id) 
                WHERE ((a.stndn_order_id = $trnsHdrID)$whereCls)";
    $result = executeSQLNoParams($strSql);
    return $result;
}

function getTrnsfrMxRoutingID($srcDocID, $srcDocType = "Transfer Transactions") {
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

function isMCFGLIntrfcBlcdOrg($orgID) {
    $strSql = "SELECT COALESCE(SUM(a.dbt_amount),0) dbt_sum, 
COALESCE(SUM(a.crdt_amount),0) crdt_sum 
FROM mcf.mcf_gl_interface a, accb.accb_chart_of_accnts b 
WHERE a.gl_batch_id = -1 and a.accnt_id = b.accnt_id and b.org_id=" . $orgID .
            " ";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        $dffrce1 = (float) $row[0] - (float) $row[1];
        $dffrce = abs(round($dffrce1, 2));
        if ($dffrce == 0) {
            return true;
        } else {
            return false;
        }
    }
    return false;
}

function getMCFGLIntrfcDffrnc($orgID) {
    $strSql = "SELECT COALESCE(SUM(a.dbt_amount),0) dbt_sum, 
COALESCE(SUM(a.crdt_amount),0) crdt_sum 
FROM mcf.mcf_gl_interface a, accb.accb_chart_of_accnts b 
WHERE a.gl_batch_id = -1 and a.accnt_id = b.accnt_id and b.org_id=" . $orgID .
            " ";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        $dffrce1 = (float) $row[0] - (float) $row[1];
        return $dffrce1;
    }
    return 0;
}

function get_CustAccountsTtl_MCF2($statusSrchIn, $branchSrchIn, $prdtTypeSrchIn, $isEnabled, $searchFor, $searchIn, $orgID, $searchAll) {
    $extra1 = "";

    if ($searchAll == true) {
        $extra1 = "or 1 = 1";
    }
    $cnt = 0;
    $strSql = "";
    $whrcls = "";
    if ($searchIn == "Account Title") {
        $whrcls = " AND (a.account_title ilike '" . loc_db_escape_string($searchFor) . "')";
    } else if ($searchIn == "Account Number") {
        $whrcls = " AND (a.account_number ilike '" . loc_db_escape_string($searchFor) . "')";
    } else if ($searchIn == "Account Type") {
        $whrcls = " AND (a.account_type ilike '" . loc_db_escape_string($searchFor) . "')";
    } else if ($searchIn == "Branch") {
        $v_branch_ids = get_BankBranchIds($searchFor);
        $whrcls = " AND (a.branch_id IN (" . $v_branch_ids . "))";
    }

    if ($isEnabled == "true") {
        $whrcls .= " and upper(a.status) = 'UNAUTHORIZED'";
    }

    /* if ($prdtTypeSrchIn == "All Account Types") {
      $whrcls .= " and 1 = 1";
      } else {
      $whrcls .= " and account_type = '" . loc_db_escape_string($prdtTypeSrchIn) . "'";
      } */
    if ($prdtTypeSrchIn == "All Product Types") {
        $whrcls .= " and 1 = 1";
    } else {
        $whrcls .= " and upper(mcf.get_ac_prdt_name(account_type, product_type_id)) = upper('" . loc_db_escape_string($prdtTypeSrchIn) . "')";
    }
    if ($branchSrchIn == "All Branches") {
        $whrcls .= " and 1 = 1";
    } else {
        $whrcls .= " and a.branch_id = $branchSrchIn";
    }

    if ($statusSrchIn == "All Statuses") {
        $whrcls .= " and 1 = 1";
    } else {
        $whrcls .= "  and a.status = '$statusSrchIn'";
    }

    $strSql1 = "SELECT count(1) " .
            "FROM mcf.mcf_accounts a "
            . "WHERE ((a.org_id = " . $orgID . " " . $extra1 . ")" . $whrcls . ")";
    $result1 = executeSQLNoParams($strSql1);
    //var_dump($strSql);
    while ($row1 = loc_db_fetch_array($result1)) {
        $cnt = $row1[0];
    }

    $strSql2 = "SELECT count(1) " .
            "FROM mcf.mcf_accounts_hstrc a "
            . "WHERE (1 = 1 AND a.rvsn_ttl = (SELECT max(rvsn_ttl) FROM mcf.mcf_accounts_hstrc c WHERE a.account_id = c.account_id) "
            . "AND (a.org_id = " . $orgID . " " . $extra1 . ")" . $whrcls . ")";
    $result2 = executeSQLNoParams($strSql2);
    //var_dump($strSql);
    while ($row2 = loc_db_fetch_array($result2)) {
        $cnt = $cnt + $row2[0];
    }

    return $cnt;
}

function get_CustAccounts_MCF2($statusSrchIn, $branchSrchIn, $prdtTypeSrchIn, $isEnabled, $searchFor, $searchIn, $offset, $limit_size, $orgID, $searchAll, $sortBy) {
    $extra1 = "";

    if ($searchAll == true) {
        $extra1 = "or 1 = 1";
    }

    $strSql = "";
    $whrcls = "";
    if ($searchIn == "Account Title") {
        $whrcls = " AND (a.account_title ilike '" . loc_db_escape_string($searchFor) . "')";
    } else if ($searchIn == "Account Number") {
        $whrcls = " AND (a.account_number ilike '" . loc_db_escape_string($searchFor) . "')";
    } else if ($searchIn == "Account Type") {
        $whrcls = " AND (a.account_type ilike '" . loc_db_escape_string($searchFor) . "')";
    } else if ($searchIn == "Branch") {
        $v_branch_ids = get_BankBranchIds($searchFor);
        $whrcls = " AND (a.branch_id IN (" . v_branch_ids . "))";
    }

    if ($isEnabled == "true") {
        $whrcls .= " and upper(a.status) = 'UNAUTHORIZED'";
    }

    if ($prdtTypeSrchIn == "All Product Types") {
        $whrcls .= " and 1 = 1";
    } else {
        $whrcls .= " and upper(mcf.get_ac_prdt_name(account_type, product_type_id)) = upper('" . loc_db_escape_string($prdtTypeSrchIn) . "')";
    }

    if ($branchSrchIn == "All Branches") {
        $whrcls .= " and 1 = 1";
    } else {
        $whrcls .= " and a.branch_id = $branchSrchIn";
    }

    if ($statusSrchIn == "All Statuses") {
        $whrcls .= " and 1 = 1";
    } else {
        $whrcls .= "  and a.status = '$statusSrchIn'";
    }

    if ($sortBy == "Date Added DESC") {
        $ordrBy = "last_update_date DESC";
    } else if ($sortBy == "Account Title DESC") {
        $ordrBy = "account_title DESC";
    } else if ($sortBy == "Account Title ASC") {
        $ordrBy = "account_title ASC";
    } else if ($sortBy == "Account Number") {
        $ordrBy = "account_number ASC";
    } else if ($sortBy == "Account Type ASC") {
        $ordrBy = "account_type ASC";
    }

//    $strSql = "SELECT account_id, account_title, account_number, account_type, " .
//            "(SELECT site_desc||' ('||location_code_name||')' FROM org.org_sites_locations WHERE location_id = a.branch_id) branch, status " .
//            "FROM mcf.mcf_accounts a " .
//            "WHERE ((a.org_id = " . $orgID . " " . $extra1 . ")" . $whrcls .
//            ") ORDER BY " . $ordrBy . " LIMIT " . $limit_size .
//            " OFFSET " . abs($offset * $limit_size);

    $strSql = "SELECT account_id, account_title, account_number, account_type, " .
            "(SELECT site_desc||' ('||location_code_name||')' FROM org.org_sites_locations WHERE location_id = a.branch_id) branch, status, last_update_date, mcf.get_ac_prdt_name(account_type, product_type_id) prdct_nm " .
            "FROM mcf.mcf_accounts a " .
            "WHERE ( 1 = 1 AND a.chngs_pndng = 0 AND (a.org_id = " . $orgID . " " . $extra1 . ")" . $whrcls .
            ") UNION "
            . "SELECT account_id, account_title, account_number, account_type, " .
            "(SELECT site_desc||' ('||location_code_name||')' FROM org.org_sites_locations WHERE location_id = a.branch_id) branch, status, last_update_date, mcf.get_ac_prdt_name(account_type, product_type_id) prdct_nm " .
            "FROM mcf.mcf_accounts_hstrc a " .
            "WHERE (1 = 1 AND a.rvsn_ttl = (SELECT max(rvsn_ttl) FROM mcf.mcf_accounts_hstrc c WHERE"
            . " c.account_id = a.account_id) AND a.status NOT IN ('Approved','Authorized') AND (a.org_id = " . $orgID . " " . $extra1 . ")" . $whrcls .
            ") ORDER BY " . $ordrBy . " LIMIT " . $limit_size .
            " OFFSET " . abs($offset * $limit_size);
    $result = executeSQLNoParams($strSql);
    //var_dump($strSql);
    return $result;
}

function isBioDataPrsnt($prsnCustID, $prsnTyp = "IND") {
    $sql = "SELECT user_id FROM demo_user WHERE person_cust_id = " . $prsnCustID .
            " and prsn_type='" . mysq_db_escape_string($prsnTyp) . "'";
    //echo $sql;
    $result = executeMySQLQry($sql);
    while ($row = mysq_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -1;
}

function createBioData($prsnCustID, $prsnTyp = "IND") {
    $sql = "INSERT INTO demo_user SET user_name='" . mysq_db_escape_string("BIO_USR_" . $prsnTyp . "_" . $prsnCustID) .
            "', person_cust_id=" . $prsnCustID .
            ", prsn_type='" . mysq_db_escape_string($prsnTyp) . "'";
    return execMySQLiUpdtInsSQL($sql);
}

function deleteBioData($prsnCustID, $prsnTyp = "IND") {
    $sql = "DELETE FROM demo_user WHERE person_cust_id=" . $prsnCustID . " " .
            " and prsn_type='" . mysq_db_escape_string($prsnTyp) . "'";
    return execMySQLiUpdtInsSQL($sql);
}

function getBioData($prsnCustID, $prsnTyp = "IND") {
    $sql = "SELECT user_id,user_name,person_cust_id, prsn_type FROM demo_user "
            . "WHERE person_cust_id=" . $prsnCustID . " " .
            " and prsn_type='" . mysq_db_escape_string($prsnTyp) . "'";

    $result = executeMySQLQry($sql);
    $arr = array();
    $i = 0;
    while ($row = mysq_db_fetch_array($result)) {

        $arr[$i] = array(
            'user_id' => $row[0],
            'user_name' => $row[1]
        );
        $i++;
    }
    return $arr;
}

function getUserBioFinger($user_id) {
    $sql = "SELECT user_id, finger_id, finger_data FROM demo_finger WHERE user_id= '" . $user_id . "' ";
    $result = executeMySQLQry($sql);
    $arr = array();
    $i = 0;
    while ($row = mysq_db_fetch_array($result)) {
        $arr[$i] = array(
            'user_id' => $row[0],
            "finger_id" => $row[1],
            "finger_data" => $row[2]
        );
        $i++;
    }
    return $arr;
}
?>