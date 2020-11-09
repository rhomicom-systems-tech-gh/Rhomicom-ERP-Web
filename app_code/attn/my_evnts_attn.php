<?php
$pageNo = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
$lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 10;
$sortBy = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : "";
$curIdx = 0;
$orgID = $_SESSION['ORG_ID'];
$usrID = $_SESSION['USRID'];
$prsnid = $_SESSION['PRSN_ID'];

if (array_key_exists('lgn_num', get_defined_vars())) {
    if ($lgn_num > 0 && $canview === true) {
        if ($qstr == "DELETE") {
            if ($actyp == 1) {
                
            }
        } else if ($qstr == "UPDATE") {
            if ($actyp == 1) {
                
            } else if ($actyp == 2) {
                
            }
        } else {
            if ($vwtyp == 0) {

                echo $cntent . "<li>
						<span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                                <span style=\"text-decoration:none;\">My Events</span>
					</li>
                                       </ul>
                                     </div>";
                $total = get_MyEvntsAttndTtl($srchFor, $srchIn, $prsnid);
                if ($pageNo > ceil($total / $lmtSze)) {
                    $pageNo = 1;
                } else if ($pageNo < 1) {
                    $pageNo = ceil($total / $lmtSze);
                }

                $curIdx = $pageNo - 1;
                $result = get_MyEvntsAttndTblr($srchFor, $srchIn, $curIdx, $lmtSze, $prsnid);
                $cntr = 0;
                $colClassType1 = "col-lg-2";
                $colClassType2 = "col-lg-4";
                ?>
                <form id='myEvntsAtndForm' action='' method='post' accept-charset='UTF-8'>
                    <div class="row rhoRowMargin">
                        <div class="<?php echo $colClassType1; ?>" style="padding:0px 1px 0px 15px !important;">                    
                            <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="">
                                <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                New Registration
                            </button>
                        </div>
                        <div class="<?php echo $colClassType2; ?>" style="padding:0px 15px 0px 15px !important;">
                            <div class="input-group">
                                <input class="form-control" id="myEvntsAtndSrchFor" type = "text" placeholder="Search For" value="<?php echo trim(str_replace("%",
                                " ", $srchFor)); ?>" onkeyup="enterKeyFuncMyEvntsAtnd(event, '', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>')">
                                <input id="myEvntsAtndPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                <label class="btn btn-primary btn-file input-group-addon" onclick="getMyEvntsAtnd('clear', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>')">
                                    <span class="glyphicon glyphicon-remove"></span>
                                </label>
                                <label class="btn btn-primary btn-file input-group-addon" onclick="getMyEvntsAtnd('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>')">
                                    <span class="glyphicon glyphicon-search"></span>
                                </label> 
                            </div>
                        </div>
                        <div class="<?php echo $colClassType2; ?>">
                            <div class="input-group">
                                <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                <select data-placeholder="Select..." class="form-control chosen-select" id="myEvntsAtndSrchIn">
                                    <?php
                                    $valslctdArry = array("");
                                    $srchInsArrys = array("Name/Number");

                                    for ($z = 0; $z < count($srchInsArrys); $z++) {
                                        if ($srchIn == $srchInsArrys[$z]) {
                                            $valslctdArry[$z] = "selected";
                                        }
                                        ?>
                                        <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                <?php } ?>
                                </select>
                                <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                <select data-placeholder="Select..." class="form-control chosen-select" id="myEvntsAtndDsplySze" style="min-width:70px !important;">                            
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
                        <div class="<?php echo $colClassType1; ?>">
                            <nav aria-label="Page navigation">
                                <ul class="pagination" style="margin: 0px !important;">
                                    <li>
                                        <a class="rhopagination" href="javascript:getMyEvntsAtnd('previous', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');" aria-label="Previous">
                                            <span aria-hidden="true">&laquo;</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="rhopagination" href="javascript:getMyEvntsAtnd('next', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');" aria-label="Next">
                                            <span aria-hidden="true">&raquo;</span>
                                        </a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                    <div class="row"> 
                        <div  class="col-md-12">
                            <table class="table table-striped table-bordered table-responsive" id="myEvntsAtndTable" cellspacing="0" width="100%" style="width:100%;">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Event No.</th>
                                        <th>Event Name / Description</th>
                                        <th style="text-align: center;">Present?</th>
                                        <th>From</th>
                                        <th>To</th>
                                        <th>Sponsor</th>
                                        <th>Invoice Number</th>
                                        <th>Amount Billed</th>
                                        <th>Amount Paid</th>
                                        <th>&nbsp;</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    while ($row = loc_db_fetch_array($result)) {
                                        $cntr += 1;
                                        ?>
                                        <tr id="myEvntsAtndRow_<?php echo $cntr; ?>">                                    
                                            <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                            <td class="lovtd"><?php echo $row[2]; ?></td>
                                            <td class="lovtd"><?php echo $row[3]; ?></td>

                                            <td class="lovtd" style="text-align:center;">
                                                <?php
                                                $isChkd = "";
                                                if ($row[11] == "1") {
                                                    $isChkd = "checked=\"true\"";
                                                }
                                                ?>
                                                <div class="form-group form-group-sm">
                                                    <div class="form-check" style="font-size: 12px !important;">
                                                        <label class="form-check-label">
                                                            <input type="checkbox" class="form-check-input" id="myEvntsAtndRow<?php echo $cntr; ?>_IsPresent" name="myEvntsAtndRow<?php echo $cntr; ?>_IsPresent" <?php echo $isChkd ?> disabled="true">
                                                        </label>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="lovtd"><?php echo $row[4]; ?></td>
                                            <td class="lovtd"><?php echo $row[5]; ?></td>
                                            <!--<td><?php echo ($row[11] == '1' ? "YES" : "NO"); ?></td>-->
                                            <td class="lovtd"><?php echo $row[12]; ?></td>
                                            <td class="lovtd"><?php echo $row[13]; ?></td>
                                            <?php
                                            $spnColor4 = "";
                                            $netAmnt = (float) $row[24] - (float) $row[25];
                                            if ($netAmnt <= 0) {
                                                $spnColor4 = "lime";
                                            } else {
                                                $spnColor4 = "#FF9191";
                                            }
                                            ?>
                                            <td class="lovtd" style="background-color:<?php echo $spnColor4; ?>;"><?php echo number_format($row[24], 2, '.',
                                                    ','); ?></td>
                                            <td class="lovtd" style="background-color:<?php echo $spnColor4; ?>;"><?php echo number_format($row[25], 2, '.',
                                                    ','); ?></td>
                                            <td class="lovtd">
                                                <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Details" onclick="" style="padding:2px !important;" style="padding:2px !important;">
                                                    <!--<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>-->
                                                    <img src="cmn_images/kghostview.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                </button>
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
                //Extra Attendance Information
                //1. Time Details
                //2. Points Earned
            }
        }
    }
}
?>