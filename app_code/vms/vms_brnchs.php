<?php
$pageNo = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
$lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 30;
$sortBy = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : "";

$qStrtDte = substr($gnrlTrnsDteDMYHMS, 0, 11) . " 00:00:00";
$qEndDte = substr($gnrlTrnsDteDMYHMS, 0, 11) . " 23:59:59";

if (isset($_POST['qStrtDte'])) {
    $qStrtDte = cleanInputData($_POST['qStrtDte']);
    if (strlen($qStrtDte) >= 11) {
        $qStrtDte = substr($qStrtDte, 0, 11) . " 00:00:00";
    } else {
        $qStrtDte = date('d-M-Y') . " 00:00:00";
    }
}

if (isset($_POST['qEndDte'])) {
    $qEndDte = cleanInputData($_POST['qEndDte']);
    if (strlen($qEndDte) >= 11) {
        $qEndDte = substr($qEndDte, 0, 11) . " 23:59:59";
    } else {
        $qEndDte = date('d-M-Y') . " 23:59:59";
    }
}

$prsnid = $_SESSION['PRSN_ID'];
$orgID = $_SESSION['ORG_ID'];
if (array_key_exists('lgn_num', get_defined_vars())) {
    if ($lgn_num > 0 && $canview === true) {
        if ($qstr == "DELETE") {
            if ($actyp == 1) {
                
            }
        } else if ($qstr == "UPDATE") {
            if ($actyp == 1) {
                
            }
        } else {
            if ($vwtyp == 0) {
                $canAddLoc = test_prmssns($dfltPrvldgs[22], $mdlNm);
                $canEdtLoc = test_prmssns($dfltPrvldgs[23], $mdlNm);
                $canDelLoc = test_prmssns($dfltPrvldgs[24], $mdlNm);
                $pkID = -1;
                if ($srcMenu == "Banking") {
                    $cntent .= "<li onclick=\"openATab('#allmodules', 'grp=17&typ=1');\">
                                        <span style=\"text-decoration:none;\">Banking & Microfinance Menu</span>
                                </li>
                                <li onclick=\"openATab('#allmodules', 'grp=$group&typ=$type&srcMenu=$srcMenu');\">
                                        <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                        <span style=\"text-decoration:none;\">VMS Menu</span>
                                </li>";
                } else {
                    $cntent .= "
                                <li onclick=\"openATab('#allmodules', 'grp=$group&typ=$type&srcMenu=$srcMenu');\">
                                        <span style=\"text-decoration:none;\">VMS Menu</span>
                                </li>";
                }
                echo $cntent . "<li onclick=\"openATab('#allmodules', 'grp=$group&typ=$type&pg=1&vtyp=0&srcMenu=$srcMenu');\">
                                    <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                    <span style=\"text-decoration:none;\">Branches/Sites/Locations</span>
				</li>
                               </ul>
                              </div>"; //get_site_code_desc "Branch"
                $total = get_SitesLocsTtl($orgID, $prsnid, $srchFor, $srchIn, "");
                if ($pageNo > ceil($total / $lmtSze)) {
                    $pageNo = 1;
                } else if ($pageNo < 1) {
                    $pageNo = ceil($total / $lmtSze);
                }
                $curIdx = $pageNo - 1;
                //$curIdx = 0;
                //$lmtSze = 500;
                $result = get_SitesLocs($orgID, $prsnid, $srchFor, $srchIn, $curIdx, $lmtSze, "");
                $cntr = 0;
                $colClassType3 = "col-lg-4";
                $colClassType1 = "col-lg-2";
                $colClassType2 = "col-lg-3";
                ?>
                <form id='allVmsBrnchsForm' action='' method='post' accept-charset='UTF-8'>
                    <div class="row rhoRowMargin">
                        <?php
                        if ($canAddLoc === true) {
                            ?> 
                            <div class="<?php echo $colClassType2; ?>" style="padding:0px 1px 0px 1px !important;"> 
                                <div class="col-md-12">
                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="getOneVmsBrnchsForm(-1, 'ShowDialog', 'FROMBRNCH');" style="width:100% !important;">
                                        <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                        New Branch
                                    </button>
                                </div>
                            </div>
                            <?php
                        } else {
                            $colClassType1 = "col-lg-3";
                            $colClassType2 = "col-lg-5";
                            $colClassType3 = "col-lg-4";
                        }
                        ?>
                        <div class="<?php echo $colClassType2; ?>" style="padding:0px 15px 0px 15px !important;">
                            <div class="input-group">
                                <input class="form-control" id="allVmsBrnchsSrchFor" type = "text" placeholder="Search For" value="<?php
                                echo trim(str_replace("%", " ", $srchFor));
                                ?>" onkeyup="enterKeyFuncAllVmsBrnchs(event, '', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&srcMenu=<?php echo $srcMenu; ?>')">
                                <input id="allVmsBrnchsPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                <label class="btn btn-primary btn-file input-group-addon" onclick="getAllVmsBrnchs('clear', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&srcMenu=<?php echo $srcMenu; ?>')">
                                    <span class="glyphicon glyphicon-remove"></span>
                                </label>
                                <label class="btn btn-primary btn-file input-group-addon" onclick="getAllVmsBrnchs('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&srcMenu=<?php echo $srcMenu; ?>')">
                                    <span class="glyphicon glyphicon-search"></span>
                                </label> 
                            </div>
                        </div>
                        <div class="<?php echo $colClassType3; ?>">
                            <div class="input-group">
                                <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                <select data-placeholder="Select..." class="form-control chosen-select" id="allVmsBrnchsSrchIn">
                                    <?php
                                    $valslctdArry = array("", "", "");
                                    $srchInsArrys = array("Site Name", "Site Description");

                                    for ($z = 0; $z < count($srchInsArrys); $z++) {
                                        if ($srchIn == $srchInsArrys[$z]) {
                                            $valslctdArry[$z] = "selected";
                                        }
                                        ?>
                                        <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                    <?php } ?>
                                </select>
                                <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                <select data-placeholder="Select..." class="form-control chosen-select" id="allVmsBrnchsDsplySze" style="min-width:70px !important;">                            
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
                                        <a class="rhopagination" href="javascript:getAllVmsBrnchs('previous', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&srcMenu=<?php echo $srcMenu; ?>');" aria-label="Previous">
                                            <span aria-hidden="true">&laquo;</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="rhopagination" href="javascript:getAllVmsBrnchs('next', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&srcMenu=<?php echo $srcMenu; ?>');" aria-label="Next">
                                            <span aria-hidden="true">&raquo;</span>
                                        </a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                    <div class="row"  style="padding:1px 15px 1px 15px !important;"><hr style="margin:1px 0px 3px 0px;"></div>
                    <div class="row" style="padding:0px 15px 0px 15px !important">
                        <div class="col-md-12">
                            <fieldset><legend class="basic_person_lg1">Branches/Agencies</legend>
                                <?php
                                $grpcntr = 0;
                                while ($row = loc_db_fetch_array($result)) {
                                    if ($grpcntr == 0) {
                                        ?>
                                        <div class="row">
                                            <?php
                                        }
                                        ?>
                                        <div class="col-md-3">
                                            <button type="button" class="btn btn-default btn-lg btn-block" style="min-height:115px;height:113px;margin-bottom:5px;" onclick="openATab('#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=1&vtyp=1&sbmtdSiteID=<?php echo $row[0]; ?>&srcMenu=<?php echo $srcMenu; ?>');">
                                                <img src="cmn_images/vault_folder_icon.png" style="margin:5px auto; height:40px; width:auto; position: relative; vertical-align: middle;float:none;">
                                                <br/>
                                                <span class="wordwrap3"><?php echo $row[1]; ?></span>
                                                <br/>&nbsp;
                                            </button>
                                        </div>
                                        <?php
                                        if ($grpcntr == 3) {
                                            ?>
                                        </div>
                                        <?php
                                        $grpcntr = 0;
                                    } else {
                                        $grpcntr = $grpcntr + 1;
                                    }
                                }
                                ?>
                            </fieldset>
                        </div>
                        <!--<div class="col-md-6">
                            <fieldset><legend class="basic_person_lg1">Agencies</legend>
                        <?php
                        $curIdx = 0;
                        $lmtSze = 500;
                        $result = get_SitesLocs($orgID, $prsnid, $srchFor, $srchIn, $curIdx, $lmtSze, "Agency");
                        $cntr = 0;
                        $grpcntr = 0;
                        while ($row = loc_db_fetch_array($result)) {
                            if ($grpcntr == 0) {
                                ?>
                                                                                                                                                                                                                                <div class="row">
                                <?php
                            }
                            ?>
                                                                                                                                                                            <div class="col-md-6">
                                                                                                                                                                                <button type="button" class="btn btn-default btn-lg btn-block" style="min-height:115px;height:113px;margin-bottom:5px;" onclick="openATab('#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=1&vtyp=1&sbmtdSiteID=<?php echo $row[0]; ?>&srcMenu=<?php echo $srcMenu; ?>');">
                                                                                                                                                                                    <img src="cmn_images/vault_folder_icon.png" style="margin:5px auto; height:40px; width:auto; position: relative; vertical-align: middle;float:none;">
                                                                                                                                                                                    <br/>
                                                                                                                                                                                    <span class="wordwrap3"><?php echo $row[1]; ?></span>
                                                                                                                                                                                    <br/>&nbsp;
                                                                                                                                                                                </button>
                                                                                                                                                                            </div>
                            <?php
                            if ($grpcntr == 1) {
                                ?>
                                                                                                                                                                                                                                </div>
                                <?php
                                $grpcntr = 0;
                            } else {
                                $grpcntr = $grpcntr + 1;
                            }
                        }
                        ?>
                            </fieldset>
                        </div>-->
                    </div>                
                </form>
                <?php
            } else if ($vwtyp == 1) {
//Vaults Under Branches
                $canAddVlt = test_prmssns($dfltPrvldgs[25], $mdlNm);
                $canEdtVlt = test_prmssns($dfltPrvldgs[26], $mdlNm);
                $canDelVlt = test_prmssns($dfltPrvldgs[27], $mdlNm);
                $pkID = isset($_POST['sbmtdSiteID']) ? $_POST['sbmtdSiteID'] : -1;
                if ($srcMenu == "Banking") {
                    $cntent .= "
                                <li onclick=\"openATab('#allmodules', 'grp=17&typ=1');\">
                                        <span style=\"text-decoration:none;\">Banking & Microfinance Menu</span>
                                </li>
                                <li onclick=\"openATab('#allmodules', 'grp=$group&typ=$type&srcMenu=$srcMenu');\">
                                        <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                        <span style=\"text-decoration:none;\">VMS Menu</span>
                                </li>";
                } else {
                    $cntent .= "
                                <li onclick=\"openATab('#allmodules', 'grp=$group&typ=$type&srcMenu=$srcMenu');\">
                                        <span style=\"text-decoration:none;\">VMS Menu</span>
                                </li>";
                }
                echo $cntent . "<li onclick=\"openATab('#allmodules', 'grp=$group&typ=$type&pg=1&vtyp=0&srcMenu=$srcMenu');\">
                                    <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                    <span style=\"text-decoration:none;\">Branches/Sites/Locations</span>
				</li>
                                <li>
                                    <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                    <span style=\"text-decoration:none;\">Vaults</span>
				</li>
                               </ul>
                              </div>";
                $total = get_SitesVaultsTtl($pkID, $prsnid, $srchFor, $srchIn);
                if ($pageNo > ceil($total / $lmtSze)) {
                    $pageNo = 1;
                } else if ($pageNo < 1) {
                    $pageNo = ceil($total / $lmtSze);
                }

                $curIdx = $pageNo - 1;
                $result = get_SitesVaults($pkID, $prsnid, $srchFor, $srchIn, $curIdx, $lmtSze);
                $cntr = 0;
                $colClassType3 = "col-lg-4";
                $colClassType1 = "col-lg-2";
                $colClassType2 = "col-lg-3";
                ?>
                <form id='allBrchVaultsForm' action='' method='post' accept-charset='UTF-8'>
                    <div class="row rhoRowMargin">
                        <?php
                        if ($canAddVlt === true) {
                            ?> 
                            <div class="<?php echo $colClassType2; ?>" style="padding:0px 1px 0px 1px !important;"> 
                                <div class="col-md-12">
                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="getOneVmsVltsForm(-1, 'ShowDialog', 'FROMBRNCH', <?php echo $pkID; ?>);" style="width:100% !important;">
                                        <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                        New Vault
                                    </button>
                                </div>
                            </div>
                            <?php
                        } else {
                            $colClassType1 = "col-lg-3";
                            $colClassType2 = "col-lg-5";
                            $colClassType3 = "col-lg-4";
                        }
                        ?>
                        <div class="<?php echo $colClassType2; ?>" style="padding:0px 15px 0px 15px !important;">
                            <div class="input-group">
                                <input class="form-control" id="allBrchVaultsSrchFor" type = "text" placeholder="Search For" value="<?php
                                echo trim(str_replace("%", " ", $srchFor));
                                ?>" onkeyup="enterKeyFuncAllBrchVaults(event, '', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&sbmtdSiteID=<?php echo $pkID; ?>&srcMenu=<?php echo $srcMenu; ?>')">
                                <input id="allBrchVaultsPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                <label class="btn btn-primary btn-file input-group-addon" onclick="getAllBrchVaults('clear', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&sbmtdSiteID=<?php echo $pkID; ?>&srcMenu=<?php echo $srcMenu; ?>')">
                                    <span class="glyphicon glyphicon-remove"></span>
                                </label>
                                <label class="btn btn-primary btn-file input-group-addon" onclick="getAllBrchVaults('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&sbmtdSiteID=<?php echo $pkID; ?>&srcMenu=<?php echo $srcMenu; ?>')">
                                    <span class="glyphicon glyphicon-search"></span>
                                </label> 
                            </div>
                        </div>
                        <div class="<?php echo $colClassType3; ?>">
                            <div class="input-group">
                                <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                <select data-placeholder="Select..." class="form-control chosen-select" id="allBrchVaultsSrchIn">
                                    <?php
                                    $valslctdArry = array("", "", "");
                                    $srchInsArrys = array("Vault Name", "Vault Description");

                                    for ($z = 0; $z < count($srchInsArrys); $z++) {
                                        if ($srchIn == $srchInsArrys[$z]) {
                                            $valslctdArry[$z] = "selected";
                                        }
                                        ?>
                                        <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                    <?php } ?>
                                </select>
                                <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                <select data-placeholder="Select..." class="form-control chosen-select" id="allBrchVaultsDsplySze" style="min-width:70px !important;">                            
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
                                        <a class="rhopagination" href="javascript:getAllBrchVaults('previous', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&sbmtdSiteID=<?php echo $pkID; ?>&srcMenu=<?php echo $srcMenu; ?>');" aria-label="Previous">
                                            <span aria-hidden="true">&laquo;</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="rhopagination" href="javascript:getAllBrchVaults('next', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&sbmtdSiteID=<?php echo $pkID; ?>&srcMenu=<?php echo $srcMenu; ?>');" aria-label="Next">
                                            <span aria-hidden="true">&raquo;</span>
                                        </a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                    <div class="row"  style="padding:1px 15px 1px 15px !important;"><hr style="margin:1px 0px 3px 0px;"></div>
                    <div class="row" style="padding:0px 15px 0px 15px !important">
                        <?php
                        $grpcntr = 0;
                        while ($row = loc_db_fetch_array($result)) {
                            if ($grpcntr == 0) {
                                ?>
                                <div class="row">
                                    <?php
                                }
                                ?>
                                <div class="col-md-3 colmd3special2">
                                    <button type="button" class="btn btn-default btn-lg btn-block" style="min-height:145px;height:143px;margin-bottom:5px;" onclick="openATab('#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=1&vtyp=2&sbmtdStoreID=<?php echo $row[0]; ?>&sbmtdSiteID=<?php echo $pkID; ?>&srcMenu=<?php echo $srcMenu; ?>');">
                                        <img src="cmn_images/secure_icon.png" style="margin:5px auto; height:70px; width:auto; position: relative; vertical-align: middle;float:none;">
                                        <br/>
                                        <span class="wordwrap3"><?php echo $row[1]; ?></span>
                                        <br/>&nbsp;
                                    </button>
                                </div>
                                <?php
                                if ($grpcntr == 3) {
                                    ?>
                                </div>
                                <?php
                                $grpcntr = 0;
                            } else {
                                $grpcntr = $grpcntr + 1;
                            }
                        }
                        ?>
                    </div>
                </form>
                <?php
            } else if ($vwtyp == 2) {
//Cages Under Vaults
                $canAddCage = test_prmssns($dfltPrvldgs[28], $mdlNm);
                $canEdtCage = test_prmssns($dfltPrvldgs[29], $mdlNm);
                $canDelCage = test_prmssns($dfltPrvldgs[30], $mdlNm);
                $sbmtdSiteID = isset($_POST['sbmtdSiteID']) ? $_POST['sbmtdSiteID'] : -1;
                $pkID = isset($_POST['sbmtdStoreID']) ? $_POST['sbmtdStoreID'] : -1;
                if ($srcMenu == "Banking") {
                    $cntent .= "
                                <li onclick=\"openATab('#allmodules', 'grp=17&typ=1');\">
                                        <span style=\"text-decoration:none;\">Banking & Microfinance Menu</span>
                                </li>
                                <li onclick=\"openATab('#allmodules', 'grp=$group&typ=$type&srcMenu=$srcMenu');\">
                                        <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                        <span style=\"text-decoration:none;\">VMS Menu</span>
                                </li>";
                } else {
                    $cntent .= "
                                <li onclick=\"openATab('#allmodules', 'grp=$group&typ=$type&srcMenu=$srcMenu');\">
                                        <span style=\"text-decoration:none;\">VMS Menu</span>
                                </li>";
                }
                echo $cntent . "<li onclick=\"openATab('#allmodules', 'grp=$group&typ=$type&pg=1&vtyp=0&srcMenu=$srcMenu');\">
                                    <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                    <span style=\"text-decoration:none;\">Branches/Sites/Locations</span>
				</li>
                                <li onclick=\"openATab('#allmodules', 'grp=$group&typ=$type&pg=1&vtyp=1&sbmtdSiteID=$sbmtdSiteID&srcMenu=$srcMenu');\">
                                    <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                    <span style=\"text-decoration:none;\">Vaults</span>
				</li>
                                <li>
                                    <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                    <span style=\"text-decoration:none;\">Cages</span>
				</li>
                               </ul>
                              </div>";
                $total = get_VaultCagesTtl($pkID, $prsnid, $srchFor, $srchIn);
                if ($pageNo > ceil($total / $lmtSze)) {
                    $pageNo = 1;
                } else if ($pageNo < 1) {
                    $pageNo = ceil($total / $lmtSze);
                }

                $curIdx = $pageNo - 1;
                $result = get_VaultCages($pkID, $prsnid, $srchFor, $srchIn, $curIdx, $lmtSze);
                $cntr = 0;
                $colClassType3 = "col-lg-4";
                $colClassType1 = "col-lg-2";
                $colClassType2 = "col-lg-3";
                ?>
                <form id='allVaultCagesForm' action='' method='post' accept-charset='UTF-8'>
                    <div class="row rhoRowMargin">
                        <?php
                        if ($canAddCage === true) {
                            ?> 
                            <div class="<?php echo $colClassType2; ?>" style="padding:0px 1px 0px 1px !important;"> 
                                <div class="col-md-12">
                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="getOneVmsCgFormV(-1, 'branches',<?php echo $pkID; ?>,<?php echo $sbmtdSiteID; ?>);" style="width:100% !important;">
                                        <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                        New Cage
                                    </button>
                                </div>
                            </div>
                            <?php
                        } else {
                            $colClassType1 = "col-lg-3";
                            $colClassType2 = "col-lg-5";
                            $colClassType3 = "col-lg-4";
                        }
                        ?>
                        <div class="<?php echo $colClassType2; ?>" style="padding:0px 15px 0px 15px !important;">
                            <div class="input-group">
                                <input class="form-control" id="allVaultCagesSrchFor" type = "text" placeholder="Search For" value="<?php
                                echo trim(str_replace("%", " ", $srchFor));
                                ?>" onkeyup="enterKeyFuncAllVaultCages(event, '', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&sbmtdStoreID=<?php echo $pkID; ?>&sbmtdSiteID=<?php echo $sbmtdSiteID; ?>&srcMenu=<?php echo $srcMenu; ?>')">
                                <input id="allVaultCagesPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                <label class="btn btn-primary btn-file input-group-addon" onclick="getAllVaultCages('clear', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&sbmtdStoreID=<?php echo $pkID; ?>&sbmtdSiteID=<?php echo $sbmtdSiteID; ?>&srcMenu=<?php echo $srcMenu; ?>')">
                                    <span class="glyphicon glyphicon-remove"></span>
                                </label>
                                <label class="btn btn-primary btn-file input-group-addon" onclick="getAllVaultCages('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&sbmtdStoreID=<?php echo $pkID; ?>&sbmtdSiteID=<?php echo $sbmtdSiteID; ?>&srcMenu=<?php echo $srcMenu; ?>')">
                                    <span class="glyphicon glyphicon-search"></span>
                                </label> 
                            </div>
                        </div>
                        <div class="<?php echo $colClassType3; ?>">
                            <div class="input-group">
                                <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                <select data-placeholder="Select..." class="form-control chosen-select" id="allVaultCagesSrchIn">
                                    <?php
                                    $valslctdArry = array("", "", "");
                                    $srchInsArrys = array("Cage Name", "Cage Description");

                                    for ($z = 0; $z < count($srchInsArrys); $z++) {
                                        if ($srchIn == $srchInsArrys[$z]) {
                                            $valslctdArry[$z] = "selected";
                                        }
                                        ?>
                                        <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                    <?php } ?>
                                </select>
                                <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                <select data-placeholder="Select..." class="form-control chosen-select" id="allVaultCagesDsplySze" style="min-width:70px !important;">                            
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
                                        <a class="rhopagination" href="javascript:getAllVaultCages('previous', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&sbmtdStoreID=<?php echo $pkID; ?>&sbmtdSiteID=<?php echo $sbmtdSiteID; ?>&srcMenu=<?php echo $srcMenu; ?>');" aria-label="Previous">
                                            <span aria-hidden="true">&laquo;</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="rhopagination" href="javascript:getAllVaultCages('next', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&sbmtdStoreID=<?php echo $pkID; ?>&sbmtdSiteID=<?php echo $sbmtdSiteID; ?>&srcMenu=<?php echo $srcMenu; ?>');" aria-label="Next">
                                            <span aria-hidden="true">&raquo;</span>
                                        </a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                    <div class="row"  style="padding:1px 15px 1px 15px !important;"><hr style="margin:1px 0px 3px 0px;"></div>
                    <div class="row" style="padding:0px 15px 0px 15px !important">
                        <?php
                        $grpcntr = 0;
                        while ($row = loc_db_fetch_array($result)) {
                            if ($grpcntr == 0) {
                                ?>
                                <div class="row">
                                    <?php
                                }
                                ?>
                                <div class="col-md-3 colmd3special2">
                                    <button type="button" class="btn btn-default btn-lg btn-block" style="min-height:145px;height:143px;margin-bottom:5px;" onclick="openATab('#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=1&vtyp=3&sbmtdShelfID=<?php echo $row[0]; ?>&sbmtdSiteID=<?php echo $sbmtdSiteID; ?>&sbmtdStoreID=<?php echo $pkID; ?>&srcMenu=<?php echo $srcMenu; ?>');">
                                        <img src="cmn_images/safe-icon.png" style="margin:5px auto; height:70px; width:auto; position: relative; vertical-align: middle;float:none;">
                                        <br/>
                                        <span class="wordwrap3"><?php echo $row[1]; ?></span>
                                        <br/>&nbsp;
                                    </button>
                                </div>
                                <?php
                                if ($grpcntr == 3) {
                                    ?>
                                </div>
                                <?php
                                $grpcntr = 0;
                            } else {
                                $grpcntr = $grpcntr + 1;
                            }
                        }
                        ?>
                    </div>
                </form>
                <?php
            } else if ($vwtyp == 3) {
//Items & Latest Qty Left >> Cage Item Bin Card Display Right
//New Transfer|New Deposit|New Withdrawal|Misc Receipt|Mics Issue (Prompt to Select Transaction Type)  
                /* $pageNo = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
                  $lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 1;
                  $sortBy = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : ""; */
                $canAddTrns = test_prmssns($dfltPrvldgs[37], $mdlNm);
                $canEdtTrns = test_prmssns($dfltPrvldgs[38], $mdlNm);
                $canDelTrns = test_prmssns($dfltPrvldgs[39], $mdlNm);
                $pkID = isset($_POST['sbmtdShelfID']) ? $_POST['sbmtdShelfID'] : -1;
                $sbmtdSiteID = isset($_POST['sbmtdSiteID']) ? $_POST['sbmtdSiteID'] : -1;
                $sbmtdStoreID = isset($_POST['sbmtdStoreID']) ? $_POST['sbmtdStoreID'] : -1;
                $cageID = -1;
                $invAssetAcntID = -1;
                $isFrmBnkng = isset($_POST['isFrmBnkng']) ? (int) cleanInputData($_POST['isFrmBnkng']) : 0;
                $shdAutoUpdt = isset($_POST['shdAutoUpdt']) ? (int) cleanInputData($_POST['shdAutoUpdt']) : 0;
                if ($isFrmBnkng <= 0 || $isFrmBnkng == 2) {
                    if ($srcMenu == "Banking") {
                        $cntent .= "
                                <li onclick=\"openATab('#allmodules', 'grp=17&typ=1');\">
                                        <span style=\"text-decoration:none;\">Banking & Microfinance Menu</span>
                                </li>
                                <li onclick=\"openATab('#allmodules', 'grp=$group&typ=$type&srcMenu=$srcMenu');\">
                                        <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                        <span style=\"text-decoration:none;\">VMS Menu</span>
                                </li>";
                    } else {
                        $cntent .= "
                                <li onclick=\"openATab('#allmodules', 'grp=$group&typ=$type&srcMenu=$srcMenu');\">
                                        <span style=\"text-decoration:none;\">VMS Menu</span>
                                </li>";
                    }
                    if ($isFrmBnkng == 2) {
                        $sbmtdCageID = isset($_POST['sbmtdCageID']) ? $_POST['sbmtdCageID'] : -1;
                        if ($pkID <= 0 && $sbmtdCageID <= 0) {
                            $pkID = getLatestCage($prsnid, $cageID, $sbmtdStoreID, $invAssetAcntID);
                            $sbmtdSiteID = getLatestSiteID($prsnid);
                        } else if ($sbmtdCageID > 0) {
                            $pkID = $sbmtdCageID;
                            $sbmtdStoreID = (float) getGnrlRecNm("inv.inv_shelf", "line_id", "store_id", $sbmtdCageID);
                        }
                        echo $cntent . "
                                <li onclick=\"openATab('#allmodules', 'grp=$group&typ=$type&pg=1&vtyp=1&sbmtdSiteID=$sbmtdSiteID&srcMenu=$srcMenu');\">
                                    <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                    <span style=\"text-decoration:none;\">Vaults</span>
				</li>
                                <li onclick=\"openATab('#allmodules', 'grp=$group&typ=$type&pg=1&vtyp=2&sbmtdSiteID=$sbmtdSiteID&sbmtdStoreID=$sbmtdStoreID&srcMenu=$srcMenu');\">
                                    <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                    <span style=\"text-decoration:none;\">Cages</span>
				</li>
                                <li onclick=\"openATab('#allmodules', 'grp=17&typ=1&pg=3');\">
						<span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                                <span style=\"text-decoration:none;\"> Core Banking Menu</span>
                                </li>
                                <li onclick=\"openATab('#allmodules', 'grp=17&typ=1&q=SUBMENUS&pg=3&subPgNo=3.1');\">
                                                <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
						<span style=\"text-decoration:none;\">Teller Operations</span>
                                </li>
                                <li>
                                    <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                    <span style=\"text-decoration:none;\">Cash Exchange/Transfers</span>
				</li>
                               </ul>
                              </div>";
                    } else {
                        echo $cntent . "<li onclick=\"openATab('#allmodules', 'grp=$group&typ=$type&pg=1&vtyp=0&srcMenu=$srcMenu');\">
                                    <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                    <span style=\"text-decoration:none;\">Branches/Sites/Locations</span>
				</li>
                                <li onclick=\"openATab('#allmodules', 'grp=$group&typ=$type&pg=1&vtyp=1&sbmtdSiteID=$sbmtdSiteID&srcMenu=$srcMenu');\">
                                    <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                    <span style=\"text-decoration:none;\">Vaults</span>
				</li>
                                <li onclick=\"openATab('#allmodules', 'grp=$group&typ=$type&pg=1&vtyp=2&sbmtdSiteID=$sbmtdSiteID&sbmtdStoreID=$sbmtdStoreID&srcMenu=$srcMenu');\">
                                    <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                    <span style=\"text-decoration:none;\">Cages</span>
				</li>
                                <li>
                                    <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                    <span style=\"text-decoration:none;\">Bin Card Information</span>
				</li>
                               </ul>
                              </div>";
                    }
                } else {
                    $sbmtdCageID = isset($_POST['sbmtdCageID']) ? $_POST['sbmtdCageID'] : -1;
                    if ($pkID <= 0 && $sbmtdCageID <= 0) {
                        $pkID = getLatestCage($prsnid, $cageID, $sbmtdStoreID, $invAssetAcntID);
                        $sbmtdSiteID = getLatestSiteID($prsnid);
                    } else if ($sbmtdCageID > 0) {
                        $pkID = $sbmtdCageID;
                        $sbmtdStoreID = (float) getGnrlRecNm("inv.inv_shelf", "line_id", "store_id", $sbmtdCageID);
                    }
                }
                if ($cageID <= 0) {
                    $cageID = $pkID;
                }
                $vltCageNm = getGnrlRecNm("inv.inv_itm_subinventories", "subinv_id", "subinv_name", $sbmtdStoreID) . " - " .
                        getGnrlRecNm("inv.inv_shelf", "line_id", "shelve_name", $cageID) . "";
                $total = get_CageItemsTtl($pkID, $srchFor, $srchIn);
                if ($pageNo > ceil($total / $lmtSze)) {
                    $pageNo = 1;
                } else if ($pageNo < 1) {
                    $pageNo = ceil($total / $lmtSze);
                }
                $curIdx = $pageNo - 1;
                $result = get_CageItems($pkID, $srchFor, $srchIn, $curIdx, $lmtSze);
                $cntr = 0;
                $colClassType3 = "col-lg-4";
                $colClassType1 = "col-lg-2";
                $colClassType2 = "col-lg-3";
                $itemID = -1;
                $rsltAll = get_SmmryCageItems($pkID);
                ?>
                <form id='allCageItemsForm' action='' method='post' accept-charset='UTF-8'>
                    <div class="row">
                        <div class="col-md-2" style="padding:0px 1px 0px 15px;">
                            <!--<button type="button" class="btn btn-default btn-lg btn-block" style="min-height:245px;height:243px;margin-bottom:5px;width: 100% !important;min-width: 100% !important;" onclick="">
                                <img src="cmn_images/safe-icon.png" style="margin:5px; height:50px; width:auto; position: relative; vertical-align: top;float:none;">
                                <span class="wordwrap3">Cage Position</span>
                                <br/>&nbsp;
                            </button>-->
                            <table class="table table-striped table-bordered table-responsive rhoBootsTable" id="allCagePositionTable" cellspacing="0" width="100%" style="width:100%;">
                                <caption>Overall Cage Position</caption>
                                <thead>
                                    <tr>		
                                        <th>UNIT</th>
                                        <th style="text-align:right;">Total Value</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $ttlPrce = 0;
                                    while ($rowAll = loc_db_fetch_array($rsltAll)) {
                                        $cntr += 1;
                                        $ttlPrce += (float) $rowAll[8];
                                        ?>
                                        <tr id="allCagePosRow_<?php echo $cntr; ?>"> 
                                            <td class="lovtd">
                                                <?php echo $rowAll[2]; ?>
                                                <input type="hidden" id="allCagePosRow<?php echo $cntr; ?>_ItemID" name="allCagePosRow<?php echo $cntr; ?>_ItemID" value="<?php echo $rowAll[0]; ?>">
                                                <input type="hidden" id="allCagePosRow<?php echo $cntr; ?>_CageID" name="allCagePosRow<?php echo $cntr; ?>_CageID" value="<?php echo $pkID; ?>">
                                            </td>
                                            <td class="lovtd" style="text-align:right;"><?php echo /* $rowAll[17] . " " . */number_format((float) $rowAll[8], 2); ?></td>                                          
                                        </tr>
                                        <?php
                                    }
                                    $cntr = 0;
                                    ?>
                                </tbody>
                                <tfoot>                                                            
                                    <tr>
                                        <th>TOTALS:</th>
                                        <th style="text-align: right;">
                                            <?php
                                            echo "<span style=\"color:blue;\">" . number_format($ttlPrce, 2, '.', ',') . "</span>";
                                            ?>
                                        </th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <div class="col-md-10">                           
                            <ul class="nav nav-tabs rho-hideable-tabs" style="margin-top:1px !important;">
                                <li class="active"><a data-toggle="tab" data-rhodata="&pg=1&vtyp=3&isFrmBnkng=<?php echo $isFrmBnkng; ?>&sbmtdShelfID=<?php echo $pkID; ?>&sbmtdSiteID=<?php echo $sbmtdSiteID; ?>&sbmtdStoreID=<?php echo $sbmtdStoreID; ?>&srcMenu=<?php echo $srcMenu; ?>" href="#cagePostn" id="cagePostntab">Position</a></li>
                                <li><a data-toggle="tabajxcage" data-rhodata="&pg=2&vtyp=38&isFrmBnkng=<?php echo $isFrmBnkng; ?>&sbmtdShelfID=<?php echo $pkID; ?>&sbmtdSiteID=<?php echo $sbmtdSiteID; ?>&sbmtdStoreID=<?php echo $sbmtdStoreID; ?>&srcMenu=<?php echo $srcMenu; ?>" href="#cageTrns" id="cageTrnstab">All Transactions</a></li>
                            </ul>                            
                            <div class="custDiv" style="padding:0px !important;"> 
                                <div class="tab-content" style="padding:5px !important;padding-top:7px !important;">
                                    <div id="cagePostn" class="tab-pane fadein active" style="border:none !important;padding:0px !important;"> 
                                        <div class="row rhoRowMargin"> 
                                            <?php
                                            $colClassType1 = "col-lg-2";
                                            $colClassType2 = "col-lg-3";
                                            $colClassType3 = "col-lg-4";
                                            ?>
                                            <div class="<?php echo $colClassType2; ?>" style="padding:0px 15px 0px 15px !important;">
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-default btn-sm" style="height: 30px !important;" onclick="">
                                                        <img src="cmn_images/pdf.png" style="left: 0.5%; padding-right: 5px; height:17px; width:20px; position: relative; vertical-align: middle;">
                                                        Cage Actions &AMP; Reports
                                                    </button>
                                                    <button class="btn btn-info dropdown-toggle btn-sm" style="height:30px !important;" type="button" data-toggle="dropdown">
                                                        <span class="caret"></span>
                                                    </button>
                                                    <ul class="dropdown-menu" role="menu" style="margin-left: 15px !important;">
                                                        <?php
                                                        $cageRslt = null;
                                                        if ($isFrmBnkng <= 0) {
                                                            $cageRslt = getMyVmsCages($prsnid, $sbmtdSiteID);
                                                        } else {
                                                            $cageRslt = getMyLnkdVmsCages($prsnid);
                                                        }
                                                        while ($rowCg = loc_db_fetch_array($cageRslt)) {
                                                            $cageID = (int) $rowCg[2];
                                                            $vltID = (int) $rowCg[1];
                                                            $invAssetAcntID = (int) $rowCg[9];
                                                            $cageNm = $rowCg[3];
                                                            if ($isFrmBnkng <= 0 || $isFrmBnkng == 2) {
                                                                ?>  
                                                                <li>
                                                                    <a href="javascript:getAllCageItems('', '#allmodules', 'grp=25&typ=1&pg=1&vtyp=3&isFrmBnkng=<?php echo $isFrmBnkng; ?>&sbmtdShelfID=<?php echo $cageID; ?>&sbmtdSiteID=<?php echo $sbmtdSiteID; ?>&sbmtdStoreID=<?php echo $vltID; ?>&srcMenu=<?php echo $srcMenu; ?>', function (slctr, linkArgs) {
                                                                    <?php if ($isFrmBnkng <= 0 || $isFrmBnkng == 2) { ?>
                                                                           openATab(slctr, linkArgs);
                                                                           <?php
                                                                       } else {
                                                                           ?>
                                                                           chckMyTillPos('ReloadDialog', linkArgs);
                                                                       <?php }
                                                                       ?>
                                                                       });">
                                                                        <img src="cmn_images/teller1.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                        My Till/Drawer (<?php echo $cageNm; ?>)
                                                                    </a>
                                                                </li>
                                                                <?php
                                                            } else {
                                                                ?>  
                                                                <li>
                                                                    <a href="javascript:chckMyTillPos('ReloadDialog','grp=25&typ=1&pg=1&vtyp=3&isFrmBnkng=<?php echo $isFrmBnkng; ?>&sbmtdShelfID=<?php echo $cageID; ?>&sbmtdSiteID=<?php echo $sbmtdSiteID; ?>&sbmtdStoreID=<?php echo $vltID; ?>&srcMenu=<?php echo $srcMenu; ?>');">
                                                                        <img src="cmn_images/teller1.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                        My Till/Drawer (<?php echo $cageNm; ?>)
                                                                    </a>
                                                                </li>
                                                                <?php
                                                            }
                                                            $reportTitle = "Teller's Call Over Report";
                                                            $reportName = "Teller's Call Over Report";
                                                            $rptID = getRptID($reportName);
                                                            $prmID1 = getParamIDUseSQLRep("{:P_CAGE_ID}", $rptID);
                                                            $prmID2 = getParamIDUseSQLRep("{:P_FROM_DATE}", $rptID);
                                                            $prmID3 = getParamIDUseSQLRep("{:P_TO_DATE}", $rptID);
                                                            $prmID4 = getParamIDUseSQLRep("{:documentTitle}", $rptID);
                                                            $paramRepsNVals = $prmID1 . "~" . $cageID . "|" . $prmID2 . "~" . substr($qStrtDte, 0, 11) . "|" . $prmID3 . "~" . substr($qEndDte, 0, 11) . "|" . $prmID4 . "~" . $reportTitle . "|-190~PDF";
                                                            $paramStr = urlencode(str_replace("'", "\'", $paramRepsNVals));

                                                            $reportTitle1 = "Tellers Cash Transactions Report";
                                                            $reportName1 = "Tellers Cash Transactions Report";
                                                            $rptID1 = getRptID($reportName1);
                                                            $prmID11 = getParamIDUseSQLRep("{:P_CAGE_ID}", $rptID1);
                                                            $prmID21 = getParamIDUseSQLRep("{:P_FROM_DATE}", $rptID1);
                                                            $prmID31 = getParamIDUseSQLRep("{:P_TO_DATE}", $rptID1);
                                                            $prmID41 = getParamIDUseSQLRep("{:documentTitle}", $rptID1);
                                                            $paramRepsNVals1 = $prmID11 . "~" . $cageID . "|" . $prmID21 . "~" . substr($qStrtDte, 0, 11) . "|" . $prmID31 . "~" . substr($qEndDte, 0, 11) . "|" . $prmID41 . "~" . $reportTitle1 . "|-190~PDF";
                                                            $paramStr1 = urlencode(str_replace("'", "\'", $paramRepsNVals1));

                                                            $reportName2 = "VMS Bin Card Report";
                                                            $reportTitle2 = $reportName2;
                                                            $rptID2 = getRptID($reportName2);
                                                            $prmID12 = getParamIDUseSQLRep("{:P_CAGE_ID}", $rptID2);
                                                            $prmID22 = getParamIDUseSQLRep("{:P_ITM_ID}", $rptID2);
                                                            $prmID32 = getParamIDUseSQLRep("{:P_FROM_DATE}", $rptID2);
                                                            $prmID42 = getParamIDUseSQLRep("{:P_TO_DATE}", $rptID2);
                                                            $paramRepsNVals2 = $prmID12 . "~" . $cageID . "|" . $prmID22 . "~-1|" . $prmID32 . "~" . substr($qStrtDte, 0, 11) . "|" . $prmID42 . "~" . substr($qEndDte, 0, 11) . "|-130~" . $reportTitle2 . "|-190~PDF";
                                                            $paramStr2 = urlencode(str_replace("'", "\'", $paramRepsNVals2));
                                                            ?>  
                                                            <li>
                                                                <a href="javascript:getSilentRptsRnSts(<?php echo $rptID; ?>, -1, '<?php echo $paramStr; ?>');">
                                                                    <img src="cmn_images/pdf.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                    My Call Over Report (<?php echo $cageNm; ?>)
                                                                </a>
                                                            </li> 
                                                            <li>
                                                                <a href="javascript:getSilentRptsRnSts(<?php echo $rptID1; ?>, -1, '<?php echo $paramStr1; ?>');">
                                                                    <img src="cmn_images/pdf.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                    My Cash Transactions Report (<?php echo $cageNm; ?>)
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a href="javascript:getSilentRptsRnSts(<?php echo $rptID2; ?>, -1, '<?php echo $paramStr2; ?>');">
                                                                    <img src="cmn_images/pdf.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                    My Bin Card Report (<?php echo $cageNm; ?>)
                                                                </a>
                                                            </li>                                                            
                                                            <?php
                                                        }
                                                        if ($canAddTrns === true && ($isFrmBnkng <= 0 || $isFrmBnkng == 2)) {
                                                            $trnsType = "Direct Cage/Shelve Transaction";
                                                            $trnsType2 = "Teller/Cashier Transfers";
                                                            ?>
                                                            <li>
                                                                <a href="javascript:getOneVmsTrnsForm(-1, '<?php echo $trnsType; ?>', 34, 'ShowDialog',3, '', '<?php echo $srcMenu; ?>', <?php echo $isFrmBnkng; ?>, <?php echo $pkID; ?>, <?php echo $sbmtdSiteID; ?>, <?php echo $sbmtdStoreID; ?>);">
                                                                    <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                    New Direct Cage Transaction
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a href="javascript:getOneVmsTrnsForm(-1, '<?php echo $trnsType2; ?>', 20, 'ShowDialog',3, '', '<?php echo $srcMenu; ?>', <?php echo $isFrmBnkng; ?>, <?php echo $pkID; ?>, <?php echo $sbmtdSiteID; ?>, <?php echo $sbmtdStoreID; ?>);">
                                                                    <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                    New Vault/Cage Transfer
                                                                </a>
                                                            </li>
                                                        <?php }
                                                        ?>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="<?php echo $colClassType2; ?>" style="padding:0px 15px 0px 15px !important;">
                                                <div class="input-group">
                                                    <input class="form-control" id="allCageItemsSrchFor" type = "text" placeholder="Search For" value="<?php
                                                    echo trim(str_replace("%", " ", $srchFor));
                                                    ?>" onkeyup="enterKeyFuncAllCageItems(event, '', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&isFrmBnkng=<?php echo $isFrmBnkng; ?>&sbmtdShelfID=<?php echo $pkID; ?>&sbmtdSiteID=<?php echo $sbmtdSiteID; ?>&sbmtdStoreID=<?php echo $sbmtdStoreID; ?>&srcMenu=<?php echo $srcMenu; ?>', function (slctr, linkArgs) {
                                                           <?php if ($isFrmBnkng <= 0 || $isFrmBnkng == 2) { ?>
                                                                                    openATab(slctr, linkArgs);
                                                               <?php
                                                           } else {
                                                               ?>
                                                                                    chckMyTillPos('ReloadDialog', linkArgs);
                                                           <?php } ?>
                                                                            });">
                                                    <input id="allCageItemsPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getAllCageItems('clear', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&isFrmBnkng=<?php echo $isFrmBnkng; ?>&sbmtdShelfID=<?php echo $pkID; ?>&sbmtdSiteID=<?php echo $sbmtdSiteID; ?>&sbmtdStoreID=<?php echo $sbmtdStoreID; ?>&srcMenu=<?php echo $srcMenu; ?>', function (slctr, linkArgs) {
                                                    <?php if ($isFrmBnkng <= 0 || $isFrmBnkng == 2) { ?>
                                                                                    openATab(slctr, linkArgs);
                                                        <?php
                                                    } else {
                                                        ?>
                                                                                    chckMyTillPos('ReloadDialog', linkArgs);
                                                    <?php } ?>
                                                                            });">
                                                        <span class="glyphicon glyphicon-remove"></span>
                                                    </label>
                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getAllCageItems('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&isFrmBnkng=<?php echo $isFrmBnkng; ?>&sbmtdShelfID=<?php echo $pkID; ?>&sbmtdSiteID=<?php echo $sbmtdSiteID; ?>&sbmtdStoreID=<?php echo $sbmtdStoreID; ?>&srcMenu=<?php echo $srcMenu; ?>', function (slctr, linkArgs) {
                                                    <?php if ($isFrmBnkng <= 0 || $isFrmBnkng == 2) { ?>
                                                                                    openATab(slctr, linkArgs);
                                                        <?php
                                                    } else {
                                                        ?>
                                                                                    chckMyTillPos('ReloadDialog', linkArgs);
                                                    <?php }
                                                    ?>
                                                                            });">
                                                        <span class="glyphicon glyphicon-search"></span>
                                                    </label> 
                                                </div>
                                            </div>
                                            <div class="<?php echo $colClassType3; ?>">
                                                <div class="input-group">
                                                    <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                                    <select data-placeholder="Select..." class="form-control chosen-select" id="allCageItemsSrchIn">
                                                        <?php
                                                        $valslctdArry = array("", "");
                                                        $srchInsArrys = array("Item/Denomination", "Money Type");

                                                        for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                            if ($srchIn == $srchInsArrys[$z]) {
                                                                $valslctdArry[$z] = "selected";
                                                            }
                                                            ?>
                                                            <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                    <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                                    <select data-placeholder="Select..." class="form-control chosen-select" id="allCageItemsDsplySze" style="min-width:70px !important;">                            
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
                                                            <a class="rhopagination" href="javascript:getAllCageItems('previous', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&isFrmBnkng=<?php echo $isFrmBnkng; ?>&sbmtdShelfID=<?php echo $pkID; ?>&sbmtdSiteID=<?php echo $sbmtdSiteID; ?>&sbmtdStoreID=<?php echo $sbmtdStoreID; ?>&srcMenu=<?php echo $srcMenu; ?>', function (slctr, linkArgs) {
                                                            <?php if ($isFrmBnkng <= 0 || $isFrmBnkng == 2) { ?>
                                                                   openATab(slctr, linkArgs);
                                                                   <?php
                                                               } else {
                                                                   ?>
                                                                   chckMyTillPos('ReloadDialog', linkArgs);
                                                               <?php }
                                                               ?>
                                                               });" aria-label="Previous">
                                                                <span aria-hidden="true">&laquo;</span>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="rhopagination" href="javascript:getAllCageItems('next', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&isFrmBnkng=<?php echo $isFrmBnkng; ?>&sbmtdShelfID=<?php echo $pkID; ?>&sbmtdSiteID=<?php echo $sbmtdSiteID; ?>&sbmtdStoreID=<?php echo $sbmtdStoreID; ?>&srcMenu=<?php echo $srcMenu; ?>', function (slctr, linkArgs) {
                                                            <?php if ($isFrmBnkng <= 0 || $isFrmBnkng == 2) { ?>
                                                                   openATab(slctr, linkArgs);
                                                                   <?php
                                                               } else {
                                                                   ?>
                                                                   chckMyTillPos('ReloadDialog', linkArgs);
                                                               <?php }
                                                               ?>
                                                               });" aria-label="Next">
                                                                <span aria-hidden="true">&raquo;</span>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </nav>
                                            </div>
                                        </div>
                                        <div class="row" style="padding:1px 15px 1px 15px !important;"><hr style="margin:1px 0px 3px 0px;"></div>
                                        <div class="row" style="padding:0px 15px 0px 15px !important">
                                            <div class="col-md-12" style="padding:0px 1px 0px 1px !important">
                                                <div class="col-md-12" style="padding:0px 0px 0px 0px !important">
                                                    <table class="table table-striped table-bordered table-responsive rhoBootsTable" id="allCageItemsTable" cellspacing="0" width="100%" style="width:100%;">
                                                        <caption>Cage Items Detail Information <span style="color:blueviolet;font-weight: bold;font-size:16px;">[<?php echo $vltCageNm; ?>]</span></caption>
                                                        <thead>
                                                            <tr>
                                                                <!--<th>No.</th>-->		
                                                                <th>Denomination</th>
                                                                <th style="text-align:right;">Pieces</th>
                                                                <th>UoM</th>
                                                                <th style="text-align:right;">Unit Value</th>
                                                                <th style="text-align:right;">Total Value</th>
                                                                <th>Money Type</th>
                                                                <th>Vault</th>
                                                                <th>Cage</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            while ($row = loc_db_fetch_array($result)) {
                                                                /**/
                                                                if ($cntr == 0) {
                                                                    $itemID = (int) $row[3];
                                                                    $cageID = (int) $row[2];
                                                                }
                                                                $cntr += 1;
                                                                ?>
                                                                <tr id="allCageItemsRow_<?php echo $cntr; ?>" class="hand_cursor">                                    
                                                                    <!--<td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>-->
                                                                    <td class="lovtd">
                                                                        <?php echo $row[8]; ?>
                                                                        <input type="hidden" id="allCageItemsRow<?php echo $cntr; ?>_ItemID" name="allCageItemsRow<?php echo $cntr; ?>_ItemID" value="<?php echo $row[3]; ?>">
                                                                        <input type="hidden" id="allCageItemsRow<?php echo $cntr; ?>_CageID" name="allCageItemsRow<?php echo $cntr; ?>_CageID" value="<?php echo $row[2]; ?>">
                                                                    </td>
                                                                    <td class="lovtd" style="text-align:right;color:green;font-weight:bold;">
                                                                        <?php
                                                                        echo number_format((float) $row[4], 0);
                                                                        ?>
                                                                    </td>
                                                                    <td class="lovtd">
                                                                        <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;color:black;font-weight:bold;" onclick="getGnrlUOMBrkdwnForm(-1, 23, <?php echo $row[3]; ?>, <?php echo $row[4]; ?>, '<?php echo $row[17]; ?>');" data-toggle="tooltip" data-placement="bottom" title="View QTY Breakdown"><?php echo $row[14]; ?></button>
                                                                    </td>
                                                                    <td class="lovtd" style="text-align:right;">
                                                                        <?php
                                                                        echo number_format((float) $row[15], 2);
                                                                        ?>
                                                                    </td>
                                                                    <td class="lovtd" style="text-align:right;color:blue;font-weight: bold;">
                                                                        <?php
                                                                        echo $row[17] . " " . number_format((float) $row[18], 2);
                                                                        ?>
                                                                    </td>
                                                                    <td class="lovtd" style="color:red;font-weight:bold;"><?php echo $row[7]; ?></td>
                                                                    <td class="lovtd"><?php echo $row[19]; ?></td>
                                                                    <td class="lovtd"><?php echo $row[20]; ?></td>                                              
                                                                </tr>
                                                                <?php
                                                            }
                                                            ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <div class="col-md-12" style="padding:0px 0px 0px 0px !important">
                                                    <div id="allCageItemsBnCrdDiv">
                                                        <div class="row rhoRowMargin" style="margin-top:3px !Important;">
                                                            <form id='allCageItemTrnsForm' action='' method='post' accept-charset='UTF-8'>
                                                                <?php
                                                                $colClassType0 = "col-lg-3";
                                                                $colClassType2 = "col-lg-5";
                                                                $colClassType3 = "col-lg-4";
                                                                $pageNo = 1;
                                                                $lmtSze = 10;
                                                                $sortBy = "";
                                                                $total = get_CageItemsBnCrdTtl($itemID, $cageID, $srchFor, $srchIn, $qStrtDte, $qEndDte);
                                                                if ($pageNo > ceil($total / $lmtSze)) {
                                                                    $pageNo = 1;
                                                                } else if ($pageNo < 1) {
                                                                    $pageNo = ceil($total / $lmtSze);
                                                                }

                                                                $curIdx = $pageNo - 1;
                                                                $result5 = get_CageItemsBnCrd($itemID, $cageID, $srchFor, $srchIn, $qStrtDte, $qEndDte, $curIdx, $lmtSze);
                                                                $cntr = 0;
                                                                $vwtyp = 5;
                                                                $reportName = "VMS Bin Card Report";
                                                                $reportTitle = $reportName;
                                                                $rptID = getRptID($reportName);
                                                                $prmID1 = getParamIDUseSQLRep("{:P_CAGE_ID}", $rptID);
                                                                $prmID2 = getParamIDUseSQLRep("{:P_ITM_ID}", $rptID);
                                                                $prmID3 = getParamIDUseSQLRep("{:P_FROM_DATE}", $rptID);
                                                                $prmID4 = getParamIDUseSQLRep("{:P_TO_DATE}", $rptID);
                                                                $paramRepsNVals = $prmID1 . "~" . $cageID . "|" . $prmID2 . "~" . $itemID . "|" . $prmID3 . "~" . substr($qStrtDte, 0, 11) . "|" . $prmID4 . "~" . substr($qEndDte, 0, 11) . "|-130~" . $reportTitle . "|-190~PDF";
                                                                $paramStr = urlencode($paramRepsNVals);


                                                                $paramRepsNVals1 = $prmID1 . "~" . $cageID . "|" . $prmID2 . "~-1|" . $prmID3 . "~" . substr($qStrtDte, 0, 11) . "|" . $prmID4 . "~" . substr($qEndDte, 0, 11) . "|-130~" . $reportTitle . "|-190~PDF";
                                                                $paramStr1 = urlencode($paramRepsNVals1);

                                                                $reportName2 = "VMS Summary Bin Card Report";
                                                                $reportTitle2 = $reportName2;
                                                                $rptID2 = getRptID($reportName2);
                                                                $prmID21 = getParamIDUseSQLRep("{:P_CAGE_ID}", $rptID2);
                                                                $prmID23 = getParamIDUseSQLRep("{:P_FROM_DATE}", $rptID2);
                                                                $prmID24 = getParamIDUseSQLRep("{:P_TO_DATE}", $rptID2);
                                                                $paramRepsNVals2 = $prmID21 . "~" . $cageID . "|" . $prmID23 . "~" . substr($qStrtDte, 0, 11) . "|" . $prmID24 . "~" . substr($qEndDte, 0, 11) . "|-130~" . $reportTitle2 . "|-190~PDF";
                                                                $paramStr2 = urlencode($paramRepsNVals2);
                                                                ?>
                                                                <div class="<?php echo $colClassType0; ?>" style="padding:0px 15px 0px 15px !important;">
                                                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="getSilentRptsRnSts(<?php echo $rptID; ?>, -1, '<?php echo $paramStr; ?>');" style="width:100% !important;" data-toggle="tooltip" data-placement="bottom" title="Selected Denomination's Bin Card Report">
                                                                        <img src="cmn_images/pdf-icon-copy.png" style="left: 0.5%; padding-right: 0px; height:20px; width:auto; position: relative; vertical-align: middle;">                                
                                                                    </button>
                                                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="getSilentRptsRnSts(<?php echo $rptID; ?>, -1, '<?php echo $paramStr1; ?>');" style="width:100% !important;" data-toggle="tooltip" data-placement="bottom" title="Overall Cage Bin Card Report">
                                                                        <img src="cmn_images/pdf-icon-copy.png" style="left: 0.5%; padding-right: 0px; height:20px; width:auto; position: relative; vertical-align: middle;">                                
                                                                    </button>
                                                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="getSilentRptsRnSts(<?php echo $rptID2; ?>, -1, '<?php echo $paramStr2; ?>');" style="width:100% !important;" data-toggle="tooltip" data-placement="bottom" title="Summarized Cage Bin Card Report">
                                                                        <img src="cmn_images/pdf-icon-copy.png" style="left: 0.5%; padding-right: 0px; height:20px; width:auto; position: relative; vertical-align: middle;">                                
                                                                    </button>
                                                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="getAllCageItemTrns('', '#allCageItemsBnCrdDiv', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&sbmtdItemID=<?php echo $itemID; ?>&sbmtdCageID=<?php echo $cageID; ?>&srcMenu=<?php echo $srcMenu; ?>&shdAutoUpdt=1');" style="width:100% !important;" data-toggle="tooltip" data-placement="bottom" title="Auto-Correct Wrong Balance if Any">
                                                                        <img src="cmn_images/adjustments.png" style="left: 0.5%; padding-right: 0px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                    </button>
                                                                </div>
                                                                <div class="<?php echo $colClassType3; ?>" style="padding:0px 15px 0px 15px !important;">
                                                                    <div class="input-group">
                                                                        <input class="form-control" id="allCageItemTrnsSrchFor" type = "text" placeholder="Search For" value="<?php
                                                                        echo trim(str_replace("%", " ", $srchFor));
                                                                        ?>" onkeyup="enterKeyFuncAllCageItemTrns(event, '', '#allCageItemsBnCrdDiv', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&sbmtdItemID=<?php echo $itemID; ?>&sbmtdCageID=<?php echo $cageID; ?>&srcMenu=<?php echo $srcMenu; ?>')">
                                                                        <input id="allCageItemTrnsPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getAllCageItemTrns('clear', '#allCageItemsBnCrdDiv', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&sbmtdItemID=<?php echo $itemID; ?>&sbmtdCageID=<?php echo $cageID; ?>&srcMenu=<?php echo $srcMenu; ?>')">
                                                                            <span class="glyphicon glyphicon-remove"></span>
                                                                        </label>
                                                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getAllCageItemTrns('', '#allCageItemsBnCrdDiv', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&sbmtdItemID=<?php echo $itemID; ?>&sbmtdCageID=<?php echo $cageID; ?>&srcMenu=<?php echo $srcMenu; ?>')">
                                                                            <span class="glyphicon glyphicon-search"></span>
                                                                        </label>
                                                                        <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                                                        <select data-placeholder="Select..." class="form-control chosen-select" id="allCageItemTrnsSrchIn"  style="display:none;">
                                                                            <?php
                                                                            $valslctdArry = array("", "", "");
                                                                            $srchInsArrys = array("All", "Description", "Money Type");

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
                                                                <div class="<?php echo $colClassType2; ?>" style="padding:0px 1px 0px 1px !important;">
                                                                    <div class="col-md-6" style="padding:0px 1px 0px 1px !important;">
                                                                        <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                                                            <input class="form-control" size="16" type="text" id="allCageItemTrnsStrtDate" name="allCageItemTrnsStrtDate" value="<?php
                                                                            echo substr($qStrtDte, 0, 11);
                                                                            ?>" placeholder="Start Date">
                                                                            <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                                                            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6" style="padding:0px 15px 0px 1px !important;">
                                                                        <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                                                            <input class="form-control" size="16" type="text"  id="allCageItemTrnsEndDate" name="allCageItemTrnsEndDate" value="<?php
                                                                            echo substr($qEndDte, 0, 11);
                                                                            ?>" placeholder="End Date">
                                                                            <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                                                            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                                        </div>
                                                                    </div>                            
                                                                </div>
                                                            </form>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <table class="table table-striped table-bordered table-responsive rhoBootsTable" id="allCageItemTrnsTable" cellspacing="0" width="100%" style="width:100%;">
                                                                    <caption>Bin Card Transactions</caption>
                                                                    <thead>
                                                                        <tr>
                                                                            <th>No.</th>
                                                                            <th>Transaction Type & Description</th>
                                                                            <th style="text-align:right;">Trns. Qty</th>
                                                                            <th>Base UoM</th>
                                                                            <th style="text-align:right;">Amount Received</th>
                                                                            <th style="text-align:right;">Amount Issued</th>
                                                                            <th style="text-align:right;">Balance After Trns.</th>
                                                                            <th>Money Type</th>
                                                                            <th style="min-width:85px;width:85px;">Trns. Date</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <?php
                                                                        $cntr = 0;
                                                                        $rnngBalance = 0;
                                                                        $firstDate = "";
                                                                        $cntrTtl = loc_db_num_rows($result5);
                                                                        while ($row5 = loc_db_fetch_array($result5)) {
                                                                            $cntr += 1;
                                                                            /* if ($cntr == 1) {
                                                                              $firstDate = substr($row5[18], 0, 11);
                                                                              $prevDate = date('d-M-Y', strtotime($firstDate . ' -1 day'));
                                                                              //echo $firstDate . ":" . $prevDate;
                                                                              $rnngBalance = getStockLstTotBls($row5[4], $row5[6],
                                                                              $row5[1], $row5[17], $prevDate) * ((float) $row5[20]);
                                                                              }
                                                                              $rnngBalance += ((float) $row5[12]) * ((float) $row5[20]);
                                                                              if ($cntr == 1 || $firstDate != substr($row5[18], 0, 10)) {
                                                                              $firstDate = substr($row5[18], 0, 10);
                                                                              $rnngBalance = ((float) $row5[13]) * ((float) $row5[19]);
                                                                              } else {
                                                                              $rnngBalance += ((float) $row5[12]) * ((float) $row5[20]);
                                                                              } */
                                                                            if ($cntr == 1) {
                                                                                $rnngBalance = ((float) $row5[25]);
                                                                            }
                                                                            $rnngBalance += ((float) $row5[12]) * ((float) $row5[20]);
                                                                            if ($cntr == $cntrTtl && $shdAutoUpdt == 1) {
                                                                                execUpdtInsSQL("UPDATE vms.vms_stock_daily_bals
                                                                                    SET stock_tot_qty=(" . $rnngBalance . "/unit_value)
                                                                                  WHERE bals_date='" . $row5[27] . "' and store_vault_id=" . $row5[4]
                                                                                        . " and cage_shelve_id=" . $row5[6]
                                                                                        . " and item_id=" . $row5[1] . "");
                                                                            }
                                                                            ?>
                                                                            <tr id="allCageItemTrnsRow_<?php echo $cntr; ?>">                                    
                                                                                <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                                                                <td class="lovtd">
                                                                                    <?php echo $row5[15] . " " . $row5[16]; ?>
                                                                                    <input type="hidden" id="allCageItemTrnsRow<?php echo $cntr; ?>_TrnsLnID" name="allCageItemTrnsRow<?php echo $cntr; ?>_TrnsLnID" value="<?php echo $row5[0]; ?>">
                                                                                </td>
                                                                                <td class="lovtd" style="text-align:right;color:green;font-weight:bold;"><?php
                                                                                    echo number_format((float) $row5[12], 0);
                                                                                    ?></td>
                                                                                <td class="lovtd" style="">
                                                                                    <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;color:black;font-weight:bold;" onclick="getGnrlUOMBrkdwnForm(<?php echo $row5[0]; ?>, 23, <?php echo $row5[1]; ?>, <?php echo $row5[12]; ?>, '<?php echo $row5[22]; ?>');" data-toggle="tooltip" data-placement="bottom" title="View QTY Breakdown"><?php echo $row5[14]; ?></button>
                                                                                </td>
                                                                                <td class="lovtd" style="text-align:right;color:blue;"><?php
                                                                                    if (((float) $row5[12]) >= 0) {
                                                                                        echo number_format(abs((float) $row5[12]) * ((float) $row5[20]), 2);
                                                                                    } else {
                                                                                        echo "0.00";
                                                                                    }
                                                                                    ?>
                                                                                </td>
                                                                                <td class="lovtd" style="text-align:right;color:blue;"><?php
                                                                                    if (((float) $row5[12]) < 0) {
                                                                                        echo number_format(abs((float) $row5[12]) * ((float) $row5[20]), 2);
                                                                                    } else {
                                                                                        echo "0.00";
                                                                                    }
                                                                                    ?>
                                                                                </td>
                                                                                <td class = "lovtd" style = "text-align:right;color:blue;font-weight:bold;"><?php
                                                                                    echo number_format($rnngBalance, 2);
                                                                                    ?>
                                                                                </td>
                                                                                <td class="lovtd" style="color:red;font-weight:bold;"><?php echo $row5[17]; ?></td>   
                                                                                <td class="lovtd" style="min-width:85px;width:85px;"><?php echo $row5[18]; ?></td>                                                                   
                                                                            </tr>
                                                                            <?php
                                                                        }
                                                                        ?>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>                                    
                                    <div id="cageTrns" class="tab-pane fade" style="border:none !important;padding:0px !important;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                </form>
                <?php
            } else if ($vwtyp == 5) {
                $pageNo = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
                $lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 10;
                $sortBy = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : "";
                $itemID = isset($_POST['sbmtdItemID']) ? (int) cleanInputData($_POST['sbmtdItemID']) : -1;
                $cageID = isset($_POST['sbmtdCageID']) ? (int) cleanInputData($_POST['sbmtdCageID']) : -1;
                $shdAutoUpdt = isset($_POST['shdAutoUpdt']) ? (int) cleanInputData($_POST['shdAutoUpdt']) : 0;
                ?>
                <div class="row rhoRowMargin" style="margin-top:3px !Important;">
                    <form id='allCageItemTrnsForm' action='' method='post' accept-charset='UTF-8'>
                        <?php
                        $colClassType0 = "col-lg-3";
                        $colClassType2 = "col-lg-5";
                        $colClassType3 = "col-lg-4";
                        $total = get_CageItemsBnCrdTtl($itemID, $cageID, $srchFor, $srchIn, $qStrtDte, $qEndDte);
                        if ($pageNo > ceil($total / $lmtSze)) {
                            $pageNo = 1;
                        } else if ($pageNo < 1) {
                            $pageNo = ceil($total / $lmtSze);
                        }

                        $curIdx = $pageNo - 1;
                        $result5 = get_CageItemsBnCrd($itemID, $cageID, $srchFor, $srchIn, $qStrtDte, $qEndDte, $curIdx, $lmtSze);
                        $cntr = 0;
                        $vwtyp = 5;
                        $reportName = "VMS Bin Card Report";
                        $reportTitle = $reportName;
                        $rptID = getRptID($reportName);
                        $prmID1 = getParamIDUseSQLRep("{:P_CAGE_ID}", $rptID);
                        $prmID2 = getParamIDUseSQLRep("{:P_ITM_ID}", $rptID);
                        $prmID3 = getParamIDUseSQLRep("{:P_FROM_DATE}", $rptID);
                        $prmID4 = getParamIDUseSQLRep("{:P_TO_DATE}", $rptID);
                        $paramRepsNVals = $prmID1 . "~" . $cageID . "|" . $prmID2 . "~" . $itemID . "|" . $prmID3 . "~" . substr($qStrtDte, 0, 11) . "|" . $prmID4 . "~" . substr($qEndDte, 0, 11) . "|-130~" . $reportTitle . "|-190~PDF";
                        $paramStr = urlencode($paramRepsNVals);


                        $paramRepsNVals1 = $prmID1 . "~" . $cageID . "|" . $prmID2 . "~-1|" . $prmID3 . "~" . substr($qStrtDte, 0, 11) . "|" . $prmID4 . "~" . substr($qEndDte, 0, 11) . "|-130~" . $reportTitle . "|-190~PDF";
                        $paramStr1 = urlencode($paramRepsNVals1);


                        $reportName2 = "VMS Summary Bin Card Report";
                        $reportTitle2 = $reportName2;
                        $rptID2 = getRptID($reportName2);
                        $prmID21 = getParamIDUseSQLRep("{:P_CAGE_ID}", $rptID2);
                        $prmID23 = getParamIDUseSQLRep("{:P_FROM_DATE}", $rptID2);
                        $prmID24 = getParamIDUseSQLRep("{:P_TO_DATE}", $rptID2);
                        $paramRepsNVals2 = $prmID21 . "~" . $cageID . "|" . $prmID23 . "~" . substr($qStrtDte, 0, 11) . "|" . $prmID24 . "~" . substr($qEndDte, 0, 11) . "|-130~" . $reportTitle2 . "|-190~PDF";
                        $paramStr2 = urlencode($paramRepsNVals2);
                        ?>
                        <div class="<?php echo $colClassType0; ?>" style="padding:0px 15px 0px 15px !important;">
                            <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="getSilentRptsRnSts(<?php echo $rptID; ?>, -1, '<?php echo $paramStr; ?>');" style="width:100% !important;" data-toggle="tooltip" data-placement="bottom" title="Selected Denomination's Bin Card Report">
                                <img src="cmn_images/pdf-icon-copy.png" style="left: 0.5%; padding-right: 0px; height:20px; width:auto; position: relative; vertical-align: middle;">                                
                            </button>
                            <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="getSilentRptsRnSts(<?php echo $rptID; ?>, -1, '<?php echo $paramStr1; ?>');" style="width:100% !important;" data-toggle="tooltip" data-placement="bottom" title="Overall Cage Bin Card Report">
                                <img src="cmn_images/pdf-icon-copy.png" style="left: 0.5%; padding-right: 0px; height:20px; width:auto; position: relative; vertical-align: middle;">                                
                            </button>
                            <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="getSilentRptsRnSts(<?php echo $rptID2; ?>, -1, '<?php echo $paramStr2; ?>');" style="width:100% !important;" data-toggle="tooltip" data-placement="bottom" title="Summarized Cage Bin Card Report">
                                <img src="cmn_images/pdf-icon-copy.png" style="left: 0.5%; padding-right: 0px; height:20px; width:auto; position: relative; vertical-align: middle;">                                
                            </button>
                            <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="getAllCageItemTrns('', '#allCageItemsBnCrdDiv', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&sbmtdItemID=<?php echo $itemID; ?>&sbmtdCageID=<?php echo $cageID; ?>&srcMenu=<?php echo $srcMenu; ?>&shdAutoUpdt=1');" style="width:100% !important;" data-toggle="tooltip" data-placement="bottom" title="Auto-Correct Wrong Balance if Any">
                                <img src="cmn_images/adjustments.png" style="left: 0.5%; padding-right: 0px; height:20px; width:auto; position: relative; vertical-align: middle;">
                            </button>
                        </div>
                        <div class="<?php echo $colClassType3; ?>" style="padding:0px 15px 0px 15px !important;">
                            <div class="input-group">
                                <input class="form-control" id="allCageItemTrnsSrchFor" type = "text" placeholder="Search For" value="<?php
                                echo trim(str_replace("%", " ", $srchFor));
                                ?>" onkeyup="enterKeyFuncAllCageItemTrns(event, '', '#allCageItemsBnCrdDiv', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&sbmtdItemID=<?php echo $itemID; ?>&sbmtdCageID=<?php echo $cageID; ?>&srcMenu=<?php echo $srcMenu; ?>')">
                                <input id="allCageItemTrnsPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                <label class="btn btn-primary btn-file input-group-addon" onclick="getAllCageItemTrns('clear', '#allCageItemsBnCrdDiv', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&sbmtdItemID=<?php echo $itemID; ?>&sbmtdCageID=<?php echo $cageID; ?>&srcMenu=<?php echo $srcMenu; ?>')">
                                    <span class="glyphicon glyphicon-remove"></span>
                                </label>
                                <label class="btn btn-primary btn-file input-group-addon" onclick="getAllCageItemTrns('', '#allCageItemsBnCrdDiv', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&sbmtdItemID=<?php echo $itemID; ?>&sbmtdCageID=<?php echo $cageID; ?>&srcMenu=<?php echo $srcMenu; ?>')">
                                    <span class="glyphicon glyphicon-search"></span>
                                </label>
                                <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                <select data-placeholder="Select..." class="form-control chosen-select" id="allCageItemTrnsSrchIn" style="display:none;">
                                    <?php
                                    $valslctdArry = array("", "", "");
                                    $srchInsArrys = array("All", "Description", "Money Type");

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
                        <div class="<?php echo $colClassType2; ?>" style="padding:0px 1px 0px 1px !important;">
                            <div class="col-md-6" style="padding:0px 1px 0px 1px !important;">
                                <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                    <input class="form-control" size="16" type="text" id="allCageItemTrnsStrtDate" name="allCageItemTrnsStrtDate" value="<?php
                                    echo substr($qStrtDte, 0, 11);
                                    ?>" placeholder="Start Date">
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                </div></div>
                            <div class="col-md-6" style="padding:0px 15px 0px 1px !important;">
                                <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                    <input class="form-control" size="16" type="text"  id="allCageItemTrnsEndDate" name="allCageItemTrnsEndDate" value="<?php
                                    echo substr($qEndDte, 0, 11);
                                    ?>" placeholder="End Date">
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                </div>
                            </div>                            
                        </div>
                    </form>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-striped table-bordered table-responsive rhoBootsTable" id="allCageItemTrnsTable" cellspacing="0" width="100%" style="width:100%;">
                            <caption>Bin Card Transactions</caption>
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Transaction Type & Description</th>
                                    <th style="text-align:right;">Trns. Qty</th>
                                    <th>Base UoM</th>
                                    <th style="text-align:right;">Amount Received</th>
                                    <th style="text-align:right;">Amount Issued</th>
                                    <th style="text-align:right;">Closing Balance</th>
                                    <th>Money Type</th>
                                    <th style="min-width:85px;width:85px;">Trns. Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $cntr = 0;
                                $rnngBalance = 0;
                                $firstDate = "";
                                $cntrTtl = loc_db_num_rows($result5);
                                while ($row5 = loc_db_fetch_array($result5)) {
                                    $cntr += 1;
                                    /* if ($cntr == 1) {
                                      $firstDate = substr($row5[18], 0, 11);
                                      $prevDate = date('d-M-Y', strtotime($firstDate . ' -1 day'));
                                      //echo $firstDate . ":" . $prevDate;
                                      $rnngBalance = getStockLstTotBls($row5[4], $row5[6],
                                      $row5[1], $row5[17], $prevDate) * ((float) $row5[20]);
                                      }
                                      $rnngBalance += ((float) $row5[12]) * ((float) $row5[20]);
                                      if ($cntr == 1 || $firstDate != substr($row5[18], 0, 10)) {
                                      $firstDate = substr($row5[18], 0, 10);
                                      $rnngBalance = ((float) $row5[13]) * ((float) $row5[19]);
                                      } else {
                                      $rnngBalance += ((float) $row5[12]) * ((float) $row5[20]);
                                      } */
                                    if ($cntr == 1) {
                                        $rnngBalance = ((float) $row5[25]);
                                    }
                                    $rnngBalance += ((float) $row5[12]) * ((float) $row5[20]);
                                    if ($cntr == $cntrTtl && $shdAutoUpdt == 1) {
                                        execUpdtInsSQL("UPDATE vms.vms_stock_daily_bals
                                                                                    SET stock_tot_qty=(" . $rnngBalance . "/unit_value)
                                                                                  WHERE bals_date='" . $row5[27] . "' and store_vault_id=" . $row5[4]
                                                . " and cage_shelve_id=" . $row5[6]
                                                . " and item_id=" . $row5[1] . "");
                                    }
                                    ?>
                                    <tr id="allCageItemTrnsRow_<?php echo $cntr; ?>">                                    
                                        <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                        <td class="lovtd">
                                            <?php echo $row5[15] . " " . $row5[16]; ?>
                                            <input type="hidden" id="allCageItemTrnsRow<?php echo $cntr; ?>_TrnsLnID" name="allCageItemTrnsRow<?php echo $cntr; ?>_TrnsLnID" value="<?php echo $row5[0]; ?>">
                                        </td>
                                        <td class="lovtd" style="text-align:right;color:green;font-weight:bold;"><?php echo number_format((float) $row5[12], 0); ?></td>
                                        <td class="lovtd" style="">
                                            <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;color:black;font-weight:bold;" onclick="getGnrlUOMBrkdwnForm(<?php echo $row5[0]; ?>, 23, <?php echo $row5[1]; ?>, <?php echo $row5[12]; ?>, '<?php echo $row5[22]; ?>');" data-toggle="tooltip" data-placement="bottom" title="View QTY Breakdown"><?php echo $row5[14]; ?></button>
                                        </td>
                                        <td class="lovtd" style="text-align:right;color:blue;"><?php
                                            if (((float) $row5[12]) >= 0) {
                                                echo number_format(abs((float) $row5[12]) * ((float) $row5[20]), 2);
                                            } else {
                                                echo "0.00";
                                            }
                                            ?>
                                        </td>
                                        <td class="lovtd" style="text-align:right;color:blue;"><?php
                                            if (((float) $row5[12]) < 0) {
                                                echo number_format(abs((float) $row5[12]) * ((float) $row5[20]), 2);
                                            } else {
                                                echo "0.00";
                                            }
                                            ?>
                                        </td>
                                        <td class="lovtd" style="text-align:right;color:blue;font-weight:bold;"><?php echo number_format($rnngBalance, 2); ?></td>
                                        <td class="lovtd" style="color:red;font-weight:bold;"><?php echo $row5[17]; ?></td>   
                                        <td class="lovtd" style="min-width:85px;width:85px;"><?php echo $row5[18]; ?></td>                                                                   
                                    </tr>
                                    <?php
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <?php
            } else if ($vwtyp == 7) {
                $pkID = isset($_POST['sbmtdShelfID']) ? $_POST['sbmtdShelfID'] : -1;
                $sbmtdSiteID = isset($_POST['sbmtdSiteID']) ? $_POST['sbmtdSiteID'] : -1;
                $sbmtdStoreID = isset($_POST['sbmtdStoreID']) ? $_POST['sbmtdStoreID'] : -1;
                $shelfCageNm = getVMSCageName($pkID, $sbmtdStoreID);
                $result = get_AllCageItems($pkID);
                $ttlPrce = 0;
                $ttlQty = 0;
                $lastCur = "";
                ?>
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-striped table-bordered table-responsive rhoBootsTable" id="allCagePostnTable" cellspacing="0" width="100%" style="width:100%;">
                            <caption>Overall Cage Position for (<?php echo $shelfCageNm; ?>)</caption>
                            <thead>
                                <tr>
                                    <!--<th>No.</th>-->		
                                    <th>Denomination</th>
                                    <th style="text-align:right;">Pieces</th>
                                    <th>UoM</th>
                                    <th style="text-align:right;">Unit Value</th>
                                    <th style="text-align:right;">Total Value</th>
                                    <th>Money Type</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $cntr = 0;
                                while ($row = loc_db_fetch_array($result)) {
                                    $cntr += 1;
                                    $ttlPrce += (float) $row[18];
                                    $lastCur = $row[17];
                                    $ttlQty += (float) $row[4];
                                    ?>
                                    <tr id="allCagePostnRow_<?php echo $cntr; ?>" class="hand_cursor">                                    
                                        <!--<td class="lovtd"><?php echo ($cntr); ?></td>-->
                                        <td class="lovtd">
                                            <?php echo $row[8]; ?>
                                            <input type="hidden" id="allCagePostnRow<?php echo $cntr; ?>_ItemID" name="allCagePostnRow<?php echo $cntr; ?>_ItemID" value="<?php echo $row[3]; ?>">
                                            <input type="hidden" id="allCagePostnRow<?php echo $cntr; ?>_CageID" name="allCagePostnRow<?php echo $cntr; ?>_CageID" value="<?php echo $row[2]; ?>">
                                        </td>
                                        <td class="lovtd" style="text-align:right;color:green;font-weight:bold;">
                                            <?php echo number_format((float) $row[4], 0); ?>
                                        </td>
                                        <td class="lovtd">
                                            <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;color:black;font-weight:bold;" onclick="getGnrlUOMBrkdwnForm(-1, 23, <?php echo $row[3]; ?>, <?php echo $row[4]; ?>, '<?php echo $row[17]; ?>');" data-toggle="tooltip" data-placement="bottom" title="View QTY Breakdown"><?php echo $row[14]; ?></button>
                                        </td>
                                        <td class="lovtd" style="text-align:right;">
                                            <?php echo number_format((float) $row[15], 2); ?>
                                        </td>
                                        <td class="lovtd" style="text-align:right;color:blue;font-weight: bold;">
                                            <?php
                                            echo $row[17] . " " . number_format((float) $row[18], 2);
                                            ?>
                                        </td>
                                        <td class="lovtd" style="color:red;font-weight:bold;"><?php echo $row[7]; ?></td>         
                                    </tr>
                                    <?php
                                }
                                ?>
                            </tbody>
                            <tfoot>                                                            
                                <tr>
                                    <th>TOTALS:</th>
                                    <!--<th>&nbsp;</th>-->
                                    <th style="text-align: right;">
                                        <?php
                                        echo "<span style=\"color:blue;\">" . number_format($ttlQty, 0, '.', ',') . "</span>";
                                        ?>
                                    </th>
                                    <th>&nbsp;</th>
                                    <th>&nbsp;</th>
                                    <th style="text-align: right;">
                                        <?php
                                        echo "<span style=\"color:blue;\">" . $lastCur . " " . number_format($ttlPrce, 2, '.', ',') . "</span>";
                                        ?>
                                    </th>
                                    <th>&nbsp;</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <?php
            }
        }
    }
}