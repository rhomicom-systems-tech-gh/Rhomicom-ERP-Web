<?php
if (array_key_exists('lgn_num', get_defined_vars())) {
    $prsnid = $_SESSION['PRSN_ID'];
    $orgID = $_SESSION['ORG_ID'];
    $lnkdFirmID = getGnrlRecNm("prs.prsn_names_nos", "person_id", "lnkd_firm_org_id", $prsnid);
    //$pkID = $PKeyID;
       
   
    if ($subPgNo == 5.2) {//INVESTMENTS
        $trnsType = "INVESTMENT";
        $formTitle = "Investment Attached Document";
        if ($vwtyp == "0") {
            /* BASIC DATA */
           $pkID = isset($_POST['invstmntID']) ? cleanInputData($_POST['invstmntID']) : -1; 
            
            if ($vwtypActn == "EDIT" || $vwtypActn == "VIEW" || $vwtypActn === "ADD") {

                $sbmtdTrnsHdrID = $pkID;
                $voidedTrnsHdrID = -1;
                $rqstatusColor = "red";
                $ttlColor = "blue";
                $mtrtyColor = "green";
                $apprvdAmntColor = "blue";
                $isPaidColor = "blue";
                $mkReadOnly = "";
                $mkReadOnlyDsbld = "";
                $mkRmrkReadOnly = "";  
                $mkReadOnlyDsbldRlvr = "disabled=\"true\"";
                $trnsTtl = 0.00;
                $crncy = "GHS";
                $crncyID = ""; 
                $isDisbursed = "NO";
                $payDate = "";
                $routingID = getMCFMxRoutingID($sbmtdTrnsHdrID, "Investments");
                $cashCollateralValue = 0.00;
                $prptyCollateralValue = 0.00;
                $invstmntCollateralValue = 0.00;
                
               $trnsStatusDsply = "Incomplete, Unprocessed";
               $trnsStatus = "Incomplete";
               $svngs_product_id = -1;
               $prdtNm = "";
               $amount = 0.00;
               $tenor = "";
               $tenor_type = "";
               $shd_rollover = "No";
               $rollover_type = "";
               $ifo_name = "";
               $ifo_contact = "";
               $interest_rate = "";
               $discount_rate = "";
               $rate_type = "";
               $pay_back_method = "";
               $running_interest_bal = 0.00;
               $payback_crdt_acct_id = -1;
               $paybackAcct = "";
               $trnsctn_no = "";
               $application_date = "";
               $trnsBranchID = get_Person_BranchID($prsnid);
               $trnsBranch = getGnrlRecNm("org.org_sites_locations", "location_id", "site_desc||' ('||location_code_name||')'", "pasn.get_prsn_siteid($prsnid)"); 
               $account_id = -1;
               $accountNo = "";
               $pymnt_method = "";
               $cash_chq_pymnt_acct_trns_id = -1;
               $pymnt_dbt_acct_id = -1;
               $pymntMtdSrcID = -1;
               $pymntMtdSrc = "";
               $cust_type = "";
               $cash_payback_acct_trns_id = -1;
               $payback_chq_no = "";
               $rdscnt_rqst_date = "";
               $cust_id = -1; 
               $custNm = "";
               $invstmnt_officer_id = $prsnid;
               $invstmnt_officer= getGnrlRecNm("prs.prsn_names_nos", "person_id", "trim(title || ' ' || sur_name ||"
                       . "', ' || first_name || ' ' || other_names)", $invstmnt_officer_id);
               $invstmnt_type = "Please Select";
               $start_date = "";
               $end_date = "";
               $maturity_value = "";
               $crncyIsoCode = "GHS";
               $pymntMtdScrLbl = "Account No.:";
               $crdtAcctChqNoDsply = "style=\"display:none;\"";
               $crdtAcctNoDsply = "style=\"display:block;\"";
               
               $invstmntMaxAmount = "";
               $invstmntMinAmount = "";
               $intRate = "";
               $dscntRate = "";
               $currencyId = "";
                
                $result = getInvestmentDet($pkID);
                while ($row = loc_db_fetch_array($result)) {
                    
                    $svngs_product_id = $row[1];
                    $prdtNm = getGnrlRecNm("mcf.mcf_prdt_savings", "svngs_product_id", "product_name", $svngs_product_id);
                    $amount = $row[2];
                    $tenor = $row[3];
                    $tenor_type = $row[4];
                    $shd_rollover = $row[5];
                    $rollover_type = $row[6];
                    $cust_id = $row[7]; 
                    $custNm = getCustomerName($row[24], $cust_id);
                    $ifo_name = $row[8];
                    $ifo_contact = $row[9];
                    $interest_rate = $row[11];
                    $discount_rate = $row[10];
                    $rate_type = $row[12];
                    $pay_back_method = $row[13];
                    if($pay_back_method == "Cheque Payment"){
                        $crdtAcctChqNoDsply ="style=\"display:block;\"";
                        $crdtAcctNoDsply ="style=\"display:none;\"";
                    }
                    $running_interest_bal = $row[14];
                    $payback_crdt_acct_id = $row[15];
                    $paybackAcct = getGnrlRecNm("mcf.mcf_accounts", "account_id", "account_number", $payback_crdt_acct_id);
                    $trnsStatus = $row[16];
                    $trnsStatusDsply = $row[16].", ".$row[33];
                    $trnsctn_no = $row[17];
                    $application_date = $row[18];
                    $trnsBranchID = $row[19];
                    $trnsBranch = getGnrlRecNm("org.org_sites_locations", "location_id", "site_desc||' ('||location_code_name||')'", $trnsBranchID); 
                    $account_id = $row[20];
                    $accountNo = getGnrlRecNm("mcf.mcf_accounts", "account_id", "account_number", $row[20]);
                    $pymnt_method = $row[21];
                    $cash_chq_pymnt_acct_trns_id = $row[22];
                    $pymnt_dbt_acct_id = $row[23];
                    if($pymnt_method == "Debit Account"){
                        $pymntMtdSrcID = $pymnt_dbt_acct_id;
                        $pymntMtdSrc= getGnrlRecNm("mcf.mcf_accounts", "account_id", "account_number", $pymnt_dbt_acct_id);
                        $pymntMtdScrLbl = "Account No.:";
                    } else {
                        $pymntMtdSrcID = $cash_chq_pymnt_acct_trns_id;
                        $pymntMtdSrc = getGnrlRecNm("mcf.mcf_cust_account_transactions", "acct_trns_id", "doc_no", $cash_chq_pymnt_acct_trns_id);
                        $pymntMtdScrLbl = "Document No.:";
                    }
                    $cust_type = $row[24];
                    $cash_payback_acct_trns_id = $row[25];
                    $payback_chq_no = $row[32];
                    $rdscnt_rqst_date = $row[26];
                    $invstmnt_officer_id = $row[27];
                    $invstmnt_officer = getGnrlRecNm("prs.prsn_names_nos", "person_id", "trim(title || ' ' || sur_name || ', ' || first_name || ' ' || other_names)", $invstmnt_officer_id);
                    $invstmnt_type = $row[28];
                    $start_date = $row[29];
                    $end_date = $row[30];
                    $maturity_value = $row[31];
                    $crncyIsoCode = getGnrlRecNm("mcf.mcf_currencies", "crncy_id", "iso_code", 
                            ("(SELECT currency_id FROM mcf.mcf_prdt_savings WHERE svngs_product_id = $svngs_product_id)"));                    

                    $trnsTtl = (float) $row[2];  
                    $isDisbursed = $trnsStatus;
                    
                    $result3 = get_InvstmntPrdtDetsForApplctn($row[1]);
                    $row3 = loc_db_fetch_array($result3);
                    $invstmntMaxAmount = $row3[8];
                    $invstmntMinAmount = $row3[9];
                    $intRate = $row3[2];
                    $dscntRate = $row3[7];
                    $currencyId = $row3[1];


                    if ($vwtypActn == "VIEW" ||($trnsStatus == "Initiated" || $trnsStatus == "Unauthorized" || $trnsStatus == "Approved"  || $trnsStatus == "Authorized" || $trnsStatus == "Voided")) {
                        $mkReadOnly = "readonly=\"true\"";
                        $mkReadOnlyDsbld = "disabled=\"true\"";
                        $mkReadOnlyDsbldRlvr = "disabled=\"true\"";
                    }
                    
                }
                    ?>
                    <input class="form-control" id="addOrEditForm" type = "hidden" placeholder="addOrEditForm" value="Edit"/>                   
                    <div class="">
                        <div class="row">                  
                            <div class="col-md-12">
                                <div class="col-md-12" style="padding:0px 0px 10px 1px !important;">
                                    <div style="padding:0px 1px 0px 15px !important;float:left;">
                                            <button type="button" class="btn btn-default btn-sm" style="height:30px;" id="myVmsTrnsStatusBtn">
                                                    <input type="text" style="display:none !important;" class="form-control" id="invstmntHdrStatus" placeholder="Status" value="<?php echo $trnsStatusDsply; ?>"/>
                                                    <span style="font-weight:bold;">Status: </span><span style="color:<?php echo $rqstatusColor; ?>;font-weight: bold;"><?php echo $trnsStatusDsply; ?></span>
                                            </button>
                                            <?php if(($trnsStatus == "Incomplete" || $trnsStatus == "Rejected" || $trnsStatus == "Withdrawn") && ($pkID > 0) && $vwtypActn != "VIEW") { ?>
                                            <button type="button" class="btn btn-default btn-sm" style="height:30px;" onclick="getInvestmentsForm('myFormsModalLg', 'myFormsModalBodyLg', 'myFormsModalTitleLg', 'Edit Investment', 25, 5.2,0,'EDIT', <?php echo $sbmtdTrnsHdrID; ?>,'indCustTableRow1');" data-toggle="tooltip" title="Reload Transaction">
                                                    <img src="cmn_images/refresh.bmp" style="padding-right: 2px; height:17px; width:auto; position: relative; vertical-align: middle;">
                                            </button>
                                            <?php } ?>
                                            <button type="button" class="btn btn-default btn-sm" style="height:30px;" id="myVmsTrnsTtlBtn">
                                                    <span style="font-weight:bold;">Interest Earned: </span><span style="color:<?php echo $ttlColor; ?>;font-weight: bold;"><?php echo $crncyIsoCode." ".number_format($running_interest_bal,2); ?></span>
                                            </button>
                                            <?php if($pkID > 0){ ?>
                                            <button type="button" class="btn btn-default" style="height:30px;" onclick="getOneMcfDocsForm_Gnrl(<?php echo $pkID; ?>,'<?php echo $trnsType; ?>', 140, '<?php echo $formTitle; ?>');" data-toggle="tooltip" data-placement="bottom" title = "Attached Documents">
                                                <img src="cmn_images/adjunto.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                            </button>
                                            <?php } ?>
                                            <button type="button" class="btn btn-default" style="height:30px;" onclick="getInvestmentsForm('myFormsModalLg', 'myFormsModalBodyLg', 'myFormsModalTitleLg', 'Add New Investment', 25, <?php echo $subPgNo; ?>,0,'ADD', -1);" data-toggle="tooltip" data-placement="bottom" title = "Clear Form">
                                                <img src="cmn_images/undo_256.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                            </button>
                                    </div>
                                    <div style="padding:0px 1px 0px 1px !important;float:right;">
                                            <?php if (($trnsStatus == "Incomplete" || $trnsStatus == "Withdrawn" || $trnsStatus == "Rejected") && $vwtypActn != "VIEW") { ?>                                                    
                                                    <button type="button" class="btn btn-default btn-sm" style="height:30px;" onclick="saveInvstmntApplication(0);"><img src="cmn_images/FloppyDisk.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Save&nbsp;</button>
                                                    <button type="button" class="btn btn-default btn-sm" style="height:30px;" onclick="saveInvstmntApplication(1);"><img src="cmn_images/Emailcon.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Submit&nbsp;</button>   
                                            <?php 
                                            } else if (($trnsStatus == "Initiated" || $trnsStatus == "Unauthorized") && $vwtypActn != "VIEW") {
                                                    ?>                                    
                                                    <button type="button" class="btn btn-default btn-sm" style="height:30px;" onclick="withdrawRejectApproveVoidActn(0);"><img src="cmn_images/withdraw_rqst.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Withdraw&nbsp;</button>                                      
                                                    <button type="button" class="btn btn-default btn-sm" style="height:30px;" onclick="withdrawRejectApproveVoidActn(3);"><img src="cmn_images/Stamp-512.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Authorize&nbsp;</button>                                                                                                        
                                                    <button type="button" class="btn btn-default btn-sm" style="height:30px;" onclick="checkWkfRqstStatus(<?php echo $routingID; ?>, 'Teller Trns. Authorization Progress History');"><img src="cmn_images/workflow.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Progress&nbsp;</button>                                
                                                            <?php
                                                    } else if (($trnsStatus == "Approved" || $trnsStatus == "Authorized") && $vwtypActn != "VIEW") {
                                                            ?>
                                                    <button type="button"  class="btn btn-default btn-sm" style="display:none; height:30px;" onclick="checkWkfRqstStatus(<?php echo $routingID; ?>, 'Teller Trns. Authorization Progress History');"><img src="cmn_images/workflow.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;" data-toggle="tooltip" title="Authorization Progress History">Progress&nbsp;</button>                                 
                                            <?php } ?>
                                            <?php
                                            if ($trnsStatus == "Approved" || $trnsStatus == "Authorized") {
                                                    $reportTitle = "Withdrawal Transaction";
                                                    $reportName = "Teller Transaction Receipt";
                                                    $rptID = getRptID($reportName);
                                                    $prmID1 = getParamIDUseSQLRep("{:invoice_id}", $rptID);
                                                    $prmID2 = getParamIDUseSQLRep("{:documentTitle}", $rptID);
                                                    $invcID = $sbmtdTrnsHdrID;
                                                    $paramRepsNVals = $prmID1 . "~" . $invcID . "|" . $prmID2 . "~" . $reportTitle . "|-190~PDF";
                                                    $paramStr = urlencode($paramRepsNVals);
                                                    ?>
                                                    <?php if($trnsStatusDsply == "Authorized, Unprocessed") { ?>
                                                    <button type="button" class="btn btn-primary" onclick="processFixedDepositInvestment(0);" >Process</button>
                                                        <?php } else if($trnsStatusDsply == "Authorized, Processed") { ?>
                                                    <button type="button" class="btn btn-default btn-sm" style="height:30px;" onclick="processFixedDepositInvestment(1);"><img src="cmn_images/90.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Void&nbsp;</button>
                                                    <?php } ?>                                                    
                                                    <button type="button" class="btn btn-default btn-sm" style="height:30px;" title="Get Voucher on Thermal Receipt Paper" onclick="getSilentRptsRnSts(<?php echo $rptID; ?>, - 1, '<?php echo $paramStr; ?>');">
                                                            <img src="cmn_images/printer-icon.png" style="left: 0.5%; padding-right: 5px; height:17px; width:17px; position: relative; vertical-align: middle;">
                                                            POS
                                                    </button> 
                                                    <button type="button" class="btn btn-default btn-sm" style="height:30px;" title="Get Voucher on A4" onclick="getSilentRptsRnSts(<?php echo $rptID; ?>, - 1, '<?php echo $paramStr; ?>');">
                                                            <img src="cmn_images/printer-icon.png" style="left: 0.5%; padding-right: 5px; height:17px; width:17px; position: relative; vertical-align: middle;">
                                                            A4
                                                    </button>                                                
                                                            <?php
                                            }
                                            ?>
                                    </div>
                                </div>                                            
                                <form class="form-horizontal" id="customerAccountForm">
                                    <div style="display:block;">
                                        <!--DATA FOR VALIDATIONS-->
                                        <input type="hidden" id="currencyId" value="<?php echo $currencyId; ?>">
                                        <input type="hidden" id="invstmntMaxAmount" value="<?php echo $invstmntMaxAmount; ?>">
                                        <input type="hidden" id="invstmntMinAmount" value="<?php echo $invstmntMinAmount; ?>">
                                        <input type="hidden" id="intRate" value="<?php echo $intRate; ?>">
                                        <input type="hidden" id="dscntRate" value="<?php echo $dscntRate; ?>">
                                    </div>                                                
                                    <div class="row"><!-- ROW 1 -->
                                        <div class="col-lg-12">
                                            <!--<fieldset class="basic_person_fs5">-->
                                            <legend class="basic_person_lg1" style="margin-top:5px !important;margin-bottom: 10px !important;">Investment Header</legend>
                                            <div class="col-lg-4">
                                                <input class="form-control" id="invstmntID" type = "hidden" placeholder="Investment ID" value="<?php echo $sbmtdTrnsHdrID; ?>"/>                                                        
                                                <div class="form-group form-group-sm">
                                                    <label for="trnsctnNo" class="control-label col-md-4">Transaction No:</label>
                                                    <div class="col-md-8">
                                                        <input class="form-control" id="trnsctnNo" type = "text" placeholder="Transaction No." value="<?php echo $trnsctn_no; ?>" readonly/>                                                                                                                                            
                                                    </div>
                                                </div>
                                                <div class="form-group form-group-sm">
                                                    <label for="applctnDate" class="control-label col-md-4">Request Date:</label>
                                                    <div  class="col-md-8">
                                                        <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input3" data-link-format="yyyy-mm-dd">
                                                            <input class="form-control" size="16" type="text" id="applctnDate" value="<?php echo $application_date; ?>" readonly="">
                                                            <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                                            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                        </div>
                                                    </div>
                                                </div> 
                                            </div>                                                          
                                            <div class="col-lg-4">
                                                <div class="form-group form-group-sm">
                                                    <label for="bnkBranch" class="control-label col-md-4">Branch:</label>
                                                    <div  class="col-md-8">
                                                        <div class="input-group">
                                                            <input type="text" class="form-control" aria-label="..." id="bnkBranch" value="<?php echo $trnsBranch; ?>" readonly="">
                                                            <input type="hidden" id="bnkBranchID" value="<?php echo $trnsBranchID; ?>">  
                                                            <input type="hidden" id="gnrlOrgID" value="<?php echo $orgID; ?>">
                                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Sites/Locations New', 'gnrlOrgID', '', '', 'radio', true, '<?php echo $trnsBranchID; ?>', 'bnkBranchID', 'bnkBranch', 'clear', 1, '');">
                                                                <span class="glyphicon glyphicon-th-list"></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div> 
                                                <div class="form-group form-group-sm">
                                                    <label for="invstmntOfficer" class="control-label col-md-4">Officer:</label>
                                                    <div  class="col-md-8">
                                                        <input type="text" class="form-control" aria-label="..." id="invstmntOfficer" value="<?php echo $invstmnt_officer; ?>" readonly>
                                                        <input type="hidden" id="invstmntOfficerID" value="<?php echo $invstmnt_officer_id; ?>">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="form-group form-group-sm">
                                                    <label for="acctNumber" class="control-label col-md-4">Account No.:</label>
                                                    <div class="col-md-8">
                                                        <input class="form-control" id="acctNumber" type = "text" placeholder="" value="<?php echo $accountNo; ?>" readonly/>
                                                        <input class="form-control" id="accountID" type = "hidden" placeholder="Account ID" value="<?php echo $account_id; ?>"/>                                                                                                                                            
                                                    </div>
                                                </div>                                                             
                                                <div class="form-group form-group-sm">
                                                    <label for="ttlInterest" class="control-label col-md-4">Total Interest:</label>
                                                    <div class="col-md-8">
                                                        <div class="input-group">
                                                            <label id="crncyIsoCode" class="btn btn-primary btn-file input-group-addon">
                                                                <?php echo $crncyIsoCode; ?>
                                                            </label>		
                                                            <input class="form-control" id="ttlInterest" type = "text" style="color:<?php echo $rqstatusColor; ?>;font-weight: bold;" placeholder="" value="<?php echo number_format(($maturity_value - $amount),2); ?>" readonly/>
                                                        </div>                                                                                                                                        
                                                        <input class="form-control" id="status" type = "hidden" style="color:<?php echo $rqstatusColor; ?>;font-weight: bold;" placeholder="" value="<?php echo $trnsStatus; ?>" readonly/>                                                                                                                                        
                                                    </div>
                                                </div>                                                            

                                            </div>
                                            <!--</fieldset>-->
                                        </div>
                                    </div>
                                    <div class="row"><!-- ROW 2 -->
                                        <div class="col-lg-12">
                                            <!--<fieldset class="basic_person_fs1" style="margin-top:5px !important;">-->
                                            <legend class="basic_person_lg1" style="margin-top:10px !important;margin-bottom: 10px !important;">Investment Details</legend>                                                     
                                            <div class="row"><!-- ROW 1 -->
                                                <div class="col-lg-6">
                                                    <fieldset class="basic_person_fs1"><legend class="basic_person_lg1">Request Details</legend>
                                                        <div class="form-group form-group-sm">
                                                            <label for="custType" class="control-label col-md-4">Customer Type:</label>
                                                            <input type="hidden" id="custTypeYes" value="YES">
                                                            <div  class="col-md-8">
                                                                <select <?php echo $mkReadOnlyDsbld; ?> class="form-control" id="custType" onchange="onChangeOfCustType()">
                                                                    <?php
                                                                    $sltdCorp = "";
                                                                    $sltdIndv = "";
                                                                    $sltdGrp = "";
                                                                    if ($cust_type == "Corporate") {
                                                                        $sltdCorp = "selected";
                                                                    } else if ($cust_type == "Individual") {
                                                                        $sltdIndv = "selected";
                                                                    } else if ($cust_type == "Group") {
                                                                        $sltdGrp = "selected";
                                                                    }
                                                                    ?>
                                                                    <option value="Corporate" <?php echo $sltdCorp; ?>>Corporate</option>
                                                                    <option value="Individual" <?php echo $sltdIndv; ?>>Individual</option>
                                                                    <option value="Group" <?php echo $sltdGrp; ?>>Customer Group</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-group-sm">
                                                            <label for="bnkCustomer" class="control-label col-md-4">Customer Name:</label>
                                                            <div  class="col-md-8">
                                                                <div class="input-group">
                                                                    <input type="text" class="form-control" aria-label="..." id="bnkCustomer" value="<?php echo $custNm; ?>" readonly="readonly">
                                                                    <input type="hidden" id="bnkCustomerID" value="<?php echo $cust_id; ?>">
                                                                    <input type="hidden" id="gnrlOrgID" value="<?php echo $orgID; ?>">
                                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="onClickInvstmntPrdtCust(1);">
                                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-group-sm">
                                                            <label for="invstmntType" class="control-label col-md-4">Investment Type:</label>
                                                            <div  class="col-md-8">
                                                                <select <?php echo $mkReadOnlyDsbld; ?> class="form-control" id="invstmntType" onchange="onChangeInvstmntType();"> 
                                                                    <?php
                                                                    $sltdTB = "";
                                                                    $sltdFD = "";
                                                                    if ($invstmnt_type == "Treasury Bill") {
                                                                        $sltdTB = "selected";
                                                                    } else if ($invstmnt_type == "Fixed Deposit") {
                                                                        $sltdFD = "selected";
                                                                    }
                                                                    ?>
                                                                    <!--<option value="Treasury Bill">Treasury Bill</option>-->
                                                                    <option value="Fixed Deposit" <?php echo $sltdFD; ?>>Fixed Deposit</option>
                                                                </select>
                                                            </div>
                                                        </div>                                                                    
                                                        <div class="form-group form-group-sm">
                                                            <label for="prdtType" class="control-label col-md-4">Product:</label>
                                                            <div  class="col-md-8">
                                                                <div class="input-group">
                                                                    <input type="text" class="form-control" aria-label="..." id="prdtType" value="<?php echo $prdtNm; ?>" readonly="readonly">
                                                                    <input type="hidden" id="prdtTypeID" value="<?php echo $svngs_product_id; ?>">
                                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="onClickInvstmntProduct(<?php echo $pgNo; ?>,<?php echo $subPgNo; ?>);">
                                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                                    </label>
                                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="dsplyPrdtForm();" data-toggle="tooltip" data-placement="bottom" title = "View Product">
                                                                            <span class="glyphicon glyphicon-info-sign"></span>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div> 
                                                        <div class="form-group form-group-sm">
                                                            <label for="invstmntAmount" class="control-label col-md-4">Amount:</label>
                                                            <div  class="col-md-8">
                                                                <div class="input-group">
                                                                    <label id="crncyIsoCode" class="btn btn-primary btn-file input-group-addon">
                                                                        <?php echo $crncyIsoCode; ?>
                                                                    </label>		
                                                                    <input onchange="calcMaturityValue();" <?php echo $mkReadOnly; ?> class="form-control rqrdFld" id="invstmntAmount" type = "number" min="0" placeholder="Amount" value="<?php echo $amount; ?>"/>
                                                                </div>
                                                            </div>
                                                        </div>   
                                                        <div class="form-group form-group-sm">
                                                            <label for="invstmntTenor" class="control-label col-md-4">Tenure:</label>
                                                            <div  class="col-md-8">
                                                                <div class="input-group col-md-12">
                                                                    <div  class="col-md-4" style="padding-left:0px !important;">
                                                                        <input onchange="calcMaturityValue();" <?php echo $mkReadOnly; ?> class="form-control rqrdFld" id="invstmntTenor" type = "number" min="0" placeholder="" value="<?php echo $tenor; ?>"/>
                                                                    </div>
                                                                    <div  class="col-md-8" style="padding-left:0px !important; padding-right: 0px !important; ">
                                                                        <select <?php echo $mkReadOnlyDsbld; ?> class="form-control" id="invstmntTenorType" >
                                                                            <?php
                                                                            $sltdDay = "";
                                                                            $sltdYear = "";
                                                                            if ($tenor_type == "Day") {
                                                                                $sltdDay = "selected";
                                                                            } else if ($tenor_type == "YEAR") {
                                                                                $sltdYear = "selected";
                                                                            }
                                                                            ?>
                                                                            <option value="Day" <?php echo $sltdDay; ?>>Day(s)</option>
                                                                            <option value="Year" <?php echo $sltdYear; ?>>Year(s)</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>                                                            
                                                        </div>
                                                        <div class="form-group form-group-sm">
                                                            <label for="interestRate" class="control-label col-md-4">Rate:</label>
                                                            <div  class="col-md-8">
                                                                <div  class="col-md-12" style="padding-right: 0px !important; padding-left: 0px !important;">
                                                                    <div  class="col-md-4" style="padding-left:0px !important; padding-right: 15px !important; ">
                                                                        <select <?php echo $mkReadOnlyDsbld; ?> class="form-control" id="rateType" onchange="onChangeRateType();">
                                                                            <?php
                                                                            $sltdYears = "";
                                                                            $sltdMonths = "";
                                                                            if ($rate_type == "Interest") {
                                                                                $sltdYears = "selected";
                                                                            } else if ($rate_type == "Discount") {
                                                                                $sltdMonths = "selected";
                                                                            }
                                                                            ?>
                                                                            <option value="Interest" <?php echo $sltdYears; ?>>Interest</option>
                                                                            <option value="Discount" <?php echo $sltdMonths; ?>>Discount</option>
                                                                        </select>
                                                                    </div>
                                                                    <div class="input-group col-md-8">
                                                                        <input onchange="calcMaturityValue();" <?php echo $mkReadOnly; ?> class="form-control rqrdFld" id="interestRate" type = "number" min="0" placeholder="Rate" value="<?php echo $interest_rate; ?>"/>
                                                                        <label class="btn btn-primary btn-file input-group-addon" style="padding-left: 2px !important;">
                                                                            % Per Annum
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-group-sm">
                                                            <label for="startDate" class="control-label col-md-4">Effective Date:</label>
                                                            <div  class="col-md-8">
                                                                <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input3" data-link-format="yyyy-mm-dd">
                                                                    <input class="form-control" size="16" type="text" id="startDate" value="<?php echo $start_date; ?>" readonly="">
                                                                    <!--<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>-->
                                                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-group-sm">
                                                            <label for="mtrtyDate" class="control-label col-md-4">Maturity:</label>
                                                            <div  class="col-md-8">
                                                                <div class="input-group">
                                                                    <input <?php echo $mkReadOnly; ?> style="color:<?php echo $ttlColor; ?>;font-weight: bold;" class="form-control" id="mtrtyDate" type = "text" min="0" placeholder="Maturity Date" value="<?php echo $end_date; ?>" readonly="readonly"/>
                                                                    <label id="crncyIsoCode1" class="btn btn-primary btn-file input-group-addon">
                                                                        <?php echo $crncyIsoCode; ?>
                                                                    </label>
                                                                    <input <?php echo $mkReadOnly; ?> style="color:<?php echo $ttlColor; ?>;font-weight: bold;" class="form-control" id="mtrtyValue" type = "text" min="0" placeholder="Maturity Value" value="<?php echo $maturity_value; ?>" readonly="readonly"/>
                                                                </div>
                                                            </div>
                                                        </div>                                                      
                                                        <?php
                                                        $dsplyRSADOptn = "";
                                                        if ($shd_rollover == "No") {
                                                            $dsplyRSADOptn = "style='display:none;'";
                                                        }
                                                        ?>
                                                        <div class="form-group form-group-sm">
                                                            <label for="shdRollover" class="control-label col-md-4">Rollover:</label>
                                                            <div  class="col-md-8">
                                                                <div class="input-group col-md-12">
                                                                    <div  class="col-md-4" style="padding-left:0px !important; padding-right: 15px !important; ">
                                                                        <select <?php echo $mkReadOnlyDsbld; ?> class="form-control" id="shdRollover" onchange="onChangeShdRollover();">
                                                                            <?php
                                                                            $sltdYes = "";
                                                                            $sltdNo = "";
                                                                            if ($shd_rollover == "Yes") {
                                                                                $sltdYes = "selected";
                                                                            } else if ($shd_rollover == "No") {
                                                                                $sltdNo = "selected";
                                                                            }
                                                                            ?>                                                                                        
                                                                            <option value="No" selected <?php echo $sltdNo; ?>>No</option>
                                                                            <option value="Yes" <?php echo $sltdYes; ?>>Yes</option>
                                                                        </select>
                                                                    </div>
                                                                    <div  class="col-md-6" style="padding-left:0px !important; padding-right: 0px !important; ">
                                                                        <select <?php echo $mkReadOnlyDsbldRlvr; ?> class="form-control" id="rolloverType" >
                                                                            <option value=""></option>
                                                                            <?php
                                                                            $sltdPO = "";
                                                                            $sltdPNI = "";
                                                                            if ($rollover_type == "Principal Only") {
                                                                                $sltdPO = "selected";
                                                                            } else if ($rollover_type == "Principal and Interest") {
                                                                                $sltdPNI = "selected";
                                                                            }
                                                                            ?>
                                                                            <option value="Principal Only" <?php echo $sltdPO; ?>>Principal Only</option>
                                                                            <option value="Principal and Interest" <?php echo $sltdPNI; ?>>Principal and Interest</option>
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-md-2" style="padding-right:0px !important;">
                                                                        <div style="padding: 0px 1px 0px 1px !important; float: right !important">
                                                                            <button type="button" class="btn btn-default btn-sm" style="height:30px;" onclick="processInvstmnt();"><img src="cmn_images/98.png" style="left: 0.5%; padding-right: 5px; padding-left: 5px; height:17px; width:auto; position: relative; vertical-align: middle;" data-toggle="tooltip" data-placement="bottom" title = "Calculate">&nbsp;</button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>                                                                      
                                                    </fieldset>
                                                </div>
                                                <div class="col-lg-6">                                                       
                                                    <div class="row"><!-- ROW 1 -->
                                                        <div class="col-lg-12">
                                                            <fieldset class="basic_person_fs5"><legend class="basic_person_lg1">Payment</legend>
                                                                <div  class="col-md-12">
                                                                    <div class="form-group form-group-sm">
                                                                        <label for="pymntMthod" class="control-label col-md-4">Payment Method:</label>
                                                                        <div  class="col-md-8">
                                                                            <select <?php echo $mkReadOnlyDsbld; ?> class="form-control" id="pymntMthod" onchange="onChangePymntMtd();">
                                                                                <?php
                                                                                $sltdDbtAcct = "";
                                                                                $sltdOther = "";
                                                                                if ($pymnt_method == "Debit Account") {
                                                                                    $sltdDbtAcct = "selected";
                                                                                } else if ($pymnt_method == "Cash/Cheque Payment") {
                                                                                    $sltdOther = "selected";
                                                                                }
                                                                                ?>                                                                                        
                                                                                <option value="Debit Account" selected <?php echo $sltdDbtAcct; ?>>Debit Account</option>
                                                                                <option value="Cash/Cheque Payment" <?php echo $sltdOther; ?>>Cash/Cheque Payment</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>    
                                                                    <div class="form-group form-group-sm">
                                                                        <label for="pymntMtdSrc" class="control-label col-md-4"><span id="src_id"><?php echo $pymntMtdScrLbl; ?></span></label>
                                                                        <div  class="col-md-8">
                                                                            <div class="input-group" style="width:100% !important;">
                                                                                <input type="text"  class="form-control" aria-label="..." id="pymntMtdSrc" value="<?php echo $pymntMtdSrc; ?>" readonly>
                                                                                <input type="hidden" id="pymntMtdSrcID" value="<?php echo $pymntMtdSrcID; ?>">
                                                                                <input type="hidden" id="gnrlOrgID" value="<?php echo $orgID; ?>">
                                                                                <label class="btn btn-primary btn-file input-group-addon" onclick="onClickPymntMtdSrc();">
                                                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                    </div>                                                                       
                                                                </div>
                                                            </fieldset>
                                                        </div>
                                                    </div> 
                                                    <div class="row"><!-- ROW 2 -->
                                                        <div class="col-lg-12">
                                                            <fieldset class="basic_person_fs5"><legend class="basic_person_lg1">Pay-Back</legend>
                                                                <div  class="col-md-12">
                                                                    <div class="form-group form-group-sm">
                                                                        <label for="benfName" class="control-label col-md-4">Beneficiary:</label>
                                                                        <div  class="col-md-8">
                                                                            <input <?php echo $mkReadOnly; ?> type="text"  class="form-control" aria-label="..." id="benfName" value="<?php echo $ifo_name; ?>">
                                                                        </div>
                                                                    </div>    
                                                                    <div class="form-group form-group-sm">
                                                                        <label for="benfCntct" class="control-label col-md-4">Contact Nos:</label>
                                                                        <div  class="col-md-8">
                                                                            <input <?php echo $mkReadOnly; ?> type="text"  class="form-control" aria-label="..." id="benfCntct" value="<?php echo $ifo_contact; ?>">
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group form-group-sm">
                                                                        <label for="payBckMthod" class="control-label col-md-4">Pay-Back Method:</label>
                                                                        <div  class="col-md-8">
                                                                            <select <?php echo $mkReadOnlyDsbld; ?> class="form-control" id="payBckMthod" onchange="onChangeBckPayMtd();">
                                                                                <?php
                                                                                $sltdCrdtAcct = "";
                                                                                $sltdOther = "";
                                                                                if ($pay_back_method == "Credit Account") {
                                                                                    $sltdCrdtAcct = "selected";
                                                                                } else if ($pay_back_method == "Cheque Payment") {
                                                                                    $sltdOther = "selected";
                                                                                }
                                                                                ?>                                                                                        
                                                                                <option value="Credit Account" selected <?php echo $sltdCrdtAcct; ?>>Credit Account</option>
                                                                                <option value="Cheque Payment" <?php echo $sltdOther; ?>>Cheque Payment</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>    
                                                                    <div id="crdtAcctNoDiv" <?php echo $crdtAcctNoDsply; ?> class="form-group form-group-sm">
                                                                        <label for="paybackAcct" class="control-label col-md-4">Account No.</label>
                                                                        <div  class="col-md-8">
                                                                            <div class="input-group" style="width:100% !important;">
                                                                                <input type="text"  class="form-control" aria-label="..." id="paybackAcct" value="<?php echo $paybackAcct; ?>" readonly>
                                                                                <input type="hidden" id="paybackAcctID" value="<?php echo $payback_crdt_acct_id; ?>">
                                                                                <input type="hidden" id="gnrlOrgID" value="<?php echo $orgID; ?>">
                                                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Bank Accounts (For Loan Repayment)', 'gnrlOrgID', 'bnkCustomerID', '', 'radio', true, '', 'paybackAcctID', 'paybackAcct', 'clear', 1, '');">
                                                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                    </div> 
                                                                    <div id="crdtAcctChqNoDiv" <?php echo $crdtAcctChqNoDsply; ?> class="form-group form-group-sm" >
                                                                        <label for="chqNo" class="control-label col-md-4">Cheque No.:</label>
                                                                        <div  class="col-md-8">
                                                                            <input type="text"  class="form-control" aria-label="..." id="chqNo" value="<?php echo $payback_chq_no; ?>" readonly>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </fieldset>
                                                        </div>
                                                    </div>   
                                                    <div class="row"><!-- ROW 3 -->
                                                        <div class="col-lg-12">
                                                            <legend class="basic_person_lg1">Rediscount</legend>
                                                                <div  class="col-md-12">  
                                                                    <div class="form-group form-group-sm">
                                                                        <label for="rdscntDate" class="control-label col-md-4">Rediscount Date:</label>
                                                                        <div  class="col-md-8">
                                                                            <input class="form-control" size="16" type="text" id="rdscntDate" value="<?php echo $rdscnt_rqst_date; ?>" readonly="">
                                                                        </div>
                                                                    </div>                                                                       
                                                                </div>
                                                        </div>
                                                    </div>                                                       
                                                </div>
                                            </div>  
                                            <!--</fieldset>-->
                                        </div>
                                    </div>                                              
                                </form>                             
                            </div>                
                        </div>          
                    </div>
                    <?php
            } 
        }
        else if ($vwtyp == "4") {
            $pkID = isset($_POST['PKeyID']) ? cleanInputData($_POST['PKeyID']) : -1; 
            
            $invstmntPrdtDetArry = array();
            $result = get_InvstmntPrdtDetsForApplctn($pkID);

            while ($row = loc_db_fetch_array($result)) {
                $crncyIsoCode = getGnrlRecNm("mcf.mcf_currencies", "crncy_id", "iso_code", $row[1]);

                $invstmntPrdtDetArry = array("svngsProductId" => $row[0], "crncyIsoCode" => $crncyIsoCode, "currencyId" => $row[1],
                    "interestRate" => $row[2], "productType" => $row[3], "invstmntDurationNo" => $row[4], 
                    "invstmntDurationType" => $row[5], "invstmntType" => $row[6], "discountRate" => $row[7], 
                    "invstmntMaxAmount" => $row[8], "invstmntMinAmount" => $row[9]);
            }

            $response = array("invstmntPrdtDetArry" => $invstmntPrdtDetArry);

            echo json_encode($response);
            exit();
        } 
    } 

    ?>     

    <script type="text/javascript">
        $(document).ready(function () {
            
        });
    </script>                

    <?php
}
?>
