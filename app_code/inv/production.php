<?php
$canAdd = test_prmssns($dfltPrvldgs[93], $mdlNm);
$canEdt = test_prmssns($dfltPrvldgs[94], $mdlNm);
$canDel = test_prmssns($dfltPrvldgs[95], $mdlNm);

$pageNo = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
$lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 10;
$sortBy = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : "Value";
if (array_key_exists('lgn_num', get_defined_vars())) {
    if ($lgn_num > 0 && $canview === true) {
        if ($qstr == "DELETE") {
            if ($actyp == 1) {
                
            } else if ($actyp == 5) {
                
            }
        } else if ($qstr == "UPDATE") {
            if ($actyp == 1) {
                
            }
        } else {
            if ($vwtyp == 0) {
                echo $cntent . "<li onclick=\"openATab('#allmodules', 'grp=$group&typ=$type&pg=$pgNo&vtyp=0');\">
                                    <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                    <span style=\"text-decoration:none;\">Production Processes</span>
				</li>
                               </ul>
                              </div>";
                $error = "";
                $searchAll = true;
                $srchFor = isset($_POST['searchfor']) ? cleanInputData($_POST['searchfor']) : '';
                $srchIn = isset($_POST['searchin']) ? cleanInputData($_POST['searchin']) : 'Document Number';
                $pageNo = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
                $lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 30;
                $sortBy = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : "Trns. ID DESC";
                $qShwProcessOnly = true;
                if (isset($_POST['qShwProcessOnly'])) {
                    $qShwProcessOnly = cleanInputData($_POST['qShwProcessOnly']) === "YES" ? true : false;
                }
                $qShwRunsOnly = false;
                if ($qShwProcessOnly == false) {
                    $qShwRunsOnly = true;
                }
                if (strpos($srchFor, "%") === FALSE) {
                    $srchFor = "%" . str_replace(" ", "%", $srchFor) . "%";
                    $srchFor = str_replace("%%", "%", $srchFor);
                }
                if ($vwtyp == 0) {
                    $total = get_Total_PrdctCrtn($srchFor, $srchIn, $orgID, $qShwProcessOnly);
                    if ($pageNo > ceil($total / $lmtSze)) {
                        $pageNo = 1;
                    } else if ($pageNo < 1) {
                        $pageNo = ceil($total / $lmtSze);
                    }
                    $curIdx = $pageNo - 1;
                    $result = get_Basic_PrdctCrtn($srchFor, $srchIn, $curIdx, $lmtSze, $orgID, $qShwProcessOnly);
                    $cntr = 0;
                    $colClassType1 = "col-md-2";
                    $colClassType2 = "col-md-5";
                    $colClassType3 = "col-md-5";
                    ?> 
                    <form id='scmPrdctCrtnForm' action='' method='post' accept-charset='UTF-8'>
                        <!--ROW ID-->
                        <input class="form-control" id="tblRowID" type = "hidden" placeholder="ROW ID"/>                     
                        <fieldset class=""><legend class="basic_person_lg1" style="color: #003245">PRODUCTION PROCESSES</legend>
                            <div class="row" style="margin-bottom:0px;">
                                <?php
                                $colClassType1 = "col-md-2";
                                $colClassType2 = "col-md-5";
                                $colClassType3 = "col-md-10";
                                ?>
                                <div class="<?php echo $colClassType3; ?>" style="padding:0px 15px 0px 15px !important;">
                                    <div class="input-group">
                                        <input class="form-control" id="scmPrdctCrtnSrchFor" type = "text" placeholder="Search For" value="<?php
                                        echo trim(str_replace("%", " ", $srchFor));
                                        ?>" onkeyup="enterKeyFuncScmPrdctCrtn(event, '', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=0')">
                                        <input id="scmPrdctCrtnPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getScmPrdctCrtn('clear', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=0');">
                                            <span class="glyphicon glyphicon-remove"></span>
                                        </label>
                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getScmPrdctCrtn('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=0');">
                                            <span class="glyphicon glyphicon-search"></span>
                                        </label>
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                        <select data-placeholder="Select..." class="form-control chosen-select" id="scmPrdctCrtnSrchIn">
                                            <?php
                                            /* Run Status-R
                                              Batch Number-R
                                              Classification-D/R
                                              Created By-D/R
                                              Description-D/R
                                              Process Code/Name-D/R
                                              Start Date-R */
                                            $valslctdArry = array("", "", "", "", "", "", "");
                                            $srchInsArrys = array("Process Code/Name", "Description", "Classification", "Created By", "Batch Number", "Run Status", "Start Date");
                                            for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                if ($qShwProcessOnly) {
                                                    if ($z >= 4) {
                                                        continue;
                                                    }
                                                }
                                                if ($srchIn == $srchInsArrys[$z]) {
                                                    $valslctdArry[$z] = "selected";
                                                }
                                                ?>
                                                <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                            <?php } ?>
                                        </select>
                                        <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                        <select data-placeholder="Select..." class="form-control chosen-select" id="scmPrdctCrtnDsplySze" style="min-width:70px !important;">                            
                                            <?php
                                            $valslctdArry = array("", "", "", "", "", "", "", "");
                                            $dsplySzeArry = array(1, 5, 10, 15, 30, 50, 100, 500, 1000, 1000000);
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
                                                <a href="javascript:getScmPrdctCrtn('previous', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=0');" aria-label="Previous">
                                                    <span aria-hidden="true">&laquo;</span>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:getScmPrdctCrtn('next', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=0');" aria-label="Next">
                                                    <span aria-hidden="true">&raquo;</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </nav>
                                </div>
                            </div>   
                            <div class="row " style="margin-bottom:2px;padding:2px 15px 2px 15px !important">   
                                <div class="col-md-12" style="padding:2px 1px 2px 1px !important;border-top:1px solid #ddd;border-bottom:1px solid #ddd;">
                                    <?php if ($canAdd === true) { ?>   
                                        <div class="col-md-6" style="padding:0px 0px 0px 0px !important;">                      
                                            <button type="button" class="btn btn-default" style="margin-bottom: 0px;" onclick="getOneScmPrdctCrtnForm(-1, 1, 'ShowDialog', 'Supplier Standard Payment');" data-toggle="tooltip" data-placement="bottom" title="Add New Supplier Standard Payment">
                                                <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                NEW PRODUCTION PROCESS
                                            </button> 
                                        </div>  
                                    <?php }
                                    ?>
                                    <div class="col-md-6" style="padding:5px 1px 0px 1px !important;">
                                        <div class="form-group form-group-sm" style="padding:0px 0px 0px 0px !important;">
                                            <label for="accbTxCdeIsEnbld" class="control-label col-lg-6">Show Defined Processes Only?:</label>
                                            <div  class="col-lg-6">
                                                <?php
                                                $chkdYes = "";
                                                $chkdNo = "checked=\"\"";
                                                if ($qShwProcessOnly === true) {
                                                    $chkdNo = "";
                                                    $chkdYes = "checked=\"\"";
                                                }
                                                ?>
                                                <?php
                                                if ($canEdt === true) {
                                                    ?>
                                                    <label class="radio-inline"><input type="radio" name="accbTxCdeIsEnbld" value="YES" <?php echo $chkdYes; ?>>YES</label>
                                                    <label class="radio-inline"><input type="radio" name="accbTxCdeIsEnbld" value="NO" <?php echo $chkdNo; ?>>NO</label>
                                                <?php } else {
                                                    ?>
                                                    <span><?php echo ($accbTxCdeIsEnbld == "1" ? "YES" : "NO"); ?></span>
                                                    <?php
                                                }
                                                ?>
                                            </div>
                                        </div>                            
                                    </div>
                                </div>
                            </div>
                            <div class="row"> 
                                <div  class="col-md-12">
                                    <table class="table table-striped table-bordered table-responsive" id="scmPrdctCrtnHdrsTable" cellspacing="0" width="100%" style="width:100%;">
                                        <thead>
                                            <tr>
                                                <th style="max-width:35px;width:35px;">No.</th>
                                                <th style="max-width:30px;width:30px;">...</th>
                                                <th>Process Name/Number</th>
                                                <th>Process Description</th>
                                                <th>Classification</th>
                                                <th style="max-width:115px;width:115px;text-align: center;">Status</th>
                                                <?php if ($canDel === true) { ?>
                                                    <th style="max-width:30px;width:30px;">...</th>
                                                <?php } ?>
                                                <?php if ($canVwRcHstry === true) { ?>
                                                    <th style="max-width:30px;width:30px;">...</th>
                                                <?php } ?>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            while ($row = loc_db_fetch_array($result)) {
                                                $cntr += 1;
                                                ?>
                                                <tr id="scmPrdctCrtnHdrsRow_<?php echo $cntr; ?>">                                    
                                                    <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>    
                                                    <td class="lovtd">
                                                        <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View/Edit Invoice" 
                                                                onclick="getOneScmPrdctCrtnForm(<?php echo $row[0]; ?>, 1, 'ShowDialog', '<?php echo $row[2]; ?>');" style="padding:2px !important;" style="padding:2px !important;">                                                                
                                                                    <?php if ($canAdd === true) { ?>                                
                                                                <img src="cmn_images/edit32.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                            <?php } else { ?>
                                                                <img src="cmn_images/kghostview.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                            <?php } ?>
                                                        </button>
                                                    </td>
                                                    <td class="lovtd"><?php echo $row[1]; ?></td>
                                                    <td class="lovtd"><?php echo $row[3]; ?></td>
                                                    <td class="lovtd"><?php echo $row[2]; ?></td>
                                                    <?php
                                                    if ($qShwProcessOnly) {
                                                        $isChkd = "";
                                                        if ($row[4] == "1") {
                                                            $isChkd = "checked=\"true\"";
                                                        }
                                                        ?>
                                                        <td class="lovtd" style="text-align:center;">
                                                            <div class="form-group form-group-sm">
                                                                <div class="form-check" style="font-size: 12px !important;">
                                                                    <label class="form-check-label">
                                                                        <input type="checkbox" class="form-check-input" id="scmPrdctCrtnHdrsRow<?php echo $cntr; ?>_IsEnbld" name="scmPrdctCrtnHdrsRow<?php echo $cntr; ?>_IsEnbld" <?php echo $isChkd ?>>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </td>  
                                                        <?php
                                                    } else {
                                                        $style1 = "color:red;";
                                                        if ($row[4] == "Approved") {
                                                            $style1 = "color:green;";
                                                        } else if ($row[4] == "Cancelled") {
                                                            $style1 = "color:#0d0d0d;";
                                                        }
                                                        ?>
                                                        <td class="lovtd" style="font-weight:bold;<?php echo $style1; ?>"><?php echo $row[4]; ?></td>  
                                                        <?php
                                                    }
                                                    if ($canDel === true) {
                                                        ?>
                                                        <td class="lovtd">
                                                            <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="Delete Process" onclick="delScmPrdctCrtn('scmPrdctCrtnHdrsRow_<?php echo $cntr; ?>');" style="padding:2px !important;" style="padding:2px !important;">
                                                                <img src="cmn_images/no.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                            </button>
                                                            <input type="hidden" id="scmPrdctCrtnHdrsRow<?php echo $cntr; ?>_HdrID" name="scmPrdctCrtnHdrsRow<?php echo $cntr; ?>_HdrID" value="<?php echo $row[0]; ?>">
                                                        </td>
                                                    <?php } ?>
                                                    <?php
                                                    if ($canVwRcHstry === true) {
                                                        if ($qShwProcessOnly) {
                                                            ?>
                                                            <td class="lovtd">
                                                                <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" onclick="getRecHstry('<?php
                                                                echo urlencode(encrypt1(($row[0] . "|scm.scm_process_definition|process_def_id"), $smplTokenWord1));
                                                                ?>');" style="padding:2px !important;">
                                                                    <img src="cmn_images/Information.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                </button>
                                                            </td>
                                                        <?php } else {
                                                            ?>
                                                            <td class="lovtd">
                                                                <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" onclick="getRecHstry('<?php
                                                                echo urlencode(encrypt1(($row[0] . "|scm.scm_process_run|process_run_id"), $smplTokenWord1));
                                                                ?>');" style="padding:2px !important;">
                                                                    <img src="cmn_images/Information.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                </button>
                                                            </td>
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
                        </fieldset>
                    </form>
                    <?php
                }
            }
        }
    }
}
?>