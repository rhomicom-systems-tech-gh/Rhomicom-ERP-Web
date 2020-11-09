<?php
$canview = test_prmssns($dfltPrvldgs[36], $mdlNm);
$canVwRcHstry = test_prmssns("View Record History", $mdlNm);

$pageNo = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
$lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 10000000;
$sortBy = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : "Value";

if (isset($_POST['searchfor'])) {
    $srchFor = cleanInputData($_POST['searchfor']);
}
if (isset($_POST['searchin'])) {
    $srchIn = cleanInputData($_POST['searchin']);
}
if (strpos($srchFor, "%") === FALSE) {
    $srchFor = " " . $srchFor . " ";
    $srchFor = str_replace(" ", "%", $srchFor);
}
$canAdd = test_prmssns($dfltPrvldgs[42], $mdlNm);
$canEdt = test_prmssns($dfltPrvldgs[43], $mdlNm);
$canDel = test_prmssns($dfltPrvldgs[44], $mdlNm);

if (array_key_exists('lgn_num', get_defined_vars())) {
    if ($lgn_num > 0 && $canview === true) {
        if ($qstr == "DELETE") {
            if ($actyp == 1) {
                
            } else if ($actyp == 5) {
                
            }
        } else if ($qstr == "UPDATE") {
            if ($actyp == 1) {
                //Save Payment Methods
                //var_dump($_POST);
                //exit();
                header("content-type:application/json");
                $slctdAccbPayMthds = isset($_POST['slctdAccbPayMthds']) ? cleanInputData($_POST['slctdAccbPayMthds']) : '';
                $exitErrMsg="";
                $afftctd = 0;
                if (trim($slctdAccbPayMthds, "|~") != "") {
                    $variousRows = explode("|", trim($slctdAccbPayMthds, "|"));
                    for ($y = 0; $y < count($variousRows); $y++) {
                        $crntRow = explode("~", $variousRows[$y]);
                        if (count($crntRow) == 7) {
                            $ln_PayMthdID = (float) (cleanInputData1($crntRow[0]));
                            $ln_PayMthdNm = cleanInputData1($crntRow[1]);
                            $ln_PayMthdDesc = cleanInputData1($crntRow[2]);
                            $ln_AccountID = (int) cleanInputData1($crntRow[3]);
                            $ln_SprtDocTyp = cleanInputData1($crntRow[4]);
                            $ln_BckGrndPrcs = cleanInputData1($crntRow[5]);
                            $ln_IsEnabled = cleanInputData1($crntRow[6]) == "YES" ? true : false;
                            if ($ln_PayMthdNm != "" && $ln_AccountID > 0 && $ln_SprtDocTyp != "") {
                                if ($ln_PayMthdID <= 0) {
                                    $afftctd += createPymntMthd($orgID, $ln_PayMthdNm, $ln_PayMthdDesc, $ln_AccountID, $ln_SprtDocTyp,
                                            $ln_BckGrndPrcs, $ln_IsEnabled);
                                } else {
                                    $afftctd += updtPymntMthd($ln_PayMthdID, $ln_PayMthdNm, $ln_PayMthdDesc, $ln_AccountID, $ln_SprtDocTyp,
                                            $ln_BckGrndPrcs, $ln_IsEnabled);
                                }
                            }
                        }
                    }
                }
                if ($exitErrMsg != "") {
                    $exitErrMsg = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>"
                            . "" . $afftctd . " Payment Method(s) Saved Successfully!"
                            . "<br/><span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>" . $exitErrMsg . "</span>";
                } else {
                    $exitErrMsg = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>"
                            . "" . $afftctd . " Payment Method(s) Saved Successfully!";
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
                                    <span style=\"text-decoration:none;\">Payment Methods</span>
				</li>
                               </ul>
                              </div>";
                $mthdNm = array("Supplier Cheque", "Supplier Cash",
                    "Customer Cheque", "Customer Cash",
                    "Supplier Prepayment Application", "Customer Prepayment Application", "Petty Cash Payment");
                $docTypes = array("Supplier Payments", "Supplier Payments",
                    "Customer Payments", "Customer Payments",
                    "Supplier Payments", "Customer Payments", "Supplier Payments");
                $bckgrndPrcs = array("Supplier Cheque Payment", "Supplier Cash Payment",
                    "Customer Cheque Payment", "Customer Cash Payment",
                    "Supplier Prepayment Application", "Customer Prepayment Application", "Supplier Cash Payment");

                $oldMthdID = -1;
                for ($i = 0; $i < count($mthdNm); $i++) {
                    $oldMthdID = getGnrlRecID("accb.accb_paymnt_mthds", "pymnt_mthd_name", "paymnt_mthd_id", $mthdNm[$i], $orgID);
                    if ($oldMthdID <= 0) {
                        createPymntMthd($orgID, $mthdNm[$i], $mthdNm[$i], -1, $docTypes[$i], $bckgrndPrcs[$i], true);
                    }
                }
                $total = get_Total_PymntMthds($orgID, $srchFor, $srchIn);
                if ($pageNo > ceil($total / $lmtSze)) {
                    $pageNo = 1;
                } else if ($pageNo < 1) {
                    $pageNo = ceil($total / $lmtSze);
                }
                $curIdx = $pageNo - 1;
                $result = get_PymntMthds($curIdx, $lmtSze, $orgID, $srchFor, $srchIn);
                $cntr = 0;
                $colClassType2 = "col-lg-6";
                ?>
                <form id='accbPayMthdsForm' action='' method='post' accept-charset='UTF-8'>
                    <div class="row rhoRowMargin">
                        <?php
                        $nwRowHtml = "<tr id=\"accbPayMthdsRow__WWW123WWW\" role=\"row\">
                                            <td class=\"lovtd\">
                                                <div class=\"form-group form-group-sm\" style=\"width:100% !important;\">
                                                    <span>New</span>
                                                    <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"accbPayMthdsRow_WWW123WWW_PayMthdID\" value=\"-1\">
                                                </div>
                                            </td> 
                                            <td class=\"lovtd\">
                                                <div class=\"form-group form-group-sm\" style=\"width:100% !important;\">
                                                    <div class=\"input-group\"  style=\"width:100%;\">
                                                        <input type=\"text\" class=\"form-control rqrdFld\" aria-label=\"...\" id=\"accbPayMthdsRow_WWW123WWW_PayMthdNm\" value=\"\">
                                                        <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Payment Means', '', '', '', 'radio', true, '', 'accbPayMthdsRow_WWW123WWW_PayMthdNm', '', 'clear', 0, '');\">
                                                            <span class=\"glyphicon glyphicon-th-list\"></span>
                                                        </label>
                                                    </div>
                                                </div>                                                        
                                            </td>
                                            <td class=\"lovtd\">
                                                <div class=\"form-group form-group-sm\" style=\"width:100% !important;\">
                                                    <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"accbPayMthdsRow_WWW123WWW_PayMthdDesc\" value=\"\">
                                                </div>                                                        
                                            </td>
                                            <td class=\"lovtd\">
                                                <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"accbPayMthdsRow_WWW123WWW_AccountID\" value=\"-1\" style=\"width:100% !important;\"> 
                                                <div class=\"input-group\" style=\"width:100% !important;\">
                                                    <input type=\"text\" class=\"form-control rqrdFld\" aria-label=\"...\" id=\"accbPayMthdsRow_WWW123WWW_AccountNm\" name=\"accbPayMthdsRow_WWW123WWW_AccountNm\" value=\"\" readonly=\"true\" style=\"width:100% !important;\">
                                                    <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Transaction Accounts', 'allOtherInputOrgID', '', '', 'radio', true, '', 'accbPayMthdsRow_WWW123WWW_AccountID', 'accbPayMthdsRow_WWW123WWW_AccountNm', 'clear', 1, '', function () {
                                                                                        changeElmntTitleFunc('accbPayMthdsRow_WWW123WWW_AccountNm');
                                                                                    });\">
                                                        <span class=\"glyphicon glyphicon-th-list\"></span>
                                                    </label>
                                                </div>                                             
                                            </td>    
                                            <td class=\"lovtd\">
                                                <select data-placeholder=\"Select...\" class=\"form-control chosen-select rqrdFld\" id=\"accbPayMthdsRow_WWW123WWW_SprtDocTyp\" style=\"width:100% !important;\">";
                        $valslctdArry = array("", "");
                        $srchInsArrys = array("Supplier Payments", "Customer Payments");
                        for ($z = 0; $z < count($srchInsArrys); $z++) {
                            $nwRowHtml .= "<option value=\"" . $srchInsArrys[$z] . "\" " . $valslctdArry[$z] . ">" . $srchInsArrys[$z] . "</option>";
                        }
                        $nwRowHtml .= "</select>
                                            </td>
                                            <td class=\"lovtd\">
                                                <div class=\"form-group form-group-sm\" style=\"width:100% !important;\">
                                                    <div class=\"input-group\"  style=\"width:100%;\">
                                                        <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"accbPayMthdsRow_WWW123WWW_BckGrndPrcs\" value=\"\" readonly=\"true\">
                                                        <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Reports and Processes', '', '', '', 'radio', true, '', '', 'accbPayMthdsRow_WWW123WWW_BckGrndPrcs', 'clear', 0, '');\">
                                                            <span class=\"glyphicon glyphicon-th-list\"></span>
                                                        </label>
                                                    </div>
                                                </div>                                                       
                                            </td>
                                            <td class=\"lovtd\" style=\"text-align: center;\">";
                        $isChkd = "checked=\"true\"";
                        $nwRowHtml .= "<div class=\"form-group form-group-sm\">
                                                        <div class=\"form-check\" style=\"font-size: 12px !important;\">
                                                            <label class=\"form-check-label\">
                                                                <input type=\"checkbox\" class=\"form-check-input\" id=\"accbPayMthdsRow_WWW123WWW_IsEnbld\" name=\"accbPayMthdsRow_WWW123WWW_IsEnbld\" " . $isChkd . ">
                                                            </label>
                                                        </div>
                                                    </div>                                                        
                                            </td>";
                        if ($canVwRcHstry === true) {
                            $nwRowHtml .= "<td class=\"lovtd\">&nbsp;</td>";
                        }
                        $nwRowHtml .= "</tr>";
                        $nwRowHtml = urlencode($nwRowHtml);
                        ?> 
                        <div class="<?php echo $colClassType2; ?>" style="padding:0px 1px 0px 1px !important;"> 
                            <div class="col-md-12">
                                <?php if ($canAdd) { ?>
                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="insertNewRowBe4('accbPayMthdsTable', 0, '<?php echo $nwRowHtml; ?>');">
                                        <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                        New Payment Method
                                    </button>
                                <?php } ?>
                                <?php if ($canAdd || $canEdt) { ?>
                                    <button type="button" class="btn btn-default btn-sm" style="margin-bottom: 5px;" onclick="saveAccbPayMthds('#allmodules', 'grp=6&typ=1&pg=18&vtyp=0');">
                                        <img src="cmn_images/FloppyDisk.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                        SAVE
                                    </button>
                                <?php } ?>
                                <button type="button" class="btn btn-default btn-sm" style="margin-bottom: 5px;" onclick="openATab('#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=0');">
                                    <img src="cmn_images/refresh.bmp" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                    REFRESH
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="row"  style="padding:1px 15px 1px 15px !important;"><hr style="margin:1px 0px 3px 0px;"></div>                    
                    <div class="row"> 
                        <div  class="col-md-12">
                            <table class="table table-striped table-bordered table-responsive" id="accbPayMthdsTable" cellspacing="0" width="100%" style="width:100%;min-width: 500px !important;">
                                <thead>
                                    <tr>
                                        <th style="max-width: 35px !important;width: 30px !important;">No.</th> 
                                        <th>Method Name</th>
                                        <th>Method Description</th>
                                        <th>Charge Account Name</th>
                                        <th>Supported Document Type</th>
                                        <th>Background Process Name</th>
                                        <th style="max-width: 75px !important;width: 75px !important;text-align: center;">Enabled?</th>
                                        <?php if ($canVwRcHstry === true) { ?>
                                            <th>...</th>
                                        <?php } ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    while ($row = loc_db_fetch_array($result)) {
                                        $cntr += 1;
                                        $isEnabled = $row[6];
                                        $payMthdID = $row[0];
                                        $payMthdNm = $row[1];
                                        $payMthdDesc = $row[2];
                                        $chrgAcntID = $row[3];
                                        $chrgAcntNm = $row[7];
                                        $sprtdDocType = $row[4];
                                        $bckgrdPrcsNm = $row[5];
                                        ?>
                                        <tr id="accbPayMthdsRow_<?php echo $cntr; ?>">
                                            <td class="lovtd">
                                                <div class="form-group form-group-sm" style="width:100% !important;">
                                                    <span><?php echo ($curIdx * $lmtSze) + ($cntr); ?></span>
                                                    <input type="hidden" class="form-control" aria-label="..." id="accbPayMthdsRow<?php echo $cntr; ?>_PayMthdID" value="<?php echo $payMthdID; ?>">
                                                </div>
                                            </td> 
                                            <td class="lovtd">
                                                <?php if ($canEdt === true) { ?>
                                                    <div class="form-group form-group-sm" style="width:100% !important;">
                                                        <div class="input-group"  style="width:100%;">
                                                            <input type="text" class="form-control rqrdFld" aria-label="..." id="accbPayMthdsRow<?php echo $cntr; ?>_PayMthdNm" value="<?php echo $payMthdNm; ?>">
                                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Payment Means', '', '', '', 'radio', true, '<?php echo $payMthdNm; ?>', 'accbPayMthdsRow<?php echo $cntr; ?>_PayMthdNm', '', 'clear', 0, '');">
                                                                <span class="glyphicon glyphicon-th-list"></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                <?php } else { ?>
                                                    <input type="text" class="form-control" aria-label="..." value="<?php echo $payMthdNm; ?>" readonly="true" style="width:100%;">
                                                <?php } ?>                                                         
                                            </td>
                                            <td class="lovtd">
                                                <?php if ($canEdt === true) { ?>
                                                    <div class="form-group form-group-sm" style="width:100% !important;">
                                                        <input type="text" class="form-control" aria-label="..." id="accbPayMthdsRow<?php echo $cntr; ?>_PayMthdDesc" value="<?php echo $payMthdDesc; ?>">
                                                    </div>
                                                <?php } else { ?>
                                                    <input type="text" class="form-control" aria-label="..." value="<?php echo $payMthdDesc; ?>" readonly="true" style="width:100%;">
                                                <?php } ?>                                                         
                                            </td>
                                            <td class="lovtd">
                                                <input type="hidden" class="form-control" aria-label="..." id="accbPayMthdsRow<?php echo $cntr; ?>_AccountID" value="<?php echo $chrgAcntID; ?>" style="width:100% !important;"> 
                                                <?php if ($canEdt === true) { ?>
                                                    <div class="input-group" style="width:100% !important;">
                                                        <input type="text" class="form-control rqrdFld" aria-label="..." id="accbPayMthdsRow<?php echo $cntr; ?>_AccountNm" name="accbPayMthdsRow<?php echo $cntr; ?>_AccountNm" value="<?php echo $chrgAcntNm; ?>" readonly="true" style="width:100% !important;">
                                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Transaction Accounts', 'allOtherInputOrgID', '', '', 'radio', true, '', 'accbPayMthdsRow<?php echo $cntr; ?>_AccountID', 'accbPayMthdsRow<?php echo $cntr; ?>_AccountNm', 'clear', 1, '', function () {
                                                                                            changeElmntTitleFunc('accbPayMthdsRow<?php echo $cntr; ?>_AccountNm');
                                                                                        });">
                                                            <span class="glyphicon glyphicon-th-list"></span>
                                                        </label>
                                                    </div>    
                                                <?php } else { ?>
                                                    <span><?php echo $chrgAcntNm; ?></span>
                                                <?php } ?>                                             
                                            </td>    
                                            <td class="lovtd">
                                                <select data-placeholder="Select..." class="form-control chosen-select rqrdFld" id="accbPayMthdsRow<?php echo $cntr; ?>_SprtDocTyp" style="width:100% !important;">
                                                    <?php
                                                    $valslctdArry = array("", "");
                                                    $srchInsArrys = array("Supplier Payments", "Customer Payments");
                                                    for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                        if ($sprtdDocType == $srchInsArrys[$z]) {
                                                            $valslctdArry[$z] = "selected";
                                                        }
                                                        ?>
                                                        <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </td>
                                            <td class="lovtd">
                                                <?php if ($canEdt === true) { ?>
                                                    <div class="form-group form-group-sm" style="width:100% !important;">
                                                        <div class="input-group"  style="width:100%;">
                                                            <input type="text" class="form-control" aria-label="..." id="accbPayMthdsRow<?php echo $cntr; ?>_BckGrndPrcs" value="<?php echo $bckgrdPrcsNm; ?>" readonly="true">
                                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Reports and Processes', '', '', '', 'radio', true, '<?php echo $bckgrdPrcsNm; ?>', '', 'accbPayMthdsRow<?php echo $cntr; ?>_BckGrndPrcs', 'clear', 0, '');">
                                                                <span class="glyphicon glyphicon-th-list"></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                <?php } else { ?>
                                                    <input type="text" class="form-control" aria-label="..." value="<?php echo $bckgrdPrcsNm; ?>" readonly="true" style="width:100%;">
                                                <?php } ?>                                                         
                                            </td>
                                            <td class="lovtd" style="text-align: center;">
                                                <?php
                                                $isChkd = "";
                                                if ($isEnabled == "1") {
                                                    $isChkd = "checked=\"true\"";
                                                }
                                                if ($canEdt === true) {
                                                    ?>
                                                    <div class="form-group form-group-sm">
                                                        <div class="form-check" style="font-size: 12px !important;">
                                                            <label class="form-check-label">
                                                                <input type="checkbox" class="form-check-input" id="accbPayMthdsRow<?php echo $cntr; ?>_IsEnbld" name="accbPayMthdsRow<?php echo $cntr; ?>_IsEnbld" <?php echo $isChkd ?>>
                                                            </label>
                                                        </div>
                                                    </div>
                                                <?php } else { ?>
                                                    <span class=""><?php echo ($isEnabled == "1" ? "Yes" : "No"); ?></span>
                                                <?php } ?>                                                         
                                            </td>
                                            <?php if ($canVwRcHstry === true) { ?>
                                                <td class="lovtd">
                                                    <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" onclick="getRecHstry('<?php
                                                    echo urlencode(encrypt1(($payMthdID . "|accb.accb_paymnt_mthds|paymnt_mthd_id"), $smplTokenWord1));
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
            }
        }
    }
}
?>