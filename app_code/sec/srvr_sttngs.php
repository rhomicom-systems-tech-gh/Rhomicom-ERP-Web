<?php
$canAddSrvrStng = test_prmssns($dfltPrvldgs[12], $mdlNm);
$canEdtSrvrStng = test_prmssns($dfltPrvldgs[13], $mdlNm);

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
                $srvrStnSmtpClnt = isset($_POST['srvrStnSmtpClnt']) ? cleanInputData($_POST['srvrStnSmtpClnt']) : "";
                $srvrStnSmtpClntID = isset($_POST['srvrStnSmtpClntID']) ? (int) cleanInputData($_POST['srvrStnSmtpClntID']) : -1;
                $srvrStnIsDflt = isset($_POST['srvrStnIsDflt']) ? cleanInputData($_POST['srvrStnIsDflt']) : "NO";
                $is_dflt = ($srvrStnIsDflt == "YES") ? "t" : "f";
                $srvrStnSndrsEmail = isset($_POST['srvrStnSndrsEmail']) ? cleanInputData($_POST['srvrStnSndrsEmail']) : "";
                $srvrStnSndrsPswd = isset($_POST['srvrStnSndrsPswd']) ? cleanInputData($_POST['srvrStnSndrsPswd']) : "";
                $srvrStnSmtPort = isset($_POST['srvrStnSmtPort']) ? (int) cleanInputData($_POST['srvrStnSmtPort']) : 0;
                $srvrStnDomainNm = isset($_POST['srvrStnDomainNm']) ? cleanInputData($_POST['srvrStnDomainNm']) : "";
                $srvrStnFTPUrl = isset($_POST['srvrStnFTPUrl']) ? cleanInputData($_POST['srvrStnFTPUrl']) : "";
                $srvrStnFTPUsrNm = isset($_POST['srvrStnFTPUsrNm']) ? cleanInputData($_POST['srvrStnFTPUsrNm']) : "";
                $srvrStnFTPPswd = isset($_POST['srvrStnFTPPswd']) ? cleanInputData($_POST['srvrStnFTPPswd']) : "";
                $srvrStnFTPPort = isset($_POST['srvrStnFTPPort']) ? (int) cleanInputData($_POST['srvrStnFTPPort']) : 0;
                $srvrStnFTPStrtDir = isset($_POST['srvrStnFTPStrtDir']) ? cleanInputData($_POST['srvrStnFTPStrtDir']) : "";
                $srvrStnFTPBaseDir = isset($_POST['srvrStnFTPBaseDir']) ? cleanInputData($_POST['srvrStnFTPBaseDir']) : "";
                $srvrStnEnforceFTP = isset($_POST['srvrStnEnforceFTP']) ? cleanInputData($_POST['srvrStnEnforceFTP']) : "NO";
                $enfc = ($srvrStnEnforceFTP == "YES") ? true : false;
                $srvrStnComPort = isset($_POST['srvrStnComPort']) ? (int) cleanInputData($_POST['srvrStnComPort']) : "";
                $srvrStnBaudRate = isset($_POST['srvrStnBaudRate']) ? (int) cleanInputData($_POST['srvrStnBaudRate']) : "";
                $srvrStnTimeout = isset($_POST['srvrStnTimeout']) ? (int) cleanInputData($_POST['srvrStnTimeout']) : "";
                $srvrStnPGDumpDir = isset($_POST['srvrStnPGDumpDir']) ? cleanInputData($_POST['srvrStnPGDumpDir']) : "";
                $srvrStnBkpDir = isset($_POST['srvrStnBkpDir']) ? cleanInputData($_POST['srvrStnBkpDir']) : "";
                $srvrStnSmtpIP = isset($_POST['srvrStnSmtpIP']) ? cleanInputData($_POST['srvrStnSmtpIP']) : "";
                $slctdApiVals = isset($_POST['slctdApiVals']) ? cleanInputData($_POST['slctdApiVals']) : "";

                $oldID = getEmlSvrID($srvrStnSmtpClnt);
                if ($srvrStnSmtpClnt != "" && $srvrStnSmtpIP != "" && $srvrStnSndrsEmail != "" && $srvrStnSndrsPswd != "" && $srvrStnSmtPort != 0 && ($oldID <= 0 || $oldID == $srvrStnSmtpClntID)) {
                    if ($srvrStnIsDflt == "YES") {
                        undefaultAllEmlSvrs();
                    }
                    if ($srvrStnSmtpClntID <= 0) {
                        createEml_Svr($srvrStnSmtpClnt, $srvrStnSndrsEmail, $srvrStnSndrsPswd, $srvrStnSmtPort, $is_dflt, $srvrStnDomainNm, $srvrStnFTPUrl, $srvrStnFTPUsrNm, $srvrStnFTPPswd, $srvrStnFTPPort, $srvrStnFTPBaseDir, $enfc, $srvrStnPGDumpDir, $srvrStnBkpDir, $srvrStnComPort, $srvrStnBaudRate, $srvrStnTimeout, $srvrStnFTPStrtDir, $srvrStnSmtpIP);
                        $srvrStnSmtpClntID = getEmlSvrID($srvrStnSmtpClnt);
                    } else {
                        updateEmlSvrs($srvrStnSmtpClntID, $srvrStnSmtpClnt, $srvrStnSndrsEmail, $srvrStnSndrsPswd, $srvrStnSmtPort, $is_dflt, $srvrStnDomainNm, $srvrStnFTPUrl, $srvrStnFTPUsrNm, $srvrStnFTPPswd, $srvrStnFTPPort, $srvrStnFTPBaseDir, $enfc, $srvrStnPGDumpDir, $srvrStnBkpDir, $srvrStnComPort, $srvrStnBaudRate, $srvrStnTimeout, $srvrStnFTPStrtDir, $srvrStnSmtpIP);
                    }

                    $affctdRws = 0;
                    if (trim($slctdApiVals, "|~") != "") {
                        //Save Question Answers
                        $variousRows = explode("|", trim($slctdApiVals, "|"));
                        for ($z = 0; $z < count($variousRows); $z++) {
                            $crntRow = explode("~", $variousRows[$z]);
                            if (count($crntRow) == 2) {
                                $inptParam = (cleanInputData1($crntRow[0]));
                                $inptParamVal = (cleanInputData1($crntRow[1]));
                                $updstr = "UPDATE sec.sec_email_servers SET sms_param" . ($z + 1) .
                                        "='" . loc_db_escape_string($inptParam . "|" . $inptParamVal) . "' WHERE server_id = " . $srvrStnSmtpClntID;
                                $affctdRws += execUpdtInsSQL($updstr);
                            }
                        }
                    }
                    echo ("<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>Server Setting Successfully Saved!<br/>" . $affctdRws . "Parameter(s) Saved!");
                    exit();
                } else {
                    echo ("<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>Either the New Server Name is in Use <br/>or Data Supplied is Incomplete!</span>");
                    exit();
                }
            } else if ($actyp == 2) {
                
            }
        } else {
            if ($vwtyp == 0) {
                $pkID = isset($_POST['sbmtdSrvrStngID']) ? $_POST['sbmtdSrvrStngID'] : -1;
                echo $cntent . "<li>
                                    <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                    <span style=\"text-decoration:none;\">Server Settings</span>
				</li>
                               </ul>
                              </div>";
                $total = get_SrvrStngsTblrTtl($srchFor, $srchIn);
                if ($pageNo > ceil($total / $lmtSze)) {
                    $pageNo = 1;
                } else if ($pageNo < 1) {
                    $pageNo = ceil($total / $lmtSze);
                }

                $curIdx = $pageNo - 1;
                $result = get_SrvrStngsTblr($srchFor, $srchIn, $curIdx, $lmtSze);
                $cntr = 0;
                $colClassType1 = "col-lg-2";
                $colClassType2 = "col-lg-3";
                $colClassType3 = "col-lg-4";
                ?>
                <form id='allSrvrStngsForm' action='' method='post' accept-charset='UTF-8'>
                    <div class="row rhoRowMargin">
                        <?php
                        if ($canAddSrvrStng === true) {
                            ?> 
                            <div class="<?php echo $colClassType2; ?>" style="padding:0px 1px 0px 1px !important;"> 
                                <div class="col-md-6">
                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="getOneSrvrStngForm(-1, 2);" style="width:100% !important;">
                                        <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                        New Server
                                    </button>
                                </div>
                                <div class="col-md-6">
                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="saveSrvrStngForm();" style="width:100% !important;">
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
                                <input class="form-control" id="allSrvrStngsSrchFor" type = "text" placeholder="Search For" value="<?php echo trim(str_replace("%", " ", $srchFor)); ?>" onkeyup="enterKeyFuncAllSrvrStngs(event, '', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>')">
                                <input id="allSrvrStngsPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                <label class="btn btn-primary btn-file input-group-addon" onclick="getAllSrvrStngs('clear', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>')">
                                    <span class="glyphicon glyphicon-remove"></span>
                                </label>
                                <label class="btn btn-primary btn-file input-group-addon" onclick="getAllSrvrStngs('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>')">
                                    <span class="glyphicon glyphicon-search"></span>
                                </label> 
                            </div>
                        </div>
                        <div class="<?php echo $colClassType3; ?>">
                            <div class="input-group">
                                <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                <select data-placeholder="Select..." class="form-control chosen-select" id="allSrvrStngsSrchIn">
                                    <?php
                                    $valslctdArry = array("", "");
                                    $srchInsArrys = array("SMTP CLIENT", "SENDER's USER NAME");

                                    for ($z = 0; $z < count($srchInsArrys); $z++) {
                                        if ($srchIn == $srchInsArrys[$z]) {
                                            $valslctdArry[$z] = "selected";
                                        }
                                        ?>
                                        <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                    <?php } ?>
                                </select>
                                <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                <select data-placeholder="Select..." class="form-control chosen-select" id="allSrvrStngsDsplySze" style="min-width:70px !important;">                            
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
                                        <a class="rhopagination" href="javascript:getAllSrvrStngs('previous', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');" aria-label="Previous">
                                            <span aria-hidden="true">&laquo;</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:getAllSrvrStngs('next', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');" aria-label="Next">
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
                                <table class="table table-striped table-bordered table-responsive" id="allSrvrStngsTable" cellspacing="0" width="100%" style="width:100%;">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Server Name</th>
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
                                            <tr id="allSrvrStngsRow_<?php echo $cntr; ?>" class="hand_cursor">                                    
                                                <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                                <td class="lovtd"><?php echo $row[1]; ?><input type="hidden" class="form-control" aria-label="..." id="allSrvrStngsRow<?php echo $cntr; ?>_SrvrID" value="<?php echo $row[0]; ?>"></td>
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
                                <legend class = "basic_person_lg">Server Details</legend>
                                <div class="container-fluid" id="srvrStngsDetailInfo">
                                    <?php
                                    if ($pkID > 0) {
                                        $result1 = get_SrvrStngsDet($pkID);
                                        while ($row1 = loc_db_fetch_array($result1)) {
                                            ?>
                                            <div class="row">
                                                <div  class="col-md-6" style="padding:0px 3px 0px 3px !important;">
                                                    <fieldset class="basic_person_fs" style="padding-top:10px !important;"> 
                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                            <label for="srvrStnSmtpClnt" class="control-label col-lg-4">SMTP Client:</label>
                                                            <div  class="col-lg-8">
                                                                <?php if ($canEdtSrvrStng === true) { ?>
                                                                    <input type="text" class="form-control" aria-label="..." id="srvrStnSmtpClnt" name="srvrStnSmtpClnt" value="<?php echo $row1[1]; ?>" style="width:100%;">
                                                                    <input type="hidden" class="form-control" aria-label="..." id="srvrStnSmtpClntID" name="srvrStnSmtpClntID" value="<?php echo $row1[0]; ?>">
                                                                <?php } else {
                                                                    ?>
                                                                    <span><?php echo $row1[1]; ?></span>
                                                                    <?php
                                                                }
                                                                ?>
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                            <label for="srvrStnSndrsEmail" class="control-label col-lg-4">SENDER's Email:</label>
                                                            <div  class="col-lg-8">
                                                                <?php if ($canEdtSrvrStng === true) { ?>
                                                                    <input type="text" class="form-control" aria-label="..." id="srvrStnSndrsEmail" name="srvrStnSndrsEmail" value="<?php echo $row1[2]; ?>" style="width:100%;">
                                                                <?php } else {
                                                                    ?>
                                                                    <span><?php echo $row1[2]; ?></span>
                                                                    <?php
                                                                }
                                                                ?>
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                            <label for="srvrStnSndrsPswd" class="control-label col-lg-4">Password:</label>
                                                            <div  class="col-lg-8">
                                                                <?php if ($canEdtSrvrStng === true) { ?>
                                                                    <input type="password" class="form-control" aria-label="..." id="srvrStnSndrsPswd" name="srvrStnSndrsPswd" value="<?php echo decrypt($row1[3], $smplTokenWord); ?>" style="width:100%;">
                                                                <?php } else {
                                                                    ?>
                                                                    <span><?php echo decrypt($row1[3], $smplTokenWord); ?></span>
                                                                    <?php
                                                                }
                                                                ?>
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                            <label for="srvrStnSmtPort" class="control-label col-lg-4">Smtp Port No.:</label>
                                                            <div  class="col-lg-3">
                                                                <?php if ($canEdtSrvrStng === true) { ?>
                                                                    <input type="number" min="1" max="9999" class="form-control" aria-label="..." id="srvrStnSmtPort" name="srvrStnSmtPort" value="<?php echo $row1[4]; ?>" style="width:100%;">
                                                                <?php } else {
                                                                    ?>
                                                                    <span><?php echo $row1[4]; ?></span>
                                                                    <?php
                                                                }
                                                                ?>
                                                            </div>
                                                            <div  class="col-lg-5">
                                                                <?php if ($canEdtSrvrStng === true) { ?>
                                                                    <input type="text" class="form-control" aria-label="..." id="srvrStnSmtpIP" name="srvrStnSmtpIP" value="<?php echo $row1[29]; ?>" style="width:100%;">
                                                                <?php } else {
                                                                    ?>
                                                                    <span><?php echo $row1[29]; ?></span>
                                                                    <?php
                                                                }
                                                                ?>
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                            <label for="srvrStnIsDflt" class="control-label col-lg-6">Is Default?:</label>
                                                            <div  class="col-lg-6">
                                                                <?php
                                                                $chkdYes = "";
                                                                $chkdNo = "checked=\"\"";
                                                                if ($row1[5] == "Yes") {
                                                                    $chkdNo = "";
                                                                    $chkdYes = "checked=\"\"";
                                                                }
                                                                ?>
                                                                <?php if ($canEdtSrvrStng === true) { ?>
                                                                    <label class="radio-inline"><input type="radio" name="srvrStnIsDflt" value="YES" <?php echo $chkdYes; ?>>YES</label>
                                                                    <label class="radio-inline"><input type="radio" name="srvrStnIsDflt" value="NO" <?php echo $chkdNo; ?>>NO</label>
                                                                <?php } else {
                                                                    ?>
                                                                    <span><?php echo ($row1[5] == "Yes" ? "YES" : "NO"); ?></span>
                                                                    <?php
                                                                }
                                                                ?>
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                            <label for="srvrStnDomainNm" class="control-label col-lg-4">Domain Name:</label>
                                                            <div  class="col-lg-8">
                                                                <?php if ($canEdtSrvrStng === true) { ?>
                                                                    <input type="text" class="form-control" aria-label="..." id="srvrStnDomainNm" name="srvrStnDomainNm" value="<?php echo $row1[6]; ?>" style="width:100%;">
                                                                <?php } else {
                                                                    ?>
                                                                    <span><?php echo $row1[6]; ?></span>
                                                                    <?php
                                                                }
                                                                ?>
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                            <label for="srvrStnFTPUrl" class="control-label col-lg-4">FTP Server Url:</label>
                                                            <div  class="col-lg-8">
                                                                <?php if ($canEdtSrvrStng === true) { ?>
                                                                    <input type="text" class="form-control" aria-label="..." id="srvrStnFTPUrl" name="srvrStnFTPUrl" value="<?php echo $row1[7]; ?>" style="width:100%;">
                                                                <?php } else {
                                                                    ?>
                                                                    <span><?php echo $row1[7]; ?></span>
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
                                                            <label for="srvrStnFTPUsrNm" class="control-label col-lg-4">FTP Username:</label>
                                                            <div  class="col-lg-8">
                                                                <?php if ($canEdtSrvrStng === true) { ?>
                                                                    <input type="text" class="form-control" aria-label="..." id="srvrStnFTPUsrNm" name="srvrStnFTPUsrNm" value="<?php echo $row1[8]; ?>" style="width:100%;">
                                                                <?php } else {
                                                                    ?>
                                                                    <span><?php echo $row1[8]; ?></span>
                                                                    <?php
                                                                }
                                                                ?>
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                            <label for="srvrStnFTPPswd" class="control-label col-lg-4">Password:</label>
                                                            <div  class="col-lg-8">
                                                                <?php if ($canEdtSrvrStng === true) { ?>
                                                                    <input type="password" class="form-control" aria-label="..." id="srvrStnFTPPswd" name="srvrStnFTPPswd" value="<?php echo decrypt($row1[9], $smplTokenWord); ?>" style="width:100%;">
                                                                <?php } else {
                                                                    ?>
                                                                    <span><?php echo decrypt($row1[9], $smplTokenWord); ?></span>
                                                                    <?php
                                                                }
                                                                ?>
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                            <label for="srvrStnFTPPort" class="control-label col-lg-8">FTP Port:</label>
                                                            <div  class="col-lg-4">
                                                                <?php if ($canEdtSrvrStng === true) { ?>
                                                                    <input type="number" min="1" max="99999" class="form-control" aria-label="..." id="srvrStnFTPPort" name="srvrStnFTPPort" value="<?php echo $row1[10]; ?>" style="width:100%;">
                                                                <?php } else {
                                                                    ?>
                                                                    <span><?php echo $row1[10]; ?></span>
                                                                    <?php
                                                                }
                                                                ?>
                                                            </div>
                                                        </div>                                                        
                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                            <label for="srvrStnFTPStrtDir" class="control-label col-lg-4">FTP User's Home Directory:</label>
                                                            <div  class="col-lg-8">
                                                                <?php if ($canEdtSrvrStng === true) { ?>
                                                                    <input type="text" class="form-control" aria-label="..." id="srvrStnFTPStrtDir" name="srvrStnFTPStrtDir" value="<?php echo $row1[28]; ?>" style="width:100%;">
                                                                <?php } else {
                                                                    ?>
                                                                    <span><?php echo $row1[28]; ?></span>
                                                                    <?php
                                                                }
                                                                ?>
                                                            </div>
                                                        </div>                                                        
                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                            <label for="srvrStnFTPBaseDir" class="control-label col-lg-4">Base Subdirectory URL:</label>
                                                            <div  class="col-lg-8">
                                                                <?php if ($canEdtSrvrStng === true) { ?>
                                                                    <input type="text" class="form-control" aria-label="..." id="srvrStnFTPBaseDir" name="srvrStnFTPBaseDir" value="<?php echo $row1[11]; ?>" style="width:100%;">
                                                                <?php } else {
                                                                    ?>
                                                                    <span><?php echo $row1[11]; ?></span>
                                                                    <?php
                                                                }
                                                                ?>
                                                            </div>
                                                        </div>                                                        
                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                            <label for="srvrStnEnforceFTP" class="control-label col-lg-6">Enforce FTP?:</label>
                                                            <div  class="col-lg-6">
                                                                <?php
                                                                $chkdYes = "";
                                                                $chkdNo = "checked=\"\"";
                                                                if ($row1[12] == "Yes") {
                                                                    $chkdNo = "";
                                                                    $chkdYes = "checked=\"\"";
                                                                }
                                                                ?>
                                                                <?php if ($canEdtSrvrStng === true) { ?>
                                                                    <label class="radio-inline"><input type="radio" name="srvrStnEnforceFTP" value="YES" <?php echo $chkdYes; ?>>YES</label>
                                                                    <label class="radio-inline"><input type="radio" name="srvrStnEnforceFTP" value="NO" <?php echo $chkdNo; ?>>NO</label>
                                                                <?php } else {
                                                                    ?>
                                                                    <span><?php echo ($row1[12] == "Yes" ? "YES" : "NO"); ?></span>
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
                                                            <label for="srvrStnComPort" class="control-label col-lg-8">Modem Settings COM Port:</label>
                                                            <div  class="col-lg-4">
                                                                <?php if ($canEdtSrvrStng === true) { ?>
                                                                    <input type="number" min="1" max="9999" class="form-control" aria-label="..." id="srvrStnComPort" name="srvrStnComPort" value="<?php echo $row1[15]; ?>" style="width:100%;">
                                                                <?php } else {
                                                                    ?>
                                                                    <span><?php echo $row1[15]; ?></span>
                                                                    <?php
                                                                }
                                                                ?>
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                            <label for="srvrStnBaudRate" class="control-label col-lg-8">Modem Settings BAUD Rate:</label>
                                                            <div  class="col-lg-4">
                                                                <?php if ($canEdtSrvrStng === true) { ?>
                                                                    <input type="number" min="1" max="999999" class="form-control" aria-label="..." id="srvrStnBaudRate" name="srvrStnBaudRate" value="<?php echo $row1[16]; ?>" style="width:100%;">
                                                                <?php } else {
                                                                    ?>
                                                                    <span><?php echo $row1[16]; ?></span>
                                                                    <?php
                                                                }
                                                                ?>
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                            <label for="srvrStnTimeout" class="control-label col-lg-8">Blocked Old Passwords:</label>
                                                            <div  class="col-lg-4">
                                                                <?php if ($canEdtSrvrStng === true) { ?>
                                                                    <input type="number" min="0" max="9999" class="form-control" aria-label="..." id="srvrStnTimeout" name="srvrStnTimeout" value="<?php echo $row1[17]; ?>" style="width:100%;">
                                                                <?php } else {
                                                                    ?>
                                                                    <span><?php echo $row1[17]; ?></span>
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
                                                            <label for="srvrStnPGDumpDir" class="control-label col-lg-4">PG Dump Bin Directory:</label>
                                                            <div  class="col-lg-8">
                                                                <?php if ($canEdtSrvrStng === true) { ?>
                                                                    <input type="text" class="form-control" aria-label="..." id="srvrStnPGDumpDir" name="srvrStnPGDumpDir" value="<?php echo $row1[13]; ?>" style="width:100%;">
                                                                <?php } else {
                                                                    ?>
                                                                    <span><?php echo $row1[13]; ?></span>
                                                                    <?php
                                                                }
                                                                ?>
                                                            </div>
                                                        </div>                                                        
                                                        <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                            <label for="srvrStnBkpDir" class="control-label col-lg-4">Backup Directory:</label>
                                                            <div  class="col-lg-8">
                                                                <?php if ($canEdtSrvrStng === true) { ?>
                                                                    <input type="text" class="form-control" aria-label="..." id="srvrStnBkpDir" name="srvrStnBkpDir" value="<?php echo $row1[14]; ?>" style="width:100%;">
                                                                <?php } else {
                                                                    ?>
                                                                    <span><?php echo $row1[14]; ?></span>
                                                                    <?php
                                                                }
                                                                ?>
                                                            </div>
                                                        </div> 
                                                        <!--<div class="" style="float:right;padding-right:15px !important;">
                                                            <button type="button" class="btn btn-primary" onclick="">Backup Database</button>
                                                            <button type="button" class="btn btn-primary" onclick="">Restore Database</button>
                                                        </div>-->
                                                    </fieldset>
                                                </div>                                    
                                            </div>
                                            <div class="row">
                                                <div  class="col-md-12" style="padding:0px 3px 0px 3px !important;">
                                                    <fieldset class="basic_person_fs">                                       
                                                        <table class="table table-striped table-bordered table-responsive" id="srvrStngSmsApiTable" cellspacing="0" width="100%" style="width:100%;min-width: 700px;">
                                                            <thead>
                                                                <tr>
                                                                    <th>No.</th>
                                                                    <th>Parameter</th>
                                                                    <th>Value</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php
                                                                $cntr = 0;
                                                                while ($cntr < 10) {
                                                                    $arry1 = explode("|", $row1[18 + $cntr]);
                                                                    $cntr += 1;
                                                                    ?>
                                                                    <tr id="srvrStngSmsApiRow_<?php echo $cntr; ?>" class="hand_cursor">                                    
                                                                        <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                                                        <td class="lovtd">
                                                                            <?php if ($canEdtSrvrStng === true) { ?>
                                                                                <input type="text" class="form-control" aria-label="..." id="srvrStngSmsApiRow<?php echo $cntr; ?>_Param" name="srvrStngSmsApiRow<?php echo $cntr; ?>_Param" value="<?php echo str_replace('"', '&quot;', $arry1[0]); ?>" style="width:100%;">

                                                                            <?php } else {
                                                                                ?>
                                                                                <span><?php echo $arry1[0]; ?></span>
                                                                                <?php
                                                                            }
                                                                            ?>
                                                                        </td>
                                                                        <td class="lovtd">
                                                                            <?php if ($canEdtSrvrStng === true) { ?>
                                                                                <input type="text" class="form-control" aria-label="..." id="srvrStngSmsApiRow<?php echo $cntr; ?>_Value" name="srvrStngSmsApiRow<?php echo $cntr; ?>_Value" value="<?php echo str_replace('"', '&quot;', $arry1[1]); ?>" style="width:100%;">
                                                                            <?php } else {
                                                                                ?>
                                                                                <span><?php echo $arry1[1]; ?></span>
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
                $curIdx = 0;
                $pkID = isset($_POST['sbmtdSrvrStngID']) ? $_POST['sbmtdSrvrStngID'] : -1;
                ?>
                <?php
                if ($pkID > 0) {
                    $result1 = get_SrvrStngsDet($pkID);
                    while ($row1 = loc_db_fetch_array($result1)) {
                        ?>
                        <div class="row">
                            <div  class="col-md-6" style="padding:0px 3px 0px 3px !important;">
                                <fieldset class="basic_person_fs" style="padding-top:10px !important;"> 
                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                        <label for="srvrStnSmtpClnt" class="control-label col-lg-4">SMTP Client:</label>
                                        <div  class="col-lg-8">
                                            <?php if ($canEdtSrvrStng === true) { ?>
                                                <input type="text" class="form-control" aria-label="..." id="srvrStnSmtpClnt" name="srvrStnSmtpClnt" value="<?php echo $row1[1]; ?>" style="width:100%;">
                                                <input type="hidden" class="form-control" aria-label="..." id="srvrStnSmtpClntID" name="srvrStnSmtpClntID" value="<?php echo $row1[0]; ?>">
                                            <?php } else {
                                                ?>
                                                <span><?php echo $row1[1]; ?></span>
                                                <?php
                                            }
                                            ?>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                        <label for="srvrStnSndrsEmail" class="control-label col-lg-4">SENDER's Email:</label>
                                        <div  class="col-lg-8">
                                            <?php if ($canEdtSrvrStng === true) { ?>
                                                <input type="text" class="form-control" aria-label="..." id="srvrStnSndrsEmail" name="srvrStnSndrsEmail" value="<?php echo $row1[2]; ?>" style="width:100%;">
                                            <?php } else {
                                                ?>
                                                <span><?php echo $row1[2]; ?></span>
                                                <?php
                                            }
                                            ?>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                        <label for="srvrStnSndrsPswd" class="control-label col-lg-4">Password:</label>
                                        <div  class="col-lg-8">
                                            <?php if ($canEdtSrvrStng === true) { ?>
                                                <input type="password" class="form-control" aria-label="..." id="srvrStnSndrsPswd" name="srvrStnSndrsPswd" value="<?php echo decrypt($row1[3], $smplTokenWord); ?>" style="width:100%;">
                                            <?php } else {
                                                ?>
                                                <span><?php echo decrypt($row1[3], $smplTokenWord); ?></span>
                                                <?php
                                            }
                                            ?>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                        <label for="srvrStnSmtPort" class="control-label col-lg-4">Smtp Port No./IP:</label>
                                        <div  class="col-lg-3">
                                            <?php if ($canEdtSrvrStng === true) { ?>
                                                <input type="number" min="1" max="9999" class="form-control" aria-label="..." id="srvrStnSmtPort" name="srvrStnSmtPort" value="<?php echo $row1[4]; ?>" style="width:100%;">
                                            <?php } else {
                                                ?>
                                                <span><?php echo $row1[4]; ?></span>
                                                <?php
                                            }
                                            ?>
                                        </div>
                                        <div  class="col-lg-5">
                                            <?php if ($canEdtSrvrStng === true) { ?>
                                                <input type="text" class="form-control" aria-label="..." id="srvrStnSmtpIP" name="srvrStnSmtpIP" value="<?php echo $row1[29]; ?>" style="width:100%;">
                                            <?php } else {
                                                ?>
                                                <span><?php echo $row1[29]; ?></span>
                                                <?php
                                            }
                                            ?>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                        <label for="srvrStnIsDflt" class="control-label col-lg-6">Is Default?:</label>
                                        <div  class="col-lg-6">
                                            <?php
                                            $chkdYes = "";
                                            $chkdNo = "checked=\"\"";
                                            if ($row1[5] == "Yes") {
                                                $chkdNo = "";
                                                $chkdYes = "checked=\"\"";
                                            }
                                            ?>
                                            <?php if ($canEdtSrvrStng === true) { ?>
                                                <label class="radio-inline"><input type="radio" name="srvrStnIsDflt" value="YES" <?php echo $chkdYes; ?>>YES</label>
                                                <label class="radio-inline"><input type="radio" name="srvrStnIsDflt" value="NO" <?php echo $chkdNo; ?>>NO</label>
                                            <?php } else {
                                                ?>
                                                <span><?php echo ($row1[5] == "Yes" ? "YES" : "NO"); ?></span>
                                                <?php
                                            }
                                            ?>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                        <label for="srvrStnDomainNm" class="control-label col-lg-4">Domain Name:</label>
                                        <div  class="col-lg-8">
                                            <?php if ($canEdtSrvrStng === true) { ?>
                                                <input type="text" class="form-control" aria-label="..." id="srvrStnDomainNm" name="srvrStnDomainNm" value="<?php echo $row1[6]; ?>" style="width:100%;">
                                            <?php } else {
                                                ?>
                                                <span><?php echo $row1[6]; ?></span>
                                                <?php
                                            }
                                            ?>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                        <label for="srvrStnFTPUrl" class="control-label col-lg-4">FTP Server Url:</label>
                                        <div  class="col-lg-8">
                                            <?php if ($canEdtSrvrStng === true) { ?>
                                                <input type="text" class="form-control" aria-label="..." id="srvrStnFTPUrl" name="srvrStnFTPUrl" value="<?php echo $row1[7]; ?>" style="width:100%;">
                                            <?php } else {
                                                ?>
                                                <span><?php echo $row1[7]; ?></span>
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
                                        <label for="srvrStnFTPUsrNm" class="control-label col-lg-4">FTP Username:</label>
                                        <div  class="col-lg-8">
                                            <?php if ($canEdtSrvrStng === true) { ?>
                                                <input type="text" class="form-control" aria-label="..." id="srvrStnFTPUsrNm" name="srvrStnFTPUsrNm" value="<?php echo $row1[8]; ?>" style="width:100%;">
                                            <?php } else {
                                                ?>
                                                <span><?php echo $row1[8]; ?></span>
                                                <?php
                                            }
                                            ?>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                        <label for="srvrStnFTPPswd" class="control-label col-lg-4">Password:</label>
                                        <div  class="col-lg-8">
                                            <?php if ($canEdtSrvrStng === true) { ?>
                                                <input type="password" class="form-control" aria-label="..." id="srvrStnFTPPswd" name="srvrStnFTPPswd" value="<?php echo decrypt($row1[9], $smplTokenWord); ?>" style="width:100%;">
                                            <?php } else {
                                                ?>
                                                <span><?php echo decrypt($row1[9], $smplTokenWord); ?></span>
                                                <?php
                                            }
                                            ?>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                        <label for="srvrStnFTPPort" class="control-label col-lg-8">FTP Port:</label>
                                        <div  class="col-lg-4">
                                            <?php if ($canEdtSrvrStng === true) { ?>
                                                <input type="number" min="1" max="99999" class="form-control" aria-label="..." id="srvrStnFTPPort" name="srvrStnFTPPort" value="<?php echo $row1[10]; ?>" style="width:100%;">
                                            <?php } else {
                                                ?>
                                                <span><?php echo $row1[10]; ?></span>
                                                <?php
                                            }
                                            ?>
                                        </div>
                                    </div>                                                        
                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                        <label for="srvrStnFTPStrtDir" class="control-label col-lg-4">FTP User's Home Directory:</label>
                                        <div  class="col-lg-8">
                                            <?php if ($canEdtSrvrStng === true) { ?>
                                                <input type="text" class="form-control" aria-label="..." id="srvrStnFTPStrtDir" name="srvrStnFTPStrtDir" value="<?php echo $row1[28]; ?>" style="width:100%;">
                                            <?php } else {
                                                ?>
                                                <span><?php echo $row1[28]; ?></span>
                                                <?php
                                            }
                                            ?>
                                        </div>
                                    </div>                                                        
                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                        <label for="srvrStnFTPBaseDir" class="control-label col-lg-4">Base Subdirectory URL:</label>
                                        <div  class="col-lg-8">
                                            <?php if ($canEdtSrvrStng === true) { ?>
                                                <input type="text" class="form-control" aria-label="..." id="srvrStnFTPBaseDir" name="srvrStnFTPBaseDir" value="<?php echo $row1[11]; ?>" style="width:100%;">
                                            <?php } else {
                                                ?>
                                                <span><?php echo $row1[11]; ?></span>
                                                <?php
                                            }
                                            ?>
                                        </div>
                                    </div>                                                        
                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                        <label for="srvrStnEnforceFTP" class="control-label col-lg-6">Enforce FTP?:</label>
                                        <div  class="col-lg-6">
                                            <?php
                                            $chkdYes = "";
                                            $chkdNo = "checked=\"\"";
                                            if ($row1[12] == "Yes") {
                                                $chkdNo = "";
                                                $chkdYes = "checked=\"\"";
                                            }
                                            ?>
                                            <?php if ($canEdtSrvrStng === true) { ?>
                                                <label class="radio-inline"><input type="radio" name="srvrStnEnforceFTP" value="YES" <?php echo $chkdYes; ?>>YES</label>
                                                <label class="radio-inline"><input type="radio" name="srvrStnEnforceFTP" value="NO" <?php echo $chkdNo; ?>>NO</label>
                                            <?php } else {
                                                ?>
                                                <span><?php echo ($row1[12] == "Yes" ? "YES" : "NO"); ?></span>
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
                                        <label for="srvrStnComPort" class="control-label col-lg-8">Modem Settings COM Port:</label>
                                        <div  class="col-lg-4">
                                            <?php if ($canEdtSrvrStng === true) { ?>
                                                <input type="number" min="1" max="9999" class="form-control" aria-label="..." id="srvrStnComPort" name="srvrStnComPort" value="<?php echo $row1[15]; ?>" style="width:100%;">
                                            <?php } else {
                                                ?>
                                                <span><?php echo $row1[15]; ?></span>
                                                <?php
                                            }
                                            ?>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                        <label for="srvrStnBaudRate" class="control-label col-lg-8">Modem Settings BAUD Rate:</label>
                                        <div  class="col-lg-4">
                                            <?php if ($canEdtSrvrStng === true) { ?>
                                                <input type="number" min="1" max="999999" class="form-control" aria-label="..." id="srvrStnBaudRate" name="srvrStnBaudRate" value="<?php echo $row1[16]; ?>" style="width:100%;">
                                            <?php } else {
                                                ?>
                                                <span><?php echo $row1[16]; ?></span>
                                                <?php
                                            }
                                            ?>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                        <label for="srvrStnTimeout" class="control-label col-lg-8">Blocked Old Passwords:</label>
                                        <div  class="col-lg-4">
                                            <?php if ($canEdtSrvrStng === true) { ?>
                                                <input type="number" min="0" max="9999" class="form-control" aria-label="..." id="srvrStnTimeout" name="srvrStnTimeout" value="<?php echo $row1[17]; ?>" style="width:100%;">
                                            <?php } else {
                                                ?>
                                                <span><?php echo $row1[17]; ?></span>
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
                                        <label for="srvrStnPGDumpDir" class="control-label col-lg-4">PG Dump Bin Directory:</label>
                                        <div  class="col-lg-8">
                                            <?php if ($canEdtSrvrStng === true) { ?>
                                                <input type="text" class="form-control" aria-label="..." id="srvrStnPGDumpDir" name="srvrStnPGDumpDir" value="<?php echo $row1[13]; ?>" style="width:100%;">
                                            <?php } else {
                                                ?>
                                                <span><?php echo $row1[13]; ?></span>
                                                <?php
                                            }
                                            ?>
                                        </div>
                                    </div>                                                        
                                    <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                        <label for="srvrStnBkpDir" class="control-label col-lg-4">Backup Directory:</label>
                                        <div  class="col-lg-8">
                                            <?php if ($canEdtSrvrStng === true) { ?>
                                                <input type="text" class="form-control" aria-label="..." id="srvrStnBkpDir" name="srvrStnBkpDir" value="<?php echo $row1[14]; ?>" style="width:100%;">
                                            <?php } else {
                                                ?>
                                                <span><?php echo $row1[14]; ?></span>
                                                <?php
                                            }
                                            ?>
                                        </div>
                                    </div> 
                                    <!--<div class="" style="float:right;padding-right:15px !important;">
                                        <button type="button" class="btn btn-primary" onclick="">Backup Database</button>
                                        <button type="button" class="btn btn-primary" onclick="">Restore Database</button>
                                    </div>-->
                                </fieldset>
                            </div>                                    
                        </div>
                        <div class="row">
                            <div  class="col-md-12" style="padding:0px 3px 0px 3px !important;">
                                <fieldset class="basic_person_fs">                                       
                                    <table class="table table-striped table-bordered table-responsive" id="srvrStngSmsApiTable" cellspacing="0" width="100%" style="width:100%;min-width: 700px;">
                                        <thead>
                                            <tr>
                                                <th>No.</th>
                                                <th>Parameter</th>
                                                <th>Value</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $cntr = 0;
                                            while ($cntr < 10) {
                                                $arry1 = explode("|", $row1[18 + $cntr]);
                                                $cntr += 1;
                                                ?>
                                                <tr id="srvrStngSmsApiRow_<?php echo $cntr; ?>" class="hand_cursor">                                    
                                                    <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                                    <td class="lovtd">
                                                        <?php if ($canEdtSrvrStng === true) { ?>
                                                            <input type="text" class="form-control" aria-label="..." id="srvrStngSmsApiRow<?php echo $cntr; ?>_Param" name="srvrStngSmsApiRow<?php echo $cntr; ?>_Param" value="<?php echo str_replace('"', '&quot;', $arry1[0]); ?>" style="width:100%;">

                                                        <?php } else {
                                                            ?>
                                                            <span><?php echo $arry1[0]; ?></span>
                                                            <?php
                                                        }
                                                        ?>
                                                    </td>
                                                    <td class="lovtd">
                                                        <?php if ($canEdtSrvrStng === true) { ?>
                                                            <input type="text" class="form-control" aria-label="..." id="srvrStngSmsApiRow<?php echo $cntr; ?>_Value" name="srvrStngSmsApiRow<?php echo $cntr; ?>_Value" value="<?php echo str_replace('"', '&quot;', $arry1[1]); ?>" style="width:100%;">
                                                        <?php } else {
                                                            ?>
                                                            <span><?php echo $arry1[1]; ?></span>
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
            } else if ($vwtyp == 2) {
                $curIdx = 0;
                $pkID = -1;
                if ($canAddSrvrStng === true) {
                    
                } else {
                    exit();
                }
                ?>
                <div class="row">
                    <div  class="col-md-6" style="padding:0px 3px 0px 3px !important;">
                        <fieldset class="basic_person_fs" style="padding-top:10px !important;"> 
                            <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                <label for="srvrStnSmtpClnt" class="control-label col-lg-4">SMTP Client:</label>
                                <div  class="col-lg-8">
                                    <input type="text" class="form-control" aria-label="..." id="srvrStnSmtpClnt" name="srvrStnSmtpClnt" value="" style="width:100%;">
                                    <input type="hidden" class="form-control" aria-label="..." id="srvrStnSmtpClntID" name="srvrStnSmtpClntID" value="-1">
                                </div>
                            </div>
                            <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                <label for="srvrStnSndrsEmail" class="control-label col-lg-4">SENDER's Email:</label>
                                <div  class="col-lg-8">
                                    <input type="text" class="form-control" aria-label="..." id="srvrStnSndrsEmail" name="srvrStnSndrsEmail" value="" style="width:100%;">
                                </div>
                            </div>
                            <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                <label for="srvrStnSndrsPswd" class="control-label col-lg-4">Password:</label>
                                <div  class="col-lg-8">
                                    <input type="password" class="form-control" aria-label="..." id="srvrStnSndrsPswd" name="srvrStnSndrsPswd" value="" style="width:100%;">
                                </div>
                            </div>
                            <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                <label for="srvrStnSmtPort" class="control-label col-lg-4">Smtp Port No./IP:</label>
                                <div  class="col-lg-3">
                                    <input type="number" min="1" max="9999" class="form-control" aria-label="..." id="srvrStnSmtPort" name="srvrStnSmtPort" value="" style="width:100%;">
                                </div>
                                <div  class="col-lg-5">
                                    <input type="text" class="form-control" aria-label="..." id="srvrStnSmtpIP" name="srvrStnSmtpIP" value="" style="width:100%;">
                                </div>
                            </div>
                            <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                <label for="srvrStnIsDflt" class="control-label col-lg-6">Is Default?:</label>
                                <div  class="col-lg-6">
                                    <?php
                                    $chkdYes = "";
                                    $chkdNo = "checked=\"\"";
                                    ?>                                    
                                    <label class="radio-inline"><input type="radio" name="srvrStnIsDflt" value="YES" <?php echo $chkdYes; ?>>YES</label>
                                    <label class="radio-inline"><input type="radio" name="srvrStnIsDflt" value="NO" <?php echo $chkdNo; ?>>NO</label>
                                </div>
                            </div>
                            <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                <label for="srvrStnDomainNm" class="control-label col-lg-4">Domain Name:</label>
                                <div  class="col-lg-8">
                                    <input type="text" class="form-control" aria-label="..." id="srvrStnDomainNm" name="srvrStnDomainNm" value="" style="width:100%;">
                                </div>
                            </div>
                            <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                <label for="srvrStnFTPUrl" class="control-label col-lg-4">FTP Server Url:</label>
                                <div  class="col-lg-8">
                                    <input type="text" class="form-control" aria-label="..." id="srvrStnFTPUrl" name="srvrStnFTPUrl" value="" style="width:100%;">
                                </div>
                            </div>
                        </fieldset>
                    </div>
                    <div  class="col-md-6" style="padding:0px 3px 0px 3px !important;">
                        <fieldset class="basic_person_fs" style="padding-top:10px !important;">
                            <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                <label for="srvrStnFTPUsrNm" class="control-label col-lg-4">FTP Username:</label>
                                <div  class="col-lg-8">
                                    <input type="text" class="form-control" aria-label="..." id="srvrStnFTPUsrNm" name="srvrStnFTPUsrNm" value="" style="width:100%;">
                                </div>
                            </div>
                            <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                <label for="srvrStnFTPPswd" class="control-label col-lg-4">Password:</label>
                                <div  class="col-lg-8">
                                    <input type="password" class="form-control" aria-label="..." id="srvrStnFTPPswd" name="srvrStnFTPPswd" value="" style="width:100%;">
                                </div>
                            </div>
                            <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                <label for="srvrStnFTPPort" class="control-label col-lg-8">FTP Port:</label>
                                <div  class="col-lg-4">
                                    <input type="number" min="1" max="99999" class="form-control" aria-label="..." id="srvrStnFTPPort" name="srvrStnFTPPort" value="" style="width:100%;">
                                </div>
                            </div>                                                        
                            <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                <label for="srvrStnFTPStrtDir" class="control-label col-lg-4">FTP User's Home Directory:</label>
                                <div  class="col-lg-8">
                                    <input type="text" class="form-control" aria-label="..." id="srvrStnFTPStrtDir" name="srvrStnFTPStrtDir" value="" style="width:100%;">
                                </div>
                            </div>                                                        
                            <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                <label for="srvrStnFTPBaseDir" class="control-label col-lg-4">Base Subdirectory URL:</label>
                                <div  class="col-lg-8">
                                    <input type="text" class="form-control" aria-label="..." id="srvrStnFTPBaseDir" name="srvrStnFTPBaseDir" value="" style="width:100%;">
                                </div>
                            </div>                                                        
                            <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                <label for="srvrStnEnforceFTP" class="control-label col-lg-6">Enforce FTP?:</label>
                                <div  class="col-lg-6">
                                    <?php
                                    $chkdYes = "";
                                    $chkdNo = "checked=\"\"";
                                    ?>
                                    <label class="radio-inline"><input type="radio" name="srvrStnEnforceFTP" value="YES" <?php echo $chkdYes; ?>>YES</label>
                                    <label class="radio-inline"><input type="radio" name="srvrStnEnforceFTP" value="NO" <?php echo $chkdNo; ?>>NO</label>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                </div>
                <div class="row">
                    <div  class="col-md-6" style="padding:0px 3px 0px 3px !important;">
                        <fieldset class="basic_person_fs" style="padding-top:10px !important;">
                            <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                <label for="srvrStnComPort" class="control-label col-lg-8">Modem Settings COM Port:</label>
                                <div  class="col-lg-4">
                                    <input type="number" min="1" max="9999" class="form-control" aria-label="..." id="srvrStnComPort" name="srvrStnComPort" value="" style="width:100%;">
                                </div>
                            </div>
                            <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                <label for="srvrStnBaudRate" class="control-label col-lg-8">Modem Settings BAUD Rate:</label>
                                <div  class="col-lg-4">
                                    <input type="number" min="1" max="999999" class="form-control" aria-label="..." id="srvrStnBaudRate" name="srvrStnBaudRate" value="" style="width:100%;">
                                </div>
                            </div>
                            <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                <label for="srvrStnTimeout" class="control-label col-lg-8">Blocked Old Passwords:</label>
                                <div  class="col-lg-4">
                                    <input type="number" min="0" max="9999" class="form-control" aria-label="..." id="srvrStnTimeout" name="srvrStnTimeout" value="" style="width:100%;">
                                </div>
                            </div>
                        </fieldset>
                    </div>
                    <div  class="col-md-6" style="padding:0px 3px 0px 3px !important;">
                        <fieldset class="basic_person_fs" style="padding-top:15px !important;">                                                       
                            <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                <label for="srvrStnPGDumpDir" class="control-label col-lg-4">PG Dump Bin Directory:</label>
                                <div  class="col-lg-8">
                                    <input type="text" class="form-control" aria-label="..." id="srvrStnPGDumpDir" name="srvrStnPGDumpDir" value="" style="width:100%;">
                                </div>
                            </div>                                                        
                            <div class="form-group form-group-sm col-md-12" style="padding:0px 3px 0px 3px !important;">
                                <label for="srvrStnBkpDir" class="control-label col-lg-4">Backup Directory:</label>
                                <div  class="col-lg-8">
                                    <input type="text" class="form-control" aria-label="..." id="srvrStnBkpDir" name="srvrStnBkpDir" value="" style="width:100%;">
                                </div>
                            </div> 
                        </fieldset>
                    </div>                                    
                </div>
                <div class="row">
                    <div  class="col-md-12" style="padding:0px 3px 0px 3px !important;">
                        <fieldset class="basic_person_fs">                                       
                            <table class="table table-striped table-bordered table-responsive" id="srvrStngSmsApiTable" cellspacing="0" width="100%" style="width:100%;min-width: 700px;">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Parameter</th>
                                        <th>Value</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $cntr = 0;
                                    while ($cntr < 10) {
                                        $cntr += 1;
                                        ?>
                                        <tr id="srvrStngSmsApiRow_<?php echo $cntr; ?>" class="hand_cursor">                                    
                                            <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                            <td class="lovtd">
                                                <input type="text" class="form-control" aria-label="..." id="srvrStngSmsApiRow<?php echo $cntr; ?>_Param" name="srvrStngSmsApiRow<?php echo $cntr; ?>_Param" value="" style="width:100%;">
                                            </td>
                                            <td class="lovtd">
                                                <input type="text" class="form-control" aria-label="..." id="srvrStngSmsApiRow<?php echo $cntr; ?>_Value" name="srvrStngSmsApiRow<?php echo $cntr; ?>_Value" value="" style="width:100%;">
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