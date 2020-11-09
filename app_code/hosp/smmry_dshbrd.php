<?php
$canAddRecs = test_prmssns($dfltPrvldgs[0], $mdlNm);
$canEdtRecs = test_prmssns($dfltPrvldgs[0], $mdlNm);
$canDelRecs = test_prmssns($dfltPrvldgs[0], $mdlNm);

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
                                    <span style=\"text-decoration:none;\">Summary Dashboard</span>
				</li>
                               </ul>
                              </div>";


                $canAddRcmddSrvsMain = true;//test_prmssns($dfltPrvldgs[16], $mdlNm);
                $canEdtRcmddSrvsMain = true;//test_prmssns($dfltPrvldgs[17], $mdlNm);
                $canDelRcmddSrvsMain = true;//test_prmssns($dfltPrvldgs[18], $mdlNm);

                $error = "";
                
                $cnsltnID = isset($_POST['cnsltnID']) ? cleanInputData($_POST['cnsltnID']) : -1;

                $srchFor = isset($_POST['searchfor']) ? cleanInputData($_POST['searchfor']) : '';
                $srchIn = isset($_POST['searchin']) ? cleanInputData($_POST['searchin']) : 'Both';
                $pageNo = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
                $lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 1000;
                $sortBy = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : "Date Added DESC";
                if (strpos($srchFor, "%") === FALSE) {
                    $srchFor = "%" . str_replace(" ", "%", $srchFor) . "%";
                    $srchFor = str_replace("%%", "%", $srchFor);
                }

                $total = getCreditRcmddSrvsMainsTblTtl($cnsltnID, $srchFor, $srchIn);
                if ($pageNo > ceil($total / $lmtSze)) {
                    $pageNo = 1;
                } else if ($pageNo < 1) {
                    $pageNo = ceil($total / $lmtSze);
                }

                $curIdx = $pageNo - 1;
                $result = getCreditRcmddSrvsMainsTbl($cnsltnID, $srchFor, $srchIn, $curIdx, $lmtSze);
                $cntr = 0;
                ?>
                <form id='allRcmddSrvsMainsForm' action='' method='post' accept-charset='UTF-8'>
                    <!--<fieldset class="basic_person_fs5">-->
                    <legend class="basic_person_lg1" style="color: #003245">RECOMMENDED SERVICES</legend>                
                    <input class="form-control" id="rcmdSrvsMainId" type = "hidden" placeholder="ROW ID" value=""/>                     
                    <div class="row rhoRowMargin" style="margin-bottom:10px;">
                            <div class="col-lg-4" style="padding:0px 1px 0px 15px !important;">     
                                <?php
                                if ($canAddRcmddSrvsMain === true) {
                                ?>   
                                <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="getOneRcmddSrvsMainForm(-1);">
                                    <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                    New Service
                                </button>
                                    <?php
                                }
                                ?>
                            </div>
                        <div class="col-lg-4" style="padding:0px 15px 0px 15px !important;">
                            <div class="input-group">
                                <input class="form-control" id="allRcmddSrvsMainsSrchFor" type = "text" placeholder="Search For" value="<?php echo trim(str_replace("%", " ", $srchFor)); ?>" onkeyup="enterKeyFuncAllRcmddSrvsMains(event, '', '#allmodules', 'grp=14&typ=1&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&mdl=Clinic/Hospital')">
                                <input id="allRcmddSrvsMainsPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                <label class="btn btn-primary btn-file input-group-addon" onclick="getAllRcmddSrvsMains('clear', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&mdl=Clinic/Hospital');">
                                    <span class="glyphicon glyphicon-remove"></span>
                                </label>
                                <label class="btn btn-primary btn-file input-group-addon" onclick="getAllRcmddSrvsMains('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&mdl=Clinic/Hospital');">
                                    <span class="glyphicon glyphicon-search"></span>
                                </label> 
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="input-group">
                                <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                <select data-placeholder="Select..." class="form-control chosen-select" id="allRcmddSrvsMainsSrchIn">
                                <?php
                                $valslctdArry = array("");
                                $srchInsArrys = array("Service Name");
                                for ($z = 0; $z < count($srchInsArrys); $z++) {
                                    if ($srchIn == $srchInsArrys[$z]) {
                                        $valslctdArry[$z] = "selected";
                                    }
                                    ?>
                                        <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                    <?php } ?>
                                </select>
                                <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                <select data-placeholder="Select..." class="form-control chosen-select" id="allRcmddSrvsMainsDsplySze" style="min-width:70px !important;">                            
                                    <?php
                                    $valslctdArry = array("");
                                    $dsplySzeArry = array(1000);
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
                    </div>               
                    <div class="row" style="padding:0px 15px 0px 15px !important"> 
                        <div class="col-md-4" style="padding:0px 1px 0px 1px !important">
                            <fieldset class="basic_person_fs">                                        
                                <table class="table table-striped table-bordered table-responsive" id="allRcmddSrvsMainsTable" cellspacing="0" width="100%" style="width:100%;">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Service Name</th>
                                            <th>Request Date</th>                                   
                                            <?php if ($canDelRcmddSrvsMain === true) { ?>
                                                <th>&nbsp;</th>
                                            <?php } ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $sbmtdRcmddSrvsMainID = -1;
                                        while ($row = loc_db_fetch_array($result)) {
                                            if ($sbmtdRcmddSrvsMainID <= 0 && $cntr <= 0) {
                                                $sbmtdRcmddSrvsMainID = $row[0];
                                            }

                                            $cntr += 1;
                                            ?>
                                            <tr id="allRcmddSrvsMainsRow_<?php echo $cntr; ?>" class="hand_cursor">                                    
                                                <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                                <td class="lovtd"><?php echo $row[2]; ?>
                                                    <input type="hidden" class="form-control" aria-label="..." id="allRcmddSrvsMainsRow<?php echo $cntr; ?>_RcmddSrvsMainID" value="<?php echo $row[0]; ?>">
                                                </td>
                                                <td class="lovtd">
                                                <?php echo $mainSvsOffrd; ?>
                                                </td>
                                                    <?php if ($canDelRcmddSrvsMain === true) { ?>
                                                    <td class="lovtd">
                                                        <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="deleteRcmddSrvsMain(<?php echo $row[0]; ?>)" data-toggle="tooltip" data-placement="bottom" title="Delete Service">
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
                            </fieldset>
                        </div>                        
                        <div  class="col-md-8" style="padding:0px 1px 0px 1px !important">
                            <fieldset class="basic_person_fs" style="padding-top:5px !important;">
                                <div class="" id="allRcmddSrvsMainsDetailInfo">
                                    <fieldset class="basic_person_fs" style="padding:10px 3px 0px 3px !important;border:none !important;border-bottom:1px solid #ddd !important;border-radius: 0px !important;">                                              
                                        <div class="row" id="allRcmddSrvsMainsHdrInfo" style="padding:0px 15px 0px 15px !important">


                                        </div>     
                                    </fieldset>
                                </div>
                            </fieldset>
                        </div>
                    </div>
                </form>
                <?php
            }
        }
    }
}
?>