<?php
if (array_key_exists('lgn_num', get_defined_vars())) {

    //if ($vwtyp == "0") {
    $cntent .= "
					<li onclick=\"openATab('#allmodules', 'grp=17&typ=1&pg=2');\">
                                                <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
						<span style=\"text-decoration:none;\">Customer Accounts Menu</span>
                                        </li>";

    $usrID = $_SESSION['USRID'];
    $prsnid = $_SESSION['PRSN_ID'];
    $orgID = $_SESSION['ORG_ID'];
    
    $canAddCustAcct = test_prmssns($dfltPrvldgs[80], $mdlNm); 
    $canEditCustAcct = test_prmssns($dfltPrvldgs[81], $mdlNm); 
    $canDelCustAcct = test_prmssns($dfltPrvldgs[82], $mdlNm); 
    $canViewCustAcct = test_prmssns($dfltPrvldgs[20], $mdlNm);  
    $canExprtCustAcct = test_prmssns($dfltPrvldgs[239], $mdlNm);   
    $canImprtCustAcct = test_prmssns($dfltPrvldgs[240], $mdlNm);   
    $canExprtCustAcctSgntrs = test_prmssns($dfltPrvldgs[241], $mdlNm); 
    $canImprtCustAcctSgntrs = test_prmssns($dfltPrvldgs[242], $mdlNm); 
    $canExprtCustAcctLiens = test_prmssns($dfltPrvldgs[243], $mdlNm); 
    $canImprtCustAcctLiens = test_prmssns($dfltPrvldgs[244], $mdlNm); 
    $canSeeOtherStaffAcctTrns = test_prmssns($dfltPrvldgs[298], $mdlNm); 

    $lnkdFirmID = getGnrlRecNm("prs.prsn_names_nos", "person_id", "lnkd_firm_org_id", $prsnid);
    $pkID = $prsnid;
    
    $prsnBranchID = get_Person_BranchID($prsnid);
    $prsnBranch = getGnrlRecNm("org.org_sites_locations", "location_id", "site_desc||' ('||location_code_name||')'", "pasn.get_prsn_siteid($prsnid)");    

    if ($pkID > 0) {

        if ($subPgNo == 2.1) {//CUSTOMER ACCOUNTS
        echo $cntent .= "
					<li onclick=\"openATab('#allmodules', 'grp=17&typ=1&pg=2&subPgNo=2.1');\">
                                                <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
						<span style=\"text-decoration:none;\">Customer Accounts</span>
                                        </li></div>";            
            $error = "";
            $searchAll = true;

            $srchFor = isset($_POST['searchfor']) ? cleanInputData($_POST['searchfor']) : '';
            $srchIn = isset($_POST['searchin']) ? cleanInputData($_POST['searchin']) : 'Both';
            $pageNo = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
            $lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 10;
            $sortBy = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : "Date Added DESC";
            /**/
            $isEnabledOnly = false;
            if (isset($_POST['isEnabled'])) {
                $isEnabledOnly = cleanInputData($_POST['isEnabled']);
            }
            
            $branchSrchIn = isset($_POST['branchSrchIn']) ? cleanInputData($_POST['branchSrchIn']) : $prsnBranchID;
            $prdtTypeSrchIn = isset($_POST['prdtTypeSrchIn']) ? cleanInputData($_POST['prdtTypeSrchIn']) : "Savings";
            $statusSrchIn = isset($_POST['statusSrchIn']) ? cleanInputData($_POST['statusSrchIn']) : "All Statuses";
            $bnkPrdtTypeSrchInID = isset($_POST['bnkPrdtTypeSrchInID']) ? cleanInputData($_POST['bnkPrdtTypeSrchInID']) : -1;
            
            if($branchSrchIn == -1){

            } else {
                $prsnBranchID = $branchSrchIn;
            }
            
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
                $total = get_CustAccountsTtl($statusSrchIn, $branchSrchIn, $prdtTypeSrchIn, $bnkPrdtTypeSrchInID, $isEnabledOnly, $srchFor, $srchIn, $orgID, $searchAll);
                if ($pageNo > ceil($total / $lmtSze)) {
                    $pageNo = 1;
                } else if ($pageNo < 1) {
                    $pageNo = ceil($total / $lmtSze);
                }

                $curIdx = $pageNo - 1;
                $result = get_CustAccounts($statusSrchIn, $branchSrchIn, $prdtTypeSrchIn, $bnkPrdtTypeSrchInID, $isEnabledOnly, $srchFor, $srchIn, $curIdx, $lmtSze, $orgID, $searchAll, $sortBy);
                $cntr = 0;
                $colClassType1 = "col-lg-2";
                $colClassType2 = "col-lg-3";
                ?> 
                <form id='dataAdminForm' action='' method='post' accept-charset='UTF-8'>
                    <!--ROW ID-->
                    <input class="form-control" id="tblRowID" type = "hidden" placeholder="ROW ID"/>                     
                    <fieldset class="basic_person_fs1"><legend class="basic_person_lg" style="color: #003245">CUSTOMER ACCOUNTS</legend>
                    <div class="row" style="margin-bottom:1px;">
                        <?php if ($canAddCustAcct === true) { ?>   
                            <div class="<?php echo $colClassType1; ?>" style="padding:0px 1px 0px 15px !important;">                    
                                <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="getCustAcctsForm('myFormsModalCA', 'myFormsModalBodyCA', 'myFormsModalTitleCA', 'Add New Account', 13, <?php echo $subPgNo; ?>,0,'ADD', -1,'custAcctTable');">
                                    <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                    New Account
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
                                <input class="form-control" id="dataAdminSrchFor" type = "text" placeholder="Search For" value="<?php echo trim(str_replace("%", " ", $srchFor)); ?>" onkeyup="enterKeyFuncCust(event, '', '#allmodules', 'grp=17&typ=1&pg=2&subPgNo=2.1')">
                                <input id="dataAdminPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                <label class="btn btn-primary btn-file input-group-addon" onclick="getCustData('clear', '#allmodules', 'grp=17&typ=1&pg=2&subPgNo=2.1')">
                                    <span class="glyphicon glyphicon-remove"></span>
                                </label>
                                <label class="btn btn-primary btn-file input-group-addon" onclick="getCustData('', '#allmodules', 'grp=17&typ=1&pg=2&subPgNo=2.1')">
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
                                    $srchInsArrys = array("Account Title", "Account Number");
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
                        <div class="<?php echo $colClassType1; ?>">
                            <div class="input-group">                        
                                <span class="input-group-addon"><span class="glyphicon glyphicon-sort-by-attributes"></span></span>
                                <select data-placeholder="Select..." class="form-control chosen-select" id="dataAdminSortBy">
                                    <?php
                                    $valslctdArry = array("", "", "", "", "");
                                    $srchInsArrys = array("Date Added DESC", "Account Title ASC", "Account Title DESC", "Account Number", "Account Type ASC");
                                    for ($z = 0; $z < count($srchInsArrys); $z++) {
                                        if ($srchIn == $srchInsArrys[$z]) {
                                            $valslctdArry[$z] = "selected";
                                        }
                                        ?>
                                        <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="<?php echo $colClassType1; ?>">
                            <div  class="col-lg-8" style="padding-right:5px !important;">
                                <nav aria-label="Page navigation">
                                    <ul class="pagination" style="margin: 0px !important;">
                                        <li>
                                            <a href="javascript:getCustData('previous', '#allmodules', 'grp=17&typ=1&pg=2&subPgNo=2.1');" aria-label="Previous">
                                                <span aria-hidden="true">&laquo;</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="javascript:getCustData('next', '#allmodules', 'grp=17&typ=1&pg=2&subPgNo=2.1');" aria-label="Next">
                                                <span aria-hidden="true">&raquo;</span>
                                            </a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                            <div class="col-lg-4">
                                <button type="button" class="btn btn-default" style="margin-bottom: 5px;" title="Create Loan Payment Accounts" onclick="getCustAcctsForm('myFormsModalLg', 'myFormsModalBodyLg', 'myFormsModalTitleLg', 'Branch Payment Accounts', 13, <?php echo $subPgNo; ?>,7,'ADD', -1);/*createDisbmntPymntCustAccount();*/">
                                    <img src="cmn_images/loan_pymnt_acct.png" style="left: 0.5%; padding-right: 0px; height:20px; width:20px; position: relative; vertical-align: middle;">
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="row" style="margin-bottom:2px;padding:2px 15px 2px 15px !important">   
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
                                        <?php if ($canExprtCustAcct === true) { ?> 
                                        <li>
                                            <a href="javascript:exprtPrsnlCstmrs();">
                                                <img src="cmn_images/image007.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                Export Customer Accounts
                                            </a>
                                        </li>
                                        <?php } if ($canImprtCustAcct === true) { ?> 
                                        <li>
                                            <a href="javascript:imprtCustAccnts();">
                                                <img src="cmn_images/image007.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                Import Customer Accounts
                                            </a>
                                        </li>
                                        <?php } ?> 
                                        <?php if ($canExprtCustAcctSgntrs === true) { ?> 
                                        <li>
                                            <a href="javascript:exprtPrsnlCstmrs();">
                                                <img src="cmn_images/image007.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                Export Signatories
                                            </a>
                                        </li>
                                        <?php } if ($canExprtCustAcctSgntrs === true) { ?> 
                                        <li>
                                            <a href="javascript:imprtPrsnlCstmrs;">
                                                <img src="cmn_images/image007.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                Import Signatories
                                            </a>
                                        </li>
                                        <?php } ?>
                                        <?php if ($canExprtCustAcctLiens === true) { ?> 
                                        <li>
                                            <a href="javascript:exprtPrsnlCstmrs();">
                                                <img src="cmn_images/image007.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                Export Lien
                                            </a>
                                        </li>
                                        <?php } if ($canImprtCustAcctLiens === true) { ?>
                                        <li>
                                            <a href="javascript:imprtPrsnlCstmrs;">
                                                <img src="cmn_images/image007.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                Import Lien
                                            </a>
                                        </li>
                                        <?php } if ($canImprtCustAcctLiens === true) { ?>
                                        <li>
                                            <a href="javascript:imprtCustAccntsTrnsctns;">
                                                <img src="cmn_images/image007.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                Import Transactions
                                            </a>
                                        </li>
                                        <?php } ?>
                                    </ul>
                                </div>                              
                            </div>
                            <div class="col-md-10" style="float:right !important;">
                                <div class="col-lg-12" style="margin-bottom: 1px;"><!-- style="padding: 5px 1px 0px 15px !important">-->
                                    <div class="input-group">
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>                                        
                                        <select class="form-control" id="dataAdminStatusSrchIn" onchange="javascript:getCustData('', '#allmodules', 'grp=17&typ=1&pg=2&subPgNo=2.1');">
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
                                                else if ($statusSrchIn == "Authorized") {
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
                                                else if ($statusSrchIn == "Unauthorized") {
                                                    $selectedTxtUOT = "selected";
                                                }
                                            ?>
                                            <option <?php echo $selectedTxtAS; ?> value="All Statuses">All Statuses</option>
                                            <option value="Unauthorized" <?php echo $selectedTxtUOT; ?>>Unauthorized</option>
                                            <option value="Authorized" <?php echo $selectedTxtATZ; ?>>Authorized</option>
                                            <option value="Rejected" <?php echo $selectedTxtRJT; ?>>Rejected</option>
                                            <option value="Incomplete" <?php echo $selectedTxtINC; ?>>Incomplete</option>
                                            <option value="Withdrawn" <?php echo $selectedTxtWDR; ?>>Withdrawn</option>
                                        </select>                                    
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                        <select class="form-control" id="dataAdminBranchSrchIn" onchange="javascript:getCustData('', '#allmodules', 'grp=17&typ=1&pg=2&subPgNo=2.1');">
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
                                        <select class="form-control" id="dataAdminPrdtTypeSrchIn" onchange="clearROLovField('dataAdminBnkPrdtTypeSrchIn', 'dataAdminBnkPrdtTypeSrchInID');">
                                            <option value="All Account Types">All Account Types</option>
                                            <?php
                                            $brghtStr = "";
                                            $isDynmyc = FALSE;
                                            $titleRslt = getLovValues("%", "Both", 0, 100, $brghtStr,
                                                            getLovID("Bank Account Types"), $isDynmyc, -1, "", "");
                                            while ($titleRow = loc_db_fetch_array($titleRslt)) {
                                                $selectedTxt = "";
                                                if ($titleRow[0] == $prdtTypeSrchIn) {
                                                    $selectedTxt = "selected";
                                                }
                                                ?>
                                                <option value="<?php echo $titleRow[0]; ?>" <?php echo $selectedTxt; ?>><?php echo $titleRow[0]; ?></option>
                                                <?php
                                            }
                                            ?>
                                        </select> 
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                        <div class="input-group">
                                            <?php 
                                            $bnkPrdtTypeSrchIn = "";
                                            if ($prdtTypeSrchIn == "All Account Types") {
                                                $bnkPrdtTypeSrchIn = "";
                                            } else if($prdtTypeSrchIn == "Loan"){
                                                $bnkPrdtTypeSrchIn = getGnrlRecNm("mcf.mcf_prdt_loans", "loan_product_id", "product_name", $bnkPrdtTypeSrchInID);
                                            } else {
                                                $bnkPrdtTypeSrchIn = getGnrlRecNm("mcf.mcf_prdt_savings", "svngs_product_id", "product_name", $bnkPrdtTypeSrchInID);
                                             }
                                            
                                            ?>
                                            <input type="text" class="form-control" aria-label="..." id="dataAdminBnkPrdtTypeSrchIn" value="<?php echo $bnkPrdtTypeSrchIn; ?>" readonly="readonly">
                                            <input type="hidden" id="dataAdminBnkPrdtTypeSrchInID" value="<?php echo $bnkPrdtTypeSrchInID; ?>">
                                            <label class="btn btn-primary btn-file input-group-addon" onclick="onClickAcctProduct();">
                                                <span class="glyphicon glyphicon-th-list"></span>
                                            </label>
                                            <label class="btn btn-primary btn-file input-group-addon" onclick="clearROLovField('dataAdminBnkPrdtTypeSrchIn', 'dataAdminBnkPrdtTypeSrchInID');">
                                                <span class="glyphicon glyphicon-remove"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>                        
                    <div class="row"> 
                        <div  class="col-md-12">
                            <table class="table table-striped table-bordered table-responsive" id="custAcctTable" cellspacing="0" width="100%" style="width:100%;">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <?php if ($canAddCustAcct === true) { ?> 
                                            <th>...</th>
                                        <?php } ?>
                                        <?php if ($canDelCustAcct === true) { ?> 
                                            <th>...</th>
                                        <?php } ?>		
                                        <th>Account Title</th>
                                        <th>Account Number</th>
                                        <th>Product Type</th>
                                        <th>Branch</th>
                                        <th>Status</th>
                                        <?php if ($canViewCustAcct === true) { ?> 
                                            <th>...</th>
                                        <?php } ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    while ($row = loc_db_fetch_array($result)) {
                                        /**/
                                        $cntr += 1;

                                        ?>
                                        <tr id="custAcctTableRow<?php echo $cntr; ?>">                                    
                                            <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                            <?php if ($canAddCustAcct === true) { ?>                              
                                                <td class="lovtd">
                                                    <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="Edit Customer Account" 
                                                            onclick="getCustAcctsForm('myFormsModalCA', 'myFormsModalBodyCA', 'myFormsModalTitleCA', 'Edit Customer Account', 13, <?php echo $subPgNo; ?>,0,'EDIT', <?php echo $row[0]; ?>,'custAcctTable','custAcctTableRow<?php echo $cntr; ?>');" style="padding:2px !important;" style="padding:2px !important;">
                                                        <!--<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>-->
                                                        <img src="cmn_images/edit32.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                    </button>
                                                </td>
                                            <?php } ?>
                                            <?php if ($canDelCustAcct === true) { ?> 
                                            <td class="lovtd">
                                                <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="Delete Account" onclick="deleteCustAccnt(<?php echo $row[0]; ?>,'<?php echo $row[5]; ?>');" style="padding:2px !important;" style="padding:2px !important;">
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
                                            <?php if ($canViewCustAcct === true) { ?> 
                                            <td class="lovtd">
                                                <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Customer Account" onclick="getCustAcctsForm('myFormsModalCA', 'myFormsModalBodyCA', 'myFormsModalTitleCA', 'View Customer Account', 13, <?php echo $subPgNo; ?>,0,'VIEW', <?php echo $row[0]; ?>,'custAcctTable','custAcctTableRow<?php echo $cntr; ?>');" style="padding:2px !important;" style="padding:2px !important;">
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
        else if ($subPgNo == 2.2) {
            echo $cntent .= "<li onclick=\"openATab('#allmodules', 'grp=17&typ=1&pg=2&subPgNo=2.2');\">
                                                            <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                                            <span style=\"text-decoration:none;\">Manual Batch Payments</span>
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
                $total = getManualInterestPymntSavingsTblTtl($isEnabledOnly, $srchFor, $srchIn, $orgID, $searchAll);
                if ($pageNo > ceil($total / $lmtSze)) {
                    $pageNo = 1;
                } else if ($pageNo < 1) {
                    $pageNo = ceil($total / $lmtSze);
                }

                $curIdx = $pageNo - 1;
                $result = getManualInterestPymntSavingsTbl($isEnabledOnly, $srchFor, $srchIn, $curIdx, $lmtSze, $orgID, $searchAll, $sortBy);
                $cntr = 0;
                $colClassType1 = "col-lg-2";
                $colClassType2 = "col-lg-3";
                ?> 
                <form id='mnlSvngsIntrstPymntForm' action='' method='post' accept-charset='UTF-8'>
                    <!--ROW ID-->
                    <input class="form-control" id="tblRowID" type = "hidden" placeholder="ROW ID"/>                     
                    <fieldset class="basic_person_fs1"><legend class="basic_person_lg" style="color: #003245">MANUAL INTEREST PAYMENTS</legend>
                    <div class="row" style="margin-bottom:1px;">
                        <div class="col-lg-3" style="padding:0px 15px 0px 15px !important;">
                            <div class="input-group">
                                <input class="form-control" id="mnlSvngsIntrstPymntSrchFor" type = "text" placeholder="Search For" value="<?php echo trim(str_replace("%", " ", $srchFor)); ?>" onkeyup="enterKeyFuncCust(event, '', '#allmodules', 'grp=17&typ=1&pg=2&subPgNo=2.2','mnlSvngsIntrstPymnt')">
                                <input id="mnlSvngsIntrstPymntPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                <label class="btn btn-primary btn-file input-group-addon" onclick="getCustData('clear', '#allmodules', 'grp=17&typ=1&pg=2&subPgNo=2.2','mnlSvngsIntrstPymnt')">
                                    <span class="glyphicon glyphicon-remove"></span>
                                </label>
                                <label class="btn btn-primary btn-file input-group-addon" onclick="getCustData('', '#allmodules', 'grp=17&typ=1&pg=2&subPgNo=2.2','mnlSvngsIntrstPymnt')">
                                    <span class="glyphicon glyphicon-search"></span>
                                </label> 
                            </div>
                        </div>
                        <div  class="col-lg-3">
                            <div class="input-group">
                                <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                <select data-placeholder="Select..." class="form-control chosen-select" id="mnlSvngsIntrstPymntSrchIn">
                                    <?php
                                    $valslctdArry = array("", "");
                                    $srchInsArrys = array("Batch Name","Description");
                                    for ($z = 0; $z < count($srchInsArrys); $z++) {
                                        if ($srchIn == $srchInsArrys[$z]) {
                                            $valslctdArry[$z] = "selected";
                                        }
                                        ?>
                                        <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                    <?php } ?>
                                </select>
                                <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                <select data-placeholder="Select..." class="form-control chosen-select" id="mnlSvngsIntrstPymntDsplySze" style="min-width:70px !important;">                            
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
                                <select data-placeholder="Select..." class="form-control chosen-select" id="mnlSvngsIntrstPymntSortBy">
                                    <?php
                                    $valslctdArry = array("", "", "");
                                    $srchInsArrys = array("Transaction Date DESC", "Batch Name ASC", "Batch Name DESC");
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
                                    <input type="checkbox" class="form-check-input" onclick="getCustData('', '#allmodules', 'grp=17&typ=1&pg=2&subPgNo=2.2','mnlSvngsIntrstPymnt');" id="mnlSvngsIntrstPymntIsEnabled" name="mnlSvngsIntrstPymntIsEnabled" <?php echo $nonAprvdChekd; ?>>
                                    Is Unauthorized?
                                </label>
                            </div>                             
                        </div>                            
                        <div  class="col-lg-2">
                            <nav aria-label="Page navigation">
                                <ul class="pagination" style="margin: 0px !important;">
                                    <li>
                                        <a href="javascript:getCustData('previous', '#allmodules', 'grp=17&typ=1&pg=2&subPgNo=2.2','mnlSvngsIntrstPymnt');" aria-label="Previous">
                                            <span aria-hidden="true">&laquo;</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:getCustData('next', '#allmodules', 'grp=17&typ=1&pg=2&subPgNo=2.2','mnlSvngsIntrstPymnt');" aria-label="Next">
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
                                if ($canAddPrsn === true) {
                                    ?>
                                <button type="button" class="btn btn-default btn-sm" onclick="getMnlSvngsIntrstPymntForm('myFormsModalxLG', 'myFormsModalxLGBody', 'myFormsModalxLGTitle', 'Add Batch Payment', 13, <?php echo $subPgNo; ?>, 0,'ADD', -1,'indCustTable');">
                                    <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                    New Batch Payment
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
                                        <?php if ($canAddPrsn === true) { ?>
                                            <th>...</th>
                                        <?php } ?>
                                        <th>...</th>
                                        <th>Batch Name</th>
                                        <th>Description</th>
                                        <th>Transaction Date</th>
                                        <th>Cur.</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Is Paid</th>
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
                                            <?php if ($canAddPrsn === true) { ?>                                    
                                                <td class="lovtd">
                                                    <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="Edit Batch Payment" 
                                                            onclick="getMnlSvngsIntrstPymntForm('myFormsModalxLG', 'myFormsModalxLGBody', 'myFormsModalxLGTitle', 'Edit Batch Payment', 13, <?php echo $subPgNo; ?>, 0,'EDIT', <?php echo $row[0]; ?>,'indCustTable','indCustTableRow<?php echo $cntr; ?>');" style="padding:2px !important;" style="padding:2px !important;">
                                                        <!--<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>-->
                                                        <img src="cmn_images/edit32.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                    </button>
                                                </td>
                                            <?php } ?>
                                            <td class="lovtd">
                                                <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="Delete Batch Payment" onclick="deleteMnlSvngsIntrstPymnt(<?php echo $row[0]; ?>)" style="padding:2px !important;" style="padding:2px !important;">
                                                    <!--<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>-->
                                                    <img src="cmn_images/delete.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                </button>
                                            </td>
                                            <td class="lovtd"><?php echo $row[10]; ?></td>
                                            <td class="lovtd"><?php echo $row[2]; ?></td>
                                            <td class="lovtd"><?php echo $row[1]; ?></td>
                                            <td class="lovtd"><?php echo $row[12]; ?></td>
                                            <td class="lovtd"><span style="color:blue; font-weight: bold; font-size: 18px !important;"><?php echo number_format($row[4],4); ?></span></td>
                                            <td class="lovtd"><?php echo $row[6]; ?></td>
                                            <td class="lovtd"><?php echo $row[7]; ?></td>
                                            <td class="lovtd">
                                                <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Batch Payment" onclick="getMnlSvngsIntrstPymntForm('myFormsModalxLG', 'myFormsModalxLGBody', 'myFormsModalxLGTitle', 'View Batch Payment', 13, <?php echo $subPgNo; ?>, 0,'VIEW', <?php echo $row[0]; ?>,'indCustTable','indCustTableRow<?php echo $cntr; ?>');" style="padding:2px !important;" style="padding:2px !important;">
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
        else if ($subPgNo == 2.3) {//STATEMENT OF ACCOUNT
            
            $acctNoFind = isset($_POST['acctNoFind']) ? cleanInputData($_POST['acctNoFind']) : '';
            $acctNoID = getGnrlRecID("mcf.mcf_accounts", "account_number", "account_id", $acctNoFind, $orgID);

               $qStrtDte = "01-Jan-1900 00:00:00";
               $qEndDte = "31-Dec-4000 23:59:59"; 
               $prdtCode = substr($acctNoFind,0,3);
               $acctHolderPrsnID = getAccountLinkedPrsnID($acctNoFind);
                
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
           
            
        echo $cntent .= "
					<li onclick=\"openATab('#allmodules', 'grp=17&typ=1&pg=2&subPgNo=2.3');\">
                                                <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
						<span style=\"text-decoration:none;\">Statement of Accounts</span>
                                        </li></div>";   
        
        
        
        
                $pageNoAH = isset($_POST['pageNoAH']) ? cleanInputData($_POST['pageNoAH']) : 1;
                $lmtSze1 = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 10;   

                $total1 = get_OneCustAccntHstrytNavTtlSA($acctNoFind);

                if ($pageNoAH > ceil($total1 / $lmtSze1)) {
                    $pageNoAH = 1;
                } else if ($pageNoAH < 1) {
                    $pageNoAH = ceil($total1 / $lmtSze1);
                }

                $curIdx = $pageNoAH - 1;
                $acntHstryRslt = get_OneCustAccntHstryNavSA($acctNoFind, $curIdx, $lmtSze1, $qStrtDte, $qEndDte);
                
                $tblNm1 = "mcf.mcf_accounts";

                $cnt = getCustAcctDataChngPndngCountSA($acctNoFind);
                if($cnt > 0){
                    $tblNm1 = "mcf.mcf_accounts_hstrc";        
                }                 

                $result = getCustAccountDetsSA($acctNoFind, $tblNm1);
                //$balDteStr = substr(getStartOfDayYMD(),0,10);//getDB_Date_timeYYYYMMDD();

                $result2 = getCustAccountBalsSA($acctNoFind, $qEndDte);
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

                ?>
                    <div class="row" style="margin-bottom:2px;padding:1px 15px 2px 15px !important">   
                        <div  class="col-md-12" style="padding:2px 1px 2px 1px !important;border-top:1px solid #ddd;border-bottom:1px solid #ddd;">
                            <div  style="float:left;width:35% !important;padding-right: 10px !important;">
                                <div class="col-md-12" style="padding:0px 1px 0px 1px !important;">
                                    <div class="input-group">
                                        <input class="form-control rqrdFld" id="acctNoFind" style="font-size: 13px !important;font-weight: bold !important;" placeholder="Enter Account Number" type = "text" min="0" placeholder="" value="<?php echo $acctNoFind; ?>" onkeypress="getAccountStatementKD(event)"/>
                                        <input type="hidden" id="acctNoFindAccId" value="<?php echo $acctNoID; ?>">
                                        <input type="hidden" id="acctNoFindRawTxt" value="<?php echo ''; ?>">
                                        <label class="btn btn-primary btn-file input-group-addon" onclick="dsplyAllBankCustsLov2();">
                                            <span class="glyphicon glyphicon-th-list"></span>
                                        </label>
                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getAccountStatement('', '#allmodules', 'grp=17&typ=1&pg=2&subPgNo=2.3')">
                                            <img src="cmn_images/search.png" style="height:17px; width:auto; position: relative; vertical-align: middle;">
                                            FIND
                                        </label>
                                    </div>
                                    <!--Account Transaction ID-->
                                    <input type="hidden" id="gnrlOrgID" value="<?php echo $orgID; ?>">
                                    <input class="form-control" id="acctTrnsId" placeholder="Transaction ID" type = "hidden" placeholder="" value="<?php echo $pkID; ?>"/>
                                </div>
                            </div>
                            <div  style="float:right;width:65% !important;">
                                <div cass="col-xs-12">
                                <div class="col-xs-6" style="padding:0px 1px 0px 0px !important;">
                                    <div class="input-group">
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                        <select class="form-control" id="branchSrchIn" onchange="">
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
                                        <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                        <select data-placeholder="Select..." class="form-control chosen-select" id="DsplySze" style="min-width:50px !important;">                            
                                            <?php
                                            $valslctdArry = array("", "", "", "", "", "", "", "");
                                            $dsplySzeArry = array(1, 5, 10, 15, 30, 50, 100, 500, 1000);
                                            for ($y = 0; $y < count($dsplySzeArry); $y++) {
                                                if ($lmtSze1 == $dsplySzeArry[$y]) {
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
                                <div class="col-xs-3" style="padding:0px 1px 0px 0px !important;">
                                    <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                        <input class="form-control" size="16" type="text" id="StrtDate" name="StrtDate" value="<?php
                                        echo substr($qStrtDte, 0, 11);
                                        ?>" placeholder="Start Date">
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                    </div>
                                </div>
                                <div class="col-xs-3" style="padding:0px 1px 0px 0px !important;">
                                    <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                        <input class="form-control" size="16" type="text"  id="EndDate" name="EndDate" value="<?php
                                        echo substr($qEndDte, 0, 11);
                                        ?>" placeholder="End Date">
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                    </div>
                                </div>
                                </div>
                            </div>                            
                        </div>
                    </div> 
                <?php
                
                $rowNo = loc_db_num_rows($result);
                if($rowNo > 0){
                    while ($row = loc_db_fetch_array($result)) {
                        $branch = getGnrlRecNm("org.org_sites_locations", "location_id", "site_desc||' ('||location_code_name||')'", $row[19]);
                        $relOfficerNm = getGnrlRecNm("prs.prsn_names_nos", "person_id", "trim(title || ' ' || sur_name || ', ' || first_name || ' ' || other_names)", $row[20]);                    
                        $custNm = getGnrlRecNm2("mcf.mcf_accounts", "account_number", "account_title", $acctNoFind);
                        /* Edit */
                        ?>
                        <!--<input class="form-control" id="acctID" type = "hidden" placeholder="Account ID" value="<?php echo $row[0]; ?>"/>-->
                        <form class="form-horizontal" id="customerAccountTrnsHistoryForm">
                            <?php
                                if($prdtCode != '211' || ($prdtCode == '211' && $canSeeOtherStaffAcctTrns === true) || ($acctHolderPrsnID == $prsnid)){
                            ?>
                            <div class="row"><!-- ROW 1 -->
                                <div class="col-lg-12"> 
                                    <legend class="basic_person_lg1" id="hstrcAcctTrns"><span style="font-weight:bold !important;"><?php echo $custNm; ?></span></legend>
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
                                                <div class="col-lg-10"><legend class="basic_person_lg1" id="hstrcAcctTrns">Historic Account Transactions</legend></div> 
                                                <div class="col-lg-1">
                                                    <input id="pageNoAH" type = "hidden" value="<?php echo $pageNoAH; ?>">
                                                    <nav aria-label="Page navigation" style="width: 100px !important;">
                                                        <ul class="pagination" style="margin: 0px !important;">
                                                            <li>
                                                                <a href="javascript:getAccountStatement('previous','#allmodules', 'grp=17&typ=1&pg=2&subPgNo=2.3');" aria-label="Previous">
                                                                    <span aria-hidden="true">&laquo;</span>
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a href="javascript:getAccountStatement('next','#allmodules', 'grp=17&typ=1&pg=2&subPgNo=2.3');" aria-label="Next">
                                                                    <span aria-hidden="true">&raquo;</span>
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </nav>
                                                </div>
                                                <div class="col-lg-1">
                                                    <?php 
                                                        $reportTitle = "Statement of Account";
                                                        $reportName = "Customer Statement of Account";
                                                        $rptID = getRptID($reportName);
                                                        $prmID1 = getParamIDUseSQLRep("{:P_ACCT_ID}", $rptID);
                                                        $prmID3 = getParamIDUseSQLRep("{:P_FROM_DATE}", $rptID);
                                                        $prmID4 = getParamIDUseSQLRep("{:P_TO_DATE}", $rptID);
                                                        $prmID2 = getParamIDUseSQLRep("{:documentTitle}", $rptID);
                                                        //$acctID = $loanDisbmntDetID;
                                                        //$paramRepsNVals = $prmID1 . "~" . $acctID . "|" . $prmID3 . "~" . $invcID . "|" . $prmID4 . "~" . $invcID . "|" . $prmID2 . "~" . $reportTitle . "|-190~PDF";
                                                        //$paramStr = urlencode($paramRepsNVals);
                                                    ?>
                                                    <button type="button" class="btn btn-default btn-sm" style="height:30px;" title="Statement of Account" onclick="printStatementOfAccount(<?php echo $rptID; ?>, <?php echo $prmID1; ?>, <?php echo $prmID2; ?>, <?php echo $prmID3; ?>, <?php echo $prmID4; ?>,'<?php echo $reportTitle; ?>');">
                                                        <img src="cmn_images/printer-icon.png" style="left: 0.5%; padding-right: 5px; height:17px; width:17px; position: relative; vertical-align: middle;">
                                                    </button>
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
                } else {
                    /* Edit */
                    ?>
                    <!--<input class="form-control" id="acctID" type = "hidden" placeholder="Account ID" value="<?php echo $row[0]; ?>"/>-->
                    <form class="form-horizontal" id="customerAccountTrnsHistoryForm">
                        <div class="row"><!-- ROW 1 -->
                            <div class="col-lg-12"> 
                                <fieldset class="" style="margin-top:0px !important;"><legend class="basic_person_lg1" id="hstrcAcctTrns">Account Balance</legend>  
                                    <div  class="col-md-6">
                                        <div class="form-group form-group-sm">
                                            <label for="curBal" class="control-label col-md-4">Current Balance:</label>
                                            <div class="col-md-8">
                                                <input class="form-control" style="font-weight: bold;color:green;font-size:16px !important;" id="curBal" type = "text" placeholder="" value="0.00" readonly/>                                                                                                                                        
                                            </div>
                                        </div>
                                        <div class="form-group form-group-sm">
                                            <label for="unclrdFunds" class="control-label col-md-4">Uncleared Funds:</label>
                                            <div  class="col-md-8">
                                                <input class="form-control" style="font-weight: bold;color:green;font-size:16px !important;" size="16" type="text" id="unclrdFunds" value="0.00" readonly="">
                                            </div>
                                        </div>                                                                                                                            
                                    </div>
                                    <div  class="col-md-6">
                                        <div class="form-group form-group-sm">
                                            <label for="lienAmntDsp" class="control-label col-md-4">Lien Amount:</label>
                                            <div  class="col-md-8">
                                                <input class="form-control" style="font-weight: bold;color:green;font-size:16px !important;" id="lienAmntDsp" type = "text" min="0" placeholder="Overdraft Balance" value="0.00" readonly/>
                                            </div>
                                        </div> 
                                        <div class="form-group form-group-sm">
                                            <label for="avlblBal" class="control-label col-md-4">Available Balance:</label>
                                            <div  class="col-md-8">
                                                <input class="form-control" style="font-weight: bold;color:blue;font-size:16px !important;" id="avlblBal" type = "text" min="0" placeholder="Overdraft Balance" value="0.00" readonly/>
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
                                            <div class="col-lg-10"><legend class="basic_person_lg1" id="hstrcAcctTrns">Historic Account Transactions</legend></div> 
                                            <div class="col-lg-2">
                                                <input id="pageNoAH" type = "hidden" value="<?php echo $pageNoAH; ?>">
                                                <nav aria-label="Page navigation">
                                                    <ul class="pagination" style="margin: 0px !important;">
                                                        <li>
                                                            <a href="javascript:getAccountStatement('previous','#allmodules', 'grp=17&typ=1&pg=2&subPgNo=2.3');" aria-label="Previous">
                                                                <span aria-hidden="true">&laquo;</span>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="javascript:getAccountStatement('previous','#allmodules', 'grp=17&typ=1&pg=2&subPgNo=2.3');" aria-label="Next">
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
        ?>       
        <?php
        //}
    }

    //echo $cntent;
    //}
}
?>
