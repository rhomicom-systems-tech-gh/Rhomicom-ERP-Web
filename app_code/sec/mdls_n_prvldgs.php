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
                echo $cntent . "<li>
						<span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                                <span style=\"text-decoration:none;\">Modules List</span>
					</li>
                                       </ul>
                                     </div>";
                $total = get_AllMdlsTtl($srchFor, $srchIn);
                if ($pageNo > ceil($total / $lmtSze)) {
                    $pageNo = 1;
                } else if ($pageNo < 1) {
                    $pageNo = ceil($total / $lmtSze);
                }

                $curIdx = $pageNo - 1;
                $result = get_AllMdls($srchFor, $srchIn, $curIdx, $lmtSze, $sortBy);
                $cntr = 0;
                $colClassType1 = "col-lg-2";
                $colClassType2 = "col-lg-3";
                ?>
                <form id='allMdlsForm' action='' method='post' accept-charset='UTF-8'>
                    <div class="row rhoRowMargin">
                        <div class="col-md-5" style="padding:0px 15px 0px 15px !important;">
                            <div class="input-group">
                                <input class="form-control" id="allMdlsSrchFor" name="allMdlsSrchFor" type = "text" placeholder="Search For" value="<?php echo trim(str_replace("%",
                                " ", $srchFor)); ?>" onkeyup="enterKeyFuncAllMdls(event, '', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=0')">
                                <input id="allMdlsPageNo" name="allMdlsPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                <label class="btn btn-primary btn-file input-group-addon" onclick="getAllMdls('clear', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=0')">
                                    <span class="glyphicon glyphicon-remove"></span>
                                </label>
                                <label class="btn btn-primary btn-file input-group-addon" onclick="getAllMdls('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=0')">
                                    <span class="glyphicon glyphicon-search"></span>
                                </label> 
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="input-group">
                                <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                <!--<select data-placeholder="Select..." class="form-control chosen-select" id="allMdlsSrchIn">
                                <?php
                                $valslctdArry = array("", "", "");
                                $srchInsArrys = array("Role Name", "Priviledge Name", "Owner Module");

                                for ($z = 0; $z < count($srchInsArrys); $z++) {
                                    if ($srchIn == $srchInsArrys[$z]) {
                                        $valslctdArry[$z] = "selected";
                                    }
                                    ?>
                                                                                <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                <?php } ?>
                                </select>-->
                                <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                <select data-placeholder="Select..." class="form-control chosen-select" id="allMdlsDsplySze" name="allMdlsDsplySze" style="min-width:70px !important;">                            
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

                        <div class="col-md-3">
                            <div class="input-group">                        
                                <span class="input-group-addon"><span class="glyphicon glyphicon-sort-by-attributes"></span></span>
                                <select data-placeholder="Select..." class="form-control chosen-select" id="allMdlsSortBy" name="allMdlsSortBy">
                                    <?php
                                    $valslctdArry = array("", "", "");
                                    $srchInsArrys = array("Module Name", "Module ID");
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
                                        <a class="rhopagination" href="javascript:getAllMdls('previous', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=0');" aria-label="Previous">
                                            <span aria-hidden="true">&laquo;</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="rhopagination" href="javascript:getAllMdls('next', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=0');" aria-label="Next">
                                            <span aria-hidden="true">&raquo;</span>
                                        </a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                    <div class="row"> 
                        <div  class="col-md-12">
                            <table class="table table-striped table-bordered table-responsive" id="allMdlsTable" cellspacing="0" width="100%" style="width:100%;min-width: 800px;">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Module Name</th>
                                        <th>Module Description</th>
                                        <th>Date Added</th>
                                        <th>Audit Trail Table Name</th>
                                        <th>...</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    while ($row = loc_db_fetch_array($result)) {
                                        $cntr += 1;
                                        ?>
                                        <tr id="allMdlsEdtRow_<?php echo $cntr; ?>">                                    
                                            <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
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
                                                <span><?php echo $row[4]; ?></span>                                                       
                                            </td>
                                            <td class="lovtd">
                                                <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Details" onclick="getOneMdlForm('myFormsModalLg', 'myFormsModalBodyLg', 'myFormsModalTitleLg', 'mdlPrvldgsForm', 'View/Edit Priviledges for Module (<?php echo $row[1]; ?>)', <?php echo $row[0]; ?>, 1, <?php echo $pgNo; ?>, 'clear');" style="padding:2px !important;" style="padding:2px !important;">
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
                $pkID = isset($_POST['sbmtdMdlID']) ? $_POST['sbmtdMdlID'] : -1;
                ?>
                <form id='mdlPrvldgsForm' action='' method='post' accept-charset='UTF-8'>                   
                    <?php
                    $total = get_AllMdls_PrvldgsTtl($srchFor, $srchIn, $pkID);
                    if ($pageNo > ceil($total / $lmtSze)) {
                        $pageNo = 1;
                    } else if ($pageNo < 1) {
                        $pageNo = ceil($total / $lmtSze);
                    }

                    $curIdx = $pageNo - 1;
                    $result1 = get_AllMdls_Prvldgs($srchFor, $srchIn, $curIdx, $lmtSze, $pkID, $sortBy);
                    $colClassType1 = "col-lg-2";
                    $colClassType2 = "col-lg-4";
                    ?>
                    <div class="row">
                        <fieldset class=""><!--<legend class="basic_person_lg">Priviledges for Role</legend>-->
                            <div class="row">
                                <div class="col-md-5" style="padding:0px 15px 0px 15px !important;">
                                    <div class="input-group">
                                        <input class="form-control" id="mdlPrvldgsSrchFor" type = "text" placeholder="Search For" value="<?php echo trim(str_replace("%",
                                    " ", $srchFor)); ?>" onkeyup="enterKeyFuncOneMdl(event, 'myFormsModalLg', 'myFormsModalBodyLg', 'myFormsModalTitleLg', 'mdlPrvldgsForm', '', <?php echo $pkID; ?>, <?php echo $vwtyp; ?>, <?php echo $pgNo; ?>, '');">
                                        <input id="mdlPrvldgsPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getOneMdlForm('myFormsModalLg', 'myFormsModalBodyLg', 'myFormsModalTitleLg', 'mdlPrvldgsForm', '', <?php echo $pkID; ?>, <?php echo $vwtyp; ?>, <?php echo $pgNo; ?>, 'clear');">
                                            <span class="glyphicon glyphicon-remove"></span>
                                        </label>
                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getOneMdlForm('myFormsModalLg', 'myFormsModalBodyLg', 'myFormsModalTitleLg', 'mdlPrvldgsForm', '', <?php echo $pkID; ?>, <?php echo $vwtyp; ?>, <?php echo $pgNo; ?>, '');">
                                            <span class="glyphicon glyphicon-search"></span>
                                        </label> 
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="input-group">
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                        <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                        <select data-placeholder="Select..." class="form-control chosen-select" id="mdlPrvldgsDsplySze" style="min-width:70px !important;">                            
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
                                <div class="col-md-3">
                                    <div class="input-group">                        
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-sort-by-attributes"></span></span>
                                        <select data-placeholder="Select..." class="form-control chosen-select" id="mdlPrvldgsSortBy">
                                            <?php
                                            $valslctdArry = array("", "", "");
                                            $srchInsArrys = array("Priviledge Name");
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
                                                <a class="rhopagination" href="javascript:getOneMdlForm('myFormsModalLg', 'myFormsModalBodyLg', 'myFormsModalTitleLg', 'mdlPrvldgsForm', '', <?php echo $pkID; ?>, <?php echo $vwtyp; ?>, <?php echo $pgNo; ?>,'previous');" aria-label="Previous">
                                                    <span aria-hidden="true">&laquo;</span>
                                                </a>
                                            </li>
                                            <li>
                                                <a class="rhopagination" href="javascript:getOneMdlForm('myFormsModalLg', 'myFormsModalBodyLg', 'myFormsModalTitleLg', 'mdlPrvldgsForm', '', <?php echo $pkID; ?>, <?php echo $vwtyp; ?>, <?php echo $pgNo; ?>,'next');" aria-label="Next">
                                                    <span aria-hidden="true">&raquo;</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </nav>
                                </div>
                            </div>
                            <div class="row"> 
                                <div  class="col-md-12">
                                    <table class="table table-striped table-bordered table-responsive" id="mdlPrvldgsTable" cellspacing="0" width="100%" style="width:100%;min-width: 800px !important;">
                                        <thead>
                                            <tr>
                                                <th>No.</th>
                                                <th>Priviledge Name</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $cntr = 0;
                                            while ($row1 = loc_db_fetch_array($result1)) {
                                                $cntr += 1;
                                                ?>
                                                <tr id="mdlPrvldgsEdtRow_<?php echo $cntr; ?>">                                    
                                                    <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                                    <td class="lovtd"><span><?php echo $row1[0]; ?></span></td>
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
                
            } else if ($vwtyp == 3) {
                
            } else if ($vwtyp == 4) {
                
            }
        }
    }
}