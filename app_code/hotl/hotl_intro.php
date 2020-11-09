<?php

$menuItems = array("Summary Dashboard", "Reservations / Check-Ins",
    "Facility Types", "Restaurant", "Gym / Pool/ Sports", "Complaints / Issues", "General Rentals"
);
$menuImages = array("dashboard220.png", "booking.png", "categories.png", "menu.ico", "sports.png"
    , "list.jpg", "rent1.png");

$mdlNm = "Hospitality Management";
$ModuleName = $mdlNm;

$dfltPrvldgs = array(
    /* 1 */ "View Hospitality Manager", "View Rooms Dashboard",
    /* 2 */ "View Reservations", "View Check Ins", "View Service Types",
    /* 5 */ "View Restaurant", "View Gym",
    /* 7 */ "Add Service Types", "Edit Service Types", "Delete Service Types",
    /* 10 */ "Add Check Ins", "Edit Check Ins", "Delete Check Ins",
    /* 13 */ "Add Applications", "Edit Applications", "Delete Applications",
    /* 16 */ "Add Gym Types", "Edit Gym Types", "Delete Gym Types",
    /* 19 */ "Add Gym Registration", "Edit Gym Registration", "Delete Gym Registration",
    /* 22 */ "View SQL", "View Record History",
    /* 24 */ "Add Table Order", "Edit Table Order", "Delete Table Order", "Setup Tables",
    /* 28 */ "View Complaints/Observations", "Add Complaints/Observations", "Edit Complaints/Observations", "Delete Complaints/Observations",
    /* 32 */ "View only Self-Created Sales", "Cancel Documents", "Take Payments", "Apply Adhoc Discounts", "Apply Pre-defined Discounts",
    /* 37 */ "View Rental Item", "Can Edit Unit Price", "View only Branch-Related Documents");

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


$invPrmSnsRstl = getHtlPrmssns($prsnid, $orgID, $usrID);
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
$canview = ($invPrmSnsRstl[4] >= 1) ? true : false;
$canViewDshbrd = ($invPrmSnsRstl[5] >= 1) ? true : false;
$canViewRsrvtns = ($invPrmSnsRstl[6] >= 1) ? true : false;
$canViewChckIns = ($invPrmSnsRstl[7] >= 1) ? true : false;
$canViewSrvcTyp = ($invPrmSnsRstl[8] >= 1) ? true : false;
$canViewRstrnt = ($invPrmSnsRstl[9] >= 1) ? true : false;
$canViewGym = ($invPrmSnsRstl[10] >= 1) ? true : false;
$canViewCmplnts = ($invPrmSnsRstl[11] >= 1) ? true : false;
$canVwRcHstry = ($invPrmSnsRstl[12] >= 1) ? true : false;
$canViewSQL = ($invPrmSnsRstl[13] >= 1) ? true : false;
$vwOnlySelf = ($invPrmSnsRstl[14] >= 1) ? true : false;
$canEdtPrice = ($invPrmSnsRstl[15] >= 1) ? true : false;
$cancelDocs = ($invPrmSnsRstl[16] >= 1) ? true : false;
$canPayDocs = ($invPrmSnsRstl[17] >= 1) ? true : false;
$canCreateDscnt = ($invPrmSnsRstl[18] >= 1) ? true : false;
$canApplyDscnt = ($invPrmSnsRstl[19] >= 1) ? true : false;
$vwOnlyBranch = ($invPrmSnsRstl[20] >= 1) ? true : false;

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
    $cntent .= "<li onclick=\"openATab('#allmodules', 'grp=$group&typ=$type');\">
                        <span style=\"text-decoration:none;\">Hospitality Menu</span>
                </li>";
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
      <p>";
        /* $cntent .= "</ul></div>
          <div style=\"font-family: Tahoma, Arial, sans-serif;font-size: 1.3em;padding:10px 15px 15px 20px;border:1px solid #ccc;\">
          <div style=\"padding:5px 30px 5px 10px;margin-bottom:2px;\">
          <span style=\"font-family: georgia, times;font-size: 12px;font-style:italic;
          font-weight:normal;\">This module helps you to manage your organization's Hospitality Needs! The module has the ff areas:</span>
          </div>
          <p>"; */
        $grpcntr = 0;
        for ($i = 0; $i < count($menuItems); $i++) {
            $No = $i + 1;
            if ($i == 0 && $canViewDshbrd === false) {
                continue;
            } else if ($i == 1 && $canViewRsrvtns === false && $canViewChckIns === false) {
                continue;
            } else if ($i == 2 && $canViewSrvcTyp === false) {
                continue;
            } else if ($i == 3 && $canViewRstrnt === false) {
                continue;
            } else if ($i == 4 && $canViewGym === false) {
                continue;
            } else if ($i == 5 && $canViewCmplnts === false) {
                continue;
            } else if ($i == 6 && $canViewChckIns === false) {
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
        require "smmry_dshbrd.php";
    } else if ($pgNo == 2) {
        require "checkins.php";
    } else if ($pgNo == 3) {
        require "fclty_types.php";
    } else if ($pgNo == 4) {
        require "restaurant.php";
    } else if ($pgNo == 5) {
        require "gym_pool.php";
    } else if ($pgNo == 6) {
        require "complaints.php";
    } else if ($pgNo == 7) {
        require "gnrl_rentals.php";
    } else {
        restricted();
    }
} else {
    restricted();
}

function getHtlPrmssns($prsnID, $orgid, $usrID) {
    global $ssnRoles;
    $mdlNm = "Hospitality Management";
    $rslts = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
    $sqlStr = "Select (select oprtnl_crncy_id from org.org_details where org_id = $orgid), "
            . "pasn.get_prsn_siteid(" . $prsnID . "), "
            . "pasn.get_prsn_divid_of_spctype(" . $prsnID . ",'Access Control Group'),"
            . "scm.getUserStoreID(" . $usrID . ", " . $orgid . "), "
            . "sec.test_prmssns('View Hospitality Manager', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "') vwInvntry, "
            . "sec.test_prmssns('View Rooms Dashboard', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
            . "sec.test_prmssns('View Reservations', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
            . "sec.test_prmssns('View Check Ins', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
            . "sec.test_prmssns('View Service Types', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
            . "sec.test_prmssns('View Restaurant', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
            . "sec.test_prmssns('View Gym', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
            . "sec.test_prmssns('View Complaints/Observations', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
            . "sec.test_prmssns('View Record History', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
            . "sec.test_prmssns('View SQL', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
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
    }
    return $rslts;
}

function get_dshbrd_rooms($searchWord, $searchIn, $offset, $limit_size, $orgID, $fcltyType) {
    global $orgID;
    $strSql = "";
    $whereClause = "";
// if ($searchIn == "Facility Name")
    if ($searchIn == "Facility Type Name") {
        $whereClause = "(b.service_type_name ilike '" . loc_db_escape_string($searchWord) .
                "')";
    } else if ($searchIn == "Facility Type Description") {
        $whereClause = "(b.description ilike '" . loc_db_escape_string($searchWord) .
                "')";
    } else {
        $whereClause = "(a.room_name ilike '" . loc_db_escape_string($searchWord) .
                "')";
    }

    $strSql = "SELECT a.room_id, a.room_name, a.room_description, a.is_enabled, 
   CASE WHEN a.is_enabled != '1' THEN 'BLOCKED' 
        WHEN a.crnt_no_occpnts = a.mx_no_occpnts AND a.crnt_no_occpnts>0 THEN 'FULLY ISSUED OUT' 
        WHEN a.crnt_no_occpnts < a.mx_no_occpnts AND a.crnt_no_occpnts>0 THEN 'PARTIALLY ISSUED OUT' 
        WHEN a.crnt_no_occpnts > a.mx_no_occpnts THEN 'OVERLOADED'
        WHEN (Select MAX(check_in_id) from hotl.checkins_hdr y where y.doc_status='Reserved' and y.service_det_id = a.room_id) IS NOT NULL THEN
        'RESERVED'
        WHEN a.needs_hse_keeping ='1' THEN 'DIRTY' 
        ELSE 'AVAILABLE' END status,
a.mx_no_occpnts,a.crnt_no_occpnts, a.needs_hse_keeping, b.service_type_name,
COALESCE((Select to_char(to_timestamp(MIN(y.start_date),'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY')||' to '||
to_char(to_timestamp(MAX(y.end_date),'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY') 
from hotl.checkins_hdr y 
where (y.doc_status='Reserved' or y.doc_status='Checked-In' or y.doc_status='Ordered' or y.doc_status='Rented Out') and y.service_det_id = a.room_id),'') period_rsvrd
, COALESCE((select scm.get_cstmr_splr_name(MAX(z.customer_id)) from hotl.checkins_hdr z  
where (z.doc_status='Reserved' or z.doc_status='Checked-In' or z.doc_status='Ordered' or z.doc_status='Rented Out') and z.service_det_id = a.room_id),b.service_type_name) cstmr_nm,
a.service_type_id
    FROM hotl.rooms a, hotl.service_types b " .
            "WHERE " . $whereClause . " and a.service_type_id=b.service_type_id and b.org_id=" . $orgID . " and b.type_of_facility='" . loc_db_escape_string($fcltyType) .
            "' ORDER BY a.crnt_no_occpnts DESC, a.is_enabled ASC, a.needs_hse_keeping DESC, a.room_name LIMIT " . $limit_size .
            " OFFSET " . (abs($offset * $limit_size));
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_Ttl_dshbrd_rooms($searchWord, $searchIn, $orgID, $fcltyType) {
    global $orgID;
    $strSql = "";
    $whereClause = "";
// if ($searchIn == "Facility Name")
    if ($searchIn == "Facility Type Name") {
        $whereClause = "(b.service_type_name ilike '" . loc_db_escape_string($searchWord) .
                "')";
    } else if ($searchIn == "Facility Type Description") {
        $whereClause = "(b.description ilike '" . loc_db_escape_string($searchWord) .
                "')";
    } else {
        $whereClause = "(a.room_name ilike '" . loc_db_escape_string($searchWord) .
                "')";
    }
    $strSql = "SELECT  count(1) FROM hotl.rooms a, hotl.service_types b " .
            "WHERE " . $whereClause . " and a.service_type_id=b.service_type_id and b.org_id=" .
            $orgID . " and b.type_of_facility='" . loc_db_escape_string($fcltyType) .
            "'";
    //echo $strSql;
    $result = executeSQLNoParams($strSql);
    if (loc_db_num_rows($result) > 0) {
        $row = loc_db_fetch_array($result);
        return (float) $row[0];
    }
    return 0;
}

function get_SrvcTyps($searchWord, $searchIn, $offset, $limit_size, $orgID) {
    $strSql = "";

    $whereClause = "(b.room_name ilike '" . loc_db_escape_string($searchWord) .
            "' or a.service_type_name ilike '" . loc_db_escape_string($searchWord) .
            "' or a.description ilike '" . loc_db_escape_string($searchWord) .
            "')";
    $strSql = "SELECT DISTINCT a.service_type_id, a.service_type_name " .
            "FROM hotl.service_types a, hotl.rooms b " .
            "WHERE " . $whereClause . " and a.org_id=" . $orgID .
            " and a.service_type_id=b.service_type_id ORDER BY a.service_type_id DESC LIMIT " . $limit_size .
            " OFFSET " . (abs($offset * $limit_size));

    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_Ttl_SrvsTyps($searchWord, $searchIn, $orgID) {
    $strSql = "";

    $whereClause = "(b.room_name ilike '" . loc_db_escape_string($searchWord) .
            "' or a.service_type_name ilike '" . loc_db_escape_string($searchWord) .
            "' or a.description ilike '" . loc_db_escape_string($searchWord) .
            "')";

    $strSql = "SELECT count(DISTINCT a.service_type_id) " .
            "FROM hotl.service_types a, hotl.rooms b " .
            "WHERE " . $whereClause . " and a.org_id=" . $orgID . " and a.service_type_id=b.service_type_id ";
    $result = executeSQLNoParams($strSql);
    if (loc_db_num_rows($result) > 0) {
        $row = loc_db_fetch_array($result);
        return (float) $row[0];
    }
    return 0;
}

function get_One_ServTypeDt($serv_type_hdrID) {
    $strSql = "SELECT service_type_id, service_type_name, description, 
      is_enabled, inv_item_id, type_of_facility, no_shw_inv_itm_id,
cancelltn_days_fr_pnlty, pnlty_num_dys_tochrg, mltply_dys_by_adults, mltply_dys_by_chldrn,
inv.get_invitm_name(inv_item_id) itm_nm, inv.get_invitm_name(no_shw_inv_itm_id) no_show_itm_nm,
scm.get_item_unit_price_ls_tx(inv_item_id,1) itm_price, scm.get_item_unit_price_ls_tx(inv_item_id,1) no_show_itm_price
FROM hotl.service_types a WHERE service_type_id ='" . $serv_type_hdrID . "'";

    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_InvItemNm($itmID) {
    $strSql = "SELECT item_desc || ' (' || item_code || ')' " .
            "FROM inv.inv_itm_list a " .
            "WHERE item_id =" . $itmID . "";
    $result = executeSQLNoParams($strSql);
    if (loc_db_num_rows($result) > 0) {
        $row = loc_db_fetch_array($result);
        return $row[0];
    }
    return "";
}

function get_InvItemPriceLsTx($itmID) {
    $strSql = "SELECT orgnl_selling_price " .
            "FROM inv.inv_itm_list a " .
            "WHERE item_id =" . $itmID . "";
    $result = executeSQLNoParams($strSql);
    if (loc_db_num_rows($result) > 0) {
        $row = loc_db_fetch_array($result);
        return (float) $row[0];
    }
    return 0.00;
}

function get_InvItemPrice($itmID) {
    $strSql = "SELECT selling_price " .
            "FROM inv.inv_itm_list a " .
            "WHERE item_id =" . $itmID . "";
    $result = executeSQLNoParams($strSql);
    if (loc_db_num_rows($result) > 0) {
        $row = loc_db_fetch_array($result);
        return (float) $row[0];
    }
    return 0.00;
}

function get_InvItemTaxID($itmID) {
    $strSql = "SELECT tax_code_id " .
            "FROM inv.inv_itm_list a " .
            "WHERE item_id =" . $itmID . "";
    $result = executeSQLNoParams($strSql);
    if (loc_db_num_rows($result) > 0) {
        $row = loc_db_fetch_array($result);
        return (float) $row[0];
    }
    return -1;
}

function get_room_prices($serv_type_hdrID) {
    $whereClause = "";
    $strSql = "SELECT special_price_id, 
to_char(to_timestamp(a.start_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS'), 
to_char(to_timestamp(a.end_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS'),
price_less_tx, is_enabled, selling_price
  FROM hotl.service_type_prices a " .
            "WHERE service_type_id = " . $serv_type_hdrID . $whereClause . " ORDER BY start_date";
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_rooms($serv_type_hdrID, $offset, $limit_size, $searchIn, $searchWord) {
    $whereClause = " and (a.room_name ilike '" . loc_db_escape_string($searchWord) . "')";
    $strSql = "SELECT room_id, room_name, room_description, is_enabled, 
   CASE WHEN crnt_no_occpnts = mx_no_occpnts AND crnt_no_occpnts>0 THEN 'FULLY ISSUED OUT' 
        WHEN crnt_no_occpnts < mx_no_occpnts AND crnt_no_occpnts>0 THEN 'PARTIALLY ISSUED OUT' 
        WHEN crnt_no_occpnts > mx_no_occpnts THEN 'OVERLOADED' 
        ELSE 'AVAILABLE' END status, mx_no_occpnts, crnt_no_occpnts, needs_hse_keeping, expected_duration, lnkd_asset_id,
        (select b.asset_code_name from accb.accb_fa_assets_rgstr b where b.asset_id = a.lnkd_asset_id) asset_num " .
            "FROM hotl.rooms a " .
            "WHERE service_type_id = " . $serv_type_hdrID . $whereClause .
            " ORDER BY room_name LIMIT " . $limit_size .
            " OFFSET " . (abs($offset * $limit_size));
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_ttl_rooms($serv_type_hdrID, $searchIn, $searchWord) {
    $whereClause = " and (a.room_name ilike '" . loc_db_escape_string($searchWord) . "')";
    $strSql = "SELECT count(1) " .
            "FROM hotl.rooms a " .
            "WHERE service_type_id =" . $serv_type_hdrID . $whereClause . "";
    $result = executeSQLNoParams($strSql);
    if (loc_db_num_rows($result) > 0) {
        $row = loc_db_fetch_array($result);
        return (float) $row[0];
    }
    return 0;
}

function deleteSrvsTyp($hdrID, $srvsNm) {
    if (isSrvsTypInUse($hdrID) === true) {
        $dsply = "No Record Deleted<br/>Cannot delete a Facility Type used in Transactions!";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
    $delSQL = "DELETE FROM hotl.rooms WHERE service_type_id = " . $hdrID;
    $affctd1 = execUpdtInsSQL($delSQL, "Name:" . $srvsNm);

    $delSQL = "DELETE FROM hotl.service_types WHERE service_type_id = " . $hdrID;
    $affctd2 = execUpdtInsSQL($delSQL, "Name:" . $srvsNm);

    if ($affctd2 > 0) {
        $dsply = "";
        $dsply .= "<br/>Successfully Executed the ff-";
        $dsply .= "<br/>Deleted $affctd1 Facility(ies)!";
        $dsply .= "<br/>Deleted $affctd2 Facility Type(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Deleted";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function deleteSrvsTypLn($Lnid, $critrNm) {
    if (isRoomInUse($Lnid) === true) {
        $dsply = "No Record Deleted<br/>Cannot delete a Facility used in Transactions!";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
    $delSQL = "DELETE FROM hotl.rooms WHERE room_id = " . $Lnid;
    $affctd2 = execUpdtInsSQL($delSQL, "Room Name = " . $critrNm);

    if ($affctd2 > 0) {
        $dsply = "";
        $dsply .= "<br/>Successfully Executed the ff-";
        $dsply .= "<br/>Deleted $affctd2 Facility(ies)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Deleted";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function deletePriceLn($Lnid, $critrNm) {
    $delSQL = "DELETE FROM hotl.service_type_prices WHERE special_price_id = " . $Lnid;
    $affctd2 = execUpdtInsSQL($delSQL, "Price Det = " . $critrNm);
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

function isPriceDatesInUse($hdrid, $strtDte, $endDte) {
    if ($strtDte != "") {
        $strtDte = cnvrtDMYTmToYMDTm($strtDte);
    }
    if ($endDte != "") {
        $endDte = cnvrtDMYTmToYMDTm($endDte);
    }
    $strSql = "SELECT a.special_price_id " .
            "FROM hotl.service_type_prices a " .
            "WHERE(a.service_type_id = " . $hdrid .
            " and (to_timestamp('" . $strtDte . "','YYYY-MM-DD HH24:MI:SS') between 
to_timestamp(a.start_date,'YYYY-MM-DD HH24:MI:SS') 
AND to_timestamp(a.end_date,'YYYY-MM-DD HH24:MI:SS') or to_timestamp('" . $endDte .
            "','YYYY-MM-DD HH24:MI:SS') between to_timestamp(a.start_date,'YYYY-MM-DD HH24:MI:SS') 
AND to_timestamp(a.end_date,'YYYY-MM-DD HH24:MI:SS')))";

    $result = executeSQLNoParams($strSql);
    if (loc_db_num_rows($result) > 0) {
        $row = loc_db_fetch_array($result);
        return (float) $row[0];
    }
    return -1;
}

/* function getTrnsDatePrice($hdrid, $trnsDte, $calcMthd) {
  if (strlen($trnsDte) > 20) {
  $trnsDte = substr($trnsDte, 0, 20);
  }
  if ($trnsDte != "") {
  $trnsDte = cnvrtDMYTmToYMDTm($trnsDte);
  } else {
  $trnsDte = getDB_Date_time();
  }
  $trnsDte1 = substr($trnsDte, 0, 10);
  $whrcls = " and (to_timestamp('" . $trnsDte . "','YYYY-MM-DD HH24:MI:SS') between
  to_timestamp(a.start_date,'YYYY-MM-DD HH24:MI:SS')
  AND to_timestamp(a.end_date,'YYYY-MM-DD HH24:MI:SS'))";
  if ($calcMthd == "Hours") {
  $whrcls = " and (to_timestamp('" . $trnsDte . "','YYYY-MM-DD HH24:MI:SS') between to_timestamp('" . $trnsDte1 . "' || substring(a.start_date, 11), 'YYYY-MM-DD HH24:MI:SS') "
  . "AND to_timestamp('" . $trnsDte1 . "' || substring(a.end_date,11),'YYYY-MM-DD HH24:MI:SS'))";
  }
  $strSql = "SELECT a.selling_price " .
  "FROM hotl.service_type_prices a " .
  "WHERE(a.service_type_id = " . $hdrid .
  $whrcls . ")";

  $result = executeSQLNoParams($strSql);
  if (loc_db_num_rows($result) > 0) {
  $row = loc_db_fetch_array($result);
  return (float) $row[0];
  }
  return -1;
  } */

function getTrnsDateOrgnlPrice($hdrid, $trnsDte) {

    if (strlen($trnsDte) > 20) {
        $trnsDte = substr($trnsDte, 0, 20);
    }
    if ($trnsDte != "") {
        $trnsDte = cnvrtDMYTmToYMDTm($trnsDte);
    } else {
        $trnsDte = getDB_Date_time();
    }
    $strSql = "SELECT a.price_less_tx " .
            "FROM hotl.service_type_prices a " .
            "WHERE(a.service_type_id = " . $hdrid .
            " and (to_timestamp('" . $trnsDte . "','YYYY-MM-DD HH24:MI:SS') between 
to_timestamp(a.start_date,'YYYY-MM-DD HH24:MI:SS') 
AND to_timestamp(a.end_date,'YYYY-MM-DD HH24:MI:SS')))";

    $result = executeSQLNoParams($strSql);
    if (loc_db_num_rows($result) > 0) {
        $row = loc_db_fetch_array($result);
        return (float) $row[0];
    }
    return -1;
}

function isSrvsTypInUse($hdrid) {
    //string strSql = "SELECT a.application_det_id " +
    // "FROM hotl.application_det a " +
    // "WHERE(a.main_service_type_id = " + hdrid + ")";
    //DataSet dtst = Global.mnFrm.cmCde.selectDataNoParams(strSql);
    //if (dtst.Tables[0].Rows.Count > 0)
    //{
    //  return true;
    //}
    //string strSql = "SELECT a.check_in_det_id " +
    // "FROM hotl.check_ins_det a " +
    // "WHERE(a.sevice_type_id = " + hdrid + ")";
    //DataSet dtst = Global.mnFrm.cmCde.selectDataNoParams(strSql);
    //if (dtst.Tables[0].Rows.Count > 0)
    //{
    //  return true;
    //}

    $strSql = "SELECT a.check_in_id " .
            "FROM hotl.checkins_hdr a " .
            "WHERE(a.service_type_id = " . $hdrid . ")";
    $result = executeSQLNoParams($strSql);
    if (loc_db_num_rows($result) > 0) {
        return true;
    }
    return false;
}

function isRoomInUse($roomid) {
    $strSql = "SELECT a.check_in_id " .
            "FROM hotl.checkins_hdr a " .
            "WHERE(a.service_det_id = " . $roomid . ")";

    $result = executeSQLNoParams($strSql);
    if (loc_db_num_rows($result) > 0) {
        return true;
    }
    return false;
}

function getSrvsTypID($srvstypname, $orgid) {
    $strSql = "select service_type_id from hotl.service_types where lower(service_type_name) = '"
            . loc_db_escape_string($srvstypname) . "' and org_id = " . $orgid;
    $result = executeSQLNoParams($strSql);
    if (loc_db_num_rows($result) > 0) {
        $row = loc_db_fetch_array($result);
        return (float) $row[0];
    }
    return -1;
}

function getRoomID($rmname, $orgid) {
    $strSql = "select a.room_id from hotl.rooms a, hotl.service_types b where lower(a.room_name) = '"
            . loc_db_escape_string($rmname) . "' and a.service_type_id=b.service_type_id and b.org_id = " . $orgid;
    $result = executeSQLNoParams($strSql);
    if (loc_db_num_rows($result) > 0) {
        $row = loc_db_fetch_array($result);
        return (float) $row[0];
    }
    return -1;
}

function getSrvsTypName($srvstypid) {
    $strSql = "select service_type_name from hotl.service_types where service_type_id = "
            . $srvstypid;
    $result = executeSQLNoParams($strSql);
    if (loc_db_num_rows($result) > 0) {
        $row = loc_db_fetch_array($result);
        return $row[0];
    }
    return "";
}

function getRoomName($roomid) {
    $strSql = "select a.room_name from hotl.rooms a where a.room_id= "
            . $roomid;
    $result = executeSQLNoParams($strSql);
    if (loc_db_num_rows($result) > 0) {
        $row = loc_db_fetch_array($result);
        return  $row[0];
    }
    return "";
}

function getPriceID($strtDate, $endDte, $srvsTypID) {
    if ($strtDate != "") {
        $strtDate = cnvrtDMYTmToYMDTm($strtDate);
    }
    if ($endDte != "") {
        $endDte = cnvrtDMYTmToYMDTm($endDte);
    }
    $strSql = "select a.special_price_id from hotl.service_type_prices a where start_date = '" .
            $strtDate . "' and end_date = '" .
            $endDte . "' and a.service_type_id = " . $srvsTypID;

    $result = executeSQLNoParams($strSql);
    if (loc_db_num_rows($result) > 0) {
        $row = loc_db_fetch_array($result);
        return (float) $row[0];
    }
    return -1;
}

function createSrvsTyp($orgid, $srvsTypname, $srvsTypdesc, $itmId, $isEnbld, $fcltyType
        , $noshwItmID, $cancelDys, $pnltyDays, $mltplyAdlts, $mltplyChdn) {
    global $usrID;
    $insSQL = "INSERT INTO hotl.service_types(
            service_type_name, description, is_enabled, 
            inv_item_id, org_id, created_by, creation_date, last_update_by, 
            last_update_date, type_of_facility, 
no_shw_inv_itm_id, cancelltn_days_fr_pnlty, pnlty_num_dys_tochrg,
mltply_dys_by_adults, mltply_dys_by_chldrn) " .
            "VALUES ('" . loc_db_escape_string($srvsTypname) .
            "', '" . loc_db_escape_string($srvsTypdesc) .
            "', '" . cnvrtBoolToBitStr($isEnbld) .
            "', " . $itmId .
            ", " . $orgid . ", " . $usrID . ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), " . $usrID .
            ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), '" . loc_db_escape_string($fcltyType) .
            "', " . $noshwItmID . ", " . $cancelDys . ", " . $pnltyDays . ", '" .
            cnvrtBoolToBitStr($mltplyAdlts) .
            "', '" . cnvrtBoolToBitStr($mltplyChdn) .
            "')";
    return execUpdtInsSQL($insSQL);
}

function updateSrvsTyp($srvcTypID, $srvsTypname, $srvsTypdesc, $itmId, $isEnbld, $fcltyType
        , $noshwItmID, $cancelDys, $pnltyDays, $mltplyAdlts, $mltplyChdn) {
    global $usrID;
    $updtSQL = "UPDATE hotl.service_types SET " .
            "service_type_name='" . loc_db_escape_string($srvsTypname) .
            "', description='" . loc_db_escape_string($srvsTypdesc) .
            "', inv_item_id=" . $itmId .
            ", last_update_by=" . $usrID . ", " .
            "last_update_date=to_char(now(),'YYYY-MM-DD HH24:MI:SS'), is_enabled='" .
            cnvrtBoolToBitStr($isEnbld) .
            "', type_of_facility = '" . loc_db_escape_string($fcltyType) .
            "',no_shw_inv_itm_id =" . $noshwItmID .
            " , cancelltn_days_fr_pnlty=" . $cancelDys .
            ", pnlty_num_dys_tochrg=" . $pnltyDays .
            ", mltply_dys_by_adults='" . cnvrtBoolToBitStr($mltplyAdlts) .
            "', mltply_dys_by_chldrn='" . cnvrtBoolToBitStr($mltplyChdn) .
            "' WHERE (service_type_id =" . $srvcTypID . ")";
    return execUpdtInsSQL($updtSQL);
}

function createSpecialPrice($hdrID, $strtDate, $endDate, $priceLsTx, $isEnbld, $txID) {
    global $usrID;
    if ($strtDate != "") {
        $strtDate = cnvrtDMYTmToYMDTm($strtDate);
    }
    if ($endDate != "") {
        $endDate = cnvrtDMYTmToYMDTm($endDate);
    }
    $insSQL = "INSERT INTO hotl.service_type_prices(
            service_type_id, start_date, end_date, price_less_tx, is_enabled, 
            created_by, creation_date, last_update_by, last_update_date, selling_price) " .
            "VALUES (" . $hdrID .
            ", '" . $strtDate .
            "', '" . $endDate .
            "', " . $priceLsTx .
            ", '" . cnvrtBoolToBitStr($isEnbld) .
            "', " . $usrID . ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), " .
            $usrID . ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), scm.get_sllng_price_inctax(" . $txID . ", " . $priceLsTx . "))";
    return execUpdtInsSQL($insSQL);
}

function updateSpecialPrice($priceID, $strtDate, $endDate, $priceLsTx, $isEnbld, $txID) {
    global $usrID;
    if ($strtDate != "") {
        $strtDate = cnvrtDMYTmToYMDTm($strtDate);
    }
    if ($endDate != "") {
        $endDate = cnvrtDMYTmToYMDTm($endDate);
    }
    $updtSQL = "UPDATE hotl.service_type_prices
   SET start_date='" . $strtDate .
            "', end_date='" . $endDate .
            "', price_less_tx=" . $priceLsTx .
            ", is_enabled='" . cnvrtBoolToBitStr($isEnbld) .
            "', last_update_by=" . $usrID .
            ", last_update_date=to_char(now(),'YYYY-MM-DD HH24:MI:SS')"
            . ", selling_price=scm.get_sllng_price_inctax(" . $txID . ", " . $priceLsTx . ") WHERE special_price_id=" . $priceID;
    return execUpdtInsSQL($updtSQL);
}

function createRoom($srvcTypID, $roomNm, $roomDesc, $isEnbld, $mxCstmrs, $isdirty, $mxHrs, $asset_id) {
    global $usrID;
    $insSQL = "INSERT INTO hotl.rooms(
            room_name, room_description, is_enabled, created_by, 
            creation_date, last_update_by, last_update_date, service_type_id, 
            mx_no_occpnts,needs_hse_keeping,expected_duration,lnkd_asset_id) " .
            "VALUES ('" . loc_db_escape_string($roomNm) .
            "', '" . loc_db_escape_string($roomDesc) .
            "', '" . cnvrtBoolToBitStr($isEnbld) .
            "', " . $usrID . ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), " . $usrID .
            ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), " . $srvcTypID . ", " . $mxCstmrs .
            ", '" . cnvrtBoolToBitStr($isdirty) .
            "', " . $mxHrs . "," . $asset_id . ")";
    return execUpdtInsSQL($insSQL);
}

function updateRoom($roomID, $roomNm, $roomDesc, $isEnbld, $mxCstmrs, $isdirty, $mxHrs, $asset_id) {
    global $usrID;
    $insSQL = "UPDATE hotl.rooms SET 
             room_name='" . loc_db_escape_string($roomNm) .
            "', room_description='" . loc_db_escape_string($roomDesc) .
            "', mx_no_occpnts=" . $mxCstmrs . ", is_enabled='" . cnvrtBoolToBitStr($isEnbld) .
            "', last_update_by=" . $usrID .
            ", last_update_date=to_char(now(),'YYYY-MM-DD HH24:MI:SS'), needs_hse_keeping='" . cnvrtBoolToBitStr($isdirty) .
            "',expected_duration=" . $mxHrs . ", lnkd_asset_id = " . $asset_id .
            " WHERE room_id=" . $roomID . " ";
    return execUpdtInsSQL($insSQL);
}

function updateRoomCleanStatus($roomID, $isdirty) {
    global $usrID;
    $insSQL = "UPDATE hotl.rooms SET 
             last_update_by=" . $usrID .
            ", last_update_date=to_char(now(),'YYYY-MM-DD HH24:MI:SS'),needs_hse_keeping='" . cnvrtBoolToBitStr($isdirty) .
            "' WHERE room_id=" . $roomID . " ";
    return execUpdtInsSQL($insSQL);
}

function updateRoomBlckdStatus($roomID, $isblckd) {
    global $usrID;
    $insSQL = "UPDATE hotl.rooms SET 
             last_update_by=" . $usrID .
            ", last_update_date=to_char(now(),'YYYY-MM-DD HH24:MI:SS'), is_enabled='" . cnvrtBoolToBitStr($isblckd) .
            "' WHERE room_id=" . $roomID . " ";
    return execUpdtInsSQL($insSQL);
}

function getNewRsltLnID() {
    $strSql = "select nextval('hotl.gym_actvty_progress_progress_id_seq')";
    $result = executeSQLNoParams($strSql);
    if (loc_db_num_rows($result) > 0) {
        $row = loc_db_fetch_array($result);
        return (float) $row[0];
    }
    return -1;
}

function get_One_ActvtyRslts($checkInID) {
    $strSql = "SELECT * FROM (SELECT b.room_id, b.room_name, b.room_description, b.expected_duration, 
CASE WHEN c.activity_id IS NOT NULL THEN '1' ELSE b.is_enabled END is_enabled, 
to_char(to_timestamp(COALESCE(c.start_date, a.start_date),'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') start_date, 
to_char(to_timestamp(COALESCE(c.end_date,a.end_date),'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') end_date, 
COALESCE(c.hours_done,0) hours_done, 
COALESCE(c.is_complete,'0') is_complete, 
COALESCE(c.remarks,'') remarks,
COALESCE(c.progress_id,-1) progress_id
  FROM hotl.checkins_hdr a 
  LEFT OUTER JOIN hotl.rooms b ON (a.service_type_id=b.service_type_id ) 
  LEFT OUTER JOIN hotl.gym_actvty_progress c ON (b.room_id=c.activity_id and c.check_in_id = " . $checkInID . ")
        WHERE((a.check_in_id = " . $checkInID . "))) tbl1 
        WHERE tbl1.is_enabled='1' ORDER BY 2, 3,1";

    $result = executeSQLNoParams($strSql);
    return $result;
}

?>
