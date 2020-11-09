<?php
if (array_key_exists('lgn_num', get_defined_vars())) {
    $prsnid = $_SESSION['PRSN_ID'];
    $orgID = $_SESSION['ORG_ID'];
    $lnkdFirmID = getGnrlRecNm("prs.prsn_names_nos", "person_id", "lnkd_firm_org_id", $prsnid);
    $pkID = $PKeyID;


    if ($subPgNo == "3.4.1") {//CHEQUE BOOK REGISTER
        if ($vwtyp == "0") {
            if ($vwtypActn == "EDIT") {

                $result = get_ChqBookRegisterDets($pkID);
                while ($row = loc_db_fetch_array($result)) {
                    $rvnAcct = getGnrlRecNm("accb.accb_chart_of_accnts", "accnt_id", "accnt_num || '.' || accnt_name", $row[14]);
                    ?>
                    <form class="form-horizontal" id="chqBookRegisterForm" style="padding:5px 20px 5px 20px;">
                        <div class="row">
                            <!--Cheque Book ID-->
                            <input class="form-control" size="16" type="hidden" id="chqBookId" value="<?php echo $pkID; ?>" readonly="">  

                            <div class="form-group form-group-sm">
                                <label for="chqBookNo" class="control-label col-md-4">Book No.:</label>
                                <div  class="col-md-8">
                                    <input class="form-control" id="chqBookNo" type = "text" placeholder="" value="<?php echo $row[1]; ?>" readonly/>
                                </div>
                            </div>
                            <div class="form-group form-group-sm">
                                <label for="chqType" class="control-label col-md-4">Cheque Type:</label>
                                <div  class="col-md-8">
                                    <select class="form-control" id="chqType" onchange="onChangeOfChqType()" disabled="disabled">
                                        <?php
                                        $sltdPsChq = "";
                                        $sltdCpChq = "";
                                        $sltdGpChq = "";
                                        $sltdCtChq = "";
                                        $sltdBkChq = "";
                                        $sltdVswChq = "";
                                        $sltdVsdChq = "";
                                        $sltdPsbk = "";
                                        $sltdLnApFrm = "";
                                        if ($row[2] === "Personal Cheque") {
                                            $sltdPsChq = "selected";
                                        } else if ($row[2] === "Corporate Cheque") {
                                            $sltdCpChq = "selected";
                                        } else if ($row[2] === "Group Cheque") {
                                            $sltdGpChq = "selected";
                                        } else if ($row[2] === "Counter Cheque") {
                                            $sltdCtChq = "selected";
                                        } else if ($row[2] === "Banker's Draft") {
                                            $sltdBkChq = "selected";
                                        } else if ($row[2] === "Withdrawal Slip") {
                                            $sltdVswChq = "selected";
                                        } else if ($row[2] === "Deposit Slip") {
                                            $sltdVsdChq = "selected";
                                        } else if ($row[2] === "Passbook") {
                                            $sltdPsbk = "selected";
                                        } else if ($row[2] === "Loan Application Form") {
                                            $sltdLnApFrm = "selected";
                                        }
                                        ?>
                                        <option value="Personal Cheque" <?php echo $sltdPsChq; ?>>Personal Cheque</option>
                                        <option value="Corporate Cheque" <?php echo $sltdCpChq; ?>>Corporate Cheque</option>
                                        <option value="Group Cheque" <?php echo $sltdGpChq; ?>>Group Cheque</option>
                                        <option value="Counter Cheque" <?php echo $sltdCtChq; ?>>Counter Cheque</option>
                                        <option value="Banker's Draft" <?php echo $sltdBkChq; ?>>Banker's Draft</option>
                                        <option value="Withdrawal Slip" <?php echo $sltdVswChq; ?>>Withdrawal Slip</option>  
                                        <option value="Deposit Slip" <?php echo $sltdVsdChq; ?>>Deposit Slip</option>  
                                        <option value="Passbook" <?php echo $sltdPsbk; ?>>Passbook</option> 
                                        <option value="Loan Application Form" <?php echo $sltdLnApFrm; ?>>Loan Application Form</option> 
                                    </select>
                                </div>
                            </div>  

                            <?php
                            $dsplyAcct = "";
                            if ($row[2] == "Counter Cheque" || $row[2] == "Banker's Draft" || $row[2] == "Withdrawal Slip" || $row[2] == "Deposit Slip") {
                                $dsplyAcct = "style='display:none !important;'";
                            }
                            ?>                                                       
                            <div <?php echo $dsplyAcct; ?> id="acctNoDiv" class="form-group form-group-sm">
                                <label for="acctNo" class="control-label col-md-4">Account:</label>
                                <div  class="col-md-8">
                                    <input class="form-control" id="acctNo" placeholder="Account" type = "text" placeholder="" value="<?php echo $row[4]; ?>" readonly/>
                                    <input type="hidden" id="acctID" value="<?php echo $row[3]; ?>">
                                    <input type="hidden" id="acctNoFindRawTxt" value="<?php echo ''; ?>">
                                    <input type="hidden" id="gnrlOrgID" value="<?php echo $orgID; ?>">
                                </div>
                            </div>
                            <div <?php echo $dsplyAcct; ?> id="acctTitleDiv" class="form-group form-group-sm">
                                <label for="acctTitle" class="control-label col-md-4">Account Title:</label>
                                <div  class="col-md-8">
                                    <input class="form-control" id="acctTitle" type = "text"  placeholder="Account Title" value="<?php echo $row[5]; ?>" readonly=""/>
                                </div>
                            </div>                          
                            <div class="form-group form-group-sm">
                                <label for="noOfPages" class="control-label col-md-4">No. of Pages:</label>
                                <div  class="col-md-8">
                                    <input class="form-control" id="noOfPages" type = "number" min="0" placeholder="No. of Pages" value="<?php echo $row[6]; ?>" onchange="autoCalcLastSerialNo();" readonly/>
                                </div>
                            </div>
                            <div class="form-group form-group-sm">
                                <label for="chqNoFirst" class="control-label col-md-4">Serial No. 1st:</label>
                                <div  class="col-md-8">
                                    <input class="form-control" id="chqNoFirst" type = "number" min="0" placeholder="Serial No. 1st" value="<?php echo $row[7]; ?>" onchange="autoCalcLastSerialNo();" readonly/>
                                </div>
                            </div>
                            <div class="form-group form-group-sm">
                                <label for="chqNoLast" class="control-label col-md-4">Serial No. Last:</label>
                                <div  class="col-md-8">
                                    <input class="form-control" id="chqNoLast" type = "number" min="0" placeholder="Serial No. Last" value="<?php echo $row[8]; ?>" readonly/>
                                </div>
                            </div>
                            <div style="display:none !important;" class="form-group form-group-sm">
                                <label for="status1" class="control-label col-md-4">Book Status:</label>
                                <div  class="col-md-8">
                                    <input class="form-control" id="status1" type = "text"  placeholder="status1" value="<?php echo $row[9]; ?>" readonly=""/>
                                </div>
                            </div>
                            <div class="form-group form-group-sm">
                                <label for="status" class="control-label col-md-4">Book Status:</label>
                                <div  class="col-md-8">
                                    <select class="form-control rqrdFld" id="status" onchange="onChqBkStatusChng();">
                                        <option value="<?php echo $row[9]; ?>" selected="true" ><?php echo $row[9]; ?></option>
                                        <?php if($row[9] == "Produced"){ ?>
                                        <option value="Issued" >Issued</option> 
                                        <?php } if($row[9] == "Issued" || $row[9] == "Un-Blocked"){ ?>
                                        <option value="Blocked" >Blocked</option> 
                                        <?php } else if($row[9] == "Blocked") { ?>  
                                        <option value="Un-Blocked" >Un-Blocked</option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div> 
                            <?php
                            $dsplyIssDte = "";
                            
                            if ($row[9] !== "Issued") {
                                $dsplyIssDte = "style='display:none !important;'";
                            }
                            ?>                            
                            <div <?php echo $dsplyIssDte; ?> id="issueDateDiv" class="form-group form-group-sm">
                                <label for="issueDate" class="control-label col-md-4">Date Issued:</label>
                                <div  class="col-md-8">
                                    <?php if($row[9] == "Issued"){ ?>
                                    <input class="form-control" size="16" type="text" id="issueDate" value="<?php echo $row[10]; ?>" readonly="">
                                    <?php } else { ?>
                                    <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input3" data-link-format="yyyy-mm-dd">
                                        <input class="form-control rqrdFld" size="16" type="text" id="issueDate" value="<?php echo $row[10]; ?>" readonly="">
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                    </div>
                                    <?php } ?>
                                </div>
                            </div>   
                            <div style="display:none !important;" class="form-group form-group-sm">
                                <label for="approvalStatus" class="control-label col-md-5">Approval Status:</label>
                                <div  class="col-md-7">
                                    <input class="form-control" style="color:red !important;" size="16" type="text" id="approvalStatus" value="<?php echo $row[11]; ?>" placeholder="Approval Status" readonly="">
                                </div>
                            </div>
                            <?php if ($row[2] === "Personal Cheque" || $row[2] === "Corporate Cheque" || $row[2] === "Group Cheque"
                                    || $row[2] === "Passbook" || $row[2] === "Loan Application Form") { ?>
                            <div class="form-group form-group-sm chqBkRvnDiv">
                                <label for="chqBkFee" class="control-label col-md-4">Fee:</label>
                                <div  class="col-md-8">
                                    <input class="form-control" id="chqBkFee" type = "number" min="0" placeholder="Fee" value="<?php echo $row[13]; ?>" readonly="readonly"/>
                                </div>
                            </div>
                            <div class="form-group form-group-sm chqBkRvnDiv">
                                <label for="chqBkFeeRvnueAcct" class="control-label col-md-4">Revenue Account:</label>
                                <div  class="col-md-8">
                                    <input type="text" class="form-control" aria-label="..." id="chqBkFeeRvnueAcct" value="<?php echo $rvnAcct; ?>" readonly="readonly">
                                    <input type="hidden" id="gnrlOrgID" value="<?php echo $orgID; ?>">
                                    <input type="hidden" id="chqBkFeeRvnueAcctID" value="<?php echo $row[14]; ?>">
                                </div>
                            </div>
                            <div class="form-group form-group-sm">
                                <label for="feeStatus" class="control-label col-md-4">Fee Status:</label>
                                <div  class="col-md-8">
                                    <input class="form-control" style="color:red !important;" size="16" type="text" id="feeStatus" value="<?php echo $row[15]; ?>" placeholder="Fee Status" readonly="">
                                </div>
                            </div>
                             <?php } ?>
                        </div>
                        <div class="row" style="float:right;padding-right: 1px;">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-primary" onclick="submitChqBookForm('myFormsModal', '<?php echo $pkID; ?>', 0);">Process</button>
                            <?php if (($row[2] === "Personal Cheque" || $row[2] === "Corporate Cheque" || $row[2] === "Group Cheque"
                                    || $row[2] === "Passbook" || $row[2] === "Loan Application Form") && $row[15] == "Processed") { ?>
                            <button type="button" class="btn btn-warning" onclick="submitChqBookForm('myFormsModal', '<?php echo $pkID; ?>', 1);">Void</button>
                                    <?php } ?>
                            <button type="button" style="display:none !important;" class="btn btn-success" onclick="withdrawChqBookForm('myLovModal', '<?php echo $pkID; ?>');">Withdraw</button>                    
                        </div>
                    </form>
                    <?php
                }
            } 
            else if ($vwtypActn == "ADD") {
                ?>
                <form class="form-horizontal" id="chqBookRegisterForm" style="padding:5px 20px 5px 20px;">
                    <div class="row">
                        <!--Cheque Book ID-->
                        <input class="form-control" size="16" type="hidden" id="chqBookId" value="-1" readonly="">  

                        <div class="form-group form-group-sm">
                            <label for="chqBookNo" class="control-label col-md-4">Book No.:</label>
                            <div  class="col-md-8">
                                <input class="form-control" id="chqBookNo" type = "text" placeholder="" value="" readonly/>
                            </div>
                        </div>
                        <div class="form-group form-group-sm">
                            <label for="chqType" class="control-label col-md-4">Cheque Type:</label>
                            <div  class="col-md-8">
                                <select class="form-control rqrdFld" id="chqType" onchange="onChangeOfChqType()">
                                    <?php
                                    $sltdPsChq = "";
                                    $sltdCpChq = "";
                                    $sltdGpChq = "";
                                    $sltdCtChq = "";
                                    $sltdBkChq = "";
                                    $sltdVswChq = "";
                                    $sltdVsdChq = "";
                                    $sltdPsbk = "";
                                    $sltdLnApFrm = "";
                                    ?>
                                    <option value="Personal Cheque" <?php echo $sltdPsChq; ?>>Personal Cheque</option>
                                    <option value="Corporate Cheque" <?php echo $sltdCpChq; ?>>Corporate Cheque</option>
                                    <option value="Group Cheque" <?php echo $sltdGpChq; ?>>Group Cheque</option>
                                    <option value="Counter Cheque" <?php echo $sltdCtChq; ?>>Counter Cheque</option>
                                    <option value="Banker's Draft" <?php echo $sltdBkChq; ?>>Banker's Draft</option>
                                    <option value="Withdrawal Slip" <?php echo $sltdVswChq; ?>>Withdrawal Slip</option>  
                                    <option value="Deposit Slip" <?php echo $sltdVsdChq; ?>>Deposit Slip</option> 
                                    <option value="Passbook" <?php echo $sltdPsbk; ?>>Passbook</option> 
                                    <option value="Loan Application Form" <?php echo $sltdLnApFrm; ?>>Loan Application Form</option> 
                                </select>
                            </div>
                        </div>  
                        <div id="acctNoDiv" class="form-group form-group-sm">
                            <label for="acctNo" class="control-label col-md-4">Account:</label>
                            <div  class="col-md-8">
                                <div class="input-group">
                                    <input class="form-control rqrdFld" id="acctNo" placeholder="Account" type = "text" placeholder="" value="" readonly/>
                                    <input type="hidden" id="acctID" value="">
                                    <input type="hidden" id="acctNoFindRawTxt" value="<?php echo ''; ?>">
                                    <input type="hidden" id="gnrlOrgID" value="<?php echo $orgID; ?>">
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'MCF All Bank Accounts', 'gnrlOrgID', '', '', 'radio', true, '', 'acctID', 'acctNoFindRawTxt', 'clear', 1, '', function () {
                                                $('#acctNo').val($('#acctNoFindRawTxt').val().split(' [')[0]);
                                                $('#acctTitle').val($('#acctNoFindRawTxt').val().split(' [')[1].split(']')[0]);

                                            });">
                                        <span class="glyphicon glyphicon-th-list"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div id="acctTitleDiv" class="form-group form-group-sm">
                            <label for="acctTitle" class="control-label col-md-4">Account Title:</label>
                            <div  class="col-md-8">
                                <input class="form-control" id="acctTitle" type = "text"  placeholder="Account Title" value="" readonly=""/>
                            </div>
                        </div>                          
                        <div class="form-group form-group-sm">
                            <label for="noOfPages" class="control-label col-md-4">No. of Pages:</label>
                            <div  class="col-md-8">
                                <input class="form-control rqrdFld" id="noOfPages" type = "number" min="0" placeholder="No. of Pages" value="" onchange="autoCalcLastSerialNo();"/>
                            </div>
                        </div>
                        <div class="form-group form-group-sm">
                            <label for="chqNoFirst" class="control-label col-md-4">Serial No. 1st:</label>
                            <div  class="col-md-8">
                                <input class="form-control rqrdFld" id="chqNoFirst" type = "number" min="0" placeholder="Serial No. 1st" value="" onchange="autoCalcLastSerialNo();"/>
                            </div>
                        </div>
                        <div class="form-group form-group-sm">
                            <label for="chqNoLast" class="control-label col-md-4">Serial No. Last:</label>
                            <div  class="col-md-8">
                                <input class="form-control" id="chqNoLast" type = "number" min="0" placeholder="Serial No. Last" value="" readonly=""/>
                            </div>
                        </div>
                        <div class="form-group form-group-sm">
                            <label for="status" class="control-label col-md-4">Book Status:</label>
                            <div  class="col-md-8">
                                <input class="form-control" id="status" type = "text"  placeholder="Status" value="Produced" readonly=""/>
                                <select style="display:none !important;" class="form-control rqrdFld" id="status1" onchange="onChqBkStatusChng();">
                                    <?php
                                    $sltdPrdcd = "";
                                    $sltdIssud = "";
                                    $sltdblkd = "";
                                    $sltdunblkd = "";
                                    ?>
                                    <option value="Produced" <?php echo $sltdPrdcd; ?>>Produced</option>
                                    <option value="Issued" <?php echo $sltdIssud; ?>>Issued</option>
                                    <option value="Blocked" <?php echo $sltdblkd; ?>>Blocked</option>
                                    <option value="Un-Blocked" <?php echo $sltdunblkd; ?>>Un-Blocked</option>                                         

                                </select>
                            </div>
                        </div>                            
                        <div id="issueDateDiv" style ="display:none !important;" class="form-group form-group-sm">
                            <label for="issueDate" class="control-label col-md-4">Date Issued:</label>
                            <div  class="col-md-8">
                                <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input3" data-link-format="yyyy-mm-dd">
                                    <input class="form-control rqrdFld" size="16" type="text" id="issueDate" value="<?php echo date('d-M-Y'); ?>" readonly="">
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                </div>
                            </div>
                        </div>                              
                        <div style="display:none !important;" class="form-group form-group-sm">
                            <label for="approvalStatus" class="control-label col-md-4">Approval Status:</label>
                            <div  class="col-md-8">
                                <input class="form-control" style="color:red !important;" size="16" type="text" id="approvalStatus" value="" placeholder="Approval Status" readonly="">
                            </div>
                        </div>
                        <div class="form-group form-group-sm chqBkRvnDiv">
                            <label for="chqBkFee" class="control-label col-md-4">Fee:</label>
                            <div  class="col-md-8">
                                <input class="form-control rqrdFld" id="chqBkFee" type = "number" min="0" placeholder="Fee" value="0.00"/>
                            </div>
                        </div>
                        <div class="form-group form-group-sm chqBkRvnDiv">
                            <label for="chqBkFeeRvnueAcct" class="control-label col-md-4">Revenue Account:</label>
                            <div  class="col-md-8">
                                <div class="input-group">
                                    <input type="text" class="form-control" aria-label="..." id="chqBkFeeRvnueAcct" value="" readonly="readonly">
                                    <input type="hidden" id="chqBkFeeRvnueAcctID" value="-1">
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Accounts', 'gnrlOrgID', '', '', 'radio', true, '', 'chqBkFeeRvnueAcctID', 'chqBkFeeRvnueAcct', 'clear', 1, ' AND (SELECT is_prnt_accnt FROM accb.accb_chart_of_accnts x WHERE x.accnt_id = tbl1.a::bigint AND accnt_type = \'R\') = \'0\' ');">
                                        <span class="glyphicon glyphicon-th-list"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row" style="float:right;padding-right: 1px;">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" onclick="submitChqBookForm('myFormsModal', '<?php echo $pkID; ?>',0);">Process</button>
                        <button type="button" style="display:none !important;" class="btn btn-success" onclick="withdrawChqBookForm('myLovModal', '<?php echo $pkID; ?>');">Withdraw</button>                    
                    </div>
                </form>
                <?php
            } 
            else if ($vwtypActn == "VIEW") {

                $result = get_ChqBookRegisterDets($pkID);
                while ($row = loc_db_fetch_array($result)) {
                    ?>
                    <form class="form-horizontal" id="chqBookRegisterForm" style="padding:5px 20px 5px 20px;">
                        <div class="row">
                            <!--Cheque Book ID-->
                            <input class="form-control" size="16" type="hidden" id="chqBookId" value="<?php echo $pkID; ?>" readonly="">  

                            <div class="form-group form-group-sm">
                                <label for="chqBookNo" class="control-label col-md-4">Book No.:</label>
                                <div  class="col-md-8">
                                    <span><?php echo $row[1]; ?></span>
                                </div>
                            </div>
                            <div class="form-group form-group-sm">
                                <label for="chqType" class="control-label col-md-4">Cheque Type:</label>
                                <div  class="col-md-8">
                                    <span><?php echo $row[2]; ?></span>
                                </div>
                            </div>                              
                            <?php
                            $dsplyAcct = "";
                            if ($row[2] == "Counter Cheque" || $row[2] == "Banker's Draft" || $row[2] == "Withdrawal Slip" || $row[2] == "Deposit Slip") {
                                $dsplyAcct = "style='display:none !important;'";
                            }
                            ?>                                                       
                            <div <?php echo $dsplyAcct; ?> id="acctNoDiv" class="form-group form-group-sm">                            
                                <label for="acctNo" class="control-label col-md-4">Account:</label>
                                <div  class="col-md-8">
                                    <span><?php echo $row[4]; ?></span>
                                </div>
                            </div>
                            <div  <?php echo $dsplyAcct; ?> class="form-group form-group-sm">
                                <label for="acctTitle" class="control-label col-md-4">Account Title:</label>
                                <div  class="col-md-8">
                                    <span><?php echo $row[5]; ?></span>
                                </div>
                            </div>                           
                            <div class="form-group form-group-sm">
                                <label for="noOfPages" class="control-label col-md-4">No. of Pages:</label>
                                <div  class="col-md-8">
                                    <span><?php echo $row[6]; ?></span>
                                </div>
                            </div>
                            <div class="form-group form-group-sm">
                                <label for="chqNoFirst" class="control-label col-md-4">Serial No. 1st:</label>
                                <div  class="col-md-8">
                                    <span><?php echo $row[7]; ?></span>
                                </div>
                            </div>
                            <div class="form-group form-group-sm">
                                <label for="chqNoLast" class="control-label col-md-4">Serial No. Last:</label>
                                <div  class="col-md-8">
                                    <span><?php echo $row[8]; ?></span>
                                </div>
                            </div>
                            <div class="form-group form-group-sm">
                                <label for="status" class="control-label col-md-4">Book Status:</label>
                                <div  class="col-md-8">
                                    <input id="status" type="hidden" value="<?php echo $row[9]; ?>">
                                    <span><?php echo $row[9]; ?></span>
                                </div>
                            </div> 
                            <?php
                            $dsplyIssDte = "";
                            if ($row[9] !== "Issued") {
                                $dsplyIssDte = "style='display:none !important;'";
                            }
                            ?>                            
                            <div <?php echo $dsplyIssDte; ?> id="issueDateDiv" class="form-group form-group-sm">
                                <label for="issueDate" class="control-label col-md-4">Date Issued:</label>
                                <div  class="col-md-8">
                                    <span><?php echo $row[10]; ?></span>
                                </div>
                            </div>                            
                            <div  style="display:none !important;" class="form-group form-group-sm">
                                <label for="approvalStatus" class="control-label col-md-4">Approval Status:</label>
                                <div  class="col-md-8">
                                    <span><?php echo $row[11]; ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="row" style="float:right;padding-right: 1px;">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>               
                        </div>
                    </form>
                    <?php
                }
            }
        }
    } 
    else if ($subPgNo == "3.4.2") {//CHEQUES REGISTER
        if ($vwtyp == "0") {
            if ($vwtypActn == "EDIT") {

                $result = get_ChqRegisterDets($pkID);
                while ($row = loc_db_fetch_array($result)) {
                    ?>
                    <form class="form-horizontal" id="chqRegisterForm" style="padding:5px 20px 5px 20px;">
                        <div class="row">
                            <!--Cheque ID-->
                            <input class="form-control" size="16" type="hidden" id="chqId" value="<?php echo $pkID; ?>" readonly="">  

                            <div class="form-group form-group-sm">
                                <label for="chqNo" class="control-label col-md-4">Cheque No.:</label>
                                <div  class="col-md-8">
                                    <input class="form-control" id="chqNo" type = "text" placeholder="" value="<?php echo $row[1]; ?>" readonly/>
                                </div>
                            </div>
                            <div class="form-group form-group-sm">
                                <label for="status" class="control-label col-md-4">Status:</label>
                                <div  class="col-md-8">
                                    <select class="form-control rqrdFld" id="status" >
                                        <?php
                                        $sltdVld = "";
                                        $sltdBlkd = "";
                                        $sltdUnBlkd = "";
                                        if ($row[2] == "Valid") {
                                            $sltdVld = "selected";
                                        } else if ($row[2] == "Blocked") {
                                            $sltdBlkd = "selected";
                                        } else if ($row[2] == "Un-Blocked") {
                                            $sltdUnBlkd = "selected";
                                        }
                                        ?>
                                        <option value="Valid" <?php echo $sltdVld; ?>>Valid</option>
                                        <option value="Blocked" <?php echo $sltdBlkd; ?>>Blocked</option> 										
                                    </select>
                                </div>
                            </div> 
                            <div class="form-group form-group-sm">
                                <label for="comments" class="control-label col-md-4">Comments:</label>
                                <div  class="col-md-8">
                                    <textarea class="form-control rqrdFld" id="comments" cols="2" placeholder="Comments" rows="3"><?php echo $row[3]; ?></textarea>
                                </div>                                                         
                            </div> 								
                            <div style="display:none !important;" class="form-group form-group-sm">
                                <label for="approvalStatus" class="control-label col-md-5">Approval Status:</label>
                                <div  class="col-md-7">
                                    <input class="form-control" style="color:red !important;" size="16" type="text" id="approvalStatus" value="<?php echo $row[4]; ?>" placeholder="Approval Status" readonly="">
                                </div>
                            </div>
                        </div>
                        <div class="row" style="float:right;padding-right: 1px;">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-primary" onclick="submitChqForm('myFormsModal', '<?php echo $pkID; ?>');">Submit</button>
                            <button type="button" style="display:none !important;" class="btn btn-success" onclick="withdrawChqForm('myFormsModal', '<?php echo $pkID; ?>');">Withdraw</button>                    
                        </div>
                    </form>
                    <?php
                }
            } else if ($vwtypActn == "ADD") {
                ?>
                <form class="form-horizontal" id="chqRegisterForm" style="padding:5px 20px 5px 20px;">
                    <div class="row">
                        <!--Cheque ID-->
                        <input class="form-control" size="16" type="hidden" id="chqId" value="-1" readonly="">  

                        <div class="form-group form-group-sm">
                            <label for="chqNo" class="control-label col-md-4">Cheque No.:</label>
                            <div  class="col-md-8">
                                <input class="form-control" id="chqNo" type = "text" placeholder="" value="" readonly/>
                            </div>
                        </div>
                        <div class="form-group form-group-sm">
                            <label for="status" class="control-label col-md-4">Status:</label>
                            <div  class="col-md-8">
                                <select class="form-control rqrdFld" id="status" >
                                    <?php
                                    $sltdVld = "";
                                    $sltdBlkd = "";
                                    $sltdUnBlkd = "";
                                    ?>
                                    <option value="Valid" <?php echo $sltdVld; ?>>Valid</option>
                                    <option value="Blocked" <?php echo $sltdBlkd; ?>>Blocked</option>
                                    <option value="Un-Blocked" <?php echo $sltdUnBlkd; ?>>Un-Blocked</option>                                        
                                </select>
                            </div>
                        </div>    
                        <div class="form-group form-group-sm">
                            <label for="comments" class="control-label col-md-4">Comments:</label>
                            <div  class="col-md-8">
                                <textarea class="form-control rqrdFld" id="comments" cols="2" placeholder="Comments" rows="3"></textarea>
                            </div>                                                         
                        </div> 
                        <div style="display:none !important;" class="form-group form-group-sm">
                            <label for="approvalStatus" class="control-label col-md-4">Approval Status:</label>
                            <div  class="col-md-8">
                                <input class="form-control" style="color:red !important;" size="16" type="text" id="approvalStatus" value="" placeholder="Approval Status" readonly="">
                            </div>
                        </div>
                    </div>
                    <div class="row" style="float:right;padding-right: 1px;">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" onclick="submitChqForm('myFormsModal', '<?php echo $pkID; ?>');">Submit</button>
                        <button type="button" style="display:none !important;" class="btn btn-success" onclick="withdrawChqForm('myFormsModal', '<?php echo $pkID; ?>');">Withdraw</button>                    
                    </div>
                </form>
                <?php
            } else if ($vwtypActn == "VIEW") {

                $result = get_ChqRegisterDets($pkID);
                while ($row = loc_db_fetch_array($result)) {
                    ?>
                    <form class="form-horizontal" id="chqRegisterForm" style="padding:5px 20px 5px 20px;">
                        <div class="row">
                            <!--Cheque ID-->
                            <input class="form-control" size="16" type="hidden" id="chqId" value="<?php echo $pkID; ?>" readonly="">  

                            <div class="form-group form-group-sm">
                                <label for="chqNo" class="control-label col-md-4">Cheque No.:</label>
                                <div  class="col-md-8">
                                    <span><?php echo $row[1]; ?></span>
                                </div>
                            </div>
                            <div class="form-group form-group-sm">
                                <label for="status" class="control-label col-md-4">Status:</label>
                                <div  class="col-md-8">
                                    <input id="status" type="hidden" value="<?php echo $row[2]; ?>">
                                    <span><?php echo $row[2]; ?></span>
                                </div>
                            </div>  
                            <div class="form-group form-group-sm">
                                <label for="comments" class="control-label col-md-4">Comments:</label>
                                <div  class="col-md-8">
                                    <span><?php echo $row[3]; ?></span>
                                </div>
                            </div>							
                            <div style="display:none !important;" class="form-group form-group-sm">
                                <label for="approvalStatus" class="control-label col-md-4">Approval Status:</label>
                                <div  class="col-md-8">
                                    <span><?php echo $row[4]; ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="row" style="float:right;padding-right: 1px;">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>                 
                        </div>
                    </form>
                    <?php
                }
            }
        }
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
