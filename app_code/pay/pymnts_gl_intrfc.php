<?php
$pageNo = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
$lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 10;
$sortBy = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : "Value";

$qNotSentToGl = true;
$qUnbalncdOnly = false;
$qUsrGnrtd = false;
$qStrtDte = "01-Jan-1900 00:00:00";
$qEndDte = "31-Dec-4000 23:59:59";
$qNwStrtDte = date('d-M-Y H:i:s');
$qLowVal = 0;
$qHighVal = 0;
$canVwRcHstry = test_prmssns("View Record History", $mdlNm);
if (isset($_POST['qNotSentToGl'])) {
    $qNotSentToGl = cleanInputData($_POST['qNotSentToGl']) === "true" ? true : false;
}

if (isset($_POST['qUnbalncdOnly'])) {
    $qUnbalncdOnly = cleanInputData($_POST['qUnbalncdOnly']) === "true" ? true : false;
}

if (isset($_POST['qUsrGnrtd'])) {
    $qUsrGnrtd = cleanInputData($_POST['qUsrGnrtd']) === "true" ? true : false;
}

if (isset($_POST['qLowVal'])) {
    $qLowVal = (float) cleanInputData($_POST['qLowVal']);
}

if (isset($_POST['qHighVal'])) {
    $qHighVal = (float) cleanInputData($_POST['qHighVal']);
}

if (isset($_POST['qStrtDte'])) {
    $qStrtDte = cleanInputData($_POST['qStrtDte']);
    if (strlen($qStrtDte) == 11) {
        $qStrtDte = substr($qStrtDte, 0, 11) . " 00:00:00";
    } else {
        $qStrtDte = "01-Jan-1900 00:00:00";
    }
}

if (isset($_POST['qNwStrtDte'])) {
    $qNwStrtDte = cleanInputData($_POST['qNwStrtDte']);
    if (strlen($qNwStrtDte) == 11) {
        $qNwStrtDte = substr($qNwStrtDte, 0, 11) . " 00:00:00";
    } else {
        $qNwStrtDte = date('d-M-Y H:i:s');
    }
}

if (isset($_POST['qEndDte'])) {
    $qEndDte = cleanInputData($_POST['qEndDte']);
    if (strlen($qEndDte) == 11) {
        $qEndDte = substr($qEndDte, 0, 11) . " 23:59:59";
    } else {
        $qEndDte = "31-Dec-4000 23:59:59";
    }
}

if (array_key_exists('lgn_num', get_defined_vars())) {
    if ($lgn_num > 0 && $canview === true) {
        if ($qstr == "DELETE") {
            if ($actyp == 1) {
                /* Delete Interface Transaction */
                $canDelTrns = test_prmssns($dfltPrvldgs[21], $mdlNm);
                $slctdIntrfcIDs = isset($_POST['slctdIntrfcIDs']) ? cleanInputData($_POST['slctdIntrfcIDs']) : '';
                $variousRows = explode("|", trim($slctdIntrfcIDs, "|"));
                $affctd1 = 0;
                if ($canDelTrns) {
                    for ($z = 0; $z < count($variousRows); $z++) {
                        $crntRow = explode("~", $variousRows[$z]);
                        if (count($crntRow) == 2) {
                            $intrfcID = (float) cleanInputData1($crntRow[0]);
                            $intrfcIDDesc = cleanInputData1($crntRow[1]);
                            $affctd1 += deletePAYTrnsGLIntFcLn($intrfcID, $intrfcIDDesc);
                        }
                    }
                    if ($affctd1 > 0) {
                        $dsply = "Successfully Deleted the ff Records-";
                        $dsply .= "<br/>$affctd1 Interface Transaction(s)!";
                        echo "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
                    } else {
                        $dsply = "No Record Deleted!";
                        echo "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
                    }
                } else {
                    restricted();
                }
            } else if ($actyp == 5) {
                
            }
        } else if ($qstr == "UPDATE") {
            if ($actyp == 1) {
                header("content-type:application/json");
                $glIntrfcTrnsID = isset($_POST['glIntrfcTrnsID']) ? (float) cleanInputData($_POST['glIntrfcTrnsID']) : -1;
                $glIntrfcTrnsDate = isset($_POST['glIntrfcTrnsDate']) ? cleanInputData($_POST['glIntrfcTrnsDate']) : '';
                $glIntrfcTrnsDesc = isset($_POST['glIntrfcTrnsDesc']) ? cleanInputData($_POST['glIntrfcTrnsDesc']) : '';
                $intrfcAccntID = isset($_POST['intrfcAccntID']) ? (float) cleanInputData($_POST['intrfcAccntID']) : -1;
                $incrsDcrs = isset($_POST['incrsDcrs']) ? cleanInputData($_POST['incrsDcrs']) : "";
                $enteredCrncyNm = isset($_POST['enteredCrncyNm']) ? cleanInputData($_POST['enteredCrncyNm']) : "";
                $enteredCrncyID = getPssblValID($enteredCrncyNm, getLovID("Currencies"));
                $enteredAmount = isset($_POST['enteredAmount']) ? (float) cleanInputData($_POST['enteredAmount']) : 0;
                $funcCrncyRate = isset($_POST['funcCrncyRate']) ? (float) cleanInputData($_POST['funcCrncyRate']) : 0;
                $accntCrncyRate = isset($_POST['accntCrncyRate']) ? (float) cleanInputData($_POST['accntCrncyRate']) : 0;
                $funcCrncyAmount = isset($_POST['funcCrncyAmount']) ? (float) cleanInputData($_POST['funcCrncyAmount']) : 0;
                $accntCrncyAmount = isset($_POST['accntCrncyAmount']) ? (float) cleanInputData($_POST['accntCrncyAmount']) : 0;
                $accntCurrID = (float) getGnrlRecNm("accb.accb_chart_of_accnts", "accnt_id", "crncy_id", $intrfcAccntID);
                $accntCurrNm = getPssblValNm($accntCurrID);
                $funcCurrID = getOrgFuncCurID($orgID);
                $funcCrncyNm = getPssblValNm($funcCurrID);
                $errMsg = "";
                $affctd = 0;
                //var_dump($_POST);
                //echo $glIntrfcTrnsDesc . ":" . $glIntrfcTrnsDate . ":" . $incrsDcrs . ":" . $intrfcAccntID . ":" . $enteredAmount . ":" . $enteredCrncyID;
                if ($glIntrfcTrnsDesc != "" && $glIntrfcTrnsDate != "" && $incrsDcrs != "" && $intrfcAccntID > 0 && $enteredAmount != 0 && $enteredCrncyID
                        > 0) {
                    $netAmnt = (float) dbtOrCrdtAccntMultiplier($intrfcAccntID, substr($incrsDcrs, 0, 1)) * (float) $funcCrncyAmount;
                    if (!isTransPrmttd($intrfcAccntID, $glIntrfcTrnsDate, $netAmnt, $errMsg)) {
                        $arr_content['percent'] = 100;
                        $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>Accounting not Allowed!<br/>$errMsg</span>";
                        echo json_encode($arr_content);
                        exit();
                    }
                    $netamnt = dbtOrCrdtAccntMultiplier($intrfcAccntID, substr($incrsDcrs, 0, 1)) * (float) $funcCrncyAmount;
                    $dateStr = getFrmtdDB_Date_time();
                    if (dbtOrCrdtAccnt($intrfcAccntID, substr($incrsDcrs, 0, 1)) == "Debit") {
                        if ($glIntrfcTrnsID <= 0) {
                            $affctd += createPAYTrnsGLIntFcLn($intrfcAccntID, $glIntrfcTrnsDesc, $funcCrncyAmount, $glIntrfcTrnsDate,
                                    $funcCurrID, 0, $netamnt, "Imbalance Correction", -1, -1, $dateStr, "", "USR", $enteredAmount,
                                    $enteredCrncyID, $accntCrncyAmount, $accntCurrID, $funcCrncyRate, $accntCrncyRate);
                        } else {
                            $affctd += updatePAYTrnsGLIntFcLn($glIntrfcTrnsID, $intrfcAccntID, $glIntrfcTrnsDesc, $funcCrncyAmount,
                                    $glIntrfcTrnsDate, $funcCurrID, 0, $netamnt, "Imbalance Correction", -1, -1, $dateStr, "", "USR",
                                    $enteredAmount, $enteredCrncyID, $accntCrncyAmount, $accntCurrID, $funcCrncyRate, $accntCrncyRate);
                        }
                    } else {
                        if ($glIntrfcTrnsID <= 0) {
                            $affctd += createPAYTrnsGLIntFcLn($intrfcAccntID, $glIntrfcTrnsDesc, 0, $glIntrfcTrnsDate, $funcCurrID,
                                    $funcCrncyAmount, $netamnt, "Imbalance Correction", -1, -1, $dateStr, "", "USR", $enteredAmount,
                                    $enteredCrncyID, $accntCrncyAmount, $accntCurrID, $funcCrncyRate, $accntCrncyRate);
                        } else {
                            $affctd += updatePAYTrnsGLIntFcLn($glIntrfcTrnsID, $intrfcAccntID, $glIntrfcTrnsDesc, 0, $glIntrfcTrnsDate,
                                    $funcCurrID, $funcCrncyAmount, $netamnt, "Imbalance Correction", -1, -1, $dateStr, "", "USR",
                                    $enteredAmount, $enteredCrncyID, $accntCrncyAmount, $accntCurrID, $funcCrncyRate, $accntCrncyRate);
                        }
                    }
                    $arr_content['percent'] = 100;
                    $arr_content['message'] = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>" . $affctd . " Interface Transaction(s) Successfully Saved!<br/>" . $errMsg;
                    echo json_encode($arr_content);
                    exit();
                } else {
                    $arr_content['percent'] = 100;
                    $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>Data Supplied is Incomplete or Invalid at some fields!</span>";
                    echo json_encode($arr_content);
                    exit();
                }
            }
        } else {
            if ($vwtyp == 0) {
                echo $cntent . "<li onclick=\"openATab('#allmodules', 'grp=$group&typ=$type&pg=$pgNo&vtyp=0');\">
                                    <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                    <span style=\"text-decoration:none;\">GL Interface</span>
				</li>
                               </ul>
                              </div>";
                //GL Interface Table
                $canSendToGl = test_prmssns($dfltPrvldgs[21], $mdlNm);
                $canAddCrctnTrns = test_prmssns($dfltPrvldgs[21], $mdlNm);
                $canDelCrctnTrns = test_prmssns($dfltPrvldgs[21], $mdlNm);
                $total = get_PAYGlIntrfcTtl($srchFor, $srchIn, $orgID, $qStrtDte, $qEndDte, $qNotSentToGl, $qUnbalncdOnly, $qUsrGnrtd,
                        $qLowVal, $qHighVal);
                if ($pageNo > ceil($total / $lmtSze)) {
                    $pageNo = 1;
                } else if ($pageNo < 1) {
                    $pageNo = ceil($total / $lmtSze);
                }
                $curIdx = $pageNo - 1;
                $result = get_PAYGlIntrfc(
                        $srchFor, $srchIn, $curIdx, $lmtSze, $orgID, $qStrtDte, $qEndDte, $qNotSentToGl, $qUnbalncdOnly, $qUsrGnrtd,
                        $qLowVal, $qHighVal
                );
                $cntr = 0;
                ?> 
                <form id='allPayGLIntrfcsForm' action='' method='post' accept-charset='UTF-8'>
                    <div class="row " style="margin-bottom:0px;padding:0px 15px 0px 15px !important;">
                        <div class="col-lg-3" style="padding:0px 1px 0px 1px !important;">
                            <div class="input-group">
                                <input class="form-control" id="allPayGLIntrfcsSrchFor" type = "text" placeholder="Search For" value="<?php
                                echo trim(str_replace("%", " ", $srchFor));
                                ?>" onkeyup="enterKeyFuncAllPayGLIntrfcs(event, '', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>')">
                                <input id="allPayGLIntrfcsPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                <label class="btn btn-primary btn-file input-group-addon" onclick="getAllPayGLIntrfcs('clear', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>')">
                                    <span class="glyphicon glyphicon-remove"></span>
                                </label>
                                <label class="btn btn-primary btn-file input-group-addon" onclick="getAllPayGLIntrfcs('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');">
                                    <span class="glyphicon glyphicon-search"></span>
                                </label> 
                            </div>
                        </div>
                        <div class="col-lg-3" style="padding:0px 1px 0px 1px !important;">
                            <div class="input-group">
                                <span class="input-group-addon">In</span>
                                <select data-placeholder="Select..." class="form-control chosen-select" id="allPayGLIntrfcsSrchIn">
                                    <?php
                                    $valslctdArry = array("", "", "", "", "");
                                    $srchInsArrys = array("Account Name", "Account Number", "Source",
                                        "Transaction Date", "Transaction Description");
                                    for ($z = 0; $z < count($srchInsArrys); $z++) {
                                        if ($srchIn == $srchInsArrys[$z]) {
                                            $valslctdArry[$z] = "selected";
                                        }
                                        ?>
                                        <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                    <?php } ?>
                                </select>
                                <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                <select data-placeholder="Select..." class="form-control chosen-select" id="allPayGLIntrfcsDsplySze" style="min-width:65px !important;">                            
                                    <?php
                                    $valslctdArry = array("", "", "", "", "", "",
                                        "", "");
                                    $dsplySzeArry = array(1, 5, 10, 15, 30, 50, 100,
                                        500, 1000);
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
                        <div class="col-lg-4" style="padding:0px 1px 0px 1px !important;">
                            <div class="col-xs-6" style="padding:0px 1px 0px 0px !important;">
                                <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                    <input class="form-control" size="16" type="text" id="allPayGLIntrfcsStrtDate" name="allPayGLIntrfcsStrtDate" value="<?php
                                    echo substr($qStrtDte, 0, 11);
                                    ?>" placeholder="Start Date">
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                </div></div>
                            <div class="col-xs-6" style="padding:0px 1px 0px 0px !important;">
                                <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                    <input class="form-control" size="16" type="text"  id="allPayGLIntrfcsEndDate" name="allPayGLIntrfcsEndDate" value="<?php
                                    echo substr($qEndDte, 0, 11);
                                    ?>" placeholder="End Date">
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                </div>
                            </div>                            
                        </div>
                        <div class="col-lg-2" style="padding:0px 1px 0px 1px !important;">
                            <nav aria-label="Page navigation">
                                <ul class="pagination" style="margin: 0px !important;">
                                    <li>
                                        <a class="rhopagination" href="javascript:getAllPayGLIntrfcs('previous', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');" aria-label="Previous">
                                            <span aria-hidden="true">&laquo;</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="rhopagination" href="javascript:getAllPayGLIntrfcs('next', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');" aria-label="Next">
                                            <span aria-hidden="true">&raquo;</span>
                                        </a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                    <div class="row " style="margin-bottom:2px;padding:2px 15px 2px 15px !important">   
                        <div class="col-md-12" style="padding:2px 1px 2px 1px !important;border-top:1px solid #ddd;border-bottom:1px solid #ddd;">
                            <div class="col-md-3" style="padding:5px 1px 0px 1px !important;">
                                <div class="form-check" style="font-size: 12px !important;">
                                    <label class="form-check-label">
                                        <?php
                                        $notToGlChekd = "";
                                        if ($qNotSentToGl == true) {
                                            $notToGlChekd = "checked=\"true\"";
                                        }
                                        $notBalcdChekd = "";
                                        if ($qUnbalncdOnly == true) {
                                            $notBalcdChekd = "checked=\"true\"";
                                        }
                                        $usrTrnsChekd = "";
                                        if ($qUsrGnrtd == true) {
                                            $usrTrnsChekd = "checked=\"true\"";
                                        }
                                        ?>
                                        <input type="checkbox" class="form-check-input" onclick="getAllPayGLIntrfcs('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');" id="allPayGLIntrfcsSntToGl" name="allPayGLIntrfcsSntToGl" <?php echo $notToGlChekd; ?>>
                                        Transactions Not Sent to GL
                                    </label>
                                </div>                            
                            </div>
                            <div class="col-md-3" style="padding:5px 1px 0px 1px !important;">
                                <div class="form-check" style="font-size: 12px !important;">
                                    <label class="form-check-label">
                                        <input type="checkbox" class="form-check-input" onclick="getAllPayGLIntrfcs('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');" id="allPayGLIntrfcsUnbalncd" name="allPayGLIntrfcsUnbalncd"  <?php echo $notBalcdChekd; ?>>
                                        Possible Unbalanced Trns.
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-2" style="padding:5px 1px 0px 1px !important;">
                                <div class="form-check" style="font-size: 12px !important;">
                                    <label class="form-check-label">
                                        <input type="checkbox" class="form-check-input" onclick="getAllPayGLIntrfcs('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');" id="allPayGLIntrfcsUsrTrns" name="allPayGLIntrfcsUsrTrns"  <?php echo $usrTrnsChekd; ?>>
                                        User Generated Trns.
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-2" style="padding:0px 1px 0px 1px !important;">
                                <div class="input-group">
                                    <label class="btn btn-primary btn-file input-group-addon">
                                        <span class="glyphicon glyphicon-sort-by-order"></span>
                                    </label>
                                    <input class="form-control" id="allPayGLIntrfcsLowVal" type = "number" placeholder="Low Value" value="<?php
                                    echo $qLowVal;
                                    ?>" onkeyup="enterKeyFuncAllPayGLIntrfcs(event, '', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>')">
                                </div>
                            </div>   
                            <div class="col-md-2" style="padding:0px 1px 0px 1px !important;">
                                <div class="input-group">
                                    <label class="btn btn-primary btn-file input-group-addon">
                                        <span class="glyphicon glyphicon-sort-by-order-alt"></span>
                                    </label>
                                    <input class="form-control" id="allPayGLIntrfcsHighVal" type = "number" placeholder="High Value" value="<?php
                                    echo $qHighVal;
                                    ?>" onkeyup="enterKeyFuncAllPayGLIntrfcs(event, '', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>')">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row " style="margin-bottom:2px;padding:2px 15px 2px 15px !important;">
                        <div class="col-md-12" style="padding:0px 0px 0px 0px !important;border-top:1px solid #ddd;border-bottom:1px solid #ddd;">
                            <div class="col-md-4" style="padding:2px 1px 2px 1px !important;">
                                <div class="input-group">
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getAllPayGLIntrfcs('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');">
                                        <span style="font-weight:bold;">Imbalance Amount</span>
                                    </label>
                                    <?php
                                    $dffrc = (float) getPAYGLIntrfcDffrnc($orgID);
                                    $style1 = "color:green;";
                                    if (abs($dffrc) != 0) {
                                        $style1 = "color:red;";
                                    }
                                    ?>
                                    <input class="form-control" id="allPayGLIntrfcsImbalsAmt" type = "text" placeholder="0.00" value="<?php
                                    echo number_format($dffrc, 2);
                                    ?>" readonly="true" style="font-weight:bold;<?php echo $style1; ?>">
                                </div>
                            </div>   
                            <div class="col-md-8" style="padding:2px 1px 2px 1px !important;">
                                <?php if ($canAddCrctnTrns && abs($dffrc) != 0) { ?>
                                    <button type="button" class="btn btn-default btn-sm" onclick="getOnePAYGLIntrfcForm(-1);">
                                        <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                        Add Correction Trns.
                                    </button>
                                <?php } ?>                           
                                <?php if ($canDelCrctnTrns) { ?>
                                    <button type="button" class="btn btn-default btn-sm" onclick="delSlctdPAYIntrfcLines();">
                                        <img src="cmn_images/90.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                        Void/Delete
                                    </button>
                                <?php } ?> 
                                <?php /* if ($canSendToGl) { ?>
                                  <button type="button" class="btn btn-default btn-sm" onclick="getOneVmsTrnsForm(-1, '<?php echo $trnsType; ?>', 20, 'ShowDialog',<?php echo $vwtyp; ?>, '<?php echo $srcMenu; ?>');">
                                  <img src="cmn_images/98.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                  Auto-Correct Wrong Transfers
                                  </button>
                                  <?php } */ ?>                            
                                <?php
                                if ($canSendToGl) {
                                    $reportTitle = "Journal Import from Payroll Module-Web";
                                    $reportName = "Journal Import from Payroll Module-Web";
                                    $rptID = getRptID($reportName);
                                    $prmID1 = getParamIDUseSQLRep("{:glbatch_name}", $rptID);
                                    $prmID2 = getParamIDUseSQLRep("{:intrfc_tbl_name}", $rptID);
                                    $glBtchNm = "%Payroll%";
                                    $pIntrfcTblNm = "pay.pay_gl_interface";
                                    $paramRepsNVals = $prmID1 . "~" . $glBtchNm . "|" . $prmID2 . "~" . $pIntrfcTblNm . "|-130~" . $reportTitle . "|-190~PDF";
                                    $paramStr = urlencode($paramRepsNVals);
                                    ?>
                                    <button type="button" class="btn btn-default btn-sm" onclick="getMyMdlRptRuns('', 'ShowDialog', 'grp=9&typ=1&pg=1&vtyp=50&sbmtdRptID=<?php echo $rptID; ?>');">
                                        <img src="cmn_images/98.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                        Send Outstanding Trns. to GL
                                    </button>
                                <?php } ?>                       
                                <?php
                                if ($canSendToGl) {
                                    $reportTitle = "Post GL Transaction Batches-Web";
                                    $reportName = "Post GL Transaction Batches-Web";
                                    $rptID = getRptID($reportName);
                                    $paramRepsNVals = "-130~" . $reportTitle . "|-190~PDF";
                                    $paramStr = urlencode($paramRepsNVals);
                                    ?>
                                    <button type="button" class="btn btn-default btn-sm" onclick="getMyMdlRptRuns('', 'ShowDialog', 'grp=9&typ=1&pg=1&vtyp=50&sbmtdRptID=<?php echo $rptID; ?>');">
                                        <img src="cmn_images/98.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                        Post GL Transactions
                                    </button>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </form>
                <form id='allPayGLIntrfcsHdrsForm' action='' method='post' accept-charset='UTF-8'>
                    <div class="row"> 
                        <div  class="col-md-12">
                            <table class="table table-striped table-bordered table-responsive" id="allPayGLIntrfcsHdrsTable" cellspacing="0" width="100%" style="width:100%;">
                                <thead>
                                    <tr>
                                        <th>&nbsp;</th>
                                        <th>No.</th>
                                        <th>Account</th>
                                        <th>Remark/Narration</th>
                                        <th style="text-align:right;">CUR.</th>
                                        <th style="text-align:right;min-width: 100px;width: 100px;">Debit Amount</th>
                                        <th style="text-align:right;min-width: 100px;width: 100px;">Credit Amount</th>
                                        <th>Transaction Date</th>
                                        <th>Source</th>
                                        <th>GL Batch Name</th>                                          
                                        <th>&nbsp;</th>
                                        <?php if ($canVwRcHstry === true) { ?>
                                            <th>...</th>
                                        <?php } ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    while ($row = loc_db_fetch_array($result)) {
                                        /**/
                                        $cntr += 1;
                                        ?>
                                        <tr id="allPayGLIntrfcsHdrsRow_<?php echo $cntr; ?>">
                                            <td class="lovtd">
                                                <input type="checkbox" name="allPayGLIntrfcsHdrsRow<?php echo $cntr; ?>_CheckBox" value="<?php echo $row[0] . ";" . $row[1]; ?>">
                                                <input type="hidden" value="<?php echo $row[0]; ?>" id="allPayGLIntrfcsHdrsRow<?php echo $cntr; ?>_AccntID">
                                                <input type="hidden" value="<?php echo $row[11]; ?>" id="allPayGLIntrfcsHdrsRow<?php echo $cntr; ?>_IntrfcID">
                                            </td>                                    
                                            <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                            <td class="lovtd"><?php echo $row[1] . ": " . $row[2]; ?></td>
                                            <td class="lovtd"><?php echo $row[3]; ?></td>
                                            <td class="lovtd" style="text-align:right;font-weight:bold;"><?php echo $row[15]; ?></td>
                                            <td class="lovtd" style="text-align:right;font-weight:bold;color:blue;"><?php echo number_format((float) $row[5], 2); ?></td>
                                            <td class="lovtd" style="text-align:right;font-weight:bold;color:blue;"><?php echo number_format((float) $row[6], 2); ?></td>
                                            <td class="lovtd"><?php echo $row[4]; ?></td>
                                            <td class="lovtd"><?php echo $row[8] . " - " . $row[14]; ?></td>
                                            <td class="lovtd"><?php echo $row[10]; ?></td>
                                            <td class="lovtd">                                                     
                                                <button type="button" class="btn btn-default btn-sm" onclick="getOnePAYGLIntrfcForm(<?php echo $row[11]; ?>, 'allPayGLIntrfcsHdrsRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="View Details" style="padding:2px !important;">
                                                    <img src="cmn_images/edit32.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                </button>
                                                <?php if ($row[16] != "SYS" && ((float) $row[9]) <= 0) { ?>  
                                                <?php } else { ?>
                                                <?php } ?>
                                            </td>
                                            <?php if ($canVwRcHstry === true) { ?>
                                                <td class="lovtd">
                                                    <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" onclick="getRecHstry('<?php echo urlencode(encrypt1(($row[11] . "|pay.pay_gl_interface|interface_id"),
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
                </form>                
                <?php
            } else if ($vwtyp == 1) {
                //New Interface Transaction Form
                $canEdtIntrfc = test_prmssns($dfltPrvldgs[21], $mdlNm);
                $sbmtdIntrfcID = isset($_POST['sbmtdIntrfcID']) ? cleanInputData($_POST['sbmtdIntrfcID']) : -1;
                $slctdIntrfcID = isset($_POST['slctdIntrfcID']) ? cleanInputData($_POST['slctdIntrfcID']) : -1;
                $intrfcTrnsDesc = "";
                $intrfcTrnsDate = "";
                $incrsDcrs = "";
                $accntID = -1;
                $glBatchID = -1;
                $accntNum = "";
                $accntName = "";
                $enteredAmount = 0;
                $enteredCrncyID = -1;
                $enteredCrncyNm = "";
                $funcCrncyRate = 0;
                $accntCrncyRate = 0;
                $funcCrncyAmount = 0;
                $accntCrncyAmount = 0;
                $funcCurrID = -1;
                $funcCrncyNm = "";
                $accntCrncyNm = "";
                $trnsSource = "USR";
                $mkReadOnly = "";
                if ($sbmtdIntrfcID > 0) {
                    $result = get_OnePAYGlIntrfcDet($sbmtdIntrfcID);
                    while ($row = loc_db_fetch_array($result)) {
                        $sbmtdIntrfcID = (float) $row[11];
                        $glBatchID = (float) $row[9];
                        $intrfcTrnsDesc = $row[3];
                        $intrfcTrnsDate = $row[4];
                        $accntID = (float) $row[0];
                        $accntNum = $row[1];
                        $accntName = $row[2];
                        $dbtAmnt = (float) $row[5];
                        $crdtAmnt = (float) $row[6];
                        $dbtOrCrdt = "C";
                        if (abs($dbtAmnt) > abs($crdtAmnt)) {
                            $dbtOrCrdt = "D";
                        }
                        if ($dbtOrCrdt == "C") {
                            $incrsDcrs = incrsOrDcrsAccnt($accntID, "Credit");
                            $funcCrncyAmount = $crdtAmnt;
                        } else {
                            $incrsDcrs = incrsOrDcrsAccnt($accntID, "Debit");
                            $funcCrncyAmount = $dbtAmnt;
                        }
                        $enteredAmount = (float) $row[18];
                        $enteredCrncyID = (float) $row[19];
                        $enteredCrncyNm = $row[20];
                        $funcCrncyRate = (float) $row[24];
                        $accntCrncyRate = (float) $row[25];
                        $accntCrncyAmount = (float) $row[21];
                        $funcCurrID = (float) $row[12];
                        $funcCrncyNm = $row[15];
                        $accntCurrID = (float) $row[22];
                        //getGnrlRecNm("accb.accb_chart_of_accnts", "accnt_id", "crncy_id", $accntID);
                        $accntCrncyNm = $row[23];
                        //getPssblValNm($accntCurrID);
                        $trnsSource = $row[16];
                        if ($glBatchID > 0) {
                            //$trnsSource == "SYS" || 
                            $canEdtIntrfc = FALSE;
                            $mkReadOnly = "readonly=\"true\"";
                        }
                    }
                } else {
                    $dfrnce = round(getPAYGLIntrfcDffrnc($orgID), 2);
                    if ($dfrnce == 0) {
                        echo "<div id='rho_form'><H1 style=\"text-align:center; color:red;\">NO IMBALANCE!!!</H1>
                                    <p style=\"text-align:center; color:red;\"><b><i>Sorry, There's no Imbalance to correct! Thank You!</i></b></p>
                                 </div>";
                        exit();
                    }
                }
                ?>
                <form class="form-horizontal" id='addGLIntrfcsForm' action='' method='post' accept-charset='UTF-8'>
                    <div class="row">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <div class="col-md-4">
                                            <label for="glIntrfcTrnsDate" class="control-label">Transaction Date:</label>
                                        </div>
                                        <div class="col-md-8">
                <?php if ($canEdtIntrfc === true) { ?>                                                
                                                <div class="input-group date form_date_tme" data-date="" data-date-format="dd-M-yyyy hh:ii:ss" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd hh:ii:ss">
                                                    <input class="form-control" size="16" type="text" id="glIntrfcTrnsDate" value="<?php echo $intrfcTrnsDate; ?>" readonly="true">
                                                    <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                </div>
                                            <?php } else { ?>
                                                <span><?php echo $intrfcTrnsDate; ?></span>
                <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <div class="col-md-4">
                                            <label for="glIntrfcTrnsDesc" class="control-label">Transaction Description:</label>
                                        </div>
                                        <div class="col-md-8">     
                <?php if ($canEdtIntrfc === true) { ?>
                                                <textarea rows="2" name="glIntrfcTrnsDesc" id="glIntrfcTrnsDesc" class="form-control rqrdFld"><?php echo $intrfcTrnsDesc; ?></textarea>
                                                <input type="hidden" name="glIntrfcTrnsID" id="glIntrfcTrnsID" class="form-control" value="<?php echo $sbmtdIntrfcID; ?>">                                                       
                                            <?php } else { ?>
                                                <span><?php echo $intrfcTrnsDesc; ?></span>
                <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <div class="col-md-4">
                                            <label for="incrsDcrs" class="control-label">Action:</label>
                                        </div>
                                        <div class="col-md-8">
                                                <?php if ($canEdtIntrfc === true) { ?>
                                                <select class="form-control" id="incrsDcrs">                                                        
                                                    <?php
                                                    $valslctdArry = array("", "");
                                                    $valuesArrys = array("Increase", "Decrease");

                                                    for ($z = 0; $z < count($valuesArrys); $z++) {
                                                        if (strtoupper($incrsDcrs) == strtoupper($valuesArrys[$z])) {
                                                            $valslctdArry[$z] = "selected";
                                                        }
                                                        ?>
                                                        <option value="<?php echo $valuesArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $valuesArrys[$z]; ?></option>
                                                <?php } ?>
                                                </select>
                                            <?php } else { ?>
                                                <span><?php echo $incrsDcrs; ?></span>
                <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <div class="col-md-4">
                                            <label for="intrfcGLAccountNm" class="control-label">GL Account:</label>
                                        </div>
                                        <div class="col-md-8">
                <?php if ($canEdtIntrfc === true) { ?>                                   
                                                <div class="input-group">
                                                    <input type="text" name="intrfcGLAccountNm" id="intrfcGLAccountNm" class="form-control" value="<?php echo $accntNum . "." . $accntName; ?>" readonly="true">
                                                    <input type="hidden" name="intrfcAccntID" id="intrfcAccntID" class="form-control" value="<?php echo $accntID; ?>">
                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Asset Accounts', 'allOtherInputOrgID', '', '', 'radio', true, '', 'intrfcAccntID', 'intrfcGLAccountNm', 'clear', 0, '', function () {
                                                                                    afterPAYIntrfcItemSlctn();
                                                                                });"> 
                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                    </label>
                                                </div>
                                            <?php } else { ?>
                                                <span><?php echo $accntNum . "." . $accntName; ?></span>
                <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">                                    
                                    <div class="col-sm-12">
                                        <div class="col-md-4">
                                            <label for="enteredCrncyNm" class="control-label">Entered Amount:</label>
                                        </div>
                                        <div class="col-md-8">
                <?php if ($canEdtIntrfc === true) { ?>  
                                                <div class="input-group">                                    
                                                    <input type="text" class="form-control rqrdFld" aria-label="..." id="enteredCrncyNm" name="enteredCrncyNm" value="<?php echo $enteredCrncyNm; ?>" readonly="true" style="width:60px;max-width:60px;">    
                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Currencies', '', '', '', 'radio', true, '<?php echo $enteredCrncyNm; ?>', 'enteredCrncyNm', '', 'clear', 0, '', function () {
                                                                                    afterPAYIntrfcItemSlctn();
                                                                                });"> 
                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                    </label> 
                                                    <input class="form-control rqrdFld vmsTtlAmt" type="text" id="enteredAmount" value="<?php
                                                           echo number_format($enteredAmount, 2);
                                                           ?>"  style="font-weight:bold;" onchange="afterPAYIntrfcItemSlctn();"/>
                                                </div>
                                            <?php } else { ?>
                                                <span><?php echo $enteredCrncyNm . " " . number_format($enteredAmount, 2); ?></span>
                <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <div class="col-md-4">
                                            <label for="funcCrncyRate" class="control-label">Func. Curr. Rate:</label>
                                        </div>
                                        <div class="col-md-8">
                                            <?php if ($canEdtIntrfc === true) { ?>                                 
                                                <input type="number" name="funcCrncyRate" id="funcCrncyRate" class="form-control rqrdFld" value="<?php echo $funcCrncyRate; ?>">                                                        
                                            <?php } else { ?>
                                                <span><?php echo $funcCrncyRate; ?></span>
                <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <div class="col-md-4">
                                            <label for="accntCrncyRate" class="control-label">Accnt. Curr. Rate:</label>
                                        </div>
                                        <div class="col-md-8">
                                            <?php if ($canEdtIntrfc === true) { ?>                                 
                                                <input type="number" name="accntCrncyRate" id="accntCrncyRate" class="form-control rqrdFld" value="<?php echo $accntCrncyRate; ?>">                                                        
                                            <?php } else { ?>
                                                <span><?php echo $accntCrncyRate; ?></span>
                <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">                                    
                                    <div class="col-sm-12">
                                        <div class="col-md-4">
                                            <label for="funcCrncyAmount" class="control-label">Func. Curr. Amnt:</label>
                                        </div>
                                        <div class="col-md-8">
                <?php if ($canEdtIntrfc === true) { ?>  
                                                <div class="input-group">  
                                                    <label class="btn btn-primary btn-file input-group-addon" id="funcCrncyNm"> 
                    <?php echo $funcCrncyNm; ?>
                                                    </label> 
                                                    <input class="form-control" type="text" id="funcCrncyAmount" value="<?php
                                                           echo number_format($funcCrncyAmount, 2);
                                                           ?>"  style="font-weight:bold;" onchange="fmtAsNumber('funcCrncyAmount');" readonly="true"/>
                                                </div>
                                            <?php } else { ?>
                                                <span><?php echo $funcCrncyNm . " " . number_format($funcCrncyAmount, 2); ?></span>
                <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">                                    
                                    <div class="col-sm-12">
                                        <div class="col-md-4">
                                            <label for="accntCrncyAmount" class="control-label">Accnt. Curr. Amnt:</label>
                                        </div>
                                        <div class="col-md-8">
                <?php if ($canEdtIntrfc === true) { ?>  
                                                <div class="input-group">   
                                                    <label class="btn btn-primary btn-file input-group-addon" id="accntCrncyNm"> 
                    <?php echo $accntCrncyNm; ?>
                                                    </label>
                                                    <input class="form-control" type="text" id="accntCrncyAmount" value="<?php
                                                           echo number_format($accntCrncyAmount, 2);
                                                           ?>"  style="font-weight:bold;" onchange="fmtAsNumber('accntCrncyAmount');" readonly="true"/>
                                                </div>
                                            <?php } else { ?>
                                                <span><?php echo $accntCrncyNm . " " . number_format($accntCrncyAmount, 2); ?></span>
                <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>                               
                        </div>
                        <div class="row" style="float:right;padding-right: 30px;margin-top: 5px;">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <?php if ($canEdtIntrfc === true && $glBatchID <= 0) { //&& $trnsSource != "SYS"    ?>
                                <button type="button" class="btn btn-primary" onclick="savePAYGLIntrfcForm();">Save Changes</button>
                <?php } ?>
                        </div>
                    </div>
                </form>                    
                <?php
            } else if ($vwtyp == 2) {
                header("content-type:application/json");
                $intrfcAccntID = isset($_POST['intrfcAccntID']) ? (float) cleanInputData($_POST['intrfcAccntID']) : -1;
                $trnsDate = isset($_POST['glIntrfcTrnsDate']) ? cleanInputData($_POST['glIntrfcTrnsDate']) : "";
                $funcCrncyRate = isset($_POST['funcCrncyRate']) ? cleanInputData($_POST['funcCrncyRate']) : "";
                $accntCrncyRate = isset($_POST['accntCrncyRate']) ? cleanInputData($_POST['accntCrncyRate']) : "";
                $enteredCrncyNm = isset($_POST['enteredCrncyNm']) ? cleanInputData($_POST['enteredCrncyNm']) : "";
                $enteredAmount = isset($_POST['enteredAmount']) ? (float) cleanInputData($_POST['enteredAmount']) : 0;
                $accntCurrID = (float) getGnrlRecNm("accb.accb_chart_of_accnts", "accnt_id", "crncy_id", $intrfcAccntID);
                $accntCurrNm = getPssblValNm($accntCurrID);
                $enteredCrncyID = getPssblValID($enteredCrncyNm, getLovID("Currencies"));

                $funcCurrID = getOrgFuncCurID($orgID);
                $funcCrncyNm = getPssblValNm($funcCurrID);
                if ($funcCrncyRate == 1 || $funcCrncyRate == 0) {
                    $funcCrncyRate = get_LtstExchRate($enteredCrncyID, $funcCurrID, $trnsDate);
                }
                if ($accntCrncyRate == 1 || $accntCrncyRate == 0) {
                    $accntCrncyRate = get_LtstExchRate($enteredCrncyID, $accntCurrID, $trnsDate);
                }
                $funcCrncyAmount = ($funcCrncyRate * $enteredAmount);
                $accntCrncyAmount = ($accntCrncyRate * $enteredAmount);
                $arr_content['FuncCrncyRate'] = $funcCrncyRate;
                $arr_content['AccntCrncyRate'] = $accntCrncyRate;
                $arr_content['FuncCrncyNm'] = $funcCrncyNm;
                $arr_content['FuncCrncyAmount'] = $funcCrncyAmount;
                $arr_content['AccntCrncyNm'] = $accntCurrNm;
                $arr_content['AccntCrncyAmount'] = $accntCrncyAmount;
                $errMsg = "Success";
                $arr_content['message'] = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>" . $errMsg;
                echo json_encode($arr_content);
                exit();
            }
        }
    }
}
?>