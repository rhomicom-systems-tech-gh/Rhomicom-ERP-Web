<?php

$dateStr = getDB_Date_time();
$pkID = $PKeyID;

$prsnid = $_SESSION['PRSN_ID'];

$prsnJob = getPrsnJobNm($prsnid);

if (array_key_exists('lgn_num', get_defined_vars())) {
    if ($lgn_num > 0 && $canview === true) {
        //echo $_POST['dataToSend'];
        //var_dump($_POST);

        if ($qstr == "DELETE") {
            //var_dump($_POST);
            //echo $PKeyID;
            //exit;
            if ($actyp == 1) {//Header
                //BANK BRANCHES
                $PrvdrRowID = isset($_POST['PrvdrRowID']) ? cleanInputData($_POST['PrvdrRowID']) : -1; 
                $SrvsPrvdrID = isset($_POST['SrvsPrvdrID']) ? cleanInputData($_POST['SrvsPrvdrID']) : -1; 
                
                $bankPersonInUse = isPrvdrGroupPersonInActiveUse($SrvsPrvdrID);

                $dateStr = getDB_Date_time();
                //check loan status -> Incomplete, Rejected and Withdrawn CAN BE DELETED
                if ($bankPersonInUse) {
                    echo "SORRY";
                    exit();
                } else {
                    /*$updtSQL3 = "UPDATE chqbkos.chqbkos_banks SET last_update_by = $usrID, last_update_date = '$dateStr'"
                            . " WHERE bank_id = (SELECT bank_id FROM chqbkos.chqbkos_sort_codes WHERE sort_code_id = $PKeyID)";
                    execUpdtInsSQL($updtSQL3);*/
                    
                    $rowCnt = deleteCreditPrvdrGroupPersons($PrvdrRowID);

                    if ($rowCnt > 0) {                        
                        echo "Group Person Deleted Successfully";
                    } else {
                        echo "Failed to Delete Group Person";
                    }
                    
                    exit();
                }
                exit();
            } else {//Details
                //PROVIDER GROUPS
                $bankInUse = isPrvdrGroupInActiveUse($PKeyID);
                //check loan status -> Incomplete, Rejected and Withdrawn CAN BE DELETED
                if ($bankInUse) {
                    echo "SORRY";
                    exit();
                } else {
                    $rowCnt = deleteCreditPrvdrGroups($PKeyID);
                    if ($rowCnt > 0) {
                        echo "Group Deleted Successfully";
                    } else {
                        echo "Failed to Delete Group";
                    }
                    exit();
                }
            }
        } else if ($qstr == "UPDATE") {
            if ($actyp == 1) {
                //SERVICE PROVIDERS
                $sbmtdPrvdrGroupID = isset($_POST['sbmtdPrvdrGroupID']) ? cleanInputData($_POST['sbmtdPrvdrGroupID']) : -1;
                $slctdPrvdrGroupPersons = isset($_POST['slctdPrvdrGroupPersons']) ? cleanInputData($_POST['slctdPrvdrGroupPersons']) : '';

                $dateStr = getDB_Date_time();
                $recCntInst = 0;
                $recCntUpdt = 0;

                if (trim($slctdPrvdrGroupPersons, "|~") != "") {
                    //$delSQL3 = "DELETE from mcf.mcf_credit_risk_profile_factors WHERE bank_id = $sbmtdPrvdrGroupID";
                    //execUpdtInsSQL($delSQL3);

                    $variousRows = explode("|", trim($slctdPrvdrGroupPersons, "|"));
                    for ($z = 0; $z < count($variousRows); $z++) {
                        $crntRow = explode("~", $variousRows[$z]);
                        if (count($crntRow) == 5) {
                            $prvdrGroupSrvsPrvdrID = (int) (cleanInputData1($crntRow[0]));
                            $providerType =  cleanInputData1($crntRow[1]);
                            $prvdrGroupPersonID =  (int)cleanInputData1($crntRow[2]);
                            $startDate =  cleanInputData1($crntRow[3]);
                            if($startDate != ""){
                                $startDate = cnvrtDMYToYMD($startDate);
                            }
                            $endDate =  cleanInputData1($crntRow[4]);
                            
                            if($endDate == ""){
                                $endDate = "4000-12-31";
                            } else {
                                $endDate = cnvrtDMYToYMD($endDate);
                            }
                            
                            $srvs_type_id = (int)getGnrlRecNm("hosp.prvdr_grps", "prvdr_grp_id", "main_srvc_type_id", $sbmtdPrvdrGroupID);
                            $max_daily_appntmnts = (int)getGnrlRecNm("hosp.prvdr_grps", "prvdr_grp_id", "max_daily_appntmnts", $sbmtdPrvdrGroupID);
                            
                            if ($prvdrGroupSrvsPrvdrID > 0) {
                                $recCntUpdt = $recCntUpdt + updateCreditPrvdrGroupPersons($prvdrGroupSrvsPrvdrID, $srvs_type_id, $sbmtdPrvdrGroupID, $providerType, $prvdrGroupPersonID, $max_daily_appntmnts, $startDate, $endDate, $dateStr);
                            } else {
                                $prvdrGroupSrvsPrvdrID = getCreditPrvdrGroupPersonID();
                                $recCntInst = $recCntInst + createCreditPrvdrGroupPersons($prvdrGroupSrvsPrvdrID, $srvs_type_id, $sbmtdPrvdrGroupID, $providerType, $prvdrGroupPersonID, $max_daily_appntmnts, $startDate, $endDate, $dateStr);
                            }
                        }
                    }

                    echo "<span style='color:green;font-weight:bold !important;'><i>$recCntInst record(s) inserted</br>$recCntUpdt record(s) updated</i></span>";
                    exit();
                } else {
                    echo '<div><img src="cmn_images/error.gif" style="float:left;margin-right:5px;width:30px;height:30px;"/>'
                    . 'Please provide one Person Record before saving!<br/></div>';
                    exit();
                }
            } 
            else {
                //BANKS
                $prvdrGroupDesc = isset($_POST['prvdrGroupDesc']) ? cleanInputData($_POST['prvdrGroupDesc']) : '';
                $prvdrGroupName = isset($_POST['prvdrGroupName']) ? cleanInputData($_POST['prvdrGroupName']) : '';
                $prvdrGrpId = isset($_POST['prvdrGrpId']) ? cleanInputData($_POST['prvdrGrpId']) : -1;
                $prvdrGroupMainSrvcTypeId = isset($_POST['prvdrGroupMainSrvcTypeId']) ? cleanInputData($_POST['prvdrGroupMainSrvcTypeId']) : -1;
                $prvdrGroupDetMaxDailyAppntmnts = isset($_POST['prvdrGroupDetMaxDailyAppntmnts']) ? cleanInputData($_POST['prvdrGroupDetMaxDailyAppntmnts']) : 1000;
                $isEnabled = isset($_POST['isEnabled']) ? cleanInputData($_POST['isEnabled']) : 'Yes';
                $prvdrGrpCostItmId = isset($_POST['prvdrGrpCostItmId']) ? cleanInputData($_POST['prvdrGrpCostItmId']) : -1;

                $dateStr = getDB_Date_time();

                if ($prvdrGroupName != "" && $prvdrGroupDesc != "" && (int)$prvdrGroupMainSrvcTypeId != -1 && (float)$prvdrGroupDetMaxDailyAppntmnts > 0) {
                    if ($prvdrGrpId <= 0) {
                        $prvdrGrpId = getCreditPrvdrGroupID();
                        $rsltExts = doesPrvdrGroupNameExistsSave($prvdrGroupName);
                        if ($rsltExts) {
                            echo '<div><img src="cmn_images/error.gif" style="float:left;margin-right:5px;width:30px;height:30px;"/>'
                            . 'Sorry! Group Name already Exists!<br/></div>';
                            exit();
                        }
                        
                        $rsltCodeExts = doesPrvdrGroupMainSrvcTypeIdExistsSave($prvdrGroupMainSrvcTypeId);
                        if ($rsltCodeExts && 1 > 2) {
                            echo '<div><img src="cmn_images/error.gif" style="float:left;margin-right:5px;width:30px;height:30px;"/>'
                            . 'Sorry! The Main Service selected is already assigned to another Provider Group!<br/></div>';
                            exit();
                        }
                        //$prvdrGrpId, $prvdrGrpName, $prvdrGrpDesc, $main_srvc_type_id, $max_daily_appntmnts, $isEnabled, $dateStr
                        $rowCnt = createCreditPrvdrGroups($prvdrGrpId, $prvdrGroupName, $prvdrGroupDesc, $prvdrGroupMainSrvcTypeId, $prvdrGroupDetMaxDailyAppntmnts, $prvdrGrpCostItmId,  $isEnabled, $dateStr);
                        if ($rowCnt <= 0) {
                            echo '<div><img src="cmn_images/error.gif" style="float:left;margin-right:5px;width:30px;height:30px;"/>'
                            . 'Failed to Save Group!<br/></div>';
                            exit();
                        }
                    } else {
                        $rsltExts = doesPrvdrGroupNameExistsUpdate($prvdrGrpId, $prvdrGroupName);
                        if ($rsltExts) {
                            echo '<div><img src="cmn_images/error.gif" style="float:left;margin-right:5px;width:30px;height:30px;"/>'
                            . 'Sorry! Update Failed! Group Name already Exists!<br/></div>';
                            exit();
                        }
                        
                        $rsltCodeExts = doesPrvdrGroupMainSrvcTypeIdExistsUpdate($prvdrGrpId, $prvdrGroupMainSrvcTypeId);
                        if ($rsltExts) {
                            echo '<div><img src="cmn_images/error.gif" style="float:left;margin-right:5px;width:30px;height:30px;"/>'
                            . 'Sorry! Update Failed! The Main Service selected is already assigned to another Provider Group!<br/></div>';
                            exit();
                        }

                        $rowCnt = updateCreditPrvdrGroups($prvdrGrpId, $prvdrGroupName, $prvdrGroupDesc, $prvdrGroupMainSrvcTypeId, $prvdrGroupDetMaxDailyAppntmnts, $prvdrGrpCostItmId,  $isEnabled, $dateStr);
                        if ($rowCnt <= 0) {
                            echo '<div><img src="cmn_images/error.gif" style="float:left;margin-right:5px;width:30px;height:30px;"/>'
                            . 'Failed to Update Group!<br/></div>';
                            exit();
                        } else {
                            $updtSQL3 = "UPDATE hosp.srvs_prvdrs SET srvs_type_id = $prvdrGroupMainSrvcTypeId, max_daily_appntmnts = $prvdrGroupDetMaxDailyAppntmnts, last_update_by = $usrID, last_update_date = '$dateStr'"
                                    . " WHERE prvdr_id = $prvdrGrpId";
                            execUpdtInsSQL($updtSQL3);
                        }
                    }

                    echo json_encode(array("prvdrGrpId" => $prvdrGrpId));

                    exit();
                } else {
                    echo '<div><img src="cmn_images/error.gif" style="float:left;margin-right:5px;width:30px;height:30px;"/>'
                    . 'Please complete all mandatory fields before saving!<br/></div>';
                    exit();
                }
            }
        } else {
            if (1 == 1) {
                //var_dump($_POST);
                $canAddPrvdrGroup = test_prmssns($dfltPrvldgs[17], $mdlNm);
                $canEdtPrvdrGroup = test_prmssns($dfltPrvldgs[18], $mdlNm);
                $canDelPrvdrGroup = test_prmssns($dfltPrvldgs[19], $mdlNm);

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
                                    <span style=\"text-decoration:none;\">Service Providers</span>
				</li>
                               </ul>
                              </div>";

                    $total = getCreditPrvdrGroupsTblTtl($isEnabledOnly, $srchFor, $srchIn, $searchAll);
                    if ($pageNo > ceil($total / $lmtSze)) {
                        $pageNo = 1;
                    } else if ($pageNo < 1) {
                        $pageNo = ceil($total / $lmtSze);
                    }

                    $curIdx = $pageNo - 1;
                    $result = getCreditPrvdrGroupsTbl($isEnabledOnly, $srchFor, $srchIn, $curIdx, $lmtSze, $searchAll, $sortBy);
                    $cntr = 0;
                    $colClassType1 = "col-lg-2";
                    $colClassType2 = "col-lg-3";
                    $colClassType3 = "col-lg-1";
                    
                    ?>
                    <form id='allPrvdrGroupsForm' action='' method='post' accept-charset='UTF-8'>
                        <!--<fieldset class="basic_person_fs5">-->
                        <legend class="basic_person_lg1" style="color: #003245">SERVICE PROVIDERS GROUPS</legend>                
                        <input class="form-control" id="tblRowID" type = "hidden" placeholder="ROW ID"/>                     
                        <div class="row rhoRowMargin" style="margin-bottom:10px;">
                    <?php
                    if ($canAddPrvdrGroup === true) {
                        ?>   
                                <div class="<?php echo $colClassType1; ?>" style="padding:0px 1px 0px 15px !important;">                    
                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="getOnePrvdrGroupForm(-1);">
                                        <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                        New Group
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
                                    <input class="form-control" id="allPrvdrGroupsSrchFor" type = "text" placeholder="Search For" value="<?php echo trim(str_replace("%", " ", $srchFor)); ?>" onkeyup="enterKeyFuncAllPrvdrGroups(event, '', '#allmodules', 'grp=14&typ=1&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&mdl=Clinic/Hospital')">
                                    <input id="allPrvdrGroupsPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getAllPrvdrGroups('clear', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&mdl=Clinic/Hospital');">
                                        <span class="glyphicon glyphicon-remove"></span>
                                    </label>
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getAllPrvdrGroups('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&mdl=Clinic/Hospital');">
                                        <span class="glyphicon glyphicon-search"></span>
                                    </label> 
                                </div>
                            </div>
                            <div class="<?php echo $colClassType2; ?>">
                                <div class="input-group">
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="allPrvdrGroupsSrchIn">
                                    <?php
                                    $valslctdArry = array("", "", "");
                                    $srchInsArrys = array("Group Name", "Description", "Main Service Offered");
                                    for ($z = 0; $z < count($srchInsArrys); $z++) {
                                        if ($srchIn == $srchInsArrys[$z]) {
                                            $valslctdArry[$z] = "selected";
                                        }
                                        ?>
                                            <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                    <?php } ?>
                                    </select>
                                    <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="allPrvdrGroupsDsplySze" style="min-width:70px !important;">                            
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
                                        <input type="checkbox" class="form-check-input" onclick="getAllPrvdrGroups('clear', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&mdl=Clinic/Hospital');" id="allPrvdrGroupsIsEnabled" name="allPrvdrGroupsIsEnabled" <?php echo $nonAprvdChekd; ?>>
                                        Enabled?
                                    </label>
                                </div>                             
                            </div>
                            <div class="<?php echo $colClassType3; ?>" style="width:10% !important;">
                                <nav aria-label="Page navigation">
                                    <ul class="pagination" style="margin: 0px !important;">
                                        <li>
                                            <a href="javascript:getAllPrvdrGroups('previous', '#allmodules', 'grp=14&typ=1&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&mdl=Clinic/Hospital');" aria-label="Previous">
                                                <span aria-hidden="true">&laquo;</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="javascript:getAllPrvdrGroups('next', '#allmodules', 'grp=14&typ=1&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&mdl=Clinic/Hospital');" aria-label="Next">
                                                <span aria-hidden="true">&raquo;</span>
                                            </a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>  
                            <div class="<?php echo $colClassType3; ?>" style="padding:0px 1px 0px 3px !important;"> 
                                <div style="float:right !important;">
                                    <?php if ($canAddPrvdrGroup === true) { ?>
                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="savePrvdrGroup(<?php echo $pgNo; ?>, <?php echo $vwtyp; ?>);" data-toggle="tooltip" data-placement="bottom" title="Save Group">
                                        <img src="cmn_images/FloppyDisk.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                        Save Group
                                    </button>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>               
                        <div class="row" style="padding:0px 15px 0px 15px !important"> 
                            <div class="col-md-4" style="padding:0px 1px 0px 1px !important">
                                <fieldset class="basic_person_fs">                                        
                                    <table class="table table-striped table-bordered table-responsive" id="allPrvdrGroupsTable" cellspacing="0" width="100%" style="width:100%;">
                                        <thead>
                                            <tr>
                                                <th>No.</th>
                                                <th>Group Name</th>
                                                <th>Main Service Offered</th>                                   
                                        <?php if ($canDelPrvdrGroup === true) { ?>
                                                    <th>&nbsp;</th>
                                        <?php } ?>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $sbmtdPrvdrGroupID = -1;
                                            while ($row = loc_db_fetch_array($result)) {
                                                if ($sbmtdPrvdrGroupID <= 0 && $cntr <= 0) {
                                                    $sbmtdPrvdrGroupID = $row[0];
                                                }

                                                $mainSvsOffrd = getGnrlRecNm("hosp.srvs_types", "type_id", "type_name", $row[4]);

                                                $cntr += 1;
                                                ?>
                                                <tr id="allPrvdrGroupsRow_<?php echo $cntr; ?>" class="hand_cursor">                                    
                                                    <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                                    <td class="lovtd"><?php echo $row[2]; ?>
                                                        <input type="hidden" class="form-control" aria-label="..." id="allPrvdrGroupsRow<?php echo $cntr; ?>_PrvdrGroupID" value="<?php echo $row[0]; ?>">
                                                    </td>
                                                    <td class="lovtd">
                                                        <?php echo $mainSvsOffrd; ?>
                                                    </td>
                                                        <?php if ($canDelPrvdrGroup === true) { ?>
                                                        <td class="lovtd">
                                                            <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="deletePrvdrGroup(<?php echo $row[0]; ?>)" data-toggle="tooltip" data-placement="bottom" title="Delete Group Name">
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
                                    <div class="" id="allPrvdrGroupsDetailInfo">
                                        <div class="row" id="allPrvdrGroupsHdrInfo" style="padding:0px 15px 0px 15px !important">
                    <?php
                    $onePrvdrGroupDetID = -1;
                    $onePrvdrGroupDetName = "";
                    $onePrvdrGroupDetDesc = "";
                    $onePrvdrGroupDetIsEnbld = "1";
                    $onePrvdrGroupDetMainServiceNm = "";
                    $onePrvdrGroupDetMainServiceId = -1;
                    $onePrvdrGroupDetMaxDailyAppntmnts = "1000";
                    $onePrvdrGroupDetCostItmNm = "";
                    $onePrvdrGroupDetCostItmId = -1;
                    
                    $result1 = getCreditPrvdrGroupsDets($sbmtdPrvdrGroupID);
                    while ($row1 = loc_db_fetch_array($result1)) {
                        $onePrvdrGroupDetID = $row1[0];
                        $onePrvdrGroupDetName = $row1[1];
                        $onePrvdrGroupDetDesc = $row1[2];
                        $onePrvdrGroupDetIsEnbld = $row1[3];
                        $onePrvdrGroupDetMainServiceId = $row1[4];
                        $onePrvdrGroupDetMainServiceNm = getGnrlRecNm("hosp.srvs_types", "type_id", "type_name", $row1[4]);
                        $onePrvdrGroupDetMaxDailyAppntmnts = $row1[5];
                        $onePrvdrGroupDetCostItmNm = $row1[8];
                        $onePrvdrGroupDetCostItmId = $row1[7];
                    }
                    ?>
                                            <fieldset class="basic_person_fs" style="padding:10px 3px 0px 3px !important;border:none !important;border-bottom:1px solid #ddd !important;border-radius: 0px !important;">                                              
                                                <div  class="col-md-6" style="padding:0px 3px 0px 3px !important;">
                                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                        <label for="onePrvdrGroupDetName" class="control-label col-lg-4" style="padding:0px 15px 0px 1px !important;">Group Name:</label>
                                                        <div  class="col-lg-8" style="padding:0px 15px 0px 1px !important;">
                                                                <?php if ($canEdtPrvdrGroup === true) { ?>
                                                                <input type="text" class="form-control rqrdFld" aria-label="..." id="onePrvdrGroupDetName" name="onePrvdrGroupDetName" value="<?php echo $onePrvdrGroupDetName; ?>" style="width:100%;">
                                                                <input type="hidden" class="form-control" aria-label="..." id="onePrvdrGroupDetID" name="onePrvdrGroupDetID" value="<?php echo $onePrvdrGroupDetID; ?>">
                                                                <?php } else { ?>
                                                                <span><?php echo $onePrvdrGroupDetName; ?></span>
                                                                <?php } ?>
                                                        </div>
                                                    </div>                                                        
                                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                        <label for="onePrvdrGroupDetDesc" class="control-label col-lg-4" style="padding:0px 15px 0px 1px !important;">Description:</label>
                                                        <div class="col-lg-8" style="padding:0px 15px 0px 1px !important;">
                                                        <?php if ($canEdtPrvdrGroup === true) { ?>
                                                        <input type="text" class="form-control rqrdFld" aria-label="..."  id="onePrvdrGroupDetDesc" name="onePrvdrGroupDetDesc" value="<?php echo $onePrvdrGroupDetDesc; ?>" style="width:100%;">
                                                        <?php } else {  ?>
                                                        <span><?php echo $onePrvdrGroupDetDesc; ?></span>
                                                        <?php } ?>
                                                        </div>
                                                    </div>
                                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                        <label for="onePrvdrGroupDetMaxDailyAppntmnts" class="control-label col-lg-4" style="padding:0px 15px 0px 1px !important;">Max. Daily Appointments:</label>
                                                        <div class="input-group col-lg-8" style="padding:0px 15px 0px 1px !important;">
                                                            <?php if ($canEdtPrvdrGroup === true) { ?>
                                                            <input type="number" min="1" class="form-control rqrdFld" aria-label="..." id="onePrvdrGroupDetMaxDailyAppntmnts" value="<?php echo $onePrvdrGroupDetMaxDailyAppntmnts; ?>">
                                                            <?php } else { ?>
                                                            <span><?php echo $onePrvdrGroupDetMainServiceNm; ?></span>
                                                            <?php } ?>
                                                        </div>
                                                    </div>      
                                                </div>
                                                <div  class="col-md-6" style="padding:0px 3px 0px 3px !important;">
                                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                        <label for="onePrvdrGroupDetMainServiceNm" class="control-label col-lg-4" style="padding:0px 15px 0px 1px !important;">Main Service:</label>
                                                        <div class="input-group col-lg-8" style="padding:0px 15px 0px 1px !important;">
                                                            <?php if ($canEdtPrvdrGroup === true) { ?>
                                                            <input type="text" class="form-control rqrdFld" aria-label="..." id="onePrvdrGroupDetMainServiceNm" value="<?php echo $onePrvdrGroupDetMainServiceNm; ?>" readonly="true">
                                                            <input type="hidden" class="form-control" aria-label="..." id="onePrvdrGroupDetMainServiceId" value="<?php echo $onePrvdrGroupDetMainServiceId; ?>">
                                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Service Types', '', '', '', 'radio', true, '', 'onePrvdrGroupDetMainServiceId', 'onePrvdrGroupDetMainServiceNm', 'clear', 0, '');">
                                                                <span class="glyphicon glyphicon-th-list"></span>
                                                            </label>
                                                            <?php } else { ?>
                                                            <span><?php echo $onePrvdrGroupDetMainServiceNm; ?></span>
                                                            <?php } ?>
                                                        </div>
                                                    </div>
                                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                        <label for="onePrvdrGroupDetCostItmNm" class="control-label col-lg-4" style="padding:0px 15px 0px 1px !important;">Cost Item:</label>
                                                        <div class="input-group col-lg-8" style="padding:0px 15px 0px 1px !important;">
                                                            <?php if ($canEdtPrvdrGroup === true) { ?>
                                                            <input type="text" class="form-control" aria-label="..." id="onePrvdrGroupDetCostItmNm" value="<?php echo $onePrvdrGroupDetCostItmNm; ?>" readonly="true">
                                                            <input type="hidden" class="form-control" aria-label="..." id="onePrvdrGroupDetCostItmId" value="<?php echo $onePrvdrGroupDetCostItmId; ?>">
                                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Inventory Services', 'gnrlOrgID', '', '', 'radio', true, '', 'onePrvdrGroupDetCostItmId', 'onePrvdrGroupDetCostItmNm', 'clear', 0, '');">
                                                                <span class="glyphicon glyphicon-th-list"></span>
                                                            </label>
                                                            <?php } else { ?>
                                                            <span><?php echo $onePrvdrGroupDetCostItmNm; ?></span>
                                                            <?php } ?>
                                                        </div>
                                                    </div>
                                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                        <label for="onePrvdrGroupDetIsEnbld" class="control-label col-lg-4" style="padding:0px 15px 0px 1px !important;">Is Enabled:</label>
                                                        <div  class="col-lg-8" style="padding:0px 15px 0px 1px !important;">
                    <?php if ($canEdtPrvdrGroup === true) { ?>
                                                                <select class="form-control" id="onePrvdrGroupDetIsEnbld" >
                                                                <?php
                                                                $sltdYes = "";
                                                                $sltdNo = "";
                                                                if ($onePrvdrGroupDetIsEnbld == "1") {
                                                                    $sltdYes = "selected";
                                                                } else if ($onePrvdrGroupDetIsEnbld == "0") {
                                                                    $sltdNo = "selected";
                                                                }
                                                                ?>
                                                                    <option value="1" <?php echo $sltdYes; ?>>Yes</option>
                                                                    <option value="0" <?php echo $sltdNo; ?>>No</option>
                                                                </select>
                    <?php } else {
                        ?>
                                                                <span><?php echo ($onePrvdrGroupDetIsEnbld == "1") ? 'Yes' : 'No'; ?></span>
                                                                <?php
                                                            }
                                                            ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </fieldset>
                                        </div> 
                                        <div class="row" id="allPrvdrGroupPersonsDetailInfo" style="padding:0px 15px 0px 15px !important">
                                                            <?php
                                                            /* &vtyp=<?php echo $vwtyp; ?> */
                                                            $srchFor = "%";
                                                            $srchIn = "Person Name";
                                                            $pageNo = 1;
                                                            $lmtSze = 10;
                                                            $vwtyp = 1;
                                                            if ($sbmtdPrvdrGroupID > 0) {
                                                                $total = get_AllPrvdrGroupPersonsTtl($srchFor, $srchIn, $sbmtdPrvdrGroupID);
                                                                //$total = get_AllPrvdrGroupsTtl($srchFor, $srchIn, $sbmtdPrvdrGroupID);
                                                                if ($pageNo > ceil($total / $lmtSze)) {
                                                                    $pageNo = 1;
                                                                } else if ($pageNo < 1) {
                                                                    $pageNo = ceil($total / $lmtSze);
                                                                }
                                                                $curIdx = $pageNo - 1;
                                                                $result2 = get_AllPrvdrGroupPersons($srchFor, $srchIn, $curIdx, $lmtSze, $sbmtdPrvdrGroupID);
                                                                ?>
                                                <div class="row" style="padding:0px 15px 0px 15px !important">
                                                    <legend class="basic_person_lg1" style="color: #003245">SERVICE PROVIDERS</legend>
                                                                    <?php
                                                                    if ($canEdtPrvdrGroup === true) {
                                                                        $colClassType1 = "col-lg-2";
                                                                        $colClassType2 = "col-lg-3";
                                                                        $colClassType3 = "col-lg-4";
                                                                        $nwRowHtml = "<tr id=\"allPrvdrGroupPersonsRow__WWW123WWW\">
                                                                        <td class=\"lovtd\"><span class=\"normaltd\">New</span></td>
                                                                        <td class=\"lovtd\" style=\"max-width:80px !important;\">
                                                                            <div class=\"form-group form-group-sm\" style=\"width:100% !important;margin-bottom:0px !important;\">
                                                                                <select style=\"width:100% !important;\" data-placeholder=\"Select...\" class=\"form-control chosen-select rqrdFld\" id=\"allPrvdrGroupPersonsRow_WWW123WWW_PrvdrType\" onchange=\"onChangeOfPrvdrType(_WWW123WWW)\">";
                                                                                    $valslctdArry = array("");//, "");
                                                                                    $srchInsArrys = array("Staff");//, "Locum");
										    
                                                                                    for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                                                        $selected = "";
                                                                                        if($srchInsArrys[$z] == "Staff"){
                                                                                            $selected = "selected";
                                                                                        }
                                                                                        $nwRowHtml .="<option value=\"$srchInsArrys[$z]\" $selected>$srchInsArrys[$z]</option>";
                                                                                    } 
                                                                                $nwRowHtml .="</select>
                                                                                <input type=\"hidden\"  class=\"form-control\" aria-label=\"...\" id=\"allPrvdrGroupPersonsRow_WWW123WWW_PrvdrGroupSrvsPrvdrID\" value=\"-1\" style=\"width:100% !important;\">                                                                                                                          
										<input type=\"hidden\"  class=\"form-control\" aria-label=\"...\" id=\"allPrvdrGroupPersonsRow_WWW123WWW_PrvdrGroupID\" value=\"$onePrvdrGroupDetID\" style=\"width:100% !important;\">                                                                                                                          
                                                                            </div>                                                      
                                                                        </td>
                                                                        <td style=\"min-width: 270px !important;\">
                                                                            <div class=\"input-group\"  style=\"width:100%;\">
                                                                                <input type=\"text\" class=\"form-control rqrdFld\" aria-label=\"...\" id=\"allPrvdrGroupPersonsRow_WWW123WWW_PrvdrGroupPersonNm\" value=\"\" readonly=\"true\">
                                                                                <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"allPrvdrGroupPersonsRow_WWW123WWW_PrvdrGroupPersonID\" value=\"\">
                                                                                <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getServiceProviderPersonList(_WWW123WWW)\">
                                                                                    <span class=\"glyphicon glyphicon-th-list\"></span>
                                                                                </label>
                                                                            </div>
                                                                        </td>
                                                                        <td class=\"lovtd\"> 
                                                                            <div class=\"input-group date form_date\" data-date=\"\" data-date-format=\"dd-M-yyyy\" data-link-field=\"dtp_input2\" data-link-format=\"yyyy-mm-dd\">
                                                                                <input style=\"width:100% !important;\" class=\"form-control rqrdFld\" size=\"16\" type=\"text\" id=\"allPrvdrGroupPersonsRow_WWW123WWW_StartDate\" name=\"allPrvdrGroupPersonsRow_WWW123WWW_StartDate\" value=\"\">
                                                                                <!--<span class=\"input-group-addon\"><span class=\"glyphicon glyphicon-remove\"></span></span>-->
                                                                                <span class=\"input-group-addon\"><span class=\"glyphicon glyphicon-calendar\"></span></span>
                                                                            </div>
                                                                        </td>
                                                                        <td class=\"lovtd\">
                                                                            <div class=\"input-group date form_date\" data-date=\"\" data-date-format=\"dd-M-yyyy\" data-link-field=\"dtp_input2\" data-link-format=\"yyyy-mm-dd\">
                                                                                <input style=\"width:100% !important;\" class=\"form-control\" size=\"16\" type=\"text\" id=\"allPrvdrGroupPersonsRow_WWW123WWW_EndDate\" name=\"allPrvdrGroupPersonsRow_WWW123WWW_EndDate\" value=\"\">
                                                                                <!--<span class=\"input-group-addon\"><span class=\"glyphicon glyphicon-remove\"></span></span>-->
                                                                                <span class=\"input-group-addon\"><span class=\"glyphicon glyphicon-calendar\"></span></span>
                                                                            </div>
                                                                        </td>
                                                                        <td class=\"lovtd\">
                                                                            <button type=\"button\" class=\"btn btn-default\" style=\"margin: 0px !important;padding:0px 3px 2px 4px !important;\" onclick=\"delPrvdrGroupPerson('allPrvdrGroupPersonsRow__WWW123WWW');\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Delete Person\">
                                                                                    <img src=\"cmn_images/no.png\" style=\"height:15px; width:auto; position: relative; vertical-align: middle;\">
                                                                            </button>
                                                                        </td>
                                                                    </tr>";
                                                                        ?>
                                                        <div class="<?php echo $colClassType1; ?>" style="padding:0px 1px 0px 3px !important;">  
                                                            <?php if ($canAddPrvdrGroup === true) { ?>
                                                            <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="insertNewRowBe4('allPrvdrGroupPersonsTable', 0, '<?php echo urlencode($nwRowHtml); ?>');" data-toggle="tooltip" data-placement="bottom" title="Add Person">
                                                                <img src="cmn_images/add1-64.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                            </button>
                                                            <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="savePrvdrGroupPerson();" data-toggle="tooltip" data-placement="bottom" title="Save Person">
                                                                <img src="cmn_images/FloppyDisk.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
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
                                                            <input class="form-control" id="allPrvdrGroupPersonsSrchFor" type = "text" placeholder="Search For" value="<?php
                                                    echo trim(str_replace("%", " ", $srchFor));
                                                    ?>" onkeyup="enterKeyFuncAllPrvdrGroupPersons(event, '', '#allPrvdrGroupPersonsDetailInfo', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo "2"; ?>&sbmtdPrvdrGroupID=<?php echo $sbmtdPrvdrGroupID; ?>');">
                                                            <input id="allPrvdrGroupPersonsPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getAllPrvdrGroupPersons('clear', '#allPrvdrGroupPersonsDetailInfo', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo "2"; ?>&sbmtdPrvdrGroupID=<?php echo $sbmtdPrvdrGroupID; ?>&mdl=Clinic/Hospital');">
                                                                <span class="glyphicon glyphicon-remove"></span>
                                                            </label>
                                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getAllPrvdrGroupPersons('', '#allPrvdrGroupPersonsDetailInfo', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo "2"; ?>&sbmtdPrvdrGroupID=<?php echo $sbmtdPrvdrGroupID; ?>&mdl=Clinic/Hospital');">
                                                                <span class="glyphicon glyphicon-search"></span>
                                                            </label> 
                                                        </div>
                                                    </div>
                                                    <div class="<?php echo $colClassType2; ?>" style="padding:0px 3px 0px 3px !important;">
                                                        <div class="input-group">
                                                            <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                                            <!--<select data-placeholder="Select..." class="form-control chosen-select" id="allPrvdrGroupPersonsSrchIn">
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
                                                            <select data-placeholder="Select..." class="form-control chosen-select" id="allPrvdrGroupPersonsDsplySze" style="min-width:70px !important;">                            
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
                                                                    <a class="rhopagination" href="javascript:getAllPrvdrGroupPersons('previous', '#allPrvdrGroupPersonsDetailInfo', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo "2"; ?>&sbmtdPrvdrGroupID=<?php echo $sbmtdPrvdrGroupID; ?>&mdl=Clinic/Hospital');" aria-label="Previous">
                                                                        <span aria-hidden="true">&laquo;</span>
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <a class="rhopagination" href="javascript:getAllPrvdrGroupPersons('next', '#allPrvdrGroupPersonsDetailInfo', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo "2"; ?>&sbmtdPrvdrGroupID=<?php echo $sbmtdPrvdrGroupID; ?>&mdl=Clinic/Hospital');" aria-label="Next">
                                                                        <span aria-hidden="true">&raquo;</span>
                                                                    </a>
                                                                </li>
                                                            </ul>
                                                        </nav>
                                                        <input type="hidden" class="form-control" aria-label="..." id="gnrlOrgID" name="gnrlOrgID" value="<?php echo $orgID; ?>">                                                    
                                                        <input type="hidden" class="form-control" aria-label="..." id="sbmtdPrvdrGroupID" name="sbmtdPrvdrGroupID" value="<?php echo $sbmtdPrvdrGroupID; ?>">
                                                    </div>
                                                </div>
                                                <div class="row" style="padding:0px 15px 0px 15px !important">                  
                                                    <div class="col-md-12" style="padding:0px 3px 0px 3px !important">
                                                        <table class="table table-striped table-bordered table-responsive" id="allPrvdrGroupPersonsTable" cellspacing="0" width="100%" style="width:100%;min-width: 300px !important;">
                                                            <thead>
                                                                <tr>
                                                                    <th>No.</th>
                                                                    <th>Type</th>
                                                                    <th>Person Name</th>
                                                                    <th>Start Date</th>
                                                                    <th>End Date</th>
                                                                    <th>&nbsp;</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <input type="text" style="display:none !important;" id="lovOrgID" value="<?php echo $orgID; ?>"/>
                                                                <?php
                                                                $cntr = 0;
                                                                while ($row2 = loc_db_fetch_array($result2)) {
                                                                    $cntr += 1;
                                                                    $ttlOptnsScore = 0;
                                                                    ?>
                                                                    <tr id="allPrvdrGroupPersonsRow_<?php echo $cntr; ?>">                                    
                                                                        <td class="lovtd"><span><?php echo ($curIdx * $lmtSze) + ($cntr); ?></span></td>
                                                                        <td class="lovtd" style="max-width:80px !important;">
                                                                            <div class="form-group form-group-sm" style="width:100% !important;margin-bottom:0px !important;">
                                                                                <select style="width:100% !important;" data-placeholder="Select..." class="form-control chosen-select rqrdFld" id="allPrvdrGroupPersonsRow<?php echo $cntr; ?>_PrvdrType" onchange="onChangeOfPrvdrType(<?php echo $cntr; ?>)">
                                                                                    <?php
                                                                                    $valslctdArry = array("");//, "");
                                                                                    $srchInsArrys = array("Staff");//, "Locum");

                                                                                    for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                                                        if ($row2[2] == $srchInsArrys[$z]) {
                                                                                            $valslctdArry[$z] = "selected";
                                                                                        }
                                                                                        ?>
                                                                                        <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                                                                    <?php } ?>
                                                                                </select>
                                                                                <input type="hidden" class="form-control" aria-label="..." id="allPrvdrGroupPersonsRow<?php echo $cntr; ?>_PrvdrGroupSrvsPrvdrID" value="<?php echo $row2[0]; ?>" style="width:100% !important;"> 
                                                                                <input type="hidden" class="form-control" aria-label="..." id="allPrvdrGroupPersonsRow<?php echo $cntr; ?>_PrvdrGroupID" value="<?php echo $row2[1]; ?>" style="width:100% !important;">
                                                                            </div>                                                      
                                                                        </td>
                                                                        <td style="min-width: 270px !important;">
                                                                            <div class="input-group"  style="width:100%;">
                                                                                <input type="text" class="form-control rqrdFld" aria-label="..." id="allPrvdrGroupPersonsRow<?php echo $cntr; ?>_PrvdrGroupPersonNm" value="<?php echo $row2[4]; ?>" readonly="true">
                                                                                <input type="hidden" class="form-control" aria-label="..." id="allPrvdrGroupPersonsRow<?php echo $cntr; ?>_PrvdrGroupPersonID" value="<?php echo $row2[3]; ?>">
                                                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getServiceProviderPersonList(<?php echo $cntr; ?>)">
                                                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                                                </label>
                                                                            </div>
                                                                        </td>
                                                                        <td class="lovtd"> 
                                                                            <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                                                                <input style="width:100% !important;" class="form-control rqrdFld" size="16" type="text" id="allPrvdrGroupPersonsRow<?php echo $cntr; ?>_StartDate" name="allPrvdrGroupPersonsRow<?php echo $cntr; ?>_StartDate" value="<?php echo $row2[6]; ?>">
                                                                                <!--<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>-->
                                                                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                                            </div>
                                                                        </td>
                                                                        <td class="lovtd">
                                                                            <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                                                                <input style="width:100% !important;" class="form-control" size="16" type="text" id="allPrvdrGroupPersonsRow<?php echo $cntr; ?>_EndDate" name="allPrvdrGroupPersonsRow<?php echo $cntr; ?>_EndDate" value="<?php echo $row2[7]; ?>">
                                                                                <!--<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>-->
                                                                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                                            </div>
                                                                        </td>
                                                                        <td class="lovtd">
                                                                            <?php if ($canDelPrvdrGroup === true) { ?>
                                                                            <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delPrvdrGroupPerson('allPrvdrGroupPersonsRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Group">
                                                                                <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                                            </button>
                                                                            <?php } ?>
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
                } else if ($vwtyp == 1) {//3.1
                    $sbmtdPrvdrGroupID = isset($_POST['sbmtdPrvdrGroupID']) ? cleanInputData($_POST['sbmtdPrvdrGroupID']) : -1;
                    ?>
                    <div class="row" id="allPrvdrGroupsHdrInfo" style="padding:0px 15px 0px 15px !important">
                    <?php
                    $onePrvdrGroupDetID = -1;
                    $onePrvdrGroupDetName = "";
                    $onePrvdrGroupDetDesc = "";
                    $onePrvdrGroupDetIsEnbld = "1";
                    $onePrvdrGroupDetMainServiceNm = "";
                    $onePrvdrGroupDetMainServiceId = -1;
                    $onePrvdrGroupDetMaxDailyAppntmnts = "1000";
                    $onePrvdrGroupDetCostItmNm = "";
                    $onePrvdrGroupDetCostItmId = -1;
                    
                    $result1 = getCreditPrvdrGroupsDets($sbmtdPrvdrGroupID);
                    while ($row1 = loc_db_fetch_array($result1)) {
                        $onePrvdrGroupDetID = $row1[0];
                        $onePrvdrGroupDetName = $row1[1];
                        $onePrvdrGroupDetDesc = $row1[2];
                        $onePrvdrGroupDetIsEnbld = $row1[3];
                        $onePrvdrGroupDetMainServiceId = $row1[4];
                        $onePrvdrGroupDetMainServiceNm = getGnrlRecNm("hosp.srvs_types", "type_id", "type_name", $row1[4]);
                        $onePrvdrGroupDetMaxDailyAppntmnts = $row1[5];
                        $onePrvdrGroupDetCostItmNm = $row1[8];
                        $onePrvdrGroupDetCostItmId = $row1[7];
                    }
                    ?>                            
                        <fieldset class="basic_person_fs" style="padding:10px 3px 0px 3px !important;border:none !important;border-bottom:1px solid #ddd !important;border-radius: 0px !important;">                           
                            <div  class="col-md-6" style="padding:0px 3px 0px 3px !important;">
                                <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                    <label for="onePrvdrGroupDetName" class="control-label col-lg-4" style="padding:0px 15px 0px 1px !important;">Group Name:</label>
                                    <div  class="col-lg-8" style="padding:0px 15px 0px 1px !important;">
                                            <?php if ($canEdtPrvdrGroup === true) { ?>
                                            <input type="text" class="form-control rqrdFld" aria-label="..." id="onePrvdrGroupDetName" name="onePrvdrGroupDetName" value="<?php echo $onePrvdrGroupDetName; ?>" style="width:100%;">
                                            <input type="hidden" class="form-control" aria-label="..." id="onePrvdrGroupDetID" name="onePrvdrGroupDetID" value="<?php echo $onePrvdrGroupDetID; ?>">
                                            <?php } else {
                                                ?>
                                            <span><?php echo $onePrvdrGroupDetName; ?></span>
                        <?php
                    }
                    ?>
                                    </div>
                                </div>
                                <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                    <label for="onePrvdrGroupDetDesc" class="control-label col-lg-4" style="padding:0px 15px 0px 1px !important;">Description:</label>
                                    <div class="col-lg-8" style="padding:0px 15px 0px 1px !important;">
                        <?php if ($canEdtPrvdrGroup === true) { ?>
                                            <input type="text" class="form-control rqrdFld" aria-label="..." id="onePrvdrGroupDetDesc" name="onePrvdrGroupDetDesc" value="<?php echo $onePrvdrGroupDetDesc; ?>" style="width:100%;">
                        <?php } else {
                            ?>
                                            <span><?php echo $onePrvdrGroupDetDesc; ?></span>
                            <?php
                        }
                        ?>
                                    </div>
                                </div> 
                                <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                    <label for="onePrvdrGroupDetMaxDailyAppntmnts" class="control-label col-lg-4" style="padding:0px 15px 0px 1px !important;">Max. Daily Appointments:</label>
                                    <div class="input-group col-lg-8" style="padding:0px 15px 0px 1px !important;">
                                        <?php if ($canEdtPrvdrGroup === true) { ?>
                                        <input type="number" min="1" class="form-control rqrdFld" aria-label="..." id="onePrvdrGroupDetMaxDailyAppntmnts" value="<?php echo $onePrvdrGroupDetMaxDailyAppntmnts; ?>">
                                        <?php } else { ?>
                                        <span><?php echo $onePrvdrGroupDetMainServiceNm; ?></span>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                            <div  class="col-md-6" style="padding:0px 3px 0px 3px !important;">
                                <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                    <label for="onePrvdrGroupDetMainServiceNm" class="control-label col-lg-4" style="padding:0px 15px 0px 1px !important;">Main Service:</label>
                                    <div class="input-group col-lg-8" style="padding:0px 15px 0px 1px !important;">
                                        <?php if ($canEdtPrvdrGroup === true) { ?>
                                        <input type="text" class="form-control rqrdFld" aria-label="..." id="onePrvdrGroupDetMainServiceNm" value="<?php echo $onePrvdrGroupDetMainServiceNm; ?>" readonly="true">
                                        <input type="hidden" class="form-control" aria-label="..." id="onePrvdrGroupDetMainServiceId" value="<?php echo $onePrvdrGroupDetMainServiceId; ?>">
                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Service Types', '', '', '', 'radio', true, '', 'onePrvdrGroupDetMainServiceId', 'onePrvdrGroupDetMainServiceNm', 'clear', 0, '');">
                                            <span class="glyphicon glyphicon-th-list"></span>
                                        </label>
                                        <?php } else { ?>
                                        <span><?php echo $onePrvdrGroupDetMainServiceNm; ?></span>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                    <label for="onePrvdrGroupDetCostItmNm" class="control-label col-lg-4" style="padding:0px 15px 0px 1px !important;">Cost Item:</label>
                                    <div class="input-group col-lg-8" style="padding:0px 15px 0px 1px !important;">
                                        <?php if ($canEdtPrvdrGroup === true) { ?>
                                        <input type="text" class="form-control" aria-label="..." id="onePrvdrGroupDetCostItmNm" value="<?php echo $onePrvdrGroupDetCostItmNm; ?>" readonly="true">
                                        <input type="hidden" class="form-control" aria-label="..." id="onePrvdrGroupDetCostItmId" value="<?php echo $onePrvdrGroupDetCostItmId; ?>">
                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Inventory Services', 'gnrlOrgID', '', '', 'radio', true, '', 'onePrvdrGroupDetCostItmId', 'onePrvdrGroupDetCostItmNm', 'clear', 0, '');">
                                            <span class="glyphicon glyphicon-th-list"></span>
                                        </label>
                                        <?php } else { ?>
                                        <span><?php echo $onePrvdrGroupDetCostItmNm; ?></span>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                    <label for="onePrvdrGroupDetIsEnbld" class="control-label col-lg-4" style="padding:0px 15px 0px 1px !important;">Is Enabled:</label>
                                    <div  class="col-lg-8" style="padding:0px 15px 0px 1px !important;">
                    <?php if ($canEdtPrvdrGroup === true) { ?>
                                            <select class="form-control" id="onePrvdrGroupDetIsEnbld" >
                                            <?php
                                            $sltdYes = "";
                                            $sltdNo = "";
                                            if ($onePrvdrGroupDetIsEnbld == "1") {
                                                $sltdYes = "selected";
                                            } else if ($onePrvdrGroupDetIsEnbld == "0") {
                                                $sltdNo = "selected";
                                            }
                                            ?>
                                                <option value="1" <?php echo $sltdYes; ?>>Yes</option>
                                                <option value="0" <?php echo $sltdNo; ?>>No</option>
                                            </select>                                   
                    <?php } else {
                        ?>
                                            <span><?php echo ($onePrvdrGroupDetIsEnbld == "1") ? 'Yes' : 'No'; ?></span>
                                            <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                    <div class="row" id="allPrvdrGroupPersonsDetailInfo" style="padding:0px 15px 0px 15px !important">
                                        <?php
                                        /* &vtyp=<?php echo $vwtyp; ?> */
                                        $srchFor = "%";
                                        $srchIn = "Person Name";
                                        $pageNo = 1;
                                        $lmtSze = 10;
                                        $vwtyp = 1;
                                        if ($sbmtdPrvdrGroupID > 0) {
                                            $total = get_AllPrvdrGroupPersonsTtl($srchFor, $srchIn, $sbmtdPrvdrGroupID);
                                            //$total = get_AllPrvdrGroupsTtl($srchFor, $srchIn, $sbmtdPrvdrGroupID);
                                            if ($pageNo > ceil($total / $lmtSze)) {
                                                $pageNo = 1;
                                            } else if ($pageNo < 1) {
                                                $pageNo = ceil($total / $lmtSze);
                                            }
                                            $curIdx = $pageNo - 1;
                                            $result2 = get_AllPrvdrGroupPersons($srchFor, $srchIn, $curIdx, $lmtSze, $sbmtdPrvdrGroupID);
                                            ?>
                            <div class="row" style="padding:0px 15px 0px 15px !important">
                                <legend class="basic_person_lg1" style="color: #003245">SERVICE PROVIDERS</legend>
                                                <?php
                                                if ($canEdtPrvdrGroup === true) {
                                                    $colClassType1 = "col-lg-2";
                                                    $colClassType2 = "col-lg-3";
                                                    $colClassType3 = "col-lg-4";
                                                    $nwRowHtml = "<tr id=\"allPrvdrGroupPersonsRow__WWW123WWW\">
                                                        <td class=\"lovtd\"><span class=\"normaltd\">New</span></td>
                                                        <td class=\"lovtd\" style=\"max-width:80px !important;\">
                                                            <div class=\"form-group form-group-sm\" style=\"width:100% !important;margin-bottom:0px !important;\">
                                                                <select style=\"width:100% !important;\" data-placeholder=\"Select...\" class=\"form-control chosen-select rqrdFld\" id=\"allPrvdrGroupPersonsRow_WWW123WWW_PrvdrType\" onchange=\"onChangeOfPrvdrType(_WWW123WWW)\">";
                                                                    $valslctdArry = array("");//, "");
                                                                    $srchInsArrys = array("Staff");//, "Locum");

                                                                    for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                                        $selected = "";
                                                                        if($srchInsArrys[$z] == "Staff"){
                                                                            $selected = "selected";
                                                                        }
                                                                        $nwRowHtml .="<option value=\"$srchInsArrys[$z]\" $selected>$srchInsArrys[$z]</option>";
                                                                    } 
                                                                $nwRowHtml .="</select>
                                                                <input type=\"hidden\"  class=\"form-control\" aria-label=\"...\" id=\"allPrvdrGroupPersonsRow_WWW123WWW_PrvdrGroupSrvsPrvdrID\" value=\"-1\" style=\"width:100% !important;\">                                                                                                                          
                                                                <input type=\"hidden\"  class=\"form-control\" aria-label=\"...\" id=\"allPrvdrGroupPersonsRow_WWW123WWW_PrvdrGroupID\" value=\"$onePrvdrGroupDetID\" style=\"width:100% !important;\">                                                                                                                          
                                                            </div>                                                      
                                                        </td>
                                                        <td style=\"min-width: 270px !important;\">
                                                            <div class=\"input-group\"  style=\"width:100%;\">
                                                                <input type=\"text\" class=\"form-control rqrdFld\" aria-label=\"...\" id=\"allPrvdrGroupPersonsRow_WWW123WWW_PrvdrGroupPersonNm\" value=\"\" readonly=\"true\">
                                                                <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"allPrvdrGroupPersonsRow_WWW123WWW_PrvdrGroupPersonID\" value=\"\">
                                                                <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getServiceProviderPersonList(_WWW123WWW)\">
                                                                    <span class=\"glyphicon glyphicon-th-list\"></span>
                                                                </label>
                                                            </div>
                                                        </td>
                                                        <td class=\"lovtd\"> 
                                                            <div class=\"input-group date form_date\" data-date=\"\" data-date-format=\"dd-M-yyyy\" data-link-field=\"dtp_input2\" data-link-format=\"yyyy-mm-dd\">
                                                                <input style=\"width:100% !important;\" class=\"form-control rqrdFld\" size=\"16\" type=\"text\" id=\"allPrvdrGroupPersonsRow_WWW123WWW_StartDate\" name=\"allPrvdrGroupPersonsRow_WWW123WWW_StartDate\" value=\"\">
                                                                <!--<span class=\"input-group-addon\"><span class=\"glyphicon glyphicon-remove\"></span></span>-->
                                                                <span class=\"input-group-addon\"><span class=\"glyphicon glyphicon-calendar\"></span></span>
                                                            </div>
                                                        </td>
                                                        <td class=\"lovtd\">
                                                            <div class=\"input-group date form_date\" data-date=\"\" data-date-format=\"dd-M-yyyy\" data-link-field=\"dtp_input2\" data-link-format=\"yyyy-mm-dd\">
                                                                <input style=\"width:100% !important;\" class=\"form-control\" size=\"16\" type=\"text\" id=\"allPrvdrGroupPersonsRow_WWW123WWW_EndDate\" name=\"allPrvdrGroupPersonsRow_WWW123WWW_EndDate\" value=\"\">
                                                                <!--<span class=\"input-group-addon\"><span class=\"glyphicon glyphicon-remove\"></span></span>-->
                                                                <span class=\"input-group-addon\"><span class=\"glyphicon glyphicon-calendar\"></span></span>
                                                            </div>
                                                        </td>
                                                        <td class=\"lovtd\">
                                                            <button type=\"button\" class=\"btn btn-default\" style=\"margin: 0px !important;padding:0px 3px 2px 4px !important;\" onclick=\"delPrvdrGroupPerson('allPrvdrGroupPersonsRow__WWW123WWW');\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Delete Person\">
                                                                    <img src=\"cmn_images/no.png\" style=\"height:15px; width:auto; position: relative; vertical-align: middle;\">
                                                            </button>
                                                        </td>
                                                    </tr>";               ?>
                                    <div class="<?php echo $colClassType1; ?>" style="padding:0px 1px 0px 3px !important;">     
                                        <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="insertNewRowBe4('allPrvdrGroupPersonsTable', 0, '<?php echo urlencode($nwRowHtml); ?>');" data-toggle="tooltip" data-placement="bottom" title="Add Person">
                                            <img src="cmn_images/add1-64.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                        </button>
                                        <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="savePrvdrGroupPerson();" data-toggle="tooltip" data-placement="bottom" title="Save Person">
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
                                        <input class="form-control" id="allPrvdrGroupPersonsSrchFor" type = "text" placeholder="Search For" value="<?php
                                echo trim(str_replace("%", " ", $srchFor));
                                ?>" onkeyup="enterKeyFuncAllPrvdrGroupPersons(event, '', '#allPrvdrGroupPersonsDetailInfo', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&sbmtdPrvdrGroupID=<?php echo $sbmtdPrvdrGroupID; ?>&mdl=Clinic/Hospital');">
                                        <input id="allPrvdrGroupPersonsPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getAllPrvdrGroupPersons('clear', '#allPrvdrGroupPersonsDetailInfo', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&sbmtdPrvdrGroupID=<?php echo $sbmtdPrvdrGroupID; ?>&mdl=Clinic/Hospital');">
                                            <span class="glyphicon glyphicon-remove"></span>
                                        </label>
                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getAllPrvdrGroupPersons('', '#allPrvdrGroupPersonsDetailInfo', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&sbmtdPrvdrGroupID=<?php echo $sbmtdPrvdrGroupID; ?>&mdl=Clinic/Hospital');">
                                            <span class="glyphicon glyphicon-search"></span>
                                        </label> 
                                    </div>
                                </div>
                                <div class="<?php echo $colClassType2; ?>" style="padding:0px 3px 0px 3px !important;">
                                    <div class="input-group">
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                        <!--<select data-placeholder="Select..." class="form-control chosen-select" id="allPrvdrGroupPersonsSrchIn">
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
                                        <select data-placeholder="Select..." class="form-control chosen-select" id="allPrvdrGroupPersonsDsplySze" style="min-width:70px !important;">                            
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
                                                <a class="rhopagination" href="javascript:getAllPrvdrGroupPersons('previous', '#allPrvdrGroupPersonsDetailInfo', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&sbmtdPrvdrGroupID=<?php echo $sbmtdPrvdrGroupID; ?>&mdl=Clinic/Hospital');" aria-label="Previous">
                                                    <span aria-hidden="true">&laquo;</span>
                                                </a>
                                            </li>
                                            <li>
                                                <a class="rhopagination" href="javascript:getAllPrvdrGroupPersons('next', '#allPrvdrGroupPersonsDetailInfo', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&sbmtdPrvdrGroupID=<?php echo $sbmtdPrvdrGroupID; ?>&mdl=Clinic/Hospital');" aria-label="Next">
                                                    <span aria-hidden="true">&raquo;</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </nav>
                                    <input type="hidden" class="form-control" aria-label="..." id="gnrlOrgID" name="gnrlOrgID" value="<?php echo $orgID; ?>">                                                    
                                    <input type="hidden" class="form-control" aria-label="..." id="sbmtdPrvdrGroupID" name="sbmtdPrvdrGroupID" value="<?php echo $sbmtdPrvdrGroupID; ?>">
                                </div>
                            </div>
                            <div class="row" style="padding:0px 15px 0px 15px !important">                  
                                <div class="col-md-12" style="padding:0px 3px 0px 3px !important">
                                    <table class="table table-striped table-bordered table-responsive" id="allPrvdrGroupPersonsTable" cellspacing="0" width="100%" style="width:100%;min-width: 300px !important;">
                                        <thead>
                                            <tr>
                                                <th>No.</th>
                                                <th>Type</th>
                                                <th>Person Name</th>
                                                <th>Start Date</th>
                                                <th>End Date</th>
                                                <th>&nbsp;</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <input type="text" style="display:none !important;" id="lovOrgID" value="<?php echo $orgID; ?>"/>
                                            <?php
                                            $cntr = 0;
                                            while ($row2 = loc_db_fetch_array($result2)) {
                                                $cntr += 1;
                                                $ttlOptnsScore = 0;
                                                ?>
                                                <tr id="allPrvdrGroupPersonsRow_<?php echo $cntr; ?>">                                    
                                                    <td class="lovtd"><span><?php echo ($curIdx * $lmtSze) + ($cntr); ?></span></td>
                                                    <td class="lovtd" style="max-width:80px !important;">
                                                        <div class="form-group form-group-sm" style="width:100% !important;margin-bottom:0px !important;">
                                                            <select style="width:100% !important;" data-placeholder="Select..." class="form-control chosen-select rqrdFld" id="allPrvdrGroupPersonsRow<?php echo $cntr; ?>_PrvdrType" onchange="onChangeOfPrvdrType(<?php echo $cntr; ?>)">
                                                                <?php
                                                                $valslctdArry = array("");//, "");
                                                                $srchInsArrys = array("Staff");//, "Locum");

                                                                for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                                    if ($row2[2] == $srchInsArrys[$z]) {
                                                                        $valslctdArry[$z] = "selected";
                                                                    }
                                                                    ?>
                                                                    <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                                                <?php } ?>
                                                            </select>
                                                            <input type="hidden" class="form-control" aria-label="..." id="allPrvdrGroupPersonsRow<?php echo $cntr; ?>_PrvdrGroupSrvsPrvdrID" value="<?php echo $row2[0]; ?>" style="width:100% !important;"> 
                                                            <input type="hidden" class="form-control" aria-label="..." id="allPrvdrGroupPersonsRow<?php echo $cntr; ?>_PrvdrGroupID" value="<?php echo $row2[1]; ?>" style="width:100% !important;">
                                                        </div>
                                                    </td>                                             
                                                     <td style="min-width: 270px !important;">
                                                        <div class="input-group"  style="width:100%;">
                                                            <input type="text" class="form-control rqrdFld" aria-label="..." id="allPrvdrGroupPersonsRow<?php echo $cntr; ?>_PrvdrGroupPersonNm" value="<?php echo $row2[4]; ?>" readonly="true">
                                                            <input type="hidden" class="form-control" aria-label="..." id="allPrvdrGroupPersonsRow<?php echo $cntr; ?>_PrvdrGroupPersonID" value="<?php echo $row2[3]; ?>">
                                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getServiceProviderPersonList(<?php echo $cntr; ?>)">
                                                                <span class="glyphicon glyphicon-th-list"></span>
                                                            </label>
                                                        </div>
                                                    </td>
                                                    <td class="lovtd"> 
                                                        <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                                            <input style="width:100% !important;" class="form-control rqrdFld" size="16" type="text" id="allPrvdrGroupPersonsRow<?php echo $cntr; ?>_StartDate" name="allPrvdrGroupPersonsRow<?php echo $cntr; ?>_StartDate" value="<?php echo $row2[6]; ?>">
                                                            <!--<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>-->
                                                            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                        </div>
                                                    </td>
                                                    <td class="lovtd"> 
                                                        <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                                            <input style="width:100% !important;" class="form-control" size="16" type="text" id="allPrvdrGroupPersonsRow<?php echo $cntr; ?>_EndDate" name="allPrvdrGroupPersonsRow<?php echo $cntr; ?>_EndDate" value="<?php echo $row2[7]; ?>">
                                                            <!--<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>-->
                                                            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                        </div>
                                                    </td>	
                                                    <td class="lovtd">
                                                        <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delPrvdrGroupPerson('allPrvdrGroupPersonsRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Provider Person">
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
                } else if ($vwtyp == 2) {//2
                    $sbmtdPrvdrGroupID = isset($_POST['sbmtdPrvdrGroupID']) ? cleanInputData($_POST['sbmtdPrvdrGroupID']) : -1;
                    if ($sbmtdPrvdrGroupID > 0) {
                        $total = get_AllPrvdrGroupPersonsTtl($srchFor, $srchIn, $sbmtdPrvdrGroupID);
                        //$total = get_AllPrvdrGroupsTtl($srchFor, $srchIn, $sbmtdPrvdrGroupID);
                        if ($pageNo > ceil($total / $lmtSze)) {
                            $pageNo = 1;
                        } else if ($pageNo < 1) {
                            $pageNo = ceil($total / $lmtSze);
                        }
                        $curIdx = $pageNo - 1;
                        $result2 = get_AllPrvdrGroupPersons($srchFor, $srchIn, $curIdx, $lmtSze, $sbmtdPrvdrGroupID);
                        ?>
                        <div class="row" style="padding:0px 15px 0px 15px !important">
                            <legend class="basic_person_lg1" style="color: #003245">BRANCH SORT CODES</legend>
                        <?php
                        if ($canEdtPrvdrGroup === true) {
                            $colClassType1 = "col-lg-2";
                            $colClassType2 = "col-lg-3";
                            $colClassType3 = "col-lg-4";
                            $nwRowHtml = urlencode("<tr id=\"allPrvdrGroupPersonsRow__WWW123WWW\">"
                                    . "<td class=\"lovtd\"><span class=\"normaltd\">New</span></td>"
                                    . "<td class=\"lovtd\">
                                                <input type=\"text\" style=\"width:100% !important;\" class=\"form-control rqrdFld\" aria-label=\"...\" id=\"allPrvdrGroupPersonsRow_WWW123WWW_PersonNm\" name=\"allPrvdrGroupPersonsRow_WWW123WWW_PersonNm\" value=\"\">
                                                <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"allPrvdrGroupPersonsRow_WWW123WWW_PrvdrGroupSrvsPrvdrID\" value=\"-1\" style=\"width:100% !important;\">                                                                                                                          
                                            </td>                                             
                                            <td class=\"lovtd\">
                                                    <input type=\"text\" style=\"width:100% !important;\" class=\"form-control rqrdFld\" aria-label=\"...\" id=\"allPrvdrGroupPersonsRow_WWW123WWW_PersonSortCode\" name=\"allPrvdrGroupPersonsRow_WWW123WWW_PersonSortCode\" value=\"\">                                                               
                                            </td>
                                            <td class=\"lovtd\">
                                                <button type=\"button\" class=\"btn btn-default\" style=\"margin: 0px !important;padding:0px 3px 2px 4px !important;\" onclick=\"delPrvdrGroupPerson('allPrvdrGroupPersonsRow__WWW123WWW');\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Delete Person\">
                                                        <img src=\"cmn_images/no.png\" style=\"height:15px; width:auto; position: relative; vertical-align: middle;\">
                                                </button>
                                            </td>
                        </tr>");
                            ?> 
                                <div class="<?php echo $colClassType1; ?>" style="padding:0px 1px 0px 3px !important;">     
                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="insertNewRowBe4('allPrvdrGroupPersonsTable', 0, '<?php echo $nwRowHtml; ?>');" data-toggle="tooltip" data-placement="bottom" title="Add Person">
                                        <img src="cmn_images/add1-64.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                    </button>
                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="savePrvdrGroupPerson();" data-toggle="tooltip" data-placement="bottom" title="Save Person">
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
                                    <input class="form-control" id="allPrvdrGroupPersonsSrchFor" type = "text" placeholder="Search For" value="<?php
                            echo trim(str_replace("%", " ", $srchFor));
                            ?>" onkeyup="enterKeyFuncAllPrvdrGroupPersons(event, '', '#allPrvdrGroupPersonsDetailInfo', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&sbmtdPrvdrGroupID=<?php echo $sbmtdPrvdrGroupID; ?>&mdl=Clinic/Hospital');">
                                    <input id="allPrvdrGroupPersonsPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getAllPrvdrGroupPersons('clear', '#allPrvdrGroupPersonsDetailInfo', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&sbmtdPrvdrGroupID=<?php echo $sbmtdPrvdrGroupID; ?>&mdl=Clinic/Hospital');">
                                        <span class="glyphicon glyphicon-remove"></span>
                                    </label>
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getAllPrvdrGroupPersons('', '#allPrvdrGroupPersonsDetailInfo', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&sbmtdPrvdrGroupID=<?php echo $sbmtdPrvdrGroupID; ?>&mdl=Clinic/Hospital');">
                                        <span class="glyphicon glyphicon-search"></span>
                                    </label> 
                                </div>
                            </div>
                            <div class="<?php echo $colClassType2; ?>" style="padding:0px 3px 0px 3px !important;">
                                <div class="input-group">
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                    <!--<select data-placeholder="Select..." class="form-control chosen-select" id="allPrvdrGroupPersonsSrchIn">
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
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="allPrvdrGroupPersonsDsplySze" style="min-width:70px !important;">                            
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
                                            <a class="rhopagination" href="javascript:getAllPrvdrGroupPersons('previous', '#allPrvdrGroupPersonsDetailInfo', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo "2"; ?>&sbmtdPrvdrGroupID=<?php echo $sbmtdPrvdrGroupID; ?>&mdl=Clinic/Hospital');" aria-label="Previous">
                                                <span aria-hidden="true">&laquo;</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a class="rhopagination" href="javascript:getAllPrvdrGroupPersons('next', '#allPrvdrGroupPersonsDetailInfo', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo "2"; ?>&sbmtdPrvdrGroupID=<?php echo $sbmtdPrvdrGroupID; ?>&mdl=Clinic/Hospital');" aria-label="Next">
                                                <span aria-hidden="true">&raquo;</span>
                                            </a>
                                        </li>
                                    </ul>
                                </nav>
                                <input type="hidden" class="form-control" aria-label="..." id="gnrlOrgID" name="gnrlOrgID" value="<?php echo $orgID; ?>">                         
                                <input type="hidden" class="form-control" aria-label="..." id="sbmtdPrvdrGroupID" name="sbmtdPrvdrGroupID" value="<?php echo $sbmtdPrvdrGroupID; ?>">
                            </div>
                        </div>
                        <div class="row" style="padding:0px 15px 0px 15px !important">                  
                            <div class="col-md-12" style="padding:0px 3px 0px 3px !important">
                                <table class="table table-striped table-bordered table-responsive" id="allPrvdrGroupPersonsTable" cellspacing="0" width="100%" style="width:100%;min-width: 300px !important;">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Type</th>
                                            <th>Person Name</th>
                                            <th>Start Date</th>
                                            <th>End Date</th>
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
                                            <tr id="allPrvdrGroupPersonsRow_<?php echo $cntr; ?>">                                    
                                                <td class="lovtd"><span><?php echo ($curIdx * $lmtSze) + ($cntr); ?></span></td>
                                                <td class="lovtd" style="max-width:80px !important;">
                                                    <div class="form-group form-group-sm" style="width:100% !important;margin-bottom:0px !important;">
                                                        <select style="width:100% !important;" data-placeholder="Select..." class="form-control chosen-select rqrdFld" id="allPrvdrGroupPersonsRow<?php echo $cntr; ?>_PrvdrType">
                                                            <?php
                                                            $valslctdArry = array("");//, "");
                                                            $srchInsArrys = array("Staff");//, "Locum");

                                                            for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                                if ($row2[2] == $srchInsArrys[$z]) {
                                                                    $valslctdArry[$z] = "selected";
                                                                }
                                                                ?>
                                                                <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                        <input type="hidden" class="form-control" aria-label="..." id="allPrvdrGroupPersonsRow<?php echo $cntr; ?>_PrvdrGroupSrvsPrvdrID" value="<?php echo $row2[0]; ?>" style="width:100% !important;"> 
                                                        <input type="hidden" class="form-control" aria-label="..." id="allPrvdrGroupPersonsRow<?php echo $cntr; ?>_PrvdrGroupID" value="<?php echo $row2[1]; ?>" style="width:100% !important;">
                                                    </div>
                                                </td> 
                                                <td
                                                <td style="min-width: 270px !important;">
                                                    <div class="input-group"  style="width:100%;">
                                                        <input type="text" class="form-control rqrdFld" aria-label="..." id="allPrvdrGroupPersonsRow<?php echo $cntr; ?>_PrvdrGroupPersonNm" value="<?php echo $row2[4]; ?>" readonly="true">
                                                        <input type="hidden" class="form-control" aria-label="..." id="allPrvdrGroupPersonsRow<?php echo $cntr; ?>_PrvdrGroupPersonID" value="<?php echo $row2[3]; ?>">
                                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Investigation Types', '', '', '', 'radio', true, '', 'allPrvdrGroupPersonsRow<?php echo $cntr; ?>_PrvdrGroupPersonID', 'allPrvdrGroupPersonsRow<?php echo $cntr; ?>_PrvdrGroupPersonNm', 'clear', 0, '');">
                                                            <span class="glyphicon glyphicon-th-list"></span>
                                                        </label>
                                                    </div>
                                                </td>
                                                <td class="lovtd"> 
                                                    <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                                        <input style="width:100% !important;" class="form-control rqrdFld" size="16" type="text" id="allPrvdrGroupPersonsRow<?php echo $cntr; ?>_StartDate" name="allPrvdrGroupPersonsRow<?php echo $cntr; ?>_StartDate" value="<?php echo $row2[6]; ?>">
                                                        <!--<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>-->
                                                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                    </div>
                                                </td>
                                                <td class="lovtd"> 
                                                    <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                                        <input style="width:100% !important;" class="form-control" size="16" type="text" id="allPrvdrGroupPersonsRow<?php echo $cntr; ?>_EndDate" name="allPrvdrGroupPersonsRow<?php echo $cntr; ?>_EndDate" value="<?php echo $row2[7]; ?>">
                                                        <!--<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>-->
                                                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                    </div>
                                                </td>
                                                <td class="lovtd">
                                                    <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delPrvdrGroupPerson('allPrvdrGroupPersonsRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Group">
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
    }
}