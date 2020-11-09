<?php

$menuItems = array(
    "Chart of Accounts", "Journal Entries", "Petty Cash Vouchers", "Transactions Search",
    "Financial Statements", "Budgets", "Simplified Transaction Vouchers", "Accounting Periods",
    "Assets & Investments", "Payables Invoices", "Receivables Invoices", "Invoice Payments",
    "Business Partners/Firms", "Tax Codes", "Default Accounts", "Exchange Rates", "Document Templates",
    "Payment Methods", "Account Reconciliation", "Standard Reports"
);

$menuImages = array(
    "GL-256.png", "generaljournal.png", "cashbook_big_icon.png", "CustomIcon.png", "dfltAccnts1.jpg", "budget.jpg", "edit-notes.png", "calendar2.ico", "assets1.jpg", "bills.png", "rcvbls1.jpg", "cheque.png", "cstmrs1.jpg", "tax1.jpg", "settings.png", "sales_growth-512.png", "accounts_mn.jpg", "cheque.png", "mi_scare_report.png", "report-icon-png.png"
);

$mdlNm = "Accounting";
$ModuleName = $mdlNm;

$dfltPrvldgs = array(
    "View Accounting", "View Chart of Accounts",
    /* 2 */ "View Account Transactions", "View Transactions Search",
    /* 4 */ "View/Generate Trial Balance", "View/Generate Profit & Loss Statement",
    /* 6 */ "View/Generate Balance Sheet", "View Budgets",
    /* 8 */ "View Transaction Templates", "View Record History", "View SQL",
    /* 11 */ "Add Chart of Accounts", "Edit Chart of Accounts", "Delete Chart of Accounts",
    /* 14 */ "Add Batch for Transactions", "Edit Batch for Transactions", "Void/Delete Batch for Transactions",
    /* 17 */ "Add Transactions Directly", "Edit Transactions", "Delete Transactions",
    /* 20 */ "Add Transactions Using Template", "Post Transactions",
    /* 22 */ "Add Budgets", "Edit Budgets", "Delete Budgets",
    /* 25 */ "Add Transaction Templates", "Edit Transaction Templates", "Delete Transaction Templates",
    /* 28 */ "View Only Self-Created Transaction Batches",
    /* 29 */ "View Financial Statements", "View Accounting Periods", "View Payables",
    /* 32 */ "View Receivables", "View Customers/Suppliers", "View Tax Codes",
    /* 35 */ "View Default Accounts", "View Account Reconciliation",
    /* 37 */ "Add Accounting Periods", "Edit Accounting Periods", "Delete Accounting Periods",
    /* 40 */ "View Fixed Assets", "View Payments",
    /* 42 */ "Add Payment Methods", "Edit Payment Methods", "Delete Payment Methods",
    /* 45 */ "Add Supplier Standard Payments", "Edit Supplier Standard Payments", "Delete Supplier Standard Payments",
    /* 48 */ "Add Supplier Advance Payments", "Edit Supplier Advance Payments", "Delete Supplier Advance Payments",
    /* 51 */ "Setup Exchange Rates", "Setup Document Templates", "Review/Approve Payables Documents", "Review/Approve Receivables Documents",
    /* 55 */ "Add Direct Refund from Supplier", "Edit Direct Refund from Supplier", "Delete Direct Refund from Supplier",
    /* 58 */ "Add Supplier Credit Memo (InDirect Refund)", "Edit Supplier Credit Memo (InDirect Refund)", "Delete Supplier Credit Memo (InDirect Refund)",
    /* 61 */ "Add Direct Topup for Supplier", "Edit Direct Topup for Supplier", "Delete Direct Topup for Supplier",
    /* 64 */ "Add Supplier Debit Memo (InDirect Topup)", "Edit Supplier Debit Memo (InDirect Topup)", "Delete Supplier Debit Memo (InDirect Topup)",
    /* 67 */ "Cancel Payables Documents", "Cancel Receivables Documents",
    /* 69 */ "Reject Payables Documents", "Reject Receivables Documents",
    /* 71 */ "Pay Payables Documents", "Pay Receivables Documents",
    /* 73 */ "Add Customer Standard Payments", "Edit Customer Standard Payments", "Delete Customer Standard Payments",
    /* 76 */ "Add Customer Advance Payments", "Edit Customer Advance Payments", "Delete Customer Advance Payments",
    /* 79 */ "Add Direct Refund to Customer", "Edit Direct Refund to Customer", "Delete Direct Refund to Customer",
    /* 82 */ "Add Customer Credit Memo (InDirect Topup)", "Edit Customer Credit Memo (InDirect Topup)", "Delete Customer Credit Memo (InDirect Topup)",
    /* 85 */ "Add Direct Topup from Customer", "Edit Direct Topup from Customer", "Delete Direct Topup from Customer",
    /* 88 */ "Add Customer Debit Memo (InDirect Refund)", "Edit Customer Debit Memo (InDirect Refund)", "Delete Customer Debit Memo (InDirect Refund)",
    /* 91 */ "Add Customers/Suppliers", "Edit Customers/Suppliers", "Delete Customers/Suppliers",
    /* 94 */ "Add Fixed Assets", "Edit Fixed Assets", "Delete Fixed Assets",
    /* 97 */ "View Petty Cash Vouchers", "View Petty Cash Payments", "Add Petty Cash Payments", "Edit Petty Cash Payments", "Delete Petty Cash Payments",
    /* 102 */ "View Petty Cash Re-imbursements", "Add Petty Cash Re-imbursements", "Edit Petty Cash Re-imbursements", "Delete Petty Cash Re-imbursements",
    /* 106 */ "Edit Journal Entries(Debit/Credit)", "Edit Journal Entries(Increase/Decrease)", "Edit Simplified Double Entries",
    /* 109 */ "Review/Approve Petty Cash Payments", "Review/Approve Petty Cash Re-imbursements",
    /* 111 */ "View Expense Vouchers", "Add Expense Vouchers", "Edit Expense Vouchers", "Delete Expense Vouchers",
    /* 115 */ "View Income Vouchers", "Add Income Vouchers", "Edit Income Vouchers", "Delete Income Vouchers",
    /* 119 */ "View Fund Management", "Add Fund Management", "Edit Fund Management", "Delete Fund Management"
);

$prsnid = $_SESSION['PRSN_ID'];
$orgID = $_SESSION['ORG_ID'];
$usrID = $_SESSION['USRID'];
$uName = $_SESSION['UNAME'];

$accbPrmSnsRstl = getAccbPgPrmssns($orgID);
$fnccurid = $accbPrmSnsRstl[0];
$fnccurnm = getPssblValNm($fnccurid);
$canview = ($accbPrmSnsRstl[1] >= 1) ? true : false;
$canViewChrt = ($accbPrmSnsRstl[2] >= 1) ? true : false;
$canViewJrnlBatch = ($accbPrmSnsRstl[3] >= 1) ? true : false;
$canViewPttyCash = ($accbPrmSnsRstl[4] >= 1) ? true : false;
$canViewTrnsSrch = ($accbPrmSnsRstl[5] >= 1) ? true : false;
$canViewFinStmnt = ($accbPrmSnsRstl[6] >= 1) ? true : false;
$canViewBdgts = ($accbPrmSnsRstl[7] >= 1) ? true : false;
$canViewTrnsTmplts = ($accbPrmSnsRstl[8] >= 1) ? true : false;
$canViewAccPrds = ($accbPrmSnsRstl[9] >= 1) ? true : false;
$canViewFxdAssts = ($accbPrmSnsRstl[10] >= 1) ? true : false;
$canViewPybls = ($accbPrmSnsRstl[11] >= 1) ? true : false;
$canViewRcvbls = ($accbPrmSnsRstl[12] >= 1) ? true : false;
$canViewPymnts = ($accbPrmSnsRstl[13] >= 1) ? true : false;
$canViewCstmrSpplr = ($accbPrmSnsRstl[14] >= 1) ? true : false;
$canViewTxCodes = ($accbPrmSnsRstl[15] >= 1) ? true : false;
$canViewDfltAccnts = ($accbPrmSnsRstl[16] >= 1) ? true : false;
$canViewRcncl = ($accbPrmSnsRstl[17] >= 1) ? true : false;
$canVwRcHstry = ($accbPrmSnsRstl[18] >= 1) ? true : false;
$canViewSQL = ($accbPrmSnsRstl[19] >= 1) ? true : false;
$canVwOnlySelf = ($accbPrmSnsRstl[20] >= 1) ? true : false;
$canViewExcRates = ($accbPrmSnsRstl[21] >= 1) ? true : false;
$canViewDocTmplts = ($accbPrmSnsRstl[22] >= 1) ? true : false;
$canViewJrnlBatchDetLines = ($accbPrmSnsRstl[23] >= 1) ? true : false;
$canViewJrnlBatchEditLines = ($accbPrmSnsRstl[24] >= 1) ? true : false;
$canViewJrnlBatchSmryLines = ($accbPrmSnsRstl[25] >= 1) ? true : false;
$canPostTrans = ($accbPrmSnsRstl[26] >= 1) ? true : false;

$vwtyp = "0";
$qstr = "";
$dsply = "";
$actyp = "";
$srchFor = "";
$srchIn = "All";
$PKeyID = -1;
$sortBy = "ID ASC";

$gnrlTrnsDteDMYHMS = getFrmtdDB_Date_time();
$gnrlTrnsDteYMDHMS = cnvrtDMYTmToYMDTm($gnrlTrnsDteDMYHMS);
$gnrlTrnsDteYMD = substr($gnrlTrnsDteYMDHMS, 0, 10);
$gnrlTrnsDteDMY = substr($gnrlTrnsDteDMYHMS, 0, 11);

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
                        <span style=\"text-decoration:none;\">Accounting Menu</span>
                </li>";
    if ($pgNo == 0) {
        $cntent .= " </ul></div>
                <div style=\"font-family: Tahoma, Arial, sans-serif;font-size: 1.3em;padding:10px 15px 15px 20px;border:1px solid #ccc;\">                    
                    <div style=\"padding:5px 30px 5px 10px;margin-bottom:2px;\">
                        <span style=\"font-family: georgia, times;font-size: 12px;font-style:italic;
                        font-weight:normal;\">This module helps you manage all financial matters in your organisation. The module has the ff areas:</span>
                    </div>
        <p>";
        $grpcntr = 0;
        for ($i = 0; $i < count($menuItems); $i++) {
            $iToUse = $i;
            if ($i == 2) {
                $i = 6;
            } else if ($i == 3) {
                $i = 2;
            } else if ($i == 6) {
                $i = 3;
            }
            $No = $i + 1;
            $menuLinks = "openATab('#allmodules', 'grp=$group&typ=$type&pg=$No&vtyp=0');";
            if ($i == 6) {
                $menuLinks = "openATab('#allmodules', 'grp=$group&typ=$type&pg=$No&vtyp=999');";
            }
            if ($i == 0 && $canViewChrt === false) {
                $i = $iToUse;
                continue;
            } else if ($i == 1 && $canViewJrnlBatch === false) {
                $i = $iToUse;
                continue;
            } else if ($i == 2 && $canViewPttyCash === false) {
                $i = $iToUse;
                continue;
            } else if ($i == 3 && $canViewTrnsSrch === false) {
                $i = $iToUse;
                continue;
            } else if ($i == 4 && $canViewFinStmnt === false) {
                $i = $iToUse;
                continue;
            } else if ($i == 5 && $canViewBdgts === false) {
                $i = $iToUse;
                continue;
            } else if ($i == 6 && $canViewTrnsTmplts === false) {
                $i = $iToUse;
                continue;
            } else if ($i == 7 && $canViewAccPrds === false) {
                $i = $iToUse;
                continue;
            } else if ($i == 8 && $canViewFxdAssts === false) {
                $i = $iToUse;
                continue;
            } else if ($i == 9 && $canViewPybls === false) {
                $i = $iToUse;
                continue;
            } else if ($i == 10 && $canViewRcvbls === false) {
                $i = $iToUse;
                continue;
            } else if ($i == 11 && $canViewPymnts === false) {
                $i = $iToUse;
                continue;
            } else if ($i == 12 && $canViewCstmrSpplr === false) {
                $i = $iToUse;
                continue;
            } else if ($i == 13 && $canViewTxCodes === false) {
                $i = $iToUse;
                continue;
            } else if ($i == 14 && $canViewDfltAccnts === false) {
                $i = $iToUse;
                continue;
            } else if ($i == 15 && $canViewExcRates === false) {
                $i = $iToUse;
                continue;
            } else if (($i == 16 || $i == 17) && $canViewDocTmplts === false) {
                $i = $iToUse;
                continue;
            } else if ($i == 18 && $canViewRcncl === false) {
                $i = $iToUse;
                continue;
            }
            if ($grpcntr == 0) {
                $cntent .= "<div class=\"row\">";
            }
            $cntent .= "<div class=\"col-md-3 colmd3special2\">
        <button type=\"button\" class=\"btn btn-default btn-lg btn-block modulesButton\" onclick=\"$menuLinks\">
            <img src=\"cmn_images/$menuImages[$i]\" style=\"margin:5px; padding-right: 1em; height:58px; width:auto; position: relative; vertical-align: middle;float:left;\">
            <span class=\"wordwrap2\">" . ($menuItems[$i]) . "</span>
        </button>
            </div>";
            if ($grpcntr == 3 || $iToUse == count($menuItems) - 1) {
                $cntent .= "</div>";
                $grpcntr = 0;
            } else {
                $grpcntr = $grpcntr + 1;
            }
            $i = $iToUse;
            $iToUse++;
        }
        $cntent .= "
      </p>
    </div>";
        echo $cntent;
        $updtSQL = "UPDATE accb.accb_trnsctn_batches SET avlbl_for_postng='1' WHERE batch_status ='0' and batch_source !='Manual' and avlbl_for_postng!='1'";
        execUpdtInsSQL($updtSQL);
    } else if ($pgNo == 1) {
        require "accnts_chrt.php";
    } else if ($pgNo == 2) {
        require "journal_entries.php";
    } else if ($pgNo == 3) {
        require "ptty_cash.php";
    } else if ($pgNo == 4) {
        require "trns_srch.php";
    } else if ($pgNo == 5) {
        require "fin_stmnts.php";
    } else if ($pgNo == 6) {
        require "bdgts.php";
    } else if ($pgNo == 7) {
        require "trns_tmplts.php";
    } else if ($pgNo == 70) {
        require "fund_mngmnt.php";
    } else if ($pgNo == 71) {
        require "loan_types.php";
    } else if ($pgNo == 72) {
        require "incm_expns_vchr.php";
    } else if ($pgNo == 8) {
        require "accnt_periods.php";
    } else if ($pgNo == 9) {
        require "assets_rgstr.php";
    } else if ($pgNo == 10) {
        require "pybls_invc.php";
    } else if ($pgNo == 11) {
        require "rcvbls_invc.php";
    } else if ($pgNo == 12) {
        require "invc_pymnts.php";
    } else if ($pgNo == 13) {
        require "business_prtnrs.php";
    } else if ($pgNo == 14) {
        require "tax_codes.php";
    } else if ($pgNo == 15) {
        require "dflt_accnts.php";
    } else if ($pgNo == 16) {
        require "exchng_rates.php";
    } else if ($pgNo == 17) {
        require "doc_tmplts.php";
    } else if ($pgNo == 18) {
        require "pymt_mthds.php";
    } else if ($pgNo == 19) {
        require "accnt_rcncl.php";
    } else if ($pgNo == 20) {
        require "accb_rpts.php";
    } else {
        restricted();
    }
} else {
    restricted();
}

function getAccbPgPrmssns_GLOBALS($orgid)
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
        . "sec.test_prmssns('View Transaction Templates', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "')+sec.test_prmssns('View Expense Vouchers', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "')+sec.test_prmssns('View Income Vouchers', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "')+sec.test_prmssns('View Fund Management', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
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

function getAccbPyblsPrmssns($orgid)
{
    global $ssnRoles;
    $mdlNm = "Accounting";
    $rslts = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
    $sqlStr = "Select sec.test_prmssns('Add Supplier Standard Payments', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
        . "sec.test_prmssns('Edit Supplier Standard Payments', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
        . "sec.test_prmssns('Delete Supplier Standard Payments', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
        . "sec.test_prmssns('Add Supplier Advance Payments', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
        . "sec.test_prmssns('Edit Supplier Advance Payments', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
        . "sec.test_prmssns('Delete Supplier Advance Payments', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
        . "sec.test_prmssns('Add Direct Refund from Supplier', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
        . "sec.test_prmssns('Edit Direct Refund from Supplier', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
        . "sec.test_prmssns('Delete Direct Refund from Supplier', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
        . "sec.test_prmssns('Add Supplier Credit Memo (InDirect Refund)', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
        . "sec.test_prmssns('Edit Supplier Credit Memo (InDirect Refund)', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
        . "sec.test_prmssns('Delete Supplier Credit Memo (InDirect Refund)', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
        . "sec.test_prmssns('Add Direct Topup for Supplier', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
        . "sec.test_prmssns('Edit Direct Topup for Supplier', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
        . "sec.test_prmssns('Delete Direct Topup for Supplier', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
        . "sec.test_prmssns('Add Supplier Debit Memo (InDirect Topup)', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
        . "sec.test_prmssns('Edit Supplier Debit Memo (InDirect Topup)', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
        . "sec.test_prmssns('Delete Supplier Debit Memo (InDirect Topup)', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
        . "sec.test_prmssns('Review/Approve Payables Documents', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
        . "sec.test_prmssns('Pay Payables Documents', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
        . "sec.test_prmssns('Cancel Payables Documents', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "')";
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
        /*  $rslts[21] = ((int) $row[21]);
          $rslts[22] = ((int) $row[22]);
          $rslts[23] = ((int) $row[23]);
          $rslts[24] = ((int) $row[24]);
          $rslts[25] = ((int) $row[25]);
          $rslts[26] = ((int) $row[26]); */
    }
    return $rslts;
}

function getAccbRcvblsPrmssns($orgid)
{
    global $ssnRoles;
    $mdlNm = "Accounting";
    $rslts = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
    $sqlStr = "Select sec.test_prmssns('Add Customer Standard Payments', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
        . "sec.test_prmssns('Edit Customer Standard Payments', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
        . "sec.test_prmssns('Delete Customer Standard Payments', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
        . "sec.test_prmssns('Add Customer Advance Payments', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
        . "sec.test_prmssns('Edit Customer Advance Payments', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
        . "sec.test_prmssns('Delete Customer Advance Payments', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
        . "sec.test_prmssns('Add Direct Refund to Customer', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
        . "sec.test_prmssns('Edit Direct Refund to Customer', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
        . "sec.test_prmssns('Delete Direct Refund to Customer', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
        . "sec.test_prmssns('Add Customer Credit Memo (InDirect Topup)', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
        . "sec.test_prmssns('Edit Customer Credit Memo (InDirect Topup)', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
        . "sec.test_prmssns('Delete Customer Credit Memo (InDirect Topup)', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
        . "sec.test_prmssns('Add Direct Topup from Customer', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
        . "sec.test_prmssns('Edit Direct Topup from Customer', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
        . "sec.test_prmssns('Delete Direct Topup from Customer', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
        . "sec.test_prmssns('Add Customer Debit Memo (InDirect Refund)', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
        . "sec.test_prmssns('Edit Customer Debit Memo (InDirect Refund)', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
        . "sec.test_prmssns('Delete Customer Debit Memo (InDirect Refund)', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
        . "sec.test_prmssns('Review/Approve Receivables Documents', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
        . "sec.test_prmssns('Pay Receivables Documents', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "'), "
        . "sec.test_prmssns('Cancel Receivables Documents', '" . $mdlNm . "','" . loc_db_escape_string($ssnRoles) . "')";
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
        /*  $rslts[21] = ((int) $row[21]);
          $rslts[22] = ((int) $row[22]);
          $rslts[23] = ((int) $row[23]);
          $rslts[24] = ((int) $row[24]);
          $rslts[25] = ((int) $row[25]);
          $rslts[26] = ((int) $row[26]); */
    }
    return $rslts;
}
