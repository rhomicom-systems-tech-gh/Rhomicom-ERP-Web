<?php
$dateStr = getDB_Date_time();
$pkID = $PKeyID;

$prsnid = $_SESSION['PRSN_ID'];
$orgID = $_SESSION['ORG_ID'];

//var_dump($_POST);
$canAddInvstgtnList = test_prmssns($dfltPrvldgs[23], $mdlNm);
$canEdtInvstgtnList = test_prmssns($dfltPrvldgs[24], $mdlNm);
$canDelInvstgtnList = test_prmssns($dfltPrvldgs[25], $mdlNm);

$prsnJob = getPrsnJobNm($prsnid);

if (array_key_exists('lgn_num', get_defined_vars())) {
    if ($lgn_num > 0 && $canview === true) {
        //echo $_POST['dataToSend'];
        //var_dump($_POST);

        if ($qstr == "DELETE") {
            if ($actyp == 2) {//Item Services Required
                $recInUse = isItmservs_rqrd_id_InActiveUse($PKeyID);
                if ($recInUse) {
                    echo "SORRY! Record is in use";
                    exit();
                } else {
                    $rowCnt = deleteItmServsRqrd($PKeyID);
                    if ($rowCnt > 0) {
                        echo "Record Deleted Successfully";
                    } else {
                        echo "Failed to Delete Record";
                    }
                    exit();
                }
            } else if ($actyp == 1) {
                //var_dump($_POST);
                $bkTypCodeInUse = isInvstgtnListInActiveUse($PKeyID);
                //check loan status -> Incomplete, Rejected and Withdrawn CAN BE DELETED
                if ($bkTypCodeInUse) {
                    echo "SORRY! Lab Investigation is in use";
                    exit();
                } else {
                    $rowCnt = deleteCreditInvstgtnLists($PKeyID);
                    if ($rowCnt > 0) {
                        echo "Lab Investigation Record Deleted Successfully";
                    } else {
                        echo "Failed to Delete Lab Investigation Record";
                    }
                    exit();
                }
            }
        } else if ($qstr == "UPDATE") {
            if ($actyp == 2) {//item/Services Required
                $slctdItmServsRqrd = isset($_POST['slctdItmServsRqrd']) ? cleanInputData($_POST['slctdItmServsRqrd']) : '';
                global $usrID;
                $dateStr = getDB_Date_time();
                $created_by = $usrID;
                $last_update_by = $usrID;
                $creation_date = $dateStr;
                $last_update_date = $dateStr;
                $recCntInst = 0;
                $recCntUpdt = 0;
                if (trim($slctdItmServsRqrd, "|~") != "") {
                    $variousRows = explode("|", trim($slctdItmServsRqrd, "|"));
                    for ($z = 0; $z < count($variousRows); $z++) {
                        $crntRow = explode("~", $variousRows[$z]);
                        if (count($crntRow) == 7) {
                            $itmservs_rqrd_id = (int) (cleanInputData1($crntRow[0]));
                            $invstgtn_list_id = (int) (cleanInputData1($crntRow[1]));
                            $quantity = (int) (cleanInputData1($crntRow[2]));
                            $cmnts = cleanInputData1($crntRow[3]);
                            $itm_id = (int) (cleanInputData1($crntRow[4]));
                            $servs_type_id = (int) (cleanInputData1($crntRow[5]));
                            $itm_type =  cleanInputData1($crntRow[6]);
                            if ($itmservs_rqrd_id > 0) {
                                $recCntUpdt = $recCntUpdt + updateItmServsRqrd($itmservs_rqrd_id, $invstgtn_list_id, $quantity, $cmnts, $created_by, $creation_date, 
                                        $last_update_by, $last_update_date, $itm_id, $servs_type_id, $itm_type);
                            } else {
                                $itmservs_rqrd_id = getItmservs_rqrd_id();
                                $recCntInst = $recCntInst + insertItmServsRqrd($itmservs_rqrd_id, $invstgtn_list_id, $quantity, $cmnts, $created_by, $creation_date, 
                                        $last_update_by, $last_update_date, $itm_id, $servs_type_id, $itm_type);
                            }
                        }
                    }
                    echo "<span style='color:green;font-weight:bold !important;'><i>$recCntInst record(s) inserted</br>$recCntUpdt record(s) updated</i></span>";
                    exit();
                } else {
                    echo '<div><img src="cmn_images/error.gif" style="float:left;margin-right:5px;width:30px;height:30px;"/>'
                    . 'Please provide one record before saving!<br/></div>';
                    exit();
                }
            } else if ($actyp == 1) {
                $slctdInvstgtnLists = isset($_POST['slctdInvstgtnLists']) ? cleanInputData($_POST['slctdInvstgtnLists']) : '';

                $dateStr = getDB_Date_time();
                $recCntInst = 0;
                $recCntUpdt = 0;

                if (trim($slctdInvstgtnLists, "|~") != "") {

                    $variousRows = explode("|", trim($slctdInvstgtnLists, "|"));
                    for ($z = 0; $z < count($variousRows); $z++) {
                        $crntRow = explode("~", $variousRows[$z]);
                        if (count($crntRow) == 6) {

                            $diseaseId = (int) (cleanInputData1($crntRow[0]));
                            $diagnsListNm = cleanInputData1($crntRow[1]);
                            $diagnsListDesc = cleanInputData1($crntRow[2]);
                            $diagnsListIsEnabled = cleanInputData1($crntRow[3]);
                            $srvsItmId = (int) (cleanInputData1($crntRow[4]));
                            $invstgtn_type = cleanInputData1($crntRow[5]);

                            if ($diseaseId > 0) {
                                $recCntUpdt = $recCntUpdt + updateCreditInvstgtnLists($diseaseId, $diagnsListNm, $diagnsListDesc, $diagnsListIsEnabled, $dateStr, $srvsItmId, $invstgtn_type);
                            } else {
                                $diseaseId = getInvstgtnListID();
                                $recCntInst = $recCntInst + createCreditInvstgtnLists($diseaseId, $diagnsListNm, $diagnsListDesc, $diagnsListIsEnabled, $dateStr, $srvsItmId, $invstgtn_type);
                            }
                        }
                    }

                    echo "<span style='color:green;font-weight:bold !important;'><i>$recCntInst record(s) inserted</br>$recCntUpdt record(s) updated</i></span>";
                    exit();
                } else {
                    echo '<div><img src="cmn_images/error.gif" style="float:left;margin-right:5px;width:30px;height:30px;"/>'
                    . 'Please provide one Lab Investigation Record before saving!<br/></div>';
                    exit();
                }
            }
        } else {
            if ($vwtyp == 0) {
                $error = "";
                $searchAll = true;
                $isEnabledOnly = false;
                if (isset($_POST['isEnabled'])) {
                    $isEnabledOnly = cleanInputData($_POST['isEnabled']);
                }


                $srchFor = isset($_POST['searchfor']) ? cleanInputData($_POST['searchfor']) : '';
                $srchIn = isset($_POST['searchin']) ? cleanInputData($_POST['searchin']) : 'Both';
                $pageNo = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
                $lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 15;
                $sortBy = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : "Date Added DESC";
                if (strpos($srchFor, "%") === FALSE) {
                    $srchFor = "%" . str_replace(" ", "%", $srchFor) . "%";
                    $srchFor = str_replace("%%", "%", $srchFor);
                }

                if ($vwtyp == 0) {//3
                    echo $cntent . "<li onclick=\"openATab('#allmodules', 'grp=$group&typ=$type&pg=$pgNo&vtyp=0&mdl=Clinic/Hospital');\">
                                    <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                    <span style=\"text-decoration:none;\">Investigation List</span>
				</li>
                               </ul>
                              </div>";

                    $total = getCreditInvstgtnListsTblTtl($isEnabledOnly, $srchFor, $srchIn, $searchAll);
                    if ($pageNo > ceil($total / $lmtSze)) {
                        $pageNo = 1;
                    } else if ($pageNo < 1) {
                        $pageNo = ceil($total / $lmtSze);
                    }

                    $curIdx = $pageNo - 1;
                    $result = getCreditInvstgtnListsTbl($isEnabledOnly, $srchFor, $srchIn, $curIdx, $lmtSze, $searchAll, $sortBy);
                    $cntr = 0;
                    $colClassType1 = "col-lg-2";
                    $colClassType2 = "col-lg-3";
                    $colClassType3 = "col-lg-1";
                    ?>
                    <form id='allInvstgtnListsForm' action='' method='post' accept-charset='UTF-8'>
                        <!--<fieldset class="basic_person_fs5">-->
                        <legend class="basic_person_lg1" style="color: #003245">LABORATORY AND RADIOLOGY INVESTIGATIONS</legend>                
                        <input class="form-control" id="tblRowID" type = "hidden" placeholder="ROW ID"/>                     
                        <div class="row rhoRowMargin" style="margin-bottom:10px;">
                            <?php
                            if ($canAddInvstgtnList === true) {
                                $nwRowHtml = urlencode("<tr id=\"allInvstgtnListsRow__WWW123WWW\">"
                                        . "<td class=\"lovtd\"><span class=\"normaltd\">New</span></td>"
                                        . "<td class=\"lovtd\">
                                                            <input type=\"text\" style=\"width:100% !important;\" class=\"form-control rqrdFld\" aria-label=\"...\" id=\"allInvstgtnListsRow_WWW123WWW_InvstgtnListNm\" name=\"allInvstgtnListsRow_WWW123WWW_InvstgtnListNm\" value=\"\">   
                                                            <input type=\"hidden\"  class=\"form-control\" aria-label=\"...\" id=\"allInvstgtnListsRow_WWW123WWW_InvstgtnListID\" value=\"-1\" style=\"width:100% !important;\">                                                                                                                          
                                                        </td>                                             
                                                        <td class=\"lovtd\">
                                                                <input type=\"text\" style=\"width:100% !important;\" class=\"form-control rqrdFld\" aria-label=\"...\" id=\"allInvstgtnListsRow_WWW123WWW_InvstgtnListDesc\" name=\"allInvstgtnListsRow_WWW123WWW_InvstgtnListDesc\" value=\"\">                                                               
                                                        </td>
                                                        <td class=\"lovtd\">       
                                                            <select class=\"form-control\" aria-label=\"...\" id=\"allInvstgtnListsRow_WWW123WWW_InvstgtnListType\" name=\"allInvstgtnListsRow_WWW123WWW_InvstgtnListType\">
                                                                <option value=\"Lab\" selected>Laboratory</option>
                                                                <option value=\"Radiology\" >Radiology</option>														
                                                            </select>
                                                        </td>
                                                        <td class=\"lovtd\">       
                                                            <select class=\"form-control\" aria-label=\"...\" id=\"allInvstgtnListsRow_WWW123WWW_InvstgtnListIsEnabled\" name=\"allInvstgtnListsRow_WWW123WWW_InvstgtnListIsEnabled\">
                                                                <option value=\"1\" selected>Yes</option>
                                                                <option value=\"0\" >No</option>														
                                                            </select>
                                                        </td>
                                                        <td class=\"lovtd\">
                                                            <div class=\"form-group form-group-sm\" style=\"width:100% !important;\">
                                                                <div class=\"input-group\"  style=\"width:100%;\">
                                                                    <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"allInvstgtnListsRow_WWW123WWW_SrvcOffrdItm\" value=\"\" readonly=\"true\">
                                                                    <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"allInvstgtnListsRow_WWW123WWW_SrvcOffrdItmID\" value=\"-1\">
                                                                    <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Inventory Services', 'gnrlOrgID', '', '', 'radio', true, '', 'allInvstgtnListsRow_WWW123WWW_SrvcOffrdItmID', 'allInvstgtnListsRow_WWW123WWW_SrvcOffrdItm', 'clear', 0, '');\">
                                                                        <span class=\"glyphicon glyphicon-th-list\"></span>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style=\"width: 10px !important; max-width: 10px !important;\"></td>
                                                        <td class=\"lovtd\" style=\"width: 10px !important; max-width: 10px !important;\">
                                                            <button type=\"button\" class=\"btn btn-default\" style=\"margin: 0px !important;padding:0px 3px 2px 4px !important;\" onclick=\"delInvstgtnList('allInvstgtnListsRow__WWW123WWW');\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Delete Investigation\">
                                                                    <img src=\"cmn_images/no.png\" style=\"height:15px; width:auto; position: relative; vertical-align: middle;\">
                                                            </button>
                                                        </td>
                                    </tr>");
                                ?>   
                                <div class="<?php echo $colClassType1; ?>" style="padding:0px 1px 0px 15px !important;">  
                                    <?php if ($canAddInvstgtnList === true) { ?>
                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="insertNewRowBe4('allInvstgtnListsTable', 0, '<?php echo $nwRowHtml; ?>');">
                                        <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                        New Investigation
                                    </button>
                                    <?php } ?>
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
                                    <input class="form-control" id="allInvstgtnListsSrchFor" type = "text" placeholder="Search For" value="<?php echo trim(str_replace("%", " ", $srchFor)); ?>" onkeyup="enterKeyFuncAllInvstgtnLists(event, '', '#allmodules', 'grp=14&typ=1&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&mdl=Clinic/Hospital')">
                                    <input id="allInvstgtnListsPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getAllInvstgtnLists('clear', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&mdl=Clinic/Hospital');">
                                        <span class="glyphicon glyphicon-remove"></span>
                                    </label>
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getAllInvstgtnLists('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&mdl=Clinic/Hospital');">
                                        <span class="glyphicon glyphicon-search"></span>
                                    </label> 
                                </div>
                            </div>
                            <div class="<?php echo $colClassType2; ?>">
                                <div class="input-group">
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="allInvstgtnListsSrchIn">
                                        <?php
                                        $valslctdArry = array("", "", "");
                                        $srchInsArrys = array("Description", "Investigation Name", "Investigation Type");
                                        for ($z = 0; $z < count($srchInsArrys); $z++) {
                                            if ($srchIn == $srchInsArrys[$z]) {
                                                $valslctdArry[$z] = "selected";
                                            }
                                            ?>
                                            <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                        <?php } ?>
                                    </select>
                                    <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="allInvstgtnListsDsplySze" style="min-width:70px !important;">                            
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
                                        <input type="checkbox" class="form-check-input" onclick="getAllInvstgtnLists('clear', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&mdl=Clinic/Hospital');" id="allInvstgtnListsIsEnabled" name="allInvstgtnListsIsEnabled" <?php echo $nonAprvdChekd; ?>>
                                        Enabled?
                                    </label>
                                </div>                             
                            </div>
                            <div class="<?php echo $colClassType3; ?>" style="width:10% !important;">
                                <nav aria-label="Page navigation">
                                    <ul class="pagination" style="margin: 0px !important;">
                                        <li>
                                            <a href="javascript:getAllInvstgtnLists('previous', '#allmodules', 'grp=14&typ=1&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&mdl=Clinic/Hospital');" aria-label="Previous">
                                                <span aria-hidden="true">&laquo;</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="javascript:getAllInvstgtnLists('next', '#allmodules', 'grp=14&typ=1&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&mdl=Clinic/Hospital');" aria-label="Next">
                                                <span aria-hidden="true">&raquo;</span>
                                            </a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>  
                            <div class="<?php echo $colClassType3; ?>" style="padding:0px 1px 0px 3px !important;"> 
                                <div style="float:right !important;">
                                    <?php if ($canAddInvstgtnList === true) { ?>
                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="saveInvstgtnList();" data-toggle="tooltip" data-placement="bottom" title="Save InvstgtnList">
                                        <img src="cmn_images/FloppyDisk.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                        Save Investigation
                                    </button>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>               
                        <div class="row" style="padding:0px 15px 0px 15px !important">                        
                            <div  class="col-md-12" style="padding:0px 1px 0px 1px !important">
                                <fieldset class="basic_person_fs" style="padding-top:5px !important;">
                                    <div class="" id="allInvstgtnListsDetailInfo">
                                        <div class="row" id="allInvstgtnListsDetailInfo" style="padding:0px 15px 0px 15px !important">
                                            <div class="row" style="padding:0px 15px 0px 15px !important">                  
                                                <div class="col-md-12" style="padding:0px 3px 0px 3px !important">
                                                    <table class="table table-striped table-bordered table-responsive" id="allInvstgtnListsTable" cellspacing="0" width="100%" style="width:100%;min-width: 300px !important;">
                                                        <thead>
                                                            <tr>
                                                                <th>No.</th>
                                                                <th>Investigation Name</th>
                                                                <th>Description</th>
                                                                <th style="width: 110px !important; max-width: 110px !important;">Type</th>
                                                                <th style="width: 90px !important; max-width: 90px !important;">Is Enabled</th>
                                                                <th>Inventory Item</th>
                                                                <?php if(1 > 2){ ?>
                                                                <th style="width: 10px !important; max-width: 10px !important;">&nbsp;</th>
                                                                <?php } ?>
                                                                <th style="width: 10px !important; max-width: 10px !important;">&nbsp;</th>
                                                            </tr>
                                                        </thead>
                                                        <?php
                                                        if ($total > 0) {
                                                            ?>
                                                            <tbody>
                                                                <?php
                                                                $cntr = 0;
                                                                while ($row2 = loc_db_fetch_array($result)) {
                                                                    $cntr += 1;
                                                                    $ttlOptnsScore = 0;
                                                                    $svsItmNm = getGnrlRecNm("inv.inv_itm_list", "item_id", "item_desc||' ('||item_code||')'", $row2[4]);
                                                                    ?>
                                                                    <tr id="allInvstgtnListsRow_<?php echo $cntr; ?>">                                    
                                                                        <td class="lovtd"><span><?php echo ($curIdx * $lmtSze) + ($cntr); ?></span></td>
                                                                        <td class="lovtd">
                                                                            <input type="hidden" class="form-control" aria-label="..." id="allInvstgtnListsRow<?php echo $cntr; ?>_InvstgtnListID" value="<?php echo $row2[0]; ?>" style="width:100% !important;"> 
                                                                            <input type="text" style="width:100% !important;" class="form-control rqrdFld" aria-label="..." id="allInvstgtnListsRow<?php echo $cntr; ?>_InvstgtnListNm" name="allInvstgtnListsRow<?php echo $cntr; ?>_InvstgtnListNm" value="<?php echo $row2[1]; ?>">                                                               
                                                                        </td>                                             
                                                                        <td class="lovtd"> 
                                                                            <input type="text" style="width:100% !important;" class="form-control rqrdFld" aria-label="..." id="allInvstgtnListsRow<?php echo $cntr; ?>_InvstgtnListDesc" name="allInvstgtnListsRow<?php echo $cntr; ?>_InvstgtnListDesc" value="<?php echo $row2[2]; ?>">
                                                                        </td>
                                                                        <td class="lovtd">  
                                                                            <select class="form-control" aria-label="..." id="allInvstgtnListsRow<?php echo $cntr; ?>_InvstgtnListType" name="allInvstgtnListsRow<?php echo $cntr; ?>_InvstgtnListType">
                                                                                <?php
                                                                                $sltdLab = "";
                                                                                $sltdRad = "";
                                                                                if ($row2[5] == "Lab") {
                                                                                    $sltdLab = "selected";
                                                                                } else if ($row2[5] == "Radiology") {
                                                                                    $sltdRad = "selected";
                                                                                }
                                                                                ?>
                                                                                <option value="Lab" <?php echo $sltdLab; ?>>Laboratory</option>
                                                                                <option value="Radiology" <?php echo $sltdRad; ?>>Radiology</option>    
                                                                            </select>		
                                                                        </td>
                                                                        <td class="lovtd">  
                                                                            <select class="form-control" aria-label="..." id="allInvstgtnListsRow<?php echo $cntr; ?>_InvstgtnListIsEnabled" name="allInvstgtnListsRow<?php echo $cntr; ?>_InvstgtnListIsEnabled">
                                                                                <?php
                                                                                $sltdYes = "";
                                                                                $sltdNo = "";
                                                                                if ($row2[3] == "1") {
                                                                                    $sltdYes = "selected";
                                                                                } else if ($row2[3] == "0") {
                                                                                    $sltdNo = "selected";
                                                                                }
                                                                                ?>
                                                                                <option value="1" <?php echo $sltdYes; ?>>Yes</option>
                                                                                <option value="0" <?php echo $sltdNo; ?>>No</option>    
                                                                            </select>		
                                                                        </td>
                                                                        <td class="lovtd"> 
                                                                            <div class="input-group"  style="width:100%;">
                                                                                <input type="text" style="width:100% !important;" class="form-control" aria-label="..." id="allInvstgtnListsRow<?php echo $cntr; ?>_SrvcOffrdItm" name="allInvstgtnListsRow<?php echo $cntr; ?>_SrvcOffrdItm" value="<?php echo $svsItmNm; ?>">
                                                                                <input type="hidden" class="form-control" aria-label="..." id="allInvstgtnListsRow<?php echo $cntr; ?>_SrvcOffrdItmID" value="<?php echo $row2[4]; ?>">
                                                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Inventory Services', 'gnrlOrgID', '', '', 'radio', true, '', 'allInvstgtnListsRow<?php echo $cntr; ?>_SrvcOffrdItmID', 'allInvstgtnListsRow<?php echo $cntr; ?>_SrvcOffrdItm', 'clear', 0, '');">
                                                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                                                </label>
                                                                            </div>
                                                                        </td>
                                                                        <?php if(1 > 2){ ?>
                                                                        <td class="lovtd">
                                                                            <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="getInvstgtnListItemsForm(<?php echo $row2[0]; ?>, 1, 'ShowDialog', '<?php echo $row2[1]; ?>','LAB');" data-toggle="tooltip" data-placement="bottom" title="Add/View Investigation Item/Services">
                                                                                <img src="cmn_images/chcklst2.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                                            </button>
                                                                        </td>
                                                                        <?php } ?>
                                                                        <td class="lovtd">
                                                                            <?php if($canDelInvstgtnList == true){ ?>
                                                                            <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delInvstgtnList('allInvstgtnListsRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Investigation">
                                                                                <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                                            </button
                                                                            <?php } ?>
                                                                        </td>
                                                                    </tr>
                                                                    <?php
                                                                }
                                                                ?>
                                                            </tbody>
                                                            <?php
                                                        } else {
                                                            ?>
                                                            <tfoot>
                                                            <span>No Results Found</span>
                                                            </tfoot>
                                                            <?php
                                                        }
                                                        ?>
                                                    </table>                        
                                                </div>                
                                            </div>              
                                        </div>                                   
                                    </div>
                                </fieldset>
                            </div>
                        </div>
                        <!--</fieldset>-->
                    </form>
                    <?php
                }
            } else if ($vwtyp == 1) {
                ?>
                <div class="row"><!-- ROW 1 -->
                    <div class="col-lg-12">  
                        <div class="row" id="allItmServsRqrdDetailInfo" style="padding:0px 15px 0px 15px !important">
                            <?php
                            $searchAll = true;
                            $isEnabledOnly = false;
                            if (isset($_POST['isEnabled'])) {
                                $isEnabledOnly = cleanInputData($_POST['isEnabled']);
                            }

                            $srchFor = isset($_POST['searchfor']) ? cleanInputData($_POST['searchfor']) : '';
                            $srchIn = isset($_POST['searchin']) ? cleanInputData($_POST['searchin']) : 'Item Desc';
                            $pageNo = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
                            $lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 50;
                            $sortBy = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : "Date Added DESC";
                            if (strpos($srchFor, "%") === FALSE) {
                                $srchFor = "%" . str_replace(" ", "%", $srchFor) . "%";
                                $srchFor = str_replace("%%", "%", $srchFor);
                            }

                            $sbmtdSrcRecID = isset($_POST['sbmtdSrcRecID']) ? cleanInputData($_POST['sbmtdSrcRecID']) : -1;
                            $sbmtdSrcType = isset($_POST['sbmtdSrcType']) ? cleanInputData($_POST['sbmtdSrcType']) : 'LAB';
                            $InvstgnNm = isset($_POST['InvstgnNm ']) ? cleanInputData($_POST['InvstgnNm ']) : '';
                            
                            $invstgtnListId = -1;
                            $servsTypeId = -1;
                            
                            if($sbmtdSrcType == "LAB"){
                                $invstgtnListId = $sbmtdSrcRecID;
                            } else {
                                $servsTypeId = $sbmtdSrcRecID;
                            }
                            
                            $sbmtdAppntmntStatus ='Open';
                            if (1 > 0) {
                                $total = getItmServsRqrdRptTblTtl($sbmtdSrcRecID, $sbmtdSrcType, $srchFor, $srchIn, $searchAll);

                                if ($pageNo > ceil($total / $lmtSze)) {
                                    $pageNo = 1;
                                } else if ($pageNo < 1) {
                                    $pageNo = ceil($total / $lmtSze);
                                }
                                $curIdx = $pageNo - 1;
                                $result2 = getItmServsRqrdRptTbl($sbmtdSrcRecID, $sbmtdSrcType, $srchFor, $srchIn, $curIdx, $lmtSze, $searchAll, $sortBy);
                                ?>
                                <div class="row" style="padding:0px 15px 0px 15px !important">
                                    <?php
                                    if ($canEdtInvstgtnList === true) {
                                        //$colClassType1 = "col-lg-2";
                                        $colClassType1 = "col-lg-6";
                                        $colClassType2 = "col-lg-3";
                                        $colClassType3 = "col-lg-4";
                                        $nwRowHtml = urlencode("<tr id=\"allItmServsRqrdRow__WWW123WWW\">
                                                <td class=\"lovtd\"><span class=\"normaltd\">New</span></td>
                                                <td class=\"lovtd\">
                                                    <div class=\"form-group form-group-sm\" style=\"width:100% !important;margin-bottom:0px !important;\">
                                                    <select data-placeholder=\"Select...\" class=\"form-control chosen-select rqrdFld\" id=\"allItmServsRqrdRow_WWW123WWW_ItmType\">
                                                        <option value=\"Item\" selected>Item</option>
                                                        <option value=\"Service\">Service</option>
                                                     </select>
                                                    </div>                                                       
                                                </td>    
                                                <td class=\"lovtd\">
                                                    <div class=\"input-group\">
							<input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"allItmServsRqrdRow_WWW123WWW_ItmServsRqrdID\" value=\"-1\" style=\"width:100% !important;\"> 
                                                        <input type=\"text\" class=\"form-control rqrdFld\" aria-label=\"...\" id=\"allItmServsRqrdRow_WWW123WWW_ItemDesc\" value=\"\" readonly>
                                                        <input type=\"hidden\" id=\"allItmServsRqrdRow_WWW123WWW_ItemID\" value=\"-1\">
                                                        <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getItemServiceLOV('allItmServsRqrdRow_WWW123WWW_ItemID','allItmServsRqrdRow_WWW123WWW_ItemDesc','allItmServsRqrdRow_WWW123WWW_ItmType');\">
                                                                <span class=\"glyphicon glyphicon-th-list\"></span>
                                                        </label>
                                                    </div>
                                                </td>
                                                <td class=\"lovtd\">
                                                    <input type=\"number\" style=\"width:100% !important;\" min=\"0\" class=\"form-control rqrdFld\" aria-label=\"...\" id=\"allItmServsRqrdRow_WWW123WWW_Qty\" name=\"allItmServsRqrdRow_WWW123WWW_Qty\" value=\"\">
                                                </td>
                                                <td class=\"lovtd\">                                                                                                                            
                                                    <textarea class=\"form-control\" aria-label=\"...\" id=\"allItmServsRqrdRow_WWW123WWW_Cmnts\" name=\"allItmServsRqrdRow_WWW123WWW_Cmnts\"></textarea>                                                                        
                                                </td>
                                                <td class=\"lovtd\">
                                                    <button type=\"button\" class=\"btn btn-default\" style=\"margin: 0px !important;padding:0px 3px 2px 4px !important;\" onclick=\"deleteItmServsRqrd('allItmServsRqrdRow__WWW123WWW');\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Delete Item\">
                                                        <img src=\"cmn_images/no.png\" style=\"height:15px; width:auto; position: relative; vertical-align: middle;\">
                                                    </button>
                                                </td>
                                            </tr>");
                                        ?>
                                        <div class="<?php echo $colClassType1; ?>" style="padding:0px 1px 0px 3px !important;">
                                            <?php if ($sbmtdAppntmntStatus == "Open") { ?>
                                                <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="insertNewRowBe4('allItmServsRqrdTable', 0, '<?php echo $nwRowHtml; ?>');" data-toggle="tooltip" data-placement="bottom" title="Add Item">
                                                    <img src="cmn_images/add1-64.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">&nbsp;New Item
                                                </button>
                                            <?php } ?>
                                        </div>
                                        <?php
                                    } else {
                                        $colClassType1 = "col-lg-3";
                                        $colClassType2 = "col-lg-6";
                                        $colClassType3 = "col-lg-6";
                                        /* $colClassType1 = "col-lg-3";
                                          $colClassType2 = "col-lg-3";
                                          $colClassType3 = "col-lg-3"; */
                                    }
                                    ?>

                                    <input type="hidden" class="form-control" aria-label="..." id="recCnt" name="recCnt" value="">
                                    <input type="hidden" class="form-control" aria-label="..." id="gnrlOrgID" name="gnrlOrgID" value="<?php echo $orgID; ?>">
                                    <input type="hidden" class="form-control" aria-label="..." id="InvstgtnListId" value="<?php echo $invstgtnListId; ?>" style="width:100% !important;">
                                    <input type="hidden" class="form-control" aria-label="..." id="ServsTypeId" value="<?php echo $servsTypeId; ?>" style="width:100% !important;">
                                    <input type="hidden" class="form-control" aria-label="..." id="InvstgnNm" value="<?php echo $InvstgnNm; ?>" style="width:100% !important;">
                                    
                                    <div class="<?php echo $colClassType1; ?>" style="padding:0px 1px 0px 3px !important;"> 
                                        <div style="float:right !important;">
                                            <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="saveItmServsRqrd('<?php echo ""; ?>', '<?php echo $sbmtdAppntmntStatus; ?>');" data-toggle="tooltip" data-placement="bottom" title="Save Service Item(s)">
                                                <img src="cmn_images/FloppyDisk.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">&nbsp;Save
                                            </button>
                                            <button type="button" class="btn btn-default" style="margin-bottom: 5px;height:30px;" onclick="closeSrvItmDialog()"><img src="cmn_images/back_2.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Close&nbsp;</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="row" style="padding:0px 15px 0px 15px !important">                  
                                    <div class="col-md-12" style="padding:0px 3px 0px 3px !important">
                                        <table class="table table-striped table-bordered table-responsive" id="allItmServsRqrdTable" cellspacing="0" width="100%" style="width:100%;min-width: 300px !important;">
                                            <thead>
                                                <tr>
                                                    <th>No.</th>
                                                    <th>Line Type</th>
                                                    <th style="min-width: 270px !important;">Item</th>
                                                    <th style="min-width: 60px !important;width:60px !important;">Qty</th>
                                                    <th>Comments</th>
                                                    <th>...</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $cntr = 0;
                                                while ($row = loc_db_fetch_array($result2)) {
                                                    $itmservs_rqrd_id = $row[0];
                                                    $invstgtn_list_id = $row[1];
                                                    $quantity = $row[2]; 
                                                    $cmnts = $row[3];
                                                    $itm_id = $row[4]; 
                                                    $servs_type_id = $row[5];
                                                    $itm_type = $row[6]; 
                                                    
                                                    $itmCode = getGnrlRecNm("inv.inv_itm_list", "item_id", "item_code", $itm_id);
                                                    $itmDesc = getGnrlRecNm("inv.inv_itm_list", "item_id", "item_desc", $itm_id);
                                                    $cntr += 1;
                                                    ?>
                                                    <tr id="allItmServsRqrdRow_<?php echo $cntr; ?>">                                    
                                                        <td class="lovtd"><span><?php echo ($curIdx * $lmtSze) + ($cntr); ?></span></td>
                                                        <td>
                                                            <div class="form-group form-group-sm" style="width:100% !important;margin-bottom:0px !important;">
                                                                <select data-placeholder="Select..." class="form-control chosen-select rqrdFld" id="allItmServsRqrdRow<?php echo $cntr; ?>_ItmType">
                                                                <?php
                                                                $valslctdArry = array("", "");
                                                                $srchInsArrys = array("Item", "Service");

                                                                for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                                    if ($itm_type == $srchInsArrys[$z]) {
                                                                        $valslctdArry[$z] = "selected";
                                                                    }
                                                                    ?>
                                                                        <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>
                                                        </td>
                                                        <td class="lovtd">
                                                            <div class="input-group">
                                                                <input type="hidden" class="form-control" aria-label="..." id="allItmServsRqrdRow<?php echo $cntr; ?>_ItmServsRqrdID" value="<?php echo $itmservs_rqrd_id; ?>" style="width:100% !important;">
                                                                <input type="text" class="form-control rqrdFld" aria-label="..." id="allItmServsRqrdRow<?php echo $cntr; ?>_ItemDesc" value="<?php echo $itmDesc." (".$itmCode.")"; ?>" readonly>
                                                                <input type="hidden" id="allItmServsRqrdRow<?php echo $cntr; ?>_ItemID" value="<?php echo $itm_id; ?>">
                                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Inventory Items', 'gnrlOrgID', '', '', 'radio', true, '', 'allItmServsRqrdRow<?php echo $cntr; ?>_ItemID', 'allItmServsRqrdRow<?php echo $cntr; ?>_ItemDesc', 'clear', 1, '');">
                                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                                </label>
                                                            </div>
                                                        </td>
                                                        <td class="lovtd"> 
                                                            <input type="number" style="width:100% !important;" min="1" class="form-control rqrdFld" aria-label="..." id="allItmServsRqrdRow<?php echo $cntr; ?>_Qty" name="allItmServsRqrdRow<?php echo $cntr; ?>_Qty" value="<?php echo $quantity; ?>"> 
                                                        </td>
                                                        <td class="lovtd">                                                             
                                                            <textarea style="width:100% !important;" class="form-control" aria-label="..." id="allItmServsRqrdRow<?php echo $cntr; ?>_Cmnts" name="allItmServsRqrdRow<?php echo $cntr; ?>_Cmnts" ><?php echo $cmnts; ?></textarea>                                                                       
                                                        </td>
                                                        <?php if ($sbmtdAppntmntStatus == "Open") { ?>
                                                            <td class="lovtd">
                                                                <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delItmServsRqrd('allItmServsRqrdRow_<?php echo $cntr; ?>', '<?php echo $itmservs_rqrd_id; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Item">
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
                <?php
            } 
        }
    }
}