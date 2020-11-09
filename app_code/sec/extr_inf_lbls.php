<?php
$pageNo = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
$lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 10;
$sortBy = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : "Role Name";

if (array_key_exists('lgn_num', get_defined_vars())) {
    if ($lgn_num > 0 && $canview === true) {
        if ($qstr == "DELETE") {
            if ($actyp == 1) {
                
            }
        } else if ($qstr == "UPDATE") {
            if ($actyp == 1) {
                
            } else if ($actyp == 2) {
                
            }
        } else {
            if ($vwtyp == 0) {
                $pkID = isset($_POST['sbmtdMdlID']) ? $_POST['sbmtdMdlID'] : -1;
                echo $cntent . "<li>
						<span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                                <span style=\"text-decoration:none;\">Extra Info Labels</span>
					</li>
                                       </ul>
                                     </div>";
                ?>
                <form id='allSbgrpsForm' action='' method='post' accept-charset='UTF-8'>
                    <div class="row rhoRowMargin">
                        <div class="col-md-3" style="padding:0px 15px 0px 15px !important;">
                            <select class="form-control" id="allMdlsToSlct" style="width:100%;">
                                <?php
                                $titleRslt = get_AllMdls("%", "Module Name", 0, 1000000, "Module Name");
                                $cntr = 0;
                                while ($titleRow = loc_db_fetch_array($titleRslt)) {
                                    $selectedTxt = "";
                                    if ($pkID <= 0 && $cntr == 0) {
                                        $pkID = $titleRow[0];
                                        $selectedTxt = "selected";
                                    } else if ($pkID == $titleRow[0]) {
                                        $selectedTxt = "selected";
                                    }
                                    $cntr++;
                                    ?>
                                    <option value="<?php echo $titleRow[0]; ?>" <?php echo $selectedTxt; ?>><?php echo $titleRow[1]; ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-3" style="padding:0px 15px 0px 15px !important;">
                            <div class="input-group">
                                <input class="form-control" id="allSbgrpsSrchFor" name="allSbgrpsSrchFor" type = "text" placeholder="Search For" value="<?php
                                echo trim(str_replace("%", " ", $srchFor));
                                ?>" onkeyup="enterKeyFuncAllSbgrps(event, '', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=0')">
                                <input id="allSbgrpsPageNo" name="allSbgrpsPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                <label class="btn btn-primary btn-file input-group-addon" onclick="getAllSbgrps('clear', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=0')">
                                    <span class="glyphicon glyphicon-remove"></span>
                                </label>
                                <label class="btn btn-primary btn-file input-group-addon" onclick="getAllSbgrps('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=0')">
                                    <span class="glyphicon glyphicon-search"></span>
                                </label> 
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="input-group">
                                <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                <!--<select data-placeholder="Select..." class="form-control chosen-select" id="allSbgrpsSrchIn">
                                <?php
                                $valslctdArry = array("");
                                $srchInsArrys = array("Module Name");

                                for ($z = 0; $z < count($srchInsArrys); $z++) {
                                    if ($srchIn == $srchInsArrys[$z]) {
                                        $valslctdArry[$z] = "selected";
                                    }
                                    ?>
                                                                                                                                                        <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                <?php } ?>
                                </select>-->
                                <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                <select data-placeholder="Select..." class="form-control chosen-select" id="allSbgrpsDsplySze" name="allSbgrpsDsplySze" style="min-width:70px !important;">                            
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

                        <div class="col-md-2">
                            <div class="input-group">                        
                                <span class="input-group-addon"><span class="glyphicon glyphicon-sort-by-attributes"></span></span>
                                <select data-placeholder="Select..." class="form-control chosen-select" id="allSbgrpsSortBy" name="allSbgrpsSortBy">
                                    <?php
                                    $valslctdArry = array("", "", "");
                                    $srchInsArrys = array("Table ID");
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
                        <div class="col-md-2">
                            <nav aria-label="Page navigation">
                                <ul class="pagination" style="margin: 0px !important;">
                                    <li>
                                        <a class="rhopagination" href="javascript:getAllSbgrps('previous', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=0');" aria-label="Previous">
                                            <span aria-hidden="true">&laquo;</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="rhopagination" href="javascript:getAllSbgrps('next', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=0');" aria-label="Next">
                                            <span aria-hidden="true">&raquo;</span>
                                        </a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                    <div class="row"> 
                        <div  class="col-md-12">
                            <table class="table table-striped table-bordered table-responsive" id="allSbgrpsTable" cellspacing="0" width="100%" style="width:100%;min-width: 800px;">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Subgroup Name</th>
                                        <th>Main Table name</th>
                                        <th>Key Column</th>
                                        <th>Date Added</th>
                                        <th>...</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $total = get_AllSbgrpsTtl($srchFor, $srchIn, $pkID);
                                    if ($pageNo > ceil($total / $lmtSze)) {
                                        $pageNo = 1;
                                    } else if ($pageNo < 1) {
                                        $pageNo = ceil($total / $lmtSze);
                                    }

                                    $curIdx = $pageNo - 1;
                                    $result = get_AllSbgrps($srchFor, $srchIn, $curIdx, $lmtSze, $pkID, $sortBy);
                                    $cntr = 0;
                                    while ($row = loc_db_fetch_array($result)) {
                                        $cntr += 1;
                                        ?>
                                        <tr id="allSbgrpsEdtRow_<?php echo $cntr; ?>">                                    
                                            <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                            <td class="lovtd">
                                                <span><?php echo $row[0]; ?></span>                                                         
                                            </td>
                                            <td class="lovtd">
                                                <span><?php echo $row[1]; ?></span>                                                         
                                            </td>
                                            <td class="lovtd">
                                                <span><?php echo $row[2]; ?></span>                                                        
                                            </td>
                                            <td class="lovtd">
                                                <span><?php echo $row[3]; ?></span>                                                       
                                            </td>
                                            <td class="lovtd">
                                                <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Details" onclick="getOneSbgrpForm('myFormsModalLg', 'myFormsModalBodyLg', 'myFormsModalTitleLg', 'sbgrpsLblsForm', 'View/Edit Priviledges for Module (<?php echo $row[0]; ?>)', <?php echo $row[4]; ?>, 1, <?php echo $pgNo; ?>, 'clear');" style="padding:2px !important;" style="padding:2px !important;">
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
                </form>
                <?php
            } else if ($vwtyp == 1) {
                $pkID = isset($_POST['sbmtdTblID']) ? $_POST['sbmtdTblID'] : -1;
                ?>
                <form id='sbgrpsLblsForm' action='' method='post' accept-charset='UTF-8'>                   
                    <?php
                    $total = get_AllSbgrpsLblsTtl($srchFor, $srchIn, $pkID);
                    if ($pageNo > ceil($total / $lmtSze)) {
                        $pageNo = 1;
                    } else if ($pageNo < 1) {
                        $pageNo = ceil($total / $lmtSze);
                    }

                    $curIdx = $pageNo - 1;
                    $result1 = get_AllSbgrpsLbls($srchFor, $srchIn, $curIdx, $lmtSze, $pkID, $sortBy);
                    $colClassType1 = "col-lg-2";
                    $colClassType2 = "col-lg-4";
                    ?>
                    <div class="row">
                        <fieldset class=""><!--<legend class="basic_person_lg">Priviledges for Role</legend>-->
                            <div class="row">
                                <div class="col-md-3" style="padding:0px 1px 0px 15px !important;">                    
                                    <input id="sbmtdTblID" type = "hidden" value="<?php echo $pkID; ?>">
                                    <input id="sbgrpsLblsSlctdLabels" type = "hidden" value="">
                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Extra Information Labels', '', '', '', 'check', true, '', '', '', 'clear', 1,
                                                    '', function () {
                                                        afterOneSbgrpLblSlctn();
                                                    }, 'sbgrpsLblsSlctdLabels');">
                                        <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                        Add Label
                                    </button>
                                </div>
                                <div class="col-md-3" style="padding:0px 15px 0px 15px !important;">
                                    <div class="input-group">
                                        <input class="form-control" id="sbgrpsLblsSrchFor" type = "text" placeholder="Search For" value="<?php
                                        echo trim(str_replace("%", " ", $srchFor));
                                        ?>" onkeyup="enterKeyFuncOneSbgrp(event, 'myFormsModalLg', 'myFormsModalBodyLg', 'myFormsModalTitleLg', 'sbgrpsLblsForm', '', <?php echo $pkID; ?>, <?php echo $vwtyp; ?>, <?php echo $pgNo; ?>, '');">
                                        <input id="sbgrpsLblsPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getOneSbgrpForm('myFormsModalLg', 'myFormsModalBodyLg', 'myFormsModalTitleLg', 'sbgrpsLblsForm', '', <?php echo $pkID; ?>, <?php echo $vwtyp; ?>, <?php echo $pgNo; ?>, 'clear');">
                                            <span class="glyphicon glyphicon-remove"></span>
                                        </label>
                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getOneSbgrpForm('myFormsModalLg', 'myFormsModalBodyLg', 'myFormsModalTitleLg', 'sbgrpsLblsForm', '', <?php echo $pkID; ?>, <?php echo $vwtyp; ?>, <?php echo $pgNo; ?>, '');">
                                            <span class="glyphicon glyphicon-search"></span>
                                        </label> 
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="input-group">
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                        <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                        <select data-placeholder="Select..." class="form-control chosen-select" id="sbgrpsLblsDsplySze" style="min-width:70px !important;">                            
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
                                <div class="col-md-2">
                                    <div class="input-group">                        
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-sort-by-attributes"></span></span>
                                        <select data-placeholder="Select..." class="form-control chosen-select" id="sbgrpsLblsSortBy">
                                            <?php
                                            $valslctdArry = array("", "", "");
                                            $srchInsArrys = array("Label Name");
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
                                <div class="col-md-2">
                                    <nav aria-label="Page navigation">
                                        <ul class="pagination" style="margin: 0px !important;">
                                            <li>
                                                <a href="javascript:getOneSbgrpForm('myFormsModalLg', 'myFormsModalBodyLg', 'myFormsModalTitleLg', 'sbgrpsLblsForm', '', <?php echo $pkID; ?>, <?php echo $vwtyp; ?>, <?php echo $pgNo; ?>,'previous');" aria-label="Previous">
                                                    <span aria-hidden="true">&laquo;</span>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:getOneSbgrpForm('myFormsModalLg', 'myFormsModalBodyLg', 'myFormsModalTitleLg', 'sbgrpsLblsForm', '', <?php echo $pkID; ?>, <?php echo $vwtyp; ?>, <?php echo $pgNo; ?>,'next');" aria-label="Next">
                                                    <span aria-hidden="true">&raquo;</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </nav>
                                </div>
                            </div>
                            <div class="row"> 
                                <div  class="col-md-12">
                                    <table class="table table-striped table-bordered table-responsive" id="sbgrpsLblsTable" cellspacing="0" width="100%" style="width:100%;min-width: 800px !important;">
                                        <thead>
                                            <tr>
                                                <th>No.</th>
                                                <th>Label Name</th>
                                                <th>Enable/Disable</th>
                                                <th>Delete</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $cntr = 0;
                                            while ($row1 = loc_db_fetch_array($result1)) {
                                                $cntr += 1;
                                                ?>
                                                <tr id="sbgrpsLblsEdtRow_<?php echo $cntr; ?>">                                    
                                                    <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                                    <td class="lovtd"><span><?php echo $row1[0]; ?></span></td>
                                                    <?php
                                                    if ($row1[1] == 1) {
                                                        ?>
                                                        <td class="lovtd">
                                                            <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="Click to Disable" onclick="enblDsblSbgrpLblSlctn(<?php echo $row1[4];?>, 0);" style="padding:2px !important;" style="padding:2px !important;">
                                                                <!--<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>-->
                                                                <img src="cmn_images/90.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                <span>DISABLE&nbsp;&nbsp;</span>
                                                            </button>
                                                        </td>
                                                    <?php } else {
                                                        ?>
                                                        <td class="lovtd">
                                                            <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="Click to Enable" onclick="enblDsblSbgrpLblSlctn(<?php echo $row1[4];?>, 1);" style="padding:2px !important;" style="padding:2px !important;">
                                                                <!--<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>-->
                                                                <img src="cmn_images/success.gif" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                <span>ENABLE&nbsp;&nbsp;</span>
                                                            </button>
                                                        </td>
                                                    <?php }
                                                    ?>
                                                    <td class="lovtd">
                                                        <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="Click to Delete" onclick="deleteSbgrpLblSlctn(<?php echo $row1[4];?>);" style="padding:2px !important;" style="padding:2px !important;">
                                                            <!--<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>-->
                                                            <img src="cmn_images/delete.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                            <span>DELETE&nbsp;&nbsp;</span>
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
                    </div>
                </form>
                <?php
            } else if ($vwtyp == 2) {
                //Save Selected Labels
                header("content-type:application/json");
                $sbmtdTblID = isset($_POST['sbmtdTblID']) ? cleanInputData($_POST['sbmtdTblID']) : -1;
                $sbgrpsLblsSlctdLabels = isset($_POST['sbgrpsLblsSlctdLabels']) ? cleanInputData($_POST['sbgrpsLblsSlctdLabels']) : "";

                if (trim($sbgrpsLblsSlctdLabels, ",") != "") {
                    $variousRows1 = explode(",", trim($sbgrpsLblsSlctdLabels, ","));
                    for ($z = 0; $z < count($variousRows1); $z++) {
                        $ln_LableLnID = (float) (cleanInputData1($variousRows1[$z]));
                        if ($ln_LableLnID > 0) {
                            if (doesTableHvThsExtrInfoLbl($sbmtdTblID, $ln_LableLnID) == false) {
                                createAllwdExtraInfos($sbmtdTblID, $ln_LableLnID, true);
                            }
                        }
                    }
                }

                $errMsg = "Success";
                $arr_content['message'] = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>" . $errMsg;
                //var_dump($_POST);
                //var_dump($arr_content);
                echo json_encode($arr_content);
                exit();
            } else if ($vwtyp == 3) {
                //Enable Disable Selected Labels
                header("content-type:application/json");
                $sbmtdCmbntnID = isset($_POST['sbmtdCmbntnID']) ? cleanInputData($_POST['sbmtdCmbntnID']) : -1;
                $enableDsbl = isset($_POST['enableDsbl']) ? cleanInputData($_POST['enableDsbl']) : "0";

                if ($enableDsbl == "1") {
                    enblDsblAllwdExtraInfos($sbmtdCmbntnID, true);
                } else {
                    enblDsblAllwdExtraInfos($sbmtdCmbntnID, false);
                }

                $errMsg = "Success";
                $arr_content['message'] = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>" . $errMsg;
                //var_dump($_POST);
                //var_dump($arr_content);
                echo json_encode($arr_content);
                exit();
            } else if ($vwtyp == 4) {
                //Delete Selected Labels
                header("content-type:application/json");
                $sbmtdCmbntnID = isset($_POST['sbmtdCmbntnID']) ? cleanInputData($_POST['sbmtdCmbntnID']) : -1;

                deleteAllwdExtraInfos($sbmtdCmbntnID);

                $errMsg = "Success";
                $arr_content['message'] = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>" . $errMsg;
                //var_dump($_POST);
                //var_dump($arr_content);
                echo json_encode($arr_content);
                exit();
            }
        }
    }
}