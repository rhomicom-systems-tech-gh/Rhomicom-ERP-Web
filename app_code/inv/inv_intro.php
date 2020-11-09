<?php

$menuItems = array("Sales/Item Issues", "Purchases", "Item List", "Product Categories", "Stores/Warehouses"
    , "Receipts", "Receipt Returns", "Item Type Templates", "Sales/Item Balance Reports", "Unit of Measures", "Stock Transfers",
    "Misc. Adjustments", "GL Interface Table", "Product Creation", "Credit Analysis", "Standard Reports");
$menuImages = array("sale.jpg", "purchases.jpg", "chcklst3.png", "categories.png", "WareHouseMgmtIcon.png"
    , "receipt.jpg", "receipt_return.jpg", "item_template.jpg"
    , "invoice.ico", "uom.png", "stock_trnsfr.png", "adjustments.png",
    "GL-256.png", "assets1.jpg", "loans_prdt.png", "report-icon-png.png");

$mdlNm = "Stores And Inventory Manager";
$ModuleName = $mdlNm;

$dfltPrvldgs = array("View Inventory Manager",
    /* 1 */ "View Item List", "View Product Categories", "View Stores/Warehouses",
    /* 4 */ "View Receipts", "View Receipt Returns", "View Item Type Templates",
    /* 7 */ "View Item Balances",
    /* 8 */ "Add Items", "Update Items",
    /* 10 */ "Add Item Stores", "Update Item Stores", "Delete Item Stores",
    /* 13 */ "Add Product Category", "Update Product Category",
    /* 15 */ "Add Stores", "Update Stores",
    /* 17 */ "Add Store Users", "Update Store Users", "Delete Store Users",
    /* 20 */ "Add Store Shelves", "Delete Store Shelves",
    /* 22 */ "Add Receipt", "Delete Receipt",
    /* 24 */ "Add Receipt Return", "Delete Receipt Return",
    /* 26 */ "Add Item Template", "Update Item Template",
    /* 28 */ "Add Template Stores", "Update Template Stores",
    /* 30 */ "View GL Interface",
    /* 31 */ "View SQL", "View Record History", "Send To GL Interface Table",
    /* 34 */ "View Purchases", "View Sales/Item Issues", "View Sales Returns",
    /* 37 */ "View Payments Received",
    /* 38 */ "View Purchase Requisitions", "Add Purchase Requisitions", "Edit Purchase Requisitions", "Delete Purchase Requisitions",
    /* 42 */ "View Purchase Orders", "Add Purchase Orders", "Edit Purchase Orders", "Delete Purchase Orders",
    /* 46 */ "View Pro-Forma Invoices", "Add Pro-Forma Invoices", "Edit Pro-Forma Invoices", "Delete Pro-Forma Invoices",
    /* 50 */ "View Sales Orders", "Add Sales Orders", "Edit Sales Orders", "Delete Sales Orders",
    /* 54 */ "View Sales Invoices", "Add Sales Invoices", "Edit Sales Invoices", "Delete Sales Invoices",
    /* 58 */ "View Internal Item Requests", "Add Internal Item Requests", "Edit Internal Item Requests", "Delete Internal Item Requests",
    /* 62 */ "View Item Issues-Unbilled", "Add Item Issues-Unbilled", "Edit Item Issues-Unbilled", "Delete Item Issues-Unbilled",
    /* 66 */ "View Sales Returns", "Add Sales Return", "Edit Sales Return", "Delete Sales Return",
    /* 70 */ "Send GL Interface Records to GL", "Cancel Documents", "View only Self-Created Documents",
    /* 73 */ "View UOM", "Add UOM", "Edit UOM", "Delete UOM", "Make Payments", "Delete Product Category",
    /* 79 */ "View UOM Conversion", "Add UOM Conversion", "Edit UOM Conversion", "Delete UOM Conversion",
    /* 83 */ "View Drug Interactions", "Add Drug Interactions", "Edit Drug Interactions", "Delete Drug Interactions",
    /* 87 */ "Edit Receipt", "Edit Returns", "Edit Store Transfers", "Edit Adjustments",
    /* 91 */ "Clear Stock Balance", "Do Quick Receipt",
    /* 93 */ "View Item Production", "Add Item Production", "Edit Item Production", "Delete Item Production",
    /* 97 */ "Setup Production Processes", "Apply Adhoc Discounts",
    /* 99 */ "View Production Runs", "Add Production Runs", "Edit Production Runs", "Delete Production Runs",
    /* 103 */ "Can Edit Unit Price", "View only Branch-Related Documents");

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

$invPrmSnsRstl = getInvPgPrmssns($prsnid, $orgID, $usrID);
$fnccurid = $invPrmSnsRstl[0];
$fnccurnm = getPssblValNm($fnccurid);
$brnchLocID = $invPrmSnsRstl[1];
$brnchLoc = getGnrlRecNm("org.org_sites_locations", "location_id", "REPLACE(location_code_name || '.' || site_desc, '.' || location_code_name,'')", $brnchLocID);
$acsCntrlGrpID = $invPrmSnsRstl[2];
if ($selectedStoreID <= 0) {
    $selectedStoreID = $invPrmSnsRstl[3];
    $_SESSION['SELECTED_SALES_STOREID'] = $selectedStoreID;
}
$acsCntrlGrpNm = getStoreNm($selectedStoreID);
$canview = ($invPrmSnsRstl[4] >= 1) ? true : false;
$canViewSales = ($invPrmSnsRstl[5] >= 1) ? true : false;
$canViewPrchs = ($invPrmSnsRstl[6] >= 1) ? true : false;
$canViewItmLst = ($invPrmSnsRstl[7] >= 1) ? true : false;
$canViewCtgry = ($invPrmSnsRstl[8] >= 1) ? true : false;
$canViewStores = ($invPrmSnsRstl[9] >= 1) ? true : false;
$canViewRcpts = ($invPrmSnsRstl[10] >= 1) ? true : false;
$canViewRtrns = ($invPrmSnsRstl[11] >= 1) ? true : false;
$canViewTmplts = ($invPrmSnsRstl[12] >= 1) ? true : false;
$canViewBals = ($invPrmSnsRstl[13] >= 1) ? true : false;
$canViewUOM = ($invPrmSnsRstl[14] >= 1) ? true : false;
$canViewGLIntrfc = ($invPrmSnsRstl[15] >= 1) ? true : false;
$canViewPrdctn = ($invPrmSnsRstl[16] >= 1) ? true : false;
$canVwRcHstry = ($invPrmSnsRstl[17] >= 1) ? true : false;
$canViewSQL = ($invPrmSnsRstl[18] >= 1) ? true : false;
$vwOnlySelf = ($invPrmSnsRstl[19] >= 1) ? true : false;
$canEdtItmPrice = ($invPrmSnsRstl[20] >= 1) ? true : false;
$vwOnlyBranch = ($invPrmSnsRstl[21] >= 1) ? true : false;
$canViewrpts = true;

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
    $cntent .= "
					<li onclick=\"openATab('#allmodules', 'grp=$group&typ=$type');\">
						<span style=\"text-decoration:none;\">Sales/Inventory Menu</span>
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
        /*
          <span style=\"font-family: georgia, times;font-size: 14px;font-style:normal;
          font-weight:bold;color:black\">Branch:</span>
          <span style=\"font-family: tahoma;font-size: 14px;font-style:normal;
          font-weight:bold;color:blue;\">$brnchLoc</span>
          <span style=\"font-family: tahoma;font-size: 14px;font-style:normal;
          font-weight:bold;color:blue;\">$acsCntrlGrpNm</span>
         *  "<div style=\"font-family: Tahoma, Arial, sans-serif;font-size: 1.3em;padding:10px 15px 15px 20px;border:1px solid #ccc;\">                    
          <div style=\"padding:5px 30px 5px 10px;margin-bottom:2px;\">
          <span style=\"font-family: georgia, times;font-size: 12px;font-style:italic;
          font-weight:normal;\">This module helps you to manage your organization's Inventory System! The module has the ff areas:</span>
          </div>
          <p>"; */
        $grpcntr = 0;
        for ($i = 0; $i < count($menuItems); $i++) {
            $No = $i + 1;
            if ($i == 0 && $canViewSales === false) {
                continue;
            } else if ($i == 1 && $canViewPrchs === false) {
                continue;
            } else if ($i == 2 && $canViewItmLst === false) {
                continue;
            } else if ($i == 3 && $canViewCtgry === false) {
                continue;
            } else if ($i == 4 && $canViewStores === false) {
                continue;
            } else if ($i == 5 && $canViewRcpts === false) {
                continue;
            } else if ($i == 6 && $canViewRtrns === false) {
                continue;
            } else if ($i == 7 && $canViewTmplts === false) {
                continue;
            } else if ($i == 8 && $canViewBals === false) {
                continue;
            } else if ($i == 9 && $canViewUOM === false) {
                continue;
            } else if ($i == 10 && $canViewRcpts === false) {
                continue;
            } else if ($i == 11) {
                continue;
            } else if ($i == 12 && $canViewGLIntrfc == false) {
                continue;
            } else if ($i == 13 && $canViewPrdctn === false) {
                continue;
            } else if ($i == 14 && $canViewSales === false) {
                continue;
            } else if ($i == 15 && $canViewrpts === false) {
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
        require "sales_invc.php";
    } else if ($pgNo == 2) {
        require "purchases.php";
    } else if ($pgNo == 3) {
        require "item_list.php";
    } else if ($pgNo == 4) {
        require "prdct_ctgries.php";
    } else if ($pgNo == 5) {
        require "stores.php";
    } else if ($pgNo == 6) {
        require "receipts.php";
    } else if ($pgNo == 7) {
        require "rcpts_returns.php";
    } else if ($pgNo == 8) {
        require "itm_tmplts.php";
    } else if ($pgNo == 9) {
        require "itm_balances.php";
    } else if ($pgNo == 10) {
        require "uom.php";
    } else if ($pgNo == 11) {
        require "stock_trnsfr.php";
    } else if ($pgNo == 12) {
        require "misc_adjstmnts.php";
    } else if ($pgNo == 13) {
        require "gl_intrfc.php";
    } else if ($pgNo == 14) {
        require "production.php";
    } else if ($pgNo == 15) {
        require "cnsmr_credit.php";
    } else if ($pgNo == 16) {
        require "inv_rpts.php";
    } else {
        restricted();
    }
} else {
    restricted();
}
?>
