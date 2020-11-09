<?php
if (array_key_exists('lgn_num', get_defined_vars())) {
    $prsnid = $_SESSION['PRSN_ID'];
    $orgID = $_SESSION['ORG_ID'];
    $lnkdFirmID = getGnrlRecNm("prs.prsn_names_nos", "person_id", "lnkd_firm_org_id", $prsnid);
    $pkID = $prdtID;
    
    $rowATZ0 = ""; $rowATZ1 = ""; $rowATZ2 = "";$rowATZ3 = "";$rowATZ4 = "";$rowATZ5 = "";$rowATZ6 = "";$rowATZ7 = "";$rowATZ8 = "";$rowATZ9 = "";$rowATZ10 = "";
    $rowATZ11 = "";$rowATZ12 = "";$rowATZ13 = "";$rowATZ14 = "";$rowATZ15 = "";$rowATZ16 = "";$rowATZ17 = "";$rowATZ18 = "";$rowATZ19 = "";$rowATZ19 = "";
    $rowATZ20 = "";$rowATZ21 = "";$rowATZ22 = "";$rowATZ23 = "";$rowATZ24 = "";$rowATZ25 = "";$rowATZ26 = "";$rowATZ27 = "";$rowATZ28 = "";$rowATZ29 = "";
    $rowATZ30 = "";$rowATZ31 = "";$rowATZ32 = "";$rowATZ33 = "";$rowATZ34 = "";$rowATZ35 = "";$rowATZ36 = "";$rowATZ37 = "";$rowATZ38 = "";$rowATZ39 = "";
    $rowATZ40 = ""; $rowATZ41 = ""; $rowATZ42 = ""; $rowATZ43 = ""; $rowATZ44 = ""; $rowATZ45 = ""; $rowATZ46 = ""; $rowATZ46 = ""; $rowATZ47 = "";
    $rowATZ48 = ""; $rowATZ49 = ""; $rowATZ50 = ""; $rowATZ51 = "";
    $v_BranchATZ = "";   
    
    $prdtCodeLn = "10";    
    $strSql = "SELECT var_value FROM mcf.mcf_global_variables a
            WHERE var_name = 'Product Code Length'";
    $result = executeSQLNoParams($strSql);
    while ($row = loc_db_fetch_array($result)) {
        $prdtCodeLn = $row[0];
    } 

    $prdtCdeLnStr = "onKeyPress=\"if(this.value.length==$prdtCodeLn) return false\"";
    
    ?><input class="form-control" id="codeLnght" type = "hidden" placeholder="Code Length" value="<?php echo $prdtCodeLn; ?>"/><?php
    
    if ($subPgNo == 7.1) {//SAVINGS PRODUCT
        if ($vwtyp == "0") {
            /* BASIC DATA */
            $trnsStatus = "Incomplete";
            $rqstatusColor = "red";
            $mkReadOnly = "";
            $mkReadOnlyDsbld = "";  
            $showHideDivInvstmnt = "style=\"display:none !important;\""; 
            $showHideDivSavings = "style=\"display:block !important;\""; 
            $prdtDivCls = "col-md-4";
            $prdtCrncy = "GHS";
            $prdtCrncyATZ = "GHS";
            

            if ($vwtypActn === "ADD") {               
                /* Add */
                ?>
                <input class="form-control" id="addOrEditForm" type = "hidden" placeholder="addOrEditForm" value="Add"/>
                <div class="row" style="margin: 0px 0px 10px 0px !important;">
                    <div class="col-md-12" style="padding:0px 1px 5px 1px !important;">
                        <div style="float:left;">
                            <button type="button" class="btn btn-default btn-sm" style="height:30px;">
                                    <span style="font-weight:bold;">Status: </span><span style="color:<?php echo $rqstatusColor; ?>;font-weight: bold;"><?php echo $trnsStatus; ?></span>
                            </button>                                                    
                        </div>
                        <div class="" style="float:right;"> 
                            <button type="button" class="btn btn-default btn-sm" style="" onclick="saveProductMainData(<?php echo $pgNo; ?>, <?php echo $subPgNo; ?>, <?php echo $vwtyp; ?>, 0);">
                                <img src="cmn_images/FloppyDisk.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">
                                SAVE
                            </button>
                        </div>
                    </div>
                </div>                
                <div class="row">                  
                    <div class="col-md-12">
                        <form class="form-horizontal">
                            <div class="row"><!-- ROW 1 -->                              
                                <div class="col-lg-4" id="PrdtDiv">
                                    <fieldset class="basic_person_fs5"><legend class="basic_person_lg1">Product</legend>
                                        <div class="form-group form-group-sm">
                                            <label for="prdtType" class="control-label col-md-4">Type:</label>
                                            <div  class="col-md-8">
                                                <select class="form-control" id="prdtType" onchange="showHideSvngsInvstmntElmnts()">
                                                    <option selected="true" disabled="disabled" style="font-style: italic;">--Please Select--</option>
                                                    <?php
                                                    $brghtStr = "";
                                                    $isDynmyc = FALSE;
                                                    $titleRslt = getLovValues("%", "Both", 0, 100, $brghtStr, getLovID("Savings Product Types"), $isDynmyc, -1, "", "");
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
                                            <label for="pdtCode" class="control-label col-md-4">Code:</label>
                                            <div class="col-md-8">
                                                <input class="form-control rqrdFld" id="pdtCode" min="0" <?php echo $prdtCdeLnStr; ?> type = "number" placeholder="Code" value=""/>
                                                <!--CUSTOMER ID-->
                                                <input class="form-control" id="prdtID" type = "hidden" placeholder="Product ID" value=""/>                                                                                                                                           
                                            </div>
                                        </div> 
                                        <div class="form-group form-group-sm">
                                            <label for="prdtName" class="control-label col-md-4">Name:</label>
                                            <div  class="col-md-8">
                                                <input class="form-control rqrdFld" id="prdtName" type = "text" placeholder="Name" value=""/>
                                            </div>
                                        </div>     
                                        <div class="form-group form-group-sm">
                                            <label for="prdtDesc" class="control-label col-md-4">Description:</label>
                                            <div  class="col-md-8">
                                                <textarea class="form-control rqrdFld" id="prdtDesc" cols="2" placeholder="Description" rows="3"></textarea>
                                            </div>
                                        </div>                                                            
                                        <div class="form-group form-group-sm">
                                            <label for="prdtCrncy" class="control-label col-md-4">Currency:</label>
                                            <div  class="col-md-8">
                                                <select class="form-control" id="prdtCrncy" >
                                                    <option selected="true" disabled="disabled">--Please Select--</option>
                                                    <?php
                                                    $brghtStr = "";
                                                    $isDynmyc = FALSE;
                                                    $titleRslt = getLovValues("%", "Both", 0, 100, $brghtStr, getLovID("Bank Currencies (Select List)"), $isDynmyc, -1, "", "");
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
                                            <label for="isStaffAccountProduct" class="control-label col-md-4">Staff Product?:</label>
                                            <div  class="col-md-8">
                                                <select class="form-control" id="isStaffAccountProduct" onchange="">
                                                    <option value="No">No</option>                                            
                                                    <option value="Yes">Yes</option>
                                                </select>
                                            </div>
                                        </div>
                                    </fieldset>
                                </div>
                                <div class="col-lg-4 svngsDiv" id="InterestDiv"> 
                                    <fieldset class="basic_person_fs5"><legend class="basic_person_lg1">Interest</legend>
                                        <div  class="form-group form-group-sm">
                                            <label for="chrgInterest" class="control-label col-md-5">Pay Interest?:</label>
                                            <div  class="col-md-7">
                                                <?php
                                                $chkdYes = "";
                                                $chkdNo = "checked=\"\"";
                                                ?>
                                                <label class="radio-inline"><input onchange="onChangePayInterest()" type="radio" name="chrgInterest" id="chrgInterest1" value="YES" <?php echo $chkdYes; ?>>YES</label>
                                                <label class="radio-inline"><input onchange="onChangePayInterest()" type="radio" name="chrgInterest" id="chrgInterest2" value="NO" <?php echo $chkdNo; ?>>NO</label>
                                            </div>                                                              
                                        </div>                                                        
                                        <div class="form-group form-group-sm">
                                            <label for="intRate" class="control-label col-md-5">Rate:</label>
                                            <div  class="col-md-7">
                                                <div  class="col-md-12 input-group" style="padding-right: 0px !important; padding-left: 0px !important;">
                                                    <input class="form-control" id="intRate" type = "number" min="0" placeholder="Rate" value="" readonly="true"/>
                                                    <label class="btn btn-primary btn-file input-group-addon" style="padding-left: 2px !important;">
                                                        % Per Annum
                                                    </label>
                                                </div>
                                            </div>
                                        </div>																	                                                                        
                                        <div class="form-group form-group-sm">
                                            <label for="accrualFrqncy" class="control-label col-md-5">Accrual Frequency:</label>
                                            <div  class="col-md-7">
                                                <select class="form-control" id="accrualFrqncy" disabled="disabled">
                                                    <option selected="true" disabled="disabled">--Please Select--</option>
                                                    <?php
                                                    $brghtStr = "";
                                                    $isDynmyc = FALSE;
                                                    $titleRslt = getLovValues("%", "Both", 0, 100, $brghtStr, getLovID("Interest Accrual Frequency"), $isDynmyc, -1, "", "");
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
                                            <label for="calcMethod" class="control-label col-md-5">Calculation Method:</label>
                                            <div  class="col-md-7">
                                                <select class="form-control" id="calcMethod" disabled="disabled">
                                                    <option selected="true" disabled="disabled">--Please Select--</option>
                                                    <?php
                                                    $brghtStr = "";
                                                    $isDynmyc = FALSE;
                                                    $titleRslt = getLovValues("%", "Both", 0, 100, $brghtStr, getLovID("Interest Calculation Methods"), $isDynmyc, -1, "", "");
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
                                            <label for="crdtnFrqncy" class="control-label col-md-5">Crediting Period:</label>
                                            <div  class="col-md-7">
                                                <select class="form-control" id="crdtnFrqncy" disabled="disabled">
                                                    <option selected="true" disabled="disabled">--Please Select--</option>
                                                    <option value="monthly">Monthly</option>
                                                    <option value="every2months">Every 2 Months</option>
                                                    <option value="quarterly">Quarterly</option>
                                                    <option value="semi-anually">Semi-Annually</option>
                                                    <option value="annually">Annually</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group form-group-sm">
                                            <label for="crdtnType" class="control-label col-md-5">Crediting Type:</label>
                                            <div  class="col-md-7">
                                                <select class="form-control" id="crdtnType" disabled="disabled">
                                                    <option selected="true" disabled="disabled">--Please Select--</option>
                                                    <option value="Manually">Manually</option>
                                                    <option value="Automatic">Automatic</option>
                                                </select>
                                            </div>
                                        </div>                                        
                                        <div style="display:none !important;" class="form-group form-group-sm">
                                            <label for="pstnFrqncy" class="control-label col-md-4">Posting Frequency:</label>
                                            <div  class="col-md-8">
                                                <select class="form-control" id="pstnFrqncy" >
                                                    <option value="">&nbsp;</option>
                                                    <?php
                                                    $brghtStr = "";
                                                    $isDynmyc = FALSE;
                                                    $titleRslt = getLovValues("%", "Both", 0, 100, $brghtStr, getLovID("Interest Posting Frequencies"), $isDynmyc, -1, "", "");
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
                                        <div style="display:none !important;" class="form-group form-group-sm">
                                            <label for="calcType" class="control-label col-md-4">Calculation Type:</label>
                                            <div  class="col-md-8">
                                                <select class="form-control" id="calcType" >
                                                    <option value="">&nbsp;</option>
                                                    <?php
                                                    $brghtStr = "";
                                                    $isDynmyc = FALSE;
                                                    $titleRslt = getLovValues("%", "Both", 0, 100, $brghtStr, getLovID("Interest Calculation Types"), $isDynmyc, -1, "", "");
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
                                    </fieldset>   
                                </div>
                                <div class="col-lg-4 svngsDiv" id="FeesNBalDiv">
                                    <fieldset class="basic_person_fs5"><legend class="basic_person_lg1">Fees and Balances</legend>
                                        <div class="form-group form-group-sm">
                                            <label for="entryFees" class="control-label col-md-4">Entry Fee:</label>
                                            <div  class="col-md-8">
                                                <input class="form-control" id="entryFees" type = "number" min="0" placeholder="Entry" value="0.00"/>
                                            </div>
                                        </div>                                        
                                       <div class="form-group form-group-sm">
                                            <label for="closeFees" class="control-label col-md-4">Close Fee:</label>
                                            <div  class="col-md-8">
                                                <input class="form-control" id="closeFees" type = "number" min="0" placeholder="Close" value="0.00"/>
                                            </div>
                                        </div> 
                                        <div class="form-group form-group-sm">
                                            <label for="reOpenFees" class="control-label col-md-4">Re-Open Fee:</label>
                                            <div  class="col-md-8">
                                                <input class="form-control" id="reOpenFees" type = "number" min="0" placeholder="Re-Open" value="0.00"/>
                                            </div>
                                        </div>    
                                        <div class="form-group form-group-sm">
                                            <label for="depstOpnBal" class="control-label col-md-5">Opening Balance:</label>
                                            <div  class="col-md-7">
                                                <input class="form-control" id="depstOpnBal" type = "number" min="0" placeholder="Opening Balance" value="0.00"/>
                                            </div>
                                        </div>
                                        <div class="form-group form-group-sm">
                                            <label for="accBalMin" class="control-label col-md-5">Minimum Balance:</label>
                                            <div  class="col-md-7">
                                                <input class="form-control" id="accBalMin" type = "number" min="0" placeholder="Running Balance Min" value="0.00"/>
                                            </div>
                                        </div>
                                        <div class="form-group form-group-sm">
                                            <label for="minBalForInterest" class="control-label col-md-6">Min Balance for Interest:</label>
                                            <div  class="col-md-6">
                                                <input class="form-control" id="minBalForInterest" type = "number" min="0" placeholder="Min Balance for Interest" value="0.00"/>
                                            </div>
                                        </div>
                                    </fieldset>
                                </div>  
                                <div class="col-lg-6" <?php echo $showHideDivInvstmnt; ?> id="InvstmntDiv"> 
                                    <fieldset class="basic_person_fs5"><legend class="basic_person_lg1">Investment</legend>																	                                                                        
                                        <div class="form-group form-group-sm">
                                            <label for="invstmntType" class="control-label col-md-4">Investment Type:</label>
                                            <div  class="col-md-8">
                                                <select class="form-control" id="invstmntType" >
                                                    <option selected="true" disabled="disabled">--Please Select--</option>
                                                    <option value="Treasury Bills">Treasury Bills</option>
                                                    <option value="Fixed Deposit">Fixed Deposit</option>
                                                </select>
                                            </div>
                                        </div>  
                                        <div class="form-group form-group-sm">
                                            <label for="drtnNo" class="control-label col-md-4">Duration:</label>
                                            <div  class="col-md-8">
                                                <div class="input-group col-md-12">
                                                    <div  class="col-md-4" style="padding-left:0px !important;">
                                                        <input <?php echo $mkReadOnly; ?> class="form-control rqrdFld" id="drtnNo" type = "number" min="0" placeholder="" value=""/>
                                                    </div>
                                                    <div  class="col-md-8" style="padding-left:0px !important; padding-right: 0px !important; ">
                                                        <select <?php echo $mkReadOnlyDsbld; ?> class="form-control" id="drtnType" >
                                                            <?php
                                                            $sltdDay = "";
                                                            $sltdYear = "";
                                                            if ($tenor_type == "Day") {
                                                                $sltdDay = "selected";
                                                            } else if ($tenor_type == "Year") {
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
                                            <label for="intRate1" class="control-label col-md-4">Interest Rate:</label>
                                            <div  class="col-md-8">
                                                <div  class="col-md-12 input-group" style="padding-right: 0px !important; padding-left: 0px !important;">
                                                    <input <?php echo $mkReadOnly; ?> class="form-control" id="intRate1" type = "number" min="0" placeholder="Interest Rate" value=""/>
                                                    <label class="btn btn-primary btn-file input-group-addon" style="padding-left: 2px !important;">
                                                        % Per Annum
                                                    </label>
                                                </div>
                                            </div>                                           
                                        </div>                                       
                                        <div class="form-group form-group-sm">
                                            <label for="dscntRate" class="control-label col-md-4">Discount Rate:</label>
                                            <div  class="col-md-8">
                                                <div  class="col-md-12 input-group" style="padding-right: 0px !important; padding-left: 0px !important;">
                                                    <input <?php echo $mkReadOnly; ?> class="form-control" id="dscntRate" type = "number" min="0" placeholder="Discount Rate" value="0.00"/>
                                                    <label class="btn btn-primary btn-file input-group-addon" style="padding-left: 2px !important;">
                                                        % Per Annum
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group form-group-sm">
                                            <label for="minAmount" class="control-label col-md-4">Amount Range:</label>
                                            <div  class="col-md-8">
                                                <div  class="col-md-12 input-group" style="padding-right: 0px !important; padding-left: 0px !important;">
                                                    <div class="col-md-6" style="padding-left:0px;">
                                                        <input <?php echo $mkReadOnly; ?> class="form-control rqrdFld" id="minAmount" type = "number" min="0" placeholder="Minimum" value="1.00"/>
                                                    </div>
                                                    <div class="col-md-6" style="padding-left:0px;padding-right: 0px;">
                                                        <input <?php echo $mkReadOnly; ?> class="form-control" id="maxAmount" type = "number" min="0" placeholder="Maximum" value=""/>
                                                    </div>
                                                </div>
                                                <!--<input class="form-control rqrdFld" id="minAmount" type = "number" min="0" placeholder="Minimum Amount" value="1.00"/>-->
                                            </div>
                                        </div> 
                                        <div class="form-group form-group-sm">
                                            <label for="invstmntFeeFlat" class="control-label col-md-4">Charge Fees:</label>
                                            <div  class="col-md-8">
                                                <div  class="col-md-12 input-group" style="padding-right: 0px !important; padding-left: 0px !important;">
                                                    <div class="col-md-8" style="padding-left:0px;padding-right: 0px;">
                                                        <div class="input-group">
                                                            <input <?php echo $mkReadOnly; ?> class="form-control" id="invstmntFeeFlat" type = "number" min="0" placeholder="Flat" value=""/>
                                                            <label class="btn btn-primary btn-file input-group-addon" style="padding-left: 2px !important;">
                                                                GHS
                                                            </label>
                                                            <input <?php echo $mkReadOnly; ?> class="form-control" id="invstmntFeePrcnt" type = "number" min="0" placeholder="Percent" value=""/>
                                                            <label class="btn btn-primary btn-file input-group-addon" style="padding-left: 2px !important;">
                                                                %
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4" style="padding-left:0px;padding-right: 0px;">
                                                        <select <?php echo $mkReadOnlyDsbld; ?> class="form-control" id="feeApplctnType" >
                                                            <?php
                                                            $sltdDay = "";
                                                            $sltdYear = "";
                                                            $sltdNoFee = "";
                                                            $sltdMtrty = "";
                                                            ?>
                                                            <option disabled="disabled" selected="true">**When?**</option>
                                                            <option value="No Fee" <?php echo $sltdNoFee; ?>>No Fee</option>
                                                            <option value="Processing" <?php echo $sltdDay; ?>>Processing</option>
                                                            <option value="Liquidating" <?php echo $sltdYear; ?>>Liquidating</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>                                                                                           
                                    </fieldset>   
                                </div>                                
                            </div>   
                            <div class="row svngsDiv" ><!-- ROW 2 -->                              
                                <div class="col-lg-6">
                                    <fieldset class="basic_person_fs5"><legend class="basic_person_lg1">Eligible Customer Types</legend>
                                        <div class="form-group form-group-sm">
                                            <label for="indCust" class="control-label col-lg-4">Individual Customers:</label>
                                            <div class="form-check col-lg-8">
                                                <label class="form-check-label">
                                                    <input type="checkbox" class="form-check-input" id="indCust" name="indCust">
                                                    YES
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group form-group-sm">
                                            <label for="corpCust" class="control-label col-lg-4">Corporate Customers:</label>
                                            <div class="form-check col-lg-8">
                                                <label class="form-check-label" style="margin-left: 30">
                                                    <input type="checkbox" class="form-check-input" id="corpCust" name="corpCust">
                                                    YES
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group form-group-sm">
                                            <label for="custGrp" class="control-label col-lg-4">Customer Groups:</label>
                                            <div class="form-check col-lg-8">
                                                <label class="form-check-label">
                                                    <input type="checkbox" class="form-check-input" id="custGrp" name="custGrp">
                                                    YES
                                                </label>
                                            </div>
                                        </div>
                                    </fieldset>                                                
                                </div>
                                <div class="col-lg-6">
                                    <fieldset class="basic_person_fs5"><legend class="basic_person_lg1">General</legend>
                                        <div class="form-group form-group-sm">
                                            <label for="chrgCOT" class="control-label col-lg-4">Charge COT?:</label>
                                            <div  class="col-lg-8">
                                                <?php
                                                $chkdYes = "";
                                                $chkdNo = "checked=\"\"";
                                                ?>
                                                <label class="radio-inline"><input onchange="onChangeChargeCOT();" type="radio" name="chrgCOT" id="chrgCOT1" value="YES" <?php echo $chkdYes; ?>>YES</label>
                                                <label class="radio-inline"><input onchange="onChangeChargeCOT();" type="radio" name="chrgCOT" id="chrgCOT2" value="NO" <?php echo $chkdNo; ?>>NO</label>
                                            </div>
                                        </div>
                                        <div class="form-group form-group-sm">
                                            <label for="cotFreeWtdwlNo" class="control-label col-md-4">COT Free Withdrawal No.:</label>
                                            <div  class="col-md-3">
                                                <input class="form-control" id="cotFreeWtdwlNo" type = "number" min="0" placeholder="COT No." value="0" readonly=""/>
                                            </div>
                                            <div  class="col-md-5">&nbsp;</div>
                                        </div>
                                        <div class="form-group form-group-sm">
                                            <label for="useAsColtrl" class="control-label col-lg-4">Use as Collateral?:</label>
                                            <div  class="col-lg-8">
                                                <?php
                                                $chkdYes = "";
                                                $chkdNo = "checked=\"\"";
                                                ?>
                                                <label class="radio-inline"><input type="radio" name="useAsColtrl" value="YES" <?php echo $chkdYes; ?>>YES</label>
                                                <label class="radio-inline"><input type="radio" name="useAsColtrl" value="NO" <?php echo $chkdNo; ?>>NO</label>
                                            </div>
                                        </div> 
                                    </fieldset>                                                
                                </div>
                            </div>                             
                            <div class="row svngsDiv"><!-- ROW 3 -->
                                <div class="col-lg-12"> 
                                    <!--<fieldset class="basic_person_fs3">-->
                                        <legend class="basic_person_lg1">Withdrawal</legend> 
                                        <div class="row" style="margin:5px !important;text-align: centre !important;"><!--subrow 1-->
                                            <div class="col-lg-6">
                                                <div class="form-group form-group-sm">
                                                    <label for="wtdwlLimitType" class="control-label col-lg-3">Limit Type:</label>
                                                    <div  class="col-lg-7">
                                                        <?php
                                                        $chkdNo = "";
                                                        $chkdYes = "checked=\"\"";
                                                        ?>
                                                        <label class="radio-inline"><input onchange="onChangeWdwlLmtType();" type="radio" name="wtdwlLimitType" id="wtdwlLimitType1" value="NO LIMIT" <?php echo $chkdYes; ?>>NO LIMIT</label>
                                                        <label class="radio-inline"><input onchange="onChangeWdwlLmtType();" type="radio" name="wtdwlLimitType" id="wtdwlLimitType2" value="DAILY" <?php echo $chkdNo; ?>>DAILY</label>
                                                        <!--<label class="radio-inline"><input type="radio" name="wtdwlLimitType" value="WEEKLY" <?php echo $chkdNo; ?>>WEEKLY</label>
                                                        <label class="radio-inline"><input type="radio" name="wtdwlLimitType" value="MONTHLY" <?php echo $chkdNo; ?>>MONTHLY</label>
                                                        <label class="radio-inline"><input type="radio" name="wtdwlLimitType" value="QUARTERLY" <?php echo $chkdNo; ?>>QUARTERLY</label>
                                                        <label class="radio-inline"><input type="radio" name="wtdwlLimitType" value="SEMI-ANNUAL" <?php echo $chkdNo; ?>>SEMI-ANNUAL</label>
                                                        <label class="radio-inline"><input type="radio" name="wtdwlLimitType" value="ANNUAL" <?php echo $chkdNo; ?>>ANNUAL</label>-->
                                                    </div>
                                                </div>
                                            </div>
                                            <div  class="col-lg-6">
                                                <div class="form-group form-group-sm">
                                                    <label for="ovdrftAllowed" class="control-label col-lg-4">Allow Overdraft?:</label>
                                                    <div  class="col-lg-8">
                                                        <?php
                                                        $chkdYes = "";
                                                        $chkdNo = "checked=\"\"";
                                                        ?>
                                                        <label class="radio-inline"><input onchange="onChangeAllwOvdrft();" type="radio" name="ovdrftAllowed" id="ovdrftAllowed1" value="YES" <?php echo $chkdYes; ?>>YES</label>
                                                        <label class="radio-inline"><input onchange="onChangeAllwOvdrft();" type="radio" name="ovdrftAllowed" id="ovdrftAllowed2" value="NO" <?php echo $chkdNo; ?>>NO</label>
                                                    </div>
                                                </div>                                                                
                                            </div>                                            
                                        </div>
                                        <div class="row" style="margin:5px !important;"><!--subrow 2-->
                                            <div class="col-lg-12">
                                                <div class="col-lg-6" style="padding-left:0px !important;">
                                                    <fieldset class="basic_person_fs5"><legend class="basic_person_lg1">Withdrawal Limit</legend>
                                                        <div  class="col-lg-5">                                                            
                                                            <div class="form-group form-group-sm">
                                                                <label for="wtdwlLimitNo" class="control-label col-md-4">No.:</label>
                                                                <div  class="col-md-8">
                                                                    <input class="form-control" id="wtdwlLimitNo" type = "number" min="0" placeholder="Limit No." value="0" readonly="true"/>
                                                                </div>
                                                            </div>                                                                
                                                        </div>
                                                        <div  class="col-lg-7">
                                                            <div class="form-group form-group-sm">
                                                                <label for="wtdwlLimitAmt" class="control-label col-md-4">Amount:</label>
                                                                <div  class="col-md-8">
                                                                    <input class="form-control" id="wtdwlLimitAmt" type = "number" min="0" placeholder="Limit Amount" value="0.00" readonly="true"/>
                                                                </div>
                                                            </div>                                                                
                                                        </div> 
                                                    </fieldset>
                                                </div>
                                                <div class="col-lg-6" style="padding-right:0px !important;">
                                                    <fieldset class="basic_person_fs5"><legend class="basic_person_lg1">Overdraft Penalty</legend>
                                                        <div  class="col-lg-7">
                                                            <div class="form-group form-group-sm">
                                                                <label for="wtdwlPnltyFlat" class="control-label col-md-4">Flat:</label>
                                                                <div  class="col-md-8">
                                                                    <input class="form-control" id="wtdwlPnltyFlat" type = "number" min="0" placeholder="Flat" value="0.00" readonly="true"/>
                                                                </div>
                                                            </div>                                                                
                                                        </div>
                                                        <div  class="col-lg-5">
                                                            <div class="form-group form-group-sm">
                                                                <label for="wtdwlPnltyPercent" class="control-label col-md-4">Percent:</label>
                                                                <div  class="col-md-8">
                                                                    <input class="form-control" id="wtdwlPnltyPercent" type = "number" min="0" placeholder="Percent" value="0.00" readonly="true"/>
                                                                </div>
                                                            </div>                                                                
                                                        </div>
                                                    </fieldset>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row" style="margin:5px !important;"><!--subrow 4-->
                                            <div class="col-lg-12"> 
                                                <fieldset class="basic_person_fs5"><legend class="basic_person_lg1">Commission on Turnover</legend> 
                                                    <div  class="col-md-12">
                                                        <button id="addCmsnBtn" type="button" class="btn btn-default" style="margin-bottom: 5px; display: none !important;" onclick="getSvngsWtdwlForm('myLovModal', 'myLovModalBody', 'myLovModalTitle', 'svngsWtdwlCmsnForm', '', 'Add Commission', 12, <?php echo $subPgNo; ?>, 5, '', -1);">
                                                            <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                            Add Commission
                                                        </button>
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
                                                            </tbody>
                                                        </table>
                                                    </div> 
                                                </fieldset>
                                            </div>                                                                
                                        </div>
                                    <!--</fieldset>-->
                                </div>
                            </div> 
                        </form>  
                    </div>                
                </div>          
                <?php
            } else {
                
                $tblNm1 = "mcf.mcf_prdt_savings";

                $cnt = getSvngsPrdtDataChngPndngCount($pkID);
                if($cnt > 0){
                    $tblNm1 = "mcf.mcf_prdt_savings_hstrc";        
                }                  
                
                $shwHydNtlntySts = "style=\"display:none !important;\"";                
                /* Read Only */
                $result = get_SavingsPrdtDet($pkID, $tblNm1);

                while ($row = loc_db_fetch_array($result)) {
                    $prdtCrncy = getGnrlRecNm("mcf.mcf_currencies", "crncy_id", "iso_code", $row[4]);  
                    $trnsStatus = $row[40];
                    if($trnsStatus == "Unauthorized" || $trnsStatus == "Initiated"){
                        $shwHydNtlntySts = "style=\"display:block !important;\"";
                    }    
                    
		    if($vwtypActn == "VIEW" || ($trnsStatus == "Initiated" || $trnsStatus == "Approved" || $trnsStatus == "Unauthorized" || $trnsStatus == "Authorized")){
                        $mkReadOnly = "readonly=\"readonly\"";
                        $mkReadOnlyDsbld = "disabled=\"true\"";
                    }    
                    
                    if($row[5] == "Investment"){
                        $showHideDivInvstmnt = "style=\"display:block !important;\""; 
                        $showHideDivSavings = "style=\"display:none !important;\"";
                        $prdtDivCls = "col-md-6";
                    }
                    
                    if($cnt > 0){
                        $tblNmAuthrzd = "mcf.mcf_prdt_savings";        
                        $resultAuthrzd = get_SavingsPrdtDet($pkID, $tblNmAuthrzd);
                        while ($rowATZ = loc_db_fetch_array($resultAuthrzd)) {
                            $rowATZ0 = $rowATZ[0]; $rowATZ1 = $rowATZ[1]; $rowATZ2 = $rowATZ[2]; $rowATZ3 = $rowATZ[3]; $rowATZ4 = $rowATZ[4];
                            $rowATZ5 = $rowATZ[5]; $rowATZ6 = $rowATZ[6]; $rowATZ7 = $rowATZ[7]; $rowATZ8 = $rowATZ[8]; $rowATZ9 = $rowATZ[9];
                            $rowATZ10 = $rowATZ[10]; $rowATZ11 = $rowATZ[11]; $rowATZ12 = $rowATZ[12]; $rowATZ13 = $rowATZ[13]; $rowATZ14 = $rowATZ[14];
                            $rowATZ15 = $rowATZ[15]; $rowATZ16 = $rowATZ[16]; $rowATZ17 = $rowATZ[17]; $rowATZ18 = $rowATZ[18]; $rowATZ19 = $rowATZ[19];
                            $rowATZ20 = $rowATZ[20]; $rowATZ21 = $rowATZ[21]; $rowATZ22 = $rowATZ[22]; $rowATZ23 = $rowATZ[23]; $rowATZ24 = $rowATZ[24];
                            $rowATZ25 = $rowATZ[25]; $rowATZ26 = $rowATZ[26]; $rowATZ27 = $rowATZ[27]; $rowATZ28 = $rowATZ[28]; $rowATZ29 = $rowATZ[29];
                            $rowATZ30 = $rowATZ[30]; $rowATZ31 = $rowATZ[31]; $rowATZ32 = $rowATZ[32]; $rowATZ33 = $rowATZ[33]; $rowATZ34 = $rowATZ[34];
                            $rowATZ35 = $rowATZ[35]; $rowATZ36 = $rowATZ[36]; $rowATZ37 = $rowATZ[37]; $rowATZ38 = $rowATZ[38]; $rowATZ39 = $rowATZ[39];
                            $rowATZ40 = $rowATZ[40]; $rowATZ41 = $rowATZ[41]; $rowATZ42 = $rowATZ[42]; $rowATZ43 = $rowATZ[43]; $rowATZ44 = $rowATZ[44]; 
                            $rowATZ45 = $rowATZ[45];
                            
                            $prdtCrncyATZ = getGnrlRecNm("mcf.mcf_currencies", "crncy_id", "iso_code", $rowATZ[4]); 
                        }               
                    }                     
                    /* Edit */
                    ?>
                    <input class="form-control" id="myFormPage" type = "hidden" placeholder="myFormPage" value="1"/>
                    <input class="form-control" id="addOrEditForm" type = "hidden" placeholder="addOrEditForm" value="Edit"/>
                    <input class="form-control" id="rvsnTtl" type = "hidden" placeholder="Revision Total" value="<?php echo $row[41]; ?>"/>
                    <input class="form-control" id="status" type = "hidden" placeholder="status" value="<?php echo $trnsStatus; ?>"/>
                    <div class="row" style="margin: 0px 0px 10px 0px !important;">
                        <div class="col-md-12" style="padding:0px 0px 0px 0px !important;">
                            <button type="button" class="btn btn-default btn-sm phone-only-btn" onclick="openATab('#allmodules', 'grp=17&typ=1&pg=7&vtyp=0');">Main Parameters</button>
                            <button type="button" class="btn btn-default btn-sm phone-only-btn" onclick="openATab('#prfBPStndEvntsAcctEDT', 'grp=17&typ=1&pg=7&vtyp=1');">Standard Events Accounting</button>
                        </div>
                    </div>
                    <div class="row" style="margin: 0px 0px 10px 0px !important;">
                        <div class="col-md-12" style="padding:0px 1px 5px 1px !important;">
                            <div style="float:left;"> 
                                <button type="button" class="btn btn-default btn-sm" style="height:30px;" id="myVmsTrnsStatusBtn">
                                        <span style="font-weight:bold;">Status: </span><span style="color:<?php echo $rqstatusColor; ?>;font-weight: bold;"><?php echo $trnsStatus; ?></span>
                                </button>
                                <?php if($vwtypActn != "VIEW") { ?>
                                <button type="button" class="btn btn-default btn-sm" style="height:30px;" onclick="getProductsForm('myFormsModalLg', 'myFormsModalBodyLg', 'myFormsModalTitleLg', 'Edit Savings and Investment Product', 12, <?php echo $subPgNo; ?>,0,'EDIT', <?php echo $pkID; ?>,'','indCustTableRow1');" data-toggle="tooltip" title="Reload Transaction">
                                        <img src="cmn_images/refresh.bmp" style="padding-right: 2px; height:17px; width:auto; position: relative; vertical-align: middle;">
                                </button>
                                <?php } ?>
                                <button type="button" class="btn btn-default" style="height:30px;" onclick="getOneMcfDocsForm_Gnrl(<?php echo $pkID; ?>,'SAVINGS AND INVESTMENTS PRODUCTS', 140, 'Savings and Investments Attachments');" data-toggle="tooltip" data-placement="bottom" title = "Attached Documents">
                                    <img src="cmn_images/adjunto.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                </button>                                                    
                            </div>
                            <div class="" style="float:right;">
                                <?php if($vwtypActn == "EDIT" ) { if ($trnsStatus == "Authorized" || $trnsStatus == "Approved" && (test_prmssns($dfltPrvldgs[65], $mdlNm) === true)) { ?>
                                <button type="button" class="btn btn-default btn-sm" style="" onclick="modifyAutrzSvngsPrdtRqst(<?php echo $pgNo; ?>, <?php echo $subPgNo; ?>, <?php echo $vwtyp; ?>);">
                                    <img src="cmn_images/edit32.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">
                                    MODIFY DATA
                                </button>
                                 <?php } else if ($trnsStatus == "Incomplete" || $trnsStatus == "Rejected" || $trnsStatus == "Withdrawn") { ?>  
                                <button type="button" class="btn btn-default btn-sm" style="" onclick="saveProductMainData(<?php echo $pgNo; ?>, <?php echo $subPgNo; ?>, <?php echo $vwtyp; ?>, 0);">
                                    <img src="cmn_images/FloppyDisk.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">
                                    SAVE
                                </button>
                                <button type="button" class="btn btn-default btn-sm" style="" onclick="saveProductMainData(<?php echo $pgNo; ?>, <?php echo $subPgNo; ?>, <?php echo $vwtyp; ?>, 1);">
                                    <img src="cmn_images/Emailcon.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">
                                    SUBMIT
                                </button>                                    
                                 <?php } else if ($trnsStatus == "Unauthorized") { ?>    
                                    <?php if (didAuthorizerSubmit($pkID, $usrID)) { ?>
                                    <button type="button" class="btn btn-default btn-sm" style="height:30px;" onclick="wthdrwRjctAutrzSvngsPrdtDataRqst('WITHDRAW', <?php echo $pgNo; ?>, <?php echo $subPgNo; ?>, <?php echo $vwtyp; ?>);"><img src="cmn_images/withdraw_rqst.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Withdraw&nbsp;</button>                                                                          
                                    <?php } else {  ?>
                                    <button type="button" class="btn btn-default btn-sm" style="height:30px;" onclick="wthdrwRjctAutrzSvngsPrdtDataRqst('REJECT', <?php echo $pgNo; ?>, <?php echo $subPgNo; ?>, <?php echo $vwtyp; ?>);"><img src="cmn_images/withdraw_rqst.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Reject&nbsp;</button>                                         
                                    <?php if (test_prmssns($dfltPrvldgs[67], $mdlNm) === true) { ?>
                                    <button type="button" class="btn btn-default btn-sm" style="height:30px;" onclick="wthdrwRjctAutrzSvngsPrdtDataRqst('AUTHORIZE', <?php echo $pgNo; ?>, <?php echo $subPgNo; ?>, <?php echo $vwtyp; ?>);"><img src="cmn_images/Stamp-512.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Authorize&nbsp;</button>                                                                                                                                     
                                <?php } } } } ?>   
                            </div>
                        </div>
                    </div>                    
                    <ul class="nav nav-tabs rho-hideable-tabs" style="margin-top:-10px !important;">
                        <li class="active"><a data-toggle="tab" data-rhodata="&pg=7&vtyp=0" href="#prflBCHomeEDT" id="prflBCHomeEDTtab">Main Parameters</a></li>
                        <li id="standardEvtns" style="display: block;"><a data-toggle="tabajxprfledt" data-rhodata="&pg=7&vtyp=1" href="#prfBPStndEvntsAcctEDT" onclick="newSvngsPrdtOpenATab('#prfBPStndEvntsAcctEDT', 'grp=17&typ=1&pg=<?php echo $pgNo; ?>&subPgNo=<?php echo $subPgNo; ?>&vtyp=1&vtypActn=<?php echo $vwtypActn; ?>&prdtID=<?php echo $prdtID; ?>&rvsnTtlAPD=<?php echo $row[41]; ?>');" id="prfBPStndEvntsAcctEDTtab">Standard Events Accounting</a></li>
                        <!--<li id="customEvnts" style="display: none;"><a data-toggle="tabajxprfledt" data-rhodata="&pg=7&vtyp=2" href="#prflCstmEvntsAcctEDT" onclick="newSvngsPrdtOpenATab('#prflCstmEvntsAcctEDT', 'grp=17&typ=1&pg=<?php echo $pgNo; ?>&subPgNo=<?php echo $subPgNo; ?>&vtyp=2&vtypActn=VIEW&prdtID=<?php echo $prdtID; ?>');" id="prflCstmEvntsAcctEDTtab">Custom Events Accounting</a></li>-->
                    </ul>
                    <div class="row">                  
                        <div class="col-md-12">
                            <div class="custDiv"> 
                                <div class="tab-content">
                                    <div id="prflBCHomeEDT" class="tab-pane fadein active" style="border:none !important;">                                             
                                        <form class="form-horizontal">
                                            <div class="row"><!-- ROW 1 -->                              
                                                <div class="<?php echo $prdtDivCls; ?>" id="PrdtDiv">
                                                    <fieldset class="basic_person_fs5"><legend class="basic_person_lg1">Product</legend>
                                                        <div class="form-group form-group-sm">
                                                            <?php echo dsplyCntrlLbl($cnt, $row[5], $rowATZ5, "col-md-4", "Type", "prdtType"); ?>
                                                            <div  class="col-md-8">
                                                                <select <?php echo $mkReadOnlyDsbld; ?> class="form-control" id="prdtType" onchange="showHideSvngsInvstmntElmnts()">
                                                                    <option selected="true" disabled="disabled">--Please Select--</option>
                                                                    <?php
                                                                    $brghtStr = "";
                                                                    $isDynmyc = FALSE;
                                                                    $titleRslt = getLovValues("%", "Both", 0, 100, $brghtStr, getLovID("Savings Product Types"), $isDynmyc, -1, "", "");
                                                                    while ($titleRow = loc_db_fetch_array($titleRslt)) {
                                                                        $selectedTxt = "";
                                                                        if ($row[5] == $titleRow[0]) {
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
                                                            <?php echo dsplyCntrlLbl($cnt, $row[1], $rowATZ1, "col-md-4", "Code", "pdtCode"); ?>
                                                            <div class="col-md-8">
                                                                <input <?php echo $mkReadOnly; ?> class="form-control rqrdFld" id="pdtCode" min="0" <?php echo $prdtCdeLnStr; ?> type = "number" placeholder="Code" value="<?php echo $row[1]; ?>"/>
                                                                <!--CUSTOMER ID-->
                                                                <input class="form-control" id="prdtID" type = "hidden" placeholder="Product ID" value="<?php echo $row[0]; ?>"/>                                                                                                                                           
                                                            </div>
                                                        </div> 
                                                        <div class="form-group form-group-sm">
                                                            <?php echo dsplyCntrlLbl($cnt, $row[2], $rowATZ2, "col-md-4", "Name", "prdtName"); ?>
                                                            <div  class="col-md-8">
                                                                <input <?php echo $mkReadOnly; ?> class="form-control rqrdFld" id="prdtName" type = "text" placeholder="Name" value="<?php echo $row[2]; ?>"/>
                                                            </div>
                                                        </div>     
                                                        <div class="form-group form-group-sm">
                                                            <?php echo dsplyCntrlLbl($cnt, $row[3], $rowATZ3, "col-md-4", "Description", "prdtDesc"); ?>
                                                            <div  class="col-md-8">
                                                                <textarea <?php echo $mkReadOnly; ?> class="form-control rqrdFld" id="prdtDesc" cols="2" placeholder="Description" rows="2"><?php echo $row[3]; ?></textarea>
                                                            </div>
                                                        </div>                                                            
                                                        <div class="form-group form-group-sm">
                                                            <?php echo dsplyCntrlLbl($cnt, $prdtCrncy, $prdtCrncyATZ, "col-md-4", "Currency", "prdtCrncy"); ?>
                                                            <div  class="col-md-8">
                                                                <select <?php echo $mkReadOnlyDsbld; ?> class="form-control" id="prdtCrncy" >
                                                                    <option selected="true" disabled="disabled">--Please Select--</option>
                                                                    <?php
                                                                    $brghtStr = "";
                                                                    $isDynmyc = TRUE;
                                                                    $titleRslt = getLovValues("%", "Both", 0, 100, $brghtStr, getLovID("Bank Currencies (Select List)"), $isDynmyc, -1, "", "");
                                                                    while ($titleRow = loc_db_fetch_array($titleRslt)) {
                                                                        
                                                                        echo $titleRow[0];
                                                                        $selectedTxt = "";
                                                                        if ($prdtCrncy == $titleRow[0]) {
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
                                                            <?php  echo dsplyCntrlLbl($cnt, $row[45], $rowATZ45, "col-md-4", "Staff Product?", "isStaffAccountProduct"); ?>
                                                            <div  class="col-md-8">
                                                                <select <?php echo $mkReadOnlyDsbld; ?> class="form-control" id="isStaffAccountProduct" onchange="">
                                                                    <?php
                                                                    $sltdNo = "";
                                                                    $sltdYes = "";
                                                                    if ($row[45] === "No") {
                                                                        $sltdNo = "selected=\"selected\"";
                                                                    } else if ($row[45] === "Yes") {
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
                                                <div class="col-lg-4 svngsDiv" id="InterestDiv" <?php echo $showHideDivSavings; ?>> 
                                                    <fieldset class="basic_person_fs5"><legend class="basic_person_lg1">Interest</legend>
                                                        <div  class="form-group form-group-sm">
                                                            <?php echo dsplyCntrlLbl($cnt, $row[6], $rowATZ6, "col-md-5", "Pay Interest?", "chrgInterest"); ?>
                                                            <div  class="col-md-7">
                                                                <?php
                                                                $chkdYes = "checked=\"\"";
                                                                $chkdNo = "";
                                                                if ($row[6] == "NO") {
                                                                    $chkdNo = "checked=\"\"";
                                                                    $chkdYes = "";
                                                                }
                                                                ?>
                                                                <label class="radio-inline"><input <?php echo $mkReadOnlyDsbld; ?>  onchange="onChangePayInterest()" type="radio" name="chrgInterest" id="chrgInterest1" value="YES" <?php echo $chkdYes; ?>>YES</label>
                                                                <label class="radio-inline"><input <?php echo $mkReadOnlyDsbld; ?> onchange="onChangePayInterest()" type="radio" name="chrgInterest" id="chrgInterest2" value="NO" <?php echo $chkdNo; ?>>NO</label>
                                                            </div>                                                              
                                                        </div>                                                        
                                                        <div class="form-group form-group-sm">
                                                            <?php echo dsplyCntrlLbl($cnt, $row[7], $rowATZ7, "col-md-5", "Rate", "intRate"); ?>
                                                            <div  class="col-md-7">
                                                                <div  class="col-md-12 input-group" style="padding-right: 0px !important; padding-left: 0px !important;">
                                                                    <input <?php echo $mkReadOnly; ?> class="form-control" id="intRate" type = "number" min="0" placeholder="Rate" value="<?php echo $row[7]; ?>"/>
                                                                    <label class="btn btn-primary btn-file input-group-addon" style="padding-left: 2px !important;">
                                                                        % Per Annum
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>																	                                                                        
                                                        <div class="form-group form-group-sm">
                                                            <?php echo dsplyCntrlLbl($cnt, $row[8], $rowATZ8, "col-md-5", "Accrual Frequency", "accrualFrqncy"); ?>
                                                            <div  class="col-md-7">
                                                                <select <?php echo $mkReadOnlyDsbld; ?> class="form-control" id="accrualFrqncy">
                                                                    <option selected="true" disabled="disabled">--Please Select--</option>
                                                                    <?php
                                                                    $brghtStr = "";
                                                                    $isDynmyc = FALSE;
                                                                    $titleRslt = getLovValues("%", "Both", 0, 100, $brghtStr, getLovID("Interest Accrual Frequency"), $isDynmyc, -1, "", "");
                                                                    while ($titleRow = loc_db_fetch_array($titleRslt)) {
                                                                        $selectedTxt = "";
                                                                        if ($row[8] == $titleRow[0]) {
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
                                                            <?php echo dsplyCntrlLbl($cnt, $row[10], $rowATZ10, "col-md-5", "Calculation Method", "calcMethod"); ?>
                                                            <div  class="col-md-7">
                                                                <select <?php echo $mkReadOnlyDsbld; ?> class="form-control" id="calcMethod">
                                                                    <option selected="true" disabled="disabled">--Please Select--</option>
                                                                    <?php
                                                                    $brghtStr = "";
                                                                    $isDynmyc = FALSE;
                                                                    $titleRslt = getLovValues("%", "Both", 0, 100, $brghtStr, getLovID("Interest Calculation Methods"), $isDynmyc, -1, "", "");
                                                                    while ($titleRow = loc_db_fetch_array($titleRslt)) {
                                                                        $selectedTxt = "";
                                                                        if ($row[10] == $titleRow[0]) {
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
                                                            <?php echo dsplyCntrlLbl($cnt, $row[31], $rowATZ31, "col-md-5", "Crediting Period", "crdtnFrqncy"); ?>
                                                            <div  class="col-md-7">
                                                                <select <?php echo $mkReadOnlyDsbld; ?> class="form-control" id="crdtnFrqncy">
                                                                    <?php 
                                                                    $selectedTxtMnthly = "";
                                                                    $selectedTxtEvry2Mnths = "";
                                                                    $selectedTxtQrtly = "";
                                                                    $selectedTxtSmiAnly = "";
                                                                    $selectedTxtAnly = "";
                                                                    if ($row[31] == "monthly") {
                                                                        $selectedTxtMnthly = "selected=\"selected\"";
                                                                    } else if ($row[31] == "every2months") {
                                                                        $selectedTxtEvry2Mnths = "selected=\"selected\"";
                                                                    } else if ($row[31] == "quarterly") {
                                                                        $selectedTxtQrtly = "selected=\"selected\"";
                                                                    } else if ($row[31] == "semi-anually") {
                                                                        $selectedTxtSmiAnly = "selected=\"selected\"";
                                                                    } else if ($row[31] == "annually") {
                                                                        $selectedTxtAnly = "selected=\"selected\"";
                                                                    }
                                                                    ?>
                                                                    <option selected="true" disabled="disabled">--Please Select--</option>
                                                                    <option value="monthly" <?php echo $selectedTxtMnthly; ?>>Monthly</option>
                                                                    <option value="every2months" <?php echo $selectedTxtEvry2Mnths; ?>>Every 2 Months</option>
                                                                    <option value="quarterly" <?php echo $selectedTxtQrtly; ?>>Quarterly</option>
                                                                    <option value="semi-anually" <?php echo $selectedTxtSmiAnly; ?>>Semi-Annually</option>
                                                                    <option value="annually" <?php echo $selectedTxtAnly; ?>>Annually</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-group-sm">
                                                            <?php echo dsplyCntrlLbl($cnt, $row[32], $rowATZ32, "col-md-5", "Crediting Type", "crdtnType"); ?>
                                                            <div  class="col-md-7">
                                                                <select <?php echo $mkReadOnlyDsbld; ?> class="form-control" id="crdtnType">
                                                                    <?php
                                                                    $selectedTxtMnly = "";
                                                                    $selectedTxtAtmtc = "";
                                                                    if ($row[32] == "Manually") {
                                                                        $selectedTxtMnly = "selected=\"selected\"";
                                                                    } else if ($row[32] == "Automatic") {
                                                                        $selectedTxtAtmtc = "selected=\"selected\"";
                                                                    }
                                                                    ?>
                                                                    <option selected="true" disabled="disabled">--Please Select--</option>
                                                                    <option value="Manually" <?php echo $selectedTxtMnly; ?>>Manually</option>
                                                                    <option value="Automatic" <?php echo $selectedTxtAtmtc; ?>>Automatic</option>
                                                                </select>
                                                            </div>
                                                        </div>                                        
                                                        <div style="display:none !important;" class="form-group form-group-sm">
                                                            <label for="pstnFrqncy" class="control-label col-md-4">Posting Frequency:</label>
                                                            <div  class="col-md-8">
                                                                <select <?php echo $mkReadOnlyDsbld; ?> class="form-control" id="pstnFrqncy" >
                                                                    <option value="">&nbsp;</option>
                                                                    <?php
                                                                    $brghtStr = "";
                                                                    $isDynmyc = FALSE;
                                                                    $titleRslt = getLovValues("%", "Both", 0, 100, $brghtStr, getLovID("Interest Posting Frequencies"), $isDynmyc, -1, "", "");
                                                                    while ($titleRow = loc_db_fetch_array($titleRslt)) {
                                                                        $selectedTxt = "";
                                                                        if ($row[9] == $titleRow[0]) {
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
                                                        <div style="display:none !important;" class="form-group form-group-sm">
                                                            <label for="calcType" class="control-label col-md-4">Calculation Type:</label>
                                                            <div  class="col-md-8">
                                                                <select class="form-control" id="calcType" >
                                                                    <option value="">&nbsp;</option>
                                                                    <?php
                                                                    $brghtStr = "";
                                                                    $isDynmyc = FALSE;
                                                                    $titleRslt = getLovValues("%", "Both", 0, 100, $brghtStr, getLovID("Interest Calculation Types"), $isDynmyc, -1, "", "");
                                                                    while ($titleRow = loc_db_fetch_array($titleRslt)) {
                                                                        $selectedTxt = "";
                                                                        if ($row[11] == $titleRow[0]) {
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
                                                    </fieldset>   
                                                </div>
                                                <div class="col-lg-4 svngsDiv" id="FeesNBalDiv" <?php echo $showHideDivSavings; ?>>
                                                    <fieldset class="basic_person_fs5"><legend class="basic_person_lg1">Fees and Balances</legend>
                                                        <div class="form-group form-group-sm">
                                                            <?php echo dsplyCntrlLbl($cnt, $row[26], $rowATZ26, "col-md-4", "Entry Fee", "entryFees"); ?>
                                                            <div  class="col-md-8">
                                                                <input <?php echo $mkReadOnly; ?> class="form-control" id="entryFees" type = "number" min="0" placeholder="Entry" value="<?php echo $row[26]; ?>"/>
                                                            </div>
                                                        </div>                                        
                                                       <div class="form-group form-group-sm">
                                                           <?php echo dsplyCntrlLbl($cnt, $row[27], $rowATZ27, "col-md-4", "Close Fee", "closeFees"); ?>
                                                            <div  class="col-md-8">
                                                                <input <?php echo $mkReadOnly; ?> class="form-control" id="closeFees" type = "number" min="0" placeholder="Close" value="<?php echo $row[27]; ?>"/>
                                                            </div>
                                                        </div> 
                                                        <div class="form-group form-group-sm">
                                                            <?php echo dsplyCntrlLbl($cnt, $row[28], $rowATZ28, "col-md-4", "Re-Open", "reOpenFees"); ?>
                                                            <div  class="col-md-8">
                                                                <input <?php echo $mkReadOnly; ?> class="form-control" id="reOpenFees" type = "number" min="0" placeholder="Re-Open" value="<?php echo $row[28]; ?>"/>
                                                            </div>
                                                        </div>    
                                                        <div class="form-group form-group-sm">
                                                            <?php echo dsplyCntrlLbl($cnt, $row[22], $rowATZ22, "col-md-5", "Opening Balance", "depstOpnBal"); ?>
                                                            <div  class="col-md-7">
                                                                <input <?php echo $mkReadOnly; ?> class="form-control" id="depstOpnBal" type = "number" min="0" placeholder="Opening Balance" value="<?php echo $row[22]; ?>"/>
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-group-sm">
                                                            <?php echo dsplyCntrlLbl($cnt, $row[23], $rowATZ23, "col-md-5", "Minimum Balance", "accBalMin"); ?>
                                                            <div  class="col-md-7">
                                                                <input <?php echo $mkReadOnly; ?> class="form-control" id="accBalMin" type = "number" min="0" placeholder="Running Balance Min" value="<?php echo $row[23]; ?>"/>
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-group-sm">
                                                            <?php echo dsplyCntrlLbl($cnt, $row[33], $rowATZ33, "col-md-6", "Min Balance for Interest", "minBalForInterest"); ?>
                                                            <div  class="col-md-6">
                                                                <input <?php echo $mkReadOnly; ?> class="form-control" id="minBalForInterest" type = "number" min="0" placeholder="Min Balance for Interest" value="<?php echo $row[33]; ?>"/>
                                                            </div>
                                                        </div>
                                                    </fieldset>
                                                </div>  
                                                <div class="col-lg-6" <?php echo $showHideDivInvstmnt; ?> id="InvstmntDiv"> 
                                                    <fieldset class="basic_person_fs5"><legend class="basic_person_lg1">Investment</legend>																	                                                                        
                                                        <div class="form-group form-group-sm">
                                                            <?php echo dsplyCntrlLbl($cnt, $row[36], $rowATZ36, "col-md-4", "Investment Type", "invstmntType"); ?>
                                                            <div  class="col-md-8">
                                                                <select <?php echo $mkReadOnlyDsbld; ?> class="form-control" id="invstmntType" >
                                                                    <?php
                                                                    $selectedTxtTbills = "";
                                                                    $selectedTxtFxDpst = "";
                                                                    if ($row[36] == "Treasury Bills") {
                                                                        $selectedTxtTbills = "selected=\"selected\"";
                                                                    } else if ($row[36] == "Fixed Deposit") {
                                                                        $selectedTxtFxDpst = "selected=\"selected\"";
                                                                    }
                                                                    ?>
                                                                    <option value="Treasury Bills" <?php echo $selectedTxtTbills; ?>>Treasury Bills</option>
                                                                    <option value="Fixed Deposit" <?php echo $selectedTxtFxDpst; ?>>Fixed Deposit</option>
                                                                </select>
                                                            </div>
                                                        </div>  
                                                        <div class="form-group form-group-sm">
                                                            <?php echo dsplyCntrlLbl($cnt, $row[34]." ".$row[35], $rowATZ34." ".$rowATZ35, "col-md-4", "Duration", "drtnNo"); ?>
                                                            <div  class="col-md-8">
                                                                <div class="input-group col-md-12">
                                                                    <div  class="col-md-4" style="padding-left:0px !important;">
                                                                        <input <?php echo $mkReadOnly; ?> class="form-control rqrdFld" id="drtnNo" type = "number" min="0" placeholder="" value="<?php echo $row[34]; ?>"/>
                                                                    </div>
                                                                    <div  class="col-md-8" style="padding-left:0px !important; padding-right: 0px !important; ">
                                                                        <select <?php echo $mkReadOnlyDsbld; ?> class="form-control" id="drtnType" >
                                                                            <?php
                                                                            $sltdDay = "";
                                                                            $sltdYear = "";
                                                                            if ($row[35] == "Day") {
                                                                                $sltdDay = "selected";
                                                                            } else if ($row[35] == "Year") {
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
                                                            <?php echo dsplyCntrlLbl($cnt, $row[7], $rowATZ7, "col-md-4", "Interest Rate", "intRate1"); ?>
                                                            <div  class="col-md-8">
                                                                <div  class="col-md-12 input-group" style="padding-right: 0px !important; padding-left: 0px !important;">
                                                                    <input <?php echo $mkReadOnly; ?> class="form-control" id="intRate1" type = "number" min="0" placeholder="Interest Rate" value="<?php echo $row[7]; ?>"/>
                                                                    <label class="btn btn-primary btn-file input-group-addon" style="padding-left: 2px !important;">
                                                                        % Per Annum
                                                                    </label>
                                                                </div>
                                                            </div>                                           
                                                        </div>                                       
                                                        <div class="form-group form-group-sm">
                                                            <?php echo dsplyCntrlLbl($cnt, $row[37], $rowATZ37, "col-md-4", "Discount Rate", "dscntRate"); ?>
                                                            <div  class="col-md-8">
                                                                <div  class="col-md-12 input-group" style="padding-right: 0px !important; padding-left: 0px !important;">
                                                                    <input <?php echo $mkReadOnly; ?> class="form-control" id="dscntRate" type = "number" min="0" placeholder="Discount Rate" value="<?php echo $row[37]; ?>"/>
                                                                    <label class="btn btn-primary btn-file input-group-addon" style="padding-left: 2px !important;">
                                                                        % Per Annum
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-group-sm">
                                                            <?php echo dsplyCntrlLbl($cnt, $row[39]." - ".$row[38], $rowATZ39." - ".$rowATZ38, "col-md-4", "Amount Range", "minAmount"); ?>
                                                            <div  class="col-md-8">
                                                                <div  class="col-md-12 input-group" style="padding-right: 0px !important; padding-left: 0px !important;">
                                                                    <div class="col-md-6" style="padding-left:0px;">
                                                                        <input <?php echo $mkReadOnly; ?> class="form-control rqrdFld" id="minAmount" type = "number" min="0" placeholder="Minimum" value="<?php echo $row[39]; ?>"/>
                                                                    </div>
                                                                    <div class="col-md-6" style="padding-left:0px;padding-right: 0px;">
                                                                        <input <?php echo $mkReadOnly; ?> class="form-control" id="maxAmount" type = "number" min="0" placeholder="Maximum" value="<?php echo $row[38]; ?>"/>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-group-sm">
                                                            <?php echo dsplyCntrlLbl($cnt, $row[42]." ".$row[43]." When ", $rowATZ42." ".$rowATZ43, "col-md-4", "Charge Fees", "invstmntFeeFlat"); ?>
                                                            <!--<label for="minAmount" class="control-label col-md-4">Charge Fees:</label>-->
                                                            <div  class="col-md-8">
                                                                <div  class="col-md-12 input-group" style="padding-right: 0px !important; padding-left: 0px !important;">
                                                                    <div class="col-md-8" style="padding-left:0px;padding-right: 0px;">
                                                                        <div class="input-group">
                                                                            <input <?php echo $mkReadOnly; ?> class="form-control" id="invstmntFeeFlat" type = "number" min="0" placeholder="Flat" value="<?php echo $row[42]; ?>"/>
                                                                            <label class="btn btn-primary btn-file input-group-addon" style="padding-left: 2px !important;">
                                                                                GHS
                                                                            </label>
                                                                            <input <?php echo $mkReadOnly; ?> class="form-control" id="invstmntFeePrcnt" type = "number" min="0" placeholder="Percent" value="<?php echo $row[43]; ?>"/>
                                                                            <label class="btn btn-primary btn-file input-group-addon" style="padding-left: 2px !important;">
                                                                                %
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4" style="padding-left:0px;padding-right: 0px;">
                                                                        <select <?php echo $mkReadOnlyDsbld; ?> class="form-control" id="feeApplctnType" >
                                                                            <?php
                                                                            $sltdDay = "";
                                                                            $sltdNoFee = "";
                                                                            $sltdYear = "";
                                                                            $sltdMtrty = "";
                                                                            if ($row[44] == "No Fee") {
                                                                                $sltdNoFee = "selected";
                                                                            } else if ($row[44] == "Processing") {
                                                                                $sltdDay = "selected";
                                                                            } else if ($row[44] == "Liquidating") {
                                                                                $sltdYear = "selected";
                                                                            } else if ($row[44] == "Maturity") {
                                                                                $sltdMtrty = "selected";
                                                                            }
                                                                            ?>
                                                                            <option disabled="disabled" selected="true">**When?**</option>
                                                                            <option value="No Fee" <?php echo $sltdNoFee; ?>>No Fee</option>
                                                                            <option value="Processing" <?php echo $sltdDay; ?>>Processing</option>
                                                                            <option value="Liquidating" <?php echo $sltdYear; ?>>Liquidating</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>                                                              
                                                    </fieldset>   
                                                </div>                                
                                            </div>   
                                            <div class="row svngsDiv" <?php echo $showHideDivSavings; ?>><!-- ROW 2 -->                              
                                                <div class="col-lg-6">
                                                    <fieldset class="basic_person_fs5"><legend class="basic_person_lg1">Eligible Customer Types</legend>
                                                        <div class="form-group form-group-sm">
                                                            <?php echo dsplyCntrlLbl($cnt, $row[12], $rowATZ12, "col-md-4", "Individual Customers", "indCust"); ?>
                                                            <div class="form-check col-lg-8">
                                                                <label class="form-check-label">
                                                                    <?php
                                                                    $chkd = "checked=\"\"";
                                                                    if ($row[12] == "NO") {
                                                                        $chkd = "";
                                                                    }
                                                                    ?>
                                                                    <input <?php echo $mkReadOnlyDsbld; ?> type="checkbox" class="form-check-input" id="indCust" name="indCust" <?php echo $chkd; ?>>
                                                                    YES
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-group-sm">
                                                            <?php echo dsplyCntrlLbl($cnt, $row[13], $rowATZ13, "col-md-4", "Corporate Customers", "corpCust"); ?>
                                                            <div class="form-check col-lg-8">
                                                                <label class="form-check-label" style="margin-left: 30">
                                                                    <?php
                                                                    $chkd = "checked=\"\"";
                                                                    if ($row[13] == "NO") {
                                                                        $chkd = "";
                                                                    }
                                                                    ?>
                                                                    <input <?php echo $mkReadOnlyDsbld; ?> type="checkbox" class="form-check-input" id="corpCust" name="corpCust"  <?php echo $chkd; ?>>
                                                                    YES
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-group-sm">
                                                            <?php echo dsplyCntrlLbl($cnt, $row[14], $rowATZ14, "col-md-4", "Customer Groups", "custGrp"); ?>
                                                            <div class="form-check col-lg-8">
                                                                <label class="form-check-label">
                                                                    <?php
                                                                    $chkd = "checked=\"\"";
                                                                    if ($row[14] == "NO") {
                                                                        $chkd = "";
                                                                    }
                                                                    ?>
                                                                    <input <?php echo $mkReadOnlyDsbld; ?> type="checkbox" class="form-check-input" id="custGrp" name="custGrp"  <?php echo $chkd; ?>>
                                                                    YES
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </fieldset>                                                
                                                </div>
                                                <div class="col-lg-6">
                                                    <fieldset class="basic_person_fs5"><legend class="basic_person_lg1">General</legend>
                                                        <div class="form-group form-group-sm">
                                                            <?php echo dsplyCntrlLbl($cnt, $row[20], $rowATZ20, "col-md-4", "Charge COT?", "chrgCOT"); ?>
                                                            <div  class="col-lg-8">
                                                                <?php
                                                                $chkdYes = "checked=\"\"";
                                                                $chkdNo = "";
                                                                if ($row[20] == "NO") {
                                                                    $chkdNo = "checked=\"\"";
                                                                    $chkdYes = "";
                                                                }
                                                                ?>
                                                                <label class="radio-inline"><input <?php echo $mkReadOnlyDsbld; ?> onchange="onChangeChargeCOT();" type="radio" name="chrgCOT" id="chrgCOT1" value="YES" <?php echo $chkdYes; ?>>YES</label>
                                                                <label class="radio-inline"><input <?php echo $mkReadOnlyDsbld; ?> onchange="onChangeChargeCOT();" type="radio" name="chrgCOT" id="chrgCOT2" value="NO" <?php echo $chkdNo; ?>>NO</label>
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-group-sm">
                                                            <?php echo dsplyCntrlLbl($cnt, $row[21], $rowATZ21, "col-md-4", "COT Free Withdrawal No.", "cotFreeWtdwlNo"); ?>
                                                            <div  class="col-md-3">
                                                                <input <?php echo $mkReadOnly; ?> class="form-control" id="cotFreeWtdwlNo" type = "number" min="0" placeholder="COT No." value="<?php echo $row[21]; ?>"/>
                                                            </div>
                                                            <div  class="col-md-5">&nbsp;</div>
                                                        </div>
                                                        <div class="form-group form-group-sm">
                                                            <?php echo dsplyCntrlLbl($cnt, $row[25], $rowATZ25, "col-md-4", "Use as Collateral?", "useAsColtrl"); ?>
                                                            <div  class="col-lg-8">
                                                                <?php
                                                                $chkdYes = "checked=\"\"";
                                                                $chkdNo = "";
                                                                if ($row[25] == "NO") {
                                                                    $chkdNo = "checked=\"\"";
                                                                    $chkdYes = "";
                                                                }
                                                                ?>
                                                                <label class="radio-inline"><input <?php echo $mkReadOnlyDsbld; ?> type="radio" name="useAsColtrl" value="YES" <?php echo $chkdYes; ?>>YES</label>
                                                                <label class="radio-inline"><input <?php echo $mkReadOnlyDsbld; ?> type="radio" name="useAsColtrl" value="NO" <?php echo $chkdNo; ?>>NO</label>
                                                            </div>
                                                        </div> 
                                                    </fieldset>                                                
                                                </div>
                                            </div>                             
                                            <div class="row svngsDiv" <?php echo $showHideDivSavings; ?>><!-- ROW 3 -->
                                                <div class="col-lg-12"> 
                                                    <!--<fieldset class="basic_person_fs3">-->
                                                        <legend class="basic_person_lg1">Withdrawal</legend> 
                                                        <div class="row" style="margin:5px !important;text-align: centre !important;"><!--subrow 1-->
                                                            <div class="col-lg-6">
                                                                <div class="form-group form-group-sm">
                                                                    <?php echo dsplyCntrlLbl($cnt, $row[15], $rowATZ15, "col-lg-3", "Limit Type", "wtdwlLimitType"); ?>
                                                                    <div  class="col-lg-7">
                                                                        <?php
                                                                            $chkdNL = "";
                                                                            $chkdDL = "";

                                                                            if ($row[15] == "NO LIMIT") {
                                                                                $chkdNL = "checked=\"\"";
                                                                            } else if ($row[15] == "DAILY") {
                                                                                $chkdDL = "checked=\"\"";
                                                                            }
                                                                            ?>
                                                                        <label class="radio-inline"><input <?php echo $mkReadOnlyDsbld; ?> onchange="onChangeWdwlLmtType();" type="radio" name="wtdwlLimitType" id="wtdwlLimitType1" value="NO LIMIT" <?php echo $chkdNL; ?>>NO LIMIT</label>
                                                                        <label class="radio-inline"><input <?php echo $mkReadOnlyDsbld; ?> onchange="onChangeWdwlLmtType();" type="radio" name="wtdwlLimitType" id="wtdwlLimitType2" value="DAILY" <?php echo $chkdDL; ?>>DAILY</label>
                                                                        <!--<label class="radio-inline"><input type="radio" name="wtdwlLimitType" value="WEEKLY" <?php echo $chkdNo; ?>>WEEKLY</label>
                                                                        <label class="radio-inline"><input type="radio" name="wtdwlLimitType" value="MONTHLY" <?php echo $chkdNo; ?>>MONTHLY</label>
                                                                        <label class="radio-inline"><input type="radio" name="wtdwlLimitType" value="QUARTERLY" <?php echo $chkdNo; ?>>QUARTERLY</label>
                                                                        <label class="radio-inline"><input type="radio" name="wtdwlLimitType" value="SEMI-ANNUAL" <?php echo $chkdNo; ?>>SEMI-ANNUAL</label>
                                                                        <label class="radio-inline"><input type="radio" name="wtdwlLimitType" value="ANNUAL" <?php echo $chkdNo; ?>>ANNUAL</label>-->
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div  class="col-lg-6">
                                                                <div class="form-group form-group-sm">
                                                                    <?php echo dsplyCntrlLbl($cnt, $row[24], $rowATZ24, "col-lg-4", "Allow Overdraft?", "ovdrftAllowed"); ?>
                                                                    <div  class="col-lg-8">
                                                                        <?php
                                                                        $chkdYes = "checked=\"\"";
                                                                        $chkdNo = "";
                                                                        if ($row[24] == "NO") {
                                                                            $chkdNo = "checked=\"\"";
                                                                            $chkdYes = "";
                                                                        }
                                                                        ?>
                                                                        <label class="radio-inline"><input onchange="onChangeAllwOvdrft();" type="radio" name="ovdrftAllowed" id="ovdrftAllowed1" value="YES" <?php echo $chkdYes; ?>>YES</label>
                                                                        <label class="radio-inline"><input onchange="onChangeAllwOvdrft();" type="radio" name="ovdrftAllowed" id="ovdrftAllowed2" value="NO" <?php echo $chkdNo; ?>>NO</label>
                                                                    </div>
                                                                </div>                                                                
                                                            </div>                                            
                                                        </div>
                                                        <div class="row" style="margin:5px !important;"><!--subrow 2-->
                                                            <div class="col-lg-12">
                                                                <div class="col-lg-6" style="padding-left:0px !important;">
                                                                    <fieldset class="basic_person_fs5"><legend class="basic_person_lg1">Withdrawal Limit</legend>
                                                                        <div  class="col-lg-5">                                                            
                                                                            <div class="form-group form-group-sm">
                                                                                <?php echo dsplyCntrlLbl($cnt, $row[16], $rowATZ16, "col-md-4", "No.", "wtdwlLimitNo"); ?>
                                                                                <div  class="col-md-8">
                                                                                    <input <?php echo $mkReadOnly; ?> class="form-control" id="wtdwlLimitNo" type = "number" min="0" placeholder="Limit No." value="<?php echo $row[16]; ?>"/>
                                                                                </div>
                                                                            </div>                                                                
                                                                        </div>
                                                                        <div  class="col-lg-7">
                                                                            <div class="form-group form-group-sm">
                                                                                <?php echo dsplyCntrlLbl($cnt, $row[17], $rowATZ17, "col-md-4", "Amount", "wtdwlLimitAmt"); ?>
                                                                                <div  class="col-md-8">
                                                                                    <input <?php echo $mkReadOnly; ?> class="form-control" id="wtdwlLimitAmt" type = "number" min="0" placeholder="Limit Amount" value="<?php echo $row[17]; ?>"/>
                                                                                </div>
                                                                            </div>                                                                
                                                                        </div> 
                                                                    </fieldset>
                                                                </div>
                                                                <div class="col-lg-6" style="padding-right:0px !important;">
                                                                    <fieldset class="basic_person_fs5"><legend class="basic_person_lg1">Overdraft Penalty</legend>
                                                                        <div  class="col-lg-7">
                                                                            <div class="form-group form-group-sm">
                                                                                <?php echo dsplyCntrlLbl($cnt, $row[18], $rowATZ18, "col-md-4", "Flat", "wtdwlPnltyFlat"); ?>
                                                                                <div  class="col-md-8">
                                                                                    <input <?php echo $mkReadOnly; ?> class="form-control" id="wtdwlPnltyFlat" type = "number" min="0" placeholder="Flat" value="<?php echo $row[18]; ?>"/>
                                                                                </div>
                                                                            </div>                                                                
                                                                        </div>
                                                                        <div  class="col-lg-5">
                                                                            <div class="form-group form-group-sm">
                                                                                <?php echo dsplyCntrlLbl($cnt, $row[19], $rowATZ19, "col-md-4", "Percent", "wtdwlPnltyPercent"); ?>
                                                                                <div  class="col-md-8">
                                                                                    <input <?php echo $mkReadOnly; ?> class="form-control" id="wtdwlPnltyPercent" type = "number" min="0" placeholder="Percent" value="<?php echo $row[19]; ?>"/>
                                                                                </div>
                                                                            </div>                                                                
                                                                        </div>
                                                                    </fieldset>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row" style="margin:5px !important;"><!--subrow 4-->
                                                            <div class="col-lg-12"> 
                                                                <fieldset class="basic_person_fs5"><legend class="basic_person_lg1">Commission on Turnover</legend> 
                                                                    <div  class="col-md-12">
                                                                        <?php
                                                                        if($vwtypActn == "EDIT" && ($trnsStatus == "Incomplete" || $trnsStatus == "Rejected" || $trnsStatus == "Withdrawn")){ ?>
                                                                        <button id="addCmsnBtn" type="button" class="btn btn-default" style="margin-bottom: 5px; display: block !important;" onclick="getSvngsWtdwlForm('myLovModal', 'myLovModalBody', 'myLovModalTitle', 'svngsWtdwlCmsnForm', '', 'Add Commission', 12, <?php echo $subPgNo; ?>, 5, '', -1);">
                                                                            <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                            Add Commission
                                                                        </button>
                                                                        <?php } ?>
                                                                        <table id="wtdwlCmsnTblAdd" class="table table-striped table-bordered table-responsive" cellspacing="0" width="100%" style="width:100%;">
                                                                            <thead>
                                                                                <tr>
                                                                                    <?php if($vwtypActn == "EDIT" && ($trnsStatus == "Incomplete" || $trnsStatus == "Rejected" || $trnsStatus == "Withdrawn")){ ?>
                                                                                    <th>...</th>
                                                                                    <th>...</th>
                                                                                    <?php } ?>
                                                                                    <th>No.</th>
                                                                                    <th>Low Range</th>
                                                                                    <th>High Range</th>
                                                                                    <th>Amount Flat</th>
                                                                                    <th>Amount Percent</th>
                                                                                    <th>Remarks</th>
                                                                                    <th style="display:none;">Remarks</th>
                                                                                    <th <?php echo $shwHydNtlntySts; ?>>Status</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                <?php
                                                                                $result1 = get_SavingsWtdwlCmmsn($pkID);
                                                                                $cntr = 0;
                                                                                while ($row1 = loc_db_fetch_array($result1)) {
                                                                                    $cntr++;
                                                                                    $row1ATZ0 = ""; $row1ATZ1 = ""; $row1ATZ2 = ""; $row1ATZ3 = ""; $row1ATZ4 = ""; $row1ATZ5 = "";
                                                                                    $row1ATZ6 = ""; $row1ATZ7 = "";
                                                                                    if($row1[0] > 0 && $row1[7] === "Yes"){
                                                                                        $result1ATZ = get_SavingsWtdwlCmmsnATZ($row1[0]);
                                                                                        while ($row1ATZ = loc_db_fetch_array($result1ATZ)) {
                                                                                            $row1ATZ0 = $row1ATZ[0]; $row1ATZ1 = $row1ATZ[1]; $row1ATZ2 = $row1ATZ[2]; $row1ATZ3 = $row1ATZ[3];
                                                                                            $row1ATZ4 = $row1ATZ[4]; $row1ATZ5 = $row1ATZ[5];
                                                                                        }
                                                                                    }
                                                                                    ?>
                                                                                    <tr id="svngsWtdwlCmsnTblAddRow<?php echo $cntr; ?>">
                                                                                        <?php if($vwtypActn == "EDIT" && ($trnsStatus == "Incomplete" || $trnsStatus == "Rejected" || $trnsStatus == "Withdrawn")){ ?>
                                                                                        <td>
                                                                                            <button type="button" class="btn btn-default btn-sm" onclick="getSvngsWtdwlForm('myLovModal', 'myLovModalBody', 'myLovModalTitle', 'svngsWtdwlCmsnForm', 'svngsWtdwlCmsnTblAddRow<?php echo $cntr; ?>', 'Edit Withdrawal Commission', 12, <?php echo $subPgNo; ?>, 5, 'EDIT', <?php echo $row1[0]; ?>);" style="padding:2px !important;">
                                                                                                    <!--<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>-->
                                                                                                <img src="cmn_images/edit32.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                                            </button>
                                                                                        </td>
                                                                                        <td>
                                                                                            <button type="button" class="btn btn-default btn-sm" onclick="deleteSvngsWtdwlCmsn(<?php echo $row1[0]; ?>,<?php echo $pkID; ?>,'<?php echo $row1[7]; ?>');" style="padding:2px !important;">
                                                                                                    <!--<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>-->
                                                                                                <img src="cmn_images/delete.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                                            </button>
                                                                                        </td>
                                                                                        <?php } ?>
                                                                                        <td>
                                                                                            <?php echo $cntr; ?>
                                                                                        </td>
                                                                                        <td><?php echo dsplyTblData($row1[1], $row1ATZ1, $row1[0], $row1[7]); ?></td>
                                                                                        <td><?php echo dsplyTblData($row1[2], $row1ATZ2, $row1[0], $row1[7]); ?></td>
                                                                                        <td><?php echo dsplyTblData($row1[3], $row1ATZ3, $row1[0], $row1[7]); ?></td>
                                                                                        <td><?php echo dsplyTblData($row1[4], $row1ATZ4, $row1[0], $row1[7]); ?></td>
                                                                                        <td><?php echo dsplyTblData($row1[5], $row1ATZ5, $row1[0], $row1[7]); ?></td>
                                                                                        <td style="display:none;"><?php echo $row1[0]; ?></td>
                                                                                        <td <?php echo $shwHydNtlntySts; ?>>
                                                                                            <?php 
                                                                                            if($row1[0] < 0){
                                                                                                echo "<span style='color:red;'><b>Deleted</b></span>";
                                                                                            } else  {
                                                                                               if($row1[7] === "No"){
                                                                                                    echo "<span style='color:blue;'><b>New</b></span>";
                                                                                               } else {
                                                                                                   echo "&nbsp;";
                                                                                               }
                                                                                            }
                                                                                            ?>
                                                                                        </td>
                                                                                    </tr>
                                                                                <?php } ?>																			
                                                                            </tbody>
                                                                        </table>
                                                                    </div> 
                                                                </fieldset>
                                                            </div>                                                                
                                                        </div>
                                                    <!--</fieldset>-->
                                                </div>
                                            </div> 
                                        </form>   
                                    </div>
                                    <div id="prfBPStndEvntsAcctEDT" class="tab-pane fade" style="border:none !important;"></div>
                                    <div id="prflCstmEvntsAcctEDT" class="tab-pane fade" style="border:none !important;"></div>      
                                </div>                        
                            </div>                         
                        </div>                
                    </div>          
                    <?php
                }
            }
        } else if ($vwtyp == "1") {
            /* STANDARD ACCOUNTING EVENTS */
            $rvsnTtlAPD = isset($_POST['rvsnTtlAPD']) ? cleanInputData($_POST['rvsnTtlAPD']) : 0;
            $prdtTypeSEA = isset($_POST['prdtTypeSEA']) ? cleanInputData($_POST['prdtTypeSEA']) : '';
            
            if ($vwtypActn === "ADD_NA") {
                ?>
                <div class="row" style="margin: 0px 0px 10px 0px !important;">
                    <div class="col-md-9" style="padding:0px 0px 0px 15px !important;">&nbsp;</div>   
                    <div class="col-md-1" style="padding:0px 1px 0px 1px !important;"><button type="button" class="btn btn-default btn-sm" style="width:100% !important;" onclick="saveSvngsPrdtStdAcctnEvents(<?php echo $pgNo; ?>, <?php echo $subPgNo; ?>, <?php echo $vwtyp; ?>);"><img src="cmn_images/FloppyDisk.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">SAVE</button></div>
                    <div class="col-md-1" style="padding:0px 1px 0px 1px !important;"><button type="button" class="btn btn-default btn-sm" style="width:100% !important;"><img src="cmn_images/Emailcon.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">SUBMIT</button></div>                        
                    <div class="col-md-1" style="padding:0px 1px 0px 1px !important;"><button type="button" class="btn btn-default btn-sm" style="width:100% !important;"><img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">RESET</button></div>                
                </div>                

                <form class="form-horizontal" id='stdEvntsForm' action='' method='post' accept-charset='UTF-8'>
                    <div class="row"><!-- ROW 1 -->
                        <div class="col-lg-4">
                            <fieldset class="basic_person_fs5"><legend class="basic_person_lg">Cash Deposit</legend>
                                <div class="form-group form-group-sm">
                                    <label for="cashDepositDbtAcc" class="control-label col-md-4">Debit Account:</label>
                                    <div  class="col-md-8">
                                        <div class="input-group">
                                            <!--SAVINGS ACCOUNTING EVENT ID-->
                                            <input class="form-control" id="svngsAccntnEvntId" type = "hidden" placeholder="Savings Accounting Event ID" value=""/>

                                            <input type="text" class="form-control" aria-label="..." id="cashDepositDbtAcc" value="<?php echo ''; ?>">
                                            <input type="hidden" id="gnrlOrgID" value="<?php echo $orgID; ?>">
                                            <input type="hidden" id="cashDepositDbtAccID" value="<?php echo ''; ?>">
                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Accounts', 'gnrlOrgID', '', '', 'radio', true, '<?php echo ""; ?>', 'cashDepositDbtAccID', 'cashDepositDbtAcc', 'clear', 1, ' AND (SELECT is_prnt_accnt FROM accb.accb_chart_of_accnts x WHERE x.accnt_id = tbl1.a::bigint) = \'0\' ');">
                                                <span class="glyphicon glyphicon-th-list"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group form-group-sm">
                                    <label for="cashDepositCrdtAcc" class="control-label col-md-4">Credit Account:</label>
                                    <div  class="col-md-8">
                                        <div class="input-group">
                                            <input type="text" class="form-control" aria-label="..." id="cashDepositCrdtAcc" value="<?php echo ''; ?>">
                                            <input type="hidden" id="gnrlOrgID" value="<?php echo $orgID; ?>">
                                            <input type="hidden" id="cashDepositCrdtAccID" value="<?php echo ''; ?>">
                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Accounts', 'gnrlOrgID', '', '', 'radio', true, '<?php echo ""; ?>', 'cashDepositCrdtAccID', 'cashDepositCrdtAcc', 'clear', 1, ' AND (SELECT is_prnt_accnt FROM accb.accb_chart_of_accnts x WHERE x.accnt_id = tbl1.a::bigint) = \'0\' ');">
                                                <span class="glyphicon glyphicon-th-list"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>  
                            </fieldset>    
                        </div>                                
                        <div class="col-lg-4">
                            <fieldset class="basic_person_fs5"><legend class="basic_person_lg">Cheque Deposit</legend>
                                <div class="form-group form-group-sm">
                                    <label for="chqDepositDbtAcc" class="control-label col-md-4">Debit Account:</label>
                                    <div  class="col-md-8">
                                        <div class="input-group">
                                            <input type="text" class="form-control" aria-label="..." id="chqDepositDbtAcc" value="<?php echo ''; ?>">
                                            <input type="hidden" id="gnrlOrgID" value="<?php echo $orgID; ?>">
                                            <input type="hidden" id="chqDepositDbtAccID" value="<?php echo ''; ?>">
                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Accounts', 'gnrlOrgID', '', '', 'radio', true, '<?php echo ""; ?>', 'chqDepositDbtAccID', 'chqDepositDbtAcc', 'clear', 1, ' AND (SELECT is_prnt_accnt FROM accb.accb_chart_of_accnts x WHERE x.accnt_id = tbl1.a::bigint) = \'0\' ');">
                                                <span class="glyphicon glyphicon-th-list"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group form-group-sm">
                                    <label for="chqDepositCrdtAcc" class="control-label col-md-4">Credit Account:</label>
                                    <div  class="col-md-8">
                                        <div class="input-group">
                                            <input type="text" class="form-control" aria-label="..." id="chqDepositCrdtAcc" value="<?php echo ''; ?>">
                                            <input type="hidden" id="gnrlOrgID" value="<?php echo $orgID; ?>">
                                            <input type="hidden" id="chqDepositCrdtAccID" value="<?php echo ''; ?>">
                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Accounts', 'gnrlOrgID', '', '', 'radio', true, '<?php echo ""; ?>', 'chqDepositCrdtAccID', 'chqDepositCrdtAcc', 'clear', 1, ' AND (SELECT is_prnt_accnt FROM accb.accb_chart_of_accnts x WHERE x.accnt_id = tbl1.a::bigint) = \'0\' ');">
                                                <span class="glyphicon glyphicon-th-list"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>  
                            </fieldset>                                                  
                        </div>
                        <div class="col-lg-4">
                            <fieldset class="basic_person_fs5"><legend class="basic_person_lg">Cash Withdrawal</legend>
                                <div class="form-group form-group-sm">
                                    <label for="cashWtdwlDbtAcc" class="control-label col-md-4">Debit Account:</label>
                                    <div  class="col-md-8">
                                        <div class="input-group">
                                            <input type="text" class="form-control" aria-label="..." id="cashWtdwlDbtAcc" value="<?php echo ''; ?>">
                                            <input type="hidden" id="gnrlOrgID" value="<?php echo $orgID; ?>">
                                            <input type="hidden" id="cashWtdwlDbtAccID" value="<?php echo ''; ?>">
                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Accounts', 'gnrlOrgID', '', '', 'radio', true, '<?php echo ""; ?>', 'cashWtdwlDbtAccID', 'cashWtdwlDbtAcc', 'clear', 1, ' AND (SELECT is_prnt_accnt FROM accb.accb_chart_of_accnts x WHERE x.accnt_id = tbl1.a::bigint) = \'0\' ');">
                                                <span class="glyphicon glyphicon-th-list"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group form-group-sm">
                                    <label for="cashWtdwlCrdtAcc" class="control-label col-md-4">Credit Account:</label>
                                    <div  class="col-md-8">
                                        <div class="input-group">
                                            <input type="text" class="form-control" aria-label="..." id="cashWtdwlCrdtAcc" value="<?php echo''; ?>">
                                            <input type="hidden" id="gnrlOrgID" value="<?php echo $orgID; ?>">
                                            <input type="hidden" id="cashWtdwlCrdtAccID" value="<?php echo ''; ?>">
                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Accounts', 'gnrlOrgID', '', '', 'radio', true, '<?php echo ""; ?>', 'cashWtdwlCrdtAccID', 'cashWtdwlCrdtAcc', 'clear', 1, ' AND (SELECT is_prnt_accnt FROM accb.accb_chart_of_accnts x WHERE x.accnt_id = tbl1.a::bigint) = \'0\' ');">
                                                <span class="glyphicon glyphicon-th-list"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>  
                            </fieldset>                                                
                        </div>
                    </div>   
                    <div class="row"><!-- ROW 2 -->
                        <div class="col-lg-4">
                            <fieldset class="basic_person_fs5"><legend class="basic_person_lg">Entry Fees</legend>
                                <div class="form-group form-group-sm">
                                    <label for="entryFeesDbtAcc" class="control-label col-md-4">Debit Account:</label>
                                    <div  class="col-md-8">
                                        <div class="input-group">
                                            <input type="text" class="form-control" aria-label="..." id="entryFeesDbtAcc" value="<?php echo ''; ?>">
                                            <input type="hidden" id="gnrlOrgID" value="<?php echo $orgID; ?>">
                                            <input type="hidden" id="entryFeesDbtAccID" value="<?php echo ''; ?>">
                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Accounts', 'gnrlOrgID', '', '', 'radio', true, '<?php echo ""; ?>', 'entryFeesDbtAccID', 'entryFeesDbtAcc', 'clear', 1, ' AND (SELECT is_prnt_accnt FROM accb.accb_chart_of_accnts x WHERE x.accnt_id = tbl1.a::bigint) = \'0\' ');">
                                                <span class="glyphicon glyphicon-th-list"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group form-group-sm">
                                    <label for="entryFeesCrdtAcc" class="control-label col-md-4">Credit Account:</label>
                                    <div  class="col-md-8">
                                        <div class="input-group">
                                            <input type="text" class="form-control" aria-label="..." id="entryFeesCrdtAcc" value="<?php echo ''; ?>">
                                            <input type="hidden" id="gnrlOrgID" value="<?php echo $orgID; ?>">
                                            <input type="hidden" id="entryFeesCrdtAccID" value="<?php echo ''; ?>">
                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Accounts', 'gnrlOrgID', '', '', 'radio', true, '<?php echo ""; ?>', 'entryFeesCrdtAccID', 'entryFeesCrdtAcc', 'clear', 1, ' AND (SELECT is_prnt_accnt FROM accb.accb_chart_of_accnts x WHERE x.accnt_id = tbl1.a::bigint) = \'0\' ');">
                                                <span class="glyphicon glyphicon-th-list"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>  
                            </fieldset>    
                        </div>                                
                        <div class="col-lg-4">
                            <fieldset class="basic_person_fs5"><legend class="basic_person_lg">Close Fees</legend>
                                <div class="form-group form-group-sm">
                                    <label for="closeFeesDbtAcc" class="control-label col-md-4">Debit Account:</label>
                                    <div  class="col-md-8">
                                        <div class="input-group">
                                            <input type="text" class="form-control" aria-label="..." id="closeFeesDbtAcc" value="<?php echo ''; ?>">
                                            <input type="hidden" id="gnrlOrgID" value="<?php echo $orgID; ?>">
                                            <input type="hidden" id="closeFeesDbtAccID" value="<?php echo ''; ?>">
                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Accounts', 'gnrlOrgID', '', '', 'radio', true, '<?php echo ""; ?>', 'closeFeesDbtAccID', 'closeFeesDbtAcc', 'clear', 1, ' AND (SELECT is_prnt_accnt FROM accb.accb_chart_of_accnts x WHERE x.accnt_id = tbl1.a::bigint) = \'0\' ');">
                                                <span class="glyphicon glyphicon-th-list"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group form-group-sm">
                                    <label for="closeFeesCrdtAcc" class="control-label col-md-4">Credit Account:</label>
                                    <div  class="col-md-8">
                                        <div class="input-group">
                                            <input type="text" class="form-control" aria-label="..." id="closeFeesCrdtAcc" value="<?php echo ''; ?>">
                                            <input type="hidden" id="gnrlOrgID" value="<?php echo $orgID; ?>">
                                            <input type="hidden" id="closeFeesCrdtAccID" value="<?php echo ''; ?>">
                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Accounts', 'gnrlOrgID', '', '', 'radio', true, '<?php echo ""; ?>', 'closeFeesCrdtAccID', 'closeFeesCrdtAcc', 'clear', 1, ' AND (SELECT is_prnt_accnt FROM accb.accb_chart_of_accnts x WHERE x.accnt_id = tbl1.a::bigint) = \'0\' ');">
                                                <span class="glyphicon glyphicon-th-list"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>  
                            </fieldset>                                                  
                        </div>
                        <div class="col-lg-4">
                            <fieldset class="basic_person_fs5"><legend class="basic_person_lg">Re-Open Fees</legend>
                                <div class="form-group form-group-sm">
                                    <label for="reopenFeesDbtAcc" class="control-label col-md-4">Debit Account:</label>
                                    <div  class="col-md-8">
                                        <div class="input-group">
                                            <input type="text" class="form-control" aria-label="..." id="reopenFeesDbtAcc" value="<?php echo ''; ?>">
                                            <input type="hidden" id="gnrlOrgID" value="<?php echo $orgID; ?>">
                                            <input type="hidden" id="reopenFeesDbtAccID" value="<?php echo ''; ?>">
                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Accounts', 'gnrlOrgID', '', '', 'radio', true, '<?php echo ""; ?>', 'reopenFeesDbtAccID', 'reopenFeesDbtAcc', 'clear', 1, ' AND (SELECT is_prnt_accnt FROM accb.accb_chart_of_accnts x WHERE x.accnt_id = tbl1.a::bigint) = \'0\' ');">
                                                <span class="glyphicon glyphicon-th-list"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group form-group-sm">
                                    <label for="reopenFeesCrdtAcc" class="control-label col-md-4">Credit Account:</label>
                                    <div  class="col-md-8">
                                        <div class="input-group">
                                            <input type="text" class="form-control" aria-label="..." id="reopenFeesCrdtAcc" value="<?php echo ''; ?>">
                                            <input type="hidden" id="gnrlOrgID" value="<?php echo $orgID; ?>">
                                            <input type="hidden" id="reopenFeesCrdtAccID" value="<?php echo ''; ?>">
                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Accounts', 'gnrlOrgID', '', '', 'radio', true, '<?php echo ""; ?>', 'reopenFeesCrdtAccID', 'reopenFeesCrdtAcc', 'clear', 1, ' AND (SELECT is_prnt_accnt FROM accb.accb_chart_of_accnts x WHERE x.accnt_id = tbl1.a::bigint) = \'0\' ');">
                                                <span class="glyphicon glyphicon-th-list"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>  
                            </fieldset>                                                
                        </div>
                    </div>    
                    <div class="row"><!-- ROW 3 -->
                        <div class="col-lg-4">
                            <fieldset class="basic_person_fs5"><legend class="basic_person_lg">Overdraft Penalty Fee</legend>
                                <div class="form-group form-group-sm">
                                    <label for="wtdwlPnltyFlatDbtAcc" class="control-label col-md-4">Debit Account:</label>
                                    <div  class="col-md-8">
                                        <div class="input-group">
                                            <input type="text" class="form-control" aria-label="..." id="wtdwlPnltyFlatDbtAcc" value="<?php echo ''; ?>">
                                            <input type="hidden" id="gnrlOrgID" value="<?php echo $orgID; ?>">
                                            <input type="hidden" id="wtdwlPnltyFlatDbtAccID" value="<?php echo ''; ?>">
                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Accounts', 'gnrlOrgID', '', '', 'radio', true, '<?php echo ""; ?>', 'wtdwlPnltyFlatDbtAccID', 'wtdwlPnltyFlatDbtAcc', 'clear', 1, ' AND (SELECT is_prnt_accnt FROM accb.accb_chart_of_accnts x WHERE x.accnt_id = tbl1.a::bigint) = \'0\' ');">
                                                <span class="glyphicon glyphicon-th-list"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group form-group-sm">
                                    <label for="wtdwlPnltyFlatCrdtAcc" class="control-label col-md-4">Credit Account:</label>
                                    <div  class="col-md-8">
                                        <div class="input-group">
                                            <input type="text" class="form-control" aria-label="..." id="wtdwlPnltyFlatCrdtAcc" value="<?php echo ''; ?>">
                                            <input type="hidden" id="gnrlOrgID" value="<?php echo $orgID; ?>">
                                            <input type="hidden" id="wtdwlPnltyFlatCrdtAccID" value="<?php echo ''; ?>">
                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Accounts', 'gnrlOrgID', '', '', 'radio', true, '<?php echo ""; ?>', 'wtdwlPnltyFlatCrdtAccID', 'wtdwlPnltyFlatCrdtAcc', 'clear', 1, ' AND (SELECT is_prnt_accnt FROM accb.accb_chart_of_accnts x WHERE x.accnt_id = tbl1.a::bigint) = \'0\' ');">
                                                <span class="glyphicon glyphicon-th-list"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>  
                            </fieldset>    
                        </div>                                
                        <div class="col-lg-4">
                            <span>&nbsp;</span>
                            <fieldset style="display:none !important;" class="basic_person_fs5"><legend class="basic_person_lg">Withdrawal Penalty Fee (%)</legend>
                                <div class="form-group form-group-sm">
                                    <label for="wtdwlPnltyPrcntDbtAcc" class="control-label col-md-4">Debit Account:</label>
                                    <div  class="col-md-8">
                                        <div class="input-group">
                                            <input type="text" class="form-control" aria-label="..." id="wtdwlPnltyPrcntDbtAcc" value="<?php echo ''; ?>">
                                            <input type="hidden" id="gnrlOrgID" value="<?php echo $orgID; ?>">
                                            <input type="hidden" id="wtdwlPnltyPrcntDbtAccID" value="<?php echo ''; ?>">
                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Accounts', 'gnrlOrgID', '', '', 'radio', true, '<?php echo ""; ?>', 'wtdwlPnltyPrcntDbtAccID', 'wtdwlPnltyPrcntDbtAcc', 'clear', 1, ' AND (SELECT is_prnt_accnt FROM accb.accb_chart_of_accnts x WHERE x.accnt_id = tbl1.a::bigint) = \'0\' ');">
                                                <span class="glyphicon glyphicon-th-list"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group form-group-sm">
                                    <label for="wtdwlPnltyPrcntCrdtAcc" class="control-label col-md-4">Credit Account:</label>
                                    <div  class="col-md-8">
                                        <div class="input-group">
                                            <input type="text" class="form-control" aria-label="..." id="wtdwlPnltyPrcntCrdtAcc" value="<?php echo ''; ?>">
                                            <input type="hidden" id="gnrlOrgID" value="<?php echo $orgID; ?>">
                                            <input type="hidden" id="wtdwlPnltyPrcntCrdtAccID" value="<?php echo ''; ?>">
                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Accounts', 'gnrlOrgID', '', '', 'radio', true, '<?php echo ""; ?>', 'wtdwlPnltyPrcntCrdtAccID', 'wtdwlPnltyPrcntCrdtAcc', 'clear', 1, ' AND (SELECT is_prnt_accnt FROM accb.accb_chart_of_accnts x WHERE x.accnt_id = tbl1.a::bigint) = \'0\' ');">
                                                <span class="glyphicon glyphicon-th-list"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>  
                            </fieldset>                                                  
                        </div>
                        <div class="col-lg-4">
                            <fieldset class="basic_person_fs5"><legend class="basic_person_lg">Commission on Turnover</legend>
                                <div class="form-group form-group-sm">
                                    <label for="cotFeeFlatDbtAcc" class="control-label col-md-4">Debit Account:</label>
                                    <div  class="col-md-8">
                                        <div class="input-group">
                                            <input type="text" class="form-control" aria-label="..." id="cotFeeFlatDbtAcc" value="<?php echo ''; ?>">
                                            <input type="hidden" id="gnrlOrgID" value="<?php echo $orgID; ?>">
                                            <input type="hidden" id="cotFeeFlatDbtAccID" value="<?php echo ''; ?>">
                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Accounts', 'gnrlOrgID', '', '', 'radio', true, '<?php echo ""; ?>', 'cotFeeFlatDbtAccID', 'cotFeeFlatDbtAcc', 'clear', 1, ' AND (SELECT is_prnt_accnt FROM accb.accb_chart_of_accnts x WHERE x.accnt_id = tbl1.a::bigint) = \'0\' ');">
                                                <span class="glyphicon glyphicon-th-list"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group form-group-sm">
                                    <label for="cotFeeFlatCrdtAcc" class="control-label col-md-4">Credit Account:</label>
                                    <div  class="col-md-8">
                                        <div class="input-group">
                                            <input type="text" class="form-control" aria-label="..." id="cotFeeFlatCrdtAcc" value="<?php echo ''; ?>">
                                            <input type="hidden" id="gnrlOrgID" value="<?php echo $orgID; ?>">
                                            <input type="hidden" id="cotFeeFlatCrdtAccID" value="<?php echo ''; ?>">
                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Accounts', 'gnrlOrgID', '', '', 'radio', true, '<?php echo ""; ?>', 'cotFeeFlatCrdtAccID', 'cotFeeFlatCrdtAcc', 'clear', 1, ' AND (SELECT is_prnt_accnt FROM accb.accb_chart_of_accnts x WHERE x.accnt_id = tbl1.a::bigint) = \'0\' ');">
                                                <span class="glyphicon glyphicon-th-list"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>  
                            </fieldset>                                                
                        </div>
                    </div>   
                    <div class="row"><!-- ROW 4 -->
                        <div class="col-lg-4">
                            <span>&nbsp;</span>
                            <fieldset style="display:none !important;" class="basic_person_fs5"><legend class="basic_person_lg">COT Fee Percent</legend>
                                <div class="form-group form-group-sm">
                                    <label for="invstmntFeeDbtAcc" class="control-label col-md-4">Debit Account:</label>
                                    <div  class="col-md-8">
                                        <div class="input-group">
                                            <input type="text" class="form-control" aria-label="..." id="invstmntFeeDbtAcc" value="<?php echo ''; ?>">
                                            <input type="hidden" id="gnrlOrgID" value="<?php echo $orgID; ?>">
                                            <input type="hidden" id="invstmntFeeDbtAccID" value="<?php echo ''; ?>">
                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Accounts', 'gnrlOrgID', '', '', 'radio', true, '<?php echo ""; ?>', 'invstmntFeeDbtAccID', 'invstmntFeeDbtAcc', 'clear', 1, ' AND (SELECT is_prnt_accnt FROM accb.accb_chart_of_accnts x WHERE x.accnt_id = tbl1.a::bigint) = \'0\' ');">
                                                <span class="glyphicon glyphicon-th-list"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group form-group-sm">
                                    <label for="invstmntFeeCrdtAcc" class="control-label col-md-4">Credit Account:</label>
                                    <div  class="col-md-8">
                                        <div class="input-group">
                                            <input type="text" class="form-control" aria-label="..." id="invstmntFeeCrdtAcc" value="<?php echo ''; ?>">
                                            <input type="hidden" id="gnrlOrgID" value="<?php echo $orgID; ?>">
                                            <input type="hidden" id="invstmntFeeCrdtAccID" value="<?php echo ''; ?>">
                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Accounts', 'gnrlOrgID', '', '', 'radio', true, '<?php echo ""; ?>', 'invstmntFeeCrdtAccID', 'invstmntFeeCrdtAcc', 'clear', 1, ' AND (SELECT is_prnt_accnt FROM accb.accb_chart_of_accnts x WHERE x.accnt_id = tbl1.a::bigint) = \'0\' ');">
                                                <span class="glyphicon glyphicon-th-list"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>  
                            </fieldset>    
                        </div>                                
                        <div class="col-lg-4">
                            <fieldset class="basic_person_fs5"><legend class="basic_person_lg">Interest Accrual</legend>
                                <div class="form-group form-group-sm">
                                    <label for="interesPyblDbtAcc" class="control-label col-md-4">Debit Account:</label>
                                    <div  class="col-md-8">
                                        <div class="input-group">
                                            <input type="text" class="form-control" aria-label="..." id="interesPyblDbtAcc" value="<?php echo ''; ?>">
                                            <input type="hidden" id="gnrlOrgID" value="<?php echo $orgID; ?>">
                                            <input type="hidden" id="interesPyblDbtAccID" value="<?php echo ''; ?>">
                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Accounts', 'gnrlOrgID', '', '', 'radio', true, '<?php echo ""; ?>', 'interesPyblDbtAccID', 'interesPyblDbtAcc', 'clear', 1, ' AND (SELECT is_prnt_accnt FROM accb.accb_chart_of_accnts x WHERE x.accnt_id = tbl1.a::bigint) = \'0\' ');">
                                                <span class="glyphicon glyphicon-th-list"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group form-group-sm">
                                    <label for="interesPyblCrdtAcc" class="control-label col-md-4">Credit Account:</label>
                                    <div  class="col-md-8">
                                        <div class="input-group">
                                            <input type="text" class="form-control" aria-label="..." id="interesPyblCrdtAcc" value="<?php echo ''; ?>">
                                            <input type="hidden" id="gnrlOrgID" value="<?php echo $orgID; ?>">
                                            <input type="hidden" id="interesPyblCrdtAccID" value="<?php echo ''; ?>">
                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Accounts', 'gnrlOrgID', '', '', 'radio', true, '<?php echo ""; ?>', 'interesPyblCrdtAccID', 'interesPyblCrdtAcc', 'clear', 1, ' AND (SELECT is_prnt_accnt FROM accb.accb_chart_of_accnts x WHERE x.accnt_id = tbl1.a::bigint) = \'0\' ');">
                                                <span class="glyphicon glyphicon-th-list"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>  
                            </fieldset>                                                  
                        </div>
                        <div class="col-lg-4">
                            <fieldset class="basic_person_fs5"><legend class="basic_person_lg">Interest Expense</legend>
                                <div class="form-group form-group-sm">
                                    <label for="interesExpenseDbtAcc" class="control-label col-md-4">Debit Account:</label>
                                    <div  class="col-md-8">
                                        <div class="input-group">
                                            <input type="text" class="form-control" aria-label="..." id="interesExpenseDbtAcc" value="<?php echo ''; ?>">
                                            <input type="hidden" id="gnrlOrgID" value="<?php echo $orgID; ?>">
                                            <input type="hidden" id="interesExpenseDbtAccID" value="<?php echo ''; ?>">
                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Accounts', 'gnrlOrgID', '', '', 'radio', true, '<?php echo ""; ?>', 'interesExpenseDbtAccID', 'interesExpenseDbtAcc', 'clear', 1, ' AND (SELECT is_prnt_accnt FROM accb.accb_chart_of_accnts x WHERE x.accnt_id = tbl1.a::bigint) = \'0\' ');">
                                                <span class="glyphicon glyphicon-th-list"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group form-group-sm">
                                    <label for="interesExpenseCrdtAcc" class="control-label col-md-4">Credit Account:</label>
                                    <div  class="col-md-8">
                                        <div class="input-group">
                                            <input type="text" class="form-control" aria-label="..." id="interesExpenseCrdtAcc" value="<?php echo ''; ?>">
                                            <input type="hidden" id="gnrlOrgID" value="<?php echo $orgID; ?>">
                                            <input type="hidden" id="interesExpenseCrdtAccID" value="<?php echo ''; ?>">
                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Accounts', 'gnrlOrgID', '', '', 'radio', true, '<?php echo ""; ?>', 'interesExpenseCrdtAccID', 'interesExpenseCrdtAcc', 'clear', 1, ' AND (SELECT is_prnt_accnt FROM accb.accb_chart_of_accnts x WHERE x.accnt_id = tbl1.a::bigint) = \'0\' ');">
                                                <span class="glyphicon glyphicon-th-list"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>  
                            </fieldset>                                                
                        </div>
                    </div>                      
                </form>                
                <?php
            } else {
    
                $mkReadOnly = "";
                $mkReadOnlyDsbld = "";
                $trnsStatus = "Incomplete";     
                $tblNm1 = "mcf.mcf_prdt_savings_stdevnt_accntn";
                $showHideDivInvstmntAccts = "style=\"display:none !important;\""; 
                $showHideDivSvngsAccts = "style=\"display:block !important;\""; 
                if($prdtTypeSEA === "Investment"){
                    $showHideDivInvstmntAccts = "style=\"display:block !important;\""; 
                    $showHideDivSvngsAccts = "style=\"display:none !important;\""; 
                }

                $cnt = getSvngsPrdtDataChngPndngCount($pkID);
                if($cnt > 0){
                    $tblNm1 = "mcf.mcf_prdt_savings_stdevnt_accntn_hstrc";        
                } 
                
                $result = get_SavingsPrdtStdAccEvtnsDet($pkID, $rvsnTtlAPD, $tblNm1);

                //$row2 = loc_db_fetch_array($result)
                while ($row2 = loc_db_fetch_array($result)) {
                    $trnsStatus = getSvngsPrdtStatus($pkID, $rvsnTtlAPD);

                    if($vwtypActn == "VIEW" || $trnsStatus == "Initiated" || $trnsStatus == "Approved" || $trnsStatus == "Unauthorized" || $trnsStatus == "Authorized"){
                        $mkReadOnly = "readonly=\"readonly\"";
                        $mkReadOnlyDsbld = "disabled=\"true\"";
                    }
                    
                    //if($cnt > 0){
                        $tblNmAuthrzd = "mcf.mcf_prdt_savings_stdevnt_accntn";        
                        $resultAuthrzd = get_SavingsPrdtStdAccEvtnsDet($pkID, $rvsnTtlAPD, $tblNmAuthrzd);
                        while ($rowATZ = loc_db_fetch_array($resultAuthrzd)) {
                            $rowATZ0 = $rowATZ[0]; $rowATZ1 = $rowATZ[1]; $rowATZ2 = $rowATZ[2];  $rowATZ3 = $rowATZ[3]; $rowATZ4 = $rowATZ[4];
                            $rowATZ5 = $rowATZ[5]; $rowATZ6 = $rowATZ[6]; $rowATZ7 = $rowATZ[7]; $rowATZ8 = $rowATZ[8]; $rowATZ9 = $rowATZ[9];
                            $rowATZ10 = $rowATZ[10]; $rowATZ11 = $rowATZ[11]; $rowATZ12 = $rowATZ[12]; $rowATZ13 = $rowATZ[13]; $rowATZ14 = $rowATZ[14];
                            $rowATZ15 = $rowATZ[15]; $rowATZ16 = $rowATZ[16]; $rowATZ17 = $rowATZ[17]; $rowATZ18 = $rowATZ[18]; $rowATZ19 = $rowATZ[19];
                            $rowATZ20 = $rowATZ[20]; $rowATZ21 = $rowATZ[21]; $rowATZ22 = $rowATZ[22]; $rowATZ23 = $rowATZ[23]; $rowATZ24 = $rowATZ[24];
                            $rowATZ25 = $rowATZ[25];
                        }               
                    //}                    
                    ?>          
                    <form class="form-horizontal" id='stdEvntsForm' action='' method='post' accept-charset='UTF-8'>
                        <div class="row"><!-- ROW 1 -->
                            <div class="col-lg-4">
                                <fieldset class="basic_person_fs5"><legend class="basic_person_lg">Cash Deposit</legend>
                                    <div class="form-group form-group-sm">
                                        <?php $rwDt1 = getGnrlRecNm("accb.accb_chart_of_accnts", "accnt_id", "accnt_num || '.' || accnt_name", $row2[1]);
                                              $rwATZDt1 = getGnrlRecNm("accb.accb_chart_of_accnts", "accnt_id", "accnt_num || '.' || accnt_name", $rowATZ1);
                                        echo dsplyCntrlLbl($cnt, $rwDt1, $rwATZDt1, "col-md-4", "Debit Account", "cashDepositDbtAcc"); ?>
                                        <div  class="col-md-8">
                                            <div class="input-group">
                                                <!--SAVINGS ACCOUNTING EVENT ID-->
                                                <input class="form-control" id="svngsAccntnEvntId" type = "hidden" placeholder="Savings Accounting Event ID" value="<?php echo $row2[25]; ?>"/>
                                                <input <?php echo $mkReadOnly; ?> type="text" class="form-control" aria-label="..." id="cashDepositDbtAcc" value="<?php echo $rwDt1; ?>">
                                                <input type="hidden" id="gnrlOrgID" value="<?php echo $orgID; ?>">
                                                <input type="hidden" id="cashDepositDbtAccID" value="<?php echo $row2[1]; ?>">
                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Accounts', 'gnrlOrgID', '', '', 'radio', true, '<?php echo ""; ?>', 'cashDepositDbtAccID', 'cashDepositDbtAcc', 'clear', 1, ' AND (SELECT is_prnt_accnt FROM accb.accb_chart_of_accnts x WHERE x.accnt_id = tbl1.a::bigint) = \'0\' ');">
                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm">
                                        <?php $rwDt2 = getGnrlRecNm("accb.accb_chart_of_accnts", "accnt_id", "accnt_num || '.' || accnt_name", $row2[2]);
                                        echo dsplyCntrlLbl($cnt, $rwDt2,
                                                getGnrlRecNm("accb.accb_chart_of_accnts", "accnt_id", "accnt_num || '.' || accnt_name", $rowATZ2), "col-md-4", "Credit Account", "cashDepositCrdtAcc"); ?>
                                        <div  class="col-md-8">
                                            <div class="input-group">
                                                <input <?php echo $mkReadOnly; ?> type="text" class="form-control" aria-label="..." id="cashDepositCrdtAcc" value="<?php echo $rwDt2; ?>">
                                                <input type="hidden" id="gnrlOrgID" value="<?php echo $orgID; ?>">
                                                <input type="hidden" id="cashDepositCrdtAccID" value="<?php echo $row2[2]; ?>">
                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Accounts', 'gnrlOrgID', '', '', 'radio', true, '<?php echo ""; ?>', 'cashDepositCrdtAccID', 'cashDepositCrdtAcc', 'clear', 1, ' AND (SELECT is_prnt_accnt FROM accb.accb_chart_of_accnts x WHERE x.accnt_id = tbl1.a::bigint) = \'0\' ', function(){
                                                    $('#cashWtdwlDbtAcc').val($('#cashDepositCrdtAcc').val());
                                                    $('#cashWtdwlDbtAccID').val($('#cashDepositCrdtAccID').val());
                                                    $('#wtdwlPnltyFlatDbtAcc').val($('#cashDepositCrdtAcc').val());
                                                    $('#wtdwlPnltyFlatDbtAccID').val($('#cashDepositCrdtAccID').val());
                                                    $('#interesExpenseCrdtAcc').val($('#cashDepositCrdtAcc').val());
                                                    $('#interesExpenseCrdtAccID').val($('#cashDepositCrdtAccID').val());
                                                });">
                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>  
                                </fieldset>    
                            </div>                                
                            <div class="col-lg-4">
                                <fieldset class="basic_person_fs5"><legend class="basic_person_lg">Cheque Deposit</legend>
                                    <div class="form-group form-group-sm">
                                        <?php  $rwDt3 = getGnrlRecNm("accb.accb_chart_of_accnts", "accnt_id", "accnt_num || '.' || accnt_name", $row2[3]);
                                        echo dsplyCntrlLbl($cnt, $rwDt3,
                                                getGnrlRecNm("accb.accb_chart_of_accnts", "accnt_id", "accnt_num || '.' || accnt_name", $rowATZ3), "col-md-4", "Debit Account", "chqDepositDbtAcc"); ?>
                                        <div  class="col-md-8">
                                            <div class="input-group">
                                                <input <?php echo $mkReadOnly; ?> type="text" class="form-control" aria-label="..." id="chqDepositDbtAcc" value="<?php echo $rwDt3; ?>">
                                                <input type="hidden" id="gnrlOrgID" value="<?php echo $orgID; ?>">
                                                <input type="hidden" id="chqDepositDbtAccID" value="<?php echo $row2[3]; ?>">
                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Accounts', 'gnrlOrgID', '', '', 'radio', true, '<?php echo ""; ?>', 'chqDepositDbtAccID', 'chqDepositDbtAcc', 'clear', 1, ' AND (SELECT is_prnt_accnt FROM accb.accb_chart_of_accnts x WHERE x.accnt_id = tbl1.a::bigint) = \'0\' ');">
                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm">
                                        <?php $rwDt4 = getGnrlRecNm("accb.accb_chart_of_accnts", "accnt_id", "accnt_num || '.' || accnt_name", $row2[4]);
                                        echo dsplyCntrlLbl($cnt, $rwDt4,
                                                getGnrlRecNm("accb.accb_chart_of_accnts", "accnt_id", "accnt_num || '.' || accnt_name", $rowATZ4), "col-md-4", "Credit Account", "chqDepositCrdtAcc"); ?>
                                        <div  class="col-md-8">
                                            <div class="input-group">
                                                <input <?php echo $mkReadOnly; ?> type="text" class="form-control" aria-label="..." id="chqDepositCrdtAcc" value="<?php echo $rwDt4; ?>">
                                                <input type="hidden" id="gnrlOrgID" value="<?php echo $orgID; ?>">
                                                <input type="hidden" id="chqDepositCrdtAccID" value="<?php echo $row2[4]; ?>">
                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Accounts', 'gnrlOrgID', '', '', 'radio', true, '<?php echo ""; ?>', 'chqDepositCrdtAccID', 'chqDepositCrdtAcc', 'clear', 1, ' AND (SELECT is_prnt_accnt FROM accb.accb_chart_of_accnts x WHERE x.accnt_id = tbl1.a::bigint) = \'0\' ');">
                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>  
                                </fieldset>                                                  
                            </div>
                            <div <?php echo $showHideDivSvngsAccts; ?> class="col-lg-4">
                                <fieldset class="basic_person_fs5"><legend class="basic_person_lg">Cash Withdrawal</legend>
                                    <div class="form-group form-group-sm">
                                        <?php $rwDt5 = getGnrlRecNm("accb.accb_chart_of_accnts", "accnt_id", "accnt_num || '.' || accnt_name", $row2[5]);
                                        echo dsplyCntrlLbl($cnt, $rwDt5,
                                                getGnrlRecNm("accb.accb_chart_of_accnts", "accnt_id", "accnt_num || '.' || accnt_name", $rowATZ5), "col-md-4", "Debit Account", "cashWtdwlDbtAcc"); ?>
                                        <div  class="col-md-8">
                                            <div class="input-group">
                                                <input <?php echo $mkReadOnly; ?> type="text" class="form-control" aria-label="..." id="cashWtdwlDbtAcc" value="<?php echo $rwDt5; ?>">
                                                <input type="hidden" id="gnrlOrgID" value="<?php echo $orgID; ?>">
                                                <input type="hidden" id="cashWtdwlDbtAccID" value="<?php echo $row2[5]; ?>">
                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Accounts', 'gnrlOrgID', '', '', 'radio', true, '<?php echo ""; ?>', 'cashWtdwlDbtAccID', 'cashWtdwlDbtAcc', 'clear', 1, ' AND (SELECT is_prnt_accnt FROM accb.accb_chart_of_accnts x WHERE x.accnt_id = tbl1.a::bigint) = \'0\' ', function(){
                                                    $('#cashDepositCrdtAcc').val($('#cashWtdwlDbtAcc').val());
                                                    $('#cashDepositCrdtAccID').val($('#cashWtdwlDbtAccID').val());
                                                    $('#wtdwlPnltyFlatDbtAcc').val($('#cashWtdwlDbtAcc').val());
                                                    $('#wtdwlPnltyFlatDbtAccID').val($('#cashWtdwlDbtAccID').val());
                                                    $('#interesExpenseCrdtAcc').val($('#cashWtdwlDbtAcc').val());
                                                    $('#interesExpenseCrdtAccID').val($('#cashWtdwlDbtAccID').val());
                                                });">
                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm">
                                        <?php $rwDt6 = getGnrlRecNm("accb.accb_chart_of_accnts", "accnt_id", "accnt_num || '.' || accnt_name", $row2[6]);
                                        echo dsplyCntrlLbl($cnt, $rwDt6,
                                                getGnrlRecNm("accb.accb_chart_of_accnts", "accnt_id", "accnt_num || '.' || accnt_name", $rowATZ6), "col-md-4", "Credit Account", "cashWtdwlCrdtAcc"); ?>
                                        <div  class="col-md-8">
                                            <div class="input-group">
                                                <input <?php echo $mkReadOnly; ?> type="text" class="form-control" aria-label="..." id="cashWtdwlCrdtAcc" value="<?php echo $rwDt6; ?>">
                                                <input type="hidden" id="gnrlOrgID" value="<?php echo $orgID; ?>">
                                                <input type="hidden" id="cashWtdwlCrdtAccID" value="<?php echo $row2[6]; ?>">
                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Accounts', 'gnrlOrgID', '', '', 'radio', true, '<?php echo ""; ?>', 'cashWtdwlCrdtAccID', 'cashWtdwlCrdtAcc', 'clear', 1, ' AND (SELECT is_prnt_accnt FROM accb.accb_chart_of_accnts x WHERE x.accnt_id = tbl1.a::bigint) = \'0\' ');">
                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>  
                                </fieldset>                                                
                            </div>
                        </div>   
                        <div <?php echo $showHideDivSvngsAccts; ?> class="row"><!-- ROW 2 -->
                            <div class="col-lg-4">
                                <fieldset class="basic_person_fs5"><legend class="basic_person_lg">Entry Fees</legend>
                                    <div class="form-group form-group-sm">
                                        <?php $rwDt7 = getGnrlRecNm("accb.accb_chart_of_accnts", "accnt_id", "accnt_num || '.' || accnt_name", $row2[7]);
                                        echo dsplyCntrlLbl($cnt, $rwDt7,
                                                getGnrlRecNm("accb.accb_chart_of_accnts", "accnt_id", "accnt_num || '.' || accnt_name", $rowATZ7), "col-md-4", "Debit Account", "entryFeesDbtAcc"); ?>
                                        <div  class="col-md-8">
                                            <div class="input-group">
                                                <input <?php echo $mkReadOnly; ?>  type="text" class="form-control" aria-label="..." id="entryFeesDbtAcc" value="<?php echo $rwDt7; ?>">
                                                <input type="hidden" id="gnrlOrgID" value="<?php echo $orgID; ?>">
                                                <input type="hidden" id="entryFeesDbtAccID" value="<?php echo $row2[7]; ?>">
                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Accounts', 'gnrlOrgID', '', '', 'radio', true, '<?php echo ""; ?>', 'entryFeesDbtAccID', 'entryFeesDbtAcc', 'clear', 1, ' AND (SELECT is_prnt_accnt FROM accb.accb_chart_of_accnts x WHERE x.accnt_id = tbl1.a::bigint) = \'0\' ');">
                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm">
                                        <?php $rwDt8 = getGnrlRecNm("accb.accb_chart_of_accnts", "accnt_id", "accnt_num || '.' || accnt_name", $row2[8]);
                                        echo dsplyCntrlLbl($cnt, $rwDt8,
                                                getGnrlRecNm("accb.accb_chart_of_accnts", "accnt_id", "accnt_num || '.' || accnt_name", $rowATZ8), "col-md-4", "Credit Account", "entryFeesCrdtAcc"); ?>
                                        <div  class="col-md-8">
                                            <div class="input-group">
                                                <input <?php echo $mkReadOnly; ?> type="text" class="form-control" aria-label="..." id="entryFeesCrdtAcc" value="<?php echo $rwDt8; ?>">
                                                <input type="hidden" id="gnrlOrgID" value="<?php echo $orgID; ?>">
                                                <input type="hidden" id="entryFeesCrdtAccID" value="<?php echo $row2[8]; ?>">
                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Accounts', 'gnrlOrgID', '', '', 'radio', true, '<?php echo ""; ?>', 'entryFeesCrdtAccID', 'entryFeesCrdtAcc', 'clear', 1, ' AND (SELECT is_prnt_accnt FROM accb.accb_chart_of_accnts x WHERE x.accnt_id = tbl1.a::bigint) = \'0\' ');">
                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>  
                                </fieldset>    
                            </div>                                
                            <div class="col-lg-4">
                                <fieldset class="basic_person_fs5"><legend class="basic_person_lg">Close Fees</legend>
                                    <div class="form-group form-group-sm">
                                        <?php $rwDt9 = getGnrlRecNm("accb.accb_chart_of_accnts", "accnt_id", "accnt_num || '.' || accnt_name", $row2[9]);
                                        echo dsplyCntrlLbl($cnt, $rwDt9,
                                                getGnrlRecNm("accb.accb_chart_of_accnts", "accnt_id", "accnt_num || '.' || accnt_name", $rowATZ9), "col-md-4", "Debit Account", "closeFeesDbtAcc"); ?>
                                        <div  class="col-md-8">
                                            <div class="input-group">
                                                <input <?php echo $mkReadOnly; ?> type="text" class="form-control" aria-label="..." id="closeFeesDbtAcc" value="<?php echo $rwDt9; ?>">
                                                <input type="hidden" id="gnrlOrgID" value="<?php echo $orgID; ?>">
                                                <input type="hidden" id="closeFeesDbtAccID" value="<?php echo $row2[9]; ?>">
                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Accounts', 'gnrlOrgID', '', '', 'radio', true, '<?php echo ""; ?>', 'closeFeesDbtAccID', 'closeFeesDbtAcc', 'clear', 1, ' AND (SELECT is_prnt_accnt FROM accb.accb_chart_of_accnts x WHERE x.accnt_id = tbl1.a::bigint) = \'0\' ');">
                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm">
                                        <?php $rwDt10 = getGnrlRecNm("accb.accb_chart_of_accnts", "accnt_id", "accnt_num || '.' || accnt_name", $row2[10]);
                                        echo dsplyCntrlLbl($cnt, $rwDt10,
                                                getGnrlRecNm("accb.accb_chart_of_accnts", "accnt_id", "accnt_num || '.' || accnt_name", $rowATZ10), "col-md-4", "Credit Account", "closeFeesCrdtAcc"); ?>
                                        <div  class="col-md-8">
                                            <div class="input-group">
                                                <input <?php echo $mkReadOnly; ?> type="text" class="form-control" aria-label="..." id="closeFeesCrdtAcc" value="<?php echo $rwDt10; ?>">
                                                <input type="hidden" id="gnrlOrgID" value="<?php echo $orgID; ?>">
                                                <input type="hidden" id="closeFeesCrdtAccID" value="<?php echo $row2[10]; ?>">
                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Accounts', 'gnrlOrgID', '', '', 'radio', true, '<?php echo ""; ?>', 'closeFeesCrdtAccID', 'closeFeesCrdtAcc', 'clear', 1, ' AND (SELECT is_prnt_accnt FROM accb.accb_chart_of_accnts x WHERE x.accnt_id = tbl1.a::bigint) = \'0\' ');">
                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>  
                                </fieldset>                                                  
                            </div>
                            <div class="col-lg-4">
                                <fieldset class="basic_person_fs5"><legend class="basic_person_lg">Re-Open Fees</legend>
                                    <div class="form-group form-group-sm">
                                        <?php $rwDt11 = getGnrlRecNm("accb.accb_chart_of_accnts", "accnt_id", "accnt_num || '.' || accnt_name", $row2[11]);
                                        echo dsplyCntrlLbl($cnt, $rwDt11,
                                                getGnrlRecNm("accb.accb_chart_of_accnts", "accnt_id", "accnt_num || '.' || accnt_name", $rowATZ11), "col-md-4", "Debit Account", "reopenFeesDbtAcc"); ?>
                                        <div  class="col-md-8">
                                            <div class="input-group">
                                                <input <?php echo $mkReadOnly; ?> type="text" class="form-control" aria-label="..." id="reopenFeesDbtAcc" value="<?php echo $rwDt11; ?>">
                                                <input type="hidden" id="gnrlOrgID" value="<?php echo $orgID; ?>">
                                                <input type="hidden" id="reopenFeesDbtAccID" value="<?php echo $row2[11]; ?>">
                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Accounts', 'gnrlOrgID', '', '', 'radio', true, '<?php echo ""; ?>', 'reopenFeesDbtAccID', 'reopenFeesDbtAcc', 'clear', 1, ' AND (SELECT is_prnt_accnt FROM accb.accb_chart_of_accnts x WHERE x.accnt_id = tbl1.a::bigint) = \'0\' ');">
                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm">
                                        <?php $rwDt12 = getGnrlRecNm("accb.accb_chart_of_accnts", "accnt_id", "accnt_num || '.' || accnt_name", $row2[12]);
                                        echo dsplyCntrlLbl($cnt, $rwDt12,
                                                getGnrlRecNm("accb.accb_chart_of_accnts", "accnt_id", "accnt_num || '.' || accnt_name", $rowATZ12), "col-md-4", "Credit Account", "reopenFeesCrdtAcc"); ?>
                                        <div  class="col-md-8">
                                            <div class="input-group">
                                                <input <?php echo $mkReadOnly; ?> type="text" class="form-control" aria-label="..." id="reopenFeesCrdtAcc" value="<?php echo $rwDt12; ?>">
                                                <input type="hidden" id="gnrlOrgID" value="<?php echo $orgID; ?>">
                                                <input type="hidden" id="reopenFeesCrdtAccID" value="<?php echo $row2[12]; ?>">
                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Accounts', 'gnrlOrgID', '', '', 'radio', true, '<?php echo ""; ?>', 'reopenFeesCrdtAccID', 'reopenFeesCrdtAcc', 'clear', 1, ' AND (SELECT is_prnt_accnt FROM accb.accb_chart_of_accnts x WHERE x.accnt_id = tbl1.a::bigint) = \'0\' ');">
                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>  
                                </fieldset>                                                
                            </div>
                        </div>    
                        <div class="row"><!-- ROW 3 -->
                            <div <?php echo $showHideDivSvngsAccts; ?> class="col-lg-4">
                                <fieldset class="basic_person_fs5"><legend class="basic_person_lg">Overdraft Penalty Fee</legend>
                                    <div class="form-group form-group-sm">
                                        <?php $rwDt13 = getGnrlRecNm("accb.accb_chart_of_accnts", "accnt_id", "accnt_num || '.' || accnt_name", $row2[13]);
                                        echo dsplyCntrlLbl($cnt, $rwDt13,
                                                getGnrlRecNm("accb.accb_chart_of_accnts", "accnt_id", "accnt_num || '.' || accnt_name", $rowATZ13), "col-md-4", "Debit Account", "wtdwlPnltyFlatDbtAcc"); ?>
                                        <div  class="col-md-8">
                                            <div class="input-group">
                                                <input <?php echo $mkReadOnly; ?> type="text" class="form-control" aria-label="..." id="wtdwlPnltyFlatDbtAcc" value="<?php echo $rwDt13; ?>">
                                                <input type="hidden" id="gnrlOrgID" value="<?php echo $orgID; ?>">
                                                <input type="hidden" id="wtdwlPnltyFlatDbtAccID" value="<?php echo $row2[13]; ?>">
                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Accounts', 'gnrlOrgID', '', '', 'radio', true, '<?php echo ""; ?>', 'wtdwlPnltyFlatDbtAccID', 'wtdwlPnltyFlatDbtAcc', 'clear', 1, ' AND (SELECT is_prnt_accnt FROM accb.accb_chart_of_accnts x WHERE x.accnt_id = tbl1.a::bigint) = \'0\' ', function(){
                                                    $('#cashDepositCrdtAcc').val($('#wtdwlPnltyFlatDbtAcc').val());
                                                    $('#cashDepositCrdtAccID').val($('#wtdwlPnltyFlatDbtAccID').val());
                                                    $('#cashWtdwlDbtAcc').val($('#wtdwlPnltyFlatDbtAcc').val());
                                                    $('#cashWtdwlDbtAccID').val($('#wtdwlPnltyFlatDbtAccID').val());
                                                    $('#interesExpenseCrdtAcc').val($('#wtdwlPnltyFlatDbtAcc').val());
                                                    $('#interesExpenseCrdtAccID').val($('#wtdwlPnltyFlatDbtAccID').val());
                                                    $('#cotFeeFlatDbtAcc').val($('#wtdwlPnltyFlatDbtAcc').val());
                                                    $('#cotFeeFlatDbtAccID').val($('#wtdwlPnltyFlatDbtAccID').val());
                                                });">
                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm">
                                        <?php $rwDt14 = getGnrlRecNm("accb.accb_chart_of_accnts", "accnt_id", "accnt_num || '.' || accnt_name", $row2[14]);
                                        echo dsplyCntrlLbl($cnt, $rwDt14,
                                                getGnrlRecNm("accb.accb_chart_of_accnts", "accnt_id", "accnt_num || '.' || accnt_name", $rowATZ14), "col-md-4", "Credit Account", "wtdwlPnltyFlatCrdtAcc"); ?>
                                        <div  class="col-md-8">
                                            <div class="input-group">
                                                <input <?php echo $mkReadOnly; ?> type="text" class="form-control" aria-label="..." id="wtdwlPnltyFlatCrdtAcc" value="<?php echo $rwDt14; ?>">
                                                <input type="hidden" id="gnrlOrgID" value="<?php echo $orgID; ?>">
                                                <input type="hidden" id="wtdwlPnltyFlatCrdtAccID" value="<?php echo $row2[14]; ?>">
                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Accounts', 'gnrlOrgID', '', '', 'radio', true, '<?php echo ""; ?>', 'wtdwlPnltyFlatCrdtAccID', 'wtdwlPnltyFlatCrdtAcc', 'clear', 1, ' AND (SELECT is_prnt_accnt FROM accb.accb_chart_of_accnts x WHERE x.accnt_id = tbl1.a::bigint) = \'0\' ');">
                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>  
                                </fieldset>    
                            </div>                               
                            <div <?php echo $showHideDivSvngsAccts; ?> class="col-lg-4">
                                <fieldset class="basic_person_fs5"><legend class="basic_person_lg">Commission on Turnover</legend>
                                    <div class="form-group form-group-sm">
                                        <?php $rwDt17 = getGnrlRecNm("accb.accb_chart_of_accnts", "accnt_id", "accnt_num || '.' || accnt_name", $row2[17]);
                                        echo dsplyCntrlLbl($cnt, $rwDt17,
                                                getGnrlRecNm("accb.accb_chart_of_accnts", "accnt_id", "accnt_num || '.' || accnt_name", $rowATZ17), "col-md-4", "Debit Account", "cotFeeFlatDbtAcc"); ?>
                                        <div  class="col-md-8">
                                            <div class="input-group">
                                                <input <?php echo $mkReadOnly; ?> type="text" class="form-control" aria-label="..." id="cotFeeFlatDbtAcc" value="<?php echo $rwDt17; ?>">
                                                <input type="hidden" id="gnrlOrgID" value="<?php echo $orgID; ?>">
                                                <input type="hidden" id="cotFeeFlatDbtAccID" value="<?php echo $row2[17]; ?>">
                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Accounts', 'gnrlOrgID', '', '', 'radio', true, '<?php echo ""; ?>', 'cotFeeFlatDbtAccID', 'cotFeeFlatDbtAcc', 'clear', 1, ' AND (SELECT is_prnt_accnt FROM accb.accb_chart_of_accnts x WHERE x.accnt_id = tbl1.a::bigint) = \'0\' ', function(){
                                                    $('#cashDepositCrdtAcc').val($('#cotFeeFlatDbtAcc').val());
                                                    $('#cashDepositCrdtAccID').val($('#cotFeeFlatDbtAccID').val());
                                                    $('#cashWtdwlDbtAcc').val($('#cotFeeFlatDbtAcc').val());
                                                    $('#cashWtdwlDbtAccID').val($('#cotFeeFlatDbtAccID').val());
                                                    $('#wtdwlPnltyFlatDbtAcc').val($('#cotFeeFlatDbtAcc').val());
                                                    $('#wtdwlPnltyFlatDbtAccID').val($('#cotFeeFlatDbtAccID').val());
                                                    $('#interesExpenseCrdtAcc').val($('#cotFeeFlatDbtAcc').val());
                                                    $('#interesExpenseCrdtAccID').val($('#cotFeeFlatDbtAccID').val());
                                                });">
                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm">
                                        <?php $rwDt18 = getGnrlRecNm("accb.accb_chart_of_accnts", "accnt_id", "accnt_num || '.' || accnt_name", $row2[18]);
                                        echo dsplyCntrlLbl($cnt, $rwDt18,
                                                getGnrlRecNm("accb.accb_chart_of_accnts", "accnt_id", "accnt_num || '.' || accnt_name", $rowATZ18), "col-md-4", "Credit Account", "cotFeeFlatCrdtAcc"); ?>
                                        <div  class="col-md-8">
                                            <div class="input-group">
                                                <input <?php echo $mkReadOnly; ?> type="text" class="form-control" aria-label="..." id="cotFeeFlatCrdtAcc" value="<?php echo $rwDt18; ?>">
                                                <input type="hidden" id="gnrlOrgID" value="<?php echo $orgID; ?>">
                                                <input type="hidden" id="cotFeeFlatCrdtAccID" value="<?php echo $row2[18]; ?>">
                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Accounts', 'gnrlOrgID', '', '', 'radio', true, '<?php echo ""; ?>', 'cotFeeFlatCrdtAccID', 'cotFeeFlatCrdtAcc', 'clear', 1, ' AND (SELECT is_prnt_accnt FROM accb.accb_chart_of_accnts x WHERE x.accnt_id = tbl1.a::bigint) = \'0\' ');">
                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>  
                                </fieldset>                                                
                            </div>
                            <div class="col-lg-4">
                                <fieldset class="basic_person_fs5"><legend class="basic_person_lg">Interest Accrual</legend>
                                    <div class="form-group form-group-sm">
                                        <?php $rwDt21 = getGnrlRecNm("accb.accb_chart_of_accnts", "accnt_id", "accnt_num || '.' || accnt_name", $row2[21]);
                                        echo dsplyCntrlLbl($cnt, $rwDt21,
                                                getGnrlRecNm("accb.accb_chart_of_accnts", "accnt_id", "accnt_num || '.' || accnt_name", $rowATZ21), "col-md-4", "Debit Account", "interesPyblDbtAcc"); ?>
                                        <div  class="col-md-8">
                                            <div class="input-group">
                                                <input <?php echo $mkReadOnly; ?> type="text" class="form-control" aria-label="..." id="interesPyblDbtAcc" value="<?php echo $rwDt21; ?>">
                                                <input type="hidden" id="gnrlOrgID" value="<?php echo $orgID; ?>">
                                                <input type="hidden" id="interesPyblDbtAccID" value="<?php echo $row2[21]; ?>">
                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Accounts', 'gnrlOrgID', '', '', 'radio', true, '<?php echo ""; ?>', 'interesPyblDbtAccID', 'interesPyblDbtAcc', 'clear', 1, ' AND (SELECT is_prnt_accnt FROM accb.accb_chart_of_accnts x WHERE x.accnt_id = tbl1.a::bigint) = \'0\' ');">
                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm">
                                        <?php $rwDt22 = getGnrlRecNm("accb.accb_chart_of_accnts", "accnt_id", "accnt_num || '.' || accnt_name", $row2[22]);
                                        echo dsplyCntrlLbl($cnt, $rwDt22,
                                                getGnrlRecNm("accb.accb_chart_of_accnts", "accnt_id", "accnt_num || '.' || accnt_name", $rowATZ22), "col-md-4", "Credit Account", "interesPyblCrdtAcc"); ?>
                                        <div  class="col-md-8">
                                            <div class="input-group">
                                                <input <?php echo $mkReadOnly; ?> type="text" class="form-control" aria-label="..." id="interesPyblCrdtAcc" value="<?php echo $rwDt22; ?>">
                                                <input type="hidden" id="gnrlOrgID" value="<?php echo $orgID; ?>">
                                                <input type="hidden" id="interesPyblCrdtAccID" value="<?php echo $row2[22]; ?>">
                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Accounts', 'gnrlOrgID', '', '', 'radio', true, '<?php echo ""; ?>', 'interesPyblCrdtAccID', 'interesPyblCrdtAcc', 'clear', 1, ' AND (SELECT is_prnt_accnt FROM accb.accb_chart_of_accnts x WHERE x.accnt_id = tbl1.a::bigint) = \'0\' ', function(){
                                                    $('#interesExpenseDbtAcc').val($('#interesPyblCrdtAcc').val());
                                                    $('#interesExpenseDbtAccID').val($('#interesPyblCrdtAccID').val());
                                                });">
                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>  
                                </fieldset>                                                  
                            </div>                              
                        </div>   
                        <div class="row"><!-- ROW 4 -->
                            <div class="col-lg-4">
                                <fieldset class="basic_person_fs5"><legend class="basic_person_lg">Interest Payment</legend>
                                    <div class="form-group form-group-sm">
                                        <?php $rwDt23 = getGnrlRecNm("accb.accb_chart_of_accnts", "accnt_id", "accnt_num || '.' || accnt_name", $row2[23]);
                                        echo dsplyCntrlLbl($cnt, $rwDt23,
                                                getGnrlRecNm("accb.accb_chart_of_accnts", "accnt_id", "accnt_num || '.' || accnt_name", $rowATZ23), "col-md-4", "Debit Account", "interesExpenseDbtAcc"); ?>
                                        <div  class="col-md-8">
                                            <div class="input-group">
                                                <input <?php echo $mkReadOnly; ?> type="text" class="form-control" aria-label="..." id="interesExpenseDbtAcc" value="<?php echo $rwDt23; ?>">
                                                <input type="hidden" id="gnrlOrgID" value="<?php echo $orgID; ?>">
                                                <input type="hidden" id="interesExpenseDbtAccID" value="<?php echo $row2[23]; ?>">
                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Accounts', 'gnrlOrgID', '', '', 'radio', true, '<?php echo ""; ?>', 'interesExpenseDbtAccID', 'interesExpenseDbtAcc', 'clear', 1, ' AND (SELECT is_prnt_accnt FROM accb.accb_chart_of_accnts x WHERE x.accnt_id = tbl1.a::bigint) = \'0\' ', function(){
                                                    $('#interesPyblCrdtAcc').val($('#interesExpenseDbtAcc').val());
                                                    $('#interesPyblCrdtAccID').val($('#interesExpenseDbtAccID').val());
                                                });">
                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm">
                                        <?php $rwDt24 = getGnrlRecNm("accb.accb_chart_of_accnts", "accnt_id", "accnt_num || '.' || accnt_name", $row2[24]);
                                        echo dsplyCntrlLbl($cnt, $rwDt24,
                                                getGnrlRecNm("accb.accb_chart_of_accnts", "accnt_id", "accnt_num || '.' || accnt_name", $rowATZ24), "col-md-4", "Credit Account", "interesExpenseCrdtAcc"); ?>
                                        <div  class="col-md-8">
                                            <div class="input-group">
                                                <input <?php echo $mkReadOnly; ?> type="text" class="form-control" aria-label="..." id="interesExpenseCrdtAcc" value="<?php echo $rwDt24; ?>">
                                                <input type="hidden" id="gnrlOrgID" value="<?php echo $orgID; ?>">
                                                <input type="hidden" id="interesExpenseCrdtAccID" value="<?php echo $row2[24]; ?>">
                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Accounts', 'gnrlOrgID', '', '', 'radio', true, '<?php echo ""; ?>', 'interesExpenseCrdtAccID', 'interesExpenseCrdtAcc', 'clear', 1, ' AND (SELECT is_prnt_accnt FROM accb.accb_chart_of_accnts x WHERE x.accnt_id = tbl1.a::bigint) = \'0\' ', function(){
                                                    $('#cashDepositCrdtAcc').val($('#interesExpenseCrdtAcc').val());
                                                    $('#cashDepositCrdtAccID').val($('#interesExpenseCrdtAccID').val());
                                                    $('#cashWtdwlDbtAcc').val($('#interesExpenseCrdtAcc').val());
                                                    $('#cashWtdwlDbtAccID').val($('#interesExpenseCrdtAccID').val());
                                                    $('#wtdwlPnltyFlatDbtAcc').val($('#interesExpenseCrdtAcc').val());
                                                    $('#wtdwlPnltyFlatDbtAccID').val($('#interesExpenseCrdtAccID').val());
                                                    $('#cotFeeFlatDbtAcc').val($('#interesExpenseCrdtAcc').val());
                                                    $('#cotFeeFlatDbtAccID').val($('#interesExpenseCrdtAccID').val());
                                                });">
                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>  
                                </fieldset>                                                
                            </div>                            
                            <div <?php echo $showHideDivInvstmntAccts; ?> class="col-lg-4">
                                <fieldset class="basic_person_fs5"><legend class="basic_person_lg">Investment</legend>
                                    <div class="form-group form-group-sm">
                                        <?php $rwDt19 = getGnrlRecNm("accb.accb_chart_of_accnts", "accnt_id", "accnt_num || '.' || accnt_name", $row2[19]);
                                        echo dsplyCntrlLbl($cnt, $rwDt19,
                                                getGnrlRecNm("accb.accb_chart_of_accnts", "accnt_id", "accnt_num || '.' || accnt_name", $rowATZ19), "col-md-4", "Liability Account", "invstmntFeeDbtAcc"); ?>
                                        <div  class="col-md-8">
                                            <div class="input-group">
                                                <input <?php echo $mkReadOnly; ?> type="text" class="form-control" aria-label="..." id="invstmntFeeDbtAcc" value="<?php echo $rwDt19; ?>">
                                                <input type="hidden" id="gnrlOrgID" value="<?php echo $orgID; ?>">
                                                <input type="hidden" id="invstmntFeeDbtAccID" value="<?php echo $row2[19]; ?>">
                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Accounts', 'gnrlOrgID', '', '', 'radio', true, '<?php echo ""; ?>', 'invstmntFeeDbtAccID', 'invstmntFeeDbtAcc', 'clear', 1, ' AND (SELECT is_prnt_accnt FROM accb.accb_chart_of_accnts x WHERE x.accnt_id = tbl1.a::bigint) = \'0\' ');">
                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm">
                                        <?php $rwDt20 = getGnrlRecNm("accb.accb_chart_of_accnts", "accnt_id", "accnt_num || '.' || accnt_name", $row2[20]);
                                        echo dsplyCntrlLbl($cnt, $rwDt20,
                                                getGnrlRecNm("accb.accb_chart_of_accnts", "accnt_id", "accnt_num || '.' || accnt_name", $rowATZ20), "col-md-4", "Fee Revenue Account", "invstmntFeeCrdtAcc"); ?>
                                        <div  class="col-md-8">
                                            <div class="input-group">
                                                <input <?php echo $mkReadOnly; ?> type="text" class="form-control" aria-label="..." id="invstmntFeeCrdtAcc" value="<?php echo $rwDt20; ?>">
                                                <input type="hidden" id="gnrlOrgID" value="<?php echo $orgID; ?>">
                                                <input type="hidden" id="invstmntFeeCrdtAccID" value="<?php echo $row2[20]; ?>">
                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Accounts', 'gnrlOrgID', '', '', 'radio', true, '<?php echo ""; ?>', 'invstmntFeeCrdtAccID', 'invstmntFeeCrdtAcc', 'clear', 1, ' AND (SELECT is_prnt_accnt FROM accb.accb_chart_of_accnts x WHERE x.accnt_id = tbl1.a::bigint) = \'0\' ');">
                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>  
                                </fieldset>    
                            </div>
                            <div class="col-lg-4">
                                <span>&nbsp;</span>
                                <fieldset style="display:none !important;" class="basic_person_fs5"><legend class="basic_person_lg">Withdrawal Penalty Fee (%)</legend>
                                    <div class="form-group form-group-sm">
                                        <?php $rwDt15 = getGnrlRecNm("accb.accb_chart_of_accnts", "accnt_id", "accnt_num || '.' || accnt_name", $row2[15]);
                                        echo dsplyCntrlLbl($cnt, $rwDt15,
                                                getGnrlRecNm("accb.accb_chart_of_accnts", "accnt_id", "accnt_num || '.' || accnt_name", $rowATZ15), "col-md-4", "Debit Account", "wtdwlPnltyPrcntDbtAcc"); ?>
                                        <div  class="col-md-8">
                                            <div class="input-group">
                                                <input <?php echo $mkReadOnly; ?> type="text" class="form-control" aria-label="..." id="wtdwlPnltyPrcntDbtAcc" value="<?php echo $rwDt15; ?>">
                                                <input type="hidden" id="gnrlOrgID" value="<?php echo $orgID; ?>">
                                                <input type="hidden" id="wtdwlPnltyPrcntDbtAccID" value="<?php echo $row2[15]; ?>">
                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Accounts', 'gnrlOrgID', '', '', 'radio', true, '<?php echo ""; ?>', 'wtdwlPnltyPrcntDbtAccID', 'wtdwlPnltyPrcntDbtAcc', 'clear', 1, ' AND (SELECT is_prnt_accnt FROM accb.accb_chart_of_accnts x WHERE x.accnt_id = tbl1.a::bigint) = \'0\' ');">
                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm">
                                        <?php $rwDt16 = getGnrlRecNm("accb.accb_chart_of_accnts", "accnt_id", "accnt_num || '.' || accnt_name", $row2[16]);
                                        echo dsplyCntrlLbl($cnt, $rwDt16,
                                                getGnrlRecNm("accb.accb_chart_of_accnts", "accnt_id", "accnt_num || '.' || accnt_name", $rowATZ16), "col-md-4", "Credit Account", "wtdwlPnltyPrcntCrdtAcc"); ?>
                                        <div  class="col-md-8">
                                            <div class="input-group">
                                                <input <?php echo $mkReadOnly; ?> type="text" class="form-control" aria-label="..." id="wtdwlPnltyPrcntCrdtAcc" value="<?php echo $rwDt16; ?>">
                                                <input type="hidden" id="gnrlOrgID" value="<?php echo $orgID; ?>">
                                                <input type="hidden" id="wtdwlPnltyPrcntCrdtAccID" value="<?php echo $row2[16]; ?>">
                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Accounts', 'gnrlOrgID', '', '', 'radio', true, '<?php echo ""; ?>', 'wtdwlPnltyPrcntCrdtAccID', 'wtdwlPnltyPrcntCrdtAcc', 'clear', 1, ' AND (SELECT is_prnt_accnt FROM accb.accb_chart_of_accnts x WHERE x.accnt_id = tbl1.a::bigint) = \'0\' ');">
                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>  
                                </fieldset>                                                  
                            </div>                            
                        </div>                      
                    </form>   

                    <?php
                }
            } 
        } else if ($vwtyp == "5") {
            /* ADD COMMISSION */
//$rowID = isset($_POST['rowID']) ? cleanInputData($_POST['rowID']) : -1;
            ?>
            <form class="form-horizontal" id="svngsWtdwlCmsnForm" style="padding:5px 20px 5px 20px;">
                <div class="row">
                    <div class="form-group form-group-sm">
                        <label for="lowRange" class="control-label col-md-4">Low Range:</label>
                        <div class="col-md-8">
                            <input class="form-control" id="lowRange" type = "number" min="0" placeholder="Low Range" value=""/>
                            <!--row ID-->
                            <input class="form-control" size="16" type="hidden" id="rowID" value="<?php echo $rowID; ?>" readonly="">
                            <!--table rowElementID-->
                            <input class="form-control" size="16" type="hidden" id="tblRowElementID" value="" readonly="">
                        </div>
                    </div> 
                    <div class="form-group form-group-sm">
                        <label for="highRange" class="control-label col-md-4">High Range:</label>
                        <div class="col-md-8">
                            <input class="form-control" id="highRange" type = "number" min="0" placeholder="High Range" value=""/>
                        </div>
                    </div>
                    <div class="form-group form-group-sm">
                        <label for="amountFlat" class="control-label col-md-4">Amount Flat:</label>
                        <div class="col-md-8">
                            <input class="form-control" id="amountFlat" type = "number" min="0" placeholder="Amount Flat" value=""/>
                        </div>
                    </div>
                    <div class="form-group form-group-sm">
                        <label for="amountPrcnt" class="control-label col-md-4">Amount Percent:</label>
                        <div class="col-md-8">
                            <input class="form-control" id="amountPrcnt" type = "number" min="0" placeholder="Amount Percent" value=""/>
                        </div>
                    </div>
                    <div class="form-group form-group-sm">
                        <label for="remarks" class="control-label col-md-4">Remarks:</label>
                        <div class="col-md-8">
                            <textarea class="form-control" id="remarks" cols="2" placeholder="Remarks" rows="2"></textarea>
                        </div>
                    </div>
                </div>
                <div class="row" style="float:right;padding-right: 1px;">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="saveSvngsWtdwlCmsnForm('myLovModal', '<?php echo $rowID; ?>');">Save Changes</button>
                </div>
            </form>
            <?php
        }
    } 
    else if ($subPgNo == 7.2) {//CREDIT PRODUCT
        if ($vwtyp == "0") {
            if ($vwtypActn == "EDIT" || $vwtypActn == "VIEW" || $vwtypActn == "ADD") {
                $canAddRiskProfile = test_prmssns($dfltPrvldgs[1], $mdlNm);
                $canEdtRiskProfile = test_prmssns($dfltPrvldgs[1], $mdlNm);
                $canDelRiskProfile = test_prmssns($dfltPrvldgs[1], $mdlNm);
                /* Read Only */
                $trnsStatus = "Incomplete";
                $sbmtdTrnsHdrID = $pkID;
                $voidedTrnsHdrID = -1;
                $rqstatusColor = "red";
                $mkReadOnly = "";
                $mkReadOnlyDsbld = "";
                $makeHidden = "";
                $makeShown = "style=\"display:none !important;\"";  

                $rvsn_ttl = 0;
                $loan_product_id= -1; 
                $product_name= ""; 
                $product_desc= ""; 
                $currency_id_old= "";
                $loan_max_amount= ""; 
                $loan_min_amount= ""; 
                $loan_repayment_type= ""; 
                $grace_period_no= ""; 
                $grace_period_type= ""; 
                $max_loan_tenor= ""; 
                $max_loan_tenor_type= ""; 
                $guarantor_required= ""; 
                $guarantor_no= ""; 
                $sector_clsfctn_major_id= -1; 
                $sector_clsfctn_major= ""; 
                $sector_clsfctn_minor_id= -1; 
                $sector_clsfctn_minor= ""; 
                $product_code= ""; 
                $product_type= ""; 
                $posting_frequency= ""; 
                $charge_interest= ""; 
                $interest_rate= ""; 
                $cust_type_custgrp= ""; 
                $cust_type_corp= ""; 
                $cust_type_ind= ""; 
                $start_date_active= ""; 
                $end_date_active= ""; 
                $compounding_period= ""; 
                $principal_rcvbl_acct_id= -1; 
                $interest_rcvbl_acct_id= -1; 
                $deferred_interest_acct_id= -1; 
                $loans_payable_acct_id= -1; 
                $is_account_rqrd= ""; 
                $cash_collateral_rqrd= ""; 
                $value_flat_min_cash_collateral= ""; 
                $value_flat_prcnt_cash_collateral= ""; 
                $prpty_collateral_rqrd= ""; 
                $value_flat_min_prpty_collateral= ""; 
                $value_flat_prcnt_prpty_collateral= ""; 
                $invstmnt_collateral_rqrd= ""; 
                $value_flat_min_invstmnt_collateral= ""; 
                $value_flat_prcnt_invstmnt_collateral= ""; 
                $min_loan_tenor= ""; 
                $min_loan_tenor_type= ""; 
                $rcvry_officer_rqrd= ""; 
                $currency_id= -1; 
                $interest_rate_type= ""; 
                $interest_revenue_acct_id= -1; 
                $ttl_allwd_principal_rcvbl_amnt_flat= 0.00; 
                $scoring_set_hdr_id= -1; 
                $isoCode= "GHS"; 
                $scoringSet = "";
                $bad_debt_acct_id = -1;
                $loan_provision_crdt_acct_id = -1;
                $loan_provision_dbt_acct_id = -1;
                $grpType = "Everyone"; 
                $groupID = "-1";
                $isStaffLoanProduct="No";
                
                $loan_product_idATZ= -1; 
                $product_nameATZ= ""; 
                $product_descATZ= ""; 
                $currency_id_oldATZ= "";
                $loan_max_amountATZ= ""; 
                $loan_min_amountATZ= ""; 
                $loan_repayment_typeATZ= ""; 
                $grace_period_noATZ= ""; 
                $grace_period_typeATZ= ""; 
                $max_loan_tenorATZ= ""; 
                $max_loan_tenor_typeATZ= ""; 
                $guarantor_requiredATZ= ""; 
                $guarantor_noATZ= ""; 
                $sector_clsfctn_major_idATZ= -1; 
                $sector_clsfctn_majorATZ= ""; 
                $sector_clsfctn_minor_idATZ= -1; 
                $sector_clsfctn_minorATZ= ""; 
                $product_codeATZ= ""; 
                $product_typeATZ= ""; 
                $posting_frequencyATZ= ""; 
                $charge_interestATZ= ""; 
                $interest_rateATZ= ""; 
                $cust_type_custgrpATZ= ""; 
                $cust_type_corpATZ= ""; 
                $cust_type_indATZ= ""; 
                $start_date_activeATZ= ""; 
                $end_date_activeATZ= ""; 
                $compounding_periodATZ= ""; 
                $principal_rcvbl_acct_idATZ= -1; 
                $interest_rcvbl_acct_idATZ= -1; 
                $deferred_interest_acct_idATZ= -1; 
                $loans_payable_acct_idATZ= -1; 
                $is_account_rqrdATZ= ""; 
                $cash_collateral_rqrdATZ= ""; 
                $value_flat_min_cash_collateralATZ= ""; 
                $value_flat_prcnt_cash_collateralATZ= ""; 
                $prpty_collateral_rqrdATZ= ""; 
                $value_flat_min_prpty_collateralATZ= ""; 
                $value_flat_prcnt_prpty_collateralATZ= ""; 
                $invstmnt_collateral_rqrdATZ= ""; 
                $value_flat_min_invstmnt_collateralATZ= ""; 
                $value_flat_prcnt_invstmnt_collateralATZ= ""; 
                $min_loan_tenorATZ= ""; 
                $min_loan_tenor_typeATZ= ""; 
                $rcvry_officer_rqrdATZ= ""; 
                $currency_idATZ= -1; 
                $interest_rate_typeATZ= ""; 
                $interest_revenue_acct_idATZ= -1; 
                $ttl_allwd_principal_rcvbl_amnt_flatATZ= 0.00; 
                $scoring_set_hdr_idATZ= -1; 
                $isoCodeATZ= "GHS"; 
                $scoringSetATZ = "";    
                $bad_debt_acct_idATZ = -1;
                $loan_provision_crdt_acct_idATZ = -1;
                $loan_provision_dbt_acct_idATZ = -1;
                $grpTypeATZ = ""; 
                $groupIDATZ = "";
                $isStaffLoanProductATZ="";
                
                
                $tblNm1 = "mcf.mcf_prdt_loans";

                $cnt = getCrdtPrdtDataChngPndngCount($pkID);
                if($cnt > 0){
                    $tblNm1 = "mcf.mcf_prdt_loans_hstrc";        
                }                  
                
                $shwHydNtlntySts = "style=\"display:none !important;\"";
                
                $result = get_CreditPrdtDet($pkID, $tblNm1);
                while ($row = loc_db_fetch_array($result)) {  
                    $trnsStatus = $row[48];
                    $rvsn_ttl = $row[51];
                             
                    if($vwtypActn == "VIEW" || ($vwtypActn == "EDIT" && ($trnsStatus == "Initiated" || $trnsStatus == "Approved" || $trnsStatus == "Unauthorized" || $trnsStatus == "Authorized"))){
                        $mkReadOnly = "readonly=\"readonly\"";
                        $mkReadOnlyDsbld = "disabled=\"true\"";
                    }       
                    
                    if($trnsStatus == "Unauthorized" || $trnsStatus == "Initiated"){
                        $shwHydNtlntySts = "style=\"display:block !important;\"";
                    }                       
                    
                    $loan_product_id= $row[0]; 
                    $product_name= $row[1]; 
                    $product_desc= $row[2]; 
                    $product_code= $row[3];
                    $loan_max_amount= $row[4]; 
                    $loan_min_amount= $row[5]; 
                    $loan_repayment_type= $row[6]; 
                    $grace_period_no= $row[7]; 
                    $grace_period_type= $row[8]; 
                    $max_loan_tenor= $row[9]; 
                    $max_loan_tenor_type= $row[10]; 
                    $guarantor_required= $row[11]; 
                    $guarantor_no= $row[12]; 
                    $sector_clsfctn_major_id= $row[13]; 
                    $sector_clsfctn_major= getGnrlRecNm("mcf.mcf_loan_sectors_major", "major_sector_id", "sector_name", $row[13]);                     
                    $sector_clsfctn_minor_id= $row[14]; 
                    $sector_clsfctn_minor= getGnrlRecNm("mcf.mcf_loan_sectors_minor", "minor_sector_id", "sector_name", $row[14]);                   
                    $currency_id_old= $row[15]; 
                    $product_type= $row[16]; 
                    $posting_frequency= $row[17]; 
                    $charge_interest= $row[18]; 
                    $interest_rate= $row[19]; 
                    $cust_type_custgrp= $row[20]; 
                    $cust_type_corp= $row[21]; 
                    $cust_type_ind= $row[22]; 
                    $start_date_active= $row[23]; 
                    $end_date_active= $row[24]; 
                    $compounding_period= $row[25]; 
                    $principal_rcvbl_acct_id= $row[26]; 
                    $interest_rcvbl_acct_id= $row[27]; 
                    $deferred_interest_acct_id= $row[28]; 
                    $loans_payable_acct_id= $row[29]; 
                    $is_account_rqrd= $row[30]; 
                    $cash_collateral_rqrd= $row[31]; 
                    $value_flat_min_cash_collateral= $row[32]; 
                    $value_flat_prcnt_cash_collateral= $row[33]; 
                    $prpty_collateral_rqrd= $row[34]; 
                    $value_flat_min_prpty_collateral= $row[35]; 
                    $value_flat_prcnt_prpty_collateral= $row[36]; 
                    $invstmnt_collateral_rqrd= $row[37]; 
                    $value_flat_min_invstmnt_collateral= $row[38]; 
                    $value_flat_prcnt_invstmnt_collateral= $row[39]; 
                    $min_loan_tenor= $row[40]; 
                    $min_loan_tenor_type= $row[41]; 
                    $rcvry_officer_rqrd= $row[42]; 
                    $currency_id= $row[43]; 
                    $interest_rate_type= $row[44]; 
                    $interest_revenue_acct_id= $row[45]; 
                    $ttl_allwd_principal_rcvbl_amnt_flat= $row[46]; 
                    $scoring_set_hdr_id= $row[47]; 
                    $isoCode= $row[50];
                    $scoringSet = getGnrlRecNm("mcf.mcf_credit_scoring_set_hdr", "scoring_set_hdr_id", "set_name", $scoring_set_hdr_id);
                    $bad_debt_acct_id = $row[52]; 
                    $loan_provision_dbt_acct_id = $row[53];
                    $loan_provision_crdt_acct_id =$row[54];
                    $isStaffLoanProduct=$row[55];
                    $grpType = $row[56];
                    $groupID = $row[57];
                    
                }
                
                if($cnt > 0){
                    $tblNmAuthrzd = "mcf.mcf_prdt_loans";        
                    $resultAuthrzd = get_CreditPrdtDet($pkID, $tblNmAuthrzd);
                    while ($rowATZ = loc_db_fetch_array($resultAuthrzd)) {
                        $loan_product_idATZ= $rowATZ[0]; $product_nameATZ= $rowATZ[1];  $product_descATZ= $rowATZ[2];  $product_codeATZ= $rowATZ[3];
                        $loan_max_amountATZ= $rowATZ[4];  $loan_min_amountATZ= $rowATZ[5];  $loan_repayment_typeATZ= $rowATZ[6]; 
                        $grace_period_noATZ= $rowATZ[7];  $grace_period_typeATZ= $rowATZ[8];  $max_loan_tenorATZ= $rowATZ[9]; 
                        $max_loan_tenor_typeATZ= $rowATZ[10];  $guarantor_requiredATZ= $rowATZ[11];  $guarantor_noATZ= $rowATZ[12]; 
                        $sector_clsfctn_major_idATZ= $rowATZ[13]; 
                        $sector_clsfctn_majorATZ= getGnrlRecNm("mcf.mcf_loan_sectors_major", "major_sector_id", "sector_name", $rowATZ[13]);                     
                        $sector_clsfctn_minor_idATZ= $rowATZ[14]; 
                        $sector_clsfctn_minorATZ= getGnrlRecNm("mcf.mcf_loan_sectors_minor", "minor_sector_id", "sector_name", $rowATZ[14]);                   
                        $currency_id_oldATZ= $rowATZ[15];  $product_typeATZ= $rowATZ[16];  $posting_frequencyATZ= $rowATZ[17]; 
                        $charge_interestATZ= $rowATZ[18];  $interest_rateATZ= $rowATZ[19];  $cust_type_custgrpATZ= $rowATZ[20]; 
                        $cust_type_corpATZ= $rowATZ[21];  $cust_type_indATZ= $rowATZ[22];  $start_date_activeATZ= $rowATZ[23]; 
                        $end_date_activeATZ= $rowATZ[24];  $compounding_periodATZ= $rowATZ[25];  $principal_rcvbl_acct_idATZ= $rowATZ[26]; 
                        $interest_rcvbl_acct_idATZ= $rowATZ[27];  $deferred_interest_acct_idATZ= $rowATZ[28];  $loans_payable_acct_idATZ= $rowATZ[29]; 
                        $is_account_rqrdATZ= $rowATZ[30];  $cash_collateral_rqrdATZ= $rowATZ[31];  $value_flat_min_cash_collateralATZ= $rowATZ[32]; 
                        $value_flat_prcnt_cash_collateralATZ= $rowATZ[33];  $prpty_collateral_rqrdATZ= $rowATZ[34];  $value_flat_min_prpty_collateralATZ= $rowATZ[35]; 
                        $value_flat_prcnt_prpty_collateralATZ= $rowATZ[36];  $invstmnt_collateral_rqrdATZ= $rowATZ[37];  $value_flat_min_invstmnt_collateralATZ= $rowATZ[38]; 
                        $value_flat_prcnt_invstmnt_collateralATZ= $rowATZ[39];  $min_loan_tenorATZ= $rowATZ[40];  $min_loan_tenor_typeATZ= $rowATZ[41]; 
                        $rcvry_officer_rqrdATZ= $rowATZ[42];  $currency_idATZ= $rowATZ[43];  $interest_rate_typeATZ= $rowATZ[44]; 
                        $interest_revenue_acct_idATZ= $rowATZ[45];  $ttl_allwd_principal_rcvbl_amnt_flatATZ= $rowATZ[46];  $scoring_set_hdr_idATZ= $rowATZ[47]; 
                        $isoCodeATZ= $rowATZ[50];
                        $scoringSetATZ= getGnrlRecNm("mcf.mcf_credit_scoring_set_hdr", "scoring_set_hdr_id", "set_name", $scoring_set_hdr_idATZ);  
                        $bad_debt_acct_idATZ = $rowATZ[52]; 
                        $loan_provision_dbt_acct_idATZ = $rowATZ[53];
                        $loan_provision_crdt_acct_idATZ =$rowATZ[54];
                        $isStaffLoanProductATZ=$row[55];
                        $grpTypeATZ = $row[56];
                        $groupIDATZ = $row[57];
                        
                        //$prdtCrncyATZ = getGnrlRecNm("mcf.mcf_currencies", "crncy_id", "iso_code", $rowATZ[4]); 
                    }               
                }                 
                
                ?>
                <input class="form-control" id="addOrEditForm" type = "hidden" placeholder="addOrEditForm" value="Edit"/>
                <input class="form-control" id="rvsnTtl" type = "hidden" placeholder="Revision Total" value="<?php echo $rvsn_ttl; ?>"/>
                <input class="form-control" id="recCnt" type = "hidden" placeholder="Revision Total" value="0"/>
                <div class="row" style="margin: 0px 0px 10px 0px !important;">
                    <div class="col-md-12" style="padding:0px 0px 0px 0px !important;">
                        <button type="button" class="btn btn-default btn-sm phone-only-btn" onclick="openATab('#allmodules', 'grp=17&typ=1&pg=7&vtyp=0');">Main Parameters</button>
                        <button type="button" class="btn btn-default btn-sm phone-only-btn" onclick="openATab('#prfBPStndEvntsAcctEDT', 'grp=17&typ=1&pg=7&vtyp=1');">Standard Events Accounting</button>
                        <button type="button" class="btn btn-default btn-sm phone-only-btn" onclick="openATab('#prflCstmEvntsAcctEDT', 'grp=17&typ=1&pg=7&vtyp=2');">Custom Events Accounting</button>
                    </div>
                </div>
                <div class="">
                    <div class="row" style="margin: 0px 0px 10px 0px !important;">
                        <div class="col-md-12" style="padding:0px 1px 5px 1px !important;">
                            <div style="float:left;"> 
                                <button type="button" class="btn btn-default btn-sm" style="height:30px;" id="myVmsTrnsStatusBtn">
                                        <span style="font-weight:bold;">Status: </span><span style="color:<?php echo $rqstatusColor; ?>;font-weight: bold;"><?php echo $trnsStatus; ?></span>
                                </button>
                                <?php  if($vwtypActn == "EDIT" && $pkID > 0) { ?>
                                <button type="button" class="btn btn-default btn-sm" style="height:30px;" onclick="getProductsForm('myFormsModalLg', 'myFormsModalBodyLg', 'myFormsModalTitleLg', 'Edit Credit Product', 12, <?php echo $subPgNo; ?>, 0, 'EDIT', <?php echo $pkID; ?> , '', 'indCustTableRow1');" data-toggle="tooltip" title="Reload Transaction">
                                        <img src="cmn_images/refresh.bmp" style="padding-right: 2px; height:17px; width:auto; position: relative; vertical-align: middle;">
                                </button>
                                <?php } ?>
                                <?php  if($vwtypActn != "ADD") { ?>
                                <button type="button" class="btn btn-default" style="height:30px;" onclick="getOneMcfDocsForm_Gnrl(<?php echo $pkID; ?>,'<?php echo $trnsType; ?>', 140, 'CREDIT PRODUCT');" data-toggle="tooltip" data-placement="bottom" title = "Attached Documents">
                                    <img src="cmn_images/adjunto.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                </button>
                                <?php } ?>
                                <button type="button" class="btn btn-default" style="height:30px;" onclick="getProductsForm('myFormsModalLg', 'myFormsModalBodyLg', 'myFormsModalTitleLg', 'Edit Credit Product', 12, <?php echo $subPgNo; ?>, 0, 'EDIT', -1 , '', 'indCustTableRow1');" data-toggle="tooltip" data-placement="bottom" title = "Attached Documents">
                                    <img src="cmn_images/undo_256.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                </button>
                            </div>
                            <div class="" style="float:right;">
                                <?php if($vwtypActn == "EDIT" || $vwtypActn == "ADD") { if ($trnsStatus == "Authorized") { ?>
                                <button type="button" class="btn btn-default btn-sm" style="" onclick="modifyAutrzCrdtPrdtRqst(<?php echo $pgNo; ?>, <?php echo $subPgNo; ?>, <?php echo $vwtyp; ?>);">
                                    <img src="cmn_images/edit32.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">
                                    MODIFY DATA
                                </button>                               
                                
                                <?php } else if (($trnsStatus == "Incomplete" || $trnsStatus == "Withdrawn" || $trnsStatus == "Rejected") && $vwtypActn != "VIEW") { ?> 
                                <button type="button" class="btn btn-default btn-sm" style="" onclick="saveCreditProduct(0);">
                                    <img src="cmn_images/FloppyDisk.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">
                                    SAVE
                                </button>
                                <button type="button" class="btn btn-default btn-sm" style="" onclick="saveCreditProduct(1);">
                                    <img src="cmn_images/Emailcon.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">
                                    SUBMIT
                                </button>
                                 <?php } else if ($trnsStatus == "Unauthorized") { ?>    
                                    <?php if (didAuthorizerSubmitCrdtPrdt($pkID, $usrID)) { ?>
                                    <button type="button" class="btn btn-default btn-sm" style="height:30px;" onclick="wthdrwRjctAutrzCrdtPrdtDataRqst('WITHDRAW', <?php echo $pgNo; ?>, <?php echo $subPgNo; ?>, <?php echo $vwtyp; ?>);"><img src="cmn_images/withdraw_rqst.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Withdraw&nbsp;</button>                                                                          
                                    <?php } else {  ?>
                                    <button type="button" class="btn btn-default btn-sm" style="height:30px;" onclick="wthdrwRjctAutrzCrdtPrdtDataRqst('REJECT', <?php echo $pgNo; ?>, <?php echo $subPgNo; ?>, <?php echo $vwtyp; ?>);"><img src="cmn_images/withdraw_rqst.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Reject&nbsp;</button>                                         
                                    <button type="button" class="btn btn-default btn-sm" style="height:30px;" onclick="wthdrwRjctAutrzCrdtPrdtDataRqst('AUTHORIZE', <?php echo $pgNo; ?>, <?php echo $subPgNo; ?>, <?php echo $vwtyp; ?>);"><img src="cmn_images/Stamp-512.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Authorize&nbsp;</button>                                                                                                                                     
                                <?php } } }?> 
                            </div>
                        </div>
                    </div>                        
                    <div class="row">                  
                        <div class="col-md-12">                                           
                            <form class="form-horizontal">
                                <div class="row"><!-- ROW 1 -->                                
                                    <div class="col-lg-4">
                                        <fieldset class="basic_person_fs1"><legend class="basic_person_lg">Product</legend>
                                            <div class="form-group form-group-sm">
                                                <?php echo dsplyCntrlLbl($cnt, $loan_product_id, $loan_product_idATZ, "col-md-4", "Code", "pdtCode"); ?>
                                                <div class="col-md-8">
                                                    <input <?php echo $mkReadOnly; ?> class="form-control rqrdFld" id="pdtCode" type = "number" <?php echo $prdtCdeLnStr; ?> placeholder="Code" value="<?php echo $product_code; ?>"/>
                                                    <!--PRODUCT ID-->
                                                    <input class="form-control rqrdFld" id="sbmtdPrdtID" type = "hidden" placeholder="Product ID" value="<?php echo $loan_product_id; ?>"/>                                                                                                                                           
                                                </div>
                                            </div> 
                                            <div class="form-group form-group-sm">
                                                <?php echo dsplyCntrlLbl($cnt, $product_name, $product_nameATZ, "col-md-4", "Name", "prdtName"); ?>
                                                <div  class="col-md-8">
                                                    <input <?php echo $mkReadOnly; ?> class="form-control rqrdFld" id="prdtName" type = "text" placeholder="Name" value="<?php echo $product_name; ?>"/>
                                                </div>
                                            </div>     
                                            <div class="form-group form-group-sm">
                                                <?php echo dsplyCntrlLbl($cnt, $product_desc, $product_descATZ, "col-md-4", "Description", "prdtDesc"); ?>
                                                <div  class="col-md-8">
                                                    <textarea <?php echo $mkReadOnly; ?> class="form-control rqrdFld" id="prdtDesc" cols="2" placeholder="Description" rows="3"><?php echo $product_desc; ?></textarea>
                                                </div>
                                            </div>                                                            
                                            <div class="form-group form-group-sm">
                                                <?php echo dsplyCntrlLbl($cnt, $isoCode, $isoCodeATZ, "col-md-4", "Currency", "prdtCrncy"); ?>
                                                <div  class="col-md-8">
                                                    <select <?php echo $mkReadOnlyDsbld; ?> class="form-control" id="prdtCrncy" >
                                                        <!--<option value="">&nbsp;</option>-->
                                                        <?php
                                                        $brghtStr = "";
                                                        $isDynmyc = FALSE;
                                                        $titleRslt = getLovValues("%", "Both", 0, 100, $brghtStr, getLovID("Bank Currencies (Select List)"), $isDynmyc, -1, "", "");
                                                        while ($titleRow = loc_db_fetch_array($titleRslt)) {
                                                            $selectedTxt = "";
                                                            if ($isoCode == $titleRow[0]) {
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
                                                <?php echo dsplyCntrlLbl($cnt, $product_type, $product_typeATZ, "col-md-4", "Type", "prdtType"); ?>
                                                <div  class="col-md-8">
                                                    <select <?php echo $mkReadOnlyDsbld; ?> class="form-control" id="prdtType" >
                                                        <!--<option value="">&nbsp;</option>-->
                                                        <?php
                                                        $brghtStr = "";
                                                        $isDynmyc = FALSE;
                                                        $selectedTxtLoan = "";
                                                        $selectedTxtOvdrft = "";
                                                        if ($product_type == "Loan") {
                                                            $selectedTxtLoan = "selected";
                                                        }else if($product_type == "Overdraft"){
                                                            $selectedTxtOvdrft = "selected";
                                                        }
                                                        ?>
                                                        <option value="Loan" <?php echo $selectedTxtLoan; ?>>Loan</option>
                                                        <option value="Overdraft" <?php echo $selectedTxtOvdrft; ?>>Overdraft</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group form-group-sm">
                                                <?php  echo dsplyCntrlLbl($cnt, $isStaffLoanProduct, $isStaffLoanProductATZ, "col-md-4", "Staff Product?", "isStaffLoanProduct"); ?>
                                                <div  class="col-md-8">
                                                    <select <?php echo $mkReadOnlyDsbld; ?> class="form-control" id="isStaffLoanProduct" onchange="">
                                                        <?php
                                                        $sltdNo = "";
                                                        $sltdYes = "";
                                                        if ($isStaffLoanProduct === "No") {
                                                            $sltdNo = "selected=\"selected\"";
                                                        } else if ($isStaffLoanProduct === "Yes") {
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
                                    <div class="col-lg-4">
                                        <fieldset class="basic_person_fs1"><legend class="basic_person_lg">Product</legend>
                                            <div class="form-group form-group-sm">
                                                <?php echo dsplyCntrlLbl($cnt, $loan_min_amount, $loan_min_amountATZ, "col-md-4", "Min Amount", "minAmnt"); ?>
                                                <div class="col-md-8">
                                                    <input <?php echo $mkReadOnly; ?> class="form-control rqrdFld" id="minAmnt" type = "number" placeholder="Min Amount:" value="<?php echo $loan_min_amount; ?>"/>                                                                                                                                         
                                                </div>
                                            </div> 
                                            <div class="form-group form-group-sm">
                                                <?php echo dsplyCntrlLbl($cnt, $loan_max_amount, $loan_max_amountATZ, "col-md-4", "Max Amount", "maxAmnt"); ?>
                                                <div  class="col-md-8">
                                                    <input <?php echo $mkReadOnly; ?> class="form-control rqrdFld" id="maxAmnt" type = "text" placeholder="Max Amount" value="<?php echo $loan_max_amount; ?>"/>
                                                </div>
                                            </div>                                            
                                            <div class="form-group form-group-sm">
                                                <?php echo dsplyCntrlLbl($cnt, $loan_repayment_type, $loan_repayment_typeATZ, "col-md-4", "Repayment", "rpmntType"); ?>
                                                <div  class="col-md-8">
                                                    <select <?php echo $mkReadOnlyDsbld; ?> class="form-control" id="rpmntType" >
                                                        <!--<option value="">&nbsp;</option>-->
                                                        <?php
                                                        $brghtStr = "";
                                                        $isDynmyc = FALSE;
                                                        $selectedTxtDaily = "";
                                                        $selectedTxtWkly = "";
                                                        $selectedTxtBiWkly = "";
                                                        $selectedTxtHlfMnthly = "";
                                                        $selectedTxtMnthly = "";
                                                        $selectedTxtQtrly = "";
                                                        $selectedTxtHlfYr = "";
                                                        $selectedTxtYrly = "";
                                                        if ($loan_repayment_type == "daily") {
                                                            $selectedTxtDaily = "selected";
                                                        }else if($loan_repayment_type == "weekly"){
                                                            $selectedTxtWkly = "selected";
                                                        } else if ($loan_repayment_type == "biweekly") {
                                                            $selectedTxtBiWkly = "selected";
                                                        }else if($loan_repayment_type == "halfmonth"){
                                                            $selectedTxtHlfMnthly = "selected";
                                                        } else if ($loan_repayment_type == "month") {
                                                            $selectedTxtMnthly = "selected";
                                                        }else if($loan_repayment_type == "quarter"){
                                                            $selectedTxtQtrly = "selected";
                                                        } else if ($loan_repayment_type == "halfyear") {
                                                            $selectedTxtHlfYr = "selected";
                                                        }else if($loan_repayment_type == "year"){
                                                            $selectedTxtYrly = "selected";
                                                        }
                                                        
                                                        if($pkID <= 0){
                                                            $selectedTxtMnthly = "selected";
                                                        }
                                                        ?>
                                                        <option value="daily" <?php echo $selectedTxtDaily; ?>>Every Day</option>
                                                        <option value="weekly" <?php echo $selectedTxtWkly; ?>>Every Week</option>
                                                        <option value="biweekly" <?php echo $selectedTxtBiWkly; ?>>Every 2 Weeks</option>
                                                        <option value="halfmonth" <?php echo $selectedTxtHlfMnthly; ?>>Every Half Month</option>
                                                        <option value="month" <?php echo $selectedTxtMnthly; ?>>Every Month</option>
                                                        <option value="quarter" <?php echo $selectedTxtQtrly; ?>>Every Quarter</option>
                                                        <option value="halfyear" <?php echo $selectedTxtHlfYr; ?>>Every 6 Months</option>
                                                        <option value="year" <?php echo $selectedTxtYrly; ?>>Every Year</option>
                                                    </select>
                                                </div>
                                            </div> 
                                            <div class="form-group form-group-sm">
                                                <?php echo dsplyCntrlLbl($cnt, $grace_period_no. " " .$grace_period_type, $grace_period_noATZ. " " .$grace_period_typeATZ, "col-md-4", "Grace Period", "gracePeriodNo"); ?>
                                                <div  class="col-md-8">
                                                    <div class="input-group col-md-12">
                                                        <div  class="col-md-4" style="padding-left:0px !important;">
                                                            <input <?php echo $mkReadOnly; ?> class="form-control rqrdFld" id="gracePeriodNo" type = "number" min="0" placeholder="" value="<?php echo $grace_period_no; ?>"/>
                                                        </div>
                                                        <div  class="col-md-8" style="padding-left:0px !important; padding-right: 0px !important; ">
                                                            <select <?php echo $mkReadOnlyDsbld; ?> class="form-control" id="gracePeriodType" >
                                                                <?php
                                                                    $brghtStr = "";
                                                                    $isDynmyc = FALSE;
                                                                    $selectedTxtDay = "";
                                                                    $selectedTxtWeek = "";
                                                                    $selectedTxtMnth = "";
                                                                    $selectedTxtYr = "";
                                                                    if ($grace_period_type == "day") {
                                                                        $selectedTxtDay = "selected";
                                                                    }else if($grace_period_type == "week"){
                                                                        $selectedTxtWeek = "selected";
                                                                    } else if ($grace_period_type == "month") {
                                                                        $selectedTxtMnth = "selected";
                                                                    }else if($grace_period_type == "year"){
                                                                        $selectedTxtYr = "selected";
                                                                    } 

                                                                    if($pkID <= 0){
                                                                        $selectedTxtMnth = "selected";
                                                                    }
                                                                    ?>
                                                                <option value="day" <?php echo $selectedTxtDay;?>>Day(s)</option>
                                                                <option value="week" <?php echo $selectedTxtWeek;?>>Week(s)</option>
                                                                <option value="month" <?php echo $selectedTxtMnth;?>>Month(s)</option>
                                                                <option value="year" <?php echo $selectedTxtYr;?>>Year(s)</option>                                                                
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>                                                            
                                            </div>
                                            <div class="form-group form-group-sm">
                                                <?php echo dsplyCntrlLbl($cnt, $min_loan_tenor. " " .$min_loan_tenor_type, $min_loan_tenorATZ. " " .$min_loan_tenor_typeATZ, "col-md-4", "Min. Tenure", "minLoanTenor"); ?>
                                                <div  class="col-md-8">
                                                    <div class="input-group col-md-12">
                                                        <div  class="col-md-4" style="padding-left:0px !important;">
                                                            <input <?php echo $mkReadOnly; ?> class="form-control rqrdFld" id="minLoanTenor" type = "number" min="0" placeholder="" value="<?php echo $min_loan_tenor; ?>"/>
                                                        </div>
                                                        <div  class="col-md-8" style="padding-left:0px !important; padding-right: 0px !important; ">
                                                            <select <?php echo $mkReadOnlyDsbld; ?> class="form-control" id="minLoanTenorType" >
                                                                <?php
                                                                    $brghtStr = "";
                                                                    $isDynmyc = FALSE;
                                                                    $selectedTxtMnths = "";
                                                                    $selectedTxtYrs = "";
                                                                    if ($min_loan_tenor_type == "Month(s)") {
                                                                        $selectedTxtMnths = "selected";
                                                                    } else if($min_loan_tenor_type == "Year(s)"){
                                                                        $selectedTxtYrs = "selected";
                                                                    } 

                                                                    if($pkID <= 0){
                                                                        $selectedTxtMnths = "selected";
                                                                    }
                                                                    ?>
                                                                <option value="Month(s)" <?php echo $selectedTxtMnths;?>>Month(s)</option>
                                                                <option value="Year(s)" <?php echo $selectedTxtYrs;?>>Year(s)</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>                                                            
                                            </div>
                                            <div class="form-group form-group-sm">
                                                <?php echo dsplyCntrlLbl($cnt, $max_loan_tenor. " " .$max_loan_tenor_type, $max_loan_tenorATZ. " " .$max_loan_tenor_typeATZ, "col-md-4", "Max. Tenure", "maxLoanTenor"); ?>
                                                <div  class="col-md-8">
                                                    <div class="input-group col-md-12">
                                                        <div  class="col-md-4" style="padding-left:0px !important;">
                                                            <input <?php echo $mkReadOnly; ?> class="form-control rqrdFld" id="maxLoanTenor" type = "number" min="0" placeholder="" value="<?php echo $max_loan_tenor; ?>"/>
                                                        </div>
                                                        <div  class="col-md-8" style="padding-left:0px !important; padding-right: 0px !important; ">
                                                            <select <?php echo $mkReadOnlyDsbld; ?> class="form-control" id="maxLoanTenorType" >
                                                                <?php
                                                                    $brghtStr = "";
                                                                    $isDynmyc = FALSE;
                                                                    $selectedTxtMnths1 = "";
                                                                    $selectedTxtYrs1 = "";
                                                                    if ($max_loan_tenor_type == "Month(s)") {
                                                                        $selectedTxtMnths1 = "selected";
                                                                    } else if($max_loan_tenor_type == "Year(s)"){
                                                                        $selectedTxtYrs1 = "selected";
                                                                    } 

                                                                    if($pkID <= 0){
                                                                        $selectedTxtMnths1 = "selected";
                                                                    }
                                                                    ?>
                                                                <option value="Month(s)" <?php echo $selectedTxtMnths1;?>>Month(s)</option>
                                                                <option value="Year(s)" <?php echo $selectedTxtYrs1;?>>Year(s)</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>                                                            
                                            </div>
                                        </fieldset>
                                    </div>                                    
                                    <div class="col-lg-4"> 
                                        <fieldset class="basic_person_fs1"><legend class="basic_person_lg">Collaterals</legend>
                                            <div  class="form-group form-group-sm">
                                                <?php echo dsplyCntrlLbl($cnt, $cash_collateral_rqrd, $cash_collateral_rqrdATZ, "col-md-4", "Cash?", "cashCltrlRqrd"); ?>
                                                <div  class="col-md-8">
                                                    <?php
                                                    $chkdYes1 = "checked=\"\"";
                                                    $chkdNo1 = "";
                                                    if ($cash_collateral_rqrd == "No") {
                                                        $chkdNo1 = "checked=\"\"";
                                                        $chkdYes1 = "";
                                                    }
                                                    ?>
                                                    <label class="radio-inline"><input type="radio" name="cashCltrlRqrd" value="Yes" <?php echo $chkdYes1; ?>>YES</label>
                                                    <label class="radio-inline"><input type="radio" name="cashCltrlRqrd" value="No" <?php echo $chkdNo1; ?>>NO</label>
                                                </div>                                                              
                                            </div>
                                            <div class="form-group form-group-sm">
                                                <?php echo dsplyCntrlLbl($cnt, $value_flat_min_cash_collateral.$isoCode." ".$value_flat_prcnt_cash_collateral."%", $value_flat_min_cash_collateralATZ.$isoCodeATZ." ".$value_flat_prcnt_cash_collateralATZ."%", "col-md-4", "Cash Amount", "valueFlatCashCltrl"); ?>
                                                <div  class="col-md-8">
                                                    <div class="input-group">
                                                        <input <?php echo $mkReadOnly; ?> class="form-control" id="valueFlatCashCltrl" type = "number" min="0" placeholder="Flat Amount" value="<?php echo $value_flat_min_cash_collateral; ?>"/>
                                                        <label class="btn btn-primary btn-file input-group-addon" style="padding-left: 2px !important;">
                                                            GHS
                                                        </label>
                                                        <input <?php echo $mkReadOnly; ?> class="form-control" id="valuePrcntCashCltrl" type = "number" min="0" placeholder="Amount Percent" value="<?php echo $value_flat_prcnt_cash_collateral; ?>"/>
                                                        <label class="btn btn-primary btn-file input-group-addon" style="padding-left: 2px !important;">
                                                            %
                                                        </label>
                                                    </div>
                                                </div>                                                            
                                            </div>
                                            <div  class="form-group form-group-sm">
                                                <?php echo dsplyCntrlLbl($cnt, $prpty_collateral_rqrd, $prpty_collateral_rqrdATZ, "col-md-4", "Property?", "prptyCltrlRqrd"); ?>
                                                <div  class="col-md-8">
                                                    <?php
                                                    $chkdYes2 = "checked=\"\"";
                                                    $chkdNo2 = "";
                                                    if ($prpty_collateral_rqrd == "No") {
                                                        $chkdNo2 = "checked=\"\"";
                                                        $chkdYes2 = "";
                                                    }
                                                    ?>
                                                    <label class="radio-inline"><input type="radio" name="prptyCltrlRqrd" value="Yes" <?php echo $chkdYes2; ?>>YES</label>
                                                    <label class="radio-inline"><input type="radio" name="prptyCltrlRqrd" value="No" <?php echo $chkdNo2; ?>>NO</label>
                                                </div>                                                              
                                            </div>
                                            <div class="form-group form-group-sm">
                                                <?php echo dsplyCntrlLbl($cnt, $value_flat_min_prpty_collateral.$isoCode." ".$value_flat_prcnt_prpty_collateral."%", $value_flat_min_prpty_collateralATZ.$isoCodeATZ." ".$value_flat_prcnt_prpty_collateralATZ."%", "col-md-4", "Property Value", "valueFlatPrptyCltrl"); ?>
                                                <div  class="col-md-8">
                                                    <div class="input-group">
                                                        <input <?php echo $mkReadOnly; ?> class="form-control" id="valueFlatPrptyCltrl" type = "number" min="0" placeholder="Flat Amount" value="<?php echo $value_flat_min_prpty_collateral; ?>"/>
                                                        <label class="btn btn-primary btn-file input-group-addon" style="padding-left: 2px !important;">
                                                            GHS
                                                        </label>
                                                        <input <?php echo $mkReadOnly; ?> class="form-control" id="valuePrcntPrptyCltrl" type = "number" min="0" placeholder="Amount Percent" value="<?php echo $value_flat_prcnt_prpty_collateral; ?>"/>
                                                        <label class="btn btn-primary btn-file input-group-addon" style="padding-left: 2px !important;">
                                                            %
                                                        </label>
                                                    </div>
                                                </div>                                                            
                                            </div>
                                            <div  class="form-group form-group-sm">
                                                <?php echo dsplyCntrlLbl($cnt, $invstmnt_collateral_rqrd, $invstmnt_collateral_rqrdATZ, "col-md-4", "Investment?", "invstmntCltrlRqrd"); ?>
                                                <div  class="col-md-8">
                                                    <?php
                                                    $chkdYes = "checked=\"\"";
                                                    $chkdNo = "";
                                                    if ($invstmnt_collateral_rqrd == "No") {
                                                        $chkdNo = "checked=\"\"";
                                                        $chkdYes = "";
                                                    }
                                                    ?>
                                                    <label class="radio-inline"><input type="radio" name="invstmntCltrlRqrd" value="Yes" <?php echo $chkdYes; ?>>YES</label>
                                                    <label class="radio-inline"><input type="radio" name="invstmntCltrlRqrd" value="No" <?php echo $chkdNo; ?>>NO</label>
                                                </div>                                                              
                                            </div>
                                            <div class="form-group form-group-sm">
                                                <?php echo dsplyCntrlLbl($cnt, $value_flat_min_invstmnt_collateral.$isoCode." ".$value_flat_prcnt_invstmnt_collateral."%", $value_flat_min_invstmnt_collateralATZ.$isoCodeATZ." ".$value_flat_prcnt_invstmnt_collateralATZ."%", "col-md-4", "Investmemt Amount", "valueFlatInvstmntCltrl"); ?>
                                                <div  class="col-md-8">
                                                    <div class="input-group">
                                                        <input <?php echo $mkReadOnly; ?> class="form-control" id="valueFlatInvstmntCltrl" type = "number" min="0" placeholder="Flat Amount" value="<?php echo $value_flat_min_invstmnt_collateral; ?>"/>
                                                        <label class="btn btn-primary btn-file input-group-addon" style="padding-left: 2px !important;">
                                                            GHS
                                                        </label>
                                                        <input <?php echo $mkReadOnly; ?> class="form-control" id="valuePrcntInvstmntCltrl" type = "number" min="0" placeholder="Amount Percent" value="<?php echo $value_flat_prcnt_invstmnt_collateral; ?>"/>
                                                        <label class="btn btn-primary btn-file input-group-addon" style="padding-left: 2px !important;">
                                                            %
                                                        </label>
                                                    </div>
                                                </div>                                                            
                                            </div>
                                        </fieldset>   
                                    </div>
                                </div>   
                                <div class="row"><!-- ROW 2 -->                                
                                    <div class="col-lg-4">
                                        <fieldset class="basic_person_fs1"><legend class="basic_person_lg">Interest</legend>
                                            <div  class="form-group form-group-sm">
                                                <?php echo dsplyCntrlLbl($cnt, $charge_interest, $charge_interestATZ, "col-md-4", "Charge Interest?", "chrgInterest"); ?>
                                                <div  class="col-md-8">
                                                    <?php
                                                    $chkdYes = "checked=\"\"";
                                                    $chkdNo = "";
                                                    if ($charge_interest == "NO") {
                                                        $chkdNo = "checked=\"\"";
                                                        $chkdYes = "";
                                                    }
                                                    ?>
                                                    <label class="radio-inline"><input type="radio" name="chrgInterest" value="YES" <?php echo $chkdYes; ?>>YES</label>
                                                    <label class="radio-inline"><input type="radio" name="chrgInterest" value="NO" <?php echo $chkdNo; ?>>NO</label>
                                                </div>                                                              
                                            </div> 
                                            <div class="form-group form-group-sm">
                                                <?php echo dsplyCntrlLbl($cnt, $interest_rate_type, $interest_rate_typeATZ, "col-md-4", "Interest Rate Type", "intRateType"); ?>
                                                <div  class="col-md-8">
                                                    <select <?php echo $mkReadOnlyDsbld; ?> class="form-control" id="intRateType" onchange="">
                                                        <?php 
                                                        $brghtStr = "";
                                                        $isDynmyc = FALSE;
                                                        $selectedTxtRdcnRte = "";
                                                        $selectedTxtFlatRte = "";
                                                        if ($interest_rate_type == "Reducing Rate") {
                                                            $selectedTxtRdcnRte = "selected";
                                                        }else if($interest_rate_type == "Flat Rate"){
                                                            $selectedTxtFlatRte = "selected";
                                                        }
                                                        ?> 
                                                        <option value="Reducing Rate" <?php echo $selectedTxtRdcnRte; ?>>Reducing Rate - Reducing Interest</option>
                                                        <option value="Flat Rate" <?php echo $selectedTxtFlatRte; ?>>Flat Rate - Fixed Principal and Interest</option> 
                                                    </select>
                                                </div>
                                            </div>                                               
                                            <div class="form-group form-group-sm">
                                                <?php echo dsplyCntrlLbl($cnt, $interest_rate, $interest_rateATZ, "col-md-4", "Interest Rate", "interestRate"); ?>
                                                <div  class="col-md-8">
                                                    <div class="input-group">
                                                        <input <?php echo $mkReadOnly; ?> class="form-control rqrdFld" id="interestRate" type = "number" min="0" placeholder="Interest Rate" value="<?php echo $interest_rate; ?>"/>
                                                        <label class="btn btn-primary btn-file input-group-addon" style="padding-left: 2px !important;">
                                                            % Per Annum
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group form-group-sm">
                                                <?php echo dsplyCntrlLbl($cnt, $compounding_period, $compounding_periodATZ, "col-md-4", "Compound", "compound"); ?>
                                                <div  class="col-md-8">
                                                    <select <?php echo $mkReadOnlyDsbld; ?> class="form-control" id="compound" onchange="">
                                                        <?php 
                                                        $brghtStr = "";
                                                        $isDynmyc = FALSE;
                                                        $selectedTxtDaily = "";
                                                        $selectedTxtWkly = "";
                                                        $selectedTxtBiWkly = "";
                                                        $selectedTxtHlfMnthly = "";
                                                        $selectedTxtMnthly = "";
                                                        $selectedTxtQtrly = "";
                                                        $selectedTxtHlfYr = "";
                                                        $selectedTxtYrly = "";
                                                        $selectedTxtSimple = "";
                                                        if ($compounding_period == "daily") {
                                                            $selectedTxtDaily = "selected";
                                                        }else if($compounding_period == "weekly"){
                                                            $selectedTxtWkly = "selected";
                                                        } else if ($compounding_period == "biweekly") {
                                                            $selectedTxtBiWkly = "selected";
                                                        }else if($compounding_period == "halfmonth"){
                                                            $selectedTxtHlfMnthly = "selected";
                                                        } else if ($compounding_period == "month") {
                                                            $selectedTxtMnthly = "selected";
                                                        }else if($compounding_period == "quarter"){
                                                            $selectedTxtQtrly = "selected";
                                                        } else if ($compounding_period == "semiannually") {
                                                            $selectedTxtHlfYr = "selected";
                                                        }else if($compounding_period == "year"){
                                                            $selectedTxtYrly = "selected";
                                                        }else if($compounding_period == "simple"){
                                                            $selectedTxtSimple = "selected";
                                                        }
                                                        
                                                        if($pkID <= 0){
                                                            $selectedTxtYrly = "selected";
                                                        }
                                                        ?>                                                        
                                                        <option value="year" <?php echo $selectedTxtYrly; ?>>Annually</option>
                                                        <option value="semiannually" <?php echo $selectedTxtHlfYr; ?>>Semi-Annually</option> 
                                                        <option value="quarterly" <?php echo $selectedTxtQtrly; ?>>Quarterly</option>  
                                                        <option value="monthly" <?php echo $selectedTxtMnthly; ?>>Monthly</option>  
                                                        <option value="semimonthly" <?php echo $selectedTxtHlfMnthly; ?>>Semi-Monthly</option> 
                                                        <option value="biweekly" <?php echo $selectedTxtBiWkly; ?>>BiWeekly</option>  
                                                        <option value="weekly" <?php echo $selectedTxtWkly; ?>>Weekly</option>
                                                        <option value="daily"<?php echo $selectedTxtDaily; ?>>Daily</option>
                                                        <option value="simple"<?php echo $selectedTxtSimple; ?>>Simple Interest</option>
                                                    </select>
                                                </div>
                                            </div>                                              
                                            <div style="display:none !important;" class="form-group form-group-sm">
                                                <?php echo dsplyCntrlLbl($cnt, $posting_frequency, $posting_frequencyATZ, "col-md-4", "Posting Frequency", "pstnFrqncy"); ?>
                                                <label for="pstnFrqncy" class="control-label col-md-4">Posting Frequency:</label>
                                                <div  class="col-md-8">
                                                    <select <?php echo $mkReadOnlyDsbld; ?> class="form-control" id="pstnFrqncy" >
                                                        <!--<option value="">&nbsp;</option>-->
                                                        <?php
                                                        $brghtStr = "";
                                                        $isDynmyc = FALSE;
                                                        $titleRslt = getLovValues("%", "Both", 0, 100, $brghtStr, getLovID("Interest Posting Frequencies"), $isDynmyc, -1, "", "");
                                                        while ($titleRow = loc_db_fetch_array($titleRslt)) {
                                                            $selectedTxt = "";
                                                            if ($posting_frequency == $titleRow[0]) {
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
                                            <div  class="form-group form-group-sm">
                                                <?php echo dsplyCntrlLbl($cnt, $guarantor_required, $guarantor_requiredATZ, "col-md-4", "Guarantor Rqrd?", "grntaRqrd"); ?>
                                                <div  class="col-md-8">
                                                    <?php
                                                    $chkdYesCI = "checked=\"\"";
                                                    $chkdNoCI = "";
                                                    if ($guarantor_required == "No") {
                                                        $chkdNoCI = "checked=\"\"";
                                                        $chkdYesCI = "";
                                                    }
                                                    ?>
                                                    <label class="radio-inline"><input type="radio" name="grntaRqrd" value="Yes" <?php echo $chkdYesCI; ?>>YES</label>
                                                    <label class="radio-inline"><input type="radio" name="grntaRqrd" value="No" <?php echo $chkdNoCI; ?>>NO</label>
                                                </div>                                                              
                                            </div>
                                            <div class="form-group form-group-sm">
                                                <?php echo dsplyCntrlLbl($cnt, $guarantor_no, $guarantor_noATZ, "col-md-4", "Guarantor No.", "grntaNo"); ?>
                                                <div  class="col-md-8">
                                                    <input <?php echo $mkReadOnly; ?> class="form-control" id="grntaNo" type = "number" min="0" placeholder="Guarantor No." value="<?php echo $guarantor_no; ?>"/>
                                                </div>
                                            </div>
                                            <div class="form-group form-group-sm">
                                                <?php echo dsplyCntrlLbl($cnt, $ttl_allwd_principal_rcvbl_amnt_flat, $ttl_allwd_principal_rcvbl_amnt_flatATZ, "col-md-4", "Max. Principal Rcvbl. Balance", "maxPrncplRcvbl"); ?>
                                                <div class="col-md-8">
                                                    <input <?php echo $mkReadOnly; ?> class="form-control rqrdFld" id="maxPrncplRcvbl" type = "number" placeholder="Max Principal Rcvbl" value="<?php echo $ttl_allwd_principal_rcvbl_amnt_flat; ?>"/>                                                                                                                                         
                                                </div>
                                            </div>                                           
                                        </fieldset>  
                                    </div>
                                    <div class="col-lg-4">
                                        <fieldset class="basic_person_fs1"><legend class="basic_person_lg">Accounts</legend>
                                            <div class="form-group form-group-sm">
                                                <?php $mv = getGnrlRecNm("accb.accb_chart_of_accnts", "accnt_id", "accnt_num || '.' || accnt_name", $principal_rcvbl_acct_id);
                                                echo dsplyCntrlLbl($cnt, $mv, getGnrlRecNm("accb.accb_chart_of_accnts", "accnt_id", "accnt_num || '.' || accnt_name", $principal_rcvbl_acct_idATZ), "col-md-4", "Principal Receivable", "prncplRcvblDbtAcc"); ?>
                                                <div  class="col-md-8">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" aria-label="..." id="prncplRcvblDbtAcc" value="<?php echo $mv; ?>" readonly="readonly">
                                                        <input type="hidden" id="gnrlOrgID" value="<?php echo $orgID; ?>">
                                                        <input type="hidden" id="prncplRcvblDbtAccID" value="<?php echo $principal_rcvbl_acct_id; ?>">
                                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Accounts', 'gnrlOrgID', '', '', 'radio', true, '<?php echo ""; ?>', 'prncplRcvblDbtAccID', 'prncplRcvblDbtAcc', 'clear', 1, ' AND (SELECT is_prnt_accnt FROM accb.accb_chart_of_accnts x WHERE x.accnt_id = tbl1.a::bigint) = \'0\' ');">
                                                            <span class="glyphicon glyphicon-th-list"></span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group form-group-sm">
                                                <?php $mv1 =getGnrlRecNm("accb.accb_chart_of_accnts", "accnt_id", "accnt_num || '.' || accnt_name", $loans_payable_acct_id);
                                                echo dsplyCntrlLbl($cnt, $mv1, getGnrlRecNm("accb.accb_chart_of_accnts", "accnt_id", "accnt_num || '.' || accnt_name", $loans_payable_acct_idATZ), "col-md-4", "Loans Payable", "loansPybleCrdtAcc"); ?>
                                                <div  class="col-md-8">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" aria-label="..." id="loansPybleCrdtAcc" value="<?php echo $mv1; ?>" readonly="readonly">
                                                        <input type="hidden" id="gnrlOrgID" value="<?php echo $orgID; ?>">
                                                        <input type="hidden" id="loansPybleCrdtAccID" value="<?php echo $loans_payable_acct_id; ?>">
                                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Accounts', 'gnrlOrgID', '', '', 'radio', true, '<?php echo ""; ?>', 'loansPybleCrdtAccID', 'loansPybleCrdtAcc', 'clear', 1, ' AND (SELECT is_prnt_accnt FROM accb.accb_chart_of_accnts x WHERE x.accnt_id = tbl1.a::bigint) = \'0\' ');">
                                                            <span class="glyphicon glyphicon-th-list"></span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group form-group-sm">
                                                <?php $mv2 = getGnrlRecNm("accb.accb_chart_of_accnts", "accnt_id", "accnt_num || '.' || accnt_name", $interest_rcvbl_acct_id);
                                                echo dsplyCntrlLbl($cnt, $mv2, getGnrlRecNm("accb.accb_chart_of_accnts", "accnt_id", "accnt_num || '.' || accnt_name", $interest_rcvbl_acct_idATZ), "col-md-4", "Interest Receivable", "intrstRcvblDbtAcc"); ?>
                                                <div  class="col-md-8">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" aria-label="..." id="intrstRcvblDbtAcc" value="<?php echo $mv2; ?>" readonly="readonly">
                                                        <input type="hidden" id="gnrlOrgID" value="<?php echo $orgID; ?>">
                                                        <input type="hidden" id="intrstRcvblDbtAccID" value="<?php echo $interest_rcvbl_acct_id; ?>">
                                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Accounts', 'gnrlOrgID', '', '', 'radio', true, '<?php echo ""; ?>', 'intrstRcvblDbtAccID', 'intrstRcvblDbtAcc', 'clear', 1, ' AND (SELECT is_prnt_accnt FROM accb.accb_chart_of_accnts x WHERE x.accnt_id = tbl1.a::bigint) = \'0\' ');">
                                                            <span class="glyphicon glyphicon-th-list"></span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group form-group-sm">
                                                <?php $mv3 = getGnrlRecNm("accb.accb_chart_of_accnts", "accnt_id", "accnt_num || '.' || accnt_name", $deferred_interest_acct_id);
                                                echo dsplyCntrlLbl($cnt, $mv3, getGnrlRecNm("accb.accb_chart_of_accnts", "accnt_id", "accnt_num || '.' || accnt_name", $deferred_interest_acct_idATZ), "col-md-4", "Deferred Interest", "dfrdIntrstCrdtAcc"); ?>
                                                <div  class="col-md-8">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" aria-label="..." id="dfrdIntrstCrdtAcc" value="<?php echo $mv3; ?>" readonly="readonly">
                                                        <input type="hidden" id="gnrlOrgID" value="<?php echo $orgID; ?>">
                                                        <input type="hidden" id="dfrdIntrstCrdtAccID" value="<?php echo $deferred_interest_acct_id; ?>">
                                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Accounts', 'gnrlOrgID', '', '', 'radio', true, '<?php echo ""; ?>', 'dfrdIntrstCrdtAccID', 'dfrdIntrstCrdtAcc', 'clear', 1, ' AND (SELECT is_prnt_accnt FROM accb.accb_chart_of_accnts x WHERE x.accnt_id = tbl1.a::bigint) = \'0\' ');">
                                                            <span class="glyphicon glyphicon-th-list"></span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group form-group-sm">
                                                <?php $mv4 = getGnrlRecNm("accb.accb_chart_of_accnts", "accnt_id", "accnt_num || '.' || accnt_name", $interest_revenue_acct_id);
                                                echo dsplyCntrlLbl($cnt, $mv4, getGnrlRecNm("accb.accb_chart_of_accnts", "accnt_id", "accnt_num || '.' || accnt_name", $interest_revenue_acct_idATZ), "col-md-4", "Interest Revenue", "intrstRvnueCrdtAcc"); ?>
                                                <div  class="col-md-8">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" aria-label="..." id="intrstRvnueCrdtAcc" value="<?php echo $mv4; ?>" readonly="readonly">
                                                        <input type="hidden" id="gnrlOrgID" value="<?php echo $orgID; ?>">
                                                        <input type="hidden" id="intrstRvnueCrdtAccID" value="<?php echo $interest_revenue_acct_id; ?>">
                                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Accounts', 'gnrlOrgID', '', '', 'radio', true, '<?php echo ""; ?>', 'intrstRvnueCrdtAccID', 'intrstRvnueCrdtAcc', 'clear', 1, ' AND (SELECT is_prnt_accnt FROM accb.accb_chart_of_accnts x WHERE x.accnt_id = tbl1.a::bigint) = \'0\' ');">
                                                            <span class="glyphicon glyphicon-th-list"></span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group form-group-sm">
                                                <?php $mv5 = getGnrlRecNm("accb.accb_chart_of_accnts", "accnt_id", "accnt_num || '.' || accnt_name", $bad_debt_acct_id);
                                                echo dsplyCntrlLbl($cnt, $mv5, getGnrlRecNm("accb.accb_chart_of_accnts", "accnt_id", "accnt_num || '.' || accnt_name", $bad_debt_acct_idATZ), "col-md-4", "Bad Debt", "badDebtExpnsAcc"); ?>
                                                <div  class="col-md-8">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" aria-label="..." id="badDebtExpnsAcc" value="<?php echo $mv5; ?>" readonly="readonly">
                                                        <input type="hidden" id="gnrlOrgID" value="<?php echo $orgID; ?>">
                                                        <input type="hidden" id="badDebtExpnsAccID" value="<?php echo $bad_debt_acct_id; ?>">
                                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Accounts', 'gnrlOrgID', '', '', 'radio', true, '<?php echo ""; ?>', 'badDebtExpnsAccID', 'badDebtExpnsAcc', 'clear', 1, ' AND (SELECT is_prnt_accnt FROM accb.accb_chart_of_accnts x WHERE x.accnt_id = tbl1.a::bigint) = \'0\' ');">
                                                            <span class="glyphicon glyphicon-th-list"></span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group form-group-sm">
                                                <?php $mv7 = getGnrlRecNm("accb.accb_chart_of_accnts", "accnt_id", "accnt_num || '.' || accnt_name", $loan_provision_dbt_acct_id);
                                                echo dsplyCntrlLbl($cnt, $mv7, getGnrlRecNm("accb.accb_chart_of_accnts", "accnt_id", "accnt_num || '.' || accnt_name", $loan_provision_dbt_acct_idATZ), "col-md-4", "Provision Dbt", "prvsnExpnsAcc"); ?>
                                                <div  class="col-md-8">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" aria-label="..." id="prvsnExpnsAcc" value="<?php echo $mv7; ?>" readonly="readonly">
                                                        <input type="hidden" id="gnrlOrgID" value="<?php echo $orgID; ?>">
                                                        <input type="hidden" id="prvsnExpnsAccID" value="<?php echo $loan_provision_dbt_acct_id; ?>">
                                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Accounts', 'gnrlOrgID', '', '', 'radio', true, '<?php echo ""; ?>', 'prvsnExpnsAccID', 'prvsnExpnsAcc', 'clear', 1, ' AND (SELECT is_prnt_accnt FROM accb.accb_chart_of_accnts x WHERE x.accnt_id = tbl1.a::bigint) = \'0\' ');">
                                                            <span class="glyphicon glyphicon-th-list"></span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group form-group-sm">
                                                <?php $mv6 = getGnrlRecNm("accb.accb_chart_of_accnts", "accnt_id", "accnt_num || '.' || accnt_name", $loan_provision_crdt_acct_id);
                                                echo dsplyCntrlLbl($cnt, $mv6, getGnrlRecNm("accb.accb_chart_of_accnts", "accnt_id", "accnt_num || '.' || accnt_name", $loan_provision_crdt_acct_idATZ), "col-md-4", "Provision Crdt", "prvsnContraAcc"); ?>
                                                <div  class="col-md-8">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" aria-label="..." id="prvsnContraAcc" value="<?php echo $mv6; ?>" readonly="readonly">
                                                        <input type="hidden" id="gnrlOrgID" value="<?php echo $orgID; ?>">
                                                        <input type="hidden" id="prvsnContraAccID" value="<?php echo $loan_provision_crdt_acct_id; ?>">
                                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Accounts', 'gnrlOrgID', '', '', 'radio', true, '<?php echo ""; ?>', 'prvsnContraAccID', 'prvsnContraAcc', 'clear', 1, ' AND (SELECT is_prnt_accnt FROM accb.accb_chart_of_accnts x WHERE x.accnt_id = tbl1.a::bigint) = \'0\' ');">
                                                            <span class="glyphicon glyphicon-th-list"></span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </fieldset>
                                    </div>                                    
                                    <div class="col-lg-4"> 
                                        <fieldset class="basic_person_fs1"><legend class="basic_person_lg">General</legend> 
                                            <div  class="form-group form-group-sm">
                                                <?php echo dsplyCntrlLbl($cnt, $is_account_rqrd, $is_account_rqrdATZ, "col-md-4", "Account Required?", "isAcctRqrd"); ?>
                                                <div  class="col-md-8">
                                                    <?php
                                                    $chkdYes = "checked=\"\"";
                                                    $chkdNo = "";
                                                    if ($is_account_rqrd == "No") {
                                                        $chkdNo = "checked=\"\"";
                                                        $chkdYes = "";
                                                    }
                                                    ?>
                                                    <label class="radio-inline"><input type="radio" name="isAcctRqrd" value="Yes" <?php echo $chkdYes; ?>>YES</label>
                                                    <label class="radio-inline"><input type="radio" name="isAcctRqrd" value="No" <?php echo $chkdNo; ?>>NO</label>
                                                </div>                                                              
                                            </div>
                                            <div  class="form-group form-group-sm">
                                                <?php echo dsplyCntrlLbl($cnt, $rcvry_officer_rqrd, $rcvry_officer_rqrdATZ, "col-md-4", "Credit Officer Required?", "crdtOfficerRqrd"); ?>
                                                <div  class="col-md-8">
                                                    <?php
                                                    $chkdYes = "checked=\"\"";
                                                    $chkdNo = "";
                                                    if ($rcvry_officer_rqrd == "No") {
                                                        $chkdNo = "checked=\"\"";
                                                        $chkdYes = "";
                                                    }
                                                    ?>
                                                    <label class="radio-inline"><input type="radio" name="crdtOfficerRqrd" value="Yes" <?php echo $chkdYes; ?>>YES</label>
                                                    <label class="radio-inline"><input type="radio" name="crdtOfficerRqrd" value="No" <?php echo $chkdNo; ?>>NO</label>
                                                </div>                                                              
                                            </div>
                                            <div class="form-group form-group-sm">
                                                <?php echo dsplyCntrlLbl($cnt, $scoringSet, $scoringSetATZ, "col-md-4", "Assessment Set", "assmntSet"); ?>
                                                <div  class="col-md-8">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" aria-label="..." id="assmntSet" value="<?php echo $scoringSet; ?>" readonly="readonly">
                                                        <input type="hidden" id="assmntSetID" value="<?php echo $scoring_set_hdr_id; ?>">
                                                        <input type="hidden" id="gnrlOrgID" value="<?php echo $orgID; ?>">
                                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'MCF Assessemnt Set - Valid', 'gnrlOrgID', '', '', 'radio', true, '', 'assmntSetID', 'assmntSet', 'clear', 1, '');">
                                                            <span class="glyphicon glyphicon-th-list"></span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group form-group-sm">
                                                <?php  echo dsplyCntrlLbl($cnt, $grpType, $grpTypeATZ, "col-md-4", "Allowed Group Type", "grpType"); ?>
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
                                                        if ($grpType === "Everyone") {
                                                            $sltdEvryone = "selected=\"selected\"";
                                                        } else if ($grpType === "Divisions/Groups") {
                                                            $sltdDvGrp = "selected=\"selected\"";
                                                        } else if ($grpType === "Grade") {
                                                            $sltdGrd = "selected=\"selected\"";
                                                        } else if ($grpType === "Job") {
                                                            $sltdJob = "selected=\"selected\"";
                                                        } else if ($grpType === "Position") {
                                                            $sltdPstn = "selected=\"selected\"";
                                                        } else if ($grpType === "Site/Location") {
                                                            $sltdSiteLoc = "selected=\"selected\"";
                                                        } else if ($grpType === "Person Type") {
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
                                                <?php  echo dsplyCntrlLbl($cnt, $grpType, $grpTypeATZ, "col-md-4", "Allowed Group Name", "groupName"); ?>
                                                <div  class="col-md-8">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" aria-label="..." id="groupName" value="<?php echo getAllwdGrpVal($grpType, $groupID); ?>" readonly="">
                                                        <input type="hidden" id="gnrlOrgID" value="<?php echo $orgID; ?>">
                                                        <input type="hidden" id="groupID" value="<?php echo $groupID; ?>">
                                                        <label disabled="true" id="groupNameLbl" class="btn btn-primary btn-file input-group-addon" onclick="getNoticeLovs('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Customers and Suppliers', 'gnrlOrgID', '', '', 'radio', true, '', 'groupID', 'groupName', 'clear', 1, '');">
                                                            <span class="glyphicon glyphicon-th-list"></span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group form-group-sm">
                                                <?php echo dsplyCntrlLbl($cnt, $sector_clsfctn_major, $sector_clsfctn_majorATZ, "col-md-4", "Sector Major", "sctrMjr"); ?>
                                                <div  class="col-md-8">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" aria-label="..." id="sctrMjr" value="<?php echo $sector_clsfctn_major; ?>" readonly="readonly">
                                                        <input type="hidden" id="sctrMjrID" value="<?php echo $sector_clsfctn_major_id; ?>">
                                                        <input type="hidden" id="gnrlOrgID" value="<?php echo $orgID; ?>">
                                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'MCF Loan Sectors - Major', 'gnrlOrgID', '', '', 'radio', true, '', 'sctrMjrID', 'sctrMjr', 'clear', 1, '');">
                                                            <span class="glyphicon glyphicon-th-list"></span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group form-group-sm">
                                                <?php echo dsplyCntrlLbl($cnt, $sector_clsfctn_minor, $sector_clsfctn_minorATZ, "col-md-4", "Sector Minor", "sctrMnr"); ?>
                                                <div  class="col-md-8">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" aria-label="..." id="sctrMnr" value="<?php echo $sector_clsfctn_minor; ?>" readonly="readonly">
                                                        <input type="hidden" id="sctrMnrID" value="<?php echo $sector_clsfctn_minor_id; ?>">
                                                        <input type="hidden" id="gnrlOrgID" value="<?php echo $orgID; ?>">
                                                        <label class="btn btn-primary btn-file input-group-addon" onclick="onClickSectorMinor();">
                                                            <span class="glyphicon glyphicon-th-list"></span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div> 
                                            <div class="form-group form-group-sm">
                                                <?php echo dsplyCntrlLbl($cnt, $start_date_active, $start_date_activeATZ, "col-md-4", "Start Date", "startDate"); ?>
                                                <div  class="col-md-8">
                                                    <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input3" data-link-format="yyyy-mm-dd">
                                                        <input class="form-control" size="16" type="text" id="startDate" value="<?php echo $start_date_active; ?>" readonly="">
                                                        <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                    </div>
                                                </div>
                                            </div>      
                                            <div class="form-group form-group-sm">
                                                <?php echo dsplyCntrlLbl($cnt, $end_date_active, $end_date_activeATZ, "col-md-4", "End Date", "endDate"); ?>
                                                <div  class="col-md-8">
                                                    <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input4" data-link-format="yyyy-mm-dd">
                                                        <input class="form-control" size="16" type="text" id="endDate" value="<?php echo $end_date_active; ?>" readonly="">
                                                        <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                    </div>
                                                </div>
                                            </div>                                                          
                                        </fieldset>   
                                    </div>
                                </div>                                  
                                <div class="row"><!-- ROW 3 -->
                                    <div class="col-lg-12"> 
                                        <fieldset class="basic_person_fs5" style="margin-top: 5px !important;"><legend class="basic_person_lg1">Attach Product To Customer Types</legend> 
                                            <div  class="col-md-4">
                                                <div class="form-group form-group-sm">
                                                    <?php echo dsplyCntrlLbl($cnt, $cust_type_ind, $cust_type_indATZ, "col-md-8", "Individual Customers", "indCust"); ?>
                                                    <div class="form-check col-md-4">
                                                        <label class="form-check-label">
                                                            <?php
                                                            $chkd = "checked=\"\"";
                                                            if ($cust_type_ind == "NO") {
                                                                $chkd = "";
                                                            }
                                                            ?>																
                                                            <input type="checkbox" class="form-check-input" id="indCust" name="indCust" <?php echo $chkd; ?>>
                                                            YES
                                                        </label>
                                                    </div>
                                                </div>                                                                
                                            </div>
                                            <div  class="col-md-4">
                                                <div class="form-group form-group-sm">
                                                    <?php echo dsplyCntrlLbl($cnt, $cust_type_corp, $cust_type_corpATZ, "col-md-8", "Corporate Customers", "corpCust"); ?>
                                                    <div class="form-check col-md-4">
                                                        <label class="form-check-label">
                                                            <?php
                                                            $chkd = "checked=\"\"";
                                                            if ($cust_type_corp == "NO") {
                                                                $chkd = "";
                                                            }
                                                            ?>																	
                                                            <input type="checkbox" class="form-check-input" id="corpCust" name="corpCust" <?php echo $chkd; ?>>
                                                            YES
                                                        </label>
                                                    </div>
                                                </div>   
                                            </div>
                                            <div  class="col-md-4">
                                                <div class="form-group form-group-sm">
                                                    <?php echo dsplyCntrlLbl($cnt, $cust_type_custgrp, $cust_type_custgrpATZ, "col-md-8", "Customer Groups", "custGrp"); ?>
                                                    <div class="form-check col-md-4">
                                                        <label class="form-check-label">
                                                            <?php
                                                            $chkd = "checked=\"\"";
                                                            if ($cust_type_custgrp == "NO") {
                                                                $chkd = "";
                                                            }
                                                            ?>																	
                                                            <input type="checkbox" class="form-check-input" id="custGrp" name="custGrp" <?php echo $chkd; ?>>
                                                            YES
                                                        </label>
                                                    </div>
                                                </div>  
                                            </div>
                                        </fieldset>
                                    </div>
                                </div> 
                                <div class="row"><!-- ROW 4 -->
                                    <div class="col-lg-12">  
                                        <div class="row" id="allPrcsnFeesDetailInfo" style="padding:0px 15px 0px 15px !important">
                                            <?php
                                            /* &vtyp=<?php echo $vwtyp; ?> */
                                            $srchFor = "%";
                                            $srchIn = "Fee Name";
                                            $pageNo = 1;
                                            $lmtSze = 10;
                                            $vwtyp = 1;
                                            if ($pkID > 0) {
                                                $total = get_AllLoanPrdtPrcsnFeeTtl($srchFor, $srchIn, $pkID);
                                                //$total = get_AllBanksTtl($srchFor, $srchIn, $pkID);
                                                if ($pageNo > ceil($total / $lmtSze)) {
                                                    $pageNo = 1;
                                                } else if ($pageNo < 1) {
                                                    $pageNo = ceil($total / $lmtSze);
                                                }
                                                $curIdx = $pageNo - 1;
                                                $result2 = get_AllLoanPrdtPrcsnFee($srchFor, $srchIn, $curIdx, $lmtSze, $pkID);
                                                ?>
                                                <div class="row" style="padding:0px 15px 0px 15px !important">
                                                    <legend class="basic_person_lg1" style="color: #003245">PROCESSING FEES</legend>
                                                    <?php
                                                    if ($canEdtRiskProfile === true) {
                                                        $colClassType1 = "col-lg-2";
                                                        $colClassType2 = "col-lg-3";
                                                        $colClassType3 = "col-lg-4";
                                                        $nwRowHtml = urlencode("<tr id=\"allPrcsnFeesRow__WWW123WWW\">"
                                                                        . "<td class=\"lovtd\"><span class=\"normaltd\">New</span></td>
                                                                            <td class=\"lovtd\">
                                                                                <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"allPrcsnFeesRow_WWW123WWW_LoanprdtPrcssnFeeID\" value=\"-1\" style=\"width:100% !important;\"> 
                                                                                <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"allPrcsnFeesRow_WWW123WWW_LoanPrdtID\" value=\"<?php echo sbmtdPrdtID; ?>\" style=\"width:100% !important;\">                                                                         
                                                                                <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"allPrcsnFeesRow_WWW123WWW_FeeNm\" name=\"allPrcsnFeesRow_WWW123WWW_FeeNm\" value=\"\">                                                                        
                                                                            </td>
                                                                            <td class=\"lovtd\">
                                                                                <div class=\"input-group\">
                                                                                    <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"allPrcsnFeesRow_WWW123WWW_FeeType\" name=\"allPrcsnFeesRow_WWW123WWW_FeeType\" value=\"\" readonly=\"true\">
                                                                                    <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'MCF Loan Processing Fee Types', 'gnrlOrgID', '', '', 'radio', true, '', 'allPrcsnFeesRow_WWW123WWW_FeeTypeID', 'allPrcsnFeesRow_WWW123WWW_FeeType', 'clear', 1, '');\">
                                                                                            <span class=\"glyphicon glyphicon-th-list\"></span>
                                                                                    </label>
                                                                                    <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"allPrcsnFeesRow_WWW123WWW_FeeTypeID\" value=\"-1\" style=\"width:100% !important;\"> 
                                                                                </div>
                                                                            </td>                                             
                                                                            <td class=\"lovtd\">
                                                                                    <input type=\"number\" min=\"0\" class=\"form-control rqrdFld\" aria-label=\"...\" id=\"allPrcsnFeesRow_WWW123WWW_AmntFlat\" name=\"allPrcsnFeesRow_WWW123WWW_AmntFlat\" value=\"\">                                                               
                                                                            </td>
                                                                            <td class=\"lovtd\">
                                                                                    <input type=\"number\" min=\"0\" class=\"form-control rqrdFld\" aria-label=\"...\" id=\"allPrcsnFeesRow_WWW123WWW_AmntPrcnt\" name=\"allPrcsnFeesRow_WWW123WWW_AmntPrcnt\" value=\"\">                                                               
                                                                            </td>
                                                                            <td class=\"lovtd\">                                                                         
                                                                                <div class=\"input-group\">
                                                                                    <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"allPrcsnFeesRow_WWW123WWW_RvnAcct\" name=\"allPrcsnFeesRow_WWW123WWW_RvnAcct\" value=\"\" readonly=\"true\">
                                                                                    <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Accounts', 'gnrlOrgID', '', '', 'radio', true, '', 'allPrcsnFeesRow_WWW123WWW_RvnAcctID', 'allPrcsnFeesRow_WWW123WWW_RvnAcct', 'clear', 1, ' AND (SELECT is_prnt_accnt FROM accb.accb_chart_of_accnts x WHERE x.accnt_id = tbl1.a::bigint) = \'0\' ');\">
                                                                                         <span class=\"glyphicon glyphicon-th-list\"></span>
                                                                                    </label>
                                                                                    <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"allPrcsnFeesRow_WWW123WWW_RvnAcctID\" value=\"-1\" readonly=\"true\"> 
                                                                                </div>                                                                        
                                                                            </td>
                                                                            <td class=\"lovtd\">  
                                                                                    <input type=\"text\" min=\"0\" class=\"form-control\" aria-label=\"...\" id=\"allPrcsnFeesRow_WWW123WWW_Rmrks\" name=\"allPrcsnFeesRow_WWW123WWW_Rmrks\" value=\"\">                                                               
                                                                            </td>
                                                                            <td class=\"lovtd\">
                                                                                <button type=\"button\" class=\"btn btn-default\" style=\"margin: 0px !important;padding:0px 3px 2px 4px !important;\" onclick=\"delPrcsnFee('allPrcsnFeesRow__WWW123WWW');\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Delete Profile Factor\">
                                                                                        <img src=\"cmn_images/no.png\" style=\"height:15px; width:auto; position: relative; vertical-align: middle;\">
                                                                                </button>
                                                                            </td>
                                                        </tr>");
                                                        ?>
                                                        <div class="<?php echo $colClassType1; ?>" style="padding:0px 1px 0px 3px !important;">
                                                            <?php if (($trnsStatus == "Incomplete" || $trnsStatus == "Withdrawn" || $trnsStatus == "Rejected") && $vwtypActn != "VIEW") { ?>
                                                            <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="insertNewRowBe4('allPrcsnFeesTable', 0, '<?php echo $nwRowHtml; ?>');" data-toggle="tooltip" data-placement="bottom" title="Add Profile Factor">
                                                                <img src="cmn_images/add1-64.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                            </button>
                                                            <?php } ?>
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
                                                            <input class="form-control" id="allPrcsnFeesSrchFor" type = "text" placeholder="Search For" value="<?php
                                                            echo trim(str_replace("%", " ", $srchFor));
                                                            ?>" onkeyup="enterKeyFuncAllPrcsnFees(event, '', '#allPrcsnFeesDetailInfo', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&subPgNo=7.21&vtypActn=<?php echo $vwtypActn; ?>&prdtID=<?php echo $pkID; ?>');">
                                                            <input id="allPrcsnFeesPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getAllPrcsnFees('clear', '#allPrcsnFeesDetailInfo', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&subPgNo=7.21&vtypActn=<?php echo $vwtypActn; ?>&prdtID=<?php echo $pkID; ?>');">
                                                                <span class="glyphicon glyphicon-remove"></span>
                                                            </label>
                                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getAllPrcsnFees('', '#allPrcsnFeesDetailInfo', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&subPgNo=7.21&vtypActn=<?php echo $vwtypActn; ?>&prdtID=<?php echo $pkID; ?>');">
                                                                <span class="glyphicon glyphicon-search"></span>
                                                            </label> 
                                                        </div>
                                                    </div>
                                                    <div class="<?php echo $colClassType2; ?>" style="padding:0px 3px 0px 3px !important;">
                                                        <div class="input-group">
                                                            <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                                            <!--<select data-placeholder="Select..." class="form-control chosen-select" id="allPrcsnFeesSrchIn">
                                                            <?php
                                                            $valslctdArry = array("");
                                                            $srchInsArrys = array("Fee Name");

                                                            for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                                if ($srchIn == $srchInsArrys[$z]) {
                                                                    $valslctdArry[$z] = "selected";
                                                                }
                                                                ?>
                                                                                                                        <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                                            <?php } ?>
                                                            </select>-->
                                                            <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                                            <select data-placeholder="Select..." class="form-control chosen-select" id="allPrcsnFeesDsplySze" style="min-width:70px !important;">                            
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
                                                                    <a class="rhopagination" href="javascript:getAllPrcsnFees('previous', '#allPrcsnFeesDetailInfo', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&subPgNo=7.21&vtypActn=<?php echo $vwtypActn; ?>&prdtID=<?php echo $pkID; ?>');" aria-label="Previous">
                                                                        <span aria-hidden="true">&laquo;</span>
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <a class="rhopagination" href="javascript:getAllPrcsnFees('next', '#allPrcsnFeesDetailInfo', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&subPgNo=7.21&vtypActn=<?php echo $vwtypActn; ?>&prdtID=<?php echo $pkID; ?>');" aria-label="Next">
                                                                        <span aria-hidden="true">&raquo;</span>
                                                                    </a>
                                                                </li>
                                                            </ul>
                                                        </nav>
                                                        <input type="hidden" class="form-control" aria-label="..." id="gnrlOrgID" name="gnrlOrgID" value="<?php echo $orgID; ?>">                                                    
                                                        <input type="hidden" class="form-control" aria-label="..." id="sbmtdRiskProfileID" name="sbmtdRiskProfileID" value="<?php echo $pkID; ?>">
                                                    </div>
                                                </div>
                                                <div class="row" style="padding:0px 15px 0px 15px !important">                  
                                                    <div class="col-md-12" style="padding:0px 3px 0px 3px !important">
                                                        <table class="table table-striped table-bordered table-responsive" id="allPrcsnFeesTable" cellspacing="0" width="100%" style="width:100%;min-width: 300px !important;">
                                                            <thead>
                                                                <tr>
                                                                    <th>No.</th>
                                                                    <th>Fee Name</th>
                                                                    <th>Fee Type</th>
                                                                    <th>Amount Flat</th>
                                                                    <th>Amount Percent</th>
                                                                    <th>Revenue Account</th>
                                                                    <th>Remarks</th>
                                                                    <?php if (($trnsStatus == "Incomplete" || $trnsStatus == "Withdrawn" || $trnsStatus == "Rejected") && $vwtypActn != "VIEW") { ?>
                                                                    <th>&nbsp;</th>
                                                                    <?php } ?>
                                                                    <th <?php echo $shwHydNtlntySts; ?>>Status</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php
                                                                $cntr = 0;
                                                                while ($row2 = loc_db_fetch_array($result2)) {                                                                
                                                                    $cntr += 1;
                                                                    $ttlOptnsScore = 0;
                                                                    $row1ATZ0 = ""; $row1ATZ1 = ""; $row1ATZ2 = ""; $row1ATZ3 = ""; $row1ATZ4 = ""; $row1ATZ5 = "";
                                                                    $row1ATZ6 = -1; $row1ATZ7 = "";
                                                                    if($row2[0] > 0 && $row2[8] === "Yes"){
                                                                        $result1ATZ = get_AllLoanPrdtPrcsnFeeATZ($row2[0]);
                                                                        while ($row1ATZ = loc_db_fetch_array($result1ATZ)) {
                                                                            $row1ATZ0 = $row1ATZ[0]; $row1ATZ1 = $row1ATZ[1]; $row1ATZ2 = $row1ATZ[2]; $row1ATZ3 = $row1ATZ[3];
                                                                            $row1ATZ4 = $row1ATZ[4]; $row1ATZ5 = $row1ATZ[5]; $row1ATZ6 = $row1ATZ[6]; $row1ATZ7 = $row1ATZ[7];
                                                                        }
                                                                    }
                                                                    ?>
                                                                    <tr id="allPrcsnFeesRow_<?php echo $cntr; ?>">                                    
                                                                        <td class="lovtd"><span><?php echo ($curIdx * $lmtSze) + ($cntr); ?></span></td>
                                                                        <td class="lovtd">
                                                                            <input type="hidden" class="form-control" aria-label="..." id="allPrcsnFeesRow<?php echo $cntr; ?>_LoanprdtPrcssnFeeID" value="<?php echo $row2[0]; ?>" style="width:100% !important;"> 
                                                                            <input type="hidden" class="form-control" aria-label="..." id="allPrcsnFeesRow<?php echo $cntr; ?>_LoanPrdtID" value="<?php echo $row2[1]; ?>" style="width:100% !important;">                                                                         
                                                                            <?php if($vwtypActn === "VIEW" || $trnsStatus === "Initiated" || $trnsStatus === "Unauthorized"){ 
                                                                            echo dsplyTblData($row2[2], $row1ATZ2, $row2[0], $row2[8]);  
                                                                            } else { ?>
                                                                            <input <?php echo $mkReadOnly; ?> type="text" class="form-control" aria-label="..." id="allPrcsnFeesRow<?php echo $cntr; ?>_FeeNm" name="allPrcsnFeesRow<?php echo $cntr; ?>_FeeNm" value="<?php echo $row2[2]; ?>">                                                                        
                                                                            <?php } ?>
                                                                        </td>
                                                                        <td class="lovtd"> 
                                                                            <?php if($vwtypActn === "VIEW" || $trnsStatus === "Initiated" || $trnsStatus === "Unauthorized"){ 
                                                                            echo dsplyTblData($row2[3], $row1ATZ3, $row2[0], $row2[8]);  
                                                                            } else { ?>
                                                                            <div class="input-group">
                                                                                <input type="text" class="form-control" aria-label="..." id="allPrcsnFeesRow<?php echo $cntr; ?>_FeeType" name="allPrcsnFeesRow<?php echo $cntr; ?>_FeeType" value="<?php echo $row2[3]; ?>" readonly="true">
                                                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'MCF Loan Processing Fee Types', 'gnrlOrgID', '', '', 'radio', true, '', 'allPrcsnFeesRow<?php echo $cntr; ?>_FeeTypeID', 'allPrcsnFeesRow<?php echo $cntr; ?>_FeeType', 'clear', 1, '');">
                                                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                                                </label>
                                                                                <input type="hidden" class="form-control" aria-label="..." id="allPrcsnFeesRow<?php echo $cntr; ?>_FeeTypeID" value="<?php echo $row2[3]; ?>" readonly="true"> 
                                                                            </div> 
                                                                            <?php } ?>
                                                                        </td>
                                                                        <td class="lovtd"> 
                                                                            <?php if($vwtypActn === "VIEW" || $trnsStatus === "Initiated" || $trnsStatus === "Unauthorized"){ 
                                                                            echo dsplyTblData($row2[4], $row1ATZ4, $row2[0], $row2[8]);  
                                                                            } else { ?>
                                                                            <input <?php echo $mkReadOnly; ?> type="number" min="0" class="form-control rqrdFld" aria-label="..." id="allPrcsnFeesRow<?php echo $cntr; ?>_AmntFlat" name="allPrcsnFeesRow<?php echo $cntr; ?>_AmntFlat" value="<?php echo $row2[4]; ?>">
                                                                            <?php } ?>
                                                                        </td>                                            
                                                                        <td class="lovtd">
                                                                            <?php if($vwtypActn === "VIEW" || $trnsStatus === "Initiated" || $trnsStatus === "Unauthorized" || $trnsStatus == "Approved"){ 
                                                                            echo dsplyTblData($row2[5], $row1ATZ5, $row2[0], $row2[8]);  
                                                                            } else { ?>
                                                                            <input <?php echo $mkReadOnly; ?> type="number" min="0" class="form-control rqrdFld" aria-label="..." id="allPrcsnFeesRow<?php echo $cntr; ?>_AmntPrcnt" name="allPrcsnFeesRow<?php echo $cntr; ?>_AmntPrcnt" value="<?php echo $row2[5]; ?>">                                                               
                                                                            <?php } ?>
                                                                        </td>
                                                                        <td class="lovtd">  
                                                                            <?php if($vwtypActn === "VIEW" || $trnsStatus === "Initiated" || $trnsStatus === "Unauthorized"){ 
                                                                            echo dsplyTblData(getGnrlRecNm("accb.accb_chart_of_accnts", "accnt_id", "accnt_num || '.' || accnt_name", $row2[6]), getGnrlRecNm("accb.accb_chart_of_accnts", "accnt_id", "accnt_num || '.' || accnt_name", $row1ATZ6), $row2[0], $row2[8]);  
                                                                            } else { ?>
                                                                            <div class="input-group">
                                                                                <input type="text" class="form-control" aria-label="..." id="allPrcsnFeesRow<?php echo $cntr; ?>_RvnAcct" name="allPrcsnFeesRow<?php echo $cntr; ?>_RvnAcct" value="<?php echo getGnrlRecNm("accb.accb_chart_of_accnts", "accnt_id", "accnt_num || '.' || accnt_name", $row2[6]); ?>" readonly="true">
                                                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Accounts', 'gnrlOrgID', '', '', 'radio', true, '', 'allPrcsnFeesRow<?php echo $cntr; ?>_RvnAcctID', 'allPrcsnFeesRow<?php echo $cntr; ?>_RvnAcct', 'clear', 1, ' AND (SELECT is_prnt_accnt FROM accb.accb_chart_of_accnts x WHERE x.accnt_id = tbl1.a::bigint) = \'0\' ');">
                                                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                                                </label>
                                                                                <input type="hidden" class="form-control" aria-label="..." id="allPrcsnFeesRow<?php echo $cntr; ?>_RvnAcctID" value="<?php echo $row2[6]; ?>" readonly="true"> 
                                                                            </div> 
                                                                            <?php } ?>
                                                                        </td>
                                                                        <td class="lovtd"> 
                                                                            <?php if($vwtypActn === "VIEW" || $trnsStatus === "Initiated" || $trnsStatus === "Unauthorized"){ 
                                                                            echo dsplyTblData($row2[7], $row1ATZ7, $row2[0], $row2[8]);  
                                                                            } else { ?>
                                                                            <input <?php echo $mkReadOnly; ?> type="text" min="0" class="form-control" aria-label="..." id="allPrcsnFeesRow<?php echo $cntr; ?>_Rmrks" name="allPrcsnFeesRow<?php echo $cntr; ?>_Rmrks" value="<?php echo $row2[7]; ?>">                                                               
                                                                            <?php } ?>
                                                                        </td>
                                                                        <?php if (($trnsStatus == "Incomplete" || $trnsStatus == "Withdrawn" || $trnsStatus == "Rejected") && $vwtypActn != "VIEW") { ?>
                                                                        <td class="lovtd">
                                                                            <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delPrcsnFee('allPrcsnFeesRow_<?php echo $cntr; ?>','<?php echo $row2[8]; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Risk Profile">
                                                                                <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                                            </button>
                                                                        </td>
                                                                        <?php } ?>
                                                                        <td <?php echo $shwHydNtlntySts; ?>>
                                                                            <?php 
                                                                            if($row2[0] < 0){
                                                                                echo "<span style='color:red;'><b>Deleted</b></span>";
                                                                            } else  {
                                                                               if($row2[8] === "No"){
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
                                </div>  
                                <div class="row"><!-- ROW 4 -->
                                    <div class="col-lg-12">  
                                        <div class="row" id="allLatePymntFeesDetailInfo" style="padding:0px 15px 0px 15px !important">
                                            <?php
                                            /* &vtyp=<?php echo $vwtyp; ?> */
                                            $srchFor = "%";
                                            $srchIn = "Fee Name";
                                            $pageNo = 1;
                                            $lmtSze = 10;
                                            $vwtyp = 1;
                                            if ($pkID > 0) {
                                                $total = get_AllLoanPrdtLatePymntFeeTtl($srchFor, $srchIn, $pkID);
                                                //$total = get_AllBanksTtl($srchFor, $srchIn, $pkID);
                                                if ($pageNo > ceil($total / $lmtSze)) {
                                                    $pageNo = 1;
                                                } else if ($pageNo < 1) {
                                                    $pageNo = ceil($total / $lmtSze);
                                                }
                                                $curIdx = $pageNo - 1;
                                                $result2 = get_AllLoanPrdtLatePymntFee($srchFor, $srchIn, $curIdx, $lmtSze, $pkID);
                                                ?>
                                                <div class="row" style="padding:0px 15px 0px 15px !important">
                                                    <legend class="basic_person_lg1" style="color: #003245">LATE PAYMENT FEES</legend>
                                                    <?php
                                                    if ($canEdtRiskProfile === true) {
                                                        $colClassType1 = "col-lg-2";
                                                        $colClassType2 = "col-lg-3";
                                                        $colClassType3 = "col-lg-4";
                                                        $nwRowHtml = urlencode("<tr id=\"allLatePymntFeesRow__WWW123WWW\">"
                                                                                        . "<td class=\"lovtd\"><span class=\"normaltd\">New</span></td>
                                                                                                <td class=\"lovtd\">
                                                                                                        <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"allLatePymntFeesRow_WWW123WWW_LoanprdtLateFeeID\" value=\"-1\" style=\"width:100% !important;\"> 
                                                                                                        <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"allLatePymntFeesRow_WWW123WWW_LoanPrdtID\" value\"\" style=\"width:100% !important;\">                                                                         
                                                                                                        <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"allLatePymntFeesRow_WWW123WWW_FeeNm\" name=\"allLatePymntFeesRow_WWW123WWW_FeeNm\" value=\"\">                                                                        
                                                                                                </td>
                                                                                                <td class=\"lovtd\">
                                                                                                    <div class=\"input-group\">
                                                                                                        <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"allLatePymntFeesRow_WWW123WWW_Target\" name=\"allLatePymntFeesRow_WWW123WWW_Target\" value=\"\" readonly=\"true\">
                                                                                                        <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'MCF Loan Late Fee Targets', 'gnrlOrgID', '', '', 'radio', true, '', 'allLatePymntFeesRow_WWW123WWW_TargetID', 'allLatePymntFeesRow_WWW123WWW_Target', 'clear', 1, '');\">
                                                                                                             <span class=\"glyphicon glyphicon-th-list\"></span>
                                                                                                        </label>
                                                                                                        <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"allLatePymntFeesRow_WWW123WWW_TargetID\" value=\"\" style=\"width:100% !important;\"> 
                                                                                                    </div>
                                                                                                </td>                                             
                                                                                                <td class=\"lovtd\">
                                                                                                                <input type=\"number\" min=\"0\" class=\"form-control rqrdFld\" aria-label=\"...\" id=\"allLatePymntFeesRow_WWW123WWW_AmntFlat\" name=\"allLatePymntFeesRow_WWW123WWW_AmntFlat\" value=\"\">                                                               
                                                                                                </td>
                                                                                                <td class=\"lovtd\">
                                                                                                                <input type=\"number\" min=\"0\" class=\"form-control rqrdFld\" aria-label=\"...\" id=\"allLatePymntFeesRow_WWW123WWW_AmntPrcnt\" name=\"allLatePymntFeesRow_WWW123WWW_AmntPrcnt\" value=\"\">                                                               
                                                                                                </td>
                                                                                                <td style=\"display:none !important;\" class=\"lovtd\">                                                                         
                                                                                                        <div class=\"input-group\">
                                                                                                                <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"allLatePymntFeesRow_WWW123WWW_DbtAcct\" name=\"allLatePymntFeesRow_WWW123WWW_DbtAcct\" value=\"\" readonly=\"true\">
                                                                                                                <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Accounts', 'gnrlOrgID', '', '', 'radio', true, '', 'allLatePymntFeesRow_WWW123WWW_DbtAcctID', 'allLatePymntFeesRow_WWW123WWW_DbtAcct', 'clear', 1, ' AND (SELECT is_prnt_accnt FROM accb.accb_chart_of_accnts x WHERE x.accnt_id = tbl1.a::bigint) = \'0\' ');\">
                                                                                                                         <span class=\"glyphicon glyphicon-th-list\"></span>
                                                                                                                </label>
                                                                                                                <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"allLatePymntFeesRow_WWW123WWW_DbtAcctID\" value=\"-1\" readonly=\"true\"> 
                                                                                                        </div>                                                                        
                                                                                                </td>
                                                                                                <td class=\"lovtd\">                                                                         
                                                                                                        <div class=\"input-group\">
                                                                                                                <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"allLatePymntFeesRow_WWW123WWW_CrdtAcct\" name=\"allLatePymntFeesRow_WWW123WWW_CrdtAcct\" value=\"\" readonly=\"true\">
                                                                                                                <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Accounts', 'gnrlOrgID', '', '', 'radio', true, '', 'allLatePymntFeesRow_WWW123WWW_CrdtAcctID', 'allLatePymntFeesRow_WWW123WWW_CrdtAcct', 'clear', 1, ' AND (SELECT is_prnt_accnt FROM accb.accb_chart_of_accnts x WHERE x.accnt_id = tbl1.a::bigint) = \'0\' ');\">
                                                                                                                         <span class=\"glyphicon glyphicon-th-list\"></span>
                                                                                                                </label>
                                                                                                                <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"allLatePymntFeesRow_WWW123WWW_CrdtAcctID\" value=\"-1\" readonly=\"true\"> 
                                                                                                        </div>                                                                        
                                                                                                </td>
                                                                                                <td class=\"lovtd\">
                                                                                                    <span>&nbsp;</span>
                                                                                                </td>
                                                                                                <td class=\"lovtd\">
                                                                                                        <button type=\"button\" class=\"btn btn-default\" style=\"margin: 0px !important;padding:0px 3px 2px 4px !important;\" onclick=\"delLatePymntFee('allLatePymntFeesRow__WWW123WWW');\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Delete Profile Factor\">
                                                                                                                        <img src=\"cmn_images/no.png\" style=\"height:15px; width:auto; position: relative; vertical-align: middle;\">
                                                                                                        </button>
                                                                                                </td>
                                                                                                <td style=\"display:none;\" id=\"allLatePymntFeesRow_WWW123WWW_Frqncy\"></td>
                                                                                                <td style=\"display:none;\" id=\"allLatePymntFeesRow_WWW123WWW_FrqncyNo\"></td>
                                                        </tr>");
                                                        ?>
                                                    <div class="<?php echo $colClassType1; ?>" style="padding:0px 1px 0px 3px !important;"> 
                                                        <?php if (($trnsStatus == "Incomplete" || $trnsStatus == "Withdrawn" || $trnsStatus == "Rejected") && $vwtypActn != "VIEW") { ?>
                                                            <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="insertNewRowBe4('allLatePymntFeesTable', 0, '<?php echo $nwRowHtml; ?>');" data-toggle="tooltip" data-placement="bottom" title="Add Profile Factor">
                                                                <img src="cmn_images/add1-64.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                            </button>
                                                        <?php } ?>
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
                                                            <input class="form-control" id="allLatePymntFeesSrchFor" type = "text" placeholder="Search For" value="<?php
                                                            echo trim(str_replace("%", " ", $srchFor));
                                                            ?>" onkeyup="enterKeyFuncAllLatePymntFees(event, '', '#allLatePymntFeesDetailInfo', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&subPgNo=7.22&vtypActn=<?php echo $vwtypActn; ?>&prdtID=<?php echo $pkID; ?>');">
                                                            <input id="allLatePymntFeesPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getAllLatePymntFees('clear', '#allLatePymntFeesDetailInfo', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&subPgNo=7.22&vtypActn=<?php echo $vwtypActn; ?>&prdtID=<?php echo $pkID; ?>');">
                                                                <span class="glyphicon glyphicon-remove"></span>
                                                            </label>
                                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getAllLatePymntFees('', '#allLatePymntFeesDetailInfo', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&subPgNo=7.22&vtypActn=<?php echo $vwtypActn; ?>&prdtID=<?php echo $pkID; ?>');">
                                                                <span class="glyphicon glyphicon-search"></span>
                                                            </label> 
                                                        </div>
                                                    </div>
                                                    <div class="<?php echo $colClassType2; ?>" style="padding:0px 3px 0px 3px !important;">
                                                        <div class="input-group">
                                                            <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                                            <!--<select data-placeholder="Select..." class="form-control chosen-select" id="allLatePymntFeesSrchIn">
                                                            <?php
                                                            $valslctdArry = array("");
                                                            $srchInsArrys = array("Fee Name");

                                                            for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                                if ($srchIn == $srchInsArrys[$z]) {
                                                                    $valslctdArry[$z] = "selected";
                                                                }
                                                                ?>
                                                                                                                        <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                                            <?php } ?>
                                                            </select>-->
                                                            <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                                            <select data-placeholder="Select..." class="form-control chosen-select" id="allLatePymntFeesDsplySze" style="min-width:70px !important;">                            
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
                                                                    <a class="rhopagination" href="javascript:getAllLatePymntFees('previous', '#allLatePymntFeesDetailInfo', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&subPgNo=7.22&vtypActn=<?php echo $vwtypActn; ?>&prdtID=<?php echo $pkID; ?>');" aria-label="Previous">
                                                                        <span aria-hidden="true">&laquo;</span>
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <a class="rhopagination" href="javascript:getAllLatePymntFees('next', '#allLatePymntFeesDetailInfo', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&subPgNo=7.22&vtypActn=<?php echo $vwtypActn; ?>&prdtID=<?php echo $pkID; ?>');" aria-label="Next">
                                                                        <span aria-hidden="true">&raquo;</span>
                                                                    </a>
                                                                </li>
                                                            </ul>
                                                        </nav>
                                                        <input type="hidden" class="form-control" aria-label="..." id="gnrlOrgID" name="gnrlOrgID" value="<?php echo $orgID; ?>">                                                    
                                                        <input type="hidden" class="form-control" aria-label="..." id="sbmtdRiskProfileID" name="sbmtdRiskProfileID" value="<?php echo $pkID; ?>">
                                                    </div>
                                                </div>
                                                <div class="row" style="padding:0px 15px 0px 15px !important">                  
                                                    <div class="col-md-12" style="padding:0px 3px 0px 3px !important">
                                                        <table class="table table-striped table-bordered table-responsive" id="allLatePymntFeesTable" cellspacing="0" width="100%" style="width:100%;min-width: 300px !important;">
                                                            <thead>
                                                                <tr>
                                                                    <th>No.</th>
                                                                    <th>Fee Name</th>
                                                                    <th>Target</th>
                                                                    <th>Amount Flat</th>
                                                                    <th>Amount Percent</th>
                                                                    <!--<th>Debit Account</th>-->
                                                                    <th>Revenue Account</th>
                                                                    <th>&nbsp;</th>
                                                                    <?php if (($trnsStatus == "Incomplete" || $trnsStatus == "Withdrawn" || $trnsStatus == "Rejected") && $vwtypActn != "VIEW") { ?>
                                                                    <th>&nbsp;</th>
                                                                    <?php } ?>
                                                                    <th style="display:none;">&nbsp;</th>
                                                                    <th style="display:none;">&nbsp;</th>
                                                                    <th <?php echo $shwHydNtlntySts; ?>>Status</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php
                                                                $cntr = 0;
                                                                while ($row2 = loc_db_fetch_array($result2)) {                                                                
                                                                    $cntr += 1;
                                                                    $ttlOptnsScore = 0;
                                                                    $row1ATZ0 = ""; $row1ATZ1 = ""; $row1ATZ2 = ""; $row1ATZ3 = ""; $row1ATZ4 = ""; $row1ATZ5 = "";
                                                                    $row1ATZ6 = -1; $row1ATZ7 = -1; $row1ATZ8 = ""; $row1ATZ9 = ""; $row1ATZ10 = "";
                                                                    if($row2[0] > 0 && $row2[10] === "Yes"){
                                                                        $result1ATZ = get_AllLoanPrdtLatePymntFeeATZ($row2[0]);
                                                                        while ($row1ATZ = loc_db_fetch_array($result1ATZ)) {
                                                                            $row1ATZ0 = $row1ATZ[0]; $row1ATZ1 = $row1ATZ[1]; $row1ATZ2 = $row1ATZ[2]; $row1ATZ3 = $row1ATZ[3];
                                                                            $row1ATZ4 = $row1ATZ[4]; $row1ATZ5 = $row1ATZ[5]; $row1ATZ6 = $row1ATZ[6]; $row1ATZ7 = $row1ATZ[7];
                                                                            $row1ATZ8 = $row1ATZ[8]; $row1ATZ9 = $row1ATZ[9]; $row1ATZ10 = $row1ATZ[10];
                                                                        }
                                                                    }
                                                                    ?>
                                                                    <tr id="allLatePymntFeesRow_<?php echo $cntr; ?>">                                    
                                                                        <td class="lovtd"><span><?php echo ($curIdx * $lmtSze) + ($cntr); ?></span></td>
                                                                        <td class="lovtd">
                                                                            <input type="hidden" class="form-control" aria-label="..." id="allLatePymntFeesRow<?php echo $cntr; ?>_LoanprdtLateFeeID" value="<?php echo $row2[0]; ?>" style="width:100% !important;"> 
                                                                            <input type="hidden" class="form-control" aria-label="..." id="allLatePymntFeesRow<?php echo $cntr; ?>_LoanPrdtID" value="<?php echo $row2[1]; ?>" style="width:100% !important;">                                                                         
                                                                            <?php if($vwtypActn === "VIEW" || $trnsStatus === "Initiated" || $trnsStatus === "Unauthorized"){ 
                                                                            echo dsplyTblData($row2[2], $row1ATZ2, $row2[0], $row2[10]);  
                                                                            } else { ?>
                                                                            <input <?php echo $mkReadOnly; ?> type="text" class="form-control" aria-label="..." id="allLatePymntFeesRow<?php echo $cntr; ?>_FeeNm" name="allLatePymntFeesRow<?php echo $cntr; ?>_FeeNm" value="<?php echo $row2[2]; ?>">                                                                        
                                                                            <?php } ?>
                                                                        </td>
                                                                        <td class="lovtd">  
                                                                            <?php if($vwtypActn === "VIEW" || $trnsStatus === "Initiated" || $trnsStatus === "Unauthorized"){ 
                                                                            echo dsplyTblData($row2[3], $row1ATZ3, $row2[0], $row2[10]);  
                                                                            } else { ?>
                                                                            <div class="input-group">
                                                                                <input type="text" class="form-control" aria-label="..." id="allLatePymntFeesRow<?php echo $cntr; ?>_Target" name="allLatePymntFeesRow<?php echo $cntr; ?>_Target" value="<?php echo $row2[3]; ?>" readonly="true">
                                                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'MCF Loan Late Fee Targets', 'gnrlOrgID', '', '', 'radio', true, '', 'allLatePymntFeesRow<?php echo $cntr; ?>_TargetID', 'allLatePymntFeesRow<?php echo $cntr; ?>_Target', 'clear', 1, '');">
                                                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                                                </label>
                                                                                <input type="hidden" class="form-control" aria-label="..." id="allLatePymntFeesRow<?php echo $cntr; ?>_TargetID" value="<?php echo $row2[3]; ?>" readonly="true"> 
                                                                            </div>
                                                                            <?php } ?>
                                                                        </td>
                                                                        <td class="lovtd"> 
                                                                            <?php if($vwtypActn === "VIEW" || $trnsStatus === "Initiated" || $trnsStatus === "Unauthorized"){ 
                                                                            echo dsplyTblData($row2[4], $row1ATZ4, $row2[0], $row2[10]);  
                                                                            } else { ?>
                                                                            <input <?php echo $mkReadOnly; ?> type="number" min="0" class="form-control rqrdFld" aria-label="..." id="allLatePymntFeesRow<?php echo $cntr; ?>_AmntFlat" name="allLatePymntFeesRow<?php echo $cntr; ?>_AmntFlat" value="<?php echo $row2[4]; ?>">
                                                                            <?php } ?>
                                                                        </td>                                            
                                                                        <td class="lovtd">  
                                                                            <?php if($vwtypActn === "VIEW" || $trnsStatus === "Initiated" || $trnsStatus === "Unauthorized"){ 
                                                                            echo dsplyTblData($row2[5], $row1ATZ5, $row2[0], $row2[10]);  
                                                                            } else { ?>
                                                                            <input <?php echo $mkReadOnly; ?> type="number" min="0" class="form-control rqrdFld" aria-label="..." id="allLatePymntFeesRow<?php echo $cntr; ?>_AmntPrcnt" name="allLatePymntFeesRow<?php echo $cntr; ?>_AmntPrcnt" value="<?php echo $row2[5]; ?>">                                                               
                                                                            <?php } ?>
                                                                        </td>
                                                                        <td style="display:none !important;" class="lovtd">  
                                                                            <?php if($vwtypActn === "VIEW" || $trnsStatus === "Initiated" || $trnsStatus === "Unauthorized"){ 
                                                                            echo dsplyTblData(getGnrlRecNm("accb.accb_chart_of_accnts", "accnt_id", "accnt_num || '.' || accnt_name", $row2[6]), getGnrlRecNm("accb.accb_chart_of_accnts", "accnt_id", "accnt_num || '.' || accnt_name", $row1ATZ6), $row2[0], $row2[10]);  
                                                                            } else { ?>
                                                                            <div class="input-group">
                                                                                <input type="text" class="form-control" aria-label="..." id="allLatePymntFeesRow<?php echo $cntr; ?>_DbtAcct" name="allLatePymntFeesRow<?php echo $cntr; ?>_DbtAcct" value="<?php echo getGnrlRecNm("accb.accb_chart_of_accnts", "accnt_id", "accnt_num || '.' || accnt_name", $row2[6]); ?>" readonly="true">
                                                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Accounts', 'gnrlOrgID', '', '', 'radio', true, '', 'allLatePymntFeesRow<?php echo $cntr; ?>_DbtAcctID', 'allPrcsnFeesRow<?php echo $cntr; ?>_DbtAcct', 'clear', 1, ' AND (SELECT is_prnt_accnt FROM accb.accb_chart_of_accnts x WHERE x.accnt_id = tbl1.a::bigint) = \'0\' ');">
                                                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                                                </label>
                                                                                <input type="hidden" class="form-control" aria-label="..." id="allLatePymntFeesRow<?php echo $cntr; ?>_DbtAcctID" value="<?php echo $row2[6]; ?>" readonly="true"> 
                                                                            </div>
                                                                            <?php } ?>
                                                                        </td>
                                                                        <td class="lovtd"> 
                                                                            <?php if($vwtypActn === "VIEW" || $trnsStatus === "Initiated" || $trnsStatus === "Unauthorized"){ 
                                                                            echo dsplyTblData(getGnrlRecNm("accb.accb_chart_of_accnts", "accnt_id", "accnt_num || '.' || accnt_name", $row2[7]), getGnrlRecNm("accb.accb_chart_of_accnts", "accnt_id", "accnt_num || '.' || accnt_name", $row1ATZ7), $row2[0], $row2[10]);  
                                                                            } else { ?>
                                                                            <div class="input-group">
                                                                                <input type="text" class="form-control" aria-label="..." id="allLatePymntFeesRow<?php echo $cntr; ?>_CrdtAcct" name="allLatePymntFeesRow<?php echo $cntr; ?>_CrdtAcct" value="<?php echo getGnrlRecNm("accb.accb_chart_of_accnts", "accnt_id", "accnt_num || '.' || accnt_name", $row2[7]); ?>" readonly="true">
                                                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Accounts', 'gnrlOrgID', '', '', 'radio', true, '', 'allLatePymntFeesRow<?php echo $cntr; ?>_CrdtAcctID', 'allPrcsnFeesRow<?php echo $cntr; ?>_CrdtAcct', 'clear', 1, ' AND (SELECT is_prnt_accnt FROM accb.accb_chart_of_accnts x WHERE x.accnt_id = tbl1.a::bigint) = \'0\' ');">
                                                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                                                </label>
                                                                                <input type="hidden" class="form-control" aria-label="..." id="allLatePymntFeesRow<?php echo $cntr; ?>_CrdtAcctID" value="<?php echo $row2[7]; ?>" readonly="true"> 
                                                                            </div>
                                                                            <?php } ?>
                                                                        </td>
                                                                        <td class="lovtd">
                                                                            <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="getCrdtPrdtLateFeesFrqncyNRmksForm('allLatePymntFeesRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Add Profile Options">
                                                                                <img src="cmn_images/add1-64.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                                            </button>
                                                                        </td>
                                                                        <?php if (($trnsStatus == "Incomplete" || $trnsStatus == "Withdrawn" || $trnsStatus == "Rejected") && $vwtypActn != "VIEW") { ?>
                                                                        <td class="lovtd">
                                                                            <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delLatePymntFee('allLatePymntFeesRow_<?php echo $cntr; ?>','<?php echo $row2[10]; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Risk Profile">
                                                                                <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                                            </button>
                                                                        </td>
                                                                        <?php } ?>
                                                                        <td style="display:none;" id="allLatePymntFeesRow<?php echo $cntr; ?>_Frqncy"><?php echo $row2[8]; ?></td>
                                                                        <td style="display:none;" id="allLatePymntFeesRow<?php echo $cntr; ?>_FrqncyNo"><?php echo $row2[9]; ?></td>
                                                                        <td <?php echo $shwHydNtlntySts; ?>>
                                                                            <?php 
                                                                            if($row2[0] < 0){
                                                                                echo "<span style='color:red;'><b>Deleted</b></span>";
                                                                            } else  {
                                                                               if($row2[10] === "No"){
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
                                </div>                                  
                            </form>                         
                        </div>                
                    </div>    
                    <div class="row" style="margin: 0px 0px 10px 0px !important;">
                        <div class="col-md-12" style="padding:0px 1px 5px 1px !important;">
                            <div style="float:left;"> 
                                <button type="button" class="btn btn-default btn-sm" style="height:30px;" id="myVmsTrnsStatusBtn">
                                        <span style="font-weight:bold;">Status: </span><span style="color:<?php echo $rqstatusColor; ?>;font-weight: bold;"><?php echo $trnsStatus; ?></span>
                                </button>
                                <?php  if($vwtypActn == "EDIT" && $pkID > 0) { ?>
                                <button type="button" class="btn btn-default btn-sm" style="height:30px;" onclick="getProductsForm('myFormsModalLg', 'myFormsModalBodyLg', 'myFormsModalTitleLg', 'Edit Credit Product', 12, <?php echo $subPgNo; ?>, 0, 'EDIT', <?php echo $pkID; ?> , '', 'indCustTableRow1');" data-toggle="tooltip" title="Reload Transaction">
                                        <img src="cmn_images/refresh.bmp" style="padding-right: 2px; height:17px; width:auto; position: relative; vertical-align: middle;">
                                </button>
                                <?php } ?>
                                <?php  if($vwtypActn != "ADD") { ?>
                                <button type="button" class="btn btn-default" style="height:30px;" onclick="getOneMcfDocsForm_Gnrl(<?php echo $pkID; ?>,'<?php echo $trnsType; ?>', 140, 'CREDIT PRODUCT');" data-toggle="tooltip" data-placement="bottom" title = "Attached Documents">
                                    <img src="cmn_images/adjunto.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                </button>
                                <?php } ?>
                                <button type="button" class="btn btn-default" style="height:30px;" onclick="getProductsForm('myFormsModalLg', 'myFormsModalBodyLg', 'myFormsModalTitleLg', 'Edit Credit Product', 12, <?php echo $subPgNo; ?>, 0, 'EDIT', -1 , '', 'indCustTableRow1');" data-toggle="tooltip" data-placement="bottom" title = "Attached Documents">
                                    <img src="cmn_images/undo_256.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                </button>
                            </div>
                            <div class="" style="float:right;">
                                <?php if (($trnsStatus == "Incomplete" || $trnsStatus == "Withdrawn" || $trnsStatus == "Rejected") && $vwtypActn != "VIEW") { ?> 
                                <button type="button" class="btn btn-default btn-sm" style="" onclick="saveCreditProduct(0);">
                                    <img src="cmn_images/FloppyDisk.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">
                                    SAVE
                                </button>
                                <button type="button" class="btn btn-default btn-sm" style="" onclick="saveCreditProduct(1);">
                                    <img src="cmn_images/Emailcon.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">
                                    SUBMIT
                                </button>
                                 <?php } ?>
                            </div>
                        </div>
                    </div>                         
                </div>
                <?php
                //}
            }
        } 
        else if ($vwtyp == "1") {
            /* ADD FREQUENCY AND REMARKS */
            $rowID = isset($_POST['sbmtdLoanprdtLateFeeID']) ? cleanInputData($_POST['sbmtdLoanprdtLateFeeID']) : -1;
            $sbmtdPrdtID = isset($_POST['sbmtdPrdtID']) ? cleanInputData($_POST['sbmtdPrdtID']) : -1;
            $trnsStatus = getGnrlRecNm("mcf.mcf_prdt_loans", "loan_product_id", "status", $sbmtdPrdtID);
            //echo" Status =".$sbmtdPrdtID;
            $loanPrdtID = -1;
            $feeNm = "";
            $frqncy = "";
            $frqncyNo = 0;            
            $remarks = "";
            $mkReadOnly = "";
            $mkReadOnlyDsbld = "";
            $mkDsplyNone = "display:block !important";
            $clsFrqncy = "col-md-8";
            $clsFrqncyNo = "col-md-4";
            
            $result = getLoanPrdtLatePymntFeeExtraData($rowID);
            while($row = loc_db_fetch_array($result)){
                //$corpDirectorID = $row[0];
                $loanPrdtID = $row[1];
                $feeNm =  $row[2];
                $frqncy = $row[3];
                $frqncyNo = $row[4];
                $remarks = $row[5];
                    if($vwtypActn == "VIEW" || ($vwtypActn == "EDIT" && ($trnsStatus == "Initiated" || $trnsStatus == "Approved" || $trnsStatus == "Unauthorized" || $trnsStatus == "Authorized"))){
                    $mkReadOnly = "readonly=\"readonly\"";
                    $mkReadOnlyDsbld = "disabled=\"true\"";                    
                }
                
                if($frqncy == "Repayment Date"){
                    $mkDsplyNone = "display:none !important";
                    $clsFrqncy = "col-md-12";
                    $clsFrqncyNo = "col-md-0";
                }
            }            
            
            ?>
            <form class="form-horizontal" id="allLatePymntFeesFrqncyNRmksForm" style="padding:5px 20px 5px 20px;">
                <div class="row">
                    <input class="form-control" size="16" type="hidden" id="sbmtdLoanprdtLateFeeID" value="<?php echo $rowID; ?>" readonly="">                    
                    <div class="form-group form-group-sm">
                        <label for="frqncyNo" class="control-label col-md-4">Charge Penalty On:</label>
                        <div  class="col-md-8">
                            <div class="input-group col-md-12">
                                <div id="frqncyDiv" class="<?php echo $clsFrqncy; ?>" style="padding-left:0px !important; padding-right: 0px !important; ">
                                    <select <?php echo $mkReadOnlyDsbld; ?> class="form-control" id="frqncy"  onchange="onChqLateFeeFrqncy();">
                                        <?php
                                        $sltdRpyPrd = "";
                                        $sltdDay = "";
                                        $sltdWeek = "";
                                        $sltdMonth= "";
                                        $sltdYear= "";
                                        if ($frqncy == "Repayment Date") {
                                            $sltdRpyPrd = "selected";
                                        } else if ($frqncy == "Day") {
                                            $sltdDay = "selected";
                                        } else if ($frqncy == "Week") {
                                            $sltdWeek = "selected";
                                        } else if ($frqncy == "Month") {
                                            $sltdMonth = "selected";
                                        } else if ($frqncy == "Year") {
                                            $sltdYear = "selected";
                                        }
                                        ?>
                                        <option value="Repayment Date" <?php echo $sltdRpyPrd; ?>>Repayment Date</option>
                                        <!--<option value="Day" <?php echo $sltdDay; ?>>Day</option>
                                        <option value="Week" <?php echo $sltdWeek; ?>>Week</option>
                                        <option value="Month" <?php echo $sltdMonth; ?>>Month</option>
                                        <option value="Year" <?php echo $sltdYear; ?>>Year</option>-->
                                    </select>
                                </div>
                                <div id="frqncyNoDiv"  class="<?php echo $clsFrqncyNo; ?>" style="padding-left:0px !important; <?php echo $mkDsplyNone; ?>">
                                    <input <?php echo $mkReadOnly; ?> style="margin-left:15px !important;" class="form-control rqrdFld" id="frqncyNo" type = "number" min="0" placeholder="" value="<?php echo $frqncyNo; ?>"/>
                                </div>                                
                            </div>
                        </div>                                                            
                    </div>
                    <div class="form-group form-group-sm">
                        <label for="remarks" class="control-label col-md-4">Remarks:</label>
                        <div class="col-md-8">
                            <textarea <?php echo $mkReadOnly; ?> class="form-control" id="remarks" cols="2" placeholder="Remarks" rows="2"><?php echo $remarks; ?></textarea>
                        </div>
                    </div>
                </div>
                <div class="row" style="float:right;padding-right: 1px;">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <?php if (($trnsStatus == "Incomplete" || $trnsStatus == "Withdrawn" || $trnsStatus == "Rejected") && $vwtypActn != "VIEW") { ?>
                        <button type="button" class="btn btn-primary" onclick="saveCrdtPrdtFrqncyNRemarks('myFormsModalx', <?php echo $rowID; ?>);">Save Changes</button>
                    <?php } ?>
                </div>
            </form>
            <?php
        } else if ($vwtyp == "5") {
            /* ADD COMMISSION */
//$rowID = isset($_POST['rowID']) ? cleanInputData($_POST['rowID']) : -1;
            ?>
            <form class="form-horizontal" id="svngsWtdwlCmsnForm" style="padding:5px 20px 5px 20px;">
                <div class="row">
                    <div class="form-group form-group-sm">
                        <label for="lowRange" class="control-label col-md-4">Low Range:</label>
                        <div class="col-md-8">
                            <input class="form-control" id="lowRange" type = "number" min="0" placeholder="Low Range" value=""/>
                            <!--row ID-->
                            <input class="form-control" size="16" type="hidden" id="rowID" value="<?php echo $rowID; ?>" readonly="">
                            <!--table rowElementID-->
                            <input class="form-control" size="16" type="hidden" id="tblRowElementID" value="" readonly="">
                        </div>
                    </div> 
                    <div class="form-group form-group-sm">
                        <label for="highRange" class="control-label col-md-4">High Range:</label>
                        <div class="col-md-8">
                            <input class="form-control" id="highRange" type = "number" min="0" placeholder="High Range" value=""/>
                        </div>
                    </div>
                    <div class="form-group form-group-sm">
                        <label for="amountFlat" class="control-label col-md-4">Amount Flat:</label>
                        <div class="col-md-8">
                            <input class="form-control" id="amountFlat" type = "number" min="0" placeholder="Amount Flat" value=""/>
                        </div>
                    </div>
                    <div class="form-group form-group-sm">
                        <label for="amountPrcnt" class="control-label col-md-4">Amount Percent:</label>
                        <div class="col-md-8">
                            <input class="form-control" id="amountPrcnt" type = "number" min="0" placeholder="Amount Percent" value=""/>
                        </div>
                    </div>
                    <div class="form-group form-group-sm">
                        <label for="remarks" class="control-label col-md-4">Remarks:</label>
                        <div class="col-md-8">
                            <textarea class="form-control" id="remarks" cols="2" placeholder="Remarks" rows="2"></textarea>
                        </div>
                    </div>
                </div>
                <div class="row" style="float:right;padding-right: 1px;">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="saveSvngsWtdwlCmsnForm('myLovModal', '<?php echo $rowID; ?>');">Save Changes</button>
                </div>
            </form>
            <?php
        }
    } 
    else if ($subPgNo == 7.21){ 
            $trnsStatus = "Incomplete";
            $mkReadOnly = "";
            $mkReadOnlyDsbld = "";
        
            $error = "";
            $searchAll = true;

            $srchFor = isset($_POST['searchfor']) ? cleanInputData($_POST['searchfor']) : '';
            $srchIn = isset($_POST['searchin']) ? cleanInputData($_POST['searchin']) : 'Fee Name';
            $pageNo = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
            $lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 10;
            $sortBy = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : "Date Added DESC";
            if (strpos($srchFor, "%") === FALSE) {
                $srchFor = "%" . str_replace(" ", "%", $srchFor) . "%";
                $srchFor = str_replace("%%", "%", $srchFor);
            }     
            
            $canAddRiskProfile = test_prmssns($dfltPrvldgs[1], $mdlNm);
            $canEdtRiskProfile = test_prmssns($dfltPrvldgs[1], $mdlNm);
            $canDelRiskProfile = test_prmssns($dfltPrvldgs[1], $mdlNm);
            
            $rvsnTtl = isset($_POST['rvsnTtl']) ? cleanInputData($_POST['rvsnTtl']) : 0;
                $tblNm1 = "mcf.mcf_prdt_loans";

                $cnt = getCrdtPrdtDataChngPndngCount($pkID);
                if($cnt > 0){
                    $tblNm1 = "mcf.mcf_prdt_loans_hstrc";        
                }  

            $result = get_CreditPrdtDet($pkID, $tblNm1);
            while ($row = loc_db_fetch_array($result)) {  
                $trnsStatus = $row[48];

                if($vwtypActn == "VIEW" || ($vwtypActn == "EDIT" && ($trnsStatus == "Initiated" || $trnsStatus == "Approved" || $trnsStatus == "Unauthorized" || $trnsStatus == "Authorized"))){
                    $mkReadOnly = "readonly=\"readonly\"";
                    $mkReadOnlyDsbld = "disabled=\"true\"";
                } 
            }            
        
    ?> 

        <div class="row" id="allPrcsnFeesDetailInfo" style="padding:0px 15px 0px 15px !important">
            <?php
            $vwtyp = 1;
            
            
            if ($pkID > 0) {
                $total = get_AllLoanPrdtPrcsnFeeTtl($srchFor, $srchIn, $pkID);
                //$total = get_AllBanksTtl($srchFor, $srchIn, $pkID);
                if ($pageNo > ceil($total / $lmtSze)) {
                    $pageNo = 1;
                } else if ($pageNo < 1) {
                    $pageNo = ceil($total / $lmtSze);
                }
                $curIdx = $pageNo - 1;
                $result2 = get_AllLoanPrdtPrcsnFee($srchFor, $srchIn, $curIdx, $lmtSze, $pkID);
                ?>
                <div class="row" style="padding:0px 15px 0px 15px !important">
                    <legend class="basic_person_lg1" style="color: #003245">PROCESSING FEES</legend>
                    <?php
                    if ($canEdtRiskProfile === true) {
                        $colClassType1 = "col-lg-2";
                        $colClassType2 = "col-lg-3";
                        $colClassType3 = "col-lg-4";
                        $nwRowHtml = urlencode("<tr id=\"allPrcsnFeesRow__WWW123WWW\">"
                                        . "<td class=\"lovtd\"><span class=\"normaltd\">New</span></td>
                                            <td class=\"lovtd\">
                                                <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"allPrcsnFeesRow_WWW123WWW_LoanprdtPrcssnFeeID\" value=\"-1\" style=\"width:100% !important;\"> 
                                                <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"allPrcsnFeesRow_WWW123WWW_LoanPrdtID\" value=\"<?php echo sbmtdPrdtID; ?>\" style=\"width:100% !important;\">                                                                         
                                                <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"allPrcsnFeesRow_WWW123WWW_FeeNm\" name=\"allPrcsnFeesRow_WWW123WWW_FeeNm\" value=\"\">                                                                        
                                            </td>
                                            <td class=\"lovtd\">
                                                <div class=\"input-group\">
                                                    <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"allPrcsnFeesRow_WWW123WWW_FeeType\" name=\"allPrcsnFeesRow_WWW123WWW_FeeType\" value=\"\" readonly=\"true\">
                                                    <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'MCF Loan Processing Fee Types', 'gnrlOrgID', '', '', 'radio', true, '', 'allPrcsnFeesRow_WWW123WWW_FeeTypeID', 'allPrcsnFeesRow_WWW123WWW_FeeType', 'clear', 1, '');\">
                                                            <span class=\"glyphicon glyphicon-th-list\"></span>
                                                    </label>
                                                    <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"allPrcsnFeesRow_WWW123WWW_FeeTypeID\" value=\"-1\" style=\"width:100% !important;\"> 
                                                </div>
                                            </td>                                             
                                            <td class=\"lovtd\">
                                                    <input type=\"number\" min=\"0\" class=\"form-control rqrdFld\" aria-label=\"...\" id=\"allPrcsnFeesRow_WWW123WWW_AmntFlat\" name=\"allPrcsnFeesRow_WWW123WWW_AmntFlat\" value=\"\">                                                               
                                            </td>
                                            <td class=\"lovtd\">
                                                    <input type=\"number\" min=\"0\" class=\"form-control rqrdFld\" aria-label=\"...\" id=\"allPrcsnFeesRow_WWW123WWW_AmntPrcnt\" name=\"allPrcsnFeesRow_WWW123WWW_AmntPrcnt\" value=\"\">                                                               
                                            </td>
                                            <td class=\"lovtd\">                                                                         
                                                <div class=\"input-group\">
                                                    <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"allPrcsnFeesRow_WWW123WWW_RvnAcct\" name=\"allPrcsnFeesRow_WWW123WWW_RvnAcct\" value=\"\" readonly=\"true\">
                                                    <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Accounts', 'gnrlOrgID', '', '', 'radio', true, '', 'allPrcsnFeesRow_WWW123WWW_RvnAcctID', 'allPrcsnFeesRow_WWW123WWW_RvnAcct', 'clear', 1, ' AND (SELECT is_prnt_accnt FROM accb.accb_chart_of_accnts x WHERE x.accnt_id = tbl1.a::bigint) = \'0\' ');\">
                                                         <span class=\"glyphicon glyphicon-th-list\"></span>
                                                    </label>
                                                    <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"allPrcsnFeesRow_WWW123WWW_RvnAcctID\" value=\"-1\" readonly=\"true\"> 
                                                </div>                                                                        
                                            </td>
                                            <td class=\"lovtd\">  
                                                    <input type=\"text\" min=\"0\" class=\"form-control\" aria-label=\"...\" id=\"allPrcsnFeesRow_WWW123WWW_Rmrks\" name=\"allPrcsnFeesRow_WWW123WWW_Rmrks\" value=\"\">                                                               
                                            </td>
                                            <td class=\"lovtd\">
                                                <button type=\"button\" class=\"btn btn-default\" style=\"margin: 0px !important;padding:0px 3px 2px 4px !important;\" onclick=\"delPrcsnFee('allPrcsnFeesRow__WWW123WWW');\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Delete Profile Factor\">
                                                        <img src=\"cmn_images/no.png\" style=\"height:15px; width:auto; position: relative; vertical-align: middle;\">
                                                </button>
                                            </td>
                        </tr>");
                        ?>
                        <div class="<?php echo $colClassType1; ?>" style="padding:0px 1px 0px 3px !important;">
                            <?php if (($trnsStatus == "Incomplete" || $trnsStatus == "Withdrawn" || $trnsStatus == "Rejected") && $vwtypActn != "VIEW") { ?>
                            <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="insertNewRowBe4('allPrcsnFeesTable', 0, '<?php echo $nwRowHtml; ?>');" data-toggle="tooltip" data-placement="bottom" title="Add Profile Factor">
                                <img src="cmn_images/add1-64.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                            </button>
                            <?php } ?>
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
                            <input class="form-control" id="allPrcsnFeesSrchFor" type = "text" placeholder="Search For" value="<?php
                            echo trim(str_replace("%", " ", $srchFor));
                            ?>" onkeyup="enterKeyFuncAllPrcsnFees(event, '', '#allPrcsnFeesDetailInfo', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&subPgNo=<?php echo $subPgNo; ?>&vtypActn=<?php echo $vwtypActn; ?>&prdtID=<?php echo $pkID; ?>');">
                            <input id="allPrcsnFeesPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                            <label class="btn btn-primary btn-file input-group-addon" onclick="getAllPrcsnFees('clear', '#allPrcsnFeesDetailInfo', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&subPgNo=<?php echo $subPgNo; ?>&vtypActn=<?php echo $vwtypActn; ?>&prdtID=<?php echo $pkID; ?>');">
                                <span class="glyphicon glyphicon-remove"></span>
                            </label>
                            <label class="btn btn-primary btn-file input-group-addon" onclick="getAllPrcsnFees('', '#allPrcsnFeesDetailInfo', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&subPgNo=<?php echo $subPgNo; ?>&vtypActn=<?php echo $vwtypActn; ?>&prdtID=<?php echo $pkID; ?>');">
                                <span class="glyphicon glyphicon-search"></span>
                            </label> 
                        </div>
                    </div>
                    <div class="<?php echo $colClassType2; ?>" style="padding:0px 3px 0px 3px !important;">
                        <div class="input-group">
                            <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                            <!--<select data-placeholder="Select..." class="form-control chosen-select" id="allPrcsnFeesSrchIn">
                            <?php
                            $valslctdArry = array("");
                            $srchInsArrys = array("Fee Name");

                            for ($z = 0; $z < count($srchInsArrys); $z++) {
                                if ($srchIn == $srchInsArrys[$z]) {
                                    $valslctdArry[$z] = "selected";
                                }
                                ?>
                                                                                        <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                            <?php } ?>
                            </select>-->
                            <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                            <select data-placeholder="Select..." class="form-control chosen-select" id="allPrcsnFeesDsplySze" style="min-width:70px !important;">                            
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
                                    <a class="rhopagination" href="javascript:getAllPrcsnFees('previous', '#allPrcsnFeesDetailInfo', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&subPgNo=<?php echo $subPgNo; ?>&vtypActn=<?php echo $vwtypActn; ?>&prdtID=<?php echo $pkID; ?>');" aria-label="Previous">
                                        <span aria-hidden="true">&laquo;</span>
                                    </a>
                                </li>
                                <li>
                                    <a class="rhopagination" href="javascript:getAllPrcsnFees('next', '#allPrcsnFeesDetailInfo', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&subPgNo=<?php echo $subPgNo; ?>&vtypActn=<?php echo $vwtypActn; ?>&prdtID=<?php echo $pkID; ?>');" aria-label="Next">
                                        <span aria-hidden="true">&raquo;</span>
                                    </a>
                                </li>
                            </ul>
                        </nav>
                        <input type="hidden" class="form-control" aria-label="..." id="gnrlOrgID" name="gnrlOrgID" value="<?php echo $orgID; ?>">                                                    
                        <input type="hidden" class="form-control" aria-label="..." id="sbmtdRiskProfileID" name="sbmtdRiskProfileID" value="<?php echo $pkID; ?>">
                    </div>
                </div>
                <div class="row" style="padding:0px 15px 0px 15px !important">                  
                    <div class="col-md-12" style="padding:0px 3px 0px 3px !important">
                        <table class="table table-striped table-bordered table-responsive" id="allPrcsnFeesTable" cellspacing="0" width="100%" style="width:100%;min-width: 300px !important;">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Fee Name</th>
                                    <th>Fee Type</th>
                                    <th>Amount Flat</th>
                                    <th>Amount Percent</th>
                                    <th>Revenue Account</th>
                                    <th>Remarks</th>
                                    <th>&nbsp;</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $cntr = 0;
                                while ($row2 = loc_db_fetch_array($result2)) {                                                                
                                    $cntr += 1;
                                    $ttlOptnsScore = 0
                                    ?>
                                    <tr id="allPrcsnFeesRow_<?php echo $cntr; ?>">                                    
                                        <td class="lovtd"><span><?php echo ($curIdx * $lmtSze) + ($cntr); ?></span></td>
                                        <td class="lovtd">
                                            <input type="hidden" class="form-control" aria-label="..." id="allPrcsnFeesRow<?php echo $cntr; ?>_LoanprdtPrcssnFeeID" value="<?php echo $row2[0]; ?>" style="width:100% !important;"> 
                                            <input type="hidden" class="form-control" aria-label="..." id="allPrcsnFeesRow<?php echo $cntr; ?>_LoanPrdtID" value="<?php echo $row2[1]; ?>" style="width:100% !important;">                                                                         
                                            <input <?php echo $mkReadOnly; ?> type="text" class="form-control" aria-label="..." id="allPrcsnFeesRow<?php echo $cntr; ?>_FeeNm" name="allPrcsnFeesRow<?php echo $cntr; ?>_FeeNm" value="<?php echo $row2[2]; ?>">                                                                        
                                        </td>
                                        <td class="lovtd">                                                                         
                                            <div class="input-group">
                                                <input type="text" class="form-control" aria-label="..." id="allPrcsnFeesRow<?php echo $cntr; ?>_FeeType" name="allPrcsnFeesRow<?php echo $cntr; ?>_FeeType" value="<?php echo $row2[3]; ?>" readonly="true">
                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'MCF Loan Processing Fee Types', 'gnrlOrgID', '', '', 'radio', true, '', 'allPrcsnFeesRow<?php echo $cntr; ?>_FeeTypeID', 'allPrcsnFeesRow<?php echo $cntr; ?>_FeeType', 'clear', 1, '');">
                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                </label>
                                                <input type="hidden" class="form-control" aria-label="..." id="allPrcsnFeesRow<?php echo $cntr; ?>_FeeTypeID" value="<?php echo $row2[3]; ?>" readonly="true"> 
                                            </div>                                                                        
                                        </td>
                                        <td class="lovtd"> 
                                            <input <?php echo $mkReadOnly; ?> type="number" min="0" class="form-control rqrdFld" aria-label="..." id="allPrcsnFeesRow<?php echo $cntr; ?>_AmntFlat" name="allPrcsnFeesRow<?php echo $cntr; ?>_AmntFlat" value="<?php echo $row2[4]; ?>">
                                        </td>                                            
                                        <td class="lovtd">  
                                            <input <?php echo $mkReadOnly; ?> type="number" min="0" class="form-control rqrdFld" aria-label="..." id="allPrcsnFeesRow<?php echo $cntr; ?>_AmntPrcnt" name="allPrcsnFeesRow<?php echo $cntr; ?>_AmntPrcnt" value="<?php echo $row2[5]; ?>">                                                               
                                        </td>
                                        <td class="lovtd">                                                                         
                                            <div class="input-group">
                                                <input type="text" class="form-control" aria-label="..." id="allPrcsnFeesRow<?php echo $cntr; ?>_RvnAcct" name="allPrcsnFeesRow<?php echo $cntr; ?>_RvnAcct" value="<?php echo getGnrlRecNm("accb.accb_chart_of_accnts", "accnt_id", "accnt_num || '.' || accnt_name", $row2[6]); ?>" readonly="true">
                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Accounts', 'gnrlOrgID', '', '', 'radio', true, '', 'allPrcsnFeesRow<?php echo $cntr; ?>_RvnAcctID', 'allPrcsnFeesRow<?php echo $cntr; ?>_RvnAcct', 'clear', 1, ' AND (SELECT is_prnt_accnt FROM accb.accb_chart_of_accnts x WHERE x.accnt_id = tbl1.a::bigint) = \'0\' ');">
                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                </label>
                                                <input type="hidden" class="form-control" aria-label="..." id="allPrcsnFeesRow<?php echo $cntr; ?>_RvnAcctID" value="<?php echo $row2[6]; ?>" readonly="true"> 
                                            </div>                                                                        
                                        </td>
                                        <td class="lovtd">  
                                            <input <?php echo $mkReadOnly; ?> type="text" min="0" class="form-control" aria-label="..." id="allPrcsnFeesRow<?php echo $cntr; ?>_Rmrks" name="allPrcsnFeesRow<?php echo $cntr; ?>_Rmrks" value="<?php echo $row2[7]; ?>">                                                               
                                        </td>
                                        <td class="lovtd">
                                            <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delPrcsnFee('allPrcsnFeesRow_<?php echo $cntr; ?>','<?php echo $row2[8]; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Risk Profile">
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
    } else if ($subPgNo == 7.22){
            $trnsStatus = "Incomplete";
            $mkReadOnly = "";
            $mkReadOnlyDsbld = "";
        
            $error = "";
            $searchAll = true;

            $srchFor = isset($_POST['searchfor']) ? cleanInputData($_POST['searchfor']) : '';
            $srchIn = isset($_POST['searchin']) ? cleanInputData($_POST['searchin']) : 'Fee Name';
            $pageNo = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
            $lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 10;
            $sortBy = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : "Date Added DESC";
            if (strpos($srchFor, "%") === FALSE) {
                $srchFor = "%" . str_replace(" ", "%", $srchFor) . "%";
                $srchFor = str_replace("%%", "%", $srchFor);
            }     
            
            $canAddRiskProfile = test_prmssns($dfltPrvldgs[1], $mdlNm);
            $canEdtRiskProfile = test_prmssns($dfltPrvldgs[1], $mdlNm);
            $canDelRiskProfile = test_prmssns($dfltPrvldgs[1], $mdlNm);
            
                $tblNm1 = "mcf.mcf_prdt_loans";

                $cnt = getCrdtPrdtDataChngPndngCount($pkID);
                if($cnt > 0){
                    $tblNm1 = "mcf.mcf_prdt_loans_hstrc";        
                }              

            $result = get_CreditPrdtDet($pkID, $tblNm1);
            while ($row = loc_db_fetch_array($result)) {  
                $trnsStatus = $row[48];

                if($vwtypActn == "VIEW" || ($vwtypActn == "EDIT" && ($trnsStatus == "Initiated" || $trnsStatus == "Approved" || $trnsStatus == "Unauthorized" || $trnsStatus == "Authorized"))){
                    $mkReadOnly = "readonly=\"readonly\"";
                    $mkReadOnlyDsbld = "disabled=\"true\"";
                } 
            }          
        ?>
        <div class="row" id="allLatePymntFeesDetailInfo" style="padding:0px 15px 0px 15px !important">
            <?php
            /* &vtyp=<?php echo $vwtyp; ?> */
            $vwtyp = 1;
            if ($pkID > 0) {
                $total = get_AllLoanPrdtLatePymntFeeTtl($srchFor, $srchIn, $pkID);
                //$total = get_AllBanksTtl($srchFor, $srchIn, $pkID);
                if ($pageNo > ceil($total / $lmtSze)) {
                    $pageNo = 1;
                } else if ($pageNo < 1) {
                    $pageNo = ceil($total / $lmtSze);
                }
                $curIdx = $pageNo - 1;
                $result2 = get_AllLoanPrdtLatePymntFee($srchFor, $srchIn, $curIdx, $lmtSze, $pkID);
                ?>
                <div class="row" style="padding:0px 15px 0px 15px !important">
                    <legend class="basic_person_lg1" style="color: #003245">LATE PAYMENT FEES</legend>
                    <?php
                    if ($canEdtRiskProfile === true) {
                        $colClassType1 = "col-lg-2";
                        $colClassType2 = "col-lg-3";
                        $colClassType3 = "col-lg-4";
                        $nwRowHtml = urlencode("<tr id=\"allLatePymntFeesRow__WWW123WWW\">"
                                                        . "<td class=\"lovtd\"><span class=\"normaltd\">New</span></td>
                                                                <td class=\"lovtd\">
                                                                        <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"allLatePymntFeesRow_WWW123WWW_LoanprdtLateFeeID\" value=\"-1\" style=\"width:100% !important;\"> 
                                                                        <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"allLatePymntFeesRow_WWW123WWW_LoanPrdtID\" value\"\" style=\"width:100% !important;\">                                                                         
                                                                        <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"allLatePymntFeesRow_WWW123WWW_FeeNm\" name=\"allLatePymntFeesRow_WWW123WWW_FeeNm\" value=\"\">                                                                        
                                                                </td>
                                                                <td class=\"lovtd\">
                                                                    <div class=\"input-group\">
                                                                        <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"allLatePymntFeesRow_WWW123WWW_Target\" name=\"allLatePymntFeesRow_WWW123WWW_Target\" value=\"\" readonly=\"true\">
                                                                        <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'MCF Loan Late Fee Targets', 'gnrlOrgID', '', '', 'radio', true, '', 'allLatePymntFeesRow_WWW123WWW_TargetID', 'allLatePymntFeesRow_WWW123WWW_Target', 'clear', 1, '');\">
                                                                             <span class=\"glyphicon glyphicon-th-list\"></span>
                                                                        </label>
                                                                        <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"allLatePymntFeesRow_WWW123WWW_TargetID\" value=\"\" style=\"width:100% !important;\"> 
                                                                    </div>
                                                                </td>                                             
                                                                <td class=\"lovtd\">
                                                                                <input type=\"number\" min=\"0\" class=\"form-control rqrdFld\" aria-label=\"...\" id=\"allLatePymntFeesRow_WWW123WWW_AmntFlat\" name=\"allLatePymntFeesRow_WWW123WWW_AmntFlat\" value=\"\">                                                               
                                                                </td>
                                                                <td class=\"lovtd\">
                                                                                <input type=\"number\" min=\"0\" class=\"form-control rqrdFld\" aria-label=\"...\" id=\"allLatePymntFeesRow_WWW123WWW_AmntPrcnt\" name=\"allLatePymntFeesRow_WWW123WWW_AmntPrcnt\" value=\"\">                                                               
                                                                </td>
                                                                <td style=\"display:none !important;\" class=\"lovtd\">                                                                         
                                                                        <div class=\"input-group\">
                                                                                <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"allLatePymntFeesRow_WWW123WWW_DbtAcct\" name=\"allLatePymntFeesRow_WWW123WWW_DbtAcct\" value=\"\" readonly=\"true\">
                                                                                <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Accounts', 'gnrlOrgID', '', '', 'radio', true, '', 'allLatePymntFeesRow_WWW123WWW_DbtAcctID', 'allLatePymntFeesRow_WWW123WWW_DbtAcct', 'clear', 1, ' AND (SELECT is_prnt_accnt FROM accb.accb_chart_of_accnts x WHERE x.accnt_id = tbl1.a::bigint) = \'0\' ');\">
                                                                                         <span class=\"glyphicon glyphicon-th-list\"></span>
                                                                                </label>
                                                                                <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"allLatePymntFeesRow_WWW123WWW_DbtAcctID\" value=\"-1\" readonly=\"true\"> 
                                                                        </div>                                                                        
                                                                </td>
                                                                <td class=\"lovtd\">                                                                         
                                                                        <div class=\"input-group\">
                                                                                <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"allLatePymntFeesRow_WWW123WWW_CrdtAcct\" name=\"allLatePymntFeesRow_WWW123WWW_CrdtAcct\" value=\"\" readonly=\"true\">
                                                                                <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Accounts', 'gnrlOrgID', '', '', 'radio', true, '', 'allLatePymntFeesRow_WWW123WWW_CrdtAcctID', 'allLatePymntFeesRow_WWW123WWW_CrdtAcct', 'clear', 1, ' AND (SELECT is_prnt_accnt FROM accb.accb_chart_of_accnts x WHERE x.accnt_id = tbl1.a::bigint) = \'0\' ');\">
                                                                                         <span class=\"glyphicon glyphicon-th-list\"></span>
                                                                                </label>
                                                                                <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"allLatePymntFeesRow_WWW123WWW_CrdtAcctID\" value=\"-1\" readonly=\"true\"> 
                                                                        </div>                                                                        
                                                                </td>
                                                                <td class=\"lovtd\">
                                                                    <span>&nbsp;</span>
                                                                </td>
                                                                <td class=\"lovtd\">
                                                                        <button type=\"button\" class=\"btn btn-default\" style=\"margin: 0px !important;padding:0px 3px 2px 4px !important;\" onclick=\"delLatePymntFee('allLatePymntFeesRow__WWW123WWW');\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Delete Profile Factor\">
                                                                                        <img src=\"cmn_images/no.png\" style=\"height:15px; width:auto; position: relative; vertical-align: middle;\">
                                                                        </button>
                                                                </td>
                                                                <td style=\"display:none;\" id=\"allLatePymntFeesRow_WWW123WWW_Frqncy\"></td>
                                                                <td style=\"display:none;\" id=\"allLatePymntFeesRow_WWW123WWW_FrqncyNo\"></td>
                        </tr>");
                        ?>
                    <div class="<?php echo $colClassType1; ?>" style="padding:0px 1px 0px 3px !important;"> 
                        <?php if (($trnsStatus == "Incomplete" || $trnsStatus == "Withdrawn" || $trnsStatus == "Rejected") && $vwtypActn != "VIEW") { ?>
                            <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="insertNewRowBe4('allLatePymntFeesTable', 0, '<?php echo $nwRowHtml; ?>');" data-toggle="tooltip" data-placement="bottom" title="Add Profile Factor">
                                <img src="cmn_images/add1-64.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                            </button>
                        <?php } ?>
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
                            <input class="form-control" id="allLatePymntFeesSrchFor" type = "text" placeholder="Search For" value="<?php
                            echo trim(str_replace("%", " ", $srchFor));
                            ?>" onkeyup="enterKeyFuncAllLatePymntFees(event, '', '#allLatePymntFeesDetailInfo', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&subPgNo=7.22&vtypActn=<?php echo $vwtypActn; ?>&prdtID=<?php echo $pkID; ?>');">
                            <input id="allLatePymntFeesPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                            <label class="btn btn-primary btn-file input-group-addon" onclick="getAllLatePymntFees('clear', '#allLatePymntFeesDetailInfo', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&subPgNo=7.22&vtypActn=<?php echo $vwtypActn; ?>&prdtID=<?php echo $pkID; ?>');">
                                <span class="glyphicon glyphicon-remove"></span>
                            </label>
                            <label class="btn btn-primary btn-file input-group-addon" onclick="getAllLatePymntFees('', '#allLatePymntFeesDetailInfo', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&subPgNo=7.22&vtypActn=<?php echo $vwtypActn; ?>&prdtID=<?php echo $pkID; ?>');">
                                <span class="glyphicon glyphicon-search"></span>
                            </label> 
                        </div>
                    </div>
                    <div class="<?php echo $colClassType2; ?>" style="padding:0px 3px 0px 3px !important;">
                        <div class="input-group">
                            <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                            <!--<select data-placeholder="Select..." class="form-control chosen-select" id="allLatePymntFeesSrchIn">
                            <?php
                            $valslctdArry = array("");
                            $srchInsArrys = array("Fee Name");

                            for ($z = 0; $z < count($srchInsArrys); $z++) {
                                if ($srchIn == $srchInsArrys[$z]) {
                                    $valslctdArry[$z] = "selected";
                                }
                                ?>
                                                                                        <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                            <?php } ?>
                            </select>-->
                            <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                            <select data-placeholder="Select..." class="form-control chosen-select" id="allLatePymntFeesDsplySze" style="min-width:70px !important;">                            
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
                                    <a class="rhopagination" href="javascript:getAllLatePymntFees('previous', '#allLatePymntFeesDetailInfo', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&subPgNo=7.22&vtypActn=<?php echo $vwtypActn; ?>&prdtID=<?php echo $pkID; ?>');" aria-label="Previous">
                                        <span aria-hidden="true">&laquo;</span>
                                    </a>
                                </li>
                                <li>
                                    <a class="rhopagination" href="javascript:getAllLatePymntFees('next', '#allLatePymntFeesDetailInfo', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&subPgNo=7.22&vtypActn=<?php echo $vwtypActn; ?>&prdtID=<?php echo $pkID; ?>');" aria-label="Next">
                                        <span aria-hidden="true">&raquo;</span>
                                    </a>
                                </li>
                            </ul>
                        </nav>
                        <input type="hidden" class="form-control" aria-label="..." id="gnrlOrgID" name="gnrlOrgID" value="<?php echo $orgID; ?>">                                                    
                        <input type="hidden" class="form-control" aria-label="..." id="sbmtdRiskProfileID" name="sbmtdRiskProfileID" value="<?php echo $pkID; ?>">
                    </div>
                </div>
                <div class="row" style="padding:0px 15px 0px 15px !important">                  
                    <div class="col-md-12" style="padding:0px 3px 0px 3px !important">
                        <table class="table table-striped table-bordered table-responsive" id="allLatePymntFeesTable" cellspacing="0" width="100%" style="width:100%;min-width: 300px !important;">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Fee Name</th>
                                    <th>Target</th>
                                    <th>Amount Flat</th>
                                    <th>Amount Percent</th>
                                    <!--<th>Debit Account</th>-->
                                    <th>Revenue Account</th>
                                    <th>&nbsp;</th>
                                    <th>&nbsp;</th>
                                    <th style="display:none;">&nbsp;</th>
                                    <th style="display:none;">&nbsp;</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $cntr = 0;
                                while ($row2 = loc_db_fetch_array($result2)) {                                                                
                                    $cntr += 1;
                                    $ttlOptnsScore = 0
                                    ?>
                                    <tr id="allLatePymntFeesRow_<?php echo $cntr; ?>">                                    
                                        <td class="lovtd"><span><?php echo ($curIdx * $lmtSze) + ($cntr); ?></span></td>
                                        <td class="lovtd">
                                            <input type="hidden" class="form-control" aria-label="..." id="allLatePymntFeesRow<?php echo $cntr; ?>_LoanprdtLateFeeID" value="<?php echo $row2[0]; ?>" style="width:100% !important;"> 
                                            <input type="hidden" class="form-control" aria-label="..." id="allLatePymntFeesRow<?php echo $cntr; ?>_LoanPrdtID" value="<?php echo $row2[1]; ?>" style="width:100% !important;">                                                                         
                                            <input <?php echo $mkReadOnly; ?> type="text" class="form-control" aria-label="..." id="allLatePymntFeesRow<?php echo $cntr; ?>_FeeNm" name="allLatePymntFeesRow<?php echo $cntr; ?>_FeeNm" value="<?php echo $row2[2]; ?>">                                                                        
                                        </td>
                                        <td class="lovtd">                                                                         
                                            <div class="input-group">
                                                <input type="text" class="form-control" aria-label="..." id="allLatePymntFeesRow<?php echo $cntr; ?>_Target" name="allLatePymntFeesRow<?php echo $cntr; ?>_Target" value="<?php echo $row2[3]; ?>" readonly="true">
                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'MCF Loan Late Fee Targets', 'gnrlOrgID', '', '', 'radio', true, '', 'allLatePymntFeesRow<?php echo $cntr; ?>_TargetID', 'allLatePymntFeesRow<?php echo $cntr; ?>_Target', 'clear', 1, '');">
                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                </label>
                                                <input type="hidden" class="form-control" aria-label="..." id="allLatePymntFeesRow<?php echo $cntr; ?>_TargetID" value="<?php echo $row2[3]; ?>" readonly="true"> 
                                            </div>                                                                        
                                        </td>
                                        <td class="lovtd"> 
                                            <input <?php echo $mkReadOnly; ?> type="number" min="0" class="form-control rqrdFld" aria-label="..." id="allLatePymntFeesRow<?php echo $cntr; ?>_AmntFlat" name="allLatePymntFeesRow<?php echo $cntr; ?>_AmntFlat" value="<?php echo $row2[4]; ?>">
                                        </td>                                            
                                        <td class="lovtd">  
                                            <input <?php echo $mkReadOnly; ?> type="number" min="0" class="form-control rqrdFld" aria-label="..." id="allLatePymntFeesRow<?php echo $cntr; ?>_AmntPrcnt" name="allLatePymntFeesRow<?php echo $cntr; ?>_AmntPrcnt" value="<?php echo $row2[5]; ?>">                                                               
                                        </td>
                                        <td style="display:none !important;" class="lovtd">                                                                         
                                            <div class="input-group">
                                                <input type="text" class="form-control" aria-label="..." id="allLatePymntFeesRow<?php echo $cntr; ?>_DbtAcct" name="allLatePymntFeesRow<?php echo $cntr; ?>_DbtAcct" value="<?php echo getGnrlRecNm("accb.accb_chart_of_accnts", "accnt_id", "accnt_num || '.' || accnt_name", $row2[6]); ?>" readonly="true">
                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Accounts', 'gnrlOrgID', '', '', 'radio', true, '', 'allLatePymntFeesRow<?php echo $cntr; ?>_DbtAcctID', 'allPrcsnFeesRow<?php echo $cntr; ?>_DbtAcct', 'clear', 1, ' AND (SELECT is_prnt_accnt FROM accb.accb_chart_of_accnts x WHERE x.accnt_id = tbl1.a::bigint) = \'0\' ');">
                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                </label>
                                                <input type="hidden" class="form-control" aria-label="..." id="allLatePymntFeesRow<?php echo $cntr; ?>_DbtAcctID" value="<?php echo $row2[6]; ?>" readonly="true"> 
                                            </div>                                                                        
                                        </td>
                                        <td class="lovtd">                                                                         
                                            <div class="input-group">
                                                <input type="text" class="form-control" aria-label="..." id="allLatePymntFeesRow<?php echo $cntr; ?>_CrdtAcct" name="allLatePymntFeesRow<?php echo $cntr; ?>_CrdtAcct" value="<?php echo getGnrlRecNm("accb.accb_chart_of_accnts", "accnt_id", "accnt_num || '.' || accnt_name", $row2[7]); ?>" readonly="true">
                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Accounts', 'gnrlOrgID', '', '', 'radio', true, '', 'allLatePymntFeesRow<?php echo $cntr; ?>_CrdtAcctID', 'allPrcsnFeesRow<?php echo $cntr; ?>_CrdtAcct', 'clear', 1, ' AND (SELECT is_prnt_accnt FROM accb.accb_chart_of_accnts x WHERE x.accnt_id = tbl1.a::bigint) = \'0\' ');">
                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                </label>
                                                <input type="hidden" class="form-control" aria-label="..." id="allLatePymntFeesRow<?php echo $cntr; ?>_CrdtAcctID" value="<?php echo $row2[7]; ?>" readonly="true"> 
                                            </div>                                                                        
                                        </td>
                                        <td class="lovtd">
                                            <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="getCrdtPrdtLateFeesFrqncyNRmksForm('allLatePymntFeesRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Add Profile Options">
                                                <img src="cmn_images/add1-64.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                            </button>
                                        </td>
                                        <td class="lovtd">
                                            <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delLatePymntFee('allLatePymntFeesRow_<?php echo $cntr; ?>','<?php echo $row2[10]; ?>'));" data-toggle="tooltip" data-placement="bottom" title="Delete Risk Profile">
                                                <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                            </button>
                                        </td>
                                        <td style="display:none;" id="allLatePymntFeesRow<?php echo $cntr; ?>_Frqncy"><?php echo $row2[8]; ?></td>
                                        <td style="display:none;" id="allLatePymntFeesRow<?php echo $cntr; ?>_FrqncyNo"><?php echo $row2[9]; ?></td>
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
    ?>     

    <script type="text/javascript">
        $(document).ready(function () {
            
        });
    </script>                

    <?php
}
?>
