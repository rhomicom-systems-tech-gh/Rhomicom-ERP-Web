<?php
if (array_key_exists('lgn_num', get_defined_vars())) {
    $prsnid = $_SESSION['PRSN_ID'];
    $prsnID = $_SESSION['PRSN_ID'];
    $orgID = $_SESSION['ORG_ID'];
    $lnkdFirmID = getGnrlRecNm("prs.prsn_names_nos", "person_id", "lnkd_firm_org_id", $prsnid);
    $pkID = $PKeyID;

    $prsnBranchID = get_Person_BranchID($prsnid);
    $prsnBranch = getGnrlRecNm("org.org_sites_locations", "location_id", "site_desc||' ('||location_code_name||')'", "pasn.get_prsn_siteid($prsnid)");

    //var_dump($_POST);

    if ($subPgNo == 4.1) {//LOAN APPLICATIONS
        $trnsType = "LOAN APPLICATION";
        $formTitle = "Loan Application Attached Document";
        if ($vwtyp == "0") {
            /* BASIC DATA */
            if ($vwtypActn == "EDIT" || $vwtypActn == "VIEW") {
                
                $trnsStatus = "Incomplete";
                $sbmtdTrnsHdrID = $pkID;
                $voidedTrnsHdrID = -1;
                $rqstatusColor = "red";
                $ttlColor = "blue";
                $apprvdAmntColor = "blue";
                $isPaidColor = "blue";
                $mkReadOnly = "";
                $mkReadOnlyDsbld = "";
                $mkRmrkReadOnly = "";                
                $trnsTtl = 0.00;
                $crncy = "GHS";
                $crncyID = ""; 
                $isDisbursed = "NO";
                $payDate = "";
                $routingID = getMCFMxRoutingID($sbmtdTrnsHdrID, "Loan Applications");
                $cashCollateralValue = 0.00;
                $prptyCollateralValue = 0.00;
                $invstmntCollateralValue = 0.00;
                $sector_clsfctn_major_id= -1; 
                $sector_clsfctn_major= "";           
                $sector_clsfctn_minor_id= -1; 
                $sector_clsfctn_minor= "";  
                
                //var_dump("routingID " .$routingID." sbmtdTrnsHdrID=".$sbmtdTrnsHdrID);
                $result = get_LoanRqstDet($pkID);
                while ($row = loc_db_fetch_array($result)) {
                    
                    if((int)$row[20] > 0){
                        $cashCollateralValue = getAcctCollateralCurrAvlblBal($row[20]);
                    }
                    
                    if((int)$row[21] > 0){
                        $invstmntCollateralValue = getInvstmntOrPrptyNetVal($row[21], "Investment");//getAcctCollateralCurrAvlblBal($row[21]);
                    }

                    if((int)$row[22] > 0){
                        $prptyCollateralValue = getInvstmntOrPrptyNetVal($row[22], "Property");//getAcctCollateralCurrAvlblBal($row[22]);
                    }                    
                    
                    $trnsStatus = $row[8];
                    
                    //$voidedTrnsHdrID = (int) $row[6];
                    $trnsTtl = (float) $row[27];  
                    $isDisbursed = $row[28];
                    
                    $result3 = get_LoanPrdtDetsForLoanApplctn($row[14]);
                    $row3 = loc_db_fetch_array($result3);
                    
                    $sector_clsfctn_major_id= $row[34]; 
                    $sector_clsfctn_major= getGnrlRecNm("mcf.mcf_loan_sectors_major", "major_sector_id", "sector_name||' ['||sector_desc||']'",$sector_clsfctn_major_id);  
                    $sector_clsfctn_minor_id= $row[35]; 
                    $sector_clsfctn_minor= getGnrlRecNm("mcf.mcf_loan_sectors_minor", "minor_sector_id", "sector_name||' ['||sector_desc||']'", $sector_clsfctn_minor_id);                     

                    $bnkBranch = getGnrlRecNm("org.org_sites_locations", "location_id", "site_desc||' ('||location_code_name||')'", $row[3]);
                    $rcvryOfficer = getGnrlRecNm("prs.prsn_names_nos", "person_id", "trim(title || ' ' || sur_name || ', ' || first_name || ' ' || other_names)", $row[7]);
                    $rpymntAccNo = getGnrlRecNm("mcf.mcf_accounts", "account_id", "account_number", $row[19]);
                    $cashColtAccNo = getGnrlRecNm("mcf.mcf_accounts", "account_id", "account_number", $row[20]);
                    
                    $prptyColtNm = getGnrlRecNm("mcf.mcf_property_collaterals", "prpty_collateral_id", "collateral_name", $row[22]);
                    $invstmntColtNm = "";//getGnrlRecNm("mcf.mcf_property_collaterals", "prpty_collateral_id", "collateral_name", $row[21]);
                    /* Add */
                    $topUpRqstNo = getGnrlRecNm("mcf.mcf_loan_request", "loan_rqst_id", "trnsctn_id", $row[24]);
                    $crncyIsoCode = getGnrlRecNm("mcf.mcf_currencies", "crncy_id", "iso_code", $row3[1]);

		    $loanDisbmntDetID = getLoanRqstDisbmntDetID($pkID);                    

                    $tabWkflApprvl = 'style="display:none !important;"';

                    $actn = "ADD";
                    $pMode = 0; //NEW
                    if ($vwtypActn == "VIEW" ||($trnsStatus == "Initiated" || $trnsStatus == "Approved" || $trnsStatus == "Rescheduled" || $trnsStatus == "Topped-Up")) {
                        $actn = "SMRYPAGE";
                        $pMode = 1; //NEW
                        $mkReadOnly = "readonly=\"true\"";
                        $mkReadOnlyDsbld = "disabled=\"true\"";
                    }
                    
                    if($trnsStatus == "Approved" || $trnsStatus == "Rescheduled" || $trnsStatus == "Topped-Up"){
                        $tabWkflApprvl = 'style="display:block !important;"';
                    }
                    ?>
                    <div class="row" style="margin: 0px 0px 10px 0px !important;" >
                        <input type="hidden" id="onlyViewMode" value="<?php echo $vwtypActn; ?>"/>
                        <input type="hidden" id="lnRqstID" value="<?php echo $row[0]; ?>"/>
                        <input type="hidden" id="lnprflFctrId" value="-1"/>
                        <input type="hidden" id="assmntCmnts" value="<?php echo $row[24]; ?>"/>
                        <input class="form-control" id="addOrEditForm" type = "hidden" placeholder="addOrEditForm" value="Edit"/>                    
                    </div>                    
                    <div class="row" style="margin: 0px 0px 10px 0px !important;">
                        <div class="col-md-12" style="padding:0px 0px 0px 0px !important;">
                            <button type="button" class="btn btn-default btn-sm phone-only-btn" onclick="openATab('#allmodules', 'grp=17&typ=1&pg=2&vtyp=0');">Main Parameters</button>
                            <button type="button" class="btn btn-default btn-sm phone-only-btn" onclick="openATab('#prflCMDataEDT', 'grp=17&typ=1&pg=2&vtyp=1');">Standard Events Accounting</button>
                            <button type="button" class="btn btn-default btn-sm phone-only-btn" onclick="openATab('#prflCMAttchmntEDT', 'grp=17&typ=1&pg=2&vtyp=2');">Custom Events Accounting</button>
                        </div>
                    </div>
                    <div class="">
                        <ul class="nav nav-tabs rho-hideable-tabs" style="margin-top:-10px !important;">
                            <li class="active"><a data-toggle="tab" data-rhodata="&pg=2&vtyp=0" href="#prflCMHomeEDT" id="prflCMHomeEDTtab">Application Details</a></li>
                            <li id="standardEvtns" style="display: block;"><a data-toggle="tabajxprfledt" data-rhodata="&pg=2&vtyp=1" href="#prflCMDataEDT" onclick="newLoanRequestOpenATab('#prflCMDataEDT', 'grp=17&typ=1&pg=<?php echo $pgNo; ?>&subPgNo=<?php echo $subPgNo; ?>&vtyp=1&vtypActn=<?php echo $actn; ?>&pMode=<?php echo $pMode; ?>&onlyViewMode=<?php echo $vwtypActn; ?>');" id="prflCMDataEDTtab">Credit Risk Assessment</a></li>
                            <li id="customEvnts" <?php echo $tabWkflApprvl; ?>><a data-toggle="tabajxprfledt" data-rhodata="&pg=2&vtyp=2" href="#prflCMAttchmntEDT" onclick="newLoanRequestOpenATab('#prflCMAttchmntEDT', 'grp=17&typ=1&pg=<?php echo $pgNo; ?>&subPgNo=<?php echo $subPgNo; ?>&vtyp=2&vtypActn=EDIT&onlyViewMode=<?php echo $vwtypActn; ?>');" id="prflCMAttchmntEDTtab">Workflow Approval</a></li>
                        </ul>
                        <div class="row">                  
                            <div class="col-md-12">
                                <div class="custDiv"> 
                                    <div class="tab-content">
                                        <div id="prflCMHomeEDT" class="tab-pane fadein active" style="border:none !important;"> 
                                            <div class="col-md-12" style="padding:0px 0px 10px 1px !important;">
                                                <div style="padding:0px 1px 0px 15px !important;float:left;">
                                                        <button type="button" class="btn btn-default btn-sm" style="height:30px;" id="myVmsTrnsStatusBtn">
                                                                <span style="font-weight:bold;">Status: </span><span style="color:<?php echo $rqstatusColor; ?>;font-weight: bold;"><?php echo $trnsStatus; ?></span>
                                                        </button>
                                                        <?php if($vwtypActn != "VIEW") { ?>
                                                        <button type="button" class="btn btn-default btn-sm" style="height:30px;" onclick="getLoanRqstForm('myFormsModalLg', 'myFormsModalBodyLg', 'myFormsModalTitleLg', 'Edit Loan Request', 15, <?php echo $subPgNo; ?>,0,'EDIT', <?php echo $sbmtdTrnsHdrID; ?>,'indCustTable','indCustTableRow1');" data-toggle="tooltip" title="Reload Transaction">
                                                                <img src="cmn_images/refresh.bmp" style="padding-right: 2px; height:17px; width:auto; position: relative; vertical-align: middle;">
                                                        </button>
                                                        <?php } ?>
                                                        <button type="button" class="btn btn-default btn-sm" style="height:30px;" id="myVmsTrnsTtlBtn">
                                                                <span style="font-weight:bold;">Approved Amount: </span><span style="color:<?php echo $ttlColor; ?>;font-weight: bold;"><?php echo $crncyIsoCode." ".number_format($trnsTtl,2); ?></span>
                                                        </button>
                                                        <button type="button" class="btn btn-default btn-sm" style="height:30px;" id="myVmsTrnsIsPaidBtn">
                                                                <span style="font-weight:bold;">Disbursed: </span><span style="color:<?php echo $ttlColor; ?>;font-weight: bold;"><?php echo $isDisbursed; ?></span>
                                                        </button>
                                                        <button type="button" class="btn btn-default" style="height:30px;" onclick="getOneMcfDocsForm_Gnrl(<?php echo $pkID; ?>,'<?php echo $trnsType; ?>', 140, '<?php echo $formTitle; ?>');" data-toggle="tooltip" data-placement="bottom" title = "Attached Documents">
                                                            <img src="cmn_images/adjunto.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                        </button>                                                    
                                                </div>
                                                <div style="padding:0px 1px 0px 1px !important;float:right;">
                                                        <?php if (($trnsStatus == "Incomplete" || $trnsStatus == "Withdrawn" || $trnsStatus == "Rejected") && $vwtypActn != "VIEW") { ?>                                                    
                                                                <button type="button" class="btn btn-default btn-sm" style="height:30px;" onclick="saveLoanApplication(<?php echo $pgNo; ?>, <?php echo $subPgNo; ?>, <?php echo $vwtyp; ?>);"><img src="cmn_images/FloppyDisk.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Save&nbsp;</button>
                                                                <button type="button" class="btn btn-default btn-sm" style="height:30px;" onclick="submitWithdrawLoanRqstActn(<?php echo $pgNo; ?>, <?php echo $subPgNo; ?>, 1, 'SUBMIT');"><img src="cmn_images/Emailcon.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Submit&nbsp;</button>   
                                                        <?php 
                                                        } else if ($trnsStatus == "Initiated" && $vwtypActn != "VIEW") {
                                                                ?>                                    
                                                                <button type="button" class="btn btn-default btn-sm" style="height:30px;" onclick="submitWithdrawLoanRqstActn(<?php echo $pgNo; ?>, <?php echo $subPgNo; ?>, 1, 'WITHDRAW');"><img src="cmn_images/withdraw_rqst.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Withdraw&nbsp;</button>                                      
                                                                <button type="button" class="btn btn-default btn-sm" style="height:30px;" onclick="authrzeMCFTrnsRqstCrdtMgmnt('LOAN_PYMNT', 20, '<?php echo $subPgNo; ?>');"><img src="cmn_images/Stamp-512.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Authorize&nbsp;</button>                                                                                                        
                                                                <button type="button" class="btn btn-default btn-sm" style="height:30px;" onclick="checkWkfRqstStatus(<?php echo $routingID; ?>, 'Teller Trns. Authorization Progress History');"><img src="cmn_images/workflow.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Progress&nbsp;</button>                                
                                                                        <?php
                                                                } else if (($trnsStatus == "Approved" || $trnsStatus == "Rescheduled" || $trnsStatus == "Topped-Up") && $isDisbursed == "NO" && $vwtypActn != "VIEW") {
                                                                        ?>
                                                                <button type="button" class="btn btn-default btn-sm" style="height:30px;" onclick="checkWkfRqstStatus(<?php echo $routingID; ?>, 'Teller Trns. Authorization Progress History');"><img src="cmn_images/workflow.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;" data-toggle="tooltip" title="Authorization Progress History">Progress&nbsp;</button>  
                                                                <button type="button" class="btn btn-default btn-sm" style="height:30px;" onclick="getLoanRqstRvrslForm('myFormsModaly', 'myFormsModalyBody', 'myFormsModalyTitle', 'rvrsApprvdLoanForm', '', 'Reverse Loan Approval', 15, 4.1, 9, '', <?php echo $pkID; ?>);"><img src="cmn_images/90.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Reverse Approval&nbsp;</button>                               
                                                        <?php } ?>
                                                        <?php
                                                        if ($trnsStatus == "Approved" || $trnsStatus == "Rescheduled" || $trnsStatus == "Topped-Up") {
                                                                $reportTitle = "My Loan Schedule";
                                                                $reportName = "Loan Schedule";
                                                                $rptID = getRptID($reportName);
                                                                $prmID1 = getParamIDUseSQLRep("{:dsbmntDetID}", $rptID);
                                                                $prmID2 = getParamIDUseSQLRep("{:documentTitle}", $rptID);
                                                                $invcID = $loanDisbmntDetID;
                                                                $paramRepsNVals = $prmID1 . "~" . $invcID . "|" . $prmID2 . "~" . $reportTitle . "|-190~PDF";
                                                                $paramStr = urlencode($paramRepsNVals);
                                                                ?>
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
                                                    <input type="hidden" id="currencyId" value="<?php echo $row3[1]; ?>">
                                                    <input type="hidden" id="isAccountRqrd" value="<?php echo $row3[2]; ?>">
                                                    <input type="hidden" id="loanMaxAmount" value="<?php echo $row3[3]; ?>">
                                                    <input type="hidden" id="loanMinAmount" value="<?php echo $row3[4]; ?>">
                                                    <input type="hidden" id="maxLoanTenor" value="<?php echo $row3[5]; ?>">
                                                    <input type="hidden" id="maxLoanTenorType" value="<?php echo $row3[6]; ?>">
                                                    <input type="hidden" id="guarantorRequired" value="<?php echo $row3[7]; ?>">
                                                    <input type="hidden" id="guarantorNo" value="<?php echo $row3[8]; ?>">
                                                    <input type="hidden" id="productType" value="<?php echo $row3[9]; ?>">
                                                    <input type="hidden" id="custTypeCustgrp" value="<?php echo $row3[10]; ?>">
                                                    <input type="hidden" id="custTypeCorp" value="<?php echo $row3[11]; ?>">
                                                    <input type="hidden" id="custTypeInd" value="<?php echo $row3[12]; ?>">
                                                    <input type="hidden" id="cashCollateralRqrd" value="<?php echo $row3[13]; ?>">
                                                    <input type="hidden" id="valueFlatCashColt" value="<?php echo $row3[14]; ?>">
                                                    <input type="hidden" id="valuePrcntCashColt" value="<?php echo $row3[15]; ?>">                                            
                                                    <input type="hidden" id="prptyCollateralRqrd" value="<?php echo $row3[16]; ?>">
                                                    <input type="hidden" id="valueFlatPrptyColt" value="<?php echo $row3[17]; ?>">
                                                    <input type="hidden" id="valuePrcntPrptyColt" value="<?php echo $row3[18]; ?>">
                                                    <input type="hidden" id="invstmntCollateralRqrd" value="<?php echo $row3[19]; ?>">
                                                    <input type="hidden" id="valueInvstmntCashColt" value="<?php echo $row3[20]; ?>">
                                                    <input type="hidden" id="valueInvstmntPrcntColt" value="<?php echo $row3[21]; ?>">  
                                                    <input type="hidden" id="minLoanTenor" value="<?php echo $row3[22]; ?>">
                                                    <input type="hidden" id="minLoanTenorType" value="<?php echo $row3[23]; ?>">
                                                    <input type="hidden" id="baseIntRate" value="<?php echo $row3[24]; ?>">
                                                </div>                                                
                                                <div class="row"><!-- ROW 1 -->
                                                    <div class="col-lg-12">
                                                        <!--<fieldset class="basic_person_fs5">-->
                                                        <legend class="basic_person_lg1" style="margin-top:5px !important;margin-bottom: 10px !important;">Application Header</legend>
                                                        <div class="col-lg-4">
                                                            <input class="form-control" id="loanRqstID" type = "hidden" placeholder="Loan Request ID" value="<?php echo $row[0]; ?>"/>                                                        
                                                            <div class="form-group form-group-sm">
                                                                <label for="rqsNumber" class="control-label col-md-4">Request No:</label>
                                                                <div class="col-md-8">
                                                                    <input class="form-control" id="rqsNumber" type = "text" placeholder="" value="<?php echo $row[1]; ?>" readonly/>                                                                                                                                            
                                                                </div>
                                                            </div>
                                                            <div class="form-group form-group-sm">
                                                                <label for="applctnDate" class="control-label col-md-4">Appl. Date:</label>
                                                                <div  class="col-md-8">
                                                                    <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input3" data-link-format="yyyy-mm-dd">
                                                                        <input class="form-control" size="16" type="text" id="applctnDate" value="<?php echo $row[2]; ?>" readonly="">
                                                                        <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                                                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                                        <span class="input-group-addon" onclick="javascript:unfreezeDialog();"><span class="glyphicon glyphicon-info-sign"></span></span>
                                                                    </div>
                                                                </div>
                                                            </div>                                                        
                                                            <div class="form-group form-group-sm">
                                                                <label for="bnkBranch" class="control-label col-md-4">Branch:</label>
                                                                <div  class="col-md-8">
                                                                    <div class="input-group">
                                                                        <input type="text" class="form-control" aria-label="..." id="bnkBranch" value="<?php echo $bnkBranch; ?>" readonly="">
                                                                        <input type="hidden" id="bnkBranchID" value="<?php echo $row[3]; ?>">  
                                                                        <input type="hidden" id="gnrlOrgID" value="<?php echo $orgID; ?>">
                                                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Sites/Locations New', 'gnrlOrgID', '', '', 'radio', true, '', 'bnkBranchID', 'bnkBranch', 'clear', 1, '');">
                                                                            <span class="glyphicon glyphicon-th-list"></span>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>                                                          
                                                        <div class="col-lg-3">
                                                            <div class="form-group form-group-sm">
                                                                <label for="prpsOfLoan" class="control-label col-md-3">Purpose:</label>
                                                                <div  class="col-md-9">
                                                                    <textarea <?php echo $mkReadOnly; ?> class="form-control rqrdFld" id="prpsOfLoan" cols="2" placeholder="Purpose of Loan" rows="5"><?php echo $row[4]; ?></textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-5">
                                                            <div class="form-group form-group-sm">
                                                                <label for="acctNumber" class="control-label col-md-5">Loan Account Number:</label>
                                                                <div class="col-md-7">
                                                                    <input class="form-control" id="acctNumber" type = "text" placeholder="" value="<?php echo $row[6]; ?>" readonly/>
                                                                    <!--LOAN APPLICATION ID-->
                                                                    <input class="form-control" id="acctID" type = "hidden" placeholder="Account ID" value="<?php echo $row[5]; ?>"/>                                                                                                                                            
                                                                </div>
                                                            </div> 
                                                            <div class="form-group form-group-sm">
                                                                <label for="rcvryOfficer" class="control-label col-md-5">Credit Officer:</label>
                                                                <div  class="col-md-7">
                                                                    <div class="input-group">
                                                                        <input type="text" class="form-control" aria-label="..." id="rcvryOfficer" value="<?php echo $rcvryOfficer; ?>" readonly>
                                                                        <input type="hidden" id="rcvryOfficerID" value="<?php echo $row[7]; ?>">
                                                                        <input type="hidden" id="gnrlOrgID" value="<?php echo $orgID; ?>">
                                                                        <label class="btn btn-primary btn-file input-group-addon" onclick="clearROLovField('rcvryOfficer', 'rcvryOfficerID');">
                                                                            <span class="glyphicon glyphicon-remove"></span>
                                                                        </label>
                                                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'MCF Credit Officers', 'gnrlOrgID', '', '', 'radio', true, '', 'rcvryOfficerID', 'rcvryOfficer', 'clear', 1, '');">
                                                                            <span class="glyphicon glyphicon-th-list"></span>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div>                                                             
                                                            <div class="form-group form-group-sm">
                                                                <label for="status" class="control-label col-md-5">Status:</label>
                                                                <div class="col-md-7">
                                                                    <input class="form-control" id="status" type = "text" style="color:<?php echo $rqstatusColor; ?>;font-weight: bold;" placeholder="" value="<?php echo $row[8]; ?>" readonly/>                                                                                                                                        
                                                                </div>
                                                            </div>                                                            

                                                        </div>
                                                        <!--</fieldset>-->
                                                    </div>
                                                </div>
                                                <div class="row"><!-- ROW 2 -->
                                                    <div class="col-lg-12">
                                                        <!--<fieldset class="basic_person_fs1" style="margin-top:5px !important;">-->
                                                        <legend class="basic_person_lg1" style="margin-top:10px !important;margin-bottom: 10px !important;">Application Details</legend>                                                     
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
                                                                                if ($row[9] == "Corporate") {
                                                                                    $sltdCorp = "selected";
                                                                                } else if ($row[9] == "Individual") {
                                                                                    $sltdIndv = "selected";
                                                                                } else if ($row[9] == "Group") {
                                                                                    $sltdGrp = "selected";
                                                                                }
                                                                                ?>
                                                                                <option value="Corporate" <?php echo $sltdCorp; ?>>Corporate</option>
                                                                                <option value="Individual" <?php echo $sltdIndv; ?>>Individual</option>
                                                                                <option value="Group" <?php echo $sltdGrp; ?>>Customer Group</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <?php
                                                                    $dsplyOptnCustGrp = "";
                                                                    if ($row[9] != "Group") {
                                                                        $dsplyOptnCustGrp = "style='display:none;'";
                                                                    }
                                                                    ?>
                                                                    <div class="form-group form-group-sm" <?php echo $dsplyOptnCustGrp; ?> id="custGrpDiv">
                                                                        <label for="custGrp" class="control-label col-md-4">Group Name:</label>
                                                                        <div  class="col-md-8">
                                                                            <div class="input-group">
                                                                                <input type="text" class="form-control" aria-label="..." id="custGrp" value="<?php echo $row[11]; ?>" readonly="readonly">
                                                                                <input type="hidden" id="custGrpID" value="<?php echo $row[10]; ?>">
                                                                                <input type="hidden" id="gnrlOrgID" value="<?php echo $orgID; ?>">
                                                                                <label class="btn btn-primary btn-file input-group-addon" onclick="onClickGroupNmOrCust(0)">
                                                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group form-group-sm">
                                                                        <label for="bnkCustomer" class="control-label col-md-4">Customer Name:</label>
                                                                        <div  class="col-md-8">
                                                                            <div class="input-group">
                                                                                <input type="text" class="form-control" aria-label="..." id="bnkCustomer" value="<?php echo $row[13]; ?>" readonly="readonly">
                                                                                <input type="hidden" id="bnkCustomerID" value="<?php echo $row[12]; ?>">
                                                                                <input type="hidden" id="gnrlOrgID" value="<?php echo $orgID; ?>">
                                                                                <label class="btn btn-primary btn-file input-group-addon" onclick="onClickGroupNmOrCust(1);">
                                                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group form-group-sm">
                                                                        <label for="crdtType" class="control-label col-md-4">Credit Type:</label>
                                                                        <div  class="col-md-8">
                                                                            <select <?php echo $mkReadOnlyDsbld; ?> class="form-control" id="crdtType" onchange="onChangeCrdtType();">
                                                                                <?php
                                                                                $sltdLoan = "";
                                                                                $sltdOD = "";
                                                                                if ($row[9] == "Loan") {
                                                                                    $sltdLoan = "selected";
                                                                                } else if ($row[9] == "Overdraft") {
                                                                                    $sltdOD = "selected";
                                                                                }
                                                                                ?>
                                                                                <option value="Loan" <?php echo $sltdLoan; ?>>Loan</option>
                                                                                <option value="Overdraft" <?php echo $sltdOD; ?>>Overdraft</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>                                                                    
                                                                    <div class="form-group form-group-sm">
                                                                        <label for="prdtType" class="control-label col-md-4">Product:</label>
                                                                        <div  class="col-md-8">
                                                                            <div class="input-group">
                                                                                <input type="text" class="form-control" aria-label="..." id="prdtType" value="<?php echo $row[15]; ?>" readonly="readonly">
                                                                                <input type="hidden" id="prdtTypeID" value="<?php echo $row[14]; ?>">
                                                                                <label class="btn btn-primary btn-file input-group-addon" onclick="onClickProduct(<?php echo $pgNo; ?>,<?php echo $subPgNo; ?>);">
                                                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                                                </label>
                                                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getProductsForm('myFormsModalLgZ', 'myFormsModalLgZBody', 'myFormsModalLgZTitle', 'Edit Credit Product', 12, 7.2,0,'VIEW', <?php echo $row[14]; ?>,'','indCustTableRow1');">
                                                                                    <span class="glyphicon glyphicon-info-sign"></span>
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                    </div> 
                                                                    <div class="form-group form-group-sm">
                                                                        <label for="rqstType" class="control-label col-md-4">Request Type:</label>
                                                                        <div  class="col-md-8">
                                                                            <select <?php echo $mkReadOnlyDsbld; ?> class="form-control" id="rqstType" onchange="onChangeOfRqstType();">
                                                                                <?php
                                                                                $sltdNwLn = "";
                                                                                $sltdTpUp = "";
                                                                                $sltdRshdl = "";
                                                                                if ($row[23] == "New Request") {
                                                                                    $sltdNwLn = "selected";
                                                                                } else if ($row[23] == "Top-Up Request") {
                                                                                    $sltdTpUp = "selected";
                                                                                } else if ($row[23] == "Reschedule Request") {
                                                                                    $sltdRshdl = "selected";
                                                                                }
                                                                                ?>
                                                                                <option value="New Request" <?php echo $sltdNwLn; ?>>New Request</option>
                                                                                <option value="Top-Up Request" <?php echo $sltdTpUp; ?>>Top-Up Request</option>
                                                                                <option value="Reschedule Request" <?php echo $sltdRshdl; ?>>Reschedule Request</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <?php
                                                                    $dsplyOptn = "";
                                                                    if ($row[23] == "New Request") {
                                                                        $dsplyOptn = "style='display:none;'";
                                                                    }
                                                                    ?>
                                                                    <div class="form-group form-group-sm" <?php echo $dsplyOptn; ?> id="rqstIdForTopupDiv">
                                                                        <label for="refLoanrqst" class="control-label col-md-4">Request ID - Ref.:</label>
                                                                        <div  class="col-md-8">
                                                                            <div class="input-group">
                                                                                <input type="text" class="form-control" aria-label="..." id="refLoanrqst" value="<?php echo $topUpRqstNo; ?>" readonly="readonly">
                                                                                <input type="hidden" id="refLoanrqstID" value="<?php echo $row[24]; ?>">
                                                                                <input type="hidden" id="gnrlOrgID" value="<?php echo $orgID; ?>">
                                                                                <label class="btn btn-primary btn-file input-group-addon" onclick="onClickRefLoanRqst(<?php echo $pgNo; ?>,<?php echo $subPgNo; ?>);">
                                                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                                                </label>
                                                                                <label class="btn btn-primary btn-file input-group-addon" title="View Details" onclick="viewRefLoanRqst()">
                                                                                    <img src="cmn_images/kghostview.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group form-group-sm">
                                                                        <label for="loanAmount" class="control-label col-md-4">Amount:</label>
                                                                        <div  class="col-md-8">
                                                                            <div class="input-group">
                                                                                <label id="crncyIsoCode" class="btn btn-primary btn-file input-group-addon">
                                                                                    <?php echo $crncyIsoCode; ?>
                                                                                </label>		
                                                                                <input <?php echo $mkReadOnly; ?> class="form-control rqrdFld" id="loanAmount" type = "number" min="0" placeholder="Amount" value="<?php echo $row[16]; ?>"/>
                                                                            </div>
                                                                        </div>
                                                                    </div>   
                                                                    <div class="form-group form-group-sm">
                                                                        <label for="loanTenor" class="control-label col-md-4">Tenure:</label>
                                                                        <div  class="col-md-8">
                                                                            <div class="input-group col-md-12">
                                                                                <div  class="col-md-4" style="padding-left:0px !important;">
                                                                                    <input <?php echo $mkReadOnly; ?> class="form-control rqrdFld" id="loanTenor" type = "number" min="0" placeholder="" value="<?php echo $row[17]; ?>"/>
                                                                                </div>
                                                                                <div  class="col-md-8" style="padding-left:0px !important; padding-right: 0px !important; ">
                                                                                    <select <?php echo $mkReadOnlyDsbld; ?> class="form-control" id="loanTenorType" >
                                                                                        <?php
                                                                                        $sltdYears = "";
                                                                                        $sltdMonths = "";
                                                                                        if ($row[18] == "Year(s)") {
                                                                                            $sltdYears = "selected";
                                                                                        } else if ($row[18] == "Month(s)") {
                                                                                            $sltdMonths = "selected";
                                                                                        }
                                                                                        ?>
                                                                                        <option value="Year(s)" <?php echo $sltdYears; ?>>Year(s)</option>
                                                                                        <option value="Month(s)" <?php echo $sltdMonths; ?>>Month(s)</option>
                                                                                    </select>
                                                                                </div>
                                                                            </div>
                                                                        </div>                                                            
                                                                    </div>
                                                                    <div class="form-group form-group-sm">
                                                                        <label for="interestRate" class="control-label col-md-4">Interest Rate:</label>
                                                                        <div  class="col-md-8">
                                                                            <div class="input-group">
                                                                                <input <?php echo $mkReadOnly; ?> class="form-control rqrdFld" id="interestRate" type = "number" min="0" placeholder="Interest Rate" value="<?php echo $row[25]; ?>"/>
                                                                                <label class="btn btn-primary btn-file input-group-addon" style="padding-left: 2px !important;">
                                                                                    % Per Annum
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                    </div>                                                                    
                                                                    <?php
                                                                    $dsplyRSADOptn = "";
                                                                    if ($row3[2] == "No" && $row[26] == "Manual Payments") {
                                                                        $dsplyRSADOptn = "style='display:none;'";
                                                                    }
                                                                    ?>
                                                                    <div class="form-group form-group-sm">
                                                                        <label for="rpmntSrcAcct" class="control-label col-md-4">Repayment:</label>
                                                                        <div  class="col-md-8">
                                                                            <div class="input-group col-md-12">
                                                                                <div  class="col-md-6" style="padding-left:0px !important; padding-right: 0px !important; ">
                                                                                    <select <?php echo $mkReadOnlyDsbld; ?> class="form-control" id="rpmntSrcType" onchange="onChangeRpymntType();">
                                                                                        <?php
                                                                                        $sltdAcctDedctn = "";
                                                                                        $sltdManPymnts = "";
                                                                                        if ($row[26] == "Account Deductions") {
                                                                                            $sltdAcctDedctn = "selected";
                                                                                        } else if ($row[26] == "Manual Payments") {
                                                                                            $sltdManPymnts = "selected";
                                                                                        }
                                                                                        ?>                                                                                        
                                                                                        <option value="Account Deductions" selected <?php echo $sltdAcctDedctn; ?>>Account Deductions</option>
                                                                                        <!--<option value="Manual Payments" <?php echo ""; ?>>Manual Payments</option>-->
                                                                                    </select>
                                                                                </div>
                                                                                <div  class="col-md-6" style="padding-right: 0px !important; ">
                                                                                    <div class="input-group">
                                                                                        <input type="text" class="form-control" aria-label="..." id="rpmntSrcAcct" value="<?php echo $rpymntAccNo; ?>" readonly>
                                                                                        <input type="hidden" id="rpmntSrcAcctID" value="<?php echo $row[19]; ?>">
                                                                                        <input type="hidden" id="gnrlOrgID" value="<?php echo $orgID; ?>">
                                                                                        <label <?php echo $dsplyRSADOptn; ?> id="rpmntSrcAcctDiv" class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Bank Accounts (For Loan Repayment)', 'gnrlOrgID', 'bnkCustomerID', '', 'radio', true, '', 'rpmntSrcAcctID', 'rpmntSrcAcct', 'clear', 1, '');">
                                                                                            <span class="glyphicon glyphicon-th-list"></span>
                                                                                        </label>
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
                                                                        <fieldset class="basic_person_fs5"><legend class="basic_person_lg1">Collaterals and Sectors</legend>
                                                                            <div  class="col-md-12">
                                                                                <?php
                                                                                $dsplyCCOptn = "";
                                                                                $dsplyICOptn = "";
                                                                                $dsplyPCOptn = "";
                                                                                if ($row3[13] == "No") {
                                                                                    $dsplyCCOptn = "style='display:none;'";
                                                                                }
                                                                                if ($row3[19] == "No") {
                                                                                    $dsplyICOptn = "style='display:none;'";
                                                                                }
                                                                                if ($row3[16] == "No") {
                                                                                    $dsplyPCOptn = "style='display:none;'";
                                                                                }
                                                                                ?>
                                                                                <div class="form-group form-group-sm" id="cashColtDiv1">
                                                                                    <label for="cashCollateral" class="control-label col-md-4">Cash Collateral:</label>
                                                                                    <div  class="col-md-8">
                                                                                        <div class="input-group" id="cashColtDivIG" style="width:100% !important;">
                                                                                            <input type="text" class="form-control" aria-label="..." id="cashCollateral" readonly="readonly" value="<?php echo $cashColtAccNo; ?>">
                                                                                            <input type="hidden" class="form-control" aria-label="..." id="cashCollateralValue" value="<?php echo $cashCollateralValue; ?>" readonly>
                                                                                            <input type="hidden" class="form-control" aria-label="..." id="cashColHasLien" value="No" readonly>
                                                                                            <input type="hidden" id="cashCollateralID" value="<?php echo $row[20]; ?>"> 
                                                                                            <input type="hidden" id="gnrlOrgID" value="<?php echo $orgID; ?>">
                                                                                            <label id="cashColtDiv" <?php echo $dsplyCCOptn; ?> class="btn btn-primary btn-file input-group-addon" onclick="onClickCollateral(<?php echo $pgNo; ?>,<?php echo $subPgNo; ?>, 1);">
                                                                                                <span class="glyphicon glyphicon-th-list"></span>
                                                                                            </label>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="form-group form-group-sm" id="investmentColtDiv1">
                                                                                    <label for="invstmntCollateral" class="control-label col-md-4">Investment Collateral:</label>
                                                                                    <div  class="col-md-8">
                                                                                        <div class="input-group" id="investmentColtDivIG" style="width:100% !important;">
                                                                                            <input type="text" class="form-control" aria-label="..." id="invstmntCollateral" readonly="readonly" value="<?php echo $invstmntColtNm; ?>">
                                                                                            <input type="hidden" class="form-control" aria-label="..." id="invstmntCollateralValue" value="<?php echo $invstmntCollateralValue; ?>" readonly>
                                                                                            <input type="hidden" class="form-control" aria-label="..." id="invstmntColHasLien" value="No" readonly>
                                                                                            <input type="hidden" id="invstmntCollateralID" value="<?php echo $row[21]; ?>">
                                                                                            <input type="hidden" id="gnrlOrgID" value="<?php echo $orgID; ?>">
                                                                                            <label id="investmentColtDiv" <?php echo $dsplyICOptn; ?> class="btn btn-primary btn-file input-group-addon" onclick="onClickCollateral(<?php echo $pgNo; ?>,<?php echo $subPgNo; ?>, 2);">
                                                                                                <span class="glyphicon glyphicon-th-list"></span>
                                                                                            </label>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="form-group form-group-sm" id="propertyColtDiv1">
                                                                                    <label for="prptyCollateral" class="control-label col-md-4">Property Collateral:</label>
                                                                                    <div  class="col-md-8">
                                                                                        <div class="input-group" id="propertyColtDivIG" style="width:100% !important;">
                                                                                            <input type="text" class="form-control" aria-label="..." id="prptyCollateral" readonly="readonly" value="<?php echo $prptyColtNm; ?>">
                                                                                            <input type="hidden" class="form-control" aria-label="..." id="prptyCollateralValue" value="<?php echo $prptyCollateralValue; ?>" readonly>
                                                                                            <input type="hidden" class="form-control" aria-label="..." id="prptyColHasLien" value="No" readonly>
                                                                                            <input type="hidden" id="prptyCollateralID" value="<?php echo $row[22]; ?>">
                                                                                            <input type="hidden" id="gnrlOrgID" value="<?php echo $orgID; ?>">
                                                                                            <label id="propertyColtDiv" <?php echo $dsplyPCOptn; ?> class="btn btn-primary btn-file input-group-addon" onclick="onClickCollateral(<?php echo $pgNo; ?>,<?php echo $subPgNo; ?>, 3);">
                                                                                                <span class="glyphicon glyphicon-th-list"></span>
                                                                                            </label>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-12" style="padding:2px 1px 2px 1px !important;border-top:1px solid #ddd;">
                                                                                    <div class="form-group form-group-sm">
                                                                                        <label for="sctrMjr" class="control-label col-md-4">Major Sector:</label>
                                                                                        <div  class="col-md-8">
                                                                                            <div class="input-group">
                                                                                                <input type="text" class="form-control" aria-label="..." id="sctrMjr" value="<?php echo $sector_clsfctn_major; ?>" readonly="readonly">
                                                                                                <input type="hidden" id="sctrMjrID" value="<?php echo $row[34]; ?>">
                                                                                                <input type="hidden" id="gnrlOrgID" value="<?php echo $orgID; ?>">
                                                                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'MCF Loan Sectors - Major', 'gnrlOrgID', '', '', 'radio', true, '', 'sctrMjrID', 'sctrMjr', 'clear', 1, '');">
                                                                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                                                                </label>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="form-group form-group-sm">
                                                                                        <label for="sctrMnr" class="control-label col-md-4">Minor Sector:</label>
                                                                                        <div  class="col-md-8">
                                                                                            <div class="input-group">
                                                                                                <input type="text" class="form-control" aria-label="..." id="sctrMnr" value="<?php echo $sector_clsfctn_minor; ?>" readonly="readonly">
                                                                                                <input type="hidden" id="sctrMnrID" value="<?php echo $row[35]; ?>">
                                                                                                <input type="hidden" id="gnrlOrgID" value="<?php echo $orgID; ?>">
                                                                                                <label class="btn btn-primary btn-file input-group-addon" onclick="onClickSectorMinor();">
                                                                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                                                                </label>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>            
                                                                            </div>
                                                                        </fieldset>
                                                                    </div>
                                                                </div> 
                                                                <div class="row"><!-- ROW 2 -->
                                                                    <div class="col-lg-12">
                                                                        <fieldset id="guarantorsFldSt" class="basic_person_fs5"><legend id="guarantorsLgnd" class="basic_person_lg1">Guarantor(s)</legend>
                                                                            <div  class="col-md-12">
                                                                                <div class="row"><!-- ROW 3 -->
                                                                                    <div class="col-lg-6">
                                                                                        <?php if(($trnsStatus == "Incomplete" ||  $trnsStatus == "Rejected" || $trnsStatus == "Withdrawn") && $vwtypActn != "VIEW") { ?>
                                                                                        <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="getLoanGrntaForm('myFormsModalx', 'myFormsModalxBody', 'myFormsModalxTitle', 'acctSignatoryForm', '', 'Add Guarantor', 15, <?php echo $subPgNo; ?>, 5, '', - 1);">
                                                                                            <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                                            Add Guarantor
                                                                                        </button>
                                                                                        <?php } ?>
                                                                                    </div>                                                                
                                                                                    <div class="col-lg-6">&nbsp;</div>
                                                                                </div>
                                                                                <table id="loanGuarantorsTblAdd" class="table table-striped table-bordered table-responsive" cellspacing="0" width="100%" style="width:100%;">
                                                                                    <thead>
                                                                                        <tr>
                                                                                            <?php if(($trnsStatus == "Incomplete" ||  $trnsStatus == "Rejected" || $trnsStatus == "Withdrawn") && $vwtypActn != "VIEW"){ ?>
                                                                                            <th>...</th>
                                                                                            <th>...</th>
                                                                                            <?php } ?>
                                                                                            <th>No.</th>
                                                                                            <th>Guarantor</th>
                                                                                            <th>ID No.</th>
                                                                                            <?php if(($trnsStatus == "Incomplete" ||  $trnsStatus == "Rejected" || $trnsStatus == "Withdrawn") && $vwtypActn != "VIEW"){ ?>
                                                                                            <th>...</th>
                                                                                            <?php } ?>
                                                                                            <th style="display:none;">Src Type</th>
                                                                                            <th style="display:none;">GuatantorID</th>                                                                        
                                                                                        </tr>
                                                                                    </thead>
                                                                                    <tbody>
                                                                                        <?php
                                                                                        $cnta = 0;
                                                                                        $result2 = get_Loan_Guarantors($pkID);
                                                                                        while ($row2 = loc_db_fetch_array($result2)) {
                                                                                            $cnta = $cnta + 1;
                                                                                            $localIdNo = getLoanGuarantorLocalIDNo($row2[2], $row2[3]);
                                                                                            $loanGrntaID = $row2[0];
                                                                                            ?>
                                                                                            <tr id="loanGuarantorsTblAddRow<?php echo $loanGrntaID; ?>">
                                                                                                <?php if(($trnsStatus == "Incomplete" ||  $trnsStatus == "Rejected" || $trnsStatus == "Withdrawn") && $vwtypActn != "VIEW"){ ?>
                                                                                                    <td>
                                                                                                        <button type="button" class="btn btn-default btn-sm" onclick="getLoanGrntaForm('myFormsModalx', 'myFormsModalxBody', 'myFormsModalxTitle', 'acctSignatoryForm', '', 'Edit Guarantor', 15, <?php echo $subPgNo; ?>, 5, 'EDIT', <?php echo $loanGrntaID; ?>);" style="padding:2px !important;">
                                                                                                            <img src="cmn_images/edit32.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                                                        </button>
                                                                                                    </td>
                                                                                                    <td>
                                                                                                        <button type="button" class="btn btn-default btn-sm" onclick="deleteLoanRqstGuarantor(<?php echo $loanGrntaID; ?>,'<?php echo $trnsStatus; ?>');" style="padding:2px !important;">
                                                                                                            <img src="cmn_images/delete.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                                                        </button>
                                                                                                    </td>
                                                                                                <?php } ?>
                                                                                                <td><?php echo $cnta; ?></td>
                                                                                                <td><?php echo $row2[1]; ?></td>
                                                                                                <td><?php echo $localIdNo; ?></td>
                                                                                                <?php if(($trnsStatus == "Incomplete" ||  $trnsStatus == "Rejected" || $trnsStatus == "Withdrawn") && $vwtypActn != "VIEW"){ ?>
                                                                                                <td><button type="button" class="btn btn-default btn-sm" onclick="getLoanGrntaForm('myFormsModalx', 'myFormsModalxBody', 'myFormsModalxTitle', 'acctSignatoryForm', '', 'Edit Guarantor', 15, <?php echo $subPgNo; ?>, 5, 'VIEW', <?php echo $loanGrntaID; ?>);" style="padding:2px !important;">
                                                                                                        <img src="cmn_images/kghostview.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                                                    </button>
                                                                                                </td>
                                                                                                <?php } ?>
                                                                                                <td style="display:none;"><?php echo $row2[2]; ?></td>
                                                                                                <td style="display:none;"><?php echo $row2[0]; ?></td>                                                                        
                                                                                            </tr>
                                                                                            <?php
                                                                                        }
                                                                                        ?>
                                                                                    </tbody>
                                                                                </table>
                                                                            </div>
                                                                        </fieldset>
                                                                    </div>
                                                                </div>  
                                                            </div>
                                                        </div>  
                                                        <!--</fieldset>-->
                                                    </div>
                                                </div>                                              
                                            </form>  
                                        </div>
                                        <div id="prflCMDataEDT" class="tab-pane fade" style="border:none !important;"></div>
                                        <div id="prflCMAttchmntEDT" class="tab-pane fade" style="border:none !important;"></div>      
                                    </div>                        
                                </div>                         
                            </div>                
                        </div>          
                    </div>
                    <?php
                }
            } else if ($vwtypActn === "ADD") {
                
                $trnsStatus = "Incomplete";
                $sbmtdTrnsHdrID = -1;

                $rqstatusColor = "red";
                $mkReadOnly = "";
                $mkRmrkReadOnly = "";                
                $trnsTtl = 0.00;
                $crncy = "GHS";
                $crncyID = ""; 
                $isDisbursed = "NO";
                $payDate = "";

                $prsnBranchID = get_Person_BranchID($prsnid);
                $prsnBranch = getGnrlRecNm("org.org_sites_locations", "location_id", "site_desc||' ('||location_code_name||')'", "pasn.get_prsn_siteid($prsnid)");
                /* Add */
                ?>
                <div class="row" style="margin: 0px 0px 10px 0px !important;" >
                    <input type="hidden" id="lnRqstID" value=""/>
                    <input type="hidden" id="lnprflFctrId" value="-1"/>
                    <input class="form-control" id="addOrEditForm" type = "hidden" placeholder="addOrEditForm" value="Add"/>                    
                </div>                    
                <div class="row" style="margin: 0px 0px 10px 0px !important;">
                    <div class="col-md-12" style="padding:0px 0px 0px 0px !important;">
                        <button type="button" class="btn btn-default btn-sm phone-only-btn" onclick="openATab('#allmodules', 'grp=17&typ=1&pg=2&vtyp=0');">Main Parameters</button>
                        <button type="button" class="btn btn-default btn-sm phone-only-btn" onclick="openATab('#prflCMDataEDT', 'grp=17&typ=1&pg=2&vtyp=1');">Standard Events Accounting</button>
                        <button type="button" class="btn btn-default btn-sm phone-only-btn" onclick="openATab('#prflCMAttchmntEDT', 'grp=17&typ=1&pg=2&vtyp=2');">Custom Events Accounting</button>
                    </div>
                </div>
                <div class="">
                    <ul class="nav nav-tabs rho-hideable-tabs" style="margin-top:-10px !important;">
                        <li class="active"><a data-toggle="tab" data-rhodata="&pg=2&vtyp=0" href="#prflCMHomeEDT" id="prflCMHomeEDTtab">Application Details</a></li>
                        <li id="standardEvtns" style="display: none;"><a data-toggle="tabajxprfledt" data-rhodata="&pg=2&vtyp=1" href="#prflCMDataEDT" onclick="newLoanRequestOpenATab('#prflCMDataEDT', 'grp=17&typ=1&pg=<?php echo $pgNo; ?>&subPgNo=<?php echo $subPgNo; ?>&vtyp=1&vtypActn=ADD');" id="prflCMDataEDTtab">Credit Risk Assessment</a></li>
                        <li id="customEvnts" style="display: none;"><a data-toggle="tabajxprfledt" data-rhodata="&pg=2&vtyp=2" href="#prflCMAttchmntEDT" onclick="newLoanRequestOpenATab('#prflCMAttchmntEDT', 'grp=17&typ=1&pg=<?php echo $pgNo; ?>&subPgNo=<?php echo $subPgNo; ?>&vtyp=2&vtypActn=VIEW');" id="prflCMAttchmntEDTtab">Workflow Approval</a></li>
                    </ul>
                    <div class="row">                  
                        <div class="col-md-12">
                            <div class="custDiv"> 
                                <div class="tab-content">
                                    <div id="prflCMHomeEDT" class="tab-pane fadein active" style="border:none !important;"> 
                                        <div class="col-md-12" style="padding:0px 0px 10px 1px !important;">
                                            <div style="padding:0px 1px 0px 15px !important;float:left;">
                                                    <button type="button" class="btn btn-default btn-sm" style="height:30px;" id="myVmsTrnsStatusBtn">
                                                            <span style="font-weight:bold;">Status: </span><span style="color:<?php echo $rqstatusColor; ?>;font-weight: bold;"><?php echo $trnsStatus; ?></span>
                                                    </button>
                                            </div>
                                            <div style="padding:0px 1px 0px 1px !important;float:right;">
                                                <button type="button" class="btn btn-default btn-sm" style="height:30px;" onclick="saveLoanApplication(<?php echo $pgNo; ?>, <?php echo $subPgNo; ?>, <?php echo $vwtyp; ?>);"><img src="cmn_images/FloppyDisk.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Save&nbsp;</button>                                                    
                                            </div>                                            
                                        </div>                                            
                                        <form class="form-horizontal" id="customerAccountForm">
                                            <div style="display:none;">
                                                <!--DATA FOR VALIDATIONS-->
                                                <input type="hidden" id="currencyId" value="">
                                                <input type="hidden" id="isAccountRqrd" value="">
                                                <input type="hidden" id="loanMaxAmount" value="">
                                                <input type="hidden" id="loanMinAmount" value="">
                                                <input type="hidden" id="maxLoanTenor" value="">
                                                <input type="hidden" id="maxLoanTenorType" value="">
                                                <input type="hidden" id="guarantorRequired" value="">
                                                <input type="hidden" id="guarantorNo" value="">
                                                <input type="hidden" id="productType" value="">
                                                <input type="hidden" id="custTypeCustgrp" value="">
                                                <input type="hidden" id="custTypeCorp" value="">
                                                <input type="hidden" id="custTypeInd" value="">
                                                <input type="hidden" id="cashCollateralRqrd" value="">
                                                <input type="hidden" id="valueFlatCashColt" value="">
                                                <input type="hidden" id="valuePrcntCashColt" value="">                                                 
                                                <input type="hidden" id="prptyCollateralRqrd" value="">
                                                <input type="hidden" id="valueFlatPrptyColt" value="">
                                                <input type="hidden" id="valuePrcntPrptyColt" value="">
                                                <input type="hidden" id="invstmntCollateralRqrd" value="">
                                                <input type="hidden" id="valueInvstmntCashColt" value="">
                                                <input type="hidden" id="valueInvstmntPrcntColt" value="">   
                                                <input type="hidden" id="minLoanTenor" value="">
                                                <input type="hidden" id="minLoanTenorType" value="">
                                                <input type="hidden" id="baseIntRate" value="">
                                            </div>
                                            <div class="row"><!-- ROW 1 -->
                                                <div class="col-lg-12">
                                                    <fieldset class="basic_person_fs5"><legend class="basic_person_lg">Application Header</legend>
                                                        <div class="col-lg-4">
                                                            <input class="form-control" id="loanRqstID" type = "hidden" placeholder="Loan Request ID" value=""/>                                                        
                                                            <div class="form-group form-group-sm">
                                                                <label for="rqsNumber" class="control-label col-md-4">Request No:</label>
                                                                <div class="col-md-8">
                                                                    <input class="form-control" id="rqsNumber" type = "text" placeholder="" value="" readonly/>                                                                                                                                            
                                                                </div>
                                                            </div>
                                                            <div class="form-group form-group-sm">
                                                                <label for="applctnDate" class="control-label col-md-4">Appl. Date:</label>
                                                                <div  class="col-md-8">
                                                                    <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input3" data-link-format="yyyy-mm-dd">
                                                                        <input class="form-control" size="16" type="text" id="applctnDate" value="" readonly="">
                                                                        <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                                                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                                        <span class="input-group-addon" onclick="javascript:unfreezeDialog();"><span class="glyphicon glyphicon-info-sign"></span></span>
                                                                    </div>
                                                                </div>
                                                            </div>                                                        
                                                            <div class="form-group form-group-sm">
                                                                <label for="bnkBranch" class="control-label col-md-4">Branch:</label>
                                                                <div  class="col-md-8">
                                                                    <div class="input-group">
                                                                        <input type="text" class="form-control" aria-label="..." id="bnkBranch" value="<?php echo $prsnBranch; ?>" readonly="">
                                                                        <input type="hidden" id="bnkBranchID" value="<?php echo $prsnBranchID; ?>"> 
                                                                        <input type="hidden" id="gnrlOrgID" value="<?php echo $orgID; ?>">
                                                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Sites/Locations New', 'gnrlOrgID', '', '', 'radio', true, '', 'bnkBranchID', 'bnkBranch', 'clear', 1, '');">
                                                                            <span class="glyphicon glyphicon-th-list"></span>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>                                                          
                                                        <div class="col-lg-3">
                                                            <div class="form-group form-group-sm">
                                                                <label for="prpsOfLoan" class="control-label col-md-3">Purpose:</label>
                                                                <div  class="col-md-9">
                                                                    <textarea class="form-control rqrdFld" id="prpsOfLoan" cols="2" placeholder="Purpose of Loan" rows="5"></textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-5">
                                                            <div class="form-group form-group-sm">
                                                                <label for="acctNumber" class="control-label col-md-5">Loan Account Number:</label>
                                                                <div class="col-md-7">
                                                                    <input class="form-control" id="acctNumber" type = "text" placeholder="" value="" readonly/>
                                                                    <!--LOAN APPLICATION ID-->
                                                                    <input class="form-control" id="acctID" type = "hidden" placeholder="Account ID" value=""/>                                                                                                                                            
                                                                </div>
                                                            </div> 
                                                            <div class="form-group form-group-sm">
                                                                <label for="rcvryOfficer" class="control-label col-md-5">Credit Officer:</label>
                                                                <div  class="col-md-7">
                                                                    <div class="input-group">
                                                                        <input type="text" class="form-control" aria-label="..." id="rcvryOfficer" value="" readonly>
                                                                        <input type="hidden" id="rcvryOfficerID" value="<?php echo -1; ?>">
                                                                        <input type="hidden" id="gnrlOrgID" value="<?php echo $orgID; ?>">
                                                                        <label class="btn btn-primary btn-file input-group-addon" onclick="clearROLovField('rcvryOfficer', 'rcvryOfficerID');">
                                                                            <span class="glyphicon glyphicon-remove"></span>
                                                                        </label>                                                                        
                                                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'MCF Credit Officers', 'gnrlOrgID', '', '', 'radio', true, '', 'rcvryOfficerID', 'rcvryOfficer', 'clear', 1, '');">
                                                                            <span class="glyphicon glyphicon-th-list"></span>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div>                                                             
                                                            <div class="form-group form-group-sm">
                                                                <label for="status" class="control-label col-md-5">Status:</label>
                                                                <div class="col-md-7">
                                                                    <input class="form-control" style="color:<?php echo $rqstatusColor; ?>" id="status" type = "text" placeholder="" value="Incomplete" readonly/>                                                                                                                                        
                                                                </div>
                                                            </div>                                                            

                                                        </div>
                                                    </fieldset>
                                                </div>
                                            </div>
                                            <div class="row"><!-- ROW 2 -->
                                                <div class="col-lg-12">
                                                    <!--<fieldset class="basic_person_fs1" style="margin-top:5px !important;">-->
                                                    <legend class="basic_person_lg" style="margin-top:10px !important;">Application Details</legend>                                                     
                                                    <div class="row"><!-- ROW 1 -->
                                                        <div class="col-lg-6">
                                                            <fieldset class="basic_person_fs1"><legend class="basic_person_lg">Request Details</legend>
                                                                <div class="form-group form-group-sm">
                                                                    <label for="custType" class="control-label col-md-4">Customer Type:</label>
                                                                    <input type="hidden" id="custTypeYes" value="YES">
                                                                    <div  class="col-md-8">
                                                                        <select class="form-control" id="custType" onchange="onChangeOfCustType()">
                                                                            <option value="--Please Select--">--Please Select--</option>
                                                                            <option value="Corporate">Corporate</option>
                                                                            <option value="Individual">Individual</option>
                                                                            <option value="Group">Customer Group</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group form-group-sm" id="custGrpDiv">
                                                                    <label for="custGrp" class="control-label col-md-4">Group Name:</label>
                                                                    <div  class="col-md-8">
                                                                        <div class="input-group">
                                                                            <input type="text" class="form-control" aria-label="..." id="custGrp" value="" readonly="readonly">
                                                                            <input type="hidden" id="custGrpID" value="-1">
                                                                            <input type="hidden" id="gnrlOrgID" value="<?php echo $orgID; ?>">
                                                                            <label class="btn btn-primary btn-file input-group-addon" onclick="onClickGroupNmOrCust(0)">
                                                                                <span class="glyphicon glyphicon-th-list"></span>
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </div>    
                                                                <div class="form-group form-group-sm">
                                                                    <label for="bnkCustomer" class="control-label col-md-4">Customer Name:</label>
                                                                    <div  class="col-md-8">
                                                                        <div class="input-group">
                                                                            <input type="text" class="form-control" aria-label="..." id="bnkCustomer" value="" readonly="readonly">
                                                                            <input type="hidden" id="bnkCustomerID" value="-1">
                                                                            <input type="hidden" id="gnrlOrgID" value="<?php echo $orgID; ?>">
                                                                            <label class="btn btn-primary btn-file input-group-addon" onclick="onClickGroupNmOrCust(1);">
                                                                                <span class="glyphicon glyphicon-th-list"></span>
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group form-group-sm">
                                                                    <label for="crdtType" class="control-label col-md-4">Credit Type:</label>
                                                                    <div  class="col-md-8">
                                                                        <select class="form-control" id="crdtType" onchange="onChangeCrdtType();">
                                                                            <option value="Loan">Loan</option>
                                                                            <option value="Overdraft">Overdraft</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group form-group-sm">
                                                                    <label for="prdtType" class="control-label col-md-4">Product:</label>
                                                                    <div  class="col-md-8">
                                                                        <div class="input-group">
                                                                            <input type="text" class="form-control" aria-label="..." id="prdtType" value="" readonly="readonly">
                                                                            <input type="hidden" id="prdtTypeID" value="-1">
                                                                            <label class="btn btn-primary btn-file input-group-addon" onclick="onClickProduct(<?php echo $pgNo; ?>,<?php echo $subPgNo; ?>);">
                                                                                <span class="glyphicon glyphicon-th-list"></span>
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </div> 
                                                                <div class="form-group form-group-sm">
                                                                    <label for="rqstType" class="control-label col-md-4">Request Type:</label>
                                                                    <div  class="col-md-8">
                                                                        <select class="form-control" id="rqstType" onchange="onChangeOfRqstType();">
                                                                            <option value="New Request">New Request</option>
                                                                            <option value="Top-Up Request">Top-Up Request</option>
                                                                            <option value="Reschedule Request">Reschedule Request</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group form-group-sm" id="rqstIdForTopupDiv" style="display:none;">
                                                                    <label for="refLoanrqst" class="control-label col-md-4">Request ID - Ref.:</label>
                                                                    <div  class="col-md-8">
                                                                        <div class="input-group">
                                                                            <input type="text" class="form-control" aria-label="..." id="refLoanrqst" value="" readonly="readonly">
                                                                            <input type="hidden" id="refLoanrqstID" value="-1">
                                                                            <input type="hidden" id="gnrlOrgID" value="<?php echo $orgID; ?>">
                                                                            <label class="btn btn-primary btn-file input-group-addon" onclick="onClickRefLoanRqst(<?php echo $pgNo; ?>,<?php echo $subPgNo; ?>);">
                                                                                <span class="glyphicon glyphicon-th-list"></span>
                                                                            </label>
                                                                            <label class="btn btn-primary btn-file input-group-addon" title="View Details" onclick="viewRefLoanRqst()">
                                                                                <img src="cmn_images/kghostview.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </div>   
                                                                <div class="form-group form-group-sm">
                                                                    <label for="loanAmount" class="control-label col-md-4">Amount:</label>
                                                                    <div  class="col-md-8">
                                                                        <div class="input-group">
                                                                            <label id="crncyIsoCode" class="btn btn-primary btn-file input-group-addon">
                                                                            </label>		
                                                                            <input class="form-control" id="loanAmount" type = "number" min="0" placeholder="Amount" value=""/>
                                                                        </div>
                                                                    </div>
                                                                </div>                                                                 
                                                                <div class="form-group form-group-sm">
                                                                    <label for="loanTenor" class="control-label col-md-4">Tenure:</label>
                                                                    <div  class="col-md-8">
                                                                        <div class="input-group col-md-12">
                                                                            <div  class="col-md-4" style="padding-left:0px !important;">
                                                                                <input class="form-control" id="loanTenor" type = "number" min="0" placeholder="" value=""/>
                                                                            </div>
                                                                            <div  class="col-md-8" style="padding-left:0px !important; padding-right: 0px !important; ">
                                                                                <select class="form-control" id="loanTenorType" >
                                                                                    <option value="Year(s)" selected>Year(s)</option>
                                                                                    <option value="Month(s)">Month(s)</option>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                    </div>                                                            
                                                                </div> 
                                                                <div class="form-group form-group-sm">
                                                                    <label for="interestRate" class="control-label col-md-4">Interest Rate:</label>
                                                                    <div  class="col-md-8">
                                                                        <div class="input-group">
                                                                            <input class="form-control" id="interestRate" type = "number" min="0" placeholder="Interest Rate" value=""/>
                                                                            <label class="btn btn-primary btn-file input-group-addon" style="padding-left: 2px !important;">
                                                                                % Per Annum
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </div>  
                                                                <div class="form-group form-group-sm">
                                                                    <label for="rpmntSrcAcct" class="control-label col-md-4">Repayment:</label>
                                                                    <div  class="col-md-8">
                                                                        <div class="input-group col-md-12">
                                                                            <div  class="col-md-6" style="padding-left:0px !important; padding-right: 0px !important; ">
                                                                                <select class="form-control" id="rpmntSrcType" onchange="onChangeRpymntType();">
                                                                                    <option value="Account Deductions" selected>Account Deductions</option>
                                                                                    <!--<option value="Manual Payments">Manual Payments</option>-->
                                                                                </select>
                                                                            </div>
                                                                            <div  class="col-md-6" style="padding-right: 0px !important; ">
                                                                                <div class="input-group">
                                                                                    <input type="text" class="form-control" aria-label="..." id="rpmntSrcAcct" value="" readonly>
                                                                                    <input type="hidden" id="rpmntSrcAcctID" value="">
                                                                                    <input type="hidden" id="gnrlOrgID" value="<?php echo $orgID; ?>">
                                                                                    <label id="rpmntSrcAcctDiv" class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Bank Accounts (For Loan Repayment)', 'gnrlOrgID', 'bnkCustomerID', '', 'radio', true, '', 'rpmntSrcAcctID', 'rpmntSrcAcct', 'clear', 1, '');">
                                                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                                                    </label>
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
                                                                    <fieldset class="basic_person_fs5"><legend class="basic_person_lg">Collaterals and Sectors</legend>
                                                                        <div  class="col-md-12">
                                                                            <div class="form-group form-group-sm" id="cashColtDiv1">
                                                                                <label for="cashCollateral" class="control-label col-md-4">Cash Collateral:</label>
                                                                                <div  class="col-md-8">
                                                                                    <div class="input-group" id="cashColtDivIG" style="width:100% !important;">
                                                                                        <input type="text" class="form-control" aria-label="..." id="cashCollateral" value="" readonly>
                                                                                        <input type="hidden" class="form-control" aria-label="..." id="cashCollateralValue" value="0.00" readonly>
                                                                                        <input type="hidden" class="form-control" aria-label="..." id="cashColHasLien" value="No" readonly>
                                                                                        <input type="hidden" id="cashCollateralID" value="-1"> 
                                                                                        <input type="hidden" id="gnrlOrgID" value="<?php echo $orgID; ?>">
                                                                                        <label id="cashColtDiv" class="btn btn-primary btn-file input-group-addon" onclick="onClickCollateral(<?php echo $pgNo; ?>,<?php echo $subPgNo; ?>, 1);">
                                                                                            <span class="glyphicon glyphicon-th-list"></span>
                                                                                        </label>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group form-group-sm" id="investmentColtDiv1">
                                                                                <label for="invstmntCollateral" class="control-label col-md-4">Investment Collateral:</label>
                                                                                <div  class="col-md-8">
                                                                                    <div class="input-group" id="investmentColtDivIG" style="width:100% !important;">
                                                                                        <input type="text" class="form-control" aria-label="..." id="invstmntCollateral" value="" readonly>
                                                                                        <input type="hidden" class="form-control" aria-label="..." id="invstmntCollateralValue" value="0.00" readonly>
                                                                                        <input type="hidden" class="form-control" aria-label="..." id="invstmntColHasLien" value="No" readonly>
                                                                                        <input type="hidden" id="invstmntCollateralID" value="-1">
                                                                                        <input type="hidden" id="gnrlOrgID" value="<?php echo $orgID; ?>">
                                                                                        <label id="investmentColtDiv" class="btn btn-primary btn-file input-group-addon" onclick="onClickCollateral(<?php echo $pgNo; ?>,<?php echo $subPgNo; ?>, 2);">
                                                                                            <span class="glyphicon glyphicon-th-list"></span>
                                                                                        </label>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group form-group-sm" id="propertyColtDiv1">
                                                                                <label for="prptyCollateral" class="control-label col-md-4">Property Collateral:</label>
                                                                                <div  class="col-md-8">
                                                                                    <div class="input-group" id="propertyColtDivIG" style="width:100% !important;">
                                                                                        <input type="text" class="form-control" aria-label="..." id="prptyCollateral" value="" readonly>
                                                                                        <input type="hidden" class="form-control" aria-label="..." id="prptyCollateralValue" value="0.00" readonly>
                                                                                        <input type="hidden" class="form-control" aria-label="..." id="prptyColHasLien" value="No" readonly>
                                                                                        <input type="hidden" id="prptyCollateralID" value="-1">
                                                                                        <input type="hidden" id="gnrlOrgID" value="<?php echo $orgID; ?>">
                                                                                        <label id="propertyColtDiv" class="btn btn-primary btn-file input-group-addon" onclick="onClickCollateral(<?php echo $pgNo; ?>,<?php echo $subPgNo; ?>, 3); ;">
                                                                                            <span class="glyphicon glyphicon-th-list"></span>
                                                                                        </label>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-12" style="padding:2px 1px 2px 1px !important;border-top:1px solid #ddd;">
                                                                                <div class="form-group form-group-sm">
                                                                                    <label for="sctrMjr" class="control-label col-md-4">Major Sector:</label>
                                                                                    <div  class="col-md-8">
                                                                                        <div class="input-group">
                                                                                            <input type="text" class="form-control" aria-label="..." id="sctrMjr" value="" readonly="readonly">
                                                                                            <input type="hidden" id="sctrMjrID" value="-1">
                                                                                            <input type="hidden" id="gnrlOrgID" value="<?php echo $orgID; ?>">
                                                                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'MCF Loan Sectors - Major', 'gnrlOrgID', '', '', 'radio', true, '', 'sctrMjrID', 'sctrMjr', 'clear', 1, '');">
                                                                                                <span class="glyphicon glyphicon-th-list"></span>
                                                                                            </label>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="form-group form-group-sm">
                                                                                    <label for="sctrMnr" class="control-label col-md-4">Minor Sector:</label>
                                                                                    <div  class="col-md-8">
                                                                                        <div class="input-group">
                                                                                            <input type="text" class="form-control" aria-label="..." id="sctrMnr" value="" readonly="readonly">
                                                                                            <input type="hidden" id="sctrMnrID" value="-1">
                                                                                            <input type="hidden" id="gnrlOrgID" value="<?php echo $orgID; ?>">
                                                                                            <label class="btn btn-primary btn-file input-group-addon" onclick="onClickSectorMinor();">
                                                                                                <span class="glyphicon glyphicon-th-list"></span>
                                                                                            </label>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </fieldset>
                                                                </div>
                                                            </div> 
                                                            <div class="row"><!-- ROW 2 -->
                                                                <div class="col-lg-12">
                                                                    <fieldset id="guarantorsFldSt" class="basic_person_fs5"><legend id="guarantorsLgnd" class="basic_person_lg">Guarantors</legend>
                                                                        <div  class="col-md-12">
                                                                            <div class="row"><!-- ROW 3 -->
                                                                                <div class="col-lg-6">
                                                                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="getLoanGrntaForm('myLovModal', 'myLovModalBody', 'myLovModalTitle', 'acctSignatoryForm', '', 'Add Guarantor', 15, <?php echo $subPgNo; ?>, 5, '', - 1);">
                                                                                        <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                                        Add Guarantor
                                                                                    </button>
                                                                                </div>                                                                
                                                                                <div class="col-lg-6">&nbsp;</div>
                                                                            </div>
                                                                            <table id="loanGuarantorsTblAdd" class="table table-striped table-bordered table-responsive" cellspacing="0" width="100%" style="width:100%;">
                                                                                <thead>
                                                                                    <tr>
                                                                                        <th>...</th>
                                                                                        <th>No.</th>
                                                                                        <th>Guarantor</th>
                                                                                        <th>ID No.</th>
                                                                                        <th>...</th>
                                                                                        <th style="display:none;">Src Type</th>
                                                                                        <th style="display:none;">GuatantorID</th>                                                                        
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                    </fieldset>
                                                                </div>
                                                            </div>  
                                                        </div>
                                                    </div>  
                                                    <!--</fieldset>-->
                                                </div>
                                            </div>                                              
                                        </form>  
                                    </div>
                                    <div id="prflCMDataEDT" class="tab-pane fade" style="border:none !important;"></div>
                                    <div id="prflCMAttchmntEDT" class="tab-pane fade" style="border:none !important;"></div>      
                                </div>                        
                            </div>                         
                        </div>                
                    </div>          
                </div>
                <?php
            }
        } 
        else if ($vwtyp == "1") {
            
            $onlyViewMode = isset($_POST['onlyViewMode']) ? cleanInputData($_POST['onlyViewMode']) : '';
            
            /* RISK ASSESSMENT */
            if ($vwtypActn === "EDIT") {
                
            } else if ($vwtypActn === "SMRYPAGE") {
//var_dump($_POST);

                $loanRqstID = isset($_POST['lnRqstID']) ? cleanInputData($_POST['lnRqstID']) : -1;
                $lnprflFctrId = isset($_POST['lnprflFctrId']) ? cleanInputData($_POST['lnprflFctrId']) : -1;
                $chkedAssmntID = isset($_POST['chkedAssmntID']) ? cleanInputData($_POST['chkedAssmntID']) : -1;

                $optn = isset($_POST['optn']) ? cleanInputData($_POST['optn']) : 'FIRST';
                $currQstnNo = isset($_POST['qstnNo']) ? cleanInputData($_POST['qstnNo']) : 0;
                $qstnTtlNo = isset($_POST['qstnTtlNo']) ? cleanInputData($_POST['qstnTtlNo']) : 0;

                $assmntCmnts = isset($_POST['assmntCmnts']) ? cleanInputData($_POST['assmntCmnts']) : '';
                $status = isset($_POST['status']) ? cleanInputData($_POST['status']) : '';

                $pMode = isset($_POST['pMode']) ? cleanInputData($_POST['pMode']) : 0; //NEW

                $btnSbmtDsply = 'style="display:inline-block !important;"';
                $btnWdwlDsply = 'style="display:none !important;"';
                $btnFirst = 'style="display:inline-block !important;"';

                $cmntsRdOnly = '';
                if ($status == "Initiated") {
                    $btnSbmtDsply = 'style="display:none !important;"';
                    if($onlyViewMode != "VIEW"){
                        $btnWdwlDsply = 'style="display:inline-block !important;"';
                    }
                    $cmntsRdOnly = 'readonly="readonly"';
                    $btnFirst = 'style="display:none !important;"';
                } else if ($onlyViewMode == "VIEW" || ($status == "Approved" || $status == "Rescheduled" || $status == "Topped-Up")) {
                    $btnSbmtDsply = 'style="display:none !important;"';
                    $btnWdwlDsply = 'style="display:none !important;"';
                    $cmntsRdOnly = 'readonly="readonly"';
                    $btnFirst = 'style="display:none !important;"';
                } //else if ($statusTitle == "Withdrawn") {
                
                $assmntCmnts = get_LoanRqstAssmntCmnts($loanRqstID);
//}
//NEW
                if ($pMode == "0") {
                    if ($chkedAssmntID > 0) {
                        $lnprflFctrId = get_AssessmentProfileFactorID($chkedAssmntID);
//UPDATE 
                        update_AssessmentQuestion($chkedAssmntID, $loanRqstID, $lnprflFctrId);
                    }
                }


                $ttlScore = 0;
                $pssFlClr = "red";
                $pssOrFail = "FAILED";
                $lnPrdtAssmntSetID= -1;
                $passScore = 0.00;
                $lnPrdtID = getGnrlRecNm("mcf.mcf_loan_request", "loan_rqst_id", "loan_product_id", $loanRqstID);
                if($lnPrdtID != ""){
                    $lnPrdtID = (int)$lnPrdtID;

                    $lnPrdtAssmntSetID = getGnrlRecNm("mcf.mcf_prdt_loans", "loan_product_id", "scoring_set_hdr_id", $lnPrdtID);
                    if($lnPrdtAssmntSetID != ""){
                        $lnPrdtAssmntSetID = (int)$lnPrdtAssmntSetID;
                        $passScore = getGnrlRecNm("mcf.mcf_credit_scoring_set_hdr", "scoring_set_hdr_id", "pass_score", $lnPrdtAssmntSetID);
                        if($passScore != ""){
                            $passScore = (float)$passScore;
                        } else {
                            $passScore = 0.00;
                        }
                    }
                }                

//GET 
                ?>

                <div class="row" style="margin: 0px 0px 10px 0px !important;">
                    <div class="col-md-4" style="padding:0px 1px 0px 1px !important;"><button type="button" <?php echo $btnFirst; ?> class="btn btn-primary btn-sm"  onclick="getCreditAssessmentQstn(<?php echo $pgNo; ?>, <?php echo $subPgNo; ?>, <?php echo $vwtyp; ?>, 'FIRST', '<?php echo $vwtypActn; ?>', 1);"><span class="glyphicon glyphicon-fast-backward" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;"></span>FIRST</button> 
                            <!--<button type="button" class="btn btn-primary btn-sm"  onclick="getCreditAssessmentQstn(<?php echo $pgNo; ?>, <?php echo $subPgNo; ?>, <?php echo $vwtyp; ?>, 'PREVIOUS', '<?php echo $vwtypActn; ?>',1);"><span class="glyphicon glyphicon-backward" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;"></span>PREVIOUS</button>--></div>
                    <div class="col-md-4" style="padding:0px 0px 0px 15px !important; color: green !important; font-weight: bold !important; text-align: center !important; font-size: 16px !important;"><?php echo "PASS SCORE: ". number_format($passScore,2); ?></div>   
                    <div class="col-md-4" style="padding:0px 1px 0px 1px !important;">
                        <div id="divSW1" style="text-align:right !important;">
                            <button type="button" id="withdrawLoanRqst1" <?php echo $btnWdwlDsply; ?> class="btn btn-success btn-sm"  onclick="submitWithdrawLoanRqstActn(<?php echo $pgNo; ?>, <?php echo $subPgNo; ?>, <?php echo $vwtyp; ?>, 'WITHDRAW', '<?php echo $vwtypActn; ?>');"><img src="cmn_images/withdraw_rqst.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">WITHDRAW</button>             
                            <button type="button" id="submitLoanRqst1" <?php echo $btnSbmtDsply; ?> class="btn btn-success btn-sm"  onclick="submitWithdrawLoanRqstActn(<?php echo $pgNo; ?>, <?php echo $subPgNo; ?>, <?php echo $vwtyp; ?>, 'SUBMIT');"><span class="glyphicon glyphicon-log-in" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;"></span>SUBMIT</button>
                        </div>
                    </div>
                </div>                

                <form class="form-horizontal" id='assmntFinPage' action='' method='post' accept-charset='UTF-8'>
                    <div class="row"><!-- ROW 1 -->
                        <div class="col-lg-12">
                            <div class="dataTables_scroll">
                                <table class="gridtable" id="assmntSmryTable" cellspacing="0" width="100%" style="width:100%;min-width: 400px;">
                                    <thead>
                                        <tr>
                                            <th width="15%" class="likeheader" style="font-weight:bold !important;">PROFILE</th>
                                            <th width="25%" style="font-weight:bold !important;">RISK FACTOR</th>
                                            <th width="55%" class="likeheader" style="font-weight:bold !important;">ANSWER</th>
                                            <th width="5%" style="font-weight:bold !important;">SCORE</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        

//                                        
//                                        $resultsTtl = get_AssessmentSummary($loanRqstID);
//                                        while ($rowTtl = loc_db_fetch_array($resultsTtl)) {
//                                            $ttlScore = $ttlScore + $rowTtl[4];
//                                        }
                                        
                                        $rowCnt = 0;
                                        $results = get_AssessmentSummary($loanRqstID);
                                        while ($row = loc_db_fetch_array($results)) {
                                            $ttlScore = $ttlScore + $row[4];
                                            $rowCnt = $rowCnt +1;
                                            ?>
                                            <tr>
                                                <td width="15%" class="likeheader"><?php echo $row[1]; ?></td>
                                                <td width="25%" ><?php echo $row[2]; ?></td>
                                                <td width="55%" class="likeheader"><?php echo $row[3]; ?></td>
                                                <td width="5%" style="text-align:right !important; font-weight:bold !important;" ><?php echo $row[4]; ?></td>
                                            </tr>
                                        <?php 
                                            }
                                            if((int)$rowCnt <= 0){
                                                $pssOrFail = "NO";
                                                $pssFlClr = "green";
                                            } else {
                                                if((float)$passScore > 0 && (float)$passScore <= (float)$ttlScore){
                                                    $pssOrFail = "PASSED";
                                                    $pssFlClr = "green";
                                                }
                                            }
                                        ?>
                                        <tr>
                                            <td width="95%" colspan="3" class="likeheader" style="text-align:right !important; font-weight:bold !important; font-size: 16px !important; color:<?php echo $pssFlClr; ?> "><?php echo $pssOrFail; ?> TOTAL SCORE:</td>
                                            <td width="5%" id="ttlScoreId" class="likeheader" style="text-align:right !important; font-weight:bold !important; font-size: 15px !important; color:<?php echo $pssFlClr; ?> "><?php echo $ttlScore; ?></td>
                                        </tr>
                                    </tbody>

                                </table>
                            </div>
                        </div>                                
                    </div>   

                    <div class="row" style="margin-top:10px !important;"><!-- ROW 1 -->  
                        <div class="col-lg-12"> 
                            <fieldset class="basic_person_fs5"><legend class="basic_person_lg">COMMENTS</legend>
                                <div class="form-group form-group-sm">
                                    <!--<label for="trnsDesc" class="control-label col-md-4">Description:</label>-->
                                    <div  class="col-md-12">
                                        <textarea class="form-control rqrdFld" <?php echo $cmntsRdOnly; ?> id="assmntScoreCmnts" cols="2" placeholder="Comments" rows="4"><?php echo $assmntCmnts; ?></textarea>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                    </div>
                </form> 

                <div class="row" style="margin: 0px 0px 10px 0px !important;">
                    <div class="col-md-4" style="padding:0px 1px 0px 1px !important;"><button type="button" <?php echo $btnFirst; ?> class="btn btn-primary btn-sm"  onclick="getCreditAssessmentQstn(<?php echo $pgNo; ?>, <?php echo $subPgNo; ?>, <?php echo $vwtyp; ?>, 'FIRST', '<?php echo $vwtypActn; ?>', 1);"><span class="glyphicon glyphicon-fast-backward" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;"></span>FIRST</button> 
                    <!--<button type="button" class="btn btn-primary btn-sm"  onclick="getCreditAssessmentQstn(<?php echo $pgNo; ?>, <?php echo $subPgNo; ?>, <?php echo $vwtyp; ?>, 'PREVIOUS', '<?php echo $vwtypActn; ?>',1);"><span class="glyphicon glyphicon-backward" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;"></span>PREVIOUS</button>--></div>
                    <div class="col-md-4" style="padding:0px 0px 0px 15px !important;text-align: center !important;"><span id="qstnNoId" style="color:green;font-size: 15px !important;"><?php echo $qstnTtlNo; ?></span>&nbsp;of&nbsp;<span id="qstnTtlNoId" style="color:green;font-size: 15px !important;"><?php echo $qstnTtlNo; ?></span></div>   
                    <div class="col-md-4" style="padding:0px 1px 0px 1px !important;">
                        <div id="divSW" style="text-align:right !important;">
                            <button type="button" id="withdrawLoanRqst" <?php echo $btnWdwlDsply; ?> class="btn btn-success btn-sm"  onclick="submitWithdrawLoanRqstActn(<?php echo $pgNo; ?>, <?php echo $subPgNo; ?>, <?php echo $vwtyp; ?>, 'WITHDRAW', '<?php echo $vwtypActn; ?>');"><img src="cmn_images/withdraw_rqst.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">WITHDRAW</button>              
                            <button type="button" id="submitLoanRqst" <?php echo $btnSbmtDsply; ?> class="btn btn-success btn-sm"  onclick="submitWithdrawLoanRqstActn(<?php echo $pgNo; ?>, <?php echo $subPgNo; ?>, <?php echo $vwtyp; ?>, 'SUBMIT');"><span class="glyphicon glyphicon-log-in" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;"></span>SUBMIT</button>
                        </div>
                    </div>                
                </div>               

                <?php
            } else if ($vwtypActn === "ADD") {
//var_dump($_POST);
                $loanRqstID = isset($_POST['lnRqstID']) ? cleanInputData($_POST['lnRqstID']) : -1;
                $qstnTtlNo = isset($_POST['qstnTtlNo']) ? cleanInputData($_POST['qstnTtlNo']) : 0;

                $lnprflFctrId = isset($_POST['lnprflFctrId']) ? cleanInputData($_POST['lnprflFctrId']) : -1;
                $chkedAssmntID = isset($_POST['chkedAssmntID']) ? cleanInputData($_POST['chkedAssmntID']) : -1;
                $optn = isset($_POST['optn']) ? cleanInputData($_POST['optn']) : 'FIRST';
                $currQstnNo = isset($_POST['qstnNo']) ? cleanInputData($_POST['qstnNo']) : 0;


                $assmntCmnts = isset($_POST['assmntCmnts']) ? cleanInputData($_POST['assmntCmnts']) : '';
              

                $scoreSetHdrID = get_CreditScoreSetHdrIDForLoanRqst($loanRqstID);
                if ($scoreSetHdrID <= 0) {

                    $status = isset($_POST['status']) ? cleanInputData($_POST['status']) : '';


                    $btnSbmtDsply = 'style="display:inline-block !important;"';
                    $btnWdwlDsply = 'style="display:none !important;"';

                    $cmntsRdOnly = '';
                    if ($status == "Initiated") {
                        $btnSbmtDsply = 'style="display:none !important;"';
                        $btnWdwlDsply = 'style="display:inline-block !important;"';
                        $cmntsRdOnly = 'readonly="readonly"';
                    }
                    ?>
                    <div class="row" style="margin: 0px 0px 10px 0px !important;">
                        <div id="divSW1" style="float:right !important;">
                            <button type="button" id="withdrawLoanRqst1" <?php echo $btnWdwlDsply; ?> class="btn btn-success btn-sm"  onclick="submitWithdrawLoanRqstActn(<?php echo $pgNo; ?>, <?php echo $subPgNo; ?>, <?php echo $vwtyp; ?>, 'WITHDRAW', '<?php echo $vwtypActn; ?>');"><img src="cmn_images/withdraw_rqst.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">WITHDRAW</button>             
                            <button type="button" id="submitLoanRqst1" <?php echo $btnSbmtDsply; ?> class="btn btn-success btn-sm"  onclick="submitWithdrawLoanRqstActn(<?php echo $pgNo; ?>, <?php echo $subPgNo; ?>, <?php echo $vwtyp; ?>, 'SUBMIT');"><span class="glyphicon glyphicon-log-in" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;"></span>SUBMIT</button>
                        </div>                        
                        <legend class="basic_person_lg" id="assmntQstn" style="color:red">NO ASSESSMENT EXISTS FOR THIS LOAN/OVERDRAFT REQUEST</legend>
                        <span id="ttlScoreId" style="display:none !important;">0.00</span>
                    </div>
                    <div class="row" style="margin-top:10px !important;"><!-- ROW 1 -->  
                        <div class="col-lg-12"> 
                            <fieldset class="basic_person_fs5"><legend class="basic_person_lg">COMMENTS</legend>
                                <div class="form-group form-group-sm">
                                    <!--<label for="trnsDesc" class="control-label col-md-4">Description:</label>-->
                                    <div  class="col-md-12">
                                        <textarea class="form-control rqrdFld" <?php echo $cmntsRdOnly; ?> id="assmntScoreCmnts" cols="2" placeholder="Comments" rows="4"><?php echo $assmntCmnts; ?></textarea>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                    </div>
                    <?php
                } else {
                    ?>

                    <div class="row" style="margin: 0px 0px 10px 0px !important;">
                        <div class="col-md-4" style="padding:0px 1px 0px 1px !important;">
                            <div style="float:left !important;">
                                <button type="button" class="btn btn-primary btn-sm"  onclick="getCreditAssessmentQstn(<?php echo $pgNo; ?>, <?php echo $subPgNo; ?>, <?php echo $vwtyp; ?>, 'FIRST', '<?php echo $vwtypActn; ?>', 0);"><span class="glyphicon glyphicon-fast-backward" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;"></span>FIRST</button> 
                                <button type="button" class="btn btn-primary btn-sm"  onclick="getCreditAssessmentQstn(<?php echo $pgNo; ?>, <?php echo $subPgNo; ?>, <?php echo $vwtyp; ?>, 'PREVIOUS', '<?php echo $vwtypActn; ?>', 0);"><span class="glyphicon glyphicon-backward" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;"></span>PREVIOUS</button>
                            </div>
                        </div>
                        <div class="col-md-4" style="padding:0px 0px 0px 15px !important;">&nbsp;</div>   
                        <div class="col-md-4" style="padding:0px 1px 0px 1px !important;">
                            <div style="float:right !important;">
                                <button type="button" class="btn btn-primary btn-sm" onclick="getCreditAssessmentQstn(<?php echo $pgNo; ?>, <?php echo $subPgNo; ?>, <?php echo $vwtyp; ?>, 'NEXT', '<?php echo $vwtypActn; ?>', 0);"><span class="glyphicon glyphicon-forward" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;"></span>
                                    <?php
                                    if ($currQstnNo == ((int) $qstnTtlNo - 1) && ($currQstnNo != 0) && ($optn != 'FIRST') && ($optn != 'PREVIOUS')) {
                                        echo 'VIEW SUMMARY';
                                    } else if ($optn == 'LAST') {
                                        echo 'VIEW SUMMARY';
                                    } else {
                                        echo 'NEXT';
                                    }
                                    ?>
                                </button>
                                <button type="button" class="btn btn-primary btn-sm" onclick="getCreditAssessmentQstn(<?php echo $pgNo; ?>, <?php echo $subPgNo; ?>, <?php echo $vwtyp; ?>, 'LAST', '<?php echo $vwtypActn; ?>', 0);"><span class="glyphicon glyphicon-fast-forward" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;"></span>
                                    <?php
                                    if ($currQstnNo == ((int) $qstnTtlNo - 1) && ($currQstnNo != 0) && ($optn != 'FIRST') && ($optn != 'PREVIOUS')) {
                                        echo 'VIEW SUMMARY';
                                    } else if ($optn == 'LAST') {
                                        echo 'VIEW SUMMARY';
                                    } else {
                                        echo 'LAST';
                                    }
                                    ?>
                                </button>
                            </div>
                        </div>              
                    </div>                

                    <form class="form-horizontal" id='stdEvntsForm' action='' method='post' accept-charset='UTF-8'>
                        <div class="row"><!-- ROW 1 -->
                            <div class="col-lg-12">
                                <?php
                                $result1 = get_AssessmentRiskFactorProfileNCode($lnprflFctrId, $optn, $loanRqstID, $chkedAssmntID);
                                $riskFactorCode = "";
                                $riskProfile = "";
                                $qstnNoCnta = 0;
                                $qstnTtlCnta = 0;

                                while ($row1 = loc_db_fetch_array($result1)) {
                                    $riskFactorCode = $row1[0];
                                    $riskProfile = $row1[1];
                                }
                                ?>   
                                <div class="row" style="margin: 0px 0px 10px 0px !important;">
                                    <div class="col-md-12" style="padding:0px 1px 0px 1px !important;">
                                        <legend class="basic_person_lg1"  style="width:100% !important; font-size: 18px !important;"><?php echo $riskProfile; ?></legend>
                                    </div>            
                                </div>

                                <fieldset class="basic_person_fs5"><legend class="basic_person_lg" id="assmntQstn" style="color:red"><?php echo $riskFactorCode; ?></legend>                            
                                    <?php
                                    $result = get_AssessmentQuestion($lnprflFctrId, $optn, $loanRqstID, $chkedAssmntID, $currQstnNo);

                                    while ($row = loc_db_fetch_array($result)) {
                                        $qstnNoCnta = $row[7];
                                        $qstnTtlCnta = $row[8];
                                        ?>

                                        <div  class="form-group form-group-sm">
                                            <div class="col-md-3" style="padding:0px 0px 0px 15px !important;">&nbsp;</div>
                                            <div  class="col-md-6">
                                                <?php
                                                $chkdYesNo = "";
                                                if ($row[6] == "YES") {
                                                    $chkdYesNo = "checked=\"\"";
                                                }
                                                ?>
                                                <label class="radio-inline" style="font-size:16px !important;"><input type="radio" name="assmntID" id="assmntID_<?php echo $row[0]; ?>" value="<?php echo $row[0]; ?>" <?php echo $chkdYesNo; ?>><?php echo $row[4]; ?></label>
                                            </div>  
                                            <div class="col-md-3" style="padding:0px 0px 0px 15px !important;">&nbsp;</div>
                                        </div> 


                                        <?php
                                    }
                                    ?>                            
                                </fieldset> 
                            </div>                                
                        </div>                    
                    </form> 

                    <div class="row" style="margin: 0px 0px 10px 0px !important;">
                        <div class="col-md-4" style="padding:0px 1px 0px 1px !important;">
                            <div style="float:left !important;">
                                <button type="button" class="btn btn-primary btn-sm" onclick="getCreditAssessmentQstn(<?php echo $pgNo; ?>, <?php echo $subPgNo; ?>, <?php echo $vwtyp; ?>, 'FIRST', '<?php echo $vwtypActn; ?>', 0);"><span class="glyphicon glyphicon-fast-backward" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;"></span>FIRST</button> 
                                <button type="button" class="btn btn-primary btn-sm" onclick="getCreditAssessmentQstn(<?php echo $pgNo; ?>, <?php echo $subPgNo; ?>, <?php echo $vwtyp; ?>, 'PREVIOUS', '<?php echo $vwtypActn; ?>', 0);"><span class="glyphicon glyphicon-backward" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;"></span>PREVIOUS</button>
                            </div>
                        </div>
                        <div class="col-md-4" style="padding:0px 0px 0px 15px !important;text-align: center !important;"><span id="qstnNoId" style="color:green;font-size: 15px !important;"><?php echo $qstnNoCnta; ?></span>&nbsp;of&nbsp;<span id="qstnTtlNoId" style="color:green;font-size: 15px !important;"><?php echo $qstnTtlCnta; ?></span></div>   
                        <div class="col-md-4" style="padding:0px 1px 0px 1px !important;">
                            <div style="float:right !important;">
                                <button type="button" class="btn btn-primary btn-sm" onclick="getCreditAssessmentQstn(<?php echo $pgNo; ?>, <?php echo $subPgNo; ?>, <?php echo $vwtyp; ?>, 'NEXT', '<?php echo $vwtypActn; ?>', 0);"><span class="glyphicon glyphicon-forward" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;"></span>
                                    <?php
                                    if ($qstnNoCnta == $qstnTtlCnta && ($qstnNoCnta != 0)) {
                                        echo 'VIEW SUMMARY';
                                    } else {
                                        echo 'NEXT';
                                    }
                                    ?>
                                </button>
                                <button type="button" class="btn btn-primary btn-sm" onclick="getCreditAssessmentQstn(<?php echo $pgNo; ?>, <?php echo $subPgNo; ?>, <?php echo $vwtyp; ?>, 'LAST', '<?php echo $vwtypActn; ?>', 0);"><span class="glyphicon glyphicon-fast-forward" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;"></span>
                                    <?php
                                    if ($qstnNoCnta == $qstnTtlCnta && ($qstnNoCnta != 0)) {
                                        echo 'VIEW SUMMARY';
                                    } else {
                                        echo 'LAST';
                                    }
                                    ?>
                                </button>
                            </div>
                        </div> 
                    </div>               

                    <?php
                }
            } else if ($vwtypActn === "VIEW") {
                
            }
        } 
        else if ($vwtyp == "2") {
            $onlyViewMode = isset($_POST['onlyViewMode']) ? cleanInputData($_POST['onlyViewMode']) : '';
            /* APPROVAL SCREEN */
             //echo "Approval Screen";
            if ($vwtypActn == "VIEW") {
                
            } else if ($vwtypActn == "ADD") {
                /* Add */
            } else if ($vwtypActn == "EDIT") {
                /* Edit Only */
                //echo "Hello";
                
                $loanRqstID = isset($_POST['lnRqstID']) ? cleanInputData($_POST['lnRqstID']) : -1;
                $status = isset($_POST['status']) ? cleanInputData($_POST['status']) : '';
                
                $routingID = isset($_POST['routingID']) ? cleanInputData($_POST['routingID']) : -1;
                $inptSlctdRtngs = isset($_POST['inptSlctdRtngs']) ? cleanInputData($_POST['inptSlctdRtngs']) : '';
                $actionToPrfrm  = isset($_POST['actionToPrfrm']) ? cleanInputData($_POST['actionToPrfrm']) : ''; 
                $srcDocID  = isset($_POST['srcDocID']) ? cleanInputData($_POST['srcDocID']) : -1; 
                $srcDocType  = isset($_POST['srcDocType']) ? cleanInputData($_POST['srcDocType']) : '';
                
                $trnsStatus = "Incomplete";
                $sbmtdTrnsHdrID = $loanRqstID;
                $voidedTrnsHdrID = -1;
                $rqstatusColor = "red";
                $ttlColor = "blue";
                $apprvdAmntColor = "blue";
                $isPaidColor = "blue";
                $mkReadOnly = "readonly=\"readonly\"";
                $mkRmrkReadOnly = "";                
                $trnsTtl = 0.00;
                $crncy = "GHS";
                $crncyID = ""; 
                $isDisbursed = "NO";
                $payDate = "";
                $apprvdLoanAmount = 0.0;
                $apprvdLoanTenor = "";
                $apprvdLoanTenorType = "";
                $apprvdInterestRate = 0.00;
                $btnDsplyNone = "";
                
                //$btnVoidTrns = 'style="display:inline-block !important;"';
                //$btnWdwlDsply = 'style="display:none !important;"';
                //$btnFirst = 'style="display:inline-block !important;"';
                
                if($routingID > 0){
                    if ($trnsStatus == "Approved" || $trnsStatus == "Rescheduled" || $trnsStatus == "Topped-Up"){
                            $btnDsplyNone = 'style="display:inline-block !important;"';
                    } else if ($trnsStatus == "Initiated"){
                        $btnDsplyNone = 'style="display:none !important;"';
                    }
                } else {
                    $routingID = getMCFMxRoutingID($sbmtdTrnsHdrID, "Loan Applications"); 
                     $btnDsplyNone = 'style="display:none !important;"';
                }
                
                
            //var_dump($_POST);
                $result = get_LoanRqstDet($loanRqstID);
                while($row = loc_db_fetch_array($result)){
                    $trnsStatus = $row[8];
                    $crncyIsoCode = getGnrlRecNm("mcf.mcf_currencies", "crncy_id", "iso_code", $row[32]);
                    
                    if($trnsStatus == "Initiated"){
                        $mkReadOnly = "";
                    }
                    
                    $rqstdLoanAmount = (float) $row[16];
                    $rqstdLoanTenor = (float)  $row[17];
                    $rqstdLoanTenorType = $row[18];
                    $rqstdInterestRate = (float) $row[25];  
                    
                    $apprvdLoanAmount = (float) $row[27];
                    $apprvdLoanTenor = (float) $row[29];
                    if($apprvdLoanTenor <= 0){
                        $apprvdLoanTenor = 0.00;
                    }
                    $apprvdLoanTenorType = $row[30];
                    $apprvdInterestRate = (float) $row[31];   
                    if($apprvdInterestRate <= 0){
                        $apprvdInterestRate = 0.00;
                    }
                    
                    ?>
                        <div class="col-md-12" style="padding:0px 0px 10px 1px !important;">
                            <div style="padding:0px 1px 0px 0px !important;float:left;">
                                    <button type="button" class="btn btn-default btn-sm" style="height:30px;" id="myVmsTrnsStatusBtn">
                                            <span style="font-weight:bold;">Status: </span><span style="color:<?php echo $rqstatusColor; ?>;font-weight: bold;"><?php echo $trnsStatus; ?></span>
                                    </button>
                                    <?php if($onlyViewMode != "VIEW") { ?>
                                    <button type="button" class="btn btn-default btn-sm" style="height:30px;" onclick="getLoanRqstForm('myFormsModalLg', 'myFormsModalBodyLg', 'myFormsModalTitleLg', 'Edit Loan Request', 15, <?php echo $subPgNo; ?>,0,'EDIT', <?php echo $sbmtdTrnsHdrID; ?>,'indCustTable','indCustTableRow1');" data-toggle="tooltip" title="Reload Transaction">
                                            <img src="cmn_images/refresh.bmp" style="padding-right: 2px; height:17px; width:auto; position: relative; vertical-align: middle;">
                                    </button>
                                    <?php } ?>
                                    <button type="button" class="btn btn-default" style="height:30px;" onclick="getOneMcfDocsForm_Gnrl(<?php echo $loanRqstID; ?>,'<?php echo $trnsType; ?>', 140,'<?php echo $formTitle; ?>');" data-toggle="tooltip" data-placement="bottom" title = "Attached Documents">
                                        <img src="cmn_images/adjunto.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                    </button>
                            </div>
                            <div style="padding:0px 1px 0px 1px !important;float:right;">
                                    <?php if ($trnsStatus == "Initiated") {
                                            ?>                                                                       
                                            <button <?php echo $btnDsplyNone; ?> type="button" class="btn btn-default btn-sm" style="height:30px;" onclick="approveLoanApplication(<?php echo $routingID; ?>,'<?php echo $actionToPrfrm; ?>',<?php echo $srcDocID; ?>);"><img src="cmn_images/Stamp-512.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Approve&nbsp;</button>                                                                                                        
                                            <button <?php echo $btnDsplyNone; ?> type="button" class="btn btn-default btn-sm" style="height:30px;" onclick="checkWkfRqstStatus(<?php echo $routingID; ?>, 'Teller Trns. Authorization Progress History');"><img src="cmn_images/workflow.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Progress&nbsp;</button>                                
                                                    <?php
                                            } else if ($trnsStatus == "Approved" || $trnsStatus == "Rescheduled" || $trnsStatus == "Topped-Up") {
                                                    ?>
                                            <button type="button" class="btn btn-default btn-sm" style="height:30px;" onclick="checkWkfRqstStatus(<?php echo $routingID; ?>, 'Teller Trns. Authorization Progress History');"><img src="cmn_images/workflow.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;" data-toggle="tooltip" title="Authorization Progress History">Progress&nbsp;</button>  
                                            <button <?php echo $btnDsplyNone; ?> type="button" class="btn btn-default btn-sm" style="height:30px;" onclick="saveAccountTrnsRvrs(<?php echo $pgNo; ?>, '<?php echo $subPgNo; ?>', <?php echo $vwtyp; ?>, 'LOAN_PYMNT', 0);"><img src="cmn_images/90.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Void Transaction&nbsp;</button>                               
                                    <?php } ?>
                                    <?php
                                    if ($trnsStatus == "Approved" || $trnsStatus == "Rescheduled" || $trnsStatus == "Topped-Up") {
                                            $reportTitle = "Withdrawal Transaction";
                                            $reportName = "Teller Transaction Receipt";
                                            $rptID = getRptID($reportName);
                                            $prmID1 = getParamIDUseSQLRep("{:invoice_id}", $rptID);
                                            $prmID2 = getParamIDUseSQLRep("{:documentTitle}", $rptID);
                                            $invcID = $sbmtdTrnsHdrID;
                                            $paramRepsNVals = $prmID1 . "~" . $invcID . "|" . $prmID2 . "~" . $reportTitle . "|-190~PDF";
                                            $paramStr = urlencode($paramRepsNVals);
                                            ?>
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
                        <form class="form-horizontal" id='wkpApprvlForm' action='' method='post' accept-charset='UTF-8'>
                            <div class="row"><!-- ROW 1 -->
                                <div class="col-lg-12">
                                    <div class="col-lg-6">
                                        <fieldset class="basic_person_fs5"><legend class="basic_person_lg1">Requested</legend>
                                            <div class="form-group form-group-sm">
                                                <label for="rqstdLoanAmount" class="control-label col-md-4">Amount:</label>
                                                <div  class="col-md-8">
                                                    <div class="input-group">
                                                        <label id="crncyIsoCode" style="font-weight:bold; font-size: 12px !important;" class="btn btn-primary btn-file input-group-addon">
                                                            <?php echo $crncyIsoCode; ?>
                                                        </label>		
                                                        <input class="form-control" readonly="true" style="font-weight:bold; font-size: 12px !important;" id="rqstdLoanAmount" type = "text" min="0" placeholder="Amount" value="<?php echo number_format($rqstdLoanAmount,2); ?>"/>
                                                    </div>
                                                </div>
                                            </div>   
                                            <div class="form-group form-group-sm">
                                                <label for="rqstdLoanTenor" class="control-label col-md-4">Tenure:</label>
                                                <div  class="col-md-8">
                                                    <div class="input-group col-md-12">
                                                        <div  class="col-md-4" style="padding-left:0px !important;">
                                                            <input class="form-control" readonly="true" style="font-weight:bold; font-size: 12px !important;" id="rqstdLoanTenor" type = "number" min="0" placeholder="" value="<?php echo $rqstdLoanTenor; ?>"/>
                                                        </div>
                                                        <div  class="col-md-8" style="padding-left:0px !important; padding-right: 0px !important; ">
                                                            <select class="form-control" disabled="true" style="font-weight:bold;" id="rqstdLoanTenorType" >
                                                                <?php
                                                                $sltdYears = "";
                                                                $sltdMonths = "";
                                                                if ($rqstdLoanTenorType == "Year(s)") {
                                                                    $sltdYears = "selected";
                                                                } else if ($rqstdLoanTenorType == "Month(s)") {
                                                                    $sltdMonths = "selected";
                                                                }
                                                                ?>
                                                                <option value="Year(s)" <?php echo $sltdYears; ?>>Year(s)</option>
                                                                <option value="Month(s)" <?php echo $sltdMonths; ?>>Month(s)</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>                                                            
                                            </div>
                                            <div class="form-group form-group-sm">
                                                <label for="rqstdInterestRate" class="control-label col-md-4">Interest Rate:</label>
                                                <div  class="col-md-8">
                                                    <div class="input-group">
                                                        <input class="form-control" readonly="true" style="font-weight:bold; font-size: 12px !important;" id="rqstdInterestRate" type = "text" min="0" placeholder="Interest Rate" value="<?php echo number_format($rqstdInterestRate,2); ?>"/>
                                                        <label class="btn btn-primary btn-file input-group-addon" style="padding-left: 2px !important; font-weight:bold; font-size: 12px !important;">
                                                            % Per Annum
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </fieldset>
                                    </div>
                                    <div class="col-lg-6">
                                        <fieldset class="basic_person_fs5"><legend class="basic_person_lg1">Approved</legend>
                                            <div class="form-group form-group-sm">
                                                <label for="apprvdLoanAmount" class="control-label col-md-4">Amount:</label>
                                                <div  class="col-md-8">
                                                    <div class="input-group">
                                                        <label id="crncyIsoCode" style="font-weight:bold; font-size: 12px !important;" class="btn btn-primary btn-file input-group-addon">
                                                            <?php echo $crncyIsoCode; ?>
                                                        </label>		
                                                        <input <?php echo $mkReadOnly; ?>  class="form-control rqrdFld" style="font-weight:bold; font-size: 12px !important;" id="apprvdLoanAmount" type = "number" min="0" placeholder="" value="<?php echo $apprvdLoanAmount; ?>"/>
                                                    </div>
                                                </div>
                                            </div>   
                                            <div class="form-group form-group-sm">
                                                <label for="apprvdLoanTenor" class="control-label col-md-4">Tenure:</label>
                                                <div  class="col-md-8">
                                                    <div class="input-group col-md-12">
                                                        <div  class="col-md-4" style="padding-left:0px !important;">
                                                            <input <?php echo $mkReadOnly; ?>  class="form-control rqrdFld" style="font-weight:bold; font-size: 12px !important;" id="apprvdLoanTenor" type = "number" min="0" placeholder="" value="<?php echo $apprvdLoanTenor; ?>"/>
                                                        </div>
                                                        <div  class="col-md-8" style="padding-left:0px !important; padding-right: 0px !important; ">
                                                            <select <?php echo $mkReadOnly; ?>  class="form-control" style="font-weight:bold;" id="apprvdLoanTenorType" >
                                                                <?php
                                                                $sltdYears = "";
                                                                $sltdMonths = "";
                                                                if ($apprvdLoanTenorType == "Year(s)") {
                                                                    $sltdYears = "selected";
                                                                } else if ($apprvdLoanTenorType == "Month(s)") {
                                                                    $sltdMonths = "selected";
                                                                }
                                                                ?>
                                                                <option value="Month(s)" <?php echo $sltdMonths; ?>>Month(s)</option>
                                                                <option value="Year(s)" <?php echo $sltdYears; ?>>Year(s)</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>                                                            
                                            </div>
                                            <div class="form-group form-group-sm">
                                                <label for="apprvdInterestRate" class="control-label col-md-4">Interest Rate:</label>
                                                <div  class="col-md-8">
                                                    <div class="input-group">
                                                        <input <?php echo $mkReadOnly; ?>  class="form-control rqrdFld" style="font-weight:bold; font-size: 12px !important;" id="apprvdInterestRate" type = "number" min="0" placeholder="" value="<?php echo $apprvdInterestRate; ?>"/>
                                                        <label class="btn btn-primary btn-file input-group-addon" style="padding-left: 2px !important; font-weight:bold; font-size: 12px !important;">
                                                            % Per Annum
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </fieldset>
                                    </div>                                    
                                </div>                                
                            </div>
                            <div class="row" style="margin-top: 5px !important;"><!-- ROW 1 -->
                                <div class="col-lg-12">
                                    <legend class="basic_person_lg1">Comments</legend>
                                        <div  class="col-lg-12">
                                            <div class="form-group form-group-sm">
                                                <textarea class="form-control rqrdFld" style="" <?php echo $mkReadOnly; ?> id="apprvlCmnts" cols="2" placeholder="Comments" rows="5"><?php echo $row[33]; ?></textarea>
                                            </div>                                      
                                        </div>
                                </div>
                            </div>
                        </form> 
                    <?php
                }
                
                
                
            }
        } else if ($vwtyp == "3") {
            
        } 
        else if ($vwtyp == "4") {

            $loanPrdtDetArry = array();
            $result = get_LoanPrdtDetsForLoanApplctn($pkID);

            while ($row = loc_db_fetch_array($result)) {

                $crncyIsoCode = getGnrlRecNm("mcf.mcf_currencies", "crncy_id", "iso_code", $row[1]);

                $loanPrdtDetArry = array("loanProductId" => $row[0], "crncyIsoCode" => $crncyIsoCode, "isAccountRqrd" => $row[2],
                    "loanMaxAmount" => $row[3], "loanMinAmount" => $row[4],
                    "maxLoanTenor" => $row[5], "maxLoanTenorType" => $row[6], "guarantorRequired" => $row[7],
                    "guarantorNo" => $row[8], "productType" => $row[9],
                    "custTypeCustgrp" => $row[10], "custTypeCorp" => $row[11], "custTypeInd" => $row[12],
                    "cashCollateralRqrd" => $row[13], "valueFlatCashColt" => $row[14], "valuePrcntCashColt" => $row[15],
                    "prptyCollateralRqrd" => $row[16], "valueFlatPrptyColt" => $row[17], "valuePrcntPrptyColt" => $row[18],
                    "invstmntCollateralRqrd" => $row[19], "valueInvstmntCashColt" => $row[20], "valueInvstmntCashColt" => $row[21],
                    "minLoanTenor" => $row[22], "minLoanTenorType" => $row[23], "baseIntRate" => $row[24], "prdtCompound" => $row[25],
                    "prdtPayBack" => $row[26], "gracePeriodNo" => $row[27], "gracePeriodType" => $row[28], "intRateType" => $row[29]);
            }


            $response = array("loanPrdtDetArry" => $loanPrdtDetArry);

            echo json_encode($response);
            exit();
        } 
        else if ($vwtyp == "5") {
            /* ADD GUARANTOR */
            $rowID = isset($_POST['rowID']) ? cleanInputData($_POST['rowID']) : -1;
            $loanRqstID = isset($_POST['loanRqstID']) ? cleanInputData($_POST['loanRqstID']) : -1;
            $srcType = "";//isset($_POST['srcType']) ? cleanInputData($_POST['srcType']) : '';
            $guarantor = "";
            $guarantorID = -1;
            
            
            
            $result = get_Loan_Guarantor($rowID);
            while($row = loc_db_fetch_array($result)){
                $srcType = $row[2];
                $guarantor = $row[1];
                $guarantorID =  $row[3];
            }
            
            
            ?>
            <form class="form-horizontal" id="acctSignatoryForm" style="padding:5px 20px 5px 20px;">
                <div class="row">
                    <!--Guarantor ID-->
                    <input class="form-control" size="16" type="hidden" id="loanGurntaID" value="<?php echo $rowID; ?>" readonly="">                    
                    <!--Loan Request ID-->
                    <input type="hidden" id="loanRqstID" value="<?php echo $loanRqstID; ?>">
                    <div id="srcTypeDiv" class="form-group form-group-sm" style="display:none;">
                        <label for="srcType" class="control-label col-md-4">Source Type:</label>
                        <div  class="col-md-8">
                            <select class="form-control" id="srcType" >
                                <?php
                                $sltdIndCst = "";
                                $sltdOthrPrsn = "";
                                if ($srcType == "Individual Customers") {
                                    $sltdIndCst = "selected";
                                } else if ($srcType == "Other Persons") {
                                    $sltdOthrPrsn = "selected";
                                }
                                ?>
                                <option value="Individual Customers" <?php echo $sltdIndCst; ?>>Individual Customers</option>
                                <option value="Other Persons" <?php echo $sltdOthrPrsn; ?>>Other Persons</option>
                            </select>
                        </div>
                    </div>                    
                    <div class="form-group form-group-sm">
                        <label for="guarantor" class="control-label col-md-4">Name:</label>
                        <div  class="col-md-8">
                            <div class="input-group">
                                <!--table rowElementID-->
                                <input class="form-control" size="16" type="hidden" id="tblRowElementID" value="" readonly="">
                                <!--custType Individual-->
                                <input type="hidden" id="custTypeIndividual" value="Individual">
                                <!--prsnType Director-->
                                <input type="hidden" id="relation" value="Guarantor">

                                <input type="text" class="form-control" aria-label="..." id="guarantor" value="<?php echo $guarantor; ?>">
                                <input type="hidden" id="guarantorID" value="<?php echo $guarantorID; ?>">
                                <input type="hidden" id="gnrlOrgID" value="<?php echo $orgID; ?>">
                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLoanGuarantors();">
                                    <span class="glyphicon glyphicon-th-list"></span>
                                </label>
                            </div>
                        </div>
                    </div>                  
                </div>
                <div class="row" style="float:right;padding-right: 1px;">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <?php if($vwtypActn != "VIEW") { ?>
                        <button type="button" class="btn btn-primary" onclick="saveLoanGuarantorForm('myFormsModalx', '<?php echo $rowID; ?>');">Save Changes</button>
                    <?php } ?>
                </div>
            </form>
            <?php
        } 
        else if ($vwtyp == "6") {//GET RESCHEDULE LOAN REQUEST DETAILS
            $refLoanrqstID = isset($_POST['refLoanrqstID']) ? cleanInputData($_POST['refLoanrqstID']) : -1;
            $currTenorBalInMonths = "";
            $loanRepaymentType = "";


            $refLoanRqstDetArry = array();
            $result = get_RefLoanRqstDetsForLoanApplctn($refLoanrqstID);

            while ($row = loc_db_fetch_array($result)) {

                $loanRepaymentType = $row[6];

                if ($loanRepaymentType == "daily") {
                    $currTenorBalInMonths = number_format((float) $row[2] * (float) (12 / 365.25), 2);
                } else if ($loanRepaymentType == "weekly") {
                    $currTenorBalInMonths = number_format((float) $row[2] * (float) (12 / 52), 2);
                } else if ($loanRepaymentType == "biweekly") {
                    $currTenorBalInMonths = number_format((float) $row[2] * (float) (12 / 26), 2);
                } else if ($loanRepaymentType == "halfmonth") {
                    $currTenorBalInMonths = (float) $row[2] * (float) (1 / 2);
                } else if ($loanRepaymentType == "month") {
                    $currTenorBalInMonths = (float) $row[2];
                } else if ($loanRepaymentType == "quarter") {
                    $currTenorBalInMonths = (float) $row[2] * (float) 3;
                } else if ($loanRepaymentType == "halfyear") {
                    $currTenorBalInMonths = (float) $row[2] * (float) 6;
                } else {
                    $currTenorBalInMonths = (float) $row[2] * (float) 12;
                }

                $refLoanRqstDetArry = array("loanRqstId" => $row[0], "principalAmountBal" => $row[1], "ttlTenorBal" => $currTenorBalInMonths,
                    "apprvdLoanTenorType" => $row[3], "apprvdInterestRate" => $row[4], "loanRepaymentType" => "Month(s)",
                    "apprvdLoanTenor" => $row[7]);
            }


            $response = array("refLoanRqstDetArry" => $refLoanRqstDetArry);

            echo json_encode($response);
            exit();
        }
        else if ($vwtyp == "7"){//LOAN AMORTIZATION SCHEDULE
            
            //var_dump($_POST);

            $loanAmount = isset($_POST['loanAmount']) ? cleanInputData($_POST['loanAmount']) : 0;
            $loanTenor = isset($_POST['loanTenor']) ? cleanInputData($_POST['loanTenor']) : 0;
            $loanTenorType = isset($_POST['loanTenorType']) ? cleanInputData($_POST['loanTenorType']) : '';
            $interestRate = isset($_POST['interestRate']) ? cleanInputData($_POST['interestRate']) : -1;

            $interestRateType = isset($_POST['intRateType']) ? cleanInputData($_POST['intRateType']) : "";
            $compound = isset($_POST['compound']) ? cleanInputData($_POST['compound']) : -1;
            $payBack = isset($_POST['payBack']) ? cleanInputData($_POST['payBack']) : '';
            $gracePeriodNo = isset($_POST['gracePeriodNo']) ? cleanInputData($_POST['gracePeriodNo']) : -1;
            $gracePeriodType = isset($_POST['gracePeriodType']) ? cleanInputData($_POST['gracePeriodType']) : '';
            $dsbmntDate = isset($_POST['dsbmntDate']) ? cleanInputData($_POST['dsbmntDate']) : '';
            $crdtType = isset($_POST['crdtType']) ? cleanInputData($_POST['crdtType']) : '';
            $crncyIsoCode = isset($_POST['crncyIsoCode']) ? cleanInputData($_POST['crncyIsoCode']) : '';
            
            
            $disbDetArray = getLoanCalcAmortizationSchedule($loanAmount, $loanTenor, $loanTenorType, $payBack, $compound, 
                    $gracePeriodNo, $gracePeriodType, $interestRate, $dsbmntDate, $interestRateType);  
            
            ?>

                <fieldset class="basic_person_fs1"><legend class="basic_person_lg">Results</legend>
                    <div class="form-group form-group-sm">
                        <label for="prdcPayAmount" class="control-label col-md-5">Payment <span id="payBackType" style="font-weight:bold; font-size: 14px !important;color:blue;">Every <?php echo ucfirst($payBack); ?></span>:</label>
                        <div  class="col-md-7">
                            <div class="input-group">
                                <label id="crncyIsoCode2" class="btn btn-primary btn-file input-group-addon"><?php echo $crncyIsoCode; ?>
                                </label>		
                                <input style="font-weight:bold; font-size: 14px !important;color:blue;" class="form-control" id="prdcPayAmount" type = "text" min="0" placeholder="" value="<?php echo number_format($disbDetArray["scheduledPrdcPaymntAmnt"],2); ?>" readonly="true"/>
                                <input style="display:none !important;" class="form-control" id="prdcPayAmountRaw" type = "text" min="0" placeholder="" value="<?php echo $disbDetArray["scheduledPrdcPaymntAmnt"]; ?>" readonly="true"/>
                            </div>
                        </div>
                    </div>                                                                 
                    <div class="form-group form-group-sm">
                        <label for="ttlPayments" class="control-label col-md-5">Total of <span id="ttlLoanTermTimes" style="font-weight:bold; font-size: 14px !important;color:blue;"><?php echo $disbDetArray["ttlLoanTermTimes"]; ?></span> Payments:</label>
                        <div  class="col-md-7">
                            <div class="input-group">
                                <label id="crncyIsoCode3" class="btn btn-primary btn-file input-group-addon"><?php echo $crncyIsoCode; ?>
                                </label>		
                                <input style="font-weight:bold; font-size: 14px !important;color:blue;" class="form-control" id="ttlPayments" type = "text" min="0" placeholder="" value="<?php echo number_format(((float)$loanAmount + (float)$disbDetArray["ttlIntrst"]),2); ?>" readonly="true"/>
                            </div>
                        </div>                                                            
                    </div> 
                    <div class="form-group form-group-sm">
                        <label for="ttlInterest" class="control-label col-md-5">Total Interest:</label>
                        <div  class="col-md-7">
                            <div class="input-group">
                                <label id="crncyIsoCode4" class="btn btn-primary btn-file input-group-addon"><?php echo $crncyIsoCode; ?>
                                </label>		
                                <input style="font-weight:bold; font-size: 14px !important;color:blue;" class="form-control" id="ttlInterest" type = "text" min="0" placeholder="" value="<?php echo number_format($disbDetArray["ttlIntrst"],2); ?>" readonly="true"/>
                            </div>
                        </div>
                    </div>     
                    <div class="form-group form-group-sm">
                        <label for="startDate" class="control-label col-md-5">Scheduled Start Date:</label>
                        <div  class="col-md-7">
                            <input style="font-weight:bold; font-size: 14px !important;color:blue;" class="form-control" id="startDate" type = "text" min="0" placeholder="" value="<?php echo $disbDetArray["repayStartDate"]; ?>" readonly="true"/>
                        </div>
                    </div>     
                    <div class="form-group form-group-sm">
                        <label for="endDate" class="control-label col-md-5">Scheduled End Date:</label>
                        <div  class="col-md-7">
                            <input style="font-weight:bold; font-size: 14px !important;color:blue;" class="form-control" id="endDate" type = "text" min="0" placeholder="" value="<?php echo $disbDetArray["repayEndDate"]; ?>" readonly="true"/>
                        </div>
                    </div>
                    <div style="padding: 0px 1px 0px 1px !important; float: right !important;">
                        <button type="button" class="btn btn-default btn-sm" style="height:30px;" onclick="openATab('#allmodules', 'grp=17&typ=1&pg=4&subPgNo=4.4');"><img src="cmn_images/undo_256.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Reset&nbsp;</button>
                        <button type="button" class="btn btn-default btn-sm" style="height:30px;" onclick="showAmo(<?php echo $loanAmount; ?>,<?php echo $disbDetArray["interestRatePerPeriod"]; ?>,
                            <?php echo $disbDetArray["ttlLoanTermTimes"]; ?>, <?php echo $disbDetArray["scheduledPrdcPaymntAmnt"]; ?>, '<?php echo $payBack; ?>', '<?php echo $disbDetArray["repayStartDate"]; ?>',
                            '<?php echo $disbDetArray["repayEndDate"]; ?>', 'myFormsModalBodyLgz',<?php echo -1; ?>, '<?php echo $interestRateType; ?>', '<?php echo $crdtType; ?>','<?php echo $compound; ?>',-1,'YES');"><img src="cmn_images/kghostview.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">View Schedule&nbsp;</button>
                    </div>
                    <?php
                        $cntent = "<div style=\"display:none !important;\">
                                        <select id=\"trnsNtAllwdDysSlt1\">";
                        $brghtStr = "";
                        $isDynmyc = FALSE;
                        $titleRslt = getLovValues("%", "Both", 0, 100, $brghtStr, getLovID("Transactions not Allowed Days"), $isDynmyc, -1, "", "");
                        while ($titleRow = loc_db_fetch_array($titleRslt)) {
                            $selectedTxt = "";
                            $cntent .= "<option value=\"$titleRow[0]\" $selectedTxt>$titleRow[0]</option>";
                        }
                        $cntent .= "</select>
                                    </div>";

                        $cntent .= "<div style=\"display:none !important;\">
                                            <select id=\"trnsNtAllwdDtsSlt1\">";
                        $brghtStr = "";
                        $isDynmyc = FALSE;
                        $titleRslt = getLovValues("%", "Both", 0, 100, $brghtStr, getLovID("Transactions not Allowed Dates"), $isDynmyc, -1, "", "");
                        while ($titleRow = loc_db_fetch_array($titleRslt)) {
                            $selectedTxt = "";
                            $cntent .= "<option value=\"$titleRow[0]\" $selectedTxt>$titleRow[0]</option>";
                        }
                        $cntent .= "</select>
                                        </div>";

                        echo $cntent;                    
                    ?>
                    
                </fieldset>



            <?php

            //echo json_encode(array("disbDetArray" => $disbDetArray, "finMsg"=> "Schedule Created Successfully"));
            //exit();
        }
        else if ($vwtyp == "8"){
            $cashCollateralAcctID = isset($_POST['cashCollateralAcctID']) ? cleanInputData($_POST['cashCollateralAcctID']) : -1;
            $invstmntCollateralID = isset($_POST['invstmntCollateralID']) ? cleanInputData($_POST['invstmntCollateralID']) : -1;
            $prptyCollateralID = isset($_POST['prptyCollateralID']) ? cleanInputData($_POST['prptyCollateralID']) : -1;
            $refLoanrqstID = isset($_POST['refLoanrqstID']) ? cleanInputData($_POST['refLoanrqstID']) : -1;
            $refLoanLienTtl = 0;
            
            $coltTypeOptn = isset($_POST['coltTypeOptn']) ? cleanInputData($_POST['coltTypeOptn']) : -1;
            $coltValue = 0.00;
            $hasColtLien = "No";
            
            //COLLATERAL VALUE
            
            if($coltTypeOptn == 1){
                //cash collateral
                $hasColtLien = hasAcctActvCollateral($cashCollateralAcctID);
                $coltValue = (float)getAcctCollateralCurrAvlblBal($cashCollateralAcctID);               
            } else if($coltTypeOptn == 2){
                //investment collateral
                $hasColtLien = hasInvstmntOrPrptyLien($invstmntCollateralAcctID, "Investment");
                $coltValue = (float)getInvstmntOrPrptyNetVal($invstmntCollateralAcctID, "Investment");   
                
            } else if($coltTypeOptn == 3){
                //property collateral
                $hasColtLien = hasInvstmntOrPrptyLien($prptyCollateralID, "Property");
                $coltValue = (float)getInvstmntOrPrptyNetVal($prptyCollateralID, "Property");         
            }
            
            if($refLoanrqstID > 0){
                $refLoanLienTtl = (float)getLoanRefLienAmnt($refLoanrqstID, $coltTypeOptn);
                $coltValue = $coltValue + $refLoanLienTtl;
            }
            
            $response = array("hasColtLien" => $hasColtLien, "coltValue" => $coltValue);

            echo json_encode($response);
            exit();
            
        }
        else if ($vwtyp == "9") {
            /*REVERSE APPROVED LOAN*/
            $rowID = isset($_POST['loanRqstID']) ? cleanInputData($_POST['loanRqstID']) : -1;

            ?>
            <form class="form-horizontal" id="rvrsApprvdLoanForm" style="padding:5px 20px 5px 20px;">
                <div class="row">                    
                    <div class="form-group form-group-sm">
                        <div  class="col-md-12">
                            <textarea class="form-control rqrdFld" id="rvrslCmnts" cols="2" placeholder="Reversal Comments" rows="4"><?php echo ""; ?></textarea>
                        </div>
                    </div>                      
                </div>
                <div class="row" style="float:right;padding-right: 1px;">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="submitWithdrawLoanRqstActn(<?php echo $pgNo; ?>, <?php echo $subPgNo; ?>, 1, 'REVERSE');">REVERSE APPROVAL</button>
                </div>
            </form>
            <?php
        }
    } 
    else if ($subPgNo == 4.2) {//LOAN DISBURSEMENTS
        if ($vwtyp == "0") {
            /* BASIC DATA */
            if ($vwtypActn == "EDIT") {
                /* Read Only */
                $trnsStatus = "Incomplete";
                $sbmtdTrnsHdrID = $pkID;
                $voidedTrnsHdrID = -1;
                $rqstatusColor = "red";
                $ttlColor = "blue";
                $mkReadOnly = "";
                $mkRmrkReadOnly = "";                
                $result = get_DisbursmentHdrDet($pkID);
                $trnsTtl = 0.00;
                $crncy = "GHS";
                $crncyID = "";

                while ($row = loc_db_fetch_array($result)) {
                    $trnsStatus = $row[4];
                    $voidedTrnsHdrID = (int) $row[6];
                    $crncy = $row[7];
                    $crncyID = $row[9];
                    $trnsTtl = (float) $row[8];
                    
                    if ($trnsStatus == "Incomplete" || $trnsStatus == "Withdrawn" || $trnsStatus == "Rejected") {
                        $rqstatusColor = "red";
                        if ($voidedTrnsHdrID <= 0) {
                            $mkReadOnly = "";
                            $mkRmrkReadOnly = "";
                        } else {
                            $mkReadOnly = "readonly=\"true\"";
                            $mkRmrkReadOnly = "";
                            //$ttlColor = "red";
                            //$vwOrAdd = "VIEW";
                        }
                    } else if ($trnsStatus != "Authorized" && $trnsStatus != "Disbursed" && $trnsStatus != "Void") {
                        $mkReadOnly = "readonly=\"true\"";
                        $mkRmrkReadOnly = "readonly=\"true\"";
                        $rqstatusColor = "brown";
                        //$vwOrAdd = "VIEW";
                    } else if ($trnsStatus == "Void") {
                        $mkReadOnly = "readonly=\"true\"";
                        $mkRmrkReadOnly = "readonly=\"true\"";
                        $rqstatusColor = "red";
                        //$ttlColor = "red";
                        //$vwOrAdd = "VIEW";
                    } else {
                        $rqstatusColor = "green";
                        $mkReadOnly = "readonly=\"true\"";
                        $mkRmrkReadOnly = "readonly=\"true\"";
                        //$vwOrAdd = "VIEW";
                    }                    

                    $prsnBranch = getGnrlRecNm("org.org_sites_locations", "location_id", "site_desc||' ('||location_code_name||')'", $row[3]);
                    /* Edit */
                    ?>
                    <div class="row" style="margin: 0px 0px 10px 0px !important;" >
                        <input type="hidden" id="lnRqstID" value=""/>
                        <input type="hidden" id="lnprflFctrId" value="-1"/>
                        <input class="form-control" id="addOrEditForm" type = "hidden" placeholder="addOrEditForm" value="Add"/>                    
                    </div>                    
                    <div class="">
                        <div class="row">                  
                            <div class="col-md-12">
                                <div class="custDiv" style="border:none !important; padding-top:0px !important;"> 
                                    <div class="tab-content">
                                        <div id="prflCMHomeEDT" class="tab-pane fadein active" style="border:none !important;"> 
                                            <div class="col-md-12" style="padding:0px 0px 10px 1px !important;">
                                                <div style="padding:0px 1px 0px 15px !important;float:left;">
                                                        <button type="button" class="btn btn-default btn-sm" style="height:30px;" id="myVmsTrnsStatusBtn">
                                                                <span style="font-weight:bold;">Status: </span><span style="color:<?php echo $rqstatusColor; ?>;font-weight: bold;"><?php echo $trnsStatus; ?></span>
                                                        </button>
                                                        <button type="button" class="btn btn-default btn-sm" style="height:30px;" onclick="getDisbursementForm('myFormsModalLg', 'myFormsModalBodyLg', 'myFormsModalTitleLg', 'Edit Disbursement', 15, <?php echo $subPgNo; ?>,0,'EDIT', <?php echo $sbmtdTrnsHdrID; ?>,'indCustTable','indCustTableRow1');" data-toggle="tooltip" title="Reload Transaction">
                                                                <img src="cmn_images/refresh.bmp" style="padding-right: 2px; height:17px; width:auto; position: relative; vertical-align: middle;">
                                                        </button>
                                                        <button type="button" class="btn btn-default btn-sm" style="height:30px;" id="myVmsTrnsTtlBtn">
                                                                <span style="font-weight:bold;">Total: </span><span style="color:<?php echo $ttlColor; ?>;font-weight: bold;"><?php echo $crncy." ".number_format($trnsTtl,2); ?></span>
                                                        </button>
                                                </div>
                                                <div style="padding:0px 1px 0px 1px !important;float:right;">
                                                        <?php if (($trnsStatus == "Incomplete" || $trnsStatus == "Withdrawn" || $trnsStatus == "Rejected") && (test_prmssns($dfltPrvldgs[148], $mdlNm) === true) && canPrsnSeeDsbmntBranchDocs($prsnID, $pkID)) { ?>                                                    
                                                                <button type="button" class="btn btn-default btn-sm" style="height:30px;" onclick="saveDisbursementHdr(<?php echo $pgNo; ?>, <?php echo $subPgNo; ?>, <?php echo $vwtyp; ?>);"><img src="cmn_images/FloppyDisk.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Save&nbsp;</button> 
                                                                <?php 
                                                                if((float)$trnsTtl > 0){
                                                                ?>    
                                                                <button type="button" class="btn btn-default btn-sm" style="height:30px;" onclick="disburseApprovedLoans(<?php echo $pgNo; ?>, <?php echo $subPgNo; ?>, 0);"><img src="cmn_images/pay.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Disburse&nbsp;</button> 
                                                                 <?php   
                                                                } 
                                                            } else if (($trnsStatus == "Disbursed" || $trnsStatus == "Void") && (test_prmssns($dfltPrvldgs[150], $mdlNm) === true) && canPrsnSeeDsbmntBranchDocs($prsnID, $pkID)) {
                                                            $reportTitle = "Withdrawal Transaction";
                                                            $reportName = "Teller Transaction Receipt";
                                                            $rptID = getRptID($reportName);
                                                            $prmID1 = getParamIDUseSQLRep("{:invoice_id}", $rptID);
                                                            $prmID2 = getParamIDUseSQLRep("{:documentTitle}", $rptID);
                                                            $invcID = $sbmtdTrnsHdrID;
                                                            $paramRepsNVals = $prmID1 . "~" . $invcID . "|" . $prmID2 . "~" . $reportTitle . "|-190~PDF";
                                                            $paramStr = urlencode($paramRepsNVals);
                                                            ?>
                                                            <button type="button" class="btn btn-default btn-sm" style="height:30px; display:none !important;" title="Get Voucher on Thermal Receipt Paper" onclick="getSilentRptsRnSts(<?php echo $rptID; ?>, - 1, '<?php echo $paramStr; ?>');">
                                                                    <img src="cmn_images/printer-icon.png" style="left: 0.5%; padding-right: 5px; height:17px; width:17px; position: relative; vertical-align: middle;">
                                                                    POS
                                                            </button> 
                                                            <button type="button" class="btn btn-default btn-sm" style="height:30px;  display:none !important;" title="Get Voucher on A4" onclick="getSilentRptsRnSts(<?php echo $rptID; ?>, - 1, '<?php echo $paramStr; ?>');">
                                                                    <img src="cmn_images/printer-icon.png" style="left: 0.5%; padding-right: 5px; height:17px; width:17px; position: relative; vertical-align: middle;">
                                                                    A4
                                                            </button>    
                                                                    <?php if ($voidedTrnsHdrID <= 0 && $trnsStatus == "Disbursed") { ?>
                                                                    <button type="button" class="btn btn-default btn-sm" style="height:30px;" onclick="disburseApprovedLoans(<?php echo $pgNo; ?>, <?php echo $subPgNo; ?>, 1);">
                                                                        <img src="cmn_images/90.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">
                                                                        Void Disbursement&nbsp;
                                                                    </button>                                            
                                                                    <?php
                                                            }
                                                    }
                                                        ?>
                                                </div>
                                            </div>                                                                                       
                                            <form class="form-horizontal" id="loanDisbursementForm">
                                                <div class="row"><!-- ROW 1 -->
                                                    <div class="col-lg-12">
                                                        <fieldset class="basic_person_fs5"><legend class="basic_person_lg">Disbursement Header</legend>
                                                            <div class="col-lg-4">
                                                                <input class="form-control" id="disbmntHdrID" type = "hidden" placeholder="Disbursement Header ID" value="<?php echo $row[0]; ?>"/>                                                        
                                                                <div class="form-group form-group-sm">
                                                                    <label for="batchNo" class="control-label col-md-4">Batch No:</label>
                                                                    <div class="col-md-8">
                                                                        <input class="form-control" id="batchNo" type = "text" placeholder="" value="<?php echo $row[1]; ?>" readonly/>                                                                                                                                            
                                                                    </div>
                                                                </div>
                                                                <div class="form-group form-group-sm">
                                                                    <label for="disbmntDate" class="control-label col-md-4">Date:</label>
                                                                    <div class="col-md-8">
                                                                        <input class="form-control" id="disbmntDate" type = "text" placeholder="" value="<?php echo $row[2]; ?>" readonly/>                                                                                                                                            
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-4">
                                                                <div class="form-group form-group-sm">
                                                                    <!--<label for="description" class="control-label col-md-4" >Description:</label>-->
                                                                    <div  class="col-md-12">
                                                                        <textarea class="form-control rqrdFld" id="description" cols="2" placeholder="Description" rows="3" <?php echo $mkReadOnly; ?> ><?php echo $row[5]; ?></textarea>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-4">
                                                                <div class="form-group form-group-sm">
                                                                    <label for="bnkBranch" class="control-label col-md-4">Branch:</label>
                                                                    <div  class="col-md-8">
                                                                        <div class="input-group" style="width:100% !important;">
                                                                            <input type="text" class="form-control" aria-label="..." id="bnkBranch" value="<?php echo $prsnBranch; ?>" readonly>
                                                                            <input type="hidden" id="bnkBranchID" value="<?php echo $row[3]; ?>"> 
                                                                            <input type="hidden" id="gnrlOrgID" value="<?php echo $orgID; ?>">
                                                                            <label style="display:none !important;" class="btn btn-primary btn-file input-group-addon" onclick="onDsbmntBnkBranchChange();">
                                                                                <span class="glyphicon glyphicon-th-list"></span>
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group form-group-sm">
                                                                    <label for="crncy" class="control-label col-md-4">Currency:</label>
                                                                    <div  class="col-md-8">
                                                                        <div class="input-group">
                                                                            <input type="text" class="form-control" aria-label="..." id="crncy" value="<?php echo $crncy; ?>" readonly>
                                                                            <input type="hidden" id="crncyID" value="<?php echo $crncyID; ?>"> 
                                                                            <input type="hidden" id="gnrlOrgID" value="<?php echo $orgID; ?>">
                                                                            <label class="btn btn-primary btn-file input-group-addon" onclick="onDsbmntCrncyChange()">
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
                                                        <fieldset class="basic_person_fs1" style="margin-top:5px !important;">
                                                            <legend class="basic_person_lg">Disbursement Details</legend>
                                                            <div class="row"><!-- ROW 2 -->
                                                                <div class="col-lg-12">
                                                                    <div  class="col-md-12">
                                                                        <div class="row"><!-- ROW 3 -->
                                                                            <div class="col-lg-12">
                                                                                <div style="float:left; margin-bottom: 5px !important;">
                                                                                <?php 
                                                                                    if($row[4] == "Incomplete" || $row[4] == "Withdrawn" || $row[4] == "Rejected"){
                                                                                ?>
                                                                                <button type="button" class="btn btn-default btn-sm" onclick="getAddLoanLinesForm('myFormsModaly', 'myFormsModalyBody', 'myFormsModalyTitle', 'acctSignatoryForm', '', 'Auto-Loan Approved Loans', 15, <?php echo $subPgNo; ?>, 6, '', -1);"><img src="cmn_images/Refresh1.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">AUTO-LOAD</button>
                                                                                <button type="button" class="btn btn-default btn-sm" onclick="getAddLoanLinesForm('myFormsModaly', 'myFormsModalyBody', 'myFormsModalyTitle', 'acctSignatoryForm', '', 'Add Loan Request', 15, <?php echo $subPgNo; ?>, 5, '', - 1);"><img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">ADD LOAN</button>                                                                            
                                                                                <?php 
                                                                                    }
                                                                                ?>                                                                                
                                                                                </div>                                           
                                                                            </div>
                                                                        </div>
                                                                        <table id="disbmntDetTblAdd" class="table table-striped table-bordered table-responsive" cellspacing="0" width="100%" style="width:100%;">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th>...</th>
                                                                                    <th>No.</th>
                                                                                    <th>Customer</th>
                                                                                    <th>Customer<br>Type</th>
                                                                                    <th>Request No.</th>
                                                                                    <th>Approved Amount</th>
                                                                                    <th>Loan Tenor</th>                                                                                
                                                                                    <th>Interest<br>Rate (%)</th>
                                                                                    <th>Loan<br>Type</th>                                                                              
                                                                                    <th>...</th>
                                                                                    <th>...</th>
                                                                                    <th style="display:none;">Loan Request ID</th>
                                                                                    <th style="display:none;">Charge Interest</th>
                                                                                    <th style="display:none;">Tenor</th>
                                                                                    <th style="display:none;">Tenor Type</th>
                                                                                    <th style="display:none;">Repayment Type</th>
                                                                                    <th style="display:none;">Compound Period Type</th>
                                                                                    <th style="display:none;">Grace Period</th>
                                                                                    <th style="display:none;">Grace Period Type</th>
                                                                                    <th style="display:none;">Disbursement Detail ID</th>

                                                                                    <th style="display:none;">Interest Rate Per Period</th>
                                                                                    <th style="display:none;">Total Loan Term Times</th>
                                                                                    <th style="display:none;">Payment Per Period</th>
                                                                                    <th style="display:none;">Interest Rate Type</th>
                                                                                    <th>Schedule<br>Saved?</th>   
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody id="disbmntDetTblTbody">
                                                                                <?php
                                                                                $cnta = 0;
                                                                                $result2 = get_DisbursementDetLoans($row[0]);
                                                                                while ($row2 = loc_db_fetch_array($result2)) {
                                                                                    $cnta = $cnta + 1;
                                                                                    $approvedAmount = $row2[4];
                                                                                    $isSchdlSvdFntClr = "red";
                                                                                    $isSchdlSvd = $row2[37];
                                                                                    if($isSchdlSvd === "YES"){
                                                                                        $isSchdlSvdFntClr = "green";
                                                                                    } else if($row2[23] === "Overdraft"){
                                                                                        $isSchdlSvdFntClr = "green";
                                                                                        $isSchdlSvd = "N/A";
                                                                                    }

                                                                                    $ttlLoanTermTimes = calculate_TtlLoanTermTimes($row2[10], $row2[11], $row2[12]);

                                                                                    if ($row2[22] === "Top-Up Request") {
                                                                                        $result4 = get_RefLoanRqstDetsForLoanApplctn($row2[24]);
                                                                                        while ($row4 = loc_db_fetch_array($result4)) {
                                                                                            $currPrincipalAmountBal = (float) $row4[1];
                                                                                            $approvedAmount = (float) $approvedAmount + $currPrincipalAmountBal;
                                                                                        }
                                                                                    }
                                                                                    ?>

                                                                                    <tr id="disbmntDetTblAddRow_<?php echo $row2[16]; ?>">
                                                                                        <td class="lovtd"><button type="button" title="View Loan Request" class="btn btn-default btn-sm" onclick="getLoanRqstForm('myFormsModalLgZ', 'myFormsModalLgZBody', 'myFormsModalLgZTitle', 'View Loan Request', 15, 4.1,0,'VIEW', <?php echo $row2[8]; ?>,'indCustTable','indCustTableRow1');" style="padding:2px !important;">
                                                                                                <img src="cmn_images/kghostview.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                                            </button></td>
                                                                                        <td class="lovtd"><?php echo $cnta; ?></td>
                                                                                        <td class="lovtd"><?php echo $row2[1]; ?></td>
                                                                                        <td class="lovtd"><?php echo $row2[2]; ?></td>
                                                                                        <td class="lovtd"><?php echo $row2[3]; ?></td>
                                                                                        <td class="lovtd" style="text-align:right; color:blue !important;font-weight: bold; font-size: 14px !important;"><?php echo number_format($row2[4],2); ?></td>
                                                                                        <td class="lovtd"><?php echo $row2[5]; ?></td>
                                                                                        <td class="lovtd"><?php echo number_format($row2[6],2); ?></td>
                                                                                        <td class="lovtd"><?php echo $row2[23]; ?></td>
                                                                                        <td class="lovtd">
                                                                                            <?php
                                                                                            if (!($row[4] == "Incomplete" || $row[4] == "Rejected" || $row[4] == "Withdrawn")) {
                                                                                                ?>
                                                                                                <button type="button" title="View Schedule" class="btn btn-default btn-sm" onclick="showAmo(<?php echo $approvedAmount; ?>,<?php echo $row2[17]; ?>,
                                                                                                <?php echo $ttlLoanTermTimes; ?>, <?php echo $row2[18]; ?>, '<?php echo $row2[12]; ?>', '<?php echo $row2[19]; ?>',
                                                                                                        '<?php echo $row2[20]; ?>', 'myFormsModalBodyLgz',<?php echo $row2[16]; ?>, '<?php echo $row2[21]; ?>', '<?php echo $row2[23]; ?>','<?php echo $row2[13]; ?>',<?php echo $row2[33]; ?>, '<?php echo $isSchdlSvd; ?>');" style="padding:2px !important;">
                                                                                                    <img src="cmn_images/kghostview.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                                                </button>
                                                                                             <?php
                                                                                            } else {
                                                                                                echo "...";
                                                                                            }
                                                                                            ?>
                                                                                        </td>
                                                                                        <td class="lovtd">
                                                                                            <?php
                                                                                            if ($row[4] == "Incomplete" || $row[4] == "Rejected" || $row[4] == "Withdrawn") {
                                                                                                ?>
                                                                                                <button type="button" title="Clear Loan Request" class="btn btn-default btn-sm" onclick="deleteLoanDisbursementDet(<?php echo $row2[16]; ?>, <?php echo $sbmtdTrnsHdrID; ?>);" style="padding:2px !important;">
                                                                                                    <img src="cmn_images/delete_img.gif" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                                                </button>
                                                                                                <?php
                                                                                            } else {
                                                                                                echo "...";
                                                                                            }
                                                                                            ?>
                                                                                        </td>
                                                                                        <td style="display:none;"><?php echo $row2[8]; ?></td>
                                                                                        <td style="display:none;"><?php echo $row2[9]; ?></td>
                                                                                        <td style="display:none;"><?php echo $row2[10]; ?></td>
                                                                                        <td style="display:none;"><?php echo $row2[11]; ?></td>
                                                                                        <td style="display:none;"><?php echo $row2[12]; ?></td>
                                                                                        <td style="display:none;"><?php echo $row2[13]; ?></td>
                                                                                        <td style="display:none;"><?php echo $row2[14]; ?></td>
                                                                                        <td style="display:none;"><?php echo $row2[15]; ?></td>
                                                                                        <td style="display:none;"><?php echo $row2[16]; ?></td>
                                                                                        <td style="display:none;"><?php echo $row2[17]; ?></td>
                                                                                        <td style="display:none;"><?php echo $ttlLoanTermTimes; ?></td>
                                                                                        <td style="display:none;"><?php echo $row2[18]; ?></td>
                                                                                        <td style="display:none;"><?php echo $row2[21]; ?></td>
                                                                                        <td class="lovtd" style="color:<?php echo $isSchdlSvdFntClr;?> !important;font-weight: bold; font-size: 14px !important;"><?php echo $isSchdlSvd; ?></td>
                                                                                    </tr>                                                                                    
                                                                                    <?php
                                                                                }

                                                                                /* $disbDetArray[$i] = array('loanRqstId' => $row[0], "customer" => $row[1], "custType" => $row[2],
                                                                                  "trnsctnId" => $row[3], "approvedAmount" => $row[4],
                                                                                  "tenorDisp" => $row[5], "interestRate" => $row[6], "applicationDate" => $row[7],
                                                                                  "loanRqstId" => $row[8], "chargeInterest" => $row[9],
                                                                                  "loanTenor" => $row[10], "loanTenorType" => $row[11], "loanRepaymentType" => $row[12],
                                                                                  "compoundingPeriod" => $row[13], "gracePeriodNo" => $row[14],
                                                                                  "gracePeriodType" => $row[15], "disbmntDetId" => $row[16],
                                                                                  "interestRatePerPeriod" => $row[17], "ttlLoanTermTimes" => $ttlLoanTermTimes,
                                                                                  "scheduledPrdcPaymntAmnt" => $row[18], "repayStartDate" => $row[19],
                                                                                  "repayEndDate" => $row[20], "interestRateType" => $row[21]); */
                                                                                ?>
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </fieldset>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        <div id = "prflCMDataEDT" class = "tab-pane fade" style = "border:none !important;"></div>
                                        <div id = "prflCMAttchmntEDT" class = "tab-pane fade" style = "border:none !important;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                }
                
                $cntent = "<div style=\"display:none !important;\">
                                <select id=\"trnsNtAllwdDysSlt1\">";
                $brghtStr = "";
                $isDynmyc = FALSE;
                $titleRslt = getLovValues("%", "Both", 0, 100, $brghtStr, getLovID("Transactions not Allowed Days"), $isDynmyc, -1, "", "");
                while ($titleRow = loc_db_fetch_array($titleRslt)) {
                    $selectedTxt = "";
                    $cntent .= "<option value=\"$titleRow[0]\" $selectedTxt>$titleRow[0]</option>";
                }
                $cntent .= "</select>
                            </div>";

                $cntent .= "<div style=\"display:none !important;\">
                                    <select id=\"trnsNtAllwdDtsSlt1\">";
                $brghtStr = "";
                $isDynmyc = FALSE;
                $titleRslt = getLovValues("%", "Both", 0, 100, $brghtStr, getLovID("Transactions not Allowed Dates"), $isDynmyc, -1, "", "");
                while ($titleRow = loc_db_fetch_array($titleRslt)) {
                    $selectedTxt = "";
                    $cntent .= "<option value=\"$titleRow[0]\" $selectedTxt>$titleRow[0]</option>";
                }
                $cntent .= "</select>
                                </div>";

                echo $cntent;                
                
            } 
            else if ($vwtypActn === "ADD") {
                /* Add */
                $trnsStatus = "Incomplete";
                $sbmtdTrnsHdrID = $pkID;
                $voidedTrnsHdrID = -1;
                $rqstatusColor = "red";
                $ttlColor = "blue";
                $mkReadOnly = "";
                $mkRmrkReadOnly = "";                
                //$result = get_DisbursmentHdrDet($pkID);
                $trnsTtl = 0.00;
                $crncy = "GHS";
                $crncyID = getGnrlRecID("mcf.mcf_currencies", "iso_code", "crncy_id", $crncy, $orgID);                
                ?>
                <div class="row" style="margin: 0px 0px 10px 0px !important;" >
                    <input type="hidden" id="lnRqstID" value=""/>
                    <input type="hidden" id="lnprflFctrId" value="-1"/>
                    <input class="form-control" id="addOrEditForm" type = "hidden" placeholder="addOrEditForm" value="Add"/>                    
                </div>                    
                <div class="">
                    <div class="row">                  
                        <div class="col-md-12">
                            <div class="custDiv" style="border:none !important; padding-top:0px !important;"> 
                                <div class="tab-content">
                                    <div id="prflCMHomeEDT" class="tab-pane fadein active" style="border:none !important;">  
                                            <div class="col-md-12" style="padding:0px 0px 10px 1px !important;">
                                                <div style="padding:0px 1px 0px 15px !important;float:left;">
                                                        <button type="button" class="btn btn-default btn-sm" style="height:30px;" id="myVmsTrnsStatusBtn">
                                                                <span style="font-weight:bold;">Status: </span><span style="color:<?php echo $rqstatusColor; ?>;font-weight: bold;"><?php echo $trnsStatus; ?></span>
                                                        </button>
                                                        <button type="button" class="btn btn-default btn-sm" style="height:30px;" id="myVmsTrnsTtlBtn">
                                                                <span style="font-weight:bold;">Total: </span><span style="color:<?php echo $ttlColor; ?>;font-weight: bold;"><?php echo $crncy." ".number_format($trnsTtl,2); ?></span>
                                                        </button>
                                                </div>
                                                <div style="padding:0px 1px 0px 1px !important;float:right;">
                                                        <?php if ($trnsStatus == "Incomplete" || $trnsStatus == "Withdrawn" || $trnsStatus == "Rejected") { ?>                                                    
                                                                <button type="button" class="btn btn-default btn-sm" style="height:30px;" onclick="saveDisbursementHdr(<?php echo $pgNo; ?>, <?php echo $subPgNo; ?>, <?php echo $vwtyp; ?>);"><img src="cmn_images/FloppyDisk.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Save&nbsp;</button> 
                                                        <?php } else if ($trnsStatus == "Disbursed" || $trnsStatus == "Void") {
                                                                $reportTitle = "Withdrawal Transaction";
                                                                $reportName = "Teller Transaction Receipt";
                                                                $rptID = getRptID($reportName);
                                                                $prmID1 = getParamIDUseSQLRep("{:invoice_id}", $rptID);
                                                                $prmID2 = getParamIDUseSQLRep("{:documentTitle}", $rptID);
                                                                $invcID = $sbmtdTrnsHdrID;
                                                                $paramRepsNVals = $prmID1 . "~" . $invcID . "|" . $prmID2 . "~" . $reportTitle . "|-190~PDF";
                                                                $paramStr = urlencode($paramRepsNVals);
                                                                ?>
                                                                <button type="button" class="btn btn-default btn-sm" style="height:30px;" title="Get Voucher on Thermal Receipt Paper" onclick="getSilentRptsRnSts(<?php echo $rptID; ?>, - 1, '<?php echo $paramStr; ?>');">
                                                                        <img src="cmn_images/printer-icon.png" style="left: 0.5%; padding-right: 5px; height:17px; width:17px; position: relative; vertical-align: middle;">
                                                                        POS
                                                                </button> 
                                                                <button type="button" class="btn btn-default btn-sm" style="height:30px;" title="Get Voucher on A4" onclick="getSilentRptsRnSts(<?php echo $rptID; ?>, - 1, '<?php echo $paramStr; ?>');">
                                                                        <img src="cmn_images/printer-icon.png" style="left: 0.5%; padding-right: 5px; height:17px; width:17px; position: relative; vertical-align: middle;">
                                                                        A4
                                                                </button>    
                                                                        <?php if ($voidedTrnsHdrID <= 0 || $trnsStatus == "Disbursed") { ?>
                                                                        <button type="button" class="btn btn-default btn-sm" style="height:30px;" onclick="disburseApprovedLoans(<?php echo $pgNo; ?>, <?php echo $subPgNo; ?>, 1);"><img src="cmn_images/90.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Void Disbursement&nbsp;</button>                                            
                                                                        <?php
                                                                }
                                                        }
                                                        ?>
                                                </div>
                                            </div>                                          
                                        <form class="form-horizontal" id="loanDisbursementForm">
                                            <div class="row"><!-- ROW 1 -->
                                                <div class="col-lg-12">
                                                    <fieldset class="basic_person_fs5"><legend class="basic_person_lg">Disbursement Header</legend>
                                                        <div class="col-lg-4">
                                                            <input class="form-control" id="disbmntHdrID" type = "hidden" placeholder="Disbursement Header ID" value=""/>                                                        
                                                            <div class="form-group form-group-sm">
                                                                <label for="batchNo" class="control-label col-md-4">Batch No:</label>
                                                                <div class="col-md-8">
                                                                    <input class="form-control" id="batchNo" type = "text" placeholder="" value="" readonly/>                                                                                                                                            
                                                                </div>
                                                            </div>
                                                            <div class="form-group form-group-sm">
                                                                <label for="disbmntDate" class="control-label col-md-4">Date:</label>
                                                                <div class="col-md-8">
                                                                    <input class="form-control" id="disbmntDate" type = "text" placeholder="" value="" readonly/>                                                                                                                                            
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4">
                                                            <div class="form-group form-group-sm">
                                                                <!--<label for="description" class="control-label col-md-3">Description:</label>-->
                                                                <div  class="col-md-12">
                                                                    <textarea class="form-control rqrdFld" id="description" cols="2" placeholder="Description" rows="3"></textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4">
                                                            <div class="form-group form-group-sm">
                                                                <label for="bnkBranch" class="control-label col-md-4">Branch:</label>
                                                                <div  class="col-md-8">
                                                                    <div class="input-group" style="width:100% !important;">
                                                                        <input type="text" class="form-control" aria-label="..." id="bnkBranch" value="<?php echo $prsnBranch; ?>" readonly>
                                                                        <input type="hidden" id="bnkBranchID" value="<?php echo $prsnBranchID; ?>"> 
                                                                        <input type="hidden" id="gnrlOrgID" value="<?php echo $orgID; ?>">
                                                                        <label style="display:none !important;" class="btn btn-primary btn-file input-group-addon" onclick="onDsbmntBnkBranchChange();">
                                                                            <span class="glyphicon glyphicon-th-list"></span>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="form-group form-group-sm">
                                                                <label for="crncy" class="control-label col-md-4">Currency:</label>
                                                                <div  class="col-md-8">
                                                                    <div class="input-group">
                                                                        <input type="text" class="form-control" aria-label="..." id="crncy" value="<?php echo $crncy; ?>" readonly>
                                                                        <input type="hidden" id="crncyID" value="<?php echo $crncyID; ?>"> 
                                                                        <input type="hidden" id="gnrlOrgID" value="<?php echo $orgID; ?>">
                                                                        <label class="btn btn-primary btn-file input-group-addon" onclick="onDsbmntCrncyChange();">
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
                                                    <fieldset class="basic_person_fs1" style="margin-top:5px !important;"><legend class="basic_person_lg">Disbursement Details</legend>
                                                        <div class="row"><!-- ROW 2 -->
                                                            <div class="col-lg-12">
                                                                <div  class="col-md-12">
                                                                    <div class="row"><!-- ROW 3 -->
                                                                        <div class="col-lg-12">
                                                                            <div style="float:left; margin-bottom: 5px !important;">
                                                                            <?php 
                                                                                if($trnsStatus == "Incomplete" || $trnsStatus == "Withdrawn" || $trnsStatus == "Rejected"){
                                                                            ?>
                                                                            <button type="button" class="btn btn-default btn-sm" onclick="getAddLoanLinesForm('myFormsModaly', 'myFormsModalyBody', 'myFormsModalyTitle', 'acctSignatoryForm', '', 'Auto-Loan Approved Loans', 15, <?php echo $subPgNo; ?>, 6, '', -1);"><img src="cmn_images/Refresh1.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">AUTO-LOAD</button>
                                                                            <button type="button" class="btn btn-default btn-sm" onclick="getAddLoanLinesForm('myFormsModaly', 'myFormsModalyBody', 'myFormsModalyTitle', 'acctSignatoryForm', '', 'Add Loan Request', 15, <?php echo $subPgNo; ?>, 5, '', - 1);"><img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">ADD LOAN</button>                                                                            
                                                                            <?php 
                                                                                }
                                                                            ?>                                                                                
                                                                            </div>                                           
                                                                        </div>
                                                                    </div>
                                                                    <table id="disbmntDetTblAdd" class="table table-striped table-bordered table-responsive" cellspacing="0" width="100%" style="width:100%;">
                                                                        <thead>
                                                                            <tr>
                                                                                <th>...</th>
                                                                                <th>No.</th>
                                                                                <th>Customer</th>
                                                                                <th>Customer<br>Type</th>
                                                                                <th>Request No.</th>
                                                                                <th>Approved Amount</th>
                                                                                <th>Loan Tenor</th>                                                                                
                                                                                <th>Interest<br>Rate (%)</th>
                                                                                <th>Loan<br>Type</th>                                                                              
                                                                                <th>...</th>
                                                                                <th>...</th>
                                                                                <th style="display:none;">Loan Request ID</th>
                                                                                <th style="display:none;">Charge Interest</th>
                                                                                <th style="display:none;">Tenor</th>
                                                                                <th style="display:none;">Tenor Type</th>
                                                                                <th style="display:none;">Repayment Type</th>
                                                                                <th style="display:none;">Compound Period Type</th>
                                                                                <th style="display:none;">Grace Period</th>
                                                                                <th style="display:none;">Grace Period Type</th>
                                                                                <th style="display:none;">Disbursement Detail ID</th>

                                                                                <th style="display:none;">Interest Rate Per Period</th>
                                                                                <th style="display:none;">Total Loan Term Times</th>
                                                                                <th style="display:none;">Payment Per Period</th>
                                                                                <th style="display:none;">Interest Rate Type</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody id="disbmntDetTblTbody">
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div> 
                                                    </fieldset>
                                                </div>
                                            </div>                                            
                                        </form>  
                                    </div>
                                    <div id="prflCMDataEDT" class="tab-pane fade" style="border:none !important;"></div>
                                    <div id="prflCMAttchmntEDT" class="tab-pane fade" style="border:none !important;"></div>      
                                </div>                        
                            </div>                         
                        </div>                
                    </div>          
                </div>
                <?php
            } 
            else if ($vwtypActn == "VIEW") {
                /* Read Only */
                $trnsStatus = "Incomplete";
                $sbmtdTrnsHdrID = $pkID;
                $voidedTrnsHdrID = -1;
                $rqstatusColor = "red";
                $ttlColor = "blue";
                $mkReadOnly = "";
                $mkRmrkReadOnly = "";                
                $result = get_DisbursmentHdrDet($pkID);
                $trnsTtl = 0.00;
                $crncy = "GHS";
                $crncyID = "";
                $mkReadOnly = "readonly=\"true\"";
                $dsplyBlkNone = "style='display:none;'";

                while ($row = loc_db_fetch_array($result)) {
                    $trnsStatus = $row[4];
                    $voidedTrnsHdrID = (int) $row[6];
                    $crncy = $row[7];
                    $crncyID = $row[9];
                    $trnsTtl = (float) $row[8];
                    
                    if ($trnsStatus == "Incomplete" || $trnsStatus == "Withdrawn" || $trnsStatus == "Rejected") {
                        $rqstatusColor = "red";
                    } else if ($trnsStatus != "Authorized" && $trnsStatus != "Disbursed" && $trnsStatus != "Void") {
                        $rqstatusColor = "brown";
                        //$vwOrAdd = "VIEW";
                    } else if ($trnsStatus == "Void") {
                        $rqstatusColor = "red";
                    } else {
                        $rqstatusColor = "green";
                    }                    

                    $prsnBranch = getGnrlRecNm("org.org_sites_locations", "location_id", "site_desc||' ('||location_code_name||')'", $row[3]);
                    /* Edit */
                    ?>
                    <div class="row" style="margin: 0px 0px 10px 0px !important;" >
                        <input type="hidden" id="lnRqstID" value=""/>
                        <input type="hidden" id="lnprflFctrId" value="-1"/>
                        <input class="form-control" id="addOrEditForm" type = "hidden" placeholder="addOrEditForm" value="Add"/>                    
                    </div>                    
                    <div class="">
                        <div class="row">                  
                            <div class="col-md-12">
                                <div class="custDiv" style="border:none !important; padding-top:0px !important;"> 
                                    <div class="tab-content">
                                        <div id="prflCMHomeEDT" class="tab-pane fadein active" style="border:none !important;"> 
                                            <div class="col-md-12" style="padding:0px 0px 10px 1px !important;">
                                                <div style="padding:0px 1px 0px 15px !important;float:left;">
                                                        <button type="button" class="btn btn-default btn-sm" style="height:30px;" id="myVmsTrnsStatusBtn">
                                                                <span style="font-weight:bold;">Status: </span><span style="color:<?php echo $rqstatusColor; ?>;font-weight: bold;"><?php echo $trnsStatus; ?></span>
                                                        </button>
                                                        <button type="button" class="btn btn-default btn-sm" style="height:30px;" onclick="getDisbursementForm('myFormsModalLg', 'myFormsModalBodyLg', 'myFormsModalTitleLg', 'Edit Disbursement', 15, <?php echo $subPgNo; ?>,0,'VIEW', <?php echo $sbmtdTrnsHdrID; ?>,'indCustTable','indCustTableRow1');" data-toggle="tooltip" title="Reload Transaction">
                                                                <img src="cmn_images/refresh.bmp" style="padding-right: 2px; height:17px; width:auto; position: relative; vertical-align: middle;">
                                                        </button>
                                                        <button type="button" class="btn btn-default btn-sm" style="height:30px;" id="myVmsTrnsTtlBtn">
                                                                <span style="font-weight:bold;">Total: </span><span style="color:<?php echo $ttlColor; ?>;font-weight: bold;"><?php echo $crncy." ".number_format($trnsTtl,2); ?></span>
                                                        </button>
                                                </div>
                                                <div style="padding:0px 1px 0px 1px !important;float:right;">
                                                        <?php if ($trnsStatus == "Disbursed" || $trnsStatus == "Void") {
                                                            $reportTitle = "Withdrawal Transaction";
                                                            $reportName = "Teller Transaction Receipt";
                                                            $rptID = getRptID($reportName);
                                                            $prmID1 = getParamIDUseSQLRep("{:invoice_id}", $rptID);
                                                            $prmID2 = getParamIDUseSQLRep("{:documentTitle}", $rptID);
                                                            $invcID = $sbmtdTrnsHdrID;
                                                            $paramRepsNVals = $prmID1 . "~" . $invcID . "|" . $prmID2 . "~" . $reportTitle . "|-190~PDF";
                                                            $paramStr = urlencode($paramRepsNVals);
                                                            ?>
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
                                            <form class="form-horizontal" id="loanDisbursementForm">
                                                <div class="row"><!-- ROW 1 -->
                                                    <div class="col-lg-12">
                                                        <fieldset class="basic_person_fs5"><legend class="basic_person_lg">Disbursement Header</legend>
                                                            <div class="col-lg-4">
                                                                <input class="form-control" id="disbmntHdrID" type = "hidden" placeholder="Disbursement Header ID" value="<?php echo $row[0]; ?>"/>                                                        
                                                                <div class="form-group form-group-sm">
                                                                    <label for="batchNo" class="control-label col-md-4">Batch No:</label>
                                                                    <div class="col-md-8">
                                                                        <input class="form-control" id="batchNo" type = "text" placeholder="" value="<?php echo $row[1]; ?>" readonly/>                                                                                                                                            
                                                                    </div>
                                                                </div>
                                                                <div class="form-group form-group-sm">
                                                                    <label for="disbmntDate" class="control-label col-md-4">Date:</label>
                                                                    <div class="col-md-8">
                                                                        <input class="form-control" id="disbmntDate" type = "text" placeholder="" value="<?php echo $row[2]; ?>" readonly/>                                                                                                                                            
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-4">
                                                                <div class="form-group form-group-sm">
                                                                    <!--<label for="description" class="control-label col-md-4" >Description:</label>-->
                                                                    <div  class="col-md-12">
                                                                        <textarea class="form-control rqrdFld" id="description" cols="2" placeholder="Description" rows="3" <?php echo $mkReadOnly; ?> ><?php echo $row[5]; ?></textarea>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-4">
                                                                <div class="form-group form-group-sm">
                                                                    <label for="bnkBranch" class="control-label col-md-4">Branch:</label>
                                                                    <div  class="col-md-8">
                                                                        <div class="input-group">
                                                                            <input type="text" class="form-control" aria-label="..." id="bnkBranch" value="<?php echo $prsnBranch; ?>" readonly>
                                                                            <input type="hidden" id="bnkBranchID" value="<?php echo $row[3]; ?>"> 
                                                                            <input type="hidden" id="gnrlOrgID" value="<?php echo $orgID; ?>">
                                                                            <label <?php echo $dsplyBlkNone; ?> class="btn btn-primary btn-file input-group-addon" onclick="onDsbmntBnkBranchChange();">
                                                                                <span class="glyphicon glyphicon-th-list"></span>
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group form-group-sm">
                                                                    <label for="crncy" class="control-label col-md-4">Currency:</label>
                                                                    <div  class="col-md-8">
                                                                        <div class="input-group">
                                                                            <input type="text" class="form-control" aria-label="..." id="crncy" value="<?php echo $crncy; ?>" readonly>
                                                                            <input type="hidden" id="crncyID" value="<?php echo $crncyID; ?>"> 
                                                                            <input type="hidden" id="gnrlOrgID" value="<?php echo $orgID; ?>">
                                                                            <label <?php echo $dsplyBlkNone; ?> class="btn btn-primary btn-file input-group-addon" onclick="onDsbmntCrncyChange()">
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
                                                        <fieldset class="basic_person_fs1" style="margin-top:5px !important;">
                                                            <legend class="basic_person_lg">Disbursement Details</legend>
                                                            <div class="row"><!-- ROW 2 -->
                                                                <div class="col-lg-12">
                                                                    <div  class="col-md-12">
                                                                        <div class="row"><!-- ROW 3 -->
                                                                            <div class="col-lg-12">
                                                                                <div style="float:left; margin-bottom: 5px !important;">
                                                                                <?php 
                                                                                    if("Withdrawn" == "Incomplete"){
                                                                                ?>
                                                                                <button type="button" class="btn btn-default btn-sm" onclick="getAddLoanLinesForm('myFormsModaly', 'myFormsModalyBody', 'myFormsModalyTitle', 'acctSignatoryForm', '', 'Auto-Loan Approved Loans', 15, <?php echo $subPgNo; ?>, 6, '', -1);"><img src="cmn_images/Refresh1.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">AUTO-LOAD</button>
                                                                                <button type="button" class="btn btn-default btn-sm" onclick="getAddLoanLinesForm('myFormsModaly', 'myFormsModalyBody', 'myFormsModalyTitle', 'acctSignatoryForm', '', 'Add Loan Request', 15, <?php echo $subPgNo; ?>, 5, '', - 1);"><img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">ADD LOAN</button>                                                                            
                                                                                <?php 
                                                                                    }
                                                                                ?>                                                                                
                                                                                </div>                                           
                                                                            </div>
                                                                        </div>
                                                                        <table id="disbmntDetTblAdd" class="table table-striped table-bordered table-responsive" cellspacing="0" width="100%" style="width:100%;">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th>...</th>
                                                                                    <th>No.</th>
                                                                                    <th>Customer</th>
                                                                                    <th>Customer<br>Type</th>
                                                                                    <th>Request No.</th>
                                                                                    <th>Approved Amount</th>
                                                                                    <th>Loan Tenor</th>                                                                                
                                                                                    <th>Interest<br>Rate (%)</th>
                                                                                    <th>Loan<br>Type</th>                                                                              
                                                                                    <th>...</th>
                                                                                    <th>...</th>
                                                                                    <th style="display:none;">Loan Request ID</th>
                                                                                    <th style="display:none;">Charge Interest</th>
                                                                                    <th style="display:none;">Tenor</th>
                                                                                    <th style="display:none;">Tenor Type</th>
                                                                                    <th style="display:none;">Repayment Type</th>
                                                                                    <th style="display:none;">Compound Period Type</th>
                                                                                    <th style="display:none;">Grace Period</th>
                                                                                    <th style="display:none;">Grace Period Type</th>
                                                                                    <th style="display:none;">Disbursement Detail ID</th>

                                                                                    <th style="display:none;">Interest Rate Per Period</th>
                                                                                    <th style="display:none;">Total Loan Term Times</th>
                                                                                    <th style="display:none;">Payment Per Period</th>
                                                                                    <th style="display:none;">Interest Rate Type</th>
                                                                                    <th>Schedule<br>Saved?</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody id="disbmntDetTblTbody">
                                                                                <?php
                                                                                $cnta = 0;
                                                                                $result2 = get_DisbursementDetLoans($row[0]);
                                                                                while ($row2 = loc_db_fetch_array($result2)) {
                                                                                    $cnta = $cnta + 1;
                                                                                    $approvedAmount = $row2[4];
                                                                                    $isSchdlSvdFntClr = "red";
                                                                                    $isSchdlSvd = $row2[37];
                                                                                    if($isSchdlSvd === "YES"){
                                                                                        $isSchdlSvdFntClr = "green";
                                                                                    } else if($row2[23] === "Overdraft"){
                                                                                        $isSchdlSvdFntClr = "green";
                                                                                        $isSchdlSvd = "N/A";
                                                                                    }
                                                                                    
                                                                                    $ttlLoanTermTimes = calculate_TtlLoanTermTimes($row2[10], $row2[11], $row2[12]);

                                                                                    if ($row2[22] === "Top-Up Request") {
                                                                                        $result4 = get_RefLoanRqstDetsForLoanApplctn($row2[24]);
                                                                                        while ($row4 = loc_db_fetch_array($result4)) {
                                                                                            $currPrincipalAmountBal = (float) $row4[1];
                                                                                            $approvedAmount = (float) $approvedAmount + $currPrincipalAmountBal;
                                                                                        }
                                                                                    }
                                                                                    ?>

                                                                                    <tr id="disbmntDetTblAddRow_<?php echo $row2[16]; ?>">
                                                                                        <td class="lovtd"><button type="button" title="View Loan Request" class="btn btn-default btn-sm" onclick="getLoanRqstForm('myFormsModalLgZ', 'myFormsModalLgZBody', 'myFormsModalLgZTitle', 'View Loan Request', 15, 4.1,0,'VIEW', <?php echo $row2[8]; ?>,'indCustTable','indCustTableRow1');" style="padding:2px !important;">
                                                                                                <img src="cmn_images/kghostview.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                                            </button></td>
                                                                                        <td class="lovtd"><?php echo $cnta; ?></td>
                                                                                        <td class="lovtd"><?php echo $row2[1]; ?></td>
                                                                                        <td class="lovtd"><?php echo $row2[2]; ?></td>
                                                                                        <td class="lovtd"><?php echo $row2[3]; ?></td>
                                                                                        <td class="lovtd" style="text-align:right; color:blue !important;font-weight: bold; font-size: 14px !important;"><?php echo number_format($row2[4],2); ?></td>
                                                                                        <td class="lovtd"><?php echo $row2[5]; ?></td>
                                                                                        <td class="lovtd"><?php echo number_format($row2[6],2); ?></td>
                                                                                        <td class="lovtd"><?php echo $row2[23]; ?></td>
                                                                                        <td class="lovtd">
                                                                                            <?php
                                                                                            if (!($row[4] == "Incomplete" || $row[4] == "Rejected" || $row[4] == "Withdrawn")) {
                                                                                                ?>
                                                                                                <button type="button" title="View Schedule" class="btn btn-default btn-sm" onclick="showAmo(<?php echo $approvedAmount; ?>,<?php echo $row2[17]; ?>,
                                                                                                <?php echo $ttlLoanTermTimes; ?>, <?php echo $row2[18]; ?>, '<?php echo $row2[12]; ?>', '<?php echo $row2[19]; ?>',
                                                                                                        '<?php echo $row2[20]; ?>', 'myFormsModalBodyLgz',<?php echo $row2[16]; ?>, '<?php echo $row2[21]; ?>', '<?php echo $row2[23]; ?>','<?php echo $row2[13]; ?>',<?php echo $row2[33]; ?>, '<?php echo $isSchdlSvd; ?>');" style="padding:2px !important;">
                                                                                                    <img src="cmn_images/kghostview.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                                                </button>
                                                                                             <?php
                                                                                            } else {
                                                                                                echo "...";
                                                                                            }
                                                                                            ?>
                                                                                        </td>
                                                                                        <td class="lovtd">
                                                                                            <?php
                                                                                            if ($row[4] == "Incomplete" || $row[4] == "Rejected" || $row[4] == "Withdrawn") {
                                                                                                ?>
                                                                                                <button type="button" title="Clear Loan Request" class="btn btn-default btn-sm" onclick="deleteLoanDisbursementDet(<?php echo $row2[16]; ?>, <?php echo $sbmtdTrnsHdrID; ?>);" style="padding:2px !important;">
                                                                                                    <img src="cmn_images/delete_img.gif" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                                                </button>
                                                                                                <?php
                                                                                            } else {
                                                                                                echo "...";
                                                                                            }
                                                                                            ?>
                                                                                        </td>
                                                                                        <td style="display:none;"><?php echo $row2[8]; ?></td>
                                                                                        <td style="display:none;"><?php echo $row2[9]; ?></td>
                                                                                        <td style="display:none;"><?php echo $row2[10]; ?></td>
                                                                                        <td style="display:none;"><?php echo $row2[11]; ?></td>
                                                                                        <td style="display:none;"><?php echo $row2[12]; ?></td>
                                                                                        <td style="display:none;"><?php echo $row2[13]; ?></td>
                                                                                        <td style="display:none;"><?php echo $row2[14]; ?></td>
                                                                                        <td style="display:none;"><?php echo $row2[15]; ?></td>
                                                                                        <td style="display:none;"><?php echo $row2[16]; ?></td>
                                                                                        <td style="display:none;"><?php echo $row2[17]; ?></td>
                                                                                        <td style="display:none;"><?php echo $ttlLoanTermTimes; ?></td>
                                                                                        <td style="display:none;"><?php echo $row2[18]; ?></td>
                                                                                        <td style="display:none;"><?php echo $row2[21]; ?></td>
                                                                                        <td class="lovtd" style="color:<?php echo $isSchdlSvdFntClr;?> !important;font-weight: bold; font-size: 14px !important;"><?php echo $isSchdlSvd; ?></td>
                                                                                    </tr>                                                                                    
                                                                                    <?php
                                                                                }

                                                                                /* $disbDetArray[$i] = array('loanRqstId' => $row[0], "customer" => $row[1], "custType" => $row[2],
                                                                                  "trnsctnId" => $row[3], "approvedAmount" => $row[4],
                                                                                  "tenorDisp" => $row[5], "interestRate" => $row[6], "applicationDate" => $row[7],
                                                                                  "loanRqstId" => $row[8], "chargeInterest" => $row[9],
                                                                                  "loanTenor" => $row[10], "loanTenorType" => $row[11], "loanRepaymentType" => $row[12],
                                                                                  "compoundingPeriod" => $row[13], "gracePeriodNo" => $row[14],
                                                                                  "gracePeriodType" => $row[15], "disbmntDetId" => $row[16],
                                                                                  "interestRatePerPeriod" => $row[17], "ttlLoanTermTimes" => $ttlLoanTermTimes,
                                                                                  "scheduledPrdcPaymntAmnt" => $row[18], "repayStartDate" => $row[19],
                                                                                  "repayEndDate" => $row[20], "interestRateType" => $row[21]); */
                                                                                ?>
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </fieldset>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        <div id = "prflCMDataEDT" class = "tab-pane fade" style = "border:none !important;"></div>
                                        <div id = "prflCMAttchmntEDT" class = "tab-pane fade" style = "border:none !important;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                }
                
                $cntent = "<div style=\"display:none !important;\">
                                <select id=\"trnsNtAllwdDysSlt1\">";
                $brghtStr = "";
                $isDynmyc = FALSE;
                $titleRslt = getLovValues("%", "Both", 0, 100, $brghtStr, getLovID("Transactions not Allowed Days"), $isDynmyc, -1, "", "");
                while ($titleRow = loc_db_fetch_array($titleRslt)) {
                    $selectedTxt = "";
                    $cntent .= "<option value=\"$titleRow[0]\" $selectedTxt>$titleRow[0]</option>";
                }
                $cntent .= "</select>
                            </div>";

                $cntent .= "<div style=\"display:none !important;\">
                                    <select id=\"trnsNtAllwdDtsSlt1\">";
                $brghtStr = "";
                $isDynmyc = FALSE;
                $titleRslt = getLovValues("%", "Both", 0, 100, $brghtStr, getLovID("Transactions not Allowed Dates"), $isDynmyc, -1, "", "");
                while ($titleRow = loc_db_fetch_array($titleRslt)) {
                    $selectedTxt = "";
                    $cntent .= "<option value=\"$titleRow[0]\" $selectedTxt>$titleRow[0]</option>";
                }
                $cntent .= "</select>
                                </div>";

                echo $cntent;                
            }
        } else if ($vwtyp == "1") {
            /* RISK ASSESSMENT */

            if ($vwtypActn === "ADD") {// RISK ASSESSMENT
                $loanRqstID = isset($_POST['lnRqstID']) ? cleanInputData($_POST['lnRqstID']) : -1;
                $lnprflFctrId = isset($_POST['lnprflFctrId']) ? cleanInputData($_POST['lnprflFctrId']) : -1;
                $chkedAssmntID = isset($_POST['chkedAssmntID']) ? cleanInputData($_POST['chkedAssmntID']) : -1;
                $optn = isset($_POST['optn']) ? cleanInputData($_POST['optn']) : 'FIRST';
                ?>

                <div class="row" style="margin: 0px 0px 10px 0px !important;">
                    <div class="col-md-2" style="padding:0px 1px 0px 1px !important;"><button type="button" class="btn btn-default btn-sm" style="width:100% !important;" onclick="getCreditAssessmentQstn(<?php echo $pgNo; ?>, <?php echo $subPgNo; ?>, <?php echo $vwtyp; ?>, 'FIRST', '<?php echo $vwtypActn; ?>');"><span class="glyphicon glyphicon-fast-backward" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;"></span>FIRST</button></div> 
                    <div class="col-md-2" style="padding:0px 1px 0px 1px !important;"><button type="button" class="btn btn-default btn-sm" style="width:100% !important;" onclick="getCreditAssessmentQstn(<?php echo $pgNo; ?>, <?php echo $subPgNo; ?>, <?php echo $vwtyp; ?>, 'PREVIOUS', '<?php echo $vwtypActn; ?>');"><span class="glyphicon glyphicon-backward" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;"></span>PREVIOUS</button></div>
                    <div class="col-md-4" style="padding:0px 0px 0px 15px !important;">&nbsp;</div>   
                    <div class="col-md-2" style="padding:0px 1px 0px 1px !important;"><button type="button" class="btn btn-default btn-sm" style="width:100% !important;" onclick="getCreditAssessmentQstn(<?php echo $pgNo; ?>, <?php echo $subPgNo; ?>, <?php echo $vwtyp; ?>, 'NEXT', '<?php echo $vwtypActn; ?>');"><span class="glyphicon glyphicon-forward" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;"></span>NEXT</button></div>
                    <div class="col-md-2" style="padding:0px 1px 0px 1px !important;"><button type="button" class="btn btn-default btn-sm" style="width:100% !important;" onclick="getCreditAssessmentQstn(<?php echo $pgNo; ?>, <?php echo $subPgNo; ?>, <?php echo $vwtyp; ?>, 'LAST', '<?php echo $vwtypActn; ?>');"><span class="glyphicon glyphicon-fast-forward" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;"></span>LAST</button></div>              
                </div>                

                <form class="form-horizontal" id='stdEvntsForm' action='' method='post' accept-charset='UTF-8'>
                    <div class="row"><!-- ROW 1 -->
                        <div class="col-lg-12">
                            <?php
                            $result1 = get_AssessmentRiskFactorProfileNCode($lnprflFctrId, $optn, $loanRqstID, $chkedAssmntID);
                            $riskFactorCode = "";
                            $riskProfile = "";

                            while ($row1 = loc_db_fetch_array($result1)) {
                                $riskFactorCode = $row1[0];
                                $riskProfile = $row1[1];
                            }
                            ?>   
                            <div class="row" style="margin: 0px 0px 10px 0px !important;">
                                <div class="col-md-12" style="padding:0px 1px 0px 1px !important;">
                                    <button type="button" class="btn btn-default btn-md" style="width:100% !important;"><?php echo $riskProfile; ?></button>
                                </div>            
                            </div>

                            <fieldset class="basic_person_fs5"><legend class="basic_person_lg" id="assmntQstn" style="color:red"><?php echo $riskFactorCode; ?></legend>                            
                                <?php
                                $result = get_AssessmentQuestion($lnprflFctrId, $optn, $loanRqstID, $chkedAssmntID);

                                while ($row = loc_db_fetch_array($result)) {
                                    ?>

                                    <div  class="form-group form-group-sm">
                                        <div class="col-md-3" style="padding:0px 0px 0px 15px !important;">&nbsp;</div>
                                        <div  class="col-md-6">
                                            <?php
                                            $chkdYesNo = "";
                                            if ($row[6] == "YES") {
                                                $chkdYesNo = "checked=\"\"";
                                            }
                                            ?>
                                            <label class="radio-inline" style="font-size:16px !important;"><input type="radio" name="assmntID" id="assmntID_<?php echo $row[0]; ?>" value="<?php echo $row[0]; ?>" <?php echo $chkdYesNo; ?>><?php echo $row[4]; ?></label>
                                        </div>  
                                        <div class="col-md-3" style="padding:0px 0px 0px 15px !important;">&nbsp;</div>
                                    </div> 


                                    <?php
                                }
                                ?>                            
                            </fieldset> 
                        </div>                                
                    </div>                    
                </form> 

                <div class="row" style="margin: 0px 0px 10px 0px !important;">
                    <div class="col-md-2" style="padding:0px 1px 0px 1px !important;"><button type="button" class="btn btn-default btn-sm" style="width:100% !important;" onclick="getCreditAssessmentQstn(<?php echo $pgNo; ?>, <?php echo $subPgNo; ?>, <?php echo $vwtyp; ?>, 'FIRST', '<?php echo $vwtypActn; ?>');"><span class="glyphicon glyphicon-fast-backward" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;"></span>FIRST</button></div> 
                    <div class="col-md-2" style="padding:0px 1px 0px 1px !important;"><button type="button" class="btn btn-default btn-sm" style="width:100% !important;" onclick="getCreditAssessmentQstn(<?php echo $pgNo; ?>, <?php echo $subPgNo; ?>, <?php echo $vwtyp; ?>, 'PREVIOUS', '<?php echo $vwtypActn; ?>');"><span class="glyphicon glyphicon-backward" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;"></span>PREVIOUS</button></div>
                    <div class="col-md-4" style="padding:0px 0px 0px 15px !important;">&nbsp;</div>   
                    <div class="col-md-2" style="padding:0px 1px 0px 1px !important;"><button type="button" class="btn btn-default btn-sm" style="width:100% !important;" onclick="getCreditAssessmentQstn(<?php echo $pgNo; ?>, <?php echo $subPgNo; ?>, <?php echo $vwtyp; ?>, 'NEXT', '<?php echo $vwtypActn; ?>');"><span class="glyphicon glyphicon-forward" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;"></span>NEXT</button></div>
                    <div class="col-md-2" style="padding:0px 1px 0px 1px !important;"><button type="button" class="btn btn-default btn-sm" style="width:100% !important;" onclick="getCreditAssessmentQstn(<?php echo $pgNo; ?>, <?php echo $subPgNo; ?>, <?php echo $vwtyp; ?>, 'LAST', '<?php echo $vwtypActn; ?>');"><span class="glyphicon glyphicon-fast-forward" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;"></span>LAST</button></div>              
                </div>               

                <?php
            } else if ($vwtypActn === "VIEW") {
                
            }
        } else if ($vwtyp == "2") {

        } else if ($vwtyp == "3") {
            
        } else if ($vwtyp == "4") {
            
        } else if ($vwtyp == "5") {
            /* ADD INDIVIDUAL LOAN*/
            $rowID = isset($_POST['rowID']) ? cleanInputData($_POST['rowID']) : -1;
            $disbmntHdrID = isset($_POST['disbmntHdrID']) ? cleanInputData($_POST['disbmntHdrID']) : -1;
            //$custType = isset($_POST['custType']) ? cleanInputData($_POST['custType']) : '';
            $custGrpID = isset($_POST['custGrpID']) ? cleanInputData($_POST['custGrpID']) : -1;
            $bnkBranchID = isset($_POST['bnkBranchID']) ? cleanInputData($_POST['bnkBranchID']) : -1;
            $crncyID = isset($_POST['crncyID']) ? cleanInputData($_POST['crncyID']) : -1;
            ?>
            <form class="form-horizontal" id="acctSignatoryForm" style="padding:5px 20px 5px 20px;">
                <div class="row">
                    <!--account Signatory ID-->
                    <input class="form-control" size="16" type="hidden" id="disbmntDetId" value="<?php echo $rowID; ?>" readonly="">                        
                    <!--<input type="hidden" id="custGrpID" value="<?php echo $custGrpID; ?>">-->
                    <div class="form-group form-group-sm">
                        <label for="custType" class="control-label col-md-4">Customer Type(s):</label>
                        <div  class="col-md-8">
                            <select class="form-control" id="custType" onChange="dspCustGrpOnDisbForm();">
                                <option value="--Please Select--">--Please Select--</option>                                    
                                <option value="Individual">Individuals</option>
                                <option value="Corporate">Corporate</option>
                                <option value="Individual and Corporate">Individuals and Corporate</option>
                                <option value="Group Members">Group Members</option>
                            </select>
                        </div>
                    </div>                        
                    <div class="form-group form-group-sm" id="custGrpDiv" style="display:none;">
                        <label for="custGrp" class="control-label col-md-4">Group:</label>
                        <div  class="col-md-8">
                            <div class="input-group">
                                <input type="text" class="form-control" aria-label="..." id="custGrp" value="" readonly placeholder=""> 
                                <input type="hidden" id="custGrpID" value="-1">  
                                <input type="hidden" id="gnrlOrgID" value="<?php echo $orgID; ?>">
                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'MCF Customer Groups', 'gnrlOrgID', '', '', 'radio', true, '', 'custGrpID', 'custGrp', 'clear', 1, '');">
                                    <span class="glyphicon glyphicon-th-list"></span>
                                </label>
                            </div>
                        </div>
                    </div>                   
                    <div class="form-group form-group-sm">
                        <label for="loanRqst" class="control-label col-md-4">Loan Request:</label>
                        <div  class="col-md-8">
                            <div class="input-group">
                                <!--table rowElementID-->
                                <input class="form-control" size="16" type="hidden" id="tblRowElementID" value="" readonly="">

                                <input type="text" class="form-control" aria-label="..." id="loanRqst" value="">
                                <input type="hidden" id="loanRqstId" value="">
                                <input type="hidden" id="gnrlOrgID" value="<?php echo $orgID; ?>">
                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLoanApplicant();">
                                    <span class="glyphicon glyphicon-th-list"></span>
                                </label>
                            </div>
                        </div>
                    </div>                  
                </div>
                <div class="row" style="float:right;padding-right: 1px;">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="saveLoanRqstForm(<?php echo $pgNo; ?>, <?php echo $subPgNo; ?>);">Load Request</button>
                </div>
            </form>
            <?php
        }
        else if ($vwtyp == "6") {
            /* AUTO-LOAD LOAN*/
            $rowID = isset($_POST['rowID']) ? cleanInputData($_POST['rowID']) : -1;
            $disbmntHdrID = isset($_POST['disbmntHdrID']) ? cleanInputData($_POST['disbmntHdrID']) : -1;
            //$custType = isset($_POST['custType']) ? cleanInputData($_POST['custType']) : '';
            $custGrpID = isset($_POST['custGrpID']) ? cleanInputData($_POST['custGrpID']) : -1;
            $bnkBranchID = isset($_POST['bnkBranchID']) ? cleanInputData($_POST['bnkBranchID']) : -1;
            $crncyID = isset($_POST['crncyID']) ? cleanInputData($_POST['crncyID']) : -1;
            ?>
            <form class="form-horizontal" id="acctSignatoryForm" style="padding:5px 20px 5px 20px;">
                <div class="row">
                    <!--account Signatory ID-->
                    <input class="form-control" size="16" type="hidden" id="disbmntDetId" value="<?php echo $rowID; ?>" readonly="">                    
                    <!--account ID-->  
                    <!--<input type="hidden" id="custGrpID" value="<?php echo $custGrpID; ?>">-->    
                    <div class="form-group form-group-sm">
                        <label for="custType" class="control-label col-md-4">Customer Type(s):</label>
                        <div  class="col-md-8">
                            <select class="form-control" id="custType" onchange="dspCustGrpOnDisbForm();">
                                <option value="--Please Select--">--Please Select--</option>                                    
                                <option value="Individual">Individuals</option>
                                <option value="Corporate">Corporate</option>
                                <option value="Individual and Corporate">Individuals and Corporate</option>
                                <option value="Group Members">Group Members</option>
                            </select>
                        </div>
                    </div>   
                    <div class="form-group form-group-sm" id="custGrpDiv" style="display:none;">
                        <label for="custGrp" class="control-label col-md-4">Group:</label>
                        <div  class="col-md-8">
                            <div class="input-group">
                                <input type="text" class="form-control" aria-label="..." id="custGrp" value="" readonly placeholder=""> 
                                <input type="hidden" id="custGrpID" value="-1">  
                                <input type="hidden" id="gnrlOrgID" value="<?php echo $orgID; ?>">
                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'MCF Customer Groups', 'gnrlOrgID', '', '', 'radio', true, '', 'custGrpID', 'custGrp', 'clear', 1, '');">
                                    <span class="glyphicon glyphicon-th-list"></span>
                                </label>
                            </div>
                        </div>
                    </div>                      
                </div>
                <div class="row" style="float:right;padding-right: 1px;">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="autoLoadApprovedLoans(<?php echo $pgNo; ?>, <?php echo $subPgNo; ?>, 0);">LOAD</button>
                </div>
            </form>
            <?php
        }
    } 
    else if ($subPgNo == 4.3) {//LOAN REPAYMENT
        //DEPOSIT PRODUCT NEW
        if ($vwtyp == "0") {
            /* NEW DEPOSIT */
            $cntent .= "
					<li onclick=\"openATab('#allmodules', 'grp=17&typ=1&pg=4');\">
                                                <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
						<span style=\"text-decoration:none;\">Credit Management Menu</span>
                                        </li>";

            if ($vwtypActn != "FIND") {
                echo $cntent .= "
					<li onclick=\"openATab('#allmodules', 'grp=17&typ=1&pg=4&subPgNo=4.3');\">
                                                <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
						<span style=\"text-decoration:none;\">Loan Repayment</span>
                                        </li></div>";
            }

            $sbmtdTrnsHdrID = isset($_POST['PKeyID']) ? cleanInputData($_POST['PKeyID']) : -1;
            $trnsType = "LOAN_REPAY"; //"DEPOSIT";
            if ($vwtypActn == "EDIT" || $vwtypActn === "ADD") {
                /* Add */
                if (!$canAddTrns || ($sbmtdTrnsHdrID > 0 && !$canEdtTrns)) {
                    restricted();
                    exit();
                }
                $cageID = -1;
                $invAssetAcntID = -1;
                $sbmtdStoreID = -1;
                $pkID = getLatestCage($prsnid, $cageID, $sbmtdStoreID, $invAssetAcntID);
                $sbmtdSiteID = getLatestSiteID($prsnid);
                $trnsStatus = "Not Submitted";
                $rqstatusColor = "red";

                $dte = date('ymdHis');
                $usrTrnsCode = getGnrlRecNm("sec.sec_users", "user_id", "code_for_trns_nums", $usrID);
                $gnrtdTrnsNo = "";
                $gnrtdTrnsDate = date('d-M-Y H:i:s');
                $dateStr = date('Y-m-d H:i:s');
                $prprdBy = "";

                $acctTitle = "";
                $unclrdBal = 0;
                $clrdBal = 0;
                $docType = "";
                $docNum = "";
                $trnsAmount = 0;
                $cashAmount = 0;
                $trnsDesc = "";
                $acctNo = "";
                $accntID = -1;
                $acctStatus = "";
                $acctCrncy = $fnccurnm;
                $crncyID = $fnccurid;
                $crncyIDNm = $fnccurnm;
                $acctType = "";
                $acctCustomer = "";
                $prsnTypeEntity = "";
                $acctBranch = "";
                $acctLien = 0;
                $mandate = "";
                $authorizer = "";
                $aprvLimit = 0;
                $wtdrwlLimitNo = 0;
                $wtdrwlLimitAmt = 0;
                $wtdrwlLimitType = "";
                $exchangeRate = 0;
                $voidedTrnsHdrID = -1;
                $voidedTrnsType = "";
                $dbOrCrdt = "CR";
                $trnsPersonName = "";
                $trnsPersonTelNo = "";
                $trnsPersonAddress = "";
                $trnsPersonIDType = "";
                $trnsPersonIDNumber = "";
                $trnsPersonType = "";
                $mkReadOnly = "";
                $mkRmrkReadOnly = "";
                $vwOrAdd = "ADD";
                $pageCaption = "<span style=\"font-weight:bold;\">TOTAL LOAN REPAYMENT: </span><span style=\"font-weight:bold;color:blue;\" id=\"tllrTrnsAmntTtlFld\">" . $acctCrncy . " " . number_format($trnsAmount, 2) . "</span>";
                $brnchLocID = getLatestSiteID($prsnid);
                $brnchLoc = getGnrlRecNm("org.org_sites_locations", "location_id", "location_code_name", $brnchLocID);
                $accntFctrsLocID = -1;
                $accntFctrsLoc = "";
                $loanRepayType = "";
                $loanRpmntSrcAcctID = -1;
                $loanRpmntSrcAcct = "";
                $loanRpmntSrcAmnt = 0.00;
                $bnkCustomerID = -1;

                $unclrdColor = "blue";
                $clrdColor = "blue";
                if ($sbmtdTrnsHdrID > 0) {
                    //Important! Must Check if One also has prmsn to Edit brought Trns Hdr ID
                    $result = get_OneCustAccntTrnsDet_LoanRpmnt($sbmtdTrnsHdrID);
                    if ($row = loc_db_fetch_array($result)) {
                        $trnsType = $row[5];
                        $trnsStatus = $row[7];
                        $voidedTrnsHdrID = (float) $row[28];
                        $voidedTrnsType = $row[29];
                        if ($trnsStatus == "Not Submitted" || $trnsStatus == "Withdrawn" || $trnsStatus == "Rejected") {
                            $rqstatusColor = "red";
                            if ($voidedTrnsHdrID <= 0) {
                                $mkReadOnly = "";
                                $mkRmrkReadOnly = "";
                            } else {
                                $mkReadOnly = "readonly=\"true\"";
                                $mkRmrkReadOnly = "";
                                $vwOrAdd = "VIEW";
                            }
                        } else if ($trnsStatus != "Authorized" && $trnsStatus != "Void" && $trnsStatus != "Received") {
                            $mkReadOnly = "readonly=\"true\"";
                            $mkRmrkReadOnly = "readonly=\"true\"";
                            $rqstatusColor = "brown";
                            $vwOrAdd = "VIEW";
                        } else if ($trnsStatus == "Void") {
                            $mkReadOnly = "readonly=\"true\"";
                            $mkRmrkReadOnly = "readonly=\"true\"";
                            $rqstatusColor = "red";
                            $vwOrAdd = "VIEW";
                        } else {

                            $rqstatusColor = "green";
                            $mkReadOnly = "readonly=\"true\"";
                            $mkRmrkReadOnly = "readonly=\"true\"";
                            $vwOrAdd = "VIEW";
                        }
                        $gnrtdTrnsNo = $row[12];
                        $gnrtdTrnsDate = $row[11];
                        $uName11 = getUserName((float) $row[32]);
                        $prprdBy = "<span style=\"color:blue;font-weight:bold;\">" . $uName11 . "@" . $gnrtdTrnsDate . "</span>";
                        $brnchLocID = (int) $row[14];
                        $brnchLoc = getGnrlRecNm("org.org_sites_locations", "location_id", "location_code_name", $brnchLocID);
                        $accntFctrsLocID = (int) $row[33];
                        $accntFctrsLoc = getGnrlRecNm("org.org_sites_locations", "location_id", "location_code_name || '.' || site_desc", $accntFctrsLocID);
                        $crncyID = (int) $row[9];
                        $crncyIDNm = $row[10];
                        if ($crncyID <= 0) {
                            $crncyID = $fnccurid;
                            $crncyIDNm = $fnccurnm;
                        }
                        if ($voidedTrnsHdrID <= 0) {
                            $trnsDesc = $row[31];
                        } else {
                            $trnsDesc = $row[30];
                        }
                        $acctTitle = $row[2];
                        $docType = $row[15];
                        $docNum = $row[16];
                        $trnsAmount = $row[6];
                        $cashAmount = $row[27];
                        $acctNo = $row[1];
                        $accntID = (int) $row[8];
                        $acctCrncy = $row[10];
                        $authorizer = $row[34];
                        $aprvLimit = (float) $row[36];
                        $dbOrCrdt = $row[24];
                        $pageCaption = "<span style=\"font-weight:bold;\">TOTAL LOAN REPAYMENT: </span><span style=\"font-weight:bold;color:blue;\" id=\"tllrTrnsAmntTtlFld\">" . $acctCrncy . " " . number_format($trnsAmount, 2) . "</span>";
                        $unclrdBal = (float) $row[37];
                        $clrdBal = (float) $row[38];
//                        if ($unclrdBal > 0) {
//                            $unclrdColor = "green";
//                        } else {
                        $unclrdColor = "red";
                        //}
//                        if ($clrdBal > 0) {
//                            $clrdColor = "green";
//                        } else {
                        $clrdColor = "red";
                        //}
                        $acctType = $row[39];
                        $acctStatus = $row[40];
                        $acctCustomer = $row[41];
                        $prsnTypeEntity = $row[42];
                        $acctLien = (float) $row[43];
                        $mandate = $row[44];

                        $wtdrwlLimitNo = $row[45];
                        $wtdrwlLimitAmt = $row[46];
                        $wtdrwlLimitType = $row[47];

                        $trnsPersonName = $row[17];
                        $trnsPersonTelNo = $row[18];
                        $trnsPersonAddress = $row[19];
                        $trnsPersonIDType = $row[20];
                        $trnsPersonIDNumber = $row[21];
                        $trnsPersonType = $row[22];
                        $loanRepayType = $row[53];
                        $loanRpmntSrcAcctID = $row[54];
                        $loanRpmntSrcAcct = getGnrlRecNm("mcf.mcf_accounts", "account_id", "account_number", $row[54]);
                        $loanRpmntSrcAmnt = number_format($row[55],2);
                        $bnkCustomerID = $row[56];
                    }
                }
                $routingID = getMCFLoanTrnsMxRoutingID($sbmtdTrnsHdrID);
                $trnsNum = str_replace(" ()", "", " (" . $gnrtdTrnsNo . ")@" . $brnchLoc);
                ?>
                <fieldset class="" style="padding: 5px 2px 5px 2px !important;">
                    <legend class="basic_person_lg1" style="color: #003245"><?php echo $pageCaption; ?></legend>
                    <input class="form-control" id="addOrEditForm" type = "hidden" placeholder="addOrEditForm" value="Add"/> 
                    <input class="form-control" id="mcfTlrTrnsType" type = "hidden" value="<?php echo $trnsType; ?>"/>                    
                    <div class="row">                  
                        <div class="col-md-12">
                            <div class="custDiv" style="border:none !important; padding:0px !important;">
                                <div id="prflCBHomeEDT" style="border:none !important;">  
                                    <div class="row" style="margin: 0px 0px 5px 0px !important;">
                                        <div class="col-md-6" style="padding:0px 1px 0px 1px !important;">
                                            <div class="form-group form-group-sm">
                                                <div class="col-md-6" style="padding:0px 1px 0px 1px !important;">
                                                    <div class="input-group">
                                                        <input class="form-control rqrdFld" id="acctNoFind" style="font-size: 13px !important;font-weight: bold !important;" placeholder="Enter Account Number" type = "text" min="0" placeholder="" value="<?php echo $acctNo; ?>" <?php echo $mkReadOnly; ?>/>
                                                        <input type="hidden" id="acctNoFindAccId" value="<?php echo ''; ?>">
                                                        <input type="hidden" id="acctNoFindRawTxt" value="<?php echo ''; ?>">
                                                        <?php if ($trnsStatus != "Authorized" && $trnsStatus != "Received" && $trnsStatus != "Void") { ?>
                                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'MCF All Bank Loan Accounts', '', '', '', 'radio', true, '', 'acctNoFindAccId', 'acctNoFindRawTxt', 'clear', 1, '', function () {
                                                                $('#acctNoFind').val($('#acctNoFindRawTxt').val().split(' {')[0]);
                                                                });">
                                                                <span class="glyphicon glyphicon-th-list"></span>
                                                            </label>
                                                        <?php } ?>
                                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getCustAcctsForm('myFormsModalCB', 'myFormsModalBodyCB', 'myFormsModalTitleCB', 'View Customer Account', 13, 2.1, 0, 'VIEW', <?php echo $accntID; ?>, 'custAcctTable', '', '<?php echo $acctNo; ?>');" data-toggle="tooltip" data-placement="bottom" title = "Latest Account Information">
                                                            <span class="glyphicon glyphicon-info-sign"></span>
                                                        </label>
                                                    </div>
                                                    <!--Account Transaction ID-->
                                                    <input class="form-control" id="acctTrnsId" placeholder="Transaction ID" type = "hidden" placeholder="" value="<?php echo $sbmtdTrnsHdrID; ?>"/>
                                                    <input class="form-control" type="hidden" id="mcfVoidedTrnsHdrID" value="<?php echo $voidedTrnsHdrID; ?>"/>
                                                    <input class="form-control" type="hidden" id="newMCFHdrID" value="-1"/>
                                                </div>
                                                <div class="col-md-6" style="padding:0px 1px 0px 0px !important;">
                                                    <div style="float:left;">
                <?php if ($trnsStatus != "Authorized" && $trnsStatus != "Received" && $trnsStatus != "Void") { ?>
                                                            <button type="button" class="btn btn-success btn-sm" style="height: 30px;" onclick="getAcctDetails_LoanRpmnt(15, '<?php echo $subPgNo; ?>', 0, 'FIND');">
                                                                <img src="cmn_images/search.png" style="height:17px; width:auto; position: relative; vertical-align: middle;">
                                                                FIND
                                                            </button>
                <?php } ?>
                                                        <button type="button" class="btn btn-default btn-sm" style="height: 30px;" onclick="getOneMcfDocsForm('<?php echo $trnsType; ?>', 140);" data-toggle="tooltip" data-placement="bottom" title = "Attached Documents">
                                                            <img src="cmn_images/adjunto.png" style="height:17px; width:auto; position: relative; vertical-align: middle;">
                                                            Attachments
                                                        </button>
                                                        <div class="btn-group">
                                                            <button type="button" class="btn btn-default btn-sm" style="height: 30px !important;" onclick=""><!--advancedAccountSearch();-->
                                                                <!--<img src="cmn_images/kghostview.png" style="left: 0.5%; padding-right: 5px; height:17px; width:20px; position: relative; vertical-align: middle;">-->
                                                                My Tills
                                                            </button>
                                                            <button class="btn btn-info dropdown-toggle btn-sm" style="height:30px !important;" type="button" data-toggle="dropdown">
                                                                <span class="caret"></span>
                                                            </button>
                                                            <ul class="dropdown-menu" role="menu" style="margin-left: 15px !important;">
                                                            <?php
                                                            $cageRslt = getMyCages($prsnid);
                                                            while ($rowCg = loc_db_fetch_array($cageRslt)) {
                                                                $cageID = (int) $rowCg[2];
                                                                $vltID = (int) $rowCg[1];
                                                                $invAssetAcntID = (int) $rowCg[9];
                                                                $cageNm = $rowCg[3];
                                                                ?>                                                                
                                                                    <li>
                                                                        <a href="javascript:getOneCageFnPos(<?php echo $cageID; ?>, 7);">
                                                                            <img src="cmn_images/teller1.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                            My Till/Drawer (<?php echo $cageNm; ?>)
                                                                        </a>
                                                                    </li>
                                                                <?php }
                                                                ?>
                                                            </ul>
                                                        </div>
                                                        <?php if ($trnsStatus !== "Not Submitted") { ?>
                                                            <button type="button" class="btn btn-default btn-sm" style="height: 30px;" onclick="getCoreBankingForm('myFormsModalLg', 'myFormsModalBodyLg', 'myFormsModalTitleLg', 'Add New Savings Product', 15, '4.3', 0, 'ADD', -1);">
                                                                <img src="cmn_images/add1-64.png" style="height:17px; width:auto; position: relative; vertical-align: middle;">
                                                                NEW
                                                            </button>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            </div>                                               
                                        </div>
                                        <div class="col-md-6" style="padding:0px 0px 0px 1px !important;">
                                            <div style="padding:0px 1px 0px 15px !important;float:left;">
                                                <button type="button" class="btn btn-default btn-sm" style="height:30px;" id="myVmsTrnsStatusBtn">
                                                    <span style="font-weight:bold;">Status: </span><span style="color:<?php echo $rqstatusColor; ?>;font-weight: bold;"><?php echo $trnsStatus; ?></span>
                                                </button>
                                                <button type="button" class="btn btn-default btn-sm" style="height:30px;" onclick="getCoreBankingForm('myFormsModalLg', 'myFormsModalBodyLg', 'myFormsModalTitleLg', 'WITHDRAWAL TRANSACTION', 15, '<?php echo $subPgNo; ?>', 0, 'ADD', <?php echo $sbmtdTrnsHdrID; ?>);" data-toggle="tooltip" title="Reload Transaction">
                                                    <img src="cmn_images/refresh.bmp" style="padding-right: 2px; height:17px; width:auto; position: relative; vertical-align: middle;">                                                    
                                                </button>
                                            </div>
                                            <div style="padding:0px 1px 0px 1px !important;float:right;">
                <?php if ($trnsStatus == "Not Submitted" || $trnsStatus == "Withdrawn" || $trnsStatus == "Rejected") { ?>                                                    
                                                    <?php if ($voidedTrnsHdrID <= 0) { ?>
                                                        <button type="button" class="btn btn-default btn-sm" style="height:30px;" onclick="saveAccountTrns_LoansMgmt(<?php echo $pgNo; ?>, '<?php echo $subPgNo; ?>', <?php echo $vwtyp; ?>, 'LOAN_REPAY', 0);"><img src="cmn_images/FloppyDisk.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Save&nbsp;</button>
                                                        <button type="button" class="btn btn-default btn-sm" style="height:30px;" onclick="saveAccountTrns_LoansMgmt(<?php echo $pgNo; ?>, '<?php echo $subPgNo; ?>', <?php echo $vwtyp; ?>, 'LOAN_REPAY', 1);"><img src="cmn_images/Emailcon.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Finalize&nbsp;</button>   
                    <?php } else { ?>                                          
                                                        <button type="button" class="btn btn-default btn-sm" style="height:30px;" onclick="saveAccountTrnsRvrs(<?php echo $pgNo; ?>, '<?php echo $subPgNo; ?>', <?php echo $vwtyp; ?>, 'LOAN_REPAY', 1);"><img src="cmn_images/Emailcon.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Submit Reversal&nbsp;</button>
                                                    <?php } ?>
                                                    <?php
                                                } else if ($trnsStatus != "Authorized" && $trnsStatus != "Received" && $trnsStatus != "Void") {
                                                    ?>                                    
                                                    <button type="button" class="btn btn-default btn-sm" style="height:30px;" onclick="wthdrwMCFTrnsRqst_CrdtMgmnt('LOAN_REPAY', 20, '<?php echo $subPgNo; ?>');"><img src="cmn_images/withdraw_rqst.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Withdraw&nbsp;</button>                                      
                                                    <button type="button" class="btn btn-default btn-sm" style="height:30px;" onclick="authrzeMCFTrnsRqst_CrdtMgmnt('LOAN_REPAY', 20, '<?php echo $subPgNo; ?>');"><img src="cmn_images/Stamp-512.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Authorize&nbsp;</button>                                                                                                      
                                                    <button type="button" class="btn btn-default btn-sm" style="height:30px;" onclick="checkWkfRqstStatus(<?php echo $routingID; ?>, 'Teller Trns. Authorization Progress History');"><img src="cmn_images/workflow.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Progress&nbsp;</button>                                
                <?php } else if ($trnsStatus == "Authorized") {
                    ?>
                                                    <button type="button" class="btn btn-default btn-sm" style="height:30px;" onclick="finalzeMCFTrnsRqst_CrdtMgmnt('LOAN_REPAY', 20, '<?php echo $subPgNo; ?>');"><img src="cmn_images/payment_256.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Receive</button>
                                                    <button type="button" class="btn btn-default btn-sm" style="height:30px;" onclick="checkWkfRqstStatus(<?php echo $routingID; ?>, 'Teller Trns. Authorization Progress History');"><img src="cmn_images/workflow.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;" data-toggle="tooltip" title="Authorization Progress History">Progress&nbsp;</button>  
                                                    <button type="button" class="btn btn-default btn-sm" style="height:30px;" onclick="saveAccountTrnsRvrs(<?php echo $pgNo; ?>, '<?php echo $subPgNo; ?>', <?php echo $vwtyp; ?>, 'LOAN_REPAY', 0);"><img src="cmn_images/90.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Void Transaction&nbsp;</button>                               
                    <?php
                } else if ($trnsStatus == "Received" || $trnsStatus == "Void") {
                    $reportTitle = "Deposit Transaction Receipt";
                    $reportName = "Teller Transaction Receipt";
                    $rptID = getRptID($reportName);
                    $prmID1 = getParamIDUseSQLRep("{:invoice_id}", $rptID);
                    $prmID2 = getParamIDUseSQLRep("{:documentTitle}", $rptID);
                    $invcID = $sbmtdTrnsHdrID;
                    $paramRepsNVals = $prmID1 . "~" . $invcID . "|" . $prmID2 . "~" . $reportTitle . "|-190~PDF";
                    $paramStr = urlencode($paramRepsNVals);
                    ?>
                                                    <button type="button" class="btn btn-default btn-sm" style="height:30px;" title="Get Voucher on Thermal Receipt Paper" onclick="getSilentRptsRnSts(<?php echo $rptID; ?>, - 1, '<?php echo $paramStr; ?>');">
                                                        <img src="cmn_images/printer-icon.png" style="left: 0.5%; padding-right: 5px; height:17px; width:17px; position: relative; vertical-align: middle;">
                                                        POS
                                                    </button> 
                                                    <button type="button" class="btn btn-default btn-sm" style="height:30px;" title="Get Voucher on A4" onclick="getSilentRptsRnSts(<?php echo $rptID; ?>, - 1, '<?php echo $paramStr; ?>');">
                                                        <img src="cmn_images/printer-icon.png" style="left: 0.5%; padding-right: 5px; height:17px; width:17px; position: relative; vertical-align: middle;">
                                                        A4
                                                    </button>
                                                    <button type="button" class="btn btn-default btn-sm" style="height:30px;" onclick="checkWkfRqstStatus(<?php echo $routingID; ?>, 'Teller Trns. Authorization Progress History');"><img src="cmn_images/workflow.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;" data-toggle="tooltip" title="Authorization Progress History">Progress&nbsp;</button>   
                    <?php if ($voidedTrnsHdrID <= 0 && $trnsStatus == "Received") { ?>
                                                        <button type="button" class="btn btn-default btn-sm" style="height:30px;" onclick="saveAccountTrnsRvrs(<?php echo $pgNo; ?>, '<?php echo $subPgNo; ?>', <?php echo $vwtyp; ?>, 'LOAN_REPAY', 0);"><img src="cmn_images/90.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Void Transaction&nbsp;</button>                                            
                                                        <?php
                                                    }
                                                }
                                                ?>
                                            </div>
                                        </div>
                                    </div>                                          
                                    <form class="form-horizontal">
                                        <div class="row">
                                            <div class="col-lg-6"> 
                                                <fieldset class=""><legend class="basic_person_lg1">TRNS.<?php echo $trnsNum; ?></legend>
                                                    <div class="form-group form-group-sm">
                                                        <label for="acctTitle" class="control-label col-md-4">Account Title:</label>
                                                        <div  class="col-md-8">
                                                            <input class="form-control" id="acctTitle" type = "text" placeholder="" value="<?php echo $acctTitle; ?>" readonly="readonly"/>
                                                            <input class="form-control" id="ttlDocAmntVal" type = "hidden" placeholder="" value="<?php echo $trnsAmount; ?>" readonly="readonly"/>
                                                        </div>
                                                    </div> 
                                                    <div class="form-group form-group-sm">
                                                        <label for="unclrdBal" class="control-label col-md-4">Current Balance (B4):</label>
                                                        <div  class="col-md-8">
                                                            <div class="input-group">
                                                                <span class="input-group-addon" id="unclrdBalCrncy" style="font-weight:bold;"><?php echo $acctCrncy; ?></span>
                                                                <input id="unclrdBal" class="form-control"  type = "text" placeholder="" aria-describedby="unclrdBalCrncy" readonly="readonly" style="font-weight: bold;color:<?php echo $unclrdColor; ?>;font-size:16px !important;" placeholder="" value="<?php
                                echo number_format($unclrdBal, 2);
                                ?>"/>
                                                            </div>                                                                
                                                        </div>                                                            
                                                    </div>                                                        
                                                    <div class="form-group form-group-sm">
                                                        <label for="clrdBal" class="control-label col-md-4">Available Balance (B4):</label>
                                                        <div  class="col-md-8">
                                                            <div class="input-group">
                                                                <span class="input-group-addon" id="clrdBalCrncy" style="font-weight:bold;"><?php echo $acctCrncy; ?></span>
                                                                <input id="clrdBal" class="form-control"  type = "text" placeholder="" style="font-weight: bold;color:<?php echo $clrdColor; ?>;font-size:16px !important;" type = "text" placeholder="" value="<?php
                                echo number_format($clrdBal, 2);
                                ?>" aria-describedby="clrdBalCrncy" readonly="readonly"/>
                                                            </div>                                                                
                                                        </div>                                                            
                                                    </div> 
                                                    <div class="form-group form-group-sm">
                                                        <label for="docType" class="control-label col-md-4">Document Type & No.:</label>
                                                        <div  class="col-md-4" style="padding-right: 1px !important;">
                                                            <select class="form-control" id="docType" <?php echo $mkReadOnly; ?> onchange="mcfTrnsDocTypeChng();">
                                                                <?php
                                                                $brghtStr = "";
                                                                $isDynmyc = FALSE;
                                                                $titleRslt = getLovValues("%", "Both", 0, 100, $brghtStr, getLovID("MCF Deposit Document Types"), $isDynmyc, -1, "", "");
                                                                while ($titleRow = loc_db_fetch_array($titleRslt)) {
                                                                    $selectedTxt = "";
                                                                    if ($titleRow[0] == $docType) {
                                                                        $selectedTxt = "selected";
                                                                    }
                                                                ?>
                                                                    <option value="<?php echo $titleRow[0]; ?>" <?php echo $selectedTxt; ?>><?php echo $titleRow[0]; ?></option>
                                                                    <?php
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>
                                                        <div  class="col-md-4" style="padding-left: 1px !important;">
                                                            <input class="form-control" id="docNum" type = "text" placeholder="Document No" value="<?php echo $docNum; ?>" <?php echo $mkReadOnly; ?>/>
                                                        </div>
                                                    </div>                                                    
                                                    <div class="form-group form-group-sm">
                                                        <label for="loanRepayType" class="control-label col-md-4">Repayment Type:</label>
                                                        <div class="col-md-8">
                                                            <select class="form-control" id="loanRepayType" <?php echo $mkReadOnly; ?> onchange="">
                                                                <?php
                                                                    $selectedTxtRpymnt = "";
                                                                    $selectedTxtStlmnt = "";
                                                                    if ($loanRepayType == "REPAYMENT") {
                                                                        $selectedTxtRpymnt = "selected";
                                                                    } else if ($loanRepayType == "SETTLEMENT") {
                                                                        $selectedTxtStlmnt = "selected";
                                                                    }
                                                                ?>
                                                                    <option value="REPAYMENT" <?php echo $selectedTxtRpymnt; ?>>REPAYMENT</option>
                                                                    <option value="SETTLEMENT" <?php echo $selectedTxtStlmnt; ?>>SETTLEMENT</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <!--<div class="form-group form-group-sm">
                                                        <label for="docNum" class="control-label col-md-4">Document No:</label>
                                                        <div  class="col-md-8">
                                                            <input class="form-control" id="docNum" type = "text" placeholder="Document No" value="<?php echo $docNum; ?>" <?php echo $mkReadOnly; ?>/>
                                                        </div>
                                                    </div>-->  
                                                    <div class="form-group form-group-sm">
                                                        <label for="trnsAmount"  style="font-size: 20px !important;" class="control-label col-md-4">Amount:</label>
                                                        <input class="form-control" id="trnsAmntRaw" type = "hidden" min="0" placeholder="Amount Raw" value="<?php echo $cashAmount;
                                                ?>"/>
                                                        <input class="form-control" id="trnsAmntRaw1" type = "hidden" value="<?php
                                                               echo $cashAmount;
                                                               ?>"/>
                                                        <div  class="col-md-6">
                                                            <div class="input-group input-group-lg">
                                                                <label class="btn btn-info btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Currencies', '', '', '', 'radio', true, '<?php echo $crncyIDNm; ?>', 'mcfPymtCrncyNm', '', 'clear', 0, '', function () {
                                                                    $('#trnsAmountCrncy').html($('#mcfPymtCrncyNm').val());
                                                                    });">
                                                                    <span class="" style="font-size: 20px !important;" id="trnsAmountCrncy"><?php echo $crncyIDNm; ?> </span>
                                                                </label>
                                                                <input type="hidden" id="mcfPymtCrncyNm" value="<?php echo $crncyIDNm; ?>">
                                                                <input class="form-control" style="height:46px !important; font-size: 20px !important;text-align: right !important;" id="trnsAmount" type = "text" placeholder="" value="<?php
                                                echo number_format($cashAmount, 2);
                                                ?>" aria-describedby="trnsAmountCrncy" readonly="readonly"/>                                                               
                                                            </div>                                                                    
                                                        </div>
                                                        <div class="col-md-2" style="padding-left:0px !important;">
                                                            <button type="button" class="btn btn-default btn-lg" onclick="getCashBreakdown_LoanRepay('myFormsModaly', 'myFormsModalyBody', 'myFormsModalyTitle', 'cashDenominationsForm', '', 'Cash Breakdown', 15, '<?php echo $subPgNo; ?>', 2, '<?php echo $vwOrAdd; ?>');" style="width:100% !important;height: 46px !important;" title="Cash Breakdown">
                                                                <img src="cmn_images/cash_breakdown.png" style="left: 0.5%; padding-right: 5px; height:35px; width:auto; position: relative; vertical-align: middle;">
                                                            </button>
                                                        </div>
                                                    </div>   
                                                    <div class="form-group form-group-sm">
                                                        <label for="trnsDesc" class="control-label col-md-4">Narration:</label>
                                                        <div  class="col-md-8">
                                                            <div class="input-group">
                                                                <textarea class="form-control rqrdFld input-group-addon" rows="2" placeholder="Narration/Remarks" id="trnsDesc" name="trnsDesc" <?php echo $mkRmrkReadOnly; ?> style="text-align:left !important;"><?php echo $trnsDesc; ?></textarea>
                                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Sample Tellering Narrations', '', '', '', 'radio', true, '', '', 'trnsDesc', 'clear', 1, '');">
                                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div> 
                                                </fieldset>   
                                            </div>                                                
                                            <div class="col-lg-6">
                                                <fieldset class=""><legend class="basic_person_lg1">Account Details</legend>
                                                    <div class="form-group form-group-sm">
                                                        <label for="acctNo" class="control-label col-md-4">Account Number:</label>
                                                        <div class="col-md-8">
                                                            <input class="form-control" id="acctNo" type = "text" placeholder="" value="<?php echo $acctNo; ?>" readonly="readonly"/>  
                                                            <input class="form-control" id="acctID" placeholder="Account ID" type = "hidden" placeholder="" value="<?php echo $accntID; ?>"/>
                                                        </div>
                                                    </div>
                                                    <div class="form-group form-group-sm">
                                                        <label for="acctStatus" class="control-label col-md-4">Account Status:</label>
                                                        <div  class="col-md-8">
                                                            <input class="form-control" id="acctStatus" type = "text" placeholder="" value="<?php echo $acctStatus; ?>" readonly="readonly"/>
                                                        </div>
                                                    </div>
                                                    <div class="form-group form-group-sm">
                                                        <label for="acctCrncy" class="control-label col-md-4">Currency:</label>
                                                        <div  class="col-md-8">
                                                            <input class="form-control" id="acctCrncy" type = "text" placeholder="" value="<?php echo $acctCrncy; ?>" readonly="readonly"/>
                                                        </div>
                                                    </div>
                                                    <div class="form-group form-group-sm">
                                                        <label for="acctType" class="control-label col-md-4">Account Type:</label>
                                                        <div  class="col-md-8">
                                                            <input class="form-control" id="acctType" type = "text" placeholder="" value="<?php echo $acctType; ?>" readonly="readonly"/>
                                                        </div>
                                                    </div>
                                                    <div class="form-group form-group-sm">
                                                        <label for="acctCustomer" class="control-label col-md-4">Customer:</label>
                                                        <div  class="col-md-8">
                                                            <input class="form-control" id="acctCustomer" type = "text" placeholder="" value="<?php echo $acctCustomer; ?>" readonly="readonly"/>
                                                        </div>
                                                    </div>
                                                    <div style="display:none !important;" class="form-group form-group-sm">
                                                        <label for="prsnTypeEntity" class="control-label col-md-4">Person Type/Entity:</label>
                                                        <div  class="col-md-8">
                                                            <input class="form-control" id="prsnTypeEntity" type = "text" placeholder="" value="<?php echo $prsnTypeEntity; ?>" readonly="readonly"/>
                                                        </div>
                                                    </div>
                                                    <div class="form-group form-group-sm">
                                                        <label for="acctBranch" class="control-label col-md-4">Branch:</label>
                                                        <div class="col-md-8">
                                                            <input class="form-control" id="acctBranch" type = "text" placeholder="" value="<?php echo $accntFctrsLoc; ?>" readonly="readonly"/>
                                                        </div>
                                                    </div>
                                                    <div class="form-group form-group-sm">
                                                        <label for="acctLien" class="control-label col-md-4">Lien on Account:</label>
                                                        <div  class="col-md-8">
                                                            <input class="form-control" id="acctLien" type = "text" placeholder="" value="<?php
                                                echo number_format($acctLien, 2);
                                                ?>" readonly="readonly"/>
                                                        </div>
                                                    </div>
                                                    <div class="form-group form-group-sm">
                                                        <label for="loanRpmntSrcAcct" class="control-label col-md-4">Repayment Source:</label>
                                                        <div  class="col-md-8">
                                                            <div class="input-group col-md-12">
                                                                <div  class="col-md-7" style="padding-left:0px !important; padding-right: 0px !important; ">
                                                                    <div class="input-group">
                                                                        <input type="text" class="form-control" aria-label="..." id="loanRpmntSrcAcct" value="<?php echo $loanRpmntSrcAcct; ?>" readonly>
                                                                        <input type="hidden" id="loanRpmntSrcAcctID" value="<?php echo $loanRpmntSrcAcctID; ?>">
                                                                        <input type="hidden" id="bnkCustomerID" value="<?php echo $bnkCustomerID; ?>">
                                                                        <label class="btn btn-primary btn-file input-group-addon" onclick="listCustAccountsForRpmnt();">
                                                                            <span class="glyphicon glyphicon-th-list"></span>
                                                                        </label>
                                                                        <label class="btn btn-primary btn-file input-group-addon" onclick="javascript:$('#loanRpmntSrcAcctID').val(-1); $('#loanRpmntSrcAcct').val('');">
                                                                            <span class="glyphicon glyphicon-remove"></span>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <div  class="col-md-5" style="padding-right: 0px !important; padding-left:2px !important;">
                                                                    <div class="input-group">
                                                                        <label  for="loanRpmntSrcAmnt" class="btn btn-primary btn-file input-group-addon" onclick="">
                                                                            GHS
                                                                        </label>
                                                                        <input type="text" class="form-control rqrdFld" aria-label="..." id="loanRpmntSrcAmntDsply"  style="font-weight: bold; font-size: 14px !important;"  <?php echo $mkReadOnly; ?> onblur="formatAmount('loanRpmntSrcAmnt', 'loanRpmntSrcAmntDsply');" value="<?php echo $loanRpmntSrcAmnt; ?>">
                                                                        <input type="hidden" readonly="readonly" class="form-control rqrdFld" aria-label="..." id="loanRpmntSrcAmnt" value="<?php echo $row[55]; ?>" <?php echo $mkReadOnly; ?>>
                                                                        
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div> 
                                                </fieldset>
                                            </div>
                                        </div> 
                                        <fieldset class="" style="display:none !important;"><legend class="basic_person_lg1" style="color: #003245">Cheque Details</legend>
                <?php
                $dsplyNwLine = "";
                $nwRowHtml1 = urlencode("<tr id=\"oneVmsTrnsRow__WWW123WWW\">"
                        . "<td class=\"lovtd\"><span class=\"\">New</span></td>"
                        . "<td class=\"lovtd\" style=\"display:none;\">
                                                            <div class=\"input-group\" style=\"width:100% !important;\">
                                                                <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"oneVmsTrnsRow_WWW123WWW_chqType\" name=\"oneVmsTrnsRow_WWW123WWW_chqType\" value=\"In-House\" readonly=\"true\" style=\"width:100% !important;\">
                                                                <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneVmsTrnsRow_WWW123WWW_chqTypeID\" value=\"-1\" style=\"width:100% !important;\">                                              
                                                                <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneVmsTrnsRow_WWW123WWW_TrnsLnID\" value=\"-1\" style=\"width:100% !important;\">                                                                   
                                                                <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'MCF Deposit Cheque Types', '', '', '', 'radio', true, '', 'oneVmsTrnsRow_WWW123WWW_chqTypeID', 'oneVmsTrnsRow_WWW123WWW_chqType', 'clear', 1, '', function () {
                                                                       //afterVMSItemSlctn('oneVmsTrnsRow__WWW123WWW');
                                                                       var sltdVal = $('#oneVmsTrnsRow_WWW123WWW_chqType').val();
                                                                       if(sltdVal == 'In-House'){
                                                                            $('.extnl').css('display','none');
                                                                             $('#oneVmsTrnsRow_WWW123WWW_bnkNm').val('');
                                                                             $('#oneVmsTrnsRow_WWW123WWW_bnkID').val('');
                                                                             $('oneVmsTrnsRow_WWW123WWW_brnchNm').val('');
                                                                             $('oneVmsTrnsRow_WWW123WWW_brnchiD').val('');
                                                                       } else {
                                                                            $('.extnl').css('display','table-cell');
                                                                       }
                                                                       });\">
                                                                       <span class=\"glyphicon glyphicon-th-list\"></span>
                                                                </label>
                                                            </div> 
                                                        </td>
                                                        <td class=\"lovtd extnl\">
                                                            <div class=\"input-group\" style=\"width:100% !important;\">
                                                                <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneVmsTrnsRow_WWW123WWW_bnkNm\" name=\"oneVmsTrnsRow_WWW123WWW_bnkNm\" value=\"\" readonly=\"true\" style=\"width:100% !important;\">
                                                                <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneVmsTrnsRow_WWW123WWW_bnkID\" value=\"-1\" style=\"width:100% !important;\">                                               
                                                                <label  style=\"display:none;\" class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'MCF All Banks', 'gnrlOrgID', '', '', 'radio', true, '', 'oneVmsTrnsRow_WWW123WWW_bnkID', 'oneVmsTrnsRow_WWW123WWW_bnkNm', 'clear', 1, '', function () {
                                                                       //afterVMSItemSlctn('oneVmsTrnsRow__WWW123WWW');
                                                                       });\">
                                                                       <span class=\"glyphicon glyphicon-th-list\"></span>
                                                                </label>
                                                            </div> 
                                                            <span>In-House</span>                                             
                                                        </td>
                                                        <td class=\"lovtd extnl\">
                                                            <div class=\"input-group\" style=\"width:100% !important;\">
                                                                <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneVmsTrnsRow_WWW123WWW_brnchNm\" name=\"oneVmsTrnsRow_WWW123WWW_brnchNm\" value=\"\" readonly=\"true\" style=\"width:100% !important;\">
                                                                <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneVmsTrnsRow_WWW123WWW_brnchID\" value=\"-1\" style=\"width:100% !important;\">                                              
                                                                <label style=\"display:none;\" class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Bank Branches MCF', 'oneVmsTrnsRow_WWW123WWW_bnkID', '', '', 'radio', true, '', 'oneVmsTrnsRow_WWW123WWW_brnchID', 'oneVmsTrnsRow_WWW123WWW_brnchNm', 'clear', 1, '');\">
                                                                       <span class=\"glyphicon glyphicon-th-list\"></span>
                                                                </label>
                                                            </div> 
                                                            <span>In-House</span>
                                                        </td> 
                                                        <td class=\"lovtd\">
                                                            <input type=\"number\" class=\"form-control rqrdFld\" aria-label=\"...\" id=\"oneVmsTrnsRow_WWW123WWW_chqNo\" name=\"oneVmsTrnsRow_WWW123WWW_chqNo\" value=\"\" >                                                    
                                                        </td>
                                                        <td class=\"lovtd\">  
                                                            <div class=\"input-group date form_date\" data-date=\"\" data-date-format=\"dd-M-yyyy\" data-link-field=\"dtp_input2\" data-link-format=\"yyyy-mm-dd\">
                                                                <input type=\"text\" class=\"form-control\" size=\"16\" aria-label=\"...\" id=\"oneVmsTrnsRow_WWW123WWW_chqDte\" name=\"oneVmsTrnsRow_WWW123WWW_chqDte\" value=\"\" readonly=\"\">                                                                    
                                                                <span class=\"input-group-addon\"><span class=\"glyphicon glyphicon-calendar\"></span></span>
                                                            </div>
                                                        </td>                                                        
                                                        <td class=\"lovtd\">
                                                            <div class=\"\" style=\"width:100% !important;\">
                                                                <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneVmsTrnsRow_WWW123WWW_chqCurNm\" name=\"oneVmsTrnsRow_WWW123WWW_chqCurNm\" value=\"" . $acctCrncy . "\" readonly=\"true\" style=\"width:100% !important;\">
                                                                <label class=\"btn btn-primary btn-file\" onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Currencies', '', '', '', 'radio', true, '', 'oneVmsTrnsRow_WWW123WWW_chqCurNm', '', 'clear', 1, '', function () {
                                                                            $('#oneVmsTrnsRow_WWW123WWW_chqCurNm1').html($('#oneVmsTrnsRow_WWW123WWW_chqCurNm').val());
                                                                        });\">
                                                                       <span class=\"\" id=\"oneVmsTrnsRow_WWW123WWW_chqCurNm1\">" . $acctCrncy . "</span>
                                                                </label>
                                                            </div>                                              
                                                        </td>
                                                        <td class=\"lovtd\">
                                                            <input type=\"text\" class=\"form-control chqValCls rqrdFld\" aria-label=\"...\" id=\"oneVmsTrnsRow_WWW123WWW_chqVal\" name=\"oneVmsTrnsRow_WWW123WWW_chqVal\" value=\"0.00\" oninput=\"formatAmountTableGen('oneVmsTrnsRow__WWW123WWW','oneVmsTrnsRow_WWW123WWW_chqVal',6);\" style=\"width:100% !important;text-align: right;\">                                                    
                                                        </td>
                                                        <td class=\"lovtd\">
                                                            <input type=\"number\" class=\"form-control chqRatesCls rqrdFld\" aria-label=\"...\" id=\"oneVmsTrnsRow_WWW123WWW_exchngRate\" name=\"oneVmsTrnsRow_WWW123WWW_exchngRate\" value=\"1.0000\" >                                                    
                                                        </td>
                                                        <td class=\"lovtd\">  
                                                            <div class=\"input-group date form_date\" data-date=\"\" data-date-format=\"dd-M-yyyy\" data-link-field=\"dtp_input2\" data-link-format=\"yyyy-mm-dd\">
                                                                <input type=\"text\" class=\"form-control\" size=\"16\" aria-label=\"...\" id=\"oneVmsTrnsRow_WWW123WWW_chqValDte\" name=\"oneVmsTrnsRow_WWW123WWW_chqValDte\" value=\"\" readonly=\"\">                                                                    
                                                                <span class=\"input-group-addon\"><span class=\"glyphicon glyphicon-calendar\"></span></span>
                                                            </div>
                                                        </td>                                           
                                                        <td class=\"lovtd\">
                                                            <button type=\"button\" class=\"btn btn-default\" style=\"margin: 0px !important;padding:0px 3px 2px 4px !important;\" onclick=\"delTrnsChqLine('oneVmsTrnsRow__WWW123WWW');\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Delete Trns. Line\">
                                                                    <img src=\"cmn_images/no.png\" style=\"height:15px; width:auto; position: relative; vertical-align: middle;\">
                                                            </button>
                                                        </td>
                                                    </tr>");
                $nwRowHtml = urlencode("<tr id=\"oneVmsTrnsRow__WWW123WWW\">"
                        . "<td class=\"lovtd\"><span class=\"\">New</span></td>"
                        . "<td class=\"lovtd\" style=\"display:none;\">
                                                            <div class=\"input-group\" style=\"width:100% !important;\">
                                                                <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"oneVmsTrnsRow_WWW123WWW_chqType\" name=\"oneVmsTrnsRow_WWW123WWW_chqType\" value=\"External\" readonly=\"true\" style=\"width:100% !important;\">
                                                                <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneVmsTrnsRow_WWW123WWW_chqTypeID\" value=\"-1\" style=\"width:100% !important;\">                                              
                                                                <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneVmsTrnsRow_WWW123WWW_TrnsLnID\" value=\"-1\" style=\"width:100% !important;\">                                                                   
                                                                <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'MCF Deposit Cheque Types', '', '', '', 'radio', true, '', 'oneVmsTrnsRow_WWW123WWW_chqTypeID', 'oneVmsTrnsRow_WWW123WWW_chqType', 'clear', 1, '', function () {
                                                                       //afterVMSItemSlctn('oneVmsTrnsRow__WWW123WWW');
                                                                       var sltdVal = $('#oneVmsTrnsRow_WWW123WWW_chqType').val();
                                                                       if(sltdVal == 'In-House'){
                                                                            $('.extnl').css('display','none');
                                                                             $('#oneVmsTrnsRow_WWW123WWW_bnkNm').val('');
                                                                             $('#oneVmsTrnsRow_WWW123WWW_bnkID').val('');
                                                                             $('oneVmsTrnsRow_WWW123WWW_brnchNm').val('');
                                                                             $('oneVmsTrnsRow_WWW123WWW_brnchiD').val('');
                                                                       } else {
                                                                            $('.extnl').css('display','table-cell');
                                                                       }
                                                                       });\">
                                                                       <span class=\"glyphicon glyphicon-th-list\"></span>
                                                                </label>
                                                            </div> 
                                                        </td>
                                                        <td class=\"lovtd extnl\">
                                                            <div class=\"input-group\" style=\"width:100% !important;\">
                                                                <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"oneVmsTrnsRow_WWW123WWW_bnkNm\" name=\"oneVmsTrnsRow_WWW123WWW_bnkNm\" value=\"\" readonly=\"true\" style=\"width:100% !important;\">
                                                                <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneVmsTrnsRow_WWW123WWW_bnkID\" value=\"-1\" style=\"width:100% !important;\">                                               
                                                                <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'MCF All Banks', 'gnrlOrgID', '', '', 'radio', true, '', 'oneVmsTrnsRow_WWW123WWW_bnkID', 'oneVmsTrnsRow_WWW123WWW_bnkNm', 'clear', 1, '', function () {
                                                                       //afterVMSItemSlctn('oneVmsTrnsRow__WWW123WWW');
                                                                       });\">
                                                                       <span class=\"glyphicon glyphicon-th-list\"></span>
                                                                </label>
                                                            </div>                                              
                                                        </td>
                                                        <td class=\"lovtd extnl\">
                                                            <div class=\"input-group\" style=\"width:100% !important;\">
                                                                <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"oneVmsTrnsRow_WWW123WWW_brnchNm\" name=\"oneVmsTrnsRow_WWW123WWW_brnchNm\" value=\"\" readonly=\"true\" style=\"width:100% !important;\">
                                                                <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneVmsTrnsRow_WWW123WWW_brnchID\" value=\"-1\" style=\"width:100% !important;\">                                              
                                                                <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Bank Branches MCF', 'oneVmsTrnsRow_WWW123WWW_bnkID', '', '', 'radio', true, '', 'oneVmsTrnsRow_WWW123WWW_brnchID', 'oneVmsTrnsRow_WWW123WWW_brnchNm', 'clear', 1, '');\">
                                                                       <span class=\"glyphicon glyphicon-th-list\"></span>
                                                                </label>
                                                            </div> 
                                                        </td> 
                                                        <td class=\"lovtd\">
                                                            <input type=\"number\" class=\"form-control rqrdFld\" aria-label=\"...\" id=\"oneVmsTrnsRow_WWW123WWW_chqNo\" name=\"oneVmsTrnsRow_WWW123WWW_chqNo\" value=\"\" >                                                    
                                                        </td>
                                                        <td class=\"lovtd\">  
                                                            <div class=\"input-group date form_date\" data-date=\"\" data-date-format=\"dd-M-yyyy\" data-link-field=\"dtp_input2\" data-link-format=\"yyyy-mm-dd\">
                                                                <input type=\"text\" class=\"form-control\" size=\"16\" aria-label=\"...\" id=\"oneVmsTrnsRow_WWW123WWW_chqDte\" name=\"oneVmsTrnsRow_WWW123WWW_chqDte\" value=\"\" readonly=\"\">                                                                    
                                                                <span class=\"input-group-addon\"><span class=\"glyphicon glyphicon-calendar\"></span></span>
                                                            </div>
                                                        </td>                                                        
                                                        <td class=\"lovtd\">
                                                            <div class=\"\" style=\"width:100% !important;\">
                                                                <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneVmsTrnsRow_WWW123WWW_chqCurNm\" name=\"oneVmsTrnsRow_WWW123WWW_chqCurNm\" value=\"" . $acctCrncy . "\" readonly=\"true\" style=\"width:100% !important;\">
                                                                <label class=\"btn btn-primary btn-file\" onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Currencies', '', '', '', 'radio', true, '', 'oneVmsTrnsRow_WWW123WWW_chqCurNm', '', 'clear', 1, '', function () {
                                                                            $('#oneVmsTrnsRow_WWW123WWW_chqCurNm1').html($('#oneVmsTrnsRow_WWW123WWW_chqCurNm').val());
                                                                        });\">
                                                                       <span class=\"\" id=\"oneVmsTrnsRow_WWW123WWW_chqCurNm1\">" . $acctCrncy . "</span>
                                                                </label>
                                                            </div>                                              
                                                        </td>
                                                        <td class=\"lovtd\">
                                                            <input type=\"text\" class=\"form-control chqValCls rqrdFld\" aria-label=\"...\" id=\"oneVmsTrnsRow_WWW123WWW_chqVal\" name=\"oneVmsTrnsRow_WWW123WWW_chqVal\" value=\"0.00\" oninput=\"formatAmountTableGen('oneVmsTrnsRow__WWW123WWW','oneVmsTrnsRow_WWW123WWW_chqVal',6);\" style=\"width:100% !important;text-align: right;\">                                                    
                                                        </td>
                                                        <td class=\"lovtd\">
                                                            <input type=\"number\" class=\"form-control chqRatesCls rqrdFld\" aria-label=\"...\" id=\"oneVmsTrnsRow_WWW123WWW_exchngRate\" name=\"oneVmsTrnsRow_WWW123WWW_exchngRate\" value=\"1.0000\" >                                                    
                                                        </td>
                                                        <td class=\"lovtd\">  
                                                            <div class=\"input-group date form_date\" data-date=\"\" data-date-format=\"dd-M-yyyy\" data-link-field=\"dtp_input2\" data-link-format=\"yyyy-mm-dd\">
                                                                <input type=\"text\" class=\"form-control\" size=\"16\" aria-label=\"...\" id=\"oneVmsTrnsRow_WWW123WWW_chqValDte\" name=\"oneVmsTrnsRow_WWW123WWW_chqValDte\" value=\"\" readonly=\"\">                                                                    
                                                                <span class=\"input-group-addon\"><span class=\"glyphicon glyphicon-calendar\"></span></span>
                                                            </div>
                                                        </td>                                           
                                                        <td class=\"lovtd\">
                                                            <button type=\"button\" class=\"btn btn-default\" style=\"margin: 0px !important;padding:0px 3px 2px 4px !important;\" onclick=\"delTrnsChqLine('oneVmsTrnsRow__WWW123WWW');\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Delete Trns. Line\">
                                                                    <img src=\"cmn_images/no.png\" style=\"height:15px; width:auto; position: relative; vertical-align: middle;\">
                                                            </button>
                                                        </td>
                                                    </tr>");
                ?> 
                                            <?php if ($trnsStatus == "Not Submitted" || $trnsStatus == "Withdrawn" || $trnsStatus == "Rejected") { ?>
                                                <div class="row" style="padding:0px 0px 0px 0px !important;">
                                                    <div class="<?php echo $dsplyPymntCls1; ?>" style="padding:0px 0px 0px 15px !important;float:left;">
                                                        <button type="button" class="btn btn-default" style="margin-bottom: 5px;<?php echo $dsplyNwLine; ?>" onclick="insertNewTrnsChqsRows('oneVmsTrnsLnsTable', 0, '<?php echo $nwRowHtml1; ?>');" data-toggle="tooltip" data-placement="bottom" title = "New In-House Cheque Line">
                                                            <img src="cmn_images/add1-64.png" style="height:20px; width:auto; position: relative; vertical-align: middle;"> New In-House Cheque
                                                        </button>
                                                        <button type="button" class="btn btn-default" style="margin-bottom: 5px;<?php echo $dsplyNwLine; ?>" onclick="insertNewTrnsChqsRows('oneVmsTrnsLnsTable', 0, '<?php echo $nwRowHtml; ?>');" data-toggle="tooltip" data-placement="bottom" title = "New External Cheque Line">
                                                            <img src="cmn_images/add1-64.png" style="height:20px; width:auto; position: relative; vertical-align: middle;"> New External Cheque
                                                        </button>
                                                    </div>
                                                </div>
                <?php } ?>
                                            <div class="row">
                                                <div class="col-md-12" id="oneVmsTrnsLnsTblSctn">
                                                    <table class="table table-striped table-bordered table-responsive" id="oneVmsTrnsLnsTable" cellspacing="0" width="100%" style="width:100%;min-width: 900px !important;">
                                                        <thead>
                                                            <tr>
                                                                <th>No.</th>
                                                                <th style="display:none;">Type</th>
                                                                <th class="extnl">Bank</th>
                                                                <th class="extnl">Branch</th>
                                                                <th>CHQ. No.</th>
                                                                <th>CHQ. Date</th>
                                                                <th>CUR.</th>
                                                                <th>CHQ. Amount</th>
                                                                <th>Rate</th>
                                                                <th>Value Date</th>
                <?php if ($trnsStatus == "Not Submitted" || $trnsStatus == "Withdrawn" || $trnsStatus == "Rejected") { ?>
                                                                    <th>&nbsp;</th>
                                                                <?php } ?>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                <?php
                $acntChqsRslt = getAccountTrnsChqs($sbmtdTrnsHdrID);
                $cntr = 0;
                while ($rwChqs = loc_db_fetch_array($acntChqsRslt)) {
                    $cntr++;
                    $chqType = $rwChqs[9];
                    $inptType = "text";
                    $dsplyLable = "";
                    $dsplySpan = "display:none;";
                    if ($chqType != "External") {
                        $inptType = "hidden";
                        $dsplyLable = "display:none;";
                        $dsplySpan = "";
                    }
                    ?>
                                                                <tr id="oneVmsTrnsRow_<?php echo $cntr; ?>">
                                                                    <td class="lovtd"><span class=""><?php echo $cntr; ?></span></td>
                                                                    <td class="lovtd" style="display:none;">
                                                                        <div class="input-group" style="width:100% !important;">
                                                                            <input type="text" class="form-control" aria-label="..." id="oneVmsTrnsRow<?php echo $cntr; ?>_chqType" name="oneVmsTrnsRow<?php echo $cntr; ?>_chqType" value="<?php echo $rwChqs[9]; ?>" readonly="true" style="width:100% !important;">
                                                                            <input type="hidden" class="form-control" aria-label="..." id="oneVmsTrnsRow<?php echo $cntr; ?>_chqTypeID" value="<?php echo $rwChqs[11]; ?>" style="width:100% !important;">                                              
                                                                            <input type="hidden" class="form-control" aria-label="..." id="oneVmsTrnsRow<?php echo $cntr; ?>_TrnsLnID" value="<?php echo $rwChqs[0]; ?>" style="width:100% !important;">                                                                   
                                                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'MCF Deposit Cheque Types', '', '', '', 'radio', true, '', 'oneVmsTrnsRow<?php echo $cntr; ?>_chqTypeID', 'oneVmsTrnsRow<?php echo $cntr; ?>_chqType', 'clear', 1, '', function () {
                                                                                //afterVMSItemSlctn('oneVmsTrnsRow_<?php echo $cntr; ?>');
                                                                                var sltdVal = $('#oneVmsTrnsRow<?php echo $cntr; ?>_chqType').val();
                                                                                if (sltdVal == 'In-House') {
                                                                                $('.extnl').css('display', 'none');
                                                                                $('#oneVmsTrnsRow<?php echo $cntr; ?>_bnkNm').val('');
                                                                                $('#oneVmsTrnsRow<?php echo $cntr; ?>_bnkID').val('');
                                                                                $('oneVmsTrnsRow<?php echo $cntr; ?>_brnchNm').val('');
                                                                                $('oneVmsTrnsRow<?php echo $cntr; ?>_brnchiD').val('');
                                                                                } else {
                                                                                $('.extnl').css('display', 'table-cell');
                                                                                }
                                                                                });">
                                                                                <span class="glyphicon glyphicon-th-list"></span>
                                                                            </label>
                                                                        </div> 
                                                                    </td>
                                                                    <td class="lovtd extnl">
                                                                        <div class="input-group" style="width:100% !important;">
                                                                            <input type="<?php echo $inptType; ?>" class="form-control" aria-label="..." id="oneVmsTrnsRow<?php echo $cntr; ?>_bnkNm" name="oneVmsTrnsRow<?php echo $cntr; ?>_bnkNm" value="<?php echo $rwChqs[3]; ?>" readonly="true" style="width:100% !important;">
                                                                            <input type="hidden" class="form-control" aria-label="..." id="oneVmsTrnsRow<?php echo $cntr; ?>_bnkID" value="<?php echo $rwChqs[2]; ?>" style="width:100% !important;">                                               
                                                                            <label style="<?php echo $dsplyLable; ?>" class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'MCF All Banks', 'gnrlOrgID', '', '', 'radio', true, '', 'oneVmsTrnsRow<?php echo $cntr; ?>_bnkID', 'oneVmsTrnsRow<?php echo $cntr; ?>_bnkNm', 'clear', 1, '', function () {
                                                                                //afterVMSItemSlctn('oneVmsTrnsRow_<?php echo $cntr; ?>');
                                                                                });">
                                                                                <span class="glyphicon glyphicon-th-list"></span>
                                                                            </label>
                                                                        </div> 
                                                                        <span style="<?php echo $dsplySpan; ?>"><?php echo $chqType; ?></span>
                                                                    </td>
                                                                    <td class="lovtd extnl">
                                                                        <div class="input-group" style="width:100% !important;">
                                                                            <input type="<?php echo $inptType; ?>" class="form-control" aria-label="..." id="oneVmsTrnsRow<?php echo $cntr; ?>_brnchNm" name="oneVmsTrnsRow<?php echo $cntr; ?>_brnchNm" value="<?php echo $rwChqs[5]; ?>" readonly="true" style="width:100% !important;">
                                                                            <input type="hidden" class="form-control" aria-label="..." id="oneVmsTrnsRow<?php echo $cntr; ?>_brnchID" value="<?php echo $rwChqs[4]; ?>" style="width:100% !important;">                                              
                                                                            <label style="<?php echo $dsplyLable; ?>" class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Bank Branches MCF', 'oneVmsTrnsRow<?php echo $cntr; ?>_bnkID', '', '', 'radio', true, '', 'oneVmsTrnsRow<?php echo $cntr; ?>_brnchID', 'oneVmsTrnsRow<?php echo $cntr; ?>_brnchNm', 'clear', 1, '');">
                                                                                <span class="glyphicon glyphicon-th-list"></span>
                                                                            </label>
                                                                        </div> 
                                                                        <span style="<?php echo $dsplySpan; ?>"><?php echo $chqType; ?></span> 
                                                                    </td> 
                                                                    <td class="lovtd">
                                                                        <input type="number" class="form-control rqrdFld" aria-label="..." id="oneVmsTrnsRow<?php echo $cntr; ?>_chqNo" name="oneVmsTrnsRow<?php echo $cntr; ?>_chqNo" value="<?php echo $rwChqs[6]; ?>" <?php echo $mkReadOnly; ?>>                                                    
                                                                    </td>
                                                                    <td class="lovtd">  
                                                                        <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                                                            <input type="text" class="form-control" size="16" aria-label="..." id="oneVmsTrnsRow<?php echo $cntr; ?>_chqDte" name="oneVmsTrnsRow<?php echo $cntr; ?>_chqDte" value="<?php echo $rwChqs[10]; ?>" readonly="true">                                                                    
                                                                            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                                        </div>
                                                                    </td>                                                        
                                                                    <td class="lovtd">
                                                                        <div class="" style="width:100% !important;">
                                                                            <input type="hidden" class="form-control" aria-label="..." id="oneVmsTrnsRow<?php echo $cntr; ?>_chqCurNm" name="oneVmsTrnsRow<?php echo $cntr; ?>_chqCurNm" value="<?php echo $rwChqs[14]; ?>" readonly="true" style="width:100% !important;">
                                                                            <label class="btn btn-primary btn-file" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Currencies', '', '', '', 'radio', true, '', 'oneVmsTrnsRow<?php echo $cntr; ?>_chqCurNm', '', 'clear', 1, '', function () {
                                                                                $('#oneVmsTrnsRow<?php echo $cntr; ?>_chqCurNm1').html($('#oneVmsTrnsRow<?php echo $cntr; ?>_chqCurNm').val());
                                                                                });">
                                                                                <span class="" id="oneVmsTrnsRow<?php echo $cntr; ?>_chqCurNm1"><?php echo $rwChqs[14]; ?></span>
                                                                            </label>
                                                                        </div>                                              
                                                                    </td>
                                                                    <td class="lovtd">
                                                                        <input type="text" class="form-control chqValCls rqrdFld" aria-label="..." id="oneVmsTrnsRow<?php echo $cntr; ?>_chqVal" name="oneVmsTrnsRow<?php echo $cntr; ?>_chqVal" value="<?php
                                                        echo number_format((float) $rwChqs[7], 2);
                                                        ?>" style="width:100% !important;text-align: right;" <?php echo $mkReadOnly; ?>/>                                                    
                                                                    </td>
                                                                    <td class="lovtd">
                                                                        <input type="number" class="form-control chqRatesCls rqrdFld" aria-label="..." id="oneVmsTrnsRow<?php echo $cntr; ?>_exchngRate" name="oneVmsTrnsRow<?php echo $cntr; ?>_exchngRate" value="<?php
                                                        echo number_format((float) $rwChqs[15], 4);
                                                        ?>" <?php echo $mkReadOnly; ?>>                                                    
                                                                    </td>
                                                                    <td class="lovtd">  
                                                                        <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                                                            <input type="text" class="form-control" size="16" aria-label="..." id="oneVmsTrnsRow<?php echo $cntr; ?>_chqValDte" name="oneVmsTrnsRow<?php echo $cntr; ?>_chqValDte" value="<?php echo $rwChqs[8]; ?>" readonly="true">                                                                    
                                                                            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                                        </div>
                                                                    </td>   
                    <?php if ($trnsStatus == "Not Submitted" || $trnsStatus == "Withdrawn" || $trnsStatus == "Rejected") { ?>
                                                                        <td class="lovtd">
                                                                            <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delTrnsChqLine_LoanRpmnt('oneVmsTrnsRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Trns. Line">
                                                                                <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                                            </button>
                                                                        </td>
                    <?php } ?>
                                                                </tr>                                                    
                                                                <?php } ?>
                                                        </tbody>
                                                    </table>   
                                                </div>
                                            </div>
                                        </fieldset>
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <fieldset class=""><legend class="basic_person_lg1">Depositor's Details</legend>
                                                    <div class="form-group form-group-sm">
                                                        <label for="trnsPersonType" class="control-label col-lg-4">Deposited By:</label>
                                                        <div  class="col-lg-8">
                <?php
                $chkdSelf = "";
                $chkdOthers = "checked=\"\"";
                if ($trnsPersonType == "Self") {
                    $chkdOthers = "";
                    $chkdSelf = "checked=\"\"";
                }
                ?>
                                                            <label class="radio-inline"><input type="radio" name="trnsPersonType" onclick="trnsPrsnTypeChng();" value="Self" <?php echo $chkdSelf; ?>>Self</label>
                                                            <label class="radio-inline"><input type="radio" name="trnsPersonType" onclick="trnsPrsnTypeChng();" value="Others" <?php echo $chkdOthers; ?>>Others</label>                                                               
                                                        </div>
                                                    </div>
                                                    <div class="form-group form-group-sm">
                                                        <label for="trnsPersonName" class="control-label col-md-4">Person Name:</label>
                                                        <div class="col-md-8">
                                                            <input class="form-control rqrdFld" id="trnsPersonName" type = "text" placeholder="" value="<?php echo $trnsPersonName; ?>" <?php echo $mkReadOnly; ?>/>
                                                        </div>
                                                    </div>
                                                    <div class="form-group form-group-sm">
                                                        <label for="trnsPersonTelNo" class="control-label col-md-4">Mobile No:</label>
                                                        <div class="col-md-8">
                                                            <input class="form-control rqrdFld" id="trnsPersonTelNo" type = "text" placeholder="" value="<?php echo $trnsPersonTelNo; ?>" <?php echo $mkReadOnly; ?>/>
                                                        </div>
                                                    </div>
                                                </fieldset>
                                            </div>
                                            <div class="col-lg-6">
                                                <fieldset class=""><legend class="basic_person_lg1">Depositor's Details</legend>                                                        
                                                    <div class="form-group form-group-sm">
                                                        <label for="trnsPersonAddress" class="control-label col-md-4">Address:</label>
                                                        <div class="col-md-8">
                                                            <input class="form-control" id="trnsPersonAddress" type = "text" placeholder="" value="<?php echo $trnsPersonAddress; ?>" <?php echo $mkReadOnly; ?>/>
                                                        </div>
                                                    </div>
                                                    <div class="form-group form-group-sm">
                                                        <label for="trnsPersonIDType" class="control-label col-md-4">ID Type:</label>
                                                        <div class="col-md-8">
                                                            <select class="form-control rqrdFld" id="trnsPersonIDType" <?php echo $mkReadOnly; ?>>  
                                                                <option value="">Please Select...</option>
                <?php
                $brghtStr = "";
                $isDynmyc = FALSE;
                $titleRslt = getLovValues("%", "Both", 0, 100, $brghtStr, getLovID("National ID Types"), $isDynmyc, -1, "", "");
                while ($titleRow = loc_db_fetch_array($titleRslt)) {
                    $selectedTxt = "";
                    if ($trnsPersonIDType == $titleRow[0]) {
                        $selectedTxt = "selected";
                    }
                    ?>
                                                                    <option value="<?php echo $titleRow[0]; ?>" <?php echo $selectedTxt; ?>><?php echo $titleRow[0]; ?></option>
                                                                    <?php
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="form-group form-group-sm">
                                                        <label for="trnsPersonIDNumber" class="control-label col-md-4">ID Number:</label>
                                                        <div class="col-md-8">
                                                            <input class="form-control rqrdFld" id="trnsPersonIDNumber" type = "text" placeholder="" value="<?php echo $trnsPersonIDNumber; ?>" <?php echo $mkReadOnly; ?>/>
                                                        </div>
                                                    </div>
                                                </fieldset>
                                            </div>                                                                 
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-12"> 
                                                <fieldset class="" style=""><legend class="basic_person_lg1" id="docTypeDtls">Historic Account Transactions</legend> 
                                                    <div  class="col-md-12" style="padding:0px !important;">
                                                        <table id="acctHistoryTblAdd" class="table table-striped table-bordered table-responsive" cellspacing="0" width="100%" style="width:100%;">
                                                            <thead>
                                                                <tr>
                                                                    <th>&nbsp;</th>
                                                                    <th>Date</th>
                                                                    <th>Transaction Type & Description</th>
                                                                    <th>Trns. No.</th>
                                                                    <th style="text-align:right;min-width: 120px;">Amount</th>
                                                                    <th style="text-align:right;min-width: 120px;">Current Bals. (After Trns.)</th>
                                                                    <th style="text-align:right;min-width: 120px;">Available Bals. (After Trns.)</th>
                                                                    <th>Status</th>
                                                                    <th>Authorizer</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="acctHistoryTblTbody">
                <?php
                $acntHstryRslt = get_OneCustAccntHstry($accntID);
                while ($rwHstry = loc_db_fetch_array($acntHstryRslt)) {
                    ?>
                                                                    <tr>
                                                                        <td class="lovtd">
                                                                            <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Transaction Details" onclick="getOneVmsTrnsForm(<?php echo $rwHstry[0]; ?>, '<?php echo $rwHstry[5]; ?>', 30, 'ShowDialog',<?php echo $vwtyp; ?>);" style="padding:2px !important;">
                                                                                <img src="cmn_images/kghostview.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                            </button>
                                                                        </td>
                                                                        <td class="lovtd"><?php echo $rwHstry[11]; ?></td>
                                                                        <td class="lovtd"><?php echo $rwHstry[5] . " @" . $rwHstry[48] . " - " . $rwHstry[31] . " [" . $rwHstry[15] . " - " . $rwHstry[16] . "]"; ?></td>
                                                                        <td class="lovtd"><?php echo $rwHstry[12]; ?></td>
                                                                        <td class="lovtd" style="text-align:right;font-weight:bold;"><?php
                                                echo $rwHstry[10] . " " . number_format((float) $rwHstry[6], 2);
                                                ?></td>
                                                                        <td class="lovtd" style="text-align:right;font-weight:bold;color:blue;"><?php
                                                                            echo $rwHstry[10] . " " . number_format((float) $rwHstry[37], 2);
                                                                            ?></td>
                                                                        <td class="lovtd" style="text-align:right;font-weight:bold;color:blue;"><?php
                                                                            echo $rwHstry[10] . " " . number_format((float) $rwHstry[38], 2);
                                                                            ?></td>
                                                                        <td class="lovtd"><?php echo $rwHstry[7]; ?></td>
                                                                        <td class="lovtd"><?php echo $rwHstry[34]; ?></td>
                                                                    </tr>
                <?php } ?>
                                                            </tbody>
                                                        </table>
                                                    </div> 
                                                </fieldset>
                                            </div>                                                                
                                        </div>                                              
                                    </form>  
                                </div>                          
                            </div>                         
                        </div>                
                    </div> 
                </fieldset>
                <?php
            } else if ($vwtypActn == "VIEW") {
                getMCFTrnsRdOnlyDsply($sbmtdTrnsHdrID, $trnsType);
            } else if ($vwtypActn == "FIND") {
                //header("content-type:application/json");

                $acctNo = $PKeyID;
                $acctDetArray = array();

                //validate account
                $valRslt = validateAccountNoLoanRepay($acctNo);

                if ($valRslt == -1) {
                    echo "INVALID ACCOUNT NUMBER";
                    exit;
                }

                $result = getAccountDetails($acctNo);

                while ($row = loc_db_fetch_array($result)) {

                    $acctDetArray = array('accountID' => $row[0], 'accountNo' => $row[1], 'status' => $row[2],
                        'currID' => $row[3], 'acctType' => $row[4], 'custId' => $row[5], 'prsnTypeEntity' => $row[7], 'branchId' => $row[13],
                        'acctTitle' => $row[9], 'mandate' => $row[10],
                        'currNm' => $row[12], 'bnkCustomerID' => $row[14]);
                }

                $result2 = getAccountSignatories($acctNo);
                $noOfRec = loc_db_num_rows($result2);
                $acctSgntrsArray = array();



                for ($i = 0; $i < $noOfRec; $i++) {

                    $row = loc_db_fetch_array($result2);
                    $bioType = "";
                    if ($row[4] == "Other Persons") {
                        $bioType = "OTH";
                    } else if ($row[4] == "Individual Customers") {
                        $bioType = "IND";
                    }
                    $bioData = "<span style=\"\">No FingerPrint Data</span>";
                    if ($bioType != "") {
                        $bioUserID = isBioDataPrsnt($row[2], $bioType);
                        if ($bioUserID > 0) {
                            $bioUser = getBioData($row[2], $bioType);
                            foreach ($bioUser as $rowBio) {
                                $finger = getUserBioFinger($rowBio['user_id']);
                                $verification = '';
                                $url_verification = base64_encode($bio_base_path . "verification.php?user_id=" . $rowBio['user_id']);
                                if (count($finger) == 0) {
                                    
                                } else {
                                    $verification = "<a href='finspot:FingerspotVer;$url_verification' class='btn btn-sm btn-success'>Verify Thumbprint</a>";
                                }
                                $bioData = "<code id='user_finger_" . $rowBio['user_id'] . "' style=\"display:none;\">" . count($finger) . "</code>"
                                        . "$verification";
                            }
                        }
                    }
                    $acctSgntrsArray[$i] = array('id' => $row[0], 'name' => $row[1], "toSignMndtry" => $row[3], "bioData" => $bioData);
                }

                $clrdBal = getAccountBal($acctNo, 'Cleared');
                $unclrdBal = getAccountBal($acctNo, 'Uncleared');

                $acctBalsArray = array('clrBal' => $clrdBal, 'unclrBal' => $unclrdBal);

                $result3 = getAccountWithdrawalLimitInfo($acctNo);
                $acctWdrwlLimitArray = array();

                while ($row3 = loc_db_fetch_array($result3)) {
                    $acctWdrwlLimitArray = array('limitNo' => $row3[0], 'limitAmount' => $row3[1]);
                }

                $result4 = getHistoricAccountTrns($acctNo); //, 'WITHDRAWAL'

                $noOfHistoryRec = loc_db_num_rows($result4);
                $acctTrnsHistoryArray = array();

                for ($j = 0; $j < $noOfHistoryRec; $j++) {

                    $row4 = loc_db_fetch_array($result4);
                    $acctTrnsHistoryArray[$j] = array('acctTrnsId' => $row4[0],
                        'trnsDate' => $row4[11],
                        'trnsDesc' => $row4[5] . " @" . $row4[48] . " - " . $row4[31] . " [" . $row4[15] . " - " . $row4[16] . "]",
                        'trnsNo' => $row4[12],
                        'amount' => $row4[10] . " " . number_format((float) $row4[6], 2),
                        'netClrdBal' => $row4[10] . " " . number_format((float) $row4[37], 2),
                        'netUnclrdBal' => $row4[10] . " " . number_format((float) $row4[38], 2),
                        'status' => $row4[7],
                        'authorizer' => $row4[34],
                        'trnsType' => $row4[5],
                        'vwType' => "0");
                    /* $acctTrnsHistoryArray[$i] = array('acctTrnsId' => $row4[0], 'trnsDate' => $row4[1], 'crncy' => $row4[2],
                      'docType' => $row4[3], 'docNo' => $row4[4], 'amount' => $row4[5], 'netClrdBal' => $row4[6],
                      'netUnclrdBal' => $row4[7], 'status' => $row4[8]); */
                }

                $response = array('accountDetails' => $acctDetArray,
                    'signatories' => $acctSgntrsArray, 'accountBalance' => $acctBalsArray,
                    'accountWdrwlLimit' => $acctWdrwlLimitArray, 'acctTrnsHistory' => $acctTrnsHistoryArray);

                //var_dump($response);                

                echo json_encode($response);
                exit;
            }
        } else if ($vwtyp == "1") {
            
        } else if ($vwtyp == "2") {
            /* CASH ANALYSIS */
            if ($vwtypActn == "VIEW") {
                /* Read Only */
                $acctTrnsId = $PKeyID;
                $mcfPymtCrncyNm = isset($_POST['mcfPymtCrncyNm']) ? cleanInputData($_POST['mcfPymtCrncyNm']) : $fnccurnm;
                if ($mcfPymtCrncyNm == "") {
                    $mcfPymtCrncyNm = $fnccurnm;
                }
                $mcfPymtCrncyID = getPssblValID($mcfPymtCrncyNm, getLovID("Currencies"));
                $mcfCrncyID = (int) getGnrlRecNm("mcf.mcf_currencies", "mapped_lov_crncy_id", "crncy_id", $mcfPymtCrncyID);
                $usedVltID = -1;
                $usedCageID = -1;
                $usedCageNm = "";
                $usedVltNm = "";
                $itemState = "Issuable";
                $extrInputFlds = "";
                $capturedItemIDs = "";
                $dsplyBalance = "";
                $usedInvAcntID = -1;
                $dfltItemState = "Issuable";
                if ($usedVltID <= 0 && $usedCageID <= 0) {
                    $pID = getLatestCage($prsnid, $usedCageID, $usedVltID, $usedInvAcntID, $mcfPymtCrncyID);
                    $usedCageNm = getGnrlRecNm("inv.inv_shelf", "line_id", "shelve_name", $usedCageID);
                    $usedVltNm = getGnrlRecNm("inv.inv_itm_subinventories", "subinv_id", "subinv_name", $usedVltID);
                    $dfltItemState = getGnrlRecNm("inv.inv_shelf", "line_id", "dflt_item_state", $usedCageID);
                }
                ?>
                <form class="form-horizontal" id="cashDenominationsForm" style="padding:5px;">
                    <input type="hidden" class="form-control" aria-label="..." id="cashDenominationsID" value="">
                    <input type="hidden" class="form-control" aria-label="..." id="cashDenominationsTtl" value="">
                    <input type="hidden" class="form-control" aria-label="..." id="cashDenominationsTtlRaw" value="">
                    <input type="hidden" class="form-control" aria-label="..." id="cashDenominationsTtlRaw1" value="">
                    <input type="hidden" class="form-control" aria-label="..." id="cashDenominationsTtlFmtd" value="">
                    <div class="row">
                        <div class=""> 
                            <fieldset style="padding: 1px !important;">
                                <!--<legend class="basic_person_lg">Total:<span id="cashBreakdownLgnd" style="color:red;"><?php
                echo $mcfPymtCrncyNm . " " . number_format(get_TransCashAnalysisTtlAmount($acctTrnsId, 1), 2);
                ?></span>
                                </legend>-->                              
                                <table id="cashBreakdownTblEDT" class="table table-striped table-bordered table-responsive" cellspacing="0" width="100%" style="width:100%;">
                                    <thead>
                                        <tr>
                                            <th style="max-width:100px;width:100px;">Type</th>
                                            <th style="max-width:100px;width:100px;">Denom.</th>
                                            <th style="max-width:100px;width:100px;">Pieces</th>
                                            <th>Total Amount</th>
                                            <th style="display:none;">Value</th>
                                            <th style="display:none;">Unit Value</th>                                           
                                            <th style="display:none;">...</th>
                                            <th style="display:none;">...</th>
                                            <th style="display:none;">...</th>
                                            <th>Exchange Rate</th>
                                            <th style="<?php echo $dsplyBalance; ?>">Running Balance</th>
                                            <th>Transaction Type</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                <?php
                $dateStr = getDB_Date_time();
                //check existence of account transaction
                if ($acctTrnsId == -1) {
                    createInitAccountTrns($pAcctID, $dateStr, $trnsType);
                    $acctTrnsId = getInitAccountTrnsID($pAcctID, $dateStr);
                }
                $exists = checkExstncOfCashBrkdwnForTrnsID($acctTrnsId, $orgID);
                if ($exists > 0) {
                    $delSQL = "DELETE FROM mcf.mcf_account_trns_cash_analysis WHERE acct_trns_id=" . $acctTrnsId .
                            " and denomination_id NOT IN (SELECT crncy_denom_id FROM mcf.mcf_currency_denominations WHERE crncy_id = $mcfCrncyID)";
                    execUpdtInsSQL($delSQL);
                    $exists = checkExstncOfCashBrkdwnForTrnsID($acctTrnsId, $orgID);
                }
                if ($exists <= 0) {
                    createCashBreakdownInit($acctTrnsId, $mcfCrncyID, $usedVltID, $usedCageID, $dfltItemState);
                }
                $mcfAccntCrncyID = getAccountLovCrncyID($pAcctID);
                $dateStr1 = getFrmtdDB_Date_time();
                $exchangeRate1 = round(get_LtstBNKExchRate($mcfPymtCrncyID, $mcfAccntCrncyID, $dateStr1), 15);
                ?>
                                    <input class="form-control" id="initAcctTrnsId" placeholder="Init Account Trns ID" type = "hidden" placeholder="" value="<?php echo $acctTrnsId; ?>"/>
                                        <?php
                                        $result1 = get_TransCashAnalysisRO($acctTrnsId, $mcfCrncyID);
                                        //$result1 = get_CurrencyDenoms(1); //get_CurrencyDenoms($crncyID);
                                        $cntr = 0;
                                        $ttlRows = loc_db_num_rows($result1);
                                        $ttlAmount = 0;
                                        $ttlQty = 0;
                                        while ($row1 = loc_db_fetch_array($result1)) {
                                            $cntr++;
                                            $qty = $row1[6];
                                            $lineTrnsType = "Payment";
                                            
                                            if((int) $qty < 0){
                                                $qty = -1 * (int)$row1[6];
                                                $lineTrnsType = "Refund";
                                            }     
                                            
                                            $ttlVal = 0;
                                            if ($row1[1] == 'Coin') {
                                                if ((float) $row1[7] == 0) {
                                                    $ttlVal = "";
                                                } else {
                                                    $ttlVal = number_format($row1[7], 2);
                                                    $ttlAmount = $ttlAmount + (float) $row1[7];
                                                }
                                            } else {
                                                if ((float) $row1[7] == 0) {
                                                    $ttlVal = "";
                                                } else {
                                                    $ttlVal = number_format($row1[7]);
                                                    $ttlAmount = $ttlAmount + (float) $row1[7];
                                                }
                                            }
                                            if ($row1[11] != 1 && $row1[11] != 0) {
                                                $exchangeRate = number_format($row1[11], 4);
                                            } else {
                                                $exchangeRate = number_format($exchangeRate1, 6);
                                            }
                                            $runningBalance = (float) $row1[12];
                                            $ttlQty += (float) $row1[6];
                                            if ($ttlVal != "" && ((int) $row1[9]) > 0) {//&& $usedVltID <= 0 && $usedCageID <= 0) {
                                                $usedVltID = (int) $row1[8];
                                                $usedCageID = (int) $row1[9];
                                                $itemState = $row1[10];
                                                $usedCageNm = getGnrlRecNm("inv.inv_shelf", "line_id", "shelve_name", $usedCageID);
                                                $usedVltNm = getGnrlRecNm("inv.inv_itm_subinventories", "subinv_id", "subinv_name", $usedVltID);
                                            }
                                            ?>
                                        <tr id="cashBreakdownRow_<?php echo $cntr; ?>">
                                            <td class="lovtd"><?php echo $row1[1]; ?></td>
                                            <td class="lovtd"><?php echo $row1[2]; ?></td>
                                            <td class="lovtd"><input class="cbQty form-control" onfocus="initCashBreakdownForm(<?php echo $cntr; ?>, <?php echo $ttlRows; ?>);" id="cashBreakdownRow<?php echo $cntr; ?>_denomQty" type = "number" min="0" placeholder="Quantity" value="<?php echo $qty; ?>" style="text-align: right;width:100% !important;" readonly="true"/></td>
                                            <td class="lovtd"><input class="cbTTlAmnt form-control" onfocus="initCashBreakdownForm(<?php echo $cntr; ?>, <?php echo $ttlRows; ?>);" id="cashBreakdownRow<?php echo $cntr; ?>_ttlAmnt" type = "text" placeholder="Total Amount" value="<?php echo $ttlVal; ?>" style="text-align: right;width:100% !important;" readonly="true"/></td>                                                    
                                            <td class="lovtd" style="display:none;"><?php echo $ttlVal; ?></td>
                                            <td id="cashBreakdownRow<?php echo $cntr; ?>_value" class="lovtd" style="display:none;"><?php echo $row1[3]; ?></td>
                                            <td id="cashBreakdownRow<?php echo $cntr; ?>_denomID" class="lovtd" style="display:none;"><?php echo $row1[0]; ?></td>
                                            <td id="cashBreakdownRow<?php echo $cntr; ?>_cashAnalysisID" class="lovtd" style="display:none;"><?php echo $row1[5]; ?></td>
                                            <td id="cashBreakdownRow<?php echo $cntr; ?>_ttlAmntRaw" class="lovtd" style="display:none;"><?php echo $row1[7]; ?></td>
                                            <td class="lovtd"><input class="cbExchngRate form-control" onfocus="initCashBreakdownForm(<?php echo $cntr; ?>, <?php echo $ttlRows; ?>);" id="cashBreakdownRow<?php echo $cntr; ?>_ExchngRate" type = "number" placeholder="Exchange Rate" value="<?php echo $exchangeRate; ?>" style="text-align: right;width:100% !important;" readonly="true"/></td>    
                                            <td class="lovtd" style="<?php echo $dsplyBalance; ?>">
                                                <input type="text" class="form-control cbRnngBal" aria-label="..." id="cashBreakdownRow<?php echo $cntr; ?>_RnngBal" name="cashBreakdownRow<?php echo $cntr; ?>_RnngBal" value="<?php
                                                echo number_format($runningBalance, 2);
                                                ?>" style="width:100% !important;text-align: right;font-weight:bold;color:blue;" readonly="true"> 
                                                <?php
                                                       if (strpos($capturedItemIDs, ";" . $row1[0] . ";") === FALSE) {
                                                           $capturedItemIDs = ";" . $row1[0] . ";";
                                                           $extrInputFlds .= "<input type=\"hidden\" id=\"cashBreakdownDenom_" . $row1[0] . "\" name=\"cashBreakdownDenom_" . $row1[0] . "\" value=\"" . number_format($runningBalance, 2) . "\" readonly=\"true\">";
                                                       }
                                                       ?>
                                            </td>
                                            <td class="lovtd"  style="">
                                                <select data-placeholder="Select..." class="lnTrnsType form-control chosen-select" id="cashBreakdownRow<?php echo $cntr; ?>_lnTrnsType" style="width:100% !important;" style="min-width:78px !important;">
                                                    <?php
                                                    $valslctdArry = array("", "");
                                                    $srchInsArrys = array("Payment", "Refund");
                                                    for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                        if ($lineTrnsType == $srchInsArrys[$z]) {
                                                            $valslctdArry[$z] = "selected";
                                                        }
                                                        ?>
                                                        <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </td>
                                        </tr>
                <?php } ?>
                                    </tbody>
                                    <tfoot>                                                            
                                        <tr>
                                            <th>&nbsp;</th>
                                            <th>TOTALS:</th>
                                            <th style="text-align: right;">
                                                <?php
                                                echo "<span style=\"color:blue;\" id=\"mcfCptrdCbQtyTtlBtn\">" . number_format($ttlQty, 0, '.', ',') . "</span>";
                                                ?>
                                                <input type="hidden" id="myCptrdQtyTtlVal" value="<?php echo $nwQty; ?>">
                                            </th>
                                            <th style="text-align: right;">
                                                <?php
                                                echo "<span style=\"color:red;font-weight:bold;\" id=\"mcfCptrdCbValsTtlBtn\">" . number_format($ttlAmount, 2, '.', ',') . "</span>";
                                                ?>
                                                <input type="hidden" id="myCptrdUmValsTtlVal" value="<?php echo $ttlPrce; ?>">
                                            </th>
                                            <th style="display:none;">&nbsp;</th>
                                            <th style="display:none;">&nbsp;</th>                                           
                                            <th style="display:none;">&nbsp;</th>
                                            <th style="display:none;">&nbsp;</th>
                                            <th style="display:none;">&nbsp;</th>
                                            <th>&nbsp;</th>
                                            <th style="<?php echo $dsplyBalance; ?>">&nbsp;</th>
                                        </tr>
                                    </tfoot>
                                </table>
                                <div id="rnngBalsMcfHiddenFields">
                                    <?php echo $extrInputFlds; ?>
                                </div>  
                                <div class="col-md-12">
                                    <div class="form-group form-group-sm" style="margin-top:5px;">
                                        <div class = "col-md-3" style="padding:0px 1px 0px 1px !important;">
                                            <label style="margin-bottom:0px !important;padding:2px 0px 0px 5px !important;">Destination Till:&nbsp;</label>
                                        </div>
                                        <div class = "col-md-3" style="padding:0px 1px 0px 1px !important;">
                                            <div class="input-group">
                                                <input type="text" class="form-control" aria-label="..." id="bnkPymtDfltVlt" name="bnkPymtDfltVlt" value="<?php echo $usedVltNm; ?>" readonly="true">
                                                <input type="hidden" id="bnkPymtDfltVltID" value="<?php echo $usedVltID; ?>">
                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'VMS Vaults', 'gnrlVmsPymtOrgID', '', '', 'radio', true, '<?php echo $usedVltID; ?>', 'bnkPymtDfltVltID', 'bnkPymtDfltVlt', 'clear', 1, '');">
                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class = "col-md-3" style="padding:0px 1px 0px 1px !important;">
                                            <div class="input-group">
                                                <input type="text" class="form-control" aria-label="..." id="bnkPymtDfltCage" name="bnkPymtDfltCage" value="<?php echo $usedCageNm; ?>" readonly="true">
                                                <input type="hidden" id="bnkPymtDfltCageID" value="<?php echo $usedCageID; ?>">
                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'VMS Vault Cages', 'bnkPymtDfltVltID', 'bnkPymtFrmCrncyNm', '', 'radio', true, '<?php echo $usedCageID; ?>', 'bnkPymtDfltCageID', 'bnkPymtDfltCage', 'clear', 1, '');">
                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class = "col-md-3" style="padding:0px 1px 0px 1px !important;">
                                            <div class="input-group">
                                                <input type="text" class="form-control" aria-label="..." id="bnkPymtDfltItemState" name="bnkPymtDfltItemState" value="<?php echo $itemState; ?>" readonly="true">
                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Vault Item States', '', '', '', 'radio', true, '', '', 'bnkPymtDfltItemState', 'clear', 1, '', function () {
                                                    //alert('Item Selected');
                                                    });">
                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                    </div>
                    <div class="row" style="float:right;padding-right: 1px;">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </form>
                <?php
            } 
            else if ($vwtypActn == "ADD") {
                /* Add */
                $acctTrnsId = $PKeyID;
                $mcfPymtCrncyNm = isset($_POST['mcfPymtCrncyNm']) ? cleanInputData($_POST['mcfPymtCrncyNm']) : $fnccurnm;
                if ($mcfPymtCrncyNm == "") {
                    $mcfPymtCrncyNm = $fnccurnm;
                }
                $mcfPymtCrncyID = getPssblValID($mcfPymtCrncyNm, getLovID("Currencies"));
                $mcfCrncyID = (int) getGnrlRecNm("mcf.mcf_currencies", "mapped_lov_crncy_id", "crncy_id", $mcfPymtCrncyID);
                $ttlPrce = 0;
                $usedVltID = -1;
                $usedCageID = -1;
                $usedCageNm = "";
                $usedVltNm = "";
                $itemState = "Issuable";
                $extrInputFlds = "";
                $capturedItemIDs = "";
                $dsplyBalance = "";
                $usedInvAcntID = -1;
                $dfltItemState = "Issuable";
                if ($usedVltID <= 0 && $usedCageID <= 0) {
                    $pID = getLatestCage($prsnid, $usedCageID, $usedVltID, $usedInvAcntID, $mcfPymtCrncyID);
                    $usedCageNm = getGnrlRecNm("inv.inv_shelf", "line_id", "shelve_name", $usedCageID);
                    $usedVltNm = getGnrlRecNm("inv.inv_itm_subinventories", "subinv_id", "subinv_name", $usedVltID);
                    $dfltItemState = getGnrlRecNm("inv.inv_shelf", "line_id", "dflt_item_state", $usedCageID);
                }
                ?>
                <form class="form-horizontal" id="cashDenominationsForm" style="padding:5px;">
                    <input type="hidden" class="form-control" aria-label="..." id="cashDenominationsID" value="">
                    <input type="hidden" class="form-control" aria-label="..." id="cashDenominationsTtl" value="">
                    <input type="hidden" class="form-control" aria-label="..." id="cashDenominationsTtlRaw" value="">
                    <input type="hidden" class="form-control" aria-label="..." id="cashDenominationsTtlRaw1" value="">
                    <input type="hidden" class="form-control" aria-label="..." id="cashDenominationsTtlFmtd" value="">
                    <div class="row">
                        <div class=""> 
                            <fieldset style="padding: 1px !important;">
                                <!--<legend class="basic_person_lg">Total:<span id="cashBreakdownLgnd" style="color:red;"><?php
                echo $mcfPymtCrncyNm . " " . number_format(get_TransCashAnalysisTtlAmount($acctTrnsId, 1), 2);
                ?></span>
                                </legend>-->                              
                                <table id="cashBreakdownTblEDT" class="table table-striped table-bordered table-responsive" cellspacing="0" width="100%" style="width:100%;">
                                    <thead>
                                        <tr>
                                            <th style="max-width:100px;width:100px;">Type</th>
                                            <th style="max-width:100px;width:100px;">Denom.</th>
                                            <th style="max-width:100px;width:100px;">Pieces</th>
                                            <th>Total Amount</th>
                                            <th style="display:none;">Value</th>
                                            <th style="display:none;">Unit Value</th>                                           
                                            <th style="display:none;">...</th>
                                            <th style="display:none;">...</th>
                                            <th style="display:none;">...</th>
                                            <th>Exchange Rate</th>
                                            <th style="<?php echo $dsplyBalance; ?>">Running Balance</th>
                                            <th>Transaction Type</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                <?php
                $dateStr = getDB_Date_time();
                //check existence of account transaction
                if ($acctTrnsId == -1) {
                    createInitAccountTrns($pAcctID, $dateStr, 'LOAN_REPAY');
                    $acctTrnsId = getInitAccountTrnsID($pAcctID, $dateStr);
                }
                $exists = checkExstncOfCashBrkdwnForTrnsID($acctTrnsId, $orgID);
                if ($exists > 0) {
                    $delSQL = "DELETE FROM mcf.mcf_account_trns_cash_analysis WHERE acct_trns_id=" . $acctTrnsId .
                            " and denomination_id NOT IN (SELECT crncy_denom_id FROM mcf.mcf_currency_denominations WHERE crncy_id = $mcfCrncyID)";
                    execUpdtInsSQL($delSQL);
                    $exists = checkExstncOfCashBrkdwnForTrnsID($acctTrnsId, $orgID);
                }
                if ($exists <= 0) {
                    createCashBreakdownInit($acctTrnsId, $mcfCrncyID, $usedVltID, $usedCageID, $dfltItemState);
                }
                $mcfAccntCrncyID = getAccountLovCrncyID($pAcctID);
                $dateStr1 = getFrmtdDB_Date_time();
                $exchangeRate1 = round(get_LtstBNKExchRate($mcfPymtCrncyID, $mcfAccntCrncyID, $dateStr1), 15);
                ?>
                                    <input class="form-control" id="initAcctTrnsId" placeholder="Init Account Trns ID" type = "hidden" placeholder="" value="<?php echo $acctTrnsId; ?>"/>
                                        <?php
                                        $result1 = get_TransCashAnalysis($acctTrnsId, $mcfCrncyID);
                                        //$result1 = get_CurrencyDenoms(1); //get_CurrencyDenoms($crncyID);
                                        $cntr = 0;
                                        $ttlRows = loc_db_num_rows($result1);
                                        $ttlAmount = 0;
                                        $ttlQty = 0;
                                        
                                        
                                        while ($row1 = loc_db_fetch_array($result1)) {
                                            $cntr++;
                                            $qty = $row1[6];
                                            $lnAmnt = $row1[7];
                                            $lineTrnsType = "Payment";
                                            
                                            if((float) $qty < 0){
                                                $qty = -1 * (float)$row1[6];
                                                $lnAmnt = -1 * (float)$row1[7];
                                                $lineTrnsType = "Refund";
                                                
                                            }                                            
                                            
                                            $ttlVal = 0;
                                            if ($row1[1] == 'Coin') {
                                                if ((float) $row1[7] == 0) {
                                                    $ttlVal = "";
                                                } else {
                                                    $ttlVal = number_format($lnAmnt, 2);
                                                    
                                                    if($lineTrnsType == "Refund"){
                                                        $ttlAmount = $ttlAmount - $lnAmnt;                                                        
                                                    } else {
                                                        $ttlAmount = $ttlAmount + (float) $lnAmnt;
                                                    }
                                                }
                                            } else {
                                                if ((float) $row1[7] == 0) {
                                                    $ttlVal = "";
                                                } else {
                                                    $ttlVal = number_format($lnAmnt);
                                                    
                                                    if($lineTrnsType == "Refund"){
                                                        $ttlAmount = $ttlAmount - $lnAmnt;                                                        
                                                    } else {
                                                        $ttlAmount = $ttlAmount + (float) $lnAmnt;
                                                    }
                                                }
                                            }
                                            if ($row1[11] != 1 && $row1[11] != 0) {
                                                $exchangeRate = number_format($row1[11], 4);
                                            } else {
                                                $exchangeRate = number_format($exchangeRate1, 6);
                                            }
                                            $runningBalance = (float) $row1[12];
                                            $ttlQty += (float) $qty;
                                            if ($ttlVal != "" && ((int) $row1[9]) > 0) {//$usedVltID <= 0 && $usedCageID <= 0) {
                                                $usedVltID = (int) $row1[8];
                                                $usedCageID = (int) $row1[9];
                                                $itemState = $row1[10];
                                                $usedCageNm = getGnrlRecNm("inv.inv_shelf", "line_id", "shelve_name", $usedCageID);
                                                $usedVltNm = getGnrlRecNm("inv.inv_itm_subinventories", "subinv_id", "subinv_name", $usedVltID);
                                            }
                                            ?>
                                        <tr id="cashBreakdownRow_<?php echo $cntr; ?>">
                                            <td class="lovtd"><?php echo $row1[1]; ?></td>
                                            <td class="lovtd"><?php echo $row1[2]; ?></td>
                                            <td class="lovtd"><input class="cbQty form-control" onchange="calcCashBreadownRowVal_LoanRepay('cashBreakdownRow_<?php echo $cntr; ?>', 'cbQty');" onfocus="initCashBreakdownForm(<?php echo $cntr; ?>, <?php echo $ttlRows; ?>);" id="cashBreakdownRow<?php echo $cntr; ?>_denomQty" type = "number" min="0" placeholder="Quantity" value="<?php echo $qty; ?>" style="text-align: right;width:100% !important;"/></td>
                                            <td class="lovtd"><input class="cbTTlAmnt form-control" onchange="calcCashBreadownRowVal_LoanRepay('cashBreakdownRow_<?php echo $cntr; ?>', 'cbTTlAmnt');" onfocus="initCashBreakdownForm(<?php echo $cntr; ?>, <?php echo $ttlRows; ?>);" id="cashBreakdownRow<?php echo $cntr; ?>_ttlAmnt" type = "text" placeholder="Total Amount" value="<?php echo $ttlVal; ?>" style="text-align: right;width:100% !important;"/></td>                                                    
                                            <td class="lovtd" style="display:none;"><?php echo $ttlVal; ?></td>
                                            <td id="cashBreakdownRow<?php echo $cntr; ?>_value" class="lovtd" style="display:none;"><?php echo $row1[3]; ?></td>
                                            <td id="cashBreakdownRow<?php echo $cntr; ?>_denomID" class="lovtd" style="display:none;"><?php echo $row1[0]; ?></td>
                                            <td id="cashBreakdownRow<?php echo $cntr; ?>_cashAnalysisID" class="lovtd" style="display:none;"><?php echo $row1[5]; ?></td>
                                            <td id="cashBreakdownRow<?php echo $cntr; ?>_ttlAmntRaw" class="lovtd" style="display:none;"><?php echo $row1[7]; ?></td>
                                            <td class="lovtd"><input class="cbExchngRate form-control" onchange="calcCashBreadownRowVal('cashBreakdownRow_<?php echo $cntr; ?>', 'cbExchngRate');" onfocus="initCashBreakdownForm(<?php echo $cntr; ?>, <?php echo $ttlRows; ?>);" id="cashBreakdownRow<?php echo $cntr; ?>_ExchngRate" type = "number" placeholder="Exchange Rate" value="<?php echo $exchangeRate; ?>" style="text-align: right;width:100% !important;"/></td>  
                                            <td class="lovtd" style="<?php echo $dsplyBalance; ?>">
                                                <input type="text" class="form-control cbRnngBal" aria-label="..." id="cashBreakdownRow<?php echo $cntr; ?>_RnngBal" name="cashBreakdownRow<?php echo $cntr; ?>_RnngBal" value="<?php
                                                    echo number_format($runningBalance, 2);
                                                    ?>" style="width:100% !important;text-align: right;font-weight:bold;color:blue;" readonly="true"> 
                                                <?php
                                                       if (strpos($capturedItemIDs, ";" . $row1[0] . ";") === FALSE) {
                                                           $capturedItemIDs = ";" . $row1[0] . ";";
                                                           $extrInputFlds .= "<input type=\"hidden\" id=\"cashBreakdownDenom_" . $row1[0] . "\" name=\"cashBreakdownDenom_" . $row1[0] . "\" value=\"" . number_format($runningBalance, 2) . "\" readonly=\"true\">";
                                                       }
                                                       ?>
                                            </td>
                                            <td class="lovtd"  style="">
                                                <select data-placeholder="Select..." class="lnTrnsType form-control chosen-select" id="cashBreakdownRow<?php echo $cntr; ?>_lnTrnsType" style="min-width:78px !important;" onchange="calcCashBreadownRowVal_LoanRepay('cashBreakdownRow_<?php echo $cntr; ?>', 'lnTrnsType');">
                                                    <?php
                                                    $valslctdArry = array("", "");
                                                    $srchInsArrys = array("Payment", "Refund");
                                                    for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                        if ($lineTrnsType == $srchInsArrys[$z]) {
                                                            $valslctdArry[$z] = "selected";
                                                        }
                                                        ?>
                                                        <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </td>
                                        </tr>
                <?php } ?>
                                    </tbody>
                                    <tfoot>                                                            
                                        <tr>
                                            <th>&nbsp;</th>
                                            <th>TOTALS:</th>
                                            <th style="text-align: right;">
                <?php
                echo "<span style=\"color:blue;\" id=\"mcfCptrdCbQtyTtlBtn\">" . number_format($ttlQty, 0, '.', ',') . "</span>";
                ?>
                                                <input type="hidden" id="myCptrdQtyTtlVal" value="<?php echo $nwQty; ?>">
                                            </th>
                                            <th style="text-align: right;">
                <?php
                echo "<span style=\"color:red;font-weight:bold;\" id=\"mcfCptrdCbValsTtlBtn\">" . number_format($ttlAmount, 2, '.', ',') . "</span>";
                ?>
                                                <input type="hidden" id="myCptrdUmValsTtlVal" value="<?php echo $ttlPrce; ?>">
                                            </th>
                                            <th style="display:none;">&nbsp;</th>
                                            <th style="display:none;">&nbsp;</th>                                           
                                            <th style="display:none;">&nbsp;</th>
                                            <th style="display:none;">&nbsp;</th>
                                            <th style="display:none;">&nbsp;</th>
                                            <th>&nbsp;</th>
                                            <th style="<?php echo $dsplyBalance; ?>">&nbsp;</th>
                                        </tr>
                                    </tfoot>
                                </table>
                                <div id="rnngBalsMcfHiddenFields">
                <?php echo $extrInputFlds; ?>
                                </div>                            
                                <div class="col-md-12">
                                    <div class="form-group form-group-sm" style="margin-top:5px;">
                                        <div class = "col-md-3" style="padding:0px 1px 0px 1px !important;">
                                            <label style="margin-bottom:0px !important;padding:2px 0px 0px 5px !important;">Destination Till:&nbsp;</label>
                                        </div>
                                        <div class = "col-md-3" style="padding:0px 1px 0px 1px !important;">
                                            <div class="input-group">
                                                <input type="text" class="form-control rqrdFld" aria-label="..." id="bnkPymtDfltVlt" name="bnkPymtDfltVlt" value="<?php echo $usedVltNm; ?>" readonly="true">
                                                <input type="hidden" id="bnkPymtDfltVltID" value="<?php echo $usedVltID; ?>">
                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'VMS Vaults', 'gnrlVmsPymtOrgID', '', '', 'radio', true, '<?php echo $usedVltID; ?>', 'bnkPymtDfltVltID', 'bnkPymtDfltVlt', 'clear', 1, '');">
                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class = "col-md-3" style="padding:0px 1px 0px 1px !important;">
                                            <div class="input-group">
                                                <input type="text" class="form-control rqrdFld" aria-label="..." id="bnkPymtDfltCage" name="bnkPymtDfltCage" value="<?php echo $usedCageNm; ?>" readonly="true">
                                                <input type="hidden" id="bnkPymtDfltCageID" value="<?php echo $usedCageID; ?>">
                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'VMS Vault Cages', 'bnkPymtDfltVltID', 'bnkPymtFrmCrncyNm', '', 'radio', true, '<?php echo $usedCageID; ?>', 'bnkPymtDfltCageID', 'bnkPymtDfltCage', 'clear', 1, '');">
                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class = "col-md-3" style="padding:0px 1px 0px 1px !important;">
                                            <div class="input-group">
                                                <input type="text" class="form-control rqrdFld" aria-label="..." id="bnkPymtDfltItemState" name="bnkPymtDfltItemState" value="<?php echo $itemState; ?>" readonly="true">
                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Vault Item States', '', '', '', 'radio', true, '', '', 'bnkPymtDfltItemState', 'clear', 1, '', function () {
                                                    //alert('Item Selected');
                                                    });">
                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                    </div>
                    <div class="row" style="float:right;padding-right: 1px;">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" onclick="saveCashDenominationForm_LoanRepay('myFormsModaly', '<?php echo $subPgNo; ?>');">Save Changes</button>
                    </div>
                </form>
                <?php
            } else if ($vwtypActn == "EDIT") {
                /* Edit Only */
            }
        }
    } 
    else if ($subPgNo == 4.4) {//LOAN CALCULATOR
            $cntent .= "
					<li onclick=\"openATab('#allmodules', 'grp=17&typ=1&pg=4');\">
                                                <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
						<span style=\"text-decoration:none;\">Credit Management Menu</span>
                                        </li>";
        echo $cntent .= "
					<li onclick=\"openATab('#allmodules', 'grp=17&typ=1&pg=4&subPgNo=4.4');\">
                                                <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
						<span style=\"text-decoration:none;\">Loan Calculator</span>
                                        </li></div>";            
        ?>
        <div class="row">                  
            <div class="col-md-12">
                <div id="loanCalcFormDiv" style="border:none !important;">                                            
                    <form class="form-horizontal" id="loanCalcForm">
                        <div style="display:none;">
                            <!--DATA FOR VALIDATIONS-->
                            <input type="hidden" id="currencyId" value="">
                            <input type="hidden" id="isAccountRqrd" value="">
                            <input type="hidden" id="loanMaxAmount" value="">
                            <input type="hidden" id="loanMinAmount" value="">
                            <input type="hidden" id="maxLoanTenor" value="">
                            <input type="hidden" id="maxLoanTenorType" value="">
                            <input type="hidden" id="guarantorRequired" value="">
                            <input type="hidden" id="guarantorNo" value="">
                            <input type="hidden" id="productType" value="">
                            <input type="hidden" id="custTypeCustgrp" value="">
                            <input type="hidden" id="custTypeCorp" value="">
                            <input type="hidden" id="custTypeInd" value="">
                            <input type="hidden" id="cashCollateralRqrd" value="">
                            <input type="hidden" id="valueFlatCashColt" value="">
                            <input type="hidden" id="valuePrcntCashColt" value="">                                                 
                            <input type="hidden" id="prptyCollateralRqrd" value="">
                            <input type="hidden" id="valueFlatPrptyColt" value="">
                            <input type="hidden" id="valuePrcntPrptyColt" value="">
                            <input type="hidden" id="invstmntCollateralRqrd" value="">
                            <input type="hidden" id="valueInvstmntCashColt" value="">
                            <input type="hidden" id="valueInvstmntPrcntColt" value="">   
                            <input type="hidden" id="minLoanTenor" value="">
                            <input type="hidden" id="minLoanTenorType" value="">
                            <input type="hidden" id="baseIntRate" value="">
                            
                        </div>
                        <div class="row"><!-- ROW 2 -->
                            <div class="col-lg-12">
                                <legend class="basic_person_lg1" style="color: #003245">LOANS CALCULATOR</legend>                                                    
                                <div class="row"><!-- ROW 1 -->
                                    <div class="col-lg-6">
                                        <fieldset class="basic_person_fs1"><legend class="basic_person_lg">Request Details</legend>
                                            <div class="form-group form-group-sm">
                                                <label for="custType" class="control-label col-md-4">Customer Type:</label>
                                                <input type="hidden" id="custTypeYes" value="YES">
                                                <div  class="col-md-8">
                                                    <select class="form-control" id="custType" onchange="">
                                                        <option value="Not Applicable">Not Applicable</option>
                                                        <option value="Corporate">Corporate</option>
                                                        <option value="Individual">Individual</option>
                                                        <option value="Group">Customer Group</option>
                                                    </select>
                                                </div>
                                            </div>                                            
                                            <div class="form-group form-group-sm">
                                                <label for="crdtType" class="control-label col-md-4">Credit Type:</label>
                                                <div  class="col-md-8">
                                                    <select class="form-control" id="crdtType" onchange="onChangeCrdtType();">
                                                        <option value="Not Applicable">Not Applicable</option>
                                                        <option value="Loan">Loan</option>
                                                        <option value="Overdraft">Overdraft</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group form-group-sm">
                                                <label for="prdtType" class="control-label col-md-4">Product:</label>
                                                <div  class="col-md-8">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" aria-label="..." id="prdtType" value="" readonly="readonly">
                                                        <input type="hidden" id="prdtTypeID" value="-1">
                                                        <input type="hidden" id="gnrlOrgID" value="<?php echo $orgID; ?>">
                                                        <label class="btn btn-primary btn-file input-group-addon" onclick="onClickProductLoanCalc(15,4.1);">
                                                            <span class="glyphicon glyphicon-th-list"></span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div> 
                                            <div class="form-group form-group-sm">
                                                <label for="loanAmount" class="control-label col-md-4">Amount:</label>
                                                <div  class="col-md-8">
                                                    <div class="input-group">
                                                        <label id="crncyIsoCode" class="btn btn-primary btn-file input-group-addon">
                                                        </label>		
                                                        <input class="form-control" id="loanAmount" type = "number" min="0" placeholder="Amount" value=""/>
                                                    </div>
                                                </div>
                                            </div>                                                                 
                                            <div class="form-group form-group-sm">
                                                <label for="loanTenor" class="control-label col-md-4">Tenure:</label>
                                                <div  class="col-md-8">
                                                    <div class="input-group col-md-12">
                                                        <div  class="col-md-4" style="padding-left:0px !important;">
                                                            <input class="form-control" id="loanTenor" type = "number" min="0" placeholder="" value=""/>
                                                        </div>
                                                        <div  class="col-md-8" style="padding-left:0px !important; padding-right: 0px !important; ">
                                                            <select class="form-control" id="loanTenorType" >
                                                                <option value="Year(s)" selected>Year(s)</option>
                                                                <option value="Month(s)">Month(s)</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>                                                            
                                            </div> 
                                            <div class="form-group form-group-sm">
                                                <label for="interestRate" class="control-label col-md-4">Interest Rate:</label>
                                                <div  class="col-md-8">
                                                    <div class="input-group">
                                                        <input class="form-control" id="interestRate" type = "number" min="0" placeholder="Interest Rate" value=""/>
                                                        <label class="btn btn-primary btn-file input-group-addon" style="padding-left: 2px !important;">
                                                            % Per Annum
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group form-group-sm">
                                                <label for="intRateType" class="control-label col-md-4">Interest Rate Type:</label>
                                                <div  class="col-md-8">
                                                    <select class="form-control" id="intRateType" onchange="">
                                                        <option value="Reducing Rate" selected>Reducing Rate - Reducing Interest</option>
                                                        <option value="Flat Rate">Flat Rate - Fixed Principal and Interest</option> 
                                                    </select>
                                                </div>
                                            </div>                                            
                                            <div class="form-group form-group-sm">
                                                <label for="compound" class="control-label col-md-4">Compound:</label>
                                                <div  class="col-md-8">
                                                    <select class="form-control" id="compound" onchange="">
                                                        <option value="year" selected>Annually</option>
                                                        <option value="semiannually">Semi-Annually</option> 
                                                        <option value="quarterly">Quarterly</option>  
                                                        <option value="monthly">Monthly</option>  
                                                        <option value="semimonthly">Semi-Monthly</option> 
                                                        <option value="biweekly">BiWeekly</option>  
                                                        <option value="weekly">Weekly</option>
                                                        <option value="daily">Daily</option>
                                                        <option value="simple">Simple Interest</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group form-group-sm">
                                                <label for="payBack" class="control-label col-md-4">Pay Back:</label>
                                                <div  class="col-md-8">
                                                    <select class="form-control" id="payBack" onchange="">
                                                        <option value="daily">Every Day</option>
                                                        <option value="weekly">Every Week</option>
                                                        <option value="biweekly">Every 2 Weeks</option>
                                                        <option value="halfmonth">Every Half Month</option>
                                                        <option value="month" selected>Every Month</option>
                                                        <option value="quarter">Every Quarter</option>
                                                        <option value="halfyear">Every 6 Months</option>
                                                        <option value="year">Every Year</option>                                                        
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group form-group-sm">
                                                <label for="gracePeriodNo" class="control-label col-md-4">Grace Period:</label>
                                                <div  class="col-md-8">
                                                    <div class="input-group col-md-12">
                                                        <div  class="col-md-4" style="padding-left:0px !important;">
                                                            <input class="form-control" id="gracePeriodNo" type = "number" min="0" placeholder="" value=""/>
                                                        </div>
                                                        <div  class="col-md-8" style="padding-left:0px !important; padding-right: 0px !important; ">
                                                            <select class="form-control" id="gracePeriodType" >
                                                                <option value="day" selected>Day(s)</option>
                                                                <option value="week">Week(s)</option>
                                                                <option value="month" selected>Month(s)</option>
                                                                <option value="year">Year(s)</option>                                                                
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>                                                            
                                            </div> 
                                            <div class="form-group form-group-sm">
                                                <label for="dsbmntDate" class="control-label col-md-4">Disbursement Date:</label>
                                                <div  class="col-md-8">
                                                    <div class="col-md-8" style="padding-left:0px !important;">
                                                        <div class='input-group date' id='datetimepicker1' data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                                            <input type='text' class="form-control" size="16" id="dsbmntDate" placeholder="DD-Mon-YYYY"/> 
                                                            <span class="input-group-addon">
                                                                <span class="glyphicon glyphicon-calendar"></span>
                                                            </span>
                                                        </div>                                                       
                                                    </div>
                                                    <div class="col-md-4" style="padding-right:0px !important;">
                                                        <div style="padding: 0px 1px 0px 1px !important; float: right !important">
                                                            <button type="button" class="btn btn-default btn-sm" style="height:30px;" onclick="openATab('#allmodules', 'grp=17&typ=1&pg=4&subPgNo=4.4');"><img src="cmn_images/undo_256.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">&nbsp;</button>
                                                            <button type="button" class="btn btn-default btn-sm" style="height:30px;" onclick="calcLoanAmortization(15, 4.1, '7');"><img src="cmn_images/98.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">&nbsp;</button>
                                                        </div>
                                                    </div>                                                   
                                                </div>
                                            </div>
                                        </fieldset>
                                    </div>
                                    <div class="col-lg-6">
                                        <div id ="amrtiztnSchdlDiv">
                                        <fieldset class="basic_person_fs1"><legend class="basic_person_lg">Schedule Details</legend>
                                            <div class="form-group form-group-sm">
                                                <label for="prdcPayAmount" class="control-label col-md-5">Payment <span id="payBackType"></span>:</label>
                                                <div  class="col-md-7">
                                                    <div class="input-group">
                                                        <label id="crncyIsoCode2" class="btn btn-primary btn-file input-group-addon">
                                                        </label>		
                                                        <input class="form-control" id="prdcPayAmount" type = "text" min="0" placeholder="" value="" readonly="true"/>
                                                        <input style="display:none !important;" class="form-control" id="prdcPayAmountRaw" type = "text" min="0" placeholder="" value="" readonly="true"/>
                                                    </div>
                                                </div>
                                            </div>                                                                 
                                            <div class="form-group form-group-sm">
                                                <label for="ttlPayments" class="control-label col-md-5">Total of <span id="ttlLoanTermTimes"></span> Payments:</label>
                                                <div  class="col-md-7">
                                                    <div class="input-group">
                                                        <label id="crncyIsoCode3" class="btn btn-primary btn-file input-group-addon">
                                                        </label>		
                                                        <input class="form-control" id="ttlPayments" type = "text" min="0" placeholder="" value="" readonly="true"/>
                                                    </div>
                                                </div>                                                            
                                            </div> 
                                            <div class="form-group form-group-sm">
                                                <label for="ttlInterest" class="control-label col-md-5">Total Interest:</label>
                                                <div  class="col-md-7">
                                                    <div class="input-group">
                                                        <label id="crncyIsoCode4" class="btn btn-primary btn-file input-group-addon">
                                                        </label>		
                                                        <input class="form-control" id="ttlInterest" type = "text" min="0" placeholder="" value="" readonly="true"/>
                                                    </div>
                                                </div>
                                            </div>     
                                            <div class="form-group form-group-sm">
                                                <label for="startDate" class="control-label col-md-5">Scheduled Start Date:</label>
                                                <div  class="col-md-7">
                                                    <input class="form-control" id="startDate" type = "text" min="0" placeholder="" value="" readonly="true"/>
                                                </div>
                                            </div>     
                                            <div class="form-group form-group-sm">
                                                <label for="endDate" class="control-label col-md-5">Scheduled End Date:</label>
                                                <div  class="col-md-7">
                                                    <input class="form-control" id="endDate" type = "text" min="0" placeholder="" value="" readonly="true"/>
                                                </div>
                                            </div>
                                            <div style="padding: 0px 1px 0px 1px !important; float: right !important; display:none !important;">
                                                <button type="button" class="btn btn-default btn-sm" style="height:30px;" onclick="openATab('#allmodules', 'grp=17&typ=1&pg=4&subPgNo=4.4');"><img src="cmn_images/undo_256.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Reset&nbsp;</button>
                                                <button type="button" class="btn btn-default btn-sm" style="height:30px;" onclick="viewLoanAmortizationSchedule();"><img src="cmn_images/kghostview.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">View Schedule&nbsp;</button>
                                            </div>
                                        </fieldset>
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
    else if ($subPgNo == 4.5) {//CASH LOAN PAYMENTS
        if ($vwtyp == "0") {
            /* NEW WITHDRAWAL */
            $cntent .= "
                <li onclick=\"openATab('#allmodules', 'grp=17&typ=1&pg=4');\">
                        <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                        <span style=\"text-decoration:none;\">Credit Management Menu</span>
                </li>";

            if ($vwtypActn != "FIND" && $vwtypActn != "LOANPYMNT") {
                echo $cntent .= "
                <li onclick=\"openATab('#allmodules', 'grp=17&typ=1&pg=4&subPgNo=4.5');\">
                        <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                        <span style=\"text-decoration:none;\">Cash Loan Payments</span>
                </li></ul>
               </div>";
            }
            $sbmtdTrnsHdrID = isset($_POST['PKeyID']) ? cleanInputData($_POST['PKeyID']) : -1;           
            
            $trnsType = "LOAN_PYMNT"; //"WITHDRAWAL";
            //$trnsTypeDsp = $trnsType;
            if ($vwtypActn == "EDIT" || $vwtypActn === "ADD") {
                /* Add */
                if (!$canAddTrns || ($sbmtdTrnsHdrID > 0 && !$canEdtTrns)) {
                    restricted();
                    exit();
                }
                $cageID = -1;
                $invAssetAcntID = -1;
                $sbmtdStoreID = -1;
                $pkID = getLatestCage($prsnid, $cageID, $sbmtdStoreID, $invAssetAcntID);
                $sbmtdSiteID = getLatestSiteID($prsnid);
                $trnsStatus = "Not Submitted";
                $rqstatusColor = "red";

                $dte = date('ymdHis');
                $usrTrnsCode = getGnrlRecNm("sec.sec_users", "user_id", "code_for_trns_nums", $usrID);
                $gnrtdTrnsDate = date('d-M-Y H:i:s');
                $dateStr = date('Y-m-d H:i:s');
                $prprdBy = "";

                $gnrtdTrnsNo = "";
                $acctTitle = "";
                $unclrdBal = 0;
                $clrdBal = 0;
                $docType = "";
                $docNum = "";
                $trnsAmount = 0;
                $cashAmount = 0;
                $trnsDesc = "";
                $acctNo = "";
                $accntID = -1;
                $acctStatus = "";
                $acctCrncy = $fnccurnm;
                $crncyID = $fnccurid;
                $crncyIDNm = $fnccurnm;
                $acctType = "";
                $acctCustomer = "";
                $prsnTypeEntity = "";
                $acctBranch = "";
                $acctLien = 0;
                $mandate = "";
                $authorizer = "";
                $aprvLimit = 0;
                $wtdrwlLimitNo = 0;
                $wtdrwlLimitAmt = 0;
                $wtdrwlLimitType = "";
                $exchangeRate = 0;
                $voidedTrnsHdrID = -1;
                $voidedTrnsType = "";
                $dbOrCrdt = "DR";
                $trnsPersonName = "";
                $trnsPersonTelNo = "";
                $trnsPersonAddress = "";
                $trnsPersonIDType = "";
                $trnsPersonIDNumber = "";
                $trnsPersonType = "";
                $mkReadOnly = "";
                $mkRmrkReadOnly = "";
                $vwOrAdd = "ADD";
                $pageCaption = "<span style=\"font-weight:bold;\">TOTAL LOAN PAYMENT: </span><span style=\"font-weight:bold;color:blue;\" id=\"tllrTrnsAmntTtlFld\">" . $acctCrncy . " " . number_format($trnsAmount, 2) . "</span>";
                $brnchLocID = getLatestSiteID($prsnid);
                $brnchLoc = getGnrlRecNm("org.org_sites_locations", "location_id", "location_code_name", $brnchLocID);
                $accntFctrsLocID = -1;
                $accntFctrsLoc = "";
                $disbmntHdrID = -1;
                $disbmntHdrNo = "";
                $disbmntDetID = -1;
                $disbmntDetNo = "";
                $loanCustID = -1;
                $loanCustName = "";
                $custType = "";

                $unclrdColor = "blue";
                $clrdColor = "blue";
                if ($sbmtdTrnsHdrID > 0) {
                    $result = get_OneCustAccntTrnsDet($sbmtdTrnsHdrID);
                    if ($row = loc_db_fetch_array($result)) {
                        $trnsType = $row[5];
                        $trnsStatus = $row[7];
                        $voidedTrnsHdrID = (float) $row[28];
                        $voidedTrnsType = $row[29];
                        if ($trnsStatus == "Not Submitted" || $trnsStatus == "Withdrawn" || $trnsStatus == "Rejected") {
                            $rqstatusColor = "red";
                            if ($voidedTrnsHdrID <= 0) {
                                $mkReadOnly = "";
                                $mkRmrkReadOnly = "";
                            } else {
                                $mkReadOnly = "readonly=\"true\"";
                                $mkRmrkReadOnly = "";
                                $vwOrAdd = "VIEW";
                            }
                        } else if ($trnsStatus != "Authorized" && $trnsStatus != "Paid" && $trnsStatus != "Void") {
                            $mkReadOnly = "readonly=\"true\"";
                            $mkRmrkReadOnly = "readonly=\"true\"";
                            $rqstatusColor = "brown";
                            $vwOrAdd = "VIEW";
                        } else if ($trnsStatus == "Void") {
                            $mkReadOnly = "readonly=\"true\"";
                            $mkRmrkReadOnly = "readonly=\"true\"";
                            $rqstatusColor = "red";
                            $vwOrAdd = "VIEW";
                        } else {
                            $rqstatusColor = "green";
                            $mkReadOnly = "readonly=\"true\"";
                            $mkRmrkReadOnly = "readonly=\"true\"";
                            $vwOrAdd = "VIEW";
                        }
                        $gnrtdTrnsNo = $row[12];
                        $gnrtdTrnsDate = $row[11];
                        $uName11 = getUserName((float) $row[32]);
                        $prprdBy = "<span style=\"color:blue;font-weight:bold;\">" . $uName11 . "@" . $gnrtdTrnsDate . "</span>";
                        $brnchLocID = (int) $row[14];
                        $brnchLoc = getGnrlRecNm("org.org_sites_locations", "location_id", "site_desc", $brnchLocID);
                        $accntFctrsLocID = (int) $row[33];
                        $accntFctrsLoc = getGnrlRecNm("org.org_sites_locations", "location_id", "location_code_name || '.' || site_desc", $accntFctrsLocID);
                        $crncyID = (int) $row[9];
                        $crncyIDNm = $row[10];
                        if ($crncyID <= 0) {
                            $crncyID = $fnccurid;
                            $crncyIDNm = $fnccurnm;
                        }
                        if ($voidedTrnsHdrID <= 0) {
                            $trnsDesc = $row[31];
                        } else {
                            $trnsDesc = $row[30];
                        }
                        $acctTitle = $row[2];
                        $docType = $row[15];
                        $docNum = $row[16];
                        $trnsAmount = $row[6];
                        $cashAmount = $row[27];
                        $acctNo = $row[1];
                        $accntID = (int) $row[8];
                        $acctCrncy = $row[10];
                        $authorizer = $row[34];
                        $aprvLimit = (float) $row[36];
                        $dbOrCrdt = $row[24];
                        $pageCaption = "<span style=\"font-weight:bold;\">TOTAL LOAN PAYMENT: </span><span style=\"font-weight:bold;color:blue;\" id=\"tllrTrnsAmntTtlFld\">" . $acctCrncy . " " . number_format($trnsAmount, 2) . "</span>";
                        $unclrdBal = (float) $row[37];
                        $clrdBal = (float) $row[38];
                        if ($unclrdBal > 0) {
                            $unclrdColor = "green";
                        } else {
                            $unclrdColor = "red";
                        }
                        if ($clrdBal > 0) {
                            $clrdColor = "green";
                        } else {
                            $clrdColor = "red";
                        }
                        $acctType = $row[39];
                        $acctStatus = $row[40];
                        $acctCustomer = $row[41];
                        $prsnTypeEntity = $row[42];
                        $acctLien = (float) $row[43];
                        $mandate = $row[44];

                        $wtdrwlLimitNo = $row[45];
                        $wtdrwlLimitAmt = $row[46];
                        $wtdrwlLimitType = $row[47];

                        $trnsPersonName = $row[17];
                        $trnsPersonTelNo = $row[18];
                        $trnsPersonAddress = $row[19];
                        $trnsPersonIDType = $row[20];
                        $trnsPersonIDNumber = $row[21];
                        $trnsPersonType = $row[22];
                        $disbmntHdrID = (int)$row[50];
                        $disbmntHdrNo = getGnrlRecNm("mcf.mcf_loan_disbursement_hdr", "disbmnt_hdr_id", "batch_no||' ('||description||')'", $disbmntHdrID);
                        $disbmntDetID = (int)$row[51];
                        $disbmntDetNo = "";
                        $rslt = executeSQLNoParams("SELECT distinct trnsctn_id||' ('||v.title ||' '|| v.sur_name || ', ' || v.first_name || ' ' || v.other_names||')' rtrn,
                            v.cust_id, trim(v.title ||' '|| v.sur_name || ', ' || v.first_name || ' ' || v.other_names) cname, z.cust_type
                            FROM mcf.mcf_loan_disbursement_det y,  mcf.mcf_loan_request z, mcf.mcf_customers_ind v
                            WHERE y.loan_rqst_id = z.loan_rqst_id
                            AND z.cust_id = v.cust_id
                            AND y.disbmnt_det_id = $disbmntDetID
                          UNION
                          SELECT distinct trnsctn_id||' ('||v.cust_name||')' rtrn, v.cust_id, trim(v.cust_name) cname, z.cust_type
                            FROM mcf.mcf_loan_disbursement_det y,  mcf.mcf_loan_request z, mcf.mcf_customers_corp v
                            WHERE y.loan_rqst_id = z.loan_rqst_id
                            AND z.cust_id = v.cust_id
                            AND y.disbmnt_det_id = $disbmntDetID");
                        while($rowRslt = loc_db_fetch_array($rslt)){
                            $disbmntDetNo = $rowRslt[0];
                            $loanCustID = $rowRslt[1];
                            $loanCustName = $rowRslt[2];
                            $custType = $rowRslt[3];
                        }

                        
                    }
                }
                $routingID = getMCFLoanTrnsMxRoutingID($sbmtdTrnsHdrID);
                $trnsNum = str_replace(" ()", "", " (" . $gnrtdTrnsNo . ")@" . $brnchLoc);
                ?>
                <fieldset class="" style="padding: 5px 2px 5px 2px !important;"><legend class="basic_person_lg1" style="color: #003245"><?php echo $pageCaption; ?></legend>
                    <input class="form-control" id="addOrEditForm" type = "hidden" placeholder="addOrEditForm" value="Add"/> 
                    <input class="form-control" id="mcfTlrTrnsType" type = "hidden" value="<?php echo "LOAN_PYMNT"; ?>"/>     
                    <div class="row">                  
                        <div class="col-md-12">
                            <div class="custDiv" style="border:none !important; padding:0px !important;">
                                <div id="prflCBHomeEDT" style="border:none !important;">  
                                    <div class="row" style="margin: 0px 0px 5px 0px !important;">
                                        <div class="col-md-6" style="padding:0px 1px 0px 1px !important;">
                                            <div class="form-group form-group-sm">
                                                <div class="col-md-6" style="padding:0px 1px 0px 1px !important;">
                                                    <div class="input-group">
                                                        <input class="form-control rqrdFld" id="acctNoFind" style="font-size: 13px !important;font-weight: bold !important;" placeholder="Enter Account Number" type = "text" min="0" placeholder="" value="<?php echo $acctNo; ?>" <?php echo $mkReadOnly; ?>/>
                                                        <input type="hidden" id="acctNoFindAccId" value="<?php echo ''; ?>">
                                                        <input type="hidden" id="acctNoFindRawTxt" value="<?php echo ''; ?>">
                <?php if ($trnsStatus != "Authorized" && $trnsStatus != "Paid" && $trnsStatus != "Void") { ?>
                                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'MCF Manual Loan Payment Accounts', '', '', '', 'radio', true, '', 'acctNoFindAccId', 'acctNoFindRawTxt', 'clear', 1, '', function () {
                                                                $('#acctNoFind').val($('#acctNoFindRawTxt').val().split(' [')[0]);
                                                                });">
                                                                <span class="glyphicon glyphicon-th-list"></span>
                                                            </label>
                                                               <?php } ?>
                                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getCustAcctsForm('myFormsModalCB', 'myFormsModalBodyCB', 'myFormsModalTitleCB', 'View Customer Account', 13, 2.1, 0, 'VIEW', <?php echo $accntID; ?>, 'custAcctTable', '', '<?php echo $acctNo; ?>');" data-toggle="tooltip" data-placement="bottom" title = "Latest Account Information">
                                                            <span class="glyphicon glyphicon-info-sign"></span>
                                                        </label>
                                                    </div>
                                                    <!--Account Transaction ID-->
                                                    <input class="form-control" id="acctTrnsId" type = "hidden" placeholder="" value="<?php echo $sbmtdTrnsHdrID; ?>"/>
                                                    <input class="form-control" type="hidden" id="mcfVoidedTrnsHdrID" value="<?php echo $voidedTrnsHdrID; ?>"/>
                                                    <input class="form-control" type="hidden" id="newMCFHdrID" value="-1"/>
                                                </div>
                                                <div class="col-md-6" style="padding:0px 1px 0px 0px !important;">
                                                    <div style="float:left;">
                <?php if ($trnsStatus != "Authorized" && $trnsStatus != "Paid" && $trnsStatus != "Void") { ?>
                                                            <button type="button" class="btn btn-success btn-sm" style="height: 30px;" onclick="getAcctDetails(15, '<?php echo $subPgNo; ?>', 0, 'FIND');">
                                                                <img src="cmn_images/search.png" style="height:17px; width:auto; position: relative; vertical-align: middle;">
                                                                FIND
                                                            </button>
                <?php } ?>
                                                        <button type="button" class="btn btn-default btn-sm" style="height: 30px;" onclick="getOneMcfDocsForm('<?php echo $trnsType; ?>', 140);" data-toggle="tooltip" data-placement="bottom" title = "Attached Documents">
                                                            <img src="cmn_images/adjunto.png" style="height:17px; width:auto; position: relative; vertical-align: middle;">
                                                            Attachments
                                                        </button>
                                                        <div class="btn-group">
                                                            <button type="button" class="btn btn-default btn-sm" style="height: 30px !important;" onclick="">
                                                                My Tills
                                                            </button>
                                                            <button class="btn btn-info dropdown-toggle btn-sm" style="height:30px !important;" type="button" data-toggle="dropdown">
                                                                <span class="caret"></span>
                                                            </button>
                                                            <ul class="dropdown-menu" role="menu" style="margin-left: 15px !important;">
                                                            <?php
                                                            $cageRslt = getMyCages($prsnid);
                                                            while ($rowCg = loc_db_fetch_array($cageRslt)) {
                                                                $cageID = (int) $rowCg[2];
                                                                $vltID = (int) $rowCg[1];
                                                                $invAssetAcntID = (int) $rowCg[9];
                                                                $cageNm = $rowCg[3];
                                                                ?>                                                                
                                                                    <li>
                                                                        <a href="javascript:getOneCageFnPos(<?php echo $cageID; ?>, 7);">
                                                                            <img src="cmn_images/teller1.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                            My Till/Drawer (<?php echo $cageNm; ?>)
                                                                        </a>
                                                                    </li>
                                                            <?php }
                                                            ?>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>                                               
                                        </div>
                                        <div class="col-md-6" style="padding:0px 0px 0px 1px !important;">
                                            <div style="padding:0px 1px 0px 15px !important;float:left;">
                                                <button type="button" class="btn btn-default btn-sm" style="height:30px;" id="myVmsTrnsStatusBtn">
                                                    <span style="font-weight:bold;">Status: </span><span style="color:<?php echo $rqstatusColor; ?>;font-weight: bold;"><?php echo $trnsStatus; ?></span>
                                                </button>
                                                <button type="button" class="btn btn-default btn-sm" style="height:30px;" onclick="getCoreBankingForm('myFormsModalLg', 'myFormsModalBodyLg', 'myFormsModalTitleLg', 'WITHDRAWAL TRANSACTION', 15, '<?php echo $subPgNo; ?>', 0, 'ADD', <?php echo $sbmtdTrnsHdrID; ?>);" data-toggle="tooltip" title="Reload Transaction">
                                                    <img src="cmn_images/refresh.bmp" style="padding-right: 2px; height:17px; width:auto; position: relative; vertical-align: middle;">
                                                </button>
                                            </div>
                                            <div style="padding:0px 1px 0px 1px !important;float:right;">
                                                    <?php if ($trnsStatus == "Not Submitted" || $trnsStatus == "Withdrawn" || $trnsStatus == "Rejected") { ?>                                                    
                                                        <?php if ($voidedTrnsHdrID <= 0) { ?>
                                                        <button type="button" class="btn btn-default btn-sm" style="height:30px;" onclick="saveAccountTrns_LoansMgmt(<?php echo $pgNo; ?>, '<?php echo $subPgNo; ?>', <?php echo $vwtyp; ?>, 'LOAN_PYMNT', 0);"><img src="cmn_images/FloppyDisk.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Save&nbsp;</button>
                                                        <button type="button" class="btn btn-default btn-sm" style="height:30px;" onclick="saveAccountTrns_LoansMgmt(<?php echo $pgNo; ?>, '<?php echo $subPgNo; ?>', <?php echo $vwtyp; ?>, 'LOAN_PYMNT', 1);"><img src="cmn_images/Emailcon.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Submit&nbsp;</button>   
                                                    <?php } else { ?>                                          
                                                        <button type="button" class="btn btn-default btn-sm" style="height:30px;" onclick="saveAccountTrnsRvrs(<?php echo $pgNo; ?>, '<?php echo $subPgNo; ?>', <?php echo $vwtyp; ?>, 'LOAN_PYMNT', 1);"><img src="cmn_images/Emailcon.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Submit Reversal&nbsp;</button>
                                                    <?php } ?>
                                                    <?php
                                                } else if ($trnsStatus != "Authorized" && $trnsStatus != "Paid" && $trnsStatus != "Void") {
                                                    ?>                                    
                                                    <button type="button" class="btn btn-default btn-sm" style="height:30px;" onclick="wthdrwMCFTrnsRqst_CrdtMgmnt('LOAN_PYMNT', 20, '<?php echo $subPgNo; ?>');"><img src="cmn_images/withdraw_rqst.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Withdraw&nbsp;</button>                                      
                                                    <button type="button" class="btn btn-default btn-sm" style="height:30px;" onclick="authrzeMCFTrnsRqstCrdtMgmnt('LOAN_PYMNT', 20, '<?php echo $subPgNo; ?>');"><img src="cmn_images/Stamp-512.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Authorize&nbsp;</button>                                                                                                        
                                                    <button type="button" class="btn btn-default btn-sm" style="height:30px;" onclick="checkWkfRqstStatus(<?php echo $routingID; ?>, 'Teller Trns. Authorization Progress History');"><img src="cmn_images/workflow.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Progress&nbsp;</button>                                
                                                        <?php
                                                    } else if ($trnsStatus == "Authorized") {
                                                        ?>
                                                    <button type="button" class="btn btn-default btn-sm" style="height:30px;" onclick="finalzeMCFTrnsRqst_LoanPymnt('LOAN_PYMNT', 20, '<?php echo $subPgNo; ?>');"><img src="cmn_images/payment_256.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">PAY</button>
                                                    <button type="button" class="btn btn-default btn-sm" style="height:30px;" onclick="checkWkfRqstStatus(<?php echo $routingID; ?>, 'Teller Trns. Authorization Progress History');"><img src="cmn_images/workflow.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;" data-toggle="tooltip" title="Authorization Progress History">Progress&nbsp;</button>  
                                                    <button type="button" class="btn btn-default btn-sm" style="height:30px;" onclick="saveAccountTrnsRvrs(<?php echo $pgNo; ?>, '<?php echo $subPgNo; ?>', <?php echo $vwtyp; ?>, 'LOAN_PYMNT', 0);"><img src="cmn_images/90.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Void Transaction&nbsp;</button>                               
                                                <?php } ?>
                                                <?php
                                                if ($trnsStatus == "Paid" || $trnsStatus == "Void") {
                                                    $reportTitle = "Withdrawal Transaction";
                                                    $reportName = "Teller Transaction Receipt";
                                                    $rptID = getRptID($reportName);
                                                    $prmID1 = getParamIDUseSQLRep("{:invoice_id}", $rptID);
                                                    $prmID2 = getParamIDUseSQLRep("{:documentTitle}", $rptID);
                                                    $invcID = $sbmtdTrnsHdrID;
                                                    $paramRepsNVals = $prmID1 . "~" . $invcID . "|" . $prmID2 . "~" . $reportTitle . "|-190~PDF";
                                                    $paramStr = urlencode($paramRepsNVals);
                                                    ?>
                                                    <button type="button" class="btn btn-default btn-sm" style="height:30px;" title="Get Voucher on Thermal Receipt Paper" onclick="getSilentRptsRnSts(<?php echo $rptID; ?>, - 1, '<?php echo $paramStr; ?>');">
                                                        <img src="cmn_images/printer-icon.png" style="left: 0.5%; padding-right: 5px; height:17px; width:17px; position: relative; vertical-align: middle;">
                                                        POS
                                                    </button> 
                                                    <button type="button" class="btn btn-default btn-sm" style="height:30px;" title="Get Voucher on A4" onclick="getSilentRptsRnSts(<?php echo $rptID; ?>, - 1, '<?php echo $paramStr; ?>');">
                                                        <img src="cmn_images/printer-icon.png" style="left: 0.5%; padding-right: 5px; height:17px; width:17px; position: relative; vertical-align: middle;">
                                                        A4
                                                    </button>
                                                    <button type="button" class="btn btn-default btn-sm" style="height:30px;" onclick="checkWkfRqstStatus(<?php echo $routingID; ?>, 'Teller Trns. Authorization Progress History');"><img src="cmn_images/workflow.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;" data-toggle="tooltip" title="Authorization Progress History">Progress&nbsp;</button>      
                    <?php if ($voidedTrnsHdrID <= 0 || $trnsStatus == "Paid") { ?>
                                                        <button type="button" class="btn btn-default btn-sm" style="height:30px;" onclick="saveAccountTrnsRvrs(<?php echo $pgNo; ?>, '<?php echo $subPgNo; ?>', <?php echo $vwtyp; ?>, 'LOAN_PYMNT', 0);"><img src="cmn_images/90.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Void Transaction&nbsp;</button>                                            
                                                        <?php
                                                    }
                                                }
                                                ?>
                                            </div>
                                        </div>
                                    </div>                                          
                                    <form class="form-horizontal">
                                        <div class="row"><!-- ROW 1 -->  
                                            <div class="col-lg-6"> 
                                                <fieldset class=""><legend class="basic_person_lg1">TRNS.<?php echo $trnsNum; ?></legend>
                                                    <div class="form-group form-group-sm">
                                                        <label for="acctTitle" class="control-label col-md-4">Account Title:</label>
                                                        <div  class="col-md-8">
                                                            <input class="form-control" id="acctTitle" type = "text" placeholder="" value="<?php echo $acctTitle; ?>" readonly="readonly"/>
                                                            <input class="form-control" id="ttlDocAmntVal" type = "hidden" placeholder="" value="<?php echo $trnsAmount; ?>" readonly="readonly"/>
                                                        </div>
                                                    </div>  
                                                    <div class="form-group form-group-sm">
                                                        <label for="unclrdBal" class="control-label col-md-4">Current Balance (B4):</label>
                                                        <div  class="col-md-8">
                                                            <div class="input-group">
                                                                <span class="input-group-addon" id="unclrdBalCrncy" style="font-weight: bold;"><?php echo $acctCrncy; ?></span>
                                                                <input id="unclrdBal" class="form-control"  type = "text" style="font-weight: bold;color:<?php echo $unclrdColor; ?>;font-size:16px !important;" placeholder="" value="<?php
                                echo number_format($unclrdBal, 2);
                                ?>" aria-describedby="unclrdBalCrncy" readonly="readonly"/>
                                                            </div>                                                                
                                                        </div>                                                            
                                                    </div>                                                       
                                                    <div class="form-group form-group-sm">
                                                        <label for="clrdBal" class="control-label col-md-4">Available Balance (B4):</label>
                                                        <div  class="col-md-8">
                                                            <div class="input-group">
                                                                <span class="input-group-addon" id="clrdBalCrncy" style="font-weight:bold;"><?php echo $acctCrncy; ?></span>
                                                                <input id="clrdBal" class="form-control" style="font-weight: bold;color:<?php echo $clrdColor; ?>;font-size:16px !important;" type = "text" placeholder="" value="<?php
                                echo number_format($clrdBal, 2);
                                ?>" aria-describedby="clrdBalCrncy" readonly="readonly"/>
                                                            </div>                                                                
                                                        </div>                                                            
                                                    </div>
                                                    <div class="form-group form-group-sm">
                                                        <label for="docType" class="control-label col-md-4">Document Type & No.:</label>
                                                        <div  class="col-md-4" style="padding-right: 1px !important;">
                                                            <select class="form-control" id="docType" <?php echo $mkReadOnly; ?> onchange="mcfTrnsDocTypeChng();">
                                                                <option value="Paperless" selected="selected">Paperless</option>
                                                            </select>
                                                        </div>
                                                        <div  class="col-md-4" style="padding-left: 1px !important;">
                                                            <input class="form-control" id="docNum" type = "text" placeholder="Document No" value="<?php echo $docNum; ?>" readonly="readonly"/>
                                                        </div>
                                                    </div>
                                                    <div class="form-group form-group-sm">
                                                        <label for="disbmntHdrNo" class="control-label col-md-4">Disbursed Loan:</label>
                                                        <div  class="col-md-4" style="padding-right: 1px !important;">
                                                            <div class="input-group">
                                                                <input type="text" class="form-control" aria-label="..." id="disbmntHdrNo" value="<?php echo $disbmntHdrNo; ?>" readonly="readonly">
                                                                <input type="hidden" id="disbmntHdrID" value="<?php echo $disbmntHdrID; ?>">
                                                                <input type="hidden" id="gnrlOrgID" value="<?php echo $orgID; ?>">
                                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'MCF Manual Payment Disbursements', 'gnrlOrgID', <?php echo $accntFctrsLocID; ?>, '', 'radio', true, '', 'disbmntHdrID', 'disbmntHdrNo', 'clear', 1, '', function(){
                                                                    $('#disbmntDetID').val(-1);
                                                                    $('#disbmntDetNo').val('');
                                                                });">
                                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div  class="col-md-4" style="padding-left: 1px !important;">
                                                            <div class="input-group">
                                                                <input type="text" class="form-control" aria-label="..." id="disbmntDetNo" value="<?php echo $disbmntDetNo; ?>" readonly="readonly">
                                                                <input type="hidden" id="disbmntDetID" value="<?php echo $disbmntDetID; ?>">
                                                                <input type="hidden" id="gnrlOrgID" value="<?php echo $orgID; ?>">
                                                                <input type="hidden" id="disbrsdLoanAmnt" value="">
                                                                <label class="btn btn-primary btn-file input-group-addon" onclick="onClickManualPymntLoans(<?php echo $pgNo; ?>, <?php echo $subPgNo; ?>);">
                                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>                                                     
                                                    <div class="form-group form-group-sm">
                                                        <label for="trnsAmount"  style="font-size: 20px !important;" class="control-label col-md-4">Amount:</label>
                                                        <input class="form-control" id="trnsAmntRaw" type = "hidden" min="0" placeholder="Amount Raw" value="<?php
                                                                echo $cashAmount;
                                                                ?>"/>
                                                        <input class="form-control" id="trnsAmntRaw1" type = "hidden" value="<?php
                                                        echo $cashAmount;
                                                        ?>"/>
                                                        <div  class="col-md-6">
                                                            <div class="input-group input-group-lg">
                                                                <label class="btn btn-info btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Currencies', '', '', '', 'radio', true, '<?php echo $crncyIDNm; ?>', 'mcfPymtCrncyNm', '', 'clear', 0, '', function () {
                                                                    $('#trnsAmountCrncy').html($('#mcfPymtCrncyNm').val());
                                                                    });">
                                                                    <span class="" style="font-size: 20px !important;" id="trnsAmountCrncy"><?php echo $crncyIDNm; ?> </span>
                                                                </label>
                                                                <input type="hidden" id="mcfPymtCrncyNm" value="<?php echo $crncyIDNm; ?>">
                                                                <input class="form-control" style="height:46px !important; font-size: 20px !important;text-align: right !important;" id="trnsAmount" type = "text" placeholder="" value="<?php
                                                       echo number_format($cashAmount, 2);
                                                       ?>" aria-describedby="trnsAmountCrncy" readonly="readonly"/>                                                               
                                                            </div>                                                                    
                                                        </div>
                                                        <div class="col-md-2" style="padding-left:0px !important;">
                                                            <button type="button" class="btn btn-default btn-lg" onclick="getCashBreakdown('myFormsModaly', 'myFormsModalyBody', 'myFormsModalyTitle', 'cashDenominationsForm', '', 'Cash Breakdown', 15, '<?php echo $subPgNo; ?>', 2, '<?php echo $vwOrAdd; ?>');" style="width:100% !important;height: 46px !important;" title="Cash Breakdown">
                                                                <img src="cmn_images/cash_breakdown.png" style="left: 0.5%; padding-right: 5px; height:35px; width:auto; position: relative; vertical-align: middle;">
                                                            </button>
                                                        </div>
                                                    </div>   
                                                    <div class="form-group form-group-sm">
                                                        <label for="trnsDesc" class="control-label col-md-4">Narration:</label>
                                                        <div  class="col-md-8">
                                                            <div class="input-group">
                                                                <textarea class="form-control rqrdFld input-group-addon" rows="2" placeholder="Narration/Remarks" id="trnsDesc" name="trnsDesc" <?php echo $mkRmrkReadOnly; ?> style="text-align:left !important;"><?php echo $trnsDesc; ?></textarea>
                                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Sample Tellering Narrations', '', '', '', 'radio', true, '', '', 'trnsDesc', 'clear', 1, '');">
                                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div> 
                                                </fieldset>   
                                            </div>                                                
                                            <div class="col-lg-6">
                                                <fieldset class=""><legend class="basic_person_lg1">Account Details</legend>
                                                    <div class="form-group form-group-sm">
                                                        <label for="acctNo" class="control-label col-md-4">Account Number:</label>
                                                        <div class="col-md-8">
                                                            <input class="form-control" id="acctNo" type="text" placeholder="" value="<?php echo $acctNo; ?>" readonly="readonly"/>  
                                                            <input class="form-control" id="acctID" placeholder="Account ID" type="hidden" placeholder="" value="<?php echo $accntID; ?>"/>
                                                        </div>
                                                    </div>
                                                    <div class="form-group form-group-sm">
                                                        <label for="acctStatus" class="control-label col-md-4">Account Status:</label>
                                                        <div  class="col-md-8">
                                                            <input class="form-control" id="acctStatus" type = "text" placeholder="" value="<?php echo $acctStatus; ?>" readonly="readonly"/>
                                                        </div>
                                                    </div>
                                                    <div class="form-group form-group-sm">
                                                        <label for="acctCrncy" class="control-label col-md-4">Currency:</label>
                                                        <div  class="col-md-8">
                                                            <input class="form-control" id="acctCrncy" type = "text" placeholder="" value="<?php echo $acctCrncy; ?>" readonly="readonly"/>
                                                        </div>
                                                    </div>
                                                    <div class="form-group form-group-sm">
                                                        <label for="acctType" class="control-label col-md-4">Account Type:</label>
                                                        <div  class="col-md-8">
                                                            <input class="form-control" id="acctType" type = "text" placeholder="" value="<?php echo $acctType; ?>" readonly="readonly"/>
                                                        </div>
                                                    </div>
                                                    <div class="form-group form-group-sm">
                                                        <label for="acctCustomer" class="control-label col-md-4">Customer:</label>
                                                        <div  class="col-md-8">
                                                            <input class="form-control" id="acctCustomer" type = "text" placeholder="" value="<?php echo $acctCustomer; ?>" readonly="readonly"/>
                                                        </div>
                                                    </div>
                                                    <div class="form-group form-group-sm">
                                                        <label for="prsnTypeEntity" class="control-label col-md-4">Person Type/Entity:</label>
                                                        <div  class="col-md-8">
                                                            <input class="form-control" id="prsnTypeEntity" type = "text" placeholder="" value="<?php echo $prsnTypeEntity; ?>" readonly="readonly"/>
                                                        </div>
                                                    </div>
                                                    <div class="form-group form-group-sm">
                                                        <label for="acctBranch" class="control-label col-md-4">Branch:</label>
                                                        <div  class="col-md-8">
                                                            <input class="form-control" id="acctBranch" type = "text" placeholder="" value="<?php echo $accntFctrsLoc; ?>" readonly="readonly"/>
                                                        </div>
                                                    </div>
                                                    <div class="form-group form-group-sm">
                                                        <label for="acctLien" class="control-label col-md-4">Lien on Account:</label>
                                                        <div  class="col-md-8">
                                                            <input class="form-control" id="acctLien" type = "text" placeholder="" value="<?php
                                                       echo number_format($acctLien, 2);
                                                       ?>" readonly="readonly"/>
                                                        </div>
                                                    </div>
                                                </fieldset>
                                            </div>
                                        </div> 
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <fieldset class=""><legend class="basic_person_lg1">Withdrawer's Details</legend>
                                                    <div class="form-group form-group-sm">
                                                        <label for="trnsPersonType" class="control-label col-lg-4">Withdrawal By:</label>
                                                        <div  class="col-lg-8">
                                                            <?php
                                                            $chkdSelf = "";
                                                            $chkdOthers = "checked=\"\"";
                                                            if ($trnsPersonType == "Self") {
                                                                $chkdOthers = "";
                                                                $chkdSelf = "checked=\"\"";
                                                            }
                                                            ?>
                                                            <label class="radio-inline"><input type="radio" name="trnsPersonType" onclick="trnsPrsnTypeChng();" value="Self" <?php echo $chkdSelf; ?>>Self</label>
                                                            <label class="radio-inline"><input type="radio" name="trnsPersonType" onclick="trnsPrsnTypeChng();" value="Others" <?php echo $chkdOthers; ?>>Others</label>                                                               
                                                        </div>
                                                    </div>
                                                    <div class="form-group form-group-sm">
                                                        <label for="trnsPersonName" class="control-label col-md-4">Person Name:</label>
                                                        <div class="col-md-8">
                                                            <input class="form-control rqrdFld" id="trnsPersonName" type = "text" placeholder="" value="<?php echo $trnsPersonName; ?>" <?php echo $mkReadOnly; ?>/>
                                                        </div>
                                                    </div>
                                                    <div class="form-group form-group-sm">
                                                        <label for="trnsPersonTelNo" class="control-label col-md-4">Mobile No:</label>
                                                        <div class="col-md-8">
                                                            <input class="form-control rqrdFld" id="trnsPersonTelNo" type = "text" placeholder="" value="<?php echo $trnsPersonTelNo; ?>" <?php echo $mkReadOnly; ?>/>
                                                        </div>
                                                    </div>
                                                    <div class="form-group form-group-sm">
                                                        <label for="trnsPersonAddress" class="control-label col-md-4">Address:</label>
                                                        <div class="col-md-8">
                                                            <input class="form-control" id="trnsPersonAddress" type = "text" placeholder="" value="<?php echo $trnsPersonAddress; ?>" <?php echo $mkReadOnly; ?>/>
                                                        </div>
                                                    </div>
                                                    <div class="form-group form-group-sm">
                                                        <label for="trnsPersonIDType" class="control-label col-md-4">ID Type:</label>
                                                        <div class="col-md-8">
                                                            <select class="form-control rqrdFld" id="trnsPersonIDType" <?php echo $mkReadOnly; ?>>  
                                                                <option value="">Please Select...</option>
                <?php
                $brghtStr = "";
                $isDynmyc = FALSE;
                $titleRslt = getLovValues("%", "Both", 0, 100, $brghtStr, getLovID("National ID Types"), $isDynmyc, -1, "", "");
                while ($titleRow = loc_db_fetch_array($titleRslt)) {
                    $selectedTxt = "";
                    if ($trnsPersonIDType == $titleRow[0]) {
                        $selectedTxt = "selected";
                    }
                    ?>
                                                                    <option value="<?php echo $titleRow[0]; ?>" <?php echo $selectedTxt; ?>><?php echo $titleRow[0]; ?></option>
                                                                    <?php
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="form-group form-group-sm">
                                                        <label for="trnsPersonIDNumber" class="control-label col-md-4">ID Number:</label>
                                                        <div class="col-md-8">
                                                            <input class="form-control rqrdFld" id="trnsPersonIDNumber" type = "text" placeholder="" value="<?php echo $trnsPersonIDNumber; ?>" <?php echo $mkReadOnly; ?>/>
                                                        </div>
                                                    </div>
                                                </fieldset>
                                            </div>
                                            <div class="col-lg-6"> 
                                                <fieldset class="" style=""><legend class="basic_person_lg1" id="docTypeDtls">Mandate and Signatory(s)</legend> 
                                                    <div class="col-md-12" style="padding:0px !important;">
                                                        <div class="form-group form-group-sm">
                                                            <label for="mandate" class="control-label col-md-4">Account Mandate:</label>
                                                            <div  class="col-md-8">
                                                                <input class="form-control" id="mandate" type = "text" placeholder="" value="<?php echo $mandate; ?>" readonly="readonly"/>
                                                            </div>
                                                        </div>
                                                        <table id="acctSignatoriesTblAdd" class="table table-striped table-bordered table-responsive" cellspacing="0" width="100%" style="width:100%;">
                                                            <thead>
                                                                <tr>
                                                                    <th>No.</th>
                                                                    <th>Signatory Name</th>
                                                                    <th style="text-align: center !important;">Signed?</th>
                                                                    <th>&nbsp;</th>
                                                                    <th>&nbsp;</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="acctSignatoriesTblTbody">
                                                            <?php
                                                            $acntMndteRslt = getAccountSignatories($acctNo);
                                                            $cntr = 1;
                                                            if($loanCustID > 0 && $custType != "Corporate") {
                                                            //while ($rwMndte = loc_db_fetch_array($acntMndteRslt)) {
                                                                //$cntr++;
                                                                ?>
                                                                    <tr id="acctSignatoriesTblAddRow_<?php echo $cntr; ?>">  
                                                                        <td class="lovtd"><?php echo $cntr; ?></td>
                                                                        <td class="lovtd" id="acctSignatoriesTblAddRow<?php echo $cntr; ?>_name"><?php echo $loanCustName; ?></td>

                                                                        <td class="lovtd" style="text-align: center !important;">
                                                                            <?php
                                                                            $isChkd = "checked=\"\"";
                                                                            ?>
                                                                            <input type="checkbox" class="form-check-input" <?php echo $isChkd; ?>>
                                                                        </td>
                                                                        <td class="lovtd">
                                                                            <button type="button" class="btn btn-info btn-sm" onclick="viewSignatoryForm('myLovModal', 'myLovModalBody', 'myLovModalTitle', 'acctSignatoryForm', '', 'Signatory', 13, 2.1, 5, 'VIEW',<?php echo $loanCustID; ?>,'LOANPYMNT');" style="padding:2px !important;">View Signatory</button>
                                                                        </td>
                                                                        <td class="lovtd">
                                                                            <?php
                                                                            $bioType = "IND";
                                                                            $bioData = "<span style=\"\">No FingerPrint Data</span>";
                                                                            if ($bioType != "") {
                                                                                $bioUserID = isBioDataPrsnt($loanCustID, $bioType);
                                                                                if ($bioUserID > 0) {
                                                                                    $bioUser = getBioData($loanCustID, $bioType);
                                                                                    foreach ($bioUser as $rowBio) {
                                                                                        $finger = getUserBioFinger($rowBio['user_id']);
                                                                                        $verification = '';
                                                                                        $url_verification = base64_encode($bio_base_path . "verification.php?user_id=" . $rowBio['user_id']);
                                                                                        if (count($finger) == 0) {

                                                                                        } else {
                                                                                            $verification = "<a href='finspot:FingerspotVer;$url_verification' class='btn btn-sm btn-success'>Verify Thumbprint</a>";
                                                                                        }
                                                                                        $bioData = "<code id='user_finger_" . $rowBio['user_id'] . "' style=\"display:none;\">" . count($finger) . "</code>"
                                                                                                . "$verification";
                                                                                    }
                                                                                }
                                                                            }
                                                                            echo $bioData;
                                                                            ?>
                                                                        </td>
                                                                        <td style="display:none;" id="acctSignatoriesTblAddRow<?php echo $cntr; ?>_MndtrySign"><?php echo "Yes"; ?></td>
                                                                        <td  class="lovtd" style="display:none;" id="acctSignatoriesTblAddRow<?php echo $cntr; ?>_ID"><?php echo $loanCustID; ?></td>                            
                                                                    </tr>
                                                                <?php } ?>
                                                            </tbody>
                                                        </table>
                                                    </div> 
                                                </fieldset>
                                            </div>                                                                
                                        </div> 
                                        <!--<div class="row"> 
                                            <div class="col-lg-6">
                                                <fieldset class=""><legend class="basic_person_lg1">Transaction Authorizers</legend>
                                                    <div class="form-group form-group-sm">
                                                        <label for="authorizer" class="control-label col-md-4">Authorizer:</label>
                                                        <div  class="col-md-8">
                                                            <input class="form-control" id="authorizer" type = "text" placeholder="" value="<?php echo $authorizer; ?>" readonly="readonly"/>
                                                        </div>
                                                    </div>
                                                    <div class="form-group form-group-sm">
                                                        <label for="aprvLimit" class="control-label col-md-4">Approval Limit:</label>
                                                        <div  class="col-md-8">
                                                            <div class="input-group">
                                                                <span class="input-group-addon" id="aprvLimitCrncy"><?php echo $crncyIDNm; ?></span>
                                                                <input id="aprvLimit" class="form-control"  type = "text" placeholder="" value="<?php
                echo number_format($aprvLimit, 2);
                ?>" aria-describedby="aprvLimitCrncy" readonly="readonly"/>
                                                            </div>                                                                 
                                                        </div>                                                            
                                                    </div>
                                                </fieldset>
                                            </div>
                                            <div class="col-lg-6">
                                                <fieldset class=""><legend class="basic_person_lg1" id="wtdrwlLimitTypeLegend">Withdrawal Limit</legend>
                                                    <div class="form-group form-group-sm">
                                                        <label for="wtdrwlLimitNo" class="control-label col-md-4">Limit No:</label>
                                                        <div  class="col-md-8">
                                                            <input class="form-control" id="wtdrwlLimitNo" type = "text" placeholder="" value="<?php echo $wtdrwlLimitNo; ?>" readonly="readonly"/>
                                                        </div>
                                                    </div>
                                                    <div class="form-group form-group-sm">
                                                        <label for="wtdrwlLimitAmt" class="control-label col-md-4">Limit Amount:</label>
                                                        <div  class="col-md-8">
                                                            <div class="input-group">
                                                                <span class="input-group-addon" id="wtdrwlLimitAmtCrncy"><?php echo $acctCrncy; ?></span>
                                                                <input class="form-control"  id="wtdrwlLimitAmt" type = "text" placeholder="" value="<?php
                echo number_format($wtdrwlLimitAmt, 2);
                ?>" aria-describedby="wtdrwlLimitAmtCrncy" readonly="readonly"/>
                                                            </div>                                                                
                                                        </div>                                                            
                                                    </div>
                                                </fieldset>
                                            </div>
                                        </div>-->   
                                        <div class="row">
                                            <div class="col-lg-12"> 
                                                <fieldset class="" style=""><legend class="basic_person_lg1" id="docTypeDtls">Historic Account Transactions</legend> 
                                                    <div  class="col-md-12" style="padding:0px !important;">
                                                        <table id="acctHistoryTblAdd" class="table table-striped table-bordered table-responsive" cellspacing="0" width="100%" style="width:100%;">
                                                            <thead>
                                                                <tr>
                                                                    <th>&nbsp;</th>
                                                                    <th>Date</th>
                                                                    <th>Transaction Type & Description</th>
                                                                    <th>Trns. No.</th>
                                                                    <th style="text-align:right;min-width: 120px;">Amount</th>
                                                                    <th style="text-align:right;min-width: 120px;">Current Bal. (After Trns.)</th>
                                                                    <th style="text-align:right;min-width: 120px;">Available Bal. (After Trns.)</th>
                                                                    <th>Status</th>
                                                                    <th>Authorizer</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="acctHistoryTblTbody">
                <?php
                $acntHstryRslt = get_OneCustAccntHstry($accntID);
                while ($rwHstry = loc_db_fetch_array($acntHstryRslt)) {
                    ?>
                                                                    <tr>
                                                                        <td class="lovtd">
                                                                            <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Transaction Details" onclick="getOneVmsTrnsForm(<?php echo $rwHstry[0]; ?>, '<?php echo $rwHstry[5]; ?>', 30, 'ShowDialog',<?php echo $vwtyp; ?>);" style="padding:2px !important;">
                                                                                <img src="cmn_images/kghostview.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                            </button>
                                                                        </td>
                                                                        <td class="lovtd"><?php echo $rwHstry[11]; ?></td>
                                                                        <td class="lovtd"><?php echo $rwHstry[5] . " @" . $rwHstry[48] . " - " . $rwHstry[31] . " [" . $rwHstry[15] . " - " . $rwHstry[16] . "]"; ?></td>
                                                                        <td class="lovtd"><?php echo $rwHstry[12]; ?></td>
                                                                        <td class="lovtd" style="text-align:right;font-weight:bold;"><?php
                                                echo $rwHstry[10] . " " . number_format((float) $rwHstry[6], 2);
                                                ?></td>
                                                                        <td class="lovtd" style="text-align:right;font-weight:bold;color:blue;"><?php
                                                                            echo $rwHstry[10] . " " . number_format((float) $rwHstry[37], 2);
                                                                            ?></td>
                                                                        <td class="lovtd" style="text-align:right;font-weight:bold;color:blue;"><?php
                                                                            echo $rwHstry[10] . " " . number_format((float) $rwHstry[38], 2);
                                                                            ?></td>
                                                                        <td class="lovtd"><?php echo $rwHstry[7]; ?></td>
                                                                        <td class="lovtd"><?php echo $rwHstry[34]; ?></td>
                                                                    </tr>
                <?php } ?>
                                                            </tbody>
                                                        </table>
                                                    </div> 
                                                </fieldset>
                                            </div>                                                                
                                        </div>                                             
                                    </form>  
                                </div>                      
                            </div>                         
                        </div>                
                    </div> 
                </fieldset>
                <?php
            } else if ($vwtypActn == "VIEW") {
                getMCFTrnsRdOnlyDsply($sbmtdTrnsHdrID, $trnsType);
            } else if ($vwtypActn == "FIND") {
                //header("content-type:application/json");
                $acctNo = $PKeyID;
                $acctDetArray = array();
                //validate account
                $valRslt = validateAccountNo($acctNo);
                if ($valRslt == -1) {
                    echo "INVALID ACCOUNT NUMBER";
                    exit;
                }
                $result = getAccountDetails($acctNo);
                while ($row = loc_db_fetch_array($result)) {
                    $acctDetArray = array('accountID' => $row[0], 'accountNo' => $row[1], 'status' => $row[2],
                        'currID' => $row[3], 'acctType' => $row[4], 'custId' => $row[5], 'prsnTypeEntity' => $row[7], 'branchId' => $row[13],
                        'acctTitle' => $row[9], 'mandate' => $row[10],
                        'currNm' => $row[12]);
                }
                $result2 = getAccountSignatories($acctNo);
                $noOfRec = loc_db_num_rows($result2);
                $acctSgntrsArray = array();
                for ($i = 0; $i < $noOfRec; $i++) {
                    $row = loc_db_fetch_array($result2);

                    $bioType = "";
                    if ($row[4] == "Other Persons") {
                        $bioType = "OTH";
                    } else if ($row[4] == "Individual Customers") {
                        $bioType = "IND";
                    }
                    $bioData = "<span style=\"\">No FingerPrint Data</span>";
                    if ($bioType != "") {
                        $bioUserID = isBioDataPrsnt($row[2], $bioType);
                        if ($bioUserID > 0) {
                            $bioUser = getBioData($row[2], $bioType);
                            foreach ($bioUser as $rowBio) {
                                $finger = getUserBioFinger($rowBio['user_id']);
                                $verification = '';
                                $url_verification = base64_encode($bio_base_path . "verification.php?user_id=" . $rowBio['user_id']);
                                if (count($finger) == 0) {
                                    
                                } else {
                                    $verification = "<a href='finspot:FingerspotVer;$url_verification' class='btn btn-sm btn-success'>Verify Thumbprint</a>";
                                }
                                $bioData = "<code id='user_finger_" . $rowBio['user_id'] . "' style=\"display:none;\">" . count($finger) . "</code>"
                                        . "$verification";
                            }
                        }
                    }
                    $acctSgntrsArray[$i] = array('id' => $row[0], 'name' => $row[1], "toSignMndtry" => $row[3], "bioData" => $bioData);
                }

                $clrdBal = getAccountBal($acctNo, 'Cleared');
                $unclrdBal = getAccountBal($acctNo, 'Uncleared');
                $acctBalsArray = array('clrBal' => $clrdBal, 'unclrBal' => $unclrdBal);
                $result3 = getAccountWithdrawalLimitInfo($acctNo);
                $acctWdrwlLimitArray = array();

                while ($row3 = loc_db_fetch_array($result3)) {
                    $acctWdrwlLimitArray = array('limitNo' => $row3[0], 'limitAmount' => $row3[1]);
                }
                $result4 = getHistoricAccountTrns($acctNo); //, 'WITHDRAWAL'

                $noOfHistoryRec = loc_db_num_rows($result4);
                $acctTrnsHistoryArray = array();

                for ($j = 0; $j < $noOfHistoryRec; $j++) {
                    $row4 = loc_db_fetch_array($result4);
                    $acctTrnsHistoryArray[$j] = array('acctTrnsId' => $row4[0],
                        'trnsDate' => $row4[11],
                        'trnsDesc' => $row4[5] . " @" . $row4[48] . " - " . $row4[31] . " [" . $row4[15] . " - " . $row4[16] . "]",
                        'trnsNo' => $row4[12],
                        'amount' => $row4[10] . " " . number_format((float) $row4[6], 2),
                        'netClrdBal' => $row4[10] . " " . number_format((float) $row4[37], 2),
                        'netUnclrdBal' => $row4[10] . " " . number_format((float) $row4[38], 2),
                        'status' => $row4[7],
                        'authorizer' => $row4[34],
                        'trnsType' => $row4[5],
                        'vwType' => "0");
                }

                $response = array('accountDetails' => $acctDetArray,
                    'signatories' => $acctSgntrsArray, 'accountBalance' => $acctBalsArray,
                    'accountWdrwlLimit' => $acctWdrwlLimitArray, 'acctTrnsHistory' => $acctTrnsHistoryArray);

                //var_dump($response);
                echo json_encode($response);
                exit;
            }
            else if($vwtypActn == "LOANPYMNT"){
                //header("content-type:application/json");
                //echo ""
                $disbmntDetID = isset($_POST['disbmntDetID']) ? cleanInputData($_POST['disbmntDetID']) : -1;
                $trnsPersonName = "";
                $custType = "";
                $custID = -1;
                
                $acctDetArray = array();

                if ($disbmntDetID == -1) {
                    echo "INVALID ID";
                    exit;
                }
                $result = get_DisbmntDetlsForManualPymnt($disbmntDetID);
                while ($row = loc_db_fetch_array($result)) {
                    $trnsPersonName = $row[2];
                    $custType = $row[3];
                    $custID = $row[4];
                    $acctDetArray = array('loanRqstID' => $row[0], 'disbrsdLoanAmnt' => $row[1], 'trnsPersonName' => $trnsPersonName,
                        'custType' => $custType, 'custID' => $custID, 'groupID' => $row[5]);
                }
                
                $acctSgntrsArray = array();
                if ($custType != "Corporate") {

                    $bioType = "IND";
                    
                    $bioData = "<span style=\"\">No FingerPrint Data</span>";
                    if ($bioType != "") {
                        $bioUserID = isBioDataPrsnt($custID, $bioType);
                        if ($bioUserID > 0) {
                            $bioUser = getBioData($custID, $bioType);
                            foreach ($bioUser as $rowBio) {
                                $finger = getUserBioFinger($rowBio['user_id']);
                                $verification = '';
                                $url_verification = base64_encode($bio_base_path . "verification.php?user_id=" . $rowBio['user_id']);
                                if (count($finger) == 0) {
                                    
                                } else {
                                    $verification = "<a href='finspot:FingerspotVer;$url_verification' class='btn btn-sm btn-success'>Verify Thumbprint</a>";
                                }
                                $bioData = "<code id='user_finger_" . $rowBio['user_id'] . "' style=\"display:none;\">" . count($finger) . "</code>"
                                        . "$verification";
                            }
                        }
                    }
                    $acctSgntrsArray[0] = array('id' => $custID, 'name' => $trnsPersonName, "toSignMndtry" => "Yes", "bioData" => $bioData);
                }

                $response = array('accountDetails' => $acctDetArray, 'signatories' => $acctSgntrsArray);

                //var_dump($response);
                echo json_encode($response);
                exit;
            }
        } else if ($vwtyp == "1") {
            
        } else if ($vwtyp == "2") {
            /* CASH ANALYSIS */
            if ($vwtypActn == "VIEW") {
                /* Read Only */
                $acctTrnsId = $PKeyID;
                $mcfPymtCrncyNm = isset($_POST['mcfPymtCrncyNm']) ? cleanInputData($_POST['mcfPymtCrncyNm']) : $fnccurnm;
                if ($mcfPymtCrncyNm == "") {
                    $mcfPymtCrncyNm = $fnccurnm;
                }
                $mcfPymtCrncyID = getPssblValID($mcfPymtCrncyNm, getLovID("Currencies"));
                $mcfCrncyID = (int) getGnrlRecNm("mcf.mcf_currencies", "mapped_lov_crncy_id", "crncy_id", $mcfPymtCrncyID);
                $usedVltID = -1;
                $usedCageID = -1;
                $usedCageNm = "";
                $usedVltNm = "";
                $itemState = "Issuable";
                $extrInputFlds = "";
                $capturedItemIDs = "";
                $dsplyBalance = "display:none;";
                $usedInvAcntID = -1;
                $dfltItemState = "Issuable";
                if ($usedVltID <= 0 && $usedCageID <= 0) {
                    $pID = getLatestCage($prsnid, $usedCageID, $usedVltID, $usedInvAcntID, $mcfPymtCrncyID);
                    $usedCageNm = getGnrlRecNm("inv.inv_shelf", "line_id", "shelve_name", $usedCageID);
                    $usedVltNm = getGnrlRecNm("inv.inv_itm_subinventories", "subinv_id", "subinv_name", $usedVltID);
                    $dfltItemState = getGnrlRecNm("inv.inv_shelf", "line_id", "dflt_item_state", $usedCageID);
                }
                ?>
                <form class="form-horizontal" id="cashDenominationsForm" style="padding:5px;">
                    <input type="hidden" class="form-control" aria-label="..." id="cashDenominationsID" value="">
                    <input type="hidden" class="form-control" aria-label="..." id="cashDenominationsTtl" value="">
                    <input type="hidden" class="form-control" aria-label="..." id="cashDenominationsTtlRaw" value="">
                    <input type="hidden" class="form-control" aria-label="..." id="cashDenominationsTtlRaw1" value="">
                    <input type="hidden" class="form-control" aria-label="..." id="cashDenominationsTtlFmtd" value="">
                    <div class="row">
                        <div class=""> 
                            <fieldset style="padding: 1px !important;">
                            <!--<legend class="basic_person_lg">Total:<span id="cashBreakdownLgnd" style="color:red;"><?php
                echo $mcfPymtCrncyNm . " " . number_format(get_TransCashAnalysisTtlAmount($acctTrnsId, 1), 2);
                ?></span>
                            </legend>-->                              
                                <table id="cashBreakdownTblEDT" class="table table-striped table-bordered table-responsive" cellspacing="0" width="100%" style="width:100%;">
                                    <thead>
                                        <tr>
                                            <th style="max-width:100px;width:100px;">Type</th>
                                            <th style="max-width:100px;width:100px;">Denom.</th>
                                            <th style="max-width:100px;width:100px;">Pieces</th>
                                            <th>Total Amount</th>
                                            <th style="display:none;">Value</th>
                                            <th style="display:none;">Unit Value</th>                                            
                                            <th style="display:none;">...</th>
                                            <th style="display:none;">...</th>
                                            <th style="display:none;">...</th>
                                            <th>Exchange Rate</th>
                                            <th style="<?php echo $dsplyBalance; ?>">Running Balance</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                <?php
                $dateStr = getDB_Date_time();
//check existence of account transaction
                if ($acctTrnsId == -1) {
                    createInitAccountTrns($pAcctID, $dateStr, 'WITHDRAWAL');
                    $acctTrnsId = getInitAccountTrnsID($pAcctID, $dateStr);
                }
                $exists = checkExstncOfCashBrkdwnForTrnsID($acctTrnsId, $orgID);
                if ($exists > 0) {
                    $delSQL = "DELETE FROM mcf.mcf_account_trns_cash_analysis WHERE acct_trns_id=" . $acctTrnsId .
                            " and denomination_id NOT IN (SELECT crncy_denom_id FROM mcf.mcf_currency_denominations WHERE crncy_id = $mcfCrncyID)";
                    execUpdtInsSQL($delSQL);
                    $exists = checkExstncOfCashBrkdwnForTrnsID($acctTrnsId, $orgID);
                }
                if ($exists <= 0) {
                    createCashBreakdownInit($acctTrnsId, $mcfCrncyID, $usedVltID, $usedCageID, $dfltItemState);
                }
                $mcfAccntCrncyID = getAccountLovCrncyID($pAcctID);
                $dateStr1 = getFrmtdDB_Date_time();
                $exchangeRate1 = round(get_LtstBNKExchRate($mcfPymtCrncyID, $mcfAccntCrncyID, $dateStr1), 15);
                ?>
                                    <input class="form-control" id="initAcctTrnsId" placeholder="Init Account Trns ID" type = "hidden" placeholder="" value="<?php echo $acctTrnsId; ?>"/>
                                        <?php
                                        $result1 = get_TransCashAnalysisRO($acctTrnsId, $mcfCrncyID);
                                        //$result1 = get_CurrencyDenoms(1); 
                                        //get_CurrencyDenoms($crncyID);
                                        $cntr = 0;
                                        $ttlRows = loc_db_num_rows($result1);
                                        $ttlAmount = 0;
                                        $ttlQty = 0;
                                        while ($row1 = loc_db_fetch_array($result1)) {
                                            $cntr++;
                                            $ttlVal = 0;
                                            if ($row1[1] == 'Coin') {
                                                if ((float) $row1[7] == 0) {
                                                    $ttlVal = "";
                                                } else {
                                                    $ttlVal = number_format($row1[7], 2);
                                                    $ttlAmount = $ttlAmount + (float) $row1[7];
                                                }
                                            } else {
                                                if ((float) $row1[7] == 0) {
                                                    $ttlVal = "";
                                                } else {
                                                    $ttlVal = number_format($row1[7]);
                                                    $ttlAmount = $ttlAmount + (float) $row1[7];
                                                }
                                            }
                                            if ($row1[11] != 1 && $row1[11] != 0) {
                                                $exchangeRate = number_format($row1[11], 4);
                                            } else {
                                                $exchangeRate = number_format($exchangeRate1, 6);
                                            }
                                            $runningBalance = (float) $row1[12];
                                            $ttlQty += (float) $row1[6];
                                            if ($ttlVal != "" && ((int) $row1[9]) > 0) {//$usedVltID <= 0 && $usedCageID <= 0) {
                                                $usedVltID = (int) $row1[8];
                                                $usedCageID = (int) $row1[9];
                                                $itemState = $row1[10];
                                                $usedCageNm = getGnrlRecNm("inv.inv_shelf", "line_id", "shelve_name", $usedCageID);
                                                $usedVltNm = getGnrlRecNm("inv.inv_itm_subinventories", "subinv_id", "subinv_name", $usedVltID);
                                            }
                                            ?>
                                        <tr id="cashBreakdownRow_<?php echo $cntr; ?>">
                                            <td class="lovtd"><?php echo $row1[1]; ?></td>
                                            <td class="lovtd"><?php echo $row1[2]; ?></td>
                                            <td class="lovtd"><input class="cbQty form-control" onfocus="initCashBreakdownForm(<?php echo $cntr; ?>, <?php echo $ttlRows; ?>);" id="cashBreakdownRow<?php echo $cntr; ?>_denomQty" type = "number" min="0" placeholder="Quantity" value="<?php echo $row1[6]; ?>" style="text-align: right;width:100% !important;" readonly="true"/></td>
                                            <td class="lovtd"><input class="cbTTlAmnt form-control" onfocus="initCashBreakdownForm(<?php echo $cntr; ?>, <?php echo $ttlRows; ?>);" id="cashBreakdownRow<?php echo $cntr; ?>_ttlAmnt" type = "text" placeholder="Total Amount" value="<?php echo $ttlVal; ?>" style="text-align: right;width:100% !important;" readonly="true"/></td>                                                    
                                            <td class="lovtd" style="display:none;"><?php echo $ttlVal; ?></td>
                                            <td id="cashBreakdownRow<?php echo $cntr; ?>_value" class="lovtd" style="display:none;"><?php echo $row1[3]; ?></td>
                                            <td id="cashBreakdownRow<?php echo $cntr; ?>_denomID" class="lovtd" style="display:none;"><?php echo $row1[0]; ?></td>
                                            <td id="cashBreakdownRow<?php echo $cntr; ?>_cashAnalysisID" class="lovtd" style="display:none;"><?php echo $row1[5]; ?></td>
                                            <td id="cashBreakdownRow<?php echo $cntr; ?>_ttlAmntRaw" class="lovtd" style="display:none;"><?php echo $row1[7]; ?></td>
                                            <td class="lovtd"><input class="cbExchngRate form-control" onfocus="initCashBreakdownForm(<?php echo $cntr; ?>, <?php echo $ttlRows; ?>);" id="cashBreakdownRow<?php echo $cntr; ?>_ExchngRate" type = "number" placeholder="Exchange Rate" value="<?php echo $exchangeRate; ?>" style="text-align: right;width:100% !important;" readonly="true"/></td>  
                                            <td class="lovtd" style="<?php echo $dsplyBalance; ?>">
                                                <input type="text" class="form-control cbRnngBal" aria-label="..." id="cashBreakdownRow<?php echo $cntr; ?>_RnngBal" name="cashBreakdownRow<?php echo $cntr; ?>_RnngBal" value="<?php
                    echo number_format($runningBalance, 2);
                    ?>" style="width:100% !important;text-align: right;font-weight:bold;color:blue;" readonly="true"> 
                                                <?php
                                                if (strpos($capturedItemIDs, ";" . $row1[0] . ";") === FALSE) {
                                                    $capturedItemIDs = ";" . $row1[0] . ";";
                                                    $extrInputFlds .= "<input type=\"hidden\" id=\"cashBreakdownDenom_" . $row1[0] . "\" name=\"cashBreakdownDenom_" . $row1[0] . "\" value=\"" . number_format($runningBalance, 2) . "\" readonly=\"true\">";
                                                }
                                                ?>
                                            </td>
                                        </tr>
                                                   <?php } ?>
                                    </tbody>
                                    <tfoot>                                                            
                                        <tr>
                                            <th>&nbsp;</th>
                                            <th>TOTALS:</th>
                                            <th style="text-align: right;">
                <?php
                echo "<span style=\"color:blue;\" id=\"mcfCptrdCbQtyTtlBtn\">" . number_format($ttlQty, 0, '.', ',') . "</span>";
                ?>
                                                <input type="hidden" id="myCptrdQtyTtlVal" value="<?php echo $nwQty; ?>">
                                            </th>
                                            <th style="text-align: right;">
                <?php
                echo "<span style=\"color:red;font-weight:bold;\" id=\"mcfCptrdCbValsTtlBtn\">" . number_format($ttlAmount, 2, '.', ',') . "</span>";
                ?>
                                                <input type="hidden" id="myCptrdUmValsTtlVal" value="<?php echo $ttlPrce; ?>">
                                            </th>
                                            <th style="display:none;">&nbsp;</th>
                                            <th style="display:none;">&nbsp;</th>                                           
                                            <th style="display:none;">&nbsp;</th>
                                            <th style="display:none;">&nbsp;</th>
                                            <th style="display:none;">&nbsp;</th>
                                            <th>&nbsp;</th>
                                            <th style="<?php echo $dsplyBalance; ?>">&nbsp;</th>
                                        </tr>
                                    </tfoot>
                                </table>
                                <div id="rnngBalsMcfHiddenFields">
                <?php echo $extrInputFlds; ?>
                                </div>                                
                                <div class="col-md-12">
                                    <div class="form-group form-group-sm" style="margin-top:5px;">
                                        <div class = "col-md-3" style="padding:0px 1px 0px 1px !important;">
                                            <label style="margin-bottom:0px !important;padding:2px 0px 0px 5px !important;">Destination Till:&nbsp;</label>
                                        </div>
                                        <div class = "col-md-3" style="padding:0px 1px 0px 1px !important;">
                                            <div class="input-group">
                                                <input type="text" class="form-control" aria-label="..." id="bnkPymtDfltVlt" name="bnkPymtDfltVlt" value="<?php echo $usedVltNm; ?>" readonly="true">
                                                <input type="hidden" id="bnkPymtDfltVltID" value="<?php echo $usedVltID; ?>">
                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'VMS Vaults', 'gnrlVmsPymtOrgID', '', '', 'radio', true, '<?php echo $usedVltID; ?>', 'bnkPymtDfltVltID', 'bnkPymtDfltVlt', 'clear', 1, '');">
                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class = "col-md-3" style="padding:0px 1px 0px 1px !important;">
                                            <div class="input-group">
                                                <input type="text" class="form-control" aria-label="..." id="bnkPymtDfltCage" name="bnkPymtDfltCage" value="<?php echo $usedCageNm; ?>" readonly="true">
                                                <input type="hidden" id="bnkPymtDfltCageID" value="<?php echo $usedCageID; ?>">
                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'VMS Vault Cages', 'bnkPymtDfltVltID', 'bnkPymtFrmCrncyNm', '', 'radio', true, '<?php echo $usedCageID; ?>', 'bnkPymtDfltCageID', 'bnkPymtDfltCage', 'clear', 1, '');">
                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class = "col-md-3" style="padding:0px 1px 0px 1px !important;">
                                            <div class="input-group">
                                                <input type="text" class="form-control" aria-label="..." id="bnkPymtDfltItemState" name="bnkPymtDfltItemState" value="<?php echo $itemState; ?>" readonly="true">
                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Vault Item States', '', '', '', 'radio', true, '', '', 'bnkPymtDfltItemState', 'clear', 1, '', function () {
                                                    //alert('Item Selected');
                                                    });">
                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                    </div>
                    <div class="row" style="float:right;padding-right: 1px;">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </form>
                <?php
            } else if ($vwtypActn == "ADD") {
                /* Add */
                $acctTrnsId = $PKeyID;
                $mcfPymtCrncyNm = isset($_POST['mcfPymtCrncyNm']) ? cleanInputData($_POST['mcfPymtCrncyNm']) : $fnccurnm;
                if ($mcfPymtCrncyNm == "") {
                    $mcfPymtCrncyNm = $fnccurnm;
                }
                $mcfPymtCrncyID = getPssblValID($mcfPymtCrncyNm, getLovID("Currencies"));
                $mcfCrncyID = (int) getGnrlRecNm("mcf.mcf_currencies", "mapped_lov_crncy_id", "crncy_id", $mcfPymtCrncyID);
                $usedVltID = -1;
                $usedCageID = -1;
                $usedCageNm = "";
                $usedVltNm = "";
                $itemState = "Issuable";
                $extrInputFlds = "";
                $capturedItemIDs = "";
                $dsplyBalance = "";
                $usedInvAcntID = -1;
                $dfltItemState = "Issuable";
                if ($usedVltID <= 0 && $usedCageID <= 0) {
                    $pID = getLatestCage($prsnid, $usedCageID, $usedVltID, $usedInvAcntID, $mcfPymtCrncyID);
                    $usedCageNm = getGnrlRecNm("inv.inv_shelf", "line_id", "shelve_name", $usedCageID);
                    $usedVltNm = getGnrlRecNm("inv.inv_itm_subinventories", "subinv_id", "subinv_name", $usedVltID);
                    $dfltItemState = getGnrlRecNm("inv.inv_shelf", "line_id", "dflt_item_state", $usedCageID);
                }
                ?>
                <form class="form-horizontal" id="cashDenominationsForm" style="padding:5px;">
                    <input type="hidden" class="form-control" aria-label="..." id="cashDenominationsID" value="">
                    <input type="hidden" class="form-control" aria-label="..." id="cashDenominationsTtl" value="">
                    <input type="hidden" class="form-control" aria-label="..." id="cashDenominationsTtlRaw" value="">
                    <input type="hidden" class="form-control" aria-label="..." id="cashDenominationsTtlRaw1" value="">
                    <input type="hidden" class="form-control" aria-label="..." id="cashDenominationsTtlFmtd" value="">
                    <div class="row">
                        <div class=""> 
                            <fieldset style="padding: 1px !important;">
                            <!--<legend class="basic_person_lg">Total:<span id="cashBreakdownLgnd" style="color:red;"><?php
                echo $mcfPymtCrncyNm . " " . number_format(get_TransCashAnalysisTtlAmount($acctTrnsId, 1), 2);
                ?></span>
                            </legend>-->                              
                                <table id="cashBreakdownTblEDT" class="table table-striped table-bordered table-responsive" cellspacing="0" width="100%" style="width:100%;">
                                    <thead>
                                        <tr>
                                            <th style="max-width:100px;width:100px;">Type</th>
                                            <th style="max-width:100px;width:100px;">Denom.</th>
                                            <th style="max-width:100px;width:100px;">Pieces</th>
                                            <th>Total Amount</th>
                                            <th style="display:none;">Value</th>
                                            <th style="display:none;">Unit Value</th>                                           
                                            <th style="display:none;">...</th>
                                            <th style="display:none;">...</th>
                                            <th style="display:none;">...</th>
                                            <th>Exchange Rate</th>
                                            <th style="<?php echo $dsplyBalance; ?>">Running Balance</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                <?php
                $dateStr = getDB_Date_time();
//check existence of account transaction
                if ($acctTrnsId == -1) {
                    createInitAccountTrns($pAcctID, $dateStr, 'LOAN_PYMNT');
                    $acctTrnsId = getInitAccountTrnsID($pAcctID, $dateStr);
                }
                $exists = checkExstncOfCashBrkdwnForTrnsID($acctTrnsId, $orgID);
                if ($exists > 0) {
                    $delSQL = "DELETE FROM mcf.mcf_account_trns_cash_analysis WHERE acct_trns_id=" . $acctTrnsId .
                            " and denomination_id NOT IN (SELECT crncy_denom_id FROM mcf.mcf_currency_denominations WHERE crncy_id = $mcfCrncyID)";
                    execUpdtInsSQL($delSQL);
                    $exists = checkExstncOfCashBrkdwnForTrnsID($acctTrnsId, $orgID);
                }
                if ($exists <= 0) {
                    createCashBreakdownInit($acctTrnsId, $mcfCrncyID, $usedVltID, $usedCageID, $dfltItemState);
                }
                $mcfAccntCrncyID = getAccountLovCrncyID($pAcctID);
                $dateStr1 = getFrmtdDB_Date_time();
                $exchangeRate1 = round(get_LtstBNKExchRate($mcfPymtCrncyID, $mcfAccntCrncyID, $dateStr1), 15);
                ?>
                                    <input class="form-control" id="initAcctTrnsId" placeholder="Init Account Trns ID" type = "hidden" placeholder="" value="<?php echo $acctTrnsId; ?>"/>
                                        <?php
                                        $result1 = get_TransCashAnalysis($acctTrnsId, $mcfCrncyID);
                                        $cntr = 0;
                                        $ttlRows = loc_db_num_rows($result1);
                                        $ttlAmount = 0;
                                        $ttlQty = 0;
                                        while ($row1 = loc_db_fetch_array($result1)) {
                                            $cntr++;
                                            $ttlVal = 0;
                                            if ($row1[1] == 'Coin') {
                                                if ((float) $row1[7] == 0) {
                                                    $ttlVal = "";
                                                } else {
                                                    $ttlVal = number_format($row1[7], 2);
                                                    $ttlAmount = $ttlAmount + (float) $row1[7];
                                                }
                                            } else {
                                                if ((float) $row1[7] == 0) {
                                                    $ttlVal = "";
                                                } else {
                                                    $ttlVal = number_format($row1[7]);
                                                    $ttlAmount = $ttlAmount + (float) $row1[7];
                                                }
                                            }
                                            if ($row1[11] != 1 && $row1[11] != 0) {
                                                $exchangeRate = number_format($row1[11], 4);
                                            } else {
                                                $exchangeRate = number_format($exchangeRate1, 6);
                                            }
                                            $runningBalance = (float) $row1[12];
                                            $ttlQty += (float) $row1[6];
                                            if ($ttlVal != "" && ((int) $row1[9]) > 0) {//$usedVltID <= 0 && $usedCageID <= 0) {
                                                $usedVltID = (int) $row1[8];
                                                $usedCageID = (int) $row1[9];
                                                $itemState = $row1[10];
                                                $usedCageNm = getGnrlRecNm("inv.inv_shelf", "line_id", "shelve_name", $usedCageID);
                                                $usedVltNm = getGnrlRecNm("inv.inv_itm_subinventories", "subinv_id", "subinv_name", $usedVltID);
                                            }
                                            ?>
                                        <tr id="cashBreakdownRow_<?php echo $cntr; ?>">
                                            <td class="lovtd"><?php echo $row1[1]; ?></td>
                                            <td class="lovtd"><?php echo $row1[2]; ?></td>
                                            <td class="lovtd"><input class="cbQty form-control" onchange="calcCashBreadownRowVal('cashBreakdownRow_<?php echo $cntr; ?>', 'cbQty');" onfocus="initCashBreakdownForm(<?php echo $cntr; ?>, <?php echo $ttlRows; ?>);" id="cashBreakdownRow<?php echo $cntr; ?>_denomQty" type = "number" min="0" placeholder="Quantity" value="<?php echo $row1[6]; ?>" style="text-align: right;width:100% !important;"/></td>
                                            <td class="lovtd"><input class="cbTTlAmnt form-control" onchange="calcCashBreadownRowVal('cashBreakdownRow_<?php echo $cntr; ?>', 'cbTTlAmnt');" onfocus="initCashBreakdownForm(<?php echo $cntr; ?>, <?php echo $ttlRows; ?>);" id="cashBreakdownRow<?php echo $cntr; ?>_ttlAmnt" type = "text" placeholder="Total Amount" value="<?php echo $ttlVal; ?>" style="text-align: right;width:100% !important;"/></td>                                                    
                                            <td class="lovtd" style="display:none;"><?php echo $ttlVal; ?></td>
                                            <td id="cashBreakdownRow<?php echo $cntr; ?>_value" class="lovtd" style="display:none;"><?php echo $row1[3]; ?></td>
                                            <td id="cashBreakdownRow<?php echo $cntr; ?>_denomID" class="lovtd" style="display:none;"><?php echo $row1[0]; ?></td>
                                            <td id="cashBreakdownRow<?php echo $cntr; ?>_cashAnalysisID" class="lovtd" style="display:none;"><?php echo $row1[5]; ?></td>
                                            <td id="cashBreakdownRow<?php echo $cntr; ?>_ttlAmntRaw" class="lovtd" style="display:none;"><?php echo $row1[7]; ?></td>
                                            <td class="lovtd"><input class="cbExchngRate form-control" onchange="calcCashBreadownRowVal('cashBreakdownRow_<?php echo $cntr; ?>', 'cbExchngRate');" onfocus="initCashBreakdownForm(<?php echo $cntr; ?>, <?php echo $ttlRows; ?>);" id="cashBreakdownRow<?php echo $cntr; ?>_ExchngRate" type = "number" placeholder="Exchange Rate" value="<?php echo $exchangeRate; ?>" style="text-align: right;width:100% !important;"/></td>  
                                            <td class="lovtd" style="<?php echo $dsplyBalance; ?>">
                                                <input type="text" class="form-control cbRnngBal" aria-label="..." id="cashBreakdownRow<?php echo $cntr; ?>_RnngBal" name="cashBreakdownRow<?php echo $cntr; ?>_RnngBal" value="<?php
                    echo number_format($runningBalance, 2);
                    ?>" style="width:100% !important;text-align: right;font-weight:bold;color:blue;" readonly="true"> 
                                                <?php
                                                if (strpos($capturedItemIDs, ";" . $row1[0] . ";") === FALSE) {
                                                    $capturedItemIDs = ";" . $row1[0] . ";";
                                                    $extrInputFlds .= "<input type=\"hidden\" id=\"cashBreakdownDenom_" . $row1[0] . "\" name=\"cashBreakdownDenom_" . $row1[0] . "\" value=\"" . number_format($runningBalance, 2) . "\" readonly=\"true\">";
                                                }
                                                ?>
                                            </td>
                                        </tr>
                                                   <?php } ?>
                                    </tbody>
                                    <tfoot>                                                            
                                        <tr>
                                            <th>&nbsp;</th>
                                            <th>TOTALS:</th>
                                            <th style="text-align: right;">
                <?php
                echo "<span style=\"color:blue;\" id=\"mcfCptrdCbQtyTtlBtn\">" . number_format($ttlQty, 0, '.', ',') . "</span>";
                ?>
                                                <input type="hidden" id="myCptrdQtyTtlVal" value="<?php echo $nwQty; ?>">
                                            </th>
                                            <th style="text-align: right;">
                <?php
                echo "<span style=\"color:red;font-weight:bold;\" id=\"mcfCptrdCbValsTtlBtn\">" . number_format($ttlAmount, 2, '.', ',') . "</span>";
                ?>
                                                <input type="hidden" id="myCptrdUmValsTtlVal" value="<?php echo $ttlPrce; ?>">
                                            </th>
                                            <th style="display:none;">&nbsp;</th>
                                            <th style="display:none;">&nbsp;</th>                                           
                                            <th style="display:none;">&nbsp;</th>
                                            <th style="display:none;">&nbsp;</th>
                                            <th style="display:none;">&nbsp;</th>
                                            <th>&nbsp;</th>
                                            <th style="<?php echo $dsplyBalance; ?>">&nbsp;</th>
                                        </tr>
                                    </tfoot>
                                </table>
                                <div id="rnngBalsMcfHiddenFields">
                <?php echo $extrInputFlds; ?>
                                </div>                              
                                <div class="col-md-12">
                                    <div class="form-group form-group-sm" style="margin-top:5px;">
                                        <div class = "col-md-3" style="padding:0px 1px 0px 1px !important;">
                                            <label style="margin-bottom:0px !important;padding:2px 0px 0px 5px !important;">Source Till:&nbsp;</label>
                                        </div>
                                        <div class = "col-md-3" style="padding:0px 1px 0px 1px !important;">
                                            <div class="input-group">
                                                <input type="text" class="form-control" aria-label="..." id="bnkPymtDfltVlt" name="bnkPymtDfltVlt" value="<?php echo $usedVltNm; ?>" readonly="true">
                                                <input type="hidden" id="bnkPymtDfltVltID" value="<?php echo $usedVltID; ?>">
                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'VMS Vaults', 'gnrlVmsPymtOrgID', '', '', 'radio', true, '<?php echo $usedVltID; ?>', 'bnkPymtDfltVltID', 'bnkPymtDfltVlt', 'clear', 1, '');">
                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class = "col-md-3" style="padding:0px 1px 0px 1px !important;">
                                            <div class="input-group">
                                                <input type="text" class="form-control" aria-label="..." id="bnkPymtDfltCage" name="bnkPymtDfltCage" value="<?php echo $usedCageNm; ?>" readonly="true">
                                                <input type="hidden" id="bnkPymtDfltCageID" value="<?php echo $usedCageID; ?>">
                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'VMS Vault Cages', 'bnkPymtDfltVltID', 'bnkPymtFrmCrncyNm', '', 'radio', true, '<?php echo $usedCageID; ?>', 'bnkPymtDfltCageID', 'bnkPymtDfltCage', 'clear', 1, '');">
                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class = "col-md-3" style="padding:0px 1px 0px 1px !important;">
                                            <div class="input-group">
                                                <input type="text" class="form-control" aria-label="..." id="bnkPymtDfltItemState" name="bnkPymtDfltItemState" value="<?php echo $itemState; ?>" readonly="true">
                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Vault Item States', '', '', '', 'radio', true, '', '', 'bnkPymtDfltItemState', 'clear', 1, '', function () {
                                                    //alert('Item Selected');
                                                    });">
                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                    </div>
                    <div class="row" style="float:right;padding-right: 1px;">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" onclick="saveCashDenominationFormLR('myFormsModaly', '<?php echo $subPgNo; ?>');">Save Changes</button>
                    </div>
                </form>
                <?php
            } else if ($vwtypActn == "EDIT") {
                /* Edit Only */
            }
        }
    } 
    else if ($subPgNo == 4.6) {//RISK ASSESSMENT
        if($subVwtyp == 1){//CREDIT ASSESSMENTS
            
        }
        else if($subVwtyp == 2){
            /* ADD RISK FACTOR */
            //if ($vwtypActn == "EDIT" || $vwtypActn == "VIEW") {}
            $riskFactorCode = "";//isset($_POST['riskFactorCode']) ? cleanInputData($_POST['riskFactorCode']) : '';
            $riskFactorDesc = "";//isset($_POST['riskFactorDesc']) ? cleanInputData($_POST['riskFactorDesc']) : '';
            $isEnabled = "";
            $riskFactorId = $pkID;
            $mkReadOnly = "";
            
            if($vwtypActn == "EDIT" || $vwtypActn == "VIEW"){
                $mkReadOnly = "style=\"readonly:readonly;\"";
            }

            $result = getCreditRiskFactorDets($pkID);
            while($row = loc_db_fetch_array($result)){
                //$corpDirectorID = $row[0];
                $riskFactorCode = $row[1];
                $riskFactorDesc =  $row[2];
                $isEnabled = $row[8];
            }


            ?>
            <form class="form-horizontal" id="riskFactorsForm" style="padding:5px 20px 5px 20px;">             
                <div class="row">                   
                    <div class="form-group form-group-sm">
                        <input class="form-control" size="16" type="hidden" id="riskFactorId" value="<?php echo $riskFactorId; ?>" readonly=""> 
                        <label for="riskFactorCode" class="control-label col-md-4">Risk Factor:</label>
                        <div  class="col-md-8">
                            <textarea <?php echo $mkReadOnly; ?> class="form-control rqrdFld" id="riskFactorCode" cols="2" placeholder="Risk Factor" rows="3"><?php echo $riskFactorCode; ?></textarea>
                        </div>
                    </div>                    
                    <div class="form-group form-group-sm">
                        <label for="riskFactorDesc" class="control-label col-md-4">Description:</label>
                        <div  class="col-md-8">
                            <textarea <?php echo $mkReadOnly; ?> class="form-control" id="riskFactorDesc" cols="2" placeholder="Description" rows="4"><?php echo $riskFactorDesc; ?></textarea>
                        </div>
                    </div> 
                    <div class="form-group form-group-sm">
                        <label for="isEnabled" class="control-label col-md-4">Enabled?:</label>
                        <div  class="col-md-8">
                            <select class="form-control" id="isEnabled" >
                                <?php
                                $sltdYes = "";
                                $sltdNo = "";
                                if ($isEnabled == "Yes") {
                                    $sltdYes = "selected";
                                } else if ($isEnabled == "No") {
                                    $sltdNo = "selected";
                                }
                                ?>
                                <option value="Yes" <?php echo $sltdYes; ?>>Yes</option>
                                <option value="No" <?php echo $sltdNo; ?>>No</option>
                            </select>
                        </div>
                    </div>                    
                </div>
                <div class="row" style="float:right;padding-right: 1px;">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <?php if($vwtypActn != "VIEW") { ?>
                        <button type="button" class="btn btn-primary" onclick="saveRiskFactor(<?php echo $pgNo; ?>, <?php echo $subPgNo; ?>, <?php echo $subVwtyp; ?>, <?php echo $vwtyp;?>);">Save Changes</button>
                    <?php } ?>
                </div>                   
            </form>
            <?php      
        } 
        else if ($subVwtyp == 3 || $subVwtyp == 3.1 || $subVwtyp == 3.2 || $subVwtyp == 3.3){
            //var_dump($_POST);
            $canAddRiskProfile = test_prmssns($dfltPrvldgs[265], $mdlNm);
            $canEdtRiskProfile = test_prmssns($dfltPrvldgs[266], $mdlNm);
            $canDelRiskProfile = test_prmssns($dfltPrvldgs[267], $mdlNm);
            
            $error = "";
            $searchAll = true;
            $isEnabledOnly = false;
            if (isset($_POST['isEnabled'])) {
                $isEnabledOnly = cleanInputData($_POST['isEnabled']);
            }
            

            $srchFor = isset($_POST['searchfor']) ? cleanInputData($_POST['searchfor']) : '';
            $srchIn = isset($_POST['searchin']) ? cleanInputData($_POST['searchin']) : 'Both';
            $pageNo = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
            $lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 10;
            $sortBy = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : "Date Added DESC";
            if (strpos($srchFor, "%") === FALSE) {
                $srchFor = "%" . str_replace(" ", "%", $srchFor) . "%";
                $srchFor = str_replace("%%", "%", $srchFor);
            }

        if ($subVwtyp == 3) {
            echo  $cntent .= " <li onclick=\"openATab('#allmodules', 'grp=17&typ=1&pg=4');\">
                                                <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
						<span style=\"text-decoration:none;\">Credit Management Menu</span>
                                        </li>
					<li onclick=\"openATab('#allmodules', 'grp=17&typ=1&pg=4&subPgNo=4.6');\">
                                                <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
						<span style=\"text-decoration:none;\">Credit Risk Assessment</span>
                                        </li>
                        <li onclick=\"openATab('#allmodules', 'grp=17&typ=1&pg=4&subPgNo=4.6&subVwtyp=3');\">
                                                                <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                                                <span style=\"text-decoration:none;\">Risk Profiles</span>
                            </li></div>";            
            
            $total = getCreditRiskProfilesTblTtl($isEnabledOnly, $srchFor, $srchIn, $orgID, $searchAll);
            if ($pageNo > ceil($total / $lmtSze)) {
                $pageNo = 1;
            } else if ($pageNo < 1) {
                $pageNo = ceil($total / $lmtSze);
            }

            $curIdx = $pageNo - 1;
            $result = getCreditRiskProfilesTbl($isEnabledOnly, $srchFor, $srchIn, $curIdx, $lmtSze, $orgID, $searchAll, $sortBy);
            $cntr = 0;
            $colClassType1 = "col-lg-2";
            $colClassType2 = "col-lg-3";
            $colClassType3 = "col-lg-1";
            ?>
            <form id='allRiskProfilesForm' action='' method='post' accept-charset='UTF-8'>
                <!--<fieldset class="basic_person_fs5">-->
                    <legend class="basic_person_lg1" style="color: #003245">RISK PROFILES</legend>                
                    <input class="form-control" id="tblRowID" type = "hidden" placeholder="ROW ID"/>                     
                    <div class="row rhoRowMargin" style="margin-bottom:10px;">
                        <?php
                        if ($canAddRiskProfile === true) {
                            ?>   
                            <div class="<?php echo $colClassType1; ?>" style="padding:0px 1px 0px 15px !important;">                    
                                <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="getOneRiskProfileForm(-1, <?php echo $vwtyp; ?>);">
                                    <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                    New Risk Profile
                                </button>
                            </div>
                            <?php
                        } else {
                            $colClassType1 = "col-lg-2";
                            $colClassType2 = "col-lg-3";
                            $colClassType3 = "col-lg-1";
                        }
                        ?>
                        <div class="<?php echo $colClassType2; ?>" style="padding:0px 15px 0px 15px !important;">
                            <div class="input-group">
                                <input class="form-control" id="allRiskProfilesSrchFor" type = "text" placeholder="Search For" value="<?php echo trim(str_replace("%", " ", $srchFor)); ?>" onkeyup="enterKeyFuncCust(event, '', '#allmodules', 'grp=17&typ=1&pg=4&subPgNo=4.1')">
                                <input id="allRiskProfilesPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                <label class="btn btn-primary btn-file input-group-addon" onclick="getAllRiskProfiles('clear', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&subPgNo=<?php echo $subPgNo; ?>&subVwtyp=<?php echo $subVwtyp; ?>');">
                                    <span class="glyphicon glyphicon-remove"></span>
                                </label>
                                <label class="btn btn-primary btn-file input-group-addon" onclick="getAllRiskProfiles('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&subPgNo=<?php echo $subPgNo; ?>&subVwtyp=<?php echo $subVwtyp; ?>');">
                                    <span class="glyphicon glyphicon-search"></span>
                                </label> 
                            </div>
                        </div>
                        <div class="<?php echo $colClassType2; ?>">
                            <div class="input-group">
                                <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                <select data-placeholder="Select..." class="form-control chosen-select" id="allRiskProfilesSrchIn">
                                    <?php
                                    $valslctdArry = array("", "");
                                    $srchInsArrys = array("Risk Profile","Description");
                                    for ($z = 0; $z < count($srchInsArrys); $z++) {
                                        if ($srchIn == $srchInsArrys[$z]) {
                                            $valslctdArry[$z] = "selected";
                                        }
                                        ?>
                                        <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                    <?php } ?>
                                </select>
                                <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                <select data-placeholder="Select..." class="form-control chosen-select" id="allRiskProfilesDsplySze" style="min-width:70px !important;">                            
                                    <?php
                                    $valslctdArry = array("", "", "", "", "", "", "", "");
                                    $dsplySzeArry = array(1, 5, 10, 15, 30, 50, 100, 500, 1000);
                                    for ($y = 0; $y < count($dsplySzeArry); $y++) {
                                        if ($lmtSze == $dsplySzeArry[$y]) {
                                            $valslctdArry[$y] = "selected";
                                        } else {
                                            $valslctdArry[$y] = "";
                                        }
                                        ?>
                                        <option value="<?php echo $dsplySzeArry[$y]; ?>" <?php echo $valslctdArry[$y]; ?>><?php echo $dsplySzeArry[$y]; ?></option>                            
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>                        
                        <div class="<?php echo $colClassType3; ?>" style="padding: 5px 1px 0px 15px !important">
                            <div class="form-check" style="font-size: 12px !important;">
                                <label class="form-check-label">
                                    <?php
                                    $nonAprvdChekd = "";
                                    if ($isEnabledOnly == "true") {
                                        $nonAprvdChekd = "checked=\"true\"";
                                    }
                                    ?>
                                    <input type="checkbox" class="form-check-input" onclick="getAllRiskProfiles('clear', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&subPgNo=<?php echo $subPgNo; ?>&subVwtyp=<?php echo $subVwtyp; ?>');" id="allRiskProfilesIsEnabled" name="allRiskProfilesIsEnabled" <?php echo $nonAprvdChekd; ?>>
                                    Enabled?
                                </label>
                            </div>                             
                        </div>
                        <div class="<?php echo $colClassType3; ?>" style="width:10% !important;">
                            <nav aria-label="Page navigation">
                                <ul class="pagination" style="margin: 0px !important;">
                                    <li>
                                        <a href="javascript:getAllRiskProfiles('previous', '#allmodules', 'grp=17&typ=1&pg=4&subPgNo=4.6&subVwtyp=3');" aria-label="Previous">
                                            <span aria-hidden="true">&laquo;</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:getAllRiskProfiles('next', '#allmodules', 'grp=17&typ=1&pg=4&subPgNo=4.6&subVwtyp=3');" aria-label="Next">
                                            <span aria-hidden="true">&raquo;</span>
                                        </a>
                                    </li>
                                </ul>
                            </nav>
                        </div>  
                        <div class="<?php echo $colClassType3; ?>" style="padding:0px 1px 0px 3px !important;"> 
                            <div style="float:right !important;">
                                <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="validateRiskProfileFctr();" data-toggle="tooltip" data-placement="bottom" title="Validate Risk Profile">
                                    <img src="cmn_images/valid_1.jpg" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                </button>
                                <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="saveRiskProfile(<?php echo $pgNo; ?>, <?php echo $subPgNo; ?>, <?php echo $subVwtyp; ?>, <?php echo $vwtyp;?>);" data-toggle="tooltip" data-placement="bottom" title="Save Risk Profile">
                                    <img src="cmn_images/FloppyDisk.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                </button>
                            </div>
                        </div>
                    </div>               
                    <div class="row" style="padding:0px 15px 0px 15px !important"> 
                        <div class="col-md-4" style="padding:0px 1px 0px 1px !important">
                            <fieldset class="basic_person_fs">                                        
                                <table class="table table-striped table-bordered table-responsive" id="allRiskProfilesTable" cellspacing="0" width="100%" style="width:100%;">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Risk Profile</th>
                                            <th>Description</th>                                   
                                            <?php if ($canDelRiskProfile === true) { ?>
                                                <th>&nbsp;</th>
                                            <?php } ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $sbmtdRiskProfileID = -1;
                                        while ($row = loc_db_fetch_array($result)) {
                                            if ($sbmtdRiskProfileID <= 0 && $cntr <= 0) {
                                                $sbmtdRiskProfileID = $row[0];
                                            }
                                            $cntr += 1;
                                            ?>
                                            <tr id="allRiskProfilesRow_<?php echo $cntr; ?>" class="hand_cursor">                                    
                                                <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                                <td class="lovtd"><?php echo $row[2]; ?>
                                                    <input type="hidden" class="form-control" aria-label="..." id="allRiskProfilesRow<?php echo $cntr; ?>_RiskProfileID" value="<?php echo $row[0]; ?>">
                                                </td>
                                                <td class="lovtd">
                                                    <?php echo $row[1]; ?>
                                                </td>
                                                <?php if ($canDelRiskProfile === true) { ?>
                                                    <td class="lovtd">
                                                        <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="deleteRiskProfile(<?php echo $sbmtdRiskProfileID; ?>)" data-toggle="tooltip" data-placement="bottom" title="Delete Risk Profile">
                                                            <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                        </button>
                                                    </td>
                                                <?php } ?>
                                            </tr>
                                            <?php
                                        }
                                        ?>
                                    </tbody>
                                </table>                        
                            </fieldset>
                        </div>                        
                        <div  class="col-md-8" style="padding:0px 1px 0px 1px !important">
                            <fieldset class="basic_person_fs" style="padding-top:5px !important;">
                                <div class="" id="allRiskProfilesDetailInfo">
                                    <div class="row" id="allRiskProfilesHdrInfo" style="padding:0px 15px 0px 15px !important">
                                        <?php
                                        $oneRiskProfileDetID = -1;
                                        $oneRiskProfileDetName = "";
                                        $oneRiskProfileDetDesc = "";
                                        $oneRiskProfileDetTtlScore = 0;
                                        $oneRiskProfileDetIsEnbld = "Yes";
                                        $vldtyColor = "red";
                                        $vldtyStatus = "Invalid";
                                        $result1 = getCreditRiskProfilesDets($sbmtdRiskProfileID);
                                        while ($row1 = loc_db_fetch_array($result1)) {
                                            $oneRiskProfileDetID = $row1[0];
                                            $oneRiskProfileDetName = $row1[1];
                                            $oneRiskProfileDetDesc = $row1[2];
                                            $oneRiskProfileDetTtlScore = $row1[3];
                                            $oneRiskProfileDetIsEnbld = $row1[4];
                                            $vldtyStatus = $row1[6];
                                            if($vldtyStatus == "Valid"){
                                                $vldtyColor = "green";
                                            }
                                        }                                         
                                        ?>
                                            <fieldset class="basic_person_fs" style="padding:10px 3px 0px 3px !important;border:none !important;border-bottom:1px solid #ddd !important;border-radius: 0px !important;">                                              
                                                <div  class="col-md-6" style="padding:0px 3px 0px 3px !important;">
                                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                        <label for="oneRiskProfileDetName" class="control-label col-lg-4" style="padding:0px 15px 0px 1px !important;">Risk Profile:</label>
                                                        <div  class="col-lg-8" style="padding:0px 15px 0px 1px !important;">
                                                            <?php if ($canEdtRiskProfile === true) { ?>
                                                                <input type="text" class="form-control rqrdFld" aria-label="..." id="oneRiskProfileDetName" name="oneRiskProfileDetName" value="<?php echo $oneRiskProfileDetName; ?>" style="width:100%;">
                                                                <input type="hidden" class="form-control" aria-label="..." id="oneRiskProfileDetID" name="oneRiskProfileDetID" value="<?php echo $oneRiskProfileDetID; ?>">
                                                            <?php } else {
                                                                ?>
                                                                <span><?php echo $oneRiskProfileDetName; ?></span>
                                                                <?php
                                                            }
                                                            ?>
                                                        </div>
                                                    </div>
                                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                        <label for="oneRiskProfileDetDesc" class="control-label col-lg-4" style="padding:0px 15px 0px 1px !important;">Description:</label>
                                                        <div  class="col-lg-8" style="padding:0px 15px 0px 1px !important;">
                                                            <?php if ($canEdtRiskProfile === true) { ?>
                                                                <textarea class="form-control" aria-label="..." id="oneRiskProfileDetDesc" name="oneRiskProfileDetDesc" style="width:100%;" cols="5" rows="3"><?php echo $oneRiskProfileDetDesc; ?></textarea>
                                                            <?php } else {
                                                                ?>
                                                                <span><?php echo $oneRiskProfileDetDesc; ?></span>
                                                                <?php
                                                            }
                                                            ?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div  class="col-md-6" style="padding:0px 3px 0px 3px !important;">                                                        
                                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                        <label for="oneRiskProfileDetTtlScore" class="control-label col-lg-4" style="padding:0px 15px 0px 1px !important;">Total Score:</label>
                                                        <div class="col-lg-8" style="padding:0px 15px 0px 1px !important;">
                                                            <?php if ($canEdtRiskProfile === true) { ?>
                                                                <input type="number" min="0" class="form-control rqrdFld" aria-label="..." id="oneRiskProfileDetTtlScore" name="oneRiskProfileDetTtlScore" value="<?php echo $oneRiskProfileDetTtlScore; ?>" style="width:100%;">
                                                            <?php } else {
                                                                ?>
                                                                <span><?php echo $oneRiskProfileDetTtlScore; ?></span>
                                                                <?php
                                                            }
                                                            ?>
                                                        </div>
                                                    </div>
                                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                        <label for="oneRiskProfileDetIsEnbld" class="control-label col-lg-4" style="padding:0px 15px 0px 1px !important;">Is Enabled:</label>
                                                        <div  class="col-lg-8" style="padding:0px 15px 0px 1px !important;">
                                                            <?php if ($canEdtRiskProfile === true) { ?>
                                                                <select class="form-control" id="oneRiskProfileDetIsEnbld" >
                                                                    <?php
                                                                    $sltdYes = "";
                                                                    $sltdNo = "";
                                                                    if ($oneRiskProfileDetIsEnbld == "Yes") {
                                                                        $sltdYes = "selected";
                                                                    } else if ($oneRiskProfileDetIsEnbld == "No") {
                                                                        $sltdNo = "selected";
                                                                    }
                                                                    ?>
                                                                    <option value="Yes" <?php echo $sltdYes; ?>>Yes</option>
                                                                    <option value="No" <?php echo $sltdNo; ?>>No</option>
                                                                </select>
                                                            <?php } else {
                                                                ?>
                                                                <span><?php echo $oneRiskProfileDetIsEnbld; ?></span>
                                                                <?php
                                                            }
                                                            ?>
                                                        </div>
                                                    </div>
                                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                        <label for="oneRiskProfileDetValidity" class="control-label col-lg-4" style="padding:0px 15px 0px 1px !important;">Status:</label>
                                                        <div  class="col-lg-8" style="padding:0px 15px 0px 1px !important;">
                                                            <span id="oneRiskProfileDetValidity" style="color:<?php echo $vldtyColor; ?>;font-weight: bold;"><?php echo $vldtyStatus; ?></span>                                                            
                                                        </div>
                                                    </div>                                                    
                                                </div>
                                            </fieldset>
                                    </div> 
                                    <div class="row" id="allRiskProfileFctrsDetailInfo" style="padding:0px 15px 0px 15px !important">
                                        <?php
                                        /* &vtyp=<?php echo $vwtyp; ?> */
                                        $srchFor = "%";
                                        $srchIn = "Risk Factor";
                                        $pageNo = 1;
                                        $lmtSze = 10;
                                        $vwtyp = 1;
                                        if ($sbmtdRiskProfileID > 0) {
                                            $total = get_AllRiskProfileFctrsTtl($srchFor, $srchIn, $sbmtdRiskProfileID);
                                            //$total = get_AllBanksTtl($srchFor, $srchIn, $sbmtdRiskProfileID);
                                            if ($pageNo > ceil($total / $lmtSze)) {
                                                $pageNo = 1;
                                            } else if ($pageNo < 1) {
                                                $pageNo = ceil($total / $lmtSze);
                                            }
                                            $curIdx = $pageNo - 1;
                                            $result2 = get_AllRiskProfileFctrs($srchFor, $srchIn, $curIdx, $lmtSze, $sbmtdRiskProfileID);
                                            ?>
                                            <div class="row" style="padding:0px 15px 0px 15px !important">
                                                <legend class="basic_person_lg1" style="color: #003245">RISK PROFILE FACTORS</legend>
                                                <?php
                                                if ($canEdtRiskProfile === true) {
                                                    $colClassType1 = "col-lg-2";
                                                    $colClassType2 = "col-lg-3";
                                                    $colClassType3 = "col-lg-4";
                                                    $nwRowHtml = urlencode("<tr id=\"allRiskProfileFctrsRow__WWW123WWW\">"
                                                                    . "<td class=\"lovtd\"><span class=\"normaltd\">New</span></td>"
                                                                    . "<td class=\"lovtd\">
                                                                            <div class=\"input-group\">
                                                                                <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"allRiskProfileFctrsRow_WWW123WWW_RiskFactorNm\" name=\"allRiskProfileFctrsRow_WWW123WWW_RiskFactorNm\" value=\"\" readonly=\"true\">
                                                                                <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'MCF Credit Risk Factors', 'gnrlOrgID', '', '', 'radio', true, '', 'allRiskProfileFctrsRow_WWW123WWW_RiskFactorID', 'allRiskProfileFctrsRow_WWW123WWW_RiskFactorNm', 'clear', 1, '');\">
                                                                                        <span class=\"glyphicon glyphicon-th-list\"></span>
                                                                                </label>
                                                                                <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"allRiskProfileFctrsRow_WWW123WWW_RiskFactorID\" value=\"-1\" style=\"width:100% !important;\"> 
                                                                            </div>
                                                                            <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"allRiskProfileFctrsRow_WWW123WWW_RiskProfileFactorID\" value=\"-1\" style=\"width:100% !important;\">                                                                                                                          
                                                                        </td>                                             
                                                                        <td class=\"lovtd\">
                                                                                <input type=\"number\" min=\"0\" class=\"form-control rqrdFld\" aria-label=\"...\" id=\"allRiskProfileFctrsRow_WWW123WWW_Score\" name=\"allRiskProfileFctrsRow_WWW123WWW_Score\" value=\"\">                                                               
                                                                        </td>
                                                                        <td class=\"lovtd\">
                                                                                <input type=\"number\" min=\"0\" class=\"form-control rqrdFld\" aria-label=\"...\" id=\"allRiskProfileFctrsRow_WWW123WWW_SortOrder\" name=\"allRiskProfileFctrsRow_WWW123WWW_SortOrder\" value=\"\">                                                               
                                                                        </td>
                                                                        <td class=\"lovtd\" style=\"display:none;\">
                                                                                <span id=\"allRiskProfileFctrsRow_WWW123WWW_TtlOptnsScore\"></span>                                                              
                                                                        </td>
                                                                        <td class=\"lovtd\">
                                                                                <span></span>                                                              
                                                                        </td>
                                                                        <td class=\"lovtd\">
                                                                            <button type=\"button\" class=\"btn btn-default\" style=\"margin: 0px !important;padding:0px 3px 2px 4px !important;\" onclick=\"delRiskProfileFctr('allRiskProfileFctrsRow__WWW123WWW');\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Delete Profile Factor\">
                                                                                    <img src=\"cmn_images/no.png\" style=\"height:15px; width:auto; position: relative; vertical-align: middle;\">
                                                                            </button>
                                                                        </td>
                                                    </tr>");
                                                    ?>
                                                    <div class="<?php echo $colClassType1; ?>" style="padding:0px 1px 0px 3px !important;">     
                                                        <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="insertNewRowBe4('allRiskProfileFctrsTable', 0, '<?php echo $nwRowHtml; ?>');" data-toggle="tooltip" data-placement="bottom" title="Add Profile Factor">
                                                            <img src="cmn_images/add1-64.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                        </button>
                                                        <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="saveRiskProfileFctr();" data-toggle="tooltip" data-placement="bottom" title="Save Profile Factor">
                                                            <img src="cmn_images/FloppyDisk.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                        </button>
                                                    </div>
                                                    <?php
                                                } else {
                                                    $colClassType1 = "col-lg-4";
                                                    $colClassType2 = "col-lg-4";
                                                    $colClassType3 = "col-lg-4";
                                                }
                                                ?>
                                                <div class="<?php echo $colClassType3; ?>" style="padding:0px 3px 0px 3px !important;">
                                                    <div class="input-group">
                                                        <input class="form-control" id="allRiskProfileFctrsSrchFor" type = "text" placeholder="Search For" value="<?php
                                                        echo trim(str_replace("%", " ", $srchFor));
                                                        ?>" onkeyup="enterKeyFuncAllRiskProfileFctrs(event, '', '#allRiskProfileFctrsDetailInfo', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&subPgNo=<?php echo $subPgNo; ?>&subVwtyp=<?php echo "3.2"; ?>&sbmtdRiskProfileID=<?php echo $sbmtdRiskProfileID; ?>');">
                                                        <input id="allRiskProfileFctrsPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getAllRiskProfileFctrs('clear', '#allRiskProfileFctrsDetailInfo', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&subPgNo=<?php echo $subPgNo; ?>&subVwtyp=<?php echo "3.2"; ?>&sbmtdRiskProfileID=<?php echo $sbmtdRiskProfileID; ?>');">
                                                            <span class="glyphicon glyphicon-remove"></span>
                                                        </label>
                                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getAllRiskProfileFctrs('', '#allRiskProfileFctrsDetailInfo', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&subPgNo=<?php echo $subPgNo; ?>&subVwtyp=<?php echo "3.2"; ?>&sbmtdRiskProfileID=<?php echo $sbmtdRiskProfileID; ?>');">
                                                            <span class="glyphicon glyphicon-search"></span>
                                                        </label> 
                                                    </div>
                                                </div>
                                                <div class="<?php echo $colClassType2; ?>" style="padding:0px 3px 0px 3px !important;">
                                                    <div class="input-group">
                                                        <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                                        <!--<select data-placeholder="Select..." class="form-control chosen-select" id="allRiskProfileFctrsSrchIn">
                                                        <?php
                                                        $valslctdArry = array("");
                                                        $srchInsArrys = array("Name");

                                                        for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                            if ($srchIn == $srchInsArrys[$z]) {
                                                                $valslctdArry[$z] = "selected";
                                                            }
                                                            ?>
                                                                                                                    <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                                        <?php } ?>
                                                        </select>-->
                                                        <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                                        <select data-placeholder="Select..." class="form-control chosen-select" id="allRiskProfileFctrsDsplySze" style="min-width:70px !important;">                            
                                                            <?php
                                                            $valslctdArry = array("", "", "", "", "", "", "", "");
                                                            $dsplySzeArry = array(1, 5, 10, 15, 30, 50, 100, 500, 1000);
                                                            for ($y = 0; $y < count($dsplySzeArry); $y++) {
                                                                if ($lmtSze == $dsplySzeArry[$y]) {
                                                                    $valslctdArry[$y] = "selected";
                                                                } else {
                                                                    $valslctdArry[$y] = "";
                                                                }
                                                                ?>
                                                                <option value="<?php echo $dsplySzeArry[$y]; ?>" <?php echo $valslctdArry[$y]; ?>><?php echo $dsplySzeArry[$y]; ?></option>                            
                                                                <?php
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="<?php echo $colClassType2; ?>" style="padding:0px 3px 0px 3px !important;">
                                                    <nav aria-label="Page navigation">
                                                        <ul class="pagination" style="margin: 0px !important;">
                                                            <li>
                                                                <a class="rhopagination" href="javascript:getAllRiskProfileFctrs('previous', '#allRiskProfileFctrsDetailInfo', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&subPgNo=<?php echo $subPgNo; ?>&subVwtyp=<?php echo "3.2"; ?>&sbmtdRiskProfileID=<?php echo $sbmtdRiskProfileID; ?>');" aria-label="Previous">
                                                                    <span aria-hidden="true">&laquo;</span>
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a class="rhopagination" href="javascript:getAllRiskProfileFctrs('next', '#allRiskProfileFctrsDetailInfo', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&subPgNo=<?php echo $subPgNo; ?>&subVwtyp=<?php echo "3.2"; ?>&sbmtdRiskProfileID=<?php echo $sbmtdRiskProfileID; ?>');" aria-label="Next">
                                                                    <span aria-hidden="true">&raquo;</span>
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </nav>
                                                    <input type="hidden" class="form-control" aria-label="..." id="gnrlOrgID" name="gnrlOrgID" value="<?php echo $orgID; ?>">                                                    
                                                    <input type="hidden" class="form-control" aria-label="..." id="sbmtdRiskProfileID" name="sbmtdRiskProfileID" value="<?php echo $sbmtdRiskProfileID; ?>">
                                                </div>
                                            </div>
                                            <div class="row" style="padding:0px 15px 0px 15px !important">                  
                                                <div class="col-md-12" style="padding:0px 3px 0px 3px !important">
                                                    <table class="table table-striped table-bordered table-responsive" id="allRiskProfileFctrsTable" cellspacing="0" width="100%" style="width:100%;min-width: 300px !important;">
                                                        <thead>
                                                            <tr>
                                                                <th>No.</th>
                                                                <th>Risk Factor</th>
                                                                <th>Score</th>
                                                                <th>Sort Order</th>
                                                                <th style="display:none;">Ttl Option Score</th>
                                                                <th>&nbsp;</th>
                                                                <th>&nbsp;</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $cntr = 0;
                                                            while ($row2 = loc_db_fetch_array($result2)) {                                                                
                                                                $cntr += 1;
                                                                $ttlOptnsScore = getRiskProfileFctrOptnsMaxScore($row2[0]);
                                                                ?>
                                                                <tr id="allRiskProfileFctrsRow_<?php echo $cntr; ?>">                                    
                                                                    <td class="lovtd"><span><?php echo ($curIdx * $lmtSze) + ($cntr); ?></span></td>
                                                                    <td class="lovtd">
                                                                        <input type="hidden" class="form-control" aria-label="..." id="allRiskProfileFctrsRow<?php echo $cntr; ?>_RiskProfileFactorID" value="<?php echo $row2[0]; ?>" style="width:100% !important;"> 
                                                                        <input type="hidden" class="form-control" aria-label="..." id="allRiskProfileFctrsRow<?php echo $cntr; ?>_RiskProfileID" value="<?php echo $row2[1]; ?>" style="width:100% !important;">                                                                         
                                                                        <input type="hidden" class="form-control" aria-label="..." id="allRiskProfileFctrsRow<?php echo $cntr; ?>_RiskFactorID" value="<?php echo $row2[2]; ?>" readonly="true"> 
                                                                        <div class="input-group">
                                                                            <input type="text" class="form-control" aria-label="..." id="allRiskProfileFctrsRow<?php echo $cntr; ?>_RiskFactorNm" name="allRiskProfileFctrsRow<?php echo $cntr; ?>__RiskFactorNm" value="<?php echo $row2[3]; ?>" readonly="true">
                                                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'MCF Credit Risk Factors', 'gnrlOrgID', '', '', 'radio', true, '', 'allRiskProfileFctrsRow<?php echo $cntr; ?>__RiskFactorID', 'allRiskProfileFctrsRow<?php echo $cntr; ?>__RiskFactorNm', 'clear', 1, '');">
                                                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                                            </label>
                                                                        </div>                                                                        
                                                                    </td>                                             
                                                                    <td class="lovtd"> 
                                                                        <input type="number" min="0" class="form-control rqrdFld" aria-label="..." id="allRiskProfileFctrsRow<?php echo $cntr; ?>_Score" name="allRiskProfileFctrsRow<?php echo $cntr; ?>_Score" value="<?php echo $row2[4]; ?>">
                                                                    </td>                                            
                                                                    <td class="lovtd">  
                                                                        <input type="number" min="0" class="form-control rqrdFld" aria-label="..." id="allRiskProfileFctrsRow<?php echo $cntr; ?>_SortOrder" name="allRiskProfileFctrsRow<?php echo $cntr; ?>_SortOrder" value="<?php echo $row2[5]; ?>">                                                               
                                                                    </td>
                                                                    <td class="lovtd" style="display:none;">
                                                                        <span id="allRiskProfileFctrsRow<?php echo $cntr; ?>_TtlOptnsScore"><?php echo $ttlOptnsScore; ?></span>                                                              
                                                                    </td>
                                                                    <td class="lovtd">
                                                                        <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="getRiskProfileFctrOptnsForm('allRiskProfileFctrsRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Add Profile Options">
                                                                            <img src="cmn_images/add1-64.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                                        </button>
                                                                    </td>
                                                                    <td class="lovtd">
                                                                        <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delRiskProfileFctr('allRiskProfileFctrsRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Risk Profile">
                                                                            <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                                        </button>
                                                                    </td>
                                                                </tr>
                                                                <?php
                                                            }
                                                            ?>
                                                        </tbody>
                                                    </table>                        
                                                </div>                
                                            </div>
                                            <?php
                                        } else {
                                            ?>
                                            <span>No Results Found</span>
                                            <?php
                                        }
                                        ?> 
                                    </div>                                   
                                </div>
                            </fieldset>
                        </div>
                    </div>
                <!--</fieldset>-->
            </form>
            <?php
        } 
        else if ($subVwtyp == 3.1) {
            $sbmtdRiskProfileID = isset($_POST['sbmtdRiskProfileID']) ? cleanInputData($_POST['sbmtdRiskProfileID']) : -1;                    
            ?>
            <div class="row" id="allRiskProfilesHdrInfo" style="padding:0px 15px 0px 15px !important">
                <?php
                $oneRiskProfileDetID = -1;
                $oneRiskProfileDetName = "";
                $oneRiskProfileDetDesc = "";
                $oneRiskProfileDetTtlScore = 0;
                $oneRiskProfileDetIsEnbld = "Yes";
                $vldtyColor = "red";
                $vldtyStatus = "Invalid";
                $result1 = getCreditRiskProfilesDets($sbmtdRiskProfileID);
                while ($row1 = loc_db_fetch_array($result1)) {
                    $oneRiskProfileDetID = $row1[0];
                    $oneRiskProfileDetName = $row1[1];
                    $oneRiskProfileDetDesc = $row1[2];
                    $oneRiskProfileDetTtlScore = $row1[3];
                    $oneRiskProfileDetIsEnbld = $row1[4];
                    $vldtyStatus = $row1[6];
                    if($vldtyStatus == "Valid"){
                        $vldtyColor = "green";
                    }
                } 
                ?>                            
                    <fieldset class="basic_person_fs" style="padding:10px 3px 0px 3px !important;border:none !important;border-bottom:1px solid #ddd !important;border-radius: 0px !important;">                           
                        <div  class="col-md-6" style="padding:0px 3px 0px 3px !important;">
                            <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                <label for="oneRiskProfileDetName" class="control-label col-lg-4" style="padding:0px 15px 0px 1px !important;">Risk Profile:</label>
                                <div  class="col-lg-8" style="padding:0px 15px 0px 1px !important;">
                                    <?php if ($canEdtRiskProfile === true) { ?>
                                        <input type="text" class="form-control rqrdFld" aria-label="..." id="oneRiskProfileDetName" name="oneRiskProfileDetName" value="<?php echo $oneRiskProfileDetName; ?>" style="width:100%;">
                                        <input type="hidden" class="form-control" aria-label="..." id="oneRiskProfileDetID" name="oneRiskProfileDetID" value="<?php echo $oneRiskProfileDetID; ?>">
                                    <?php } else {
                                        ?>
                                        <span><?php echo $oneRiskProfileDetName; ?></span>
                                        <?php
                                    }
                                    ?>
                                </div>
                            </div>
                            <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                <label for="oneRiskProfileDetDesc" class="control-label col-lg-4" style="padding:0px 15px 0px 1px !important;">Description:</label>
                                <div  class="col-lg-8" style="padding:0px 15px 0px 1px !important;">
                                    <?php if ($canEdtRiskProfile === true) { ?>
                                        <textarea class="form-control" aria-label="..." id="oneRiskProfileDetDesc" name="oneRiskProfileDetDesc" style="width:100%;" cols="5" rows="3"><?php echo $oneRiskProfileDetDesc; ?></textarea>
                                    <?php } else {
                                        ?>
                                        <span><?php echo $oneRiskProfileDetDesc; ?></span>
                                        <?php
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div  class="col-md-6" style="padding:0px 3px 0px 3px !important;"> 
                            <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                <label for="oneRiskProfileDetTtlScore" class="control-label col-lg-4" style="padding:0px 15px 0px 1px !important;">Total Score:</label>
                                <div class="col-lg-8" style="padding:0px 15px 0px 1px !important;">
                                    <?php if ($canEdtRiskProfile === true) { ?>
                                        <input type="number" min="0" class="form-control rqrdFld" aria-label="..." id="oneRiskProfileDetTtlScore" name="oneRiskProfileDetTtlScore" value="<?php echo $oneRiskProfileDetTtlScore; ?>" style="width:100%;">
                                    <?php } else {
                                        ?>
                                        <span><?php echo $oneRiskProfileDetTtlScore; ?></span>
                                        <?php
                                    }
                                    ?>
                                </div>
                            </div>
                            <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                <label for="oneRiskProfileDetIsEnbld" class="control-label col-lg-4" style="padding:0px 15px 0px 1px !important;">Is Enabled:</label>
                                <div  class="col-lg-8" style="padding:0px 15px 0px 1px !important;">
                                    <?php if ($canEdtRiskProfile === true) { ?>
                                        <select class="form-control" id="oneRiskProfileDetIsEnbld" >
                                            <?php
                                            $sltdYes = "";
                                            $sltdNo = "";
                                            if ($oneRiskProfileDetIsEnbld == "Yes") {
                                                $sltdYes = "selected";
                                            } else if ($oneRiskProfileDetIsEnbld == "No") {
                                                $sltdNo = "selected";
                                            }
                                            ?>
                                            <option value="Yes" <?php echo $sltdYes; ?>>Yes</option>
                                            <option value="No" <?php echo $sltdNo; ?>>No</option>
                                        </select>                                   
                                    <?php } else {
                                        ?>
                                        <span><?php echo $row1[4]; ?></span>
                                        <?php
                                    }
                                    ?>
                                </div>
                            </div>
                            <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                <label for="oneRiskProfileDetValidity" class="control-label col-lg-4" style="padding:0px 15px 0px 1px !important;">Status:</label>
                                <div  class="col-lg-8" style="padding:0px 15px 0px 1px !important;">
                                    <span id="oneRiskProfileDetValidity" style="color:<?php echo $vldtyColor; ?>;font-weight: bold;"><?php echo $vldtyStatus; ?></span>                                                            
                                </div>
                            </div>
                        </div>
                    </fieldset>
            </div>
            <div class="row" id="allRiskProfileFctrsDetailInfo" style="padding:0px 15px 0px 15px !important">
                <?php
                /* &vtyp=<?php echo $vwtyp; ?> */
                $srchFor = "%";
                $srchIn = "Risk Factor";
                $pageNo = 1;
                $lmtSze = 10;
                $vwtyp = 1;
                if ($sbmtdRiskProfileID > 0) {
                    $total = get_AllRiskProfileFctrsTtl($srchFor, $srchIn, $sbmtdRiskProfileID);
                    //$total = get_AllBanksTtl($srchFor, $srchIn, $sbmtdRiskProfileID);
                    if ($pageNo > ceil($total / $lmtSze)) {
                        $pageNo = 1;
                    } else if ($pageNo < 1) {
                        $pageNo = ceil($total / $lmtSze);
                    }
                    $curIdx = $pageNo - 1;
                    $result2 = get_AllRiskProfileFctrs($srchFor, $srchIn, $curIdx, $lmtSze, $sbmtdRiskProfileID);
                    ?>
                    <div class="row" style="padding:0px 15px 0px 15px !important">
                        <legend class="basic_person_lg1" style="color: #003245">RISK PROFILE FACTORS</legend>
                        <?php
                        if ($canEdtRiskProfile === true) {
                            $colClassType1 = "col-lg-2";
                            $colClassType2 = "col-lg-3";
                            $colClassType3 = "col-lg-4";
                            $nwRowHtml = urlencode("<tr id=\"allRiskProfileFctrsRow__WWW123WWW\">"
                                            . "<td class=\"lovtd\"><span class=\"normaltd\">New</span></td>"
                                            . "<td class=\"lovtd\">
                                                    <div class=\"input-group\">
                                                        <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"allRiskProfileFctrsRow_WWW123WWW_RiskFactorNm\" name=\"allRiskProfileFctrsRow_WWW123WWW_RiskFactorNm\" value=\"\" readonly=\"true\">
                                                        <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'MCF Credit Risk Factors', 'gnrlOrgID', '', '', 'radio', true, '', 'allRiskProfileFctrsRow_WWW123WWW_RiskFactorID', 'allRiskProfileFctrsRow_WWW123WWW_RiskFactorNm', 'clear', 1, '');\">
                                                                <span class=\"glyphicon glyphicon-th-list\"></span>
                                                        </label>
                                                        <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"allRiskProfileFctrsRow_WWW123WWW_RiskFactorID\" value=\"-1\" style=\"width:100% !important;\"> 
                                                    </div>
                                                    <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"allRiskProfileFctrsRow_WWW123WWW_RiskProfileFactorID\" value=\"-1\" style=\"width:100% !important;\">                                                                                                                          
                                                </td>                                             
                                                <td class=\"lovtd\">
                                                        <input type=\"number\" min=\"0\" class=\"form-control rqrdFld\" aria-label=\"...\" id=\"allRiskProfileFctrsRow_WWW123WWW_Score\" name=\"allRiskProfileFctrsRow_WWW123WWW_Score\" value=\"\">                                                               
                                                </td>
                                                <td class=\"lovtd\">
                                                        <input type=\"number\" min=\"0\" class=\"form-control rqrdFld\" aria-label=\"...\" id=\"allRiskProfileFctrsRow_WWW123WWW_SortOrder\" name=\"allRiskProfileFctrsRow_WWW123WWW_SortOrder\" value=\"\">                                                               
                                                </td>
                                                <td class=\"lovtd\" style=\"display:none;\">
                                                        <span id=\"allRiskProfileFctrsRow_WWW123WWW_TtlOptnsScore\"></span>                                                              
                                                </td>
                                                <td class=\"lovtd\">
                                                        <span></span>                                                              
                                                </td>                                                
                                                <td class=\"lovtd\">
                                                    <button type=\"button\" class=\"btn btn-default\" style=\"margin: 0px !important;padding:0px 3px 2px 4px !important;\" onclick=\"delRiskProfileFctr('allRiskProfileFctrsRow__WWW123WWW');\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Delete Profile Factor\">
                                                            <img src=\"cmn_images/no.png\" style=\"height:15px; width:auto; position: relative; vertical-align: middle;\">
                                                    </button>
                                                </td>
                            </tr>");
                            ?>
                            <div class="<?php echo $colClassType1; ?>" style="padding:0px 1px 0px 3px !important;">     
                                <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="insertNewRowBe4('allRiskProfileFctrsTable', 0, '<?php echo $nwRowHtml; ?>');" data-toggle="tooltip" data-placement="bottom" title="Add Profile Factor">
                                    <img src="cmn_images/add1-64.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                </button>
                                <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="saveRiskProfileFctr();" data-toggle="tooltip" data-placement="bottom" title="Save Profile Factor">
                                    <img src="cmn_images/FloppyDisk.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                </button>
                            </div>
                            <?php
                        } else {
                            $colClassType1 = "col-lg-4";
                            $colClassType2 = "col-lg-4";
                            $colClassType3 = "col-lg-4";
                        }
                        ?>
                        <div class="<?php echo $colClassType3; ?>" style="padding:0px 3px 0px 3px !important;">
                            <div class="input-group">
                                <input class="form-control" id="allRiskProfileFctrsSrchFor" type = "text" placeholder="Search For" value="<?php
                                echo trim(str_replace("%", " ", $srchFor));
                                ?>" onkeyup="enterKeyFuncAllRiskProfileFctrs(event, '', '#allRiskProfileFctrsDetailInfo', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&subPgNo=<?php echo $subPgNo; ?>&subVwtyp=<?php echo "3.2"; ?>&sbmtdRiskProfileID=<?php echo $sbmtdRiskProfileID; ?>');">
                                <input id="allRiskProfileFctrsPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                <label class="btn btn-primary btn-file input-group-addon" onclick="getAllRiskProfileFctrs('clear', '#allRiskProfileFctrsDetailInfo', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&subPgNo=<?php echo $subPgNo; ?>&subVwtyp=<?php echo "3.2"; ?>&sbmtdRiskProfileID=<?php echo $sbmtdRiskProfileID; ?>');">
                                    <span class="glyphicon glyphicon-remove"></span>
                                </label>
                                <label class="btn btn-primary btn-file input-group-addon" onclick="getAllRiskProfileFctrs('', '#allRiskProfileFctrsDetailInfo', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&subPgNo=<?php echo $subPgNo; ?>&subVwtyp=<?php echo "3.2"; ?>&sbmtdRiskProfileID=<?php echo $sbmtdRiskProfileID; ?>');">
                                    <span class="glyphicon glyphicon-search"></span>
                                </label> 
                            </div>
                        </div>
                        <div class="<?php echo $colClassType2; ?>" style="padding:0px 3px 0px 3px !important;">
                            <div class="input-group">
                                <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                <!--<select data-placeholder="Select..." class="form-control chosen-select" id="allRiskProfileFctrsSrchIn">
                                <?php
                                $valslctdArry = array("");
                                $srchInsArrys = array("Name");

                                for ($z = 0; $z < count($srchInsArrys); $z++) {
                                    if ($srchIn == $srchInsArrys[$z]) {
                                        $valslctdArry[$z] = "selected";
                                    }
                                    ?>
                                                                                            <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                <?php } ?>
                                </select>-->
                                <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                <select data-placeholder="Select..." class="form-control chosen-select" id="allRiskProfileFctrsDsplySze" style="min-width:70px !important;">                            
                                    <?php
                                    $valslctdArry = array("", "", "", "", "", "", "", "");
                                    $dsplySzeArry = array(1, 5, 10, 15, 30, 50, 100, 500, 1000);
                                    for ($y = 0; $y < count($dsplySzeArry); $y++) {
                                        if ($lmtSze == $dsplySzeArry[$y]) {
                                            $valslctdArry[$y] = "selected";
                                        } else {
                                            $valslctdArry[$y] = "";
                                        }
                                        ?>
                                        <option value="<?php echo $dsplySzeArry[$y]; ?>" <?php echo $valslctdArry[$y]; ?>><?php echo $dsplySzeArry[$y]; ?></option>                            
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="<?php echo $colClassType2; ?>" style="padding:0px 3px 0px 3px !important;">
                            <nav aria-label="Page navigation">
                                <ul class="pagination" style="margin: 0px !important;">
                                    <li>
                                        <a class="rhopagination" href="javascript:getAllRiskProfileFctrs('previous', '#allRiskProfileFctrsDetailInfo', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&subPgNo=<?php echo $subPgNo; ?>&subVwtyp=<?php echo "3.2"; ?>&sbmtdRiskProfileID=<?php echo $sbmtdRiskProfileID; ?>');" aria-label="Previous">
                                            <span aria-hidden="true">&laquo;</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="rhopagination" href="javascript:getAllRiskProfileFctrs('next', '#allRiskProfileFctrsDetailInfo', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&subPgNo=<?php echo $subPgNo; ?>&subVwtyp=<?php echo "3.2"; ?>&sbmtdRiskProfileID=<?php echo $sbmtdRiskProfileID; ?>');" aria-label="Next">
                                            <span aria-hidden="true">&raquo;</span>
                                        </a>
                                    </li>
                                </ul>
                            </nav>
                            <input type="hidden" class="form-control" aria-label="..." id="gnrlOrgID" name="gnrlOrgID" value="<?php echo $orgID; ?>">                                                    
                            <input type="hidden" class="form-control" aria-label="..." id="sbmtdRiskProfileID" name="sbmtdRiskProfileID" value="<?php echo $sbmtdRiskProfileID; ?>">
                        </div>
                    </div>
                    <div class="row" style="padding:0px 15px 0px 15px !important">                  
                        <div class="col-md-12" style="padding:0px 3px 0px 3px !important">
                            <table class="table table-striped table-bordered table-responsive" id="allRiskProfileFctrsTable" cellspacing="0" width="100%" style="width:100%;min-width: 300px !important;">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Risk Factor</th>
                                        <th>Score</th>
                                        <th>Sort Order</th>
                                        <th style="display:none;">Ttl Options Score</th>
                                        <th>&nbsp;</th>
                                        <th>&nbsp;</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $cntr = 0;
                                    while ($row2 = loc_db_fetch_array($result2)) {
                                        $cntr += 1;
                                        $ttlOptnsScore = getRiskProfileFctrOptnsMaxScore($row2[0]);
                                        ?>
                                        <tr id="allRiskProfileFctrsRow_<?php echo $cntr; ?>">                                    
                                            <td class="lovtd"><span><?php echo ($curIdx * $lmtSze) + ($cntr); ?></span></td>
                                            <td class="lovtd">
                                                <input type="hidden" class="form-control" aria-label="..." id="allRiskProfileFctrsRow<?php echo $cntr; ?>_RiskProfileFactorID" value="<?php echo $row2[0]; ?>" style="width:100% !important;"> 
                                                <input type="hidden" class="form-control" aria-label="..." id="allRiskProfileFctrsRow<?php echo $cntr; ?>_RiskProfileID" value="<?php echo $row2[1]; ?>" style="width:100% !important;">                                                                         
                                                <input type="hidden" class="form-control" aria-label="..." id="allRiskProfileFctrsRow<?php echo $cntr; ?>_RiskFactorID" value="<?php echo $row2[2]; ?>" readonly="true"> 
                                                <div class="input-group">
                                                    <input type="text" class="form-control" aria-label="..." id="allRiskProfileFctrsRow<?php echo $cntr; ?>_RiskFactorNm" name="allRiskProfileFctrsRow<?php echo $cntr; ?>__RiskFactorNm" value="<?php echo $row2[3]; ?>" readonly="true">
                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'MCF Credit Risk Factors', 'gnrlOrgID', '', '', 'radio', true, '', 'allRiskProfileFctrsRow<?php echo $cntr; ?>__RiskFactorID', 'allRiskProfileFctrsRow<?php echo $cntr; ?>__RiskFactorNm', 'clear', 1, '');">
                                                            <span class="glyphicon glyphicon-th-list"></span>
                                                    </label>
                                                </div>                                                                        
                                            </td>                                             
                                            <td class="lovtd"> 
                                                <input type="number" min="0" class="form-control rqrdFld" aria-label="..." id="allRiskProfileFctrsRow<?php echo $cntr; ?>_Score" name="allRiskProfileFctrsRow<?php echo $cntr; ?>_Score" value="<?php echo $row2[4]; ?>">
                                            </td>                                            
                                            <td class="lovtd">  
                                                <input type="number" min="0" class="form-control rqrdFld" aria-label="..." id="allRiskProfileFctrsRow<?php echo $cntr; ?>_SortOrder" name="allRiskProfileFctrsRow<?php echo $cntr; ?>_SortOrder" value="<?php echo $row2[5]; ?>">                                                               
                                            </td>
                                            <td class="lovtd" style="display:none;">
                                                <span id="allRiskProfileFctrsRow<?php echo $cntr; ?>_TtlOptnsScore"><?php echo $ttlOptnsScore; ?></span>                                                              
                                            </td>	
                                            <td class="lovtd">
                                                <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="getRiskProfileFctrOptnsForm('allRiskProfileFctrsRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Add Profile Options">
                                                    <img src="cmn_images/add1-64.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                </button>
                                            </td>
                                            <td class="lovtd">
                                                <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delRiskProfileFctr('allRiskProfileFctrsRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Risk Profile">
                                                    <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                </button>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                </tbody>
                            </table>                        
                        </div>                
                    </div>
                    <?php
                } else {
                    ?>
                    <span>No Results Found</span>
                    <?php
                }
                ?> 
            </div>                     
            <?php
        } 
        else if ($subVwtyp == 3.2) {
            $sbmtdRiskProfileID = isset($_POST['sbmtdRiskProfileID']) ? cleanInputData($_POST['sbmtdRiskProfileID']) : -1;                    
            if ($sbmtdRiskProfileID > 0) {
                $total = get_AllRiskProfileFctrsTtl($srchFor, $srchIn, $sbmtdRiskProfileID);
                //$total = get_AllBanksTtl($srchFor, $srchIn, $sbmtdRiskProfileID);
                if ($pageNo > ceil($total / $lmtSze)) {
                    $pageNo = 1;
                } else if ($pageNo < 1) {
                    $pageNo = ceil($total / $lmtSze);
                }
                $curIdx = $pageNo - 1;
                $result2 = get_AllRiskProfileFctrs($srchFor, $srchIn, $curIdx, $lmtSze, $sbmtdRiskProfileID);
                ?>
                <div class="row" style="padding:0px 15px 0px 15px !important">
                    <legend class="basic_person_lg1" style="color: #003245">RISK PROFILE FACTORS</legend>
                    <?php
                    if ($canEdtRiskProfile === true) {
                        $colClassType1 = "col-lg-2";
                        $colClassType2 = "col-lg-3";
                        $colClassType3 = "col-lg-4";
                        $nwRowHtml = urlencode("<tr id=\"allRiskProfileFctrsRow__WWW123WWW\">"
                                        . "<td class=\"lovtd\"><span class=\"normaltd\">New</span></td>"
                                        . "<td class=\"lovtd\">
                                                <div class=\"input-group\">
                                                    <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"allRiskProfileFctrsRow_WWW123WWW_RiskFactorNm\" name=\"allRiskProfileFctrsRow_WWW123WWW_RiskFactorNm\" value=\"\" readonly=\"true\">
                                                    <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'MCF Credit Risk Factors', 'gnrlOrgID', '', '', 'radio', true, '', 'allRiskProfileFctrsRow_WWW123WWW_RiskFactorID', 'allRiskProfileFctrsRow_WWW123WWW_RiskFactorNm', 'clear', 1, '');\">
                                                            <span class=\"glyphicon glyphicon-th-list\"></span>
                                                    </label>
                                                    <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"allRiskProfileFctrsRow_WWW123WWW_RiskFactorID\" value=\"-1\" style=\"width:100% !important;\"> 
                                                </div>
                                                <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"allRiskProfileFctrsRow_WWW123WWW_RiskProfileFactorID\" value=\"-1\" style=\"width:100% !important;\">                                                                                                                          
                                            </td>                                             
                                            <td class=\"lovtd\">
                                                    <input type=\"number\" min=\"0\" class=\"form-control rqrdFld\" aria-label=\"...\" id=\"allRiskProfileFctrsRow_WWW123WWW_Score\" name=\"allRiskProfileFctrsRow_WWW123WWW_Score\" value=\"\">                                                               
                                            </td>
                                            <td class=\"lovtd\">
                                                    <input type=\"number\" min=\"0\" class=\"form-control rqrdFld\" aria-label=\"...\" id=\"allRiskProfileFctrsRow_WWW123WWW_SortOrder\" name=\"allRiskProfileFctrsRow_WWW123WWW_SortOrder\" value=\"\">                                                               
                                            </td>
                                            <td class=\"lovtd\" style=\"display:none;\">
                                                <span id=\"allRiskProfileFctrsRow_WWW123WWW_TtlOptnsScore\"></span>                                                              
                                            </td>
                                            <td class=\"lovtd\">
                                                    <span></span>                                                              
                                            </td>
                                            <td class=\"lovtd\">
                                                <button type=\"button\" class=\"btn btn-default\" style=\"margin: 0px !important;padding:0px 3px 2px 4px !important;\" onclick=\"delRiskProfileFctr('allRiskProfileFctrsRow__WWW123WWW');\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Delete Profile Factor\">
                                                        <img src=\"cmn_images/no.png\" style=\"height:15px; width:auto; position: relative; vertical-align: middle;\">
                                                </button>
                                            </td>
                        </tr>");                                                
                        ?> 
                        <div class="<?php echo $colClassType1; ?>" style="padding:0px 1px 0px 3px !important;">     
                            <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="insertNewRowBe4('allRiskProfileFctrsTable', 0, '<?php echo $nwRowHtml; ?>');" data-toggle="tooltip" data-placement="bottom" title="Add Profile Factor">
                                <img src="cmn_images/add1-64.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                            </button>
                            <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="saveRiskProfileFctr();" data-toggle="tooltip" data-placement="bottom" title="Save Profile Factor">
                                <img src="cmn_images/FloppyDisk.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                            </button>
                        </div>
                        <?php
                    } else {
                        $colClassType1 = "col-lg-4";
                        $colClassType2 = "col-lg-4";
                        $colClassType3 = "col-lg-4";
                    }
                    ?>
                    <div class="<?php echo $colClassType3; ?>" style="padding:0px 3px 0px 3px !important;">
                        <div class="input-group">
                            <input class="form-control" id="allRiskProfileFctrsSrchFor" type = "text" placeholder="Search For" value="<?php
                            echo trim(str_replace("%", " ", $srchFor));
                            ?>" onkeyup="enterKeyFuncAllRiskProfileFctrs(event, '', '#allRiskProfileFctrsDetailInfo', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&subPgNo=<?php echo $subPgNo; ?>&subVwtyp=<?php echo "3.2"; ?>&sbmtdRiskProfileID=<?php echo $sbmtdRiskProfileID; ?>');">
                            <input id="allRiskProfileFctrsPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                            <label class="btn btn-primary btn-file input-group-addon" onclick="getAllRiskProfileFctrs('clear', '#allRiskProfileFctrsDetailInfo', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&subPgNo=<?php echo $subPgNo; ?>&subVwtyp=<?php echo "3.2"; ?>&sbmtdRiskProfileID=<?php echo $sbmtdRiskProfileID; ?>');">
                                <span class="glyphicon glyphicon-remove"></span>
                            </label>
                            <label class="btn btn-primary btn-file input-group-addon" onclick="getAllRiskProfileFctrs('', '#allRiskProfileFctrsDetailInfo', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&subPgNo=<?php echo $subPgNo; ?>&subVwtyp=<?php echo "3.2"; ?>&sbmtdRiskProfileID=<?php echo $sbmtdRiskProfileID; ?>');">
                                <span class="glyphicon glyphicon-search"></span>
                            </label> 
                        </div>
                    </div>
                    <div class="<?php echo $colClassType2; ?>" style="padding:0px 3px 0px 3px !important;">
                        <div class="input-group">
                            <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                            <!--<select data-placeholder="Select..." class="form-control chosen-select" id="allRiskProfileFctrsSrchIn">
                            <?php
                            $valslctdArry = array("");
                            $srchInsArrys = array("Name");

                            for ($z = 0; $z < count($srchInsArrys); $z++) {
                                if ($srchIn == $srchInsArrys[$z]) {
                                    $valslctdArry[$z] = "selected";
                                }
                                ?>
                                                                                        <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                            <?php } ?>
                            </select>-->
                            <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                            <select data-placeholder="Select..." class="form-control chosen-select" id="allRiskProfileFctrsDsplySze" style="min-width:70px !important;">                            
                                <?php
                                $valslctdArry = array("", "", "", "", "", "", "", "");
                                $dsplySzeArry = array(1, 5, 10, 15, 30, 50, 100, 500, 1000);
                                for ($y = 0; $y < count($dsplySzeArry); $y++) {
                                    if ($lmtSze == $dsplySzeArry[$y]) {
                                        $valslctdArry[$y] = "selected";
                                    } else {
                                        $valslctdArry[$y] = "";
                                    }
                                    ?>
                                    <option value="<?php echo $dsplySzeArry[$y]; ?>" <?php echo $valslctdArry[$y]; ?>><?php echo $dsplySzeArry[$y]; ?></option>                            
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="<?php echo $colClassType2; ?>" style="padding:0px 3px 0px 3px !important;">
                        <nav aria-label="Page navigation">
                            <ul class="pagination" style="margin: 0px !important;">
                                <li>
                                    <a class="rhopagination" href="javascript:getAllRiskProfileFctrs('previous', '#allRiskProfileFctrsDetailInfo', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&subPgNo=<?php echo $subPgNo; ?>&subVwtyp=<?php echo "3.2"; ?>&sbmtdRiskProfileID=<?php echo $sbmtdRiskProfileID; ?>');" aria-label="Previous">
                                        <span aria-hidden="true">&laquo;</span>
                                    </a>
                                </li>
                                <li>
                                    <a class="rhopagination" href="javascript:getAllRiskProfileFctrs('next', '#allRiskProfileFctrsDetailInfo', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&subPgNo=<?php echo $subPgNo; ?>&subVwtyp=<?php echo "3.2"; ?>&sbmtdRiskProfileID=<?php echo $sbmtdRiskProfileID; ?>');" aria-label="Next">
                                        <span aria-hidden="true">&raquo;</span>
                                    </a>
                                </li>
                            </ul>
                        </nav>
			<input type="hidden" class="form-control" aria-label="..." id="gnrlOrgID" name="gnrlOrgID" value="<?php echo $orgID; ?>">                         
                        <input type="hidden" class="form-control" aria-label="..." id="sbmtdRiskProfileID" name="sbmtdRiskProfileID" value="<?php echo $sbmtdRiskProfileID; ?>">
                    </div>
                </div>
                <div class="row" style="padding:0px 15px 0px 15px !important">                  
                    <div class="col-md-12" style="padding:0px 3px 0px 3px !important">
                        <table class="table table-striped table-bordered table-responsive" id="allRiskProfileFctrsTable" cellspacing="0" width="100%" style="width:100%;min-width: 300px !important;">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Risk Factor</th>
                                    <th>Score</th>
                                    <th>Sort Order</th>
                                    <th style="display:none;">Ttl Option Score</th>
                                    <th>&nbps;</th>
                                    <th>&nbsp;</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $cntr = 0;
                                while ($row2 = loc_db_fetch_array($result2)) {
                                    $cntr += 1;
                                    $ttlOptnsScore = getRiskProfileFctrOptnsMaxScore($row2[0]);
                                    ?>
                                    <tr id="allRiskProfileFctrsRow_<?php echo $cntr; ?>">                                    
                                        <td class="lovtd"><span><?php echo ($curIdx * $lmtSze) + ($cntr); ?></span></td>
                                        <td class="lovtd">
                                            <input type="hidden" class="form-control" aria-label="..." id="allRiskProfileFctrsRow<?php echo $cntr; ?>_RiskProfileFactorID" value="<?php echo $row2[0]; ?>" style="width:100% !important;"> 
                                            <input type="hidden" class="form-control" aria-label="..." id="allRiskProfileFctrsRow<?php echo $cntr; ?>_RiskProfileID" value="<?php echo $row2[1]; ?>" style="width:100% !important;">                                                                         
                                            <input type="hidden" class="form-control" aria-label="..." id="allRiskProfileFctrsRow<?php echo $cntr; ?>_RiskFactorID" value="<?php echo $row2[2]; ?>" readonly="true">
                                            <div class="input-group">
                                                <input type="text" class="form-control" aria-label="..." id="allRiskProfileFctrsRow<?php echo $cntr; ?>_RiskFactorNm" name="allRiskProfileFctrsRow<?php echo $cntr; ?>__RiskFactorNm" value="<?php echo $row2[3]; ?>" readonly="true">
                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'MCF Credit Risk Factors', 'gnrlOrgID', '', '', 'radio', true, '', 'allRiskProfileFctrsRow<?php echo $cntr; ?>__RiskFactorID', 'allRiskProfileFctrsRow<?php echo $cntr; ?>__RiskFactorNm', 'clear', 1, '');">
                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                </label>
                                            </div>                                                                        
                                        </td>                                             
                                        <td class="lovtd"> 
                                            <input type="number" min="0" class="form-control rqrdFld" aria-label="..." id="allRiskProfileFctrsRow<?php echo $cntr; ?>_Score" name="allRiskProfileFctrsRow<?php echo $cntr; ?>_Score" value="<?php echo $row2[4]; ?>">
                                        </td>                                            
                                        <td class="lovtd">  
                                            <input type="number" min="0" class="form-control rqrdFld" aria-label="..." id="allRiskProfileFctrsRow<?php echo $cntr; ?>_SortOrder" name="allRiskProfileFctrsRow<?php echo $cntr; ?>_SortOrder" value="<?php echo $row2[5]; ?>">                                                               
                                        </td>
                                        <td class="lovtd" style="display:none;">
                                                <span id="allRiskProfileFctrsRow<?php echo $cntr; ?>_TtlOptnsScore"><?php echo $ttlOptnsScore; ?></span>                                                              
                                            </td>
                                        <td class="lovtd">
                                            <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="getRiskProfileFctrOptnsForm('allRiskProfileFctrsRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Add Profile Options">
                                                <img src="cmn_images/add1-64.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                            </button>
                                        </td>
                                        <td class="lovtd">
                                            <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delRiskProfileFctr('allRiskProfileFctrsRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Risk Profile">
                                                <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                            </button>
                                        </td>
                                    </tr>
                                    <?php
                                }
                                ?>
                            </tbody>
                        </table>                        
                    </div>                
                </div>
                <?php
            } else {
                ?>
                <span>No Results Found</span>
                <?php
            }
        }
        else if ($subVwtyp == 3.3) { 
            //var_dump($_POST);
            $sbmtdRiskProfileFctrID = isset($_POST['sbmtdRiskProfileFctrID']) ? cleanInputData($_POST['sbmtdRiskProfileFctrID']) : -1;			
            if ($sbmtdRiskProfileFctrID > 0) {
                $total = get_AllRiskProfileFctrOptnsTtl($srchFor, $srchIn, $sbmtdRiskProfileFctrID);
                //$total = get_AllBanksTtl($srchFor, $srchIn, $sbmtdRiskProfileFctrID);
                if ($pageNo > ceil($total / $lmtSze)) {
                    $pageNo = 1;
                } else if ($pageNo < 1) {
                    $pageNo = ceil($total / $lmtSze);
                }
                $curIdx = $pageNo - 1;
                $result2 = get_AllRiskProfileFctrOptns($srchFor, $srchIn, $curIdx, $lmtSze, $sbmtdRiskProfileFctrID);
                ?>
                <div class="row" style="padding:0px 15px 0px 15px !important">
				    <form id='allRiskProfileFctrOptnsForm' action='' method='post' accept-charset='UTF-8'>
                    <legend class="basic_person_lg1" style="color: #003245">RISK FACTOR OPTIONS</legend>
                    <?php
                    if ($canEdtRiskProfile === true) {
                        $colClassType1 = "col-lg-2";
                        $colClassType2 = "col-lg-3";
                        $colClassType3 = "col-lg-4";
                        $nwRowHtml = urlencode("<tr id=\"allRiskProfileFctrOptnsRow__WWW123WWW\">"
                                        . "<td class=\"lovtd\"><span class=\"normaltd\">New</span></td>"
                                        . "<td class=\"lovtd\">
                                                <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"allRiskProfileFctrOptnsRow_WWW123WWW_RiskProfileFactorOptnID\" value=\"-1\" style=\"width:100% !important;\">										
                                                    <input type=\"text\" class=\"form-control rqrdFld\" aria-label=\"...\" id=\"allRiskProfileFctrOptnsRow_WWW123WWW_OptnDesc\" name=\"allRiskProfileFctrOptnsRow_WWW123WWW_OptnDesc\" value=\"\">                                                               
						<input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"allRiskProfileFctrOptnsRow_WWW123WWW_RiskProfileFctrID\" value=\"-1\" style=\"width:100% !important;\">  
                                            </td>
											<td class=\"lovtd\">
                                                    <input type=\"number\" min=\"0\" class=\"form-control rqrdFld\" aria-label=\"...\" id=\"allRiskProfileFctrOptnsRow_WWW123WWW_Score\" name=\"allRiskProfileFctrOptnsRow_WWW123WWW_Score\" value=\"\">                                                               
                                            </td>
                                            <td class=\"lovtd\">
                                                    <input type=\"number\" min=\"0\" class=\"form-control rqrdFld\" aria-label=\"...\" id=\"allRiskProfileFctrOptnsRow_WWW123WWW_SortOrder\" name=\"allRiskProfileFctrOptnsRow_WWW123WWW_SortOrder\" value=\"\">                                                               
                                            </td>
                                            <td class=\"lovtd\">
                                                <button type=\"button\" class=\"btn btn-default\" style=\"margin: 0px !important;padding:0px 3px 2px 4px !important;\" onclick=\"delRiskProfileFctrOptn('allRiskProfileFctrOptnsRow__WWW123WWW');\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Delete Profile Factor Option\">
                                                        <img src=\"cmn_images/no.png\" style=\"height:15px; width:auto; position: relative; vertical-align: middle;\">
                                                </button>
                                            </td>
                        </tr>");                                                
                        ?> 
                        <div class="<?php echo $colClassType1; ?>" style="padding:0px 1px 0px 3px !important;">     
                            <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="insertNewRowBe4('allRiskProfileFctrOptnsTable', 0, '<?php echo $nwRowHtml; ?>');" data-toggle="tooltip" data-placement="bottom" title="Add Risk Factor Option">
                                <img src="cmn_images/add1-64.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                            </button>
                            <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="saveRiskProfileFctrOptn();" data-toggle="tooltip" data-placement="bottom" title="Save Risk Factor Option">
                                <img src="cmn_images/FloppyDisk.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                            </button>
                        </div>
                        <?php
                    } else {
                        $colClassType1 = "col-lg-4";
                        $colClassType2 = "col-lg-4";
                        $colClassType3 = "col-lg-4";
                    }
                    ?>
                    <div class="<?php echo $colClassType3; ?>" style="padding:0px 3px 0px 3px !important;">
                        <div class="input-group">
                            <input class="form-control" id="allRiskProfileFctrOptnsSrchFor" type = "text" placeholder="Search For" value="<?php
                            echo trim(str_replace("%", " ", $srchFor));
                            ?>" onkeyup="enterKeyFuncAllRiskProfileFctrOptns(event, '', '#allRiskProfileFctrOptnsDetailInfo', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&subPgNo=<?php echo $subPgNo; ?>&subVwtyp=<?php echo "3.3"; ?>&sbmtdRiskProfileFctrID=<?php echo $sbmtdRiskProfileFctrID; ?>');">
                            <input id="allRiskProfileFctrOptnsPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                            <label class="btn btn-primary btn-file input-group-addon" onclick="getAllRiskProfileFctrOptns('clear', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&subPgNo=<?php echo $subPgNo; ?>&subVwtyp=<?php echo "3.3"; ?>&sbmtdRiskProfileFctrID=<?php echo $sbmtdRiskProfileFctrID; ?>');">
                                <span class="glyphicon glyphicon-remove"></span>
                            </label>
                            <label class="btn btn-primary btn-file input-group-addon" onclick="getAllRiskProfileFctrOptns('', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&subPgNo=<?php echo $subPgNo; ?>&subVwtyp=<?php echo "3.3"; ?>&sbmtdRiskProfileFctrID=<?php echo $sbmtdRiskProfileFctrID; ?>');">
                                <span class="glyphicon glyphicon-search"></span>
                            </label> 
                        </div>
                    </div>
                    <div class="<?php echo $colClassType2; ?>" style="padding:0px 3px 0px 3px !important;">
                        <div class="input-group">
                            <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                            <!--<select data-placeholder="Select..." class="form-control chosen-select" id="allRiskProfileFctrOptnsSrchIn">
                            <?php
                            $valslctdArry = array("");
                            $srchInsArrys = array("Name");

                            for ($z = 0; $z < count($srchInsArrys); $z++) {
                                if ($srchIn == $srchInsArrys[$z]) {
                                    $valslctdArry[$z] = "selected";
                                }
                                ?>
                                                                                        <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                            <?php } ?>
                            </select>-->
                            <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                            <select data-placeholder="Select..." class="form-control chosen-select" id="allRiskProfileFctrOptnsDsplySze" style="min-width:70px !important;">                            
                                <?php
                                $valslctdArry = array("", "", "", "", "", "", "", "");
                                $dsplySzeArry = array(1, 5, 10, 15, 30, 50, 100, 500, 1000);
                                for ($y = 0; $y < count($dsplySzeArry); $y++) {
                                    if ($lmtSze == $dsplySzeArry[$y]) {
                                        $valslctdArry[$y] = "selected";
                                    } else {
                                        $valslctdArry[$y] = "";
                                    }
                                    ?>
                                    <option value="<?php echo $dsplySzeArry[$y]; ?>" <?php echo $valslctdArry[$y]; ?>><?php echo $dsplySzeArry[$y]; ?></option>                            
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="<?php echo $colClassType2; ?>" style="padding:0px 3px 0px 3px !important;">
                        <nav aria-label="Page navigation">
                            <ul class="pagination" style="margin: 0px !important;">
                                <li>
                                    <a class="rhopagination" href="javascript:getAllRiskProfileFctrOptns('previous', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&subPgNo=<?php echo $subPgNo; ?>&subVwtyp=<?php echo "3.3"; ?>&sbmtdRiskProfileFctrID=<?php echo $sbmtdRiskProfileFctrID; ?>');" aria-label="Previous">
                                        <span aria-hidden="true">&laquo;</span>
                                    </a>
                                </li>
                                <li>
                                    <a class="rhopagination" href="javascript:getAllRiskProfileFctrOptns('next', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&subPgNo=<?php echo $subPgNo; ?>&subVwtyp=<?php echo "3.3"; ?>&sbmtdRiskProfileFctrID=<?php echo $sbmtdRiskProfileFctrID; ?>');" aria-label="Next">
                                        <span aria-hidden="true">&raquo;</span>
                                    </a>
                                </li>
                            </ul>
                        </nav>
			<input type="hidden" class="form-control" aria-label="..." id="gnrlOrgID" name="gnrlOrgID" value="<?php echo $orgID; ?>">                         
                        <input type="hidden" class="form-control" aria-label="..." id="sbmtdRiskProfileFctrID" name="sbmtdRiskProfileFctrID" value="<?php echo $sbmtdRiskProfileFctrID; ?>">
                    </div>
                </div>
                <div class="row" style="padding:0px 15px 0px 15px !important">                  
                    <div class="col-md-12" style="padding:0px 3px 0px 3px !important">
                        <table class="table table-striped table-bordered table-responsive" id="allRiskProfileFctrOptnsTable" cellspacing="0" width="100%" style="width:100%;min-width: 300px !important;">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Option</th>
                                    <th>Score</th>
                                    <th>Sort Order</th>
                                    <th>&nbsp;</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $cntr = 0;
                                while ($row2 = loc_db_fetch_array($result2)) {
                                    $cntr += 1;
                                    ?>
                                    <tr id="allRiskProfileFctrOptnsRow_<?php echo $cntr; ?>">                                    
                                        <td class="lovtd"><span><?php echo ($curIdx * $lmtSze) + ($cntr); ?></span></td>
                                        <td class="lovtd">
                                            <input type="hidden" class="form-control" aria-label="..." id="allRiskProfileFctrOptnsRow<?php echo $cntr; ?>_RiskProfileFactorOptnID" value="<?php echo $row2[0]; ?>" style="width:100% !important;"> 
                                            <input type="hidden" class="form-control" aria-label="..." id="allRiskProfileFctrOptnsRow<?php echo $cntr; ?>_RiskProfileFctrID" value="<?php echo $row2[1]; ?>" style="width:100% !important;">                                                                         
                                            <input type="text" class="form-control rqrdFld" aria-label="..." id="allRiskProfileFctrOptnsRow<?php echo $cntr; ?>_OptnDesc" name="allRiskProfileFctrOptnsRow<?php echo $cntr; ?>_OptnDesc" value="<?php echo $row2[2]; ?>">                                                                       
                                        </td>                                             
                                        <td class="lovtd"> 
                                            <input type="number" min="0" class="form-control rqrdFld" aria-label="..." id="allRiskProfileFctrOptnsRow<?php echo $cntr; ?>_Score" name="allRiskProfileFctrOptnsRow<?php echo $cntr; ?>_Score" value="<?php echo $row2[3]; ?>">
                                        </td>                                            
                                        <td class="lovtd">  
                                            <input type="number" min="0" class="form-control rqrdFld" aria-label="..." id="allRiskProfileFctrOptnsRow<?php echo $cntr; ?>_SortOrder" name="allRiskProfileFctrOptnsRow<?php echo $cntr; ?>_SortOrder" value="<?php echo $row2[4]; ?>">                                                               
                                        </td>
                                        <td class="lovtd">
                                            <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delRiskProfileFctrOptn('allRiskProfileFctrOptnsRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Risk Factor Option">
                                                <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                            </button>
                                        </td>
                                    </tr>
                                    <?php
                                }
                                ?>
                            </tbody>
                        </table>                        
                    </div>                
                </div>
				</form>
                <?php
            } else {
                ?>
                <span>No Results Found</span>
                <?php
            }
        }        
        }
        else if ($subVwtyp == 4 || $subVwtyp == 4.1 || $subVwtyp == 4.2){
            //var_dump($_POST);
            $canAddAssessmentSet = test_prmssns($dfltPrvldgs[271], $mdlNm);
            $canEdtAssessmentSet = test_prmssns($dfltPrvldgs[272], $mdlNm);
            $canDelAssessmentSet = test_prmssns($dfltPrvldgs[273], $mdlNm);
            
            $error = "";
            $searchAll = true;
            $isEnabledOnly = false;
            if (isset($_POST['isEnabled'])) {
                $isEnabledOnly = cleanInputData($_POST['isEnabled']);
            }
            

            $srchFor = isset($_POST['searchfor']) ? cleanInputData($_POST['searchfor']) : '';
            $srchIn = isset($_POST['searchin']) ? cleanInputData($_POST['searchin']) : 'Both';
            $pageNo = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
            $lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 10;
            $sortBy = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : "Date Added DESC";
            if (strpos($srchFor, "%") === FALSE) {
                $srchFor = "%" . str_replace(" ", "%", $srchFor) . "%";
                $srchFor = str_replace("%%", "%", $srchFor);
            }

        if ($subVwtyp == 4) {
            echo  $cntent .= " <li onclick=\"openATab('#allmodules', 'grp=17&typ=1&pg=4');\">
                                                <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
						<span style=\"text-decoration:none;\">Credit Management Menu</span>
                                        </li>
					<li onclick=\"openATab('#allmodules', 'grp=17&typ=1&pg=4&subPgNo=4.6');\">
                                                <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
						<span style=\"text-decoration:none;\">Credit Risk Assessment</span>
                                        </li>
                        <li onclick=\"openATab('#allmodules', 'grp=17&typ=1&pg=4&subPgNo=4.6&subVwtyp=4');\">
                                                                <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                                                <span style=\"text-decoration:none;\">Assessment Sets</span>
                            </li></div>";            
            
            $total = getCreditScornsetHdrTblTtl($isEnabledOnly, $srchFor, $srchIn, $orgID, $searchAll);
            if ($pageNo > ceil($total / $lmtSze)) {
                $pageNo = 1;
            } else if ($pageNo < 1) {
                $pageNo = ceil($total / $lmtSze);
            }

            $curIdx = $pageNo - 1;
            $result = getCreditScornsetHdrTbl($isEnabledOnly, $srchFor, $srchIn, $curIdx, $lmtSze, $orgID, $searchAll, $sortBy);
            $cntr = 0;
            $colClassType1 = "col-lg-2";
            $colClassType2 = "col-lg-3";
            $colClassType3 = "col-lg-1";
            ?>
            <form id='allAssessmentSetsForm' action='' method='post' accept-charset='UTF-8'>
                <!--<fieldset class="basic_person_fs5">-->
                    <legend class="basic_person_lg1" style="color: #003245">ASSESSMENT SETS</legend>                
                    <input class="form-control" id="tblRowID" type = "hidden" placeholder="ROW ID"/>                     
                    <div class="row rhoRowMargin" style="margin-bottom:10px;">
                        <?php
                        if ($canAddAssessmentSet === true) {
                            ?>   
                            <div class="<?php echo $colClassType1; ?>" style="padding:0px 1px 0px 15px !important;">                    
                                <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="getOneAssessmentSetForm(-1, <?php echo $vwtyp; ?>);">
                                    <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                    New Assessment Set
                                </button>
                            </div>
                            <?php
                        } else {
                            $colClassType1 = "col-lg-2";
                            $colClassType2 = "col-lg-3";
                            $colClassType3 = "col-lg-1";
                        }
                        ?>
                        <div class="<?php echo $colClassType2; ?>" style="padding:0px 15px 0px 15px !important;">
                            <div class="input-group">
                                <input class="form-control" id="allAssessmentSetsSrchFor" type = "text" placeholder="Search For" value="<?php echo trim(str_replace("%", " ", $srchFor)); ?>" onkeyup="enterKeyFuncCust(event, '', '#allmodules', 'grp=17&typ=1&pg=4&subPgNo=4.1')">
                                <input id="allAssessmentSetsPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                <label class="btn btn-primary btn-file input-group-addon" onclick="getAllAssessmentSets('clear', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&subPgNo=<?php echo $subPgNo; ?>&subVwtyp=<?php echo $subVwtyp; ?>');">
                                    <span class="glyphicon glyphicon-remove"></span>
                                </label>
                                <label class="btn btn-primary btn-file input-group-addon" onclick="getAllAssessmentSets('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&subPgNo=<?php echo $subPgNo; ?>&subVwtyp=<?php echo $subVwtyp; ?>');">
                                    <span class="glyphicon glyphicon-search"></span>
                                </label> 
                            </div>
                        </div>
                        <div class="<?php echo $colClassType2; ?>">
                            <div class="input-group">
                                <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                <select data-placeholder="Select..." class="form-control chosen-select" id="allAssessmentSetsSrchIn">
                                    <?php
                                    $valslctdArry = array("", "");
                                    $srchInsArrys = array("Set Name","Description");
                                    for ($z = 0; $z < count($srchInsArrys); $z++) {
                                        if ($srchIn == $srchInsArrys[$z]) {
                                            $valslctdArry[$z] = "selected";
                                        }
                                        ?>
                                        <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                    <?php } ?>
                                </select>
                                <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                <select data-placeholder="Select..." class="form-control chosen-select" id="allAssessmentSetsDsplySze" style="min-width:70px !important;">                            
                                    <?php
                                    $valslctdArry = array("", "", "", "", "", "", "", "");
                                    $dsplySzeArry = array(1, 5, 10, 15, 30, 50, 100, 500, 1000);
                                    for ($y = 0; $y < count($dsplySzeArry); $y++) {
                                        if ($lmtSze == $dsplySzeArry[$y]) {
                                            $valslctdArry[$y] = "selected";
                                        } else {
                                            $valslctdArry[$y] = "";
                                        }
                                        ?>
                                        <option value="<?php echo $dsplySzeArry[$y]; ?>" <?php echo $valslctdArry[$y]; ?>><?php echo $dsplySzeArry[$y]; ?></option>                            
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>                        
                        <div class="<?php echo $colClassType3; ?>" style="padding: 5px 1px 0px 15px !important">
                            <div class="form-check" style="font-size: 12px !important;">
                                <label class="form-check-label">
                                    <?php
                                    $nonAprvdChekd = "";
                                    if ($isEnabledOnly == "true") {
                                        $nonAprvdChekd = "checked=\"true\"";
                                    }
                                    ?>
                                    <input type="checkbox" class="form-check-input" onclick="getAllAssessmentSets('clear', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&subPgNo=<?php echo $subPgNo; ?>&subVwtyp=<?php echo $subVwtyp; ?>');" id="allAssessmentSetsIsEnabled" name="allAssessmentSetsIsEnabled" <?php echo $nonAprvdChekd; ?>>
                                    Enabled?
                                </label>
                            </div>                             
                        </div>
                        <div class="<?php echo $colClassType3; ?>" style="width:10% !important;">
                            <nav aria-label="Page navigation">
                                <ul class="pagination" style="margin: 0px !important;">
                                    <li>
                                        <a href="javascript:getAllAssessmentSets('previous', '#allmodules', 'grp=17&typ=1&pg=4&subPgNo=4.6&subVwtyp=4');" aria-label="Previous">
                                            <span aria-hidden="true">&laquo;</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:getAllAssessmentSets('next', '#allmodules', 'grp=17&typ=1&pg=4&subPgNo=4.6&subVwtyp=4');" aria-label="Next">
                                            <span aria-hidden="true">&raquo;</span>
                                        </a>
                                    </li>
                                </ul>
                            </nav>
                        </div>  
                        <div class="<?php echo $colClassType3; ?>" style="padding:0px 1px 0px 3px !important;"> 
                            <div style="float:right !important;">
                                <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="validateAssessmentSetPrfl();" data-toggle="tooltip" data-placement="bottom" title="Validate Risk Profile">
                                    <img src="cmn_images/valid_1.jpg" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                </button>
                                <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="saveAssessmentSet(<?php echo $pgNo; ?>, <?php echo $subPgNo; ?>, <?php echo $subVwtyp; ?>, <?php echo $vwtyp;?>);" data-toggle="tooltip" data-placement="bottom" title="Save Risk Profile">
                                    <img src="cmn_images/FloppyDisk.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                </button>
                            </div>
                        </div>
                    </div>               
                    <div class="row" style="padding:0px 15px 0px 15px !important"> 
                        <div class="col-md-4" style="padding:0px 1px 0px 1px !important">
                            <fieldset class="basic_person_fs">                                        
                                <table class="table table-striped table-bordered table-responsive" id="allAssessmentSetsTable" cellspacing="0" width="100%" style="width:100%;">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Assessment Set</th>
                                            <th>Description</th>                                   
                                            <?php if ($canDelAssessmentSet === true) { ?>
                                                <th>&nbsp;</th>
                                            <?php } ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $sbmtdAssessmentSetID = -1;
                                        while ($row = loc_db_fetch_array($result)) {
                                            if ($sbmtdAssessmentSetID <= 0 && $cntr <= 0) {
                                                $sbmtdAssessmentSetID = $row[0];
                                            }
                                            $cntr += 1;
                                            ?>
                                            <tr id="allAssessmentSetsRow_<?php echo $cntr; ?>" class="hand_cursor">                                    
                                                <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                                <td class="lovtd"><?php echo $row[2]; ?>
                                                    <input type="hidden" class="form-control" aria-label="..." id="allAssessmentSetsRow<?php echo $cntr; ?>_AssessmentSetID" value="<?php echo $row[0]; ?>">
                                                </td>
                                                <td class="lovtd">
                                                    <?php echo $row[1]; ?>
                                                </td>
                                                <?php if ($canDelAssessmentSet === true) { ?>
                                                    <td class="lovtd">
                                                        <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="deleteAssessmentSet(<?php echo $sbmtdAssessmentSetID; ?>)" data-toggle="tooltip" data-placement="bottom" title="Delete Risk Profile">
                                                            <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                        </button>
                                                    </td>
                                                <?php } ?>
                                            </tr>
                                            <?php
                                        }
                                        ?>
                                    </tbody>
                                </table>                        
                            </fieldset>
                        </div>                        
                        <div  class="col-md-8" style="padding:0px 1px 0px 1px !important">
                            <fieldset class="basic_person_fs" style="padding-top:5px !important;">
                                <div class="" id="allAssessmentSetsDetailInfo">
                                    <div class="row" id="allAssessmentSetsHdrInfo" style="padding:0px 15px 0px 15px !important">
                                        <?php
                                        $oneAssessmentSetDetID = -1;
                                        $oneAssessmentSetDetName = "";
                                        $oneAssessmentSetDetDesc = "";
                                        $oneAssessmentSetDetTtlScore = 0;
                                        $oneAssessmentSetDetIsEnbld = "Yes";
                                        $vldtyColor = "red";
                                        $vldtyStatus = "Invalid";
                                        $result1 = getCreditScornsetHdrDets($sbmtdAssessmentSetID);
                                        while ($row1 = loc_db_fetch_array($result1)) {
                                            $oneAssessmentSetDetID = $row1[0];
                                            $oneAssessmentSetDetName = $row1[1];
                                            $oneAssessmentSetDetDesc = $row1[2];
                                            $oneAssessmentSetDetTtlScore = $row1[3];
                                            $oneAssessmentSetDetIsEnbld = $row1[4];
                                            $vldtyStatus = $row1[6];
                                            if($vldtyStatus == "Valid"){
                                                $vldtyColor = "green";
                                            }
                                        }                                         
                                        ?>
                                            <fieldset class="basic_person_fs" style="padding:10px 3px 0px 3px !important;border:none !important;border-bottom:1px solid #ddd !important;border-radius: 0px !important;">                                              
                                                <div  class="col-md-6" style="padding:0px 3px 0px 3px !important;">
                                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                        <label for="oneAssessmentSetDetName" class="control-label col-lg-4" style="padding:0px 15px 0px 1px !important;">Assessment Set:</label>
                                                        <div  class="col-lg-8" style="padding:0px 15px 0px 1px !important;">
                                                            <?php if ($canEdtAssessmentSet === true) { ?>
                                                                <input type="text" class="form-control rqrdFld" aria-label="..." id="oneAssessmentSetDetName" name="oneAssessmentSetDetName" value="<?php echo $oneAssessmentSetDetName; ?>" style="width:100%;">
                                                                <input type="hidden" class="form-control" aria-label="..." id="oneAssessmentSetDetID" name="oneAssessmentSetDetID" value="<?php echo $oneAssessmentSetDetID; ?>">
                                                            <?php } else {
                                                                ?>
                                                                <span><?php echo $oneAssessmentSetDetName; ?></span>
                                                                <?php
                                                            }
                                                            ?>
                                                        </div>
                                                    </div>
                                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                        <label for="oneAssessmentSetDetDesc" class="control-label col-lg-4" style="padding:0px 15px 0px 1px !important;">Description:</label>
                                                        <div  class="col-lg-8" style="padding:0px 15px 0px 1px !important;">
                                                            <?php if ($canEdtAssessmentSet === true) { ?>
                                                                <textarea class="form-control" aria-label="..." id="oneAssessmentSetDetDesc" name="oneAssessmentSetDetDesc" style="width:100%;" cols="5" rows="3"><?php echo $oneAssessmentSetDetDesc; ?></textarea>
                                                            <?php } else {
                                                                ?>
                                                                <span><?php echo $oneAssessmentSetDetDesc; ?></span>
                                                                <?php
                                                            }
                                                            ?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div  class="col-md-6" style="padding:0px 3px 0px 3px !important;">                                                        
                                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                        <label for="oneAssessmentSetDetTtlScore" class="control-label col-lg-4" style="padding:0px 15px 0px 1px !important;">Pass Score:</label>
                                                        <div class="col-lg-8" style="padding:0px 15px 0px 1px !important;">
                                                            <?php if ($canEdtAssessmentSet === true) { ?>
                                                                <input type="number" min="0" class="form-control rqrdFld" aria-label="..." id="oneAssessmentSetDetTtlScore" name="oneAssessmentSetDetTtlScore" value="<?php echo $oneAssessmentSetDetTtlScore; ?>" style="width:100%;">
                                                            <?php } else {
                                                                ?>
                                                                <span><?php echo $oneAssessmentSetDetTtlScore; ?></span>
                                                                <?php
                                                            }
                                                            ?>
                                                        </div>
                                                    </div>
                                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                        <label for="oneAssessmentSetDetIsEnbld" class="control-label col-lg-4" style="padding:0px 15px 0px 1px !important;">Is Enabled:</label>
                                                        <div  class="col-lg-8" style="padding:0px 15px 0px 1px !important;">
                                                            <?php if ($canEdtAssessmentSet === true) { ?>
                                                                <select class="form-control" id="oneAssessmentSetDetIsEnbld" >
                                                                    <?php
                                                                    $sltdYes = "";
                                                                    $sltdNo = "";
                                                                    if ($oneAssessmentSetDetIsEnbld == "Yes") {
                                                                        $sltdYes = "selected";
                                                                    } else if ($oneAssessmentSetDetIsEnbld == "No") {
                                                                        $sltdNo = "selected";
                                                                    }
                                                                    ?>
                                                                    <option value="Yes" <?php echo $sltdYes; ?>>Yes</option>
                                                                    <option value="No" <?php echo $sltdNo; ?>>No</option>
                                                                </select>
                                                            <?php } else {
                                                                ?>
                                                                <span><?php echo $oneAssessmentSetDetIsEnbld; ?></span>
                                                                <?php
                                                            }
                                                            ?>
                                                        </div>
                                                    </div>
                                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                        <label for="oneAssessmentSetDetValidity" class="control-label col-lg-4" style="padding:0px 15px 0px 1px !important;">Status:</label>
                                                        <div  class="col-lg-8" style="padding:0px 15px 0px 1px !important;">
                                                            <span id="oneAssessmentSetDetValidity" style="color:<?php echo $vldtyColor; ?>;font-weight: bold;"><?php echo $vldtyStatus; ?></span>                                                            
                                                        </div>
                                                    </div>                                                    
                                                </div>
                                            </fieldset>
                                    </div>
                                    <div class="row" id="allAssessmentSetPrflsDetailInfo" style="padding:0px 15px 0px 15px !important">
                                        <?php
                                        /* &vtyp=<?php echo $vwtyp; ?> */
                                        $srchFor = "%";
                                        $srchIn = "Risk Factor";
                                        $pageNo = 1;
                                        $lmtSze = 10;
                                        $vwtyp = 1;
                                        if ($sbmtdAssessmentSetID > 0) {
                                            $total = get_AllAssessmentSetPrflsTtl($srchFor, $srchIn, $sbmtdAssessmentSetID);
                                            //$total = get_AllBanksTtl($srchFor, $srchIn, $sbmtdAssessmentSetID);
                                            if ($pageNo > ceil($total / $lmtSze)) {
                                                $pageNo = 1;
                                            } else if ($pageNo < 1) {
                                                $pageNo = ceil($total / $lmtSze);
                                            }
                                            $curIdx = $pageNo - 1;
                                            $result2 = get_AllAssessmentSetPrfls($srchFor, $srchIn, $curIdx, $lmtSze, $sbmtdAssessmentSetID);
                                            ?>
                                            <div class="row" style="padding:0px 15px 0px 15px !important">
                                                <legend class="basic_person_lg1" style="color: #003245">ASSESSED RISK PROFILES</legend>
                                                <?php
                                                if ($canEdtAssessmentSet === true) {
                                                    $colClassType1 = "col-lg-2";
                                                    $colClassType2 = "col-lg-3";
                                                    $colClassType3 = "col-lg-4";
                                                    $nwRowHtml = urlencode("<tr id=\"allAssessmentSetPrflsRow__WWW123WWW\">"
                                                                    . "<td class=\"lovtd\"><span class=\"normaltd\">New</span></td>"
                                                                    . "<td class=\"lovtd\">
                                                                            <div class=\"input-group\">
                                                                                <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"allAssessmentSetPrflsRow_WWW123WWW_AssessmentPrflNm\" name=\"allAssessmentSetPrflsRow_WWW123WWW_AssessmentPrflNm\" value=\"\" readonly=\"true\">
                                                                                <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'MCF Credit Risk Profiles - Valid', 'gnrlOrgID', '', '', 'radio', true, '', 'allAssessmentSetPrflsRow_WWW123WWW_AssessmentPrflID', 'allAssessmentSetPrflsRow_WWW123WWW_AssessmentPrflNm', 'clear', 1, '');\">
                                                                                        <span class=\"glyphicon glyphicon-th-list\"></span>
                                                                                </label>
                                                                                <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"allAssessmentSetPrflsRow_WWW123WWW_AssessmentPrflID\" value=\"-1\" style=\"width:100% !important;\"> 
                                                                            </div>
                                                                            <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"allAssessmentSetPrflsRow_WWW123WWW_AssessmentSetPrflID\" value=\"-1\" style=\"width:100% !important;\">                                                                                                                          
                                                                        </td>                                             
                                                                        <td class=\"lovtd\">
                                                                                <input type=\"number\" min=\"0\" class=\"form-control\" aria-label=\"...\" id=\"allAssessmentSetPrflsRow_WWW123WWW_Score\" name=\"allAssessmentSetPrflsRow_WWW123WWW_Score\" value=\"\" readonly=\"readonly\">                                                               
                                                                        </td>
                                                                        <td class=\"lovtd\">
                                                                                <input type=\"number\" min=\"0\" class=\"form-control rqrdFld\" aria-label=\"...\" id=\"allAssessmentSetPrflsRow_WWW123WWW_SortOrder\" name=\"allAssessmentSetPrflsRow_WWW123WWW_SortOrder\" value=\"\">                                                               
                                                                        </td>
                                                                        <td class=\"lovtd\" style=\"display:none;\">
                                                                                <span id=\"allAssessmentSetPrflsRow_WWW123WWW_TtlOptnsScore\"></span>                                                              
                                                                        </td>
                                                                        <td class=\"lovtd\">
                                                                            <button type=\"button\" class=\"btn btn-default\" style=\"margin: 0px !important;padding:0px 3px 2px 4px !important;\" onclick=\"delAssessmentSetPrfl('allAssessmentSetPrflsRow__WWW123WWW');\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Delete Profile Factor\">
                                                                                    <img src=\"cmn_images/no.png\" style=\"height:15px; width:auto; position: relative; vertical-align: middle;\">
                                                                            </button>
                                                                        </td>
                                                    </tr>");
                                                    ?>
                                                    <div class="<?php echo $colClassType1; ?>" style="padding:0px 1px 0px 3px !important;">     
                                                        <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="insertNewRowBe4('allAssessmentSetPrflsTable', 0, '<?php echo $nwRowHtml; ?>');" data-toggle="tooltip" data-placement="bottom" title="Add Profile Factor">
                                                            <img src="cmn_images/add1-64.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                        </button>
                                                        <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="saveAssessmentSetPrfl();" data-toggle="tooltip" data-placement="bottom" title="Save Profile Factor">
                                                            <img src="cmn_images/FloppyDisk.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                        </button>
                                                    </div>
                                                    <?php
                                                } else {
                                                    $colClassType1 = "col-lg-4";
                                                    $colClassType2 = "col-lg-4";
                                                    $colClassType3 = "col-lg-4";
                                                }
                                                ?>
                                                <div class="<?php echo $colClassType3; ?>" style="padding:0px 3px 0px 3px !important;">
                                                    <div class="input-group">
                                                        <input class="form-control" id="allAssessmentSetPrflsSrchFor" type = "text" placeholder="Search For" value="<?php
                                                        echo trim(str_replace("%", " ", $srchFor));
                                                        ?>" onkeyup="enterKeyFuncAllAssessmentSetPrfls(event, '', '#allAssessmentSetPrflsDetailInfo', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&subPgNo=<?php echo $subPgNo; ?>&subVwtyp=<?php echo "3.2"; ?>&sbmtdAssessmentSetID=<?php echo $sbmtdAssessmentSetID; ?>');">
                                                        <input id="allAssessmentSetPrflsPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getAllAssessmentSetPrfls('clear', '#allAssessmentSetPrflsDetailInfo', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&subPgNo=<?php echo $subPgNo; ?>&subVwtyp=<?php echo "4.2"; ?>&sbmtdAssessmentSetID=<?php echo $sbmtdAssessmentSetID; ?>');">
                                                            <span class="glyphicon glyphicon-remove"></span>
                                                        </label>
                                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getAllAssessmentSetPrfls('', '#allAssessmentSetPrflsDetailInfo', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&subPgNo=<?php echo $subPgNo; ?>&subVwtyp=<?php echo "4.2"; ?>&sbmtdAssessmentSetID=<?php echo $sbmtdAssessmentSetID; ?>');">
                                                            <span class="glyphicon glyphicon-search"></span>
                                                        </label> 
                                                    </div>
                                                </div>
                                                <div class="<?php echo $colClassType2; ?>" style="padding:0px 3px 0px 3px !important;">
                                                    <div class="input-group">
                                                        <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                                        <!--<select data-placeholder="Select..." class="form-control chosen-select" id="allAssessmentSetPrflsSrchIn">
                                                        <?php
                                                        $valslctdArry = array("");
                                                        $srchInsArrys = array("Name");

                                                        for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                            if ($srchIn == $srchInsArrys[$z]) {
                                                                $valslctdArry[$z] = "selected";
                                                            }
                                                            ?>
                                                                                                                    <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                                        <?php } ?>
                                                        </select>-->
                                                        <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                                        <select data-placeholder="Select..." class="form-control chosen-select" id="allAssessmentSetPrflsDsplySze" style="min-width:70px !important;">                            
                                                            <?php
                                                            $valslctdArry = array("", "", "", "", "", "", "", "");
                                                            $dsplySzeArry = array(1, 5, 10, 15, 30, 50, 100, 500, 1000);
                                                            for ($y = 0; $y < count($dsplySzeArry); $y++) {
                                                                if ($lmtSze == $dsplySzeArry[$y]) {
                                                                    $valslctdArry[$y] = "selected";
                                                                } else {
                                                                    $valslctdArry[$y] = "";
                                                                }
                                                                ?>
                                                                <option value="<?php echo $dsplySzeArry[$y]; ?>" <?php echo $valslctdArry[$y]; ?>><?php echo $dsplySzeArry[$y]; ?></option>                            
                                                                <?php
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="<?php echo $colClassType2; ?>" style="padding:0px 3px 0px 3px !important;">
                                                    <nav aria-label="Page navigation">
                                                        <ul class="pagination" style="margin: 0px !important;">
                                                            <li>
                                                                <a class="rhopagination" href="javascript:getAllAssessmentSetPrfls('previous', '#allAssessmentSetPrflsDetailInfo', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&subPgNo=<?php echo $subPgNo; ?>&subVwtyp=<?php echo "4.2"; ?>&sbmtdAssessmentSetID=<?php echo $sbmtdAssessmentSetID; ?>');" aria-label="Previous">
                                                                    <span aria-hidden="true">&laquo;</span>
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a class="rhopagination" href="javascript:getAllAssessmentSetPrfls('next', '#allAssessmentSetPrflsDetailInfo', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&subPgNo=<?php echo $subPgNo; ?>&subVwtyp=<?php echo "4.2"; ?>&sbmtdAssessmentSetID=<?php echo $sbmtdAssessmentSetID; ?>');" aria-label="Next">
                                                                    <span aria-hidden="true">&raquo;</span>
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </nav>
                                                    <input type="hidden" class="form-control" aria-label="..." id="gnrlOrgID" name="gnrlOrgID" value="<?php echo $orgID; ?>">                                                    
                                                    <input type="hidden" class="form-control" aria-label="..." id="sbmtdAssessmentSetID" name="sbmtdAssessmentSetID" value="<?php echo $sbmtdAssessmentSetID; ?>">
                                                </div>
                                            </div>
                                            <div class="row" style="padding:0px 15px 0px 15px !important">                  
                                                <div class="col-md-12" style="padding:0px 3px 0px 3px !important">
                                                    <table class="table table-striped table-bordered table-responsive" id="allAssessmentSetPrflsTable" cellspacing="0" width="100%" style="width:100%;min-width: 300px !important;">
                                                        <thead>
                                                            <tr>
                                                                <th>No.</th>
                                                                <th>Risk Profile</th>
                                                                <th>Score</th>
                                                                <th>Sort Order</th>
                                                                <th style="display:none;">Ttl Option Score</th>
                                                                <th>&nbsp;</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $cntr = 0;
                                                            while ($row2 = loc_db_fetch_array($result2)) {                                                                
                                                                $cntr += 1;
                                                                $ttlOptnsScore = 0;
                                                                ?>
                                                                <tr id="allAssessmentSetPrflsRow_<?php echo $cntr; ?>">                                    
                                                                    <td class="lovtd"><span><?php echo ($curIdx * $lmtSze) + ($cntr); ?></span></td>
                                                                    <td class="lovtd">
                                                                        <input type="hidden" class="form-control" aria-label="..." id="allAssessmentSetPrflsRow<?php echo $cntr; ?>_AssessmentSetPrflID" value="<?php echo $row2[0]; ?>" style="width:100% !important;"> 
                                                                        <input type="hidden" class="form-control" aria-label="..." id="allAssessmentSetPrflsRow<?php echo $cntr; ?>_AssessmentSetID" value="<?php echo $row2[1]; ?>" style="width:100% !important;">                                                                         
                                                                        <input type="hidden" class="form-control" aria-label="..." id="allAssessmentSetPrflsRow<?php echo $cntr; ?>_AssessmentPrflID" value="<?php echo $row2[2]; ?>" readonly="true"> 
                                                                        <div class="input-group">
                                                                            <input type="text" class="form-control" aria-label="..." id="allAssessmentSetPrflsRow<?php echo $cntr; ?>_AssessmentPrflNm" name="allAssessmentSetPrflsRow<?php echo $cntr; ?>_AssessmentPrflNm" value="<?php echo $row2[3]; ?>" readonly="true">
                                                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'MCF Credit Risk Profiles - Valid', 'gnrlOrgID', '', '', 'radio', true, '', 'allAssessmentSetPrflsRow<?php echo $cntr; ?>_AssessmentPrflID', 'allAssessmentSetPrflsRow<?php echo $cntr; ?>_RiskFactorNm', 'clear', 1, '');">
                                                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                                            </label>
                                                                        </div>                                                                        
                                                                    </td>                                             
                                                                    <td class="lovtd"> 
                                                                        <input type="number" min="0" class="form-control" aria-label="..." id="allAssessmentSetPrflsRow<?php echo $cntr; ?>_Score" name="allAssessmentSetPrflsRow<?php echo $cntr; ?>_Score" value="<?php echo $row2[4]; ?>" readonly="readonly">
                                                                    </td>                                            
                                                                    <td class="lovtd">  
                                                                        <input type="number" min="0" class="form-control rqrdFld" aria-label="..." id="allAssessmentSetPrflsRow<?php echo $cntr; ?>_SortOrder" name="allAssessmentSetPrflsRow<?php echo $cntr; ?>_SortOrder" value="<?php echo $row2[5]; ?>">                                                               
                                                                    </td>
                                                                    <td class="lovtd" style="display:none;">
                                                                        <span id="allAssessmentSetPrflsRow<?php echo $cntr; ?>_TtlOptnsScore"><?php echo $ttlOptnsScore; ?></span>                                                              
                                                                    </td>
                                                                    <td class="lovtd">
                                                                        <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delAssessmentSetPrfl('allAssessmentSetPrflsRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Risk Profile">
                                                                            <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                                        </button>
                                                                    </td>
                                                                </tr>
                                                                <?php
                                                            }
                                                            ?>
                                                        </tbody>
                                                    </table>                        
                                                </div>                
                                            </div>
                                            <?php
                                        } else {
                                            ?>
                                            <span>No Results Found</span>
                                            <?php
                                        }
                                        ?> 
                                    </div>                                   
                                </div>
                            </fieldset>
                        </div>
                    </div>
                <!--</fieldset>-->
            </form>
            <?php
        } 
        else if ($subVwtyp == 4.1) {
            $sbmtdAssessmentSetID = isset($_POST['sbmtdAssessmentSetID']) ? cleanInputData($_POST['sbmtdAssessmentSetID']) : -1;                    
            ?>
            <div class="row" id="allAssessmentSetsHdrInfo" style="padding:0px 15px 0px 15px !important">
                <?php
                $oneAssessmentSetDetID = -1;
                $oneAssessmentSetDetName = "";
                $oneAssessmentSetDetDesc = "";
                $oneAssessmentSetDetTtlScore = 0;
                $oneAssessmentSetDetIsEnbld = "Yes";
                $vldtyColor = "red";
                $vldtyStatus = "Invalid";
                $result1 = getCreditScornsetHdrDets($sbmtdAssessmentSetID);
                while ($row1 = loc_db_fetch_array($result1)) {
                    $oneAssessmentSetDetID = $row1[0];
                    $oneAssessmentSetDetName = $row1[1];
                    $oneAssessmentSetDetDesc = $row1[2];
                    $oneAssessmentSetDetTtlScore = $row1[3];
                    $oneAssessmentSetDetIsEnbld = $row1[4];
                    $vldtyStatus = $row1[6];
                    if($vldtyStatus == "Valid"){
                        $vldtyColor = "green";
                    }
                } 
                ?>                            
                    <fieldset class="basic_person_fs" style="padding:10px 3px 0px 3px !important;border:none !important;border-bottom:1px solid #ddd !important;border-radius: 0px !important;">                           
                        <div  class="col-md-6" style="padding:0px 3px 0px 3px !important;">
                            <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                <label for="oneAssessmentSetDetName" class="control-label col-lg-4" style="padding:0px 15px 0px 1px !important;">Assessment Set:</label>
                                <div  class="col-lg-8" style="padding:0px 15px 0px 1px !important;">
                                    <?php if ($canEdtAssessmentSet === true) { ?>
                                        <input type="text" class="form-control rqrdFld" aria-label="..." id="oneAssessmentSetDetName" name="oneAssessmentSetDetName" value="<?php echo $oneAssessmentSetDetName; ?>" style="width:100%;">
                                        <input type="hidden" class="form-control" aria-label="..." id="oneAssessmentSetDetID" name="oneAssessmentSetDetID" value="<?php echo $oneAssessmentSetDetID; ?>">
                                    <?php } else {
                                        ?>
                                        <span><?php echo $oneAssessmentSetDetName; ?></span>
                                        <?php
                                    }
                                    ?>
                                </div>
                            </div>
                            <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                <label for="oneAssessmentSetDetDesc" class="control-label col-lg-4" style="padding:0px 15px 0px 1px !important;">Description:</label>
                                <div  class="col-lg-8" style="padding:0px 15px 0px 1px !important;">
                                    <?php if ($canEdtAssessmentSet === true) { ?>
                                        <textarea class="form-control" aria-label="..." id="oneAssessmentSetDetDesc" name="oneAssessmentSetDetDesc" style="width:100%;" cols="5" rows="3"><?php echo $oneAssessmentSetDetDesc; ?></textarea>
                                    <?php } else {
                                        ?>
                                        <span><?php echo $oneAssessmentSetDetDesc; ?></span>
                                        <?php
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div  class="col-md-6" style="padding:0px 3px 0px 3px !important;"> 
                            <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                <label for="oneAssessmentSetDetTtlScore" class="control-label col-lg-4" style="padding:0px 15px 0px 1px !important;">Pass Score:</label>
                                <div class="col-lg-8" style="padding:0px 15px 0px 1px !important;">
                                    <?php if ($canEdtAssessmentSet === true) { ?>
                                        <input type="number" min="0" class="form-control rqrdFld" aria-label="..." id="oneAssessmentSetDetTtlScore" name="oneAssessmentSetDetTtlScore" value="<?php echo $oneAssessmentSetDetTtlScore; ?>" style="width:100%;">
                                    <?php } else {
                                        ?>
                                        <span><?php echo $oneAssessmentSetDetTtlScore; ?></span>
                                        <?php
                                    }
                                    ?>
                                </div>
                            </div>
                            <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                <label for="oneAssessmentSetDetIsEnbld" class="control-label col-lg-4" style="padding:0px 15px 0px 1px !important;">Is Enabled:</label>
                                <div  class="col-lg-8" style="padding:0px 15px 0px 1px !important;">
                                    <?php if ($canEdtAssessmentSet === true) { ?>
                                        <select class="form-control" id="oneAssessmentSetDetIsEnbld" >
                                            <?php
                                            $sltdYes = "";
                                            $sltdNo = "";
                                            if ($oneAssessmentSetDetIsEnbld == "Yes") {
                                                $sltdYes = "selected";
                                            } else if ($oneAssessmentSetDetIsEnbld == "No") {
                                                $sltdNo = "selected";
                                            }
                                            ?>
                                            <option value="Yes" <?php echo $sltdYes; ?>>Yes</option>
                                            <option value="No" <?php echo $sltdNo; ?>>No</option>
                                        </select>                                   
                                    <?php } else {
                                        ?>
                                        <span><?php echo $row1[4]; ?></span>
                                        <?php
                                    }
                                    ?>
                                </div>
                            </div>
                            <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                <label for="oneAssessmentSetDetValidity" class="control-label col-lg-4" style="padding:0px 15px 0px 1px !important;">Status:</label>
                                <div  class="col-lg-8" style="padding:0px 15px 0px 1px !important;">
                                    <span id="oneAssessmentSetDetValidity" style="color:<?php echo $vldtyColor; ?>;font-weight: bold;"><?php echo $vldtyStatus; ?></span>                                                            
                                </div>
                            </div>
                        </div>
                    </fieldset>
            </div>
            <div class="row" id="allAssessmentSetPrflsDetailInfo" style="padding:0px 15px 0px 15px !important">
                <?php
                /* &vtyp=<?php echo $vwtyp; ?> */
                $srchFor = "%";
                $srchIn = "Risk Factor";
                $pageNo = 1;
                $lmtSze = 10;
                $vwtyp = 1;
                if ($sbmtdAssessmentSetID > 0) {
                    $total = get_AllAssessmentSetPrflsTtl($srchFor, $srchIn, $sbmtdAssessmentSetID);
                    //$total = get_AllBanksTtl($srchFor, $srchIn, $sbmtdAssessmentSetID);
                    if ($pageNo > ceil($total / $lmtSze)) {
                        $pageNo = 1;
                    } else if ($pageNo < 1) {
                        $pageNo = ceil($total / $lmtSze);
                    }
                    $curIdx = $pageNo - 1;
                    $result2 = get_AllAssessmentSetPrfls($srchFor, $srchIn, $curIdx, $lmtSze, $sbmtdAssessmentSetID);
                    ?>
                    <div class="row" style="padding:0px 15px 0px 15px !important">
                        <legend class="basic_person_lg1" style="color: #003245">ASSESSED RISK PROFILES</legend>
                        <?php
                        if ($canEdtAssessmentSet === true) {
                            $colClassType1 = "col-lg-2";
                            $colClassType2 = "col-lg-3";
                            $colClassType3 = "col-lg-4";
                            $nwRowHtml = urlencode("<tr id=\"allAssessmentSetPrflsRow__WWW123WWW\">"
                                            . "<td class=\"lovtd\"><span class=\"normaltd\">New</span></td>"
                                            . "<td class=\"lovtd\">
                                                    <div class=\"input-group\">
                                                        <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"allAssessmentSetPrflsRow_WWW123WWW_AssessmentPrflNm\" name=\"allAssessmentSetPrflsRow_WWW123WWW_AssessmentPrflNm\" value=\"\" readonly=\"true\">
                                                        <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'MCF Credit Risk Profiles - Valid', 'gnrlOrgID', '', '', 'radio', true, '', 'allAssessmentSetPrflsRow_WWW123WWW_AssessmentPrflID', 'allAssessmentSetPrflsRow_WWW123WWW_AssessmentPrflNm', 'clear', 1, '');\">
                                                                <span class=\"glyphicon glyphicon-th-list\"></span>
                                                        </label>
                                                        <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"allAssessmentSetPrflsRow_WWW123WWW_AssessmentPrflID\" value=\"-1\" style=\"width:100% !important;\"> 
                                                    </div>
                                                    <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"allAssessmentSetPrflsRow_WWW123WWW_AssessmentSetPrflID\" value=\"-1\" style=\"width:100% !important;\">                                                                                                                          
                                                </td>                                             
                                                <td class=\"lovtd\">
                                                        <input type=\"number\" min=\"0\" class=\"form-control\" aria-label=\"...\" id=\"allAssessmentSetPrflsRow_WWW123WWW_Score\" name=\"allAssessmentSetPrflsRow_WWW123WWW_Score\" value=\"\" readonly=\"readonly\">                                                               
                                                </td>
                                                <td class=\"lovtd\">
                                                        <input type=\"number\" min=\"0\" class=\"form-control rqrdFld\" aria-label=\"...\" id=\"allAssessmentSetPrflsRow_WWW123WWW_SortOrder\" name=\"allAssessmentSetPrflsRow_WWW123WWW_SortOrder\" value=\"\">                                                               
                                                </td>
                                                <td class=\"lovtd\" style=\"display:none;\">
                                                        <span id=\"allAssessmentSetPrflsRow_WWW123WWW_TtlOptnsScore\"></span>                                                              
                                                </td>                                               
                                                <td class=\"lovtd\">
                                                    <button type=\"button\" class=\"btn btn-default\" style=\"margin: 0px !important;padding:0px 3px 2px 4px !important;\" onclick=\"delAssessmentSetPrfl('allAssessmentSetPrflsRow__WWW123WWW');\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Delete Profile Factor\">
                                                            <img src=\"cmn_images/no.png\" style=\"height:15px; width:auto; position: relative; vertical-align: middle;\">
                                                    </button>
                                                </td>
                            </tr>");
                            ?>
                            <div class="<?php echo $colClassType1; ?>" style="padding:0px 1px 0px 3px !important;">     
                                <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="insertNewRowBe4('allAssessmentSetPrflsTable', 0, '<?php echo $nwRowHtml; ?>');" data-toggle="tooltip" data-placement="bottom" title="Add Profile Factor">
                                    <img src="cmn_images/add1-64.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                </button>
                                <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="saveAssessmentSetPrfl();" data-toggle="tooltip" data-placement="bottom" title="Save Profile Factor">
                                    <img src="cmn_images/FloppyDisk.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                </button>
                            </div>
                            <?php
                        } else {
                            $colClassType1 = "col-lg-4";
                            $colClassType2 = "col-lg-4";
                            $colClassType3 = "col-lg-4";
                        }
                        ?>
                        <div class="<?php echo $colClassType3; ?>" style="padding:0px 3px 0px 3px !important;">
                            <div class="input-group">
                                <input class="form-control" id="allAssessmentSetPrflsSrchFor" type = "text" placeholder="Search For" value="<?php
                                echo trim(str_replace("%", " ", $srchFor));
                                ?>" onkeyup="enterKeyFuncAllAssessmentSetPrfls(event, '', '#allAssessmentSetPrflsDetailInfo', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&subPgNo=<?php echo $subPgNo; ?>&subVwtyp=<?php echo "3.2"; ?>&sbmtdAssessmentSetID=<?php echo $sbmtdAssessmentSetID; ?>');">
                                <input id="allAssessmentSetPrflsPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                <label class="btn btn-primary btn-file input-group-addon" onclick="getAllAssessmentSetPrfls('clear', '#allAssessmentSetPrflsDetailInfo', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&subPgNo=<?php echo $subPgNo; ?>&subVwtyp=<?php echo "4.2"; ?>&sbmtdAssessmentSetID=<?php echo $sbmtdAssessmentSetID; ?>');">
                                    <span class="glyphicon glyphicon-remove"></span>
                                </label>
                                <label class="btn btn-primary btn-file input-group-addon" onclick="getAllAssessmentSetPrfls('', '#allAssessmentSetPrflsDetailInfo', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&subPgNo=<?php echo $subPgNo; ?>&subVwtyp=<?php echo "4.2"; ?>&sbmtdAssessmentSetID=<?php echo $sbmtdAssessmentSetID; ?>');">
                                    <span class="glyphicon glyphicon-search"></span>
                                </label> 
                            </div>
                        </div>
                        <div class="<?php echo $colClassType2; ?>" style="padding:0px 3px 0px 3px !important;">
                            <div class="input-group">
                                <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                <!--<select data-placeholder="Select..." class="form-control chosen-select" id="allAssessmentSetPrflsSrchIn">
                                <?php
                                $valslctdArry = array("");
                                $srchInsArrys = array("Name");

                                for ($z = 0; $z < count($srchInsArrys); $z++) {
                                    if ($srchIn == $srchInsArrys[$z]) {
                                        $valslctdArry[$z] = "selected";
                                    }
                                    ?>
                                                                                            <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                <?php } ?>
                                </select>-->
                                <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                <select data-placeholder="Select..." class="form-control chosen-select" id="allAssessmentSetPrflsDsplySze" style="min-width:70px !important;">                            
                                    <?php
                                    $valslctdArry = array("", "", "", "", "", "", "", "");
                                    $dsplySzeArry = array(1, 5, 10, 15, 30, 50, 100, 500, 1000);
                                    for ($y = 0; $y < count($dsplySzeArry); $y++) {
                                        if ($lmtSze == $dsplySzeArry[$y]) {
                                            $valslctdArry[$y] = "selected";
                                        } else {
                                            $valslctdArry[$y] = "";
                                        }
                                        ?>
                                        <option value="<?php echo $dsplySzeArry[$y]; ?>" <?php echo $valslctdArry[$y]; ?>><?php echo $dsplySzeArry[$y]; ?></option>                            
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="<?php echo $colClassType2; ?>" style="padding:0px 3px 0px 3px !important;">
                            <nav aria-label="Page navigation">
                                <ul class="pagination" style="margin: 0px !important;">
                                    <li>
                                        <a class="rhopagination" href="javascript:getAllAssessmentSetPrfls('previous', '#allAssessmentSetPrflsDetailInfo', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&subPgNo=<?php echo $subPgNo; ?>&subVwtyp=<?php echo "4.2"; ?>&sbmtdAssessmentSetID=<?php echo $sbmtdAssessmentSetID; ?>');" aria-label="Previous">
                                            <span aria-hidden="true">&laquo;</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="rhopagination" href="javascript:getAllAssessmentSetPrfls('next', '#allAssessmentSetPrflsDetailInfo', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&subPgNo=<?php echo $subPgNo; ?>&subVwtyp=<?php echo "4.2"; ?>&sbmtdAssessmentSetID=<?php echo $sbmtdAssessmentSetID; ?>');" aria-label="Next">
                                            <span aria-hidden="true">&raquo;</span>
                                        </a>
                                    </li>
                                </ul>
                            </nav>
                            <input type="hidden" class="form-control" aria-label="..." id="gnrlOrgID" name="gnrlOrgID" value="<?php echo $orgID; ?>">                                                    
                            <input type="hidden" class="form-control" aria-label="..." id="sbmtdAssessmentSetID" name="sbmtdAssessmentSetID" value="<?php echo $sbmtdAssessmentSetID; ?>">
                        </div>
                    </div>
                    <div class="row" style="padding:0px 15px 0px 15px !important">                  
                        <div class="col-md-12" style="padding:0px 3px 0px 3px !important">
                            <table class="table table-striped table-bordered table-responsive" id="allAssessmentSetPrflsTable" cellspacing="0" width="100%" style="width:100%;min-width: 300px !important;">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Risk Profile</th>
                                        <th>Score</th>
                                        <th>Sort Order</th>
                                        <th style="display:none;">Ttl Options Score</th>
                                        <th>&nbsp;</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $cntr = 0;
                                    while ($row2 = loc_db_fetch_array($result2)) {
                                        $cntr += 1;
                                        $ttlOptnsScore = 0;
                                        ?>
                                        <tr id="allAssessmentSetPrflsRow_<?php echo $cntr; ?>">                                    
                                            <td class="lovtd"><span><?php echo ($curIdx * $lmtSze) + ($cntr); ?></span></td>
                                            <td class="lovtd">
                                                <input type="hidden" class="form-control" aria-label="..." id="allAssessmentSetPrflsRow<?php echo $cntr; ?>_AssessmentSetPrflID" value="<?php echo $row2[0]; ?>" style="width:100% !important;"> 
                                                <input type="hidden" class="form-control" aria-label="..." id="allAssessmentSetPrflsRow<?php echo $cntr; ?>_AssessmentSetID" value="<?php echo $row2[1]; ?>" style="width:100% !important;">                                                                         
                                                <input type="hidden" class="form-control" aria-label="..." id="allAssessmentSetPrflsRow<?php echo $cntr; ?>_AssessmentPrflID" value="<?php echo $row2[2]; ?>" readonly="true"> 
                                                <div class="input-group">
                                                    <input type="text" class="form-control" aria-label="..." id="allAssessmentSetPrflsRow<?php echo $cntr; ?>_AssessmentPrflNm" name="allAssessmentSetPrflsRow<?php echo $cntr; ?>_AssessmentPrflNm" value="<?php echo $row2[3]; ?>" readonly="true">
                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'MCF Credit Risk Profiles - Valid', 'gnrlOrgID', '', '', 'radio', true, '', 'allAssessmentSetPrflsRow<?php echo $cntr; ?>_AssessmentPrflID', 'allAssessmentSetPrflsRow<?php echo $cntr; ?>_AssessmentPrflNm', 'clear', 1, '');">
                                                            <span class="glyphicon glyphicon-th-list"></span>
                                                    </label>
                                                </div>                                                                        
                                            </td>                                             
                                            <td class="lovtd"> 
                                                <input type="number" min="0" class="form-control" aria-label="..." id="allAssessmentSetPrflsRow<?php echo $cntr; ?>_Score" name="allAssessmentSetPrflsRow<?php echo $cntr; ?>_Score" value="<?php echo $row2[4]; ?>" readonly="readonly">
                                            </td>                                            
                                            <td class="lovtd">  
                                                <input type="number" min="0" class="form-control rqrdFld" aria-label="..." id="allAssessmentSetPrflsRow<?php echo $cntr; ?>_SortOrder" name="allAssessmentSetPrflsRow<?php echo $cntr; ?>_SortOrder" value="<?php echo $row2[5]; ?>">                                                               
                                            </td>
                                            <td class="lovtd" style="display:none;">
                                                <span id="allAssessmentSetPrflsRow<?php echo $cntr; ?>_TtlOptnsScore"><?php echo $ttlOptnsScore; ?></span>                                                              
                                            </td>	
                                            <td class="lovtd">
                                                <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delAssessmentSetPrfl('allAssessmentSetPrflsRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Risk Profile">
                                                    <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                </button>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                </tbody>
                            </table>                        
                        </div>                
                    </div>
                    <?php
                } else {
                    ?>
                    <span>No Results Found</span>
                    <?php
                }
                ?> 
            </div>                     
            <?php
        } 
        else if ($subVwtyp == 4.2) {
            $sbmtdAssessmentSetID = isset($_POST['sbmtdAssessmentSetID']) ? cleanInputData($_POST['sbmtdAssessmentSetID']) : -1;                    
            if ($sbmtdAssessmentSetID > 0) {
                $total = get_AllAssessmentSetPrflsTtl($srchFor, $srchIn, $sbmtdAssessmentSetID);
                //$total = get_AllBanksTtl($srchFor, $srchIn, $sbmtdAssessmentSetID);
                if ($pageNo > ceil($total / $lmtSze)) {
                    $pageNo = 1;
                } else if ($pageNo < 1) {
                    $pageNo = ceil($total / $lmtSze);
                }
                $curIdx = $pageNo - 1;
                $result2 = get_AllAssessmentSetPrfls($srchFor, $srchIn, $curIdx, $lmtSze, $sbmtdAssessmentSetID);
                ?>
                <div class="row" style="padding:0px 15px 0px 15px !important">
                    <legend class="basic_person_lg1" style="color: #003245">ASSESSED RISK PROFILES</legend>
                    <?php
                    if ($canEdtAssessmentSet === true) {
                        $colClassType1 = "col-lg-2";
                        $colClassType2 = "col-lg-3";
                        $colClassType3 = "col-lg-4";
                        $nwRowHtml = urlencode("<tr id=\"allAssessmentSetPrflsRow__WWW123WWW\">"
                                        . "<td class=\"lovtd\"><span class=\"normaltd\">New</span></td>"
                                        . "<td class=\"lovtd\">
                                                <div class=\"input-group\">
                                                    <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"allAssessmentSetPrflsRow_WWW123WWW_AssessmentPrflNm\" name=\"allAssessmentSetPrflsRow_WWW123WWW_AssessmentPrflNm\" value=\"\" readonly=\"true\">
                                                    <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'MCF Credit Risk Profiles - Valid', 'gnrlOrgID', '', '', 'radio', true, '', 'allAssessmentSetPrflsRow_WWW123WWW_AssessmentPrflID', 'allAssessmentSetPrflsRow_WWW123WWW_AssessmentPrflNm', 'clear', 1, '');\">
                                                            <span class=\"glyphicon glyphicon-th-list\"></span>
                                                    </label>
                                                    <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"allAssessmentSetPrflsRow_WWW123WWW_AssessmentPrflID\" value=\"-1\" style=\"width:100% !important;\"> 
                                                </div>
                                                <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"allAssessmentSetPrflsRow_WWW123WWW_AssessmentSetPrflID\" value=\"-1\" style=\"width:100% !important;\">                                                                                                                          
                                            </td>                                             
                                            <td class=\"lovtd\">
                                                    <input type=\"number\" min=\"0\" class=\"form-control\" aria-label=\"...\" id=\"allAssessmentSetPrflsRow_WWW123WWW_Score\" name=\"allAssessmentSetPrflsRow_WWW123WWW_Score\" value=\"\" readonly=\"readonly\">                                                               
                                            </td>
                                            <td class=\"lovtd\">
                                                    <input type=\"number\" min=\"0\" class=\"form-control rqrdFld\" aria-label=\"...\" id=\"allAssessmentSetPrflsRow_WWW123WWW_SortOrder\" name=\"allAssessmentSetPrflsRow_WWW123WWW_SortOrder\" value=\"\">                                                               
                                            </td>
                                            <td class=\"lovtd\" style=\"display:none;\">
                                                <span id=\"allAssessmentSetPrflsRow_WWW123WWW_TtlOptnsScore\"></span>                                                              
                                            </td>
                                            <td class=\"lovtd\">
                                                <button type=\"button\" class=\"btn btn-default\" style=\"margin: 0px !important;padding:0px 3px 2px 4px !important;\" onclick=\"delAssessmentSetPrfl('allAssessmentSetPrflsRow__WWW123WWW');\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Delete Profile Factor\">
                                                        <img src=\"cmn_images/no.png\" style=\"height:15px; width:auto; position: relative; vertical-align: middle;\">
                                                </button>
                                            </td>
                        </tr>");                                                
                        ?> 
                        <div class="<?php echo $colClassType1; ?>" style="padding:0px 1px 0px 3px !important;">     
                            <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="insertNewRowBe4('allAssessmentSetPrflsTable', 0, '<?php echo $nwRowHtml; ?>');" data-toggle="tooltip" data-placement="bottom" title="Add Profile Factor">
                                <img src="cmn_images/add1-64.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                            </button>
                            <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="saveAssessmentSetPrfl();" data-toggle="tooltip" data-placement="bottom" title="Save Profile Factor">
                                <img src="cmn_images/FloppyDisk.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                            </button>
                        </div>
                        <?php
                    } else {
                        $colClassType1 = "col-lg-4";
                        $colClassType2 = "col-lg-4";
                        $colClassType3 = "col-lg-4";
                    }
                    ?>
                    <div class="<?php echo $colClassType3; ?>" style="padding:0px 3px 0px 3px !important;">
                        <div class="input-group">
                            <input class="form-control" id="allAssessmentSetPrflsSrchFor" type = "text" placeholder="Search For" value="<?php
                            echo trim(str_replace("%", " ", $srchFor));
                            ?>" onkeyup="enterKeyFuncAllAssessmentSetPrfls(event, '', '#allAssessmentSetPrflsDetailInfo', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&subPgNo=<?php echo $subPgNo; ?>&subVwtyp=<?php echo "3.2"; ?>&sbmtdAssessmentSetID=<?php echo $sbmtdAssessmentSetID; ?>');">
                            <input id="allAssessmentSetPrflsPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                            <label class="btn btn-primary btn-file input-group-addon" onclick="getAllAssessmentSetPrfls('clear', '#allAssessmentSetPrflsDetailInfo', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&subPgNo=<?php echo $subPgNo; ?>&subVwtyp=<?php echo "4.2"; ?>&sbmtdAssessmentSetID=<?php echo $sbmtdAssessmentSetID; ?>');">
                                <span class="glyphicon glyphicon-remove"></span>
                            </label>
                            <label class="btn btn-primary btn-file input-group-addon" onclick="getAllAssessmentSetPrfls('', '#allAssessmentSetPrflsDetailInfo', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&subPgNo=<?php echo $subPgNo; ?>&subVwtyp=<?php echo "4.2"; ?>&sbmtdAssessmentSetID=<?php echo $sbmtdAssessmentSetID; ?>');">
                                <span class="glyphicon glyphicon-search"></span>
                            </label> 
                        </div>
                    </div>
                    <div class="<?php echo $colClassType2; ?>" style="padding:0px 3px 0px 3px !important;">
                        <div class="input-group">
                            <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                            <!--<select data-placeholder="Select..." class="form-control chosen-select" id="allAssessmentSetPrflsSrchIn">
                            <?php
                            $valslctdArry = array("");
                            $srchInsArrys = array("Name");

                            for ($z = 0; $z < count($srchInsArrys); $z++) {
                                if ($srchIn == $srchInsArrys[$z]) {
                                    $valslctdArry[$z] = "selected";
                                }
                                ?>
                                                                                        <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                            <?php } ?>
                            </select>-->
                            <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                            <select data-placeholder="Select..." class="form-control chosen-select" id="allAssessmentSetPrflsDsplySze" style="min-width:70px !important;">                            
                                <?php
                                $valslctdArry = array("", "", "", "", "", "", "", "");
                                $dsplySzeArry = array(1, 5, 10, 15, 30, 50, 100, 500, 1000);
                                for ($y = 0; $y < count($dsplySzeArry); $y++) {
                                    if ($lmtSze == $dsplySzeArry[$y]) {
                                        $valslctdArry[$y] = "selected";
                                    } else {
                                        $valslctdArry[$y] = "";
                                    }
                                    ?>
                                    <option value="<?php echo $dsplySzeArry[$y]; ?>" <?php echo $valslctdArry[$y]; ?>><?php echo $dsplySzeArry[$y]; ?></option>                            
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="<?php echo $colClassType2; ?>" style="padding:0px 3px 0px 3px !important;">
                        <nav aria-label="Page navigation">
                            <ul class="pagination" style="margin: 0px !important;">
                                <li>
                                    <a class="rhopagination" href="javascript:getAllAssessmentSetPrfls('previous', '#allAssessmentSetPrflsDetailInfo', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&subPgNo=<?php echo $subPgNo; ?>&subVwtyp=<?php echo "4.2"; ?>&sbmtdAssessmentSetID=<?php echo $sbmtdAssessmentSetID; ?>');" aria-label="Previous">
                                        <span aria-hidden="true">&laquo;</span>
                                    </a>
                                </li>
                                <li>
                                    <a class="rhopagination" href="javascript:getAllAssessmentSetPrfls('next', '#allAssessmentSetPrflsDetailInfo', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&subPgNo=<?php echo $subPgNo; ?>&subVwtyp=<?php echo "4.2"; ?>&sbmtdAssessmentSetID=<?php echo $sbmtdAssessmentSetID; ?>');" aria-label="Next">
                                        <span aria-hidden="true">&raquo;</span>
                                    </a>
                                </li>
                            </ul>
                        </nav>
			<input type="hidden" class="form-control" aria-label="..." id="gnrlOrgID" name="gnrlOrgID" value="<?php echo $orgID; ?>">                         
                        <input type="hidden" class="form-control" aria-label="..." id="sbmtdAssessmentSetID" name="sbmtdAssessmentSetID" value="<?php echo $sbmtdAssessmentSetID; ?>">
                    </div>
                </div>
                <div class="row" style="padding:0px 15px 0px 15px !important">                  
                    <div class="col-md-12" style="padding:0px 3px 0px 3px !important">
                        <table class="table table-striped table-bordered table-responsive" id="allAssessmentSetPrflsTable" cellspacing="0" width="100%" style="width:100%;min-width: 300px !important;">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Risk Profile</th>
                                    <th>Score</th>
                                    <th>Sort Order</th>
                                    <th style="display:none;">Ttl Option Score</th>
                                    <th>&nbsp;</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $cntr = 0;
                                while ($row2 = loc_db_fetch_array($result2)) {
                                    $cntr += 1;
                                    $ttlOptnsScore = 0;
                                    ?>
                                    <tr id="allAssessmentSetPrflsRow_<?php echo $cntr; ?>">                                    
                                        <td class="lovtd"><span><?php echo ($curIdx * $lmtSze) + ($cntr); ?></span></td>
                                        <td class="lovtd">
                                            <input type="hidden" class="form-control" aria-label="..." id="allAssessmentSetPrflsRow<?php echo $cntr; ?>_AssessmentSetPrflID" value="<?php echo $row2[0]; ?>" style="width:100% !important;"> 
                                            <input type="hidden" class="form-control" aria-label="..." id="allAssessmentSetPrflsRow<?php echo $cntr; ?>_AssessmentSetID" value="<?php echo $row2[1]; ?>" style="width:100% !important;">                                                                         
                                            <input type="hidden" class="form-control" aria-label="..." id="allAssessmentSetPrflsRow<?php echo $cntr; ?>_AssessmentPrflID" value="<?php echo $row2[2]; ?>" readonly="true">
                                            <div class="input-group">
                                                <input type="text" class="form-control" aria-label="..." id="allAssessmentSetPrflsRow<?php echo $cntr; ?>_AssessmentPrflNm" name="allAssessmentSetPrflsRow<?php echo $cntr; ?>_AssessmentPrflNm" value="<?php echo $row2[3]; ?>" readonly="true">
                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'MCF Credit Risk Profiles - Valid', 'gnrlOrgID', '', '', 'radio', true, '', 'allAssessmentSetPrflsRow<?php echo $cntr; ?>_AssessmentPrflID', 'allAssessmentSetPrflsRow<?php echo $cntr; ?>_AssessmentPrflNm', 'clear', 1, '');">
                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                </label>
                                            </div>                                                                        
                                        </td>                                             
                                        <td class="lovtd"> 
                                            <input type="number" min="0" class="form-control" aria-label="..." id="allAssessmentSetPrflsRow<?php echo $cntr; ?>_Score" name="allAssessmentSetPrflsRow<?php echo $cntr; ?>_Score" value="<?php echo $row2[4]; ?>" readonly="readonly">
                                        </td>                                            
                                        <td class="lovtd">  
                                            <input type="number" min="0" class="form-control rqrdFld" aria-label="..." id="allAssessmentSetPrflsRow<?php echo $cntr; ?>_SortOrder" name="allAssessmentSetPrflsRow<?php echo $cntr; ?>_SortOrder" value="<?php echo $row2[5]; ?>">                                                               
                                        </td>
                                        <td class="lovtd" style="display:none;">
                                                <span id="allAssessmentSetPrflsRow<?php echo $cntr; ?>_TtlOptnsScore"><?php echo $ttlOptnsScore; ?></span>                                                              
                                            </td>
                                        <td class="lovtd">
                                            <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delAssessmentSetPrfl('allAssessmentSetPrflsRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Risk Profile">
                                                <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                            </button>
                                        </td>
                                    </tr>
                                    <?php
                                }
                                ?>
                            </tbody>
                        </table>                        
                    </div>                
                </div>
                <?php
            } else {
                ?>
                <span>No Results Found</span>
                <?php
            }
        }       
        }
    
        
    }
    else if ($subPgNo == 4.7) {//PROPERTY COLLATERALS
        //if ($vwtypActn == "EDIT" || $vwtypActn == "VIEW") {}
        $prptyColtCode = "";
        $prptyColtNm = "";
        $prptyColtDesc = "";
        $prptyColtType = "";
        $prptyOwnerCust = "";
        $prptyValuerSupID = -1;
        $prptyValue = 0.00;
        $prptyColtParentID = -1;
        $prptyOwnerCustID = -1;
        $prptyOwnerCustType = "Individual";
        $isEnabled = "";
        $prptyColtId = $pkID;
        $mkReadOnly = "";
        $mkReadOnlyDsbld = "";
        $trnsStatus = "Incomplete";
        $rqstatusColor = "red";


        $result = getPropertyCollateralDets($pkID);
        while($row = loc_db_fetch_array($result)){
            $prptyColtCode = $row[8];
            $prptyColtNm = $row[1];
            $prptyColtDesc = $row[2];
            $prptyColtType = $row[3];
            $prptyOwnerCust = $row[4];
            $prptyValuerSupID = $row[5];
            $prptyValue = $row[6];
            $prptyColtParentID = $row[7];
            $prptyOwnerCustID = $row[9];
            $prptyOwnerCustType = $row[16];
            $trnsStatus = $row[14];
            
            if(($trnsStatus == "Unauthorized") || $vwtypActn == "VIEW"){
                $mkReadOnly = "readonly=\"readonly\"";
                $mkReadOnlyDsbld = "disabled=\"true\"";
            }            
        }


        ?>
        <div class="col-md-12" style="padding:0px 0px 10px 1px !important;">
            <div style="padding:0px 1px 0px 15px !important;float:left;">
                    <button type="button" class="btn btn-default btn-sm" style="height:30px;" id="myVmsTrnsStatusBtn">
                            <span style="font-weight:bold;">Status: </span><span style="color:<?php echo $rqstatusColor; ?>;font-weight: bold;"><?php echo $trnsStatus; ?></span>
                    </button>
                    <?php if($vwtypActn != "VIEW") { ?>
                    <button type="button" class="btn btn-default btn-sm" style="height:30px;" onclick="getPrptyColtsForm('myFormsModalxLG', 'myFormsModalxBodyLG', 'myFormsModalxTitleLG', 'Edit Property Collateral', 15, <?php echo $subPgNo; ?>,0,'EDIT', <?php echo $pkID; ?>,'indCustTable','indCustTableRow1');" data-toggle="tooltip" title="Reload Transaction">
                            <img src="cmn_images/refresh.bmp" style="padding-right: 2px; height:17px; width:auto; position: relative; vertical-align: middle;">
                    </button>
                    <?php } ?>
                    <button type="button" class="btn btn-default" style="height:30px;" onclick="getOneMcfDocsForm_Gnrl(<?php echo $pkID; ?>,'PROPERTY COLLATERAL', 140, 'Property Collateral Form');" data-toggle="tooltip" data-placement="bottom" title = "Attached Documents">
                        <img src="cmn_images/adjunto.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                    </button>                                                    
            </div>
            <div style="padding:0px 1px 0px 1px !important;float:right;">
                    <?php if (($trnsStatus == "Incomplete" || $trnsStatus == "Withdrawn" || $trnsStatus == "Rejected") && $vwtypActn != "VIEW") { ?>                                                    
                            <button type="button" class="btn btn-default btn-sm" style="height:30px;" onclick="savePrptyColt(<?php echo $pgNo; ?>, <?php echo $subPgNo; ?>, <?php echo $vwtyp;?>);"><img src="cmn_images/FloppyDisk.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Save&nbsp;</button>
                            <button type="button" class="btn btn-default btn-sm" style="height:30px;" onclick="submitWithdrawPrptyColt(<?php echo $pgNo; ?>, <?php echo $subPgNo; ?>, 1, 'SUBMIT');"><img src="cmn_images/Emailcon.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Submit&nbsp;</button>   
                    <?php 
                    } else if ($trnsStatus == "Unauthorized" && $vwtypActn != "VIEW") {
                            ?>  
                            <button type="button" class="btn btn-default btn-sm" style="height:30px;" onclick="submitWithdrawPrptyColt(<?php echo $pgNo; ?>, <?php echo $subPgNo; ?>, 1, 'REJECT');"><img src="cmn_images/withdraw_rqst.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Reject&nbsp;</button>
                            <button type="button" class="btn btn-default btn-sm" style="height:30px;" onclick="submitWithdrawPrptyColt(<?php echo $pgNo; ?>, <?php echo $subPgNo; ?>, 1, 'WITHDRAW');"><img src="cmn_images/withdraw_rqst.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Withdraw&nbsp;</button>                                      
                            <button type="button" class="btn btn-default btn-sm" style="height:30px;" onclick="submitWithdrawPrptyColt(<?php echo $pgNo; ?>, <?php echo $subPgNo; ?>, 1, 'AUTHORIZE');"><img src="cmn_images/Stamp-512.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Authorize&nbsp;</button>                                                                                                        
                            <button type="button" class="btn btn-default btn-sm" style="height:30px;" onclick="checkWkfRqstStatus(<?php echo $routingID; ?>, 'Teller Trns. Authorization Progress History');"><img src="cmn_images/workflow.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">&nbsp;</button>                                
                                    <?php
                            } else if (($trnsStatus == "Authorized") && $vwtypActn != "VIEW") {
                                    ?>
                            <button type="button" class="btn btn-default btn-sm" style="height:30px;" onclick="checkWkfRqstStatus(<?php echo $routingID; ?>, 'Teller Trns. Authorization Progress History');"><img src="cmn_images/workflow.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;" data-toggle="tooltip" title="Authorization Progress History">&nbsp;</button>  
                            <button type="button" style="display:none !important;" class="btn btn-default btn-sm" style="height:30px;" onclick="submitWithdrawPrptyColt(<?php echo $pgNo; ?>, <?php echo $subPgNo; ?>, 1, 'REVERSE');"><img src="cmn_images/90.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Reverse Approval&nbsp;</button>                               
                    <?php } ?>
            </div>
        </div>            
        <form class="form-horizontal" id="riskFactorsForm" style="padding:5px 20px 5px 20px;">             
            <div class="row">      
                <div class="form-group form-group-sm">
                    <label for="prptyColtCode" class="control-label col-md-4">Code:</label>
                    <div  class="col-md-8">
                        <input type="text" readonly="readonly" class="form-control" id="prptyColtCode" placeholder="Collateral Code" value="<?php echo $prptyColtCode; ?>"/>
                    </div>
                </div>
                <div class="form-group form-group-sm">
                    <label for="prptyColtType" class="control-label col-md-4">Property Type:</label>
                    <div  class="col-md-8">
                        <div class="input-group">
                            <input type="text" class="form-control" aria-label="..." id="prptyColtType" value="<?php echo $prptyColtType; ?>" readonly="readonly">
                            <input type="hidden" id="prptyColtTypeID" value="<?php echo $prptyColtType; ?>">
                            <input type="hidden" id="gnrlOrgID" value="<?php echo $orgID; ?>">
                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'MCF Property Collateral Types', 'gnrlOrgID', '', '', 'radio', true, '', 'prptyColtTypeID', 'prptyColtType', 'clear', 1, '');;">
                                <span class="glyphicon glyphicon-th-list"></span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group form-group-sm">
                    <input class="form-control" size="16" type="hidden" id="prptyColtId" value="<?php echo $prptyColtId; ?>" readonly=""> 
                    <label for="prptyColtNm" class="control-label col-md-4">Asset Name/Number:</label>
                    <div  class="col-md-8">
                        <input type="text" <?php echo $mkReadOnly; ?> class="form-control rqrdFld" id="prptyColtNm" placeholder="Asset Name/Number" value="<?php echo $prptyColtNm; ?>"/>
                    </div>
                </div>                    
                <div class="form-group form-group-sm">
                    <label for="prptyColtDesc" class="control-label col-md-4">Description:</label>
                    <div  class="col-md-8">
                        <textarea <?php echo $mkReadOnly; ?> class="form-control" id="prptyColtDesc" cols="2" placeholder="Description" rows="2"><?php echo $prptyColtDesc; ?></textarea>
                    </div>
                </div>
                <div class="form-group form-group-sm">
                    <label for="prptyColtParent" class="control-label col-md-4">Parent Property:</label>
                    <div  class="col-md-8">
                        <div class="input-group">
                            <input type="text" class="form-control" aria-label="..." id="prptyColtParent" value="<?php echo getGnrlRecNm("mcf.mcf_property_collaterals", "prpty_collateral_id", "collateral_name", $prptyColtParentID); ?>" readonly="readonly">
                            <input type="hidden" id="prptyColtParentID" value="<?php echo $prptyColtParentID; ?>">
                            <input type="hidden" id="gnrlOrgID" value="<?php echo $orgID; ?>">
                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Property Collaterals', 'gnrlOrgID', '', '', 'radio', true, '', 'prptyColtParentID', 'prptyColtParent', 'clear', 1, '');;">
                                <span class="glyphicon glyphicon-th-list"></span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group form-group-sm">
                    <label for="prptyOwnerCustType" class="control-label col-md-4">Owner Type:</label>
                    <input type="hidden" id="custTypeYes" value="YES">
                    <div  class="col-md-8">
                        <select <?php echo $mkReadOnlyDsbld; ?> class="form-control" id="prptyOwnerCustType" onchange="">
                            <?php
                            $sltdCorp = "";
                            $sltdIndv = "";
                            $sltdGrp = "";
                            if ($prptyOwnerCustType == "Corporate") {
                                $sltdCorp = "selected";
                            } else if ($prptyOwnerCustType == "Individual") {
                                $sltdIndv = "selected";
                            } else if ($prptyOwnerCustType == "Group") {
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
                    <label for="prptyOwnerCust" class="control-label col-md-4">Owner Name:</label>
                    <div  class="col-md-8">
                        <div class="input-group">
                            <input type="text" class="form-control" aria-label="..." id="prptyOwnerCust" value="<?php echo $prptyOwnerCust; ?>" readonly="readonly">
                            <input type="hidden" id="prptyOwnerCustID" value="<?php echo $prptyOwnerCustID; ?>">
                            <input type="hidden" id="gnrlOrgID" value="<?php echo $orgID; ?>">
                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Bank Customers', 'gnrlOrgID', 'prptyOwnerCustType', '', 'radio', true, '', 'prptyOwnerCustID', 'prptyOwnerCust', 'clear', 1, '');;">
                                <span class="glyphicon glyphicon-th-list"></span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group form-group-sm">
                    <label for="prptyValue" class="control-label col-md-4">Property Value:</label>
                    <div  class="col-md-8">
                        <div class="input-group">
                            <label class="btn btn-primary btn-file input-group-addon" onclick="">
                                GHS
                            </label>
                            <input type="number" min="0" class="form-control rqrdFld" aria-label="..." id="prptyValue" value="<?php echo $prptyValue; ?>" <?php echo $mkReadOnly; ?>>
                        </div>
                    </div>
                </div>
                <div class="form-group form-group-sm">
                    <label for="prptyValuerSup" class="control-label col-md-4">Valuer:</label>
                    <div  class="col-md-8">
                        <div class="input-group">
                            <input type="text" class="form-control" aria-label="..." id="prptyValuerSup" value="<?php echo getGnrlRecNm("scm.scm_cstmr_suplr", "cust_sup_id", "cust_sup_name", $prptyValuerSupID); ?>" readonly="readonly">
                            <input type="hidden" id="prptyValuerSupID" value="<?php echo $prptyValuerSupID; ?>">
                            <input type="hidden" id="lnkdPrsnID" value="-1">
                            <input type="hidden" id="gnrlOrgID" value="<?php echo $orgID; ?>">
                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Customers and Suppliers', 'gnrlOrgID', 'lnkdPrsnID', '', 'radio', true, '', 'prptyValuerSupID', 'prptyValuerSup', 'clear', 1, '');">
                                <span class="glyphicon glyphicon-th-list"></span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row" style="float:right;padding-right: 1px;display:none !important;">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>>
            </div>                   
        </form>
        <?php      
    }
    else if ($subPgNo == 4.8 || $subPgNo == 4.81 || $subPgNo == 4.82) {//SECTOR CLASSIFICATION
        //var_dump($_POST);
        $canAddSectorMajor = test_prmssns($dfltPrvldgs[277], $mdlNm);
        $canEdtSectorMajor = test_prmssns($dfltPrvldgs[278], $mdlNm);
        $canDelSectorMajor = test_prmssns($dfltPrvldgs[279], $mdlNm);

        $error = "";
        $searchAll = true;
        $isEnabledOnly = false;
        if (isset($_POST['isEnabled'])) {
            $isEnabledOnly = cleanInputData($_POST['isEnabled']);
        }


        $srchFor = isset($_POST['searchfor']) ? cleanInputData($_POST['searchfor']) : '';
        $srchIn = isset($_POST['searchin']) ? cleanInputData($_POST['searchin']) : 'Both';
        $pageNo = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
        $lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 10;
        $sortBy = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : "Date Added DESC";
        if (strpos($srchFor, "%") === FALSE) {
            $srchFor = "%" . str_replace(" ", "%", $srchFor) . "%";
            $srchFor = str_replace("%%", "%", $srchFor);
        }

        if ($subPgNo == 4.8) {
            echo  $cntent .= " <li onclick=\"openATab('#allmodules', 'grp=17&typ=1&pg=4');\">
                                                <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
						<span style=\"text-decoration:none;\">Credit Management Menu</span>
                                        </li>
                        <li onclick=\"openATab('#allmodules', 'grp=17&typ=1&pg=4&subPgNo=4.8');\">
                                                                <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                                                <span style=\"text-decoration:none;\">Sector Classifications</span>
                            </li></div>";            
            
            $total = getSectorMajorTblTtl($isEnabledOnly, $srchFor, $srchIn, $orgID, $searchAll);
            if ($pageNo > ceil($total / $lmtSze)) {
                $pageNo = 1;
            } else if ($pageNo < 1) {
                $pageNo = ceil($total / $lmtSze);
            }

            $curIdx = $pageNo - 1;
            $result = getSectorMajorTbl($isEnabledOnly, $srchFor, $srchIn, $curIdx, $lmtSze, $orgID, $searchAll, $sortBy);
            $cntr = 0;
            $colClassType1 = "col-lg-2";
            $colClassType2 = "col-lg-3";
            $colClassType3 = "col-lg-1";
            ?>
            <form id='allSectorMajorsForm' action='' method='post' accept-charset='UTF-8'>
                <!--<fieldset class="basic_person_fs5">-->
                    <legend class="basic_person_lg1" style="color: #003245">SECTOR CLASSIFICATIONS</legend>                
                    <input class="form-control" id="tblRowID" type = "hidden" placeholder="ROW ID"/>                     
                    <div class="row rhoRowMargin" style="margin-bottom:10px;">
                        <?php
                        if ($canAddSectorMajor === true) {
                            ?>   
                            <div class="<?php echo $colClassType1; ?>" style="padding:0px 1px 0px 15px !important;">                    
                                <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="getOneSectorMajorForm(-1, <?php echo $vwtyp; ?>);">
                                    <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                    New Major Sector
                                </button>
                            </div>
                            <?php
                        } else {
                            $colClassType1 = "col-lg-2";
                            $colClassType2 = "col-lg-3";
                            $colClassType3 = "col-lg-1";
                        }
                        ?>
                        <div class="<?php echo $colClassType2; ?>" style="padding:0px 15px 0px 15px !important;">
                            <div class="input-group">
                                <input class="form-control" id="allSectorMajorsSrchFor" type = "text" placeholder="Search For" value="<?php echo trim(str_replace("%", " ", $srchFor)); ?>" onkeyup="enterKeyFuncCust(event, '', '#allmodules', 'grp=17&typ=1&pg=4&subPgNo=4.1')">
                                <input id="allSectorMajorsPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                <label class="btn btn-primary btn-file input-group-addon" onclick="getAllSectorMajors('clear', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&subPgNo=<?php echo $subPgNo; ?>');">
                                    <span class="glyphicon glyphicon-remove"></span>
                                </label>
                                <label class="btn btn-primary btn-file input-group-addon" onclick="getAllSectorMajors('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&subPgNo=<?php echo $subPgNo; ?>');">
                                    <span class="glyphicon glyphicon-search"></span>
                                </label> 
                            </div>
                        </div>
                        <div class="<?php echo $colClassType2; ?>">
                            <div class="input-group">
                                <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                <select data-placeholder="Select..." class="form-control chosen-select" id="allSectorMajorsSrchIn">
                                    <?php
                                    $valslctdArry = array("", "");
                                    $srchInsArrys = array("Major Sector","Description");
                                    for ($z = 0; $z < count($srchInsArrys); $z++) {
                                        if ($srchIn == $srchInsArrys[$z]) {
                                            $valslctdArry[$z] = "selected";
                                        }
                                        ?>
                                        <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                    <?php } ?>
                                </select>
                                <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                <select data-placeholder="Select..." class="form-control chosen-select" id="allSectorMajorsDsplySze" style="min-width:70px !important;">                            
                                    <?php
                                    $valslctdArry = array("", "", "", "", "", "", "", "");
                                    $dsplySzeArry = array(1, 5, 10, 15, 30, 50, 100, 500, 1000);
                                    for ($y = 0; $y < count($dsplySzeArry); $y++) {
                                        if ($lmtSze == $dsplySzeArry[$y]) {
                                            $valslctdArry[$y] = "selected";
                                        } else {
                                            $valslctdArry[$y] = "";
                                        }
                                        ?>
                                        <option value="<?php echo $dsplySzeArry[$y]; ?>" <?php echo $valslctdArry[$y]; ?>><?php echo $dsplySzeArry[$y]; ?></option>                            
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>                        
                        <div class="<?php echo $colClassType3; ?>" style="padding: 5px 1px 0px 15px !important">
                            <div class="form-check" style="font-size: 12px !important;">
                                <label class="form-check-label">
                                    <?php
                                    $nonAprvdChekd = "";
                                    if ($isEnabledOnly == "true") {
                                        $nonAprvdChekd = "checked=\"true\"";
                                    }
                                    ?>
                                    <input type="checkbox" class="form-check-input" onclick="getAllSectorMajors('clear', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&subPgNo=<?php echo $subPgNo; ?>');" id="allSectorMajorsIsEnabled" name="allSectorMajorsIsEnabled" <?php echo $nonAprvdChekd; ?>>
                                    Enabled?
                                </label>
                            </div>                             
                        </div>
                        <div class="<?php echo $colClassType3; ?>" style="width:10% !important;">
                            <nav aria-label="Page navigation">
                                <ul class="pagination" style="margin: 0px !important;">
                                    <li>
                                        <a href="javascript:getAllSectorMajors('previous', '#allmodules', 'grp=17&typ=1&pg=4&subPgNo=4.8');" aria-label="Previous">
                                            <span aria-hidden="true">&laquo;</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:getAllSectorMajors('next', '#allmodules', 'grp=17&typ=1&pg=4&subPgNo=4.8');" aria-label="Next">
                                            <span aria-hidden="true">&raquo;</span>
                                        </a>
                                    </li>
                                </ul>
                            </nav>
                        </div>  
                        <div class="<?php echo $colClassType3; ?>" style="padding:0px 1px 0px 3px !important;"> 
                            <div style="float:right !important;">
                                <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="saveSectorMajor(<?php echo $pgNo; ?>, <?php echo $subPgNo; ?>, <?php echo $subVwtyp; ?>, <?php echo $vwtyp;?>);" data-toggle="tooltip" data-placement="bottom" title="Save Major Sector">
                                    <img src="cmn_images/FloppyDisk.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">&nbsp;&nbsp;Save
                                </button>
                            </div>
                        </div>
                    </div>               
                    <div class="row" style="padding:0px 15px 0px 15px !important"> 
                        <div class="col-md-4" style="padding:0px 1px 0px 1px !important">
                            <fieldset class="basic_person_fs">                                        
                                <table class="table table-striped table-bordered table-responsive" id="allSectorMajorsTable" cellspacing="0" width="100%" style="width:100%;">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Major Sector</th>
                                            <th>Description</th>                                   
                                            <?php if ($canDelSectorMajor === true) { ?>
                                                <th>&nbsp;</th>
                                            <?php } ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $sbmtdSectorMajorID = -1;
                                        while ($row = loc_db_fetch_array($result)) {
                                            //if ($sbmtdSectorMajorID <= 0 && $cntr <= 0) {
                                                $sbmtdSectorMajorID = $row[0];
                                            //}
                                            $cntr += 1;
                                            ?>
                                            <tr id="allSectorMajorsRow_<?php echo $cntr; ?>" class="hand_cursor">                                    
                                                <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                                <td class="lovtd"><?php echo $row[1]; ?>
                                                    <input type="hidden" class="form-control" aria-label="..." id="allSectorMajorsRow<?php echo $cntr; ?>_SectorMajorID" value="<?php echo $row[0]; ?>">
                                                </td>
                                                <td class="lovtd">
                                                    <?php echo $row[2]; ?>
                                                </td>
                                                <?php if ($canDelSectorMajor === true) { ?>
                                                    <td class="lovtd">
                                                        <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="deleteSectorMajor(<?php echo $sbmtdSectorMajorID; ?>)" data-toggle="tooltip" data-placement="bottom" title="Delete Major Sector">
                                                            <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                        </button>
                                                    </td>
                                                <?php } ?>
                                            </tr>
                                            <?php
                                        }
                                        ?>
                                    </tbody>
                                </table>                        
                            </fieldset>
                        </div>                        
                        <div  class="col-md-8" style="padding:0px 1px 0px 1px !important">
                            <fieldset class="basic_person_fs" style="padding-top:5px !important;">
                                <div class="" id="allSectorMajorsDetailInfo">
                                    <div class="row" id="allSectorMajorsHdrInfo" style="padding:0px 15px 0px 15px !important">
                                        <?php
                                        $oneSectorMajorDetID = -1;
                                        $oneSectorMajorDetName = "";
                                        $oneSectorMajorDetDesc = "";
                                        $oneSectorMajorDetIsEnbld = "Yes";
                                        $result1 = getSectorMajorDets($sbmtdSectorMajorID);
                                        while ($row1 = loc_db_fetch_array($result1)) {
                                            $oneSectorMajorDetID = $row1[0];
                                            $oneSectorMajorDetName = $row1[1];
                                            $oneSectorMajorDetDesc = $row1[2];
                                            $oneSectorMajorDetIsEnbld = $row1[3];
                                        }                                         
                                        ?>
                                            <fieldset class="basic_person_fs" style="padding:10px 3px 0px 3px !important;border:none !important;border-bottom:1px solid #ddd !important;border-radius: 0px !important;">                                              
                                                <div  class="col-md-6" style="padding:0px 3px 0px 3px !important;">
                                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                        <label for="oneSectorMajorDetName" class="control-label col-lg-4" style="padding:0px 15px 0px 1px !important;">Major Sector:</label>
                                                        <div  class="col-lg-8" style="padding:0px 15px 0px 1px !important;">
                                                            <?php if ($canEdtSectorMajor === true) { ?>
                                                                <input type="text" class="form-control rqrdFld" aria-label="..." id="oneSectorMajorDetName" name="oneSectorMajorDetName" value="<?php echo $oneSectorMajorDetName; ?>" style="width:100%;">
                                                                <input type="hidden" class="form-control" aria-label="..." id="oneSectorMajorDetID" name="oneSectorMajorDetID" value="<?php echo $oneSectorMajorDetID; ?>">
                                                            <?php } else {
                                                                ?>
                                                                <span><?php echo $oneSectorMajorDetName; ?></span>
                                                                <?php
                                                            }
                                                            ?>
                                                        </div>
                                                    </div>
                                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                        <label for="oneSectorMajorDetDesc" class="control-label col-lg-4" style="padding:0px 15px 0px 1px !important;">Description:</label>
                                                        <div  class="col-lg-8" style="padding:0px 15px 0px 1px !important;">
                                                            <?php if ($canEdtSectorMajor === true) { ?>
                                                                <textarea class="form-control" aria-label="..." id="oneSectorMajorDetDesc" name="oneSectorMajorDetDesc" style="width:100%;" cols="5" rows="3"><?php echo $oneSectorMajorDetDesc; ?></textarea>
                                                            <?php } else {
                                                                ?>
                                                                <span><?php echo $oneSectorMajorDetDesc; ?></span>
                                                                <?php
                                                            }
                                                            ?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div  class="col-md-6" style="padding:0px 3px 0px 3px !important;">                                                        
                                                   <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                        <label for="oneSectorMajorDetIsEnbld" class="control-label col-lg-4" style="padding:0px 15px 0px 1px !important;">Is Enabled:</label>
                                                        <div  class="col-lg-8" style="padding:0px 15px 0px 1px !important;">
                                                            <?php if ($canEdtSectorMajor === true) { ?>
                                                                <select class="form-control" id="oneSectorMajorDetIsEnbld" >
                                                                    <?php
                                                                    $sltdYes = "";
                                                                    $sltdNo = "";
                                                                    if ($oneSectorMajorDetIsEnbld == "Yes") {
                                                                        $sltdYes = "selected";
                                                                    } else if ($oneSectorMajorDetIsEnbld == "No") {
                                                                        $sltdNo = "selected";
                                                                    }
                                                                    ?>
                                                                    <option value="Yes" <?php echo $sltdYes; ?>>Yes</option>
                                                                    <option value="No" <?php echo $sltdNo; ?>>No</option>
                                                                </select>
                                                            <?php } else {
                                                                ?>
                                                                <span><?php echo $oneSectorMajorDetIsEnbld; ?></span>
                                                                <?php
                                                            }
                                                            ?>
                                                        </div>
                                                    </div>                                                    
                                                </div>
                                            </fieldset>
                                    </div> 
                                    <div class="row" id="allSectorMinorDetailInfo" style="padding:0px 15px 0px 15px !important">
                                        <?php
                                        /* &vtyp=<?php echo $vwtyp; ?> */
                                        $srchFor = "%";
                                        $srchIn = "Risk Factor";
                                        $pageNo = 1;
                                        $lmtSze = 10;
                                        $vwtyp = 1;
                                        if ($sbmtdSectorMajorID > 0) {
                                            $total = get_AllSectorMinorTtl($srchFor, $srchIn, $sbmtdSectorMajorID);
                                            //$total = get_AllBanksTtl($srchFor, $srchIn, $sbmtdSectorMajorID);
                                            if ($pageNo > ceil($total / $lmtSze)) {
                                                $pageNo = 1;
                                            } else if ($pageNo < 1) {
                                                $pageNo = ceil($total / $lmtSze);
                                            }
                                            $curIdx = $pageNo - 1;
                                            $result2 = get_AllSectorMinor($srchFor, $srchIn, $curIdx, $lmtSze, $sbmtdSectorMajorID);
                                            ?>
                                            <div class="row" style="padding:0px 15px 0px 15px !important">
                                                <legend class="basic_person_lg1" style="color: #003245">MINOR SECTORS</legend>
                                                <?php
                                                if ($canEdtSectorMajor === true) {
                                                    $colClassType1 = "col-lg-2";
                                                    $colClassType2 = "col-lg-3";
                                                    $colClassType3 = "col-lg-4";
                                                    $nwRowHtml = urlencode("<tr id=\"allSectorMinorRow__WWW123WWW\">"
                                                                    . "<td class=\"lovtd\"><span class=\"normaltd\">New</span></td>"
                                                                    . " <td class=\"lovtd\">
                                                                                <input type=\"text\" min=\"0\" class=\"form-control rqrdFld\" aria-label=\"...\" id=\"allSectorMinorRow_WWW123WWW_MinorSectorNm\" name=\"allSectorMinorRow_WWW123WWW_MinorSectorNm\" value=\"\">                                                               
                                                                        </td>
                                                                        <td class=\"lovtd\">
                                                                                <input type=\"text\" min=\"0\" class=\"form-control rqrdFld\" aria-label=\"...\" id=\"allSectorMinorRow_WWW123WWW_MinorSectorDesc\" name=\"allSectorMinorRow_WWW123WWW_MinorSectorDesc\" value=\"\">                                                               
                                                                        </td>
									<td class=\"lovtd\">
                                                                            <select class=\"form-control\" id=\"allSectorMinorRow_WWW123WWW_isEnabledNm\" name=\"allSectorMinorRow_WWW123WWW_isEnabledNm\" >
                                                                                <option value=\"Yes\" selected>Yes</option>
                                                                                <option value=\"No\" >No</option>
                                                                            </select>
                                                                            <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"allSectorMinorRow_WWW123WWW_MinorSectorID\" value=\"-1\" style=\"width:100% !important;\">                                                                                                                          
                                                                        </td>
                                                                        <td class=\"lovtd\">
                                                                            <button type=\"button\" class=\"btn btn-default\" style=\"margin: 0px !important;padding:0px 3px 2px 4px !important;\" onclick=\"delSectorMinor('allSectorMinorRow__WWW123WWW');\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Delete Minor Sector\">
                                                                                    <img src=\"cmn_images/no.png\" style=\"height:15px; width:auto; position: relative; vertical-align: middle;\">
                                                                            </button>
                                                                        </td>
                                                    </tr>");
                                                    ?>
                                                    <div class="<?php echo $colClassType1; ?>" style="padding:0px 1px 0px 3px !important;">     
                                                        <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="insertNewRowBe4('allSectorMinorTable', 0, '<?php echo $nwRowHtml; ?>');" data-toggle="tooltip" data-placement="bottom" title="Add Minor Sector">
                                                            <img src="cmn_images/add1-64.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                        </button>
                                                        <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="saveSectorMinor();" data-toggle="tooltip" data-placement="bottom" title="Save Minor Sector">
                                                            <img src="cmn_images/FloppyDisk.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                        </button>
                                                    </div>
                                                    <?php
                                                } else {
                                                    $colClassType1 = "col-lg-4";
                                                    $colClassType2 = "col-lg-4";
                                                    $colClassType3 = "col-lg-4";
                                                }
                                                ?>
                                                <div class="<?php echo $colClassType3; ?>" style="padding:0px 3px 0px 3px !important;">
                                                    <div class="input-group">
                                                        <input class="form-control" id="allSectorMinorSrchFor" type = "text" placeholder="Search For" value="<?php
                                                        echo trim(str_replace("%", " ", $srchFor));
                                                        ?>" onkeyup="enterKeyFuncAllSectorMinor(event, '', '#allSectorMinorDetailInfo', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&subPgNo=<?php echo $subPgNo; ?>&sbmtdSectorMajorID=<?php echo $sbmtdSectorMajorID; ?>');">
                                                        <input id="allSectorMinorPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getAllSectorMinor('clear', '#allSectorMinorDetailInfo', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&subPgNo=<?php echo $subPgNo; ?>&sbmtdSectorMajorID=<?php echo $sbmtdSectorMajorID; ?>');">
                                                            <span class="glyphicon glyphicon-remove"></span>
                                                        </label>
                                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getAllSectorMinor('', '#allSectorMinorDetailInfo', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&subPgNo=<?php echo $subPgNo; ?>&sbmtdSectorMajorID=<?php echo $sbmtdSectorMajorID; ?>');">
                                                            <span class="glyphicon glyphicon-search"></span>
                                                        </label> 
                                                    </div>
                                                </div>
                                                <div class="<?php echo $colClassType2; ?>" style="padding:0px 3px 0px 3px !important;">
                                                    <div class="input-group">
                                                        <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                                        <!--<select data-placeholder="Select..." class="form-control chosen-select" id="allSectorMinorSrchIn">
                                                        <?php
                                                        $valslctdArry = array("");
                                                        $srchInsArrys = array("Name");

                                                        for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                            if ($srchIn == $srchInsArrys[$z]) {
                                                                $valslctdArry[$z] = "selected";
                                                            }
                                                            ?>
                                                                                                                    <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                                        <?php } ?>
                                                        </select>-->
                                                        <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                                        <select data-placeholder="Select..." class="form-control chosen-select" id="allSectorMinorDsplySze" style="min-width:70px !important;">                            
                                                            <?php
                                                            $valslctdArry = array("", "", "", "", "", "", "", "");
                                                            $dsplySzeArry = array(1, 5, 10, 15, 30, 50, 100, 500, 1000);
                                                            for ($y = 0; $y < count($dsplySzeArry); $y++) {
                                                                if ($lmtSze == $dsplySzeArry[$y]) {
                                                                    $valslctdArry[$y] = "selected";
                                                                } else {
                                                                    $valslctdArry[$y] = "";
                                                                }
                                                                ?>
                                                                <option value="<?php echo $dsplySzeArry[$y]; ?>" <?php echo $valslctdArry[$y]; ?>><?php echo $dsplySzeArry[$y]; ?></option>                            
                                                                <?php
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="<?php echo $colClassType2; ?>" style="padding:0px 3px 0px 3px !important;">
                                                    <nav aria-label="Page navigation">
                                                        <ul class="pagination" style="margin: 0px !important;">
                                                            <li>
                                                                <a class="rhopagination" href="javascript:getAllSectorMinor('previous', '#allSectorMinorDetailInfo', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&subPgNo=<?php echo $subPgNo; ?>&sbmtdSectorMajorID=<?php echo $sbmtdSectorMajorID; ?>');" aria-label="Previous">
                                                                    <span aria-hidden="true">&laquo;</span>
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a class="rhopagination" href="javascript:getAllSectorMinor('next', '#allSectorMinorDetailInfo', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&subPgNo=<?php echo $subPgNo; ?>&sbmtdSectorMajorID=<?php echo $sbmtdSectorMajorID; ?>');" aria-label="Next">
                                                                    <span aria-hidden="true">&raquo;</span>
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </nav>
                                                    <input type="hidden" class="form-control" aria-label="..." id="gnrlOrgID" name="gnrlOrgID" value="<?php echo $orgID; ?>">                                                    
                                                    <input type="hidden" class="form-control" aria-label="..." id="sbmtdSectorMajorID" name="sbmtdSectorMajorID" value="<?php echo $sbmtdSectorMajorID; ?>">
                                                </div>
                                            </div>
                                            <div class="row" style="padding:0px 15px 0px 15px !important">                  
                                                <div class="col-md-12" style="padding:0px 3px 0px 3px !important">
                                                    <table class="table table-striped table-bordered table-responsive" id="allSectorMinorTable" cellspacing="0" width="100%" style="width:100%;min-width: 300px !important;">
                                                        <thead>
                                                            <tr>
                                                                <th>No.</th>
                                                                <th>Minor Sector</th>
                                                                <th>Description</th>
                                                                <th>Is Enabled?</th>
                                                                <th>&nbsp;</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $cntr = 0;
                                                            while ($row2 = loc_db_fetch_array($result2)) {                                                                
                                                                $cntr += 1;
                                                                $ttlOptnsMinorSectorNm = 0;
                                                                ?>
                                                                <tr id="allSectorMinorRow_<?php echo $cntr; ?>">                                    
                                                                    <td class="lovtd"><span><?php echo ($curIdx * $lmtSze) + ($cntr); ?></span></td>                                            
                                                                    <td class="lovtd"> 
                                                                        <input type="text" min="0" class="form-control rqrdFld" aria-label="..." id="allSectorMinorRow<?php echo $cntr; ?>_MinorSectorNm" name="allSectorMinorRow<?php echo $cntr; ?>_MinorSectorNm" value="<?php echo $row2[2]; ?>">
                                                                    </td>                                            
                                                                    <td class="lovtd">  
                                                                        <input type="text" min="0" class="form-control rqrdFld" aria-label="..." id="allSectorMinorRow<?php echo $cntr; ?>_MinorSectorDesc" name="allSectorMinorRow<?php echo $cntr; ?>_MinorSectorDesc" value="<?php echo $row2[3]; ?>">                                                               
                                                                    </td>
                                                                    <td class="lovtd">
                                                                        <input type="hidden" class="form-control" aria-label="..." id="allSectorMinorRow<?php echo $cntr; ?>_MinorSectorID" value="<?php echo $row2[0]; ?>" style="width:100% !important;"> 
                                                                        <input type="hidden" class="form-control" aria-label="..." id="allSectorMinorRow<?php echo $cntr; ?>_SectorMajorID" value="<?php echo $row2[1]; ?>" style="width:100% !important;">                                                                         
                                                                        <input type="hidden" class="form-control" aria-label="..." id="allSectorMinorRow<?php echo $cntr; ?>_isEnabledID" value="<?php echo $row2[4]; ?>" readonly="true">  
                                                                        <select class="form-control" id="allSectorMinorRow<?php echo $cntr; ?>_isEnabledNm" name="allSectorMinorRow<?php echo $cntr; ?>__isEnabledNm" >
                                                                            <?php
                                                                            $sltdYes = "";
                                                                            $sltdNo = "";
                                                                            if ($row2[4] == "Yes") {
                                                                                $sltdYes = "selected";
                                                                            } else if ($row2[4] == "No") {
                                                                                $sltdNo = "selected";
                                                                            }
                                                                            ?>
                                                                            <option value="Yes" <?php echo $sltdYes; ?>>Yes</option>
                                                                            <option value="No" <?php echo $sltdNo; ?>>No</option>
                                                                        </select>
                                                                    </td> 
                                                                    <td class="lovtd">
                                                                        <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delSectorMinor('allSectorMinorRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Major Sector">
                                                                            <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                                        </button>
                                                                    </td>
                                                                </tr>
                                                                <?php
                                                            }
                                                            ?>
                                                        </tbody>
                                                    </table>                        
                                                </div>                
                                            </div>
                                            <?php
                                        } else {
                                            ?>
                                            <span>No Results Found</span>
                                            <?php
                                        }
                                        ?> 
                                    </div>                                   
                                </div>
                            </fieldset>
                        </div>
                    </div>
                <!--</fieldset>-->
            </form>
            <?php
        } 
        else if ($subPgNo == 4.81) {
            $sbmtdSectorMajorID = isset($_POST['sbmtdSectorMajorID']) ? cleanInputData($_POST['sbmtdSectorMajorID']) : -1;                    
            ?>
            <div class="row" id="allSectorMajorsHdrInfo" style="padding:0px 15px 0px 15px !important">
                <?php
                $oneSectorMajorDetID = -1;
                $oneSectorMajorDetName = "";
                $oneSectorMajorDetDesc = "";
                $oneSectorMajorDetIsEnbld = "Yes";
                $result1 = getSectorMajorDets($sbmtdSectorMajorID);
                while ($row1 = loc_db_fetch_array($result1)) {
                    $oneSectorMajorDetID = $row1[0];
                    $oneSectorMajorDetName = $row1[1];
                    $oneSectorMajorDetDesc = $row1[2];
                    $oneSectorMajorDetIsEnbld = $row1[3];
                } 
                ?>                            
                    <fieldset class="basic_person_fs" style="padding:10px 3px 0px 3px !important;border:none !important;border-bottom:1px solid #ddd !important;border-radius: 0px !important;">                           
                        <div  class="col-md-6" style="padding:0px 3px 0px 3px !important;">
                            <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                <label for="oneSectorMajorDetName" class="control-label col-lg-4" style="padding:0px 15px 0px 1px !important;">Major Sector:</label>
                                <div  class="col-lg-8" style="padding:0px 15px 0px 1px !important;">
                                    <?php if ($canEdtSectorMajor === true) { ?>
                                        <input type="text" class="form-control rqrdFld" aria-label="..." id="oneSectorMajorDetName" name="oneSectorMajorDetName" value="<?php echo $oneSectorMajorDetName; ?>" style="width:100%;">
                                        <input type="hidden" class="form-control" aria-label="..." id="oneSectorMajorDetID" name="oneSectorMajorDetID" value="<?php echo $oneSectorMajorDetID; ?>">
                                    <?php } else {
                                        ?>
                                        <span><?php echo $oneSectorMajorDetName; ?></span>
                                        <?php
                                    }
                                    ?>
                                </div>
                            </div>
                            <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                <label for="oneSectorMajorDetDesc" class="control-label col-lg-4" style="padding:0px 15px 0px 1px !important;">Description:</label>
                                <div  class="col-lg-8" style="padding:0px 15px 0px 1px !important;">
                                    <?php if ($canEdtSectorMajor === true) { ?>
                                        <textarea class="form-control" aria-label="..." id="oneSectorMajorDetDesc" name="oneSectorMajorDetDesc" style="width:100%;" cols="5" rows="3"><?php echo $oneSectorMajorDetDesc; ?></textarea>
                                    <?php } else {
                                        ?>
                                        <span><?php echo $oneSectorMajorDetDesc; ?></span>
                                        <?php
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div  class="col-md-6" style="padding:0px 3px 0px 3px !important;"> 
                            <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                <label for="oneSectorMajorDetIsEnbld" class="control-label col-lg-4" style="padding:0px 15px 0px 1px !important;">Is Enabled:</label>
                                <div  class="col-lg-8" style="padding:0px 15px 0px 1px !important;">
                                    <?php if ($canEdtSectorMajor === true) { ?>
                                        <select class="form-control" id="oneSectorMajorDetIsEnbld" >
                                            <?php
                                            $sltdYes = "";
                                            $sltdNo = "";
                                            if ($oneSectorMajorDetIsEnbld == "Yes") {
                                                $sltdYes = "selected";
                                            } else if ($oneSectorMajorDetIsEnbld == "No") {
                                                $sltdNo = "selected";
                                            }
                                            ?>
                                            <option value="Yes" <?php echo $sltdYes; ?>>Yes</option>
                                            <option value="No" <?php echo $sltdNo; ?>>No</option>
                                        </select>                                   
                                    <?php } else {
                                        ?>
                                        <span><?php echo $oneSectorMajorDetIsEnbld; ?></span>
                                        <?php
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </fieldset>
            </div>
            <div class="row" id="allSectorMinorDetailInfo" style="padding:0px 15px 0px 15px !important">
                <?php
                /* &vtyp=<?php echo $vwtyp; ?> */
                $srchFor = "%";
                $srchIn = "Risk Factor";
                $pageNo = 1;
                $lmtSze = 10;
                $vwtyp = 1;
                if ($sbmtdSectorMajorID > 0) {
                    $total = get_AllSectorMinorTtl($srchFor, $srchIn, $sbmtdSectorMajorID);
                    //$total = get_AllBanksTtl($srchFor, $srchIn, $sbmtdSectorMajorID);
                    if ($pageNo > ceil($total / $lmtSze)) {
                        $pageNo = 1;
                    } else if ($pageNo < 1) {
                        $pageNo = ceil($total / $lmtSze);
                    }
                    $curIdx = $pageNo - 1;
                    $result2 = get_AllSectorMinor($srchFor, $srchIn, $curIdx, $lmtSze, $sbmtdSectorMajorID);
                    ?>
                    <div class="row" style="padding:0px 15px 0px 15px !important">
                        <legend class="basic_person_lg1" style="color: #003245">MINOR SECTORS</legend>
                        <?php
                        if ($canEdtSectorMajor === true) {
                            $colClassType1 = "col-lg-2";
                            $colClassType2 = "col-lg-3";
                            $colClassType3 = "col-lg-4";
                            $nwRowHtml = urlencode("<tr id=\"allSectorMinorRow__WWW123WWW\">"
                                            . "<td class=\"lovtd\"><span class=\"normaltd\">New</span></td>"
                                            . "<td class=\"lovtd\">
                                                        <input type=\"text\" min=\"0\" class=\"form-control rqrdFld\" aria-label=\"...\" id=\"allSectorMinorRow_WWW123WWW_MinorSectorNm\" name=\"allSectorMinorRow_WWW123WWW_MinorSectorNm\" value=\"\">                                                               
                                                </td>
                                                <td class=\"lovtd\">
                                                        <input type=\"text\" min=\"0\" class=\"form-control rqrdFld\" aria-label=\"...\" id=\"allSectorMinorRow_WWW123WWW_MinorSectorDesc\" name=\"allSectorMinorRow_WWW123WWW_MinorSectorDesc\" value=\"\">                                                               
                                                </td>
						<td class=\"lovtd\">
                                                    <select class=\"form-control\" id=\"allSectorMinorRow_WWW123WWW_isEnabledNm\" name=\"allSectorMinorRow_WWW123WWW_isEnabledNm\" >
                                                        <option value=\"Yes\" selected>Yes</option>
                                                        <option value=\"No\" >No</option>
                                                    </select>
                                                    <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"allSectorMinorRow_WWW123WWW_MinorSectorID\" value=\"-1\" style=\"width:100% !important;\">                                                                                                                          
                                                </td>                                             
                                                                                                
                                                <td class=\"lovtd\">
                                                    <button type=\"button\" class=\"btn btn-default\" style=\"margin: 0px !important;padding:0px 3px 2px 4px !important;\" onclick=\"delSectorMinor('allSectorMinorRow__WWW123WWW');\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Delete Minor Sector\">
                                                            <img src=\"cmn_images/no.png\" style=\"height:15px; width:auto; position: relative; vertical-align: middle;\">
                                                    </button>
                                                </td>
                            </tr>");
                            ?>
                            <div class="<?php echo $colClassType1; ?>" style="padding:0px 1px 0px 3px !important;">     
                                <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="insertNewRowBe4('allSectorMinorTable', 0, '<?php echo $nwRowHtml; ?>');" data-toggle="tooltip" data-placement="bottom" title="Add Minor Sector">
                                    <img src="cmn_images/add1-64.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                </button>
                                <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="saveSectorMinor();" data-toggle="tooltip" data-placement="bottom" title="Save Minor Sector">
                                    <img src="cmn_images/FloppyDisk.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                </button>
                            </div>
                            <?php
                        } else {
                            $colClassType1 = "col-lg-4";
                            $colClassType2 = "col-lg-4";
                            $colClassType3 = "col-lg-4";
                        }
                        ?>
                        <div class="<?php echo $colClassType3; ?>" style="padding:0px 3px 0px 3px !important;">
                            <div class="input-group">
                                <input class="form-control" id="allSectorMinorSrchFor" type = "text" placeholder="Search For" value="<?php
                                echo trim(str_replace("%", " ", $srchFor));
                                ?>" onkeyup="enterKeyFuncAllSectorMinor(event, '', '#allSectorMinorDetailInfo', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&subPgNo=<?php echo $subPgNo; ?>&sbmtdSectorMajorID=<?php echo $sbmtdSectorMajorID; ?>');">
                                <input id="allSectorMinorPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                <label class="btn btn-primary btn-file input-group-addon" onclick="getAllSectorMinor('clear', '#allSectorMinorDetailInfo', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&subPgNo=<?php echo $subPgNo; ?>&sbmtdSectorMajorID=<?php echo $sbmtdSectorMajorID; ?>');">
                                    <span class="glyphicon glyphicon-remove"></span>
                                </label>
                                <label class="btn btn-primary btn-file input-group-addon" onclick="getAllSectorMinor('', '#allSectorMinorDetailInfo', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&subPgNo=<?php echo $subPgNo; ?>&sbmtdSectorMajorID=<?php echo $sbmtdSectorMajorID; ?>');">
                                    <span class="glyphicon glyphicon-search"></span>
                                </label> 
                            </div>
                        </div>
                        <div class="<?php echo $colClassType2; ?>" style="padding:0px 3px 0px 3px !important;">
                            <div class="input-group">
                                <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                <!--<select data-placeholder="Select..." class="form-control chosen-select" id="allSectorMinorSrchIn">
                                <?php
                                $valslctdArry = array("");
                                $srchInsArrys = array("Name");

                                for ($z = 0; $z < count($srchInsArrys); $z++) {
                                    if ($srchIn == $srchInsArrys[$z]) {
                                        $valslctdArry[$z] = "selected";
                                    }
                                    ?>
                                                                                            <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                <?php } ?>
                                </select>-->
                                <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                <select data-placeholder="Select..." class="form-control chosen-select" id="allSectorMinorDsplySze" style="min-width:70px !important;">                            
                                    <?php
                                    $valslctdArry = array("", "", "", "", "", "", "", "");
                                    $dsplySzeArry = array(1, 5, 10, 15, 30, 50, 100, 500, 1000);
                                    for ($y = 0; $y < count($dsplySzeArry); $y++) {
                                        if ($lmtSze == $dsplySzeArry[$y]) {
                                            $valslctdArry[$y] = "selected";
                                        } else {
                                            $valslctdArry[$y] = "";
                                        }
                                        ?>
                                        <option value="<?php echo $dsplySzeArry[$y]; ?>" <?php echo $valslctdArry[$y]; ?>><?php echo $dsplySzeArry[$y]; ?></option>                            
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="<?php echo $colClassType2; ?>" style="padding:0px 3px 0px 3px !important;">
                            <nav aria-label="Page navigation">
                                <ul class="pagination" style="margin: 0px !important;">
                                    <li>
                                        <a class="rhopagination" href="javascript:getAllSectorMinor('previous', '#allSectorMinorDetailInfo', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&subPgNo=<?php echo $subPgNo; ?>&sbmtdSectorMajorID=<?php echo $sbmtdSectorMajorID; ?>');" aria-label="Previous">
                                            <span aria-hidden="true">&laquo;</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="rhopagination" href="javascript:getAllSectorMinor('next', '#allSectorMinorDetailInfo', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&subPgNo=<?php echo $subPgNo; ?>&sbmtdSectorMajorID=<?php echo $sbmtdSectorMajorID; ?>');" aria-label="Next">
                                            <span aria-hidden="true">&raquo;</span>
                                        </a>
                                    </li>
                                </ul>
                            </nav>
                            <input type="hidden" class="form-control" aria-label="..." id="gnrlOrgID" name="gnrlOrgID" value="<?php echo $orgID; ?>">                                                    
                            <input type="hidden" class="form-control" aria-label="..." id="sbmtdSectorMajorID" name="sbmtdSectorMajorID" value="<?php echo $sbmtdSectorMajorID; ?>">
                        </div>
                    </div>
                    <div class="row" style="padding:0px 15px 0px 15px !important">                  
                        <div class="col-md-12" style="padding:0px 3px 0px 3px !important">
                            <table class="table table-striped table-bordered table-responsive" id="allSectorMinorTable" cellspacing="0" width="100%" style="width:100%;min-width: 300px !important;">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Minor Sector</th>
                                        <th>Description</th>
                                        <th>Is Enabled?</th>
                                        <th>&nbsp;</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $cntr = 0;
                                    while ($row2 = loc_db_fetch_array($result2)) {
                                        $cntr += 1;
                                        $ttlOptnsMinorSectorNm = 0;
                                        ?>
                                        <tr id="allSectorMinorRow_<?php echo $cntr; ?>">                                    
                                            <td class="lovtd"><span><?php echo ($curIdx * $lmtSze) + ($cntr); ?></span></td>                                           
                                            <td class="lovtd"> 
                                                <input type="text" min="0" class="form-control rqrdFld" aria-label="..." id="allSectorMinorRow<?php echo $cntr; ?>_MinorSectorNm" name="allSectorMinorRow<?php echo $cntr; ?>_MinorSectorNm" value="<?php echo $row2[2]; ?>">
                                            </td>                                            
                                            <td class="lovtd">  
                                                <input type="text" min="0" class="form-control rqrdFld" aria-label="..." id="allSectorMinorRow<?php echo $cntr; ?>_MinorSectorDesc" name="allSectorMinorRow<?php echo $cntr; ?>_MinorSectorDesc" value="<?php echo $row2[3]; ?>">                                                               
                                            </td>
					    <td class="lovtd">
                                                <input type="hidden" class="form-control" aria-label="..." id="allSectorMinorRow<?php echo $cntr; ?>_MinorSectorID" value="<?php echo $row2[0]; ?>" style="width:100% !important;"> 
                                                <input type="hidden" class="form-control" aria-label="..." id="allSectorMinorRow<?php echo $cntr; ?>_SectorMajorID" value="<?php echo $row2[1]; ?>" style="width:100% !important;">                                                                         
                                                <input type="hidden" class="form-control" aria-label="..." id="allSectorMinorRow<?php echo $cntr; ?>_isEnabledID" value="<?php echo $row2[4]; ?>" readonly="true"> 
                                                <select class="form-control" id="allSectorMinorRow<?php echo $cntr; ?>_isEnabledNm" name="allSectorMinorRow<?php echo $cntr; ?>__isEnabledNm" >
                                                    <?php
                                                    $sltdYes = "";
                                                    $sltdNo = "";
                                                    if ($row2[4] == "Yes") {
                                                        $sltdYes = "selected";
                                                    } else if ($row2[4] == "No") {
                                                        $sltdNo = "selected";
                                                    }
                                                    ?>
                                                    <option value="Yes" <?php echo $sltdYes; ?>>Yes</option>
                                                    <option value="No" <?php echo $sltdNo; ?>>No</option>
                                                </select>
                                            </td>
											<td class="lovtd">
                                                <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delSectorMinor('allSectorMinorRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Major Sector">
                                                    <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                </button>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                </tbody>
                            </table>                        
                        </div>                
                    </div>
                    <?php
                } else {
                    ?>
                    <span>No Results Found</span>
                    <?php
                }
                ?> 
            </div>                     
            <?php
        } 
        else if ($subPgNo == 4.82) {
            $sbmtdSectorMajorID = isset($_POST['sbmtdSectorMajorID']) ? cleanInputData($_POST['sbmtdSectorMajorID']) : -1;                    
            if ($sbmtdSectorMajorID > 0) {
                $total = get_AllSectorMinorTtl($srchFor, $srchIn, $sbmtdSectorMajorID);
                //$total = get_AllBanksTtl($srchFor, $srchIn, $sbmtdSectorMajorID);
                if ($pageNo > ceil($total / $lmtSze)) {
                    $pageNo = 1;
                } else if ($pageNo < 1) {
                    $pageNo = ceil($total / $lmtSze);
                }
                $curIdx = $pageNo - 1;
                $result2 = get_AllSectorMinor($srchFor, $srchIn, $curIdx, $lmtSze, $sbmtdSectorMajorID);
                ?>
                <div class="row" style="padding:0px 15px 0px 15px !important">
                    <legend class="basic_person_lg1" style="color: #003245">RISK PROFILE FACTORS</legend>
                    <?php
                    if ($canEdtSectorMajor === true) {
                        $colClassType1 = "col-lg-2";
                        $colClassType2 = "col-lg-3";
                        $colClassType3 = "col-lg-4";
                        $nwRowHtml = urlencode("<tr id=\"allSectorMinorRow__WWW123WWW\">"
                                        . "<td class=\"lovtd\"><span class=\"normaltd\">New</span></td>"
                                        . "<td class=\"lovtd\">
                                                    <input type=\"text\" min=\"0\" class=\"form-control rqrdFld\" aria-label=\"...\" id=\"allSectorMinorRow_WWW123WWW_MinorSectorNm\" name=\"allSectorMinorRow_WWW123WWW_MinorSectorNm\" value=\"\">                                                               
                                            </td>
                                            <td class=\"lovtd\">
                                                    <input type=\"text\" min=\"0\" class=\"form-control rqrdFld\" aria-label=\"...\" id=\"allSectorMinorRow_WWW123WWW_MinorSectorDesc\" name=\"allSectorMinorRow_WWW123WWW_MinorSectorDesc\" value=\"\">                                                               
                                            </td>
					    <td class=\"lovtd\">
                                                <select class=\"form-control\" id=\"allSectorMinorRow_WWW123WWW_isEnabledNm\" name=\"allSectorMinorRow_WWW123WWW_isEnabledNm\" >
                                                    <option value=\"Yes\" selected>Yes</option>
                                                    <option value=\"No\" >No</option>
                                                </select>
                                                <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"allSectorMinorRow_WWW123WWW_MinorSectorID\" value=\"-1\" style=\"width:100% !important;\">                                                                                                                          
                                            </td> 
                                            <td class=\"lovtd\">
                                                <button type=\"button\" class=\"btn btn-default\" style=\"margin: 0px !important;padding:0px 3px 2px 4px !important;\" onclick=\"delSectorMinor('allSectorMinorRow__WWW123WWW');\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Delete Profile Factor\">
                                                        <img src=\"cmn_images/no.png\" style=\"height:15px; width:auto; position: relative; vertical-align: middle;\">
                                                </button>
                                            </td>
                        </tr>");                                                
                        ?> 
                        <div class="<?php echo $colClassType1; ?>" style="padding:0px 1px 0px 3px !important;">     
                            <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="insertNewRowBe4('allSectorMinorTable', 0, '<?php echo $nwRowHtml; ?>');" data-toggle="tooltip" data-placement="bottom" title="Add Profile Factor">
                                <img src="cmn_images/add1-64.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                            </button>
                            <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="saveSectorMinor();" data-toggle="tooltip" data-placement="bottom" title="Save Profile Factor">
                                <img src="cmn_images/FloppyDisk.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                            </button>
                        </div>
                        <?php
                    } else {
                        $colClassType1 = "col-lg-4";
                        $colClassType2 = "col-lg-4";
                        $colClassType3 = "col-lg-4";
                    }
                    ?>
                    <div class="<?php echo $colClassType3; ?>" style="padding:0px 3px 0px 3px !important;">
                        <div class="input-group">
                            <input class="form-control" id="allSectorMinorSrchFor" type = "text" placeholder="Search For" value="<?php
                            echo trim(str_replace("%", " ", $srchFor));
                            ?>" onkeyup="enterKeyFuncAllSectorMinor(event, '', '#allSectorMinorDetailInfo', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&subPgNo=<?php echo $subPgNo; ?>&sbmtdSectorMajorID=<?php echo $sbmtdSectorMajorID; ?>');">
                            <input id="allSectorMinorPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                            <label class="btn btn-primary btn-file input-group-addon" onclick="getAllSectorMinor('clear', '#allSectorMinorDetailInfo', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&subPgNo=<?php echo $subPgNo; ?>&sbmtdSectorMajorID=<?php echo $sbmtdSectorMajorID; ?>');">
                                <span class="glyphicon glyphicon-remove"></span>
                            </label>
                            <label class="btn btn-primary btn-file input-group-addon" onclick="getAllSectorMinor('', '#allSectorMinorDetailInfo', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&subPgNo=<?php echo $subPgNo; ?>&sbmtdSectorMajorID=<?php echo $sbmtdSectorMajorID; ?>');">
                                <span class="glyphicon glyphicon-search"></span>
                            </label> 
                        </div>
                    </div>
                    <div class="<?php echo $colClassType2; ?>" style="padding:0px 3px 0px 3px !important;">
                        <div class="input-group">
                            <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                            <!--<select data-placeholder="Select..." class="form-control chosen-select" id="allSectorMinorSrchIn">
                            <?php
                            $valslctdArry = array("");
                            $srchInsArrys = array("Name");

                            for ($z = 0; $z < count($srchInsArrys); $z++) {
                                if ($srchIn == $srchInsArrys[$z]) {
                                    $valslctdArry[$z] = "selected";
                                }
                                ?>
                                                                                        <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                            <?php } ?>
                            </select>-->
                            <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                            <select data-placeholder="Select..." class="form-control chosen-select" id="allSectorMinorDsplySze" style="min-width:70px !important;">                            
                                <?php
                                $valslctdArry = array("", "", "", "", "", "", "", "");
                                $dsplySzeArry = array(1, 5, 10, 15, 30, 50, 100, 500, 1000);
                                for ($y = 0; $y < count($dsplySzeArry); $y++) {
                                    if ($lmtSze == $dsplySzeArry[$y]) {
                                        $valslctdArry[$y] = "selected";
                                    } else {
                                        $valslctdArry[$y] = "";
                                    }
                                    ?>
                                    <option value="<?php echo $dsplySzeArry[$y]; ?>" <?php echo $valslctdArry[$y]; ?>><?php echo $dsplySzeArry[$y]; ?></option>                            
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="<?php echo $colClassType2; ?>" style="padding:0px 3px 0px 3px !important;">
                        <nav aria-label="Page navigation">
                            <ul class="pagination" style="margin: 0px !important;">
                                <li>
                                    <a class="rhopagination" href="javascript:getAllSectorMinor('previous', '#allSectorMinorDetailInfo', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&subPgNo=<?php echo $subPgNo; ?>&sbmtdSectorMajorID=<?php echo $sbmtdSectorMajorID; ?>');" aria-label="Previous">
                                        <span aria-hidden="true">&laquo;</span>
                                    </a>
                                </li>
                                <li>
                                    <a class="rhopagination" href="javascript:getAllSectorMinor('next', '#allSectorMinorDetailInfo', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&subPgNo=<?php echo $subPgNo; ?>&sbmtdSectorMajorID=<?php echo $sbmtdSectorMajorID; ?>');" aria-label="Next">
                                        <span aria-hidden="true">&raquo;</span>
                                    </a>
                                </li>
                            </ul>
                        </nav>
			<input type="hidden" class="form-control" aria-label="..." id="gnrlOrgID" name="gnrlOrgID" value="<?php echo $orgID; ?>">                         
                        <input type="hidden" class="form-control" aria-label="..." id="sbmtdSectorMajorID" name="sbmtdSectorMajorID" value="<?php echo $sbmtdSectorMajorID; ?>">
                    </div>
                </div>
                <div class="row" style="padding:0px 15px 0px 15px !important">                  
                    <div class="col-md-12" style="padding:0px 3px 0px 3px !important">
                        <table class="table table-striped table-bordered table-responsive" id="allSectorMinorTable" cellspacing="0" width="100%" style="width:100%;min-width: 300px !important;">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Minor Sector</th>
                                    <th>Description</th>
                                    <th>Is Enabled?</th>
                                    <th>&nbsp;</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $cntr = 0;
                                while ($row2 = loc_db_fetch_array($result2)) {
                                    $cntr += 1;
                                    $ttlOptnsMinorSectorNm = 0;
                                    ?>
                                    <tr id="allSectorMinorRow_<?php echo $cntr; ?>">                                    
                                        <td class="lovtd"><span><?php echo ($curIdx * $lmtSze) + ($cntr); ?></span></td>                                        
                                        <td class="lovtd"> 
                                            <input type="text" min="0" class="form-control rqrdFld" aria-label="..." id="allSectorMinorRow<?php echo $cntr; ?>_MinorSectorNm" name="allSectorMinorRow<?php echo $cntr; ?>_MinorSectorNm" value="<?php echo $row2[2]; ?>">
                                        </td>                                            
                                        <td class="lovtd">  
                                            <input type="text" min="0" class="form-control rqrdFld" aria-label="..." id="allSectorMinorRow<?php echo $cntr; ?>_MinorSectorDesc" name="allSectorMinorRow<?php echo $cntr; ?>_MinorSectorDesc" value="<?php echo $row2[3]; ?>">                                                               
                                        </td>
					<td class="lovtd">
                                            <input type="hidden" class="form-control" aria-label="..." id="allSectorMinorRow<?php echo $cntr; ?>_MinorSectorID" value="<?php echo $row2[0]; ?>" style="width:100% !important;"> 
                                            <input type="hidden" class="form-control" aria-label="..." id="allSectorMinorRow<?php echo $cntr; ?>_SectorMajorID" value="<?php echo $row2[1]; ?>" style="width:100% !important;">                                                                         
                                            <input type="hidden" class="form-control" aria-label="..." id="allSectorMinorRow<?php echo $cntr; ?>_isEnabledID" value="<?php echo $row2[4]; ?>" readonly="true">
                                            <select class="form-control" id="allSectorMinorRow<?php echo $cntr; ?>_isEnabledNm" name="allSectorMinorRow<?php echo $cntr; ?>__isEnabledNm" >
                                                <?php
                                                $sltdYes = "";
                                                $sltdNo = "";
                                                if ($row2[4] == "Yes") {
                                                    $sltdYes = "selected";
                                                } else if ($row2[4] == "No") {
                                                    $sltdNo = "selected";
                                                }
                                                ?>
                                                <option value="Yes" <?php echo $sltdYes; ?>>Yes</option>
                                                <option value="No" <?php echo $sltdNo; ?>>No</option>
                                            </select>
                                        </td>
                                        <td class="lovtd">
                                            <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delSectorMinor('allSectorMinorRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Major Sector">
                                                <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                            </button>
                                        </td>
                                    </tr>
                                    <?php
                                }
                                ?>
                            </tbody>
                        </table>                        
                    </div>                
                </div>
                <?php
            } else {
                ?>
                <span>No Results Found</span>
                <?php
            }
        }       
    }
    else if ($subPgNo == 4.9) {//LOAN WRITEOFF
        $canAuthorizeLoanWrtoff = test_prmssns($dfltPrvldgs[284], $mdlNm); 
        $canProcessLoanWrtoff = test_prmssns($dfltPrvldgs[285], $mdlNm);
        
        $trnsDte = getStartOfDayDMYHMS();
        $loanWriteOffTrnsDte = $trnsDte;
        $loanWriteOffCode = "";
        $loanWriteOffReason = "";
        $loanRqstID = -1;
        $loanRqst = "";
//        $bnkBranchID = -1;
//        $bnkBranch = -1;
        $loanWriteOffAmount = 0.00;
        $loanWriteOffCustNm = "";
        $loanWriteOffId = $pkID;
        $crncy = "GHS";
        $mkReadOnly = "";
        $mkReadOnlyDsbld = "";
        $trnsStatusDsply = "Incomplete, Unprocessed";
        $trnsStatus = "Incomplete";
        $rqstatusColor = "red";


        $result = getLoanWriteOffDets($pkID);
        while($row = loc_db_fetch_array($result)){
            $loanWriteOffCode = $row[1];
            $loanWriteOffTrnsDte = $row[2];
            $prsnBranchID = $row[3];
            $prsnBranch = getGnrlRecNm("org.org_sites_locations", "location_id", "site_desc||' ('||location_code_name||')'", $prsnBranchID);  
            $loanRqstID = $row[4];
	    $loanWriteOffCustNm = $row[5];
            $loanWriteOffReason = $row[6];
            $loanWriteOffAmount = number_format($row[7],2);           
            $crncy = $row[10];
            $lnAcctID = (int)getGnrlRecNm("mcf.mcf_loan_request", "loan_rqst_id", "account_id", $loanRqstID);
            $loanRqst = getGnrlRecNm("mcf.mcf_accounts", "account_id", "account_number||' ['||mcf.get_customer_name(cust_type,cust_id)||']'", $lnAcctID);
            $trnsStatus = $row[8];
            $trnsStatusDsply = $row[8].", ".$row[9];
            
            if(($trnsStatus == "Unauthorized") || $vwtypActn == "VIEW"){
                $mkReadOnly = "readonly=\"readonly\"";
                $mkReadOnlyDsbld = "disabled=\"true\"";
            }            
        }


        ?>
        <div class="col-md-12" style="padding:0px 0px 10px 1px !important;">
            <div style="padding:0px 1px 0px 15px !important;float:left;">
                    <button type="button" class="btn btn-default btn-sm" style="height:30px;" id="myVmsTrnsStatusBtn">
                            <input type="text" style="display:none !important;" class="form-control" id="mnlpymntSvngsHdrStatus" placeholder="Status" value="<?php echo $trnsStatusDsply; ?>"/>
                            <span style="font-weight:bold;"></span><span style="color:<?php echo $rqstatusColor; ?>;font-weight: bold;"><?php echo $trnsStatusDsply; ?></span>
                    </button>
                    <?php if($vwtypActn != "VIEW") { ?>
                    <button type="button" class="btn btn-default btn-sm" style="height:30px;" onclick="getLoanWriteOffForm('myFormsModalxLG', 'myFormsModalxBodyLG', 'myFormsModalxTitleLG', 'Edit Property Collateral', 15, <?php echo $subPgNo; ?>,0,'EDIT', <?php echo $pkID; ?>,'indCustTable','indCustTableRow1');" data-toggle="tooltip" title="Reload Transaction">
                            <img src="cmn_images/refresh.bmp" style="padding-right: 2px; height:17px; width:auto; position: relative; vertical-align: middle;">
                    </button>
                    <?php } ?>
                    <button type="button" class="btn btn-default" style="height:30px;" onclick="getOneMcfDocsForm_Gnrl(<?php echo $pkID; ?>,'LOAN WRITE-OFF', 140, 'Loan Write-Off Form');" data-toggle="tooltip" data-placement="bottom" title = "Attached Documents">
                        <img src="cmn_images/adjunto.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                    </button>                                                    
            </div>
            <div style="padding:0px 1px 0px 1px !important;float:right;">
                    <?php if (($trnsStatus == "Incomplete" || $trnsStatus == "Withdrawn" || $trnsStatus == "Rejected") && $vwtypActn != "VIEW") { ?>                                                    
                            <button type="button" class="btn btn-default btn-sm" style="height:30px;" onclick="saveLoanWriteOff(<?php echo $pgNo; ?>, <?php echo $subPgNo; ?>, <?php echo $vwtyp;?>);"><img src="cmn_images/FloppyDisk.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Save&nbsp;</button>
                            <button type="button" class="btn btn-default btn-sm" style="height:30px;" onclick="submitWithdrawLoanWriteOff(<?php echo $pgNo; ?>, <?php echo $subPgNo; ?>, 1, 'SUBMIT');"><img src="cmn_images/Emailcon.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Submit&nbsp;</button>   
                    <?php 
                    } else if ($trnsStatus == "Unauthorized" && $vwtypActn != "VIEW") {
                            ?>  
                            <button type="button" class="btn btn-default btn-sm" style="height:30px;" onclick="submitWithdrawLoanWriteOff(<?php echo $pgNo; ?>, <?php echo $subPgNo; ?>, 1, 'REJECT');"><img src="cmn_images/withdraw_rqst.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Reject&nbsp;</button>
                            <button type="button" class="btn btn-default btn-sm" style="height:30px;" onclick="submitWithdrawLoanWriteOff(<?php echo $pgNo; ?>, <?php echo $subPgNo; ?>, 1, 'WITHDRAW');"><img src="cmn_images/withdraw_rqst.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Withdraw&nbsp;</button>                                      
                            <button type="button" class="btn btn-default btn-sm" style="height:30px;" onclick="submitWithdrawLoanWriteOff(<?php echo $pgNo; ?>, <?php echo $subPgNo; ?>, 1, 'AUTHORIZE');"><img src="cmn_images/Stamp-512.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Authorize&nbsp;</button>                                                                                                        
                            <button type="button" class="btn btn-default btn-sm" style="height:30px;" onclick="checkWkfRqstStatus(<?php echo $routingID; ?>, 'Teller Trns. Authorization Progress History');"><img src="cmn_images/workflow.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">&nbsp;</button>                                
                                    <?php
                            } else if (($trnsStatus == "Authorized") && $vwtypActn != "VIEW") {
                                    ?>
                            <button type="button" class="btn btn-default btn-sm" style="height:30px;" onclick="checkWkfRqstStatus(<?php echo $routingID; ?>, 'Teller Trns. Authorization Progress History');"><img src="cmn_images/workflow.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;" data-toggle="tooltip" title="Authorization Progress History">&nbsp;</button>  
                            <button type="button" style="display:none !important;" class="btn btn-default btn-sm" style="height:30px;" onclick="submitWithdrawloanWriteOff(<?php echo $pgNo; ?>, <?php echo $subPgNo; ?>, 1, 'REVERSE');"><img src="cmn_images/90.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Reverse Approval&nbsp;</button>                               
                    <?php } ?>
            </div>
        </div>            
        <form class="form-horizontal" id="riskFactorsForm" style="padding:5px 20px 5px 20px;">             
            <div class="row">      
                <div class="form-group form-group-sm">
                    <input class="form-control" size="16" type="hidden" id="loanWriteOffId" value="<?php echo $loanWriteOffId; ?>" readonly=""> 
                    <label for="loanWriteOffCode" class="control-label col-md-4">Transaction ID:</label>
                    <div  class="col-md-8">
                        <input type="text" readonly="readonly" class="form-control" id="loanWriteOffCode" placeholder="Transaction ID" value="<?php echo $loanWriteOffCode; ?>"/>
                    </div>
                </div>
                <div class="form-group form-group-sm">
                    <label for="loanWriteOffTrnsDte" class="control-label col-md-4">Transaction Date:</label>
                    <div  class="col-md-8">
                        <input type="text" <?php echo $mkReadOnly; ?> class="form-control" id="loanWriteOffTrnsDte" placeholder="Transaction Date" value="<?php echo $loanWriteOffTrnsDte; ?>" readonly="readonly"/>
                    </div>
                </div> 
                <div class="form-group form-group-sm">
                    <label for="bnkBranch" class="control-label col-md-4">Branch:</label>
                    <div  class="col-md-8">
                        <div class="input-group" ><!--style="width:100% !important;">-->
                            <input type="text" class="form-control" aria-label="..." id="bnkBranch" value="<?php echo $prsnBranch; ?>" readonly>
                            <input type="hidden" id="bnkBranchID" value="<?php echo $prsnBranchID; ?>"> 
                            <input type="hidden" id="gnrlOrgID" value="<?php echo $orgID; ?>">
                            <label  class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Sites/Locations New', 'gnrlOrgID', '', '', 'radio', true, '', 'bnkBranchID', 'bnkBranch', 'clear', 1, '');">
                                <span class="glyphicon glyphicon-th-list"></span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group form-group-sm">
                    <label for="loanRqst" class="control-label col-md-4">Loan Request:</label>
                    <div  class="col-md-8">
                        <div class="input-group">
                            <input type="text" class="form-control" aria-label="..." id="loanRqst" value="<?php echo $loanRqst; ?>" readonly="readonly">
                            <input type="hidden" id="loanRqstID" value="<?php echo $loanRqstID; ?>">
                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'MCF Loan Canditates For Write-Off', 'gnrlOrgID','bnkBranchID', '', 'radio', true, '', 'loanRqstID', 'loanRqst', 'clear', 1, '');;">
                                <span class="glyphicon glyphicon-th-list"></span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group form-group-sm">
                    <label for="loanWriteOffCustNm" class="control-label col-md-4">Customer:</label>
                    <div  class="col-md-8">
                        <input type="text" readonly="readonly" class="form-control rqrdFld" id="loanWriteOffCustNm" placeholder="Customer Name" value="<?php echo $loanWriteOffCustNm; ?>"/>
                    </div>
                </div>                    
                <div class="form-group form-group-sm">
                    <label for="loanWriteOffReason" class="control-label col-md-4">Write-Off Reason:</label>
                    <div  class="col-md-8">
                        <textarea <?php echo $mkReadOnly; ?> class="form-control rqrdFld" id="loanWriteOffReason" cols="2" placeholder="Write-Off Reason" rows="4"><?php echo $loanWriteOffReason; ?></textarea>
                    </div>
                </div>
                <div class="form-group form-group-sm">
                    <label for="loanWriteOffAmount" class="control-label col-md-4">Amount:</label>
                    <div  class="col-md-8">
                        <div class="input-group">
                            <label class="btn btn-primary btn-file input-group-addon" onclick="">
                                <?php echo $crncy; ?>
                            </label>
                            <input type="text" class="form-control rqrdFld" aria-label="..." id="loanWriteOffAmount" value="<?php echo $loanWriteOffAmount; ?>" readonly="readonly">
                        </div>
                    </div>
                </div>
            </div>
                <?php if($trnsStatusDsply == "Authorized, Unprocessed") { ?>
            <div class="row" style="float:right;padding-right: 7px;">
                    <button type="button" class="btn btn-primary" onclick="processLoanWriteOff(0);" >Process Loan Write-Off</button>
                <?php } else if($trnsStatusDsply == "Authorized, Processed") { ?>
                    <button type="button" class="btn btn-primary" onclick="processLoanWriteOff(1);" >Void Loan Write-Off</button>
            </div> 
                <?php } ?>                   
        </form>
        <?php      
    }
    else if ($subPgNo == 4.11) {//PEO
        //var_dump($_POST);
        $canAddSectorMajor = test_prmssns($dfltPrvldgs[1], $mdlNm);
        $canEdtSectorMajor = test_prmssns($dfltPrvldgs[1], $mdlNm);
        $canDelSectorMajor = test_prmssns($dfltPrvldgs[1], $mdlNm);

        $error = "";
        $searchAll = true;
        $isEnabledOnly = false;
        if (isset($_POST['isEnabled'])) {
            $isEnabledOnly = cleanInputData($_POST['isEnabled']);
        }


        $srchFor = isset($_POST['searchfor']) ? cleanInputData($_POST['searchfor']) : '';
        $srchIn = isset($_POST['searchin']) ? cleanInputData($_POST['searchin']) : 'Both';
        $pageNo = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
        $lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 10;
        $sortBy = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : "Date Added DESC";
        if (strpos($srchFor, "%") === FALSE) {
            $srchFor = "%" . str_replace(" ", "%", $srchFor) . "%";
            $srchFor = str_replace("%%", "%", $srchFor);
        }

        if ($subPgNo == 4.11) {
            echo  $cntent .= " <li onclick=\"openATab('#allmodules', 'grp=17&typ=1&pg=4');\">
                                                <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
						<span style=\"text-decoration:none;\">Credit Management Menu</span>
                                        </li>
                        <li onclick=\"openATab('#allmodules', 'grp=17&typ=1&pg=4&subPgNo=4.11');\">
                                                                <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                                                <span style=\"text-decoration:none;\">Summary Dashboard</span>
                            </li></div>";            
            
            $total = getSectorMajorTblTtl($isEnabledOnly, $srchFor, $srchIn, $orgID, $searchAll);
            if ($pageNo > ceil($total / $lmtSze)) {
                $pageNo = 1;
            } else if ($pageNo < 1) {
                $pageNo = ceil($total / $lmtSze);
            }

            $curIdx = $pageNo - 1;
            $result = getSectorMajorTbl($isEnabledOnly, $srchFor, $srchIn, $curIdx, $lmtSze, $orgID, $searchAll, $sortBy);
            $cntr = 0;
            $colClassType1 = "col-lg-2";
            $colClassType2 = "col-lg-3";
            $colClassType3 = "col-lg-1";
            ?>
            <form id='allSectorMajorsForm' action='' method='post' accept-charset='UTF-8'>
                <!--<fieldset class="basic_person_fs5">-->
                    <legend class="basic_person_lg1" style="color: #003245">SUMMARY DASHBOARD</legend>                              
                    <div class="row" style="padding:0px 15px 10px 15px !important"> 
                        <div class="col-md-4" style="padding:0px 1px 0px 1px !important">
                            <fieldset class="basic_person_fs">                                        
                                <canvas id="lineChart"></canvas>
                            </fieldset>
                        </div>  
                        <div class="col-md-4" style="padding:0px 1px 0px 1px !important">
                            <fieldset class="basic_person_fs">                                        
                                <canvas id="barChart"></canvas>
                            </fieldset>
                        </div>                          
                        <div class="col-md-4" style="padding:0px 1px 0px 1px !important">
                            <fieldset class="basic_person_fs">                                        
                                <canvas id="pieChart" style="height:170px !important;"></canvas>
                            </fieldset>
                        </div>                          
                    </div>
                    <div class="row" style="padding:0px 15px 10px 15px !important"> 
                        <div class="col-md-4" style="padding:0px 1px 0px 1px !important">
                            <fieldset class="basic_person_fs">  <legend class="basic_person_lg1" style="margin-top:5px !important;margin-bottom: 10px !important;">Loan Classifications</legend>                                      
                                <table class="table table-striped table-bordered table-responsive" id="allSectorMajorsTable" cellspacing="0" width="100%" style="width:100%;"><caption style="display:none !important;">Loan Classifications</caption>
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Classification</th>
                                            <th>Total</th>                                   
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr id="allSectorMajorsRow_<?php echo $cntr; ?>" class="hand_cursor">  
                                            <td class="lovtd">1</td>
                                            <td class="lovtd">Standard</td>
                                            <td class="lovtd">1</td>
                                        </tr>
                                        <tr id="allSectorMajorsRow_<?php echo $cntr; ?>" class="hand_cursor"> 
                                            <td class="lovtd">2</td>
                                            <td class="lovtd">Special Mention</td>
                                            <td class="lovtd">10</td>
                                        </tr>
                                        <tr id="allSectorMajorsRow_<?php echo $cntr; ?>" class="hand_cursor">    
                                            <td class="lovtd">3</td>
                                            <td class="lovtd">Sub-Standard</td>
                                            <td class="lovtd">8</td>
                                        </tr>
                                        <tr id="allSectorMajorsRow_<?php echo $cntr; ?>" class="hand_cursor"> 
                                            <td class="lovtd">4</td>
                                            <td class="lovtd">Doubtful</td>
                                            <td class="lovtd">1</td>
                                        </tr>
                                        <tr id="allSectorMajorsRow_<?php echo $cntr; ?>" class="hand_cursor">
                                            <td class="lovtd">5</td>
                                            <td class="lovtd">Loss</td>
                                            <td class="lovtd">1</td>
                                        </tr>
                                    </tbody>
                                </table>                        
                            </fieldset>
                        </div> 
                        <div class="col-md-4" style="padding:0px 1px 0px 1px !important">
                            <fieldset class="basic_person_fs"> <legend class="basic_person_lg1" style="margin-top:5px !important;margin-bottom: 10px !important;">Loan Sectors</legend>                                       
                                <table class="table table-striped table-bordered table-responsive" id="allSectorMajorsTable" cellspacing="0" width="100%" style="width:100%;">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Major Sector</th>
                                            <th>Description</th>                                   
                                            <?php if ($canDelSectorMajor === true) { ?>
                                                <th>&nbsp;</th>
                                            <?php } ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $sbmtdSectorMajorID = -1;
                                        while ($row = loc_db_fetch_array($result)) {
                                            if ($sbmtdSectorMajorID <= 0 && $cntr <= 0) {
                                                $sbmtdSectorMajorID = $row[0];
                                            }
                                            $cntr += 1;
                                            ?>
                                            <tr id="allSectorMajorsRow_<?php echo $cntr; ?>" class="hand_cursor">                                    
                                                <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                                <td class="lovtd"><?php echo $row[1]; ?>
                                                    <input type="hidden" class="form-control" aria-label="..." id="allSectorMajorsRow<?php echo $cntr; ?>_SectorMajorID" value="<?php echo $row[0]; ?>">
                                                </td>
                                                <td class="lovtd">
                                                    <?php echo $row[2]; ?>
                                                </td>
                                                <?php if ($canDelSectorMajor === true) { ?>
                                                    <td class="lovtd">
                                                        <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="deleteSectorMajor(<?php echo $sbmtdSectorMajorID; ?>)" data-toggle="tooltip" data-placement="bottom" title="Delete Major Sector">
                                                            <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                        </button>
                                                    </td>
                                                <?php } ?>
                                            </tr>
                                            <?php
                                        }
                                        ?>
                                    </tbody>
                                </table>                        
                            </fieldset>
                        </div>                          
                        <div class="col-md-4" style="padding:0px 1px 0px 1px !important">
                            <fieldset class="basic_person_fs">  <legend class="basic_person_lg1" style="margin-top:5px !important;margin-bottom: 10px !important;">Loan Classifications</legend>                                      
                                <table class="table table-striped table-bordered table-responsive" id="allSectorMajorsTable" cellspacing="0" width="100%" style="width:100%;"><caption style="display:none !important;">Loan Classifications</caption>
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Classification</th>
                                            <th>Total</th>                                   
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr id="allSectorMajorsRow_<?php echo $cntr; ?>" class="hand_cursor">  
                                            <td class="lovtd">1</td>
                                            <td class="lovtd">Standard</td>
                                            <td class="lovtd">1</td>
                                        </tr>
                                        <tr id="allSectorMajorsRow_<?php echo $cntr; ?>" class="hand_cursor"> 
                                            <td class="lovtd">2</td>
                                            <td class="lovtd">Special Mention</td>
                                            <td class="lovtd">10</td>
                                        </tr>
                                        <tr id="allSectorMajorsRow_<?php echo $cntr; ?>" class="hand_cursor">    
                                            <td class="lovtd">3</td>
                                            <td class="lovtd">Sub-Standard</td>
                                            <td class="lovtd">8</td>
                                        </tr>
                                        <tr id="allSectorMajorsRow_<?php echo $cntr; ?>" class="hand_cursor"> 
                                            <td class="lovtd">4</td>
                                            <td class="lovtd">Doubtful</td>
                                            <td class="lovtd">1</td>
                                        </tr>
                                        <tr id="allSectorMajorsRow_<?php echo $cntr; ?>" class="hand_cursor">
                                            <td class="lovtd">5</td>
                                            <td class="lovtd">Loss</td>
                                            <td class="lovtd">1</td>
                                        </tr>
                                    </tbody>
                                </table>                        
                            </fieldset>
                        </div> 
                    </div>                    
                <!--</fieldset>-->
            </form>
            <?php
        }      
    } 
    else if ($subPgNo == 4.12) {//OVERDRAFT INTEREST
        $canAuthorizeOvdrftIntrst = test_prmssns($dfltPrvldgs[290], $mdlNm);  
        $canProcessOvdrftIntrst = test_prmssns($dfltPrvldgs[291], $mdlNm);
    
        $trnsDte = getStartOfDayDMYHMS();
        $ovdrftIntrstPymntTrnsDte = $trnsDte;
        $ovdrftIntrstPymntCode = "";
        $ovdrftIntrstPymntReason = "";
        $loanRqstID = -1;
        $loanRqst = "";
//        $bnkBranchID = -1;
//        $bnkBranch = -1;
        $ovdrftIntrstPymntAmount = 0.00;
        $ovdrftIntrstPymntCustNm = "";
        $ovdrftIntrstPymntId = $pkID;
        $crncy = "GHS";
        $mkReadOnly = "";
        $mkReadOnlyDsbld = "";
        $trnsStatusDsply = "Incomplete, Unprocessed";
        $trnsStatus = "Incomplete";
        $rqstatusColor = "red";


        $result = getOvdrftIntrstPymntDets($pkID);
        while($row = loc_db_fetch_array($result)){
            $ovdrftIntrstPymntCode = $row[1];
            $ovdrftIntrstPymntTrnsDte = $row[2];
            $prsnBranchID = $row[3];
            $prsnBranch = getGnrlRecNm("org.org_sites_locations", "location_id", "site_desc||' ('||location_code_name||')'", $prsnBranchID);  
            $loanRqstID = $row[4];
	    $ovdrftIntrstPymntCustNm = $row[5];
            $ovdrftIntrstPymntReason = $row[6];
            $ovdrftIntrstPymntAmount = number_format($row[7],2);           
            $crncy = $row[10];
            $loanRqst = getGnrlRecNm("mcf.mcf_loan_request", "loan_rqst_id", "trnsctn_id||' ['||mcf.get_customer_name(cust_type,cust_id)||']'", $loanRqstID);
            $trnsStatus = $row[8];
            $trnsStatusDsply = $row[8].", ".$row[9];
            
            if(($trnsStatus == "Unauthorized") || $vwtypActn == "VIEW"){
                $mkReadOnly = "readonly=\"readonly\"";
                $mkReadOnlyDsbld = "disabled=\"true\"";
            }            
        }


        ?>
        <div class="col-md-12" style="padding:0px 0px 10px 1px !important;">
            <div style="padding:0px 1px 0px 15px !important;float:left;">
                    <button type="button" class="btn btn-default btn-sm" style="height:30px;" id="myVmsTrnsStatusBtn">
                            <input type="text" style="display:none !important;" class="form-control" id="mnlpymntSvngsHdrStatus" placeholder="Status" value="<?php echo $trnsStatusDsply; ?>"/>
                            <span style="font-weight:bold;"></span><span style="color:<?php echo $rqstatusColor; ?>;font-weight: bold;"><?php echo $trnsStatusDsply; ?></span>
                    </button>
                    <?php if($vwtypActn != "VIEW") { ?>
                    <button type="button" class="btn btn-default btn-sm" style="height:30px;" onclick="getOvdrftIntrstPymntForm('myFormsModalxLG', 'myFormsModalxBodyLG', 'myFormsModalxTitleLG', 'Edit Overdraft Interes', 15, <?php echo $subPgNo; ?>,0,'EDIT', <?php echo $pkID; ?>,'indCustTable','indCustTableRow1');" data-toggle="tooltip" title="Reload Transaction">
                            <img src="cmn_images/refresh.bmp" style="padding-right: 2px; height:17px; width:auto; position: relative; vertical-align: middle;">
                    </button>
                    <?php } ?>
                    <button type="button" class="btn btn-default" style="height:30px;" onclick="getOneMcfDocsForm_Gnrl(<?php echo $pkID; ?>,'OVERDRAFT INTEREST PROCESSING', 140, 'Loan Write-Off Form');" data-toggle="tooltip" data-placement="bottom" title = "Attached Documents">
                        <img src="cmn_images/adjunto.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                    </button>                                                    
            </div>
            <div style="padding:0px 1px 0px 1px !important;float:right;">
                    <?php if (($trnsStatus == "Incomplete" || $trnsStatus == "Withdrawn" || $trnsStatus == "Rejected") && $vwtypActn != "VIEW") { ?>                                                    
                            <button type="button" class="btn btn-default btn-sm" style="height:30px;" onclick="saveOvdrftIntrstPymnt(<?php echo $pgNo; ?>, <?php echo $subPgNo; ?>, <?php echo $vwtyp;?>);"><img src="cmn_images/FloppyDisk.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Save&nbsp;</button>
                            <button type="button" class="btn btn-default btn-sm" style="height:30px;" onclick="submitWithdrawOvdrftIntrstPymnt(<?php echo $pgNo; ?>, <?php echo $subPgNo; ?>, 1, 'SUBMIT');"><img src="cmn_images/Emailcon.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Submit&nbsp;</button>   
                    <?php 
                    } else if ($trnsStatus == "Unauthorized" && $vwtypActn != "VIEW") {
                            ?>  
                            <button type="button" class="btn btn-default btn-sm" style="height:30px;" onclick="submitWithdrawOvdrftIntrstPymnt(<?php echo $pgNo; ?>, <?php echo $subPgNo; ?>, 1, 'REJECT');"><img src="cmn_images/withdraw_rqst.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Reject&nbsp;</button>
                            <button type="button" class="btn btn-default btn-sm" style="height:30px;" onclick="submitWithdrawOvdrftIntrstPymnt(<?php echo $pgNo; ?>, <?php echo $subPgNo; ?>, 1, 'WITHDRAW');"><img src="cmn_images/withdraw_rqst.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Withdraw&nbsp;</button>                                      
                            <button type="button" class="btn btn-default btn-sm" style="height:30px;" onclick="submitWithdrawOvdrftIntrstPymnt(<?php echo $pgNo; ?>, <?php echo $subPgNo; ?>, 1, 'AUTHORIZE');"><img src="cmn_images/Stamp-512.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Authorize&nbsp;</button>                                                                                                        
                            <button type="button" class="btn btn-default btn-sm" style="height:30px;" onclick="checkWkfRqstStatus(<?php echo $routingID; ?>, 'Teller Trns. Authorization Progress History');"><img src="cmn_images/workflow.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">&nbsp;</button>                                
                                    <?php
                            } else if (($trnsStatus == "Authorized") && $vwtypActn != "VIEW") {
                                    ?>
                            <button type="button" class="btn btn-default btn-sm" style="height:30px;" onclick="checkWkfRqstStatus(<?php echo $routingID; ?>, 'Teller Trns. Authorization Progress History');"><img src="cmn_images/workflow.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;" data-toggle="tooltip" title="Authorization Progress History">&nbsp;</button>  
                            <button type="button" style="display:none !important;" class="btn btn-default btn-sm" style="height:30px;" onclick="submitWithdrawovdrftIntrstPymnt(<?php echo $pgNo; ?>, <?php echo $subPgNo; ?>, 1, 'REVERSE');"><img src="cmn_images/90.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Reverse Approval&nbsp;</button>                               
                    <?php } ?>
            </div>
        </div>            
        <form class="form-horizontal" id="riskFactorsForm" style="padding:5px 20px 5px 20px;">             
            <div class="row">      
                <div class="form-group form-group-sm">
                    <input class="form-control" size="16" type="hidden" id="ovdrftIntrstPymntId" value="<?php echo $ovdrftIntrstPymntId; ?>" readonly=""> 
                    <label for="ovdrftIntrstPymntCode" class="control-label col-md-4">Transaction ID:</label>
                    <div  class="col-md-8">
                        <input type="text" readonly="readonly" class="form-control" id="ovdrftIntrstPymntCode" placeholder="Transaction ID" value="<?php echo $ovdrftIntrstPymntCode; ?>"/>
                    </div>
                </div>
                <div class="form-group form-group-sm">
                    <label for="ovdrftIntrstPymntTrnsDte" class="control-label col-md-4">Transaction Date:</label>
                    <div  class="col-md-8">
                        <input type="text" <?php echo $mkReadOnly; ?> class="form-control" id="ovdrftIntrstPymntTrnsDte" placeholder="Transaction Date" value="<?php echo $ovdrftIntrstPymntTrnsDte; ?>" readonly="readonly"/>
                    </div>
                </div> 
                <div class="form-group form-group-sm">
                    <label for="bnkBranch" class="control-label col-md-4">Branch:</label>
                    <div  class="col-md-8">
                        <div class="input-group" ><!--style="width:100% !important;">-->
                            <input type="text" class="form-control" aria-label="..." id="bnkBranch" value="<?php echo $prsnBranch; ?>" readonly>
                            <input type="hidden" id="bnkBranchID" value="<?php echo $prsnBranchID; ?>"> 
                            <input type="hidden" id="gnrlOrgID" value="<?php echo $orgID; ?>">
                            <label  class="btn btn-primary btn-file input-group-addon" onclick="onDsbmntBnkBranchChange();">
                                <span class="glyphicon glyphicon-th-list"></span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group form-group-sm">
                    <label for="loanRqst" class="control-label col-md-4">Loan Request:</label>
                    <div  class="col-md-8">
                        <div class="input-group">
                            <input type="text" class="form-control" aria-label="..." id="loanRqst" value="<?php echo $loanRqst; ?>" readonly="readonly">
                            <input type="hidden" id="loanRqstID" value="<?php echo $loanRqstID; ?>">
                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'MCF Loan Canditates For Write-Off', 'gnrlOrgID','bnkBranchID', '', 'radio', true, '', 'loanRqstID', 'loanRqst', 'clear', 1, '');;">
                                <span class="glyphicon glyphicon-th-list"></span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group form-group-sm">
                    <label for="ovdrftIntrstPymntCustNm" class="control-label col-md-4">Customer:</label>
                    <div  class="col-md-8">
                        <input type="text" readonly="readonly" class="form-control rqrdFld" id="ovdrftIntrstPymntCustNm" placeholder="Customer Name" value="<?php echo $ovdrftIntrstPymntCustNm; ?>"/>
                    </div>
                </div>                    
                <div class="form-group form-group-sm">
                    <label for="ovdrftIntrstPymntReason" class="control-label col-md-4">Process Description:</label>
                    <div  class="col-md-8">
                        <textarea <?php echo $mkReadOnly; ?> class="form-control rqrdFld" id="ovdrftIntrstPymntReason" cols="2" placeholder="Process Description" rows="4"><?php echo $ovdrftIntrstPymntReason; ?></textarea>
                    </div>
                </div>
                <div class="form-group form-group-sm">
                    <label for="ovdrftIntrstPymntAmount" class="control-label col-md-4">Amount:</label>
                    <div  class="col-md-8">
                        <div class="input-group">
                            <label class="btn btn-primary btn-file input-group-addon" onclick="">
                                <?php echo $crncy; ?>
                            </label>
                            <input type="text" class="form-control rqrdFld" aria-label="..." id="ovdrftIntrstPymntAmount" value="<?php echo $ovdrftIntrstPymntAmount; ?>" readonly="readonly">
                        </div>
                    </div>
                </div>
            </div>
                <?php if($trnsStatusDsply == "Authorized, Unprocessed") { ?>
            <div class="row" style="float:right;padding-right: 7px;">
                    <button type="button" class="btn btn-primary" onclick="processOvdrftIntrstPymnt(0);" >Process Loan Write-Off</button>
                <?php } else if($trnsStatusDsply == "Authorized, Processed") { ?>
                    <button type="button" class="btn btn-primary" onclick="processOvdrftIntrstPymnt(1);" >Void Loan Write-Off</button>
            </div> 
                <?php } ?>                   
        </form>
        <?php      
    }
    ?>
    <!-- MODAL WINDOWS -->
    <!--  style="min-width: 1000px;left:-35%;"-->          

    <script type="text/javascript">

                $('#datetimepicker1').datetimepicker({
                    showTodayButton: true,
                    format: "d-M-yyyy",
                    //sideBySide: true,
                    widgetPositioning: {
                        horizontal: 'right',
                        vertical: 'top'
                    },
                    language: 'en',
                    weekStart: 0,
                    //todayBtn: true,
                    autoclose: true,
                    todayHighlight: true,
                    keyboardNavigation: true,
                    startView: 2,
                    minView: 2,
                    maxView: 4,
                    forceParse: true
                });   

        $(document).ready(function () {
            var ctxPiD = document.getElementById("pieChart");//.getContext('2d');
            if(ctxPiD){
                var ctxP = ctxPiD.getContext('2d');
                var myPieChart = new Chart(ctxP, {
                    type: 'pie',
                    data: {
                        labels: ["Red", "Green", "Yellow", "Grey", "Dark Grey"],
                        datasets: [
                            {
                                data: [300, 50, 100, 40, 120],
                                backgroundColor: ["#F7464A", "#46BFBD", "#FDB45C", "#949FB1", "#4D5360"],
                                hoverBackgroundColor: ["#FF5A5E", "#5AD3D1", "#FFC870", "#A8B3C5", "#616774"]
                            }
                        ]
                    },
                    options: {
                        responsive: true
                    }    
                });
            }
            
            var ctxLiD = document.getElementById("lineChart");//.getContext('2d');
            if(ctxLiD){
                var ctxL = ctxLiD.getContext('2d');
                var myLineChart = new Chart(ctxL, {
                    type: 'line',
                    data: {
                        labels: ["January", "February", "March", "April", "May", "June", "July"],
                        datasets: [
                            {
                                label: "My First dataset",
                                fillColor: "rgba(220,220,220,0.2)",
                                strokeColor: "rgba(220,220,220,1)",
                                pointColor: "rgba(220,220,220,1)",
                                pointStrokeColor: "#fff",
                                pointHighlightFill: "#fff",
                                pointHighlightStroke: "rgba(220,220,220,1)",
                                data: [65, 59, 80, 81, 56, 55, 40]
                            },
                            {
                                label: "My Second dataset",
                                fillColor: "rgba(151,187,205,0.2)",
                                strokeColor: "rgba(151,187,205,1)",
                                pointColor: "rgba(151,187,205,1)",
                                pointStrokeColor: "#fff",
                                pointHighlightFill: "#fff",
                                pointHighlightStroke: "rgba(151,187,205,1)",
                                data: [28, 48, 40, 19, 86, 27, 90]
                            }
                        ]
                    },
                    options: {
                        responsive: true
                    }    
                });   
            }
                
            var ctxBiD = document.getElementById("barChart");//.getContext('2d');
            if(ctxBiD){
                var ctxB = ctxBiD.getContext('2d');
                var myBarChart = new Chart(ctxB, {
                    type: 'bar',
                    data: {
                        labels: ["Red", "Blue", "Yellow", "Green", "Purple", "Orange"],
                        datasets: [{
                            label: '# of Votes',
                            data: [12, 19, 3, 5, 2, 3],
                            backgroundColor: [
                                'rgba(255, 99, 132, 0.2)',
                                'rgba(54, 162, 235, 0.2)',
                                'rgba(255, 206, 86, 0.2)',
                                'rgba(75, 192, 192, 0.2)',
                                'rgba(153, 102, 255, 0.2)',
                                'rgba(255, 159, 64, 0.2)'
                            ],
                            borderColor: [
                                'rgba(255,99,132,1)',
                                'rgba(54, 162, 235, 1)',
                                'rgba(255, 206, 86, 1)',
                                'rgba(75, 192, 192, 1)',
                                'rgba(153, 102, 255, 1)',
                                'rgba(255, 159, 64, 1)'
                            ],
                            borderWidth: 1
                        }]
                    },
                    optionss: {
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero:true
                                }
                            }]
                        }
                    }
                });  
            }
        });
    </script>                

    <?php
}
?>
