<?php
$canAdd = test_prmssns($dfltPrvldgs[14], $mdlNm);
$canEdt = test_prmssns($dfltPrvldgs[15], $mdlNm);
$canDel = test_prmssns($dfltPrvldgs[16], $mdlNm);

$pageNo = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
$lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 30;
$sortBy = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : "Value";
if (array_key_exists('lgn_num', get_defined_vars())) {
    if ($lgn_num > 0 && $canview === true) {
        if ($qstr == "DELETE") {
            if ($actyp == 1) {
                
            } else if ($actyp == 5) {
                
            }
        } else if ($qstr == "UPDATE") {
            if ($actyp == 1) {
                //Save Default Accounts
                //var_dump($_POST);
                //exit();
                header("content-type:application/json");
                $slctdDfltAccnts = isset($_POST['slctdDfltAccnts']) ? cleanInputData($_POST['slctdDfltAccnts']) : '';
                $rowid = (int) getGnrlRecID2("scm.scm_dflt_accnts", "''||org_id", "row_id", $orgID);

                $systemTypeNm = array("Inventory/Asset Account", "Cost of Goods Sold Account", "Purchase Expense Account", "Purchase Returns Account",
                    "Sales Revenue Account", "Sales Returns Account", "Item Receipts Accrual Account",
                    "Cash Payments Accounts", "Check Payments Account", "Receivables Account",
                    "Sales Discount Account", "Customer Advance Payments Account", "Bad Debt Expense Account",
                    "Supplier Cash Payments Accounts", "Petty Cash Account", "Liability Account",
                    "Purchasing Discount Account", "Supplier Advance Account",
                    "Total Current Assets Account", "Total Current Liabilities Account", "Total Assets Account",
                    "Total Liabilities Account", "Total Owner's Equity Account", "Total Revenues Account",
                    "Total Cost of Goods Sold Account", "Total Inventories Account", "Total Prepaid Expenses Account");
                $systemTypeCtgry = array("Inventory Item Accounts", "Inventory Item Accounts", "Inventory Item Accounts", "Inventory Item Accounts",
                    "Inventory Item Accounts", "Inventory Item Accounts", "Inventory Item Accounts",
                    "Sales/Receivables Accounts", "Sales/Receivables Accounts", "Sales/Receivables Accounts",
                    "Sales/Receivables Accounts", "Sales/Receivables Accounts", "Sales/Receivables Accounts",
                    "Purchasing/Payables Accounts", "Purchasing/Payables Accounts", "Purchasing/Payables Accounts",
                    "Purchasing/Payables Accounts", "Purchasing/Payables Accounts",
                    "Accounts for Simple Financial Ratios", "Accounts for Simple Financial Ratios", "Accounts for Simple Financial Ratios",
                    "Accounts for Simple Financial Ratios", "Accounts for Simple Financial Ratios", "Accounts for Simple Financial Ratios",
                    "Accounts for Simple Financial Ratios", "Accounts for Simple Financial Ratios", "Accounts for Simple Financial Ratios");
                $dfltAccntColNames = array("itm_inv_asst_acnt_id", "cost_of_goods_acnt_id", "expense_acnt_id", "prchs_rtrns_acnt_id",
                    "rvnu_acnt_id", "sales_rtrns_acnt_id", "inv_adjstmnts_lblty_acnt_id",
                    "sales_cash_acnt_id", "sales_check_acnt_id", "sales_rcvbl_acnt_id",
                    "sales_dscnt_accnt", "sales_lblty_acnt_id", "bad_debt_acnt_id",
                    "rcpt_cash_acnt_id", "petty_cash_acnt_id", "rcpt_lblty_acnt_id",
                    "prchs_dscnt_accnt", "rcpt_rcvbl_acnt_id",
                    "ttl_caa", "ttl_cla", "ttl_aa",
                    "ttl_la", "ttl_oea", "ttl_ra",
                    "ttl_cgsa", "ttl_ia", "ttl_pea");
                $exitErrMsg = "";
                $afftctd = 0;
                if (trim($slctdDfltAccnts, "|~") != "") {
                    $variousRows = explode("|", trim($slctdDfltAccnts, "|"));
                    for ($y = 0; $y < count($variousRows); $y++) {
                        $crntRow = explode("~", $variousRows[$y]);
                        if (count($crntRow) == 3) {
                            $ln_Ctgry = (cleanInputData1($crntRow[0]));
                            $ln_SysName = cleanInputData1($crntRow[1]);
                            $ln_AccountID = (int) cleanInputData1($crntRow[2]);
                            if ($ln_Ctgry != "" && $ln_AccountID > 0 && $ln_SysName != "") {
                                $indx1 = findArryIdx($systemTypeNm, $ln_SysName);
                                $ln_CtgryFnd = $systemTypeCtgry[$indx1];
                                if ($ln_CtgryFnd == $ln_Ctgry && $indx1 >= 0) {
                                    $afftctd += updateDfltAcnt($rowid, $dfltAccntColNames[$indx1], $ln_AccountID);
                                }
                            }
                        }
                    }
                }
                if ($exitErrMsg != "") {
                    $exitErrMsg = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>"
                            . "" . $afftctd . " Default Account(s) Saved Successfully!"
                            . "<br/><span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>" . $exitErrMsg . "</span>";
                } else {
                    $exitErrMsg = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>"
                            . "" . $afftctd . " Default Account(s) Saved Successfully!";
                }
                $arr_content['percent'] = 100;
                $arr_content['message'] = $exitErrMsg;
                echo json_encode($arr_content);
                exit();
            }
        } else {
            if ($vwtyp == 0) {
                //Assets & Investments
                echo $cntent . "<li onclick=\"openATab('#allmodules', 'grp=$group&typ=$type&pg=$pgNo&vtyp=0');\">
                                <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                <span style=\"text-decoration:none;\">Default Accounts</span>
                            </li>
                           </ul>
                          </div>";
                $error = "";
                $searchAll = true;
                $srchFor = isset($_POST['searchfor']) ? cleanInputData($_POST['searchfor']) : '';
                $srchIn = isset($_POST['searchin']) ? cleanInputData($_POST['searchin']) : 'Both';
                $pageNo = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
                $lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 100;
                $sortBy = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : "Trns. ID DESC";

                if (strpos($srchFor, "%") === FALSE) {
                    $srchFor = "%" . str_replace(" ", "%", $srchFor) . "%";
                    $srchFor = str_replace("%%", "%", $srchFor);
                }
                $result = get_Org_DfltAcnts($orgID);
                $cntr = 0;
                $colClassType1 = "col-lg-5";
                ?>
                <form id='allDfltAcntsForm' action='' method='post' accept-charset='UTF-8'>                        
                    <div class="row " style="margin-bottom:0px;padding:0px 15px 0px 15px !important;"> 
                        <div class="<?php echo $colClassType1; ?>" style="padding:0px 1px 0px 1px !important;"> 
                            <?php if ($canAdd || $canEdt) { ?>
                                <button type="button" class="btn btn-default btn-sm" style="margin-bottom: 5px;" onclick="saveAccbDfltAcnts('#allmodules', 'grp=6&typ=1&pg=15&vtyp=0');">
                                    <img src="cmn_images/FloppyDisk.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                    SAVE
                                </button>
                            <?php } ?>
                            <button type="button" class="btn btn-default btn-sm" style="margin-bottom: 5px;" onclick="openATab('#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=0');">
                                <img src="cmn_images/refresh.bmp" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                REFRESH
                            </button>
                        </div>
                    </div>
                    <div class="row"> 
                        <div  class="col-md-12">
                            <table class="table table-striped table-bordered table-responsive" id="allDfltAcntsTable" cellspacing="0" width="100%" style="width:100%;">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th style="max-width:250px;width:250px;">Category</th>
                                        <th style="max-width:250px;width:250px;">System Transaction Type</th>
                                        <th>Default GL Account</th>
                                        <?php if ($canVwRcHstry === true) { ?>
                                            <th>...</th>
                                        <?php } ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $systemTypeNm = array("Inventory/Asset Account", "Cost of Goods Sold Account", "Purchase Expense Account", "Purchase Returns Account",
                                        "Sales Revenue Account", "Sales Returns Account", "Item Receipts Accrual Account",
                                        "Cash Payments Accounts", "Check Payments Account", "Receivables Account",
                                        "Sales Discount Account", "Customer Advance Payments Account", "Bad Debt Expense Account",
                                        "Supplier Cash Payments Accounts", "Petty Cash Account", "Liability Account",
                                        "Purchasing Discount Account", "Supplier Advance Account",
                                        "Total Current Assets Account", "Total Current Liabilities Account", "Total Assets Account",
                                        "Total Liabilities Account", "Total Owner's Equity Account", "Total Revenues Account",
                                        "Total Cost of Goods Sold Account", "Total Inventories Account", "Total Prepaid Expenses Account");
                                    $systemTypeCtgry = array("Inventory Item Accounts", "Inventory Item Accounts", "Inventory Item Accounts", "Inventory Item Accounts",
                                        "Inventory Item Accounts", "Inventory Item Accounts", "Inventory Item Accounts",
                                        "Sales/Receivables Accounts", "Sales/Receivables Accounts", "Sales/Receivables Accounts",
                                        "Sales/Receivables Accounts", "Sales/Receivables Accounts", "Sales/Receivables Accounts",
                                        "Purchasing/Payables Accounts", "Purchasing/Payables Accounts", "Purchasing/Payables Accounts",
                                        "Purchasing/Payables Accounts", "Purchasing/Payables Accounts",
                                        "Accounts for Simple Financial Ratios", "Accounts for Simple Financial Ratios", "Accounts for Simple Financial Ratios",
                                        "Accounts for Simple Financial Ratios", "Accounts for Simple Financial Ratios", "Accounts for Simple Financial Ratios",
                                        "Accounts for Simple Financial Ratios", "Accounts for Simple Financial Ratios", "Accounts for Simple Financial Ratios");
                                    $row_id = -1;
                                    $itm_inv_asst_acnt_id = -1;
                                    $itm_inv_asst_acnt = "";
                                    $cost_of_goods_acnt_id = -1;
                                    $cost_of_goods_acnt = "";
                                    $expense_acnt_id = -1;
                                    $expense_acnt = "";
                                    $prchs_rtrns_acnt_id = -1;
                                    $prchs_rtrns_acnt = "";
                                    $rvnu_acnt_id = -1;
                                    $rvnu_acnt = "";
                                    $sales_rtrns_acnt_id = -1;
                                    $sales_rtrns_acnt = "";
                                    $sales_cash_acnt_id = -1;
                                    $sales_cash_acnt = "";
                                    $sales_check_acnt_id = -1;
                                    $sales_check_acnt = "";
                                    $sales_rcvbl_acnt_id = -1;
                                    $sales_rcvbl_acnt = "";
                                    $rcpt_cash_acnt_id = -1;
                                    $rcpt_cash_acnt = "";
                                    $rcpt_lblty_acnt_id = -1;
                                    $rcpt_lblty_acnt = "";
                                    $inv_adjstmnts_lblty_acnt_id = -1;
                                    $inv_adjstmnts_lblty_acnt = "";
                                    $ttl_caa = -1;
                                    $ttl_caa_nm = "";
                                    $ttl_cla = -1;
                                    $ttl_cla_nm = "";
                                    $ttl_aa = -1;
                                    $ttl_aa_nm = "";
                                    $ttl_la = -1;
                                    $ttl_la_nm = "";
                                    $ttl_oea = -1;
                                    $ttl_oea_nm = "";
                                    $ttl_ra = -1;
                                    $ttl_ra_nm = "";
                                    $ttl_cgsa = -1;
                                    $ttl_cgsa_nm = "";
                                    $ttl_ia = -1;
                                    $ttl_ia_nm = "";
                                    $ttl_pea = -1;
                                    $ttl_pea_nm = "";
                                    $sales_dscnt_accnt = -1;
                                    $sales_dscnt_accnt_nm = "";
                                    $prchs_dscnt_accnt = -1;
                                    $prchs_dscnt_accnt_nm = "";
                                    $sales_lblty_acnt_id = -1;
                                    $sales_lblty_acnt_id_nm = "";
                                    $bad_debt_acnt_id = -1;
                                    $bad_debt_acnt_id_nm = "";
                                    $rcpt_rcvbl_acnt_id = -1;
                                    $rcpt_rcvbl_acnt_nm = "";
                                    $petty_cash_acnt_id = -1;
                                    $petty_cash_acnt_nm = "";
                                    $dfltAccntID = -1;
                                    $dfltAccntName = "";
                                    if ($row = loc_db_fetch_array($result)) {
                                        $row_id = $row[0];
                                        $itm_inv_asst_acnt_id = $row[1];
                                        $itm_inv_asst_acnt = $row[2];
                                        $cost_of_goods_acnt_id = $row[3];
                                        $cost_of_goods_acnt = $row[4];
                                        $expense_acnt_id = $row[5];
                                        $expense_acnt = $row[6];

                                        $prchs_rtrns_acnt_id = $row[7];
                                        $prchs_rtrns_acnt = $row[8];
                                        $rvnu_acnt_id = $row[9];
                                        $rvnu_acnt = $row[10];
                                        $sales_rtrns_acnt_id = $row[11];
                                        $sales_rtrns_acnt = $row[12];
                                        $sales_cash_acnt_id = $row[13];
                                        $sales_cash_acnt = $row[14];

                                        $sales_check_acnt_id = $row[15];
                                        $sales_check_acnt = $row[16];
                                        $sales_rcvbl_acnt_id = $row[17];
                                        $sales_rcvbl_acnt = $row[18];
                                        $rcpt_cash_acnt_id = $row[19];
                                        $rcpt_cash_acnt = $row[20];
                                        $rcpt_lblty_acnt_id = $row[21];
                                        $rcpt_lblty_acnt = $row[22];

                                        $inv_adjstmnts_lblty_acnt_id = $row[23];
                                        $inv_adjstmnts_lblty_acnt = $row[24];
                                        $ttl_caa = $row[25];
                                        $ttl_caa_nm = $row[26];
                                        $ttl_cla = $row[27];
                                        $ttl_cla_nm = $row[28];
                                        $ttl_aa = $row[29];
                                        $ttl_aa_nm = $row[30];
                                        $ttl_la = $row[31];

                                        $ttl_la_nm = $row[32];
                                        $ttl_oea = $row[33];
                                        $ttl_oea_nm = $row[34];
                                        $ttl_ra = $row[35];
                                        $ttl_ra_nm = $row[36];
                                        $ttl_cgsa = $row[37];
                                        $ttl_cgsa_nm = $row[38];
                                        $ttl_ia = $row[39];
                                        $ttl_ia_nm = $row[40];
                                        $ttl_pea = $row[41];
                                        $ttl_pea_nm = $row[42];
                                        $sales_dscnt_accnt = $row[43];
                                        $sales_dscnt_accnt_nm = $row[44];
                                        $prchs_dscnt_accnt = $row[45];
                                        $prchs_dscnt_accnt_nm = $row[46];
                                        $sales_lblty_acnt_id = $row[47];
                                        $sales_lblty_acnt_id_nm = $row[48];
                                        $bad_debt_acnt_id = $row[49];
                                        $bad_debt_acnt_id_nm = $row[50];
                                        $rcpt_rcvbl_acnt_id = $row[51];
                                        $rcpt_rcvbl_acnt_nm = $row[52];
                                        $petty_cash_acnt_id = $row[53];
                                        $petty_cash_acnt_nm = $row[54];
                                    }
                                    while ($cntr < count($systemTypeNm)) {
                                        switch ($cntr) {
                                            case 0:
                                                $dfltAccntID = $itm_inv_asst_acnt_id;
                                                $dfltAccntName = $itm_inv_asst_acnt;
                                                break;
                                            case 1:
                                                $dfltAccntID = $cost_of_goods_acnt_id;
                                                $dfltAccntName = $cost_of_goods_acnt;
                                                break;
                                            case 2:
                                                $dfltAccntID = $expense_acnt_id;
                                                $dfltAccntName = $expense_acnt;
                                                break;
                                            case 3:
                                                $dfltAccntID = $prchs_rtrns_acnt_id;
                                                $dfltAccntName = $prchs_rtrns_acnt;
                                                break;
                                            case 4:
                                                $dfltAccntID = $rvnu_acnt_id;
                                                $dfltAccntName = $rvnu_acnt;
                                                break;
                                            case 5:
                                                $dfltAccntID = $sales_rtrns_acnt_id;
                                                $dfltAccntName = $sales_rtrns_acnt;
                                                break;
                                            case 6:
                                                $dfltAccntID = $inv_adjstmnts_lblty_acnt_id;
                                                $dfltAccntName = $inv_adjstmnts_lblty_acnt;
                                                break;
                                            case 7:
                                                $dfltAccntID = $sales_cash_acnt_id;
                                                $dfltAccntName = $sales_cash_acnt;
                                                break;
                                            case 8:
                                                $dfltAccntID = $sales_check_acnt_id;
                                                $dfltAccntName = $sales_check_acnt;
                                                break;
                                            case 9:
                                                $dfltAccntID = $sales_rcvbl_acnt_id;
                                                $dfltAccntName = $sales_rcvbl_acnt;
                                                break;
                                            case 10:
                                                $dfltAccntID = $sales_dscnt_accnt;
                                                $dfltAccntName = $sales_dscnt_accnt_nm;
                                                break;
                                            case 11:
                                                $dfltAccntID = $sales_lblty_acnt_id;
                                                $dfltAccntName = $sales_lblty_acnt_id_nm;
                                                break;
                                            case 12:
                                                $dfltAccntID = $bad_debt_acnt_id;
                                                $dfltAccntName = $bad_debt_acnt_id_nm;
                                                break;
                                            case 13:
                                                $dfltAccntID = $rcpt_cash_acnt_id;
                                                $dfltAccntName = $rcpt_cash_acnt;
                                                break;
                                            case 14:
                                                $dfltAccntID = $petty_cash_acnt_id;
                                                $dfltAccntName = $petty_cash_acnt_nm;
                                                break;
                                            case 15:
                                                $dfltAccntID = $rcpt_lblty_acnt_id;
                                                $dfltAccntName = $rcpt_lblty_acnt;
                                                break;
                                            case 16:
                                                $dfltAccntID = $prchs_dscnt_accnt;
                                                $dfltAccntName = $prchs_dscnt_accnt_nm;
                                                break;
                                            case 17:
                                                $dfltAccntID = $rcpt_rcvbl_acnt_id;
                                                $dfltAccntName = $rcpt_rcvbl_acnt_nm;
                                                break;
                                            case 18:
                                                $dfltAccntID = $ttl_caa;
                                                $dfltAccntName = $ttl_caa_nm;
                                                break;
                                            case 19:
                                                $dfltAccntID = $ttl_cla;
                                                $dfltAccntName = $ttl_cla_nm;
                                                break;
                                            case 20:
                                                $dfltAccntID = $ttl_aa;
                                                $dfltAccntName = $ttl_aa_nm;
                                                break;
                                            case 21:
                                                $dfltAccntID = $ttl_la;
                                                $dfltAccntName = $ttl_la_nm;
                                                break;
                                            case 22:
                                                $dfltAccntID = $ttl_oea;
                                                $dfltAccntName = $ttl_oea_nm;
                                                break;
                                            case 23:
                                                $dfltAccntID = $ttl_ra;
                                                $dfltAccntName = $ttl_ra_nm;
                                                break;
                                            case 24:
                                                $dfltAccntID = $ttl_cgsa;
                                                $dfltAccntName = $ttl_cgsa_nm;
                                                break;
                                            case 25:
                                                $dfltAccntID = $ttl_ia;
                                                $dfltAccntName = $ttl_ia_nm;
                                                break;
                                            case 26:
                                                $dfltAccntID = $ttl_pea;
                                                $dfltAccntName = $ttl_pea_nm;
                                                break;
                                            default:
                                                $dfltAccntID = -1;
                                                $dfltAccntName = "";
                                                break;
                                        }
                                        ?>
                                        <tr id="allDfltAcntsRow_<?php echo $cntr; ?>">                                    
                                            <td class="lovtd"><?php echo ($cntr + 1); ?></td>
                                            <td class="lovtd">
                                                <?php echo $systemTypeCtgry[$cntr]; ?>
                                                <input type="hidden" id="allDfltAcntsRow<?php echo $cntr; ?>_Ctgry" value="<?php echo $systemTypeCtgry[$cntr]; ?>">
                                            </td>
                                            <td class="lovtd">
                                                <?php echo $systemTypeNm[$cntr]; ?>
                                                <input type="hidden" id="allDfltAcntsRow<?php echo $cntr; ?>_SysName" value="<?php echo $systemTypeNm[$cntr]; ?>">
                                            </td>
                                            <td class="lovtd">
                                                <input type="hidden" id="allDfltAcntsRow<?php echo $cntr; ?>_GLAcntID" value="<?php echo $dfltAccntID; ?>">
                                                <?php if ($canEdt) { ?>                                                        
                                                    <div class="input-group">
                                                        <input class="form-control rqrdFld" id="allDfltAcntsRow<?php echo $cntr; ?>_GLAcntNm" style="font-size: 13px !important;font-weight: bold !important;" placeholder="Enter GL Account Number" type = "text" min="0" placeholder="" value="<?php echo $dfltAccntName; ?>" readonly="true"/>
                                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Accounts', 'allOtherInputOrgID', '', '', 'radio', true, '', 'allDfltAcntsRow<?php echo $cntr; ?>_GLAcntID', 'allDfltAcntsRow<?php echo $cntr; ?>_GLAcntNm', 'clear', 1, '', function () {});">
                                                            <span class="glyphicon glyphicon-th-list"></span>
                                                        </label>
                                                    </div>
                                                    <?php
                                                } else {
                                                    echo $dfltAccntName;
                                                }
                                                ?>
                                            </td>
                                            <?php if ($canVwRcHstry === true) { ?>
                                                <td class="lovtd">
                                                    <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" onclick="getRecHstry('<?php
                                                    echo urlencode(encrypt1(($row_id . "|scm.scm_dflt_accnts|row_id"), $smplTokenWord1));
                                                    ?>');" style="padding:2px !important;">
                                                        <img src="cmn_images/Information.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                    </button>
                                                </td>
                                            <?php } ?>
                                        </tr>
                                        <?php
                                        $cntr += 1;
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>                     
                    </div>
                </form>
                <?php
            }
        }
    }
}
?>