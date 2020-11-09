<?php

function getInvPgPrmssns($prsnID, $orgid, $usrID) {
    global $ssnRoles;
    $mdlNm = "Stores And Inventory Manager";
    $rslts = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
    $sqlStr = "Select (select oprtnl_crncy_id from org.org_details where org_id = $orgid), "
            . "pasn.get_prsn_siteid(" . $prsnID . "), "
            . "pasn.get_prsn_divid_of_spctype(" . $prsnID . ",'Access Control Group'),"
            . "scm.getUserStoreID(" . $usrID . ", " . $orgid . "), "
            . "sec.test_prmssns('View Inventory Manager', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "') vwInvntry, "
            . "sec.test_prmssns('View Sales/Item Issues', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
            . "sec.test_prmssns('View Purchases', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
            . "sec.test_prmssns('View Item List', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
            . "sec.test_prmssns('View Product Categories', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
            . "sec.test_prmssns('View Stores/Warehouses', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
            . "sec.test_prmssns('View Receipts', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
            . "sec.test_prmssns('View Receipt Returns', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
            . "sec.test_prmssns('View Item Type Templates', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
            . "sec.test_prmssns('View Item Balances', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
            . "sec.test_prmssns('View UOM', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
            . "sec.test_prmssns('View GL Interface', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
            . "sec.test_prmssns('View Item Production', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
            . "sec.test_prmssns('View Record History', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
            . "sec.test_prmssns('View SQL', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
            . "sec.test_prmssns('View only Self-Created Documents', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'),"
            . "sec.test_prmssns('Can Edit Unit Price', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'),"
            . "sec.test_prmssns('View only Branch-Related Documents', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "')";
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
    }
    return $rslts;
}

function getScmSalesPrmssns($orgid) {
    global $ssnRoles;
    global $mdlNm;
    $rslts = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
    $sqlStr = "Select sec.test_prmssns('Add Pro-Forma Invoices', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
            . "sec.test_prmssns('Edit Pro-Forma Invoices', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
            . "sec.test_prmssns('Delete Pro-Forma Invoices', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
            . "sec.test_prmssns('Add Sales Orders', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
            . "sec.test_prmssns('Edit Sales Orders', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
            . "sec.test_prmssns('Delete Sales Orders', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
            . "sec.test_prmssns('Add Sales Invoices', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
            . "sec.test_prmssns('Edit Sales Invoices', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
            . "sec.test_prmssns('Delete Sales Invoices', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
            . "sec.test_prmssns('Add Internal Item Requests', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
            . "sec.test_prmssns('Edit Internal Item Requests', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
            . "sec.test_prmssns('Delete Internal Item Requests', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
            . "sec.test_prmssns('Add Item Issues-Unbilled', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
            . "sec.test_prmssns('Edit Item Issues-Unbilled', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
            . "sec.test_prmssns('Delete Item Issues-Unbilled', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
            . "sec.test_prmssns('Add Sales Return', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
            . "sec.test_prmssns('Edit Sales Return', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
            . "sec.test_prmssns('Delete Sales Return', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
            . "sec.test_prmssns('View only Self-Created Documents', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
            . "sec.test_prmssns('Make Payments', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
            . "sec.test_prmssns('Cancel Documents', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
            . "sec.test_prmssns('Can Edit Unit Price', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "')";
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
        /*  $rslts[22] = ((int) $row[22]);
          $rslts[23] = ((int) $row[23]);
          $rslts[24] = ((int) $row[24]);
          $rslts[25] = ((int) $row[25]);
          $rslts[26] = ((int) $row[26]); */
    }
    return $rslts;
}

function getScmPrchsPrmssns($orgid) {
    global $ssnRoles;
    global $mdlNm;
    $rslts = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
    $sqlStr = "Select sec.test_prmssns('Add Purchase Requisitions', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
            . "sec.test_prmssns('Edit Purchase Requisitions', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
            . "sec.test_prmssns('Delete Purchase Requisitions', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
            . "sec.test_prmssns('Add Purchase Orders', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
            . "sec.test_prmssns('Edit Purchase Orders', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
            . "sec.test_prmssns('Delete Purchase Orders', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
            . "sec.test_prmssns('View only Self-Created Documents', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
            . "sec.test_prmssns('Make Payments', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
            . "sec.test_prmssns('Cancel Documents', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
            . "sec.test_prmssns('Can Edit Unit Price', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "')";
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
        /*
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
          $rslts[26] = ((int) $row[26]); */
    }
    return $rslts;
}

function createPaymntLine($pymtTyp, $amnt, $curBals, $payRmrk, $srcDocTyp, $srcDocID, $dateStr, $dateRcvd) {
    $dateStr = getDB_Date_time();
    global $usrID;
    global $orgID;

    $insSQL = "INSERT INTO scm.scm_payments(" .
            "pymnt_type, amount_paid, custmrs_balance, pymnt_remark, " .
            "src_doc_typ, src_doc_id, created_by, creation_date, last_update_by, " .
            "last_update_date, date_rcvd) " .
            "VALUES ('" . loc_db_escape_string($pymtTyp) . "',$amnt,$curBals, '" . loc_db_escape_string($payRmrk) .
            "','" . loc_db_escape_string($srcDocTyp) .
            "',$srcDocID,$usrID, '" . $dateStr . "',$usrID, '" . $dateStr . "', '" . $dateRcvd . "')";
    execUpdtInsSQL($insSQL);
}

function createTodaysGLBatch($orgid, $batchnm, $batchdesc, $batchsource) {
    $dateStr = getDB_Date_time();
    global $usrID;
    global $orgID;

    $insSQL = "INSERT INTO accb.accb_trnsctn_batches(" .
            "batch_name, batch_description, created_by, creation_date, " .
            "org_id, batch_status, last_update_by, last_update_date, batch_source) " .
            "VALUES ('" . loc_db_escape_string($batchnm) . "', '" . loc_db_escape_string($batchdesc) .
            "',$usrID, '" . $dateStr . "',$orgid, '0', $usrID, 
              '" . $dateStr . "','" . loc_db_escape_string($batchsource) . "')";
    execUpdtInsSQL($insSQL);
}

function createPymntGLLine($accntid, $trnsdesc, $dbtamnt, $trnsdte, $crncyid, $batchid, $crdtamnt, $netamnt, $srcids, $dateStr) {
    $dateStr = getDB_Date_time();
    global $usrID;
    global $orgID;

    if (accntid <= 0) {
        return;
    }
    $insSQL = "INSERT INTO accb.accb_trnsctn_details(" .
            "accnt_id, transaction_desc, dbt_amount, trnsctn_date, " .
            "func_cur_id, created_by, creation_date, batch_id, crdt_amount, " .
            "last_update_by, last_update_date, net_amount, trns_status, source_trns_ids) " .
            "VALUES ($accntid, '" . loc_db_escape_string($trnsdesc) . "', $dbtamnt, 
               '" . loc_db_escape_string($trnsdte) . "',$crncyid, $usrID, 
                '" . $dateStr . "',$batchid,$crdtamnt,$usrID, 
               '" . $dateStr . "',$netamnt, '0', '" . $srcids . "')";
    execUpdtInsSQL($insSQL);
}

function createPymntGLIntFcLn($accntid, $trnsdesc, $dbtamnt, $trnsdte, $crncyid, $crdtamnt, $netamnt, $srcDocTyp, $srcDocID, $srcDocLnID,
        $dateStr) {
    $dateStr = getDB_Date_time();
    global $usrID;
    global $orgID;

    if ($accntid <= 0) {
        return;
    }
    $insSQL = "INSERT INTO scm.scm_gl_interface(" .
            "accnt_id, transaction_desc, dbt_amount, trnsctn_date, " .
            "func_cur_id, created_by, creation_date, crdt_amount, last_update_by, " .
            "last_update_date, net_amount, gl_batch_id, src_doc_typ, src_doc_id, " .
            "src_doc_line_id) " .
            "VALUES ($accntid, '" . loc_db_escape_string($trnsdesc) . "',$dbtamnt, 
               '" . loc_db_escape_string($trnsdte) . "',$crncyid,$usrID, '" . $dateStr . "',$crdtamnt,$usrID, 
                   '" . $dateStr . "',$netamnt, -1,'" . loc_db_escape_string($srcDocTyp) . "',$srcDocID, $srcDocLnID)";
    execUpdtInsSQL($insSQL);
}

function getPurchOdrID($parPONo) {
    global $orgID;
    $sqlStr = "SELECT prchs_doc_hdr_id from scm.scm_prchs_docs_hdr
          WHERE purchase_doc_num = $parPONo AND org_id = $orgID";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return 0;
}

function getPurchOdrNo($parPOID) {
    $sqlStr = "select purchase_doc_num from scm.scm_prchs_docs_hdr 
         where prchs_doc_hdr_id = $parPOID";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return 0;
}

function dispDBTimeLong($parDTime) {
    if ($parDTime == "") {
        return "";
    } else {
        return to_char(to_timestamp($parDTime, 'YYYY-MM-DD HH24:MI:SS'), 'DD-Mon-YYYY HH24:MI:SS');
    }
}

function getGnrlRecIDilike($tblNm, $srchcol, $rtrnCol, $recname, $orgid) {
    $sqlStr = "select " . $rtrnCol . " from " . $tblNm . " where lower(" . $srchcol . ") ilike lower('" .
            loc_db_escape_string($recname) . "') and org_id = " . $orgid;
//echo "<p>".$sqlStr ."</p>";
    $result = executeSQLNoParams($sqlStr);
    $ftchrslt = "'";
    $ftchrslt1 = "'";
    while ($row = loc_db_fetch_array($result)) {
        $ftchrslt1 .= $row[0] . "','";
    }
    if ($ftchrslt1 != "'") {
        $finrst = $ftchrslt1 . $ftchrslt;
        return str_ireplace(",''", "", $finrst);
    }
    return -1;
}

function getSalesDocLnID($itmID, $storeID, $srcDocID) {
    $sqlStr = "select y.invc_det_ln_id " .
            "from scm.scm_sales_invc_det y " .
            "where y.itm_id=  $itmID and y.store_id= $storeID 
            and y.invc_hdr_id=" . $srcDocID . " ";
    $result = executeSQLNoParams($sqlStr);
    if (loc_db_num_rows($result) > 0) {
        $row = loc_db_fetch_array($result);
        return $row[0];
    }
    return -1;
}

function getSalesSmmryItmID($smmryType, $codeBhnd, $srcDocID, $srcDocTyp) {
    $sqlStr = "select y.smmry_id " .
            "from scm.scm_doc_amnt_smmrys y " .
            "where y.smmry_type= '" . $smmryType . "' and y.code_id_behind= " . $codeBhnd .
            " and y.src_doc_type='" . $srcDocTyp .
            "' and y.src_doc_hdr_id=" . $srcDocID . " ";
    $result = executeSQLNoParams($sqlStr);
    if (loc_db_num_rows($result) > 0) {
        $row = loc_db_fetch_array($result);
        return $row[0];
    }
    return -1;
}

function get_One_SalesDcDt1($dochdrID) {
    $strSql = "SELECT a.invc_hdr_id, a.invc_number, " .
            "a.invc_type, a.src_doc_hdr_id, a.invc_date, " .
            "a.customer_id, a.customer_site_id, a.comments_desc, a.payment_terms, " .
            "a.approval_status, a.next_aproval_action, " .
            "a.created_by, a.branch_id, org.get_site_code_desc(a.branch_id) " .
            "FROM scm.scm_sales_invc_hdr a " .
            "WHERE(a.invc_hdr_id = " . $dochdrID .
            ")";

    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_One_SalesDcDt($dochdrID) {
    $sqlStr = "SELECT a.invc_hdr_id mt, 
        CASE WHEN a.invc_type = 'Sales Order' THEN 'Sales Invoice'
        WHEN a.invc_type = 'Pro-Forma Invoice' THEN 'Sales Order'
        WHEN a.invc_type = 'Internal Item Request' THEN 'Item Issue-Unbilled'
        WHEN a.invc_type = 'Sales Invoice' THEN 'Sales Return'
        WHEN a.invc_type = 'Item Issue-Unbilled' THEN 'Sales Return'
        END \"document type\", 
        (SELECT invc_number FROM scm.scm_sales_invc_hdr WHERE invc_hdr_id = a.src_doc_hdr_id) \"source doc. no\",
        a.comments_desc \"description\", a.invc_number \"doc. number\", 
        (SELECT cust_sup_name FROM scm.scm_cstmr_suplr WHERE cust_sup_id = a.customer_id) \"customer name\",
        to_char(to_timestamp(a.invc_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY') \"doc. date\" ,  
       (SELECT site_name FROM scm.scm_cstmr_suplr_sites WHERE cust_supplier_id = a.customer_site_id) \"customer site\",
       a.payment_terms \"payment terms\", 
       (SELECT user_name from sec.sec_users WHERE user_id = a.created_by) \"created by\",
       a.approval_status \"approval status\", a.next_aproval_action \"next action\", a.customer_id mt, 
       a.customer_site_id mt, a.branch_id, org.get_site_code_desc(a.branch_id)
       FROM scm.scm_sales_invc_hdr a 
        WHERE a.invc_hdr_id = $dochdrID";
//echo $sqlStr;
    $result = executeSQLNoParams($sqlStr);
    return $result;
}

function getSalesDocBscAmnt($dochdrID, $docTyp) {
    $strSql = "select SUM(CASE WHEN (smmry_type='2Tax' or smmry_type='4Extra Charge') THEN -1*y.smmry_amnt ELSE y.smmry_amnt END) amnt " .
            "from scm.scm_doc_amnt_smmrys y " .
            "where y.src_doc_hdr_id=" . $dochdrID .
            " and y.src_doc_type='" . $docTyp . "' and y.smmry_type != '1Initial Amount' " .
            " and y.smmry_type != '6Total Payments Received' and y.smmry_type != " .
            "'7Change/Balance' and smmry_type!='3Discount'";
    $result = executeSQLNoParams($strSql);
    $rs = 0;

    if (loc_db_num_rows($result) > 0) {
        $row = loc_db_fetch_array($result);
        $rs = $row[0];
    }
    return $rs;
}

function getSalesDocCodesAmnt($codeID, $unitAmnt, $qnty) {
    $codeSQL = getGnrlRecNm("scm.scm_tax_codes", "code_id", "sql_formular", $codeID);
    $codeSQL = str_replace("{:unit_price}", $unitAmnt, str_replace("{:qty}", $qnty, $codeSQL));
    if ($codeSQL != "") {
        $result = executeSQLNoParams($codeSQL);
        $rs1 = 0;

        if (loc_db_num_rows($result) > 0) {
            $row = loc_db_fetch_array($result);
            $rs1 = $row[0];
        }
        return $rs1 * $qnty;
    } else {
        return 0.00;
    }
}

function getSalesDocGrndAmnt($dochdrID) {
    $strSql = "select COALESCE(SUM(y.doc_qty*unit_selling_price),0) amnt " .
            "from scm.scm_sales_invc_det y " .
            "where y.invc_hdr_id=" . $dochdrID . " ";
//echo "</br>".$strSql;
    $result = executeSQLNoParams($strSql);
    $rs = 0;

    if (loc_db_num_rows($result) > 0) {
        $row = loc_db_fetch_array($result);
        $rs = (float) $row[0];
    }
    return $rs;
}

function getSalesDocRcvdPymnts($dochdrID, $docType) {
    $strSql = "select COALESCE(SUM(y.amount_paid),0) amnt " .
            "from scm.scm_payments y " .
            "where y.src_doc_id=" . $dochdrID . " and y.src_doc_typ = '" . loc_db_escape_string($docType) . "'";
    $result = executeSQLNoParams($strSql);
    $rs = 0;

    if (loc_db_num_rows($result) > 0) {
        $row = loc_db_fetch_array($result);
        $rs = $row[0];
    }
    return $rs;
}

function get_One_AvlblSrcLnQty($srcLnID) {
    $strSql = "SELECT (a.doc_qty - a.qty_trnsctd_in_dest_doc) avlbl_qty " .
            "FROM scm.scm_sales_invc_det a " .
            "WHERE(a.invc_det_ln_id = " . $srcLnID .
            ") ORDER BY a.invc_det_ln_id";
    $result = executeSQLNoParams($strSql);
    $rs = 0;
    if (loc_db_num_rows($result) > 0) {
        $row = loc_db_fetch_array($result);
        $rs = $row[0];
    }
    return $rs;
}

function get_One_LnTrnsctdQty($dochdrID, $srcLnID) {
    $strSql = "SELECT SUM(a.doc_qty) trnsctd_qty " .
            "FROM scm.scm_sales_invc_det a " .
            "WHERE(a.invc_hdr_id IN(select b.invc_hdr_id " .
            "from scm.scm_sales_invc_hdr b where b.src_doc_hdr_id = " . $dochdrID .
            " and b.src_doc_hdr_id>0) and a.invc_det_ln_id = "
            . $srcLnID . ")";
    $result = executeSQLNoParams($strSql);
    $rs = 0;
    if (loc_db_num_rows($result) > 0) {
        $row = loc_db_fetch_array($result);
        $rs = $row[0];
    }
    return $rs;
}

function get_One_SalesDcLines($dochdrID) {
    global $recDt_SQL;
    $strSql = "SELECT a.invc_det_ln_id, a.itm_id, " .
            "a.doc_qty, a.unit_selling_price, (a.doc_qty * a.unit_selling_price) amnt, " .
            "a.store_id, a.crncy_id, (a.doc_qty - a.qty_trnsctd_in_dest_doc) avlbl_qty, " .
            "a.src_line_id, a.tax_code_id, a.dscnt_code_id, a.chrg_code_id, a.rtrn_reason, a.consgmnt_ids " .
            "FROM scm.scm_sales_invc_det a " .
            "WHERE(a.invc_hdr_id = " . $dochdrID .
            " and a.invc_hdr_id >0) ORDER BY a.invc_det_ln_id";
    $result = executeSQLNoParams($strSql);
    $recDt_SQL = $strSql;
    return $result;
}

function updateSmmryItmOld($smmryID, $smmryTyp, $amnt, $autoCalc, $smmryNm) {
//Global.mnFrm.cmCde.Extra_Adt_Trl_Info = "";
    global $usrID;
    if ($smmryTyp == "3Discount") {
        $amnt = -1 * abs($amnt);
    }
    $dateStr = getDB_Date_time();
    $updtSQL = "UPDATE scm.scm_doc_amnt_smmrys SET " .
            "smmry_amnt = " . $amnt .
            ", last_update_by = " . $usrID . ", " .
            "auto_calc = '" . cnvrtBoolToBitStr($autoCalc) .
            "', last_update_date = '" . $dateStr .
            "', smmry_name='" . loc_db_escape_string($smmryNm) . "' WHERE (smmry_id = " . $smmryID . ")";
    execUpdtInsSQL($updtSQL);
}

function deleteSalesDocHdrNDet($valLnid, $docNum) {
    $trnsCnt1 = 0;
    $docStatus = getGnrlRecNm("scm.scm_sales_invc_hdr", "invc_hdr_id", "approval_status", $valLnid);
    $docType = getGnrlRecNm("scm.scm_sales_invc_hdr", "invc_hdr_id", "invc_type", $valLnid);
    $docTypeLnsDlvrd = (int) getGnrlRecNm("scm.scm_sales_invc_hdr", "invc_hdr_id", "scm.getsaleslnsdlvrd(invc_hdr_id)", $valLnid);
    if ($docTypeLnsDlvrd > 0 || $docStatus == "Approved" || $docStatus == "Initiated" || $docStatus == "Validated" || $docStatus == "Cancelled" || strpos($docStatus, "Reviewed") !== FALSE) {
        $trnsCnt1 = 1;
    }
    if ($trnsCnt1 > 0) {
        $dsply = "No Record Deleted<br/>Cannot DELETE Approved, Initiated, Reviewed, Validated, Cancelled Documents or lines Delivered Already!!";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
    $delSQL = "DELETE FROM scm.scm_gl_interface WHERE src_doc_id = " . $valLnid . " and src_doc_typ='" . loc_db_escape_string($docType) . "'";
    $affctd4 = execUpdtInsSQL($delSQL, "Document Number = " . $docNum);
    $delSQL = "DELETE FROM scm.scm_sales_doc_attchmnts WHERE doc_hdr_id = " . $valLnid;
    $affctd3 = execUpdtInsSQL($delSQL, "Document Number = " . $docNum);
    $delSQL = "DELETE FROM scm.scm_sales_invc_det WHERE invc_hdr_id = " . $valLnid;
    $affctd2 = execUpdtInsSQL($delSQL, "Document Number = " . $docNum);
    $delSQL = "DELETE FROM scm.scm_sales_invc_hdr WHERE invc_hdr_id = " . $valLnid;
    $affctd1 = execUpdtInsSQL($delSQL, "Document Number = " . $docNum);
    if ($affctd1 > 0) {
        $dsply = "";
        $dsply .= "<br/>Successfully Executed the ff-";
        $dsply .= "<br/>Deleted $affctd2 Document Line(s)!";
        $dsply .= "<br/>Deleted $affctd1 Document(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Deleted";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function deleteSalesDocLine($valLnid, $docNum) {
    $trnsCnt1 = 0;
    $docHdrID = getGnrlRecNm("scm.scm_sales_invc_det", "invc_det_ln_id", "invc_hdr_id", $valLnid);
    $docStatus = getGnrlRecNm("scm.scm_sales_invc_hdr", "invc_hdr_id", "approval_status", $docHdrID);
    $docTypeLnsDlvrd = (int) getGnrlRecNm("scm.scm_sales_invc_det", "invc_det_ln_id", "is_itm_delivered", $valLnid);
    if ($docTypeLnsDlvrd > 0 || $docStatus == "Approved" || $docStatus == "Initiated" || $docStatus == "Validated" || $docStatus == "Cancelled" || strpos($docStatus, "Reviewed") !== FALSE) {
        $trnsCnt1 = 1;
    }
    if ($trnsCnt1 > 0) {
        $dsply = "No Record Deleted<br/>Cannot DELETE Approved, Initiated, Reviewed, Validated, Cancelled Documents or lines Delivered Already!!";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
    $delSQL = "DELETE FROM scm.scm_sales_invc_det WHERE invc_det_ln_id = " . $valLnid;
    $affctd2 = execUpdtInsSQL($delSQL, "Document Number = " . $docNum);
    if ($affctd2 > 0) {
        $dsply = "";
        $dsply .= "<br/>Successfully Executed the ff-";
        $dsply .= "<br/>Deleted $affctd2 Document Line(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Deleted";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function deleteSmmryItm($docID, $docType, $smmryTyp) {
//Global.mnFrm.cmCde.Extra_Adt_Trl_Info = "";
    $delSQL = "DELETE FROM scm.scm_doc_amnt_smmrys WHERE src_doc_hdr_id = " .
            $docID . " and src_doc_type = '" . $docType . "' and smmry_type = '" . $smmryTyp . "'";
    execUpdtInsSQL($delSQL);
}

function get_INVItems($searchFor, $searchIn, $offset, $limit_size, $sortBy) {
    global $orgID;
    $whereClause = "";
    $strSql = "";
    $ordrBy = "";
    if ($sortBy == "Value") {
        $ordrBy = "a.orgnl_selling_price DESC";
    } else {
        $ordrBy = "a.item_id DESC";
    }
//Total Quantity
    if ($searchIn == "Name/Description") {
        $whereClause = " and ((a.item_code||' '||a.item_desc) ilike '" . loc_db_escape_string($searchFor) .
                "')";
    } else if ($searchIn == "Category") {
        $whereClause = " and ((select cat_name FROM inv.inv_product_categories WHERE cat_id = category_id) ilike '" . loc_db_escape_string($searchFor) .
                "')";
    } else if ($searchIn == "Type") {
        $whereClause = " and (a.item_type ilike '" . loc_db_escape_string($searchFor) .
                "')";
    }
    $strSql = "select item_id,item_code, item_desc, category_id, tax_code_id, " .
            "dscnt_code_id, extr_chrg_id, inv_asset_acct_id, cogs_acct_id, sales_rev_accnt_id, sales_ret_accnt_id, " .
            " purch_ret_accnt_id, expense_accnt_id, enabled_flag, planning_enabled, min_level, max_level, " .
            " selling_price, item_type, scm.get_ltst_item_bals(item_id, to_char(now(),'YYYY-MM-DD')) total_qty, extra_info, other_desc, image, 
                (SELECT uom_name from inv.unit_of_measure WHERE uom_id = a.base_uom_id), " .
            " generic_name, trade_name, drug_usual_dsge, drug_max_dsge, 
                contraindications, food_interactions, inv.get_invitm_unitval(item_id) orgnl_selling_price,
                (select cat_name FROM inv.inv_product_categories WHERE cat_id = category_id), 
                value_price_crncy_id, gst.get_pssbl_val(value_price_crncy_id), auto_dflt_in_vms_trns 
            from inv.inv_itm_list a " .
            "WHERE ((a.org_id = " . $orgID . ")$whereClause) ORDER BY $ordrBy LIMIT " . $limit_size .
            " OFFSET " . abs($offset * $limit_size);
// echo $strSql;
// and a.item_type not ilike 'VaultItem%'
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_INVItemsTtl($searchFor, $searchIn) {
    global $orgID;
    $whereClause = "";
    $strSql = "";
//Total Quantity
    if ($searchIn == "Name/Description") {
        $whereClause = " and ((a.item_code||' '||a.item_desc) ilike '" . loc_db_escape_string($searchFor) .
                "')";
    } else if ($searchIn == "Category") {
        $whereClause = " and ((select cat_name FROM inv.inv_product_categories WHERE cat_id = category_id) ilike '" . loc_db_escape_string($searchFor) .
                "')";
    } else if ($searchIn == "Type") {
        $whereClause = " and (a.item_type ilike '" . loc_db_escape_string($searchFor) .
                "')";
    }
    $strSql = "select count(1) 
            from inv.inv_itm_list a " .
            "WHERE ((a.org_id = " . $orgID . ")$whereClause)";
// and a.item_type not ilike 'VaultItem%'
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return 0;
}

function get_INVItemsToExport($orgID, $limit_size) {
    $whereClause = "";
    $strSql = "
select item_code,
       item_desc,
       (CASE WHEN enabled_flag = '1' THEN 'YES' ELSE 'NO' END)                                     enabled,
       (select item_type_name FROM inv.inv_itm_type_templates y WHERE y.item_type_id = a.tmplt_id) type_nm,
       item_type,
       inv.get_catgryname(category_id)                                                             category,
       inv.get_uom_name(a.base_uom_id)                                                             uom_nm,
       scm.get_tax_code(tax_code_id)                                                               tax,
       scm.get_tax_code(dscnt_code_id)                                                             dscnt,
       scm.get_tax_code(extr_chrg_id)                                                              charge,
       gst.get_pssbl_val(value_price_crncy_id),
       selling_price,
       scm.get_ltst_item_bals(item_id, to_char(now(), 'YYYY-MM-DD'))                               total_qty,
       inv.get_invitm_unitval(a.item_id)                                                           unit_val,
       (SELECT tbl1.strnms
        FROM (SELECT z.itm_id,
                     STRING_AGG(inv.get_store_name(z.subinv_id), '|' ORDER BY inv.get_store_name(z.subinv_id)) strnms
              FROM inv.inv_stock z
              where z.itm_id = a.item_id
              group by z.itm_id) tbl1),
       (SELECT tbl1.uomnms
        FROM (SELECT z.item_id,
                     STRING_AGG(inv.get_uom_name(z.uom_id) || '~' || z.cnvsn_factor || '~' || z.uom_level || '~' ||
                                z.selling_price, '|' ORDER BY z.uom_level) uomnms
              FROM inv.itm_uoms z
              where z.item_id = a.item_id
              group by z.item_id) tbl1),
       extra_info,
       other_desc,
       generic_name,
       trade_name,
       drug_usual_dsge,
       drug_max_dsge,
       contraindications,
       food_interactions,
       (SELECT tbl1.drugnms
        FROM (SELECT z.first_drug_id,
                     STRING_AGG(inv.get_invitm_code(z.second_drug_id) || '~' || z.intrctn_effect || '~' || z.action, '|'
                                ORDER BY z.drug_intrctn_id) drugnms
              FROM inv.inv_drug_interactions z
              where z.first_drug_id = a.item_id
              group by z.first_drug_id) tbl1),
       inv_asset_acct_id,
       cogs_acct_id,
       sales_rev_accnt_id,
       sales_ret_accnt_id,
       purch_ret_accnt_id,
       expense_accnt_id,
       planning_enabled,
       min_level,
       max_level,
       image,
       auto_dflt_in_vms_trns,
       item_id
from inv.inv_itm_list a " .
            "WHERE ((a.org_id = " . $orgID . ")$whereClause) ORDER BY item_code LIMIT " . $limit_size .
            " OFFSET 0";
// echo $strSql;
    logSessionErrs($strSql);
// and a.item_type not ilike 'VaultItem%'
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_OneINVItems($item_id) {
    $strSql = "select item_id,item_code, item_desc, tmplt_id,
            (select item_type_name FROM inv.inv_itm_type_templates y WHERE y.item_type_id = a.tmplt_id), 
            item_type, category_id, 
            (select cat_name FROM inv.inv_product_categories WHERE cat_id = category_id), 
            base_uom_id, (SELECT uom_name from inv.unit_of_measure WHERE uom_id = a.base_uom_id), 
            tax_code_id, scm.get_tax_code(tax_code_id), 
            dscnt_code_id, scm.get_tax_code(dscnt_code_id), 
            extr_chrg_id, scm.get_tax_code(extr_chrg_id), 
            planning_enabled, auto_dflt_in_vms_trns, enabled_flag, 
            min_level, max_level, 
            value_price_crncy_id, gst.get_pssbl_val(value_price_crncy_id), 
            orgnl_selling_price, selling_price, 
            inv_asset_acct_id, accb.get_accnt_num(a.inv_asset_acct_id) || '.' || accb.get_accnt_name(a.inv_asset_acct_id), 
            cogs_acct_id, accb.get_accnt_num(a.cogs_acct_id) || '.' || accb.get_accnt_name(a.cogs_acct_id), 
            sales_rev_accnt_id, accb.get_accnt_num(a.sales_rev_accnt_id) || '.' || accb.get_accnt_name(a.sales_rev_accnt_id), 
            sales_ret_accnt_id, accb.get_accnt_num(a.sales_ret_accnt_id) || '.' || accb.get_accnt_name(a.sales_ret_accnt_id), 
            purch_ret_accnt_id, accb.get_accnt_num(a.purch_ret_accnt_id) || '.' || accb.get_accnt_name(a.purch_ret_accnt_id), 
            expense_accnt_id, accb.get_accnt_num(a.expense_accnt_id) || '.' || accb.get_accnt_name(a.expense_accnt_id), 
            extra_info, other_desc, 
            generic_name, trade_name, drug_usual_dsge, drug_max_dsge, 
            contraindications, food_interactions, total_qty, image, inv.get_invitm_unitval(a.item_id) unit_val
            FROM inv.inv_itm_list a WHERE a.item_id=" . $item_id;
    return executeSQLNoParams($strSql);
}

function get_OneINVItemStores($item_id) {
    $strSql = "SELECT row_number() over(order by b.subinv_name) as row , b.subinv_name, a.shelves,
            to_char(to_timestamp(a.start_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS'), 
            CASE WHEN a.end_date='' THEN a.end_date ELSE to_char(to_timestamp(a.end_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') END, 
            a.subinv_id, a.shelves_ids, a.stock_id 
            FROM inv.inv_stock a inner join inv.inv_itm_subinventories b ON a.subinv_id = b.subinv_id 
            WHERE a.itm_id = " . $item_id;
    return executeSQLNoParams($strSql);
}

function get_OneINVItemUOMs($item_id) {
    $strSql = "SELECT row_number() over(order by tbl1.uom_level DESC, tbl1.itm_uom_id) as row, tbl1.* FROM 
           (SELECT b.uom_name, a.cnvsn_factor,
          a.uom_level, a.itm_uom_id, a.uom_id, a.selling_price, a.price_less_tax
               FROM inv.itm_uoms a inner join inv.unit_of_measure b ON a.uom_id = b.uom_id 
               WHERE a.item_id = " . $item_id . "
               UNION
            SELECT b.uom_name, 1,
          -1, -1, a.base_uom_id, a.selling_price, a.orgnl_selling_price 
          FROM inv.inv_itm_list a inner join inv.unit_of_measure b ON a.base_uom_id = b.uom_id 
           WHERE a.item_id = " . $item_id . ") tbl1 order by tbl1.uom_level DESC, tbl1.itm_uom_id";
    return executeSQLNoParams($strSql);
}

function get_OneINVItemDrgIntrctns($item_id) {
    $strSql = "SELECT row_number() over(order by b.item_code) as row, 
          b.item_desc || '(' || b.item_code || ')', a.intrctn_effect,
          a.action, a.second_drug_id, a.drug_intrctn_id 
          FROM inv.inv_drug_interactions a inner join inv.inv_itm_list b ON a.second_drug_id = b.item_id 
          WHERE a.first_drug_id =" . $item_id . " order by 1";
    return executeSQLNoParams($strSql);
}

function getHgstUnitCostPrice($itmID) {
    $strSql = "SELECT c.cost_price 
         FROM inv.inv_consgmt_rcpt_det c 
         WHERE (c.itm_id =" . $itmID . ") ORDER BY c.consgmt_id DESC LIMIT 1 OFFSET 0";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return 0;
}

function updateSellingPrice($itemID, $nwPrice, $invTxCodeID) {
    global $usrID;
// $orgnlPrice .
//$dateStr = getDB_Date_time();
    $updtSQL = "UPDATE inv.inv_itm_list SET 
                  selling_price=" . $nwPrice .
            ", orgnl_selling_price = scm.get_sllng_price_lesstax(" . $invTxCodeID . "," . $nwPrice . ")" .
            ",tax_code_id=" . $invTxCodeID .
            ", last_update_by=" . loc_db_escape_string($usrID) .
            ", last_update_date=to_char(now(),'YYYY-MM-DD HH24:MI:SS') WHERE (item_id = " . $itemID . ")";
    return execUpdtInsSQL($updtSQL);
}

function getINVItmID($itemNm) {
    global $orgID;
    $sqlStr = "select item_id from inv.inv_itm_list where lower(trim(item_code)) = '" .
            loc_db_escape_string(strtolower($itemNm)) . "' and org_id = " . $orgID;
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -1;
}

function createINVItm($itmNm, $itmDesc, $ctgryID, $orgid, $isenbled, $sllgPrice, $cogsID, $assetID, $revID, $salesRetID, $prchRetID,
        $expnsID, $txID, $dscntID, $chrgID, $minLvl, $maxLvl, $plnngEnbld, $itmType, $image, $extrInfo, $othrDesc, $baseUomID, $tmpltID,
        $gnrcNm, $tradeNm, $drugUslDsg, $drugMaxDsg, $cntrIndctns, $foodIntrctns, $orgnSllngPrc, $valCrncyID, $autoDfltINV) {
    global $usrID;
    $dateStr = getDB_Date_time();
    $insSQL = "INSERT INTO inv.inv_itm_list(
            item_code, item_desc, category_id, org_id, enabled_flag, 
            selling_price, cogs_acct_id, inv_asset_acct_id, created_by, creation_date, 
            last_update_by, last_update_date, total_qty, reservations, available_balance, 
            sales_rev_accnt_id, sales_ret_accnt_id, purch_ret_accnt_id, expense_accnt_id, 
            tax_code_id, dscnt_code_id, extr_chrg_id, min_level, max_level, 
            planning_enabled, item_type, image, extra_info, other_desc, base_uom_id, 
            tmplt_id, generic_name, trade_name, drug_usual_dsge, drug_max_dsge, 
            contraindications, food_interactions, orgnl_selling_price, value_price_crncy_id, 
            auto_dflt_in_vms_trns) " .
            "VALUES ('" . loc_db_escape_string($itmNm) .
            "', '" . loc_db_escape_string($itmDesc) .
            "'," . loc_db_escape_string($ctgryID) .
            "," . loc_db_escape_string($orgid) .
            ", '" . loc_db_escape_string($isenbled) .
            "', " . loc_db_escape_string($sllgPrice) .
            ", " . loc_db_escape_string($cogsID) .
            ", " . loc_db_escape_string($assetID) .
            ", " . loc_db_escape_string($usrID) .
            ", '" . loc_db_escape_string($dateStr) .
            "', " . loc_db_escape_string($usrID) .
            ", '" . loc_db_escape_string($dateStr) .
            "',0,0,0, " . loc_db_escape_string($revID) .
            ", " . loc_db_escape_string($salesRetID) .
            ", " . loc_db_escape_string($prchRetID) .
            ", " . loc_db_escape_string($expnsID) .
            ", " . loc_db_escape_string($txID) .
            ", " . loc_db_escape_string($dscntID) .
            ", " . loc_db_escape_string($chrgID) .
            ", " . loc_db_escape_string($minLvl) .
            ", " . loc_db_escape_string($maxLvl) .
            ", '" . loc_db_escape_string($plnngEnbld) .
            "', '" . loc_db_escape_string($itmType) .
            "', '" . loc_db_escape_string($image) .
            "', '" . loc_db_escape_string($extrInfo) .
            "', '" . loc_db_escape_string($othrDesc) .
            "', " . loc_db_escape_string($baseUomID) .
            ", " . loc_db_escape_string($tmpltID) .
            ", '" . loc_db_escape_string($gnrcNm) .
            "', '" . loc_db_escape_string($tradeNm) .
            "', '" . loc_db_escape_string($drugUslDsg) .
            "', '" . loc_db_escape_string($drugMaxDsg) .
            "', '" . loc_db_escape_string($cntrIndctns) .
            "', '" . loc_db_escape_string($foodIntrctns) .
            "', scm.get_sllng_price_lesstax(" . $txID . "," . $sllgPrice . "), " . loc_db_escape_string($valCrncyID) .
            ", '" . loc_db_escape_string($autoDfltINV) .
            "')";
    //logSsnErrs($insSQL);
    return execUpdtInsSQL($insSQL);
}

function updateINVItem($itemid, $itmNm, $itmDesc, $ctgryID, $orgid, $isenbled, $sllgPrice, $cogsID, $assetID, $revID, $salesRetID,
        $prchRetID, $expnsID, $txID, $dscntID, $chrgID, $minLvl, $maxLvl, $plnngEnbld, $itmType, $image, $extrInfo, $othrDesc, $baseUomID,
        $tmpltID, $gnrcNm, $tradeNm, $drugUslDsg, $drugMaxDsg, $cntrIndctns, $foodIntrctns, $orgnSllngPrc, $valCrncyID, $autoDfltINV) {
    global $usrID;
    $dateStr = getDB_Date_time();
    $insSQL = "UPDATE inv.inv_itm_list
   SET item_code='" . loc_db_escape_string($itmNm) .
            "', item_desc='" . loc_db_escape_string($itmDesc) .
            "', category_id=" . loc_db_escape_string($ctgryID) .
            ", org_id=" . loc_db_escape_string($orgid) .
            ", enabled_flag='" . loc_db_escape_string($isenbled) .
            "', selling_price=" . loc_db_escape_string($sllgPrice) .
            ", cogs_acct_id=" . loc_db_escape_string($cogsID) .
            ", inv_asset_acct_id=" . loc_db_escape_string($assetID) .
            ", last_update_by=" . loc_db_escape_string($usrID) .
            ", last_update_date='" . loc_db_escape_string($dateStr) .
            "', sales_rev_accnt_id=" . loc_db_escape_string($revID) .
            ", sales_ret_accnt_id=" . loc_db_escape_string($salesRetID) .
            ", purch_ret_accnt_id=" . loc_db_escape_string($prchRetID) .
            ", expense_accnt_id=" . loc_db_escape_string($expnsID) .
            ", tax_code_id=" . loc_db_escape_string($txID) .
            ", dscnt_code_id=" . loc_db_escape_string($dscntID) .
            ", extr_chrg_id=" . loc_db_escape_string($chrgID) .
            ", min_level=" . loc_db_escape_string($minLvl) .
            ", max_level=" . loc_db_escape_string($maxLvl) .
            ", planning_enabled='" . loc_db_escape_string($plnngEnbld) .
            "', item_type='" . loc_db_escape_string($itmType) .
            "', image='" . loc_db_escape_string($image) .
            "', extra_info='" . loc_db_escape_string($extrInfo) .
            "', other_desc='" . loc_db_escape_string($othrDesc) .
            "', base_uom_id=" . loc_db_escape_string($baseUomID) .
            ", tmplt_id=" . loc_db_escape_string($tmpltID) .
            ", generic_name='" . loc_db_escape_string($gnrcNm) .
            "', trade_name='" . loc_db_escape_string($tradeNm) .
            "', drug_usual_dsge='" . loc_db_escape_string($drugUslDsg) .
            "', drug_max_dsge='" . loc_db_escape_string($drugMaxDsg) .
            "', contraindications='" . loc_db_escape_string($cntrIndctns) .
            "', food_interactions='" . loc_db_escape_string($foodIntrctns) .
            "', orgnl_selling_price=scm.get_sllng_price_lesstax(" . $txID . "," . $sllgPrice . ")" .
            ", value_price_crncy_id=" . loc_db_escape_string($valCrncyID) .
            ", auto_dflt_in_vms_trns='" . loc_db_escape_string($autoDfltINV) .
            "' WHERE item_id = " . $itemid;
// . loc_db_escape_string($orgnSllngPrc)
    return execUpdtInsSQL($insSQL);
}

function getPriceLessTx($txID, $sllgPrice) {
    $strSQL = "select scm.get_sllng_price_lesstax(" . $txID . "," . $sllgPrice . ")";
    $result = executeSQLNoParams($strSQL);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return 0;
}

function getINVItemStockID($itmID, $storeID) {
    $sqlStr = "select stock_id from inv.inv_stock where itm_id = " . loc_db_escape_string($itmID) .
            " and subinv_id = " . loc_db_escape_string($storeID);
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -1;
}

function createINVItemStore($itmID, $storeID, $shelves, $orgID, $strtDte, $endDte, $shelveIDs) {
    global $usrID;
    $dateStr = getDB_Date_time();
    $insSQL = "INSERT INTO inv.inv_stock(
            itm_id, subinv_id, created_by, creation_date, last_update_by, 
            last_update_date, shelves, start_date, end_date, org_id, shelves_ids) " .
            "VALUES (" . loc_db_escape_string($itmID) .
            ", " . loc_db_escape_string($storeID) .
            ", " . loc_db_escape_string($usrID) .
            ", '" . loc_db_escape_string($dateStr) .
            "', " . loc_db_escape_string($usrID) .
            ", '" . loc_db_escape_string($dateStr) .
            "', '" . loc_db_escape_string($shelves) .
            "', '" . loc_db_escape_string($strtDte) .
            "', '" . loc_db_escape_string($endDte) .
            "', " . loc_db_escape_string($orgID) .
            ", '" . loc_db_escape_string($shelveIDs) .
            "')";
    return execUpdtInsSQL($insSQL);
}

function updateINVItemStore($stockID, $itmID, $storeID, $shelves, $orgID, $strtDte, $endDte, $shelveIDs) {
    global $usrID;
    $dateStr = getDB_Date_time();
    $insSQL = "UPDATE inv.inv_stock
   SET itm_id=" . loc_db_escape_string($itmID) .
            ", subinv_id=" . loc_db_escape_string($storeID) .
            ", shelves='" . loc_db_escape_string($shelves) .
            "', start_date='" . loc_db_escape_string($strtDte) .
            "', end_date='" . loc_db_escape_string($endDte) .
            "', shelves_ids='" . loc_db_escape_string($shelveIDs) .
            "', last_update_by=" . loc_db_escape_string($usrID) .
            ", last_update_date='" . loc_db_escape_string($dateStr) .
            "', org_id=" . loc_db_escape_string($orgID) .
            "  WHERE stock_id = " . $stockID;
    return execUpdtInsSQL($insSQL);
}

function getINVItemUomID($itmID, $uomID) {
    $sqlStr = "select itm_uom_id from inv.itm_uoms where item_id = " . loc_db_escape_string($itmID) . " and uom_id = " . loc_db_escape_string($uomID);
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -1;
}

function createINVItemUom($itmID, $uomID, $cnvsnFctr, $sortOrdr, $prcLsTx, $sllngPrice) {
    global $usrID;
    $dateStr = getDB_Date_time();
    $insSQL = "INSERT INTO inv.itm_uoms(
            item_id, uom_id, is_base_uom, cnvsn_factor, uom_level, 
            created_by, creation_date, last_update_by, last_update_date, 
            price_less_tax, selling_price) " .
            "VALUES (" . loc_db_escape_string($itmID) .
            ", " . loc_db_escape_string($uomID) .
            ",'0', " . loc_db_escape_string($cnvsnFctr) .
            ", " . loc_db_escape_string($sortOrdr) .
            ", " . loc_db_escape_string($usrID) .
            ", '" . loc_db_escape_string($dateStr) .
            "', " . loc_db_escape_string($usrID) .
            ", '" . loc_db_escape_string($dateStr) .
            "', " . loc_db_escape_string($prcLsTx) .
            ", " . loc_db_escape_string($sllngPrice) .
            ")";
//    echo $insSQL;
    return execUpdtInsSQL($insSQL);
}

function updateINVItemUom($itmUoMID, $itmID, $uomID, $cnvsnFctr, $sortOrdr, $prcLsTx, $sllngPrice) {
    global $usrID;
    $dateStr = getDB_Date_time();
    $insSQL = "UPDATE inv.itm_uoms
   SET item_id=" . loc_db_escape_string($itmID) .
            ", uom_id=" . loc_db_escape_string($uomID) .
            ", cnvsn_factor='" . loc_db_escape_string($cnvsnFctr) .
            "', uom_level=" . loc_db_escape_string($sortOrdr) .
            ", price_less_tax=" . loc_db_escape_string($prcLsTx) .
            ", last_update_by=" . loc_db_escape_string($usrID) .
            ", last_update_date='" . loc_db_escape_string($dateStr) .
            "', selling_price=" . loc_db_escape_string($sllngPrice) .
            "  WHERE itm_uom_id = " . $itmUoMID;
    return execUpdtInsSQL($insSQL);
}

function getINVItemIntrctnID($itmID, $secondItmID) {
    $sqlStr = "select drug_intrctn_id from inv.inv_drug_interactions where first_drug_id = " . loc_db_escape_string($itmID) . " and second_drug_id = " . loc_db_escape_string($secondItmID);
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -1;
}

function createINVItemIntrctn($itmID, $secondItmID, $intrctnEffct, $actionItm) {
    global $usrID;
    $dateStr = getDB_Date_time();
    $insSQL = "INSERT INTO inv.inv_drug_interactions(
            first_drug_id, second_drug_id, intrctn_effect, action, created_by, 
            creation_date, last_update_by, last_update_date) " .
            "VALUES (" . loc_db_escape_string($itmID) .
            ", " . loc_db_escape_string($secondItmID) .
            ", '" . loc_db_escape_string($intrctnEffct) .
            "', '" . loc_db_escape_string($actionItm) .
            "', " . loc_db_escape_string($usrID) .
            ", '" . loc_db_escape_string($dateStr) .
            "', " . loc_db_escape_string($usrID) .
            ", '" . loc_db_escape_string($dateStr) .
            "')";
    return execUpdtInsSQL($insSQL);
}

function updateINVItemIntrctn($intrctnID, $itmID, $secondItmID, $intrctnEffct, $actionItm) {
    global $usrID;
    $dateStr = getDB_Date_time();
    $insSQL = "UPDATE inv.inv_drug_interactions
   SET first_drug_id=" . loc_db_escape_string($itmID) .
            ", second_drug_id=" . loc_db_escape_string($secondItmID) .
            ", intrctn_effect='" . loc_db_escape_string($intrctnEffct) .
            "', action='" . loc_db_escape_string($actionItm) .
            "', last_update_by=" . loc_db_escape_string($usrID) .
            ", last_update_date='" . loc_db_escape_string($dateStr) .
            "' WHERE drug_intrctn_id = " . $intrctnID;
    return execUpdtInsSQL($insSQL);
}

function uploadDaImageItem($itemid, &$nwImgLoc) {
    global $tmpDest;
    global $ftp_base_db_fldr;
    global $usrID;
    global $fldrPrfx;
    global $smplTokenWord1;

    $msg = "";
    $allowedExts = array("gif", "jpeg", "jpg", "png");
    if (isset($_FILES["daItemPicture"])) {
//$files = multiple($_FILES);
        $flnm = $_FILES["daItemPicture"]["name"];
        $msg .= $flnm;
        $temp = explode(".", $flnm);
        $extension = end($temp);
        if ($_FILES["daItemPicture"]["error"] > 0) {
            $msg .= "Return Code: " . $_FILES["daItemPicture"]["error"] . "<br>";
        } else {
            $msg .= "Upload: " . $_FILES["daItemPicture"]["name"] . "<br>";
            $msg .= "Type: " . $_FILES["daItemPicture"]["type"] . "<br>";
            $msg .= "Size: " . ($_FILES["daItemPicture"]["size"]) . " bytes<br>";
            $msg .= "Temp file: " . $_FILES["daItemPicture"]["tmp_name"] . "<br>";
            if ((($_FILES["daItemPicture"]["type"] == "image/gif") || ($_FILES["daItemPicture"]["type"] == "image/jpeg") || ($_FILES["daItemPicture"]["type"] == "image/jpg") || ($_FILES["daItemPicture"]["type"] == "image/pjpeg") || ($_FILES["daItemPicture"]["type"] == "image/x-png") || ($_FILES["daItemPicture"]["type"] == "image/png") || in_array($extension, $allowedExts)) && ($_FILES["daItemPicture"]["size"] < 2000000)) {
                $nwFileName = encrypt1($itemid . "." . $extension, $smplTokenWord1) . "." . $extension;
                $img_src = $fldrPrfx . $tmpDest . "$nwFileName";
                move_uploaded_file($_FILES["daItemPicture"]["tmp_name"], $img_src);
                $ftp_src = $ftp_base_db_fldr . "/Inv/$itemid" . "." . $extension;
                if (file_exists($img_src)) {
                    copy("$img_src", "$ftp_src");

                    $dateStr = getDB_Date_time();
                    $updtSQL = "UPDATE inv.inv_itm_list " .
                            "SET last_update_by=" . $usrID . ", " .
                            "last_update_date='" . $dateStr .
                            "', image = '" . $itemid . "." . $extension . "' WHERE item_id=" . $itemid;
                    execUpdtInsSQL($updtSQL);
                }
                $msg .= "Image Stored";
                $nwImgLoc = "$itemid" . "." . $extension;
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

function deleteINVItm($pkeyID, $extrInfo = "") {
    $selSQL = "Select count(1) from scm.scm_sales_invc_det where itm_id = " . $pkeyID;
    $result = executeSQLNoParams($selSQL);
    $trnsCnt = 0;
    while ($row = loc_db_fetch_array($result)) {
        $trnsCnt = (float) $row[0];
    }
    if ($trnsCnt > 0) {
        $dsply = "No Record Deleted<br/>Cannot Delete Items used in Transactions!";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
    $selSQL1 = "Select count(1) from scm.scm_prchs_docs_det where itm_id = " . $pkeyID;
    $result1 = executeSQLNoParams($selSQL1);
    $trnsCnt1 = 0;
    while ($row = loc_db_fetch_array($result1)) {
        $trnsCnt1 = (float) $row[0];
    }
    if ($trnsCnt1 > 0) {
        $dsply = "No Record Deleted<br/>Cannot Delete Items used in Transactions!";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
    $selSQL11 = "Select count(1) from inv.inv_consgmt_rcpt_det where itm_id = " . $pkeyID;
    $result11 = executeSQLNoParams($selSQL11);
    $trnsCnt11 = 0;
    while ($row = loc_db_fetch_array($result11)) {
        $trnsCnt11 = (float) $row[0];
    }
    if ($trnsCnt11 > 0) {
        $dsply = "No Record Deleted<br/>Cannot Delete Items used in Transactions!";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
    $selSQL3 = "Select count(1) from inv.inv_stock_daily_bals a, inv.inv_stock b  "
            . "WHERE a.stock_id=b.stock_id and b.itm_id= " . $pkeyID . "";
    $result3 = executeSQLNoParams($selSQL3);
    $trnsCnt3 = 0;
    while ($row = loc_db_fetch_array($result3)) {
        $trnsCnt3 = (float) $row[0];
    }
    if ($trnsCnt3 > 0) {
        $dsply = "No Record Deleted<br/>Cannot Delete Items used in Transactions!";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
    $selSQL33 = "Select count(1) from mcf.mcf_currency_denominations "
            . "WHERE vault_item_id = " . $pkeyID . "";
    $result33 = executeSQLNoParams($selSQL33);
    $trnsCnt33 = 0;
    while ($row = loc_db_fetch_array($result33)) {
        $trnsCnt33 = (float) $row[0];
    }
    if ($trnsCnt33 > 0) {
        $dsply = "No Record Deleted<br/>Cannot Delete Items used in Creating Currency Denominations in Banking!";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
    $selSQL31 = "Select count(1) from vms.vms_transaction_lines "
            . "WHERE itm_id= " . $pkeyID . "";
    $result31 = executeSQLNoParams($selSQL31);
    $trnsCnt31 = 0;
    while ($row = loc_db_fetch_array($result31)) {
        $trnsCnt31 = (float) $row[0];
    }
    if ($trnsCnt31 > 0) {
        $dsply = "No Record Deleted<br/>Cannot Delete Items used in Transactions!";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
    $selSQL32 = "Select count(1) from vms.vms_transaction_pymnt "
            . "WHERE itm_id= " . $pkeyID . "";
    $result32 = executeSQLNoParams($selSQL32);
    $trnsCnt32 = 0;
    while ($row = loc_db_fetch_array($result32)) {
        $trnsCnt32 = (float) $row[0];
    }
    if ($trnsCnt32 > 0) {
        $dsply = "No Record Deleted<br/>Cannot Delete Items used in Transactions!";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
    $affctd1 = 0;
    $affctd2 = 0;
    $affctd3 = 0;
    $affctd4 = 0;
    if (($trnsCnt + $trnsCnt1 + $trnsCnt11 + $trnsCnt3 + $trnsCnt31 + $trnsCnt32 + $trnsCnt33) <= 0) {
        $insSQL = "DELETE FROM inv.inv_stock WHERE itm_id = " . $pkeyID;
        $affctd1 = execUpdtInsSQL($insSQL, "Item Name:" . $extrInfo);
        $insSQL = "DELETE FROM inv.itm_uoms WHERE item_id = " . $pkeyID;
        $affctd2 = execUpdtInsSQL($insSQL, "Item Name:" . $extrInfo);
        $insSQL = "DELETE FROM inv.inv_drug_interactions WHERE first_drug_id = " . $pkeyID . " or second_drug_id = " . $pkeyID;
        $affctd3 = execUpdtInsSQL($insSQL, "Item Name:" . $extrInfo);
        $insSQL = "DELETE FROM inv.inv_itm_list WHERE item_id = " . $pkeyID;
        $affctd4 = execUpdtInsSQL($insSQL, "Item Name:" . $extrInfo);
    }
    if ($affctd4 > 0) {
        $dsply = "Successfully Deleted the ff Records-";
        $dsply .= "<br/>$affctd4 Item(s)!";
        $dsply .= "<br/>$affctd1 Item Store(s)!";
        $dsply .= "<br/>$affctd2 Item Uom(s)!";
        $dsply .= "<br/>$affctd3 Interaction(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function deleteINVItmStore($pkeyID, $extrInfo = "") {
    $selSQL11 = "Select count(1) from inv.inv_consgmt_rcpt_det where stock_id = " . $pkeyID;
    $result11 = executeSQLNoParams($selSQL11);
    $trnsCnt11 = 0;
    while ($row = loc_db_fetch_array($result11)) {
        $trnsCnt11 = (float) $row[0];
    }
    if ($trnsCnt11 > 0) {
        $dsply = "No Record Deleted<br/>Cannot Delete Stock used in Transactions!";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
    $affctd1 = 0;
    if (($trnsCnt11) <= 0) {
        $insSQL = "DELETE FROM inv.inv_stock WHERE stock_id = " . $pkeyID;
        $affctd1 = execUpdtInsSQL($insSQL, "Item Name:" . $extrInfo);
    }
    if ($affctd1 > 0) {
        $dsply = "Successfully Deleted the ff Records-";
        $dsply .= "<br/>$affctd1 Item Store(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function deleteINVItmUom($pkeyID, $extrInfo = "") {
    $trnsCnt = 0;
    $affctd2 = 0;
    if (($trnsCnt) <= 0) {
        $insSQL = "DELETE FROM inv.itm_uoms WHERE itm_uom_id = " . $pkeyID;
        $affctd2 = execUpdtInsSQL($insSQL, "Item Name:" . $extrInfo);
    }
    if ($affctd2 > 0) {
        $dsply = "Successfully Deleted the ff Records-";
        $dsply .= "<br/>$affctd2 Item Uom(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function deleteINVItmIntrctn($pkeyID, $extrInfo = "") {
    $trnsCnt = 0;
    $affctd3 = 0;
    if (($trnsCnt) <= 0) {
        $insSQL = "DELETE FROM inv.inv_drug_interactions WHERE drug_intrctn_id = " . $pkeyID;
        $affctd3 = execUpdtInsSQL($insSQL, "Item Name:" . $extrInfo);
    }
    if ($affctd3 > 0) {
        $dsply = "Successfully Deleted the ff Records-";
        $dsply .= "<br/>$affctd3 Interaction(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function get_StoresWhs($pkID, $prsnID, $searchWord, $searchIn, $offset, $limit_size) {
    global $orgID;
    global $fnccurid;
    global $gnrlTrnsDteYMD;
    $whereCls = "";
    $extrWhere = "";
    if ($pkID > 0) {
        $extrWhere .= " and (org.does_prsn_hv_crtria_id(" . $prsnID . ", a.allwd_group_value::bigint, a.allwd_group_type)>0) and (a.lnkd_site_id = $pkID)";
    }
    if ($searchIn == "Store Name") {
        $whereCls = " and (a.subinv_name ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Store Description") {
        $whereCls = " and (a.subinv_desc ilike '" . loc_db_escape_string($searchWord) . "')";
    }
    $strSql = "SELECT a.subinv_id mt,
    a.subinv_name \"code/name\", 
        a.subinv_desc \"description/comments\", 
        CASE WHEN a.enabled_flag='1' THEN 'Yes' ELSE 'No' END \"is_enabled?\",
        a.subinv_manager,
        (prs.get_prsn_name(a.subinv_manager) || ' (' || prs.get_prsn_loc_id(a.subinv_manager) || ')') prsn_nm, 
        a.inv_asset_acct_id,  
       (SELECT string_agg(accb.get_accnt_num(y.inv_asset_acct_id) || '.' || accb.get_accnt_name(y.inv_asset_acct_id),',') FROM (SELECT DISTINCT z.inv_asset_acct_id FROM inv.inv_shelf z WHERE z.store_id=a.subinv_id) y) asset_acc,
        gst.get_pssbl_val(accb.get_accnt_crncy_id(a.inv_asset_acct_id)) crncy_nm,
        accb.get_accnt_crncy_id(a.inv_asset_acct_id) crncy_id,
      (SELECT SUM(accb.get_ltst_accnt_bals(y.inv_asset_acct_id, '" . $gnrlTrnsDteYMD . "')) FROM (SELECT DISTINCT z.inv_asset_acct_id FROM inv.inv_shelf z WHERE z.store_id=a.subinv_id) y) acct_bals,
        vms.get_ltst_stock_bals1(a.subinv_id,-1,-1,'','" . $gnrlTrnsDteYMD . "') ttlStoreWhsAmnt,
        c.location_id, REPLACE(c.location_code_name || '.' || c.site_desc, '.' || c.location_code_name,'') location_code_name 
        FROM inv.inv_itm_subinventories a
        LEFT OUTER JOIN org.org_sites_locations c ON (c.location_id = a.lnkd_site_id)
        WHERE (a.org_id=" . $orgID . "" . $extrWhere . $whereCls . ") 
        ORDER BY a.subinv_name LIMIT " . $limit_size . " OFFSET " . abs($offset * $limit_size);
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_StoresWhsTtl($pkID, $prsnID, $searchWord, $searchIn) {
    global $orgID;
    $whereCls = "";
    $extrWhere = "";
    if ($pkID > 0) {
        $extrWhere .= " and (org.does_prsn_hv_crtria_id(" . $prsnID . ", a.allwd_group_value::bigint,a.allwd_group_type)>0) and (a.lnkd_site_id = $pkID)";
    }
    if ($searchIn == "Store Name") {
        $whereCls = " and (a.subinv_name ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Store Description") {
        $whereCls = " and (a.subinv_desc ilike '" . loc_db_escape_string($searchWord) . "')";
    }
    $strSql = "SELECT count(1)  
        FROM inv.inv_itm_subinventories a
         WHERE (a.org_id=" . $orgID . "" . $extrWhere . $whereCls . ")";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return 0;
}

function get_OneStoreDet($subinvID) {
    $strSql = "select a.subinv_id, a.subinv_name, a.subinv_desc, a.address, a.lnkd_site_id, 
                   org.get_site_code_desc(lnkd_site_id) site_name, 
                    a.inv_asset_acct_id, accb.get_accnt_num(a.inv_asset_acct_id) || '.' || accb.get_accnt_name(a.inv_asset_acct_id) asset_acc,
                    REPLACE(org.get_criteria_name(a.allwd_group_value::bigint,a.allwd_group_type),'Everyone','') group_name, 
                    a.allwd_group_type, a.allwd_group_value, 
                    a.subinv_manager, (prs.get_prsn_name(a.subinv_manager) || ' (' || prs.get_prsn_loc_id(a.subinv_manager) || ')') mngr_nm, 
                    CASE WHEN a.allow_sales='1' THEN 'Yes' ELSE 'No' END allow_sales, 
                    CASE WHEN a.enabled_flag='1' THEN 'Yes' ELSE 'No' END enabled_flag
        from inv.inv_itm_subinventories a
         WHERE (a.subinv_id=" . $subinvID . ")";
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_OneStoreCages($subinvID) {
    $strSql = "SELECT row_number() over(order by shelf_id) as row, shelf_id, line_id, 
                   lnkd_cstmr_id, scm.get_cstmr_splr_name(lnkd_cstmr_id), allwd_group_type, allwd_group_value, 
                    enabled_flag,  inv_asset_acct_id, cage_shelve_mngr_id, prs.get_prsn_name(cage_shelve_mngr_id), 
                dflt_item_state, managers_wthdrwl_limit, managers_deposit_limit, dflt_item_type, 
                CASE WHEN shelve_name='' THEN gst.get_pssbl_val(shelf_id) ELSE shelve_name END, 
                CASE WHEN shelve_desc='' THEN gst.get_pssbl_val_desc(shelf_id) ELSE shelve_desc END                    
                    FROM inv.inv_shelf
                 WHERE (store_id=" . $subinvID . ")";
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_OneStoreUsers($subinvID) {
    $strSql = "SELECT row_number() over(order by user_id) as row , user_id, 
                    sec.get_usr_name(user_id) || '(' || prs.get_prsn_name(sec.get_usr_prsn_id(user_id)) || ')',
                    to_char(to_timestamp(start_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS'), 
                    CASE WHEN end_date='' THEN end_date ELSE to_char(to_timestamp(end_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') END,
                    line_id
                    FROM inv.inv_user_subinventories 
               WHERE (subinv_id=" . $subinvID . ")";
    $result = executeSQLNoParams($strSql);
    return $result;
}

function getStoreID($storeNm) {
    $sqlStr = "select subinv_id from inv.inv_itm_subinventories where lower(subinv_name) = '" .
            loc_db_escape_string(strtolower($storeNm)) . "'";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -1;
}

function getStoreNm($storeID) {
    $sqlStr = "select subinv_name from inv.inv_itm_subinventories where subinv_id= " .
            loc_db_escape_string($storeID) . "";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return "";
}

function createStore($storeNm, $storeDesc, $storeAddrs, $isSalesAllwd, $mngrPrsnID, $orgid, $isenbled, $invAsstAcntID, $lnkdSiteID,
        $allwdGrpType, $allwdGrpVal) {
    global $usrID;
    $dateStr = getDB_Date_time();
    $insSQL = "INSERT INTO inv.inv_itm_subinventories(
            subinv_name, subinv_desc, address, creation_date, 
            created_by, last_update_by, last_update_date, allow_sales, subinv_manager, 
            org_id, enabled_flag, inv_asset_acct_id, lnkd_site_id, allwd_group_type, 
            allwd_group_value) " .
            "VALUES ('" . loc_db_escape_string($storeNm) .
            "', '" . loc_db_escape_string($storeDesc) .
            "', '" . loc_db_escape_string($storeAddrs) .
            "', '" . loc_db_escape_string($dateStr) .
            "', " . loc_db_escape_string($usrID) .
            ", " . loc_db_escape_string($usrID) .
            ", '" . loc_db_escape_string($dateStr) .
            "', '" . loc_db_escape_string($isSalesAllwd) .
            "', " . loc_db_escape_string($mngrPrsnID) .
            ", " . loc_db_escape_string($orgid) .
            ", '" . loc_db_escape_string($isenbled) .
            "', " . loc_db_escape_string($invAsstAcntID) .
            ", " . loc_db_escape_string($lnkdSiteID) .
            ", '" . loc_db_escape_string($allwdGrpType) .
            "', '" . loc_db_escape_string($allwdGrpVal) .
            "')";
    return execUpdtInsSQL($insSQL);
}

function updateStore($storeid, $storeNm, $storeDesc, $storeAddrs, $isSalesAllwd, $mngrPrsnID, $orgid, $isenbled, $invAsstAcntID,
        $lnkdSiteID, $allwdGrpType, $allwdGrpVal) {
    global $usrID;
    $dateStr = getDB_Date_time();
    $insSQL = "UPDATE inv.inv_itm_subinventories SET 
                subinv_name='" . loc_db_escape_string($storeNm) .
            "', subinv_desc='" . loc_db_escape_string($storeDesc) .
            "', address='" . loc_db_escape_string($storeAddrs) .
            "', last_update_by=" . loc_db_escape_string($usrID) .
            ", last_update_date='" . loc_db_escape_string($dateStr) .
            "', allow_sales='" . loc_db_escape_string($isSalesAllwd) .
            "', subinv_manager=" . loc_db_escape_string($mngrPrsnID) .
            ", org_id=" . loc_db_escape_string($orgid) .
            ", enabled_flag='" . loc_db_escape_string($isenbled) .
            "', inv_asset_acct_id=" . loc_db_escape_string($invAsstAcntID) .
            ", lnkd_site_id=" . loc_db_escape_string($lnkdSiteID) .
            ", allwd_group_type='" . loc_db_escape_string($allwdGrpType) .
            "', allwd_group_value='" . loc_db_escape_string($allwdGrpVal) .
            "' WHERE subinv_id = " . $storeid;
    return execUpdtInsSQL($insSQL);
}

function deleteStore($pkeyID, $extrInfo = "") {
    $selSQL = "Select count(1) from mcf.mcf_account_trns_cash_analysis WHERE vault_id = " . $pkeyID;
    $result = executeSQLNoParams($selSQL);
    $trnsCnt = 0;
    while ($row = loc_db_fetch_array($result)) {
        $trnsCnt = (float) $row[0];
    }
    if ($trnsCnt > 0) {
        $dsply = "No Record Deleted<br/>Cannot Delete Stores used in Transactions!";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
    $selSQL1 = "Select count(1) from vms.vms_transaction_lines WHERE src_store_vault_id = " . $pkeyID . " or dest_store_vault_id = " . $pkeyID;
    $result1 = executeSQLNoParams($selSQL1);
    $trnsCnt1 = 0;
    while ($row = loc_db_fetch_array($result1)) {
        $trnsCnt1 = (float) $row[0];
    }
    if ($trnsCnt1 > 0) {
        $dsply = "No Record Deleted<br/>Cannot Delete Stores used in Transactions!";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
    $selSQL2 = "Select count(1) from vms.vms_transaction_pymnt WHERE src_store_vault_id = " . $pkeyID . " or dest_store_vault_id = " . $pkeyID;
    $result2 = executeSQLNoParams($selSQL2);
    $trnsCnt2 = 0;
    while ($row = loc_db_fetch_array($result2)) {
        $trnsCnt2 = (float) $row[0];
    }
    if ($trnsCnt2 > 0) {
        $dsply = "No Record Deleted<br/>Cannot Delete Stores used in Transactions!";
        return "<p style=\"text-align:left;color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
    $selSQL3 = "Select count(1) from vms.vms_stock_daily_bals WHERE store_vault_id = " . $pkeyID;
    $result3 = executeSQLNoParams($selSQL3);
    $trnsCnt3 = 0;
    while ($row = loc_db_fetch_array($result3)) {
        $trnsCnt3 = (float) $row[0];
    }
    $selSQL3 = "Select count(1) from scm.scm_sales_invc_det WHERE store_id = " . $pkeyID;
    $result31 = executeSQLNoParams($selSQL3);
    while ($row = loc_db_fetch_array($result31)) {
        $trnsCnt3 += (float) $row[0];
    }
    $selSQL3 = "Select count(1) from inv.inv_consgmt_rcpt_det WHERE subinv_id = " . $pkeyID;
    $result32 = executeSQLNoParams($selSQL3);
    while ($row = loc_db_fetch_array($result32)) {
        $trnsCnt3 += (float) $row[0];
    }
    $selSQL3 = "Select count(1) from inv.inv_svd_consgmt_rcpt_det WHERE s_subinv_id = " . $pkeyID;
    $result33 = executeSQLNoParams($selSQL3);
    while ($row = loc_db_fetch_array($result33)) {
        $trnsCnt3 += (float) $row[0];
    }
    if ($trnsCnt3 > 0) {
        $dsply = "No Record Deleted<br/>Cannot Delete Stores used in Transactions!";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
    $affctd1 = 0;
    $affctd2 = 0;
    $affctd3 = 0;
    if (($trnsCnt + $trnsCnt1 + $trnsCnt2 + $trnsCnt3) <= 0) {
        $insSQL = "DELETE FROM inv.inv_user_subinventories WHERE subinv_id = " . $pkeyID;
        $affctd2 = execUpdtInsSQL($insSQL, "Store Name:" . $extrInfo);
        $insSQL = "DELETE FROM inv.inv_shelf WHERE store_id = " . $pkeyID;
        $affctd3 = execUpdtInsSQL($insSQL, "Store Name:" . $extrInfo);
        $insSQL = "DELETE FROM inv.inv_itm_subinventories WHERE subinv_id = " . $pkeyID;
        $affctd1 = execUpdtInsSQL($insSQL, "Store Name:" . $extrInfo);
    }
    if ($affctd1 > 0) {
        $dsply = "Successfully Deleted the ff Records-";
        $dsply .= "<br/>$affctd2 Store User(s)!";
        $dsply .= "<br/>$affctd3 Store Cage(s)!";
        $dsply .= "<br/>$affctd1 Store(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function getStoreUsrLineID($user_id, $storewhsID) {
    $sqlStr = "select line_id from inv.inv_user_subinventories where user_id = " .
            loc_db_escape_string($user_id) . " and subinv_id = " .
            loc_db_escape_string($storewhsID) . " ORDER BY line_id DESC LIMIT 1 OFFSET 0";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -1;
}

function createStoreUser($storewhsUsrID, $storeID, $strtDte, $endDte) {
    global $usrID;
    global $orgID;
    $dateStr = getDB_Date_time();
    if ($strtDte != "") {
        $strtDte = cnvrtDMYTmToYMDTm($strtDte);
    }
    if ($endDte != "") {
        $endDte = cnvrtDMYTmToYMDTm($endDte);
    }
    $insSQL = "INSERT INTO inv.inv_user_subinventories(
            user_id, subinv_id, start_date, end_date, created_by, creation_date, 
            last_update_by, last_update_date, org_id) " .
            "VALUES (" . loc_db_escape_string($storewhsUsrID) .
            ", " . loc_db_escape_string($storeID) .
            ", '" . loc_db_escape_string($strtDte) .
            "', '" . loc_db_escape_string($endDte) .
            "', " . loc_db_escape_string($usrID) .
            ", '" . loc_db_escape_string($dateStr) .
            "', " . loc_db_escape_string($usrID) .
            ", '" . loc_db_escape_string($dateStr) .
            "', " . loc_db_escape_string($orgID) .
            ")";
    return execUpdtInsSQL($insSQL);
}

function updateStoreUser($lineID, $storewhsUsrID, $storeID, $strtDte, $endDte) {
    global $usrID;
    $dateStr = getDB_Date_time();
    if ($strtDte != "") {
        $strtDte = cnvrtDMYTmToYMDTm($strtDte);
    }
    if ($endDte != "") {
        $endDte = cnvrtDMYTmToYMDTm($endDte);
    }
    $insSQL = "UPDATE inv.inv_user_subinventories
                SET user_id=" . loc_db_escape_string($storewhsUsrID) . ", 
                    subinv_id=" . loc_db_escape_string($storeID) . ", 
                    start_date='" . loc_db_escape_string($strtDte) . "', 
                    end_date='" . loc_db_escape_string($endDte) . "', 
                    last_update_by=" . loc_db_escape_string($usrID) . ", 
                    last_update_date='" . loc_db_escape_string($dateStr) . "'
              WHERE line_id = " . $lineID;
    return execUpdtInsSQL($insSQL);
}

function deleteStoreUser($pkeyID, $extrInfo = "") {
    $insSQL = "DELETE FROM inv.inv_user_subinventories WHERE line_id = " . $pkeyID;
    $affctd2 = execUpdtInsSQL($insSQL, "Store Name:" . $extrInfo);
    if ($affctd2 > 0) {
        $dsply = "Successfully Deleted the ff Records-";
        $dsply .= "<br/>$affctd2 Store User(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No record Deleted";
        return "<p style=\"text-align:left;color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function get_StoreCages($pkID, $prsnID, $searchWord, $searchIn, $offset, $limit_size) {
    $whereCls = "";
    $extrWhere = "";
    global $orgID;
    global $fnccurid;
    global $gnrlTrnsDteYMD;
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
        CASE WHEN accb.get_accnt_crncy_id(a.inv_asset_acct_id)=$fnccurid THEN accb.get_ltst_accnt_bals(a.inv_asset_acct_id, '" . $gnrlTrnsDteYMD . "') 
            ELSE accb.get_ltst_accnt_crncy_bals(a.inv_asset_acct_id, '" . $gnrlTrnsDteYMD . "') END acct_bals,
        vms.get_ltst_stock_bals1(a.store_id, a.line_id,-1,'','" . $gnrlTrnsDteYMD . "') ttlCgAmnt
        FROM inv.inv_shelf a
        LEFT OUTER JOIN inv.inv_itm_subinventories b ON (b.subinv_id = a.store_id)
        LEFT OUTER JOIN org.org_sites_locations c ON (c.location_id = b.lnkd_site_id)
        WHERE (a.org_id=" . $orgID . "" . $extrWhere . $whereCls . ") 
        ORDER BY a.shelve_name LIMIT " . $limit_size . " OFFSET " . abs($offset * $limit_size);
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_StoreCagesTtl($pkID, $prsnID, $searchWord, $searchIn) {
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
         WHERE (a.org_id=" . $orgID . "" . $extrWhere . $whereCls . ")";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return 0;
}

function get_OneCageDet($pkID) {
    global $fnccurid;
    global $gnrlTrnsDteYMD;
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
        CASE WHEN accb.get_accnt_crncy_id(a.inv_asset_acct_id)=$fnccurid THEN accb.get_ltst_accnt_bals(a.inv_asset_acct_id, '" . $gnrlTrnsDteYMD . "') 
            ELSE accb.get_ltst_accnt_crncy_bals(a.inv_asset_acct_id, '" . $gnrlTrnsDteYMD . "') END acct_bals,
        vms.get_ltst_stock_bals1(a.store_id, a.line_id,-1,'','" . $gnrlTrnsDteYMD . "') ttlCgAmnt,
        REPLACE(org.get_criteria_name(a.allwd_group_value::bigint,a.allwd_group_type),'Everyone','') group_name,
        a.managers_wthdrwl_limit,
        a.managers_deposit_limit,
        a.dflt_item_type,
        a.dflt_item_state
        FROM inv.inv_shelf a
        LEFT OUTER JOIN inv.inv_itm_subinventories b ON (b.subinv_id = a.store_id)
        LEFT OUTER JOIN org.org_sites_locations c ON (c.location_id = b.lnkd_site_id)
        WHERE (a.line_id=" . $pkID . ")";
//echo $strSql;
    $result = executeSQLNoParams($strSql);
    return $result;
}

function getCageTillID($shlveNm, $storewhsid) {
    $sqlStr = "select line_id from inv.inv_shelf where lower(shelve_name) = '" .
            loc_db_escape_string(strtolower($shlveNm)) .
            "' and store_id = " . loc_db_escape_string($storewhsid);
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -1;
}

function getCageMngrID($lineid) {
    $sqlStr = "select cage_shelve_mngr_id from inv.inv_shelf where line_id = " . loc_db_escape_string($lineid);
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -1;
}

function createCageTill($orgid, $shelfID, $storeID, $shelfNm, $shelfDesc, $lnkdCstmrID, $allwdGrpType, $allwdGrpVal, $invAcntID,
        $cageMngrID, $dfltItmState, $mngrsWthdrwLmt, $mngrsDepLmt, $dfltType, $isenbled) {
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

function updateCageTill($lineid, $shelfID, $storeID, $shelfNm, $shelfDesc, $lnkdCstmrID, $allwdGrpType, $allwdGrpVal, $invAcntID,
        $cageMngrID, $dfltItmState, $mngrsWthdrwLmt, $mngrsDepLmt, $dfltType, $isenbled) {
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
        $dsply = "No Record Deleted<br/>Cannot Delete Store Cages used in Transactions!";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
    $selSQL1 = "Select count(1) from vms.vms_transaction_lines WHERE src_cage_shelve_id = " . $pkeyID . " or dest_cage_shelve_id = " . $pkeyID;
    $result1 = executeSQLNoParams($selSQL1);
    $trnsCnt1 = 0;
    while ($row = loc_db_fetch_array($result1)) {
        $trnsCnt1 = (float) $row[0];
    }
    if ($trnsCnt1 > 0) {
        $dsply = "No Record Deleted<br/>Cannot Delete Store Cages used in Transactions!";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
    $selSQL2 = "Select count(1) from vms.vms_transaction_pymnt WHERE src_cage_shelve_id = " . $pkeyID . " or dest_cage_shelve_id = " . $pkeyID;
    $result2 = executeSQLNoParams($selSQL2);
    $trnsCnt2 = 0;
    while ($row = loc_db_fetch_array($result2)) {
        $trnsCnt2 = (float) $row[0];
    }
    if ($trnsCnt2 > 0) {
        $dsply = "No Record Deleted<br/>Cannot Delete Store Cages used in Transactions!";
        return "<p style=\"text-align:left;color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
    $selSQL3 = "Select count(1) from vms.vms_stock_daily_bals WHERE cage_shelve_id = " . $pkeyID;
    $result3 = executeSQLNoParams($selSQL3);
    $trnsCnt3 = 0;
    while ($row = loc_db_fetch_array($result3)) {
        $trnsCnt3 = (float) $row[0];
    }
    if ($trnsCnt3 > 0) {
        $dsply = "No Record Deleted<br/>Cannot Delete Store Cages used in Transactions!";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
    $affctd1 = 0;
    if (($trnsCnt + $trnsCnt1 + $trnsCnt2 + $trnsCnt3) <= 0) {
        $insSQL = "DELETE FROM inv.inv_shelf WHERE line_id = " . $pkeyID;
        $affctd1 = execUpdtInsSQL($insSQL, "Cage/Till Name:" . $extrInfo);
    }
    if ($affctd1 > 0) {
        $dsply = "Successfully Deleted the ff Records-";
        $dsply .= "<br/>$affctd1 Store Cage(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function get_INVGlIntrfc($searchWord, $searchIn, $offset, $limit_size, $orgID, $dte1, $dte2, $notgonetogl, $imblcnTrns, $usrTrns, $lowVal,
        $highVal) {
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
        $usrTrnsSql = " and (trns_source != 'SYS') ";
    }
    if ($imblcnTrns) {
        $imblnce_trns = " and ((Select string_agg(tbl1.ids1, ',') from (select string_agg(',' || v.interface_id||',', '') ids1
      from  scm.scm_gl_interface v
      group by v.src_doc_id,v.src_doc_typ, substring(v.trnsctn_date from 0 for 11)
      having COALESCE(SUM(v.dbt_amount),0)-COALESCE(SUM(v.crdt_amount),0) != 0) tbl1) like '%,'||a.interface_id||',%')";
        /*
          or a.interface_id IN (select MIN(v.interface_id)
          from  scm.scm_gl_interface v
          group by v.source_trns_id, substring(v.trnsctn_date from 0 for 11)
          having COALESCE(SUM(v.dbt_amount),0)-COALESCE(SUM(v.crdt_amount),0) != 0) */
        /*
          (select MAX(v.interface_id)
          from  scm.scm_gl_interface v
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
        $whereCls = "(a.src_doc_typ ||' '||scm.get_src_doc_num(a.src_doc_id,a.src_doc_typ) ilike '" . loc_db_escape_string($searchWord) .
                "') and ";
    } else if ($searchIn == "Transaction Description") {
        $whereCls = "(a.transaction_desc ilike '" . loc_db_escape_string($searchWord) .
                "') and ";
    }
    $strSql = "SELECT a.accnt_id, b.accnt_num, b.accnt_name, a.transaction_desc, 
to_char(to_timestamp(a.trnsctn_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS'), a.dbt_amount, " .
            "a.crdt_amount, a.src_doc_id source_trns_id, a.src_doc_typ, a.gl_batch_id, " .
            "(select d.batch_name from accb.accb_trnsctn_batches d where d.batch_id = a.gl_batch_id) btch_nm, " .
            "a.interface_id, a.func_cur_id, -1, scm.get_src_doc_num(a.src_doc_id,a.src_doc_typ), "
            . "gst.get_pssbl_val(a.func_cur_id), a.src_doc_typ trns_source " .
            "FROM scm.scm_gl_interface a, accb.accb_chart_of_accnts b " .
            "WHERE ((a.accnt_id = b.accnt_id) and " . $whereCls . "(b.org_id = " . $orgID . ")" . $to_gl .
            $imblnce_trns . $usrTrnsSql . $amntCls . " and (to_timestamp(a.trnsctn_date,'YYYY-MM-DD HH24:MI:SS') between to_timestamp('" . $dte1 .
            "','YYYY-MM-DD HH24:MI:SS') AND to_timestamp('" . $dte2 . "','YYYY-MM-DD HH24:MI:SS'))) " .
            "ORDER BY a.interface_id DESC LIMIT " . $limit_size . " OFFSET " . abs($offset * $limit_size);
//echo $strSql;
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_INVGlIntrfcTtl($searchWord, $searchIn, $orgID, $dte1, $dte2, $notgonetogl, $imblcnTrns, $usrTrns, $lowVal, $highVal) {
    global $gnrlTrnsDteYMDHMS;
    execUpdtInsSQL("UPDATE scm.scm_gl_interface SET trnsctn_date='" . $gnrlTrnsDteYMDHMS . "' WHERE trnsctn_date=''");
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
        $imblnce_trns = " and ((Select string_agg(tbl1.ids1, ',') from (select string_agg(',' || v.interface_id||',', '') ids1
      from  scm.scm_gl_interface v
      group by v.src_doc_id,v.src_doc_typ, substring(v.trnsctn_date from 0 for 11)
      having COALESCE(SUM(v.dbt_amount),0)-COALESCE(SUM(v.crdt_amount),0) != 0) tbl1) like '%,'||a.interface_id||',%')";
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
        $whereCls = "(a.src_doc_typ ||' '||scm.get_src_doc_num(a.src_doc_id,a.src_doc_typ) ilike '" . loc_db_escape_string($searchWord) .
                "') and ";
    } else if ($searchIn == "Transaction Description") {
        $whereCls = "(a.transaction_desc ilike '" . loc_db_escape_string($searchWord) .
                "') and ";
    }
    $strSql = "SELECT count(1) " .
            "FROM scm.scm_gl_interface a, accb.accb_chart_of_accnts b " .
            "WHERE ((a.accnt_id = b.accnt_id) and " . $whereCls . "(b.org_id = " . $orgID . ")" . $to_gl .
            $imblnce_trns . $usrTrnsSql . $amntCls . " and (to_timestamp(a.trnsctn_date,'YYYY-MM-DD HH24:MI:SS') between to_timestamp('" . $dte1 .
            "','YYYY-MM-DD HH24:MI:SS') AND to_timestamp('" . $dte2 . "','YYYY-MM-DD HH24:MI:SS'))) ";
//echo $strSql;
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return 0;
}

function get_OneINVGlIntrfcDet($intrfcID) {
    $strSql = "SELECT a.accnt_id, b.accnt_num, b.accnt_name, a.transaction_desc, 
to_char(to_timestamp(a.trnsctn_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS'), a.dbt_amount, " .
            "a.crdt_amount, a.src_doc_id source_trns_id, 'INVroll Run', a.gl_batch_id, " .
            "(select d.batch_name from accb.accb_trnsctn_batches d where d.batch_id = a.gl_batch_id) btch_nm, a.interface_id, a.func_cur_id, " .
            "-1, 'Batch Name', gst.get_pssbl_val(a.func_cur_id), 
             a.src_doc_typ trns_source, a.net_amount, a.net_amount entered_amnt, a.func_cur_id entered_amt_crncy_id, 
             gst.get_pssbl_val(a.func_cur_id), a.net_amount accnt_crncy_amnt, a.func_cur_id accnt_crncy_id, 
             gst.get_pssbl_val(a.func_cur_id), 
             1 func_cur_exchng_rate, 1 accnt_cur_exchng_rate " .
            "FROM scm.scm_gl_interface a, accb.accb_chart_of_accnts b " .
            "WHERE ((a.accnt_id = b.accnt_id) and (a.interface_id = " . $intrfcID . "))";
    $result = executeSQLNoParams($strSql);
    return $result;
}

function getINVGLIntrfcDffrnc($orgID) {
    $strSql = "SELECT COALESCE(SUM(a.dbt_amount),0) dbt_sum, 
COALESCE(SUM(a.crdt_amount),0) crdt_sum 
FROM scm.scm_gl_interface a, accb.accb_chart_of_accnts b 
WHERE a.gl_batch_id = -1 and a.accnt_id = b.accnt_id and b.org_id=" . $orgID .
            " ";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        $dffrce1 = (float) $row[0] - (float) $row[1];
        return $dffrce1;
    }
    return 0;
}

function createINVTrnsGLIntFcLn($accntid, $trnsdesc, $dbtamnt, $trnsdte, $crncyid, $crdtamnt, $netamnt, $srcDocTyp, $srcDocID, $srcDocLnID,
        $dateStr, $trnsLnTyp, $trnsSrc, $entrdAMnt, $entrdCrncyID, $acctCrncyAmnt, $acctCrncyID, $funcCrncyRate, $acntCrncyRate) {
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
    $insSQL = "INSERT INTO scm.scm_gl_interface(
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

function updateINVTrnsGLIntFcLn($intrfcLineID, $accntid, $trnsdesc, $dbtamnt, $trnsdte, $crncyid, $crdtamnt, $netamnt, $srcDocTyp,
        $srcDocID, $srcDocLnID, $dateStr, $trnsLnTyp, $trnsSrc, $entrdAMnt, $entrdCrncyID, $acctCrncyAmnt, $acctCrncyID, $funcCrncyRate,
        $acntCrncyRate) {
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
    $insSQL = "UPDATE scm.scm_gl_interface
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
    //and trns_source='USR'
    return execUpdtInsSQL($insSQL);
}

function deleteINVTrnsGLIntFcLn($intrfcLineID, $intrfcDesc) {
    $delSQL = "DELETE FROM scm.scm_gl_interface WHERE interface_id = " . $intrfcLineID . " and gl_batch_id<=0 and trns_source != 'SYS'";
    return execUpdtInsSQL($delSQL, $intrfcDesc);
}

function getUomBrkDwn($itemID) {
    $strSQL = "Select * from (SELECT a.itm_uom_id mt, "
            . "(SELECT b.uom_name FROM inv.unit_of_measure b WHERE b.uom_id = a.uom_id) uom, " .
            " a.uom_id mt, uom_level mt, cnvsn_factor mt, selling_price, price_less_tax, inv.get_invitm_unitval(a.item_id) " .
            " FROM inv.itm_uoms a WHERE a.item_id = $itemID " .
            " union " .
            " SELECT -1 mt, (SELECT b.uom_name FROM inv.unit_of_measure b WHERE b.uom_id = a.base_uom_id) uom, " .
            " base_uom_id mt, -1 mt, 1 mt, selling_price, orgnl_selling_price, inv.get_invitm_unitval(a.item_id) " .
            " FROM inv.inv_itm_list a WHERE a.item_id = $itemID) tbl1 ORDER BY 4 DESC";
    $result = executeSQLNoParams($strSQL);
    return $result;
}

function getUomCnvrsnFctr($itemID, $uomNm) {
    $strSQL = "Select tbl1.mtcf from (SELECT a.itm_uom_id mt, "
            . "(SELECT b.uom_name FROM inv.unit_of_measure b WHERE b.uom_id = a.uom_id) uom, " .
            " a.uom_id mt, uom_level mt, cnvsn_factor mtcf, selling_price, price_less_tax, inv.get_invitm_unitval(a.item_id) " .
            " FROM inv.itm_uoms a WHERE a.item_id = $itemID " .
            " union " .
            " SELECT -1 mt, (SELECT b.uom_name FROM inv.unit_of_measure b WHERE b.uom_id = a.base_uom_id) uom, " .
            " base_uom_id mt, -1 mt, 1 mtcf, selling_price, orgnl_selling_price, inv.get_invitm_unitval(a.item_id) " .
            " FROM inv.inv_itm_list a WHERE a.item_id = $itemID) tbl1 "
            . "WHERE lower(tbl1.uom) = '" . loc_db_escape_string(strtolower($uomNm)) . "'";
    $result = executeSQLNoParams($strSQL);
    while ($rw = loc_db_fetch_array($result)) {
        return (float) $rw[0];
    }
    return 1;
}

function get_One_PrdtCtgry_Det($catID) {
    $strSql = "select a.cat_id, a.cat_name, a.cat_desc, a.start_date, a.end_date, a.enabled_flag "
            . "from inv.inv_product_categories a " .
            "WHERE(a.cat_id = " . $catID . ")";
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_Basic_PrdtCtgry($searchWord, $searchIn, $offset, $limit_size) {
    $whrcls = "";
    if ($searchIn == "Category Name") {
        $whrcls = " and (a.cat_name ilike '" . loc_db_escape_string($searchWord) .
                "')";
    } else if ($searchIn == "Category Description") {
        $whrcls = " and (a.cat_desc ilike '" . loc_db_escape_string($searchWord) .
                "')";
    }
    $strSql = "select a.cat_id, a.cat_name, a.cat_desc, a.start_date, a.end_date, a.enabled_flag "
            . "from inv.inv_product_categories a " .
            "WHERE ((1=1)" . $whrcls . ") ORDER BY a.cat_id DESC LIMIT " . $limit_size .
            " OFFSET " . (abs($offset * $limit_size));
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_Total_PrdtCtgry($searchWord, $searchIn) {
    $whrcls = "";
    if ($searchIn == "Category Name") {
        $whrcls = " and (a.cat_name ilike '" . loc_db_escape_string($searchWord) .
                "')";
    } else if ($searchIn == "Category Description") {
        $whrcls = " and (a.cat_desc ilike '" . loc_db_escape_string($searchWord) .
                "')";
    }
    $strSql = "SELECT count(1)  " .
            "FROM inv.inv_product_categories a " .
            "WHERE ((1=1)" . $whrcls . ")";

    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return 0;
}

function createPrdctCtgry($orgid, $cat_name, $cat_desc, $isEnbld) {
    global $usrID;
    $insSQL = "INSERT INTO inv.inv_product_categories(cat_name, cat_desc, created_by, creation_date,
                                       last_update_by, last_update_date, start_date, end_date, org_id, enabled_flag) " .
            "VALUES ('" . loc_db_escape_string($cat_name) .
            "', '" . loc_db_escape_string($cat_desc) .
            "', " . $usrID . ", to_char(now(), 'YYYY-MM-DD HH24:MI:SS'), " . $usrID .
            ", to_char(now(), 'YYYY-MM-DD HH24:MI:SS'),'','', " . $orgid . ", '" .
            cnvrtBoolToBitStr($isEnbld) . "')";
    return execUpdtInsSQL($insSQL);
}

function updatePrdctCtgry($cat_id, $cat_name, $cat_desc, $isEnbld) {
    global $usrID;
    $updtSQL = "UPDATE inv.inv_product_categories SET " .
            "cat_name='" . loc_db_escape_string($cat_name) .
            "', cat_desc='" . loc_db_escape_string($cat_desc) .
            "', last_update_by=" . $usrID . ", " .
            "last_update_date=to_char(now(), 'YYYY-MM-DD HH24:MI:SS')" .
            ", enabled_flag='" . cnvrtBoolToBitStr($isEnbld) .
            "' WHERE (cat_id =" . $cat_id . ")";
    return execUpdtInsSQL($updtSQL);
}

function deletePrdctCtgry($ctgryid, $ctgryNm) {
    $trnsCnt1 = 0;
    $trnsCnt2 = 0;
    $strSql = "SELECT count(1) FROM inv.inv_itm_list a WHERE(a.category_id = " . $ctgryid . ")";
    $result1 = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result1)) {
        $trnsCnt1 = (float) $row[0];
    }
    $strSql = "SELECT count(1) FROM inv.inv_itm_type_templates a WHERE(a.category_id = " . $ctgryid . ")";
    $result1 = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result1)) {
        $trnsCnt2 = (float) $row[0];
    }
    if (($trnsCnt1 + $trnsCnt2) > 0) {
        $dsply = "No Record Deleted<br/>Cannot delete Product Categories used in Item or Item Template Creation!";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
    $delSQL = "DELETE FROM inv.inv_product_categories WHERE cat_id = " . $ctgryid;
    $affctd1 = execUpdtInsSQL($delSQL, "Category Name = " . $ctgryNm);
    if ($affctd1 > 0) {
        $dsply = "";
        $dsply .= "<br/>Successfully Executed the ff-";
        $dsply .= "<br/>Deleted $affctd1 Product Category(ies)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Deleted";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function get_One_UOMStp_Det($uomID) {
    $strSql = "select a.uom_id, a.uom_name, a.uom_desc, a.enabled_flag from inv.unit_of_measure a " .
            "WHERE(a.uom_id = " . $uomID . ")";
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_Basic_UOMStp($searchWord, $searchIn, $offset, $limit_size) {
    $whrcls = "";
    if ($searchIn == "UOM Name") {
        $whrcls = " and (a.uom_name ilike '" . loc_db_escape_string($searchWord) .
                "')";
    } else if ($searchIn == "UOM Description") {
        $whrcls = " and (a.uom_desc ilike '" . loc_db_escape_string($searchWord) .
                "')";
    }
    $strSql = "select a.uom_id, a.uom_name, a.uom_desc, a.enabled_flag from inv.unit_of_measure a "
            . "WHERE ((1=1)" . $whrcls . ") ORDER BY a.uom_id DESC LIMIT " . $limit_size .
            " OFFSET " . (abs($offset * $limit_size));
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_Total_UOMStp($searchWord, $searchIn) {
    $whrcls = "";
    if ($searchIn == "UOM Name") {
        $whrcls = " and (a.uom_name ilike '" . loc_db_escape_string($searchWord) .
                "')";
    } else if ($searchIn == "UOM Description") {
        $whrcls = " and (a.uom_desc ilike '" . loc_db_escape_string($searchWord) .
                "')";
    }
    $strSql = "SELECT count(1)  " .
            "FROM inv.unit_of_measure a " .
            "WHERE ((1=1)" . $whrcls . ")";

    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return 0;
}

function createUOMStp($orgid, $uom_name, $uom_desc, $isEnbld) {
    global $usrID;
    $insSQL = "INSERT INTO inv.unit_of_measure(
	uom_name, uom_desc, enabled_flag, org_id, created_by, creation_date, last_update_by, last_update_date) " .
            "VALUES ('" . loc_db_escape_string($uom_name) .
            "', '" . loc_db_escape_string($uom_desc) .
            "', '" . cnvrtBoolToBitStr($isEnbld) . "', " . $orgid .
            ", " . $usrID . ", to_char(now(), 'YYYY-MM-DD HH24:MI:SS'), " . $usrID .
            ", to_char(now(), 'YYYY-MM-DD HH24:MI:SS'))";
    return execUpdtInsSQL($insSQL);
}

function updateUOMStp($uom_id, $uom_name, $uom_desc, $isEnbld) {
    global $usrID;
    $updtSQL = "UPDATE inv.unit_of_measure SET " .
            "uom_name='" . loc_db_escape_string($uom_name) .
            "', uom_desc='" . loc_db_escape_string($uom_desc) .
            "', last_update_by=" . $usrID . ", last_update_date=to_char(now(), 'YYYY-MM-DD HH24:MI:SS')" .
            ", enabled_flag='" . cnvrtBoolToBitStr($isEnbld) .
            "' WHERE (uom_id =" . $uom_id . ")";
    return execUpdtInsSQL($updtSQL);
}

function deleteUOMStp($uomid, $uomNm) {
    $trnsCnt1 = 0;
    $trnsCnt2 = 0;
    $trnsCnt3 = 0;
    $strSql = "SELECT count(1) FROM inv.inv_itm_list a WHERE(a.base_uom_id = " . $uomid . ")";
    $result1 = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result1)) {
        $trnsCnt1 = (float) $row[0];
    }
    $strSql = "SELECT count(1) FROM inv.itm_uoms a WHERE(a.uom_id = " . $uomid . ")";
    $result1 = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result1)) {
        $trnsCnt2 = (float) $row[0];
    }
    $strSql = "SELECT count(1) FROM inv.inv_tmplt_uoms a WHERE(a.uom_id = " . $uomid . ")";
    $result1 = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result1)) {
        $trnsCnt3 = (float) $row[0];
    }
    if (($trnsCnt1 + $trnsCnt2 + $trnsCnt3) > 0) {
        $dsply = "No Record Deleted<br/>Cannot delete a UOMs used in Item or Item Template Creation!";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
    $delSQL = "DELETE FROM inv.unit_of_measure WHERE uom_id = " . $uomid;
    $affctd1 = execUpdtInsSQL($delSQL, "UOM Name = " . $uomNm);
    if ($affctd1 > 0) {
        $dsply = "";
        $dsply .= "<br/>Successfully Executed the ff-";
        $dsply .= "<br/>Deleted $affctd1 UOM(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Deleted";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function get_ItmTmplts($searchFor, $searchIn, $offset, $limit_size) {
    $whereClause = "";
    $strSql = "";
    if ($searchIn == "Name") {
        $whereClause = " and ((a.item_type_name) ilike '" . loc_db_escape_string($searchFor) .
                "')";
    } else if ($searchIn == "Description") {
        $whereClause = " and (a.item_type_desc ilike '" . loc_db_escape_string($searchFor) . "')";
    }
    $strSql = "select item_type_id, item_type_name, item_type_desc, category_id,
                (select cat_name FROM inv.inv_product_categories WHERE cat_id = category_id), 
                (SELECT uom_name from inv.unit_of_measure WHERE uom_id = a.base_uom_id), a.base_uom_id, tax_code_id, " .
            "dscnt_code_id, extr_chrg_id, inv_asset_acct_id, cogs_acct_id, sales_rev_accnt_id, sales_ret_accnt_id, " .
            " purch_ret_accnt_id, expense_accnt_id, is_tmplt_enabled_flag, planning_enabled, min_level, max_level, " .
            " selling_price, item_type, value_price_crncy_id, gst.get_pssbl_val(value_price_crncy_id), auto_dflt_in_vms_trns  from inv.inv_itm_type_templates a " .
            "WHERE ((1=1)$whereClause) ORDER BY a.item_type_id DESC LIMIT " . $limit_size .
            " OFFSET " . abs($offset * $limit_size);
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_ItmTmpltsTtl($searchFor, $searchIn) {

    $whereClause = "";
    $strSql = "";
    if ($searchIn == "Name") {
        $whereClause = " and ((a.item_type_name) ilike '" . loc_db_escape_string($searchFor) .
                "')";
    } else if ($searchIn == "Description") {
        $whereClause = " and (a.item_type_desc ilike '" . loc_db_escape_string($searchFor) . "')";
    }
    $strSql = "select count(1) from inv.inv_itm_type_templates a " .
            "WHERE ((1=1)$whereClause)";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return 0;
}

function get_OneItmTmplts($tmplt_id) {
    $strSql = "SELECT item_type_id, item_type_name, item_type_desc, 
        cogs_acct_id, accb.get_accnt_num(a.cogs_acct_id) || '.' || accb.get_accnt_name(a.cogs_acct_id), 
        inv_asset_acct_id, accb.get_accnt_num(a.inv_asset_acct_id) || '.' || accb.get_accnt_name(a.inv_asset_acct_id), 
       category_id, (select cat_name FROM inv.inv_product_categories WHERE cat_id = category_id), 
      a.base_uom_id, (SELECT uom_name from inv.unit_of_measure WHERE uom_id = a.base_uom_id),  
       is_tmplt_enabled_flag, selling_price, 
       sales_rev_accnt_id, accb.get_accnt_num(a.sales_rev_accnt_id) || '.' || accb.get_accnt_name(a.sales_rev_accnt_id), 
       sales_ret_accnt_id, accb.get_accnt_num(a.sales_ret_accnt_id) || '.' || accb.get_accnt_name(a.sales_ret_accnt_id), 
       purch_ret_accnt_id, accb.get_accnt_num(a.purch_ret_accnt_id) || '.' || accb.get_accnt_name(a.purch_ret_accnt_id), 
       expense_accnt_id, accb.get_accnt_num(a.expense_accnt_id) || '.' || accb.get_accnt_name(a.expense_accnt_id), 
       org_id, tax_code_id, scm.get_tax_code(tax_code_id), 
       dscnt_code_id, scm.get_tax_code(dscnt_code_id), 
       extr_chrg_id, scm.get_tax_code(extr_chrg_id), min_level, max_level, 
       planning_enabled, item_type, value_price_crncy_id, gst.get_pssbl_val(value_price_crncy_id), auto_dflt_in_vms_trns 
     FROM inv.inv_itm_type_templates a WHERE a.item_type_id=" . $tmplt_id;
    return executeSQLNoParams($strSql);
}

function get_OneItmTmpltStores($tmplt_id) {
    $strSql = "SELECT row_number() over(order by b.subinv_name) as row , b.subinv_name, a.shelves,
            to_char(to_timestamp(a.start_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS'), 
            CASE WHEN a.end_date='' THEN a.end_date ELSE to_char(to_timestamp(a.end_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') END, 
            a.subinv_id, a.shelves_ids, a.line_id 
            FROM inv.inv_item_types_stores_template a inner join inv.inv_itm_subinventories b ON a.subinv_id = b.subinv_id 
            WHERE a.item_type_template_id = " . $tmplt_id;
    return executeSQLNoParams($strSql);
}

function get_OneItmTmpltUOMs($tmplt_id) {
    $strSql = "SELECT row_number() over(order by tbl1.uom_level DESC, tbl1.tmplt_uom_id) as row, tbl1.* FROM 
           (SELECT b.uom_name, a.cnvsn_factor,
          a.uom_level, a.tmplt_uom_id, a.uom_id
               FROM inv.inv_tmplt_uoms a inner join inv.unit_of_measure b ON a.uom_id = b.uom_id 
               WHERE a.item_type_id = " . $tmplt_id . "
               UNION
            SELECT b.uom_name, 1,
          -1, -1, a.base_uom_id
          FROM inv.inv_itm_type_templates a inner join inv.unit_of_measure b ON a.base_uom_id = b.uom_id 
           WHERE a.item_type_id = " . $tmplt_id . ") tbl1 order by tbl1.uom_level DESC, tbl1.tmplt_uom_id";
    return executeSQLNoParams($strSql);
}

function getItmTmpltID($itemNm) {
    $sqlStr = "select item_type_id from inv.inv_itm_type_templates where lower(item_type_name) = '" .
            loc_db_escape_string(strtolower($itemNm)) . "'";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -1;
}

function createItmTmplt($itmNm, $itmDesc, $ctgryID, $orgid, $isenbled, $sllgPrice, $cogsID, $assetID, $revID, $salesRetID, $prchRetID,
        $expnsID, $txID, $dscntID, $chrgID, $minLvl, $maxLvl, $plnngEnbld, $itmType, $baseUomID, $valCrncyID, $autoDfltINV) {
    global $usrID;
    $dateStr = getDB_Date_time();
    $insSQL = "INSERT INTO inv.inv_itm_type_templates(
            item_type_name, item_type_desc, category_id, org_id, is_tmplt_enabled_flag, 
            selling_price, cogs_acct_id, inv_asset_acct_id, created_by, creation_date, 
            last_update_by, last_update_date, 
            sales_rev_accnt_id, sales_ret_accnt_id, purch_ret_accnt_id, expense_accnt_id, 
            tax_code_id, dscnt_code_id, extr_chrg_id, min_level, max_level, 
            planning_enabled, item_type, base_uom_id, value_price_crncy_id, 
            auto_dflt_in_vms_trns) " .
            "VALUES ('" . loc_db_escape_string($itmNm) .
            "', '" . loc_db_escape_string($itmDesc) .
            "'," . loc_db_escape_string($ctgryID) .
            "," . loc_db_escape_string($orgid) .
            ", '" . loc_db_escape_string($isenbled) .
            "', " . loc_db_escape_string($sllgPrice) .
            ", " . loc_db_escape_string($cogsID) .
            ", " . loc_db_escape_string($assetID) .
            ", " . loc_db_escape_string($usrID) .
            ", '" . loc_db_escape_string($dateStr) .
            "', " . loc_db_escape_string($usrID) .
            ", '" . loc_db_escape_string($dateStr) .
            "', " . loc_db_escape_string($revID) .
            ", " . loc_db_escape_string($salesRetID) .
            ", " . loc_db_escape_string($prchRetID) .
            ", " . loc_db_escape_string($expnsID) .
            ", " . loc_db_escape_string($txID) .
            ", " . loc_db_escape_string($dscntID) .
            ", " . loc_db_escape_string($chrgID) .
            ", " . loc_db_escape_string($minLvl) .
            ", " . loc_db_escape_string($maxLvl) .
            ", '" . loc_db_escape_string($plnngEnbld) .
            "', '" . loc_db_escape_string($itmType) .
            "', " . loc_db_escape_string($baseUomID) .
            ", " . loc_db_escape_string($valCrncyID) .
            ", '" . loc_db_escape_string($autoDfltINV) .
            "')";
    return execUpdtInsSQL($insSQL);
}

function updateItmTmplt($itemid, $itmNm, $itmDesc, $ctgryID, $orgid, $isenbled, $sllgPrice, $cogsID, $assetID, $revID, $salesRetID,
        $prchRetID, $expnsID, $txID, $dscntID, $chrgID, $minLvl, $maxLvl, $plnngEnbld, $itmType, $baseUomID, $valCrncyID, $autoDfltINV) {
    global $usrID;
    $dateStr = getDB_Date_time();
    $insSQL = "UPDATE inv.inv_itm_type_templates
   SET item_type_name='" . loc_db_escape_string($itmNm) .
            "', item_type_desc='" . loc_db_escape_string($itmDesc) .
            "', category_id=" . loc_db_escape_string($ctgryID) .
            ", org_id=" . loc_db_escape_string($orgid) .
            ", is_tmplt_enabled_flag='" . loc_db_escape_string($isenbled) .
            "', selling_price=" . loc_db_escape_string($sllgPrice) .
            ", cogs_acct_id=" . loc_db_escape_string($cogsID) .
            ", inv_asset_acct_id=" . loc_db_escape_string($assetID) .
            ", last_update_by=" . loc_db_escape_string($usrID) .
            ", last_update_date='" . loc_db_escape_string($dateStr) .
            "', sales_rev_accnt_id=" . loc_db_escape_string($revID) .
            ", sales_ret_accnt_id=" . loc_db_escape_string($salesRetID) .
            ", purch_ret_accnt_id=" . loc_db_escape_string($prchRetID) .
            ", expense_accnt_id=" . loc_db_escape_string($expnsID) .
            ", tax_code_id=" . loc_db_escape_string($txID) .
            ", dscnt_code_id=" . loc_db_escape_string($dscntID) .
            ", extr_chrg_id=" . loc_db_escape_string($chrgID) .
            ", min_level=" . loc_db_escape_string($minLvl) .
            ", max_level=" . loc_db_escape_string($maxLvl) .
            ", planning_enabled='" . loc_db_escape_string($plnngEnbld) .
            "', item_type='" . loc_db_escape_string($itmType) .
            "', base_uom_id=" . loc_db_escape_string($baseUomID) .
            ", value_price_crncy_id=" . loc_db_escape_string($valCrncyID) .
            ", auto_dflt_in_vms_trns='" . loc_db_escape_string($autoDfltINV) .
            "' WHERE item_type_id = " . $itemid;
    return execUpdtInsSQL($insSQL);
}

function getItmTmpltStockID($itmID, $storeID) {
    $sqlStr = "select line_id from inv.inv_item_types_stores_template where item_type_template_id = " . loc_db_escape_string($itmID) .
            " and subinv_id = " . loc_db_escape_string($storeID);
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -1;
}

function createItmTmpltStore($itmID, $storeID, $shelves, $orgID, $strtDte, $endDte, $shelveIDs) {
    global $usrID;
    $dateStr = getDB_Date_time();
    $insSQL = "INSERT INTO inv.inv_item_types_stores_template(
            item_type_template_id, subinv_id, created_by, creation_date, last_update_by, 
            last_update_date, shelves, start_date, end_date, org_id, shelves_ids) " .
            "VALUES (" . loc_db_escape_string($itmID) .
            ", " . loc_db_escape_string($storeID) .
            ", " . loc_db_escape_string($usrID) .
            ", '" . loc_db_escape_string($dateStr) .
            "', " . loc_db_escape_string($usrID) .
            ", '" . loc_db_escape_string($dateStr) .
            "', '" . loc_db_escape_string($shelves) .
            "', '" . loc_db_escape_string($strtDte) .
            "', '" . loc_db_escape_string($endDte) .
            "', " . loc_db_escape_string($orgID) .
            ", '" . loc_db_escape_string($shelveIDs) .
            "')";
    return execUpdtInsSQL($insSQL);
}

function updateItmTmpltStore($stockID, $itmID, $storeID, $shelves, $orgID, $strtDte, $endDte, $shelveIDs) {
    global $usrID;
    $dateStr = getDB_Date_time();
    $insSQL = "UPDATE inv.inv_item_types_stores_template
   SET item_type_template_id=" . loc_db_escape_string($itmID) .
            ", subinv_id=" . loc_db_escape_string($storeID) .
            ", shelves='" . loc_db_escape_string($shelves) .
            "', start_date='" . loc_db_escape_string($strtDte) .
            "', end_date='" . loc_db_escape_string($endDte) .
            "', shelves_ids='" . loc_db_escape_string($shelveIDs) .
            "', last_update_by=" . loc_db_escape_string($usrID) .
            ", last_update_date='" . loc_db_escape_string($dateStr) .
            "', org_id=" . loc_db_escape_string($orgID) .
            "  WHERE line_id = " . $stockID;
    return execUpdtInsSQL($insSQL);
}

function getItmTmpltUomID($itmID, $uomID) {
    $sqlStr = "select tmplt_uom_id from inv.inv_tmplt_uoms where item_type_id = " . loc_db_escape_string($itmID) . " and uom_id = " . loc_db_escape_string($uomID);
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -1;
}

function createItmTmpltUom($itmID, $uomID, $cnvsnFctr, $sortOrdr) {
    global $usrID;
    $dateStr = getDB_Date_time();
    $insSQL = "INSERT INTO inv.inv_tmplt_uoms(
            item_type_id, uom_id, is_base_uom, cnvsn_factor, uom_level, 
            created_by, creation_date, last_update_by, last_update_date) " .
            "VALUES (" . loc_db_escape_string($itmID) .
            ", " . loc_db_escape_string($uomID) .
            ",'0', " . loc_db_escape_string($cnvsnFctr) .
            ", " . loc_db_escape_string($sortOrdr) .
            ", " . loc_db_escape_string($usrID) .
            ", '" . loc_db_escape_string($dateStr) .
            "', " . loc_db_escape_string($usrID) .
            ", '" . loc_db_escape_string($dateStr) .
            "')";
    return execUpdtInsSQL($insSQL);
}

function updateItmTmpltUom($itmUoMID, $itmID, $uomID, $cnvsnFctr, $sortOrdr) {
    global $usrID;
    $dateStr = getDB_Date_time();
    $insSQL = "UPDATE inv.inv_tmplt_uoms
   SET item_type_id=" . loc_db_escape_string($itmID) .
            ", uom_id=" . loc_db_escape_string($uomID) .
            ", cnvsn_factor='" . loc_db_escape_string($cnvsnFctr) .
            "', uom_level=" . loc_db_escape_string($sortOrdr) .
            ", last_update_by=" . loc_db_escape_string($usrID) .
            ", last_update_date='" . loc_db_escape_string($dateStr) .
            "'  WHERE tmplt_uom_id = " . $itmUoMID;
    return execUpdtInsSQL($insSQL);
}

function deleteItmTmplt($pkeyID, $extrInfo = "") {
    $selSQL = "Select count(1) from inv.inv_itm_list where item_id = " . $pkeyID;
    $result = executeSQLNoParams($selSQL);
    $trnsCnt = 0;
    while ($row = loc_db_fetch_array($result)) {
        $trnsCnt = (float) $row[0];
    }
    if ($trnsCnt > 0) {
        $dsply = "No Record Deleted<br/>Cannot Delete Templates used in Creating Items!";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
    $affctd1 = 0;
    $affctd2 = 0;
    $affctd3 = 0;
    $affctd4 = 0;
    if (($trnsCnt) <= 0) {
        $insSQL = "DELETE FROM inv.inv_item_types_stores_template WHERE item_type_template_id = " . $pkeyID;
        $affctd1 = execUpdtInsSQL($insSQL, "Template Name:" . $extrInfo);
        $insSQL = "DELETE FROM inv.inv_tmplt_uoms WHERE item_type_id = " . $pkeyID;
        $affctd2 = execUpdtInsSQL($insSQL, "Template Name:" . $extrInfo);
        $insSQL = "DELETE FROM inv.inv_itm_type_templates WHERE item_type_id = " . $pkeyID;
        $affctd4 = execUpdtInsSQL($insSQL, "Template Name:" . $extrInfo);
    }
    if ($affctd4 > 0) {
        $dsply = "Successfully Deleted the ff Records-";
        $dsply .= "<br/>$affctd4 Template(s)!";
        $dsply .= "<br/>$affctd1 Template Store(s)!";
        $dsply .= "<br/>$affctd2 Template Uom(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function deleteItmTmpltStore($pkeyID, $extrInfo = "") {
    $trnsCnt11 = 0;
    $affctd1 = 0;
    if (($trnsCnt11) <= 0) {
        $insSQL = "DELETE FROM inv.inv_item_types_stores_template WHERE line_id = " . $pkeyID;
        $affctd1 = execUpdtInsSQL($insSQL, "Template Name:" . $extrInfo);
    }
    if ($affctd1 > 0) {
        $dsply = "Successfully Deleted the ff Records-";
        $dsply .= "<br/>$affctd1 Template Store(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function deleteItmTmpltUom($pkeyID, $extrInfo = "") {
    $trnsCnt = 0;
    $affctd2 = 0;
    if (($trnsCnt) <= 0) {
        $insSQL = "DELETE FROM inv.inv_tmplt_uoms WHERE tmplt_uom_id = " . $pkeyID;
        $affctd2 = execUpdtInsSQL($insSQL, "Template Name:" . $extrInfo);
    }
    if ($affctd2 > 0) {
        $dsply = "Successfully Deleted the ff Records-";
        $dsply .= "<br/>$affctd2 Template Uom(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function get_Basic_SalesDoc($searchWord, $searchIn, $offset, $limit_size, $orgID, $shwUnpstdOnly, $shwUnpaidOnly, $shwSelfOnly,
        $qShwMyBranch, $qStrtDte, $qEndDte) {
    global $vwOnlySelf;
    global $usrID;
    global $brnchLocID;
    global $vwOnlyBranch;
    if ($qStrtDte != "") {
        $qStrtDte = cnvrtDMYTmToYMDTm($qStrtDte);
    }
    if ($qEndDte != "") {
        $qEndDte = cnvrtDMYTmToYMDTm($qEndDte);
    }
    $strSql = "";
    $whrcls = "";
    $unpstdCls = "";
    if ($shwUnpstdOnly) {
        $unpstdCls .= " AND (accb.is_gl_batch_pstd(scm.getSalesDocGlBatchID(a.invc_hdr_id,a.invc_type))!='1' and a.approval_status IN ('Approved','Cancelled'))";
    }
    if ($shwUnpaidOnly) {
        $unpstdCls .= " AND (round(scm.get_DocSmryOutsbls(a.invc_hdr_id,a.invc_type),2)>0 and a.approval_status IN ('Approved'))";
    }
    if ($vwOnlySelf === true || $shwSelfOnly === true) {
        $whrcls .= " and (a.created_by = " . $usrID . ")";
    }
    if ($vwOnlyBranch === true || $qShwMyBranch === true) {
        $whrcls .= " and (a.branch_id = " . $brnchLocID . ")";
    }
    if ($searchIn == "Document Number") {
        $whrcls .= " and (a.invc_number ilike '" . loc_db_escape_string($searchWord) . "' or trim(to_char(a.invc_hdr_id, '99999999999999999999')) ilike '" . loc_db_escape_string($searchWord) .
                "')";
    } else if ($searchIn == "Document Description") {
        $whrcls .= " and (a.comments_desc ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Customer Name") {
        $whrcls .= " and (a.customer_id IN (select c.cust_sup_id from 
scm.scm_cstmr_suplr c where c.cust_sup_name ilike '" . loc_db_escape_string($searchWord) .
                "'))";
    } else if ($searchIn == "Source Doc Number") {
        $whrcls .= " and (a.src_doc_hdr_id IN (select c.invc_hdr_id from scm.scm_sales_invc_hdr c where c.invc_number ilike '" . loc_db_escape_string($searchWord) .
                "') or scm.get_src_doc_num(a.other_mdls_doc_id, a.other_mdls_doc_type) ilike '" . loc_db_escape_string($searchWord) .
                "')";
    } else if ($searchIn == "Approval Status") {
        $whrcls .= " and (a.approval_status ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Created By") {
        $whrcls .= " and (sec.get_usr_name(a.created_by) ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Currency") {
        $whrcls .= " and (gst.get_pssbl_val(a.invc_curr_id) ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Classification") {
        $whrcls .= " and (a.invoice_clsfctn ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Branch") {
        $whrcls .= " and (org.get_site_code_desc(a.branch_id) ilike '" . loc_db_escape_string($searchWord) . "')";
    } else {
        $whrcls .= " and (org.get_site_code_desc(a.branch_id) ilike '" . loc_db_escape_string($searchWord) . "' "
                . "or gst.get_pssbl_val(a.invc_curr_id) ilike '" . loc_db_escape_string($searchWord) . "' "
                . "or sec.get_usr_name(a.created_by) ilike '" . loc_db_escape_string($searchWord) . "' "
                . "or a.approval_status ilike '" . loc_db_escape_string($searchWord) . "' "
                . "or a.invc_number ilike '" . loc_db_escape_string($searchWord) . "' or trim(to_char(a.invc_hdr_id, '99999999999999999999')) ilike '" . loc_db_escape_string($searchWord) .
                "' or a.comments_desc ilike '" . loc_db_escape_string($searchWord) . "' "
                . "or a.customer_id IN (select c.cust_sup_id from 
scm.scm_cstmr_suplr c where c.cust_sup_name ilike '" . loc_db_escape_string($searchWord) .
                "') or a.src_doc_hdr_id IN (select c.invc_hdr_id from scm.scm_sales_invc_hdr c where c.invc_number ilike '" . loc_db_escape_string($searchWord) .
                "') or scm.get_src_doc_num(a.other_mdls_doc_id, a.other_mdls_doc_type) ilike '" . loc_db_escape_string($searchWord) .
                "' or a.invoice_clsfctn ilike '" . loc_db_escape_string($searchWord) . "')";
    }
    $strSql = "SELECT a.invc_hdr_id, a.invc_number, a.invc_type,
        a.comments_desc,gst.get_pssbl_val(a.invc_curr_id), 
        round(scm.get_DocSmryGrndTtl(a.invc_hdr_id,a.invc_type),2) invoice_amount, 
        round(scm.get_DocSmryPymtRcvd(a.invc_hdr_id,a.invc_type),2) amnt_paid, 
        round(scm.get_DocSmryOutsbls(a.invc_hdr_id,a.invc_type),2) balance, 
        a.approval_status, scm.get_cstmr_splr_name(a.customer_id), 
        a.branch_id, org.get_site_code_desc(a.branch_id), a.invc_date, to_char(to_timestamp(a.invc_date,'YYYY-MM-DD'),'DD-Mon-YYYY') date
        FROM scm.scm_sales_invc_hdr a
        WHERE((a.org_id = " . $orgID . ") and (to_timestamp(a.invc_date,'YYYY-MM-DD') between to_timestamp('" . $qStrtDte .
            "','YYYY-MM-DD HH24:MI:SS') AND to_timestamp('" . $qEndDte . "','YYYY-MM-DD HH24:MI:SS'))" . $whrcls . $unpstdCls .
            ") ORDER BY a.invc_hdr_id DESC LIMIT " . $limit_size .
            " OFFSET " . (abs($offset * $limit_size));
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_Total_SalesDoc($searchWord, $searchIn, $orgID, $shwUnpstdOnly, $shwUnpaidOnly, $shwSelfOnly, $qShwMyBranch, $qStrtDte, $qEndDte) {
    global $vwOnlySelf;
    global $usrID;
    global $brnchLocID;
    global $vwOnlyBranch;
    if ($qStrtDte != "") {
        $qStrtDte = cnvrtDMYTmToYMDTm($qStrtDte);
    }
    if ($qEndDte != "") {
        $qEndDte = cnvrtDMYTmToYMDTm($qEndDte);
    }
    execUpdtInsSQL("DELETE FROM scm.scm_sales_invc_hdr a
                        WHERE approval_status = 'Not Validated'
                          AND (SELECT count(z.invc_hdr_id) FROM scm.scm_sales_invc_det z WHERE z.invc_hdr_id = a.invc_hdr_id) <= 0
                          AND (SELECT count(y.doc_hdr_id) FROM scm.scm_sales_doc_attchmnts y WHERE y.doc_hdr_id = a.invc_hdr_id) <= 0
                          AND coalesce((SELECT sum(x.smmry_amnt)
                                        FROM scm.scm_doc_amnt_smmrys x
                                        WHERE x.src_doc_hdr_id = a.invc_hdr_id
                                          AND x.src_doc_type = a.invc_type), 0) = 0
                          AND age(now(), to_timestamp(a.last_update_date, 'YYYY-MM-DD HH24:MI:SS')) >= INTERVAL '23 Hours'");

    $strSql = "";
    $whrcls = "";
    $unpstdCls = "";
    if ($shwUnpstdOnly) {
        $unpstdCls .= " AND (accb.is_gl_batch_pstd(scm.getSalesDocGlBatchID(a.invc_hdr_id,a.invc_type))!='1' and a.approval_status IN ('Approved','Cancelled'))";
    }
    if ($shwUnpaidOnly) {
        $unpstdCls .= " AND (round(scm.get_DocSmryOutsbls(a.invc_hdr_id,a.invc_type),2)>0 and a.approval_status IN ('Approved'))";
    }
    if ($vwOnlySelf === true || $shwSelfOnly === true) {
        $whrcls .= " and (a.created_by = " . $usrID . ")";
    }
    if ($vwOnlyBranch === true || $qShwMyBranch === true) {
        $whrcls .= " and (a.branch_id = " . $brnchLocID . ")";
    }
    if ($searchIn == "Document Number") {
        $whrcls .= " and (a.invc_number ilike '" . loc_db_escape_string($searchWord) . "' or trim(to_char(a.invc_hdr_id, '99999999999999999999')) ilike '" . loc_db_escape_string($searchWord) .
                "')";
    } else if ($searchIn == "Document Description") {
        $whrcls .= " and (a.comments_desc ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Customer Name") {
        $whrcls .= " and (a.customer_id IN (select c.cust_sup_id from 
scm.scm_cstmr_suplr c where c.cust_sup_name ilike '" . loc_db_escape_string($searchWord) .
                "'))";
    } else if ($searchIn == "Source Doc Number") {
        $whrcls .= " and (a.src_doc_hdr_id IN (select c.invc_hdr_id from scm.scm_sales_invc_hdr c where c.invc_number ilike '" . loc_db_escape_string($searchWord) .
                "') or scm.get_src_doc_num(a.other_mdls_doc_id, a.other_mdls_doc_type) ilike '" . loc_db_escape_string($searchWord) .
                "')";
    } else if ($searchIn == "Approval Status") {
        $whrcls .= " and (a.approval_status ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Created By") {
        $whrcls .= " and (sec.get_usr_name(a.created_by) ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Currency") {
        $whrcls .= " and (gst.get_pssbl_val(a.invc_curr_id) ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Classification") {
        $whrcls .= " and (a.invoice_clsfctn ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Branch") {
        $whrcls .= " and (org.get_site_code_desc(a.branch_id) ilike '" . loc_db_escape_string($searchWord) . "')";
    } else {
        $whrcls .= " and (org.get_site_code_desc(a.branch_id) ilike '" . loc_db_escape_string($searchWord) . "' "
                . "or gst.get_pssbl_val(a.invc_curr_id) ilike '" . loc_db_escape_string($searchWord) . "' "
                . "or sec.get_usr_name(a.created_by) ilike '" . loc_db_escape_string($searchWord) . "' "
                . "or a.approval_status ilike '" . loc_db_escape_string($searchWord) . "' "
                . "or a.invc_number ilike '" . loc_db_escape_string($searchWord) . "' or trim(to_char(a.invc_hdr_id, '99999999999999999999')) ilike '" . loc_db_escape_string($searchWord) .
                "' or a.comments_desc ilike '" . loc_db_escape_string($searchWord) . "' "
                . "or a.customer_id IN (select c.cust_sup_id from 
scm.scm_cstmr_suplr c where c.cust_sup_name ilike '" . loc_db_escape_string($searchWord) .
                "') or a.src_doc_hdr_id IN (select c.invc_hdr_id from scm.scm_sales_invc_hdr c where c.invc_number ilike '" . loc_db_escape_string($searchWord) .
                "') or scm.get_src_doc_num(a.other_mdls_doc_id, a.other_mdls_doc_type) ilike '" . loc_db_escape_string($searchWord) .
                "' or a.invoice_clsfctn ilike '" . loc_db_escape_string($searchWord) . "')";
    }
    $strSql = "SELECT count(1) 
        FROM scm.scm_sales_invc_hdr a 
        WHERE((a.org_id = " . $orgID . ") and (to_timestamp(a.invc_date,'YYYY-MM-DD') between to_timestamp('" . $qStrtDte .
            "','YYYY-MM-DD HH24:MI:SS') AND to_timestamp('" . $qEndDte . "','YYYY-MM-DD HH24:MI:SS'))" . $whrcls . $unpstdCls . ")";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return 0;
}

function get_DocSmryLns($dochdrID, $docTyp) {
    $strSql = "SELECT a.smmry_id, CASE WHEN a.smmry_type='3Discount' THEN 'Discount' ELSE a.smmry_name END, " .
            "a.smmry_amnt, a.code_id_behind, a.smmry_type, a.auto_calc, "
            . "REPLACE(REPLACE(a.smmry_type,'2Tax','3Tax'),'3Discount','2Discount') smtyp " .
            "FROM scm.scm_doc_amnt_smmrys a " .
            "WHERE((a.src_doc_hdr_id = " . $dochdrID .
            ") and (a.src_doc_type='" . loc_db_escape_string($docTyp) . "')) ORDER BY 7";
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_DfltSplrPyblsCashAcnt($spplrID, $orgID, $v_BranchID = -1) {
    global $brnchLocID;
    if ($v_BranchID <= 0) {
        $v_BranchID = $brnchLocID;
    }
    $strSql = "SELECT (CASE WHEN " . $spplrID . ">0 THEN "
            . "(SELECT org.get_accnt_id_brnch_eqv(" . $v_BranchID . ", dflt_pybl_accnt_id) FROM scm.scm_cstmr_suplr WHERE cust_sup_id=" . $spplrID . ") "
            . "ELSE (SELECT org.get_accnt_id_brnch_eqv(" . $v_BranchID . ", rcpt_lblty_acnt_id)) END) FROM scm.scm_dflt_accnts a " .
            "WHERE(a.org_id = " . $orgID . ")";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return ((int) $row[0]);
    }
    return -1;
}

function get_DfltCstmrRcvblsCashAcnt($spplrID, $orgID, $v_BranchID = -1) {
    global $brnchLocID;
    if ($v_BranchID <= 0) {
        $v_BranchID = $brnchLocID;
    }
    $strSql = "SELECT (CASE WHEN " . $spplrID . ">0 THEN (SELECT org.get_accnt_id_brnch_eqv(" . $v_BranchID . ", dflt_rcvbl_accnt_id) FROM scm.scm_cstmr_suplr WHERE cust_sup_id=" . $spplrID . ") "
            . "ELSE (SELECT org.get_accnt_id_brnch_eqv(" . $v_BranchID . ", sales_rcvbl_acnt_id)) END) " .
            "FROM scm.scm_dflt_accnts a " .
            "WHERE(a.org_id = " . $orgID . ")";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return ((int) $row[0]);
    }
    return -1;
}

function get_DfltCstmrSpplrSiteID($spplrID) {
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

function get_CstmrSpplrSiteLnkID($spplrID, $siteID) {
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

function getLnkdPrsnCstmrSpplrID($lnkdprsnid) {
    $sqlStr = "select cust_sup_id from scm.scm_cstmr_suplr where lnkd_prsn_id=" . $lnkdprsnid;
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -1;
}

function createCstmrLnkdPrsn($cstmrNm, $cstmrDesc, $clsfctn, $cstrmOrSpplr, $orgid, $dfltPyblAcnt, $dfltRcvblAcnt, $lnkdPrsn, $gender, $dob,
        $isenbled, $frmBrndNm, $orgType, $cmpnyRegNum, $dateIncorp, $typeOfIncorp, $vatNum, $tinNum, $ssnitNum, $numEmplys, $descSrvcs,
        $listSrvcs) {
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

function createCstmrSiteLnkdPrsn($cstmrID, $cntctPrsn, $cntctNos, $email, $siteNm, $siteDesc, $bankNm, $bankBrnch, $bnkNum, $wthTaxID,
        $dscntCodeID, $bllngAddrs, $shpToAddrs, $swftCode, $natnlty, $ntnltyIDType, $idNum, $dateIssued, $expryDate, $otherInfo, $isenabled,
        $ibanNum, $accntCurID) {
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

function getCstmrSpplrName1($cstmrid) {
    $sqlStr = "select cust_sup_name from scm.scm_cstmr_suplr where cust_sup_id=" . $cstmrid;
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return "";
}

function getCstmrSiteNm($siteID, $cstmrID) {
    $sqlStr = "select site_name from scm.scm_cstmr_suplr_sites where cust_supplier_id = " . loc_db_escape_string($cstmrID) .
            " and cust_sup_site_id = " . loc_db_escape_string(strtolower($siteID)) . "";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return "";
}

function get_One_SalesInvcDocHdr($hdrID) {
    $strSql = "SELECT a.invc_hdr_id, to_char(to_timestamp(a.invc_date,'YYYY-MM-DD'),'DD-Mon-YYYY'), 
       created_by, sec.get_usr_name(a.created_by), a.invc_number, a.invc_type, 
       a.comments_desc, a.src_doc_hdr_id, a.customer_id, scm.get_cstmr_splr_name(a.customer_id),
       a.customer_site_id, scm.get_cstmr_splr_site_name(a.customer_site_id), 
       a.approval_status, a.next_aproval_action, round(scm.get_DocSmryGrndTtl(a.invc_hdr_id,a.invc_type),2) invoice_amount, 
       a.payment_terms, a.src_doc_type, a.pymny_method_id, accb.get_pymnt_mthd_name(a.pymny_method_id), 
       round(scm.get_DocSmryPymtRcvd(a.invc_hdr_id,a.invc_type),2) amnt_paid, 
       scm.getSalesDocGlBatchID(a.invc_hdr_id,a.invc_type) gl_batch_id, accb.get_gl_batch_name(scm.getSalesDocGlBatchID(a.invc_hdr_id,a.invc_type)),
       '' cstmrs_doc_num, '' doc_tmplt_clsfctn, a.invc_curr_id, gst.get_pssbl_val(a.invc_curr_id),
       a.event_rgstr_id, a.evnt_cost_category, a.event_doc_type, a.receivables_accnt_id,
       accb.get_accnt_num(a.receivables_accnt_id) || '.' || accb.get_accnt_name(a.receivables_accnt_id) balancing_accnt,
       accb.is_gl_batch_pstd(scm.getSalesDocGlBatchID(a.invc_hdr_id,a.invc_type)) is_pstd, 
       other_mdls_doc_id, other_mdls_doc_type, 0 invc_amnt_appld_elswhr, 
       scm.getSalesDocDebtGlBatchID(a.invc_hdr_id,a.invc_type) debt_gl_batch_id, accb.get_gl_batch_name(scm.getSalesDocDebtGlBatchID(a.invc_hdr_id,a.invc_type)), 
       scm.get_src_doc_num(a.src_doc_hdr_id, a.src_doc_type), a.allow_dues, a.enbl_auto_misc_chrges, 
       a.exchng_rate,scm.get_src_doc_num(a.other_mdls_doc_id,a.other_mdls_doc_type) doc_no, 
       scm.getSalesDocRcvblID(a.invc_hdr_id,a.invc_type) rcvblsInvcID,
       scm.get_ScmRcvblsDocHdrNum(a.invc_hdr_id,a.invc_type,a.org_id), 
       accb.get_src_doc_type(scm.getSalesDocRcvblID(a.invc_hdr_id,a.invc_type),'Customer'), 
       a.branch_id, org.get_site_code_desc(a.branch_id), invoice_clsfctn, 
       mspy_item_set_id, pay.get_itm_st_name(mspy_item_set_id), mspy_amnt_gvn, 
       cheque_card_num, sign_code, mspy_apply_advnc, mspy_keep_excess
  FROM scm.scm_sales_invc_hdr a
  WHERE ((a.invc_hdr_id = " . $hdrID . "))";
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_One_SalesInvcAmounts($hdrID) {
    $strSql = "SELECT round(scm.get_DocSmryGrndTtl(a.invc_hdr_id,a.invc_type),2) invoice_amount, 
       round(scm.get_DocSmryPymtRcvd(a.invc_hdr_id,a.invc_type),2) amnt_paid
  FROM scm.scm_sales_invc_hdr a
  WHERE ((a.invc_hdr_id = " . $hdrID . "))";
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_One_PrchsDocAmounts($hdrID) {
    $strSql = "SELECT round(scm.getprchsdocgrndamnt(a.prchs_doc_hdr_id),2) invoice_amount, 
       0 amnt_paid
  FROM scm.scm_prchs_docs_hdr a
  WHERE ((a.prchs_doc_hdr_id = " . $hdrID . "))";
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_SalesInvcDocDet($dochdrID) {
    $strSql = "SELECT a.invc_det_ln_id, a.itm_id, " .
            "a.doc_qty, a.unit_selling_price, (a.doc_qty * a.unit_selling_price) amnt, " .
            "a.store_id, a.crncy_id, (a.doc_qty - a.qty_trnsctd_in_dest_doc) avlbl_qty, " .
            "a.src_line_id, a.tax_code_id, a.dscnt_code_id, a.chrg_code_id, a.rtrn_reason, " .
            "a.consgmnt_ids, a.orgnl_selling_price, b.base_uom_id, b.item_code, b.item_desc, 
      c.uom_name, a.is_itm_delivered, REPLACE(a.extra_desc || ' (' || a.other_mdls_doc_type || ')',' ()','')
        , a.other_mdls_doc_id, a.other_mdls_doc_type, a.lnkd_person_id, 
      REPLACE(prs.get_prsn_surname(a.lnkd_person_id) || ' (' 
      || prs.get_prsn_loc_id(a.lnkd_person_id) || ')', ' ()', '') fullnm, 
      CASE WHEN a.alternate_item_name='' THEN b.item_desc ELSE a.alternate_item_name END, d.cat_name,
        REPLACE(a.inv_asset_acct_id || ',' || a.cogs_acct_id || ',' || a.sales_rev_accnt_id || ',' || a.sales_ret_accnt_id || ',' || a.purch_ret_accnt_id || ',' || a.expense_accnt_id,
'-1,-1,-1,-1,-1,-1', b.inv_asset_acct_id || ',' || b.cogs_acct_id || ',' || b.sales_rev_accnt_id || ',' || b.sales_ret_accnt_id || ',' || b.purch_ret_accnt_id || ',' || b.expense_accnt_id) itm_accnts,
      b.item_type,a.rented_itm_qty, a.other_mdls_doc_id, a.other_mdls_doc_type, 
        scm.get_tax_code(a.tax_code_id), scm.get_tax_code(a.dscnt_code_id), scm.get_tax_code(a.chrg_code_id) 
        FROM scm.scm_sales_invc_det a, inv.inv_itm_list b, inv.unit_of_measure c, inv.inv_product_categories d  
        WHERE(a.invc_hdr_id = " . $dochdrID .
            " and a.invc_hdr_id>0 and a.itm_id = b.item_id and b.base_uom_id = c.uom_id and d.cat_id = b.category_id) ORDER BY a.invc_det_ln_id, b.category_id";

    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_SalesInvcDocDet2($dochdrID) {
    $strSql = "SELECT a.invc_det_ln_id, a.itm_id, " .
            "a.doc_qty, a.unit_selling_price, (a.doc_qty * a.unit_selling_price * a.rented_itm_qty) amnt, " .
            "a.store_id, a.crncy_id, (a.doc_qty - a.qty_trnsctd_in_dest_doc) avlbl_qty, " .
            "a.src_line_id, a.tax_code_id, a.dscnt_code_id, a.chrg_code_id, a.rtrn_reason, " .
            "a.consgmnt_ids, a.orgnl_selling_price, b.base_uom_id, b.item_code, b.item_desc, 
      c.uom_name, a.is_itm_delivered, REPLACE(a.extra_desc || ' (' || a.other_mdls_doc_type || ')',' ()','')
        , a.other_mdls_doc_id, a.other_mdls_doc_type, a.lnkd_person_id, 
      REPLACE(prs.get_prsn_surname(a.lnkd_person_id) || ' (' 
      || prs.get_prsn_loc_id(a.lnkd_person_id) || ')', ' ()', '') fullnm, 
      CASE WHEN a.alternate_item_name='' THEN b.item_desc ELSE a.alternate_item_name END, d.cat_name,
        REPLACE(a.inv_asset_acct_id || ',' || a.cogs_acct_id || ',' || a.sales_rev_accnt_id || ',' || a.sales_ret_accnt_id || ',' || a.purch_ret_accnt_id || ',' || a.expense_accnt_id,
       '-1,-1,-1,-1,-1,-1', b.inv_asset_acct_id || ',' || b.cogs_acct_id || ',' || b.sales_rev_accnt_id || ',' || b.sales_ret_accnt_id || ',' || b.purch_ret_accnt_id || ',' || b.expense_accnt_id) itm_accnts,
      b.item_type,a.rented_itm_qty, a.other_mdls_doc_id, a.other_mdls_doc_type, 
        scm.get_tax_code(a.tax_code_id), scm.get_tax_code(a.dscnt_code_id), scm.get_tax_code(a.chrg_code_id)  
      FROM scm.scm_sales_invc_det a, inv.inv_itm_list b, inv.unit_of_measure c, inv.inv_product_categories d  
        WHERE(a.invc_hdr_id = " . $dochdrID . " and a.invc_hdr_id>0 and a.itm_id = b.item_id and b.base_uom_id = c.uom_id and d.cat_id = b.category_id)"
            . " ORDER BY a.invc_det_ln_id, b.category_id";
    //echo $strSql;
    $result = executeSQLNoParams($strSql);
    return $result;
}

function createSmmryItm($smmryTyp, $smmryNm, $amnt, $codeBehind, $srcDocTyp, $srcDocHdrID, $autoCalc) {
    global $usrID;
    if ($smmryTyp == "3Discount") {
        $amnt = -1 * abs($amnt);
    }
    $insSQL = "INSERT INTO scm.scm_doc_amnt_smmrys(" .
            "smmry_type, smmry_name, smmry_amnt, code_id_behind, " .
            "src_doc_type, src_doc_hdr_id, created_by, creation_date, last_update_by, " .
            "last_update_date, auto_calc) " .
            "VALUES ('" . loc_db_escape_string($smmryTyp) .
            "', '" . loc_db_escape_string($smmryNm) .
            "', " . $amnt . ", " . $codeBehind . ", '" . loc_db_escape_string($srcDocTyp) .
            "', " . $srcDocHdrID . ", " . $usrID . ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), " . $usrID . ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), '" .
            cnvrtBoolToBitStr($autoCalc) . "')";
    return execUpdtInsSQL($insSQL);
}

function updateSmmryItm($smmryID, $smmryTyp, $amnt, $autoCalc, $smmryNm) {
    global $usrID;
    if ($smmryTyp == "3Discount") {
        $amnt = -1 * abs($amnt);
    }
    $updtSQL = "UPDATE scm.scm_doc_amnt_smmrys SET " .
            "smmry_amnt = " . $amnt .
            ", last_update_by = " . $usrID . ", " .
            "auto_calc = '" . cnvrtBoolToBitStr($autoCalc) .
            "', last_update_date = to_char(now(),'YYYY-MM-DD HH24:MI:SS'), smmry_name='" . loc_db_escape_string($smmryNm) . "' WHERE (smmry_id = " . $smmryID . ")";

    return execUpdtInsSQL($updtSQL);
}

function createSalesDocHdr($orgid, $docNum, $desc, $docTyp, $docdte, $pymntTrms, $cstmrID, $siteID, $apprvlSts, $nxtApprvl, $srcDocID,
        $rcvblAcntID, $pymntID, $invcCurrID, $exchRate, $chckInID, $chckInType, $enblAutoChrg, $event_rgstr_id, $evntCtgry, $allwDues,
        $evntType, $src_doc_type, $brnchID = -1, $scmSalesInvcClssfctn = "Standard", $scmSalesInvcPyItmSetID = -1,
        $scmSalesInvcPyAmntGvn = 0, $scmSalesInvcPyChqNumber = "", $scmSalesInvcPySignCode = "", $scmSalesInvcAplyAdvnc = "1",
        $scmSalesInvcKeepExcss = "1") {
    global $usrID;
    global $brnchLocID;
    if ($brnchID <= 0) {
        $brnchID = $brnchLocID;
    }
    if ($docdte != "") {
        $docdte = cnvrtDMYToYMD($docdte);
    }
    $insSQL = "INSERT INTO scm.scm_sales_invc_hdr(" .
            "invc_date, payment_terms, customer_id, " .
            "customer_site_id, comments_desc, approval_status, created_by, " .
            "creation_date, last_update_by, last_update_date, next_aproval_action, " .
            "invc_number, invc_type, src_doc_hdr_id, org_id, receivables_accnt_id, " .
            "pymny_method_id, invc_curr_id, exchng_rate, " .
            "other_mdls_doc_id, other_mdls_doc_type, enbl_auto_misc_chrges, " .
            "event_rgstr_id, evnt_cost_category, allow_dues, event_doc_type, " .
            "src_doc_type, branch_id,invoice_clsfctn, mspy_amnt_gvn, mspy_item_set_id, "
            . "cheque_card_num, sign_code, mspy_apply_advnc, mspy_keep_excess) " .
            "VALUES ('" . loc_db_escape_string($docdte) .
            "', '" . loc_db_escape_string($pymntTrms) .
            "', " . $cstmrID . ", " . $siteID . ", '" . loc_db_escape_string($desc) .
            "', '" . loc_db_escape_string($apprvlSts) . "', " . $usrID .
            ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), " . $usrID .
            ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), '" . loc_db_escape_string($nxtApprvl) .
            "', '" . loc_db_escape_string($docNum) . "', '"
            . loc_db_escape_string($docTyp) . "', " . $srcDocID . ", " .
            $orgid . ", " . $rcvblAcntID . ", " . $pymntID . ", "
            . $invcCurrID . ", " . $exchRate . "," . $chckInID . ",'" . $chckInType .
            "','" . cnvrtBoolToBitStr($enblAutoChrg) .
            "'," . $event_rgstr_id . ", '" . loc_db_escape_string($evntCtgry) .
            "','" . cnvrtBoolToBitStr($allwDues) .
            "', '" . loc_db_escape_string($evntType) .
            "','" . loc_db_escape_string($src_doc_type) . "'," . $brnchID .
            ", '" . loc_db_escape_string($scmSalesInvcClssfctn) .
            "'," . $scmSalesInvcPyAmntGvn . "," . $scmSalesInvcPyItmSetID .
            ",'" . loc_db_escape_string($scmSalesInvcPyChqNumber) .
            "','" . loc_db_escape_string($scmSalesInvcPySignCode) .
            "','" . loc_db_escape_string($scmSalesInvcAplyAdvnc) .
            "','" . loc_db_escape_string($scmSalesInvcKeepExcss) . "')";
    //echo $insSQL;
    execUpdtInsSQL($insSQL);
    $sbmtdScmSalesInvcID = getGnrlRecID("scm.scm_sales_invc_hdr", "invc_number", "invc_hdr_id", $docNum, $orgid);
    if ($srcDocID > 0 && $sbmtdScmSalesInvcID > 0) {
        $insSQL = "INSERT INTO scm.scm_sales_invc_det(invc_hdr_id, itm_id, store_id, doc_qty, unit_selling_price,
                                   tax_code_id, created_by, creation_date, last_update_by, last_update_date,
                                   dscnt_code_id, chrg_code_id, src_line_id, qty_trnsctd_in_dest_doc, crncy_id,
                                   rtrn_reason, consgmnt_ids, cnsgmnt_qty_dist, orgnl_selling_price, is_itm_delivered,
                                   other_mdls_doc_id, other_mdls_doc_type, extra_desc, lnkd_person_id,
                                   alternate_item_name, rented_itm_qty, cogs_acct_id, sales_rev_accnt_id,
                                   sales_ret_accnt_id, purch_ret_accnt_id, expense_accnt_id, inv_asset_acct_id) 
                 select " . $sbmtdScmSalesInvcID . ", c.itm_id,c.store_id, (c.doc_qty-coalesce(c.qty_trnsctd_in_dest_doc,0)),c.unit_selling_price,c.tax_code_id, " .
                $usrID . ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), " . $usrID . ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), c.dscnt_code_id ,c.chrg_code_id, 
                    c.invc_det_ln_id, 0,c.crncy_id,c.rtrn_reason,'','',c.orgnl_selling_price,'0',-1,'','',-1,c.alternate_item_name,
                    c.rented_itm_qty,c.cogs_acct_id,c.sales_rev_accnt_id,c.sales_ret_accnt_id,
                    c.purch_ret_accnt_id, c.expense_accnt_id, c.inv_asset_acct_id
                from scm.scm_sales_invc_det c where (c.invc_hdr_id = " . $srcDocID . " and (c.doc_qty-coalesce(c.qty_trnsctd_in_dest_doc,0))>0) ORDER BY c.invc_det_ln_id";
//echo $insSQL;
        execUpdtInsSQL($insSQL);
    }
    return $sbmtdScmSalesInvcID;
}

function updtSalesDocHdr($docid, $docNum, $desc, $docTyp, $docdte, $pymntTerms, $spplrID, $spplrSiteID, $apprvlSts, $nxtApprvl, $srcDocID,
        $rcvblAcntID, $pymntID, $invcCurrID, $exchRate, $chckInID, $chckInType, $enblAutoChrg, $event_rgstr_id, $evntCtgry, $allwDues,
        $evntType, $src_doc_type, $brnchID = -1, $scmSalesInvcClssfctn = "Standard", $scmSalesInvcPyItmSetID = -1,
        $scmSalesInvcPyAmntGvn = 0, $scmSalesInvcPyChqNumber = "", $scmSalesInvcPySignCode = "", $scmSalesInvcAplyAdvnc = "0",
        $scmSalesInvcKeepExcss = "1") {
    global $usrID;
    global $brnchLocID;
    if ($brnchID <= 0) {
        $brnchID = $brnchLocID;
    }
    if ($docdte != "") {
        $docdte = cnvrtDMYToYMD($docdte);
    }
    $updtSQL = "UPDATE scm.scm_sales_invc_hdr SET " .
            "invc_date='" . loc_db_escape_string($docdte) .
            "', payment_terms='" . loc_db_escape_string($pymntTerms) .
            "', customer_id=" . $spplrID . ", " .
            "customer_site_id=" . $spplrSiteID .
            ", comments_desc='" . loc_db_escape_string($desc) .
            "', approval_status='" . loc_db_escape_string($apprvlSts) .
            "', last_update_by=" . $usrID .
            ", last_update_date = to_char(now(),'YYYY-MM-DD HH24:MI:SS'), next_aproval_action = '" . loc_db_escape_string($nxtApprvl) .
            "', invc_number = '" . loc_db_escape_string($docNum) .
            "', invc_type = '" . loc_db_escape_string($docTyp) . "', src_doc_hdr_id=" . $srcDocID .
            ", pymny_method_id = " . $pymntID . ", invc_curr_id=" . $invcCurrID .
            ", exchng_rate=" . $exchRate .
            ", other_mdls_doc_id=" . $chckInID .
            ", other_mdls_doc_type='" . loc_db_escape_string($chckInType) . "' " .
            ", enbl_auto_misc_chrges='" . cnvrtBoolToBitStr($enblAutoChrg) . "' " .
            ", event_rgstr_id = " . $event_rgstr_id .
            ", evnt_cost_category = '" . loc_db_escape_string($evntCtgry) . "' " .
            ", allow_dues = '" . cnvrtBoolToBitStr($allwDues) .
            "', event_doc_type = '" . loc_db_escape_string($evntType) . "', receivables_accnt_id= " . $rcvblAcntID .
            ", src_doc_type = '" . loc_db_escape_string($src_doc_type) . "', branch_id=" . $brnchID .
            ", invoice_clsfctn = '" . loc_db_escape_string($scmSalesInvcClssfctn) .
            "', mspy_amnt_gvn=" . $scmSalesInvcPyAmntGvn .
            ", mspy_item_set_id=" . $scmSalesInvcPyItmSetID .
            ", cheque_card_num='" . loc_db_escape_string($scmSalesInvcPyChqNumber) .
            "', sign_code='" . loc_db_escape_string($scmSalesInvcPySignCode) .
            "', mspy_apply_advnc='" . loc_db_escape_string($scmSalesInvcAplyAdvnc) .
            "', mspy_keep_excess='" . loc_db_escape_string($scmSalesInvcKeepExcss) .
            "' WHERE (invc_hdr_id = " . $docid . ")";
    return execUpdtInsSQL($updtSQL);
}

function getNewSalesInvcLnID() {
    $strSql = "select nextval('scm.scm_itm_sales_ordrs_det_trnstn_id_seq')";
    $result = executeSQLNoParams($strSql);

    if (loc_db_num_rows($result) > 0) {
        $row = loc_db_fetch_array($result);
        return $row[0];
    }
    return -1;
}

function createSalesDocLn($lineid, $docID, $itmID, $qty, $untPrice, $storeID, $crncyID, $srclnID, $txCode, $dscntCde, $chrgeCde, $rtrnRsn,
        $cnsgmntIDs, $orgnlPrice, $dlvrd, $prsnID, $altrntNm, $invAstAcntID, $cogsID, $salesRevID, $salesRetID, $purcRetID, $expnsID) {
    global $usrID;
    global $canEdtItmPrice;
    $untPriceCls = $untPrice;
    if ($canEdtItmPrice === false) {
        $untPriceCls = "scm.get_item_unit_sllng_price(" . $itmID . ", " . $qty . ")";
    }
    $insSQL = "INSERT INTO scm.scm_sales_invc_det(invc_det_ln_id, " .
            "invc_hdr_id, itm_id, doc_qty, unit_selling_price, " .
            "created_by, creation_date, last_update_by, last_update_date, " .
            "store_id, crncy_id, src_line_id, tax_code_id, " .
            "dscnt_code_id, chrg_code_id, qty_trnsctd_in_dest_doc,
  rtrn_reason, consgmnt_ids, orgnl_selling_price,is_itm_delivered, lnkd_person_id, alternate_item_name,
  cogs_acct_id, sales_rev_accnt_id, sales_ret_accnt_id, purch_ret_accnt_id, expense_accnt_id, inv_asset_acct_id) " .
            "VALUES (" . $lineid .
            "," . $docID .
            ", " . $itmID .
            ", " . $qty . ", " . $untPriceCls . ", " . $usrID .
            ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), " . $usrID .
            ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), " . $storeID .
            ", " . $crncyID . ", " . $srclnID . ", " . $txCode .
            ", " . $dscntCde . ", " . $chrgeCde . ", 0, '" .
            loc_db_escape_string($rtrnRsn) . "', '" . loc_db_escape_string($cnsgmntIDs) .
            "', scm.get_sllng_price_lesstax(" . $txCode . "," . $untPriceCls . "), '" . cnvrtBoolToBitStr($dlvrd) . "', " . $prsnID .
            ",'" . loc_db_escape_string($altrntNm) . "'," . $cogsID .
            "," . $salesRevID .
            "," . $salesRetID .
            "," . $purcRetID .
            "," . $expnsID .
            "," . $invAstAcntID .
            ")";
    return execUpdtInsSQL($insSQL);
}

function updateSalesDocLn($lnID, $itmID, $qty, $untPrice, $storeID, $crncyID, $srclnID, $txCode, $dscntCde, $chrgeCde, $rtrnRsn,
        $cnsgmntIDs, $orgnlPrice, $dlvrd, $prsnID, $altrntNm, $invAstAcntID, $cogsID, $salesRevID, $salesRetID, $purcRetID, $expnsID) {
    global $usrID;
    global $canEdtItmPrice;
    $untPriceCls = $untPrice;
    if ($canEdtItmPrice === false) {
        $untPriceCls = "scm.get_item_unit_sllng_price(" . $itmID . ", " . $qty . ")";
    }
    $updtSQL = "UPDATE scm.scm_sales_invc_det SET " .
            "itm_id=" . $itmID .
            ", doc_qty =" . $qty .
            ", unit_selling_price= " . $untPriceCls .
            ", orgnl_selling_price= scm.get_sllng_price_lesstax(" . $txCode . "," . $untPriceCls . "), " .
            "last_update_by = " . $usrID .
            ", last_update_date= to_char(now(),'YYYY-MM-DD HH24:MI:SS'), " .
            "store_id=" . $storeID .
            ", crncy_id =" . $crncyID . ", src_line_id = " . $srclnID .
            ", tax_code_id = " . $txCode .
            ", dscnt_code_id = " . $dscntCde .
            ", chrg_code_id = " . $chrgeCde .
            ", rtrn_reason = '" . loc_db_escape_string($rtrnRsn) .
            "', consgmnt_ids ='" . loc_db_escape_string($cnsgmntIDs) .
            "', is_itm_delivered ='" . cnvrtBoolToBitStr($dlvrd) .
            "', lnkd_person_id = " . $prsnID .
            ", alternate_item_name = '" . loc_db_escape_string($altrntNm) .
            "', cogs_acct_id=" . $cogsID .
            ", sales_rev_accnt_id=" . $salesRevID .
            ", sales_ret_accnt_id =" . $salesRetID .
            ", purch_ret_accnt_id =" . $purcRetID .
            ", expense_accnt_id =" . $expnsID .
            ", inv_asset_acct_id =" . $invAstAcntID .
            " WHERE (invc_det_ln_id = " . $lnID . ")";
    return execUpdtInsSQL($updtSQL);
}

function createSalesDocLn1($lineid, $docID, $itmID, $qty, $untPrice, $storeID, $crncyID, $srclnID, $txCode, $dscntCde, $chrgeCde, $rtrnRsn,
        $cnsgmntIDs, $orgnlPrice, $dlvrd, $prsnID, $altrntNm, $invAstAcntID, $cogsID, $salesRevID, $salesRetID, $purcRetID, $expnsID,
        $rented_itm_qty = 1, $other_mdls_doc_id = -1, $other_mdls_doc_type = "", $extra_desc = "") {
    global $usrID;
    global $canEdtItmPrice;
    $untPriceCls = $untPrice;
    if ($canEdtItmPrice === false) {
        $untPriceCls = "scm.get_item_unit_sllng_price(" . $itmID . ", " . $qty . ")";
    }
    if (strlen($extra_desc) > 300) {
        $extra_desc = substr($extra_desc, 0, 300);
    }
    $insSQL = "INSERT INTO scm.scm_sales_invc_det(invc_det_ln_id, " .
            "invc_hdr_id, itm_id, doc_qty, unit_selling_price, " .
            "created_by, creation_date, last_update_by, last_update_date, " .
            "store_id, crncy_id, src_line_id, tax_code_id, " .
            "dscnt_code_id, chrg_code_id, qty_trnsctd_in_dest_doc, 
      rtrn_reason, consgmnt_ids, orgnl_selling_price,is_itm_delivered, lnkd_person_id, alternate_item_name,  
            cogs_acct_id, sales_rev_accnt_id, sales_ret_accnt_id, purch_ret_accnt_id, expense_accnt_id, 
            inv_asset_acct_id, rented_itm_qty, other_mdls_doc_id, other_mdls_doc_type, extra_desc) " .
            "VALUES (" . $lineid .
            "," . $docID .
            ", " . $itmID .
            ", " . $qty . ", " . $untPriceCls . ", " . $usrID .
            ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), " . $usrID .
            ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), " . $storeID .
            ", " . $crncyID . ", " . $srclnID . ", " . $txCode .
            ", " . $dscntCde . ", " . $chrgeCde . ", 0, '" .
            loc_db_escape_string($rtrnRsn) . "', '" . loc_db_escape_string($cnsgmntIDs) .
            "', scm.get_sllng_price_lesstax(" . $txCode . "," . $untPriceCls . "), '" . cnvrtBoolToBitStr($dlvrd) . "', " . $prsnID .
            ",'" . loc_db_escape_string($altrntNm) . "'," . $cogsID .
            "," . $salesRevID .
            "," . $salesRetID .
            "," . $purcRetID .
            "," . $expnsID .
            "," . $invAstAcntID .
            "," . $rented_itm_qty .
            "," . $other_mdls_doc_id .
            ",'" . loc_db_escape_string($other_mdls_doc_type) . "','" . loc_db_escape_string($extra_desc) . "')";
			
	    /*CLINIC/HOSPITAL*/
        $chckInID = getInvcHdrChckInID($docID);
        $admsn_id = -1;
        if($chckInID > 0){
            $admsn_id = getGnrlRecNm("hosp.inpatient_admissions", "ref_check_in_id", "admsn_id", $chckInID);
            if($admsn_id == ""){
                 $admsn_id = -1;
            }
        }

        if($admsn_id > 0){
            $appntmntID = (int)getGnrlRecNm("hosp.inpatient_admissions", "admsn_id", "dest_appntmnt_id", $admsn_id);
            //$appntmntID = getGnrlRecNm("hosp.medcl_cnsltn", "cnsltn_id", "appntmnt_id", $cnsltnID);
            //$srvs_type_id = getGnrlRecNm("hosp.appntmnt", "appntmnt_id", "srvs_type_id", $appntmntID); 
            $dateStr = getDB_Date_time();
            $ttlQty = $qty;
                $appntmntDataItemsID = getAppntmntDataItemsIDHotl();
                $uomID = "(SELECT inv.get_invitm_uom_id($itmID))";
                $isBilled = "Y";
                insertAppntmntDataItemsAllCols($appntmntDataItemsID, $appntmntID, $itmID, $ttlQty, $docID, $lineid, $isBilled, $usrID, $dateStr, $uomID, -1);
        }
        /*CLINIC/HOSPITAL*/
			
    return execUpdtInsSQL($insSQL);
}

function updateSalesDocLn1($lnID, $itmID, $qty, $untPrice, $storeID, $crncyID, $srclnID, $txCode, $dscntCde, $chrgeCde, $rtrnRsn,
        $cnsgmntIDs, $orgnlPrice, $dlvrd, $prsnID, $altrntNm, $invAstAcntID, $cogsID, $salesRevID, $salesRetID, $purcRetID, $expnsID,
        $rented_itm_qty = 1, $other_mdls_doc_id = -1, $other_mdls_doc_type = "", $extra_desc = "") {
    global $usrID;
    global $canEdtItmPrice;
    $untPriceCls = $untPrice;
    if ($canEdtItmPrice === false) {
        $untPriceCls = "scm.get_item_unit_sllng_price(" . $itmID . ", " . $qty . ")";
    }
    if (strlen($extra_desc) > 300) {
        $extra_desc = substr($extra_desc, 0, 300);
    }
    $updtSQL = "UPDATE scm.scm_sales_invc_det SET " .
            "itm_id=" . $itmID .
            ", doc_qty =" . $qty .
            ", unit_selling_price= " . $untPriceCls .
            ", orgnl_selling_price= scm.get_sllng_price_lesstax(" . $txCode . "," . $untPriceCls . "), " .
            "last_update_by = " . $usrID .
            ", last_update_date= to_char(now(),'YYYY-MM-DD HH24:MI:SS'), " .
            "store_id=" . $storeID .
            ", crncy_id =" . $crncyID . ", src_line_id = " . $srclnID .
            ", tax_code_id = " . $txCode .
            ", dscnt_code_id = " . $dscntCde .
            ", chrg_code_id = " . $chrgeCde .
            ", rtrn_reason = '" . loc_db_escape_string($rtrnRsn) .
            "', consgmnt_ids ='" . loc_db_escape_string($cnsgmntIDs) .
            "', is_itm_delivered ='" . cnvrtBoolToBitStr($dlvrd) .
            "', lnkd_person_id = " . $prsnID .
            ", alternate_item_name = '" . loc_db_escape_string($altrntNm) .
            "', cogs_acct_id=" . $cogsID .
            ", sales_rev_accnt_id=" . $salesRevID .
            ", sales_ret_accnt_id =" . $salesRetID .
            ", purch_ret_accnt_id =" . $purcRetID .
            ", expense_accnt_id =" . $expnsID .
            ", inv_asset_acct_id =" . $invAstAcntID .
            ", rented_itm_qty=" . $rented_itm_qty .
            ", other_mdls_doc_id=" . $other_mdls_doc_id .
            ", other_mdls_doc_type='" . loc_db_escape_string($other_mdls_doc_type) .
            "', extra_desc='" . loc_db_escape_string($extra_desc) . "' WHERE (invc_det_ln_id = " . $lnID . ")";
			
	    /*CLINIC/HOSPITAL*/   
        $docID = (int)getGnrlRecNm("scm.scm_sales_invc_det", "invc_det_ln_id", "invc_hdr_id", $lnID);
        $chckInID = getInvcHdrChckInID($docID);
        $admsn_id = -1;
        if($chckInID > 0){
            $admsn_id = getGnrlRecNm("hosp.inpatient_admissions", "ref_check_in_id", "admsn_id", $chckInID);
            if($admsn_id == ""){
                 $admsn_id = -1;
            }
            
             if($admsn_id > 0){
                 execUpdtInsSQL("UPDATE hosp.appntmnt_data_items SET qty = $qty WHERE invc_hdr_id = $docID AND invc_det_ln_id = $lnID AND invc_hdr_id > 0 AND invc_det_ln_id > 0");
             }
        }
        /*CLINIC/HOSPITAL*/
	
    return execUpdtInsSQL($updtSQL);
}

function createSalesDocLn2($lineid, $docID, $itmID, $qty, $untPrice, $storeID, $crncyID, $srclnID, $txCode, $dscntCde, $chrgeCde, $rtrnRsn,
        $cnsgmntIDs, $orgnlPrice, $dlvrd, $prsnID, $altrntNm, $invAstAcntID, $cogsID, $salesRevID, $salesRetID, $purcRetID, $expnsID,
        $rented_itm_qty = 1, $other_mdls_doc_id = -1, $other_mdls_doc_type = "", $extra_desc = "") {
    global $usrID;
    global $canEdtItmPrice;
    $untPriceCls = $untPrice;
    /* if ($canEdtItmPrice === false) {
      $untPriceCls = "scm.get_item_unit_sllng_price(" . $itmID . ", " . $qty . ")";
      } */
    if (strlen($extra_desc) > 300) {
        $extra_desc = substr($extra_desc, 0, 300);
    }
    $insSQL = "INSERT INTO scm.scm_sales_invc_det(invc_det_ln_id, " .
            "invc_hdr_id, itm_id, doc_qty, unit_selling_price, " .
            "created_by, creation_date, last_update_by, last_update_date, " .
            "store_id, crncy_id, src_line_id, tax_code_id, " .
            "dscnt_code_id, chrg_code_id, qty_trnsctd_in_dest_doc, 
      rtrn_reason, consgmnt_ids, orgnl_selling_price,is_itm_delivered, lnkd_person_id, alternate_item_name,  
            cogs_acct_id, sales_rev_accnt_id, sales_ret_accnt_id, purch_ret_accnt_id, expense_accnt_id, 
            inv_asset_acct_id, rented_itm_qty, other_mdls_doc_id, other_mdls_doc_type, extra_desc) " .
            "VALUES (" . $lineid .
            "," . $docID .
            ", " . $itmID .
            ", " . $qty . ", " . $untPriceCls . ", " . $usrID .
            ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), " . $usrID .
            ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), " . $storeID .
            ", " . $crncyID . ", " . $srclnID . ", " . $txCode .
            ", " . $dscntCde . ", " . $chrgeCde . ", 0, '" .
            loc_db_escape_string($rtrnRsn) . "', '" . loc_db_escape_string($cnsgmntIDs) .
            "', scm.get_sllng_price_lesstax(" . $txCode . "," . $untPriceCls . "), '" . cnvrtBoolToBitStr($dlvrd) . "', " . $prsnID .
            ",'" . loc_db_escape_string($altrntNm) . "'," . $cogsID .
            "," . $salesRevID .
            "," . $salesRetID .
            "," . $purcRetID .
            "," . $expnsID .
            "," . $invAstAcntID .
            "," . $rented_itm_qty .
            "," . $other_mdls_doc_id .
            ",'" . loc_db_escape_string($other_mdls_doc_type) . "','" . loc_db_escape_string($extra_desc) . "')";
			
	       /*CLINIC/HOSPITAL*/
        $chckInID = getInvcHdrChckInID($docID);
        $admsn_id = -1;
        if($chckInID > 0){
            $admsn_id = getGnrlRecNm("hosp.inpatient_admissions", "ref_check_in_id", "admsn_id", $chckInID);
            if($admsn_id == ""){
                 $admsn_id = -1;
            }
        }

        if($admsn_id > 0){
            $appntmntID = (int)getGnrlRecNm("hosp.inpatient_admissions", "admsn_id", "dest_appntmnt_id", $admsn_id);
            //$appntmntID = getGnrlRecNm("hosp.medcl_cnsltn", "cnsltn_id", "appntmnt_id", $cnsltnID);
            //$srvs_type_id = getGnrlRecNm("hosp.appntmnt", "appntmnt_id", "srvs_type_id", $appntmntID); 
            $dateStr = getDB_Date_time();
            $ttlQty = $qty;
                $appntmntDataItemsID = getAppntmntDataItemsIDHotl();
                $uomID = "(SELECT inv.get_invitm_uom_id($itmID))";
                $isBilled = "Y";
                insertAppntmntDataItemsAllCols($appntmntDataItemsID, $appntmntID, $itmID, $ttlQty, $docID, $lineid, $isBilled, $usrID, $dateStr, $uomID, -1);
        }
        /*CLINIC/HOSPITAL*/
	
    return execUpdtInsSQL($insSQL);
}

function updateSalesDocLn2($lnID, $itmID, $qty, $untPrice, $storeID, $crncyID, $srclnID, $txCode, $cnsgmntIDs, $dlvrd, $altrntNm,
        $rented_itm_qty = 1, $other_mdls_doc_id = -1, $other_mdls_doc_type = "", $extra_desc = "") {
    global $usrID;
    $untPriceCls = $untPrice;
    if (strlen($extra_desc) > 300) {
        $extra_desc = substr($extra_desc, 0, 300);
    }
    $updtSQL = "UPDATE scm.scm_sales_invc_det SET " .
            "itm_id=" . $itmID .
            ", doc_qty =" . $qty .
            ", unit_selling_price= " . $untPriceCls .
            ", orgnl_selling_price= scm.get_sllng_price_lesstax(" . $txCode . "," . $untPriceCls . "), " .
            "last_update_by = " . $usrID .
            ", last_update_date= to_char(now(),'YYYY-MM-DD HH24:MI:SS'), " .
            "store_id=" . $storeID .
            ", crncy_id =" . $crncyID . ", src_line_id = " . $srclnID .
            ", consgmnt_ids ='" . loc_db_escape_string($cnsgmntIDs) .
            "', is_itm_delivered ='" . cnvrtBoolToBitStr($dlvrd) .
            "', alternate_item_name = '" . loc_db_escape_string($altrntNm) .
            "', rented_itm_qty=" . $rented_itm_qty .
            ", other_mdls_doc_id=" . $other_mdls_doc_id .
            ", other_mdls_doc_type='" . loc_db_escape_string($other_mdls_doc_type) .
            "', extra_desc='" . loc_db_escape_string($extra_desc) . "' WHERE (invc_det_ln_id = " . $lnID . ")";
    //echo $updtSQL;
	
	    /*CLINIC/HOSPITAL*/   
        $docID = (int)getGnrlRecNm("scm.scm_sales_invc_det", "invc_det_ln_id", "invc_hdr_id", $lnID);
        $chckInID = getInvcHdrChckInID($docID);
        $admsn_id = -1;
        if($chckInID > 0){
            $admsn_id = getGnrlRecNm("hosp.inpatient_admissions", "ref_check_in_id", "admsn_id", $chckInID);
            if($admsn_id == ""){
                 $admsn_id = -1;
            }
            
             if($admsn_id > 0){
                 execUpdtInsSQL("UPDATE hosp.appntmnt_data_items SET qty = $qty WHERE invc_hdr_id = $docID AND invc_det_ln_id = $lnID AND invc_hdr_id > 0 AND invc_det_ln_id > 0");
             }
        }
        /*CLINIC/HOSPITAL*/
	
    return execUpdtInsSQL($updtSQL);
}

function updateSalesDocLn3($lnID, $itmID, $qty, $storeID, $crncyID, $srclnID, $txCode, $dscntCde, $chrgeCde, $rtrnRsn, $cnsgmntIDs, $dlvrd,
        $prsnID, $altrntNm, $invAstAcntID, $cogsID, $salesRevID, $salesRetID, $purcRetID, $expnsID, $rented_itm_qty = 1,
        $other_mdls_doc_id = -1, $other_mdls_doc_type = "", $extra_desc = "") {
    global $usrID;
    if (strlen($extra_desc) > 300) {
        $extra_desc = substr($extra_desc, 0, 300);
    }
    $updtSQL = "UPDATE scm.scm_sales_invc_det SET " .
            "itm_id=" . $itmID .
            ", doc_qty =" . $qty .
            ", last_update_by = " . $usrID .
            ", last_update_date= to_char(now(),'YYYY-MM-DD HH24:MI:SS'), " .
            "store_id=" . $storeID .
            ", crncy_id =" . $crncyID . ", src_line_id = " . $srclnID .
            ", tax_code_id = " . $txCode .
            ", dscnt_code_id = " . $dscntCde .
            ", chrg_code_id = " . $chrgeCde .
            ", rtrn_reason = '" . loc_db_escape_string($rtrnRsn) .
            "', consgmnt_ids ='" . loc_db_escape_string($cnsgmntIDs) .
            "', is_itm_delivered ='" . cnvrtBoolToBitStr($dlvrd) .
            "', lnkd_person_id = " . $prsnID .
            ", alternate_item_name = '" . loc_db_escape_string($altrntNm) .
            "', cogs_acct_id=" . $cogsID .
            ", sales_rev_accnt_id=" . $salesRevID .
            ", sales_ret_accnt_id =" . $salesRetID .
            ", purch_ret_accnt_id =" . $purcRetID .
            ", expense_accnt_id =" . $expnsID .
            ", inv_asset_acct_id =" . $invAstAcntID .
            ", rented_itm_qty=" . $rented_itm_qty .
            ", other_mdls_doc_id=" . $other_mdls_doc_id .
            ", other_mdls_doc_type='" . loc_db_escape_string($other_mdls_doc_type) .
            "', extra_desc='" . loc_db_escape_string($extra_desc) . "' WHERE (invc_det_ln_id = " . $lnID . ")";
			
	    /*CLINIC/HOSPITAL*/   
        $docID = (int)getGnrlRecNm("scm.scm_sales_invc_det", "invc_det_ln_id", "invc_hdr_id", $lnID);
        $chckInID = getInvcHdrChckInID($docID);
        $admsn_id = -1;
        if($chckInID > 0){
            $admsn_id = getGnrlRecNm("hosp.inpatient_admissions", "ref_check_in_id", "admsn_id", $chckInID);
            if($admsn_id == ""){
                 $admsn_id = -1;
            }
            
             if($admsn_id > 0){
                 execUpdtInsSQL("UPDATE hosp.appntmnt_data_items SET qty = $qty WHERE invc_hdr_id = $docID AND invc_det_ln_id = $lnID AND invc_hdr_id > 0 AND invc_det_ln_id > 0");
             }
        }
        /*CLINIC/HOSPITAL*/		
	
    return execUpdtInsSQL($updtSQL);
}

function updateSalesLnDlvrd($lnID, $dlvrd) {
    $updtSQL = "UPDATE scm.scm_sales_invc_det SET " .
            "is_itm_delivered='" . cnvrtBoolToBitStr($dlvrd) .
            "' WHERE (invc_det_ln_id = " . $lnID . ")";
    return execUpdtInsSQL($updtSQL);
}

function updtSrcDocTrnsctdQty($src_lnid, $qty) {
    global $usrID;
    $updtSQL = "UPDATE scm.scm_sales_invc_det SET " .
            "qty_trnsctd_in_dest_doc=qty_trnsctd_in_dest_doc+" . $qty .
            ", last_update_by=" . $usrID .
            ", last_update_date=to_char(now(),'YYYY-MM-DD HH24:MI:SS') WHERE (invc_det_ln_id = " .
            $src_lnid . ")";
    return execUpdtInsSQL($updtSQL);
}

function get_AllConsignments($searchWord, $searchIn, $offset, $limit_size, $orgID, $cstmrSiteID) {
    $strSql = "";
    $wherecls = "";
    $invCls = "";
    $extInvCls = "";
    $itmTyp = " AND (a.item_type != 'Expense Item') AND (a.item_type != 'Services')";

    if ($searchIn == "Item Code/Name") {
        $wherecls = "(a.item_code ilike '" . loc_db_escape_string($searchWord) .
                "') AND ";
    } else if ($searchIn == "Item Description") {
        $wherecls = "(a.item_desc ilike '" . loc_db_escape_string($searchWord) .
                "') AND ";
    }

    $strSql = "SELECT distinct a.item_id, a.item_code, a.item_desc, " .
            "a.selling_price, a.category_id, b.stock_id, b.subinv_id, b.shelves, " .
            "a.tax_code_id, CASE WHEN scm.get_cstmr_splr_dscntid("
            . $cstmrSiteID . ") != -1 THEN scm.get_cstmr_splr_dscntid("
            . $cstmrSiteID . ") ELSE a.dscnt_code_id END , a.extr_chrg_id, c.consgmt_id, c.cost_price, c.expiry_date " .
            "FROM inv.inv_itm_list a, inv.inv_stock b, inv.inv_consgmt_rcpt_det c " .
            "WHERE (" . $wherecls . "(a.item_id = b.itm_id and b.stock_id = c.stock_id " .
            "and a.item_id = c.itm_id and b.subinv_id = c.subinv_id and a.enabled_flag='1')" . $invCls .
            " AND (a.org_id = " . $orgID .
            ")" . $extInvCls . $itmTyp . ") ORDER BY c.consgmt_id ASC, a.item_code LIMIT " . $limit_size .
            " OFFSET " . (abs($offset * $limit_size));
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_StoreItems($searchWord, $searchIn, $offset, $limit_size, $orgID, $storeID, $docTyp, $cnsgmtsOnly, $itmID, $cstmrSiteID) {
    $strSql = "";
    $wherecls = "";
    $invCls = "";
    $extInvCls = "";
    $itmTyp = "";
    if ($docTyp == "Sales Invoice" || $docTyp == "Pro-Forma Invoice" || $docTyp == "Sales Order") {
        $itmTyp = " AND ((a.item_type = 'Merchandise Inventory' AND b.subinv_id = " . $storeID . ") OR a.item_type = 'Services')";
        $invCls = "";
        $extInvCls = " AND (now() between to_timestamp(b.start_date, " .
                "'YYYY-MM-DD HH24:MI:SS') AND to_timestamp(CASE WHEN b.end_date='' " .
                "THEN '4000-12-31 23:59:59' ELSE b.end_date END, " .
                "'YYYY-MM-DD HH24:MI:SS'))";
    } else if ($docTyp == "Internal Item Request") {
//itmTyp = " AND (a.item_type != 'Expense Item') AND (a.item_type != 'Services')";
    } else if ($docTyp == "Item Issue-Unbilled") {
//itmTyp = " AND (a.item_type != 'Expense Item') AND (a.item_type != 'Services')";
        $invCls = " AND (b.subinv_id = " . $storeID . ")";
        $extInvCls = " AND (now() between to_timestamp(b.start_date, " .
                "'YYYY-MM-DD HH24:MI:SS') AND to_timestamp(CASE WHEN b.end_date='' THEN '4000-12-31 23:59:59' ELSE b.end_date END, " .
                "'YYYY-MM-DD HH24:MI:SS'))";
    } /* else if ($docTyp == "") {
      $invCls = " AND (b.subinv_id = " . $storeID . ")";
      } */
    if ($searchIn == "Item Code/Name") {
        $wherecls = "(a.item_code ilike '" . loc_db_escape_string($searchWord) .
                "' or a.item_desc ilike '" . loc_db_escape_string($searchWord) .
                "') AND ";
    } else /* if ($searchIn == "Item Description") */ {
        $wherecls = "(a.item_code ilike '" . loc_db_escape_string($searchWord) .
                "' or a.item_desc ilike '" . loc_db_escape_string($searchWord) .
                "') AND ";
    }
    if ($itmID > 0) {
        $wherecls .= "(a.item_id=" . $itmID . ") AND ";
    }
    if ($cnsgmtsOnly == true) {
        if ($storeID > 0) {
            $invCls = " AND (b.subinv_id = " . $storeID . ")";
        }
        $strSql = "SELECT distinct a.item_id, a.item_code, REPLACE(a.item_code || ' (' || a.item_desc || ')', ' (' || a.item_code || ')','') item_desc, " .
                "a.selling_price, a.category_id, b.stock_id, b.subinv_id, b.shelves, " .
                "a.tax_code_id, CASE WHEN scm.get_cstmr_splr_dscntid("
                . $cstmrSiteID . ") != -1 THEN scm.get_cstmr_splr_dscntid("
                . $cstmrSiteID . ") ELSE a.dscnt_code_id END , a.extr_chrg_id, c.consgmt_id, c.cost_price, c.expiry_date,"
                . "inv.get_store_name(b.subinv_id),scm.get_tax_code(a.tax_code_id),"
                . " scm.get_tax_code((CASE WHEN scm.get_cstmr_splr_dscntid("
                . $cstmrSiteID . ") != -1 THEN scm.get_cstmr_splr_dscntid("
                . $cstmrSiteID . ") ELSE a.dscnt_code_id END)), scm.get_tax_code(a.extr_chrg_id), a.base_uom_id, inv.get_uom_name(a.base_uom_id),"
                . "inv.get_csgmt_lst_avlbl_bls(c.consgmt_id), 0,0,inv.get_catgryname(a.category_id),
                   a.inv_asset_acct_id, a.cogs_acct_id, a.sales_rev_accnt_id, a.sales_ret_accnt_id, a.purch_ret_accnt_id, a.expense_accnt_id,a.item_type " .
                "FROM inv.inv_itm_list a, inv.inv_stock b, inv.inv_consgmt_rcpt_det c " .
                "WHERE (" . $wherecls . "(a.item_id = b.itm_id and b.stock_id = c.stock_id " .
                "and a.item_id = c.itm_id and b.subinv_id = c.subinv_id and a.enabled_flag='1')" . $invCls .
                " AND (a.org_id = " . $orgID . " and inv.get_csgmt_lst_avlbl_bls(c.consgmt_id)>0)" . $extInvCls . $itmTyp . ") ORDER BY c.consgmt_id ASC, a.item_code LIMIT " . $limit_size .
                " OFFSET " . (abs($offset * $limit_size));
//echo $strSql; inv.get_csgmt_lst_rsvd_bls(c.consgmt_id), inv.get_csgmt_lst_tot_bls(c.consgmt_id)
    } else {
        $strSql = "SELECT a.item_id, a.item_code, REPLACE(a.item_code || ' (' || a.item_desc || ')', ' (' || a.item_code || ')','') item_desc, " .
                "a.selling_price, a.category_id, COALESCE(b.stock_id,-1), COALESCE(b.subinv_id,-1), b.shelves, " .
                "a.tax_code_id, CASE WHEN scm.get_cstmr_splr_dscntid("
                . $cstmrSiteID . ") != -1 THEN scm.get_cstmr_splr_dscntid("
                . $cstmrSiteID . ") ELSE a.dscnt_code_id END , a.extr_chrg_id, -1, inv.get_invitm_unitval(a.item_id),'', "
                . "inv.get_store_name(b.subinv_id), scm.get_tax_code(a.tax_code_id),"
                . " scm.get_tax_code((CASE WHEN scm.get_cstmr_splr_dscntid("
                . $cstmrSiteID . ") != -1 THEN scm.get_cstmr_splr_dscntid("
                . $cstmrSiteID . ") ELSE a.dscnt_code_id END)), scm.get_tax_code(a.extr_chrg_id), a.base_uom_id, inv.get_uom_name(a.base_uom_id),"
                . "scm.get_ltst_stock_avlbl_bals(b.stock_id,to_char(now(),'YYYY-MM-DD')), 0, 0,inv.get_catgryname(a.category_id),
                   a.inv_asset_acct_id, a.cogs_acct_id, a.sales_rev_accnt_id, a.sales_ret_accnt_id, a.purch_ret_accnt_id, a.expense_accnt_id,a.item_type " .
                "FROM inv.inv_itm_list a LEFT OUTER JOIN inv.inv_stock b ON a.item_id = b.itm_id " . $extInvCls .
                " WHERE (" . $wherecls . "(a.enabled_flag='1')" . $invCls .
                " AND (a.org_id = " . $orgID .
                ")" . $itmTyp . ") ORDER BY a.item_code LIMIT " . $limit_size .
                " OFFSET " . (abs($offset * $limit_size));
//scm.get_ltst_stock_rsrvd_bals(b.stock_id), scm.get_ltst_stock_bals(b.stock_id)
    }
//echo $strSql;
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_Total_StoreItms(
        $searchWord, $searchIn, $orgID, $storeID, $docTyp, $cnsgmtsOnly, $itmID) {
    $strSql = "";
    $wherecls = "";
    $invCls = "";
    $extInvCls = "";
    $itmTyp = "";
    if ($docTyp == "Sales Invoice" || $docTyp == "Pro-Forma Invoice" || $docTyp == "Sales Order") {
        $itmTyp = " AND ((a.item_type = 'Merchandise Inventory' AND b.subinv_id = " . $storeID . ") OR a.item_type = 'Services')";
        $invCls = "";
        $extInvCls = " AND (now() between to_timestamp(b.start_date, " .
                "'YYYY-MM-DD HH24:MI:SS') AND to_timestamp(CASE WHEN b.end_date='' " .
                "THEN '4000-12-31 23:59:59' ELSE b.end_date END, " .
                "'YYYY-MM-DD HH24:MI:SS'))";
    } else if ($docTyp == "Internal Item Request") {
//itmTyp = " AND (a.item_type != 'Expense Item') AND (a.item_type != 'Services')";
    } else if ($docTyp == "Item Issue-Unbilled") {
//itmTyp = " AND (a.item_type != 'Expense Item') AND (a.item_type != 'Services')";
        $invCls = " AND (b.subinv_id = " . $storeID . ")";
        $extInvCls = " AND (now() between to_timestamp(b.start_date, " .
                "'YYYY-MM-DD HH24:MI:SS') AND to_timestamp(CASE WHEN b.end_date='' THEN '4000-12-31 23:59:59' ELSE b.end_date END, " .
                "'YYYY-MM-DD HH24:MI:SS'))";
    } /* else if ($docTyp == "") {
      $invCls = " AND (b.subinv_id = " . $storeID . ")";
      } */
    if ($searchIn == "Item Code/Name") {
        $wherecls = "(a.item_code ilike '" . loc_db_escape_string($searchWord) .
                "' or a.item_desc ilike '" . loc_db_escape_string($searchWord) .
                "') AND ";
    } else /* if ($searchIn == "Item Description") */ {
        $wherecls = "(a.item_code ilike '" . loc_db_escape_string($searchWord) .
                "' or a.item_desc ilike '" . loc_db_escape_string($searchWord) .
                "') AND ";
    }
    if ($itmID > 0) {
        $wherecls .= "(a.item_id=" . $itmID . ") AND ";
    }
    if ($cnsgmtsOnly == true) {
        if ($storeID > 0) {
            $invCls = " AND (b.subinv_id = " . $storeID . ")";
        }
        $strSql = "SELECT count(distinct c.consgmt_id) " .
                "FROM inv.inv_itm_list a, inv.inv_stock b, inv.inv_consgmt_rcpt_det c " .
                "WHERE (" . $wherecls . "(a.item_id = b.itm_id and b.stock_id = c.stock_id " .
                "and a.item_id = c.itm_id and b.subinv_id = c.subinv_id and a.enabled_flag='1')" . $invCls .
                " AND (a.org_id = " . $orgID .
                " and inv.get_csgmt_lst_avlbl_bls(c.consgmt_id)>0)" . $extInvCls . $itmTyp . ")";
    } else {
        $strSql = "SELECT count(1)" .
                "FROM inv.inv_itm_list a LEFT OUTER JOIN inv.inv_stock b ON a.item_id = b.itm_id " . $extInvCls .
                " WHERE (" . $wherecls . "(a.enabled_flag='1')" . $invCls .
                " AND (a.org_id = " . $orgID .
                ")" . $itmTyp . ")";
    }
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return ((float) $row[0]);
    }
    return -1;
}

function get_OneStoreItemDets($searchWord, $orgID, $storeID, $docTyp, $cnsgmtsOnly, $itmID, $cstmrSiteID) {
    $strSql = "";
    $wherecls = "";
    $invCls = "";
    $extInvCls = "";
    $itmTyp = "";
    if ($docTyp == "Sales Invoice" || $docTyp == "Pro-Forma Invoice" || $docTyp == "Sales Order") {
        $itmTyp = " AND ((a.item_type = 'Merchandise Inventory' AND b.subinv_id = " . $storeID . ") OR a.item_type = 'Services')";
        $invCls = "";
        $extInvCls = " AND (now() between to_timestamp(b.start_date, " .
                "'YYYY-MM-DD HH24:MI:SS') AND to_timestamp(CASE WHEN b.end_date='' " .
                "THEN '4000-12-31 23:59:59' ELSE b.end_date END, " .
                "'YYYY-MM-DD HH24:MI:SS'))";
    } else if ($docTyp == "Internal Item Request") {
//itmTyp = " AND (a.item_type != 'Expense Item') AND (a.item_type != 'Services')";
    } else if ($docTyp == "Item Issue-Unbilled") {
//itmTyp = " AND (a.item_type != 'Expense Item') AND (a.item_type != 'Services')";
        $invCls = " AND (b.subinv_id = " . $storeID . ")";
        $extInvCls = " AND (now() between to_timestamp(b.start_date, " .
                "'YYYY-MM-DD HH24:MI:SS') AND to_timestamp(CASE WHEN b.end_date='' THEN '4000-12-31 23:59:59' ELSE b.end_date END, " .
                "'YYYY-MM-DD HH24:MI:SS'))";
    } /* else if ($docTyp == "") {
      $invCls = " AND (b.subinv_id = " . $storeID . ")";
      } */
    $wherecls = "(a.item_code ilike '" . loc_db_escape_string($searchWord) .
            "' or a.item_desc ilike '" . loc_db_escape_string($searchWord) .
            "') AND ";
    if ($itmID > 0) {
        $wherecls .= "(a.item_id=" . $itmID . ") AND ";
    }
    if ($storeID > 0) {
        $invCls = " AND ((b.subinv_id = " . $storeID . ") OR a.item_type IN ('Services','Expense Item'))";
    }
    if ($cnsgmtsOnly == true) {
        $strSql = "SELECT distinct a.item_id, a.item_code, REPLACE(a.item_code || ' (' || a.item_desc || ')', ' (' || a.item_code || ')','') item_desc, " .
                "a.selling_price, a.category_id, b.stock_id, b.subinv_id, b.shelves, " .
                "a.tax_code_id, CASE WHEN scm.get_cstmr_splr_dscntid("
                . $cstmrSiteID . ") != -1 THEN scm.get_cstmr_splr_dscntid("
                . $cstmrSiteID . ") ELSE a.dscnt_code_id END , a.extr_chrg_id, c.consgmt_id, c.cost_price, c.expiry_date,"
                . "inv.get_store_name(b.subinv_id),scm.get_tax_code(a.tax_code_id),"
                . " scm.get_tax_code((CASE WHEN scm.get_cstmr_splr_dscntid("
                . $cstmrSiteID . ") != -1 THEN scm.get_cstmr_splr_dscntid("
                . $cstmrSiteID . ") ELSE a.dscnt_code_id END)), scm.get_tax_code(a.extr_chrg_id), a.base_uom_id, inv.get_uom_name(a.base_uom_id),"
                . "inv.get_csgmt_lst_avlbl_bls(c.consgmt_id), 0,0,inv.get_catgryname(a.category_id),
                   a.inv_asset_acct_id, a.cogs_acct_id, a.sales_rev_accnt_id, a.sales_ret_accnt_id, a.purch_ret_accnt_id, a.expense_accnt_id,a.item_type " .
                "FROM inv.inv_itm_list a, inv.inv_stock b, inv.inv_consgmt_rcpt_det c " .
                "WHERE (" . $wherecls . "(a.item_id = b.itm_id and b.stock_id = c.stock_id " .
                "and a.item_id = c.itm_id and b.subinv_id = c.subinv_id and a.enabled_flag='1')" . $invCls .
                " AND (a.org_id = " . $orgID . " and inv.get_csgmt_lst_avlbl_bls(c.consgmt_id)>0)" . $extInvCls . $itmTyp . ") ORDER BY c.consgmt_id ASC, a.item_code";
//echo $strSql; inv.get_csgmt_lst_rsvd_bls(c.consgmt_id), inv.get_csgmt_lst_tot_bls(c.consgmt_id)
    } else {
        $strSql = "SELECT a.item_id, a.item_code, REPLACE(a.item_code || ' (' || a.item_desc || ')', ' (' || a.item_code || ')','') item_desc, " .
                "a.selling_price, a.category_id, COALESCE(b.stock_id,-1), COALESCE(b.subinv_id,-1), b.shelves, " .
                "a.tax_code_id, CASE WHEN scm.get_cstmr_splr_dscntid("
                . $cstmrSiteID . ") != -1 THEN scm.get_cstmr_splr_dscntid("
                . $cstmrSiteID . ") ELSE a.dscnt_code_id END , a.extr_chrg_id, -1, inv.get_invitm_unitval(a.item_id),'', "
                . "inv.get_store_name(b.subinv_id), scm.get_tax_code(a.tax_code_id),"
                . " scm.get_tax_code((CASE WHEN scm.get_cstmr_splr_dscntid("
                . $cstmrSiteID . ") != -1 THEN scm.get_cstmr_splr_dscntid("
                . $cstmrSiteID . ") ELSE a.dscnt_code_id END)), scm.get_tax_code(a.extr_chrg_id), a.base_uom_id, inv.get_uom_name(a.base_uom_id),"
                . "CASE WHEN scm.get_ltst_stock_avlbl_bals(b.stock_id,to_char(now(),'YYYY-MM-DD'))<=inv.get_invitm_stckttl(a.item_id, b.subinv_id) THEN scm.get_ltst_stock_avlbl_bals(b.stock_id,to_char(now(),'YYYY-MM-DD')) ELSE inv.get_invitm_stckttl(a.item_id, b.subinv_id) END, 0, 0,inv.get_catgryname(a.category_id),
                   a.inv_asset_acct_id, a.cogs_acct_id, a.sales_rev_accnt_id, a.sales_ret_accnt_id, a.purch_ret_accnt_id, a.expense_accnt_id,a.item_type " .
                "FROM inv.inv_itm_list a LEFT OUTER JOIN inv.inv_stock b ON a.item_id = b.itm_id " . $extInvCls .
                " WHERE (" . $wherecls . "(a.enabled_flag='1')" . $invCls .
                " AND (a.org_id = " . $orgID .
                ")" . $itmTyp . ") ORDER BY a.item_code";
//scm.get_ltst_stock_rsrvd_bals(b.stock_id), scm.get_ltst_stock_bals(b.stock_id)
    }
//echo $strSql;
    $result = executeSQLNoParams($strSql);
    return $result;
}

function getOldstItmCnsgmts($itmID, $qnty) {
    global $selectedStoreID;
    $res = ",";
    $strSql = "SELECT distinct c.consgmt_id, inv.get_csgmt_lst_avlbl_bls(c.consgmt_id) " .
            "FROM inv.inv_consgmt_rcpt_det c " .
            "WHERE ((c.itm_id=" . $itmID . ") and (c.subinv_id =" . $selectedStoreID . ") and  (inv.get_csgmt_lst_avlbl_bls(c.consgmt_id)>0)) ORDER BY c.consgmt_id ASC";

    $result = executeSQLNoParams($strSql);
    $curAvlbQty = 0;
    while ($row = loc_db_fetch_array($result)) {
        if ($curAvlbQty < $qnty) {
            $res = $res . $row[0] . ",";
            $curAvlbQty = $curAvlbQty + (float) $row[1];
        } else {
            return trim($res, ',');
        }
    }
    return trim($res, ',');
}

function getOldstItmCnsgmtsForStock($itmID, $qnty, $storeID) {
    $res = ",";
    $strSql = "SELECT distinct c.consgmt_id, inv.get_csgmt_lst_avlbl_bls(c.consgmt_id) " .
            "FROM inv.inv_consgmt_rcpt_det c " .
            "WHERE ((c.itm_id=" . $itmID . ") and (c.subinv_id =" . $storeID .
            ") and (inv.get_csgmt_lst_avlbl_bls(c.consgmt_id)>0)) ORDER BY c.consgmt_id ASC";


    $result = executeSQLNoParams($strSql);
    $curAvlbQty = 0;
    while ($row = loc_db_fetch_array($result)) {
        if ($curAvlbQty < $qnty) {
            $res = $res . $row[0] . ",";
            $curAvlbQty = $curAvlbQty + (float) $row[1];
        } else {
            return trim($res, ',');
        }
    }
    return trim($res, ',');
}

/* public static List<string> getOldstItmCnsgmtsNCstPrcLstForStock(long itmID, double qnty, int storeID)
  {
  List<string> result = new List<string>();
  string resCnsgmntIDs = ",";
  string resCnsgmntIDCstPrce = ",";
  string strSql = "SELECT distinct c.consgmt_id, cost_price, inv.get_csgmt_lst_avlbl_bls(c.consgmt_id) " +
  "FROM inv.inv_consgmt_rcpt_det c " +
  "WHERE ((c.itm_id=" + itmID + ") and (c.subinv_id =" + storeID + ") and (inv.get_csgmt_lst_avlbl_bls(c.consgmt_id)>0)) ORDER BY c.consgmt_id ASC";

  DataSet dtst = Global.mnFrm.cmCde.selectDataNoParams(strSql);
  double curAvlbQty = 0;
  for (int i = 0; i < dtst.Tables[0].Rows.Count; i++)
  {
  if (curAvlbQty < qnty)
  {
  resCnsgmntIDs = resCnsgmntIDs + dtst.Tables[0].Rows[i][0].ToString() + ",";
  resCnsgmntIDCstPrce = resCnsgmntIDCstPrce + dtst.Tables[0].Rows[i][1].ToString() + ",";
  curAvlbQty = curAvlbQty + double.Parse(dtst.Tables[0].Rows[i][2].ToString());
  }
  else
  {
  result.Add(resCnsgmntIDs.Trim(','));
  result.Add(resCnsgmntIDCstPrce.Trim(','));
  return result;
  }
  }
  result.Add(resCnsgmntIDs.Trim(','));
  result.Add(resCnsgmntIDCstPrce.Trim(','));
  return result;
  } */

function getCnsgmtsQtySum($cnsgmtIDs) {
//MessageBox.Show(cnsgmtIDs);
    if ($cnsgmtIDs != "") {
        $cnsgmtIDs = trim(str_replace(",,", ",", str_replace(",,", ",", str_replace(",,", ",", $cnsgmtIDs))), ',');
    }
    if ($cnsgmtIDs == "") {
        $cnsgmtIDs = "-123412";
    }
    $strSql = "SELECT distinct c.consgmt_id, inv.get_csgmt_lst_avlbl_bls(c.consgmt_id) " .
            "FROM inv.inv_consgmt_rcpt_det c " .
            "WHERE ((c.consgmt_id IN (" . trim($cnsgmtIDs, ',') .
            ")) and (inv.get_csgmt_lst_avlbl_bls(c.consgmt_id)>0)) ORDER BY c.consgmt_id ASC";

    $result = executeSQLNoParams($strSql);
    $ttlQty = 0;
    while ($row = loc_db_fetch_array($result)) {
        $ttlQty = $ttlQty + (float) $row[1];
    }
    return $ttlQty;
}

function getCnsgmtsRsvdSum($cnsgmtIDs) {
    if ($cnsgmtIDs == "") {
        $cnsgmtIDs = "-123412";
    }
    $strSql = "SELECT distinct c.consgmt_id, inv.get_csgmt_lst_rsvd_bls(c.consgmt_id) " .
            "FROM inv.inv_consgmt_rcpt_det c " .
            "WHERE ((c.consgmt_id IN (" . trim($cnsgmtIDs, ',') .
            ")) and (inv.get_csgmt_lst_rsvd_bls(c.consgmt_id)>0)) ORDER BY c.consgmt_id ASC";

    $result = executeSQLNoParams($strSql);
    $ttlQty = 0;
    while ($row = loc_db_fetch_array($result)) {
        $ttlQty = $ttlQty + (float) $row[1];
    }
    return $ttlQty;
}

function get_SalesInvc_Attachments($searchWord, $offset, $limit_size, $batchID, &$attchSQL) {
    $strSql = "SELECT a.attchmnt_id, a.doc_hdr_id, a.attchmnt_desc, a.file_name " .
            "FROM scm.scm_sales_doc_attchmnts a " .
            "WHERE(a.attchmnt_desc ilike '" . loc_db_escape_string($searchWord) .
            "' and a.doc_hdr_id = " . $batchID . ") ORDER BY a.attchmnt_id LIMIT " . $limit_size .
            " OFFSET " . (abs($offset * $limit_size));
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_Total_SalesInvc_Attachments($searchWord, $batchID) {
    $strSql = "SELECT count(1) " .
            "FROM scm.scm_sales_doc_attchmnts a " .
            "WHERE(a.attchmnt_desc ilike '" . loc_db_escape_string($searchWord) .
            "' and a.doc_hdr_id = " . $batchID . ")";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return 0;
}

function getSalesInvcAttchmtDocs($batchid) {
    $sqlStr = "SELECT attchmnt_id, file_name, attchmnt_desc
  FROM scm.scm_sales_doc_attchmnts WHERE 1=1 AND file_name != '' AND doc_hdr_id = " . $batchid;
    $result = executeSQLNoParams($sqlStr);
    return $result;
}

function updateSalesInvcDocFlNm($attchmnt_id, $file_name) {
    global $usrID;
    $dateStr = getDB_Date_time();
    $insSQL = "UPDATE scm.scm_sales_doc_attchmnts SET file_name='"
            . loc_db_escape_string($file_name) .
            "', last_update_by=" . $usrID .
            ", last_update_date='" . $dateStr . "'
                WHERE attchmnt_id=" . $attchmnt_id;
    return execUpdtInsSQL($insSQL);
}

function getNewSalesInvcDocID() {
    $strSql = "select nextval('scm.scm_sales_doc_attchmnts_attchmnt_id_seq')";
    $result = executeSQLNoParams($strSql);

    if (loc_db_num_rows($result) > 0) {
        $row = loc_db_fetch_array($result);
        return $row[0];
    }
    return -1;
}

function createSalesInvcDoc($attchmnt_id, $hdrid, $attchmnt_desc, $file_name) {
    global $usrID;
    $dateStr = getDB_Date_time();
    $insSQL = "INSERT INTO scm.scm_sales_doc_attchmnts(
            attchmnt_id, doc_hdr_id, attchmnt_desc, file_name, created_by, 
            creation_date, last_update_by, last_update_date)
             VALUES (" . $attchmnt_id . ", " . $hdrid . ",'"
            . loc_db_escape_string($attchmnt_desc) . "','"
            . loc_db_escape_string($file_name) . "',"
            . $usrID . ",'" . $dateStr . "'," . $usrID . ",'" . $dateStr . "')";
    return execUpdtInsSQL($insSQL);
}

function deleteSalesInvcDoc($pkeyID, $docTrnsNum = "") {
    $insSQL = "DELETE FROM scm.scm_sales_doc_attchmnts WHERE attchmnt_id = " . $pkeyID;
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

function uploadDaSalesInvcDoc($attchmntID, &$nwImgLoc, &$errMsg) {
    global $tmpDest;
    global $ftp_base_db_fldr;
    global $usrID;
    global $fldrPrfx;
    global $smplTokenWord1;

    $msg = "";
    $allowedExts = array('png', 'jpg', 'gif', 'jpeg', 'bmp', 'pdf', 'xls', 'xlsx',
        'doc', 'docx', 'ppt', 'pptx', 'txt', 'csv');

    if (isset($_FILES["daSalesInvcAttchmnt"])) {
        $flnm = $_FILES["daSalesInvcAttchmnt"]["name"];
        $temp = explode(".", $flnm);
        $extension = end($temp);
        if ($_FILES["daSalesInvcAttchmnt"]["error"] > 0) {
            $msg .= "Return Code: " . $_FILES["daSalesInvcAttchmnt"]["error"] . "<br>";
        } else {
            $msg .= "Uploaded File: " . $_FILES["daSalesInvcAttchmnt"]["name"] . "<br>";
            $msg .= "Type: " . $_FILES["daSalesInvcAttchmnt"]["type"] . "<br>";
            $msg .= "Size: " . round(($_FILES["daSalesInvcAttchmnt"]["size"]) / (1024 * 1024), 2) . " MB<br>";
//$msg .= "Temp file: " . $_FILES["daSalesInvcAttchmnt"]["tmp_name"] . "<br>";
            if ((($_FILES["daSalesInvcAttchmnt"]["type"] == "image/gif") || ($_FILES["daSalesInvcAttchmnt"]["type"] == "image/jpeg") || ($_FILES["daSalesInvcAttchmnt"]["type"] == "image/jpg") || ($_FILES["daSalesInvcAttchmnt"]["type"] == "image/pjpeg") || ($_FILES["daSalesInvcAttchmnt"]["type"] == "image/x-png") || ($_FILES["daSalesInvcAttchmnt"]["type"] == "image/png") || in_array($extension, $allowedExts)) && ($_FILES["daSalesInvcAttchmnt"]["size"] < 10000000)) {
                $nwFileName = encrypt1($attchmntID . "." . $extension, $smplTokenWord1) . "." . $extension;
                $img_src = $fldrPrfx . $tmpDest . "$nwFileName";
                move_uploaded_file($_FILES["daSalesInvcAttchmnt"]["tmp_name"], $img_src);
                $ftp_src = $ftp_base_db_fldr . "/Sales/$attchmntID" . "." . $extension;
                if (file_exists($img_src)) {
                    copy("$img_src", "$ftp_src");
                    $dateStr = getDB_Date_time();
                    $updtSQL = "UPDATE scm.scm_sales_doc_attchmnts
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
                $msg .= "Invalid file!<br/>File Size must be below 10MB and<br/>File Type must be in the ff:<br/>" . implode(", ",
                                $allowedExts);
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

function reCalcSalesInvcSmmrys($srcDocID, $srcDocType, $p_cstmrID, $invcCurrID, $p_docStatus) {
    global $usrID;
    global $orgID;
    $strSql = "select scm.reCalcSmmrys(" . $srcDocID .
            ", '" . loc_db_escape_string($srcDocType) .
            "'," . $p_cstmrID . "," . $invcCurrID . ",'" . loc_db_escape_string($p_docStatus) . "'," . $orgID . "," . $usrID . ")";

    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return "ERROR:No Result";
}

function load_dues_attchd_vals($invoice_id, $p_storeid) {
    global $usrID;
    global $orgID;
    $strSql = "SELECT pay.invcSaveMassPayItms(" . $invoice_id . "," . $p_storeid . "," . $usrID . ")";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return "ERROR:No Result";
}

function reCalcPrchsDocSmmrys($srcDocID, $srcDocType, $p_cstmrID, $invcCurrID, $p_docStatus) {
    global $usrID;
    global $orgID;
    $strSql = "select scm.reCalcPrchsDocSmmrys(" . $srcDocID .
            ", '" . loc_db_escape_string($srcDocType) .
            "'," . $p_cstmrID . "," . $invcCurrID . ",'" . loc_db_escape_string($p_docStatus) . "'," . $orgID . "," . $usrID . ")";

    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return "ERROR:No Result";
}

function approve_sales_prchsdoc($srcDocID, $p_DocKind) {
    global $usrID;
    global $orgID;
    $strSql = "select scm.approve_sales_prchsdoc(" . $srcDocID . ",'" . loc_db_escape_string($p_DocKind) . "'," . $orgID . "," . $usrID . ")";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return "ERROR:No Result";
}

function cancelSalesPrchsDoc($p_dochdrid, $p_dockind, $p_org_id, $p_who_rn) {
    $strSql = "select scm.cancel_sales_prchsdoc(" . $p_dochdrid .
            ", '" . loc_db_escape_string($p_dockind) .
            "', " . $p_org_id . "," . $p_who_rn . ")";

    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return "ERROR:No Result";
}

function udateItemBalances($itmID, $qnty, $cnsgmntIDs, $txCodeID, $dscntCodeID, $chrgCodeID, $docTyp, $docID, $srcDocID, $dfltRcvblAcntID,
        $dfltInvAcntID, $dfltCGSAcntID, $dfltExpnsAcntID, $dfltRvnuAcntID, $stckID, $unitSllgPrc, $crncyID, $docLnID, $dfltSRAcntID,
        $dfltCashAcntID, $dfltCheckAcntID, $srcDocLnID, $dateStr, $docIDNum, $entrdCurrID, $exchngRate, $dfltLbltyAccnt, $strSrcDocType) {
    global $usrID;
    $strSql = "select inv.udateitembalances(
	" . $itmID . ",
	" . $qnty . ",
	'" . $cnsgmntIDs . "',
	" . $txCodeID . ",
	" . $dscntCodeID . ",
	" . $chrgCodeID . ",
	'" . $docTyp . "',
	" . $docID . ",
	" . $srcDocID . ",
	" . $dfltRcvblAcntID . ",
	" . $dfltInvAcntID . ",
	" . $dfltCGSAcntID . ",
	" . $dfltExpnsAcntID . ",
	" . $dfltRvnuAcntID . ",
	" . $stckID . ",
	" . $unitSllgPrc . ",
	" . $crncyID . ",
	" . $docLnID . ",
	" . $dfltSRAcntID . ",
	" . $dfltCashAcntID . ",
	" . $dfltCheckAcntID . ",
	" . $srcDocLnID . ",
	'" . $dateStr . "',
	'" . $docIDNum . "',
	" . $entrdCurrID . ",
	" . $exchngRate . ",
	" . $dfltLbltyAccnt . ",
	'" . $strSrcDocType . "'," . $usrID . ")";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return "ERROR:No Result";
}

function rvrsQtyPostngs($lnID, $cnsgmntIDs, $dateStr, $stckID, $p_doctyp, $strSrcDocType) {
    global $usrID;
    $strSql = "select 
      inv.rvrsqtypostngs(
	" . $lnID . ",
	'" . $cnsgmntIDs . "',
	'" . $dateStr . "',
	" . $stckID . ",
	'" . $p_doctyp . "',
	'" . $strSrcDocType . "'," . $usrID . ")";

    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return "ERROR:No Result";
}

function approve_cnsgn_rcpt($srcDocID, $p_DocKind) {
    global $usrID;
    global $orgID;
    $strSql = "select inv.approve_cnsgn_rcpt(" . $srcDocID . ",'" . loc_db_escape_string($p_DocKind) . "'," . $orgID . "," . $usrID . ")";

    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return "ERROR:No Result";
}

function cancel_cnsgn_rcpt($p_dochdrid, $p_dockind, $p_org_id, $p_who_rn) {
    $strSql = "select inv.cancel_cnsgn_rcpt(" . $p_dochdrid .
            ", '" . loc_db_escape_string($p_dockind) .
            "', " . $p_org_id . "," . $p_who_rn . ")";

    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return "ERROR:No Result";
}

function approve_stck_trnsfr($srcDocID, $p_DocKind) {
    global $usrID;
    global $orgID;
    $strSql = "select inv.approve_stck_trnsfr(" . $srcDocID . ",'" . loc_db_escape_string($p_DocKind) . "'," . $orgID . "," . $usrID . ")";

    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return "ERROR:No Result";
}

function cancel_stck_trnsfr($p_dochdrid, $p_dockind, $p_org_id, $p_who_rn) {
    $strSql = "select inv.cancel_stck_trnsfr(" . $p_dochdrid .
            ", '" . loc_db_escape_string($p_dockind) .
            "', " . $p_org_id . "," . $p_who_rn . ")";

    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return "ERROR:No Result";
}

function get_Basic_PrchsDoc($searchWord, $searchIn, $offset, $limit_size, $orgID, $shwUnpstdOnly, $shwUnpaidOnly) {
    global $vwOnlySelf;
    global $usrID;
    $strSql = "";
    $whereClause = "";
    $crtdByClause = "";
    if ($vwOnlySelf === true) {
        $crtdByClause = " AND (created_by=" . $usrID . ")";
    }
    if ($searchIn == "Document Number") {
        $whereClause = "(a.purchase_doc_num ilike '" . loc_db_escape_string($searchWord) .
                "') AND ";
    } else if ($searchIn == "Document Description") {
        $whereClause = "(a.comments_desc ilike '" . loc_db_escape_string($searchWord) .
                "') AND ";
    } else if ($searchIn == "Supplier Name") {
        $whereClause = "(a.supplier_id IN (select c.cust_sup_id from scm.scm_cstmr_suplr c where c.cust_sup_name ilike '" . loc_db_escape_string($searchWord) .
                "')) AND ";
    } else if ($searchIn == "Requisition Number") {
        $whereClause = "(a.requisition_id IN (select c.prchs_doc_hdr_id from scm.scm_prchs_docs_hdr c where c.purchase_doc_num ilike '" . loc_db_escape_string($searchWord) .
                "')) AND ";
    } else if ($searchIn == "Approval Status") {
        $whereClause = "(a.approval_status ilike '" . loc_db_escape_string($searchWord) .
                "') AND ";
    } else if ($searchIn == "Created By") {
        $whereClause = "(a.created_by IN (select c.user_id from sec.sec_users c where c.user_name ilike '" . loc_db_escape_string($searchWord) .
                "')) AND ";
    } else if ($searchIn == "Branch") {
        $whereClause = " and (org.get_site_code_desc(a.branch_id) ilike '" . loc_db_escape_string($searchWord) . "')";
    } else {
        $whereClause = " and (org.get_site_code_desc(a.branch_id) ilike '" . loc_db_escape_string($searchWord) . "' "
                . "or a.created_by IN (select c.user_id from sec.sec_users c where c.user_name ilike '" . loc_db_escape_string($searchWord) .
                "') or a.approval_status ilike '" . loc_db_escape_string($searchWord) .
                "' or a.requisition_id IN (select c.prchs_doc_hdr_id from scm.scm_prchs_docs_hdr c where c.purchase_doc_num ilike '" . loc_db_escape_string($searchWord) .
                "') or a.supplier_id IN (select c.cust_sup_id from scm.scm_cstmr_suplr c where c.cust_sup_name ilike '" . loc_db_escape_string($searchWord) .
                "') or a.comments_desc ilike '" . loc_db_escape_string($searchWord) .
                "' or a.purchase_doc_num ilike '" . loc_db_escape_string($searchWord) .
                "')";
    }

    $strSql = "SELECT a.prchs_doc_hdr_id, a.purchase_doc_num, a.purchase_doc_type, 
        comments_desc,gst.get_pssbl_val(a.prntd_doc_curr_id), round(scm.getprchsdocgrndamnt(a.prchs_doc_hdr_id),2), 
        a.approval_status, scm.get_cstmr_splr_name(a.supplier_id), a.branch_id, org.get_site_code_desc(a.branch_id) " .
            "FROM scm.scm_prchs_docs_hdr a " .
            "WHERE (" . $whereClause . "(a.org_id = " . $orgID .
            ")" . $crtdByClause . ") ORDER BY a.prchs_doc_hdr_id DESC LIMIT " . $limit_size .
            " OFFSET " . (abs($offset * $limit_size));
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_Total_PrchsDoc($searchWord, $searchIn, $orgID, $shwUnpstdOnly, $shwUnpaidOnly) {
    global $vwOnlySelf;
    global $usrID;
    $strSql = "";
    $whereClause = "";
    $crtdByClause = "";
    if ($vwOnlySelf === true) {
        $crtdByClause = " AND (created_by=" . $usrID . ")";
    }
    if ($searchIn == "Document Number") {
        $whereClause = "(a.purchase_doc_num ilike '" . loc_db_escape_string($searchWord) .
                "') AND ";
    } else if ($searchIn == "Document Description") {
        $whereClause = "(a.comments_desc ilike '" . loc_db_escape_string($searchWord) .
                "') AND ";
    } else if ($searchIn == "Supplier Name") {
        $whereClause = "(a.supplier_id IN (select c.cust_sup_id from scm.scm_cstmr_suplr c where c.cust_sup_name ilike '" . loc_db_escape_string($searchWord) .
                "')) AND ";
    } else if ($searchIn == "Requisition Number") {
        $whereClause = "(a.requisition_id IN (select c.prchs_doc_hdr_id from scm.scm_prchs_docs_hdr c where c.purchase_doc_num ilike '" . loc_db_escape_string($searchWord) .
                "')) AND ";
    } else if ($searchIn == "Approval Status") {
        $whereClause = "(a.approval_status ilike '" . loc_db_escape_string($searchWord) .
                "') AND ";
    } else if ($searchIn == "Created By") {
        $whereClause = "(a.created_by IN (select c.user_id from sec.sec_users c where c.user_name ilike '" . loc_db_escape_string($searchWord) .
                "')) AND ";
    } else if ($searchIn == "Branch") {
        $whereClause = " and (org.get_site_code_desc(a.branch_id) ilike '" . loc_db_escape_string($searchWord) . "')";
    } else {
        $whereClause = " and (org.get_site_code_desc(a.branch_id) ilike '" . loc_db_escape_string($searchWord) . "' "
                . "or a.created_by IN (select c.user_id from sec.sec_users c where c.user_name ilike '" . loc_db_escape_string($searchWord) .
                "') or a.approval_status ilike '" . loc_db_escape_string($searchWord) .
                "' or a.requisition_id IN (select c.prchs_doc_hdr_id from scm.scm_prchs_docs_hdr c where c.purchase_doc_num ilike '" . loc_db_escape_string($searchWord) .
                "') or a.supplier_id IN (select c.cust_sup_id from scm.scm_cstmr_suplr c where c.cust_sup_name ilike '" . loc_db_escape_string($searchWord) .
                "') or a.comments_desc ilike '" . loc_db_escape_string($searchWord) .
                "' or a.purchase_doc_num ilike '" . loc_db_escape_string($searchWord) .
                "')";
    }

    $strSql = "SELECT count(1) " .
            "FROM scm.scm_prchs_docs_hdr a " .
            "WHERE (" . $whereClause . "(a.org_id = " . $orgID .
            ")" . $crtdByClause . ")";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return 0;
}

function get_One_PrchsDocDocHdr($hdrID) {
    $strSql = "SELECT a.prchs_doc_hdr_id,
       to_char(to_timestamp(a.prchs_doc_date, 'YYYY-MM-DD'), 'DD-Mon-YYYY'),
       created_by,
       sec.get_usr_name(a.created_by),
       a.purchase_doc_num,
       a.purchase_doc_type,
       a.comments_desc,
       a.requisition_id,
       a.supplier_id,
       scm.get_cstmr_splr_name(a.supplier_id),
       a.supplier_site_id,
       scm.get_cstmr_splr_site_name(a.supplier_site_id),
       a.approval_status,
       a.next_aproval_action,
       round(scm.getprchsdocgrndamnt(a.prchs_doc_hdr_id), 2)                  invoice_amount,
       a.payment_terms,
       CASE WHEN a.requisition_id > 0 THEN 'Purchase Requisition' ELSE '' END src_doc_type,
       -1                                                                     pymny_method_id,
       accb.get_pymnt_mthd_name(-1),
       0                                                                      amnt_paid,
       -1                                                                     gl_batch_id,
       accb.get_gl_batch_name(-1),
       ''                                                                     spplrs_doc_num,
       ''                                                                     doc_tmplt_clsfctn,
       a.prntd_doc_curr_id,
       gst.get_pssbl_val(a.prntd_doc_curr_id),
       scm.get_src_doc_num(a.requisition_id, 'Purchase Requisition'),
       exchng_rate,
       po_rec_status,
      CASE WHEN char_length(a.need_by_date)>0 THEN to_char(to_timestamp(a.need_by_date, 'YYYY-MM-DD'), 'DD-Mon-YYYY') ELSE '' END     need_by_date, 
      a.branch_id, org.get_site_code_desc(a.branch_id)
  FROM scm.scm_prchs_docs_hdr a 
  WHERE ((a.prchs_doc_hdr_id = " . $hdrID . "))";
//echo $strSql;
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_PrchsDocDocDet($hdrID) {
    $strSql = "SELECT a.prchs_doc_line_id,
                    a.itm_id,
                    a.quantity,
                    a.unit_price,
                    (a.quantity*a.unit_price) amnt,
                    a.store_id,
                    a.crncy_id,
                    a.src_line_id,
                    (scm.get_pr_line_qty(a.src_line_id)-scm.get_pr_line_usg(a.src_line_id)+a.quantity) pr_qty,
                    scm.get_pr_line_usg(a.src_line_id) qty_in_pos,
                    a.tax_code_id,
                    a.dscnt_code_id,
                    a.extr_chrg_id,
                    b.base_uom_id,
                    inv.get_uom_name(b.base_uom_id),
                    a.alternate_item_name,
                    a.dsply_doc_line_in_rcpt,
                    a.qty_rcvd,
                    a.rqstd_qty_ordrd
             FROM scm.scm_prchs_docs_det a, inv.inv_itm_list b " .
            "WHERE (a.itm_id=b.item_id and a.prchs_doc_hdr_id = " . $hdrID . ") ORDER BY a.prchs_doc_line_id";
    $result = executeSQLNoParams($strSql);
    return $result;
}

function createPrchsDocHdr($orgid, $docNum, $desc, $docTyp, $docdte, $pymntTrms, $spplrID, $siteID, $apprvlSts, $nxtApprvl, $srcDocID,
        $invcCurrID, $exchRate, $needByDte, $brnchID = -1) {
    global $usrID;
    global $brnchLocID;
    if ($brnchID <= 0) {
        $brnchID = $brnchLocID;
    }
    if ($docdte != "") {
        $docdte = cnvrtDMYToYMD($docdte);
    }
    if ($needByDte != "") {
        $needByDte = cnvrtDMYToYMD($needByDte);
    }
    $insSQL = "INSERT INTO scm.scm_prchs_docs_hdr(prchs_doc_date, need_by_date, supplier_id, supplier_site_id, comments_desc,
                                   approval_status, created_by, creation_date, last_update_by, last_update_date,
                                   next_aproval_action,
                                   purchase_doc_num, purchase_doc_type, requisition_id, org_id,
                                   po_rec_status, prntd_doc_curr_id, exchng_rate, payment_terms, payables_accnt_id, branch_id) " .
            "VALUES ('" . loc_db_escape_string($docdte) .
            "', '" . loc_db_escape_string($needByDte) .
            "', " . $spplrID . ", " . $siteID . ", '" . loc_db_escape_string($desc) .
            "', '" . loc_db_escape_string($apprvlSts) . "', " . $usrID .
            ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), " . $usrID .
            ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), '" . loc_db_escape_string($nxtApprvl) .
            "', '" . loc_db_escape_string($docNum) . "', '"
            . loc_db_escape_string($docTyp) . "', " . $srcDocID . ", " .
            $orgid . ",'', " . $invcCurrID . ", " . $exchRate . ", '" . loc_db_escape_string($pymntTrms) .
            "',-1," . $brnchID . ")";
    execUpdtInsSQL($insSQL);
    $sbmtdScmPrchsDocID = getGnrlRecID("scm.scm_prchs_docs_hdr", "purchase_doc_num", "prchs_doc_hdr_id", $docNum, $orgid);
    if ($srcDocID > 0 && $sbmtdScmPrchsDocID > 0) {
        $insSQL = "INSERT INTO scm.scm_prchs_docs_det(prchs_doc_hdr_id, itm_id, quantity, unit_price, created_by,
                                   creation_date, last_update_by, last_update_date, store_id, crncy_id, qty_rcvd,
                                   rqstd_qty_ordrd, src_line_id, dsply_doc_line_in_rcpt, alternate_item_name,
                                   tax_code_id, dscnt_code_id, extr_chrg_id, unit_price_ls_tx) 
                 select " . $sbmtdScmPrchsDocID . ", c.itm_id, (c.quantity-coalesce(c.rqstd_qty_ordrd,0)),c.unit_price, " . $usrID . ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), " . $usrID .
                ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), c.store_id ,c.crncy_id,0, 0, c.prchs_doc_line_id, '1',c.alternate_item_name,c.tax_code_id,c.dscnt_code_id,c.extr_chrg_id,c.unit_price_ls_tx
                from scm.scm_prchs_docs_det c where (c.prchs_doc_hdr_id = " . $srcDocID . " and (c.quantity-coalesce(c.rqstd_qty_ordrd,0))>0) ORDER BY c.prchs_doc_line_id";
        execUpdtInsSQL($insSQL);
    }
    return $sbmtdScmPrchsDocID;
}

function updtPrchsDocHdr($docid, $docNum, $desc, $docTyp, $docdte, $pymntTrms, $spplrID, $siteID, $apprvlSts, $nxtApprvl, $srcDocID,
        $invcCurrID, $exchRate, $needByDte, $brnchID = -1) {
    global $usrID;
    global $brnchLocID;
    if ($brnchID <= 0) {
        $brnchID = $brnchLocID;
    }
    if ($docdte != "") {
        $docdte = cnvrtDMYToYMD($docdte);
    }
    if ($needByDte != "") {
        $needByDte = cnvrtDMYToYMD($needByDte);
    }
    $updtSQL = "UPDATE scm.scm_prchs_docs_hdr
                SET prchs_doc_date='" . loc_db_escape_string($docdte) . "',
                    need_by_date='" . loc_db_escape_string($needByDte) . "',
                    supplier_id=" . $spplrID . ",
                    supplier_site_id=" . $siteID . ",
                    comments_desc='" . loc_db_escape_string($desc) . "',
                    approval_status='" . loc_db_escape_string($apprvlSts) . "',
                    last_update_by=" . $usrID . ",
                    last_update_date=to_char(now(),'YYYY-MM-DD HH24:MI:SS'),
                    next_aproval_action='" . loc_db_escape_string($nxtApprvl) . "',
                    purchase_doc_num='" . loc_db_escape_string($docNum) . "',
                    purchase_doc_type='" . loc_db_escape_string($docTyp) . "',
                    requisition_id=" . $srcDocID . ",
                    prntd_doc_curr_id=" . $invcCurrID . ",
                    exchng_rate=" . $exchRate . ",
                    payment_terms='" . loc_db_escape_string($pymntTrms) . "',
                    branch_id=" . $brnchID . "
                WHERE (prchs_doc_hdr_id = " . $docid . ")";
    return execUpdtInsSQL($updtSQL);
}

function createPrchsDocLn($docID, $itmID, $qty, $untPrice, $storeID, $crncyID, $srclnID, $altrntNm, $tax_code_id, $dscnt_code_id,
        $extr_chrg_id) {
    global $usrID;
    $insSQL = "INSERT INTO scm.scm_prchs_docs_det(prchs_doc_hdr_id, itm_id, quantity, unit_price, created_by,
                                   creation_date, last_update_by, last_update_date, store_id, crncy_id, qty_rcvd,
                                   rqstd_qty_ordrd, src_line_id, dsply_doc_line_in_rcpt, alternate_item_name, 
                                   tax_code_id, dscnt_code_id, extr_chrg_id, unit_price_ls_tx) " .
            "VALUES (" . $docID .
            ", " . $itmID .
            ", " . $qty . ", " . $untPrice . ", " . $usrID .
            ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), " . $usrID .
            ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), " . $storeID .
            ", " . $crncyID . ",0,0, " . $srclnID . ", '1','" . loc_db_escape_string($altrntNm) .
            "', " . $tax_code_id . ", " . $dscnt_code_id . ", " . $extr_chrg_id . ", scm.get_sllng_price_lesstax(" . $tax_code_id . ", " . $untPrice . "))";
    return execUpdtInsSQL($insSQL);
}

function updatePrchsDocLn($lnID, $docID, $itmID, $qty, $untPrice, $storeID, $crncyID, $srclnID, $altrntNm, $tax_code_id, $dscnt_code_id,
        $extr_chrg_id) {
    global $usrID;
    $updtSQL = "UPDATE scm.scm_prchs_docs_det
                    SET prchs_doc_hdr_id=" . $docID . ",
                        itm_id=" . $itmID . ",
                        quantity=" . $qty . ",
                        unit_price=" . $untPrice . ",
                        last_update_by=" . $usrID . ",
                        last_update_date=to_char(now(),'YYYY-MM-DD HH24:MI:SS'),
                        store_id=" . $storeID . ",
                        crncy_id=" . $crncyID . ",
                        src_line_id=" . $srclnID . ",
                        alternate_item_name='" . loc_db_escape_string($altrntNm) . "',
                        tax_code_id=" . $tax_code_id . ",
                        dscnt_code_id=" . $dscnt_code_id . ",
                        extr_chrg_id=" . $extr_chrg_id . ", unit_price_ls_tx=scm.get_sllng_price_lesstax(" . $tax_code_id . ", " . $untPrice . ")
                    WHERE (prchs_doc_line_id = " . $lnID . ")";
    return execUpdtInsSQL($updtSQL);
}

function deletePrchsDocHdrNDet($valLnid, $docNum) {
    $trnsCnt1 = 0;
    $docStatus = getGnrlRecNm("scm.scm_prchs_docs_hdr", "prchs_doc_hdr_id", "approval_status", $valLnid);
    if ($docStatus == "Approved" || $docStatus == "Initiated" || $docStatus == "Validated" || $docStatus == "Cancelled" || strpos($docStatus,
                    "Reviewed") !== FALSE) {
        $trnsCnt1 = 1;
    }
    if ($trnsCnt1 > 0) {
        $dsply = "No Record Deleted<br/>Cannot DELETE Approved, Initiated, Reviewed, Validated, Cancelled Documents!";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
    $delSQL = "DELETE FROM scm.scm_prchs_doc_attchmnts WHERE doc_hdr_id = " . $valLnid;
    $affctd3 = execUpdtInsSQL($delSQL, "Document Number = " . $docNum);
    $delSQL = "DELETE FROM scm.scm_prchs_docs_det WHERE prchs_doc_hdr_id = " . $valLnid;
    $affctd2 = execUpdtInsSQL($delSQL, "Document Number = " . $docNum);
    $delSQL = "DELETE FROM scm.scm_prchs_docs_hdr WHERE prchs_doc_hdr_id = " . $valLnid;
    $affctd1 = execUpdtInsSQL($delSQL, "Document Number = " . $docNum);
    if ($affctd1 > 0) {
        $dsply = "";
        $dsply .= "<br/>Successfully Executed the ff-";
        $dsply .= "<br/>Deleted $affctd2 Document Line(s)!";
        $dsply .= "<br/>Deleted $affctd1 Document(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Deleted";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function deletePrchsDocLine($valLnid, $docNum) {
    $trnsCnt1 = 0;
    $docHdrID = (float) getGnrlRecNm("scm.scm_prchs_docs_det", "prchs_doc_line_id", "prchs_doc_hdr_id", $valLnid);
    $docStatus = getGnrlRecNm("scm.scm_prchs_docs_hdr", "prchs_doc_hdr_id", "approval_status", $docHdrID);
    if ($docStatus == "Approved" || $docStatus == "Initiated" || $docStatus == "Validated" || $docStatus == "Cancelled" || strpos($docStatus,
                    "Reviewed") !== FALSE) {
        $trnsCnt1 = 1;
    }
    if ($trnsCnt1 > 0) {
        $dsply = "No Record Deleted<br/>Cannot DELETE Approved, Initiated, Reviewed, Validated, Cancelled Documents!";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
    $delSQL = "DELETE FROM scm.scm_prchs_docs_det WHERE prchs_doc_line_id = " . $valLnid;
    $affctd2 = execUpdtInsSQL($delSQL, "Document Number = " . $docNum);
    if ($affctd2 > 0) {
        $dsply = "";
        $dsply .= "<br/>Successfully Executed the ff-";
        $dsply .= "<br/>Deleted $affctd2 Document Line(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Deleted";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function get_Basic_CnsgnRcpt($searchWord, $searchIn, $offset, $limit_size, $orgID, $shwUnpstdOnly, $shwUnpaidOnly) {
    execUpdtInsSQL("UPDATE inv.inv_consgmt_rcpt_hdr SET doc_curr_id=org.get_orgfunc_crncy_id(" . $orgID . ") WHERE doc_curr_id<=0");
    global $vwOnlySelf;
    global $usrID;
    global $fnccurnm;
    $strSql = "";
    $whereClause = "";
    $crtdByClause = "";
    if ($vwOnlySelf === true) {
        $crtdByClause = " AND (created_by=" . $usrID . ")";
    }
    if ($searchIn == "Document Number") {
        $whereClause = "(((CASE WHEN char_length(coalesce(a.rcpt_number,''))<=0 THEN ''||a.rcpt_id ELSE coalesce(a.rcpt_number,'') END)||' ['||scm.get_src_doc_num(coalesce(a.po_id,-1),'Purchase')||']') ilike '" . loc_db_escape_string($searchWord) .
                "') AND ";
    } else if ($searchIn == "Document Description") {
        $whereClause = "(a.description ilike '" . loc_db_escape_string($searchWord) .
                "') AND ";
    } else if ($searchIn == "Supplier Name") {
        $whereClause = "(a.supplier_id IN (select c.cust_sup_id from scm.scm_cstmr_suplr c where c.cust_sup_name ilike '" . loc_db_escape_string($searchWord) .
                "')) AND ";
    } else if ($searchIn == "Approval Status") {
        $whereClause = "(a.approval_status ilike '" . loc_db_escape_string($searchWord) .
                "') AND ";
    } else if ($searchIn == "Created By") {
        $whereClause = "(a.created_by IN (select c.user_id from sec.sec_users c where c.user_name ilike '" . loc_db_escape_string($searchWord) .
                "')) AND ";
    }
    $strSql = "SELECT a.rcpt_id, (CASE WHEN char_length(coalesce(a.rcpt_number,''))<=0 THEN ''||a.rcpt_id ELSE coalesce(a.rcpt_number,'') END) ||' ['||scm.get_src_doc_num(coalesce(a.po_id,-1),'Purchase')||']', 
        to_char(to_timestamp(a.date_received,'YYYY-MM-DD'),'DD-Mon-YYYY'),
        description,gst.get_pssbl_val(a.doc_curr_id), round(scm.getcnsgnrcptgrndamnt(a.rcpt_id),2), 
        a.approval_status, scm.get_cstmr_splr_name(a.supplier_id), a.received_by, coalesce(a.po_id,-1) " .
            "FROM inv.inv_consgmt_rcpt_hdr a " .
            "WHERE (" . $whereClause . "(a.org_id = " . $orgID .
            ")" . $crtdByClause . ") ORDER BY a.rcpt_id DESC LIMIT " . $limit_size .
            " OFFSET " . (abs($offset * $limit_size));
//echo $strSql;a.doc_curr_id invc_curr_id, gst.get_pssbl_val(a.doc_curr_id)
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_Total_CnsgnRcpt($searchWord, $searchIn, $orgID, $shwUnpstdOnly, $shwUnpaidOnly) {
    global $vwOnlySelf;
    global $usrID;
    $strSql = "";
    $whereClause = "";
    $crtdByClause = "";
    if ($vwOnlySelf === true) {
        $crtdByClause = " AND (created_by=" . $usrID . ")";
    }
    if ($searchIn == "Document Number") {
        $whereClause = "(((CASE WHEN char_length(coalesce(a.rcpt_number,''))<=0 THEN ''||a.rcpt_id ELSE coalesce(a.rcpt_number,'') END)||' ['||scm.get_src_doc_num(coalesce(a.po_id,-1),'Purchase')||']') ilike '" . loc_db_escape_string($searchWord) .
                "') AND ";
    } else if ($searchIn == "Document Description") {
        $whereClause = "(a.description ilike '" . loc_db_escape_string($searchWord) .
                "') AND ";
    } else if ($searchIn == "Supplier Name") {
        $whereClause = "(a.supplier_id IN (select c.cust_sup_id from scm.scm_cstmr_suplr c where c.cust_sup_name ilike '" . loc_db_escape_string($searchWord) .
                "')) AND ";
    } else if ($searchIn == "Approval Status") {
        $whereClause = "(a.approval_status ilike '" . loc_db_escape_string($searchWord) .
                "') AND ";
    } else if ($searchIn == "Created By") {
        $whereClause = "(a.created_by IN (select c.user_id from sec.sec_users c where c.user_name ilike '" . loc_db_escape_string($searchWord) .
                "')) AND ";
    }
    $strSql = "SELECT count(1) " .
            "FROM inv.inv_consgmt_rcpt_hdr a " .
            "WHERE (" . $whereClause . "(a.org_id = " . $orgID .
            ")" . $crtdByClause . ")";
//echo $strSql;
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return 0;
}

function get_One_CnsgnRcptDocHdr($hdrID) {
    $strSql = "SELECT a.rcpt_id, to_char(to_timestamp(a.date_received, 'YYYY-MM-DD'), 'DD-Mon-YYYY'), 
       created_by, sec.get_usr_name(a.created_by), coalesce(a.rcpt_number,''||a.rcpt_id), 
       (CASE WHEN coalesce(a.po_id,-1)>0 THEN 'Purchase Order Receipt' ELSE 'Miscellaneous Receipt' END) rcpt_type, 
       a.description, a.po_id, a.supplier_id, scm.get_cstmr_splr_name(a.supplier_id),
       a.site_id, scm.get_cstmr_splr_site_name(a.site_id), 
       a.approval_status, a.next_approval_status, round(scm.getcnsgnrcptgrndamnt(a.rcpt_id),2) invoice_amount, 
       '' payment_terms, 'Purchase Order' src_doc_type, -1 pymny_method_id, '' pymnt_mthd_name, 
       0 amnt_paid, -1 gl_batch_id, '' gl_batch_name,
       '' cstmrs_doc_num, '' doc_tmplt_clsfctn, a.doc_curr_id invc_curr_id, gst.get_pssbl_val(a.doc_curr_id),
       -1 event_rgstr_id, '' evnt_cost_category, '' event_doc_type, a.payables_accnt_id,
       accb.get_accnt_num(a.payables_accnt_id) || '.' || accb.get_accnt_name(a.payables_accnt_id) balancing_accnt,
       accb.is_gl_batch_pstd(-1) is_pstd, 
       scm.getRcptDocPyblID(a.rcpt_id,'Goods/Services Receipt') pyblsInvcID,
       scm.get_scmpyblsdochdrnum(a.rcpt_id, 'Goods/Services Receipt', a.org_id), 
       accb.get_src_doc_type(scm.getRcptDocPyblID(a.rcpt_id, 'Goods/Services Receipt'),'Supplier'),
       scm.get_src_doc_num(a.po_id, 'Purchase Order'), a.exchng_rate
  FROM inv.inv_consgmt_rcpt_hdr a
  WHERE ((a.rcpt_id = " . $hdrID . "))";
    //, 
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_CnsgnRcptDocDet($hdrID, $lmtSze = 50) {
    $docStatus = getGnrlRecNm("inv.inv_consgmt_rcpt_hdr", "rcpt_id", "approval_status", $hdrID);
    $docExchRate = (float) getGnrlRecNm("inv.inv_consgmt_rcpt_hdr", "rcpt_id", "exchng_rate", $hdrID);
    $strSql = "";
    if ($docStatus == "Received" || $docStatus == "Cancelled") {
        $strSql = "select c.line_id , c.itm_id, inv.get_invitm_name(c.itm_id::INTEGER), c.quantity_rcvd, (c.cost_price/" . $docExchRate . "), 
                c.po_line_id, c.subinv_id, inv.get_store_name(c.subinv_id), c.stock_id, 
                CASE WHEN c.expiry_date= '' THEN c.expiry_date ELSE to_char(to_timestamp(c.expiry_date,'YYYY-MM-DD'),'DD-Mon-YYYY') END, 
                CASE WHEN c.manfct_date= '' THEN c.manfct_date ELSE to_char(to_timestamp(c.manfct_date,'YYYY-MM-DD'),'DD-Mon-YYYY') END,  
                c.lifespan, c.tag_number, c.serial_number, c.consignmt_condition, 
                c.remarks, c.consgmt_id, d.base_uom_id, inv.get_uom_name(d.base_uom_id)
                from inv.inv_consgmt_rcpt_det c, inv.inv_itm_list d where (c.itm_id=d.item_id and c.rcpt_id = " . $hdrID .
                ") ORDER BY c.line_id LIMIT " . $lmtSze . " OFFSET 0";
    } else {
        $strSql = "select c.s_line_id , c.s_itm_id, inv.get_invitm_name(c.s_itm_id::INTEGER), c.s_quantity_rcvd, c.s_cost_price, 
                c.s_po_line_id, c.s_subinv_id, inv.get_store_name(c.s_subinv_id), c.s_stock_id, 
                CASE WHEN c.s_expiry_date= '' THEN c.s_expiry_date ELSE to_char(to_timestamp(c.s_expiry_date,'YYYY-MM-DD'),'DD-Mon-YYYY') END, 
                CASE WHEN c.s_manfct_date= '' THEN c.s_manfct_date ELSE to_char(to_timestamp(c.s_manfct_date,'YYYY-MM-DD'),'DD-Mon-YYYY') END,  
                c.s_lifespan, c.s_tag_number, c.s_serial_number, c.s_consignmt_condition, 
                c.s_remarks, c.s_consgmt_id, d.base_uom_id, inv.get_uom_name(d.base_uom_id)
                from inv.inv_svd_consgmt_rcpt_det c, inv.inv_itm_list d where (c.s_itm_id=d.item_id and c.s_rcpt_id = " . $hdrID .
                ") ORDER BY c.s_line_id LIMIT " . $lmtSze . " OFFSET 0";
    }
    //echo $strSql;
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_Basic_CnsgnRtrn($searchWord, $searchIn, $offset, $limit_size, $orgID, $shwUnpstdOnly, $shwUnpaidOnly) {
    execUpdtInsSQL("UPDATE inv.inv_consgmt_rcpt_rtns_hdr SET doc_curr_id=org.get_orgfunc_crncy_id(" . $orgID . ") WHERE doc_curr_id<=0");
    global $vwOnlySelf;
    global $usrID;
    global $fnccurnm;
    $strSql = "";
    $whereClause = "";
    $crtdByClause = "";
    if ($vwOnlySelf === true) {
        $crtdByClause = " AND (created_by=" . $usrID . ")";
    }
    if ($searchIn == "Document Number") {
        $whereClause = "((a.rcpt_rtns_id||' ['||scm.get_src_doc_num(coalesce(a.rcpt_id,-1),'Receipt')||']') ilike '" . loc_db_escape_string($searchWord) .
                "') AND ";
    } else if ($searchIn == "Document Description") {
        $whereClause = "(a.description ilike '" . loc_db_escape_string($searchWord) .
                "') AND ";
    } else if ($searchIn == "Supplier Name") {
        $whereClause = "(a.supplier_id IN (select c.cust_sup_id from scm.scm_cstmr_suplr c where c.cust_sup_name ilike '" . loc_db_escape_string($searchWord) .
                "')) AND ";
    } else if ($searchIn == "Approval Status") {
        $whereClause = "(a.approval_status ilike '" . loc_db_escape_string($searchWord) .
                "') AND ";
    } else if ($searchIn == "Created By") {
        $whereClause = "(a.created_by IN (select c.user_id from sec.sec_users c where c.user_name ilike '" . loc_db_escape_string($searchWord) .
                "')) AND ";
    }

    $strSql = "SELECT a.rcpt_rtns_id, a.rcpt_rtns_id||' [RCPT NO.:'||scm.get_src_doc_num(coalesce(a.rcpt_id,-1),'Receipt')||']', 
        to_char(to_timestamp(a.date_returned,'YYYY-MM-DD'),'DD-Mon-YYYY'),
        description,gst.get_pssbl_val(a.doc_curr_id), round(scm.getcnsgnrtrngrndamnt(a.rcpt_rtns_id),2), 
        a.approval_status, scm.get_cstmr_splr_name(a.supplier_id), a.returned_by, coalesce(a.rcpt_id,-1) " .
            "FROM inv.inv_consgmt_rcpt_rtns_hdr a " .
            "WHERE (" . $whereClause . "(a.org_id = " . $orgID .
            ")" . $crtdByClause . ") ORDER BY a.rcpt_rtns_id DESC LIMIT " . $limit_size .
            " OFFSET " . (abs($offset * $limit_size));
//echo $strSql;
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_Total_CnsgnRtrn($searchWord, $searchIn, $orgID, $shwUnpstdOnly, $shwUnpaidOnly) {
    global $vwOnlySelf;
    global $usrID;
    $strSql = "";
    $whereClause = "";
    $crtdByClause = "";
    if ($vwOnlySelf === true) {
        $crtdByClause = " AND (created_by=" . $usrID . ")";
    }
    if ($searchIn == "Document Number") {
        $whereClause = "((a.rcpt_rtns_id||' ['||scm.get_src_doc_num(coalesce(a.rcpt_id,-1),'Receipt')||']') ilike '" . loc_db_escape_string($searchWord) .
                "') AND ";
    } else if ($searchIn == "Document Description") {
        $whereClause = "(a.description ilike '" . loc_db_escape_string($searchWord) .
                "') AND ";
    } else if ($searchIn == "Supplier Name") {
        $whereClause = "(a.supplier_id IN (select c.cust_sup_id from scm.scm_cstmr_suplr c where c.cust_sup_name ilike '" . loc_db_escape_string($searchWord) .
                "')) AND ";
    } else if ($searchIn == "Approval Status") {
        $whereClause = "(a.approval_status ilike '" . loc_db_escape_string($searchWord) .
                "') AND ";
    } else if ($searchIn == "Created By") {
        $whereClause = "(a.created_by IN (select c.user_id from sec.sec_users c where c.user_name ilike '" . loc_db_escape_string($searchWord) .
                "')) AND ";
    }

    $strSql = "SELECT count(1) " .
            "FROM inv.inv_consgmt_rcpt_rtns_hdr a " .
            "WHERE (" . $whereClause . "(a.org_id = " . $orgID .
            ")" . $crtdByClause . ")";
//echo $strSql;
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return 0;
}

function get_One_CnsgnRtrnDocHdr($hdrID) {
    $strSql = "SELECT a.rcpt_rtns_id, to_char(to_timestamp(a.date_returned,'YYYY-MM-DD'),'DD-Mon-YYYY'), 
       created_by, sec.get_usr_name(a.created_by), coalesce(a.rcpt_number,''||a.rcpt_rtns_id), 
       'Receipt Returns' rcpt_type, a.description, a.rcpt_id, a.supplier_id, scm.get_cstmr_splr_name(a.supplier_id),
       a.site_id, scm.get_cstmr_splr_site_name(a.site_id), 
       a.approval_status, a.next_approval_status, round(scm.getcnsgnrtrngrndamnt(a.rcpt_rtns_id),2) invoice_amount, 
       '' payment_terms, 'Receipt' src_doc_type, -1 pymny_method_id, '' pymnt_mthd_name, 
       0 amnt_paid, -1 gl_batch_id, '' gl_batch_name,
       '' cstmrs_doc_num, '' doc_tmplt_clsfctn, a.doc_curr_id invc_curr_id, gst.get_pssbl_val(a.doc_curr_id),
       -1 event_rgstr_id, '' evnt_cost_category, '' event_doc_type, a.payables_accnt_id,
       accb.get_accnt_num(a.payables_accnt_id) || '.' || accb.get_accnt_name(a.payables_accnt_id) balancing_accnt,
       accb.is_gl_batch_pstd(-1) is_pstd, 
       scm.getRcptDocPyblID(a.rcpt_rtns_id,'Goods/Services Receipt Return') pyblsInvcID,
       scm.get_scmpyblsdochdrnum(a.rcpt_rtns_id, 'Goods/Services Receipt Return', a.org_id), 
       accb.get_src_doc_type(scm.getRcptDocPyblID(a.rcpt_rtns_id, 'Goods/Services Receipt Return'),'Supplier'), a.exchng_rate
  FROM inv.inv_consgmt_rcpt_rtns_hdr a
  WHERE ((a.rcpt_rtns_id = " . $hdrID . "))";
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_CnsgnRtrnDocDet($hdrID) {
    $docStatus = getGnrlRecNm("inv.inv_consgmt_rcpt_rtns_hdr", "rcpt_rtns_id", "approval_status", $hdrID);
    $docExchRate = (float) getGnrlRecNm("inv.inv_consgmt_rcpt_rtns_hdr", "rcpt_rtns_id", "exchng_rate", $hdrID);
    $strSql = "";
    if ($docStatus == "Returned" || $docStatus == "Cancelled") {
        $strSql = "select c.line_id , c.itm_id, inv.get_invitm_name(c.itm_id::INTEGER), c.qty_rtnd, (e.cost_price/" . $docExchRate . "), 
                c.rcpt_line_id, c.subinv_id, inv.get_store_name(c.subinv_id), c.stock_id, 
                c.rtnd_reason, c.remarks, c.consgmt_id, d.base_uom_id, inv.get_uom_name(d.base_uom_id), (e.quantity_rcvd-coalesce(e.qty_rtrnd,0))
                from inv.inv_consgmt_rcpt_rtns_det c, inv.inv_itm_list d, inv.inv_consgmt_rcpt_det e
                where (c.itm_id=d.item_id and c.rcpt_line_id=e.line_id and c.rtns_hdr_id = " . $hdrID . ") ORDER BY c.line_id";
    } else {
        $strSql = "select c.s_line_id , c.s_itm_id, inv.get_invitm_name(c.s_itm_id::INTEGER), c.s_qty_rtnd, (e.cost_price/" . $docExchRate . "), 
                c.s_rcpt_line_id, c.s_subinv_id, inv.get_store_name(c.s_subinv_id), c.s_stock_id, 
                c.s_rtnd_reason, c.s_remarks, c.s_consgmt_id, d.base_uom_id, inv.get_uom_name(d.base_uom_id), (e.quantity_rcvd-coalesce(e.qty_rtrnd,0))
                from inv.inv_svd_consgmt_rcpt_rtns_det c, inv.inv_itm_list d, inv.inv_consgmt_rcpt_det e
                where (c.s_itm_id=d.item_id and c.s_rcpt_line_id=e.line_id and c.s_rtns_hdr_id = " . $hdrID . ") ORDER BY c.s_line_id";
    }
    $result = executeSQLNoParams($strSql);
    return $result;
}

function createCnsgnRtrnHdr($orgid, $docNum, $desc, $docdte, $spplrID, $spplrSiteID, $apprvlSts, $nxtApprvl, $rcptDocID, $pyblAccntID, $docCurID, $docExchRate) {
    global $usrID;
    if ($docdte != "") {
        $docdte = cnvrtDMYToYMD($docdte);
    }
    $insSQL = "INSERT INTO inv.inv_consgmt_rcpt_rtns_hdr(rcpt_id, date_returned, returned_by, supplier_id, created_by, creation_date, last_update_by, last_update_date, 
                                     approval_status, next_approval_status, description, org_id,
                                     site_id, rcpt_number, payables_accnt_id, doc_curr_id, exchng_rate) " .
            "VALUES (" . $rcptDocID . ", '" . loc_db_escape_string($docdte) .
            "', " . $usrID .
            ", " . $spplrID . ", " . $usrID .
            ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), " . $usrID .
            ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), '" . loc_db_escape_string($apprvlSts) . "', '" . loc_db_escape_string($nxtApprvl) .
            "', '" . loc_db_escape_string($desc) .
            "', " . $orgid . ", " . $spplrSiteID . ", '" . loc_db_escape_string($docNum) . "', " . $pyblAccntID .
            ", " . $docCurID .
            ", " . $docExchRate .
            ")";
    execUpdtInsSQL($insSQL);
    $sbmtdScmCnsgnRtrnID = (float) getGnrlRecID("inv.inv_consgmt_rcpt_rtns_hdr", "rcpt_number", "rcpt_rtns_id", $docNum, $orgid);
    if ($rcptDocID > 0 && $sbmtdScmCnsgnRtrnID > 0) {
        $insSQL = "INSERT INTO inv.inv_svd_consgmt_rcpt_rtns_det(s_consgmt_id, s_stock_id, s_qty_rtnd, s_created_by, s_creation_date, s_last_update_by, s_last_update_date,
                                         s_rcpt_line_id, s_rtnd_reason, s_remarks, s_itm_id, s_subinv_id, s_rtns_hdr_id) 
                 select c.consgmt_id, c.stock_id, (c.quantity_rcvd-coalesce(c.qty_rtrnd,0)), " . $usrID . ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), " . $usrID .
                ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), c.line_id ,'Wrong Receipt','', c.itm_id, c.subinv_id, " . $sbmtdScmCnsgnRtrnID . " 
                from inv.inv_consgmt_rcpt_det c where (c.rcpt_id = " . $rcptDocID . " and (c.quantity_rcvd-coalesce(c.qty_rtrnd,0))>0) ORDER BY c.line_id";
        execUpdtInsSQL($insSQL);
    }
    return $sbmtdScmCnsgnRtrnID;
}

function updtCnsgnRtrnHdr($docid, $docNum, $desc, $docdte, $spplrID, $spplrSiteID, $apprvlSts, $nxtApprvl, $rcptDocID, $pyblAccntID, $docCurID, $docExchRate) {
    global $usrID;
    if ($docdte != "") {
        $docdte = cnvrtDMYToYMD($docdte);
    }
//rcpt_id=" . $rcptDocID . ",

    $updtSQL = "UPDATE inv.inv_consgmt_rcpt_rtns_hdr
                    SET date_returned='" . loc_db_escape_string($docdte) . "',
                        returned_by=" . $usrID . ",
                        supplier_id=" . $spplrID . ",
                        last_update_by=" . $usrID . ",
                        last_update_date=to_char(now(),'YYYY-MM-DD HH24:MI:SS'),
                        approval_status='" . loc_db_escape_string($apprvlSts) . "',
                        next_approval_status='" . loc_db_escape_string($nxtApprvl) . "',
                        description='" . loc_db_escape_string($desc) . "',
                        site_id=" . $spplrSiteID . ",
                        rcpt_number='" . loc_db_escape_string($docNum) . "',
                        payables_accnt_id=" . $pyblAccntID . ",
                        doc_curr_id=" . $docCurID . ",
                        exchng_rate=" . $docExchRate . "
                    WHERE rcpt_rtns_id=" . $docid;
    return execUpdtInsSQL($updtSQL);
}

function createCnsgnRtrnLine($qtyRtrd, $cnsgnID, $rcptDocLnID, $cnsgnCndtn, $rmrks, $itmID, $storeID, $rcptRtrnsHdrID) {
    global $usrID;
    $insSQL = "INSERT INTO inv.inv_svd_consgmt_rcpt_rtns_det(s_consgmt_id, s_stock_id, s_qty_rtnd, s_created_by, s_creation_date, s_last_update_by, s_last_update_date,
                                         s_rcpt_line_id, s_rtnd_reason, s_remarks, s_itm_id, s_subinv_id, s_rtns_hdr_id) " .
            "VALUES (" . $cnsgnID . ", " .
            "inv.getItemStockID(" . $itmID . "," . $storeID . "), " . $qtyRtrd . ", " . $usrID . ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), " . $usrID .
            ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), " . $rcptDocLnID .
            ", '" . loc_db_escape_string($cnsgnCndtn) . "', '" . loc_db_escape_string($rmrks) . "', " . $itmID . ", " . $storeID . ", " . $rcptRtrnsHdrID . ")";
    return execUpdtInsSQL($insSQL);
}

function updtCnsgnRtrnLine($lineID, $qtyRtrd, $cnsgnID, $rcptDocLnID, $cnsgnCndtn, $rmrks, $itmID, $storeID, $rcptRtrnsHdrID) {

    global $usrID;
    $updtSQL = "UPDATE inv.inv_svd_consgmt_rcpt_rtns_det
                    SET s_consgmt_id=" . $cnsgnID . ",
                        s_stock_id=inv.getItemStockID(" . $itmID . "," . $storeID . "),
                        s_qty_rtnd=" . $qtyRtrd . ",
                        s_last_update_by=" . $usrID . ",
                        s_last_update_date=to_char(now(),'YYYY-MM-DD HH24:MI:SS'),
                        s_rcpt_line_id=" . $rcptDocLnID . ",
                        s_rtnd_reason='" . loc_db_escape_string($cnsgnCndtn) . "',
                        s_remarks='" . loc_db_escape_string($rmrks) . "',
                        s_itm_id=" . $itmID . ",
                        s_subinv_id=" . $storeID . ",
                        s_rtns_hdr_id=" . $rcptRtrnsHdrID . "
                    WHERE s_line_id=" . $lineID;
    return execUpdtInsSQL($updtSQL);
}

function deleteCnsgnRtrnHdrNDet($valLnid, $docNum) {
    $trnsCnt1 = 0;
    $docStatus = getGnrlRecNm("inv.inv_consgmt_rcpt_rtns_hdr", "rcpt_rtns_id", "approval_status", $valLnid);
    $docType = "Receipt Returns";
    $docTypeLnsDlvrd = (int) getGnrlRecNm("inv.inv_consgmt_rcpt_rtns_det", "rtns_hdr_id", "count(line_id)", $valLnid);
    if ($docTypeLnsDlvrd > 0 || $docStatus == "Received" || $docStatus == "Initiated" || $docStatus == "Validated" || $docStatus == "Cancelled" || strpos($docStatus, "Reviewed") !== FALSE) {
        $trnsCnt1 = 1;
    }
    if ($trnsCnt1 > 0) {
        $dsply = "No Record Deleted<br/>Cannot DELETE Approved, Initiated, Reviewed, Validated, Cancelled Documents or lines Delivered Already!!";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
    $delSQL = "DELETE FROM scm.scm_gl_interface WHERE src_doc_id = " . $valLnid . " and src_doc_typ='" . loc_db_escape_string($docType) . "'";
    $affctd4 = execUpdtInsSQL($delSQL, "Document Number = " . $docNum);
    $delSQL = "DELETE FROM inv.inv_doc_attchmnts WHERE doc_hdr_id = " . $valLnid . " and doc_hdr_type='" . loc_db_escape_string($docType) . "'";
    $affctd3 = execUpdtInsSQL($delSQL, "Document Number = " . $docNum);
    $delSQL = "DELETE FROM inv.inv_svd_consgmt_rcpt_rtns_det WHERE s_rtns_hdr_id = " . $valLnid;
    $affctd2 = execUpdtInsSQL($delSQL, "Document Number = " . $docNum);
    $delSQL = "DELETE FROM inv.inv_consgmt_rcpt_rtns_hdr WHERE rcpt_rtns_id = " . $valLnid;
    $affctd1 = execUpdtInsSQL($delSQL, "Document Number = " . $docNum);
    if ($affctd1 > 0) {
        $dsply = "";
        $dsply .= "<br/>Successfully Executed the ff-";
        $dsply .= "<br/>Deleted $affctd2 Document Line(s)!";
        $dsply .= "<br/>Deleted $affctd1 Document(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Deleted";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function deleteCnsgnRtrnLine($valLnid, $docNum) {
    $trnsCnt1 = 0;
    $docHdrID = (float) getGnrlRecNm("inv.inv_svd_consgmt_rcpt_rtns_det", "s_line_id", "s_rtns_hdr_id", $valLnid);
    if ($docHdrID <= 0) {
        $dsply = "No Record Deleted<br/>Cannot DELETE Approved, Initiated, Reviewed, Validated, Cancelled Documents or lines Delivered Already!!";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
    $docStatus = getGnrlRecNm("inv.inv_consgmt_rcpt_rtns_hdr", "rcpt_rtns_id", "approval_status", $docHdrID);
    $docTypeLnsDlvrd = (int) getGnrlRecNm("inv.inv_consgmt_rcpt_rtns_det", "rtns_hdr_id", "count(line_id)", $valLnid);
    if ($docTypeLnsDlvrd > 0 || $docStatus == "Received" || $docStatus == "Initiated" || $docStatus == "Validated" || $docStatus == "Cancelled" || strpos($docStatus, "Reviewed") !== FALSE) {
        $trnsCnt1 = 1;
    }
    if ($trnsCnt1 > 0) {
        $dsply = "No Record Deleted<br/>Cannot DELETE Approved, Initiated, Reviewed, Validated, Cancelled Documents or lines Delivered Already!!";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
    $delSQL = "DELETE FROM inv.inv_svd_consgmt_rcpt_rtns_det WHERE s_line_id = " . $valLnid;
    $affctd1 = execUpdtInsSQL($delSQL, "Document Number = " . $docNum);
    if ($affctd1 > 0) {
        $dsply = "";
        $dsply .= "<br/>Successfully Executed the ff-";
        $dsply .= "<br/>Deleted $affctd1 Document Line(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Deleted";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function get_Basic_StockTrnsfr($searchWord, $searchIn, $offset, $limit_size, $orgID, $shwUnpstdOnly, $shwUnpaidOnly) {
    global $vwOnlySelf;
    global $usrID;
    global $fnccurnm;
    $strSql = "";
    $whereClause = "";
    $crtdByClause = "";
    if ($vwOnlySelf === true) {
        $crtdByClause = " AND (created_by=" . $usrID . ")";
    }
    if ($searchIn == "Document Number") {
        $whereClause = "((a.transfer_hdr_id||'') ilike '" . loc_db_escape_string($searchWord) .
                "') AND ";
    } else if ($searchIn == "Document Description") {
        $whereClause = "(a.description ilike '" . loc_db_escape_string($searchWord) .
                "') AND ";
    } else if ($searchIn == "Status") {
        $whereClause = "(a.status ilike '" . loc_db_escape_string($searchWord) .
                "') AND ";
    } else if ($searchIn == "Created By") {
        $whereClause = "(a.created_by IN (select c.user_id from sec.sec_users c where c.user_name ilike '" . loc_db_escape_string($searchWord) .
                "')) AND ";
    }

    $strSql = "SELECT a.transfer_hdr_id, (CASE WHEN char_length(coalesce(a.rcpt_number,''))>0 THEN coalesce(a.rcpt_number,'') ELSE ''||a.transfer_hdr_id END), 
        to_char(to_timestamp(a.transfer_date,'YYYY-MM-DD'),'DD-Mon-YYYY'),
        description,'" . loc_db_escape_string($fnccurnm) . "', round(total_amount::NUMERIC,2), 
        a.status, inv.get_store_name(a.source_subinv_id), inv.get_store_name(a.dest_subinv_id) " .
            "FROM inv.inv_stock_transfer_hdr a " .
            "WHERE (" . $whereClause . "(a.org_id = " . $orgID .
            ")" . $crtdByClause . ") ORDER BY a.transfer_hdr_id DESC LIMIT " . $limit_size .
            " OFFSET " . (abs($offset * $limit_size));
//echo $strSql;
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_Total_StockTrnsfr($searchWord, $searchIn, $orgID, $shwUnpstdOnly, $shwUnpaidOnly) {
    global $vwOnlySelf;
    global $usrID;
    $strSql = "";
    $whereClause = "";
    $crtdByClause = "";
    if ($vwOnlySelf === true) {
        $crtdByClause = " AND (created_by=" . $usrID . ")";
    }
    if ($searchIn == "Document Number") {
        $whereClause = "((a.transfer_hdr_id||'') ilike '" . loc_db_escape_string($searchWord) .
                "') AND ";
    } else if ($searchIn == "Document Description") {
        $whereClause = "(a.description ilike '" . loc_db_escape_string($searchWord) .
                "') AND ";
    } else if ($searchIn == "Status") {
        $whereClause = "(a.status ilike '" . loc_db_escape_string($searchWord) .
                "') AND ";
    } else if ($searchIn == "Created By") {
        $whereClause = "(a.created_by IN (select c.user_id from sec.sec_users c where c.user_name ilike '" . loc_db_escape_string($searchWord) .
                "')) AND ";
    }

    $strSql = "SELECT count(1) " .
            "FROM inv.inv_stock_transfer_hdr a " .
            "WHERE (" . $whereClause . "(a.org_id = " . $orgID .
            ")" . $crtdByClause . ")";
//echo $strSql;
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return 0;
}

function get_One_StockTrnsfrDocHdr($hdrID) {
    $strSql = "SELECT a.transfer_hdr_id, 
        to_char(to_timestamp(a.transfer_date,'YYYY-MM-DD'),'DD-Mon-YYYY'), 
       created_by, sec.get_usr_name(a.created_by), 
       (CASE WHEN char_length(coalesce(a.rcpt_number,''))>0 THEN coalesce(a.rcpt_number,'') ELSE ''||a.transfer_hdr_id END), 
       a.description, a.status,a.total_amount,
       a.source_subinv_id, inv.get_store_name(a.source_subinv_id), a.dest_subinv_id, inv.get_store_name(a.dest_subinv_id)       
  FROM inv.inv_stock_transfer_hdr a
  WHERE ((a.transfer_hdr_id = " . $hdrID . "))";
//echo $strSql;
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_StockTrnsfrDocDet($hdrID) {
    $strSql = "SELECT line_id,
                    itm_id, inv.get_invitm_name(itm_id),
                    transfer_qty,
                    (ttl_amount/(CASE WHEN transfer_qty!=0 THEN transfer_qty ELSE 1 END))   unit_price,
                    ttl_amount,
                    src_store_id, inv.get_store_name(a.src_store_id),
                    dest_subinv_id, inv.get_store_name(a.dest_subinv_id),
                    reason,
                    cnsgmnt_nos,
                    b.base_uom_id, inv.get_uom_name(b.base_uom_id),
                    remarks,
                  CASE WHEN scm.get_ltst_stock_avlbl_bals(src_stock_id,to_char(now(),'YYYY-MM-DD'))<=inv.get_invitm_stckttl(a.itm_id, a.src_store_id) THEN scm.get_ltst_stock_avlbl_bals(src_stock_id,to_char(now(),'YYYY-MM-DD')) ELSE inv.get_invitm_stckttl(a.itm_id, a.src_store_id) END,
                   cost_price
             FROM inv.inv_stock_transfer_det a, inv.inv_itm_list b " .
            "WHERE (a.itm_id=b.item_id and a.transfer_hdr_id=" . $hdrID . ") ORDER BY a.line_id";
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_Basic_PrdctCrtn($searchWord, $searchIn, $offset, $limit_size, $orgID, $isDeftn) {
    /* Run Status-R
      Batch Number-R
      Classification-D/R
      Created By-D/R
      Description-D/R
      Process Code/Name-D/R
      Start Date-R */
    $strSql = "";
    $whereClause = "";
    $crtdByClause = "";
    if ($isDeftn == false) {
        if ($searchIn == "Batch Number") {
            $whereClause = "(a.batch_code_num ilike '" . loc_db_escape_string($searchWord) .
                    "') AND ";
        } else if ($searchIn == "Run Status") {
            $whereClause = "(a.process_status ilike '" . loc_db_escape_string($searchWord) .
                    "') AND ";
        } else if ($searchIn == "Start Date") {
            $whereClause = "(to_char(to_timestamp(a.start_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') ilike '" . loc_db_escape_string($searchWord) .
                    "') AND ";
        }
    }

    if ($searchIn == "Description") {
        if ($isDeftn) {
            $whereClause = "(a.process_def_description ilike '" . loc_db_escape_string($searchWord) .
                    "') AND ";
        } else {
            $whereClause = "(a.remarks_desc ilike '" . loc_db_escape_string($searchWord) .
                    "') AND ";
        }
    } else if ($searchIn == "Classification") {
        $whereClause = "((Select b.process_def_clsfctn from scm.scm_process_definition b where b.process_def_id = a.process_def_id) ilike '" . loc_db_escape_string($searchWord) .
                "') AND ";
    } else if ($searchIn == "Process Code/Name") {
        $whereClause = "((Select b.process_def_name from scm.scm_process_definition b where b.process_def_id = a.process_def_id) ilike '" . loc_db_escape_string($searchWord) .
                "') AND ";
    } else if ($searchIn == "Created By") {
        $whereClause = "(a.created_by IN (select c.user_id from sec.sec_users c where c.user_name ilike '" . loc_db_escape_string($searchWord) .
                "')) AND ";
    }

    if ($isDeftn) {
        $strSql = "SELECT a.process_def_id, a.process_def_name, a.process_def_clsfctn, a.process_def_description, a.is_enabled " .
                "FROM scm.scm_process_definition a " .
                "WHERE (" . $whereClause . "(a.org_id = " . $orgID .
                ")" . $crtdByClause . ") ORDER BY a.process_def_id DESC LIMIT " . $limit_size .
                " OFFSET " . (abs($offset * $limit_size));
    } else {
        $strSql = "SELECT a.process_run_id, a.batch_code_num, z.process_def_clsfctn clsftn, a.remarks_desc, a.process_status " .
                "FROM scm.scm_process_run a, scm.scm_process_definition z " .
                "WHERE (" . $whereClause . "(z.org_id = " . $orgID .
                " and z.process_def_id = a.process_def_id)" . $crtdByClause . ") ORDER BY a.process_run_id DESC LIMIT " . $limit_size .
                " OFFSET " . (abs($offset * $limit_size));
    }
//echo $strSql;
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_Total_PrdctCrtn($searchWord, $searchIn, $orgID, $isDeftn) {
    $strSql = "";
    $whereClause = "";
    $crtdByClause = "";
    if ($isDeftn == false) {
        if ($searchIn == "Batch Number") {
            $whereClause = "(a.batch_code_num ilike '" . loc_db_escape_string($searchWord) .
                    "') AND ";
        } else if ($searchIn == "Run Status") {
            $whereClause = "(a.process_status ilike '" . loc_db_escape_string($searchWord) .
                    "') AND ";
        } else if ($searchIn == "Start Date") {
            $whereClause = "(to_char(to_timestamp(a.start_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') ilike '" . loc_db_escape_string($searchWord) .
                    "') AND ";
        }
    }

    if ($searchIn == "Description") {
        if ($isDeftn) {
            $whereClause = "(a.process_def_description ilike '" . loc_db_escape_string($searchWord) .
                    "') AND ";
        } else {
            $whereClause = "(a.remarks_desc ilike '" . loc_db_escape_string($searchWord) .
                    "') AND ";
        }
    } else if ($searchIn == "Classification") {
        $whereClause = "((Select b.process_def_clsfctn from scm.scm_process_definition b where b.process_def_id = a.process_def_id) ilike '" . loc_db_escape_string($searchWord) .
                "') AND ";
    } else if ($searchIn == "Process Code/Name") {
        $whereClause = "((Select b.process_def_name from scm.scm_process_definition b where b.process_def_id = a.process_def_id) ilike '" . loc_db_escape_string($searchWord) .
                "') AND ";
    } else if ($searchIn == "Created By") {
        $whereClause = "(a.created_by IN (select c.user_id from sec.sec_users c where c.user_name ilike '" . loc_db_escape_string($searchWord) .
                "')) AND ";
    }

    if ($isDeftn) {
        $strSql = "SELECT count(1) " .
                "FROM scm.scm_process_definition a " .
                "WHERE (" . $whereClause . "(a.org_id = " . $orgID .
                ")" . $crtdByClause . ")";
    } else {
        $strSql = "SELECT count(1) FROM scm.scm_process_run a, scm.scm_process_definition z " .
                "WHERE (" . $whereClause . "(z.org_id = " . $orgID .
                " and z.process_def_id = a.process_def_id)" . $crtdByClause . ")";
    }
//echo $strSql;
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return 0;
}

function get_One_PrdctCrtnDt($hdrID) {
    $strSql = "SELECT a.prchs_doc_hdr_id, a.purchase_doc_num, 
        a.purchase_doc_type, a.requisition_id, 
      to_char(to_timestamp(a.prchs_doc_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY'), 
      to_char(to_timestamp(a.need_by_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY'), " .
            "a.supplier_id, a.supplier_site_id, a.comments_desc, " .
            "a.approval_status, a.next_aproval_action, " .
            "a.created_by, a.prntd_doc_curr_id, a.exchng_rate, a.payment_terms, "
            . "a.branch_id, org.get_site_code_desc(a.branch_id)  " .
            "FROM scm.scm_prchs_docs_hdr a " .
            "WHERE(a.prchs_doc_hdr_id = " . $hdrID .
            ") ORDER BY a.purchase_doc_type, a.purchase_doc_num";
    $result = executeSQLNoParams($strSql);
    return $result;
}

function createCnsgnRcpHdr($orgid, $docNum, $desc, $docdte, $spplrID, $spplrSiteID, $apprvlSts, $nxtApprvl, $poDocID, $pyblAccntID, $docCurID, $docExchRate) {
    global $usrID;
    if ($docdte != "") {
        $docdte = cnvrtDMYToYMD($docdte);
    }
    $insSQL = "INSERT INTO inv.inv_consgmt_rcpt_hdr(po_id, date_received, received_by, supplier_id, created_by, creation_date, last_update_by, last_update_date, 
                                     approval_status, next_approval_status, description, org_id,
                                     site_id, return_status, rcpt_number, payables_accnt_id, doc_curr_id, exchng_rate) " .
            "VALUES (" . $poDocID . ", '" . loc_db_escape_string($docdte) .
            "', " . $usrID .
            ", " . $spplrID . ", " . $usrID .
            ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), " . $usrID .
            ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), '" . loc_db_escape_string($apprvlSts) . "', '" . loc_db_escape_string($nxtApprvl) .
            "', '" . loc_db_escape_string($desc) .
            "', " . $orgid . ", " . $spplrSiteID . ",'', '" . loc_db_escape_string($docNum) . "', " . $pyblAccntID .
            ", " . $docCurID .
            ", " . $docExchRate .
            ")";
    execUpdtInsSQL($insSQL);
    $sbmtdScmCnsgnRcptID = getGnrlRecID("inv.inv_consgmt_rcpt_hdr", "rcpt_number", "rcpt_id", $docNum, $orgid);
    if ($poDocID > 0 && $sbmtdScmCnsgnRcptID > 0) {
        $insSQL = "INSERT INTO inv.inv_svd_consgmt_rcpt_det(s_consgmt_id, s_stock_id, s_quantity_rcvd, s_cost_price, s_created_by,
                                         s_creation_date, s_last_update_by, s_last_update_date, s_expiry_date,
                                         s_manfct_date, s_lifespan, s_tag_number, s_serial_number, s_po_line_id,
                                         s_consignmt_condition, s_remarks, s_itm_id, s_subinv_id, s_rcpt_id) 
                 select -1, inv.getItemStockID(c.itm_id,c.store_id), (c.quantity-coalesce(c.qty_rcvd,0)),c.unit_price, " . $usrID . ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), " . $usrID .
                ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'),'','','','','',c.prchs_doc_line_id, 'Good','',c.itm_id,c.store_id," . $sbmtdScmCnsgnRcptID . "
                from scm.scm_prchs_docs_det c where (c.prchs_doc_hdr_id = " . $poDocID . " and (c.quantity-coalesce(c.qty_rcvd,0))>0) ORDER BY c.prchs_doc_line_id";
//echo $insSQL;
        execUpdtInsSQL($insSQL);
    }
    return $sbmtdScmCnsgnRcptID;
}

function updtCnsgnRcpHdr($docid, $docNum, $desc, $docdte, $spplrID, $spplrSiteID, $apprvlSts, $nxtApprvl, $poDocID, $pyblAccntID, $docCurID, $docExchRate) {
    global $usrID;
    if ($docdte != "") {
        $docdte = cnvrtDMYToYMD($docdte);
    }
    $updtSQL = "UPDATE inv.inv_consgmt_rcpt_hdr
                    SET po_id=" . $poDocID . ",
                        date_received='" . loc_db_escape_string($docdte) . "',
                        received_by=" . $usrID . ",
                        supplier_id=" . $spplrID . ",
                        last_update_by=" . $usrID . ",
                        last_update_date=to_char(now(),'YYYY-MM-DD HH24:MI:SS'),
                        approval_status='" . loc_db_escape_string($apprvlSts) . "',
                        next_approval_status='" . loc_db_escape_string($nxtApprvl) . "',
                        description='" . loc_db_escape_string($desc) . "',
                        site_id=" . $spplrSiteID . ",
                        rcpt_number='" . loc_db_escape_string($docNum) . "',
                        payables_accnt_id=" . $pyblAccntID . ",
                        doc_curr_id=" . $docCurID . ",
                        exchng_rate=" . $docExchRate . "
                    WHERE rcpt_id=" . $docid;
    return execUpdtInsSQL($updtSQL);
}

function createCnsgnRcptLine($qtyRcvd, $costPrce, $expdte, $manDte, $tagNum, $serialNum, $poDocLnID, $cnsgnCndtn, $rmrks, $itmID, $storeID,
        $rcptHdrID) {
    global $usrID;
    if (trim($expdte) === "") {
        $expdte = "31-Dec-4000";
    }
    $v_expdte = $expdte;
    if (trim($expdte) != "") {
        $expdte = cnvrtDMYToYMD($expdte);
    }
    if (trim($manDte) != "") {
        $manDte = cnvrtDMYToYMD($manDte);
    }
    $insSQL = "INSERT INTO inv.inv_svd_consgmt_rcpt_det(s_consgmt_id, s_stock_id, s_quantity_rcvd, s_cost_price, s_created_by, s_creation_date, s_last_update_by, s_last_update_date,
                                         s_expiry_date, s_manfct_date, s_lifespan, s_tag_number, s_serial_number, s_po_line_id,
                                         s_consignmt_condition, s_remarks, s_itm_id, s_subinv_id, s_rcpt_id) " .
            "VALUES (-1, " .
            "inv.getItemStockID(" . $itmID . "," . $storeID . "), " . $qtyRcvd . ", " . $costPrce . ", " . $usrID . ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), " . $usrID .
            ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), '" . loc_db_escape_string($expdte) . "', '" . loc_db_escape_string($manDte) . "', " .
            "substr(''||age(to_timestamp('" . loc_db_escape_string($expdte) . "', 'YYYY-MM-DD'),to_timestamp((CASE WHEN char_length('" . loc_db_escape_string($manDte) . "') <=0 THEN to_char(now(),'YYYY-MM-DD') ELSE '" . loc_db_escape_string($expdte) . "' END),'YYYY-MM-DD')),1,21)" .
            ", '" . loc_db_escape_string($tagNum) . "', '" . loc_db_escape_string($serialNum) . "', " . $poDocLnID .
            ", '" . loc_db_escape_string($cnsgnCndtn) . "', '" . loc_db_escape_string($rmrks) . "', " . $itmID . ", " . $storeID . ", " . $rcptHdrID . ")";
    return execUpdtInsSQL($insSQL);
}

function updtCnsgnRcptLine($lineID, $qtyRcvd, $costPrce, $expdte, $manDte, $tagNum, $serialNum, $poDocLnID, $cnsgnCndtn, $rmrks, $itmID,
        $storeID, $rcptHdrID) {

    global $usrID;
    if (trim($expdte) === "") {
        $expdte = "31-Dec-4000";
    }
    $v_expdte = $expdte;
    if (trim($expdte) != "") {
        $expdte = cnvrtDMYToYMD($expdte);
    }
    if (trim($manDte) != "") {
        $manDte = cnvrtDMYToYMD($manDte);
    }
    $updtSQL = "UPDATE inv.inv_svd_consgmt_rcpt_det
                    SET s_consgmt_id=-1,
                        s_stock_id=inv.getItemStockID(" . $itmID . "," . $storeID . "),
                        s_quantity_rcvd=" . $qtyRcvd . ",
                        s_cost_price=" . $costPrce . ",
                        s_last_update_by=" . $usrID . ",
                        s_last_update_date=to_char(now(),'YYYY-MM-DD HH24:MI:SS'),
                        s_expiry_date='" . loc_db_escape_string($expdte) . "',
                        s_manfct_date='" . loc_db_escape_string($manDte) . "',
                        s_lifespan=substr(''||age(to_timestamp('" . loc_db_escape_string($expdte) . "', 'YYYY-MM-DD'),to_timestamp((CASE WHEN char_length('" . loc_db_escape_string($manDte) . "') <=0 THEN to_char(now(),'YYYY-MM-DD') ELSE '" . loc_db_escape_string($expdte) . "' END),'YYYY-MM-DD')),1,21),
                        s_tag_number='" . loc_db_escape_string($tagNum) . "',
                        s_serial_number='" . loc_db_escape_string($serialNum) . "',
                        s_po_line_id=" . $poDocLnID . ",
                        s_consignmt_condition='" . loc_db_escape_string($cnsgnCndtn) . "',
                        s_remarks='" . loc_db_escape_string($rmrks) . "',
                        s_itm_id=" . $itmID . ",
                        s_subinv_id=" . $storeID . ",
                        s_rcpt_id=" . $rcptHdrID . "
                    WHERE s_line_id=" . $lineID;
    return execUpdtInsSQL($updtSQL);
}

function deleteCnsgnRcptHdrNDet($valLnid, $docNum) {
    $trnsCnt1 = 0;
    $docStatus = getGnrlRecNm("inv.inv_consgmt_rcpt_hdr", "rcpt_id", "approval_status", $valLnid);
    $docType = getGnrlRecNm("inv.inv_consgmt_rcpt_hdr", "rcpt_id",
            "(CASE WHEN coalesce(po_id,-1)>0 THEN 'Purchase Order Receipt' ELSE 'Miscellaneous Receipt' END)", $valLnid);
    $docTypeLnsDlvrd = (int) getGnrlRecNm("inv.inv_consgmt_rcpt_det", "rcpt_id", "count(line_id)", $valLnid);
    if ($docTypeLnsDlvrd > 0 || $docStatus == "Received" || $docStatus == "Initiated" || $docStatus == "Validated" || $docStatus == "Cancelled" || strpos($docStatus, "Reviewed") !== FALSE) {
        $trnsCnt1 = 1;
    }
    if ($trnsCnt1 > 0) {
        $dsply = "No Record Deleted<br/>Cannot DELETE Approved, Initiated, Reviewed, Validated, Cancelled Documents or lines Delivered Already!!";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
    $delSQL = "DELETE FROM scm.scm_gl_interface WHERE src_doc_id = " . $valLnid . " and src_doc_typ='" . loc_db_escape_string($docType) . "'";
    $affctd4 = execUpdtInsSQL($delSQL, "Document Number = " . $docNum);
    $delSQL = "DELETE FROM inv.inv_doc_attchmnts WHERE doc_hdr_id = " . $valLnid . " and doc_hdr_type='" . loc_db_escape_string($docType) . "'";
    $affctd3 = execUpdtInsSQL($delSQL, "Document Number = " . $docNum);
    $delSQL = "DELETE FROM inv.inv_svd_consgmt_rcpt_det WHERE s_rcpt_id = " . $valLnid;
    $affctd2 = execUpdtInsSQL($delSQL, "Document Number = " . $docNum);
    $delSQL = "DELETE FROM inv.inv_consgmt_rcpt_hdr WHERE rcpt_id = " . $valLnid;
    $affctd1 = execUpdtInsSQL($delSQL, "Document Number = " . $docNum);
    if ($affctd1 > 0) {
        $dsply = "";
        $dsply .= "<br/>Successfully Executed the ff-";
        $dsply .= "<br/>Deleted $affctd2 Document Line(s)!";
        $dsply .= "<br/>Deleted $affctd1 Document(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Deleted";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function deleteCnsgnRcptLine($valLnid, $docNum) {
    $trnsCnt1 = 0;
    $docHdrID = (float) getGnrlRecNm("inv.inv_svd_consgmt_rcpt_det", "s_line_id", "s_rcpt_id", $valLnid);
    if ($docHdrID <= 0) {
        $dsply = "No Record Deleted<br/>Cannot DELETE Approved, Initiated, Reviewed, Validated, Cancelled Documents or lines Delivered Already!!";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
    $docStatus = getGnrlRecNm("inv.inv_consgmt_rcpt_hdr", "rcpt_id", "approval_status", $docHdrID);
    $docTypeLnsDlvrd = (int) getGnrlRecNm("inv.inv_consgmt_rcpt_det", "rcpt_id", "count(line_id)", $valLnid);
    if ($docTypeLnsDlvrd > 0 || $docStatus == "Received" || $docStatus == "Initiated" || $docStatus == "Validated" || $docStatus == "Cancelled" || strpos($docStatus, "Reviewed") !== FALSE) {
        $trnsCnt1 = 1;
    }
    if ($trnsCnt1 > 0) {
        $dsply = "No Record Deleted<br/>Cannot DELETE Approved, Initiated, Reviewed, Validated, Cancelled Documents or lines Delivered Already!!";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
    $delSQL = "DELETE FROM inv.inv_svd_consgmt_rcpt_det WHERE s_line_id = " . $valLnid;
    $affctd1 = execUpdtInsSQL($delSQL, "Document Number = " . $docNum);
    if ($affctd1 > 0) {
        $dsply = "";
        $dsply .= "<br/>Successfully Executed the ff-";
        $dsply .= "<br/>Deleted $affctd1 Document Line(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Deleted";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function createStockTrnsfrHdr($orgid, $docNum, $desc, $docdte, $srcStoreID, $destStoreID, $apprvlSts, $ttlAmnt) {
    global $usrID;
    if ($docdte != "") {
        $docdte = cnvrtDMYToYMD($docdte);
    }
    $insSQL = "INSERT INTO inv.inv_stock_transfer_hdr(description, created_by, creation_date, last_update_by, last_update_date, 
                                       transfer_date, source_subinv_id, dest_subinv_id, org_id, 
                                       total_amount, status, rcpt_number) " .
            "VALUES ('" . loc_db_escape_string($desc) . "', " . $usrID .
            ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), " . $usrID .
            ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), '" . loc_db_escape_string($docdte) .
            "', " . $srcStoreID . ", " . $destStoreID . ", " . $orgid . ", " . $ttlAmnt .
            ", '" . loc_db_escape_string($apprvlSts) . "', '" . loc_db_escape_string($docNum) . "')";
    return execUpdtInsSQL($insSQL);
}

function updtStockTrnsfrHdr($docid, $docNum, $desc, $docdte, $srcStoreID, $destStoreID, $apprvlSts, $ttlAmnt) {
    global $usrID;
    if ($docdte != "") {
        $docdte = cnvrtDMYToYMD($docdte);
    }
    $updtSQL = "UPDATE inv.inv_stock_transfer_hdr
                SET description='" . loc_db_escape_string($desc) . "',
                    last_update_by=" . $usrID . ",
                    last_update_date=to_char(now(),'YYYY-MM-DD HH24:MI:SS'),
                    transfer_date='" . loc_db_escape_string($docdte) . "',
                    source_subinv_id=" . $srcStoreID . ",
                    dest_subinv_id=" . $destStoreID . ",
                    total_amount=" . $ttlAmnt . ",
                    status='" . loc_db_escape_string($apprvlSts) . "',
                    rcpt_number='" . loc_db_escape_string($docNum) . "' 
                  WHERE transfer_hdr_id=" . $docid;
    return execUpdtInsSQL($updtSQL);
}

function createStockTrnsfrLine($qtyTrnsfr, $costPrce, $cnsgnCndtn, $trnsfrHdrID, $rmrks, $itmID, $deststoreID, $cnsgnNos, $ttlAmnt,
        $srcStoreID) {
    global $usrID;
    $insSQL = "INSERT INTO inv.inv_stock_transfer_det(transfer_qty, src_stock_id, reason, created_by, creation_date, last_update_by, last_update_date, 
                                       transfer_hdr_id, remarks, itm_id, dest_subinv_id, cnsgmnt_nos, ttl_amount,
                                       src_store_id, dest_stock_id, cost_price) " .
            "VALUES (" . $qtyTrnsfr . ", inv.getItemStockID(" . $itmID . "," . $srcStoreID . "), '" . loc_db_escape_string($cnsgnCndtn) .
            "', " . $usrID . ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), " . $usrID .
            ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), " . $trnsfrHdrID . ", '" . loc_db_escape_string($rmrks) .
            "', " . $itmID . ", " . $deststoreID . ", '" . loc_db_escape_string($cnsgnNos) . "', " . $ttlAmnt .
            "," . $srcStoreID . ", inv.getItemStockID(" . $itmID . "," . $deststoreID . ")," . $costPrce . ")";
    return execUpdtInsSQL($insSQL);
}

function updtStockTrnsfrLine($lineID, $qtyTrnsfr, $costPrce, $cnsgnCndtn, $trnsfrHdrID, $rmrks, $itmID, $deststoreID, $cnsgnNos, $ttlAmnt,
        $srcStoreID) {

    global $usrID;
    $updtSQL = "UPDATE inv.inv_stock_transfer_det
                    SET transfer_qty=" . $qtyTrnsfr . ",
                        src_stock_id=inv.getItemStockID(" . $itmID . "," . $srcStoreID . "),
                        reason= '" . loc_db_escape_string($cnsgnCndtn) . "',
                        last_update_date=to_char(now(),'YYYY-MM-DD HH24:MI:SS'),
                        last_update_by=" . $usrID . ",
                        transfer_hdr_id=" . $trnsfrHdrID . ",
                        remarks='" . loc_db_escape_string($rmrks) . "',
                        itm_id=" . $itmID . ",
                        dest_subinv_id=" . $deststoreID . ",
                        cnsgmnt_nos='" . loc_db_escape_string($cnsgnNos) . "',
                        ttl_amount=" . $ttlAmnt . ",
                        src_store_id=" . $srcStoreID . ",
                        dest_stock_id=inv.getItemStockID(" . $itmID . "," . $deststoreID . "),
                        cost_price=" . $costPrce . "
                    WHERE line_id=" . $lineID;
    return execUpdtInsSQL($updtSQL);
}

function deleteStockTrnsfrHdrNDet($valLnid, $docNum) {
    $trnsCnt1 = 0;
    $docStatus = getGnrlRecNm("inv.inv_stock_transfer_hdr", "transfer_hdr_id", "status", $valLnid);
    if ($docStatus == "Transfer Successful" || $docStatus == "Initiated" || $docStatus == "Validated" || $docStatus == "Cancelled" || strpos($docStatus,
                    "Reviewed") !== FALSE) {
        $trnsCnt1 = 1;
    }
    if ($trnsCnt1 > 0) {
        $dsply = "No Record Deleted<br/>Cannot DELETE Completed, Initiated, Reviewed, Validated, Cancelled Documents or lines Delivered Already!!";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
    $delSQL = "DELETE FROM scm.scm_gl_interface WHERE src_doc_id = " . $valLnid . " and src_doc_typ='Stock Transfer'";
    $affctd4 = execUpdtInsSQL($delSQL, "Document Number = " . $docNum);
    $delSQL = "DELETE FROM inv.inv_doc_attchmnts WHERE doc_hdr_id = " . $valLnid . " and doc_hdr_type='Stock Transfer'";
    $affctd3 = execUpdtInsSQL($delSQL, "Document Number = " . $docNum);
    $delSQL = "DELETE FROM inv.inv_stock_transfer_det WHERE transfer_hdr_id = " . $valLnid;
    $affctd2 = execUpdtInsSQL($delSQL, "Document Number = " . $docNum);
    $delSQL = "DELETE FROM inv.inv_stock_transfer_hdr WHERE transfer_hdr_id = " . $valLnid;
    $affctd1 = execUpdtInsSQL($delSQL, "Document Number = " . $docNum);
    if ($affctd1 > 0) {
        $dsply = "";
        $dsply .= "<br/>Successfully Executed the ff-";
        $dsply .= "<br/>Deleted $affctd2 Document Line(s)!";
        $dsply .= "<br/>Deleted $affctd1 Document(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Deleted";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function deleteStockTrnsfrLine($valLnid, $docNum) {
    $trnsCnt1 = 0;
    $docHdrID = (float) getGnrlRecNm("inv.inv_stock_transfer_det", "line_id", "transfer_hdr_id", $valLnid);
    if ($docHdrID <= 0) {
        $dsply = "No Record Deleted<br/>Cannot DELETE Completed, Initiated, Reviewed, Validated, Cancelled Documents or lines Delivered Already!!";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
    $docStatus = getGnrlRecNm("inv.inv_stock_transfer_hdr", "transfer_hdr_id", "status", $docHdrID);
    if ($docStatus == "Transfer Successful" || $docStatus == "Initiated" || $docStatus == "Validated" || $docStatus == "Cancelled" || strpos($docStatus,
                    "Reviewed") !== FALSE) {
        $trnsCnt1 = 1;
    }
    if ($trnsCnt1 > 0) {
        $dsply = "No Record Deleted<br/>Cannot DELETE Completed, Initiated, Reviewed, Validated, Cancelled Documents or lines Delivered Already!!";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
    $delSQL = "DELETE FROM inv.inv_stock_transfer_det WHERE line_id = " . $valLnid;
    $affctd1 = execUpdtInsSQL($delSQL, "Document Number = " . $docNum);
    if ($affctd1 > 0) {
        $dsply = "";
        $dsply .= "<br/>Successfully Executed the ff-";
        $dsply .= "<br/>Deleted $affctd1 Document Line(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Deleted";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function get_InvWrngBalsRpt($trnsID, $asAtDate) {
    $strSql = "SELECT
tbl1.gnrl_data1::INTEGER rownumbr,
tbl1.gnrl_data2 item_name,
tbl1.gnrl_data3 uom,
tbl1.gnrl_data4 store_nm,
tbl1.gnrl_data5::NUMERIC consg_tot_qty,
tbl1.gnrl_data6::NUMERIC consg_rsrv,
tbl1.gnrl_data7::NUMERIC consg_avlbl,
tbl1.gnrl_data8::NUMERIC stock_tot_qty,
tbl1.gnrl_data9::NUMERIC stoc_rsrv,
tbl1.gnrl_data10::NUMERIC stoc_avlbl,
tbl1.gnrl_data11 bals_date,
to_char(to_timestamp('" . $asAtDate . "','YYYY-MM-DD'),'DD-Mon-YYYY') p_as_at_date
FROM rpt.rpt_accb_data_storage tbl1 
WHERE tbl1.accb_rpt_runid=" . $trnsID . " ORDER BY tbl1.gnrl_data1::INTEGER";
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_ItemBalsRpt($trnsID, $asAtDate) {
    $strSql = "SELECT
tbl1.gnrl_data1::INTEGER rownumbr,
tbl1.gnrl_data2 item_name,
tbl1.gnrl_data3 ctgry,
tbl1.gnrl_data4 uom,
tbl1.gnrl_data5::NUMERIC stock_tot_qty,
tbl1.gnrl_data6::NUMERIC stoc_rsrv,
tbl1.gnrl_data7::NUMERIC stoc_avlbl,
tbl1.gnrl_data8::NUMERIC stock_tot_cost,
tbl1.gnrl_data9 bals_date,
tbl1.gnrl_data10 crncy,
'" . $asAtDate . "' p_as_at_date
FROM rpt.rpt_accb_data_storage tbl1 
WHERE tbl1.accb_rpt_runid=" . $trnsID . " ORDER BY tbl1.gnrl_data1::INTEGER";
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_StockBalsRpt($trnsID, $asAtDate) {
    $strSql = "SELECT
tbl1.gnrl_data1::INTEGER rownumbr,
tbl1.gnrl_data2 item_name,
tbl1.gnrl_data3 ctgry,
tbl1.gnrl_data4 uom,
tbl1.gnrl_data5::NUMERIC stock_tot_qty,
tbl1.gnrl_data6::NUMERIC stoc_rsrv,
tbl1.gnrl_data7::NUMERIC stoc_avlbl,
tbl1.gnrl_data8::NUMERIC stock_tot_cost,
tbl1.gnrl_data9 bals_date,
tbl1.gnrl_data10 crncy,
tbl1.gnrl_data11 storenm,
'" . $asAtDate . "' p_as_at_date
FROM rpt.rpt_accb_data_storage tbl1 
WHERE tbl1.accb_rpt_runid=" . $trnsID . " ORDER BY tbl1.gnrl_data1::INTEGER";
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_CnsgnBalsRpt($trnsID, $asAtDate) {
    $strSql = "SELECT
tbl1.gnrl_data1::INTEGER rownumbr,
tbl1.gnrl_data2 item_name,
tbl1.gnrl_data3 ctgry,
tbl1.gnrl_data4 uom,
tbl1.gnrl_data5::NUMERIC stock_tot_qty,
tbl1.gnrl_data6::NUMERIC stoc_rsrv,
tbl1.gnrl_data7::NUMERIC stoc_avlbl,
tbl1.gnrl_data8::NUMERIC stock_tot_cost,
tbl1.gnrl_data9 bals_date,
tbl1.gnrl_data10 crncy,
tbl1.gnrl_data11 storenm,
tbl1.gnrl_data12 cnsgnNo,
'" . $asAtDate . "' p_as_at_date
FROM rpt.rpt_accb_data_storage tbl1 
WHERE tbl1.accb_rpt_runid=" . $trnsID . " ORDER BY tbl1.gnrl_data1::INTEGER";
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_BinCardRpt($trnsID, $accbStrtFSRptDte, $asAtDate) {
    $strSql = "SELECT
    tbl1.gnrl_data1::INTEGER rownumbr,
    tbl1.gnrl_data2 item_name,
    tbl1.gnrl_data3 storenm,
    tbl1.gnrl_data4 trns_type,
    tbl1.gnrl_data5::NUMERIC trns_qty,
    tbl1.gnrl_data6::NUMERIC stock_tot_qty,
    tbl1.gnrl_data7::NUMERIC stoc_rsrv,
    tbl1.gnrl_data8::NUMERIC stoc_avlbl,
    tbl1.gnrl_data9 bals_date,
    tbl1.gnrl_data10 uom,
    tbl1.gnrl_data11 comments_desc,
    tbl1.gnrl_data12 p_to_date,
    '" . $asAtDate . "' p_as_at_date, 
    (SELECT SUM(b.gnrl_data5::NUMERIC) FROM rpt.rpt_accb_data_storage b
    WHERE b.gnrl_data1::INTEGER <= tbl1.gnrl_data1::INTEGER
    AND b.accb_rpt_runid=tbl1.accb_rpt_runid) rnng_bals
FROM rpt.rpt_accb_data_storage tbl1 
WHERE tbl1.accb_rpt_runid=" . $trnsID . " ORDER BY tbl1.gnrl_data1::INTEGER";
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_SalesMoneyRcvd($trnsID, $accbStrtFSRptDte, $asAtDate) {
    $strSql = "SELECT
    tbl1.gnrl_data1::INTEGER rownumbr,
    tbl1.gnrl_data2 doc_number,
    tbl1.gnrl_data3::NUMERIC invc_amnt,
    tbl1.gnrl_data4::NUMERIC dscnt_amnt,
    tbl1.gnrl_data5::NUMERIC amnt_paid,
    tbl1.gnrl_data6::NUMERIC outstand_amnt,
    tbl1.gnrl_data7 invc_date,
    tbl1.gnrl_data8 dateClause,
    tbl1.gnrl_data9 p_from_date,
    tbl1.gnrl_data10 p_to_date,
    '" . $accbStrtFSRptDte . "' p_as_at_date1,
    '" . $asAtDate . "' p_as_at_date2
FROM rpt.rpt_accb_data_storage tbl1 
WHERE tbl1.accb_rpt_runid=" . $trnsID . " ORDER BY tbl1.gnrl_data1::INTEGER";
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_PymtsMoneyRcvd($trnsID, $accbStrtFSRptDte, $asAtDate) {
    $strSql = "SELECT
    tbl1.gnrl_data1::INTEGER rownumbr,
    tbl1.gnrl_data2 doc_number,
    tbl1.gnrl_data3::NUMERIC invc_amnt,
    tbl1.gnrl_data4::NUMERIC dscnt_amnt,
    tbl1.gnrl_data5::NUMERIC amnt_paid,
    tbl1.gnrl_data6::NUMERIC outstand_amnt,
    tbl1.gnrl_data7 invc_date,
    tbl1.gnrl_data8 dateClause,
    tbl1.gnrl_data9 p_from_date,
    tbl1.gnrl_data10 p_to_date,
    '" . $accbStrtFSRptDte . "' p_as_at_date1,
    '" . $asAtDate . "' p_as_at_date2
FROM rpt.rpt_accb_data_storage tbl1 
WHERE tbl1.accb_rpt_runid=" . $trnsID . " ORDER BY tbl1.gnrl_data1::INTEGER";
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_ItemsSold($trnsID, $accbStrtFSRptDte, $asAtDate) {
    $strSql = "SELECT
    tbl1.gnrl_data1::INTEGER rownumbr,
    tbl1.gnrl_data2 item_code_desc,
    tbl1.gnrl_data3 doc_numbers,
    tbl1.gnrl_data4 uom_nm,
    tbl1.gnrl_data5::NUMERIC docqty,
    tbl1.gnrl_data6::NUMERIC sales_price,
    tbl1.gnrl_data7::NUMERIC total_amnt,
    tbl1.gnrl_data8 p_from_date,
    tbl1.gnrl_data9 p_to_date,
    tbl1.gnrl_data10 crncy,
    '" . $accbStrtFSRptDte . "' p_as_at_date1,
    '" . $asAtDate . "' p_as_at_date2
FROM rpt.rpt_accb_data_storage tbl1 
WHERE tbl1.accb_rpt_runid=" . $trnsID . " ORDER BY tbl1.gnrl_data1::INTEGER";
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_ItemInf($itmID, $cstmrSiteID) {
    $strSql = "SELECT a.item_code, a.item_desc, 
a.selling_price, a.tax_code_id, CASE WHEN scm.get_cstmr_splr_dscntid("
            . $cstmrSiteID . ") != -1 THEN scm.get_cstmr_splr_dscntid("
            . $cstmrSiteID . ") ELSE a.dscnt_code_id END, a.extr_chrg_id, 
                a.item_type, a.base_uom_id, a.orgnl_selling_price, 
                (SELECT z.uom_name FROM inv.unit_of_measure z WHERE z.uom_id = a.base_uom_id),
                inv_asset_acct_id, cogs_acct_id, sales_rev_accnt_id, sales_ret_accnt_id, purch_ret_accnt_id, expense_accnt_id " .
            "FROM inv.inv_itm_list a WHERE a.item_id = " . $itmID;
    $result = executeSQLNoParams($strSql);
    return $result;
}

function getTrnsDatePrice($hdrid, $trnsDte, $calcMethod) {
    if (strlen($trnsDte) > 20) {
        $trnsDte = substr($trnsDte, 0, 20);
    }
    if ($trnsDte != "") {
        $trnsDte = cnvrtDMYTmToYMDTm($trnsDte);
    }
    $trnsDte1 = substr($trnsDte, 0, 10);
    $whrcls = " and (to_timestamp('" . $trnsDte . "','YYYY-MM-DD HH24:MI:SS') between 
to_timestamp(a.start_date,'YYYY-MM-DD HH24:MI:SS') AND to_timestamp(a.end_date,'YYYY-MM-DD HH24:MI:SS'))";
    if ($calcMethod == "2") {
        $whrcls = " and (to_timestamp('" . $trnsDte . "','YYYY-MM-DD HH24:MI:SS') between 
to_timestamp('" . $trnsDte1 . "' ||substring(a.start_date,11),'YYYY-MM-DD HH24:MI:SS') "
                . "AND to_timestamp('" . $trnsDte1 . "' ||substring(a.end_date,11),'YYYY-MM-DD HH24:MI:SS'))";
    }
    $strSql = "SELECT a.selling_price " .
            "FROM hotl.service_type_prices a " .
            "WHERE(a.service_type_id = " . $hdrid . $whrcls . ")";
    //echo $strSql;
    $result = executeSQLNoParams($strSql);
    if (loc_db_num_rows($result) > 0) {
        $row = loc_db_fetch_array($result);
        return $row[0];
    }
    return -1;
}

function get_SalesDocLnID($itemID, $unitPrice, $docHdrID) {
    $strSql = "SELECT a.invc_det_ln_id FROM scm.scm_sales_invc_det a " .
            " WHERE a.itm_id=" . $itemID .
            " and a.unit_selling_price=" . $unitPrice .
            " and a.other_mdls_doc_id=" . $docHdrID .
            " ORDER BY a.invc_det_ln_id ASC LIMIT 1 OFFSET 0";
    //echo $strSql;
    $result = executeSQLNoParams($strSql);
    //echo loc_db_num_rows($result);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -1;
}

function get_One_ChcknSalesID($checkInID) {
    $strSql = "Select COALESCE(y.invc_hdr_id,-1)
       FROM hotl.checkins_hdr a 
        LEFT OUTER JOIN scm.scm_sales_invc_hdr y ON ((a.check_in_id = y.other_mdls_doc_id or (a.prnt_chck_in_id=y.other_mdls_doc_id and y.other_mdls_doc_id>0))
        and (a.doc_type=y.other_mdls_doc_type or (a.prnt_doc_typ=y.other_mdls_doc_type and a.prnt_doc_typ != ''))) " .
            "WHERE a.check_in_id=" . $checkInID . " ";
    $result = executeSQLNoParams($strSql);
    if (loc_db_num_rows($result) > 0) {
        $row = loc_db_fetch_array($result);
        return $row[0];
    }
    return -1;
}

function deleteRcvblsDocHdrNDet($valLnid, $docNum) {
    $trnsCnt1 = 0;
    $docStatus = getGnrlRecNm("accb.accb_rcvbls_invc_hdr", "rcvbls_invc_hdr_id", "approval_status", $valLnid);
    if ($docStatus == "Approved" || $docStatus == "Initiated" || $docStatus == "Validated" || $docStatus == "Cancelled" || strpos($docStatus,
                    "Reviewed") !== FALSE) {
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
    if ($affctd1 > 0) {
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

function getInvoiceReport($P_INVOICE_ID) {
    $sqlStr = "SELECT a.invc_hdr_id,
       a.doc_qty,
       a.unit_selling_price,
       (a.doc_qty * a.unit_selling_price * a.rented_itm_qty)                                      amnt,
       e.invc_number,
       e.invc_type,
       to_char(to_timestamp(e.invc_date, 'YYYY-MM-DD'), 'DD-Mon-YYYY')                            invc_date,
       scm.get_cstmr_splr_name(e.customer_id)                                                     customer_name,
       COALESCE(f.site_name, ' ')                                                                 site_name,
       COALESCE(f.billing_address, ' ')                                                           billing_address,
       COALESCE(f.ship_to_address, ' ')                                                           ship_to_address,
       e.payment_terms,
       gst.get_pssbl_val(e.invc_curr_id)                                                          curr,
       z.rcvbls_invc_hdr_id,
       z.rcvbls_invc_type,
       tbl1a.pymnt_id,
       accb.get_pymnt_mthd_name(tbl1a.pymnt_mthd_id) ||
       (CASE WHEN round(scm.get_DocSmryOutsbls(e.invc_hdr_id,e.invc_type),2) <= 0 THEN '-Full Payment' ELSE '-Part Payment' END)     pymnt_mthd,
       tbl1a.amount_paid,
       tbl1a.change_or_balance,
       tbl1a.pymnt_remark,
       tbl1a.src_doc_typ,
       tbl1a.src_doc_id,
       tbl1a.created_by,
       to_char(to_timestamp(tbl1a.pymnt_date, 'YYYY-MM-DD HH24:MI:SS'), 'DD-Mon-YYYY HH24:MI:SS') pymnt_date,
       z.rcvbls_invc_number,
       tbl1c.user_name,
       e.branch_id,
       org.get_site_code_desc(e.branch_id)                                                        branch_nm,
       tbl1a.amount_given, e.approval_status
    FROM scm.scm_sales_invc_det a,
     inv.inv_itm_list b,
     inv.unit_of_measure c,
     inv.inv_product_categories d,
     scm.scm_sales_invc_hdr e
         LEFT OUTER JOIN scm.scm_cstmr_suplr_sites f ON (e.customer_site_id = f.cust_sup_site_id)
         LEFT OUTER JOIN accb.accb_rcvbls_invc_hdr z
                         ON (z.src_doc_hdr_id = e.invc_hdr_id AND z.src_doc_type = e.invc_type)
         LEFT OUTER JOIN accb.accb_payments tbl1a
                         ON (tbl1a.src_doc_typ = z.rcvbls_invc_type AND tbl1a.src_doc_id = z.rcvbls_invc_hdr_id)
         LEFT OUTER JOIN SEC.sec_users tbl1c ON (tbl1a.created_by = tbl1c.user_id)
WHERE (a.invc_hdr_id = e.invc_hdr_id AND a.invc_hdr_id = " . $P_INVOICE_ID . " AND a.invc_hdr_id >0
    AND a.itm_id = b.item_id AND b.base_uom_id= C.uom_id AND D.cat_id = b.category_id)
ORDER BY to_timestamp(tbl1a.pymnt_date, 'YYYY-MM-DD HH24:MI:SS') DESC, tbl1a.pymnt_id DESC LIMIT 1";
    $result = executeSQLNoParams($sqlStr);
    return $result;
}

function getInvoiceRptDet($P_INVOICE_ID) {
    $strSQL = "SELECT 
        a.doc_qty, a.unit_selling_price, (a.doc_qty * a.unit_selling_price*a.rented_itm_qty) amnt, 
        b.item_code, b.item_desc, 
        b.base_uom_id,c.uom_name,
	    CASE WHEN a.rented_itm_qty>1 THEN ''||a.rented_itm_qty||' x ' || a.doc_qty ELSE ''|| a.doc_qty END  doc_qty2,
	    gst.get_pssbl_val(e.invc_curr_id) curr, inv.get_catgryname(b.category_id) cat_nm
      FROM scm.scm_sales_invc_det a, inv.inv_itm_list b, 
        inv.unit_of_measure c, scm.scm_sales_invc_hdr e  
      WHERE( a.invc_hdr_id= e.invc_hdr_id and a.invc_hdr_id =" . $P_INVOICE_ID . "  and a.invc_hdr_id >0 
      and a.itm_id = b.item_id and b.base_uom_id=c.uom_id) 
      ORDER BY a.invc_det_ln_id";
    $result = executeSQLNoParams($strSQL);
    return $result;
}

function getInvoiceRptSmmry($P_INVOICE_ID, $P_INVOICE_TYP) {
    $strSQL = "Select REPLACE(CASE WHEN smmry_type='3Discount' THEN 'Discount' ELSE smmry_name END,'Actual Outstanding Balance','Amount Due') ||':' smmry_name, 
smmry_amnt, 
REPLACE(REPLACE(smmry_type,'2Tax','3Tax'),'3Discount','2Discount') smtyp 
from scm.scm_doc_amnt_smmrys 
WHERE src_doc_hdr_id=" . $P_INVOICE_ID . " and src_doc_type='" . loc_db_escape_string($P_INVOICE_TYP) . "'
and smmry_name !='Outstanding Balance' 
and smmry_name !='Total Deposits' order by 3";
    $result = executeSQLNoParams($strSQL);
    return $result;
}

function getInvoiceRptAllSmmry($P_INVOICE_ID, $P_INVOICE_TYP) {
    $strSQL = "Select REPLACE(CASE WHEN smmry_type='3Discount' THEN 'Discount' ELSE smmry_name END,'Actual Outstanding Balance','Amount Due') ||':' smmry_name, 
smmry_amnt, 
REPLACE(REPLACE(smmry_type,'2Tax','3Tax'),'3Discount','2Discount') smtyp 
from scm.scm_doc_amnt_smmrys 
WHERE src_doc_hdr_id=" . $P_INVOICE_ID . " and src_doc_type='" . loc_db_escape_string($P_INVOICE_TYP) . "' order by 3";
    $result = executeSQLNoParams($strSQL);
    return $result;
    /*
      and smmry_name !='Outstanding Balance'
      and smmry_name !='Total Deposits' */
}

function getInvTemplateID($templtNm) {
    $strSql = "SELECT a.item_type_id " .
            "FROM inv.inv_itm_type_templates a " .
            "WHERE (lower(a.item_type_name) = lower('" . loc_db_escape_string($templtNm) . "'))";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return (int) $row[0];
    }
    return -1;
}

function getInvTemplateInf($tmpltID) {
    $strSql = "SELECT a.inv_asset_acct_id, cogs_acct_id, sales_rev_accnt_id, sales_ret_accnt_id, purch_ret_accnt_id, expense_accnt_id, "
            . "tax_code_id, dscnt_code_id, extr_chrg_id, item_type, base_uom_id, value_price_crncy_id, auto_dflt_in_vms_trns,"
            . "planning_enabled,min_level,max_level,category_id " .
            "FROM inv.inv_itm_type_templates a WHERE a.item_type_id = " . $tmpltID;
    $result = executeSQLNoParams($strSql);
    return $result;
}

function getTaxCodeID($codeNm, $orgID) {
    $strSql = "SELECT a.code_id FROM scm.scm_tax_codes a " .
            "WHERE(a.code_name = '" . loc_db_escape_string($codeNm) . "' and a.org_id = " . $orgID . ")";
    //logSessionErrs($strSql);
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return (int) $row[0];
    }
    return -1;
}

/* CONSUMER CREDIT ASSESSMENT */

//CREDIT ITEMS
function insertCreditItems($credit_itm_id, $cnsmr_credit_id, $item_id, $vendor_id, $itm_pymnt_plan_id, $qty, $unit_selling_price,
        $itm_plan_init_deposit, $usrID, $dateStr, $noOfPymnt) {

    $insSQL = "INSERT INTO scm.scm_cnsmr_credit_items(
            credit_itm_id, cnsmr_credit_id, item_id, vendor_id, itm_pymnt_plan_id, 
            qty, unit_selling_price, itm_plan_init_deposit, created_by, creation_date, last_update_by,  last_update_date)
            VALUES ($credit_itm_id, $cnsmr_credit_id, $item_id, $vendor_id, $itm_pymnt_plan_id, 
            $qty, $unit_selling_price, $itm_plan_init_deposit,  $usrID,'" . $dateStr . "'," . $usrID . ",'" . $dateStr . "')";

    $cnta = execUpdtInsSQL($insSQL);

    $ttl_prdt_price = getTtlPrdtsPrice($cnsmr_credit_id);
    //if($init_dpst_type == "Automatic"){
    $ttl_initial_deposit = getTtlItmDpsts($cnsmr_credit_id);
    //}

    $mnthly_rpymnts = getMnthlyRpymnts($ttl_prdt_price, $ttl_initial_deposit, $noOfPymnt);

    execUpdtInsSQL("UPDATE scm.scm_cnsmr_credit_analys
                SET  last_update_by = $usrID, last_update_date = '" . $dateStr . "', 
                            ttl_prdt_price = $ttl_prdt_price,  
                            ttl_initial_deposit =$ttl_initial_deposit, mnthly_rpymnts = $mnthly_rpymnts
                WHERE cnsmr_credit_id = $cnsmr_credit_id");

    return $cnta;
}

function updateCreditItems($credit_itm_id, $cnsmr_credit_id, $item_id, $vendor_id, $itm_pymnt_plan_id, $qty, $unit_selling_price,
        $itm_plan_init_deposit, $usrID, $dateStr, $noOfPymnt) {
    $updtSQL = "UPDATE scm.scm_cnsmr_credit_items
   SET item_id=$item_id, vendor_id=$vendor_id, itm_pymnt_plan_id=$itm_pymnt_plan_id, 
       qty=$qty, unit_selling_price=$unit_selling_price, itm_plan_init_deposit = $itm_plan_init_deposit, 
       last_update_by=$usrID,  last_update_date='" . $dateStr . "'
    WHERE credit_itm_id = $credit_itm_id";

    $cnta = execUpdtInsSQL($updtSQL);

    $ttl_prdt_price = getTtlPrdtsPrice($cnsmr_credit_id);
    //if($init_dpst_type == "Automatic"){
    $ttl_initial_deposit = getTtlItmDpsts($cnsmr_credit_id);
    //}

    $mnthly_rpymnts = getMnthlyRpymnts($ttl_prdt_price, $ttl_initial_deposit, $noOfPymnt);

    execUpdtInsSQL("UPDATE scm.scm_cnsmr_credit_analys
            SET  last_update_by = $usrID, last_update_date = '" . $dateStr . "', 
                        ttl_prdt_price = $ttl_prdt_price,  
                        ttl_initial_deposit =$ttl_initial_deposit, mnthly_rpymnts = $mnthly_rpymnts
            WHERE cnsmr_credit_id = $cnsmr_credit_id");

    return $cnta;
}

function deleteCreditItems($credit_itm_id, $cnsmr_credit_id, $noOfPymnt, $usrID, $dateStr) {
    $delSQL1 = "DELETE FROM scm.scm_cnsmr_credit_items WHERE credit_itm_id = $credit_itm_id";

    $cnta = execUpdtInsSQL($delSQL1);


    $ttl_prdt_price = getTtlPrdtsPrice($cnsmr_credit_id);
    //if($init_dpst_type == "Automatic"){
    $ttl_initial_deposit = getTtlItmDpsts($cnsmr_credit_id);
    //}

    $mnthly_rpymnts = getMnthlyRpymnts($ttl_prdt_price, $ttl_initial_deposit, $noOfPymnt);

    execUpdtInsSQL("UPDATE scm.scm_cnsmr_credit_analys
            SET  last_update_by = $usrID, last_update_date = '" . $dateStr . "', 
                        ttl_prdt_price = $ttl_prdt_price,  
                        ttl_initial_deposit =$ttl_initial_deposit, mnthly_rpymnts = $mnthly_rpymnts
            WHERE cnsmr_credit_id = $cnsmr_credit_id");

    return $cnta;
}

function getCreditItemsID() {
    $sqlStr = "SELECT nextval('scm.scm_cnsmr_credit_items_credit_itm_id_seq'::regclass);";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -1;
}

function get_CreditItems($cnsmr_credit_id) {
    $strSql = "SELECT credit_itm_id, item_id, (select item_code||'.'||item_desc FROM inv.inv_itm_list WHERE item_id = x.item_id) item_desc, 
        vendor_id, (SELECT cust_sup_name FROM scm.scm_cstmr_suplr WHERE cust_sup_id = vendor_id) vendor_name,
        itm_pymnt_plan_id, (SELECT plan_name FROM inv.inv_itm_payment_plans WHERE itm_pymnt_plan_id = x.itm_pymnt_plan_id) plan_name, 
        qty, itm_plan_init_deposit, unit_selling_price    
       FROM scm.scm_cnsmr_credit_items x
    WHERE cnsmr_credit_id = $cnsmr_credit_id";
    //echo $strSql;
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_CreditItemsTtl($searchWord, $searchIn) {
    $whrcls = "";
    if ($searchIn == "Item") {
        $whrcls .= " and (b.item_desc ilike '" . loc_db_escape_string($searchWord) .
                "' OR b.item_code ilike '" . loc_db_escape_string($searchWord) .
                "') ";
    }


    $strSql = "SELECT count(1)
  FROM (SELECT 1 FROM scm.scm_cnsmr_credit_items a, inv.inv_itm_list b
        WHERE ((1=1 AND a.item_id = b.item_id )" . $whrcls . "))";
    $result = executeSQLNoParams($strSql);
    //var_dump($strSql);
    while ($row = loc_db_fetch_array($result)) {
        $cnt = $row[0];
    }
    return $cnt;
}

function getCreditItemsDet($credit_itm_id) {
    $strSql = "SELECT credit_itm_id, item_id, (select item_code||'.'||item_desc FROM inv.inv_itm_list WHERE item_id = x.item_id) item_desc, 
        vendor_id, (SELECT cust_sup_name FROM scm.scm_cstmr_suplr WHERE cust_sup_id = vendor_id) vendor_name,
        itm_pymnt_plan_id, (SELECT plan_name FROM inv.inv_itm_payment_plans WHERE itm_pymnt_plan_id = x.itm_pymnt_plan_id) plan_name, 
        qty, itm_plan_init_deposit, unit_selling_price    
       FROM scm.scm_cnsmr_credit_items x
    WHERE credit_itm_id = $credit_itm_id";

    $result = executeSQLNoParams($strSql);
    return $result;
}

//On select of payment plan, get plan price
function getItmPlanUnitPrice($itm_pymnt_plan_id) {
    $sqlStr = "SELECT coalesce(plan_price,0.00) FROM inv.inv_itm_payment_plans WHERE itm_pymnt_plan_id = $itm_pymnt_plan_id";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return 0.00;
}

function getItmPlanIntlDpst($itm_pymnt_plan_id) {
    $sqlStr = "SELECT coalesce(initial_deposit,0.00) FROM inv.inv_itm_payment_plans WHERE itm_pymnt_plan_id = $itm_pymnt_plan_id";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return 0.00;
}

//ITEM PAYMENT PLANS
function insertItemPymntPlans($itm_pymnt_plan_id, $item_id, $plan_name, $no_of_pymnts, $plan_price, $initial_deposit, $is_enabled, $usrID, $dateStr) {
    global $orgID;
    $insSQL = "INSERT INTO inv.inv_itm_payment_plans(
            itm_pymnt_plan_id, item_id, plan_name, no_of_pymnts, 
            plan_price, initial_deposit, is_enabled, created_by, creation_date, last_update_by, 
            last_update_date, org_id)
            VALUES ($itm_pymnt_plan_id, $item_id, '" . loc_db_escape_string($plan_name) . "', $no_of_pymnts, $plan_price, $initial_deposit, '$is_enabled',
            $usrID,'" . $dateStr . "'," . $usrID . ",'" . $dateStr . "', $orgID)";

    return execUpdtInsSQL($insSQL);
}

function updateItemPymntPlans($itm_pymnt_plan_id, $item_id, $plan_name, $no_of_pymnts, $plan_price, $initial_deposit, $is_enabled, $usrID, $dateStr) {
    $updtSQL = "UPDATE inv.inv_itm_payment_plans
   SET item_id=$item_id, plan_name='" . loc_db_escape_string($plan_name) . "', no_of_pymnts=$no_of_pymnts, 
       plan_price=$plan_price, initial_deposit = $initial_deposit, is_enabled = '$is_enabled', last_update_by=$usrID, 
       last_update_date='" . $dateStr . "'
    WHERE itm_pymnt_plan_id = $itm_pymnt_plan_id";

    return execUpdtInsSQL($updtSQL);
}

function deleteItemPymntPlans($itm_pymnt_plan_id) {
    $delSQL1 = "DELETE FROM inv.inv_itm_payment_plans WHERE itm_pymnt_plan_id = $itm_pymnt_plan_id";

    return execUpdtInsSQL($delSQL1);
}

function getItemPymntPlansID() {
    $sqlStr = "SELECT nextval('inv.inv_itm_payment_plans_itm_pymnt_plan_id_seq'::regclass);";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -1;
}

function getItmPymntPlansDets($itm_pymnt_plan_id) {
    $strSql = "SELECT itm_pymnt_plan_id, plan_name, no_of_pymnts, plan_price, is_enabled, initial_deposit
  FROM inv.inv_itm_payment_plans x
  WHERE itm_pymnt_plan_id = $itm_pymnt_plan_id";

    $result = executeSQLNoParams($strSql);
    return $result;
}

function getItmPymntPlansTbl($isEnabled, $searchFor, $searchIn, $offset, $limit_size, $orgID, $searchAll, $sortBy, $itmID) {
    $extra1 = "";

    if ($searchAll == true) {
        $extra1 = "or 1 = 1";
    }

    $strSql = "";
    $whrcls = "";
    if ($searchIn == "Plan Name") {
        $whrcls .= " AND (a.plan_name ilike '" . loc_db_escape_string($searchFor) .
                "') ";
    }

    if ($isEnabled == "true") {
        $whrcls .= " and upper(a.is_enabled) = 'YES'";
    }

    $ordrBy = "a.last_update_date DESC";

    $strSql = "SELECT itm_pymnt_plan_id, plan_name, no_of_pymnts, plan_price, is_enabled, initial_deposit
        FROM inv.inv_itm_payment_plans a  WHERE (1 = 1 AND item_id = $itmID AND (a.org_id = " . $orgID . " " . $extra1 . ")" . $whrcls .
            ") ORDER BY no_of_pymnts ASC LIMIT " . $limit_size .
            " OFFSET " . abs($offset * $limit_size);
    $result = executeSQLNoParams($strSql);
    //var_dump($strSql);
    return $result;
}

function getItmPymntPlansTblTtl($isEnabled, $searchFor, $searchIn, $orgID, $searchAll, $itmID) {
    $extra1 = "";
    $extra2 = "";

    if ($searchAll == true) {
        $extra1 = "or 1 = 1";
    }

    $strSql = "";
    $whrcls = "";
    if ($searchIn == "Plan Name") {
        $whrcls .= " AND (a.plan_name ilike '" . loc_db_escape_string($searchFor) .
                "') ";
    }

    if ($isEnabled == "true") {
        $whrcls .= " and upper(a.is_enabled) = 'YES'";
    }

    $strSql = "SELECT count(1) " .
            "FROM inv.inv_itm_payment_plans a WHERE (1 = 1 AND item_id = $itmID AND (a.org_id = " . $orgID . " " . $extra1 . ")" . $whrcls . $extra2 .
            ")";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return 0;
}

//POST-DATED CHEQUES
function insertPostDtdChqs($postdated_chq_id, $cnsmr_credit_id, $chq_no, $chq_issuer_name, $chq_bank, $amount, $usrID, $dateStr) {

    $insSQL = "INSERT INTO scm.scm_post_dated_cheques(
            postdated_chq_id, cnsmr_credit_id, chq_no, chq_issuer_name, 
            chq_bank, amount, created_by, creation_date, last_update_by, 
            last_update_date)
            VALUES ($postdated_chq_id, $cnsmr_credit_id, '" . loc_db_escape_string($chq_no) . "', 
			'" . loc_db_escape_string($chq_issuer_name) . "', '" . loc_db_escape_string($chq_bank) . "', $amount, $usrID,'" . $dateStr .
            "'," . $usrID . ",'" . $dateStr . "')";

    return execUpdtInsSQL($insSQL);
}

function updatePostDtdChqs($postdated_chq_id, $cnsmr_credit_id, $chq_no, $chq_issuer_name, $chq_bank, $amount, $usrID, $dateStr) {
    $updtSQL = "UPDATE scm.scm_post_dated_cheques
   SET cnsmr_credit_id=$cnsmr_credit_id, chq_no='" . loc_db_escape_string($chq_no) . "', 
   chq_issuer_name='" . loc_db_escape_string($chq_issuer_name) . "', 
       chq_bank='" . loc_db_escape_string($chq_bank) . "', amount = $amount, last_update_by=$usrID, 
       last_update_date='" . $dateStr . "'
    WHERE postdated_chq_id = $postdated_chq_id";

    return execUpdtInsSQL($updtSQL);
}

function deletePostDtdChqs($postdated_chq_id) {
    $delSQL1 = "DELETE FROM scm.scm_post_dated_cheques WHERE postdated_chq_id = $postdated_chq_id";

    return execUpdtInsSQL($delSQL1);
}

function getPostDtdChqsID() {
    $sqlStr = "SELECT nextval('scm.scm_post_dated_cheques_postdated_chq_id_seq'::regclass);";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -1;
}

function get_PostDtdChqs($cnsmr_credit_id) {

    $strSql = "SELECT postdated_chq_id, chq_no, chq_issuer_name,  chq_bank,  amount, cnsmr_credit_id 
  FROM scm.scm_post_dated_cheques a WHERE cnsmr_credit_id = $cnsmr_credit_id";
    //echo $strSql;
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_PostDtdChqsTtl($searchWord, $searchIn) {
    $whrcls = "";
    if ($searchIn == "Issuer") {
        $whrcls .= " and (a.chq_issuer_name ilike '" . loc_db_escape_string($searchWord) .
                "') ";
    }

    if ($searchIn == "Cheque No") {
        $whrcls .= " and (a.chq_no ilike '" . loc_db_escape_string($searchWord) .
                "') ";
    }

    $strSql = "SELECT count(1)
  FROM scm.scm_post_dated_cheques a
        WHERE ((1=1 )" . $whrcls . ")";
    $result = executeSQLNoParams($strSql);
    //var_dump($strSql);
    while ($row = loc_db_fetch_array($result)) {
        $cnt = $row[0];
    }
    return $cnt;
}

function get_PostDtdChqsDet($postdated_chq_id) {
    $strSql = "SELECT postdated_chq_id, chq_no, chq_issuer_name, chq_bank, amount, cnsmr_credit_id
  FROM scm.scm_post_dated_cheques x
  WHERE postdated_chq_id = $postdated_chq_id";

    $result = executeSQLNoParams($strSql);
    return $result;
}

//CONSUMER CREDIT ANALYSIS
function insertCnsmrCrdtAnalysis($cnsmr_credit_id, $cust_sup_id, $salary_income, $fuel_allowance,
        $rent_allowance, $clothing_allowance, $other_allowances, $loan_deductions, $trns_date,
        $marketer_person_id, $pymnt_option, $guarantor_name, $guarantor_contact_nos,
        $guarantor_occupation, $guarantor_place_of_work, $period_at_workplace, $period_uom_at_workplace,
        $guarantor_email, $no_of_pymnts, $init_dpst_type, $ttl_initial_deposit, $usrID, $dateStr, $transactionNo, $orgID, $salesStoreNmID) {

    $dbtSvsRtio = round((($salary_income + $fuel_allowance +
            $rent_allowance + $clothing_allowance + $other_allowances) / 2), 2);

    $affdblty = round(($dbtSvsRtio - $loan_deductions), 2);

    /* if ($trns_date != "") {
      $trns_date = cnvrtDMYTmToYMDTm($trns_date);
      } */

    $ttl_prdt_price = getTtlPrdtsPrice($cnsmr_credit_id);
    //if($init_dpst_type == "Automatic"){
    $ttl_initial_deposit = getTtlItmDpsts($cnsmr_credit_id);
    //}

    $mnthly_rpymnts = getMnthlyRpymnts($ttl_prdt_price, $ttl_initial_deposit, $no_of_pymnts);

    $insSQL = "INSERT INTO scm.scm_cnsmr_credit_analys(
            cnsmr_credit_id, cust_sup_id, salary_income, fuel_allowance, 
            rent_allowance, clothing_allowance, other_allowances, debt_service_ratio, 
            loan_deductions, affordability_amnt, created_by, creation_date, 
            last_update_by, last_update_date, trns_date, marketer_person_id, 
            pymnt_option, guarantor_name, guarantor_contact_nos, guarantor_occupation, 
            guarantor_place_of_work, period_at_workplace, period_uom_at_workplace,  guarantor_email, 
			ttl_prdt_price, no_of_pymnts, init_dpst_type, ttl_initial_deposit, mnthly_rpymnts, transaction_no, org_id, src_store_id)
            VALUES ($cnsmr_credit_id, $cust_sup_id, $salary_income, $fuel_allowance, 
            $rent_allowance, $clothing_allowance, $other_allowances,  $dbtSvsRtio,   
            $loan_deductions,  $affdblty, $usrID,'" . $dateStr .
            "',$usrID,'" . $dateStr . "', '$trns_date', $marketer_person_id, 
            '$pymnt_option','" . loc_db_escape_string($guarantor_name) . "','" . loc_db_escape_string($guarantor_contact_nos) .
            "','" . loc_db_escape_string($guarantor_occupation) . "','"
            . loc_db_escape_string($guarantor_place_of_work) . "','" . loc_db_escape_string($period_at_workplace) .
            "','" . loc_db_escape_string($period_uom_at_workplace) . "','" . loc_db_escape_string($guarantor_email) . "',
			$ttl_prdt_price, $no_of_pymnts, '$init_dpst_type', $ttl_initial_deposit, $mnthly_rpymnts, '$transactionNo', $orgID, $salesStoreNmID)";
    //var_dump($insSQL);
    return execUpdtInsSQL($insSQL);
}

function updateCnsmrCrdtAnalysis($cnsmr_credit_id, $cust_sup_id, $salary_income, $fuel_allowance,
        $rent_allowance, $clothing_allowance, $other_allowances, $loan_deductions, $trns_date,
        $marketer_person_id, $pymnt_option, $guarantor_name, $guarantor_contact_nos,
        $guarantor_occupation, $guarantor_place_of_work, $period_at_workplace, $period_uom_at_workplace,
        $guarantor_email, $no_of_pymnts, $init_dpst_type, $ttl_initial_deposit, $usrID, $dateStr, $salesStoreNmID) {

    $dbtSvsRtio = round((($salary_income + $fuel_allowance +
            $rent_allowance + $clothing_allowance + $other_allowances) / 2), 2);

    $affdblty = round(($dbtSvsRtio - $loan_deductions), 2);

    //CHECK IF NO OF PAYMENT HAS CHANGED
    //IF YES EXECUTE BELOW
    //SET itm_pymnt_plan_id = -1 AND unit_selling_price to NULL
    /* execUpdtInsSQL("UPDATE scm.scm_cnsmr_credit_items SET itm_pymnt_plan_id = -1,"
      . "unit_selling_price = NULL, itm_plan_init_deposit = NULL WHERE cnsmr_credit_id = $cnsmr_credit_id"); */

    //ELSE DO NOTHING

    $ttl_prdt_price = getTtlPrdtsPrice($cnsmr_credit_id);
    //if($init_dpst_type == "Automatic"){
    $ttl_initial_deposit = getTtlItmDpsts($cnsmr_credit_id);
    //}

    $mnthly_rpymnts = getMnthlyRpymnts($ttl_prdt_price, $ttl_initial_deposit, $no_of_pymnts);

    $updtSQL = "UPDATE scm.scm_cnsmr_credit_analys
            SET cust_sup_id = $cust_sup_id, salary_income = $salary_income, fuel_allowance = $fuel_allowance, 
            rent_allowance = $rent_allowance, clothing_allowance = $clothing_allowance, 
			other_allowances = $other_allowances, debt_service_ratio = $dbtSvsRtio, 
            loan_deductions = $loan_deductions, affordability_amnt = $affdblty, 
            last_update_by = $usrID, last_update_date = '" . $dateStr . "', trns_date = '$trns_date', marketer_person_id = $marketer_person_id, 
            pymnt_option = '$pymnt_option', guarantor_name = '" . loc_db_escape_string($guarantor_name) . "', 
			guarantor_contact_nos = '" . loc_db_escape_string($guarantor_contact_nos) . "',
			guarantor_occupation = '" . loc_db_escape_string($guarantor_occupation) . "', 
            guarantor_place_of_work = '" . loc_db_escape_string($guarantor_place_of_work) . "',  
			period_at_workplace = '" . loc_db_escape_string($period_at_workplace) . "', 
			period_uom_at_workplace = '" . loc_db_escape_string($period_uom_at_workplace) . "',  
			guarantor_email = '" . loc_db_escape_string($guarantor_email) . "', 
			ttl_prdt_price = $ttl_prdt_price, no_of_pymnts = $no_of_pymnts, init_dpst_type = '$init_dpst_type', 
			ttl_initial_deposit =$ttl_initial_deposit, mnthly_rpymnts = $mnthly_rpymnts, src_store_id = $salesStoreNmID
    WHERE cnsmr_credit_id = $cnsmr_credit_id";

    return execUpdtInsSQL($updtSQL);
}

function deleteCnsmrCrdtAnalysis($cnsmr_credit_id) {
    $delSQL1 = "DELETE FROM scm.scm_cnsmr_credit_items WHERE cnsmr_credit_id = $cnsmr_credit_id";
    $delSQL2 = "DELETE FROM scm.scm_post_dated_cheques WHERE cnsmr_credit_id = $cnsmr_credit_id";
    $delSQL3 = "DELETE FROM scm.scm_cnsmr_credit_analys WHERE cnsmr_credit_id = $cnsmr_credit_id";

    execUpdtInsSQL($delSQL1);
    execUpdtInsSQL($delSQL2);
    return execUpdtInsSQL($delSQL3);
}

function getCnsmrCrdtAnalysisID() {
    $sqlStr = "SELECT nextval('scm.scm_cnsmr_credit_analys_cnsmr_credit_id_seq'::regclass);";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -1;
}

function get_CnsmrCrdtAnalysisDet($cnsmr_credit_id) {
    $strSql = "SELECT cnsmr_credit_id, a.cust_sup_id, cust_sup_name, salary_income, fuel_allowance, /**4**/
       rent_allowance, clothing_allowance, other_allowances, debt_service_ratio, /**8**/
       loan_deductions, affordability_amnt, to_char(to_timestamp(trns_date,'YYYY-MM-DD'),'DD-Mon-YYYY') trns_date, /**11**/
	   (SELECT title||' '||first_name ||' '|| other_names ||' '|| sur_name FROM prs.prsn_names_nos
	   WHERE person_id = marketer_person_id) marketer_person, /**12**/ 
       pymnt_option, guarantor_name, guarantor_contact_nos, guarantor_occupation, /**16**/
       guarantor_place_of_work, period_at_workplace, period_uom_at_workplace, guarantor_email, /**20**/
       ttl_prdt_price, no_of_pymnts, ttl_initial_deposit, mnthly_rpymnts, init_dpst_type, /**25**/
       marketer_person_id, transaction_no, status /**28**/
  FROM scm.scm_cnsmr_credit_analys a, scm.scm_cstmr_suplr b
  WHERE a.cust_sup_id = b.cust_sup_id
  AND cnsmr_credit_id = $cnsmr_credit_id";

    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_CnsmrCrdtAnalysis($searchFor, $searchIn, $offset, $limit_size, $sortBy) {
    global $orgID;
    $whereClause = "";
    $strSql = "";
    $ordrBy = "";
    if ($sortBy == "Value") {
        $ordrBy = "a.ttl_prdt_price DESC";
    } else {
        $ordrBy = "a.cnsmr_credit_id DESC";
    }


    if ($searchIn == "Customer Name") {
        $whereClause = " and (b.cust_sup_name ilike '" . loc_db_escape_string($searchFor) .
                "')";
    } else if ($searchIn == "Transaction No.") {
        $whereClause = " and (a.transaction_no ilike '" . loc_db_escape_string($searchFor) .
                "')";
    } else if ($searchIn == "Marketer") {
        $whereClause = " and ((SELECT title||' '||first_name ||' '|| other_names ||' '|| sur_name FROM prs.prsn_names_nos
	   WHERE person_id = a.marketer_person_id) ilike '" . loc_db_escape_string($searchFor) .
                "')";
    }
    $strSql = "SELECT cnsmr_credit_id, cust_sup_name, transaction_no,  ttl_prdt_price, affordability_amnt,
        no_of_pymnts, mnthly_rpymnts, to_char(to_timestamp(trns_date,'YYYY-MM-DD'),'DD-Mon-YYYY') trns_date, (SELECT title||' '||first_name ||' '|| other_names ||' '|| sur_name FROM prs.prsn_names_nos
	   WHERE person_id = marketer_person_id) marketer_person,  debt_service_ratio, guarantor_name,  a.cust_sup_id,  salary_income,
       fuel_allowance, rent_allowance, clothing_allowance, other_allowances,  loan_deductions, 
       pymnt_option,  guarantor_contact_nos, guarantor_occupation,  guarantor_place_of_work, 
       period_at_workplace, period_uom_at_workplace,  guarantor_email,  ttl_initial_deposit,  init_dpst_type, status
  FROM scm.scm_cnsmr_credit_analys a, scm.scm_cstmr_suplr b " .
            "WHERE ((a.org_id = " . $orgID . " AND a.cust_sup_id = b.cust_sup_id)$whereClause) ORDER BY $ordrBy LIMIT " . $limit_size .
            " OFFSET " . abs($offset * $limit_size);
// echo $strSql;
// and a.item_type not ilike 'VaultItem%'
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_CnsmrCrdtAnalysisTtl($searchFor, $searchIn) {
    global $orgID;
    $whereClause = "";
    $strSql = "";
//Total Quantity
    if ($searchIn == "Customer Name") {
        $whereClause = " and (b.cust_sup_name ilike '" . loc_db_escape_string($searchFor) .
                "')";
    } else if ($searchIn == "Transaction No.") {
        $whereClause = " and (a.transaction_no ilike '" . loc_db_escape_string($searchFor) .
                "')";
    } else if ($searchIn == "Marketer") {
        $whereClause = " and ((SELECT title||' '||first_name ||' '|| other_names ||' '|| sur_name FROM prs.prsn_names_nos
	   WHERE person_id = a.marketer_person_id) ilike '" . loc_db_escape_string($searchFor) .
                "')";
    }

    $strSql = "select count(1) 
            from (SELECT 1 FROM scm.scm_cnsmr_credit_analys a, scm.scm_cstmr_suplr b " .
            "WHERE ((a.org_id = " . $orgID . " AND a.cust_sup_id = b.cust_sup_id)$whereClause))TBL1";
// and a.item_type not ilike 'VaultItem%'
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return 0;
}

//Total Items Product Price
function getTtlPrdtsPrice($cnsmr_credit_id) {
    $sqlStr = "SELECT sum(coalesce(unit_selling_price,0.00) * coalesce(qty,1)) FROM scm.scm_cnsmr_credit_items WHERE cnsmr_credit_id = $cnsmr_credit_id";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return 0.00;
}

//Total Items Expected Deposit
function getTtlItmDpsts($cnsmr_credit_id) {
    $sqlStr = "SELECT sum(coalesce(itm_plan_init_deposit,0.00))  FROM scm.scm_cnsmr_credit_items WHERE cnsmr_credit_id = $cnsmr_credit_id";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return 0.00;
}

function getMnthlyRpymnts($ttlPrdtsPrice, $initDpsts, $noOfRpymnts) {
    $mntlyPymnt = round(($ttlPrdtsPrice - $initDpsts) / $noOfRpymnts, 2);
    return $mntlyPymnt;
}

function getCnsmrCrdtItemCount($cnsmr_credit_id) {
    $strSql = "SELECT count(*)
  FROM scm.scm_cnsmr_credit_items
WHERE cnsmr_credit_id = $cnsmr_credit_id";

    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -1;
}

function getItmPlanSllnPrice($itm_pymnt_plan_id) {
    $sqlStr = "SELECT plan_price, initial_deposit FROM inv.inv_itm_payment_plans WHERE itm_pymnt_plan_id = $itm_pymnt_plan_id";
    $result = executeSQLNoParams($sqlStr);

    return $result;
}

function updateCnsmrCrdtAnalysisStatus($cnsmr_credit_id, $status) {
    $updtSQL1 = "UPDATE scm.scm_cnsmr_credit_analys SET status = '$status' WHERE cnsmr_credit_id = $cnsmr_credit_id";

    return execUpdtInsSQL($updtSQL1);
}

//ITEM PAYMENT PLAN SETUP
function insertItemPymntPlansSetup($itm_pymnt_plan_setup_id, $plan_name, $no_of_pymnts, $plan_price_type, $plan_price, $initial_deposit_type, $initial_deposit, $order_no, $is_enabled, $usrID, $dateStr) {
    global $orgID;
    $insSQL = "INSERT INTO inv.inv_itm_payment_plans_setup(
            itm_pymnt_plan_setup_id, plan_name, no_of_pymnts, plan_price_type,
            plan_price, initial_deposit_type, initial_deposit, order_no, is_enabled, created_by, creation_date, last_update_by, 
            last_update_date, org_id)
            VALUES ($itm_pymnt_plan_setup_id, '" . loc_db_escape_string($plan_name) . "', $no_of_pymnts, '" . loc_db_escape_string($plan_price_type) . "', 
			$plan_price, '" . loc_db_escape_string($initial_deposit_type) . "', $initial_deposit, $order_no, '$is_enabled',
            $usrID,'" . $dateStr . "'," . $usrID . ",'" . $dateStr . "', $orgID)";

    return execUpdtInsSQL($insSQL);
}

function updateItemPymntPlansSetup($itm_pymnt_plan_setup_id, $plan_name, $no_of_pymnts, $plan_price_type, $plan_price, $initial_deposit_type, $initial_deposit, $order_no, $is_enabled, $usrID, $dateStr) {
    $updtSQL = "UPDATE inv.inv_itm_payment_plans_setup
   SET plan_name='" . loc_db_escape_string($plan_name) . "', no_of_pymnts=$no_of_pymnts, 
       plan_price_type = '" . loc_db_escape_string($plan_price_type) . "', plan_price=$plan_price, 
	   initial_deposit_type = '" . loc_db_escape_string($initial_deposit_type) . "', initial_deposit = $initial_deposit,
       order_no = $order_no, is_enabled = '$is_enabled', last_update_by=$usrID, 
       last_update_date='" . $dateStr . "'
    WHERE itm_pymnt_plan_setup_id = $itm_pymnt_plan_setup_id";

    return execUpdtInsSQL($updtSQL);
}

function deleteItemPymntPlansSetup($itm_pymnt_plan_setup_id) {
    $delSQL1 = "DELETE FROM inv.inv_itm_payment_plans_setup WHERE itm_pymnt_plan_setup_id = $itm_pymnt_plan_setup_id";

    return execUpdtInsSQL($delSQL1);
}

function getItemPymntPlansSetupID() {
    $sqlStr = "SELECT nextval('inv.inv_itm_payment_plans_itm_pymnt_plan_id_seq'::regclass);";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -1;
}

function getItmPymntPlansSetupDets($itm_pymnt_plan_setup_id) {
    $strSql = "SELECT itm_pymnt_plan_setup_id, plan_name, no_of_pymnts, plan_price_type, plan_price, is_enabled, initial_deposit_type, initial_deposit, order_no
  FROM inv.inv_itm_payment_plans_setup x
  WHERE itm_pymnt_plan_setup_id = $itm_pymnt_plan_setup_id";

    $result = executeSQLNoParams($strSql);
    return $result;
}

function getItmPymntPlansSetupTbl($isEnabled, $searchFor, $searchIn, $offset, $limit_size, $orgID, $searchAll, $sortBy) {
    $extra1 = "";

    if ($searchAll == true) {
        $extra1 = "or 1 = 1";
    }

    $strSql = "";
    $whrcls = "";
    if ($searchIn == "Plan Name") {
        $whrcls .= " AND (a.plan_name ilike '" . loc_db_escape_string($searchFor) .
                "') ";
    }

    if ($isEnabled == "true") {
        $whrcls .= " and upper(a.is_enabled) = 'YES'";
    }

    $strSql = "SELECT itm_pymnt_plan_setup_id, plan_name, no_of_pymnts, plan_price, is_enabled, initial_deposit, plan_price_type, initial_deposit_type, order_no
        FROM inv.inv_itm_payment_plans_setup a  WHERE (1 = 1 AND (a.org_id = " . $orgID . " " . $extra1 . ")" . $whrcls .
            ") ORDER BY order_no ASC LIMIT " . $limit_size .
            " OFFSET " . abs($offset * $limit_size);
    $result = executeSQLNoParams($strSql);
    //var_dump($strSql);
    return $result;
}

function getItmPymntPlansSetupTblTtl($isEnabled, $searchFor, $searchIn, $orgID, $searchAll) {
    $extra1 = "";
    $extra2 = "";

    if ($searchAll == true) {
        $extra1 = "or 1 = 1";
    }

    $strSql = "";
    $whrcls = "";
    if ($searchIn == "Plan Name") {
        $whrcls .= " AND (a.plan_name ilike '" . loc_db_escape_string($searchFor) .
                "') ";
    }

    if ($isEnabled == "true") {
        $whrcls .= " and upper(a.is_enabled) = 'YES'";
    }

    $strSql = "SELECT count(1) " .
            "FROM inv.inv_itm_payment_plans_setup a WHERE (1 = 1 AND (a.org_id = " . $orgID . " " . $extra1 . ")" . $whrcls . $extra2 .
            ")";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return 0;
}

//FROM 13042020
function createCreditAnalysisSalesInvoice($cnsmrCreditID, $sbmtdScmSalesInvcID, $scmSalesInvcInvcCurID, $scmSalesInvcVchType, $rqStatus) {
    //GET CUSTOMER ID AND STORE ID
    $myCustID = -1;
    $myStoreID = -1;
    $scmSalesInvcClssfctn = "";
    $myCustSiteID = -1;
    $creditItmId = -1;
    $prdtItemId = -1;
    $prdtVendorId = -1;
    $prdtQty = 0;
    $sllnPrice = 0.00;
    $initDpst = 0.00;
    $cnta1 = 0;
    $cnta2 = 0;
    $cnta5 = 0;
    $ln_TaxID = -1;
    $ln_DscntID = -1;
    $ln_ChrgID = -1;
    $ln_OrgnlPrice = 0.00;
    $ln_LineDesc = "";
    $ln_AstAcntID = -1;
    $ln_CogsID = -1;
    $ln_SalesRevID = -1;
    $ln_SalesRetID = -1;
    $ln_PurcRetID = -1;
    $ln_ExpnsID = -1;
    $ln_IsDlvrd = false;
    $ln_SrcDocLnID = -1;
    $ln_ReturnRsn = "";
    $ln_LnkdPrsnID = -1;
    $ln_CnsgnIDs = ",";
    $ln_TrnsLnID = -1;
    $afftctd = 0;
    $errMsg1 = "";
    $cnta6 = 0;
    $trnsNo = "";

    $strSql1 = "SELECT cust_sup_id, src_store_id, ttl_prdt_price, 'Consumer Finance' invoice_clsfctn, transaction_no FROM scm.scm_cnsmr_credit_analys WHERE cnsmr_credit_id = $cnsmrCreditID";

    $result1 = executeSQLNoParams($strSql1);
    while ($row1 = loc_db_fetch_array($result1)) {
        $myCustID = (int) $row1[0];
        $myStoreID = (int) $row1[1];
        $trnsNo = $row1[4];
    }

    //GET CUSTOMER SITE ID
    $strSql2 = "SELECT cust_sup_site_id FROM scm.scm_cstmr_suplr_sites WHERE cust_supplier_id = $myCustID LIMIT 1";

    $result2 = executeSQLNoParams($strSql2);
    while ($row2 = loc_db_fetch_array($result2)) {
        $myCustSiteID = (int) $row2[0];
        ;
    }

    //UPDATE scm.scm_sales_invc_hdr
    $updtSQL1 = "UPDATE scm.scm_sales_invc_hdr SET customer_id = $myCustID, customer_site_id = $myCustSiteID, invoice_clsfctn = 'Consumer Finance', "
            . " comments_desc = '$trnsNo' WHERE invc_hdr_id = $sbmtdScmSalesInvcID";
    $cnta1 = execUpdtInsSQL($updtSQL1);

    //UPDATE scm.scm_cnsmr_credit_analys
    $updtSQL2 = " UPDATE scm.scm_cnsmr_credit_analys SET src_invc_hdr_id =  $sbmtdScmSalesInvcID WHERE cnsmr_credit_id = $cnsmrCreditID";
    $cnta2 = execUpdtInsSQL($updtSQL2);

    $result3 = get_CreditItems($cnsmrCreditID);
    while ($row3 = loc_db_fetch_array($result3)) {
        $creditItmId = (int) $row3[0];
        $prdtItemId = (int) $row3[1];
        $prdtVendorId = (int) $row3[3];
        $prdtQty = (float) $row3[7];
        $sllnPrice = (float) $row3[9];
        $initDpst = (float) $row3[8];

        //GET RELEVANT DETAILS OF ITEM
        $strSql4 = "SELECT tax_code_id, dscnt_code_id, extr_chrg_id, orgnl_selling_price, item_desc, inv_asset_acct_id, cogs_acct_id, 
            sales_rev_accnt_id,  sales_ret_accnt_id, purch_ret_accnt_id, expense_accnt_id FROM inv.inv_itm_list WHERE item_id = $prdtItemId";

        $result4 = executeSQLNoParams($strSql4);
        while ($row4 = loc_db_fetch_array($result4)) {
            $ln_TaxID = (int) $row4[0];
            $ln_DscntID = (int) $row4[1];
            $ln_ChrgID = (int) $row4[2];
            $ln_OrgnlPrice = (float) $row4[3];
            $ln_LineDesc = $row4[4];
            $ln_AstAcntID = (int) $row4[5];
            $ln_CogsID = (int) $row4[6];
            $ln_SalesRevID = (int) $row4[7];
            $ln_SalesRetID = (int) $row4[8];
            $ln_PurcRetID = (int) $row4[9];
            $ln_ExpnsID = (int) $row4[10];

            $ln_TrnsLnID = getNewSalesInvcLnID();

            $afftctd += createSalesDocLn($ln_TrnsLnID, $sbmtdScmSalesInvcID, $prdtItemId, $prdtQty, $sllnPrice,
                    $myStoreID, $scmSalesInvcInvcCurID, $ln_SrcDocLnID, $ln_TaxID, $ln_DscntID, $ln_ChrgID,
                    $ln_ReturnRsn, $ln_CnsgnIDs, $ln_OrgnlPrice, $ln_IsDlvrd, $ln_LnkdPrsnID, $ln_LineDesc,
                    $ln_AstAcntID, $ln_CogsID, $ln_SalesRevID, $ln_SalesRetID, $ln_PurcRetID, $ln_ExpnsID);

            $errMsg1 = reCalcSalesInvcSmmrys($sbmtdScmSalesInvcID, $scmSalesInvcVchType, $myCustID, $scmSalesInvcInvcCurID, $rqStatus);

            //UPDATE LINES WITH CREDIT_ITEM LINES WITH SALES INVOICE LINE IDS
            $updtSQL5 = " UPDATE scm.scm_cnsmr_credit_items SET src_invc_det_ln_id = $ln_TrnsLnID  WHERE credit_itm_id = $creditItmId";
            $cnta5 = execUpdtInsSQL($updtSQL5);

            //UPDATE INITIAL DEPOSIT
            /* $updtSQL6 = " UPDATE scm.scm_sales_invc_det SET extra_desc = 'Initial Deposit: $initDpst' WHERE invc_det_ln_id = $ln_TrnsLnID";
              $cnta6 = execUpdtInsSQL($updtSQL6); */
        }
    }

    return $afftctd;
}

function isSalesInvLnkdToCrdtAnlsys($sbmtdScmSalesInvcID) {
    $strSql = "SELECT count(*)
  FROM scm.scm_cnsmr_credit_analys
WHERE src_invc_hdr_id = $sbmtdScmSalesInvcID";

    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -1;
}

/*CLINIC HOSPITAL*/
function createHospAppntmntSalesInvoice($appntmntID, $sbmtdScmSalesInvcID, $scmSalesInvcInvcCurID, $scmSalesInvcVchType, $rqStatus){
    //GET CUSTOMER ID AND STORE ID
	global $usrID;
        global $orgID;
    $dateStr = getDB_Date_time();
    $myCustID = -1;
    $myStoreID = -1;
    $scmSalesInvcClssfctn = "";
    $myCustSiteID  = -1;
    $appDataItmId = -1;
    $prdtItemId = -1;
    $prdtVendorId = -1;
    $prdtQty = 0;
    $sllnPrice = 0.00;
    $initDpst = 0.00;
    $cnta1 = 0;
    $cnta2 = 0;
    $cnta5 = 0;
    $ln_TaxID = -1;
    $ln_DscntID = -1;
    $ln_ChrgID = -1;
    $ln_OrgnlPrice = 0.00;
    $ln_LineDesc = "";
    $ln_AstAcntID = -1;
    $ln_CogsID = -1;
    $ln_SalesRevID = -1;
    $ln_SalesRetID = -1;
    $ln_PurcRetID = -1;
    $ln_ExpnsID = -1;
    $ln_IsDlvrd = false;
    $ln_SrcDocLnID = -1;
    $ln_ReturnRsn = "";
    $ln_LnkdPrsnID = -1;
    $ln_CnsgnIDs = ",";
    $ln_TrnsLnID = -1;
    $afftctd = 0;
    $errMsg1 = "";
    $cnta6 = 0;
    $trnsNo = "";
    
    $strSql1 = "SELECT src_store_id, appntmnt_no FROM hosp.appntmnt WHERE appntmnt_id = $appntmntID";
    
    $result1 = executeSQLNoParams($strSql1);
    while ($row1 = loc_db_fetch_array($result1)) {
        $myStoreID = (int) $row1[0];
        $trnsNo = $row1[1];
    }


	//GET customer_id	
    $vstID = (int)getGnrlRecNm("hosp.appntmnt", "appntmnt_id", "vst_id", $appntmntID);
	$ptntPrsnID = (int)getGnrlRecNm("hosp.visit", "vst_id", "prsn_id", $vstID);

	$cstmrNm = "";
	$resultYY = executeSQLNoParams("SELECT cust_sup_id, cust_sup_name
		FROM scm.scm_cstmr_suplr a WHERE lnkd_prsn_id > 0 AND lnkd_prsn_id = $ptntPrsnID AND org_id = $orgID LIMIT 1");
	$rowYYCnt = loc_db_num_fields($resultYY);
	if($rowYYCnt > 0){
		while ($rowYY = loc_db_fetch_array($resultYY)) {
			$myCustID = (int)$rowYY[0];
			$cstmrNm = $rowYY[1];
		}
	}
    
    //GET CUSTOMER SITE ID
    $strSql2 = "SELECT cust_sup_site_id FROM scm.scm_cstmr_suplr_sites WHERE cust_supplier_id = $myCustID LIMIT 1";

    $result2 = executeSQLNoParams($strSql2);
    while ($row2 = loc_db_fetch_array($result2)) {
        $myCustSiteID = (int) $row2[0];;
    }
    
    //UPDATE scm.scm_sales_invc_hdr
    $updtSQL1 = "UPDATE scm.scm_sales_invc_hdr SET customer_id = $myCustID, customer_site_id = $myCustSiteID, invoice_clsfctn = 'Standard', "
            . " comments_desc = '$trnsNo' WHERE invc_hdr_id = $sbmtdScmSalesInvcID";
    $cnta1 = execUpdtInsSQL($updtSQL1);
        
    $result3 = getAppntmntDataItems($appntmntID);
    while ($row3 = loc_db_fetch_array($result3)) {
        $appDataItmId = (int)$row3[0];
        $prdtItemId = (int)$row3[1];
        $prdtQty = (float)$row3[3];
        $sllnPrice = (float)$row3[11];
        $initDpst = 0;
        
       //GET RELEVANT DETAILS OF ITEM
        $strSql4 = "SELECT tax_code_id, dscnt_code_id, extr_chrg_id, orgnl_selling_price, item_desc, inv_asset_acct_id, cogs_acct_id, 
            sales_rev_accnt_id,  sales_ret_accnt_id, purch_ret_accnt_id, expense_accnt_id FROM inv.inv_itm_list WHERE item_id = $prdtItemId";

        $result4 = executeSQLNoParams($strSql4);
        while ($row4 = loc_db_fetch_array($result4)) {
            $ln_TaxID = (int)$row4[0];
            $ln_DscntID = (int)$row4[1];
            $ln_ChrgID = (int)$row4[2];
            $ln_OrgnlPrice = (float)$row4[3];
            $ln_LineDesc = $row4[4];
            $ln_AstAcntID = (int)$row4[5];
            $ln_CogsID = (int)$row4[6];
            $ln_SalesRevID = (int)$row4[7];
            $ln_SalesRetID = (int)$row4[8];
            $ln_PurcRetID = (int)$row4[9];
            $ln_ExpnsID = (int)$row4[10];
                   
            $ln_TrnsLnID = getNewSalesInvcLnID();
            
            $afftctd += createSalesDocLn($ln_TrnsLnID, $sbmtdScmSalesInvcID, $prdtItemId, $prdtQty, $sllnPrice,
		$myStoreID, $scmSalesInvcInvcCurID, $ln_SrcDocLnID, $ln_TaxID, $ln_DscntID, $ln_ChrgID,
		$ln_ReturnRsn, $ln_CnsgnIDs, $ln_OrgnlPrice, $ln_IsDlvrd, $ln_LnkdPrsnID, $ln_LineDesc,
		$ln_AstAcntID, $ln_CogsID, $ln_SalesRevID, $ln_SalesRetID, $ln_PurcRetID, $ln_ExpnsID);
            
            $errMsg1 = reCalcSalesInvcSmmrys($sbmtdScmSalesInvcID, $scmSalesInvcVchType, $myCustID, $scmSalesInvcInvcCurID, $rqStatus);
            
            //UPDATE LINES WITH SALES INVOICE DETAILS
            $updtSQL5 = " UPDATE hosp.appntmnt_data_items SET invc_det_ln_id = $ln_TrnsLnID, invc_hdr_id =  $sbmtdScmSalesInvcID,
			last_update_by = $usrID,  last_update_date = '$dateStr', is_billed = 'Y'		
			WHERE appdt_itm_id = $appDataItmId";
            $cnta5 = execUpdtInsSQL($updtSQL5);
			
            
        }
        
    }

    return $afftctd;
}

function isSalesInvLnkdToAppntmnt($sbmtdScmSalesInvcID) {
    $strSql = "SELECT count(*)
  FROM hosp.appntmnt_data_items
WHERE invc_hdr_id = $sbmtdScmSalesInvcID";

    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -1;
}

function calcAppntmntDataItmsTtlAmt($appntmntID, $invc_hdr_id){ 
    $ttlAmnt = 0;
    
    $strSql = "SELECT distinct item_id, coalesce(qty,0) FROM hosp.appntmnt_data_items WHERE appntmnt_id = $appntmntID AND invc_hdr_id = $invc_hdr_id AND invc_hdr_id > 0";
    $result = executeSQLNoParams($strSql);
    while($row = loc_db_fetch_array($result)){
        
        $strSql4 = "SELECT orgnl_selling_price FROM inv.inv_itm_list WHERE item_id = $row[0]";
        $result4 = executeSQLNoParams($strSql4);
        while($row4 = loc_db_fetch_array($result4)){
            $ttlAmnt =  $ttlAmnt + (float)$row[1] * (float)$row4[0];
        }       
    }
    return round($ttlAmnt,2);
}

function getAppntmntDataItems($appntmntID) {
    $strSql = "SELECT appdt_itm_id, x.item_id, item_desc||' ('||item_code||')' itm_desc, qty, /**3**/
        cmnts, CASE WHEN is_billed = 'Y' THEN 'Yes' ELSE 'No' END is_billed, CASE WHEN is_paid = 'Y' THEN 'Yes' ELSE 'No' END is_paid, /**6**/
        x.invc_hdr_id, invc_number, x.invc_det_ln_id, appntmnt_id, /**10**/
        y.orgnl_selling_price /**11**/
  FROM hosp.appntmnt_data_items x INNER JOIN inv.inv_itm_list y ON x.item_id = y.item_id
  LEFT OUTER JOIN scm.scm_sales_invc_hdr z ON x.invc_hdr_id = z.invc_hdr_id
  LEFT OUTER JOIN scm.scm_sales_invc_det w ON x.invc_det_ln_id = w.invc_det_ln_id AND w.itm_id = x.item_id
  WHERE appntmnt_id = $appntmntID AND x.invc_hdr_id = -1 AND x.invc_det_ln_id = -1 AND x.is_billed = 'N' ";

    $result = executeSQLNoParams($strSql);
    return $result;
}

function getAppntmntDataItemsIDHotl() {
    $sqlStr = "SELECT nextval('hosp.appntmnt_data_items_appdt_itm_id_seq'::regclass);";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -1;
}

function insertAppntmntDataItemsAllCols($appdt_itm_id, $appntmnt_id, $item_id, $qty, $invc_hdr_id, $invc_det_ln_id, $is_billed, $usrID, $dateStr, $uomID, $src_srvstype_row_id) {
    global $orgID;
    $insSQL = "INSERT INTO hosp.appntmnt_data_items(
            appdt_itm_id, appntmnt_id, qty, created_by, creation_date, 
            last_update_by, last_update_date, item_id, uom_id, src_srvstype_row_id, invc_hdr_id, invc_det_ln_id, is_billed)
            VALUES ($appdt_itm_id, $appntmnt_id, $qty,
            $usrID,'" . $dateStr . "'," . $usrID . ",'" . $dateStr . "', $item_id, $uomID, $src_srvstype_row_id, $invc_hdr_id, $invc_det_ln_id, '$is_billed')";

    return execUpdtInsSQL($insSQL);
}

function getInvcHdrChckInID($invcHdrId){
    $strSql = "SELECT other_mdls_doc_id FROM scm.scm_sales_invc_hdr WHERE invc_hdr_id =  $invcHdrId AND other_mdls_doc_type = 'Check-In'";
    $result = executeSQLNoParams($strSql);
    while($row = loc_db_fetch_array($result)){
        return (int)$row[0];
    }
    return -1;
}
/*CLINIC HOSPITAL*/

?>