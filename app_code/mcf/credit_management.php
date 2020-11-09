<?php
if (array_key_exists('lgn_num', get_defined_vars())) {

    //if ($vwtyp == "0") {
    $cntent .= "
					<li onclick=\"openATab('#allmodules', 'grp=17&typ=1&pg=4');\">
                                                <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
						<span style=\"text-decoration:none;\">Credit Management Menu</span>
                                        </li>";

    $usrID = $_SESSION['USRID'];
    $prsnid = $_SESSION['PRSN_ID'];
    $orgID = $_SESSION['ORG_ID'];
    $qStrtDte = "01-Jan-1900 00:00:00";
    $qEndDte = "31-Dec-4000 23:59:59";
    
    $canAddLoanApplctn = test_prmssns($dfltPrvldgs[143], $mdlNm); 
    $canEditLoanApplctn = test_prmssns($dfltPrvldgs[144], $mdlNm); 
    $canDelLoanApplctn = test_prmssns($dfltPrvldgs[145], $mdlNm); 
    $canAuthorzLoanApplctn = test_prmssns($dfltPrvldgs[146], $mdlNm);
    $canViewLoanApplctn = test_prmssns($dfltPrvldgs[26], $mdlNm); 
    
    $canExprtLoanApplctn = test_prmssns($dfltPrvldgs[215], $mdlNm);   
    $canImprtLoanApplctn = test_prmssns($dfltPrvldgs[216], $mdlNm);   

    $canAddLoanDsbmnt = test_prmssns($dfltPrvldgs[147], $mdlNm); 
    $canEditLoanDsbmnt = test_prmssns($dfltPrvldgs[148], $mdlNm); 
    $canDelLoanDsbmnt = test_prmssns($dfltPrvldgs[149], $mdlNm); 
    $canViewLoanDsbmnt = test_prmssns($dfltPrvldgs[27], $mdlNm);   
    
    $canImprtLoanDsbmnt = test_prmssns($dfltPrvldgs[216], $mdlNm);  
    $canImprtLoanSchedule = test_prmssns($dfltPrvldgs[216], $mdlNm);    
    
    $canAddLoanRpmnt = test_prmssns($dfltPrvldgs[109], $mdlNm); 
    $canEditLoanRpmnt = test_prmssns($dfltPrvldgs[110], $mdlNm); 
    $canDelLoanRpmnt = test_prmssns($dfltPrvldgs[111], $mdlNm); 
    $canViewLoanRpmnt = test_prmssns($dfltPrvldgs[28], $mdlNm);  
   
    
    $lnkdFirmID = getGnrlRecNm("prs.prsn_names_nos", "person_id", "lnkd_firm_org_id", $prsnid);
    $pkID = $prsnid;
    
    $prsnBranchID = get_Person_BranchID($prsnid);
    $prsnBranch = getGnrlRecNm("org.org_sites_locations", "location_id", "site_desc||' ('||location_code_name||')'", "pasn.get_prsn_siteid($prsnid)");             

    if ($pkID > 0) {
        
    $branchSrchIn = isset($_POST['branchSrchIn']) ? cleanInputData($_POST['branchSrchIn']) : $prsnBranchID;
    $statusSrchIn = isset($_POST['statusSrchIn']) ? cleanInputData($_POST['statusSrchIn']) : "All Statuses";  
    $crdtTypeSrchIn = isset($_POST['crdtTypeSrchIn']) ? cleanInputData($_POST['crdtTypeSrchIn']) : "All Credit Types";
    
    if (isset($_POST['qStrtDte'])) {
        $qStrtDte = cleanInputData($_POST['qStrtDte']);
        if (strlen($qStrtDte) == 11) {
            $qStrtDte = substr($qStrtDte, 0, 11) . " 00:00:00";
        } else {
            $qStrtDte = "01-Jan-1900 00:00:00";
        }
    }

    if (isset($_POST['qEndDte'])) {
        $qEndDte = cleanInputData($_POST['qEndDte']);
        if (strlen($qEndDte) == 11) {
            $qEndDte = substr($qEndDte, 0, 11) . " 23:59:59";
        } else {
            $qEndDte = "31-Dec-4000 23:59:59";
        }
    }    

    if($branchSrchIn == -1){

    } else {
        $prsnBranchID = $branchSrchIn;
    }      

        if ($subPgNo == 4.1) {//LOAN APPLICATIONS
        echo $cntent .= "
					<li onclick=\"openATab('#allmodules', 'grp=17&typ=1&pg=4&subPgNo=4.1');\">
                                                <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
						<span style=\"text-decoration:none;\">Loan Applications</span>
                                        </li></div>";            
            $error = "";
            $searchAll = true;

            $srchFor = isset($_POST['searchfor']) ? cleanInputData($_POST['searchfor']) : '';
            $srchIn = isset($_POST['searchin']) ? cleanInputData($_POST['searchin']) : 'Both';
            $pageNo = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
            $lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 10;
            $sortBy = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : "Date Added DESC";
            if (strpos($srchFor, "%") === FALSE) {
                $srchFor = "%" . str_replace(" ", "%", $srchFor) . "%";
                $srchFor = str_replace("%%", "%", $srchFor);
            }

            if ($vwtyp == 0) {
                $canAddPrsn = test_prmssns($dfltPrvldgs[7], $mdlNm);
                /*echo $cntent . "<li>
						<span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                                <span style=\"text-decoration:none;\">Data Administration</span>
					</li>
                                       </ul>
                                     </div>";*/
                $total = get_LoanRqstTtl($qStrtDte, $qEndDte, $crdtTypeSrchIn, $statusSrchIn, $branchSrchIn, $srchFor, $srchIn, $orgID, $searchAll);
                if ($pageNo > ceil($total / $lmtSze)) {
                    $pageNo = 1;
                } else if ($pageNo < 1) {
                    $pageNo = ceil($total / $lmtSze);
                }

                $curIdx = $pageNo - 1;
                $result = get_LoanRqst($qStrtDte, $qEndDte, $crdtTypeSrchIn, $statusSrchIn, $branchSrchIn, $srchFor, $srchIn, $curIdx, $lmtSze, $orgID, $searchAll, $sortBy);
                $cntr = 0;
                $colClassType1 = "col-lg-2";
                $colClassType2 = "col-lg-3";
                ?> 
                <form id='dataAdminForm' action='' method='post' accept-charset='UTF-8'>
                    <!--ROW ID-->
                    <input class="form-control" id="tblRowID" type = "hidden" placeholder="ROW ID"/>                     
                    <fieldset class="basic_person_fs1"><legend class="basic_person_lg" style="color: #003245">LOAN APPLICATIONS</legend>
                    <div class="row" style="margin-bottom:1px;">
                        <?php
                        if ($canAddLoanApplctn === true) {
                            ?>   
                            <div class="<?php echo $colClassType1; ?>" style="padding:0px 1px 0px 15px !important;">                    
                                <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="getLoanRqstForm('myFormsModalLg', 'myFormsModalBodyLg', 'myFormsModalTitleLg', 'Add New Loan', 15, <?php echo $subPgNo; ?>,0,'ADD', -1,'indCustTable');">
                                    <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                    New Loan
                                </button>
                            </div>
                            <?php
                        } else {
                            $colClassType1 = "col-lg-2";
                            $colClassType2 = "col-lg-4";
                        }
                        ?>
                        <div class="<?php echo $colClassType2; ?>" style="padding:0px 15px 0px 15px !important;">
                            <div class="input-group">
                                <input class="form-control" id="dataAdminSrchFor" type = "text" placeholder="Search For" value="<?php echo trim(str_replace("%", " ", $srchFor)); ?>" onkeyup="enterKeyFuncCust(event, '', '#allmodules', 'grp=17&typ=1&pg=4&subPgNo=4.1')">
                                <input id="dataAdminPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                <label class="btn btn-primary btn-file input-group-addon" onclick="getCustData('clear', '#allmodules', 'grp=17&typ=1&pg=4&subPgNo=4.1')">
                                    <span class="glyphicon glyphicon-remove"></span>
                                </label>
                                <label class="btn btn-primary btn-file input-group-addon" onclick="getCustData('', '#allmodules', 'grp=17&typ=1&pg=4&subPgNo=4.1')">
                                    <span class="glyphicon glyphicon-search"></span>
                                </label> 
                            </div>
                        </div>
                        <div class="<?php echo $colClassType2; ?>">
                            <div class="input-group">
                                <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                <select data-placeholder="Select..." class="form-control chosen-select" id="dataAdminSrchIn">
                                    <?php
                                    $valslctdArry = array("", "", "", "");
                                    $srchInsArrys = array("Request Number","Customer Name", "Account Number", "Product Name");
                                    for ($z = 0; $z < count($srchInsArrys); $z++) {
                                        if ($srchIn == $srchInsArrys[$z]) {
                                            $valslctdArry[$z] = "selected";
                                        }
                                        ?>
                                        <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                    <?php } ?>
                                </select>
                                <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                <select data-placeholder="Select..." class="form-control chosen-select" id="dataAdminDsplySze" style="min-width:70px !important;">                            
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
                        <div class="<?php echo $colClassType1; ?>">
                            <div class="input-group">                        
                                <span class="input-group-addon"><span class="glyphicon glyphicon-sort-by-attributes"></span></span>
                                <select data-placeholder="Select..." class="form-control chosen-select" id="dataAdminSortBy">
                                    <?php
                                    $valslctdArry = array("", "", "", "", "", "");
                                    $srchInsArrys = array("Date Added DESC", "Request Number ASC", "Account Number", "Product Name ASC", "Loan Status ASC");
                                    for ($z = 0; $z < count($srchInsArrys); $z++) {
                                        if ($sortBy == $srchInsArrys[$z]) {
                                            $valslctdArry[$z] = "selected";
                                        }
                                        ?>
                                        <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="<?php echo $colClassType1; ?>">
                            <nav aria-label="Page navigation">
                                <ul class="pagination" style="margin: 0px !important;">
                                    <li>
                                        <a href="javascript:getCustData('previous', '#allmodules', 'grp=17&typ=1&pg=4&subPgNo=4.1');" aria-label="Previous">
                                            <span aria-hidden="true">&laquo;</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:getCustData('next', '#allmodules', 'grp=17&typ=1&pg=4&subPgNo=4.1');" aria-label="Next">
                                            <span aria-hidden="true">&raquo;</span>
                                        </a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                    <div class="row" style="margin-bottom:2px;padding:1px 15px 2px 15px !important">   
                        <div  class="col-md-12" style="padding:2px 1px 2px 1px !important;border-top:1px solid #ddd;border-bottom:1px solid #ddd;">
                            <div class="col-md-2" style="float:left;">
                                <div class="btn-group" style="margin-bottom: 1px;">
                                    <button type="button" class="btn btn-default btn-sm" style="height: 30px !important;" onclick="">
                                        <img src="cmn_images/image007.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                        Excel
                                    </button>
                                    <button class="btn btn-info dropdown-toggle btn-sm" style="height:30px !important;" type="button" data-toggle="dropdown">
                                        <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu" role="menu" style="margin-left: 15px !important;">
                                        <?php if ($canExprtLoanApplctn === true){ ?> 
                                        <li>
                                            <a href="javascript:alert(exprtLoanApplctns());">
                                                <img src="cmn_images/image007.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                Export Loans
                                            </a>
                                        </li>
                                        <?php } if ($canImprtLoanApplctn === true) { ?> 
                                        <li>
                                            <a href="javascript:imprtLoanApplctns();">
                                                <img src="cmn_images/image007.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                Import Loans
                                            </a>
                                        </li>
                                        <?php } if ($canImprtLoanApplctn === true) { ?> 
                                        <li>
                                            <a href="javascript:imprtLoanDsbsmntDates();">
                                                <img src="cmn_images/image007.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                Import Loan Disbursement Dates
                                            </a>
                                        </li>
                                        <?php } ?> 
                                    </ul>
                                </div>                              
                            </div>
                            <div class="col-md-4" style="padding:0px 1px 0px 1px !important;">
                                <div class="col-xs-6" style="padding:0px 1px 0px 0px !important;">
                                    <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                        <input class="form-control" size="16" type="text" id="dataAdminStrtDate" name="dataAdminStrtDate" value="<?php
                                        echo substr($qStrtDte, 0, 11);
                                        ?>" placeholder="Start Date">
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                    </div></div>
                                <div class="col-xs-6" style="padding:0px 1px 0px 0px !important;">
                                    <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                        <input class="form-control" size="16" type="text"  id="dataAdminEndDate" name="dataAdminEndDate" value="<?php
                                        echo substr($qEndDte, 0, 11);
                                        ?>" placeholder="End Date">
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                    </div></div>                            
                            </div>                            
                            <div class="col-md-6" style="float:right !important;">
                                <div class="col-lg-12" style="margin-bottom: 1px;"><!-- style="padding: 5px 1px 0px 15px !important">-->
                                    <div class="input-group">
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>                                        
                                        <select class="form-control" id="dataAdminStatusSrchIn" onchange="javascript:getCustData('', '#allmodules', 'grp=17&typ=1&pg=4&subPgNo=4.1');">
                                            <?php

                                                $selectedTxtAS = "";
                                                $selectedTxtATZ = "";
                                                $selectedTxtRJT = "";
                                                $selectedTxtINC = "";
                                                $selectedTxtWDR = "";
                                                $selectedTxtUOT = "";
                                                if ($statusSrchIn == "All Statuses") {
                                                    $selectedTxtAS = "selected";
                                                }
                                                else if ($statusSrchIn == "Approved") {
                                                    $selectedTxtATZ = "selected";
                                                }
                                                else if ($statusSrchIn == "Rejected") {
                                                    $selectedTxtRJT = "selected";
                                                }
                                                else if ($statusSrchIn == "Incomplete") {
                                                    $selectedTxtINC = "selected";
                                                }
                                                else if ($statusSrchIn == "Withdrawn") {
                                                    $selectedTxtWDR = "selected";
                                                }
                                                else if ($statusSrchIn == "Initiated") {
                                                    $selectedTxtUOT = "selected";
                                                }
                                            ?>
                                            <option <?php echo $selectedTxtAS; ?> value="All Statuses">All Statuses</option>
                                            <option value="Initiated" <?php echo $selectedTxtUOT; ?>>Initiated</option>
                                            <option value="Approved" <?php echo $selectedTxtATZ; ?>>Approved</option>
                                            <option value="Rejected" <?php echo $selectedTxtRJT; ?>>Rejected</option>
                                            <option value="Incomplete" <?php echo $selectedTxtINC; ?>>Incomplete</option>
                                            <option value="Withdrawn" <?php echo $selectedTxtWDR; ?>>Withdrawn</option>
                                        </select>                                    
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                        <select class="form-control" id="dataAdminBranchSrchIn" onchange="javascript:getCustData('', '#allmodules', 'grp=17&typ=1&pg=4&subPgNo=4.1');">
                                            <option value="All Branches">All Branches</option>
                                            <?php
                                            $brghtStr = "";
                                            $isDynmyc = FALSE;
                                            $titleRslt = getLovValues("%", "Both", 0, 100, $brghtStr,
                                                            getLovID("Sites/Locations New"), $isDynmyc, -1, "", "");
                                            while ($titleRow = loc_db_fetch_array($titleRslt)) {
                                                $selectedTxt = "";
                                                if ($titleRow[0] == $prsnBranchID) {
                                                    $selectedTxt = "selected";
                                                }
                                                ?>
                                                <option value="<?php echo $titleRow[0]; ?>" <?php echo $selectedTxt; ?>><?php echo $titleRow[1]; ?></option>
                                                <?php
                                            }
                                            ?>
                                        </select> 
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                        <select class="form-control" id="dataAdminCrdtTypeSrchIn" onchange="">
                                            <?php
                                                $selectedTxtAS = "";
                                                $selectedTxtATZ = "";
                                                $selectedTxtRJT = "";
                                                if ($crdtTypeSrchIn == "All Credit Types") {
                                                    $selectedTxtAS = "selected";
                                                }
                                                else if ($crdtTypeSrchIn == "Loan") {
                                                    $selectedTxtATZ = "selected";
                                                }
                                                else if ($crdtTypeSrchIn == "Overdraft") {
                                                    $selectedTxtRJT = "selected";
                                                }
                                            ?>
                                            <option <?php echo $selectedTxtAS; ?> value="All Credit Types">All Credit Types</option>
                                            <option value="Loan" <?php echo $selectedTxtATZ; ?>>Loan</option>
                                            <option value="Overdraft" <?php echo $selectedTxtRJT; ?>>Overdraft</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>                          
                    <div class="row"> 
                        <div  class="col-md-12">
                            <table class="table table-striped table-bordered table-responsive" id="indCustTable" cellspacing="0" width="100%" style="width:100%;">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <?php if ($canEditLoanApplctn === true) { ?>
                                            <th>...</th>
                                        <?php } ?>
                                       <?php if ($canDelLoanApplctn === true) { ?>
                                            <th>...</th>
                                        <?php } ?>
                                        <th>Customer</th>                                        
                                        <th>Request Number</th>
                                        <th>Product</th>
                                        <th>Application Date</th>
                                        <th>Loan Amount</th>
                                        <th>Status</th>
                                        <th>...</th>
                                        <!--<th>...</th>-->
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    while ($row = loc_db_fetch_array($result)) {
                                        /**/
                                        $cntr += 1;

                                        ?>
                                        <tr id="indCustTableRow<?php echo $cntr; ?>">                                    
                                            <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                            <?php if ($canEditLoanApplctn === true) { ?>                                    
                                                <td class="lovtd">
                                                    <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="Edit Loan Request" 
                                                            onclick="getLoanRqstForm('myFormsModalLg', 'myFormsModalBodyLg', 'myFormsModalTitleLg', 'Edit Loan Request', 15, <?php echo $subPgNo; ?>,0,'EDIT', <?php echo $row[0]; ?>,'indCustTable','indCustTableRow<?php echo $cntr; ?>');" style="padding:2px !important;" style="padding:2px !important;">
                                                        <!--<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>-->
                                                        <img src="cmn_images/edit32.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                    </button>
                                                </td>
                                            <?php } ?>
                                            <?php if ($canDelLoanApplctn === true) { ?>
                                            <td class="lovtd">
                                                <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="Delete Account" onclick="deleteLoanApplication(<?php echo $row[0]; ?>,'<?php echo $row[7]; ?>')" style="padding:2px !important;" style="padding:2px !important;">
                                                    <!--<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>-->
                                                    <img src="cmn_images/delete.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                </button>
                                            </td>
                                             <?php } ?>
                                            <td class="lovtd"><?php echo $row[1]; ?></td>
                                            <td class="lovtd"><?php echo $row[2]; ?></td>
                                            <td class="lovtd"><?php echo $row[3]; ?></td>
                                            <td class="lovtd"><?php echo $row[4]; ?></td>
                                            <td class="lovtd"><?php echo $row[5]; ?></td>
                                            <td class="lovtd"><?php echo $row[7]; ?></td>
                                            <?php if ($canViewLoanApplctn === true) { ?>
                                            <td class="lovtd">
                                                <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Loan Request" onclick="getLoanRqstForm('myFormsModalLg', 'myFormsModalBodyLg', 'myFormsModalTitleLg', 'Edit Loan Request', 15, <?php echo $subPgNo; ?>,0,'VIEW', <?php echo $row[0]; ?>,'indCustTable','indCustTableRow<?php echo $cntr; ?>');" style="padding:2px !important;" style="padding:2px !important;">
                                                    <!--<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>-->
                                                    <img src="cmn_images/kghostview.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                </button>
                                            </td>
                                            <?php } ?>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>                     
                    </div>
                    </fieldset>
                </form>
                <?php
            } 
        } 
        else if ($subPgNo == 4.2) {//LOAN DISBURSEMENT
        echo $cntent .= "
					<li onclick=\"openATab('#allmodules', 'grp=17&typ=1&pg=4&subPgNo=4.2');\">
                                                <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
						<span style=\"text-decoration:none;\">Loan Disbursement</span>
                                        </li></div>";            
            $error = "";
            $searchAll = true;

            $srchFor = isset($_POST['searchfor']) ? cleanInputData($_POST['searchfor']) : '';
            $srchIn = isset($_POST['searchin']) ? cleanInputData($_POST['searchin']) : 'Both';
            $pageNo = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
            $lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 10;
            $sortBy = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : "Transaction Date DESC";
            if (strpos($srchFor, "%") === FALSE) {
                $srchFor = "%" . str_replace(" ", "%", $srchFor) . "%";
                $srchFor = str_replace("%%", "%", $srchFor);
            }

            if ($vwtyp == 0) {
                $canAddPrsn = test_prmssns($dfltPrvldgs[7], $mdlNm);
                /*echo $cntent . "<li>
						<span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                                <span style=\"text-decoration:none;\">Data Administration</span>
					</li>
                                       </ul>
                                     </div>";*/
                $total = get_DisbursmentHdrTtl($srchFor, $srchIn, $orgID, $searchAll);
                if ($pageNo > ceil($total / $lmtSze)) {
                    $pageNo = 1;
                } else if ($pageNo < 1) {
                    $pageNo = ceil($total / $lmtSze);
                }

                $curIdx = $pageNo - 1;
                $result = get_DisbursmentHdr($srchFor, $srchIn, $curIdx, $lmtSze, $orgID, $searchAll, $sortBy);
                $cntr = 0;
                $colClassType1 = "col-lg-2";
                $colClassType2 = "col-lg-3";
                ?> 
                <form id='dataAdminForm' action='' method='post' accept-charset='UTF-8'>
                    <!--ROW ID-->
                    <input class="form-control" id="tblRowID" type = "hidden" placeholder="ROW ID"/>                     
                    <fieldset class="basic_person_fs1"><legend class="basic_person_lg" style="color: #003245">LOAN DISBURSEMENT</legend>
                    <div class="row" style="margin-bottom:1px;">
                        <?php
                        if ($canAddLoanDsbmnt === true) {
                            ?>   
                            <div class="<?php echo $colClassType1; ?>" style="padding:0px 1px 0px 15px !important;">                    
                                <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="getDisbursementForm('myFormsModalLg', 'myFormsModalBodyLg', 'myFormsModalTitleLg', 'Add New Disbursement', 15, <?php echo $subPgNo; ?>,0,'ADD', -1,'indCustTable');">
                                    <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                    New Disbursement
                                </button>
                            </div>
                            <?php
                        } else {
                            $colClassType1 = "col-lg-2";
                            $colClassType2 = "col-lg-4";
                        }
                        ?>
                        <div class="<?php echo $colClassType2; ?>" style="padding:0px 15px 0px 15px !important;">
                            <div class="input-group">
                                <input class="form-control" id="dataAdminSrchFor" type = "text" placeholder="Search For" value="<?php echo trim(str_replace("%", " ", $srchFor)); ?>" onkeyup="enterKeyFuncCust(event, '', '#allmodules', 'grp=17&typ=1&pg=4&subPgNo=4.2')">
                                <input id="dataAdminPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                <label class="btn btn-primary btn-file input-group-addon" onclick="getCustData('clear', '#allmodules', 'grp=17&typ=1&pg=4&subPgNo=4.2')">
                                    <span class="glyphicon glyphicon-remove"></span>
                                </label>
                                <label class="btn btn-primary btn-file input-group-addon" onclick="getCustData('', '#allmodules', 'grp=17&typ=1&pg=4&subPgNo=4.2')">
                                    <span class="glyphicon glyphicon-search"></span>
                                </label> 
                            </div>
                        </div>
                        <div class="<?php echo $colClassType2; ?>">
                            <div class="input-group">
                                <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                <select data-placeholder="Select..." class="form-control chosen-select" id="dataAdminSrchIn">
                                    <?php
                                    $valslctdArry = array("", "", "", "", "");
                                    $srchInsArrys = array("Batch Number", "Transaction Date", "Status"
                                        , "Description","Branch");
                                    for ($z = 0; $z < count($srchInsArrys); $z++) {
                                        if ($srchIn == $srchInsArrys[$z]) {
                                            $valslctdArry[$z] = "selected";
                                        }
                                        ?>
                                        <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                    <?php } ?>
                                </select>
                                <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                <select data-placeholder="Select..." class="form-control chosen-select" id="dataAdminDsplySze" style="min-width:70px !important;">                            
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
                        <div class="<?php echo $colClassType1; ?>">
                            <div class="input-group">                        
                                <span class="input-group-addon"><span class="glyphicon glyphicon-sort-by-attributes"></span></span>
                                <select data-placeholder="Select..." class="form-control chosen-select" id="dataAdminSortBy">
                                    <?php
                                    $valslctdArry = array("", "", "", "", "");
                                    $srchInsArrys = array("Transaction Date DESC", "Batch Number", "Date Added DESC",  "Status ASC");
                                    for ($z = 0; $z < count($srchInsArrys); $z++) {
                                        if ($sortBy == $srchInsArrys[$z]) {
                                            $valslctdArry[$z] = "selected";
                                        }
                                        ?>
                                        <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="<?php echo $colClassType1; ?>">
                            <nav aria-label="Page navigation">
                                <ul class="pagination" style="margin: 0px !important;">
                                    <li>
                                        <a href="javascript:getCustData('previous', '#allmodules', 'grp=17&typ=1&pg=4&subPgNo=4.2');" aria-label="Previous">
                                            <span aria-hidden="true">&laquo;</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:getCustData('next', '#allmodules', 'grp=17&typ=1&pg=4&subPgNo=4.2');" aria-label="Next">
                                            <span aria-hidden="true">&raquo;</span>
                                        </a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                    <div class="row" style="margin-bottom:2px;padding:1px 15px 2px 15px !important">   
                        <div  class="col-md-12" style="padding:2px 1px 2px 1px !important;border-top:1px solid #ddd;border-bottom:1px solid #ddd;">
                            <div class="col-md-12" style="float:left;">
                                <div class="btn-group" style="margin-bottom: 1px;">
                                    <button type="button" class="btn btn-default btn-sm" style="height: 30px !important;" onclick="">
                                        <img src="cmn_images/image007.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                        Excel
                                    </button>
                                    <button class="btn btn-info dropdown-toggle btn-sm" style="height:30px !important;" type="button" data-toggle="dropdown">
                                        <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu" role="menu" style="margin-left: 15px !important;">
                                        <?php if ($canImprtLoanDsbmnt === true){ ?> 
                                        <li>
                                            <a href="javascript:imprtDisbursements();">
                                                <img src="cmn_images/image007.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                Import Disbursements
                                            </a>
                                        </li>
                                        <?php } if ($canImprtLoanSchedule === true) { ?> 
                                        <li>
                                            <a href="javascript:imprtLoanSchedules();">
                                                <img src="cmn_images/image007.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                Import Loan Schedules
                                            </a>
                                        </li>
                                        <?php } if ($canImprtLoanSchedule === true) { ?> 
                                        <li>
                                            <a href="javascript:populateLoanSchedules();">
                                                <img src="cmn_images/image007.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                Populate Loan Schedules
                                            </a>
                                        </li>
                                        <?php } if ($canImprtLoanDsbmnt === true) { ?> 
                                        <li>
                                            <a href="javascript:imprtOverdraftDisbursements();">
                                                <img src="cmn_images/image007.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                Import Overdraft Disbursement
                                            </a>
                                        </li>
                                        <?php } ?>                                     
                                    </ul>
                                </div>                              
                            </div>
                            <div class="col-md-4" style="padding:0px 1px 0px 1px !important;display:none !important;">
                                <div class="col-xs-6" style="padding:0px 1px 0px 0px !important;">
                                    <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                        <input class="form-control" size="16" type="text" id="dataAdminStrtDate" name="dataAdminStrtDate" value="<?php
                                        echo substr($qStrtDte, 0, 11);
                                        ?>" placeholder="Start Date">
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                    </div></div>
                                <div class="col-xs-6" style="padding:0px 1px 0px 0px !important;">
                                    <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                        <input class="form-control" size="16" type="text"  id="dataAdminEndDate" name="dataAdminEndDate" value="<?php
                                        echo substr($qEndDte, 0, 11);
                                        ?>" placeholder="End Date">
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                    </div></div>                            
                            </div>                            
                            <div class="col-md-6" style="float:right !important;display:none !important;">
                                <div class="col-lg-12" style="margin-bottom: 1px;"><!-- style="padding: 5px 1px 0px 15px !important">-->
                                    <div class="input-group">
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>                                        
                                        <select class="form-control" id="dataAdminStatusSrchIn" onchange="javascript:getCustData('', '#allmodules', 'grp=17&typ=1&pg=4&subPgNo=4.1');">
                                            <?php

                                                $selectedTxtAS = "";
                                                $selectedTxtATZ = "";
                                                $selectedTxtRJT = "";
                                                $selectedTxtINC = "";
                                                $selectedTxtWDR = "";
                                                $selectedTxtUOT = "";
                                                if ($statusSrchIn == "All Statuses") {
                                                    $selectedTxtAS = "selected";
                                                }
                                                else if ($statusSrchIn == "Approved") {
                                                    $selectedTxtATZ = "selected";
                                                }
                                                else if ($statusSrchIn == "Rejected") {
                                                    $selectedTxtRJT = "selected";
                                                }
                                                else if ($statusSrchIn == "Incomplete") {
                                                    $selectedTxtINC = "selected";
                                                }
                                                else if ($statusSrchIn == "Withdrawn") {
                                                    $selectedTxtWDR = "selected";
                                                }
                                                else if ($statusSrchIn == "Initiated") {
                                                    $selectedTxtUOT = "selected";
                                                }
                                            ?>
                                            <option <?php echo $selectedTxtAS; ?> value="All Statuses">All Statuses</option>
                                            <option value="Initiated" <?php echo $selectedTxtUOT; ?>>Initiated</option>
                                            <option value="Approved" <?php echo $selectedTxtATZ; ?>>Approved</option>
                                            <option value="Rejected" <?php echo $selectedTxtRJT; ?>>Rejected</option>
                                            <option value="Incomplete" <?php echo $selectedTxtINC; ?>>Incomplete</option>
                                            <option value="Withdrawn" <?php echo $selectedTxtWDR; ?>>Withdrawn</option>
                                        </select>                                    
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                        <select class="form-control" id="dataAdminBranchSrchIn" onchange="javascript:getCustData('', '#allmodules', 'grp=17&typ=1&pg=4&subPgNo=4.1');">
                                            <option value="All Branches">All Branches</option>
                                            <?php
                                            $brghtStr = "";
                                            $isDynmyc = FALSE;
                                            $titleRslt = getLovValues("%", "Both", 0, 100, $brghtStr,
                                                            getLovID("Sites/Locations New"), $isDynmyc, -1, "", "");
                                            while ($titleRow = loc_db_fetch_array($titleRslt)) {
                                                $selectedTxt = "";
                                                if ($titleRow[0] == $prsnBranchID) {
                                                    $selectedTxt = "selected";
                                                }
                                                ?>
                                                <option value="<?php echo $titleRow[0]; ?>" <?php echo $selectedTxt; ?>><?php echo $titleRow[1]; ?></option>
                                                <?php
                                            }
                                            ?>
                                        </select> 
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                        <select class="form-control" id="dataAdminCrdtTypeSrchIn" onchange="">
                                            <?php
                                                $selectedTxtAS = "";
                                                $selectedTxtATZ = "";
                                                $selectedTxtRJT = "";
                                                if ($crdtTypeSrchIn == "All Credit Types") {
                                                    $selectedTxtAS = "selected";
                                                }
                                                else if ($crdtTypeSrchIn == "Loan") {
                                                    $selectedTxtATZ = "selected";
                                                }
                                                else if ($crdtTypeSrchIn == "Overdraft") {
                                                    $selectedTxtRJT = "selected";
                                                }
                                            ?>
                                            <option <?php echo $selectedTxtAS; ?> value="All Credit Types">All Credit Types</option>
                                            <option value="Loan" <?php echo $selectedTxtATZ; ?>>Loan</option>
                                            <option value="Overdraft" <?php echo $selectedTxtRJT; ?>>Overdraft</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>                        
                    <div class="row"> 
                        <div  class="col-md-12">
                            <table class="table table-striped table-bordered table-responsive" id="indCustTable" cellspacing="0" width="100%" style="width:100%;">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <?php if ($canEditLoanDsbmnt === true) { ?>
                                            <th>...</th>
                                        <?php } ?>
                                        <?php if ($canDelLoanDsbmnt === true) { ?>
                                            <th>...</th>
                                        <?php } ?>
                                        <th>Batch Number</th>                                        
                                        <th>Transaction Date</th>
                                        <th>Description</th>
                                        <th>Qty</th>
                                        <th>CUR</th>
                                        <th>Total Amount</th>
                                        <th>Status</th>
                                        <th>...</th>
                                        <!--<th>...</th>-->
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    while ($row = loc_db_fetch_array($result)) {
                                        /**/
                                        $crncy = getGnrlRecNm("mcf.mcf_currencies", "crncy_id", "iso_code", (int)$row[8]);
                                        $cntr += 1;

                                        ?>
                                        <tr id="indCustTableRow<?php echo $cntr; ?>">                                    
                                            <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                            <?php if ($canEditLoanDsbmnt === true) { ?>                                    
                                                <td class="lovtd">
                                                    <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="Edit Disbursement" 
                                                            onclick="getDisbursementForm('myFormsModalLg', 'myFormsModalBodyLg', 'myFormsModalTitleLg', 'Edit Disbursement', 15, <?php echo $subPgNo; ?>,0,'EDIT', <?php echo $row[0]; ?>,'indCustTable','indCustTableRow<?php echo $cntr; ?>');" style="padding:2px !important;" style="padding:2px !important;">
                                                        <!--<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>-->
                                                        <img src="cmn_images/edit32.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                    </button>
                                                </td>
                                            <?php } ?>
                                            <?php if ($canDelLoanDsbmnt === true) { ?>    
                                            <td class="lovtd">
                                                <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="Delete Disbursement" onclick="deleteLoanDisbursement(<?php echo $row[0]; ?>,'<?php echo $row[6]; ?>')" style="padding:2px !important;" style="padding:2px !important;">
                                                    <!--<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>-->
                                                    <img src="cmn_images/delete.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                </button>
                                            </td>
                                            <?php } ?>
                                            <td class="lovtd"><?php echo $row[1]; ?></td>
                                            <td class="lovtd"><?php echo $row[2]; ?></td>
                                            <td class="lovtd"><?php echo $row[3]; ?></td>
                                            <td class="lovtd" style="text-align:center !important;font-weight: bold;"><?php echo $row[4]; ?></td>
                                            <td class="lovtd" style="font-weight: bold;"><?php echo $crncy; ?></td>
                                            <td  style="text-align:right; color:blue !important;font-weight: bold;"><?php echo number_format($row[5],2); ?></td>
                                            <td class="lovtd"><?php echo $row[6]; ?></td>
                                            <td class="lovtd">
                                                <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Customer Account" 
                                                        onclick="getDisbursementForm('myFormsModalLg', 'myFormsModalBodyLg', 'myFormsModalTitleLg', 'Edit Disbursement', 15, <?php echo $subPgNo; ?>,0,'VIEW', <?php echo $row[0]; ?>,'indCustTable','indCustTableRow<?php echo $cntr; ?>');" style="padding:2px !important;" style="padding:2px !important;">
                                                    <!--<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>-->
                                                    <img src="cmn_images/kghostview.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
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
                    </fieldset>
                </form>
                <?php
            } 
        }  
        else if ($subPgNo == 4.3) {//LOAN REPAYMENT TRANSACTIONS
            echo $cntent .= "
					<li onclick=\"openATab('#allmodules', 'grp=17&typ=1&pg=4&subPgNo=4.3');\">
                                                <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
						<span style=\"text-decoration:none;\">Loan Repayment</span>
                                        </li></div>"; 
            $error = "";
            $searchAll = true;

            $srchFor = isset($_POST['searchfor']) ? cleanInputData($_POST['searchfor']) : '';
            $srchIn = isset($_POST['searchin']) ? cleanInputData($_POST['searchin']) : 'Both';
            $pageNo = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
            $lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 10;
            $sortBy = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : "Transaction Date DESC";
            //$trnsType = isset($_POST['trnsType']) ? cleanInputData($_POST['trnsType']) : "LOAN REPAYMENT";
            $trnsType = "LOAN_REPAY";//"DEPOSIT";

            if (strpos($srchFor, "%") === FALSE) {
                $srchFor = "%" . str_replace(" ", "%", $srchFor) . "%";
                $srchFor = str_replace("%%", "%", $srchFor);
            }

            if ($vwtyp == 0) {
                $canAddPrsn = test_prmssns($dfltPrvldgs[7], $mdlNm);
                /* echo $cntent . "<li>
                  <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                  <span style=\"text-decoration:none;\">Data Administration</span>
                  </li>
                  </ul>
                  </div>"; */
                $total = get_LoanRpmntTransactionsTtl($srchFor, $srchIn, $orgID, $searchAll, $trnsType);
                if ($pageNo > ceil($total / $lmtSze)) {
                    $pageNo = 1;
                } else if ($pageNo < 1) {
                    $pageNo = ceil($total / $lmtSze);
                }

                $curIdx = $pageNo - 1;
                $result = get_LoanRpmntTransactions($srchFor, $srchIn, $curIdx, $lmtSze, $orgID, $searchAll, $sortBy, $trnsType);
                $cntr = 0;
                $colClassType1 = "col-lg-2";
                //$colClassType2 = "col-lg-8";
                ?> 
                <form id='mcfAcntTrnsForm' action='' method='post' accept-charset='UTF-8'>
                    <input class="form-control" id="tblRowID" type = "hidden" placeholder="ROW ID"/>                     
                    <fieldset class=""><legend class="basic_person_lg1" style="color: #003245">LOAN REPAYMENT TRANSACTIONS</legend>
                        <div class="row" style="margin-bottom:0px;">
                            <?php
                            $colClassType1 = "col-lg-2";
                            $colClassType2 = "col-lg-3";
                            if ($canAddLoanRpmnt === true) {
                                ?>   
                                <div class="<?php echo $colClassType1; ?>" style="padding:0px 1px 0px 15px !important;">                    
                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="getCoreBankingForm('myFormsModalLg', 'myFormsModalBodyLg', 'myFormsModalTitleLg', 'Add New Savings Product', 15, '<?php echo $subPgNo; ?>', 0, 'ADD', -1);">
                                        <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                        New Repayment
                                    </button>
                                </div>
                                <?php
                            }
                            ?>

                                                                                                <!--<div class="<?php echo $colClassType1; ?>" style="padding:0px 15px 0px 15px !important;">
                                                                                                    <div class="input-group">
                                                                                                        <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                                                                                        <select data-placeholder="Select..." class="form-control chosen-select" id="trnsType" style="min-width:70px !important;">                            
                            <?php
                            $valslctdArry = array("");
                            $dsplySzeArry = array("LOAN REPAYMENT");
                            for ($y = 0; $y < count($dsplySzeArry); $y++) {
                                if ($otherPrsnType == $dsplySzeArry[$y]) {
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
                                                                                                </div>-->                         
                            <div class="<?php echo $colClassType2; ?>" style="padding:0px 15px 0px 15px !important;">
                                <div class="input-group">
                                    <input class="form-control" id="mcfAcntTrnsSrchFor" type = "text" placeholder="Search For" value="<?php
                                    echo trim(str_replace("%", " ", $srchFor));
                                    ?>" onkeyup="enterKeyFuncCust(event, '', '#allmodules', 'grp=17&typ=1&pg=4&subPgNo=4.3', 'mcfAcntTrns')">
                                    <input id="mcfAcntTrnsPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getCustData('clear', '#allmodules', 'grp=17&typ=1&pg=4&subPgNo=4.3', 'mcfAcntTrns')">
                                        <span class="glyphicon glyphicon-remove"></span>
                                    </label>
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getCustData('', '#allmodules', 'grp=17&typ=1&pg=4&subPgNo=4.3', 'mcfAcntTrns')">
                                        <span class="glyphicon glyphicon-search"></span>
                                    </label> 
                                </div>
                            </div>
                            <div class="<?php echo $colClassType2; ?>">
                                <div class="input-group">
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="mcfAcntTrnsSrchIn">
                                        <?php
                                        $valslctdArry = array("", "", "", "", "");
                                        $srchInsArrys = array("Account Number", "Account Title", "Transaction Date", "Transaction Type", "Status");
                                        for ($z = 0; $z < count($srchInsArrys); $z++) {
                                            if ($srchIn == $srchInsArrys[$z]) {
                                                $valslctdArry[$z] = "selected";
                                            }
                                            ?>
                                            <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                        <?php } ?>
                                    </select>
                                    <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="mcfAcntTrnsDsplySze" style="min-width:70px !important;">                            
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
                            <div class="<?php echo $colClassType1; ?>">
                                <div class="input-group">                        
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-sort-by-attributes"></span></span>
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="mcfAcntTrnsSortBy">
                                        <?php
                                        $valslctdArry = array("", "", "", "", "");
                                        $srchInsArrys = array("Account Number", "Account Title ASC", "Transaction Type ASC", "Account Title DESC", "Transaction Date DESC");
                                        for ($z = 0; $z < count($srchInsArrys); $z++) {
                                            if ($sortBy == $srchInsArrys[$z]) {
                                                $valslctdArry[$z] = "selected";
                                            }
                                            ?>
                                            <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="<?php echo $colClassType1; ?>">
                                <nav aria-label="Page navigation">
                                    <ul class="pagination" style="margin: 0px !important;">
                                        <li>
                                            <a href="javascript:getCustData('previous', '#allmodules', 'grp=17&typ=1&pg=4&subPgNo=4.3','mcfAcntTrns');" aria-label="Previous">
                                                <span aria-hidden="true">&laquo;</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="javascript:getCustData('next', '#allmodules', 'grp=17&typ=1&pg=4&subPgNo=4.3','mcfAcntTrns');" aria-label="Next">
                                                <span aria-hidden="true">&raquo;</span>
                                            </a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div>                  
                        <div class="row"> 
                            <div  class="col-md-12">
                                <table class="table table-striped table-bordered table-responsive" id="allMcfTrnsHdrsTable" cellspacing="0" width="100%" style="width:100%;">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <?php if ($canEditLoanRpmnt === true) { ?>
                                                <th>...</th>
                                            <?php } ?>
                                            <?php if (/*$canDelLoanRpmnt === true*/ 1 == 2) { ?>
                                                <th>...</th>
                                            <?php } ?>
                                            <th>Trans. Type - No.</th>		
                                            <th>Account Number</th>
                                            <th>Account Title</th>
                                            <th>Trans. Date</th>
                                            <th>CUR.</th>
                                            <th style="text-align:right;">Amount</th>
                                            <th>Status</th>
                                            <th>...</th>
                                            <!--<th>...</th>-->
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        while ($row = loc_db_fetch_array($result)) {
                                            /**/
                                            $cntr += 1;
                                            ?>
                                            <tr id="allMcfTrnsHdrsRow_<?php echo $cntr; ?>">                                    
                                                <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                                <?php if ($canEditLoanRpmnt === true) { ?>                                    
                                                    <td class="lovtd">
                                                        <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="Edit Transaction" 
                                                                onclick="getCoreBankingForm('myFormsModalLg', 'myFormsModalBodyLg', 'myFormsModalTitleLg', 'LOAN REPAYMENT TRANSACTION', 15, '<?php echo $subPgNo; ?>', 0, 'EDIT', <?php echo $row[0]; ?>);" style="padding:2px !important;" style="padding:2px !important;">
                                                            <!--<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>-->
                                                            <img src="cmn_images/edit32.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                        </button>
                                                    </td>
                                                <?php } ?>
                                                <?php if (/*$canDelLoanRpmnt === true*/ 1 == 2) { ?>     
                                                <td class="lovtd">
						    <?php if ($row[7] == "Not Submitted") { ?>  
                                                    <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="Delete Transaction" onclick="delMCFTrnsHdr_LoanRpmnt('allMcfTrnsHdrsRow_<?php echo $cntr; ?>');" style="padding:2px !important;" style="padding:2px !important;">
                                                        <!--<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>-->
                                                        <img src="cmn_images/no.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                    </button>
                                                    <input type="hidden" id="allMcfTrnsHdrsRow<?php echo $cntr; ?>_HdrID" name="allMcfTrnsHdrsRow<?php echo $cntr; ?>_HdrID" value="<?php echo $row[0]; ?>">
						    <?php } ?>
                                                </td>
                                                <?php } ?>
                                                <td class="lovtd"><?php echo $row[5] . " - " . $row[12]; ?></td>
                                                <td class="lovtd"><?php echo $row[1]; ?></td>
                                                <td class="lovtd"><?php echo $row[2]; ?></td>
                                                <td class="lovtd"><?php echo $row[11]; ?></td>
                                                <td class="lovtd" style="text-align:right;font-weight: bold;color:black;"><?php echo $row[10]; ?></td>
                                                <td class="lovtd" style="text-align:right;font-weight: bold;color:blue;"><?php
                                                    echo number_format((float) $row[6], 2);
                                                    ?></td>
                                                <td class="lovtd"><?php echo $row[7]; ?></td>
                                                <td class="lovtd">
                                                    <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Transaction" onclick="getCoreBankingForm('myFormsModalLg', 'myFormsModalBodyLg', 'myFormsModalTitleLg', 'LOAN REPAYMENT TRANSACTION', 15, '<?php echo $subPgNo; ?>', 0, 'VIEW', <?php echo $row[0]; ?>);" style="padding:2px !important;" style="padding:2px !important;">
                                                        <!--<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>-->
                                                        <img src="cmn_images/kghostview.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
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
                    </fieldset>
                </form>
                <?php
            }
        }
        else if ($subPgNo == 4.4) {}
        else if ($subPgNo == 4.5) {
           $canAddCashLoanRpmnt = test_prmssns($dfltPrvldgs[151], $mdlNm); 
            $canEditCashLoanRpmnt = test_prmssns($dfltPrvldgs[152], $mdlNm); 
            $canDelCashLoanRpmnt = test_prmssns($dfltPrvldgs[153], $mdlNm); 
            $canViewCashLoanRpmnt = test_prmssns($dfltPrvldgs[28], $mdlNm);
            echo $cntent .= "
					<li onclick=\"openATab('#allmodules', 'grp=17&typ=1&pg=4&subPgNo=4.5');\">
                                                <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
						<span style=\"text-decoration:none;\">Loan Payment Transactions</span>
                                        </li></div>";
            $error = "";
            $searchAll = true;

            $srchFor = isset($_POST['searchfor']) ? cleanInputData($_POST['searchfor']) : '';
            $srchIn = isset($_POST['searchin']) ? cleanInputData($_POST['searchin']) : 'Both';
            $pageNo = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
            $lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 10;
            $sortBy = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : "Transaction Date DESC";
            $trnsType = "LOAN_PYMNT";

            if (strpos($srchFor, "%") === FALSE) {
                $srchFor = "%" . str_replace(" ", "%", $srchFor) . "%";
                $srchFor = str_replace("%%", "%", $srchFor);
            }

            if ($vwtyp == 0) {
                $canAddPrsn = test_prmssns($dfltPrvldgs[7], $mdlNm);
                $total = get_LoanRpmntTransactionsTtl($srchFor, $srchIn, $orgID, $searchAll, $trnsType);
                if ($pageNo > ceil($total / $lmtSze)) {
                    $pageNo = 1;
                } else if ($pageNo < 1) {
                    $pageNo = ceil($total / $lmtSze);
                }

                $curIdx = $pageNo - 1;
                $result = get_LoanRpmntTransactions($srchFor, $srchIn, $curIdx, $lmtSze, $orgID, $searchAll, $sortBy, $trnsType);
                $cntr = 0;
                $colClassType1 = "col-lg-2";
                $colClassType2 = "col-lg-3";
                ?> 
                <form id='mcfAcntTrnsForm' action='' method='post' accept-charset='UTF-8'>
                    <!--ROW ID-->
                    <input class="form-control" id="tblRowID" type = "hidden" placeholder="ROW ID"/>                     
                    <fieldset class=""><legend class="basic_person_lg1" style="color: #003245">LOAN PAYMENT TRANSACTIONS</legend>
                        <div class="row" style="margin-bottom:0px;">
                            <?php
                            if ($canAddCashLoanRpmnt === true) {
                                ?>   
                                <div class="<?php echo $colClassType1; ?>" style="padding:0px 1px 0px 15px !important;">                    
                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="getCoreBankingForm('myFormsModalLg', 'myFormsModalBodyLg', 'myFormsModalTitleLg', 'LOAN PAYMENT TRANSACTION', 15, '<?php echo $subPgNo; ?>', 0, 'ADD', -1);">
                                        <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                        New Payment
                                    </button>
                                </div>
                                <?php
                            } else {
                                $colClassType1 = "col-lg-2";
                                $colClassType2 = "col-lg-4";
                            }
                            ?>
                            <div class="<?php echo $colClassType2; ?>" style="padding:0px 15px 0px 15px !important;">
                                <div class="input-group">
                                    <input class="form-control" id="mcfAcntTrnsSrchFor" type = "text" placeholder="Search For" value="<?php
                                    echo trim(str_replace("%", " ", $srchFor));
                                    ?>" onkeyup="enterKeyFuncCust(event, '', '#allmodules', 'grp=17&typ=1&pg=4&subPgNo=4.5', 'mcfAcntTrns')">
                                    <input id="mcfAcntTrnsPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getCustData('clear', '#allmodules', 'grp=17&typ=1&pg=4&subPgNo=4.5', 'mcfAcntTrns')">
                                        <span class="glyphicon glyphicon-remove"></span>
                                    </label>
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getCustData('', '#allmodules', 'grp=17&typ=1&pg=4&subPgNo=4.5', 'mcfAcntTrns')">
                                        <span class="glyphicon glyphicon-search"></span>
                                    </label> 
                                </div>
                            </div>
                            <div class="<?php echo $colClassType2; ?>">
                                <div class="input-group">
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="mcfAcntTrnsSrchIn">
                                        <?php
                                        $valslctdArry = array("", "", "", "", "");
                                        $srchInsArrys = array("Account Number", "Account Title", "Transaction Date"
                                            , "Transaction Type", "Status");
                                        for ($z = 0; $z < count($srchInsArrys); $z++) {
                                            if ($srchIn == $srchInsArrys[$z]) {
                                                $valslctdArry[$z] = "selected";
                                            }
                                            ?>
                                            <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                        <?php } ?>
                                    </select>
                                    <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="mcfAcntTrnsDsplySze" style="min-width:70px !important;">                            
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
                            <div class="<?php echo $colClassType1; ?>">
                                <div class="input-group">                        
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-sort-by-attributes"></span></span>
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="mcfAcntTrnsSortBy">
                                        <?php
                                        $valslctdArry = array("", "", "", "", "");
                                        $srchInsArrys = array("Account Number", "Account Title ASC", "Transaction Type ASC", "Account Title DESC", "Transaction Date DESC");
                                        for ($z = 0; $z < count($srchInsArrys); $z++) {
                                            if ($sortBy == $srchInsArrys[$z]) {
                                                $valslctdArry[$z] = "selected";
                                            }
                                            ?>
                                            <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="<?php echo $colClassType1; ?>">
                                <nav aria-label="Page navigation">
                                    <ul class="pagination" style="margin: 0px !important;">
                                        <li>
                                            <a href="javascript:getCustData('previous', '#allmodules', 'grp=17&typ=1&pg=4&subPgNo=4.5','mcfAcntTrns');" aria-label="Previous">
                                                <span aria-hidden="true">&laquo;</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="javascript:getCustData('next', '#allmodules', 'grp=17&typ=1&pg=4&subPgNo=4.5','mcfAcntTrns');" aria-label="Next">
                                                <span aria-hidden="true">&raquo;</span>
                                            </a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div>                        
                        <div class="row"> 
                            <div  class="col-md-12">
                                <table class="table table-striped table-bordered table-responsive" id="allMcfTrnsHdrsTable" cellspacing="0" width="100%" style="width:100%;">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <?php if ($canEditCashLoanRpmnt === true) { ?>
                                                <th>...</th>
                                            <?php } ?>
                                            <?php if ($canDelCashLoanRpmnt === true) { ?>
                                                <th>...</th>
                                            <?php } ?>
                                            <th>Trans. Type - No.</th>		
                                            <th>Account Number</th>
                                            <th>Account Title</th>
                                            <th>Trans. Date</th>
                                            <th>CUR.</th>
                                            <th style="text-align:right;">Amount</th>
                                            <th>Status</th>
                                            <th>...</th>
                                            <!--<th>...</th>-->
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        while ($row = loc_db_fetch_array($result)) {
                                            /**/
                                            $cntr += 1;
                                            ?>
                                            <tr id="allMcfTrnsHdrsRow_<?php echo $cntr; ?>">                                    
                                                <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                                <?php if ($canEditCashLoanRpmnt === true) { ?>                                    
                                                    <td class="lovtd">
                                                        <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="Edit Transaction" 
                                                                onclick="getCoreBankingForm('myFormsModalLg', 'myFormsModalBodyLg', 'myFormsModalTitleLg', 'LOAN PAYMENT TRANSACTION', 15, '<?php echo $subPgNo; ?>', 0, 'EDIT', <?php echo $row[0]; ?>);" style="padding:2px !important;" style="padding:2px !important;">
                                                            <!--<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>-->
                                                            <img src="cmn_images/edit32.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                        </button>
                                                    </td>
                                                <?php } ?>
                                                <?php if ($canDelCashLoanRpmnt === true) { ?>
                                                <td class="lovtd">
                                                    <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="Delete Transaction" onclick="delMCFTrnsHdr('allMcfTrnsHdrsRow_<?php echo $cntr; ?>');" style="padding:2px !important;" style="padding:2px !important;">
                                                        <!--<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>-->
                                                        <img src="cmn_images/no.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                    </button>
                                                    <input type="hidden" id="allMcfTrnsHdrsRow<?php echo $cntr; ?>_HdrID" name="allMcfTrnsHdrsRow<?php echo $cntr; ?>_HdrID" value="<?php echo $row[0]; ?>">
                                                </td>
                                                 <?php } ?>
                                                <td class="lovtd"><?php echo $row[5] . " - " . $row[12]; ?></td>
                                                <td class="lovtd"><?php echo $row[1]; ?></td>
                                                <td class="lovtd"><?php echo $row[2]; ?></td>
                                                <td class="lovtd"><?php echo $row[11]; ?></td>
                                                <td class="lovtd" style="text-align:right;font-weight: bold;color:black;"><?php echo $row[10]; ?></td>
                                                <td class="lovtd" style="text-align:right;font-weight: bold;color:blue;"><?php
                                                    echo number_format((float) $row[6], 2);
                                                    ?></td>
                                                <td class="lovtd"><?php echo $row[7]; ?></td>
                                                <td class="lovtd">
                                                    <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Transaction" onclick="getCoreBankingForm('myFormsModalLg', 'myFormsModalBodyLg', 'myFormsModalTitleLg', 'LOAN PAYMENT TRANSACTION', 15, '<?php echo $subPgNo; ?>', 0, 'VIEW', <?php echo $row[0]; ?>);" style="padding:2px !important;" style="padding:2px !important;">
                                                        <!--<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>-->
                                                        <img src="cmn_images/kghostview.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
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
                    </fieldset>
                </form>
                <?php
            }
        }
        else if ($subPgNo == 4.6) {//CREDIT RISK ASSESSMENT
//        echo $cntent .= "
//					<li onclick=\"openATab('#allmodules', 'grp=17&typ=1&pg=4&subPgNo=4.1');\">
//                                                <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
//						<span style=\"text-decoration:none;\">Loan Applications</span>
//                                        </li></div>";
        
        $cntent .= "
					<li onclick=\"openATab('#allmodules', 'grp=17&typ=1&pg=4&subPgNo=4.6');\">
                                                <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
						<span style=\"text-decoration:none;\">Credit Risk Assessment</span>
                                        </li>";        
        
            if($subVwtyp == 1){
                //CREDIT ASSESSMENTS
                 
            } 
            else if($subVwtyp == 2){
                $canAddRiskFactor = test_prmssns($dfltPrvldgs[265], $mdlNm); 
                $canEditRiskFactor = test_prmssns($dfltPrvldgs[266], $mdlNm); 
                $canDelRiskFactor = test_prmssns($dfltPrvldgs[267], $mdlNm);
                $canViewRiskFactor = test_prmssns($dfltPrvldgs[90], $mdlNm);   
            
                echo $cntent .= "<li onclick=\"openATab('#allmodules', 'grp=17&typ=1&pg=4&subPgNo=4.6&subVwtyp=2');\">
                                                                <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                                                <span style=\"text-decoration:none;\">Risk Factors</span>
                            </li></div>";                  
                //var_dump($_POST);
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

                if ($vwtyp == 0) {
                    $canAddPrsn = test_prmssns($dfltPrvldgs[7], $mdlNm);
                    /*echo $cntent . "<li>
                                                    <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                                    <span style=\"text-decoration:none;\">Data Administration</span>
                                            </li>
                                           </ul>
                                         </div>";*/
                    $total = getCreditRiskFactorTblTtl($isEnabledOnly, $srchFor, $srchIn, $orgID, $searchAll);
                    if ($pageNo > ceil($total / $lmtSze)) {
                        $pageNo = 1;
                    } else if ($pageNo < 1) {
                        $pageNo = ceil($total / $lmtSze);
                    }

                    $curIdx = $pageNo - 1;
                    $result = getCreditRiskFactorTbl($isEnabledOnly, $srchFor, $srchIn, $curIdx, $lmtSze, $orgID, $searchAll, $sortBy);
                    $cntr = 0;
                    $colClassType1 = "col-lg-2";
                    $colClassType2 = "col-lg-3";
                    ?> 
                    <form id='riskFactorsForm' action='' method='post' accept-charset='UTF-8'>
                        <!--ROW ID-->
                        <input class="form-control" id="tblRowID" type = "hidden" placeholder="ROW ID"/>                     
                        <fieldset class="basic_person_fs1"><legend class="basic_person_lg" style="color: #003245">RISK FACTORS</legend>
                        <div class="row" style="margin-bottom:1px;">
                            <div class="col-lg-3" style="padding:0px 15px 0px 15px !important;">
                                <div class="input-group">
                                    <input class="form-control" id="riskFactorsSrchFor" type = "text" placeholder="Search For" value="<?php echo trim(str_replace("%", " ", $srchFor)); ?>" onkeyup="enterKeyFuncCust(event, '', '#allmodules', 'grp=17&typ=1&pg=4&subPgNo=4.6&subVwtyp=2','riskFactors')">
                                    <input id="riskFactorsPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getCustData('clear', '#allmodules', 'grp=17&typ=1&pg=4&subPgNo=4.6&subVwtyp=2','riskFactors')">
                                        <span class="glyphicon glyphicon-remove"></span>
                                    </label>
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getCustData('', '#allmodules', 'grp=17&typ=1&pg=4&subPgNo=4.6&subVwtyp=2','riskFactors')">
                                        <span class="glyphicon glyphicon-search"></span>
                                    </label> 
                                </div>
                            </div>
                            <div  class="col-lg-3">
                                <div class="input-group">
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="riskFactorsSrchIn">
                                        <?php
                                        $valslctdArry = array("", "");
                                        $srchInsArrys = array("Risk Factor","Description");
                                        for ($z = 0; $z < count($srchInsArrys); $z++) {
                                            if ($srchIn == $srchInsArrys[$z]) {
                                                $valslctdArry[$z] = "selected";
                                            }
                                            ?>
                                            <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                        <?php } ?>
                                    </select>
                                    <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="riskFactorsDsplySze" style="min-width:70px !important;">                            
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
                            <div  class="col-lg-2">
                                <div class="input-group">                        
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-sort-by-attributes"></span></span>
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="riskFactorsSortBy">
                                        <?php
                                        $valslctdArry = array("", "", "");
                                        $srchInsArrys = array("Date Added DESC", "Risk Factor ASC", "Risk Factor DESC");
                                        for ($z = 0; $z < count($srchInsArrys); $z++) {
                                            if ($sortBy == $srchInsArrys[$z]) {
                                                $valslctdArry[$z] = "selected";
                                            }
                                            ?>
                                            <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-2" style="padding: 5px 1px 0px 15px !important">
                                <div class="form-check" style="font-size: 12px !important;">
                                    <label class="form-check-label">
                                        <?php
                                        $nonAprvdChekd = "";
                                        if ($isEnabledOnly == "true") {
                                            $nonAprvdChekd = "checked=\"true\"";
                                        }
                                        ?>
                                        <input type="checkbox" class="form-check-input" onclick="getCustData('', '#allmodules', 'grp=17&typ=1&pg=4&subPgNo=4.6&subVwtyp=2','riskFactors');" id="riskFactorsIsEnabled" name="riskFactorsIsEnabled" <?php echo $nonAprvdChekd; ?>>
                                        Enabled Risk Factors?
                                    </label>
                                </div>                             
                            </div>                            
                            <div  class="col-lg-2">
                                <nav aria-label="Page navigation">
                                    <ul class="pagination" style="margin: 0px !important;">
                                        <li>
                                            <a href="javascript:getCustData('previous', '#allmodules', 'grp=17&typ=1&pg=4&subPgNo=4.6&subVwtyp=2','riskFactors');" aria-label="Previous">
                                                <span aria-hidden="true">&laquo;</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="javascript:getCustData('next', '#allmodules', 'grp=17&typ=1&pg=4&subPgNo=4.6&subVwtyp=2','riskFactors');" aria-label="Next">
                                                <span aria-hidden="true">&raquo;</span>
                                            </a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                        <div class="row " style="margin-bottom:2px;padding:2px 15px 2px 15px !important">   
                            <div class="col-md-12" style="padding:2px 1px 2px 1px !important;border-top:1px solid #ddd;border-bottom:1px solid #ddd;">
                                <div class="col-md-12" style="padding:0px 1px 0px 1px !important; float:left !important;">
                                    <?php
                                    if ($canAddRiskFactor === true) {
                                        ?>
                                    <button type="button" class="btn btn-default btn-sm" onclick="getRiskFactorForm('myFormsModalx', 'myFormsModalxBody', 'myFormsModalxTitle', 'Add Risk Factor', 15, <?php echo $subPgNo; ?>, <?php echo $subVwtyp; ?>,0,'ADD', -1,'indCustTable');">
                                        <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                        New Risk Factor
                                    </button>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>                            
                        <div class="row"> 
                            <div  class="col-md-12">
                                <table class="table table-striped table-bordered table-responsive" id="indCustTable" cellspacing="0" width="100%" style="width:100%;">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <?php if ($canEditRiskFactor === true) { ?>
                                                <th>...</th>
                                            <?php } ?>
                                                <?php if ($canDelRiskFactor === true) { ?>
                                                <th>...</th>
                                            <?php } ?>
                                            <th>Risk Factor</th>
                                            <th>Description</th>
                                            <th>Is Enabled?</th>
                                            <th>...</th>
                                            <!--<th>...</th>-->
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        while ($row = loc_db_fetch_array($result)) {
                                            /**/
                                            $cntr += 1;

                                            ?>
                                            <tr id="indCustTableRow<?php echo $cntr; ?>">                                    
                                                <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                                <?php if ($canEditRiskFactor === true) { ?>                                    
                                                    <td class="lovtd">
                                                        <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="Edit Risk Factor" 
                                                                onclick="getRiskFactorForm('myFormsModalx', 'myFormsModalxBody', 'myFormsModalxTitle', 'Edit Risk Factor', 15, <?php echo $subPgNo; ?>, <?php echo $subVwtyp; ?>,0,'EDIT', <?php echo $row[0]; ?>,'indCustTable','indCustTableRow<?php echo $cntr; ?>');" style="padding:2px !important;" style="padding:2px !important;">
                                                            <!--<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>-->
                                                            <img src="cmn_images/edit32.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                        </button>
                                                    </td>
                                                <?php } ?>
                                                <?php if ($canDelRiskFactor === true) { ?>        
                                                <td class="lovtd">
                                                    <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="Delete Risk Factor" onclick="deleteRiskFactor(<?php echo $row[0]; ?>)" style="padding:2px !important;" style="padding:2px !important;">
                                                        <!--<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>-->
                                                        <img src="cmn_images/delete.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                    </button>
                                                </td>
                                                <?php } ?>
                                                <td class="lovtd"><?php echo $row[1]; ?></td>
                                                <td class="lovtd"><?php echo $row[2]; ?></td>
                                                <td class="lovtd"><?php echo $row[3]; ?></td>
                                                <td class="lovtd">
                                                    <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Risk Factor" onclick="getRiskFactorForm('myFormsModalx', 'myFormsModalxBody', 'myFormsModalxTitle', 'View Risk Factor', 15, <?php echo $subPgNo; ?>, <?php echo $subVwtyp; ?>,0,'VIEW', <?php echo $row[0]; ?>,'indCustTable','indCustTableRow<?php echo $cntr; ?>');" style="padding:2px !important;" style="padding:2px !important;">
                                                        <!--<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>-->
                                                        <img src="cmn_images/kghostview.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
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
                        </fieldset>
                    </form>
                    <?php
                }                   
            } else if($subVwtyp == 3){
                //RISK PROFILE
            } else if($subVwtyp == 4){
                //ASSESSMENT SET
            }
        } 
        else if ($subPgNo == 4.7) {
            $canAddPrptyColt = test_prmssns($dfltPrvldgs[274], $mdlNm); 
            $canEditPrptyColt = test_prmssns($dfltPrvldgs[275], $mdlNm); 
            $canDelPrptyColt = test_prmssns($dfltPrvldgs[276], $mdlNm); 
            $canViewPrptyColt = test_prmssns($dfltPrvldgs[246], $mdlNm);
            echo $cntent .= "<li onclick=\"openATab('#allmodules', 'grp=17&typ=1&pg=4&subPgNo=4.7');\">
                                                            <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                                            <span style=\"text-decoration:none;\">Property Collaterals</span>
                        </li></div>";                  
            //var_dump($_POST);
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

            if ($vwtyp == 0) {
                $canAddPrsn = test_prmssns($dfltPrvldgs[7], $mdlNm);
                /*echo $cntent . "<li>
                                                <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                                <span style=\"text-decoration:none;\">Data Administration</span>
                                        </li>
                                       </ul>
                                     </div>";*/
                $total = getPropertyCollateralTblTtl($isEnabledOnly, $srchFor, $srchIn, $orgID, $searchAll);
                if ($pageNo > ceil($total / $lmtSze)) {
                    $pageNo = 1;
                } else if ($pageNo < 1) {
                    $pageNo = ceil($total / $lmtSze);
                }

                $curIdx = $pageNo - 1;
                $result = getPropertyCollateralTbl($isEnabledOnly, $srchFor, $srchIn, $curIdx, $lmtSze, $orgID, $searchAll, $sortBy);
                $cntr = 0;
                $colClassType1 = "col-lg-2";
                $colClassType2 = "col-lg-3";
                ?> 
                <form id='prptyColtsForm' action='' method='post' accept-charset='UTF-8'>
                    <!--ROW ID-->
                    <input class="form-control" id="tblRowID" type = "hidden" placeholder="ROW ID"/>                     
                    <fieldset class="basic_person_fs1"><legend class="basic_person_lg" style="color: #003245">PROPERTY COLLATERAL</legend>
                    <div class="row" style="margin-bottom:1px;">
                        <div class="col-lg-3" style="padding:0px 15px 0px 15px !important;">
                            <div class="input-group">
                                <input class="form-control" id="prptyColtsSrchFor" type = "text" placeholder="Search For" value="<?php echo trim(str_replace("%", " ", $srchFor)); ?>" onkeyup="enterKeyFuncCust(event, '', '#allmodules', 'grp=17&typ=1&pg=4&subPgNo=4.7','prptyColts')">
                                <input id="prptyColtsPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                <label class="btn btn-primary btn-file input-group-addon" onclick="getCustData('clear', '#allmodules', 'grp=17&typ=1&pg=4&subPgNo=4.7','prptyColts')">
                                    <span class="glyphicon glyphicon-remove"></span>
                                </label>
                                <label class="btn btn-primary btn-file input-group-addon" onclick="getCustData('', '#allmodules', 'grp=17&typ=1&pg=4&subPgNo=4.7','prptyColts')">
                                    <span class="glyphicon glyphicon-search"></span>
                                </label> 
                            </div>
                        </div>
                        <div  class="col-lg-3">
                            <div class="input-group">
                                <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                <select data-placeholder="Select..." class="form-control chosen-select" id="prptyColtsSrchIn">
                                    <?php
                                    $valslctdArry = array("", "");
                                    $srchInsArrys = array("Property Collateral","Description");
                                    for ($z = 0; $z < count($srchInsArrys); $z++) {
                                        if ($srchIn == $srchInsArrys[$z]) {
                                            $valslctdArry[$z] = "selected";
                                        }
                                        ?>
                                        <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                    <?php } ?>
                                </select>
                                <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                <select data-placeholder="Select..." class="form-control chosen-select" id="prptyColtsDsplySze" style="min-width:70px !important;">                            
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
                        <div  class="col-lg-2">
                            <div class="input-group">                        
                                <span class="input-group-addon"><span class="glyphicon glyphicon-sort-by-attributes"></span></span>
                                <select data-placeholder="Select..." class="form-control chosen-select" id="prptyColtsSortBy">
                                    <?php
                                    $valslctdArry = array("", "", "");
                                    $srchInsArrys = array("Date Added DESC", "Property Name ASC", "Property Name DESC");
                                    for ($z = 0; $z < count($srchInsArrys); $z++) {
                                        if ($sortBy == $srchInsArrys[$z]) {
                                            $valslctdArry[$z] = "selected";
                                        }
                                        ?>
                                        <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-2" style="padding: 5px 1px 0px 15px !important">
                            <div class="form-check" style="font-size: 12px !important;">
                                <label class="form-check-label">
                                    <?php
                                    $nonAprvdChekd = "";
                                    if ($isEnabledOnly == "true") {
                                        $nonAprvdChekd = "checked=\"true\"";
                                    }
                                    ?>
                                    <input type="checkbox" class="form-check-input" onclick="getCustData('', '#allmodules', 'grp=17&typ=1&pg=4&subPgNo=4.7','prptyColts');" id="prptyColtsIsEnabled" name="prptyColtsIsEnabled" <?php echo $nonAprvdChekd; ?>>
                                    Is Unauthorized?
                                </label>
                            </div>                             
                        </div>                            
                        <div  class="col-lg-2">
                            <nav aria-label="Page navigation">
                                <ul class="pagination" style="margin: 0px !important;">
                                    <li>
                                        <a href="javascript:getCustData('previous', '#allmodules', 'grp=17&typ=1&pg=4&subPgNo=4.7','prptyColts');" aria-label="Previous">
                                            <span aria-hidden="true">&laquo;</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:getCustData('next', '#allmodules', 'grp=17&typ=1&pg=4&subPgNo=4.7','prptyColts');" aria-label="Next">
                                            <span aria-hidden="true">&raquo;</span>
                                        </a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                    <div class="row " style="margin-bottom:2px;padding:2px 15px 2px 15px !important">   
                        <div class="col-md-12" style="padding:2px 1px 2px 1px !important;border-top:1px solid #ddd;border-bottom:1px solid #ddd;">
                            <div class="col-md-12" style="padding:0px 1px 0px 1px !important; float:left !important;">
                                <?php
                                if ($canAddPrptyColt === true) {
                                    ?>
                                <button type="button" class="btn btn-default btn-sm" onclick="getPrptyColtsForm('myFormsModalxLG', 'myFormsModalxLGBody', 'myFormsModalxLGTitle', 'Add Property Collateral', 15, <?php echo $subPgNo; ?>,0,'ADD', -1,'indCustTable');">
                                    <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                    New Property Collateral
                                </button>
                                <?php } ?>
                            </div>
                        </div>
                    </div>                            
                    <div class="row"> 
                        <div  class="col-md-12">
                            <table class="table table-striped table-bordered table-responsive" id="indCustTable" cellspacing="0" width="100%" style="width:100%;">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <?php if ($canEditPrptyColt === true) { ?>
                                            <th>...</th>
                                        <?php } ?>
                                        <?php if ($canDelPrptyColt === true) { ?>
                                            <th>...</th>
                                        <?php } ?>
                                        <th>Property Name</th>
                                        <th>Description</th>
                                        <th>Type</th>
                                        <th>Property ID</th>
                                        <th>Owner</th>
                                        <th>Status</th>
                                        <th>...</th>
                                        <!--<th>...</th>-->
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    while ($row = loc_db_fetch_array($result)) {
                                        /**/
                                        $cntr += 1;

                                        ?>
                                        <tr id="indCustTableRow<?php echo $cntr; ?>">                                    
                                            <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                            <?php if ($canEditPrptyColt === true) { ?>                                    
                                                <td class="lovtd">
                                                    <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="Edit Property Collateral" 
                                                            onclick="getPrptyColtsForm('myFormsModalxLG', 'myFormsModalxLGBody', 'myFormsModalxLGTitle', 'Edit Property Collateral', 15, <?php echo $subPgNo; ?>,0,'EDIT', <?php echo $row[0]; ?>,'indCustTable','indCustTableRow<?php echo $cntr; ?>');" style="padding:2px !important;" style="padding:2px !important;">
                                                        <!--<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>-->
                                                        <img src="cmn_images/edit32.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                    </button>
                                                </td>
                                            <?php } ?>
                                            <?php if ($canDelPrptyColt === true) { ?>
                                            <td class="lovtd">
                                                <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="Delete Property Collateral" onclick="deletePrptyColt(<?php echo $row[0]; ?>)" style="padding:2px !important;" style="padding:2px !important;">
                                                    <!--<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>-->
                                                    <img src="cmn_images/delete.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                </button>
                                            </td>
                                            <?php } ?>
                                            <td class="lovtd"><?php echo $row[1]; ?></td>
                                            <td class="lovtd"><?php echo $row[2]; ?></td>
                                            <td class="lovtd"><?php echo $row[3]; ?></td>
                                            <td class="lovtd"><?php echo $row[4]; ?></td>
                                            <td class="lovtd"><?php echo $row[5]; ?></td>
                                            <td class="lovtd"><?php echo $row[6]; ?></td>
                                            <td class="lovtd">
                                                <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Property Collateral" onclick="getPrptyColtsForm('myFormsModalxLG', 'myFormsModalxLGBody', 'myFormsModalxLGTitle', 'View Property Collateral', 15, <?php echo $subPgNo; ?>,0,'VIEW', <?php echo $row[0]; ?>,'indCustTable','indCustTableRow<?php echo $cntr; ?>');" style="padding:2px !important;" style="padding:2px !important;">
                                                    <!--<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>-->
                                                    <img src="cmn_images/kghostview.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
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
                    </fieldset>
                </form>
                <?php
            }                   
        } 
        else if ($subPgNo == 4.8) {
            //SECTOR CLASSIFICATIONS
        }
        else if ($subPgNo == 4.9) {
            $canAddLoanWrtoff = test_prmssns($dfltPrvldgs[280], $mdlNm); 
            $canEditLoanWrtoff = test_prmssns($dfltPrvldgs[281], $mdlNm); 
            $canDelLoanWrtoff = test_prmssns($dfltPrvldgs[282], $mdlNm); 
            $canViewLoanWrtoff = test_prmssns($dfltPrvldgs[283], $mdlNm); 
             
            echo $cntent .= "<li onclick=\"openATab('#allmodules', 'grp=17&typ=1&pg=4&subPgNo=4.9');\">
                                                            <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                                            <span style=\"text-decoration:none;\">Loan Write-Offs</span>
                        </li></div>";                  
            //var_dump($_POST);
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
            $sortBy = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : "Transaction Date DESC";
            if (strpos($srchFor, "%") === FALSE) {
                $srchFor = "%" . str_replace(" ", "%", $srchFor) . "%";
                $srchFor = str_replace("%%", "%", $srchFor);
            }

            if ($vwtyp == 0) {
                $canAddPrsn = test_prmssns($dfltPrvldgs[7], $mdlNm);
                /*echo $cntent . "<li>
                                                <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                                <span style=\"text-decoration:none;\">Data Administration</span>
                                        </li>
                                       </ul>
                                     </div>";*/
                $total = getLoanWriteOffTblTtl($isEnabledOnly, $srchFor, $srchIn, $orgID, $searchAll);
                if ($pageNo > ceil($total / $lmtSze)) {
                    $pageNo = 1;
                } else if ($pageNo < 1) {
                    $pageNo = ceil($total / $lmtSze);
                }

                $curIdx = $pageNo - 1;
                $result = getLoanWriteOffTbl($isEnabledOnly, $srchFor, $srchIn, $curIdx, $lmtSze, $orgID, $searchAll, $sortBy);
                $cntr = 0;
                $colClassType1 = "col-lg-2";
                $colClassType2 = "col-lg-3";
                ?> 
                <form id='loanWriteOffsForm' action='' method='post' accept-charset='UTF-8'>
                    <!--ROW ID-->
                    <input class="form-control" id="tblRowID" type = "hidden" placeholder="ROW ID"/>                     
                    <fieldset class="basic_person_fs1"><legend class="basic_person_lg" style="color: #003245">LOAN WRITE-OFFS</legend>
                    <div class="row" style="margin-bottom:1px;">
                        <div class="col-lg-3" style="padding:0px 15px 0px 15px !important;">
                            <div class="input-group">
                                <input class="form-control" id="loanWriteOffsSrchFor" type = "text" placeholder="Search For" value="<?php echo trim(str_replace("%", " ", $srchFor)); ?>" onkeyup="enterKeyFuncCust(event, '', '#allmodules', 'grp=17&typ=1&pg=4&subPgNo=4.9','loanWriteOffs')">
                                <input id="loanWriteOffsPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                <label class="btn btn-primary btn-file input-group-addon" onclick="getCustData('clear', '#allmodules', 'grp=17&typ=1&pg=4&subPgNo=4.9','loanWriteOffs')">
                                    <span class="glyphicon glyphicon-remove"></span>
                                </label>
                                <label class="btn btn-primary btn-file input-group-addon" onclick="getCustData('', '#allmodules', 'grp=17&typ=1&pg=4&subPgNo=4.9','loanWriteOffs')">
                                    <span class="glyphicon glyphicon-search"></span>
                                </label> 
                            </div>
                        </div>
                        <div  class="col-lg-3">
                            <div class="input-group">
                                <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                <select data-placeholder="Select..." class="form-control chosen-select" id="loanWriteOffsSrchIn">
                                    <?php
                                    $valslctdArry = array("", "");
                                    $srchInsArrys = array("Customer Name","Loan Request");
                                    for ($z = 0; $z < count($srchInsArrys); $z++) {
                                        if ($srchIn == $srchInsArrys[$z]) {
                                            $valslctdArry[$z] = "selected";
                                        }
                                        ?>
                                        <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                    <?php } ?>
                                </select>
                                <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                <select data-placeholder="Select..." class="form-control chosen-select" id="loanWriteOffsDsplySze" style="min-width:70px !important;">                            
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
                        <div  class="col-lg-2">
                            <div class="input-group">                        
                                <span class="input-group-addon"><span class="glyphicon glyphicon-sort-by-attributes"></span></span>
                                <select data-placeholder="Select..." class="form-control chosen-select" id="loanWriteOffsSortBy">
                                    <?php
                                    $valslctdArry = array("");
                                    $srchInsArrys = array("Transaction Date DESC");
                                    for ($z = 0; $z < count($srchInsArrys); $z++) {
                                        if ($sortBy == $srchInsArrys[$z]) {
                                            $valslctdArry[$z] = "selected";
                                        }
                                        ?>
                                        <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-2" style="padding: 5px 1px 0px 15px !important">
                            <div class="form-check" style="font-size: 12px !important;">
                                <label class="form-check-label">
                                    <?php
                                    $nonAprvdChekd = "";
                                    if ($isEnabledOnly == "true") {
                                        $nonAprvdChekd = "checked=\"true\"";
                                    }
                                    ?>
                                    <input type="checkbox" class="form-check-input" onclick="getCustData('', '#allmodules', 'grp=17&typ=1&pg=4&subPgNo=4.9','loanWriteOffs');" id="loanWriteOffsIsEnabled" name="loanWriteOffsIsEnabled" <?php echo $nonAprvdChekd; ?>>
                                    Is Unauthorized?
                                </label>
                            </div>                             
                        </div>                            
                        <div  class="col-lg-2">
                            <nav aria-label="Page navigation">
                                <ul class="pagination" style="margin: 0px !important;">
                                    <li>
                                        <a href="javascript:getCustData('previous', '#allmodules', 'grp=17&typ=1&pg=4&subPgNo=4.9','loanWriteOffs');" aria-label="Previous">
                                            <span aria-hidden="true">&laquo;</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:getCustData('next', '#allmodules', 'grp=17&typ=1&pg=4&subPgNo=4.9','loanWriteOffs');" aria-label="Next">
                                            <span aria-hidden="true">&raquo;</span>
                                        </a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                    <div class="row " style="margin-bottom:2px;padding:2px 15px 2px 15px !important">   
                        <div class="col-md-12" style="padding:2px 1px 2px 1px !important;border-top:1px solid #ddd;border-bottom:1px solid #ddd;">
                            <div class="col-md-12" style="padding:0px 1px 0px 1px !important; float:left !important;">
                                <?php
                                if ($canAddLoanWrtoff === true) {
                                    ?>
                                <button type="button" class="btn btn-default btn-sm" onclick="getLoanWriteOffForm('myFormsModalxLG', 'myFormsModalxLGBody', 'myFormsModalxLGTitle', 'Add Loan Write-Off', 15, <?php echo $subPgNo; ?>,0,'ADD', -1,'indCustTable');">
                                    <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                    New Loan Write-Off
                                </button>
                                <?php } ?>
                            </div>
                        </div>
                    </div>                            
                    <div class="row"> 
                        <div  class="col-md-12">
                            <table class="table table-striped table-bordered table-responsive" id="indCustTable" cellspacing="0" width="100%" style="width:100%;">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <?php if ($canEditLoanWrtoff === true) { ?>
                                            <th>...</th>
                                        <?php } ?>
                                        <?php if ($canDelLoanWrtoff === true) { ?>
                                            <th>...</th>
                                        <?php } ?>
					<th>Transaction ID.</th>
                                        <th>Customer</th>
                                        <th>Loan Request</th>
                                        <th>Transaction Date</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>...</th>
                                        <!--<th>...</th>-->
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    while ($row = loc_db_fetch_array($result)) {
                                        /**/
                                        $cntr += 1;

                                        ?>
                                        <tr id="indCustTableRow<?php echo $cntr; ?>">                                    
                                            <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                            <?php if ($canEditLoanWrtoff === true) { ?>                                    
                                                <td class="lovtd">
                                                    <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="Edit Loan Write-Off" 
                                                            onclick="getLoanWriteOffForm('myFormsModalxLG', 'myFormsModalxLGBody', 'myFormsModalxLGTitle', 'Edit Loan Write-Off', 15, <?php echo $subPgNo; ?>, 0,'EDIT', <?php echo $row[0]; ?>,'indCustTable','indCustTableRow<?php echo $cntr; ?>');" style="padding:2px !important;" style="padding:2px !important;">
                                                        <!--<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>-->
                                                        <img src="cmn_images/edit32.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                    </button>
                                                </td>
                                            <?php } ?>
                                            <?php if ($canDelLoanWrtoff === true) { ?>    
                                            <td class="lovtd">
                                                <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="Delete Loan Write-Off" onclick="deleteRiskFactor(<?php echo $row[0]; ?>)" style="padding:2px !important;" style="padding:2px !important;">
                                                    <!--<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>-->
                                                    <img src="cmn_images/delete.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                </button>
                                            </td>
                                             <?php } ?>
                                            <td class="lovtd"><?php echo $row[1]; ?></td>
                                            <td class="lovtd"><?php echo $row[2]; ?></td>
                                            <td class="lovtd"><?php echo $row[3]; ?></td>
                                            <td class="lovtd"><?php echo $row[4]; ?></td>
                                            <td class="lovtd"><?php echo $row[5]; ?></td>
                                            <td class="lovtd"><?php echo $row[6]; ?></td>
                                            <td class="lovtd">
                                                <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Loan Write-Off" onclick="getLoanWriteOffForm('myFormsModalxLG', 'myFormsModalxLGBody', 'myFormsModalxLGTitle', 'View Loan Write-Off', 15, <?php echo $subPgNo; ?>, 0,'VIEW', <?php echo $row[0]; ?>,'indCustTable','indCustTableRow<?php echo $cntr; ?>');" style="padding:2px !important;" style="padding:2px !important;">
                                                    <!--<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>-->
                                                    <img src="cmn_images/kghostview.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
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
                    </fieldset>
                </form>
                <?php
            }                   
        } 
        else if ($subPgNo == 4.10) {
            //SUMMARY DASHBOARD
        }
        else if ($subPgNo == 4.12) {
            $canAddOvdrftIntrst = test_prmssns($dfltPrvldgs[286], $mdlNm); 
            $canEditOvdrftIntrst = test_prmssns($dfltPrvldgs[287], $mdlNm); 
            $canDelOvdrftIntrst = test_prmssns($dfltPrvldgs[288], $mdlNm); 
            $canViewOvdrftIntrst = test_prmssns($dfltPrvldgs[289], $mdlNm); 
            
            echo $cntent .= "<li onclick=\"openATab('#allmodules', 'grp=17&typ=1&pg=4&subPgNo=4.12');\">
                                                            <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                                            <span style=\"text-decoration:none;\">Overdraft Interest Processing</span>
                        </li></div>";                  
            //var_dump($_POST);
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
            $sortBy = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : "Transaction Date DESC";
            if (strpos($srchFor, "%") === FALSE) {
                $srchFor = "%" . str_replace(" ", "%", $srchFor) . "%";
                $srchFor = str_replace("%%", "%", $srchFor);
            }

            if ($vwtyp == 0) {
                $canAddPrsn = test_prmssns($dfltPrvldgs[7], $mdlNm);
                /*echo $cntent . "<li>
                                                <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                                <span style=\"text-decoration:none;\">Data Administration</span>
                                        </li>
                                       </ul>
                                     </div>";*/
                $total = getOvdrftIntrstPymntTblTtl($isEnabledOnly, $srchFor, $srchIn, $orgID, $searchAll);
                if ($pageNo > ceil($total / $lmtSze)) {
                    $pageNo = 1;
                } else if ($pageNo < 1) {
                    $pageNo = ceil($total / $lmtSze);
                }

                $curIdx = $pageNo - 1;
                $result = getOvdrftIntrstPymntTbl($isEnabledOnly, $srchFor, $srchIn, $curIdx, $lmtSze, $orgID, $searchAll, $sortBy);
                $cntr = 0;
                $colClassType1 = "col-lg-2";
                $colClassType2 = "col-lg-3";
                ?> 
                <form id='ovdrftIntrstPymntForm' action='' method='post' accept-charset='UTF-8'>
                    <!--ROW ID-->
                    <input class="form-control" id="tblRowID" type = "hidden" placeholder="ROW ID"/>                     
                    <fieldset class="basic_person_fs1"><legend class="basic_person_lg" style="color: #003245">OVERDRAFT INTEREST PROCESSING</legend>
                    <div class="row" style="margin-bottom:1px;">
                        <div class="col-lg-3" style="padding:0px 15px 0px 15px !important;">
                            <div class="input-group">
                                <input class="form-control" id="ovdrftIntrstPymntSrchFor" type = "text" placeholder="Search For" value="<?php echo trim(str_replace("%", " ", $srchFor)); ?>" onkeyup="enterKeyFuncCust(event, '', '#allmodules', 'grp=17&typ=1&pg=4&subPgNo=4.12','ovdrftIntrstPymnt')">
                                <input id="ovdrftIntrstPymntPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                <label class="btn btn-primary btn-file input-group-addon" onclick="getCustData('clear', '#allmodules', 'grp=17&typ=1&pg=4&subPgNo=4.12','ovdrftIntrstPymnt')">
                                    <span class="glyphicon glyphicon-remove"></span>
                                </label>
                                <label class="btn btn-primary btn-file input-group-addon" onclick="getCustData('', '#allmodules', 'grp=17&typ=1&pg=4&subPgNo=4.12','ovdrftIntrstPymnt')">
                                    <span class="glyphicon glyphicon-search"></span>
                                </label> 
                            </div>
                        </div>
                        <div  class="col-lg-3">
                            <div class="input-group">
                                <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                <select data-placeholder="Select..." class="form-control chosen-select" id="ovdrftIntrstPymntSrchIn">
                                    <?php
                                    $valslctdArry = array("", "");
                                    $srchInsArrys = array("Customer Name","Loan Request");
                                    for ($z = 0; $z < count($srchInsArrys); $z++) {
                                        if ($srchIn == $srchInsArrys[$z]) {
                                            $valslctdArry[$z] = "selected";
                                        }
                                        ?>
                                        <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                    <?php } ?>
                                </select>
                                <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                <select data-placeholder="Select..." class="form-control chosen-select" id="ovdrftIntrstPymntDsplySze" style="min-width:70px !important;">                            
                                    <?php
                                    $valslctdArry = array("", "", "", "", "", "", "", "");
                                    $dsplySzeArry = array(1, 5, 10, 13, 30, 50, 100, 500, 1000);
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
                        <div  class="col-lg-2">
                            <div class="input-group">                        
                                <span class="input-group-addon"><span class="glyphicon glyphicon-sort-by-attributes"></span></span>
                                <select data-placeholder="Select..." class="form-control chosen-select" id="ovdrftIntrstPymntSortBy">
                                    <?php
                                    $valslctdArry = array("");
                                    $srchInsArrys = array("Transaction Date DESC");
                                    for ($z = 0; $z < count($srchInsArrys); $z++) {
                                        if ($sortBy == $srchInsArrys[$z]) {
                                            $valslctdArry[$z] = "selected";
                                        }
                                        ?>
                                        <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-2" style="padding: 5px 1px 0px 15px !important">
                            <div class="form-check" style="font-size: 12px !important;">
                                <label class="form-check-label">
                                    <?php
                                    $nonAprvdChekd = "";
                                    if ($isEnabledOnly == "true") {
                                        $nonAprvdChekd = "checked=\"true\"";
                                    }
                                    ?>
                                    <input type="checkbox" class="form-check-input" onclick="getCustData('', '#allmodules', 'grp=17&typ=1&pg=4&subPgNo=4.12','ovdrftIntrstPymnt');" id="ovdrftIntrstPymntIsEnabled" name="ovdrftIntrstPymntIsEnabled" <?php echo $nonAprvdChekd; ?>>
                                    Is Unauthorized?
                                </label>
                            </div>                             
                        </div>                            
                        <div  class="col-lg-2">
                            <nav aria-label="Page navigation">
                                <ul class="pagination" style="margin: 0px !important;">
                                    <li>
                                        <a href="javascript:getCustData('previous', '#allmodules', 'grp=17&typ=1&pg=4&subPgNo=4.12','ovdrftIntrstPymnt');" aria-label="Previous">
                                            <span aria-hidden="true">&laquo;</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:getCustData('next', '#allmodules', 'grp=17&typ=1&pg=4&subPgNo=4.12','ovdrftIntrstPymnt');" aria-label="Next">
                                            <span aria-hidden="true">&raquo;</span>
                                        </a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                    <div class="row " style="margin-bottom:2px;padding:2px 15px 2px 15px !important">   
                        <div class="col-md-12" style="padding:2px 1px 2px 1px !important;border-top:1px solid #ddd;border-bottom:1px solid #ddd;">
                            <div class="col-md-12" style="padding:0px 1px 0px 1px !important; float:left !important;">
                                <?php
                                if ($canAddOvdrftIntrst === true) {
                                    ?>
                                <button type="button" class="btn btn-default btn-sm" onclick="getOvdrftIntrstPymntForm('myFormsModalxLG', 'myFormsModalxLGBody', 'myFormsModalxLGTitle', 'Add Interest Process', 15, <?php echo $subPgNo; ?>, 0,'ADD', -1,'indCustTable');">
                                    <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                    New Interest Process
                                </button>
                                <?php } ?>
                            </div>
                        </div>
                    </div>                            
                    <div class="row"> 
                        <div  class="col-md-12">
                            <table class="table table-striped table-bordered table-responsive" id="indCustTable" cellspacing="0" width="100%" style="width:100%;">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <?php if ($canEditOvdrftIntrst === true) { ?>
                                            <th>...</th>
                                        <?php } ?>
                                       <?php if ($canDelOvdrftIntrst === true) { ?>
                                            <th>...</th>
                                        <?php } ?>
                                        <th>Transaction ID</th>
                                        <th>Customer</th>
                                        <th>Transaction Date</th>
                                        <th>Cur.</th>
                                        <th>Accrued</br>Amount</th>
                                        <th>Credit Request</th>
                                        <th>Status</th>
                                        <th>...</th>
                                        <!--<th>...</th>-->
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    while ($row = loc_db_fetch_array($result)) {
                                        /**/
                                        $cntr += 1;
                                        $prcsColor = "style='color:red !important;'";
                                        if($row[10] == "Paid"){
                                            $prcsColor = "style='color:green; font-weight:bold !important;'";
                                        }

                                        ?>
                                        <tr id="indCustTableRow<?php echo $cntr; ?>">                                    
                                            <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                            <?php if ($canEditOvdrftIntrst === true) { ?>                                    
                                                <td class="lovtd">
                                                    <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="Edit Interest Process" 
                                                            onclick="getOvdrftIntrstPymntForm('myFormsModalxLG', 'myFormsModalxLGBody', 'myFormsModalxLGTitle', 'Edit Interest Process', 15, <?php echo $subPgNo; ?>, 0,'EDIT', <?php echo $row[0]; ?>,'indCustTable','indCustTableRow<?php echo $cntr; ?>');" style="padding:2px !important;" style="padding:2px !important;">
                                                        <!--<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>-->
                                                        <img src="cmn_images/edit32.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                    </button>
                                                </td>
                                            <?php } ?>
                                            <?php if ($canDelOvdrftIntrst === true) { ?> 
                                            <td class="lovtd">
                                                <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="Delete Interest Process" onclick="deleteOvdrftIntrstPymnt(<?php echo $row[0]; ?>)" style="padding:2px !important;" style="padding:2px !important;">
                                                    <!--<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>-->
                                                    <img src="cmn_images/delete.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                </button>
                                            </td>
                                            <?php } ?>
                                            <td class="lovtd"><?php echo $row[1]; ?></td>
                                            <td class="lovtd"><?php echo $row[2]; ?></td>
                                            <td class="lovtd"><?php echo $row[4]; ?></td>
                                            <td class="lovtd"><?php echo $row[5]; ?></td>
                                            <td class="lovtd"><span style="color:blue; font-weight: bold; font-size: 18px !important;"><?php echo number_format($row[6],2); ?></span></td>
                                            <td class="lovtd"><?php echo $row[3]; ?></td>
                                            <td><?php echo $row[7]; ?></br><span <?php echo $prcsColor; ?>><?php echo $row[10]; ?></span></td>
                                            <td class="lovtd">
                                                <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Batch Payment" onclick="getOvdrftIntrstPymntForm('myFormsModalxLG', 'myFormsModalxLGBody', 'myFormsModalxLGTitle', 'View Interest Process', 15, <?php echo $subPgNo; ?>, 0,'VIEW', <?php echo $row[0]; ?>,'indCustTable','indCustTableRow<?php echo $cntr; ?>');" style="padding:2px !important;" style="padding:2px !important;">
                                                    <!--<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>-->
                                                    <img src="cmn_images/kghostview.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
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
                    </fieldset>
                </form>
                <?php
            }                   
        }        
        ?>

        <?php

    }

    //echo $cntent;
    //}
}
?>
