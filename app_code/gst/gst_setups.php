<?php
$canAddGst = test_prmssns($dfltPrvldgs[3], $mdlNm);
$canEdtGst = test_prmssns($dfltPrvldgs[4], $mdlNm);
$canDelGst = test_prmssns($dfltPrvldgs[5], $mdlNm);

$pageNo = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
$lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 10;
$sortBy = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : "";

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
                $pkID = isset($_POST['sbmtdLovID']) ? $_POST['sbmtdLovID'] : -1;
                echo $cntent . "<li onclick=\"openATab('#allmodules', 'grp=$group&typ=$type');\">
						<span style=\"text-decoration:none;\">Value Lists Setup Menu</span>
				</li>
                                <li>
                                    <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                    <span style=\"text-decoration:none;\">Value Lists Setup</span>
				</li>
                               </ul>
                              </div>";
                $total = get_LovsTtl($srchFor, $srchIn);
                if ($pageNo > ceil($total / $lmtSze)) {
                    $pageNo = 1;
                } else if ($pageNo < 1) {
                    $pageNo = ceil($total / $lmtSze);
                }

                $curIdx = $pageNo - 1;
                $result = get_LovsTblr($srchFor, $srchIn, $curIdx, $lmtSze);
                $cntr = 0;
                $colClassType1 = "col-lg-2";
                $colClassType2 = "col-lg-3";
                $colClassType3 = "col-lg-4";
                ?>
                <form id='allLovStpsForm' action='' method='post' accept-charset='UTF-8'>
                    <div class="row rhoRowMargin">
                        <?php
                        if ($canAddGst === true) {
                            ?> 
                            <div class="<?php echo $colClassType3; ?>" style="padding:0px 1px 0px 1px !important;"> 
                                <div class="col-md-6">
                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="getOneLovStpForm(-1, 2);" style="width:100% !important;">
                                        <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                        New Values List
                                    </button>
                                </div>
                                <div class="col-md-6">
                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="saveLovStpForm();" style="width:100% !important;">
                                        <img src="cmn_images/FloppyDisk.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                        Save
                                    </button>
                                </div>
                            </div>
                            <?php
                        } else {
                            $colClassType1 = "col-lg-2";
                            $colClassType2 = "col-lg-5";
                        }
                        ?>
                        <div class="<?php echo $colClassType2; ?>" style="padding:0px 15px 0px 15px !important;">
                            <div class="input-group">
                                <input class="form-control" id="allLovStpsSrchFor" type = "text" placeholder="Search For" value="<?php echo trim(str_replace("%", " ", $srchFor)); ?>" onkeyup="enterKeyFuncLovStps(event, '', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>')">
                                <input id="allLovStpsPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                <label class="btn btn-primary btn-file input-group-addon" onclick="getAllLovStps('clear', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>')">
                                    <span class="glyphicon glyphicon-remove"></span>
                                </label>
                                <label class="btn btn-primary btn-file input-group-addon" onclick="getAllLovStps('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>')">
                                    <span class="glyphicon glyphicon-search"></span>
                                </label> 
                            </div>
                        </div>
                        <div class="<?php echo $colClassType2; ?>">
                            <div class="input-group">
                                <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                <select data-placeholder="Select..." class="form-control chosen-select" id="allLovStpsSrchIn">
                                    <?php
                                    $valslctdArry = array("", "");
                                    $srchInsArrys = array("LOV Name", "LOV Description");

                                    for ($z = 0; $z < count($srchInsArrys); $z++) {
                                        if ($srchIn == $srchInsArrys[$z]) {
                                            $valslctdArry[$z] = "selected";
                                        }
                                        ?>
                                        <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                    <?php } ?>
                                </select>
                                <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                <select data-placeholder="Select..." class="form-control chosen-select" id="allLovStpsDsplySze" style="min-width:70px !important;">                            
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
                            <nav aria-label="Page navigation">
                                <ul class="pagination" style="margin: 0px !important;">
                                    <li>
                                        <a class="rhopagination" href="javascript:getAllLovStps('previous', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');" aria-label="Previous">
                                            <span aria-hidden="true">&laquo;</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="rhopagination" href="javascript:getAllLovStps('next', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');" aria-label="Next">
                                            <span aria-hidden="true">&raquo;</span>
                                        </a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                    <div class="row"  style="padding:1px 15px 1px 15px !important;"><hr style="margin:1px 0px 3px 0px;"></div>
                    <div class="row" style="padding:0px 15px 0px 15px !important"> 
                        <div  class="col-lg-3" style="padding:0px 1px 0px 1px !important">
                            <fieldset class="basic_person_fs">                                        
                                <table class="table table-striped table-bordered table-responsive" id="allLovStpsTable" cellspacing="0" width="100%" style="width:100%;">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Value List Name</th>
                                            <?php
                                            if ($canDelGst === true) {
                                                ?>
                                                <th>&nbsp;</th>
                                            <?php } ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        while ($row = loc_db_fetch_array($result)) {
                                            if ($pkID <= 0 && $cntr <= 0) {
                                                $pkID = $row[0];
                                            }
                                            $cntr += 1;
                                            ?>
                                            <tr id="allLovStpsRow_<?php echo $cntr; ?>" class="hand_cursor">                                    
                                                <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                                <td class="lovtd"><?php echo $row[1]; ?><input type="hidden" class="form-control" aria-label="..." id="allLovStpsRow<?php echo $cntr; ?>_LovID" value="<?php echo $row[0]; ?>"></td>                                                
                                                <?php
                                                if ($canDelGst === true) {
                                                    ?>
                                                    <td class="lovtd">
                                                        <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delLOV('allLovStpsRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Value List">
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
                        <div  class="col-lg-9" style="padding:0px 1px 0px 1px !important">
                            <fieldset class="basic_person_fs" style="padding-top:10px !important;">
                                <div class="rho-container-fluid" id="lovStpsDetailInfo">
                                    <?php
                                    if ($pkID > 0) {
                                        $result1 = get_LovsDetail($pkID);
                                        $sbmtdLovID = $pkID;
                                        while ($row1 = loc_db_fetch_array($result1)) {
                                            ?>
                                            <div class="row phone-only-btn" style="margin: 0px 0px 10px 0px !important;">
                                                <div class="col-md-12" style="padding:0px 0px 0px 0px !important;">
                                                    <button type="button" class="btn btn-default btn-sm phone-only-btn" onclick="openATab('#lovStpsDetailInfo', 'grp=4&typ=1&pg=1&vtyp=1&sbmtdLovID=<?php echo $sbmtdLovID; ?>');">LOV Details</button>
                                                    <button type="button" class="btn btn-default btn-sm phone-only-btn" onclick="openATab('#lovPsblValsPage', 'grp=4&typ=1&pg=1&vtyp=3&sbmtdLovID=<?php echo $sbmtdLovID; ?>');">Possible Values</button>
                                                </div>
                                            </div>
                                            <ul class="nav nav-tabs rho-hideable-tabs" style="margin-top:-5px !important;">
                                                <li class="active"><a data-toggle="tab" data-rhodata="&pg=1&vtyp=1&sbmtdLovID=<?php echo $sbmtdLovID; ?>" href="#lovDetPage" id="lovDetPagetab">LOV Details</a></li>
                                                <li><a data-toggle="tabajxgst" data-rhodata="&pg=1&vtyp=3&sbmtdLovID=<?php echo $sbmtdLovID; ?>" href="#lovPsblValsPage" id="lovPsblValsPagetab">Possible Values</a></li>
                                            </ul>
                                            <div class="row">                  
                                                <div class="col-md-12">
                                                    <div class="custDiv"> 
                                                        <div class="tab-content">
                                                            <div id="lovDetPage" class="tab-pane fadein active" style="border:none !important;"> 
                                                                <div class="row">
                                                                    <div  class="col-md-6" style="padding:0px 3px 0px 3px !important;">
                                                                        <fieldset class="basic_person_fs" style="padding:10px 3px 0px 3px !important;"> 
                                                                            <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                                                <label for="lovDetLovNm" class="control-label col-lg-4">Value List Name:</label>
                                                                                <div  class="col-lg-8">
                                                                                    <?php if ($canEdtGst === true) { ?>
                                                                                        <input type="text" class="form-control" aria-label="..." id="lovDetLovNm" name="lovDetLovNm" value="<?php echo $row1[1]; ?>" style="width:100%;">
                                                                                        <input type="hidden" class="form-control" aria-label="..." id="lovDetLovID" name="lovDetLovID" value="<?php echo $row1[0]; ?>">
                                                                                    <?php } else {
                                                                                        ?>
                                                                                        <span><?php echo $row1[1]; ?></span>
                                                                                        <?php
                                                                                    }
                                                                                    ?>
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                                                <label for="lovDetLovDesc" class="control-label col-lg-4">Value List Description:</label>
                                                                                <div  class="col-lg-8">
                                                                                    <?php if ($canEdtGst === true) { ?>
                                                                                        <textarea class="form-control" aria-label="..." id="lovDetLovDesc" name="lovDetLovDesc" style="width:100%;" cols="3" rows="4"><?php echo $row1[2]; ?></textarea>
                                                                                    <?php } else {
                                                                                        ?>
                                                                                        <span><?php echo $row1[2]; ?></span>
                                                                                        <?php
                                                                                    }
                                                                                    ?>
                                                                                </div>
                                                                            </div>
                                                                        </fieldset>
                                                                    </div>
                                                                    <div  class="col-md-6" style="padding:0px 3px 0px 3px !important;">
                                                                        <fieldset class = "basic_person_fs">
                                                                            <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                                                <label for="lovDetDfndBy" class="control-label col-lg-6">Defined By:</label>
                                                                                <div class="col-lg-6">
                                                                                    <?php if ($canEdtGst === true) { ?>
                                                                                        <select data-placeholder="Select..." class="form-control chosen-select" id="lovDetDfndBy">
                                                                                            <?php
                                                                                            $valslctdArry = array("", "");
                                                                                            $srchInsArrys = array("SYS", "USR");
                                                                                            for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                                                                if ($row1[4] == $srchInsArrys[$z]) {
                                                                                                    $valslctdArry[$z] = "selected";
                                                                                                }
                                                                                                ?>
                                                                                                <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                                                                            <?php } ?>
                                                                                        </select>
                                                                                    <?php } else {
                                                                                        ?>
                                                                                        <span><?php echo $row1[4]; ?></span>
                                                                                        <?php
                                                                                    }
                                                                                    ?>
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                                                <label for="lovDetIsDynmc" class="control-label col-lg-6">Is Dynamic?:</label>
                                                                                <div class="col-lg-6">
                                                                                    <?php
                                                                                    $chkdYes = "";
                                                                                    $chkdNo = "checked=\"\"";
                                                                                    if ($row1[5] == "YES") {
                                                                                        $chkdNo = "";
                                                                                        $chkdYes = "checked=\"\"";
                                                                                    }
                                                                                    ?>
                                                                                    <?php if ($canEdtGst === true) { ?>
                                                                                        <label class="radio-inline"><input type="radio" name="lovDetIsDynmc" value="YES" <?php echo $chkdYes; ?>>YES</label>
                                                                                        <label class="radio-inline"><input type="radio" name="lovDetIsDynmc" value="NO" <?php echo $chkdNo; ?>>NO</label>
                                                                                    <?php } else {
                                                                                        ?>
                                                                                        <span><?php echo ($row1[5] == "YES" ? "YES" : "NO"); ?></span>
                                                                                        <?php
                                                                                    }
                                                                                    ?>
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                                                <label for="lovDetIsEnabled" class="control-label col-lg-6">Is Enabled?:</label>
                                                                                <div class="col-lg-6">
                                                                                    <?php
                                                                                    $chkdYes = "";
                                                                                    $chkdNo = "checked=\"\"";
                                                                                    if ($row1[6] == "YES") {
                                                                                        $chkdNo = "";
                                                                                        $chkdYes = "checked=\"\"";
                                                                                    }
                                                                                    ?>
                                                                                    <?php if ($canEdtGst === true) { ?>
                                                                                        <label class="radio-inline"><input type="radio" name="lovDetIsEnabled" value="YES" <?php echo $chkdYes; ?>>YES</label>
                                                                                        <label class="radio-inline"><input type="radio" name="lovDetIsEnabled" value="NO" <?php echo $chkdNo; ?>>NO</label>
                                                                                    <?php } else {
                                                                                        ?>
                                                                                        <span><?php echo ($row1[6] == "YES" ? "YES" : "NO"); ?></span>
                                                                                        <?php
                                                                                    }
                                                                                    ?>
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                                                <label for="lovDetOrdrByCls" class="control-label col-lg-6">Order By Clause:</label>
                                                                                <div  class="col-lg-6">
                                                                                    <?php if ($canEdtGst === true) { ?>
                                                                                        <input type="text" class="form-control" aria-label="..." id="lovDetOrdrByCls" name="lovDetOrdrByCls" value="<?php echo $row1[7]; ?>" style="width:100%;">
                                                                                    <?php } else {
                                                                                        ?>
                                                                                        <span><?php echo $row1[7]; ?></span>
                                                                                        <?php
                                                                                    }
                                                                                    ?>
                                                                                </div>
                                                                            </div>
                                                                        </fieldset>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div  class="col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                                        <fieldset class="basic_person_fs" style="padding:10px 3px 0px 3px !important;"> 
                                                                            <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                                                <label for="lovDetSqlQry" class="control-label col-lg-2">SQL Query if Dynamic:</label>
                                                                                <div  class="col-lg-10">
                                                                                    <?php if ($canEdtGst === true) { ?>
                                                                                        <textarea class="form-control" aria-label="..." id="lovDetSqlQry" name="lovDetSqlQry" style="width:100%;" cols="9" rows="10"><?php echo $row1[3]; ?></textarea>
                                                                                    <?php } else {
                                                                                        ?>
                                                                                        <span><?php echo $row1[3]; ?></span>
                                                                                        <?php
                                                                                    }
                                                                                    ?>
                                                                                </div>
                                                                            </div>
                                                                        </fieldset>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div id="lovPsblValsPage" class="tab-pane fade" style="border:none !important;padding:1px !important;"></div>
                                                        </div>                        
                                                    </div>                         
                                                </div>                
                                            </div>                                              
                                            <?php
                                        }
                                    } else {
                                        ?>
                                        <span>No Results Found</span>
                                        <?php
                                    }
                                    ?>
                                </div>
                            </fieldset>
                        </div>
                    </div>
                </form>
                <?php
            } else if ($vwtyp == 1) {
                //lov detail on tr click
                $curIdx = 0;
                $pkID = isset($_POST['sbmtdLovID']) ? $_POST['sbmtdLovID'] : -1;
                if ($pkID > 0) {
                    $result1 = get_LovsDetail($pkID);
                    $sbmtdLovID = $pkID;
                    while ($row1 = loc_db_fetch_array($result1)) {
                        ?>
                        <div class="row phone-only-btn" style="margin: 0px 0px 10px 0px !important;">
                            <div class="col-md-12" style="padding:0px 0px 0px 0px !important;">
                                <button type="button" class="btn btn-default btn-sm phone-only-btn" onclick="openATab('#lovStpsDetailInfo', 'grp=4&typ=1&pg=1&vtyp=1&sbmtdLovID=<?php echo $sbmtdLovID; ?>');">LOV Details</button>
                                <button type="button" class="btn btn-default btn-sm phone-only-btn" onclick="openATab('#lovPsblValsPage', 'grp=4&typ=1&pg=1&vtyp=3&sbmtdLovID=<?php echo $sbmtdLovID; ?>');">Possible Values</button>
                            </div>
                        </div>
                        <ul class="nav nav-tabs rho-hideable-tabs" style="margin-top:-5px !important;">
                            <li class="active"><a data-toggle="tab" data-rhodata="&pg=1&vtyp=1&sbmtdLovID=<?php echo $sbmtdLovID; ?>" href="#lovDetPage" id="lovDetPagetab">LOV Details</a></li>
                            <li><a data-toggle="tabajxgst" data-rhodata="&pg=1&vtyp=3&sbmtdLovID=<?php echo $sbmtdLovID; ?>" href="#lovPsblValsPage" id="lovPsblValsPagetab">Possible Values</a></li>
                        </ul>
                        <div class="row">                  
                            <div class="col-md-12">
                                <div class="custDiv"> 
                                    <div class="tab-content">
                                        <div id="lovDetPage" class="tab-pane fadein active" style="border:none !important;"> 
                                            <div class="row">
                                                <div  class="col-md-6" style="padding:0px 3px 0px 3px !important;">
                                                    <fieldset class="basic_person_fs" style="padding:10px 3px 0px 3px !important;"> 
                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                            <label for="lovDetLovNm" class="control-label col-lg-4">Value List Name:</label>
                                                            <div  class="col-lg-8">
                                                                <?php if ($canEdtGst === true) { ?>
                                                                    <input type="text" class="form-control" aria-label="..." id="lovDetLovNm" name="lovDetLovNm" value="<?php echo $row1[1]; ?>" style="width:100%;">
                                                                    <input type="hidden" class="form-control" aria-label="..." id="lovDetLovID" name="lovDetLovID" value="<?php echo $row1[0]; ?>">
                                                                <?php } else {
                                                                    ?>
                                                                    <span><?php echo $row1[1]; ?></span>
                                                                    <?php
                                                                }
                                                                ?>
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                            <label for="lovDetLovDesc" class="control-label col-lg-4">Value List Description:</label>
                                                            <div  class="col-lg-8">
                                                                <?php if ($canEdtGst === true) { ?>
                                                                    <textarea class="form-control" aria-label="..." id="lovDetLovDesc" name="lovDetLovDesc" style="width:100%;" cols="3" rows="4"><?php echo $row1[2]; ?></textarea>
                                                                <?php } else {
                                                                    ?>
                                                                    <span><?php echo $row1[2]; ?></span>
                                                                    <?php
                                                                }
                                                                ?>
                                                            </div>
                                                        </div>
                                                    </fieldset>
                                                </div>
                                                <div  class="col-md-6" style="padding:0px 3px 0px 3px !important;">
                                                    <fieldset class = "basic_person_fs">
                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                            <label for="lovDetDfndBy" class="control-label col-lg-6">Defined By:</label>
                                                            <div class="col-lg-6">
                                                                <?php if ($canEdtGst === true) { ?>
                                                                    <select data-placeholder="Select..." class="form-control chosen-select" id="lovDetDfndBy">
                                                                        <?php
                                                                        $valslctdArry = array("", "");
                                                                        $srchInsArrys = array("SYS", "USR");
                                                                        for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                                            if ($row1[4] == $srchInsArrys[$z]) {
                                                                                $valslctdArry[$z] = "selected";
                                                                            }
                                                                            ?>
                                                                            <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                                                        <?php } ?>
                                                                    </select>
                                                                <?php } else {
                                                                    ?>
                                                                    <span><?php echo $row1[4]; ?></span>
                                                                    <?php
                                                                }
                                                                ?>
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                            <label for="lovDetIsDynmc" class="control-label col-lg-6">Is Dynamic?:</label>
                                                            <div class="col-lg-6">
                                                                <?php
                                                                $chkdYes = "";
                                                                $chkdNo = "checked=\"\"";
                                                                if ($row1[5] == "YES") {
                                                                    $chkdNo = "";
                                                                    $chkdYes = "checked=\"\"";
                                                                }
                                                                ?>
                                                                <?php if ($canEdtGst === true) { ?>
                                                                    <label class="radio-inline"><input type="radio" name="lovDetIsDynmc" value="YES" <?php echo $chkdYes; ?>>YES</label>
                                                                    <label class="radio-inline"><input type="radio" name="lovDetIsDynmc" value="NO" <?php echo $chkdNo; ?>>NO</label>
                                                                <?php } else {
                                                                    ?>
                                                                    <span><?php echo ($row1[5] == "YES" ? "YES" : "NO"); ?></span>
                                                                    <?php
                                                                }
                                                                ?>
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                            <label for="lovDetIsEnabled" class="control-label col-lg-6">Is Enabled?:</label>
                                                            <div class="col-lg-6">
                                                                <?php
                                                                $chkdYes = "";
                                                                $chkdNo = "checked=\"\"";
                                                                if ($row1[6] == "YES") {
                                                                    $chkdNo = "";
                                                                    $chkdYes = "checked=\"\"";
                                                                }
                                                                ?>
                                                                <?php if ($canEdtGst === true) { ?>
                                                                    <label class="radio-inline"><input type="radio" name="lovDetIsEnabled" value="YES" <?php echo $chkdYes; ?>>YES</label>
                                                                    <label class="radio-inline"><input type="radio" name="lovDetIsEnabled" value="NO" <?php echo $chkdNo; ?>>NO</label>
                                                                <?php } else {
                                                                    ?>
                                                                    <span><?php echo ($row1[6] == "YES" ? "YES" : "NO"); ?></span>
                                                                    <?php
                                                                }
                                                                ?>
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                            <label for="lovDetOrdrByCls" class="control-label col-lg-6">Order By Clause:</label>
                                                            <div  class="col-lg-6">
                                                                <?php if ($canEdtGst === true) { ?>
                                                                    <input type="text" class="form-control" aria-label="..." id="lovDetOrdrByCls" name="lovDetOrdrByCls" value="<?php echo $row1[7]; ?>" style="width:100%;">
                                                                <?php } else {
                                                                    ?>
                                                                    <span><?php echo $row1[7]; ?></span>
                                                                    <?php
                                                                }
                                                                ?>
                                                            </div>
                                                        </div>
                                                    </fieldset>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div  class="col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                    <fieldset class="basic_person_fs" style="padding:10px 3px 0px 3px !important;"> 
                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                            <label for="lovDetSqlQry" class="control-label col-lg-2">SQL Query if Dynamic:</label>
                                                            <div  class="col-lg-10">
                                                                <?php if ($canEdtGst === true) { ?>
                                                                    <textarea class="form-control" aria-label="..." id="lovDetSqlQry" name="lovDetSqlQry" style="width:100%;" cols="9" rows="10"><?php echo $row1[3]; ?></textarea>
                                                                <?php } else {
                                                                    ?>
                                                                    <span><?php echo $row1[3]; ?></span>
                                                                    <?php
                                                                }
                                                                ?>
                                                            </div>
                                                        </div>
                                                    </fieldset>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="lovPsblValsPage" class="tab-pane fade" style="border:none !important;padding:1px !important;"></div>
                                    </div>                        
                                </div>                         
                            </div>                
                        </div>                                              
                        <?php
                    }
                } else {
                    ?>
                    <span>No Results Found</span>
                    <?php
                }
            } else if ($vwtyp == 2) {
                //New Org Form
                $curIdx = 0;
                $pkID = -1;
                if ($canAddGst === true) {
                    
                } else {
                    exit();
                }
                ?>
                <div class="row">                  
                    <div class="col-md-12">
                        <div class="custDiv" style="border:none !important;"> 
                            <div class="tab-content">
                                <div id="lovDetPage" class="tab-pane fadein active" style="border:none !important;"> 
                                    <div class="row">
                                        <div  class="col-md-6" style="padding:0px 3px 0px 3px !important;">
                                            <fieldset class="basic_person_fs" style="padding:10px 3px 0px 3px !important;"> 
                                                <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                    <label for="lovDetLovNm" class="control-label col-lg-4">Value List Name:</label>
                                                    <div  class="col-lg-8">
                                                        <input type="text" class="form-control" aria-label="..." id="lovDetLovNm" name="lovDetLovNm" value="" style="width:100%;">
                                                        <input type="hidden" class="form-control" aria-label="..." id="lovDetLovID" name="lovDetLovID" value="">
                                                    </div>
                                                </div>
                                                <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                    <label for="lovDetLovDesc" class="control-label col-lg-4">Value List Description:</label>
                                                    <div  class="col-lg-8">
                                                        <textarea class="form-control" aria-label="..." id="lovDetLovDesc" name="lovDetLovDesc" style="width:100%;" cols="3" rows="4"></textarea>
                                                    </div>
                                                </div>
                                            </fieldset>
                                        </div>
                                        <div  class="col-md-6" style="padding:0px 3px 0px 3px !important;">
                                            <fieldset class = "basic_person_fs">
                                                <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                    <label for="lovDetDfndBy" class="control-label col-lg-6">Defined By:</label>
                                                    <div class="col-lg-6">
                                                        <select data-placeholder="Select..." class="form-control chosen-select" id="lovDetDfndBy">
                                                            <?php
                                                            $valslctdArry = array("");
                                                            $srchInsArrys = array("USR");
                                                            for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                                ?>
                                                                <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                    <label for="lovDetIsDynmc" class="control-label col-lg-6">Is Dynamic?:</label>
                                                    <div class="col-lg-6">
                                                        <?php
                                                        $chkdYes = "";
                                                        $chkdNo = "checked=\"\"";
                                                        ?>
                                                        <label class="radio-inline"><input type="radio" name="lovDetIsDynmc" value="YES" <?php echo $chkdYes; ?>>YES</label>
                                                        <label class="radio-inline"><input type="radio" name="lovDetIsDynmc" value="NO" <?php echo $chkdNo; ?>>NO</label>

                                                    </div>
                                                </div>
                                                <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                    <label for="lovDetIsEnabled" class="control-label col-lg-6">Is Enabled?:</label>
                                                    <div class="col-lg-6">
                                                        <?php
                                                        $chkdYes = "";
                                                        $chkdNo = "checked=\"\"";
                                                        ?>
                                                        <label class="radio-inline"><input type="radio" name="lovDetIsEnabled" value="YES" <?php echo $chkdYes; ?>>YES</label>
                                                        <label class="radio-inline"><input type="radio" name="lovDetIsEnabled" value="NO" <?php echo $chkdNo; ?>>NO</label>
                                                    </div>
                                                </div>
                                                <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                    <label for="lovDetOrdrByCls" class="control-label col-lg-6">Order By Clause:</label>
                                                    <div  class="col-lg-6">
                                                        <input type="text" class="form-control" aria-label="..." id="lovDetOrdrByCls" name="lovDetOrdrByCls" value="ORDER BY 1 ASC" style="width:100%;">
                                                    </div>
                                                </div>
                                            </fieldset>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div  class="col-md-12" style="padding:0px 3px 0px 3px !important;">
                                            <fieldset class="basic_person_fs" style="padding:10px 3px 0px 3px !important;"> 
                                                <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                    <label for="lovDetSqlQry" class="control-label col-lg-2">SQL Query if Dynamic:</label>
                                                    <div  class="col-lg-10">
                                                        <textarea class="form-control" aria-label="..." id="lovDetSqlQry" name="lovDetSqlQry" style="width:100%;" cols="9" rows="10"></textarea>
                                                    </div>
                                                </div>
                                            </fieldset>
                                        </div>
                                    </div>
                                </div>                               
                            </div>                        
                        </div>                         
                    </div>                
                </div>       


                <?php
            } else if ($vwtyp == 3) {
                //echo "Lov Possible Values";
                $lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 30;
                $curIdx = 0;
                $pkID = isset($_POST['sbmtdLovID']) ? $_POST['sbmtdLovID'] : -1;
                if ($pkID > 0) {
                    $sbmtdLovID = $pkID;
                    $total = get_TtlLovsPssblVals($srchFor, $srchIn, $pkID);
                    if ($pageNo > ceil($total / $lmtSze)) {
                        $pageNo = 1;
                    } else if ($pageNo < 1) {
                        $pageNo = ceil($total / $lmtSze);
                    }

                    $curIdx = $pageNo - 1;
                    $result1 = get_LovsPssblVals($srchFor, $srchIn, $curIdx, $lmtSze, $pkID);
                    $colClassType1 = "col-lg-2";
                    $colClassType2 = "col-lg-3";
                    $colClassType3 = "col-lg-4";
                    $isdynmc = getGnrlRecNm("gst.gen_stp_lov_names", "value_list_id", "is_list_dynamic", $pkID);
                    ?>
                    <form id='psblValsForm' action='' method='post' accept-charset='UTF-8'> 
                        <div class="row">
                            <?php
                            if ($canEdtGst === true && $isdynmc != "1") {
                                $nwRowHtml = urlencode("<tr id=\"psblValsRow__WWW123WWW\">"
                                        . "<td class=\"lovtd\"><span class=\"normaltd\">New</span></td>"
                                        . "<td class=\"lovtd\">
                                              <div class=\"form-group form-group-sm\" style=\"width:100% !important;margin-bottom:0px !important;\">
                                                            <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"psblValsRow_WWW123WWW_PValNm\" value=\"\" style=\"width:100% !important;\">
                                                            <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"psblValsRow_WWW123WWW_PValID\" value=\"\">
                                              </div>
                                          </td>
                                          <td class=\"lovtd\">                                          
                                              <div class=\"form-group form-group-sm\" style=\"width:100% !important;margin-bottom:0px !important;\">
                                                            <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"psblValsRow_WWW123WWW_PValDesc\" value=\"\" style=\"width:100% !important;\">
                                              </div>
                                          </td>
                                          <td class=\"lovtd\"> 
                                              <div class=\"form-group form-group-sm\" style=\"width:100% !important;margin-bottom:0px !important;\">
                                                            <div class=\"form-check\" style=\"font-size: 12px !important;\">
                                                                <label class=\"form-check-label\">
                                                                    <input type=\"checkbox\" class=\"form-check-input\" id=\"psblValsRow_WWW123WWW_IsEnabled\" name=\"psblValsRow_WWW123WWW_IsEnabled\">
                                                                </label>
                                                            </div>
                                              </div>
                                           </td>
                                           <td class=\"lovtd\">
                                              <div class=\"form-group form-group-sm\" style=\"width:100% !important;margin-bottom:0px !important;\">
                                                            <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"psblValsRow_WWW123WWW_AlwdOrgs\" value=\"\" style=\"width:100% !important;\">
                                              </div>
                                          </td>
                                        </tr>");
                                ?> 
                                <div class="<?php echo $colClassType1; ?>" style="padding:0px 1px 0px 15px !important;">     
                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="insertNewRowBe4('psblValsTable', 0, '<?php echo $nwRowHtml; ?>');" data-toggle="tooltip" data-placement="bottom" title="New Possible Value">
                                        <img src="cmn_images/add1-64.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                    </button>
                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="savePsblValsForm();" data-toggle="tooltip" data-placement="bottom" title="Save Possible Values">
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
                            <div class="<?php echo $colClassType3; ?>" style="padding:0px 15px 0px 15px !important;">
                                <div class="input-group">
                                    <input class="form-control" id="psblValsSrchFor" type = "text" placeholder="Search For" value="<?php echo trim(str_replace("%", " ", $srchFor)); ?>" onkeyup="enterKeyFuncPsblVals(event, '', '#lovPsblValsPage', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&sbmtdLovID=<?php echo $sbmtdLovID; ?>');">
                                    <input id="psblValsPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getAllPsblVals('clear', '#lovPsblValsPage', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&sbmtdLovID=<?php echo $sbmtdLovID; ?>');">
                                        <span class="glyphicon glyphicon-remove"></span>
                                    </label>
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getAllPsblVals('', '#lovPsblValsPage', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&sbmtdLovID=<?php echo $sbmtdLovID; ?>');">
                                        <span class="glyphicon glyphicon-search"></span>
                                    </label> 
                                </div>
                            </div>
                            <div class="<?php echo $colClassType3; ?>">
                                <div class="input-group">
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="psblValsSrchIn">
                                        <?php
                                        $valslctdArry = array("", "");
                                        $srchInsArrys = array("Value", "Description");

                                        for ($z = 0; $z < count($srchInsArrys); $z++) {
                                            if ($srchIn == $srchInsArrys[$z]) {
                                                $valslctdArry[$z] = "selected";
                                            }
                                            ?>
                                            <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                        <?php } ?>
                                    </select>
                                    <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="psblValsDsplySze" style="min-width:70px !important;">                            
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
                                <nav aria-label="Page navigation">
                                    <ul class="pagination" style="margin: 0px !important;">
                                        <li>
                                            <a class="rhopagination" href="javascript:getAllPsblVals('previous', '#lovPsblValsPage', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&sbmtdLovID=<?php echo $sbmtdLovID; ?>');" aria-label="Previous">
                                                <span aria-hidden="true">&laquo;</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a class="rhopagination" href="javascript:getAllPsblVals('next', '#lovPsblValsPage', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&sbmtdLovID=<?php echo $sbmtdLovID; ?>');" aria-label="Next">
                                                <span aria-hidden="true">&raquo;</span>
                                            </a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                        <div class="row"> 
                            <div  class="col-md-12">
                                <table class="table table-striped table-bordered table-responsive" id="psblValsTable" cellspacing="0" width="100%" style="width:100%;min-width: 600px !important;">
                                    <thead>
                                        <tr>
                                            <th style="max-width:30px;width:30px;">No.</th>
                                            <th style="min-width:300px;">Possible Value</th>
                                            <th style="min-width:310px;">Possible Value Description</th>
                                            <th style="max-width:50px;width:50px;text-align: center;">Enabled?</th>
                                            <th style="max-width:50px;width:50px;">Allowed Org IDs</th>
                                            <?php
                                            if ($canDelGst === true && $isdynmc != "1") {
                                                ?>
                                                <th style="max-width:30px;width:30px;">&nbsp;</th>
                                            <?php } ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $cntr = 0;
                                        while ($row1 = loc_db_fetch_array($result1)) {
                                            $cntr += 1;
                                            ?>
                                            <tr id="psblValsRow_<?php echo $cntr; ?>">                                    
                                                <td class="lovtd"><span class="normaltd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></span></td>
                                                <td class="lovtd">
                                                    <?php if ($canEdtGst === true && $isdynmc != "1") { ?>
                                                        <div class="form-group form-group-sm" style="width:100% !important;margin-bottom:0px !important;">
                                                            <input type="text" class="form-control" aria-label="..." id="psblValsRow<?php echo $cntr; ?>_PValNm" value="<?php echo $row1[2]; ?>" style="width:100% !important;">
                                                            <input type="hidden" class="form-control" aria-label="..." id="psblValsRow<?php echo $cntr; ?>_PValID" value="<?php echo $row1[0]; ?>">
                                                        </div>
                                                    <?php } else { ?>
                                                        <span><?php echo $row1[2]; ?></span>
                                                    <?php } ?>                                                         
                                                </td>                                                
                                                <td class="lovtd">
                                                    <?php if ($canEdtGst === true && $isdynmc != "1") { ?>
                                                        <div class="form-group form-group-sm" style="width:100% !important;margin-bottom:0px !important;">
                                                            <input type="text" class="form-control" aria-label="..." id="psblValsRow<?php echo $cntr; ?>_PValDesc" value="<?php echo $row1[3]; ?>" style="width:100% !important;">
                                                        </div>
                                                    <?php } else { ?>
                                                        <span><?php echo $row1[3]; ?></span>
                                                    <?php } ?>                                                         
                                                </td>
                                                <td class="lovtd" style="max-width:50px;width:50px;text-align: center;">
                                                    <?php
                                                    $isChkd = "";
                                                    if ($row1[4] == "1") {
                                                        $isChkd = "checked=\"true\"";
                                                    }
                                                    if ($canEdtGst === true && $isdynmc != "1") {
                                                        ?>
                                                        <div class="form-group form-group-sm" style="width:100% !important;margin-bottom:0px !important;">
                                                            <div class="form-check" style="font-size: 12px !important;">
                                                                <label class="form-check-label">
                                                                    <input type="checkbox" class="form-check-input" id="psblValsRow<?php echo $cntr; ?>_IsEnabled" name="psblValsRow<?php echo $cntr; ?>_IsEnabled" <?php echo $isChkd ?>>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    <?php } else { ?>
                                                        <span><?php echo ($row1[4] == "1" ? "Yes" : "No"); ?></span>
                                                    <?php } ?>                                                         
                                                </td>
                                                <td class="lovtd">
                                                    <?php if ($canEdtGst === true && $isdynmc != "1") { ?>
                                                        <div class="form-group form-group-sm" style="width:100% !important;margin-bottom:0px !important;">
                                                            <input type="text" class="form-control" aria-label="..." id="psblValsRow<?php echo $cntr; ?>_AlwdOrgs" value="<?php echo $row1[5]; ?>" style="width:100% !important;">
                                                        </div>
                                                    <?php } else { ?>
                                                        <span><?php echo $row1[5]; ?></span>
                                                    <?php } ?>                                                         
                                                </td>
                                                <?php
                                                if ($canDelGst === true && $isdynmc != "1") {
                                                    ?>
                                                    <td class="lovtd">
                                                        <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delPsblValue('psblValsRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Possible Value">
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
                    </form>
                    <?php
                }
            } else if ($vwtyp == 4) {
                
            }
        }
    }
}    