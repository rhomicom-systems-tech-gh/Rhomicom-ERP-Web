<?php
$menuItems = array("Branches & Vaults", "Transactions", "Standard Reports", "VMS Administration");
$menuImages = array("safe-icon.png", "bulkPay.png", "report-icon-png.png", "settings.png");
$trnsTypes = array(
    "Transits (Specie Movement)", "Teller/Cashier Transfers", "To Exam",
    /* 3 */ "From Exam", "Deposits", "Withdrawals",
    /* 6 */ "Currency Importation", "Currency Destruction", "Currency Sale", "Currency Purchase",
    /* 10 */ "Miscellaneous Adjustments", "Direct Cage/Shelve Transaction", "Vault/GL Account Transfers", "GL/Vault Account Transfers"
);
$trnsTypeABRV = array("SMV", "TLR", "TXM", "FXM", "DEP", "WTH", "IMP", "DES", "FXS", "FXP", "MSADJ", "DCT", "VGLTX", "GLVTX");
$trnsTypeDfltItmState = array(
    "Issuable", "Issuable", "Unexamined", "Unexamined", "Unexamined", "Issuable",
    "Mint", "Examined-Unfit", "Issuable", "Issuable", "Issuable", "Issuable", "Issuable", "Issuable"
);
$vmsCstmrClsfctn = "VMS BOG Banks";

$mdlNm = "Vault Management";
$ModuleName = $mdlNm;
$dfltPrvldgs = array(
    "View Vault Management",
    /* 1 */ "View Branches & Vaults", "View Transactions", "View Standard Reports",
    /* 4 */ "View VMS Administration", "View SQL", "View Record History",
    /* 7 */ "View Branch Setup", "View Vault Setup", "View Cage Setup", "View Item List Setup",
    /* 11 */ "View Authorization Limits Setup", "View Customers/Suppliers Setup",
    /* 13 */ "View Transfer Transactions", "View Direct Customer Deposits", "View Direct Customer Withdrawals",
    /* 16 */ "View Currency Importation", "View Currency Destruction",
    /* 18 */ "View Transit Transfers", "View Teller Transfers", "View Exam Transfers", "View From Exam",
    /* 22 */ "Add Branch/Agency", "Edit Branch/Agency", "Delete Branch/Agency",
    /* 25 */ "Add Vault", "Edit Vault", "Delete Vault",
    /* 28 */ "Add Cage/Shelve", "Edit Cage/Shelve", "Delete Cage/Shelve",
    /* 31 */ "Add Items", "Edit Items", "Delete Items",
    /* 34 */ "Add Authorization Limit", "Edit Authorization Limit", "Delete Authorization Limit",
    /* 37 */ "Add Transactions", "Edit Transactions", "Delete Transactions", "Authorize Transactions",
    /* 41 */ "Search All Transactions", "See non-related Transactions",
    /* 43 */ "View Currency Sale", "View Currency Purchase", "View Miscellaneous Adjustments",
    /* 46 */ "Can Send to GL", "Can Add Correction Transactions", "Can Void Correction Transactions",
    /* 49 */ "Add Customer/Supplier", "Edit Customer/Supplier", "Delete Customer/Supplier",
    /* 52 */ "View Direct Cage/Shelve Transaction",
    /* 53 */ "View Vault/GL Account Transfers", "See Other Branch Transactions"
);

$srcMenu = isset($_POST['srcMenu']) ? cleanInputData($_POST['srcMenu']) : "VMS";
$subSrcMenu = isset($_POST['subSrcMenu']) ? cleanInputData($_POST['subSrcMenu']) : "VMS";
$canview = test_prmssns($dfltPrvldgs[0], $ModuleName);
$canSeeOthrsTrns = test_prmssns($dfltPrvldgs[41], $ModuleName);
$vwtyp = "0";
$qstr = "";
$dsply = "";
$actyp = "";
$srchFor = "";
$srchIn = "Name";
$PKeyID = -1;
$sortBy = "ID ASC";
$usrID = $_SESSION['USRID'];
$prsnid = $_SESSION['PRSN_ID'];
$orgID = $_SESSION['ORG_ID'];
$uName = $_SESSION['UNAME'];
$fnccurid = getOrgFuncCurID($orgID);
$fnccurnm = getPssblValNm($fnccurid);

$gnrlTrnsDteDMYHMS = getStartOfDayDMYHMS();
$gnrlTrnsDteYMDHMS = cnvrtDMYTmToYMDTm($gnrlTrnsDteDMYHMS);
$gnrlTrnsDteYMD = substr($gnrlTrnsDteYMDHMS, 0, 10);

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
						<span style=\"text-decoration:none;\">All Modules&nbsp;</span>
                                                <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
					</li>";
//var_dump($_POST);
if ($lgn_num > 0 && $canview === true) {
    if ($pgNo == 0) {
        $brnchLocID = getLatestSiteID($prsnid);
        $brnchLoc = getGnrlRecNm("org.org_sites_locations", "location_id", "REPLACE(location_code_name || '.' || site_desc, '.' || location_code_name,'')", $brnchLocID);
        $acsCntrlGrpID = getAcntGroup($prsnid, "Access Control Group");
        $acsCntrlGrpNm = getGnrlRecNm("org.org_divs_groups", "div_id", "div_code_name", $acsCntrlGrpID);
        if ($srcMenu == "Banking") {
            $cntent .= "<li onclick=\"openATab('#allmodules', 'grp=17&typ=1');\">
                                <span style=\"text-decoration:none;\">Banking & Microfinance Menu</span>
                        </li>
                        <li onclick=\"openATab('#allmodules', 'grp=$group&typ=$type&srcMenu=$srcMenu');\">
                                <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                <span style=\"text-decoration:none;\">VMS Menu</span>
                        </li>
                       </ul>
                     </div>";
        } else {
            $cntent .= "<li onclick=\"openATab('#allmodules', 'grp=$group&typ=$type&srcMenu=$srcMenu');\">
                                <span style=\"text-decoration:none;\">VMS Menu</span>
                        </li>
                       </ul>
                     </div>";
        }
        $cntent .= "<div style=\"font-family: Tahoma, Arial, sans-serif;font-size: 1.3em;
                    padding:10px 15px 15px 20px;border:1px solid #ccc;\">                    
                      <div style=\"padding:5px 30px 5px 5px;margin-bottom:2px;\">
                        <span style=\"font-family: georgia, times;font-size: 20px;font-style:italic;
                        font-weight:normal;\"><span style=\"font-family: georgia, times;font-size: 14px;font-style:normal;
                        font-weight:bold;color:black\">Transactions Date:</span>
                            <span style=\"font-family: tahoma;font-size: 14px;font-style:normal;
                        font-weight:bold;color:blue;\">$gnrlTrnsDteDMYHMS</span>
                        <span style=\"font-family: georgia, times;font-size: 20px;font-style:italic;
                        font-weight:normal;\"><span style=\"font-family: georgia, times;font-size: 14px;font-style:normal;
                        font-weight:bold;color:black\">Branch:</span>
                            <span style=\"font-family: tahoma;font-size: 14px;font-style:normal;
                        font-weight:bold;color:blue;\">$brnchLoc</span>
                        <span style=\"font-family: georgia, times;font-size: 20px;font-style:italic;
                        font-weight:normal;\"><span style=\"font-family: georgia, times;font-size: 14px;font-style:normal;
                        font-weight:bold;color:black\">Default Accounts:</span>
                            <span style=\"font-family: tahoma;font-size: 14px;font-style:normal;
                        font-weight:bold;color:blue;\">$acsCntrlGrpNm</span>
                    </div>";
        $grpcntr = 0;
        if ($acsCntrlGrpID > 0 && $brnchLocID > 0) {
            for ($i = 0; $i < count($menuItems); $i++) {
                $No = $i + 1;
                if (test_prmssns($dfltPrvldgs[$i + 1], $mdlNm) == FALSE) {
                    continue;
                }
                if ($grpcntr == 0) {
                    $cntent .= "<div class=\"row\">";
                }

                if ($i == 2) {
                    $cntent .= "<div class=\"col-md-3 colmd3special2\">
        <button type=\"button\" class=\"btn btn-default btn-lg btn-block\" style=\"min-height:175px;height:173px;margin-bottom:5px;\" onclick=\"openATab('#allmodules', 'grp=$group&typ=$type&pg=$No&isStndrdRprtsView=1&srcMenu=$srcMenu');\">
            <img src=\"cmn_images/$menuImages[$i]\" style=\"margin:5px auto; height:48px; width:auto; position: relative; vertical-align: middle;float:none;\">
            <br/>
            <span class=\"wordwrap3\">" . ($menuItems[$i]) . "</span>
            <br/>&nbsp;
        </button>
            </div>";
                } else {
                    $cntent .= "<div class=\"col-md-3 colmd3special2\">
        <button type=\"button\" class=\"btn btn-default btn-lg btn-block\" style=\"min-height:175px;height:173px;margin-bottom:5px;\" onclick=\"openATab('#allmodules', 'grp=$group&typ=$type&pg=$No&vtyp=0&srcMenu=$srcMenu');\">
            <img src=\"cmn_images/$menuImages[$i]\" style=\"margin:5px auto; height:78px; width:auto; position: relative; vertical-align: middle;float:none;\">
            <br/>
            <span class=\"wordwrap3\">" . ($menuItems[$i]) . "</span>
            <br/>&nbsp;
        </button>
            </div>";
                }

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
        } else {
            $cntent .= "<div style=\"text-align: center;\"><span style=\"font-size: 15px; font-weight: bold; color: red !important;\">Sorry! Your Branch and/or Accounts Group has not been Setup!</br> Contact I.T for Assistance. Thanks!</span></div>";
        }
        echo $cntent;
    } else if ($pgNo == 1) {
        //Branches & Vaults  
        require 'vms_brnchs.php';
    } else if ($pgNo == 2) {
        //Transactions
        require "vms_trns.php";
    } else if ($pgNo == 3) {
        //Standard Reports
        require "vms_rpts.php";
    } else if ($pgNo == 4) {
        //Setups & Configurations
        require "vms_stps.php";
    } else {
        restricted();
    }
} else {
    restricted();
}

function get_SitesLocs($pkID, $prsnID, $searchWord, $searchIn, $offset, $limit_size, $brnchType = "")
{
    global $orgID;
    global $gnrlTrnsDteYMDHMS;
    $balDte = substr($gnrlTrnsDteYMDHMS, 0, 10);


    $whereCls = "";
    $extrWhere = "";
    if ($pkID > 0) {
        $extrWhere .= " and (org.does_prsn_hv_crtria_id(" . $prsnID . ", a.allwd_group_value::bigint,a.allwd_group_type)>0) and (a.org_id = $pkID)";
    }
    if ($searchIn == "Site Name") {
        $whereCls = " and (a.location_code_name ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Site Description") {
        $whereCls = " and (a.site_desc ilike '" . loc_db_escape_string($searchWord) . "')";
    }
    if ($brnchType != "") {
        $whereCls .= " and (gst.get_pssbl_val(a.site_type_id) ilike '" . loc_db_escape_string($brnchType) . "')";
    }
    $strSql = "SELECT a.location_id mt, 
        REPLACE(a.location_code_name || '.' || a.site_desc, '.' || a.location_code_name,'') \"location_code/name\", 
        a.site_desc \"description/comments\", 
        CASE WHEN a.is_enabled='1' THEN 'Yes' ELSE 'No' END \"is_enabled?\",
        vms.get_ltst_stock_bals2(a.location_id,'" . $balDte . "') ttlLocAmnt,
        a.site_type_id,
        gst.get_pssbl_val(a.site_type_id) sitetype,
        a.allwd_group_type,
        org.get_criteria_name(a.allwd_group_value::bigint,a.allwd_group_type) group_name
        FROM org.org_sites_locations a
        WHERE ((a.org_id = $orgID)" . $extrWhere . $whereCls . ")
    ORDER BY a.location_code_name LIMIT " . $limit_size . " OFFSET " . abs($offset * $limit_size);
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_SitesLocsTtl($pkID, $prsnID, $searchWord, $searchIn, $brnchType = "")
{
    global $orgID;
    $whereCls = "";
    $extrWhere = "";
    if ($pkID > 0) {
        $extrWhere .= " and (org.does_prsn_hv_crtria_id(" . $prsnID . ", a.allwd_group_value::bigint, a.allwd_group_type)>0) and (a.org_id = $pkID)";
    }
    if ($searchIn == "Site Name") {
        $whereCls = " and (a.location_code_name ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Site Description") {
        $whereCls = " and (a.site_desc ilike '" . loc_db_escape_string($searchWord) . "')";
    }
    if ($brnchType != "") {
        $whereCls .= " and (gst.get_pssbl_val(a.site_type_id) ilike '" . loc_db_escape_string($brnchType) . "')";
    }
    $strSql = "SELECT count(1)
    FROM org.org_sites_locations a
    WHERE ((a.org_id = $orgID)" . $extrWhere . $whereCls . ")";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return 0;
}

function get_OneSiteLocDet($siteID)
{
    $strSql = "SELECT a.location_id mt, 
        a.location_code_name, 
        a.site_desc \"description/comments\", 
        CASE WHEN a.is_enabled='1' THEN 'Yes' ELSE 'No' END \"is_enabled?\",
        a.site_type_id,
        gst.get_pssbl_val(a.site_type_id) sitetype,
        a.allwd_group_type,
        org.get_criteria_name(a.allwd_group_value::bigint,a.allwd_group_type) group_name,
        a.allwd_group_value
        FROM org.org_sites_locations a
         WHERE (a.location_id=" . $siteID . ")";
    $result = executeSQLNoParams($strSql);
    return $result;
}

function getSiteLocID($siteLocNm)
{
    $sqlStr = "select location_id from org.org_sites_locations where lower(location_code_name) = '" .
        loc_db_escape_string(strtolower($siteLocNm)) . "'";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -1;
}

function createSiteLoc($siteNm, $siteDesc, $siteTypeID, $orgid, $isenbled, $allwdGrpType, $allwdGrpVal)
{
    global $usrID;
    $dateStr = getDB_Date_time();
    $insSQL = "INSERT INTO org.org_sites_locations(
            location_code_name, org_id, is_enabled, created_by, 
            creation_date, last_update_by, last_update_date, site_desc, allwd_group_type, 
            allwd_group_value, site_type_id) " .
        "VALUES ('" . loc_db_escape_string($siteNm) .
        "', " . loc_db_escape_string($orgid) .
        ", '" . loc_db_escape_string($isenbled) .
        "', " . loc_db_escape_string($usrID) .
        ", '" . loc_db_escape_string($dateStr) .
        "', " . loc_db_escape_string($usrID) .
        ", '" . loc_db_escape_string($dateStr) .
        "', '" . loc_db_escape_string($siteDesc) .
        "', '" . loc_db_escape_string($allwdGrpType) .
        "', '" . loc_db_escape_string($allwdGrpVal) .
        "', " . loc_db_escape_string($siteTypeID) .
        ")";
    return execUpdtInsSQL($insSQL);
}

function updateSiteLoc($siteid, $siteNm, $siteDesc, $siteTypeID, $orgid, $isenbled, $allwdGrpType, $allwdGrpVal)
{
    global $usrID;
    $dateStr = getDB_Date_time();
    $insSQL = "UPDATE org.org_sites_locations SET 
                location_code_name='" . loc_db_escape_string($siteNm) .
        "', site_desc='" . loc_db_escape_string($siteDesc) .
        "', site_type_id=" . loc_db_escape_string($siteTypeID) .
        ", last_update_by=" . loc_db_escape_string($usrID) .
        ", last_update_date='" . loc_db_escape_string($dateStr) .
        "', org_id=" . loc_db_escape_string($orgid) .
        ", is_enabled='" . loc_db_escape_string($isenbled) .
        "', allwd_group_type='" . loc_db_escape_string($allwdGrpType) .
        "', allwd_group_value='" . loc_db_escape_string($allwdGrpVal) .
        "' WHERE location_id = " . $siteid;
    return execUpdtInsSQL($insSQL);
}

function deleteSiteLoc($pkeyID, $extrInfo = "")
{
    $selSQL = "Select count(1) from mcf.mcf_cust_account_transactions WHERE branch_id = " . $pkeyID;
    $result = executeSQLNoParams($selSQL);
    $trnsCnt = 0;
    while ($row = loc_db_fetch_array($result)) {
        $trnsCnt = (float) $row[0];
    }
    if ($trnsCnt > 0) {
        $dsply = "No Record Deleted<br/>Cannot Delete Sites used in Transactions!";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
    $selSQL1 = "Select count(1) from vms.vms_transactions_hdr WHERE trns_loc_site_id = " . $pkeyID;
    $result1 = executeSQLNoParams($selSQL1);
    $trnsCnt1 = 0;
    while ($row = loc_db_fetch_array($result1)) {
        $trnsCnt1 = (float) $row[0];
    }
    if ($trnsCnt1 > 0) {
        $dsply = "No Record Deleted<br/>Cannot Delete Sites used in Transactions!";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
    $selSQL3 = "Select count(1) from vms.vms_stock_daily_bals "
        . "WHERE store_vault_id IN (Select y.subinv_id from inv.inv_itm_subinventories y where y.lnkd_site_id= " . $pkeyID . ")";
    $result3 = executeSQLNoParams($selSQL3);
    $trnsCnt3 = 0;
    while ($row = loc_db_fetch_array($result3)) {
        $trnsCnt3 = (float) $row[0];
    }
    if ($trnsCnt3 > 0) {
        $dsply = "No Record Deleted<br/>Cannot Delete Vaults used in Transactions!";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
    $affctd1 = 0;
    if (($trnsCnt + $trnsCnt1 + $trnsCnt3) <= 0) {
        $insSQL = "DELETE FROM org.org_sites_locations WHERE location_id = " . $pkeyID;
        $affctd1 = execUpdtInsSQL($insSQL, "Site Name:" . $extrInfo);
    }
    if ($affctd1 > 0) {
        $dsply = "Successfully Deleted the ff Records-";
        $dsply .= "<br/>$affctd1 Site(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function get_SitesVaults($pkID, $prsnID, $searchWord, $searchIn, $offset, $limit_size)
{
    global $orgID;
    global $fnccurid;
    global $gnrlTrnsDteYMD;
    $whereCls = "";
    $extrWhere = "";
    if ($pkID > 0) {
        $extrWhere .= " and (org.does_prsn_hv_crtria_id(" . $prsnID . ", a.allwd_group_value::bigint, a.allwd_group_type)>0) and (a.lnkd_site_id = $pkID)";
    }
    if ($searchIn == "Vault Name") {
        $whereCls = " and (a.subinv_name ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Vault Description") {
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
        vms.get_ltst_stock_bals1(a.subinv_id,-1,-1,'','" . $gnrlTrnsDteYMD . "') ttlVltAmnt,
        c.location_id, REPLACE(c.location_code_name || '.' || c.site_desc, '.' || c.location_code_name,'') location_code_name 
        FROM inv.inv_itm_subinventories a
        LEFT OUTER JOIN org.org_sites_locations c ON (c.location_id = a.lnkd_site_id)
        WHERE (a.org_id=" . $orgID . "" . $extrWhere . $whereCls . ") 
        ORDER BY a.subinv_name LIMIT " . $limit_size . " OFFSET " . abs($offset * $limit_size);
    //echo $strSql;
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_SitesVaultsTtl($pkID, $prsnID, $searchWord, $searchIn)
{
    global $orgID;
    $whereCls = "";
    $extrWhere = "";
    if ($pkID > 0) {
        $extrWhere .= " and (org.does_prsn_hv_crtria_id(" . $prsnID . ", a.allwd_group_value::bigint,a.allwd_group_type)>0) and (a.lnkd_site_id = $pkID)";
    }
    if ($searchIn == "Vault Name") {
        $whereCls = " and (a.subinv_name ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Vault Description") {
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

function get_OneVaultDet($subinvID)
{
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

function get_OneVaultCages($subinvID)
{
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

function get_OneVaultUsers($subinvID)
{
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

function getVaultID($vaultNm)
{
    $sqlStr = "select subinv_id from inv.inv_itm_subinventories where lower(subinv_name) = '" .
        loc_db_escape_string(strtolower($vaultNm)) . "'";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -1;
}

function createVault($vaultNm, $vaultDesc, $vaultAddrs, $isSalesAllwd, $mngrPrsnID, $orgid, $isenbled, $invAsstAcntID, $lnkdSiteID, $allwdGrpType, $allwdGrpVal)
{
    global $usrID;
    $dateStr = getDB_Date_time();
    $insSQL = "INSERT INTO inv.inv_itm_subinventories(
            subinv_name, subinv_desc, address, creation_date, 
            created_by, last_update_by, last_update_date, allow_sales, subinv_manager, 
            org_id, enabled_flag, inv_asset_acct_id, lnkd_site_id, allwd_group_type, 
            allwd_group_value) " .
        "VALUES ('" . loc_db_escape_string($vaultNm) .
        "', '" . loc_db_escape_string($vaultDesc) .
        "', '" . loc_db_escape_string($vaultAddrs) .
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

function updateVault($vaultid, $vaultNm, $vaultDesc, $vaultAddrs, $isSalesAllwd, $mngrPrsnID, $orgid, $isenbled, $invAsstAcntID, $lnkdSiteID, $allwdGrpType, $allwdGrpVal)
{
    global $usrID;
    $dateStr = getDB_Date_time();
    $insSQL = "UPDATE inv.inv_itm_subinventories SET 
                subinv_name='" . loc_db_escape_string($vaultNm) .
        "', subinv_desc='" . loc_db_escape_string($vaultDesc) .
        "', address='" . loc_db_escape_string($vaultAddrs) .
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
        "' WHERE subinv_id = " . $vaultid;
    return execUpdtInsSQL($insSQL);
}

function deleteVault($pkeyID, $extrInfo = "")
{
    $selSQL = "Select count(1) from mcf.mcf_account_trns_cash_analysis WHERE vault_id = " . $pkeyID;
    $result = executeSQLNoParams($selSQL);
    $trnsCnt = 0;
    while ($row = loc_db_fetch_array($result)) {
        $trnsCnt = (float) $row[0];
    }
    if ($trnsCnt > 0) {
        $dsply = "No Record Deleted<br/>Cannot Delete Vaults used in Transactions!";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
    $selSQL1 = "Select count(1) from vms.vms_transaction_lines WHERE src_store_vault_id = " . $pkeyID . " or dest_store_vault_id = " . $pkeyID;
    $result1 = executeSQLNoParams($selSQL1);
    $trnsCnt1 = 0;
    while ($row = loc_db_fetch_array($result1)) {
        $trnsCnt1 = (float) $row[0];
    }
    if ($trnsCnt1 > 0) {
        $dsply = "No Record Deleted<br/>Cannot Delete Vaults used in Transactions!";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
    $selSQL2 = "Select count(1) from vms.vms_transaction_pymnt WHERE src_store_vault_id = " . $pkeyID . " or dest_store_vault_id = " . $pkeyID;
    $result2 = executeSQLNoParams($selSQL2);
    $trnsCnt2 = 0;
    while ($row = loc_db_fetch_array($result2)) {
        $trnsCnt2 = (float) $row[0];
    }
    if ($trnsCnt2 > 0) {
        $dsply = "No Record Deleted<br/>Cannot Delete Vaults used in Transactions!";
        return "<p style=\"text-align:left;color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
    $selSQL3 = "Select count(1) from vms.vms_stock_daily_bals WHERE store_vault_id = " . $pkeyID;
    $result3 = executeSQLNoParams($selSQL3);
    $trnsCnt3 = 0;
    while ($row = loc_db_fetch_array($result3)) {
        $trnsCnt3 = (float) $row[0];
    }
    if ($trnsCnt3 > 0) {
        $dsply = "No Record Deleted<br/>Cannot Delete Vaults used in Transactions!";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
    $affctd1 = 0;
    $affctd2 = 0;
    $affctd3 = 0;
    if (($trnsCnt + $trnsCnt1 + $trnsCnt2 + $trnsCnt3) <= 0) {
        $insSQL = "DELETE FROM inv.inv_user_subinventories WHERE subinv_id = " . $pkeyID;
        $affctd2 = execUpdtInsSQL($insSQL, "Vault Name:" . $extrInfo);
        $insSQL = "DELETE FROM inv.inv_shelf WHERE store_id = " . $pkeyID;
        $affctd3 = execUpdtInsSQL($insSQL, "Vault Name:" . $extrInfo);
        $insSQL = "DELETE FROM inv.inv_itm_subinventories WHERE subinv_id = " . $pkeyID;
        $affctd1 = execUpdtInsSQL($insSQL, "Vault Name:" . $extrInfo);
    }
    if ($affctd1 > 0) {
        $dsply = "Successfully Deleted the ff Records-";
        $dsply .= "<br/>$affctd2 Vault User(s)!";
        $dsply .= "<br/>$affctd3 Vault Cage(s)!";
        $dsply .= "<br/>$affctd1 Vault(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function getVaultUsrLineID($user_id, $vltID)
{
    $sqlStr = "select line_id from inv.inv_user_subinventories where user_id = " .
        loc_db_escape_string($user_id) . " and subinv_id = " .
        loc_db_escape_string($vltID) . " ORDER BY line_id DESC LIMIT 1 OFFSET 0";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -1;
}

function createVaultUser($vltUsrID, $vaultID, $strtDte, $endDte)
{
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
        "VALUES (" . loc_db_escape_string($vltUsrID) .
        ", " . loc_db_escape_string($vaultID) .
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

function updateVaultUser($lineID, $vltUsrID, $vaultID, $strtDte, $endDte)
{
    global $usrID;
    $dateStr = getDB_Date_time();
    if ($strtDte != "") {
        $strtDte = cnvrtDMYTmToYMDTm($strtDte);
    }
    if ($endDte != "") {
        $endDte = cnvrtDMYTmToYMDTm($endDte);
    }
    $insSQL = "UPDATE inv.inv_user_subinventories
                SET user_id=" . loc_db_escape_string($vltUsrID) . ", 
                    subinv_id=" . loc_db_escape_string($vaultID) . ", 
                    start_date='" . loc_db_escape_string($strtDte) . "', 
                    end_date='" . loc_db_escape_string($endDte) . "', 
                    last_update_by=" . loc_db_escape_string($usrID) . ", 
                    last_update_date='" . loc_db_escape_string($dateStr) . "'
              WHERE line_id = " . $lineID;
    return execUpdtInsSQL($insSQL);
}

function deleteVaultUser($pkeyID, $extrInfo = "")
{
    $insSQL = "DELETE FROM inv.inv_user_subinventories WHERE subinv_id = " . $pkeyID;
    $affctd2 = execUpdtInsSQL($insSQL, "Vault Name:" . $extrInfo);
    if ($affctd2 > 0) {
        $dsply = "Successfully Deleted the ff Records-";
        $dsply .= "<br/>$affctd2 Vault User(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function get_VaultCages($pkID, $prsnID, $searchWord, $searchIn, $offset, $limit_size)
{
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
        WHERE (a.org_id=" . $orgID . " and b.lnkd_site_id>0" . $extrWhere . $whereCls . ") 
        ORDER BY a.shelve_name LIMIT " . $limit_size . " OFFSET " . abs($offset * $limit_size);
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_VaultCagesTtl($pkID, $prsnID, $searchWord, $searchIn)
{
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
         WHERE (a.org_id=" . $orgID . " and b.lnkd_site_id>0" . $extrWhere . $whereCls . ")";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return 0;
}

function get_OneCageDet($pkID)
{
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
    $result = executeSQLNoParams($strSql);
    return $result;
}

function getCageTillID($shlveNm, $vltid)
{
    $sqlStr = "select line_id from inv.inv_shelf where lower(shelve_name) = '" .
        loc_db_escape_string(strtolower($shlveNm)) .
        "' and store_id = " . loc_db_escape_string($vltid);
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -1;
}

function getCageMngrID($lineid)
{
    $sqlStr = "select cage_shelve_mngr_id from inv.inv_shelf where line_id = " . loc_db_escape_string($lineid);
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -1;
}

function createCageTill($orgid, $shelfID, $storeID, $shelfNm, $shelfDesc, $lnkdCstmrID, $allwdGrpType, $allwdGrpVal, $invAcntID, $cageMngrID, $dfltItmState, $mngrsWthdrwLmt, $mngrsDepLmt, $dfltType, $isenbled)
{
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

function updateCageTill($lineid, $shelfID, $storeID, $shelfNm, $shelfDesc, $lnkdCstmrID, $allwdGrpType, $allwdGrpVal, $invAcntID, $cageMngrID, $dfltItmState, $mngrsWthdrwLmt, $mngrsDepLmt, $dfltType, $isenbled)
{
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

function deleteCageTill($pkeyID, $extrInfo = "")
{
    $selSQL = "Select count(1) from mcf.mcf_account_trns_cash_analysis WHERE cage_shelve_id = " . $pkeyID;
    $result = executeSQLNoParams($selSQL);
    $trnsCnt = 0;
    while ($row = loc_db_fetch_array($result)) {
        $trnsCnt = (float) $row[0];
    }
    if ($trnsCnt > 0) {
        $dsply = "No Record Deleted<br/>Cannot Delete Vault Cages used in Transactions!";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
    $selSQL1 = "Select count(1) from vms.vms_transaction_lines WHERE src_cage_shelve_id = " . $pkeyID . " or dest_cage_shelve_id = " . $pkeyID;
    $result1 = executeSQLNoParams($selSQL1);
    $trnsCnt1 = 0;
    while ($row = loc_db_fetch_array($result1)) {
        $trnsCnt1 = (float) $row[0];
    }
    if ($trnsCnt1 > 0) {
        $dsply = "No Record Deleted<br/>Cannot Delete Vault Cages used in Transactions!";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
    $selSQL2 = "Select count(1) from vms.vms_transaction_pymnt WHERE src_cage_shelve_id = " . $pkeyID . " or dest_cage_shelve_id = " . $pkeyID;
    $result2 = executeSQLNoParams($selSQL2);
    $trnsCnt2 = 0;
    while ($row = loc_db_fetch_array($result2)) {
        $trnsCnt2 = (float) $row[0];
    }
    if ($trnsCnt2 > 0) {
        $dsply = "No Record Deleted<br/>Cannot Delete Vault Cages used in Transactions!";
        return "<p style=\"text-align:left;color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
    $selSQL3 = "Select count(1) from vms.vms_stock_daily_bals WHERE cage_shelve_id = " . $pkeyID;
    $result3 = executeSQLNoParams($selSQL3);
    $trnsCnt3 = 0;
    while ($row = loc_db_fetch_array($result3)) {
        $trnsCnt3 = (float) $row[0];
    }
    if ($trnsCnt3 > 0) {
        $dsply = "No Record Deleted<br/>Cannot Delete Vault Cages used in Transactions!";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
    $affctd1 = 0;
    if (($trnsCnt + $trnsCnt1 + $trnsCnt2 + $trnsCnt3) <= 0) {
        $insSQL = "DELETE FROM inv.inv_shelf WHERE line_id = " . $pkeyID;
        $affctd1 = execUpdtInsSQL($insSQL, "Cage/Till Name:" . $extrInfo);
    }
    if ($affctd1 > 0) {
        $dsply = "Successfully Deleted the ff Records-";
        $dsply .= "<br/>$affctd1 Vault Cage(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function getAuthrzrLmtID($prsnID, $siteID, $trnsTyp, $crncyID, $minAmnt, $maxAmnt)
{
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

function get_ApprvrLmts($pkID, $searchWord, $searchIn, $offset, $limit_size)
{
    $whereCls = "";
    if ($searchIn == "Authorizer Name") {
        $whereCls = " and (prs.get_prsn_name(a.authorizer_person_id) ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Site") {
        $whereCls = " and (org.get_site_code_desc(a.site_id) ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Transaction Type") {
        $whereCls = " and (a.transaction_type ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Currency") {
        $whereCls = " and (gst.get_pssbl_val(a.currency_id) ilike '" . loc_db_escape_string($searchWord) . "')";
    }

    $strSql = "SELECT a.authorizer_limit_id, 
        prs.get_prsn_loc_id(a.authorizer_person_id), 
        prs.get_prsn_name(a.authorizer_person_id),
        a.site_id, 
        CASE WHEN a.site_id>0 THEN org.get_site_code_desc(a.site_id) ELSE 'All Branches' END, 
        COALESCE(NULLIF(a.transaction_type,''), 'All Transaction Types') transaction_type,
        a.currency_id, 
        CASE WHEN a.currency_id>0 THEN gst.get_pssbl_val(a.currency_id) ELSE 'All Currencies' END,
        a.min_amount, a.max_amount, a.is_enabled, 
        (Select count(1) from vms.vms_transactions_hdr z WHERE z.mtchd_athrzr_lmt_id=a.authorizer_limit_id) isused
        FROM vms.vms_authorizers_limit a
        WHERE ((a.org_id = $pkID)$whereCls) 
        ORDER BY a.authorizer_limit_id DESC LIMIT " . $limit_size . " OFFSET " . abs($offset * $limit_size);
    //echo $strSql;
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_ApprvrLmtsTtl($pkID, $searchWord, $searchIn)
{
    $whereCls = "";
    if ($searchIn == "Authorizer Name") {
        $whereCls = " and (prs.get_prsn_name(a.authorizer_person_id) ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Site") {
        $whereCls = " and (org.get_site_code_desc(a.site_id) ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Transaction Type") {
        $whereCls = " and (a.transaction_type ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Currency") {
        $whereCls = " and (gst.get_pssbl_val(a.currency_id) ilike '" . loc_db_escape_string($searchWord) . "')";
    }

    $strSql = "SELECT count(1) 
        FROM vms.vms_authorizers_limit a
        WHERE ((a.org_id = $pkID)$whereCls)";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return 0;
}

function deleteApprvrLmt($pkeyID, $extrInfo = "")
{
    $selSQL = "Select count(1) from vms.vms_transactions_hdr WHERE mtchd_athrzr_lmt_id = " . $pkeyID;
    $result = executeSQLNoParams($selSQL);
    $trnsCnt = 0;
    $affctd1 = 0;
    while ($row = loc_db_fetch_array($result)) {
        $trnsCnt = $row[0];
    }
    if ($trnsCnt <= 0) {
        $insSQL = "DELETE FROM vms.vms_authorizers_limit WHERE authorizer_limit_id = " . $pkeyID;
        $affctd1 = execUpdtInsSQL($insSQL, "Name:" . $extrInfo);
    }
    if ($affctd1 > 0) {
        $dsply = "Successfully Deleted the ff Records-";
        $dsply .= "<br/>$affctd1 Authorizer Limit(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Deleted<br/>Has been Used to Authorize $trnsCnt Transaction(s)!";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function createApprvrLmt($athrzrPsnID, $crncyID, $minAmnt, $maxAmnt, $isEnbld, $orgID, $siteID, $trnsType)
{
    global $usrID;
    $dateStr = getDB_Date_time();
    $insSQL = "INSERT INTO vms.vms_authorizers_limit(
            authorizer_person_id, currency_id, min_amount, 
            max_amount, is_enabled, created_by, creation_date, last_update_by, 
            last_update_date, org_id, site_id, transaction_type) " .
        "VALUES (" . $athrzrPsnID . ", " . $crncyID . ", " . $minAmnt . ", " . $maxAmnt .
        ", '" . loc_db_escape_string($isEnbld) . "', " . $usrID . ", '" . $dateStr .
        "', " . $usrID . ", '" . $dateStr .
        "', " . $orgID . ", " . $siteID . ", '" . loc_db_escape_string($trnsType) . "')";
    return execUpdtInsSQL($insSQL);
}

function updateApprvrLmt($athrzrLmtID, $athrzrPsnID, $crncyID, $minAmnt, $maxAmnt, $isEnbld, $siteID, $trnsType)
{
    global $usrID;
    $dateStr = getDB_Date_time();
    $selSQL = "Select count(1) from vms.vms_transactions_hdr WHERE mtchd_athrzr_lmt_id = " . $athrzrLmtID;
    $result = executeSQLNoParams($selSQL);
    $trnsCnt = 0;
    while ($row = loc_db_fetch_array($result)) {
        $trnsCnt = $row[0];
    }
    if ($trnsCnt <= 0) {
        $insSQL = "UPDATE vms.vms_authorizers_limit
                SET authorizer_person_id=" . $athrzrPsnID .
            ", currency_id=" . $crncyID .
            ", min_amount=" . $minAmnt .
            ", max_amount=" . $maxAmnt .
            ", is_enabled='" . loc_db_escape_string($isEnbld) .
            "', last_update_by=" . $usrID .
            ", last_update_date='" . $dateStr .
            "', site_id=" . $siteID .
            ", transaction_type='" . loc_db_escape_string($trnsType) . "' 
               WHERE(authorizer_limit_id = " . $athrzrLmtID . ")";
        return execUpdtInsSQL($insSQL);
    } else {
        $insSQL = "UPDATE vms.vms_authorizers_limit
                SET is_enabled='" . loc_db_escape_string($isEnbld) .
            "', last_update_by=" . $usrID .
            ", last_update_date='" . $dateStr .
            "' WHERE(authorizer_limit_id = " . $athrzrLmtID . ")";
        return execUpdtInsSQL($insSQL);
    }
}

function get_Trns($trnsType, $notAprvdOnly, $voidedOnly, $strtDate, $endDate, $searchWord, $searchIn, $offset, $limit_size)
{
    global $usrID;
    global $prsnid;
    global $orgID;
    global $canSeeOthrsTrns;
    //var_dump(func_get_args());
    $whereCls = "";
    if ($searchIn == "Transaction Number") {
        $whereCls = " and (a.trans_number ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Description") {
        $whereCls = " and (a.comments_desc ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Transaction Type") {
        $whereCls = " and (a.trans_type ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Client" || $searchIn == "Vendor") {
        $whereCls = " and ((scm.get_cstmr_splr_name(a.cstmr_spplr_id) || ' ' || "
            . "scm.get_cstmr_splr_site_name(a.cstmr_spplr_site_id)) ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Transaction Status") {
        $whereCls = " and (a.approval_status ilike '" . loc_db_escape_string($searchWord) . "'"
            . " or a.validity_status ilike '" . loc_db_escape_string($searchWord) . "')";
    }
    if ($strtDate != "") {
        $whereCls .= " AND (a.trans_date >= '" . loc_db_escape_string(cnvrtDMYTmToYMDTm($strtDate)) . "')";
    }
    if ($endDate != "") {
        $whereCls .= " AND (a.trans_date <= '" . loc_db_escape_string(cnvrtDMYTmToYMDTm($endDate)) . "')";
    }
    if ($trnsType != "") {
        $whereCls .= " and a.trans_type='" . loc_db_escape_string($trnsType) . "'";
    }
    if ($notAprvdOnly == "true") {
        $whereCls .= " and a.approval_status != 'Authorized'";
    }
    if ($voidedOnly == "true") {
        $whereCls .= " and (a.validity_status != 'VALID' or a.voided_trns_hdr_id>0)";
    }
    if (!$canSeeOthrsTrns) {
        $whereCls .= " and (a.created_by = $usrID or a.last_update_by= $usrID or a.approved_by_prsn_id=$prsnid)";
    }
    $strSql = "SELECT a.trans_hdr_id, 
       to_char(to_timestamp(a.trans_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') trnsDate,
       a.trans_number, a.trans_type, a.comments_desc, 
       a.cstmr_spplr_id, scm.get_cstmr_splr_name(a.cstmr_spplr_id) cstmr_name,
       a.cstmr_spplr_site_id, scm.get_cstmr_splr_site_name(a.cstmr_spplr_site_id) site_name, a.internal_doc_clsfctn,
       a.approval_status, a.next_aproval_action, 
       a.validity_status, a.voided_trns_hdr_id, a.voided_trns_type,  
       a.created_by, a.creation_date, a.last_update_date, 
       a.approved_by_prsn_id, a.mtchd_athrzr_lmt_id, a.trns_loc_site_id, a.ttl_trns_amount, 
       a.src_store_vault_id, inv.get_store_name(a.src_store_vault_id) srcVltNm,
       a.src_cage_shelve_id, inv.get_shelve_name(a.src_cage_shelve_id) srcCageNm,
       a.dest_store_vault_id, inv.get_store_name(a.dest_store_vault_id) destVltNm,
       a.dest_cage_shelve_id, inv.get_shelve_name(a.dest_cage_shelve_id) destCageID,
       a.crncy_id, gst.get_pssbl_val(a.crncy_id) pssblValNm, 
       a.pymnt_crncy_id, gst.get_pssbl_val(a.pymnt_crncy_id),        
       a.pymnt_ttl_amnt, a.avrg_exchange_rate  
  FROM vms.vms_transactions_hdr a
        WHERE ((a.org_id = $orgID)$whereCls) 
        ORDER BY a.trans_hdr_id DESC LIMIT " . $limit_size . " OFFSET " . abs($offset * $limit_size);

    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_TrnsTtl($trnsType, $notAprvdOnly, $voidedOnly, $strtDate, $endDate, $searchWord, $searchIn)
{
    global $usrID;
    global $prsnid;
    global $orgID;
    global $canSeeOthrsTrns;

    $whereCls = "";
    if ($searchIn == "Transaction Number") {
        $whereCls = " and (a.trans_number ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Description") {
        $whereCls = " and (a.comments_desc ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Transaction Type") {
        $whereCls = " and (a.trans_type ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Client" || $searchIn == "Vendor") {
        $whereCls = " and ((scm.get_cstmr_splr_name(a.cstmr_spplr_id) || ' ' || "
            . "scm.get_cstmr_splr_site_name(a.cstmr_spplr_site_id)) ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Transaction Status") {
        $whereCls = " and (a.approval_status ilike '" . loc_db_escape_string($searchWord) . "'"
            . " or a.validity_status ilike '" . loc_db_escape_string($searchWord) . "')";
    }
    if ($strtDate != "") {
        $whereCls .= " AND (a.trans_date >= '" . loc_db_escape_string(cnvrtDMYTmToYMDTm($strtDate)) . "')";
    }
    if ($endDate != "") {
        $whereCls .= " AND (a.trans_date <= '" . loc_db_escape_string(cnvrtDMYTmToYMDTm($endDate)) . "')";
    }
    if ($trnsType != "") {
        $whereCls .= " and a.trans_type='" . loc_db_escape_string($trnsType) . "'";
    }
    if ($notAprvdOnly == "true") {
        $whereCls .= " and a.approval_status != 'Authorized'";
    }
    if ($voidedOnly == "true") {
        $whereCls .= " and (a.validity_status != 'VALID' or a.voided_trns_hdr_id>0)";
    }
    if (!$canSeeOthrsTrns) {
        $whereCls .= " and (a.created_by = $usrID or a.last_update_by= $usrID or a.approved_by_prsn_id=$prsnid)";
    }
    $strSql = "SELECT count(1) FROM vms.vms_transactions_hdr a
        WHERE ((a.org_id = $orgID)$whereCls)";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return 0;
}

function get_AllCageTrns($trnsType, $notAprvdOnly, $voidedOnly, $strtDate, $endDate, $searchWord, $sbmtdShelfID, $offset, $limit_size)
{
    global $usrID;
    global $prsnid;
    global $orgID;
    global $canSeeOthrsTrns;
    //var_dump(func_get_args());
    $whereCls = "";
    $whereCls = " and (a.trans_number ilike '" . loc_db_escape_string($searchWord) .
        "' or a.comments_desc ilike '" . loc_db_escape_string($searchWord) .
        "' or a.trans_type ilike '" . loc_db_escape_string($searchWord) .
        "' or (scm.get_cstmr_splr_name(a.cstmr_spplr_id) || ' ' || scm.get_cstmr_splr_site_name(a.cstmr_spplr_site_id)) ilike '" . loc_db_escape_string($searchWord) .
        "' or a.approval_status ilike '" . loc_db_escape_string($searchWord) . "'"
        . " or a.validity_status ilike '" . loc_db_escape_string($searchWord) . "')";

    if ($strtDate != "") {
        $whereCls .= " AND (a.trans_date >= '" . loc_db_escape_string(cnvrtDMYTmToYMDTm($strtDate)) . "')";
    }
    if ($endDate != "") {
        $whereCls .= " AND (a.trans_date <= '" . loc_db_escape_string(cnvrtDMYTmToYMDTm($endDate)) . "')";
    }
    if ($trnsType != "") {
        $whereCls .= " and a.trans_type='" . loc_db_escape_string($trnsType) . "'";
    }
    if ($notAprvdOnly == "true") {
        $whereCls .= " and a.approval_status != 'Authorized'";
    }
    if ($voidedOnly == "true") {
        $whereCls .= " and (a.validity_status != 'VALID' or a.voided_trns_hdr_id>0)";
    }
    if (!$canSeeOthrsTrns) {
        $whereCls .= " and (a.created_by = $usrID or a.last_update_by= $usrID or a.approved_by_prsn_id=$prsnid)";
    }
    $strSql = "SELECT a.trans_hdr_id, 
       to_char(to_timestamp(a.trans_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') trnsDate,
       a.trans_number, a.trans_type, a.comments_desc, 
       a.cstmr_spplr_id, scm.get_cstmr_splr_name(a.cstmr_spplr_id) cstmr_name,
       a.cstmr_spplr_site_id, scm.get_cstmr_splr_site_name(a.cstmr_spplr_site_id) site_name, a.internal_doc_clsfctn,
       a.approval_status, a.next_aproval_action, 
       a.validity_status, a.voided_trns_hdr_id, a.voided_trns_type,  
       a.created_by, a.creation_date, a.last_update_date, 
       a.approved_by_prsn_id, a.mtchd_athrzr_lmt_id, a.trns_loc_site_id, a.ttl_trns_amount, 
       a.src_store_vault_id, inv.get_store_name(a.src_store_vault_id) srcVltNm,
       a.src_cage_shelve_id, inv.get_shelve_name(a.src_cage_shelve_id) srcCageNm,
       a.dest_store_vault_id, inv.get_store_name(a.dest_store_vault_id) destVltNm,
       a.dest_cage_shelve_id, inv.get_shelve_name(a.dest_cage_shelve_id) destCageID,
       a.crncy_id, gst.get_pssbl_val(a.crncy_id) pssblValNm, 
       a.pymnt_crncy_id, gst.get_pssbl_val(a.pymnt_crncy_id),        
       a.pymnt_ttl_amnt, a.avrg_exchange_rate  
  FROM vms.vms_transactions_hdr a
        WHERE ((a.org_id = $orgID and (a.src_cage_shelve_id=$sbmtdShelfID or a.dest_cage_shelve_id=$sbmtdShelfID) and (a.src_cage_shelve_id>=0 or a.dest_cage_shelve_id>0))$whereCls) 
        ORDER BY a.trans_hdr_id DESC LIMIT " . $limit_size . " OFFSET " . abs($offset * $limit_size);

    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_AllCageTrnsTtl($trnsType, $notAprvdOnly, $voidedOnly, $strtDate, $endDate, $searchWord, $sbmtdShelfID)
{
    global $usrID;
    global $prsnid;
    global $orgID;
    global $canSeeOthrsTrns;
    //var_dump(func_get_args());
    $whereCls = "";
    $whereCls = " and (a.trans_number ilike '" . loc_db_escape_string($searchWord) .
        "' or a.comments_desc ilike '" . loc_db_escape_string($searchWord) .
        "' or a.trans_type ilike '" . loc_db_escape_string($searchWord) .
        "' or (scm.get_cstmr_splr_name(a.cstmr_spplr_id) || ' ' || scm.get_cstmr_splr_site_name(a.cstmr_spplr_site_id)) ilike '" . loc_db_escape_string($searchWord) .
        "' or a.approval_status ilike '" . loc_db_escape_string($searchWord) . "'"
        . " or a.validity_status ilike '" . loc_db_escape_string($searchWord) . "')";

    if ($strtDate != "") {
        $whereCls .= " AND (a.trans_date >= '" . loc_db_escape_string(cnvrtDMYTmToYMDTm($strtDate)) . "')";
    }
    if ($endDate != "") {
        $whereCls .= " AND (a.trans_date <= '" . loc_db_escape_string(cnvrtDMYTmToYMDTm($endDate)) . "')";
    }
    if ($trnsType != "") {
        $whereCls .= " and a.trans_type='" . loc_db_escape_string($trnsType) . "'";
    }
    if ($notAprvdOnly == "true") {
        $whereCls .= " and a.approval_status != 'Authorized'";
    }
    if ($voidedOnly == "true") {
        $whereCls .= " and (a.validity_status != 'VALID' or a.voided_trns_hdr_id>0)";
    }
    if (!$canSeeOthrsTrns) {
        $whereCls .= " and (a.created_by = $usrID or a.last_update_by= $usrID or a.approved_by_prsn_id=$prsnid)";
    }
    $strSql = "SELECT count(1) FROM vms.vms_transactions_hdr a
        WHERE ((a.org_id = $orgID and (a.src_cage_shelve_id=$sbmtdShelfID or a.dest_cage_shelve_id=$sbmtdShelfID) and (a.src_cage_shelve_id>=0 or a.dest_cage_shelve_id>0))$whereCls)";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return 0;
}

function get_One_VMSTrnsHdr($trnsHdrID)
{
    global $usrID;
    global $prsnid;
    global $canSeeOthrsTrns;
    $whereCls = "";
    if (!$canSeeOthrsTrns) {
        $whereCls .= " and (a.created_by = $usrID or a.last_update_by= $usrID or a.approved_by_prsn_id=$prsnid)";
    }
    $strSql = "SELECT a.trans_hdr_id, 
        to_char(to_timestamp(a.trans_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') trnsDate,
       a.trans_number, a.trans_type, a.comments_desc, 
       a.cstmr_spplr_id, scm.get_cstmr_splr_name(a.cstmr_spplr_id) cstmr_name,
       a.cstmr_spplr_site_id, scm.get_cstmr_splr_site_name(a.cstmr_spplr_site_id) site_name, 
       a.internal_doc_clsfctn,
       a.approval_status, a.next_aproval_action, 
       a.validity_status, a.voided_trns_hdr_id, a.voided_trns_type,  
       a.created_by, a.creation_date, a.last_update_date, 
       a.approved_by_prsn_id, a.mtchd_athrzr_lmt_id, a.trns_loc_site_id, a.ttl_trns_amount, 
       a.src_store_vault_id, inv.get_store_name(a.src_store_vault_id) srcVltNm,
       a.src_cage_shelve_id, inv.get_shelve_name(a.src_cage_shelve_id) srcCageNm,
       a.dest_store_vault_id, inv.get_store_name(a.dest_store_vault_id) destVltNm,
       a.dest_cage_shelve_id, inv.get_shelve_name(a.dest_cage_shelve_id) destCageID,
       a.crncy_id, gst.get_pssbl_val(a.crncy_id) pssblValNm, 
       a.pymnt_crncy_id, gst.get_pssbl_val(a.pymnt_crncy_id),        
       a.pymnt_ttl_amnt, a.avrg_exchange_rate, a.reversal_reason, a.is_bin_card_trns, 
       a.trns_prsntd_by_person, prs.get_prsn_loc_id(a.trns_officiated_by), 
       prs.get_prsn_name(a.trns_officiated_by),a.trns_officiated_by, a.cheque_slip_no,
       (Select SUM(z.ptycsh_smmry_amnt) FROM accb.accb_ptycsh_amnt_smmrys z WHERE z.lnkd_vms_trns_hdr_id=" . $trnsHdrID .
        " and z.ptycsh_smmry_type IN ('1Initial Amount')) sum_expns
  FROM vms.vms_transactions_hdr a
        WHERE ((a.trans_hdr_id = $trnsHdrID)$whereCls)";
    $result = executeSQLNoParams($strSql);
    return $result;
}

function getTrnsHdrID($trnsNum)
{
    $strSql = "SELECT a.trans_hdr_id 
        FROM vms.vms_transactions_hdr a
        WHERE ((a.trans_number ='" . loc_db_escape_string($trnsNum) . "')) ORDER BY a.trans_hdr_id DESC ";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return -1;
}

function getNewTrnsHdrID()
{
    $sqlStr = "select nextval('vms.vms_transactions_hdr_trans_hdr_id_seq'::regclass);";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -1;
}

function getNewVMSTrnsLineID()
{
    $sqlStr = "select nextval('vms.vms_transaction_lines_trans_det_ln_id_seq'::regclass);";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -1;
}

function getNewVMSPymtLineID()
{
    $sqlStr = "select nextval('vms.vms_transaction_pymnt_trans_det_ln_id_seq'::regclass);";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -1;
}

function getNewGLIntrfcID()
{
    $sqlStr = "select nextval('vms.vms_gl_interface_interface_id_seq'::regclass);";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -1;
}

function getNewAttchdDocID()
{
    $sqlStr = "select nextval('vms.vms_doc_attchmnts_attchmnt_id_seq'::regclass);";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -1;
}

function deleteVMSTrnsHdr($pkeyID, $srcDocType, $extrInfo = "")
{
    $selSQL = "Select count(1) from vms.vms_transactions_hdr WHERE approval_status IN ('Authorized','Initiated','Reviewed') and trans_hdr_id = " . $pkeyID;
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
        $insSQL = "DELETE FROM vms.vms_gl_interface WHERE src_doc_id = " . $pkeyID . " and src_doc_typ = '" . loc_db_escape_string($srcDocType) . "'";
        $affctd1 = execUpdtInsSQL($insSQL, "Trns Number:" . $extrInfo . ":Type:" . $srcDocType);
        $insSQL = "DELETE FROM vms.vms_trns_amnt_brkdwn WHERE trans_det_ln_id IN (select z.trans_det_ln_id from vms.vms_transaction_lines z WHERE z.trans_hdr_id= " . $pkeyID . ")";
        $affctd2 = execUpdtInsSQL($insSQL, "Trns Number:" . $extrInfo . ":Type:" . $srcDocType);
        $insSQL = "DELETE FROM vms.vms_transaction_lines WHERE trans_hdr_id = " . $pkeyID;
        $affctd3 = execUpdtInsSQL($insSQL, "Trns Number:" . $extrInfo . ":Type:" . $srcDocType);
        $insSQL = "DELETE FROM vms.vms_doc_attchmnts WHERE trans_hdr_id = " . $pkeyID;
        $affctd4 = execUpdtInsSQL($insSQL, "Trns Number:" . $extrInfo . ":Type:" . $srcDocType);
        $insSQL = "DELETE FROM vms.vms_transactions_hdr WHERE trans_hdr_id = " . $pkeyID;
        $affctd5 = execUpdtInsSQL($insSQL, "Trns Number:" . $extrInfo . ":Type:" . $srcDocType);
    }
    if ($affctd5 > 0) {
        $dsply = "Successfully Deleted the ff Records-";
        $dsply .= "<br/>$affctd1 GL Interface Transaction(s)!";
        $dsply .= "<br/>$affctd2 Trns. Amount Breakdown(s)!";
        $dsply .= "<br/>$affctd3 Transaction Line(s)!";
        $dsply .= "<br/>$affctd4 Doc. Attachment(s)!";
        $dsply .= "<br/>$affctd5 VMS Transaction(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Deleted<br/>Cannot Delete Authorized Transactions!";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function createVMSTrnsHdr($trnsHdrID, $trnsDte, $trnsNum, $trnsType, $cmmntDesc, $cstmrID, $cstmrSiteID, $voidedTrnsHdrID, $voidedTrnsType, $docClsfctn, $trnsSiteID, $trnsAmnt, $srcVltID, $srcCageID, $destStoreID, $destCageID, $crncyID, $pymntCrncyID, $pymtTtlAmnt, $exchngRate, $isBinCardTrns = "0", $vmsTrnsPrsn = "N/A", $vmsOffctStaffPrsID = -1)
{
    global $usrID;
    global $orgID;
    global $gnrlTrnsDteYMDHMS;
    $dateStr = getDB_Date_time();
    $insSQL = "INSERT INTO vms.vms_transactions_hdr(
            trans_hdr_id, trans_date, trans_number, trans_type, comments_desc, 
            cstmr_spplr_id, cstmr_spplr_site_id, approval_status, next_aproval_action, 
            validity_status, voided_trns_hdr_id, voided_trns_type, org_id, 
            created_by, creation_date, last_update_by, last_update_date, 
            internal_doc_clsfctn, approved_by_prsn_id, mtchd_athrzr_lmt_id, 
            trns_loc_site_id, ttl_trns_amount, src_store_vault_id, src_cage_shelve_id, 
            dest_store_vault_id, dest_cage_shelve_id, crncy_id, pymnt_crncy_id, 
       pymnt_ttl_amnt, avrg_exchange_rate, is_bin_card_trns, 
            trns_prsntd_by_person, trns_officiated_by) " .
        "VALUES (" . $trnsHdrID . ",'" . loc_db_escape_string($gnrlTrnsDteYMDHMS) .
        "', '" . loc_db_escape_string($trnsNum) .
        "', '" . loc_db_escape_string($trnsType) .
        "', '" . loc_db_escape_string($cmmntDesc) .
        "', " . $cstmrID .
        ", " . $cstmrSiteID .
        ", 'Not Submitted', 'Submit', 'Not Validated', " .
        $voidedTrnsHdrID . ", '" . loc_db_escape_string($voidedTrnsType) .
        "', " . $orgID . ", " . $usrID . ", '" . $dateStr .
        "', " . $usrID . ", '" . $dateStr .
        "', '" . loc_db_escape_string($docClsfctn) .
        "', -1, -1, " . $trnsSiteID .
        ", " . $trnsAmnt .
        ", " . $srcVltID .
        ", " . $srcCageID .
        ", " . $destStoreID .
        ", " . $destCageID .
        ", " . $crncyID .
        ", " . $pymntCrncyID .
        ", " . $pymtTtlAmnt .
        ", " . $exchngRate .
        ", '" . loc_db_escape_string($isBinCardTrns) .
        "', '" . loc_db_escape_string($vmsTrnsPrsn) .
        "', " . $vmsOffctStaffPrsID .
        ")";
    return execUpdtInsSQL($insSQL);
}

function updateVMSTrnsHdr($trnsHdrID, $trnsDte, $trnsNum, $trnsType, $cmmntDesc, $cstmrID, $cstmrSiteID, $docClsfctn, $trnsSiteID, $trnsAmnt, $srcVltID, $srcCageID, $destStoreID, $destCageID, $crncyID, $pymntCrncyID, $pymtTtlAmnt, $exchngRate, $vmsTrnsPrsn = "N/A", $vmsOffctStaffPrsID = -1, $vmsChequeNo = "")
{
    global $usrID;
    global $gnrlTrnsDteYMDHMS;
    $dateStr = getDB_Date_time();
    $selSQL = "Select count(1) from vms.vms_transactions_hdr WHERE approval_status IN ('Authorized','Initiated','Reviewed') and trans_hdr_id = " . $trnsHdrID;
    $result = executeSQLNoParams($selSQL);
    $trnsCnt = 0;
    while ($row = loc_db_fetch_array($result)) {
        $trnsCnt = $row[0];
    }
    if ($trnsCnt <= 0 && $trnsHdrID > 0) {
        $insSQL = "UPDATE vms.vms_transactions_hdr 
            SET trans_date='" . loc_db_escape_string($gnrlTrnsDteYMDHMS) .
            "', trans_number='" . loc_db_escape_string($trnsNum) .
            "', trans_type='" . loc_db_escape_string($trnsType) .
            "', comments_desc='" . loc_db_escape_string($cmmntDesc) .
            "', cstmr_spplr_id=" . $cstmrID .
            ",  cstmr_spplr_site_id=" . $cstmrSiteID .
            ",  last_update_by=" . $usrID .
            ", last_update_date='" . $dateStr .
            "', internal_doc_clsfctn='" . loc_db_escape_string($docClsfctn) .
            "',  trns_loc_site_id=" . $trnsSiteID .
            ",  ttl_trns_amount=" . $trnsAmnt .
            ", src_store_vault_id=" . $srcVltID .
            ", src_cage_shelve_id=" . $srcCageID .
            ", dest_store_vault_id=" . $destStoreID .
            ", dest_cage_shelve_id=" . $destCageID .
            ", crncy_id=" . $crncyID .
            ", pymnt_crncy_id=" . $pymntCrncyID .
            ", pymnt_ttl_amnt=" . $pymtTtlAmnt .
            ", avrg_exchange_rate=" . $exchngRate .
            ", trns_prsntd_by_person='" . loc_db_escape_string($vmsTrnsPrsn) .
            "', trns_officiated_by=" . $vmsOffctStaffPrsID .
            ", cheque_slip_no='" . loc_db_escape_string($vmsChequeNo) .
            "' WHERE(trans_hdr_id = " . $trnsHdrID . ")";
        return execUpdtInsSQL($insSQL);
    } else {
        return 0;
    }
}

function updateVMSTrnsVoidRsn($trnsHdrID, $cmmntDesc)
{
    global $usrID;
    $dateStr = getDB_Date_time();
    $selSQL = "Select count(1) from vms.vms_transactions_hdr WHERE approval_status IN ('Authorized','Initiated','Reviewed') and trans_hdr_id = " . $trnsHdrID;
    $result = executeSQLNoParams($selSQL);
    $trnsCnt = 0;
    while ($row = loc_db_fetch_array($result)) {
        $trnsCnt = $row[0];
    }
    if ($trnsCnt <= 0 && $trnsHdrID > 0) {
        $insSQL = "UPDATE vms.vms_transactions_hdr 
            SET reversal_reason='" . loc_db_escape_string($cmmntDesc) .
            "', last_update_by=" . $usrID .
            ", last_update_date='" . $dateStr .
            "' WHERE(trans_hdr_id = " . $trnsHdrID . ")";
        return execUpdtInsSQL($insSQL);
    } else {
        return 0;
    }
}

function createVMSTrnsVoidLn($trnsHdrID, $voidedTrnsHdrID)
{
    global $usrID;
    $dateStr = getDB_Date_time();
    $insSQL = "INSERT INTO vms.vms_transaction_lines(
            trans_hdr_id, itm_id, src_store_vault_id, src_cage_shelve_id, 
            dest_store_vault_id, dest_cage_shelve_id, doc_qty, crncy_id, 
            unit_value, validity_status, voided_src_line_id, is_itm_delivered, 
            alternate_item_name, src_asset_acct_id, dest_asset_acct_id, liability_accnt_id, 
            expense_accnt_id, cogs_accnt_id, revenue_accnt_id, pymt_cur_exchng_rate, 
            created_by, creation_date, last_update_by, last_update_date, 
            base_uom_id, src_itm_state_clsfctn, dst_itm_state_clsfctn, src_balance_b4_trns, 
            line_desc, dst_balance_b4_trns)
                SELECT $voidedTrnsHdrID, itm_id, src_store_vault_id, src_cage_shelve_id, 
           dest_store_vault_id, dest_cage_shelve_id, -1*doc_qty, crncy_id, 
           unit_value, validity_status, trans_det_ln_id, '0', 
           alternate_item_name, src_asset_acct_id, dest_asset_acct_id, liability_accnt_id, 
           expense_accnt_id, cogs_accnt_id, revenue_accnt_id, pymt_cur_exchng_rate, 
           " . $usrID . ", '" . $dateStr . "', " . $usrID . ", '" . $dateStr .
        "', base_uom_id, src_itm_state_clsfctn, dst_itm_state_clsfctn, 
            (vms.get_ltst_stock_bals(src_store_vault_id, src_cage_shelve_id, itm_id::integer, src_itm_state_clsfctn)* unit_value), 
            line_desc, 
            (vms.get_ltst_stock_bals(dest_store_vault_id, dest_cage_shelve_id, itm_id::integer, dst_itm_state_clsfctn)* unit_value)
      FROM vms.vms_transaction_lines WHERE trans_hdr_id = " . $trnsHdrID;
    return execUpdtInsSQL($insSQL);
}

function createVMSPymtVoidLn($trnsHdrID, $voidedTrnsHdrID)
{
    global $usrID;
    $dateStr = getDB_Date_time();
    $insSQL = "INSERT INTO vms.vms_transaction_pymnt(
            trans_hdr_id, itm_id, src_store_vault_id, src_cage_shelve_id, 
            dest_store_vault_id, dest_cage_shelve_id, doc_qty, crncy_id, 
            unit_value, validity_status, voided_src_line_id, is_itm_delivered, 
            alternate_item_name, src_asset_acct_id, dest_asset_acct_id, liability_accnt_id, 
            expense_accnt_id, cogs_accnt_id, revenue_accnt_id, pymt_cur_exchng_rate, 
            created_by, creation_date, last_update_by, last_update_date, 
            base_uom_id, src_itm_state_clsfctn, dst_itm_state_clsfctn, src_balance_b4_trns, 
            line_desc, dst_balance_b4_trns)
                SELECT $voidedTrnsHdrID, itm_id, src_store_vault_id, src_cage_shelve_id, 
           dest_store_vault_id, dest_cage_shelve_id, -1*doc_qty, crncy_id, 
           unit_value, validity_status, trans_det_ln_id, '0', 
           alternate_item_name, src_asset_acct_id, dest_asset_acct_id, liability_accnt_id, 
           expense_accnt_id, cogs_accnt_id, revenue_accnt_id, pymt_cur_exchng_rate, 
           " . $usrID . ", '" . $dateStr . "', " . $usrID . ", '" . $dateStr .
        "', base_uom_id, src_itm_state_clsfctn, dst_itm_state_clsfctn, 
            (vms.get_ltst_stock_bals(src_store_vault_id, src_cage_shelve_id, itm_id::integer, src_itm_state_clsfctn)* unit_value), 
            line_desc, 
            (vms.get_ltst_stock_bals(dest_store_vault_id, dest_cage_shelve_id, itm_id::integer, dst_itm_state_clsfctn)* unit_value)
      FROM vms.vms_transaction_pymnt WHERE trans_hdr_id = " . $trnsHdrID;
    return execUpdtInsSQL($insSQL);
}

function createVMSExpnsTrnsVoidLine($trnsHdrID, $voidedTrnsHdrID)
{
    global $usrID;
    $dateStr = getDB_Date_time();
    $insSQL = "INSERT INTO accb.accb_ptycsh_amnt_smmrys(
            ptycsh_smmry_type, ptycsh_smmry_desc, ptycsh_smmry_amnt, 
            code_id_behind, src_ptycsh_type, src_ptycsh_hdr_id, created_by, 
            creation_date, last_update_by, last_update_date, auto_calc, incrs_dcrs1, 
            asset_expns_acnt_id, incrs_dcrs2, liability_acnt_id, appld_prepymnt_doc_id, 
            validty_status, orgnl_line_id, entrd_curr_id, func_curr_id, accnt_curr_id, 
            func_curr_rate, accnt_curr_rate, func_curr_amount, accnt_curr_amnt, 
            initial_amnt_line_id, lnkd_vms_trns_hdr_id) " .
        "Select ptycsh_smmry_type, ptycsh_smmry_desc, -1*ptycsh_smmry_amnt, 
            code_id_behind, src_ptycsh_type, src_ptycsh_hdr_id, 
           " . $usrID . ", '" . $dateStr . "', " . $usrID . ", '" . $dateStr .
        "', auto_calc, incrs_dcrs1, 
            asset_expns_acnt_id, incrs_dcrs2, liability_acnt_id, appld_prepymnt_doc_id, 
            validty_status, orgnl_line_id, entrd_curr_id, func_curr_id, accnt_curr_id, 
            func_curr_rate, accnt_curr_rate, -1*func_curr_amount, -1*accnt_curr_amnt, 
            initial_amnt_line_id, $voidedTrnsHdrID FROM accb.accb_ptycsh_amnt_smmrys WHERE lnkd_vms_trns_hdr_id = " . $trnsHdrID;
    return execUpdtInsSQL($insSQL);
}

function deleteVMSGlIntrfc($pkeyID, $srcDocTyp, $extrInfo = "")
{
    $insSQL = "DELETE FROM vms.vms_gl_interface WHERE src_doc_id = " . $pkeyID .
        " and src_doc_typ = '" . loc_db_escape_string($srcDocTyp) . "' and gl_batch_id<=0";
    $affctd1 = execUpdtInsSQL($insSQL, "Trns No:" . $extrInfo . ":Type:" . $srcDocTyp);
    return $affctd1;
}

function createVMSGlIntrfc($interfaceID, $accntID, $trnsDesc, $dbtAmnt, $trnsDate, $funcCrncyID, $crdtAmnt, $netAmnt, $srcDocTyp, $srcDocID, $srcDocLineID, $trnsLnTyp, $trnsSource, $entrdAMnt, $entrdCrncy, $acntCrncyAMnt, $acntCrncyID, $funcCrncyExRate, $accntCrncyExRate)
{
    global $usrID;
    $dateStr = getDB_Date_time();
    $insSQL = "INSERT INTO vms.vms_gl_interface(
            interface_id, accnt_id, transaction_desc, dbt_amount, trnsctn_date, 
            func_cur_id, created_by, creation_date, crdt_amount, last_update_by, 
            last_update_date, net_amount, gl_batch_id, src_doc_typ, src_doc_id, 
            src_doc_line_id, trns_ln_type, trns_source, entered_amnt, entered_amt_crncy_id, 
            accnt_crncy_amnt, accnt_crncy_id, func_cur_exchng_rate, accnt_cur_exchng_rate) " .
        "VALUES (" . $interfaceID .
        ", " . $accntID .
        ", '" . loc_db_escape_string($trnsDesc) .
        "', " . $dbtAmnt .
        ", '" . loc_db_escape_string($trnsDate) .
        "', " . $funcCrncyID .
        ", " . $usrID .
        ", '" . $dateStr .
        "', " . $crdtAmnt .
        ", " . $usrID .
        ", '" . $dateStr .
        "', " . $netAmnt .
        ", -1, '" . loc_db_escape_string($srcDocTyp) .
        "', " . $srcDocID .
        ", " . $srcDocLineID .
        ", '" . loc_db_escape_string($trnsLnTyp) .
        "', '" . loc_db_escape_string($trnsSource) .
        "', " . $entrdAMnt .
        ", " . $entrdCrncy .
        ", " . $acntCrncyAMnt .
        ", " . $acntCrncyID .
        ", " . $funcCrncyExRate .
        ", " . $accntCrncyExRate .
        ")";
    return execUpdtInsSQL($insSQL);
}

function get_VMSAttachments($searchWord, $offset, $limit_size, $hdrID, &$attchSQL)
{
    $strSql = "SELECT a.attchmnt_id, a.trans_hdr_id, a.attchmnt_desc, a.file_name " .
        "FROM vms.vms_doc_attchmnts a " .
        "WHERE(a.attchmnt_desc ilike '" . loc_db_escape_string($searchWord) .
        "' and a.trans_hdr_id = " . $hdrID . ") ORDER BY a.attchmnt_id LIMIT " . $limit_size .
        " OFFSET " . (abs($offset * $limit_size));
    $result = executeSQLNoParams($strSql);
    $attchSQL = $strSql;
    return $result;
}

function get_Total_VMSAttachments($searchWord, $hdrID)
{
    $strSql = "SELECT count(1) " .
        "FROM vms.vms_doc_attchmnts a " .
        "WHERE(a.attchmnt_desc ilike '" . loc_db_escape_string($searchWord) .
        "' and a.trans_hdr_id = " . $hdrID . ")";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return 0;
}

function getVMSTrnsAttchmtDocs()
{
    global $prsnid;

    $sqlStr = "SELECT attchmnt_id, file_name, attchmnt_desc
  FROM vms.vms_doc_attchmnts WHERE 1=1 AND file_name != '' AND trans_hdr_id = " . $prsnid;

    $result = executeSQLNoParams($sqlStr);
    return $result;
}

function updateVMSDocFlNm($attchmnt_id, $file_name)
{
    global $usrID;
    $dateStr = getDB_Date_time();
    $insSQL = "UPDATE vms.vms_doc_attchmnts SET file_name='"
        . loc_db_escape_string($file_name) .
        "', last_update_by=" . $usrID .
        ", last_update_date='" . $dateStr . "'
                WHERE attchmnt_id=" . $attchmnt_id;
    return execUpdtInsSQL($insSQL);
}

function getNewVMSDocID()
{
    $strSql = "select nextval('vms.vms_doc_attchmnts_attchmnt_id_seq')";
    $result = executeSQLNoParams($strSql);

    if (loc_db_num_rows($result) > 0) {
        $row = loc_db_fetch_array($result);
        return $row[0];
    }
    return -1;
}

function createVMSDoc($attchmnt_id, $hdrid, $attchmnt_desc, $file_name)
{
    global $usrID;
    $dateStr = getDB_Date_time();
    $insSQL = "INSERT INTO vms.vms_doc_attchmnts(
            attchmnt_id, trans_hdr_id, attchmnt_desc, file_name, created_by, 
            creation_date, last_update_by, last_update_date)
             VALUES (" . $attchmnt_id . ", " . $hdrid . ",'"
        . loc_db_escape_string($attchmnt_desc) . "','"
        . loc_db_escape_string($file_name) . "',"
        . $usrID . ",'" . $dateStr . "'," . $usrID . ",'" . $dateStr . "')";
    return execUpdtInsSQL($insSQL);
}

function deleteVMSDoc($pkeyID, $docTrnsNum = "")
{
    $insSQL = "DELETE FROM vms.vms_doc_attchmnts WHERE attchmnt_id = " . $pkeyID;
    $affctd1 = execUpdtInsSQL($insSQL, "VMS Trns. No:" . $docTrnsNum);
    if ($affctd1 > 0) {
        $dsply = "Successfully Deleted the ff Records-";
        $dsply .= "<br/>$affctd1 Attached Document(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Deleted";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function uploadDaVMSDoc($attchmntID, &$nwImgLoc, &$errMsg)
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

    if (isset($_FILES["daVMSAttchmnt"])) {
        $flnm = $_FILES["daVMSAttchmnt"]["name"];
        $temp = explode(".", $flnm);
        $extension = end($temp);
        if ($_FILES["daVMSAttchmnt"]["error"] > 0) {
            $msg .= "Return Code: " . $_FILES["daVMSAttchmnt"]["error"] . "<br>";
        } else {
            $msg .= "Uploaded File: " . $_FILES["daVMSAttchmnt"]["name"] . "<br>";
            $msg .= "Type: " . $_FILES["daVMSAttchmnt"]["type"] . "<br>";
            $msg .= "Size: " . round(($_FILES["daVMSAttchmnt"]["size"]) / (1024 * 1024), 2) . " MB<br>";
            //$msg .= "Temp file: " . $_FILES["daVMSAttchmnt"]["tmp_name"] . "<br>";
            if ((($_FILES["daVMSAttchmnt"]["type"] == "image/gif") ||
                    ($_FILES["daVMSAttchmnt"]["type"] == "image/jpeg") ||
                    ($_FILES["daVMSAttchmnt"]["type"] == "image/jpg") ||
                    ($_FILES["daVMSAttchmnt"]["type"] == "image/pjpeg") ||
                    ($_FILES["daVMSAttchmnt"]["type"] == "image/x-png") ||
                    ($_FILES["daVMSAttchmnt"]["type"] == "image/png") ||
                    in_array($extension, $allowedExts)) &&
                ($_FILES["daVMSAttchmnt"]["size"] < 10000000)
            ) {
                $nwFileName = encrypt1($attchmntID . "." . $extension, $smplTokenWord1) . "." . $extension;
                $img_src = $fldrPrfx . $tmpDest . "$nwFileName";
                move_uploaded_file($_FILES["daVMSAttchmnt"]["tmp_name"], $img_src);
                $ftp_src = $ftp_base_db_fldr . "/Vms/$attchmntID" . "." . $extension;
                if (file_exists($img_src)) {
                    copy("$img_src", "$ftp_src");
                    $dateStr = getDB_Date_time();
                    $updtSQL = "UPDATE vms.vms_doc_attchmnts
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

function get_TrnsAmntBrkdwn($pkID)
{
    $strSql = "SELECT trns_amnt_brkdwn_id, trans_det_ln_id, itm_id, uom_id, base_uom_mltplr, uom_qty
               FROM vms.vms_trns_amnt_brkdwn a, inv.itm_uoms b 
               WHERE ((a.itm_id =b.item_id and a.uom_id =  b.uom_id) and (a.trans_det_ln_id = $pkID))"
        . " ORDER BY a.uom_level DESC";
    $result = executeSQLNoParams($strSql);
    return $result;
}

function createTrnsAmntBrkdwn($brkdwnID, $trnsLnID, $itemID, $uomID, $mltplr, $uomQty)
{
    global $usrID;
    $dateStr = getDB_Date_time();
    $insSQL = "INSERT INTO vms.vms_trns_amnt_brkdwn(
            trns_amnt_brkdwn_id, trans_det_ln_id, itm_id, uom_id, base_uom_mltplr, 
            uom_qty, created_by, creation_date, last_update_by, last_update_date) " .
        "VALUES (" . $brkdwnID .
        ", " . $trnsLnID .
        ", " . $itemID .
        ", " . $uomID .
        ", " . $mltplr .
        ", " . $uomQty .
        ", " . $usrID . ", '" . $dateStr .
        "', " . $usrID . ", '" . $dateStr .
        "')";
    return execUpdtInsSQL($insSQL);
}

function deleteTrnsAmntBrkdwn($pkeyID, $extrInfo = "")
{
    $insSQL = "DELETE FROM vms.vms_trns_amnt_brkdwn WHERE trans_det_ln_id = " . $pkeyID;
    $affctd1 = execUpdtInsSQL($insSQL, "Trns No:" . $extrInfo);
    return $affctd1;
}

function deleteVMSTrnsLine($pkeyID, $srcDocType, $extrInfo = "")
{
    $selSQL = "Select count(1) from vms.vms_gl_interface WHERE gl_batch_id>0 and src_doc_line_id = " . $pkeyID . " and trns_ln_type = '" . loc_db_escape_string($srcDocType) . "'";
    $result = executeSQLNoParams($selSQL);
    $trnsCnt = 0;
    $affctd1 = 0;
    $affctd2 = 0;
    $affctd3 = 0;
    while ($row = loc_db_fetch_array($result)) {
        $trnsCnt = $row[0];
    }
    if ($trnsCnt <= 0) {
        $insSQL = "DELETE FROM vms.vms_gl_interface WHERE src_doc_line_id = " . $pkeyID . " and trns_ln_type = '" . loc_db_escape_string($srcDocType) . "'";
        $affctd1 = execUpdtInsSQL($insSQL, "Trns Number:" . $extrInfo . ":Type:" . $srcDocType);
        $insSQL = "DELETE FROM vms.vms_trns_amnt_brkdwn WHERE trans_det_ln_id = " . $pkeyID . "";
        $affctd2 = execUpdtInsSQL($insSQL, "Trns Number:" . $extrInfo . ":Type:" . $srcDocType);
        $insSQL = "DELETE FROM vms.vms_transaction_lines WHERE trans_det_ln_id = " . $pkeyID;
        $affctd3 = execUpdtInsSQL($insSQL, "Trns Number:" . $extrInfo . ":Type:" . $srcDocType);
    }
    if ($affctd3 > 0) {
        $dsply = "Successfully Deleted the ff Records-";
        $dsply .= "<br/>$affctd1 GL Interface Transaction(s)!";
        $dsply .= "<br/>$affctd2 Trns. Amount Breakdown(s)!";
        $dsply .= "<br/>$affctd3 Transaction Line(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Deleted<br/>Cannot Delete Authorized Transactions!";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function createVMSTrnsLine($trnsLnID, $trnsHdrID, $itmID, $srcVltID, $srcCageID, $destVltID, $destCageID, $docQty, $crncyID, $unitVal, $orgnlVoidedLnID, $lineRmrk, $srcAstAcntID, $destAstAcntID, $lbltyAcntID, $expenseAcntID, $funcCrncyExRate, $baseUomID, $cogsAcntID, $revnuAcntID, $srcItmStateClsfctn, $dstItmStateClsfctn, $line_desc = "")
{
    global $usrID;
    $dateStr = getDB_Date_time();
    $insSQL = "INSERT INTO vms.vms_transaction_lines(
            trans_det_ln_id, trans_hdr_id, itm_id, src_store_vault_id, src_cage_shelve_id, 
            dest_store_vault_id, dest_cage_shelve_id, doc_qty, crncy_id, 
            unit_value, validity_status, voided_src_line_id, is_itm_delivered, 
            alternate_item_name, src_asset_acct_id, dest_asset_acct_id, liability_accnt_id, 
            expense_accnt_id, cogs_accnt_id, revenue_accnt_id, pymt_cur_exchng_rate, 
            created_by, creation_date, last_update_by, last_update_date, 
            base_uom_id, src_itm_state_clsfctn, dst_itm_state_clsfctn";
    if ($srcCageID > 0) {
        $insSQL .= ", src_balance_b4_trns";
    }
    if ($destCageID > 0) {
        $insSQL .= ", dst_balance_b4_trns";
    }
    // $unitVal .
    $insSQL .= ", line_desc) VALUES (" . $trnsLnID .
        "," . $trnsHdrID .
        "," . $itmID .
        "," . $srcVltID .
        "," . $srcCageID .
        "," . $destVltID .
        "," . $destCageID .
        "," . $docQty .
        "," . $crncyID .
        ",vms.get_denom_unit_val(" . $itmID . ")" .
        ",'Not Validated', " . $orgnlVoidedLnID .
        ", '0', '" . loc_db_escape_string($lineRmrk) .
        "', " . $srcAstAcntID .
        "," . $destAstAcntID .
        "," . $lbltyAcntID .
        "," . $expenseAcntID .
        "," . $cogsAcntID .
        "," . $revnuAcntID .
        "," . $funcCrncyExRate .
        ", " . $usrID . ", '" . $dateStr .
        "', " . $usrID . ", '" . $dateStr .
        "', " . $baseUomID .
        ", '" . loc_db_escape_string($srcItmStateClsfctn) .
        "', '" . loc_db_escape_string($dstItmStateClsfctn) . "'";
    if ($srcCageID > 0) {
        $insSQL .= ", (vms.get_ltst_stock_bals(" . $srcVltID . ", " . $srcCageID . ", " . $itmID . ", '" . loc_db_escape_string($srcItmStateClsfctn) . "')* " . $unitVal . ")";
    }
    if ($destCageID > 0) {
        $insSQL .= ", (vms.get_ltst_stock_bals(" . $destVltID . ", " . $destCageID . ", " . $itmID . ", '" . loc_db_escape_string($dstItmStateClsfctn) . "')* " . $unitVal . ")";
    }
    $insSQL .= ", '" . loc_db_escape_string($line_desc) . "')";
    return execUpdtInsSQL($insSQL);
}

function updateVMSTrnsLine($trnsLnID, $itmID, $srcVltID, $srcCageID, $destVltID, $destCageID, $docQty, $crncyID, $unitVal, $lineRmrk, $srcAstAcntID, $destAstAcntID, $lbltyAcntID, $expenseAcntID, $funcCrncyExRate, $baseUomID, $cogsAcntID, $revnuAcntID, $srcItmStateClsfctn, $dstItmStateClsfctn, $line_desc = "")
{
    global $usrID;
    $dateStr = getDB_Date_time();
    if ($trnsLnID > 0) {
        $insSQL = "UPDATE vms.vms_transaction_lines
                    SET itm_id=" . $itmID . ", 
                    src_store_vault_id=" . $srcVltID . ", 
                    src_cage_shelve_id=" . $srcCageID . ", 
                    dest_store_vault_id=" . $destVltID . ", 
                    dest_cage_shelve_id=" . $destCageID . ", 
                    doc_qty=" . $docQty . ", 
                    crncy_id=" . $crncyID . ", 
                    unit_value=vms.get_denom_unit_val(" . $itmID . "),
                    alternate_item_name='" . loc_db_escape_string($lineRmrk) . "', 
                    src_asset_acct_id=" . $srcAstAcntID . ", 
                    dest_asset_acct_id=" . $destAstAcntID . ", 
                    liability_accnt_id=" . $lbltyAcntID . ", 
                    expense_accnt_id=" . $expenseAcntID . ", 
                    cogs_accnt_id=" . $cogsAcntID . ", 
                    revenue_accnt_id=" . $revnuAcntID . ", 
                    pymt_cur_exchng_rate=" . $funcCrncyExRate . ",
                    last_update_by=" . $usrID . ", 
                    last_update_date='" . $dateStr . "', 
                    base_uom_id=" . $baseUomID . ",
                    src_itm_state_clsfctn='" . loc_db_escape_string($srcItmStateClsfctn) . "',
                    dst_itm_state_clsfctn='" . loc_db_escape_string($dstItmStateClsfctn) . "'";
        if ($destCageID > 0) {
            $insSQL .= ", dst_balance_b4_trns=(vms.get_ltst_stock_bals(" . $destVltID . ", " . $destCageID . ", " . $itmID . ", '" . loc_db_escape_string($dstItmStateClsfctn) . "')* " . $unitVal . ")";
        }
        if ($srcCageID > 0) {
            $insSQL .= ", src_balance_b4_trns=(vms.get_ltst_stock_bals(" . $srcVltID . ", " . $srcCageID . ", " . $itmID . ", '" . loc_db_escape_string($srcItmStateClsfctn) . "')* " . $unitVal . ")";
        }
        $insSQL .= ", line_desc = '" . loc_db_escape_string($line_desc) . "' WHERE(trans_det_ln_id = " . $trnsLnID . ")";
        return execUpdtInsSQL($insSQL);
    } else {
        return 0;
    }
}

function deleteVMSPymtLine($pkeyID, $srcDocType, $extrInfo = "")
{
    $selSQL = "Select count(1) from vms.vms_gl_interface WHERE gl_batch_id>0 and src_doc_line_id = " . $pkeyID . " and trns_ln_type = '" . loc_db_escape_string($srcDocType) . "'";
    $result = executeSQLNoParams($selSQL);
    $trnsCnt = 0;
    $affctd1 = 0;
    $affctd2 = 0;
    $affctd3 = 0;
    while ($row = loc_db_fetch_array($result)) {
        $trnsCnt = $row[0];
    }
    if ($trnsCnt <= 0) {
        $insSQL = "DELETE FROM vms.vms_gl_interface WHERE src_doc_line_id = " . $pkeyID . " and trns_ln_type = '" . loc_db_escape_string($srcDocType) . "'";
        $affctd1 = execUpdtInsSQL($insSQL, "Trns Number:" . $extrInfo . ":Type:" . $srcDocType);
        $insSQL = "DELETE FROM vms.vms_trns_amnt_brkdwn WHERE trans_det_ln_id = " . $pkeyID . "";
        $affctd2 = execUpdtInsSQL($insSQL, "Trns Number:" . $extrInfo . ":Type:" . $srcDocType);
        $insSQL = "DELETE FROM vms.vms_transaction_pymnt WHERE trans_det_ln_id = " . $pkeyID;
        $affctd3 = execUpdtInsSQL($insSQL, "Trns Number:" . $extrInfo . ":Type:" . $srcDocType);
    }
    if ($affctd3 > 0) {
        $dsply = "Successfully Deleted the ff Records-";
        $dsply .= "<br/>$affctd1 GL Interface Transaction(s)!";
        $dsply .= "<br/>$affctd2 Trns. Amount Breakdown(s)!";
        $dsply .= "<br/>$affctd3 Payment Line(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Deleted<br/>Cannot Delete Authorized Transactions!";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function createVMSPymtLine($trnsLnID, $trnsHdrID, $itmID, $srcVltID, $srcCageID, $destVltID, $destCageID, $docQty, $crncyID, $unitVal, $orgnlVoidedLnID, $lineRmrk, $srcAstAcntID, $destAstAcntID, $lbltyAcntID, $expenseAcntID, $funcCrncyExRate, $baseUomID, $cogsAcntID, $revnuAcntID, $srcItmStateClsfctn, $dstItmStateClsfctn, $line_desc = "")
{
    global $usrID;
    $dateStr = getDB_Date_time();
    $insSQL = "INSERT INTO vms.vms_transaction_pymnt(
            trans_det_ln_id, trans_hdr_id, itm_id, src_store_vault_id, src_cage_shelve_id, 
            dest_store_vault_id, dest_cage_shelve_id, doc_qty, crncy_id, 
            unit_value, validity_status, voided_src_line_id, is_itm_delivered, 
            alternate_item_name, src_asset_acct_id, dest_asset_acct_id, liability_accnt_id, 
            expense_accnt_id, cogs_accnt_id, revenue_accnt_id, pymt_cur_exchng_rate, 
            created_by, creation_date, last_update_by, last_update_date, 
            base_uom_id, src_itm_state_clsfctn, dst_itm_state_clsfctn";
    if ($srcCageID > 0) {
        $insSQL .= ", src_balance_b4_trns";
    }
    if ($destCageID > 0) {
        $insSQL .= ", dst_balance_b4_trns";
    }
    $insSQL .= ", line_desc) " .
        "VALUES (" . $trnsLnID .
        ", " . $trnsHdrID .
        "," . $itmID .
        "," . $srcVltID .
        "," . $srcCageID .
        "," . $destVltID .
        "," . $destCageID .
        "," . $docQty .
        "," . $crncyID .
        ",vms.get_denom_unit_val(" . $itmID . "),'Not Validated', " . $orgnlVoidedLnID .
        ", '0', '" . loc_db_escape_string($lineRmrk) .
        "', " . $srcAstAcntID .
        "," . $destAstAcntID .
        "," . $lbltyAcntID .
        "," . $expenseAcntID .
        "," . $cogsAcntID .
        "," . $revnuAcntID .
        "," . $funcCrncyExRate .
        ", " . $usrID . ", '" . $dateStr .
        "', " . $usrID . ", '" . $dateStr .
        "', " . $baseUomID .
        ", '" . loc_db_escape_string($srcItmStateClsfctn) .
        "', '" . loc_db_escape_string($dstItmStateClsfctn) . "'";
    if ($srcCageID > 0) {
        $insSQL .= ", (vms.get_ltst_stock_bals(" . $srcVltID . ", " . $srcCageID . ", " . $itmID . ", '" . loc_db_escape_string($srcItmStateClsfctn) . "')* " . $unitVal . ")";
    }
    if ($destCageID > 0) {
        $insSQL .= ", (vms.get_ltst_stock_bals(" . $destVltID . ", " . $destCageID . ", " . $itmID . ", '" . loc_db_escape_string($dstItmStateClsfctn) . "')* " . $unitVal . ")";
    }
    $insSQL .= ", '" . loc_db_escape_string($line_desc) . "')";
    return execUpdtInsSQL($insSQL);
}

function updateVMSPymtLine($trnsLnID, $itmID, $srcVltID, $srcCageID, $destVltID, $destCageID, $docQty, $crncyID, $unitVal, $lineRmrk, $srcAstAcntID, $destAstAcntID, $lbltyAcntID, $expenseAcntID, $funcCrncyExRate, $baseUomID, $cogsAcntID, $revnuAcntID, $srcItmStateClsfctn, $dstItmStateClsfctn, $line_desc = "")
{
    global $usrID;
    $dateStr = getDB_Date_time();
    if ($trnsLnID > 0) {
        $insSQL = "UPDATE vms.vms_transaction_pymnt
                    SET itm_id=" . $itmID . ", 
                    src_store_vault_id=" . $srcVltID . ", 
                    src_cage_shelve_id=" . $srcCageID . ", 
                    dest_store_vault_id=" . $destVltID . ", 
                    dest_cage_shelve_id=" . $destCageID . ", 
                    doc_qty=" . $docQty . ", 
                    crncy_id=" . $crncyID . ", 
                    unit_value=vms.get_denom_unit_val(" . $itmID . "),
                    alternate_item_name='" . loc_db_escape_string($lineRmrk) . "', 
                    src_asset_acct_id=" . $srcAstAcntID . ", 
                    dest_asset_acct_id=" . $destAstAcntID . ", 
                    liability_accnt_id=" . $lbltyAcntID . ", 
                    expense_accnt_id=" . $expenseAcntID . ", 
                    cogs_accnt_id=" . $cogsAcntID . ", 
                    revenue_accnt_id=" . $revnuAcntID . ", 
                    pymt_cur_exchng_rate=" . $funcCrncyExRate . ",
                    last_update_by=" . $usrID . ", 
                    last_update_date='" . $dateStr . "', 
                    base_uom_id=" . $baseUomID . ",
                    src_itm_state_clsfctn='" . loc_db_escape_string($srcItmStateClsfctn) . "',
                    dst_itm_state_clsfctn='" . loc_db_escape_string($dstItmStateClsfctn) . "'";
        if ($destCageID > 0) {
            $insSQL .= ", dst_balance_b4_trns=(vms.get_ltst_stock_bals(" . $destVltID . ", " . $destCageID . ", " . $itmID . ", '" . loc_db_escape_string($dstItmStateClsfctn) . "')* " . $unitVal . ")";
        }
        if ($srcCageID > 0) {
            $insSQL .= ", src_balance_b4_trns=(vms.get_ltst_stock_bals(" . $srcVltID . ", " . $srcCageID . ", " . $itmID . ", '" . loc_db_escape_string($srcItmStateClsfctn) . "')* " . $unitVal . ")";
        }
        $insSQL .= ", line_desc='" . loc_db_escape_string($line_desc) . "'
       WHERE(trans_det_ln_id = " . $trnsLnID . ")";
        return execUpdtInsSQL($insSQL);
    } else {
        return 0;
    }
}

function getAccountLovCrncyID($acctID)
{
    $strSql = "SELECT mcf.get_accnt_lov_crncy_id($acctID)";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return (int) $row[0];
    }
    return -1;
}

function delVmsExpnsTrnsLine($pkeyID, $srcDocType, $extrInfo = "")
{
    $selSQL = "Select count(1) from vms.vms_gl_interface WHERE gl_batch_id>0 and src_doc_line_id = " . $pkeyID . " and trns_ln_type = '" . loc_db_escape_string($srcDocType) . "'";
    $result = executeSQLNoParams($selSQL);
    $trnsCnt = 0;
    $affctd1 = 0;
    $affctd2 = 0;
    $affctd3 = 0;
    while ($row = loc_db_fetch_array($result)) {
        $trnsCnt = $row[0];
    }
    if ($trnsCnt <= 0) {
        $insSQL = "DELETE FROM vms.vms_gl_interface WHERE src_doc_line_id = " . $pkeyID . " and trns_ln_type = '" . loc_db_escape_string($srcDocType) . "'";
        $affctd1 = execUpdtInsSQL($insSQL, "Acnt Number:" . $extrInfo . ":Type:" . $srcDocType);

        $insSQL = "DELETE FROM accb.accb_ptycsh_amnt_smmrys WHERE ptycsh_smmry_id = " . $pkeyID;
        $affctd3 = execUpdtInsSQL($insSQL, "Acnt Number:" . $extrInfo . ":Type:" . $srcDocType);
    }
    if ($affctd3 > 0) {
        $dsply = "Successfully Deleted the ff Records-";
        $dsply .= "<br/>$affctd1 GL Interface Transaction(s)!";
        $dsply .= "<br/>$affctd3 Vault/GL Account Transfer(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Deleted<br/>Cannot Delete Authorized Transactions!";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function createVMSExpnsTrnsLine($lineRmrk, $ptyCshAstAcntID, $expenseAcntID, $entrdCrcnyID, $fncCnrcyID, $acntCrncyID, $funcCrncyExRate, $acntCrncyExRate, $trnsAmnt, $vmsTrnsHdrID, $trnsTypIdx)
{
    global $usrID;
    $dateStr = getDB_Date_time();
    $incrDcrs1 = "Increase";
    $incrDcrs2 = "Decrease";
    if ($trnsTypIdx == 13) {
        $incrDcrs1 = "Decrease";
        $incrDcrs2 = "Increase";
    }
    $insSQL = "INSERT INTO accb.accb_ptycsh_amnt_smmrys(
            ptycsh_smmry_type, ptycsh_smmry_desc, ptycsh_smmry_amnt, 
            code_id_behind, src_ptycsh_type, src_ptycsh_hdr_id, created_by, 
            creation_date, last_update_by, last_update_date, auto_calc, incrs_dcrs1, 
            asset_expns_acnt_id, incrs_dcrs2, liability_acnt_id, appld_prepymnt_doc_id, 
            validty_status, orgnl_line_id, entrd_curr_id, func_curr_id, accnt_curr_id, 
            func_curr_rate, accnt_curr_rate, func_curr_amount, accnt_curr_amnt, 
            initial_amnt_line_id, lnkd_vms_trns_hdr_id) " .
        "VALUES ('1Initial Amount'"
        . ", '" . loc_db_escape_string($lineRmrk) .
        "', " . $trnsAmnt .
        ", -1, 'Petty Cash Payments', -1, " . $usrID .
        ", '" . $dateStr .
        "', " . $usrID .
        ", '" . $dateStr .
        "', '0', '" . $incrDcrs1 . "', " . $expenseAcntID .
        ", '" . $incrDcrs2 . "', " . $ptyCshAstAcntID .
        ", -1, 'VALID', -1, " . $entrdCrcnyID .
        ", " . $fncCnrcyID .
        ", " . $acntCrncyID .
        ", " . $funcCrncyExRate .
        ", " . $acntCrncyExRate .
        ", " . ($funcCrncyExRate * $trnsAmnt) .
        ", " . ($acntCrncyExRate * $trnsAmnt) .
        ",-1, " . $vmsTrnsHdrID . ")";
    return execUpdtInsSQL($insSQL);
}

function updateVMSExpnsTrnsLine($trnsLnID, $lineRmrk, $ptyCshAstAcntID, $expenseAcntID, $entrdCrcnyID, $fncCnrcyID, $acntCrncyID, $funcCrncyExRate, $acntCrncyExRate, $trnsAmnt, $vmsTrnsHdrID, $trnsTypIdx)
{
    global $usrID;
    $dateStr = getDB_Date_time();
    $incrDcrs1 = "Increase";
    $incrDcrs2 = "Decrease";
    if ($trnsTypIdx == 13) {
        $incrDcrs1 = "Decrease";
        $incrDcrs2 = "Increase";
    }
    $insSQL = "UPDATE accb.accb_ptycsh_amnt_smmrys
   SET ptycsh_smmry_desc='" . loc_db_escape_string($lineRmrk) .
        "', ptycsh_smmry_amnt=" . $trnsAmnt .
        ", last_update_by=" . $usrID .
        ", last_update_date='" . $dateStr .
        "', incrs_dcrs1='" . loc_db_escape_string($incrDcrs1) .
        "', asset_expns_acnt_id=" . $expenseAcntID .
        ", incrs_dcrs2='" . loc_db_escape_string($incrDcrs2) .
        "', liability_acnt_id= " . $ptyCshAstAcntID .
        ", entrd_curr_id=" . $entrdCrcnyID .
        ", func_curr_id=" . $fncCnrcyID .
        ", accnt_curr_id=" . $acntCrncyID .
        ", func_curr_rate=" . $funcCrncyExRate .
        ", accnt_curr_rate=" . $acntCrncyExRate .
        ", func_curr_amount=" . ($funcCrncyExRate * $trnsAmnt) .
        ", accnt_curr_amnt=" . ($acntCrncyExRate * $trnsAmnt) .
        ", initial_amnt_line_id=" . $vmsTrnsHdrID . " WHERE ptycsh_smmry_id = " . $trnsLnID . "";
    return execUpdtInsSQL($insSQL);
}

function getLatestSiteID($prsnID)
{
    $strSql = "SELECT pasn.get_prsn_siteid(" . $prsnID . ") ";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return -1;
}

function getLatestCage($prsnID, &$cageID, &$vltID, &$invAssetAcntID, $crncyID = -1)
{
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

function deleteDocGLInfcLns($docID, $srcDocType)
{
    $delSQL = "DELETE FROM vms.vms_gl_interface WHERE src_doc_id = " .
        $docID . " and src_doc_typ ilike '%" . loc_db_escape_string($srcDocType) . "%' and gl_batch_id <= 0";
    return execUpdtInsSQL($delSQL);
}

function deleteGLInfcLine($intfcID)
{
    $delSQL = "DELETE FROM vms.vms_gl_interface WHERE interface_id = " .
        $intfcID . " and gl_batch_id <= 0";
    return execUpdtInsSQL($delSQL);
}

function getDocGLInfcLns($docID, $srcDocType)
{
    $strSql = "SELECT * FROM vms.vms_gl_interface WHERE src_doc_id = " .
        $docID . " and src_doc_typ ilike '%" . loc_db_escape_string($srcDocType) . "%' and gl_batch_id != -1";
    return executeSQLNoParams($strSql);
}

function createVMSTrnsGLIntFcLn($accntid, $trnsdesc, $dbtamnt, $trnsdte, $crncyid, $crdtamnt, $netamnt, $srcDocTyp, $srcDocID, $srcDocLnID, $dateStr, $trnsLnTyp, $trnsSrc, $entrdAMnt, $entrdCrncyID, $acctCrncyAmnt, $acctCrncyID, $funcCrncyRate, $acntCrncyRate)
{
    global $usrID;
    if ($accntid <= 0) {
        return;
    }
    if ($trnsdte != "") {
        $trnsdte = cnvrtDMYTmToYMDTm($trnsdte);
    }
    if ($dateStr != "") {
        $dateStr = cnvrtDMYTmToYMDTm($dateStr);
    }
    $insSQL = "INSERT INTO vms.vms_gl_interface(
            accnt_id, transaction_desc, dbt_amount, trnsctn_date, 
            func_cur_id, created_by, creation_date, crdt_amount, last_update_by, 
            last_update_date, net_amount, gl_batch_id, src_doc_typ, src_doc_id, 
            src_doc_line_id, trns_ln_type, trns_source, entered_amnt, entered_amt_crncy_id, 
            accnt_crncy_amnt, accnt_crncy_id, func_cur_exchng_rate, accnt_cur_exchng_rate) " .
        "VALUES (" . $accntid .
        ", '" . loc_db_escape_string($trnsdesc) .
        "', " . $dbtamnt .
        ", '" . loc_db_escape_string($trnsdte) .
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

function updateVMSTrnsGLIntFcLn($intrfcLineID, $accntid, $trnsdesc, $dbtamnt, $trnsdte, $crncyid, $crdtamnt, $netamnt, $srcDocTyp, $srcDocID, $srcDocLnID, $dateStr, $trnsLnTyp, $trnsSrc, $entrdAMnt, $entrdCrncyID, $acctCrncyAmnt, $acctCrncyID, $funcCrncyRate, $acntCrncyRate)
{
    global $usrID;
    global $gnrlTrnsDteYMD;
    if ($accntid <= 0) {
        return;
    }
    /* if ($trnsdte != "") {
      $trnsdte = cnvrtDMYTmToYMDTm($trnsdte);
      } */
    //$trnsdte = $gnrlTrnsDteYMD;
    if ($dateStr != "") {
        $dateStr = cnvrtDMYTmToYMDTm($dateStr);
    }
    $insSQL = "UPDATE vms.vms_gl_interface
            SET accnt_id=" . $accntid .
        ", transaction_desc='" . loc_db_escape_string($trnsdesc) .
        "', dbt_amount=" . $dbtamnt .
        ", trnsctn_date='" . loc_db_escape_string($gnrlTrnsDteYMD) .
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
        " WHERE interface_id=" . $intrfcLineID . " and gl_batch_id<=0 and trns_source='USR'";
    return execUpdtInsSQL($insSQL);
}

function deleteVMSTrnsGLIntFcLn($intrfcLineID, $intrfcDesc)
{
    $delSQL = "DELETE FROM vms.vms_gl_interface WHERE interface_id = " . $intrfcLineID . " and gl_batch_id<=0 and trns_source!='SYS'";
    return execUpdtInsSQL($delSQL, $intrfcDesc);
}

function get_Fresh_VMSTrnsLines($dochdrID, $crncyID, $dstCageID, $excludedItems = "", $dstVltID = -1, $dstItemState = "Issuable", $srcCageID = -1, $srcVltID = -1, $srcItemState = "Issuable", $itmID = -1, $catgryNm = "Note")
{
    $extrWhere = " and d.cat_name ilike '" . loc_db_escape_string($catgryNm) . "'";
    if ($itmID > 0) {
        $extrWhere .= " and b.item_id = " . $itmID;
    } else if ($excludedItems != "") {
        $extrWhere .= " and b.item_id NOT IN (" . $excludedItems . ")";
    }
    $strSql = "SELECT -1, $dochdrID, b.item_id, -1, -1, -1, $dstCageID, 0, b.value_price_crncy_id, 
                   b.orgnl_selling_price, (1 * b.orgnl_selling_price) amnt, 'VALID', -1, '0', 
                   b.item_desc, -1, -1, -1, -1, -1, -1, 1, 
                   b.created_by, b.creation_date, b.last_update_by, b.last_update_date, 
                   b.base_uom_id, 'Issuable', b.base_uom_id, b.item_code, b.item_desc, 
                  c.uom_name, d.cat_name, b.item_type, 'Issuable', 
                  (vms.get_ltst_stock_bals($srcVltID, $srcCageID, b.item_id::integer, '$srcItemState') * b.orgnl_selling_price) rnngbals, 
                      '' linedesc, 
                  (vms.get_ltst_stock_bals($dstVltID, $dstCageID, b.item_id::integer, '$dstItemState') * b.orgnl_selling_price) rnngbals1  
              FROM inv.inv_itm_list b, inv.unit_of_measure c, inv.inv_product_categories d
              WHERE (b.value_price_crncy_id=" . $crncyID . " "
        . "and b.base_uom_id = c.uom_id and d.cat_id = b.category_id "
        . "and b.auto_dflt_in_vms_trns='1'" . $extrWhere . ") "
        . "ORDER BY b.orgnl_selling_price DESC, b.category_id, b.item_code";
    //echo $strSql;
    return executeSQLNoParams($strSql);
}

function get_All_Crncies($crncyID, $itmCtgry = "Note")
{
    $extrWhere = " and b.item_type ilike '%Cash%' and d.cat_name ilike '" . loc_db_escape_string($itmCtgry) . "'";
    $strSql = "SELECT b.item_id, b.value_price_crncy_id, 
                   b.orgnl_selling_price, (1 * b.orgnl_selling_price) amnt, 
                   b.item_desc, b.base_uom_id, b.item_code, b.item_desc, 
                  c.uom_name, d.cat_name, b.item_type
              FROM inv.inv_itm_list b, inv.unit_of_measure c, inv.inv_product_categories d
              WHERE (b.value_price_crncy_id=" . $crncyID . " "
        . "and b.base_uom_id = c.uom_id and d.cat_id = b.category_id "
        . "and b.auto_dflt_in_vms_trns='1'" . $extrWhere . ") "
        . "ORDER BY b.orgnl_selling_price DESC, b.category_id, b.item_code";
    return executeSQLNoParams($strSql);
}

function get_One_PtCshExpnsLines($dochdrID)
{
    $extrWhere = " and (a.ptycsh_smmry_type != '6Grand Total' and 
a.ptycsh_smmry_type != '7Total Payments Made' and a.ptycsh_smmry_type !='8Outstanding Balance')";

    $strSql = "SELECT ptycsh_smmry_id, ptycsh_smmry_type, ptycsh_smmry_desc, ptycsh_smmry_amnt, 
       code_id_behind, auto_calc, incrs_dcrs1, 
       asset_expns_acnt_id, incrs_dcrs2, liability_acnt_id, appld_prepymnt_doc_id, 
       entrd_curr_id, gst.get_pssbl_val(a.entrd_curr_id), 
       func_curr_id, gst.get_pssbl_val(a.func_curr_id), 
      accnt_curr_id, gst.get_pssbl_val(a.accnt_curr_id), 
      func_curr_rate, accnt_curr_rate, 
       func_curr_amount, accnt_curr_amnt, initial_amnt_line_id,
                accb.get_accnt_num(asset_expns_acnt_id) || '.' || accb.get_accnt_name(asset_expns_acnt_id) expns_acc,
       src_ptycsh_type, src_ptycsh_hdr_id, lnkd_vms_trns_hdr_id
  FROM accb.accb_ptycsh_amnt_smmrys a 
           WHERE((a.lnkd_vms_trns_hdr_id = " . $dochdrID . ")" . $extrWhere . ") ORDER BY ptycsh_smmry_type ASC, ptycsh_smmry_id";
    //echo $strSql;
    return executeSQLNoParams($strSql);
}

function get_One_VMSTrnsLines($dochdrID, $itmID = -1, $catgryNm = "%")
{
    $extrWhere = " and d.cat_name ilike '" . loc_db_escape_string($catgryNm) . "'";
    if ($itmID > 0) {
        $extrWhere .= " and b.item_id = " . $itmID;
    }
    $strSql = "SELECT a.trans_det_ln_id, a.trans_hdr_id, a.itm_id, a.src_store_vault_id, a.src_cage_shelve_id, 
                    a.dest_store_vault_id, a.dest_cage_shelve_id, a.doc_qty, a.crncy_id, 
                    a.unit_value, (a.doc_qty * a.unit_value) amnt, a.validity_status, a.voided_src_line_id, a.is_itm_delivered, 
                    a.alternate_item_name, a.src_asset_acct_id, a.dest_asset_acct_id, a.liability_accnt_id, 
                    a.expense_accnt_id, a.cogs_accnt_id, a.revenue_accnt_id, a.pymt_cur_exchng_rate, 
                    a.created_by, a.creation_date, a.last_update_by, a.last_update_date, 
                    a.base_uom_id, a.src_itm_state_clsfctn, b.base_uom_id, b.item_code, b.item_desc, 
                   c.uom_name, d.cat_name, b.item_type, a.dst_itm_state_clsfctn, 
                   CASE WHEN a.src_cage_shelve_id>0 THEN a.src_balance_b4_trns ELSE a.dst_balance_b4_trns END, 
                   a.line_desc, 
                   CASE WHEN a.dest_cage_shelve_id>0 THEN a.dst_balance_b4_trns ELSE a.src_balance_b4_trns END, 
                    inv.get_store_name(a.src_store_vault_id) srcVltNm,
                   inv.get_shelve_name(a.src_cage_shelve_id) srcCageNm,
                   inv.get_store_name(a.dest_store_vault_id) destVltNm,
                   inv.get_shelve_name(a.dest_cage_shelve_id) destCageNm,
                   a.src_balance_b4_trns,
                   a.dst_balance_b4_trns,
                    a.src_balance_afta_trns,
                    a.dst_balance_afta_trns, 
        inv.get_uom_qty(b.item_id::integer, a.doc_qty, 'tray') tray,
        inv.get_uom_qty(b.item_id::integer, a.doc_qty, 'bundle') bundle 
               FROM vms.vms_transaction_lines a, 
                    inv.inv_itm_list b, 
                    inv.unit_of_measure c, 
                    inv.inv_product_categories d
               WHERE(a.trans_hdr_id= " . $dochdrID . " and a.trans_hdr_id>0 and a.itm_id = b.item_id"
        . " and b.base_uom_id = c.uom_id and d.cat_id = b.category_id" . $extrWhere . ") "
        . "ORDER BY a.unit_value DESC, a.itm_id, a.trans_det_ln_id, b.category_id";
    //echo $itmID.$strSql;
    return executeSQLNoParams($strSql);
}

function get_One_VMSTrnsPymntLines($dochdrID)
{
    $strSql = "SELECT a.trans_det_ln_id, a.trans_hdr_id, a.itm_id, a.src_store_vault_id, a.src_cage_shelve_id, 
                    a.dest_store_vault_id, a.dest_cage_shelve_id, a.doc_qty, a.crncy_id, 
                    a.unit_value, (a.doc_qty * a.unit_value) amnt, a.validity_status, a.voided_src_line_id, a.is_itm_delivered, 
                    a.alternate_item_name, a.src_asset_acct_id, a.dest_asset_acct_id, a.liability_accnt_id, 
                    a.expense_accnt_id, a.cogs_accnt_id, a.revenue_accnt_id, a.pymt_cur_exchng_rate, 
                    a.created_by, a.creation_date, a.last_update_by, a.last_update_date, 
                    a.base_uom_id, a.src_itm_state_clsfctn, b.base_uom_id, b.item_code, b.item_desc, 
                    c.uom_name, d.cat_name, b.item_type, a.dst_itm_state_clsfctn, 
                   CASE WHEN a.src_cage_shelve_id>0 THEN a.src_balance_b4_trns ELSE a.dst_balance_b4_trns END, 
                    a.line_desc, 
                   CASE WHEN a.dest_cage_shelve_id>0 THEN a.dst_balance_b4_trns ELSE a.src_balance_b4_trns END, 
                    inv.get_store_name(a.src_store_vault_id) srcVltNm,
                   inv.get_shelve_name(a.src_cage_shelve_id) srcCageNm,
                   inv.get_store_name(a.dest_store_vault_id) destVltNm,
                   inv.get_shelve_name(a.dest_cage_shelve_id) destCageNm,
                   a.src_balance_b4_trns,
                   a.dst_balance_b4_trns   
               FROM vms.vms_transaction_pymnt a, inv.inv_itm_list b, 
               inv.unit_of_measure c, inv.inv_product_categories d
               WHERE(a.trans_hdr_id= " . $dochdrID .
        " and a.trans_hdr_id>0 and a.itm_id = b.item_id and b.base_uom_id = c.uom_id" .
        " and d.cat_id = b.category_id) ORDER BY a.unit_value DESC, a.itm_id, a.trans_det_ln_id, b.category_id";
    //echo $strSql;
    return executeSQLNoParams($strSql);
}

function get_One_VMSTrnsPymntCages($dochdrID)
{
    $strSql = "SELECT a.src_store_vault_id, 
                    a.src_cage_shelve_id, 
                    a.dest_store_vault_id, 
                    a.dest_cage_shelve_id,
                    inv.get_store_name(a.src_store_vault_id) srcVltNm,
                    inv.get_shelve_name(a.src_cage_shelve_id) srcCageNm,
                    inv.get_store_name(a.dest_store_vault_id) destVltNm,
                    inv.get_shelve_name(a.dest_cage_shelve_id) destCageNm
               FROM vms.vms_transaction_pymnt a
               WHERE(a.trans_hdr_id= " . $dochdrID .
        " and a.trans_hdr_id>0 and (a.src_cage_shelve_id>0 or a.dest_cage_shelve_id>0)) ORDER BY a.trans_det_ln_id LIMIT 1 OFFSET 0";
    return executeSQLNoParams($strSql);
}

function rvrsImprtdIntrfcTrns($docID, $doctype)
{
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
        createVMSTrnsGLIntFcLn($accntID, "(Reversal)" . $row[2], -1 * $dbtamount, $trnsdte, $crncy_id, -1 * $crdtamount, -1 * $netamnt, $row[13], $srcDocID, $srcDocLnID, $dateStr, $trnsLnTyp, $trnsSrc, $entrdAMnt, $entrdCrncyID, $acctCrncyAmnt, $acctCrncyID, $funcCrncyRate, $acntCrncyRate);
    }
    return true;
}

function validateVMSAccntng($docHdrID, &$errMsg)
{
    global $orgID;
    global $gnrlTrnsDteDMYHMS;
    try {
        $sccs = FALSE;
        $onlyValidate = "YES";
        $dateStr = getFrmtdDB_Date_time();
        $doctype = "";
        $docNum = "";
        $cstmrNm = "";
        $docDesc = "";
        $rsltAcc = get_One_VMSTrnsHdr($docHdrID);
        $entrdRate = 0;
        if ($rwAcc = loc_db_fetch_array($rsltAcc)) {
            $doctype = $rwAcc[3];
            $docNum = $rwAcc[2];
            $cstmrNm = $rwAcc[6];
            $docDesc = $rwAcc[4];
            $entrdRate = (float) $rwAcc[35];
        }
        $fnccurid = getOrgFuncCurID($orgID);
        $result = get_One_VMSTrnsLines($docHdrID);
        while ($row = loc_db_fetch_array($result)) {
            $itmID = (int) $row[2];
            $itmDesc = $row[29] . " (" . $row[28] . " " .
                $row[14] . ")";
            $srcStoreID = (int) $row[3];
            $srcCageID = (int) $row[4];
            $srcVltCageNm = getGnrlRecNm("inv.inv_itm_subinventories", "subinv_id", "subinv_name", $srcStoreID) . " (" .
                getGnrlRecNm("inv.inv_shelf", "line_id", "shelve_name", $srcCageID) . ")";
            $destStoreID = (int) $row[5];
            $destCageID = (int) $row[6];
            $destVltCageNm = getGnrlRecNm("inv.inv_itm_subinventories", "subinv_id", "subinv_name", $destStoreID) . " (" .
                getGnrlRecNm("inv.inv_shelf", "line_id", "shelve_name", $destCageID) . ")";

            $crncyID = (int) $row[8];
            $srclnID = (float) $row[12];
            $qty = (float) $row[7];
            $price = (float) $row[9];
            $lineid = (float) $row[0];
            $srcInvAcntID = (int) $row[15];
            $destInvAcntID = (int) $row[16];
            $cogsID = (int) $row[19];
            $salesRevID = (int) $row[20];
            $lbltyAccntID = (int) $row[17];
            $expnsID = (int) $row[18];
            $itmType = $row[33];
            $trnsLnTyp = "";
            $trnsSrc = "SYS";
            $isTrnsPymnt = false;
            if ($itmID > 0) {
                $sccs = generateItmAccntng($fnccurid, $qty, $doctype, $docHdrID, $srcInvAcntID, $destInvAcntID, $lbltyAccntID, $expnsID, $cogsID, $salesRevID, $price, $crncyID, $entrdRate, $lineid, $gnrlTrnsDteDMYHMS, $docNum, $cstmrNm, $docDesc, $itmDesc, $itmType, $trnsLnTyp, $trnsSrc, $isTrnsPymnt, $srcVltCageNm, $destVltCageNm, $dateStr, $errMsg, $onlyValidate);
                if ($sccs == false) {
                    return $sccs;
                }
            }
        }
        //Check for Trns Payment
        $result1 = get_One_VMSTrnsPymntLines($docHdrID);
        while ($row1 = loc_db_fetch_array($result1)) {
            $itmID = (int) $row1[2];
            $itmDesc = $row1[29] . " (" . $row1[28] . " " .
                $row1[14] . ")";
            $srcStoreID = (int) $row1[3];
            $srcCageID = (int) $row1[4];
            $destStoreID = (int) $row1[5];
            $destCageID = (int) $row1[6];
            $srcVltCageNm = getGnrlRecNm("inv.inv_itm_subinventories", "subinv_id", "subinv_name", $srcStoreID) . " (" .
                getGnrlRecNm("inv.inv_shelf", "line_id", "shelve_name", $srcCageID) . ")";
            $destVltCageNm = getGnrlRecNm("inv.inv_itm_subinventories", "subinv_id", "subinv_name", $destStoreID) . " (" .
                getGnrlRecNm("inv.inv_shelf", "line_id", "shelve_name", $destCageID) . ")";

            $crncyID = (int) $row1[8];
            $srclnID = (float) $row1[12];
            $qty = (float) $row1[7];
            $price = (float) $row1[9];
            $lineid = (float) $row1[0];
            $srcInvAcntID = (int) $row1[15];
            $destInvAcntID = (int) $row1[16];
            $cogsID = (int) $row1[19];
            $salesRevID = (int) $row1[20];
            $lbltyAccntID = (int) $row1[17];
            $expnsID = (int) $row1[18];
            $itmType = $row1[33];
            $trnsLnTyp = "";
            $trnsSrc = "SYS";
            $isTrnsPymnt = true;
            if ($itmID > 0) {
                $sccs = generateItmAccntng($fnccurid, $qty, $doctype, $docHdrID, $srcInvAcntID, $destInvAcntID, $lbltyAccntID, $expnsID, $cogsID, $salesRevID, $price, $crncyID, $entrdRate, $lineid, $gnrlTrnsDteDMYHMS, $docNum, $cstmrNm, $docDesc, $itmDesc, $itmType, $trnsLnTyp, $trnsSrc, $isTrnsPymnt, $srcVltCageNm, $destVltCageNm, $dateStr, $errMsg, $onlyValidate);
                if ($sccs == false) {
                    return $sccs;
                }
            }
        }
        return $sccs;
    } catch (Exception $e) {
        $errMsg = 'Caught exception: VALIDATE ACCNTS ' . $e->getMessage();
        return false;
    }
}

function createVMSAccntng($docHdrID, &$errMsg)
{
    global $orgID;
    global $usrID;
    global $gnrlTrnsDteDMYHMS;
    $doctype = "";
    $doctype2 = "";
    $docNum = "";
    $cstmrNm = "";
    $docDesc = "";
    try {
        $dateStr = getFrmtdDB_Date_time();
        $rsltAcc = get_One_VMSTrnsHdr($docHdrID);
        $entrdRate = 0;
        if ($rwAcc = loc_db_fetch_array($rsltAcc)) {
            $doctype = $rwAcc[3];
            $docNum = $rwAcc[2];
            $cstmrNm = $rwAcc[6];
            $docDesc = $rwAcc[4];
            $entrdRate = (float) $rwAcc[35];
        }
        $fnccurid = getOrgFuncCurID($orgID);
        $result = get_One_VMSTrnsLines($docHdrID);
        deleteDocGLInfcLns($docHdrID, $doctype);
        rvrsImprtdIntrfcTrns($docHdrID, $doctype);
        while ($row = loc_db_fetch_array($result)) {
            $itmID = (int) $row[2];
            $itmDesc = $row[29] . " (" . $row[28] . " " .
                $row[14] . ")";
            $srcStoreID = (int) $row[3];
            $srcCageID = (int) $row[4];
            $srcVltCageNm = getGnrlRecNm("inv.inv_itm_subinventories", "subinv_id", "subinv_name", $srcStoreID) . " (" .
                getGnrlRecNm("inv.inv_shelf", "line_id", "shelve_name", $srcCageID) . ")";
            $destStoreID = (int) $row[5];
            $destCageID = (int) $row[6];
            $destVltCageNm = getGnrlRecNm("inv.inv_itm_subinventories", "subinv_id", "subinv_name", $destStoreID) . " (" .
                getGnrlRecNm("inv.inv_shelf", "line_id", "shelve_name", $destCageID) . ")";

            $crncyID = (int) $row[8];
            $srclnID = (float) $row[12];
            $qty = (float) $row[7];
            $price = (float) $row[9];
            $lineid = (float) $row[0];
            $srcInvAcntID = (int) $row[15];
            $destInvAcntID = (int) $row[16];
            $cogsID = (int) $row[19];
            $salesRevID = (int) $row[20];
            $lbltyAccntID = (int) $row[17];
            $expnsID = (int) $row[18];
            $itmType = $row[33];
            $trnsLnTyp = "";
            $trnsSrc = "SYS";
            $isTrnsPymnt = false;
            if ($itmID > 0) {
                $sccs = generateItmAccntng($fnccurid, $qty, $doctype, $docHdrID, $srcInvAcntID, $destInvAcntID, $lbltyAccntID, $expnsID, $cogsID, $salesRevID, $price, $crncyID, $entrdRate, $lineid, $gnrlTrnsDteDMYHMS, $docNum, $cstmrNm, $docDesc, $itmDesc, $itmType, $trnsLnTyp, $trnsSrc, $isTrnsPymnt, $srcVltCageNm, $destVltCageNm, $dateStr, $errMsg);
                if ($sccs === false) {
                    return false;
                }
            }
        }
        //Check for Trns Payment
        $result1 = get_One_VMSTrnsPymntLines($docHdrID);
        while ($row1 = loc_db_fetch_array($result1)) {
            $itmID = (int) $row1[2];
            $itmDesc = $row1[29] . " (" . $row1[28] . " " .
                $row1[14] . ")";
            $srcStoreID = (int) $row1[3];
            $srcCageID = (int) $row1[4];
            $destStoreID = (int) $row1[5];
            $destCageID = (int) $row1[6];
            $srcVltCageNm = getGnrlRecNm("inv.inv_itm_subinventories", "subinv_id", "subinv_name", $srcStoreID) . " (" .
                getGnrlRecNm("inv.inv_shelf", "line_id", "shelve_name", $srcCageID) . ")";
            $destVltCageNm = getGnrlRecNm("inv.inv_itm_subinventories", "subinv_id", "subinv_name", $destStoreID) . " (" .
                getGnrlRecNm("inv.inv_shelf", "line_id", "shelve_name", $destCageID) . ")";

            $crncyID = (int) $row1[8];
            $srclnID = (float) $row1[12];
            $qty = (float) $row1[7];
            $price = (float) $row1[9];
            $lineid = (float) $row1[0];
            $srcInvAcntID = (int) $row1[15];
            $destInvAcntID = (int) $row1[16];
            $cogsID = (int) $row1[19];
            $salesRevID = (int) $row1[20];
            $lbltyAccntID = (int) $row1[17];
            $expnsID = (int) $row1[18];
            $itmType = $row1[33];
            $trnsLnTyp = "";
            $trnsSrc = "SYS";
            $isTrnsPymnt = true;
            if ($itmID > 0) {
                $sccs = generateItmAccntng($fnccurid, $qty, $doctype, $docHdrID, $srcInvAcntID, $destInvAcntID, $lbltyAccntID, $expnsID, $cogsID, $salesRevID, $price, $crncyID, $entrdRate, $lineid, $gnrlTrnsDteDMYHMS, $docNum, $cstmrNm, $docDesc, $itmDesc, $itmType, $trnsLnTyp, $trnsSrc, $isTrnsPymnt, $srcVltCageNm, $destVltCageNm, $dateStr, $errMsg);
                if ($sccs === false) {
                    return false;
                }
            }
        }
        //Check for Petty Cash Payments
        $result1 = get_One_PtCshExpnsLines($docHdrID);
        while ($row1 = loc_db_fetch_array($result1)) {
            $incrsDcrs1 = substr($row1[6], 0, 1);
            $inAccntID1 = (int) $row1[7];
            $incrsDcrs2 = substr($row1[8], 0, 1);
            $inAccntID2 = (int) $row1[9];
            $trnsAmnt = (float) $row1[3];
            $entrdCrncyID = (int) $row1[11];
            $entrdRate = 1;
            $doctype2 = $row1[23];
            $docHdrID = (float) $row1[25];
            $lineid = (float) $row1[0];
            $docDesc = $docNum . "-" . $row1[2];
            $sccs = generateGnrlVMSAcntng($incrsDcrs1, $inAccntID1, $incrsDcrs2, $inAccntID2, $trnsAmnt, $entrdCrncyID, $entrdRate, $doctype, $docHdrID, $lineid, $gnrlTrnsDteDMYHMS, $docDesc, $usrID, $orgID, $errMsg);
            if ($sccs === false) {
                return false;
            } else {
                $delSQL = "DELETE FROM vms.vms_gl_interface WHERE accnt_id=" . $inAccntID2 . " and src_doc_id = " .
                    $docHdrID . " and src_doc_typ ilike '%" . loc_db_escape_string($doctype) . "%' and gl_batch_id = -1";
                execUpdtInsSQL($delSQL);
            }
        }
        return true;
    } catch (Exception $e) {
        if (trim($doctype) !== "") {
            deleteDocGLInfcLns($docHdrID, $doctype);
            rvrsImprtdIntrfcTrns($docHdrID, $doctype);
        }
        $errMsg .= 'Caught exception: ' . $e->getMessage();
        return false;
    }
}

function sendToVMSGLIntrfcMnl($accntID, $incrsDcrs, $amount, $trns_date, $trns_desc, $crncy_id, $dateStr, $srcDocTyp, $srcDocID, $srcDocLnID, $trnsLnTyp, $trnsSrc, $entrdAMnt, $entrdCrncyID, $acctCrncyAmnt, $acctCrncyID, $funcCrncyRate, $acntCrncyRate, &$errMsg)
{
    try {
        $netamnt = dbtOrCrdtAccntMultiplier($accntID, $incrsDcrs) * $amount;
        $py_dbt_ln = -1;
        $py_crdt_ln = -1;
        if (dbtOrCrdtAccnt($accntID, $incrsDcrs) == "Debit") {
            if ($py_dbt_ln <= 0) {
                createVMSTrnsGLIntFcLn($accntID, $trns_desc, $amount, $trns_date, $crncy_id, 0, $netamnt, $srcDocTyp, $srcDocID, $srcDocLnID, $dateStr, $trnsLnTyp, $trnsSrc, $entrdAMnt, $entrdCrncyID, $acctCrncyAmnt, $acctCrncyID, $funcCrncyRate, $acntCrncyRate);
            }
        } else {
            if ($py_crdt_ln <= 0) {
                createVMSTrnsGLIntFcLn($accntID, $trns_desc, 0, $trns_date, $crncy_id, $amount, $netamnt, $srcDocTyp, $srcDocID, $srcDocLnID, $dateStr, $trnsLnTyp, $trnsSrc, $entrdAMnt, $entrdCrncyID, $acctCrncyAmnt, $acctCrncyID, $funcCrncyRate, $acntCrncyRate);
            }
        }
        return true;
    } catch (Exception $e) {
        $errMsg .= 'Caught exception: ' . $e->getMessage();
        return false;
    }
}

function generateItmAccntng($fnccurid, $qty, $doctype, $docHdrID, $srcInvAcntID, $destInvAcntID, $lbltyAccntID, $expnsID, $cogsID, $salesRevID, $price, $entrdCrncyID, $entrdRate, $lineid, $trnsDateStr, $docNum, $cstmrNm, $docDesc, $itmDesc, $itmType, $trnsLnTyp, $trnsSrc, $isTrnsPymnt, $srcVltCageNm, $destVltCageNm, $dateStr, &$errMsg, $onlyValidate = "NO")
{
    try {
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
        $funcCrncyRate = round(get_LtstExchRate($entrdCrncyID, $fnccurid, $trnsDateStr), 15);
        $acntCrncyRate = 1.00;
        $entrdAMnt = round($qty * $price, 2);
        if ($doctype == "Transits (Specie Movement)" || $doctype == "Teller/Cashier Transfers" || $doctype == "To Exam" || $doctype == "From Exam") {
            if ($srcInvAcntID <= 0 || $destInvAcntID <= 0) {
                $errMsg .= "Source and Destination Cage Accounts must be setup!<br/>";
                return false;
            }
        } else if ($doctype == "Deposits" || $doctype == "GL/Vault Account Transfers") {
            if ($destInvAcntID <= 0) {
                $errMsg .= "Destination Cage Account must be setup!<br/>";
                return false;
            }
            if (($doctype == "Deposits" && $lbltyAccntID <= 0)) {
                $errMsg .= "Customer Liability Accounts must be setup!<br/>";
                return false;
            }
            if (($doctype == "GL/Vault Account Transfers") && $expnsID <= 0) {
                $errMsg .= "Item Expense Accounts must be setup!<br/>";
                return false;
            }
        } else if ($doctype == "Withdrawals" || $doctype == "Currency Destruction" || $doctype == "Vault/GL Account Transfers") {
            if ($srcInvAcntID <= 0) {
                $errMsg .= "Source Cage Account must be setup!<br/>";
                return false;
            }
            if (($doctype == "Withdrawals" && $lbltyAccntID <= 0)) {
                $errMsg .= "Customer Liability Accounts must be setup!<br/>";
                return false;
            }
            if (($doctype == "Currency Destruction" || $doctype == "Vault/GL Account Transfers") && $expnsID <= 0) {
                $errMsg .= "Item Expense Accounts must be setup!<br/>";
                return false;
            }
        } else if ($doctype == "Currency Sale") {
            if ($isTrnsPymnt == false && ($srcInvAcntID <= 0 || $cogsID <= 0)) {
                $errMsg .= "Source Cage Account and Cost of Sales Accounts must be setup!<br/>";
                return false;
            }
            if ($isTrnsPymnt == true && ($destInvAcntID <= 0 || $salesRevID <= 0)) {
                $errMsg .= "Destination Cage Account and Sales Revenue Accounts must be setup!<br/>";
                return false;
            }
            if ($entrdRate <= 0) {
                $errMsg .= "Transaction Exchange Rate must be above zero!<br/>";
                return false;
            }
        } else if ($doctype == "Currency Purchase" || $doctype == "Currency Importation") {
            if ($isTrnsPymnt == false && ($destInvAcntID <= 0 || $lbltyAccntID <= 0)) {
                $errMsg .= "Destination Cage Account and Vendor Liability Accounts must be setup!<br/>";
                return false;
            }
            if ($isTrnsPymnt == true && ($srcInvAcntID <= 0 || $lbltyAccntID <= 0)) {
                $errMsg .= "Source Cage Account and Vendor Liability Accounts must be setup!<br/>";
                return false;
            }
            if ($entrdRate <= 0) {
                $errMsg .= "Transaction Exchange Rate must be above zero!<br/>";
                return false;
            }
        } else if ($doctype == "Miscellaneous Adjustments") {
            if ($isTrnsPymnt == false && ($srcInvAcntID <= 0 && $destInvAcntID <= 0)) {
                $errMsg .= "Source and Destination Cage Accounts cannot be both empty!<br/>";
                return false;
            }
        }
        if (strpos($itmType, "Inventory") !== FALSE || strpos($itmType, "Fixed Assets") !== FALSE || strpos($itmType, "Vault") !== FALSE) {
            $ttlCstPrice = $qty * $price * $funcCrncyRate;
            if ($doctype == "Transits (Specie Movement)" || $doctype == "Teller/Cashier Transfers" || $doctype == "To Exam" || $doctype == "From Exam") {
                $acctCrncyID = (int) getGnrlRecNm("accb.accb_chart_of_accnts", "accnt_id", "crncy_id", $srcInvAcntID);
                if ($entrdCrncyID !== $acctCrncyID) {
                    $errMsg .= "Transaction Currency ID is not the same as the Source Cage Account!<br/>";
                    return false;
                }
                $acctCrncyID1 = (int) getGnrlRecNm("accb.accb_chart_of_accnts", "accnt_id", "crncy_id", $destInvAcntID);
                if ($entrdCrncyID !== $acctCrncyID) {
                    $errMsg .= "Transaction Currency ID is not the same as the Destination Cage Account!<br/><br/>";
                    return false;
                }
                if ($onlyValidate == "YES") {
                    return true;
                }
            } else if ($doctype == "Deposits") {
                $acctCrncyID = (int) getGnrlRecNm("accb.accb_chart_of_accnts", "accnt_id", "crncy_id", $destInvAcntID);
                if ($entrdCrncyID !== $acctCrncyID) {
                    $errMsg .= "Transaction Currency ID is not the same as the Destination Cage Account!<br/><br/>";
                    return false;
                }
            } else if ($doctype == "Withdrawals" || $doctype == "Currency Destruction" || $doctype == "Vault/GL Account Transfers") {
                $acctCrncyID = (int) getGnrlRecNm("accb.accb_chart_of_accnts", "accnt_id", "crncy_id", $srcInvAcntID);
                if ($entrdCrncyID !== $acctCrncyID) {
                    $errMsg .= "Transaction Currency ID is not the same as the Destination Cage Account!<br/><br/>";
                    return false;
                }
            } else if ($doctype == "Miscellaneous Adjustments") {
                if ($srcInvAcntID > 0) {
                    $acctCrncyID = (int) getGnrlRecNm("accb.accb_chart_of_accnts", "accnt_id", "crncy_id", $srcInvAcntID);
                    if ($entrdCrncyID !== $acctCrncyID) {
                        $errMsg = "Transaction Currency ID is not the same as the Source Cage Account!<br/><br/>";
                        return false;
                    }
                }
                if ($destInvAcntID > 0) {
                    $acctCrncyID = (int) getGnrlRecNm("accb.accb_chart_of_accnts", "accnt_id", "crncy_id", $destInvAcntID);
                    if ($entrdCrncyID !== $acctCrncyID) {
                        $errMsg .= "Transaction Currency ID is not the same as the Destination Cage Account!<br/><br/>";
                        return false;
                    }
                }
            } else if ($doctype == "Currency Sale") {
                if ($isTrnsPymnt == false) {
                    $acctCrncyID = (int) getGnrlRecNm("accb.accb_chart_of_accnts", "accnt_id", "crncy_id", $srcInvAcntID);
                    if ($entrdCrncyID !== $acctCrncyID) {
                        $errMsg .= "Transaction Currency ID is not the same as the Source Cage Account!<br/><br/>";
                        return false;
                    }
                } else {
                    $acctCrncyID = (int) getGnrlRecNm("accb.accb_chart_of_accnts", "accnt_id", "crncy_id", $destInvAcntID);
                    if ($entrdCrncyID !== $acctCrncyID) {
                        $errMsg .= "Transaction Currency ID is not the same as the Destination Cage Account!<br/><br/>";
                        return false;
                    }
                }
            } else if ($doctype == "Currency Purchase" || $doctype == "Currency Importation") {
                if ($isTrnsPymnt == false) {
                    $acctCrncyID = (int) getGnrlRecNm("accb.accb_chart_of_accnts", "accnt_id", "crncy_id", $destInvAcntID);
                    if ($entrdCrncyID !== $acctCrncyID) {
                        $errMsg .= "Transaction Currency ID is not the same as the Destination Cage Account!<br/><br/>";
                        return false;
                    }
                } else {
                    $acctCrncyID = (int) getGnrlRecNm("accb.accb_chart_of_accnts", "accnt_id", "crncy_id", $srcInvAcntID);
                    if ($entrdCrncyID !== $acctCrncyID) {
                        $errMsg .= "Transaction Currency ID is not the same as the Destination Cage Account!<br/><br/>";
                        return false;
                    }
                }
            }
            if ($onlyValidate == "YES") {
                return true;
            }
            if ($doctype == "Transits (Specie Movement)" || $doctype == "Teller/Cashier Transfers" || $doctype == "To Exam" || $doctype == "From Exam") {
                $acntCrncyRate = round(1, 15);
                $acctCrncyAmnt = round($entrdAMnt * $acntCrncyRate, 2);
                if ($fnccurid != $acctCrncyID) {
                    $fncCurBals = getAccntLstDailyNetBals($srcInvAcntID, $trnsDateStr);
                    $accCurBals = getAccntCrncyLstDlyNetBals($srcInvAcntID, $trnsDateStr);
                    if ($accCurBals <= 0 && $fncCurBals != 0) {
                        $accCurBals = $fncCurBals;
                    } else {
                        $accCurBals = 1;
                    }
                    $avrgStckCost = round($fncCurBals / $accCurBals, 15);
                    $ttlCstPrice = $qty * $price * $avrgStckCost;
                    $funcCrncyRate = $avrgStckCost;
                }
                $succs = sendToVMSGLIntrfcMnl(
                    $srcInvAcntID,
                    "D",
                    $ttlCstPrice,
                    $trnsDateStr,
                    $docNum . "-Transfer of " . $itmDesc . " from " . $srcVltCageNm . " to " . $destVltCageNm . " (" . $docDesc . "-" . $doctype . ")",
                    $entrdCrncyID,
                    $dateStr,
                    $doctype,
                    $docHdrID,
                    $lineid,
                    $trnsLnTyp,
                    $trnsSrc,
                    $entrdAMnt,
                    $entrdCrncyID,
                    $acctCrncyAmnt,
                    $acctCrncyID,
                    $funcCrncyRate,
                    $acntCrncyRate,
                    $errMsg
                );
                if (!$succs) {
                    return $succs;
                } else {
                    $acctCrncyID1 = (int) getGnrlRecNm("accb.accb_chart_of_accnts", "accnt_id", "crncy_id", $destInvAcntID);
                    $acntCrncyRate1 = round(1, 15);
                    $acctCrncyAmnt = round($entrdAMnt * $acntCrncyRate1, 2);
                    $succs = sendToVMSGLIntrfcMnl(
                        $destInvAcntID,
                        "I",
                        $ttlCstPrice,
                        $trnsDateStr,
                        $docNum . "-Transfer of " . $itmDesc . " from " . $srcVltCageNm . " to " . $destVltCageNm . " (" . $docDesc . "-" . $doctype . ")",
                        $entrdCrncyID,
                        $dateStr,
                        $doctype,
                        $docHdrID,
                        $lineid,
                        $trnsLnTyp,
                        $trnsSrc,
                        $entrdAMnt,
                        $entrdCrncyID,
                        $acctCrncyAmnt,
                        $acctCrncyID1,
                        $funcCrncyRate,
                        $acntCrncyRate1,
                        $errMsg
                    );
                    if (!$succs) {
                        return $succs;
                    }
                }
            } else if ($doctype == "Deposits" || $doctype == "GL/Vault Account Transfers") {
                $acctCrncyID = (int) getGnrlRecNm("accb.accb_chart_of_accnts", "accnt_id", "crncy_id", $destInvAcntID);
                $acntCrncyRate = round(1, 15);
                $acctCrncyAmnt = round($entrdAMnt * $acntCrncyRate, 2);
                $succs = sendToVMSGLIntrfcMnl($destInvAcntID, "I", $ttlCstPrice, $trnsDateStr, $docNum . "-Deposit of " . $itmDesc . " into " . $destVltCageNm . " by $cstmrNm (" . $docDesc . "-" . $doctype . ")", $entrdCrncyID, $dateStr, $doctype, $docHdrID, $lineid, $trnsLnTyp, $trnsSrc, $entrdAMnt, $entrdCrncyID, $acctCrncyAmnt, $acctCrncyID, $funcCrncyRate, $acntCrncyRate, $errMsg);
                if (!$succs) {
                    return $succs;
                } else {
                    if ($doctype == "Deposits") {
                        $acctCrncyID = (int) getGnrlRecNm("accb.accb_chart_of_accnts", "accnt_id", "crncy_id", $lbltyAccntID);
                        $acntCrncyRate = round(get_LtstExchRate($entrdCrncyID, $acctCrncyID, $trnsDateStr), 15);
                        $acctCrncyAmnt = round($entrdAMnt * $acntCrncyRate, 2);
                        $succs = sendToVMSGLIntrfcMnl(
                            $lbltyAccntID,
                            "I",
                            $ttlCstPrice,
                            $trnsDateStr,
                            $docNum . "-Deposit of " . $itmDesc . " into " . $destVltCageNm . " by $cstmrNm (" . $docDesc . "-" . $doctype . ")",
                            $entrdCrncyID,
                            $dateStr,
                            $doctype,
                            $docHdrID,
                            $lineid,
                            $trnsLnTyp,
                            $trnsSrc,
                            $entrdAMnt,
                            $entrdCrncyID,
                            $acctCrncyAmnt,
                            $acctCrncyID,
                            $funcCrncyRate,
                            $acntCrncyRate,
                            $errMsg
                        );
                        if (!$succs) {
                            return $succs;
                        }
                    } else {
                        $acctCrncyID = (int) getGnrlRecNm("accb.accb_chart_of_accnts", "accnt_id", "crncy_id", $expnsID);
                        $acntCrncyRate = round(get_LtstExchRate($entrdCrncyID, $acctCrncyID, $trnsDateStr), 15);
                        $acctCrncyAmnt = round($entrdAMnt * $acntCrncyRate, 2);
                        $succs = sendToVMSGLIntrfcMnl(
                            $expnsID,
                            "D",
                            $ttlCstPrice,
                            $trnsDateStr,
                            $docNum . "-" . $doctype . " of " . $itmDesc . " from " . $destVltCageNm . " (" . $docDesc . "-" . $doctype . ")",
                            $entrdCrncyID,
                            $dateStr,
                            $doctype,
                            $docHdrID,
                            $lineid,
                            $trnsLnTyp,
                            $trnsSrc,
                            $entrdAMnt,
                            $entrdCrncyID,
                            $acctCrncyAmnt,
                            $acctCrncyID,
                            $funcCrncyRate,
                            $acntCrncyRate,
                            $errMsg
                        );
                        if (!$succs) {
                            return $succs;
                        }
                    }
                }
            } else if ($doctype == "Withdrawals" || $doctype == "Currency Destruction" || $doctype == "Vault/GL Account Transfers") {
                $acctCrncyID = (int) getGnrlRecNm("accb.accb_chart_of_accnts", "accnt_id", "crncy_id", $srcInvAcntID);
                $acntCrncyRate = round(1, 15);
                $acctCrncyAmnt = round($entrdAMnt * $acntCrncyRate, 2);
                if ($fnccurid != $acctCrncyID) {
                    $fncCurBals = getAccntLstDailyNetBals($srcInvAcntID, $trnsDateStr);
                    $accCurBals = getAccntCrncyLstDlyNetBals($srcInvAcntID, $trnsDateStr);
                    if ($accCurBals <= 0 && $fncCurBals != 0) {
                        $accCurBals = $fncCurBals;
                    } else {
                        $accCurBals = 1;
                    }
                    $avrgStckCost = round($fncCurBals / $accCurBals, 15);
                    $ttlCstPrice = $qty * $price * $avrgStckCost;
                    $funcCrncyRate = $avrgStckCost;
                }
                $succs = sendToVMSGLIntrfcMnl(
                    $srcInvAcntID,
                    "D",
                    $ttlCstPrice,
                    $trnsDateStr,
                    $docNum . "-Removal of " . $itmDesc . " from " . $srcVltCageNm . " (" . $docDesc . "-" . $doctype . ")",
                    $entrdCrncyID,
                    $dateStr,
                    $doctype,
                    $docHdrID,
                    $lineid,
                    $trnsLnTyp,
                    $trnsSrc,
                    $entrdAMnt,
                    $entrdCrncyID,
                    $acctCrncyAmnt,
                    $acctCrncyID,
                    $funcCrncyRate,
                    $acntCrncyRate,
                    $errMsg
                );
                if (!$succs) {
                    return $succs;
                } else {
                    if ($doctype == "Withdrawals") {
                        $acctCrncyID = (int) getGnrlRecNm("accb.accb_chart_of_accnts", "accnt_id", "crncy_id", $lbltyAccntID);
                        $acntCrncyRate = round(get_LtstExchRate($entrdCrncyID, $acctCrncyID, $trnsDateStr), 15);
                        $acctCrncyAmnt = round($entrdAMnt * $acntCrncyRate, 2);
                        $succs = sendToVMSGLIntrfcMnl(
                            $lbltyAccntID,
                            "D",
                            $ttlCstPrice,
                            $trnsDateStr,
                            $docNum . "-Withdrawal of " . $itmDesc . " from " . $srcVltCageNm . " by $cstmrNm (" . $docDesc . "-" . $doctype . ")",
                            $entrdCrncyID,
                            $dateStr,
                            $doctype,
                            $docHdrID,
                            $lineid,
                            $trnsLnTyp,
                            $trnsSrc,
                            $entrdAMnt,
                            $entrdCrncyID,
                            $acctCrncyAmnt,
                            $acctCrncyID,
                            $funcCrncyRate,
                            $acntCrncyRate,
                            $errMsg
                        );
                        if (!$succs) {
                            return $succs;
                        }
                    } else {
                        $acctCrncyID = (int) getGnrlRecNm("accb.accb_chart_of_accnts", "accnt_id", "crncy_id", $expnsID);
                        $acntCrncyRate = round(get_LtstExchRate($entrdCrncyID, $acctCrncyID, $trnsDateStr), 15);
                        $acctCrncyAmnt = round($entrdAMnt * $acntCrncyRate, 2);
                        $succs = sendToVMSGLIntrfcMnl(
                            $expnsID,
                            "I",
                            $ttlCstPrice,
                            $trnsDateStr,
                            $docNum . "-" . $doctype . " of " . $itmDesc . " from " . $srcVltCageNm . " (" . $docDesc . "-" . $doctype . ")",
                            $entrdCrncyID,
                            $dateStr,
                            $doctype,
                            $docHdrID,
                            $lineid,
                            $trnsLnTyp,
                            $trnsSrc,
                            $entrdAMnt,
                            $entrdCrncyID,
                            $acctCrncyAmnt,
                            $acctCrncyID,
                            $funcCrncyRate,
                            $acntCrncyRate,
                            $errMsg
                        );
                        if (!$succs) {
                            return $succs;
                        }
                    }
                }
            } else if ($doctype == "Miscellaneous Adjustments") {
                if ($srcInvAcntID > 0) {
                    $acctCrncyID = (int) getGnrlRecNm("accb.accb_chart_of_accnts", "accnt_id", "crncy_id", $srcInvAcntID);
                    $acntCrncyRate = round(1, 15);
                    $acctCrncyAmnt = round($entrdAMnt * $acntCrncyRate, 2);
                    if ($fnccurid != $acctCrncyID) {
                        $fncCurBals = getAccntLstDailyNetBals($srcInvAcntID, $trnsDateStr);
                        $accCurBals = getAccntCrncyLstDlyNetBals($srcInvAcntID, $trnsDateStr);
                        if ($accCurBals <= 0 && $fncCurBals != 0) {
                            $accCurBals = $fncCurBals;
                        } else {
                            $accCurBals = 1;
                        }
                        $avrgStckCost = round($fncCurBals / $accCurBals, 15);
                        $ttlCstPrice = $qty * $price * $avrgStckCost;
                        $funcCrncyRate = $avrgStckCost;
                    }
                    $succs = sendToVMSGLIntrfcMnl(
                        $srcInvAcntID,
                        "D",
                        $ttlCstPrice,
                        $trnsDateStr,
                        $docNum . "-Correction of " . $itmDesc . " Balance in " . $srcVltCageNm . " (" . $docDesc . "-" . $doctype . ")",
                        $entrdCrncyID,
                        $dateStr,
                        $doctype,
                        $docHdrID,
                        $lineid,
                        $trnsLnTyp,
                        $trnsSrc,
                        $entrdAMnt,
                        $entrdCrncyID,
                        $acctCrncyAmnt,
                        $acctCrncyID,
                        $funcCrncyRate,
                        $acntCrncyRate,
                        $errMsg
                    );
                    if (!$succs) {
                        return $succs;
                    } else {
                        $acctCrncyID = (int) getGnrlRecNm("accb.accb_chart_of_accnts", "accnt_id", "crncy_id", $lbltyAccntID);
                        $acntCrncyRate = round(get_LtstExchRate($entrdCrncyID, $acctCrncyID, $trnsDateStr), 15);
                        $acctCrncyAmnt = round($entrdAMnt * $acntCrncyRate, 2);
                        $succs = sendToVMSGLIntrfcMnl(
                            $lbltyAccntID,
                            "D",
                            $ttlCstPrice,
                            $trnsDateStr,
                            $docNum . "-Adjustment of " . $itmDesc . " balance in " . $srcVltCageNm . " (" . $docDesc . "-" . $doctype . ")",
                            $entrdCrncyID,
                            $dateStr,
                            $doctype,
                            $docHdrID,
                            $lineid,
                            $trnsLnTyp,
                            $trnsSrc,
                            $entrdAMnt,
                            $entrdCrncyID,
                            $acctCrncyAmnt,
                            $acctCrncyID,
                            $funcCrncyRate,
                            $acntCrncyRate,
                            $errMsg
                        );
                        if (!$succs) {
                            return $succs;
                        }
                    }
                }
                if ($destInvAcntID > 0) {
                    $acctCrncyID = (int) getGnrlRecNm("accb.accb_chart_of_accnts", "accnt_id", "crncy_id", $destInvAcntID);
                    $acntCrncyRate = round(1, 15);
                    $acctCrncyAmnt = round($entrdAMnt * $acntCrncyRate, 2);
                    $succs = sendToVMSGLIntrfcMnl(
                        $destInvAcntID,
                        "I",
                        $ttlCstPrice,
                        $trnsDateStr,
                        $docNum . "-Adjustment of " . $itmDesc . " balance in " . $destVltCageNm . " (" . $docDesc . "-" . $doctype . ")",
                        $entrdCrncyID,
                        $dateStr,
                        $doctype,
                        $docHdrID,
                        $lineid,
                        $trnsLnTyp,
                        $trnsSrc,
                        $entrdAMnt,
                        $entrdCrncyID,
                        $acctCrncyAmnt,
                        $acctCrncyID,
                        $funcCrncyRate,
                        $acntCrncyRate,
                        $errMsg
                    );
                    if (!$succs) {
                        return $succs;
                    } else {
                        $acctCrncyID = (int) getGnrlRecNm("accb.accb_chart_of_accnts", "accnt_id", "crncy_id", $lbltyAccntID);
                        if ($entrdCrncyID !== $acctCrncyID) {
                            $errMsg .= "Transaction Currency ID is not the same as the Destination Cage Account!<br/><br/>";
                            return false;
                        }
                        $acntCrncyRate = round(get_LtstExchRate($entrdCrncyID, $acctCrncyID, $trnsDateStr), 15);
                        $acctCrncyAmnt = round($entrdAMnt * $acntCrncyRate, 2);
                        $succs = sendToVMSGLIntrfcMnl(
                            $lbltyAccntID,
                            "I",
                            $ttlCstPrice,
                            $trnsDateStr,
                            $docNum . "-Adjustment of " . $itmDesc . " balance in " . $destVltCageNm . " (" . $docDesc . "-" . $doctype . ")",
                            $entrdCrncyID,
                            $dateStr,
                            $doctype,
                            $docHdrID,
                            $lineid,
                            $trnsLnTyp,
                            $trnsSrc,
                            $entrdAMnt,
                            $entrdCrncyID,
                            $acctCrncyAmnt,
                            $acctCrncyID,
                            $funcCrncyRate,
                            $acntCrncyRate,
                            $errMsg
                        );
                        if (!$succs) {
                            return $succs;
                        }
                    }
                }
            } else if ($doctype == "Currency Sale") {
                if ($isTrnsPymnt == false) {
                    $acctCrncyID = (int) getGnrlRecNm("accb.accb_chart_of_accnts", "accnt_id", "crncy_id", $srcInvAcntID);
                    $acntCrncyRate = round(1, 15);
                    $acctCrncyAmnt = round($entrdAMnt * $acntCrncyRate, 2);
                    if ($fnccurid != $acctCrncyID) {
                        $fncCurBals = getAccntLstDailyNetBals($srcInvAcntID, $trnsDateStr);
                        $accCurBals = getAccntCrncyLstDlyNetBals($srcInvAcntID, $trnsDateStr);
                        if ($accCurBals <= 0 && $fncCurBals != 0) {
                            $accCurBals = $fncCurBals;
                        } else {
                            $accCurBals = 1;
                        }
                        $avrgStckCost = round($fncCurBals / $accCurBals, 15);
                        $ttlCstPrice = $qty * $price * $avrgStckCost;
                        $funcCrncyRate = $avrgStckCost;
                    }
                    $succs = sendToVMSGLIntrfcMnl(
                        $srcInvAcntID,
                        "D",
                        $ttlCstPrice,
                        $trnsDateStr,
                        $docNum . "-Sale of " . $itmDesc . " from " . $srcVltCageNm . " to $cstmrNm (" . $docDesc . "-" . $doctype . ")",
                        $entrdCrncyID,
                        $dateStr,
                        $doctype,
                        $docHdrID,
                        $lineid,
                        $trnsLnTyp,
                        $trnsSrc,
                        $entrdAMnt,
                        $entrdCrncyID,
                        $acctCrncyAmnt,
                        $acctCrncyID,
                        $funcCrncyRate,
                        $acntCrncyRate,
                        $errMsg
                    );
                    if (!$succs) {
                        return $succs;
                    } else {
                        $acctCrncyID = (int) getGnrlRecNm("accb.accb_chart_of_accnts", "accnt_id", "crncy_id", $cogsID);
                        $acntCrncyRate = round(get_LtstExchRate($entrdCrncyID, $acctCrncyID, $trnsDateStr), 15);
                        $acctCrncyAmnt = round($entrdAMnt * $acntCrncyRate, 2);
                        $succs = sendToVMSGLIntrfcMnl(
                            $cogsID,
                            "I",
                            $ttlCstPrice,
                            $trnsDateStr,
                            $docNum . "-Sale of " . $itmDesc . " from " . $srcVltCageNm . " to $cstmrNm (" . $docDesc . "-" . $doctype . ")",
                            $entrdCrncyID,
                            $dateStr,
                            $doctype,
                            $docHdrID,
                            $lineid,
                            $trnsLnTyp,
                            $trnsSrc,
                            $entrdAMnt,
                            $entrdCrncyID,
                            $acctCrncyAmnt,
                            $acctCrncyID,
                            $funcCrncyRate,
                            $acntCrncyRate,
                            $errMsg
                        );
                        if (!$succs) {
                            return $succs;
                        }
                    }
                } else {
                    $acctCrncyID = (int) getGnrlRecNm("accb.accb_chart_of_accnts", "accnt_id", "crncy_id", $destInvAcntID);
                    $acntCrncyRate = round(1, 15);
                    $acctCrncyAmnt = round($entrdAMnt * $acntCrncyRate, 2);
                    $succs = sendToVMSGLIntrfcMnl(
                        $destInvAcntID,
                        "I",
                        $ttlCstPrice,
                        $trnsDateStr,
                        $docNum . "-Payment of " . $itmDesc . " to " . $destVltCageNm . " by $cstmrNm (" . $docDesc . "-" . $doctype . ")",
                        $entrdCrncyID,
                        $dateStr,
                        $doctype,
                        $docHdrID,
                        $lineid,
                        $trnsLnTyp,
                        $trnsSrc,
                        $entrdAMnt,
                        $entrdCrncyID,
                        $acctCrncyAmnt,
                        $acctCrncyID,
                        $funcCrncyRate,
                        $acntCrncyRate,
                        $errMsg
                    );
                    if (!$succs) {
                        return $succs;
                    } else {
                        $acctCrncyID = (int) getGnrlRecNm("accb.accb_chart_of_accnts", "accnt_id", "crncy_id", $salesRevID);
                        $acntCrncyRate = round(get_LtstExchRate($entrdCrncyID, $acctCrncyID, $trnsDateStr), 15);
                        $acctCrncyAmnt = round($entrdAMnt * $acntCrncyRate, 2);
                        $succs = sendToVMSGLIntrfcMnl(
                            $salesRevID,
                            "I",
                            $ttlCstPrice,
                            $trnsDateStr,
                            $docNum . "-Payment of " . $itmDesc . " to " . $destVltCageNm . " by $cstmrNm (" . $docDesc . "-" . $doctype . ")",
                            $entrdCrncyID,
                            $dateStr,
                            $doctype,
                            $docHdrID,
                            $lineid,
                            $trnsLnTyp,
                            $trnsSrc,
                            $entrdAMnt,
                            $entrdCrncyID,
                            $acctCrncyAmnt,
                            $acctCrncyID,
                            $funcCrncyRate,
                            $acntCrncyRate,
                            $errMsg
                        );
                        if (!$succs) {
                            return $succs;
                        }
                    }
                }
            } else if ($doctype == "Currency Purchase" || $doctype == "Currency Importation") {
                if ($isTrnsPymnt == false) {
                    $acctCrncyID = (int) getGnrlRecNm("accb.accb_chart_of_accnts", "accnt_id", "crncy_id", $destInvAcntID);
                    $acntCrncyRate = round(1, 15);
                    $acctCrncyAmnt = round($entrdAMnt * $acntCrncyRate, 2);
                    if ($fnccurid != $acctCrncyID) {
                        $avrgStckCost = $entrdRate;
                        $ttlCstPrice = $qty * $price * $avrgStckCost;
                        $funcCrncyRate = $avrgStckCost;
                    }
                    $succs = sendToVMSGLIntrfcMnl($destInvAcntID, "I", $ttlCstPrice, $trnsDateStr, $docNum . "-Purchase of " . $itmDesc . " to " . $destVltCageNm . " from $cstmrNm (" . $docDesc . "-" . $doctype . ")", $entrdCrncyID, $dateStr, $doctype, $docHdrID, $lineid, $trnsLnTyp, $trnsSrc, $entrdAMnt, $entrdCrncyID, $acctCrncyAmnt, $acctCrncyID, $funcCrncyRate, $acntCrncyRate, $errMsg);
                    if (!$succs) {
                        return $succs;
                    } else {
                        $acctCrncyID = (int) getGnrlRecNm("accb.accb_chart_of_accnts", "accnt_id", "crncy_id", $lbltyAccntID);
                        $acntCrncyRate = round(get_LtstExchRate($entrdCrncyID, $acctCrncyID, $trnsDateStr), 15);
                        $acctCrncyAmnt = round($entrdAMnt * $acntCrncyRate, 2);
                        $succs = sendToVMSGLIntrfcMnl(
                            $lbltyAccntID,
                            "I",
                            $ttlCstPrice,
                            $trnsDateStr,
                            $docNum . "-Purchase of " . $itmDesc . " to " . $destVltCageNm . " from $cstmrNm (" . $docDesc . "-" . $doctype . ")",
                            $entrdCrncyID,
                            $dateStr,
                            $doctype,
                            $docHdrID,
                            $lineid,
                            $trnsLnTyp,
                            $trnsSrc,
                            $entrdAMnt,
                            $entrdCrncyID,
                            $acctCrncyAmnt,
                            $acctCrncyID,
                            $funcCrncyRate,
                            $acntCrncyRate,
                            $errMsg
                        );
                        if (!$succs) {
                            return $succs;
                        }
                    }
                } else {
                    $acctCrncyID = (int) getGnrlRecNm("accb.accb_chart_of_accnts", "accnt_id", "crncy_id", $srcInvAcntID);
                    $acntCrncyRate = round(1, 15);
                    $acctCrncyAmnt = round($entrdAMnt * $acntCrncyRate, 2);
                    $succs = sendToVMSGLIntrfcMnl(
                        $srcInvAcntID,
                        "D",
                        $ttlCstPrice,
                        $trnsDateStr,
                        $docNum . "-Payment of " . $itmDesc . " from " . $srcVltCageNm . " to $cstmrNm (" . $docDesc . "-" . $doctype . ")",
                        $entrdCrncyID,
                        $dateStr,
                        $doctype,
                        $docHdrID,
                        $lineid,
                        $trnsLnTyp,
                        $trnsSrc,
                        $entrdAMnt,
                        $entrdCrncyID,
                        $acctCrncyAmnt,
                        $acctCrncyID,
                        $funcCrncyRate,
                        $acntCrncyRate,
                        $errMsg
                    );
                    if (!$succs) {
                        return $succs;
                    } else {
                        $acctCrncyID = (int) getGnrlRecNm("accb.accb_chart_of_accnts", "accnt_id", "crncy_id", $lbltyAccntID);
                        $acntCrncyRate = round(get_LtstExchRate($entrdCrncyID, $acctCrncyID, $trnsDateStr), 15);
                        $acctCrncyAmnt = round($entrdAMnt * $acntCrncyRate, 2);
                        $succs = sendToVMSGLIntrfcMnl(
                            $lbltyAccntID,
                            "D",
                            $ttlCstPrice,
                            $trnsDateStr,
                            $docNum . "-Payment of " . $itmDesc . " from " . $srcVltCageNm . " to $cstmrNm (" . $docDesc . "-" . $doctype . ")",
                            $entrdCrncyID,
                            $dateStr,
                            $doctype,
                            $docHdrID,
                            $lineid,
                            $trnsLnTyp,
                            $trnsSrc,
                            $entrdAMnt,
                            $entrdCrncyID,
                            $acctCrncyAmnt,
                            $acctCrncyID,
                            $funcCrncyRate,
                            $acntCrncyRate,
                            $errMsg
                        );
                        if (!$succs) {
                            return $succs;
                        }
                    }
                }
            }
        }
        return true;
    } catch (Exception $e) {
        $errMsg .= 'Caught exception: ' . $e->getMessage();
        return false;
    }
}

function generateGnrlVMSAcntng($incrsDcrs1, $inAccntID1, $incrsDcrs2, $inAccntID2, $trnsAmnt, $entrdCrncyID, $entrdRate, $doctype, $docHdrID, $lineid, $dateStr, $docDesc, $usrID, $orgID, &$errMsg)
{
    try {
        $strSQL = "Select CASE WHEN vms.general_dbl_accntng_entry('" . loc_db_escape_string($incrsDcrs1) . "',
                                                    " . $inAccntID1 . ",
                                                    '" . loc_db_escape_string($incrsDcrs2) . "',
                                                    " . $inAccntID2 . ",
                                                    " . $trnsAmnt . ",
                                                    " . $entrdCrncyID . ",
                                                    " . $entrdRate . ",
                                                    '" . loc_db_escape_string($doctype) . "',
                                                    " . $docHdrID . ",
                                                    " . $lineid . ",
                                                    '" . loc_db_escape_string($dateStr) . "',
                                                    '" . loc_db_escape_string($docDesc) . "',
                                                    " . $usrID . ",
                                                    " . $orgID . ")=TRUE THEN 1 ELSE 0 END";
        //echo $strSQL;
        $result = executeSQLNoParams($strSQL);
        while ($row = loc_db_fetch_array($result)) {
            return $row[0] == "1" ? true : false;
        }
        return false;
    } catch (Exception $e) {
        $errMsg = 'Caught exception: ' . $e->getMessage();
        return false;
    }
}

function isVMSTrnsQtyVld($docHdrID, &$errMsg)
{
    global $trnsTypes;
    global $trnsTypeABRV;
    global $gnrlTrnsDteDMYHMS;
    try {
        $dateStr = $gnrlTrnsDteDMYHMS; //getFrmtdDB_Date_time();
        $doctype = "";
        $docNum = "";
        $cstmrNm = "";
        $docDesc = "";
        $expnsSum = 0;
        $ttlDocSum = 0;
        $rsltAcc = get_One_VMSTrnsHdr($docHdrID);
        if ($rwAcc = loc_db_fetch_array($rsltAcc)) {
            $doctype = $rwAcc[3];
            $docNum = $rwAcc[2];
            $cstmrNm = $rwAcc[6];
            $docDesc = $rwAcc[4];
            $ttlDocSum = (float) $rwAcc[21];
            $expnsSum = $rwAcc[43];
        }
        $succs = false;
        $msg = "";
        $result = get_One_VMSTrnsLines($docHdrID);
        //echo $doctype;
        while ($row = loc_db_fetch_array($result)) {
            $itemID = (int) $row[2];
            $isdlvrd = false; //cnvrtBitStrToBool($row[13]);
            $srcStoreID = (int) $row[3];
            $srcCageID = (int) $row[4];
            $destStoreID = (int) $row[5];
            $destCageID = (int) $row[6];
            $qty = (float) $row[7];
            $lineid = (float) $row[0];
            $itmType = $row[33];
            $srcItmState = $row[27];
            $destItmState = $row[34];
            $isTrnsPymnt = false;
            $docTypPrfx = $trnsTypeABRV[findArryIdx($trnsTypes, $doctype)];
            if ($doctype == "Transits (Specie Movement)" || $doctype == "Teller/Cashier Transfers" || $doctype == "To Exam" || $doctype == "From Exam") {
                if ($itemID > 0 && $srcStoreID > 0 && $srcCageID && $isdlvrd == false) {
                    $msg .= $doctype . ":" . $itemID . ":" . $srcStoreID . ":" . $srcCageID . ":" . $qty . ":" . $lineid . ":" . $itmType . ":" . $srcItmState . ":" . $destItmState . ":" . $docTypPrfx . "<br/>";
                    $succs = isStockQtyAvlbl($srcStoreID, $srcCageID, $itemID, $srcItmState, $qty, $dateStr, "D");
                    if ($succs === false) {
                        return $succs;
                    }
                }
                if ($itemID > 0 && $destStoreID > 0 && $destCageID > 0 && $isdlvrd == false) {
                    $msg .= $doctype . ":" . $itemID . ":" . $destStoreID . ":" . $destCageID . ":" . $qty . ":" . $lineid . ":" . $itmType . ":" . $srcItmState . ":" . $destItmState . ":" . $docTypPrfx . "<br/>";
                    $succs = isStockQtyAvlbl($destStoreID, $destCageID, $itemID, $destItmState, $qty, $dateStr, "I");
                    if ($succs === false) {
                        return $succs;
                    }
                }
            } else if ($doctype == "Miscellaneous Adjustments" || $doctype == "Direct Cage/Shelve Transaction") {
                if ($itemID > 0 && $srcStoreID > 0 && $srcCageID > 0 && $isdlvrd == false) {
                    $succs = isStockQtyAvlbl($srcStoreID, $srcCageID, $itemID, $srcItmState, $qty, $dateStr, "D");
                    if ($succs === false) {
                        return $succs;
                    }
                }
                if ($itemID > 0 && $destStoreID > 0 && $destCageID > 0 && $isdlvrd == false) {
                    $succs = isStockQtyAvlbl($destStoreID, $destCageID, $itemID, $destItmState, $qty, $dateStr, "I");
                }
            } else if ($doctype == "Deposits" || $doctype == "Currency Importation" || $doctype == "GL/Vault Account Transfers") {
                if ($itemID > 0 && $destStoreID > 0 && $destCageID > 0 && $isdlvrd == false) {
                    $succs = isStockQtyAvlbl($destStoreID, $destCageID, $itemID, $destItmState, $qty, $dateStr, "I");
                    if ($succs === false) {
                        return $succs;
                    }
                    if ($doctype == "GL/Vault Account Transfers" && $expnsSum != $ttlDocSum) {
                        $succs = false;
                        return $succs;
                    }
                }
            } else if ($doctype == "Withdrawals" || $doctype == "Currency Destruction" || $doctype == "Vault/GL Account Transfers") {
                if ($itemID > 0 && $srcStoreID > 0 && $srcCageID && $isdlvrd == false) {
                    $succs = isStockQtyAvlbl($srcStoreID, $srcCageID, $itemID, $srcItmState, $qty, $dateStr, "D");
                    if ($succs === false) {
                        return $succs;
                    }
                    if ($doctype == "Vault/GL Account Transfers" && $expnsSum != $ttlDocSum) {
                        $succs = false;
                        return $succs;
                    }
                }
            } else if ($doctype == "Currency Sale") {
                if ($isTrnsPymnt == false) {
                    if ($itemID > 0 && $srcStoreID > 0 && $srcCageID > 0 && $isdlvrd == false) {
                        $succs = isStockQtyAvlbl($srcStoreID, $srcCageID, $itemID, $srcItmState, $qty, $dateStr, "D");
                        if ($succs === false) {
                            return $succs;
                        }
                    }
                }
            } else if ($doctype == "Currency Purchase") {
                if ($isTrnsPymnt == false) {
                    //echo "BENHRE2_" . $itemID . "_" . $destStoreID . "_" . $destCageID . "_" . $isdlvrd . "_" . $isTrnsPymnt;
                    if ($itemID > 0 && $destStoreID > 0 && $destCageID > 0 && $isdlvrd == false) {
                        $succs = isStockQtyAvlbl($destStoreID, $destCageID, $itemID, $destItmState, $qty, $dateStr, "I");
                        if ($succs === false) {
                            return $succs;
                        }
                        //echo "BENHRE2";
                    }
                }
            }
        }

        //Check for Trns Payment
        $docTypPrfx = $trnsTypeABRV[findArryIdx($trnsTypes, $doctype)];
        $result1 = get_One_VMSTrnsPymntLines($docHdrID);
        while ($row1 = loc_db_fetch_array($result1)) {
            $itemID = (int) $row1[2];
            $isdlvrd = false;
            //cnvrtBitStrToBool($row1[13]);
            $srcStoreID = (int) $row1[3];
            $srcCageID = (int) $row1[4];
            $destStoreID = (int) $row1[5];
            $destCageID = (int) $row1[6];
            $qty = (float) $row1[7];
            $lineid = (float) $row1[0];
            $itmType = $row1[33];
            $srcItmState = $row1[27];
            $destItmState = $row1[34];
            $isTrnsPymnt = true;
            if ($doctype == "Currency Sale" || $doctype == "Currency Purchase") {
                if ($isTrnsPymnt == true) {
                    if ($itemID > 0 && $destStoreID > 0 && $destCageID > 0 && $isdlvrd == false) {
                        $succs = isStockQtyAvlbl($destStoreID, $destCageID, $itemID, $destItmState, $qty, $dateStr, "I");
                        if ($succs === false) {
                            return $succs;
                        }
                        $msg .= $doctype . ":" . $itemID . ":" . $destStoreID . ":" . $destCageID . ":" . $destItmState . ":" . $qty . ":" . $succs . "<br/>";
                    }
                    if ($itemID > 0 && $srcStoreID > 0 && $srcCageID > 0 && $isdlvrd == false) {
                        $succs = isStockQtyAvlbl($srcStoreID, $srcCageID, $itemID, $srcItmState, $qty, $dateStr, "D");
                        if ($succs === false) {
                            return $succs;
                        }
                        $msg .= $doctype . ":" . $itemID . ":" . $srcStoreID . ":" . $srcCageID . ":" . $srcItmState . ":" . $qty . ":" . $succs . "<br/>";
                    }
                }
            }
        }
        if ($succs == true) {
            $succs = validateVMSAccntng($docHdrID, $errMsg);
        }
        return $succs;
    } catch (Exception $e) {
        $errMsg .= 'Caught exception: ' . $e->getMessage();
        return false;
    }
}

function updateVMSStckBals($docHdrID, &$errMsg)
{
    global $trnsTypes;
    global $trnsTypeABRV;
    global $gnrlTrnsDteDMYHMS;
    try {
        $errMsg = "";
        $dateStr = $gnrlTrnsDteDMYHMS; //getFrmtdDB_Date_time();
        $doctype = "";
        $docNum = "";
        $cstmrNm = "";
        $docDesc = "";
        $rsltAcc = get_One_VMSTrnsHdr($docHdrID);
        if ($rwAcc = loc_db_fetch_array($rsltAcc)) {
            $doctype = $rwAcc[3];
            $docNum = $rwAcc[2];
            $cstmrNm = $rwAcc[6];
            $docDesc = $rwAcc[4];
        }
        $succs = FALSE;
        $result = get_One_VMSTrnsLines($docHdrID);
        while ($row = loc_db_fetch_array($result)) {
            $itemID = (int) $row[2];
            $isdlvrd = false; //cnvrtBitStrToBool($row[13]);
            $srcStoreID = (int) $row[3];
            $srcCageID = (int) $row[4];
            $destStoreID = (int) $row[5];
            $destCageID = (int) $row[6];
            $qty = (float) $row[7];
            $lineid = (float) $row[0];
            $itmType = $row[33];
            $srcItmState = $row[27];
            $destItmState = $row[34];
            $isTrnsPymnt = false;
            $docTypPrfx = $trnsTypeABRV[findArryIdx($trnsTypes, $doctype)];
            if ($doctype == "Transits (Specie Movement)" || $doctype == "Teller/Cashier Transfers" || $doctype == "To Exam" || $doctype == "From Exam") {
                if ($itemID > 0 && $srcStoreID > 0 && $srcCageID && $isdlvrd == false) {
                    $succs = updateItemBalances($srcStoreID, $srcCageID, $itemID, $itmType, $srcItmState, $qty, "D", $docTypPrfx, $lineid, $dateStr, $errMsg);
                }
                if ($itemID > 0 && $destStoreID > 0 && $destCageID > 0 && $isdlvrd == false) {
                    $succs = updateItemBalances($destStoreID, $destCageID, $itemID, $itmType, $destItmState, $qty, "I", $docTypPrfx, $lineid, $dateStr, $errMsg);
                }
            } else if ($doctype == "Miscellaneous Adjustments" || $doctype == "Direct Cage/Shelve Transaction") {
                if ($itemID > 0 && $srcStoreID > 0 && $srcCageID > 0 && $isdlvrd == false) {
                    $succs = updateItemBalances($srcStoreID, $srcCageID, $itemID, $itmType, $srcItmState, $qty, "D", $docTypPrfx, $lineid, $dateStr, $errMsg);
                }
                if ($itemID > 0 && $destStoreID > 0 && $destCageID > 0 && $isdlvrd == false) {
                    $succs = updateItemBalances($destStoreID, $destCageID, $itemID, $itmType, $destItmState, $qty, "I", $docTypPrfx, $lineid, $dateStr, $errMsg);
                }
            } else if ($doctype == "Deposits" || $doctype == "Currency Importation" || $doctype == "GL/Vault Account Transfers") {
                if ($itemID > 0 && $destStoreID > 0 && $destCageID > 0 && $isdlvrd == false) {
                    $succs = updateItemBalances($destStoreID, $destCageID, $itemID, $itmType, $destItmState, $qty, "I", $docTypPrfx, $lineid, $dateStr, $errMsg);
                }
            } else if ($doctype == "Withdrawals" || $doctype == "Currency Destruction" || $doctype == "Vault/GL Account Transfers") {
                if ($itemID > 0 && $srcStoreID > 0 && $srcCageID > 0 && $isdlvrd == false) {
                    $succs = updateItemBalances($srcStoreID, $srcCageID, $itemID, $itmType, $srcItmState, $qty, "D", $docTypPrfx, $lineid, $dateStr, $errMsg);
                }
            } else if ($doctype == "Currency Sale") {
                if ($isTrnsPymnt == false) {
                    if ($itemID > 0 && $srcStoreID > 0 && $srcCageID > 0 && $isdlvrd == false) {
                        $succs = updateItemBalances($srcStoreID, $srcCageID, $itemID, $itmType, $srcItmState, $qty, "D", $docTypPrfx, $lineid, $dateStr, $errMsg);
                    }
                }
                if ($isTrnsPymnt == true) {
                    if ($itemID > 0 && $destStoreID > 0 && $destCageID > 0 && $isdlvrd == false) {
                        $succs = updateItemBalances($destStoreID, $destCageID, $itemID, $itmType, $destItmState, $qty, "I", $docTypPrfx, $lineid, $dateStr, $errMsg);
                    }
                }
            } else if ($doctype == "Currency Purchase") {
                if ($isTrnsPymnt == false) {
                    if ($itemID > 0 && $destStoreID > 0 && $destCageID > 0 && $isdlvrd == false) {
                        $succs = updateItemBalances($destStoreID, $destCageID, $itemID, $itmType, $destItmState, $qty, "I", $docTypPrfx, $lineid, $dateStr, $errMsg);
                    }
                }
                if ($isTrnsPymnt == true) {
                    if ($itemID > 0 && $srcStoreID > 0 && $srcCageID > 0 && $isdlvrd == false) {
                        $succs = updateItemBalances($srcStoreID, $srcCageID, $itemID, $itmType, $srcItmState, $qty, "D", $docTypPrfx, $lineid, $dateStr, $errMsg);
                    }
                }
            }
            if ($succs) {
                updateVMSTrnsLnDlvrd($lineid, true);
            }
        }
        //Check for Trns Payment
        $docTypPrfx = $trnsTypeABRV[findArryIdx($trnsTypes, $doctype)];
        $result1 = get_One_VMSTrnsPymntLines($docHdrID);
        $msg = $docHdrID . "<br/>";
        while ($row1 = loc_db_fetch_array($result1)) {
            $itemID = (int) $row1[2];
            $isdlvrd = false; //cnvrtBitStrToBool($row1[13]);
            $srcStoreID = (int) $row1[3];
            $srcCageID = (int) $row1[4];
            $destStoreID = (int) $row1[5];
            $destCageID = (int) $row1[6];
            $qty = (float) $row1[7];
            $lineid = (float) $row1[0];
            $itmType = $row1[33];
            $srcItmState = $row1[27];
            $destItmState = $row1[34];
            $isTrnsPymnt = true;
            if ($doctype == "Currency Sale" || $doctype == "Currency Purchase") {
                if ($isTrnsPymnt == true) {
                    if ($itemID > 0 && $destStoreID > 0 && $destCageID > 0 && $isdlvrd == false) {
                        $succs = updateItemBalances($destStoreID, $destCageID, $itemID, $itmType, $destItmState, $qty, "I", $docTypPrfx, $lineid, $dateStr, $errMsg);
                        $msg .= $doctype . ":" . $itemID . ":" . $destStoreID . ":" . $destCageID . ":" . $destItmState . ":" . $qty . ":" . $succs . ":" . $errMsg . "<br/>";
                    }
                    if ($itemID > 0 && $srcStoreID > 0 && $srcCageID > 0 && $isdlvrd == false) {
                        $succs = updateItemBalances($srcStoreID, $srcCageID, $itemID, $itmType, $srcItmState, $qty, "D", $docTypPrfx, $lineid, $dateStr, $errMsg);
                        $msg .= $doctype . ":" . $itemID . ":" . $srcStoreID . ":" . $srcCageID . ":" . $srcItmState . ":" . $qty . ":" . $succs . ":" . $errMsg . "<br/>";
                    }
                }
            }
            if ($succs) {
                updateVMSPymntLnDlvrd($lineid, true);
            }
        }
        //$succs = false;
        //var_dump("succs::".$succs);
        return $succs;
    } catch (Exception $e) {
        $errMsg = 'Caught exception: ' . $e->getMessage();
        return false;
    }
}

function updateVMSTrnsLnDlvrd($lnID, $dlvrd)
{
    $updtSQL = "UPDATE vms.vms_transaction_lines SET " .
        "is_itm_delivered='" . cnvrtBoolToBitStr($dlvrd) .
        "' WHERE (trans_det_ln_id = " . $lnID . ")";
    return executeSQLNoParams($updtSQL);
}

function updateVMSPymntLnDlvrd($lnID, $dlvrd)
{
    $updtSQL = "UPDATE vms.vms_transaction_pymnt SET " .
        "is_itm_delivered='" . cnvrtBoolToBitStr($dlvrd) .
        "' WHERE (trans_det_ln_id = " . $lnID . ")";
    return executeSQLNoParams($updtSQL);
}

function updateItemBalances($vltID, $cageID, $itemID, $itmType, $itmState, $qnty, $actTyp, $docTypPrfx, $docLnID, $dateStr, &$errMsg)
{
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

function postStockQty($vltID, $cageID, $itemID, $itmState, $totQty, $trnsDate, $src_trsID)
{
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

function isStockQtyAvlbl($vltID, $cageID, $itemID, $itmState, $totQty, $trnsDate, $actionType)
{
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

function hsTrnsUptdStockBls($srctrnsid, $trnsdate, $vltID, $cageID, $itemID, $itmState)
{
    if ($trnsdate != "") {
        $trnsdate = cnvrtDMYTmToYMDTm($trnsdate);
    }
    if (strlen($trnsdate) > 10) {
        $trnsdate = substr($trnsdate, 0, 10);
    }
    $strSql = "SELECT a.bal_id FROM vms.vms_stock_daily_bals a " .
        "WHERE a.store_vault_id = " . $vltID .
        " and a.cage_shelve_id= " . $cageID .
        " and a.item_id=" . $itemID .
        " and a.itm_state_clsfctn = '" . loc_db_escape_string($itmState) .
        "' and a.bals_date = '" . $trnsdate . "' and a.source_trns_ids like '%," . $srctrnsid . ",%'";
    $result = executeSQLNoParams($strSql);
    if (loc_db_num_rows($result) > 0) {
        return true;
    }
    return false;
}

function getStockBlsTrnsDte($srctrnsid, $vltID, $cageID, $itemID, $itmState)
{
    $strSql = "SELECT to_char(to_timestamp(a.bals_date,'YYYY-MM-DD'),'DD-Mon-YYYY 00:00:00') FROM vms.vms_stock_daily_bals a " .
        "WHERE a.store_vault_id = " . $vltID .
        " and a.cage_shelve_id= " . $cageID .
        " and a.item_id=" . $itemID .
        " and a.itm_state_clsfctn = '" . loc_db_escape_string($itmState) .
        "' and a.source_trns_ids like '%," . $srctrnsid . ",%' ORDER BY a.bals_date DESC";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return "";
}

function getStockDailyBalsID($vltID, $cageID, $itemID, $itmState, $balsDate)
{
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

function getStoreLstTotBlsUsgDte($vltID, $cageID, $itemID, $itmState, $balsDate)
{
    if ($balsDate != "") {
        $balsDate = substr($balsDate, 0, 10);
    }
    $strSql = "SELECT vms.get_ltst_stock_bals($vltID, $cageID, $itemID, '" . $itmState . "', '" . $balsDate . "')";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return 0.00;
}

function getStoreLstTotBls($vltID, $cageID, $itemID, $itmState)
{
    $strSql = "SELECT vms.get_ltst_stock_bals($vltID, $cageID, $itemID, '" . $itmState . "')";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return 0.00;
}

function getStockLstTotBls($vltID, $cageID, $itemID, $itmState, $balsDate)
{
    //var_dump(func_get_args());
    if ($balsDate != "") {
        $balsDate = cnvrtDMYTmToYMDTm($balsDate);
    }
    if (strlen($balsDate) > 10) {
        $balsDate = substr($balsDate, 0, 10);
    }
    $strSql = "SELECT COALESCE(a.stock_tot_qty,0) " .
        "FROM vms.vms_stock_daily_bals a " .
        "WHERE(to_timestamp(a.bals_date,'YYYY-MM-DD') <=  to_timestamp('" . $balsDate .
        "','YYYY-MM-DD') and (store_vault_id = " . $vltID .
        " or " . $vltID . " <= 0) and (cage_shelve_id = " . $cageID .
        " or " . $cageID . " <= 0) and (item_id = " . $itemID .
        " or " . $itemID . " <= 0) and (itm_state_clsfctn = '" . loc_db_escape_string($itmState) .
        "' or '" . loc_db_escape_string($itmState) . "' = '')) ORDER BY to_timestamp(a.bals_date,'YYYY-MM-DD') DESC LIMIT 1 OFFSET 0";
    //echo $strSql;
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return 0.00;
}

function createStckDailyBals($vltID, $cageID, $itemID, $itmState, $totQty, $balsDate, $src_trnsID)
{
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

function updtStckDailyBals($vltID, $cageID, $itemID, $itmState, $totQty, $balsDate, $act_typ, $src_trnsID)
{
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

function getUomBrkDwn($itemID)
{
    $strSQL = "Select * from (SELECT a.itm_uom_id mt, "
        . "(SELECT b.uom_name FROM inv.unit_of_measure b WHERE b.uom_id = a.uom_id) uom, " .
        " a.uom_id mt, uom_level mt, cnvsn_factor mt, selling_price, price_less_tax " .
        " FROM inv.itm_uoms a WHERE a.item_id = $itemID " .
        " union " .
        " SELECT -1 mt, (SELECT b.uom_name FROM inv.unit_of_measure b WHERE b.uom_id = a.base_uom_id) uom, " .
        " base_uom_id mt, -1 mt, 1 mt, selling_price, orgnl_selling_price " .
        " FROM inv.inv_itm_list a WHERE a.item_id = $itemID) tbl1 ORDER BY 4 DESC";
    $result = executeSQLNoParams($strSQL);
    return $result;
}

function getUomCnvrsnFctr($itemID, $uomNm)
{
    $strSQL = "Select tbl1.mtcf from (SELECT a.itm_uom_id mt, "
        . "(SELECT b.uom_name FROM inv.unit_of_measure b WHERE b.uom_id = a.uom_id) uom, " .
        " a.uom_id mt, uom_level mt, cnvsn_factor mtcf, selling_price, price_less_tax " .
        " FROM inv.itm_uoms a WHERE a.item_id = $itemID " .
        " union " .
        " SELECT -1 mt, (SELECT b.uom_name FROM inv.unit_of_measure b WHERE b.uom_id = a.base_uom_id) uom, " .
        " base_uom_id mt, -1 mt, 1 mtcf, selling_price, orgnl_selling_price " .
        " FROM inv.inv_itm_list a WHERE a.item_id = $itemID) tbl1 "
        . "WHERE lower(tbl1.uom) = '" . loc_db_escape_string(strtolower($uomNm)) . "'";
    $result = executeSQLNoParams($strSQL);
    while ($rw = loc_db_fetch_array($result)) {
        return (float) $rw[0];
    }
    return 1;
}

function getVMSTrnsAttchMnts($trnsHdrId)
{
    global $ftp_base_db_fldr;
    $sqlStr = "SELECT string_agg(REPLACE(a.attchmnt_desc,';',','),';') attchmnt_desc, 
string_agg(REPLACE('" . $ftp_base_db_fldr . "/Vms/' || a.file_name,';',','),';') file_name 
  FROM vms.vms_doc_attchmnts a 
  WHERE trans_hdr_id=" . $trnsHdrId;
    $result = executeSQLNoParams($sqlStr);
    return $result;
}

function updateVMSVoidedTrnsStatus($trnsHdrId, $vldtyStatus)
{
    /*
      global $usrID;
      $datestr = getDB_Date_time();,
      last_update_by = $usrID,
      last_update_date = '$datestr' */
    $updSQL = "UPDATE vms.vms_transactions_hdr
            SET validity_status='" . loc_db_escape_string($vldtyStatus) . "'
            WHERE trans_hdr_id = $trnsHdrId";
    $affctd = execUpdtInsSQL($updSQL);
    return $affctd;
}

function updateVMSTrnsStatus($trnsHdrId, $nwApprvlStatus, $nxtApprvlAction, $vldtyStatus, $cur_prsnid = -1)
{
    global $usrID;
    global $prsnid;
    $extrUpdate = "";
    if ($cur_prsnid <= 0 && $nwApprvlStatus == "Authorized") {
        $cur_prsnid = $prsnid;
        $extrUpdate = ", approved_by_prsn_id = $cur_prsnid";
    }
    $datestr = getDB_Date_time();
    $updSQL = "UPDATE vms.vms_transactions_hdr
            SET approval_status='" . loc_db_escape_string($nwApprvlStatus) . "',
                next_aproval_action='" . loc_db_escape_string($nxtApprvlAction) . "',
                validity_status='" . loc_db_escape_string($vldtyStatus) . "',
                last_update_by = $usrID,
                last_update_date = '$datestr'" . $extrUpdate . "
            WHERE trans_hdr_id = $trnsHdrId";
    $affctd = execUpdtInsSQL($updSQL);
    return $affctd;
}

function updateVMSTrnsAtzrLmtID($trnsHdrId, $athrzrLmtID)
{
    global $usrID;
    $datestr = getDB_Date_time();
    $updSQL = "UPDATE vms.vms_transactions_hdr
            SET last_update_by = $usrID,
                last_update_date = '$datestr',
                mtchd_athrzr_lmt_id = $athrzrLmtID
            WHERE trans_hdr_id = $trnsHdrId";
    $affctd = execUpdtInsSQL($updSQL);
    return $affctd;
}

function getNextVMSApprvrs($siteID, $trnsTyp, $crncyID, $trnsAmnt)
{
    $strSql = "SELECT a.authorizer_person_id, a.authorizer_limit_id 
        FROM vms.vms_authorizers_limit a
        WHERE ((a.site_id = $siteID or a.site_id<=0) "
        . "and (a.currency_id = $crncyID or a.currency_id<=0) "
        . "and ($trnsAmnt>=a.min_amount) "
        . "and ($trnsAmnt<=a.max_amount) "
        . "and (a.transaction_type ='" . loc_db_escape_string($trnsTyp) . "' or a.transaction_type='')) ";
    $result = executeSQLNoParams($strSql);
    return $result;
}

function isTrnsWthnCageMngrsLmt($srcCageID, $dstCageID, $trnsAmnt, $trnsType = "Direct Cage/Shelve Transaction")
{
    $strSql = "SELECT a.cage_shelve_mngr_id, -1 
        FROM inv.inv_shelf a
        WHERE ((a.line_id = $srcCageID) and abs($trnsAmnt)>=0 "
        . "and (a.managers_wthdrwl_limit>=abs($trnsAmnt)) and 'Direct Cage/Shelve Transaction'='" . loc_db_escape_string($trnsType) . "') "
        . " UNION "
        . " SELECT a.cage_shelve_mngr_id, -1 
        FROM inv.inv_shelf a
        WHERE ((a.line_id = $dstCageID) and abs($trnsAmnt)>=0 "
        . "and (a.managers_deposit_limit>=abs($trnsAmnt)) and 'Direct Cage/Shelve Transaction'='" . loc_db_escape_string($trnsType) . "') ";
    $result = executeSQLNoParams($strSql);
    return $result;
}

function vmsTrnsReqMsgActns($routingID = -1, $inptSlctdRtngs = "", $actionToPrfrm = "Initiate", $srcDocID = -1, $srcDocType = "Vault Transactions")
{
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
            $rqstHdrStatus = getGnrlRecNm("vms.vms_transactions_hdr", "trans_hdr_id", "approval_status", $srcDocID);
            if ($rqstHdrStatus == 'Authorized' || $rqstHdrStatus == 'Paid' || ($rqstHdrStatus == 'Initiated' && $actionToPrfrm == "Initiate" && $routingID <= 0)) {
                return "<span style=\"color:red;font-weight:bold;\">|ERROR| Nothing to $actionToPrfrm!</span>";
            }
        }
        if ($actionToPrfrm == "Initiate" && $srcDocID > 0) {
            $msg_id = getWkfMsgID();
            $appID = getAppID('Vault Transactions', 'Vault Management');
            //Requestor
            $prsnid = $fromPrsnID;
            $fullNm = $usrFullNm;
            $prsnLocID = getPersonLocID($prsnid);

            //Message Header & Details
            $msghdr = "$fullNm ($prsnLocID) Requests for approval of a VMS Transaction";
            $msgbody = "VMS TRANSACTION REQUEST ON ($reqestDte):- "
                . "A VMS Transaction Request has been submitted by $fullNm ($prsnLocID) "
                . "<br/>Please open the attached Work Document and attend to this Request.
                      <br/>Thank you.";
            $msgtyp = "Work Document";
            $msgsts = "0";
            $hrchyid = 1; //Get hierarchy ID
            $rslt = getVMSTrnsAttchMnts($srcDocID);
            $attchmnts = ""; //Get Attachments
            $attchmnts_desc = ""; //Get Attachments
            while ($rw = loc_db_fetch_array($rslt)) {
                $attchmnts = $rw[1];
                $attchmnts_desc = $rw[0];
            }

            createWkfMsg($msg_id, $msghdr, $msgbody, $userID, $appID, $msgtyp, $msgsts, $srcdoctyp, $srcdocid, $hrchyid, $attchmnts, $attchmnts_desc);
            //Get Hierarchy Members
            $siteID = -1;
            $athrzrLmtID = -1;
            $trnsTyp = "";
            //$trnsTypSlf = "";
            $mxSrcCageID = -1;
            $mxDstCageID = -1;
            $crncyID = "";
            $trnsAmnt = "";
            $rslt1 = get_One_VMSTrnsHdr($srcdocid);
            while ($rw1 = loc_db_fetch_array($rslt1)) {
                $siteID = (int) $rw1[20];
                $trnsTyp = $rw1[3];
                $crncyID = (int) $rw1[30];
                $trnsAmnt = (float) $rw1[21];
                //$trnsTypSlf = $trnsTypesSlf[findArryIdx($trnsTypes, $trnsTyp)];
                $mxSrcCageID = (float) $rw1[24];
                $mxDstCageID = (float) $rw1[28];
            }

            $result = null;
            if ($mxSrcCageID > 0 || $mxDstCageID > 0) {
                $result = isTrnsWthnCageMngrsLmt($mxSrcCageID, $mxDstCageID, $trnsAmnt, $trnsTyp);
                if (loc_db_num_rows($result) <= 0) {
                    $result = getNextVMSApprvrs($siteID, $trnsTyp, $crncyID, $trnsAmnt);
                }
            } else {
                $result = getNextVMSApprvrs($siteID, $trnsTyp, $crncyID, $trnsAmnt);
            }
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

                    if (strtoupper($isWflMailAllwd) == "YES" && $isWflMailAllwdID > 0) {
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
                        }
                    }
                }
            }
            if ($prsnsFnd <= 0) {
                $dsply .= "|ERROR|-No Approval Hierarchy Found<br/>";
                $msg = "<p style = \"text-align:left; color:#ff0000;\"><span style=\"font-style:italic;font-weight:bold;\">$dsply</span></p>";
            } else {
                //Update Request Status to In Process
                updateVMSTrnsStatus($srcdocid, "Initiated", "Authorize", "Validated");
                updateVMSTrnsAtzrLmtID($srcdocid, $athrzrLmtID);
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
                $rqstHdrStatus = getGnrlRecNm("vms.vms_transactions_hdr", "trans_hdr_id", "approval_status", $srcDocID);
                if (($rqstHdrStatus == 'Authorized' || $rqstHdrStatus == 'Paid') && $actionToPrfrm != "Open") {
                    return "<span style=\"color:red;font-weight:bold;\">|ERROR| Nothing to $actionToPrfrm!</span>";
                }
            }
            $srcdoctyp = $srcDocType;
            $srcdocid = $srcDocID;
            $orgnlPrsnID = getUserPrsnID1($orgnlPrsnUsrID);
            if ($isActionDone == '0') {
                if ($actionToPrfrm == "Open") {
                    getVMSTrnsRdOnlyDsply($srcDocID, $srcDocType);
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
                    $affctd4 += updateVMSTrnsStatus($srcdocid, "Rejected", "Initiate", "Not Validated");

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
                        $msgbodyAddOn .= "WITHDRAWAL ON $datestr:- This document has been Withdrawn by $usrFullNm with the ff Message:<br/>";
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
                        $affctd4 += updateVMSTrnsStatus($srcdocid, "Withdrawn", "Initiate", "Not Validated");

                        //Begin Email Sending Process                    
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
                        $affctd = 0;
                        $errMsg = "";
                        if ($nxtPrsnID <= 0) {
                            $dsply .= "Start VMS Trans. Item Balance Update and Accounting Entries Generation<br/>";
                            $shdActnBeDone = isVMSTrnsQtyVld($srcDocID, $errMsg);
                            $dsply .= $errMsg;
                            if ($shdActnBeDone == true) {
                                $dsply .= "Item Balance Verified<br/>";
                                $shdActnBeDone = createVMSAccntng($srcDocID, $errMsg);
                                $dsply .= $errMsg;
                                if (strpos($dsply, "ERROR") !== FALSE || strpos($dsply, "caught exception") !== FALSE) {
                                    $shdActnBeDone = false;
                                }
                                if ($shdActnBeDone == true) {
                                    $shdActnBeDone = updateVMSStckBals($srcDocID, $errMsg);
                                    $dsply .= $errMsg;
                                    if ($shdActnBeDone == true) {
                                        $dsply .= "Item Balance Updated<br/>";
                                        $dsply = "Authorization Successful<br/>";
                                        $newStatus = "Authorized";
                                        $nxtStatus = "Open;Acknowledge";
                                        $newVldtyStatus = "Validated";
                                        $voidedTrnsID = (float) getGnrlRecNm("vms.vms_transactions_hdr", "trans_hdr_id", "voided_trns_hdr_id", $srcdocid);
                                        if ($voidedTrnsID > 0) {
                                            updateVMSVoidedTrnsStatus($voidedTrnsID, "Voided");
                                        }
                                    }
                                }
                            } else {
                                $dsply .= "<i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>Error Occured!<br/>Kindly ensure that you have the Requested Quantities in the various Source Cages!";
                                $newVldtyStatus = "Not Validated";
                            }
                        }
                        if ($shdActnBeDone == true) {
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
                            $affctd4 += updateVMSTrnsStatus($srcdocid, $newStatus, $nxtStatus, $newVldtyStatus);
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

function withdrawVMSTrnsRqst($hdrid)
{
    //$apprvrStatus = 'Withdrawn';
    $msg = "";
    $rqstHdrStatus = getGnrlRecNm("vms.vms_transactions_hdr", "trans_hdr_id", "approval_status", $hdrid);
    if ($rqstHdrStatus == 'Authorized') {
        return "<span style=\"color:red;\">|ERROR| Nothing to Withdraw!</span>";
    }
    $srcDocID = $hdrid;
    $srcDocType = "Vault Transactions";
    $inptSlctdRtngs = "";
    $selSQL = "SELECT MAX(b.routing_id)
  FROM wkf.wkf_actual_msgs_hdr a, wkf.wkf_actual_msgs_routng b
  WHERE a.msg_id=b.msg_id and a.src_doc_type='" . $srcDocType . "' 
  and a.src_doc_id=" . $hdrid;
    $result1 = executeSQLNoParams($selSQL);
    while ($row = loc_db_fetch_array($result1)) {
        $routingID = $row[0];
        $actionToPrfrm = "Withdraw";
        if ($routingID > 0) {
            $msg = vmsTrnsReqMsgActns($routingID, $inptSlctdRtngs, $actionToPrfrm, $srcDocID, $srcDocType);
        }
    }
    if ($msg == "") {
        $msg = "<span style=\"color:red;\">|ERROR| Nothing to Withdraw!</span>";
    }
    return $msg;
}

function authorizeVMSTrnsRqst($hdrid)
{
    //$apprvrStatus = 'Authorized';
    global $prsnid;
    $msg = "";
    $rqstHdrStatus = getGnrlRecNm("vms.vms_transactions_hdr", "trans_hdr_id", "approval_status", $hdrid);
    if ($rqstHdrStatus == 'Authorized') {
        return "<span style=\"color:red;\">|ERROR| Nothing to Authorize!</span>";
    }
    $srcDocID = $hdrid;
    $srcDocType = "Vault Transactions";
    $inptSlctdRtngs = "";
    $routingID = getVMSMxRoutingID($hdrid, $srcDocType, $prsnid);
    if ($routingID > 0) {
        $actionToPrfrm = "Authorize";
        $msg = vmsTrnsReqMsgActns($routingID, $inptSlctdRtngs, $actionToPrfrm, $srcDocID, $srcDocType);
    }
    if ($msg == "") {
        $msg = "<span style=\"color:red;\">|ERROR| Nothing to Authorize!</span>";
    }
    return $msg;
}

function getVMSMxRoutingID($srcDocID, $srcDocType = "Vault Transactions", $toPrsnID = -1)
{
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

function getVMSCageName($cageID, $vltID)
{
    $selSQL = "SELECT a.shelve_name 
  FROM inv.inv_shelf a
  WHERE (a.store_id=" . $vltID . " or " . $vltID . "<=0) 
  and a.line_id=" . $cageID . " ORDER BY line_id DESC LIMIT 1 OFFSET 0";
    $result1 = executeSQLNoParams($selSQL);
    while ($row = loc_db_fetch_array($result1)) {
        return $row[0];
    }
    return "";
}

function get_CageItems($pkID, $searchWord, $searchIn, $offset, $limit_size)
{
    $whereCls = "";
    if ($searchIn == "Item/Denomination") {
        $whereCls = " and (b.item_code || ' ' || b.item_desc ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Money Type") {
        $whereCls = " and (a.itm_state_clsfctn ilike '" . loc_db_escape_string($searchWord) . "')";
    }
    $strSql = "SELECT a.bal_id, a.store_vault_id, a.cage_shelve_id, a.item_id, a.stock_tot_qty, 
       a.source_trns_ids, a.bals_date, a.itm_state_clsfctn,
       b.item_code, b.item_desc, b.category_id, c.cat_name,
       b.item_type, b.base_uom_id, d.uom_name, b.orgnl_selling_price, 
       b.value_price_crncy_id, gst.get_pssbl_val(b.value_price_crncy_id) crncy_nm,
       (a.stock_tot_qty*b.orgnl_selling_price) ttl_val,
       inv.get_store_name(a.store_vault_id),
       inv.get_shelve_name(a.cage_shelve_id)
        FROM vms.vms_stock_daily_bals a, 
                inv.inv_itm_list b, 
                inv.inv_product_categories c, 
                inv.unit_of_measure d
        WHERE (a.item_id = b.item_id and b.category_id = c.cat_id and d.uom_id = b.base_uom_id
        and a.bals_date = (SELECT MAX(z.bals_date) FROM vms.vms_stock_daily_bals z 
        WHERE a.store_vault_id=z.store_vault_id and a.cage_shelve_id=z.cage_shelve_id and a.item_id=z.item_id
        and a.itm_state_clsfctn=z.itm_state_clsfctn) and a.stock_tot_qty!=0 and a.cage_shelve_id=$pkID" . "$whereCls) 
        ORDER BY b.orgnl_selling_price DESC, b.item_code LIMIT " . $limit_size . " OFFSET " . abs($offset * $limit_size);

    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_CageItemsTtl($pkID, $searchWord, $searchIn)
{
    $whereCls = "";
    if ($searchIn == "Item/Denomination") {
        $whereCls = " and (b.item_code || ' ' || b.item_desc ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Money Type") {
        $whereCls = " and (a.itm_state_clsfctn ilike '" . loc_db_escape_string($searchWord) . "')";
    }
    $strSql = "SELECT count(1) 
  FROM vms.vms_stock_daily_bals a, 
	  inv.inv_itm_list b, 
	  inv.inv_product_categories c, 
	  inv.unit_of_measure d
  WHERE (a.item_id = b.item_id and b.category_id = c.cat_id and d.uom_id = b.base_uom_id
  and a.bals_date = (SELECT MAX(z.bals_date) FROM vms.vms_stock_daily_bals z 
  WHERE a.store_vault_id=z.store_vault_id and a.cage_shelve_id=z.cage_shelve_id and a.item_id=z.item_id
  and a.itm_state_clsfctn=z.itm_state_clsfctn) and a.stock_tot_qty!=0 and a.cage_shelve_id=$pkID" . "$whereCls)";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return 0;
}

function get_AllCageItems($pkID)
{
    $whereCls = "";
    $strSql = "SELECT distinct a.bal_id, a.store_vault_id, a.cage_shelve_id, a.item_id, a.stock_tot_qty, 
       a.source_trns_ids, a.bals_date, a.itm_state_clsfctn,
       b.item_code, b.item_desc, b.category_id, c.cat_name,
       b.item_type, b.base_uom_id, d.uom_name, b.orgnl_selling_price, 
       b.value_price_crncy_id, gst.get_pssbl_val(b.value_price_crncy_id) crncy_nm,
       (a.stock_tot_qty*b.orgnl_selling_price) ttl_val,
       inv.get_store_name(a.store_vault_id),
       inv.get_shelve_name(a.cage_shelve_id)
        FROM vms.vms_stock_daily_bals a, 
                inv.inv_itm_list b, 
                inv.inv_product_categories c, 
                inv.unit_of_measure d
        WHERE (a.item_id = b.item_id and b.category_id = c.cat_id and d.uom_id = b.base_uom_id
        and a.bals_date = (SELECT MAX(z.bals_date) FROM vms.vms_stock_daily_bals z 
        WHERE a.store_vault_id=z.store_vault_id and a.cage_shelve_id=z.cage_shelve_id and a.item_id=z.item_id
        and a.itm_state_clsfctn=z.itm_state_clsfctn) and a.stock_tot_qty!=0 and a.cage_shelve_id=$pkID" . "$whereCls) 
        ORDER BY b.orgnl_selling_price DESC, b.item_code";
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_SmmryCageItems($pkID)
{
    $whereCls = "";
    $strSql = "SELECT a.item_id, SUM(a.stock_tot_qty), 
       b.item_code, b.item_desc, d.uom_name, b.orgnl_selling_price, 
       b.value_price_crncy_id, gst.get_pssbl_val(b.value_price_crncy_id) crncy_nm,
       SUM(a.stock_tot_qty*b.orgnl_selling_price) ttl_val,
       b.item_type
        FROM inv.inv_itm_list b 
             LEFT OUTER JOIN inv.inv_product_categories c ON (b.category_id = c.cat_id) 
             LEFT OUTER JOIN    inv.unit_of_measure d ON (d.uom_id = b.base_uom_id) 
             LEFT OUTER JOIN    vms.vms_stock_daily_bals a ON (a.item_id = b.item_id and a.cage_shelve_id=$pkID and a.bals_date = (SELECT MAX(z.bals_date) FROM vms.vms_stock_daily_bals z 
        WHERE a.store_vault_id=z.store_vault_id and a.cage_shelve_id=z.cage_shelve_id and a.item_id=z.item_id
        and a.itm_state_clsfctn=z.itm_state_clsfctn))    
        WHERE (b.item_type ilike 'Vault%' and b.value_price_crncy_id=inv.get_shlv_act_crncy_id($pkID)" . "$whereCls) 
            GROUP BY 1,3,4,5,6,7,8,10
        ORDER BY b.orgnl_selling_price DESC, b.item_code";
    $result = executeSQLNoParams($strSql);
    //echo $strSql;
    return $result;
}

function get_CageItemsBnCrd($itemID, $cageID, $searchWord, $searchIn, $qStrtDte, $qEndDte, $offset, $limit_size)
{
    global $orgID;
    $whereCls = "";
    $offset = 0;
    $limit_size = 1000000;
    if ($searchIn == "Description") {
        $whereCls = " and (tbl1.comments_desc ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Money Type") {
        $whereCls = " and (tbl1.itm_state_clsfctn ilike '" . loc_db_escape_string($searchWord) . "')";
    } else {
        $whereCls = " and (tbl1.comments_desc ilike '" . loc_db_escape_string($searchWord) . "'"
            . " or tbl1.itm_state_clsfctn ilike '" . loc_db_escape_string($searchWord) . "')";
    }
    if ($qStrtDte != "") {
        $whereCls .= " AND (tbl1.last_update_date >= '" . loc_db_escape_string(cnvrtDMYTmToYMDTm($qStrtDte)) . "')";
    }
    if ($qEndDte != "") {
        $whereCls .= " AND (tbl1.last_update_date <= '" . loc_db_escape_string(cnvrtDMYTmToYMDTm($qEndDte)) . "')";
    }
    $strSql = "SELECT   tbl1.trndid,
                        tbl1.itm_id,
                        tbl1.item_code,
                        tbl1.item_desc,
                        tbl1.vlt_id,
                        tbl1.vlt_name,
                        tbl1.cage_id,
                        tbl1.cage_name,
                        m.store_vault_id,
                        m.cage_shelve_id,
                        m.itm_state_clsfctn,
                        to_char(to_date(m.bals_date,'YYYY-MM-DD'), 'DD-Mon-YYYY'),
                        tbl1.qnty, 
                        m.stock_tot_qty,
                        tbl1.uom,
                        tbl1.trans_type || ' [' || tbl1.trans_number ||']', 
                        tbl1.comments_desc,
                        tbl1.itm_state_clsfctn,
                        to_char(to_timestamp(tbl1.last_update_date,'YYYY-MM-DD HH24:MI:SS'), 'DD-Mon-YYYY HH24:MI:SS'),
                        m.unit_value,
                        tbl1.unit_value,
                        tbl1.crncy_id,
                        tbl1.crncy_nm,
                        tbl1.order_no,
                        tbl1.bals_afta,
               vms.get_ltst_stock_bals1(tbl1.vlt_id, tbl1.cage_id, tbl1.itm_id::INTEGER, '',
                                                     to_char(
                                                         to_timestamp('" . loc_db_escape_string($qStrtDte) . "', 'DD-Mon-YYYY HH24:MI:SS')
                                                         -
                                                         INTERVAL '1 day',
                                                         'YYYY-MM-DD'))                  opng_cage_value,
               vms.get_ltst_stock_bals1(tbl1.vlt_id, tbl1.cage_id, tbl1.itm_id::INTEGER, '',
                                                     to_char(
                                                         to_timestamp('" . loc_db_escape_string($qEndDte) . "', 'DD-Mon-YYYY HH24:MI:SS'),
                                                         'YYYY-MM-DD'))                 clsng_cage_value,
                                                         m.bals_date
        FROM (
    SELECT b.trans_det_ln_id trndid,
                        a.trans_type,
                        a.trans_number, 
                        a.comments_desc||' '||a.reversal_reason comments_desc,
                        c.item_code, 
                        c.item_desc, 
                        CASE WHEN b.src_cage_shelve_id=" . $cageID . " THEN -1*b.doc_qty ELSE b.doc_qty END qnty, 
                        d.uom_name uom,
                        a.trans_date last_update_date,
                        b.itm_id, 
                        CASE WHEN b.src_cage_shelve_id = " . $cageID . " THEN b.src_store_vault_id ELSE -1 END vlt_id, 
                        CASE WHEN b.src_cage_shelve_id = " . $cageID . " THEN inv.get_store_name(b.src_store_vault_id) WHEN b.dest_cage_shelve_id = " . $cageID . " THEN inv.get_store_name(b.dest_store_vault_id) ELSE '' END vlt_name,
                        CASE WHEN b.src_cage_shelve_id = " . $cageID . " THEN b.src_cage_shelve_id WHEN b.dest_cage_shelve_id = " . $cageID . " THEN b.dest_cage_shelve_id ELSE -1 END cage_id, 
                        CASE WHEN b.src_cage_shelve_id = " . $cageID . " THEN inv.get_shelve_name(b.src_cage_shelve_id) WHEN b.dest_cage_shelve_id = " . $cageID . " THEN inv.get_shelve_name(b.dest_cage_shelve_id) ELSE '' END cage_name,
                        CASE WHEN b.src_cage_shelve_id = " . $cageID . " THEN b.src_itm_state_clsfctn WHEN b.dest_cage_shelve_id = " . $cageID . " THEN b.dst_itm_state_clsfctn ELSE '' END itm_state_clsfctn,
                        b.unit_value,
                        c.value_price_crncy_id crncy_id,
                        gst.get_pssbl_val(c.value_price_crncy_id) crncy_nm,
                        1 order_no,
                        src_balance_afta_trns bals_afta
        FROM vms.vms_transactions_hdr a, sec.sec_users y, vms.vms_transaction_lines b, 
        inv.inv_itm_list c, inv.unit_of_measure d
        WHERE ((b.src_cage_shelve_id=" . $cageID . ") AND (a.trans_hdr_id = b.trans_hdr_id AND b.itm_id = c.item_id AND c.base_uom_id = d.uom_id) 
        AND (a.approval_status ilike 'Authorized' or b.is_itm_delivered='1') AND (a.org_id =" . $orgID . ") AND 
        (a.created_by=y.user_id) AND b.itm_id=" . $itemID . ")
            UNION
         SELECT b.trans_det_ln_id trndid,
                        a.trans_type,
                        a.trans_number, 
                        a.comments_desc||' '||a.reversal_reason comments_desc,
                        c.item_code, 
                        c.item_desc, 
                        CASE WHEN b.dest_cage_shelve_id=" . $cageID . " THEN b.doc_qty ELSE  -1*b.doc_qty END qnty, 
                        d.uom_name uom,
                        a.trans_date last_update_date,
                        b.itm_id, 
                        CASE WHEN b.dest_cage_shelve_id = " . $cageID . " THEN b.dest_store_vault_id WHEN b.src_cage_shelve_id = " . $cageID . " THEN b.src_store_vault_id ELSE -1 END vlt_id, 
                        CASE WHEN b.dest_cage_shelve_id = " . $cageID . " THEN inv.get_store_name(b.dest_store_vault_id) WHEN b.src_cage_shelve_id = " . $cageID . " THEN inv.get_store_name(b.src_store_vault_id) ELSE '' END vlt_name,
                        CASE WHEN b.dest_cage_shelve_id = " . $cageID . " THEN b.dest_cage_shelve_id WHEN b.src_cage_shelve_id = " . $cageID . " THEN b.src_cage_shelve_id ELSE -1 END cage_id, 
                        CASE WHEN b.dest_cage_shelve_id = " . $cageID . " THEN inv.get_shelve_name(b.dest_cage_shelve_id) WHEN b.src_cage_shelve_id = " . $cageID . " THEN inv.get_shelve_name(b.src_cage_shelve_id) ELSE '' END cage_name,
                        CASE WHEN b.dest_cage_shelve_id = " . $cageID . " THEN b.dst_itm_state_clsfctn WHEN b.src_cage_shelve_id = " . $cageID . " THEN b.src_itm_state_clsfctn ELSE '' END itm_state_clsfctn,
                        b.unit_value,
                        c.value_price_crncy_id crncy_id,
                        gst.get_pssbl_val(c.value_price_crncy_id) crncy_nm,
                        2 order_no,
                        dst_balance_afta_trns bals_afta                        
        FROM vms.vms_transactions_hdr a, sec.sec_users y, vms.vms_transaction_lines b, 
        inv.inv_itm_list c, inv.unit_of_measure d
        WHERE ((b.dest_cage_shelve_id=" . $cageID . ") AND (a.trans_hdr_id = b.trans_hdr_id AND b.itm_id = c.item_id AND c.base_uom_id = d.uom_id) 
        AND (a.approval_status ilike 'Authorized' or b.is_itm_delivered='1') AND (a.org_id =" . $orgID . ") AND 
        (a.created_by=y.user_id) AND b.itm_id=" . $itemID . ")
UNION
      SELECT b.trans_det_ln_id trndid,
                        a.trans_type,
                        a.trans_number, 
                        a.comments_desc||' '||a.reversal_reason comments_desc,
                        c.item_code, 
                        c.item_desc, 
                        CASE WHEN b.src_cage_shelve_id=" . $cageID . " THEN -1*b.doc_qty ELSE b.doc_qty END qnty, 
                        d.uom_name uom,
                        a.trans_date last_update_date,
                        b.itm_id, 
                        CASE WHEN b.src_cage_shelve_id = " . $cageID . " THEN b.src_store_vault_id WHEN b.dest_cage_shelve_id = " . $cageID . " THEN b.dest_store_vault_id ELSE -1 END vlt_id, 
                        CASE WHEN b.src_cage_shelve_id = " . $cageID . " THEN inv.get_store_name(b.src_store_vault_id) WHEN b.dest_cage_shelve_id = " . $cageID . " THEN inv.get_store_name(b.dest_store_vault_id) ELSE '' END vlt_name,
                        CASE WHEN b.src_cage_shelve_id = " . $cageID . " THEN b.src_cage_shelve_id WHEN b.dest_cage_shelve_id = " . $cageID . " THEN b.dest_cage_shelve_id ELSE -1 END cage_id, 
                        CASE WHEN b.src_cage_shelve_id = " . $cageID . " THEN inv.get_shelve_name(b.src_cage_shelve_id) WHEN b.dest_cage_shelve_id = " . $cageID . " THEN inv.get_shelve_name(b.dest_cage_shelve_id) ELSE '' END cage_name,
                        CASE WHEN b.src_cage_shelve_id = " . $cageID . " THEN b.src_itm_state_clsfctn WHEN b.dest_cage_shelve_id = " . $cageID . " THEN b.dst_itm_state_clsfctn ELSE '' END itm_state_clsfctn,
                        b.unit_value,
                        c.value_price_crncy_id crncy_id,
                        gst.get_pssbl_val(c.value_price_crncy_id) crncy_nm,
                        1 order_no,
                        src_balance_b4_trns + (b.unit_value*(CASE WHEN b.src_cage_shelve_id=" . $cageID . " THEN -1*b.doc_qty ELSE b.doc_qty END)) bals_afta
        FROM vms.vms_transactions_hdr a, sec.sec_users y, 
        vms.vms_transaction_pymnt b, inv.inv_itm_list c, 
        inv.unit_of_measure d
        WHERE ((b.src_cage_shelve_id=" . $cageID . ") AND (a.trans_hdr_id = b.trans_hdr_id AND b.itm_id = c.item_id AND c.base_uom_id = d.uom_id) 
        AND (a.approval_status ilike 'Authorized' or b.is_itm_delivered='1') AND (a.org_id =" . $orgID . ") AND 
        (a.created_by=y.user_id) AND b.itm_id=" . $itemID . ")
            UNION
      SELECT b.trans_det_ln_id trndid,
                        a.trans_type,
                        a.trans_number, 
                        a.comments_desc||' '||a.reversal_reason comments_desc,
                        c.item_code, 
                        c.item_desc, 
                        CASE WHEN b.dest_cage_shelve_id=" . $cageID . " THEN b.doc_qty ELSE  -1*b.doc_qty END qnty, 
                        d.uom_name uom,
                        a.trans_date last_update_date,
                        b.itm_id, 
                        CASE WHEN b.dest_cage_shelve_id = " . $cageID . " THEN b.dest_store_vault_id WHEN b.src_cage_shelve_id = " . $cageID . " THEN b.src_store_vault_id ELSE -1 END vlt_id, 
                        CASE WHEN b.dest_cage_shelve_id = " . $cageID . " THEN inv.get_store_name(b.dest_store_vault_id) WHEN b.src_cage_shelve_id = " . $cageID . " THEN inv.get_store_name(b.src_store_vault_id) ELSE '' END vlt_name,
                        CASE WHEN b.dest_cage_shelve_id = " . $cageID . " THEN b.dest_cage_shelve_id WHEN b.src_cage_shelve_id = " . $cageID . " THEN b.src_cage_shelve_id ELSE -1 END cage_id, 
                        CASE WHEN b.dest_cage_shelve_id = " . $cageID . " THEN inv.get_shelve_name(b.dest_cage_shelve_id) WHEN b.src_cage_shelve_id = " . $cageID . " THEN inv.get_shelve_name(b.src_cage_shelve_id) ELSE '' END cage_name,
                        CASE WHEN b.dest_cage_shelve_id = " . $cageID . " THEN b.dst_itm_state_clsfctn WHEN b.src_cage_shelve_id = " . $cageID . " THEN b.src_itm_state_clsfctn ELSE '' END itm_state_clsfctn,
                        b.unit_value,
                        c.value_price_crncy_id crncy_id,
                        gst.get_pssbl_val(c.value_price_crncy_id) crncy_nm,
                        2 order_no,
                        dst_balance_b4_trns + (b.unit_value*(CASE WHEN b.dest_cage_shelve_id=" . $cageID . " THEN b.doc_qty ELSE  -1*b.doc_qty END)) bals_afta
        FROM vms.vms_transactions_hdr a, sec.sec_users y, 
        vms.vms_transaction_pymnt b, inv.inv_itm_list c, 
        inv.unit_of_measure d
        WHERE ((b.dest_cage_shelve_id=" . $cageID . ") AND (a.trans_hdr_id = b.trans_hdr_id AND b.itm_id = c.item_id AND c.base_uom_id = d.uom_id) 
        AND (a.approval_status ilike 'Authorized' or b.is_itm_delivered='1') AND (a.org_id =" . $orgID . ") AND 
        (a.created_by=y.user_id) AND b.itm_id=" . $itemID . ")            
UNION
      SELECT b.cash_analysis_id trndid,
                        a.trns_type,
                        a.trns_no, 
                        a.description||' '||a.reversal_reason comments_desc,
                        c.item_code, 
                        c.item_desc, 
                        CASE WHEN a.trns_type='WITHDRAWAL' THEN -1*b.qty ELSE b.qty END qnty, 
                        d.uom_name uom,
                        a.trns_date last_update_date,
                        c.item_id, 
                        b.vault_id vlt_id, 
                        inv.get_store_name(b.vault_id) vlt_name,
                        b.cage_shelve_id cage_id, 
                        inv.get_shelve_name(b.cage_shelve_id) cage_name,
                        b.item_state itm_state_clsfctn,
                        b.unit_value,
                        c.value_price_crncy_id crncy_id,
                        gst.get_pssbl_val(c.value_price_crncy_id) crncy_nm,
                        3 order_no,
                        cage_balance_b4_trns + (b.unit_value*(CASE WHEN a.trns_type='WITHDRAWAL' THEN -1*b.qty ELSE b.qty END)) bals_afta
        FROM mcf.mcf_cust_account_transactions a, sec.sec_users y, 
        mcf.mcf_account_trns_cash_analysis b, inv.inv_itm_list c, 
        inv.unit_of_measure d, mcf.mcf_currency_denominations e
        WHERE ((b.cage_shelve_id=" . $cageID . ") AND (a.acct_trns_id = b.acct_trns_id AND b.denomination_id = e.crncy_denom_id 
            AND e.vault_item_id = c.item_id AND c.base_uom_id = d.uom_id AND b.qty != 0) 
        AND (a.status ilike 'Paid' or a.status ilike 'Received' or a.status ilike 'Void') AND (a.org_id =" . $orgID . " OR a.org_id IS NULL) AND 
        (a.created_by=y.user_id) AND c.item_id=" . $itemID . ")
         UNION
                   SELECT
                     b.cash_analysis_id                              trndid,
                     'BATCH TRANSACTION'                             trns_type,
                     a.batch_number                                  trns_no,
                     'BATCH TRANSACTION FROM Relations Manager:' || prs.get_prsn_name(a.lnkd_person_id) || ' Staff No: '
                     ||
                     prs.get_prsn_loc_id(a.lnkd_person_id) || ' ' || a.rmrk_cmmnt || ' ' ||
                     a.reversal_reason                               comments_desc,
                        c.item_code, 
                        c.item_desc, 
                        b.qty qnty, 
                        d.uom_name uom,
                        a.batch_date last_update_date,
                        c.item_id, 
                        b.vault_id vlt_id, 
                        inv.get_store_name(b.vault_id) vlt_name,
                        b.cage_shelve_id cage_id, 
                        inv.get_shelve_name(b.cage_shelve_id) cage_name,
                        b.item_state itm_state_clsfctn,
                        b.unit_value,
                        c.value_price_crncy_id crncy_id,
                        gst.get_pssbl_val(c.value_price_crncy_id) crncy_nm,
                        3 order_no,
                        cage_balance_b4_trns + (b.unit_value*(b.qty)) bals_afta
                   FROM
                     mcf.mcf_bulk_trns_hdr a, sec.sec_users y, mcf.mcf_account_trns_cash_analysis b,
                     inv.inv_itm_list c, inv.unit_of_measure d,
                     mcf.mcf_currency_denominations e
                   WHERE ((b.cage_shelve_id = " . $cageID . ") AND
                          (a.bulk_trns_hdr_id = b.bulk_trns_hdr_id AND b.acct_trns_id <= 0 AND
                           b.denomination_id = e.crncy_denom_id AND e.vault_item_id = c.item_id AND b.qty != 0 AND c.base_uom_id = d.uom_id)
                          AND (a.status ILIKE 'Processed' OR a.status ILIKE 'Void') AND
                          (a.org_id = " . $orgID . " OR a.org_id IS NULL) AND (a.created_by = y.user_id) AND
                          c.item_id = " . $itemID . ")  
                              ) tbl1 
        left outer join vms.vms_stock_daily_bals m on (m.store_vault_id = tbl1.vlt_id and m.cage_shelve_id = tbl1.cage_id 
        and tbl1.itm_state_clsfctn=m.itm_state_clsfctn and tbl1.itm_id=m.item_id and substr(tbl1.last_update_date,1,10) = m.bals_date)
        WHERE tbl1.vlt_id>0 and tbl1.cage_id>0" . $whereCls .
        " ORDER BY tbl1.last_update_date ASC, tbl1.order_no ASC, m.bal_id ASC, tbl1.trndid ASC  LIMIT " . $limit_size . " OFFSET " . abs($offset * $limit_size);
    //echo $strSql;
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_CageItemsBnCrdTtl($itemID, $cageID, $searchWord, $searchIn, $qStrtDte, $qEndDte)
{
    global $orgID;
    $whereCls = "";
    if ($searchIn == "Description") {
        $whereCls = " and (tbl1.comments_desc ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Money Type") {
        $whereCls = " and (tbl1.itm_state_clsfctn ilike '" . loc_db_escape_string($searchWord) . "')";
    } else {
        $whereCls = " and (tbl1.comments_desc ilike '" . loc_db_escape_string($searchWord) . "'"
            . " or tbl1.itm_state_clsfctn ilike '" . loc_db_escape_string($searchWord) . "')";
    }
    if ($qStrtDte != "") {
        $whereCls .= " AND (tbl1.last_update_date >= '" . loc_db_escape_string(cnvrtDMYTmToYMDTm($qStrtDte)) . "')";
    }
    if ($qEndDte != "") {
        $whereCls .= " AND (tbl1.last_update_date <= '" . loc_db_escape_string(cnvrtDMYTmToYMDTm($qEndDte)) . "')";
    }
    $strSql = "SELECT count(tbl1.trans_det_ln_id) 
        FROM (
    SELECT b.trans_det_ln_id,
                        a.trans_type,
                        a.trans_number, 
                        a.comments_desc||' '||a.reversal_reason comments_desc,
                        c.item_code, 
                        c.item_desc, 
                        CASE WHEN b.src_cage_shelve_id=" . $cageID . " THEN -1*b.doc_qty ELSE b.doc_qty END qnty, 
                        d.uom_name uom,
                        a.last_update_date,
                        b.itm_id, 
                        CASE WHEN b.src_cage_shelve_id = " . $cageID . " THEN b.src_store_vault_id ELSE -1 END vlt_id, 
                        CASE WHEN b.src_cage_shelve_id = " . $cageID . " THEN inv.get_store_name(b.src_store_vault_id) WHEN b.dest_cage_shelve_id = " . $cageID . " THEN inv.get_store_name(b.dest_store_vault_id) ELSE '' END vlt_name,
                        CASE WHEN b.src_cage_shelve_id = " . $cageID . " THEN b.src_cage_shelve_id WHEN b.dest_cage_shelve_id = " . $cageID . " THEN b.dest_cage_shelve_id ELSE -1 END cage_id, 
                        CASE WHEN b.src_cage_shelve_id = " . $cageID . " THEN inv.get_shelve_name(b.src_cage_shelve_id) WHEN b.dest_cage_shelve_id = " . $cageID . " THEN inv.get_shelve_name(b.dest_cage_shelve_id) ELSE '' END cage_name,
                        CASE WHEN b.src_cage_shelve_id = " . $cageID . " THEN b.src_itm_state_clsfctn WHEN b.dest_cage_shelve_id = " . $cageID . " THEN b.dst_itm_state_clsfctn ELSE '' END itm_state_clsfctn,
                        b.unit_value,
                        c.value_price_crncy_id crncy_id,
                        gst.get_pssbl_val(c.value_price_crncy_id) crncy_nm,
                        1 order_no
        FROM vms.vms_transactions_hdr a, sec.sec_users y, vms.vms_transaction_lines b, 
        inv.inv_itm_list c, inv.unit_of_measure d
        WHERE ((b.src_cage_shelve_id=" . $cageID . ") AND (a.trans_hdr_id = b.trans_hdr_id AND b.itm_id = c.item_id AND c.base_uom_id = d.uom_id) 
        AND (a.approval_status ilike 'Authorized' or b.is_itm_delivered='1') AND (a.org_id =" . $orgID . ") AND 
        (a.created_by=y.user_id) AND b.itm_id=" . $itemID . ")
            UNION
         SELECT b.trans_det_ln_id,
                        a.trans_type,
                        a.trans_number, 
                        a.comments_desc||' '||a.reversal_reason comments_desc,
                        c.item_code, 
                        c.item_desc, 
                        CASE WHEN b.dest_cage_shelve_id=" . $cageID . " THEN b.doc_qty ELSE  -1*b.doc_qty END qnty, 
                        d.uom_name uom,
                        a.last_update_date,
                        b.itm_id, 
                        CASE WHEN b.dest_cage_shelve_id = " . $cageID . " THEN b.dest_store_vault_id WHEN b.src_cage_shelve_id = " . $cageID . " THEN b.src_store_vault_id ELSE -1 END vlt_id, 
                        CASE WHEN b.dest_cage_shelve_id = " . $cageID . " THEN inv.get_store_name(b.dest_store_vault_id) WHEN b.src_cage_shelve_id = " . $cageID . " THEN inv.get_store_name(b.src_store_vault_id) ELSE '' END vlt_name,
                        CASE WHEN b.dest_cage_shelve_id = " . $cageID . " THEN b.dest_cage_shelve_id WHEN b.src_cage_shelve_id = " . $cageID . " THEN b.src_cage_shelve_id ELSE -1 END cage_id, 
                        CASE WHEN b.dest_cage_shelve_id = " . $cageID . " THEN inv.get_shelve_name(b.dest_cage_shelve_id) WHEN b.src_cage_shelve_id = " . $cageID . " THEN inv.get_shelve_name(b.src_cage_shelve_id) ELSE '' END cage_name,
                        CASE WHEN b.dest_cage_shelve_id = " . $cageID . " THEN b.dst_itm_state_clsfctn WHEN b.src_cage_shelve_id = " . $cageID . " THEN b.src_itm_state_clsfctn ELSE '' END itm_state_clsfctn,
                        b.unit_value,
                        c.value_price_crncy_id crncy_id,
                        gst.get_pssbl_val(c.value_price_crncy_id) crncy_nm,
                        2 order_no                        
        FROM vms.vms_transactions_hdr a, sec.sec_users y, vms.vms_transaction_lines b, 
        inv.inv_itm_list c, inv.unit_of_measure d
        WHERE ((b.dest_cage_shelve_id=" . $cageID . ") AND (a.trans_hdr_id = b.trans_hdr_id AND b.itm_id = c.item_id AND c.base_uom_id = d.uom_id) 
        AND (a.approval_status ilike 'Authorized' or b.is_itm_delivered='1') AND (a.org_id =" . $orgID . ") AND 
        (a.created_by=y.user_id) AND b.itm_id=" . $itemID . ")
UNION
      SELECT b.trans_det_ln_id,
                        a.trans_type,
                        a.trans_number, 
                        a.comments_desc||' '||a.reversal_reason comments_desc,
                        c.item_code, 
                        c.item_desc, 
                        CASE WHEN b.src_cage_shelve_id=" . $cageID . " THEN -1*b.doc_qty ELSE b.doc_qty END qnty, 
                        d.uom_name uom,
                        a.last_update_date,
                        b.itm_id, 
                        CASE WHEN b.src_cage_shelve_id = " . $cageID . " THEN b.src_store_vault_id WHEN b.dest_cage_shelve_id = " . $cageID . " THEN b.dest_store_vault_id ELSE -1 END vlt_id, 
                        CASE WHEN b.src_cage_shelve_id = " . $cageID . " THEN inv.get_store_name(b.src_store_vault_id) WHEN b.dest_cage_shelve_id = " . $cageID . " THEN inv.get_store_name(b.dest_store_vault_id) ELSE '' END vlt_name,
                        CASE WHEN b.src_cage_shelve_id = " . $cageID . " THEN b.src_cage_shelve_id WHEN b.dest_cage_shelve_id = " . $cageID . " THEN b.dest_cage_shelve_id ELSE -1 END cage_id, 
                        CASE WHEN b.src_cage_shelve_id = " . $cageID . " THEN inv.get_shelve_name(b.src_cage_shelve_id) WHEN b.dest_cage_shelve_id = " . $cageID . " THEN inv.get_shelve_name(b.dest_cage_shelve_id) ELSE '' END cage_name,
                        CASE WHEN b.src_cage_shelve_id = " . $cageID . " THEN b.src_itm_state_clsfctn WHEN b.dest_cage_shelve_id = " . $cageID . " THEN b.dst_itm_state_clsfctn ELSE '' END itm_state_clsfctn,
                        b.unit_value,
                        c.value_price_crncy_id crncy_id,
                        gst.get_pssbl_val(c.value_price_crncy_id) crncy_nm,
                        1 order_no
        FROM vms.vms_transactions_hdr a, sec.sec_users y, 
        vms.vms_transaction_pymnt b, inv.inv_itm_list c, 
        inv.unit_of_measure d
        WHERE ((b.src_cage_shelve_id=" . $cageID . ") AND (a.trans_hdr_id = b.trans_hdr_id AND b.itm_id = c.item_id AND c.base_uom_id = d.uom_id) 
        AND (a.approval_status ilike 'Authorized' or b.is_itm_delivered='1') AND (a.org_id =" . $orgID . ") AND 
        (a.created_by=y.user_id) AND b.itm_id=" . $itemID . ")
            UNION
      SELECT b.trans_det_ln_id,
                        a.trans_type,
                        a.trans_number, 
                        a.comments_desc||' '||a.reversal_reason comments_desc,
                        c.item_code, 
                        c.item_desc, 
                        CASE WHEN b.dest_cage_shelve_id=" . $cageID . " THEN b.doc_qty ELSE  -1*b.doc_qty END qnty, 
                        d.uom_name uom,
                        a.last_update_date,
                        b.itm_id, 
                        CASE WHEN b.dest_cage_shelve_id = " . $cageID . " THEN b.dest_store_vault_id WHEN b.src_cage_shelve_id = " . $cageID . " THEN b.src_store_vault_id ELSE -1 END vlt_id, 
                        CASE WHEN b.dest_cage_shelve_id = " . $cageID . " THEN inv.get_store_name(b.dest_store_vault_id) WHEN b.src_cage_shelve_id = " . $cageID . " THEN inv.get_store_name(b.src_store_vault_id) ELSE '' END vlt_name,
                        CASE WHEN b.dest_cage_shelve_id = " . $cageID . " THEN b.dest_cage_shelve_id WHEN b.src_cage_shelve_id = " . $cageID . " THEN b.src_cage_shelve_id ELSE -1 END cage_id, 
                        CASE WHEN b.dest_cage_shelve_id = " . $cageID . " THEN inv.get_shelve_name(b.dest_cage_shelve_id) WHEN b.src_cage_shelve_id = " . $cageID . " THEN inv.get_shelve_name(b.src_cage_shelve_id) ELSE '' END cage_name,
                        CASE WHEN b.dest_cage_shelve_id = " . $cageID . " THEN b.dst_itm_state_clsfctn WHEN b.src_cage_shelve_id = " . $cageID . " THEN b.src_itm_state_clsfctn ELSE '' END itm_state_clsfctn,
                        b.unit_value,
                        c.value_price_crncy_id crncy_id,
                        gst.get_pssbl_val(c.value_price_crncy_id) crncy_nm,
                        2 order_no
        FROM vms.vms_transactions_hdr a, sec.sec_users y, 
        vms.vms_transaction_pymnt b, inv.inv_itm_list c, 
        inv.unit_of_measure d
        WHERE ((b.dest_cage_shelve_id=" . $cageID . ") AND (a.trans_hdr_id = b.trans_hdr_id AND b.itm_id = c.item_id AND c.base_uom_id = d.uom_id) 
        AND (a.approval_status ilike 'Authorized' or b.is_itm_delivered='1') AND (a.org_id =" . $orgID . ") AND 
        (a.created_by=y.user_id) AND b.itm_id=" . $itemID . ")            
    UNION
      SELECT b.cash_analysis_id,
                        a.trns_type,
                        a.trns_no, 
                        a.description||' '||a.reversal_reason comments_desc,
                        c.item_code, 
                        c.item_desc, 
                        CASE WHEN a.trns_type='WITHDRAWAL' THEN -1*b.qty ELSE b.qty END qnty, 
                        d.uom_name uom,
                        a.last_update_date,
                        c.item_id, 
                        b.vault_id vlt_id, 
                        inv.get_store_name(b.vault_id) vlt_name,
                        b.cage_shelve_id cage_id, 
                        inv.get_shelve_name(b.cage_shelve_id) cage_name,
                        b.item_state itm_state_clsfctn,
                        b.unit_value,
                        c.value_price_crncy_id crncy_id,
                        gst.get_pssbl_val(c.value_price_crncy_id) crncy_nm,
                        3 order_no
        FROM mcf.mcf_cust_account_transactions a, sec.sec_users y, 
        mcf.mcf_account_trns_cash_analysis b, inv.inv_itm_list c, 
        inv.unit_of_measure d, mcf.mcf_currency_denominations e
        WHERE ((b.cage_shelve_id=" . $cageID . ") AND (a.acct_trns_id = b.acct_trns_id AND b.denomination_id = e.crncy_denom_id AND e.vault_item_id = c.item_id AND c.base_uom_id = d.uom_id) 
        AND (a.status ilike 'Paid' or a.status ilike 'Received') AND (a.org_id =" . $orgID . " OR a.org_id IS NULL) AND 
        (a.created_by=y.user_id) AND c.item_id=" . $itemID . ")            
         UNION
                   SELECT
                     b.cash_analysis_id                              trndid,
                     'BATCH TRANSACTION'                             trns_type,
                     a.batch_number                                  trns_no,
                     'BATCH TRANSACTION FROM Relations Manager:' || prs.get_prsn_name(a.lnkd_person_id) || ' Staff No: '
                     ||
                     prs.get_prsn_loc_id(a.lnkd_person_id) || ' ' || a.rmrk_cmmnt || ' ' ||
                     a.reversal_reason                               comments_desc,
                        c.item_code, 
                        c.item_desc, 
                        b.qty qnty, 
                        d.uom_name uom,
                        a.batch_date last_update_date,
                        c.item_id, 
                        b.vault_id vlt_id, 
                        inv.get_store_name(b.vault_id) vlt_name,
                        b.cage_shelve_id cage_id, 
                        inv.get_shelve_name(b.cage_shelve_id) cage_name,
                        b.item_state itm_state_clsfctn,
                        b.unit_value,
                        c.value_price_crncy_id crncy_id,
                        gst.get_pssbl_val(c.value_price_crncy_id) crncy_nm,
                        3 order_no
                   FROM
                     mcf.mcf_bulk_trns_hdr a, sec.sec_users y, mcf.mcf_account_trns_cash_analysis b,
                     inv.inv_itm_list c, inv.unit_of_measure d,
                     mcf.mcf_currency_denominations e
                   WHERE ((b.cage_shelve_id = " . $cageID . ") AND
                          (a.bulk_trns_hdr_id = b.bulk_trns_hdr_id AND b.acct_trns_id <= 0 AND
                           b.denomination_id = e.crncy_denom_id AND e.vault_item_id = c.item_id AND b.qty != 0 AND c.base_uom_id = d.uom_id)
                          AND (a.status ILIKE 'Processed' OR a.status ILIKE 'Void') AND
                          (a.org_id = " . $orgID . " OR a.org_id IS NULL) AND (a.created_by = y.user_id) AND
                          c.item_id = " . $itemID . ")  
                              ) tbl1 
        left outer join vms.vms_stock_daily_bals m on (m.store_vault_id = tbl1.vlt_id and m.cage_shelve_id = tbl1.cage_id 
        and tbl1.itm_state_clsfctn=m.itm_state_clsfctn and tbl1.itm_id=m.item_id and substr(tbl1.last_update_date,1,10)=m.bals_date)
        WHERE tbl1.vlt_id>0 and tbl1.cage_id>0" . $whereCls;
    //echo $strSql;
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return 0;
}

function get_VMSGlIntrfc($searchWord, $searchIn, $offset, $limit_size, $orgID, $dte1, $dte2, $notgonetogl, $imblcnTrns, $usrTrns, $lowVal, $highVal)
{
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
                from  vms.vms_gl_interface v
                group by v.src_doc_id,v.src_doc_typ, substring(v.trnsctn_date from 0 for 11)
                having round(COALESCE(SUM(v.dbt_amount),0)-COALESCE(SUM(v.crdt_amount),0),2) != 0) tbl1) like '%,'||a.interface_id||',%')";
        /* $imblnce_trns = " and (a.interface_id IN (select MAX(v.interface_id)
          from  vms.vms_gl_interface v
          group by v.src_doc_typ, v.src_doc_id, abs(v.net_amount), v.src_doc_line_id
          having count(v.src_doc_line_id) %2 != 0 or v.src_doc_id<=0 or v.src_doc_id IS NULL
          or vms.get_src_doc_num(v.src_doc_id,v.src_doc_typ) IS NULL
          or vms.get_src_doc_num(v.src_doc_id,v.src_doc_typ)=''))"; */
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
            "' or vms.get_src_doc_num(a.src_doc_id,a.src_doc_typ) ilike '" . loc_db_escape_string($searchWord) .
            "') and ";
    } else if ($searchIn == "Transaction Description") {
        $whereCls = "(a.transaction_desc ilike '" . loc_db_escape_string($searchWord) .
            "') and ";
    }
    $strSql = "SELECT a.accnt_id, b.accnt_num, b.accnt_name, a.transaction_desc, 
to_char(to_timestamp(a.trnsctn_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS'), a.dbt_amount, " .
        "a.crdt_amount, a.src_doc_line_id, a.src_doc_typ, a.gl_batch_id, " .
        "(select d.batch_name from accb.accb_trnsctn_batches d where d.batch_id = a.gl_batch_id) btch_nm, a.interface_id, a.func_cur_id " .
        ", a.src_doc_id, vms.get_src_doc_num(a.src_doc_id,a.src_doc_typ), gst.get_pssbl_val(a.func_cur_id) " .
        "FROM vms.vms_gl_interface a, accb.accb_chart_of_accnts b " .
        "WHERE ((a.accnt_id = b.accnt_id) and " . $whereCls . "(b.org_id = " . $orgID . ")" . $to_gl .
        $imblnce_trns . $usrTrnsSql . $amntCls . " and (to_timestamp(a.trnsctn_date,'YYYY-MM-DD HH24:MI:SS') between to_timestamp('" . $dte1 .
        "','YYYY-MM-DD HH24:MI:SS') AND to_timestamp('" . $dte2 . "','YYYY-MM-DD HH24:MI:SS'))) " .
        "ORDER BY a.interface_id DESC LIMIT " . $limit_size . " OFFSET " . abs($offset * $limit_size);
    //echo $strSql;
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_VMSGlIntrfcTtl($searchWord, $searchIn, $orgID, $dte1, $dte2, $notgonetogl, $imblcnTrns, $usrTrns, $lowVal, $highVal)
{
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
        //where gl_batch_id = -1
        $imblnce_trns = " and ((Select string_agg(tbl1.ids1, ',') from (select string_agg(',' || v.interface_id||',', '') ids1
                from  vms.vms_gl_interface v
                group by v.src_doc_id,v.src_doc_typ, substring(v.trnsctn_date from 0 for 11)
                having round(COALESCE(SUM(v.dbt_amount),0)-COALESCE(SUM(v.crdt_amount),0),2) != 0) tbl1) like '%,'||a.interface_id||',%')";
        /* $imblnce_trns = " and (a.interface_id IN (select MAX(v.interface_id)
          from  vms.vms_gl_interface v
          group by v.src_doc_typ, v.src_doc_id, abs(v.net_amount), v.src_doc_line_id
          having count(v.src_doc_line_id) %2 != 0 or v.src_doc_id<=0 or v.src_doc_id IS NULL
          or vms.get_src_doc_num(v.src_doc_id,v.src_doc_typ) IS NULL
          or vms.get_src_doc_num(v.src_doc_id,v.src_doc_typ)=''))"; */
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
            "' or vms.get_src_doc_num(a.src_doc_id,a.src_doc_typ) ilike '" . loc_db_escape_string($searchWord) .
            "') and ";
    } else if ($searchIn == "Transaction Description") {
        $whereCls = "(a.transaction_desc ilike '" . loc_db_escape_string($searchWord) .
            "') and ";
    }
    $strSql = "SELECT count(1) " .
        "FROM vms.vms_gl_interface a, accb.accb_chart_of_accnts b " .
        "WHERE ((a.accnt_id = b.accnt_id) and " . $whereCls . "(b.org_id = " . $orgID . ")" . $to_gl .
        $imblnce_trns . $usrTrnsSql . $amntCls . " and (to_timestamp(a.trnsctn_date,'YYYY-MM-DD HH24:MI:SS') between to_timestamp('" . $dte1 .
        "','YYYY-MM-DD HH24:MI:SS') AND to_timestamp('" . $dte2 . "','YYYY-MM-DD HH24:MI:SS'))) ";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return 0;
}

function isVMSGLIntrfcBlcdOrg($orgID)
{
    $strSql = "SELECT COALESCE(SUM(a.dbt_amount),0) dbt_sum, 
COALESCE(SUM(a.crdt_amount),0) crdt_sum 
FROM vms.vms_gl_interface a, accb.accb_chart_of_accnts b 
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

function getVMSGLIntrfcDffrnc($orgID)
{
    $strSql = "SELECT COALESCE(SUM(a.dbt_amount),0) dbt_sum, 
COALESCE(SUM(a.crdt_amount),0) crdt_sum 
FROM vms.vms_gl_interface a, accb.accb_chart_of_accnts b 
WHERE a.gl_batch_id = -1 and a.accnt_id = b.accnt_id and b.org_id=" . $orgID .
        " ";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        $dffrce1 = (float) $row[0] - (float) $row[1];
        return $dffrce1;
    }
    return 0;
}

function get_OneVMSGlIntrfcDet($intrfcID)
{
    $strSql = "SELECT a.accnt_id, b.accnt_num, b.accnt_name, a.transaction_desc, 
to_char(to_timestamp(a.trnsctn_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS'), a.dbt_amount, " .
        "a.crdt_amount, a.src_doc_line_id, a.src_doc_typ, a.gl_batch_id, " .
        "(select d.batch_name from accb.accb_trnsctn_batches d where d.batch_id = a.gl_batch_id) btch_nm, a.interface_id, a.func_cur_id, " .
        "a.src_doc_id, vms.get_src_doc_num(a.src_doc_id,a.src_doc_typ), gst.get_pssbl_val(a.func_cur_id), 
             a.trns_source, a.net_amount, a.entered_amnt, a.entered_amt_crncy_id, 
             gst.get_pssbl_val(a.entered_amt_crncy_id), a.accnt_crncy_amnt, a.accnt_crncy_id, 
             gst.get_pssbl_val(a.accnt_crncy_id), 
             a.func_cur_exchng_rate, a.accnt_cur_exchng_rate " .
        "FROM vms.vms_gl_interface a, accb.accb_chart_of_accnts b " .
        "WHERE ((a.accnt_id = b.accnt_id) and (a.interface_id = " . $intrfcID . "))";
    $result = executeSQLNoParams($strSql);
    return $result;
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

/*function getCstmrID($cstmrNm, $orgid)
{
    $sqlStr = "select cust_sup_id from scm.scm_cstmr_suplr where lower(cust_sup_name) = '" .
        loc_db_escape_string(strtolower($cstmrNm)) . "' and org_id=" . $orgid;
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -1;
}

function createCstmr($cstmrNm, $cstmrDesc, $clsfctn, $cstrmOrSpplr, $orgid, $dfltPyblAcnt, $dfltRcvblAcnt, $lnkdPrsn, $gender, $dob, $isenbled, $frmBrndNm, $orgType, $cmpnyRegNum, $dateIncorp, $typeOfIncorp, $vatNum, $tinNum, $ssnitNum, $numEmplys, $descSrvcs, $listSrvcs)
{
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

function updateCstmr($cstmrid, $cstmrNm, $cstmrDesc, $clsfctn, $cstrmOrSpplr, $orgid, $dfltPyblAcnt, $dfltRcvblAcnt, $lnkdPrsn, $gender, $dob, $isenbled, $frmBrndNm, $orgType, $cmpnyRegNum, $dateIncorp, $typeOfIncorp, $vatNum, $tinNum, $ssnitNum, $numEmplys, $descSrvcs, $listSrvcs)
{
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
}*/

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
/*
function createCstmrSite($cstmrID, $cntctPrsn, $cntctNos, $email, $siteNm, $siteDesc, $bankNm, $bankBrnch, $bnkNum, $wthTaxID, $dscntCodeID, $bllngAddrs, $shpToAddrs, $swftCode, $natnlty, $ntnltyIDType, $idNum, $dateIssued, $expryDate, $otherInfo, $isenabled, $ibanNum, $accntCurID)
{
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

function updateCstmrSite($siteID, $cstmrID, $cntctPrsn, $cntctNos, $email, $siteNm, $siteDesc, $bankNm, $bankBrnch, $bnkNum, $wthTaxID, $dscntCodeID, $bllngAddrs, $shpToAddrs, $swftCode, $natnlty, $ntnltyIDType, $idNum, $dateIssued, $expryDate, $otherInfo, $isenabled, $ibanNum, $accntCurID)
{
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
}*/

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
            if ((($_FILES["daCstmrPicture"]["type"] == "image/gif") ||
                    ($_FILES["daCstmrPicture"]["type"] == "image/jpeg") ||
                    ($_FILES["daCstmrPicture"]["type"] == "image/jpg") ||
                    ($_FILES["daCstmrPicture"]["type"] == "image/pjpeg") ||
                    ($_FILES["daCstmrPicture"]["type"] == "image/x-png") ||
                    ($_FILES["daCstmrPicture"]["type"] == "image/png") ||
                    in_array($extension, $allowedExts)) &&
                ($_FILES["daCstmrPicture"]["size"] < 2000000)
            ) {
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
            //$msg .= "Temp file: " . $_FILES["daVMSAttchmnt"]["tmp_name"] . "<br>";
            if ((($_FILES["daCstmrAttchmnt"]["type"] == "image/gif") ||
                    ($_FILES["daCstmrAttchmnt"]["type"] == "image/jpeg") ||
                    ($_FILES["daCstmrAttchmnt"]["type"] == "image/jpg") ||
                    ($_FILES["daCstmrAttchmnt"]["type"] == "image/pjpeg") ||
                    ($_FILES["daCstmrAttchmnt"]["type"] == "image/x-png") ||
                    ($_FILES["daCstmrAttchmnt"]["type"] == "image/png") ||
                    in_array($extension, $allowedExts)) &&
                ($_FILES["daCstmrAttchmnt"]["size"] < 10000000)
            ) {
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

function get_VMSItems($searchFor, $searchIn, $offset, $limit_size, $sortBy)
{
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
        " selling_price, item_type, vms.get_ltst_item_bals(item_id) total_qty, extra_info, other_desc, image, 
                (SELECT uom_name from inv.unit_of_measure WHERE uom_id = a.base_uom_id), " .
        " generic_name, trade_name, drug_usual_dsge, drug_max_dsge, 
                contraindications, food_interactions, orgnl_selling_price,
                (select cat_name FROM inv.inv_product_categories WHERE cat_id = category_id), 
                value_price_crncy_id, gst.get_pssbl_val(value_price_crncy_id), auto_dflt_in_vms_trns 
            from inv.inv_itm_list a " .
        "WHERE ((a.org_id = " . $orgID . " and a.item_type ilike 'VaultItem%')$whereClause) ORDER BY $ordrBy LIMIT " . $limit_size .
        " OFFSET " . abs($offset * $limit_size);
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_VMSItemsTtl($searchFor, $searchIn)
{
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
        "WHERE ((a.org_id = " . $orgID . " and a.item_type ilike 'VaultItem%')$whereClause)";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return 0;
}

function get_OneVMSItems($item_id)
{
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
            contraindications, food_interactions, total_qty, image
            FROM inv.inv_itm_list a WHERE a.item_id=" . $item_id;
    return executeSQLNoParams($strSql);
}

function get_OneVMSItemStores($item_id)
{
    $strSql = "SELECT row_number() over(order by b.subinv_name) as row , b.subinv_name, a.shelves,
            to_char(to_timestamp(a.start_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS'), 
            CASE WHEN a.end_date='' THEN a.end_date ELSE to_char(to_timestamp(a.end_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') END, 
            a.subinv_id, a.shelves_ids, a.stock_id 
            FROM inv.inv_stock a inner join inv.inv_itm_subinventories b ON a.subinv_id = b.subinv_id 
            WHERE a.itm_id = " . $item_id;
    return executeSQLNoParams($strSql);
}

function get_OneVMSItemUOMs($item_id)
{
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

function get_OneVMSItemDrgIntrctns($item_id)
{
    $strSql = "SELECT row_number() over(order by b.item_code) as row, 
          b.item_desc || '(' || b.item_code || ')', a.intrctn_effect,
          a.action, a.second_drug_id, a.drug_intrctn_id 
          FROM inv.inv_drug_interactions a inner join inv.inv_itm_list b ON a.second_drug_id = b.item_id 
          WHERE a.first_drug_id =" . $item_id . " order by 1";
    return executeSQLNoParams($strSql);
}

function getHgstUnitCostPrice($itmID)
{
    $strSql = "SELECT c.cost_price 
         FROM inv.inv_consgmt_rcpt_det c 
         WHERE (c.itm_id =" . $itmID . ") ORDER BY c.consgmt_id DESC LIMIT 1 OFFSET 0";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return 0;
}

function updateSellingPrice($itemID, $nwPrice, $orgnlPrice)
{
    global $usrID;
    $dateStr = getDB_Date_time();
    $updtSQL = "UPDATE inv.inv_itm_list SET 
                  selling_price=" . $nwPrice .
        ",orgnl_selling_price =" . $orgnlPrice .
        ", last_update_by=" . loc_db_escape_string($usrID) .
        ", last_update_date='" . loc_db_escape_string($dateStr) .
        "' WHERE (item_id = " . $itemID . ")";
    execUpdtInsSQL($updtSQL);
}

function getVMSItmID($itemNm)
{
    $sqlStr = "select item_id from inv.inv_itm_list where lower(item_code) = '" .
        loc_db_escape_string(strtolower($itemNm)) . "'";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -1;
}

function createVMSItm($itmNm, $itmDesc, $ctgryID, $orgid, $isenbled, $sllgPrice, $cogsID, $assetID, $revID, $salesRetID, $prchRetID, $expnsID, $txID, $dscntID, $chrgID, $minLvl, $maxLvl, $plnngEnbld, $itmType, $image, $extrInfo, $othrDesc, $baseUomID, $tmpltID, $gnrcNm, $tradeNm, $drugUslDsg, $drugMaxDsg, $cntrIndctns, $foodIntrctns, $orgnSllngPrc, $valCrncyID, $autoDfltVms)
{
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
        "', " . loc_db_escape_string($orgnSllngPrc) .
        ", " . loc_db_escape_string($valCrncyID) .
        ", '" . loc_db_escape_string($autoDfltVms) .
        "')";
    return execUpdtInsSQL($insSQL);
}

function updateVMSItem($itemid, $itmNm, $itmDesc, $ctgryID, $orgid, $isenbled, $sllgPrice, $cogsID, $assetID, $revID, $salesRetID, $prchRetID, $expnsID, $txID, $dscntID, $chrgID, $minLvl, $maxLvl, $plnngEnbld, $itmType, $image, $extrInfo, $othrDesc, $baseUomID, $tmpltID, $gnrcNm, $tradeNm, $drugUslDsg, $drugMaxDsg, $cntrIndctns, $foodIntrctns, $orgnSllngPrc, $valCrncyID, $autoDfltVms)
{
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
        "', orgnl_selling_price=" . loc_db_escape_string($orgnSllngPrc) .
        ", value_price_crncy_id=" . loc_db_escape_string($valCrncyID) .
        ", auto_dflt_in_vms_trns='" . loc_db_escape_string($autoDfltVms) .
        "' WHERE item_id = " . $itemid;
    return execUpdtInsSQL($insSQL);
}

function getVMSItemStockID($itmID, $storeID)
{
    $sqlStr = "select stock_id from inv.inv_stock where itm_id = " . loc_db_escape_string($itmID) .
        " and subinv_id = " . loc_db_escape_string($storeID);
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -1;
}

function createVMSItemStore($itmID, $storeID, $shelves, $orgID, $strtDte, $endDte, $shelveIDs)
{
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

function updateVMSItemStore($stockID, $itmID, $storeID, $shelves, $orgID, $strtDte, $endDte, $shelveIDs)
{
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

function getVMSItemUomID($itmID, $uomID)
{
    $sqlStr = "select itm_uom_id from inv.itm_uoms where item_id = " . loc_db_escape_string($itmID) . " and uom_id = " . loc_db_escape_string($uomID);
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -1;
}

function createVMSItemUom($itmID, $uomID, $cnvsnFctr, $sortOrdr, $prcLsTx, $sllngPrice)
{
    global $usrID;
    $dateStr = getDB_Date_time();
    $insSQL = "INSERT INTO inv.itm_uoms(
            item_id, uom_id, is_base_uom, cnvsn_factor, uom_level, 
            creation_date, created_by, last_update_date, last_update_by, 
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
    return execUpdtInsSQL($insSQL);
}

function updateVMSItemUom($itmUoMID, $itmID, $uomID, $cnvsnFctr, $sortOrdr, $prcLsTx, $sllngPrice)
{
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

function getVMSItemIntrctnID($itmID, $secondItmID)
{
    $sqlStr = "select drug_intrctn_id from inv.inv_drug_interactions where first_drug_id = " . loc_db_escape_string($itmID) . " and second_drug_id = " . loc_db_escape_string($secondItmID);
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -1;
}

function createVMSItemIntrctn($itmID, $secondItmID, $intrctnEffct, $actionItm)
{
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

function updateVMSItemIntrctn($intrctnID, $itmID, $secondItmID, $intrctnEffct, $actionItm)
{
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

function uploadDaImageItem($itemid, &$nwImgLoc)
{
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
            if ((($_FILES["daItemPicture"]["type"] == "image/gif") ||
                    ($_FILES["daItemPicture"]["type"] == "image/jpeg") ||
                    ($_FILES["daItemPicture"]["type"] == "image/jpg") ||
                    ($_FILES["daItemPicture"]["type"] == "image/pjpeg") ||
                    ($_FILES["daItemPicture"]["type"] == "image/x-png") ||
                    ($_FILES["daItemPicture"]["type"] == "image/png") ||
                    in_array($extension, $allowedExts)) &&
                ($_FILES["daItemPicture"]["size"] < 2000000)
            ) {
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

function deleteVMSItm($pkeyID, $extrInfo = "")
{
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
    $selSQL3 = "Select count(1) from vms.vms_stock_daily_bals "
        . "WHERE item_id= " . $pkeyID . "";
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

function deleteVMSItmStore($pkeyID, $extrInfo = "")
{
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

function deleteVMSItmUom($pkeyID, $extrInfo = "")
{
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

function deleteVMSItmIntrctn($pkeyID, $extrInfo = "")
{
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

function getVMSTrnsRdOnlyDsply($sbmtdVmsTrnsHdrID, $trnsType)
{
    //ReadOnly View
    global $trnsTypes;
    global $uName;
    global $gnrlTrnsDteDMYHMS;
    global $gnrlTrnsDteYMDHMS;
    global $fnccurid;
    global $fnccurnm;
    global $prsnid;
    global $orgID;
    global $vmsCstmrClsfctn;
    $sbmtdShelfID = -1;
    $sbmtdStoreID = -1;
    if ($sbmtdVmsTrnsHdrID <= 0) {
        restricted();
        exit();
    }
    $orgnlVmsTrnsHdrID = $sbmtdVmsTrnsHdrID;
    $rqStatus = "Not Submitted";
    $rqstatusColor = "red";
    $trnsType = "";
    $gnrtdTrnsNo = "";

    $gnrtdTrnsDate = $gnrlTrnsDteDMYHMS; //date('d-M-Y H:i:s');
    $gnrtdTrnsDate1 = $gnrlTrnsDteYMDHMS; //date('Y-m-d H:i:s');
    $prprdBy = "<span style=\"color:blue;font-weight:bold;\">" . $uName . "@" . $gnrtdTrnsDate . "</span>";
    $brnchLocID = -1;
    $brnchLoc = getGnrlRecNm("org.org_sites_locations", "location_id", "REPLACE(location_code_name || '.' || site_desc, '.' || location_code_name,'')", $brnchLocID);
    $crncyID = $fnccurid;
    $crncyIDNm = $fnccurnm;
    $srcVltID = -1;
    $srcCageID = -1;
    $destVltID = -1;
    $destCageID = -1;
    $srcVltNm = "";
    $srcCageNm = "";
    $destVltNm = "";
    $destCageNm = "";
    $vldtyStatus = "<span style=\"color:red;font-weight:bold;\">Not Validated</span>";
    $athrztnStatus = "<span style=\"color:red;font-weight:bold;\">Not Submitted</span>";
    $itmBaseUomNm = "pcs";
    $voidedTrnsHdrID = -1;
    $voidedTrnsType = "";
    $vmsTrnsDesc = "";
    $vmsTrnsClsfctn = "";
    $vmsTrnsTtlAmnt = 0;
    $vmsPymtTtlAmnt = 0;
    $vmsPymtCrncyID = -1;
    $vmsPymtCrncyNm = "";
    $mkReadOnly = "";
    $mkRmrkReadOnly = "";
    $cstmrID = -1;
    $cstmrNm = -1;
    $cstmrSiteID = -1;
    $exchangeRate = 0;
    $vmsTrnsPrsn = "N/A";
    $vmsOffctStaffLocID = getPersonLocID($prsnid);
    $vmsOffctStaff = getPrsnFullNm($prsnid);
    $vmsOffctStaffPrsID = $prsnid;
    $vmsChequeNo = "";
    $extrInputFlds = "";
    $capturedItemIDs = "";
    if ($sbmtdVmsTrnsHdrID > 0) {
        //Important! Must Check if One also has prmsn to Edit brought Trns Hdr ID
        calcAllVMSLinesTtlVals($sbmtdVmsTrnsHdrID);
        $result = get_One_VMSTrnsHdr($sbmtdVmsTrnsHdrID);
        if ($row = loc_db_fetch_array($result)) {
            $trnsType = $row[3];
            $rqStatus = $row[10];
            $voidedTrnsHdrID = (float) $row[13];
            $voidedTrnsType = $row[14];
            if ($rqStatus == "Not Submitted" || $rqStatus == "Withdrawn" || $rqStatus == "Rejected") {
                $rqstatusColor = "red";
                if ($voidedTrnsHdrID <= 0) {
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
            $gnrtdTrnsNo = $row[2];
            $gnrtdTrnsDate = $row[1];
            $uName11 = getUserName((float) $row[15]);
            $prprdBy = "<span style=\"color:blue;font-weight:bold;\">" . $uName11 . "@" . $gnrtdTrnsDate . "</span>";
            $brnchLocID = (int) $row[20];
            $brnchLoc = getGnrlRecNm("org.org_sites_locations", "location_id", "REPLACE(location_code_name || '.' || site_desc, '.' || location_code_name,'')", $brnchLocID);
            $crncyID = (int) $row[30];
            $crncyIDNm = $row[31];
            if ($crncyID <= 0) {
                $crncyID = $fnccurid;
                $crncyIDNm = $fnccurnm;
            }
            $srcVltID = (int) $row[22];
            $srcCageID = (int) $row[24];
            $dfltSrcItemState = getGnrlRecNm("inv.inv_shelf", "line_id", "dflt_item_state", $srcCageID);
            $destVltID = (int) $row[26];
            $destCageID = (int) $row[28];
            $dfltDstItemState = getGnrlRecNm("inv.inv_shelf", "line_id", "dflt_item_state", $destCageID);
            $srcVltNm = $row[23];
            $srcCageNm = $row[25];
            $destVltNm = $row[27];
            $destCageNm = $row[29];
            if ($row[12] == "Validated") {
                $vldtyStatus = "<span style=\"color:green;font-weight:bold;\">" . $row[12] . "</span>";
            } else {
                $vldtyStatus = "<span style=\"color:red;font-weight:bold;\">" . $row[12] . "</span>";
            }
            if ($row[10] == "Authorized") {
                $athrztnStatus = "<span style=\"color:green;font-weight:bold;\">" . $row[10] . "</span>";
            } else {
                $athrztnStatus = "<span style=\"color:red;font-weight:bold;\">" . $row[10] . "</span>";
            }
            if ($voidedTrnsHdrID <= 0) {
                $vmsTrnsDesc = $row[4];
            } else {
                $vmsTrnsDesc = $row[36];
            }
            $vmsTrnsClsfctn = $row[9];
            $vmsTrnsTtlAmnt = (float) $row[21];
            $vmsPymtTtlAmnt = (float) $row[34];
            $vmsPymtCrncyID = (float) $row[32];
            $vmsPymtCrncyNm = $row[33];
            $cstmrID = (float) $row[5];
            $cstmrNm = $row[6];
            $cstmrSiteID = (float) $row[7];
            $exchangeRate = $row[35];
            $vmsTrnsPrsn = $row[38];
            $vmsOffctStaffLocID = $row[39];
            $vmsOffctStaff = $row[40];
            $vmsChequeNo = $row[42];
            $vmsOffctStaffPrsID = (float) $row[41];
        }
    } else {
        $sbmtdVmsTrnsHdrID = getNewTrnsHdrID();
        if ($sbmtdShelfID > 0 && $sbmtdStoreID >= 0 && $trnsType = "Teller/Cashier Transfers") {
            $srcVltID = $sbmtdStoreID;
            $srcCageID = $sbmtdShelfID;
            $srcVltNm = getGnrlRecNm("inv.inv_itm_subinventories", "subinv_id", "subinv_name", $srcVltID);
            $srcCageNm = getGnrlRecNm("inv.inv_shelf", "line_id", "shelve_name", $srcCageID);
            //$destVltID = -1;
            //$destCageID = -1;
        }
        createVMSTrnsHdr($sbmtdVmsTrnsHdrID, $gnrtdTrnsDate1, $gnrtdTrnsNo, $trnsType, "", -1, -1, $voidedTrnsHdrID, $voidedTrnsType, "", $brnchLocID, 0.00, $srcVltID, $srcCageID, $destVltID, $destCageID, $crncyID, -1, 0, 0, "1", $vmsTrnsPrsn, $vmsOffctStaffPrsID);
    }
    if ($srcCageID <= 0 && $trnsType == "Vault/GL Account Transfers") {
        $invAssetAcntID = -1;
        $sbmtdShelfID = getLatestCage($prsnid, $srcCageID, $sbmtdStoreID, $invAssetAcntID);
        $srcVltID = $sbmtdStoreID;
        $srcCageID = $sbmtdShelfID;
        $srcVltNm = getGnrlRecNm("inv.inv_itm_subinventories", "subinv_id", "subinv_name", $srcVltID);
        $srcCageNm = getGnrlRecNm("inv.inv_shelf", "line_id", "shelve_name", $srcCageID);
    } else if ($destCageID <= 0 && $trnsType == "GL/Vault Account Transfers") {
        $invAssetAcntID = -1;
        $sbmtdShelfID = getLatestCage($prsnid, $destCageID, $sbmtdStoreID, $invAssetAcntID);
        $destVltID = $sbmtdStoreID;
        $destCageID = $sbmtdShelfID;
        $destVltNm = getGnrlRecNm("inv.inv_itm_subinventories", "subinv_id", "subinv_name", $destVltID);
        $destCageNm = getGnrlRecNm("inv.inv_shelf", "line_id", "shelve_name", $destCageID);
    }
    $routingID = getVMSMxRoutingID($sbmtdVmsTrnsHdrID);
    $reportTitle = $trnsType;
    $reportName = "VMS Transaction Voucher";
    if ($trnsType == $trnsTypes[4] || $trnsType == $trnsTypes[5] || $trnsType == $trnsTypes[6]) {
        $reportName = "VMS DEP/WITHDRAW Voucher";
    }
    $rptID = getRptID($reportName);
    $prmID1 = getParamIDUseSQLRep("{:trns_id}", $rptID);
    $prmID2 = getParamIDUseSQLRep("{:documentTitle}", $rptID);
    $trnsID = $sbmtdVmsTrnsHdrID;
    $paramRepsNVals = $prmID1 . "~" . $trnsID . "|" . $prmID2 . "~" . $reportTitle . "|-130~" . $reportTitle . "|-190~PDF";
    $paramStr = urlencode($paramRepsNVals);

    $trayCnvFctr = 0;
    $bndlCnvFctr = 0;
    $curRslt = get_All_Crncies($crncyID, "Note");
    while ($rowCur = loc_db_fetch_array($curRslt)) {
        $itmID = (int) $rowCur[0];
        $trayCnvFctr = getUomCnvrsnFctr($itmID, "tray");
        $bndlCnvFctr = getUomCnvrsnFctr($itmID, "bundle");
        break;
    }
?>
    <form class="form-horizontal" id="oneVmsTrnsEDTForm">
        <div class="row" style="margin: 0px 0px 10px 0px !important;">
            <div class="col-md-6" style="padding:0px 15px 0px 0px !important;">
                <div class="" style="padding:0px 0px 0px 0px;float:left !important;">
                    <button type="button" class="btn btn-default btn-sm" style="" id="myVmsTrnsStatusBtn"><span style="font-weight:bold;">Status: </span><span style="color:<?php echo $rqstatusColor; ?>;font-weight: bold;"><?php echo $rqStatus; ?></span></button>
                    <button type="button" class="btn btn-default" style="" onclick="getSilentRptsRnSts(<?php echo $rptID; ?>, -1, '<?php echo $paramStr; ?>');" style="width:100% !important;">
                        <img src="cmn_images/pdf.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">
                        Print Transaction
                    </button>
                </div>
            </div>
            <div class="col-md-6" style="padding:0px 0px 0px 0px !important;">
                <div class="" style="padding:0px 0px 0px 0px;float:right !important;">
                    <?php
                    if ($rqStatus != "Authorized") {
                    ?>
                        <button type="button" class="btn btn-default btn-sm" style="" onclick="checkWkfRqstStatus(<?php echo $routingID; ?>, 'VMS Trns. Authorization Progress History');"><img src="cmn_images/workflow.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Progress&nbsp;</button>
                    <?php
                    } else if ($rqStatus == "Authorized") {
                    ?>
                        <button type="button" class="btn btn-default btn-sm" style="" onclick="checkWkfRqstStatus(<?php echo $routingID; ?>, 'VMS Trns. Authorization Progress History');"><img src="cmn_images/workflow.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;" data-toggle="tooltip" title="Authorization Progress History">Progress&nbsp;</button>
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
                        <div class="col-md-4">
                            <label style="margin-bottom:0px !important;">Transaction No.:</label>
                        </div>
                        <div class="col-md-8">
                            <input type="text" class="form-control" aria-label="..." id="vmsTrnsNum" name="vmsTrnsNum" value="<?php echo $gnrtdTrnsNo; ?>" readonly="true">
                            <input class="form-control" type="hidden" id="vmsTrnsHdrID" value="<?php echo $sbmtdVmsTrnsHdrID; ?>" />
                            <input class="form-control" type="hidden" id="vmsVoidedTrnsHdrID" value="<?php echo $voidedTrnsHdrID; ?>" />
                            <input class="form-control" type="hidden" id="newVMSHdrID" value="-1" />
                            <input type="hidden" id="gnrlVmsOrgID" value="<?php echo $orgID; ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-4">
                            <label style="margin-bottom:0px !important;">Transaction Date:</label>
                        </div>
                        <div class="col-md-8">
                            <input type="text" class="form-control" aria-label="..." id="vmsTrnsDate" name="vmsTrnsDate" value="<?php echo $gnrtdTrnsDate; ?>" readonly="true">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-4">
                            <label style="margin-bottom:0px !important;">Transaction Type:</label>
                        </div>
                        <div class="col-md-8">
                            <?php if ($trnsType != "") { ?>
                                <input type="text" class="form-control" aria-label="..." id="vmsTrnsType" name="vmsTrnsType" value="<?php echo $trnsType; ?>" readonly="true">
                            <?php } else { ?>
                                <div class="input-group">
                                    <input type="text" class="form-control" aria-label="..." id="vmsTrnsType" name="vmsTrnsType" value="<?php echo $trnsType; ?>" <?php echo $mkReadOnly; ?>>
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Customers and Suppliers', 'gnrlVmsOrgID', '', '', 'radio', true, '', '', 'vmsTrnsType', 'clear', 1, '');">
                                        <span class="glyphicon glyphicon-th-list"></span>
                                    </label>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-4">
                            <label style="margin-bottom:0px !important;margin-top:5px !important;">Location:</label>
                        </div>
                        <div class="col-md-8">
                            <input type="text" class="form-control" aria-label="..." id="vmsBrnchLoc" name="vmsBrnchLoc" value="<?php echo $brnchLoc; ?>" readonly="true">
                            <input type="hidden" id="vmsBrnchLocID" value="<?php echo $brnchLocID; ?>">
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <?php
                    $descRowHght = 9;
                    $fldLbl = "Client";
                    $slipNoLbl = "Waybill No.:";
                    $slipNoLblRqrd = "rqrdFld";
                    if ($trnsType == "Deposits") {
                        $slipNoLbl = "Deposit Slip No.:";
                    } else if ($trnsType == "Withdrawals") {
                        $slipNoLbl = "Cheque No.:";
                    } else if ($trnsType == "GL/Vault Account Transfers") {
                        $slipNoLbl = "Cheque No.:";
                        $slipNoLblRqrd = "";
                    } else if ($trnsType == "Vault/GL Account Transfers") {
                        $slipNoLbl = "Voucher/Slip No.:";
                        $slipNoLblRqrd = "";
                    }
                    if (strpos($trnsType, "Purchase") === TRUE && strpos($trnsType, "Importation") === TRUE) {
                        $fldLbl = "Vendor";
                    }
                    if (strpos($trnsType, "Transfer") === FALSE && strpos($trnsType, "Exam") === FALSE && strpos($trnsType, "Transit") === FALSE && strpos($trnsType, "Adjustment") === FALSE) {
                        $descRowHght = 7;
                    ?>
                        <div class="form-group form-group-sm">
                            <label for="vmsCstmrNm" class="control-label col-md-4"><?php echo $fldLbl; ?>:</label>
                            <div class="col-md-8">
                                <div class="input-group">
                                    <input type="text" class="form-control" aria-label="..." id="vmsCstmrNm" name="vmsCstmrNm" value="<?php echo $cstmrNm; ?>" readonly="true">
                                    <input type="hidden" id="vmsCstmrID" value="<?php echo $cstmrID; ?>">
                                    <input type="hidden" id="vmsCstmrClsfctn" value="<?php echo $vmsCstmrClsfctn; ?>">
                                    <input class="form-control" type="hidden" id="vmsCstmrSiteID" value="<?php echo $cstmrSiteID; ?>">
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getCstmrSpplrForm(-1, 'Create/Edit <?php echo $fldLbl; ?>');" data-toggle="tooltip" title="Create New <?php echo $fldLbl; ?>">
                                        <span class="glyphicon glyphicon-plus"></span>
                                    </label>
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Some Customers and Suppliers', 'gnrlVmsOrgID', 'vmsCstmrClsfctn', '', 'radio', true, '', 'vmsCstmrID', 'vmsCstmrNm', 'clear', 1, '');" data-toggle="tooltip" title="Existing Client/Vendor">
                                        <span class="glyphicon glyphicon-th-list"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                    <div class="form-group">
                        <div class="col-md-4">
                            <label style="margin-bottom:0px !important;">Remark / Narration:</label>
                        </div>
                        <div class="col-md-8">
                            <div class="input-group">
                                <textarea class="form-control input-group-addon" rows="<?php echo $descRowHght; ?>" id="vmsTrnsDesc" name="vmsTrnsDesc" <?php echo $mkRmrkReadOnly; ?> style="text-align:left !important;"><?php echo $vmsTrnsDesc; ?></textarea>
                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'VMS Transaction Classifications', '', '', '', 'radio', true, '', '', 'vmsTrnsDesc', 'clear', 1, '');">
                                    <span class="glyphicon glyphicon-th-list"></span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <div class="col-md-4">
                            <label style="margin-bottom:0px !important;"><?php echo $slipNoLbl; ?></label>
                        </div>
                        <div class="col-md-8">
                            <input type="text" class="form-control <?php echo $slipNoLblRqrd; ?>" aria-label="..." id="vmsChequeNo" name="vmsChequeNo" value="<?php echo $vmsChequeNo; ?>" <?php echo $mkReadOnly; ?>>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-4">
                            <label style="margin-bottom:0px !important;">Representative's Info:</label>
                        </div>
                        <div class="col-md-8">
                            <input type="text" class="form-control" aria-label="..." id="vmsTrnsPrsn" name="vmsTrnsPrsn" value="<?php echo $vmsTrnsPrsn; ?>" style="width:100% !important;" <?php echo $mkReadOnly; ?>>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-4">
                            <label style="margin-bottom:0px !important;">Supervising Staff:</label>
                        </div>
                        <div class="col-md-8">
                            <div class="input-group" style="width:100% !important;">
                                <input type="text" class="form-control" aria-label="..." id="vmsOffctStaff" name="vmsOffctStaff" value="<?php echo $vmsOffctStaff; ?>" readonly="true" style="width:100% !important;">
                                <input type="hidden" class="form-control" aria-label="..." id="vmsOffctStaffLocID" value="<?php echo $vmsOffctStaffLocID; ?>" style="width:100% !important;">
                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Active Persons', 'gnrlVmsOrgID', '', '', 'radio', true, '', 'vmsOffctStaffLocID', 'vmsOffctStaff', 'clear', 1, '');">
                                    <span class="glyphicon glyphicon-th-list"></span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-4">
                            <label style="margin-bottom:0px !important;color:blue;margin-top:5px;">Total Amount:</label>
                        </div>
                        <div class="col-md-8">
                            <div class="input-group">
                                <input type="text" class="form-control" aria-label="..." id="vmsTrnsCrncyNm" name="vmsTrnsCrncyNm" value="<?php echo $crncyIDNm; ?>" readonly="true" style="width:40px;max-width:40px;">
                                <label class="btn btn-primary btn-file input-group-addon" onclick="">
                                    <span class="glyphicon glyphicon-th-list"></span>
                                </label>
                                <input class="form-control vmsTtlAmt" type="text" id="ttlVMSDocAmntVal" value="<?php
                                                                                                                echo number_format($vmsTrnsTtlAmnt, 2);
                                                                                                                ?>" style="font-weight:bold;" onchange="fmtAsNumber('ttlVMSDocAmntVal');" <?php echo $mkReadOnly; ?> />
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
                        <?php if ($trnsType == $trnsTypes[12] || $trnsType == $trnsTypes[13]) { ?>
                            <li class="active"><a data-toggle="tabajxdenoms" data-rhodata="" href="#cashItmsExpns" id="cashItmsExpnstab" style="padding: 3px 10px !important;">Breakdown of GL Transactions</a></li>
                            <li><a data-toggle="tabajxdenoms" data-rhodata="" href="#cashItmsNotes" id="cashItmsNotestab" style="padding: 3px 10px !important;">Notes</a></li>
                            <li><a data-toggle="tabajxdenoms" data-rhodata="" href="#cashItmsCoins" id="cashItmsCoinstab" style="padding: 3px 10px !important;">Coins</a></li>
                        <?php } else { ?>
                            <li class="active"><a data-toggle="tabajxdenoms" data-rhodata="" href="#cashItmsNotes" id="cashItmsNotestab" style="padding: 3px 10px !important;">Notes</a></li>
                            <li><a data-toggle="tabajxdenoms" data-rhodata="" href="#cashItmsCoins" id="cashItmsCoinstab" style="padding: 3px 10px !important;">Coins</a></li>
                        <?php } ?>
                    </ul>
                    <div class="custDiv" style="padding:0px !important;min-height: 40px !important;">
                        <div class="tab-content" style="padding:5px !important;padding-top:7px !important;">
                            <div class="row">
                                <div class="col-md-12">
                                    <?php
                                    $dsplyNwLine = "";
                                    $dsplyPymntInfo = "display:none;";
                                    $dsplyPymntCls1 = "col-md-12";
                                    $dsplyPymntCls2 = "";
                                    $dsplyPymntCls3 = "col-md-6";
                                    $dsplyPymntCls4 = "col-md-3";
                                    $srcVltCageDsply = "";
                                    $dstVltCageDsply = "";
                                    $srcCageColDsply = "display:none;";
                                    $dstCageColDsply = "";
                                    $dsplySrcItemState = "display:none;";
                                    $dsplyDstItemState = "display:none;";
                                    $dsplySrcBalance = "";
                                    $dsplyDstBalance = "";
                                    $isSrcDstCagesRqrd = "rqrdFld";
                                    $dsplyLnRemark = "";
                                    $dsplyTrays = "";
                                    $dsplyBundles = "";
                                    if ($trnsType == $trnsTypes[2] || $trnsType == $trnsTypes[3]) {
                                        $srcCageColDsply = "";
                                        $dstCageColDsply = "";
                                        $dsplyDstBalance = "";
                                        $dsplySrcBalance = "";
                                    } else if ($trnsType == $trnsTypes[8] || $trnsType == $trnsTypes[9] || $trnsType == $trnsTypes[6]) {
                                        $dsplyNwLine = "display:none;";
                                        $dsplyPymntInfo = "";
                                        $dsplyPymntCls1 = "col-md-6";
                                        $dsplyPymntCls2 = "col-md-6";
                                        $dsplyPymntCls3 = "col-md-8";
                                        $dsplyPymntCls4 = "col-md-4";
                                        $srcCageColDsply = "display:none;";
                                        $dstCageColDsply = "display:none;";
                                        if ($trnsType == $trnsTypes[8]) {
                                            $srcVltCageDsply = "";
                                            $dsplySrcBalance = "";
                                            $dstVltCageDsply = "display:none;";
                                            $dsplyDstBalance = "display:none;";
                                        } else if ($trnsType == $trnsTypes[9]) {
                                            $dsplySrcBalance = "display:none;";
                                            $srcVltCageDsply = "display:none;";
                                            $dstVltCageDsply = "";
                                            $dsplyDstBalance = "";
                                        } else if ($trnsType == $trnsTypes[6]) {
                                            $srcVltCageDsply = "display:none;";
                                            $dstVltCageDsply = "";
                                            $srcCageColDsply = "display:none;";
                                            $dstCageColDsply = "";
                                            $dsplySrcBalance = "display:none;";
                                            $dsplyDstBalance = "";
                                        }
                                    } else if ($trnsType == $trnsTypes[10]) {
                                        $srcVltCageDsply = "display:none;";
                                        $dstVltCageDsply = "";
                                        $srcCageColDsply = "display:none;";
                                        $dstCageColDsply = "";
                                        $dsplySrcBalance = "display:none;";
                                        $dsplyDstBalance = "";
                                        /* $srcVltCageDsply = "";
                                          $dstVltCageDsply = "";
                                          $srcCageColDsply = "";
                                          $dstCageColDsply = ""; */
                                        $isSrcDstCagesRqrd = "rqrdFld";
                                        $dsplySrcItemState = "";
                                        $dsplyDstItemState = "";
                                        $dsplyLnRemark = "display:none;";
                                    } else if ($trnsType == $trnsTypes[11] || $trnsType == $trnsTypes[0] || $trnsType == $trnsTypes[1] || $trnsType == $trnsTypes[3]) {
                                        $srcVltCageDsply = "";
                                        $dstVltCageDsply = "";
                                        $srcCageColDsply = "";
                                        $dstCageColDsply = "";
                                        $isSrcDstCagesRqrd = "";
                                    } else if ($trnsType == $trnsTypes[4]) {
                                        $srcVltCageDsply = "display:none;";
                                        $dstVltCageDsply = "";
                                        $srcCageColDsply = "display:none;";
                                        $dstCageColDsply = "display:none;";
                                        $dsplySrcBalance = "display:none;";
                                        $dsplyDstBalance = "";
                                    } else if ($trnsType == $trnsTypes[5]) {
                                        $srcVltCageDsply = "";
                                        $dstVltCageDsply = "display:none;";
                                        $srcCageColDsply = "display:none;";
                                        $dstCageColDsply = "display:none;";
                                        $dsplySrcBalance = "";
                                        $dsplyDstBalance = "display:none;";
                                    } else if ($trnsType == $trnsTypes[7] || $trnsType == $trnsTypes[12]) {
                                        $srcVltCageDsply = "";
                                        $dstVltCageDsply = "display:none;";
                                        $srcCageColDsply = "";
                                        $dstCageColDsply = "display:none;";
                                        $dsplySrcBalance = "";
                                        $dsplyDstBalance = "display:none;";
                                        if ($trnsType == $trnsTypes[12]) {
                                            $dsplyTrays = "display:none;";
                                            $dsplyBundles = "display:none;";
                                        }
                                    } else if ($trnsType == $trnsTypes[13]) {
                                        $srcVltCageDsply = "display:none;";
                                        $dstVltCageDsply = "";
                                        $srcCageColDsply = "display:none;";
                                        $dstCageColDsply = "";
                                        $dsplySrcBalance = "display:none;";
                                        $dsplyDstBalance = "";
                                        $dsplyTrays = "display:none;";
                                        $dsplyBundles = "display:none;";
                                    }
                                    $cashExpnsCls = "hideNotice";
                                    $cashItmsNotesCls = "active";
                                    $ttlPrce = 0;
                                    if ($trnsType == $trnsTypes[12] || $trnsType == $trnsTypes[13]) {
                                        $cashItmsNotesCls = "hideNotice";
                                        $cashExpnsCls = "active";
                                    }
                                    ?>
                                    <div class="<?php echo $dsplyPymntCls3; ?>" style="padding:0px 0px 0px 0px !important;">
                                        <div class="<?php echo $dsplyPymntCls1; ?>" style="padding:0px 0px 0px 0px !important;float:left;">
                                            <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="getOneVmsDocsForm(<?php echo $sbmtdVmsTrnsHdrID; ?>, '<?php echo $trnsType; ?>', 24);" data-toggle="tooltip" data-placement="bottom" title="Attached Documents">
                                                <img src="cmn_images/adjunto.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
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
                                        <div class="<?php echo $dsplyPymntCls2; ?>" style="padding:0px 0px 0px 0px !important;<?php echo $dsplyPymntInfo; ?>">
                                            <div class="col-md-9" style="">
                                                <div class="form-group">
                                                    <div class="input-group" style="height:30px;min-height:30px;margin-bottom: 5px;">
                                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Currencies', '', '', '', 'radio', true, '<?php echo $vmsPymtCrncyNm; ?>', 'vmsPymtCrncyNm', '', 'clear', 0, '');">
                                                            <span style="font-weight:bold;color:black;">Payment: </span>
                                                        </label>
                                                        <input type="text" class="form-control input-group-addon" aria-label="..." id="vmsPymtCrncyNm" name="vmsPymtCrncyNm" value="<?php echo $vmsPymtCrncyNm; ?>" readonly="true" style="width:40px;max-width:40px;">
                                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Currencies', '', '', '', 'radio', true, '<?php echo $vmsPymtCrncyNm; ?>', 'vmsPymtCrncyNm', '', 'clear', 0, '');">
                                                            <span class="glyphicon glyphicon-th-list"></span>
                                                        </label>
                                                        <input type="text" class="form-control input-group-addon" aria-label="..." id="myPymntValsTtlBtn" name="myPymntValsTtlBtn" value="<?php
                                                                                                                                                                                            echo number_format($vmsPymtTtlAmnt, 2);
                                                                                                                                                                                            ?>" readonly="true" style="width:100%;color:red;font-weight: bold;text-align: left;">
                                                        <input type="hidden" id="myPymntValsTtlVal" value="<?php echo $vmsPymtTtlAmnt; ?>">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3" style="padding:0px 0px 0px 0px !important;">
                                                <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="getVMSTrnsPymtForm('<?php echo $trnsType; ?>', 32);" data-toggle="tooltip" data-placement="bottom" title="Payment Form">
                                                    <img src="cmn_images/payment_256.png" style="height:20px; width:auto; position: relative; vertical-align: middle;"> Process
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="<?php echo $dsplyPymntCls4; ?>">
                                        <div class="form-group form-group-sm" style="<?php echo $srcVltCageDsply; ?>">
                                            <div class="col-md-2" style="padding:0px 0px 0px 0px !important;">
                                                <label style="margin-bottom:0px !important;padding:2px 0px 0px 5px !important;">From:&nbsp;</label>
                                            </div>
                                            <div class="col-md-5" style="padding:0px 0px 0px 0px !important;">
                                                <div class="input-group">
                                                    <input type="text" class="form-control <?php echo $isSrcDstCagesRqrd; ?>" aria-label="..." id="vmsDfltSrcVlt" name="vmsDfltSrcVlt" value="<?php echo $srcVltNm; ?>" readonly="true">
                                                    <input type="hidden" id="vmsDfltSrcVltID" value="<?php echo $srcVltID; ?>">
                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'VMS Vaults', 'gnrlVmsOrgID', '', '', 'radio', true, '<?php echo $srcVltID; ?>', 'vmsDfltSrcVltID', 'vmsDfltSrcVlt', 'clear', 1, '');">
                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-5" style="padding:0px 0px 0px 0px !important;">
                                                <div class="input-group">
                                                    <input type="text" class="form-control <?php echo $isSrcDstCagesRqrd; ?>" aria-label="..." id="vmsDfltSrcCage" name="vmsDfltSrcCage" value="<?php echo $srcCageNm; ?>" readonly="true">
                                                    <input type="hidden" id="vmsDfltSrcCageID" value="<?php echo $srcCageID; ?>">
                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="">
                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                    </label>
                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="">
                                                        <span class="glyphicon glyphicon-info-sign"></span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="<?php echo $dsplyPymntCls4; ?>">
                                        <div class="form-group form-group-sm" style="<?php echo $dstVltCageDsply; ?>">
                                            <div class="col-md-2" style="padding:0px 0px 0px 0px !important;">
                                                <label style="margin-bottom:0px !important;padding:2px 0px 0px 5px !important;">To:&nbsp;</label>
                                            </div>
                                            <div class="col-md-5" style="padding:0px 0px 0px 0px !important;">
                                                <div class="input-group">
                                                    <input type="text" class="form-control" aria-label="..." id="vmsDfltDestVlt" name="vmsDfltDestVlt" value="<?php echo $destVltNm; ?>" readonly="true">
                                                    <input type="hidden" id="vmsDfltDestVltID" value="<?php echo $destVltID; ?>">
                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="">
                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-5" style="padding:0px 0px 0px 0px !important;">
                                                <div class="input-group">
                                                    <input type="text" class="form-control" aria-label="..." id="vmsDfltDestCage" name="vmsDfltDestCage" value="<?php echo $destCageNm; ?>" readonly="true">
                                                    <input type="hidden" id="vmsDfltDestCageID" value="<?php echo $destCageID; ?>">
                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="">
                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                    </label>
                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="">
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
                            <div id="cashItmsExpns" class="tab-pane fadein <?php echo $cashExpnsCls; ?>" style="border:none !important;padding:0px !important;">
                                <div class="row">
                                    <div class="col-md-12">
                                        <table class="table table-striped table-bordered table-responsive" id="oneVmsExpnsTrnsLnsTable" cellspacing="0" width="100%" style="width:100%;min-width: 900px !important;">
                                            <thead>
                                                <tr>
                                                    <th>No.</th>
                                                    <th style="">Narration/Remark</th>
                                                    <th>CUR.</th>
                                                    <th>Entered Amount</th>
                                                    <th style="min-width:300px;width:300px;">GL Transaction Account</th>
                                                    <th style="">Payment Currency Exchange Rate</th>
                                                    <th>&nbsp;</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                if ($trnsType == $trnsTypes[12] || $trnsType == $trnsTypes[13]) {
                                                    $cntr = 0;
                                                    $resultRw = get_One_PtCshExpnsLines($sbmtdVmsTrnsHdrID);
                                                    $maxNoRows = loc_db_num_rows($resultRw);
                                                    $excludedItems = "";
                                                    while ($cntr < $maxNoRows) {
                                                        $ptycshLineID = -1;
                                                        $ptycshLineDesc = "";
                                                        $entrdCurID = -1;
                                                        $entrdAmnt = 0.00;
                                                        $entrdCurNm = "";
                                                        $expnsAcntID = -1;
                                                        $expnsAcntNm = -1;
                                                        $acntCrncyRate = 0;
                                                        if ($rowRw = loc_db_fetch_array($resultRw)) {
                                                            $ptycshLineID = (float) $rowRw[0];
                                                            $ptycshLineDesc = $rowRw[2];
                                                            $entrdCurID = (int) $rowRw[11];
                                                            $entrdAmnt = (float) $rowRw[3];
                                                            $entrdCurNm = $rowRw[12];
                                                            $expnsAcntID = $rowRw[7];
                                                            $expnsAcntNm = $rowRw[22];
                                                            $acntCrncyRate = (float) $rowRw[18];
                                                            $ttlPrce += $entrdAmnt * $acntCrncyRate;
                                                        }
                                                        $cntr += 1;
                                                ?>
                                                        <tr id="oneVmsExpnsTrnsRow_<?php echo $cntr; ?>">
                                                            <td class="lovtd"><span><?php echo ($cntr); ?></span></td>
                                                            <td class="lovtd" style="">
                                                                <input type="text" class="form-control vmsExpDesc" aria-label="..." id="oneVmsExpnsTrnsRow<?php echo $cntr; ?>_LineDesc" name="oneVmsExpnsTrnsRow<?php echo $cntr; ?>_LineDesc" value="<?php echo $ptycshLineDesc; ?>" style="width:100% !important;" <?php echo $mkReadOnly; ?> onkeypress="gnrlFldKeyPress(event, 'oneVmsExpnsTrnsRow<?php echo $cntr; ?>_LineDesc', 'oneVmsExpnsTrnsLnsTable', 'vmsExpDesc');">
                                                            </td>
                                                            <td class="lovtd">
                                                                <div class="" style="width:100% !important;">
                                                                    <input type="hidden" class="form-control" aria-label="..." id="oneVmsExpnsTrnsRow<?php echo $cntr; ?>_TrnsCurNm" name="oneVmsExpnsTrnsRow<?php echo $cntr; ?>_TrnsCurNm" value="<?php echo $entrdCurNm; ?>" readonly="true" style="width:100% !important;">
                                                                    <label class="btn btn-primary btn-file" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Currencies', '', '', '', 'radio', true, '', 'oneVmsExpnsTrnsRow<?php echo $cntr; ?>_TrnsCurNm', '', 'clear', 1, '', function () {

                                                                                $('#oneVmsExpnsTrnsRow<?php echo $cntr; ?>_TrnsCurNm1').html($('#oneVmsExpnsTrnsRow<?php echo $cntr; ?>_TrnsCurNm').val());
                                                                            });">
                                                                        <span class="" id="oneVmsExpnsTrnsRow<?php echo $cntr; ?>_TrnsCurNm1"><?php echo $entrdCurNm; ?></span>
                                                                    </label>
                                                                </div>
                                                            </td>
                                                            <td class="lovtd">
                                                                <input type="text" class="form-control vmsExpTtl" aria-label="..." id="oneVmsExpnsTrnsRow<?php echo $cntr; ?>_TtlVal" name="oneVmsExpnsTrnsRow<?php echo $cntr; ?>_TtlVal" value="<?php
                                                                                                                                                                                                                                                    echo number_format($entrdAmnt, 2);
                                                                                                                                                                                                                                                    ?>" onkeypress="gnrlFldKeyPress(event, 'oneVmsExpnsTrnsRow<?php echo $cntr; ?>_TtlVal', 'oneVmsExpnsTrnsLnsTable', 'vmsExpTtl');" style="width:100% !important;text-align: right;" <?php echo $mkReadOnly; ?> onchange="calcAllExpnsTrnsTtl();">
                                                            </td>
                                                            <td class="lovtd">
                                                                <div class="input-group" style="width:100% !important;">
                                                                    <input type="text" class="form-control" aria-label="..." id="oneVmsExpnsTrnsRow<?php echo $cntr; ?>_AccountNm" name="oneVmsExpnsTrnsRow<?php echo $cntr; ?>_AccountNm" value="<?php echo $expnsAcntNm; ?>" readonly="true" style="width:100% !important;">
                                                                    <input type="hidden" class="form-control" aria-label="..." id="oneVmsExpnsTrnsRow<?php echo $cntr; ?>_AccountID" value="<?php echo $expnsAcntID; ?>" style="width:100% !important;">
                                                                    <input type="hidden" class="form-control" aria-label="..." id="oneVmsExpnsTrnsRow<?php echo $cntr; ?>_TrnsLnID" value="<?php echo $ptycshLineID; ?>" style="width:100% !important;">
                                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Asset and Expenditure Accounts', 'gnrlVmsOrgID', '', '', 'radio', true, '', 'oneVmsExpnsTrnsRow<?php echo $cntr; ?>_AccountID', 'oneVmsExpnsTrnsRow<?php echo $cntr; ?>_AccountNm', 'clear', 1, '', function () {

                                                                            });">
                                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                                    </label>
                                                                </div>
                                                            </td>
                                                            <td class="lovtd" style="">
                                                                <input type="text" class="form-control vmsExpRt" aria-label="..." id="oneVmsExpnsTrnsRow<?php echo $cntr; ?>_ExchgRate" name="oneVmsExpnsTrnsRow<?php echo $cntr; ?>_ExchgRate" value="<?php
                                                                                                                                                                                                                                                        echo number_format($acntCrncyRate, 15);
                                                                                                                                                                                                                                                        ?>" onkeypress="gnrlFldKeyPress(event, 'oneVmsExpnsTrnsRow<?php echo $cntr; ?>_ExchgRate', 'oneVmsExpnsTrnsLnsTable', 'vmsExpRt');" style="width:100% !important;text-align: right;" <?php echo $mkReadOnly; ?> onchange="calcAllExpnsTrnsTtl();">
                                                            </td>
                                                            <td class="lovtd">
                                                                <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delVmsExpnsTrnsLine('oneVmsExpnsTrnsRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete GL Trns. Line">
                                                                    <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                                </button>
                                                            </td>
                                                        </tr>
                                                <?php
                                                    }
                                                }
                                                ?>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th>&nbsp;</th>
                                                    <th>TOTALS:</th>
                                                    <th style=""><?php echo $crncyIDNm; ?></th>
                                                    <th style="text-align: right;">
                                                        <?php
                                                        echo "<span style=\"color:red;font-weight:bold;font-size:14px;\" id=\"myCptrdExpnsTtlBtn\">" . number_format($ttlPrce, 2, '.', ',') . "</span>";
                                                        ?>
                                                        <input type="hidden" id="myCptrdExpnsTtlVal" value="<?php echo $ttlPrce; ?>">
                                                    </th>
                                                    <th style="">&nbsp;</th>
                                                    <th style="">&nbsp;</th>
                                                    <th style="">&nbsp;</th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div id="cashItmsNotes" class="tab-pane fadein <?php echo $cashItmsNotesCls; ?>" style="border:none !important;padding:0px !important;">
                                <div class="row">
                                    <div class="col-md-12">
                                        <table class="table table-striped table-bordered table-responsive" id="oneVmsTrnsLnsTable" cellspacing="0" width="100%" style="width:100%;min-width: 900px !important;">
                                            <thead>
                                                <tr>
                                                    <!--<th>No.</th> -->
                                                    <th style="max-width:100px;width:100px;">Denomination</th>
                                                    <th style="<?php echo $srcCageColDsply; ?>max-width:70px;width:70px;">Src. Cage</th>
                                                    <th style="<?php echo $dstCageColDsply; ?>max-width:70px;width:70px;">Dest. Cage</th>
                                                    <th style="<?php echo $dsplyLnRemark; ?>">Narration/Remark</th>
                                                    <th style="text-align: center;max-width:70px;width:70px;<?php echo $dsplyTrays; ?>">Trays</th>
                                                    <th style="text-align: center;max-width:70px;width:70px;<?php echo $dsplyBundles; ?>">Bundles</th>
                                                    <!--<th style = "display:none;">Pieces</th> -->
                                                    <th>UOM</th>
                                                    <th>Total Value</th>
                                                    <th style="<?php echo $dsplyPymntInfo; ?>">Payment Currency Exchange Rate</th>
                                                    <th style="<?php echo $dsplySrcBalance; ?>">Source Cage Running Balance</th>
                                                    <th style="<?php echo $dsplyDstBalance; ?>">Destination Cage Running Balance</th>
                                                    <th style="<?php echo $dsplySrcItemState; ?>">Source Cage Money Type</th>
                                                    <th style="<?php echo $dsplyDstItemState; ?>">Destination Cage Money Type</th>
                                                    <th>&nbsp;
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $cntr = 0;
                                                $resultRw = get_One_VMSTrnsLines($sbmtdVmsTrnsHdrID, -1, "Note");
                                                $maxNoRows = loc_db_num_rows($resultRw);
                                                $excludedItems = "";
                                                while ($cntr < $maxNoRows) {
                                                    $itemID = -1;
                                                    $trnsLnID = -1;
                                                    $itemName = "";
                                                    $baseUOMID = -1;
                                                    $unitVal = 0.00;
                                                    $qty = 0.00;
                                                    $baseUOMNm = "";
                                                    $ttlRowVal = 0.00;
                                                    $srcItemState = $dfltSrcItemState;
                                                    $dstItemState = $dfltDstItemState;
                                                    $funcCrncyRate = 0;
                                                    $lineDesc = "";
                                                    $srcRunningBalance = 0;
                                                    $dstRunningBalance = 0;
                                                    $srcCageID1 = -1;
                                                    $destCageID1 = -1;
                                                    $srcCageNm1 = "";
                                                    $destCageNm1 = "";
                                                    if ($rowRw = loc_db_fetch_array($resultRw)) {
                                                        $trnsLnID = (float) $rowRw[0];
                                                        $itemID = (int) $rowRw[2];
                                                        $lineDesc = $rowRw[36];
                                                        $srcRunningBalance = (float) $rowRw[35];
                                                        $dstRunningBalance = (float) $rowRw[37];
                                                        $excludedItems .= $itemID . ", ";
                                                        $itemName = $rowRw[29];
                                                        $unitVal = (float) $rowRw[9];
                                                        $baseUOMNm = $rowRw[31];
                                                        $baseUOMID = (int) $rowRw[26];
                                                        $qty = (float) $rowRw[7];
                                                        $tray = (float) $rowRw[46];
                                                        $bundle = (float) $rowRw[47];
                                                        $ttlRowVal = $qty * $unitVal;
                                                        if ($trnsLnID > 0) {
                                                            $srcItemState = $rowRw[27];
                                                            $dstItemState = $rowRw[34];
                                                        } else {
                                                            $srcItemState = $dfltSrcItemState;
                                                            $dstItemState = $dfltDstItemState;
                                                        }
                                                        $funcCrncyRate = (float) $rowRw[21];
                                                        $srcCageID1 = (int) $rowRw[4];
                                                        $destCageID1 = (int) $rowRw[6];
                                                        $srcCageNm1 = $rowRw[39];
                                                        $destCageNm1 = $rowRw[41];
                                                    }
                                                    $cntr += 1;
                                                ?>
                                                    <tr id="oneVmsTrnsRow_<?php echo $cntr; ?>">
                                                        <!--<td class="lovtd"><span><?php echo ($cntr); ?></span></td>-->
                                                        <td class="lovtd">
                                                            <div class="input-group" style="width:100% !important;">
                                                                <input type="text" class="form-control" aria-label="..." id="oneVmsTrnsRow<?php echo $cntr; ?>_ItemNm" name="oneVmsTrnsRow<?php echo $cntr; ?>_ItemNm" value="<?php echo $itemName; ?>" readonly="true" style="width:100% !important;">
                                                                <input type="hidden" class="form-control" aria-label="..." id="oneVmsTrnsRow<?php echo $cntr; ?>_ItmID" value="<?php echo $itemID; ?>" style="width:100% !important;">
                                                                <input type="hidden" class="form-control" aria-label="..." id="oneVmsTrnsRow<?php echo $cntr; ?>_BaseUoMID" value="<?php echo $baseUOMID; ?>" style="width:100% !important;">
                                                                <input type="hidden" class="form-control" aria-label="..." id="oneVmsTrnsRow<?php echo $cntr; ?>_TrnsLnID" value="<?php echo $trnsLnID; ?>" style="width:100% !important;">
                                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Vault Item List', 'gnrlVmsOrgID', 'vmsTrnsCrncyNm', '', 'radio', true, '', 'oneVmsTrnsRow<?php echo $cntr; ?>_ItmID', 'oneVmsTrnsRow<?php echo $cntr; ?>_ItemNm', 'clear', 1, '', function () {
                                                                            afterVMSItemSlctn('oneVmsTrnsRow_<?php echo $cntr; ?>');
                                                                        });">
                                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                                </label>
                                                            </div>
                                                        </td>
                                                        <td class="lovtd" style="<?php echo $srcCageColDsply; ?>">
                                                            <div class="input-group" style="width:100% !important;">
                                                                <input type="text" class="form-control" aria-label="..." id="oneVmsTrnsRow<?php echo $cntr; ?>_SrcCageNm" name="oneVmsTrnsRow<?php echo $cntr; ?>_SrcCageNm" value="<?php echo $srcCageNm1; ?>" readonly="true" style="width:100% !important;">
                                                                <input type="hidden" class="form-control" aria-label="..." id="oneVmsTrnsRow<?php echo $cntr; ?>_SrcCageID" value="<?php echo $srcCageID1; ?>" style="width:100% !important;">
                                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All VMS Vault Cages', '', 'vmsTrnsCrncyNm', '', 'radio', true, '<?php echo $srcCageID1; ?>', 'oneVmsTrnsRow<?php echo $cntr; ?>_SrcCageID', 'oneVmsTrnsRow<?php echo $cntr; ?>_SrcCageNm', 'clear', 1, '', function () {
                                                                            afterVMSItemSlctn('oneVmsTrnsRow_<?php echo $cntr; ?>');
                                                                        });">
                                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                                </label>
                                                            </div>
                                                        </td>
                                                        <td class="lovtd" style="<?php echo $dstCageColDsply; ?>">
                                                            <div class="input-group" style="width:100% !important;">
                                                                <input type="text" class="form-control" aria-label="..." id="oneVmsTrnsRow<?php echo $cntr; ?>_DstCageNm" name="oneVmsTrnsRow<?php echo $cntr; ?>_DstCageNm" value="<?php echo $destCageNm1; ?>" readonly="true" style="width:100% !important;">
                                                                <input type="hidden" class="form-control" aria-label="..." id="oneVmsTrnsRow<?php echo $cntr; ?>_DstCageID" value="<?php echo $destCageID1; ?>" style="width:100% !important;">
                                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All VMS Vault Cages', '', 'vmsTrnsCrncyNm', '', 'radio', true, '<?php echo $destCageID1; ?>', 'oneVms
                                                                                    TrnsRow<?php echo $cntr; ?>_DstCageID', 'oneVmsTrnsRow<?php echo $cntr; ?>_DstCageNm', 'clear', 1, '', function () {
                                                                                    afterVMSItemSlctn('oneVmsTrnsRow_<?php echo $cntr; ?>');
                                                                        });">
                                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                                </label>
                                                            </div>
                                                        </td>
                                                        <td class="lovtd" style="<?php echo $dsplyLnRemark; ?>">
                                                            <input type="text" class="form-control" aria-label="..." id="oneVmsTrnsRow<?php echo $cntr; ?>_LineDesc" name="oneVmsTrnsRow<?php echo $cntr; ?>_LineDesc" value="<?php echo $lineDesc; ?>" style="width:100% !important;" <?php echo $mkReadOnly; ?>>
                                                        </td>
                                                        <td class="lovtd" style="<?php echo $dsplyTrays; ?>">
                                                            <input type="text" class="form-control vmsCbTray" aria-label="..." id="oneVmsTrnsRow<?php echo $cntr; ?>_Tray" name="oneVmsTrnsRow<?php echo $cntr; ?>_Tray" value="<?php
                                                                                                                                                                                                                                echo number_format($tray, 0);
                                                                                                                                                                                                                                ?>" onchange="calcCshBrkdwnTryBndlRowVal('oneVmsTrnsRow_<?php echo $cntr; ?>');" onkeypress="vmsCbTrayKeyPress(event, 'oneVmsTrnsRow_<?php echo $cntr; ?>');" style="width:100% !important;text-align: center;" <?php echo $mkReadOnly; ?>>
                                                        </td>
                                                        <td class="lovtd" style="<?php echo $dsplyBundles; ?>">
                                                            <input type="text" class="form-control vmsCbBndl" aria-label="..." id="oneVmsTrnsRow<?php echo $cntr; ?>_Bndl" name="oneVmsTrnsRow<?php echo $cntr; ?>_Bndl" value="<?php
                                                                                                                                                                                                                                echo number_format($bundle, 0);
                                                                                                                                                                                                                                ?>" onchange="calcCshBrkdwnTryBndlRowVal('oneVmsTrnsRow_<?php echo $cntr; ?>');" onkeypress="vmsCbBndlKeyPress(event, 'oneVmsTrnsRow_<?php echo $cntr; ?>');" style="width:100% !important;text-align: center;" <?php echo $mkReadOnly; ?>>
                                                        </td>
                                                        <td class="lovtd">
                                                            <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;font-weight:bold;" onclick="getOneUOMBrkdwnForm(-1, 22, 'oneVmsTrnsRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="View QTY Breakdown"><?php echo $baseUOMNm; ?></button>
                                                        </td>
                                                        <td class="lovtd">
                                                            <input type="hidden" class="form-control vmsCbQty" aria-label="..." id="oneVmsTrnsRow<?php echo $cntr; ?>_Qty" name="oneVmsTrnsRow<?php echo $cntr; ?>_Qty" value="<?php
                                                                                                                                                                                                                                echo number_format($qty, 0);
                                                                                                                                                                                                                                ?>" onchange="calcCshBrkdwnRowVal('oneVmsTrnsRow_<?php echo $cntr; ?>');" onkeypress="vmsTrnsFormKeyPress(event, 'oneVmsTrnsRow_<?php echo $cntr; ?>');" style="width:100% !important;text-align: right;" <?php echo $mkReadOnly; ?>>
                                                            <input type="hidden" class="form-control" aria-label="..." id="oneVmsTrnsRow<?php echo $cntr; ?>_UntVal" name="oneVmsTrnsRow<?php echo $cntr; ?>_UntVal" value="<?php echo $unitVal;
                                                                                                                                                                                                                            ?>" style="width:100% !important;" readonly="true">
                                                            <input type="text" class="form-control vmsCbTtl" aria-label="..." id="oneVmsTrnsRow<?php echo $cntr; ?>_TtlVal" name="oneVmsTrnsRow<?php echo $cntr; ?>_TtlVal" value="<?php
                                                                                                                                                                                                                                    echo number_format($ttlRowVal, 2);
                                                                                                                                                                                                                                    ?>" onchange="calcCshBrkdwnTtlVal('oneVmsTrnsRow_<?php echo $cntr; ?>');" onkeypress="vmsTrnsFormTtlFldKeyPress(event, 'oneVmsTrnsRow_<?php echo $cntr; ?>');" style="width:100% !important;text-align: right;" <?php echo $mkReadOnly; ?>>
                                                        </td>
                                                        <td class="lovtd" style="<?php echo $dsplyPymntInfo; ?>">
                                                            <input type="text" class="form-control vmsFncCrncy" aria-label="..." id="oneVmsTrnsRow<?php echo $cntr; ?>_PymntCrncyRate" name="oneVmsTrnsRow<?php echo $cntr; ?>_PymntCrncyRate" value="<?php
                                                                                                                                                                                                                                                        echo number_format($funcCrncyRate, 15);
                                                                                                                                                                                                                                                        ?>" onchange="calcPymtAmntTtl('oneVmsTrnsRow_<?php echo $cntr; ?>');" onkeypress="vmsTrnsFormFnCrncyKeyPress(event, 'oneVmsTrnsRow_<?php echo $cntr; ?>');" style="width:100% !important;text-align: right;" <?php echo $mkReadOnly; ?>>
                                                        </td>
                                                        <td class="lovtd" style="<?php echo $dsplySrcBalance; ?>">
                                                            <input type="text" class="form-control vmsCbRnngBal" aria-label="..." id="oneVmsTrnsRow<?php echo $cntr; ?>_SrcRnngBal" name="oneVmsTrnsRow<?php echo $cntr; ?>_SrcRnngBal" value="<?php
                                                                                                                                                                                                                                                if ($srcCageID1 > 0) {
                                                                                                                                                                                                                                                    echo number_format($srcRunningBalance, 2);
                                                                                                                                                                                                                                                } else {
                                                                                                                                                                                                                                                    echo "0.00";
                                                                                                                                                                                                                                                }
                                                                                                                                                                                                                                                ?>" style="width:100% !important;text-align: right;font-weight:bold;color:blue;" readonly="true">
                                                            <?php
                                                            if (strpos($capturedItemIDs, ";S" . $itemID . "_" . $srcCageID1 . ";") === FALSE) {
                                                                $capturedItemIDs .= ";S" . $itemID . "_" . $srcCageID1 . ";";
                                                                $extrInputFlds .= "<input type=\"hidden\" id=\"oneVmsTrnsFrmSrcCage_" . $itemID . "_" . $srcCageID1 . "\" name=\"oneVmsTrnsFrmSrcCage_" . $itemID . "_" . $srcCageID1 . "\" value=\"" . number_format($srcRunningBalance, 2) . "\" readonly=\"true\">";
                                                            }
                                                            if (strpos($capturedItemIDs, ";D" . $itemID . "_" . $destCageID1 . ";") === FALSE) {
                                                                $capturedItemIDs .= ";D" . $itemID . "_" . $destCageID1 . ";";
                                                                $extrInputFlds .= "<input type=\"hidden\" id=\"oneVmsTrnsFrmDstCage_" . $itemID . "_" . $destCageID1 . "\" name=\"oneVmsTrnsFrmDstCage_" . $itemID . "_" . $destCageID1 . "\" value=\"" . number_format($dstRunningBalance, 2) . "\" readonly=\"true\">";
                                                            }
                                                            ?>
                                                        </td>
                                                        <td class="lovtd" style="<?php echo $dsplyDstBalance; ?>">
                                                            <input type="text" class="form-control vmsCbRnngBal" aria-label="..." id="oneVmsTrnsRow<?php echo $cntr; ?>_DstRnngBal" name="oneVmsTrnsRow<?php echo $cntr; ?>_DstRnngBal" value="<?php
                                                                                                                                                                                                                                                if ($destCageID1 > 0) {
                                                                                                                                                                                                                                                    echo number_format($dstRunningBalance, 2);
                                                                                                                                                                                                                                                } else {
                                                                                                                                                                                                                                                    echo "0.00";
                                                                                                                                                                                                                                                }
                                                                                                                                                                                                                                                ?>" style="width:100% !important;text-align: right;font-weight:bold;color:blue;" readonly="true">
                                                        </td>
                                                        <td class="lovtd" style="<?php echo $dsplySrcItemState; ?>">
                                                            <div class="input-group" style="width:100% !important;">
                                                                <input type="text" class="form-control" aria-label="..." id="oneVmsTrnsRow<?php echo $cntr; ?>_SrcItemState" name="oneVmsTrnsRow<?php echo $cntr; ?>_SrcItemState" value="<?php echo $srcItemState; ?>" readonly="true" style="width:100% !important;">
                                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Vault Item States', '', '', '', 'radio', true, '', '', 'oneVmsTrnsRow<?php echo $cntr; ?>_SrcItemState', 'clear', 1, '', function () {
                                                                            afterVMSItemSlctn('oneVmsTrnsRow_<?php echo $cntr; ?>');
                                                                        });">
                                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                                </label>
                                                            </div>
                                                        </td>
                                                        <td class="lovtd" style="<?php echo $dsplyDstItemState; ?>">
                                                            <div class="input-group" style="width:100% !important;">
                                                                <input type="text" class="form-control" aria-label="..." id="oneVmsTrnsRow<?php echo $cntr; ?>_DstItemState" name="oneVmsTrnsRow<?php echo $cntr; ?>_DstItemState" value="<?php echo $dstItemState; ?>" readonly="true" style="width:100% !important;">
                                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Vault Item States', '', '', '', 'radio', true, '', '', 'oneVmsTrnsRow<?php echo $cntr; ?>_DstItemState', 'clear', 1, '', function () {
                                                                            afterVMSItemSlctn('oneVmsTrnsRow_<?php echo $cntr; ?>');
                                                                        });">
                                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                                </label>
                                                            </div>
                                                        </td>
                                                        <td class="lovtd">
                                                            <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delVmsTrnsLine('oneVmsTrnsRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Trns. Line">
                                                                <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                            </button>
                                                        </td>
                                                    </tr>
                                                    <?php
                                                }

                                                if (($voidedTrnsHdrID <= 0 || $orgnlVmsTrnsHdrID <= 0) && ($rqStatus == "Not Submitted" || $rqStatus == "Withdrawn" || $rqStatus == "Rejected")) {
                                                    $excludedItems = trim($excludedItems, ", ");
                                                    $resultRw = get_Fresh_VMSTrnsLines($sbmtdVmsTrnsHdrID, $crncyID, $destCageID, $excludedItems, $destVltID, $dfltDstItemState, $srcCageID, $srcVltID, $dfltSrcItemState);
                                                    $maxNoRows = loc_db_num_rows($resultRw);
                                                    $nwcntr = 0;
                                                    while ($nwcntr < $maxNoRows) {
                                                        $itemID = -1;
                                                        $trnsLnID = -1;
                                                        $itemName = "";
                                                        $baseUOMID = -1;
                                                        $unitVal = 0.00;
                                                        $qty = 0.00;
                                                        $tray = 0.00;
                                                        $bundle = 0.00;
                                                        $baseUOMNm = "";
                                                        $ttlRowVal = 0.00;
                                                        $srcItemState = $dfltSrcItemState;
                                                        $dstItemState = $dfltDstItemState;
                                                        $funcCrncyRate = 0;
                                                        $lineDesc = "";
                                                        $srcRunningBalance = 0;
                                                        $dstRunningBalance = 0;
                                                        if ($rowRw = loc_db_fetch_array($resultRw)) {
                                                            $trnsLnID = (float) $rowRw[0];
                                                            $itemID = (int) $rowRw[2];
                                                            $lineDesc = $rowRw[36];
                                                            if ($lineDesc == "") {
                                                                $lineDesc = $vmsTrnsDesc;
                                                            }
                                                            $srcRunningBalance = (float) $rowRw[35];
                                                            $dstRunningBalance = (float) $rowRw[37];
                                                            $itemName = $rowRw[29];
                                                            $unitVal = (float) $rowRw[9];
                                                            $baseUOMNm = $rowRw[31];
                                                            $baseUOMID = (int) $rowRw[26];
                                                            $qty = (float) $rowRw[7];
                                                            $ttlRowVal = $qty * $unitVal;
                                                            if ($trnsLnID > 0) {
                                                                $srcItemState = $rowRw[27];
                                                                $dstItemState = $rowRw[34];
                                                            } else {
                                                                $srcItemState = $dfltSrcItemState;
                                                                $dstItemState = $dfltDstItemState;
                                                            }
                                                            $funcCrncyRate = (float) $rowRw[21];
                                                        }
                                                        $cntr += 1;
                                                        $nwcntr += 1;
                                                    ?>
                                                        <tr id="oneVmsTrnsRow_<?php echo $cntr; ?>">
                                                            <!--<td class="lovtd"><span><?php echo ($cntr); ?></span></td>-->
                                                            <td class="lovtd">
                                                                <div class="input-group" style="width:100% !important;">
                                                                    <input type="text" class="form-control" aria-label="..." id="oneVmsTrnsRow<?php echo $cntr; ?>_ItemNm" name="oneVmsTrnsRow<?php echo $cntr; ?>_ItemNm" value="<?php echo $itemName; ?>" readonly="true" style="width:100% !important;">
                                                                    <input type="hidden" class="form-control" aria-label="..." id="oneVmsTrnsRow<?php echo $cntr; ?>_ItmID" value="<?php echo $itemID; ?>" style="width:100% !important;">
                                                                    <input type="hidden" class="form-control" aria-label="..." id="oneVmsTrnsRow<?php echo $cntr; ?>_BaseUoMID" value="<?php echo $baseUOMID; ?>" style="width:100% !important;">
                                                                    <input type="hidden" class="form-control" aria-label="..." id="oneVmsTrnsRow<?php echo $cntr; ?>_TrnsLnID" value="<?php echo $trnsLnID; ?>" style="width:100% !important;">
                                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Vault Item List', 'gnrlVmsOrgID', 'vmsTrnsCrncyNm', '', 'radio', true, '', 'oneVmsTrnsRow<?php echo $cntr; ?>_ItmID', 'oneVmsTrnsRow<?php echo $cntr; ?>_ItemNm', 'clear', 1, '', function () {
                                                                                afterVMSItemSlctn('oneVmsTrnsRow_<?php echo $cntr; ?>');
                                                                            });">
                                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                                    </label>
                                                                </div>
                                                            </td>
                                                            <td class="lovtd" style="<?php echo $srcCageColDsply; ?>">
                                                                <div class="input-group" style="width:100% !important;">
                                                                    <input type="text" class="form-control" aria-label="..." id="oneVmsTrnsRow<?php echo $cntr; ?>_SrcCageNm" name="oneVmsTrnsRow<?php echo $cntr; ?>_SrcCageNm" value="<?php echo $srcCageNm; ?>" readonly="true" style="width:100% !important;">
                                                                    <input type="hidden" class="form-control" aria-label="..." id="oneVmsTrnsRow<?php echo $cntr; ?>_SrcCageID" value="<?php echo $srcCageID; ?>" style="width:100% !important;">
                                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All VMS Vault Cages', '', 'vmsTrnsCrncyNm', '', 'radio', true, '<?php echo $srcCageID; ?>', 'oneVmsTrnsRow<?php echo $cntr; ?>_SrcCageID', 'oneVmsTrnsRow<?php echo $cntr; ?>_SrcCageNm', 'clear', 1, '', function () {
                                                                                afterVMSItemSlctn('oneVmsTrnsRow_<?php echo $cntr; ?>');
                                                                            });">
                                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                                    </label>
                                                                </div>
                                                            </td>
                                                            <td class="lovtd" style="<?php echo $dstCageColDsply; ?>">
                                                                <div class="input-group" style="width:100% !important;">
                                                                    <input type="text" class="form-control" aria-label="..." id="oneVmsTrnsRow<?php echo $cntr; ?>_DstCageNm" name="oneVmsTrnsRow<?php echo $cntr; ?>_DstCageNm" value="<?php echo $destCageNm; ?>" readonly="true" style="width:100% !important;">
                                                                    <input type="hidden" class="form-control" aria-label="..." id="oneVmsTrnsRow<?php echo $cntr; ?>_DstCageID" value="<?php echo $destCageID; ?>" style="width:100% !important;">
                                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All VMS Vault Cages', '', 'vmsTrnsCrncyNm', '', 'radio', true, '<?php echo $destCageID; ?>', 'oneVmsTrnsRow<?php echo $cntr; ?>_DstCageID', 'oneVmsTrnsRow<?php echo $cntr; ?>_DstCageNm', 'clear', 1, '', function () {
                                                                                afterVMSItemSlctn('oneVmsTrnsRow_<?php echo $cntr; ?>');
                                                                            });">
                                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                                    </label>
                                                                </div>
                                                            </td>
                                                            <td class="lovtd" style="<?php echo $dsplyLnRemark; ?>">
                                                                <input type="text" class="form-control" aria-label="..." id="oneVmsTrnsRow<?php echo $cntr; ?>_LineDesc" name="oneVmsTrnsRow<?php echo $cntr; ?>_LineDesc" value="<?php echo $lineDesc; ?>" style="width:100% !important;" <?php echo $mkReadOnly; ?>>
                                                            </td>
                                                            <td class="lovtd" style="<?php echo $dsplyTrays; ?>">
                                                                <input type="text" class="form-control vmsCbTray" aria-label="..." id="oneVmsTrnsRow<?php echo $cntr; ?>_Tray" name="oneVmsTrnsRow<?php echo $cntr; ?>_Tray" value="<?php
                                                                                                                                                                                                                                    echo number_format($tray, 0);
                                                                                                                                                                                                                                    ?>" onchange="calcCshBrkdwnTryBndlRowVal('oneVmsTrnsRow_<?php echo $cntr; ?>');" onkeypress="vmsCbTrayKeyPress(event, 'oneVmsTrnsRow_<?php echo $cntr; ?>');" style="width:100% !important;text-align: center;" <?php echo $mkReadOnly; ?>>
                                                            </td>
                                                            <td class="lovtd" style="<?php echo $dsplyBundles; ?>">
                                                                <input type="text" class="form-control vmsCbBndl" aria-label="..." id="oneVmsTrnsRow<?php echo $cntr; ?>_Bndl" name="oneVmsTrnsRow<?php echo $cntr; ?>_Bndl" value="<?php
                                                                                                                                                                                                                                    echo number_format($bundle, 0);
                                                                                                                                                                                                                                    ?>" onchange="calcCshBrkdwnTryBndlRowVal('oneVmsTrnsRow_<?php echo $cntr; ?>');" onkeypress="vmsCbBndlKeyPress(event, 'oneVmsTrnsRow_<?php echo $cntr; ?>');" style="width:100% !important;text-align: center;" <?php echo $mkReadOnly; ?>>
                                                            </td>
                                                            <td class="lovtd">
                                                                <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;font-weight:bold;" onclick="getOneUOMBrkdwnForm(-1, 22, 'oneVmsTrnsRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="View QTY Breakdown"><?php echo $baseUOMNm; ?></button>
                                                            </td>
                                                            <td class="lovtd">
                                                                <input type="hidden" class="form-control vmsCbQty" aria-label="..." id="oneVmsTrnsRow<?php echo $cntr; ?>_Qty" name="oneVmsTrnsRow<?php echo $cntr; ?>_Qty" value="<?php
                                                                                                                                                                                                                                    echo number_format($qty, 0);
                                                                                                                                                                                                                                    ?>" onchange="calcCshBrkdwnRowVal('oneVmsTrnsRow_<?php echo $cntr; ?>');" onkeypress="vmsTrnsFormKeyPress(event, 'oneVmsTrnsRow_<?php echo $cntr; ?>');" style="width:100% !important;text-align: right;" <?php echo $mkReadOnly; ?>>
                                                                <input type="hidden" class="form-control" aria-label="..." id="oneVmsTrnsRow<?php echo $cntr; ?>_UntVal" name="oneVmsTrnsRow<?php echo $cntr; ?>_UntVal" value="<?php echo $unitVal;
                                                                                                                                                                                                                                ?>" style="width:100% !important;" readonly="true">
                                                                <input type="text" class="form-control vmsCbTtl" aria-label="..." id="oneVmsTrnsRow<?php echo $cntr; ?>_TtlVal" name="oneVmsTrnsRow<?php echo $cntr; ?>_TtlVal" value="<?php
                                                                                                                                                                                                                                        echo number_format($ttlRowVal, 2);
                                                                                                                                                                                                                                        ?>" onchange="calcCshBrkdwnTtlVal('oneVmsTrnsRow_<?php echo $cntr; ?>');" onkeypress="vmsTrnsFormTtlFldKeyPress(event, 'oneVmsTrnsRow_<?php echo $cntr; ?>');" style="width:100% !important;text-align: right;" <?php echo $mkReadOnly; ?>>
                                                            </td>
                                                            <td class="lovtd" style="<?php echo $dsplyPymntInfo; ?>">
                                                                <input type="text" class="form-control vmsFncCrncy" aria-label="..." id="oneVmsTrnsRow<?php echo $cntr; ?>_PymntCrncyRate" name="oneVmsTrnsRow<?php echo $cntr; ?>_PymntCrncyRate" value="<?php
                                                                                                                                                                                                                                                            echo number_format($funcCrncyRate, 15);
                                                                                                                                                                                                                                                            ?>" onchange="calcPymtAmntTtl('oneVmsTrnsRow_<?php echo $cntr; ?>');" onkeypress="vmsTrnsFormFnCrncyKeyPress(event, 'oneVmsTrnsRow_<?php echo $cntr; ?>');" style="width:100% !important;text-align: right;" <?php echo $mkReadOnly; ?>>
                                                            </td>
                                                            <td class="lovtd" style="<?php echo $dsplySrcBalance; ?>">
                                                                <input type="text" class="form-control vmsCbRnngBal" aria-label="..." id="oneVmsTrnsRow<?php echo $cntr; ?>_SrcRnngBal" name="oneVmsTrnsRow<?php echo $cntr; ?>_SrcRnngBal" value="<?php
                                                                                                                                                                                                                                                    if ($srcCageID > 0) {
                                                                                                                                                                                                                                                        echo number_format($srcRunningBalance, 2);
                                                                                                                                                                                                                                                    } else {
                                                                                                                                                                                                                                                        echo "0.00";
                                                                                                                                                                                                                                                    }
                                                                                                                                                                                                                                                    ?>" style="width:100% !important;text-align: right;font-weight:bold;color:blue;" readonly="true">
                                                                <?php
                                                                if (strpos($capturedItemIDs, ";S" . $itemID . "_" . $srcCageID . ";") === FALSE) {
                                                                    $capturedItemIDs .= ";S" . $itemID . "_" . $srcCageID . ";";
                                                                    $extrInputFlds .= "<input type=\"hidden\" id=\"oneVmsTrnsFrmSrcCage_" . $itemID . "_" . $srcCageID . "\" name=\"oneVmsTrnsFrmSrcCage_" . $itemID . "_" . $srcCageID . "\" value=\"" . number_format($srcRunningBalance, 2) . "\" readonly=\"true\">";
                                                                }
                                                                if (strpos($capturedItemIDs, ";D" . $itemID . "_" . $destCageID . ";") === FALSE) {
                                                                    $capturedItemIDs .= ";D" . $itemID . "_" . $destCageID . ";";
                                                                    $extrInputFlds .= "<input type=\"hidden\" id=\"oneVmsTrnsFrmDstCage_" . $itemID . "_" . $destCageID . "\" name=\"oneVmsTrnsFrmDstCage_" . $itemID . "_" . $destCageID . "\" value=\"" . number_format($dstRunningBalance, 2) . "\" readonly=\"true\">";
                                                                }
                                                                ?>
                                                            </td>
                                                            <td class="lovtd" style="<?php echo $dsplyDstBalance; ?>">
                                                                <input type="text" class="form-control vmsCbRnngBal" aria-label="..." id="oneVmsTrnsRow<?php echo $cntr; ?>_DstRnngBal" name="oneVmsTrnsRow<?php echo $cntr; ?>_DstRnngBal" value="<?php
                                                                                                                                                                                                                                                    if ($destCageID > 0) {
                                                                                                                                                                                                                                                        echo number_format($dstRunningBalance, 2);
                                                                                                                                                                                                                                                    } else {
                                                                                                                                                                                                                                                        echo "0.00";
                                                                                                                                                                                                                                                    }
                                                                                                                                                                                                                                                    ?>" style="width:100% !important;text-align: right;font-weight:bold;color:blue;" readonly="true">
                                                            </td>
                                                            <td class="lovtd" style="<?php echo $dsplySrcItemState; ?>">
                                                                <div class="input-group" style="width:100% !important;">
                                                                    <input type="text" class="form-control" aria-label="..." id="oneVmsTrnsRow<?php echo $cntr; ?>_SrcItemState" name="oneVmsTrnsRow<?php echo $cntr; ?>_SrcItemState" value="<?php echo $srcItemState; ?>" readonly="true" style="width:100% !important;">
                                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Vault Item States', '', '', '', 'radio', true, '', '', 'oneVmsTrnsRow<?php echo $cntr; ?>_SrcItemState', 'clear', 1, '', function () {
                                                                                afterVMSItemSlctn('oneVmsTrnsRow_<?php echo $cntr; ?>');
                                                                            });">
                                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                                    </label>
                                                                </div>
                                                            </td>
                                                            <td class="lovtd" style="<?php echo $dsplyDstItemState; ?>">
                                                                <div class="input-group" style="width:100% !important;">
                                                                    <input type="text" class="form-control" aria-label="..." id="oneVmsTrnsRow<?php echo $cntr; ?>_DstItemState" name="oneVmsTrnsRow<?php echo $cntr; ?>_DstItemState" value="<?php echo $dstItemState; ?>" readonly="true" style="width:100% !important;">
                                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Vault Item States', '', '', '', 'radio', true, '', '', 'oneVmsTrnsRow<?php echo $cntr; ?>_DstItemState', 'clear', 1, '', function () {
                                                                                afterVMSItemSlctn('oneVmsTrnsRow_<?php echo $cntr; ?>');
                                                                            });">
                                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                                    </label>
                                                                </div>
                                                            </td>
                                                            <td class="lovtd">
                                                                <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delVmsTrnsLine('oneVmsTrnsRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Trns. Line">
                                                                    <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                                </button>
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
                            <div id="cashItmsCoins" class="tab-pane fade hideNotice" style="border:none !important;padding:0px !important;">
                                <div class="row">
                                    <div class="col-md-12">
                                        <table class="table table-striped table-bordered table-responsive" id="oneVmsTrnsCoinsLnsTable" cellspacing="0" width="100%" style="width:100%;min-width: 900px !important;">
                                            <thead>
                                                <tr>
                                                    <!--<th>No.</th>-->
                                                    <th style="max-width:100px;width:100px;">Denomination</th>
                                                    <th style="<?php echo $srcCageColDsply; ?>max-width:70px;width:70px;">Src. Cage</th>
                                                    <th style="<?php echo $dstCageColDsply; ?>max-width:70px;width:70px;">Dest. Cage</th>
                                                    <th style="<?php echo $dsplyLnRemark; ?>">Narration/Remark</th>
                                                    <th style="">Pieces</th>
                                                    <th>UOM</th>
                                                    <th>Total Value</th>
                                                    <th style="<?php echo $dsplyPymntInfo; ?>">Payment Currency Exchange Rate</th>
                                                    <th style="<?php echo $dsplySrcBalance; ?>">Source Cage Running Balance</th>
                                                    <th style="<?php echo $dsplyDstBalance; ?>">Destination Cage Running Balance</th>
                                                    <th style="<?php echo $dsplySrcItemState; ?>">Source Cage Money Type</th>
                                                    <th style="<?php echo $dsplyDstItemState; ?>">Destination Cage Money Type</th>
                                                    <th>&nbsp;</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $cntr = 0;
                                                $resultRw = get_One_VMSTrnsLines($sbmtdVmsTrnsHdrID, -1, "Coin");
                                                $maxNoRows = loc_db_num_rows($resultRw);
                                                $excludedItems = "";
                                                while ($cntr < $maxNoRows) {
                                                    $itemID = -1;
                                                    $trnsLnID = -1;
                                                    $itemName = "";
                                                    $baseUOMID = -1;
                                                    $unitVal = 0.00;
                                                    $qty = 0.00;
                                                    $baseUOMNm = "";
                                                    $ttlRowVal = 0.00;
                                                    $srcItemState = $dfltSrcItemState;
                                                    $dstItemState = $dfltDstItemState;
                                                    $funcCrncyRate = 0;
                                                    $lineDesc = "";
                                                    $srcRunningBalance = 0;
                                                    $dstRunningBalance = 0;
                                                    $srcCageID1 = -1;
                                                    $destCageID1 = -1;
                                                    $srcCageNm1 = "";
                                                    $destCageNm1 = "";
                                                    if ($rowRw = loc_db_fetch_array($resultRw)) {
                                                        $trnsLnID = (float) $rowRw[0];
                                                        $itemID = (int) $rowRw[2];
                                                        $lineDesc = $rowRw[36];
                                                        $srcRunningBalance = (float) $rowRw[35];
                                                        $dstRunningBalance = (float) $rowRw[37];
                                                        $excludedItems .= $itemID . ", ";
                                                        $itemName = $rowRw[29];
                                                        $unitVal = (float) $rowRw[9];
                                                        $baseUOMNm = $rowRw[31];
                                                        $baseUOMID = (int) $rowRw[26];
                                                        $qty = (float) $rowRw[7];
                                                        $tray = (float) $rowRw[46];
                                                        $bundle = (float) $rowRw[47];
                                                        $ttlRowVal = $qty * $unitVal;
                                                        if ($trnsLnID > 0) {
                                                            $srcItemState = $rowRw[27];
                                                            $dstItemState = $rowRw[34];
                                                        } else {
                                                            $srcItemState = $dfltSrcItemState;
                                                            $dstItemState = $dfltDstItemState;
                                                        }
                                                        $funcCrncyRate = (float) $rowRw[21];
                                                        $srcCageID1 = (int) $rowRw[4];
                                                        $destCageID1 = (int) $rowRw[6];
                                                        $srcCageNm1 = $rowRw[39];
                                                        $destCageNm1 = $rowRw[41];
                                                    }
                                                    $cntr += 1;
                                                ?>
                                                    <tr id="oneVmsTrnsCoinsRow_<?php echo $cntr; ?>">
                                                        <!--<td class="lovtd"><span><?php echo ($cntr); ?></span></td>-->
                                                        <td class="lovtd">
                                                            <div class="input-group" style="width:100% !important;">
                                                                <input type="text" class="form-control" aria-label="..." id="oneVmsTrnsCoinsRow<?php echo $cntr; ?>_ItemNm" name="oneVmsTrnsCoinsRow<?php echo $cntr; ?>_ItemNm" value="<?php echo $itemName; ?>" readonly="true" style="width:100% !important;">
                                                                <input type="hidden" class="form-control" aria-label="..." id="oneVmsTrnsCoinsRow<?php echo $cntr; ?>_ItmID" value="<?php echo $itemID; ?>" style="width:100% !important;">
                                                                <input type="hidden" class="form-control" aria-label="..." id="oneVmsTrnsCoinsRow<?php echo $cntr; ?>_BaseUoMID" value="<?php echo $baseUOMID; ?>" style="width:100% !important;">
                                                                <input type="hidden" class="form-control" aria-label="..." id="oneVmsTrnsCoinsRow<?php echo $cntr; ?>_TrnsLnID" value="<?php echo $trnsLnID; ?>" style="width:100% !important;">
                                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Vault Item List', 'gnrlVmsOrgID', 'vmsTrnsCrncyNm', '', 'radio', true, '', 'oneVmsTrnsCoinsRow<?php echo $cntr; ?>_ItmID', 'oneVmsTrnsCoinsRow<?php echo $cntr; ?>_ItemNm', 'clear', 1, '', function () {
                                                                            afterVMSItemSlctn('oneVmsTrnsCoinsRow_<?php echo $cntr; ?>');
                                                                        });">
                                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                                </label>
                                                            </div>
                                                        </td>
                                                        <td class="lovtd" style="<?php echo $srcCageColDsply; ?>">
                                                            <div class="input-group" style="width:100% !important;">
                                                                <input type="text" class="form-control" aria-label="..." id="oneVmsTrnsCoinsRow<?php echo $cntr; ?>_SrcCageNm" name="oneVmsTrnsCoinsRow<?php echo $cntr; ?>_SrcCageNm" value="<?php echo $srcCageNm1; ?>" readonly="true" style="width:100% !important;">
                                                                <input type="hidden" class="form-control" aria-label="..." id="oneVmsTrnsCoinsRow<?php echo $cntr; ?>_SrcCageID" value="<?php echo $srcCageID1; ?>" style="width:100% !important;">
                                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All VMS Vault Cages', '', 'vmsTrnsCrncyNm', '', 'radio', true, '<?php echo $srcCageID1; ?>', 'oneVmsTrnsCoinsRow<?php echo $cntr; ?>_SrcCageID', 'oneVmsTrnsCoinsRow<?php echo $cntr; ?>_SrcCageNm', 'clear', 1, '', function () {
                                                                            afterVMSItemSlctn('oneVmsTrnsCoinsRow_<?php echo $cntr; ?>');
                                                                        });">
                                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                                </label>
                                                            </div>
                                                        </td>
                                                        <td class="lovtd" style="<?php echo $dstCageColDsply; ?>">
                                                            <div class="input-group" style="width:100% !important;">
                                                                <input type="text" class="form-control" aria-label="..." id="oneVmsTrnsCoinsRow<?php echo $cntr; ?>_DstCageNm" name="oneVmsTrnsCoinsRow<?php echo $cntr; ?>_DstCageNm" value="<?php echo $destCageNm1; ?>" readonly="true" style="width:100% !important;">
                                                                <input type="hidden" class="form-control" aria-label="..." id="oneVmsTrnsCoinsRow<?php echo $cntr; ?>_DstCageID" value="<?php echo $destCageID1; ?>" style="width:100% !important;">
                                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All VMS Vault Cages', '', 'vmsTrnsCrncyNm', '', 'radio', true, '<?php echo $destCageID1; ?>', 'oneVmsTrnsCoinsRow<?php echo $cntr; ?>_DstCageID', 'oneVmsTrnsCoinsRow<?php echo $cntr; ?>_DstCageNm', 'clear', 1, '', function () {
                                                                            afterVMSItemSlctn('oneVmsTrnsCoinsRow_<?php echo $cntr; ?>');
                                                                        });">
                                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                                </label>
                                                            </div>
                                                        </td>
                                                        <td class="lovtd" style="<?php echo $dsplyLnRemark; ?>">
                                                            <input type="text" class="form-control" aria-label="..." id="oneVmsTrnsCoinsRow<?php echo $cntr; ?>_LineDesc" name="oneVmsTrnsCoinsRow<?php echo $cntr; ?>_LineDesc" value="<?php echo $lineDesc; ?>" style="width:100% !important;" <?php echo $mkReadOnly; ?>>
                                                        </td>
                                                        <td class="lovtd">
                                                            <input type="text" class="form-control vmsCbQty" aria-label="..." id="oneVmsTrnsCoinsRow<?php echo $cntr; ?>_Qty" name="oneVmsTrnsCoinsRow<?php echo $cntr; ?>_Qty" value="<?php
                                                                                                                                                                                                                                        echo number_format($qty, 0);
                                                                                                                                                                                                                                        ?>" onchange="calcCshBrkdwnRowVal('oneVmsTrnsCoinsRow_<?php echo $cntr; ?>');" onkeypress="vmsTrnsFormKeyPress(event, 'oneVmsTrnsCoinsRow_<?php echo $cntr; ?>');" style="width:100% !important;text-align: right;" <?php echo $mkReadOnly; ?>>
                                                            <input type="hidden" class="form-control" aria-label="..." id="oneVmsTrnsCoinsRow<?php echo $cntr; ?>_UntVal" name="oneVmsTrnsCoinsRow<?php echo $cntr; ?>_UntVal" value="<?php echo $unitVal;
                                                                                                                                                                                                                                        ?>" style="width:100% !important;" readonly="true">
                                                        </td>
                                                        <td class="lovtd">
                                                            <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;font-weight:bold;" onclick="getOneUOMBrkdwnForm(-1, 22, 'oneVmsTrnsCoinsRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="View QTY Breakdown"><?php echo $baseUOMNm; ?></button>
                                                        </td>
                                                        <td class="lovtd">
                                                            <input type="text" class="form-control vmsCbTtl" aria-label="..." id="oneVmsTrnsCoinsRow<?php echo $cntr; ?>_TtlVal" name="oneVmsTrnsCoinsRow<?php echo $cntr; ?>_TtlVal" value="<?php
                                                                                                                                                                                                                                                echo number_format($ttlRowVal, 2);
                                                                                                                                                                                                                                                ?>" onchange="calcCshBrkdwnTtlVal('oneVmsTrnsCoinsRow_<?php echo $cntr; ?>');" onkeypress="vmsTrnsFormTtlFldKeyPress(event, 'oneVmsTrnsCoinsRow_<?php echo $cntr; ?>');" style="width:100% !important;text-align: right;" <?php echo $mkReadOnly; ?>>
                                                        </td>
                                                        <td class="lovtd" style="<?php echo $dsplyPymntInfo; ?>">
                                                            <input type="text" class="form-control vmsFncCrncy" aria-label="..." id="oneVmsTrnsCoinsRow<?php echo $cntr; ?>_PymntCrncyRate" name="oneVmsTrnsCoinsRow<?php echo $cntr; ?>_PymntCrncyRate" value="<?php
                                                                                                                                                                                                                                                                echo number_format($funcCrncyRate, 15);
                                                                                                                                                                                                                                                                ?>" onchange="calcPymtAmntTtl('oneVmsTrnsCoinsRow_<?php echo $cntr; ?>');" onkeypress="vmsTrnsFormFnCrncyKeyPress(event, 'oneVmsTrnsCoinsRow_<?php echo $cntr; ?>');" style="width:100% !important;text-align: right;" <?php echo $mkReadOnly; ?>>
                                                        </td>
                                                        <td class="lovtd" style="<?php echo $dsplySrcBalance; ?>">
                                                            <input type="text" class="form-control vmsCbRnngBal" aria-label="..." id="oneVmsTrnsCoinsRow<?php echo $cntr; ?>_SrcRnngBal" name="oneVmsTrnsCoinsRow<?php echo $cntr; ?>_SrcRnngBal" value="<?php
                                                                                                                                                                                                                                                            if ($srcCageID1 > 0) {
                                                                                                                                                                                                                                                                echo number_format($srcRunningBalance, 2);
                                                                                                                                                                                                                                                            } else {
                                                                                                                                                                                                                                                                echo "0.00";
                                                                                                                                                                                                                                                            }
                                                                                                                                                                                                                                                            ?>" style="width:100% !important;text-align: right;font-weight:bold;color:blue;" readonly="true">
                                                            <?php
                                                            if (strpos($capturedItemIDs, ";S" . $itemID . "_" . $srcCageID1 . ";") === FALSE) {
                                                                $capturedItemIDs .= ";S" . $itemID . "_" . $srcCageID1 . ";";
                                                                $extrInputFlds .= "<input type=\"hidden\" id=\"oneVmsTrnsFrmSrcCage_" . $itemID . "_" . $srcCageID1 . "\" name=\"oneVmsTrnsFrmSrcCage_" . $itemID . "_" . $srcCageID1 . "\" value=\"" . number_format($srcRunningBalance, 2) . "\" readonly=\"true\">";
                                                            }
                                                            if (strpos($capturedItemIDs, ";D" . $itemID . "_" . $destCageID1 . ";") === FALSE) {
                                                                $capturedItemIDs .= ";D" . $itemID . "_" . $destCageID1 . ";";
                                                                $extrInputFlds .= "<input type=\"hidden\" id=\"oneVmsTrnsFrmDstCage_" . $itemID . "_" . $destCageID1 . "\" name=\"oneVmsTrnsFrmDstCage_" . $itemID . "_" . $destCageID1 . "\" value=\"" . number_format($dstRunningBalance, 2) . "\" readonly=\"true\">";
                                                            }
                                                            ?>
                                                        </td>
                                                        <td class="lovtd" style="<?php echo $dsplyDstBalance; ?>">
                                                            <input type="text" class="form-control vmsCbRnngBal" aria-label="..." id="oneVmsTrnsCoinsRow<?php echo $cntr; ?>_DstRnngBal" name="oneVmsTrnsCoinsRow<?php echo $cntr; ?>_DstRnngBal" value="<?php
                                                                                                                                                                                                                                                            if ($destCageID1 > 0) {
                                                                                                                                                                                                                                                                echo number_format($dstRunningBalance, 2);
                                                                                                                                                                                                                                                            } else {
                                                                                                                                                                                                                                                                echo "0.00";
                                                                                                                                                                                                                                                            }
                                                                                                                                                                                                                                                            ?>" style="width:100% !important;text-align: right;font-weight:bold;color:blue;" readonly="true">
                                                        </td>
                                                        <td class="lovtd" style="<?php echo $dsplySrcItemState; ?>">
                                                            <div class="input-group" style="width:100% !important;">
                                                                <input type="text" class="form-control" aria-label="..." id="oneVmsTrnsCoinsRow<?php echo $cntr; ?>_SrcItemState" name="oneVmsTrnsCoinsRow<?php echo $cntr; ?>_SrcItemState" value="<?php echo $srcItemState; ?>" readonly="true" style="width:100% !important;">
                                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Vault Item States', '', '', '', 'radio', true, '', '', 'oneVmsTrnsCoinsRow<?php echo $cntr; ?>_SrcItemState', 'clear', 1, '', function () {
                                                                            afterVMSItemSlctn('oneVmsTrnsCoinsRow_<?php echo $cntr; ?>');
                                                                        });">
                                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                                </label>
                                                            </div>
                                                        </td>
                                                        <td class="lovtd" style="<?php echo $dsplyDstItemState; ?>">
                                                            <div class="input-group" style="width:100% !important;">
                                                                <input type="text" class="form-control" aria-label="..." id="oneVmsTrnsCoinsRow<?php echo $cntr; ?>_DstItemState" name="oneVmsTrnsCoinsRow<?php echo $cntr; ?>_DstItemState" value="<?php echo $dstItemState; ?>" readonly="true" style="width:100% !important;">
                                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Vault Item States', '', '', '', 'radio', true, '', '', 'oneVmsTrnsCoinsRow<?php echo $cntr; ?>_DstItemState', 'clear', 1, '', function () {
                                                                            afterVMSItemSlctn('oneVmsTrnsCoinsRow_<?php echo $cntr; ?>');
                                                                        });">
                                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                                </label>
                                                            </div>
                                                        </td>
                                                        <td class="lovtd">
                                                            <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delVmsTrnsLine('oneVmsTrnsCoinsRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Trns. Line">
                                                                <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                            </button>
                                                        </td>
                                                    </tr>
                                                    <?php
                                                }

                                                if (($voidedTrnsHdrID <= 0 || $orgnlVmsTrnsHdrID <= 0) && ($rqStatus == "Not Submitted" || $rqStatus == "Withdrawn" || $rqStatus == "Rejected")) {
                                                    $excludedItems = trim($excludedItems, ", ");
                                                    $resultRw = get_Fresh_VMSTrnsLines($sbmtdVmsTrnsHdrID, $crncyID, $destCageID, $excludedItems, $destVltID, $dfltDstItemState, $srcCageID, $srcVltID, $dfltSrcItemState, -1, "Coin");
                                                    $maxNoRows = loc_db_num_rows($resultRw);
                                                    $nwcntr = 0;
                                                    while ($nwcntr < $maxNoRows) {
                                                        $itemID = -1;
                                                        $trnsLnID = -1;
                                                        $itemName = "";
                                                        $baseUOMID = -1;
                                                        $unitVal = 0.00;
                                                        $qty = 0.00;
                                                        $tray = 0.00;
                                                        $bundle = 0.00;
                                                        $baseUOMNm = "";
                                                        $ttlRowVal = 0.00;
                                                        $srcItemState = $dfltSrcItemState;
                                                        $dstItemState = $dfltDstItemState;
                                                        $funcCrncyRate = 0;
                                                        $lineDesc = "";
                                                        $srcRunningBalance = 0;
                                                        $dstRunningBalance = 0;
                                                        if ($rowRw = loc_db_fetch_array($resultRw)) {
                                                            $trnsLnID = (float) $rowRw[0];
                                                            $itemID = (int) $rowRw[2];
                                                            $lineDesc = $rowRw[36];
                                                            if ($lineDesc == "") {
                                                                $lineDesc = $vmsTrnsDesc;
                                                            }
                                                            $srcRunningBalance = (float) $rowRw[35];
                                                            $dstRunningBalance = (float) $rowRw[37];
                                                            $itemName = $rowRw[29];
                                                            $unitVal = (float) $rowRw[9];
                                                            $baseUOMNm = $rowRw[31];
                                                            $baseUOMID = (int) $rowRw[26];
                                                            $qty = (float) $rowRw[7];
                                                            $ttlRowVal = $qty * $unitVal;
                                                            if ($trnsLnID > 0) {
                                                                $srcItemState = $rowRw[27];
                                                                $dstItemState = $rowRw[34];
                                                            } else {
                                                                $srcItemState = $dfltSrcItemState;
                                                                $dstItemState = $dfltDstItemState;
                                                            }
                                                            $funcCrncyRate = (float) $rowRw[21];
                                                        }
                                                        $cntr += 1;
                                                        $nwcntr += 1;
                                                    ?>
                                                        <tr id="oneVmsTrnsCoinsRow_<?php echo $cntr; ?>">
                                                            <!--<td class="lovtd"><span><?php echo ($cntr); ?></span></td>-->
                                                            <td class="lovtd">
                                                                <div class="input-group" style="width:100% !important;">
                                                                    <input type="text" class="form-control" aria-label="..." id="oneVmsTrnsCoinsRow<?php echo $cntr; ?>_ItemNm" name="oneVmsTrnsCoinsRow<?php echo $cntr; ?>_ItemNm" value="<?php echo $itemName; ?>" readonly="true" style="width:100% !important;">
                                                                    <input type="hidden" class="form-control" aria-label="..." id="oneVmsTrnsCoinsRow<?php echo $cntr; ?>_ItmID" value="<?php echo $itemID; ?>" style="width:100% !important;">
                                                                    <input type="hidden" class="form-control" aria-label="..." id="oneVmsTrnsCoinsRow<?php echo $cntr; ?>_BaseUoMID" value="<?php echo $baseUOMID; ?>" style="width:100% !important;">
                                                                    <input type="hidden" class="form-control" aria-label="..." id="oneVmsTrnsCoinsRow<?php echo $cntr; ?>_TrnsLnID" value="<?php echo $trnsLnID; ?>" style="width:100% !important;">
                                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Vault Item List', 'gnrlVmsOrgID', 'vmsTrnsCrncyNm', '', 'radio', true, '', 'oneVmsTrnsCoinsRow<?php echo $cntr; ?>_ItmID', 'oneVmsTrnsCoinsRow<?php echo $cntr; ?>_ItemNm', 'clear', 1, '', function () {
                                                                                afterVMSItemSlctn('oneVmsTrnsCoinsRow_<?php echo $cntr; ?>');
                                                                            });">
                                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                                    </label>
                                                                </div>
                                                            </td>
                                                            <td class="lovtd" style="<?php echo $srcCageColDsply; ?>">
                                                                <div class="input-group" style="width:100% !important;">
                                                                    <input type="text" class="form-control" aria-label="..." id="oneVmsTrnsCoinsRow<?php echo $cntr; ?>_SrcCageNm" name="oneVmsTrnsCoinsRow<?php echo $cntr; ?>_SrcCageNm" value="<?php echo $srcCageNm; ?>" readonly="true" style="width:100% !important;">
                                                                    <input type="hidden" class="form-control" aria-label="..." id="oneVmsTrnsCoinsRow<?php echo $cntr; ?>_SrcCageID" value="<?php echo $srcCageID; ?>" style="width:100% !important;">
                                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All VMS Vault Cages', '', 'vmsTrnsCrncyNm', '', 'radio', true, '<?php echo $srcCageID; ?>', 'oneVmsTrnsCoinsRow<?php echo $cntr; ?>_SrcCageID', 'oneVmsTrnsCoinsRow<?php echo $cntr; ?>_SrcCageNm', 'clear', 1, '', function () {
                                                                                afterVMSItemSlctn('oneVmsTrnsCoinsRow_<?php echo $cntr; ?>');
                                                                            });">
                                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                                    </label>
                                                                </div>
                                                            </td>
                                                            <td class="lovtd" style="<?php echo $dstCageColDsply; ?>">
                                                                <div class="input-group" style="width:100% !important;">
                                                                    <input type="text" class="form-control" aria-label="..." id="oneVmsTrnsCoinsRow<?php echo $cntr; ?>_DstCageNm" name="oneVmsTrnsCoinsRow<?php echo $cntr; ?>_DstCageNm" value="<?php echo $destCageNm; ?>" readonly="true" style="width:100% !important;">
                                                                    <input type="hidden" class="form-control" aria-label="..." id="oneVmsTrnsCoinsRow<?php echo $cntr; ?>_DstCageID" value="<?php echo $destCageID; ?>" style="width:100% !important;">
                                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All VMS Vault Cages', '', 'vmsTrnsCrncyNm', '', 'radio', true, '<?php echo $destCageID; ?>', 'oneVmsTrnsCoinsRow<?php echo $cntr; ?>_DstCageID', 'oneVmsTrnsCoinsRow<?php echo $cntr; ?>_DstCageNm', 'clear', 1, '', function () {
                                                                                afterVMSItemSlctn('oneVmsTrnsCoinsRow_<?php echo $cntr; ?>');
                                                                            });">
                                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                                    </label>
                                                                </div>
                                                            </td>
                                                            <td class="lovtd" style="<?php echo $dsplyLnRemark; ?>">
                                                                <input type="text" class="form-control" aria-label="..." id="oneVmsTrnsCoinsRow<?php echo $cntr; ?>_LineDesc" name="oneVmsTrnsCoinsRow<?php echo $cntr; ?>_LineDesc" value="<?php echo $lineDesc; ?>" style="width:100% !important;" <?php echo $mkReadOnly; ?>>
                                                            </td>
                                                            <td class="lovtd">
                                                                <input type="text" class="form-control vmsCbQty" aria-label="..." id="oneVmsTrnsCoinsRow<?php echo $cntr; ?>_Qty" name="oneVmsTrnsCoinsRow<?php echo $cntr; ?>_Qty" value="<?php
                                                                                                                                                                                                                                            echo number_format($qty, 0);
                                                                                                                                                                                                                                            ?>" onchange="calcCshBrkdwnRowVal('oneVmsTrnsCoinsRow_<?php echo $cntr; ?>');" onkeypress="vmsTrnsFormKeyPress(event, 'oneVmsTrnsCoinsRow_<?php echo $cntr; ?>');" style="width:100% !important;text-align: right;" <?php echo $mkReadOnly; ?>>
                                                                <input type="hidden" class="form-control" aria-label="..." id="oneVmsTrnsCoinsRow<?php echo $cntr; ?>_UntVal" name="oneVmsTrnsCoinsRow<?php echo $cntr; ?>_UntVal" value="<?php echo $unitVal;
                                                                                                                                                                                                                                            ?>" style="width:100% !important;" readonly="true">
                                                            </td>
                                                            <td class="lovtd">
                                                                <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;font-weight:bold;" onclick="getOneUOMBrkdwnForm(-1, 22, 'oneVmsTrnsCoinsRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="View QTY Breakdown"><?php echo $baseUOMNm; ?></button>
                                                            </td>
                                                            <td class="lovtd">
                                                                <input type="text" class="form-control vmsCbTtl" aria-label="..." id="oneVmsTrnsCoinsRow<?php echo $cntr; ?>_TtlVal" name="oneVmsTrnsCoinsRow<?php echo $cntr; ?>_TtlVal" value="<?php
                                                                                                                                                                                                                                                    echo number_format($ttlRowVal, 2);
                                                                                                                                                                                                                                                    ?>" onchange="calcCshBrkdwnTtlVal('oneVmsTrnsCoinsRow_<?php echo $cntr; ?>');" onkeypress="vmsTrnsFormTtlFldKeyPress(event, 'oneVmsTrnsCoinsRow_<?php echo $cntr; ?>');" style="width:100% !important;text-align: right;" <?php echo $mkReadOnly; ?>>
                                                            </td>
                                                            <td class="lovtd" style="<?php echo $dsplyPymntInfo; ?>">
                                                                <input type="text" class="form-control vmsFncCrncy" aria-label="..." id="oneVmsTrnsCoinsRow<?php echo $cntr; ?>_PymntCrncyRate" name="oneVmsTrnsCoinsRow<?php echo $cntr; ?>_PymntCrncyRate" value="<?php
                                                                                                                                                                                                                                                                    echo number_format($funcCrncyRate, 15);
                                                                                                                                                                                                                                                                    ?>" onchange="calcPymtAmntTtl('oneVmsTrnsCoinsRow_<?php echo $cntr; ?>');" onkeypress="vmsTrnsFormFnCrncyKeyPress(event, 'oneVmsTrnsCoinsRow_<?php echo $cntr; ?>');" style="width:100% !important;text-align: right;" <?php echo $mkReadOnly; ?>>
                                                            </td>
                                                            <td class="lovtd" style="<?php echo $dsplySrcBalance; ?>">
                                                                <input type="text" class="form-control vmsCbRnngBal" aria-label="..." id="oneVmsTrnsCoinsRow<?php echo $cntr; ?>_SrcRnngBal" name="oneVmsTrnsCoinsRow<?php echo $cntr; ?>_SrcRnngBal" value="<?php
                                                                                                                                                                                                                                                                if ($srcCageID > 0) {
                                                                                                                                                                                                                                                                    echo number_format($srcRunningBalance, 2);
                                                                                                                                                                                                                                                                } else {
                                                                                                                                                                                                                                                                    echo "0.00";
                                                                                                                                                                                                                                                                }
                                                                                                                                                                                                                                                                ?>" style="width:100% !important;text-align: right;font-weight:bold;color:blue;" readonly="true">
                                                                <?php
                                                                if (strpos($capturedItemIDs, ";S" . $itemID . "_" . $srcCageID . ";") === FALSE) {
                                                                    $capturedItemIDs .= ";S" . $itemID . "_" . $srcCageID . ";";
                                                                    $extrInputFlds .= "<input type=\"hidden\" id=\"oneVmsTrnsFrmSrcCage_" . $itemID . "_" . $srcCageID . "\" name=\"oneVmsTrnsFrmSrcCage_" . $itemID . "_" . $srcCageID . "\" value=\"" . number_format($srcRunningBalance, 2) . "\" readonly=\"true\">";
                                                                }
                                                                if (strpos($capturedItemIDs, ";D" . $itemID . "_" . $destCageID . ";") === FALSE) {
                                                                    $capturedItemIDs .= ";D" . $itemID . "_" . $destCageID . ";";
                                                                    $extrInputFlds .= "<input type=\"hidden\" id=\"oneVmsTrnsFrmDstCage_" . $itemID . "_" . $destCageID . "\" name=\"oneVmsTrnsFrmDstCage_" . $itemID . "_" . $destCageID . "\" value=\"" . number_format($dstRunningBalance, 2) . "\" readonly=\"true\">";
                                                                }
                                                                ?>
                                                            </td>
                                                            <td class="lovtd" style="<?php echo $dsplyDstBalance; ?>">
                                                                <input type="text" class="form-control vmsCbRnngBal" aria-label="..." id="oneVmsTrnsCoinsRow<?php echo $cntr; ?>_DstRnngBal" name="oneVmsTrnsCoinsRow<?php echo $cntr; ?>_DstRnngBal" value="<?php
                                                                                                                                                                                                                                                                if ($destCageID > 0) {
                                                                                                                                                                                                                                                                    echo number_format($dstRunningBalance, 2);
                                                                                                                                                                                                                                                                } else {
                                                                                                                                                                                                                                                                    echo "0.00";
                                                                                                                                                                                                                                                                }
                                                                                                                                                                                                                                                                ?>" style="width:100% !important;text-align: right;font-weight:bold;color:blue;" readonly="true">
                                                            </td>
                                                            <td class="lovtd" style="<?php echo $dsplySrcItemState; ?>">
                                                                <div class="input-group" style="width:100% !important;">
                                                                    <input type="text" class="form-control" aria-label="..." id="oneVmsTrnsCoinsRow<?php echo $cntr; ?>_SrcItemState" name="oneVmsTrnsCoinsRow<?php echo $cntr; ?>_SrcItemState" value="<?php echo $srcItemState; ?>" readonly="true" style="width:100% !important;">
                                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Vault Item States', '', '', '', 'radio', true, '', '', 'oneVmsTrnsCoinsRow<?php echo $cntr; ?>_SrcItemState', 'clear', 1, '', function () {
                                                                                afterVMSItemSlctn('oneVmsTrnsCoinsRow_<?php echo $cntr; ?>');
                                                                            });">
                                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                                    </label>
                                                                </div>
                                                            </td>
                                                            <td class="lovtd" style="<?php echo $dsplyDstItemState; ?>">
                                                                <div class="input-group" style="width:100% !important;">
                                                                    <input type="text" class="form-control" aria-label="..." id="oneVmsTrnsCoinsRow<?php echo $cntr; ?>_DstItemState" name="oneVmsTrnsCoinsRow<?php echo $cntr; ?>_DstItemState" value="<?php echo $dstItemState; ?>" readonly="true" style="width:100% !important;">
                                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Vault Item States', '', '', '', 'radio', true, '', '', 'oneVmsTrnsCoinsRow<?php echo $cntr; ?>_DstItemState', 'clear', 1, '', function () {
                                                                                afterVMSItemSlctn('oneVmsTrnsCoinsRow_<?php echo $cntr; ?>');
                                                                            });">
                                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                                    </label>
                                                                </div>
                                                            </td>
                                                            <td class="lovtd">
                                                                <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delVmsTrnsLine('oneVmsTrnsCoinsRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Trns. Line">
                                                                    <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                                </button>
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
                            <div id="rnngBalsHiddenFields">
                                <input type="hidden" id="oneVmsUomCnvFctr_Tray" value="<?php echo $trayCnvFctr; ?>" />
                                <input type="hidden" id="oneVmsUomCnvFctr_Bndl" value="<?php echo $bndlCnvFctr; ?>" />
                                <?php echo $extrInputFlds; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </fieldset>
    </form>
<?php
}

function getMyLnkdVmsCages($prsnID)
{
    $strSql = "SELECT a.shelf_id, a.store_id, a.line_id, a.shelve_name, a.shelve_desc, 
       a.lnkd_cstmr_id, a.allwd_group_type, a.allwd_group_value, a.enabled_flag, 
       a.inv_asset_acct_id, a.cage_shelve_mngr_id
       FROM inv.inv_shelf a 
       WHERE a.cage_shelve_mngr_id>0 and a.cage_shelve_mngr_id = " . $prsnID .
        " ORDER BY line_id DESC";
    $result = executeSQLNoParams($strSql);
    return $result;
}

function getMyVmsCages($prsnID, $sbmtdSiteID)
{
    $strSql = "SELECT a.shelf_id, a.store_id, a.line_id, a.shelve_name, a.shelve_desc, 
       a.lnkd_cstmr_id, a.allwd_group_type, a.allwd_group_value, a.enabled_flag, 
       a.inv_asset_acct_id, a.cage_shelve_mngr_id
       FROM inv.inv_shelf a, inv.inv_itm_subinventories b 
       WHERE a.store_id= b.subinv_id and a.cage_shelve_mngr_id>0 and a.cage_shelve_mngr_id = " . $prsnID .
        " and b.lnkd_site_id=" . $sbmtdSiteID . " ORDER BY line_id DESC";
    $result = executeSQLNoParams($strSql);
    return $result;
}

function calcAllVMSLinesTtlVals($sbmtdVmsTrnsHdrID)
{
    executeSQLNoParams("Select vms.check_trans_duplcts(" . $sbmtdVmsTrnsHdrID . ")");
    //$vmsTrnsType = getGnrlRecNm("vms.vms_transactions_hdr", "trans_hdr_id", "trans_type", $sbmtdVmsTrnsHdrID);
    $nwSrcRnngBal = 0;
    $nwDstRnngBal = 0;
    $result = get_One_VMSTrnsLines($sbmtdVmsTrnsHdrID);
    $i = 0;
    while ($row = loc_db_fetch_array($result)) {
        $lineSrcCgID = (int) $row[4];
        $lineDstCgID = (int) $row[6];
        $trnsLnID = $row[0];
        $newRnngBals = 0;
        $rnngBals = 0;
        $nwSrcRnngBal = 0;
        $nwDstRnngBal = 0;
        //$val = (float) $row[9];
        //$ttlDenomQty = (float) $row[7]; 
        $itemID = (int) $row[2];
        if ($lineSrcCgID > 0) {
            $rnngBals = (float) $row[42];
            $newRnngBals = $rnngBals + calcItemTtlVal($sbmtdVmsTrnsHdrID, $itemID, $i, $lineSrcCgID) + calcItemTtlValIssues($sbmtdVmsTrnsHdrID, $itemID, $i, $lineSrcCgID);
            $nwSrcRnngBal = round($newRnngBals, 2);
        }
        if ($lineDstCgID > 0) {
            $rnngBals = (float) $row[43];
            $newRnngBals = $rnngBals + calcItemTtlVal($sbmtdVmsTrnsHdrID, $itemID, $i, $lineDstCgID) + calcItemTtlValIssues($sbmtdVmsTrnsHdrID, $itemID, $i, $lineDstCgID);
            $nwDstRnngBal = round($newRnngBals, 2);
        }
        if ($nwSrcRnngBal >= 0 && $nwDstRnngBal >= 0) {
            $fnlUpdateSQL = "UPDATE vms.vms_transaction_lines SET src_balance_afta_trns = $nwSrcRnngBal, dst_balance_afta_trns =$nwDstRnngBal WHERE trans_det_ln_id = " . $trnsLnID;
            execUpdtInsSQL($fnlUpdateSQL);
        }
        $i++;
    }
}

function calcItemTtlVal($sbmtdVmsTrnsHdrID, $tstItemID, $idx, $lineCgID)
{
    $ttlValue = 0;
    $i = 0;
    $result = get_One_VMSTrnsLines($sbmtdVmsTrnsHdrID);
    while ($row = loc_db_fetch_array($result)) {
        if ($i <= $idx) {
            $lineDstCgID = (int) $row[6];
            $val = (float) $row[9];
            $itemID = (int) $row[2];
            $ttlDenomQty = (float) $row[7];
            $ttlCptrd1 = $val * $ttlDenomQty;
            if ($itemID > 0 && $tstItemID === $itemID) {
                if ($lineDstCgID > 0 && $lineCgID == $lineDstCgID) {
                    $ttlValue = $ttlValue + $ttlCptrd1;
                }
            }
            $i++;
        }
    }
    return $ttlValue;
}

function calcItemTtlValIssues($sbmtdVmsTrnsHdrID, $tstItemID, $idx, $lineCgID)
{
    $ttlValue = 0;
    $i = 0;
    $result = get_One_VMSTrnsLines($sbmtdVmsTrnsHdrID);
    while ($row = loc_db_fetch_array($result)) {
        if ($i <= $idx) {
            $lineSrcCgID = (int) $row[4];
            $val = (float) $row[9];
            $itemID = (int) $row[2];
            $ttlDenomQty = (float) $row[7];
            $ttlCptrd1 = $val * $ttlDenomQty;
            if ($itemID > 0 && $tstItemID === $itemID) {
                if ($lineSrcCgID > 0 && $lineCgID == $lineSrcCgID) {
                    $ttlValue = $ttlValue - $ttlCptrd1;
                }
            }
            $i++;
        }
    }
    return $ttlValue;
}
?>