<?php

$menuItems = array(
    "My Financials", "Linked Customer Invoices", "Linked Supplier Invoices",
    "Quick Payments & Pay Setups", "Pay Item Sets", "Person Sets", "All Pay / Bill Runs", "Payment Transactions",
    "GL Interface Table", "Pay Items", "Global Values", "PAYE Tax Rates", "Loan and Payment Requests",
    "Fund Management", "Loan, Payment and Investment Types", "Standard Reports"
);
$menuImages = array(
    "bills.png", "invcBill.png", "invoice1.png", "pay.png",
    "wallet.png", "staffs.png", "bulkPay.png", "search.png",
    "GL-256.png", "chcklst4.png", "world_48.png", "edit32.png",
    "loans_prdt.png", "investments_prdt.png", "services_prdt.jpg", "report-icon-png.png"
);
$mdlNm = "Internal Payments";
$ModuleName = $mdlNm;

$dfltPrvldgs = array(
    "View Internal Payments",
    /* 1 */ "View Manual Payments", "View Pay Item Sets", "View Person Sets",
    /* 4 */ "View Mass Pay Runs", "View Payment Transactions", "View GL Interface Table",
    /* 7 */ "View Record History", "View SQL",
    /* 9 */ "Add Manual Payments", "Reverse Manual Payments",
    /* 11 */ "Add Pay Item Sets", "Edit Pay Item Sets", "Delete Pay Item Sets",
    /* 14 */ "Add Person Sets", "Edit Person Sets", "Delete Person Sets",
    /* 17 */ "Add Mass Pay", "Edit Mass Pay", "Delete Mass Pay", "Send Mass Pay Transactions to Actual GL",
    /* 21 */ "Send All Transactions to Actual GL", "Run Mass Pay",
    /* 23 */ "Rollback Mass Pay Run", "Send Selected Transactions to Actual GL",
    /* 25 */ "View Pay Items", "Add Pay Items", "Edit Pay Items", "Delete Pay Items",
    /* 29 */ "View Person Pay Item Assignments", "View Banks", "Add Pay Item Assignments",
    /* 32 */ "Edit Pay Item Assignments", "Delete Pay Item Assignments",
    /* 34 */ "View Global Values", "Add Global Values", "Edit Global Values", "Delete Global Values",
    /* 38 */ "View other User's Mass Pays", "View Loan Requests", "View Fund Management", "View Loan and Payment Types",
    /* 42 */ "Add Loan and Payment Requests", "Edit Loan and Payment Requests", "Delete Loan and Payment Requests",
    /* 45 */ "Add Fund Management", "Edit Fund Management", "Delete Fund Management",
    /* 48 */ "Add Loan and Payment Types", "Edit Loan and Payment Types", "Delete Loan and Payment Types",
    /* 51 */ "View PAYE Tax Rates"
);

$canview = test_prmssns($dfltPrvldgs[0], $mdlNm) || test_prmssns("View Internal Payments", "Self Service");
$canVwRcHstry = test_prmssns($dfltPrvldgs[7], $mdlNm);
$canVwQckPay = test_prmssns($dfltPrvldgs[1], $mdlNm);

$prsnid = $_SESSION['PRSN_ID'];
$orgID = $_SESSION['ORG_ID'];
$gnrlTrnsDteDMYHMS = getFrmtdDB_Date_time();
$gnrlTrnsDteYMDHMS = cnvrtDMYTmToYMDTm($gnrlTrnsDteDMYHMS);
$gnrlTrnsDteYMD = substr($gnrlTrnsDteYMDHMS, 0, 10);
$gnrlTrnsDteDMY = substr($gnrlTrnsDteDMYHMS, 0, 11);

$fnccurid = getOrgFuncCurID($orgID);
$fnccurnm = getPssblValNm($fnccurid);
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
$srcMdl = isset($_POST['srcMdl']) ? $_POST['srcMdl'] : "pay";
$cntent = "<div>
		<ul class=\"breadcrumb\" style=\"$breadCrmbBckclr\">
                    <li onclick=\"openATab('#home', 'grp=40&typ=1');\">
                        <i class=\"fa fa-home\" aria-hidden=\"true\"></i>
                        <span style=\"text-decoration:none;\">Home</span>
                            <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i>
                        </span>
                    </li>
                    <li onclick=\"openATab('#allmodules', 'grp=40&typ=5');\">
                            <span style=\"text-decoration:none;\">All Modules&nbsp;</span>
                    </li>";

if ($lgn_num > 0 && $canview === true) {
    if ($srcMdl == "eVote") {
        $cntent .= "<li onclick=\"openATab('#allmodules', 'grp=19&typ=10');\">
                            <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                            <span style=\"text-decoration:none;\">e-Voting Menu</span>
                    </li>";
    } else {
        $customMdlNm = getEnbldPssblValDesc("Internal Payments", getLovID("Customized Module Names"));
        if (trim($customMdlNm) == "") {
            $customMdlNm = "Bills/Payments";
        }
        $cntent .= "
					<li onclick=\"openATab('#allmodules', 'grp=$group&typ=$type');\">
                                                <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
						<span style=\"text-decoration:none;\">$customMdlNm Menu</span>
					</li>";
    }
    if ($pgNo == 0) {
        $cntent .= "</ul>
                    </div>" . "<div style=\"font-family: Tahoma, Arial, sans-serif;font-size: 1.3em;
                    padding:10px 15px 15px 20px;border:1px solid #ccc;\">                    
      <div style=\"padding:5px 30px 5px 10px;margin-bottom:2px;\">
                    <span style=\"font-family: georgia, times;font-size: 12px;font-style:italic;
                    font-weight:normal;\">This is where Internal Payments and Bills in the Organisation are Captured and Managed. 
      For Customers, Suppliers and Registered Firms in the Organisation, your Invoices can be seen from here also. The module has the ff areas:  </span>";
        if ($usrName == "admin") {
            $cntent .= "<button type = \"button\" class=\"btn btn-default btn-sm\" style=\"height:30px;\" onclick=\"openATab('#allmodules', 'grp=$group&typ=$type&pg=0&vtyp=10');\" data-toggle=\"tooltip\" title=\"Reload Module\">
                            <img src=\"cmn_images/refresh.bmp\" style=\"padding-right: 2px; height:17px; width:auto; position: relative; vertical-align: middle;\">
                            Refresh
                        </button>";
        }
        $cntent .= "</div>
      <p>";
        $grpcntr = 0;

        for ($i = 0; $i < count($menuItems); $i++) {
            $No = $i + 1;
            $sndVwTyp = 0;
            if ($i == 0) {
            } else if ($i == 1) {
                $sndVwTyp = 2;
                continue;
            } else if ($i == 2) {
                $sndVwTyp = 4;
                continue;
            } else if ($i == 3 && test_prmssns($dfltPrvldgs[1], $mdlNm) == FALSE) {
                continue;
            } else if ($i == 4 && test_prmssns($dfltPrvldgs[2], $mdlNm) == FALSE) {
                continue;
            } else if ($i == 5 && test_prmssns($dfltPrvldgs[3], $mdlNm) == FALSE) {
                continue;
            } else if ($i == 6 && test_prmssns($dfltPrvldgs[4], $mdlNm) == FALSE) {
                continue;
            } else if ($i == 7 && test_prmssns($dfltPrvldgs[5], $mdlNm) == FALSE) {
                continue;
            } else if ($i == 8 && test_prmssns($dfltPrvldgs[6], $mdlNm) == FALSE) {
                continue;
            } else if ($i == 9 && test_prmssns($dfltPrvldgs[25], $mdlNm) == FALSE) {
                continue;
            } else if ($i == 10 && test_prmssns($dfltPrvldgs[34], $mdlNm) == FALSE) {
                continue;
            } else if ($i == 11 && test_prmssns($dfltPrvldgs[51], $mdlNm) == FALSE) {
                continue;
            }
            if ($grpcntr == 0) {
                $cntent .= "<div class=\"row\">";
            }
            $cntent .= "<div class=\"col-md-3 colmd3special2\">
        <button type=\"button\" class=\"btn btn-default btn-lg btn-block modulesButton\" onclick=\"openATab('#allmodules', 'grp=$group&typ=$type&pg=$No&vtyp=$sndVwTyp');\">
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
        if ($vwtyp >= 10) {
            createRqrdPayItems();
            createPayDfltSets();
        }
        session_write_close();
        execUpdtInsSQL("UPDATE pay.pay_balsitm_bals SET bals_amount = round(bals_amount , 2) WHERE (bals_amount - round(bals_amount , 2)) != 0");
    } else if ($pgNo == 1 || $pgNo == 2 || $pgNo == 3) {
        //echo "RedirectTo:" . $app_url . "self";
        require 'my_bills_slips.php';
    } else if ($pgNo == 4) {
        require "quick_pymnts.php";
    } else if ($pgNo == 5) {
        require "pay_itm_sets.php";
    } else if ($pgNo == 6) {
        require "prsn_sets.php";
    } else if ($pgNo == 7) {
        require "bulk_pymnts.php";
    } else if ($pgNo == 8) {
        require "pymnt_trns.php";
    } else if ($pgNo == 9) {
        require "pymnts_gl_intrfc.php";
    } else if ($pgNo == 10) {
        require "pay_items.php";
    } else if ($pgNo == 11) {
        require "global_values.php";
    } else if ($pgNo == 12) {
        require "paye_tax_rates.php";
    } else if ($pgNo == 13) {
        require "loan_requests.php";
    } else if ($pgNo == 14) {
        require $fldrPrfx . "app_code/accb/accb_funcs.php";
        require "fund_mngmnt.php";
    } else if ($pgNo == 15) {
        require $fldrPrfx . "app_code/accb/accb_funcs.php";
        require "loan_types.php";
    } else if ($pgNo == 16) {
        require "pay_rpts.php";
    } else {
        restricted();
    }
} else {
    restricted();
}

function get_MyPyRnsDt($pyReqID, $mspyID, $prsnID)
{
    $sqlStr = "SELECT tbl1.* FROM 
        (SELECT pymnt_req_id, payer_person_id, mass_pay_hdr_id, pymnt_req_hdr_id, 
       pymnt_trns_id, pay_item_id, org.get_payitm_nm(pay_item_id) itmNm, 
       a.amount_paid, 
       to_char(to_timestamp(a.payment_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') payment_date, 
       line_description,
       org.get_payitm_effct(pay_item_id) effct,
       org.get_payitm_mintyp(pay_item_id) mintyp
  FROM self.self_prsn_intrnl_pymnts a
  WHERE (a.pymnt_req_id = " . $pyReqID . " and payer_person_id = " . $prsnID . ") "
        . " UNION "
        . "SELECT -1, a.person_id, a.mass_pay_id, -1, 
                a.pay_trns_id, a.item_id, org.get_payitm_nm(a.item_id) itmNm, 
                a.amount_paid, 
                to_char(to_timestamp(a.paymnt_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') paymnt_date, 
                a.pymnt_desc,
       org.get_payitm_effct(a.item_id) effct,
       org.get_payitm_mintyp(a.item_id) mintyp
FROM pay.pay_itm_trnsctns a 
WHERE(a.mass_pay_id = " . $mspyID . " and person_id = " . $prsnID . " and a.pymnt_vldty_status='VALID' and a.src_py_trns_id<=0)) tbl1 "
        . " ORDER BY tbl1.pymnt_trns_id ASC";
    //echo $sqlStr;
    $result = executeSQLNoParams($sqlStr);
    return $result;
}

function get_CumltiveBals($prsnID)
{
    $sqlStr = "SELECT DISTINCT -1, a.person_id, -1, -1, -1, a.bals_itm_id, b.item_code_name itmNm, "
        . "round(a.bals_amount, 2) amount_paid, "
        . "to_char(to_timestamp(a.bals_date|| ' 12:00:00','YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') balsdte, "
        . "b.item_code_name || (CASE WHEN b.item_code_name ilike '%Principal Balance%' THEN
         pay.get_tk_tied_to_frm_bls (a.person_id, b.item_code_name,' Principal Balance') ELSE '' END) item_code_name,"
        . "b.effct_on_org_debt, "
        . "b.item_maj_type,a.bals_date,b.pay_run_priority,b.item_value_uom " .
        "FROM pay.pay_balsitm_bals a, org.org_pay_items b " .
        "WHERE((a.person_id = " . $prsnID . ") and (b.item_maj_type='Balance Item' "
        . "and b.balance_type='Cumulative' and a.bals_itm_id = b.item_id)"
        . " and (b.is_enabled = '1') and a.bals_date = (SELECT MAX(c.bals_date) FROM pay.pay_balsitm_bals c "
        . "WHERE c.person_id = " . $prsnID . " and a.bals_itm_id = c.bals_itm_id) "
        . "and (CASE WHEN a.bals_amount!=0 THEN 1 ELSE 0 END)=1) "
        . "ORDER BY a.bals_date DESC, b.item_code_name, b.pay_run_priority";
    /* WHEN b.item_code_name ilike '%2016%' "
      . "or b.item_code_name ilike '%2015%' "
      . "or b.item_code_name ilike '%2014%' THEN 1 "
      . " */
      //echo $sqlStr;
    $result = executeSQLNoParams($sqlStr);
    return $result;
}

function getBatchItmTypCnt($pyReqID, $mspyID, $prsnID)
{
    $sqlStr = "Select distinct org.get_payitm_mintyp(a.item_id) from pay.pay_itm_trnsctns a 
     WHERE(a.mass_pay_id = " . $mspyID . " and a.person_id = " . $prsnID . ")"
        . " UNION "
        . " Select distinct org.get_payitm_mintyp(a.pay_item_id) from self.self_prsn_intrnl_pymnts a 
     WHERE(a.pymnt_req_hdr_id = " . $pyReqID . " and a.payer_person_id = " . $prsnID . " and a.mass_pay_hdr_id<=0)";
    $result = executeSQLNoParams($sqlStr);
    return loc_db_num_rows($result);
}

function getBatchItmTypCnt1($mspyID)
{
    $sqlStr = "Select distinct org.get_payitm_mintyp(a.item_id) from pay.pay_value_sets_det a 
     WHERE(a.mass_pay_id = " . $mspyID . ")";
    $result = executeSQLNoParams($sqlStr);
    return loc_db_num_rows($result);
}

$fnlColorAmntDffrnc = 0;

function getBatchNetAmnt($itmTypCnt, $itmTyp, $itmNm, $effctOnOrgDbt, $amnt, &$brghtTotal, &$prpsdTtlSpnColor)
{
    /* Items Net Effect on Person's Organisational Debt
     * if(same itemtype in batch then + throughout)
     * Dues/Bills/Charges - (red) - increase
     * Dues/Bills/Charges Payments - (green) - decrease
     * 
     * Earnings - (green) - decrease
     * Payroll Deductions - (red) - increase
     * Payroll Staff Liability Balance - green - decrease
     * Employer Charges (None) (black)
     * Purely Informational (None) (black)
     * */
    global $fnlColorAmntDffrnc;

    $spnColor = "black";
    $mltplr = "+";
    /* if ($effctOnOrgDbt == "None") {
      $spnColor = "black";
      } else */
    if ($effctOnOrgDbt == "Increase") {
        $spnColor = "red";
        $fnlColorAmntDffrnc = $fnlColorAmntDffrnc - $amnt;
        if ($itmTyp == "Bills/Charges") {
            $spnColor = "red";
            $brghtTotal = $brghtTotal + $amnt;
        } else {
            if ($itmTypCnt > 1 || $itmTyp == "Balance Item") {
                $mltplr = "-";
                $brghtTotal = $brghtTotal - $amnt;
            } else {
                $brghtTotal = $brghtTotal + $amnt;
            }
        }
    } else if ($effctOnOrgDbt == "Decrease") {
        $spnColor = "green";
        $fnlColorAmntDffrnc = $fnlColorAmntDffrnc + $amnt;
        $brghtTotal = $brghtTotal + $amnt;
    } else {
        if ($itmTyp == "Bills/Charges") {
            $spnColor = "red";
            $fnlColorAmntDffrnc = $fnlColorAmntDffrnc - $amnt;
            $brghtTotal = $brghtTotal + $amnt;
        } else if ($itmTyp == "Deductions") {
            if (strpos($itmNm, "(Payment)") !== FALSE || $itmNm == "Advance Payments Amount Kept") {
                $spnColor = "green";
                $fnlColorAmntDffrnc = $fnlColorAmntDffrnc + $amnt;
            } else {
                $spnColor = "red";
                $fnlColorAmntDffrnc = $fnlColorAmntDffrnc - $amnt;
            }
            if ($itmTypCnt > 1) {
                $mltplr = "-";
                $brghtTotal = $brghtTotal - $amnt;
            } else {
                $brghtTotal = $brghtTotal + $amnt;
            }
        } else if ($itmTyp == "Earnings") {
            $spnColor = "green";
            $fnlColorAmntDffrnc = $fnlColorAmntDffrnc + $amnt;
            if ($itmNm == "Advance Payments Amount Applied") {
                if ($itmTypCnt > 1) {
                    $mltplr = "-";
                    $brghtTotal = $brghtTotal - $amnt;
                } else {
                    $brghtTotal = $brghtTotal + $amnt;
                }
            } else {
                $brghtTotal = $brghtTotal + $amnt;
            }
        } else {
            $spnColor = "black";
        }
    }
    if ($brghtTotal >= 0 && $fnlColorAmntDffrnc >= 0) {
        $prpsdTtlSpnColor = "green";
    } else {
        $prpsdTtlSpnColor = "red";
    }
    if ($mltplr == "-") {
        return "<span style=\"color:$spnColor;\">" . number_format(round((float) (-1 * $amnt), 2), 2) . "</span>";
    } else {
        return "<span style=\"color:$spnColor;\">" . number_format($amnt, 2) . "</span>";
    }
}

function get_MyPyHdrDet($pyReqID, $mspyID, $prsnID)
{
    $strSql = "SELECT tbl1.* FROM (
        SELECT -1 pay_red_hdr_id, -1 mass_pay_hdr_id, 
        'CUMULATIVE BALANCES' mspy_name, 
        'ALL-TIME OUTSTANDING BALANCES', 
        -1 wkfMsgID,
        'Completed Succesfully' status, 
        to_char(now(),'DD-Mon-YYYY HH24:MI:SS') pay_date, 
        '' attachments "
        . " UNION "
        . " SELECT -1 pay_red_hdr_id, a.mass_pay_id, 
        REPLACE((CASE WHEN a.mass_pay_id<=0 THEN 'Manual/Direct Payment' ELSE a.mass_pay_name END),'Quick Pay Run','Quick Run of Items') mspy_name, 
        REPLACE((CASE WHEN a.mass_pay_desc!='' THEN a.mass_pay_desc ELSE (CASE WHEN a.mass_pay_id<=0 THEN 'Manual/Direct Payment' ELSE a.mass_pay_name END) END),'Quick Pay Run','Quick Run of Items'), 
        -1 wkfMsgID,
        CASE WHEN a.run_status='1' AND a.sent_to_gl='1' THEN 'Completed Succesfully' ELSE 'Incomplete' END status, 
        to_char(to_timestamp(a.mass_pay_trns_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') pay_date, '' attachments 
      FROM pay.pay_mass_pay_run_hdr a 
      WHERE (((Select count(1) from pay.pay_itm_trnsctns z where z.person_id = " . $prsnID .
        " and z.mass_pay_id = a.mass_pay_id and z.pymnt_vldty_status='VALID' and z.src_py_trns_id<=0)>=1) AND (org_id = " . $_SESSION['ORG_ID'] .
        ")) "
        . " UNION "
        . "SELECT pymnt_req_hdr_id, mass_pay_hdr_id, ''||pymnt_req_hdr_id, pymnt_req_hdr_desc, 
       wkf_msg_id, status, to_char(to_timestamp(a.payment_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS'), attachments
  FROM self.self_prsn_intrnl_pymnts_hdr a 
  WHERE (((Select count(1) from self.self_prsn_intrnl_pymnts z where z.payer_person_id = " . $prsnID .
        " and z.pymnt_req_hdr_id = a.pymnt_req_hdr_id)>=1))) tbl1 "
        . "WHERE tbl1.pay_red_hdr_id=$pyReqID and tbl1.mass_pay_hdr_id =$mspyID ";
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_MyPyRnsTblr($searchFor, $searchIn, $offset, $limit_size, $prsnID, $recCnt = 1)
{
    if ($prsnID <= 0) {
        $recCnt = 0;
    }
    $wherecls = "";
    $wherecls1 = "";
    if ($searchIn === "Name/Number") {
        $wherecls = "(a.mass_pay_name ilike '" .
            loc_db_escape_string($searchFor) . "' or a.mass_pay_desc ilike '" .
            loc_db_escape_string($searchFor) . "') and ";
        $wherecls1 = "('' || a.pymnt_req_hdr_id ilike '" .
            loc_db_escape_string($searchFor) . "' or a.pymnt_req_hdr_desc ilike '" .
            loc_db_escape_string($searchFor) . "') and ";
    }
    $strSql = "SELECT tbl1.* FROM (
        SELECT -1 pay_red_hdr_id, -1, 
        'CUMULATIVE BALANCES' mspy_name, 
        'ALL TIME ITEM BALANCES', 
        -1 wkfMsgID,
        'Completed Succesfully' status, 
        to_char(now(),'YYYY-MM-DD HH24:MI:SS') pay_date, 
        '' attachments,-1,-1,'1','','', '0' "
        . " UNION "
        . " SELECT -1 pay_red_hdr_id, 
                a.mass_pay_id, 
        CASE WHEN a.mass_pay_id<=0 THEN 'Manual/Direct Payment' ELSE a.mass_pay_name END mspy_name, 
        a.mass_pay_desc, 
        -1 wkfMsgID,
        CASE WHEN a.run_status='1' AND a.sent_to_gl='1' THEN 'Completed Succesfully' ELSE 'Incomplete' END status, 
        a.mass_pay_trns_date pay_date, 
        '' attachments,
        a.prs_st_id,
        a.itm_st_id,
        pay.get_prs_st_name(a.prs_st_id),
        pay.get_itm_st_name(a.itm_st_id),
        a.run_status,
        a.sent_to_gl
      FROM pay.pay_mass_pay_run_hdr a 
      WHERE ($wherecls(((Select count(1) from pay.pay_itm_trnsctns z where z.person_id = " . $prsnID .
        " and z.mass_pay_id = a.mass_pay_id and z.pymnt_vldty_status='VALID' and z.src_py_trns_id<=0) >= " . $recCnt .
        ") or (a.allwd_group_type = 'Single Person' and a.allwd_group_value='' || " . $prsnID . ")) AND (org_id = " . $_SESSION['ORG_ID'] . ")) "
        . " UNION "
        . "SELECT pymnt_req_hdr_id, mass_pay_hdr_id, ''||pymnt_req_hdr_id, pymnt_req_hdr_desc, 
       wkf_msg_id, status, payment_date, attachments,-1,-1,'','', CASE WHEN mass_pay_hdr_id>0 THEN '1' ELSE '0' END,COALESCE((select z.sent_to_gl from pay.pay_mass_pay_run_hdr z where z.mass_pay_id=a.mass_pay_hdr_id),'0')
  FROM self.self_prsn_intrnl_pymnts_hdr a 
  WHERE ($wherecls1((Select count(1) from self.self_prsn_intrnl_pymnts z where z.payer_person_id = " . $prsnID .
        " and z.pymnt_req_hdr_id = a.pymnt_req_hdr_id)>=" . $recCnt . ") and a.mass_pay_hdr_id<=0)) tbl1 "
        . "ORDER BY tbl1.pay_date DESC LIMIT " . $limit_size .
        " OFFSET " . abs($offset * $limit_size);
    //echo $strSql;
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_MyPyRnsTtl($searchFor, $searchIn, $prsnID, $recCnt = 1)
{
    if ($prsnID <= 0) {
        $recCnt = 0;
    }
    $wherecls = "";
    $wherecls1 = "";
    //"Message Header", "Message Date", "Message Status", "Source App", "Source Module"
    if ($searchIn === "Name/Number") {
        $wherecls = "(a.mass_pay_name ilike '" .
            loc_db_escape_string($searchFor) . "' or a.mass_pay_desc ilike '" .
            loc_db_escape_string($searchFor) . "') and ";
        $wherecls1 = "('' || a.pymnt_req_hdr_id ilike '" .
            loc_db_escape_string($searchFor) . "' or a.pymnt_req_hdr_desc ilike '" .
            loc_db_escape_string($searchFor) . "') and ";
    }

    $strSql = "SELECT count(1) FROM (
        SELECT -1 pay_red_hdr_id, -1, 
        'CUMULATIVE BALANCES' mspy_name, 
        'ALL TIME ITEM BALANCES', 
        -1 wkfMsgID,
        'Completed Succesfully' status, 
        to_char(now(),'YYYY-MM-DD HH24:MI:SS') pay_date, 
        '' attachments,-1,-1,'1','','', '0' "
        . " UNION "
        . " SELECT -1 pay_red_hdr_id, 
                a.mass_pay_id, 
        CASE WHEN a.mass_pay_id<=0 THEN 'Manual/Direct Payment' ELSE a.mass_pay_name END mspy_name, 
        a.mass_pay_desc, 
        -1 wkfMsgID,
        CASE WHEN a.run_status='1' AND a.sent_to_gl='1' THEN 'Completed Succesfully' ELSE 'Incomplete' END status, 
        a.mass_pay_trns_date pay_date, 
        '' attachments,
        a.prs_st_id,
        a.itm_st_id,
        pay.get_prs_st_name(a.prs_st_id),
        pay.get_itm_st_name(a.itm_st_id),
        a.run_status,
        a.sent_to_gl
      FROM pay.pay_mass_pay_run_hdr a 
      WHERE ($wherecls(((Select count(1) from pay.pay_itm_trnsctns z where z.person_id = " . $prsnID .
        " and z.mass_pay_id = a.mass_pay_id and z.pymnt_vldty_status='VALID' and z.src_py_trns_id<=0) >= " . $recCnt .
        ") or (a.allwd_group_type = 'Single Person' and a.allwd_group_value='' || " . $prsnID . ")) AND (org_id = " . $_SESSION['ORG_ID'] . ")) "
        . " UNION "
        . "SELECT pymnt_req_hdr_id, mass_pay_hdr_id, ''||pymnt_req_hdr_id, pymnt_req_hdr_desc, 
       wkf_msg_id, status, payment_date, attachments,-1,-1,'','', CASE WHEN mass_pay_hdr_id>0 THEN '1' ELSE '0' END,
       COALESCE((select z.sent_to_gl from pay.pay_mass_pay_run_hdr z where z.mass_pay_id=a.mass_pay_hdr_id),'0')
  FROM self.self_prsn_intrnl_pymnts_hdr a 
  WHERE ($wherecls1((Select count(1) from self.self_prsn_intrnl_pymnts z where z.payer_person_id = " . $prsnID .
        " and z.pymnt_req_hdr_id = a.pymnt_req_hdr_id)>=" . $recCnt . ") and a.mass_pay_hdr_id<=0)) tbl1 ";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return 0;
}

function get_Basic_QuickPy($searchWord, $searchIn, $offset, $limit_size, $prsnID)
{
    $strSql = "";
    $whereCls = "";
    if ($searchIn == "Mass Pay Run Name") {
        $whereCls = "(a.mass_pay_name ilike '" . loc_db_escape_string($searchWord) .
            "' or a.mass_pay_id<=0)and ";
    } else if ($searchIn == "Mass Pay Run Description") {
        $whereCls = "(a.mass_pay_desc ilike '" . loc_db_escape_string($searchWord) .
            "' or a.mass_pay_id<=0) and ";
    }

    $strSql = "SELECT a.mass_pay_id, CASE WHEN a.mass_pay_id<=0 THEN 'Manual/Direct Payment' ELSE a.mass_pay_name END, a.mass_pay_desc, a.run_status, 
        to_char(to_timestamp(a.mass_pay_trns_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS')
      , a.prs_st_id, a.itm_st_id, a.sent_to_gl, to_char(to_timestamp(a.gl_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') 
      FROM pay.pay_mass_pay_run_hdr a WHERE (($whereCls(Select count(1) from pay.pay_itm_trnsctns z where z.person_id = " . $prsnID .
        " and z.mass_pay_id = a.mass_pay_id)>=1) AND (org_id = " . $_SESSION['ORG_ID'] .
        ") AND (prs_st_id<=0)) ORDER BY a.mass_pay_id DESC LIMIT " . $limit_size .
        " OFFSET " . abs($offset * $limit_size);
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_One_MsPyDet($offset, $limit_size, $mspyid)
{
    $strSql = "SELECT a.pay_trns_id, a.person_id, a.item_id, a.amount_paid, 
to_char(to_timestamp(a.paymnt_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS')
, a.paymnt_source, a.pay_trns_type, a.pymnt_desc, -1, a.crncy_id, c.local_id_no, trim(c.title || ' ' || c.sur_name || 
', ' || c.first_name || ' ' || c.other_names) fullname, b.item_code_name, a.pymnt_vldty_status, gst.get_pssbl_val(a.crncy_id) cur 
FROM (pay.pay_itm_trnsctns a LEFT OUTER JOIN org.org_pay_items b ON a.item_id = b.item_id) 
LEFT OUTER JOIN prs.prsn_names_nos c on a.person_id = c.person_id 
WHERE(a.mass_pay_id = " . $mspyid . ") ORDER BY a.pay_trns_id LIMIT " . $limit_size .
        " OFFSET " . abs($offset * $limit_size);

    $result = executeSQLNoParams($strSql);
    /* @var $result type */
    return $result;
}

function get_One_MsPyDet2($searchWord, $searchIn, $offset, $limit_size, $mspyid)
{
    $whereCls = "";
    if ($searchIn == "Person Name/ID No.") {
        $whereCls = "(c.local_id_no ilike '" . loc_db_escape_string($searchWord) .
            "' or trim(c.title || ' ' || c.sur_name || 
', ' || c.first_name || ' ' || c.other_names) ilike '" . loc_db_escape_string($searchWord) .
            "') and ";
    } else if ($searchIn == "Item Name") {
        $whereCls = "(b.item_code_name ilike '" . loc_db_escape_string($searchWord) .
            "') and ";
    }

    $strSql = "SELECT a.pay_trns_id, a.person_id, a.item_id, a.amount_paid, 
to_char(to_timestamp(a.paymnt_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS')
, a.paymnt_source, a.pay_trns_type, a.pymnt_desc, -1, a.crncy_id, c.local_id_no, trim(c.title || ' ' || c.sur_name || 
', ' || c.first_name || ' ' || c.other_names) fullname, b.item_code_name,
a.pymnt_vldty_status, (CASE WHEN coalesce(b.item_value_uom,'')='Money' THEN gst.get_pssbl_val(a.crncy_id) ELSE substr(b.item_value_uom,1,3) END) cur, b.item_min_type,
       b.effct_on_org_debt
FROM (pay.pay_itm_trnsctns a LEFT OUTER JOIN org.org_pay_items b ON a.item_id = b.item_id) 
LEFT OUTER JOIN prs.prsn_names_nos c on a.person_id = c.person_id 
WHERE " . $whereCls . "(a.mass_pay_id = " . $mspyid . ") ORDER BY c.local_id_no, b.pay_run_priority, a.pay_trns_id LIMIT " . $limit_size .
        " OFFSET " . abs($offset * $limit_size);
    //echo $strSql;
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_One_MsPyDetTtl2($searchWord, $searchIn, $mspyid)
{
    $whereCls = "";
    if ($searchIn == "Person Name/ID No.") {
        $whereCls = "(c.local_id_no ilike '" . loc_db_escape_string($searchWord) .
            "' or trim(c.title || ' ' || c.sur_name || 
', ' || c.first_name || ' ' || c.other_names) ilike '" . loc_db_escape_string($searchWord) .
            "') and ";
    } else if ($searchIn == "Item Name") {
        $whereCls = "(b.item_code_name ilike '" . loc_db_escape_string($searchWord) .
            "') and ";
    }

    $strSql = "SELECT count(1)  
FROM (pay.pay_itm_trnsctns a LEFT OUTER JOIN org.org_pay_items b ON a.item_id = b.item_id) 
LEFT OUTER JOIN prs.prsn_names_nos c on a.person_id = c.person_id 
WHERE " . $whereCls . "(a.mass_pay_id = " . $mspyid . ")";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return 0;
}

function get_RcvblsDocHdr2($searchWord, $searchIn, $offset, $limit_size, $orgID, $shwUnpstdOnly, $cstmrID = -1)
{
    $strSql = "";
    $whrcls = "";
    /* Document Number
      Document Description
      Document Classification
      Customer Name
      Customer's Doc. Number
      Source Doc Number
      Approval Status
      Created By
      Currency */
    $unpstdCls = "";
    if ($shwUnpstdOnly) {
        $unpstdCls = " AND (round(a.invoice_amount-a.amnt_paid,2)>0 or a.approval_status IN ('Not Validated','Validated','Reviewed'))";
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
    $whrcls .= " and (a.customer_id IN ($cstmrID))";
    $strSql = "SELECT rcvbls_invc_hdr_id, rcvbls_invc_number, 
rcvbls_invc_type, round(a.invoice_amount-a.amnt_paid,2),
 a.approval_status, a.src_doc_hdr_id, a.src_doc_type
        FROM accb.accb_rcvbls_invc_hdr a 
        WHERE((a.org_id = " . $orgID . ")" . $whrcls . $unpstdCls .
        ") ORDER BY rcvbls_invc_hdr_id DESC LIMIT " . $limit_size .
        " OFFSET " . abs($offset * $limit_size);

    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_RcvblsDocHdrTtl2($searchWord, $searchIn, $orgID, $shwUnpstdOnly, $cstmrID = -1)
{
    $strSql = "";
    $whrcls = "";
    /* Document Number
      Document Description
      Document Classification
      Customer Name
      Customer's Doc. Number
      Source Doc Number
      Approval Status
      Created By
      Currency */
    $unpstdCls = "";
    if ($shwUnpstdOnly) {
        $unpstdCls = " AND (round(a.invoice_amount-a.amnt_paid,2)>0 or a.approval_status IN ('Not Validated','Validated','Reviewed'))";
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
    $whrcls .= " and (a.customer_id IN ($cstmrID))";
    $strSql = "SELECT count(1) 
        FROM accb.accb_rcvbls_invc_hdr a 
        WHERE((a.org_id = " . $orgID . ")" . $whrcls . $unpstdCls .
        ")";

    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return 0;
}

function get_One_RcvblsDocHdr($hdrID)
{
    $strSql = "";

    $strSql = "SELECT rcvbls_invc_hdr_id, 
        to_char(to_timestamp(rcvbls_invc_date,'YYYY-MM-DD'),'DD-Mon-YYYY'), 
       created_by, 
       sec.get_usr_name(a.created_by), 
       rcvbls_invc_number, 
       rcvbls_invc_type, 
       comments_desc, 
       src_doc_hdr_id, 
       a.src_doc_type, 
       customer_id, 
       scm.get_cstmr_splr_name(a.customer_id),
       customer_site_id, 
       scm.get_cstmr_splr_site_name(a.customer_site_id), 
       approval_status, 
       next_aproval_action, 
       invoice_amount, 
       payment_terms, 
       pymny_method_id, 
       accb.get_pymnt_mthd_name(a.pymny_method_id), 
       amnt_paid, 
       gl_batch_id, 
       accb.get_gl_batch_name(a.gl_batch_id),
       cstmrs_doc_num, 
       doc_tmplt_clsfctn, 
       invc_curr_id, 
       gst.get_pssbl_val(a.invc_curr_id), 
       scm.get_src_doc_num(a.src_doc_hdr_id, a.src_doc_type),
        event_rgstr_id, 
        evnt_cost_category, 
        event_doc_type,
        a.invc_amnt_appld_elswhr,
        CASE WHEN a.event_doc_type='Attendance Register' and a.event_rgstr_id>0 THEN 
            (select z.recs_hdr_name from attn.attn_attendance_recs_hdr z where z.recs_hdr_id=a.event_rgstr_id)
            WHEN a.event_doc_type='Production Process Run' and a.event_rgstr_id>0 THEN 
            (select z.batch_code_num from scm.scm_process_run z where z.process_run_id=a.event_rgstr_id)
            ELSE 
            ''
        END event_num
  FROM accb.accb_rcvbls_invc_hdr a " .
        "WHERE((a.rcvbls_invc_hdr_id = " . $hdrID . "))";
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_RcvblsDocLines($docHdrID)
{
    $strSql = "";
    $whrcls = " and (a.rcvbl_smmry_type !='6Grand Total' and 
a.rcvbl_smmry_type !='7Total Payments Made' and a.rcvbl_smmry_type !='8Outstanding Balance')";
    $strSql = "SELECT 
        rcvbl_smmry_id, 
        rcvbl_smmry_type, 
        rcvbl_smmry_desc, 
        rcvbl_smmry_amnt, 
       code_id_behind, 
       auto_calc, 
       incrs_dcrs1, 
       rvnu_acnt_id, 
       incrs_dcrs2, 
       rcvbl_acnt_id, 
       appld_prepymnt_doc_id, 
       entrd_curr_id, gst.get_pssbl_val(a.entrd_curr_id), 
       func_curr_id, gst.get_pssbl_val(a.func_curr_id), 
      accnt_curr_id, gst.get_pssbl_val(a.accnt_curr_id), 
      func_curr_rate, accnt_curr_rate, 
       func_curr_amount, accnt_curr_amnt, initial_amnt_line_id, 
       REPLACE(REPLACE(a.rcvbl_smmry_type,'2Tax','3Tax'),'3Discount','2Discount') smtyp 
  FROM accb.accb_rcvbl_amnt_smmrys a " .
        "WHERE((a.src_rcvbl_hdr_id = " . $docHdrID . ")" . $whrcls . ") ORDER BY 23 ASC ";
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_One_SalesDcLines($dochdrID)
{
    $strSql = "SELECT a.invc_det_ln_id, a.itm_id, 
              a.doc_qty, a.unit_selling_price, (a.doc_qty * a.unit_selling_price) amnt, 
              a.store_id, a.crncy_id, (a.doc_qty - a.qty_trnsctd_in_dest_doc) avlbl_qty, 
              a.src_line_id, a.tax_code_id, a.dscnt_code_id, a.chrg_code_id, a.rtrn_reason, 
              a.consgmnt_ids, a.orgnl_selling_price, b.base_uom_id, b.item_code, b.item_desc, 
      c.uom_name, a.is_itm_delivered, REPLACE(a.extra_desc || ' (' || a.other_mdls_doc_type || ')',' ()','')
        , a.other_mdls_doc_id, a.other_mdls_doc_type, a.lnkd_person_id, 
      REPLACE(prs.get_prsn_surname(a.lnkd_person_id) || ' (' 
      || prs.get_prsn_loc_id(a.lnkd_person_id) || ')', ' ()', '') fullnm, 
      CASE WHEN a.alternate_item_name='' THEN b.item_desc ELSE a.alternate_item_name END, d.cat_name " .
        "FROM scm.scm_sales_invc_det a, inv.inv_itm_list b, inv.unit_of_measure c, inv.inv_product_categories d " .
        "WHERE(a.invc_hdr_id = " . $dochdrID .
        " and a.invc_hdr_id>0 and a.itm_id = b.item_id and b.base_uom_id = c.uom_id and d.cat_id = b.category_id) ORDER BY a.invc_det_ln_id, b.category_id";
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_PyblsDocSmryLns($dochdrID, $docTyp)
{
    $whrcls = " and (a.pybls_smmry_type IN ('6Grand Total','7Total Payments Made','8Outstanding Balance'))";
    $strSql = "SELECT a.pybls_smmry_id, a.pybls_smmry_desc, 
             a.pybls_smmry_amnt, a.code_id_behind, a.pybls_smmry_type, a.auto_calc 
             FROM accb.accb_pybls_amnt_smmrys a 
             WHERE((a.src_pybls_hdr_id = " . $dochdrID .
        ") and (a.src_pybls_type='" . $docTyp . "')$whrcls) ORDER BY a.pybls_smmry_type";
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_RcvblsDocSmryLns($dochdrID, $docTyp)
{
    $whrcls = " and (a.rcvbl_smmry_type IN ('6Grand Total','7Total Payments Made','8Outstanding Balance'))";
    $strSql = "SELECT a.rcvbl_smmry_id, a.rcvbl_smmry_desc, 
             a.rcvbl_smmry_amnt, a.code_id_behind, a.rcvbl_smmry_type, a.auto_calc 
             FROM accb.accb_rcvbl_amnt_smmrys a 
             WHERE((a.src_rcvbl_hdr_id = " . $dochdrID .
        ") and (a.src_rcvbl_type='" . $docTyp . "')$whrcls) ORDER BY a.rcvbl_smmry_type";
    $result = executeSQLNoParams($strSql);
    //echo $strSql;
    return $result;
}

function get_SalesDocSmryLns($dochdrID, $docTyp)
{
    $strSql = "SELECT a.smmry_id, CASE WHEN a.smmry_type='3Discount' THEN 'Discount' ELSE a.smmry_name END, 
             a.smmry_amnt, a.code_id_behind, a.smmry_type, a.auto_calc,REPLACE(REPLACE(a.smmry_type,'2Tax','3Tax'),'3Discount','2Discount') smtyp 
             FROM scm.scm_doc_amnt_smmrys a 
             WHERE((a.src_doc_hdr_id = " . $dochdrID . "
             ) and (a.src_doc_type='" . $docTyp . "')) ORDER BY 7";
    $result = executeSQLNoParams($strSql);
    //echo $strSql;
    return $result;
}

function get_PyblsDocHdr2($searchWord, $searchIn, $offset, $limit_size, $orgID, $shwUnpstdOnly, $cstmrID = -1)
{
    $strSql = "";
    $whrcls = "";
    $unpstdCls = "";
    if ($shwUnpstdOnly) {
        $unpstdCls = " AND (round(a.invoice_amount-a.amnt_paid,2)>0 or a.approval_status IN ('Not Validated','Validated','Reviewed'))";
    }
    if ($searchIn == "Document Number") {
        $whrcls = " and (a.pybls_invc_number ilike '" . loc_db_escape_string($searchWord) . "' or trim(to_char(a.pybls_invc_hdr_id, '99999999999999999999')) ilike '" . loc_db_escape_string($searchWord) .
            "')";
    } else if ($searchIn == "Document Description") {
        $whrcls = " and (a.comments_desc ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Document Classification") {
        $whrcls = " and (a.doc_tmplt_clsfctn ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Supplier Name") {
        $whrcls = " and (a.customer_id IN (select c.cust_sup_id from 
scm.scm_cstmr_suplr c where c.cust_sup_name ilike '" . loc_db_escape_string($searchWord) .
            "'))";
    } else if ($searchIn == "Supplier's Invoice Number") {
        $whrcls = " and (a.spplrs_invc_num ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Source Doc Number") {
        $whrcls = "  and (trim(to_char(a.src_doc_hdr_id, '9999999999999999999999999')) 
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
    }
    $whrcls .= " and (a.supplier_id IN ($cstmrID))";

    $strSql = "SELECT pybls_invc_hdr_id, pybls_invc_number, pybls_invc_type
, round(a.invoice_amount-a.amnt_paid,2),
 a.approval_status 
        FROM accb.accb_pybls_invc_hdr a 
        WHERE((a.org_id = " . $orgID . ")" . $whrcls . $unpstdCls .
        ") ORDER BY pybls_invc_hdr_id DESC LIMIT " . $limit_size .
        " OFFSET " . abs($offset * $limit_size);

    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_PyblsDocHdrTtl2($searchWord, $searchIn, $orgID, $shwUnpstdOnly, $cstmrID = -1)
{
    $strSql = "";
    $whrcls = "";
    $unpstdCls = "";
    if ($shwUnpstdOnly) {
        $unpstdCls = " AND (round(a.invoice_amount-a.amnt_paid,2)>0 or a.approval_status IN ('Not Validated','Validated','Reviewed'))";
    }
    if ($searchIn == "Document Number") {
        $whrcls = " and (a.pybls_invc_number ilike '" . loc_db_escape_string($searchWord) . "' or trim(to_char(a.pybls_invc_hdr_id, '99999999999999999999')) ilike '" . loc_db_escape_string($searchWord) .
            "')";
    } else if ($searchIn == "Document Description") {
        $whrcls = " and (a.comments_desc ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Document Classification") {
        $whrcls = " and (a.doc_tmplt_clsfctn ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Supplier Name") {
        $whrcls = " and (a.customer_id IN (select c.cust_sup_id from 
scm.scm_cstmr_suplr c where c.cust_sup_name ilike '" . loc_db_escape_string($searchWord) .
            "'))";
    } else if ($searchIn == "Supplier's Invoice Number") {
        $whrcls = " and (a.spplrs_invc_num ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Source Doc Number") {
        $whrcls = "  and (trim(to_char(a.src_doc_hdr_id, '9999999999999999999999999')) 
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
    }
    $whrcls .= " and (a.supplier_id IN ($cstmrID))";
    $strSql = "SELECT count(1) 
        FROM accb.accb_pybls_invc_hdr a 
        WHERE((a.org_id = " . $orgID . ")" . $whrcls . $unpstdCls .
        ")";

    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return 0;
}

function get_One_PyblsDocHdr($hdrID)
{
    $strSql = "";

    $strSql = "SELECT pybls_invc_hdr_id, 
        to_char(to_timestamp(pybls_invc_date,'YYYY-MM-DD'),'DD-Mon-YYYY'), 
       created_by, 
       sec.get_usr_name(a.created_by), 
       pybls_invc_number, 
       pybls_invc_type, 
       comments_desc, 
       src_doc_hdr_id, 
       a.src_doc_type, 
       supplier_id, 
       scm.get_cstmr_splr_name(a.supplier_id),
       supplier_site_id, 
       scm.get_cstmr_splr_site_name(a.supplier_site_id), 
       approval_status, 
       next_aproval_action, 
       invoice_amount, 
       payment_terms, 
       pymny_method_id, 
       accb.get_pymnt_mthd_name(a.pymny_method_id), 
       amnt_paid, 
       gl_batch_id, 
       accb.get_gl_batch_name(a.gl_batch_id),
       spplrs_invc_num, 
       doc_tmplt_clsfctn, 
       invc_curr_id, 
       gst.get_pssbl_val(a.invc_curr_id), 
       scm.get_src_doc_num(a.src_doc_hdr_id, a.src_doc_type),
        event_rgstr_id, 
        evnt_cost_category, 
        event_doc_type,
        a.invc_amnt_appld_elswhr,
        CASE WHEN a.event_doc_type='Attendance Register' and a.event_rgstr_id>0 THEN 
            (select z.recs_hdr_name from attn.attn_attendance_recs_hdr z where z.recs_hdr_id=a.event_rgstr_id)
            WHEN a.event_doc_type='Production Process Run' and a.event_rgstr_id>0 THEN 
            (select z.batch_code_num from scm.scm_process_run z where z.process_run_id=a.event_rgstr_id)
            ELSE 
            ''
        END event_num
  FROM accb.accb_pybls_invc_hdr a " .
        "WHERE((a.pybls_invc_hdr_id = " . $hdrID . "))";
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_PyblsDocLines($docHdrID)
{
    $strSql = "";
    $whrcls = " and (a.pybls_smmry_type !='6Grand Total' and 
a.pybls_smmry_type !='7Total Payments Made' and a.pybls_smmry_type !='8Outstanding Balance')";
    $strSql = "SELECT 
        pybls_smmry_id, 
        pybls_smmry_type, 
        pybls_smmry_desc, 
        pybls_smmry_amnt, 
       code_id_behind, 
       auto_calc, 
       incrs_dcrs1, 
       asset_expns_acnt_id, 
       incrs_dcrs2, 
       liability_acnt_id, 
       appld_prepymnt_doc_id, 
       entrd_curr_id, gst.get_pssbl_val(a.entrd_curr_id), 
       func_curr_id, gst.get_pssbl_val(a.func_curr_id), 
      accnt_curr_id, gst.get_pssbl_val(a.accnt_curr_id), 
      func_curr_rate, accnt_curr_rate, 
       func_curr_amount, accnt_curr_amnt, initial_amnt_line_id, 
       REPLACE(REPLACE(a.pybls_smmry_type,'2Tax','3Tax'),'3Discount','2Discount') smtyp 
  FROM accb.accb_pybls_amnt_smmrys a " .
        "WHERE((a.src_pybls_hdr_id = " . $docHdrID . ")" . $whrcls . ") ORDER BY 23 ASC ";
    $result = executeSQLNoParams($strSql);
    return $result;
}

function createPayDfltSets()
{
    global $orgID;
    if (getItmStID("Bill Items SQL", $orgID) <= 0) {
        $query1 = "/* Created on 12/27/2012 10:18:43 AM By Rhomicom */ " .
            "SELECT a.item_id, a.item_code_name, a.item_value_uom, " .
            "(CASE WHEN a.item_min_type='Earnings' or a.item_min_type='Employer Charges' " .
            "THEN 'Payment by Organisation' WHEN a.item_min_type='Bills/Charges' or " .
            "a.item_min_type='Deductions' THEN 'Payment by Person' ELSE 'Purely Informational' END) trns_typ " .
            "FROM org.org_pay_items a " .
            "WHERE a.local_classfctn = 'Bill Item' AND a.org_id = (SELECT org_id " .
            "FROM org.org_details " .
            "WHERE org_name = '" . loc_db_escape_string(getOrgName($orgID)) . "')";
        createItmSt($orgID, "Bill Items SQL", "Bill Items SQL", true, false, true, $query1);
    }

    if (getItmStID("All Pay Items", $orgID) <= 0) {
        $query1 = "/* Created on 12/27/2012 10:18:43 AM By Rhomicom */ " .
            "SELECT a.item_id, a.item_code_name, a.item_value_uom, " .
            "(CASE WHEN a.item_min_type='Earnings' or a.item_min_type='Employer Charges' " .
            "THEN 'Payment by Organisation' WHEN a.item_min_type='Bills/Charges' or " .
            "a.item_min_type='Deductions' THEN 'Payment by Person' ELSE 'Purely Informational' END) trns_typ " .
            "FROM org.org_pay_items a " .
            "WHERE a.org_id = (SELECT org_id " .
            "FROM org.org_details " .
            "WHERE org_name = '" . loc_db_escape_string(getOrgName($orgID)) . "')";
        createItmSt($orgID, "All Pay Items", "All Pay Items", true, true, true, $query1);
    }
    if (getPrsStID("One Person SQL", $orgID) <= 0) {
        $query1 = "/* Created on 12/27/2012 10:18:43 AM By Rhomicom */ " .
            "SELECT DISTINCT " .
            "a.person_id, " .
            "a.local_id_no, " .
            "trim(a.title || ' ' || a.sur_name || ', ' || a.first_name || ' ' || a.other_names) full_name, a.img_location " .
            "FROM prs.prsn_names_nos a, pasn.prsn_prsntyps b " .
            "WHERE     a.person_id = b.person_id " .
            "AND a.org_id = (SELECT org_id " .
            "FROM org.org_details " .
            "WHERE org_name = '" . loc_db_escape_string(getOrgName($orgID)) . "') " .
            "AND a.local_id_no IN ('M0001') " .
            "AND (now () BETWEEN TO_TIMESTAMP (b.valid_start_date || ' 00:00:00', " .
            "'YYYY-MM-DD HH24:MI:SS' " .
            ") " .
            "AND  TO_TIMESTAMP (b.valid_end_date || ' 23:59:59', " .
            "'YYYY-MM-DD HH24:MI:SS' " .
            "))";
        createPrsSt($orgID, "One Person SQL", "One Person SQL", true, $query1, false, true);
    }

    if (getPrsStID("All Active Employees", $orgID) <= 0) {
        $query1 = "/* Created on 12/27/2012 10:18:43 AM By Rhomicom */ " .
            "SELECT DISTINCT " .
            "a.person_id, " .
            "a.local_id_no, " .
            "trim(a.title || ' ' || a.sur_name || ', ' || a.first_name || ' ' || a.other_names) full_name, a.img_location " .
            "FROM prs.prsn_names_nos a, pasn.prsn_prsntyps b " .
            "WHERE     a.person_id = b.person_id " .
            "AND a.org_id = (SELECT org_id " .
            "FROM org.org_details " .
            "WHERE org_name = '" . loc_db_escape_string(getOrgName($orgID)) . "') " .
            "AND b.prsn_type = 'Employee' " .
            "AND (now () BETWEEN TO_TIMESTAMP (b.valid_start_date || ' 00:00:00', " .
            "'YYYY-MM-DD HH24:MI:SS' " .
            ") " .
            "AND  TO_TIMESTAMP (b.valid_end_date || ' 23:59:59', " .
            "'YYYY-MM-DD HH24:MI:SS' " .
            "))";
        createPrsSt($orgID, "All Active Employees", "All Active Employees", true, $query1, false, true);
    }
    if (getPrsStID("All Persons", $orgID) <= 0) {
        $query1 = "/* Created on 12/27/2012 10:18:43 AM By Rhomicom */ " .
            "SELECT DISTINCT " .
            "a.person_id, " .
            "a.local_id_no, " .
            "trim(a.title || ' ' || a.sur_name || ', ' || a.first_name || ' ' || a.other_names) full_name, a.img_location " .
            "FROM prs.prsn_names_nos a " .
            "WHERE 1 = 1";
        createPrsSt($orgID, "All Persons", "All Persons", true, $query1, true, true);
    }
}

function createRqrdPayItems()
{
    global $orgID;
    global $gnrlTrnsDteYMDHMS;
    $itmNm = array("Total Advance Payments Balance", "Advance Payments Amount Kept", "Advance Payments Amount Applied", "Annual Leave Add");
    $itmDesc = array("Total Advance Payments Balance", "Advance Payments Amount Kept", "Advance Payments Amount Applied", "Annual Leave Add");
    $itmMajTyp = array("Balance Item", "Pay Value Item", "Pay Value Item", "Pay Value Item");
    $itmMinTyp = array("Purely Informational", "Deductions", "Earnings", "Purely Informational");
    $itmUOM = array("Money", "Money", "Money", "Number");
    $payFreq = array("Adhoc", "Adhoc", "Adhoc", "Adhoc");
    $payPryty = array("99999999.97", "99999999.98", "99999999.99", "99999999.96");
    $usesSQL = array("NO", "NO", "YES", "YES");
    $localClass = array("Advance Items", "Advance Items", "Advance Items", "Leave Items");
    $balsTyp = array("Cumulative", "", "", "");
    $inc_dc_cost = array("", "Increase", "Decrease", "");
    $effctOnOrgDbt = array("Decrease", "Decrease", "Increase", "None");
    $costAccntNo = array("", "", "", "");
    $inc_dc_bals = array("", "Increase", "Decrease", "");
    $balsAccntNo = array("", "", "", "");
    $feedIntoNM = array("", "Total Advance Payments Balance", "Total Advance Payments Balance", "");
    $add_subtract = array("", "Adds", "Subtracts", "");
    $scale_fctr = array("", "1.00", "1.00", "");
    $isRetro = array("NO", "NO", "NO", "NO");
    $retroItmNm = array("", "", "", "");
    $invItmCode = array("", "", "", "");
    $allwEdit = array("YES", "YES", "YES", "YES");
    $creatsAcctng = array("NO", "YES", "YES", "NO");
    $valNm = array("Total Advance Payments Balance Value", "Advance Payments Amount Kept Value", "Advance Payments Amount Applied Value", "Annual Leave Add Value");
    $amnt = array("0", "0", "0", "");
    $valSQL = array(
        "", "", "select pay.get_ltst_blsitm_bals({:person_id},org.get_payitm_id('Total Advance Payments Balance'),'{:pay_date}')",
        "select pay.get_gbv_sal_usedte_gnrl({:person_id}, '{:pay_date}', 'Grade', 'Staff Leave Entitlements', '''Employee'', ''Staff'', ''National Service Personnel''')"
    );
    for ($i = 0; $i < count($itmNm); $i++) {
        $itm_id_in = getItmID($itmNm[$i], $orgID);
        $pryty = floatval($payPryty[$i]);
        $itmMnTypID = -1;
        $scl = floatval($scale_fctr[$i]);
        if ($itmMinTyp[$i] == "Earnings") {
            $itmMnTypID = 1;
        } else if ($itmMinTyp[$i] == "Employer Charges") {
            $itmMnTypID = 2;
        } else if ($itmMinTyp[$i] == "Deductions") {
            $itmMnTypID = 3;
        } else if ($itmMinTyp[$i] == "Bills/Charges") {
            $itmMnTypID = 4;
        } else if ($itmMinTyp[$i] == "Purely Informational") {
            $itmMnTypID = 5;
        }
        $isRetroElmnt = false;
        $allwEditing = false;
        $creatsActng = false;
        $retrItmID = getItmID($retroItmNm[$i], $orgID);
        $invItmID = getInvItmID($invItmCode[$i], $orgID);
        if ($isRetro[$i] == "YES") {
            $isRetroElmnt = true;
        }
        if ($allwEdit[$i] == "YES") {
            $allwEditing = true;
        }
        if ($creatsAcctng[$i] == "YES") {
            $creatsActng = true;
        }
        if ($itm_id_in <= 0 && $orgID > 0) {
            createItm(
                $orgID,
                $itmNm[$i],
                $itmDesc[$i],
                $itmMajTyp[$i],
                $itmMinTyp[$i],
                $itmUOM[$i],
                cnvrtYNToBool($usesSQL[$i]),
                true,
                getAccntID($costAccntNo[$i], $orgID),
                getAccntID($balsAccntNo[$i], $orgID),
                $payFreq[$i],
                $localClass[$i],
                $pryty,
                $inc_dc_cost[$i],
                $inc_dc_bals[$i],
                $balsTyp[$i],
                $itmMnTypID,
                $isRetroElmnt,
                $retrItmID,
                $invItmID,
                $allwEditing,
                $creatsActng,
                $effctOnOrgDbt[$i]
            );
            $nwItmID = getItmID($itmNm[$i], $orgID);
            $itm_id_in = $nwItmID;
            $feedIntoItmID = getItmID($feedIntoNM[$i], $orgID);
            $feedItmMayTyp = getGnrlRecNm("org.org_pay_items", "item_id", "item_maj_type", $feedIntoItmID);
            if ($itmMajTyp[$i] == "Balance Item") {
                createItmVal($nwItmID, 0, "", $itmNm[$i] + " Value");
            } else if ($feedItmMayTyp == "Balance Item") {
                if ($feedIntoItmID > 0) {
                    if ($add_subtract[$i] != "Adds" && $add_subtract[$i] != "Subtracts") {
                        $add_subtract[$i] = "Adds";
                    }
                    createItmFeed($nwItmID, $feedIntoItmID, $add_subtract[$i], $scl);
                }
            }
        }

        $val_id_in = getItmValID($valNm[$i], $itm_id_in);
        $amntFig = floatval($amnt[$i]);

        if ($itm_id_in > 0 && $val_id_in <= 0) {
            createItmVal($itm_id_in, $amntFig, $valSQL[$i], $valNm[$i]);
        } else if ($val_id_in > 0) {
            updateItmVal($val_id_in, $itm_id_in, $amntFig, $valSQL[$i], $valNm[$i]);
        }
    }
    //Staff Leave Entitlements
    $payGlblValsName = "Staff Leave Entitlements";
    $payGlblValsDesc = "Staff Leave Entitlements";
    $payGlblValsCritType = "Grade";

    $payGlblValsIsEnbld = TRUE;
    $payGlblValsID = getGBVID($payGlblValsName, $orgID);
    if ($payGlblValsID <= 0) {
        createGBVHdr($orgID, $payGlblValsName, $payGlblValsDesc, $payGlblValsCritType, $payGlblValsIsEnbld);
        $payGlblValsID = getGBVID($payGlblValsName, $orgID);
    }

    $plnNm = 'Annual Leave Plan';
    $plnDesc = 'Annual Leave Plan';
    $plnExctnIntrvl = 'Yearly';
    $plnStrtDte = '01-Jan-' . substr($gnrlTrnsDteYMDHMS, 0, 4);
    $plnEndDte = '31-Dec-' . substr($gnrlTrnsDteYMDHMS, 0, 4);
    $lnkdBalsItmID = -1;
    $lnkdAddItmID = getItmID("Annual Leave Add", $orgID);
    $lnkdSbtrctItmID = -1;
    $canExcdEntlmnt = 'NO';
    $canExcdEntlmntVal = ($canExcdEntlmnt == "NO") ? "0" : "1";
    $sbmtdPlanID = (float) getGnrlRecID("prs.hr_accrual_plans", "accrual_plan_name", "accrual_plan_id", $plnNm, $orgID);
    if ($sbmtdPlanID <= 0) {
        createAccrualPlns1(
            $plnNm,
            $plnDesc,
            $plnExctnIntrvl,
            $plnStrtDte,
            $plnEndDte,
            $lnkdBalsItmID,
            $lnkdAddItmID,
            $orgID,
            $lnkdSbtrctItmID,
            $canExcdEntlmntVal
        );
    }
}

function createAccrualPlns1(
    $plnName,
    $plnDesc,
    $plnExctnIntrvls,
    $startDate,
    $endDate,
    $lnkdBalsItmID,
    $lnkdAddItmID,
    $orgid,
    $lnkdSbtrctItmID,
    $canExcdLmt
) {
    global $usrID;
    $dateStr = getDB_Date_time();
    if ($startDate != "") {
        $startDate = cnvrtDMYToYMD($startDate);
    }
    if ($endDate != "") {
        $endDate = cnvrtDMYToYMD($endDate);
    }
    $insSQL = "INSERT INTO prs.hr_accrual_plans(
            accrual_plan_name, accrual_plan_desc, plan_execution_intrvls, 
            accrual_start_date, accrual_end_date, lnkd_balance_item_id, lnkd_balnc_add_item_id, 
            lnkd_balnc_sbtrct_item_id, can_excd_entltlmnt, org_id, created_by, creation_date, 
            last_update_by, last_update_date) " .
        "VALUES ('" . loc_db_escape_string($plnName) .
        "', '" . loc_db_escape_string($plnDesc) .
        "', '" . loc_db_escape_string($plnExctnIntrvls) .
        "', '" . loc_db_escape_string($startDate) .
        "', '" . loc_db_escape_string($endDate) .
        "', " . $lnkdBalsItmID .
        ", " . $lnkdAddItmID .
        ", " . $lnkdSbtrctItmID .
        ", '" . loc_db_escape_string($canExcdLmt) .
        "', " . $orgid .
        ", " . $usrID . ", '" . $dateStr . "', " . $usrID . ",'" . $dateStr .
        "')";
    //echo $insSQL;
    return execUpdtInsSQL($insSQL);
}

function getItmStID($itmstname, $orgid)
{
    $sqlStr = "select hdr_id from pay.pay_itm_sets_hdr where lower(itm_set_name) = '"
        . loc_db_escape_string($itmstname) . "' and org_id = " . loc_db_escape_string($orgid);
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -1;
}

function getPrsStID($prsstname, $orgid)
{
    $sqlStr = "select prsn_set_hdr_id from pay.pay_prsn_sets_hdr where lower(prsn_set_hdr_name) = '" .
        loc_db_escape_string(strtolower($prsstname)) . "' and org_id = " . loc_db_escape_string($orgid);
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -1;
}

function getPrsStName($prsstid)
{
    $sqlStr = "select prsn_set_hdr_name from pay.pay_prsn_sets_hdr where prsn_set_hdr_id = " .
        $prsstid . "";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return "";
}

function getPrsStSQL($prsstid)
{
    $sqlStr = "select sql_query from pay.pay_prsn_sets_hdr where prsn_set_hdr_id = " .
        $prsstid . "";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return "";
}

function getItmStName($itmstid)
{
    $sqlStr = "select itm_set_name from pay.pay_itm_sets_hdr where hdr_id = " .
        $itmstid . "";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return "";
}

function getMsPyName($mspyid)
{
    $sqlStr = "select mass_pay_name from pay.pay_mass_pay_run_hdr where mass_pay_id = " .
        $mspyid . "";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return "";
}

function getMsPyID($mspyname, $orgid)
{
    $sqlStr = "select mass_pay_id from pay.pay_mass_pay_run_hdr where lower(mass_pay_name) = '" .
        loc_db_escape_string(strtolower($mspyname)) . "' and org_id = " . $orgid;
    //echo $sqlStr;
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -1;
}

function getItmValueAmnt($itmvalid)
{
    $sqlStr = "select pssbl_amount from org.org_pay_items_values where pssbl_value_id = " .
        $itmvalid . "";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return 0;
}

function getItmValSQL($itmvalid)
{
    $sqlStr = "select pssbl_value_sql from org.org_pay_items_values where pssbl_value_id = " .
        $itmvalid . "";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return "";
}

function getItmValName($itmvalid)
{
    $sqlStr = "select pssbl_value_code_name from org.org_pay_items_values where pssbl_value_id = " .
        $itmvalid . "";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return "";
}

function getItmName($itmid)
{
    $sqlStr = "select item_code_name from org.org_pay_items where item_id = " .
        $itmid . "";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return "";
}

function getItmID($itmname, $orgid)
{
    $sqlStr = "select item_id from org.org_pay_items where lower(item_code_name) = '" .
        loc_db_escape_string(strtolower($itmname)) . "' and org_id = " . $orgid;
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -1;
}

function getInvItmID($itmname, $orgid)
{
    $sqlStr = "select item_id from inv.inv_itm_list where lower(item_code) = '" .
        loc_db_escape_string(strtolower($itmname)) . "' and org_id = " . $orgid;
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -1;
}

function getItmMinType($itmid)
{
    $sqlStr = "select item_min_type from org.org_pay_items where item_id = " .
        $itmid . "";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return "";
}

function getItmMajType($itmid)
{
    $sqlStr = "select item_maj_type from org.org_pay_items where item_id = " .
        $itmid . "";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return "";
}

function getItmValID($itmvalname, $itmid)
{
    $sqlStr = "select pssbl_value_id from org.org_pay_items_values where lower(pssbl_value_code_name) = '" .
        loc_db_escape_string(strtolower($itmvalname)) . "' and item_id = " . $itmid;
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -1;
}

function exctItmValSQL($itemSQL, $prsn_id, $org_id, $dateStr, $p_itm_typ_id = -1, $p_request_id = -1)
{
    if ($dateStr != "") {
        $dateStr = cnvrtDMYTmToYMDTm($dateStr);
    }
    if (strlen($dateStr) > 10) {
        $dateStr = substr($dateStr, 0, 10);
    }
    $nwSQL = str_replace("{:request_id}", $p_request_id, str_replace("{:item_typ_id}", $p_itm_typ_id, str_replace("{:pay_date}", $dateStr, str_replace("{:org_id}", $org_id, str_replace("{:person_id}", $prsn_id, $itemSQL)))));
    $result = executeSQLNoParams($nwSQL);

    while ($row = loc_db_fetch_array($result)) {
        return floatval($row[0]);
    }
    return 0;
}

function isItmValSQLValid($itemSQL, $prsn_id, $org_id, $dateStr, $p_itm_typ_id, $p_request_id, &$errMsg)
{
    set_error_handler("rhoErrorHandler3");
    try {
        if ($dateStr != "") {
            $dateStr = cnvrtDMYTmToYMDTm($dateStr);
        }
        if (strlen($dateStr) > 10) {
            $dateStr = substr($dateStr, 0, 10);
        }
        $nwSQL = str_replace("{:request_id}", $p_request_id, str_replace("{:item_typ_id}", $p_itm_typ_id, str_replace("{:pay_date}", $dateStr, str_replace("{:org_id}", $org_id, str_replace("{:person_id}", $prsn_id, $itemSQL)))));

        $result = executeSQLNoParams($nwSQL);
        if ($result !== NULL && strpos(strtoupper($_SESSION['ERROR_MSG']), "ERROR") === FALSE) {
            return true;
        } else {
            $errMsg .= $_SESSION['ERROR_MSG'] . "<br/>";
            return false;
        }
    } catch (\Exception $ex) {
        $errMsg .= "An error occurred. <br/>" . $ex->getMessage() . " Invalid Value SQL Query!<br/>";
        return FALSE;
    }
}

function isPrsnSetSQLValid($prsStSQL, &$errMsg)
{
    try {
        $testSQL = "SELECT DISTINCT " .
            "a.person_id, " .
            "a.local_id_no, " .
            "trim(a.title || ' ' || a.sur_name || ', ' || a.first_name || ' ' || a.other_names) full_name, a.img_location";
        if (strpos(
            str_replace(" ", "", str_replace("\n", "", (str_replace("\r", "", str_replace("\r\n", "", strtolower($prsStSQL)))))),
            str_replace(" ", "", strtolower($testSQL))
        ) === FALSE) {
            $errMsg = "Person Set SQL Query must start with<br/><br/>" . $testSQL;
            return FALSE;
        } else {
            $conn = getConn();
            $result = loc_db_query($conn, $prsStSQL);
            if (!$result) {
                $errMsg = "An error occurred. <br/> " . loc_db_result_error($result);
            }
            loc_db_close($conn);
            return TRUE;
        }
    } catch (Exception $ex) {
        $errMsg = "An error occurred. <br/>" . $ex->getMessage() . " Invalid Person Set SQL Query!";
        return FALSE;
    }
}

function isItemSetSQLValid($itmStSQL, &$errMsg)
{
    try {
        $testSQL = "SELECT a.item_id, a.item_code_name, a.item_value_uom, " .
            "(CASE WHEN a.item_min_type='Earnings' or a.item_min_type='Employer Charges' " .
            "THEN 'Payment by Organisation' WHEN a.item_min_type='Bills/Charges' or " .
            "a.item_min_type='Deductions' THEN 'Payment by Person' ELSE 'Purely Informational' END) trns_typ";
        if (strpos(
            str_replace(" ", "", str_replace("\n", "", (str_replace("\r", "", str_replace("\r\n", "", strtolower($itmStSQL)))))),
            str_replace(" ", "", strtolower($testSQL))
        ) === FALSE) {
            $errMsg = "Item Set SQL Query must start with<br/><br/>" . $testSQL;
            return FALSE;
        } else {
            $conn = getConn();
            $result = loc_db_query($conn, $itmStSQL);
            if (!$result) {
                $errMsg = "An error occurred. <br/> " . loc_db_result_error($result);
            }
            loc_db_close($conn);
            return TRUE;
        }
    } catch (Exception $ex) {
        $errMsg = "An error occurred. <br/>" . $ex->getMessage() . " Invalid Item Set SQL Query!";
        return FALSE;
    }
}

function updateItmVal($pssblvalid, $itmid, $amnt, $sqlFormula, $valNm)
{
    global $usrID;
    $dateStr = getDB_Date_time();
    $updtSQL = "UPDATE org.org_pay_items_values " .
        "SET item_id=" . $itmid . ", pssbl_amount=" . $amnt .
        ", pssbl_value_sql='" . loc_db_escape_string($sqlFormula) . "', " .
        "last_update_by=" . $usrID . ", last_update_date='" . $dateStr . "', " .
        "pssbl_value_code_name='" . loc_db_escape_string($valNm) . "' " .
        "WHERE pssbl_value_id = " . $pssblvalid;
    return execUpdtInsSQL($updtSQL);
}

function updateItmFeed($feedid, $itmid, $balsItmID, $addSub, $scaleFctr)
{
    global $usrID;
    $dateStr = getDB_Date_time();
    $updtSQL = "UPDATE org.org_pay_itm_feeds " .
        "SET balance_item_id=" . $balsItmID . ", fed_by_itm_id=" . $itmid .
        ", adds_subtracts='" . loc_db_escape_string($addSub) . "', " .
        "last_update_by=" . $usrID . ", last_update_date='" . $dateStr . "', " .
        "scale_factor=" . $scaleFctr .
        " WHERE feed_id = " . $feedid;
    return execUpdtInsSQL($updtSQL);
}

function clearTakeHomes()
{
    global $usrID;
    $dateStr = getDB_Date_time();
    $updtSQL = "UPDATE org.org_pay_items SET is_take_home_pay = '0', last_update_by = " . $usrID . ", " .
        "last_update_date = '" . $dateStr . "' " . "WHERE (is_take_home_pay = '1')";
    return execUpdtInsSQL($updtSQL);
}

function updateItm(
    $orgid,
    $itmid,
    $itnm,
    $itmDesc,
    $itmMajTyp,
    $itmMinTyp,
    $itmUOMTyp,
    $useSQL,
    $isenbld,
    $costAcnt,
    $balsAcnt,
    $freqncy,
    $locClass,
    $priorty,
    $inc_dc_cost,
    $inc_dc_bals,
    $balstyp,
    $itmMnID,
    $isRetro,
    $retroID,
    $invItmID,
    $allwEdit,
    $createsAcctng,
    $effctOnOrgDbt
) {
    global $usrID;
    $dateStr = getDB_Date_time();
    $updtSQL = "UPDATE org.org_pay_items " .
        "SET item_code_name='" . loc_db_escape_string($itnm) .
        "', item_desc='" . loc_db_escape_string($itmDesc) .
        "', item_maj_type='" . loc_db_escape_string($itmMajTyp) .
        "', item_min_type='" . loc_db_escape_string($itmMinTyp) .
        "', item_value_uom='" . loc_db_escape_string($itmUOMTyp) .
        "', uses_sql_formulas='" . loc_db_escape_string(cnvrtBoolToBitStr($useSQL)) .
        "', cost_accnt_id=" . $costAcnt .
        ", bals_accnt_id=" . $balsAcnt . ", " .
        "is_enabled='" . loc_db_escape_string(cnvrtBoolToBitStr($isenbld)) .
        "', org_id=" . $orgid .
        ", last_update_by=" . $usrID .
        ", last_update_date='" . $dateStr .
        "', pay_frequency = '" . loc_db_escape_string($freqncy) .
        "', local_classfctn = '" . loc_db_escape_string($locClass) .
        "', pay_run_priority = " . loc_db_escape_string($priorty) .
        ", incrs_dcrs_cost_acnt ='" . loc_db_escape_string($inc_dc_cost) .
        "', incrs_dcrs_bals_acnt='" . loc_db_escape_string($inc_dc_bals) .
        "', balance_type='" . loc_db_escape_string($balstyp) .
        "', report_line_no= " . $itmMnID .
        ", is_retro_element='" . loc_db_escape_string(cnvrtBoolToBitStr($isRetro)) .
        "', retro_item_id= " . $retroID .
        ", inv_item_id= " . $invItmID .
        ", allow_value_editing='" . loc_db_escape_string(cnvrtBoolToBitStr($allwEdit)) .
        "', creates_accounting='" . loc_db_escape_string(cnvrtBoolToBitStr($createsAcctng)) .
        "', effct_on_org_debt='" . loc_db_escape_string($effctOnOrgDbt) .
        "' WHERE item_id=" . $itmid;
    return execUpdtInsSQL($updtSQL);
}

function createItm(
    $orgid,
    $itnm,
    $itmDesc,
    $itmMajTyp,
    $itmMinTyp,
    $itmUOMTyp,
    $useSQL,
    $isenbld,
    $costAcnt,
    $balsAcnt,
    $freqncy,
    $locClass,
    $priorty,
    $inc_dc_cost,
    $inc_dc_bals,
    $balstyp,
    $itmMnID,
    $isRetro,
    $retroID,
    $invItmID,
    $allwEdit,
    $createsAcctng,
    $effctOnOrgDbt
) {
    global $usrID;
    $dateStr = getDB_Date_time();
    $insSQL = "INSERT INTO org.org_pay_items(" .
        "item_code_name, item_desc, item_maj_type, item_min_type, " .
        "item_value_uom, uses_sql_formulas, cost_accnt_id, bals_accnt_id, " .
        "is_enabled, org_id, created_by, creation_date, last_update_by, " .
        "last_update_date, pay_frequency, local_classfctn, pay_run_priority, " .
        "incrs_dcrs_cost_acnt, incrs_dcrs_bals_acnt, balance_type, report_line_no," .
        " is_retro_element,retro_item_id,inv_item_id, allow_value_editing, creates_accounting,effct_on_org_debt) " .
        "VALUES ('" . loc_db_escape_string($itnm) .
        "', '" . loc_db_escape_string($itmDesc) .
        "', '" . loc_db_escape_string($itmMajTyp) .
        "', '" . loc_db_escape_string($itmMinTyp) .
        "', '" . loc_db_escape_string($itmUOMTyp) .
        "', '" . loc_db_escape_string(cnvrtBoolToBitStr($useSQL)) .
        "', " . $costAcnt .
        ", " . $balsAcnt .
        ", '" . loc_db_escape_string(cnvrtBoolToBitStr($isenbld)) .
        "', " . $orgid .
        ", " . $usrID .
        ", '" . $dateStr .
        "', " . $usrID .
        ", '" . $dateStr .
        "', '" . loc_db_escape_string($freqncy) .
        "', '" . loc_db_escape_string($locClass) .
        "', " . $priorty .
        ",'" . loc_db_escape_string($inc_dc_cost) . "','" .
        loc_db_escape_string($inc_dc_bals) .
        "','" . loc_db_escape_string($balstyp) .
        "', " . $itmMnID .
        ", '" . loc_db_escape_string(cnvrtBoolToBitStr($isRetro)) .
        "', " . $retroID .
        ", " . $invItmID .
        ",'" . loc_db_escape_string(cnvrtBoolToBitStr($allwEdit)) .
        "','" . loc_db_escape_string(cnvrtBoolToBitStr($createsAcctng)) .
        "','" . loc_db_escape_string($effctOnOrgDbt) . "')";
    return execUpdtInsSQL($insSQL);
}

function createItmVal($itmid, $amnt, $sqlFormula, $valNm)
{
    global $usrID;
    $dateStr = getDB_Date_time();
    $insSQL = "INSERT INTO org.org_pay_items_values(" .
        "item_id, pssbl_amount, pssbl_value_sql, created_by, " .
        "creation_date, last_update_by, last_update_date, pssbl_value_code_name) " .
        "VALUES (" . $itmid . ", " . $amnt .
        ", '" . loc_db_escape_string($sqlFormula) . "', " . $usrID . ", '" . $dateStr . "', " .
        $usrID . ", '" . $dateStr . "', '" . loc_db_escape_string($valNm) . "')";
    return execUpdtInsSQL($insSQL);
}

function createItmFeed($itmid, $balsItmID, $addSub, $scaleFctr)
{
    global $usrID;
    $dateStr = getDB_Date_time();
    $insSQL = "INSERT INTO org.org_pay_itm_feeds(" .
        "balance_item_id, fed_by_itm_id, adds_subtracts, created_by, " .
        "creation_date, last_update_by, last_update_date, scale_factor) " .
        "VALUES (" . $balsItmID . ", " . $itmid .
        ", '" . loc_db_escape_string($addSub) . "', " . $usrID . ", '" . $dateStr . "', " .
        $usrID . ", '" . $dateStr . "', " . $scaleFctr . ")";
    return execUpdtInsSQL($insSQL);
}

function doesItmFeedExists($itmid, $blsItmID)
{
    $selSQL = "SELECT a.feed_id " .
        "FROM org.org_pay_itm_feeds a WHERE ((a.fed_by_itm_id = " . $itmid .
        ") and (a.balance_item_id = " . $blsItmID .
        ")) ORDER BY a.feed_id ";
    $result = executeSQLNoParams($selSQL);
    if (loc_db_num_rows($result) > 0) {
        return true;
    } else {
        return false;
    }
}

function deletePayItem($hdrID, $itmNm)
{
    $selSQL1 = "Select count(1) from pay.pay_itm_trnsctns where item_id = " . $hdrID;
    $result1 = executeSQLNoParams($selSQL1);
    $trnsCnt1 = 0;
    while ($row = loc_db_fetch_array($result1)) {
        $trnsCnt1 = (float) $row[0];
    }
    $selSQL2 = "Select count(1) from pay.pay_itm_trnsctns_retro where item_id = " . $hdrID;
    $result2 = executeSQLNoParams($selSQL2);
    while ($row = loc_db_fetch_array($result2)) {
        $trnsCnt1 += (float) $row[0];
    }
    if ($trnsCnt1 > 0) {
        $dsply = "No Record Deleted<br/>Cannot DELETE Items used in Transactions!!";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
    $delSQL = "DELETE FROM org.org_pay_itm_feeds WHERE balance_item_id = " . $hdrID . " or fed_by_itm_id = " . $hdrID;
    $affctd1 = execUpdtInsSQL($delSQL, $itmNm);
    $delSQL = "DELETE FROM org.org_pay_items_values WHERE item_id = " . $hdrID;
    $affctd2 = execUpdtInsSQL($delSQL, $itmNm);
    $delSQL = "DELETE FROM org.org_pay_items WHERE item_id = " . $hdrID;
    $affctd = execUpdtInsSQL($delSQL, $itmNm);
    if ($affctd > 0) {
        $dsply = "";
        $dsply .= "<br/>Successfully Executed the ff-";
        $dsply .= "<br/>Deleted $affctd1 Pay Item Feed(s)!";
        $dsply .= "<br/>Deleted $affctd2 Pay Item Value(s)!";
        $dsply .= "<br/>Deleted $affctd Pay Item(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Deleted";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function deleteItemFeed($itmfeedid, $itmNm)
{
    $delSQL = "DELETE FROM org.org_pay_itm_feeds WHERE feed_id = " . $itmfeedid;
    $affctd = execUpdtInsSQL($delSQL, "Item Name = " . $itmNm);
    if ($affctd > 0) {
        $dsply = "";
        $dsply .= "<br/>Successfully Executed the ff-";
        $dsply .= "<br/>Deleted $affctd Item Feed(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Deleted";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function deleteItemVal($psblValid, $itmNm)
{
    $delSQL = "DELETE FROM org.org_pay_items_values WHERE pssbl_value_id = " . $psblValid;
    $affctd = execUpdtInsSQL($delSQL, "Item Value Nm = " . $itmNm);
    if ($affctd > 0) {
        $dsply = "";
        $dsply .= "<br/>Successfully Executed the ff-";
        $dsply .= "<br/>Deleted $affctd Item Value(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Deleted";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function createItmSt($orgid, $itmsetname, $itmstdesc, $isenbled, $isdflt, $usesSQL, $sqlTxt)
{
    global $usrID;
    $insSQL = "INSERT INTO pay.pay_itm_sets_hdr(" .
        "itm_set_name, itm_set_desc, is_enabled, created_by, creation_date, " .
        "last_update_by, last_update_date, org_id, is_default, uses_sql, sql_query) " .
        "VALUES ('" . loc_db_escape_string($itmsetname) .
        "', '" . loc_db_escape_string($itmstdesc) .
        "', '" . loc_db_escape_string(cnvrtBoolToBitStr($isenbled)) .
        "', " . loc_db_escape_string($usrID) .
        ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), " . loc_db_escape_string($usrID) .
        ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), " . loc_db_escape_string($orgid) .
        ", '" . loc_db_escape_string(cnvrtBoolToBitStr($isdflt)) .
        "', '" . loc_db_escape_string($usesSQL) .
        "', '" . loc_db_escape_string($sqlTxt) .
        "')";
    return execUpdtInsSQL($insSQL);
}

function updateItmSt($hdrid, $itmsetname, $itmstdesc, $isenbled, $isdflt, $usesSQL, $sqlTxt)
{
    global $usrID;
    $updtSQL = "UPDATE pay.pay_itm_sets_hdr " .
        "SET itm_set_name='" . loc_db_escape_string($itmsetname) .
        "', itm_set_desc='" . loc_db_escape_string($itmstdesc) .
        "', is_enabled = '" . loc_db_escape_string(cnvrtBoolToBitStr($isenbled)) .
        "', is_default = '" . loc_db_escape_string(cnvrtBoolToBitStr($isdflt)) .
        "', last_update_by=" . loc_db_escape_string($usrID) .
        ", last_update_date=to_char(now(),'YYYY-MM-DD HH24:MI:SS'), uses_sql = '" . loc_db_escape_string($usesSQL) .
        "', sql_query = '" . loc_db_escape_string($sqlTxt) .
        "' WHERE hdr_id = " . $hdrid;
    return execUpdtInsSQL($updtSQL);
}

function createItemSetDet($hdrID, $itmID)
{
    global $usrID;
    $trnsTyp = "";
    $itmTyp = getItmMinType($itmID);
    if ($itmTyp == "Earnings" || $itmTyp == "Employer Charges") {
        $trnsTyp = "Payment by Organisation";
    } else if ($itmTyp == "Bills/Charges" || $itmTyp == "Deductions") {
        $trnsTyp = "Payment by Person";
    } else {
        $trnsTyp = "Purely Informational";
    }
    $insSQL = "INSERT INTO pay.pay_itm_sets_det(" .
        "hdr_id, item_id, to_do_trnsctn_type, created_by, creation_date, last_update_by, last_update_date) " .
        "VALUES (" . $hdrID . ", " . $itmID . ",'" . loc_db_escape_string($trnsTyp) .
        "', " . $usrID . ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), " . $usrID . ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'))";
    return execUpdtInsSQL($insSQL);
}

function deleteItemSet($hdrid, $setNm)
{
    $insSQL = "DELETE FROM pay.pay_itm_sets_hdr WHERE hdr_id = " . $hdrid;
    $affctd = execUpdtInsSQL($insSQL, "Item Set Name=$setNm");
    $insSQL1 = "DELETE FROM pay.pay_itm_sets_det WHERE hdr_id = " . $hdrid;
    $affctd1 = execUpdtInsSQL($insSQL1, "Item Set Name=$setNm");
    if ($affctd > 0) {
        $dsply = "Successfully Deleted the ff Records-";
        $dsply .= "<br/>$affctd Item Set(s)!";
        $dsply .= "<br/>$affctd1 Item Set Detail(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Deleted!";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">

    $dsply</p>";
    }
}

function deleteItemSetRole($hdrid, $roleNm)
{
    $affctd = deleteGnrlRecs($hdrid, "pay.pay_sets_allwd_roles", "pay_roles_id", "Linked Role Set Name = " . $roleNm);

    if ($affctd > 0) {
        $dsply = "Successfully Deleted the ff Records-";
        $dsply .= "<br/>$affctd Linked Role Set(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Deleted!";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">

    $dsply</p>";
    }
}

function deleteItemSetDet($detID, $prsnLocID)
{
    $insSQL = "DELETE FROM pay.pay_itm_sets_det WHERE det_id = " . $detID;
    $affctd = execUpdtInsSQL($insSQL, "Item Set ID:$prsnLocID");

    if ($affctd > 0) {
        $dsply = "Successfully Deleted the ff Records-";
        $dsply .= "<br/>$affctd Item Set Detail(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Deleted!";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">

    $dsply</p>";
    }
}

function createPrsSt($orgid, $prssetname, $prsstdesc, $isenbled, $sqlQry, $isdflt, $usesSQL)
{
    global $usrID;
    $dateStr = getDB_Date_time();
    $insSQL = "INSERT INTO pay.pay_prsn_sets_hdr(" .
        "prsn_set_hdr_name, prsn_set_hdr_desc, is_enabled, " .
        "created_by, creation_date, last_update_by, last_update_date, " .
        "sql_query, org_id, is_default, uses_sql) " .
        "VALUES ('" . loc_db_escape_string($prssetname) .
        "', '" . loc_db_escape_string($prsstdesc) .
        "', '" . loc_db_escape_string(cnvrtBoolToBitStr($isenbled)) .
        "', " . loc_db_escape_string($usrID) .
        ", '" . loc_db_escape_string($dateStr) .
        "', " . loc_db_escape_string($usrID) .
        ", '" . loc_db_escape_string($dateStr) .
        "', '" . loc_db_escape_string($sqlQry) .
        "', " . loc_db_escape_string($orgid) .
        ", '" . loc_db_escape_string(cnvrtBoolToBitStr($isdflt)) .
        "', '" . loc_db_escape_string(cnvrtBoolToBitStr($usesSQL)) .
        "')";
    execUpdtInsSQL($insSQL);
}

function updatePersonSet($hdrid, $prssetname, $prsstdesc, $isenbled, $sqlQry, $isdflt, $usesSQL)
{
    global $usrID;
    $dateStr = getDB_Date_time();
    $insSQL = "UPDATE pay.pay_prsn_sets_hdr " .
        "SET prsn_set_hdr_name='" . loc_db_escape_string($prssetname) .
        "', prsn_set_hdr_desc='" . loc_db_escape_string($prsstdesc) .
        "', is_enabled = '" . loc_db_escape_string(cnvrtBoolToBitStr($isenbled)) .
        "', sql_query = '" . loc_db_escape_string($sqlQry) .
        "', is_default = '" . loc_db_escape_string(cnvrtBoolToBitStr($isdflt)) .
        "', uses_sql = '" . loc_db_escape_string(cnvrtBoolToBitStr($usesSQL)) .
        "', last_update_by=" . $usrID .
        ", last_update_date='" . $dateStr .
        "' WHERE prsn_set_hdr_id = " . $hdrid;

    execUpdtInsSQL($insSQL);
}

function get_Org_DfltPrsSt($orgID)
{
    $res = array("-1", "");
    $strSql = "SELECT a.prsn_set_hdr_id, a.prsn_set_hdr_name " .
        "FROM pay.pay_prsn_sets_hdr a, pay.pay_sets_allwd_roles b " .
        "WHERE (a.prsn_set_hdr_id = b.prsn_set_id and (a.org_id = " . $orgID .
        ") and (a.is_default = '1') and (is_enabled = '1')) ORDER BY a.prsn_set_hdr_id LIMIT 1 OFFSET 0";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        $res[0] = $row[0];
        $res[1] = $row[1];
    }
    return $res;
}

function get_Org_DfltItmSt($orgID)
{
    $res = array("-1", "");
    $strSql = "SELECT a.hdr_id, a.itm_set_name, a.itm_set_desc, a.is_enabled " .
        "FROM pay.pay_itm_sets_hdr a , pay.pay_sets_allwd_roles b " .
        "WHERE (a.hdr_id = b.itm_set_id and (a.org_id = " . $orgID .
        ") and (a.is_default = '1') and (a.is_enabled = '1')) ORDER BY a.hdr_id LIMIT 1 OFFSET 0";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        $res[0] = $row[0];
        $res[1] = $row[1];
    }
    return $res;
}

function get_SlctdPrsnsInSet($searchWord, $searchIn, $offset, $limit_size, $orgID, $prsStID)
{
    $prsSQL = getPrsStSQL($prsStID);

    $mnlSQL = "Select distinct a.person_id, a.local_id_no, trim(a.title || ' ' || a.sur_name || " .
        "', ' || a.first_name || ' ' || a.other_names) full_name, b.prsn_set_det_id " .
        "from prs.prsn_names_nos a, pay.pay_prsn_sets_det b " .
        "WHERE ((a.person_id = b.person_id) and (b.prsn_set_hdr_id = " . $prsStID .
        ") and (trim(a.title || ' ' || a.sur_name || " .
        "', ' || a.first_name || ' ' || a.other_names) ilike '" . loc_db_escape_string($searchWord) .
        "' or a.local_id_no ilike '" . loc_db_escape_string($searchWord) .
        "') AND (a.org_id = " . $orgID . ")) ORDER BY a.local_id_no DESC LIMIT " . $limit_size .
        " OFFSET " . abs($offset * $limit_size);
    $strSql = "select * from (" . $prsSQL . ") tbl1 where tbl1.full_name ilike '" . loc_db_escape_string($searchWord) .
        "' or tbl1.local_id_no ilike '" . loc_db_escape_string($searchWord) .
        "' ORDER BY tbl1.local_id_no ASC LIMIT " . $limit_size .
        " OFFSET " . abs($offset * $limit_size);
    if ($prsSQL == "") {
        $strSql = $mnlSQL;
    }
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_SlctdPrsnsInSetTtl($searchWord, $searchIn, $orgID, $prsStID)
{
    $prsSQL = getPrsStSQL($prsStID);
    $mnlSQL = "Select count(distinct a.person_id) " .
        "from prs.prsn_names_nos a, pay.pay_prsn_sets_det b " .
        "WHERE ((a.person_id = b.person_id) and (b.prsn_set_hdr_id = " . $prsStID .
        ") and (trim(a.title || ' ' || a.sur_name || " .
        "', ' || a.first_name || ' ' || a.other_names) ilike '" . loc_db_escape_string($searchWord) .
        "' or a.local_id_no ilike '" . loc_db_escape_string($searchWord) .
        "') AND (a.org_id = " . $orgID . "))";
    $strSql = "select count(distinct tbl1.person_id) from (" . $prsSQL .
        ") tbl1 where tbl1.full_name ilike '" . loc_db_escape_string($searchWord) .
        "' or tbl1.local_id_no ilike '" . loc_db_escape_string($searchWord) .
        "'";
    if ($prsSQL == "") {
        $strSql = $mnlSQL;
    }
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return 0;
}

function get_PrsnDet($pkID)
{
    $strSql = "SELECT    MAX(a.person_id) mt, 
	  MAX(local_id_no), 
	  CASE WHEN MAX(img_location)='' THEN MAX(a.person_id)||'.png' ELSE MAX(img_location) END, 
          MAX(title), 
          MAX(first_name), 
          MAX(sur_name) , 
          MAX(other_names), 
          org.get_org_name(MAX(org_id)) organisation, 
          MAX(gender), 
          MAX(marital_status), 
          to_char(to_timestamp(MAX(date_of_birth),'YYYY-MM-DD'),'DD-Mon-YYYY') || ' (' || extract('years' from age(now(), to_timestamp(MAX(date_of_birth),'YYYY-MM-DD'))) || ' yr(s)' || ')' , 
          MAX(place_of_birth), 
          MAX(religion), 
          MAX(res_address) residential_address, 
          MAX(pstl_addrs) postal_address, 
          MAX(email), 
          MAX(cntct_no_tel) tel, 
          MAX(cntct_no_mobl) mobile, 
          MAX(cntct_no_fax) fax, 
          MAX(hometown), 
          MAX(nationality), 
          (CASE WHEN MAX(lnkd_firm_org_id)>0 THEN 
          scm.get_cstmr_splr_name(MAX(lnkd_firm_org_id))
              ELSE 
              MAX(new_company)
              END) \"Linked Firm/ Workplace \", 
          (CASE WHEN MAX(lnkd_firm_org_id)>0 THEN 
           scm.get_cstmr_splr_site_name(MAX(lnkd_firm_site_id))
              ELSE 
              MAX(new_company_loc)
              END) \"Branch \", 
          MAX(b.prsn_type) \"Relation Type\", 
          MAX(b.prn_typ_asgnmnt_rsn) \"Cause of Relation\", 
            MAX(b.further_details) \"Further Details\", 
            to_char(to_timestamp(MAX(b.valid_start_date),'YYYY-MM-DD'),'DD-Mon-YYYY') \"Start Date \", 
            to_char(to_timestamp(MAX(b.valid_end_date),'YYYY-MM-DD'),'DD-Mon-YYYY') \"End Date \",
            MAX(a.lnkd_firm_org_id),
            string_agg(distinct org.get_div_name(g.div_id),', '),
            string_agg(distinct org.get_site_code_desc(h.location_id),', '),
            string_agg(distinct org.get_job_name(c.job_id),', '),
            string_agg(distinct org.get_grade_name(d.grade_id),', '),
            string_agg(distinct org.get_pos_name(e.position_id),', '),
            string_agg(distinct prs.get_prsn_name(f.supervisor_prsn_id),', '),
            prs.get_prsn_name(MAX(a.person_id))
            FROM prs.prsn_names_nos a  
            LEFT OUTER JOIN pasn.prsn_prsntyps b ON (a.person_id = b.person_id and 
           b.valid_start_date = (SELECT MAX(z.valid_start_date) from pasn.prsn_prsntyps z where z.person_id = a.person_id))  
            LEFT OUTER JOIN pasn.prsn_jobs c  ON (a.person_id = c.person_id and (now() between to_timestamp(c.valid_start_date,'YYYY-MM-DD') and to_timestamp(c.valid_end_date,'YYYY-MM-DD'))) 
            LEFT OUTER JOIN pasn.prsn_grades d    ON (a.person_id = d.person_id and (now() between to_timestamp(d.valid_start_date,'YYYY-MM-DD') and to_timestamp(d.valid_end_date,'YYYY-MM-DD'))) 
            LEFT OUTER JOIN pasn.prsn_positions e ON (a.person_id = e.person_id and (now() between to_timestamp(e.valid_start_date,'YYYY-MM-DD') and to_timestamp(e.valid_end_date,'YYYY-MM-DD')))   
            LEFT OUTER JOIN pasn.prsn_supervisors f ON (a.person_id = f.person_id and (now() between to_timestamp(f.valid_start_date,'YYYY-MM-DD') and to_timestamp(f.valid_end_date,'YYYY-MM-DD')))    
            LEFT OUTER JOIN pasn.prsn_divs_groups g  ON (a.person_id = g.person_id and (now() between to_timestamp(g.valid_start_date,'YYYY-MM-DD') and to_timestamp(g.valid_end_date,'YYYY-MM-DD')))  
            LEFT OUTER JOIN pasn.prsn_locations h ON (a.person_id = h.person_id and (now() between to_timestamp(h.valid_start_date,'YYYY-MM-DD') and to_timestamp(h.valid_end_date,'YYYY-MM-DD')))      
    WHERE (a.person_id=$pkID)";
    $result = executeSQLNoParams($strSql);
    return $result;
}

function getAllBnftsPrs($searchWord, $offset, $limit_size, $prsnid)
{
    $strSql = "SELECT a.item_id, a.item_pssbl_value_id, 
to_char(to_timestamp(a.valid_start_date,'YYYY-MM-DD'),'DD-Mon-YYYY'), 
to_char(to_timestamp(a.valid_end_date,'YYYY-MM-DD'),'DD-Mon-YYYY'), 
a.row_id, b.item_maj_type, b.pay_run_priority, b.item_code_name,
org.get_payitm_valnm(a.item_pssbl_value_id) 
            FROM pasn.prsn_bnfts_cntrbtns a, org.org_pay_items b 
            WHERE ((a.item_id=b.item_id) and (a.person_id = " . $prsnid .
        ") and (b.item_code_name ilike '" . loc_db_escape_string($searchWord) .
        "' or org.get_payitm_valnm(a.item_pssbl_value_id) ilike '" . loc_db_escape_string($searchWord) .
        "')) ORDER BY b.item_maj_type, b.pay_run_priority, b.item_code_name LIMIT " . $limit_size .
        " OFFSET " . abs($offset * $limit_size);

    $result = executeSQLNoParams($strSql);
    return $result;
}

function getAllBnftsPrsTtl($searchWord, $prsnid)
{
    $strSql = "SELECT count(1) 
            FROM pasn.prsn_bnfts_cntrbtns a, org.org_pay_items b  
            WHERE ((a.item_id=b.item_id) and (a.person_id = " . $prsnid .
        ") and (b.item_code_name ilike '" . loc_db_escape_string($searchWord) .
        "' or org.get_payitm_valnm(a.item_pssbl_value_id) ilike '" . loc_db_escape_string($searchWord) .
        "'))";

    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return 0;
}

function createBnftsPrs($prsnid, $itmid, $itm_val_id, $strtdte, $enddte)
{
    global $usrID;
    if ($strtdte != "") {
        $strtdte = cnvrtDMYToYMD($strtdte);
    }
    if ($strtdte != "") {
        $enddte = cnvrtDMYToYMD($enddte);
    }
    $insSQL = "INSERT INTO pasn.prsn_bnfts_cntrbtns(" .
        "person_id, item_id, item_pssbl_value_id, valid_start_date, valid_end_date, " .
        "created_by, creation_date, last_update_by, last_update_date) " .
        "VALUES (" . $prsnid . ", " . $itmid .
        ", " . $itm_val_id . ", '" . loc_db_escape_string($strtdte) .
        "', '" . loc_db_escape_string($enddte) .
        "', " . $usrID . ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), " . $usrID .
        ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'))";
    return execUpdtInsSQL($insSQL);
}

function updateBnftsPrs($prsnid, $rowid, $itm_val_id, $strtdte, $enddte)
{
    global $usrID;
    if ($strtdte != "") {
        $strtdte = cnvrtDMYToYMD($strtdte);
    }
    if ($strtdte != "") {
        $enddte = cnvrtDMYToYMD($enddte);
    }
    $updtSQL = "UPDATE pasn.prsn_bnfts_cntrbtns " .
        "SET person_id=" . $prsnid . ", item_pssbl_value_id=" . $itm_val_id .
        ", valid_start_date='" . loc_db_escape_string($strtdte) .
        "', valid_end_date='" . loc_db_escape_string($enddte) . "', " .
        "last_update_by=" . $usrID . ", last_update_date=to_char(now(),'YYYY-MM-DD HH24:MI:SS') " .
        "WHERE row_id=" . $rowid;
    return execUpdtInsSQL($updtSQL);
}

function updateItmValsPrs($rowid, $itm_val_id)
{
    global $usrID;
    $updtSQL = "UPDATE pasn.prsn_bnfts_cntrbtns " .
        "SET item_pssbl_value_id=" . $itm_val_id .
        ", last_update_by=" . $usrID . ", last_update_date=to_char(now(),'YYYY-MM-DD HH24:MI:SS') " .
        "WHERE row_id=" . $rowid;
    return execUpdtInsSQL($updtSQL);
}

function createBank($prsnid, $brnch, $bnknm, $accntnm, $accntno, $accntyp, $netportion, $uom)
{
    global $usrID;
    if (strlen($bnknm) > 200) {
        $bnknm = substr($bnknm, 0, 200);
    }
    if (strlen($brnch) > 200) {
        $brnch = substr($brnch, 0, 200);
    }
    if (strlen($accntno) > 200) {
        $accntno = substr($accntno, 0, 200);
    }
    if (strlen($accntnm) > 200) {
        $accntnm = substr($accntnm, 0, 200);
    }
    if (strlen($accntyp) > 100) {
        $accntyp = substr($accntyp, 0, 100);
    }
    if (strlen($uom) > 10) {
        $uom = substr($uom, 0, 10);
    }
    $insSQL = "INSERT INTO pasn.prsn_bank_accounts(" .
        "account_name, account_number, net_pay_portion, " .
        "portion_uom, created_by, creation_date, last_update_by, last_update_date, " .
        "person_id, bank_name, bank_branch, account_type) " .
        "VALUES ('" . loc_db_escape_string($accntnm) . "', '" . loc_db_escape_string($accntno) . "'" .
        ", " . $netportion . ", '" . loc_db_escape_string($uom) .
        "', " . $usrID . ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), " . $usrID .
        ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), " . $prsnid .
        ", '" . loc_db_escape_string($bnknm) . "', '" . loc_db_escape_string($brnch) . "', '" . loc_db_escape_string($accntyp) . "')";
    return execUpdtInsSQL($insSQL);
}

function updateAccount($prsnid, $prsn_accntid, $brnch, $bnknm, $accntnm, $accntno, $accntyp, $netportion, $uom)
{
    global $usrID;
    $updtSQL = "UPDATE pasn.prsn_bank_accounts " .
        "SET account_name ='" . loc_db_escape_string($accntnm) .
        "', account_number ='" . loc_db_escape_string($accntno) .
        "' , bank_name = '" . loc_db_escape_string($bnknm) .
        "', bank_branch ='" . loc_db_escape_string($brnch) .
        "' , account_type ='" . loc_db_escape_string($accntyp) .
        "' , person_id=" . $prsnid .
        ", net_pay_portion=" . $netportion .
        ", portion_uom='" . loc_db_escape_string($uom) .
        "', last_update_by=" . $usrID .
        ", last_update_date=to_char(now(),'YYYY-MM-DD HH24:MI:SS') " .
        "WHERE prsn_accnt_id=" . $prsn_accntid;
    return execUpdtInsSQL($updtSQL);
}

function deletePayItmPrs($row_id, $locID)
{
    $delSQL = "DELETE FROM pasn.prsn_bnfts_cntrbtns WHERE row_id = " . $row_id;
    $affctd = execUpdtInsSQL($delSQL, "Person's Local ID = " . $locID);
    if ($affctd > 0) {
        $dsply = "Successfully Deleted the ff Records-";
        $dsply .= "<br/>$affctd Person Item(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Deleted!";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function deleteAccount($prsn_accntid, $locID)
{
    $delSQL = "DELETE FROM pasn.prsn_bank_accounts WHERE prsn_accnt_id = " . $prsn_accntid;
    $affctd = execUpdtInsSQL($delSQL, "Person's Local ID = " . $locID);
    if ($affctd > 0) {
        $dsply = "Successfully Deleted the ff Records-";
        $dsply .= "<br/>$affctd Person Account(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Deleted!";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function getBlsItmLtstDailyBalsPrs($balsItmID, $prsn_id, $balsDate, $orgID)
{
    $balsDate = cnvrtDMYToYMD($balsDate);
    $res = 0;
    $strSql = "";
    $usesSQL = getGnrlRecNm("org.org_pay_items", "item_id", "uses_sql_formulas", $balsItmID);
    if ($usesSQL != "1") {
        $strSql = "SELECT a.bals_amount " .
            "FROM pay.pay_balsitm_bals a " .
            "WHERE(to_timestamp(a.bals_date,'YYYY-MM-DD') <=  to_timestamp('" . $balsDate .
            "','YYYY-MM-DD') and a.bals_itm_id = " . $balsItmID . " and a.person_id = " . $prsn_id .
            ") ORDER BY to_timestamp(a.bals_date,'YYYY-MM-DD') DESC LIMIT 1 OFFSET 0";

        $result = executeSQLNoParams($strSql);
        while ($row = loc_db_fetch_array($result)) {
            $res = (float) $row[0];
        }
    } else {
        $valSQL = getItmValSQL(getPrsnItmVlIDPrs($prsn_id, $balsItmID));
        if ($valSQL == "") {
        } else {
            try {
                $res = exctItmValSQL(
                    $valSQL,
                    $prsn_id,
                    $orgID,
                    $balsDate,
                    -1,
                    -1
                );
            } catch (Exception $ex) {
            }
        }
    }
    return $res;
}

function getPrsnItmVlIDPrs($prsnID, $itmID)
{
    $dateStr = getDB_Date_time();
    $strSql = "Select a.item_pssbl_value_id FROM pasn.prsn_bnfts_cntrbtns a where((a.person_id = " .
        $prsnID . ") and (a.item_id = " . $itmID . ") and (to_timestamp('" . $dateStr . "'," .
        "'YYYY-MM-DD HH24:MI:SS') between to_timestamp(valid_start_date," .
        "'YYYY-MM-DD 00:00:00') AND to_timestamp(valid_end_date,'YYYY-MM-DD 23:59:59')))";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -100000;
}

function getAllAccounts($prsnid)
{
    $strSql = "SELECT bank_name, bank_branch, account_name, account_number, " .
        "account_type, net_pay_portion, portion_uom, prsn_accnt_id " .
        "FROM pasn.prsn_bank_accounts WHERE ((person_id = " . $prsnid .
        ")) ORDER BY prsn_accnt_id DESC";
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_One_ItmStDet($itmStID, $offset, $limit_size)
{
    $itmSQL = getGnrlRecNm("pay.pay_itm_sets_hdr", "hdr_id", "sql_query", $itmStID);
    $strSql = "";
    $mnlSQL = "";
    $whereCls = "";
    $mnlSQL = "SELECT a.item_id, b.item_code_name, b.item_value_uom, " .
        "a.to_do_trnsctn_type, a.det_id " .
        "FROM pay.pay_itm_sets_det a , org.org_pay_items b " .
        "WHERE((a.hdr_id = " . $itmStID . ") and (a.item_id = b.item_id) and (b.is_enabled = '1')) ORDER BY b.pay_run_priority LIMIT " . $limit_size .
        " OFFSET " . (abs($offset * $limit_size));
    $strSql = "SELECT tbl1.item_id, tbl1.item_code_name, tbl1.item_value_uom, tbl1.trns_typ, -1 " .
        "FROM (" . $itmSQL . ") tbl1, org.org_pay_items a " .
        "WHERE ((tbl1.item_id = a.item_id) and (a.is_enabled = '1')) " .
        "ORDER BY a.pay_run_priority LIMIT " . $limit_size .
        " OFFSET " . (abs($offset * $limit_size));
    if ($itmSQL == "") {
        $strSql = $mnlSQL;
    }
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_One_ItmStDet1($itmStID)
{
    $itmSQL = getGnrlRecNm("pay.pay_itm_sets_hdr", "hdr_id", "sql_query", $itmStID);
    $whereCls = "";
    $mnlSQL = "SELECT a.det_id, b.item_code_name, b.item_value_uom, " .
        "a.to_do_trnsctn_type, a.item_id, pay.get_first_itmval_id(a.item_id), b.item_maj_type, b.item_min_type, b.allow_value_editing " .
        "FROM pay.pay_itm_sets_det a , org.org_pay_items b " .
        "WHERE((a.hdr_id = " . $itmStID . ") and (a.item_id = b.item_id) and (b.is_enabled = '1')) ORDER BY b.pay_run_priority ";

    $strSql = "SELECT -1, tbl1.item_code_name, tbl1.item_value_uom, tbl1.trns_typ, 
     tbl1.item_id, pay.get_first_itmval_id(tbl1.item_id), a.item_maj_type, a.item_min_type, a.allow_value_editing " .
        "FROM (" . $itmSQL . ") tbl1, org.org_pay_items a " .
        "WHERE ((tbl1.item_id = a.item_id) and (a.is_enabled = '1')) " .
        "ORDER BY a.pay_run_priority ";
    if ($itmSQL == "") {
        $strSql = $mnlSQL;
    }
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_OnePrs_ItmStDet($prsnID, $itmStID)
{
    $itmSQL = getGnrlRecNm("pay.pay_itm_sets_hdr", "hdr_id", "sql_query", $itmStID);
    $strSql = "";
    $mnlSQL = "";
    $mnlSQL = "SELECT a.det_id, b.item_code_name, b.item_value_uom, " .
        "a.to_do_trnsctn_type, a.item_id, c.item_pssbl_value_id, b.item_maj_type, b.item_min_type, b.allow_value_editing " .
        "FROM pay.pay_itm_sets_det a , org.org_pay_items b, pasn.prsn_bnfts_cntrbtns c " .
        "WHERE(a.hdr_id = " . $itmStID . ") and (a.item_id = b.item_id) and (b.is_enabled = '1') and " .
        "(a.item_id = c.item_id) AND (c.person_id = " . $prsnID .
        ") and (now() between to_timestamp(c.valid_start_date|| ' 00:00:00','YYYY-MM-DD HH24:MI:SS') " .
        "AND to_timestamp(c.valid_end_date || ' 23:59:59','YYYY-MM-DD HH24:MI:SS')) ORDER BY b.pay_run_priority, b.item_code_name LIMIT 100 OFFSET 0";

    $strSql = "SELECT -1, tbl1.item_code_name, tbl1.item_value_uom, tbl1.trns_typ, tbl1.item_id, b.item_pssbl_value_id, a.item_maj_type, a.item_min_type, a.allow_value_editing " .
        "FROM (" . $itmSQL . ") tbl1, org.org_pay_items a, pasn.prsn_bnfts_cntrbtns b " .
        "WHERE ((tbl1.item_id = a.item_id) and (a.item_id=b.item_id ) and (a.is_enabled = '1') AND (b.person_id = " . $prsnID .
        ") and (now() between to_timestamp(b.valid_start_date|| ' 00:00:00','YYYY-MM-DD HH24:MI:SS') " .
        "AND to_timestamp(b.valid_end_date || ' 23:59:59','YYYY-MM-DD HH24:MI:SS'))) " .
        "ORDER BY a.item_maj_type DESC, a.pay_run_priority, a.item_code_name";
    if ($itmSQL == "") {
        $strSql = $mnlSQL;
    }

    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_AllItmStDet($itmStID)
{
    $itmSQL = getGnrlRecNm("pay.pay_itm_sets_hdr", "hdr_id", "sql_query", $itmStID);
    $strSql = "";
    $mnlSQL = "SELECT a.item_id, b.item_code_name, b.item_value_uom, " .
        "a.to_do_trnsctn_type, b.item_maj_type, b.item_min_type, a.det_id " .
        "FROM pay.pay_itm_sets_det a , org.org_pay_items b " .
        "WHERE((a.hdr_id = " . $itmStID . ") and (a.item_id = b.item_id) and (b.is_enabled = '1')) ORDER BY b.pay_run_priority";

    $strSql = "SELECT tbl1.item_id, tbl1.item_code_name, tbl1.item_value_uom, tbl1.trns_typ, a.item_maj_type, a.item_min_type, -1 det_id " .
        "FROM (" . $itmSQL . ") tbl1, org.org_pay_items a " .
        "WHERE ((tbl1.item_id = a.item_id) and (a.is_enabled = '1')) " .
        "ORDER BY a.pay_run_priority";
    if ($itmSQL == "") {
        $strSql = $mnlSQL;
    }
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_AllEditblItmStDet($itmStID)
{
    $itmSQL = getGnrlRecNm("pay.pay_itm_sets_hdr", "hdr_id", "sql_query", $itmStID);
    $strSql = "";
    $mnlSQL = "SELECT a.item_id, b.item_code_name, b.item_value_uom, " .
        "a.to_do_trnsctn_type, b.item_maj_type, b.item_min_type, b.uses_sql_formulas, b.is_retro_element " .
        "FROM pay.pay_itm_sets_det a , org.org_pay_items b " .
        "WHERE((a.hdr_id = " . $itmStID . ") and (a.item_id = b.item_id) and (b.is_enabled = '1' and b.allow_value_editing='1')) ORDER BY b.pay_run_priority";

    $strSql = "SELECT tbl1.item_id, tbl1.item_code_name, tbl1.item_value_uom, tbl1.trns_typ, a.item_maj_type, a.item_min_type, a.uses_sql_formulas, a.is_retro_element " .
        "FROM (" . $itmSQL . ") tbl1, org.org_pay_items a " .
        "WHERE ((tbl1.item_id = a.item_id) and (a.is_enabled = '1' and a.allow_value_editing='1')) " .
        "ORDER BY a.pay_run_priority";
    if ($itmSQL == "") {
        $strSql = $mnlSQL;
    }
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_Total_ItmsDet($itmStID)
{
    $itmSQL = getGnrlRecNm("pay.pay_itm_sets_hdr", "hdr_id", "sql_query", $itmStID);
    $strSql = "";
    $mnlSQL = "";
    $whereCls = "";
    $mnlSQL = "SELECT count(1) " .
        "FROM pay.pay_itm_sets_det a , org.org_pay_items b " .
        "WHERE((a.hdr_id = " . $itmStID . ") and (a.item_id = b.item_id) and (b.is_enabled = '1'))";
    $strSql = "SELECT count(1) " .
        "FROM (" . $itmSQL . ") tbl1, org.org_pay_items a " .
        "WHERE ((tbl1.item_id = a.item_id) and (a.is_enabled = '1'))";
    if ($itmSQL == "") {
        $strSql = $mnlSQL;
    }

    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return 0;
}

function get_Basic_ItmSt($searchWord, $searchIn, $offset, $limit_size, $orgID)
{
    $strSql = "";
    if ($searchIn == "Item Set Name") {
        $strSql = "SELECT a.hdr_id, a.itm_set_name, a.itm_set_desc, a.is_enabled, a.is_default, a.uses_sql , a.sql_query " .
            "FROM pay.pay_itm_sets_hdr a " .
            "WHERE ((a.itm_set_name ilike '" . loc_db_escape_string($searchWord) .
            "') AND (org_id = " . $orgID . ")) ORDER BY a.hdr_id DESC LIMIT " . $limit_size .
            " OFFSET " . (abs($offset * $limit_size));
    } else {
        $strSql = "SELECT a.hdr_id, a.itm_set_name, a.itm_set_desc, a.is_enabled, a.is_default, a.uses_sql , a.sql_query " .
            "FROM pay.pay_itm_sets_hdr a " .
            "WHERE ((a.itm_set_desc ilike '" . loc_db_escape_string($searchWord) .
            "') AND (org_id = " . $orgID . ")) ORDER BY a.hdr_id DESC LIMIT " . $limit_size .
            " OFFSET " . (abs($offset * $limit_size));
    }
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_Total_ItmSt($searchWord, $searchIn, $orgID)
{
    $strSql = "";
    if ($searchIn == "Item Set Name") {
        $strSql = "SELECT count(1) " .
            "FROM pay.pay_itm_sets_hdr a " .
            "WHERE ((a.itm_set_name ilike '" . loc_db_escape_string($searchWord) .
            "') AND (org_id = " . $orgID . "))";
    } else {
        $strSql = "SELECT count(1)  " .
            "FROM pay.pay_itm_sets_hdr a " .
            "WHERE ((a.itm_set_desc ilike '" . loc_db_escape_string($searchWord) .
            "') AND (org_id = " . $orgID . "))";
    }
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return 0;
}

function get_ItemSetDetail($itmStID)
{
    $strSql = "SELECT a.hdr_id, a.itm_set_name, a.itm_set_desc, a.is_enabled, a.is_default, a.uses_sql , a.sql_query " .
        "FROM pay.pay_itm_sets_hdr a " .
        "WHERE ((a.hdr_id = " . $itmStID . "))";
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_PrsnSets($searchFor, $searchIn, $offset, $limit_size, $orgID)
{
    $wherecls = "";
    if ($searchIn === "Name") {
        $wherecls = "(a.prsn_set_hdr_name ilike '" .
            loc_db_escape_string($searchFor) . "' or a.prsn_set_hdr_desc ilike '" .
            loc_db_escape_string($searchFor) . "') and ";
    } else {
        $wherecls = "(a.prsn_set_hdr_desc ilike '" .
            loc_db_escape_string($searchFor) . "') and ";
    }

    $strSql = "SELECT a.prsn_set_hdr_id, a.prsn_set_hdr_name, a.prsn_set_hdr_desc, a.is_enabled, a.sql_query, a.is_default, a.uses_sql " .
        "FROM pay.pay_prsn_sets_hdr a WHERE " . $wherecls .
        "(a.org_id = " . $orgID . ") "
        . "ORDER BY a.prsn_set_hdr_id DESC LIMIT " . $limit_size .
        " OFFSET " . abs($offset * $limit_size);
    //echo $strSql;
    $result = executeSQLNoParams($strSql);

    return $result;
}

function get_PrsnSetsTtl($searchFor, $searchIn, $orgID)
{
    $wherecls = "";
    if ($searchIn === "Name") {
        $wherecls = "(a.prsn_set_hdr_name ilike '" .
            loc_db_escape_string($searchFor) . "' or a.prsn_set_hdr_desc ilike '" .
            loc_db_escape_string($searchFor) . "') and ";
    } else {
        //Description
        $wherecls = "(a.prsn_set_hdr_desc ilike '" .
            loc_db_escape_string($searchFor) . "') and ";
    }
    $strSql = "SELECT count(1) from pay.pay_prsn_sets_hdr a WHERE " . $wherecls .
        "(org_id = " . $orgID . ")";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return 0;
}

function get_PrsnSetsDt($searchFor, $searchIn, $offset, $limit_size, $prsStID)
{
    $prsSQL = getPrsStSQL($prsStID);
    $strSql = "";
    $mnlSQL = "Select distinct a.person_id, a.local_id_no, trim(a.title || ' ' || a.sur_name || " .
        "', ' || a.first_name || ' ' || a.other_names) full_name, b.prsn_set_det_id " .
        "from prs.prsn_names_nos a, pay.pay_prsn_sets_det b " .
        "WHERE ((a.person_id = b.person_id) and (b.prsn_set_hdr_id = " . $prsStID .
        " ) and (a.local_id_no ilike '" .
        loc_db_escape_string($searchFor) . "' or trim(a.title || ' ' || a.sur_name || " .
        "', ' || a.first_name || ' ' || a.other_names) ilike '" .
        loc_db_escape_string($searchFor) . "')) ORDER BY a.local_id_no DESC LIMIT " . $limit_size .
        " OFFSET " . abs($offset * $limit_size);
    $strSql = "select * from (" . $prsSQL . ") tbl1 "
        . "WHERE (tbl1.local_id_no ilike '" .
        loc_db_escape_string($searchFor) . "' or tbl1.full_name ilike '" .
        loc_db_escape_string($searchFor) . "') ORDER BY tbl1.local_id_no DESC LIMIT " . $limit_size .
        " OFFSET " . abs($offset * $limit_size);
    if ($prsSQL == "") {
        $strSql = $mnlSQL;
    }
    $result = executeSQLNoParams($strSql);

    return $result;
}

function get_PrsnSetsDtTtl($searchFor, $searchIn, $prsStID)
{
    $prsSQL = getPrsStSQL($prsStID);
    $strSql = "";
    $mnlSQL = "Select distinct a.person_id, a.local_id_no, trim(a.title || ' ' || a.sur_name || " .
        "', ' || a.first_name || ' ' || a.other_names) full_name, b.prsn_set_det_id " .
        "from prs.prsn_names_nos a, pay.pay_prsn_sets_det b " .
        "WHERE ((a.person_id = b.person_id) and (b.prsn_set_hdr_id = " . $prsStID .
        " ))";
    $strSql = "select count(1) from (" . $prsSQL . ") tbl1 "
        . "WHERE (tbl1.local_id_no ilike '" .
        loc_db_escape_string($searchFor) . "' or tbl1.full_name ilike '" .
        loc_db_escape_string($searchFor) . "') ";
    if ($prsSQL == "") {
        $strSql = "select count(1) from (" . $mnlSQL . ") tbl1 "
            . "WHERE (tbl1.local_id_no ilike '" .
            loc_db_escape_string($searchFor) . "' or tbl1.full_name ilike '" .
            loc_db_escape_string($searchFor) . "') ";
    }
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return 0;
}

function get_PrsnSetDetail($prsnSetID)
{
    $strSql = "SELECT 
        prsn_set_hdr_id \"Person Set ID\", 
        prsn_set_hdr_name \"Person Set Name\", 
        prsn_set_hdr_desc \"Person Set Description\", 
       (CASE WHEN uses_sql='1' THEN 'YES' ELSE 'NO' END) \"Uses SQL?\", 
        sql_query \"SQL Query\", 
       (CASE WHEN is_default='1' THEN 'YES' ELSE 'NO' END) \"Is Default Set?\", 
       (CASE WHEN is_enabled='1' THEN 'YES' ELSE 'NO' END) \"Is Enabled?\" 
       FROM pay.pay_prsn_sets_hdr a " .
        "WHERE (prsn_set_hdr_id = $prsnSetID)";
    $result = executeSQLNoParams($strSql);

    return $result;
}

function doesItmSetHvRole($setID, $role_id)
{
    $strSql = "SELECT pay_roles_id FROM pay.pay_sets_allwd_roles " .
        "WHERE itm_set_id = " . $setID . " and user_role_id = " . $role_id;
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return -1;
}

function doesPrsnSetHvRole($setID, $role_id)
{
    $strSql = "SELECT pay_roles_id FROM pay.pay_sets_allwd_roles " .
        "WHERE prsn_set_id = " . $setID . " and user_role_id = " . $role_id;
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return -1;
}

function createPayRole($itmSetID, $prsSetID, $roleID)
{
    global $usrID;
    $dateStr = getDB_Date_time();
    $insSQL = "INSERT INTO pay.pay_sets_allwd_roles(" .
        "prsn_set_id, itm_set_id, user_role_id, created_by, creation_date) " .
        "VALUES (" . $prsSetID . ", " . $itmSetID . ", " . $roleID .
        ", " . $usrID . ", '" . $dateStr .
        "')";
    execUpdtInsSQL($insSQL);
}

function get_AllRoles($itmsetID)
{
    $strSql = "SELECT a.user_role_id, b.role_name, a.pay_roles_id " .
        "FROM pay.pay_sets_allwd_roles a, sec.sec_roles b " .
        "WHERE a.itm_set_id = " . $itmsetID . " and a.itm_set_id>0 and a.user_role_id = b.role_id";
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_AllRoles1($prssetID)
{
    $strSql = "SELECT a.user_role_id, b.role_name, a.pay_roles_id " .
        "FROM pay.pay_sets_allwd_roles a, sec.sec_roles b " .
        "WHERE a.prsn_set_id = " . $prssetID . " and a.prsn_set_id>0 and a.user_role_id = b.role_id";
    $result = executeSQLNoParams($strSql);
    return $result;
}

function deletePersonSet($hdrid, $setNm)
{
    $insSQL = "DELETE FROM pay.pay_prsn_sets_hdr WHERE prsn_set_hdr_id = " . $hdrid;
    $affctd = execUpdtInsSQL($insSQL, "Person Set Name=$setNm");
    $insSQL1 = "DELETE FROM pay.pay_prsn_sets_det WHERE prsn_set_hdr_id = " . $hdrid;
    $affctd1 = execUpdtInsSQL($insSQL1, "Person Set Name=$setNm");

    if ($affctd > 0) {
        $dsply = "Successfully Deleted the ff Records-";
        $dsply .= "<br/>$affctd Person Set(s)!";
        $dsply .= "<br/>$affctd1 Person Set Detail(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Deleted!";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">

    $dsply</p>";
    }
}

function deletePersonSetRole($hdrid, $roleNm)
{
    $affctd = deleteGnrlRecs($hdrid, "pay.pay_sets_allwd_roles", "pay_roles_id", "Linked Role Set Name = " . $roleNm);

    if ($affctd > 0) {
        $dsply = "Successfully Deleted the ff Records-";
        $dsply .= "<br/>$affctd Linked Role Set(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Deleted!";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">

    $dsply</p>";
    }
}

function deletePersonSetDet($detID, $prsnLocID)
{
    $insSQL = "DELETE FROM pay.pay_prsn_sets_det WHERE prsn_set_det_id = " . $detID;
    $affctd = execUpdtInsSQL($insSQL, "Person Loc. ID:$prsnLocID");

    if ($affctd > 0) {
        $dsply = "Successfully Deleted the ff Records-";
        $dsply .= "<br/>$affctd Person Set Detail(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Deleted!";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">

    $dsply</p>";
    }
}

function createPersonSetDet($hdrID, $prsID)
{
    global $usrID;
    $dateStr = getDB_Date_time();
    $insSQL = "INSERT INTO pay.pay_prsn_sets_det(" .
        "prsn_set_hdr_id, person_id, created_by, creation_date, " .
        "last_update_by, last_update_date) " .
        "VALUES (" . $hdrID .
        ", " . $prsID .
        ", " . $usrID . ", '" . $dateStr .
        "', " . $usrID . ", '" . $dateStr .
        "')";
    return execUpdtInsSQL($insSQL);
}

function doesPrsStHvPrs($hdrID, $prsnID)
{
    $strSql = "Select a.prsn_set_det_id FROM pay.pay_prsn_sets_det a where((a.prsn_set_hdr_id = " .
        $hdrID . ") and (a.person_id = " . $prsnID . "))";
    $result = executeSQLNoParams($strSql);
    if (loc_db_num_rows($result) > 0) {
        return true;
    }
    return false;
}

function doesItmStHvItm($hdrID, $itmID)
{
    $strSql = "Select a.det_id FROM pay.pay_itm_sets_det a where((a.hdr_id = " .
        $hdrID . ") and (a.item_id = " . $itmID . "))";
    $result = executeSQLNoParams($strSql);
    if (loc_db_num_rows($result) > 0) {
        return true;
    }
    return false;
}

function isItmStInUse($itmstID)
{
    $strSql = "SELECT a.mass_pay_id " .
        "FROM pay.pay_mass_pay_run_hdr a " .
        "WHERE(a.itm_st_id = " . $itmstID . ") LIMIT 1 OFFSET 0";
    $result = executeSQLNoParams($strSql);
    if (loc_db_num_rows($result) > 0) {
        return true;
    }
    return false;
}

function isPrsStInUse($prsstID)
{
    $strSql = "SELECT a.mass_pay_id " .
        "FROM pay.pay_mass_pay_run_hdr a " .
        "WHERE(a.prs_st_id = " . $prsstID . ") LIMIT 1 OFFSET 0";
    $result = executeSQLNoParams($strSql);
    if (loc_db_num_rows($result) > 0) {
        return true;
    }
    $strSql = "SELECT a.srvy_id " .
        "FROM self.self_surveys a " .
        "WHERE(a.eligible_prsn_set_id = " . $prsstID . " or a.exclsn_lst_prsn_set_id = " . $prsstID . ") LIMIT 1 OFFSET 0";
    $result = executeSQLNoParams($strSql);
    if (loc_db_num_rows($result) > 0) {
        return true;
    }
    return false;
}

function computeMathExprsn($exprSn)
{
    $strSql = "SELECT " . loc_db_escape_string(str_replace(",", "", str_replace("=", "", str_replace("/", "::float/", $exprSn))));
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return 0;
}

function getNewMsPyID()
{
    $strSql = "select  last_value from pay.pay_mass_pay_run_hdr_mass_pay_id_seq";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return ((float) $row[0]) + 1;
    }
    return 0;
}

function createMsPayAtchdVal($mspyid, $psrnID, $itmid, $amnt, $pssblvalid, $dteErnd)
{
    global $usrID;
    if ($dteErnd != "") {
        $dteErnd = cnvrtDMYTmToYMDTm($dteErnd);
    }
    $insSQL = "INSERT INTO pay.pay_value_sets_det(
            mass_pay_id, person_id, item_id, value_to_use, 
            created_by, creation_date, last_update_by, last_update_date, 
            itm_pssbl_val_id, date_earned) " .
        "VALUES (" . $mspyid . ", " . $psrnID . ", " . $itmid .
        ", " . $amnt . ", " . $usrID . ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), " .
        $usrID . ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), " . $pssblvalid . ", '" . $dteErnd . "')";
    return execUpdtInsSQL($insSQL);
}

function createMsPy(
    $orgid,
    $mspyname,
    $mspydesc,
    $trnsdte,
    $prstid,
    $itmstid,
    $glDate,
    $grpTyp,
    $grpID,
    $workPlcID,
    $workPlcSiteID,
    $entrdAmnt,
    $entrdAmntCrncyID,
    $chequeNum,
    $signCode,
    $is_quick_pay,
    $auto_asgn_itms,
    $payMassPyAplyAdvnc = "0",
    $payMassPyKeepExcss = "1"
) {
    global $usrID;
    if ($trnsdte != "") {
        $trnsdte = cnvrtDMYTmToYMDTm($trnsdte);
    }
    if ($glDate != "") {
        $glDate = cnvrtDMYTmToYMDTm($glDate);
    }
    $insSQL = "INSERT INTO pay.pay_mass_pay_run_hdr(" .
        "mass_pay_name, mass_pay_desc, created_by, creation_date, " .
        "last_update_by, last_update_date, run_status, mass_pay_trns_date, " .
        "prs_st_id, itm_st_id, org_id, sent_to_gl, gl_date, allwd_group_type, allwd_group_value,
             workplace_cstmr_id, workplace_cstmr_site_id, entered_amnt, entered_amt_crncy_id,
             cheque_card_num, sign_code, is_quick_pay, auto_asgn_itms, mspy_apply_advnc, mspy_keep_excess) " .
        "VALUES ('" . loc_db_escape_string($mspyname) .
        "', '" . loc_db_escape_string($mspydesc) .
        "', " . $usrID . ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), " . $usrID .
        ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), '0', '" . $trnsdte . "', " .
        $prstid . ", " . $itmstid . ", " . $orgid . ", '0', '" . $glDate .
        "', '" . loc_db_escape_string($grpTyp) .
        "', '" . loc_db_escape_string($grpID) .
        "', " . $workPlcID . ", " . $workPlcSiteID . ", " . $entrdAmnt . ", " .
        $entrdAmntCrncyID . ", '" . loc_db_escape_string($chequeNum) .
        "', '" . loc_db_escape_string($signCode) .
        "','" . cnvrtBoolToBitStr($is_quick_pay) .
        "','" . cnvrtBoolToBitStr($auto_asgn_itms) .
        "','" . cnvrtBoolToBitStr($payMassPyAplyAdvnc) .
        "','" . cnvrtBoolToBitStr($payMassPyKeepExcss) . "')";
    return execUpdtInsSQL($insSQL);
}

function updateMsPy(
    $mspyid,
    $mspyname,
    $mspydesc,
    $trnsdte,
    $prstid,
    $itmstid,
    $glDate,
    $grpTyp,
    $grpID,
    $workPlcID,
    $workPlcSiteID,
    $entrdAmnt,
    $entrdAmntCrncyID,
    $chequeNum,
    $signCode,
    $is_quick_pay,
    $auto_asgn_itms,
    $payMassPyAplyAdvnc = "0",
    $payMassPyKeepExcss = "1"
) {
    global $usrID;
    if ($trnsdte != "") {
        $trnsdte = cnvrtDMYTmToYMDTm($trnsdte);
    }
    if ($trnsdte != "") {
        $glDate = cnvrtDMYTmToYMDTm($glDate);
    }
    $updtSQL = "UPDATE pay.pay_mass_pay_run_hdr " .
        "SET mass_pay_name='" . loc_db_escape_string($mspyname) .
        "', mass_pay_desc='" . loc_db_escape_string($mspydesc) .
        "', mass_pay_trns_date = '" . loc_db_escape_string($trnsdte) .
        "', gl_date = '" . loc_db_escape_string($glDate) .
        "', last_update_by=" . $usrID .
        ", last_update_date=to_char(now(),'YYYY-MM-DD HH24:MI:SS'), prs_st_id = " . $prstid .
        ", itm_st_id = " . $itmstid .
        ", allwd_group_type='" . loc_db_escape_string($grpTyp) .
        "', allwd_group_value='" . loc_db_escape_string($grpID) .
        "', workplace_cstmr_id=" . $workPlcID . ",
                workplace_cstmr_site_id=" . $workPlcSiteID . ",
                entered_amnt=" . $entrdAmnt . ",
                entered_amt_crncy_id=" . $entrdAmntCrncyID . ",
                cheque_card_num='" . loc_db_escape_string($chequeNum) . "',
                sign_code='" . loc_db_escape_string($signCode) .
        "', is_quick_pay='" . cnvrtBoolToBitStr($is_quick_pay) .
        "', auto_asgn_itms='" . cnvrtBoolToBitStr($auto_asgn_itms) .
        "', mspy_apply_advnc='" . cnvrtBoolToBitStr($payMassPyAplyAdvnc) .
        "', mspy_keep_excess='" . cnvrtBoolToBitStr($payMassPyKeepExcss) .
        "' WHERE mass_pay_id = " . $mspyid;
    return execUpdtInsSQL($updtSQL);
}

function generateAttachdVals($p_msPyID)
{
    global $usrID;
    $strSql = "select pay.bulkSaveMassPayVals(" . $p_msPyID . "," . $usrID . ")";
    //echo $strSql;
    //SELECT pay.bulkSaveMassPayVals({:p_mass_py_id},{:usrID}) 
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return "ERROR:No Result";
}

function updtMsPayAtchdVal($valstdetid, $amnt)
{
    global $usrID;
    $insSQL = "UPDATE pay.pay_value_sets_det SET 
            value_to_use = " . $amnt . ", last_update_by= " . $usrID .
        ", last_update_date = to_char(now(),'YYYY-MM-DD HH24:MI:SS') WHERE value_set_det_id=" . $valstdetid;
    return execUpdtInsSQL($insSQL);
}

function getIntrnlPayReport($P_MASS_PAY_ID)
{
    $sqlStr = "SELECT a.mass_pay_id,
        a.run_status,
       gst.get_pssbl_val(b.crncy_id),
       to_char(to_timestamp(b.paymnt_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY'),
       a.mass_pay_desc,
       sec.get_usr_name(b.created_by),
       prs.get_prsn_name(b.person_id)|| ' (' || prs.get_prsn_loc_id(b.person_id)||')'
    FROM pay.pay_mass_pay_run_hdr a,
     pay.pay_itm_trnsctns b
WHERE (a.mass_pay_id =b.mass_pay_id AND a.mass_pay_id = " . $P_MASS_PAY_ID . " AND a.mass_pay_id >0)";
    $result = executeSQLNoParams($sqlStr);
    return $result;
}

function doesAtchdValHvPrsn($prsnid, $mspyid, $itmid, $dteEarned)
{
    $strSql = "SELECT value_set_det_id " .
        "FROM pay.pay_value_sets_det WHERE ((person_id = " . $prsnid .
        ") and (mass_pay_id = " . $mspyid . ") and (item_id = " . $itmid . ") and date_earned='" . $dteEarned . "')";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return ((float) $row[0]);
    }
    return -1;
}

function getAtchdValPrsnAmnt($prsnid, $mspyid, $itmid, &$dteErnd)
{
    $strSql = "SELECT value_to_use, date_earned " .
        "FROM pay.pay_value_sets_det WHERE ((person_id = " . $prsnid .
        ") and (mass_pay_id = " . $mspyid . ") and (item_id = " . $itmid . "))";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        $dteErnd = $row[1];
        return ((float) $row[0]);
    }
    return 0;
}

function getAtchdValPrsnAmnt1($prsnid, $mspyid, $itmid)
{
    $selSQL = "SELECT value_to_use, date_earned " .
        "FROM pay.pay_value_sets_det WHERE ((person_id = " . $prsnid .
        ") and (mass_pay_id = " . $mspyid . ") and (item_id = " . $itmid . "))";
    $result = executeSQLNoParams($selSQL);
    return $result;
}

function isMspyInUse($mspyID)
{
    $strSql = "SELECT a.mass_pay_id " .
        "FROM pay.pay_itm_trnsctns a, pay.pay_gl_interface b " .
        "WHERE a.mass_pay_id = " . $mspyID .
        " and b.source_trns_id = a.pay_trns_id and (b.gl_batch_id > 0 or 
 (a.pymnt_vldty_status = 'VALID' and a.src_py_trns_id <= 0)) LIMIT 1";

    $result = executeSQLNoParams($strSql);
    if (loc_db_num_rows($result) > 0) {
        return true;
    }
    return false;
}

function deleteBulkPayRun($payrunid, $payRunNm)
{
    global $orgID;
    $trnsCnt2 = 0;
    $trnsCnt3 = 0;
    $trnsCnt4 = 0;
    $payRnNm = getGnrlRecNm("pay.pay_mass_pay_run_hdr", "mass_pay_id", "mass_pay_name", $payrunid);
    $isBlkPayRn = (float) getGnrlRecNm("pay.pay_mass_pay_run_hdr", "mass_pay_id", "run_status", $payrunid);

    $orgnlMsPyID = -1;
    $rvrslmspyid = -1;
    if (strpos($payRnNm, " (Reversal)") !== FALSE) {
        $rvrslmspyid = $payrunid;
        $orgnlMsPyID = getMsPyID(str_replace(" (Reversal)", "", $payRnNm), $orgID);
        $payRnNm = str_replace(" (Reversal)", "", $payRnNm);
    } else {
        $orgnlMsPyID = $payrunid;
        $rvrslmspyid = getMsPyID($payRnNm . " (Reversal)", $orgID);
    }

    $strSql = "SELECT count(1) FROM pay.pay_itm_trnsctns a WHERE(a.pymnt_vldty_status='VALID' and a.mass_pay_id = " . $orgnlMsPyID . " and a.src_py_trns_id<=0)";
    $result1 = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result1)) {
        $trnsCnt2 = (float) $row[0];
    }
    $strSql2 = "SELECT count(1) FROM pay.pay_itm_trnsctns a WHERE(a.pymnt_vldty_status='VOID' and a.mass_pay_id = " . $rvrslmspyid . " and a.src_py_trns_id>0)";
    $result2 = executeSQLNoParams($strSql2);
    while ($row = loc_db_fetch_array($result2)) {
        $trnsCnt3 = (float) $row[0];
    }
    $strSql3 = "Select count(a.interface_id) from pay.pay_gl_interface a, pay.pay_itm_trnsctns b where a.source_trns_id = b.pay_trns_id and b.mass_pay_id=" . $orgnlMsPyID . "";
    $result3 = executeSQLNoParams($strSql3);
    while ($row = loc_db_fetch_array($result3)) {
        $trnsCnt4 = (float) $row[0];
    }
    $strSql31 = "Select count(a.interface_id) from pay.pay_gl_interface a, pay.pay_itm_trnsctns b where a.source_trns_id = b.pay_trns_id and b.mass_pay_id=" . $rvrslmspyid . "";
    $result31 = executeSQLNoParams($strSql31);
    while ($row = loc_db_fetch_array($result31)) {
        $trnsCnt4 += (float) $row[0];
    }
    $isSentToGl = (float) getGnrlRecNm("pay.pay_mass_pay_run_hdr", "mass_pay_id", "sent_to_gl", $orgnlMsPyID);
    $isSentToGl += (float) getGnrlRecNm("pay.pay_mass_pay_run_hdr", "mass_pay_id", "sent_to_gl", $rvrslmspyid);
    if (($isSentToGl > 0 && $trnsCnt4 > 0) || ($isBlkPayRn > 0 && ($trnsCnt2 > 0 || $trnsCnt3 > 0))) {
        $dsply = "No Record Deleted<br/>Cannot delete Payments which have been Run or Sent to GL and/or haven't been VOIDED!";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
    $delSQL211 = "DELETE FROM pay.pay_gl_interface WHERE gl_batch_id<=0 and source_trns_id IN (SELECT pay_trns_id from pay.pay_itm_trnsctns WHERE mass_pay_id =" . $rvrslmspyid . ")";
    $affctd211 = execUpdtInsSQL($delSQL211, "Run Name = " . $payRunNm . " (Reversal)");
    $delSQL21 = "DELETE FROM pay.pay_mass_pay_run_msgs WHERE process_id = " . $rvrslmspyid;
    $affctd21 = execUpdtInsSQL($delSQL21, "Run Name = " . $payRunNm . " (Reversal)");
    $delSQL20 = "DELETE FROM pay.pay_value_sets_det WHERE mass_pay_id = " . $rvrslmspyid;
    $affctd20 = execUpdtInsSQL($delSQL20, "Run Name = " . $payRunNm . " (Reversal)");
    $delSQL201 = "DELETE FROM pay.pay_itm_trnsctns WHERE mass_pay_id = " . $rvrslmspyid;
    $affctd201 = execUpdtInsSQL($delSQL201, "Run Name = " . $payRunNm . " (Reversal)");
    $delSQL2 = "DELETE FROM pay.pay_mass_pay_run_hdr WHERE mass_pay_id = " . $rvrslmspyid;
    $affctd2 = execUpdtInsSQL($delSQL2, "Run Name = " . $payRunNm . " (Reversal)");

    $delSQL212 = "DELETE FROM pay.pay_gl_interface WHERE gl_batch_id<=0 and source_trns_id IN (SELECT pay_trns_id from pay.pay_itm_trnsctns WHERE mass_pay_id =" . $orgnlMsPyID . ")";
    $affctd212 = execUpdtInsSQL($delSQL212, "Run Name = " . $payRunNm . " (Reversal)");
    $delSQL1 = "DELETE FROM pay.pay_mass_pay_run_msgs WHERE process_id = " . $orgnlMsPyID;
    $affctd1 = execUpdtInsSQL($delSQL1, "Run Name = " . $payRunNm);
    $delSQL0 = "DELETE FROM pay.pay_value_sets_det WHERE mass_pay_id = " . $orgnlMsPyID;
    $affctd0 = execUpdtInsSQL($delSQL0, "Run Name = " . $payRunNm);
    $delSQL01 = "DELETE FROM pay.pay_itm_trnsctns WHERE mass_pay_id = " . $orgnlMsPyID;
    $affctd01 = execUpdtInsSQL($delSQL01, "Run Name = " . $payRunNm);
    $delSQL = "DELETE FROM pay.pay_mass_pay_run_hdr WHERE mass_pay_id = " . $orgnlMsPyID;
    $affctd = execUpdtInsSQL($delSQL, "Run Name = " . $payRunNm);

    if (($affctd + $affctd2) > 0) {
        $dsply = "";
        $dsply .= "<br/>Successfully Executed the ff-";
        $dsply .= "<br/>Deleted " . ($affctd0 + $affctd20) . " Run Message(s)!";
        $dsply .= "<br/>Deleted " . ($affctd211 + $affctd212) . " Interface Entry(ies)!";
        $dsply .= "<br/>Deleted " . ($affctd1 + $affctd21) . " Attached Value(s)!";
        $dsply .= "<br/>Deleted " . ($affctd01 + $affctd201) . " Pay Transaction(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Deleted";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function deleteMsPayAtchdVal($valLnid, $runNm)
{
    $trnsCnt1 = 0;
    $payrunid = (float) getGnrlRecNm("pay.pay_value_sets_det", "value_set_det_id", "mass_pay_id", $valLnid);
    $isBlkPayRn = (float) getGnrlRecNm("pay.pay_mass_pay_run_hdr", "mass_pay_id", "run_status", $payrunid);
    $isSentToGl = (float) getGnrlRecNm("pay.pay_mass_pay_run_hdr", "mass_pay_id", "sent_to_gl", $payrunid);
    if ($isBlkPayRn > 0 || $isSentToGl > 0) {
        $trnsCnt1 += 1;
    }
    if (($trnsCnt1) > 0) {
        $dsply = "No Record Deleted<br/>Cannot delete Payments which have been Run or Sent to GL!";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
    $delSQL = "DELETE FROM pay.pay_value_sets_det WHERE value_set_det_id = " . $valLnid;
    $affctd = execUpdtInsSQL($delSQL, "Run Name = " . $runNm);
    if ($affctd > 0) {
        $dsply = "";
        $dsply .= "<br/>Successfully Executed the ff-";
        $dsply .= "<br/>Deleted $affctd Attached Value(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Deleted";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function get_One_MsPayAtchdVals($searchWord, $searchIn, $offset, $limit_size, $mspyID)
{
    $strSql = "";
    $whrcls = "";

    if ($searchIn == "Person Name/ID No.") {
        $whrcls = " AND (prs.get_prsn_name(a.person_id) ilike '" . loc_db_escape_string($searchWord) .
            "' or prs.get_prsn_loc_id(a.person_id) ilike '" . loc_db_escape_string($searchWord) .
            "')";
    } else if ($searchIn == "Item Name") {
        $whrcls = " AND (org.get_payitm_nm(a.item_id) ilike '" . loc_db_escape_string($searchWord) .
            "' or org.get_payitm_valnm(a.itm_pssbl_val_id) ilike '" . loc_db_escape_string($searchWord) .
            "')";
    }

    $strSql = "SELECT a.value_set_det_id, a.mass_pay_id, 
      a.person_id, prs.get_prsn_loc_id(a.person_id), prs.get_prsn_name(a.person_id), 
      a.item_id, org.get_payitm_nm(a.item_id),
      a.itm_pssbl_val_id, org.get_payitm_valnm(a.itm_pssbl_val_id), a.value_to_use, 
      org.get_payitm_mintyp(a.item_id), org.get_payitm_effct(a.item_id), b.uses_sql_formulas, b.allow_value_editing
      FROM pay.pay_value_sets_det a, org.org_pay_items b " .
        "WHERE((a.item_id=b.item_id and a.mass_pay_id = " . $mspyID . ")" . $whrcls . ") ORDER BY 4, 1 LIMIT " . $limit_size .
        " OFFSET " . (abs($offset * $limit_size));
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_Total_MsPayAtchdVals($searchWord, $searchIn, $mspyID)
{
    $strSql = "";
    $whrcls = "";
    if ($searchIn == "Person Name/ID No.") {
        $whrcls = " AND (prs.get_prsn_name(a.person_id) ilike '" . loc_db_escape_string($searchWord) .
            "' or prs.get_prsn_loc_id(a.person_id) ilike '" . loc_db_escape_string($searchWord) .
            "')";
    } else if ($searchIn == "Item Name") {
        $whrcls = " AND (org.get_payitm_nm(a.item_id) ilike '" . loc_db_escape_string($searchWord) .
            "' or org.get_payitm_valnm(a.itm_pssbl_val_id) ilike '" . loc_db_escape_string($searchWord) .
            "')";
    }
    $strSql = "SELECT count(1)
  FROM pay.pay_value_sets_det a " .
        "WHERE((a.mass_pay_id = " . $mspyID . ")" . $whrcls . ")";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return ((float) $row[0]);
    }
    return 0;
}

function get_Last_MsPyID($prsnID, $itmSetID)
{
    $strSql = "SELECT z.mass_pay_id 
 FROM pay.pay_mass_pay_run_hdr z, pay.pay_itm_trnsctns a " .
        "WHERE(z.mass_pay_id = a.mass_pay_id and a.person_id = " . $prsnID . " and z.itm_st_id =" . $itmSetID . ") " .
        "ORDER BY z.mass_pay_trns_date DESC LIMIT 1 OFFSET 0 ";

    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return ((float) $row[0]);
    }
    return -1;
}

function get_One_MsPyDet1($mspyid, $prsnID)
{
    $whCls = "";
    if ($prsnID > 0) {
        $whCls = " and a.person_id = " . $prsnID;
    }
    $strSql = "SELECT a.pay_trns_id, a.person_id, a.item_id, a.amount_paid, 
to_char(to_timestamp(a.paymnt_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS'), a.paymnt_source, " .
        "a.pay_trns_type, a.pymnt_desc, -1, a.crncy_id, c.local_id_no, trim(c.title || ' ' || c.sur_name || " .
        "', ' || c.first_name || ' ' || c.other_names) fullname, b.item_code_name, b.item_min_type, 
      org.get_grade_name(pasn.get_prsn_grdid(a.person_id)) grade_nm,
      org.get_job_name(pasn.get_prsn_jobid(a.person_id)) job_nm,
      org.get_pos_name(pasn.get_prsn_posid(a.person_id)) pos_nm,
      COALESCE(e.id_number,'-') ssnit_num,
      COALESCE(d.bank_name || ' (' || d.bank_branch || ')', '-') bank_brnch,
      COALESCE(d.account_number,'-') bank_acc_num
   FROM (pay.pay_itm_trnsctns a LEFT OUTER JOIN org.org_pay_items b ON a.item_id = b.item_id) 
   LEFT OUTER JOIN prs.prsn_names_nos c on a.person_id = c.person_id 
   LEFT OUTER JOIN pasn.prsn_bank_accounts d on a.person_id = d.person_id 
   LEFT OUTER JOIN prs.prsn_national_ids e on a.person_id = e.person_id and e.national_id_typ='SSNIT'
   WHERE(a.mass_pay_id = " . $mspyid . " and b.item_value_uom ='Money'" . $whCls . ") " .
        "ORDER BY c.local_id_no, b.report_line_no, b.item_min_type, b.pay_run_priority, a.pay_trns_id ";

    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_One_MsPyDetSmmry($mspyid, $prsnID)
{
    $whCls = "";
    if ($prsnID > 0) {
        $whCls = " and a.person_id = " . $prsnID;
    }
    $strSql = "SELECT -1, a.person_id, a.item_id, SUM(a.amount_paid), 
to_char(to_timestamp(a.paymnt_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY'), a.paymnt_source, " .
        "a.pay_trns_type, '', -1, a.crncy_id, c.local_id_no, trim(c.title || ' ' || c.sur_name || " .
        "', ' || c.first_name || ' ' || c.other_names) fullname, b.item_code_name, b.item_min_type, 
      org.get_grade_name(pasn.get_prsn_grdid(a.person_id)) grade_nm,
      org.get_job_name(pasn.get_prsn_jobid(a.person_id)) job_nm,
      org.get_pos_name(pasn.get_prsn_posid(a.person_id)) pos_nm,
      COALESCE(e.id_number,'-') ssnit_num,
      COALESCE(d.bank_name || ' (' || d.bank_branch || ')', '-') bank_brnch,
      COALESCE(d.account_number,'-') bank_acc_num, b.report_line_no, b.pay_run_priority, 
      substring(b.local_classfctn from position('.' in b.local_classfctn) + 1) clsfctn 
   FROM (pay.pay_itm_trnsctns a LEFT OUTER JOIN org.org_pay_items b ON a.item_id = b.item_id) 
   LEFT OUTER JOIN prs.prsn_names_nos c on a.person_id = c.person_id 
   LEFT OUTER JOIN pasn.prsn_bank_accounts d on a.person_id = d.person_id 
   LEFT OUTER JOIN prs.prsn_national_ids e on a.person_id = e.person_id and e.national_id_typ='SSNIT'
   WHERE(a.amount_paid>=0 and a.mass_pay_id = " . $mspyid . " and b.item_value_uom ='Money'" . $whCls . ") " .
        "GROUP BY 1,2,3,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23
   ORDER BY c.local_id_no, b.report_line_no, b.item_min_type, b.pay_run_priority";

    $result = executeSQLNoParams($strSql);
    return $result;
}

function getMsPyToRllBck($mspyid)
{
    $strSql = "SELECT a.pay_trns_id, a.person_id, a.item_id, a.amount_paid, 
            to_char(to_timestamp(a.paymnt_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS'), a.paymnt_source, " .
        "a.pay_trns_type, a.pymnt_desc, -1, a.crncy_id, c.local_id_no, trim(c.title || ' ' || c.sur_name || " .
        "', ' || c.first_name || ' ' || c.other_names) fullname, b.item_code_name, b.item_value_uom, b.item_maj_type, b.item_min_type " .
        "FROM (pay.pay_itm_trnsctns a LEFT OUTER JOIN org.org_pay_items b ON a.item_id = b.item_id) " .
        "LEFT OUTER JOIN prs.prsn_names_nos c on a.person_id = c.person_id " .
        "WHERE(a.mass_pay_id = " . $mspyid . ") ORDER BY a.pay_trns_id ";
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_Total_MsPyDt($mspyid)
{
    $strSql = "SELECT count(1) " .
        "FROM (pay.pay_itm_trnsctns a LEFT OUTER JOIN org.org_pay_items b ON a.item_id = b.item_id) " .
        "LEFT OUTER JOIN prs.prsn_names_nos c on a.person_id = c.person_id " .
        "WHERE(a.mass_pay_id = " . $mspyid . ")";

    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return ((float) $row[0]);
    }
    return 0;
}

function get_Basic_MsPy($searchWord, $searchIn, $offset, $limit_size, $orgID, $vwSelf)
{
    global $usrID;
    $strSql = "";
    $extrWhr = "";
    if ($vwSelf) {
        $extrWhr = " and a.created_by=" . $usrID;
    }
    if ($searchIn == "Mass Pay Run Name") {
        $strSql = "SELECT a.mass_pay_id, a.mass_pay_name, a.mass_pay_desc, a.run_status, 
        to_char(to_timestamp(a.mass_pay_trns_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS'), a.prs_st_id, a.itm_st_id, a.sent_to_gl, 
        to_char(to_timestamp(a.gl_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') " .
            "FROM pay.pay_mass_pay_run_hdr a " .
            "WHERE ((a.mass_pay_name ilike '" . loc_db_escape_string($searchWord) .
            "') AND (org_id = " . $orgID . ")" . $extrWhr . ") ORDER BY a.mass_pay_id DESC LIMIT " . $limit_size .
            " OFFSET " . (abs($offset * $limit_size));
    } else {
        $strSql = "SELECT a.mass_pay_id, a.mass_pay_name, a.mass_pay_desc, a.run_status, 
        to_char(to_timestamp(a.mass_pay_trns_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS')
, a.prs_st_id, a.itm_st_id, a.sent_to_gl, to_char(to_timestamp(a.gl_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') " .
            "FROM pay.pay_mass_pay_run_hdr a " .
            "WHERE ((a.mass_pay_desc ilike '" . loc_db_escape_string($searchWord) .
            "') AND (org_id = " . $orgID . ")" . $extrWhr . ") ORDER BY a.mass_pay_id DESC LIMIT " . $limit_size .
            " OFFSET " . (abs($offset * $limit_size));
    }
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_Total_MsPy($searchWord, $searchIn, $orgID, $vwSelf)
{
    global $usrID;
    $strSql = "";
    $extrWhr = "";
    if ($vwSelf) {
        $extrWhr = " and a.created_by=" . $usrID;
    }
    if ($searchIn == "Mass Pay Run Name") {
        $strSql = "SELECT count(1) " .
            "FROM pay.pay_mass_pay_run_hdr a " .
            "WHERE ((a.mass_pay_name ilike '" . loc_db_escape_string($searchWord) .
            "') AND (org_id = " . $orgID . ")" . $extrWhr . ")";
    } else {
        $strSql = "SELECT count(1)  " .
            "FROM pay.pay_mass_pay_run_hdr a " .
            "WHERE ((a.mass_pay_desc ilike '" . loc_db_escape_string($searchWord) .
            "') AND (org_id = " . $orgID . ")" . $extrWhr . ")";
    }
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return ((float) $row[0]);
    }
    return 0;
}

function get_OneBasic_MsPy($massPayID, $vwSelf)
{
    global $usrID;
    $strSql = "";
    $extrWhr = "";
    if ($vwSelf) {
        $extrWhr = " and a.created_by=" . $usrID;
    }
    $strSql = "SELECT a.mass_pay_id, a.mass_pay_name, a.mass_pay_desc, a.run_status, 
        to_char(to_timestamp(a.mass_pay_trns_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS'), 
        a.prs_st_id, pay.get_prs_st_name(a.prs_st_id),
        a.itm_st_id, pay.get_itm_st_name(a.itm_st_id),
        a.sent_to_gl, to_char(to_timestamp(a.gl_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS'),
        pay.get_MsPay_SumTtl(-1, a.mass_pay_id, -1), allwd_group_type, allwd_group_value,
        REPLACE(org.get_criteria_name(a.allwd_group_value::bigint, a.allwd_group_type),'Everyone','') group_name, 
        workplace_cstmr_id, scm.get_cstmr_splr_name(workplace_cstmr_id), 
        workplace_cstmr_site_id, scm.get_cstmr_splr_site_name(workplace_cstmr_site_id), 
        entered_amnt, entered_amt_crncy_id, cheque_card_num, sign_code, is_quick_pay, 
        auto_asgn_itms, mspy_apply_advnc, mspy_keep_excess
         FROM pay.pay_mass_pay_run_hdr a 
        WHERE ((a.mass_pay_id = " . $massPayID . ")" . $extrWhr . ")";
    //echo $strSql;
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_BankAdvice($msPyRunNm)
{
    global $orgID;
    $strSql = "SELECT row_number() OVER (ORDER BY tbl1.local_id_no) AS \"No.  \"
, tbl1.local_id_no \"ID No.        \", tbl1.fullname \"Full Name                     \", 
round(tbl1.total_earnings - tbl1.total_bills_charges- tbl1.total_deductions,2) \"Take Home   \", 
tbl2.bank_name || ' (' || tbl2.bank_branch || ')' \"Bank           \", tbl2.account_name || ' / ' || tbl2.account_number ||' / '||
tbl2.account_type \"Account          \", tbl2.net_pay_portion ||' ' || tbl2.portion_uom \"Portion    \", 
CASE WHEN portion_uom='Percent' THEN round(chartonumeric(to_char((net_pay_portion/100.00) * 
(tbl1.total_earnings - tbl1.total_bills_charges- tbl1.total_deductions),
'999999999999999999999999999999999999999999999D99')),2) 
 ELSE net_pay_portion END \"Amount to Transfer\" 
from pay.get_payment_summrys(" . $orgID . ",'" . loc_db_escape_string($msPyRunNm) .
        "','2') tbl1 
LEFT OUTER JOIN 
pasn.prsn_bank_accounts tbl2 ON (tbl1.person_id = tbl2.person_id and tbl2.net_pay_portion !=0) ORDER BY tbl1.local_id_no";

    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_PayRunSmmry($msPyRunNm)
{
    global $orgID;
    $strSql = "SELECT row_number() OVER (ORDER BY tbl1.local_id_no) AS \"No.  \"
        , tbl1.local_id_no \"ID No.     \", tbl1.fullname \"Full Name             \", 
        round(tbl1.total_earnings,2) \"Total Earnings \", 
        round(tbl1.total_employer_charges,2)  \"Employer Charges\", 
        round(tbl1.total_bills_charges+ tbl1.total_deductions,2) \"Deductions      \",
        round(tbl1.total_earnings - tbl1.total_bills_charges- tbl1.total_deductions,2) \"Take Home       \" 
    from pay.get_payment_summrys(" . $orgID . ",'" . loc_db_escape_string($msPyRunNm) .
        "','2') tbl1 ORDER BY tbl1.local_id_no";
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_Pay_Trns($searchWord, $searchIn, $offset, $limit_size, $orgID, $dte1, $dte2)
{
    if ($dte1 !== "") {
        $dte1 = cnvrtDMYTmToYMDTm($dte1);
    }
    if ($dte2 !== "") {
        $dte2 = cnvrtDMYTmToYMDTm($dte2);
    }

    $whrcls = "";
    $to_gl = "";
    //if ($gonetogl)
    //{
    //  $to_gl = " and (gl_batch_id > 0)";
    //}
    if ($searchIn == "Person No.") {
        $whrcls = " and (c.local_id_no ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Person Name") {
        $whrcls = " and (trim(c.title || ' ' || c.sur_name || " .
            "', ' || c.first_name || ' ' || c.other_names) ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Item Name") {
        $whrcls = " and (b.item_code_name ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Transaction Date") {
        $whrcls = " and (to_char(to_timestamp(a.paymnt_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Transaction Description") {
        $whrcls = " and (a.pymnt_desc ilike '" . loc_db_escape_string($searchWord) . "')";
    }
    $strSql = "SELECT a.pay_trns_id, a.person_id, a.item_id, a.amount_paid, 
to_char(to_timestamp(a.paymnt_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS')
, a.paymnt_source, a.pay_trns_type, a.pymnt_desc, -1, a.crncy_id, c.local_id_no, trim(c.title || ' ' || c.sur_name || " .
        "', ' || c.first_name || ' ' || c.other_names) fullname, b.item_code_name, "
        . "a.pymnt_vldty_status, gst.get_pssbl_val(a.crncy_id), b.item_min_type " .
        "FROM (pay.pay_itm_trnsctns a LEFT OUTER JOIN org.org_pay_items b ON a.item_id = b.item_id) " .
        "LEFT OUTER JOIN prs.prsn_names_nos c on a.person_id = c.person_id " .
        "WHERE((b.org_id = " . $orgID . ")" . $whrcls . $to_gl . " and (to_timestamp(a.paymnt_date,'YYYY-MM-DD HH24:MI:SS') between to_timestamp('" . loc_db_escape_string($dte1) .
        "','YYYY-MM-DD HH24:MI:SS') AND to_timestamp('" . loc_db_escape_string($dte2) . "','YYYY-MM-DD HH24:MI:SS'))) " .
        "ORDER BY a.person_id, b.pay_run_priority, a.pay_trns_id DESC LIMIT " . $limit_size .
        " OFFSET " . (abs($offset * $limit_size));
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_Total_Trns($searchWord, $searchIn, $orgID, $dte1, $dte2)
{
    if ($dte1 !== "") {
        $dte1 = cnvrtDMYTmToYMDTm($dte1);
    }
    if ($dte2 !== "") {
        $dte2 = cnvrtDMYTmToYMDTm($dte2);
    }

    $whrcls = "";
    $to_gl = "";
    //if ($gonetogl)
    //{
    //  $to_gl = " and (gl_batch_id > 0)";
    //}
    if ($searchIn == "Person No.") {
        $whrcls = " and (c.local_id_no ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Person Name") {
        $whrcls = " and (trim(c.title || ' ' || c.sur_name || " .
            "', ' || c.first_name || ' ' || c.other_names) ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Item Name") {
        $whrcls = " and (b.item_code_name ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Transaction Date") {
        $whrcls = " and (to_char(to_timestamp(a.paymnt_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Transaction Description") {
        $whrcls = " and (a.pymnt_desc ilike '" . loc_db_escape_string($searchWord) . "')";
    }
    $strSql = "SELECT count(1) " .
        "FROM (pay.pay_itm_trnsctns a LEFT OUTER JOIN org.org_pay_items b ON a.item_id = b.item_id) " .
        "LEFT OUTER JOIN prs.prsn_names_nos c on a.person_id = c.person_id " .
        "WHERE((b.org_id = " . $orgID . ")" . $whrcls . $to_gl .
        " and (to_timestamp(a.paymnt_date,'YYYY-MM-DD HH24:MI:SS') between to_timestamp('" . loc_db_escape_string($dte1) .
        "','YYYY-MM-DD HH24:MI:SS') AND to_timestamp('" . loc_db_escape_string($dte2) . "','YYYY-MM-DD HH24:MI:SS'))) ";

    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return ((float) $row[0]);
    }
    return 0;
}

function get_PAYGlIntrfc(
    $searchWord,
    $searchIn,
    $offset,
    $limit_size,
    $orgID,
    $dte1,
    $dte2,
    $notgonetogl,
    $imblcnTrns,
    $usrTrns,
    $lowVal,
    $highVal
) {
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
      from  pay.pay_gl_interface v
      group by v.source_trns_id, substring(v.trnsctn_date from 0 for 11)
      having COALESCE(SUM(v.dbt_amount),0)-COALESCE(SUM(v.crdt_amount),0) != 0) tbl1) like '%,'||a.interface_id||',%')";

        /* $imblnce_trns = " and (a.interface_id IN (select MAX(v.interface_id)
          from  pay.pay_gl_interface v
          group by v.source_trns_id, substring(v.trnsctn_date from 0 for 11)
          having COALESCE(SUM(v.dbt_amount),0)-COALESCE(SUM(v.crdt_amount),0) != 0)
          or a.interface_id IN (select MIN(v.interface_id)
          from  pay.pay_gl_interface v
          group by v.source_trns_id, substring(v.trnsctn_date from 0 for 11)
          having COALESCE(SUM(v.dbt_amount),0)-COALESCE(SUM(v.crdt_amount),0) != 0))"; */
        /*
          (select MAX(v.interface_id)
          from  pay.pay_gl_interface v
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
        $whereCls = "(a.trns_source ilike '" . loc_db_escape_string($searchWord) .
            "') and ";
    } else if ($searchIn == "Transaction Description") {
        $whereCls = "(a.transaction_desc ilike '" . loc_db_escape_string($searchWord) .
            "') and ";
    }
    $strSql = "SELECT a.accnt_id, b.accnt_num, b.accnt_name, a.transaction_desc, 
to_char(to_timestamp(a.trnsctn_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS'), a.dbt_amount, " .
        "a.crdt_amount, a.source_trns_id, 'Payroll', a.gl_batch_id, " .
        "(select d.batch_name from accb.accb_trnsctn_batches d where d.batch_id = a.gl_batch_id) btch_nm, " .
        "a.interface_id, a.func_cur_id, -1, pay.get_mass_py_name((SELECT MAX(z.mass_pay_id) FROM pay.pay_itm_trnsctns z WHERE z.pay_trns_id = a.source_trns_id)), "
        . "gst.get_pssbl_val(a.func_cur_id), a.trns_source " .
        "FROM pay.pay_gl_interface a, accb.accb_chart_of_accnts b " .
        "WHERE ((a.accnt_id = b.accnt_id) and " . $whereCls . "(b.org_id = " . $orgID . ")" . $to_gl .
        $imblnce_trns . $usrTrnsSql . $amntCls . " and (to_timestamp(a.trnsctn_date,'YYYY-MM-DD HH24:MI:SS') between to_timestamp('" . $dte1 .
        "','YYYY-MM-DD HH24:MI:SS') AND to_timestamp('" . $dte2 . "','YYYY-MM-DD HH24:MI:SS'))) " .
        "ORDER BY a.interface_id DESC LIMIT " . $limit_size . " OFFSET " . abs($offset * $limit_size);
    //echo $strSql;
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_PAYGlIntrfcTtl($searchWord, $searchIn, $orgID, $dte1, $dte2, $notgonetogl, $imblcnTrns, $usrTrns, $lowVal, $highVal)
{
    global $gnrlTrnsDteYMDHMS;
    execUpdtInsSQL("UPDATE pay.pay_gl_interface SET trnsctn_date='" . $gnrlTrnsDteYMDHMS . "' WHERE trnsctn_date=''");
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
      from  pay.pay_gl_interface v
      group by v.source_trns_id, substring(v.trnsctn_date from 0 for 11)
      having COALESCE(SUM(v.dbt_amount),0)-COALESCE(SUM(v.crdt_amount),0) != 0) tbl1) like '%,'||a.interface_id||',%')";
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
        $whereCls = "(a.trns_source ilike '" . loc_db_escape_string($searchWord) .
            "') and ";
    } else if ($searchIn == "Transaction Description") {
        $whereCls = "(a.transaction_desc ilike '" . loc_db_escape_string($searchWord) .
            "') and ";
    }
    $strSql = "SELECT count(1) " .
        "FROM pay.pay_gl_interface a, accb.accb_chart_of_accnts b " .
        "WHERE ((a.accnt_id = b.accnt_id) and " . $whereCls . "(b.org_id = " . $orgID . ")" . $to_gl .
        $imblnce_trns . $usrTrnsSql . $amntCls . " and (to_timestamp(a.trnsctn_date,'YYYY-MM-DD HH24:MI:SS') between to_timestamp('" . $dte1 .
        "','YYYY-MM-DD HH24:MI:SS') AND to_timestamp('" . $dte2 . "','YYYY-MM-DD HH24:MI:SS'))) ";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return 0;
}

function get_OnePAYGlIntrfcDet($intrfcID)
{
    $strSql = "SELECT a.accnt_id, b.accnt_num, b.accnt_name, a.transaction_desc, 
to_char(to_timestamp(a.trnsctn_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS'), a.dbt_amount, " .
        "a.crdt_amount, a.source_trns_id, 'Payroll Run', a.gl_batch_id, " .
        "(select d.batch_name from accb.accb_trnsctn_batches d where d.batch_id = a.gl_batch_id) btch_nm, a.interface_id, a.func_cur_id, " .
        "-1, 'Batch Name', gst.get_pssbl_val(a.func_cur_id), 
             a.trns_source, a.net_amount, a.net_amount entered_amnt, a.func_cur_id entered_amt_crncy_id, 
             gst.get_pssbl_val(a.func_cur_id), a.net_amount accnt_crncy_amnt, a.func_cur_id accnt_crncy_id, 
             gst.get_pssbl_val(a.func_cur_id), 
             1 func_cur_exchng_rate, 1 accnt_cur_exchng_rate " .
        "FROM pay.pay_gl_interface a, accb.accb_chart_of_accnts b " .
        "WHERE ((a.accnt_id = b.accnt_id) and (a.interface_id = " . $intrfcID . "))";
    $result = executeSQLNoParams($strSql);
    return $result;
}

function getPAYGLIntrfcDffrnc($orgID)
{
    $strSql = "SELECT COALESCE(SUM(a.dbt_amount),0) dbt_sum, 
COALESCE(SUM(a.crdt_amount),0) crdt_sum 
FROM pay.pay_gl_interface a, accb.accb_chart_of_accnts b 
WHERE a.gl_batch_id = -1 and a.accnt_id = b.accnt_id and b.org_id=" . $orgID .
        " ";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        $dffrce1 = (float) $row[0] - (float) $row[1];
        return $dffrce1;
    }
    return 0;
}

function createPAYTrnsGLIntFcLn(
    $accntid,
    $trnsdesc,
    $dbtamnt,
    $trnsdte,
    $crncyid,
    $crdtamnt,
    $netamnt,
    $srcDocTyp,
    $srcDocID,
    $srcDocLnID,
    $dateStr,
    $trnsLnTyp,
    $trnsSrc,
    $entrdAMnt,
    $entrdCrncyID,
    $acctCrncyAmnt,
    $acctCrncyID,
    $funcCrncyRate,
    $acntCrncyRate
) {
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
    $insSQL = "INSERT INTO pay.pay_gl_interface(
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

function updatePAYTrnsGLIntFcLn(
    $intrfcLineID,
    $accntid,
    $trnsdesc,
    $dbtamnt,
    $trnsdte,
    $crncyid,
    $crdtamnt,
    $netamnt,
    $srcDocTyp,
    $srcDocID,
    $srcDocLnID,
    $dateStr,
    $trnsLnTyp,
    $trnsSrc,
    $entrdAMnt,
    $entrdCrncyID,
    $acctCrncyAmnt,
    $acctCrncyID,
    $funcCrncyRate,
    $acntCrncyRate
) {
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
    $insSQL = "UPDATE pay.pay_gl_interface
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
    //echo $insSQL;
    return execUpdtInsSQL($insSQL);
}

function deletePAYTrnsGLIntFcLn($intrfcLineID, $intrfcDesc)
{
    $delSQL = "DELETE FROM pay.pay_gl_interface WHERE interface_id = " . $intrfcLineID . " and gl_batch_id<=0 and trns_source!='SYS'";
    return execUpdtInsSQL($delSQL, $intrfcDesc);
}

function get_PayeRates()
{
    $selSQL = "SELECT rates_id, level_order, rates_amount, tax_percent " .
        "FROM pay.pay_paye_rates ORDER BY level_order ASC, rates_id ASC";
    $result = executeSQLNoParams($selSQL);
    return $result;
}

function createPayeRates($lvlNo, $rateAmount, $txRate)
{
    global $usrID;
    $insSQL = "INSERT INTO pay.pay_paye_rates(
            rates_amount, tax_percent, level_order, created_by, 
            creation_date, last_update_by, last_update_date)
    VALUES (" . $rateAmount . ", " . $txRate . ", " . $lvlNo .
        ", " . $usrID . ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), " . $usrID . ",  to_char(now(),'YYYY-MM-DD HH24:MI:SS'))";
    return execUpdtInsSQL($insSQL);
}

function updatePayeRates($rateID, $lvlNo, $rateAmount, $txRate)
{
    global $usrID;
    $updtSQL = "UPDATE pay.pay_paye_rates SET 
            rates_amount=" . $rateAmount . ", tax_percent=" . $txRate . ", level_order=" . $lvlNo .
        ", last_update_by=" . $usrID . ", last_update_date=to_char(now(),'YYYY-MM-DD HH24:MI:SS') WHERE rates_id=" . $rateID;
    return execUpdtInsSQL($updtSQL);
}

function deletePayeRates($rateID, $rateDesc)
{
    $delSQL = "DELETE FROM pay.pay_paye_rates WHERE rates_id = " . $rateID . "";
    $affctd1 = execUpdtInsSQL($delSQL, $rateDesc);
    if ($affctd1 > 0) {
        $dsply = "";
        $dsply .= "<br/>Successfully Executed the ff-";
        $dsply .= "<br/>Deleted $affctd1 PAYE Tax Rate(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Deleted";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function get_PayeRateValue($inptVal)
{
    $sqlStr = "select pay.calc_irs_paye_mnthly_tax(" . $inptVal . ")";
    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return 0;
}

function get_CriteriaID($criteriaNm, $criteriaType)
{
    $strSql = "SELECT org.get_criteria_id('" . loc_db_escape_string($criteriaNm) .
        "', '" . loc_db_escape_string($criteriaType) . "') ";

    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -1;
}

function get_One_GBVDetID($hdrID, $valID, $criteriaType, $startDte)
{
    /* startDte = DateTime.ParseExact(
      startDte, "dd-MMM-yyyy HH:mm:ss",
      System.Globalization.CultureInfo.InvariantCulture).ToString("yyyy-MM-dd HH:mm:ss"); */

    $strSql = "SELECT a.value_det_id FROM pay.pay_global_values_det a " .
        "WHERE((a.global_value_hdr_id = " . $hdrID .
        ") AND (a.criteria_type='" . loc_db_escape_string($criteriaType) .
        "') AND (a.criteria_val_id= " . $valID .
        ") AND to_timestamp(a.valid_start_date,'YYYY-MM-DD HH24:MI:SS')" .
        "<=to_timestamp('" . loc_db_escape_string($startDte) . "', 'DD-Mon-YYYY HH24:MI:SS') AND to_timestamp(a.valid_end_date,'YYYY-MM-DD HH24:MI:SS')" .
        ">=to_timestamp('" . loc_db_escape_string($startDte) . "', 'DD-Mon-YYYY HH24:MI:SS'))";

    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -1;
}

function get_One_GBVDet($searchWord, $searchIn, $offset, $limit_size, $hdrID)
{
    $strSql = "";
    $whrcls = "";
    if ($searchIn == "Criteria Type/Name") {
        $whrcls = " and (a.criteria_type ilike '" . loc_db_escape_string($searchWord) . "' "
            . "or org.get_criteria_name(a.criteria_val_id,a.criteria_type) ilike '" . loc_db_escape_string($searchWord) . "')";
    }
    $strSql = "SELECT a.value_det_id, a.criteria_type, a.criteria_val_id, 
   org.get_criteria_name(a.criteria_val_id,a.criteria_type)," .
        "a.num_value, to_char(to_timestamp(a.valid_start_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS'), " .
        "to_char(to_timestamp(a.valid_end_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') " .
        "FROM pay.pay_global_values_det a " .
        "WHERE((a.global_value_hdr_id = " . $hdrID . ")" . $whrcls . ") ORDER BY a.criteria_val_id LIMIT " . $limit_size .
        " OFFSET " . (abs($offset * $limit_size));
    $result = executeSQLNoParams($strSql);

    return $result;
}

function get_Total_GBVDet($searchWord, $searchIn, $hdrID)
{
    $strSql = "";
    $whrcls = "";
    if ($searchIn == "Criteria Type/Name") {
        $whrcls = " and (a.criteria_type ilike '" . loc_db_escape_string($searchWord) . "' "
            . "or org.get_criteria_name(a.criteria_val_id,a.criteria_type) ilike '" . loc_db_escape_string($searchWord) . "')";
    }
    $strSql = "SELECT count(1) " .
        "FROM pay.pay_global_values_det a " .
        "WHERE((a.global_value_hdr_id = " . $hdrID . ")" . $whrcls . ")";

    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return 0;
}

function get_OneBasic_GBV($hdrID)
{
    $strSql = "SELECT a.global_val_id, a.global_value_name, a.global_value_desc, a.is_enabled, a.dflt_criteria_type
       FROM pay.pay_global_values_hdr a " .
        "WHERE ((global_val_id = " . $hdrID . ")" .
        ")";
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_Basic_GBV($searchWord, $searchIn, $offset, $limit_size, $orgID)
{
    $strSql = "";
    $whrcls = "";
    if ($searchIn == "Global Value Name") {
        $whrcls = " and (a.global_value_name ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Global Value Description") {
        $whrcls = " and (a.global_value_desc ilike '" . loc_db_escape_string($searchWord) . "')";
    }
    $strSql = "SELECT a.global_val_id, a.global_value_name, a.global_value_desc, a.is_enabled, a.dflt_criteria_type
       FROM pay.pay_global_values_hdr a " .
        "WHERE ((org_id = " . $orgID . ")" . $whrcls .
        ") ORDER BY a.global_val_id DESC LIMIT " . $limit_size .
        " OFFSET " . (abs($offset * $limit_size));
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_Total_GBV($searchWord, $searchIn, $orgID)
{
    $strSql = "";
    $whrcls = "";
    if ($searchIn == "Global Value Name") {
        $whrcls = " and (a.global_value_name ilike '" . loc_db_escape_string($searchWord) . "')";
    } else if ($searchIn == "Global Value Description") {
        $whrcls = " and (a.global_value_desc ilike '" . loc_db_escape_string($searchWord) . "')";
    }
    $strSql = "SELECT count(1)  " .
        "FROM pay.pay_global_values_hdr a " .
        "WHERE ((org_id = " . $orgID . ")" . $whrcls . ")";

    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return 0;
}

function deleteGBV($hdrID, $gbvNm)
{
    $delSQL = "DELETE FROM pay.pay_global_values_det WHERE global_value_hdr_id = " . $hdrID;
    $affctd1 = execUpdtInsSQL($delSQL, $gbvNm);

    $delSQL = "DELETE FROM pay.pay_global_values_hdr WHERE global_val_id = " . $hdrID;
    $affctd = execUpdtInsSQL($delSQL, $gbvNm);
    if ($affctd > 0) {
        $dsply = "";
        $dsply .= "<br/>Successfully Executed the ff-";
        $dsply .= "<br/>Deleted $affctd1 Global Value Line(s)!";
        $dsply .= "<br/>Deleted $affctd Global Value(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Deleted";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function deleteGBVLn($gbvLnid, $critrNm)
{
    $delSQL = "DELETE FROM pay.pay_global_values_det WHERE value_det_id = " . $gbvLnid;
    $affctd = execUpdtInsSQL($delSQL, "Global Value Line Name = " . $critrNm);
    if ($affctd > 0) {
        $dsply = "";
        $dsply .= "<br/>Successfully Executed the ff-";
        $dsply .= "<br/>Deleted $affctd Global Value Line(s)!";
        return "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
    } else {
        $dsply = "No Record Deleted";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
}

function getGBVID($gbvname, $orgid)
{
    $sqlStr = "select global_val_id from pay.pay_global_values_hdr where lower(global_value_name) = lower('" .
        loc_db_escape_string($gbvname) . "') and org_id = " . $orgid;

    $result = executeSQLNoParams($sqlStr);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -1;
}

function createGBVHdr($orgid, $gbvname, $gbvdesc, $dfltCrtria, $isEnbld)
{
    global $usrID;
    $insSQL = "INSERT INTO pay.pay_global_values_hdr(
            global_value_name, global_value_desc, is_enabled, 
            dflt_criteria_type, created_by, creation_date, last_update_by, 
            last_update_date, org_id) " .
        "VALUES ('" . loc_db_escape_string($gbvname) .
        "', '" . loc_db_escape_string($gbvdesc) .
        "', '" . cnvrtBoolToBitStr($isEnbld) .
        "', '" . loc_db_escape_string($dfltCrtria) .
        "', " . $usrID . ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), " . $usrID .
        ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), " . $orgid . ")";
    return execUpdtInsSQL($insSQL);
}

function updateGBVHdr($gbvHdrId, $gbvname, $gbvdesc, $dfltCrtria, $isEnbld)
{
    global $usrID;
    $updtSQL = "UPDATE pay.pay_global_values_hdr SET " .
        "global_value_name='" . loc_db_escape_string($gbvname) .
        "', global_value_desc='" . loc_db_escape_string($gbvdesc) .
        "', dflt_criteria_type='" . loc_db_escape_string($dfltCrtria) .
        "', last_update_by=" . $usrID . ", " .
        "last_update_date=to_char(now(),'YYYY-MM-DD HH24:MI:SS'), is_enabled='" . cnvrtBoolToBitStr($isEnbld) .
        "' " .
        "WHERE (global_val_id =" . $gbvHdrId . ")";
    return execUpdtInsSQL($updtSQL);
}

function getGBVLnID($gbvhdrid, $crtriaID, $crtriaTyp, $startDte)
{
    if ($startDte != "") {
        $startDte = cnvrtDMYTmToYMDTm($startDte);
    }
    $strSQL = "SELECT value_det_id FROM pay.pay_global_values_det WHERE
            global_value_hdr_id = " . $gbvhdrid . " and criteria_val_id=" . $crtriaID .
        " and criteria_type='" . loc_db_escape_string($crtriaTyp) .
        "' and valid_start_date='" . $startDte .
        "'";
    $result = executeSQLNoParams($strSQL);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -1;
}

function createGBVLn($gbvhdrid, $crtriaID, $crtriaTyp, $startDte, $endDte, $amnt)
{
    global $usrID;
    if ($startDte != "") {
        $startDte = cnvrtDMYTmToYMDTm($startDte);
    }

    if ($endDte != "") {
        $endDte = cnvrtDMYTmToYMDTm($endDte);
    }
    $insSQL = "INSERT INTO pay.pay_global_values_det(
            global_value_hdr_id, criteria_val_id, criteria_type, 
            num_value, valid_start_date, valid_end_date, created_by, creation_date, 
            last_update_by, last_update_date) " .
        "VALUES (" . $gbvhdrid . ", " . $crtriaID . ", '" . loc_db_escape_string($crtriaTyp) .
        "', " . $amnt . ", '" . $startDte .
        "', '" . $endDte .
        "', " . $usrID . ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), " . $usrID .
        ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'))";
    return execUpdtInsSQL($insSQL);
}

function updateGBVLn($gbvlnrid, $crtriaID, $crtriaTyp, $startDte, $endDte, $amnt)
{
    global $usrID;
    if ($startDte != "") {
        $startDte = cnvrtDMYTmToYMDTm($startDte);
    }
    if ($endDte != "") {
        $endDte = cnvrtDMYTmToYMDTm($endDte);
    }
    $insSQL = "UPDATE pay.pay_global_values_det SET 
            criteria_val_id=" . $crtriaID . ", num_value=" . $amnt .
        ", valid_start_date='" . loc_db_escape_string($startDte) .
        "', valid_end_date='" . loc_db_escape_string($endDte) .
        "', criteria_type='" . loc_db_escape_string($crtriaTyp) . "', last_update_by=" . $usrID .
        ", last_update_date=to_char(now(),'YYYY-MM-DD HH24:MI:SS') " .
        "WHERE value_det_id=" . $gbvlnrid . " ";
    return execUpdtInsSQL($insSQL);
}

/* function updateItmFeed($feedid, $itmid, $balsItmID, $addSub, $scaleFctr) {
  global $usrID;
  $updtSQL = "UPDATE org.org_pay_itm_feeds " .
  "SET balance_item_id=" . $balsItmID . ", fed_by_itm_id=" . $itmid .
  ", adds_subtracts='" . loc_db_escape_string($addSub) . "', " .
  "last_update_by=" . $usrID .
  ", last_update_date=to_char(now(),'YYYY-MM-DD HH24:MI:SS'), " .
  "scale_factor=" . $scaleFctr .
  " WHERE feed_id = " . $feedid;
  return execUpdtInsSQL($updtSQL);
  }

  function clearTakeHomes() {
  global $usrID;
  $updtSQL = "UPDATE org.org_pay_items SET is_take_home_pay = '0', last_update_by = " . $usrID . ", " .
  "last_update_date = to_char(now(),'YYYY-MM-DD HH24:MI:SS') " .
  "WHERE (is_take_home_pay = '1')";
  return execUpdtInsSQL($updtSQL);
  }

  function updateItmVal($pssblvalid, $itmid, $amnt, $sqlFormula, $valNm) {
  global $usrID;
  $updtSQL = "UPDATE org.org_pay_items_values " .
  "SET item_id=" . $itmid . ", pssbl_amount=" . $amnt .
  ", pssbl_value_sql='" . loc_db_escape_string($sqlFormula) . "', " .
  "last_update_by=" . $usrID . ", last_update_date=to_char(now(),'YYYY-MM-DD HH24:MI:SS'), " .
  "pssbl_value_code_name='" . loc_db_escape_string($valNm) . "' " .
  "WHERE pssbl_value_id = " . $pssblvalid;
  return execUpdtInsSQL($updtSQL);
  }

  function updateItm($orgid, $itmid, $itnm, $itmDesc, $itmMajTyp, $itmMinTyp, $itmUOMTyp, $useSQL, $isenbld, $costAcnt, $balsAcnt, $freqncy, $locClass, $priorty, $inc_dc_cost, $inc_dc_bals, $balstyp, $itmMnID, $isRetro, $retroID, $invItmID, $allwEdit, $createsAcctng, $effctOnOrgDbt) {
  global $usrID;
  $updtSQL = "UPDATE org.org_pay_items " .
  "SET item_code_name='" . loc_db_escape_string($itnm) .
  "', item_desc='" . loc_db_escape_string($itmDesc) .
  "', item_maj_type='" . loc_db_escape_string($itmMajTyp) .
  "', item_min_type='" . loc_db_escape_string($itmMinTyp) .
  "', item_value_uom='" . loc_db_escape_string($itmUOMTyp) .
  "', uses_sql_formulas='" . cnvrtBoolToBitStr($useSQL) .
  "', cost_accnt_id=" . $costAcnt .
  ", bals_accnt_id=" . $balsAcnt . ", " .
  "is_enabled='" . cnvrtBoolToBitStr($isenbld) .
  "', org_id=" . $orgid .
  ", last_update_by=" . $usrID .
  ", last_update_date=to_char(now(),'YYYY-MM-DD HH24:MI:SS'), pay_frequency = '" . loc_db_escape_string($freqncy) .
  "', local_classfctn = '" . loc_db_escape_string($locClass) .
  "', pay_run_priority = " . $priorty .
  ", incrs_dcrs_cost_acnt ='" . loc_db_escape_string($inc_dc_cost) .
  "', incrs_dcrs_bals_acnt='" . loc_db_escape_string($inc_dc_bals) .
  "', balance_type='" . loc_db_escape_string($balstyp) .
  "', report_line_no= " . $itmMnID .
  ", is_retro_element='" . cnvrtBoolToBitStr(isRetro) .
  "', retro_item_id= " . $retroID .
  ", inv_item_id= " . $invItmID .
  ", allow_value_editing='" . cnvrtBoolToBitStr($allwEdit) .
  "', creates_accounting='" . cnvrtBoolToBitStr($createsAcctng) .
  "', effct_on_org_debt='" . loc_db_escape_string($effctOnOrgDbt) .
  "' WHERE item_id=" . $itmid;
  return execUpdtInsSQL($updtSQL);
  }

  function createItm($orgid, $itnm, $itmDesc, $itmMajTyp, $itmMinTyp, $itmUOMTyp, $useSQL, $isenbld, $costAcnt, $balsAcnt, $freqncy, $locClass, $priorty, $inc_dc_cost, $inc_dc_bals, $balstyp, $itmMnID, $isRetro, $retroID, $invItmID, $allwEdit, $createsAcctng, $effctOnOrgDbt) {
  global $usrID;
  $insSQL = "INSERT INTO org.org_pay_items(" .
  "item_code_name, item_desc, item_maj_type, item_min_type, " .
  "item_value_uom, uses_sql_formulas, cost_accnt_id, bals_accnt_id, " .
  "is_enabled, org_id, created_by, creation_date, last_update_by, " .
  "last_update_date, pay_frequency, local_classfctn, pay_run_priority, " .
  "incrs_dcrs_cost_acnt, incrs_dcrs_bals_acnt, balance_type, report_line_no," .
  " is_retro_element,retro_item_id,inv_item_id, allow_value_editing, creates_accounting,effct_on_org_debt) " .
  "VALUES ('" . loc_db_escape_string($itnm) . "', '" . loc_db_escape_string($itmDesc) .
  "', '" . loc_db_escape_string($itmMajTyp) . "', '" . loc_db_escape_string($itmMinTyp) .
  "', '" . loc_db_escape_string($itmUOMTyp) . "', '" . cnvrtBoolToBitStr($useSQL) . "', " . $costAcnt .
  ", " . $balsAcnt . ", '" . cnvrtBoolToBitStr($isenbld) .
  "', " . $orgid . ", " . $usrID . ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), " . $usrID .
  ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), '" . loc_db_escape_string($freqncy) . "', '" . loc_db_escape_string($locClass) .
  "', " . $priorty . ",'" . loc_db_escape_string($inc_dc_cost) . "','" . loc_db_escape_string($inc_dc_bals) .
  "','" . loc_db_escape_string($balstyp) . "', " . $itmMnID .
  ", '" . cnvrtBoolToBitStr($isRetro) .
  "', " . $retroID . ", " . $invItmID . ",'" . cnvrtBoolToBitStr($allwEdit) .
  "','" . cnvrtBoolToBitStr($createsAcctng) .
  "','" . loc_db_escape_string($effctOnOrgDbt) . "')";
  return execUpdtInsSQL($insSQL);
  }

  function createItmVal($itmid, $amnt, $sqlFormula, $valNm) {
  global $usrID;
  $insSQL = "INSERT INTO org.org_pay_items_values(" .
  "item_id, pssbl_amount, pssbl_value_sql, created_by, " .
  "creation_date, last_update_by, last_update_date, pssbl_value_code_name) " .
  "VALUES (" . $itmid . ", " . $amnt .
  ", '" . loc_db_escape_string($sqlFormula) .
  "', " . $usrID . ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), " .
  $usrID . ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), '" . loc_db_escape_string($valNm) . "')";
  return execUpdtInsSQL($insSQL);
  }

  function createItmFeed($itmid, $balsItmID, $addSub, $scaleFctr) {
  global $usrID;
  $insSQL = "INSERT INTO org.org_pay_itm_feeds(" .
  "balance_item_id, fed_by_itm_id, adds_subtracts, created_by, " .
  "creation_date, last_update_by, last_update_date, scale_factor) " .
  "VALUES (" . $balsItmID . ", " . $itmid .
  ", '" . loc_db_escape_string($addSub) . "', " . $usrID . ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), "
  . $usrID . ", to_char(now(),'YYYY-MM-DD HH24:MI:SS'), " . $scaleFctr . ")";
  return execUpdtInsSQL($insSQL);
  }

  function doesItmFeedExists($itmid, $blsItmID) {
  $selSQL = "SELECT a.feed_id " .
  "FROM org.org_pay_itm_feeds a WHERE ((a.fed_by_itm_id = " . $itmid .
  ") and (a.balance_item_id = " . $blsItmID .
  ")) ORDER BY a.feed_id ";
  $result = executeSQLNoParams($selSQL);
  if (loc_db_num_rows($result) > 0) {
  return true;
  }
  return false;
  } */

function getAllItmFeeds($offset, $limit_size, $itmid)
{
    $selSQL = "SELECT balance_item_id, fed_by_itm_id, adds_subtracts, feed_id, scale_factor" .
        ", org.get_payitm_nm(balance_item_id), org.get_payitm_nm(fed_by_itm_id) " .
        "FROM org.org_pay_itm_feeds WHERE ((balance_item_id = " . $itmid .
        ") or (fed_by_itm_id = " . $itmid . ")) ORDER BY feed_id LIMIT " . $limit_size .
        " OFFSET " . abs($offset * $limit_size);
    $result = executeSQLNoParams($selSQL);
    return $result;
}

function get_Total_Feeds($itmid)
{
    $strSql = "SELECT count(1) " .
        "FROM org.org_pay_itm_feeds WHERE ((balance_item_id = " . $itmid .
        ") or (fed_by_itm_id = " . $itmid . "))";

    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return 0;
}

function getAllItmVals($offset, $limit_size, $itmid)
{
    $selSQL = "SELECT pssbl_value_id, pssbl_value_code_name, pssbl_amount, pssbl_value_sql, item_id " .
        "FROM org.org_pay_items_values WHERE ((item_id = " . $itmid . ")) ORDER BY pssbl_value_id DESC LIMIT " . $limit_size .
        " OFFSET " . (abs($offset * $limit_size));
    $result = executeSQLNoParams($selSQL);
    return $result;
}

function get_Total_Psbl_Vl($itmID)
{
    $strSql = "SELECT count(1) " .
        "FROM org.org_pay_items_values WHERE ((item_id = " . $itmID . "))";

    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return 0;
}

function get_One_Itm_Det($itmID)
{
    $strSql = "SELECT a.item_id, a.item_code_name, " .
        "a.item_desc, a.item_maj_type, a.item_min_type, a.item_value_uom, " .
        "a.uses_sql_formulas, a.cost_accnt_id, a.bals_accnt_id, a.is_enabled, a.org_id, " .
        "a.pay_frequency, a.local_classfctn, a.pay_run_priority, a.incrs_dcrs_cost_acnt, 
            a.incrs_dcrs_bals_acnt, a.balance_type, a.is_retro_element, a.retro_item_id, a.inv_item_id, 
            a.allow_value_editing, a.creates_accounting, a.effct_on_org_debt,
       accb.get_accnt_num(a.cost_accnt_id) || '.' || accb.get_accnt_name(a.cost_accnt_id) cost_accnt,
       accb.get_accnt_num(a.bals_accnt_id) || '.' || accb.get_accnt_name(a.bals_accnt_id) bals_accnt,
       org.get_payitm_nm(a.retro_item_id) retro_name,
       inv.get_invitm_name(a.inv_item_id) inv_name " .
        "FROM org.org_pay_items a " .
        "WHERE(a.item_id = " . $itmID . ")";
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_Basic_Itm($searchWord, $searchIn, $offset, $limit_size, $orgID)
{
    $whrcls = "";
    if ($searchIn == "Item Name") {
        $whrcls = " and (a.item_code_name ilike '" . loc_db_escape_string($searchWord) .
            "')";
    } else if ($searchIn == "Item Description") {
        $whrcls = " and (a.item_desc ilike '" . loc_db_escape_string($searchWord) .
            "' or a.local_classfctn ilike '" . loc_db_escape_string($searchWord) .
            "')";
    }
    $strSql = "SELECT a.item_id, a.item_code_name, a.item_maj_type, a.local_classfctn " .
        "FROM org.org_pay_items a " .
        "WHERE ((org_id = " . $orgID . ")" . $whrcls . ") ORDER BY a.pay_run_priority, 2 LIMIT " . $limit_size .
        " OFFSET " . (abs($offset * $limit_size));
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_Total_Itm($searchWord, $searchIn, $orgID)
{
    $whrcls = "";
    if ($searchIn == "Item Name") {
        $whrcls = " and (a.item_code_name ilike '" . loc_db_escape_string($searchWord) .
            "')";
    } else if ($searchIn == "Item Description") {
        $whrcls = " and (a.item_desc ilike '" . loc_db_escape_string($searchWord) .
            "' or a.local_classfctn ilike '" . loc_db_escape_string($searchWord) .
            "')";
    }
    $strSql = "SELECT count(1)  " .
        "FROM org.org_pay_items a " .
        "WHERE ((org_id = " . $orgID . ")" . $whrcls . ")";

    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return 0;
}

function get_TrnsRqstsDocHdr($searchWord, $searchIn, $offset, $limit_size, $orgID, $shwSelfOnly)
{
    global $usrID;
    $strSql = "";
    $whrcls = "";
    $unpstdCls = "";
    if ($shwSelfOnly) {
        $unpstdCls = " and (a.created_by = " . $usrID . ")";
    }
    if ($searchIn == "Requestor") {
        $whrcls = " and (REPLACE(prs.get_prsn_surname(a.RQSTD_FOR_PERSON_ID) || ' (' 
      || prs.get_prsn_loc_id(a.RQSTD_FOR_PERSON_ID) || ')', ' ()', '') ilike '" . loc_db_escape_string($searchWord) .
            "')";
    } else if ($searchIn == "Narration") {
        $whrcls = " and (a.REQUEST_REASON ilike '" . loc_db_escape_string($searchWord) . "'"
            . "or b.item_type_name ilike '" . loc_db_escape_string($searchWord) . "')";
    } else {
        $whrcls = " and (a.REQUEST_REASON ilike '" . loc_db_escape_string($searchWord) . "'"
            . "or b.item_type_name ilike '" . loc_db_escape_string($searchWord) . "'"
            . "or REPLACE(prs.get_prsn_surname(a.RQSTD_FOR_PERSON_ID) || ' (' 
      || prs.get_prsn_loc_id(a.RQSTD_FOR_PERSON_ID) || ')', ' ()', '') ilike '" . loc_db_escape_string($searchWord) .
            "')";
    }
    $strSql = "SELECT a.pay_request_id, a.RQSTD_FOR_PERSON_ID, 
      REPLACE(prs.get_prsn_surname(a.RQSTD_FOR_PERSON_ID) || ' (' 
      || prs.get_prsn_loc_id(a.RQSTD_FOR_PERSON_ID) || ')', ' ()', '') fullnm, a.request_type, 
        a.item_type_id,b.item_type_name, a.local_clsfctn, a.REQUEST_REASON, 
        to_char(to_timestamp(a.creation_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') rqst_sbmt_date, 
        round(a.PRNCPL_AMOUNT,2) PRNCPL_AMOUNT, a.MNTHLY_DEDUC, a.INTRST_RATE, 
        a.REPAY_PERIOD, a.REQUEST_STATUS is_pstd, a.HAS_AGREED, a.IS_PROCESSED
        FROM pay.pay_loan_pymnt_rqsts a, pay.loan_pymnt_invstmnt_typs b 
        WHERE((a.item_type_id=b.item_type_id and a.org_id = " . $orgID . ")" . $whrcls . $unpstdCls .
        ") ORDER BY pay_request_id DESC LIMIT " . $limit_size .
        " OFFSET " . (abs($offset * $limit_size));
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_Total_TrnsRqstsDoc($searchWord, $searchIn, $orgID, $shwSelfOnly)
{
    global $usrID;
    $strSql = "";
    $whrcls = "";
    $unpstdCls = "";
    if ($shwSelfOnly) {
        $unpstdCls = " and (a.created_by = " . $usrID . ")";
    }
    if ($searchIn == "Requestor") {
        $whrcls = " and (REPLACE(prs.get_prsn_surname(a.RQSTD_FOR_PERSON_ID) || ' (' 
      || prs.get_prsn_loc_id(a.RQSTD_FOR_PERSON_ID) || ')', ' ()', '') ilike '" . loc_db_escape_string($searchWord) .
            "')";
    } else if ($searchIn == "Narration") {
        $whrcls = " and (a.REQUEST_REASON ilike '" . loc_db_escape_string($searchWord) . "'"
            . "or b.item_type_name ilike '" . loc_db_escape_string($searchWord) . "')";
    } else {
        $whrcls = " and (a.REQUEST_REASON ilike '" . loc_db_escape_string($searchWord) . "'"
            . "or b.item_type_name ilike '" . loc_db_escape_string($searchWord) . "'"
            . "or REPLACE(prs.get_prsn_surname(a.RQSTD_FOR_PERSON_ID) || ' (' 
      || prs.get_prsn_loc_id(a.RQSTD_FOR_PERSON_ID) || ')', ' ()', '') ilike '" . loc_db_escape_string($searchWord) .
            "')";
    }
    $strSql = "SELECT count(1) FROM pay.pay_loan_pymnt_rqsts a, pay.loan_pymnt_invstmnt_typs b 
        WHERE((a.item_type_id=b.item_type_id and a.org_id = " . $orgID . ")" . $whrcls . $unpstdCls . ")";

    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return $row[0];
    }
    return 0;
}

function get_IndvdlTrnsRqsts($searchWord, $searchIn, $offset, $limit_size, $orgID, $rqstdPrsnID, $excludRqstID, $rqstType = "LOAN")
{
    $strSql = "";
    $whrcls = "";
    $unpstdCls = " and (a.RQSTD_FOR_PERSON_ID = " . $rqstdPrsnID . ") and (a.pay_request_id NOT IN (" . $excludRqstID . ")) and a.request_type='" . loc_db_escape_string($rqstType) . "'";
    if ($searchIn == "Requestor") {
        $whrcls = " and (REPLACE(prs.get_prsn_surname(a.RQSTD_FOR_PERSON_ID) || ' (' 
      || prs.get_prsn_loc_id(a.RQSTD_FOR_PERSON_ID) || ')', ' ()', '') ilike '" . loc_db_escape_string($searchWord) .
            "')";
    } else if ($searchIn == "Narration") {
        $whrcls = " and (a.REQUEST_REASON ilike '" . loc_db_escape_string($searchWord) . "'"
            . "or b.item_type_name ilike '" . loc_db_escape_string($searchWord) . "')";
    } else {
        $whrcls = " and (a.REQUEST_REASON ilike '" . loc_db_escape_string($searchWord) . "'"
            . "or b.item_type_name ilike '" . loc_db_escape_string($searchWord) . "'"
            . "or REPLACE(prs.get_prsn_surname(a.RQSTD_FOR_PERSON_ID) || ' (' 
      || prs.get_prsn_loc_id(a.RQSTD_FOR_PERSON_ID) || ')', ' ()', '') ilike '" . loc_db_escape_string($searchWord) .
            "')";
    }
    $strSql = "SELECT a.pay_request_id, a.RQSTD_FOR_PERSON_ID, 
      REPLACE(prs.get_prsn_surname(a.RQSTD_FOR_PERSON_ID) || ' (' 
      || prs.get_prsn_loc_id(a.RQSTD_FOR_PERSON_ID) || ')', ' ()', '') fullnm, a.request_type, 
        a.item_type_id,b.item_type_name, a.local_clsfctn, a.REQUEST_REASON, 
        to_char(to_timestamp(a.creation_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') rqst_sbmt_date, 
        round(a.PRNCPL_AMOUNT,2) PRNCPL_AMOUNT, a.MNTHLY_DEDUC, a.INTRST_RATE, 
        a.REPAY_PERIOD, a.REQUEST_STATUS is_pstd, a.HAS_AGREED, a.IS_PROCESSED
        FROM pay.pay_loan_pymnt_rqsts a, pay.loan_pymnt_invstmnt_typs b 
        WHERE((a.item_type_id=b.item_type_id and a.REQUEST_STATUS NOT IN ('Not Submitted','Rejected','Withdrawn') and a.org_id = " . $orgID . ")" . $whrcls . $unpstdCls .
        ") ORDER BY pay_request_id DESC LIMIT " . $limit_size .
        " OFFSET " . (abs($offset * $limit_size));
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_UnsttldLoanRqsts($searchWord, $searchIn, $offset, $limit_size, $orgID, $rqstdPrsnID, $dpndntItmTypID, $dpndntBalsItmID, $rqstType = "LOAN")
{
    $strSql = "";
    $whrcls = "";
    $unpstdCls = " and (a.RQSTD_FOR_PERSON_ID = " . $rqstdPrsnID .
        ") and (a.item_type_id IN (" . $dpndntItmTypID . ")) and a.request_type='" . loc_db_escape_string($rqstType) . "'";
    $strSql = "SELECT a.pay_request_id, a.RQSTD_FOR_PERSON_ID, 
      REPLACE(prs.get_prsn_surname(a.RQSTD_FOR_PERSON_ID) || ' (' 
      || prs.get_prsn_loc_id(a.RQSTD_FOR_PERSON_ID) || ')', ' ()', '') fullnm, a.request_type, 
        a.item_type_id,b.item_type_name, a.local_clsfctn, a.REQUEST_REASON, 
        to_char(to_timestamp(a.creation_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') rqst_sbmt_date, 
        round(a.PRNCPL_AMOUNT,2) PRNCPL_AMOUNT, a.MNTHLY_DEDUC, a.INTRST_RATE, 
        a.REPAY_PERIOD, a.REQUEST_STATUS is_pstd, a.HAS_AGREED, a.IS_PROCESSED
        FROM pay.pay_loan_pymnt_rqsts a, pay.loan_pymnt_invstmnt_typs b 
        WHERE((a.item_type_id=b.item_type_id and a.REQUEST_STATUS IN ('Approved')
        and pay.get_ltst_blsitm_bals(" . $rqstdPrsnID .
        "," . $dpndntBalsItmID . ",to_char(now(),'YYYY-MM-DD'))>0
            and a.IS_PROCESSED='1' and a.org_id = " . $orgID . ")" . $whrcls . $unpstdCls .
        ") ORDER BY pay_request_id DESC LIMIT " . $limit_size .
        " OFFSET " . (abs($offset * $limit_size));
    $result = executeSQLNoParams($strSql);
    return $result;
}

function get_One_TrnsRqstsDocHdr($hdrID)
{
    $strSql = "SELECT a.pay_request_id, a.RQSTD_FOR_PERSON_ID, 
      REPLACE(prs.get_prsn_surname(a.RQSTD_FOR_PERSON_ID) || ' (' 
      || prs.get_prsn_loc_id(a.RQSTD_FOR_PERSON_ID) || ')', ' ()', '') fullnm, a.request_type, 
        a.item_type_id,b.item_type_name, a.local_clsfctn, a.REQUEST_REASON, 
        to_char(to_timestamp(a.creation_date,'YYYY-MM-DD HH24:MI:SS'),'DD-Mon-YYYY HH24:MI:SS') rqst_sbmt_date, 
        round(a.PRNCPL_AMOUNT,2) PRNCPL_AMOUNT, a.MNTHLY_DEDUC, a.INTRST_RATE, 
        a.REPAY_PERIOD, a.REQUEST_STATUS is_pstd, a.HAS_AGREED, a.IS_PROCESSED, 
        a.intrst_period_type, a.repay_period_type, a.net_loan_amount, a.max_loan_amount, 
        a.enforce_max_amnt, a.lnkd_loan_id, a.min_loan_amount 
        FROM pay.pay_loan_pymnt_rqsts a, pay.loan_pymnt_invstmnt_typs b 
        WHERE(a.item_type_id=b.item_type_id and a.pay_request_id = " . $hdrID . ")";
    $result = executeSQLNoParams($strSql);
    return $result;
}

function getNewPayTrnsRqstsID()
{
    $strSql = "select nextval('pay.pay_loan_pymnt_rqsts_pay_request_id_seq')";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -1;
}

function createTrnsRqstsDocHdr($nwPkeyID, $rqstPrsnID, $rqstTyp, $itmTypID, $rqstRsn, $lclClsfctn, $rqstAmnt, $hsAgreed, $lnkdPayTrnsRqstsID)
{
    global $usrID;
    global $orgID;
    $insSQL = "INSERT INTO pay.pay_loan_pymnt_rqsts(pay_request_id, rqstd_for_person_id, request_type, item_type_id, request_reason,
                                     local_clsfctn, prncpl_amount, mnthly_deduc, intrst_rate, intrst_period_type, repay_period, 
                                     repay_period_type, request_status, has_agreed,IS_PROCESSED,
                                     org_id, created_by, creation_date, last_update_by, last_update_date,
                                     net_loan_amount,max_loan_amount,enforce_max_amnt, min_loan_amount, lnkd_loan_id) " .
        "VALUES (" . $nwPkeyID . ", " . $rqstPrsnID . ", '" . loc_db_escape_string($rqstTyp) .
        "'," . $itmTypID . ", '" . loc_db_escape_string($rqstRsn) .
        "', '" . loc_db_escape_string($lclClsfctn) .
        "', " . $rqstAmnt .
        ",0, pay.get_trans_type_rate(" . $itmTypID . ")," .
        "pay.get_trans_ratetype(" . $itmTypID . ")," .
        "pay.get_trans_repay_prd(" . $itmTypID . ")," .
        "pay.get_trans_repay_typ(" . $itmTypID . ")," .
        "'Not Submitted','" . cnvrtBoolToBitStr($hsAgreed) .
        "','0', " . $orgID . ", " . $usrID .
        ", to_char(now(), 'YYYY-MM-DD HH24:MI:SS'), " . $usrID .
        ", to_char(now(), 'YYYY-MM-DD HH24:MI:SS'),0,0,pay.get_trntyp_enfrc_mx(" . $itmTypID . "),0," . $lnkdPayTrnsRqstsID . ")";

    $insSQL1 = "UPDATE pay.pay_loan_pymnt_rqsts
                SET mnthly_deduc=round(pay.exct_itm_type_sql(pay.get_trans_typ_sql(" . $itmTypID . ")," . $itmTypID . "," . $nwPkeyID . "," . $rqstPrsnID . "," . $orgID . ",to_char(now(),'YYYY-MM-DD HH24:MI:SS')),2),
                    net_loan_amount=round(pay.exct_itm_type_sql(pay.get_trntyp_net_sql(" . $itmTypID . ")," . $itmTypID . "," . $nwPkeyID . "," . $rqstPrsnID . "," . $orgID . ",to_char(now(),'YYYY-MM-DD HH24:MI:SS')),2),
                    max_loan_amount=round(pay.exct_itm_type_sql(pay.get_trntyp_mx_sql(" . $itmTypID . ")," . $itmTypID . "," . $nwPkeyID . "," . $rqstPrsnID . "," . $orgID . ",to_char(now(),'YYYY-MM-DD HH24:MI:SS')),2),
                    min_loan_amount=round(pay.exct_itm_type_sql(pay.get_trntyp_min_sql(" . $itmTypID . ")," . $itmTypID . "," . $nwPkeyID . "," . $rqstPrsnID . "," . $orgID . ",to_char(now(),'YYYY-MM-DD HH24:MI:SS')),2)
                WHERE pay_request_id = " . $nwPkeyID;
    $afctd = execUpdtInsSQL($insSQL);
    execUpdtInsSQL($insSQL1);
    return $afctd;
}

function updtTrnsRqstsDocHdr($hdrID, $rqstPrsnID, $rqstTyp, $itmTypID, $rqstRsn, $lclClsfctn, $rqstAmnt, $hsAgreed, $lnkdPayTrnsRqstsID)
{
    global $usrID;
    global $orgID;
    $insSQL = "
    UPDATE pay.pay_loan_pymnt_rqsts
    SET rqstd_for_person_id=" . $rqstPrsnID . ",
        request_type='" . loc_db_escape_string($rqstTyp) . "',
        item_type_id=" . $itmTypID . ",
        request_reason='" . loc_db_escape_string($rqstRsn) . "',
        local_clsfctn='" . loc_db_escape_string($lclClsfctn) . "',
        prncpl_amount=" . $rqstAmnt . ",
        intrst_rate=pay.get_trans_type_rate(" . $itmTypID . "),
        intrst_period_type=pay.get_trans_ratetype(" . $itmTypID . "),
        repay_period=pay.get_trans_repay_prd(" . $itmTypID . "),
        repay_period_type=pay.get_trans_repay_typ(" . $itmTypID . "),
        enforce_max_amnt=pay.get_trntyp_enfrc_mx(" . $itmTypID . "),
        request_status = 'Not Submitted',IS_PROCESSED='0',date_processed='',
        has_agreed = '" . cnvrtBoolToBitStr($hsAgreed) . "',
        creation_date = to_char(now(), 'YYYY-MM-DD HH24:MI:SS'),
        last_update_by=" . $usrID . ",
        lnkd_loan_id=" . $lnkdPayTrnsRqstsID . ",
        last_update_date=to_char(now(), 'YYYY-MM-DD HH24:MI:SS')
    WHERE pay_request_id = " . $hdrID;
    $nwPkeyID = $hdrID;
    $insSQL1 = "UPDATE pay.pay_loan_pymnt_rqsts
                SET mnthly_deduc=round(pay.exct_itm_type_sql(pay.get_trans_typ_sql(" . $itmTypID . ")," . $itmTypID . "," . $nwPkeyID . "," . $rqstPrsnID . "," . $orgID . ",to_char(now(),'YYYY-MM-DD HH24:MI:SS')),2),
                    net_loan_amount=round(pay.exct_itm_type_sql(pay.get_trntyp_net_sql(" . $itmTypID . ")," . $itmTypID . "," . $nwPkeyID . "," . $rqstPrsnID . "," . $orgID . ",to_char(now(),'YYYY-MM-DD HH24:MI:SS')),2),
                    max_loan_amount=round(pay.exct_itm_type_sql(pay.get_trntyp_mx_sql(" . $itmTypID . ")," . $itmTypID . "," . $nwPkeyID . "," . $rqstPrsnID . "," . $orgID . ",to_char(now(),'YYYY-MM-DD HH24:MI:SS')),2),
                    min_loan_amount=round(pay.exct_itm_type_sql(pay.get_trntyp_min_sql(" . $itmTypID . ")," . $itmTypID . "," . $nwPkeyID . "," . $rqstPrsnID . "," . $orgID . ",to_char(now(),'YYYY-MM-DD HH24:MI:SS')),2)
                WHERE pay_request_id = " . $nwPkeyID;
    $afctd = execUpdtInsSQL($insSQL);
    execUpdtInsSQL($insSQL1);
    return $afctd;
}

function deleteTrnsRqsts($valLnid, $docNum)
{
    $strSql = "SELECT count(1) FROM pay.pay_loan_pymnt_rqsts a WHERE(a.pay_request_id = " . $valLnid .
        " and a.request_status IN ('Validated', 'Approved', 'Cancelled','Initiated','Reviewed'))";
    $result1 = executeSQLNoParams($strSql);
    $trnsCnt1 = 0;
    while ($row = loc_db_fetch_array($result1)) {
        $trnsCnt1 = (float) $row[0];
    }
    if (($trnsCnt1) > 0) {
        $dsply = "No Record Deleted<br/>Cannot delete a Finalized Transaction!";
        return "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
    }
    $delSQL2 = "DELETE FROM pay.pay_trans_attchmnts WHERE src_pkey_id = " . $valLnid . " and src_trans_type='LOAN_N_PAY'";
    $affctd2 = execUpdtInsSQL($delSQL2, "Desc:" . $docNum);
    $delSQL = "DELETE FROM pay.pay_loan_pymnt_rqsts WHERE pay_request_id = " . $valLnid;
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

function getLoanTypRqstsMxAmnt($itmTypID, $lnkdPayTrnsRqstsID, $rqstPrsnID)
{
    global $orgID;
    $strSql = "Select CASE WHEN " . $lnkdPayTrnsRqstsID . ">0 THEN pay.get_trns_rqst_amnt(" . $lnkdPayTrnsRqstsID .
        ") ELSE pay.exct_itm_type_sql(pay.get_trntyp_mx_sql(" . $itmTypID . ")," . $itmTypID . "," . $lnkdPayTrnsRqstsID .
        "," . $rqstPrsnID . "," . $orgID . ",to_char(now(),'YYYY-MM-DD HH24:MI:SS')) END";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        return (float) $row[0];
    }
    return -1;
}
