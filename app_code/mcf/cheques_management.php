<?php
if (array_key_exists('lgn_num', get_defined_vars())) {

    //if ($vwtyp == "0") {
    $cntent .= "
					<li onclick=\"openATab('#allmodules', 'grp=17&typ=1&pg=3');\">
                                                <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
						<span style=\"text-decoration:none;\">Core Banking Menu</span>
                                        </li>";
    $cntent .= "
					<li onclick=\"openATab('#allmodules', 'grp=17&typ=1&q=SUBMENUS&pg=3&subPgNo=3.4.1');\">
                                                <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
						<span style=\"text-decoration:none;\">Cheques Management</span>
                                        </li>";

    $usrID = $_SESSION['USRID'];
    $prsnid = $_SESSION['PRSN_ID'];
    $orgID = $_SESSION['ORG_ID'];

    $lnkdFirmID = getGnrlRecNm("prs.prsn_names_nos", "person_id", "lnkd_firm_org_id", $prsnid);
    $pkID = $prsnid;

    if ($pkID > 0) {

        if ($subPgNo == "3.4.1") {//CHEQUE BOOK REGISTER
            echo $cntent .= "
					<li onclick=\"openATab('#allmodules', 'grp=17&typ=1&pg=3&subPgNo=3.4.1');\">
                                                <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
						<span style=\"text-decoration:none;\">Cheque Book Register</span>
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
                $canAddPrsn = test_prmssns($dfltPrvldgs[125], $mdlNm);
                $canEditChqBook = test_prmssns($dfltPrvldgs[126], $mdlNm);
                $canDeleteChqBook = test_prmssns($dfltPrvldgs[127], $mdlNm);
                $canViewChqBook = test_prmssns($dfltPrvldgs[58], $mdlNm);
                /* echo $cntent . "<li>
                  <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                  <span style=\"text-decoration:none;\">Data Administration</span>
                  </li>
                  </ul>
                  </div>"; */
                $total = get_ChqBookRegisterTtl($srchFor, $srchIn, $orgID, $searchAll);
                if ($pageNo > ceil($total / $lmtSze)) {
                    $pageNo = 1;
                } else if ($pageNo < 1) {
                    $pageNo = ceil($total / $lmtSze);
                }

                $curIdx = $pageNo - 1;
                $result = get_ChqBookRegister($srchFor, $srchIn, $curIdx, $lmtSze, $orgID, $searchAll, $sortBy);
                $cntr = 0;
                $colClassType1 = "col-lg-2";
                $colClassType2 = "col-lg-3";
                ?> 
                <form id='dataAdminForm' action='' method='post' accept-charset='UTF-8'>
                    <!--ROW ID-->
                    <input class="form-control" id="tblRowID" type = "hidden" placeholder="ROW ID"/>                     
                    <fieldset class="basic_person_fs1"><legend class="basic_person_lg" style="color: #003245">CHEQUE BOOK REGISTER</legend>
                        <div class="row" style="margin-bottom:10px;">
                            <?php
                            if ($canAddPrsn === true) {
                                ?>   
                                <div class="<?php echo $colClassType1; ?>" style="padding:0px 1px 0px 15px !important;">                    
                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="getChqBookRegisterForm('myFormsModal', 'myFormsModalBody', 'myFormsModalTitle', 'Add Cheque Book', 19, '<?php echo $subPgNo; ?>', 0, 'ADD', -1);">
                                        <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                        New Cheque Book
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
                                    <input class="form-control" id="dataAdminSrchFor" type = "text" placeholder="Search For" value="<?php echo trim(str_replace("%", " ", $srchFor)); ?>" onkeyup="enterKeyFuncCust(event, '', '#allmodules', 'grp=17&typ=1&pg=3&subPgNo=3.4.1')">
                                    <input id="dataAdminPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getCustData('clear', '#allmodules', 'grp=17&typ=1&pg=3&subPgNo=3.4.1')">
                                        <span class="glyphicon glyphicon-remove"></span>
                                    </label>
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getCustData('', '#allmodules', 'grp=17&typ=1&pg=3&subPgNo=3.4.1')">
                                        <span class="glyphicon glyphicon-search"></span>
                                    </label> 
                                </div>
                            </div>
                            <div class="<?php echo $colClassType2; ?>">
                                <div class="input-group">
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="dataAdminSrchIn">
                                        <?php
                                        $valslctdArry = array("", "", "", "", "", "");
                                        $srchInsArrys = array("Cheque Book No.", "Account Number", "Account Title", "Cheque Type",
                                             "Cheque Book Status", "Approval Status");
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
                                        $srchInsArrys = array("Date Added DESC", "Account Number", "Account Title ASC", "Cheque Book No. ASC", "Cheque Type ASC");
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
                                <nav aria-label="Page navigation">
                                    <ul class="pagination" style="margin: 0px !important;">
                                        <li>
                                            <a href="javascript:getCustData('previous', '#allmodules', 'grp=17&typ=1&pg=3&subPgNo=3.4.1');" aria-label="Previous">
                                                <span aria-hidden="true">&laquo;</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="javascript:getCustData('next', '#allmodules', 'grp=17&typ=1&pg=3&subPgNo=3.4.1');" aria-label="Next">
                                                <span aria-hidden="true">&raquo;</span>
                                            </a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div>                        
                        <div class="row"> 
                            <div  class="col-md-12">
                                <table class="table table-striped table-bordered table-responsive" id="indCustTable" cellspacing="0" width="100%" style="width:100%;">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <?php if ($canEditChqBook === true) { ?>
                                                <th>...</th>
                                            <?php } ?>
                                            <?php if ($canDeleteChqBook === true) { ?>  
                                            <th>...</th>
                                            <?php } ?>
                                            <th>Cheque Book No.</th>                                            
                                            <th>Account Number</th>
                                            <th>Account Title</th>
                                            <th>Cheque Type</th>
                                            <th>Serial Nos.</th>
                                            <th>Status</th>
                                            <!--<th>Approval Status</th>-->
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
                                                <td><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                                <?php if ($canEditChqBook === true) { ?>                                    
                                                    <td>
                                                        <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="Edit Cheque Book" 
                                                                onclick="getChqBookRegisterForm('myFormsModal', 'myFormsModalBody', 'myFormsModalTitle', 'Edit Cheque Book', 19, '<?php echo $subPgNo; ?>', 0, 'EDIT', <?php echo $row[0]; ?>, 'indCustTableRow<?php echo $cntr; ?>');" style="padding:2px !important;" style="padding:2px !important;">
                                                            <!--<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>-->
                                                            <img src="cmn_images/edit32.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                        </button>
                                                    </td>
                                                <?php } ?>
                                                <?php if ($canDeleteChqBook === true) { ?>  
                                                <td>
                                                    <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="Delete Product" onclick="deleteChqBook(<?php echo $row[0]; ?>)" style="padding:2px !important;" style="padding:2px !important;">
                                                        <!--<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>-->
                                                        <img src="cmn_images/delete.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                    </button>
                                                </td>
                                                <?php } ?>
                                                <td><?php echo $row[1]; ?></td>
                                                <td><?php echo $row[2]; ?></td>
                                                <td><?php echo $row[3]; ?></td>
                                                <td><?php echo $row[4]; ?></td>
                                                <td><?php echo $row[5]; ?></td>
                                                <td><?php echo $row[6]; ?></td>
                                                <!--<td><?php echo $row[7]; ?></td>-->
                                                <?php if ($canViewChqBook === true) { ?>  
                                                <td>
                                                    <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Cheque Book" onclick="getChqBookRegisterForm('myFormsModal', 'myFormsModalBody', 'myFormsModalTitle', 'Edit Cheque Book', 19, '<?php echo $subPgNo; ?>', 0, 'VIEW', <?php echo $row[0]; ?>, 'indCustTableRow<?php echo $cntr; ?>');" style="padding:2px !important;" style="padding:2px !important;">
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
        else if ($subPgNo == "3.4.2") {//CHEQUE REGISTER
            echo $cntent .= "
					<li onclick=\"openATab('#allmodules', 'grp=17&typ=1&pg=3&subPgNo=3.4.2');\">
                                                <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
						<span style=\"text-decoration:none;\">Cheque Register</span>
                                        </li></div>";
            $error = "";
            $searchAll = true;

            $srchFor = isset($_POST['searchfor']) ? cleanInputData($_POST['searchfor']) : '';
            $srchIn = isset($_POST['searchin']) ? cleanInputData($_POST['searchin']) : 'Both';
            $pageNo = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
            $lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 10;
            $sortBy = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : "Date Changed";

            if (strpos($srchFor, "%") === FALSE) {
                $srchFor = "%" . str_replace(" ", "%", $srchFor) . "%";
                $srchFor = str_replace("%%", "%", $srchFor);
            }

            if ($vwtyp == 0) {
                $canEditChq = test_prmssns($dfltPrvldgs[130], $mdlNm);
                $canViewChq = test_prmssns($dfltPrvldgs[59], $mdlNm);
                /* echo $cntent . "<li>
                  <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                  <span style=\"text-decoration:none;\">Data Administration</span>
                  </li>
                  </ul>
                  </div>"; */
                $total = get_ChqRegisterTtl($srchFor, $srchIn, $orgID, $searchAll);
                if ($pageNo > ceil($total / $lmtSze)) {
                    $pageNo = 1;
                } else if ($pageNo < 1) {
                    $pageNo = ceil($total / $lmtSze);
                }

                $curIdx = $pageNo - 1;
                $result = get_ChqRegister($srchFor, $srchIn, $curIdx, $lmtSze, $orgID, $searchAll, $sortBy);
                $cntr = 0;
                $colClassType1 = "col-lg-2";
                //$colClassType2 = "col-lg-8";
                ?> 
                <form id='dataAdminForm' action='' method='post' accept-charset='UTF-8'>
                    <input class="form-control" id="tblRowID" type = "hidden" placeholder="ROW ID"/>                     
                    <fieldset class="basic_person_fs1"><legend class="basic_person_lg" style="color: #003245">CHEQUE REGISTER</legend>
                        <div class="row" style="margin-bottom:10px;">
                            <?php
                            $colClassType1 = "col-lg-2";
                            $colClassType2 = "col-lg-3";
                            $colClassType0 = "col-lg-4";
                            ?>
                            <div style="display:none !important;" class="<?php echo $colClassType0; ?>" style="padding:0px 15px 0px 15px !important;">
                                <div class="input-group">
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="trnsType" style="min-width:70px !important;">                            
                                        <?php
                                        $valslctdArry = array("");
                                        $dsplySzeArry = array("DEPOSIT");
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
                            </div>                         
                            <div class="<?php echo $colClassType0; ?>" style="padding:0px 15px 0px 15px !important;">
                                <div class="input-group">
                                    <input class="form-control" id="dataAdminSrchFor" type = "text" placeholder="Search For" value="<?php echo trim(str_replace("%", " ", $srchFor)); ?>" onkeyup="enterKeyFuncCust(event, '', '#allmodules', 'grp=17&typ=1&pg=3&subPgNo=3.4.2')">
                                    <input id="dataAdminPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getCustData('clear', '#allmodules', 'grp=17&typ=1&pg=3&subPgNo=3.4.2')">
                                        <span class="glyphicon glyphicon-remove"></span>
                                    </label>
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getCustData('', '#allmodules', 'grp=17&typ=1&pg=3&subPgNo=3.4.2')">
                                        <span class="glyphicon glyphicon-search"></span>
                                    </label> 
                                </div>
                            </div>
                            <div class="<?php echo $colClassType2; ?>">
                                <div class="input-group">
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="dataAdminSrchIn">
                                        <?php
                                        $valslctdArry = array("", "", "", "", "", "");
                                        $srchInsArrys = array("Cheque No.", "Cheque Book No.", "Account Title", "Account Number", "Cheque Status", "Approval Status");
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
                            <div class="<?php echo $colClassType2; ?>">
                                <div class="input-group">                        
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-sort-by-attributes"></span></span>
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="dataAdminSortBy">
                                        <?php
                                        $valslctdArry = array("", "", "", "", "", "","");
                                        $srchInsArrys = array("Date Changed","Cheque No. ASC", "Cheque No. DESC", "Cheque Book No.", "Account Number", "Cheque Status ASC", "Approval Status ASC");
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
                                <nav aria-label="Page navigation">
                                    <ul class="pagination" style="margin: 0px !important;">
                                        <li>
                                            <a href="javascript:getCustData('previous', '#allmodules', 'grp=17&typ=1&pg=3&subPgNo=3.4.2');" aria-label="Previous">
                                                <span aria-hidden="true">&laquo;</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="javascript:getCustData('next', '#allmodules', 'grp=17&typ=1&pg=3&subPgNo=3.4.2');" aria-label="Next">
                                                <span aria-hidden="true">&raquo;</span>
                                            </a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div>                   
                        <div class="row"> 
                            <div  class="col-md-12">
                                <table class="table table-striped table-bordered table-responsive" id="indCustTable" cellspacing="0" width="100%" style="width:100%;">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <?php if ($canEditChq === true) { ?>
                                                <th>...</th>
                                            <?php } ?>		
                                            <th>Cheque No.</th>
                                            <th>Cheque Book No.</th>
                                            <th>Cheque Type</th>
                                            <th>Account Number</th>
                                            <th>Cheque Status</th>
                                            <!--<th>Approval Status</th>-->
                                            <?php if ($canViewChq === true) { ?>
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
                                                <td><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                                <?php if ($canEditChq === true) { ?>                                    
                                                    <td>
                                                        <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="Edit Cheque Book" 
                                                                onclick="getChqRegisterForm('myFormsModal', 'myFormsModalBody', 'myFormsModalTitle', 'Edit Cheque', 19, '<?php echo $subPgNo; ?>', 0, 'EDIT', <?php echo $row[0]; ?>, 'indCustTableRow<?php echo $cntr; ?>');" style="padding:2px !important;" style="padding:2px !important;">
                                                            <!--<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>-->
                                                            <img src="cmn_images/edit32.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                        </button>
                                                    </td>
                                                <?php } ?>
                                                <td><?php echo $row[1]; ?></td>
                                                <td><?php echo $row[2]; ?></td>
                                                <td><?php echo $row[3]; ?></td>
                                                <td><?php echo $row[4]; ?></td>
                                                <td><?php echo $row[5]; ?></td>
                                                <!--<td><?php echo $row[6]; ?></td>-->
                                                <?php if ($canViewChq === true) { ?>
                                                <td>
                                                    <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Cheque Book" onclick="getChqRegisterForm('myFormsModal', 'myFormsModalBody', 'myFormsModalTitle', 'Edit Cheque', 19, '<?php echo $subPgNo; ?>', 0, 'VIEW', <?php echo $row[0]; ?>, 'indCustTableRow<?php echo $cntr; ?>');" style="padding:2px !important;" style="padding:2px !important;">
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
        ?>
        <!--  style="min-width: 1000px;left:-35%;"-->    
        <?php
        //}
    }

    //echo $cntent;
    //}
}
?>
