<?php
$canAddRecs = test_prmssns($dfltPrvldgs[0], $mdlNm);
$canEdtRecs = test_prmssns($dfltPrvldgs[0], $mdlNm);
$canDelRecs = test_prmssns($dfltPrvldgs[0], $mdlNm);

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
                                    <span style=\"text-decoration:none;\">Summary Dashboard</span>
				</li>
                               </ul>
                              </div>";
                updateRoomOccpntCnt();
                $error = "";
                $searchAll = true;
                $srchFor = isset($_POST['searchfor']) ? cleanInputData($_POST['searchfor']) : '';
                $srchIn = isset($_POST['searchin']) ? cleanInputData($_POST['searchin']) : 'Facility Type Name';
                $pageNo = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
                $lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 28;

                $vPsblValID = getEnbldPssblValID("Default Facility Type", getLovID("All Other Hospitality Setups"));
                $vPsblVal = getPssblValDesc($vPsblValID);

                $sortBy = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : $vPsblVal;
                $fcltyType = $sortBy;
                $qShwUnpstdOnly = false;
                if (isset($_POST['qShwUnpstdOnly'])) {
                    $qShwUnpstdOnly = cleanInputData($_POST['qShwUnpstdOnly']) === "true" ? true : false;
                }
                $qShwUnpaidOnly = false;
                if (isset($_POST['qShwUnpaidOnly'])) {
                    $qShwUnpaidOnly = cleanInputData($_POST['qShwUnpaidOnly']) === "true" ? true : false;
                }
                $qShwSelfOnly = $vwOnlySelf;
                if (isset($_POST['qShwSelfOnly'])) {
                    $qShwSelfOnly = cleanInputData($_POST['qShwSelfOnly']) === "true" ? true : false;
                }
                if (strpos($srchFor, "%") === FALSE) {
                    $srchFor = "%" . str_replace(" ", "%", $srchFor) . "%";
                    $srchFor = str_replace("%%", "%", $srchFor);
                }
                $total = get_Ttl_dshbrd_rooms($srchFor, $srchIn, $orgID, $fcltyType);
                if ($pageNo > ceil($total / $lmtSze)) {
                    $pageNo = 1;
                } else if ($pageNo < 1) {
                    $pageNo = ceil($total / $lmtSze);
                }
                $curIdx = $pageNo - 1;
                $result = get_dshbrd_rooms($srchFor, $srchIn, $curIdx, $lmtSze, $orgID, $fcltyType);
                $cntr = 0;
                ?>
                <form id='hotlSmryDshBrdForm' action='' method='post' accept-charset='UTF-8'>
                    <div class="row rhoRowMargin">
                        <div class="col-md-3" style="padding:0px 15px 0px 15px !important;">
                            <div class="input-group">
                                <input class="form-control" id="hotlSmryDshBrdSrchFor" type = "text" placeholder="Search For" value="<?php echo trim(str_replace("%", " ", $srchFor)); ?>" onkeyup="enterKeyFuncHotlSmryDshBrd(event, '', '#allmodules', 'grp=18&typ=1&pg=1&vtyp=0')">
                                <input id="hotlSmryDshBrdPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                <label class="btn btn-primary btn-file input-group-addon" onclick="getHotlSmryDshBrd('clear', '#allmodules', 'grp=18&typ=1&pg=1&vtyp=0')">
                                    <span class="glyphicon glyphicon-remove"></span>
                                </label>
                                <label class="btn btn-primary btn-file input-group-addon" onclick="getHotlSmryDshBrd('', '#allmodules', 'grp=18&typ=1&pg=1&vtyp=0')">
                                    <span class="glyphicon glyphicon-search"></span>
                                </label> 
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="input-group">
                                <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                <select data-placeholder="Select..." class="form-control chosen-select" id="hotlSmryDshBrdSrchIn">
                                    <?php
                                    $valslctdArry = array("", "", "");
                                    $srchInsArrys = array("Facility Type Name", "Facility Type Description", "Facility Name");

                                    for ($z = 0; $z < count($srchInsArrys); $z++) {
                                        if ($srchIn == $srchInsArrys[$z]) {
                                            $valslctdArry[$z] = "selected";
                                        }
                                        ?>
                                        <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                    <?php } ?>
                                </select>
                                <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                <select data-placeholder="Select..." class="form-control chosen-select" id="hotlSmryDshBrdDsplySze" style="min-width:70px !important;">                            
                                    <?php
                                    $valslctdArry = array("", "", "", "", "", "");
                                    $dsplySzeArry = array(4, 12, 20, 28, 32, 10000);
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
                        <div class="col-md-3">
                            <div class="input-group">                        
                                <span class="input-group-addon"><span class="glyphicon glyphicon-sort-by-attributes"></span>Facility Type</span>
                                <select data-placeholder="Select..." class="form-control chosen-select" id="hotlSmryDshBrdSortBy" onchange="getHotlSmryDshBrd('', '#allmodules', 'grp=18&typ=1&pg=1&vtyp=0');">
                                    <?php
                                    $valslctdArry = array("", "", "", "");
                                    $srchInsArrys = array("Room/Hall", "Field/Yard", "Restaurant Table", "Rental Item");
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
                        <div class="col-md-2">
                            <nav aria-label="Page navigation">
                                <ul class="pagination" style="margin: 0px !important;">
                                    <li>
                                        <a class="rhopagination" href="javascript:getHotlSmryDshBrd('previous', '#allmodules', 'grp=18&typ=1&pg=1&vtyp=0');" aria-label="Previous">
                                            <span aria-hidden="true">&laquo;</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="rhopagination" href="javascript:getHotlSmryDshBrd('next', '#allmodules', 'grp=18&typ=1&pg=1&vtyp=0');" aria-label="Next">
                                            <span aria-hidden="true">&raquo;</span>
                                        </a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                    <div class="row"  style="padding:1px 15px 1px 17px !important;"><hr style="margin:1px 0px 3px 0px;border-color:#ccc;"></div> 
                    <div class="row" style="padding:0px 15px 0px 15px;">
                        <?php
                        $rwcnt = loc_db_num_rows($result);
                        $grpcntr = 0;
                        $cntent = "";
                        for ($i = 0; $i < $rwcnt; $i++) {
                            $rwRec = loc_db_fetch_array($result);
                            $myBtnBackColor = "#DCDCDC"; //Gainsboro;
                            $myBtnImageKey = "90.png";
                            if ($rwRec[4] == "AVAILABLE") {
                                $myBtnBackColor = "#00FF00"; //Lime
                                $myBtnImageKey = "tick_64.png";
                            } else if ($rwRec[4] == "RESERVED") {
                                $myBtnBackColor = "#00FFFF"; //Cyan;
                                $myBtnImageKey = "person.png";
                            } else if ($rwRec[4] == "PARTIALLY ISSUED OUT") {
                                $myBtnBackColor = "#FFC0CB"; //Pink;
                                $myBtnImageKey = "person.png";
                            } else if ($rwRec[4] == "FULLY ISSUED OUT") {
                                $myBtnBackColor = "#ff0000"; //Red;
                                $myBtnImageKey = "person.png";
                            } else if ($rwRec[4] == "OVERLOADED") {
                                $myBtnBackColor = "#8B0000"; //DarkRed;
                                $myBtnImageKey = "person.png";
                            } else if ($rwRec[4] == "DIRTY") {
                                $myBtnBackColor = "#FFA500"; //Orange;
                                $myBtnImageKey = "BuildingManagement.png";
                            } else if ($rwRec[4] == "BLOCKED") {
                                $myBtnBackColor = "#DCDCDC"; //Gainsboro;
                                $myBtnImageKey = "90.png";
                            }
                            $dirty = " [Clean]";
                            if ($rwRec[7] == "1") {
                                $dirty = " [Dirty]";
                            }
                            $myBtnText = str_replace("()", "",
                                    ($rwRec[1] .
                                    " (" . $rwRec[4] .
                                    " [" . $rwRec[6] . "/" .
                                    $rwRec[5] . "] )" .
                                    " (" . substr($rwRec[10], 0, 25) . ")" .
                                    " (" . $rwRec[9] . ")" . $dirty));
                            $No = $i + 1;
                            if ($grpcntr == 0) {
                                $cntent .= "<div class=\"row\">";
                            }
                            $cntent .= "<div class=\"col-md-3\" style=\"padding:0px 13px 0px 15px;height:100% !important;min-height:100% !important;\">
                                            <div id=\"hotlSmryDshBrdBtn$No\" class=\"panel panel-default mycntxtmenu\" style=\"color:black;background-color:$myBtnBackColor;border-radius:5px;border:1px solid $myBtnBackColor;\" oncontextmenu=\"enblDisableHotlMenu('hotlSmryDshBrdBtn$No');\">
                                                <a href=\"javascript:callHotlBtnClickFunc(event,'hotlSmryDshBrdBtn$No');\">
                                                    <div class=\"panel-heading\" style=\"color:black;background-color:$myBtnBackColor;border-radius:5px;border:1px solid $myBtnBackColor;\">
                                                        <div class=\"row\">
                                                            <div class=\"col-xs-3\">
                                                                <img src=\"cmn_images/$myBtnImageKey\" style=\"margin:5px; padding-right: 1em; height:58px; width:auto; position: relative; vertical-align: middle;float:left;\">
                                                            </div>
                                                            <div class=\"col-xs-9 text-right\">
                                                                <div class=\"huge\" style=\"font-weight:bold;min-height:60px !important;height:60px !important;\">$myBtnText</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class=\"panel-footer\" style=\"color:white;background-color:$myBtnBackColor;border-top:1px solid $myBtnBackColor;\">
                                                        <!--<span class=\"pull-left\">View Details</span>
                                                        <span class=\"pull-right\"><i class=\"fa fa-arrow-circle-right\"></i></span>
                                                        <div class=\"clearfix\"></div>-->
                                                    </div>
                                                </a>
                                                <input id=\"hotlSmryDshBrdBtn" . $No . "_RoomID\" type=\"hidden\" value=\"" . $rwRec[0] . "\"/>
                                                <input id=\"hotlSmryDshBrdBtn" . $No . "_SrvsTypID\" type=\"hidden\" value=\"" . $rwRec[11] . "\"/>
                                                <input id=\"hotlSmryDshBrdBtn" . $No . "_RoomNm\" type=\"hidden\" value=\"" . $rwRec[1] . "\"/>
                                                <input id=\"hotlSmryDshBrdBtn" . $No . "_Status\" type=\"hidden\" value=\"" . $rwRec[4] . "\"/>
                                                <input id=\"hotlSmryDshBrdBtn" . $No . "_BtnText\" type=\"hidden\" value=\"" . $myBtnText . "\"/>
                                            </div>
                                        </div>";
                            if ($grpcntr == 3) {
                                $cntent .= "</div>";
                                $grpcntr = 0;
                            } else {
                                $grpcntr = $grpcntr + 1;
                            }
                        }
                        echo $cntent;
                        ?>
                    </div>
                </form>
                <div class="menu1">
                    <ul class="menu1-options">
                        <li class="menu1-option" id="openCheckinMenuItem"><img src="cmn_images/openfileicon.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">Open Check-In(s)/ Reservation(s)</li>
                        <li class="menu1-option" id="createRsrvtnMenuItem"><img src="cmn_images/staffs.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">Create Reservation</li>
                        <li class="menu1-option" id="createChckInMenuItem"><img src="cmn_images/person.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">Create Check-In</li>
                        <li class="menu1-option" id="vwRoomMenuItem"><img src="cmn_images/search_64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">View Facility Setup</li>
                        <li class="menu1-option"><hr style="margin:1px 0px 1px 0px;border-color:#ccc;"></li>
                        <li class="menu1-option" id="mkDirtyMenuItem"><img src="cmn_images/main_menu.jpg" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">Change Status to Dirty</li>
                        <li class="menu1-option" id="mkCleanMenuItem"><img src="cmn_images/tick_64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">Change Status to Clean</li>
                        <li class="menu1-option" id="blckUnblockMenuItem"><img src="cmn_images/90.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">Block Facility</li>
                        <li class="menu1-option"><hr style="margin:1px 0px 1px 0px;border-color:#ccc;"></li>
                        <li class="menu1-option" onclick="getHotlSmryDshBrd('', '#allmodules', 'grp=18&typ=1&pg=1&vtyp=0');"><img src="cmn_images/refresh.bmp" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">Refresh</li>
                        <li class="menu1-option" onclick="toggleMenu('hide');"><img src="cmn_images/close.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">Close</li>
                    </ul>
                </div>
                <?php
            }
        }
    }
}
?>