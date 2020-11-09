<?php
$canAdd = test_prmssns($dfltPrvldgs[17], $mdlNm);
$canEdt = test_prmssns($dfltPrvldgs[18], $mdlNm);
$canDel = test_prmssns($dfltPrvldgs[19], $mdlNm);

$pageNo = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
$lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 30;
$sortBy = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : "Value";

$qStrtDte = "01-Jan-1900 00:00:00";
$qEndDte = "31-Dec-4000 23:59:59";
$qNwStrtDte = date('d-M-Y H:i:s');
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
                /* Delete Rate */
                $rateID = isset($_POST['rateID']) ? cleanInputData($_POST['rateID']) : -1;
                $rateIDDesc = isset($_POST['rateIDDesc']) ? cleanInputData($_POST['rateIDDesc']) : "";
                if ($canDel) {
                    $affctd1 = deleteRate($rateID, $rateIDDesc);
                    if ($affctd1 > 0) {
                        $dsply = "Successfully Deleted the ff Records-";
                        $dsply .= "<br/>$affctd1 Exchange Rate(s)!";
                        echo "<p style = \"text-align:left; color:#32CD32;font-weight:bold;font-style:italic;\">$dsply</p>";
                    } else {
                        $dsply = "No Record Deleted!";
                        echo "<p style = \"text-align:left; color:red;font-weight:bold;font-style:italic;\">$dsply</p>";
                    }
                } else {
                    restricted();
                }
            } else if ($actyp == 2) {
                /* Delete Rate */
                $slctdRateIDs = isset($_POST['slctdRateIDs']) ? cleanInputData($_POST['slctdRateIDs']) : '';
                $variousRows = explode("|", trim($slctdRateIDs, "|"));
                $affctd1 = 0;
                if ($canDel) {
                    for ($z = 0; $z < count($variousRows); $z++) {
                        $crntRow = explode("~", $variousRows[$z]);
                        if (count($crntRow) == 2) {
                            $rateID = (float) cleanInputData1($crntRow[0]);
                            $rateIDDesc = cleanInputData1($crntRow[1]);
                            $affctd1 += deleteRate($rateID, $rateIDDesc);
                        }
                    }
                    if ($affctd1 > 0) {
                        $dsply = "Successfully Deleted the ff Records-";
                        $dsply .= "<br/>$affctd1 Exchange Rate(s)!";
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
            if ($actyp == 1) {
                header("content-type:application/json");
                $slctdRateIDs = isset($_POST['slctdRateIDs']) ? cleanInputData($_POST['slctdRateIDs']) : '';
                $errMsg = "";
                if ($slctdRateIDs != "") {
                    //Save Exchange Rates
                    $affctd = 0;
                    $variousRows = explode("|", trim($slctdRateIDs, "|"));
                    for ($z = 0; $z < count($variousRows); $z++) {
                        $crntRow = explode("~", $variousRows[$z]);
                        if (count($crntRow) == 2) {
                            $rateID = (float) cleanInputData1($crntRow[0]);
                            $rateValue = (float) cleanInputData1($crntRow[1]);
                            //$rateValue1 = (float) cleanInputData1($crntRow[1]);
                            if ($rateID > 0) {
                                $affctd += updtRateValue($rateID, $rateValue);
                            }
                        }
                    }
                    $arr_content['percent'] = 100;
                    $arr_content['message'] = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>" . $affctd . " Exchange Rate Value(s) Saved!<br/>" . $errMsg;
                    echo json_encode($arr_content);
                    exit();
                } else {
                    $arr_content['percent'] = 100;
                    $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>Data Supplied is Incomplete or Invalid at some fields!</span>";
                    echo json_encode($arr_content);
                    exit();
                }
            } else if ($actyp == 2) {
                header("content-type:application/json");
                $newRateDate = isset($_POST['newRateDate']) ? cleanInputData($_POST['newRateDate']) : '';
                $errMsg = "";
                if ($newRateDate != "") {
                    //Create Blank/Default Exchange Rates
                    $affctd = 0;
                    $funCurID = getOrgFuncCurID($orgID);
                    $funcCurCode = getPssblValNm($funCurID);
                    $result = get_ExchgCurrencies($funcCurCode);
                    while ($row = loc_db_fetch_array($result)) {
                        if (doesRateExst($newRateDate, $row[1], $funcCurCode) == false) {
                            $affctd += createRate($newRateDate, $row[1], $row[0], $funcCurCode, $funCurID, 1.0000);
                        }
                    }
                    $arr_content['percent'] = 100;
                    $arr_content['message'] = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>" . $affctd . " Exchange Rate Line(s) Created!<br/>" . $errMsg;
                    echo json_encode($arr_content);
                    exit();
                } else {
                    $arr_content['percent'] = 100;
                    $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>Data Supplied is Incomplete or Invalid at some fields!</span>";
                    echo json_encode($arr_content);
                    exit();
                }
            } else if ($actyp == 3) {
                header("content-type:application/json");
                $newRateDate = isset($_POST['newRateDate']) ? cleanInputData($_POST['newRateDate']) : '';
                $errMsg = "";
                if ($newRateDate != "" && checkForInternetConnection() === true) {
                    //Download Exchange Rates from https://docs.openexchangerates.org/
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_URL, "https://openexchangerates.org/api/historical/" . cnvrtDMYToYMD($newRateDate) . ".json?app_id=5dba57b2d47b4a11b4e5a020522de567");
                    $result1 = curl_exec($ch);
                    curl_close($ch);

                    $currencyRates = json_decode($result1);
                    $affctd = 0;
                    $funCurID = getOrgFuncCurID($orgID);
                    $funcCurCode = getPssblValNm($funCurID);
                    $result = get_ExchgCurrencies($funcCurCode);

                    $fromCurID = -1;
                    $toCurID = $funCurID;
                    $rateVals = json_decode(json_encode($currencyRates->rates), True);
                    $rateVal = (float) $rateVals[$funcCurCode];
                    $baseToFuncCurRate = $rateVal;
                    $dateStr = $newRateDate;
                    while ($row = loc_db_fetch_array($result)) {
                        $fromCurID = getPssblValID($row[1], getLovID("Currencies"));
                        $rateID = doesRateExst1($dateStr, $row[1], $funcCurCode);
                        $rateVal = (float) $rateVals[$row[1]];
                        if ($rateVal > 0) {
                            $rateVal = ($baseToFuncCurRate / $rateVal);
                            if ($rateID <= 0) {
                                $affctd += createRate($newRateDate, $row[1], $fromCurID, $funcCurCode, $toCurID, $rateVal);
                            } else {
                                $affctd += updtRateValue($rateID, $rateVal);
                            }
                        }
                    }
                    $arr_content['percent'] = 100;
                    $arr_content['message'] = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>" . $affctd . " Exchange Rate Line(s) Created!<br/>" . $errMsg;
                    echo json_encode($arr_content);
                    exit();
                } else {
                    $arr_content['percent'] = 100;
                    $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>Ensure that a Valid New Rates Date has been provided and that the Internet Connection is Working!</span>";
                    echo json_encode($arr_content);
                    exit();
                }
            }
        } else {
            if ($vwtyp == 0) {
                echo $cntent . "<li onclick=\"openATab('#allmodules', 'grp=$group&typ=$type&pg=$pgNo&vtyp=0');\">
                                    <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                    <span style=\"text-decoration:none;\">Exchange Rates</span>
				</li>
                               </ul>
                              </div>";
                //echo "Exchange Rates";
                $total = get_Total_Rates($srchFor, $srchIn, $qStrtDte, $qEndDte);
                if ($pageNo > ceil($total / $lmtSze)) {
                    $pageNo = 1;
                } else if ($pageNo < 1) {
                    $pageNo = ceil($total / $lmtSze);
                }
                $curIdx = $pageNo - 1;
                $result = get_Rates($srchFor, $srchIn, $qStrtDte, $qEndDte, $curIdx, $lmtSze);
                $cntr = 0;
                $colClassType1 = "col-lg-4";
                $colClassType2 = "col-lg-3";
                $colClassType3 = "col-lg-2";
                ?>
                <form id='acbExchRatesForm' action='' method='post' accept-charset='UTF-8'>                        
                    <div class="row " style="margin-bottom:0px;padding:0px 15px 0px 15px !important;">
                        <div class="col-lg-3" style="padding:0px 1px 0px 1px !important;">                                
                            <div class="input-group">
                                <input class="form-control" id="acbExchRatesSrchFor" type = "text" placeholder="Search For" value="<?php
                                echo trim(str_replace("%", " ", $srchFor));
                                ?>" onkeyup="enterKeyFuncAcbExchRates(event, '', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>')">
                                <input id="acbExchRatesPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                <label class="btn btn-primary btn-file input-group-addon" onclick="getAcbExchRates('clear', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>')">
                                    <span class="glyphicon glyphicon-remove"></span>
                                </label>
                                <label class="btn btn-primary btn-file input-group-addon" onclick="getAcbExchRates('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>')">
                                    <span class="glyphicon glyphicon-search"></span>
                                </label> 
                            </div>
                        </div>
                        <div class="col-lg-3" style="padding:0px 1px 0px 1px !important;">
                            <div class="input-group">
                                <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                <select data-placeholder="Select..." class="form-control chosen-select" id="acbExchRatesSrchIn">
                                    <?php
                                    $valslctdArry = array("", "", "");
                                    $srchInsArrys = array("Currency From", "Currency To", "Multiply By");

                                    for ($z = 0; $z < count($srchInsArrys); $z++) {
                                        if ($srchIn == $srchInsArrys[$z]) {
                                            $valslctdArry[$z] = "selected";
                                        }
                                        ?>
                                        <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                    <?php } ?>
                                </select>
                                <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                <select data-placeholder="Select..." class="form-control chosen-select" id="acbExchRatesDsplySze" style="min-width:70px !important;">                            
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
                        <div class="col-lg-4" style="padding:0px 1px 0px 1px !important;">
                            <div class="col-xs-6" style="padding:0px 1px 0px 0px !important;">
                                <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                    <input class="form-control" size="16" type="text" id="acbExchRatesStrtDate" name="acbExchRatesStrtDate" value="<?php
                                    echo substr($qStrtDte, 0, 11);
                                    ?>" placeholder="Start Date">
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                </div>
                            </div>
                            <div class="col-xs-6" style="padding:0px 1px 0px 0px !important;">
                                <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                    <input class="form-control" size="16" type="text"  id="acbExchRatesEndDate" name="acbExchRatesEndDate" value="<?php
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
                                        <a class="rhopagination" href="javascript:getAcbExchRates('previous', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');" aria-label="Previous">
                                            <span aria-hidden="true">&laquo;</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="rhopagination" href="javascript:getAcbExchRates('next', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');" aria-label="Next">
                                            <span aria-hidden="true">&raquo;</span>
                                        </a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                    <div class="row " style="margin-bottom:2px;padding:2px 15px 2px 15px !important;">
                        <div class="col-md-12" style="padding:0px 0px 0px 0px !important;border-top:1px solid #ddd;border-bottom:1px solid #ddd;">
                            <div class="col-md-4" style="padding:2px 1px 2px 1px !important;">
                                <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">                                           
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getAcbExchRates('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');">
                                        <span style="font-weight:bold;">New Rates Date:</span>
                                    </label>
                                    <input class="form-control" size="16" type="text" id="acbExchRatesNewDate" name="acbExchRatesNewDate" value="<?php
                                    echo substr($qNwStrtDte, 0, 11);
                                    ?>" placeholder="New Rates Date">
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                </div>
                            </div>   
                            <div class="col-md-8" style="padding:2px 1px 2px 1px !important;">
                                <?php if ($canAdd) { ?>                                                            
                                    <button type="button" class="btn btn-default" style="margin-bottom: 0px;" onclick="saveAcbExchRates('', '#allmodules', 'grp=6&typ=1&pg=16&vtyp=0', 2);">
                                        <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                        Create Rates
                                    </button>                    
                                    <button type="button" class="btn btn-default" style="margin-bottom: 0px;" onclick="saveAcbExchRates('', '#allmodules', 'grp=6&typ=1&pg=16&vtyp=0', 3);">
                                        <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                        Auto-Download Rates
                                    </button>                    
                                    <button type="button" class="btn btn-default" style="margin-bottom: 0px;" onclick="saveAcbExchRates('', '#allmodules', 'grp=6&typ=1&pg=16&vtyp=0', 1);">
                                        <img src="cmn_images/FloppyDisk.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                        Save Rates
                                    </button>
                                <?php } ?>                           
                                <?php if ($canDel) { ?>
                                    <button type="button" class="btn btn-default btn-sm" style="margin-bottom: 0px;" onclick="delSlctdAcbExchRates();">
                                        <img src="cmn_images/90.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                        Delete
                                    </button>
                                <?php } ?> 
                            </div>
                        </div>
                    </div>
                    <div class="row"> 
                        <div  class="col-md-12">
                            <table class="table table-striped table-bordered table-responsive" id="acbExchRatesTable" cellspacing="0" width="100%" style="width:100%;">
                                <thead>
                                    <tr>
                                        <?php if ($canDel === TRUE) { ?>
                                            <th>&nbsp;</th>
                                        <?php } ?>
                                        <th>No.</th>
                                        <th>Rate Date</th>
                                        <th style="max-width:75px;width:75px;">Source Currency</th>
                                        <th>Currency Code Meaning</th>
                                        <th style="max-width:85px;width:75px;">Destination Currency</th>
                                        <th>Currency Code Meaning</th>
                                        <th style="text-align:right;">Multiply Source Currency by</th>
                                        <?php if ($canDel === TRUE) { ?>
                                            <th>&nbsp;</th>
                                        <?php } ?>
                                        <?php if ($canVwRcHstry === true) { ?>
                                            <th>...</th>
                                        <?php } ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    while ($row = loc_db_fetch_array($result)) {
                                        $cntr += 1;
                                        ?>
                                        <tr id="acbExchRatesRow_<?php echo $cntr; ?>">
                                            <?php if ($canDel === TRUE) { ?>
                                                <td class="lovtd">
                                                    <input type="checkbox" name="acbExchRatesRow<?php echo $cntr; ?>_CheckBox" value="<?php echo $row[0] . ";" . $row[1]; ?>">
                                                </td>
                                            <?php } ?>
                                            <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                            <td class="lovtd">
                                                <?php
                                                if ($canEdt === TRUE) {
                                                    ?>
                                                    <input class="form-control" size="16" type="text" id="acbExchRatesRow<?php echo $cntr; ?>_RateDate" name="acbExchRatesRow<?php echo $cntr; ?>_RateDate" value="<?php echo $row[1]; ?>" readonly="true" style="width:100% !important;">
                                                    <?php
                                                } else {
                                                    echo $row[1];
                                                }
                                                ?>
                                                <input type="hidden" class="form-control" aria-label="..." id="acbExchRatesRow<?php echo $cntr; ?>_RateID" value="<?php echo $row[0]; ?>">
                                                <input type="hidden" class="form-control" aria-label="..." id="acbExchRatesRow<?php echo $cntr; ?>_FromID" value="<?php echo $row[3]; ?>">
                                                <input type="hidden" class="form-control" aria-label="..." id="acbExchRatesRow<?php echo $cntr; ?>_ToID" value="<?php echo $row[6]; ?>">
                                            </td>
                                            <td class="lovtd">
                                                <?php
                                                if ($canEdt === TRUE) {
                                                    ?>
                                                    <input class="form-control rqrdFld" size="16" type="text" id="acbExchRatesRow<?php echo $cntr; ?>_FromCur" name="acbExchRatesRow<?php echo $cntr; ?>_FromCur" value="<?php echo $row[2]; ?>" readonly="true" style="width:100% !important;">
                                                    <?php
                                                } else {
                                                    echo $row[2];
                                                }
                                                ?>
                                            </td>
                                            <td class="lovtd">
                                                <?php
                                                if ($canEdt === TRUE) {
                                                    ?>
                                                    <input class="form-control" size="16" type="text" id="acbExchRatesRow<?php echo $cntr; ?>_FromCurMng" name="acbExchRatesRow<?php echo $cntr; ?>_FromCurMng" value="<?php echo $row[4]; ?>" readonly="true" style="width:100% !important;">
                                                    <?php
                                                } else {
                                                    echo $row[4];
                                                }
                                                ?>
                                            </td>
                                            <td class="lovtd">
                                                <?php
                                                if ($canEdt === TRUE) {
                                                    ?>
                                                    <input class="form-control rqrdFld" size="16" type="text" id="acbExchRatesRow<?php echo $cntr; ?>_ToCur" name="acbExchRatesRow<?php echo $cntr; ?>_ToCur" value="<?php echo $row[5]; ?>" readonly="true" style="width:100% !important;">
                                                    <?php
                                                } else {
                                                    echo $row[5];
                                                }
                                                ?>
                                            </td>
                                            <td class="lovtd">
                                                <?php
                                                if ($canEdt === TRUE) {
                                                    ?>
                                                    <input class="form-control" size="16" type="text" id="acbExchRatesRow<?php echo $cntr; ?>_ToCurMng" name="acbExchRatesRow<?php echo $cntr; ?>_ToCurMng" value="<?php echo $row[7]; ?>" readonly="true" style="width:100% !important;">
                                                    <?php
                                                } else {
                                                    echo $row[7];
                                                }
                                                ?>
                                            </td>
                                            <td class="lovtd" style="text-align:right;font-weight: bold;color:blue;">
                                                <?php
                                                if ($canEdt === TRUE) {
                                                    ?>
                                                    <input class="form-control rqrdFld mcfExRate" size="16" type="number" id="acbExchRatesRow<?php echo $cntr; ?>_ExRate" name="acbExchRatesRow<?php echo $cntr; ?>_ExRate" value="<?php
                                                    echo number_format((float) $row[8], 15);
                                                    ?>" style="width:100% !important;text-align:right;font-weight: bold;color:blue;" onkeypress="acbCnfExRateKeyPress(event, 'acbExchRatesRow_<?php echo $cntr; ?>');">
                                                           <?php
                                                       } else {
                                                           echo number_format((float) $row[8], 15);
                                                       }
                                                       ?>
                                            </td>
                                            <?php if ($canDel === true) { ?>
                                                <td class="lovtd">
                                                    <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delAcbExchRate('acbExchRatesRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Rate">
                                                        <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                    </button>
                                                </td>
                                            <?php } ?>
                                            <?php if ($canVwRcHstry === true) { ?>
                                                <td class="lovtd">
                                                    <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" onclick="getRecHstry('<?php echo urlencode(encrypt1(($row[0] . "|accb.accb_exchange_rates|rate_id"), $smplTokenWord1)); ?>');" style="padding:2px !important;">
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
                //Get Latest Exchange Rate
                header("content-type:application/json");
                $lineAmountCrncy = isset($_POST['lineAmountCrncy']) ? cleanInputData($_POST['lineAmountCrncy']) : $fnccurnm;
                $lineAmountTransDte = isset($_POST['lineAmountTransDte']) ? cleanInputData($_POST['lineAmountTransDte']) : $gnrlTrnsDteDMYHMS;
                $lineAmountAcntID1 = isset($_POST['lineAmountAcntID1']) ? cleanInputData($_POST['lineAmountAcntID1']) : -1;
                $funCurID = getOrgFuncCurID($orgID);
                $lovID = getLovID("Currencies");
                $lineAmountCrncyID = getPssblValID($lineAmountCrncy, $lovID);
                $exchangeRate1 = round(get_LtstExchRate($lineAmountCrncyID, $funCurID, $lineAmountTransDte), 4);

                $arr_content['FuncExchgRate'] = $exchangeRate1;

                $errMsg = "Success";
                $arr_content['message'] = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>" . $errMsg;
                echo json_encode($arr_content);
                exit();
            }
        }
    }
}
?>