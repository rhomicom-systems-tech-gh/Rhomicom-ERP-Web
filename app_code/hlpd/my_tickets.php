<?php


$canAddRqsts = test_prmssns($dfltPrvldgs[6], $mdlNm);
$canEdtRqsts = test_prmssns($dfltPrvldgs[7], $mdlNm);
$canDelRqsts = test_prmssns($dfltPrvldgs[8], $mdlNm);

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
                                    <span style=\"text-decoration:none;\">My Request Tickets</span>
				</li>
                               </ul>
                              </div>";
                
                $qNotClosed = true;
                $qStrtDte = "01-Jan-1900 00:00:00";
                $qEndDte = "31-Dec-4000 23:59:59";
                if (isset($_POST['qNotClosed'])) {
                    $qNotClosed = cleanInputData($_POST['qNotClosed']) === "true" ? true : false;
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
                $total = get_AllTicketsTtl($srchFor, $srchIn, $orgID, $qStrtDte, $qEndDte, $qNotClosed, TRUE, FALSE);
                if ($pageNo > ceil($total / $lmtSze)) {
                    $pageNo = 1;
                } else if ($pageNo < 1) {
                    $pageNo = ceil($total / $lmtSze);
                }
                $curIdx = $pageNo - 1;
                $result = get_AllTickets($srchFor, $srchIn, $curIdx, $lmtSze, $orgID, $qStrtDte, $qEndDte, $qNotClosed, TRUE, FALSE);
                $cntr = 0;
                ?> 
                <form id='allRqstTcktsForm' action='' method='post' accept-charset='UTF-8'>
                    <div class="row " style="margin-bottom:0px;padding:0px 15px 0px 15px !important;">
                        <div class="col-lg-3" style="padding:0px 1px 0px 1px !important;">
                            <div class="input-group">
                                <input class="form-control" id="allRqstTcktsSrchFor" type = "text" placeholder="Search For" value="<?php
                                echo trim(str_replace("%", " ", $srchFor));
                                ?>" onkeyup="enterKeyFuncAllRqstTckts(event, '', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>')">
                                <input id="allRqstTcktsPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                <label class="btn btn-primary btn-file input-group-addon" onclick="getAllRqstTckts('clear', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>')">
                                    <span class="glyphicon glyphicon-remove"></span>
                                </label>
                                <label class="btn btn-primary btn-file input-group-addon" onclick="getAllRqstTckts('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');">
                                    <span class="glyphicon glyphicon-search"></span>
                                </label> 
                            </div>
                        </div>
                        <div class="col-lg-3" style="padding:0px 1px 0px 1px !important;">
                            <div class="input-group">
                                <span class="input-group-addon">In</span>
                                <select data-placeholder="Select..." class="form-control chosen-select" id="allRqstTcktsSrchIn">
                                    <?php
                                    $valslctdArry = array("", "", "");
                                    $srchInsArrys = array("Requestor", "Ticket Number", "Other Fields");
                                    for ($z = 0; $z < count($srchInsArrys); $z++) {
                                        if ($srchIn == $srchInsArrys[$z]) {
                                            $valslctdArry[$z] = "selected";
                                        }
                                        ?>
                                        <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                    <?php } ?>
                                </select>
                                <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                <select data-placeholder="Select..." class="form-control chosen-select" id="allRqstTcktsDsplySze" style="min-width:65px !important;">                            
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
                                    <input class="form-control" size="16" type="text" id="allRqstTcktsStrtDate" name="allRqstTcktsStrtDate" value="<?php
                                    echo substr($qStrtDte, 0, 11);
                                    ?>" placeholder="Start Date">
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                </div></div>
                            <div class="col-xs-6" style="padding:0px 1px 0px 0px !important;">
                                <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                    <input class="form-control" size="16" type="text"  id="allRqstTcktsEndDate" name="allRqstTcktsEndDate" value="<?php
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
                                        <a class="rhopagination" href="javascript:getAllRqstTckts('previous', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');" aria-label="Previous">
                                            <span aria-hidden="true">&laquo;</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="rhopagination" href="javascript:getAllRqstTckts('next', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');" aria-label="Next">
                                            <span aria-hidden="true">&raquo;</span>
                                        </a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                    <div class="row " style="margin-bottom:2px;padding:2px 15px 2px 15px !important;">
                        <div class="col-md-12" style="padding:0px 0px 0px 0px !important;border-top:1px solid #ddd;border-bottom:1px solid #ddd;">
                            <div class="col-md-3" style="padding:2px 1px 2px 1px !important;">
                                <div class="form-check" style="font-size: 12px !important;">
                                    <label class="form-check-label">
                                        <?php
                                        $notClosedChekd = "";
                                        if ($qNotClosed == true) {
                                            $notClosedChekd = "checked=\"true\"";
                                        }
                                        ?>
                                        <input type="checkbox" class="form-check-input" onclick="getAllRqstTckts('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');" id="allRqstTcktsNotClosed" name="allRqstTcktsNotClosed" <?php echo $notClosedChekd; ?>>
                                        Tickets not Closed
                                    </label>
                                </div> 
                            </div>   
                            <div class="col-md-9" style="padding:2px 1px 2px 1px !important;">
                                <?php if ($canAddRqsts) { ?>
                                    <button type="button" class="btn btn-default btn-sm" onclick="">
                                        <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                        Add New Ticket
                                    </button>
                                <?php } ?>
                                <?php if ($canEdtRqsts) { ?>
                                    <button type="button" class="btn btn-default btn-sm" onclick="">
                                        <img src="cmn_images/withdraw_rqst.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                        Withdraw Selected Tickets
                                    </button>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </form>
                <form id='allRqstTcktsHdrsForm' action='' method='post' accept-charset='UTF-8'>
                    <div class="row"> 
                        <div  class="col-md-12">
                            <table class="table table-striped table-bordered table-responsive" id="allRqstTcktsHdrsTable" cellspacing="0" width="100%" style="width:100%;">
                                <thead>
                                    <tr>
                                        <th>&nbsp;</th>
                                        <th>No.</th>		
                                        <th>Ticket Number</th>
                                        <th>Subject</th>
                                        <th>Requestor's Name</th>
                                        <th>Email</th>
                                        <th>Contact Nos.</th>
                                        <th>Category</th>
                                        <th style="text-align:center;">Status</th>
                                        <th style="text-align:center;">Is Closed?</th>
                                        <th>Assigned Group</th>
                                        <th>&nbsp;</th>
                                        <th>&nbsp;</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    while ($row = loc_db_fetch_array($result)) {
                                        /**/
                                        $cntr += 1;
                                        ?>
                                        <tr id="allRqstTcktsHdrsRow_<?php echo $cntr; ?>">
                                            <td class="lovtd">
                                                <input type="checkbox" name="allRqstTcktsHdrsRow<?php echo $cntr; ?>_CheckBox" value="<?php echo $row[0] . ";" . $row[1]; ?>">
                                                <input type="hidden" value="<?php echo $row[0]; ?>" id="allRqstTcktsHdrsRow<?php echo $cntr; ?>_TicketID">
                                            </td>                                    
                                            <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                            <td class="lovtd"><?php echo $row[1]; ?></td>
                                            <td class="lovtd"><?php echo $row[10]; ?></td>
                                            <td class="lovtd"><?php echo $row[5] . " - " . $row[4]; ?></td>
                                            <td class="lovtd"><?php echo $row[6]; ?></td>
                                            <td class="lovtd"><?php echo $row[7]; ?></td>
                                            <td class="lovtd"><?php echo $row[9]; ?></td>
                                            <td class="lovtd" style="text-align:center;"><?php echo $row[12]; ?></td>
                                            <td class="lovtd" style="text-align:center;">
                                                <?php
                                                $isChkd = "";
                                                if ($row[13] == "1") {
                                                    $isChkd = "checked=\"true\"";
                                                }
                                                ?>
                                                <div class="form-group form-group-sm">
                                                    <div class="form-check" style="font-size: 12px !important;">
                                                        <label class="form-check-label">
                                                            <input type="checkbox" class="form-check-input" id="allRqstTcktsHdrsRow<?php echo $cntr; ?>_IsClosed" name="allRqstTcktsHdrsRow<?php echo $cntr; ?>_IsClosed" <?php echo $isChkd ?> disabled="true">
                                                        </label>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="lovtd"><?php echo $row[15] . " - " . $row[17]; ?></td>
                                            <td class="lovtd">
                                                <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Details" onclick="" style="padding:2px !important;" style="padding:2px !important;">
                                                    <!--<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>-->
                                                    <img src="cmn_images/kghostview.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                </button>
                                            </td>
                                            <?php if ($canDelRqsts === true) { ?>
                                                <td class="lovtd">
                                                    <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="" data-toggle="tooltip" data-placement="bottom" title="Delete Ticket">
                                                        <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
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