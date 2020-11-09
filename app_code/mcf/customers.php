<?php
if (array_key_exists('lgn_num', get_defined_vars())) {
    $cntent .= "
					<li onclick=\"openATab('#allmodules', 'grp=17&typ=1&pg=1');\">
                                                <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
						<span style=\"text-decoration:none;\">Customer Management Menu</span>
                                        </li>";

    $usrID = $_SESSION['USRID'];
    $prsnid = $_SESSION['PRSN_ID'];
    $orgID = $_SESSION['ORG_ID'];

    $lnkdFirmID = getGnrlRecNm("prs.prsn_names_nos", "person_id", "lnkd_firm_org_id", $prsnid);
    $pkID = $prsnid;
    
    $prsnBranchID = get_Person_BranchID($prsnid);
    $prsnBranch = getGnrlRecNm("org.org_sites_locations", "location_id", "site_desc||' ('||location_code_name||')'", "pasn.get_prsn_siteid($prsnid)");      
    
    $canAddIndvCstmr = test_prmssns($dfltPrvldgs[60], $mdlNm); 
    $canEditIndvCstmr = test_prmssns($dfltPrvldgs[61], $mdlNm); 
    $canDelIndvCstmr = test_prmssns($dfltPrvldgs[62], $mdlNm); 
    $canViewIndvCstmr = test_prmssns($dfltPrvldgs[14], $mdlNm);  
    $canExprtIndvCstmr = test_prmssns($dfltPrvldgs[215], $mdlNm);   
    $canImprtIndvCstmr = test_prmssns($dfltPrvldgs[216], $mdlNm);   
    $canExprtNtnlIDsIndvCstmr = test_prmssns($dfltPrvldgs[217], $mdlNm); 
    $canImprtNtnlIDsIndvCstmr = test_prmssns($dfltPrvldgs[218], $mdlNm); 
    $canExprtAdtnlDtaIndvCstmr = test_prmssns($dfltPrvldgs[219], $mdlNm); 
    $canImprtAdtnlDtaIndvCstmr = test_prmssns($dfltPrvldgs[220], $mdlNm); 
    
    $canAddCorpCstmr = test_prmssns($dfltPrvldgs[64], $mdlNm); 
    $canEditCorpCstmr = test_prmssns($dfltPrvldgs[65], $mdlNm); 
    $canDelCorpCstmr = test_prmssns($dfltPrvldgs[66], $mdlNm); 
    $canViewCorpCstmr = test_prmssns($dfltPrvldgs[15], $mdlNm);  
    $canExprtCorpCstmr = test_prmssns($dfltPrvldgs[215], $mdlNm);   
    $canImprtCorpCstmr = test_prmssns($dfltPrvldgs[216], $mdlNm);   
    $canExprtDrctrs = test_prmssns($dfltPrvldgs[217], $mdlNm); 
    $canImprtDrctrs = test_prmssns($dfltPrvldgs[218], $mdlNm); 
    $canExprtAdtnlDtaCorpCstmr = test_prmssns($dfltPrvldgs[219], $mdlNm); 
    $canImprtAdtnlDtaCorpCstmr = test_prmssns($dfltPrvldgs[220], $mdlNm);    
    
    $canAddGrpCstmr = test_prmssns($dfltPrvldgs[68], $mdlNm); 
    $canEditGrpCstmr = test_prmssns($dfltPrvldgs[69], $mdlNm); 
    $canDelGrpCstmr = test_prmssns($dfltPrvldgs[70], $mdlNm); 
    $canViewGrpCstmr = test_prmssns($dfltPrvldgs[71], $mdlNm);  
    $canExprtGrpCstmr = test_prmssns($dfltPrvldgs[227], $mdlNm);   
    $canImprtGrpCstmr = test_prmssns($dfltPrvldgs[228], $mdlNm);   
    $canExprtGrpMmbrs = test_prmssns($dfltPrvldgs[229], $mdlNm); 
    $canImprtGrpMmbrs = test_prmssns($dfltPrvldgs[230], $mdlNm); 
    $canExprtAdtnlDtaGrpCstmr = test_prmssns($dfltPrvldgs[231], $mdlNm); 
    $canImprtAdtnlDtaGrpCstmr = test_prmssns($dfltPrvldgs[232], $mdlNm); 

    $canAddOtherPrsn = test_prmssns($dfltPrvldgs[72], $mdlNm); 
    $canEditOtherPrsn = test_prmssns($dfltPrvldgs[73], $mdlNm); 
    $canDelOtherPrsn = test_prmssns($dfltPrvldgs[74], $mdlNm); 
    $canViewOtherPrsn = test_prmssns($dfltPrvldgs[75], $mdlNm);  
    $canExprtOtherPrsn = test_prmssns($dfltPrvldgs[233], $mdlNm);   
    $canImprtOtherPrsn = test_prmssns($dfltPrvldgs[234], $mdlNm);   
    $canExprtNtnlIDsOtherPrsn = test_prmssns($dfltPrvldgs[235], $mdlNm); 
    $canImprtNtnlIDsOtherPrsn = test_prmssns($dfltPrvldgs[236], $mdlNm); 
    $canExprtAdtnlDtaOtherPrsn = test_prmssns($dfltPrvldgs[237], $mdlNm); 
    $canImprtAdtnlDtaOtherPrsn = test_prmssns($dfltPrvldgs[238], $mdlNm); 
    

    if ($pkID > 0) {
        
        $branchSrchIn = isset($_POST['branchSrchIn']) ? cleanInputData($_POST['branchSrchIn']) : $prsnBranchID;
        $statusSrchIn = isset($_POST['statusSrchIn']) ? cleanInputData($_POST['statusSrchIn']) : "All Statuses";  
        $rqstStatusSrchIn = isset($_POST['rqstStatusSrchIn']) ? cleanInputData($_POST['rqstStatusSrchIn']) : "All Statuses";  

        if($branchSrchIn == -1){

        } else {
            $prsnBranchID = $branchSrchIn;
        }          

        if ($subPgNo == 1.1) {
            //PERSONAL CUSTOMER
            echo $cntent .= "
                            <li onclick=\"openATab('#allmodules', 'grp=17&typ=1&pg=1&subPgNo=1.1');\">
                                    <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                    <span style=\"text-decoration:none;\">Individual Customers</span>
                            </li>
                  </ul>
                  </div>";
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
                //$canAddPrsn = test_prmssns($dfltPrvldgs[60], $mdlNm);
                $total = get_IndCustTtl($statusSrchIn, $branchSrchIn, $srchFor, $srchIn, $orgID, $searchAll);
                if ($pageNo > ceil($total / $lmtSze)) {
                    $pageNo = 1;
                } else if ($pageNo < 1) {
                    $pageNo = ceil($total / $lmtSze);
                }

                $curIdx = $pageNo - 1;
                $result = get_IndCust($statusSrchIn, $branchSrchIn, $srchFor, $srchIn, $curIdx, $lmtSze, $orgID, $searchAll, $sortBy);
                $cntr = 0;
                $colClassType1 = "col-lg-2";
                $colClassType2 = "col-lg-3";
                ?> 
                <form id='mcfIndCstmrForm' action='' method='post' accept-charset='UTF-8'>
                    <!--ROW ID-->
                    <input class="form-control" id="tblRowID" type = "hidden" placeholder="ROW ID"/>                     
                    <fieldset class="basic_person_fs1"><legend class="basic_person_lg" style="color: #003245">INDIVIDUAL CUSTOMERS</legend>
                        <div class="row" style="margin-bottom:1px;">
                            <?php
                            if ($canAddIndvCstmr === true) {
                                ?>   
                                <div class="<?php echo $colClassType1; ?>" style="padding:0px 1px 0px 15px !important;">                    
                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="getCustomersForm('myFormsModalLg', 'myFormsModalBodyLg', 'myFormsModalTitleLg', 'Add New Individual Customer', 11, <?php echo $subPgNo; ?>, 0, 'ADD', -1);">
                                        <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                        New Customer
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
                                    <input class="form-control" id="mcfIndCstmrSrchFor" type = "text" placeholder="Search For" value="<?php
                                    echo trim(str_replace("%", " ", $srchFor));
                                    ?>" onkeyup="enterKeyFuncCust(event, '', '#allmodules', 'grp=17&typ=1&pg=1&subPgNo=1.1', 'mcfIndCstmr')">
                                    <input id="mcfIndCstmrPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getCustData('clear', '#allmodules', 'grp=17&typ=1&pg=1&subPgNo=1.1', 'mcfIndCstmr')">
                                        <span class="glyphicon glyphicon-remove"></span>
                                    </label>
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getCustData('', '#allmodules', 'grp=17&typ=1&pg=1&subPgNo=1.1', 'mcfIndCstmr')">
                                        <span class="glyphicon glyphicon-search"></span>
                                    </label> 
                                </div>
                            </div>
                            <div class="<?php echo $colClassType2; ?>">
                                <div class="input-group">
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="mcfIndCstmrSrchIn">
                                        <?php
                                        $valslctdArry = array("", "", "", "", "", "", "", "", "", "");
                                        $srchInsArrys = array("Full Name", "ID", "Residential Address"
                                            , "Contact Information", "Linked Firm/Workplace", "Date of Birth",
                                            "Home Town", "Gender", "Marital Status");
                                        for ($z = 0; $z < count($srchInsArrys); $z++) {
                                            if ($srchIn == $srchInsArrys[$z]) {
                                                $valslctdArry[$z] = "selected";
                                            }
                                            ?>
                                            <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                        <?php } ?>
                                    </select>
                                    <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="mcfIndCstmrDsplySze" style="min-width:70px !important;">                            
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
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="mcfIndCstmrSortBy">
                                        <?php
                                        $valslctdArry = array("", "", "", "", "");
                                        $srchInsArrys = array("Date Added DESC", "Full Name", "ID ASC", "ID DESC", "Date of Birth");
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
                                            <a href="javascript:getCustData('previous', '#allmodules', 'grp=17&typ=1&pg=1&subPgNo=1.1','mcfIndCstmr');" aria-label="Previous">
                                                <span aria-hidden="true">&laquo;</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="javascript:getCustData('next', '#allmodules', 'grp=17&typ=1&pg=1&subPgNo=1.1','mcfIndCstmr');" aria-label="Next">
                                                <span aria-hidden="true">&raquo;</span>
                                            </a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                        <div class="row" style="margin-bottom:2px;padding:1px 15px 2px 15px !important">   
                            <div  class="col-md-12" style="padding:2px 1px 2px 1px !important;border-top:1px solid #ddd;border-bottom:1px solid #ddd;">
                                <div class="col-md-4" style="float:left;">
                                    <div class="col-md-12">
                                        <div class="btn-group" style="margin-bottom: 1px;">
                                            <button type="button" class="btn btn-default btn-sm" style="height: 30px !important;" onclick="">
                                                <img src="cmn_images/image007.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                Excel
                                            </button>
                                            <button class="btn btn-info dropdown-toggle btn-sm" style="height:30px !important;" type="button" data-toggle="dropdown">
                                                <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu" role="menu" style="margin-left: 15px !important;">
                                                <?php if ($canExprtIndvCstmr === true){ ?> 
                                                <li>
                                                    <a href="javascript:exprtPrsnlCstmrs();">
                                                        <img src="cmn_images/image007.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                        Export Individual Customers
                                                    </a>
                                                </li>
                                                <?php } if ($canImprtIndvCstmr === true) { ?> 
                                                <li>
                                                    <a href="javascript:imprtPrsnlCstmrs();">
                                                        <img src="cmn_images/image007.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                        Import Individual Customers
                                                    </a>
                                                </li>
                                                <?php } ?> 
                                                <?php if ($canExprtNtnlIDsIndvCstmr === true) { ?> 
                                                <li>
                                                    <a href="javascript:alert('exprtNtnlID)');">
                                                        <img src="cmn_images/image007.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                        Export National IDs
                                                    </a>
                                                </li>
                                                <?php } if ($canImprtNtnlIDsIndvCstmr === true) { ?> 
                                                <li>
                                                    <a href="javascript:imprtNtnlID();">
                                                        <img src="cmn_images/image007.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                        Import National IDs
                                                    </a>
                                                </li>
                                                <?php } ?>
                                                <?php if ($canExprtAdtnlDtaIndvCstmr === true) { ?> 
                                                <li>
                                                    <a href="javascript:alert('exprtAdtnlPrsnDta()');">
                                                        <img src="cmn_images/image007.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                        Export Additional Data
                                                    </a>
                                                </li>
                                                <?php } if ($canImprtAdtnlDtaIndvCstmr === true) { ?>
                                                <li>
                                                    <a href="javascript:alert('imprtAdtnlPrsnDta');">
                                                        <img src="cmn_images/image007.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                        Import Additional Data
                                                    </a>
                                                </li>
                                                <?php } ?>
                                            </ul>
                                        </div>
                                        <button type="button" data-toggle="tooltip" data-placement="bottom" title="Merge Agents" class="btn btn-default btn-sm" onclick="getOneMergeCustsForm(-1, 1.1, 'ShowDialog', <?php echo $vwtyp; ?>, 'Merge Customers', 'mcfIndCstmr');">
                                            <img src="cmn_images/merge_1.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                        </button>
                                    </div>
                                </div>
                                <div class="col-md-8" style="float:right !important;">
                                    <div class="col-lg-12" style="margin-bottom: 1px;"><!-- style="padding: 5px 1px 0px 15px !important">-->
                                        <div class="input-group">
                                            <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>                                        
                                            <select class="form-control" id="mcfIndCstmrStatusSrchIn" onchange="javascript:getCustData('', '#allmodules', 'grp=17&typ=1&pg=1&subPgNo=1.1','mcfIndCstmr');">
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
                                            <select class="form-control" id="mcfIndCstmrBranchSrchIn" onchange="javascript:getCustData('', '#allmodules', 'grp=17&typ=1&pg=1&subPgNo=1.1','mcfIndCstmr');">
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
                                            <?php if ($canEditIndvCstmr === true) { ?>
                                                <th>...</th>
                                            <?php } ?>
                                            <?php if ($canDelIndvCstmr === true) { ?>
                                                <th>...</th>
                                            <?php } ?>
                                            <!--<th>...</th>-->
                                            <th>ID No.</th>
                                            <th>Full Name</th>
                                            <th>Linked Firm</th>
                                            <th>Email</th>
                                            <th>Branch</th>
                                            <th>Status</th>
                                            <?php if ($canViewIndvCstmr === true) { ?>
                                                <th>...</th>
                                            <?php } ?>
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
                                                <?php if ($canEditIndvCstmr === true) { ?>                                    
                                                    <td class="lovtd">
                                                        <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="Edit Customer" 
                                                                onclick="getCustomersForm('myFormsModalLg', 'myFormsModalBodyLg', 'myFormsModalTitleLg', 'Edit Customer Profile', 11, <?php echo $subPgNo; ?>, 0, 'EDIT', <?php echo $row[0]; ?>, '', 'indCustTableRow<?php echo $cntr; ?>');" style="padding:2px !important;" style="padding:2px !important;">
                                                            <!--<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>-->
                                                            <img src="cmn_images/edit32.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                        </button>
                                                    </td>
                                                <?php } if ($canDelIndvCstmr === true) { ?>
                                                <td class="lovtd">
                                                    <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="Delete Customer" onclick="deleteCustPerson(<?php echo $row[0]; ?>, '<?php echo $row[27]; ?>', <?php echo $subPgNo; ?>);" style="padding:2px !important;" style="padding:2px !important;">
                                                        <!--<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>-->
                                                        <img src="cmn_images/delete.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                    </button>
                                                </td>
                                                <?php } ?>
                                                <td class="lovtd" style="display:none !important;">
                                                    <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="Print Profile" onclick="" style="padding:2px !important;" style="padding:2px !important;">
                                                        <!--<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>-->
                                                        <img src="cmn_images/pdf.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                    </button>
                                                </td>
                                                <td class="lovtd"><?php echo $row[1]; ?></td>
                                                <td class="lovtd"><?php echo $row[2]; ?></td>
                                                <td class="lovtd"><?php echo str_replace("()", "", $row[22] . " (" . $row[24] . ")"); ?></td>
                                                <td class="lovtd"><?php echo $row[14]; ?></td>
                                                <td class="lovtd"><?php
                                                    echo getGnrlRecNm("org.org_sites_locations", "location_id",
                                                            "site_desc||' ('||location_code_name||')'", $row[28]);
                                                    ?>
                                                </td>
                                                <td class="lovtd"><?php echo $row[27]; ?></td>
                                                <?php if ($canViewIndvCstmr === true) { ?>
                                                <td class="lovtd">                                                                                                                                        
                                                    <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Basic Profile" onclick="getCustomersForm('myFormsModalLg', 'myFormsModalBodyLg', 'myFormsModalTitleLg', 'Add New Individual Customer', 11, <?php echo $subPgNo; ?>, 0, 'VIEW', <?php echo $row[0]; ?>)" style="padding:2px !important;" style="padding:2px !important;">
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
        } else if ($subPgNo == 1.2) {//CORPORATE CUSTOMER
            echo $cntent .= "
					<li onclick=\"openATab('#allmodules', 'grp=17&typ=1&pg=1&subPgNo=1.2');\">
                                                <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
						<span style=\"text-decoration:none;\">Corporate/Joint/Trust Customers</span>
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
                //$canAddPrsn = test_prmssns($dfltPrvldgs[7], $mdlNm);
                /* echo $cntent . "<li>
                  <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                  <span style=\"text-decoration:none;\">Data Administration</span>
                  </li>
                  </ul>
                  </div>"; */
                $total = get_CorpCustTtl($statusSrchIn, $branchSrchIn, $srchFor, $srchIn, $orgID, $searchAll, "Corporate");
                if ($pageNo > ceil($total / $lmtSze)) {
                    $pageNo = 1;
                } else if ($pageNo < 1) {
                    $pageNo = ceil($total / $lmtSze);
                }

                $curIdx = $pageNo - 1;
                $result = get_CorpCust($statusSrchIn, $branchSrchIn, $srchFor, $srchIn, $curIdx, $lmtSze, $orgID, $searchAll, $sortBy, "Corporate");
                $cntr = 0;
                $colClassType1 = "col-lg-2";
                $colClassType2 = "col-lg-3";
                ?> 
                <form id='mcfCorpCstmrForm' action='' method='post' accept-charset='UTF-8'>
                    <div class="row" style="margin-bottom:1px;">
                        <?php if ($canAddCorpCstmr === true) { ?>   
                            <div class="<?php echo $colClassType1; ?>" style="padding:0px 1px 0px 15px !important;">                    
                                <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="getCustomersForm('myFormsModalLg', 'myFormsModalBodyLg', 'myFormsModalTitleLg', 'Add New Corporate/Joint/Trust Customer', 11, <?php echo $subPgNo; ?>, 0, 'ADD', -1);">
                                    <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                    New Customer
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
                                <input class="form-control" id="mcfCorpCstmrSrchFor" type = "text" placeholder="Search For" value="<?php
                                echo trim(str_replace("%", " ", $srchFor));
                                ?>" onkeyup="enterKeyFuncCust(event, '', '#allmodules', 'grp=17&typ=1&pg=1&subPgNo=1.2', 'mcfCorpCstmr')">
                                <input id="mcfCorpCstmrPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                <label class="btn btn-primary btn-file input-group-addon" onclick="getCustData('clear', '#allmodules', 'grp=17&typ=1&pg=1&subPgNo=1.2', 'mcfCorpCstmr')">
                                    <span class="glyphicon glyphicon-remove"></span>
                                </label>
                                <label class="btn btn-primary btn-file input-group-addon" onclick="getCustData('', '#allmodules', 'grp=17&typ=1&pg=1&subPgNo=1.2', 'mcfCorpCstmr')">
                                    <span class="glyphicon glyphicon-search"></span>
                                </label> 
                            </div>
                        </div>
                        <div class="<?php echo $colClassType2; ?>">
                            <div class="input-group">
                                <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                <select data-placeholder="Select..." class="form-control chosen-select" id="mcfCorpCstmrSrchIn">
                                    <?php
                                    $valslctdArry = array("", "", "", "", "");
                                    $srchInsArrys = array("Name", "ID", "Description"
                                        , "Classification", "Date of Establishment");
                                    for ($z = 0; $z < count($srchInsArrys); $z++) {
                                        if ($srchIn == $srchInsArrys[$z]) {
                                            $valslctdArry[$z] = "selected";
                                        }
                                        ?>
                                        <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                    <?php } ?>
                                </select>
                                <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                <select data-placeholder="Select..." class="form-control chosen-select" id="mcfCorpCstmrDsplySze" style="min-width:70px !important;">                            
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
                                <select data-placeholder="Select..." class="form-control chosen-select" id="mcfCorpCstmrSortBy">
                                    <?php
                                    $valslctdArry = array("", "", "", "", "");
                                    $srchInsArrys = array("Date Added DESC", "Name", "ID ASC", "ID DESC", "Date of Establishment");
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
                                        <a href="javascript:getCustData('previous', '#allmodules', 'grp=17&typ=1&pg=1&subPgNo=1.2','mcfCorpCstmr');" aria-label="Previous">
                                            <span aria-hidden="true">&laquo;</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:getCustData('next', '#allmodules', 'grp=17&typ=1&pg=1&subPgNo=1.2','mcfCorpCstmr');" aria-label="Next">
                                            <span aria-hidden="true">&raquo;</span>
                                        </a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                    <div class="row" style="margin-bottom:2px;padding:1px 15px 2px 15px !important">   
                        <div  class="col-md-12" style="padding:2px 1px 2px 1px !important;border-top:1px solid #ddd;border-bottom:1px solid #ddd;">
                            <div class="col-md-4" style="float:left;">
                                <div class="btn-group" style="margin-bottom: 1px;">
                                    <button type="button" class="btn btn-default btn-sm" style="height: 30px !important;" onclick="">
                                        <img src="cmn_images/image007.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                        Excel
                                    </button>
                                    <button class="btn btn-info dropdown-toggle btn-sm" style="height:30px !important;" type="button" data-toggle="dropdown">
                                        <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu" role="menu" style="margin-left: 15px !important;">
                                        <?php if ($canExprtCorpCstmr === true){ ?> 
                                        <li>
                                            <a href="javascript:exprtCorpGrpCstmrs();">
                                                <img src="cmn_images/image007.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                Export Corporate Customers
                                            </a>
                                        </li>
                                        <?php } if ($canImprtCorpCstmr === true) { ?> 
                                        <li>
                                            <a href="javascript:imprtCorpGrpCstmrs('Corporate Customers');">
                                                <img src="cmn_images/image007.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                Import Corporate Customers
                                            </a>
                                        </li>
                                        <?php } ?> 
                                        <?php if ($canExprtNtnlIDsIndvCstmr === true) { ?> 
                                        <li>
                                            <a href="javascript:exprtPrsnlCstmrs();">
                                                <img src="cmn_images/image007.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                Export Directors
                                            </a>
                                        </li>
                                        <?php } if ($canExprtDrctrs === true) { ?> 
                                        <li>
                                            <a href="javascript:imprtPrsnlCstmrs;">
                                                <img src="cmn_images/image007.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                Import Directors
                                            </a>
                                        </li>
                                        <?php } ?>
                                        <?php if ($canImprtDrctrs === true) { ?> 
                                        <li>
                                            <a href="javascript:exprtPrsnlCstmrs();">
                                                <img src="cmn_images/image007.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                Export Additional Data
                                            </a>
                                        </li>
                                        <?php } if ($canImprtAdtnlDtaCorpCstmr === true) { ?>
                                        <li>
                                            <a href="javascript:imprtPrsnlCstmrs;">
                                                <img src="cmn_images/image007.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                Import Additional Data
                                            </a>
                                        </li>
                                        <?php } ?>
                                    </ul>
                                </div>                              
                            </div>
                            <div class="col-md-8" style="float:right !important;">
                                <div class="col-lg-12" style="margin-bottom: 1px;"><!-- style="padding: 5px 1px 0px 15px !important">-->
                                    <div class="input-group">
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>                                        
                                        <select class="form-control" id="mcfCorpCstmrStatusSrchIn" onchange="javascript:getCustData('', '#allmodules', 'grp=17&typ=1&pg=1&subPgNo=1.2','mcfCorpCstmr');">
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
                                        <select class="form-control" id="mcfCorpCstmrBranchSrchIn" onchange="javascript:getCustData('', '#allmodules', 'grp=17&typ=1&pg=1&subPgNo=1.2','mcfCorpCstmr');">
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
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>                      
                    <div class="row"> 
                        <div  class="col-md-12">
                            <table class="table table-striped table-bordered table-responsive" id="mcfCorpCstmrTable" cellspacing="0" width="100%" style="width:100%;">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <?php if ($canEditCorpCstmr === true) { ?>
                                            <th>...</th>
                                        <?php } ?>
                                        <?php if ($canDelCorpCstmr === true) { ?>
                                            <th>...</th>
                                        <?php } ?>
                                        <!--<th>...</th>-->
                                        <th>ID No.</th>
                                        <th>Customer Name</th>
                                        <th>Classification</th>
                                        <th>Contact Nos.</th>
                                        <th>Branch</th>
                                        <th>Status</th>
                                        <?php if ($canViewCorpCstmr === true) { ?>
                                            <th>...</th>
                                        <?php } ?>
                                        <!--<th>...</th>-->
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    while ($row = loc_db_fetch_array($result)) {
                                        /**/
                                        $cntr += 1;
                                        ?>
                                        <tr id="mcfCorpCstmrRow<?php echo $cntr; ?>">                                    
                                            <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                            <?php if ($canEditCorpCstmr === true) { ?>                                    
                                                <td class="lovtd">
                                                    <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="Edit Corporate Customer" 
                                                            onclick="getCustomersForm('myFormsModalLg', 'myFormsModalBodyLg', 'myFormsModalTitleLg', 'EDIT CORPORATE CUSTOMER PROFILE', 11, <?php echo $subPgNo; ?>, 0, 'EDIT', <?php echo $row[0]; ?>);" style="padding:2px !important;" style="padding:2px !important;">
                                                        <!--<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>-->
                                                        <img src="cmn_images/edit32.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                    </button>
                                                </td>
                                            <?php } if ($canDelCorpCstmr === true) { ?>
                                            <td class="lovtd">
                                                <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="Delete Corporate Customer" onclick="deleteCustPerson(<?php echo $row[0]; ?>, '<?php echo $row[23]; ?>', <?php echo $subPgNo; ?>);" style="padding:2px !important;" style="padding:2px !important;">
                                                    <!--<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>-->
                                                    <img src="cmn_images/delete.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                </button>
                                            </td>
                                            <?php } ?>
                                            <td class="lovtd" style="display:none !important;">
                                                <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="Print Profile" onclick="" style="padding:2px !important;" style="padding:2px !important;">
                                                    <!--<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>-->
                                                    <img src="cmn_images/pdf.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                </button>
                                            </td>
                                            <td class="lovtd"><?php echo $row[1]; ?></td>
                                            <td class="lovtd"><?php echo $row[2]; ?></td>
                                            <td class="lovtd"><?php echo $row[4]; ?></td>
                                            <td class="lovtd"><?php echo trim($row[14]); ?></td>
                                            <td class="lovtd"><?php echo getGnrlRecNm("org.org_sites_locations", "location_id",
                                                    "site_desc||' ('||location_code_name||')'", $row[22]); ?></td>
                                            <td class="lovtd"><?php echo $row[23]; ?></td>
                                            <?php if ($canViewCorpCstmr === true) { ?>              
                                            <td class="lovtd">
                                                <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Corporate Customer" onclick="getCustomersForm('myFormsModalLg', 'myFormsModalBodyLg', 'myFormsModalTitleLg', 'VIEW CORPORATE CUSTOMER PROFILE', 11, <?php echo $subPgNo; ?>, 0, 'VIEW', <?php echo $row[0]; ?>);" style="padding:2px !important;" style="padding:2px !important;">
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
                </form>
                <?php
            }
        } 
        else if ($subPgNo == 1.3) {//CUSTOMER GROUPS
            echo $cntent .= "
					<li onclick=\"openATab('#allmodules', 'grp=17&typ=1&pg=1&subPgNo=1.3');\">
                                                <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
						<span style=\"text-decoration:none;\">Customer Groups</span>
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
                //$canAddPrsn = test_prmssns($dfltPrvldgs[7], $mdlNm);
                $total = get_CorpCustTtl($statusSrchIn, $branchSrchIn, $srchFor, $srchIn, $orgID, $searchAll, "Group");
                if ($pageNo > ceil($total / $lmtSze)) {
                    $pageNo = 1;
                } else if ($pageNo < 1) {
                    $pageNo = ceil($total / $lmtSze);
                }

                $curIdx = $pageNo - 1;
                $result = get_CorpCust($statusSrchIn, $branchSrchIn, $srchFor, $srchIn, $curIdx, $lmtSze, $orgID, $searchAll, $sortBy, "Group");
                $cntr = 0;
                $colClassType1 = "col-lg-2";
                $colClassType2 = "col-lg-3";
                ?> 
                <form id='mcfGrpCstmrForm' action='' method='post' accept-charset='UTF-8'>
                    <div class="row" style="margin-bottom:1px;">
                        <?php if ($canAddGrpCstmr === true) { ?>    
                            <div class="<?php echo $colClassType1; ?>" style="padding:0px 1px 0px 15px !important;">                    
                                <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="getCustomersForm('myFormsModalLg', 'myFormsModalBodyLg', 'myFormsModalTitleLg', 'Add Customer Group', 11, <?php echo $subPgNo; ?>, 0, 'ADD', -1);">
                                    <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                    New Group
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
                                <input class="form-control" id="mcfGrpCstmrSrchFor" type = "text" placeholder="Search For" value="<?php
                                echo trim(str_replace("%", " ", $srchFor));
                                ?>" onkeyup="enterKeyFuncCust(event, '', '#allmodules', 'grp=17&typ=1&pg=1&subPgNo=1.3', 'mcfGrpCstmr')">
                                <input id="mcfGrpCstmrPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                <label class="btn btn-primary btn-file input-group-addon" onclick="getCustData('clear', '#allmodules', 'grp=17&typ=1&pg=1&subPgNo=1.3', 'mcfGrpCstmr')">
                                    <span class="glyphicon glyphicon-remove"></span>
                                </label>
                                <label class="btn btn-primary btn-file input-group-addon" onclick="getCustData('', '#allmodules', 'grp=17&typ=1&pg=1&subPgNo=1.3', 'mcfGrpCstmr')">
                                    <span class="glyphicon glyphicon-search"></span>
                                </label> 
                            </div>
                        </div>
                        <div class="<?php echo $colClassType2; ?>">
                            <div class="input-group">
                                <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                <select data-placeholder="Select..." class="form-control chosen-select" id="mcfGrpCstmrSrchIn">
                                    <?php
                                    $valslctdArry = array("", "", "", "", "");
                                    $srchInsArrys = array("Name", "ID", "Description"
                                        , "Classification", "Date of Establishment");
                                    for ($z = 0; $z < count($srchInsArrys); $z++) {
                                        if ($srchIn == $srchInsArrys[$z]) {
                                            $valslctdArry[$z] = "selected";
                                        }
                                        ?>
                                        <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                    <?php } ?>
                                </select>
                                <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                <select data-placeholder="Select..." class="form-control chosen-select" id="mcfGrpCstmrDsplySze" style="min-width:70px !important;">                            
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
                                <select data-placeholder="Select..." class="form-control chosen-select" id="mcfGrpCstmrSortBy">
                                    <?php
                                    $valslctdArry = array("", "", "", "", "");
                                    $srchInsArrys = array("Date Added DESC", "Name", "ID ASC", "ID DESC", "Date of Establishment");
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
                                        <a href="javascript:getCustData('previous', '#allmodules', 'grp=17&typ=1&pg=1&subPgNo=1.3','mcfGrpCstmr');" aria-label="Previous">
                                            <span aria-hidden="true">&laquo;</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:getCustData('next', '#allmodules', 'grp=17&typ=1&pg=1&subPgNo=1.3','mcfGrpCstmr');" aria-label="Next">
                                            <span aria-hidden="true">&raquo;</span>
                                        </a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                    <div class="row" style="margin-bottom:2px;padding:1px 15px 2px 15px !important">   
                        <div  class="col-md-12" style="padding:2px 1px 2px 1px !important;border-top:1px solid #ddd;border-bottom:1px solid #ddd;">
                            <div class="col-md-4" style="float:left;">
                                <div class="btn-group" style="margin-bottom: 1px;">
                                    <button type="button" class="btn btn-default btn-sm" style="height: 30px !important;" onclick="">
                                        <img src="cmn_images/image007.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                        Excel
                                    </button>
                                    <button class="btn btn-info dropdown-toggle btn-sm" style="height:30px !important;" type="button" data-toggle="dropdown">
                                        <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu" role="menu" style="margin-left: 15px !important;">
                                        <?php if ($canExprtGrpCstmr === true){ ?> 
                                        <li>
                                            <a href="javascript:exprtCorpGrpCstmrs();">
                                                <img src="cmn_images/image007.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                Export Customer Groups
                                            </a>
                                        </li>
                                        <?php } if ($canImprtGrpCstmr === true) { ?> 
                                        <li>
                                            <a href="javascript:imprtCorpGrpCstmrs('Customer Groups');">
                                                <img src="cmn_images/image007.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                Import Customer Groups
                                            </a>
                                        </li>
                                        <?php } ?> 
                                        <?php if ($canExprtGrpMmbrs === true) { ?> 
                                        <li>
                                            <a href="javascript:exprtPrsnlCstmrs();">
                                                <img src="cmn_images/image007.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                Export Group Members
                                            </a>
                                        </li>
                                        <?php } if ($canImprtGrpMmbrs === true) { ?> 
                                        <li>
                                            <a href="javascript:imprtPrsnlCstmrs;">
                                                <img src="cmn_images/image007.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                Import Group Members
                                            </a>
                                        </li>
                                        <?php } ?>
                                        <?php if ($canExprtAdtnlDtaGrpCstmr === true) { ?> 
                                        <li>
                                            <a href="javascript:exprtPrsnlCstmrs();">
                                                <img src="cmn_images/image007.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                Export Additional Data
                                            </a>
                                        </li>
                                        <?php } if ($canImprtAdtnlDtaGrpCstmr === true) { ?>
                                        <li>
                                            <a href="javascript:imprtPrsnlCstmrs;">
                                                <img src="cmn_images/image007.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                Import Additional Data
                                            </a>
                                        </li>
                                        <?php } ?>
                                    </ul>
                                </div>                              
                            </div>
                            <div class="col-md-8" style="float:right !important;">
                                <div class="col-lg-12" style="margin-bottom: 1px;"><!-- style="padding: 5px 1px 0px 15px !important">-->
                                    <div class="input-group">
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>                                        
                                        <select class="form-control" id="mcfGrpCstmrStatusSrchIn" onchange="javascript:getCustData('', '#allmodules', 'grp=17&typ=1&pg=1&subPgNo=1.3','mcfGrpCstmr');">
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
                                        <select class="form-control" id="mcfGrpCstmrBranchSrchIn" onchange="javascript:getCustData('', '#allmodules', 'grp=17&typ=1&pg=1&subPgNo=1.3','mcfGrpCstmr');">
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
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>                      
                    <div class="row"> 
                        <div  class="col-md-12">
                            <table class="table table-striped table-bordered table-responsive" id="mcfGrpCstmrTable" cellspacing="0" width="100%" style="width:100%;">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <?php if ($canEditGrpCstmr === true) { ?>
                                            <th>...</th>
                                        <?php } ?>
                                        <?php if ($canDelGrpCstmr === true) { ?>
                                            <th>...</th>
                                        <?php } ?>
                                        <!--<th>...</th>-->
                                        <th>ID No.</th>
                                        <th>Group Name</th>
                                        <th>Classification</th>
                                        <th>Contact Nos.</th>
                                        <th>Branch</th>
                                        <th>Status</th>
                                        <?php if ($canViewGrpCstmr === true) { ?>
                                            <th>...</th>
                                        <?php } ?>
                                        <!--<th>...</th>-->
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    while ($row = loc_db_fetch_array($result)) {
                                        /**/
                                        $cntr += 1;
                                        ?>
                                        <tr id="mcfGrpCstmrRow<?php echo $cntr; ?>">                                    
                                            <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                            <?php if ($canEditGrpCstmr === true) { ?>                                    
                                                <td class="lovtd">
                                                    <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="Edit Customer Group" 
                                                            onclick="getCustomersForm('myFormsModalLg', 'myFormsModalBodyLg', 'myFormsModalTitleLg', 'EDIT CUSTOMER GROUP PROFILE', 11, <?php echo $subPgNo; ?>, 0, 'EDIT', <?php echo $row[0]; ?>);" style="padding:2px !important;" style="padding:2px !important;">
                                                        <!--<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>-->
                                                        <img src="cmn_images/edit32.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                    </button>
                                                </td>
                                            <?php } ?>
                                            <?php if ($canDelGrpCstmr === true) { ?>       
                                            <td class="lovtd">
                                                <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="Delete Customer Group" onclick="deleteCustPerson(<?php echo $row[0]; ?>, '<?php echo $row[23]; ?>', <?php echo $subPgNo; ?>);" style="padding:2px !important;" style="padding:2px !important;">
                                                    <!--<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>-->
                                                    <img src="cmn_images/delete.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                </button>
                                            </td>
                                            <?php } ?>
                                            <td class="lovtd" style="display:none !important;">
                                                <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="Print Profile" onclick="" style="padding:2px !important;" style="padding:2px !important;">
                                                    <!--<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>-->
                                                    <img src="cmn_images/pdf.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                </button>
                                            </td>
                                            <td class="lovtd"><?php echo $row[1]; ?></td>
                                            <td class="lovtd"><?php echo $row[2]; ?></td>
                                            <td class="lovtd"><?php echo $row[4]; ?></td>
                                            <td class="lovtd"><?php echo trim($row[14]); ?></td>
                                            <td class="lovtd"><?php echo getGnrlRecNm("org.org_sites_locations", "location_id",
                                                    "site_desc||' ('||location_code_name||')'", $row[22]); ?></td>
                                            <td class="lovtd"><?php echo $row[23]; ?></td>
                                            <?php if ($canViewGrpCstmr === true) { ?>       
                                            <td class="lovtd">
                                                <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Basic Profile" onclick="getCustomersForm('myFormsModalLg', 'myFormsModalBodyLg', 'myFormsModalTitleLg', 'VIEW CUSTOMER GROUP PROFILE', 11, <?php echo $subPgNo; ?>, 0, 'VIEW', <?php echo $row[0]; ?>);" style="padding:2px !important;" style="padding:2px !important;">
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
                </form>
                <?php
            }
        } 
        else if ($subPgNo == 1.4) {//OTHER PERSONS
            echo $cntent .= "
					<li onclick=\"openATab('#allmodules', 'grp=17&typ=1&pg=1&subPgNo=1.4');\">
                                                <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
						<span style=\"text-decoration:none;\">Other Persons</span>
                                        </li></div>";
            $error = "";
            $searchAll = true;

            $srchFor = isset($_POST['searchfor']) ? cleanInputData($_POST['searchfor']) : '';
            $srchIn = isset($_POST['searchin']) ? cleanInputData($_POST['searchin']) : 'Both';
            $pageNo = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
            $lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 10;
            $sortBy = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : "Date Added DESC";
            $otherPrsnType = isset($_POST['prsnType']) ? cleanInputData($_POST['prsnType']) : "All";

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
                $total = get_OtherPrsnTtl($statusSrchIn, $branchSrchIn, $srchFor, $srchIn, $orgID, $searchAll, $otherPrsnType);
                if ($pageNo > ceil($total / $lmtSze)) {
                    $pageNo = 1;
                } else if ($pageNo < 1) {
                    $pageNo = ceil($total / $lmtSze);
                }

                $curIdx = $pageNo - 1;
                $result = get_OtherPrsn($statusSrchIn, $branchSrchIn, $srchFor, $srchIn, $curIdx, $lmtSze, $orgID, $searchAll, $sortBy, $otherPrsnType);
                $cntr = 0;
                $colClassType1 = "col-lg-2";
                $colClassType2 = "col-lg-8";
                ?> 
                <form id='mcfOthPCstmrForm' action='' method='post' accept-charset='UTF-8'>
                    <div class="row" style="margin-bottom:1px;">
                        <?php
                        if ($canAddOtherPrsn === true) {
                            ?>   
                            <div class="<?php echo $colClassType1; ?>" style="padding:0px 1px 0px 15px !important;">                    
                                <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="getCustomersForm('myFormsModalLg', 'myFormsModalBodyLg', 'myFormsModalTitleLg', 'New Other Individual', 11, <?php echo $subPgNo; ?>, 0, 'ADD', -1);">
                                    <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                    New Person
                                </button>
                            </div>
                            <?php
                        } else {
                            //$colClassType1 = "col-lg-2";
                            $colClassType2 = "col-lg-10";
                        }
                        ?>
                        <div class="<?php echo $colClassType2; ?>" style="padding:0px 1px 0px 15px !important;">
                            <div class="<?php echo "col-lg-3"; ?>" style="padding:0px 15px 0px 15px !important;">
                                <div class="input-group">
                                    <input class="form-control" id="mcfOthPCstmrSrchFor" type = "text" placeholder="Search For" value="<?php
                                    echo trim(str_replace("%", " ", $srchFor));
                                    ?>" onkeyup="enterKeyFuncCust(event, '', '#allmodules', 'grp=17&typ=1&pg=1&subPgNo=1.4', 'mcfOthPCstmr')">
                                    <input id="mcfOthPCstmrPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getCustData('clear', '#allmodules', 'grp=17&typ=1&pg=1&subPgNo=1.4', 'mcfOthPCstmr')">
                                        <span class="glyphicon glyphicon-remove"></span>
                                    </label>
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getCustData('', '#allmodules', 'grp=17&typ=1&pg=1&subPgNo=1.4', 'mcfOthPCstmr')">
                                        <span class="glyphicon glyphicon-search"></span>
                                    </label> 
                                </div>
                            </div>
                            <div class="<?php echo "col-lg-4"; ?>">
                                <div class="input-group">
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="mcfOthPCstmrSrchIn">
                                        <?php
                                        $valslctdArry = array("", "", "", "", "", "", "", "", "", "");
                                        $srchInsArrys = array("Full Name", "ID", "Residential Address"
                                            , "Contact Information", "Linked Firm/Workplace", "Date of Birth",
                                            "Home Town", "Gender", "Marital Status");
                                        for ($z = 0; $z < count($srchInsArrys); $z++) {
                                            if ($srchIn == $srchInsArrys[$z]) {
                                                $valslctdArry[$z] = "selected";
                                            }
                                            ?>
                                            <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                        <?php } ?>
                                    </select>
                                    <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="mcfOthPCstmrDsplySze" style="min-width:70px !important;">                            
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
                            <div class="<?php echo "col-lg-2"; ?>" style="padding:0px 15px 0px 15px !important;">
                                <select data-placeholder="Select..." class="form-control chosen-select" id="mcfOthPCstmrOtherPrsnType" style="min-width:70px !important;">                            
                                    <?php
                                    $valslctdArry = array("", "", "", "");
                                    $dsplySzeArry = array("All", "Director", "Guarantor", "Next of Kin");
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
                            <div class="<?php echo "col-lg-3"; ?>">
                                <div class="input-group">                        
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-sort-by-attributes"></span></span>
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="mcfOthPCstmrSortBy">
                                        <?php
                                        $valslctdArry = array("", "", "", "", "");
                                        $srchInsArrys = array("Date Added DESC", "Full Name", "ID ASC", "ID DESC", "Date of Birth");
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
                        </div>
                        <div class="<?php echo "col-lg-2"; ?>">
                            <nav aria-label="Page navigation">
                                <ul class="pagination" style="margin: 0px !important;">
                                    <li>
                                        <a href="javascript:getCustData('previous', '#allmodules', 'grp=17&typ=1&pg=1&subPgNo=1.4','mcfOthPCstmr');" aria-label="Previous">
                                            <span aria-hidden="true">&laquo;</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:getCustData('next', '#allmodules', 'grp=17&typ=1&pg=1&subPgNo=1.4','mcfOthPCstmr');" aria-label="Next">
                                            <span aria-hidden="true">&raquo;</span>
                                        </a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                    <div class="row" style="margin-bottom:2px;padding:1px 15px 2px 15px !important">   
                        <div  class="col-md-12" style="padding:2px 1px 2px 1px !important;border-top:1px solid #ddd;border-bottom:1px solid #ddd;">
                            <div class="col-md-4" style="float:left;">
                                <div class="btn-group" style="margin-bottom: 1px;">
                                    <button type="button" class="btn btn-default btn-sm" style="height: 30px !important;" onclick="">
                                        <img src="cmn_images/image007.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                        Excel
                                    </button>
                                    <button class="btn btn-info dropdown-toggle btn-sm" style="height:30px !important;" type="button" data-toggle="dropdown">
                                        <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu" role="menu" style="margin-left: 15px !important;">
                                        <?php if ($canExprtGrpCstmr === true){ ?> 
                                        <li>
                                            <a href="javascript:alert(exprtOtherPersons());">
                                                <img src="cmn_images/image007.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                Export Other Persons
                                            </a>
                                        </li>
                                        <?php } if ($canImprtGrpCstmr === true) { ?> 
                                        <li>
                                            <a href="javascript:imprtOtherPersons();">
                                                <img src="cmn_images/image007.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                Import Other Persons
                                            </a>
                                        </li>
                                        <?php } ?>
                                    </ul>
                                </div>                              
                            </div>
                            <div class="col-md-8" style="float:right !important;">
                                <div class="col-lg-12" style="margin-bottom: 1px;"><!-- style="padding: 5px 1px 0px 15px !important">-->
                                    <div class="input-group">
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>                                        
                                        <select class="form-control" id="mcfOthPCstmrStatusSrchIn" onchange="javascript:getCustData('', '#allmodules', 'grp=17&typ=1&pg=1&subPgNo=1.4','mcfOthPCstmr');">
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
                                        <select class="form-control" id="mcfOthPCstmrBranchSrchIn" onchange="javascript:getCustData('', '#allmodules', 'grp=17&typ=1&pg=1&subPgNo=1.4','mcfOthPCstmr');">
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
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>                         
                    <div class="row"> 
                        <div  class="col-md-12">
                            <table class="table table-striped table-bordered table-responsive" id="mcfOthPCstmrTable" cellspacing="0" width="100%" style="width:100%;">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <?php if ($canEditOtherPrsn === true) { ?>
                                            <th>...</th>
                                        <?php } ?>
                                        <?php if ($canDelOtherPrsn === true) { ?>
                                            <th>...</th>
                                        <?php } ?>
                                        <!--<th>...</th>-->
                                        <th>ID No.</th>
                                        <th>Full Name</th>
                                        <th>Person Type</th>
                                        <th>Email</th>
                                        <th>Branch</th>
                                        <th>Status</th>
                                        <?php if ($canViewOtherPrsn === true) { ?>
                                            <th>...</th>
                                        <?php } ?>
                                        <!--<th>...</th>-->
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    while ($row = loc_db_fetch_array($result)) {
                                        /**/
                                        $cntr += 1;
                                        ?>
                                        <tr id="mcfOthPCstmrRow<?php echo $cntr; ?>">                                    
                                            <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                            <?php if ($canEditOtherPrsn === true) { ?>                                    
                                                <td class="lovtd">
                                                    <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="Edit Other Person Profile" 
                                                            onclick="getCustomersForm('myFormsModalLg', 'myFormsModalBodyLg', 'myFormsModalTitleLg', 'Edit Other Person Profile', 11, <?php echo $subPgNo; ?>, 0, 'EDIT', <?php echo $row[0]; ?>);" style="padding:2px !important;" style="padding:2px !important;">
                                                        <!--<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>-->
                                                        <img src="cmn_images/edit32.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                    </button>
                                                </td>
                                            <?php } ?>
                                            <?php if ($canDelOtherPrsn === true) { ?>               
                                            <td class="lovtd">
                                                <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="Delete Other Person" onclick="deleteCustPerson(<?php echo $row[0]; ?>, '<?php echo $row[29]; ?>', <?php echo $subPgNo; ?>);	" style="padding:2px !important;" style="padding:2px !important;">
                                                    <!--<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>-->
                                                    <img src="cmn_images/delete.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                </button>
                                            </td>
                                            <?php } ?>
                                            <td class="lovtd" style="display:none !important;">
                                                <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="Print Profile" onclick="" style="padding:2px !important;" style="padding:2px !important;">
                                                    <!--<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>-->
                                                    <img src="cmn_images/pdf.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                </button>
                                            </td>
                                            <td class="lovtd"><?php echo $row[1]; ?></td>
                                            <td class="lovtd"><?php echo $row[2]; ?></td>
                                            <td class="lovtd"><?php echo $row[25]; ?></td>
                                            <td class="lovtd"><?php echo $row[14]; ?></td>
                                            <td class="lovtd"><?php
                                                echo getGnrlRecNm("org.org_sites_locations", "location_id",
                                                        "site_desc||' ('||location_code_name||')'", $row[26]);
                                                ?>
                                            </td>
                                            <td class="lovtd"><?php echo $row[29]; ?></td>
                                            <?php if ($canViewOtherPrsn === true) { ?>                            
                                            <td class="lovtd">
                                                <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Basic Profile" onclick="getCustomersForm('myFormsModalLg', 'myFormsModalBodyLg', 'myFormsModalTitleLg', 'View Other Person Profile', 11, <?php echo $subPgNo; ?>, 0, 'VIEW', <?php echo $row[0]; ?>);" style="padding:2px !important;" style="padding:2px !important;">
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
                </form>
                <?php
            }
        }
        else if ($subPgNo == 1.5) {//CUSTOMER CORRESPONDENCE
            echo $cntent .= "
					<li onclick=\"openATab('#allmodules', 'grp=17&typ=1&pg=1&subPgNo=1.5');\">
                                                <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
						<span style=\"text-decoration:none;\">Customer Correspondence</span>
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
                //$canAddPrsn = test_prmssns($dfltPrvldgs[7], $mdlNm);
                $total = get_CstmrCrspndncTblTtl($rqstStatusSrchIn, $statusSrchIn, $branchSrchIn, $srchFor, $srchIn, $orgID, $searchAll);
                if ($pageNo > ceil($total / $lmtSze)) {
                    $pageNo = 1;
                } else if ($pageNo < 1) {
                    $pageNo = ceil($total / $lmtSze);
                }

                $curIdx = $pageNo - 1;
                $result = get_CstmrCrspndncTbl($rqstStatusSrchIn, $statusSrchIn, $branchSrchIn, $srchFor, $srchIn, $curIdx, $lmtSze, $orgID, $searchAll, $sortBy);
                $cntr = 0;
                $colClassType1 = "col-lg-2";
                $colClassType2 = "col-lg-3";
                ?> 
                <form id='cstmrCrspndncForm' action='' method='post' accept-charset='UTF-8'>
                    <div class="row" style="margin-bottom:1px;">
                        <?php if ($canAddGrpCstmr === true) { ?>    
                            <div class="<?php echo $colClassType1; ?>" style="padding:0px 1px 0px 15px !important;">                    
                                <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="getCstmrCrspndncForm('myFormsModalLg', 'myFormsModalBodyLg', 'myFormsModalTitleLg', 'Add Customer Correspondence', 11, <?php echo $subPgNo; ?>, 0, 'ADD', -1);">
                                    <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                    New Correspondence
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
                                <input class="form-control" id="cstmrCrspndncSrchFor" type = "text" placeholder="Search For" value="<?php
                                echo trim(str_replace("%", " ", $srchFor));
                                ?>" onkeyup="enterKeyFuncCust(event, '', '#allmodules', 'grp=17&typ=1&pg=1&subPgNo=1.5', 'cstmrCrspndnc')">
                                <input id="cstmrCrspndncPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                <label class="btn btn-primary btn-file input-group-addon" onclick="getCustData('clear', '#allmodules', 'grp=17&typ=1&pg=1&subPgNo=1.5', 'cstmrCrspndnc')">
                                    <span class="glyphicon glyphicon-remove"></span>
                                </label>
                                <label class="btn btn-primary btn-file input-group-addon" onclick="getCustData('', '#allmodules', 'grp=17&typ=1&pg=1&subPgNo=1.5', 'cstmrCrspndnc')">
                                    <span class="glyphicon glyphicon-search"></span>
                                </label> 
                            </div>
                        </div>
                        <div class="<?php echo $colClassType2; ?>">
                            <div class="input-group">
                                <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                <select data-placeholder="Select..." class="form-control chosen-select" id="cstmrCrspndncSrchIn">
                                    <?php
                                    $valslctdArry = array("", "");
                                    $srchInsArrys = array("Customer Name", "ID");
                                    for ($z = 0; $z < count($srchInsArrys); $z++) {
                                        if ($srchIn == $srchInsArrys[$z]) {
                                            $valslctdArry[$z] = "selected";
                                        }
                                        ?>
                                        <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                    <?php } ?>
                                </select>
                                <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                <select data-placeholder="Select..." class="form-control chosen-select" id="cstmrCrspndncDsplySze" style="min-width:70px !important;">                            
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
                                <select data-placeholder="Select..." class="form-control chosen-select" id="cstmrCrspndncSortBy">
                                    <?php
                                    $valslctdArry = array("", "", "", "", "");
                                    $srchInsArrys = array("Date Added DESC", "Customer Name", "ID ASC", "ID DESC");
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
                                        <a href="javascript:getCustData('previous', '#allmodules', 'grp=17&typ=1&pg=1&subPgNo=1.5','cstmrCrspndnc');" aria-label="Previous">
                                            <span aria-hidden="true">&laquo;</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:getCustData('next', '#allmodules', 'grp=17&typ=1&pg=1&subPgNo=1.5','cstmrCrspndnc');" aria-label="Next">
                                            <span aria-hidden="true">&raquo;</span>
                                        </a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                    <div class="row" style="margin-bottom:2px;padding:1px 15px 2px 15px !important">   
                        <div  class="col-md-12" style="padding:2px 1px 2px 1px !important;border-top:1px solid #ddd;border-bottom:1px solid #ddd;">
                            <div class="col-md-4">
                                <div class="input-group">
                                    <span class="input-group-addon">Request Status</span>                                        
                                    <select class="form-control" id="cstmrCrspndncRqstStatusSrchIn" onchange="javascript:getCustData('', '#allmodules', 'grp=17&typ=1&pg=1&subPgNo=1.5','cstmrCrspndnc');">
                                        <?php

                                            $selectedTxtAS = "";
                                            $selectedTxtATZ = "";
                                            $selectedTxtRJT = "";
                                            if ($rqstStatusSrchIn == "All Statuses") {
                                                $selectedTxtAS = "selected";
                                            }
                                            else if ($rqstStatusSrchIn == "Open") {
                                                $selectedTxtATZ = "selected";
                                            }
                                            else if ($rqstStatusSrchIn == "Closed") {
                                                $selectedTxtRJT = "selected";
                                            }
                                        ?>
                                        <option <?php echo $selectedTxtAS; ?> value="All Statuses">All Statuses</option>
                                        <option value="Open" <?php echo $selectedTxtATZ; ?>>Open</option>
                                        <option value="Closed" <?php echo $selectedTxtRJT; ?>>Closed</option>
                                    </select>                                    
                                </div>                             
                            </div>
                            <div class="col-md-4">
                                <div class="input-group">
                                    <span class="input-group-addon">Approval Status<!--<span class="glyphicon glyphicon-filter"></span>--></span>                                        
                                    <select class="form-control" id="cstmrCrspndncStatusSrchIn" onchange="javascript:getCustData('', '#allmodules', 'grp=17&typ=1&pg=1&subPgNo=1.5','cstmrCrspndnc');">
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
                                </div>                             
                            </div>                            
                            <div class="col-md-4">
                                <div class="input-group">                                 
                                    <span class="input-group-addon">Branch</span>
                                    <select class="form-control" id="cstmrCrspndncBranchSrchIn" onchange="javascript:getCustData('', '#allmodules', 'grp=17&typ=1&pg=1&subPgNo=1.5','cstmrCrspndnc');">
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
                                </div>
                            </div>
                        </div>
                    </div>                      
                    <div class="row"> 
                        <div  class="col-md-12">
                            <table class="table table-striped table-bordered table-responsive" id="cstmrCrspndncTable" cellspacing="0" width="100%" style="width:100%;">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <?php if ($canEditGrpCstmr === true) { ?>
                                            <th>...</th>
                                        <?php } ?>
                                        <?php if ($canDelGrpCstmr === true) { ?>
                                            <th>...</th>
                                        <?php } ?>
                                        <!--<th>...</th>-->
                                        <th>ID No.</th>
                                        <th>Customer Name</th>
                                        <th>Request Type</th>
                                        <th>Request Date</th>
                                        <th>Branch</th>
                                        <th>Status</th>
                                        <?php if ($canViewGrpCstmr === true) { ?>
                                            <th>...</th>
                                        <?php } ?>
                                        <!--<th>...</th>-->
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    while ($row = loc_db_fetch_array($result)) {
                                        /**/
                                        $cntr += 1;
                                        ?>
                                        <tr id="cstmrCrspndncRow<?php echo $cntr; ?>">                                    
                                            <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                            <?php if ($canEditGrpCstmr === true) { ?>                                    
                                                <td class="lovtd">
                                                    <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="Edit Customer Correspondence" 
                                                            onclick="getCstmrCrspndncForm('myFormsModalLg', 'myFormsModalBodyLg', 'myFormsModalTitleLg', 'EDIT CUSTOMER CORRESPONDENCE', 11, <?php echo $subPgNo; ?>, 0, 'EDIT', <?php echo $row[0]; ?>);" style="padding:2px !important;" style="padding:2px !important;">
                                                        <!--<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>-->
                                                        <img src="cmn_images/edit32.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                    </button>
                                                </td>
                                            <?php } ?>
                                            <?php if ($canDelGrpCstmr === true) { ?>       
                                            <td class="lovtd">
                                                <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="Delete Customer Correspondence" onclick="deleteCstmrCrspndnc(<?php echo $row[0]; ?>);" style="padding:2px !important;" style="padding:2px !important;">
                                                    <!--<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>-->
                                                    <img src="cmn_images/delete.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                </button>
                                            </td>
                                            <?php } ?>
                                            <td class="lovtd" style="display:none !important;">
                                                <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="Print Profile" onclick="" style="padding:2px !important;" style="padding:2px !important;">
                                                    <!--<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>-->
                                                    <img src="cmn_images/pdf.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                </button>
                                            </td>
                                            <td class="lovtd"><?php echo $row[1]; ?></td>
                                            <td class="lovtd"><?php echo $row[2]; ?></td>
                                            <td class="lovtd"><?php echo $row[4]; ?></td>
                                            <td class="lovtd"><?php echo trim($row[9]); ?></td>
                                            <td class="lovtd"><?php echo getGnrlRecNm("org.org_sites_locations", "location_id",
                                                    "site_desc||' ('||location_code_name||')'", $row[6]); ?></td>
                                            <td class="lovtd"><?php echo $row[12].", ".$row[13]; ?></td>
                                            <?php if ($canViewGrpCstmr === true) { ?>       
                                            <td class="lovtd">
                                                <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Basic Profile" onclick="getCstmrCrspndncForm('myFormsModalLg', 'myFormsModalBodyLg', 'myFormsModalTitleLg', 'VIEW CUSTOMER GROUP PROFILE', 11, <?php echo $subPgNo; ?>, 0, 'VIEW', <?php echo $row[0]; ?>);" style="padding:2px !important;" style="padding:2px !important;">
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
                </form>
                <?php
            }
        }
        else if ($subPgNo == 1.9) {
                ?>
                
                <?php
                $dsplyNwLine = "";
                $nwRowHtml = urlencode("<tr id=\"oneMergeCustsRow__WWW123WWW\">"
                    . "<td class=\"lovtd\"><span class=\"normaltd\">New</span></td>"
                    . "
                    <td class=\"lovtd\">
                        <div class=\"input-group\" style=\"width:100% !important;\">
                            <input type=\"text\" class=\"form-control rqrdFld\" aria-label=\"...\" id=\"oneMergeCustsRow_WWW123WWW_prmryCustNo\" name=\"oneMergeCustsRow_WWW123WWW_prmryCustNo\" value=\"\" style=\"width:100% !important;\">
                            <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneMergeCustsRow_WWW123WWW_prmryCustID\" value=\"-1\" style=\"width:100% !important;\">                                               
                            <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'EPAY All Telco Agents', '', '', '', 'radio', true, '', 'oneMergeCustsRow_WWW123WWW_prmryCustID', 'oneMergeCustsRow_WWW123WWW_prmryCustNo', 'clear', 1, '', function () {
                                   //afterVMSItemSlctn('oneMergeCustsRow__WWW123WWW');
                                   });\">
                                   <span class=\"glyphicon glyphicon-th-list\"></span>
                            </label>
                        </div>                                              
                    </td>    
                    <td class=\"lovtd\">
                        <div class=\"input-group\" style=\"width:100% !important;\">
                            <input type=\"text\" class=\"form-control rqrdFld\" aria-label=\"...\" id=\"oneMergeCustsRow_WWW123WWW_scndryCustNo\" name=\"oneMergeCustsRow_WWW123WWW_scndryCustNo\" value=\"\" style=\"width:100% !important;\">
                            <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneMergeCustsRow_WWW123WWW_scndryCustID\" value=\"-1\" style=\"width:100% !important;\">                                               
                            <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'EPAY All Telco Agents', '', '', '', 'radio', true, '', 'oneMergeCustsRow_WWW123WWW_scndryCustID', 'oneMergeCustsRow_WWW123WWW_scndryCustNo', 'clear', 1, '', function () {
                                   //afterVMSItemSlctn('oneMergeCustsRow__WWW123WWW');
                                   });\">
                                   <span class=\"glyphicon glyphicon-th-list\"></span>
                            </label>
                        </div>                                              
                    </td>
                    <td class=\"lovtd\">
                        <button type=\"button\" class=\"btn btn-default\" style=\"margin: 0px !important;padding:0px 3px 2px 4px !important;\" onclick=\"removeSltdAgntRcd('oneMergeCustsRow__WWW123WWW');\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Delete Trns. Line\">
                                <img src=\"cmn_images/no.png\" style=\"height:15px; width:auto; position: relative; vertical-align: middle;\">
                        </button>
                    </td>
                </tr>");
                ?> 
            <form class="form-horizontal">
                <div class="row" style="padding:0px 0px 0px 0px !important;">
                    <div class="col-lg-12" style="padding:0px 0px 0px 15px !important;float:left;">
                        <div style="padding-left:0px !important;float:left !important;">
                            <button type="button" class="btn btn-default" style="margin-bottom: 5px;<?php echo $dsplyNwLine; ?>" onclick="insertNewMergeCustsRowsNew('oneMergeCustsLnsTable', 0, '<?php echo $nwRowHtml; ?>');" data-toggle="tooltip" data-placement="bottom" title = "New Transaction Line">
                                <img src="cmn_images/add1-64.png" style="height:20px; width:auto; position: relative; vertical-align: middle;"> New Line
                            </button>
                        </div>
                        <div style="padding-left:0px !important;float:right !important;">
                            <button type="button" class="btn btn-default" style="margin-bottom: 5px;<?php echo $dsplyNwLine; ?>" onclick="mergeCustsNos(<?php echo $pgNo; ?>, <?php echo $vwtyp; ?>, <?php echo $subPgNo; ?>);" data-toggle="tooltip" data-placement="bottom" title = "New Transaction Line">
                                <img src="cmn_images/merge_1.png" style="height:20px; width:auto; position: relative; vertical-align: middle;"> Merge
                            </button>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12" id="oneMergeCustsLnsTblSctn">
                        <table class="table table-striped table-bordered table-responsive" id="oneMergeCustsLnsTable" cellspacing="0" width="100%" style="width:100%;min-width: 340px !important;">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th class="lovtd">Source Customer No.</th>
                                    <th class="lovtd">Destination Customer No.</th>
                                    <th>&nbsp;</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>   
                    </div>
                </div>
                <div class="row">
                    <div class="<?php echo $dsplyPymntCls1; ?>" style="padding:0px 15px 0px 0px !important;float:right;">
                        <button type="button" class="btn btn-default" style="margin-top: 5px; <?php echo $dsplyNwLine; ?>" onclick="mergeCustsNos(<?php echo $pgNo; ?>, <?php echo $vwtyp; ?>, <?php echo $subPgNo; ?>);" data-toggle="tooltip" data-placement="bottom" title = "New Transaction Line">
                            <img src="cmn_images/merge_1.png" style="height:20px; width:auto; position: relative; vertical-align: middle;"> Merge
                        </button>
                    </div>
                </div>
            </form>
          <?php      
            }
        ?>      
        <?php
    }
}
?>
<script type="text/javascript">
    function user_delete(user_id, user_name, pKeyID) {

        var dialog = bootbox.confirm({
            title: 'Delete Biometric Data?',
            size: 'small',
            message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this Biometric Data?<br/>Action cannot be Undone!</p>',
            buttons: {
                confirm: {
                    label: '<i class="fa fa-check"></i> Yes',
                    className: 'btn-success'
                },
                cancel: {
                    label: '<i class="fa fa-times"></i> No',
                    className: 'btn-danger'
                }
            },
            callback: function (result) {
                if (result === true)
                {
                    var dialog1 = bootbox.alert({
                        title: 'Deleting Biometric Data?',
                        size: 'small',
                        message: '<p><i class="fa fa-spin fa-spinner"></i> Deleting Biometric Data...Please Wait...</p>',
                        callback: function () {
                            getCustomersForm('myFormsModalLg', 'myFormsModalBodyLg', 'myFormsModalTitleLg', 'Edit Person Profile', 11, <?php echo $subPgNo; ?>, 0, 'EDIT', pKeyID, '', 'indCustTableRow3');
                        }
                    });
                    dialog1.init(function () {
                        pushBio('<?php echo $flxcde_url; ?>user.php?action=delete&user_id=' + user_id, dialog1);
                    });
                }
            }
        });
    }

    var timer4reg;
    function user_register(user_id, user_name, pKeyID) {
        /*$('body').ajaxMask();*/
        regStats = 0;
        regCt = -1;
        try
        {
            clearInterval(timer4reg);
        } catch (err)
        {
            console.log('Biometric Data Registration timer has been init');
        }
        var msg = 'Biometric Data Registration Initiated';
        var dialog = bootbox.alert({
            title: msg,
            size: 'small',
            message: '<p><i class="fa fa-spin fa-spinner"></i> ' + msg + '...Please Wait...</p>',
            callback: function () {
                getCustomersForm('myFormsModalLg', 'myFormsModalBodyLg', 'myFormsModalTitleLg', 'Edit Person Profile', 11, <?php echo $subPgNo; ?>, 0, 'EDIT', pKeyID, '', 'indCustTableRow3');
            }
        });
        dialog.init(function () {
            var limit = 20;
            var ct = 1;
            var timeout = 5000;
            setInterval(function () {
                console.log("'" + user_name + "' Biometric Data registration checking...");
                msg = "'" + user_name + "' Biometric Data registration checking...";
                dialog.find('.bootbox-body').html('<p><i class="fa fa-spin fa-spinner"></i> ' + msg + '</p>');
                user_checkregister(user_id, $("#user_finger_" + user_id).html());
                if (ct >= limit || regStats == 1)
                {
                    clearInterval(timer4reg);
                    console.log("'" + user_name + "' Biometric Data registration checking end");
                    msg = "'" + user_name + "' Biometric Data registration checking end";
                    dialog.find('.bootbox-body').html('<p><i class="fa fa-spin fa-spinner"></i> ' + msg + '</p>');
                    if (ct >= limit && regStats == 0)
                    {
                        msg = "'" + user_name + "' Biometric Data Registration fail!";
                        dialog.find('.bootbox-body').html(msg);
                        /*$('body').ajaxMask({stop: true});*/
                    }
                    if (regStats == 1)
                    {
                        $("#user_finger_" + user_id).html(regCt);
                        msg = "'" + user_name + "' Biometric Data Registration success!";
                        dialog.find('.bootbox-body').html(msg);
                        /*alert(); $('body').ajaxMask({stop: true});*/
                        loadBio('<?php echo $flxcde_url; ?>user.php?action=index');
                    }
                }
                ct++;
            }, 1000);
        });
    }

    function user_checkregister(user_id, current) {
        $.ajax({
            url: "<?php echo $flxcde_url; ?>user.php?action=checkreg&user_id=" + user_id + "&current=" + current,
            type: "GET",
            success: function (data)
            {
                try
                {
                    var res = jQuery.parseJSON(data);
                    if (res.result)
                    {
                        regStats = 1;
                        $.each(res, function (key, value) {
                            if (key == 'current')
                            {
                                regCt = value;
                            }
                        });
                    }
                } catch (err)
                {
                    alert(err.message);
                }
            }
        });
    }
</script>