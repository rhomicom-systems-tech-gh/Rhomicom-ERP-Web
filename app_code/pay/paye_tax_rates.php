<?php
$canAdd = test_prmssns($dfltPrvldgs[35], $mdlNm);
$canEdt = test_prmssns($dfltPrvldgs[36], $mdlNm);
$canDel = test_prmssns($dfltPrvldgs[37], $mdlNm);

$pageNo = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
$lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 10;
$sortBy = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : "Value";
if (array_key_exists('lgn_num', get_defined_vars())) {
    if ($lgn_num > 0 && $canview === true) {
        if ($qstr == "DELETE") {
            if ($actyp == 1) {
                /* Delete PAYE Tax Rate */
                $pKeyID = isset($_POST['pKeyID']) ? cleanInputData($_POST['pKeyID']) : -1;
                $pKeyNm = isset($_POST['pKeyNm']) ? cleanInputData($_POST['pKeyNm']) : "";
                if ($canDel) {
                    echo deletePayeRates($pKeyID, $pKeyNm);
                } else {
                    restricted();
                }
            } else if ($actyp == 5) {
                
            }
        } else if ($qstr == "UPDATE") {
            if ($actyp == 1) {
                //Save PAYE Tax Rates
                //var_dump($_POST);
                //exit();
                header("content-type:application/json");
                $slctdRateIDs = isset($_POST['slctdRateIDs']) ? cleanInputData($_POST['slctdRateIDs']) : '';
                $exitErrMsg = "";
                $afftctd = 0;
                $afftctd1 = 0;
                $afftctd2 = 0;
                if (trim($slctdRateIDs, "|~") != "") {
                    //Save Petty Cash Double Entry Lines
                    $variousRows = explode("|", trim($slctdRateIDs, "|"));
                    //echo count($variousRows);
                    for ($y = 0; $y < count($variousRows); $y++) {
                        //var_dump($crntRow);
                        $crntRow = explode("~", $variousRows[$y]);
                        if (count($crntRow) == 4) {
                            $ln_RatesID = (float) (cleanInputData1($crntRow[0]));
                            $ln_Level = (float) cleanInputData1($crntRow[1]);
                            $ln_Taxable = (float) cleanInputData1($crntRow[2]);
                            $ln_Rate = (float) cleanInputData1($crntRow[3]);
                            $errMsg = "";
                            if ($ln_RatesID <= 0) {
                                $afftctd += createPayeRates($ln_Level, $ln_Taxable, $ln_Rate);
                            } else {
                                $afftctd += updatePayeRates($ln_RatesID, $ln_Level, $ln_Taxable, $ln_Rate);
                            }
                        }
                    }
                }
                if ($exitErrMsg != "") {
                    $exitErrMsg = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>"
                            . "" . $afftctd . " PAYE Tax Rate(s) Saved Successfully!"
                            . "<br/><span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>" . $exitErrMsg . "</span>";
                } else {
                    $exitErrMsg = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>"
                            . "" . $afftctd . " PAYE Tax Rate(s) Saved Successfully!";
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
                                    <span style=\"text-decoration:none;\">Paye Tax Rates</span>
				</li>
                               </ul>
                              </div>";
                $error = "";
                $searchAll = true;
                $srchFor = isset($_POST['searchfor']) ? cleanInputData($_POST['searchfor']) : '';
                $srchIn = isset($_POST['searchin']) ? cleanInputData($_POST['searchin']) : 'Both';
                $pageNo = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
                $lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 100;
                $sortBy = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : "Trns. ID DESC";

                if (strpos($srchFor, "%") === FALSE) {
                    $srchFor = "%" . str_replace(" ", "%", $srchFor) . "%";
                    $srchFor = str_replace("%%", "%", $srchFor);
                }
                $result = get_PayeRates();
                $cntr = 0;
                $colClassType1 = "col-lg-5";
                $nwRowHtml2 = "<tr id=\"allPayeRatesRow__WWW123WWW\" onclick=\"$('#allOtherInputData99').val($('#allPayeRatesTable tr').index(this));\">"
                        . "<td class=\"lovtd\"><span class=\"normaltd\">New</span></td>"
                        . "<td class=\"lovtd\">
                                <input type=\"hidden\" id=\"allPayeRatesRow_WWW123WWW_RatesID\" value=\"-1\">
                                <input class=\"form-control rqrdFld jbDetDbt\" id=\"allPayeRatesRow_WWW123WWW_Level\" style=\"width:100% !important;font-size: 13px !important;font-weight: bold !important;\" type = \"text\" value=\"0\" onkeypress=\"gnrlFldKeyPress(event, 'allPayeRatesRow_WWW123WWW_Level', 'allPayeRatesTable', 'jbDetDbt');\"/>
                            </td>
                            <td class=\"lovtd\">
                                <input class=\"form-control rqrdFld jbDetCrdt\" id=\"allPayeRatesRow_WWW123WWW_Taxable\" style=\"width:100% !important;font-size: 13px !important;font-weight: bold !important;\" value=\"0.00\" onkeypress=\"gnrlFldKeyPress(event, 'allPayeRatesRow_WWW123WWW_Taxable', 'allPayeRatesTable', 'jbDetCrdt');\"/>
                            </td>
                            <td class=\"lovtd\">
                                <input class=\"form-control rqrdFld jbDetFuncRate\" id=\"allPayeRatesRow_WWW123WWW_Rate\" style=\"width:100% !important;font-size: 13px !important;font-weight: bold !important;\" value=\"0.00\" onkeypress=\"gnrlFldKeyPress(event, 'allPayeRatesRow_WWW123WWW_Rate', 'allPayeRatesTable', 'jbDetFuncRate');\"/>
                            </td>";
                if ($canDel === true && $canEdt === true) {
                    $nwRowHtml2 .= "<td class=\"lovtd\">
                                                                            <button type=\"button\" class=\"btn btn-default\" style=\"margin: 0px !important;padding:0px 3px 2px 4px !important;\" onclick=\"delPayTaxRateLn('allPayeRatesRow__WWW123WWW');\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Delete Journal Line\">
                                                                                <img src=\"cmn_images/no.png\" style=\"height:15px; width:auto; position: relative; vertical-align: middle;\">
                                                                            </button>
                                                                        </td>";
                }
                if ($canVwRcHstry === true) {
                    $nwRowHtml2 .= "<td class=\"lovtd\">&nbsp;</td>";
                }
                $nwRowHtml2 .= "</tr>";
                $nwRowHtml2 = urlencode($nwRowHtml2);
                ?>
                <form id='allPayeRatesForm' action='' method='post' accept-charset='UTF-8'>                        
                    <div class="row " style="margin-bottom:0px;padding:0px 15px 0px 15px !important;"> 
                        <div class="<?php echo $colClassType1; ?>" style="padding:0px 1px 0px 1px !important;"> 
                            <?php if ($canAdd || $canEdt) { ?>
                                <button type="button" class="btn btn-default btn-sm" style="margin-bottom: 5px;" onclick="insertNewTaxRateRows('allPayeRatesTable', 0, '<?php echo $nwRowHtml2; ?>');">
                                    <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                    ADD
                                </button>
                                <button type="button" class="btn btn-default btn-sm" style="margin-bottom: 5px;" onclick="saveGRAPAYEForm();">
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
                    <div class="row"> 
                        <div  class="col-md-12">
                            <table class="table table-striped table-bordered table-responsive" id="allPayeRatesTable" cellspacing="0" width="100%" style="width:100%;">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Level/Order No.</th>
                                        <th>Taxable Amount</th>
                                        <th>Tax Rate in Fractions or Decimals</th>
                                        <?php if ($canDel === true && $canEdt === true) { ?>
                                            <th>...</th>
                                        <?php } ?>
                                        <?php if ($canVwRcHstry === true) { ?>
                                            <th>...</th>
                                        <?php } ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $rates_id = -1;
                                    $lvl_order = -1;
                                    $rates_amnt = -1;
                                    $tax_prcnt = -1;
                                    while ($row = loc_db_fetch_array($result)) {
                                        $rates_id = $row[0];
                                        $lvl_order = $row[1];
                                        $rates_amnt = $row[2];
                                        $tax_prcnt = $row[3];
                                        ?>
                                        <tr id="allPayeRatesRow_<?php echo $cntr; ?>">                                   
                                            <td class="lovtd"><?php echo ($cntr + 1); ?></td>
                                            <td class="lovtd">
                                                <input type="hidden" id="allPayeRatesRow<?php echo $cntr; ?>_RatesID" value="<?php echo $rates_id; ?>">
                                                <input class="form-control rqrdFld jbDetDbt" id="allPayeRatesRow<?php echo $cntr; ?>_Level" style="font-size: 13px !important;font-weight: bold !important;width:100% !important;" type = "text" value="<?php echo $lvl_order; ?>" onkeypress="gnrlFldKeyPress(event, 'allPayeRatesRow<?php echo $cntr; ?>_Level', 'allPayeRatesTable', 'jbDetDbt');"/>
                                            </td>
                                            <td class="lovtd">
                                                <input class="form-control rqrdFld jbDetCrdt" id="allPayeRatesRow<?php echo $cntr; ?>_Taxable" style="font-size: 13px !important;font-weight: bold !important;width:100% !important;" value="<?php
                                                echo number_format($rates_amnt, 2);
                                                ?>" onkeypress="gnrlFldKeyPress(event, 'allPayeRatesRow<?php echo $cntr; ?>_Taxable', 'allPayeRatesTable', 'jbDetCrdt');"/>
                                            </td>
                                            <td class="lovtd">
                                                <input class="form-control rqrdFld jbDetFuncRate" id="allPayeRatesRow<?php echo $cntr; ?>_Rate" style="font-size: 13px !important;font-weight: bold !important;width:100% !important;" value="<?php echo $tax_prcnt; ?>" onkeypress="gnrlFldKeyPress(event, 'allPayeRatesRow<?php echo $cntr; ?>_Rate', 'allPayeRatesTable', 'jbDetFuncRate');"/>
                                            </td>
                                            <?php if ($canDel === true && $canEdt === true) { ?>
                                                <td class="lovtd">
                                                    <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delPayTaxRateLn('allPayeRatesRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Tax Line">
                                                        <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                    </button>
                                                </td>
                                            <?php } ?>
                                            <?php if ($canVwRcHstry === true) { ?>
                                                <td class="lovtd">
                                                    <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" onclick="getRecHstry('<?php
                                                    echo urlencode(encrypt1(($rates_id . "|pay.pay_paye_rates|rates_id"), $smplTokenWord1));
                                                    ?>');" style="padding:2px !important;">
                                                        <img src="cmn_images/Information.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                    </button>
                                                </td>
                                            <?php } ?>
                                        </tr>
                                        <?php
                                        $cntr += 1;
                                    }
                                    ?>
                                </tbody>
                                <tfoot>                                                           
                                    <tr>
                                        <th>&nbsp;</th>
                                        <th style="text-align:right;">Taxable Amount:</th>
                                        <th>  
                                            <input type="text" class="form-control" aria-label="..." id="allPayeRatesUnitPrc" name="allPayeRatesUnitPrc" value="1" style="width:100% !important;">
                                        </th>
                                        <th>
                                            <div class="form-group col-md-12" style="padding:0px 0px 0px 0px !important;" style="width:100% !important;">
                                                <div  class="col-md-6"  style="padding:0px 0px 0px 0px !important;" style="width:100% !important;">
                                                    <button id="addNwAccbPyblsInvcSmryBtn" type="button" class="btn btn-default" style="height:30px;" onclick="testPAYETax();" data-toggle="tooltip" data-placement="bottom" title = "Test PAYE Tax">
                                                        <img src="cmn_images/tick_64.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                        Test PAYE Tax
                                                    </button>
                                                </div>                
                                                <div  class="col-md-6"  style="padding:0px 0px 0px 0px !important;" style="width:100% !important;">
                                                    <label id="allPayeRatesSQLTestRslts" style="width:100% !important;color:green;margin-left:1px;font-size: 13px;font-weight: bold;border:1px solid #ddd;border-radius: 5px;padding:5px;">
                                                        &nbsp;TEST RESULTS
                                                    </label>
                                                </div>
                                            </div>
                                        </th>
                                        <?php if ($canDel === true && $canEdt === true) { ?>
                                            <th>&nbsp;</th>
                                        <?php } ?>
                                        <?php if ($canVwRcHstry === true) { ?>
                                            <th>&nbsp;</th>
                                        <?php } ?>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>                     
                    </div>
                </form>
                <?php
            } else if ($vwtyp == 1) {
                //Test SQL
                header("content-type:application/json");
                $allPayeRatesUnitPrc = isset($_POST['allPayeRatesUnitPrc']) ? (float) cleanInputData($_POST['allPayeRatesUnitPrc']) : 0.00;
                $errMsg = "";
                $CalcItemValue = get_PayeRateValue($allPayeRatesUnitPrc);
                $arr_content['CalcItemValue'] = $CalcItemValue;
                $arr_content['message'] = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i>" . $errMsg . ": " . number_format($CalcItemValue,
                                2) . "</span>";
                echo json_encode($arr_content);
                exit();
            }
        }
    }
}
?>