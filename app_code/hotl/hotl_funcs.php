<?php

function get_Checkins($searchWord, $searchIn, $offset, $limit_size, $orgID, $shwActive, $shwUnsettled, $extrWhere, $shwSelfOnly,
        $qShwMyBranch) {
    global $vwOnlySelf;
    global $usrID;
    global $brnchLocID;
    global $vwOnlyBranch;
    /* Doc. Status
      Created By
      Customer
      Purpose of Visit
      Document Number
      Facility Number
      Start Date */
    $strSql = "";
    $whereClause = "";
    $activeDocClause = "";
    $unstldBillClause = "";
    if ($shwUnsettled == true) {
        $unstldBillClause = " AND EXISTS (SELECT f.src_doc_hdr_id 
FROM scm.scm_doc_amnt_smmrys f WHERE f.smmry_type='7Change/Balance' 
and round(f.smmry_amnt,2)>0 and coalesce(y.invc_hdr_id,z.invc_hdr_id)=f.src_doc_hdr_id and f.src_doc_type=coalesce(y.invc_type,z.invc_type)
 and coalesce(y.approval_status,z.approval_status) != 'Cancelled')";
    }
    if ($shwActive == true) {
        $activeDocClause = " AND (a.doc_status IN ('Reserved','Checked-In','Ordered','Rented Out','Suscribed'))";
    }
    if ($vwOnlySelf === true || $shwSelfOnly === true) {
        $activeDocClause .= " and (a.created_by = " . $usrID . ")";
    }
    if ($vwOnlyBranch === true || $qShwMyBranch === true) {
        $activeDocClause .= " and (coalesce(y.branch_id,z.branch_id) = " . $brnchLocID . ")";
    }
    if ($searchIn == "Doc. Status") {
        $whereClause = "(a.doc_status ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Created By") {
        $whereClause = "(a.created_by IN (select c.user_id from sec.sec_users c where c.user_name ilike '" . loc_db_escape_string($searchWord) .
                "'))";
    } else if ($searchIn == "Customer") {
        $whereClause = "(a.sponsor_id IN (select c.cust_sup_id from scm.scm_cstmr_suplr c where c.cust_sup_name ilike '" . loc_db_escape_string($searchWord) .
                "') or a.customer_id IN (select c.cust_sup_id from scm.scm_cstmr_suplr c where c.cust_sup_name ilike '" . loc_db_escape_string($searchWord) .
                "') or a.sponsor_id = -1 or a.customer_id = -1)";
    } else if ($searchIn == "Facility Number" || $searchIn == "Table/Room Number") {
        $whereClause = "(coalesce(b.room_name,'') ilike '" . loc_db_escape_string($searchWord) . "' or (SELECT STRING_AGG(p.room_name, ', ' ORDER BY 1)
                                                                       FROM hotl.rooms p,
                                                                            hotl.checkins_hdr k
                                                                       WHERE p.room_id = k.service_det_id
                                                                         AND a.check_in_id = k.prnt_chck_in_id
                                                                         AND k.prnt_chck_in_id > 0
                                                                         AND a.doc_type = k.prnt_doc_typ) ilike '" . loc_db_escape_string($searchWord) .
                "')";
    } else if ($searchIn == "Purpose of Visit") {
        $whereClause = "(a.other_info ilike '" . loc_db_escape_string($searchWord) .
                "')";
    } else if ($searchIn == "Document Number") {
        $whereClause = "(a.doc_num ilike '" . loc_db_escape_string($searchWord) .
                "' or y.invc_number ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Start Date") {
        $whereClause = "(to_char(to_timestamp(a.start_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') ilike '" . loc_db_escape_string($searchWord) .
                "')";
    } else {
        $whereClause = "(a.doc_num ilike '" . loc_db_escape_string($searchWord) .
                "' or coalesce(y.invc_number,z.invc_number) ilike '" . loc_db_escape_string($searchWord) .
                "' or a.other_info ilike '" . loc_db_escape_string($searchWord) .
                "')";
        /* $whereClause = "(b.room_name ilike '" . loc_db_escape_string($searchWord) .
          "' or (Select p.room_name from hotl.rooms p, hotl.checkins_hdr k
          where p.room_id = k.service_det_id and a.prnt_chck_in_id=k.check_in_id
          and a.prnt_doc_typ = k.doc_type ORDER BY 1 LIMIT 1 OFFSET 0) ilike '" . loc_db_escape_string($searchWord) .
          "')"; */
    }
    $strSql = "SELECT a.check_in_id, a.doc_num, coalesce(y.invc_number,z.invc_number), 
        coalesce(y.invc_hdr_id,z.invc_hdr_id), coalesce(y.invc_type,z.invc_type), 
        coalesce(y.comments_desc,z.comments_desc), gst.get_pssbl_val(coalesce(y.invc_curr_id,z.invc_curr_id)), 
        round(scm.get_DocSmryGrndTtl(coalesce(y.invc_hdr_id,z.invc_hdr_id),coalesce(y.invc_type,z.invc_type)),2) invoice_amount, 
        round(scm.get_DocSmryPymtRcvd(coalesce(y.invc_hdr_id,z.invc_hdr_id),coalesce(y.invc_type,z.invc_type)),2) amnt_paid, 
        round(scm.get_DocSmryOutsbls(coalesce(y.invc_hdr_id,z.invc_hdr_id),coalesce(y.invc_type,z.invc_type)),2) balance, 
        coalesce(y.approval_status,z.approval_status), scm.get_cstmr_splr_name( coalesce(y.customer_id,z.customer_id)), 
        coalesce(y.branch_id,z.branch_id) branch_id, org.get_site_code_desc(coalesce(y.branch_id,z.branch_id)), a.doc_type, a.doc_status 
                        FROM hotl.checkins_hdr a 
    LEFT OUTER JOIN hotl.rooms b ON (a.service_det_id = b.room_id AND a.service_det_id>0) 
    LEFT OUTER JOIN scm.scm_sales_invc_hdr y ON ((a.check_in_id = y.other_mdls_doc_id and a.doc_type = y.other_mdls_doc_type AND a.prnt_chck_in_id <= 0))
    LEFT OUTER JOIN scm.scm_sales_invc_hdr z ON ((a.prnt_chck_in_id = z.other_mdls_doc_id AND z.other_mdls_doc_id > 0 AND a.prnt_doc_typ = z.other_mdls_doc_type AND a.prnt_doc_typ != '' AND a.prnt_chck_in_id > 0)) " .
            "WHERE " . $whereClause . $activeDocClause . $unstldBillClause .
            " and (coalesce(y.org_id, z.org_id)=" . $orgID . " or coalesce(y.org_id, z.org_id) IS NULL)" . $extrWhere .
            " ORDER BY a.check_in_id DESC LIMIT " . $limit_size .
            " OFFSET " . (abs($offset * $limit_size));
    /*
      $strSql = "SELECT a.check_in_id, a.doc_num, coalesce(y.invc_number,z.invc_number),
      coalesce(y.invc_hdr_id,z.invc_hdr_id), coalesce(y.invc_type,z.invc_type),
      coalesce(y.comments_desc,z.comments_desc), gst.get_pssbl_val(coalesce(y.invc_curr_id,z.invc_curr_id)),
      round(scm.get_DocSmryGrndTtl(coalesce(y.invc_hdr_id,z.invc_hdr_id),coalesce(y.invc_type,z.invc_type)),2) invoice_amount,
      round(scm.get_DocSmryPymtRcvd(coalesce(y.invc_hdr_id,z.invc_hdr_id),coalesce(y.invc_type,z.invc_type)),2) amnt_paid,
      round(scm.get_DocSmryOutsbls(coalesce(y.invc_hdr_id,z.invc_hdr_id),coalesce(y.invc_type,z.invc_type)),2) balance,
      coalesce(y.approval_status,z.approval_status), scm.get_cstmr_splr_name( coalesce(y.customer_id,z.customer_id)),
      coalesce(y.branch_id,z.branch_id) branch_id, org.get_site_code_desc(coalesce(y.branch_id,z.branch_id))
      FROM hotl.checkins_hdr a
      LEFT OUTER JOIN hotl.service_types d ON (a.service_type_id=d.service_type_id)
      LEFT OUTER JOIN hotl.rooms b ON (a.service_det_id = b.room_id)
      LEFT OUTER JOIN scm.scm_sales_invc_hdr y ON ((a.check_in_id = y.other_mdls_doc_id and a.doc_type = y.other_mdls_doc_type))
      LEFT OUTER JOIN scm.scm_sales_invc_hdr z ON ((a.prnt_chck_in_id = z.other_mdls_doc_id AND z.other_mdls_doc_id > 0 AND a.prnt_doc_typ = z.other_mdls_doc_type AND a.prnt_doc_typ != '')) " .
      "WHERE " . $whereClause . $activeDocClause . $unstldBillClause . " and d.org_id=" . $orgID .
      " and coalesce(y.invc_hdr_id,z.invc_hdr_id)>0" . $extrWhere .
      " ORDER BY a.check_in_id DESC LIMIT " . $limit_size .
      " OFFSET " . (abs($offset * $limit_size));
     */
    //echo $strSql;
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_Ttl_Checkins($searchWord, $searchIn, $orgID, $shwActive, $shwUnsettled, $extrWhere, $shwSelfOnly, $qShwMyBranch) {
    global $vwOnlySelf;
    global $usrID;
    global $brnchLocID;
    global $vwOnlyBranch;
    /* Doc. Status
      Created By
      Customer
      Purpose of Visit
      Document Number
      Facility Number
      Start Date */
    /* $whereClause = "(b.room_name ilike '" . loc_db_escape_string($searchWord) .
      "' or (Select p.room_name from hotl.rooms p, hotl.checkins_hdr k
      where p.room_id = k.service_det_id and a.prnt_chck_in_id=k.check_in_id
      and a.prnt_doc_typ = k.doc_type ORDER BY 1 LIMIT 1 OFFSET 0) ilike '" . loc_db_escape_string($searchWord) .
      "')"; */
    $strSql = "";
    $whereClause = "";
    $activeDocClause = "";
    $unstldBillClause = "";
    if ($shwUnsettled == true) {
        $unstldBillClause = " AND EXISTS (SELECT f.src_doc_hdr_id 
FROM scm.scm_doc_amnt_smmrys f WHERE f.smmry_type='7Change/Balance' 
and round(f.smmry_amnt,2)>0 and coalesce(y.invc_hdr_id,z.invc_hdr_id)=f.src_doc_hdr_id and f.src_doc_type=coalesce(y.invc_type,z.invc_type)
 and coalesce(y.approval_status,z.approval_status) != 'Cancelled')";
    }
    if ($shwActive == true) {
        $activeDocClause = " AND (a.doc_status IN ('Reserved','Checked-In','Ordered','Rented Out','Suscribed'))";
    }
    if ($vwOnlySelf === true || $shwSelfOnly === true) {
        $activeDocClause .= " and (a.created_by = " . $usrID . ")";
    }
    if ($vwOnlyBranch === true || $qShwMyBranch === true) {
        $activeDocClause .= " and (coalesce(y.branch_id,z.branch_id) = " . $brnchLocID . ")";
    }
    if ($searchIn == "Doc. Status") {
        $whereClause = "(a.doc_status ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Created By") {
        $whereClause = "(a.created_by IN (select c.user_id from sec.sec_users c where c.user_name ilike '" . loc_db_escape_string($searchWord) .
                "'))";
    } else if ($searchIn == "Customer") {
        $whereClause = "(a.sponsor_id IN (select c.cust_sup_id from scm.scm_cstmr_suplr c where c.cust_sup_name ilike '" . loc_db_escape_string($searchWord) .
                "') or a.customer_id IN (select c.cust_sup_id from scm.scm_cstmr_suplr c where c.cust_sup_name ilike '" . loc_db_escape_string($searchWord) .
                "') or a.sponsor_id = -1 or a.customer_id = -1)";
    } else if ($searchIn == "Facility Number" || $searchIn == "Table/Room Number") {
        $whereClause = "(coalesce(b.room_name,'') ilike '" . loc_db_escape_string($searchWord) . "' or (SELECT STRING_AGG(p.room_name, ', ' ORDER BY 1)
                                                                       FROM hotl.rooms p,
                                                                            hotl.checkins_hdr k
                                                                       WHERE p.room_id = k.service_det_id
                                                                         AND a.check_in_id = k.prnt_chck_in_id
                                                                         AND k.prnt_chck_in_id > 0
                                                                         AND a.doc_type = k.prnt_doc_typ) ilike '" . loc_db_escape_string($searchWord) .
                "')";
    } else if ($searchIn == "Purpose of Visit") {
        $whereClause = "(a.other_info ilike '" . loc_db_escape_string($searchWord) .
                "')";
    } else if ($searchIn == "Document Number") {
        $whereClause = "(a.doc_num ilike '" . loc_db_escape_string($searchWord) .
                "' or y.invc_number ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Start Date") {
        $whereClause = "(to_char(to_timestamp(a.start_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') ilike '" . loc_db_escape_string($searchWord) .
                "')";
    } else {
        $whereClause = "(a.doc_num ilike '" . loc_db_escape_string($searchWord) .
                "' or coalesce(y.invc_number,z.invc_number) ilike '" . loc_db_escape_string($searchWord) .
                "' or a.other_info ilike '" . loc_db_escape_string($searchWord) .
                "')";
    }
    $strSql = "SELECT count(1) " .
            "FROM hotl.checkins_hdr a
    LEFT OUTER JOIN hotl.rooms b ON (a.service_det_id = b.room_id AND a.service_det_id>0) 
    LEFT OUTER JOIN scm.scm_sales_invc_hdr y ON ((a.check_in_id = y.other_mdls_doc_id and a.doc_type = y.other_mdls_doc_type AND a.prnt_chck_in_id <= 0))
    LEFT OUTER JOIN scm.scm_sales_invc_hdr z ON ((a.prnt_chck_in_id = z.other_mdls_doc_id AND z.other_mdls_doc_id > 0 AND a.prnt_doc_typ = z.other_mdls_doc_type AND a.prnt_doc_typ != '' AND a.prnt_chck_in_id > 0)) " .
            "WHERE " . $whereClause . $activeDocClause . $unstldBillClause .
            " and (coalesce(y.org_id, z.org_id)=" . $orgID . " or coalesce(y.org_id, z.org_id) IS NULL)" . $extrWhere;
    /* "FROM hotl.checkins_hdr a 
      LEFT OUTER JOIN hotl.service_types d ON (a.service_type_id=d.service_type_id )
      LEFT OUTER JOIN hotl.rooms b ON (a.service_det_id = b.room_id)
      LEFT OUTER JOIN scm.scm_sales_invc_hdr y ON ((a.check_in_id = y.other_mdls_doc_id and a.doc_type = y.other_mdls_doc_type))
      LEFT OUTER JOIN scm.scm_sales_invc_hdr z ON ((a.prnt_chck_in_id = z.other_mdls_doc_id AND z.other_mdls_doc_id > 0 AND a.prnt_doc_typ = z.other_mdls_doc_type AND a.prnt_doc_typ != ''))
      WHERE " . $whereClause . $activeDocClause . $unstldBillClause . " and d.org_id=" . $orgID .
      " and coalesce(y.invc_hdr_id,z.invc_hdr_id)>0" . $extrWhere; */
    //echo $strSql;
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return 0;
}

function get_One_CheckinDt($checkInID) {
    $strSql = "Select a.check_in_id, a.doc_num, a.doc_type, a.fclty_type, 
        to_char(to_timestamp(a.start_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS'), 
        to_char(to_timestamp(a.end_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS'), 
        a.service_type_id, d.service_type_name,
       a.service_det_id, b.room_name, a.no_of_adults, a.no_of_children, a.sponsor_id, a.sponsor_site_id, 
       a.customer_id, a.customer_site_id, a.arriving_from, a.proceeding_to, 
       a.other_info, a.created_by, a.doc_status, COALESCE(y.invc_hdr_id,-1), y.invc_number, 
        y.pymny_method_id, accb.get_pymnt_mthd_name(y.pymny_method_id), y.invc_curr_id, 
        gst.get_pssbl_val(COALESCE(y.invc_curr_id,-1)), COALESCE(y.exchng_rate,1), y.approval_status, 
        y.invc_type, a.prnt_chck_in_id,a.prnt_doc_typ, COALESCE(y.enbl_auto_misc_chrges,'0'), a.use_nights, y.payment_terms, sec.get_usr_name(a.created_by), 
        scm.get_cstmr_splr_name(a.sponsor_id), scm.get_cstmr_splr_site_name(a.sponsor_site_id) , 
        scm.get_cstmr_splr_name(a.customer_id), scm.get_cstmr_splr_site_name(a.customer_site_id),
        hotl.get_doc_num(a.prnt_chck_in_id) prnt_doc_num, y.event_rgstr_id, y.payment_terms
       FROM hotl.checkins_hdr a 
        LEFT OUTER JOIN hotl.service_types d ON (a.service_type_id=d.service_type_id )
        LEFT OUTER JOIN hotl.rooms b ON (a.service_det_id = b.room_id)
        LEFT OUTER JOIN scm.scm_sales_invc_hdr y ON ((a.check_in_id = y.other_mdls_doc_id or (a.prnt_chck_in_id=y.other_mdls_doc_id and y.other_mdls_doc_id>0))
        and (a.doc_type=y.other_mdls_doc_type or (a.prnt_doc_typ=y.other_mdls_doc_type and a.prnt_doc_typ != ''))) " .
            "WHERE a.check_in_id=" . $checkInID . " ";
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_One_CheckinChckns($checkInID) {
    $strSql = "Select a.check_in_id, a.doc_num, a.doc_type, a.fclty_type, 
to_char(to_timestamp(a.start_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS'), 
to_char(to_timestamp(a.end_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS'), 
a.service_type_id, d.service_type_name,
       a.service_det_id, b.room_name, a.no_of_adults, a.no_of_children, a.sponsor_id, a.sponsor_site_id, 
       a.customer_id, a.customer_site_id, a.arriving_from, a.proceeding_to, 
       a.other_info, a.created_by, a.doc_status, a.prnt_chck_in_id,a.prnt_doc_typ, 
 a.use_nights, scm.get_cstmr_splr_name(a.customer_id), hotl.get_chkn_rntd_qty(hotl.get_main_item_id(a.service_type_id),a.check_in_id,a.doc_type) " .
            "FROM hotl.checkins_hdr a 
LEFT OUTER JOIN hotl.service_types d ON (a.service_type_id = d.service_type_id)
LEFT OUTER JOIN hotl.rooms b ON (a.service_det_id = b.room_id) " .
            "WHERE a.prnt_chck_in_id=" . $checkInID . " ";
    //echo $strSql;
    $result = executeSQLNoParams($strSql);
    return $result;
}

function createCheckIn($docNum, $docType, $strtDte, $endDte, $srvsTypID, $srvsDteID, $noAdlts, $NoChldrn, $spnsID, $spnsSiteID, $cstmrID,
        $cstmrSiteID, $srcPlace, $destPlace, $otherInfo, $fcltyType, $docStatus, $prntChckIn, $prntDocType, $useNights) {
    global $usrID;
    global $orgID;
    if ($strtDte != "") {
        $strtDte = cnvrtDMYTmToYMDTm($strtDte);
    }
    if ($endDte != "") {
        $endDte = cnvrtDMYTmToYMDTm($endDte);
    }
    if (strlen($otherInfo) > 400) {
        $otherInfo = substr($otherInfo, 0, 400);
    }
    $insSQL = "INSERT INTO hotl.checkins_hdr(
            doc_num, doc_type, start_date, end_date, service_type_id, 
            service_det_id, no_of_adults, no_of_children, sponsor_id, sponsor_site_id, 
            customer_id, customer_site_id, arriving_from, proceeding_to, 
            other_info, created_by, creation_date, last_update_by, last_update_date, 
            fclty_type, doc_status, prnt_chck_in_id, prnt_doc_typ, use_nights) " .
            "VALUES ('" . loc_db_escape_string($docNum) .
            "', '" . loc_db_escape_string($docType) .
            "', '" . loc_db_escape_string($strtDte) .
            "', '" . loc_db_escape_string($endDte) .
            "', " . $srvsTypID . ", " . $srvsDteID . ", " . $noAdlts .
            ", " . $NoChldrn . ", " . $spnsID . ", " . $spnsSiteID .
            ", " . $cstmrID . ", " . $cstmrSiteID . ", '" . loc_db_escape_string($srcPlace) .
            "', '" . loc_db_escape_string($destPlace) .
            "', '" . loc_db_escape_string($otherInfo) .
            "', " . $usrID . ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), " . $usrID .
            ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), '" . loc_db_escape_string($fcltyType) .
            "', '" . loc_db_escape_string($docStatus) .
            "', " . $prntChckIn . ", '" . loc_db_escape_string($prntDocType) .
            "', '" . loc_db_escape_string($useNights) . "')";
    //echo $insSQL;
    execUpdtInsSQL($insSQL);
    $chckInID = getGnrlRecID2("hotl.checkins_hdr", "doc_num", "check_in_id", $docNum);
    return $chckInID;
}

function updateCheckIn($chckInID, $docNum, $docType, $strtDte, $endDte, $srvsTypID, $srvsDteID, $noAdlts, $NoChldrn, $spnsID, $spnsSiteID,
        $cstmrID, $cstmrSiteID, $srcPlace, $destPlace, $otherInfo, $fcltyType, $docStatus, $prntChckIn, $prntDocType, $useNights) {
    global $usrID;
    if ($strtDte != "") {
        $strtDte = cnvrtDMYTmToYMDTm($strtDte);
    }
    if ($endDte != "") {
        $endDte = cnvrtDMYTmToYMDTm($endDte);
    }
    if (strlen($otherInfo) > 400) {
        $otherInfo = substr($otherInfo, 0, 400);
    }
    $updtSQL = "UPDATE hotl.checkins_hdr
   SET doc_num='" . loc_db_escape_string($docNum) .
            "', doc_type='" . loc_db_escape_string($docType) .
            "', start_date='" . loc_db_escape_string($strtDte) .
            "', end_date='" . loc_db_escape_string($endDte) .
            "', service_type_id=" . $srvsTypID .
            ", service_det_id=" . $srvsDteID .
            ", no_of_adults=" . $noAdlts .
            ", no_of_children=" . $NoChldrn .
            ", sponsor_id=" . $spnsID .
            ", sponsor_site_id=" . $spnsSiteID .
            ", customer_id=" . $cstmrID .
            ", customer_site_id=" . $cstmrSiteID .
            ", arriving_from='" . loc_db_escape_string($srcPlace) .
            "', proceeding_to='" . loc_db_escape_string($destPlace) .
            "', other_info='" . loc_db_escape_string($otherInfo) .
            "', last_update_by=" . $usrID . ", last_update_date=to_char(now(),'YYYY-MM-DD HH24:MI:SS'), fclty_type='" . loc_db_escape_string($fcltyType) .
            "', doc_status='" . loc_db_escape_string($docStatus) .
            "', prnt_chck_in_id = " . $prntChckIn .
            ", prnt_doc_typ='" . loc_db_escape_string($prntDocType) .
            "', use_nights = '" . loc_db_escape_string($useNights) . "' WHERE (check_in_id =" . $chckInID . ")";
			
	        /*CLINIC/HOSPITAL*/
        $admsn_id = getGnrlRecNm("hosp.inpatient_admissions", "ref_check_in_id", "admsn_id", $chckInID);
        if($admsn_id == ""){
            $admsn_id = -1;
        }

        if($admsn_id > 0){
            execUpdtInsSQL("UPDATE hosp.inpatient_admissions SET facility_type_id = $srvsTypID, room_id = $srvsDteID,"
                . "checkin_date = '" . loc_db_escape_string($strtDte) . "', checkout_date='" . loc_db_escape_string($endDte) 
                . "' WHERE ref_check_in_id = $chckInID");
        }
        /*CLINIC/HOSPITAL*/
	
    return execUpdtInsSQL($updtSQL);
}

function deleteCheckIn($chckInID, $salesChckInNum) {
    $trnsCnt1 = 0;
    $valLnid = get_One_ChcknSalesID($chckInID);
    $docStatus = getGnrlRecNm("scm.scm_sales_invc_hdr", "invc_hdr_id", "approval_status", $valLnid);
    $docNum = getGnrlRecNm("scm.scm_sales_invc_hdr", "invc_hdr_id", "invc_number", $valLnid);
    $docTypeLnsDlvrd = (int) getGnrlRecNm("scm.scm_sales_invc_hdr", "invc_hdr_id", "scm.getsaleslnsdlvrd(invc_hdr_id)", $valLnid);
    if ($docTypeLnsDlvrd > 0 || $docStatus == "Approved" || $docStatus == "Initiated" || $docStatus == "Validated" || $docStatus == "Cancelled" || strpos($docStatus, "Reviewed") !== FALSE) {
        $trnsCnt1 = 1;
    }
    if ($trnsCnt1 > 0) {
        $dsply = "No Record Deleted<br/>Cannot DELETE Approved, Initiated, Reviewed, Validated, Cancelled Documents or lines Delivered Already!!";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
    $delSQL = "DELETE FROM hotl.checkins_hdr WHERE check_in_id = " . $chckInID;
    $affctd1 = execUpdtInsSQL($delSQL, "Sales/CheckIn Number = " . $salesChckInNum);
    $affctd2 = 0;
    if ($affctd1 > 0) {
        $dsply = "";
        $dsply .= "<br/>Successfully Executed the ff-";
        $dsply .= "<br/>Deleted $affctd2 Document Line(s)!";
        $dsply .= "<br/>Deleted $affctd1 Document(s)!";
        $dsply .= "<br/>" . deleteSalesDocHdrNDet($valLnid, $docNum);
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Deleted";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function createActvtyRslt($progress_id, $chckInID, $actvtyID,
        $rsltCmmnt, $strtDte, $endDte, $isCmplt, $hrsDone) {
    global $usrID;
    if ($strtDte != "") {
        $strtDte = cnvrtDMYTmToYMDTm($strtDte);
    }
    if ($endDte != "") {
        $endDte = cnvrtDMYTmToYMDTm($endDte);
    }
    $insSQL = "INSERT INTO hotl.gym_actvty_progress(
            progress_id, check_in_id, activity_id, start_date, end_date, 
            hours_done, is_complete, remarks, created_by, creation_date, 
            last_update_by, last_update_date) " .
            "VALUES (" . $progress_id . "," . $chckInID . ", " . $actvtyID .
            ", '" . $strtDte .
            "', '" . $endDte .
            "', " . $hrsDone . ",'" . cnvrtBoolToBitStr($isCmplt) .
            "', '" . loc_db_escape_string($rsltCmmnt) .
            "', " . $usrID . ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), " . $usrID . ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'))";
    return execUpdtInsSQL($insSQL);
}

function updateActvtyRslt($progress_id, $rsltCmmnt, $strtDte, $endDte, $isCmplt, $hrsDone) {
    global $usrID;
    if ($strtDte != "") {
        $strtDte = cnvrtDMYTmToYMDTm($strtDte);
    }
    if ($endDte != "") {
        $endDte = cnvrtDMYTmToYMDTm($endDte);
    }
    $updtSQL = "UPDATE hotl.gym_actvty_progress SET " .
            "remarks='" . loc_db_escape_string($rsltCmmnt) .
            "', hours_done=" . $hrsDone .
            ", start_date='" . loc_db_escape_string($strtDte) .
            "', end_date='" . loc_db_escape_string($endDte) .
            "', last_update_by = " . $usrID . ", " .
            "last_update_date = to_char(now(),'YYYY-MM-DD HH24:MI:SS'), is_complete='" . cnvrtBoolToBitStr($isCmplt) .
            "' WHERE (progress_id =" . $progress_id . ")";
    return execUpdtInsSQL($updtSQL);
}

function deleteActvtyRslt($lnID, $pkeyNm) {
    $delSQL = "DELETE FROM hotl.gym_actvty_progress WHERE progress_id = " .
            $lnID . "";
    $affctd1 = execUpdtInsSQL($delSQL, "Activity Name = " . $pkeyNm);
    if ($affctd1 > 0) {
        $dsply = "";
        $dsply .= "<br/>Successfully Executed the ff-";
        $dsply .= "<br/>Deleted $affctd1 Activity Line(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Deleted";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function updatePrntCheckIn($chckInID, $prntChckIn) {
    global $usrID;
    $updtSQL = "UPDATE hotl.checkins_hdr
   SET prnt_chck_in_id=" . $prntChckIn .
            ", prnt_doc_typ='Check-In', last_update_by=" . $usrID .
            ", last_update_date=to_char(now(),'YYYY-MM-DD HH24:MI:SS') WHERE (check_in_id =" . $chckInID . ")";
    return execUpdtInsSQL($updtSQL);
}

function isRoomsFree($roomid, $strtDte, $endDte) {
    if ($strtDte != "") {
        $strtDte = cnvrtDMYTmToYMDTm($strtDte);
    }
    if ($endDte != "") {
        $endDte = cnvrtDMYTmToYMDTm($endDte);
    }
    $strSql = "SELECT a.check_in_id " .
            "FROM hotl.checkins_hdr a " .
            "WHERE(a.service_det_id = " . $roomid .
            " and (a.doc_status='Reserved' or a.doc_status='Checked-In' or a.doc_status='Rented Out') and (to_timestamp('" . $strtDte . "','YYYY-MM-DD HH24:MI:SS') between 
                to_timestamp(a.start_date,'YYYY-MM-DD HH24:MI:SS') 
                AND to_timestamp(a.end_date,'YYYY-MM-DD HH24:MI:SS') or to_timestamp('" . $endDte .
            "','YYYY-MM-DD HH24:MI:SS') between to_timestamp(a.start_date,'YYYY-MM-DD HH24:MI:SS') 
            AND to_timestamp(a.end_date,'YYYY-MM-DD HH24:MI:SS')))";
    $result = executeSQLNoParams($strSql);
    if (loc_db_num_rows($result) > 0) {
        $row = loc_db_fetch_array($result);
        return $row[0];
    }
    return -1;
}

function updtCheckInStatus($docid, $apprvlSts) {
    global $usrID;
    $updtSQL = "UPDATE hotl.checkins_hdr SET " .
            "doc_status='" . $apprvlSts . "', last_update_by=" . $usrID .
            ", last_update_date=to_char(now(),'YYYY-MM-DD HH24:MI:SS') WHERE ((check_in_id = " .
            $docid . " and check_in_id>0) or (prnt_chck_in_id = " . $docid . " and prnt_chck_in_id>0))";
    return execUpdtInsSQL($updtSQL);
}

function updtRoomDirtyStatus($roomID, $isDirty) {
    global $usrID;
    $updtSQL = "UPDATE hotl.rooms SET " .
            "needs_hse_keeping='" . cnvrtBoolToBitStr($isDirty) .
            "', last_update_by=" . $usrID .
            ", last_update_date=to_char(now(),'YYYY-MM-DD HH24:MI:SS') WHERE (room_id = " .
            $roomID . ")";
    return execUpdtInsSQL($updtSQL);
}

function updateRoomOccpntCnt() {
    $updtSQL = "UPDATE hotl.rooms a 
SET crnt_no_occpnts = COALESCE((SELECT sum(b.no_of_adults) 
  FROM hotl.checkins_hdr b
  WHERE (b.doc_status='Ordered') and a.room_id = b.service_det_id),0)
+ COALESCE((SELECT COALESCE(sum(COALESCE(e.rented_itm_qty,0)),0) 
  FROM hotl.checkins_hdr c, scm.scm_sales_invc_det e, hotl.service_types f 
  WHERE ((e.other_mdls_doc_id = c.check_in_id) 
and (e.other_mdls_doc_type = c.doc_type) 
and a.service_type_id = f.service_type_id 
and f.inv_item_id = e.itm_id 
 and c.doc_status IN ('Checked-In','Rented Out')
and a.room_id = c.service_det_id)),0)";
    return execUpdtInsSQL($updtSQL);
}

function getCstmrDpsts($cstmrID, $invcurID) {
    $selSQL = "select SUM(invoice_amount-invc_amnt_appld_elswhr) c, customer_id e, 
invc_curr_id f from accb.accb_rcvbls_invc_hdr where (((rcvbls_invc_type = 'Customer Advance Payment' and (invoice_amount-amnt_paid)<=0) 
or rcvbls_invc_type = 'Customer Debit Memo (InDirect Refund)') 
and approval_status='Approved' and (invoice_amount-invc_amnt_appld_elswhr)>0 and customer_id = " . $cstmrID . " and customer_id>0 and invc_curr_id = " . $invcurID . ") 
GROUP BY customer_id,invc_curr_id";
    $result = executeSQLNoParams($selSQL);
    if (loc_db_num_rows($result) > 0) {
        $row = loc_db_fetch_array($result);
        return (float) $row[0];
    }
    return 0.00;
}

function deleteComplaint($lnID, $lnNm) {
    $delSQL = "DELETE FROM hotl.cmplnts_obsvrtns WHERE complaint_id = " . $lnID;
    $affctd1 = execUpdtInsSQL($delSQL, "Type=" . $lnNm);

    if ($affctd1 > 0) {
        $dsply = "";
        $dsply .= "<br/>Successfully Executed the ff-";
        $dsply .= "<br/>Deleted $affctd1 Complaint(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Deleted";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function get_Complaints($searchWord, $searchIn, $offset, $limit_size, $chkInID) {
    global $orgID;
    $strSql = "";
    $whrcls = "";
    $extrWhere = "";
    if ($chkInID > 0) {
        $extrWhere = " AND (a.src_doc_id = " . $chkInID . ")";
    }
    /* Complaint/Observation Type
      Customer
      Description
      Status
      Person to Resolve
     */
    if ($searchIn == "Complaint/Observation Type") {
        $whrcls = " AND (a.classification ilike '" . loc_db_escape_string($searchWord) .
                "')";
    } else if ($searchIn == "Date Created") {
        $whrcls = " AND (to_char(to_timestamp(a.creation_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') ilike '" . loc_db_escape_string($searchWord) .
                "')";
    } else if ($searchIn == "Customer") {
        $whrcls = " AND (COALESCE(scm.get_cstmr_splr_name(a.customer_id),'') ilike '" . loc_db_escape_string($searchWord) .
                "')";
    } else if ($searchIn == "Description") {
        $whrcls = " AND (a.description ilike '" . loc_db_escape_string($searchWord) .
                "')";
    } else if ($searchIn == "Person to Resolve") {
        $whrcls = " AND (COALESCE(prs.get_prsn_name(a.person_to_resolve),'') ilike '" . loc_db_escape_string($searchWord) .
                "')";
    } else if ($searchIn == "Status") {
        $whrcls = " AND ((CASE WHEN a.is_resolved='1' THEN 'RESOLVED' ELSE 'PENDING' END) ilike '" . loc_db_escape_string($searchWord) .
                "')";
    } else {
        $whrcls = " AND (a.classification ilike '" . loc_db_escape_string($searchWord) .
                "')";
    }
    $strSql = "SELECT a.complaint_id, 
a.classification, a.description,a.suggestion_solution, a.customer_id, scm.get_cstmr_splr_name(a.customer_id),
a.person_to_resolve, prs.get_prsn_name(a.person_to_resolve),a.is_resolved,
      (CASE WHEN a.is_resolved='1' THEN 'RESOLVED' ELSE 'PENDING' END), 
      to_char(to_timestamp(a.creation_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS')
|| ' (' || hotl.get_doc_num(a.src_doc_id) || ')'
  FROM hotl.cmplnts_obsvrtns a " .
            "WHERE(a.org_id = " . $orgID . $whrcls . $extrWhere .
            ") ORDER BY a.creation_date, 1 LIMIT " . $limit_size .
            " OFFSET " . (abs($offset * $limit_size));

    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_Total_Complaints($searchWord, $searchIn, $chkInID) {
    global $orgID;
    $strSql = "";
    $whrcls = "";
    $extrWhere = "";
    if ($chkInID > 0) {
        $extrWhere = " AND (a.src_doc_id = " . $chkInID . ")";
    }
    /* Complaint/Observation Type
      Customer
      Description
      Status
      Person to Resolve
     */
    if ($searchIn == "Complaint/Observation Type") {
        $whrcls = " AND (a.classification ilike '" . loc_db_escape_string($searchWord) .
                "')";
    } else if ($searchIn == "Date Created") {
        $whrcls = " AND (to_char(to_timestamp(a.creation_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') ilike '" . loc_db_escape_string($searchWord) .
                "')";
    } else if ($searchIn == "Customer") {
        $whrcls = " AND (COALESCE(scm.get_cstmr_splr_name(a.customer_id),'') ilike '" . loc_db_escape_string($searchWord) .
                "')";
    } else if ($searchIn == "Description") {
        $whrcls = " AND (a.description ilike '" . loc_db_escape_string($searchWord) .
                "')";
    } else if ($searchIn == "Person to Resolve") {
        $whrcls = " AND (COALESCE(prs.get_prsn_name(a.person_to_resolve),'') ilike '" . loc_db_escape_string($searchWord) .
                "')";
    } else if ($searchIn == "Status") {
        $whrcls = " AND ((CASE WHEN a.is_resolved='1' THEN 'RESOLVED' ELSE 'PENDING' END) ilike '" . loc_db_escape_string($searchWord) .
                "')";
    } else {
        $whrcls = " AND (a.classification ilike '" . loc_db_escape_string($searchWord) .
                "')";
    }
    $strSql = "SELECT count(1) 
  FROM hotl.cmplnts_obsvrtns a " .
            "WHERE(a.org_id = " . $orgID . $whrcls . $extrWhere .
            ")";
    $result = executeSQLNoParams($strSql);
    if (loc_db_num_rows($result) > 0) {
        $row = loc_db_fetch_array($result);
        return (float) $row[0];
    }
    return 0.00;
}

function getNewCmplntID() {
    $strSql = "select nextval('hotl.cmplnts_obsvrtns_complaint_id_seq')";
    $result = executeSQLNoParams($strSql);
    if (loc_db_num_rows($result) > 0) {
        $row = loc_db_fetch_array($result);
        return (float) $row[0];
    }
    return -1;
}

function createComplaint($complaint_id, $prsnID, $srcDocID, $cstmrID, $srcDocType, $clssfctn, $descptn, $sltn, $isRslvd) {
    global $orgID;
    global $usrID;
    $insSQL = "INSERT INTO hotl.cmplnts_obsvrtns(
            complaint_id, classification, description, suggestion_solution, 
            customer_id, src_doc_id, src_doc_type, is_resolved, created_by, 
            creation_date, last_update_by, last_update_date, person_to_resolve, org_id) " .
            "VALUES (" . $complaint_id . ", '" . loc_db_escape_string($clssfctn) .
            "', '" . loc_db_escape_string($descptn) .
            "', '" . loc_db_escape_string($sltn) .
            "', " . $cstmrID . ", " . $srcDocID . ", '" . loc_db_escape_string($srcDocType) .
            "',  '" . cnvrtBoolToBitStr($isRslvd) .
            "', " . $usrID . ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), " . $usrID .
            ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), " . $prsnID .
            ", " . $orgID . ")";
    return execUpdtInsSQL($insSQL);
}

function updateComplaint($cplntID, $prsnID, $srcDocID, $cstmrID, $srcDocType, $clssfctn, $descptn, $sltn, $isRslvd) {
    global $orgID;
    global $usrID;
    $updtSQL = "UPDATE hotl.cmplnts_obsvrtns SET " .
            "classification='" . loc_db_escape_string($clssfctn) .
            "', description='" . loc_db_escape_string($descptn) .
            "', suggestion_solution='" . loc_db_escape_string($sltn) .
            "', is_resolved='" . cnvrtBoolToBitStr($isRslvd) .
            "', person_to_resolve=" . $prsnID .
            ", last_update_by = " . $usrID . ", " .
            "last_update_date = to_char(now(),'YYYY-MM-DD HH24:MI:SS') WHERE (complaint_id =" . $cplntID . ")";
    return execUpdtInsSQL($updtSQL);
}

/*CLINIC/HOSPITAL*/
function isHospitalityLnkdToClinic($chckInId) {
    $strSql = "SELECT count(*)
  FROM hosp.inpatient_admissions
WHERE ref_check_in_id = $chckInId AND ref_check_in_id > 0";

    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -1;
}

function getChkInAdmsnId($chckInId) {
    $strSql = "SELECT admsn_id FROM hosp.inpatient_admissions
WHERE ref_check_in_id = $chckInId AND ref_check_in_id > 0";

    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -1;
}
/*CLINIC/HOSPITAL*/
?>