<?php
if (array_key_exists('lgn_num', get_defined_vars())) {
    $canAddPrsn = test_prmssns($dfltPrvldgs[7], $mdlNm);
    $canEdtTmplts = test_prmssns($dfltPrvldgs[18], $mdlNm);
    $canDelPrsn = test_prmssns($dfltPrvldgs[9], $mdlNm);
    $orgID = $_SESSION['ORG_ID'];

    if ($vwtyp == "0") {
        echo $cntent . "<li onclick=\"openATab('#allmodules', 'grp=17&typ=1&pg=$pgNo&subPgNo=1.6');\">
						<span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span><span style=\"text-decoration:none;\">Additional Data Setup</span>
					</li>
                                       </ul>
                                     </div>";
        
        $srcType = isset($_POST['srcType']) ? cleanInputData($_POST['srcType']) : "";
        
        $result = get_AllCstmrExtrDataCols($orgID, $srcType);
        $cntr = 0;
        $curIdx = 0;
        ?>
        <form id='extrDtColsForm' action='' method='post' accept-charset='UTF-8'>
            <div class="row" style="margin: 0px 0px 5px 0px !important;">
                <div style="col-md-12" style="padding:0px 0px 0px 0px">
                    <div class="" style="padding:0px 1px 0px 1px !important;float:left !important;">
                        <div class="form-group form-group-sm">
                            <label for="formSrcType" class="control-label col-md-4">Form:</label>
                            <div  class="col-md-8">
                                <select class="form-control rqrdFld" id="formSrcType" onchange="loadCstmrExtrData();">
                                    <option value="--Please Select--">--Please Select--</option>
                                    <?php
                                    $brghtStr = "";
                                    $isDynmyc = FALSE;
                                    $titleRslt = getLovValues("%", "Both", 0, 100, $brghtStr,
                                            getLovID("MCF Customer Form Types"), $isDynmyc, -1, "", "");
                                    while ($titleRow = loc_db_fetch_array($titleRslt)) {
                                        $selectedTxt = "";
                                        if($srcType == $titleRow[0]){
                                           $selectedTxt= "selected=\"selected\""; 
                                        }
                                        ?>
                                        <option value="<?php echo $titleRow[0]; ?>" <?php echo $selectedTxt; ?>><?php echo $titleRow[0]; ?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="" style="padding:0px 1px 0px 1px !important;float:right !important;">
                        <button type="button" class="btn btn-default btn-sm" style="" onclick="saveCstmrExtrDataCol('#allmodules', 'grp=17&typ=1&pg=1&subPgNo=1.6&vtyp=0');"><img src="cmn_images/FloppyDisk.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">SAVE</button>
                        <button type="button" class="btn btn-default btn-sm" style="" onclick="openATab('#allmodules', 'grp=17&typ=1&pg=1&subPgNo=1.6&vtyp=0');"><img src="cmn_images/reload.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">REFRESH</button>
                    </div>
                </div>
            </div>
            <div class="row"> 
                <div  class="col-md-12">
                    <div class="dataTables_scroll">
                        <table class="table table-striped table-bordered table-responsive" id="extrCstmrDtColsTable" cellspacing="0" width="100%" style="width:100%;min-width: 1700px !important;">
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
                                            <tr id="extrCstmrDtColsRow_<?php echo $cntr; ?>">
                                                <td class="lovtd">
                                                    <?php if ($canEdtTmplts === true) { ?>
                                                        <div class="form-group form-group-sm" style="width:100% !important;">
                                                            <span><?php echo $row[1]; ?></span>
                                                            <input type="hidden" class="form-control" aria-label="..." id="extrCstmrDtColsRow<?php echo $cntr; ?>_ColNum" value="<?php echo $row[1]; ?>" style="width:100% !important;">
                                                            <input type="hidden" class="form-control" aria-label="..." id="extrCstmrDtColsRow<?php echo $cntr; ?>_ExtrDtID" value="<?php echo $row[0]; ?>">
                                                        </div>
                                                    <?php } else { ?>
                                                        <span class=""><?php echo $row[1]; ?></span>
                                                    <?php } ?>  
                                                </td>    
                                                <?php if ($canEdtTmplts === true) { ?>
                                                    <td class="lovtd">
                                                        <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delCstmrExtrDataCol('extrCstmrDtColsRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Field/Column">
                                                            <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                        </button>
                                                    </td>
                                                <?php } ?>  
                                                <td class="lovtd">
                                                    <?php if ($canEdtTmplts === true) { ?>
                                                        <div class="form-group form-group-sm" style="width:100% !important;">
                                                            <input type="text" class="form-control" aria-label="..." id="extrCstmrDtColsRow<?php echo $cntr; ?>_FieldLbl" value="<?php echo $row[2]; ?>" style="width:100% !important;">
                                                        </div>
                                                    <?php } else { ?>
                                                        <span class=""><?php echo $row[2]; ?></span>
                                                    <?php } ?>                                                         
                                                </td>
                                                <td class="lovtd">
                                                    <?php if ($canEdtTmplts === true) { ?>
                                                        <div class="form-group form-group-sm" style="width:100% !important;">
                                                            <div class="input-group"  style="width:100%;">
                                                                <input type="text" class="form-control" aria-label="..." id="extrCstmrDtColsRow<?php echo $cntr; ?>_LovNm" value="<?php echo $row[3]; ?>">
                                                                <input type="hidden" class="form-control" aria-label="..." id="extrCstmrDtColsRow<?php echo $cntr; ?>_LovID" value="-1">
                                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'LOV Names', '', '', '', 'radio', true, '<?php echo $row[3]; ?>', 'extrCstmrDtColsRow<?php echo $cntr; ?>_LovID', 'extrCstmrDtColsRow<?php echo $cntr; ?>_LovNm', 'clear', 0, '');">
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
                                                        <select data-placeholder="Select..." class="form-control chosen-select" id="extrCstmrDtColsRow<?php echo $cntr; ?>_DtTyp" name="extrCstmrDtColsRow<?php echo $cntr; ?>_DtTyp">
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
                                                            <input type="text" class="form-control" aria-label="..." id="extrCstmrDtColsRow<?php echo $cntr; ?>_Ctgry" value="<?php echo $row[5]; ?>" style="width:100% !important;">
                                                        </div>
                                                    <?php } else { ?>
                                                        <span class=""><?php echo $row[5]; ?></span>
                                                    <?php } ?>                                                         
                                                </td>
                                                <td class="lovtd">
                                                    <?php if ($canEdtTmplts === true) { ?>
                                                        <div class="form-group form-group-sm" style="width:100% !important;">
                                                            <input type="number" min="1" max="9999" class="form-control" aria-label="..." id="extrCstmrDtColsRow<?php echo $cntr; ?>_DtLen" name="extrCstmrDtColsRow<?php echo $cntr; ?>_DtLen" value="<?php echo $row[6]; ?>" style="width:100%;">
                                                        </div>
                                                    <?php } else { ?>
                                                        <span class=""><?php echo $row[6]; ?></span>
                                                    <?php } ?>                                                         
                                                </td>
                                                <td class="lovtd">
                                                    <?php if ($canEdtTmplts === true) { ?>
                                                        <select data-placeholder="Select..." class="form-control chosen-select" id="extrCstmrDtColsRow<?php echo $cntr; ?>_DspTyp" name="extrCstmrDtColsRow<?php echo $cntr; ?>_DspTyp">
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
                                                            <input type="number" min="1" max="9999" class="form-control" aria-label="..." id="extrCstmrDtColsRow<?php echo $cntr; ?>_TblColsNum" name="extrCstmrDtColsRow<?php echo $cntr; ?>_TblColsNum" value="<?php echo $row[9]; ?>" style="width:100%;">
                                                        </div>
                                                    <?php } else { ?>
                                                        <span class=""><?php echo $row[9]; ?></span>
                                                    <?php } ?>                                                         
                                                </td>
                                                <td class="lovtd">
                                                    <?php if ($canEdtTmplts === true) { ?>
                                                        <div class="form-group form-group-sm" style="width:100% !important;">
                                                            <input type="number" min="1" max="9999" class="form-control" aria-label="..." id="extrCstmrDtColsRow<?php echo $cntr; ?>_Order" name="extrCstmrDtColsRow<?php echo $cntr; ?>_Order" value="<?php echo $row[10]; ?>" style="width:100%;">
                                                        </div>
                                                    <?php } else { ?>
                                                        <span class=""><?php echo $row[10]; ?></span>
                                                    <?php } ?>                                                         
                                                </td>
                                                <td class="lovtd">
                                                    <?php if ($canEdtTmplts === true) { ?>
                                                        <div class="form-group form-group-sm" style="width:100% !important;">
                                                            <input type="text" class="form-control" aria-label="..." id="extrCstmrDtColsRow<?php echo $cntr; ?>_TblrColNms" value="<?php echo $row[11]; ?>" style="width:100% !important;">
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
                                                                    <input type="checkbox" class="form-check-input" id="extrCstmrDtColsRow<?php echo $cntr; ?>_IsRqrd" name="extrCstmrDtColsRow<?php echo $cntr; ?>_IsRqrd" <?php echo $isChkd ?>>
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
                                            <tr id="extrCstmrDtColsRow_<?php echo $cntr; ?>">                                    
                                                <td class="lovtd">
                                                    <?php if ($canEdtTmplts === true) { ?>
                                                        <div class="form-group form-group-sm" style="width:100% !important;">
                                                            <span><?php echo $cntr; ?></span>
                                                            <input type="hidden" class="form-control" aria-label="..." id="extrCstmrDtColsRow<?php echo $cntr; ?>_ColNum" value="<?php echo $cntr; ?>" style="width:100% !important;">
                                                            <input type="hidden" class="form-control" aria-label="..." id="extrCstmrDtColsRow<?php echo $cntr; ?>_ExtrDtID" value="-1">
                                                        </div>
                                                    <?php } else { ?>
                                                        <span class=""><?php echo $cntr; ?></span>
                                                    <?php } ?>  
                                                </td>
                                                <?php if ($canEdtTmplts === true) { ?>
                                                    <td class="lovtd">
                                                        <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delCstmrExtrDataCol('extrCstmrDtColsRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Field/Column">
                                                            <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                        </button>
                                                    </td>
                                                <?php } ?>
                                                <td class="lovtd">
                                                    <?php if ($canEdtTmplts === true) { ?>
                                                        <div class="form-group form-group-sm" style="width:100% !important;">
                                                            <input type="text" class="form-control" aria-label="..." id="extrCstmrDtColsRow<?php echo $cntr; ?>_FieldLbl" value="" style="width:100% !important;">
                                                        </div>
                                                    <?php } else { ?>
                                                        <span class="">&nbsp;</span>
                                                    <?php } ?>                                                         
                                                </td>
                                                <td class="lovtd">
                                                    <?php if ($canEdtTmplts === true) { ?>
                                                        <div class="form-group form-group-sm" style="width:100% !important;">
                                                            <div class="input-group"  style="width:100%;">
                                                                <input type="text" class="form-control" aria-label="..." id="extrCstmrDtColsRow<?php echo $cntr; ?>_LovNm" value="">
                                                                <input type="hidden" class="form-control" aria-label="..." id="extrCstmrDtColsRow<?php echo $cntr; ?>_LovID" value="-1">
                                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'LOV Names', '', '', '', 'radio', true, '', 'extrCstmrDtColsRow<?php echo $cntr; ?>_LovID', 'extrCstmrDtColsRow<?php echo $cntr; ?>_LovNm', 'clear', 0, '');">
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
                                                        <select data-placeholder="Select..." class="form-control chosen-select" id="extrCstmrDtColsRow<?php echo $cntr; ?>_DtTyp" name="extrCstmrDtColsRow<?php echo $cntr; ?>_DtTyp">
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
                                                            <input type="text" class="form-control" aria-label="..." id="extrCstmrDtColsRow<?php echo $cntr; ?>_Ctgry" value="" style="width:100% !important;">
                                                        </div>
                                                    <?php } else { ?>
                                                        <span class="">&nbsp;</span>
                                                    <?php } ?>                                                         
                                                </td>
                                                <td class="lovtd">
                                                    <?php if ($canEdtTmplts === true) { ?>
                                                        <div class="form-group form-group-sm" style="width:100% !important;">
                                                            <input type="number" min="1" max="9999" class="form-control" aria-label="..." id="extrCstmrDtColsRow<?php echo $cntr; ?>_DtLen" name="extrCstmrDtColsRow<?php echo $cntr; ?>_DtLen" value="" style="width:100%;">
                                                        </div>
                                                    <?php } else { ?>
                                                        <span class="">&nbsp;</span>
                                                    <?php } ?>                                                         
                                                </td>
                                                <td class="lovtd">
                                                    <?php if ($canEdtTmplts === true) { ?>
                                                        <select data-placeholder="Select..." class="form-control chosen-select" id="extrCstmrDtColsRow<?php echo $cntr; ?>_DspTyp" name="extrCstmrDtColsRow<?php echo $cntr; ?>_DspTyp">
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
                                                            <input type="number" min="1" max="9999" class="form-control" aria-label="..." id="extrCstmrDtColsRow<?php echo $cntr; ?>_TblColsNum" name="extrCstmrDtColsRow<?php echo $cntr; ?>_TblColsNum" value="" style="width:100%;">
                                                        </div>
                                                    <?php } else { ?>
                                                        <span class="">&nbsp;</span>
                                                    <?php } ?>                                                         
                                                </td>
                                                <td class="lovtd">
                                                    <?php if ($canEdtTmplts === true) { ?>
                                                        <div class="form-group form-group-sm" style="width:100% !important;">
                                                            <input type="number" min="1" max="9999" class="form-control" aria-label="..." id="extrCstmrDtColsRow<?php echo $cntr; ?>_Order" name="extrCstmrDtColsRow<?php echo $cntr; ?>_Order" value="" style="width:100%;">
                                                        </div>
                                                    <?php } else { ?>
                                                        <span class="">&nbsp;</span>
                                                    <?php } ?>                                                         
                                                </td>
                                                <td class="lovtd">
                                                    <?php if ($canEdtTmplts === true) { ?>
                                                        <div class="form-group form-group-sm" style="width:100% !important;">
                                                            <input type="text" class="form-control" aria-label="..." id="extrCstmrDtColsRow<?php echo $cntr; ?>_TblrColNms" value="" style="width:100% !important;">
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
                                                                    <input type="checkbox" class="form-check-input" id="extrCstmrDtColsRow<?php echo $cntr; ?>_IsRqrd" name="extrCstmrDtColsRow<?php echo $cntr; ?>_IsRqrd" <?php echo $isChkd ?>>
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
                                        <tr id="extrCstmrDtColsRow_<?php echo $cntr; ?>">                                    
                                            <td class="lovtd">
                                                <?php if ($canEdtTmplts === true) { ?>
                                                    <div class="form-group form-group-sm" style="width:100% !important;">
                                                        <span><?php echo $cntr; ?></span>
                                                        <input type="hidden" class="form-control" aria-label="..." id="extrCstmrDtColsRow<?php echo $cntr; ?>_ColNum" value="<?php echo $cntr; ?>" style="width:100% !important;">
                                                        <input type="hidden" class="form-control" aria-label="..." id="extrCstmrDtColsRow<?php echo $cntr; ?>_ExtrDtID" value="-1">
                                                    </div>
                                                <?php } else { ?>
                                                    <span class=""><?php echo $cntr; ?></span>
                                                <?php } ?>  
                                            </td>
                                            <?php if ($canEdtTmplts === true) { ?>
                                                <td class="lovtd">
                                                    <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delCstmrExtrDataCol('extrCstmrDtColsRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Field/Column">
                                                        <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                    </button>
                                                </td>
                                            <?php } ?>
                                            <td class="lovtd">
                                                <?php if ($canEdtTmplts === true) { ?>
                                                    <div class="form-group form-group-sm" style="width:100% !important;">
                                                        <input type="text" class="form-control" aria-label="..." id="extrCstmrDtColsRow<?php echo $cntr; ?>_FieldLbl" value="" style="width:100% !important;">
                                                    </div>
                                                <?php } else { ?>
                                                    <span class="">&nbsp;</span>
                                                <?php } ?>                                                         
                                            </td>
                                            <td class="lovtd">
                                                <?php if ($canEdtTmplts === true) { ?>
                                                    <div class="form-group form-group-sm" style="width:100% !important;">
                                                        <div class="input-group"  style="width:100%;">
                                                            <input type="text" class="form-control" aria-label="..." id="extrCstmrDtColsRow<?php echo $cntr; ?>_LovNm" value="">
                                                            <input type="hidden" class="form-control" aria-label="..." id="extrCstmrDtColsRow<?php echo $cntr; ?>_LovID" value="-1">
                                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'LOV Names', '', '', '', 'radio', true, '', 'extrCstmrDtColsRow<?php echo $cntr; ?>_LovID', 'extrCstmrDtColsRow<?php echo $cntr; ?>_LovNm', 'clear', 0, '');">
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
                                                    <select data-placeholder="Select..." class="form-control chosen-select" id="extrCstmrDtColsRow<?php echo $cntr; ?>_DtTyp" name="extrCstmrDtColsRow<?php echo $cntr; ?>_DtTyp">
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
                                                        <input type="text" class="form-control" aria-label="..." id="extrCstmrDtColsRow<?php echo $cntr; ?>_Ctgry" value="" style="width:100% !important;">
                                                    </div>
                                                <?php } else { ?>
                                                    <span class="">&nbsp;</span>
                                                <?php } ?>                                                         
                                            </td>
                                            <td class="lovtd">
                                                <?php if ($canEdtTmplts === true) { ?>
                                                    <div class="form-group form-group-sm" style="width:100% !important;">
                                                        <input type="number" min="1" max="9999" class="form-control" aria-label="..." id="extrCstmrDtColsRow<?php echo $cntr; ?>_DtLen" name="extrCstmrDtColsRow<?php echo $cntr; ?>_DtLen" value="" style="width:100%;">
                                                    </div>
                                                <?php } else { ?>
                                                    <span class="">&nbsp;</span>
                                                <?php } ?>                                                         
                                            </td>
                                            <td class="lovtd">
                                                <?php if ($canEdtTmplts === true) { ?>
                                                    <select data-placeholder="Select..." class="form-control chosen-select" id="extrCstmrDtColsRow<?php echo $cntr; ?>_DspTyp" name="extrCstmrDtColsRow<?php echo $cntr; ?>_DspTyp">
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
                                                        <input type="number" min="1" max="9999" class="form-control" aria-label="..." id="extrCstmrDtColsRow<?php echo $cntr; ?>_TblColsNum" name="extrCstmrDtColsRow<?php echo $cntr; ?>_TblColsNum" value="" style="width:100%;">
                                                    </div>
                                                <?php } else { ?>
                                                    <span class="">&nbsp;</span>
                                                <?php } ?>                                                         
                                            </td>
                                            <td class="lovtd">
                                                <?php if ($canEdtTmplts === true) { ?>
                                                    <div class="form-group form-group-sm" style="width:100% !important;">
                                                        <input type="number" min="1" max="9999" class="form-control" aria-label="..." id="extrCstmrDtColsRow<?php echo $cntr; ?>_Order" name="extrCstmrDtColsRow<?php echo $cntr; ?>_Order" value="" style="width:100%;">
                                                    </div>
                                                <?php } else { ?>
                                                    <span class="">&nbsp;</span>
                                                <?php } ?>                                                         
                                            </td>
                                            <td class="lovtd">
                                                <?php if ($canEdtTmplts === true) { ?>
                                                    <div class="form-group form-group-sm" style="width:100% !important;">
                                                        <input type="text" class="form-control" aria-label="..." id="extrCstmrDtColsRow<?php echo $cntr; ?>_TblrColNms" value="" style="width:100% !important;">
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
                                                                <input type="checkbox" class="form-check-input" id="extrCstmrDtColsRow<?php echo $cntr; ?>_IsRqrd" name="extrCstmrDtColsRow<?php echo $cntr; ?>_IsRqrd" <?php echo $isChkd ?>>
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
?>
