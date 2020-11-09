<?php
if (array_key_exists('lgn_num', get_defined_vars())) {
    $canAddPrsn = true;//test_prmssns($dfltPrvldgs[13], $mdlNm);
    $canEdtTmplts = true; //test_prmssns($dfltPrvldgs[14], $mdlNm);
    $canDelPrsn = true; //test_prmssns($dfltPrvldgs[15], $mdlNm);
    $orgID = $_SESSION['ORG_ID'];
    
    //var_dump($_POST);

    if ($qstr == "UPDATE") {
        if ($actyp == 1) {
        //COLUMN SETUP
        $slctdExtrDataCols = isset($_POST['slctdExtrDataCols']) ? cleanInputData($_POST['slctdExtrDataCols']) : '';
        $srcType = isset($_POST['srcType']) ? cleanInputData($_POST['srcType']) : '';
        if (trim($slctdExtrDataCols, "|~") != "") {
            //Save Persons
            $variousRows = explode("|", trim($slctdExtrDataCols, "|"));
            for ($z = 0; $z < count($variousRows); $z++) {
                $crntRow = explode("~", $variousRows[$z]);
                if (count($crntRow) == 12) {
                    $colno = (int) (cleanInputData1($crntRow[0]));
                    $extrdataID = (int) (cleanInputData1($crntRow[1]));
                    $collabel = trim(cleanInputData1($crntRow[2]));
                    $lovnm = cleanInputData1($crntRow[3]);
                    $datatyp = cleanInputData1($crntRow[4]);
                    $catgry = cleanInputData1($crntRow[5]);
                    $lngth = (int) (cleanInputData1($crntRow[6]));
                    $dsplytyp = cleanInputData1($crntRow[7]);
                    $tblrnumcols = (int) (cleanInputData1($crntRow[8]));
                    $ordr = (int) (cleanInputData1($crntRow[9]));
                    $csvTblColNms = cleanInputData1($crntRow[10]);
                    $isrqrd = cleanInputData1($crntRow[11]) == "YES" ? TRUE : FALSE;
                    //$oldExtrdataID = getGnrlRecID("hosp.hosp_extra_data_cols", "''||column_no", "extra_data_cols_id", $colno, $orgID);
                    $result = executeSQLNoParams("SELECT extra_data_cols_id FROM hosp.hosp_extra_data_cols WHERE "
                            . " column_no = $colno AND src_type = '" . $srcType . "' AND org_id = $orgID");
                    $oldExtrdataID = -1;
                    while ($row = loc_db_fetch_array($result)) {
                        $oldExtrdataID = $row[0];
                    }

                    if ($oldExtrdataID <= 0) {
                        //Insert
                        if ($dsplytyp == "Tabular") {
                            $dsplytyp = "T";
                        } else {
                            $dsplytyp = "D";
                        }
                        if ($collabel != "") {
                            createHospExtrDataCol($colno, $collabel, $lovnm, $datatyp, $catgry, $lngth, $dsplytyp, $orgID, $tblrnumcols, $ordr, $csvTblColNms, $isrqrd, $srcType);
                        }
                    } else {
                        if ($dsplytyp == "Tabular") {
                            $dsplytyp = "T";
                        } else {
                            $dsplytyp = "D";
                        }
                        updateHospExtrDataCol($colno, $collabel, $lovnm, $datatyp, $catgry, $lngth, $dsplytyp, $orgID, $tblrnumcols, $oldExtrdataID, $ordr, $csvTblColNms, $isrqrd, $srcType);
                    }
                }
            }
            ?>
            <div class="container-fluid"  style="float:none;width:100%;text-align: center;padding:0px 0px 0px 25px !important;">
                <div class="row" style="float:none;width:100%;text-align: center;">
                    <span style="color:green;font-weight:bold;font-size:16px;font-style: italic;font-family: Georgia;width:100%;text-align: center;">Additional Service Data Setup Saved Successfully!</span>
                </div>
            </div>
            <?php
        } else {
            ?>
            <div class="container-fluid"  style="float:none;width:100%;text-align: center;padding:0px 0px 0px 25px !important;">
                <div class="row" style="float:none;width:100%;text-align: center;">
                    <span style="color:red;font-weight:bold;font-size:16px;font-style: italic;font-family: Georgia;width:100%;text-align: center;">Failed to Save Additional Service Data Setup!</span>
                </div>
            </div>
            <?php
        }
    }
        else if ($actyp == 4) {            
            $addtnlPrsPkey = isset($_POST['addtnlSrvsPkey']) ? (float) cleanInputData($_POST['addtnlSrvsPkey']) : -1;
            $appntmntID = isset($_POST['appntmntID']) ? (float) cleanInputData($_POST['appntmntID']) : -1;
            $extDtColNum = isset($_POST['extDtColNum']) ? (int) cleanInputData($_POST['extDtColNum']) : -1;
            $pipeSprtdFieldIDs = isset($_POST['allTblValues']) ? cleanInputData($_POST['allTblValues']) : -1;
            //var_dump($_POST);
            echo "<button onclick=\"$('#myFormsModalH').modal('hide');\">Close</button>";
        }
    } else if ($qstr == "DELETE") {
        if ($actyp == 1) {
            $colNum = isset($_POST['colNum']) ? (int) (cleanInputData($_POST['colNum'])) : -1;
            $extrdataID = isset($_POST['extrdataID']) ? (int) (cleanInputData($_POST['extrdataID'])) : -1;
            echo deleteHospExtrDataCol($extrdataID, $colNum);
        }
    }  
    else if ($qstr == "ADTNL-DATA-FORM") {
        
        $formType = isset($_POST['formType']) ? cleanInputData($_POST['formType']) : '';
        $rvsnTtlAPD = isset($_POST['rvsnTtlAPD']) ? cleanInputData($_POST['rvsnTtlAPD']) : 0;
        $pkID = isset($_POST['appntmntID']) ? cleanInputData($_POST['appntmntID']) : -1;
        $srcRcmddSrvsID = isset($_POST['srcRcmddSrvsID']) ? cleanInputData($_POST['srcRcmddSrvsID']) : -1;
        
        //$trnsStatus = getGnrlRecNm("hosp.appntmnt", "appntmnt_id", "appntmnt_status", $pkID);
        //echo "Here";
        if($vwtyp == 1){
            /* ADDITIONAL DATA DATA */
            $mkReadOnlyAsd = "";
            $mkReadOnlyAsdDsbld = "";
            $trnsStatus = "Incomplete";     
            $dsplyOthrElmnts = "";
            
            $trnsStatus = getAppntmntStatus($pkID);
            $appntmntNo = getGnrlRecNm("hosp.appntmnt", "appntmnt_id", "appntmnt_no", $pkID);
            
            //echo "trnsStatus addtnl_data = ".$trnsStatus;

            $dsplyMode = "VIEW";
            if (1 == 1){ //(($canAddPrsn === true && $addOrEdit == "ADD") || ($canEdtPrsn === true && $addOrEdit == "EDIT")) {
                $dsplyMode = $vwtypActn; //$addOrEdit;
            }
            if ($pkID > 0) {
                $result = get_SrvsExtrDataGrps($orgID, $formType);
                ?>               
                <form class="form-horizontal" id="adtnlSrvsDataForm">
                    <input type="text" id="formTypeInpt" value="<?php echo $formType; ?>" style="display:none !important">
                    <?php if($srcRcmddSrvsID < 0) { ?>
                    <?php if (!($trnsStatus == "Completed" || $vwtypActn == "VIEW" || $trnsStatus == "Scheduled"  || $trnsStatus == "Cancelled")) { ?>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="col-md-6">
                                <div class="" style="float:left;">
                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;margin-top:5px;" onclick="saveAddtnlSrvsData(<?php echo $pkID; ?>, '<?php echo $formType; ?>');" style="width:100% !important;">
                                        <img src="cmn_images/FloppyDisk.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                        Save Data
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="" style="float:right;">
                                    <button type="button" class="btn btn-default" data-toggle="tooltip" style="margin-bottom: 5px; <?php echo $dsplyOthrElmnts; ?>" data-placement="bottom" title="View Appoitment Items" onclick="getOneAppntmntDataItemsForm(<?php echo $pkID; ?>, 3, 'ShowDialog', '<?php echo $appntmntNo; ?>', '<?php echo $trnsStatus; ?>');" style="padding:2px !important;" style="padding:2px !important;">
                                       <img src="cmn_images/chcklst3.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                    </button>
                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px; <?php echo $dsplyOthrElmnts; ?>" onclick="getOneScmSalesInvcForm(-1, 3, 'ShowDialog', 'Sales Invoice', 'NO', 'SALES',-1, <?php echo $pkID; ?>);" style="">
                                        <img src="cmn_images/sale.jpg" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                        Invoice
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                     <?php } ?>
                    <?php } ?>
                    <?php while ($row = loc_db_fetch_array($result)) {

                        
                        //echo $trnsStatus;

                        if($vwtypActn == "VIEW" || $trnsStatus == "Completed" || $trnsStatus == "Scheduled" || $trnsStatus == "Cancelled"){
                            $mkReadOnlyAsd = "readonly=\"readonly\"";
                            $mkReadOnlyAsdDsbld = "disabled=\"true\"";
                        }                          

                        ?>
                        <div class="row">
                            <div class="col-md-12">
                                <fieldset class="basic_person_fs4">
                                    <legend class="basic_person_lg"><?php echo $row[0]; ?></legend>
                                    <?php
                                    $result1 = get_SrvsExtrDataGrpCols($row[0], $orgID, $formType);
                                    $cntr1 = 0;
                                    $gcntr1 = 0;
                                    $cntr1Ttl = loc_db_num_rows($result1);
                                    while ($row1 = loc_db_fetch_array($result1)) {
                                        /* POSSIBLE FIELDS
                                         * label
                                         * textbox (for now only this)
                                         * textarea (for now only this)
                                         * readonly textbox with button
                                         * readonly textbox with date
                                         * textbox with number validation
                                         */
                                        if ($row1[7] == "Tabular") {
                                            $vrsFieldIDs = "";
                                            for ($i = 0; $i < $row1[9]; $i++) {
                                                if ($i == $row1[9] - 1) {
                                                    $vrsFieldIDs .= "srvsExtrTblrDtCol_" . $i;
                                                } else {
                                                    $vrsFieldIDs .= "srvsExtrTblrDtCol_" . $i . "|";
                                                }
                                            }
                                            $fldVal = get_SrvsExtrData($pkID, $formType, $row1[1], $rvsnTtlAPD);
                                            ?>
                                            <div class="row">
                                                <div  class="col-md-12">
                                                    <?php if(!($trnsStatus == "Completed" || $vwtypActn == "VIEW" || $trnsStatus == "Scheduled" || $trnsStatus == "Cancelled")) { ?>
                                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="getSrvsAddtnlDataForm('myFormsModalH', 'myFormsModalHBody', 'myFormsModalHTitle', 'addtnlSrvsTblrDataForm', '', 'Add/Edit Data', 12, 'ADD', -1, '<?php echo $vrsFieldIDs; ?>', <?php echo $row1[1]; ?>, 'extDataTblCol_<?php echo $row1[1]; ?>', '<?php echo $formType; ?>');">
                                                        <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                        Add Data
                                                    </button>
                                                    <?php } ?>
                                                    <input class="form-control" id="addtnlSrvsDataCol<?php echo $row1[1]; ?>" type = "hidden" placeholder="" value="<?php echo $fldVal; ?>"/>
                                                    <table id="extDataTblCol_<?php echo $row1[1]; ?>" class="table table-striped table-bordered table-responsive extPrsnDataTblEDT"  cellspacing="0" width="100%" style="width:100%;">
                                                        <thead><th>&nbsp;&nbsp;...</th>
                                                        <?php
                                                        $fieldHdngs = $row1[11];
                                                        $arry1 = explode(",", $fieldHdngs);
                                                        $cntr = count($arry1);
                                                        for ($i = 0; $i < $row1[9]; $i++) {
                                                            if ($i <= $cntr - 1) {
                                                                ?>
                                                                <th><?php echo $arry1[$i]; ?></th>
                                                                <?php
                                                            } else {
                                                                ?>
                                                                <th>&nbsp;</th>
                                                                <?php
                                                            }
                                                        }
                                                        ?>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $arry3 = explode("|", $fldVal);
                                                            $cntr3 = count($arry3);
                                                            $maxsze = (int) 320 / $row1[9];
                                                            if ($maxsze > 100 || $maxsze < 80) {
                                                                $maxsze = 100;
                                                            }
                                                            for ($j = 0; $j < $cntr3; $j++) {
                                                                if (trim(str_replace("~", "", $arry3[$j])) == "") {
                                                                    continue;
                                                                }
                                                                ?>
                                                                <tr id="srvsExtrTblrDtCol_<?php echo $row1[1]; ?>_Row<?php echo $j; ?>">
                                                                    <td>
                                                                        <?php if (!($trnsStatus == "Completed" || $vwtypActn == "VIEW" || $trnsStatus == "Scheduled" || $trnsStatus == "Cancelled")){ ?>
                                                                        <button type="button" class="btn btn-default btn-sm" onclick="getSrvsAddtnlDataForm('myFormsModalH', 'myFormsModalHBody', 'myFormsModalHTitle', 'addtnlSrvsTblrDataForm', 'srvsExtrTblrDtCol_<?php echo $row1[1]; ?>_Row<?php echo $j; ?>', 'Add/Edit Data', 12, 'EDIT', <?php echo $pkID; ?>, '<?php echo $vrsFieldIDs; ?>', <?php echo $row1[1]; ?>, 'extDataTblCol_<?php echo $row1[1]; ?>', '<?php echo $formType; ?>');" style="padding:2px !important;">
                                                                            <!--<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>-->
                                                                            <img src="cmn_images/edit32.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                        </button>
                                                                        <?php } ?>
                                                                    </td>
                                                                    <?php
                                                                    $arry2 = explode("~", $arry3[$j]);
                                                                    $cntr2 = count($arry2);
                                                                    for ($i = 0; $i < $row1[9]; $i++) {
                                                                        if ($i <= $cntr2 - 1) {
                                                                            ?>
                                                                            <td><?php echo $arry2[$i]; ?></td>
                                                                        <?php } else { ?>
                                                                            <td>&nbsp;</td>
                                                                            <?php
                                                                        }
                                                                    }
                                                                    ?>
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
                                            if ($gcntr1 == 0) {
                                                $gcntr1 += 1;
                                            }
                                            if (($cntr1 % 2) == 0) {
                                                ?> 
                                                <div class="row"> 
                                                    <?php
                                                }
                                                ?>
                                                <div class="col-md-6"> 
                                                    <div class="form-group form-group-sm"> 
                                                        <?php 
                                                        $prsnDValPulld = get_SrvsExtrData($pkID, $formType, $row1[1], $rvsnTtlAPD);

                                                        ?>
                                                        <label class="control-label col-md-4"><?php echo $row1[2]; ?>:</label>
                                                        <div  class="col-md-8">
                                                            <?php
                                                            //$prsnDValPulld = "";
                                                            //$prsnDValPulld = get_SrvsExtrData($pkID, $formType, $row1[1], $rvsnTtlAPD);
                                                            if ($row1[4] == "Date") {
                                                                ?>                                                        
                                                                <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                                                    <input <?php echo $mkReadOnlyAsd; ?> class="form-control" size="16" type="text" id="addtnlSrvsDataCol<?php echo $row1[1]; ?>" value="<?php echo $prsnDValPulld; ?>" readonly="">
                                                                    <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                                </div>
                                                                <?php
                                                            } else if ($row1[4] == "Number") {
                                                                ?>
                                                                <input <?php echo $mkReadOnlyAsd; ?> class="form-control" id="addtnlSrvsDataCol<?php echo $row1[1]; ?>" type = "text" placeholder="" value="<?php echo $prsnDValPulld; ?>"/>
                                                                <?php
                                                            } else {
                                                                if ($row1[3] == "") {
                                                                    if ($row1[6] < 200) {
                                                                        ?>
                                                                        <input <?php echo $mkReadOnlyAsd; ?> class="form-control" id="addtnlSrvsDataCol<?php echo $row1[1]; ?>" type = "text" placeholder="" value="<?php echo $prsnDValPulld; ?>"/>
                                                                        <?php
                                                                    } else {
                                                                        ?>
                                                                        <textarea <?php echo $mkReadOnlyAsd; ?> class="form-control" id="addtnlSrvsDataCol<?php echo $row1[1]; ?>" cols="2" placeholder="" rows="2"><?php echo $prsnDValPulld; ?></textarea>
                                                                        <?php
                                                                    }
                                                                } else {
                                                                    if ($row1[6] < 200) {
                                                                        ?>
                                                                        <div class="input-group">
                                                                            <input <?php echo $mkReadOnlyAsd; ?> type="text" class="form-control" aria-label="..." id="addtnlSrvsDataCol<?php echo $row1[1]; ?>" value="<?php echo $prsnDValPulld; ?>">  
                                                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', '<?php echo $row1[3]; ?>', '', '', '', 'radio', true, '<?php echo $prsnDValPulld; ?>', 'valueElmntID', 'addtnlSrvsDataCol<?php echo $row1[1]; ?>', 'clear', 1, '');">
                                                                                <span class="glyphicon glyphicon-th-list"></span>                                                                            
                                                                            </label>
                                                                        </div>
                                                                        <?php
                                                                    } else {
                                                                        ?>
                                                                        <div class="input-group">
                                                                            <textarea <?php echo $mkReadOnlyAsd; ?> class="form-control" id="addtnlSrvsDataCol<?php echo $row1[1]; ?>" cols="2" placeholder="" rows="2"><?php echo $prsnDValPulld; ?></textarea>
                                                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', '<?php echo $row1[3]; ?>', '', '', '', 'radio', true, '<?php echo $prsnDValPulld; ?>', 'valueElmntID', 'addtnlSrvsDataCol<?php echo $row1[1]; ?>', 'clear', 1, '');">
                                                                                <span class="glyphicon glyphicon-th-list"></span>                                                                            
                                                                            </label>
                                                                        </div>                                                                    
                                                                        <?php
                                                                    }
                                                                }
                                                            }
                                                            ?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php
                                                $cntr1 += 1;
                                                if (($cntr1 % 2) == 0 || $cntr1 == ($cntr1Ttl)) {
                                                    $cntr1 = 0;
                                                    ?>
                                                </div>
                                                <?php
                                            }
                                        }
                                    }
                                    if ($gcntr1 == 1) {
                                        $gcntr1 = 0;
                                    }
                                    ?>
                                </fieldset>
                            </div>
                            <?php ?>
                        </div>
                        <?php
                    }
                }
                ?>
            </form>
            <?php  
        } else if ($vwtyp == "12") {
            /* Add Extra Data Form */
            $addtnlSrvsPkey = isset($_POST['addtnlSrvsPkey']) ? cleanInputData($_POST['addtnlSrvsPkey']) : -1;
            $extDtColNum = isset($_POST['extDtColNum']) ? cleanInputData($_POST['extDtColNum']) : -1;
            $pipeSprtdFieldIDs = isset($_POST['pipeSprtdFieldIDs']) ? cleanInputData($_POST['pipeSprtdFieldIDs']) : "";
            $tableElmntID = isset($_POST['tableElmntID']) ? cleanInputData($_POST['tableElmntID']) : "";
            $tRowElementID = isset($_POST['tRowElementID']) ? cleanInputData($_POST['tRowElementID']) : "";
            $addOrEdit = isset($_POST['addOrEdit']) ? cleanInputData($_POST['addOrEdit']) : "";
            $formType = isset($_POST['formType']) ? cleanInputData($_POST['formType']) : "";
            //echo "extDtColNum=>".$extDtColNum."formType=>".$formType;
            $result1 = get_SrvsExtrDataGrpCols1($extDtColNum, $orgID, $formType);
            ?>
            <form class="form-horizontal" id="addtnlSrvsTblrDataForm" style="padding:5px 20px 5px 20px;">
                <div class="row">  
                    <?php
                    while ($row1 = loc_db_fetch_array($result1)) {
                        $fieldHdngs = $row1[11];
                        $arry1 = explode(",", $fieldHdngs);
                        $cntr = count($arry1);
                        for ($i = 0; $i < $row1[9]; $i++) {
                            if ($i <= $cntr - 1) {
                                ?>
                                <div class="form-group form-group-sm">
                                    <label for="srvsExtrTblrDtCol_<?php echo $i; ?>" class="control-label col-md-4"><?php echo $arry1[$i]; ?>:</label>
                                    <div class="col-md-8">
                                        <input class="form-control" id="srvsExtrTblrDtCol_<?php echo $i; ?>" type = "text" placeholder="" value=""/>
                                    </div>
                                </div>
                                <?php
                            } else {
                                ?>
                                <div class="form-group form-group-sm">
                                    <label for="srvsExtrTblrDtCol_<?php echo $i; ?>" class="control-label col-md-4">&nbsp;:</label>
                                    <div class="col-md-8">
                                        <input class="form-control" id="srvsExtrTblrDtCol_<?php echo $i; ?>" type = "text" placeholder="" value=""/>
                                    </div>
                                </div>
                                <?php
                            }
                        }
                    }
                    ?>
                </div>
                <div class="row" style="float:right;padding-right: 1px;">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="saveSrvsAddtnlDataForm('myFormsModalHBody', '<?php echo $addtnlSrvsPkey; ?>', '<?php echo $pipeSprtdFieldIDs; ?>',<?php echo $extDtColNum; ?>, '<?php echo $tableElmntID; ?>', '<?php echo $tRowElementID; ?>', '<?php echo $addOrEdit; ?>','<?php echo $formType; ?>');">Save Changes</button>
                </div>
            </form>
            <?php
        } 
    }  else {
        if ($vwtyp == "0") {

            $srcType = isset($_POST['srcType']) ? cleanInputData($_POST['srcType']) : "";
            $srvsTypeId = isset($_POST['srvsTypeId']) ? cleanInputData($_POST['srvsTypeId']) : -1;
            $srvsTypeNm = getGnrlRecNm("hosp.srvs_types", "type_id", "type_name", $srvsTypeId);

            echo $cntent . "<li onclick=\"openATab('#allmodules', 'grp=14&typ=1&pg=4&vtyp=0&mdl=Clinic/Hospital');\">
                                        <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                        <span style=\"text-decoration:none;\">Service Types</span>
                                    </li>
                                    <li onclick=\"openATab('#allmodules', 'grp=$group&typ=$type&pg=$pgNo&vtyp=0&mdl=Clinic/Hospital&srvsTypeId=$srvsTypeId&srcType=$srcType');\">
                                        <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                        <span style=\"text-decoration:none;\">Addtitional Service Data</span>
                                    </li>
                                   </ul>
                                  </div>";



            $result = get_AllSrvsExtrDataCols($orgID, $srcType);
            $cntr = 0;
            $curIdx = 0;
            ?>
            <form id='extrDtColsForm' action='' method='post' accept-charset='UTF-8'>
                <div class="row" style="margin: 0px 0px 5px 0px !important;">
                    <div style="col-md-12" style="padding:0px 0px 0px 0px">
                        <div class="col-md-6" style="padding:0px 1px 0px 1px !important;float:left !important;">
                            <div class="form-group form-group-sm">
                                <label for="formSrcType" class="control-label col-md-3">Service Type:</label>
                                <div  class="col-md-9">
                                    <input type="hidden" class="form-control" aria-label="..." id="formSrcType" value="<?php echo $srcType; ?>" style="width:100% !important;" readonly>
                                    <span style="font-weight:bold;font-size:14px !important;"><?php echo $srcType . " (" . $srvsTypeNm . ")"; ?></span>
                                    <!--<select class="form-control rqrdFld" id="formSrcType" onchange="loadSrvsExtrData();">
                                        <option value="--Please Select--">--Please Select--</option>
                                    <?php
                                    $brghtStr = "";
                                    $isDynmyc = FALSE;
                                    $titleRslt = getLovValues("%", "Both", 0, 100, $brghtStr, getLovID("MCF Customer Form Types"), $isDynmyc, -1, "", "");
                                    while ($titleRow = loc_db_fetch_array($titleRslt)) {
                                        $selectedTxt = "";
                                        if ($srcType == $titleRow[0]) {
                                            $selectedTxt = "selected=\"selected\"";
                                        }
                                        ?>
                                                <option value="<?php echo $titleRow[0]; ?>" <?php echo $selectedTxt; ?>><?php echo $titleRow[0]; ?></option>
                                        <?php
                                    }
                                    ?>
                                    </select>-->
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6" style="padding:0px 1px 0px 1px !important;float:right !important;">
                            <button type="button" class="btn btn-default btn-sm" style="" onclick="saveSrvsExtrDataCol('#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=0&mdl=Clinic/Hospital&srvsTypeId=<?php echo $srvsTypeId; ?>&srcType=<?php echo $srcType; ?>');"><img src="cmn_images/FloppyDisk.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">SAVE</button>
                            <button type="button" class="btn btn-default btn-sm" style="" onclick="openATab('#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=0&mdl=Clinic/Hospital&srvsTypeId=<?php echo $srvsTypeId; ?>&srcType=<?php echo $srcType; ?>');"><img src="cmn_images/reload.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">REFRESH</button>
                        </div>
                    </div>
                </div>
                <div class="row"> 
                    <div  class="col-md-12">
                        <div class="dataTables_scroll">
                            <table class="table table-striped table-bordered table-responsive" id="extrSrvsDtColsTable" cellspacing="0" width="100%" style="width:100%;min-width: 1700px !important;">
                                <thead>
                                    <tr>
                                        <th style="max-width: 35px !important;width: 30px !important;">Col. No.</th>
                                        <?php if ($canEdtTmplts === true) { ?>
                                            <th style="max-width: 35px !important;width: 30px !important;">&nbsp;</th>
                                        <?php } ?>  
                                        <th style="min-width: 160px !important;">Field Label</th>
                                        <th>LOV Name</th>
                                        <th>Data Type</th>
                                        <th style="min-width: 160px !important;">Category</th>
                                        <th style="max-width: 65px !important;width: 60px !important;">Data Length</th>
                                        <th>Display Type</th>
                                        <th style="max-width: 75px !important;width: 70px !important;">No. of Columns for Tabular</th>
                                        <th style="max-width: 65px !important;width: 60px !important;">Order</th>
                                        <th style="min-width: 200px !important;">Tabular Display Col Comma Separated Names</th>
                                        <th style="max-width: 65px !important;width: 60px !important;">Required?</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $rwExsts = ($row = loc_db_fetch_array($result));
                                    while ($cntr < 50) {
                                        $cntr += 1;

                                        $datacount = loc_db_num_rows($result);
                                        $jIdx = 0;
                                        if ($datacount > 0 && $jIdx < $datacount && $rwExsts == true) {
                                            if ($row[1] == $cntr) {
                                                ?>
                                                <tr id="extrSrvsDtColsRow_<?php echo $cntr; ?>">
                                                    <td class="lovtd">
                                                        <?php if ($canEdtTmplts === true) { ?>
                                                            <div class="form-group form-group-sm" style="width:100% !important;">
                                                                <span><?php echo $row[1]; ?></span>
                                                                <input type="hidden" class="form-control" aria-label="..." id="extrSrvsDtColsRow<?php echo $cntr; ?>_ColNum" value="<?php echo $row[1]; ?>" style="width:100% !important;">
                                                                <input type="hidden" class="form-control" aria-label="..." id="extrSrvsDtColsRow<?php echo $cntr; ?>_ExtrDtID" value="<?php echo $row[0]; ?>">
                                                            </div>
                                                        <?php } else { ?>
                                                            <span class=""><?php echo $row[1]; ?></span>
                                                        <?php } ?>  
                                                    </td>    
                                                    <?php if ($canEdtTmplts === true) { ?>
                                                        <td class="lovtd">
                                                            <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delSrvsExtrDataCol('extrSrvsDtColsRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Field/Column">
                                                                <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                            </button>
                                                        </td>
                                                    <?php } ?>  
                                                    <td class="lovtd">
                                                        <?php if ($canEdtTmplts === true) { ?>
                                                            <div class="form-group form-group-sm" style="width:100% !important;">
                                                                <input type="text" class="form-control" aria-label="..." id="extrSrvsDtColsRow<?php echo $cntr; ?>_FieldLbl" value="<?php echo $row[2]; ?>" style="width:100% !important;">
                                                            </div>
                                                        <?php } else { ?>
                                                            <span class=""><?php echo $row[2]; ?></span>
                                                        <?php } ?>                                                         
                                                    </td>
                                                    <td class="lovtd">
                                                        <?php if ($canEdtTmplts === true) { ?>
                                                            <div class="form-group form-group-sm" style="width:100% !important;">
                                                                <div class="input-group"  style="width:100%;">
                                                                    <input type="text" class="form-control" aria-label="..." id="extrSrvsDtColsRow<?php echo $cntr; ?>_LovNm" value="<?php echo $row[3]; ?>">
                                                                    <input type="hidden" class="form-control" aria-label="..." id="extrSrvsDtColsRow<?php echo $cntr; ?>_LovID" value="-1">
                                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'LOV Names', '', '', '', 'radio', true, '<?php echo $row[3]; ?>', 'extrSrvsDtColsRow<?php echo $cntr; ?>_LovID', 'extrSrvsDtColsRow<?php echo $cntr; ?>_LovNm', 'clear', 0, '');">
                                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        <?php } else { ?>
                                                            <span class=""><?php echo $row[3]; ?></span>
                                                        <?php } ?>                                                         
                                                    </td>
                                                    <td class="lovtd">
                                                        <?php if ($canEdtTmplts === true) { ?>
                                                            <select data-placeholder="Select..." class="form-control chosen-select" id="extrSrvsDtColsRow<?php echo $cntr; ?>_DtTyp" name="extrSrvsDtColsRow<?php echo $cntr; ?>_DtTyp">
                                                                <?php
                                                                $valslctdArry = array("", "", "");
                                                                $srchInsArrys = array("Text", "Number", "Date");
                                                                for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                                    if ($row[4] == $srchInsArrys[$z]) {
                                                                        $valslctdArry[$z] = "selected";
                                                                    }
                                                                    ?>
                                                                    <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                                                <?php } ?>
                                                            </select>
                                                        <?php } else { ?>
                                                            <span class=""><?php echo $row[4]; ?></span>
                                                        <?php } ?>                                                         
                                                    </td>
                                                    <td class="lovtd">
                                                        <?php if ($canEdtTmplts === true) { ?>
                                                            <div class="form-group form-group-sm" style="width:100% !important;">
                                                                <input type="text" class="form-control" aria-label="..." id="extrSrvsDtColsRow<?php echo $cntr; ?>_Ctgry" value="<?php echo $row[5]; ?>" style="width:100% !important;">
                                                            </div>
                                                        <?php } else { ?>
                                                            <span class=""><?php echo $row[5]; ?></span>
                                                        <?php } ?>                                                         
                                                    </td>
                                                    <td class="lovtd">
                                                        <?php if ($canEdtTmplts === true) { ?>
                                                            <div class="form-group form-group-sm" style="width:100% !important;">
                                                                <input type="number" min="1" max="9999" class="form-control" aria-label="..." id="extrSrvsDtColsRow<?php echo $cntr; ?>_DtLen" name="extrSrvsDtColsRow<?php echo $cntr; ?>_DtLen" value="<?php echo $row[6]; ?>" style="width:100%;">
                                                            </div>
                                                        <?php } else { ?>
                                                            <span class=""><?php echo $row[6]; ?></span>
                                                        <?php } ?>                                                         
                                                    </td>
                                                    <td class="lovtd">
                                                        <?php if ($canEdtTmplts === true) { ?>
                                                            <select data-placeholder="Select..." class="form-control chosen-select" id="extrSrvsDtColsRow<?php echo $cntr; ?>_DspTyp" name="extrSrvsDtColsRow<?php echo $cntr; ?>_DspTyp">
                                                                <?php
                                                                $valslctdArry = array("", "");
                                                                $srchInsArrys = array("Detail", "Tabular");
                                                                for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                                    if ($row[7] == $srchInsArrys[$z]) {
                                                                        $valslctdArry[$z] = "selected";
                                                                    }
                                                                    ?>
                                                                    <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                                                <?php } ?>
                                                            </select>
                                                        <?php } else { ?>
                                                            <span class=""><?php echo $row[7]; ?></span>
                                                        <?php } ?>                                                         
                                                    </td>
                                                    <td class="lovtd">
                                                        <?php if ($canEdtTmplts === true) { ?>
                                                            <div class="form-group form-group-sm" style="width:100% !important;">
                                                                <input type="number" min="1" max="9999" class="form-control" aria-label="..." id="extrSrvsDtColsRow<?php echo $cntr; ?>_TblColsNum" name="extrSrvsDtColsRow<?php echo $cntr; ?>_TblColsNum" value="<?php echo $row[9]; ?>" style="width:100%;">
                                                            </div>
                                                        <?php } else { ?>
                                                            <span class=""><?php echo $row[9]; ?></span>
                                                        <?php } ?>                                                         
                                                    </td>
                                                    <td class="lovtd">
                                                        <?php if ($canEdtTmplts === true) { ?>
                                                            <div class="form-group form-group-sm" style="width:100% !important;">
                                                                <input type="number" min="1" max="9999" class="form-control" aria-label="..." id="extrSrvsDtColsRow<?php echo $cntr; ?>_Order" name="extrSrvsDtColsRow<?php echo $cntr; ?>_Order" value="<?php echo $row[10]; ?>" style="width:100%;">
                                                            </div>
                                                        <?php } else { ?>
                                                            <span class=""><?php echo $row[10]; ?></span>
                                                        <?php } ?>                                                         
                                                    </td>
                                                    <td class="lovtd">
                                                        <?php if ($canEdtTmplts === true) { ?>
                                                            <div class="form-group form-group-sm" style="width:100% !important;">
                                                                <input type="text" class="form-control" aria-label="..." id="extrSrvsDtColsRow<?php echo $cntr; ?>_TblrColNms" value="<?php echo $row[11]; ?>" style="width:100% !important;">
                                                            </div>
                                                        <?php } else { ?>
                                                            <span class=""><?php echo $row[11]; ?></span>
                                                        <?php } ?>                                                         
                                                    </td>
                                                    <td class="lovtd">
                                                        <?php
                                                        $isChkd = "";
                                                        if ($row[12] == "1") {
                                                            $isChkd = "checked=\"true\"";
                                                        }
                                                        if ($canEdtTmplts === true) {
                                                            ?>
                                                            <div class="form-group form-group-sm">
                                                                <div class="form-check" style="font-size: 12px !important;">
                                                                    <label class="form-check-label">
                                                                        <input type="checkbox" class="form-check-input" id="extrSrvsDtColsRow<?php echo $cntr; ?>_IsRqrd" name="extrSrvsDtColsRow<?php echo $cntr; ?>_IsRqrd" <?php echo $isChkd ?>>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        <?php } else { ?>
                                                            <span class=""><?php echo ($row[12] == "1" ? "Yes" : "No"); ?></span>
                                                        <?php } ?>                                                         
                                                    </td>
                                                </tr>
                                                <?php
                                                $rwExsts = ($row = loc_db_fetch_array($result));
                                                $jIdx += 1;
                                            } else {
                                                ?>
                                                <tr id="extrSrvsDtColsRow_<?php echo $cntr; ?>">                                    
                                                    <td class="lovtd">
                                                        <?php if ($canEdtTmplts === true) { ?>
                                                            <div class="form-group form-group-sm" style="width:100% !important;">
                                                                <span><?php echo $cntr; ?></span>
                                                                <input type="hidden" class="form-control" aria-label="..." id="extrSrvsDtColsRow<?php echo $cntr; ?>_ColNum" value="<?php echo $cntr; ?>" style="width:100% !important;">
                                                                <input type="hidden" class="form-control" aria-label="..." id="extrSrvsDtColsRow<?php echo $cntr; ?>_ExtrDtID" value="-1">
                                                            </div>
                                                        <?php } else { ?>
                                                            <span class=""><?php echo $cntr; ?></span>
                                                        <?php } ?>  
                                                    </td>
                                                    <?php if ($canEdtTmplts === true) { ?>
                                                        <td class="lovtd">
                                                            <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delSrvsExtrDataCol('extrSrvsDtColsRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Field/Column">
                                                                <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                            </button>
                                                        </td>
                                                    <?php } ?>
                                                    <td class="lovtd">
                                                        <?php if ($canEdtTmplts === true) { ?>
                                                            <div class="form-group form-group-sm" style="width:100% !important;">
                                                                <input type="text" class="form-control" aria-label="..." id="extrSrvsDtColsRow<?php echo $cntr; ?>_FieldLbl" value="" style="width:100% !important;">
                                                            </div>
                                                        <?php } else { ?>
                                                            <span class="">&nbsp;</span>
                                                        <?php } ?>                                                         
                                                    </td>
                                                    <td class="lovtd">
                                                        <?php if ($canEdtTmplts === true) { ?>
                                                            <div class="form-group form-group-sm" style="width:100% !important;">
                                                                <div class="input-group"  style="width:100%;">
                                                                    <input type="text" class="form-control" aria-label="..." id="extrSrvsDtColsRow<?php echo $cntr; ?>_LovNm" value="">
                                                                    <input type="hidden" class="form-control" aria-label="..." id="extrSrvsDtColsRow<?php echo $cntr; ?>_LovID" value="-1">
                                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'LOV Names', '', '', '', 'radio', true, '', 'extrSrvsDtColsRow<?php echo $cntr; ?>_LovID', 'extrSrvsDtColsRow<?php echo $cntr; ?>_LovNm', 'clear', 0, '');">
                                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        <?php } else { ?>
                                                            <span class="">&nbsp;</span>
                                                        <?php } ?>                                                         
                                                    </td>
                                                    <td class="lovtd">
                                                        <?php if ($canEdtTmplts === true) { ?>
                                                            <select data-placeholder="Select..." class="form-control chosen-select" id="extrSrvsDtColsRow<?php echo $cntr; ?>_DtTyp" name="extrSrvsDtColsRow<?php echo $cntr; ?>_DtTyp">
                                                                <?php
                                                                $valslctdArry = array("", "", "");
                                                                $srchInsArrys = array("Text", "Number", "Date");
                                                                for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                                    ?>
                                                                    <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                                                <?php } ?>
                                                            </select>
                                                        <?php } else { ?>
                                                            <span class="">&nbsp;</span>
                                                        <?php } ?>                                                         
                                                    </td>
                                                    <td class="lovtd">
                                                        <?php if ($canEdtTmplts === true) { ?>
                                                            <div class="form-group form-group-sm" style="width:100% !important;">
                                                                <input type="text" class="form-control" aria-label="..." id="extrSrvsDtColsRow<?php echo $cntr; ?>_Ctgry" value="" style="width:100% !important;">
                                                            </div>
                                                        <?php } else { ?>
                                                            <span class="">&nbsp;</span>
                                                        <?php } ?>                                                         
                                                    </td>
                                                    <td class="lovtd">
                                                        <?php if ($canEdtTmplts === true) { ?>
                                                            <div class="form-group form-group-sm" style="width:100% !important;">
                                                                <input type="number" min="1" max="9999" class="form-control" aria-label="..." id="extrSrvsDtColsRow<?php echo $cntr; ?>_DtLen" name="extrSrvsDtColsRow<?php echo $cntr; ?>_DtLen" value="" style="width:100%;">
                                                            </div>
                                                        <?php } else { ?>
                                                            <span class="">&nbsp;</span>
                                                        <?php } ?>                                                         
                                                    </td>
                                                    <td class="lovtd">
                                                        <?php if ($canEdtTmplts === true) { ?>
                                                            <select data-placeholder="Select..." class="form-control chosen-select" id="extrSrvsDtColsRow<?php echo $cntr; ?>_DspTyp" name="extrSrvsDtColsRow<?php echo $cntr; ?>_DspTyp">
                                                                <?php
                                                                $valslctdArry = array("", "");
                                                                $srchInsArrys = array("Detail", "Tabular");
                                                                for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                                    ?>
                                                                    <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                                                <?php } ?>
                                                            </select>
                                                        <?php } else { ?>
                                                            <span class="">&nbsp;</span>
                                                        <?php } ?>                                                         
                                                    </td>
                                                    <td class="lovtd">
                                                        <?php if ($canEdtTmplts === true) { ?>
                                                            <div class="form-group form-group-sm" style="width:100% !important;">
                                                                <input type="number" min="1" max="9999" class="form-control" aria-label="..." id="extrSrvsDtColsRow<?php echo $cntr; ?>_TblColsNum" name="extrSrvsDtColsRow<?php echo $cntr; ?>_TblColsNum" value="" style="width:100%;">
                                                            </div>
                                                        <?php } else { ?>
                                                            <span class="">&nbsp;</span>
                                                        <?php } ?>                                                         
                                                    </td>
                                                    <td class="lovtd">
                                                        <?php if ($canEdtTmplts === true) { ?>
                                                            <div class="form-group form-group-sm" style="width:100% !important;">
                                                                <input type="number" min="1" max="9999" class="form-control" aria-label="..." id="extrSrvsDtColsRow<?php echo $cntr; ?>_Order" name="extrSrvsDtColsRow<?php echo $cntr; ?>_Order" value="" style="width:100%;">
                                                            </div>
                                                        <?php } else { ?>
                                                            <span class="">&nbsp;</span>
                                                        <?php } ?>                                                         
                                                    </td>
                                                    <td class="lovtd">
                                                        <?php if ($canEdtTmplts === true) { ?>
                                                            <div class="form-group form-group-sm" style="width:100% !important;">
                                                                <input type="text" class="form-control" aria-label="..." id="extrSrvsDtColsRow<?php echo $cntr; ?>_TblrColNms" value="" style="width:100% !important;">
                                                            </div>
                                                        <?php } else { ?>
                                                            <span class="">&nbsp;</span>
                                                        <?php } ?>                                                         
                                                    </td>
                                                    <td class="lovtd">
                                                        <?php
                                                        $isChkd = "";
                                                        if ($canEdtTmplts === true) {
                                                            ?>
                                                            <div class="form-group form-group-sm">
                                                                <div class="form-check" style="font-size: 12px !important;">
                                                                    <label class="form-check-label">
                                                                        <input type="checkbox" class="form-check-input" id="extrSrvsDtColsRow<?php echo $cntr; ?>_IsRqrd" name="extrSrvsDtColsRow<?php echo $cntr; ?>_IsRqrd" <?php echo $isChkd ?>>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        <?php } else { ?>
                                                            <span class="">&nbsp;</span>
                                                        <?php } ?>                                                         
                                                    </td>
                                                </tr>
                                                <?php
                                            }
                                        } else {
                                            ?>
                                            <tr id="extrSrvsDtColsRow_<?php echo $cntr; ?>">                                    
                                                <td class="lovtd">
                                                    <?php if ($canEdtTmplts === true) { ?>
                                                        <div class="form-group form-group-sm" style="width:100% !important;">
                                                            <span><?php echo $cntr; ?></span>
                                                            <input type="hidden" class="form-control" aria-label="..." id="extrSrvsDtColsRow<?php echo $cntr; ?>_ColNum" value="<?php echo $cntr; ?>" style="width:100% !important;">
                                                            <input type="hidden" class="form-control" aria-label="..." id="extrSrvsDtColsRow<?php echo $cntr; ?>_ExtrDtID" value="-1">
                                                        </div>
                                                    <?php } else { ?>
                                                        <span class=""><?php echo $cntr; ?></span>
                                                    <?php } ?>  
                                                </td>
                                                <?php if ($canEdtTmplts === true) { ?>
                                                    <td class="lovtd">
                                                        <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delSrvsExtrDataCol('extrSrvsDtColsRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Field/Column">
                                                            <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                        </button>
                                                    </td>
                                                <?php } ?>
                                                <td class="lovtd">
                                                    <?php if ($canEdtTmplts === true) { ?>
                                                        <div class="form-group form-group-sm" style="width:100% !important;">
                                                            <input type="text" class="form-control" aria-label="..." id="extrSrvsDtColsRow<?php echo $cntr; ?>_FieldLbl" value="" style="width:100% !important;">
                                                        </div>
                                                    <?php } else { ?>
                                                        <span class="">&nbsp;</span>
                                                    <?php } ?>                                                         
                                                </td>
                                                <td class="lovtd">
                                                    <?php if ($canEdtTmplts === true) { ?>
                                                        <div class="form-group form-group-sm" style="width:100% !important;">
                                                            <div class="input-group"  style="width:100%;">
                                                                <input type="text" class="form-control" aria-label="..." id="extrSrvsDtColsRow<?php echo $cntr; ?>_LovNm" value="">
                                                                <input type="hidden" class="form-control" aria-label="..." id="extrSrvsDtColsRow<?php echo $cntr; ?>_LovID" value="-1">
                                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'LOV Names', '', '', '', 'radio', true, '', 'extrSrvsDtColsRow<?php echo $cntr; ?>_LovID', 'extrSrvsDtColsRow<?php echo $cntr; ?>_LovNm', 'clear', 0, '');">
                                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    <?php } else { ?>
                                                        <span class="">&nbsp;</span>
                                                    <?php } ?>                                                         
                                                </td>
                                                <td class="lovtd">
                                                    <?php if ($canEdtTmplts === true) { ?>
                                                        <select data-placeholder="Select..." class="form-control chosen-select" id="extrSrvsDtColsRow<?php echo $cntr; ?>_DtTyp" name="extrSrvsDtColsRow<?php echo $cntr; ?>_DtTyp">
                                                            <?php
                                                            $valslctdArry = array("", "", "");
                                                            $srchInsArrys = array("Text", "Number", "Date");
                                                            for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                                ?>
                                                                <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    <?php } else { ?>
                                                        <span class="">&nbsp;</span>
                                                    <?php } ?>                                                         
                                                </td>
                                                <td class="lovtd">
                                                    <?php if ($canEdtTmplts === true) { ?>
                                                        <div class="form-group form-group-sm" style="width:100% !important;">
                                                            <input type="text" class="form-control" aria-label="..." id="extrSrvsDtColsRow<?php echo $cntr; ?>_Ctgry" value="" style="width:100% !important;">
                                                        </div>
                                                    <?php } else { ?>
                                                        <span class="">&nbsp;</span>
                                                    <?php } ?>                                                         
                                                </td>
                                                <td class="lovtd">
                                                    <?php if ($canEdtTmplts === true) { ?>
                                                        <div class="form-group form-group-sm" style="width:100% !important;">
                                                            <input type="number" min="1" max="9999" class="form-control" aria-label="..." id="extrSrvsDtColsRow<?php echo $cntr; ?>_DtLen" name="extrSrvsDtColsRow<?php echo $cntr; ?>_DtLen" value="" style="width:100%;">
                                                        </div>
                                                    <?php } else { ?>
                                                        <span class="">&nbsp;</span>
                                                    <?php } ?>                                                         
                                                </td>
                                                <td class="lovtd">
                                                    <?php if ($canEdtTmplts === true) { ?>
                                                        <select data-placeholder="Select..." class="form-control chosen-select" id="extrSrvsDtColsRow<?php echo $cntr; ?>_DspTyp" name="extrSrvsDtColsRow<?php echo $cntr; ?>_DspTyp">
                                                            <?php
                                                            $valslctdArry = array("", "");
                                                            $srchInsArrys = array("Detail", "Tabular");
                                                            for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                                ?>
                                                                <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    <?php } else { ?>
                                                        <span class="">&nbsp;</span>
                                                    <?php } ?>                                                         
                                                </td>
                                                <td class="lovtd">
                                                    <?php if ($canEdtTmplts === true) { ?>
                                                        <div class="form-group form-group-sm" style="width:100% !important;">
                                                            <input type="number" min="1" max="9999" class="form-control" aria-label="..." id="extrSrvsDtColsRow<?php echo $cntr; ?>_TblColsNum" name="extrSrvsDtColsRow<?php echo $cntr; ?>_TblColsNum" value="" style="width:100%;">
                                                        </div>
                                                    <?php } else { ?>
                                                        <span class="">&nbsp;</span>
                                                    <?php } ?>                                                         
                                                </td>
                                                <td class="lovtd">
                                                    <?php if ($canEdtTmplts === true) { ?>
                                                        <div class="form-group form-group-sm" style="width:100% !important;">
                                                            <input type="number" min="1" max="9999" class="form-control" aria-label="..." id="extrSrvsDtColsRow<?php echo $cntr; ?>_Order" name="extrSrvsDtColsRow<?php echo $cntr; ?>_Order" value="" style="width:100%;">
                                                        </div>
                                                    <?php } else { ?>
                                                        <span class="">&nbsp;</span>
                                                    <?php } ?>                                                         
                                                </td>
                                                <td class="lovtd">
                                                    <?php if ($canEdtTmplts === true) { ?>
                                                        <div class="form-group form-group-sm" style="width:100% !important;">
                                                            <input type="text" class="form-control" aria-label="..." id="extrSrvsDtColsRow<?php echo $cntr; ?>_TblrColNms" value="" style="width:100% !important;">
                                                        </div>
                                                    <?php } else { ?>
                                                        <span class="">&nbsp;</span>
                                                    <?php } ?>                                                         
                                                </td>
                                                <td class="lovtd">
                                                    <?php
                                                    $isChkd = "";
                                                    if ($canEdtTmplts === true) {
                                                        ?>
                                                        <div class="form-group form-group-sm">
                                                            <div class="form-check" style="font-size: 12px !important;">
                                                                <label class="form-check-label">
                                                                    <input type="checkbox" class="form-check-input" id="extrSrvsDtColsRow<?php echo $cntr; ?>_IsRqrd" name="extrSrvsDtColsRow<?php echo $cntr; ?>_IsRqrd" <?php echo $isChkd ?>>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    <?php } else { ?>
                                                        <span class="">&nbsp;</span>
                                                    <?php } ?>                                                         
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>                     
                </div>   
            </form>
            <?php
        }
    }
}
?>
