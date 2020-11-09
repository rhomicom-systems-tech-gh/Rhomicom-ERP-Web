<?php
$z = 0;
$canRunRpts = test_prmssns($dfltPrvldgs[8], $mdlNm);
$canEdtRpts = test_prmssns($dfltPrvldgs[6], $mdlNm);
$canDelRptRuns = test_prmssns($dfltPrvldgs[9], $mdlNm);
$canVwOthrsRuns = test_prmssns($dfltPrvldgs[10], $mdlNm);

$pageNo = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
$lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 30;
$sortBy = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : "Report Run ID";

if (array_key_exists('lgn_num', get_defined_vars())) {
    if ($lgn_num > 0 && $canview === true) {
        if ($qstr == "DELETE") {
            if ($actyp == 1) {
                /* Delete Process Runner */
                $runID = isset($_POST['runID']) ? cleanInputData($_POST['runID']) : -1;
                if ($canDelRptRuns) {
                    $affctd0 = deleteGnrlRecs($runID, "rpt.rpt_run_msgs", "process_id");
                    $affctd2 = deleteGnrlRecs($runID, "rpt.rpt_gnrl_data_storage", "rpt_run_id");
                    $affctd1 = deleteGnrlRecs($runID, "rpt.rpt_report_runs", "rpt_run_id");
                    if ($affctd1 > 0) {
                        $dsply = "Successfully Deleted the ff Records-";
                        $dsply .= "<br/>$affctd1 Process Run(s)!";
                        echo "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
                    } else {
                        $dsply = "No Record Deleted!";
                        echo "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
                    }
                } else {
                    restricted();
                }
            }
        } else if ($qstr == "UPDATE") {
            
        } else {
            if ($vwtyp == 0) {
                echo $cntent . "<li onclick=\"openATab('#allmodules', 'grp=$group&typ=$type&pg=$pgNo&vtyp=0');\">
                                    <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                    <span style=\"text-decoration:none;\">All Report Runs</span>
				</li>
                               </ul>
                              </div>";
                $pkID = isset($_POST['sbmtdRptID']) ? $_POST['sbmtdRptID'] : -1;
                $sbmtdAlrtID = -1;
                $sbmtdRptID = $pkID;
                ?>
                <form id="allPrcsRunsForm">
                    <div  class="rho-container-fluid1">
                        <div class="row">
                            <?php
                            $colClassType1 = "col-lg-2";
                            $colClassType2 = "col-lg-3";
                            $colClassType3 = "col-lg-5";
                            ?>
                            <div class="<?php echo $colClassType2; ?>" style="padding:0px 3px 0px 3px !important;">
                                <div class="input-group">
                                    <input class="form-control" id="allPrcsRunsSrchFor" type = "text" placeholder="Search For" value="<?php
                                    echo trim(str_replace("%", " ", $srchFor));
                                    ?>" onkeyup="enterKeyFuncAllPrcsRuns(event, '', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=0');">
                                    <input id="allPrcsRunsPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getAllPrcsRuns('clear', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=0');">
                                        <span class="glyphicon glyphicon-remove"></span>
                                    </label>
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getAllPrcsRuns('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=0');">
                                        <span class="glyphicon glyphicon-search"></span>
                                    </label> 
                                </div>
                            </div>
                            <div class="<?php echo $colClassType3; ?>" style="padding:0px 3px 0px 3px !important;">
                                <div class="input-group">
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="allPrcsRunsSrchIn">
                                        <?php
                                        $valslctdArry = array("", "", "", "", "");
                                        $srchInsArrys = array("Report Name", "Report Run ID", "Run By", "Run Date", "Run Status");

                                        for ($z = 0; $z < count($srchInsArrys); $z++) {
                                            if ($srchIn == $srchInsArrys[$z]) {
                                                $valslctdArry[$z] = "selected";
                                            }
                                            ?>
                                            <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                        <?php } ?>
                                    </select>
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="allPrcsRunsSortBy">
                                        <?php
                                        $valslctdArry = array("", "");
                                        $srchInsArrys = array("Report Run ID", "Last Active Time");

                                        for ($z = 0; $z < count($srchInsArrys); $z++) {
                                            if ($sortBy == $srchInsArrys[$z]) {
                                                $valslctdArry[$z] = "selected";
                                            }
                                            ?>
                                            <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                        <?php } ?>
                                    </select>
                                    <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="allPrcsRunsDsplySze" style="min-width:70px !important;">                            
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
                            <div class="<?php echo $colClassType1; ?>" style="padding:0px 3px 0px 3px !important;">
                                <nav aria-label="Page navigation">
                                    <ul class="pagination" style="margin: 0px !important;">
                                        <li>
                                            <a class="rhopagination" href="javascript:getAllPrcsRuns('previous', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=0');" aria-label="Previous">
                                                <span aria-hidden="true">&laquo;</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a class="rhopagination" href="javascript:getAllPrcsRuns('next', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=0');" aria-label="Next">
                                                <span aria-hidden="true">&raquo;</span>
                                            </a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12" style="padding:0px 3px 0px 3px !important;">
                                <fieldset class="basic_person_fs">                                       
                                    <table class="table table-striped table-bordered table-responsive" id="allPrcsRunsTable" cellspacing="0" width="100%" style="width:100%;min-width: 730px;">
                                        <thead>
                                            <tr>
                                                <th>No.</th>
                                                <th>ID</th>
                                                <th>Report Name</th>
                                                <th style="min-width:180px !important;">Run Status (Progress (%))</th>
                                                <th style="min-width:140px !important;">Last Time Active</th>
                                                <th>Open Output File</th>
                                                <th>Log Files</th>
                                                <th>Run By</th>
                                                <th style="min-width:145px !important;text-align:center;">Date Run [Duration]</th>
                                                <th style="">Source</th>
                                                <?php if ($canDelRptRuns === TRUE) { ?>
                                                    <th>&nbsp;</th>
                                                <?php } ?>
                                                <?php if ($canEdtRpts === TRUE) { ?>
                                                    <th>&nbsp;</th>
                                                <?php } ?>
                                                <?php if ($canRunRpts === TRUE) { ?>
                                                    <th>&nbsp;</th>
                                                <?php } ?>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $total = get_RptRunsTtl($pkID, $srchFor, $srchIn);
                                            if ($pageNo > ceil($total / $lmtSze)) {
                                                $pageNo = 1;
                                            } else if ($pageNo < 1) {
                                                $pageNo = ceil($total / $lmtSze);
                                            }

                                            $curIdx = $pageNo - 1;
                                            $result2 = get_RptRuns($pkID, $srchFor, $srchIn, $curIdx, $lmtSze, $sortBy);
                                            $cntr = 0;
                                            while ($row2 = loc_db_fetch_array($result2)) {
                                                $cntr += 1;
                                                $sbmtdAlrtID = $row2[14];
                                                //$chckd = FALSE; 
                                                $outptUsd = $row2[8];
                                                $rpt_src_encrpt = "";
                                                if ($outptUsd == "HTML" || $outptUsd == "COLUMN CHART" || $outptUsd == "SIMPLE COLUMN CHART" ||
                                                        $outptUsd == "BAR CHART" || $outptUsd == "PIE CHART" || $outptUsd == "LINE CHART") {
                                                    $rpt_src = str_replace("\\", "/", $ftp_base_db_fldr . "/Rpts") . "/amcharts_2100/samples/$row2[0].html";
                                                    $rpt_src_encrpt = encrypt1($rpt_src, $smplTokenWord1);
                                                    if (file_exists($rpt_src)) {
                                                        //file exists!
                                                    } else {
                                                        //file does not exist.
                                                        $rpt_src_encrpt = "None";
                                                    }
                                                } else if ($outptUsd == "STANDARD") {
                                                    $rpt_src = str_replace("\\", "/", $ftp_base_db_fldr . "/Rpts") . "/$row2[0].txt";
                                                    $rpt_src_encrpt = encrypt1($rpt_src, $smplTokenWord1);
                                                    if (file_exists($rpt_src)) {
                                                        //file exists!
                                                    } else {
                                                        //file does not exist.
                                                        $rpt_src_encrpt = "None";
                                                    }
                                                } else if ($outptUsd == "PDF") {
                                                    $rpt_src = str_replace("\\", "/", $ftp_base_db_fldr . "/Rpts") . "/$row2[0].pdf";
                                                    $rpt_src_encrpt = encrypt1($rpt_src, $smplTokenWord1);
                                                    if (file_exists($rpt_src)) {
                                                        //file exists!
                                                    } else {
                                                        //file does not exist.
                                                        $rpt_src_encrpt = "None";
                                                    }
                                                } else if ($outptUsd == "MICROSOFT WORD") {
                                                    $rpt_src = str_replace("\\", "/", $ftp_base_db_fldr . "/Rpts") . "/$row2[0].rtf";
                                                    $rpt_src_encrpt = encrypt1($rpt_src, $smplTokenWord1);
                                                    if (file_exists($rpt_src)) {
                                                        //file exists!
                                                    } else {
                                                        //file does not exist.
                                                        $rpt_src_encrpt = "None";
                                                    }
                                                } else if ($outptUsd == "MICROSOFT EXCEL") {
                                                    $rpt_src = str_replace("\\", "/", $ftp_base_db_fldr . "/Rpts") . "/$row2[0].xls";
                                                    $rpt_src_encrpt = encrypt1($rpt_src, $smplTokenWord1);
                                                    if (file_exists($rpt_src)) {
                                                        //file exists!
                                                    } else {
                                                        //file does not exist.
                                                        $rpt_src_encrpt = "None";
                                                    }
                                                } else if ($outptUsd == "CHARACTER SEPARATED FILE (CSV)") {
                                                    $rpt_src = str_replace("\\", "/", $ftp_base_db_fldr . "/Rpts") . "/$row2[0].csv";
                                                    $rpt_src_encrpt = encrypt1($rpt_src, $smplTokenWord1);
                                                    if (file_exists($rpt_src)) {
                                                        //file exists!
                                                    } else {
                                                        //file does not exist.
                                                        $rpt_src_encrpt = "None";
                                                    }
                                                } else {
                                                    $rpt_src_encrpt = "None";
                                                }
                                                ?>
                                                <tr id="allPrcsRunsRow_<?php echo $cntr; ?>" class="hand_cursor">                                    
                                                    <td class="lovtd">
                                                        <?php echo ($curIdx * $lmtSze) + ($cntr);
                                                        ?>
                                                        <input type="hidden" class="form-control" aria-label="..." id="allPrcsRunsRow<?php echo $cntr; ?>_RunID" value="<?php echo $row2[0]; ?>">
                                                    </td>
                                                    <td class="lovtd"><a href="javascript:getOneRptsRnStsForm(<?php echo $pkID; ?>, <?php echo $row2[0]; ?>, 3,'0', <?php echo $sbmtdAlrtID; ?>);" style="color:blue;font-weight:bold;"><?php echo $row2[0]; ?></a></td>                                 
                                                    <td class="lovtd"><?php echo $row2[13]; ?></td>
                                                    <td class="lovtd"><span <?php
                                                        $style2 = "";
                                                        if ($row2[4] == "Not Started!") {
                                                            $style2 = "style=\"background-color: #E8D68C;padding:5px !important;\"";
                                                        } else if ($row2[4] == "Preparing to Start...") {
                                                            $style2 = "style=\"background-color: yellow;padding:5px !important;\"";
                                                        } else if ($row2[4] == "Running SQL...") {
                                                            $style2 = "style=\"background-color: lightgreen;padding:5px !important;\"";
                                                        } else if ($row2[4] == "Formatting Output...") {
                                                            $style2 = "style=\"background-color: lime;padding:5px !important;\"";
                                                        } else if ($row2[4] == "Storing Output..." || $row2[4] == "Sending Output...") {
                                                            $style2 = "style=\"background-color: cyan;padding:5px !important;\"";
                                                        } else if ($row2[4] == "Completed!") {
                                                            $style2 = "style=\"background-color: gainsboro;padding:5px !important;\"";
                                                        } else if (strpos($row2[4], "Error") !== FALSE) {
                                                            $style2 = "style=\"background-color: red;padding:5px !important;\"";
                                                        }
                                                        echo $style2;
                                                        ?>><?php echo $row2[4] . " (" . $row2[5] . "%)"; ?></span></td>
                                                    <td class="lovtd"><span <?php
                                                        $tst = isDteTmeWthnIntrvl(cnvrtDMYTmToYMDTm($row2[10]), '40 second');
                                                        $style2 = "";
                                                        if ($tst == true) {
                                                            $style2 = "style=\"background-color: limegreen;color: white; font-size:12px;padding:2px;\"";
                                                        }
                                                        echo $style2;
                                                        ?>><?php echo $row2[10]; ?></span></td>
                                                    <td class="lovtd">
                                                        <?php
                                                        if ($rpt_src_encrpt == "None") {
                                                            ?>
                                                            <span style="font-weight: bold;color:#FF0000;">
                                                                <?php
                                                                echo $rpt_src_encrpt;
                                                                ?>
                                                            </span>
                                                            <?php
                                                        } else {
                                                            ?>
                                                            <button type="button" class="btn btn-default" style="margin-bottom: 0px;" onclick="doAjax('grp=1&typ=11&q=Download&fnm=<?php echo $rpt_src_encrpt; ?>', '', 'Redirect', '', '', '');" data-toggle="tooltip" data-placement="bottom" title="OPEN OUTPUT FILE">
                                                                <img src="cmn_images/dwldicon.png" style="height:20px; width:auto; position: relative; vertical-align: middle;"> View Output
                                                            </button>
                                                        <?php }
                                                        ?>
                                                    </td>
                                                    <td class="lovtd">
                                                        <button type="button" class="btn btn-default" style="margin-bottom: 0px;" onclick="getOneRptsLogForm(<?php echo $row2[0]; ?>);" data-toggle="tooltip" data-placement="bottom" title="VIEW PROCESS LOG FILE INFO">
                                                            <img src="cmn_images/vwlog.png" style="height:20px; width:auto; position: relative; vertical-align: middle;"> View Log
                                                        </button>
                                                    </td>
                                                    <td class="lovtd"><?php echo $row2[2]; ?></td>
                                                    <td class="lovtd" style="text-align:center;"><?php echo $row2[3] . "<br/><span style=\"font-weight:bold;color:blue;\">[" . $row2[15] . "]</span>"; ?></td>
                                                    <td class="lovtd"><span><?php echo $row2[11]; ?></span></td>
                                                    <?php if ($canDelRptRuns === true) { ?>
                                                        <td class="lovtd">
                                                            <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delPrcsRun('allPrcsRunsRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Process Run">
                                                                <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                            </button>
                                                        </td>
                                                    <?php } ?>
                                                    <?php if ($canEdtRpts === true) { ?>
                                                        <td class="lovtd">
                                                            <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="getOneReportStpForm(<?php echo $row2[16]; ?>, 8, 'ShowDialog', '0', 'ALL_RUNS');" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Edit Report/Process">
                                                                <img src="cmn_images/edit32.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                            </button>
                                                        </td>
                                                    <?php } ?>
                                                    <?php if ($canRunRpts === true) { ?>
                                                        <td class="lovtd">
                                                            <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="getOneRptsParamsForm(<?php echo $row2[16]; ?>, -1, '<?php echo urlencode($row2[13]); ?>', 2, -1, 'ShowDialog');" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Run Again this Report/Process" aria-describedby="tooltip945683">
                                                                <img src="cmn_images/98.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
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
                        </div>                                                
                    </div>
                </form>
                <?php
            }
        }
    }
}
?>