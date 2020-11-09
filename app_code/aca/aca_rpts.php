<?php
$pageNo = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
$lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 40;
$sortBy = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : "";
$srcMenu = "";
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
                $pkID = -1;
                echo $cntent . "<li>
                                    <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                    <span style=\"text-decoration:none;\">Standard Reports</span>
				</li>
                               </ul>
                              </div>";
                $mdlNm1 = $ModuleName;
                $total = get_Module_RptsTtl($srchFor, $srchIn, $mdlNm1, -1, "0");
                if ($pageNo > ceil($total / $lmtSze)) {
                    $pageNo = 1;
                } else if ($pageNo < 1) {
                    $pageNo = ceil($total / $lmtSze);
                }
                $curIdx = $pageNo - 1;
                $result = get_Module_Rpts($srchFor, $srchIn, $curIdx, $lmtSze, $sortBy, $mdlNm1, -1, "0");
                $cntr = 0;
                $colClassType3 = "col-lg-4";
                $colClassType1 = "col-lg-2";
                $colClassType2 = "col-lg-3";
                ?>
                <!--<form id='allMdlRptsForm' action='' method='post' accept-charset='UTF-8'>-->
                <div class="row rhoRowMargin">
                    <?php
                    $colClassType1 = "col-lg-3";
                    $colClassType2 = "col-lg-5";
                    $colClassType3 = "col-lg-4";
                    ?>
                    <div class="<?php echo $colClassType2; ?>" style="padding:0px 15px 0px 15px !important;">
                        <div class="input-group">
                            <input class="form-control" id="allMdlRptsSrchFor" type = "text" placeholder="Search For" value="<?php
                            echo trim(str_replace("%", " ", $srchFor));
                            ?>" onkeyup="enterKeyFuncAllModuleRpts(event, '', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&srcMenu=<?php echo $srcMenu; ?>&mdl=<?php echo $mdlACAorPMS;?>');">
                            <input id="allMdlRptsPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                            <label class="btn btn-primary btn-file input-group-addon" onclick="getAllModuleRpts('clear', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&srcMenu=<?php echo $srcMenu; ?>&mdl=<?php echo $mdlACAorPMS;?>');">
                                <span class="glyphicon glyphicon-remove"></span>
                            </label>
                            <label class="btn btn-primary btn-file input-group-addon" onclick="getAllModuleRpts('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&srcMenu=<?php echo $srcMenu; ?>&mdl=<?php echo $mdlACAorPMS;?>');">
                                <span class="glyphicon glyphicon-search"></span>
                            </label> 
                        </div>
                    </div>
                    <div class="<?php echo $colClassType3; ?>">
                        <div class="input-group">
                            <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                            <select data-placeholder="Select..." class="form-control chosen-select" id="allMdlRptsSrchIn">
                                <?php
                                $valslctdArry = array("", "", "");
                                $srchInsArrys = array("Report Name", "Report Description", "Owner Module");

                                for ($z = 0; $z < count($srchInsArrys); $z++) {
                                    if ($srchIn == $srchInsArrys[$z]) {
                                        $valslctdArry[$z] = "selected";
                                    }
                                    ?>
                                    <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                <?php } ?>
                            </select>
                            <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                            <select data-placeholder="Select..." class="form-control chosen-select" id="allMdlRptsDsplySze" style="min-width:70px !important;">                            
                                <?php
                                $valslctdArry = array("", "", "", "", "", "", "", "");
                                $dsplySzeArry = array(1, 5, 10, 15, 30, 40, 50, 100, 500, 1000);
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
                                    <a class="rhopagination" href="javascript:getAllModuleRpts('previous', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&srcMenu=<?php echo $srcMenu; ?>&mdl=<?php echo $mdlACAorPMS;?>');" aria-label="Previous">
                                        <span aria-hidden="true">&laquo;</span>
                                    </a>
                                </li>
                                <li>
                                    <a class="rhopagination" href="javascript:getAllModuleRpts('next', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&srcMenu=<?php echo $srcMenu; ?>&mdl=<?php echo $mdlACAorPMS;?>');" aria-label="Next">
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
                        $outputTyp = $row[12];
                        $rptOrPrcs = $row[5];
                        $imgNm = "cmn_images/98.png";
                        if ($rptOrPrcs != "SQL Report") {
                            $imgNm = "cmn_images/small_blue_script.gif";
                        } else {
                            if ($outputTyp == "PDF" || $outputTyp == "STANDARD") {
                                $imgNm = "cmn_images/pdf-icon-copy.png";
                            } else if ($outputTyp == "HTML" || strpos($outputTyp, "CHART") !== FALSE) {
                                $imgNm = "cmn_images/report-icon-png.png";
                            } else if ($outputTyp == "MICROSOFT EXCEL" || $outputTyp == "CHARACTER SEPARATED FILE (CSV)") {
                                $imgNm = "cmn_images/image007.png";
                            } else if ($outputTyp == "MICROSOFT WORD") {
                                $imgNm = "cmn_images/image001.png";
                            }
                        }
                        if ($grpcntr == 0) {
                            ?>
                            <div class="row">
                                <?php
                            }
                            ?>
                            <div class="col-md-3 colmd3special2" style="line-height: 60px;height:60px;margin-bottom:5px;">
                                <button type="button" class="btn btn-default btn-lg btn-block" style="min-height:60px;height:58px;" onclick="getMyMdlRptRuns('', 'ShowDialog', 'grp=9&typ=1&pg=1&vtyp=50&sbmtdRptID=<?php echo $row[0]; ?>&srcMenu=<?php echo $srcMenu; ?>');">
                                    <img src="<?php echo $imgNm; ?>" style="margin:5px; height:40px; width:auto; position: relative; vertical-align: middle;float:left;">
                                    <span class="wordwrap21" style="font-size:12px;font-weight:bold;vertical-align: middle !important;float:left;padding:10px 1px 1px 1px;"><?php echo $row[1]; ?></span>
                                </button>
                            </div>
                            <?php if ($grpcntr == 3) { ?>
                            </div>
                            <?php
                            $grpcntr = 0;
                        } else {
                            $grpcntr = $grpcntr + 1;
                        }
                    }
                    ?>
                </div>                
                <!--</form>-->
                <?php
            }
        }
    }
}