<?php

$menuItems = array("My Events"/* , "My Assigned Events" */, "Attendance Registers",
    "Time Tables", "Activities/Events", "Venues", "Attendance Search", "All Event Invoices", "Default Event Accounts", "All Activities Search");
$menuImages = array("calendar2.png"/* , "Calander.png" */, "openfileicon.png", "chcklst1.png",
    "Folder.png", "house_72.png", "search.png", "bills.png", "GL-256.png", "search.png");

$mdlNm = "Events And Attendance";
$ModuleName = $mdlNm;

$dfltPrvldgs = array(
    "View Events And Attendance",
    /* 1 */ "View Attendance Records", "View Time Tables", "View Events",
    /* 4 */ "View Venues", "View Attendance Search", "View SQL", "View Record History",
    /* 8 */ "Add Attendance Records", "Edit Attendance Records", "Delete Attendance Records",
    /* 11 */ "Add Time Tables", "Edit Time Tables", "Delete Time Tables",
    /* 14 */ "Add Events", "Edit Events", "Delete Events",
    /* 17 */ "Add Venues", "Edit Venues", "Delete Venues",
    /* 20 */ "Add Event Results", "Edit Event Results", "Delete Event Results",
    /* 23 */ "View Adhoc Registers", "Add Adhoc Registers", "Edit Adhoc Registers", "Delete Adhoc Registers",
    /* 27 */ "View Event Cost", "Add Event Cost", "Edit Event Cost", "Delete Event Cost",
    /* 31 */ "View Complaints/Observations", "Add Complaints/Observations", "Edit Complaints/Observations", "Delete Complaints/Observations",
    /* 35 */ "View only Self-Created Sales", "Cancel Documents", "Take Payments", "Apply Adhoc Discounts", "Apply Pre-defined Discounts",
    /* 40 */ "Can Edit Unit Price",
    /* 41 */ "View Default Accounts", "Add Default Accounts", "Edit Default Accounts", "Delete Default Accounts"
);

$canview = test_prmssns($dfltPrvldgs[0], $mdlNm) || test_prmssns("View Events/Attendances", "Self Service");

$prsnid = $_SESSION['PRSN_ID'];
$orgID = $_SESSION['ORG_ID'];
$usrID = $_SESSION['USRID'];
$uName = $_SESSION['UNAME'];

$accbFSRptStoreID = isset($_POST['accbFSRptStoreID']) ? (int) cleanInputData($_POST['accbFSRptStoreID']) : -1;
$selectedStoreID = (int) $_SESSION['SELECTED_SALES_STOREID'];

if ($accbFSRptStoreID > 0 && getUserStorePkeyID($accbFSRptStoreID) > 0) {
    $selectedStoreID = $accbFSRptStoreID;
    $_SESSION['SELECTED_SALES_STOREID'] = $selectedStoreID;
}

$gnrlTrnsDteDMYHMS = getFrmtdDB_Date_time();
$gnrlTrnsDteYMDHMS = cnvrtDMYTmToYMDTm($gnrlTrnsDteDMYHMS);
$gnrlTrnsDteYMD = substr($gnrlTrnsDteYMDHMS, 0, 10);

$invPrmSnsRstl = getAttnPrmssns($prsnid, $orgID, $usrID);
$fnccurid = $invPrmSnsRstl[0];
$fnccurnm = getPssblValNm($fnccurid);
$brnchLocID = $invPrmSnsRstl[1];
$brnchLoc = getGnrlRecNm("org.org_sites_locations", "location_id",
        "REPLACE(location_code_name || '.' || site_desc, '.' || location_code_name,'')", $brnchLocID);
$acsCntrlGrpID = $invPrmSnsRstl[2];
if ($selectedStoreID <= 0) {
    $selectedStoreID = $invPrmSnsRstl[3];
    $_SESSION['SELECTED_SALES_STOREID'] = $selectedStoreID;
}
$acsCntrlGrpNm = getStoreNm($selectedStoreID);
$vwOnlySelf = ($invPrmSnsRstl[4] >= 1) ? true : false;
$canEdtPrice = ($invPrmSnsRstl[5] >= 1) ? true : false;
$cancelDocs = ($invPrmSnsRstl[6] >= 1) ? true : false;
$canPayDocs = ($invPrmSnsRstl[7] >= 1) ? true : false;
$canCreateDscnt = ($invPrmSnsRstl[8] >= 1) ? true : false;
$canApplyDscnt = ($invPrmSnsRstl[9] >= 1) ? true : false;
$vwOnlyBranch = ($invPrmSnsRstl[10] >= 1) ? true : false;

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
					</li>"
        . "<li onclick=\"openATab('#allmodules', 'grp=$group&typ=$type');\">
						<span style=\"text-decoration:none;\">Events & Attendance Menu</span>
					</li>";

if ($lgn_num > 0 && $canview === true) {
    if ($pgNo == 0) {
        $lovNm = ("Users\' Sales Stores");
        $cntent .= " </ul></div>" .
                "<div  class=\"col-md-12\" style=\"width:87% !important;max-width:87% !important;font-family: Tahoma, Arial, sans-serif;font-size: 1.3em;padding:2px 5px 2px 5px;border:1px solid #ddd;\">
                        <div class=\"col-md-5\" style=\"padding:0px 1px 0px 1px;margin-top:3px !important;\">
                            <span style=\"font-family: georgia, times;font-size: 14px;font-style:normal;
                            font-weight:bold;color:black;\">Transactions Date:</span>
                                <span style=\"font-family: tahoma;font-size: 14px;font-style:normal;
                            font-weight:bold;color:blue;\">$gnrlTrnsDteDMYHMS</span>
                        </div>
                        <div  class=\"col-md-7\" style=\"padding:0px 1px 0px 1px !important;\">
                            <div class=\"form-group\">
                                <label for=\"accbFSRptStore\" class=\"control-label col-md-4\" style=\"padding:5px 1px 0px 1px !important;font-family: georgia, times;font-size: 14px;font-style:normal;
                            font-weight:bold;color:blac\">Default Store:</label>
                                <div  class=\"col-md-8\" style=\"padding:0px 1px 0px 1px !important;\">
                                    <div class=\"input-group\">
                                        <input type=\"text\" class=\"form-control\" style=\"font-family: tahoma;font-size: 14px;font-style:normal;
                            font-weight:bold;color:blue;width:100%;\" id=\"accbFSRptStore\" name=\"accbFSRptStore\" value=\"" . $acsCntrlGrpNm . "\" readonly=\"true\">
                                        <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"accbFSRptStoreID\" name=\"accbFSRptStoreID\" value=\"" . $selectedStoreID . "\">
                                        <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', '" . $lovNm . "', 'allOtherInputOrgID', 'allOtherInputUsrID', '', 'radio', true, '', 'accbFSRptStoreID', 'accbFSRptStore', 'clear', 1, '', function () {setCurStore();});\">
                                            <span class=\"glyphicon glyphicon-th-list\"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
      ";

        $cntent .= "<div class=\"col-md-12\" style=\"width:87% !important;max-width:87% !important;font-family: Tahoma, Arial, sans-serif;font-size: 1.8em;
                    padding:10px 15px 15px 20px;border:1px solid #ccc;\">                    
      <div style=\"padding:5px 30px 5px 10px;margin-bottom:2px;\">
                    <span style=\"font-family: georgia, times;font-size: 15px;font-style:italic;
                    font-weight:normal;\">Events assigned to you, Your Attendance History and CPD Points Earned as well as Exam Scores can be seen here. The module has the ff areas:</span>
                    </div></div>
      <p>";
        $grpcntr = 0;
        for ($i = 0; $i < count($menuItems); $i++) {
            $No = $i + 1;
            if ($i == 0) {
                
            } /* else if ($i == 1) {
              //continue;
              } */ else if ($i == 1 && test_prmssns($dfltPrvldgs[1], $mdlNm) == FALSE) {
                continue;
            } else if ($i == 2 && test_prmssns($dfltPrvldgs[2], $mdlNm) == FALSE) {
                continue;
            } else if ($i == 3 && test_prmssns($dfltPrvldgs[3], $mdlNm) == FALSE) {
                continue;
            } else if ($i == 4 && test_prmssns($dfltPrvldgs[4], $mdlNm) == FALSE) {
                continue;
            } else if ($i == 5 && test_prmssns($dfltPrvldgs[5], $mdlNm) == FALSE) {
                continue;
            } else if ($i == 8 && test_prmssns($dfltPrvldgs[5], $mdlNm) == FALSE) {
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
        //Get My Events Attended
        require 'my_evnts_attn.php';
    } else if ($pgNo == 2) {
        require 'attn_rgstrs.php';
    } else if ($pgNo == 3) {
        require 'time_tbls.php';
    } else if ($pgNo == 4) {
        require 'events_stp.php';
    } else if ($pgNo == 5) {
        require 'venues_stp.php';
    } else if ($pgNo == 6) {
        require 'attn_search.php';
    } else if ($pgNo == 7) {
        require 'event_invoices.php';
    } else if ($pgNo == 8) {
        require 'dflt_acnts.php';
    } else if ($pgNo == 9) {
        require 'event_results.php';
    } else {
        restricted();
    }
} else {
    restricted();
}

function getAttnPrmssns($prsnID, $orgid, $usrID) {
    global $ssnRoles;
    $mdlNm1 = "Events And Attendance";
    $mdlNm = "Hospitality Management";
    $rslts = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
    $sqlStr = "Select (select oprtnl_crncy_id from org.org_details where org_id = $orgid), "
            . "pasn.get_prsn_siteid(" . $prsnID . "), "
            . "pasn.get_prsn_divid_of_spctype(" . $prsnID . ",'Access Control Group'),"
            . "scm.getUserStoreID(" . $usrID . ", " . $orgid . "), "
            . "sec.test_prmssns('View only Self-Created Sales', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'),"
            . "sec.test_prmssns('Can Edit Unit Price', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'),"
            . "sec.test_prmssns('Cancel Documents', 'Stores And Inventory Manager','" . loc_db_escape_string($ssnRoles) . "'),"
            . "sec.test_prmssns('Take Payments', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'),"
            . "sec.test_prmssns('Apply Adhoc Discounts', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'),"
            . "sec.test_prmssns('Apply Pre-defined Discounts', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'),"
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
    }
    return $rslts;
}

function get_MyEvntsAttndTblr($searchFor, $searchIn, $offset, $limit_size, $prsnID) {
    $wherecls = "";
    $wherecls1 = "";
    $orgID = $_SESSION['ORG_ID'];
//"Message Header", "Message Date", "Message Status", "Source App", "Source Module"
    if ($searchIn === "Name/Number") {
        $wherecls = "(c.recs_hdr_name ilike '" .
                loc_db_escape_string($searchFor) . "' or c.recs_hdr_desc ilike '" .
                loc_db_escape_string($searchFor) . "') and ";
        $wherecls1 = "('' || a.pymnt_req_hdr_id ilike '" .
                loc_db_escape_string($searchFor) . "' or a.pymnt_req_hdr_desc ilike '" .
                loc_db_escape_string($searchFor) . "') and ";
    }

    $strSql = "SELECT attnd_rec_id mt, 
        a.recs_hdr_id m1, 
        c.recs_hdr_name reg_name, 
        c.recs_hdr_desc hdrdesc, 
        to_char(to_timestamp(c.event_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS'), 
        to_char(to_timestamp(c.end_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS'), 
        person_id m2, 
	'''' || prs.get_prsn_loc_id(a.person_id) id_no, 
      CASE WHEN a.customer_id <= 0 and a.person_id <= 0 THEN a.visitor_name_desc 
           WHEN a.person_id>0 THEN prs.get_prsn_surname(a.person_id) || ' (' || prs.get_prsn_loc_id(a.person_id) || ')' 
            ELSE scm.get_cstmr_splr_name(a.customer_id) END partcpnt_name, 
      CASE WHEN a.date_time_in != '' THEN to_char(to_timestamp(a.date_time_in,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') ELSE '' END timein, 
      CASE WHEN a.date_time_out != '' THEN to_char(to_timestamp(a.date_time_out,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') ELSE '' END timeout,
      CASE WHEN a.is_present='1' THEN 'YES' ELSE 'NO' END ispresent, 
	scm.get_cstmr_splr_name(a.sponsor_id) linked_firm,
      tbl1.invc_number invc_num, 
	COALESCE(tbl1.invoice_amnt,0) invc_ttl, 
	COALESCE(tbl1.amnt_paid,0) amnt_paid, 
	COALESCE(tbl1.amnt_left,0) balance, 
	a.attn_comments remarks,
      a.visitor_classfctn classification,
      a.point_scored1 cpd_points, 
	a.no_of_persons m3, 
        0 m4, 
        a.customer_id m5, 
        a.sponsor_id m6,
        attn.getevntamntbilled(a.recs_hdr_id, a.customer_id), 
        attn.getEvntAmntPaid(a.recs_hdr_id, a.customer_id)
  FROM attn.attn_attendance_recs a
  LEFT OUTER JOIN attn.attn_attendance_recs_hdr c ON (a.recs_hdr_id = c.recs_hdr_id) 
  LEFT OUTER JOIN (SELECT DISTINCT w.check_in_id, w.doc_num, y.invc_number, w.customer_id, 
            scm.get_doc_smry_typ_amnt(y.invc_hdr_id,'Sales Invoice','5Grand Total') invoice_amnt,
            scm.get_doc_smry_typ_amnt(y.invc_hdr_id,'Sales Invoice','6Total Payments Received')+
	    scm.get_doc_smry_typ_amnt(y.invc_hdr_id,'Sales Invoice','8Deposits') amnt_paid,
            scm.get_doc_smry_typ_amnt(y.invc_hdr_id,'Sales Invoice','9Actual_Change/Balance') amnt_left,
            y.event_doc_type,
            y.event_rgstr_id
	FROM hotl.checkins_hdr w 
	LEFT OUTER JOIN hotl.service_types d ON (w.service_type_id=d.service_type_id )
	LEFT OUTER JOIN hotl.rooms b ON (w.service_det_id = b.room_id)
	LEFT OUTER JOIN scm.scm_sales_invc_hdr y ON ((w.check_in_id = y.other_mdls_doc_id or 	(w.prnt_chck_in_id=y.other_mdls_doc_id and y.other_mdls_doc_id>0))
	and (w.doc_type=y.other_mdls_doc_type or (w.prnt_doc_typ=y.other_mdls_doc_type and w.prnt_doc_typ != ''))) 
	WHERE (w.sponsor_id IN (select c.cust_sup_id from scm.scm_cstmr_suplr c where c.cust_sup_name ilike '%') or 
	w.customer_id IN (select c.cust_sup_id from scm.scm_cstmr_suplr c where c.cust_sup_name ilike '%')) 
	and COALESCE(d.org_id, 1)=$orgID and w.doc_type IN ('Booking','Check-In') 
	and w.fclty_type IN ('Event')) tbl1  ON (a.customer_id = tbl1.customer_id  and tbl1.event_doc_type='Attendance Register' and tbl1.event_rgstr_id=a.recs_hdr_id)
   WHERE($wherecls(a.person_id = $prsnID) and ((CASE WHEN a.customer_id <= 0 and a.person_id <= 0 THEN a.visitor_name_desc 
           WHEN a.person_id>0 THEN prs.get_prsn_surname(a.person_id) || ' (' || prs.get_prsn_loc_id(a.person_id) || ')' 
            ELSE scm.get_cstmr_splr_name(a.customer_id)||scm.get_cstmr_splr_name(a.sponsor_id) END) ilike '%')) 
	ORDER BY 5, 4, 1 LIMIT " . $limit_size .
            " OFFSET " . abs($offset * $limit_size);
    //echo $strSql;
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_MyEvntsAttndTtl($searchFor, $searchIn, $prsnID) {
    $wherecls = "";
    $wherecls1 = "";
    $orgID = $_SESSION['ORG_ID'];
//"Message Header", "Message Date", "Message Status", "Source App", "Source Module"
    if ($searchIn === "Name/Number") {
        $wherecls = "(c.recs_hdr_name ilike '" .
                loc_db_escape_string($searchFor) . "' or c.recs_hdr_desc ilike '" .
                loc_db_escape_string($searchFor) . "') and ";

        $wherecls1 = "('' || a.pymnt_req_hdr_id ilike '" .
                loc_db_escape_string($searchFor) . "' or a.pymnt_req_hdr_desc ilike '" .
                loc_db_escape_string($searchFor) . "') and ";
    }

    $strSql = "SELECT count(1) 
  FROM attn.attn_attendance_recs a
  LEFT OUTER JOIN attn.attn_attendance_recs_hdr c ON (a.recs_hdr_id = c.recs_hdr_id) 
  LEFT OUTER JOIN (SELECT DISTINCT w.check_in_id, w.doc_num, y.invc_number, w.customer_id, 
            scm.get_doc_smry_typ_amnt(y.invc_hdr_id,'Sales Invoice','5Grand Total') invoice_amnt,
            scm.get_doc_smry_typ_amnt(y.invc_hdr_id,'Sales Invoice','6Total Payments Received')+
	    scm.get_doc_smry_typ_amnt(y.invc_hdr_id,'Sales Invoice','8Deposits') amnt_paid,
            scm.get_doc_smry_typ_amnt(y.invc_hdr_id,'Sales Invoice','9Actual_Change/Balance') amnt_left 
	FROM hotl.checkins_hdr w 
	LEFT OUTER JOIN hotl.service_types d ON (w.service_type_id=d.service_type_id )
	LEFT OUTER JOIN hotl.rooms b ON (w.service_det_id = b.room_id)
	LEFT OUTER JOIN scm.scm_sales_invc_hdr y ON ((w.check_in_id = y.other_mdls_doc_id or 	(w.prnt_chck_in_id=y.other_mdls_doc_id and y.other_mdls_doc_id>0))
	and (w.doc_type=y.other_mdls_doc_type or (w.prnt_doc_typ=y.other_mdls_doc_type and w.prnt_doc_typ != ''))) 
	WHERE (w.sponsor_id IN (select c.cust_sup_id from scm.scm_cstmr_suplr c where c.cust_sup_name ilike '%') or 
	w.customer_id IN (select c.cust_sup_id from scm.scm_cstmr_suplr c where c.cust_sup_name ilike '%')) 
	and COALESCE(d.org_id, 1)=$orgID and w.doc_type IN ('Booking','Check-In') 
	and w.fclty_type IN ('Event')) tbl1  ON (a.customer_id = tbl1.customer_id)
   WHERE($wherecls(a.person_id = $prsnID) and ((CASE WHEN a.customer_id <= 0 and a.person_id <= 0 THEN a.visitor_name_desc 
           WHEN a.person_id>0 THEN prs.get_prsn_surname(a.person_id) || ' (' || prs.get_prsn_loc_id(a.person_id) || ')' 
            ELSE scm.get_cstmr_splr_name(a.customer_id)||scm.get_cstmr_splr_name(a.sponsor_id) END) ilike '%')) ";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return 0;
}

function createVenue($orgid, $vnuname, $vnudesc, $vnuClssf, $isEnbld, $noofprsns) {
    global $usrID;
    $insSQL = "INSERT INTO attn.attn_event_venues(
            venue_name, venue_desc, max_no_persons, venue_classification, 
            created_by, creation_date, last_update_by, last_update_date, 
            org_id, is_enabled) " .
            "VALUES ('" . loc_db_escape_string($vnuname) .
            "', '" . loc_db_escape_string($vnudesc) .
            "', " . $noofprsns . ", '" . loc_db_escape_string($vnuClssf) .
            "', " . $usrID . ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), " . $usrID . ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), " . $orgid . ", '" .
            cnvrtBoolToBitStr($isEnbld) . "')";
    return execUpdtInsSQL($insSQL);
}

function updateVenue($vnuid, $vnuname, $vnudesc, $vnuClssf, $isEnbld, $noofprsns) {
    global $usrID;
    $updtSQL = "UPDATE attn.attn_event_venues SET " .
            "venue_name='" . loc_db_escape_string($vnuname) .
            "', venue_desc='" . loc_db_escape_string($vnudesc) .
            "', last_update_by=" . $usrID . ", " .
            "last_update_date=to_char(now(),'YYYY-MM-DD HH24:MI:SS'), venue_classification='" . loc_db_escape_string($vnuClssf) .
            "', is_enabled='" . cnvrtBoolToBitStr($isEnbld) .
            "', max_no_persons=" . $noofprsns . " " .
            "WHERE (venue_id =" . $vnuid . ")";
    return execUpdtInsSQL($updtSQL);
}

function deleteVenue($vnuid, $vnuNm) {
    $affctd1 = 0;
    if (isVnuInUse($vnuid) === false) {
        $delSQL = "DELETE FROM attn.attn_event_venues WHERE venue_id = " . $vnuid;
        $affctd1 = execUpdtInsSQL($delSQL, "Venue Name = " . $vnuNm);
    }
    if ($affctd1 > 0) {
        $dsply = "";
        $dsply .= "<br/>Successfully Executed the ff-";
        $dsply .= "<br/>Deleted $affctd1 Attendance Venue(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Deleted";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function isVnuInUse($vnuID) {
    $strSql = "SELECT a.event_id " .
            "FROM attn.attn_attendance_events a " .
            "WHERE(a.preffrd_venue_id = " . $vnuID . ")";
    $result = executeSQLNoParams($strSql);
    if (loc_db_num_rows($result) > 0) {
        return true;
    }

    $strSql = "SELECT a.time_table_det_id " .
            "FROM attn.attn_time_table_details a " .
            "WHERE(a.assgnd_venue_id = " . $vnuID . ")";
    $result = executeSQLNoParams($strSql);
    if (loc_db_num_rows($result) > 0) {
        return true;
    }
    $strSql = "SELECT a.time_table_det_id " .
            "FROM attn.temp_time_table_details a " .
            "WHERE(a.assgnd_venue_id = " . $vnuID . ")";
    $result = executeSQLNoParams($strSql);
    if (loc_db_num_rows($result) > 0) {
        return true;
    }
    return false;
}

function getVenueID($vnuname, $orgid) {
    $sqlStr = "select venue_id from attn.attn_event_venues where lower(venue_name) = lower('" .
            loc_db_escape_string($vnuname) . "') and org_id = " . $orgid;
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -1;
}

function get_One_VnuDet($codeID) {
    $strSql = "SELECT a.venue_id, a.venue_name, a.venue_desc, a.venue_classification, a.max_no_persons, a.is_enabled " .
            "FROM attn.attn_event_venues a " .
            "WHERE(a.venue_id = " . $codeID . ")";
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_Basic_Venues($searchWord, $searchIn, $offset, $limit_size, $orgID) {
    $strSql = "";
    $whrcls = "";
    if ($searchIn == "Venue Name") {
        $whrcls = " AND (a.venue_name ilike '" . loc_db_escape_string($searchWord) .
                "')";
    } else {
        $whrcls = " AND (a.venue_desc ilike '" . loc_db_escape_string($searchWord) .
                "')";
    }
    $strSql = "SELECT a.venue_id, a.venue_name " .
            "FROM attn.attn_event_venues a " .
            "WHERE ((a.org_id = " . $orgID . ")" . $whrcls . ") ORDER BY a.venue_name LIMIT " . $limit_size .
            " OFFSET " . (abs($offset * $limit_size));
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_Total_Venues($searchWord, $searchIn, $orgID) {
    $strSql = "";
    $whrcls = "";
    if ($searchIn == "Venue Name") {
        $whrcls = " AND (a.venue_name ilike '" . loc_db_escape_string($searchWord) .
                "')";
    } else {
        $whrcls = " AND (a.venue_desc ilike '" . loc_db_escape_string($searchWord) .
                "')";
    }
    $strSql = "SELECT count(1) " .
            "FROM attn.attn_event_venues a " .
            "WHERE ((a.org_id = " . $orgID . ")" . $whrcls . ")";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return 0;
}

function get_AttnRgstr_SrchLns($searchWord, $searchIn, $offset, $limit_size, $orgid, $dte1, $dte2, $sortBy) {
    if ($dte1 != "") {
        $dte1 = cnvrtDMYTmToYMDTm($dte1);
    }
    if ($dte2 != "") {
        $dte2 = cnvrtDMYTmToYMDTm($dte2);
    }
    $strSql = "";
    $whrcls = "";
    $ordrByCls = "ORDER BY 5, 1";
    if ($sortBy == "Date (DESC)") {
        $ordrByCls = "ORDER BY a.date_time_in DESC, 1";
    } else if ($sortBy == "Date (ASC)") {
        $ordrByCls = "ORDER BY a.date_time_in ASC, 1";
    } else if ($sortBy == "Person No.") {
        $ordrByCls = "ORDER BY 5, 1";
    } else if ($sortBy == "Person Name") {
        $ordrByCls = "ORDER BY 6, 1";
    }
    /*
     *  Person Name
      Date/Time In
      Date/Time Out
      Is Present?
      Attendance Comments
     * 
     */
    if ($searchIn == "Register Name") {
        $whrcls = " AND (b.recs_hdr_name ilike '" . loc_db_escape_string($searchWord) .
                "')";
    } else if ($searchIn == "Person Name/ID") {
        $whrcls = " AND ((CASE WHEN a.customer_id <= 0 and a.person_id <= 0 THEN a.visitor_name_desc 
           WHEN a.person_id>0 THEN prs.get_prsn_surname(a.person_id) || ' (' || prs.get_prsn_loc_id(a.person_id) || ')' 
            ELSE scm.get_cstmr_splr_name(a.customer_id) END) ilike '" . loc_db_escape_string($searchWord) .
                "')";
    } else if ($searchIn == "Date/Time In") {
        $whrcls = " AND (to_char(to_timestamp(a.date_time_in,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') ilike '" . loc_db_escape_string($searchWord) .
                "')";
    } else if ($searchIn == "Date/Time Out") {
        $whrcls = " AND (to_char(to_timestamp(a.date_time_out,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') ilike '" . loc_db_escape_string($searchWord) .
                "')";
    } else if ($searchIn == "Is Present?") {
        $whrcls = " AND ((CASE WHEN a.is_present='1' THEN 'TRUE/YES/PRESENT' ELSE 'FALSE/NO/ABSENT' END) ilike '" . loc_db_escape_string($searchWord) .
                "')";
    } else if ($searchIn == "Attendance Comments") {
        $whrcls = " AND (a.attn_comments ilike '" . loc_db_escape_string($searchWord) .
                "')";
    } else {
        $whrcls = " AND ((CASE WHEN a.customer_id <= 0 and a.person_id <= 0 THEN a.visitor_name_desc 
           WHEN a.person_id>0 THEN prs.get_prsn_surname(a.person_id) || ' (' || prs.get_prsn_loc_id(a.person_id) || ')' 
            ELSE scm.get_cstmr_splr_name(a.customer_id) END) ilike '" . loc_db_escape_string($searchWord) .
                "' OR a.attn_comments ilike '" . loc_db_escape_string($searchWord) .
                "' OR b.recs_hdr_name ilike '" . loc_db_escape_string($searchWord) .
                "')";
    }
    $strSql = "SELECT a.attnd_rec_id, a.recs_hdr_id, a.person_id, a.customer_id,
CASE WHEN a.customer_id <= 0 and a.person_id <= 0 THEN a.visitor_classfctn 
           WHEN a.person_id>0 THEN prs.get_prsn_loc_id(a.person_id) 
            ELSE a.visitor_classfctn || ' (' || a.customer_id || ')' END, 
      CASE WHEN a.customer_id <= 0 and a.person_id <= 0 THEN a.visitor_name_desc 
           WHEN a.person_id>0 THEN prs.get_prsn_surname(a.person_id) || ' (' || prs.get_prsn_loc_id(a.person_id) || ')' 
            ELSE scm.get_cstmr_splr_name(a.customer_id) END, b.recs_hdr_name,
      a.is_present, 
      CASE WHEN a.date_time_in != '' THEN to_char(to_timestamp(a.date_time_in,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') ELSE '' END, 
      CASE WHEN a.date_time_out != '' THEN to_char(to_timestamp(a.date_time_out,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') ELSE '' END,
      a.attn_comments
      FROM attn.attn_attendance_recs a, attn.attn_attendance_recs_hdr b " .
            "WHERE((a.recs_hdr_id=b.recs_hdr_id) and (b.org_id = " . $orgid . ") and (to_timestamp(b.event_date,'YYYY-MM-DD HH24:MI:SS') between to_timestamp('" . $dte1 .
            "','YYYY-MM-DD HH24:MI:SS') AND to_timestamp('" . $dte2 . "','YYYY-MM-DD HH24:MI:SS'))" . $whrcls . ") " . $ordrByCls . " LIMIT " . $limit_size .
            " OFFSET " . (abs($offset * $limit_size));
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_Total_AttnRgstr_SrchLns($searchWord, $searchIn, $orgid, $dte1, $dte2) {
    if ($dte1 != "") {
        $dte1 = cnvrtDMYTmToYMDTm($dte1);
    }
    if ($dte2 != "") {
        $dte2 = cnvrtDMYTmToYMDTm($dte2);
    }
    $strSql = "";
    $whrcls = "";
    /*
     *  Person Name
      Date/Time In
      Date/Time Out
      Is Present?
      Attendance Comments
     * 
     */
    if ($searchIn == "Register Name") {
        $whrcls = " AND (b.recs_hdr_name ilike '" . loc_db_escape_string($searchWord) .
                "')";
    } else if ($searchIn == "Person Name/ID") {
        $whrcls = " AND ((CASE WHEN a.customer_id <= 0 and a.person_id <= 0 THEN a.visitor_name_desc 
           WHEN a.person_id>0 THEN prs.get_prsn_surname(a.person_id) || ' (' || prs.get_prsn_loc_id(a.person_id) || ')' 
            ELSE scm.get_cstmr_splr_name(a.customer_id) END) ilike '" . loc_db_escape_string($searchWord) .
                "')";
    } else if ($searchIn == "Date/Time In") {
        $whrcls = " AND (to_char(to_timestamp(a.date_time_in,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') ilike '" . loc_db_escape_string($searchWord) .
                "')";
    } else if ($searchIn == "Date/Time Out") {
        $whrcls = " AND (to_char(to_timestamp(a.date_time_out,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') ilike '" . loc_db_escape_string($searchWord) .
                "')";
    } else if ($searchIn == "Is Present?") {
        $whrcls = " AND ((CASE WHEN a.is_present='1' THEN 'TRUE/YES/PRESENT' ELSE 'FALSE/NO/ABSENT' END) ilike '" . loc_db_escape_string($searchWord) .
                "')";
    } else if ($searchIn == "Attendance Comments") {
        $whrcls = " AND (a.attn_comments ilike '" . loc_db_escape_string($searchWord) .
                "')";
    } else {
        $whrcls = " AND ((CASE WHEN a.customer_id <= 0 and a.person_id <= 0 THEN a.visitor_name_desc 
           WHEN a.person_id>0 THEN prs.get_prsn_surname(a.person_id) || ' (' || prs.get_prsn_loc_id(a.person_id) || ')' 
            ELSE scm.get_cstmr_splr_name(a.customer_id) END) ilike '" . loc_db_escape_string($searchWord) .
                "' OR a.attn_comments ilike '" . loc_db_escape_string($searchWord) .
                "' OR b.recs_hdr_name ilike '" . loc_db_escape_string($searchWord) .
                "')";
    }
    $strSql = "SELECT count(1)
  FROM attn.attn_attendance_recs a, attn.attn_attendance_recs_hdr b " .
            "WHERE((a.recs_hdr_id=b.recs_hdr_id) and (b.org_id = " . $orgid . ") and (to_timestamp(b.event_date,'YYYY-MM-DD HH24:MI:SS') between to_timestamp('" . $dte1 .
            "','YYYY-MM-DD HH24:MI:SS') AND to_timestamp('" . $dte2 . "','YYYY-MM-DD HH24:MI:SS'))" . $whrcls . ")";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return 0;
}

function deleteActvtyRslt1($lnID, $pkeyNm) {
    $delSQL = "DELETE FROM attn.attn_attendance_events_rslts WHERE evnt_rslt_id = " .
            $lnID . "";
    $affctd1 = execUpdtInsSQL($delSQL, "Name = " . $pkeyNm);

    if ($affctd1 > 0) {
        $dsply = "";
        $dsply .= "<br/>Successfully Executed the ff-";
        $dsply .= "<br/>Deleted $affctd1 Activity/Event Result(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Deleted";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function get_One_ActvtyRslts1($searchWord, $searchIn, $offset, $limit_size, $evntID, $sbmtdRegisterID, $dte1, $dte2, $sortBy) {
    $strSql = "";
    $whrcls = "";
    $whrcls2 = "";
    if ($evntID > 0) {
        $whrcls2 .= " and (a.event_id = " . $evntID . ")";
    }
    if ($sbmtdRegisterID > 0) {
        $whrcls2 .= " and (a.lnkd_rgstr_id = " . $sbmtdRegisterID . ")";
    }
    if ($dte1 != "") {
        $dte1 = cnvrtDMYTmToYMDTm($dte1);
    }
    if ($dte2 != "") {
        $dte2 = cnvrtDMYTmToYMDTm($dte2);
    }
    $ordrByCls = "ORDER BY a.rstl_start_date DESC, a.evnt_rslt_id DESC ";
    if ($sortBy == "Date (DESC)") {
        $ordrByCls = "ORDER BY a.rstl_start_date DESC, a.evnt_rslt_id DESC ";
    } else if ($sortBy == "Date (ASC)") {
        $ordrByCls = "ORDER BY a.rstl_start_date ASC, a.evnt_rslt_id DESC ";
    }
    if ($searchIn == "Metric Name") {
        $whrcls = " AND (b.rslt_metric_name_desc ilike '" . loc_db_escape_string($searchWord) .
                "')";
    } else if ($searchIn == "Start Date") {
        $whrcls = " AND (to_char(to_timestamp(a.rstl_start_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') ilike '" . loc_db_escape_string($searchWord) .
                "')";
    } else if ($searchIn == "End Date") {
        $whrcls = " AND (to_char(to_timestamp(a.rslt_end_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') ilike '" . loc_db_escape_string($searchWord) .
                "')";
    } else if ($searchIn == "Event Result") {
        $whrcls = " AND (a.event_result ilike '" . loc_db_escape_string($searchWord) .
                "')";
    } else if ($searchIn == "Comment") {
        $whrcls = " AND (a.rslt_desc ilike '" . loc_db_escape_string($searchWord) .
                "')";
    } else {
        $whrcls = " AND (b.rslt_metric_name_desc ilike '" . loc_db_escape_string($searchWord) .
                "' or a.rslt_desc ilike '" . loc_db_escape_string($searchWord) .
                "' or a.event_result ilike '" . loc_db_escape_string($searchWord) .
                "' or to_char(to_timestamp(a.rstl_start_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') ilike '" . loc_db_escape_string($searchWord) .
                "' or to_char(to_timestamp(a.rslt_end_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') ilike '" . loc_db_escape_string($searchWord) .
                "')";
    }
    $strSql = "SELECT a.evnt_rslt_id, a.event_id, a.evnt_metric_id, coalesce(b.rslt_metric_name_desc, a.evnt_metric_nm), a.event_result, 
      CASE WHEN a.rstl_start_date != '' THEN to_char(to_timestamp(a.rstl_start_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') ELSE '' END, 
      CASE WHEN a.rslt_end_date != '' THEN to_char(to_timestamp(a.rslt_end_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') ELSE '' END,
      a.rslt_desc, a.auto_calc, a.lnkd_rgstr_id
  FROM attn.attn_attendance_events_rslts a LEFT OUTER JOIN attn.attn_attendance_events_mtrcs b ON (a.evnt_metric_id = b.rslt_metric_id) " .
            "WHERE(1=1 and (to_timestamp(a.rstl_start_date,'YYYY-MM-DD HH24:MI:SS') between to_timestamp('" . $dte1 .
            "','YYYY-MM-DD HH24:MI:SS') AND to_timestamp('" . $dte2 . "','YYYY-MM-DD HH24:MI:SS'))" . $whrcls2 . $whrcls . ") " . $ordrByCls . " LIMIT " . $limit_size .
            " OFFSET " . (abs($offset * $limit_size));
    //echo $strSql; 
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_Total_ActvtyRslts1($searchWord, $searchIn, $evntID, $sbmtdRegisterID, $dte1, $dte2) {
    $strSql = "";
    $whrcls = "";
    $whrcls2 = "";
    if ($evntID > 0) {
        $whrcls2 .= " and (a.event_id = " . $evntID . ")";
    }
    if ($sbmtdRegisterID > 0) {
        $whrcls2 .= " and (a.lnkd_rgstr_id = " . $sbmtdRegisterID . ")";
    }
    if ($dte1 != "") {
        $dte1 = cnvrtDMYTmToYMDTm($dte1);
    }
    if ($dte2 != "") {
        $dte2 = cnvrtDMYTmToYMDTm($dte2);
    }
    if ($searchIn == "Metric Name") {
        $whrcls = " AND (coalesce(b.rslt_metric_name_desc, a.evnt_metric_nm) ilike '" . loc_db_escape_string($searchWord) .
                "')";
    } else if ($searchIn == "Start Date") {
        $whrcls = " AND (to_char(to_timestamp(a.rstl_start_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') ilike '" . loc_db_escape_string($searchWord) .
                "')";
    } else if ($searchIn == "End Date") {
        $whrcls = " AND (to_char(to_timestamp(a.rslt_end_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') ilike '" . loc_db_escape_string($searchWord) .
                "')";
    } else if ($searchIn == "Event Result") {
        $whrcls = " AND (a.event_result ilike '" . loc_db_escape_string($searchWord) .
                "')";
    } else if ($searchIn == "Comment") {
        $whrcls = " AND (a.rslt_desc ilike '" . loc_db_escape_string($searchWord) .
                "')";
    } else {
        $whrcls = " AND (coalesce(b.rslt_metric_name_desc, a.evnt_metric_nm) ilike '" . loc_db_escape_string($searchWord) .
                "' or a.rslt_desc ilike '" . loc_db_escape_string($searchWord) .
                "' or a.event_result ilike '" . loc_db_escape_string($searchWord) .
                "' or to_char(to_timestamp(a.rstl_start_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') ilike '" . loc_db_escape_string($searchWord) .
                "' or to_char(to_timestamp(a.rslt_end_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') ilike '" . loc_db_escape_string($searchWord) .
                "')";
    }
    $strSql = "SELECT count(1)
  FROM attn.attn_attendance_events_rslts a LEFT OUTER JOIN attn.attn_attendance_events_mtrcs b ON (a.evnt_metric_id = b.rslt_metric_id)  " .
            "WHERE(1=1 and (to_timestamp(a.rstl_start_date,'YYYY-MM-DD HH24:MI:SS') between to_timestamp('" . $dte1 .
            "','YYYY-MM-DD HH24:MI:SS') AND to_timestamp('" . $dte2 . "','YYYY-MM-DD HH24:MI:SS'))" . $whrcls2 . $whrcls . ")";

    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return 0;
}

function createActvtyRslt1($rstlID, $evntID, $evntMtrcID, $rsltCmmnt, $rslt, $strtDte, $endDte, $autoCalc, $rgstrID, $evnt_metric_nm) {
    global $usrID;
    if ($strtDte != "") {
        $strtDte = cnvrtDMYTmToYMDTm($strtDte);
    }
    if ($endDte != "") {
        $endDte = cnvrtDMYTmToYMDTm($endDte);
    }
    $insSQL = "INSERT INTO attn.attn_attendance_events_rslts(
            evnt_rslt_id, event_id, evnt_metric_id, rslt_desc, event_result, 
            rstl_start_date, rslt_end_date, created_by, creation_date, last_update_by, 
            last_update_date, auto_calc, lnkd_rgstr_id, evnt_metric_nm) " .
            "VALUES (" . $rstlID . ", " . $evntID . ", " . $evntMtrcID .
            ", '" . loc_db_escape_string($rsltCmmnt) .
            "', '" . loc_db_escape_string($rslt) .
            "', '" . loc_db_escape_string($strtDte) .
            "', '" . loc_db_escape_string($endDte) .
            "', " . $usrID . ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), " . $usrID . ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), '" .
            cnvrtBoolToBitStr($autoCalc) . "', " . $rgstrID . ", '" . loc_db_escape_string(substr($evnt_metric_nm, 0, 499)) .
            "')";
    return execUpdtInsSQL($insSQL);
}

function updateActvtyRslt1($rstlID, $evntID, $evntMtrcID, $rsltCmmnt, $rslt, $strtDte, $endDte, $autoCalc, $rgstrID, $evnt_metric_nm) {
    global $usrID;
    if ($strtDte != "") {
        $strtDte = cnvrtDMYTmToYMDTm($strtDte);
    }
    if ($endDte != "") {
        $endDte = cnvrtDMYTmToYMDTm($endDte);
    }
    $updtSQL = "UPDATE attn.attn_attendance_events_rslts SET " .
            "rslt_desc='" . loc_db_escape_string($rsltCmmnt) .
            "', event_result='" . loc_db_escape_string($rslt) .
            "', evnt_metric_nm='" . loc_db_escape_string(substr($evnt_metric_nm, 0, 499)) .
            "', rstl_start_date='" . loc_db_escape_string($strtDte) .
            "', rslt_end_date='" . loc_db_escape_string($endDte) .
            "', evnt_metric_id=" . $evntMtrcID .
            ", last_update_by = " . $usrID . ", " .
            "last_update_date = to_char(now(),'YYYY-MM-DD HH24:MI:SS'), auto_calc='" . cnvrtBoolToBitStr($autoCalc) .
            "', lnkd_rgstr_id= " . $rgstrID .
            " WHERE (evnt_rslt_id =" . $rstlID . ")";
    return execUpdtInsSQL($updtSQL);
}

function createEvntMtrc($mtrcID, $mtrcName, $mtrcCmmnt, $rsltTyp, $isEnbld, $strSQL, $evntID) {
    global $usrID;
    $insSQL = "INSERT INTO attn.attn_attendance_events_mtrcs(
            rslt_metric_id, rslt_metric_name_desc, rslt_type, rslt_comment, 
            rslt_query, created_by, creation_date, last_update_by, last_update_date, 
            event_id, is_enabled) " .
            "VALUES (" . $mtrcID . ", '" . loc_db_escape_string($mtrcName) .
            "', '" . loc_db_escape_string($rsltTyp) .
            "', '" . loc_db_escape_string($mtrcCmmnt) .
            "', '" . loc_db_escape_string($strSQL) .
            "', " . $usrID . ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), " . $usrID .
            ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), " . $evntID . ", '" . cnvrtBoolToBitStr($isEnbld) . "')";
    return execUpdtInsSQL($insSQL);
}

function updateEvntMtrc($mtrcID, $mtrcName, $mtrcCmmnt, $rsltTyp, $isEnbld, $strSQL, $evntID) {
    global $usrID;
    $updtSQL = "UPDATE attn.attn_attendance_events_mtrcs SET " .
            "rslt_metric_name_desc='" . loc_db_escape_string($mtrcName) .
            "', rslt_type='" . loc_db_escape_string($rsltTyp) .
            "', rslt_comment='" . loc_db_escape_string($mtrcCmmnt) .
            "', rslt_query='" . loc_db_escape_string($strSQL) .
            "', event_id=" . $evntID .
            ", last_update_by = " . $usrID . ", " .
            "last_update_date = to_char(now(),'YYYY-MM-DD HH24:MI:SS'), is_enabled='" . cnvrtBoolToBitStr($isEnbld) .
            "' WHERE (rslt_metric_id =" . $mtrcID . ")";
    return execUpdtInsSQL($updtSQL);
}

function createEvntPrice($pricID, $ctgrName, $isEnbld, $itmID, $evntID) {
    global $usrID;
    $insSQL = "INSERT INTO attn.event_price_categories(
            price_ctgry_id, event_id, price_category, inv_itm_id, created_by, 
            creation_date, last_update_by, last_update_date, is_enabled) " .
            "VALUES (" . $pricID . ", " . $evntID . ", '" . loc_db_escape_string($ctgrName) .
            "', " . $itmID . ", " . $usrID . ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), " . $usrID .
            ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), '" . cnvrtBoolToBitStr($isEnbld) . "')";
    return execUpdtInsSQL($insSQL);
}

function updateEvntPrice($pricID, $ctgrName, $isEnbld, $itmID, $evntID) {
    global $usrID;
    $updtSQL = "UPDATE attn.event_price_categories SET " .
            "price_category='" . loc_db_escape_string($ctgrName) .
            "', inv_itm_id=" . $itmID .
            ", last_update_by = " . $usrID . ", " .
            "last_update_date = to_char(now(),'YYYY-MM-DD HH24:MI:SS'), is_enabled='" . cnvrtBoolToBitStr($isEnbld) .
            "' WHERE (price_ctgry_id =" . $pricID . ")";
    return execUpdtInsSQL($updtSQL);
}

function createEvntCstAcnt($costID, $ctgrName, $incrsDcrs1, $costAcntID, $incrsDcrs2, $blcgAcntID, $evntID) {
    global $usrID;
    $insSQL = "INSERT INTO attn.attn_evnt_cost_accnts(
            evnt_cost_acnt_id, event_id, cost_ctgry_nm, incrs_dcrs1, cost_account_id, 
            incrs_dcrs2, blncng_account_id, created_by, creation_date, last_update_by, 
            last_update_date) " .
            "VALUES (" . $costID . ", " . $evntID . ", '" . loc_db_escape_string($ctgrName) .
            "', '" . loc_db_escape_string($incrsDcrs1) .
            "', " . $costAcntID . ", '" . loc_db_escape_string($incrsDcrs2) .
            "', " . $blcgAcntID . ", " . $usrID . ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), " . $usrID .
            ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'))";
    return execUpdtInsSQL($insSQL);
}

function updateEvntCstAcnt($costID, $ctgrName, $incrsDcrs1, $costAcntID, $incrsDcrs2, $blcgAcntID, $evntID) {
    global $usrID;
    $updtSQL = "UPDATE attn.attn_evnt_cost_accnts SET " .
            "cost_ctgry_nm='" . loc_db_escape_string($ctgrName) .
            "', incrs_dcrs1='" . loc_db_escape_string($incrsDcrs1) .
            "',cost_account_id=" . $costAcntID .
            ", incrs_dcrs2='" . loc_db_escape_string($incrsDcrs2) .
            "',blncng_account_id=" . $blcgAcntID .
            ",last_update_by = " . $usrID . ", " .
            "last_update_date = to_char(now(),'YYYY-MM-DD HH24:MI:SS') WHERE (evnt_cost_acnt_id =" . $costID . ")";
    return execUpdtInsSQL($updtSQL);
}

function createEvent($orgid, $evntname, $evntdesc, $evntTyp, $isEnbld, $hostprsnid, $grpType, $grpID, $tmtblSessins, $hgstCntns, $slotprty,
        $evntClsfctn, $prfrdvnuID, $grpnm, $mtrcLOV, $pointsLov, $hostFirmID, $hostFirmSiteID) {
    global $usrID;
    $insSQL = "INSERT INTO attn.attn_attendance_events(
            event_name, event_desc, event_typ, host_prsn_id, allwd_grp_typ, 
            allwd_grp_id, ttl_tmtbl_sessn_mins, hghst_cntnuous_mins, slot_priority, 
            created_by, creation_date, last_update_by, last_update_date, 
            event_classification, org_id, preffrd_venue_id, is_enabled, 
            allwd_group_nm, attnd_metric_lov_nm, attnd_points_lov_nm, host_firm_id, host_firm_site_id) " .
            "VALUES ('" . loc_db_escape_string($evntname) .
            "', '" . loc_db_escape_string($evntdesc) .
            "', '" . loc_db_escape_string($evntTyp) .
            "', " . $hostprsnid . ", '" . loc_db_escape_string($grpType) .
            "', " . $grpID . ", " . $tmtblSessins . ", " . $hgstCntns . ", " . $slotprty .
            ", " . $usrID . ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), " . $usrID .
            ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), '" . loc_db_escape_string($evntClsfctn) .
            "', " . $orgid . ", " . $prfrdvnuID . ", '" . cnvrtBoolToBitStr($isEnbld) .
            "', '" . loc_db_escape_string($grpnm) .
            "', '" . loc_db_escape_string($mtrcLOV) .
            "', '" . loc_db_escape_string($pointsLov) .
            "', " . $hostFirmID . ", " . $hostFirmSiteID . ")";
    return execUpdtInsSQL($insSQL);
}

function updateEvent($evntid, $evntname, $evntdesc, $evntTyp, $isEnbld, $hostprsnid, $grpType, $grpID, $tmtblSessins, $hgstCntns, $slotprty,
        $evntClsfctn, $prfrdvnuID, $grpnm, $mtrcLOV, $pointsLov, $hostFirmID, $hostFirmSiteID) {
    global $usrID;
    $updtSQL = "UPDATE attn.attn_attendance_events SET " .
            "event_name='" . loc_db_escape_string($evntname) .
            "', event_desc='" . loc_db_escape_string($evntdesc) .
            "', event_typ='" . loc_db_escape_string($evntTyp) .
            "', host_prsn_id=" . $hostprsnid .
            ", allwd_grp_typ='" . loc_db_escape_string($grpType) .
            "', allwd_grp_id=" . $grpID .
            ", ttl_tmtbl_sessn_mins=" . $tmtblSessins .
            ", hghst_cntnuous_mins=" . $hgstCntns .
            ", slot_priority=" . $slotprty .
            ", last_update_by = " . $usrID . ", " .
            "last_update_date = to_char(now(),'YYYY-MM-DD HH24:MI:SS'), event_classification='" . loc_db_escape_string($evntClsfctn) .
            "', is_enabled='" . cnvrtBoolToBitStr($isEnbld) .
            "', preffrd_venue_id=" . $prfrdvnuID . ", allwd_group_nm = '" . loc_db_escape_string($grpnm) .
            "', attnd_metric_lov_nm= '" . loc_db_escape_string($mtrcLOV) .
            "', attnd_points_lov_nm = '" . loc_db_escape_string($pointsLov) .
            "', host_firm_id=" . $hostFirmID .
            ", host_firm_site_id=" . $hostFirmSiteID .
            " WHERE (event_id =" . $evntid . ")";
    return execUpdtInsSQL($updtSQL);
}

function updateEvent1($evntid, $evntname, $evntdesc, $evntTyp, $isEnbld, $grpType, $grpID, $tmtblSessins, $hgstCntns, $slotprty,
        $evntClsfctn, $grpnm, $mtrcLOV, $pointsLov) {
    global $usrID;
    $updtSQL = "UPDATE attn.attn_attendance_events SET " .
            "event_name='" . loc_db_escape_string($evntname) .
            "', event_desc='" . loc_db_escape_string($evntdesc) .
            "', event_typ='" . loc_db_escape_string($evntTyp) .
            "', allwd_grp_typ='" . loc_db_escape_string($grpType) .
            "', allwd_grp_id=" . $grpID .
            ", ttl_tmtbl_sessn_mins=" . $tmtblSessins .
            ", hghst_cntnuous_mins=" . $hgstCntns .
            ", slot_priority=" . $slotprty .
            ", last_update_by = " . $usrID . ", " .
            "last_update_date = to_char(now(),'YYYY-MM-DD HH24:MI:SS'), event_classification='" . loc_db_escape_string($evntClsfctn) .
            "', is_enabled='" . cnvrtBoolToBitStr($isEnbld) .
            "', attnd_metric_lov_nm= '" . loc_db_escape_string($mtrcLOV) .
            "', attnd_points_lov_nm = '" . loc_db_escape_string($pointsLov) .
            "' WHERE (event_id =" . $evntid . ")";
    return execUpdtInsSQL($updtSQL);
}

function deleteEvent($evntid, $evntname) {
    if (isEvntInUse($evntid) === true) {
        $dsply = "No Record Deleted<br/>Cannot delete an Event used in Time Tables!";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
    $delSQL = "DELETE FROM attn.event_price_categories WHERE event_id = " . $evntid;
    $affctd1 = execUpdtInsSQL($delSQL, "Name:" . $evntname);
    $delSQL = "DELETE FROM attn.attn_evnt_cost_accnts WHERE event_id = " . $evntid;
    $affctd5 = execUpdtInsSQL($delSQL, "Name:" . $evntname);
    $delSQL = "DELETE FROM attn.attn_attendance_events_mtrcs WHERE event_id = " . $evntid;
    $affctd4 = execUpdtInsSQL($delSQL, "Name:" . $evntname);
    $delSQL = "DELETE FROM attn.attn_attendance_events_rslts WHERE event_id = " . $evntid;
    $affctd3 = execUpdtInsSQL($delSQL, "Name:" . $evntname);
    $delSQL = "DELETE FROM attn.attn_attendance_events WHERE event_id = " . $evntid;
    $affctd2 = execUpdtInsSQL($delSQL, "Name:" . $evntname);

    if ($affctd2 > 0) {
        $dsply = "";
        $dsply .= "<br/>Successfully Executed the ff-";
        $dsply .= "<br/>Deleted $affctd1 Event Prices(s)!";
        $dsply .= "<br/>Deleted $affctd5 Event Account(s)!";
        $dsply .= "<br/>Deleted $affctd4 Event Metric(s)!";
        $dsply .= "<br/>Deleted $affctd3 Event Result(s)!";
        $dsply .= "<br/>Deleted $affctd2 Event(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Deleted";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function isEvntInUse($evntid) {
    $strSql = "SELECT a.time_table_det_id " .
            "FROM attn.attn_time_table_details a " .
            "WHERE(a.event_id = " . $evntid . ")";
    $result = executeSQLNoParams($strSql);
    if (loc_db_num_rows($result) > 0) {
        return true;
    }
}

function getEventID($evntname, $orgid) {
    $sqlStr = "select event_id from attn.attn_attendance_events where lower(event_name) = lower('" . loc_db_escape_string($evntname) . "') and org_id = " . $orgid;
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -1;
}

function getEventCostID($rgstrID, $src_doc_id, $src_doc_type) {
    $sqlStr = "select attnd_cost_id from attn.attn_attendance_costs where lower(src_doc_type) = lower('" .
            loc_db_escape_string($src_doc_type) . "') and recs_hdr_id = " . $rgstrID . " and src_doc_id = " . $src_doc_id;
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -1;
}

function getMtrcSQL($mtrcID) {
    $sqlStr = "select rslt_query from attn.attn_attendance_events_mtrcs where rslt_metric_id = " . $mtrcID;
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return "";
}

function computMtrcSQL($strSQL, $evntID, $dte1, $dte2) {
    if ($strSQL == "") {
        $strSQL = "select 'N/A'";
    }
    if ($strSQL != "") {
        $strSQL = str_replace("{:endDte}", dte2, str_replace("{:strtDte}", $dte1, str_replace("{:evntID}", $evntID, $strSQL)));
    }

    $result = executeSQLNoParams($strSQL);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return "";
}

function get_One_EvntDet($evntid) {
    $strSql = "SELECT a.event_id, a.event_name, a.event_desc, a.event_typ,
CASE WHEN a.event_typ='R' THEN ' R:RECURRING' ELSE 'NR:NON-RECURRING' END, 
      a.event_classification, a.allwd_grp_typ, a.allwd_grp_id,
      org.get_criteria_name(a.allwd_grp_id::bigint, a.allwd_grp_typ), a.is_enabled, a.host_prsn_id, 
      REPLACE(prs.get_prsn_surname(a.host_prsn_id) || ' (' 
      || prs.get_prsn_loc_id(a.host_prsn_id) || ')', ' ()', '') fullnm,
      a.ttl_tmtbl_sessn_mins, a.hghst_cntnuous_mins, a.slot_priority, a.preffrd_venue_id,attn.get_venue_name(a.preffrd_venue_id) vnu_nm,
      a.attnd_metric_lov_nm, a.attnd_points_lov_nm, 
      a.host_firm_id, a.host_firm_site_id, scm.get_cstmr_splr_site_name(a.host_firm_site_id), scm.get_cstmr_splr_name(a.host_firm_id) " .
            "FROM attn.attn_attendance_events a " .
            "WHERE(a.event_id = " . $evntid . ")";

    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_One_EvntMtrcs($evntid) {
    $strSql = "SELECT rslt_metric_id, rslt_metric_name_desc, rslt_type, rslt_comment, rslt_query, is_enabled
       FROM attn.attn_attendance_events_mtrcs a WHERE(a.event_id = " . $evntid . ") ORDER BY 1";
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_One_EvntPrices($evntid) {
    $strSql = "SELECT price_ctgry_id, price_category, inv_itm_id, inv.get_invitm_name(a.inv_itm_id) itm_nm, is_enabled,
        inv.getuompricelstx(a.inv_itm_id,1) prclstx, inv.getuomsllngprice(a.inv_itm_id,1)sllprice
       FROM attn.event_price_categories a WHERE(a.event_id = " . $evntid . ") ORDER BY 1";
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_One_EvntTtlPrices($evntid) {
    $strSql = "SELECT count(1) 
       FROM attn.event_price_categories a WHERE(a.event_id = " . $evntid . ") ORDER BY 1";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return 0;
}

function get_One_EvntCostAcnts($evntid) {
    $strSql = "SELECT evnt_cost_acnt_id, cost_ctgry_nm, incrs_dcrs1, 
         cost_account_id, accb.get_accnt_num(a.cost_account_id) || '.' || accb.get_accnt_name(a.cost_account_id), 
         incrs_dcrs2, blncng_account_id, accb.get_accnt_num(a.blncng_account_id) || '.' || accb.get_accnt_name(a.blncng_account_id) 
        FROM attn.attn_evnt_cost_accnts a WHERE(a.event_id = " . $evntid . ") ORDER BY 1";
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_One_EvntEnbldMtrcs($evntid) {
    $strSql = "SELECT rslt_metric_id, rslt_metric_name_desc, rslt_type, rslt_comment, 
       rslt_query, event_id, is_enabled
       FROM attn.attn_attendance_events_mtrcs a WHERE(a.event_id = " . $evntid . " and is_enabled='1') ORDER BY 1";
    $result = executeSQLNoParams($strSql);
    return $result;
}

function getNewMtrcLnID() {
    $sqlStr = "select nextval('attn.attn_attendance_events_mtrcs_rslt_metric_id_seq')";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -1;
}

function getNewPriceLnID() {
    $strSql = "select nextval('attn.event_price_categories_price_ctgry_id_seq')";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -1;
}

function getNewCstAcntLnID() {
    $strSql = "select nextval('attn.attn_evnt_cost_accnts_evnt_cost_acnt_id_seq')";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -1;
}

function getNewAttnCstLnID() {
    $strSql = "select nextval('attn.attn_attendance_costs_attnd_cost_id_seq')";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -1;
}

function getNewRsltLnID() {
    $strSql = "select nextval('attn.attn_attendance_events_rslts_evnt_rslt_id_seq')";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -1;
}

function deleteEvntMtrc($lnID, $pKeyNm) {
    $delSQL = "DELETE FROM attn.attn_attendance_events_mtrcs WHERE rslt_metric_id = " .
            $lnID . "";
    $affctd2 = execUpdtInsSQL($delSQL, "Name:" . $pKeyNm);
    if ($affctd2 > 0) {
        $dsply = "";
        $dsply .= "<br/>Successfully Executed the ff-";
        $dsply .= "<br/>Deleted $affctd2 Event Metric(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Deleted";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function deletePriceMtrc($lnID, $pKeyNm) {
    $delSQL = "DELETE FROM attn.event_price_categories WHERE price_ctgry_id = " .
            $lnID . "";
    $affctd2 = execUpdtInsSQL($delSQL, "Name:" . $pKeyNm);
    if ($affctd2 > 0) {
        $dsply = "";
        $dsply .= "<br/>Successfully Executed the ff-";
        $dsply .= "<br/>Deleted $affctd2 Price(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Deleted";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function deleteCostAcnt($lnID, $pKeyNm) {
    $delSQL = "DELETE FROM attn.attn_evnt_cost_accnts WHERE evnt_cost_acnt_id = " .
            $lnID . "";
    $affctd1 = execUpdtInsSQL($delSQL, "Name=" . $pKeyNm);
    if ($affctd1 > 0) {
        $dsply = "";
        $dsply .= "<br/>Successfully Executed the ff-";
        $dsply .= "<br/>Deleted $affctd1 Default Account(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Deleted";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function deleteAtndncMtrc($lnID, $pKeyNm) {
    $delSQL = "DELETE FROM attn.attn_attendance_recs_cntr WHERE cntr_id = " .
            $lnID . "";
    $affctd1 = execUpdtInsSQL($delSQL, "Name=" . $pKeyNm);
    if ($affctd1 > 0) {
        $dsply = "";
        $dsply .= "<br/>Successfully Executed the ff-";
        $dsply .= "<br/>Deleted $affctd1 Metric(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Deleted";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function getAtndncMtrcCnt($mrtcNm, $rgstrID) {
    if ($mrtcNm == "Male Attendance") {
        $mrtcNm = "Male%";
    } else if ($mrtcNm == "Female Attendance") {
        $mrtcNm = "Female%";
    } else {
        $mrtcNm = "%";
    }
    $selSQL = "select count(a.person_id) 
from attn.attn_attendance_recs a, prs.prsn_names_nos b
WHERE is_present='1' and a.person_id=b.person_id
and a.recs_hdr_id=" . $rgstrID . " and b.gender ilike '" . $mrtcNm . "'";
    $result = executeSQLNoParams($selSQL);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return 0;
}

function get_Basic_Events($searchWord, $searchIn, $offset, $limit_size, $orgID) {
    $strSql = "";
    $whrcls = "";
    if ($searchIn == "Event Name") {
        $whrcls = " AND (a.event_name ilike '" . loc_db_escape_string($searchWord) .
                "')";
    } else {
        $whrcls = " AND (a.event_desc ilike '" . loc_db_escape_string($searchWord) .
                "')";
    }
    $strSql = "SELECT a.event_id, a.event_name, a.event_desc, 
CASE WHEN a.event_typ='R' THEN ' R:RECURRING' ELSE 'NR:NON-RECURRING' END, 
      a.event_classification, a.allwd_grp_typ, a.allwd_grp_id,
      org.get_criteria_name(a.allwd_grp_id::bigint, a.allwd_grp_typ), a.is_enabled, a.host_prsn_id, 
      REPLACE(prs.get_prsn_surname(a.host_prsn_id) || ' (' 
      || prs.get_prsn_loc_id(a.host_prsn_id) || ')', ' ()', '') fullnm,
      a.ttl_tmtbl_sessn_mins, a.hghst_cntnuous_mins, a.slot_priority, a.preffrd_venue_id, a.attnd_metric_lov_nm, a.attnd_points_lov_nm, 
      a.host_firm_id, a.host_firm_site_id, scm.get_cstmr_splr_name(a.host_firm_id) " .
            "FROM attn.attn_attendance_events a " .
            "WHERE ((a.org_id = " . $orgID . ")" . $whrcls . ") ORDER BY a.event_id DESC LIMIT " . $limit_size .
            " OFFSET " . (abs($offset * $limit_size));

    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_Total_Events($searchWord, $searchIn, $orgID) {
    $strSql = "";
    $whrcls = "";
    if ($searchIn == "Event Name") {
        $whrcls = " AND (a.event_name ilike '" . loc_db_escape_string($searchWord) .
                "')";
    } else {
        $whrcls = " AND (a.event_desc ilike '" . loc_db_escape_string($searchWord) .
                "')";
    }
    $strSql = "SELECT count(1) " .
            "FROM attn.attn_attendance_events a " .
            "WHERE ((a.org_id = " . $orgID . ")" . $whrcls . ")";

    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return 0;
}

function createTimeTable($orgid, $tmetblname, $tmetbldesc, $eventClssf, $isEnbld, $smllstslotdrtn, $mjrDivTyp, $mjrDivStrtV, $mjrDivEndV,
        $mnrDivTyp, $mnrDivStrtV, $mnrDivEndV) {
    global $usrID;
    $insSQL = "INSERT INTO attn.attn_time_table_hdrs(
            time_table_name, time_table_desc, smllst_slot_duratn_mins, 
            maj_tme_div_typ, maj_tme_div_start_val, maj_tme_div_end_val, 
            min_tme_div_typ, min_tme_div_start_val, min_tme_div_end_val, 
            created_by, creation_date, last_update_by, last_update_date, 
            events_classifction_usd, org_id, is_enabled) " .
            "VALUES ('" . loc_db_escape_string($tmetblname) .
            "', '" . loc_db_escape_string($tmetbldesc) .
            "', " . loc_db_escape_string($smllstslotdrtn) .
            ", '" . loc_db_escape_string($mjrDivTyp) .
            "', '" . loc_db_escape_string($mjrDivStrtV) .
            "', '" . loc_db_escape_string($mjrDivEndV) .
            "', '" . loc_db_escape_string($mnrDivTyp) .
            "', '" . loc_db_escape_string($mnrDivStrtV) .
            "', '" . loc_db_escape_string($mnrDivEndV) .
            "', " . $usrID . ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), " . $usrID .
            ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), '" . loc_db_escape_string($eventClssf) .
            "', " . $orgid . ", '" . cnvrtBoolToBitStr($isEnbld) . "')";
    return execUpdtInsSQL($insSQL);
}

function updateTimeTable($tmtblid, $tmetblname, $tmetbldesc, $eventClssf, $isEnbld, $smllstslotdrtn, $mjrDivTyp, $mjrDivStrtV, $mjrDivEndV,
        $mnrDivTyp, $mnrDivStrtV, $mnrDivEndV) {
    global $usrID;
    $updtSQL = "UPDATE attn.attn_time_table_hdrs SET " .
            "time_table_name='" . loc_db_escape_string($tmetblname) .
            "', time_table_desc='" . loc_db_escape_string($tmetbldesc) .
            "', maj_tme_div_typ='" . loc_db_escape_string($mjrDivTyp) .
            "', maj_tme_div_start_val='" . loc_db_escape_string($mjrDivStrtV) .
            "', maj_tme_div_end_val='" . loc_db_escape_string($mjrDivEndV) .
            "', min_tme_div_typ='" . loc_db_escape_string($mnrDivTyp) .
            "', min_tme_div_start_val='" . loc_db_escape_string($mnrDivStrtV) .
            "', min_tme_div_end_val='" . loc_db_escape_string($mnrDivEndV) .
            "', smllst_slot_duratn_mins=" . loc_db_escape_string($smllstslotdrtn) . " " .
            ", last_update_by=" . $usrID .
            ", last_update_date=to_char(now(),'YYYY-MM-DD HH24:MI:SS'), " .
            "events_classifction_usd='" . loc_db_escape_string($eventClssf) . "', is_enabled='" .
            cnvrtBoolToBitStr($isEnbld) . "' WHERE (time_table_id =" . $tmtblid . ")";
    return execUpdtInsSQL($updtSQL);
}

function getNewTmTblDtID() {
    //$strSql = "select nextval('accb.accb_trnsctn_batches_batch_id_seq'::regclass);";
    $strSql = "select nextval('attn.time_table_details_time_table_det_id_seq')";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -1;
}

function getTmTblDtID($tmTblID, $evntID, $mjrTm, $mnrTm, $vnuID) {
    $strSql = "select time_table_det_id from attn.attn_time_table_details 
      where time_table_id=" . $tmTblID . " and event_id=" . $evntID .
            " and time_maj_div='" . loc_db_escape_string($mjrTm) .
            "' and time_min_div='" . loc_db_escape_string($mnrTm) . "' and assgnd_venue_id=" . $vnuID;
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -1;
}

function createTimeTableDetLn($tmtblDetID, $tmtblid, $evntID, $tmeMajDiv, $tmeMinDiv, $isEnbld, $hostID, $vnuID, $tmeMajDivEnd,
        $tmeMinDivEnd) {
    global $usrID;
    $insSQL = "INSERT INTO attn.attn_time_table_details(
            time_table_det_id, time_table_id, event_id, time_maj_div, time_min_div, 
            assgnd_host_id, created_by, creation_date, last_update_by, last_update_date, 
            assgnd_venue_id, is_enabled, time_maj_div_end, time_min_div_end) " .
            "VALUES (" . $tmtblDetID . ", " . $tmtblid . ", " . $evntID .
            ", '" . loc_db_escape_string($tmeMajDiv) .
            "', '" . loc_db_escape_string($tmeMinDiv) .
            "', " . $hostID . ", " . $usrID .
            ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), " . $usrID .
            ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), " . $vnuID . ", '" .
            cnvrtBoolToBitStr($isEnbld) . "', '" . loc_db_escape_string($tmeMajDivEnd) .
            "', '" . loc_db_escape_string($tmeMinDivEnd) .
            "')";
    return execUpdtInsSQL($insSQL);
}

function updtTimeTableDetLn($tmtblDetLnid, $tmtblid, $evntID, $tmeMajDiv, $tmeMinDiv, $isEnbld, $hostID, $vnuID, $tmeMajDivEnd,
        $tmeMinDivEnd) {
    global $usrID;
    $insSQL = "UPDATE attn.attn_time_table_details SET 
            time_table_id=" . $tmtblid . ", event_id=" . $evntID .
            ", time_maj_div='" . loc_db_escape_string($tmeMajDiv) .
            "', time_min_div='" . loc_db_escape_string($tmeMinDiv) .
            "', assgnd_host_id=" . $hostID . ", last_update_by=" . $usrID .
            ", last_update_date=to_char(now(),'YYYY-MM-DD HH24:MI:SS'), assgnd_venue_id=" . $vnuID .
            ", is_enabled='" . cnvrtBoolToBitStr($isEnbld) . "', time_maj_div_end='" . loc_db_escape_string($tmeMajDivEnd) .
            "', time_min_div_end='" . loc_db_escape_string($tmeMinDivEnd) .
            "' WHERE time_table_det_id=" . $tmtblDetLnid . " ";
    return execUpdtInsSQL($insSQL);
}

function deleteTimeTable($tmetblid, $tmtblNm) {
    if (isTmeTblInUse($tmetblid) === true) {
        $dsply = "No Record Deleted<br/>Cannot delete a Time Table used in Attendance Registers!";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
    $delSQL = "DELETE FROM attn.attn_time_table_details WHERE time_table_id = " . $tmetblid;
    $affctd1 = execUpdtInsSQL($delSQL, "Name:" . $tmtblNm);

    $delSQL = "DELETE FROM attn.attn_time_table_hdrs WHERE time_table_id = " . $tmetblid;
    $affctd2 = execUpdtInsSQL($delSQL, "Time Table Name = " . $tmtblNm);
    if ($affctd1 > 0) {
        $dsply = "";
        $dsply .= "<br/>Successfully Executed the ff-";
        $dsply .= "<br/>Deleted $affctd1 Time Table(s)!";
        $dsply .= "<br/>Deleted $affctd2 Detail(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Deleted";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function isTmeTblInUse($tmetblid) {
    /* $strSql = "SELECT a.time_table_det_id " .
      "FROM attn.attn_time_table_details a " .
      "WHERE(a.time_table_id = " . $tmetblid . ")";
      $result = executeSQLNoParams($strSql);
      if (loc_db_num_rows($result) > 0) {
      return true;
      }

      $strSql = "SELECT a.time_table_det_id " .
      "FROM attn.temp_time_table_details a " .
      "WHERE(a.time_table_id = " . $tmetblid . ")";
      $result = executeSQLNoParams($strSql);
      if (loc_db_num_rows($result) > 0) {
      return true;
      } */
    $strSql = "SELECT a.recs_hdr_id " .
            "FROM attn.attn_attendance_recs_hdr a " .
            "WHERE(a.time_table_id = " . $tmetblid . ")";
    $result = executeSQLNoParams($strSql);
    if (loc_db_num_rows($result) > 0) {
        return true;
    }
    return false;
}

function deleteTimeTableDLn($tmetblLnid, $EventNm) {
    if (isTmeTblLnInUse($tmetblLnid) === true) {
        $dsply = "No Record Deleted<br/>Cannot delete a Time Table used in Attendance Registers!";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
    $delSQL = "DELETE FROM attn.attn_time_table_details WHERE time_table_det_id = " . $tmetblLnid;
    $affctd2 = execUpdtInsSQL($delSQL, "Time Table Event Name = " . $EventNm);
    if ($affctd2 > 0) {
        $dsply = "";
        $dsply .= "<br/>Successfully Executed the ff-";
        $dsply .= "<br/>Deleted $affctd2 Time Table Detail(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Deleted";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function isTmeTblLnInUse($tmetblLnid) {
    $strSql = "SELECT a.recs_hdr_id " .
            "FROM attn.attn_attendance_recs_hdr a " .
            "WHERE(a.time_table_det_id = " . $tmetblLnid . ")";
    $result = executeSQLNoParams($strSql);
    if (loc_db_num_rows($result) > 0) {
        return true;
    }
    return false;
}

function getTmeTblID($tmeTblname, $orgid) {
    $strSql = "select time_table_id from attn.attn_time_table_hdrs where lower(time_table_name) = lower('" .
            loc_db_escape_string($tmeTblname) . "') and org_id = " . $orgid;
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -1;
}

function get_One_TmeTblHdrNEvnts($tmetblid, $lmit) {
    $extrWhr = "";
    if ($lmit >= 0) {
        $extrWhr = " LIMIT " . $lmit . " OFFSET 0";
    } else if ($lmit < 0) {
        $extrWhr = "";
    }

    $strSql = "SELECT b.event_name, 
        b.event_desc, b.event_typ, b.allwd_grp_typ,
       CASE WHEN b.allwd_grp_typ='Divisions/Groups' THEN 
       org.get_div_name(b.allwd_grp_id)
        WHEN b.allwd_grp_typ='Grade' THEN 
               org.get_div_name(b.allwd_grp_id)
        WHEN b.allwd_grp_typ='Job' THEN 
               org.get_div_name(b.allwd_grp_id)
        WHEN b.allwd_grp_typ='Position' THEN 
               org.get_div_name(b.allwd_grp_id)
        WHEN b.allwd_grp_typ='Site/Location' THEN 
               org.get_div_name(b.allwd_grp_id)
        WHEN b.allwd_grp_typ='Person Type' THEN 
               org.get_div_name(b.allwd_grp_id)
        ELSE 
            ''
        END grp_nm
      , b.ttl_tmtbl_sessn_mins, b.hghst_cntnuous_mins, b.slot_priority, 
       b.event_classification, b.allwd_group_nm, 
       b.attnd_metric_lov_nm, b.attnd_points_lov_nm, 
       a.event_id, a.time_maj_div, a.time_min_div, 
       prs.get_prsn_loc_id(a.assgnd_host_id), 
       (SELECT venue_name FROM attn.attn_event_venues d WHERE d.venue_id = a.assgnd_venue_id), 
       CASE WHEN a.is_enabled='1' THEN 'YES' ELSE 'NO' END, a.time_maj_div_end, a.time_min_div_end
       FROM attn.attn_time_table_details a, attn.attn_attendance_events b 
       WHERE(a.event_id = b.event_id and a.time_table_id = " . $tmetblid . ")" . $extrWhr;
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_GroupID($grpTyp, $grpNm) {
    $strSql = "SELECT org.get_criteria_id('" . loc_db_escape_string($grpNm) . "','" . loc_db_escape_string($grpTyp) . "') grp_id";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -1;
}

function get_PriceCtgryID($ctgryNm, $eventID) {
    $strSql = "SELECT price_ctgry_id from attn.event_price_categories where event_id=" . $eventID .
            " and price_category='" . loc_db_escape_string($ctgryNm) . "'";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -1;
}

function get_EventMtrcID($mtrcNm, $eventID) {
    $strSql = "SELECT rslt_metric_id from attn.attn_attendance_events_mtrcs where event_id=" . $eventID .
            " and rslt_metric_name_desc='" . loc_db_escape_string($mtrcNm) . "'";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -1;
}

function get_CostAcntCtgryID($ctgryNm, $eventID) {
    $strSql = "SELECT evnt_cost_acnt_id from attn.attn_evnt_cost_accnts where event_id=" . $eventID .
            " and cost_ctgry_nm='" . loc_db_escape_string($ctgryNm) . "'";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -1;
}

function get_CostAcntInfo($ctgryNm, $eventID) {
    $rslts = array("INCREASE", "-1", "", "INCREASE", "-1", "");
    $strSql = "SELECT a.incrs_dcrs1, a.cost_account_id, accb.get_accnt_num(a.cost_account_id) || '.' || accb.get_accnt_name(a.cost_account_id), 
                                a.incrs_dcrs2, a.blncng_account_id,  
                                accb.get_accnt_num(a.blncng_account_id) || '.' || accb.get_accnt_name(a.blncng_account_id) 
                            from attn.attn_evnt_cost_accnts a where a.event_id=" . $eventID .
            " and a.cost_ctgry_nm='" . loc_db_escape_string($ctgryNm) . "'";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        $rslts[0] = strtoupper($row[0]);
        $rslts[1] = $row[1];
        $rslts[2] = $row[2];
        $rslts[3] = strtoupper($row[3]);
        $rslts[4] = $row[4];
        $rslts[5] = $row[5];
        return $rslts;
    }
    //Global.taxFrm.rec_SQL = strSql;
    return $rslts;
}

function get_One_TmeTblHdrDet($tmetblid) {
    $strSql = "SELECT a.time_table_id, a.time_table_name, 
      a.time_table_desc, a.smllst_slot_duratn_mins, 
       a.maj_tme_div_typ, a.maj_tme_div_start_val, a.maj_tme_div_end_val, 
       a.min_tme_div_typ, a.min_tme_div_start_val, a.min_tme_div_end_val, 
       a.events_classifction_usd, a.is_enabled
  FROM attn.attn_time_table_hdrs a " .
            "WHERE(a.time_table_id = " . $tmetblid . ")";
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_Basic_TmeTbl($searchWord, $searchIn, $offset, $limit_size, $orgID) {
    $strSql = "";
    $whrcls = "";
    if ($searchIn == "Time Table Name") {
        $whrcls = " AND (a.time_table_name ilike '" . loc_db_escape_string($searchWord) .
                "')";
    } else if ($searchIn == "Time Table Description") {
        $whrcls = " AND (a.time_table_desc ilike '" . loc_db_escape_string($searchWord) .
                "')";
    }
    $strSql = "SELECT a.time_table_id, a.time_table_name " .
            "FROM attn.attn_time_table_hdrs a " .
            "WHERE ((a.org_id = " . $orgID . ")" . $whrcls .
            ") ORDER BY a.time_table_name LIMIT " . $limit_size .
            " OFFSET " . (abs($offset * $limit_size));
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_Total_TmeTbl($searchWord, $searchIn, $orgID) {
    $strSql = "";
    $whrcls = "";
    if ($searchIn == "Time Table Name") {
        $whrcls = " AND (a.time_table_name ilike '" . loc_db_escape_string($searchWord) .
                "')";
    } else if ($searchIn == "Time Table Description") {
        $whrcls = " AND (a.time_table_desc ilike '" . loc_db_escape_string($searchWord) .
                "')";
    }
    $strSql = "SELECT count(1) " .
            "FROM attn.attn_time_table_hdrs a " .
            "WHERE ((a.org_id = " . $orgID . ")" . $whrcls . ")";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return 0;
}

function get_One_TmeTbl_DetLns($searchWord, $searchIn, $offset, $limit_size, $tmtblID) {
    $strSql = "";
    $whrcls = "";
    /*
     *  Event Name
      Host Person Name
      Venue Name
      Major Time Division
      Minor Time Division
     * 
     */
    if ($searchIn == "Event Name") {
        $whrcls = " AND ((SELECT event_name FROM attn.attn_attendance_events d WHERE d.event_id = a.event_id) ilike '" . loc_db_escape_string($searchWord) .
                "')";
    } else if ($searchIn == "Host Person Name") {
        $whrcls = " AND (prs.get_prsn_name(a.assgnd_host_id) ilike '" . loc_db_escape_string($searchWord) .
                "')";
    } else if ($searchIn == "Venue Name") {
        $whrcls = " AND ((SELECT venue_name FROM attn.attn_event_venues d WHERE d.venue_id = a.assgnd_venue_id) ilike '" . loc_db_escape_string($searchWord) .
                "')";
    } else if ($searchIn == "Major Time Division") {
        $whrcls = " AND (a.time_maj_div ilike '" . loc_db_escape_string($searchWord) .
                "')";
    } else if ($searchIn == "Minor Time Division") {
        $whrcls = " AND (a.time_min_div ilike '" . loc_db_escape_string($searchWord) .
                "')";
    }
    $strSql = "SELECT a.time_table_det_id, a.event_id, " .
            "(SELECT event_name FROM attn.attn_attendance_events d WHERE d.event_id = a.event_id) evntnm, a.time_maj_div, " .
            "a.time_min_div, a.assgnd_host_id, prs.get_prsn_name(a.assgnd_host_id) hstnm, a.assgnd_venue_id, " .
            "(SELECT venue_name FROM attn.attn_event_venues d WHERE d.venue_id = a.assgnd_venue_id) vnunm, is_enabled, a.time_maj_div_end, " .
            "a.time_min_div_end " .
            "FROM attn.attn_time_table_details a " .
            "WHERE((a.time_table_id = " . $tmtblID . ")" . $whrcls . ") ORDER BY a.time_table_det_id DESC LIMIT " . $limit_size .
            " OFFSET " . (abs($offset * $limit_size));
    $result = executeSQLNoParams($strSql);
    return $result;
}

function getMonthNum($mnth) {
    $reslts = "";
    switch ($mnth) {
        case "JAN":
            $reslts = "01";
            break;
        case "FEB":
            $reslts = "02";
            break;
        case "MAR":
            $reslts = "03";
            break;
        case "APR":
            $reslts = "04";
            break;
        case "MAY":
            $reslts = "05";
            break;
        case "JUN":
            $reslts = "06";
            break;
        case "JUL":
            $reslts = "07";
            break;
        case "AUG":
            $reslts = "08";
            break;
        case "SEP":
            $reslts = "09";
            break;
        case "OCT":
            $reslts = "10";
            break;
        case "NOV":
            $reslts = "11";
            break;
        case "DEC":
            $reslts = "12";
            break;
        default:
            $reslts = $mnth;
            break;
    }
    return $reslts . "-" . $mnth;
}

function get_Total_TmeTbl_DetLns($searchWord, $searchIn, $tmtblID) {
    $strSql = "";
    $whrcls = "";
    /*
     *  Event Name
      Host Person Name
      Venue Name
      Major Time Division
      Minor Time Division
     * 
     */
    if ($searchIn == "Event Name") {
        $whrcls = " AND ((SELECT event_name FROM attn.attn_attendance_events d WHERE d.event_id = a.event_id) ilike '" . loc_db_escape_string($searchWord) .
                "')";
    } else if ($searchIn == "Host Person Name") {
        $whrcls = " AND (prs.get_prsn_name(a.assgnd_host_id) ilike '" . loc_db_escape_string($searchWord) .
                "')";
    } else if ($searchIn == "Venue Name") {
        $whrcls = " AND ((SELECT venue_name FROM attn.attn_event_venues d WHERE d.venue_id = a.assgnd_venue_id) ilike '" . loc_db_escape_string($searchWord) .
                "')";
    } else if ($searchIn == "Major Time Division") {
        $whrcls = " AND (a.time_maj_div ilike '" . loc_db_escape_string($searchWord) .
                "')";
    } else if ($searchIn == "Minor Time Division") {
        $whrcls = " AND (a.time_min_div ilike '" . loc_db_escape_string($searchWord) .
                "')";
    }
    $strSql = "SELECT count(1) " .
            "FROM attn.attn_time_table_details a " .
            "WHERE((a.time_table_id = " . $tmtblID . ")" . $whrcls . ")";

    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return 0;
}

function createAttnMtrcCnt($cntrID, $rgstrid, $mtrcname, $cmmntdesc, $rsltVal, $pssblValID) {
    global $usrID;
    $insSQL = "INSERT INTO attn.attn_attendance_recs_cntr(
            cntr_id, recs_hdr_id, metric_name_desc, cntr_result, 
            rslt_comment, created_by, creation_date, last_update_by, last_update_date, 
            lnkd_pssbl_val_id) " .
            "VALUES (" . $cntrID .
            "," . $rgstrid .
            ", '" . loc_db_escape_string($mtrcname) .
            "', " . $rsltVal .
            ", '" . loc_db_escape_string($cmmntdesc) .
            "', " . $usrID . ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), " . $usrID .
            ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), " . $pssblValID . ")";
    return execUpdtInsSQL($insSQL);
}

function updateAttnMtrcCnt($cntrid, $mtrcname, $cmmntdesc, $rsltVal, $pssblValID) {
    global $usrID;
    $updtSQL = "UPDATE attn.attn_attendance_recs_cntr SET " .
            "metric_name_desc='" . loc_db_escape_string($mtrcname) .
            "', rslt_comment='" . loc_db_escape_string($cmmntdesc) .
            "', cntr_result=" . loc_db_escape_string($rsltVal) .
            ", lnkd_pssbl_val_id=" . $pssblValID .
            ", last_update_by=" . $usrID . ", " .
            "last_update_date=to_char(now(),'YYYY-MM-DD HH24:MI:SS') WHERE (cntr_id =" . $cntrid . ")";
    return execUpdtInsSQL($updtSQL);
}

function createAttnRgstr($orgid, $rgstrname, $rgstrdesc, $tmtblID, $tmtblDetID, $evntDate, $endDte) {
    global $usrID;
    if ($evntDate != "") {
        $evntDate = cnvrtDMYTmToYMDTm($evntDate);
    }
    if ($endDte != "") {
        $endDte = cnvrtDMYTmToYMDTm($endDte);
    }

    $insSQL = "INSERT INTO attn.attn_attendance_recs_hdr(
            recs_hdr_name, recs_hdr_desc, time_table_id, time_table_det_id, 
            created_by, creation_date, last_update_by, last_update_date, 
            org_id, event_date, end_date) " .
            "VALUES ('" . loc_db_escape_string($rgstrname) .
            "', '" . loc_db_escape_string($rgstrdesc) .
            "', " . $tmtblID .
            ", " . $tmtblDetID .
            ", " . $usrID . ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), " . $usrID .
            ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), " . $orgid . ", '" . $evntDate . "', '" .
            $endDte . "')";
    return execUpdtInsSQL($insSQL);
}

function updateAttnRgstr($rgstrid, $rgstrname, $rgstrdesc, $tmtblID, $tmtblDetID, $evntDate, $endDte) {
    global $usrID;
    if ($evntDate != "") {
        $evntDate = cnvrtDMYTmToYMDTm($evntDate);
    }
    if ($endDte != "") {
        $endDte = cnvrtDMYTmToYMDTm($endDte);
    }
    $updtSQL = "UPDATE attn.attn_attendance_recs_hdr SET " .
            "recs_hdr_name='" . loc_db_escape_string($rgstrname) .
            "', recs_hdr_desc='" . loc_db_escape_string($rgstrdesc) .
            "', time_table_id=" . $tmtblID .
            ", time_table_det_id=" . $tmtblDetID .
            ", last_update_by=" . $usrID . ", " .
            "last_update_date=to_char(now(),'YYYY-MM-DD HH24:MI:SS'), event_date='" . $evntDate . "' " .
            ", end_date='" . $endDte . "' " .
            "WHERE (recs_hdr_id =" . $rgstrid . ")";
    return execUpdtInsSQL($updtSQL);
}

function createAttnRgstrDetLn($rgstrid, $psrnID, $dtetmein, $dtetmeout, $isPresent, $attnCmmnts, $name_desc, $noAdlts, $cstmrID, $vstrClsf,
        $sponsorID) {
    global $usrID;
    if ($dtetmein != "") {
        $dtetmein = cnvrtDMYTmToYMDTm($dtetmein);
    }
    if ($dtetmeout != "") {
        $dtetmeout = cnvrtDMYTmToYMDTm($dtetmeout);
    }
    $insSQL = "";
    if ($dtetmein != "" || $dtetmeout != "") {
        //visitor_name_desc, no_of_adults, no_of_chldrn, customer_id, visitor_classfctn
        $insSQL = "INSERT INTO attn.attn_attendance_recs(
            recs_hdr_id, person_id, date_time_in, date_time_out, 
            created_by, creation_date, last_update_by, last_update_date, 
            is_present, attn_comments,visitor_name_desc, no_of_persons, customer_id, visitor_classfctn, sponsor_id) " .
                "VALUES (" . $rgstrid . ", " . $psrnID . ", '" . loc_db_escape_string($dtetmein) .
                "', '" . loc_db_escape_string($dtetmeout) .
                "', " . $usrID . ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), " . $usrID .
                ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), '" . cnvrtBoolToBitStr($isPresent) .
                "', '" . loc_db_escape_string($attnCmmnts) .
                "', '" . loc_db_escape_string($name_desc) .
                "', " . $noAdlts .
                ", " . $cstmrID .
                ", '" . loc_db_escape_string($vstrClsf) .
                "'," . $sponsorID . ")";
    } else {
        $insSQL = "INSERT INTO attn.attn_attendance_recs(
            recs_hdr_id, person_id, 
            created_by, creation_date, last_update_by, last_update_date, 
            is_present, attn_comments,visitor_name_desc, no_of_persons, customer_id, visitor_classfctn, sponsor_id) " .
                "VALUES (" . $rgstrid . ", " . $psrnID . ", " . $usrID . ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), " . $usrID .
                ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), '" . cnvrtBoolToBitStr($isPresent) .
                "', '" . loc_db_escape_string($attnCmmnts) .
                "', '" . loc_db_escape_string($name_desc) .
                "', " . $noAdlts .
                ", " . $cstmrID .
                ", '" . loc_db_escape_string($vstrClsf) .
                "'," . $sponsorID . ")";
    }
    return execUpdtInsSQL($insSQL);
}

function updtAttnRgstrDetLn($rgstrDetLnid, $rgstrid, $psrnID, $dtetmein, $dtetmeout, $isPresent, $attnCmmnts, $name_desc, $noAdlts,
        $cstmrID, $vstrClsf, $sponsor_id) {
    global $usrID;
    if ($dtetmein != "") {
        $dtetmein = cnvrtDMYTmToYMDTm($dtetmein);
    }
    if ($dtetmeout != "") {
        $dtetmeout = cnvrtDMYTmToYMDTm($dtetmeout);
    }
    $insSQL = "";
    if ($dtetmein != "" || $dtetmeout != "") {
        $insSQL = "UPDATE attn.attn_attendance_recs SET 
            recs_hdr_id=" . $rgstrid . ", person_id=" . $psrnID . ", date_time_in='" . loc_db_escape_string($dtetmein) .
                "', date_time_out='" . loc_db_escape_string($dtetmeout) .
                "', last_update_by=" . $usrID .
                ", last_update_date=to_char(now(),'YYYY-MM-DD HH24:MI:SS'), is_present='" . cnvrtBoolToBitStr($isPresent) .
                "', attn_comments='" . loc_db_escape_string($attnCmmnts) .
                "',visitor_name_desc='" . loc_db_escape_string($name_desc) .
                "', no_of_persons=" . $noAdlts . ", customer_id=" . $cstmrID .
                ", visitor_classfctn='" . loc_db_escape_string($vstrClsf) .
                "', sponsor_id = " . $sponsor_id .
                " WHERE attnd_rec_id=" . $rgstrDetLnid . " ";
    } else {
        $insSQL = "UPDATE attn.attn_attendance_recs SET 
            recs_hdr_id=" . $rgstrid . ", person_id=" . $psrnID . ", last_update_by=" . $usrID .
                ", last_update_date=to_char(now(),'YYYY-MM-DD HH24:MI:SS'), is_present='" . cnvrtBoolToBitStr($isPresent) .
                "', attn_comments='" . loc_db_escape_string($attnCmmnts) .
                "',visitor_name_desc='" . loc_db_escape_string($name_desc) .
                "', no_of_persons=" . $noAdlts . ", customer_id=" . $cstmrID .
                ", visitor_classfctn='" . loc_db_escape_string($vstrClsf) .
                "', sponsor_id = " . $sponsor_id .
                " WHERE attnd_rec_id=" . $rgstrDetLnid . " ";
    }
    return execUpdtInsSQL($insSQL);
}

function updtAttnRgstrDetLn1($attnRecID, $dtetmein, $dtetmeout, $isPresent, $attnCmmnts) {
    global $usrID;
    if ($dtetmein != "") {
        $dtetmein = cnvrtDMYTmToYMDTm($dtetmein);
    }
    if ($dtetmeout != "") {
        $dtetmeout = cnvrtDMYTmToYMDTm($dtetmeout);
    }
    $insSQL = "UPDATE attn.attn_attendance_recs SET 
            date_time_in='" . loc_db_escape_string($dtetmein) .
            "', date_time_out='" . loc_db_escape_string($dtetmeout) .
            "', last_update_by=" . $usrID .
            ", last_update_date=to_char(now(),'YYYY-MM-DD HH24:MI:SS'), is_present='" . cnvrtBoolToBitStr($isPresent) .
            "', attn_comments='" . loc_db_escape_string($attnCmmnts) .
            "' WHERE attnd_rec_id=" . $attnRecID . " ";
    return execUpdtInsSQL($insSQL);
}

function createAttnRgstrTimeLn($attnRecID, $dtetmein, $dtetmeout, $isPresent, $attnCmmnts) {
    global $usrID;
    if ($dtetmein != "") {
        $dtetmein = cnvrtDMYTmToYMDTm($dtetmein);
    }
    if ($dtetmeout != "") {
        $dtetmeout = cnvrtDMYTmToYMDTm($dtetmeout);
    }
    $insSQL = "INSERT INTO attn.attn_attendance_recs_times(
            attnd_rec_id, date_time_in, date_time_out, 
            created_by, creation_date, last_update_by, last_update_date, 
            is_present, attn_comments) " .
            "VALUES (" . $attnRecID . ", '" . loc_db_escape_string($dtetmein) .
            "', '" . loc_db_escape_string($dtetmeout) .
            "', " . $usrID . ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), " . $usrID . ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), '" .
            cnvrtBoolToBitStr($isPresent) . "', '" . loc_db_escape_string($attnCmmnts) .
            "')";
    return execUpdtInsSQL($insSQL);
}

function updtAttnRgstrTimeLn($attnDetLnid, $dtetmein, $dtetmeout, $isPresent, $attnCmmnts) {
    global $usrID;
    if ($dtetmein != "") {
        $dtetmein = cnvrtDMYTmToYMDTm($dtetmein);
    }
    if ($dtetmeout != "") {
        $dtetmeout = cnvrtDMYTmToYMDTm($dtetmeout);
    }
    $insSQL = "UPDATE attn.attn_attendance_recs_times SET 
            date_time_in='" . loc_db_escape_string($dtetmein) .
            "', date_time_out='" . loc_db_escape_string($dtetmeout) .
            "', last_update_by=" . $usrID .
            ", last_update_date=to_char(now(),'YYYY-MM-DD HH24:MI:SS'), is_present='" . cnvrtBoolToBitStr($isPresent) .
            "', attn_comments='" . loc_db_escape_string($attnCmmnts) .
            "' WHERE attnd_det_rec_id=" . $attnDetLnid . " ";
    return execUpdtInsSQL($insSQL);
}

function createCstSplrRec($orgid, $cstmrname, $cstmrdesc, $cstmrTyp, $clssfctn, $pyblAccntID, $rcvblAccntID, $prsnID, $gender, $dob) {
    global $usrID;
    if ($dob != "") {
        $dob = cnvrtDMYToYMD($dob);
    }
    $insSQL = "INSERT INTO scm.scm_cstmr_suplr(" .
            "cust_sup_name, cust_sup_desc, created_by, creation_date, last_update_by, last_update_date, " .
            "cust_sup_clssfctn, cust_or_sup, org_id, dflt_pybl_accnt_id, dflt_rcvbl_accnt_id, " .
            "lnkd_prsn_id,person_gender,dob_estblshmnt) " .
            "VALUES ('" . loc_db_escape_string($cstmrname) .
            "', '" . loc_db_escape_string($cstmrdesc) .
            "', " . $usrID . ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), " . $usrID .
            ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), '" . loc_db_escape_string($clssfctn) .
            "', '" . loc_db_escape_string($cstmrTyp) . "', " . $orgid . ", " . $pyblAccntID .
            ", " . $rcvblAccntID . ", " . $prsnID . ",'" . loc_db_escape_string($gender) .
            "','" . loc_db_escape_string($dob) . "')";
    return execUpdtInsSQL($insSQL);
}

function createCstSplrSiteRec($cstmrID, $sitename, $sitedesc, $cntctPrsn, $cntctNos, $email, $bankNm, $bnkBrnch, $accNum, $blngAddrs,
        $shpngAddrs, $taxCode, $dscntCode, $swift_code, $nationality, $national_id_typ, $id_number, $date_issued, $expiry_date, $other_info) {
    global $usrID;
    $insSQL = "INSERT INTO scm.scm_cstmr_suplr_sites(" .
            "cust_supplier_id, contact_person_name, contact_nos, email, created_by, " .
            "creation_date, last_update_by, last_update_date, site_name, site_desc, " .
            "bank_name, bank_branch, bank_accnt_number, wth_tax_code_id, discount_code_id, " .
            "billing_address, ship_to_address, swift_code, 
            nationality, national_id_typ, id_number, date_issued, expiry_date, 
            other_info) " .
            "VALUES (" . $cstmrID . ", '" . loc_db_escape_string($cntctPrsn) .
            "', '" . loc_db_escape_string($cntctNos) .
            "', '" . loc_db_escape_string($email) .
            "', " . $usrID . ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), " . $usrID .
            ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), '" . loc_db_escape_string($sitename) .
            "', '" . loc_db_escape_string($sitedesc) . "', '"
            . loc_db_escape_string($bankNm) . "', '" . loc_db_escape_string($bnkBrnch) .
            "', '" . loc_db_escape_string($accNum) . "', " . $taxCode . ", " . $dscntCode .
            ", '" . loc_db_escape_string($blngAddrs) . "', '" . loc_db_escape_string($shpngAddrs) .
            "', '" . loc_db_escape_string($swift_code) . "', '" . loc_db_escape_string($nationality) .
            "', '" . loc_db_escape_string($national_id_typ) . "', '" . loc_db_escape_string($id_number) .
            "', '" . loc_db_escape_string($date_issued) . "', '" . loc_db_escape_string($expiry_date) .
            "', '" . loc_db_escape_string($other_info) . "')";
    return execUpdtInsSQL($insSQL);
}

function get_One_CstmrID($cstmrNm) {
    $strSql = "SELECT a.cust_sup_id " .
            "FROM scm.scm_cstmr_suplr a " .
            "WHERE(a.cust_sup_name = '" . loc_db_escape_string($cstmrNm) . "')";

    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -1;
}

function get_One_CstmrNm($cstmrID) {
    $strSql = "SELECT a.cust_sup_name " .
            "FROM scm.scm_cstmr_suplr a " .
            "WHERE(a.cust_sup_id = " . $cstmrID . ")";

    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return "";
}

function get_One_CstmrSiteNm($siteID) {
    $strSql = "SELECT a.site_name " .
            "FROM scm.scm_cstmr_suplr_sites a " .
            "WHERE(a.cust_sup_site_id = " . $siteID . ")";

    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return "";
}

function get_One_CstmrSiteID($cstmrID) {
    $strSql = "SELECT a.cust_sup_site_id " .
            "FROM scm.scm_cstmr_suplr_sites a " .
            "WHERE(a.cust_supplier_id = " . $cstmrID . ") ORDER BY a.cust_sup_site_id DESC";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -1;
}

function deleteAttnRgstr($rgstrid, $rgstrNm) {
    if (isAttnRgstrInUse($rgstrid) === true) {
        $dsply = "No Record Deleted<br/>Cannot delete a Register with Attendance Information!";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
    //attn.attn_attendance_costs
    $delSQL = "DELETE FROM attn.attn_attendance_recs_times "
            . "WHERE attnd_rec_id IN (Select attnd_rec_id from attn.attn_attendance_recs where recs_hdr_id = " . $rgstrid . ")";
    $affctd1 = execUpdtInsSQL($delSQL, "Register Name = " . $rgstrNm);

    $delSQL = "DELETE FROM attn.attn_attendance_recs_cntr WHERE recs_hdr_id = " . $rgstrid;
    $affctd2 = execUpdtInsSQL($delSQL, "Register Name = " . $rgstrNm);

    $delSQL = "DELETE FROM attn.attn_attendance_recs WHERE recs_hdr_id = " . $rgstrid;
    $affctd3 = execUpdtInsSQL($delSQL, "Register Name = " . $rgstrNm);

    $delSQL = "DELETE FROM attn.attn_attendance_recs_hdr WHERE recs_hdr_id = " . $rgstrid;
    $affctd4 = execUpdtInsSQL($delSQL, "Register Name = " . $rgstrNm);
    if ($affctd4 > 0) {
        $dsply = "";
        $dsply .= "<br/>Successfully Executed the ff-";
        $dsply .= "<br/>Deleted $affctd4 Register(s)!";
        $dsply .= "<br/>Deleted $affctd3 Register Record(s)!";
        $dsply .= "<br/>Deleted $affctd2 Head Count(s)!";
        $dsply .= "<br/>Deleted $affctd1 Register Time(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Deleted";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function isAttnRgstrInUse($rgstrid) {
    $strSql = "SELECT a.attnd_rec_id " .
            "FROM attn.attn_attendance_recs a " .
            "WHERE(a.recs_hdr_id = " . $rgstrid . ")";
    $result = executeSQLNoParams($strSql);
    if (loc_db_num_rows($result) > 0) {
        return true;
    }
    return false;
}

function deleteAttnTimeLn($rgtstrLnid, $PrsnNm) {
    $delSQL = "DELETE FROM attn.attn_attendance_recs_times WHERE attnd_det_rec_id = " . $rgtstrLnid;
    $affctd1 = execUpdtInsSQL($delSQL, "Person Name=" . $PrsnNm);
    if ($affctd1 > 0) {
        $dsply = "";
        $dsply .= "<br/>Successfully Executed the ff-";
        $dsply .= "<br/>Deleted $affctd1 Register Time(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Deleted";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function deleteAttnRgstrDLn($rgtstrLnid, $PrsnNm) {
    $errMsg = "";
    if (isAttnRgstrLnInUse($rgtstrLnid, $errMsg) === true) {
        $dsply = "No Record Deleted<br/>" . $errMsg;
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
    $delSQL = "DELETE FROM attn.attn_attendance_recs_times "
            . "WHERE attnd_rec_id = " . $rgtstrLnid . "";
    $affctd2 = execUpdtInsSQL($delSQL, "Person Name = " . $PrsnNm);
    $delSQL = "DELETE FROM attn.attn_attendance_recs WHERE attnd_rec_id = " . $rgtstrLnid;
    $affctd1 = execUpdtInsSQL($delSQL, "Person Name = " . $PrsnNm);
    if ($affctd1 > 0) {
        $dsply = "";
        $dsply .= "<br/>Successfully Executed the ff-";
        $dsply .= "<br/>Deleted $affctd1 Register Record(s)!";
        $dsply .= "<br/>Deleted $affctd2 Register Time(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Deleted";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function isAttnRgstrLnInUse($Lnid, &$errMsg) {
    $selSQL = "Select attn.getEvntAmntBilled(a.recs_hdr_id, a.customer_id), a.visitor_name_desc  
      from attn.attn_attendance_recs a where a.attnd_rec_id=" . $Lnid;
    $result = executeSQLNoParams($selSQL);
    while ($row = loc_db_fetch_array($result)) {
        if (((float) $row[0]) > 0) {
            $errMsg = "Cannot Delete " . $row[1] . " due to an Undeleted Invoice in this Name!";
            return true;
        }
    }
    $selSQL = "SELECT a.attnd_det_rec_id " .
            "FROM attn.attn_attendance_recs_times a " .
            "WHERE(a.attnd_rec_id = " . $Lnid . ")";
    $result = executeSQLNoParams($selSQL);
    if (loc_db_num_rows($result) > 0) {
        $errMsg = "Cannot Delete " . $row[1] . " due to Undeleted Extra Time Records!";
        return true;
    }
    return false;
}

function getAttnRgstrID($rgstrname, $orgid) {
    $sqlStr = "select recs_hdr_id from attn.attn_attendance_recs_hdr where lower(recs_hdr_name) = '"
            . loc_db_escape_string($rgstrname) . "' and org_id = " . $orgid;
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -1;
}

function getNewMtrcCntLnID() {
    //$strSql = "select nextval('accb.accb_trnsctn_batches_batch_id_seq'::regclass);";
    $strSql = "select nextval('attn.attn_attendance_recs_cntr_cntr_id_seq')";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -1;
}

function getEvntPointsLovNm($tmtblDetID) {
    $strSql = "SELECT e.attnd_points_lov_nm, d.event_id 
 FROM attn.attn_time_table_details d, attn.attn_attendance_events e 
 WHERE d.event_id = e.event_id and d.time_table_det_id = " . $tmtblDetID . "";

    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return "";
}

function get_One_AttnRgstrDet($rgstrid) {
    $strSql = "SELECT recs_hdr_id, recs_hdr_name, recs_hdr_desc, 
time_table_id, (SELECT time_table_name FROM attn.attn_time_table_hdrs d WHERE d.time_table_id = a.time_table_id) tmtblnm, 
time_table_det_id, (SELECT 'EVENT: ' || COALESCE(attn.get_event_name(d.event_id),'') || 
' VENUE: ' || COALESCE(attn.get_venue_name(d.assgnd_venue_id),'') || 
' HOST: ' || COALESCE(prs.get_prsn_name(d.assgnd_host_id),'') 
FROM attn.attn_time_table_details d WHERE d.time_table_det_id = a.time_table_det_id) evntdec, 
       to_char(to_timestamp(event_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') evdte, 
       to_char(to_timestamp(end_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') enddte,
       (Select x.event_id from attn.attn_time_table_details x where x.time_table_det_id = a.time_table_det_id) evnt_id
  FROM attn.attn_attendance_recs_hdr a " .
            "WHERE(a.recs_hdr_id = " . $rgstrid . ")";
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_Basic_AttnRgstr($searchWord, $searchIn, $offset, $limit_size, $orgID, $isAdhc) {
    $strSql = "";
    $whrcls = "";
    $extrWhr = "";
    if ($isAdhc) {
        $extrWhr = " and a.time_table_id <= 0";
    }
    if ($searchIn == "Register Name") {
        $whrcls = " AND (a.recs_hdr_name ilike '" . loc_db_escape_string($searchWord) .
                "')";
    } else if ($searchIn == "Register Description") {
        $whrcls = " AND (a.recs_hdr_desc ilike '" . loc_db_escape_string($searchWord) .
                "')";
    } else if ($searchIn == "Register Number") {
        $whrcls = " AND (trim(to_char(a.recs_hdr_id, '999999999999999999999999999')) ilike '" . loc_db_escape_string($searchWord) .
                "')";
    } else if ($searchIn == "Event Date") {
        $whrcls = " AND (to_char(to_timestamp(a.event_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') ilike '" . loc_db_escape_string($searchWord) .
                "')";
    }
    $strSql = "SELECT a.recs_hdr_id, a.recs_hdr_name " .
            "FROM attn.attn_attendance_recs_hdr a " .
            "WHERE ((a.org_id = " . $orgID . ")" . $whrcls . $extrWhr . ") ORDER BY a.recs_hdr_id DESC LIMIT " . $limit_size .
            " OFFSET " . (abs($offset * $limit_size));

    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_Total_AttnRgstr($searchWord, $searchIn, $orgID, $isAdhc) {
    $strSql = "";
    $whrcls = "";
    $extrWhr = "";
    if ($isAdhc) {
        $extrWhr = " and a.time_table_id <= 0";
    }
    if ($searchIn == "Register Name") {
        $whrcls = " AND (a.recs_hdr_name ilike '" . loc_db_escape_string($searchWord) .
                "')";
    } else if ($searchIn == "Register Description") {
        $whrcls = " AND (a.recs_hdr_desc ilike '" . loc_db_escape_string($searchWord) .
                "')";
    } else if ($searchIn == "Register Number") {
        $whrcls = " AND (trim(to_char(a.recs_hdr_id, '999999999999999999999999999')) ilike '" . loc_db_escape_string($searchWord) .
                "')";
    } else if ($searchIn == "Event Date") {
        $whrcls = " AND (to_char(to_timestamp(a.event_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') ilike '" . loc_db_escape_string($searchWord) .
                "')";
    }
    $strSql = "SELECT count(1) " .
            "FROM attn.attn_attendance_recs_hdr a " .
            "WHERE ((a.org_id = " . $orgID . ")" . $whrcls . $extrWhr . ")";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -1;
}

function get_One_AttnRgstr_DetLns($searchWord, $searchIn, $offset, $limit_size, $rgstrID) {
    $strSql = "";
    $whrcls = "";
    /*
     *  Person Name
      Date/Time In
      Date/Time Out
      Is Present?
      Attendance Comments
     * 
     */
    if ($searchIn == "Person Name/ID") {
        $whrcls = " AND ((CASE WHEN a.customer_id <= 0 and a.person_id <= 0 THEN a.visitor_name_desc 
           WHEN a.person_id>0 THEN prs.get_prsn_surname(a.person_id) || ' (' || prs.get_prsn_loc_id(a.person_id) || ')' 
            ELSE scm.get_cstmr_splr_name(a.customer_id)||scm.get_cstmr_splr_name(a.sponsor_id) END) ilike '" . loc_db_escape_string($searchWord) .
                "')";
    } else if ($searchIn == "Date/Time In") {
        $whrcls = " AND (to_char(to_timestamp(a.date_time_in,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') ilike '" . loc_db_escape_string($searchWord) .
                "')";
    } else if ($searchIn == "Date/Time Out") {
        $whrcls = " AND (to_char(to_timestamp(a.date_time_out,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') ilike '" . loc_db_escape_string($searchWord) .
                "')";
    } else if ($searchIn == "Is Present?") {
        $whrcls = " AND ((CASE WHEN a.is_present='1' THEN 'TRUE' ELSE 'FALSE' END) ilike '" . loc_db_escape_string($searchWord) .
                "')";
    } else if ($searchIn == "Attendance Comments") {
        $whrcls = " AND (a.attn_comments ilike '" . loc_db_escape_string($searchWord) .
                "')";
    }
    $strSql = "SELECT attnd_rec_id, recs_hdr_id, person_id, prs.get_prsn_loc_id(a.person_id), 
      CASE WHEN a.customer_id <= 0 and a.person_id <= 0 THEN a.visitor_name_desc 
           WHEN a.person_id>0 THEN prs.get_prsn_surname(a.person_id) || ' (' || prs.get_prsn_loc_id(a.person_id) || ')' 
            ELSE scm.get_cstmr_splr_name(a.customer_id) END, 
      CASE WHEN a.date_time_in != '' THEN to_char(to_timestamp(a.date_time_in,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') ELSE '' END, 
      CASE WHEN a.date_time_out != '' THEN to_char(to_timestamp(a.date_time_out,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') ELSE '' END,
      CASE WHEN a.is_present='1' THEN 'TRUE' ELSE 'FALSE' END, a.attn_comments,
      a.visitor_classfctn, a.no_of_persons, 0 no_of_chldrn, a.customer_id, a.sponsor_id, scm.get_cstmr_splr_name(a.sponsor_id),
 attn.getevntamntbilled(a.recs_hdr_id, a.customer_id), attn.getEvntAmntPaid(a.recs_hdr_id, a.customer_id)
  FROM attn.attn_attendance_recs a " .
            "WHERE((a.recs_hdr_id = " . $rgstrID . ")" . $whrcls . ") ORDER BY 5, 1 LIMIT " . $limit_size .
            " OFFSET " . (abs($offset * $limit_size));
    $result = executeSQLNoParams($strSql);
    //echo $strSql;
    return $result;
}

function get_Total_AttnRgstr_DetLns($searchWord, $searchIn, $rgstrID) {
    $strSql = "";
    $whrcls = "";
    /*
     *  Person Name
      Date/Time In
      Date/Time Out
      Is Present?
      Attendance Comments
     * 
     */
    if ($searchIn == "Person Name/ID") {
        $whrcls = " AND ((CASE WHEN a.customer_id <= 0 and a.person_id <= 0 THEN a.visitor_name_desc 
           WHEN a.person_id>0 THEN prs.get_prsn_surname(a.person_id) || ' (' || prs.get_prsn_loc_id(a.person_id) || ')' 
            ELSE scm.get_cstmr_splr_name(a.customer_id)||scm.get_cstmr_splr_name(a.sponsor_id) END) ilike '" . loc_db_escape_string($searchWord) .
                "')";
    } else if ($searchIn == "Date/Time In") {
        $whrcls = " AND (to_char(to_timestamp(a.date_time_in,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') ilike '" . loc_db_escape_string($searchWord) .
                "')";
    } else if ($searchIn == "Date/Time Out") {
        $whrcls = " AND (to_char(to_timestamp(a.date_time_out,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') ilike '" . loc_db_escape_string($searchWord) .
                "')";
    } else if ($searchIn == "Is Present?") {
        $whrcls = " AND ((CASE WHEN a.is_present='1' THEN 'TRUE' ELSE 'FALSE' END) ilike '" . loc_db_escape_string($searchWord) .
                "')";
    } else if ($searchIn == "Attendance Comments") {
        $whrcls = " AND (a.attn_comments ilike '" . loc_db_escape_string($searchWord) .
                "')";
    }
    $strSql = "SELECT count(1) FROM attn.attn_attendance_recs a " .
            "WHERE((a.recs_hdr_id = " . $rgstrID . ")" . $whrcls . ")";

    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return 0;
}

function get_One_AttnRgstr_Times($searchWord, $searchIn, $offset, $limit_size, $attnRecID) {
    $strSql = "";
    $whrcls = "";
    /*
     *  Person Name
      Date/Time In
      Date/Time Out
      Is Present?
      Attendance Comments
     * 
     */
    if ($searchIn == "Date/Time In") {
        $whrcls = " AND (to_char(to_timestamp(date_time_in,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') ilike '" . loc_db_escape_string($searchWord) .
                "')";
    } else if ($searchIn == "Date/Time Out") {
        $whrcls = " AND (to_char(to_timestamp(date_time_out,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') ilike '" . loc_db_escape_string($searchWord) .
                "')";
    } else if ($searchIn == "Is Present?") {
        $whrcls = " AND ((CASE WHEN is_present='1' THEN 'TRUE' ELSE 'FALSE' END) ilike '" . loc_db_escape_string($searchWord) .
                "')";
    } else if ($searchIn == "Attendance Comments") {
        $whrcls = " AND (attn_comments ilike '" . loc_db_escape_string($searchWord) .
                "')";
    }
    $strSql = "Select tbl1.* FROM (SELECT -1 attnd_det_rec_id, attnd_rec_id,  
      CASE WHEN a.date_time_in != '' THEN to_char(to_timestamp(a.date_time_in,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') ELSE '' END, 
      CASE WHEN a.date_time_out != '' THEN to_char(to_timestamp(a.date_time_out,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') ELSE '' END,
      CASE WHEN a.is_present='1' THEN 'TRUE' ELSE 'FALSE' END, 
      a.attn_comments, a.date_time_in, 'YES' from_main 
     FROM attn.attn_attendance_recs a WHERE((a.attnd_rec_id = " . $attnRecID . ")" . $whrcls . ") 
UNION
SELECT b.attnd_det_rec_id, b.attnd_rec_id,  
      CASE WHEN b.date_time_in != '' THEN to_char(to_timestamp(b.date_time_in,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') ELSE '' END, 
      CASE WHEN b.date_time_out != '' THEN to_char(to_timestamp(b.date_time_out,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') ELSE '' END,
      CASE WHEN b.is_present='1' THEN 'TRUE' ELSE 'FALSE' END, 
      b.attn_comments, b.date_time_in, 'NO' from_main
     FROM attn.attn_attendance_recs_times b " .
            "WHERE((b.attnd_rec_id = " . $attnRecID . ")" . $whrcls . ")
      ) tbl1 ORDER BY 6 DESC, 8 DESC LIMIT " . $limit_size .
            " OFFSET " . (abs($offset * $limit_size));
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_Total_AttnRgstr_Times($searchWord, $searchIn, $attnRecID) {
    $strSql = "";
    $whrcls = "";
    /*
     *  Person Name
      Date/Time In
      Date/Time Out
      Is Present?
      Attendance Comments
     * 
     */
    if ($searchIn == "Date/Time In") {
        $whrcls = " AND (to_char(to_timestamp(date_time_in,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') ilike '" . loc_db_escape_string($searchWord) .
                "')";
    } else if ($searchIn == "Date/Time Out") {
        $whrcls = " AND (to_char(to_timestamp(date_time_out,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') ilike '" . loc_db_escape_string($searchWord) .
                "')";
    } else if ($searchIn == "Is Present?") {
        $whrcls = " AND ((CASE WHEN is_present='1' THEN 'TRUE' ELSE 'FALSE' END) ilike '" . loc_db_escape_string($searchWord) .
                "')";
    } else if ($searchIn == "Attendance Comments") {
        $whrcls = " AND (attn_comments ilike '" . loc_db_escape_string($searchWord) .
                "')";
    }
    $strSql = "Select count(1) FROM (SELECT attnd_rec_id,  
      CASE WHEN a.date_time_in != '' THEN to_char(to_timestamp(a.date_time_in,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') ELSE '' END, 
      CASE WHEN a.date_time_out != '' THEN to_char(to_timestamp(a.date_time_out,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') ELSE '' END,
      CASE WHEN a.is_present='1' THEN 'TRUE' ELSE 'FALSE' END, 
      a.attn_comments, a.date_time_in, 'YES' from_main, -1  
     FROM attn.attn_attendance_recs a WHERE((a.attnd_rec_id = " . $attnRecID . ")" . $whrcls . ") 
    UNION
    SELECT b.attnd_rec_id,  
      CASE WHEN b.date_time_in != '' THEN to_char(to_timestamp(b.date_time_in,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') ELSE '' END, 
      CASE WHEN b.date_time_out != '' THEN to_char(to_timestamp(b.date_time_out,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') ELSE '' END,
      CASE WHEN b.is_present='1' THEN 'TRUE' ELSE 'FALSE' END, 
      b.attn_comments, b.date_time_in, 'NO' from_main, b.attnd_det_rec_id 
     FROM attn.attn_attendance_recs_times b " .
            "WHERE((b.attnd_rec_id = " . $attnRecID . ")" . $whrcls . ")
      ) tbl1";

    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return 0;
}

function get_One_AttnRgstr_MtrcLns($rgstrID, $lovNm, $editMode, $evntID) {
    $strSql = "";
    if ($lovNm == "") {
        $lovNm = "Attendance HeadCount Metrics";
    }
    $lovID = getLovID($lovNm);
    if ($editMode) {
        $strSql = "  SELECT   *
    FROM   (SELECT   a.cntr_id,
                     a.metric_name_desc,
                     trim(to_char(a.cntr_result,'9999999999999999999999999')) rslt,
                     a.rslt_comment,
                     a.lnkd_pssbl_val_id,
                     'NUMBER',
                     -1
              FROM   attn.attn_attendance_recs_cntr a
             WHERE   (a.recs_hdr_id = " . $rgstrID . ")
            UNION
              SELECT -1,
                     a.rslt_metric_name_desc,
                     '0',
                     a.rslt_comment,
                     -1,
                     a.rslt_type,
                     a.rslt_metric_id
              FROM   attn.attn_attendance_events_mtrcs a
             WHERE   (a.event_id = " . $evntID . "
                      AND a.is_enabled = '1'
                      AND a.rslt_metric_id NOT IN
                              (SELECT   c.evnt_metric_id
                                 FROM   attn.attn_attendance_events_rslts c
                                WHERE   (c.lnkd_rgstr_id = " . $rgstrID . ")))
            UNION
              SELECT a.evnt_rslt_id,
                     (SELECT  g.rslt_metric_name_desc FROM attn.attn_attendance_events_mtrcs g WHERE a.evnt_metric_id=g.rslt_metric_id) mtrcnm,
                     a.event_result,
                     a.rslt_desc,
                     -1,
                     (SELECT  g.rslt_type FROM attn.attn_attendance_events_mtrcs g WHERE a.evnt_metric_id=g.rslt_metric_id) rslttype,
                     a.evnt_metric_id
              FROM   attn.attn_attendance_events_rslts a
             WHERE   (a.lnkd_rgstr_id = " . $rgstrID . ")
            UNION
            SELECT   -1,
                     b.pssbl_value,
                     '0',
                     '',
                     b.pssbl_value_id,
                     'NUMBER',
                      -1
              FROM   gst.gen_stp_lov_values b
             WHERE   b.value_list_id = " . $lovID . "
                     AND b.is_enabled='1'
                     AND b.pssbl_value_id NOT IN
                              (SELECT   c.lnkd_pssbl_val_id
                                 FROM   attn.attn_attendance_recs_cntr c
                                WHERE   (c.recs_hdr_id = " . $rgstrID . "))) tbl1
ORDER BY   7, 2, 5";
    } else {
        $strSql = "SELECT * FROM (SELECT   a.cntr_id,
                     a.metric_name_desc,
                     trim(to_char(a.cntr_result,'9999999999999999999999999')) rslt,
                     a.rslt_comment,
                     a.lnkd_pssbl_val_id,
                     'NUMBER',
                      -1
              FROM   attn.attn_attendance_recs_cntr a
             WHERE   (a.recs_hdr_id = " . $rgstrID . ") 
          UNION
              SELECT a.evnt_rslt_id,
                     (SELECT  g.rslt_metric_name_desc FROM attn.attn_attendance_events_mtrcs g WHERE a.evnt_metric_id=g.rslt_metric_id) mtrcnm,
                     a.event_result,
                     a.rslt_desc,
                     -1,
                     (SELECT  g.rslt_type FROM attn.attn_attendance_events_mtrcs g WHERE a.evnt_metric_id=g.rslt_metric_id) rslttype,
                     a.evnt_metric_id
              FROM   attn.attn_attendance_events_rslts a
             WHERE   (a.lnkd_rgstr_id = " . $rgstrID . ")) tbl1           
    ORDER BY   7, 2, 5";
    }
    $result = executeSQLNoParams($strSql);
    return $result;
}

function getNewRgstrID() {
    $strSql = "select nextval('attn.attn_attendance_recs_hdr_recs_hdr_id_seq'::regclass);";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -1;
}

function doesRgstrHvPrsn($prsnid, $rgstrid) {
    $selSQL = "SELECT attnd_rec_id " .
            "FROM attn.attn_attendance_recs WHERE ((person_id = " . $prsnid .
            ") and (recs_hdr_id = " . $rgstrid . "))";
    $result = executeSQLNoParams($selSQL);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -1;
}

function doesRgstrHvCstmr($cstmrID, $rgstrid) {
    $selSQL = "SELECT attnd_rec_id " .
            "FROM attn.attn_attendance_recs WHERE ((customer_id = " . $cstmrID .
            ") and (recs_hdr_id = " . $rgstrid . "))";
    $result = executeSQLNoParams($selSQL);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -1;
}

function loadMjrTmDivs($smllstSlotDrtn, $majTmeDivTyp) {
    global $gnrlTrnsDteYMDHMS;
    $yearnum = (int) substr($gnrlTrnsDteYMDHMS, 0, 4);
    $pssblItems1 = array($yearnum, ($yearnum + 1), ($yearnum + 2), ($yearnum + 3)
        , ($yearnum + 4), ($yearnum + 5), ($yearnum + 6), ($yearnum + 7)
        , ($yearnum + 8), ($yearnum + 9), ($yearnum + 10));
    $pssblItems2 = array("Half-Year 1", "Half-Year 2");
    $pssblItems3 = array("Quarter 1", "Quarter 2", "Quarter 3", "Quarter 4");
    $pssblItems4 = array("JAN", "FEB", "MAR", "APR", "MAY", "JUN", "JUL", "AUG", "SEP", "OCT", "NOV", "DEC");
    $pssblItems5 = array("Fortnight 01", "Fortnight 02", "Fortnight 03", "Fortnight 04", "Fortnight 05", "Fortnight 06", "Fortnight 07", "Fortnight 08"
        , "Fortnight 09", "Fortnight 10", "Fortnight 11", "Fortnight 12", "Fortnight 13", "Fortnight 14", "Fortnight 15", "Fortnight 16"
        , "Fortnight 17", "Fortnight 18", "Fortnight 19", "Fortnight 20", "Fortnight 21", "Fortnight 22", "Fortnight 23", "Fortnight 24"
        , "Fortnight 25", "Fortnight 26");
    $pssblItems6 = array("Fortnight 01", "Fortnight 02");
    $pssblItems7 = array("Week 01", "Week 02", "Week 03", "Week 04", "Week 05", "Week 06", "Week 07", "Week 08"
        , "Week 09", "Week 10", "Week 11", "Week 12", "Week 13", "Week 14", "Week 15", "Week 16"
        , "Week 17", "Week 18", "Week 19", "Week 20", "Week 21", "Week 22", "Week 23", "Week 24"
        , "Week 25", "Week 26", "Week 27", "Week 28", "Week 29", "Week 30", "Week 31", "Week 32", "Week 33", "Week 34"
        , "Week 35", "Week 36", "Week 37", "Week 38", "Week 39", "Week 40", "Week 41", "Week 42"
        , "Week 43", "Week 44", "Week 45", "Week 46", "Week 47", "Week 48", "Week 49", "Week 50"
        , "Week 51", "Week 52");
    $pssblItems8 = array("Week 01", "Week 02", "Week 03", "Week 04", "Week 05");
    $pssblItems9 = array("Day 01", "Day 02", "Day 03", "Day 04", "Day 05", "Day 06", "Day 07", "Day 08"
        , "Day 09", "Day 10", "Day 11", "Day 12", "Day 13", "Day 14", "Day 15", "Day 16"
        , "Day 17", "Day 18", "Day 19", "Day 20", "Day 21", "Day 22", "Day 23", "Day 24"
        , "Day 25", "Day 26", "Day 27", "Day 28", "Day 29", "Day 30", "Day 31");
    $pssblItems10 = array("01-SUN", "02-MON", "03-TUE", "04-WED", "05-THU", "06-FRI", "07-SAT");
    $pssblItems11 = array("Hour 01", "Hour 02", "Hour 03", "Hour 04", "Hour 05", "Hour 06", "Hour 07", "Hour 08"
        , "Hour 09", "Hour 10", "Hour 11", "Hour 12", "Hour 13", "Hour 14", "Hour 15", "Hour 16"
        , "Hour 17", "Hour 18", "Hour 19", "Hour 20", "Hour 21", "Hour 22", "Hour 23", "Hour 00"
    );
    $smlstMins = 30;
    if ($smllstSlotDrtn > 0) {
        $smlstMins = $smllstSlotDrtn;
    }
    $slotsNum = round((float) (1440 / $smlstMins));
    $pssblItems12 = [];
    $dte1 = DateTime::createFromFormat('d-M-Y H:i:s', "01-Jan-2013 00:00:00");
    for ($i = 0; $i < $slotsNum; $i++) {
        $pssblItems12[$i] = $dte1->format('H:i');
        $dte1 = $dte1->modify('+' . $smlstMins . ' minute');
    }

    $pssblItems = [];
    if ($majTmeDivTyp == "01-Year") {
        $pssblItems = $pssblItems1;
    } else if ($majTmeDivTyp == "02-Half-Year") {
        $pssblItems = $pssblItems2;
    } else if ($majTmeDivTyp == "03-Quarter") {
        $pssblItems = $pssblItems3;
    } else if ($majTmeDivTyp == "04-Month") {
        $pssblItems = $pssblItems4;
    } else if ($majTmeDivTyp == "05-Fortnights in a Year") {
        $pssblItems = $pssblItems5;
    } else if ($majTmeDivTyp == "06-Fortnights in a Month") {
        $pssblItems = $pssblItems6;
    } else if ($majTmeDivTyp == "07-Weeks in a Year") {
        $pssblItems = $pssblItems7;
    } else if ($majTmeDivTyp == "08-Weeks in a Month") {
        $pssblItems = $pssblItems8;
    } else if ($majTmeDivTyp == "09-Days in a Month") {
        $pssblItems = $pssblItems9;
    } else if ($majTmeDivTyp == "10-Days in a Week") {
        $pssblItems = $pssblItems10;
    } else if ($majTmeDivTyp == "11-Hours in a Day") {
        $pssblItems = $pssblItems11;
    } else if ($majTmeDivTyp == "12-Hours/Minutes in a Day") {
        $pssblItems = $pssblItems12;
    }
    return join(";", $pssblItems);
}

function loadMnrTmDivs($smllstSlotDrtn, $minTmeDivTyp) {
    global $gnrlTrnsDteYMDHMS;
    $yearnum = (int) substr($gnrlTrnsDteYMDHMS, 0, 4);
    $pssblItems1 = array($yearnum, ($yearnum + 1), ($yearnum + 2), ($yearnum + 3)
        , ($yearnum + 4), ($yearnum + 5), ($yearnum + 6), ($yearnum + 7)
        , ($yearnum + 8), ($yearnum + 9), ($yearnum + 10));
    $pssblItems2 = array("Half-Year 1", "Half-Year 2");
    $pssblItems3 = array("Quarter 1", "Quarter 2", "Quarter 3", "Quarter 4");
    $pssblItems4 = array("JAN", "FEB", "MAR", "APR", "MAY", "JUN", "JUL", "AUG", "SEP", "OCT", "NOV", "DEC");
    $pssblItems5 = array("Fortnight 01", "Fortnight 02", "Fortnight 03", "Fortnight 04", "Fortnight 05", "Fortnight 06", "Fortnight 07", "Fortnight 08"
        , "Fortnight 09", "Fortnight 10", "Fortnight 11", "Fortnight 12", "Fortnight 13", "Fortnight 14", "Fortnight 15", "Fortnight 16"
        , "Fortnight 17", "Fortnight 18", "Fortnight 19", "Fortnight 20", "Fortnight 21", "Fortnight 22", "Fortnight 23", "Fortnight 24"
        , "Fortnight 25", "Fortnight 26");
    $pssblItems6 = array("Fortnight 01", "Fortnight 02");
    $pssblItems7 = array("Week 01", "Week 02", "Week 03", "Week 04", "Week 05", "Week 06", "Week 07", "Week 08"
        , "Week 09", "Week 10", "Week 11", "Week 12", "Week 13", "Week 14", "Week 15", "Week 16"
        , "Week 17", "Week 18", "Week 19", "Week 20", "Week 21", "Week 22", "Week 23", "Week 24"
        , "Week 25", "Week 26", "Week 27", "Week 28", "Week 29", "Week 30", "Week 31", "Week 32", "Week 33", "Week 34"
        , "Week 35", "Week 36", "Week 37", "Week 38", "Week 39", "Week 40", "Week 41", "Week 42"
        , "Week 43", "Week 44", "Week 45", "Week 46", "Week 47", "Week 48", "Week 49", "Week 50"
        , "Week 51", "Week 52");
    $pssblItems8 = array("Week 01", "Week 02", "Week 03", "Week 04", "Week 05");
    $pssblItems9 = array("Day 01", "Day 02", "Day 03", "Day 04", "Day 05", "Day 06", "Day 07", "Day 08"
        , "Day 09", "Day 10", "Day 11", "Day 12", "Day 13", "Day 14", "Day 15", "Day 16"
        , "Day 17", "Day 18", "Day 19", "Day 20", "Day 21", "Day 22", "Day 23", "Day 24"
        , "Day 25", "Day 26", "Day 27", "Day 28", "Day 29", "Day 30", "Day 31");
    $pssblItems10 = array("01-SUN", "02-MON", "03-TUE", "04-WED", "05-THU", "06-FRI", "07-SAT");
    $pssblItems11 = array("Hour 01", "Hour 02", "Hour 03", "Hour 04", "Hour 05", "Hour 06", "Hour 07", "Hour 08"
        , "Hour 09", "Hour 10", "Hour 11", "Hour 12", "Hour 13", "Hour 14", "Hour 15", "Hour 16"
        , "Hour 17", "Hour 18", "Hour 19", "Hour 20", "Hour 21", "Hour 22", "Hour 23", "Hour 00"
    );
    $smlstMins = 30;
    if ($smllstSlotDrtn > 0) {
        $smlstMins = (int) $smllstSlotDrtn;
    }
    $slotsNum = (int) round((float) (1440 / $smlstMins));
    $pssblItems12 = [];

    $dte1 = DateTime::createFromFormat('d-M-Y H:i:s', "01-Jan-2013 00:00:00");
    for ($i = 0; $i < $slotsNum; $i++) {
        $pssblItems12[$i] = $dte1->format('H:i');
        $dte1 = $dte1->modify('+' . $smlstMins . ' minute');
    }

    $pssblItems = [];
    if ($minTmeDivTyp == "01-Year") {
        $pssblItems = $pssblItems1;
    } else if ($minTmeDivTyp == "02-Half-Year") {
        $pssblItems = $pssblItems2;
    } else if ($minTmeDivTyp == "03-Quarter") {
        $pssblItems = $pssblItems3;
    } else if ($minTmeDivTyp == "04-Month") {
        $pssblItems = $pssblItems4;
    } else if ($minTmeDivTyp == "05-Fortnights in a Year") {
        $pssblItems = $pssblItems5;
    } else if ($minTmeDivTyp == "06-Fortnights in a Month") {
        $pssblItems = $pssblItems6;
    } else if ($minTmeDivTyp == "07-Weeks in a Year") {
        $pssblItems = $pssblItems7;
    } else if ($minTmeDivTyp == "08-Weeks in a Month") {
        $pssblItems = $pssblItems8;
    } else if ($minTmeDivTyp == "09-Days in a Month") {
        $pssblItems = $pssblItems9;
    } else if ($minTmeDivTyp == "10-Days in a Week") {
        $pssblItems = $pssblItems10;
    } else if ($minTmeDivTyp == "11-Hours in a Day") {
        $pssblItems = $pssblItems11;
    } else if ($minTmeDivTyp == "12-Hours/Minutes in a Day") {
        $pssblItems = $pssblItems12;
    }
    return join(";", $pssblItems);
}

function get_Total_AttnCostLns($searchWord, $searchIn, $rgstrHdrID) {
    $strSql = "";
    $whrcls = "";
    /*
     *  Description
      Source Document No.
      Category
     * 
     */
    if ($searchIn == "Description") {
        $whrcls = " AND (a.cost_comments ilike '" . loc_db_escape_string($searchWord) .
                "')";
    } else if ($searchIn == "Source Document No.") {
        $whrcls = " AND (scm.get_src_doc_num(a.src_doc_id, a.src_doc_type) ilike '" . loc_db_escape_string($searchWord) .
                "')";
    } else if ($searchIn == "Category") {
        $whrcls = " AND (a.cost_classfctn ilike '" . loc_db_escape_string($searchWord) .
                "')";
    }
    $strSql = "SELECT count(1)
  FROM attn.attn_attendance_costs a " .
            "WHERE((a.recs_hdr_id = " . $rgstrHdrID . ")" . $whrcls . ")";


    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return 0;
}

function get_One_AttnCostLns($searchWord, $searchIn, $offset, $limit_size, $rgstrHdrID) {
    $strSql = "";
    $whrcls = "";
    /*
     *  Description
      Source Document No.
      Category
     * 
     */
    if ($searchIn == "Description") {
        $whrcls = " AND (a.cost_comments ilike '" . loc_db_escape_string($searchWord) .
                "')";
    } else if ($searchIn == "Source Document No.") {
        $whrcls = " AND (scm.get_src_doc_num(a.src_doc_id, a.src_doc_type) ilike '" . loc_db_escape_string($searchWord) .
                "')";
    } else if ($searchIn == "Category") {
        $whrcls = " AND (a.cost_classfctn ilike '" . loc_db_escape_string($searchWord) .
                "')";
    }
    $strSql = "SELECT a.attnd_cost_id, a.recs_hdr_id, scm.get_src_doc_num(a.src_doc_id, a.src_doc_type), 
       a.src_doc_id, a.src_doc_type, a.cost_comments, 
       a.no_of_persons, a.no_of_days, a.unit_cost, (a.no_of_persons*a.unit_cost) ttlamount, 
       cost_classfctn, a.gl_batch_id, accb.get_gl_batch_name(a.gl_batch_id) batch_name, 
       a.incrs_dcrs1, a.asset_expns_acnt_id, 
       accb.get_accnt_num(a.asset_expns_acnt_id) || '.' || accb.get_accnt_name(a.asset_expns_acnt_id) accnt_name, 
       a.incrs_dcrs2, a.asset_lblty_acnt_id, 
       accb.get_accnt_num(a.asset_lblty_acnt_id) || '.' || accb.get_accnt_name(a.asset_lblty_acnt_id) accnt_name2,
       attn.isEvntCostHanging(a.recs_hdr_id,a.attnd_cost_id) 
       FROM attn.attn_attendance_costs a " .
            "WHERE((a.recs_hdr_id = " . $rgstrHdrID . ")" . $whrcls . ") ORDER BY 11, 1 LIMIT " . $limit_size .
            " OFFSET " . (abs($offset * $limit_size));
    $result = executeSQLNoParams($strSql);
    return $result;
}

function createAttnCostLn($rgstrid, $srcDcID, $srcDocType, $attnCmmnts, $noPrsns, $noDays, $unitCost, $costClsf) {
    global $usrID;
    //visitor_name_desc, no_of_adults, no_of_chldrn, customer_id, visitor_classfctn
    $insSQL = "INSERT INTO attn.attn_attendance_costs(
            recs_hdr_id, src_doc_id, src_doc_type, created_by, 
            creation_date, last_update_by, last_update_date, cost_comments, 
            no_of_persons, no_of_days, unit_cost, cost_classfctn) " .
            "VALUES (" . $rgstrid . ", " . $srcDcID . ", '" . loc_db_escape_string($srcDocType) .
            "', " . $usrID . ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), " . $usrID .
            ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), '" . loc_db_escape_string($attnCmmnts) .
            "', " . $noPrsns .
            ", " . $noDays .
            ", " . $unitCost .
            ", '" . loc_db_escape_string($costClsf) .
            "')";
    return execUpdtInsSQL($insSQL);
}

function updtAttnCostLn($costLnid, $rgstrid, $srcDcID, $srcDocType, $attnCmmnts, $noPrsns, $noDays, $unitCost, $costClsf) {
    global $usrID;
    //visitor_name_desc, no_of_adults, no_of_chldrn, customer_id, visitor_classfctn
    $insSQL = "UPDATE attn.attn_attendance_costs SET 
            recs_hdr_id=" . $rgstrid . ", src_doc_id=" . $srcDcID .
            ", src_doc_type='" . loc_db_escape_string($srcDocType) .
            "', last_update_by=" . $usrID .
            ", last_update_date=to_char(now(),'YYYY-MM-DD HH24:MI:SS'), cost_comments='" . loc_db_escape_string($attnCmmnts) .
            "', no_of_persons=" . $noPrsns . ", no_of_days=" . $noDays .
            ", unit_cost=" . $unitCost . ", cost_classfctn='" . loc_db_escape_string($costClsf) .
            "' WHERE attnd_cost_id=" . $costLnid . " ";
    return execUpdtInsSQL($insSQL);
}

function deleteAttnCostLn($costLnid, $costDesc) {
    $delSQL = "DELETE FROM attn.attn_attendance_costs WHERE attnd_cost_id = " . $costLnid;
    $affctd1 = execUpdtInsSQL($delSQL, "Name=" . $costDesc);
    if ($affctd1 > 0) {
        $dsply = "";
        $dsply .= "<br/>Successfully Executed the ff-";
        $dsply .= "<br/>Deleted $affctd1 Cost(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Deleted";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

?>
