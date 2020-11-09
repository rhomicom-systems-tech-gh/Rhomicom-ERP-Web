<?php
$canAddScPlcy = test_prmssns($dfltPrvldgs[12], $mdlNm);
$canEdtScPlcy = test_prmssns($dfltPrvldgs[13], $mdlNm);

$pageNo = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
$lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 10;
$sortBy = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : "";

if (array_key_exists('lgn_num', get_defined_vars())) {
    if ($lgn_num > 0 && $canview === true) {
        if ($qstr == "DELETE") {
            if ($actyp == 1) {
                
            }
        } else if ($qstr == "UPDATE") {
            if ($actyp == 1) {
                //var_dump($_POST);
                $scPlcysPlcyNm = isset($_POST['scPlcysPlcyNm']) ? cleanInputData($_POST['scPlcysPlcyNm']) : "";
                $scPlcysPlcyID = isset($_POST['scPlcysPlcyID']) ? (int) cleanInputData($_POST['scPlcysPlcyID']) : -1;
                $scPlcysIsDflt = isset($_POST['scPlcysIsDflt']) ? cleanInputData($_POST['scPlcysIsDflt']) : "NO";
                $scPlcysIsDflt_B = ($scPlcysIsDflt == "YES") ? "TRUE" : "FALSE";
                $scPlcysPwdExpryDys = isset($_POST['scPlcysPwdExpryDys']) ? (int) cleanInputData($_POST['scPlcysPwdExpryDys']) : 0;
                $scPlcysNoOfRecs = isset($_POST['scPlcysNoOfRecs']) ? (int) cleanInputData($_POST['scPlcysNoOfRecs']) : 0;
                $scPlcysPwdMinLen = isset($_POST['scPlcysPwdMinLen']) ? (int) cleanInputData($_POST['scPlcysPwdMinLen']) : 0;
                $scPlcysPwdMaxLen = isset($_POST['scPlcysPwdMaxLen']) ? (int) cleanInputData($_POST['scPlcysPwdMaxLen']) : 0;
                $scPlcysPwdOldAlwd = isset($_POST['scPlcysPwdOldAlwd']) ? (int) cleanInputData($_POST['scPlcysPwdOldAlwd']) : 0;
                $scPlcysPwdUnmAlwd = isset($_POST['scPlcysPwdUnmAlwd']) ? cleanInputData($_POST['scPlcysPwdUnmAlwd']) : "NO";
                $scPlcysPwdUnmAlwd_B = ($scPlcysPwdUnmAlwd == "YES") ? "TRUE" : "FALSE";
                $scPlcysPwdRptngChars = isset($_POST['scPlcysPwdRptngChars']) ? cleanInputData($_POST['scPlcysPwdRptngChars']) : "NO";
                $scPlcysPwdRptngChars_B = ($scPlcysPwdRptngChars == "YES") ? "TRUE" : "FALSE";
                $scPlcysAlwdFailedLgs = isset($_POST['scPlcysAlwdFailedLgs']) ? (int) cleanInputData($_POST['scPlcysAlwdFailedLgs']) : 0;
                $scPlcysAutoUnlkTme = isset($_POST['scPlcysAutoUnlkTme']) ? (int) cleanInputData($_POST['scPlcysAutoUnlkTme']) : 0;
                $scPlcysChckBlkLtrs = isset($_POST['scPlcysChckBlkLtrs']) ? cleanInputData($_POST['scPlcysChckBlkLtrs']) : "NO";
                $scPlcysChckBlkLtrs_B = ($scPlcysChckBlkLtrs == "YES") ? "TRUE" : "FALSE";
                $scPlcysChkSmllLtrs = isset($_POST['scPlcysChkSmllLtrs']) ? cleanInputData($_POST['scPlcysChkSmllLtrs']) : "NO";
                $scPlcysChkSmllLtrs_B = ($scPlcysChkSmllLtrs == "YES") ? "TRUE" : "FALSE";
                $scPlcysChkDgts = isset($_POST['scPlcysChkDgts']) ? cleanInputData($_POST['scPlcysChkDgts']) : "NO";
                $scPlcysChkDgts_B = ($scPlcysChkDgts == "YES") ? "TRUE" : "FALSE";
                $scPlcysChkWild = isset($_POST['scPlcysChkWild']) ? cleanInputData($_POST['scPlcysChkWild']) : "NO";
                $scPlcysChkWild_B = ($scPlcysChkDgts == "YES") ? "TRUE" : "FALSE";
                $scPlcysCombinations = isset($_POST['scPlcysCombinations']) ? cleanInputData($_POST['scPlcysCombinations']) : "NONE";
                $scPlcysSesnTmeOut = isset($_POST['scPlcysSesnTmeOut']) ? (int) cleanInputData($_POST['scPlcysSesnTmeOut']) : 0;
                $slctdAdtTbls = isset($_POST['slctdAdtTbls']) ? cleanInputData($_POST['slctdAdtTbls']) : "";

                $oldID = getPlcyID($scPlcysPlcyNm);
                if (doesChkdRqrmntsMeetCmbntn($scPlcysChckBlkLtrs_B, $scPlcysChkSmllLtrs_B, $scPlcysChkWild_B, $scPlcysChkDgts_B, $scPlcysCombinations) == false) {
                    echo "The selected requirement combination '" . $scPlcysCombinations . "' does not match the checked boxes above it!";
                    exit();
                }
                if ($scPlcysPlcyNm != "" && ($oldID <= 0 || $oldID == $scPlcysPlcyID)) {
                    if ($scPlcysIsDflt == "YES") {
                        undefaultAllPlcys();
                    }
                    if ($scPlcysPlcyID <= 0) {
                        createPolicy($scPlcysPlcyNm, $scPlcysAlwdFailedLgs
                                , $scPlcysPwdExpryDys, $scPlcysAutoUnlkTme, $scPlcysChckBlkLtrs_B, $scPlcysChkSmllLtrs_B, $scPlcysChkDgts_B, $scPlcysChkWild_B
                                , $scPlcysCombinations, $scPlcysIsDflt_B, $scPlcysPwdOldAlwd, $scPlcysPwdMinLen, $scPlcysPwdMaxLen
                                , $scPlcysNoOfRecs, $scPlcysPwdRptngChars_B, $scPlcysPwdUnmAlwd_B, $scPlcysSesnTmeOut);
                        $scPlcysPlcyID = getPlcyID($scPlcysPlcyNm);
                    } else {
                        updatePlcy($scPlcysPlcyID, $scPlcysPlcyNm, $scPlcysAlwdFailedLgs
                                , $scPlcysPwdExpryDys, $scPlcysAutoUnlkTme, $scPlcysChckBlkLtrs_B, $scPlcysChkSmllLtrs_B, $scPlcysChkDgts_B, $scPlcysChkWild_B
                                , $scPlcysCombinations, $scPlcysIsDflt_B, $scPlcysPwdOldAlwd, $scPlcysPwdMinLen, $scPlcysPwdMaxLen
                                , $scPlcysNoOfRecs, $scPlcysPwdRptngChars_B, $scPlcysPwdUnmAlwd_B, $scPlcysSesnTmeOut);
                    }
                    $affctdRws = 0;
                    if (trim($slctdAdtTbls, "|~") != "") {
                        //Save Question Answers
                        $variousRows = explode("|", trim($slctdAdtTbls, "|"));
                        for ($z = 0; $z < count($variousRows); $z++) {
                            $crntRow = explode("~", $variousRows[$z]);
                            if (count($crntRow) == 4) {
                                $enblTrckng = (cleanInputData1($crntRow[0]));
                                $enblTrckng_B = ($enblTrckng == "YES") ? "TRUE" : "FALSE";
                                $actnsToTrck = (cleanInputData1($crntRow[1]));
                                $inptMdlID = (int) (cleanInputData1($crntRow[2]));
                                $inptRowID = (int) (cleanInputData1($crntRow[3]));
                                if (hasPlcyEvrHdThsMdl($scPlcysPlcyID, $inptMdlID) == true) {
                                    $affctdRws += updateActnsToTrack($scPlcysPlcyID, $inptMdlID, $enblTrckng_B, $actnsToTrck);
                                } else {
                                    $affctdRws += asgnMdlToPlcy($scPlcysPlcyID, $inptMdlID, $enblTrckng_B, $actnsToTrck);
                                }
                            }
                        }
                    }
                    echo ("<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>Security Policy Successfully Saved!<br/>" . $affctdRws . "Audit Trial Information Saved!");
                    exit();
                } else {
                    echo ("<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>Either the New Policy Name is in Use <br/>or Data Supplied is Incomplete!</span>");
                    exit();
                }
            } else if ($actyp == 2) {
                
            }
        } else {
            if ($vwtyp == 0) {
                $pkID = isset($_POST['sbmtdPlcyID']) ? $_POST['sbmtdPlcyID'] : -1;
                echo $cntent . "<li>
                                    <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                    <span style=\"text-decoration:none;\">Security Policies</span>
				</li>
                               </ul>
                              </div>";
                $total = get_SecPlcysTblrTtl($srchFor, $srchIn);
                if ($pageNo > ceil($total / $lmtSze)) {
                    $pageNo = 1;
                } else if ($pageNo < 1) {
                    $pageNo = ceil($total / $lmtSze);
                }

                $curIdx = $pageNo - 1;
                $result = get_SecPlcysTblr($srchFor, $srchIn, $curIdx, $lmtSze);
                $cntr = 0;
                $colClassType1 = "col-lg-2";
                $colClassType2 = "col-lg-3";
                $colClassType3 = "col-lg-4";
                ?>
                <form id='allSecPlcysForm' action='' method='post' accept-charset='UTF-8'>
                    <div class="row rhoRowMargin">
                <?php
                if ($canAddScPlcy === true) {
                    ?> 
                            <div class="<?php echo $colClassType2; ?>" style="padding:0px 1px 0px 1px !important;"> 
                                <div class="col-md-6">
                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="getOneSecPlcyForm(-1, 2);" style="width:100% !important;">
                                        <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                        New Policy
                                    </button>
                                </div>
                                <div class="col-md-6">
                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="saveSecPlcyForm();" style="width:100% !important;">
                                        <img src="cmn_images/FloppyDisk.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                        Save
                                    </button>
                                </div>
                            </div>
                    <?php
                } else {
                    $colClassType1 = "col-lg-2";
                    $colClassType2 = "col-lg-5";
                }
                ?>
                        <div class="<?php echo $colClassType2; ?>" style="padding:0px 15px 0px 15px !important;">
                            <div class="input-group">
                                <input class="form-control" id="allSecPlcysSrchFor" type = "text" placeholder="Search For" value="<?php echo trim(str_replace("%", " ", $srchFor)); ?>" onkeyup="enterKeyFuncAllSecPlcys(event, '', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>')">
                                <input id="allSecPlcysPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                <label class="btn btn-primary btn-file input-group-addon" onclick="getAllSecPlcys('clear', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>')">
                                    <span class="glyphicon glyphicon-remove"></span>
                                </label>
                                <label class="btn btn-primary btn-file input-group-addon" onclick="getAllSecPlcys('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>')">
                                    <span class="glyphicon glyphicon-search"></span>
                                </label> 
                            </div>
                        </div>
                        <div class="<?php echo $colClassType3; ?>">
                            <div class="input-group">
                                <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                <select data-placeholder="Select..." class="form-control chosen-select" id="allSecPlcysSrchIn">
                <?php
                $valslctdArry = array("");
                $srchInsArrys = array("Policy Name");

                for ($z = 0; $z < count($srchInsArrys); $z++) {
                    if ($srchIn == $srchInsArrys[$z]) {
                        $valslctdArry[$z] = "selected";
                    }
                    ?>
                                        <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                    <?php } ?>
                                </select>
                                <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                <select data-placeholder="Select..." class="form-control chosen-select" id="allSecPlcysDsplySze" style="min-width:70px !important;">                            
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
                                        <a class="rhopagination" href="javascript:getAllSecPlcys('previous', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');" aria-label="Previous">
                                            <span aria-hidden="true">&laquo;</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="rhopagination" href="javascript:getAllSecPlcys('next', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');" aria-label="Next">
                                            <span aria-hidden="true">&raquo;</span>
                                        </a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                    <div class="row"  style="padding:1px 15px 1px 15px !important;"><hr style="margin:1px 0px 3px 0px;"></div>
                    <div class="row"> 
                        <div  class="col-md-3">
                            <fieldset class="basic_person_fs">                                        
                                <table class="table table-striped table-bordered table-responsive" id="allSecPlcysTable" cellspacing="0" width="100%" style="width:100%;">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Policy Name</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                <?php
                while ($row = loc_db_fetch_array($result)) {
                    if ($pkID <= 0 && $cntr <= 0) {
                        $pkID = $row[0];
                    }
                    $cntr += 1;
                    ?>
                                            <tr id="allSecPlcysRow_<?php echo $cntr; ?>" class="hand_cursor">                                    
                                                <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                                <td class="lovtd"><?php echo $row[1]; ?><input type="hidden" class="form-control" aria-label="..." id="allSecPlcysRow<?php echo $cntr; ?>_PlcyID" value="<?php echo $row[0]; ?>"></td>
                                            </tr>
                                            <?php
                                        }
                                        ?>
                                    </tbody>
                                </table>                        
                            </fieldset>
                        </div>                        
                        <div  class="col-md-9" style="padding:0px 15px 0px 1px !important">
                            <fieldset class="basic_person_fs" style="padding-top:10px !important;">
                                <legend class = "basic_person_lg">Policy Details</legend>
                                <div class="container-fluid" id="secPlcsDetailInfo">
                <?php
                if ($pkID > 0) {
                    $result1 = get_SecPlcysDet($pkID);
                    while ($row1 = loc_db_fetch_array($result1)) {
                        ?>
                                            <div class="row">
                                                <div  class="col-md-6" style="padding:0px 3px 0px 3px !important;">
                                                    <fieldset class="basic_person_fs" style="padding-top:10px !important;"> 
                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                            <label for="scPlcysPlcyNm" class="control-label col-lg-4">Policy Name:</label>
                                                            <div  class="col-lg-8">
                        <?php if ($canEdtScPlcy === true) { ?>
                                                                    <input type="text" class="form-control" aria-label="..." id="scPlcysPlcyNm" name="scPlcysPlcyNm" value="<?php echo $row1[1]; ?>" style="width:100%;">
                                                                    <input type="hidden" class="form-control" aria-label="..." id="scPlcysPlcyID" name="scPlcysPlcyID" value="<?php echo $row1[0]; ?>">
                        <?php } else {
                            ?>
                                                                    <span><?php echo $row1[1]; ?></span>
                            <?php
                        }
                        ?>
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                            <label for="scPlcysIsDflt" class="control-label col-lg-6">Is Default?:</label>
                                                            <div  class="col-lg-6">
                        <?php
                        $chkdYes = "";
                        $chkdNo = "checked=\"\"";
                        if ($row1[10] == "TRUE") {
                            $chkdNo = "";
                            $chkdYes = "checked=\"\"";
                        }
                        ?>
                                                                <?php if ($canEdtScPlcy === true) { ?>
                                                                    <label class="radio-inline"><input type="radio" name="scPlcysIsDflt" value="YES" <?php echo $chkdYes; ?>>YES</label>
                                                                    <label class="radio-inline"><input type="radio" name="scPlcysIsDflt" value="NO" <?php echo $chkdNo; ?>>NO</label>
                                                                <?php } else {
                                                                    ?>
                                                                    <span><?php echo ($row1[10] == "TRUE" ? "YES" : "NO"); ?></span>
                            <?php
                        }
                        ?>
                                                            </div>
                                                        </div>
                                                    </fieldset>
                                                </div>
                                                <div  class="col-md-6" style="padding:0px 3px 0px 3px !important;">
                                                    <fieldset class="basic_person_fs" style="padding-top:10px !important;">
                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                            <label for="scPlcysPwdExpryDys" class="control-label col-lg-8">Password Expiry Days:</label>
                                                            <div  class="col-lg-4">
                        <?php if ($canEdtScPlcy === true) { ?>
                                                                    <input type="number" min="1" max="9999" class="form-control" aria-label="..." id="scPlcysPwdExpryDys" name="scPlcysPwdExpryDys" value="<?php echo $row1[3]; ?>" style="width:100%;">
                        <?php } else {
                            ?>
                                                                    <span><?php echo $row1[3]; ?></span>
                                                                    <?php
                                                                }
                                                                ?>
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                            <label for="scPlcysNoOfRecs" class="control-label col-lg-8">No. of Displayed Records:</label>
                                                            <div  class="col-lg-4">
                        <?php if ($canEdtScPlcy === true) { ?>
                                                                    <input type="number" min="1" max="9999" class="form-control" aria-label="..." id="scPlcysNoOfRecs" name="scPlcysNoOfRecs" value="<?php echo $row1[14]; ?>" style="width:100%;">
                        <?php } else {
                            ?>
                                                                    <span><?php echo $row1[14]; ?></span>
                                                                    <?php
                                                                }
                                                                ?>
                                                            </div>
                                                        </div>
                                                    </fieldset>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div  class="col-md-6" style="padding:0px 3px 0px 3px !important;">
                                                    <fieldset class="basic_person_fs" style="padding-top:10px !important;">
                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                            <label for="scPlcysPwdMinLen" class="control-label col-lg-8">Minimum Length of Passwords:</label>
                                                            <div  class="col-lg-4">
                        <?php if ($canEdtScPlcy === true) { ?>
                                                                    <input type="number" min="1" max="9999" class="form-control" aria-label="..." id="scPlcysPwdMinLen" name="scPlcysPwdMinLen" value="<?php echo $row1[12]; ?>" style="width:100%;">
                        <?php } else {
                            ?>
                                                                    <span><?php echo $row1[12]; ?></span>
                                                                    <?php
                                                                }
                                                                ?>
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                            <label for="scPlcysPwdMaxLen" class="control-label col-lg-8">Maximum Length of Passwords:</label>
                                                            <div  class="col-lg-4">
                        <?php if ($canEdtScPlcy === true) { ?>
                                                                    <input type="number" min="1" max="9999" class="form-control" aria-label="..." id="scPlcysPwdMaxLen" name="scPlcysPwdMaxLen" value="<?php echo $row1[13]; ?>" style="width:100%;">
                        <?php } else {
                            ?>
                                                                    <span><?php echo $row1[13]; ?></span>
                                                                    <?php
                                                                }
                                                                ?>
                                                            </div>
                                                        </div>

                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                            <label for="scPlcysPwdOldAlwd" class="control-label col-lg-8">Blocked Old Passwords:</label>
                                                            <div  class="col-lg-4">
                        <?php if ($canEdtScPlcy === true) { ?>
                                                                    <input type="number" min="0" max="9999" class="form-control" aria-label="..." id="scPlcysPwdOldAlwd" name="scPlcysPwdOldAlwd" value="<?php echo $row1[11]; ?>" style="width:100%;">
                        <?php } else {
                            ?>
                                                                    <span><?php echo $row1[11]; ?></span>
                                                                    <?php
                                                                }
                                                                ?>
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                            <label for="scPlcysPwdUnmAlwd" class="control-label col-lg-6">User Names in Passwords?:</label>
                                                            <div  class="col-lg-6">
                        <?php
                        $chkdYes = "";
                        $chkdNo = "checked=\"\"";
                        if ($row1[16] == "TRUE") {
                            $chkdNo = "";
                            $chkdYes = "checked=\"\"";
                        }
                        ?>
                                                                <?php if ($canEdtScPlcy === true) { ?>
                                                                    <label class="radio-inline"><input type="radio" name="scPlcysPwdUnmAlwd" value="YES" <?php echo $chkdYes; ?>>YES</label>
                                                                    <label class="radio-inline"><input type="radio" name="scPlcysPwdUnmAlwd" value="NO" <?php echo $chkdNo; ?>>NO</label>
                                                                <?php } else {
                                                                    ?>
                                                                    <span><?php echo $row1[16]; ?></span>
                            <?php
                        }
                        ?>
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                            <label for="scPlcysPwdRptngChars" class="control-label col-lg-6">Same sequential characters in Pswds?:</label>
                                                            <div  class="col-lg-6">
                        <?php
                        $chkdYes = "";
                        $chkdNo = "checked=\"\"";
                        if ($row1[15] == "TRUE") {
                            $chkdNo = "";
                            $chkdYes = "checked=\"\"";
                        }
                        ?>
                                                                <?php if ($canEdtScPlcy === true) { ?>
                                                                    <label class="radio-inline"><input type="radio" name="scPlcysPwdRptngChars" value="YES" <?php echo $chkdYes; ?>>YES</label>
                                                                    <label class="radio-inline"><input type="radio" name="scPlcysPwdRptngChars" value="NO" <?php echo $chkdNo; ?>>NO</label>
                                                                <?php } else {
                                                                    ?>
                                                                    <span><?php echo $row1[15]; ?></span>
                            <?php
                        }
                        ?>
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                            <label for="scPlcysAlwdFailedLgs" class="control-label col-lg-8">Max. Failed Login Attempts:</label>
                                                            <div  class="col-lg-4">
                        <?php if ($canEdtScPlcy === true) { ?>
                                                                    <input type="number" min="1" max="9999" class="form-control" aria-label="..." id="scPlcysAlwdFailedLgs" name="scPlcysAlwdFailedLgs" value="<?php echo $row1[2]; ?>" style="width:100%;">
                        <?php } else {
                            ?>
                                                                    <span><?php echo $row1[2]; ?></span>
                                                                    <?php
                                                                }
                                                                ?>
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                            <label for="scPlcysAutoUnlkTme" class="control-label col-md-8">Time (mins) to unlock a User:</label>
                                                            <div  class="col-lg-4">
                        <?php if ($canEdtScPlcy === true) { ?>
                                                                    <input type="number" min="1" max="9999" class="form-control" aria-label="..." id="scPlcysAutoUnlkTme" name="scPlcysAutoUnlkTme" value="<?php echo $row1[4]; ?>" style="width:100%;">
                        <?php } else {
                            ?>
                                                                    <span><?php echo $row1[4]; ?></span>
                                                                    <?php
                                                                }
                                                                ?>
                                                            </div>
                                                        </div>
                                                    </fieldset>
                                                </div>
                                                <div  class="col-md-6" style="padding:0px 3px 0px 3px !important;">
                                                    <fieldset class="basic_person_fs" style="padding-top:15px !important;">
                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                            <label for="scPlcysChckBlkLtrs" class="control-label col-lg-6">Require Block Letters in Pswds?:</label>
                                                            <div  class="col-lg-6">
                        <?php
                        $chkdYes = "";
                        $chkdNo = "checked=\"\"";
                        if ($row1[5] == "TRUE") {
                            $chkdNo = "";
                            $chkdYes = "checked=\"\"";
                        }
                        ?>
                                                                <?php if ($canEdtScPlcy === true) { ?>
                                                                    <label class="radio-inline"><input type="radio" name="scPlcysChckBlkLtrs" value="YES" <?php echo $chkdYes; ?>>YES</label>
                                                                    <label class="radio-inline"><input type="radio" name="scPlcysChckBlkLtrs" value="NO" <?php echo $chkdNo; ?>>NO</label>
                                                                <?php } else {
                                                                    ?>
                                                                    <span><?php echo $row1[5]; ?></span>
                            <?php
                        }
                        ?>
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                            <label for="scPlcysChkSmllLtrs" class="control-label col-lg-6">Require Small Letters in Pswds?:</label>
                                                            <div  class="col-lg-6">
                        <?php
                        $chkdYes = "";
                        $chkdNo = "checked=\"\"";
                        if ($row1[6] == "TRUE") {
                            $chkdNo = "";
                            $chkdYes = "checked=\"\"";
                        }
                        ?>
                                                                <?php if ($canEdtScPlcy === true) { ?>
                                                                    <label class="radio-inline"><input type="radio" name="scPlcysChkSmllLtrs" value="YES" <?php echo $chkdYes; ?>>YES</label>
                                                                    <label class="radio-inline"><input type="radio" name="scPlcysChkSmllLtrs" value="NO" <?php echo $chkdNo; ?>>NO</label>
                                                                <?php } else {
                                                                    ?>
                                                                    <span><?php echo $row1[6]; ?></span>
                            <?php
                        }
                        ?>
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                            <label for="scPlcysChkDgts" class="control-label col-lg-6">Require Digits in Pswds?:</label>
                                                            <div  class="col-lg-6">
                        <?php
                        $chkdYes = "";
                        $chkdNo = "checked=\"\"";
                        if ($row1[7] == "TRUE") {
                            $chkdNo = "";
                            $chkdYes = "checked=\"\"";
                        }
                        ?>
                                                                <?php if ($canEdtScPlcy === true) { ?>
                                                                    <label class="radio-inline"><input type="radio" name="scPlcysChkDgts" value="YES" <?php echo $chkdYes; ?>>YES</label>
                                                                    <label class="radio-inline"><input type="radio" name="scPlcysChkDgts" value="NO" <?php echo $chkdNo; ?>>NO</label>
                                                                <?php } else {
                                                                    ?>
                                                                    <span><?php echo $row1[7]; ?></span>
                            <?php
                        }
                        ?>
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                            <label for="scPlcysChkWild" class="control-label col-lg-6">Require Wild Characters in Pswds?:</label>
                                                            <div  class="col-lg-6">
                        <?php
                        $chkdYes = "";
                        $chkdNo = "checked=\"\"";
                        if ($row1[8] == "TRUE") {
                            $chkdNo = "";
                            $chkdYes = "checked=\"\"";
                        }
                        ?>
                                                                <?php if ($canEdtScPlcy === true) { ?>
                                                                    <label class="radio-inline"><input type="radio" name="scPlcysChkWild" value="YES" <?php echo $chkdYes; ?>>YES</label>
                                                                    <label class="radio-inline"><input type="radio" name="scPlcysChkWild" value="NO" <?php echo $chkdNo; ?>>NO</label>
                                                                <?php } else {
                                                                    ?>
                                                                    <span><?php echo $row1[8]; ?></span>
                            <?php
                        }
                        ?>
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                            <label for="scPlcysCombinations" class="control-label col-lg-8">Combinations to Insist?:</label>
                                                            <div class="col-lg-4">
                        <?php if ($canEdtScPlcy === true) { ?>
                                                                    <select data-placeholder="Select..." class="form-control chosen-select" id="scPlcysCombinations" name="scPlcysCombinations">
                            <?php
                            $valslctdArry = array("", "", "", "", "");
                            $srchInsArrys = array("NONE", "ALL 4", "ANY 3", "ANY 2", "ANY 1");
                            for ($z = 0; $z < count($srchInsArrys); $z++) {
                                if ($row1[9] == $srchInsArrys[$z]) {
                                    $valslctdArry[$z] = "selected";
                                }
                                ?>
                                                                            <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                                                        <?php } ?>
                                                                    </select>
                                                                    <?php } else {
                                                                        ?>
                                                                    <span><?php echo $row1[9]; ?></span>
                                                                        <?php
                                                                    }
                                                                    ?>
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                            <label for="scPlcysSesnTmeOut" class="control-label col-md-8">Session Timeout (Seconds):</label>
                                                            <div  class="col-md-4">
                        <?php if ($canEdtScPlcy === true) { ?>
                                                                    <input type="number" min="1" max="9999" class="form-control" aria-label="..." id="scPlcysSesnTmeOut" name="scPlcysSesnTmeOut" value="<?php echo $row1[17]; ?>" style="width:100%;">
                        <?php } else {
                            ?>
                                                                    <span><?php echo $row1[17]; ?></span>
                                                                    <?php
                                                                }
                                                                ?>
                                                            </div>
                                                        </div>                                                
                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;"><br/></div>
                                                    </fieldset>
                                                </div>                                    
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                    <fieldset class="basic_person_fs">                                       
                                                        <table class="table table-striped table-bordered table-responsive" id="secPlcyAdtTblsTable" cellspacing="0" width="100%" style="width:100%;min-width: 700px;">
                                                            <thead>
                                                                <tr>
                                                                    <th>No.</th>
                                                                    <th>Module Name</th>
                                                                    <th>Audit Trail Table Name</th>
                                                                    <th>Enable Tracking</th>
                                                                    <th>Actions to Track</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                        <?php
                        $result2 = get_Plcy_Mdls($pkID);
                        $cntr = 0;
                        while ($row2 = loc_db_fetch_array($result2)) {
                            $cntr += 1;
                            ?>
                                                                    <tr id="secPlcyAdtTblsRow_<?php echo $cntr; ?>" class="hand_cursor">                                    
                                                                        <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                                                        <td class="lovtd"><?php echo $row2[1]; ?></td>
                                                                        <td class="lovtd"><?php echo $row2[2]; ?></td>
                                                                        <td class="lovtd">
                            <?php if ($canEdtScPlcy === true) { ?>
                                                                                <select data-placeholder="Select..." class="form-control chosen-select" id="secPlcyAdtTblsRow<?php echo $cntr; ?>_EnblTrckng" name="secPlcyAdtTblsRow<?php echo $cntr; ?>_EnblTrckng">
                                <?php
                                $valslctdArry = array("", "");
                                $srchInsArrys = array("YES", "NO");
                                for ($z = 0; $z < count($srchInsArrys); $z++) {
                                    if (($row2[3] == "TRUE" ? "YES" : "NO") == $srchInsArrys[$z]) {
                                        $valslctdArry[$z] = "selected";
                                    }
                                    ?>
                                                                                        <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                                                                    <?php } ?>
                                                                                </select>
                                                                                <?php } else {
                                                                                    ?>
                                                                                <span><?php echo $row2[3]; ?></span>
                                                                                    <?php
                                                                                }
                                                                                ?>
                                                                        </td>
                                                                        <td class="lovtd">
                                                                            <?php if ($canEdtScPlcy === true) { ?>
                                                                                <div class="input-group">
                                                                                    <input type="text" class="form-control" aria-label="..." id="secPlcyAdtTblsRow<?php echo $cntr; ?>_ActnsToTrck" name="secPlcyAdtTblsRow<?php echo $cntr; ?>_ActnsToTrck" value="<?php echo $row2[4]; ?>">
                                                                                    <input type="hidden" class="form-control" aria-label="..." id="secPlcyAdtTblsRow<?php echo $cntr; ?>_MdlID" name="secPlcyAdtTblsRow<?php echo $cntr; ?>_MdlID" value="<?php echo $row2[0]; ?>">
                                                                                    <input type="hidden" class="form-control" aria-label="..." id="secPlcyAdtTblsRow<?php echo $cntr; ?>_DfltRwID" name="secPlcyAdtTblsRow<?php echo $cntr; ?>_DfltRwID" value="<?php echo $row2[5]; ?>">                                                                                
                                                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Audit Trail Trackable Actions', '', '', '', 'check', true, '<?php echo $row2[4]; ?>', 'secPlcyAdtTblsRow<?php echo $cntr; ?>_ActnsToTrck', '', 'clear', 1, '');">
                                                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                                                    </label>
                                                                                </div><?php } else {
                                                ?>
                                                                                <span><?php echo $row2[4]; ?></span>
                                <?php
                            }
                            ?>
                                                                        </td>
                                                                    </tr>
                                                                            <?php
                                                                        }
                                                                        ?>
                                                            </tbody>
                                                        </table>
                                                    </fieldset>
                                                </div>
                                            </div>
                        <?php
                    }
                } else {
                    ?>
                                        <span>No Results Found</span>

                                        <?php
                                    }
                                    ?>
                                </div>
                            </fieldset>
                        </div>
                    </div>
                </form>
                <?php
            } else if ($vwtyp == 1) {
                $pkID = isset($_POST['sbmtdPlcyID']) ? $_POST['sbmtdPlcyID'] : -1;
                ?>
                <?php
                if ($pkID > 0) {
                    $result1 = get_SecPlcysDet($pkID);
                    while ($row1 = loc_db_fetch_array($result1)) {
                        ?>
                        <div class="row">
                            <div  class="col-md-6" style="padding:0px 3px 0px 3px !important;">
                                <fieldset class="basic_person_fs" style="padding-top:10px !important;"> 
                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                        <label for="scPlcysPlcyNm" class="control-label col-lg-4">Policy Name:</label>
                                        <div  class="col-lg-8">
                        <?php if ($canEdtScPlcy === true) { ?>
                                                <input type="text" class="form-control" aria-label="..." id="scPlcysPlcyNm" name="scPlcysPlcyNm" value="<?php echo $row1[1]; ?>" style="width:100%;">
                                                <input type="hidden" class="form-control" aria-label="..." id="scPlcysPlcyID" name="scPlcysPlcyID" value="<?php echo $row1[0]; ?>">
                        <?php } else {
                            ?>
                                                <span><?php echo $row1[1]; ?></span>
                            <?php
                        }
                        ?>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                        <label for="scPlcysIsDflt" class="control-label col-lg-6">Is Default?:</label>
                                        <div  class="col-lg-6">
                        <?php
                        $chkdYes = "";
                        $chkdNo = "checked=\"\"";
                        if ($row1[10] == "TRUE") {
                            $chkdNo = "";
                            $chkdYes = "checked=\"\"";
                        }
                        ?>
                                            <?php if ($canEdtScPlcy === true) { ?>
                                                <label class="radio-inline"><input type="radio" name="scPlcysIsDflt" value="YES" <?php echo $chkdYes; ?>>YES</label>
                                                <label class="radio-inline"><input type="radio" name="scPlcysIsDflt" value="NO" <?php echo $chkdNo; ?>>NO</label>
                                            <?php } else {
                                                ?>
                                                <span><?php echo ($row1[10] == "TRUE" ? "YES" : "NO"); ?></span>
                            <?php
                        }
                        ?>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                            <div  class="col-md-6" style="padding:0px 3px 0px 3px !important;">
                                <fieldset class="basic_person_fs" style="padding-top:10px !important;">
                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                        <label for="scPlcysPwdExpryDys" class="control-label col-lg-8">Password Expiry Days:</label>
                                        <div  class="col-lg-4">
                        <?php if ($canEdtScPlcy === true) { ?>
                                                <input type="number" min="1" max="9999" class="form-control" aria-label="..." id="scPlcysPwdExpryDys" name="scPlcysPwdExpryDys" value="<?php echo $row1[3]; ?>" style="width:100%;">
                        <?php } else {
                            ?>
                                                <span><?php echo $row1[3]; ?></span>
                                                <?php
                                            }
                                            ?>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                        <label for="scPlcysNoOfRecs" class="control-label col-lg-8">No. of Displayed Records:</label>
                                        <div  class="col-lg-4">
                        <?php if ($canEdtScPlcy === true) { ?>
                                                <input type="number" min="1" max="9999" class="form-control" aria-label="..." id="scPlcysNoOfRecs" name="scPlcysNoOfRecs" value="<?php echo $row1[14]; ?>" style="width:100%;">
                        <?php } else {
                            ?>
                                                <span><?php echo $row1[14]; ?></span>
                                                <?php
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                        </div>
                        <div class="row">
                            <div  class="col-md-6" style="padding:0px 3px 0px 3px !important;">
                                <fieldset class="basic_person_fs" style="padding-top:10px !important;">
                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                        <label for="scPlcysPwdMinLen" class="control-label col-lg-8">Minimum Length of Passwords:</label>
                                        <div  class="col-lg-4">
                        <?php if ($canEdtScPlcy === true) { ?>
                                                <input type="number" min="1" max="9999" class="form-control" aria-label="..." id="scPlcysPwdMinLen" name="scPlcysPwdMinLen" value="<?php echo $row1[12]; ?>" style="width:100%;">
                        <?php } else {
                            ?>
                                                <span><?php echo $row1[12]; ?></span>
                                                <?php
                                            }
                                            ?>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                        <label for="scPlcysPwdMaxLen" class="control-label col-lg-8">Maximum Length of Passwords:</label>
                                        <div  class="col-lg-4">
                        <?php if ($canEdtScPlcy === true) { ?>
                                                <input type="number" min="1" max="9999" class="form-control" aria-label="..." id="scPlcysPwdMaxLen" name="scPlcysPwdMaxLen" value="<?php echo $row1[13]; ?>" style="width:100%;">
                        <?php } else {
                            ?>
                                                <span><?php echo $row1[13]; ?></span>
                                                <?php
                                            }
                                            ?>
                                        </div>
                                    </div>

                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                        <label for="scPlcysPwdOldAlwd" class="control-label col-lg-8">Blocked Old Passwords:</label>
                                        <div  class="col-lg-4">
                        <?php if ($canEdtScPlcy === true) { ?>
                                                <input type="number" min="0" max="9999" class="form-control" aria-label="..." id="scPlcysPwdOldAlwd" name="scPlcysPwdOldAlwd" value="<?php echo $row1[11]; ?>" style="width:100%;">
                        <?php } else {
                            ?>
                                                <span><?php echo $row1[11]; ?></span>
                                                <?php
                                            }
                                            ?>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                        <label for="scPlcysPwdUnmAlwd" class="control-label col-lg-6">User Names in Passwords?:</label>
                                        <div  class="col-lg-6">
                        <?php
                        $chkdYes = "";
                        $chkdNo = "checked=\"\"";
                        if ($row1[16] == "TRUE") {
                            $chkdNo = "";
                            $chkdYes = "checked=\"\"";
                        }
                        ?>
                                            <?php if ($canEdtScPlcy === true) { ?>
                                                <label class="radio-inline"><input type="radio" name="scPlcysPwdUnmAlwd" value="YES" <?php echo $chkdYes; ?>>YES</label>
                                                <label class="radio-inline"><input type="radio" name="scPlcysPwdUnmAlwd" value="NO" <?php echo $chkdNo; ?>>NO</label>
                                            <?php } else {
                                                ?>
                                                <span><?php echo $row1[16]; ?></span>
                            <?php
                        }
                        ?>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                        <label for="scPlcysPwdRptngChars" class="control-label col-lg-6">Same sequential characters in Pswds?:</label>
                                        <div  class="col-lg-6">
                        <?php
                        $chkdYes = "";
                        $chkdNo = "checked=\"\"";
                        if ($row1[15] == "TRUE") {
                            $chkdNo = "";
                            $chkdYes = "checked=\"\"";
                        }
                        ?>
                                            <?php if ($canEdtScPlcy === true) { ?>
                                                <label class="radio-inline"><input type="radio" name="scPlcysPwdRptngChars" value="YES" <?php echo $chkdYes; ?>>YES</label>
                                                <label class="radio-inline"><input type="radio" name="scPlcysPwdRptngChars" value="NO" <?php echo $chkdNo; ?>>NO</label>
                                            <?php } else {
                                                ?>
                                                <span><?php echo $row1[15]; ?></span>
                            <?php
                        }
                        ?>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                        <label for="scPlcysAlwdFailedLgs" class="control-label col-lg-8">Max. Failed Login Attempts:</label>
                                        <div  class="col-lg-4">
                        <?php if ($canEdtScPlcy === true) { ?>
                                                <input type="number" min="1" max="9999" class="form-control" aria-label="..." id="scPlcysAlwdFailedLgs" name="scPlcysAlwdFailedLgs" value="<?php echo $row1[2]; ?>" style="width:100%;">
                        <?php } else {
                            ?>
                                                <span><?php echo $row1[2]; ?></span>
                                                <?php
                                            }
                                            ?>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                        <label for="scPlcysAutoUnlkTme" class="control-label col-md-8">Time (mins) to unlock a User:</label>
                                        <div  class="col-lg-4">
                        <?php if ($canEdtScPlcy === true) { ?>
                                                <input type="number" min="1" max="9999" class="form-control" aria-label="..." id="scPlcysAutoUnlkTme" name="scPlcysAutoUnlkTme" value="<?php echo $row1[4]; ?>" style="width:100%;">
                        <?php } else {
                            ?>
                                                <span><?php echo $row1[4]; ?></span>
                                                <?php
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                            <div  class="col-md-6" style="padding:0px 3px 0px 3px !important;">
                                <fieldset class="basic_person_fs" style="padding-top:15px !important;">
                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                        <label for="scPlcysChckBlkLtrs" class="control-label col-lg-6">Require Block Letters in Pswds?:</label>
                                        <div  class="col-lg-6">
                        <?php
                        $chkdYes = "";
                        $chkdNo = "checked=\"\"";
                        if ($row1[5] == "TRUE") {
                            $chkdNo = "";
                            $chkdYes = "checked=\"\"";
                        }
                        ?>
                                            <?php if ($canEdtScPlcy === true) { ?>
                                                <label class="radio-inline"><input type="radio" name="scPlcysChckBlkLtrs" value="YES" <?php echo $chkdYes; ?>>YES</label>
                                                <label class="radio-inline"><input type="radio" name="scPlcysChckBlkLtrs" value="NO" <?php echo $chkdNo; ?>>NO</label>
                                            <?php } else {
                                                ?>
                                                <span><?php echo $row1[5]; ?></span>
                            <?php
                        }
                        ?>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                        <label for="scPlcysChkSmllLtrs" class="control-label col-lg-6">Require Small Letters in Pswds?:</label>
                                        <div  class="col-lg-6">
                        <?php
                        $chkdYes = "";
                        $chkdNo = "checked=\"\"";
                        if ($row1[6] == "TRUE") {
                            $chkdNo = "";
                            $chkdYes = "checked=\"\"";
                        }
                        ?>
                                            <?php if ($canEdtScPlcy === true) { ?>
                                                <label class="radio-inline"><input type="radio" name="scPlcysChkSmllLtrs" value="YES" <?php echo $chkdYes; ?>>YES</label>
                                                <label class="radio-inline"><input type="radio" name="scPlcysChkSmllLtrs" value="NO" <?php echo $chkdNo; ?>>NO</label>
                                            <?php } else {
                                                ?>
                                                <span><?php echo $row1[6]; ?></span>
                            <?php
                        }
                        ?>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                        <label for="scPlcysChkDgts" class="control-label col-lg-6">Require Digits in Pswds?:</label>
                                        <div  class="col-lg-6">
                        <?php
                        $chkdYes = "";
                        $chkdNo = "checked=\"\"";
                        if ($row1[7] == "TRUE") {
                            $chkdNo = "";
                            $chkdYes = "checked=\"\"";
                        }
                        ?>
                                            <?php if ($canEdtScPlcy === true) { ?>
                                                <label class="radio-inline"><input type="radio" name="scPlcysChkDgts" value="YES" <?php echo $chkdYes; ?>>YES</label>
                                                <label class="radio-inline"><input type="radio" name="scPlcysChkDgts" value="NO" <?php echo $chkdNo; ?>>NO</label>
                                            <?php } else {
                                                ?>
                                                <span><?php echo $row1[7]; ?></span>
                            <?php
                        }
                        ?>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                        <label for="scPlcysChkWild" class="control-label col-lg-6">Require Wild Characters in Pswds?:</label>
                                        <div  class="col-lg-6">
                        <?php
                        $chkdYes = "";
                        $chkdNo = "checked=\"\"";
                        if ($row1[8] == "TRUE") {
                            $chkdNo = "";
                            $chkdYes = "checked=\"\"";
                        }
                        ?>
                                            <?php if ($canEdtScPlcy === true) { ?>
                                                <label class="radio-inline"><input type="radio" name="scPlcysChkWild" value="YES" <?php echo $chkdYes; ?>>YES</label>
                                                <label class="radio-inline"><input type="radio" name="scPlcysChkWild" value="NO" <?php echo $chkdNo; ?>>NO</label>
                                            <?php } else {
                                                ?>
                                                <span><?php echo $row1[8]; ?></span>
                            <?php
                        }
                        ?>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                        <label for="scPlcysCombinations" class="control-label col-lg-8">Combinations to Insist?:</label>
                                        <div class="col-lg-4">
                        <?php if ($canEdtScPlcy === true) { ?>
                                                <select data-placeholder="Select..." class="form-control chosen-select" id="scPlcysCombinations" name="scPlcysCombinations">
                            <?php
                            $valslctdArry = array("", "", "", "", "");
                            $srchInsArrys = array("NONE", "ALL 4", "ANY 3", "ANY 2", "ANY 1");
                            for ($z = 0; $z < count($srchInsArrys); $z++) {
                                if ($row1[9] == $srchInsArrys[$z]) {
                                    $valslctdArry[$z] = "selected";
                                }
                                ?>
                                                        <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                                    <?php } ?>
                                                </select>
                                                <?php } else {
                                                    ?>
                                                <span><?php echo $row1[9]; ?></span>
                                                    <?php
                                                }
                                                ?>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                        <label for="scPlcysSesnTmeOut" class="control-label col-md-8">Session Timeout (Seconds):</label>
                                        <div  class="col-md-4">
                        <?php if ($canEdtScPlcy === true) { ?>
                                                <input type="number" min="1" max="9999" class="form-control" aria-label="..." id="scPlcysSesnTmeOut" name="scPlcysSesnTmeOut" value="<?php echo $row1[17]; ?>" style="width:100%;">
                        <?php } else {
                            ?>
                                                <span><?php echo $row1[17]; ?></span>
                                                <?php
                                            }
                                            ?>
                                        </div>
                                    </div>                                                
                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;"><br/></div>
                                </fieldset>
                            </div>                                    
                        </div>
                        <div class="row">
                            <div  class="col-md-12" style="padding:0px 3px 0px 3px !important;">
                                <fieldset class="basic_person_fs">                                       
                                    <table class="table table-striped table-bordered table-responsive" id="secPlcyAdtTblsTable" cellspacing="0" width="100%" style="width:100%;min-width: 700px;">
                                        <thead>
                                            <tr>
                                                <th>No.</th>
                                                <th>Module Name</th>
                                                <th>Audit Trail Table Name</th>
                                                <th>Enable Tracking</th>
                                                <th>Actions to Track</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                        <?php
                        $result2 = get_Plcy_Mdls($pkID);
                        $cntr = 0;
                        $curIdx = 0;
                        while ($row2 = loc_db_fetch_array($result2)) {
                            $cntr += 1;
                            ?>
                                                <tr id="secPlcyAdtTblsRow_<?php echo $cntr; ?>" class="hand_cursor">                                    
                                                    <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                                    <td class="lovtd"><?php echo $row2[1]; ?></td>
                                                    <td class="lovtd"><?php echo $row2[2]; ?></td>
                                                    <td class="lovtd">
                            <?php if ($canEdtScPlcy === true) { ?>
                                                            <select data-placeholder="Select..." class="form-control chosen-select" id="secPlcyAdtTblsRow<?php echo $cntr; ?>_EnblTrckng" name="secPlcyAdtTblsRow<?php echo $cntr; ?>_EnblTrckng">
                                <?php
                                $valslctdArry = array("", "");
                                $srchInsArrys = array("YES", "NO");
                                for ($z = 0; $z < count($srchInsArrys); $z++) {
                                    if (($row2[3] == "TRUE" ? "YES" : "NO") == $srchInsArrys[$z]) {
                                        $valslctdArry[$z] = "selected";
                                    }
                                    ?>
                                                                    <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                                                <?php } ?>
                                                            </select>
                                                            <?php } else {
                                                                ?>
                                                            <span><?php echo $row2[3]; ?></span>
                                                                <?php
                                                            }
                                                            ?>
                                                    </td>
                                                    <td class="lovtd">
                                                        <?php if ($canEdtScPlcy === true) { ?>
                                                            <div class="input-group">
                                                                <input type="text" class="form-control" aria-label="..." id="secPlcyAdtTblsRow<?php echo $cntr; ?>_ActnsToTrck" name="secPlcyAdtTblsRow<?php echo $cntr; ?>_ActnsToTrck" value="<?php echo $row2[4]; ?>">
                                                                <input type="hidden" class="form-control" aria-label="..." id="secPlcyAdtTblsRow<?php echo $cntr; ?>_MdlID" name="secPlcyAdtTblsRow<?php echo $cntr; ?>_MdlID" value="<?php echo $row2[0]; ?>">
                                                                <input type="hidden" class="form-control" aria-label="..." id="secPlcyAdtTblsRow<?php echo $cntr; ?>_DfltRwID" name="secPlcyAdtTblsRow<?php echo $cntr; ?>_DfltRwID" value="<?php echo $row2[5]; ?>">                                                            
                                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Audit Trail Trackable Actions', '', '', '', 'check', true, '<?php echo $row2[4]; ?>', 'secPlcyAdtTblsRow<?php echo $cntr; ?>_ActnsToTrck', '', 'clear', 1, '');">
                                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                                </label>
                                                            </div><?php } else {
                                ?>
                                                            <span><?php echo $row2[4]; ?></span>
                                <?php
                            }
                            ?>
                                                    </td>
                                                </tr>
                                                        <?php
                                                    }
                                                    ?>
                                        </tbody>
                                    </table>
                                </fieldset>
                            </div>
                        </div>
                        <?php
                    }
                } else {
                    
                }
                ?>
                <?php
            } else if ($vwtyp == 2) {
                $pkID = -1;
                if ($canAddScPlcy === true) {
                    
                } else {
                    exit();
                }
                ?>
                <div class="row">
                    <div  class="col-md-6" style="padding:0px 3px 0px 3px !important;">
                        <fieldset class="basic_person_fs" style="padding-top:10px !important;"> 
                            <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                <label for="scPlcysPlcyNm" class="control-label col-lg-4">Policy Name:</label>
                                <div  class="col-lg-8">                                    
                                    <input type="text" class="form-control" aria-label="..." id="scPlcysPlcyNm" name="scPlcysPlcyNm" value="" style="width:100%;">
                                    <input type="hidden" class="form-control" aria-label="..." id="scPlcysPlcyID" name="scPlcysPlcyID" value="">
                                </div>
                            </div>
                            <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                <label for="scPlcysIsDflt" class="control-label col-lg-6">Is Default?:</label>
                                <div  class="col-lg-6">
                <?php
                $chkdYes = "";
                $chkdNo = "checked=\"\"";
                ?>                                    
                                    <label class="radio-inline"><input type="radio" name="scPlcysIsDflt" value="YES" <?php echo $chkdYes; ?>>YES</label>
                                    <label class="radio-inline"><input type="radio" name="scPlcysIsDflt" value="NO" <?php echo $chkdNo; ?>>NO</label>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                    <div  class="col-md-6" style="padding:0px 3px 0px 3px !important;">
                        <fieldset class="basic_person_fs" style="padding-top:10px !important;">
                            <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                <label for="scPlcysPwdExpryDys" class="control-label col-lg-8">Password Expiry Days:</label>
                                <div  class="col-lg-4">                                    
                                    <input type="number" min="1" max="9999" class="form-control" aria-label="..." id="scPlcysPwdExpryDys" name="scPlcysPwdExpryDys" value="" style="width:100%;">
                                </div>
                            </div>
                            <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                <label for="scPlcysNoOfRecs" class="control-label col-lg-8">No. of Displayed Records:</label>
                                <div  class="col-lg-4">                                    
                                    <input type="number" min="1" max="9999" class="form-control" aria-label="..." id="scPlcysNoOfRecs" name="scPlcysNoOfRecs" value="" style="width:100%;">
                                </div>
                            </div>
                        </fieldset>
                    </div>
                </div>
                <div class="row">
                    <div  class="col-md-6" style="padding:0px 3px 0px 3px !important;">
                        <fieldset class="basic_person_fs" style="padding-top:10px !important;">
                            <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                <label for="scPlcysPwdMinLen" class="control-label col-lg-8">Minimum Length of Passwords:</label>
                                <div  class="col-lg-4">                                    
                                    <input type="number" min="1" max="9999" class="form-control" aria-label="..." id="scPlcysPwdMinLen" name="scPlcysPwdMinLen" value="" style="width:100%;">
                                </div>
                            </div>
                            <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                <label for="scPlcysPwdMaxLen" class="control-label col-lg-8">Maximum Length of Passwords:</label>
                                <div  class="col-lg-4">
                                    <input type="number" min="1" max="9999" class="form-control" aria-label="..." id="scPlcysPwdMaxLen" name="scPlcysPwdMaxLen" value="" style="width:100%;">
                                </div>
                            </div>

                            <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                <label for="scPlcysPwdOldAlwd" class="control-label col-lg-8">Blocked Old Passwords:</label>
                                <div  class="col-lg-4">
                                    <input type="number" min="0" max="9999" class="form-control" aria-label="..." id="scPlcysPwdOldAlwd" name="scPlcysPwdOldAlwd" value="" style="width:100%;">
                                </div>
                            </div>
                            <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                <label for="scPlcysPwdUnmAlwd" class="control-label col-lg-6">User Names in Passwords?:</label>
                                <div  class="col-lg-6">
                <?php
                $chkdYes = "";
                $chkdNo = "checked=\"\"";
                ?>
                                    <label class="radio-inline"><input type="radio" name="scPlcysPwdUnmAlwd" value="YES" <?php echo $chkdYes; ?>>YES</label>
                                    <label class="radio-inline"><input type="radio" name="scPlcysPwdUnmAlwd" value="NO" <?php echo $chkdNo; ?>>NO</label>
                                </div>
                            </div>
                            <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                <label for="scPlcysPwdRptngChars" class="control-label col-lg-6">Same sequential characters in Pswds?:</label>
                                <div  class="col-lg-6">
                <?php
                $chkdYes = "";
                $chkdNo = "checked=\"\"";
                ?>
                                    <label class="radio-inline"><input type="radio" name="scPlcysPwdRptngChars" value="YES" <?php echo $chkdYes; ?>>YES</label>
                                    <label class="radio-inline"><input type="radio" name="scPlcysPwdRptngChars" value="NO" <?php echo $chkdNo; ?>>NO</label>
                                </div>
                            </div>
                            <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                <label for="scPlcysAlwdFailedLgs" class="control-label col-lg-8">Max. Failed Login Attempts:</label>
                                <div  class="col-lg-4">
                                    <input type="number" min="1" max="9999" class="form-control" aria-label="..." id="scPlcysAlwdFailedLgs" name="scPlcysAlwdFailedLgs" value="" style="width:100%;">
                                </div>
                            </div>
                            <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                <label for="scPlcysAutoUnlkTme" class="control-label col-md-8">Time (mins) to unlock a User:</label>
                                <div  class="col-lg-4">
                                    <input type="number" min="1" max="9999" class="form-control" aria-label="..." id="scPlcysAutoUnlkTme" name="scPlcysAutoUnlkTme" value="" style="width:100%;">
                                </div>
                            </div>
                        </fieldset>
                    </div>
                    <div  class="col-md-6" style="padding:0px 3px 0px 3px !important;">
                        <fieldset class="basic_person_fs" style="padding-top:15px !important;">
                            <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                <label for="scPlcysChckBlkLtrs" class="control-label col-lg-6">Require Block Letters in Pswds?:</label>
                                <div  class="col-lg-6">
                <?php
                $chkdYes = "";
                $chkdNo = "checked=\"\"";
                ?>
                                    <label class="radio-inline"><input type="radio" name="scPlcysChckBlkLtrs" value="YES" <?php echo $chkdYes; ?>>YES</label>
                                    <label class="radio-inline"><input type="radio" name="scPlcysChckBlkLtrs" value="NO" <?php echo $chkdNo; ?>>NO</label>
                                </div>
                            </div>
                            <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                <label for="scPlcysChkSmllLtrs" class="control-label col-lg-6">Require Small Letters in Pswds?:</label>
                                <div  class="col-lg-6">
                <?php
                $chkdYes = "";
                $chkdNo = "checked=\"\"";
                ?>
                                    <label class="radio-inline"><input type="radio" name="scPlcysChkSmllLtrs" value="YES" <?php echo $chkdYes; ?>>YES</label>
                                    <label class="radio-inline"><input type="radio" name="scPlcysChkSmllLtrs" value="NO" <?php echo $chkdNo; ?>>NO</label>
                                </div>
                            </div>
                            <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                <label for="scPlcysChkDgts" class="control-label col-lg-6">Require Digits in Pswds?:</label>
                                <div  class="col-lg-6">
                <?php
                $chkdYes = "";
                $chkdNo = "checked=\"\"";
                ?>
                                    <label class="radio-inline"><input type="radio" name="scPlcysChkDgts" value="YES" <?php echo $chkdYes; ?>>YES</label>
                                    <label class="radio-inline"><input type="radio" name="scPlcysChkDgts" value="NO" <?php echo $chkdNo; ?>>NO</label>
                                </div>
                            </div>
                            <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                <label for="scPlcysChkWild" class="control-label col-lg-6">Require Wild Characters in Pswds?:</label>
                                <div  class="col-lg-6">
                <?php
                $chkdYes = "";
                $chkdNo = "checked=\"\"";
                ?>
                                    <label class="radio-inline"><input type="radio" name="scPlcysChkWild" value="YES" <?php echo $chkdYes; ?>>YES</label>
                                    <label class="radio-inline"><input type="radio" name="scPlcysChkWild" value="NO" <?php echo $chkdNo; ?>>NO</label>
                                </div>
                            </div>
                            <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                <label for="scPlcysCombinations" class="control-label col-lg-8">Combinations to Insist?:</label>
                                <div class="col-lg-4">
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="scPlcysCombinations" name="scPlcysCombinations">
                <?php
                $valslctdArry = array("", "", "", "", "");
                $srchInsArrys = array("NONE", "ALL 4", "ANY 3", "ANY 2", "ANY 1");
                for ($z = 0; $z < count($srchInsArrys); $z++) {
                    ?>
                                            <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                <label for="scPlcysSesnTmeOut" class="control-label col-md-8">Session Timeout (Seconds):</label>
                                <div  class="col-md-4">
                                    <input type="number" min="1" max="9999" class="form-control" aria-label="..." id="scPlcysSesnTmeOut" name="scPlcysSesnTmeOut" value="" style="width:100%;">
                                </div>
                            </div>                                                
                            <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;"><br/></div>
                        </fieldset>
                    </div>                                    
                </div>
                <div class="row">
                    <div  class="col-md-12" style="padding:0px 3px 0px 3px !important;">
                        <fieldset class="basic_person_fs">                                       
                            <table class="table table-striped table-bordered table-responsive" id="secPlcyAdtTblsTable" cellspacing="0" width="100%" style="width:100%;min-width: 700px;">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Module Name</th>
                                        <th>Audit Trail Table Name</th>
                                        <th>Enable Tracking</th>
                                        <th>Actions to Track</th>
                                    </tr>
                                </thead>
                                <tbody>
                <?php
                $result2 = get_Plcy_Mdls($pkID);
                $cntr = 0;
                $curIdx = 0;
                while ($row2 = loc_db_fetch_array($result2)) {
                    $cntr += 1;
                    ?>
                                        <tr id="secPlcyAdtTblsRow_<?php echo $cntr; ?>" class="hand_cursor">                                    
                                            <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                            <td class="lovtd"><?php echo $row2[1]; ?></td>
                                            <td class="lovtd"><?php echo $row2[2]; ?></td>
                                            <td class="lovtd">
                                                <select data-placeholder="Select..." class="form-control chosen-select" id="secPlcyAdtTblsRow<?php echo $cntr; ?>_EnblTrckng" name="secPlcyAdtTblsRow<?php echo $cntr; ?>_EnblTrckng">
                    <?php
                    $valslctdArry = array("", "");
                    $srchInsArrys = array("YES", "NO");
                    for ($z = 0; $z < count($srchInsArrys); $z++) {
                        if (($row2[3] == "TRUE" ? "YES" : "NO") == $srchInsArrys[$z]) {
                            $valslctdArry[$z] = "selected";
                        }
                        ?>
                                                        <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </td>
                                            <td class="lovtd">
                                                <div class="input-group">
                                                    <input type="text" class="form-control" aria-label="..." id="secPlcyAdtTblsRow<?php echo $cntr; ?>_ActnsToTrck" name="secPlcyAdtTblsRow<?php echo $cntr; ?>_ActnsToTrck" value="<?php echo $row2[4]; ?>">
                                                    <input type="hidden" class="form-control" aria-label="..." id="secPlcyAdtTblsRow<?php echo $cntr; ?>_MdlID" name="secPlcyAdtTblsRow<?php echo $cntr; ?>_MdlID" value="<?php echo $row2[0]; ?>">
                                                    <input type="hidden" class="form-control" aria-label="..." id="secPlcyAdtTblsRow<?php echo $cntr; ?>_DfltRwID" name="secPlcyAdtTblsRow<?php echo $cntr; ?>_DfltRwID" value="<?php echo $row2[5]; ?>">
                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Audit Trail Trackable Actions', '', '', '', 'check', true, '<?php echo $row2[4]; ?>', 'secPlcyAdtTblsRow<?php echo $cntr; ?>_ActnsToTrck', '', 'clear', 1, '');">
                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                    </label>
                                                </div
                                            </td>
                                        </tr>
                    <?php
                }
                ?>
                                </tbody>
                            </table>
                        </fieldset>
                    </div>
                </div>
                <?php
            } else if ($vwtyp == 3) {
                
            } else if ($vwtyp == 4) {
                
            }
        }
    }
}    