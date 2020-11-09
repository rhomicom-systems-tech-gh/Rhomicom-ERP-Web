<?php
$canAdd = test_prmssns($dfltPrvldgs[29], $mdlNm);
$canEdt = test_prmssns($dfltPrvldgs[30], $mdlNm);
$canDel = test_prmssns($dfltPrvldgs[31], $mdlNm);
$canVwRcHstry = test_prmssns($dfltPrvldgs[23], $mdlNm);

$pageNo = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
$lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 10;
$sortBy = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : "Value";
$defaultBrkdwnLOV = "Transaction Amount Breakdown Parameters";
if (array_key_exists('lgn_num', get_defined_vars())) {
    if ($lgn_num > 0 && $canview === true) {
        if ($qstr == "DELETE") {
            if ($actyp == 1) {
                /* Delete Doc Header */
                $pKeyID = isset($_POST['pKeyID']) ? cleanInputData($_POST['pKeyID']) : -1;
                $pKeyNm = isset($_POST['pKeyNm']) ? cleanInputData($_POST['pKeyNm']) : "";
                if ($canDel) {
                    echo deleteCostAcnt($pKeyID, $pKeyNm);
                } else {
                    restricted();
                }
            } else if ($actyp == 5) {
                
            }
        } else if ($qstr == "UPDATE") {
            if ($actyp == 1) {
                //Save Complaints/Observations
                header("content-type:application/json");
                $slctdDfltAcnts = isset($_POST['slctdDfltAcnts']) ? cleanInputData($_POST['slctdDfltAcnts']) : "";
                $exitErrMsg = "";
                $afftctd = 0;
                $afftctd1 = 0;
                $afftctd2 = 0;
                if (trim($slctdDfltAcnts, "|~") != "") {
                    //Save Petty Cash Double Entry Lines
                    $variousRows = explode("|", trim($slctdDfltAcnts, "|"));
                    //echo count($variousRows);
                    for ($y = 0; $y < count($variousRows); $y++) {
                        //var_dump($crntRow);
                        $crntRow = explode("~", $variousRows[$y]);
                        if (count($crntRow) == 6) {
                            $ln_TrnsLnID = (float) (cleanInputData1($crntRow[0]));
                            $ln_ChrgAcntID = (float) cleanInputData1($crntRow[1]);
                            $ln_BalsAcntID = (float) cleanInputData1($crntRow[2]);
                            $ln_CtgryNm = cleanInputData1($crntRow[3]);
                            $ln_IncrsDcrs1 = cleanInputData1($crntRow[4]);
                            $ln_IncrsDcrs2 = cleanInputData1($crntRow[5]);
                            $errMsg = "";
                            if ($ln_CtgryNm === "" || $ln_ChrgAcntID <= 0 || $ln_BalsAcntID <= 0) {
                                $errMsg = "Row " . ($y + 1) . ":- Category Type and Accounts are all required Fields!<br/>";
                            }
                            if ($errMsg === "") {
                                if ($ln_TrnsLnID <= 0) {
                                    $ln_TrnsLnID = getNewCstAcntLnID();
                                    $afftctd += createEvntCstAcnt($ln_TrnsLnID, $ln_CtgryNm, $ln_IncrsDcrs1, $ln_ChrgAcntID, $ln_IncrsDcrs2,
                                            $ln_BalsAcntID, -1);
                                } else if ($ln_TrnsLnID > 0) {
                                    $afftctd += updateEvntCstAcnt($ln_TrnsLnID, $ln_CtgryNm, $ln_IncrsDcrs1, $ln_ChrgAcntID, $ln_IncrsDcrs2,
                                            $ln_BalsAcntID, -1);
                                }
                            } else {
                                $exitErrMsg .= $errMsg;
                            }
                        }
                    }
                }

                if ($exitErrMsg != "") {
                    $exitErrMsg = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>" . $afftctd . " Account(s) Successfully Saved!"
                            . "<br/><span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>" . $exitErrMsg . "</span>";
                } else {
                    $exitErrMsg = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>" . $afftctd . " Account(s) Successfully Saved!";
                }
                $arr_content['percent'] = 100;
                $arr_content['message'] = $exitErrMsg;
                echo json_encode($arr_content);
                exit();
            }
        } else {
            if ($vwtyp == 0) {
                echo $cntent . "<li onclick=\"openATab('#allmodules', 'grp=$group&typ=$type&pg=$pgNo&vtyp=0');\">
                                    <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                    <span style=\"text-decoration:none;\">Default Accounts</span>
				</li>
                               </ul>
                              </div>";
                $error = "";
                $searchAll = true;
                $srchFor = isset($_POST['searchfor']) ? cleanInputData($_POST['searchfor']) : '';
                $srchIn = isset($_POST['searchin']) ? cleanInputData($_POST['searchin']) : 'Complaint/Observation Type';
                $pageNo = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
                $lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 30;
                $sortBy = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : "Trns. ID DESC";
                if (strpos($srchFor, "%") === FALSE) {
                    $srchFor = "%" . str_replace(" ", "%", $srchFor) . "%";
                    $srchFor = str_replace("%%", "%", $srchFor);
                }
                $result = get_One_EvntCostAcnts(-1);
                $cntr = 0;
                $colClassType1 = "col-md-2";
                $colClassType2 = "col-md-5";
                $colClassType3 = "col-md-5";
                ?> 
                <form id='attnDfltAcntsForm' action='' method='post' accept-charset='UTF-8'>
                    <!--ROW ID-->
                    <input class="form-control" id="tblRowID" type = "hidden" placeholder="ROW ID"/>                     
                    <fieldset class=""><legend class="basic_person_lg1" style="color: #003245">DEFAULT EVENT COSTING ACCOUNTS</legend>
                        <div class="row" style="margin-bottom:0px;">
                            <?php
                            $colClassType1 = "col-md-2";
                            $colClassType2 = "col-md-5";
                            $colClassType3 = "col-md-10";
                            ?>
                            <?php
                            if ($canAdd === true) {
                                $colClassType1 = "col-md-2";
                                $colClassType2 = "col-md-6";
                                $colClassType3 = "col-md-6";

                                $nwRowHtml31 = "<tr id=\"attnDfltAcntsHdrsRow__WWW123WWW\">                                    
                                                <td class=\"lovtd\">New</td>    
                                                <td class=\"lovtd\">
                                                    <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"attnDfltAcntsHdrsRow_WWW123WWW_TrnsLnID\" value=\"-1\" style=\"width:100% !important;\">
                                                    <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"attnDfltAcntsHdrsRow_WWW123WWW_ChrgAcntID\" value=\"-1\" style=\"width:100% !important;\"> 
                                                    <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"attnDfltAcntsHdrsRow_WWW123WWW_BalsAcntID\" value=\"-1\" style=\"width:100% !important;\"> 
                                                        <div class=\"input-group\" style=\"width:100% !important;\">
                                                            <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"attnDfltAcntsHdrsRow_WWW123WWW_CtgryNm\" name=\"attnDfltAcntsHdrsRow_WWW123WWW_CtgryNm\" value=\"\" readonly=\"true\" style=\"width:100% !important;\">
                                                            <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Event Cost Categories', '', '', '', 'radio', true, '', 'attnDfltAcntsHdrsRow_WWW123WWW_CtgryNm', '', 'clear', 1, '', function () {});\">
                                                                <span class=\"glyphicon glyphicon-th-list\"></span>
                                                            </label>
                                                        </div>                                            
                                                </td> 
                                                <td class=\"lovtd\">
                                                    <select data-placeholder=\"Select...\" class=\"form-control chosen-select\" id=\"attnDfltAcntsHdrsRow_WWW123WWW_IncrsDcrs1\" style=\"width:100% !important;\">";

                                $valslctdArry = array("", "");
                                $srchInsArrys = array("Increase", "Decrease");
                                for ($z = 0; $z < count($srchInsArrys); $z++) {
                                    $nwRowHtml31 .= "<option value=\"" . $srchInsArrys[$z] . "\" " . $valslctdArry[$z] . ">" . $srchInsArrys[$z] . "</option>";
                                }
                                $nwRowHtml31 .= "</select>
                                                </td>
                                                <td class=\"lovtd\">
                                                        <div class=\"input-group\" style=\"width:100% !important;\">
                                                            <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"attnDfltAcntsHdrsRow_WWW123WWW_ChrgAcntNm\" name=\"attnDfltAcntsHdrsRow_WWW123WWW_ChrgAcntNm\" value=\"\" readonly=\"true\" style=\"width:100% !important;\">
                                                            <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Transaction Accounts', 'allOtherInputOrgID', '', '', 'radio', true, '', 'attnDfltAcntsHdrsRow_WWW123WWW_ChrgAcntID', 'attnDfltAcntsHdrsRow_WWW123WWW_ChrgAcntNm', 'clear', 1, '', function () {});\">
                                                                <span class=\"glyphicon glyphicon-th-list\"></span>
                                                            </label>
                                                        </div>                                             
                                                </td>
                                                <td class=\"lovtd\">
                                                    <select data-placeholder=\"Select...\" class=\"form-control chosen-select\" id=\"attnDfltAcntsHdrsRow_WWW123WWW_IncrsDcrs2\" style=\"width:100% !important;\">";

                                $valslctdArry = array("", "");
                                $srchInsArrys = array("Increase", "Decrease");
                                for ($z = 0; $z < count($srchInsArrys); $z++) {
                                    $nwRowHtml31 .= "<option value=\"" . $srchInsArrys[$z] . "\" " . $valslctdArry[$z] . ">" . $srchInsArrys[$z] . "</option>";
                                }
                                $nwRowHtml31 .= "</select>
                                                </td>
                                                <td class=\"lovtd\">
                                                        <div class=\"input-group\" style=\"width:100% !important;\">
                                                            <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"attnDfltAcntsHdrsRow_WWW123WWW_BalsAcntNm\" name=\"attnDfltAcntsHdrsRow_WWW123WWW_BalsAcntNm\" value=\"\" readonly=\"true\" style=\"width:100% !important;\">
                                                            <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Transaction Accounts', 'allOtherInputOrgID', '', '', 'radio', true, '', 'attnDfltAcntsHdrsRow_WWW123WWW_BalsAcntID', 'attnDfltAcntsHdrsRow_WWW123WWW_BalsAcntNm', 'clear', 1, '', function () {});\">
                                                                <span class=\"glyphicon glyphicon-th-list\"></span>
                                                            </label>
                                                        </div>                                            
                                                </td>";
                                if ($canDel === true) {
                                    $nwRowHtml31 .= "<td class=\"lovtd\">
                                                        <button type=\"button\" class=\"btn btn-default btn-sm\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Delete Complaint/Observation\" onclick=\"delAttnDfltAcnts('attnDfltAcntsHdrsRow__WWW123WWW');\" style=\"padding:2px !important;\" style=\"padding:2px !important;\">
                                                            <img src=\"cmn_images/no.png\" style=\"height:20px; width:auto; position: relative; vertical-align: middle;\">
                                                        </button>
                                                    </td>";
                                }
                                if ($canVwRcHstry === true) {
                                    $nwRowHtml31 .= "<td class=\"lovtd\">&nbsp;</td>";
                                }
                                $nwRowHtml31 .= "</tr>";
                                $nwRowHtml33 = urlencode($nwRowHtml31);
                                ?>   
                                <div class="<?php echo $colClassType2; ?>" style="padding:0px 15px 0px 15px !important;">                      
                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="insertNewAttnDfltAcntsRows('attnDfltAcntsHdrsTable', 0, '<?php echo $nwRowHtml33; ?>');" data-toggle="tooltip" data-placement="bottom" title="Add New Complaint/Observation">
                                        <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                        NEW DEFAULT ACCOUNT
                                    </button>
                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="saveAttnDfltAcntsForm();" style="width:100% !important;">
                                        <img src="cmn_images/FloppyDisk.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                        SAVE
                                    </button> 
                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;height:30px;" onclick="getAttnDfltAcnts('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');">
                                        <img src="cmn_images/refresh.bmp" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                    </button>
                                </div>
                <?php } ?>
                        </div> 
                        <div class="row"> 
                            <div  class="col-md-12">
                                <table class="table table-striped table-bordered table-responsive" id="attnDfltAcntsHdrsTable" cellspacing="0" width="100%" style="width:100%;">
                                    <thead>
                                        <tr>
                                            <th style="max-width:35px;width:35px;">No.</th>
                                            <th>Category Name</th>
                                            <th>Increase/Decrease</th>
                                            <th>Charge Account</th>
                                            <th>Increase/Decrease</th>
                                            <th>Balancing Account</th>
                                            <?php if ($canDel === true) { ?>
                                                <th style="max-width:30px;width:30px;">...</th>
                                            <?php } ?>
                                            <?php
                                            if ($canVwRcHstry === true) {
                                                ?>
                                                <th style="max-width:30px;width:30px;">...</th>
                <?php } ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        while ($row = loc_db_fetch_array($result)) {
                                            $trsctnLnID = (float) $row[0];
                                            $trsctnLnCtgryNm = $row[1];
                                            $trsctnLnIncrsDcrs1 = $row[2];
                                            $trsctnLnChrgAcntID = (float) $row[3];
                                            $trsctnLnChrgAcntNm = $row[4];
                                            $trsctnLnIncrsDcrs2 = $row[5];
                                            $trsctnLnBalsAcntID = (float) $row[6];
                                            $trsctnLnBalsAcntNm = $row[7];
                                            ?>
                                            <tr id="attnDfltAcntsHdrsRow_<?php echo $cntr; ?>">                                    
                                                <td class="lovtd"><?php echo ($cntr + 1); ?></td>    
                                                <td class="lovtd">
                                                    <input type="hidden" class="form-control" aria-label="..." id="attnDfltAcntsHdrsRow<?php echo $cntr; ?>_TrnsLnID" value="<?php echo $trsctnLnID; ?>" style="width:100% !important;">
                                                    <input type="hidden" class="form-control" aria-label="..." id="attnDfltAcntsHdrsRow<?php echo $cntr; ?>_ChrgAcntID" value="<?php echo $trsctnLnChrgAcntID; ?>" style="width:100% !important;"> 
                                                    <input type="hidden" class="form-control" aria-label="..." id="attnDfltAcntsHdrsRow<?php echo $cntr; ?>_BalsAcntID" value="<?php echo $trsctnLnBalsAcntID; ?>" style="width:100% !important;"> 
                                                    <?php
                                                    if ($canEdt === true) {
                                                        ?>
                                                        <div class="input-group" style="width:100% !important;">
                                                            <input type="text" class="form-control" aria-label="..." id="attnDfltAcntsHdrsRow<?php echo $cntr; ?>_CtgryNm" name="attnDfltAcntsHdrsRow<?php echo $cntr; ?>_CtgryNm" value="<?php echo $trsctnLnCtgryNm; ?>" readonly="true" style="width:100% !important;">
                                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Event Cost Categories', '', '', '', 'radio', true, '', 'attnDfltAcntsHdrsRow<?php echo $cntr; ?>_CtgryNm', '', 'clear', 1, '', function () {});">
                                                                <span class="glyphicon glyphicon-th-list"></span>
                                                            </label>
                                                        </div>      
                                                    <?php } else { ?>
                                                        <span><?php echo $trsctnLnCtgryNm; ?></span>
                    <?php } ?>                                             
                                                </td> 
                                                <td class="lovtd">
                                                    <select data-placeholder="Select..." class="form-control chosen-select" id="attnDfltAcntsHdrsRow<?php echo $cntr; ?>_IncrsDcrs1" style="width:100% !important;">
                                                        <?php
                                                        $valslctdArry = array("", "");
                                                        $srchInsArrys = array("Increase", "Decrease");
                                                        for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                            if ($trsctnLnIncrsDcrs1 == $srchInsArrys[$z]) {
                                                                $valslctdArry[$z] = "selected";
                                                            }
                                                            ?>
                                                            <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                    <?php } ?>
                                                    </select>
                                                </td>
                                                <td class="lovtd">
                    <?php if ($canEdt === true) { ?>
                                                        <div class="input-group" style="width:100% !important;">
                                                            <input type="text" class="form-control" aria-label="..." id="attnDfltAcntsHdrsRow<?php echo $cntr; ?>_ChrgAcntNm" name="attnDfltAcntsHdrsRow<?php echo $cntr; ?>_ChrgAcntNm" value="<?php echo $trsctnLnChrgAcntNm; ?>" readonly="true" style="width:100% !important;">
                                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Transaction Accounts', 'allOtherInputOrgID', '', '', 'radio', true, '', 'attnDfltAcntsHdrsRow<?php echo $cntr; ?>_ChrgAcntID', 'attnDfltAcntsHdrsRow<?php echo $cntr; ?>_ChrgAcntNm', 'clear', 1, '', function () {});">
                                                                <span class="glyphicon glyphicon-th-list"></span>
                                                            </label>
                                                        </div>      
                                                    <?php } else { ?>
                                                        <span><?php echo $trsctnLnChrgAcntNm; ?></span>
                    <?php } ?>                                             
                                                </td>
                                                <td class="lovtd">
                                                    <select data-placeholder="Select..." class="form-control chosen-select" id="attnDfltAcntsHdrsRow<?php echo $cntr; ?>_IncrsDcrs2" style="width:100% !important;">
                                                        <?php
                                                        $valslctdArry = array("", "");
                                                        $srchInsArrys = array("Increase", "Decrease");
                                                        for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                            if ($trsctnLnIncrsDcrs2 == $srchInsArrys[$z]) {
                                                                $valslctdArry[$z] = "selected";
                                                            }
                                                            ?>
                                                            <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                    <?php } ?>
                                                    </select>
                                                </td>
                                                <td class="lovtd">
                                                    <?php
                                                    if ($canEdt === true) {
                                                        ?>
                                                        <div class="input-group" style="width:100% !important;">
                                                            <input type="text" class="form-control" aria-label="..." id="attnDfltAcntsHdrsRow<?php echo $cntr; ?>_BalsAcntNm" name="attnDfltAcntsHdrsRow<?php echo $cntr; ?>_BalsAcntNm" value="<?php echo $trsctnLnBalsAcntNm; ?>" readonly="true" style="width:100% !important;">
                                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Transaction Accounts', 'allOtherInputOrgID', '', '', 'radio', true, '', 'attnDfltAcntsHdrsRow<?php echo $cntr; ?>_BalsAcntID', 'attnDfltAcntsHdrsRow<?php echo $cntr; ?>_BalsAcntNm', 'clear', 1, '', function () {});">
                                                                <span class="glyphicon glyphicon-th-list"></span>
                                                            </label>
                                                        </div>      
                                                    <?php } else { ?>
                                                        <span><?php echo $trsctnLnBalsAcntNm; ?></span>
                                                <?php } ?>                                             
                                                </td>
                                                <?php
                                                if ($canDel === true) {
                                                    ?>
                                                    <td class="lovtd">
                                                        <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="Delete Default Account" onclick="delAttnDfltAcnts('attnDfltAcntsHdrsRow_<?php echo $cntr; ?>');" style="padding:2px !important;" style="padding:2px !important;">
                                                            <img src="cmn_images/no.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                        </button>
                                                    </td>
                                                <?php } ?>
                                                <?php
                                                if ($canVwRcHstry === true) {
                                                    ?>
                                                    <td class="lovtd">
                                                        <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" onclick="getRecHstry('<?php
                                                                echo urlencode(encrypt1(($row[0] . "|attn.attn_evnt_cost_accnts|evnt_cost_acnt_id"),
                                                                                $smplTokenWord1));
                                                                ?>');" style="padding:2px !important;">
                                                            <img src="cmn_images/Information.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
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
                    </fieldset>
                </form>
                <?php
            } else if ($vwtyp == 3) {
                
            }
        }
    }
}
?>