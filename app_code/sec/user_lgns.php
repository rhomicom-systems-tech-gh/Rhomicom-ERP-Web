<?php
$pageNo = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
$lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 10;
$sortBy = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : "Role Name";

if (array_key_exists('lgn_num', get_defined_vars())) {
    if ($lgn_num > 0 && $canview === true) {
        if ($qstr == "DELETE") {
            if ($actyp == 1) {
                /* Force Logout Logon */
                $lgnUserID = isset($_POST['lgnUserID']) ? (float) cleanInputData($_POST['lgnUserID']) : -1;
                if ($lgnUserID > 0 && test_prmssns($dfltPrvldgs[6], $mdlNm)) {
                    $userNum = getUserName($lgnUserID);
                    echo forceLgOutUserLgns($lgnUserID, $userNum);
                } else {
                    restricted();
                }
            }
        } else if ($qstr == "UPDATE") {
            if ($actyp == 1) {
                
            } else if ($actyp == 2) {
                
            }
        } else {
            if ($vwtyp == 0) {
                echo $cntent . "<li>
						<span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                                <span style=\"text-decoration:none;\">Track User Logins</span>
					</li>
                                       </ul>
                                     </div>";
                $shwFld = isset($_POST ['qShwFailedOnly']) ? cleanInputData($_POST['qShwFailedOnly']) : "false";
                $shw_sccfl = isset($_POST ['qShwSccflOnly']) ? cleanInputData($_POST['qShwSccflOnly']) : "false";
                $shw_actv = isset($_POST ['qShwActvOnly']) ? cleanInputData($_POST['qShwActvOnly']) : "false";

                $total = get_UserLgnsTtl($srchFor, $srchIn, $shwFld, $shw_sccfl, $shw_actv);
                if ($pageNo > ceil($total / $lmtSze)) {
                    $pageNo = 1;
                } else if ($pageNo < 1) {
                    $pageNo = ceil($total / $lmtSze);
                }

                $curIdx = $pageNo - 1;
                $result = get_UserLgns($srchFor, $srchIn, $curIdx, $lmtSze, $shwFld, $shw_sccfl, $shw_actv);
                $cntr = 0;
                ?>
                <form id='trckUsrLgnsForm' action='' method='post' accept-charset='UTF-8'>
                    <div class="row rhoRowMargin">
                        <div class="col-md-3" style="padding:0px 15px 0px 15px !important;">
                            <div class="input-group">
                                <input class="form-control" id="trckUsrLgnsSrchFor" name="trckUsrLgnsSrchFor" type = "text" placeholder="Search For" value="<?php
                                echo trim(str_replace("%", " ", $srchFor));
                                ?>" onkeyup="enterKeyFuncTrckUsrLgns(event, '', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=0')">
                                <input id="trckUsrLgnsPageNo" name="trckUsrLgnsPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                <label class="btn btn-primary btn-file input-group-addon" onclick="getTrckUsrLgns('clear', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=0')">
                                    <span class="glyphicon glyphicon-remove"></span>
                                </label>
                                <label class="btn btn-primary btn-file input-group-addon" onclick="getTrckUsrLgns('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=0')">
                                    <span class="glyphicon glyphicon-search"></span>
                                </label> 
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="input-group">
                                <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                <select data-placeholder="Select..." class="form-control chosen-select" id="trckUsrLgnsSrchIn">
                                    <?php
                                    $valslctdArry = array("", "", "", "", "");
                                    $srchInsArrys = array("Login Number", "User Name", "Login Time", "Logout Time", "Machine Details");

                                    for ($z = 0; $z < count($srchInsArrys); $z++) {
                                        if ($srchIn == $srchInsArrys[$z]) {
                                            $valslctdArry[$z] = "selected";
                                        }
                                        ?>
                                        <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                    <?php } ?>
                                </select>
                                <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                <select data-placeholder="Select..." class="form-control chosen-select" id="trckUsrLgnsDsplySze" name="trckUsrLgnsDsplySze" style="min-width:70px !important;">                            
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
                        <div class="col-md-3">
                            <div class="input-group">                        
                                <span class="input-group-addon"><span class="glyphicon glyphicon-sort-by-attributes"></span></span>
                                <select data-placeholder="Select..." class="form-control chosen-select" id="trckUsrLgnsSortBy" name="trckUsrLgnsSortBy">
                                    <?php
                                    $valslctdArry = array("", "", "", "", "");
                                    $srchInsArrys = array("Login Number", "User Name", "Login Time", "Logout Time", "Machine Details");

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
                                        <a class="rhopagination" href="javascript:getTrckUsrLgns('previous', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=0');" aria-label="Previous">
                                            <span aria-hidden="true">&laquo;</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:getTrckUsrLgns('next', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=0');" aria-label="Next">
                                            <span aria-hidden="true">&raquo;</span>
                                        </a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                    <div class="row " style="margin-bottom:2px;padding:2px 15px 2px 15px !important">   
                        <div class="col-md-12" style="padding:2px 1px 2px 1px !important;border-top:1px solid #ddd;border-bottom:1px solid #ddd;">
                            <div class="col-md-4" style="padding:5px 1px 0px 1px !important;">
                                <div class="form-check" style="font-size: 12px !important;">
                                    <label class="form-check-label">
                                        <?php
                                        $shwFldChekd = "";
                                        if ($shwFld === "true") {
                                            $shwFldChekd = "checked=\"true\"";
                                        }
                                        $shw_sccflChekd = "";
                                        if ($shw_sccfl === "true") {
                                            $shw_sccflChekd = "checked=\"true\"";
                                        }
                                        $shw_actvChekd = "";
                                        if ($shw_actv === "true") {
                                            $shw_actvChekd = "checked=\"true\"";
                                        }
                                        ?>
                                        <input type="checkbox" class="form-check-input" onclick="getTrckUsrLgns('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=0');" id="trckUsrLgnsFailedOnly" name="trckUsrLgnsFailedOnly" <?php echo $shwFldChekd; ?>>
                                        Failed Logons Only
                                    </label>
                                </div>                            
                            </div>
                            <div class="col-md-4" style="padding:5px 1px 0px 1px !important;">
                                <div class="form-check" style="font-size: 12px !important;">
                                    <label class="form-check-label">
                                        <input type="checkbox" class="form-check-input" onclick="getTrckUsrLgns('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=0');" id="trckUsrLgnsSccflOnly" name="trckUsrLgnsSccflOnly"  <?php echo $shw_sccflChekd; ?>>
                                        Successful Logons Only
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-4" style="padding:5px 1px 0px 1px !important;">
                                <div class="form-check" style="font-size: 12px !important;">
                                    <label class="form-check-label">
                                        <input type="checkbox" class="form-check-input" onclick="getTrckUsrLgns('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=0');" id="trckUsrLgnsActvOnly" name="trckUsrLgnsActvOnly"  <?php echo $shw_actvChekd; ?>>
                                        Active/Online Users Only
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row"> 
                        <div  class="col-md-12">
                            <table class="table table-striped table-bordered table-responsive" id="trckUsrLgnsTable" cellspacing="0" width="100%" style="width:100%;min-width: 800px;">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>User Name</th>
                                        <th style="min-width:80px;width:80px;">Login Time</th>
                                        <th style="min-width:80px;width:80px;">Logout Time</th>
                                        <th style="min-width:240px;width:240px;">Machine Details</th>
                                        <th style="max-width:65px;width:65px;">Attempt Successful?</th>
                                        <th>Login Number</th>
                                        <th style="min-width:80px;width:80px;">Last Active Time</th>
                                        <th style="min-width:110px;width:110px;">Logon Remarks</th>
                                        <th>&nbsp;</th>
                                        <th>&nbsp;</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    while ($row = loc_db_fetch_array($result)) {
                                        $cntr += 1;
                                        $style = "";
                                        if ($row[2] == "" && $row[4] == "YES") {
                                            $style = "background-color:lime;";
                                        } else if ($row[4] == "NO") {
                                            $style = "background-color:red;";
                                        } else if ($row[2] != "" && $row[4] == "YES") {
                                            $style = "background-color:lightblue;";
                                        }
                                        ?>
                                        <tr id="trckUsrLgnsEdtRow_<?php echo $cntr; ?>">                                    
                                            <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                            <td class="lovtd">
                                                <span><?php echo $row[0]; ?></span>                                                         
                                            </td>
                                            <td class="lovtd" style="<?php echo $style; ?>">
                                                <span><?php echo $row[1]; ?></span>                                                         
                                            </td>
                                            <td class="lovtd">
                                                <span><?php echo $row[2]; ?></span>                                                        
                                            </td>
                                            <td class="lovtd">
                                                <span><?php echo $row[3]; ?></span>                                                       
                                            </td>
                                            <td class="lovtd" style="<?php echo $style; ?>">
                                                <span><?php echo $row[4]; ?></span>                                                       
                                            </td>
                                            <td class="lovtd">
                                                <span><?php echo $row[6]; ?></span>                                                       
                                            </td>
                                            <td class="lovtd" style="<?php echo $style; ?>">
                                                <span><?php echo $row[7]; ?></span>                                                       
                                            </td>
                                            <td class="lovtd">
                                                <span><?php echo $row[8]; ?></span>                                                       
                                            </td>
                                            <td class="lovtd">
                                                <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Activity" onclick="getOneUsrLgnDet('<?php echo $row[6]; ?>', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=1');" style="padding:2px !important;" style="padding:2px !important;">
                                                    <img src="cmn_images/kghostview.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                </button>
                                            </td>
                                            <td class="lovtd">
                                                <?php if ($row[2] == "" && $row[4] == "YES") { ?>
                                                    <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="Force Logout" onclick="forceUsrLgout(<?php echo $row[5]; ?>);" style="padding:2px !important;" style="padding:2px !important;">
                                                        <img src="cmn_images/90.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                    </button>
                                                <?php } else { ?>
                                                    <span>&nbsp;</span>
                                                <?php } ?>
                                            </td>
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
                //Get Activity File Contents
                $sbmtdLgnNum = isset($_POST['sbmtdLgnNum']) ? cleanInputData($_POST['sbmtdLgnNum']) : -1;
                $file = $ftp_base_db_fldr . "/bin/log_files/$sbmtdLgnNum" . ".rho";
                if (file_exists($file)) {
                    $text ="<div class=\"\" style=\"float:right;display: block;\">
                                <a id=\"rho_diag_top\"></a>
                                <a href=\"#rho_diag_bottom\" class=\"btn btn-primary\">Move Down</a>
                                <button type=\"button\" class=\"btn btn-success\" onclick=\"getOneUsrLgnDet(".$sbmtdLgnNum.", 'grp=3&typ=1&pg=7&vtyp=1', 'ReloadDialog');\">Refresh</button>
                                <button type=\"button\" class=\"btn btn-default\" data-dismiss=\"modal\">Close</button>
            </div>";
                    $text .="<div class=\"\" style=\"float:left;width:100% !important;\">". str_replace(PHP_EOL, "<br/>", file_get_contents($file))."</div>";
                    $text.="<div class=\"\" style=\"float:right;display: block;\">
                                    <a href=\"#rho_diag_top\" class=\"btn btn-primary\">Move Up</a>
                                    <button type=\"button\" class=\"btn btn-success\" onclick=\"getOneUsrLgnDet(".$sbmtdLgnNum.", 'grp=3&typ=1&pg=7&vtyp=1', 'ReloadDialog');\">Refresh</button>
                                    <button type=\"button\" class=\"btn btn-default\" data-dismiss=\"modal\">Close</button>
                                    <a id=\"rho_diag_bottom\"></a>
            </div>";
                    echo $text;
                } else {
                    echo "File not Found!";
                }
            } else if ($vwtyp == 2) {
                
            } else if ($vwtyp == 3) {
                
            } else if ($vwtyp == 4) {
                
            }
        }
    }
}