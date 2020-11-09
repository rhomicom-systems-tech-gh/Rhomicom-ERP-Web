<?php
if (array_key_exists('lgn_num', get_defined_vars())) {
    $prsnid = $_SESSION['PRSN_ID'];
    $prsnID = $_SESSION['PRSN_ID'];
    $orgID = $_SESSION['ORG_ID'];
    $lnkdFirmID = getGnrlRecNm("prs.prsn_names_nos", "person_id", "lnkd_firm_org_id", $prsnid);
    $pkID = $PKeyID;
    
    $canBlockAccount = test_prmssns($dfltPrvldgs[248], $mdlNm); 
    $canCloseAccount = test_prmssns($dfltPrvldgs[249], $mdlNm); 
    $canReopenAccount = test_prmssns($dfltPrvldgs[250], $mdlNm);    
    $canUnblockAccount = test_prmssns($dfltPrvldgs[251], $mdlNm);  
    $canSeeOtherStaffAcctTrns = test_prmssns($dfltPrvldgs[298], $mdlNm); 

    $rowATZ0 = ""; $rowATZ1 = ""; $rowATZ2 = "";$rowATZ3 = "";$rowATZ4 = "";$rowATZ5 = "";$rowATZ6 = "";$rowATZ7 = "";$rowATZ8 = "";$rowATZ9 = "";$rowATZ10 = "";
    $rowATZ11 = "";$rowATZ12 = "";$rowATZ13 = "";$rowATZ14 = "";$rowATZ15 = "";$rowATZ16 = "";$rowATZ17 = "";$rowATZ18 = "";$rowATZ19 = "";$rowATZ19 = "";
    $rowATZ20 = "";$rowATZ21 = "";$rowATZ22 = "";$rowATZ23 = "";$rowATZ24 = "";$rowATZ25 = "";$rowATZ26 = "";$rowATZ27 = "";$rowATZ28 = "";$rowATZ29 = "";
    $rowATZ30 = "";$rowATZ31 = "";$rowATZ32 = "";$rowATZ33 = "";$rowATZ34 = "";$rowATZ35 = "";
    $v_BranchATZ = "";
    $relOfficerNmATZ = "";
    
    if ($subPgNo == 2.1) {//CUSTOMER ACCOUNTS
        if ($vwtyp == "0") {
            /* BASIC DATA */
            if ($vwtypActn == "EDIT") {
                /* Read Only */
                $tblNm1 = "mcf.mcf_accounts";

                $cnt = getCustAcctDataChngPndngCount($pkID);
                if($cnt > 0){
                    $tblNm1 = "mcf.mcf_accounts_hstrc";        
                }                  
                
                $result = getCustAccountDets($pkID, $tblNm1);
                $shwHydNtlntySts = "style=\"display:none !important;\"";

                while ($row = loc_db_fetch_array($result)) {
                    $trnsStatus = $row[22];
                    $custAcctNumber = $row[4];
                    if($trnsStatus == "Unauthorized" || $trnsStatus == "Initiated"){
                        $shwHydNtlntySts = "style=\"display:block !important;\"";
                    }  
                    $branch = getGnrlRecNm("org.org_sites_locations", "location_id", "site_desc||' ('||location_code_name||')'", $row[19]);
                    $relOfficerNm = getGnrlRecNm("prs.prsn_names_nos", "person_id", "trim(title || ' ' || sur_name || ', ' || first_name || ' ' || other_names)", $row[20]);
                    $relOfficerLocalID = getGnrlRecNm("prs.prsn_names_nos", "person_id", "local_id_no", $row[20]);
                    $accountStatus = $row[29].", ".$row[28];
                    $accrdIntrst = $row[27];
                    
                    $blkClsRpn = $row[29];
                    
                    $tblNmAuthrzd = "";
                    $lblColor = "red";
                    $resultAuthrzd = "";
                    $mkReadOnly = "";
                    $mkReadOnlyDsbld = "";
                    
		    if($trnsStatus == "Initiated" || $trnsStatus == "Approved" || $trnsStatus == "Unauthorized" || $trnsStatus == "Authorized"){
                        $mkReadOnly = "readonly=\"readonly\"";
                        $mkReadOnlyDsbld = "disabled=\"true\"";
                    }                    

                    if($cnt > 0){
                        $tblNmAuthrzd = "mcf.mcf_accounts";        
                        $resultAuthrzd = getCustAccountDets($pkID, $tblNmAuthrzd);
                        while ($rowATZ = loc_db_fetch_array($resultAuthrzd)) {
                            $rowATZ0 = $rowATZ[0]; $rowATZ1 = $rowATZ[1]; $rowATZ2 = $rowATZ[2]; $rowATZ3 = $rowATZ[3]; $rowATZ4 = $rowATZ[4];
                            $rowATZ5 = $rowATZ[5]; $rowATZ6 = $rowATZ[6]; $rowATZ7 = $rowATZ[7]; $rowATZ8 = $rowATZ[8]; $rowATZ9 = $rowATZ[9];
                            $rowATZ10 = $rowATZ[10]; $rowATZ11 = $rowATZ[11]; $rowATZ12 = $rowATZ[12]; $rowATZ13 = $rowATZ[13]; $rowATZ14 = $rowATZ[14];
                            $rowATZ15 = $rowATZ[15]; $rowATZ16 = $rowATZ[16]; $rowATZ17 = $rowATZ[17]; $rowATZ18 = $rowATZ[18]; $rowATZ19 = $rowATZ[19];
                            $rowATZ20 = $rowATZ[20]; $rowATZ21 = $rowATZ[21]; $rowATZ22 = $rowATZ[22]; $rowATZ23 = $rowATZ[23]; $rowATZ24 = $rowATZ[24];
                            $rowATZ25 = $rowATZ[25]; $rowATZ26 = $rowATZ[26]; $rowATZ27 = $rowATZ[27]; $rowATZ28 = $rowATZ[28]; $rowATZ29 = $rowATZ[29];
                            $rowATZ30 = $rowATZ[30]; $rowATZ31 = $rowATZ[31]; $rowATZ32 = $rowATZ[32]; $rowATZ33 = $rowATZ[33];
                            
                            $v_BranchATZ = getGnrlRecNm("org.org_sites_locations", "location_id", "site_desc||' ('||location_code_name||')' ", $rowATZ19);
                            $relOfficerNmATZ = getGnrlRecNm("prs.prsn_names_nos", "person_id", "trim(title || ' ' || sur_name || ', ' || first_name || ' ' || other_names)", $rowATZ20);
                            $relOfficerLocalIDATZ = getGnrlRecNm("prs.prsn_names_nos", "person_id", "local_id_no", $rowATZ20);
                        }               
                    }                    
                    /* Edit */
                    ?>
                    <input class="form-control" id="addOrEditForm" type = "hidden" placeholder="addOrEditForm" value="Edit"/>
                    <input class="form-control" id="rvsnTtl" type = "hidden" placeholder="Revision Total" value="<?php echo $row[32]; ?>"/>
                    <div class="">
                        <div class="row">                  
                            <div class="col-md-12">
                                <div class="custDiv" style="border:none !important; padding-top:0px !important;"> 
                                    <div class="tab-content">
                                        <div id="prflCAHomeEDT" class="tab-pane fadein active" style="border:none !important;">    
                                            <div class="row" style="margin: 0px 0px 5px 0px !important;">
                                                <div class="col-md-12" style="padding:0px 1px 0px 1px !important;">
                                                    <div style="float:left;">
                                                        <button type="button" class="btn btn-default btn-sm" style="height:30px;" id="myVmsTrnsStatusBtn">
                                                            <span style="font-weight:bold;">Approval Status: </span><span style="color:<?php echo "red"; ?>;font-weight: bold;"><?php echo $trnsStatus; ?></span>
                                                        </button>
                                                        <button type="button" class="btn btn-default btn-sm" style="height:30px;" id="myVmsTrnsStatusBtn">
                                                            <span style="font-weight:bold;">Account Status: </span><span style="color:<?php echo "red"; ?>;font-weight: bold;"><?php echo $accountStatus; ?></span>
                                                        </button>
                                                        <?php if ($vwtypActn != "VIEW") { ?>
                                                            <button type="button" class="btn btn-default btn-sm" style="height:30px;" onclick="getCustAcctsForm('myFormsModalCA', 'myFormsModalBodyCA', 'myFormsModalTitleCA', 'Edit Customer Account', 13, 2.1, 0, 'EDIT', <?php echo $pkID; ?>, 'custAcctTable', 'indCustTableRow1');" data-toggle="tooltip" title="Reload Transaction">
                                                                <img src="cmn_images/refresh.bmp" style="padding-right: 2px; height:17px; width:auto; position: relative; vertical-align: middle;">
                                                            </button>
                                                        <?php } ?>
                                                        <button type="button" class="btn btn-default" style="height:30px;" onclick="getOneMcfDocsForm_Gnrl(<?php echo $pkID; ?>, 'CUSTOMER ACCOUNTS', 140, 'Customer Accounts Attachments');" data-toggle="tooltip" data-placement="bottom" title = "Attached Documents">
                                                            <img src="cmn_images/adjunto.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                        </button>
                                                        <button type="button" class="btn btn-default btn-sm" style="height:30px;" id="myVmsTrnsStatusBtn">
                                                            <span style="font-weight:bold;">Accrued Interest: </span><span style="color:<?php echo "blue"; ?>;font-weight: bold;"><?php echo number_format($accrdIntrst, 2); ?></span>
                                                        </button>
                                                    </div>
                                                    <div class="" style="float:right;">
                                                        <?php if ($trnsStatus == "Authorized" || $trnsStatus == "Approved" && (test_prmssns($dfltPrvldgs[81], $mdlNm) === true)  && $vwtypActn === "EDIT") { ?>
                                                        <button type="button" class="btn btn-default btn-sm" style="" onclick="modifyAutrzCustAcctRqst(<?php echo $pgNo; ?>, <?php echo $subPgNo; ?>, <?php echo $vwtyp; ?>);">
                                                            <img src="cmn_images/edit32.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">
                                                            MODIFY DATA
                                                        </button>
                                                        <?php } else if ($trnsStatus == "Incomplete" || $trnsStatus == "Rejected" || $trnsStatus == "Withdrawn") { ?>
                                                        <div class="btn-group" style="margin-bottom: 0px;">
                                                            <button type="button" class="btn btn-default btn-sm" style="height: 30px !important;" onclick="">
                                                                <img src="cmn_images/ControlPanel.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">
                                                            </button>
                                                            <button class="btn btn-info dropdown-toggle btn-sm" style="height:30px !important;" type="button" data-toggle="dropdown">
                                                                <span class="caret"></span>
                                                            </button>
                                                            <ul class="dropdown-menu" role="menu" style="margin-left: 15px !important;">
                                                                <?php if ($canBlockAccount === true && $blkClsRpn === "Active") { ?> 
                                                                <li>
                                                                    <a href="javascript:blockCloseReopenAccnts(1);">
                                                                        Block
                                                                    </a>
                                                                </li>
                                                                <?php } if ($canUnblockAccount === true && $blkClsRpn === "Blocked") { ?> 
                                                                <li>
                                                                    <a href="javascript:blockCloseReopenAccnts(2);">
                                                                        Unblock
                                                                    </a>
                                                                </li>
                                                                <?php } if ($canCloseAccount === true && $blkClsRpn === "Active") { ?> 
                                                                <li>
                                                                    <a href="javascript:blockCloseReopenAccnts(3);">
                                                                        Close
                                                                    </a>
                                                                </li>
                                                                <?php } ?> 
                                                                <?php if ($canReopenAccount === true && $blkClsRpn === "Closed") { ?> 
                                                                <li>
                                                                    <a href="javascript:blockCloseReopenAccnts(4);">
                                                                        Re-open
                                                                    </a>
                                                                </li>
                                                                <?php } ?>
                                                            </ul>
                                                        </div>  
                                                        <button type="button" id="saveCustAccountBtn" class="btn btn-default btn-sm" onclick="saveCustAccountsData(<?php echo $pgNo; ?>, <?php echo $subPgNo; ?>, <?php echo $vwtyp; ?>, 0);"><img src="cmn_images/FloppyDisk.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">SAVE</button>
                                                        <button type="button" id="submitCustAccountBtn" style="display:inline-block !important;" onclick="saveCustAccountsData(<?php echo $pgNo; ?>, <?php echo $subPgNo; ?>, <?php echo $vwtyp; ?>, 1);" class="btn btn-default btn-sm" ><img src="cmn_images/Emailcon.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">SUBMIT</button>
                                                        <?php }  if (didAuthorizerSubmit($pkID, $usrID) && ($trnsStatus == "Unauthorized" || $trnsStatus == "Initiated")) { ?>
                                                            <button type="button" id="withdrawCustAccountBtn" style="display:inline-block !important;" class="btn btn-default btn-sm"  onclick="submitWithdrawCustAccnt(<?php echo $pgNo; ?>, <?php echo $subPgNo; ?>, <?php echo $vwtyp; ?>, 'WITHDRAW');"><img src="cmn_images/withdraw_rqst.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">WITHDRAW REQUEST</button>                                                                                         
                                                        <?php }  ?>                     
                                                    </div>
                                                </div>
                                            </div>
                                            <form class="form-horizontal" id="customerAccountForm">
                                                <div class="row"><!-- ROW 1 -->
                                                    <div class="col-lg-12"> 
                                                        <!--<fieldset class="basic_person_fs5" style="padding: 1px !important;">-->
                                                        <legend class="basic_person_lg" style="margin-bottom: 5px !important;">Customer</legend> 
                                                        <div  class="col-md-3">
                                                            <div class="form-group form-group-sm">
                                                                <?php echo dsplyCntrlLbl($cnt, $row[1], $rowATZ1, "col-md-4", "Type", "custType"); ?>
                                                                <div  class="col-md-8">
                                                                    <?php if($custAcctNumber != ""){ ?>
                                                                        <input class="form-control" id="custType" type = "text" placeholder="" value="<?php echo $row[1]; ?>" readonly/>
                                                                    <?php } else { ?>
                                                                    <select <?php echo $mkReadOnlyDsbld; ?> class="form-control" id="custType" onchange="loadPrsnTypeEntiries(this)">
                                                                        <option value="--Please Select--">--Please Select--</option>
                                                                        <?php
                                                                        $brghtStr = "";
                                                                        $isDynmyc = FALSE;
                                                                        $titleRslt = getLovValues("%", "Both", 0, 100, $brghtStr, getLovID("Bank Customer Types"), $isDynmyc, -1, "", "");
                                                                        while ($titleRow = loc_db_fetch_array($titleRslt)) {
                                                                            $selectedTxt = "";
                                                                            if ($row[1] == $titleRow[0]) {
                                                                                $selectedTxt = "selected=\"selected\"";
                                                                            }
                                                                            ?>
                                                                            <option value="<?php echo $titleRow[0]; ?>" <?php echo $selectedTxt; ?>><?php echo $titleRow[0]; ?></option>
                                                                            <?php
                                                                        }
                                                                        ?>
                                                                    </select>
                                                                    <?php } ?>
                                                                </div>
                                                            </div>                                                                                                                           
                                                        </div>
                                                        <div  class="col-md-6">
                                                            <div class="form-group form-group-sm">
                                                                <?php  echo dsplyCntrlLbl($cnt, $row[2], $rowATZ2, "col-md-3", "Name", "bnkCustomer"); ?>
                                                                <div  class="col-md-9">
                                                                    <?php if($custAcctNumber != ""){ ?>
                                                                        <input class="form-control" id="bnkCustomer" type = "text" placeholder="" value="<?php echo $row[2]; ?>" readonly/>
                                                                        <input type="hidden" id="bnkCustomerID" value="<?php echo $row[3]; ?>">
                                                                    <?php } else { ?>
                                                                    <div class="input-group">
                                                                        <input type="text" class="form-control" aria-label="..." id="bnkCustomer" value="<?php echo $row[2]; ?>" readonly>
                                                                        <input type="hidden" id="bnkCustomerID" value="<?php echo $row[3]; ?>">
                                                                        <input type="hidden" id="gnrlOrgID" value="<?php echo $orgID; ?>">
                                                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Bank Customers', 'gnrlOrgID', 'custType', '', 'radio', true, '', 'bnkCustomerID', 'bnkCustomer', 'clear', 1, '', function () {
                                                                                                $('#acctTitle').val($('#bnkCustomer').val());
                                                                                                });">
                                                                            <span class="glyphicon glyphicon-th-list"></span>
                                                                        </label>
                                                                    </div>
                                                                    <?php } ?>
                                                                </div>
                                                                <div class="col-md-1" style="padding:0px 0px 0px 0px !important;">&nbsp;</div>
                                                            </div>
                                                        </div>
                                                        <div  class="col-md-3">
                                                            <div class="col-md-12" style="padding:0px 1px 0px 1px !important;"><button type="button" class="btn btn-info btn-sm" style="width:100% !important;" onclick="viewCustProfile();"><img src="cmn_images/kghostview.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">VIEW CUSTOMER PROFILE</button></div> 
                                                        </div>
                                                        <!--</fieldset>-->
                                                    </div>
                                                </div>                                            
                                                <div class="row"><!-- ROW 2 -->
                                                    <div class="col-lg-6">
                                                        <fieldset class="basic_person_fs1" style="margin-top: 5px !important;"><legend class="basic_person_lg">Account</legend>
                                                            <div class="form-group form-group-sm">
                                                                <?php  echo  dsplyCntrlLbl($cnt, $row[4], $rowATZ4, "col-md-4", "Number", "acctNumber"); ?>
                                                                <div class="col-md-8">
                                                                    <input class="form-control" id="acctNumber" type = "text" placeholder="" value="<?php echo $row[4]; ?>" readonly/>
                                                                    <!--CUSTOMER ID-->
                                                                    <input class="form-control" id="acctID" type = "hidden" placeholder="Account ID" value="<?php echo $row[0]; ?>"/>                                                                                                                                           
                                                                </div>
                                                            </div> 
                                                            <div class="form-group form-group-sm">
                                                                <?php  echo  dsplyCntrlLbl($cnt, $row[5], $rowATZ5, "col-md-4", "Account Title", "acctTitle"); ?>
                                                                <div  class="col-md-8">
                                                                    <textarea <?php echo $mkReadOnly; ?> class="form-control rqrdFld" id="acctTitle" cols="2" placeholder="Account Title" rows="3" ><?php echo $row[5]; ?></textarea>                                                                    
                                                                </div>                                                         
                                                            </div>     
                                                            <div class="form-group form-group-sm">
                                                                <?php  echo  dsplyCntrlLbl($cnt, $row[6], $rowATZ6, "col-md-4", "Account Type", "acctType"); ?>
                                                                <div  class="col-md-8">
                                                                    <?php if($custAcctNumber != ""){ ?>
                                                                        <input class="form-control" id="acctType" type = "text" placeholder="" value="<?php echo $row[6]; ?>" readonly/>
                                                                    <?php } else { ?>
                                                                    <select <?php echo $mkReadOnlyDsbld; ?> class="form-control" id="acctType" >
                                                                        <option value="--Please Select--">--Please Select--</option>
                                                                        <?php
                                                                        $brghtStr = "";
                                                                        $isDynmyc = FALSE;
                                                                        $titleRslt = getLovValues("%", "Both", 0, 100, $brghtStr, getLovID("Bank Account Types"), $isDynmyc, -1, "", "");
                                                                        while ($titleRow = loc_db_fetch_array($titleRslt)) {
                                                                            $selectedTxt = "";
                                                                            if ($row[6] == $titleRow[0]) {
                                                                                $selectedTxt = "selected=\"selected\"";
                                                                            }
                                                                            ?>
                                                                            <option value="<?php echo $titleRow[0]; ?>" <?php echo $selectedTxt; ?>><?php echo $titleRow[0]; ?></option>
                                                                            <?php
                                                                        }
                                                                        ?>
                                                                    </select>
                                                                    <?php } ?>
                                                                </div>
                                                            </div>
                                                            <div class="form-group form-group-sm">
                                                                <?php  echo dsplyCntrlLbl($cnt, $row[7], $rowATZ7, "col-md-4", "Product", "prdtType"); ?>
                                                                <div  class="col-md-8">
                                                                    <?php if($custAcctNumber != ""){ ?>
                                                                    <div class="input-group">
                                                                        <input class="form-control" id="prdtType" type = "text" placeholder="" value="<?php echo $row[7]; ?>" readonly/>
                                                                        <input type="hidden" id="prdtTypeID" value="<?php echo $row[8]; ?>">
                                                                        <label class="btn btn-primary btn-file input-group-addon" onclick="dsplyPrdtForm();" data-toggle="tooltip" data-placement="bottom" title = "View Product">
                                                                            <span class="glyphicon glyphicon-info-sign"></span>
                                                                        </label>
                                                                    </div>
                                                                    <?php } else { ?>
                                                                    <div class="input-group">
                                                                        <input type="text" class="form-control" aria-label="..." id="prdtType" value="<?php echo $row[7]; ?>" readonly>
                                                                        <input type="hidden" id="prdtTypeID" value="<?php echo $row[8]; ?>">
                                                                        <label class="btn btn-primary btn-file input-group-addon" onclick="onClickSvngsProduct(<?php echo $pgNo; ?>, <?php echo $subPgNo; ?>);" >
                                                                            <span class="glyphicon glyphicon-th-list"></span>
                                                                        </label>
                                                                        <label class="btn btn-primary btn-file input-group-addon" onclick="dsplyPrdtForm();" data-toggle="tooltip" data-placement="bottom" title = "View Product">
                                                                            <span class="glyphicon glyphicon-info-sign"></span>
                                                                        </label>
                                                                    </div>
                                                                    <?php } ?>
                                                                </div>
                                                            </div> 
                                                            <div class="form-group form-group-sm">
                                                                <?php if($cnt > 0 && $row[9] != $rowATZ9) { ?>
                                                                <label data-toggle="tooltip" title="<?php echo $rowATZ9; ?>" for="acctTrnsTyp" id="acctTrnsTypLbl" class="control-label col-md-4"><a href="#" style="color:red;" onclick="dsplyAuthrzdData('<?php echo $rowATZ9; ?>');">Transaction Type:</a></label>
                                                                <?php } else { ?>
                                                                <label for="acctTrnsTyp" id="acctTrnsTypLbl" class="control-label col-md-4">Transaction Type:</label>
                                                                <?php } ?>
                                                                <div  class="col-md-8">
                                                                    <select class="form-control" name="acctTrnsTyp[]" id="acctTrnsTyp" multiple>
                                                                        <!--<option value="">&nbsp;</option>-->
                                                                        <?php
                                                                        $brghtStr = "";
                                                                        $isDynmyc = FALSE;
                                                                        $titleRslt = getLovValues("%", "Both", 0, 100, $brghtStr, getLovID("Account Transaction Types"), $isDynmyc, -1, "", "");
                                                                        while ($titleRow = loc_db_fetch_array($titleRslt)) {
                                                                            $selectedTxt = "";
                                                                            $trnsTypArr = explode(',', $row[9]);
                                                                            foreach ($trnsTypArr as $val) {
                                                                                if ($val == $titleRow[0]) {
                                                                                    $selectedTxt = "selected=\"selected\"";
                                                                                }
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
                                                                <?php  echo dsplyCntrlLbl($cnt, $row[10], $rowATZ10, "col-md-4", "Person Type/Entity", "prsnTypeEntity"); ?>
                                                                <div  class="col-md-8">
                                                                    <select <?php echo $mkReadOnlyDsbld; ?> class="form-control" id="prsnTypeEntity"> 
                                                                        <option value="">&nbsp;</option>
                                                                        <?php
                                                                        if ($row[1] == "Corporate") {
                                                                            $lovName = "Bank Account Person Types/Entity - CORP";
                                                                            ?>
                                                                            <option value="CORP-Partnership">CORP-Partnership</option>
                                                                            <?php
                                                                        } else if ($row[1] == "Individual") {
                                                                            $lovName = "Bank Account Person Types/Entity - INDV";
                                                                        } else if ($row[1] == "Group") {
                                                                            $lovName = "Bank Account Person Types/Entity - CGRP";
                                                                        }

                                                                        $brghtStr = "";
                                                                        $isDynmyc = FALSE;
                                                                        $titleRslt = getLovValues("%", "Both", 0, 100, $brghtStr, getLovID($lovName), $isDynmyc, -1, "", "");
                                                                        while ($titleRow = loc_db_fetch_array($titleRslt)) {
                                                                            $selectedTxt = "";
                                                                            if ($row[10] == $titleRow[0]) {
                                                                                $selectedTxt = "selected=\"selected\"";
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
                                                                <?php  echo dsplyCntrlLbl($cnt, $row[11], $rowATZ11, "col-md-4", "Currency", "prdtCrncy"); ?>
                                                                <div class="col-md-8">
                                                                    <input class="form-control" id="prdtCrncyIsoCode" type = "text" placeholder="" value="<?php echo $row[11]; ?>" readonly>
                                                                    <!--CUSTOMER ID-->
                                                                    <input class="form-control" id="prdtCrncy" type = "hidden" placeholder="Currency ID" value="<?php echo $row[12]; ?>">                                                                                                                                           
                                                                </div>
                                                            </div>
                                                        </fieldset>
                                                    </div>                                
                                                    <div class="col-lg-6">
                                                        <fieldset class="basic_person_fs1" style="margin-top: 5px !important;"><legend class="basic_person_lg">General</legend>
                                                            <div class="form-group form-group-sm">
                                                                <?php if($cnt > 0 && $row[13] != $rowATZ13) { ?>
                                                                <label data-toggle="tooltip" title="<?php echo $rowATZ13; ?>" for="prpsOfAcct" id="prpsOfAcctLbl" class="control-label col-md-4"><a href="#" style="color:red;" onclick="dsplyAuthrzdData('<?php echo $rowATZ16; ?>');">Purpose:</a></label>
                                                                <?php } else { ?>
                                                                <label for="prpsOfAcct" id="prpsOfAcctLbl" class="control-label col-md-4">Purpose:</label>
                                                                <?php } ?>
                                                                <div class="col-md-8">
                                                                    <select class="form-control" name="prpsOfAcct[]" id="prpsOfAcct" multiple style="width:206px;">
                                                                        <!--<option value="">&nbsp;</option>-->
                                                                            <?php
                                                                            $brghtStr = "";
                                                                            $isDynmyc = FALSE;
                                                                            $titleRslt = getLovValues("%", "Both", 0, 100, $brghtStr, getLovID("Purpose of Account"), $isDynmyc, -1, "", "");
                                                                            while ($titleRow = loc_db_fetch_array($titleRslt)) {
                                                                                $selectedTxt = "";
                                                                                $prpsOfAcctArr = explode(',', $row[13]);
                                                                                foreach ($prpsOfAcctArr as $val) {
                                                                                    if ($val == $titleRow[0]) {
                                                                                        $selectedTxt = "selected=\"selected\"";
                                                                                    }
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
                                                                <?php  echo dsplyCntrlLbl($cnt, $row[14], $rowATZ14, "col-md-4", "Purpose - Other", "prpsOfAcctOther"); ?>
                                                                <div  class="col-md-8">
                                                                    <textarea <?php echo $mkReadOnly; ?> class="form-control" id="prpsOfAcctOther" cols="2" placeholder="Purpose - Other" rows="3"><?php echo $row[14]; ?></textarea>
                                                                </div>
                                                            </div> 
                                                            <div class="form-group form-group-sm">
                                                                <?php if($cnt > 0 && $row[15] != $rowATZ15) { ?>
                                                                <label data-toggle="tooltip" title="<?php echo $rowATZ15; ?>" for="srcOfFunds" id="srcOfFundsLbl" class="control-label col-md-4"><a href="#" style="color:red;" onclick="dsplyAuthrzdData('<?php echo $rowATZ15; ?>');">Source of Funds:</a></label>
                                                                <?php } else { ?>
                                                                <label for="srcOfFunds" id="srcOfFundsLbl" class="control-label col-md-4">Source of Funds:</label>
                                                                <?php } ?>
                                                                <div class="col-md-8">
                                                                    <select class="form-control" name="srcOfFunds" id="srcOfFunds" multiple>
                                                                        <!--<option value="">&nbsp;</option>-->
                                                                            <?php
                                                                            $brghtStr = "";
                                                                            $isDynmyc = FALSE;
                                                                            $titleRslt = getLovValues("%", "Both", 0, 100, $brghtStr, getLovID("Source of Funds"), $isDynmyc, -1, "", "");
                                                                            while ($titleRow = loc_db_fetch_array($titleRslt)) {
                                                                                $selectedTxt = "";
                                                                                $srcOfFundsArr = explode(',', $row[15]);
                                                                                foreach ($srcOfFundsArr as $val) {
                                                                                    if ($val == $titleRow[0]) {
                                                                                        $selectedTxt = "selected=\"selected\"";
                                                                                    }
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
                                                                <?php  echo dsplyCntrlLbl($cnt, $row[16], $rowATZ16, "col-md-4", "Source of Funds - Other", "srcOfFundsOther"); ?>
                                                                <div  class="col-md-8">
                                                                    <textarea <?php echo $mkReadOnly; ?> class="form-control" id="srcOfFundsOther" cols="2" placeholder="Source of Funds - Other" rows="3"><?php echo $row[16]; ?></textarea>
                                                                </div>
                                                            </div>                                                        
                                                            <div class="form-group form-group-sm">
                                                                <?php  echo dsplyCntrlLbl($cnt, $row[17], $rowATZ17, "col-md-6", "Expected Monthly Transaction Volume", "trnsPerMnthNo"); ?>
                                                                <div  class="col-md-6">
                                                                    <input <?php echo $mkReadOnly; ?> class="form-control rqrdFld" id="trnsPerMnthNo" type = "number" min="0" placeholder="Monthly Trans. Volume" value="<?php echo $row[17]; ?>"/>
                                                                </div>
                                                            </div>
                                                            <div class="form-group form-group-sm">
                                                                <?php  echo dsplyCntrlLbl($cnt, $row[18], $rowATZ18, "col-md-6", "Expected Monthly Amount", "trnsPerMnthNo"); ?>
                                                                <div  class="col-md-6">
                                                                    <input <?php echo $mkReadOnly; ?> class="form-control rqrdFld" id="amountPerMnth" type = "number" min="0" placeholder="Monthly Amount" value="<?php echo $row[18]; ?>"/>
                                                                </div>
                                                            </div>                                                        
                                                        </fieldset>
                                                    </div>
                                                </div>  
                                                <div class="row"><!-- ROW 3 -->
                                                    <div class="col-lg-6">
                                                        <fieldset class="basic_person_fs5"><legend class="basic_person_lg">Account</legend>
                                                            <div class="form-group form-group-sm">
                                                                <?php  echo dsplyCntrlLbl($cnt, $branch, $v_BranchATZ, "col-md-4", "Branch", "bnkBranch"); ?>
                                                                <div  class="col-md-8">
                                                                    <?php if($custAcctNumber != ""){ ?>
                                                                        <input class="form-control" id="bnkBranch" type = "text" placeholder="" value="<?php echo $branch; ?>" readonly/>
                                                                        <input type="hidden" id="bnkBranchID" value="<?php echo $row[19]; ?>">
                                                                    <?php } else { ?>
                                                                    <div class="input-group" style="width:100% !important;">
                                                                        <input type="text" class="form-control" aria-label="..." id="bnkBranch" value="<?php echo $branch; ?>" readonly>
                                                                        <input type="hidden" id="bnkBranchID" value="<?php echo $row[19]; ?>"> 
                                                                        <input type="hidden" id="gnrlOrgID" value="<?php echo $orgID; ?>">
                                                                        <label style="display:none !important;" class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Sites/Locations New', 'gnrlOrgID', '', '', 'radio', true, '', 'bnkBranchID', 'bnkBranch', 'clear', 1, '');">
                                                                            <span class="glyphicon glyphicon-th-list"></span>
                                                                        </label>
                                                                    </div>
                                                                    <?php } ?>
                                                                </div>
                                                            </div>                                                        
                                                            <div class="form-group form-group-sm">
                                                                <?php  echo dsplyCntrlLbl($cnt, $relOfficerNm, $relOfficerNmATZ, "col-md-4", "Relationship Officer", "reltnOfficer"); ?>
                                                                <div  class="col-md-8">
                                                                    <div class="input-group">
                                                                        <input type="text" class="form-control" aria-label="..." id="reltnOfficer" value="<?php echo $relOfficerNm; ?>" readonly>
                                                                        <input type="hidden" id="reltnOfficerID" value="<?php echo $relOfficerLocalID; ?>">
                                                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Active Persons', '', '', '', 'radio', true, '', 'reltnOfficerID', 'reltnOfficer', 'clear', 1, '');">
                                                                            <span class="glyphicon glyphicon-th-list"></span>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div>   
                                                            <div class="form-group form-group-sm">
                                                                <?php  echo dsplyCntrlLbl($cnt, $row[29], $rowATZ29, "col-md-4", "Account Status", "accountStatus"); ?>
                                                                <div  class="col-md-8">
                                                                    <input class="form-control" id="accountStatus" type = "text" placeholder="" value="<?php echo $row[29]; ?>" readonly/>
                                                                </div>
                                                            </div>      
                                                            <div class="form-group form-group-sm">
                                                                <?php  echo dsplyCntrlLbl($cnt, $row[21], $rowATZ21, "col-md-4", "Comments", "statusReason"); ?>
                                                                <div  class="col-md-8">
                                                                    <textarea <?php echo $mkReadOnly; ?> class="form-control" id="closeReason" cols="2" placeholder="Comments" rows="2"><?php echo $row[21]; ?></textarea>
                                                                </div>
                                                            </div>
                                                            <div class="form-group form-group-sm" style="display:none !important;">
                                                                <label for="status" class="control-label col-md-4">Approval Status:</label>
                                                                <div class="col-md-8">
                                                                    <input class="form-control" id="status" type = "text" placeholder="" value="<?php echo $row[22]; ?>" readonly/>                                                                                                                                        
                                                                </div>
                                                            </div>
                                                            <div class="form-group form-group-sm" style="display:none;">
                                                                <label for="startDate" class="control-label col-md-4">Status Effective Date:</label>
                                                                <div  class="col-md-8">
                                                                    <input class="form-control" size="16" type="text" id="startDate" value="<?php echo getDB_Trns_time(); ?>" readonly="">
                                                                </div>
                                                            </div> 
                                                            <div class="form-group form-group-sm" style="display:none !important;">
                                                                <label for="ovdrftBal" class="control-label col-md-4">Overdraft Balance:</label>
                                                                <div  class="col-md-8">
                                                                    <input class="form-control" id="ovdrftBal" type = "number" min="0" placeholder="Overdraft Balance" value="<?php echo $row[23]; ?>" readonly/>
                                                                </div>
                                                            </div> 
                                                            <div class="form-group form-group-sm">
                                                                <?php  echo dsplyCntrlLbl($cnt, $row[25], $rowATZ25, "col-md-4", "Allowed Group Type", "grpType"); ?>
                                                                <div  class="col-md-8">
                                                                    <select <?php echo $mkReadOnlyDsbld; ?> class="form-control" id="grpType" onchange="grpTypNoticesChange();">
                                                                        <?php
                                                                        $sltdEvryone = "";
                                                                        $sltdDvGrp = "";
                                                                        $sltdGrd = "";
                                                                        $sltdJob = "";
                                                                        $sltdPstn = "";
                                                                        $sltdSiteLoc = "";
                                                                        $sltdPrsnTyp = "";
                                                                        if ($row[25] === "Everyone") {
                                                                            $sltdEvryone = "selected=\"selected\"";
                                                                        } else if ($row[25] === "Divisions/Groups") {
                                                                            $sltdDvGrp = "selected=\"selected\"";
                                                                        } else if ($row[25] === "Grade") {
                                                                            $sltdGrd = "selected=\"selected\"";
                                                                        } else if ($row[25] === "Job") {
                                                                            $sltdJob = "selected=\"selected\"";
                                                                        } else if ($row[25] === "Position") {
                                                                            $sltdPstn = "selected=\"selected\"";
                                                                        } else if ($row[25] === "Site/Location") {
                                                                            $sltdSiteLoc = "selected=\"selected\"";
                                                                        } else if ($row[25] === "Person Type") {
                                                                            $sltdPrsnTyp = "selected=\"selected\"";
                                                                        }
                                                                        ?>
                                                                        <option value="Everyone" <?php echo $sltdEvryone; ?>>Everyone</option>                                            
                                                                        <option value="Divisions/Groups" <?php echo $sltdDvGrp; ?>>Divisions/Groups</option>
                                                                        <option value="Grade" <?php echo $sltdGrd; ?>>Grade</option>
                                                                        <option value="Job" <?php echo $sltdJob; ?>>Job</option>
                                                                        <option value="Position" <?php echo $sltdPstn; ?>>Position</option>
                                                                        <option value="Site/Location" <?php echo $sltdSiteLoc; ?>>Site/Location</option>
                                                                        <option value="Person Type" <?php echo $sltdPrsnTyp; ?>>Person Type</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="form-group form-group-sm">
                                                                <?php  echo dsplyCntrlLbl($cnt, $row[25], $rowATZ25, "col-md-4", "Allowed Group Name", "groupName"); ?>
                                                                <div  class="col-md-8">
                                                                    <div class="input-group">
                                                                        <input type="text" class="form-control" aria-label="..." id="groupName" value="<?php echo getAllwdGrpVal($row[25], $row[26]); ?>" readonly="">
                                                                        <input type="hidden" id="gnrlOrgID" value="<?php echo $orgID; ?>">
                                                                        <input type="hidden" id="groupID" value="<?php echo $row[26]; ?>">
                                                                        <label disabled="true" id="groupNameLbl" class="btn btn-primary btn-file input-group-addon" onclick="getNoticeLovs('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Customers and Suppliers', 'gnrlOrgID', '', '', 'radio', true, '', 'groupID', 'groupName', 'clear', 1, '');">
                                                                            <span class="glyphicon glyphicon-th-list"></span>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="form-group form-group-sm">
                                                                <?php  echo dsplyCntrlLbl($cnt, $row[33], $rowATZ33, "col-md-4", "Can Overdraw Account?", "canOverdrawAcct"); ?>
                                                                <div  class="col-md-8">
                                                                    <select <?php echo $mkReadOnlyDsbld; ?> class="form-control" id="canOverdrawAcct" onchange="">
                                                                        <?php
                                                                        $sltdNo = "";
                                                                        $sltdYes = "";
                                                                        if ($row[33] === "No") {
                                                                            $sltdNo = "selected=\"selected\"";
                                                                        } else if ($row[33] === "Yes") {
                                                                            $sltdYes = "selected=\"selected\"";
                                                                        }
                                                                        ?>
                                                                        <option value="No" <?php echo $sltdNo; ?>>No</option>                                            
                                                                        <option value="Yes" <?php echo $sltdYes; ?>>Yes</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </fieldset>
                                                    </div>                                
                                                    <div class="col-lg-6">
                                                        <fieldset class="basic_person_fs5"><legend class="basic_person_lg">Signatories & Mandate</legend>
                                                            <div  class="col-md-12">
                                                                <div class="row"><!-- ROW 3 -->
                                                                    <div class="col-lg-8">
                                                                        <div class="form-group form-group-sm" style="margin-bottom: 5px !important;">
                                                                            <?php  echo dsplyCntrlLbl($cnt, $row[24], $rowATZ24, "col-md-4", "Mandate", "accMndte"); ?>
                                                                            <div  class="col-md-8">
                                                                                <select <?php echo $mkReadOnlyDsbld; ?> class="form-control" id="accMndte" >
                                                                                    <?php
                                                                                    $brghtStr = "";
                                                                                    $isDynmyc = FALSE;

                                                                                    if ($row[1] === 'Individual') {
                                                                                        $selectedTxt = "selected=\"selected\"";
                                                                                        ?>
                                                                                        <option value="<?php echo $row[24]; ?>" <?php echo $selectedTxt; ?>><?php echo $row[24]; ?></option>
                                                                                        <?php
                                                                                    } else {
                                                                                        $selectedTxtAllTS = "";
                                                                                        $selectedTxtAnyOneTS = "";
                                                                                        $selectedTxtAnyTwoTS = "";
                                                                                        $selectedTxtAnyThreeTS = "";
                                                                                        $selectedTxtAnyFourTS = "";
                                                                                        $selectedTxtAnyBothTS = "";
                                                                                        if ($row[24] === "All to sign") {
                                                                                            $selectedTxtAllTS = "selected=\"selected\"";
                                                                                        } else if ($row[24] === "Anyone to sign") {
                                                                                            $selectedTxtAnyOneTS = "selected=\"selected\"";
                                                                                        } else if ($row[24] === "Any two to sign") {
                                                                                            $selectedTxtAnyTwoTS = "selected=\"selected\"";
                                                                                        } else if ($row[24] === "Any three to sign") {
                                                                                            $selectedTxtAnyThreeTS = "selected=\"selected\"";
                                                                                        } else if ($row[24] === "Any four to sign") {
                                                                                            $selectedTxtAnyFourTS = "selected=\"selected\"";
                                                                                        } else if ($row[24] === "Both to sign") {
                                                                                            $selectedTxtAnyBothTS = "selected=\"selected\"";
                                                                                        }
                                                                                        ?>
                                                                                        <option value="All to sign" <?php echo $selectedTxtAllTS; ?>>All to sign</option>
                                                                                        <option value="Anyone to sign" <?php echo $selectedTxtAnyOneTS; ?>>Anyone to sign</option>
                                                                                        <option value="Any two to sign" <?php echo $selectedTxtAnyTwoTS; ?>>Any two to sign</option>
                                                                                        <option value="Any three to sign" <?php echo $selectedTxtAnyThreeTS; ?>>Any three to sign</option>
                                                                                        <option value="Any four to sign" <?php echo $selectedTxtAnyFourTS; ?>>Any four to sign</option>
                                                                                        <option value="Both to sign" <?php echo $selectedTxtAnyBothTS; ?>>Both to sign</option>
                                                                                        <?php
                                                                                    }
                                                                                    ?>	
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-4">
                                                                        <?php if($trnsStatus == "Incomplete" || $trnsStatus == "Rejected" || $trnsStatus == "Withdrawn"){ ?>
                                                                        <button type="button" class="btn btn-default" id="getSigntryBtn" style="margin-bottom: 5px;" onclick="getSignatoryForm('myLovModal', 'myLovModalBody', 'myLovModalTitle', 'acctSignatoryForm', '', 'Add Signatory', 13, <?php echo $subPgNo; ?>, 5, 'ADD', - 1);">
                                                                            <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                            Add Signatory
                                                                        </button>
                                                                        <?php } ?>
                                                                    </div>
                                                                </div>
                                                                <table id="acctSignatoryTblAdd" class="table table-striped table-bordered table-responsive" cellspacing="0" width="100%" style="width:100%;">
                                                                    <thead>
                                                                        <tr>
                                                                            <?php if($trnsStatus == "Incomplete" || $trnsStatus == "Rejected" || $trnsStatus == "Withdrawn"){ ?>
                                                                            <th>...</th>
                                                                            <th>...</th>
                                                                            <?php } ?>
                                                                            <th>No.</th>
                                                                            <th>Signatory</th>
                                                                            <th>ID No.</th>
                                                                            <th>Sign?</th>
                                                                            <th>...</th>
                                                                            <th style="display:none;">Src Type</th>
                                                                            <th style="display:none;">SignatoryID</th>
                                                                            <th style="display:none;">End Date</th>  
                                                                            <th <?php echo $shwHydNtlntySts; ?>>Status</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <?php
                                                                        $cnta = 0;
                                                                        $result2 = get_CustAccount_Signatories($pkID);
                                                                        while ($row2 = loc_db_fetch_array($result2)) {
                                                                            $cnta = $cnta + 1;
                                                                            $row1ATZ0 = ""; $row1ATZ1 = ""; $row1ATZ2 = ""; $row1ATZ3 = ""; $row1ATZ4 = ""; $row1ATZ5 = "";
                                                                            $row1ATZ6 = ""; $row1ATZ7 = "";
                                                                            if($row2[0] > 0 && $row2[7] === "Yes"){
                                                                                $result1ATZ = get_CustAccount_SignatoriesATZ($row2[0], $row2[8]);
                                                                                while ($row1ATZ = loc_db_fetch_array($result1ATZ)) {
                                                                                    $row1ATZ0 = $row1ATZ[0]; $row1ATZ1 = $row1ATZ[1]; $row1ATZ2 = $row1ATZ[2]; $row1ATZ3 = $row1ATZ[3];
                                                                                    $row1ATZ4 = $row1ATZ[4]; $row1ATZ5 = $row1ATZ[5]; $row1ATZ6 = $row1ATZ[6];
                                                                                }
                                                                            }
                                                                            ?>
                                                                            <tr id="acctSignatoryTblAddRow<?php echo $row2[0]; ?>">
                                                                                <?php if($trnsStatus == "Incomplete" || $trnsStatus == "Rejected" || $trnsStatus == "Withdrawn"){ ?>
                                                                                <td class="lovtd"><button type="button" class="btn btn-default btn-sm" onclick="getSignatoryForm('myLovModal', 'myLovModalBody', 'myLovModalTitle', 'acctSignatoryForm', '', 'Edit Signatory', 13, <?php echo $subPgNo; ?>, 5, 'EDIT',<?php echo $row2[0]; ?>);" style="padding:2px !important;">
                                                                                        <img src="cmn_images/edit32.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                                    </button>
                                                                                </td>
                                                                                <td class="lovtd"><button type="button" class="btn btn-default btn-sm" onclick="deleteCustAccntsSignatory(<?php echo $row2[0]; ?>,<?php echo $pkID; ?>,'<?php echo $row2[7]; ?>');" style="padding:2px !important;">
                                                                                        <img src="cmn_images/delete.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                                    </button>
                                                                                </td>
                                                                                <?php } ?>
                                                                                <td class="lovtd"><?php echo $cnta; ?></td>
                                                                                <td class="lovtd"><?php echo dsplyTblData($row2[1], $row1ATZ1, $row2[0], $row2[7]); ?></td>
                                                                                <td class="lovtd"><?php echo dsplyTblData($row2[2], $row1ATZ2, $row2[0], $row2[7]); ?></td>
                                                                                <td class="lovtd"><?php echo dsplyTblData($row2[3], $row1ATZ3, $row2[0], $row2[7]); ?></td>
                                                                                <td class="lovtd">
                                                                                    <button type="button" class="btn btn-default btn-sm" onclick="viewSignatoryForm('myLovModal', 'myLovModalBody', 'myLovModalTitle', 'acctSignatoryForm', '', 'Signatory', 13, <?php echo $subPgNo; ?>, 5, 'VIEW',<?php echo $row2[0]; ?>);" style="padding:2px !important;">
                                                                                        <img src="cmn_images/kghostview.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                                    </button>
                                                                                </td>
                                                                                <td style="display:none;"><?php echo $row2[4]; ?></td>
                                                                                <td style="display:none;"><?php echo $row2[6]; ?></td> 
                                                                                <td style="display:none;"><?php echo $row2[5]; ?></td>
                                                                                <td <?php echo $shwHydNtlntySts; ?>>
                                                                                    <?php 
                                                                                    if($row2[0] < 0){
                                                                                        echo "<span style='color:red;'><b>Deleted</b></span>";
                                                                                    } else  {
                                                                                       if($row2[7] === "No"){
                                                                                            echo "<span style='color:blue;'><b>New</b></span>";
                                                                                       } else {
                                                                                           echo "&nbsp;";
                                                                                       }
                                                                                    }
                                                                                    ?>
                                                                                </td>
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
                                                <div class="row" style="display:block;"><!--ROW 4-- LIENS -->
                                                    <div class="col-lg-12"> 
                                                        <legend class="basic_person_lg" style="margin-top:10px !important">Account Liens</legend> 
                                                        <div  class="col-md-12">
                                                            <div class="row"><!-- ROW 3 -->
                                                                <div style="text-align:left !important;">
                                                                    <?php if(test_prmssns($dfltPrvldgs[87], $mdlNm) === true && ($trnsStatus == "Incomplete" || $trnsStatus == "Rejected" || $trnsStatus == "Withdrawn")) { ?>
                                                                    <button type="button" class="btn btn-default" id="getSigntryBtn" style="margin-bottom: 5px;" onclick="getLienForm('myLovModal', 'myLovModalBody', 'myLovModalTitle', 'acctLienForm', '', 'Add Lien', 13, <?php echo $subPgNo; ?>, 6, 'ADD', - 1);">
                                                                        <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                        Add Lien
                                                                    </button>
                                                                    <?php } ?>
                                                                </div>
                                                            </div>
                                                            <table id="acctLiensTblAdd" class="table table-striped table-bordered table-responsive" cellspacing="0" width="100%" style="width:100%;">
                                                                <thead>
                                                                    <tr>
                                                                        <?php if($trnsStatus == "Incomplete" || $trnsStatus == "Rejected" || $trnsStatus == "Withdrawn"){ if(test_prmssns($dfltPrvldgs[88], $mdlNm) === true) { ?>
                                                                        <th>...</th>
                                                                        <?php } if(test_prmssns($dfltPrvldgs[89], $mdlNm) === true) { ?>
                                                                        <th>...</th>
                                                                        <?php } } ?>
                                                                        <th>No.</th>
                                                                        <th>Lien ID</th>
                                                                        <th>Amount</th>
                                                                        <th>Start Date</th>
                                                                        <th>End Date</th>
                                                                        <th>Lien Status</th>
                                                                        <th>Narration</th>
                                                                        <th <?php echo $shwHydNtlntySts; ?>>Status</th>
                                                                        <th style="display:none;">...</th>
                                                                        <th style="display:none;">Lien ID</th>                                                                        
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                        <?php
                                                                        $cnta = 0;
                                                                        $result2 = get_CustAccount_Liens($pkID);
                                                                        while ($row2 = loc_db_fetch_array($result2)) {
                                                                            $cnta = $cnta + 1;
                                                                            $row1ATZ0 = ""; $row1ATZ1 = ""; $row1ATZ2 = ""; $row1ATZ3 = ""; $row1ATZ4 = ""; $row1ATZ5 = "";
                                                                            $row1ATZ6 = ""; $row1ATZ7 = ""; $row1ATZ8 = ""; $row1ATZ9 = ""; 
                                                                            if($row2[0] > 0 && $row2[9] === "Yes"){
                                                                                $result1ATZ = get_CustAccount_LiensATZ($row2[0]);
                                                                                while ($row1ATZ = loc_db_fetch_array($result1ATZ)) {
                                                                                    $row1ATZ0 = $row1ATZ[0]; $row1ATZ1 = $row1ATZ[1]; $row1ATZ2 = $row1ATZ[2]; $row1ATZ3 = $row1ATZ[3];
                                                                                    $row1ATZ4 = $row1ATZ[4]; $row1ATZ5 = $row1ATZ[5]; $row1ATZ6 = $row1ATZ[6]; $row1ATZ7 = $row1ATZ[7];
                                                                                    $row1ATZ8 = $row1ATZ[8]; 
                                                                                }
                                                                            }
                                                                            ?>
                                                                        <tr id="acctLiensTblAddRow<?php echo $row2[0]; ?>">
                                                                            <?php if($trnsStatus == "Incomplete" || $trnsStatus == "Rejected" || $trnsStatus == "Withdrawn"){ if(test_prmssns($dfltPrvldgs[88], $mdlNm) === true) { ?>
                                                                            <td class="lovtd">
                                                                                <?php if((int)$row2[10] <= 0 || $row2[6] == "Removed") { ?>
                                                                                <button type="button" class="btn btn-default btn-sm" onclick="getLienForm('myLovModal', 'myLovModalBody', 'myLovModalTitle', 'acctLienForm', '', 'Edit Lien', 13, <?php echo $subPgNo; ?>, 6, 'EDIT', <?php echo $row2[0]; ?>);" style="padding:2px !important;">
                                                                                    <img src="cmn_images/edit32.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                                </button>
                                                                                <?php } ?>
                                                                            </td>
                                                                             <?php } if(test_prmssns($dfltPrvldgs[89], $mdlNm) === true) { ?>    
                                                                            <td class="lovtd">
                                                                                <?php if((int)$row2[10] <= 0  || $row2[6] == "Removed") { ?>
                                                                                <button type="button" class="btn btn-default btn-sm" onclick="deleteCustLienRcrd(<?php echo $row2[0]; ?>,<?php echo $pkID; ?>, '<?php echo $row2[6]; ?>','<?php echo $row2[9]; ?>');" style="padding:2px !important;">
                                                                                    <img src="cmn_images/delete.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                                </button>
                                                                                <?php } ?>
                                                                            </td>
                                                                            <?php } } ?>
                                                                            <td class="lovtd"><?php echo $cnta; ?></td>
                                                                            <td class="lovtd"><?php echo dsplyTblData($row2[1], $row1ATZ1, $row2[0], $row2[9]); ?></td>
                                                                            <td class="lovtd"><?php echo dsplyTblData($row2[2], $row1ATZ2, $row2[0], $row2[9]); ?></td>
                                                                            <td class="lovtd"><?php echo dsplyTblData($row2[3], $row1ATZ3, $row2[0], $row2[9]); ?></td>
                                                                            <td class="lovtd"><?php echo dsplyTblData($row2[4], $row1ATZ4, $row2[0], $row2[9]); ?></td>
                                                                            <td class="lovtd"><?php echo dsplyTblData($row2[6], $row1ATZ6, $row2[0], $row2[9]); ?></td>
                                                                            <td class="lovtd"><?php echo dsplyTblData($row2[5], $row1ATZ5, $row2[0], $row2[9]); ?></td>
                                                                            <td <?php echo $shwHydNtlntySts; ?>>
                                                                                <?php 
                                                                                if($row2[0] < 0){
                                                                                    echo "<span style='color:red;'><b>Deleted</b></span>";
                                                                                } else  {
                                                                                   if($row2[9] === "No"){
                                                                                        echo "<span style='color:blue;'><b>New</b></span>";
                                                                                   } else {
                                                                                       echo "&nbsp;";
                                                                                   }
                                                                                }
                                                                                ?>
                                                                            </td>
                                                                            <td style="display:none;"><button type="button" class="btn btn-default btn-sm" onclick="getSvngsWtdwlForm(\'myLovModal\', \'myLovModalBody\', \'myLovModalTitle\', \'svngsWtdwlCmsnForm\', \'svngsWtdwlCmsnTblAddRow' + rowValID + '\', \'Edit Withdrawal Commission\', 12,7.1, 5, \'EDIT\',' + rowValID + ');" style="padding:2px !important;">
                                                                                    <img src="cmn_images/kghostview.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                                </button></td>
                                                                            <td style="display:none;"><?php echo $row2[7]; ?></td>
                                                                        </tr>
                        <?php
                    }
                    ?>                                                                    
                                                                </tbody>
                                                            </table>                                                    
                                                        </div>
                                                    </div>
                                                </div>                                                
                                                <div class="row" style="display:none;"><!--ROW 5-- AVAILABLE IN EDIT MODE -->
                                                    <div class="col-lg-12"> 
                                                        <fieldset class="basic_person_fs3" style="padding: 1px !important;"><legend class="basic_person_lg">Data Change History</legend> 
                                                            <div  class="col-md-12">
                                                                <table id="wtdwlCmsnTblAdd" class="table table-striped table-bordered table-responsive" cellspacing="0" width="100%" style="width:100%;">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>...</th>
                                                                            <th>No.</th>
                                                                            <th>Low Range</th>
                                                                            <th>High Range</th>
                                                                            <th>Amount Flat</th>
                                                                            <th>Amount Percent</th>
                                                                            <th>Remarks</th>
                                                                            <th style="display:none;">Remarks</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                    <?php
                    $result1 = get_SavingsWtdwlCmmsn(1);
                    $cntr = 0;
                    while ($row1 = loc_db_fetch_array($result1)) {
                        $cntr++;
                        ?>
                                                                            <tr id="svngsWtdwlCmsnTblAddRow<?php echo $cntr; ?>">
                                                                                <td class="lovtd">
                                                                                    <button type="button" class="btn btn-default btn-sm" onclick="getSvngsWtdwlForm('myLovModal', 'myLovModalBody', 'myLovModalTitle', 'svngsWtdwlCmsnForm', 'svngsWtdwlCmsnTblAddRow<?php echo $cntr; ?>', 'Edit Withdrawal Commission', 12, <?php echo $subPgNo; ?>, 5, 'EDIT', <?php echo $row1[0]; ?>);" style="padding:2px !important;">
                                                                                            <!--<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>-->
                                                                                        <img src="cmn_images/edit32.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                                    </button>
                                                                                </td>
                                                                                <td class="lovtd">
                        <?php echo $cntr; ?>
                                                                                </td>
                                                                                <td class="lovtd">
                        <?php echo $row1[1]; ?>
                                                                                </td>
                                                                                <td class="lovtd"><?php echo $row1[2]; ?></td>
                                                                                <td class="lovtd"><?php echo $row1[3]; ?></td>
                                                                                <td class="lovtd"><?php echo $row1[4]; ?></td>
                                                                                <td class="lovtd"><?php echo $row1[5]; ?></td>
                                                                                <td style="display:none;"><?php echo $row1[0]; ?></td>
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
                        </div>          
                    </div>
                    <?php
                }
            } else if ($vwtypActn === "ADD") {

                $prsnBranchID = get_Person_BranchID($prsnid);
                $prsnBranch = getGnrlRecNm("org.org_sites_locations", "location_id", "site_desc||' ('||location_code_name||')'", "pasn.get_prsn_siteid($prsnid)");

                /* Add */
                ?>
                <input class="form-control" id="addOrEditForm" type = "hidden" placeholder="addOrEditForm" value="Add"/>
                <div class="">
                    <div class="row">                  
                        <div class="col-md-12">
                            <div class="custDiv" style="border:none !important; padding-top:0px !important;"> 
                                <div class="tab-content">
                                    <div id="prflCAHomeEDT" class="tab-pane fadein active" style="border:none !important;">  
                                        <div class="row" style="margin: 0px 0px 10px 0px !important;">
                                            <div class="col-md-8" style="padding:0px 0px 0px 15px !important;">&nbsp;</div>
                                            <div class="col-md-4" style="padding:0px 1px 0px 1px !important;"><div style="text-align: right !important;">
                                                    <button type="button" id="saveCustAccountBtn" class="btn btn-primary btn-sm" onclick="saveCustAccountsData(<?php echo $pgNo; ?>, <?php echo $subPgNo; ?>, <?php echo $vwtyp; ?>, 0);"><img src="cmn_images/FloppyDisk.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">SAVE</button>
                                                    <button type="button" id="withdrawCustAccountBtn" style="display:none !important;" class="btn btn-success btn-sm"  onclick="submitWithdrawCustAccnt(<?php echo $pgNo; ?>, <?php echo $subPgNo; ?>, <?php echo $vwtyp; ?>, 'WITHDRAW');"><img src="cmn_images/withdraw_rqst.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">WITHDRAW REQUEST</button>
                                                    <button type="button" id="submitCustAccountBtn" style="display:none !important;" onclick="saveCustAccountsData(<?php echo $pgNo; ?>, <?php echo $subPgNo; ?>, <?php echo $vwtyp; ?>, 1);" class="btn btn-success btn-sm" ><img src="cmn_images/Emailcon.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">SUBMIT</button></div></div>                                            
                                        </div>                                        
                                        <form class="form-horizontal" id="customerAccountForm">
                                            <div class="row"><!-- ROW 1 -->
                                                <div class="col-lg-12"> 
                                                    <!--<fieldset class="basic_person_fs5" style="padding: 1px !important;">--> 
                                                    <legend class="basic_person_lg" style="margin-bottom: 5px !important;">Customer</legend>
                                                    <div  class="col-md-3">
                                                        <div class="form-group form-group-sm">
                                                            <label for="custType" class="control-label col-md-4">Type:</label>
                                                            <div  class="col-md-8">
                                                                <select class="form-control" id="custType" onchange="loadPrsnTypeEntiries(this)">
                                                                    <option value="--Please Select--">--Please Select--</option>
                <?php
                $brghtStr = "";
                $isDynmyc = FALSE;
                $titleRslt = getLovValues("%", "Both", 0, 100, $brghtStr, getLovID("Bank Customer Types"), $isDynmyc, -1, "", "");
                while ($titleRow = loc_db_fetch_array($titleRslt)) {
                    $selectedTxt = "";
                    ?>
                                                                        <option value="<?php echo $titleRow[0]; ?>" <?php echo $selectedTxt; ?>><?php echo $titleRow[0]; ?></option>
                                                                        <?php
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                        </div>                                                                                                                           
                                                    </div>
                                                    <div  class="col-md-6">
                                                        <div class="form-group form-group-sm">
                                                            <label for="bnkCustomer" class="control-label col-md-2">Name:</label>
                                                            <div  class="col-md-9">
                                                                <div class="input-group">
                                                                    <input type="text" class="form-control" aria-label="..." id="bnkCustomer" value="" readonly>
                                                                    <input type="hidden" id="bnkCustomerID" value="-1">
                                                                    <input type="hidden" id="gnrlOrgID" value="<?php echo $orgID; ?>">
                                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="onClickCustName();">
                                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-1" style="padding:0px 0px 0px 0px !important;">&nbsp;</div>
                                                        </div>
                                                    </div>
                                                    <div  class="col-md-3">
                                                        <div class="col-md-12" style="padding:0px 1px 0px 1px !important;"><button type="button" class="btn btn-info btn-sm" style="width:100% !important;" onclick="viewCustProfile();"><img src="cmn_images/kghostview.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">VIEW CUSTOMER PROFILE</button></div> 
                                                    </div>
                                                    <!--</fieldset>-->
                                                </div>
                                            </div>                                            
                                            <div class="row"><!-- ROW 2 -->
                                                <div class="col-lg-6">
                                                    <fieldset class="basic_person_fs1" style="margin-top: 5px !important;"><legend class="basic_person_lg">Account</legend>
                                                        <div class="form-group form-group-sm">
                                                            <label for="acctNumber" class="control-label col-md-4">Number:</label>
                                                            <div class="col-md-8">
                                                                <input class="form-control" id="acctNumber" type = "text" placeholder="" value="" readonly/>
                                                                <!--CUSTOMER ID-->
                                                                <input class="form-control" id="acctID" type = "hidden" placeholder="Account ID" value="-1"/>                                                                                                                                           
                                                            </div>
                                                        </div> 
                                                        <div class="form-group form-group-sm">
                                                            <label for="acctTitle" class="control-label col-md-4">Account Title:</label>
                                                            <div  class="col-md-8">
                                                                <textarea class="form-control rqrdFld" id="acctTitle" cols="2" placeholder="Account Title" rows="3"></textarea>
                                                            </div>                                                         
                                                        </div>     
                                                        <div class="form-group form-group-sm">
                                                            <label for="acctType" class="control-label col-md-4">Account Type:</label>
                                                            <div  class="col-md-8">
                                                                <select class="form-control" id="acctType" >
                                                                    <option value="--Please Select--">--Please Select--</option>
                <?php
                $brghtStr = "";
                $isDynmyc = FALSE;
                $titleRslt = getLovValues("%", "Both", 0, 100, $brghtStr, getLovID("Bank Account Types"), $isDynmyc, -1, "", "");
                while ($titleRow = loc_db_fetch_array($titleRslt)) {
                    $selectedTxt = "";
                    ?>
                                                                        <option value="<?php echo $titleRow[0]; ?>" <?php echo $selectedTxt; ?>><?php echo $titleRow[0]; ?></option>
                                                                        <?php
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-group-sm">
                                                            <label for="prdtType" class="control-label col-md-4">Product:</label>
                                                            <div  class="col-md-8">
                                                                <div class="input-group">
                                                                    <input type="text" class="form-control" aria-label="..." id="prdtType" value="" readonly>
                                                                    <input type="hidden" id="prdtTypeID" value="-1">
                                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="onClickSvngsProduct(<?php echo $pgNo; ?>, <?php echo $subPgNo; ?>);">
                                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                                    </label>
                                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="dsplyPrdtForm();" data-toggle="tooltip" data-placement="bottom" title = "View Product">
                                                                        <span class="glyphicon glyphicon-info-sign"></span>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div> 
                                                        <div class="form-group form-group-sm">
                                                            <label for="acctTrnsTyp" id="acctTrnsTypLbl" class="control-label col-md-4">Transaction Type:</label>
                                                            <div  class="col-md-8">
                                                                <select class="form-control" name="acctTrnsTyp[]" id="acctTrnsTyp" multiple>
                                                                    <!--<option value="">&nbsp;</option>-->
                <?php
                $brghtStr = "";
                $isDynmyc = FALSE;
                $titleRslt = getLovValues("%", "Both", 0, 100, $brghtStr, getLovID("Account Transaction Types"), $isDynmyc, -1, "", "");
                while ($titleRow = loc_db_fetch_array($titleRslt)) {
                    $selectedTxt = "";
                    ?>
                                                                        <option value="<?php echo $titleRow[0]; ?>" <?php echo $selectedTxt; ?>><?php echo $titleRow[0]; ?></option>
                                                                        <?php
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                        </div>                                                         
                                                        <div class="form-group form-group-sm">
                                                            <label for="prsnTypeEntity" class="control-label col-md-4">Person Type/Entity:</label>
                                                            <div  class="col-md-8">
                                                                <select class="form-control" id="prsnTypeEntity" >
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-group-sm">
                                                            <label for="prdtCrncy" class="control-label col-md-4">Currency:</label>
                                                            <div class="col-md-8">
                                                                <input class="form-control" id="prdtCrncyIsoCode" type = "text" placeholder="Currency" value="" readonly>
                                                                <!--CUSTOMER ID-->
                                                                <input class="form-control" id="prdtCrncy" type = "hidden" placeholder="Currency ID" value="-1">                                                                                                                                           
                                                            </div>
                                                        </div>
                                                    </fieldset>
                                                </div>                                
                                                <div class="col-lg-6">
                                                    <fieldset class="basic_person_fs1" style="margin-top: 5px !important;"><legend class="basic_person_lg">General</legend>
                                                        <div class="form-group form-group-sm">
                                                            <label for="prpsOfAcct" id="prpsOfAcctLbl" class="control-label col-md-4">Purpose:</label>
                                                            <div class="col-md-8">
                                                                <select class="form-control" name="prpsOfAcct[]" id="prpsOfAcct" multiple style="width:206px;">
                                                                    <!--<option value="">&nbsp;</option>-->
                <?php
                $brghtStr = "";
                $isDynmyc = FALSE;
                $titleRslt = getLovValues("%", "Both", 0, 100, $brghtStr, getLovID("Purpose of Account"), $isDynmyc, -1, "", "");
                while ($titleRow = loc_db_fetch_array($titleRslt)) {
                    $selectedTxt = "";
                    ?>
                                                                        <option value="<?php echo $titleRow[0]; ?>" <?php echo $selectedTxt; ?>><?php echo $titleRow[0]; ?></option>
                                                                        <?php
                                                                    }
                                                                    ?>
                                                                </select>                                                                                                                                          
                                                            </div>
                                                        </div> 
                                                        <div class="form-group form-group-sm">
                                                            <label for="prpsOfAcctOther" class="control-label col-md-4">Purpose - Other:</label>
                                                            <div  class="col-md-8">
                                                                <textarea class="form-control" id="prpsOfAcctOther" cols="2" placeholder="Purpose - Other" rows="3"></textarea>
                                                            </div>
                                                        </div> 
                                                        <div class="form-group form-group-sm">
                                                            <label for="srcOfFunds" id="srcOfFundsLbl" class="control-label col-md-4">Source of Funds:</label>
                                                            <div class="col-md-8">
                                                                <select class="form-control" name="srcOfFunds" id="srcOfFunds" multiple>
                                                                    <!--<option value="">&nbsp;</option>-->
                <?php
                $brghtStr = "";
                $isDynmyc = FALSE;
                $titleRslt = getLovValues("%", "Both", 0, 100, $brghtStr, getLovID("Source of Funds"), $isDynmyc, -1, "", "");
                while ($titleRow = loc_db_fetch_array($titleRslt)) {
                    $selectedTxt = "";
                    ?>
                                                                        <option value="<?php echo $titleRow[0]; ?>" <?php echo $selectedTxt; ?>><?php echo $titleRow[0]; ?></option>
                    <?php
                }
                ?>
                                                                </select>                                                                                                                                          
                                                            </div>
                                                        </div> 
                                                        <div class="form-group form-group-sm">
                                                            <label for="srcOfFundsOther" class="control-label col-md-4">Source of Funds - Other:</label>
                                                            <div  class="col-md-8">
                                                                <textarea class="form-control" id="srcOfFundsOther" cols="2" placeholder="Source of Funds - Other" rows="3"></textarea>
                                                            </div>
                                                        </div>                                                        
                                                        <div class="form-group form-group-sm">
                                                            <label for="trnsPerMnthNo" class="control-label col-md-6">Expected Monthly Transaction Volume:</label>
                                                            <div  class="col-md-6">
                                                                <input class="form-control rqrdFld" id="trnsPerMnthNo" type = "number" min="0" placeholder="Monthly Trans. Volume" value=""/>
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-group-sm">
                                                            <label for="amountPerMnth" class="control-label col-md-6">Expected Monthly Amount:</label>
                                                            <div  class="col-md-6">
                                                                <input class="form-control rqrdFld" id="amountPerMnth" type = "number" min="0" placeholder="Monthly Amount" value=""/>
                                                            </div>
                                                        </div>                                                        
                                                    </fieldset>
                                                </div>
                                            </div>  
                                            <div class="row"><!-- ROW 3 -->
                                                <div class="col-lg-6">
                                                    <fieldset class="basic_person_fs1"><legend class="basic_person_lg">Account</legend>
                                                        <div class="form-group form-group-sm">
                                                            <label for="bnkBranch" class="control-label col-md-4">Branch:</label>
                                                            <div  class="col-md-8">
                                                                <div class="input-group" style="width:100% !important;">
                                                                    <input type="text" class="form-control" aria-label="..." id="bnkBranch" value="<?php echo $prsnBranch; ?>" readonly>
                                                                    <input type="hidden" id="bnkBranchID" value="<?php echo $prsnBranchID; ?>"> 
                                                                    <input type="hidden" id="gnrlOrgID" value="<?php echo $orgID; ?>">
                                                                    <label style="display:none !important;" class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Sites/Locations New', 'gnrlOrgID', '', '', 'radio', true, '', 'bnkBranchID', 'bnkBranch', 'clear', 1, '');">
                                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>                                                        
                                                        <div class="form-group form-group-sm">
                                                            <label for="reltnOfficer" class="control-label col-md-4">Relationship Officer:</label>
                                                            <div  class="col-md-8">
                                                                <div class="input-group">
                                                                    <input type="text" class="form-control" aria-label="..." id="reltnOfficer" value="" readonly>
                                                                    <input type="hidden" id="reltnOfficerID" value="-1">
                                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Active Persons', '', '', '', 'radio', true, '', 'reltnOfficerID', 'reltnOfficer', 'clear', 1, '');">
                                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-group-sm">
                                                            <label for="statusReason" class="control-label col-md-4">Comments:</label>
                                                            <div  class="col-md-8">
                                                                <textarea class="form-control" id="closeReason" cols="2" placeholder="Comments" rows="2"></textarea>
                                                            </div>
                                                        </div>                                                        
                                                        <div class="form-group form-group-sm" style="display: none !important;">
                                                            <label for="status" class="control-label col-md-4">Status:</label>
                                                            <div class="col-md-8">
                                                                <input class="form-control" id="status" type = "text" placeholder="" value="Incomplete" readonly/>                                                                                                                                        
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-group-sm" style="display:none;">
                                                            <label for="startDate" class="control-label col-md-4">Status Effective Date:</label>
                                                            <div  class="col-md-8">
                                                                <input class="form-control" size="16" type="text" id="startDate" value="<?php echo getDB_Trns_time(); ?>" readonly="">
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-group-sm" style="display:none !important;">
                                                            <label for="ovdrftBal" class="control-label col-md-4">Overdraft Balance:</label>
                                                            <div  class="col-md-8">
                                                                <input class="form-control" id="ovdrftBal" type = "number" min="0" placeholder="Overdraft Balance" value="0.00" readonly/>
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-group-sm">
                                                            <label for="grpType" class="control-label col-md-4">Allowed Group Type:</label>
                                                            <div  class="col-md-8">
                                                                <select class="form-control" id="grpType" onchange="grpTypNoticesChange();">
                                                                    <option value="Everyone">Everyone</option>                                            
                                                                    <option value="Divisions/Groups">Divisions/Groups</option>
                                                                    <option value="Grade">Grade</option>
                                                                    <option value="Job">Job</option>
                                                                    <option value="Position">Position</option>
                                                                    <option value="Site/Location">Site/Location</option>
                                                                    <option value="Person Type">Person Type</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-group-sm">
                                                            <label for="groupName" class="control-label col-md-4">Allowed Group Name:</label>
                                                            <div  class="col-md-8">
                                                                <div class="input-group">
                                                                    <input type="text" class="form-control" aria-label="..." id="groupName" value="" readonly="">
                                                                    <input type="hidden" id="gnrlOrgID" value="<?php echo $orgID; ?>">
                                                                    <input type="hidden" id="groupID" value="-1">
                                                                    <label disabled="true" id="groupNameLbl" class="btn btn-primary btn-file input-group-addon" onclick="getNoticeLovs('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Customers and Suppliers', 'gnrlOrgID', '', '', 'radio', true, '', 'groupID', 'groupName', 'clear', 1, '');">
                                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-group-sm">
                                                            <label for="canOverdrawAcct" class="control-label col-md-4">Can Overdraw Account?:</label>
                                                            <div  class="col-md-8">
                                                                <select class="form-control" id="canOverdrawAcct" onchange="">
                                                                    <option value="No">No</option>                                            
                                                                    <option value="Yes">Yes</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </fieldset>
                                                </div>                                
                                                <div class="col-lg-6">
                                                    <fieldset class="basic_person_fs1"><legend class="basic_person_lg">Signatories & Mandate</legend>
                                                        <div  class="col-md-12">
                                                            <div class="row"><!-- ROW 3 -->
                                                                <div class="col-lg-8">
                                                                    <div class="row">
                                                                        <div class="col-lg-10">
                                                                            <div class="form-group form-group-sm">
                                                                                <label for="accMndte" class="control-label col-md-4">Mandate:</label>
                                                                                <div  class="col-md-8">
                                                                                    <select class="form-control" id="accMndte" >
                                                                                    </select>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-lg-2">
                                                                            <button style="display:none !important;" type="button" class="btn btn-default" id="attchmnt" style="margin-bottom: 5px;" onclick="" title="Add Attachment">
                                                                                <img src="cmn_images/adjunto.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-4">
                                                                    <button style="display:none !important;" type="button" class="btn btn-default" id="getSigntryBtn" style="margin-bottom: 5px;" onclick="getSignatoryForm('myLovModal', 'myLovModalBody', 'myLovModalTitle', 'acctSignatoryForm', '', 'Add Signatory', 13, <?php echo $subPgNo; ?>, 5, 'ADD', - 1);">
                                                                        <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                        Add Signatory
                                                                    </button>
                                                                </div>
                                                            </div>
                                                            <table id="acctSignatoryTblAdd" class="table table-striped table-bordered table-responsive" cellspacing="0" width="100%" style="width:100%;">
                                                                <thead>
                                                                    <tr>
                                                                        <th>...</th>
                                                                        <th>...</th>
                                                                        <th>No.</th>
                                                                        <th>Signatory</th>
                                                                        <th>ID No.</th>
                                                                        <th>Sign?</th>
                                                                        <th>...</th>
                                                                        <th style="display:none;">Src Type</th>
                                                                        <th style="display:none;">SignatoryID</th>
                                                                        <th style="display:none;">End Date</th>                                                                        
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </fieldset>
                                                </div>
                                            </div> 
                                            <div class="row" style="display:block;"><!--ROW 4-- LIENS -->
                                                <div class="col-lg-12"> 
                                                    <legend class="basic_person_lg" style="margin-top:10px !important">Account Liens</legend> 
                                                    <div  class="col-md-12">
                                                        <div class="row"><!-- ROW 3 -->
                                                            <div style="text-align:left !important;">
                                                                <button style="display:none !important;" type="button" class="btn btn-default" id="getSigntryBtn" style="margin-bottom: 5px;" onclick="getLienForm('myLovModal', 'myLovModalBody', 'myLovModalTitle', 'acctLienForm', '', 'Add Lien', 13, <?php echo $subPgNo; ?>, 6, 'ADD', - 1);">
                                                                    <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                    Add Lien
                                                                </button>
                                                            </div>
                                                        </div>
                                                        <table id="acctLiensTblAdd" class="table table-striped table-bordered table-responsive" cellspacing="0" width="100%" style="width:100%;">
                                                            <thead>
                                                                <tr>
                                                                    <th>...</th>
                                                                    <th>...</th>
                                                                    <th>No.</th>
                                                                    <th>Lien ID</th>
                                                                    <th>Amount</th>
                                                                    <th>Start Date</th>
                                                                    <th>End Date</th>
                                                                    <th>Lien Status</th>
                                                                    <th>Narration</th>
                                                                    <th style="display:none;">...</th>
                                                                    <th style="display:none;">Lien ID</th>                                                                        
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                            </tbody>
                                                        </table>                                                    
                                                    </div>
                                                </div>
                                            </div>
                                        </form>  
                                    </div>     
                                </div>                        
                            </div>                         
                        </div>                
                    </div>          
                </div>
                <?php
            } else if ($vwtypActn == "VIEW") {
                /* Read Only */

                $acctNo = isset($_POST['acctNo']) ? cleanInputData($_POST['acctNo']) : '';

                //var_dump($_POST);

                if ($pkID === "" || (float) $pkID <= 0) {
                    if (trim($acctNo) === "" || $acctNo === "undefined") {
                        echo '<div><img src="cmn_images/error.gif" style="float:left;margin-right:5px;width:30px;height:30px;"/>'
                        . 'Please provide an Account Number before proceeding!<br/></div>';
                        exit();
                    } else {
                        $valRslt = validateAccountNo($acctNo);
                        if ($valRslt == -1) {
                            echo '<div><img src="cmn_images/error.gif" style="float:left;margin-right:5px;width:30px;height:30px;"/>'
                            . 'INVALID ACCOUNT NUMBER!<br/></div>';
                            exit();
                        }

                        $pkID = getGnrlRecID2("mcf.mcf_accounts", "account_number", "account_id", $acctNo);
                    }
                }
                
                $tblNm1 = "mcf.mcf_accounts";

                $cnt = getCustAcctDataChngPndngCount($pkID);
                if($cnt > 0){
                    $tblNm1 = "mcf.mcf_accounts_hstrc";        
                }                  
                
                $result = getCustAccountDets($pkID, $tblNm1); 
                $shwHydNtlntySts = "style=\"display:none !important;\"";

                while ($row = loc_db_fetch_array($result)) {
                    $branch = getGnrlRecNm("org.org_sites_locations", "location_id", "site_desc||' ('||location_code_name||')'", $row[19]);
                    $relOfficerNm = getGnrlRecNm("prs.prsn_names_nos", "person_id", "trim(title || ' ' || sur_name || ', ' || first_name || ' ' || other_names)", $row[20]);
                    $relOfficerLocalID = getGnrlRecNm("prs.prsn_names_nos", "person_id", "local_id_no", $row[20]);

                    $trnsStatus = $row[22];
                    $accountStatus = $row[29].", ".$row[28];
                    $accrdIntrst = $row[27];
                    
                    if($trnsStatus == "Unauthorized" || $trnsStatus == "Initiated"){
                        $shwHydNtlntySts = "style=\"display:block !important;\"";
                    }   
                    
                    $tblNmAuthrzd = "";
                    $lblColor = "red";
                    $resultAuthrzd = "";

                    if($cnt > 0){
                        $tblNmAuthrzd = "mcf.mcf_accounts";        
                        $resultAuthrzd = getCustAccountDets($pkID, $tblNmAuthrzd);
                        while ($rowATZ = loc_db_fetch_array($resultAuthrzd)) {
                            $rowATZ0 = $rowATZ[0]; $rowATZ1 = $rowATZ[1]; $rowATZ2 = $rowATZ[2]; $rowATZ3 = $rowATZ[3]; $rowATZ4 = $rowATZ[4];
                            $rowATZ5 = $rowATZ[5]; $rowATZ6 = $rowATZ[6]; $rowATZ7 = $rowATZ[7]; $rowATZ8 = $rowATZ[8]; $rowATZ9 = $rowATZ[9];
                            $rowATZ10 = $rowATZ[10]; $rowATZ11 = $rowATZ[11]; $rowATZ12 = $rowATZ[12]; $rowATZ13 = $rowATZ[13]; $rowATZ14 = $rowATZ[14];
                            $rowATZ15 = $rowATZ[15]; $rowATZ16 = $rowATZ[16]; $rowATZ17 = $rowATZ[17]; $rowATZ18 = $rowATZ[18]; $rowATZ19 = $rowATZ[19];
                            $rowATZ20 = $rowATZ[20]; $rowATZ21 = $rowATZ[21]; $rowATZ22 = $rowATZ[22]; $rowATZ23 = $rowATZ[23]; $rowATZ24 = $rowATZ[24];
                            $rowATZ25 = $rowATZ[25]; $rowATZ26 = $rowATZ[26]; $rowATZ27 = $rowATZ[27]; $rowATZ28 = $rowATZ[28]; $rowATZ29 = $rowATZ[29];
                            $rowATZ30 = $rowATZ[30]; $rowATZ31 = $rowATZ[31]; $rowATZ32 = $rowATZ[32]; $rowATZ33 = $rowATZ[33];
                            
                            $v_BranchATZ = getGnrlRecNm("org.org_sites_locations", "location_id", "site_desc||' ('||location_code_name||')' ", $rowATZ19);
                            $relOfficerNmATZ = getGnrlRecNm("prs.prsn_names_nos", "person_id", "trim(title || ' ' || sur_name || ', ' || first_name || ' ' || other_names)", $rowATZ20);
                            $relOfficerLocalIDATZ = getGnrlRecNm("prs.prsn_names_nos", "person_id", "local_id_no", $rowATZ20);
                            
                        }               
                    }                     
                    /* Edit */
                    ?>
                    <input class="form-control" id="addOrEditForm" type = "hidden" placeholder="addOrEditForm" value="Edit"/>
                    <input class="form-control" id="rvsnTtl" type = "hidden" placeholder="Revision Total" value="<?php echo $row[32]; ?>"/>
                    <div class="row" style="margin: 0px 0px 10px 0px !important;"></div>
                    <div class="">
                        <ul class="nav nav-tabs rho-hideable-tabs" style="margin-top:-10px !important;">
                            <li id="custAccnts" class="active"><a data-toggle="tab" data-rhodata="&pg=13&vtyp=0" href="#prflCAHomeEDT" id="prflBCHomeEDTtab">Basic Data</a></li>
                            <li id="trnsHstry" style="display: block;"><a data-toggle="tabajxprfledt" data-rhodata="&pg=13&vtyp=1" href="#prflCATrnsHstryEDT" onclick="openATab('#prflCATrnsHstryEDT', 'grp=17&typ=1&pg=<?php echo $pgNo; ?>&subPgNo=<?php echo $subPgNo; ?>&vtyp=1&vtypActn=VIEW&PKeyID=<?php echo $pkID; ?>');" id="prflCATrnsHstryEDTtab">Transaction History</a></li>
                        </ul>                        
                        <div class="row">                  
                            <div class="col-md-12">
                                <div class="custDiv"> 
                                    <div class="tab-content">
                                        <div id="prflCAHomeEDT" class="tab-pane fadein active" style="border:none !important;">                                           
                                            <form class="form-horizontal" id="customerAccountForm">
                                                <div class="row" style="margin: 0px 0px 5px 0px !important;">
                                                    <div class="col-md-12" style="padding:0px 1px 0px 1px !important;">
                                                        <div style="float:left;">
                                                            <button type="button" class="btn btn-default btn-sm" style="height:30px;" id="myVmsTrnsStatusBtn">
                                                                <span style="font-weight:bold;">Approval Status: </span><span style="color:<?php echo "red"; ?>;font-weight: bold;"><?php echo $trnsStatus; ?></span>
                                                            </button>
                                                            <button type="button" class="btn btn-default btn-sm" style="height:30px;" id="myVmsTrnsStatusBtn">
                                                                <span style="font-weight:bold;">Account Status: </span><span style="color:<?php echo "red"; ?>;font-weight: bold;"><?php echo $accountStatus; ?></span>
                                                            </button>
                                                            <?php if ($vwtypActn != "VIEW") { ?>
                                                                <button type="button" class="btn btn-default btn-sm" style="height:30px;" onclick="getCustAcctsForm('myFormsModalCA', 'myFormsModalBodyCA', 'myFormsModalTitleCA', 'Edit Customer Account', 13, 2.1, 0, 'EDIT', <?php echo $pkID; ?>, 'custAcctTable', 'indCustTableRow1');" data-toggle="tooltip" title="Reload Transaction">
                                                                    <img src="cmn_images/refresh.bmp" style="padding-right: 2px; height:17px; width:auto; position: relative; vertical-align: middle;">
                                                                </button>
                                                            <?php } ?>
                                                            <button type="button" class="btn btn-default" style="height:30px;" onclick="getOneMcfDocsForm_Gnrl(<?php echo $pkID; ?>, 'CUSTOMER ACCOUNTS', 140, 'Customer Accounts Attachments');" data-toggle="tooltip" data-placement="bottom" title = "Attached Documents">
                                                                <img src="cmn_images/adjunto.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                            </button>
                                                            <button type="button" class="btn btn-default btn-sm" style="height:30px;" id="myVmsTrnsStatusBtn">
                                                                <span style="font-weight:bold;">Accrued Interest: </span><span style="color:<?php echo "blue"; ?>;font-weight: bold;"><?php echo number_format($accrdIntrst, 2); ?></span>
                                                            </button>
                                                        </div>
                                                        <div class="" style="float:right;">
                                                            <?php if (($trnsStatus == "Unauthorized" || $trnsStatus == "Initiated") && (test_prmssns($dfltPrvldgs[83], $mdlNm) === true) && $vwtypActn === "VIEW" && canPrsnSeeCustAccntBranchDocs($prsnID, $pkID, $row[32])) { ?>
                                                                <button type="button" id="withdrawCustAccountBtn" style="inline-block:block !important;" class="btn btn-default btn-sm"  onclick="rejectAuthorizedCustAccnt(<?php echo $pgNo; ?>, <?php echo $subPgNo; ?>, <?php echo $vwtyp; ?>, 'REJECT');"><img src="cmn_images/withdraw_rqst.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">REJECT</button>                                                        
                                                                <button type="button" id="approveCustAccountBtn" class="btn btn-default btn-sm"  onclick="rejectAuthorizedCustAccnt(<?php echo $pgNo; ?>, <?php echo $subPgNo; ?>, <?php echo $vwtyp; ?>, 'APPROVE');"><img src="cmn_images/Stamp-512.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">AUTHORIZE</button>
                                                            <?php } ?>   
                                                        </div>
                                                    </div>
                                                </div>                                                
                                                <div class="row"><!-- ROW 1 -->
                                                    <div class="col-lg-12"> 
                                                        <!--<fieldset class="basic_person_fs5" style="padding: 1px !important;">-->
                                                        <legend class="basic_person_lg1" style="margin-bottom: 5px !important;">Customer</legend> 
                                                        <div  class="col-md-3">
                                                            <div class="form-group form-group-sm">
                                                                <input class="form-control" id="custType" type = "hidden" placeholder="" value="<?php echo $row[1]; ?>"/>
                                                                <?php echo dsplyCntrlLbl($cnt, $row[1], $rowATZ1, "col-md-4", "Type", "custType"); ?>
                                                                <div  class="col-md-8">
                                                                    <span><?php echo $row[1]; ?></span>
                                                                </div>
                                                            </div>                                                                                                                           
                                                        </div>
                                                        <div  class="col-md-6">
                                                            <div class="form-group form-group-sm">
                                                                <?php  echo dsplyCntrlLbl($cnt, $row[2], $rowATZ2, "col-md-3", "Name", "bnkCustomer"); ?>
                                                                <div  class="col-md-9">
                                                                    <input class="form-control" id="bnkCustomerID" type = "hidden" placeholder="Account ID" value="<?php echo $row[3]; ?>"/>
                                                                    <span><?php echo $row[2]; ?></span>
                                                                </div>
                                                                <div class="col-md-1" style="padding:0px 0px 0px 0px !important;">&nbsp;</div>
                                                            </div>
                                                        </div>
                                                        <div  class="col-md-3">
                                                            <div class="col-md-12" style="padding:0px 1px 0px 1px !important;"><button type="button" class="btn btn-info btn-sm" style="width:100% !important;" onclick="viewCustProfile();"><img src="cmn_images/kghostview.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">VIEW CUSTOMER PROFILE</button></div> 
                                                        </div>
                                                        <!--</fieldset>-->
                                                    </div>
                                                </div>                                            
                                                <div class="row"><!-- ROW 2 -->
                                                    <div class="col-lg-6">
                                                        <!--<fieldset class="basic_person_fs1" style="margin-top: 5px !important;">-->
                                                        <legend class="basic_person_lg1" style="margin:5px 0px 5px 0px !important;">Account</legend>
                                                        <div class="form-group form-group-sm">
                                                            <?php  echo  dsplyCntrlLbl($cnt, $row[4], $rowATZ4, "col-md-4", "Number", "acctNumber"); ?>
                                                            <div class="col-md-8">
                                                                <input class="form-control" id="acctID" type = "hidden" placeholder="Account ID" value="<?php echo $row[0]; ?>"/>
                                                                <span><?php echo $row[4]; ?></span>                                                                                                                                           
                                                            </div>
                                                        </div> 
                                                        <div class="form-group form-group-sm">
                                                            <?php  echo  dsplyCntrlLbl($cnt, $row[5], $rowATZ5, "col-md-4", "Account Title", "acctTitle"); ?>
                                                            <div  class="col-md-8">
                                                                <span><?php echo $row[5]; ?></span>
                                                            </div>                                                         
                                                        </div>     
                                                        <div class="form-group form-group-sm">
                                                            <?php  echo  dsplyCntrlLbl($cnt, $row[6], $rowATZ6, "col-md-4", "Account Type", "acctType"); ?>
                                                            <div  class="col-md-8">
                                                                <span><?php echo $row[6]; ?></span>
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-group-sm">
                                                            <?php  echo dsplyCntrlLbl($cnt, $row[7], $rowATZ7, "col-md-4", "Product", "prdtType"); ?>
                                                            <div  class="col-md-8">
                                                                <span><?php echo $row[7]; ?></span>
                                                            </div>
                                                        </div> 
                                                        <div class="form-group form-group-sm">
                                                            <?php if($cnt > 0 && $row[9] != $rowATZ9) { ?>
                                                                <label data-toggle="tooltip" title="<?php echo $rowATZ9; ?>" for="acctTrnsTyp" id="acctTrnsTypLbl" class="control-label col-md-4"><a href="#" style="color:red;" onclick="dsplyAuthrzdData('<?php echo $rowATZ9; ?>');">Transaction Type:</a></label>
                                                                <?php } else { ?>
                                                                <label for="acctTrnsTyp" id="acctTrnsTypLbl" class="control-label col-md-4">Transaction Type:</label>
                                                                <?php } ?>
                                                            <div  class="col-md-8">
                                                                <span><?php echo $row[9]; ?></span>
                                                            </div>
                                                        </div>                                                         
                                                        <div class="form-group form-group-sm">
                                                            <?php  echo dsplyCntrlLbl($cnt, $row[10], $rowATZ10, "col-md-4", "Person Type/Entity", "prsnTypeEntity"); ?>
                                                            <div  class="col-md-8">
                                                                <span><?php echo $row[10]; ?></span>
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-group-sm">
                                                            <?php  echo dsplyCntrlLbl($cnt, $row[11], $rowATZ11, "col-md-4", "Currency", "prdtCrncy"); ?>
                                                            <div class="col-md-8">
                                                                <span><?php echo $row[11]; ?></span>                                                                                                                                           
                                                            </div>
                                                        </div>
                                                        <!--</fieldset>-->
                                                    </div>                                
                                                    <div class="col-lg-6">
                                                        <!--<fieldset class="basic_person_fs1" style="margin-top: 5px !important;">-->
                                                        <legend class="basic_person_lg1" style="margin:5px 0px 5px 0px !important;">General</legend>
                                                        <div class="form-group form-group-sm">
                                                            <?php if($cnt > 0 && $row[13] != $rowATZ13) { ?>
                                                                <label data-toggle="tooltip" title="<?php echo $rowATZ13; ?>" for="prpsOfAcct" id="prpsOfAcctLbl" class="control-label col-md-4"><a href="#" style="color:red;" onclick="dsplyAuthrzdData('<?php echo $rowATZ16; ?>');">Purpose:</a></label>
                                                                <?php } else { ?>
                                                                <label for="prpsOfAcct" id="prpsOfAcctLbl" class="control-label col-md-4">Purpose:</label>
                                                                <?php } ?>
                                                            <div class="col-md-8">
                                                                <span><?php echo $row[13]; ?></span>                                                                                                                                                           
                                                            </div>
                                                        </div> 
                                                        <div class="form-group form-group-sm">
                                                            <?php  echo dsplyCntrlLbl($cnt, $row[14], $rowATZ14, "col-md-4", "Purpose - Other", "prpsOfAcctOther"); ?>
                                                            <div  class="col-md-8">
                                                                <span><?php echo $row[14]; ?></span>
                                                            </div>
                                                        </div> 
                                                        <div class="form-group form-group-sm">
                                                            <?php if($cnt > 0 && $row[15] != $rowATZ15) { ?>
                                                                <label data-toggle="tooltip" title="<?php echo $rowATZ15; ?>" for="srcOfFunds" id="srcOfFundsLbl" class="control-label col-md-4"><a href="#" style="color:red;" onclick="dsplyAuthrzdData('<?php echo $rowATZ15; ?>');">Source of Funds:</a></label>
                                                                <?php } else { ?>
                                                                <label for="srcOfFunds" id="srcOfFundsLbl" class="control-label col-md-4">Source of Funds:</label>
                                                                <?php } ?>
                                                            <div class="col-md-8">
                                                                <span><?php echo $row[15]; ?></span>                                                                                                                                          
                                                            </div>
                                                        </div> 
                                                        <div class="form-group form-group-sm">
                                                            <?php  echo dsplyCntrlLbl($cnt, $row[16], $rowATZ16, "col-md-4", "Source of Funds - Other", "srcOfFundsOther"); ?>
                                                            <div  class="col-md-8">
                                                                <span><?php echo $row[16]; ?></span>
                                                            </div>
                                                        </div>                                                        
                                                        <div class="form-group form-group-sm">
                                                            <?php  echo dsplyCntrlLbl($cnt, $row[17], $rowATZ17, "col-md-6", "Expected Monthly Transaction Volume", "trnsPerMnthNo"); ?>
                                                            <div  class="col-md-6">
                                                                <span><?php echo number_format($row[17], 2); ?></span>
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-group-sm">
                                                            <?php  echo dsplyCntrlLbl($cnt, $row[18], $rowATZ18, "col-md-6", "Expected Monthly Amount", "trnsPerMnthNo"); ?>
                                                            <div  class="col-md-6">
                                                                <span><?php echo number_format($row[18], 2); ?></span>
                                                            </div>
                                                        </div>                                                        
                                                        <!--</fieldset>-->
                                                    </div>
                                                </div>  
                                                <div class="row"><!-- ROW 3 -->
                                                    <div class="col-lg-6">
                                                        <!--<fieldset class="basic_person_fs1">-->
                                                        <legend class="basic_person_lg1" style="margin:10px 0px 5px 0px !important;">Account</legend>
                                                        <div class="form-group form-group-sm">
                                                            <?php  echo dsplyCntrlLbl($cnt, $branch, $v_BranchATZ, "col-md-4", "Branch", "bnkBranch"); ?>
                                                            <div  class="col-md-8">
                                                                <div class="input-group">
                                                                    <span><?php echo $branch; ?></span>
                                                                </div>
                                                            </div>
                                                        </div>                                                        
                                                        <div class="form-group form-group-sm">
                                                            <?php  echo dsplyCntrlLbl($cnt, $relOfficerNm, $relOfficerNmATZ, "col-md-4", "Relationship Officer", "reltnOfficer"); ?>
                                                            <div  class="col-md-8">
                                                                <span><?php echo $relOfficerNm; ?></span>
                                                            </div>
                                                        </div>       
                                                        <div class="form-group form-group-sm">
                                                            <?php  echo dsplyCntrlLbl($cnt, $row[29], $rowATZ29, "col-md-4", "Account Status", "accountStatus"); ?>
                                                            <div  class="col-md-8">
                                                                <span><?php echo $row[29]; ?></span>
                                                            </div>
                                                        </div>       
                                                        <div class="form-group form-group-sm">
                                                            <?php  echo dsplyCntrlLbl($cnt, $row[21], $rowATZ21, "col-md-4", "Comments", "statusReason"); ?>
                                                            <div  class="col-md-8">
                                                                <span><?php echo $row[21]; ?></span>
                                                            </div>
                                                        </div>                                                        
                                                        <div class="form-group form-group-sm" style="display:none !important;">
                                                            <label for="status" class="control-label col-md-4">Status:</label>
                                                            <div class="col-md-8">
                                                                <span><?php echo $row[22]; ?></span>
                                                                <input class="form-control" id="status" type = "hidden" placeholder="" value="<?php echo $row[22]; ?>" readonly/>
                                                            </div>
                                                        </div> 
                                                        <div class="form-group form-group-sm" style="display:none !important;">
                                                            <label for="ovdrftBal" class="control-label col-md-4">Overdraft Balance:</label>
                                                            <div  class="col-md-8">
                                                                <span><?php echo number_format($row[23], 2); ?></span>
                                                            </div>
                                                        </div>  
                                                        <div class="form-group form-group-sm">
                                                            <?php  echo dsplyCntrlLbl($cnt, $row[25], $rowATZ25, "col-md-4", "Allowed Group Type", "grpType"); ?>
                                                            <div class="col-md-8">
                                                                <span><?php echo $row[25]; ?></span>
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-group-sm">
                                                            <?php  echo dsplyCntrlLbl($cnt, $row[25], $rowATZ25, "col-md-4", "Allowed Group Name", "groupName"); ?>
                                                            <div class="col-md-8">
                                                                <span><?php echo getAllwdGrpVal($row[25], $row[26]); ?></span>
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-group-sm">
                                                            <?php  echo dsplyCntrlLbl($cnt, $row[33], $rowATZ33, "col-md-4", "Can Overdraw Account?", "canOverdrawAcct"); ?>
                                                            <div class="col-md-8">
                                                                <span><?php echo $row[33]; ?></span>
                                                            </div>
                                                        </div>
                                                        <!--</fieldset>-->
                                                    </div>                                
                                                    <div class="col-lg-6">
                                                        <!--<fieldset class="basic_person_fs1">-->
                                                        <legend class="basic_person_lg1" style="margin:10px 0px 5px 0px !important;">Signatories & Mandate</legend>
                                                        <div  class="col-md-12">
                                                            <div class="row"><!-- ROW 3 -->
                                                                <div class="col-lg-8">
                                                                    <div class="form-group form-group-sm">
                                                                        <?php  echo dsplyCntrlLbl($cnt, $row[24], $rowATZ24, "col-md-4", "Mandate", "accMndte"); ?>
                                                                        <div  class="col-md-8">
                                                                            <input class="form-control" id="accMndte" type = "hidden" placeholder="Mandate" value="<?php echo $row[24]; ?>"/>
                                                                            <span><?php echo $row[24]; ?></span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-4"><?php echo ""; ?></div>
                                                            </div>
                                                            <table id="acctSignatoryTblAdd" class="table table-striped table-bordered table-responsive" cellspacing="0" width="100%" style="width:100%;">
                                                                <thead>
                                                                    <tr>
                                                                        <th>No.</th>
                                                                        <th>Signatory</th>
                                                                        <th>ID No.</th>
                                                                        <th>Sign?</th>
                                                                        <th>...</th>
                                                                        <th style="display:none;">Src Type</th>
                                                                        <th style="display:none;">SignatoryID</th>
                                                                        <th style="display:none;">End Date</th>                                                                           
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                <?php
                                                                $cnta = 0;
                                                                $result2 = get_CustAccount_Signatories($pkID);
                                                                while ($row2 = loc_db_fetch_array($result2)) {
                                                                    $cnta = $cnta + 1;
                                                                    $row1ATZ0 = ""; $row1ATZ1 = ""; $row1ATZ2 = ""; $row1ATZ3 = ""; $row1ATZ4 = ""; $row1ATZ5 = "";
                                                                    $row1ATZ6 = ""; $row1ATZ7 = "";
                                                                    if($row2[0] > 0 && $row2[7] === "Yes"){
                                                                        $result1ATZ = get_CustAccount_SignatoriesATZ($row2[0], $row2[8]);
                                                                        while ($row1ATZ = loc_db_fetch_array($result1ATZ)) {
                                                                            $row1ATZ0 = $row1ATZ[0]; $row1ATZ1 = $row1ATZ[1]; $row1ATZ2 = $row1ATZ[2]; $row1ATZ3 = $row1ATZ[3];
                                                                            $row1ATZ4 = $row1ATZ[4]; $row1ATZ5 = $row1ATZ[5]; $row1ATZ6 = $row1ATZ[6];
                                                                        }
                                                                    }
                                                                    ?>
                                                                        <tr id="acctSignatoryTblAddRow<?php echo $row2[0]; ?>">
                                                                            <td class="lovtd"><?php echo $cnta; ?></td>
                                                                            <td class="lovtd"><?php echo dsplyTblData($row2[1], $row1ATZ1, $row2[0], $row2[7]); ?></td>
                                                                            <td class="lovtd"><?php echo dsplyTblData($row2[2], $row1ATZ2, $row2[0], $row2[7]); ?></td>
                                                                            <td class="lovtd"><?php echo dsplyTblData($row2[3], $row1ATZ3, $row2[0], $row2[7]); ?></td>
                                                                            <td class="lovtd"><button type="button" class="btn btn-default btn-sm" onclick="viewSignatoryForm('myLovModal', 'myLovModalBody', 'myLovModalTitle', 'acctSignatoryForm', '', 'Signatory', 13, <?php echo $subPgNo; ?>, 5, 'VIEW',<?php echo $row2[0]; ?>);" style="padding:2px !important;">
                                                                                    <img src="cmn_images/kghostview.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                                </button></td>
                                                                            <td style="display:none;"><?php echo $row2[4]; ?></td>
                                                                            <td style="display:none;"><?php echo $row2[6]; ?></td> 
                                                                            <td style="display:none;"><?php echo $row2[5]; ?></td>
                                                                            <td <?php echo $shwHydNtlntySts; ?>>
                                                                                <?php 
                                                                                if($row2[0] < 0){
                                                                                    echo "<span style='color:red;'><b>Deleted</b></span>";
                                                                                } else  {
                                                                                   if($row2[7] === "No"){
                                                                                        echo "<span style='color:blue;'><b>New</b></span>";
                                                                                   } else {
                                                                                       echo "&nbsp;";
                                                                                   }
                                                                                }
                                                                                ?>
                                                                            </td>
                                                                        </tr>
                        <?php
                    }
                    ?>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                        <!--</fieldset>-->
                                                    </div>
                                                </div> 
                                                <div class="row" style="display:block;"><!--ROW 4-- LIENS -->
                                                    <div class="col-lg-12"> 
                                                        <legend class="basic_person_lg1" style="margin-top:10px !important">Account Liens</legend> 
                                                        <div  class="col-md-12">
                                                            <table id="acctLiensTblAdd" class="table table-striped table-bordered table-responsive" cellspacing="0" width="100%" style="width:100%;">
                                                                <thead>
                                                                    <tr>
                                                                        <th>No.</th>
                                                                        <th>Lien ID</th>
                                                                        <th>Amount</th>
                                                                        <th>Start Date</th>
                                                                        <th>End Date</th>
                                                                        <th>Lien Status</th>
                                                                        <th>Narration</th>
                                                                        <th style="display:none;">...</th>
                                                                        <th style="display:none;">Lien ID</th>                                                                        
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                    <?php
                    $cnta = 0;
                    $result2 = get_CustAccount_Liens($pkID);
                    while ($row2 = loc_db_fetch_array($result2)) {
                        $cnta = $cnta + 1;
                        ?>
                                                                        <tr id="acctLiensTblAddRow<?php echo $row2[0]; ?>">
                                                                            <td class="lovtd"><?php echo $cnta; ?></td>
                                                                            <td class="lovtd"><?php echo $row2[1]; ?></td>
                                                                            <td class="lovtd"><?php echo $row2[2]; ?></td>
                                                                            <td class="lovtd"><?php echo $row2[3]; ?></td>
                                                                            <td class="lovtd"><?php echo $row2[4]; ?></td>
                                                                            <td class="lovtd"><?php echo $row2[6]; ?></td>
                                                                            <td class="lovtd"><?php echo $row2[5]; ?></td>
                                                                            <td style="display:none;"><button type="button" class="btn btn-default btn-sm" onclick="getSvngsWtdwlForm(\'myLovModal\', \'myLovModalBody\', \'myLovModalTitle\', \'svngsWtdwlCmsnForm\', \'svngsWtdwlCmsnTblAddRow' + rowValID + '\', \'Edit Withdrawal Commission\', 12,7.1, 5, \'EDIT\',' + rowValID + ');" style="padding:2px !important;">
                                                                                    <img src="cmn_images/kghostview.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                                </button></td>
                                                                            <td style="display:none;"><?php echo $row2[7]; ?></td>
                                                                        </tr>
                        <?php
                    }
                    ?>                                                                    
                                                                </tbody>
                                                            </table>                                                    
                                                        </div>
                                                    </div>
                                                </div>                                                
                                                <div class="row" style="display:none;"><!--ROW 5-- AVAILABLE IN EDIT MODE -->
                                                    <div class="col-lg-12"> 
                                                        <fieldset class="basic_person_fs3" style="padding: 1px !important;"><legend class="basic_person_lg">Data Change History</legend> 
                                                            <div  class="col-md-12">
                                                                <table id="wtdwlCmsnTblAdd" class="table table-striped table-bordered table-responsive" cellspacing="0" width="100%" style="width:100%;">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>...</th>
                                                                            <th>No.</th>
                                                                            <th>Low Range</th>
                                                                            <th>High Range</th>
                                                                            <th>Amount Flat</th>
                                                                            <th>Amount Percent</th>
                                                                            <th>Remarks</th>
                                                                            <th style="display:none;">Remarks</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                    <?php
                    $result1 = get_SavingsWtdwlCmmsn(1);
                    $cntr = 0;
                    while ($row1 = loc_db_fetch_array($result1)) {
                        $cntr++;
                        ?>
                                                                            <tr id="svngsWtdwlCmsnTblAddRow<?php echo $cntr; ?>">
                                                                                <td class="lovtd">
                                                                                    <button type="button" class="btn btn-default btn-sm" onclick="getSvngsWtdwlForm('myLovModal', 'myLovModalBody', 'myLovModalTitle', 'svngsWtdwlCmsnForm', 'svngsWtdwlCmsnTblAddRow<?php echo $cntr; ?>', 'Edit Withdrawal Commission', 12, <?php echo $subPgNo; ?>, 5, 'EDIT', <?php echo $row1[0]; ?>);" style="padding:2px !important;">
                                                                                            <!--<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>-->
                                                                                        <img src="cmn_images/edit32.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                                    </button>
                                                                                </td>
                                                                                <td class="lovtd">
                                                                            <?php echo $cntr; ?>
                                                                                </td>
                                                                                <td class="lovtd">
                        <?php echo $row1[1]; ?>
                                                                                </td>
                                                                                <td class="lovtd"><?php echo $row1[2]; ?></td>
                                                                                <td class="lovtd"><?php echo $row1[3]; ?></td>
                                                                                <td class="lovtd"><?php echo $row1[4]; ?></td>
                                                                                <td class="lovtd"><?php echo $row1[5]; ?></td>
                                                                                <td style="display:none;"><?php echo $row1[0]; ?></td>
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
                                        <div id="prflCATrnsHstryEDT" class="tab-pane fade" style="border:none !important;"></div>                                        
                                    </div>                        
                                </div>                         
                            </div>                
                        </div>          
                    </div>
                    <?php
                }
            } else if ($vwtypActn == "PTYPENT") {
                $cType = isset($_POST['cType']) ? cleanInputData($_POST['cType']) : "";
                $lovName = "";
                ?>
                <option value="">&nbsp;</option>
                <?php
                if ($cType == "Corporate") {
                    $lovName = "Bank Account Person Types/Entity - CORP";
                    ?>
                    <option value="CORP-Partnership">CORP-Partnership</option>
                    <?php
                } else if ($cType == "Individual") {
                    $lovName = "Bank Account Person Types/Entity - INDV";
                } else if ($cType == "Group") {
                    $lovName = "Bank Account Person Types/Entity - CGRP";
                }

                $brghtStr = "";
                $isDynmyc = FALSE;
                $titleRslt = getLovValues("%", "Both", 0, 100, $brghtStr, getLovID($lovName), $isDynmyc, -1, "", "");
                while ($titleRow = loc_db_fetch_array($titleRslt)) {
                    $selectedTxt = "";
                    ?>
                    <option value="<?php echo $titleRow[0]; ?>" <?php echo $selectedTxt; ?>><?php echo $titleRow[0]; ?></option>
                    <?php
                }
            }
        } else if ($vwtyp == "1") {//VIEW OF TRANSACTION HISTORY 
            if ($vwtypActn == "VIEW") {
                /* Read Only */

               $acctNoFind = getGnrlRecNm("mcf.mcf_accounts", "account_id", "account_number", $pkID);
               $prdtCode = substr($acctNoFind,0,3);
               $acctHolderPrsnID = getAccountLinkedPrsnID($acctNoFind);
               
                $pageNoAH = isset($_POST['pageNoAH']) ? cleanInputData($_POST['pageNoAH']) : 1;
                $lmtSze1 = 10; //isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 10;   

                $total1 = get_OneCustAccntHstrytNavTtl($pkID);

                if ($pageNoAH > ceil($total1 / $lmtSze1)) {
                    $pageNoAH = 1;
                } else if ($pageNoAH < 1) {
                    $pageNoAH = ceil($total1 / $lmtSze1);
                }

                $curIdx = $pageNoAH - 1;
                $acntHstryRslt = get_OneCustAccntHstryNav($pkID, $curIdx, $lmtSze1);
                
                $tblNm1 = "mcf.mcf_accounts";

                $cnt = getCustAcctDataChngPndngCount($pkID);
                if($cnt > 0){
                    $tblNm1 = "mcf.mcf_accounts_hstrc";        
                }                 

                $result = getCustAccountDets($pkID, $tblNm1);
                $balDteStr = substr(getStartOfDayYMD(),0,10);//getDB_Date_timeYYYYMMDD();

                $result2 = getCustAccountBals($pkID, $balDteStr);
                $avlblBal = 0;
                $unclrdFunds = 0;
                $lienAmntDsp = 0;
                $curBal = 0;
                while ($row2 = loc_db_fetch_array($result2)) {
                    $avlblBal = $row2[3];
                    $unclrdFunds = $row2[1];
                    $lienAmntDsp = $row2[2];
                    $curBal = $row2[0];
                }



                while ($row = loc_db_fetch_array($result)) {
                    $branch = getGnrlRecNm("org.org_sites_locations", "location_id", "site_desc||' ('||location_code_name||')'", $row[19]);
                    $relOfficerNm = getGnrlRecNm("prs.prsn_names_nos", "person_id", "trim(title || ' ' || sur_name || ', ' || first_name || ' ' || other_names)", $row[20]);                    

                    /* Edit */
                    ?>
                    <!--<input class="form-control" id="acctID" type = "hidden" placeholder="Account ID" value="<?php echo $row[0]; ?>"/>-->
                    <form class="form-horizontal" id="customerAccountTrnsHistoryForm">
                        <?php
                            if($prdtCode != '211' || ($prdtCode == '211' && $canSeeOtherStaffAcctTrns === true) || ($acctHolderPrsnID == $prsnid)){
                        ?>
                        <div class="row"><!-- ROW 1 -->
                            <div class="col-lg-12"> 
                                <fieldset class="" style="margin-top:0px !important;"><legend class="basic_person_lg1" id="hstrcAcctTrns">Account Balance</legend>  
                                    <div  class="col-md-6">
                                        <div class="form-group form-group-sm">
                                            <label for="curBal" class="control-label col-md-4">Current Balance:</label>
                                            <div class="col-md-8">
                                                <input class="form-control" style="font-weight: bold;color:green;font-size:16px !important;" id="curBal" type = "text" placeholder="" value="<?php echo $row[11] . " " . number_format((float) $curBal, 2); ?>" readonly/>                                                                                                                                        
                                            </div>
                                        </div>
                                        <div class="form-group form-group-sm">
                                            <label for="unclrdFunds" class="control-label col-md-4">Uncleared Funds:</label>
                                            <div  class="col-md-8">
                                                <input class="form-control" style="font-weight: bold;color:green;font-size:16px !important;" size="16" type="text" id="unclrdFunds" value="<?php echo $row[11] . " " . number_format((float) $unclrdFunds, 2); ?>" readonly="">
                                            </div>
                                        </div>                                                                                                                            
                                    </div>
                                    <div  class="col-md-6">
                                        <div class="form-group form-group-sm">
                                            <label for="lienAmntDsp" class="control-label col-md-4">Lien Amount:</label>
                                            <div  class="col-md-8">
                                                <input class="form-control" style="font-weight: bold;color:green;font-size:16px !important;" id="lienAmntDsp" type = "text" min="0" placeholder="Overdraft Balance" value="<?php echo $row[11] . " " . number_format((float) $lienAmntDsp, 2); ?>" readonly/>
                                            </div>
                                        </div> 
                                        <div class="form-group form-group-sm">
                                            <label for="avlblBal" class="control-label col-md-4">Available Balance:</label>
                                            <div  class="col-md-8">
                                                <input class="form-control" style="font-weight: bold;color:blue;font-size:16px !important;" id="avlblBal" type = "text" min="0" placeholder="Overdraft Balance" value="<?php echo $row[11] . " " . number_format((float) $avlblBal, 2); ?>" readonly/>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                        </div>                                            
                        <div class="row" style="display:block;"><!--ROW 2 TRANSACTION HISTORY -->
                            <div class="col-lg-12"> 
                                <fieldset class="" style="margin-top:10px !important;">
                                    <div class="row">
                                        <div class="col-lg-11"><legend class="basic_person_lg1" id="hstrcAcctTrns">Historic Account Transactions</legend></div> 
                                        <div class="col-lg-1">
                                            <input id="trnsHstrypageNoAH" type = "hidden" value="<?php echo $pageNoAH; ?>">
                                            <nav aria-label="Page navigation">
                                                <ul class="pagination" style="margin: 0px !important;">
                                                    <li>
                                                        <a href="javascript:getCustAcctHistory('previous',<?php echo $pkID; ?>);" aria-label="Previous">
                                                            <span aria-hidden="true">&laquo;</span>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="javascript:getCustAcctHistory('next',<?php echo $pkID; ?>);" aria-label="Next">
                                                            <span aria-hidden="true">&raquo;</span>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </nav>
                                        </div>
                                    </div>
                                    <div  class="col-md-12" style="padding:0px !important;">
                                        <table id="acctHistoryTblAdd" class="table table-striped table-bordered table-responsive" cellspacing="0" width="100%" style="width:100%;">
                                            <thead>
                                                <tr>
                                                    <th>&nbsp;</th>
                                                    <th>Date</th>
                                                    <th>Transaction Type & Description</th>
                                                    <th>Trns. No.</th>
                                                    <th style="text-align:right;">Amount</th>
                                                    <th style="text-align:right;">Current Bal</th>
                                                    <th style="text-align:right;">Available Bal</th>
                                                    <th>Status</th>
                                                    <th>Authorizer</th>
                                                </tr>
                                            </thead>
                                            <tbody id="acctHistoryTblTbody">
                    <?php
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
                                                        <?php } 
                        } else {
                             ?>
                                <div style="text-align: center;"><span style="font-size: 15px; font-weight: bold; color: red !important;">Sorry! You dont have permission to view transactions!</br> Contact Management for Permission. Thanks!</span></div>
                            <?php   
                        }?>                                                    
                                            </tbody>
                                        </table>
                                    </div> 
                                </fieldset>
                            </div>
                        </div>                                                
                    </form>  
                    <?php
                }
            }
        } else if ($vwtyp == "2") {
            
        } 
        else if ($vwtyp == "4") {
            $prdtID = isset($_POST['prdtTypeID']) ? cleanInputData($_POST['prdtTypeID']) : -1;

            $svngsPrdtDetArry = array();
            $result = get_SvngsPrdtDetsForAccnt($prdtID);

            while ($row = loc_db_fetch_array($result)) {

                $crncyIsoCode = getGnrlRecNm("mcf.mcf_currencies", "crncy_id", "iso_code", $row[5]);

                $svngsPrdtDetArry = array("svngsProductId" => $row[0], "currencyId" => $row[5], "crncyIsoCode" => $crncyIsoCode);
            }


            $response = array("svngsPrdtDetArry" => $svngsPrdtDetArry);

            echo json_encode($response);
            exit();
        } else if ($vwtyp == "5") {
            /* ADD SIGNATORY */
            
            $acctSignId = isset($_POST['acctSignId']) ? cleanInputData($_POST['acctSignId']) : -1;
            $acctID = isset($_POST['acctID']) ? cleanInputData($_POST['acctID']) : -1;
            

            if ($vwtypActn == "EDIT") {

                $result = get_CustAccount_SignatoryDets($acctSignId, $acctID);
                while ($row = loc_db_fetch_array($result)) {
                    ?>
                    <form class="form-horizontal" id="acctSignatoryForm" style="padding:5px 20px 5px 20px;">
                        <div class="row">
                            <!--account Signatory ID EDIT-->
                            <input class="form-control" size="16" type="hidden" id="acctSignID" value="<?php echo $acctSignId; ?>" readonly="">                    

                    <?php
                    $srcTypeDivDspl = "style='display:none !important;'";
                    if ($row[7] == "Corporate" && $row[8] != "CORP-Partnership") {
                        $srcTypeDivDspl = "style='display:block !important;'";
                    }
                    ?>
                            <div id="srcTypeDiv" class="form-group form-group-sm" <?php echo $srcTypeDivDspl; ?>>
                                <label for="srcType" class="control-label col-md-4">Source Type:</label>
                                <div  class="col-md-8">
                                    <select class="form-control" id="srcType" disabled="true">
                    <?php
                    $selectedTxtInd = "";
                    $selectedTxtOtp = "";
                    if ($row[4] == "Individual Customers") {
                        $selectedTxtInd = "selected=\"selected\"";
                    } else if ($row[4] == "Other Persons") {
                        $selectedTxtOtp = "selected=\"selected\"";
                    }
                    ?>
                                        <option value="Individual Customers" <?php echo $selectedTxtInd; ?>>Individual Customers</option>
                                        <option value="Other Persons" <?php echo $selectedTxtOtp; ?>>Other Persons</option>
                                    </select>
                                </div>
                            </div>                    
                            <div class="form-group form-group-sm">
                                <label for="bnkSignatory" class="control-label col-md-4">Name:</label>
                                <div  class="col-md-8">
                                    <div class="input-group" style="width:100% !important;">
                                        <!--table rowElementID-->
                                        <input class="form-control" size="16" type="hidden" id="tblRowElementID" value="acctSignatoryTblAddRow<?php echo $acctSignId; ?>" readonly="">
                                        <!--custType Individual-->
                                        <input type="hidden" id="custTypeIndividual" value="Individual">
                                        <!--prsnType Director-->
                                        <input type="hidden" id="isSignatory" value="YES">

                                        <input type="text" class="form-control" aria-label="..." id="bnkSignatory" value="<?php echo $row[1]; ?>" readonly>
                                        <input type="hidden" id="bnkSignatoryID" value="<?php echo $row[6]; ?>">
                                        <input type="hidden" id="gnrlOrgID" value="<?php echo $orgID; ?>">
                                        <label style="display:none !important;" class="btn btn-primary btn-file input-group-addon" onclick="getAccountSignatories();">
                                            <span class="glyphicon glyphicon-th-list"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group form-group-sm">
                                <label for="toSignMndtry" class="control-label col-md-4">Must Sign?:</label>
                                <div  class="col-md-8">
                                    <select class="form-control" id="toSignMndtry" onchange="">
                                        <?php
                                        $selectedNo = "";
                                        $selectedYes = "";
                                        if ($row[3] == "No") {
                                            $selectedNo = "selected=\"selected\"";
                                        } else if ($row[3] == "Yes") {
                                            $selectedYes = "selected=\"selected\"";
                                        }
                                        ?>
                                        <option value="No" <?php echo $selectedNo; ?>>No</option>
                                        <option value="Yes" <?php echo $selectedYes; ?>>Yes</option>                                        
                                    </select>
                                </div>
                            </div>                    
                            <div class="form-group form-group-sm">
                                <label for="endDate" class="control-label col-md-4">End Date:</label>
                                <div  class="col-md-8">
                                    <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input3" data-link-format="yyyy-mm-dd">
                                        <input class="form-control" size="16" type="text" id="endDate" value="<?php echo $row[5]; ?>" readonly="">
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                    </div>
                                </div>
                            </div>                    

                        </div>
                        <div class="row" style="float:right;padding-right: 1px;">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-primary" onclick="saveAccountSignatoriesForm('myLovModal', '<?php echo $acctSignId; ?>', 'EDIT');">Save Changes</button>
                        </div>
                    </form>
                    <?php
                }
            } else if ($vwtypActn == "ADD") {
                ?>
                <form class="form-horizontal" id="acctSignatoryForm" style="padding:5px 20px 5px 20px;">
                    <div class="row">
                        <!--account Signatory ID-->
                        <input class="form-control" size="16" type="hidden" id="acctSignID" value="<?php echo $acctSignId; ?>" readonly="">                    
                        <!--account ID-->
                        <!--<input type="hidden" id="acctID" value="<?php echo $acctID; ?>">-->
                        <div id="srcTypeDiv" class="form-group form-group-sm" style="display:none;">
                            <label for="srcType" class="control-label col-md-4">Source Type:</label>
                            <div  class="col-md-8">
                                <select class="form-control" id="srcType" >
                                    <!--<option value="">&nbsp;</option>-->
                                    <option value="Individual Customers">Individual Customers</option>
                                    <option value="Other Persons">Other Persons</option>
                                </select>
                            </div>
                        </div>                    
                        <div class="form-group form-group-sm">
                            <label for="bnkSignatory" class="control-label col-md-4">Name:</label>
                            <div  class="col-md-8">
                                <div class="input-group">
                                    <!--table rowElementID-->
                                    <input class="form-control" size="16" type="hidden" id="tblRowElementID" value="" readonly="">
                                    <!--custType Individual-->
                                    <input type="hidden" id="custTypeIndividual" value="Individual">
                                    <!--prsnType Director-->
                                    <input type="hidden" id="isSignatory" value="YES">

                                    <input type="text" class="form-control" aria-label="..." id="bnkSignatory" value="" readonly>
                                    <input type="hidden" id="bnkSignatoryID" value="">
                                    <input type="hidden" id="gnrlOrgID" value="<?php echo $orgID; ?>">
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getAccountSignatories();">
                                        <span class="glyphicon glyphicon-th-list"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group form-group-sm">
                            <label for="toSignMndtry" class="control-label col-md-4">Must Sign?:</label>
                            <div  class="col-md-8">
                                <select class="form-control" id="toSignMndtry" onchange="">
                                    <option value="No">No</option>
                                    <option value="Yes">Yes</option>
                                </select>
                            </div>
                        </div>                    
                        <div class="form-group form-group-sm">
                            <label for="endDate" class="control-label col-md-4">End Date:</label>
                            <div  class="col-md-8">
                                <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input3" data-link-format="yyyy-mm-dd">
                                    <input class="form-control" size="16" type="text" id="endDate" value="" readonly="">
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                </div>
                            </div>
                        </div>                    

                    </div>
                    <div class="row" style="float:right;padding-right: 1px;">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" onclick="saveAccountSignatoriesForm('myLovModal', '<?php echo $acctSignId; ?>', 'ADD');">Save Changes</button>
                    </div>
                </form>
                <?php
            } else if ($vwtypActn == "VIEW") {

                /** NEW 06122017 * */
                $idSrc = isset($_POST['idSrc']) ? cleanInputData($_POST['idSrc']) : "acctSignID";

                $srcType = ""; //isset($_POST['srcType']) ? cleanInputData($_POST['srcType']) : '';
                $prsnCustID = -1; //isset($_POST['prsnCustID']) ? cleanInputData($_POST['prsnCustID']) : -1;
                $custName = ""; 

                
                //var_dump($_POST);
               
                //echo $acctSignId."||||";                           
                if ($idSrc == "acctSignID") {
                     //echo "idSrc=acctSignID".$idSrc;
                    $result = get_SignatoryDets($acctSignId, $acctID);
                    while ($row = loc_db_fetch_array($result)) {
                        $custName = $row[1];
                        $srcType = $row[4];
                        $prsnCustID = $row[6];
                    }
                } else { /** NEW 06122017 * */
                    // echo "idSrc".$idSrc;
                    $prsnCustID = getGnrlRecNm("mcf.mcf_account_signatories", "acct_sign_id", "person_cust_id", $acctSignId);
                    $prsnCustType = getGnrlRecNm("mcf.mcf_account_signatories", "acct_sign_id", "source_type", $acctSignId);
                    $srcType = $prsnCustType;
                    if($prsnCustType == "Individual Customers"){
                        $custName = getGnrlRecNm("mcf.mcf_customers_ind", "cust_id", "trim(title || ' ' || sur_name || ', ' || first_name || ' ' || other_names)", (int)$prsnCustID); 
                    } else {
                        $custName = getGnrlRecNm("mcf.mcf_prsn_names_nos", "person_id", "trim(title || ' ' || sur_name || ', ' || first_name || ' ' || other_names)", (int)$prsnCustID);
                    }
                }
 
                $picPath = "Picture/";
                $signPath = "Signature/";

                $imgLoc = "";
                $sigImgLoc = "";
                $nwFileName1 = "";
                $nwFileName2 = "";
                if ($srcType == "Individual Customers") {
                    $imgLoc = getGnrlRecNm("mcf.mcf_customers_ind", "cust_id", "CASE WHEN img_location IS NULL or img_location='' THEN '-123456789.png' ELSE img_location END", $prsnCustID);
                    $sigImgLoc = getGnrlRecNm("mcf.mcf_customers_ind", "cust_id", "CASE WHEN sign_img_location IS NULL or sign_img_location='' THEN '-987654321.png' ELSE sign_img_location END", $prsnCustID);
                } else if ($srcType == "Other Persons") {
                    $imgLoc = getGnrlRecNm("mcf.mcf_prsn_names_nos", "person_id", "CASE WHEN img_location IS NULL or img_location='' THEN '-123456789.png' ELSE img_location END", $prsnCustID);
                    $sigImgLoc = getGnrlRecNm("mcf.mcf_prsn_names_nos", "person_id", "CASE WHEN sign_img_location IS NULL or sign_img_location='' THEN '-987654321.png' ELSE sign_img_location END ", $prsnCustID);
                    $picPath = "OP_Picture/";
                    $signPath = "OP_Signature/";
                    //echo $imgLoc;
                }


                $temp1 = explode(".", $imgLoc);
                $extension1 = end($temp1);
                //echo $extension1 . ":" . $imgLoc . "<br/>";
                if (strlen(trim($extension1)) <= 0) {
                    $extension1 = "png";
                }
                $nwFileName1 = encrypt1($imgLoc, $smplTokenWord1) . "." . $extension1;
                //echo $nwFileName1."|".strlen($extension1);
                $ftp_src = $ftp_base_db_fldr . "/Mcf/Customers/" . $picPath . $imgLoc;
                $fullPemDest = $fldrPrfx . $tmpDest . $nwFileName1;
                //echo $ftp_src . "<br/>";
                if (file_exists($ftp_src) && is_dir($ftp_src) === FALSE) {
                    copy("$ftp_src", "$fullPemDest");
                } else if (!file_exists($fullPemDest)) {
                    $ftp_src = $fldrPrfx . 'cmn_images/image_up.png';
                    copy("$ftp_src", "$fullPemDest");
                }
                $nwFileName1 = $tmpDest . $nwFileName1;

                $temp2 = explode(".", $sigImgLoc);
                $extension2 = end($temp2);
                //echo $extension2 . ":" . $sigImgLoc . "<br/>";
                if (strlen(trim($extension2)) <= 0) {
                    $extension2 = "png";
                }
                $nwFileName2 = encrypt1($sigImgLoc, $smplTokenWord1) . "." . $extension2;
                //echo $nwFileName2."|".strlen($extension2);
                $ftp_src = $ftp_base_db_fldr . "/Mcf/Customers/" . $signPath . $sigImgLoc;
                //echo $ftp_src . "<br/>";
                $fullPemDest = $fldrPrfx . $tmpDest . $nwFileName2;
                if (file_exists($ftp_src) && is_dir($ftp_src) === FALSE) {
                    copy("$ftp_src", "$fullPemDest");
                } else if (!file_exists($fullPemDest)) {
                    $ftp_src = $fldrPrfx . 'cmn_images/no_image.png';
                    copy("$ftp_src", "$fullPemDest");
                }
                $nwFileName2 = $tmpDest . $nwFileName2;
                ?>
                <form class="form-horizontal" id="viewCustSignatoryForm" style="padding:5px 20px 5px 20px;">
                    <div class='row'>
                        <div  class="col-md-12">
                            <legend class="basic_person_lg" style="color: #000080"><?php echo $custName; ?></legend>
                        </div>    
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <fieldset class="basic_person_fs1"><legend class="basic_person_lg">Picture</legend>
                                <div style="margin-bottom: 10px;">
                                    <img src="<?php echo $nwFileName1; ?>" alt="..." id="img1Test" class="img-rounded center-block img-responsive" style="height: 195px !important; width: auto !important;">                                            
                                </div>                                        
                            </fieldset>
                        </div> 
                        <div class="col-lg-6">
                            <fieldset class="basic_person_fs1"><legend class="basic_person_lg">Signature/Thumbprint</legend>
                                <div style="margin-bottom: 10px;">
                                    <img src="<?php echo $nwFileName2; ?>" alt="..." id="img11Test" class="img-rounded center-block img-responsive" style="height: 195px !important; width: auto !important;">                                            
                                </div>                                        
                            </fieldset>
                        </div>                        
                    </div>
                    <div class="row" style="float:right;padding-right: 1px;">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    </div>
                </form>
                <?php
            }
        } else if ($vwtyp == "6") {
            /* ADD LIEN */
            $rowID = isset($_POST['rowID']) ? cleanInputData($_POST['rowID']) : -1;
            $acctID = isset($_POST['acctID']) ? cleanInputData($_POST['acctID']) : -1;
            $readonly = "";
            $rqrdFld = "rqrdFld";
            $rmvLienBtn = "style=\"display:block;\"";
            $rmvLienBtnTxt = "Save Changes";

            //$isApprvd
            if ($vwtypActn == "EDIT") {

                $result = get_CustAccount_LienDets($rowID, $acctID);
                while ($row = loc_db_fetch_array($result)) {
                    if($row[6] == "Approved" || $row[6] == "Active" || $row[6] == "Remove"){
                        $readonly = "readonly=\"readonly\"";
                        $rqrdFld = "";
                    }
                    
                    if($row[6] == "Remove"){
                        $readonly = "readonly=\"readonly\"";
                        $rqrdFld = "";
                        $rmvLienBtnTxt = "Activate Lien and Save";
                    } else if($row[6] == "Active"){
                        $readonly = "readonly=\"readonly\"";
                        $rqrdFld = "";
                        $rmvLienBtnTxt = "Remove Lien and Save";
                    }
                    
                    ?>
                    <form class="form-horizontal" id="acctLienForm" style="padding:5px 20px 5px 20px;">
                        <div class="row">
                            <!--account Signatory ID-->
                            <input class="form-control" size="16" type="hidden" id="acctLienID" value="<?php echo $rowID; ?>" readonly="">  
                            <input class="form-control" size="16" type="hidden" id="tblRowElementID" value="acctLiensTblAddRow<?php echo $rowID; ?>" readonly="">

                            <div class="form-group form-group-sm">
                                <label for="lienNo" class="control-label col-md-4">Lien ID:</label>
                                <div  class="col-md-8">
                                    <input class="form-control" id="lienNo" type = "text" min="0" placeholder="" value="<?php echo $row[1]; ?>" readonly/>
                                </div>
                            </div>
                            <div class="form-group form-group-sm">
                                <label for="lienAmnt" class="control-label col-md-4">Amount:</label>
                                <div  class="col-md-8">
                                    <input class="form-control <?php echo $rqrdFld; ?>" id="lienAmnt" type = "number" min="0" placeholder="Amount" value="<?php echo $row[2]; ?>" <?php echo $readonly; ?>/>
                                </div>
                            </div>                        
                            <div class="form-group form-group-sm">
                                <label for="startDate" class="control-label col-md-4">Start Date:</label>
                                <div  class="col-md-8">
                                    <input class="form-control" size="16" type="text" id="startDate" value="<?php echo $row[3]; ?>" readonly="">
                                </div>
                            </div>
                            <div class="form-group form-group-sm">
                                <label for="endDate" class="control-label col-md-4">End Date:</label>
                                <div  class="col-md-8">
                                    <input class="form-control" size="16" type="text" id="endDate" value="<?php echo $row[4]; ?>" readonly="">
                                </div>
                            </div>
                            <div class="form-group form-group-sm">
                                <label for="narration" class="control-label col-md-4">Narration:</label>
                                <div  class="col-md-8">
                                    <textarea class="form-control rqrdFld" id="narration" cols="2" placeholder="Narration" rows="4"><?php echo $row[5]; ?></textarea>
                                </div>
                            </div>
                            <div class="form-group form-group-sm">
                                <label for="lienStatus" class="control-label col-md-4">Status:</label>
                                <div  class="col-md-8">
                                    <input class="form-control" size="16" type="text" id="lienStatus" value="<?php echo $row[6]; ?>" placeholder="Status" readonly="">
                                </div>
                            </div>
                        </div>
                        <div class="row" style="float:right;">
                            <?php if($rmvLienBtnTxt == "Save Changes") { ?>
                                <button type="button" class="btn btn-primary" onclick="saveAccountLienForm('myLovModal', '<?php echo $rowID; ?>');">Save Changes</button> 
                            <?php } else  { ?>
                                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button> 
                                <button type="button" id="removeActivateBtn"  class="btn btn-primary" onclick="removeActivateLien('<?php echo $rowID; ?>');"><?php echo $rmvLienBtnTxt; ?></button>                                
                            <?php } ?>
                        </div>
                    </form>
                    <?php
                }
            } else if ($vwtypActn == "ADD") {
                $startDateDflt = date('d-M-Y');
                ?>
                <form class="form-horizontal" id="acctLienForm" style="padding:5px 20px 5px 20px;">
                    <div class="row">
                        <!--account Signatory ID-->
                        <input class="form-control" size="16" type="hidden" id="acctLienID" value="<?php echo $rowID; ?>" readonly="">  
                        <input class="form-control" size="16" type="hidden" id="tblRowElementID" value="" readonly="">

                        <div class="form-group form-group-sm">
                            <label for="lienNo" class="control-label col-md-4">Lien ID:</label>
                            <div  class="col-md-8">
                                <input class="form-control" id="lienNo" type = "text" placeholder="" value="" readonly/>
                            </div>
                        </div>
                        <div class="form-group form-group-sm">
                            <label for="lienAmnt" class="control-label col-md-4">Amount:</label>
                            <div  class="col-md-8">
                                <input class="form-control rqrdFld" id="lienAmnt" type = "number" placeholder="Amount" value="0.00"/>
                            </div>
                        </div>
                        <div class="form-group form-group-sm">
                            <label for="startDate" class="control-label col-md-4">Start Date:</label>
                            <div  class="col-md-8">
                                <input class="form-control" size="16" type="text" id="startDate" value="<?php echo $startDateDflt; ?>" readonly="" >
                            </div>
                        </div>
                        <div class="form-group form-group-sm">
                            <label for="endDate" class="control-label col-md-4">End Date:</label>
                            <div  class="col-md-8">
                                <input class="form-control" size="16" type="text" id="endDate" value="31-Dec-4000" readonly="">
                            </div>
                        </div>
                        <div class="form-group form-group-sm">
                            <label for="narration" class="control-label col-md-4">Narration:</label>
                            <div  class="col-md-8">
                                <textarea class="form-control rqrdFld" id="narration" cols="2" placeholder="Narration" rows="4"></textarea>
                            </div>
                        </div>
                        <div class="form-group form-group-sm">
                            <label for="lienStatus" class="control-label col-md-4">Status:</label>
                            <div  class="col-md-8">
                                <input class="form-control" size="16" type="text" id="lienStatus" value="Inactive" placeholder="Status" readonly="">
                            </div>
                        </div>
                    </div>
                    <div class="row" style="float:right;padding-right: 1px;">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" onclick="saveAccountLienForm('myLovModal', '<?php echo $rowID; ?>');">Save Changes</button>
                        <button type="button" style="display:none !important;" class="btn btn-success" onclick="saveAccountLienForm('myLovModal', '<?php echo $rowID; ?>');">Submit Lien</button>
                    </div>
                </form>
                <?php
            }
        } else if ($vwtyp == "7") {
            /* ADD BRANCH PAYMENT ACCOUNTS */
            $trnsType = "BRANCH PAYMENT ACCOUNTS";
            $formTitle = "Branch Payment Accounts Attached Document";

            $canAddBranchPymntAcct = test_prmssns($dfltPrvldgs[1], $mdlNm);
            $canEdtBranchPymntAcct = test_prmssns($dfltPrvldgs[1], $mdlNm);
            $canDelBranchPymntAcct = test_prmssns($dfltPrvldgs[1], $mdlNm);

            if ($vwtypActn == "EDIT" || $vwtypActn == "VIEW" || $vwtypActn === "ADD") {

                $rqstatusColor = "red";
                $ttlColor = "blue";
                $trnsStatus = "Incomplete";
                $account_id = -1;
                $accNo = "";
                $branch_id = get_Person_BranchID($prsnid);
                $branchNm = getGnrlRecNm("org.org_sites_locations", "location_id", "site_desc||' ('||location_code_name||')'", "pasn.get_prsn_siteid($prsnid)");
                $currency_id = -1;
                $crncyIsoCode = "";
                $is_enabled = "";
                $pymnt_type = "";
                ?>
                <form class="form-horizontal" id="allBranchPymntAcctsForm" style="padding:5px 20px 5px 20px;">
                    <div class="row" id="allBranchPymntAcctsDetailInfo" style="padding:0px 15px 0px 15px !important">
                <?php
                /* &vtyp=<?php echo $vwtyp; ?> */

                $error = "";
                $searchAll = true;
                $isEnabledOnly = false;
                if (isset($_POST['isEnabled'])) {
                    $isEnabledOnly = cleanInputData($_POST['isEnabled']);
                }

                $srchFor = isset($_POST['searchfor']) ? cleanInputData($_POST['searchfor']) : '';
                $srchIn = isset($_POST['searchin']) ? cleanInputData($_POST['searchin']) : 'Branch'; //'Both';
                $pageNo = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
                $lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 10;
                $sortBy = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : "Date Added DESC";
                if (strpos($srchFor, "%") === FALSE) {
                    $srchFor = "%" . str_replace(" ", "%", $srchFor) . "%";
                    $srchFor = str_replace("%%", "%", $srchFor);
                }

                $total = getBranchPymntAcctsTblTtl($isEnabledOnly, $srchFor, $srchIn, $orgID, $searchAll);
                if ($pageNo > ceil($total / $lmtSze)) {
                    $pageNo = 1;
                } else if ($pageNo < 1) {
                    $pageNo = ceil($total / $lmtSze);
                }

                $curIdx = $pageNo - 1;
                $result = getBranchPymntAcctsTbl($isEnabledOnly, $srchFor, $srchIn, $curIdx, $lmtSze, $orgID, $searchAll, $sortBy);
                $cntr = 0;
                ?>
                        <div class="row" style="padding:0px 15px 0px 15px !important">
                            <legend class="basic_person_lg1" style="color: #003245">BRANCH PAYMENT ACCOUNTS</legend>
                        <?php
                        if ($canEdtBranchPymntAcct === true) {
                            $colClassType1 = "col-lg-2";
                            $colClassType2 = "col-lg-3";
                            $colClassType3 = "col-lg-1";
                            $nwRowHtml = urlencode("<tr id=\"allBranchPymntAcctsRow__WWW123WWW\">"
                                    . "<td class=\"lovtd\"><span class=\"normaltd\">New</span></td>"
                                    . "<td class=\"lovtd\">
                                                            <div class=\"input-group\">
                                                                <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"allBranchPymntAcctsRow_WWW123WWW_BranchNm\" name=\"allBranchPymntAcctsRow_WWW123WWW_BranchNm\" value=\"\" readonly=\"true\">
                                                                <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Sites/Locations New', 'gnrlOrgID', '', '', 'radio', true, '', 'allBranchPymntAcctsRow_WWW123WWW_BranchID', 'allBranchPymntAcctsRow_WWW123WWW_BranchNm', 'clear', 1, '');\">
                                                                        <span class=\"glyphicon glyphicon-th-list\"></span>
                                                                </label>
                                                                <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"allBranchPymntAcctsRow_WWW123WWW_BranchID\" value=\"-1\" style=\"width:100% !important;\"> 
                                                            </div>
                                                            <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"allBranchPymntAcctsRow_WWW123WWW_BrnchAcctRowID\" value=\"-1\" style=\"width:100% !important;\">                                                                                                                          
                                                        </td>
                                                        <td class=\"lovtd\">
                                                            <div class=\"input-group\">
                                                                <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"allBranchPymntAcctsRow_WWW123WWW_AccountNm\" name=\"allBranchPymntAcctsRow_WWW123WWW_AccountNm\" value=\"\" readonly=\"true\">
                                                                <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'MCF Bank Branch Current Accounts', 'gnrlOrgID', 'allBranchPymntAcctsRow_WWW123WWW_BranchID', '', 'radio', true, '', 'allBranchPymntAcctsRow_WWW123WWW_AccountID', 'allBranchPymntAcctsRow_WWW123WWW_AccountNm', 'clear', 1, '',
                                                                function(){
                                                                    getAccountCrncy('allBranchPymntAcctsRow_WWW123WWW_AccountID',_WWW123WWW);
                                                                });\">
                                                                        <span class=\"glyphicon glyphicon-th-list\"></span>
                                                                </label>
                                                                <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"allBranchPymntAcctsRow_WWW123WWW_AccountID\" value=\"-1\" style=\"width:100% !important;\"> 
                                                            </div>                                                                                            
                                                        </td>                                     
                                                        <td class=\"lovtd\">
                                                            <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"allBranchPymntAcctsRow_WWW123WWW_CurrencyNm\" name=\"allBranchPymntAcctsRow_WWW123WWW_CurrencyNm\" value=\"\" readonly=\"true\">
                                                            <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"allBranchPymntAcctsRow_WWW123WWW_CurrencyID\" value=\"-1\" style=\"width:100% !important;\"> 
                                                        </td>
                                                        <td class=\"lovtd\">
                                                            <select class=\"form-control\"  id=\"allBranchPymntAcctsRow_WWW123WWW_ProductType\" >
                                                                <option value=\"Loan\">Loan</option>
                                                                <option value=\"Investment\">Investment</option>
                                                            </select>                                                               
                                                        </td>
                                                        <td class=\"lovtd\">
                                                            <select class=\"form-control\"  id=\"allBranchPymntAcctsRow_WWW123WWW_IsEnabled\" >
                                                                <option value=\"Yes\">Yes</option>
                                                                <option value=\"Yes\">No</option>
                                                            </select>                                                                                                                          
                                                        </td>
                                                        <td style=\"display:none !important;\" class=\"lovtd\">
                                                                <input readonly=\"readonly\" type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"allBranchPymntAcctsRow_WWW123WWW_Status\" name=\"allBranchPymntAcctsRow_WWW123WWW_Status\" value=\"Incomplete\">                                                               
                                                        </td>
                                                        <td class=\"lovtd\">
                                                            <button type=\"button\" class=\"btn btn-default\" style=\"margin: 0px !important;padding:0px 3px 2px 4px !important;\" onclick=\"delBranchPymntAcct('allBranchPymntAcctsRow__WWW123WWW');\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Delete Profile Factor\">
                                                                    <img src=\"cmn_images/no.png\" style=\"height:15px; width:auto; position: relative; vertical-align: middle;\">
                                                            </button>
                                                        </td>
                                    </tr>");
                            ?>
                                <div class="<?php echo $colClassType1; ?>" style="padding:0px 1px 0px 3px !important;">     
                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="insertNewRowBe4('allBranchPymntAcctsTable', 0, '<?php echo $nwRowHtml; ?>');" data-toggle="tooltip" data-placement="bottom" title="Add Profile Factor">
                                        <img src="cmn_images/add1-64.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                    </button>
                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="saveBranchPymntAcct();" data-toggle="tooltip" data-placement="bottom" title="Save Payment Account">
                                        <img src="cmn_images/FloppyDisk.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
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
                                    <input class="form-control" id="allBranchPymntAcctsSrchFor" type = "text" placeholder="Search For" value="<?php echo trim(str_replace("%", " ", $srchFor)); ?>" onkeyup="enterKeyFuncCust(event, '', '#allmodules', 'grp=17&typ=1&pg=13&subPgNo=2.1&vtyp=7')">
                                    <input id="allBranchPymntAcctsPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getAllBranchPymntAccts('clear', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&subPgNo=<?php echo $subPgNo; ?>&vtyp=<?php echo $vwtyp; ?>');">
                                        <span class="glyphicon glyphicon-remove"></span>
                                    </label>
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getAllBranchPymntAccts('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&subPgNo=<?php echo $subPgNo; ?>&vtyp=<?php echo $vwtyp; ?>');">
                                        <span class="glyphicon glyphicon-search"></span>
                                    </label> 
                                </div>
                            </div>
                            <div class="<?php echo $colClassType2; ?>">
                                <div class="input-group">
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="allBranchPymntAcctsSrchIn">
                            <?php
                            $valslctdArry = array("", "");
                            $srchInsArrys = array("Branch", "Account");
                            for ($z = 0; $z < count($srchInsArrys); $z++) {
                                if ($srchIn == $srchInsArrys[$z]) {
                                    $valslctdArry[$z] = "selected";
                                }
                                ?>
                                            <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                <?php } ?>
                                    </select>
                                    <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="allBranchPymntAcctsDsplySze" style="min-width:70px !important;">                            
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
                                        <input type="checkbox" class="form-check-input" onclick="getAllBranchPymntAccts('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&subPgNo=<?php echo $subPgNo; ?>&vtyp=<?php echo $vwtyp; ?>');" id="allBranchPymntAcctsIsEnabled" name="allBranchPymntAcctsIsEnabled" <?php echo $nonAprvdChekd; ?>>
                                        Enabled?
                                    </label>
                                </div>                             
                            </div>
                            <div class="<?php echo $colClassType3; ?>" style="width:10% !important;">
                                <nav aria-label="Page navigation">
                                    <ul class="pagination" style="margin: 0px !important;">
                                        <li>
                                            <a href="javascript:getAllBranchPymntAccts('previous', '#allmodules', 'grp=17&typ=1&pg=13&subPgNo=2.1&vtyp=7');" aria-label="Previous">
                                                <span aria-hidden="true">&laquo;</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="javascript:getAllBranchPymntAccts('next', '#allmodules', 'grp=17&typ=1&pg=13&subPgNo=2.1&vtyp=7');" aria-label="Next">
                                                <span aria-hidden="true">&raquo;</span>
                                            </a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>  
                            <div class="<?php echo $colClassType3; ?>" style="padding:0px 1px 0px 3px !important;"> 
                                <div style="float:right !important;">
                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="" data-toggle="tooltip" data-placement="bottom" title="Validate Payment Accounts">
                                        <img src="cmn_images/valid_1.jpg" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                    </button>
                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="saveBranchPymntAcct();" data-toggle="tooltip" data-placement="bottom" title="Save Payment Account">
                                        <img src="cmn_images/FloppyDisk.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="row" style="padding:0px 15px 0px 15px !important">                  
                            <div class="col-md-12" style="padding:0px 3px 0px 3px !important">
                                <table class="table table-striped table-bordered table-responsive" id="allBranchPymntAcctsTable" cellspacing="0" width="100%" style="width:100%;min-width: 300px !important;">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Branch</th>
                                            <th>Account</th>
                                            <th style="width:7% !important;">Currency</th>
                                            <th>Product Type</th>
                                            <th>Is Enabled</th>
                                            <th style="display:none !important;">Status</th>
                                            <th>&nbsp;</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                <?php
                $cntr = 0;
                while ($row2 = loc_db_fetch_array($result)) {
                    $cntr += 1;
                    $ttlOptnsScore = 0;
                    ?>
                                            <tr id="allBranchPymntAcctsRow_<?php echo $cntr; ?>">                                    
                                                <td class="lovtd"><span><?php echo ($curIdx * $lmtSze) + ($cntr); ?></span></td>
                                                <td class="lovtd">
                                                    <input type="hidden" class="form-control" aria-label="..." id="allBranchPymntAcctsRow<?php echo $cntr; ?>_BrnchAcctRowID" value="<?php echo $row2[0]; ?>" style="width:100% !important;"> 
                                                    <input type="hidden" class="form-control" aria-label="..." id="allBranchPymntAcctsRow<?php echo $cntr; ?>_BranchID" value="<?php echo $row2[1]; ?>" style="width:100% !important;">                                                                         
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" aria-label="..." id="allBranchPymntAcctsRow<?php echo $cntr; ?>_BranchNm" name="allBranchPymntAcctsRow<?php echo $cntr; ?>_BranchNm" value="<?php echo $row2[2]; ?>" readonly="true">
                                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Sites/Locations New', 'gnrlOrgID', '', '', 'radio', true, '', 'allBranchPymntAcctsRow<?php echo $cntr; ?>_BranchID', 'allBranchPymntAcctsRow<?php echo $cntr; ?>_BranchNm', 'clear', 1, '');">
                                                            <span class="glyphicon glyphicon-th-list"></span>
                                                        </label>
                                                    </div>                                                                        
                                                </td>
                                                <td class="lovtd">                                                                         
                                                    <input type="hidden" class="form-control" aria-label="..." id="allBranchPymntAcctsRow<?php echo $cntr; ?>_AccountID" value="<?php echo $row2[5]; ?>" readonly="true"> 
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" aria-label="..." id="allBranchPymntAcctsRow<?php echo $cntr; ?>_AccountNm" name="allBranchPymntAcctsRow<?php echo $cntr; ?>_AccountNm" value="<?php echo $row2[6]; ?>" readonly="true">
                                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'MCF Bank Branch Current Accounts', 'gnrlOrgID', 'allBranchPymntAcctsRow<?php echo $cntr; ?>_BranchID', '', 'radio', true, '', 'allBranchPymntAcctsRow<?php echo $cntr; ?>_AccountID', 'allBranchPymntAcctsRow<?php echo $cntr; ?>_AccountNm', 'clear', 1, '', function(){ getAccountCrncy('allBranchPymntAcctsRow<?php echo $cntr; ?>_AccountID',<?php echo $cntr; ?>); });">
                                                            <span class="glyphicon glyphicon-th-list"></span>
                                                        </label>
                                                    </div>                                                                        
                                                </td>
                                                <td class="lovtd">                                                                      
                                                    <input type="hidden" class="form-control" aria-label="..." id="allBranchPymntAcctsRow<?php echo $cntr; ?>_CurrencyID" value="<?php echo $row2[3]; ?>" readonly="true"> 
                                                    <input type="text" class="form-control" aria-label="..." id="allBranchPymntAcctsRow<?php echo $cntr; ?>_CurrencyNm" name="allBranchPymntAcctsRow<?php echo $cntr; ?>_CurrencyNm" value="<?php echo $row2[4]; ?>" readonly="true">                                                                        
                                                </td>
                                                <td class="lovtd"> 
                                                    <select class="form-control"  id="allBranchPymntAcctsRow<?php echo $cntr; ?>_ProductType" >
                    <?php
                    $sltdLoan = "";
                    $sltdInvstmnt = "";
                    if ($row2[7] == "Loan") {
                        $sltdLoan = "selected";
                    } else if ($row2[7] == "Investment") {
                        $sltdInvstmnt = "selected";
                    }
                    ?>
                                                        <option value="Loan" <?php echo $sltdLoan; ?>>Loan</option>
                                                        <option value="Investment" <?php echo $sltdInvstmnt; ?>>Investment</option>
                                                    </select>
                                                </td>
                                                <td class="lovtd"> 
                                                    <select class="form-control"  id="allBranchPymntAcctsRow<?php echo $cntr; ?>_IsEnabled" >
                    <?php
                    $sltdYes = "";
                    $sltdNo = "";
                    if ($row2[8] == "Yes") {
                        $sltdYes = "selected";
                    } else if ($row2[8] == "No") {
                        $sltdNo = "selected";
                    }
                    ?>
                                                        <option value="Yes" <?php echo $sltdYes; ?>>Yes</option>
                                                        <option value="No" <?php echo $sltdNo; ?>>No</option>
                                                    </select>
                                                </td>
                                                <td style="display:none !important;" class="lovtd">  
                                                    <input readonly="readonly" type="text" class="form-control" aria-label="..." id="allBranchPymntAcctsRow<?php echo $cntr; ?>_Status" name="allBranchPymntAcctsRow<?php echo $cntr; ?>_Status" value="<?php echo $row2[9]; ?>">                                                               
                                                </td>
                                                <td class="lovtd">
                                                    <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delBranchPymntAcct('allBranchPymntAcctsRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Risk Profile">
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
                                                    <?php ?> 
                    </div>    
                </form>
                <?php
            }
        } else if ($vwtyp == "8") {
            $acctID = isset($_POST['acctID']) ? cleanInputData($_POST['acctID']) : -1;
            $currencyNm = "";
            $currencyID = -1;

            $acctCrncyArry = array();
            $result = executeSQLNoParams("SELECT a.currency_id, b.iso_code FROM mcf.mcf_accounts a, mcf.mcf_currencies b "
                    . "WHERE a.currency_id = b.crncy_id AND a.account_id = $acctID");

            while ($row = loc_db_fetch_array($result)) {
                $currencyNm = $row[1];
                $currencyID = $row[0];
            }
            $response = array("currencyID" => $currencyID, "currencyNm" => $currencyNm);

            echo json_encode($response);
            exit();
        }
    }
    else if ($subPgNo == 2.2) {//MANUAL INTEREST PAYMENT
        $trnsDte = getStartOfDayDMYHMS();
        $mnlpymntSvngsHdrBatchNm = "";
        $mnlpymntSvngsTrnsDte = $trnsDte;
        $mnlpymntSvngsHdrDesc = "";
        $currencyID = -1;
        $currencyNm = "";
        $branchID = -1;
        $branchNm = "";
        $accruedAmount = "";
        $mnlpymntSvngsHdrId = $pkID;
        $mkReadOnly = "";
        $mkReadOnlyDsbld = "";
        $trnsStatusDsply = "Incomplete, Unprocessed";
        $trnsStatus = "Incomplete";
        $rqstatusColor = "red";

        $result = getManualInterestPymntSavingsDets($pkID);
        while($row = loc_db_fetch_array($result)){
            $mnlpymntSvngsHdrBatchNm = $row[14];
            $mnlpymntSvngsTrnsDte= $row[1];
            $mnlpymntSvngsHdrDesc = $row[2];
            $currencyID = $row[15];
            $currencyNm = getGnrlRecNm("mcf.mcf_currencies", "crncy_id", "iso_code", $currencyID);
            $branchID = $row[3];
            $branchNm = getGnrlRecNm("org.org_sites_locations", "location_id", "site_desc||' ('||location_code_name||')'", $branchID);
            $trnsStatus = $row[6];
            $trnsStatusDsply = $row[6].", ".$row[7];
            $accruedAmount = number_format($row[4],4);
            
            if(($trnsStatus == "Unauthorized") || $vwtypActn == "VIEW"){
                $mkReadOnly = "readonly=\"readonly\"";
                $mkReadOnlyDsbld = "disabled=\"true\"";
            }            
        }


        ?>
        <div class="col-md-12" style="padding:0px 0px 10px 1px !important;">
            <div style="padding:0px 1px 0px 0px !important;float:left;">
                    <button type="button" class="btn btn-default btn-sm" style="height:30px;" id="myVmsTrnsStatusBtn">
                            <input type="text" style="display:none !important;" class="form-control" id="mnlpymntSvngsHdrStatus" placeholder="Status" value="<?php echo $trnsStatusDsply; ?>"/>
                            <span style="font-weight:bold;"></span><span style="color:<?php echo $rqstatusColor; ?>;font-weight: bold;"><?php echo $trnsStatusDsply; ?></span>
                    </button>
                    <?php if($vwtypActn != "VIEW") { ?>
                    <button type="button" class="btn btn-default btn-sm" style="height:30px;" onclick="getMnlSvngsIntrstPymntForm('myFormsModalxLG', 'myFormsModalxLGBody', 'myFormsModalxLGTitle', 'Edit Batch', 13, <?php echo $subPgNo; ?>,0,'EDIT', <?php echo $pkID; ?>,'indCustTable','indCustTableRow1');" data-toggle="tooltip" title="Reload Transaction">
                            <img src="cmn_images/refresh.bmp" style="padding-right: 2px; height:17px; width:auto; position: relative; vertical-align: middle;">
                    </button>
                    <?php } ?>
                    <button type="button" class="btn btn-default" style="height:30px;" onclick="getOneMcfDocsForm_Gnrl(<?php echo $pkID; ?>,'MANUAL INTEREST PAYMENT', 140, 'Manual Interest Payment Form');" data-toggle="tooltip" data-placement="bottom" title = "Attached Documents">
                        <img src="cmn_images/adjunto.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                    </button>                                                    
            </div>
            <div style="padding:0px 1px 0px 1px !important;float:right;">
                    <?php if (($trnsStatus == "Incomplete" || $trnsStatus == "Withdrawn" || $trnsStatus == "Rejected") && $vwtypActn != "VIEW") { ?>                                                    
                            <button type="button" class="btn btn-default btn-sm" style="height:30px;" onclick="saveMnlSvngsIntrstPymnt(<?php echo $pgNo; ?>, <?php echo $subPgNo; ?>, <?php echo $vwtyp;?>);"><img src="cmn_images/FloppyDisk.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Save&nbsp;</button>
                            <button type="button" class="btn btn-default btn-sm" style="height:30px;" onclick="submitWithdrawMnlSvngsIntrstPymnt(<?php echo $pgNo; ?>, <?php echo $subPgNo; ?>, 1, 'SUBMIT');"><img src="cmn_images/Emailcon.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Submit&nbsp;</button>   
                    <?php 
                    } else if ($trnsStatus == "Unauthorized" && $vwtypActn != "VIEW") {
                            ?>    
                            <button type="button" class="btn btn-default btn-sm" style="height:30px;" onclick="submitWithdrawMnlSvngsIntrstPymnt(<?php echo $pgNo; ?>, <?php echo $subPgNo; ?>, 1, 'REJECT');"><img src="cmn_images/withdraw_rqst.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Reject&nbsp;</button>
                            <button type="button" class="btn btn-default btn-sm" style="height:30px;" onclick="submitWithdrawMnlSvngsIntrstPymnt(<?php echo $pgNo; ?>, <?php echo $subPgNo; ?>, 1, 'WITHDRAW');"><img src="cmn_images/withdraw_rqst.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Withdraw&nbsp;</button>                                      
                            <button type="button" class="btn btn-default btn-sm" style="height:30px;" onclick="submitWithdrawMnlSvngsIntrstPymnt(<?php echo $pgNo; ?>, <?php echo $subPgNo; ?>, 1, 'AUTHORIZE');"><img src="cmn_images/Stamp-512.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Authorize&nbsp;</button>                                                                                                        
                            <button type="button" class="btn btn-default btn-sm" style="height:30px;" onclick="checkWkfRqstStatus(<?php echo $routingID; ?>, 'Teller Trns. Authorization Progress History');"><img src="cmn_images/workflow.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">&nbsp;</button>                                
                                    <?php
                            } else if (($trnsStatus == "Authorized") && $vwtypActn != "VIEW") {
                                    ?>
                            <button type="button" class="btn btn-default btn-sm" style="height:30px;" onclick="checkWkfRqstStatus(<?php echo $routingID; ?>, 'Teller Trns. Authorization Progress History');"><img src="cmn_images/workflow.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;" data-toggle="tooltip" title="Authorization Progress History">&nbsp;</button>  
                            <button type="button" class="btn btn-default btn-sm" style="height:30px;" onclick="submitWithdrawMnlSvngsIntrstPymnt(<?php echo $pgNo; ?>, <?php echo $subPgNo; ?>, 1, 'REVERSE');"><img src="cmn_images/90.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Reverse Approval&nbsp;</button>                               
                    <?php } ?>
            </div>
        </div>            
        <form class="form-horizontal" id="riskFactorsForm" style="padding:5px 20px 5px 20px;">             
            <div class="row">      
                <input type="text" style="display:none !important;" class="form-control" aria-label="..." id="prgrs" value="0" readonly>
                <div class="form-group form-group-sm">                   
                    <label for="mnlpymntSvngsHdrBatchNm" class="control-label col-md-4">Batch Name:</label>
                    <div  class="col-md-8">
                        <input type="text" readonly="readonly" class="form-control" id="mnlpymntSvngsHdrBatchNm" placeholder="Batch Name" value="<?php echo $mnlpymntSvngsHdrBatchNm; ?>"/>
                    </div>
                </div>
                <div class="form-group form-group-sm">
                    <label for="mnlpymntSvngsTrnsDte" class="control-label col-md-4">Transaction Date:</label>
                    <div  class="col-md-8">
                        <input type="text" <?php echo $mkReadOnly; ?> class="form-control" id="mnlpymntSvngsTrnsDte" placeholder="Transaction Date" value="<?php echo $mnlpymntSvngsTrnsDte; ?>" readonly="readonly"/>
                    </div>
                </div>
                <div class="form-group form-group-sm">
                    <input class="form-control" size="16" type="hidden" id="mnlpymntSvngsHdrId" value="<?php echo $mnlpymntSvngsHdrId; ?>" readonly=""> 
                    <label for="mnlpymntSvngsHdrDesc" class="control-label col-md-4">Description:</label>
                    <div  class="col-md-8">
                        <textarea <?php echo $mkReadOnly; ?> class="form-control rqrdFld" id="mnlpymntSvngsHdrDesc" cols="2" placeholder="Description" rows="3"><?php echo $mnlpymntSvngsHdrDesc; ?></textarea>
                    </div>
                </div>
                <div class="form-group form-group-sm">
                    <label for="branchNm" class="control-label col-md-4">Branch:</label>
                    <div  class="col-md-8">
                        <div class="input-group">
                            <input type="text" class="form-control" aria-label="..." id="branchNm" value="<?php echo $branchNm; ?>" readonly>
                            <input type="hidden" id="branchID" value="<?php echo $branchID; ?>"> 
                            <input type="hidden" id="gnrlOrgID" value="<?php echo $orgID; ?>">
                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Sites/Locations New', 'gnrlOrgID', '', '', 'radio', true, '', 'branchID', 'branchNm', 'clear', 1, '');">
                                <span class="glyphicon glyphicon-th-list"></span>
                            </label>
                            <label class="btn btn-primary btn-file input-group-addon" onclick="javascript:$('#branchID').val(-1);$('#branchNm').val('');">
                                <span class="glyphicon glyphicon-remove"></span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group form-group-sm">
                    <label for="currencyNm" class="control-label col-md-4">Currency:</label>
                    <div  class="col-md-8">
                        <select class="form-control" id="currencyNm" <?php echo $mkReadOnlyDsbld; ?>>
                            <?php
                            $brghtStr = "";
                            $isDynmyc = FALSE;
                            $titleRslt = getLovValues("%", "Both", 0, 100, $brghtStr, getLovID("Bank Currencies (Select List)"), $isDynmyc, -1, "", "");
                            while ($titleRow = loc_db_fetch_array($titleRslt)) {
                                $selectedTxt = "";
                                if($currencyNm == $titleRow[0]){
                                    $selectedTxt = "selected=\"selected\"";
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
                    <label for="accruedAmnt" class="control-label col-md-4">Total Accrued Amount:</label>
                    <div  class="col-md-8">
                        <input type="text" readonly="readonly" style="font-weight: bold; font-size: 18px !important; " class="form-control rqrdFld" aria-label="..." id="accruedAmnt" value="<?php echo $accruedAmount; ?>" <?php echo $mkReadOnly; ?>>
                        <input type="text" style="display:none !important;" class="form-control rqrdFld" aria-label="..." id="accruedAmntDsply"  <?php echo $mkReadOnly; ?> onblur="formatAmount('accruedAmnt', 'accruedAmntDsply');">
                    </div>
                </div> 
                <?php if($trnsStatusDsply == "Authorized, Unprocessed") { ?>
            <div class="row" style="float:right;padding-right: 10px;">
                <button type="button" class="btn btn-primary" onclick="processMnlSvngsIntrstPymnt();" >Process and Pay</button>
            </div> 
                <?php } ?>
            </div>
        </form>
        <?php      
    }
    ?>
    <!-- MODAL WINDOWS -->
    <!--  style="min-width: 1000px;left:-35%;"-->          

    <script type="text/javascript">
        $(document).ready(function () {

        });
    </script>                

    <?php
}
?>
