<?php
$canVwRcHstry = test_prmssns("View Record History", $mdlNm);

$pageNo = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
$lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 100;
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
                //Search for Transactions
                echo $cntent . "<li onclick=\"openATab('#allmodules', 'grp=$group&typ=$type&pg=$pgNo&vtyp=0');\">
                                <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                <span style=\"text-decoration:none;\">Search Attendances</span>
                            </li>
                           </ul>
                          </div>";
                $error = "";
                $searchAll = true;
                $srchFor = isset($_POST['searchfor']) ? cleanInputData($_POST['searchfor']) : '';
                $srchIn = isset($_POST['searchin']) ? cleanInputData($_POST['searchin']) : 'Both';
                $pageNo = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
                $lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 50;
                $sortBy = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : "Date (DESC)";
                $qShwPstdOnly = true;
                if (isset($_POST['qShwPstdOnly'])) {
                    $qShwPstdOnly = cleanInputData($_POST['qShwPstdOnly']) === "true" ? true : false;
                }
                $qShwIntrfc = false;
                if (isset($_POST['qShwIntrfc'])) {
                    $qShwIntrfc = cleanInputData($_POST['qShwIntrfc']) === "true" ? true : false;
                }
                $qLowVal = 0;
                $qHighVal = 0;
                $date = new DateTime($gnrlTrnsDteYMD);
                $date->modify('-24 month');
                $qStrtDte = $date->format('d-M-Y') . " 00:00:00";
                $date = new DateTime($gnrlTrnsDteYMD);
                $date->modify('+24 month');
                $qEndDte = $date->format('d-M-Y') . " 23:59:59";
                /* $qStrtDte = substr($gnrlTrnsDteDMYHMS, 0, 11) . " 00:00:00";
                  $qEndDte = substr($gnrlTrnsDteDMYHMS, 0, 11) . " 23:59:59"; */
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
                if (isset($_POST['qEndDte'])) {
                    $qEndDte = cleanInputData($_POST['qEndDte']);
                    if (strlen($qEndDte) == 11) {
                        $qEndDte = substr($qEndDte, 0, 11) . " 23:59:59";
                    } else {
                        $qEndDte = "31-Dec-4000 23:59:59";
                    }
                }
                if (strpos($srchFor, "%") === FALSE) {
                    $srchFor = "%" . str_replace(" ", "%", $srchFor) . "%";
                    $srchFor = str_replace("%%", "%", $srchFor);
                }
                if ($vwtyp == 0) {
                    $total = get_Total_AttnRgstr_SrchLns($srchFor, $srchIn, $orgID, $qStrtDte, $qEndDte);
                    if ($pageNo > ceil($total / $lmtSze)) {
                        $pageNo = 1;
                    } else if ($pageNo < 1) {
                        $pageNo = ceil($total / $lmtSze);
                    }
                    $curIdx = $pageNo - 1;
                    $result = get_AttnRgstr_SrchLns($srchFor, $srchIn, $curIdx, $lmtSze, $orgID, $qStrtDte, $qEndDte, $sortBy);
                    $cntr = 0;
                    $colClassType1 = "col-md-2";
                    $colClassType2 = "col-md-4";
                    ?> 
                    <form id='attnRegstrSrchForm' action='' method='post' accept-charset='UTF-8'>
                        <!--ROW ID-->
                        <input class="form-control" id="tblRowID" type = "hidden" placeholder="ROW ID"/>                     
                        <fieldset class=""><legend class="basic_person_lg1" style="color: #003245">SEARCH ATTENDANCES</legend>
                            <div class="row" style="margin-bottom:0px;">                                
                                <div class="col-md-6" style="padding:0px 1px 0px 15px !important;">
                                    <div class="input-group">
                                        <input class="form-control" id="attnRegstrSrchSrchFor" type = "text" placeholder="Search For" value="<?php
                                        echo trim(str_replace("%", " ", $srchFor));
                                        ?>" onkeyup="enterKeyFuncAttnRegstrSrch(event, '', '#allmodules', 'grp=16&typ=1&pg=6&vtyp=0')">
                                        <input id="attnRegstrSrchPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getAttnRegstrSrch('clear', '#allmodules', 'grp=16&typ=1&pg=6&vtyp=0');">
                                            <span class="glyphicon glyphicon-remove"></span>
                                        </label>
                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getAttnRegstrSrch('', '#allmodules', 'grp=16&typ=1&pg=6&vtyp=0');">
                                            <span class="glyphicon glyphicon-search"></span>
                                        </label> 
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                        <select data-placeholder="Select..." class="form-control chosen-select" id="attnRegstrSrchSrchIn">
                                            <?php
                                            $valslctdArry = array("", "", "", "", "", "", "");
                                            $srchInsArrys = array("All", "Register Name", "Person Name/ID", "Date/Time In",
                                                "Date/Time Out", "Is Present?", "Attendance Comments");
                                            for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                if ($srchIn == $srchInsArrys[$z]) {
                                                    $valslctdArry[$z] = "selected";
                                                }
                                                ?>
                                                <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                            <?php } ?>
                                        </select>
                                        <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                        <select data-placeholder="Select..." class="form-control chosen-select" id="attnRegstrSrchDsplySze" style="min-width:70px !important;">                            
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
                                        <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                        <select data-placeholder="Select..." class="form-control chosen-select" id="attnRegstrSrchSortBy" style="width:100% !important;">
                                            <?php
                                            $valslctdArry = array("", "", "", "");
                                            $srchInsArrys = array("Date (DESC)", "Date (ASC)", "Person No.", "Person Name");
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
                                <div class="col-md-1" style="padding:0px 1px 0px 1px !important;">
                                    <nav aria-label="Page navigation">
                                        <ul class="pagination" style="margin: 0px !important;">
                                            <li>
                                                <a href="javascript:getAttnRegstrSrch('previous', '#allmodules', 'grp=16&typ=1&pg=6&vtyp=0');" aria-label="Previous">
                                                    <span aria-hidden="true">&laquo;</span>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:getAttnRegstrSrch('next', '#allmodules', 'grp=16&typ=1&pg=6&vtyp=0');" aria-label="Next">
                                                    <span aria-hidden="true">&raquo;</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </nav>
                                </div>
                                <div class="col-md-4" style="padding:0px 1px 0px 1px !important;">
                                    <div class="col-xs-6" style="padding:0px 1px 0px 0px !important;">
                                        <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                            <input class="form-control" size="16" type="text" id="attnRegstrSrchStrtDate" name="attnRegstrSrchStrtDate" value="<?php
                                            echo substr($qStrtDte, 0, 11);
                                            ?>" placeholder="Start Date">
                                            <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                        </div>
                                    </div>
                                    <div class="col-xs-6" style="padding:0px 1px 0px 0px !important;">
                                        <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                            <input class="form-control" size="16" type="text"  id="attnRegstrSrchEndDate" name="attnRegstrSrchEndDate" value="<?php
                                            echo substr($qEndDte, 0, 11);
                                            ?>" placeholder="End Date">
                                            <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                        </div>
                                    </div>                            
                                </div>
                                <div class="col-md-1" style="padding:0px 1px 0px 1px !important;">
                                    <button type="button" class="btn btn-default" onclick="funcHtmlToExcel('attnRegstrSrchHdrsTable');" style="">
                                        <img src="cmn_images/image007.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                    </button>
                                </div> 
                            </div> 
                            <div class="row"> 
                                <div  class="col-md-12">
                                    <table class="table table-striped table-bordered table-responsive" id="attnRegstrSrchHdrsTable" cellspacing="0" width="100%" style="width:100%;">
                                        <thead>
                                            <tr>
                                                <th style="max-width:25px;width:25px;">No.</th>
                                                <th>Person No.</th>
                                                <th>Person Name</th>	
                                                <th>Register Name</th>	
                                                <th style="text-align:center;">Present?</th>
                                                <th style="max-width:120px;width:120px;">Date/Time In</th>
                                                <th style="max-width:120px;width:120px;">Date/Time Out</th>
                                                <th>Comments/Remarks</th>
                                                <?php if ($canVwRcHstry === true) { ?>
                                                    <th style="max-width:20px;width:20px;">...</th>
                                                <?php } ?>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $rcHstryTblNm = "";
                                            $rcHstryPKeyColNm = "";
                                            $rcHstryPKeyColVal = "";
                                            while ($row = loc_db_fetch_array($result)) {
                                                $cntr += 1;
                                                $trnsLnRecID = ((float) $row[0]);
                                                $trnsLnHdrID = ((float) $row[1]);
                                                $trnsLnPrsnID = ((float) $row[2]);
                                                $trnsLnCstmrID = ((float) $row[3]);
                                                $trnsLnPrsnNum = $row[4];
                                                $trnsLnPrsnName = $row[5];
                                                $trnsLnRgstrName = $row[6];
                                                $trnsLnIsPresent = $row[7];
                                                $trnsLnDateIn = $row[8];
                                                $trnsLnDateOut = $row[9];
                                                $trnsLnRemarks = $row[10];
                                                ?>
                                                <tr id="attnRegstrSrchHdrsRow_<?php echo $cntr; ?>">                                    
                                                    <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>    
                                                    <td class="lovtd" style="word-wrap: break-word;">
                                                        <input type="hidden" id="attnRegstrSrchHdrsRow<?php echo $cntr; ?>_RecID" name="attnRegstrSrchHdrsRow<?php echo $cntr; ?>_RecID" value="<?php echo $trnsLnRecID; ?>">
                                                        <input type="hidden" id="attnRegstrSrchHdrsRow<?php echo $cntr; ?>_HdrID" name="attnRegstrSrchHdrsRow<?php echo $cntr; ?>_HdrID" value="<?php echo $trnsLnHdrID; ?>">
                                                        <input type="hidden" id="attnRegstrSrchHdrsRow<?php echo $cntr; ?>_PrsnID" name="attnRegstrSrchHdrsRow<?php echo $cntr; ?>_PrsnID" value="<?php echo $trnsLnPrsnID; ?>">
                                                        <input type="hidden" id="attnRegstrSrchHdrsRow<?php echo $cntr; ?>_CstmrID" name="attnRegstrSrchHdrsRow<?php echo $cntr; ?>_CstmrID" value="<?php echo $trnsLnCstmrID; ?>">
                                                        <?php echo $trnsLnPrsnNum; ?>
                                                    </td>
                                                    <td class="lovtd" style="word-wrap: break-word;"><?php echo $trnsLnPrsnName; ?></td>
                                                    <td class="lovtd">
                                                        <?php
                                                        echo $trnsLnRgstrName;
                                                        ?>
                                                    </td>                                           
                                                    <td class="lovtd" style="text-align:center;">
                                                        <?php
                                                        $isChkd = "";
                                                        if ($trnsLnIsPresent == "1") {
                                                            $isChkd = "checked=\"true\"";
                                                        }
                                                        ?>
                                                        <div class="form-group form-group-sm">
                                                            <div class="form-check" style="font-size: 12px !important;">
                                                                <label class="form-check-label">
                                                                    <input type="checkbox" class="form-check-input" id="attnRegstrSrchHdrsRow<?php echo $cntr; ?>_IsPresent" name="attnRegstrSrchHdrsRow<?php echo $cntr; ?>_IsPresent" <?php echo $isChkd ?>>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </td> 
                                                    <td class="lovtd" style="word-wrap: break-word;"><?php echo $trnsLnDateIn; ?></td>
                                                    <td class="lovtd" style="word-wrap: break-word;"><?php echo $trnsLnDateOut; ?></td>
                                                    <td class="lovtd" style="word-wrap: break-word;"><?php echo $trnsLnRemarks; ?></td>
                                                    <?php if ($canVwRcHstry === true) { ?>
                                                        <td class="lovtd">
                                                            <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" 
                                                                    onclick="getRecHstry('<?php
                                                                    echo urlencode(encrypt1(($trnsLnRecID . "|attn.attn_attendance_recs|attnd_rec_id"),
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
                                        <tfoot>                                                            
                                            <tr>
                                                <th>&nbsp;</th>
                                                <th>&nbsp;</th>
                                                <th>&nbsp;</th>
                                                <th>&nbsp;</th>
                                                <th>&nbsp;</th>
                                                <th>&nbsp;</th>
                                                <th style="">&nbsp;</th>                                           
                                                <th style="">&nbsp;</th>
                                                <?php if ($canVwRcHstry === true) { ?>
                                                    <th style="max-width:20px;width:20px;">&nbsp;</th>
                                                    <?php } ?>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>                     
                            </div>
                        </fieldset>
                    </form>
                    <?php
                }
            } else if ($vwtyp == 1) {
                
            } else if ($vwtyp == 2) {
                
            }
        }
    }
}
?>