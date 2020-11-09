<?php
if (array_key_exists('lgn_num', get_defined_vars())) {
    $prsnid = $_SESSION['PRSN_ID'];
    $prsnID = $_SESSION['PRSN_ID'];
    $orgID = $_SESSION['ORG_ID'];
    $usrID = $_SESSION['USRID'];
    $lnkdFirmID = getGnrlRecNm("prs.prsn_names_nos", "person_id", "lnkd_firm_org_id", $prsnid);
    $pkID = $custID;

    $prsnBranchID = get_Person_BranchID($prsnid);
    $prsnBranch = getGnrlRecNm("org.org_sites_locations", "location_id", "site_desc||' ('||location_code_name||')'",
            "pasn.get_prsn_siteid($prsnid)");
    $rowATZ0 = ""; $rowATZ1 = ""; $rowATZ2 = "";$rowATZ3 = "";$rowATZ4 = "";$rowATZ5 = "";$rowATZ6 = "";$rowATZ7 = "";$rowATZ8 = "";$rowATZ9 = "";$rowATZ10 = "";
    $rowATZ11 = "";$rowATZ12 = "";$rowATZ13 = "";$rowATZ14 = "";$rowATZ15 = "";$rowATZ16 = "";$rowATZ17 = "";$rowATZ18 = "";$rowATZ19 = "";$rowATZ19 = "";
    $rowATZ20 = "";$rowATZ21 = "";$rowATZ22 = "";$rowATZ23 = "";$rowATZ24 = "";$rowATZ25 = "";$rowATZ26 = "";$rowATZ27 = "";$rowATZ28 = "";$rowATZ29 = "";
    $rowATZ30 = "";$rowATZ31 = "";$rowATZ32 = "";$rowATZ33 = "";$rowATZ34 = "";$rowATZ35 = "";
    $v_BranchATZ = "";


    if ($subPgNo == 1.1) {//INDIVIDUAL CUSTOMER
        $trnsType = "Individual Customers";
        $formTitle = "Individual Customers - Attachments";
        if ($vwtyp == "0") {
            /* BASIC DATA */
            if ($vwtypActn == "EDIT") {
                /* Read Only */
                $tblNm1 = "mcf.mcf_customers_ind";
                $tblNm2 = "mcf.mcf_customers";

                $cnt = getCustDataChngPndngCount($pkID);
                if($cnt > 0){
                    $tblNm1 = "mcf.mcf_customers_ind_hstrc";
                    $tblNm2 = "mcf.mcf_customers_hstrc";        
                }                
                
                $result = get_IndCustDet($pkID, $tblNm1, $tblNm2);
                $shwHydNtlntySts = "style=\"display:none !important;\"";
                
                $picPath = "Picture/";
                $signPath = "Signature/";
                while ($row = loc_db_fetch_array($result)) {
                    $imgLoc = $row[2];
                    $temp1 = explode(".", $imgLoc);
                    $extension1 = end($temp1);
                    if (strlen(trim($extension1)) <= 0) {
                        $extension1 = "png";
                    }
                    $nwFileName1 = encrypt1($imgLoc, $smplTokenWord1) . "." . $extension1;
                    $ftp_src = $ftp_base_db_fldr . "/Mcf/Customers/" . $picPath . $imgLoc;
                    $fullPemDest = $fldrPrfx . $tmpDest . $nwFileName1;
                    if (file_exists($ftp_src) && is_dir($ftp_src) === FALSE) {
                        copy("$ftp_src", "$fullPemDest");
                    } else if (!file_exists($fullPemDest)) {
                        $ftp_src = $fldrPrfx . 'cmn_images/image_up.png';
                        copy("$ftp_src", "$fullPemDest");
                    }
                    $nwFileName1 = $tmpDest . $nwFileName1;

                    $imgLoc = $row[31];
                    $temp2 = explode(".", $imgLoc);
                    $extension2 = end($temp2);
                    if (strlen(trim($extension2)) <= 0) {
                        $extension2 = "png";
                    }
                    $nwFileName2 = encrypt1($imgLoc, $smplTokenWord1) . "." . $extension2;
                    $ftp_src = $ftp_base_db_fldr . "/Mcf/Customers/" . $signPath . $imgLoc;
                    $fullPemDest = $fldrPrfx . $tmpDest . $nwFileName2;
                    if (file_exists($ftp_src) && is_dir($ftp_src) === FALSE) {
                        copy("$ftp_src", "$fullPemDest");
                    } else if (!file_exists($fullPemDest)) {
                        $ftp_src = $fldrPrfx . 'cmn_images/no_image.png';
                        copy("$ftp_src", "$fullPemDest");
                    }
                    $nwFileName2 = $tmpDest . $nwFileName2;
                    $trnsStatus = $row[24];
                    $v_Branch = getGnrlRecNm("org.org_sites_locations", "location_id", "site_desc||' ('||location_code_name||')' ", $row[25]);
                    $rqstatusColor = "red";
                    $mkReadOnly = "";
                    $mkReadOnlyDsbld = "";
                    
		    if($trnsStatus == "Initiated" || $trnsStatus == "Approved" || $trnsStatus == "Unauthorized" || $trnsStatus == "Authorized"){
                        $mkReadOnly = "readonly=\"readonly\"";
                        $mkReadOnlyDsbld = "disabled=\"true\"";
                    }        
                    if($trnsStatus == "Unauthorized" || $trnsStatus == "Initiated"){
                        $shwHydNtlntySts = "style=\"display:block !important;\"";
                    }
                    
                    $tblNmCustIndAuthrzd = "";
                    $tblNmCustAuthrzd = ""; 
                    $lblColor = "red";
                    $resultAuthrzd = "";

                    //if($trnsStatus == "Unauthorized" || $trnsStatus == "Initiated"){
                    if($cnt > 0){
                        $tblNmCustIndAuthrzd = "mcf.mcf_customers_ind";
                        $tblNmCustAuthrzd = "mcf.mcf_customers";        
                        $resultAuthrzd = get_IndCustDet($pkID, $tblNmCustIndAuthrzd, $tblNmCustAuthrzd);
                        while ($rowATZ = loc_db_fetch_array($resultAuthrzd)) {
                            $rowATZ0 = $rowATZ[0]; $rowATZ1 = $rowATZ[1]; $rowATZ2 = $rowATZ[2]; $rowATZ3 = $rowATZ[3]; $rowATZ4 = $rowATZ[4];
                            $rowATZ5 = $rowATZ[5]; $rowATZ6 = $rowATZ[6]; $rowATZ7 = $rowATZ[7]; $rowATZ8 = $rowATZ[8]; $rowATZ9 = $rowATZ[9];
                            $rowATZ10 = $rowATZ[10]; $rowATZ11 = $rowATZ[11]; $rowATZ12 = $rowATZ[12]; $rowATZ13 = $rowATZ[13]; $rowATZ14 = $rowATZ[14];
                            $rowATZ15 = $rowATZ[15]; $rowATZ16 = $rowATZ[16]; $rowATZ17 = $rowATZ[17]; $rowATZ18 = $rowATZ[18]; $rowATZ19 = $rowATZ[19];
                            $rowATZ20 = $rowATZ[20]; $rowATZ21 = $rowATZ[21]; $rowATZ22 = $rowATZ[22]; $rowATZ23 = $rowATZ[23]; $rowATZ24 = $rowATZ[24];
                            $rowATZ25 = $rowATZ[25]; $rowATZ26 = $rowATZ[26]; $rowATZ27 = $rowATZ[27]; $rowATZ28 = $rowATZ[28]; $rowATZ29 = $rowATZ[29];
                            $rowATZ30 = $rowATZ[30]; $rowATZ31 = $rowATZ[31]; $rowATZ32 = $rowATZ[32];
    //                        $rowATZ33 = $rowATZ[33]; $rowATZ34 = $rowATZ[34];$rowATZ35 = $rowATZ[35];
    //                        $rowATZ1 = $rowATZ[1]; $rowATZ2 = $rowATZ[2]; $rowATZ3 = $rowATZ[3]; $rowATZ4 = $rowATZ[4];
    //                        $rowATZ0 = $rowATZ[0]; $rowATZ1 = $rowATZ[1]; $rowATZ2 = $rowATZ[2]; $rowATZ3 = $rowATZ[3]; $rowATZ4 = $rowATZ[4];
                            
                            $v_BranchATZ = getGnrlRecNm("org.org_sites_locations", "location_id", "site_desc||' ('||location_code_name||')' ", $rowATZ25);
                        }               
                    }
                    
                    
                    ?>
                    <input class="form-control" id="addOrEditForm" type = "hidden" placeholder="addOrEditForm" value="Edit"/>
                    <div class="row" style="margin: 0px 0px 10px 0px !important;">
                        <div class="col-md-12" style="padding:0px 0px 0px 0px !important;">
                            <button type="button" class="btn btn-default btn-sm phone-only-btn" onclick="openATab('#allmodules', 'grp=17&typ=1&pg=10&vtyp=0');">Basic Data</button>
                            <button type="button" class="btn btn-default btn-sm phone-only-btn" onclick="openATab('#prfBCOPAddPrsnDataEDT', 'grp=17&typ=1&pg=10&vtyp=1');">Additional Data</button>
                            <!--<button type="button" class="btn btn-default btn-sm phone-only-btn" onclick="openATab('#prflBCOPAttchmntsEDT', 'grp=17&typ=1&pg=10&vtyp=2');">Attachments</button>-->
                        </div>
                    </div>
                    <div class="">
                        <div class="row" style="margin: 0px 0px 10px 0px !important;">
                            <div class="col-md-12" style="padding:0px 1px 5px 1px !important;">
                                <div style="float:left;">
                                        <button type="button" class="btn btn-default btn-sm" style="height:30px;" id="myVmsTrnsStatusBtn">
                                                <span style="font-weight:bold;">Status: </span><span style="color:<?php echo $rqstatusColor; ?>;font-weight: bold;"><?php echo $trnsStatus; ?></span>
                                        </button>
                                        <?php if($vwtypActn != "VIEW") { ?>
                                        <button type="button" class="btn btn-default btn-sm" style="height:30px;" onclick="getCustomersForm('myFormsModalLg', 'myFormsModalBodyLg', 'myFormsModalTitleLg', 'Edit Customer Profile', 11, 1.1, 0, 'EDIT', <?php echo $pkID; ?> , '', 'indCustTableRow1');" data-toggle="tooltip" title="Reload Transaction">
                                                <img src="cmn_images/refresh.bmp" style="padding-right: 2px; height:17px; width:auto; position: relative; vertical-align: middle;">
                                        </button>
                                        <?php } ?>
                                        <button type="button" class="btn btn-default" style="height:30px;" onclick="getOneMcfDocsForm_Gnrl(<?php echo $pkID; ?>,'<?php echo $trnsType; ?>', 140, '<?php echo $formTitle; ?>');" data-toggle="tooltip" data-placement="bottom" title = "Attached Documents">
                                            <img src="cmn_images/adjunto.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                        </button>                                                    
                                </div>
                                <div class="" style="float:right;">
                                    <?php
                                    $bioUserID = isBioDataPrsnt($pkID, "IND");
                                    if ($bioUserID <= 0) {
                                        createBioData($pkID, "IND");
                                    }
                                    $bioUser = getBioData($pkID, "IND");
                                    $bioData = "";
                                    foreach ($bioUser as $rowBio) {
                                        $finger = getUserBioFinger($rowBio['user_id']);
                                        $register = '';
                                        $verification = '';
                                        $deleteBio = '';
                                        $url_register = base64_encode($bio_base_path . "register.php?user_id=" . $rowBio['user_id']);
                                        $url_verification = base64_encode($bio_base_path . "verification.php?user_id=" . $rowBio['user_id']);
                                        if (count($finger) == 0) {
                                            $register = "<a href='finspot:FingerspotReg;$url_register' class='btn btn-xs btn-primary' onclick=\"user_register('" . $rowBio['user_id'] . "','" . $rowBio['user_name'] . "',$pkID)\">Capture Biometric</a>";
                                        } else {
                                            $verification = "<a href='finspot:FingerspotVer;$url_verification' class='btn btn-sm btn-success'>Verify Biometric</a>";
                                            $deleteBio = "<button type=\"button\" class=\"btn btn-sm btn-danger\" onclick=\"user_delete('" . $rowBio['user_id'] . "','" . $rowBio['user_name'] . "',$pkID);\" style=\"margin-left:5px;height:28px;\">Delete</button>";
                                        }
                                        $bioData = "<code id='user_finger_" . $rowBio['user_id'] . "' style=\"display:none;\">" . count($finger) . "</code>"
                                                . "$register"
                                                . "$verification"
                                                . "$deleteBio";
                                    }
                                    echo $bioData;
                                    ?>
                                    <?php if ($trnsStatus == "Authorized" || $trnsStatus == "Approved" && (test_prmssns($dfltPrvldgs[61], $mdlNm) === true)) { ?>
                                    <button type="button" class="btn btn-default btn-sm" style="" onclick="modifyAutrzCustDataRqst(<?php echo $pgNo; ?>, <?php echo $subPgNo; ?>, <?php echo $vwtyp; ?>);">
                                        <img src="cmn_images/edit32.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">
                                        MODIFY DATA
                                    </button>
                                     <?php } else if ($trnsStatus == "Incomplete" || $trnsStatus == "Rejected" || $trnsStatus == "Withdrawn") { ?>  
                                    <button type="button" class="btn btn-default btn-sm" style="" onclick="saveBasicData(<?php echo $pgNo; ?>, <?php echo $subPgNo; ?>, <?php echo $vwtyp; ?>, 0);">
                                        <img src="cmn_images/FloppyDisk.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">
                                        SAVE
                                    </button>
                                    <button type="button" class="btn btn-default btn-sm" style="" onclick="saveBasicData(<?php echo $pgNo; ?>, <?php echo $subPgNo; ?>, <?php echo $vwtyp; ?>, 1);">
                                        <img src="cmn_images/Emailcon.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">
                                        SUBMIT
                                    </button>                                    
                                     <?php } else if ($trnsStatus == "Unauthorized") { ?>    
                                        <?php if (didAuthorizerSubmit($pkID, $usrID)  && $vwtypActn != "VIEW") { ?>
                                        <button type="button" class="btn btn-default btn-sm" style="height:30px;" onclick="wthdrwRjctAutrzCustDataRqst('WITHDRAW', <?php echo $pgNo; ?>, <?php echo $subPgNo; ?>, <?php echo $vwtyp; ?>);"><img src="cmn_images/withdraw_rqst.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Withdraw&nbsp;</button>                                                                          
                                        <?php } } ?>                                    
                                </div>
                            </div>   
                        </div>                               
                        <ul class="nav nav-tabs rho-hideable-tabs" style="margin-top:-10px !important;">
                            <li class="active"><a data-toggle="tab" data-rhodata="&pg=10&vtyp=0" href="#prflHomeBCOPEDT" id="prflBCHomeEDTtab">Basic Data</a></li>
                            <li><a data-toggle="tabajxprfledt" data-rhodata="&pg=10&vtyp=1" href="#prfBCOPAddPrsnDataEDT" onclick="openATab('#prfBCOPAddPrsnDataEDT', 'grp=17&typ=1&pg=<?php echo $pgNo; ?>&subPgNo=1.6&vtyp=1&vtypActn=EDIT&custID=<?php echo $custID; ?>&formType=Personal Customer&rvsnTtlAPD=<?php echo $row[32]; ?>');" id="prfBCOPAddPrsnDataEDTtab">Additional Data</a></li>
                            <!--<li><a data-toggle="tabajxprfledt" data-rhodata="&pg=10&vtyp=2" href="#prflBCOPAttchmntsEDT" onclick="openATab('#prflBCOPAttchmntsEDT', 'grp=17&typ=1&pg=<?php echo $pgNo; ?>&subPgNo=<?php echo $subPgNo; ?>&vtyp=2&vtypActn=EDIT&custID=<?php echo $custID; ?>');" id="prflBCOPAttchmntsEDTtab">Attachments</a></li>-->
                        </ul>
                        <div class="row">                  
                            <div class="col-md-12">
                                <div class="custDiv"> 
                                    <div class="tab-content">
                                        <div id="prflHomeBCOPEDT" class="tab-pane fadein active" style="border:none !important;">                                        
                                            <form class="form-horizontal">
                                                <div class="row">
                                                    <div class="col-lg-4">
                                                        <fieldset class="basic_person_fs1">
                                                            <legend class="basic_person_lg">Person's Picture</legend>
                                                            <div style="margin-bottom: 10px;">
                                                                <img src="<?php echo $nwFileName1; ?>" alt="..." id="img1Test" class="img-rounded center-block img-responsive" style="height: 195px !important; width: auto !important;">                                            
                                                            </div>
                                                            <div class="form-group form-group-sm">
                                                                <div class="col-md-12">
                                                                    <div class="input-group">
                                                                        <label class="btn btn-primary btn-file input-group-addon">
                                                                            Browse... <input type="file" id="custPicture" name="custPicture" onchange="changeImgSrc(this, '#img1Test', '#img1SrcLoc');" class="btn btn-default"  style="display: none;">
                                                                        </label>
                                                                        <input type="text" class="form-control" aria-label="..." id="img1SrcLoc" value="">                                                        
                                                                    </div>                                                    
                                                                </div>                                            
                                                            </div>                                        
                                                        </fieldset>
                                                    </div>                                
                                                    <div class="col-lg-4">
                                                        <fieldset class="basic_person_fs1">
                                                            <legend class="basic_person_lg">Names</legend>
                                                            <div class="form-group form-group-sm">
                                                                <?php if($cnt > 0 && $row[1] != $rowATZ1) { ?>
                                                                <label data-toggle="tooltip" title="<?php echo $rowATZ1; ?>" for="idNo" class="control-label col-md-4"><a href="#" style="color:red;" onclick="dsplyAuthrzdData('<?php echo $rowATZ1; ?>');">ID No:</a></label>
                                                                <?php } else { ?>
                                                                    <label for="idNo" class="control-label col-md-4">ID No:</label>
                                                                <?php } ?>
                                                                <div class="col-md-8">
                                                                    <input <?php echo $mkReadOnly; ?> class="form-control" id="idNo" type = "text" placeholder="ID No" value="<?php echo $row[1]; ?>"/> 
                                                                    <input class="form-control" id="profileID" type = "hidden" placeholder="Profile ID" value="<?php echo $row[0]; ?>"/>
                                                                    <input class="form-control" id="rvsnTtl" type = "hidden" placeholder="Revision Total" value="<?php echo $row[32]; ?>"/>
                                                                    <input class="form-control" id="custID" type = "hidden" placeholder="Customer ID" value="<?php echo $row[28]; ?>"/>                                                                    
                                                                </div>
                                                            </div> 
                                                            <div class="form-group form-group-sm">
                                                                <?php if($cnt > 0 && $row[3] != $rowATZ3) { ?>
                                                                <label data-toggle="tooltip" title="<?php echo $rowATZ3; ?>" for="title" class="control-label col-md-4"><a href="#" style="color:red;" onclick="dsplyAuthrzdData('<?php echo $rowATZ3; ?>');">Title:</a></label>
                                                                <?php } else { ?>
                                                                    <label  for="title" class="control-label col-md-4">Title:</label>
                                                                <?php } ?>
                                                                <div  class="col-md-8">
                                                                    <select <?php echo $mkReadOnlyDsbld; ?> class="form-control rqrdFld" id="title">
                                                                        <?php
                                                                        $brghtStr = "";
                                                                        $isDynmyc = FALSE;
                                                                        $titleRslt = getLovValues("%", "Both", 0, 100, $brghtStr,
                                                                                getLovID("Person Titles"), $isDynmyc, -1, "", "");
                                                                        while ($titleRow = loc_db_fetch_array($titleRslt)) {
                                                                            $selectedTxt = "";
                                                                            if ($titleRow[0] == $row[3]) {
                                                                                $selectedTxt = "selected";
                                                                            }
                                                                            ?>
                                                                            <option value="<?php echo $titleRow[0]; ?>" <?php echo $selectedTxt; ?>><?php echo $titleRow[0]; ?></option>
                                                                            <?php
                                                                        }
                                                                        ?>
                                                                    </select>
                                                                </div>
                                                            </div>  
                                                            <div class="form-group form-group-sm">
                                                                <?php if($cnt > 0 && $row[4] != $rowATZ4) { ?>
                                                                <label data-toggle="tooltip" title="<?php echo $rowATZ4; ?>" for="firstName" class="control-label col-md-4"><a href="#" style="color:red;" onclick="dsplyAuthrzdData('<?php echo $rowATZ4; ?>');">First Name:</a></label>
                                                                <?php } else { ?>
                                                                <label for="firstName" class="control-label col-md-4">First Name:</label>
                                                                <?php }?>
                                                                <div  class="col-md-8">
                                                                    <input <?php echo $mkReadOnly; ?> class="form-control rqrdFld" id="firstName" type = "text" placeholder="First Name" value="<?php echo $row[4]; ?>"/>
                                                                </div>
                                                            </div> 
                                                            <div class="form-group form-group-sm">
                                                                <?php if($cnt > 0 && $row[5] != $rowATZ5) { ?>
                                                                <label data-toggle="tooltip" title="<?php echo $rowATZ5; ?>" for="surName" class="control-label col-md-4"><a href="#" style="color:red;" onclick="dsplyAuthrzdData('<?php echo $rowATZ5; ?>');">Surname:</a></label>
                                                                <?php } else { ?>
                                                                <label for="surName" class="control-label col-md-4">Surname:</label>
                                                                <?php } ?>
                                                                <div  class="col-md-8">
                                                                    <input <?php echo $mkReadOnly; ?> class="form-control rqrdFld" id="surName" type = "text" placeholder="Surname" value="<?php echo $row[5]; ?>"/>
                                                                </div>
                                                            </div>     
                                                            <div class="form-group form-group-sm">
                                                                <?php if($cnt > 0 && $row[6] != $rowATZ6) { ?>
                                                                <label data-toggle="tooltip" title="<?php echo $rowAT6; ?>" for="otherNames" class="control-label col-md-4"><a href="#" style="color:red;" onclick="dsplyAuthrzdData('<?php echo $rowATZ6; ?>');">Other Names:</a></label>
                                                                <?php } else { ?>
                                                                <label for="otherNames" class="control-label col-md-4">Other Names:</label>
                                                                <?php } ?>
                                                                <div  class="col-md-8">
                                                                    <textarea <?php echo $mkReadOnly; ?> class="form-control" id="otherNames" cols="2" placeholder="Other Names" rows="3"><?php echo $row[6]; ?></textarea>
                                                                </div>
                                                            </div>
                                                            <div class="form-group form-group-sm">
                                                                <?php if($cnt > 0 && $row[8] != $rowATZ8) { ?>
                                                                <label data-toggle="tooltip" title="<?php echo $rowATZ8; ?>" for="gender" class="control-label col-md-4"><a href="#" style="color:red;" onclick="dsplyAuthrzdData('<?php echo $rowATZ8; ?>');">Gender:</a></label>
                                                                <?php } else { ?>
                                                                <label for="gender" class="control-label col-md-4">Gender:</label>
                                                                <?php } ?>
                                                                <div  class="col-md-8">
                                                                    <select <?php echo $mkReadOnlyDsbld; ?> class="form-control rqrdFld" id="gender" >
                                                                        <?php
                                                                        $brghtStr = "";
                                                                        $isDynmyc = FALSE;
                                                                        $titleRslt = getLovValues("%", "Both", 0, 100, $brghtStr,
                                                                                getLovID("Gender"), $isDynmyc, -1, "", "");
                                                                        while ($titleRow = loc_db_fetch_array($titleRslt)) {
                                                                            $selectedTxt = "";
                                                                            if ($titleRow[0] == $row[8]) {
                                                                                $selectedTxt = "selected";
                                                                            }
                                                                            ?>
                                                                            <option value="<?php echo $titleRow[0]; ?>" <?php echo $selectedTxt; ?>><?php echo $titleRow[0]; ?></option>
                                                                            <?php
                                                                        }
                                                                        ?>
                                                                    </select>
                                                                </div>
                                                            </div> 
                                                        </fieldset>
                                                    </div>
                                                    <div class="col-lg-4"> 
                                                        <fieldset class="basic_person_fs1"><legend class="basic_person_lg">Personal Data</legend>
                                                            <div class="form-group form-group-sm">
                                                                <?php if($cnt > 0 && $row[9] != $rowATZ9) { ?>
                                                                <label data-toggle="tooltip" title="<?php echo $rowATZ9; ?>" for="maritalStatus" class="control-label col-md-4"><a href="#" style="color:red;" onclick="dsplyAuthrzdData('<?php echo $rowATZ9; ?>');">Marital Status:</a></label>
                                                                <?php } else { ?>
                                                                <label for="maritalStatus" class="control-label col-md-4">Marital Status:</label>
                                                                <?php } ?>
                                                                <div  class="col-md-8">
                                                                    <select <?php echo $mkReadOnlyDsbld; ?> class="form-control rqrdFld" id="maritalStatus" >
                                                                        <?php
                                                                        $brghtStr = "";
                                                                        $isDynmyc = FALSE;
                                                                        $titleRslt = getLovValues("%", "Both", 0, 100, $brghtStr,
                                                                                getLovID("Marital Status"), $isDynmyc, -1, "", "");
                                                                        while ($titleRow = loc_db_fetch_array($titleRslt)) {
                                                                            $selectedTxt = "";
                                                                            if ($titleRow[0] == $row[9]) {
                                                                                $selectedTxt = "selected";
                                                                            }
                                                                            ?>
                                                                            <option value="<?php echo $titleRow[0]; ?>" <?php echo $selectedTxt; ?>><?php echo $titleRow[0]; ?></option>
                                                                            <?php
                                                                        }
                                                                        ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="form-group form-group-sm">
                                                                <?php if($cnt > 0 && $row[10] != $rowATZ10) { ?>
                                                                <label data-toggle="tooltip" title="<?php echo $rowATZ10; ?>" for="dob" class="control-label col-md-4"><a href="#" style="color:red;" onclick="dsplyAuthrzdData('<?php echo $rowATZ10; ?>');">Date of Birth:</a></label>
                                                                <?php } else { ?>
                                                                <label for="dob" class="control-label col-md-4">Date of Birth:</label>
                                                                <?php } ?>
                                                                <div class="col-md-8">
                                                                    <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                                                        <input class="form-control rqrdFld" size="16" type="text" id="dob" value="<?php echo $row[10]; ?>" readonly="">
                                                                        <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                                                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                                    </div>
                                                                </div>
                                                            </div> 
                                                            <div class="form-group form-group-sm">
                                                                <?php if($cnt > 0 && $row[11] != $rowATZ11) { ?>
                                                                <label data-toggle="tooltip" title="<?php echo $rowATZ11; ?>" for="pob" class="control-label col-md-4"><a href="#" style="color:red;" onclick="dsplyAuthrzdData('<?php echo $rowATZ11; ?>');">Place of Birth:</a></label>
                                                                <?php } else { ?>
                                                                <label for="pob" class="control-label col-md-4">Place of Birth:</label>
                                                                <?php } ?>
                                                                <div  class="col-md-8">
                                                                    <textarea <?php echo $mkReadOnly; ?> class="form-control" id="pob" cols="2" placeholder="Place of Birth" rows="2"><?php echo $row[11]; ?></textarea>
                                                                </div>
                                                            </div> 
                                                            <div class="form-group form-group-sm">
                                                                <?php if($cnt > 0 && $row[20] != $rowATZ20) { ?>
                                                                <label data-toggle="tooltip" title="<?php echo $rowATZ20; ?>" for="nationality" class="control-label col-md-4"><a href="#" style="color:red;" onclick="dsplyAuthrzdData('<?php echo $rowATZ20; ?>');">Nationality:</a></label>
                                                                <?php } else { ?>
                                                                <label for="nationality" class="control-label col-md-4">Nationality:</label>
                                                                <?php } ?>
                                                                <div class="col-md-8">
                                                                    <select <?php echo $mkReadOnlyDsbld; ?> class="form-control rqrdFld" id="nationality" >
                                                                        <?php
                                                                        $brghtStr = "";
                                                                        $isDynmyc = FALSE;
                                                                        $titleRslt = getLovValues("%", "Both", 0, 100, $brghtStr,
                                                                                getLovID("Nationalities"), $isDynmyc, -1, "", "");
                                                                        while ($titleRow = loc_db_fetch_array($titleRslt)) {
                                                                            $selectedTxt = "";
                                                                            if ($titleRow[0] == $row[20]) {
                                                                                $selectedTxt = "selected";
                                                                            }
                                                                            ?>
                                                                            <option value="<?php echo $titleRow[0]; ?>" <?php echo $selectedTxt; ?>><?php echo $titleRow[0]; ?></option>
                                                                            <?php
                                                                        }
                                                                        ?>
                                                                    </select>
                                                                </div>
                                                            </div>  
                                                            <div class="form-group form-group-sm">
                                                                <?php if($cnt > 0 && $row[19] != $rowATZ19) { ?>
                                                                <label data-toggle="tooltip" title="<?php echo $rowATZ19; ?>" for="homeTown" class="control-label col-md-4"><a href="#" style="color:red;" onclick="dsplyAuthrzdData('<?php echo $rowATZ19; ?>');">Home Town:</a></label>
                                                                <?php } else { ?>
                                                                <label for="homeTown" class="control-label col-md-4">Home Town:</label>
                                                                <?php } ?>
                                                                <div  class="col-md-8">
                                                                    <textarea <?php echo $mkReadOnly; ?> class="form-control" id="homeTown" cols="2" placeholder="Home Town" rows="1"><?php echo $row[19]; ?></textarea>
                                                                </div>
                                                            </div> 
                                                            <div class="form-group form-group-sm">
                                                                <?php if($cnt > 0 && $row[12] != $rowATZ12) { ?>
                                                                <label data-toggle="tooltip" title="<?php echo $rowATZ12; ?>" for="religion" class="control-label col-md-4"><a href="#" style="color:red;" onclick="dsplyAuthrzdData('<?php echo $rowATZ12; ?>');">Religion:</a></label>
                                                                <?php } else { ?>
                                                                <label for="religion" class="control-label col-md-4">Religion:</label>
                                                                <?php } ?>
                                                                <div  class="col-md-8">
                                                                    <input <?php echo $mkReadOnly; ?> class="form-control" id="religion" type = "text" placeholder="Religion" value="<?php echo $row[12]; ?>"/>
                                                                </div>
                                                            </div>                                              
                                                        </fieldset>   
                                                    </div>
                                                </div>    
                                                <div class="row"><!-- ROW 1 6154-->
                                                    <div class="col-lg-4">
                                                        <fieldset class="basic_person_fs2"><legend class="basic_person_lg">Signature/Thumbprint</legend>
                                                            <div style="margin-bottom: 10px;">
                                                                <img src="<?php echo $nwFileName2; ?>" alt="..." id="img2Test" class="img-rounded center-block img-responsive" style="height: 195px !important; width: auto !important;">                                            
                                                            </div>
                                                            <div class="form-group form-group-sm">
                                                                <div class="col-md-12">
                                                                    <div class="input-group">
                                                                        <label class="btn btn-primary btn-file input-group-addon">
                                                                            Browse... <input <?php echo $mkReadOnly; ?> type="file" id="signPicture" name="signPicture" onchange="changeImgSrc(this, '#img2Test', '#img2SrcLoc');" class="btn btn-default"  style="display: none;">
                                                                        </label>
                                                                        <input type="text" class="form-control" aria-label="..." id="img2SrcLoc" value="">                                                        
                                                                    </div>                                                    
                                                                </div>                                            
                                                            </div>                                     
                                                        </fieldset>
                                                    </div>                                
                                                    <div class="col-lg-4">
                                                        <fieldset class="basic_person_fs2"><legend class="basic_person_lg">Contact Information</legend>                                                           
                                                            <div class="form-group form-group-sm">
                                                                <?php if($cnt > 0 && $row[21] != $rowATZ21) { ?>
                                                                <label data-toggle="tooltip" title="<?php echo $rowATZ21; ?>" for="lnkdFirmName" class="control-label col-md-4"><a href="#" style="color:red;" onclick="dsplyAuthrzdData('<?php echo $rowATZ21; ?>');">Linked Firm/ Workplace:</a></label>
                                                                <?php } else { ?>
                                                                <label for="lnkdFirmName" class="control-label col-md-4">Linked Firm/ Workplace:</label>
                                                                <?php } ?>
                                                                <div  class="col-md-8">
                                                                    <div class="input-group">
                                                                        <input <?php echo $mkReadOnly; ?> type="text" class="form-control rqrdFld" aria-label="..." id="lnkdFirmName" value="<?php echo $row[21]; ?>"/>
                                                                        <input type="hidden" id="gnrlOrgID" value="<?php echo $orgID; ?>">
                                                                        <input type="hidden" id="lnkdFirmID" value="<?php echo $row[29]; ?>">
                                                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Customers and Suppliers', 'gnrlOrgID', '', '', 'radio', true, '<?php echo $row[21]; ?>', 'lnkdFirmID', 'lnkdFirmName', 'clear', 1, '');">
                                                                            <span class="glyphicon glyphicon-th-list"></span>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="form-group form-group-sm">
                                                                <?php if($cnt > 0 && $row[22] != $rowATZ22) { ?>
                                                                <label data-toggle="tooltip" title="<?php echo $rowATZ22; ?>" for="lnkdFirmLoc" class="control-label col-md-4"><a href="#" style="color:red;" onclick="dsplyAuthrzdData('<?php echo $rowATZ22; ?>');">Site/Branch:</a></label>
                                                                <?php } else { ?>
                                                                <label for="lnkdFirmLoc" class="control-label col-md-4">Site/Branch:</label>
                                                                <?php } ?>
                                                                <div  class="col-md-8">
                                                                    <div class="input-group">
                                                                        <input <?php echo $mkReadOnly; ?> type="text" class="form-control rqrdFld" id="lnkdFirmLoc" value="<?php echo $row[22]; ?>"/>  
                                                                        <input type="hidden" id="lnkdFirmLocID" value="<?php echo $row[30]; ?>">
                                                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Customer/Supplier Sites', 'lnkdFirmID', '', '', 'radio', true, '<?php echo $row[22]; ?>', 'lnkdFirmLocID', 'lnkdFirmLoc', 'clear', 1, '');">
                                                                            <span class="glyphicon glyphicon-th-list"></span>                                                                            
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div>  
                                                            <div class="form-group form-group-sm">
                                                                <?php if($cnt > 0 && $row[15] != $rowATZ15) { ?>
                                                                <label data-toggle="tooltip" title="<?php echo $rowATZ15; ?>" for="email" class="control-label col-md-4"><a href="#" style="color:red;" onclick="dsplyAuthrzdData('<?php echo $rowATZ15; ?>');">Email:</a></label>
                                                                <?php } else { ?>
                                                                <label for="email" class="control-label col-md-4">Email:</label>
                                                                <?php } ?>
                                                                <div  class="col-md-8">
                                                                    <input <?php echo $mkReadOnly; ?> class="form-control" id="email" type = "email" placeholder="<?php echo $admin_email; ?>" value="<?php echo $row[15]; ?>"/>
                                                                </div>
                                                            </div> 
                                                            <div class="form-group form-group-sm">
                                                                <?php if($cnt > 0 && (!($row[16] == $rowATZ16 && $row[17] == $rowATZ17))) { ?>
                                                                <label data-toggle="tooltip" title="<?php echo $rowATZ16.";".$rowATZ17; ?>" for="telephone" class="control-label col-md-4"><a href="#" style="color:red;" onclick="dsplyAuthrzdData('<?php echo $rowATZ16.";".$rowATZ17; ?>');">Contact Nos:</a></label>
                                                                <?php } else { ?>
                                                                <label for="telephone" class="control-label col-md-4">Contact Nos:</label>
                                                                <?php } ?>
                                                                <div  class="col-md-8">
                                                                    <input <?php echo $mkReadOnly; ?> class="form-control" id="telNo" type = "text" placeholder="Telephone" value="<?php echo $row[16]; ?>"/>
                                                                    <input <?php echo $mkReadOnly; ?> class="form-control rqrdFld" id="mobileNo" type = "text" placeholder="Mobile" value="<?php echo $row[17]; ?>"/>                                       
                                                                </div>
                                                            </div>     
                                                            <div class="form-group form-group-sm">
                                                                <?php if($cnt > 0 && $row[18] != $rowATZ18) { ?>
                                                                <label data-toggle="tooltip" title="<?php echo $rowATZ18; ?>" for="fax" class="control-label col-md-4"><a href="#" style="color:red;" onclick="dsplyAuthrzdData('<?php echo $rowATZ18; ?>');">Fax:</a></label>
                                                                <?php } else { ?>
                                                                <label for="fax" class="control-label col-md-4">Fax:</label>
                                                                <?php } ?>
                                                                <div  class="col-md-8">
                                                                    <input <?php echo $mkReadOnly; ?> class="form-control" id="faxNo" type = "text" placeholder="Fax" value="<?php echo $row[18]; ?>"/>
                                                                </div>
                                                            </div> 
                                                        </fieldset>                                                
                                                    </div>
                                                    <div class="col-lg-4">
                                                        <fieldset class="basic_person_fs2"><legend class="basic_person_lg">Relationship Type</legend>                                    
                                                            <div class="form-group form-group-sm" >
                                                                <?php if($cnt > 0 && $row[23] != $rowATZ23) { ?>
                                                                <label data-toggle="tooltip" title="<?php echo $rowATZ23; ?>" for="relation" class="control-label col-md-4"><a href="#" style="color:red;" onclick="dsplyAuthrzdData('<?php echo $rowATZ23; ?>');">Relation:</a></label>
                                                                <?php } else { ?>
                                                                <label for="relation" class="control-label col-md-4">Relation:</label>
                                                                <?php } ?>
                                                                <div  class="col-md-8">
                                                                    <select <?php echo $mkReadOnlyDsbld; ?> class="form-control rqrdFld" id="relation" >
                                                                        <?php
                                                                        $brghtStr = "";
                                                                        $isDynmyc = FALSE;
                                                                        $titleRslt = getLovValues("%", "Both", 0, 100, $brghtStr,
                                                                                getLovID("MCF Person Types"), $isDynmyc, -1, "", "");
                                                                        while ($titleRow = loc_db_fetch_array($titleRslt)) {
                                                                            $selectedTxt = "";
                                                                            if ($titleRow[0] == $row[23]) {
                                                                                $selectedTxt = "selected";
                                                                            }
                                                                            ?>
                                                                            <option value="<?php echo $titleRow[0]; ?>" <?php echo $selectedTxt; ?>><?php echo $titleRow[0]; ?></option>
                                                                            <?php
                                                                        }
                                                                        ?>
                                                                    </select>
                                                                </div>
                                                            </div>                                                          
                                                            <div class="form-group form-group-sm">
                                                                <label for="status" class="control-label col-md-4">Status:</label>
                                                                <div  class="col-md-8">
                                                                    <input <?php echo $mkReadOnly; ?> type="text" class="form-control" aria-label="..." id="status" value="<?php echo $row[24]; ?>" readonly="">
                                                                </div>
                                                            </div>
                                                            <div class="form-group form-group-sm">
                                                                <?php if($cnt > 0 && $row[25] != $rowATZ25) { ?>
                                                                <label data-toggle="tooltip" title="<?php echo $v_BranchATZ; ?>" for="bnkBranch" class="control-label col-md-4"><a href="#" style="color:red;" onclick="dsplyAuthrzdData('<?php echo $v_BranchATZ; ?>');">Branch:</a></label>
                                                                <?php } else { ?>
                                                                <label for="bnkBranch" class="control-label col-md-4">Branch:</label>
                                                                <?php } ?>
                                                                <div  class="col-md-8">
                                                                    <div class="input-group" style="width:100% !important;">
                                                                        <input type="text" class="form-control" aria-label="..." id="bnkBranch" value="<?php echo $v_Branch; ?>" readonly="">
                                                                        <input type="hidden" id="bnkBranchID" value="<?php echo $row[25]; ?>">
                                                                        <input type="hidden" id="gnrlOrgID" value="<?php echo $orgID; ?>">                                                                        
                                                                        <label style="display:none !important;" class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Sites/Locations New', 'gnrlOrgID', '', '', 'radio', true, '<?php echo $row[25]; ?>', 'bnkBranchID', 'bnkBranch', 'clear', 1, '');">
                                                                            <span class="glyphicon glyphicon-th-list"></span>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div>  
                                                            <div class="form-group form-group-sm">
                                                                <?php if($cnt > 0 && $row[26] != $rowATZ26) { ?>
                                                                <label data-toggle="tooltip" title="<?php echo $rowATZ26; ?>" for="startDate" class="control-label col-md-4"><a href="#" style="color:red;" onclick="dsplyAuthrzdData('<?php echo $rowATZ26; ?>');">Start Date:</a></label>
                                                                <?php } else { ?>
                                                                <label for="startDate" class="control-label col-md-4">Start Date:</label>
                                                                <?php } ?>
                                                                <div  class="col-md-8">
                                                                    <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input3" data-link-format="yyyy-mm-dd">
                                                                        <input class="form-control" size="16" type="text" id="startDate" value="<?php echo $row[26]; ?>" readonly="">
                                                                        <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                                                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                                        <span class="input-group-addon" onclick="javascript:unfreezeDialog();"><span class="glyphicon glyphicon-info-sign"></span></span>
                                                                    </div>
                                                                </div>
                                                            </div>      
                                                            <div class="form-group form-group-sm">
                                                                <?php if($cnt > 0 && $row[27] != $rowATZ27) { ?>
                                                                <label data-toggle="tooltip" title="<?php echo $rowATZ27; ?>" for="endDate" class="control-label col-md-4"><a href="#" style="color:red;" onclick="dsplyAuthrzdData('<?php echo $rowATZ27; ?>');">End Date:</a></label>
                                                                <?php } else { ?>
                                                                <label for="endDate" class="control-label col-md-4">End Date:</label>
                                                                <?php } ?>
                                                                <div  class="col-md-8">
                                                                    <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input4" data-link-format="yyyy-mm-dd">
                                                                        <input class="form-control" size="16" type="text" id="endDate" value="<?php echo $row[27]; ?>" readonly="">
                                                                        <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                                                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                                        <span class="input-group-addon" onclick="javascript:unfreezeDialog();"><span class="glyphicon glyphicon-info-sign"></span></span>
                                                                    </div>
                                                                </div>
                                                            </div>  
                                                        </fieldset>                                                
                                                    </div>
                                                </div> 
                                                <div class="row"><!-- ROW 3 -->
                                                    <div class="col-lg-4">
                                                        <fieldset class="basic_person_fs3"><legend class="basic_person_lg">Address</legend> 
                                                            <div class="form-group form-group-sm">
                                                                <?php if($cnt > 0 && $row[14] != $rowATZ14) { ?>
                                                                <label data-toggle="tooltip" title="<?php echo $rowATZ14; ?>" for="postalAddress" class="control-label col-md-4"><a href="#" style="color:red;" onclick="dsplyAuthrzdData('<?php echo $rowATZ14; ?>');">Postal Address:</a></label>
                                                                <?php } else { ?>
                                                                <label for="postalAddress" class="control-label col-md-4">Postal Address:</label>
                                                                <?php } ?>
                                                                <div  class="col-md-8">
                                                                    <textarea <?php echo $mkReadOnly; ?> class="form-control" id="postalAddress" cols="2" placeholder="Postal Address" rows="4"><?php echo $row[14]; ?></textarea>
                                                                </div>
                                                            </div>
                                                            <div class="form-group form-group-sm">
                                                                <?php if($cnt > 0 && $row[13] != $rowATZ13) { ?>
                                                                <label data-toggle="tooltip" title="<?php echo $rowATZ13; ?>" for="residentialAddress" class="control-label col-md-4"><a href="#" style="color:red;" onclick="dsplyAuthrzdData('<?php echo $rowATZ13; ?>');">Residential Address:</a></label>
                                                                <?php } else { ?>
                                                                <label for="residentialAddress" class="control-label col-md-4">Residential Address:</label>
                                                                <?php } ?>
                                                                <div  class="col-md-8">
                                                                    <textarea <?php echo $mkReadOnly; ?> class="form-control rqrdFld" id="residentialAddress" cols="2" placeholder="Residential Address" rows="4"><?php echo $row[13]; ?></textarea>
                                                                </div>
                                                            </div> 
                                                        </fieldset>                                        
                                                    </div>
                                                    <div class="col-lg-8"> 
                                                        <fieldset class="basic_person_fs3"><legend class="basic_person_lg">National ID Cards</legend> 
                                                            <div  class="col-md-12">
                                                                <?php if($trnsStatus == "Incomplete" || $trnsStatus == "Rejected" || $trnsStatus == "Withdrawn"){ ?>
                                                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="getIndCustNtnlIDForm('myFormsModalx', 'myFormsModalxBody', 'myFormsModalxTitle', 'ntnlIDCardsForm', '', 'Edit Customer Profile', 11, <?php echo $subPgNo; ?>, 5, '', -1);">
                                                                        <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                        Add National ID Card
                                                                    </button>
                                                                <?php } ?>
                                                                <table id="nationalIDTblEDT" class="table table-striped table-bordered table-responsive" cellspacing="0" width="100%" style="width:100%;">
                                                                    <thead>
                                                                        <tr>
                                                                            <?php if($trnsStatus == "Incomplete" || $trnsStatus == "Rejected" || $trnsStatus == "Withdrawn"){ ?>
                                                                            <th>...</th>
                                                                            <th>...</th>
                                                                            <?php } ?>
                                                                            <th>Country</th>
                                                                            <th>ID Type</th>
                                                                            <th>ID No.</th>
                                                                            <th>Date Issued</th>
                                                                            <th>Expiry Date</th>
                                                                            <th>Other Information</th>
                                                                            <th <?php echo $shwHydNtlntySts; ?>>Status</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <?php
                                                                        $result1 = get_PersonAllNtnlty($pkID, "Individual Customer");
                                                                        $cntr = 0;
                                                                        while ($row1 = loc_db_fetch_array($result1)) {
                                                                            $cntr++;
                                                                            $row1ATZ0 = ""; $row1ATZ1 = ""; $row1ATZ2 = ""; $row1ATZ3 = ""; $row1ATZ4 = ""; $row1ATZ5 = "";
                                                                            $row1ATZ6 = ""; $row1ATZ7 = "";
                                                                            if($row1[8] > 0 && $row1[7] === "Yes"){
                                                                                $result1ATZ = get_PersonAllNtnltyATZ($row1[0]);
                                                                                while ($row1ATZ = loc_db_fetch_array($result1ATZ)) {
                                                                                    $row1ATZ0 = $row1ATZ[0]; $row1ATZ1 = $row1ATZ[1]; $row1ATZ2 = $row1ATZ[2]; $row1ATZ3 = $row1ATZ[3];
                                                                                    $row1ATZ4 = $row1ATZ[4]; $row1ATZ5 = $row1ATZ[5]; $row1ATZ6 = $row1ATZ[6]; $row1ATZ7 = $row1ATZ[7];
                                                                                }
                                                                            }
                                                                            ?>
                                                                            <tr id="ntnlIDCardsRow<?php echo $cntr; ?>">
                                                                                <?php if($trnsStatus == "Incomplete" || $trnsStatus == "Rejected" || $trnsStatus == "Withdrawn"){ ?>
                                                                                <td>
                                                                                    <button type="button" class="btn btn-default btn-sm" onclick="getIndCustNtnlIDForm('myFormsModalx', 'myFormsModalxBody', 'myFormsModalxTitle', 'ntnlIDCardsForm', 'ntnlIDCardsRow<?php echo $cntr; ?>', 'Edit National ID', 11, <?php echo $subPgNo; ?>, 5, 'EDIT', <?php echo $row1[0]; ?>);" style="padding:2px !important;">
                                                                                        <!--<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>-->
                                                                                        <img src="cmn_images/edit32.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                                    </button>
                                                                                </td>
                                                                                <td>
                                                                                    <button type="button" class="btn btn-default btn-sm" onclick="deleteNationalID(<?php echo $row1[0]; ?>, '<?php echo $trnsStatus; ?>', <?php echo $subPgNo; ?>, '<?php echo $row1[7]; ?>');" style="padding:2px !important;">
                                                                                        <!--<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>-->
                                                                                        <img src="cmn_images/delete.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                                    </button>
                                                                                </td>
                                                                                <?php } ?>
                                                                                <td>
                                                                                    <?php 
                                                                                        echo dsplyTblData($row1[1], $row1ATZ1, $row1[8], $row1[7]);
                                                                                    ?>
                                                                                </td>
                                                                                <td>
                                                                                    <?php 
                                                                                        echo dsplyTblData($row1[2], $row1ATZ2, $row1[8], $row1[7]);
                                                                                    ?>
                                                                                </td>
                                                                                <td><?php 
                                                                                        echo dsplyTblData($row1[3], $row1ATZ3, $row1[8], $row1[7]);
                                                                                    ?>
                                                                                </td>
                                                                                <td><?php 
                                                                                        echo dsplyTblData($row1[4], $row1ATZ4, $row1[8], $row1[7]);
                                                                                    ?>
                                                                                </td>
                                                                                <td><?php 
                                                                                        echo dsplyTblData($row1[5], $row1ATZ5, $row1[8], $row1[7]);
                                                                                    ?>
                                                                                </td>
                                                                                <td><?php 
                                                                                        echo dsplyTblData($row1[6], $row1ATZ6, $row1[8], $row1[7]);
                                                                                    ?>
                                                                                </td>
                                                                                <td <?php echo $shwHydNtlntySts; ?>>
                                                                                    <?php 
                                                                                    if($row1[8] < 0){
                                                                                        echo "<span style='color:red;'><b>Deleted</b></span>";
                                                                                    } else  {
                                                                                       if($row1[7] === "No"){
                                                                                            echo "<span style='color:blue;'><b>New</b></span>";
                                                                                       } else {
                                                                                           echo "&nbsp;";
                                                                                       }
                                                                                    }
                                                                                    ?>
                                                                                </td>
                                                                            </tr>
                                                                        <?php } ?>
                                                                    </tbody>
                                                                </table>
                                                            </div> 
                                                        </fieldset>
                                                    </div>
                                                </div>  
                                            </form>  
                                        </div>
                                        <div id="prfBCOPAddPrsnDataEDT" class="tab-pane fade" style="border:none !important;"></div>
                                        <div id="prflBCOPAttchmntsEDT" class="tab-pane fade" style="border:none !important;"></div>      
                                    </div>                        
                                </div>                         
                            </div>                
                        </div>          
                    </div>
                    <?php
                }
            } else if ($vwtypActn === "ADD") {
                /* Add */
                    $trnsStatus = "Incomplete";
                    $rqstatusColor = "red";
                ?>
                <input class="form-control" id="addOrEditForm" type = "hidden" placeholder="addOrEditForm" value="Add"/>
                <div class="row" style="margin: 0px 0px 10px 0px !important;">
                    <div class="col-md-12" style="padding:0px 0px 0px 0px !important;">
                        <button type="button" class="btn btn-default btn-sm phone-only-btn" onclick="openATab('#allmodules', 'grp=17&typ=1&pg=10&vtyp=0');">Basic Data</button>
                        <button type="button" class="btn btn-default btn-sm phone-only-btn" onclick="openATab('#prfBCOPAddPrsnDataEDT', 'grp=17&typ=1&pg=10&vtyp=1');">Additional Data</button>
                        <!--<button type="button" class="btn btn-default btn-sm phone-only-btn" onclick="openATab('#prflBCOPAttchmntsEDT', 'grp=17&typ=1&pg=10&vtyp=2');">Attachments</button>-->
                    </div>
                </div>
                <div class="">
                    <div class="row" style="margin: 0px 0px 10px 0px !important;">
                        <div class="col-md-12" style="padding:0px 0px 5px 1px !important;">
                            <div style="float:left;">
                                <button type="button" class="btn btn-default btn-sm" style="height:30px;" id="myVmsTrnsStatusBtn">
                                        <span style="font-weight:bold;">Status: </span><span style="color:<?php echo $rqstatusColor; ?>;font-weight: bold;"><?php echo $trnsStatus; ?></span>
                                </button>                                                  
                            </div>
                            <div class="" style="float:right;"> 
                                <button type="button" class="btn btn-default btn-sm" style="" onclick="saveBasicData(<?php echo $pgNo; ?>, <?php echo $subPgNo; ?>, <?php echo $vwtyp; ?>, 0);">
                                    <img src="cmn_images/FloppyDisk.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">
                                    SAVE
                                </button>  
                            </div>
                        </div>                                          
                    </div>                    
                    <div class="row">                  
                        <div class="col-md-12">
                            <div id="prflHomeBCOPEDT" style="border:none !important;">                                              
                                <form class="form-horizontal">
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <fieldset class="basic_person_fs1"><legend class="basic_person_lg">Person's Picture</legend>
                                                <div style="margin-bottom: 10px;">
                                                    <img src="" alt="..." id="img1Test" class="img-rounded center-block img-responsive" style="height: 195px !important; width: auto !important;">                                            
                                                </div>
                                                <div class="form-group form-group-sm">
                                                    <div class="col-md-12">
                                                        <div class="input-group">
                                                            <label class="btn btn-primary btn-file input-group-addon">
                                                                Browse... <input type="file" id="custPicture" name="custPicture" onchange="changeImgSrc(this, '#img1Test', '#img1SrcLoc');" class="btn btn-default"  style="display: none;">
                                                            </label>
                                                            <input type="text" class="form-control" aria-label="..." id="img1SrcLoc" value="">                                                        
                                                        </div>                                                    
                                                    </div>                                            
                                                </div>                                        
                                            </fieldset>
                                        </div>                                
                                        <div class="col-lg-4">
                                            <fieldset class="basic_person_fs1"><legend class="basic_person_lg">Names</legend>
                                                <div class="form-group form-group-sm">
                                                    <label for="idNo" class="control-label col-md-4">ID No:</label>
                                                    <div class="col-md-8">
                                                        <input class="form-control" id="idNo" type = "text" placeholder="ID No" value=""/>
                                                        <!--CUSTOMER ID-->
                                                        <input class="form-control" id="custID" type = "hidden" placeholder="Customer ID" value=""/>                                                                     
                                                        <!--PROFILE ID-->
                                                        <input class="form-control" id="profileID" type = "hidden" placeholder="Profile ID" value=""/>                                                                      
                                                    </div>
                                                </div> 
                                                <div class="form-group form-group-sm">
                                                    <label for="title" class="control-label col-md-4">Title:</label>
                                                    <div  class="col-md-8">
                                                        <select class="form-control rqrdFld" id="title" >
                                                            <option value="">&nbsp;</option>
                                                            <?php
                                                            $brghtStr = "";
                                                            $isDynmyc = FALSE;
                                                            $titleRslt = getLovValues("%", "Both", 0, 100, $brghtStr,
                                                                    getLovID("Person Titles"), $isDynmyc, -1, "", "");
                                                            while ($titleRow = loc_db_fetch_array($titleRslt)) {
                                                                $selectedTxt = "";
                                                                ?>
                                                                <option value="<?php echo $titleRow[0]; ?>" <?php echo $selectedTxt; ?>><?php echo $titleRow[0]; ?></option>
                                                                <?php
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>  
                                                <div class="form-group form-group-sm">
                                                    <label for="firstName" class="control-label col-md-4">First Name:</label>
                                                    <div  class="col-md-8">
                                                        <input class="form-control rqrdFld" id="firstName" type = "text" placeholder="First Name" value=""/>
                                                    </div>
                                                </div> 
                                                <div class="form-group form-group-sm">
                                                    <label for="surName" class="control-label col-md-4">Surname:</label>
                                                    <div  class="col-md-8">
                                                        <input class="form-control rqrdFld" id="surName" type = "text" placeholder="Surname" value=""/>
                                                    </div>
                                                </div>     
                                                <div class="form-group form-group-sm">
                                                    <label for="otherNames" class="control-label col-md-4">Other Names:</label>
                                                    <div  class="col-md-8">
                                                        <textarea class="form-control" id="otherNames" cols="2" placeholder="Other Names" rows="3"></textarea>
                                                    </div>
                                                </div>
                                                <div class="form-group form-group-sm">
                                                    <label for="gender" class="control-label col-md-4">Gender:</label>
                                                    <div  class="col-md-8">
                                                        <select class="form-control rqrdFld" id="gender" >
                                                            <option value="">&nbsp;</option>
                                                            <?php
                                                            $brghtStr = "";
                                                            $isDynmyc = FALSE;
                                                            $titleRslt = getLovValues("%", "Both", 0, 100, $brghtStr,
                                                                    getLovID("Gender"), $isDynmyc, -1, "", "");
                                                            while ($titleRow = loc_db_fetch_array($titleRslt)) {
                                                                $selectedTxt = "";
                                                                ?>
                                                                <option value="<?php echo $titleRow[0]; ?>" <?php echo $selectedTxt; ?>><?php echo $titleRow[0]; ?></option>
                                                                <?php
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div> 
                                            </fieldset>
                                        </div>
                                        <div class="col-lg-4"> 
                                            <fieldset class="basic_person_fs1"><legend class="basic_person_lg">Personal Data</legend>
                                                <div class="form-group form-group-sm">
                                                    <label for="maritalStatus" class="control-label col-md-4">Marital Status:</label>
                                                    <div  class="col-md-8">
                                                        <select class="form-control rqrdFld" id="maritalStatus" >
                                                            <option value="">&nbsp;</option>
                                                            <?php
                                                            $brghtStr = "";
                                                            $isDynmyc = FALSE;
                                                            $titleRslt = getLovValues("%", "Both", 0, 100, $brghtStr,
                                                                    getLovID("Marital Status"), $isDynmyc, -1, "", "");
                                                            while ($titleRow = loc_db_fetch_array($titleRslt)) {
                                                                $selectedTxt = "";
                                                                ?>
                                                                <option value="<?php echo $titleRow[0]; ?>" <?php echo $selectedTxt; ?>><?php echo $titleRow[0]; ?></option>
                                                                <?php
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group form-group-sm">
                                                    <label for="dob" class="control-label col-md-4">Date of Birth</label>
                                                    <div class="col-md-8">
                                                        <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                                            <input class="form-control rqrdFld" size="16" type="text" id="dob" value="" readonly="">
                                                            <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                                            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                            <span class="input-group-addon" onclick="javascript:unfreezeDialog();"><span class="glyphicon glyphicon-info-sign"></span></span>
                                                        </div>
                                                    </div>
                                                </div> 
                                                <div class="form-group form-group-sm">
                                                    <label for="pob" class="control-label col-md-4">Place of Birth:</label>
                                                    <div  class="col-md-8">
                                                        <textarea class="form-control" id="pob" cols="2" placeholder="Place of Birth" rows="2"></textarea>
                                                    </div>
                                                </div> 
                                                <div class="form-group form-group-sm">
                                                    <label for="nationality" class="control-label col-md-4">Nationality:</label>
                                                    <div class="col-md-8">
                                                        <select class="form-control rqrdFld" id="nationality" >
                                                            <option value="">&nbsp;</option>
                                                            <?php
                                                            $brghtStr = "";
                                                            $isDynmyc = FALSE;
                                                            $titleRslt = getLovValues("%", "Both", 0, 100, $brghtStr,
                                                                    getLovID("Nationalities"), $isDynmyc, -1, "", "");
                                                            while ($titleRow = loc_db_fetch_array($titleRslt)) {
                                                                $selectedTxt = "";
                                                                ?>
                                                                <option value="<?php echo $titleRow[0]; ?>" <?php echo $selectedTxt; ?>><?php echo $titleRow[0]; ?></option>
                                                                <?php
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>  
                                                <div class="form-group form-group-sm">
                                                    <label for="homeTown" class="control-label col-md-4">Home Town:</label>
                                                    <div  class="col-md-8">
                                                        <textarea class="form-control" id="homeTown" cols="2" placeholder="Home Town" rows="1"></textarea>
                                                    </div>
                                                </div> 
                                                <div class="form-group form-group-sm">
                                                    <label for="religion" class="control-label col-md-4">Religion:</label>
                                                    <div  class="col-md-8">
                                                        <input class="form-control" id="religion" type = "text" placeholder="Religion" value=""/>
                                                    </div>
                                                </div>                                              
                                            </fieldset>   
                                        </div>
                                    </div>    
                                    <div class="row"><!-- ROW 1 -->
                                        <div class="col-lg-4">
                                            <fieldset class="basic_person_fs2"><legend class="basic_person_lg">Signature/Thumbprint</legend>
                                                <div style="margin-bottom: 10px;">
                                                    <img src="" alt="..." id="img2Test" class="img-rounded center-block img-responsive" style="height: 195px !important; width: auto !important;">                                            
                                                </div>
                                                <div class="form-group form-group-sm">
                                                    <div class="col-md-12">
                                                        <div class="input-group">
                                                            <label class="btn btn-primary btn-file input-group-addon">
                                                                Browse... <input type="file" id="signPicture" name="signPicture" onchange="changeImgSrc(this, '#img2Test', '#img2SrcLoc');" class="btn btn-default"  style="display: none;">
                                                            </label>
                                                            <input type="text" class="form-control" aria-label="..." id="img2SrcLoc" value="">                                                        
                                                        </div>                                                    
                                                    </div>                                            
                                                </div>                                     
                                            </fieldset>
                                        </div>                                
                                        <div class="col-lg-4">
                                            <fieldset class="basic_person_fs2"><legend class="basic_person_lg">Contact Information</legend>                                                       
                                                <div class="form-group form-group-sm">
                                                    <label for="lnkdFirmName" class="control-label col-md-4">Linked Firm/ Workplace</label>
                                                    <div  class="col-md-8">
                                                        <div class="input-group">
                                                            <input type="text" class="form-control rqrdFld" id="lnkdFirmName" value="">
                                                            <input type="hidden" id="gnrlOrgID" value="<?php echo $orgID; ?>">
                                                            <input type="hidden" id="lnkdFirmID" value="">
                                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Customers and Suppliers', 'gnrlOrgID', '', '', 'radio', true, '', 'lnkdFirmID', 'lnkdFirmName', 'clear', 1, '');">
                                                                <span class="glyphicon glyphicon-th-list"></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group form-group-sm">
                                                    <label for="lnkdFirmLoc" class="control-label col-md-4">Site/Branch:</label>
                                                    <div  class="col-md-8">
                                                        <div class="input-group">
                                                            <input type="text" class="form-control rqrdFld" id="lnkdFirmLoc" value="">  
                                                            <input type="hidden" id="lnkdFirmLocID" value="">
                                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Customer/Supplier Sites', 'lnkdFirmID', '', '', 'radio', true, '', 'lnkdFirmLocID', 'lnkdFirmLoc', 'clear', 1, '');">
                                                                <span class="glyphicon glyphicon-th-list"></span>                                                                            
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>  
                                                <div class="form-group form-group-sm">
                                                    <label for="email" class="control-label col-md-4">Email:</label>
                                                    <div  class="col-md-8">
                                                        <input class="form-control" id="email" type = "email" placeholder="<?php echo $admin_email; ?>" value=""/>
                                                    </div>
                                                </div> 
                                                <div class="form-group form-group-sm">
                                                    <label for="telephone" class="control-label col-md-4">Contact Nos:</label>
                                                    <div  class="col-md-8">
                                                        <input class="form-control" id="telNo" type = "text" placeholder="Telephone" value=""/>
                                                        <input class="form-control rqrdFld" id="mobileNo" type = "text" placeholder="Mobile" value=""/>                                       
                                                    </div>
                                                </div>     
                                                <div class="form-group form-group-sm">
                                                    <label for="fax" class="control-label col-md-4">Fax:</label>
                                                    <div  class="col-md-8">
                                                        <input class="form-control" id="faxNo" type = "text" placeholder="Fax" value=""/>
                                                    </div>
                                                </div> 
                                            </fieldset>                                                
                                        </div>
                                        <div class="col-lg-4">
                                            <fieldset class="basic_person_fs2"><legend class="basic_person_lg">Relationship Type</legend>                                    
                                                <div class="form-group form-group-sm">
                                                    <label for="relation" class="control-label col-md-4">Relation:</label>
                                                    <div  class="col-md-8">
                                                        <select class="form-control rqrdFld" id="relation" >
                                                            <option value="">&nbsp;</option>
                                                            <?php
                                                            $brghtStr = "";
                                                            $isDynmyc = FALSE;
                                                            $titleRslt = getLovValues("%", "Both", 0, 100, $brghtStr,
                                                                    getLovID("MCF Person Types"), $isDynmyc, -1, "", "");
                                                            while ($titleRow = loc_db_fetch_array($titleRslt)) {
                                                                $selectedTxt = "";
                                                                ?>
                                                                <option value="<?php echo $titleRow[0]; ?>" <?php echo $selectedTxt; ?>><?php echo $titleRow[0]; ?></option>
                                                                <?php
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>                                            
                                                <div class="form-group form-group-sm">
                                                    <label for="status" class="control-label col-md-4">Status:</label>
                                                    <div  class="col-md-8">
                                                        <input type="text" class="form-control" aria-label="..." id="status" value="Incomplete" readonly="">
                                                    </div>
                                                </div>
                                                <div class="form-group form-group-sm">
                                                    <label for="bnkBranch" class="control-label col-md-4">Branch:</label>
                                                    <div  class="col-md-8">
                                                        <div class="input-group" style="width:100% !important;">
                                                            <input type="text" class="form-control" aria-label="..." id="bnkBranch" value="<?php echo $prsnBranch; ?>" readonly="">
                                                            <input type="hidden" id="bnkBranchID" value="<?php echo $prsnBranchID; ?>">
                                                            <input type="hidden" id="gnrlOrgID" value="<?php echo $orgID; ?>">
                                                            <label style="display:none !important;" class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Sites/Locations New', 'gnrlOrgID', '', '', 'radio', true, '', 'bnkBranchID', 'bnkBranch', 'clear', 1, '');">
                                                                <span class="glyphicon glyphicon-th-list"></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>  
                                                <div class="form-group form-group-sm">
                                                    <label for="startDate" class="control-label col-md-4">Start Date:</label>
                                                    <div  class="col-md-8">
                                                        <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input3" data-link-format="yyyy-mm-dd">
                                                            <input class="form-control" size="16" type="text" id="startDate" value="" readonly="">
                                                            <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                                            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                            <span class="input-group-addon" onclick="javascript:unfreezeDialog();"><span class="glyphicon glyphicon-info-sign"></span></span>
                                                        </div>
                                                    </div>
                                                </div>      
                                                <div class="form-group form-group-sm">
                                                    <label for="endDate" class="control-label col-md-4">End Date:</label>
                                                    <div  class="col-md-8">
                                                        <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input4" data-link-format="yyyy-mm-dd">
                                                            <input class="form-control" size="16" type="text" id="endDate" value="" readonly="">
                                                            <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                                            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                            <span class="input-group-addon" onclick="javascript:unfreezeDialog();"><span class="glyphicon glyphicon-info-sign"></span></span>
                                                        </div>
                                                    </div>
                                                </div>  
                                            </fieldset>                                                
                                        </div>
                                    </div> 
                                    <div class="row"><!-- ROW 3 -->
                                        <div class="col-lg-4">
                                            <fieldset class="basic_person_fs3"><legend class="basic_person_lg">Address</legend> 
                                                <div class="form-group form-group-sm">
                                                    <label for="postalAddress" class="control-label col-md-4">Postal Address:</label>
                                                    <div  class="col-md-8">
                                                        <textarea class="form-control" id="postalAddress" cols="2" placeholder="Postal Address" rows="4"></textarea>
                                                    </div>
                                                </div>
                                                <div class="form-group form-group-sm">
                                                    <label for="residentialAddress" class="control-label col-md-4">Residential Address:</label>
                                                    <div  class="col-md-8">
                                                        <textarea class="form-control rqrdFld" id="residentialAddress" cols="2" placeholder="Residential Address" rows="4"></textarea>
                                                    </div>
                                                </div> 
                                            </fieldset>                                        
                                        </div>
                                        <div class="col-lg-8"> 
                                            <fieldset class="basic_person_fs3"><legend class="basic_person_lg">National ID Cards</legend> 
                                                <div  class="col-md-12">
                                                    <table id="nationalIDTblEDT" class="table table-striped table-bordered table-responsive" cellspacing="0" width="100%" style="width:100%;">
                                                        <thead>
                                                            <tr>
                                                                <?php if($trnsStatus != "Approved"){ ?>
                                                                <th>...</th>
                                                                <th>...</th>
                                                                <?php } ?>
                                                                <th>Country</th>
                                                                <th>ID Type</th>
                                                                <th>ID No.</th>
                                                                <th>Date Issued</th>
                                                                <th>Expiry Date</th>
                                                                <th>Other Information</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                        </tbody>
                                                    </table>
                                                </div> 
                                            </fieldset>
                                        </div>
                                    </div>  
                                </form>  
                            </div>     
                        </div>                
                    </div>          
                </div>
                <?php
            } else if ($vwtypActn == "VIEW") {
                /* Read Only */
                $tblNm1 = "mcf.mcf_customers_ind";
                $tblNm2 = "mcf.mcf_customers";

                $cnt = getCustDataChngPndngCount($pkID);
                if($cnt > 0){
                    $tblNm1 = "mcf.mcf_customers_ind_hstrc";
                    $tblNm2 = "mcf.mcf_customers_hstrc";        
                }                
                
                $result = get_IndCustDet($pkID, $tblNm1, $tblNm2);
                $shwHydNtlntySts = "style=\"display:none !important;\"";

                $picPath = "Picture/";
                $signPath = "Signature/";
                while ($row = loc_db_fetch_array($result)) {
                    $imgLoc = $row[2];
                    $temp1 = explode(".", $imgLoc);
                    $extension1 = end($temp1);
                    if (strlen(trim($extension1)) <= 0) {
                        $extension1 = "png";
                    }
                    $nwFileName1 = encrypt1($imgLoc, $smplTokenWord1) . "." . $extension1;
                    $ftp_src = $ftp_base_db_fldr . "/Mcf/Customers/" . $picPath . $imgLoc;
                    $fullPemDest = $fldrPrfx . $tmpDest . $nwFileName1;
                    if (file_exists($ftp_src) && is_dir($ftp_src) === FALSE) {
                        copy("$ftp_src", "$fullPemDest");
                    } else if (!file_exists($fullPemDest)) {
                        $ftp_src = $fldrPrfx . 'cmn_images/image_up.png';
                        copy("$ftp_src", "$fullPemDest");
                    }
                    $nwFileName1 = $tmpDest . $nwFileName1;

                    $imgLoc = $row[31];
                    $temp2 = explode(".", $imgLoc);
                    $extension2 = end($temp2);
                    if (strlen(trim($extension2)) <= 0) {
                        $extension2 = "png";
                    }
                    $nwFileName2 = encrypt1($imgLoc, $smplTokenWord1) . "." . $extension2;
                    $ftp_src = $ftp_base_db_fldr . "/Mcf/Customers/" . $signPath . $imgLoc;
                    $fullPemDest = $fldrPrfx . $tmpDest . $nwFileName2;
                    if (file_exists($ftp_src) && is_dir($ftp_src) === FALSE) {
                        copy("$ftp_src", "$fullPemDest");
                    } else if (!file_exists($fullPemDest)) {
                        $ftp_src = $fldrPrfx . 'cmn_images/no_image.png';
                        copy("$ftp_src", "$fullPemDest");
                    }
                    $nwFileName2 = $tmpDest . $nwFileName2;
                    
                    $trnsStatus = $row[24];
                    $rqstatusColor = "red";
                    if($trnsStatus == "Unauthorized" || $trnsStatus == "Initiated"){
                        $shwHydNtlntySts = "style=\"display:block !important;\"";
                    }
                    
                    $tblNmCustIndAuthrzd = "";
                    $tblNmCustAuthrzd = ""; 
                    $lblColor = "red";
                    $resultAuthrzd = "";                    
                    
                    if($cnt > 0){
                        $tblNmCustIndAuthrzd = "mcf.mcf_customers_ind";
                        $tblNmCustAuthrzd = "mcf.mcf_customers";        
                        $resultAuthrzd = get_IndCustDet($pkID, $tblNmCustIndAuthrzd, $tblNmCustAuthrzd);
                        while ($rowATZ = loc_db_fetch_array($resultAuthrzd)) {
                            $rowATZ0 = $rowATZ[0]; $rowATZ1 = $rowATZ[1]; $rowATZ2 = $rowATZ[2]; $rowATZ3 = $rowATZ[3]; $rowATZ4 = $rowATZ[4];
                            $rowATZ5 = $rowATZ[5]; $rowATZ6 = $rowATZ[6]; $rowATZ7 = $rowATZ[7]; $rowATZ8 = $rowATZ[8]; $rowATZ9 = $rowATZ[9];
                            $rowATZ10 = $rowATZ[10]; $rowATZ11 = $rowATZ[11]; $rowATZ12 = $rowATZ[12]; $rowATZ13 = $rowATZ[13]; $rowATZ14 = $rowATZ[14];
                            $rowATZ15 = $rowATZ[15]; $rowATZ16 = $rowATZ[16]; $rowATZ17 = $rowATZ[17]; $rowATZ18 = $rowATZ[18]; $rowATZ19 = $rowATZ[19];
                            $rowATZ20 = $rowATZ[20]; $rowATZ21 = $rowATZ[21]; $rowATZ22 = $rowATZ[22]; $rowATZ23 = $rowATZ[23]; $rowATZ24 = $rowATZ[24];
                            $rowATZ25 = $rowATZ[25]; $rowATZ26 = $rowATZ[26]; $rowATZ27 = $rowATZ[27]; $rowATZ28 = $rowATZ[28]; $rowATZ29 = $rowATZ[29];
                            $rowATZ30 = $rowATZ[30]; $rowATZ31 = $rowATZ[31]; $rowATZ32 = $rowATZ[32];
    //                        $rowATZ33 = $rowATZ[33]; $rowATZ34 = $rowATZ[34];$rowATZ35 = $rowATZ[35];
    //                        $rowATZ1 = $rowATZ[1]; $rowATZ2 = $rowATZ[2]; $rowATZ3 = $rowATZ[3]; $rowATZ4 = $rowATZ[4];
    //                        $rowATZ0 = $rowATZ[0]; $rowATZ1 = $rowATZ[1]; $rowATZ2 = $rowATZ[2]; $rowATZ3 = $rowATZ[3]; $rowATZ4 = $rowATZ[4];
                            
                            $v_BranchATZ = getGnrlRecNm("org.org_sites_locations", "location_id", "site_desc||' ('||location_code_name||')' ", $rowATZ25);
                        }               
                    }
                    
                    ?>
                    <input class="form-control" id="addOrEditForm" type = "hidden" placeholder="addOrEditForm" value="Edit"/>
                    <div class="row" style="margin: 0px 0px 10px 0px !important;">
                        <div class="col-md-12" style="padding:0px 0px 0px 0px !important;">
                            <button type="button" class="btn btn-default btn-sm phone-only-btn" onclick="openATab('#allmodules', 'grp=17&typ=1&pg=10&vtyp=0');">Basic Data</button>
                            <button type="button" class="btn btn-default btn-sm phone-only-btn" onclick="openATab('#prfBCOPAddPrsnDataEDT', 'grp=17&typ=1&pg=10&vtyp=1');">Additional Data</button>
                            <!--<button type="button" class="btn btn-default btn-sm phone-only-btn" onclick="openATab('#prflBCOPAttchmntsEDT', 'grp=17&typ=1&pg=10&vtyp=2');">Attachments</button>-->
                        </div>
                    </div>
                    <div class="">
                        <div class="row" style="margin: 0px 0px 10px 0px !important;">
                            <div class="col-md-12" style="padding:0px 0px 5px 0px !important;">
                                <div style="float:left;">
                                        <button type="button" class="btn btn-default btn-sm" style="height:30px;" id="myVmsTrnsStatusBtn">
                                                <span style="font-weight:bold;">Status: </span><span style="color:<?php echo $rqstatusColor; ?>;font-weight: bold;"><?php echo $trnsStatus; ?></span>
                                        </button>
                                        <button type="button" class="btn btn-default" style="height:30px;" onclick="getOneMcfDocsForm_Gnrl(<?php echo $pkID; ?>,'<?php echo $trnsType; ?>', 140, '<?php echo $formTitle; ?>');" data-toggle="tooltip" data-placement="bottom" title = "Attached Documents">
                                            <img src="cmn_images/adjunto.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                        </button>                                                    
                                </div>
                                <div class="" style="float:right;">                                   
                                        <?php if ($trnsStatus == "Unauthorized" && canPrsnSeeCustomerBranchDocs($prsnID, $pkID, $row[32]) === true) { ?>   
                                        <?php if (test_prmssns($dfltPrvldgs[63], $mdlNm)) { ?>
                                        <button type="button" class="btn btn-default btn-sm" style="height:30px;" onclick="rjctAutrzCustDataRqst('REJECT', <?php echo $pgNo; ?>, <?php echo $subPgNo; ?>, <?php echo $vwtyp; ?>);"><img src="cmn_images/withdraw_rqst.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Reject&nbsp;</button>                                         
                                        <?php } if (test_prmssns($dfltPrvldgs[63], $mdlNm)) { ?>
                                        <button type="button" class="btn btn-default btn-sm" style="height:30px;" onclick="rjctAutrzCustDataRqst('AUTHORIZE', <?php echo $pgNo; ?>, <?php echo $subPgNo; ?>, <?php echo $vwtyp; ?>);"><img src="cmn_images/Stamp-512.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Authorize&nbsp;</button>                                                                                                                                     
                                        <?php } } ?>                                    
                                </div>
                            </div>
                        </div>
                        <ul class="nav nav-tabs rho-hideable-tabs" style="margin-top:-10px !important;">
                            <li class="active"><a data-toggle="tab" data-rhodata="&pg=10&vtyp=0" href="#prflHomeBCOPEDT" id="prflBCHomeEDTtab">Basic Data</a></li>
                            <li><a data-toggle="tabajxprfledt" data-rhodata="&pg=10&vtyp=1" href="#prfBCOPAddPrsnDataEDT" onclick="openATab('#prfBCOPAddPrsnDataEDT', 'grp=17&typ=1&pg=<?php echo $pgNo; ?>&subPgNo=1.6&vtyp=1&vtypActn=VIEW&custID=<?php echo $custID; ?>&formType=Personal Customer&rvsnTtlAPD=<?php echo $row[32]; ?>');" id="prfBCOPAddPrsnDataEDTtab">Additional Data</a></li>
                            <!--<li><a data-toggle="tabajxprfledt" data-rhodata="&pg=10&vtyp=2" href="#prflBCOPAttchmntsEDT" onclick="openATab('#prflBCOPAttchmntsEDT', 'grp=17&typ=1&pg=<?php echo $pgNo; ?>&subPgNo=<?php echo $subPgNo; ?>&vtyp=2&vtypActn=VIEW&custID=<?php echo $custID; ?>');" id="prflBCOPAttchmntsEDTtab">Attachments</a></li>-->
                        </ul>
                        <div class="row">                  
                            <div class="col-md-12">
                                <div class="custDiv"> 
                                    <div class="tab-content">
                                        <div id="prflHomeBCOPEDT" class="tab-pane fadein active" style="border:none !important;">                                            
                                            <form class="form-horizontal">
                                                <div class="row">
                                                    <div class="col-lg-4">
                                                        <fieldset class="basic_person_fs1"><legend class="basic_person_lg">Person's Picture</legend>
                                                            <div style="margin-bottom: 10px;">
                                                                <img src="<?php echo $nwFileName1; ?>" alt="..." id="img1Test" class="img-rounded center-block img-responsive" style="height: 195px !important; width: auto !important;">                                            
                                                            </div>                                        
                                                        </fieldset>
                                                    </div>                                
                                                    <div class="col-lg-4">
                                                        <fieldset class="basic_person_fs1"><legend class="basic_person_lg">Names</legend>
                                                            <div class="form-group form-group-sm">
                                                                <?php if($cnt > 0 && $row[1] != $rowATZ1) { ?>
                                                                <label data-toggle="tooltip" title="<?php echo $rowATZ1; ?>" for="idNo" class="control-label col-md-4"><a href="#" style="color:red;" onclick="dsplyAuthrzdData('<?php echo $rowATZ1; ?>');">ID No:</a></label>
                                                                <?php } else { ?>
                                                                    <label for="idNo" class="control-label col-md-4">ID No:</label>
                                                                <?php } ?>
                                                                <div class="col-md-8">
                                                                    <span><?php echo $row[1]; ?></span>
                                                                    <input class="form-control" id="idNo" type = "hidden" placeholder="ID No" value="<?php echo $row[1]; ?>" readonly="readonly"/>
                                                                    <!--CUSTOMER ID-->
                                                                    <input class="form-control" id="custID" type = "hidden" placeholder="Customer ID" value="<?php echo $row[28]; ?>"/>                                                                     
                                                                    <!--PROFILE ID-->
                                                                    <input class="form-control" id="rvsnTtl" type = "hidden" placeholder="Revision Total" value="<?php echo $row[32]; ?>"/>
                                                                    <input class="form-control" id="profileID" type = "hidden" placeholder="Profile ID" value="<?php echo $row[0]; ?>"/>                                                                      
                                                                </div>
                                                            </div> 
                                                            <div class="form-group form-group-sm">
                                                                <?php if($cnt > 0 && $row[3] != $rowATZ3) { ?>
                                                                <label data-toggle="tooltip" title="<?php echo $rowATZ3; ?>" for="title" class="control-label col-md-4"><a href="#" style="color:red;" onclick="dsplyAuthrzdData('<?php echo $rowATZ3; ?>');">Title:</a></label>
                                                                <?php } else { ?>
                                                                    <label  for="title" class="control-label col-md-4">Title:</label>
                                                                <?php } ?>
                                                                <div  class="col-md-8">
                                                                    <span><?php echo $row[3]; ?></span>
                                                                </div>
                                                            </div>  
                                                            <div class="form-group form-group-sm">
                                                                <?php if($cnt > 0 && $row[4] != $rowATZ4) { ?>
                                                                <label data-toggle="tooltip" title="<?php echo $rowATZ4; ?>" for="firstName" class="control-label col-md-4"><a href="#" style="color:red;" onclick="dsplyAuthrzdData('<?php echo $rowATZ4; ?>');">First Name:</a></label>
                                                                <?php } else { ?>
                                                                <label for="firstName" class="control-label col-md-4">First Name:</label>
                                                                <?php }?>
                                                                <div  class="col-md-8">
                                                                    <span><?php echo $row[4]; ?></span>
                                                                </div>
                                                            </div> 
                                                            <div class="form-group form-group-sm">
                                                                <?php if($cnt > 0 && $row[5] != $rowATZ5) { ?>
                                                                <label data-toggle="tooltip" title="<?php echo $rowATZ5; ?>" for="surName" class="control-label col-md-4"><a href="#" style="color:red;" onclick="dsplyAuthrzdData('<?php echo $rowATZ5; ?>');">Surname:</a></label>
                                                                <?php } else { ?>
                                                                <label for="surName" class="control-label col-md-4">Surname:</label>
                                                                <?php } ?>
                                                                <div  class="col-md-8">
                                                                    <span><?php echo $row[5]; ?></span>
                                                                </div>
                                                            </div>     
                                                            <div class="form-group form-group-sm">
                                                                <?php if($cnt > 0 && $row[6] != $rowATZ6) { ?>
                                                                <label data-toggle="tooltip" title="<?php echo $rowAT6; ?>" for="otherNames" class="control-label col-md-4"><a href="#" style="color:red;" onclick="dsplyAuthrzdData('<?php echo $rowATZ6; ?>');">Other Names:</a></label>
                                                                <?php } else { ?>
                                                                <label for="otherNames" class="control-label col-md-4">Other Names:</label>
                                                                <?php } ?>
                                                                <div  class="col-md-8">
                                                                    <span><?php echo $row[6]; ?></span>
                                                                </div>
                                                            </div>
                                                            <div class="form-group form-group-sm">
                                                                <?php if($cnt > 0 && $row[8] != $rowATZ8) { ?>
                                                                <label data-toggle="tooltip" title="<?php echo $rowAT8; ?>" for="gender" class="control-label col-md-4"><a href="#" style="color:red;" onclick="dsplyAuthrzdData('<?php echo $rowATZ8; ?>');">Gender:</a></label>
                                                                <?php } else { ?>
                                                                <label for="gender" class="control-label col-md-4">Gender:</label>
                                                                <?php } ?>
                                                                <div  class="col-md-8">
                                                                    <span><?php echo $row[8]; ?></span>
                                                                </div>
                                                            </div> 
                                                        </fieldset>
                                                    </div>
                                                    <div class="col-lg-4"> 
                                                        <fieldset class="basic_person_fs1"><legend class="basic_person_lg">Personal Data</legend>
                                                            <div class="form-group form-group-sm">
                                                                <?php if($cnt > 0 && $row[9] != $rowATZ9) { ?>
                                                                <label data-toggle="tooltip" title="<?php echo $rowATZ9; ?>" for="maritalStatus" class="control-label col-md-4"><a href="#" style="color:red;" onclick="dsplyAuthrzdData('<?php echo $rowATZ9; ?>');">Marital Status:</a></label>
                                                                <?php } else { ?>
                                                                <label for="maritalStatus" class="control-label col-md-4">Marital Status:</label>
                                                                <?php } ?>
                                                                <div  class="col-md-8">
                                                                    <span><?php echo $row[9]; ?></span>
                                                                </div>
                                                            </div>
                                                            <div class="form-group form-group-sm">
                                                                <?php if($cnt > 0 && $row[10] != $rowATZ10) { ?>
                                                                <label data-toggle="tooltip" title="<?php echo $rowATZ10; ?>" for="dob" class="control-label col-md-4"><a href="#" style="color:red;" onclick="dsplyAuthrzdData('<?php echo $rowATZ10; ?>');">Date of Birth:</a></label>
                                                                <?php } else { ?>
                                                                <label for="dob" class="control-label col-md-4">Date of Birth:</label>
                                                                <?php } ?>
                                                                <div class="col-md-8">
                                                                    <span><?php echo $row[10]; ?></span>
                                                                </div>
                                                            </div> 
                                                            <div class="form-group form-group-sm">
                                                                <?php if($cnt > 0 && $row[11] != $rowATZ11) { ?>
                                                                <label data-toggle="tooltip" title="<?php echo $rowATZ11; ?>" for="pob" class="control-label col-md-4"><a href="#" style="color:red;" onclick="dsplyAuthrzdData('<?php echo $rowATZ11; ?>');">Place of Birth:</a></label>
                                                                <?php } else { ?>
                                                                <label for="pob" class="control-label col-md-4">Place of Birth:</label>
                                                                <?php } ?>
                                                                <div  class="col-md-8">
                                                                    <span><?php echo $row[11]; ?></span>
                                                                </div>
                                                            </div> 
                                                            <div class="form-group form-group-sm">
                                                                <?php if($cnt > 0 && $row[20] != $rowATZ20) { ?>
                                                                <label data-toggle="tooltip" title="<?php echo $rowATZ20; ?>" for="nationality" class="control-label col-md-4"><a href="#" style="color:red;" onclick="dsplyAuthrzdData('<?php echo $rowATZ20; ?>');">Nationality:</a></label>
                                                                <?php } else { ?>
                                                                <label for="nationality" class="control-label col-md-4">Nationality:</label>
                                                                <?php } ?>
                                                                <div class="col-md-8">
                                                                    <span><?php echo $row[20]; ?></span>
                                                                </div>
                                                            </div>  
                                                            <div class="form-group form-group-sm">
                                                                <?php if($cnt > 0 && $row[19] != $rowATZ19) { ?>
                                                                <label data-toggle="tooltip" title="<?php echo $rowATZ19; ?>" for="homeTown" class="control-label col-md-4"><a href="#" style="color:red;" onclick="dsplyAuthrzdData('<?php echo $rowATZ19; ?>');">Home Town:</a></label>
                                                                <?php } else { ?>
                                                                <label for="homeTown" class="control-label col-md-4">Home Town:</label>
                                                                <?php } ?>
                                                                <div  class="col-md-8">
                                                                    <span><?php echo $row[19]; ?></span>
                                                                </div>
                                                            </div> 
                                                            <div class="form-group form-group-sm">
                                                                <?php if($cnt > 0 && $row[12] != $rowATZ12) { ?>
                                                                <label data-toggle="tooltip" title="<?php echo $rowATZ12; ?>" for="religion" class="control-label col-md-4"><a href="#" style="color:red;" onclick="dsplyAuthrzdData('<?php echo $rowATZ12; ?>');">Religion:</a></label>
                                                                <?php } else { ?>
                                                                <label for="religion" class="control-label col-md-4">Religion:</label>
                                                                <?php } ?>
                                                                <div  class="col-md-8">
                                                                    <span><?php echo $row[12]; ?></span>
                                                                </div>
                                                            </div>                                              
                                                        </fieldset>   
                                                    </div>
                                                </div>    
                                                <div class="row"><!-- ROW 1 -->
                                                    <div class="col-lg-4">
                                                        <fieldset class="basic_person_fs2"><legend class="basic_person_lg">Signature/Thumbprint</legend>
                                                            <div style="margin-bottom: 10px;">
                                                                <img src="<?php echo $nwFileName2; ?>" alt="..." id="img2Test" class="img-rounded center-block img-responsive" style="height: 195px !important; width: auto !important;">                                            
                                                            </div>                                     
                                                        </fieldset>
                                                    </div>                                
                                                    <div class="col-lg-4">
                                                        <fieldset class="basic_person_fs2"><legend class="basic_person_lg">Contact Information</legend>
                                                            <div class="form-group form-group-sm">
                                                                <?php if($cnt > 0 && $row[21] != $rowATZ21) { ?>
                                                                <label data-toggle="tooltip" title="<?php echo $rowATZ21; ?>" for="linkedFirm" class="control-label col-md-4"><a href="#" style="color:red;" onclick="dsplyAuthrzdData('<?php echo $rowATZ21; ?>');">Linked Firm/ Workplace:</a></label>
                                                                <?php } else { ?>
                                                                <label for="linkedFirm" class="control-label col-md-4">Linked Firm/ Workplace</label>
                                                                <?php } ?>
                                                                <div  class="col-md-8">
                                                                    <span><?php echo $row[21]; ?></span>
                                                                </div>
                                                            </div>
                                                            <div class="form-group form-group-sm">
                                                                <?php if($cnt > 0 && $row[22] != $rowATZ22) { ?>
                                                                <label data-toggle="tooltip" title="<?php echo $rowATZ22; ?>" for="lnkdFirmLoc" class="control-label col-md-4"><a href="#" style="color:red;" onclick="dsplyAuthrzdData('<?php echo $rowATZ22; ?>');">Site/Branch:</a></label>
                                                                <?php } else { ?>
                                                                <label for="lnkdFirmLoc" class="control-label col-md-4">Site/Branch:</label>
                                                                <?php } ?>
                                                                <div  class="col-md-8">
                                                                    <span><?php echo $row[22]; ?></span>
                                                                </div>
                                                            </div>  
                                                            <div class="form-group form-group-sm">
                                                                <?php if($cnt > 0 && $row[15] != $rowATZ15) { ?>
                                                                <label data-toggle="tooltip" title="<?php echo $rowATZ15; ?>" for="email" class="control-label col-md-4"><a href="#" style="color:red;" onclick="dsplyAuthrzdData('<?php echo $rowATZ15; ?>');">Email:</a></label>
                                                                <?php } else { ?>
                                                                <label for="email" class="control-label col-md-4">Email:</label>
                                                                <?php } ?>
                                                                <div  class="col-md-8">
                                                                    <span><?php echo $row[15]; ?></span>
                                                                </div>
                                                            </div> 
                                                            <div class="form-group form-group-sm">
                                                                <?php if($cnt > 0 && (!($row[16] == $rowATZ16 && $row[17] == $rowATZ17))) { ?>
                                                                <label data-toggle="tooltip" title="<?php echo $rowATZ16.";".$rowATZ17; ?>" for="telephone" class="control-label col-md-4"><a href="#" style="color:red;" onclick="dsplyAuthrzdData('<?php echo $rowATZ16.";".$rowATZ17; ?>');">Contact Nos:</a></label>
                                                                <?php } else { ?>
                                                                <label for="telephone" class="control-label col-md-4">Contact Nos:</label>
                                                                <?php } ?>
                                                                <div  class="col-md-8">
                                                                    <span><?php echo $row[16]; ?></span>,<span><?php echo $row[17]; ?></span>                                      
                                                                </div>
                                                            </div>     
                                                            <div class="form-group form-group-sm">
                                                                <?php if($cnt > 0 && $row[18] != $rowATZ18) { ?>
                                                                <label data-toggle="tooltip" title="<?php echo $rowATZ18; ?>" for="fax" class="control-label col-md-4"><a href="#" style="color:red;" onclick="dsplyAuthrzdData('<?php echo $rowATZ18; ?>');">Fax:</a></label>
                                                                <?php } else { ?>
                                                                <label for="fax" class="control-label col-md-4">Fax:</label>
                                                                <?php } ?>
                                                                <div  class="col-md-8">
                                                                    <span><?php echo $row[18]; ?></span>  
                                                                </div>
                                                            </div> 
                                                        </fieldset>                                                
                                                    </div>
                                                    <div class="col-lg-4">
                                                        <fieldset class="basic_person_fs2"><legend class="basic_person_lg">Relationship Type</legend>                                    
                                                            <div class="form-group form-group-sm">
                                                                <?php if($cnt > 0 && $row[23] != $rowATZ23) { ?>
                                                                <label data-toggle="tooltip" title="<?php echo $rowATZ23; ?>" for="relation" class="control-label col-md-4"><a href="#" style="color:red;" onclick="dsplyAuthrzdData('<?php echo $rowATZ23; ?>');">Relation:</a></label>
                                                                <?php } else { ?>
                                                                <label for="relation" class="control-label col-md-4">Relation:</label>
                                                                <?php } ?>
                                                                <div  class="col-md-8">
                                                                    <span><?php echo $row[23]; ?></span>  
                                                                </div>
                                                            </div>                                            
                                                            <div class="form-group form-group-sm">
                                                                <label for="causeOfRelation" class="control-label col-md-4">Status:</label>
                                                                <div  class="col-md-8">
                                                                    <span><?php echo $row[24]; ?></span>  
                                                                </div>
                                                            </div>
                                                            <div class="form-group form-group-sm">
                                                                <?php if($cnt > 0 && $row[25] != $rowATZ25) { ?>
                                                                <label data-toggle="tooltip" title="<?php echo $v_BranchATZ; ?>" for="bnkBranch" class="control-label col-md-4"><a href="#" style="color:red;" onclick="dsplyAuthrzdData('<?php echo $v_BranchATZ; ?>');">Branch:</a></label>
                                                                <?php } else { ?>
                                                                <label for="bnkBranch" class="control-label col-md-4">Branch:</label>
                                                                <?php } ?>
                                                                <div  class="col-md-8">
                                                                    <span><?php
                                                                        echo getGnrlRecNm("org.org_sites_locations", "location_id",
                                                                                "site_desc||'('||location_code_name||')'", $row[25]);
                                                                        ?></span>
                                                                </div>
                                                            </div>  
                                                            <div class="form-group form-group-sm">
                                                                <?php if($cnt > 0 && $row[26] != $rowATZ26) { ?>
                                                                <label data-toggle="tooltip" title="<?php echo $rowATZ26; ?>" for="startDate" class="control-label col-md-4"><a href="#" style="color:red;" onclick="dsplyAuthrzdData('<?php echo $rowATZ26; ?>');">Start Date:</a></label>
                                                                <?php } else { ?>
                                                                <label for="startDate" class="control-label col-md-4">Start Date:</label>
                                                                <?php } ?>
                                                                <div  class="col-md-8">
                                                                    <span><?php echo $row[26]; ?></span>
                                                                </div>
                                                            </div>      
                                                            <div class="form-group form-group-sm">
                                                                <?php if($cnt > 0 && $row[27] != $rowATZ27) { ?>
                                                                <label data-toggle="tooltip" title="<?php echo $rowATZ27; ?>" for="endDate" class="control-label col-md-4"><a href="#" style="color:red;" onclick="dsplyAuthrzdData('<?php echo $rowATZ27; ?>');">End Date:</a></label>
                                                                <?php } else { ?>
                                                                <label for="endDate" class="control-label col-md-4">End Date:</label>
                                                                <?php } ?>
                                                                <div  class="col-md-8">
                                                                    <span><?php echo $row[27]; ?></span>
                                                                </div>
                                                            </div>  
                                                        </fieldset>                                                
                                                    </div>
                                                </div> 
                                                <div class="row"><!-- ROW 3 -->
                                                    <div class="col-lg-4">
                                                        <fieldset class="basic_person_fs3"><legend class="basic_person_lg">Address</legend> 
                                                            <div class="form-group form-group-sm">
                                                                <?php if($cnt > 0 && $row[14] != $rowATZ14) { ?>
                                                                <label data-toggle="tooltip" title="<?php echo $rowATZ14; ?>" for="postalAddress" class="control-label col-md-4"><a href="#" style="color:red;" onclick="dsplyAuthrzdData('<?php echo $rowATZ14; ?>');">Postal Address:</a></label>
                                                                <?php } else { ?>
                                                                <label for="postalAddress" class="control-label col-md-4">Postal Address:</label>
                                                                <?php } ?>
                                                                <div  class="col-md-8">
                                                                    <span><?php echo $row[14]; ?></span>
                                                                </div>
                                                            </div>
                                                            <div class="form-group form-group-sm">
                                                                <?php if($cnt > 0 && $row[13] != $rowATZ13) { ?>
                                                                <label data-toggle="tooltip" title="<?php echo $rowATZ13; ?>" for="residentialAddress" class="control-label col-md-4"><a href="#" style="color:red;" onclick="dsplyAuthrzdData('<?php echo $rowATZ13; ?>');">Residential Address:</a></label>
                                                                <?php } else { ?>
                                                                <label for="residentialAddress" class="control-label col-md-4">Residential Address:</label>
                                                                <?php } ?>
                                                                <div  class="col-md-8">
                                                                    <span><?php echo $row[13]; ?></span>
                                                                </div>
                                                            </div> 
                                                        </fieldset>                                        
                                                    </div>
                                                    <div class="col-lg-8"> 
                                                        <fieldset class="basic_person_fs3"><legend class="basic_person_lg">National ID Cards</legend> 
                                                            <div  class="col-md-12">
                                                                <table id="nationalIDTblEDT" class="table table-striped table-bordered table-responsive" cellspacing="0" width="100%" style="width:100%;">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Country</th>
                                                                            <th>ID Type</th>
                                                                            <th>ID No.</th>
                                                                            <th>Date Issued</th>
                                                                            <th>Expiry Date</th>
                                                                            <th>Other Information</th>
                                                                            <th <?php echo $shwHydNtlntySts; ?>>Status</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <?php
                                                                        $result1 = get_PersonAllNtnlty($pkID, "Individual Customer");
                                                                        $cntr = 0;
                                                                        while ($row1 = loc_db_fetch_array($result1)) {
                                                                            $cntr++;
                                                                            $row1ATZ0 = ""; $row1ATZ1 = ""; $row1ATZ2 = ""; $row1ATZ3 = ""; $row1ATZ4 = ""; $row1ATZ5 = "";
                                                                            $row1ATZ6 = ""; $row1ATZ7 = "";
                                                                            if($row1[8] > 0 && $row1[7] === "Yes"){
                                                                                $result1ATZ = get_PersonAllNtnltyATZ($row1[0]);
                                                                                while ($row1ATZ = loc_db_fetch_array($result1ATZ)) {
                                                                                    $row1ATZ0 = $row1ATZ[0]; $row1ATZ1 = $row1ATZ[1]; $row1ATZ2 = $row1ATZ[2]; $row1ATZ3 = $row1ATZ[3];
                                                                                    $row1ATZ4 = $row1ATZ[4]; $row1ATZ5 = $row1ATZ[5]; $row1ATZ6 = $row1ATZ[6]; $row1ATZ7 = $row1ATZ[7];
                                                                                }
                                                                            }
                                                                            ?>
                                                                            <tr id="ntnlIDCardsRow<?php echo $cntr; ?>">
                                                                                <td>
                                                                                    <?php 
                                                                                        echo dsplyTblData($row1[1], $row1ATZ1, $row1[8], $row1[7]);
                                                                                    ?>
                                                                                </td>
                                                                                <td>
                                                                                    <?php 
                                                                                        echo dsplyTblData($row1[2], $row1ATZ2, $row1[8], $row1[7]);
                                                                                    ?>
                                                                                </td>
                                                                                <td><?php 
                                                                                        echo dsplyTblData($row1[3], $row1ATZ3, $row1[8], $row1[7]);
                                                                                    ?>
                                                                                </td>
                                                                                <td><?php 
                                                                                        echo dsplyTblData($row1[4], $row1ATZ4, $row1[8], $row1[7]);
                                                                                    ?>
                                                                                </td>
                                                                                <td><?php 
                                                                                        echo dsplyTblData($row1[5], $row1ATZ5, $row1[8], $row1[7]);
                                                                                    ?>
                                                                                </td>
                                                                                <td><?php 
                                                                                        echo dsplyTblData($row1[6], $row1ATZ6, $row1[8], $row1[7]);
                                                                                    ?>
                                                                                </td>
                                                                                <td <?php echo $shwHydNtlntySts; ?>> 
                                                                                    <?php 
                                                                                    if($row1[8] < 0){
                                                                                        echo "<span style='color:red;'><b>Deleted</b></span>";
                                                                                    } else  {
                                                                                       if($row1[7] === "No"){
                                                                                            echo "<span style='color:blue;'><b>New</b></span>";
                                                                                       } else {
                                                                                           echo "&nbsp;";
                                                                                       }
                                                                                    }
                                                                                    ?>
                                                                                </td>
                                                                            </tr>
                                                                        <?php } ?>
                                                                    </tbody>
                                                                </table>
                                                            </div> 
                                                        </fieldset>
                                                    </div>
                                                </div>  
                                            </form>  
                                        </div>
                                        <div id="prfBCOPAddPrsnDataEDT" class="tab-pane fade" style="border:none !important;"></div>
                                        <div id="prflBCOPAttchmntsEDT" class="tab-pane fade" style="border:none !important;"></div>      
                                    </div>                        
                                </div>                         
                            </div>                
                        </div>          
                    </div>
                    <?php
                }
            }
        } 
        else if ($vwtyp == "1") {
            /** ADDITIONAL PERSON DATA**/
        } else if ($vwtyp == "2") {
            /* ATTACHMENTS */
        } else if ($vwtyp == "3") {
            
        } else if ($vwtyp == "4") {
            
        } else if ($vwtyp == "5") {
            /* ADD NATIONAL ID */
            $ntnlIDpKey = isset($_POST['ntnlIDpKey']) ? cleanInputData($_POST['ntnlIDpKey']) : -1;
            $mkReadOnly = "";
            $mkReadOnlyDsbld = "";
            if($vwtypActn == "VIEW"){
                $mkReadOnly = "readonly=\"readonly\"";
                $mkReadOnlyDsbld = "disabled=\"true\""; 
            }
            
            ?>
            <form class="form-horizontal" id="ntnlIDCardsForm" style="padding:5px 20px 5px 20px;">
                <div class="row">
                    <div class="form-group form-group-sm">
                        <label for="ntnlIDCardsCountry" class="control-label col-md-4">Country:</label>
                        <div class="col-md-8">
                            <input class="form-control" size="16" type="hidden" id="ntnlIDpKey" value="<?php echo $ntnlIDpKey; ?>" readonly="">
                            <select <?php echo $mkReadOnlyDsbld; ?> class="form-control" id="ntnlIDCardsCountry">
                                <option value="" selected disabled>Please Select...</option>
                                <?php
                                $brghtStr = "";
                                $isDynmyc = FALSE;
                                $titleRslt = getLovValues("%", "Both", 0, 100, $brghtStr, getLovID("Countries"), $isDynmyc, -1, "", "");
                                while ($titleRow = loc_db_fetch_array($titleRslt)) {
                                    $selectedTxt = "";
                                    ?>
                                    <option value="<?php echo $titleRow[0]; ?>" <?php echo $selectedTxt; ?>><?php echo $titleRow[0]; ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                    </div> 
                    <div class="form-group form-group-sm">
                        <label for="ntnlIDCardsIDTyp" class="control-label col-md-4">ID Type:</label>
                        <div class="col-md-8">
                            <select <?php echo $mkReadOnlyDsbld; ?> class="form-control selectpicker" id="ntnlIDCardsIDTyp">  
                                <option value="" selected disabled>Please Select...</option>
                                <?php
                                $brghtStr = "";
                                $isDynmyc = FALSE;
                                $titleRslt = getLovValues("%", "Both", 0, 100, $brghtStr, getLovID("National ID Types"), $isDynmyc, -1, "",
                                        "");
                                while ($titleRow = loc_db_fetch_array($titleRslt)) {
                                    $selectedTxt = "";
                                    ?>
                                    <option value="<?php echo $titleRow[0]; ?>" <?php echo $selectedTxt; ?>><?php echo $titleRow[0]; ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group form-group-sm">
                        <label for="ntnlIDCardsIDNo" class="control-label col-md-4">ID No:</label>
                        <div class="col-md-8">
                            <input <?php echo $mkReadOnly; ?> class="form-control rqrdFld" id="ntnlIDCardsIDNo" type = "text" placeholder="ID No." value=""/>
                        </div>
                    </div>
                    <div class="form-group form-group-sm">
                        <label for="ntnlIDCardsDateIssd" class="control-label col-md-4">Date Issued:</label>
                        <div class="col-md-8">
                            <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                <input class="form-control" size="16" type="text" id="ntnlIDCardsDateIssd" value="" readonly="readonly">
                                <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group form-group-sm">
                        <label for="ntnlIDCardsExpDate" class="control-label col-md-4">Expiry Date:</label>
                        <div class="col-md-8">
                            <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                <input class="form-control" size="16" type="text" id="ntnlIDCardsExpDate" value="" readonly="readonly">
                                <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group form-group-sm">
                        <label for="ntnlIDCardsOtherInfo" class="control-label col-md-4">Other Information:</label>
                        <div class="col-md-8">
                            <textarea <?php echo $mkReadOnly; ?> class="form-control" id="ntnlIDCardsOtherInfo" cols="2" placeholder="Other Information" rows="2"></textarea>
                        </div>
                    </div>
                </div>
                <div class="row" style="float:right;padding-right: 1px;">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <?php if($vwtypActn!="VIEW") { ?>
                        <button type="button" class="btn btn-primary" onclick="saveIndCustNtnlIDForm('myFormsModalx', '<?php echo $ntnlIDpKey; ?>', <?php echo $subPgNo; ?>, '<?php echo $vwtyp; ?>');">Save Changes</button>
                    <?php } ?>
                </div>
            </form>
            <?php
        }
    } else if ($subPgNo == 1.2 || $subPgNo == 1.3) {//CORPORATE CUSTOMER & CUSTOMER GROUP
        
        $trnsType = "Corporate Customers";
        $formTitle = "Corporate Customers - Attachments";
        $addDtaFormType = "Corporate Customer";
        $drctOrGrpMemberLbl = "Directors/Joint Account Holders/Trustees and Beneficiary(s)";
        
        $cstCls = "Customer Classifications - Corporate";
        if($subPgNo == 1.3){
            $cstCls = "Customer Classifications - Customer Group";
        }

        if ($vwtyp == "0") {
            /* BASIC DATA */
            //var_dump("pkID =".$pkID);
            $trnsStatus = "Incomplete";
            $sbmtdTrnsHdrID = $pkID;
            $voidedTrnsHdrID = -1;
            $rqstatusColor = "red";
            $mkReadOnly = "";
            $mkReadOnlyDsbld = "";
            $makeHidden = "";
            $makeShown = "style=\"display:none !important;\"";
            $custNameLbl = "Customer Name";
            $pstlAddressLbl = "Postal Address";
            $rsdntlAddressLbl = "Trading Address";
            $emplyeeSize = "No. Of Employees";
            
            if($subPgNo == 1.3){
                $trnsType = "Customer Groups";
                $formTitle = "Customer Groups - Attachments"; 
                $addDtaFormType = "Customer Group";
                $drctOrGrpMemberLbl = "Group Members";
                $makeHidden = "style=\"display:none !important;\"";
                $makeShown = "style=\"display:block !important;\"";
                $custNameLbl = "Group Name";
                $pstlAddressLbl = "Postal Address";
                $rsdntlAddressLbl = "Meeting Place";
                $emplyeeSize = "Group Size";                
            }
            
            //$routingID = getMCFMxRoutingID($sbmtdTrnsHdrID, "Loan Applications");
            
            if ($vwtypActn === "ADD") {
                    $prsnBranchID = get_Person_BranchID($prsnid);
                    $prsnBranch = getGnrlRecNm("org.org_sites_locations", "location_id", "site_desc||' ('||location_code_name||')'", "pasn.get_prsn_siteid($prsnid)");
                
                    ?>
                    <div class="row" style="margin: 0px 0px 10px 0px !important;">
                        <div class="col-md-12" style="padding:0px 1px 5px 1px !important;">
                            <div style="float:left;">
                                <button type="button" class="btn btn-default btn-sm" style="height:30px;">
                                        <span style="font-weight:bold;">Status: </span><span style="color:<?php echo $rqstatusColor; ?>;font-weight: bold;"><?php echo $trnsStatus; ?></span>
                                </button>                                                    
                            </div>
                            <div class="" style="float:right;"> 
                                <button type="button" class="btn btn-default btn-sm" style="" onclick="saveBasicDataCorp(<?php echo $pgNo; ?>, <?php echo $subPgNo; ?>, <?php echo $vwtyp; ?>, 0);">
                                    <img src="cmn_images/FloppyDisk.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">
                                    SAVE
                                </button>  
                            </div>
                        </div>
                    </div>
                    <div class="row">                  
                        <div class="col-md-12">                        
                            <form class="form-horizontal">
                                <div class="row"><!-- ROW 1 -->                                
                                    <div class="col-lg-6">
                                        <fieldset class="basic_person_fs5"><legend class="basic_person_lg1">Customer Information</legend>
                                            <div class="form-group form-group-sm">
                                                <label for="idNo" class="control-label col-md-4">ID No:</label>
                                                <input class="form-control" id="custID" type = "hidden" placeholder="Customer ID" value="<?php echo -1; ?>"/>
                                                <input class="form-control" id="profileID" type = "hidden" placeholder="Profile ID" value="<?php echo -1; ?>"/>
                                                <div class="col-md-8">
                                                    <input class="form-control" id="idNo" type = "text" placeholder="ID No." value="<?php echo ""; ?>"/>
                                                </div>
                                            </div> 
                                            <div class="form-group form-group-sm">
                                                <label for="clsfctn" class="control-label col-md-4">Classification:</label>
                                                <div  class="col-md-8">
                                                    <select class="form-control" id="clsfctn" >
                                                        <?php
                                                        $brghtStr = "";
                                                        $isDynmyc = FALSE;
                                                        $titleRslt = getLovValues("%", "Both", 0, 100, $brghtStr,
                                                                getLovID($cstCls), $isDynmyc, -1, "", "");
                                                        while ($titleRow = loc_db_fetch_array($titleRslt)) {
                                                            $selectedTxt = "";
                                                            ?>
                                                            <option value="<?php echo $titleRow[0]; ?>" <?php echo $selectedTxt; ?>><?php echo $titleRow[0]; ?></option>
                                                            <?php
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>  
                                            <div class="form-group form-group-sm">
                                                <label for="custName" class="control-label col-md-4"><?php echo $custNameLbl; ?>:</label>
                                                <div  class="col-md-8">
                                                    <input class="form-control rqrdFld" id="custName" type = "text" placeholder="<?php echo $custNameLbl; ?>" value="<?php echo ""; ?>"/>
                                                </div>
                                            </div>    
                                            <div <?php echo $makeHidden; ?> class="form-group form-group-sm">
                                                <label for="natureOfBus" class="control-label col-md-4">Nature of Business:</label>
                                                <div  class="col-md-8">
                                                    <textarea class="form-control rqrdFld" id="natureOfBus" cols="2" placeholder="Nature of Business" rows="3"><?php echo ""; ?></textarea>
                                                </div>
                                            </div>
                                            <?php if($subPgNo == 1.3){ ?>
                                            <div class="form-group form-group-sm">
                                                <label for="dateOfEstblshmnt" class="control-label col-md-4">Date of Establishment:</label>
                                                <div class="col-md-8">
                                                    <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                                        <input class="form-control" size="16" type="text" id="dateOfEstblshmnt" value="<?php echo ""; ?>" readonly="">
                                                        <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                    </div>
                                                </div>
                                            </div> 
                                            <?php } ?>
                                        </fieldset>
                                    </div>
                                    <div class="col-lg-6">
                                        <fieldset class="basic_person_fs5"><legend class="basic_person_lg1">Relationship Type</legend>                                    
                                            <div style="display:none !important;" class="form-group form-group-sm">
                                                <label for="relation" class="control-label col-md-4">Relation:</label>
                                                <div  class="col-md-8">
                                                    <select class="form-control" id="relation" >
                                                        <option value="Customer" selected="selected">Customer</option>
                                                    </select>
                                                </div>
                                            </div>  
                                            <?php if($subPgNo == 1.2){ ?>
                                            <div class="form-group form-group-sm">
                                                <label for="dateOfEstblshmnt" class="control-label col-md-4">Date of Establishment:</label>
                                                <div class="col-md-8">
                                                    <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                                        <input class="form-control" size="16" type="text" id="dateOfEstblshmnt" value="<?php echo ""; ?>" readonly="">
                                                        <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                    </div>
                                                </div>
                                            </div> 
                                            <?php } ?>
                                            <div class="form-group form-group-sm">
                                                <label for="status" class="control-label col-md-4">Status:</label>
                                                <div  class="col-md-8">
                                                    <input type="text" style="color:<?php echo $rqstatusColor; ?>;font-weight: bold;" class="form-control" aria-label="..." id="status" value="<?php echo $trnsStatus; ?>" readonly="">
                                                </div>
                                            </div>
                                            <div class="form-group form-group-sm">
                                                <label for="bnkBranch" class="control-label col-md-4">Branch:</label>
                                                <div  class="col-md-8">
                                                    <div class="input-group" style="width:100% !important;">
                                                        <input type="text" class="form-control" aria-label="..." id="bnkBranch" value="<?php echo $prsnBranch; ?>" readonly="">
                                                        <input type="hidden" id="bnkBranchID" value="<?php echo $prsnBranchID; ?>">
                                                        <input type="hidden" id="gnrlOrgID" value="<?php echo $orgID; ?>">                                                                        
                                                        <label style="display:none !important;" class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Sites/Locations New', 'gnrlOrgID', '', '', 'radio', true, '<?php echo $prsnBranchID; ?>', 'bnkBranchID', 'bnkBranch', 'clear', 1, '');">
                                                                <span class="glyphicon glyphicon-th-list"></span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>  
                                            <div class="form-group form-group-sm">
                                                <label for="startDate" class="control-label col-md-4">Start Date:</label>
                                                <div  class="col-md-8">
                                                    <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input3" data-link-format="yyyy-mm-dd">
                                                        <input class="form-control" size="16" type="text" id="startDate" value="<?php echo ""; ?>" readonly="">
                                                        <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                        <span class="input-group-addon" onclick="javascript:unfreezeDialog();"><span class="glyphicon glyphicon-info-sign"></span></span>
                                                    </div>
                                                </div>
                                            </div>      
                                            <div class="form-group form-group-sm">
                                                <label for="endDate" class="control-label col-md-4">End Date:</label>
                                                <div  class="col-md-8">
                                                    <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input4" data-link-format="yyyy-mm-dd">
                                                        <input class="form-control" size="16" type="text" id="endDate" value="<?php echo ""; ?>" readonly="">
                                                        <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                        <span class="input-group-addon" onclick="javascript:unfreezeDialog();"><span class="glyphicon glyphicon-info-sign"></span></span>
                                                    </div>
                                                </div>
                                            </div>  
                                        </fieldset>                                                
                                    </div>                                                   
                                </div>    
                                <div class="row"><!-- ROW 2 -->                                
                                    <div class="col-lg-6">
                                        <fieldset class="basic_person_fs5"><legend class="basic_person_lg1">Contact Information</legend>
                                            <div class="form-group form-group-sm">
                                                <label for="pstlAddress" class="control-label col-md-4">Postal Address:</label>
                                                <div  class="col-md-8">
                                                    <textarea class="form-control" id="pstlAddress" cols="2" placeholder="Postal Address" rows="5"><?php echo ""; ?></textarea>
                                                </div>
                                            </div>
                                            <?php if($subPgNo == 1.2){ ?>
                                            <div class="form-group form-group-sm">
                                                <label for="resAddress" class="control-label col-md-4">Trading Address:</label>
                                                <div  class="col-md-8">
                                                    <textarea class="form-control rqrdFld" id="resAddress" cols="2" placeholder="Trading Address" rows="5"><?php echo ""; ?></textarea>
                                                </div>
                                            </div>
                                            <?php } ?>
                                            <div class="form-group form-group-sm">
                                                <label for="email" class="control-label col-md-4">Email:</label>
                                                <div  class="col-md-8">
                                                    <input class="form-control" id="email" type = "email" placeholder="<?php echo $admin_email; ?>" value="<?php echo ""; ?>"/>
                                                </div>
                                            </div> 
                                            <div class="form-group form-group-sm">
                                                <label for="contactNos" class="control-label col-md-4">Contact Nos:</label>
                                                <div  class="col-md-8">
                                                    <input class="form-control rqrdFld" id="contactNos" type = "text" placeholder="Contact Nos" value="<?php echo ""; ?>"/>                                     
                                                </div>
                                            </div>     
                                            <div <?php echo $makeHidden; ?> class="form-group form-group-sm">
                                                <label for="faxNo" class="control-label col-md-4">Fax:</label>
                                                <div  class="col-md-8">
                                                    <input class="form-control" id="faxNo" type = "text" placeholder="FaxNo" value="<?php echo ""; ?>"/>
                                                </div>
                                            </div> 
                                        </fieldset>                                                
                                    </div>
                                    <div class="col-lg-6"> 
                                        <fieldset class="basic_person_fs5"><legend class="basic_person_lg1">Registration Details</legend>
                                            <div <?php echo $makeShown; ?> class="form-group form-group-sm" id="meetingDaysDiv">
                                                <label for="meetingDays" id="meetingDaysLbl" class="control-label col-md-4">Meeting Days:</label>
                                                <div  class="col-md-8">
                                                    <select class="form-control" name="meetingDays[]" id="meetingDays" multiple>
                                                        <!--<option value="">&nbsp;</option>-->
                                                        <?php
                                                        $brghtStr = "";
                                                        $isDynmyc = FALSE;
                                                        $titleRslt = getLovValues("%", "Both", 0, 100, $brghtStr,
                                                                getLovID("MCF Meeting Days"), $isDynmyc, -1, "", "");
                                                        while ($titleRow = loc_db_fetch_array($titleRslt)) {
                                                            $selectedTxt = "";
                                                            ?>
                                                            <option value="<?php echo $titleRow[0]; ?>" <?php echo $selectedTxt; ?>><?php echo $titleRow[0]; ?></option>
                                                            <?php
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div> 
                                            <?php if($subPgNo == 1.3){ ?>
                                            <div class="form-group form-group-sm">
                                                <label for="resAddress" class="control-label col-md-4"><?php echo $rsdntlAddressLbl; ?>:</label>
                                                <div  class="col-md-8">
                                                    <textarea class="form-control rqrdFld" id="resAddress" cols="2" placeholder="<?php echo $rsdntlAddressLbl; ?>" rows="5"><?php echo ""; ?></textarea>
                                                </div>
                                            </div>
                                            <?php } ?>
                                            <div <?php echo $makeHidden; ?> class="form-group form-group-sm">
                                                <label for="brandName" class="control-label col-md-4">Brand Name:</label>
                                                <div  class="col-md-8">
                                                    <input class="form-control rqrdFld" id="brandName" type = "text" placeholder="Brand Name" value="<?php echo ""; ?>"/>
                                                </div>                                                                
                                            </div>                                                            
                                            <div <?php echo $makeHidden; ?> class="form-group form-group-sm">
                                                <label for="orgType" class="control-label col-md-4">Organization Type:</label>
                                                <div  class="col-md-8">
                                                    <select class="form-control" id="orgType" >
                                                        <?php
                                                        $brghtStr = "";
                                                        $isDynmyc = FALSE;
                                                        $titleRslt = getLovValues("%", "Both", 0, 100, $brghtStr,
                                                                getLovID("Organisation Types"), $isDynmyc, -1, "", "");
                                                        while ($titleRow = loc_db_fetch_array($titleRslt)) {
                                                            $selectedTxt = "";
                                                            ?>
                                                            <option value="<?php echo $titleRow[0]; ?>" <?php echo $selectedTxt; ?>><?php echo $titleRow[0]; ?></option>
                                                            <?php
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div <?php echo $makeHidden; ?> class="form-group form-group-sm">
                                                <label for="regNo" class="control-label col-md-4">Business Reg. No:</label>
                                                <div  class="col-md-8">
                                                    <input class="form-control rqrdFld" id="regNo" type = "text" placeholder="Business Registration No" value="<?php echo ""; ?>"/>
                                                </div>                                                                   
                                            </div>                                                            
                                            <div <?php echo $makeHidden; ?> class="form-group form-group-sm">
                                                <label for="dateOfIncorp" class="control-label col-md-4">Date of Incorporation:</label>
                                                <div class="col-md-8">
                                                    <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                                        <input class="form-control rqrdFld" size="16" type="text" id="dateOfIncorp" value="<?php echo ""; ?>" readonly="">
                                                        <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                    </div>
                                                </div>
                                            </div> 
                                            <div <?php echo $makeHidden; ?> class="form-group form-group-sm">
                                                <label for="typeOfIncorp" class="control-label col-md-4">Type of Incorporation:</label>
                                                <div  class="col-md-8">
                                                    <select class="form-control" id="typeOfIncorp" >
                                                        <?php
                                                        $brghtStr = "";
                                                        $isDynmyc = FALSE;
                                                        $titleRslt = getLovValues("%", "Both", 0, 100, $brghtStr,
                                                                getLovID("Types of Incorporation"), $isDynmyc, -1, "", "");
                                                        while ($titleRow = loc_db_fetch_array($titleRslt)) {
                                                            $selectedTxt = "";
                                                            ?>
                                                            <option value="<?php echo $titleRow[0]; ?>" <?php echo $selectedTxt; ?>><?php echo $titleRow[0]; ?></option>
                                                            <?php
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>                                                            
                                            <div <?php echo $makeHidden; ?> class="form-group form-group-sm">
                                                <label for="vatNo" class="control-label col-md-4">VAT Number:</label>
                                                <div  class="col-md-8">
                                                    <input class="form-control" id="vatNo" type = "text" placeholder="VAT Number" value="<?php echo ""; ?>"/>
                                                </div>                                                                  
                                            </div>  
                                            <div <?php echo $makeHidden; ?> class="form-group form-group-sm">
                                                <label for="tinNo" class="control-label col-md-4">TIN Number:</label>
                                                <div  class="col-md-8">
                                                    <input class="form-control rqrdFld" id="tinNo" type = "text" placeholder="TIN Number" value="<?php echo ""; ?>"/>
                                                </div>                                                                
                                            </div>  
                                            <div <?php echo $makeHidden; ?> class="form-group form-group-sm">
                                                <label for="ssnitRegNo" class="control-label col-md-4">SSNIT Reg. Number:</label>
                                                <div  class="col-md-8">
                                                    <input class="form-control" id="ssnitRegNo" type = "text" placeholder="SNIT Reg. Number" value="<?php echo ""; ?>"/>
                                                </div> 
                                            </div>                                       
                                            <div class="form-group form-group-sm">
                                                <label for="noOfEmp" class="control-label col-md-4"><?php echo $emplyeeSize; ?>:</label>
                                                <div  class="col-md-8">
                                                    <input class="form-control rqrdFld" id="noOfEmp" type = "number" min="0" placeholder="<?php echo $emplyeeSize; ?>" value="0"/>
                                                </div> 
                                            </div>                                                             
                                        </fieldset>   
                                    </div>
                                </div> 
                            </form>                           
                        </div>                
                    </div>          
                    <?php
            } else {
                //EDIT AND VIEW
                
                $tblNm1 = "mcf.mcf_customers_corp";
                $tblNm2 = "mcf.mcf_customers";

                $cnt = getCustDataChngPndngCount($pkID);
                if($cnt > 0){
                    $tblNm1 = "mcf.mcf_customers_corp_hstrc";
                    $tblNm2 = "mcf.mcf_customers_hstrc";        
                }                
                
                $result = get_CorpCustDet($pkID, $tblNm1, $tblNm2);
                $shwHydNtlntySts = "style=\"display:none !important;\"";

                //$result = get_CorpCustDet($pkID);          
                while ($row = loc_db_fetch_array($result)) {

                    $trnsStatus = $row[23];
                    if($vwtypActn == "VIEW" || ($trnsStatus == "Initiated" || $trnsStatus == "Approved" || $trnsStatus == "Unauthorized" || $trnsStatus == "Authorized")){
                        $mkReadOnly = "readonly=\"readonly\"";
                        $mkReadOnlyDsbld = "disabled=\"true\"";
                    }
                    
                if($trnsStatus == "Unauthorized" || $trnsStatus == "Initiated"){
                    $shwHydNtlntySts = "style=\"display:block !important;\"";
                }                    
                    
                $tblNmCustIndAuthrzd = "";
                $tblNmCustAuthrzd = ""; 
                $lblColor = "red";
                $resultAuthrzd = "";

                //if($trnsStatus == "Unauthorized" || $trnsStatus == "Initiated"){
                if($cnt > 0){
                    $tblNmCustCorpAuthrzd = "mcf.mcf_customers_corp";
                    $tblNmCustAuthrzd = "mcf.mcf_customers";        
                    $resultAuthrzd = get_CorpCustDet($pkID, $tblNmCustCorpAuthrzd, $tblNmCustAuthrzd);
                    while ($rowATZ = loc_db_fetch_array($resultAuthrzd)) {
                        $rowATZ0 = $rowATZ[0]; $rowATZ1 = $rowATZ[1]; $rowATZ2 = $rowATZ[2]; $rowATZ3 = $rowATZ[3]; $rowATZ4 = $rowATZ[4];
                        $rowATZ5 = $rowATZ[5]; $rowATZ6 = $rowATZ[6]; $rowATZ7 = $rowATZ[7]; $rowATZ8 = $rowATZ[8]; $rowATZ9 = $rowATZ[9];
                        $rowATZ10 = $rowATZ[10]; $rowATZ11 = $rowATZ[11]; $rowATZ12 = $rowATZ[12]; $rowATZ13 = $rowATZ[13]; $rowATZ14 = $rowATZ[14];
                        $rowATZ15 = $rowATZ[15]; $rowATZ16 = $rowATZ[16]; $rowATZ17 = $rowATZ[17]; $rowATZ18 = $rowATZ[18]; $rowATZ19 = $rowATZ[19];
                        $rowATZ20 = $rowATZ[20]; $rowATZ21 = $rowATZ[21]; $rowATZ22 = $rowATZ[22]; $rowATZ23 = $rowATZ[23]; $rowATZ24 = $rowATZ[24];
                        $rowATZ25 = $rowATZ[25]; $rowATZ26 = $rowATZ[26]; $rowATZ27 = $rowATZ[27]; $rowATZ28 = $rowATZ[28]; $rowATZ29 = $rowATZ[29];

                        $v_BranchATZ = getGnrlRecNm("org.org_sites_locations", "location_id", "site_desc||' ('||location_code_name||')' ", $rowATZ24);
                    }               
                }                    

                    ?>
                        <div class="row" style="margin: 0px 0px 10px 0px !important;">
                            <div class="col-md-12" style="padding:0px 0px 0px 0px !important;">
                                <button type="button" class="btn btn-default btn-sm phone-only-btn" onclick="openATab('#allmodules', 'grp=17&typ=1&pg=10&vtyp=0');">Basic Data</button>
                                <button type="button" class="btn btn-default btn-sm phone-only-btn" onclick="openATab('#prfBCOPAddPrsnDataEDT', 'grp=17&typ=1&pg=10&vtyp=1');">Additional Data</button>
                                <!--<button type="button" class="btn btn-default btn-sm phone-only-btn" onclick="openATab('#prflBCOPAttchmntsEDT', 'grp=17&typ=1&pg=10&vtyp=2');">Attachments</button>-->
                            </div>
                        </div>
                        <div class="row" style="margin: 0px 0px 10px 0px !important;">
                            <div class="col-md-12" style="padding:0px 1px 5px 1px !important;">
                                <div style="float:left;"> 
                                    <button type="button" class="btn btn-default btn-sm" style="height:30px;" id="myVmsTrnsStatusBtn">
                                            <span style="font-weight:bold;">Status: </span><span style="color:<?php echo $rqstatusColor; ?>;font-weight: bold;"><?php echo $trnsStatus; ?></span>
                                    </button>
                                    <?php  if($vwtypActn != "VIEW") { ?>
                                    <button type="button" class="btn btn-default btn-sm" style="height:30px;" onclick="getCustomersForm('myFormsModalLg', 'myFormsModalBodyLg', 'myFormsModalTitleLg', 'Edit Corporate Customer', 11, <?php echo $subPgNo; ?>, 0, 'EDIT', <?php echo $pkID; ?> , '', 'indCustTableRow1');" data-toggle="tooltip" title="Reload Transaction">
                                            <img src="cmn_images/refresh.bmp" style="padding-right: 2px; height:17px; width:auto; position: relative; vertical-align: middle;">
                                    </button>
                                    <?php } ?>
                                    <button type="button" class="btn btn-default" style="height:30px;" onclick="getOneMcfDocsForm_Gnrl(<?php echo $pkID; ?>,'<?php echo $trnsType; ?>', 140, '<?php echo $formTitle; ?>');" data-toggle="tooltip" data-placement="bottom" title = "Attached Documents">
                                        <img src="cmn_images/adjunto.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                    </button>                                                    
                                </div>
                                <div class="" style="float:right;">
                                    <?php if (($trnsStatus == "Authorized" || $trnsStatus == "Approved") && (test_prmssns($dfltPrvldgs[65], $mdlNm) === true) && $vwtypActn === "EDIT") { ?>
                                    <button type="button" class="btn btn-default btn-sm" style="" onclick="modifyAutrzCustDataRqstNew(<?php echo $pgNo; ?>, <?php echo $subPgNo; ?>, <?php echo $vwtyp; ?>);">
                                        <img src="cmn_images/edit32.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">
                                        MODIFY DATA
                                    </button>
                                     <?php } else if ($trnsStatus == "Incomplete" || $trnsStatus == "Rejected" || $trnsStatus == "Withdrawn") { ?>  
                                    <button type="button" class="btn btn-default btn-sm" style="" onclick="saveBasicDataCorp(<?php echo $pgNo; ?>, <?php echo $subPgNo; ?>, <?php echo $vwtyp; ?>, 0);">
                                        <img src="cmn_images/FloppyDisk.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">
                                        SAVE
                                    </button>
                                    <button type="button" class="btn btn-default btn-sm" style="" onclick="saveBasicDataCorp(<?php echo $pgNo; ?>, <?php echo $subPgNo; ?>, <?php echo $vwtyp; ?>, 1);">
                                        <img src="cmn_images/Emailcon.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">
                                        SUBMIT
                                    </button>                                    
                                     <?php } else if ($trnsStatus == "Unauthorized") { ?>    
                                        <?php if (didAuthorizerSubmit($pkID, $usrID) && $vwtypActn != "VIEW") { ?>
                                        <button type="button" class="btn btn-default btn-sm" style="height:30px;" onclick="wthdrwRjctAutrzCustDataRqstNew('WITHDRAW', <?php echo $pgNo; ?>, <?php echo $subPgNo; ?>, <?php echo $vwtyp; ?>);"><img src="cmn_images/withdraw_rqst.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Withdraw&nbsp;</button>                                                                          
                                        <?php } else { if ($vwtypActn === "VIEW" && canPrsnSeeCustomerBranchDocs($prsnID, $pkID, $row[29]) === true && test_prmssns($dfltPrvldgs[67], $mdlNm) === true) {  ?>
                                        <button type="button" class="btn btn-default btn-sm" style="height:30px;" onclick="rjctAutrzCustDataRqstNew('REJECT', <?php echo $pgNo; ?>, <?php echo $subPgNo; ?>, <?php echo $vwtyp; ?>);"><img src="cmn_images/withdraw_rqst.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Reject&nbsp;</button>                                         
                                        <button type="button" class="btn btn-default btn-sm" style="height:30px;" onclick="rjctAutrzCustDataRqstNew('AUTHORIZE', <?php echo $pgNo; ?>, <?php echo $subPgNo; ?>, <?php echo $vwtyp; ?>);"><img src="cmn_images/Stamp-512.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Authorize&nbsp;</button>                                                                                                                                     
                                        <?php } } } ?>   
                                </div>
                            </div>
                        </div>
                        <ul class="nav nav-tabs rho-hideable-tabs" style="margin-top:-10px !important;">
                            <li class="active"><a data-toggle="tab" data-rhodata="&pg=10&vtyp=0" href="#prflHomeBCOPEDT" id="prflBCHomeEDTtab">Basic Data</a></li>
                            <li><a data-toggle="tabajxprfledt" data-rhodata="&pg=10&vtyp=1" href="#prfBCOPAddPrsnDataEDT" onclick="openATab('#prfBCOPAddPrsnDataEDT', 'grp=17&typ=1&pg=11&subPgNo=1.6&vtyp=1&vtypActn=<?php echo $vwtypActn; ?>&custID=<?php echo $pkID; ?>&formType=<?php echo $addDtaFormType; ?>&rvsnTtlAPD=<?php echo $row[29]; ?>');" id="prfBCOPAddPrsnDataEDTtab">Additional Data</a></li>
                            <!--<li><a data-toggle="tabajxprfledt" data-rhodata="&pg=10&vtyp=2" href="#prflBCOPAttchmntsEDT" onclick="openATab('#prflBCOPAttchmntsEDT', 'grp=17&typ=1&pg=10&vtyp=2');" id="prflBCOPAttchmntsEDTtab">Attachments</a></li>-->
                        </ul>
                        <div class="row">                  
                            <div class="col-md-12">
                                <div class="custDiv"> 
                                    <div class="tab-content">
                                        <div id="prflHomeBCOPEDT" class="tab-pane fadein active" style="border:none !important;">                          
                                            <form class="form-horizontal">
                                                <div class="row"><!-- ROW 1 -->                                
                                                    <div class="col-lg-6">
                                                        <fieldset class="basic_person_fs5"><legend class="basic_person_lg1">Customer Information</legend>
                                                            <div class="form-group form-group-sm">
                                                                <?php if($cnt > 0 && $row[1] != $rowATZ1) { ?>
                                                                <label data-toggle="tooltip" title="<?php echo $rowATZ1; ?>" for="idNo" class="control-label col-md-4"><a href="#" style="color:red;" onclick="dsplyAuthrzdData('<?php echo $rowATZ1; ?>');">ID No:</a></label>
                                                                <?php } else { ?>
                                                                    <label for="idNo" class="control-label col-md-4">ID No:</label>
                                                                <?php } ?> 
                                                                <input class="form-control" id="custID" type = "hidden" placeholder="Customer ID" value="<?php echo $row[0]; ?>"/>
                                                                <input class="form-control" id="rvsnTtl" type = "hidden" placeholder="Revision Total" value="<?php echo $row[29]; ?>"/>
                                                                <input class="form-control" id="profileID" type = "hidden" placeholder="Profile ID" value="<?php echo $row[27]; ?>"/>
                                                                <div class="col-md-8">
                                                                    <input <?php echo $mkReadOnly; ?> class="form-control" id="idNo" type = "text" placeholder="ID No." value="<?php echo $row[1]; ?>"/>
                                                                </div>
                                                            </div> 
                                                            <div class="form-group form-group-sm">
                                                                <?php if($cnt > 0 && $row[4] != $rowATZ4) { ?>
                                                                <label data-toggle="tooltip" title="<?php echo $rowATZ4; ?>" for="clsfctn" class="control-label col-md-4"><a href="#" style="color:red;" onclick="dsplyAuthrzdData('<?php echo $rowATZ4; ?>');">Classification:</a></label>
                                                                <?php } else { ?>
                                                                    <label for="clsfctn" class="control-label col-md-4">Classification:</label>
                                                                <?php } ?>                                                                
                                                                <div  class="col-md-8">
                                                                    <select <?php echo $mkReadOnlyDsbld; ?> class="form-control" id="clsfctn" >
                                                                        <?php
                                                                        $brghtStr = "";
                                                                        $isDynmyc = FALSE;
                                                                        $titleRslt = getLovValues("%", "Both", 0, 100, $brghtStr,
                                                                                getLovID($cstCls), $isDynmyc, -1, "", "");
                                                                        while ($titleRow = loc_db_fetch_array($titleRslt)) {
                                                                            $selectedTxt = "";
                                                                            if ($titleRow[0] == $row[4]) {
                                                                                $selectedTxt = "selected";
                                                                            }
                                                                            ?>
                                                                            <option value="<?php echo $titleRow[0]; ?>" <?php echo $selectedTxt; ?>><?php echo $titleRow[0]; ?></option>
                                                                            <?php
                                                                        }
                                                                        ?>
                                                                    </select>
                                                                </div>
                                                            </div>  
                                                            <div class="form-group form-group-sm">
                                                                <?php if($cnt > 0 && $row[2] != $rowATZ2) { ?>
                                                                <label data-toggle="tooltip" title="<?php echo $rowATZ2; ?>" for="custName" class="control-label col-md-4"><a href="#" style="color:red;" onclick="dsplyAuthrzdData('<?php echo $rowATZ2; ?>');"><?php echo $custNameLbl; ?>:</a></label>
                                                                <?php } else { ?>
                                                                <label for="custName" class="control-label col-md-4"><?php echo $custNameLbl; ?>:</label>
                                                                <?php } ?>
                                                                <div  class="col-md-8">
                                                                    <input <?php echo $mkReadOnly; ?> class="form-control rqrdFld" id="custName" type = "text" placeholder="<?php echo $custNameLbl; ?>" value="<?php echo $row[2]; ?>"/>
                                                                </div>
                                                            </div>    
                                                            <div <?php echo $makeHidden; ?> class="form-group form-group-sm">
                                                                <?php if($cnt > 0 && $row[3] != $rowATZ3) { ?>
                                                                <label data-toggle="tooltip" title="<?php echo $rowATZ3; ?>" for="natureOfBus" class="control-label col-md-4"><a href="#" style="color:red;" onclick="dsplyAuthrzdData('<?php echo $rowATZ3; ?>');">Nature of Business:</a></label>
                                                                <?php } else { ?>
                                                                <label for="natureOfBus" class="control-label col-md-4">Nature of Business:</label>
                                                                <?php } ?>
                                                                <div  class="col-md-8">
                                                                    <textarea <?php echo $mkReadOnly; ?> class="form-control rqrdFld" id="natureOfBus" cols="2" placeholder="Nature of Business" rows="3"><?php echo $row[3]; ?></textarea>
                                                                </div>
                                                            </div>
                                                            <?php if($subPgNo == 1.3){ ?>
                                                            <div class="form-group form-group-sm">
                                                                <?php if($cnt > 0 && $row[5] != $rowATZ5) { ?>
                                                                <label data-toggle="tooltip" title="<?php echo $rowATZ5; ?>" for="dateOfEstblshmnt" class="control-label col-md-4"><a href="#" style="color:red;" onclick="dsplyAuthrzdData('<?php echo $rowATZ5; ?>');">Date of Establishment:</a></label>
                                                                <?php } else { ?>
                                                                <label for="dateOfEstblshmnt" class="control-label col-md-4">Date of Establishment:</label>
                                                                <?php } ?>
                                                                <div class="col-md-8">
                                                                    <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                                                        <input class="form-control" size="16" type="text" id="dateOfEstblshmnt" value="<?php echo $row[5]; ?>" readonly="">
                                                                        <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                                                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                                    </div>
                                                                </div>
                                                            </div> 
                                                            <?php } ?>
                                                        </fieldset>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <fieldset class="basic_person_fs5"><legend class="basic_person_lg1">Relationship Type</legend>                                    
                                                            <div style="display:none !important;" class="form-group form-group-sm">
                                                                <label for="relation" class="control-label col-md-4">Relation:</label>
                                                                <div  class="col-md-8">
                                                                    <select class="form-control" id="relation" >
                                                                        <option value="Customer" selected="selected">Customer</option>
                                                                    </select>
                                                                </div>
                                                            </div>  
                                                            <?php if($subPgNo == 1.2){ ?>
                                                            <div class="form-group form-group-sm">
                                                                <?php if($cnt > 0 && $row[5] != $rowATZ5) { ?>
                                                                <label data-toggle="tooltip" title="<?php echo $rowATZ5; ?>" for="dateOfEstblshmnt" class="control-label col-md-4"><a href="#" style="color:red;" onclick="dsplyAuthrzdData('<?php echo $rowATZ5; ?>');">Date of Establishment:</a></label>
                                                                <?php } else { ?>
                                                                <label for="dateOfEstblshmnt" class="control-label col-md-4">Date of Establishment:</label>
                                                                <?php } ?>
                                                                <div class="col-md-8">
                                                                    <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                                                        <input class="form-control" size="16" type="text" id="dateOfEstblshmnt" value="<?php echo $row[5]; ?>" readonly="">
                                                                        <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                                                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <?php } ?>
                                                            <div class="form-group form-group-sm">
                                                                <label for="status" class="control-label col-md-4">Status:</label>
                                                                <div  class="col-md-8">
                                                                    <input type="text" style="color:<?php echo $rqstatusColor; ?>;font-weight: bold;" class="form-control" aria-label="..." id="status" value="<?php echo $row[23]; ?>" readonly="">
                                                                </div>
                                                            </div>
                                                            <div class="form-group form-group-sm">
                                                                <?php if($cnt > 0 && $row[24] != $rowATZ24) { ?>
                                                                <label data-toggle="tooltip" title="<?php echo $rowATZ24; ?>" for="bnkBranch" class="control-label col-md-4"><a href="#" style="color:red;" onclick="dsplyAuthrzdData('<?php echo $rowATZ24; ?>');">Branch:</a></label>
                                                                <?php } else { ?>
                                                                <label for="bnkBranch" class="control-label col-md-4">Branch:</label>
                                                                <?php } ?>
                                                                <div  class="col-md-8">
                                                                    <div class="input-group" style="width:100% !important;">
                                                                        <input type="text" class="form-control" aria-label="..." id="bnkBranch" value="<?php
                                                                        echo getGnrlRecNm("org.org_sites_locations", "location_id",
                                                                                        "site_desc||' ('||location_code_name||')' ", $row[24]);
                                                                        ?>" readonly="">
                                                                        <input type="hidden" id="bnkBranchID" value="<?php echo $row[24]; ?>">
                                                                        <input type="hidden" id="gnrlOrgID" value="<?php echo $orgID; ?>">                                                                        
                                                                        <label style="display:none !important;" class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Sites/Locations New', 'gnrlOrgID', '', '', 'radio', true, '<?php echo $row[24]; ?>', 'bnkBranchID', 'bnkBranch', 'clear', 1, '');">
                                                                                <span class="glyphicon glyphicon-th-list"></span>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div>  
                                                            <div class="form-group form-group-sm">
                                                                <?php if($cnt > 0 && $row[25] != $rowATZ25) { ?>
                                                                <label data-toggle="tooltip" title="<?php echo $rowATZ25; ?>" for="startDate" class="control-label col-md-4"><a href="#" style="color:red;" onclick="dsplyAuthrzdData('<?php echo $rowATZ25; ?>');">Start Date:</a></label>
                                                                <?php } else { ?>
                                                                <label for="startDate" class="control-label col-md-4">Start Date:</label>
                                                                <?php } ?>
                                                                <div  class="col-md-8">
                                                                    <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input3" data-link-format="yyyy-mm-dd">
                                                                        <input class="form-control" size="16" type="text" id="startDate" value="<?php echo $row[25]; ?>" readonly="">
                                                                        <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                                                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                                        <span class="input-group-addon" onclick="javascript:unfreezeDialog();"><span class="glyphicon glyphicon-info-sign"></span></span>
                                                                    </div>
                                                                </div>
                                                            </div>      
                                                            <div class="form-group form-group-sm">
                                                                <?php if($cnt > 0 && $row[26] != $rowATZ26) { ?>
                                                                <label data-toggle="tooltip" title="<?php echo $rowATZ26; ?>" for="endDate" class="control-label col-md-4"><a href="#" style="color:red;" onclick="dsplyAuthrzdData('<?php echo $rowATZ26; ?>');">End Date:</a></label>
                                                                <?php } else { ?>
                                                                <label for="endDate" class="control-label col-md-4">End Date:</label>
                                                                <?php } ?>
                                                                <div  class="col-md-8">
                                                                    <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input4" data-link-format="yyyy-mm-dd">
                                                                        <input class="form-control" size="16" type="text" id="endDate" value="<?php echo $row[26]; ?>" readonly="">
                                                                        <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                                                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                                        <span class="input-group-addon" onclick="javascript:unfreezeDialog();"><span class="glyphicon glyphicon-info-sign"></span></span>
                                                                    </div>
                                                                </div>
                                                            </div>  
                                                        </fieldset>                                                
                                                    </div>                                                   
                                                </div>    
                                                <div class="row"><!-- ROW 2 -->                                
                                                    <div class="col-lg-6">
                                                        <fieldset class="basic_person_fs5"><legend class="basic_person_lg1">Contact Information</legend>
                                                            <div class="form-group form-group-sm">
                                                                <?php if($cnt > 0 && $row[17] != $rowATZ17) { ?>
                                                                <label data-toggle="tooltip" title="<?php echo $rowATZ26; ?>" for="pstlAddress" class="control-label col-md-4"><a href="#" style="color:red;" onclick="dsplyAuthrzdData('<?php echo $rowATZ17; ?>');">Postal Address:</a></label>
                                                                <?php } else { ?>
                                                                <label for="pstlAddress" class="control-label col-md-4">Postal Address:</label>
                                                                <?php } ?>
                                                                <div  class="col-md-8">
                                                                    <textarea <?php echo $mkReadOnly; ?> class="form-control" id="pstlAddress" cols="2" placeholder="Postal Address" rows="5"><?php echo $row[17]; ?></textarea>
                                                                </div>
                                                            </div>
                                                            <?php if($subPgNo == 1.2){ ?>
                                                            <div class="form-group form-group-sm">
                                                                <?php if($cnt > 0 && $row[18] != $rowATZ18) { ?>
                                                                <label data-toggle="tooltip" title="<?php echo $rowATZ18; ?>" for="resAddress" class="control-label col-md-4"><a href="#" style="color:red;" onclick="dsplyAuthrzdData('<?php echo $rowATZ18; ?>');">Trading Address:</a></label>
                                                                <?php } else { ?>
                                                                <label for="resAddress" class="control-label col-md-4">Trading Address:</label>
                                                                <?php } ?>
                                                                <div  class="col-md-8">
                                                                    <textarea <?php echo $mkReadOnly; ?> class="form-control rqrdFld" id="resAddress" cols="2" placeholder="Trading Address" rows="5"><?php echo $row[18]; ?></textarea>
                                                                </div>
                                                            </div> 
                                                            <?php } ?>
                                                            <div class="form-group form-group-sm">
                                                                <?php if($cnt > 0 && $row[16] != $rowATZ16) { ?>
                                                                <label data-toggle="tooltip" title="<?php echo $rowATZ16; ?>" for="email" class="control-label col-md-4"><a href="#" style="color:red;" onclick="dsplyAuthrzdData('<?php echo $rowATZ16; ?>');">Email:</a></label>
                                                                <?php } else { ?>
                                                                <label for="email" class="control-label col-md-4">Email:</label>
                                                                <?php } ?>
                                                                <div  class="col-md-8">
                                                                    <input <?php echo $mkReadOnly; ?> class="form-control" id="email" type = "email" placeholder="<?php echo $admin_email; ?>" value="<?php echo $row[16]; ?>"/>
                                                                </div>
                                                            </div> 
                                                            <div class="form-group form-group-sm">
                                                                <?php if($cnt > 0 && $row[14] != $rowATZ14) { ?>
                                                                <label data-toggle="tooltip" title="<?php echo $rowATZ14; ?>" for="contactNos" class="control-label col-md-4"><a href="#" style="color:red;" onclick="dsplyAuthrzdData('<?php echo $rowATZ14; ?>');">Contact Nos:</a></label>
                                                                <?php } else { ?>
                                                                <label for="contactNos" class="control-label col-md-4">Contact Nos:</label>
                                                                <?php } ?>
                                                                <div  class="col-md-8">
                                                                    <input <?php echo $mkReadOnly; ?> class="form-control rqrdFld" id="contactNos" type = "text" placeholder="Contact Nos" value="<?php echo $row[14]; ?>"/>                                     
                                                                </div>
                                                            </div>     
                                                            <div <?php echo $makeHidden; ?> class="form-group form-group-sm">
                                                                <?php if($cnt > 0 && $row[15] != $rowATZ15) { ?>
                                                                <label data-toggle="tooltip" title="<?php echo $rowATZ15; ?>" for="faxNo" class="control-label col-md-4"><a href="#" style="color:red;" onclick="dsplyAuthrzdData('<?php echo $rowATZ15; ?>');">Fax:</a></label>
                                                                <?php } else { ?>
                                                                <label for="faxNo" class="control-label col-md-4">Fax:</label>
                                                                <?php } ?>
                                                                <div  class="col-md-8">
                                                                    <input <?php echo $mkReadOnly; ?> class="form-control" id="faxNo" type = "text" placeholder="FaxNo" value="<?php echo $row[15]; ?>"/>
                                                                </div>
                                                            </div> 
                                                        </fieldset>                                                
                                                    </div>
                                                    <div class="col-lg-6"> 
                                                        <fieldset class="basic_person_fs5"><legend class="basic_person_lg1">Registration Details</legend>
                                                            <div <?php echo $makeShown; ?> class="form-group form-group-sm" id="meetingDaysDiv">
                                                                <?php if($cnt > 0 && $row[28] != $rowATZ28) { ?>
                                                                <label data-toggle="tooltip" title="<?php echo $rowATZ28; ?>" for="meetingDays" class="control-label col-md-4"><a href="#" style="color:red;" onclick="dsplyAuthrzdData('<?php echo $rowATZ28; ?>');">Meeting Days:</a></label>
                                                                <?php } else { ?>
                                                                <label for="meetingDays" id="meetingDaysLbl" class="control-label col-md-4">Meeting Days:</label>
                                                                <?php } ?>
                                                                <div  class="col-md-8">
                                                                    <select class="form-control" name="meetingDays[]" id="meetingDays" multiple>
                                                                        <!--<option value="">&nbsp;</option>-->
                                                                        <?php
                                                                        $brghtStr = "";
                                                                        $isDynmyc = FALSE;
                                                                        $titleRslt = getLovValues("%", "Both", 0, 100, $brghtStr,
                                                                                getLovID("MCF Meeting Days"), $isDynmyc, -1, "", "");
                                                                        while ($titleRow = loc_db_fetch_array($titleRslt)) {
                                                                            $selectedTxt = "";
                                                                            $trnsTypArr = explode(',', $row[28]);
                                                                            foreach ($trnsTypArr as $val) {
                                                                                if ($val == $titleRow[0]) {
                                                                                    $selectedTxt = "selected=\"selected\"";
                                                                                }
                                                                            }
                                                                            ?>
                                                                            <option value="<?php echo $titleRow[0]; ?>" <?php echo $selectedTxt; ?>><?php echo $titleRow[0]; ?></option>
                                                                            <?php
                                                                        }
                                                                        ?>
                                                                    </select>
                                                                </div>
                                                            </div> 
                                                            <?php if($subPgNo == 1.3){ ?>
                                                            <div class="form-group form-group-sm">
                                                                <?php if($cnt > 0 && $row[18] != $rowATZ18) { ?>
                                                                <label data-toggle="tooltip" title="<?php echo $rowATZ18; ?>" for="resAddress" class="control-label col-md-4"><a href="#" style="color:red;" onclick="dsplyAuthrzdData('<?php echo $rowATZ18; ?>');"><?php echo $rsdntlAddressLbl; ?>:</a></label>
                                                                <?php } else { ?>
                                                                <label for="resAddress" class="control-label col-md-4"><?php echo $rsdntlAddressLbl; ?>:</label>
                                                                <?php } ?>
                                                                <div  class="col-md-8">
                                                                    <textarea <?php echo $mkReadOnly; ?> class="form-control rqrdFld" id="resAddress" cols="2" placeholder="<?php echo $rsdntlAddressLbl; ?>" rows="5"><?php echo $row[18]; ?></textarea>
                                                                </div>
                                                            </div>
                                                            <?php } ?>
                                                            <div <?php echo $makeHidden; ?> class="form-group form-group-sm">
                                                                <?php if($cnt > 0 && $row[6] != $rowATZ6) { ?>
                                                                <label data-toggle="tooltip" title="<?php echo $rowATZ6; ?>" for="brandName" class="control-label col-md-4"><a href="#" style="color:red;" onclick="dsplyAuthrzdData('<?php echo $rowATZ6; ?>');">Brand Name:</a></label>
                                                                <?php } else { ?>
                                                                <label for="brandName" class="control-label col-md-4">Brand Name:</label>
                                                                <?php } ?>
                                                                <div  class="col-md-8">
                                                                    <input <?php echo $mkReadOnly; ?> class="form-control rqrdFld" id="brandName" type = "text" placeholder="Brand Name" value="<?php echo $row[6]; ?>"/>
                                                                </div>                                                                
                                                            </div>                                                            
                                                            <div <?php echo $makeHidden; ?> class="form-group form-group-sm">
                                                                <?php if($cnt > 0 && $row[7] != $rowATZ7) { ?>
                                                                <label data-toggle="tooltip" title="<?php echo $rowATZ7; ?>" for="orgType" class="control-label col-md-4"><a href="#" style="color:red;" onclick="dsplyAuthrzdData('<?php echo $rowATZ7; ?>');">Organization Type:</a></label>
                                                                <?php } else { ?>
                                                                <label for="orgType" class="control-label col-md-4">Organization Type:</label>
                                                                <?php } ?>
                                                                <div  class="col-md-8">
                                                                    <select <?php echo $mkReadOnlyDsbld; ?> class="form-control" id="orgType" >
                                                                        <?php
                                                                        $brghtStr = "";
                                                                        $isDynmyc = FALSE;
                                                                        $titleRslt = getLovValues("%", "Both", 0, 100, $brghtStr,
                                                                                getLovID("Organisation Types"), $isDynmyc, -1, "", "");
                                                                        while ($titleRow = loc_db_fetch_array($titleRslt)) {
                                                                            $selectedTxt = "";
                                                                            if ($titleRow[0] == $row[7]) {
                                                                                $selectedTxt = "selected";
                                                                            }
                                                                            ?>
                                                                            <option value="<?php echo $titleRow[0]; ?>" <?php echo $selectedTxt; ?>><?php echo $titleRow[0]; ?></option>
                                                                            <?php
                                                                        }
                                                                        ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div <?php echo $makeHidden; ?> class="form-group form-group-sm">
                                                                <?php if($cnt > 0 && $row[8] != $rowATZ8) { ?>
                                                                <label data-toggle="tooltip" title="<?php echo $rowATZ8; ?>" for="regNo" class="control-label col-md-4"><a href="#" style="color:red;" onclick="dsplyAuthrzdData('<?php echo $rowATZ8; ?>');">Business Reg. No:</a></label>
                                                                <?php } else { ?>
                                                                <label for="regNo" class="control-label col-md-4">Business Reg. No:</label>
                                                                <?php } ?>
                                                                <div  class="col-md-8">
                                                                    <input <?php echo $mkReadOnly; ?> class="form-control rqrdFld" id="regNo" type = "text" placeholder="Business Registration No" value="<?php echo $row[8]; ?>"/>
                                                                </div>                                                                   
                                                            </div>                                                            
                                                            <div <?php echo $makeHidden; ?> class="form-group form-group-sm">
                                                                <?php if($cnt > 0 && $row[19] != $rowATZ19) { ?>
                                                                <label data-toggle="tooltip" title="<?php echo $rowATZ19; ?>" for="dateOfIncorp" class="control-label col-md-4"><a href="#" style="color:red;" onclick="dsplyAuthrzdData('<?php echo $rowATZ19; ?>');">Date of Incorporation:</a></label>
                                                                <?php } else { ?>
                                                                <label for="dateOfIncorp" class="control-label col-md-4">Date of Incorporation:</label>
                                                                <?php } ?>
                                                                <div class="col-md-8">
                                                                    <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                                                        <input class="form-control rqrdFld" size="16" type="text" id="dateOfIncorp" value="<?php echo $row[19]; ?>" readonly="">
                                                                        <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                                                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                                    </div>
                                                                </div>
                                                            </div> 
                                                            <div <?php echo $makeHidden; ?> class="form-group form-group-sm">
                                                                <?php if($cnt > 0 && $row[9] != $rowATZ9) { ?>
                                                                <label data-toggle="tooltip" title="<?php echo $rowATZ9; ?>" for="typeOfIncorp" class="control-label col-md-4"><a href="#" style="color:red;" onclick="dsplyAuthrzdData('<?php echo $rowATZ9; ?>');">Type of Incorporation:</a></label>
                                                                <?php } else { ?>
                                                                <label for="typeOfIncorp" class="control-label col-md-4">Type of Incorporation:</label>
                                                                <?php } ?>
                                                                <div  class="col-md-8">
                                                                    <select <?php echo $mkReadOnlyDsbld; ?> class="form-control" id="typeOfIncorp" >
                                                                        <?php
                                                                        $brghtStr = "";
                                                                        $isDynmyc = FALSE;
                                                                        $titleRslt = getLovValues("%", "Both", 0, 100, $brghtStr,
                                                                                getLovID("Types of Incorporation"), $isDynmyc, -1, "", "");
                                                                        while ($titleRow = loc_db_fetch_array($titleRslt)) {
                                                                            $selectedTxt = "";
                                                                            if ($titleRow[0] == $row[9]) {
                                                                                $selectedTxt = "selected";
                                                                            }
                                                                            ?>
                                                                            <option value="<?php echo $titleRow[0]; ?>" <?php echo $selectedTxt; ?>><?php echo $titleRow[0]; ?></option>
                                                                            <?php
                                                                        }
                                                                        ?>
                                                                    </select>
                                                                </div>
                                                            </div>                                                            
                                                            <div <?php echo $makeHidden; ?> class="form-group form-group-sm">
                                                                <?php if($cnt > 0 && $row[10] != $rowATZ10) { ?>
                                                                <label data-toggle="tooltip" title="<?php echo $rowATZ10; ?>" for="vatNo" class="control-label col-md-4"><a href="#" style="color:red;" onclick="dsplyAuthrzdData('<?php echo $rowATZ10; ?>');">VAT Number:</a></label>
                                                                <?php } else { ?>
                                                                <label for="vatNo" class="control-label col-md-4">VAT Number:</label>
                                                                <?php } ?>
                                                                <div  class="col-md-8">
                                                                    <input <?php echo $mkReadOnly; ?> class="form-control" id="vatNo" type = "text" placeholder="VAT Number" value="<?php echo $row[10]; ?>"/>
                                                                </div>                                                                  
                                                            </div>  
                                                            <div <?php echo $makeHidden; ?> class="form-group form-group-sm">
                                                                <?php if($cnt > 0 && $row[11] != $rowATZ11) { ?>
                                                                <label data-toggle="tooltip" title="<?php echo $rowATZ11; ?>" for="tinNo" class="control-label col-md-4"><a href="#" style="color:red;" onclick="dsplyAuthrzdData('<?php echo $rowATZ11; ?>');">TIN Number:</a></label>
                                                                <?php } else { ?>
                                                                <label for="tinNo" class="control-label col-md-4">TIN Number:</label>
                                                                <?php } ?>
                                                                <div  class="col-md-8">
                                                                    <input <?php echo $mkReadOnly; ?> class="form-control rqrdFld" id="tinNo" type = "text" placeholder="TIN Number" value="<?php echo $row[11]; ?>"/>
                                                                </div>                                                                
                                                            </div>  
                                                            <div <?php echo $makeHidden; ?> class="form-group form-group-sm">
                                                                <?php if($cnt > 0 && $row[12] != $rowATZ12) { ?>
                                                                <label data-toggle="tooltip" title="<?php echo $rowATZ12; ?>" for="ssnitRegNo" class="control-label col-md-4"><a href="#" style="color:red;" onclick="dsplyAuthrzdData('<?php echo $rowATZ12; ?>');">SSNIT Reg. Number:</a></label>
                                                                <?php } else { ?>
                                                                <label for="ssnitRegNo" class="control-label col-md-4">SSNIT Reg. Number:</label>
                                                                <?php } ?>
                                                                <div  class="col-md-8">
                                                                    <input <?php echo $mkReadOnly; ?> class="form-control" id="ssnitRegNo" type = "text" placeholder="SNIT Reg. Number" value="<?php echo $row[12]; ?>"/>
                                                                </div> 
                                                            </div>  
                                                            <div class="form-group form-group-sm">
                                                                <?php if($cnt > 0 && $row[13] != $rowATZ13) { ?>
                                                                <label data-toggle="tooltip" title="<?php echo $rowATZ13; ?>" for="noOfEmp" class="control-label col-md-4"><a href="#" style="color:red;" onclick="dsplyAuthrzdData('<?php echo $rowATZ13; ?>');"><?php echo $emplyeeSize; ?>:</a></label>
                                                                <?php } else { ?>
                                                                <label for="noOfEmp" class="control-label col-md-4"><?php echo $emplyeeSize; ?>:</label>
                                                                <?php } ?>
                                                                <div  class="col-md-8">
                                                                    <input <?php echo $mkReadOnly; ?> class="form-control rqrdFld" id="noOfEmp" type = "number" min="0" placeholder="<?php echo $emplyeeSize; ?>" value="<?php echo $row[13]; ?>"/>
                                                                </div> 
                                                            </div>                                                             
                                                        </fieldset>   
                                                    </div>
                                                </div> 
                                                <div class="row" style="margin: 5px 0px 0px 0px !important;"><!-- ROW 3 -->
                                                    <div class="col-lg-12"> 
                                                        <!--<fieldset class="basic_person_fs3" style="padding: 1px !important;">-->
                                                            <legend class="basic_person_lg1"><?php echo $drctOrGrpMemberLbl; ?></legend> 
                                                            <?php if($subPgNo == 1.2){ //CORPORATE CUSTOMER?>
                                                                <div  class="col-md-12">
                                                                    <?php if(($trnsStatus == "Incomplete" ||  $trnsStatus == "Rejected" || $trnsStatus == "Withdrawn") && $vwtypActn != "VIEW") { ?>
                                                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="getcorpDirectorForm('myFormsModalx', 'myFormsModalxBody', 'myFormsModalxTitle', 'corpDirectorForm', '', 'Add Person', 11, <?php echo $subPgNo; ?>, 5, 'ADD', - 1);">
                                                                        <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                        Add Person
                                                                    </button>    
                                                                    <?php } ?>
                                                                    <table id="grpMemberTblAdd" class="table table-striped table-bordered table-responsive" cellspacing="0" width="100%" style="width:100%;">
                                                                        <thead>
                                                                            <tr>
                                                                                <?php if(($trnsStatus == "Incomplete" ||  $trnsStatus == "Rejected" || $trnsStatus == "Withdrawn") && $vwtypActn != "VIEW"){ ?>
                                                                                <th>...</th>
                                                                                <th>...</th>
                                                                                <?php } ?>
                                                                                <th>ID No.</th>
                                                                                <th>Fullname</th>
                                                                                <th>Person Type</th>
                                                                                <th>Start Date Active</th>
                                                                                <th>End Date Active</th>
                                                                                <th <?php echo $shwHydNtlntySts; ?>>Status</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            <?php
                                                                            $result1 = get_CorpDirectors($pkID);
                                                                            $cntr = 0;
                                                                            while ($row1 = loc_db_fetch_array($result1)) {
                                                                                $corpDirectorID = $row1[0];
                                                                                $cntr++;
                                                                                $row1ATZ0 = ""; $row1ATZ1 = ""; $row1ATZ2 = ""; $row1ATZ3 = ""; $row1ATZ4 = ""; $row1ATZ5 = "";
                                                                                $row1ATZ6 = ""; $row1ATZ7 = ""; $row1ATZ8 = "";
                                                                                if($row1[0] > 0 && $row1[8] === "Yes"){
                                                                                    $result1ATZ = get_CorpDirectorsATZ($row1[0]);
                                                                                    while ($row1ATZ = loc_db_fetch_array($result1ATZ)) {
                                                                                        $row1ATZ0 = $row1ATZ[0]; $row1ATZ1 = $row1ATZ[1]; $row1ATZ2 = $row1ATZ[2]; $row1ATZ3 = $row1ATZ[3];
                                                                                        $row1ATZ4 = $row1ATZ[4]; $row1ATZ5 = $row1ATZ[5]; $row1ATZ6 = $row1ATZ[6]; $row1ATZ7 = $row1ATZ[7];
                                                                                    }
                                                                                }
                                                                                
                                                                                ?>
                                                                                <tr id="grpMemberTblAddRow<?php echo $cntr; ?>">
                                                                                    <?php if(($trnsStatus == "Incomplete" ||  $trnsStatus == "Rejected" || $trnsStatus == "Withdrawn") && $vwtypActn != "VIEW"){ ?>
                                                                                    <td>
                                                                                        <button type="button" class="btn btn-default btn-sm" onclick="getcorpDirectorForm('myFormsModalx', 'myFormsModalxBody', 'myFormsModalxTitle', 'corpDirectorForm', '', 'Edit Director', 11, <?php echo $subPgNo; ?>, 5, 'EDIT', <?php echo $corpDirectorID; ?>);" style="padding:2px !important;">
                                                                                            <!--<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>-->
                                                                                            <img src="cmn_images/edit32.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                                        </button>
                                                                                    </td>
                                                                                    <td>
                                                                                        <button type="button" class="btn btn-default btn-sm" onclick="deleteCorpDirector(<?php echo $corpDirectorID; ?>,'<?php echo $trnsStatus; ?>','<?php echo $row1[8]; ?>');" style="padding:2px !important;">
                                                                                            <!--<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>-->
                                                                                            <img src="cmn_images/delete.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                                        </button>
                                                                                    </td>
                                                                                    <?php } ?>
                                                                                    <td><?php echo dsplyTblData($row1[7], $row1ATZ7, $row1[0], $row1[8]); ?></td>
                                                                                    <td><?php echo dsplyTblData($row1[1], $row1ATZ1, $row1[0], $row1[8]); ?></td>
                                                                                    <td><?php echo dsplyTblData($row1[2], $row1ATZ2, $row1[0], $row1[8]); ?></td>
                                                                                    <td><?php echo dsplyTblData($row1[3], $row1ATZ3, $row1[0], $row1[8]); ?></td>
                                                                                    <td><?php echo dsplyTblData($row1[4], $row1ATZ4, $row1[0], $row1[8]); ?></td>
                                                                                    <td <?php echo $shwHydNtlntySts; ?>>
                                                                                        <?php 
                                                                                        if($row1[0] < 0){
                                                                                            echo "<span style='color:red;'><b>Deleted</b></span>";
                                                                                        } else  {
                                                                                           if($row1[8] === "No"){
                                                                                                echo "<span style='color:blue;'><b>New</b></span>";
                                                                                           } else {
                                                                                               echo "&nbsp;";
                                                                                           }
                                                                                        }
                                                                                        ?>
                                                                                    </td>
                                                                                    
                                                                                </tr>
                                                                            <?php } ?>
                                                                        </tbody>
                                                                    </table>
                                                                </div> 
                                                            <?php } else { //CUSTOMER GROUP ?>
                                                                <div  class="col-md-12">
                                                                    <?php if(($trnsStatus == "Incomplete" ||  $trnsStatus == "Rejected" || $trnsStatus == "Withdrawn") && $vwtypActn != "VIEW") { ?>
                                                                    <div style="float:left;">
                                                                        <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="getcorpDirectorForm('myFormsModalx', 'myFormsModalxBody', 'myFormsModalxTitle', 'grpMemberForm', '', 'Add Group Member', 11, <?php echo $subPgNo; ?>, 6, 'ADD', - 1);">
                                                                            <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                            Add Member
                                                                        </button> 
                                                                        <span style="color:red;"><i><b>***Please Note</b>: Members should be created as Individual Customers first, before Adding to Group***</i></span>
                                                                    </div>
                                                                    <?php } ?>
                                                                    <table id="grpMemberTblAdd" class="table table-striped table-bordered table-responsive" cellspacing="0" width="100%" style="width:100%;">
                                                                        <thead>
                                                                            <tr>
                                                                                <?php if(($trnsStatus == "Incomplete" ||  $trnsStatus == "Rejected" || $trnsStatus == "Withdrawn") && $vwtypActn != "VIEW"){ ?>
                                                                                <th>...</th>
                                                                                <th>...</th>
                                                                                <?php } ?>
                                                                                <th>ID No.</th>
                                                                                <th>Fullname</th>
                                                                                <th>Position</th>
                                                                                <th>Start Date Active</th>
                                                                                <th>End Date Active</th>
                                                                                <th <?php echo $shwHydNtlntySts; ?>>Status</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            <?php
                                                                            $result1 = get_GroupMembers($pkID);
                                                                            $cntr = 0;
                                                                            while ($row1 = loc_db_fetch_array($result1)) {
                                                                                $corpDirectorID = $row1[0];
                                                                                $cntr++;
                                                                                $row1ATZ0 = ""; $row1ATZ1 = ""; $row1ATZ2 = ""; $row1ATZ3 = ""; $row1ATZ4 = ""; $row1ATZ5 = "";
                                                                                $row1ATZ6 = ""; $row1ATZ7 = ""; $row1ATZ8 = "";
                                                                                if($row1[0] > 0 && $row1[8] === "Yes"){
                                                                                    $result1ATZ = get_GroupMembersATZ($row1[0]);
                                                                                    while ($row1ATZ = loc_db_fetch_array($result1ATZ)) {
                                                                                        $row1ATZ0 = $row1ATZ[0]; $row1ATZ1 = $row1ATZ[1]; $row1ATZ2 = $row1ATZ[2]; $row1ATZ3 = $row1ATZ[3];
                                                                                        $row1ATZ4 = $row1ATZ[4]; $row1ATZ5 = $row1ATZ[5]; $row1ATZ6 = $row1ATZ[6]; $row1ATZ7 = $row1ATZ[7];
                                                                                    }
                                                                                }
                                                                                ?>
                                                                                <tr id="grpMemberTblAddRow<?php echo $cntr; ?>">
                                                                                    <?php if(($trnsStatus == "Incomplete" ||  $trnsStatus == "Rejected" || $trnsStatus == "Withdrawn") && $vwtypActn != "VIEW"){ ?>
                                                                                    <td>
                                                                                        <button type="button" class="btn btn-default btn-sm" onclick="getcorpDirectorForm('myFormsModalx', 'myFormsModalxBody', 'myFormsModalxTitle', 'corpDirectorForm', '', 'Edit Group Member', 11, <?php echo $subPgNo; ?>, 6, 'EDIT', <?php echo $corpDirectorID; ?>);" style="padding:2px !important;">
                                                                                            <!--<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>-->
                                                                                            <img src="cmn_images/edit32.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                                        </button>
                                                                                    </td>
                                                                                    <td>
                                                                                        <button type="button" class="btn btn-default btn-sm" onclick="deleteGrpMember(<?php echo $corpDirectorID; ?>,'<?php echo $trnsStatus; ?>','<?php echo $row1[8]; ?>');" style="padding:2px !important;">
                                                                                            <!--<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>-->
                                                                                            <img src="cmn_images/delete.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                                        </button>
                                                                                    </td>
                                                                                    <?php } ?>
                                                                                    <td><?php echo dsplyTblData($row1[7], $row1ATZ7, $row1[0], $row1[8]); ?></td>
                                                                                    <td><?php echo dsplyTblData($row1[1], $row1ATZ1, $row1[0], $row1[8]); ?></td>
                                                                                    <td><?php echo dsplyTblData($row1[2], $row1ATZ2, $row1[0], $row1[8]); ?></td>
                                                                                    <td><?php echo dsplyTblData($row1[3], $row1ATZ3, $row1[0], $row1[8]); ?></td>
                                                                                    <td><?php echo dsplyTblData($row1[4], $row1ATZ4, $row1[0], $row1[8]); ?></td>
                                                                                    <td <?php echo $shwHydNtlntySts; ?>>
                                                                                        <?php 
                                                                                        if($row1[0] < 0){
                                                                                            echo "<span style='color:red;'><b>Deleted</b></span>";
                                                                                        } else  {
                                                                                           if($row1[8] === "No"){
                                                                                                echo "<span style='color:blue;'><b>New</b></span>";
                                                                                           } else {
                                                                                               echo "&nbsp;";
                                                                                           }
                                                                                        }
                                                                                        ?>
                                                                                    </td>
                                                                                </tr>
                                                                            <?php } ?>
                                                                        </tbody>
                                                                    </table>
                                                                </div>                                                             
                                                            <?php } ?>
                                                        <!--</fieldset>-->
                                                    </div>
                                                </div>  
                                            </form>  
                                        </div>
                                        <div id="prfBCOPAddPrsnDataEDT" class="tab-pane fade" style="border:none !important;"></div>
                                        <div id="prflBCOPAttchmntsEDT" class="tab-pane fade" style="border:none !important;"></div>      
                                    </div>                        
                                </div>                         
                            </div>                
                        </div>          
                    <?php
                }
            }
        } 
        else if ($vwtyp == "1") {
            /* ADDITIONAL DATA */
            if ($vwtypActn == "VIEW") {
                /* Read Only */
            } else if ($vwtypActn == "ADD") {
                /* Add */
            } else if ($vwtypActn == "EDIT") {
                /* Edit Only */
            }
        } 
        else if ($vwtyp == "2") {
            /* ATTACHMENTS */
            if ($vwtypActn == "VIEW") {
                /* Read Only */
            } else if ($vwtypActn == "ADD") {
                /* Add */
            } else if ($vwtypActn == "EDIT") {
                /* Edit Only */
            }
        } 
        else if ($vwtyp == "5") {
            /* ADD DIRECTORS */
            $rowID = isset($_POST['rowID']) ? cleanInputData($_POST['rowID']) : -1;
            $custID = isset($_POST['custID']) ? cleanInputData($_POST['custID']) : -1;
            $rvsnTtl = isset($_POST['rvsnTtl']) ? cleanInputData($_POST['rvsnTtl']) : 0;
            $srcType = "";//isset($_POST['srcType']) ? cleanInputData($_POST['srcType']) : '';
            //$director = "";
            $corpDirectorID = -1;            
            $director = "";
            $directorID = -1;
            $startDateActive = "";
            $endDateActive = "";
            
            
            $result = get_Corp_DirectorDets($rowID, $custID, $rvsnTtl);
            while($row = loc_db_fetch_array($result)){
                $corpDirectorID = $row[0];
                $director = $row[1];
                $directorID =  $row[3];
                $srcType = $row[2];
                $startDateActive = $row[5];
                $endDateActive = $row[6];
            }
            
            
            ?>
            <form class="form-horizontal" id="corpDirectorForm" style="padding:5px 20px 5px 20px;">
                <div class="row">
                    <!--Guarantor ID-->
                    <input class="form-control" size="16" type="hidden" id="corpDirectorID" value="<?php echo $rowID; ?>" readonly="">                    
                    <!--Loan Request ID-->
                    <!--<input type="hidden" id="custID" value="<?php echo $custID; ?>">-->
                    <div class="form-group form-group-sm">
                        <label for="srcType" class="control-label col-md-4">Source Type:</label>
                        <div  class="col-md-8">
                            <select class="form-control" id="srcType" >
                                <?php
                                $sltdIndCst = "";
                                $sltdOthrPrsn = "";
                                if ($srcType == "Individual Customers") {
                                    $sltdIndCst = "selected";
                                } else if ($srcType == "Other Persons") {
                                    $sltdOthrPrsn = "selected";
                                }
                                ?>
                                <option value="Individual Customers" <?php echo $sltdIndCst; ?>>Individual Customers</option>
                                <option value="Other Persons" <?php echo $sltdOthrPrsn; ?>>Other Persons</option>
                            </select>
                        </div>
                    </div>                    
                    <div class="form-group form-group-sm">
                        <label for="director" class="control-label col-md-4">Name:</label>
                        <div  class="col-md-8">
                            <div class="input-group">
                                <!--table rowElementID-->
                                <input class="form-control" size="16" type="hidden" id="tblRowElementID" value="" readonly="">
                                <!--custType Individual-->
                                <input type="hidden" id="custTypeIndividual" value="Individual">
                                <!--prsnType Director-->
                                <input type="hidden" id="relation" value="Director">

                                <input type="text" class="form-control" aria-label="..." id="director" value="<?php echo $director; ?>" readonly="readonly">
                                <input type="hidden" id="directorID" value="<?php echo $directorID; ?>">
                                <input type="hidden" id="gnrlOrgID" value="<?php echo $orgID; ?>">
                                <label class="btn btn-primary btn-file input-group-addon" onclick="getCorpDirectors();">
                                    <span class="glyphicon glyphicon-th-list"></span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group form-group-sm">
                        <label for="startDateActive" class="control-label col-md-4">Start Date:</label>
                        <div  class="col-md-8">
                            <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input3" data-link-format="yyyy-mm-dd">
                                <input class="form-control" size="16" type="text" id="startDateActive" value="<?php echo $startDateActive; ?>" readonly="">
                                <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                <span class="input-group-addon" onclick="javascript:unfreezeDialog();"><span class="glyphicon glyphicon-info-sign"></span></span>
                            </div>
                        </div>
                    </div>      
                    <div class="form-group form-group-sm">
                        <label for="endDateActive" class="control-label col-md-4">End Date:</label>
                        <div  class="col-md-8">
                            <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input4" data-link-format="yyyy-mm-dd">
                                <input class="form-control" size="16" type="text" id="endDateActive" value="<?php echo $endDateActive; ?>" readonly="">
                                <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                <span class="input-group-addon" onclick="javascript:unfreezeDialog();"><span class="glyphicon glyphicon-info-sign"></span></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" style="float:right;padding-right: 1px;">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <?php if($vwtypActn != "VIEW") { ?>
                        <button type="button" class="btn btn-primary" onclick="saveCorpDirectorForm('myFormsModalx', '<?php echo $rowID; ?>');">Save Changes</button>
                    <?php } ?>
                </div>
            </form>
            <?php
        } 
        else if ($vwtyp == "6") {
            /* ADD GROUP MEMBERS */
            $rowID = isset($_POST['rowID']) ? cleanInputData($_POST['rowID']) : -1;
            $custID = isset($_POST['custID']) ? cleanInputData($_POST['custID']) : -1;
            $rvsnTtl = isset($_POST['rvsnTtl']) ? cleanInputData($_POST['rvsnTtl']) : 0;
            $mbrPstn= "";//isset($_POST['mbrPstn']) ? cleanInputData($_POST['mbrPstn']) : '';
            //$member = "";
            $grpMemberID = -1;            
            $member = "";
            $memberID = -1;
            $startDateActive = "";
            $endDateActive = "";
            $hideShwBtn = "";
            $expndInptGrpBtn = "";
            
            if($vwtypActn === "EDIT"){
                $expndInptGrpBtn = "style='width:100% !important;'";
                $hideShwBtn = "style='display:none !important;'";
            }
            
            
            $result = get_Group_MemberDets($rowID, $custID, $rvsnTtl);
            while($row = loc_db_fetch_array($result)){
                $grpMemberID = $row[0];
                $member = $row[1];
                $memberID =  $row[3];
                $mbrPstn = $row[2];
                $startDateActive = $row[5];
                $endDateActive = $row[6];
            }
            
            
            ?>
            <form class="form-horizontal" id="grpMemberForm" style="padding:5px 20px 5px 20px;">
                <div class="row">
                    <!--Guarantor ID-->
                    <input class="form-control" size="16" type="hidden" id="grpMemberID" value="<?php echo $rowID; ?>" readonly="">                    
                    <!--Loan Request ID-->
                    <div class="form-group form-group-sm">
                        <label for="mbrPstn" class="control-label col-md-4">Position:</label>
                        <div  class="col-md-8">
                            <select class="form-control" id="mbrPstn" >
                                <?php
                                $brghtStr = "";
                                $isDynmyc = FALSE;
                                $titleRslt = getLovValues("%", "Both", 0, 100, $brghtStr,
                                        getLovID("MCF Group Member Positions"), $isDynmyc, -1, "", "");
                                while ($titleRow = loc_db_fetch_array($titleRslt)) {
                                    $selectedTxt = "";
                                    if ($titleRow[0] == $mbrPstn) {
                                        $selectedTxt = "selected";
                                    }
                                    ?>
                                    <option value="<?php echo $titleRow[0]; ?>" <?php echo $selectedTxt; ?>><?php echo $titleRow[0]; ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                    </div>                    
                    <div class="form-group form-group-sm">
                        <label for="member" class="control-label col-md-4">Name:</label>
                        <div  class="col-md-8">
                            <div class="input-group" <?php echo $expndInptGrpBtn; ?>>
                                <!--table rowElementID-->
                                <input class="form-control" size="16" type="hidden" id="tblRowElementID" value="" readonly="">
                                <!--custType Individual-->
                                <input type="hidden" id="custTypeIndividual" value="Individual">
                                <!--prsnType Director-->
                                <input type="hidden" id="relation" value="Director">

                                <input type="text" class="form-control" aria-label="..." id="member" value="<?php echo $member; ?>" readonly="readonly">
                                <input type="hidden" id="memberID" value="<?php echo $memberID; ?>">
                                <input type="hidden" id="gnrlOrgID" value="<?php echo $orgID; ?>">
                                <label <?php echo $hideShwBtn; ?> class="btn btn-primary btn-file input-group-addon" onclick="getGrpMembers();">
                                    <span class="glyphicon glyphicon-th-list"></span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group form-group-sm">
                        <label for="startDateActive" class="control-label col-md-4">Start Date:</label>
                        <div  class="col-md-8">
                            <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input3" data-link-format="yyyy-mm-dd">
                                <input class="form-control" size="16" type="text" id="startDateActive" value="<?php echo $startDateActive; ?>" readonly="">
                                <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                <span class="input-group-addon" onclick="javascript:unfreezeDialog();"><span class="glyphicon glyphicon-info-sign"></span></span>
                            </div>
                        </div>
                    </div>      
                    <div class="form-group form-group-sm">
                        <label for="endDateActive" class="control-label col-md-4">End Date:</label>
                        <div  class="col-md-8">
                            <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input4" data-link-format="yyyy-mm-dd">
                                <input class="form-control" size="16" type="text" id="endDateActive" value="<?php echo $endDateActive; ?>" readonly="">
                                <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                <span class="input-group-addon" onclick="javascript:unfreezeDialog();"><span class="glyphicon glyphicon-info-sign"></span></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" style="float:right;padding-right: 1px;">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <?php if($vwtypActn != "VIEW") { ?>
                        <button type="button" class="btn btn-primary" onclick="saveGrpMemberForm('myFormsModalx', '<?php echo $rowID; ?>','<?php echo $vwtypActn; ?>');">Save Changes</button>
                    <?php } ?>
                </div>
            </form>
            <?php
        }       
        else if ($vwtyp == "10") {
            /*             * Find Directors* */
            $error = "";
            $searchAll = true;

            $srchFor = isset($_POST['searchfor']) ? cleanInputData($_POST['searchfor']) : '';
            $srchIn = isset($_POST['searchin']) ? cleanInputData($_POST['searchin']) : 'Both';
            $pageNo = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
            $lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 10;
            $sortBy = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : "Full Name";
            $otherPrsnType = isset($_POST['prsnType']) ? cleanInputData($_POST['prsnType']) : "Director";

            if (strpos($srchFor, "%") === FALSE) {
                $srchFor = "%" . str_replace(" ", "%", $srchFor) . "%";
                $srchFor = str_replace("%%", "%", $srchFor);
            }

            $canAddPrsn = test_prmssns($dfltPrvldgs[7], $mdlNm);
            /* echo $cntent . "<li>
              <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
              <span style=\"text-decoration:none;\">Data Administration</span>
              </li>
              </ul>
              </div>"; */
            $total = get_OtherPrsnTtl($srchFor, $srchIn, $orgID, $searchAll, "Director");
            if ($pageNo > ceil($total / $lmtSze)) {
                $pageNo = 1;
            } else if ($pageNo < 1) {
                $pageNo = ceil($total / $lmtSze);
            }

            $curIdx = $pageNo - 1;
            $result = get_OtherPrsn($srchFor, $srchIn, $curIdx, $lmtSze, $orgID, $searchAll, $sortBy, "Director");
            $cntr = 0;
            $colClassType1 = "col-lg-2";
            $colClassType2 = "col-lg-8";
            ?> 
            <form id='dataAdminForm' action='' method='post' accept-charset='UTF-8'>
                <div class="row" style="margin-bottom:10px;">
                    <?php
                    if ($canAddPrsn === true) {
                        ?>   
                        <div class="<?php echo "col-lg-2"; ?>" style="padding:0px 1px 0px 15px !important;">                    
                            <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="getCustomersForm('myFormsModal', 'myFormsModalBody', 'myFormsModalTitle', 'Add New Individual Customer', 11, <?php echo $subPgNo; ?>, 0, 'ADD', -1);">
                                <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                New
                            </button>
                        </div>
                        <?php
                    } else {
                        //$colClassType1 = "col-lg-2";
                        $colClassType2 = "col-lg-8";
                    }
                    ?>
                    <div class="<?php echo $colClassType2; ?>" style="padding:0px 1px 0px 15px !important;">
                        <div class="<?php echo "col-lg-6"; ?>" style="padding:0px 15px 0px 15px !important;">
                            <div class="input-group">
                                <input class="form-control" id="dataAdminSrchFor" type = "text" placeholder="Search For" value="<?php
                                echo trim(str_replace("%", " ", $srchFor));
                                ?>" onkeyup="enterKeyFuncCust(event, '', '#allmodules', 'grp=17&typ=1&pg=1&subPgNo=1.4')">
                                <input id="dataAdminPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                <label class="btn btn-primary btn-file input-group-addon" onclick="getCustData('clear', '#allmodules', 'grp=17&typ=1&pg=1&subPgNo=1.4')">
                                    <span class="glyphicon glyphicon-remove"></span>
                                </label>
                                <label class="btn btn-primary btn-file input-group-addon" onclick="getCustData('', '#allmodules', 'grp=17&typ=1&pg=1&subPgNo=1.4')">
                                    <span class="glyphicon glyphicon-search"></span>
                                </label> 
                            </div>
                        </div>
                        <div class="<?php echo "col-lg-6"; ?>">
                            <div class="input-group">
                                <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                <select data-placeholder="Select..." class="form-control chosen-select" id="dataAdminSrchIn">
                                    <?php
                                    $valslctdArry = array("", "", "", "", "", "", "", "", "", "");
                                    $srchInsArrys = array("Full Name", "ID", "Residential Address"
                                        , "Contact Information", "Linked Firm/Workplace", "Date of Birth",
                                        "Home Town", "Gender", "Marital Status");
                                    for ($z = 0; $z < count($srchInsArrys); $z++) {
                                        if ($srchIn == $srchInsArrys[$z]) {
                                            $valslctdArry[$z] = "selected";
                                        }
                                        ?>
                                        <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                    <?php } ?>
                                </select>
                                <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                <select data-placeholder="Select..." class="form-control chosen-select" id="dataAdminDsplySze" style="min-width:70px !important;">                            
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
                        <div class="<?php echo "col-lg-4"; ?>" style="display:none;">
                            <div class="input-group">                        
                                <span class="input-group-addon"><span class="glyphicon glyphicon-sort-by-attributes"></span></span>
                                <select data-placeholder="Select..." class="form-control chosen-select" id="dataAdminSortBy">
                                    <?php
                                    $valslctdArry = array("", "", "", "", "");
                                    $srchInsArrys = array("Full Name", "ID ASC", "ID DESC", "Date Added DESC", "Date of Birth");
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
                    </div>
                    <div class="<?php echo "col-lg-2"; ?>">
                        <nav aria-label="Page navigation">
                            <ul class="pagination" style="margin: 0px !important;">
                                <li>
                                    <a href="javascript:getCustData('previous', '#allmodules', 'grp=17&typ=1&pg=1&subPgNo=1.1');" aria-label="Previous">
                                        <span aria-hidden="true">&laquo;</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:getCustData('next', '#allmodules', 'grp=17&typ=1&pg=1&subPgNo=1.1');" aria-label="Next">
                                        <span aria-hidden="true">&raquo;</span>
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
                <div class="row"> 
                    <div  class="col-md-12">
                        <table class="table table-striped table-bordered table-responsive" id="dataAdminTable" cellspacing="0" width="100%" style="width:100%;">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <?php if ($canAddPrsn === true) { ?>
                                        <th>...</th>
                                    <?php } ?>		
                                    <th>ID No.</th>
                                    <th>Full Name</th>
                                    <th>...</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                while ($row = loc_db_fetch_array($result)) {
                                    /**/
                                    $cntr += 1;

                                    /* $childArray = array(
                                      'checked' => var_export($chckd, TRUE),
                                      'PersonID' => $row[0],
                                      'RowNum' => ($curIdx * $lmtSze) + ($cntr + 1),
                                      'LocIDNo' => $row[1],
                                      'FullName' => $row[2],
                                      'DateOfBirth' => $row[9],
                                      'WorkPlace' => str_replace("()", "", $row[22] . " (" . $row[24] . ")"),
                                      'Email' => $row[14],
                                      'TelNos' => trim($row[15] . "," . $row[16], ","),
                                      'PostalResAddress' => trim($row[13] . " " . $row[12], " "),
                                      'Title' => $row[25],
                                      'FirstName' => $row[4],
                                      'Surname' => $row[5],
                                      'OtherNames' => $row[6],
                                      'ImageLoc' => $nwFileName,
                                      'Gender' => $row[7],
                                      'MaritalStatus' => $row[8],
                                      'PlaceOfBirth' => $row[10],
                                      'Religion' => $row[11],
                                      'ResidentialAddress' => $row[12],
                                      'PostalAddress' => $row[13],
                                      'TelNo' => $row[15],
                                      'MobileNo' => $row[16],
                                      'FaxNo' => $row[17],
                                      'HomeTown' => $row[19],
                                      'Nationality' => $row[20],
                                      'LinkedFirmOrgID' => $row[21],
                                      'LinkedFirmSiteID' => $row[23],
                                      'LinkedFirmName' => $row[22],
                                      'LinkedSiteName' => $row[24],
                                      'PrsnType' => $row[26],
                                      'PrnTypRsn' => $row[27],
                                      'FurtherDetails' => $row[28],
                                      'StartDate' => $row[29],
                                      'EndDate' => $row[30]); */
                                    ?>
                                    <tr id="dtAdmnBscPrsnPrflRow<?php echo $cntr; ?>">                                    
                                        <td><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                        <?php if ($canAddPrsn === true) { ?>                                    
                                            <td>
                                                <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="Edit Basic Profile" 
                                                        onclick="getCustomersForm('myFormsModal', 'myFormsModalBody', 'myFormsModalTitle', 'Edit Customer Profile', 11, <?php echo $subPgNo; ?>, 0, 'EDIT', <?php echo $row[0]; ?>);" style="padding:2px !important;" style="padding:2px !important;">
                                                    <!--<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>-->
                                                    <img src="cmn_images/edit32.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                </button>
                                            </td>
                                        <?php } ?>
                                        <td><?php echo $row[1]; ?></td>
                                        <td><?php echo $row[2]; ?></td>
                                        <td>
                                            <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Basic Profile" onclick="getBscProfileForm('myFormsModalLg', 'myFormsModalBodyLg', 'myFormsModalTitleLg', 'dtAdmnBscPrsnPrflForm', 'View Person Basic Profile', <?php echo $row[0]; ?>, 0, 1, 'VIEW')" style="padding:2px !important;" style="padding:2px !important;">
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
        }
    } 
    else if ($subPgNo == 1.4) {//OTHER PERSONS
        $trnsType = "Customer Groups";
        $formTitle = "Group Customers - Attachments";
        if ($vwtyp == "0") {
            /* BASIC DATA */
            if ($vwtypActn == "EDIT" || $vwtypActn == "VIEW") {
                /* Read Only */
                $tblNm1 = "mcf.mcf_prsn_names_nos";

                $cnt = getOtherPrsnDataChngPndngCount($pkID);
                if($cnt > 0){
                    $tblNm1 = "mcf.mcf_prsn_names_nos_hstrc";        
                }                
                
                $result = get_OtherPrsnDet($pkID, $tblNm1); 
                
                $mkReadOnly = "";
                $mkReadOnlyDsbld = "";
                $shwHydNtlntySts = "style=\"display:none !important;\"";

                $picPath = "OP_Picture/";
                $signPath = "OP_Signature/";
                while ($row = loc_db_fetch_array($result)) {
                    $imgLoc = $row[2];
                    $temp1 = explode(".", $imgLoc);
                    $extension1 = end($temp1);
                    if (strlen(trim($extension1)) <= 0) {
                        $extension1 = "png";
                    }
                    $nwFileName1 = encrypt1($imgLoc, $smplTokenWord1) . "." . $extension1;
                    $ftp_src = $ftp_base_db_fldr . "/Mcf/Customers/" . $picPath . $imgLoc;
                    $fullPemDest = $fldrPrfx . $tmpDest . $nwFileName1;
                    if (file_exists($ftp_src) && is_dir($ftp_src) === FALSE) {
                        copy("$ftp_src", "$fullPemDest");
                    } else if (!file_exists($fullPemDest)) {
                        $ftp_src = $fldrPrfx . 'cmn_images/image_up.png';
                        copy("$ftp_src", "$fullPemDest");
                    }
                    $nwFileName1 = $tmpDest . $nwFileName1;

                    $imgLoc = $row[30];
                    $temp2 = explode(".", $imgLoc);
                    $extension2 = end($temp2);
                    if (strlen(trim($extension2)) <= 0) {
                        $extension2 = "png";
                    }
                    $nwFileName2 = encrypt1($imgLoc, $smplTokenWord1) . "." . $extension2;
                    $ftp_src = $ftp_base_db_fldr . "/Mcf/Customers/" . $signPath . $imgLoc;
                    $fullPemDest = $fldrPrfx . $tmpDest . $nwFileName2;
                    if (file_exists($ftp_src) && is_dir($ftp_src) === FALSE) {
                        copy("$ftp_src", "$fullPemDest");
                    } else if (!file_exists($fullPemDest)) {
                        $ftp_src = $fldrPrfx . 'cmn_images/no_image.png';
                        copy("$ftp_src", "$fullPemDest");
                    }
                    $nwFileName2 = $tmpDest . $nwFileName2;
                    $trnsStatus = $row[24];
                    $rqstatusColor = "red";  
                    if($trnsStatus == "Unauthorized" || $trnsStatus == "Initiated"){
                        $shwHydNtlntySts = "style=\"display:block !important;\"";
                    }
                    
                    
                    if($vwtypActn == "VIEW" || ($trnsStatus == "Initiated" || $trnsStatus == "Approved" || $trnsStatus == "Unauthorized" || $trnsStatus == "Authorized")){
                        $mkReadOnly = "readonly=\"readonly\"";
                        $mkReadOnlyDsbld = "disabled=\"true\"";
                    }                    
                    
                    $tblNmCustAuthrzd = ""; 
                    $lblColor = "red";
                    $resultAuthrzd = "";

                    //if($trnsStatus == "Unauthorized" || $trnsStatus == "Initiated"){
                    if($cnt > 0){
                        $tblNmCustAuthrzd = "mcf.mcf_prsn_names_nos";        
                        $resultAuthrzd = get_OtherPrsnDet($pkID, $tblNmCustAuthrzd);
                        while ($rowATZ = loc_db_fetch_array($resultAuthrzd)) {
                            $rowATZ0 = $rowATZ[0]; $rowATZ1 = $rowATZ[1]; $rowATZ2 = $rowATZ[2]; $rowATZ3 = $rowATZ[3]; $rowATZ4 = $rowATZ[4];
                            $rowATZ5 = $rowATZ[5]; $rowATZ6 = $rowATZ[6]; $rowATZ7 = $rowATZ[7]; $rowATZ8 = $rowATZ[8]; $rowATZ9 = $rowATZ[9];
                            $rowATZ10 = $rowATZ[10]; $rowATZ11 = $rowATZ[11]; $rowATZ12 = $rowATZ[12]; $rowATZ13 = $rowATZ[13]; $rowATZ14 = $rowATZ[14];
                            $rowATZ15 = $rowATZ[15]; $rowATZ16 = $rowATZ[16]; $rowATZ17 = $rowATZ[17]; $rowATZ18 = $rowATZ[18]; $rowATZ19 = $rowATZ[19];
                            $rowATZ20 = $rowATZ[20]; $rowATZ21 = $rowATZ[21]; $rowATZ22 = $rowATZ[22]; $rowATZ23 = $rowATZ[23]; $rowATZ24 = $rowATZ[24];
                            $rowATZ25 = $rowATZ[25]; $rowATZ26 = $rowATZ[26]; $rowATZ27 = $rowATZ[27]; $rowATZ28 = $rowATZ[28]; $rowATZ29 = $rowATZ[29];
                            $rowATZ30 = $rowATZ[30]; $rowATZ31 = $rowATZ[31]; $rowATZ32 = $rowATZ[32];  $rowATZ33 = $rowATZ[33];
    //                       $rowATZ34 = $rowATZ[34];$rowATZ35 = $rowATZ[35];
    //                        $rowATZ1 = $rowATZ[1]; $rowATZ2 = $rowATZ[2]; $rowATZ3 = $rowATZ[3]; $rowATZ4 = $rowATZ[4];
    //                        $rowATZ0 = $rowATZ[0]; $rowATZ1 = $rowATZ[1]; $rowATZ2 = $rowATZ[2]; $rowATZ3 = $rowATZ[3]; $rowATZ4 = $rowATZ[4];
                            
                            $v_BranchATZ = getGnrlRecNm("org.org_sites_locations", "location_id", "site_desc||' ('||location_code_name||')' ", $rowATZ25);
                        }               
                    }
                    
                    ?>
                    <input class="form-control" id="addOrEditForm" type = "hidden" placeholder="addOrEditForm" value="Add"/>
                    <div class="row" style="margin: 0px 0px 10px 0px !important;">
                        <div class="col-md-12" style="padding:0px 0px 0px 0px !important;">
                            <button type="button" class="btn btn-default btn-sm phone-only-btn" onclick="openATab('#allmodules', 'grp=17&typ=1&pg=10&vtyp=0');">Basic Data</button>
                            <button type="button" class="btn btn-default btn-sm phone-only-btn" onclick="openATab('#prfBCOPAddPrsnDataEDT', 'grp=17&typ=1&pg=10&vtyp=1');">Additional Data</button>
                            <!--<button type="button" class="btn btn-default btn-sm phone-only-btn" onclick="openATab('#prflBCOPAttchmntsEDT', 'grp=17&typ=1&pg=10&vtyp=2');">Attachments</button>-->
                        </div>
                    </div>
                    <div class="">
                        <div class="row" style="margin: 0px 0px 15px 0px !important;">
                            <div style="float:left;">
                                <button type="button" class="btn btn-default btn-sm" style="height:30px;" id="myVmsTrnsStatusBtn">
                                        <span style="font-weight:bold;">Status: </span><span style="color:<?php echo $rqstatusColor; ?>;font-weight: bold;"><?php echo $trnsStatus; ?></span>
                                </button>
                                <?php if($vwtypActn != "VIEW") { ?>
                                <button type="button" class="btn btn-default btn-sm" style="height:30px;" onclick="getCustomersForm('myFormsModalLg', 'myFormsModalBodyLg', 'myFormsModalTitleLg', 'Edit Person Profile', 11, 1.4, 0, 'EDIT', <?php echo $pkID; ?> , '', 'indCustTableRow1');" data-toggle="tooltip" title="Reload Transaction">
                                        <img src="cmn_images/refresh.bmp" style="padding-right: 2px; height:17px; width:auto; position: relative; vertical-align: middle;">
                                </button>
                                <?php } ?>
                                <button type="button" class="btn btn-default" style="height:30px;" onclick="getOneMcfDocsForm_Gnrl(<?php echo $pkID; ?>,'<?php echo $trnsType; ?>', 140, '<?php echo $formTitle; ?>');" data-toggle="tooltip" data-placement="bottom" title = "Attached Documents">
                                    <img src="cmn_images/adjunto.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                </button>                                                    
                            </div>
                            <div class="" style="float:right;">
                                <?php
                                $bioUserID = isBioDataPrsnt($pkID, "OTH");
                                if ($bioUserID <= 0) {
                                    createBioData($pkID, "OTH");
                                }
                                $bioUser = getBioData($pkID, "OTH");
                                $bioData = "";
                                foreach ($bioUser as $rowBio) {
                                    $finger = getUserBioFinger($rowBio['user_id']);
                                    $register = '';
                                    $verification = '';
                                    $deleteBio = '';
                                    $url_register = base64_encode($bio_base_path . "register.php?user_id=" . $rowBio['user_id']);
                                    $url_verification = base64_encode($bio_base_path . "verification.php?user_id=" . $rowBio['user_id']);
                                    if (count($finger) == 0) {
                                        $register = "<a href='finspot:FingerspotReg;$url_register' class='btn btn-xs btn-primary' onclick=\"user_register('" . $rowBio['user_id'] . "','" . $rowBio['user_name'] . "',$pkID)\">Capture Biometric</a>";
                                    } else {
                                        $verification = "<a href='finspot:FingerspotVer;$url_verification' class='btn btn-xs btn-success'>Verify Biometric</a>";
                                        $deleteBio = "<button type=\"button\" class=\"btn btn-sm btn-danger\" onclick=\"user_delete('" . $rowBio['user_id'] . "','" . $rowBio['user_name'] . "',$pkID);\" style=\"margin-left:5px;height:28px;\">Delete</button>";
                                    }
                                    $bioData = "<code id='user_finger_" . $rowBio['user_id'] . "' style=\"display:none;\">" . count($finger) . "</code>"
                                            . "$register"
                                            . "$verification"
                                            . "$deleteBio";
                                }
                                echo $bioData;
                                ?>
                                <?php if ($vwtypActn === 'EDIT' && ($trnsStatus == "Authorized" || $trnsStatus == "Approved") && (test_prmssns($dfltPrvldgs[73], $mdlNm) === true)) { ?>
                                <button type="button" class="btn btn-default btn-sm" style="" onclick="modifyAutrzPrsnDataRqst(<?php echo $pgNo; ?>, <?php echo $subPgNo; ?>, <?php echo $vwtyp; ?>);">
                                    <img src="cmn_images/edit32.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">
                                    MODIFY DATA
                                </button>
                                 <?php } else if ($trnsStatus == "Incomplete" || $trnsStatus == "Rejected" || $trnsStatus == "Withdrawn") { ?>  
                                <button type="button" class="btn btn-default btn-sm" style="" onclick="saveBasicDataOP(<?php echo $pgNo; ?>, <?php echo $subPgNo; ?>, <?php echo $vwtyp; ?>, 0);">
                                    <img src="cmn_images/FloppyDisk.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">
                                    SAVE
                                </button>
                                <button type="button" class="btn btn-default btn-sm" style="" onclick="saveBasicDataOP(<?php echo $pgNo; ?>, <?php echo $subPgNo; ?>, <?php echo $vwtyp; ?>, 1);">
                                    <img src="cmn_images/Emailcon.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">
                                    SUBMIT
                                </button>                                    
                                 <?php } else if ($trnsStatus == "Unauthorized") { ?>    
                                    <?php if (didAuthorizerSubmit($pkID, $usrID)) { ?>
                                    <button type="button" class="btn btn-default btn-sm" style="height:30px;" onclick="wthdrwRjctAutrzPrsnDataRqst('WITHDRAW', <?php echo $pgNo; ?>, <?php echo $subPgNo; ?>, <?php echo $vwtyp; ?>);"><img src="cmn_images/withdraw_rqst.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Withdraw&nbsp;</button>                                                                          
                                    <?php } else { if ($vwtypActn === "VIEW" && canPrsnSeeOtherPrsnBranchDocs($prsnID, $pkID, $row[33]) === true && test_prmssns($dfltPrvldgs[75], $mdlNm) === true) {  ?>
                                    <button type="button" class="btn btn-default btn-sm" style="height:30px;" onclick="rjctAutrzPrsnDataRqst('REJECT', <?php echo $pgNo; ?>, <?php echo $subPgNo; ?>, <?php echo $vwtyp; ?>);"><img src="cmn_images/withdraw_rqst.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Reject&nbsp;</button>                                         
                                    <button type="button" class="btn btn-default btn-sm" style="height:30px;" onclick="rjctAutrzPrsnDataRqst('AUTHORIZE', <?php echo $pgNo; ?>, <?php echo $subPgNo; ?>, <?php echo $vwtyp; ?>);"><img src="cmn_images/Stamp-512.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Authorize&nbsp;</button>                                                                                                                                     
                                    <?php } } } ?>                                 
                            </div>                                            
                        </div>                          
                        <ul id="opTabs" class="nav nav-tabs rho-hideable-tabs" style="margin-top:-10px !important;">
                            <li class="active"><a data-toggle="tab" data-rhodata="&pg=10&vtyp=0" href="#prflHomeBCOPEDT" id="prflBCHomeEDTtab">Basic Data</a></li>
                            <!--<li id="addPrsnData" style="display: none;"><a data-toggle="tabajxprfledt" data-rhodata="&pg=10&vtyp=1" href="#prfBCOPAddPrsnDataEDT" onclick="newCustomerOpenATab('#prfBCOPAddPrsnDataEDT', 'grp=17&typ=1&pg=<?php echo $pgNo; ?>&subPgNo=<?php echo $subPgNo; ?>&vtyp=1&vtypActn=ADD');" id="prfBCOPAddPrsnDataEDTtab">Additional Data</a></li>-->
                            <li><a data-toggle="tabajxprfledt" data-rhodata="&pg=10&vtyp=1" href="#prfBCOPAddPrsnDataEDT" onclick="openATab('#prfBCOPAddPrsnDataEDT', 'grp=17&typ=1&pg=<?php echo $pgNo; ?>&subPgNo=1.6&vtyp=1&vtypActn=<?php echo $vwtypActn; ?>&custID=<?php echo $pkID; ?>&formType=Other Person&rvsnTtlAPD=<?php echo $row[33]; ?>');" id="prfBCOPAddPrsnDataEDTtab">Additional Data</a></li>
                        </ul>
                        <div class="row">                  
                            <div class="col-md-12">
                                <div class="custDiv"> 
                                    <div class="tab-content" id="OPtabContent">
                                        <div id="prflHomeBCOPEDT" class="tab-pane fadein active" style="border:none !important;">                        
                                            <form class="form-horizontal">
                                                <div class="row">
                                                    <div class="col-lg-4">
                                                        <fieldset class="basic_person_fs1"><legend class="basic_person_lg">Person's Picture</legend>
                                                            <div style="margin-bottom: 10px;">
                                                                <img src="<?php echo $nwFileName1; ?>" alt="..." id="img1Test" class="img-rounded center-block img-responsive" style="height: 195px !important; width: auto !important;">                                            
                                                            </div>
                                                            <div class="form-group form-group-sm">
                                                                <div class="col-md-12">
                                                                    <div class="input-group">
                                                                        <label class="btn btn-primary btn-file input-group-addon">
                                                                            Browse... <input type="file" id="custPicture" name="custPicture" onchange="changeImgSrc(this, '#img1Test', '#img1SrcLoc');" class="btn btn-default"  style="display: none;">
                                                                        </label>
                                                                        <input type="text" class="form-control" aria-label="..." id="img1SrcLoc" value="">                                                        
                                                                    </div>                                                    
                                                                </div>                                            
                                                            </div>                                        
                                                        </fieldset>
                                                    </div>                                
                                                    <div class="col-lg-4">
                                                        <fieldset class="basic_person_fs1"><legend class="basic_person_lg">Names</legend>
                                                            <div class="form-group form-group-sm">
                                                                <?php if($cnt > 0 && $row[1] != $rowATZ1) { ?>
                                                                <label data-toggle="tooltip" title="<?php echo $rowATZ1; ?>" for="idNo" class="control-label col-md-4"><a href="#" style="color:red;" onclick="dsplyAuthrzdData('<?php echo $rowATZ1; ?>');">ID No:</a></label>
                                                                <?php } else { ?>
                                                                <label for="idNo" class="control-label col-md-4">ID No:</label>
                                                                <?php } ?>
                                                                <div class="col-md-8">
                                                                    <input class="form-control" id="personID" type = "hidden" placeholder="Person ID" value="<?php echo $row[0]; ?>"/>
                                                                    <input class="form-control" id="rvsnTtl" type = "hidden" placeholder="Revision Total" value="<?php echo $row[33]; ?>"/>
                                                                    <input class="form-control" id="idNo" type = "text" placeholder="ID No." value="<?php echo $row[1]; ?>" readonly=""/>
                                                                </div>
                                                            </div> 
                                                            <div class="form-group form-group-sm">
                                                                <?php if($cnt > 0 && $row[3] != $rowATZ3) { ?>
                                                                <label data-toggle="tooltip" title="<?php echo $rowATZ3; ?>" for="title" class="control-label col-md-4"><a href="#" style="color:red;" onclick="dsplyAuthrzdData('<?php echo $rowATZ3; ?>');">Title:</a></label>
                                                                <?php } else { ?>
                                                                <label for="title" class="control-label col-md-4">Title:</label>
                                                                <?php } ?>
                                                                <div  class="col-md-8">
                                                                    <select <?php echo $mkReadOnlyDsbld; ?> class="form-control rqrdFld" id="title" >
                                                                        <?php
                                                                        $brghtStr = "";
                                                                        $isDynmyc = FALSE;
                                                                        $titleRslt = getLovValues("%", "Both", 0, 100, $brghtStr,
                                                                                getLovID("Person Titles"), $isDynmyc, -1, "", "");
                                                                        while ($titleRow = loc_db_fetch_array($titleRslt)) {
                                                                            $selectedTxt = "";
                                                                            if ($titleRow[0] == $row[3]) {
                                                                                $selectedTxt = "selected";
                                                                            }
                                                                            ?>
                                                                            <option value="<?php echo $titleRow[0]; ?>" <?php echo $selectedTxt; ?>><?php echo $titleRow[0]; ?></option>
                                                                            <?php
                                                                        }
                                                                        ?>
                                                                    </select>
                                                                </div>
                                                            </div>  
                                                            <div class="form-group form-group-sm">
                                                                <?php if($cnt > 0 && $row[4] != $rowATZ4) { ?>
                                                                <label data-toggle="tooltip" title="<?php echo $rowATZ4; ?>" for="firstName" class="control-label col-md-4"><a href="#" style="color:red;" onclick="dsplyAuthrzdData('<?php echo $rowATZ4; ?>');">First Name:</a></label>
                                                                <?php } else { ?>
                                                                <label for="firstName" class="control-label col-md-4">First Name:</label>
                                                                <?php } ?>
                                                                <div  class="col-md-8">
                                                                    <input <?php echo $mkReadOnly; ?> class="form-control rqrdFld" id="firstName" type = "text" placeholder="First Name" value="<?php echo $row[4]; ?>"/>
                                                                </div>
                                                            </div> 
                                                            <div class="form-group form-group-sm">
                                                                <?php if($cnt > 0 && $row[5] != $rowATZ5) { ?>
                                                                <label data-toggle="tooltip" title="<?php echo $rowATZ5; ?>" for="surName" class="control-label col-md-4"><a href="#" style="color:red;" onclick="dsplyAuthrzdData('<?php echo $rowATZ5; ?>');">Surname:</a></label>
                                                                <?php } else { ?>
                                                                <label for="surName" class="control-label col-md-4">Surname:</label>
                                                                <?php } ?>
                                                                <div  class="col-md-8">
                                                                    <input <?php echo $mkReadOnly; ?> class="form-control rqrdFld" id="surName" type = "text" placeholder="Surname" value="<?php echo $row[5]; ?>"/>
                                                                </div>
                                                            </div>     
                                                            <div class="form-group form-group-sm">
                                                                <?php if($cnt > 0 && $row[6] != $rowATZ6) { ?>
                                                                <label data-toggle="tooltip" title="<?php echo $rowATZ6; ?>" for="otherNames" class="control-label col-md-4"><a href="#" style="color:red;" onclick="dsplyAuthrzdData('<?php echo $rowATZ6; ?>');">Other Names:</a></label>
                                                                <?php } else { ?>
                                                                <label for="otherNames" class="control-label col-md-4">Other Names:</label>
                                                                <?php } ?>
                                                                <div  class="col-md-8">
                                                                    <textarea <?php echo $mkReadOnly; ?> class="form-control" id="otherNames" cols="2" placeholder="Other Names" rows="3"><?php echo $row[6]; ?></textarea>
                                                                </div>
                                                            </div>
                                                            <div class="form-group form-group-sm">
                                                                <?php if($cnt > 0 && $row[8] != $rowATZ8) { ?>
                                                                <label data-toggle="tooltip" title="<?php echo $rowATZ8; ?>" for="gender" class="control-label col-md-4"><a href="#" style="color:red;" onclick="dsplyAuthrzdData('<?php echo $rowATZ8; ?>');">Gender:</a></label>
                                                                <?php } else { ?>
                                                                <label for="gender" class="control-label col-md-4">Gender:</label>
                                                                <?php } ?>
                                                                <div  class="col-md-8">
                                                                    <select <?php echo $mkReadOnlyDsbld; ?> class="form-control rqrdFld" id="gender" >
                                                                        <?php
                                                                        $brghtStr = "";
                                                                        $isDynmyc = FALSE;
                                                                        $titleRslt = getLovValues("%", "Both", 0, 100, $brghtStr,
                                                                                getLovID("Gender"), $isDynmyc, -1, "", "");
                                                                        while ($titleRow = loc_db_fetch_array($titleRslt)) {
                                                                            $selectedTxt = "";
                                                                            if ($titleRow[0] == $row[8]) {
                                                                                $selectedTxt = "selected";
                                                                            }
                                                                            ?>
                                                                            <option value="<?php echo $titleRow[0]; ?>" <?php echo $selectedTxt; ?>><?php echo $titleRow[0]; ?></option>
                                                                            <?php
                                                                        }
                                                                        ?>
                                                                    </select>
                                                                </div>
                                                            </div> 
                                                        </fieldset>
                                                    </div>
                                                    <div class="col-lg-4"> 
                                                        <fieldset class="basic_person_fs1"><legend class="basic_person_lg">Personal Data</legend>
                                                            <div class="form-group form-group-sm">
                                                                <?php if($cnt > 0 && $row[9] != $rowATZ9) { ?>
                                                                <label data-toggle="tooltip" title="<?php echo $rowATZ8; ?>" for="maritalStatus" class="control-label col-md-4"><a href="#" style="color:red;" onclick="dsplyAuthrzdData('<?php echo $rowATZ9; ?>');">Marital Status:</a></label>
                                                                <?php } else { ?>
                                                                <label for="maritalStatus" class="control-label col-md-4">Marital Status:</label>
                                                                <?php } ?>
                                                                <div  class="col-md-8">
                                                                    <select <?php echo $mkReadOnlyDsbld; ?> class="form-control rqrdFld" id="maritalStatus" >
                                                                        <?php
                                                                        $brghtStr = "";
                                                                        $isDynmyc = FALSE;
                                                                        $titleRslt = getLovValues("%", "Both", 0, 100, $brghtStr,
                                                                                getLovID("Marital Status"), $isDynmyc, -1, "", "");
                                                                        while ($titleRow = loc_db_fetch_array($titleRslt)) {
                                                                            $selectedTxt = "";
                                                                            if ($titleRow[0] == $row[9]) {
                                                                                $selectedTxt = "selected";
                                                                            }
                                                                            ?>
                                                                            <option value="<?php echo $titleRow[0]; ?>" <?php echo $selectedTxt; ?>><?php echo $titleRow[0]; ?></option>
                                                                            <?php
                                                                        }
                                                                        ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="form-group form-group-sm">
                                                                <?php if($cnt > 0 && $row[10] != $rowATZ10) { ?>
                                                                <label data-toggle="tooltip" title="<?php echo $rowATZ10; ?>" for="dob" class="control-label col-md-4"><a href="#" style="color:red;" onclick="dsplyAuthrzdData('<?php echo $rowATZ10; ?>');">Date of Birth:</a></label>
                                                                <?php } else { ?>
                                                                <label for="dob" class="control-label col-md-4">Date of Birth</label>
                                                                <?php } ?>
                                                                <div class="col-md-8">
                                                                    <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                                                        <input class="form-control rqrdFld" size="16" type="text" id="dob" value="<?php echo $row[10]; ?>" readonly="">
                                                                        <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                                                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                                    </div>
                                                                </div>
                                                            </div> 
                                                            <div class="form-group form-group-sm">
                                                                <?php if($cnt > 0 && $row[11] != $rowATZ11) { ?>
                                                                <label data-toggle="tooltip" title="<?php echo $rowATZ11; ?>" for="pob" class="control-label col-md-4"><a href="#" style="color:red;" onclick="dsplyAuthrzdData('<?php echo $rowATZ11; ?>');">Place of Birth:</a></label>
                                                                <?php } else { ?>
                                                                <label for="pob" class="control-label col-md-4">Place of Birth</label>
                                                                <?php } ?>
                                                                <div  class="col-md-8">
                                                                    <textarea <?php echo $mkReadOnly; ?> class="form-control" id="pob" cols="2" placeholder="Place of Birth" rows="2"><?php echo $row[11]; ?></textarea>
                                                                </div>
                                                            </div> 
                                                            <div class="form-group form-group-sm">
                                                                <?php if($cnt > 0 && $row[20] != $rowATZ20) { ?>
                                                                <label data-toggle="tooltip" title="<?php echo $rowATZ11; ?>" for="nationality" class="control-label col-md-4"><a href="#" style="color:red;" onclick="dsplyAuthrzdData('<?php echo $rowATZ20; ?>');">Nationality:</a></label>
                                                                <?php } else { ?>
                                                                <label for="nationality" class="control-label col-md-4">Nationality:</label>
                                                                <?php } ?>
                                                                <div class="col-md-8">
                                                                    <select <?php echo $mkReadOnlyDsbld; ?> class="form-control rqrdFld" id="nationality" >
                                                                        <?php
                                                                        $brghtStr = "";
                                                                        $isDynmyc = FALSE;
                                                                        $titleRslt = getLovValues("%", "Both", 0, 100, $brghtStr,
                                                                                getLovID("Nationalities"), $isDynmyc, -1, "", "");
                                                                        while ($titleRow = loc_db_fetch_array($titleRslt)) {
                                                                            $selectedTxt = "";
                                                                            if ($titleRow[0] == $row[20]) {
                                                                                $selectedTxt = "selected";
                                                                            }
                                                                            ?>
                                                                            <option value="<?php echo $titleRow[0]; ?>" <?php echo $selectedTxt; ?>><?php echo $titleRow[0]; ?></option>
                                                                            <?php
                                                                        }
                                                                        ?>
                                                                    </select>
                                                                </div>
                                                            </div>  
                                                            <div class="form-group form-group-sm">
                                                                <?php if($cnt > 0 && $row[19] != $rowATZ19) { ?>
                                                                <label data-toggle="tooltip" title="<?php echo $rowATZ19; ?>" for="homeTown" class="control-label col-md-4"><a href="#" style="color:red;" onclick="dsplyAuthrzdData('<?php echo $rowATZ19; ?>');">Home Town:</a></label>
                                                                <?php } else { ?>
                                                                <label for="homeTown" class="control-label col-md-4">Home Town:</label>
                                                                <?php } ?>
                                                                <div  class="col-md-8">
                                                                    <textarea <?php echo $mkReadOnly; ?> class="form-control" id="pob" cols="2" placeholder="Home Town" rows="1"><?php echo $row[19]; ?></textarea>
                                                                </div>
                                                            </div> 
                                                            <div class="form-group form-group-sm">
                                                                <?php if($cnt > 0 && $row[12] != $rowATZ12) { ?>
                                                                <label data-toggle="tooltip" title="<?php echo $rowATZ12; ?>" for="religion" class="control-label col-md-4"><a href="#" style="color:red;" onclick="dsplyAuthrzdData('<?php echo $rowATZ12; ?>');">Religion:</a></label>
                                                                <?php } else { ?>
                                                                <label for="religion" class="control-label col-md-4">Religion:</label>
                                                                <?php } ?>
                                                                <div  class="col-md-8">
                                                                    <input <?php echo $mkReadOnly; ?> class="form-control" id="religion" type = "text" placeholder="Religion" value="<?php echo $row[12]; ?>"/>
                                                                </div>
                                                            </div>                                              
                                                        </fieldset>   
                                                    </div>
                                                </div>    
                                                <div class="row"><!-- ROW 1 -->
                                                    <div class="col-lg-4">
                                                        <fieldset class="basic_person_fs2"><legend class="basic_person_lg">Signature/Thumbprint</legend>
                                                            <div style="margin-bottom: 10px;">
                                                                <img src="<?php echo $nwFileName2; ?>" alt="..." id="img2Test" class="img-rounded center-block img-responsive" style="height: 195px !important; width: auto !important;">                                            
                                                            </div>
                                                            <div class="form-group form-group-sm">
                                                                <div class="col-md-12">
                                                                    <div class="input-group">
                                                                        <label class="btn btn-primary btn-file input-group-addon">
                                                                            Browse... <input type="file" id="signPicture" name="signPicture" onchange="changeImgSrc(this, '#img2Test', '#img2SrcLoc');" class="btn btn-default"  style="display: none;">
                                                                        </label>
                                                                        <input type="text" class="form-control" aria-label="..." id="img2SrcLoc" value="">                                                        
                                                                    </div>                                                    
                                                                </div>                                            
                                                            </div>                                     
                                                        </fieldset>
                                                    </div>                                
                                                    <div class="col-lg-4">
                                                        <fieldset class="basic_person_fs2"><legend class="basic_person_lg">Contact Information</legend>
                                                            <div class="form-group form-group-sm">
                                                                <?php if($cnt > 0 && $row[21] != $rowATZ21) { ?>
                                                                <label data-toggle="tooltip" title="<?php echo $rowATZ21; ?>" for="linkedFirm" class="control-label col-md-4"><a href="#" style="color:red;" onclick="dsplyAuthrzdData('<?php echo $rowATZ21; ?>');">Linked Firm/ Workplace:</a></label>
                                                                <?php } else { ?>
                                                                <label for="linkedFirm" class="control-label col-md-4">Linked Firm/ Workplace</label>
                                                                <?php } ?>
                                                                <div  class="col-md-8">
                                                                    <div class="input-group">
                                                                        <input type="text" class="form-control" aria-label="..." id="lnkdFirmName" value="<?php echo $row[21]; ?>">
                                                                        <input type="hidden" id="gnrlOrgID" value="<?php echo $orgID; ?>">
                                                                        <input type="hidden" id="lnkdFirmID" value="<?php echo $row[31]; ?>">
                                                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Customers and Suppliers', 'gnrlOrgID', '', '', 'radio', true, '<?php echo $row[21]; ?>', 'lnkdFirmID', 'lnkdFirmName', 'clear', 1, '');">
                                                                            <span class="glyphicon glyphicon-th-list"></span>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="form-group form-group-sm">
                                                                <?php if($cnt > 0 && $row[22] != $rowATZ22) { ?>
                                                                <label data-toggle="tooltip" title="<?php echo $rowATZ22; ?>" for="branch" class="control-label col-md-4"><a href="#" style="color:red;" onclick="dsplyAuthrzdData('<?php echo $rowATZ22; ?>');">Site/Branch:</a></label>
                                                                <?php } else { ?>
                                                                <label for="branch" class="control-label col-md-4">Site/Branch:</label>
                                                                <?php } ?>
                                                                <div  class="col-md-8">
                                                                    <div class="input-group">
                                                                        <input type="text" class="form-control" aria-label="..." id="lnkdFirmLoc" value="<?php echo $row[22]; ?>">  
                                                                        <input type="hidden" id="lnkdFirmLocID" value="<?php echo $row[32]; ?>">
                                                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Customer/Supplier Sites', 'lnkdFirmID', '', '', 'radio', true, '<?php echo $row[22]; ?>', 'lnkdFirmLocID', 'lnkdFirmLoc', 'clear', 1, '');">
                                                                            <span class="glyphicon glyphicon-th-list"></span>                                                                            
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div>  
                                                            <div class="form-group form-group-sm">
                                                                <?php if($cnt > 0 && $row[15] != $rowATZ15) { ?>
                                                                <label data-toggle="tooltip" title="<?php echo $rowATZ15; ?>" for="email" class="control-label col-md-4"><a href="#" style="color:red;" onclick="dsplyAuthrzdData('<?php echo $rowATZ15; ?>');">Email:</a></label>
                                                                <?php } else { ?>
                                                                <label for="email" class="control-label col-md-4">Email:</label>
                                                                <?php } ?>
                                                                <div  class="col-md-8">
                                                                    <input <?php echo $mkReadOnly; ?> class="form-control" id="email" type = "email" placeholder="<?php echo $admin_email; ?>" value="<?php echo $row[15]; ?>"/>
                                                                </div>
                                                            </div> 
                                                            <div class="form-group form-group-sm">
                                                                <?php if($cnt > 0 && (!($row[16] == $rowATZ16 || $row[17] == $rowATZ17))) { ?>
                                                                <label data-toggle="tooltip" title="<?php echo $rowATZ16; ?>" for="email" class="control-label col-md-4"><a href="#" style="color:red;" onclick="dsplyAuthrzdData('<?php echo $rowATZ16."/".$rowATZ17; ?>');">Contact Nos:</a></label>
                                                                <?php } else { ?>
                                                                <label for="telephone" class="control-label col-md-4">Contact Nos:</label>
                                                                <?php } ?>
                                                                <div  class="col-md-8">
                                                                    <input <?php echo $mkReadOnly; ?> class="form-control" id="telNo" type = "text" placeholder="Telephone" value="<?php echo $row[16]; ?>"/>
                                                                    <input <?php echo $mkReadOnly; ?> class="form-control rqrdFld" id="mobileNo" type = "text" placeholder="Mobile" value="<?php echo $row[17]; ?>"/>                                       
                                                                </div>
                                                            </div>     
                                                            <div class="form-group form-group-sm">
                                                                <?php if($cnt > 0 && $row[18] != $rowATZ18) { ?>
                                                                <label data-toggle="tooltip" title="<?php echo $rowATZ18; ?>" for="email" class="control-label col-md-4"><a href="#" style="color:red;" onclick="dsplyAuthrzdData('<?php echo $rowATZ18; ?>');">Fax:</a></label>
                                                                <?php } else { ?>                                                                
                                                                <label for="fax" class="control-label col-md-4">Fax:</label>
                                                                <?php } ?>
                                                                <div  class="col-md-8">
                                                                    <input <?php echo $mkReadOnly; ?> class="form-control" id="faxNo" type = "text" placeholder="Fax" value="<?php echo $row[18]; ?>"/>
                                                                </div>
                                                            </div> 
                                                        </fieldset>                                                
                                                    </div>
                                                    <div class="col-lg-4">
                                                        <fieldset class="basic_person_fs2"><legend class="basic_person_lg">Relationship Type</legend>                                    
                                                            <div class="form-group form-group-sm" style="display:none !important;">
                                                                <?php if($cnt > 0 && $row[23] != $rowATZ23) { ?>
                                                                <label data-toggle="tooltip" title="<?php echo $rowATZ23; ?>" for="relation" class="control-label col-md-4"><a href="#" style="color:red;" onclick="dsplyAuthrzdData('<?php echo $rowATZ23; ?>');">Person Type:</a></label>
                                                                <?php } else { ?>
                                                                <label for="relation" class="control-label col-md-4">Person Type:</label>
                                                                <?php } ?>
                                                                <div  class="col-md-8">
                                                                    <select <?php echo $mkReadOnlyDsbld; ?> class="form-control rqrdFld" id="relation" >
                                                                        <?php
                                                                        $brghtStr = "";
                                                                        $isDynmyc = FALSE;
                                                                        $titleRslt = getLovValues("%", "Both", 0, 100, $brghtStr,
                                                                                getLovID("MCF Other Person Types"), $isDynmyc, -1, "", "");
                                                                        while ($titleRow = loc_db_fetch_array($titleRslt)) {
                                                                            $selectedTxt = "";
                                                                            if ($titleRow[0] == $row[23]) {
                                                                                $selectedTxt = "selected";
                                                                            }
                                                                            ?>
                                                                            <option value="<?php echo $titleRow[0]; ?>" <?php echo $selectedTxt; ?>><?php echo $titleRow[0]; ?></option>
                                                                            <?php
                                                                        }
                                                                        ?>
                                                                    </select>
                                                                </div>
                                                            </div> 
                                                            <div class="form-group form-group-sm">
                                                                <?php if($cnt > 0 && $row[23] != $rowATZ23) { ?>
                                                                <label data-toggle="tooltip" title="<?php echo $rowATZ23; ?>" for="relation" class="control-label col-md-4"><a href="#" style="color:red;" onclick="dsplyAuthrzdData('<?php echo $rowATZ23; ?>');">Person Type:</a></label>
                                                                <?php } else { ?>
                                                                <label for="relation" class="control-label col-md-4">Person Type:</label>
                                                                <?php } ?>
                                                                <div  class="col-md-8">
                                                                    <select <?php echo $mkReadOnlyDsbld; ?> class="form-control" name="relationTyp[]" id="relationTyp" multiple>
                                                                        <!--<option value="">&nbsp;</option>-->
                                                                        <?php
                                                                        $brghtStr = "";
                                                                        $isDynmyc = FALSE;
                                                                        $titleRslt = getLovValues("%", "Both", 0, 100, $brghtStr,
                                                                                        getLovID("MCF Other Person Types"), $isDynmyc, -1, "", "");
                                                                        while ($titleRow = loc_db_fetch_array($titleRslt)) {
                                                                                $selectedTxt = "";
                                                                                $trnsTypArr = explode(',', $row[23]);
                                                                                foreach ($trnsTypArr as $val) {
                                                                                        if ($val == $titleRow[0]) {
                                                                                                $selectedTxt = "selected=\"selected\"";
                                                                                        }
                                                                                }
                                                                                ?>
                                                                                <option value="<?php echo $titleRow[0]; ?>" <?php echo $selectedTxt; ?>><?php echo $titleRow[0]; ?></option>
                                                                                <?php
                                                                        }
                                                                        ?>
                                                                    </select>
                                                                </div>
                                                            </div>                                                             
                                                            <div  class="form-group form-group-sm">
                                                                <?php if($cnt > 0 && $row[28] != $rowATZ28) { ?>
                                                                <label data-toggle="tooltip" title="<?php echo $rowATZ28; ?>" for="isSignatory" class="control-label col-md-4"><a href="#" style="color:red;" onclick="dsplyAuthrzdData('<?php echo $rowATZ28; ?>');">is Signatory?:</a></label>
                                                                <?php } else { ?>
                                                                <label for="isSignatory" class="control-label col-md-4">is Signatory?:</label>
                                                                <?php } ?>
                                                                <div  class="col-md-8">
                                                                    <?php
                                                                    $chkdYes = "checked=\"\"";
                                                                    $chkdNo = "";
                                                                    if ($row[28] == "NO") {
                                                                        $chkdNo = "checked=\"\"";
                                                                        $chkdYes = "";
                                                                    }
                                                                    ?>
                                                                    <label class="radio-inline"><input type="radio" name="isSignatory" value="YES" <?php echo $chkdYes; ?>>YES</label>
                                                                    <label class="radio-inline"><input type="radio" name="isSignatory" value="NO" <?php echo $chkdNo; ?>>NO</label>
                                                                </div>                                                              
                                                            </div>                                                            
                                                            <div class="form-group form-group-sm">
                                                                <?php if($cnt > 0 && $row[24] != $rowATZ24) { ?>
                                                                <label data-toggle="tooltip" title="<?php echo $rowATZ24; ?>" for="status" class="control-label col-md-4"><a href="#" style="color:red;" onclick="dsplyAuthrzdData('<?php echo $rowATZ24; ?>');">Status:</a></label>
                                                                <?php } else { ?>
                                                                <label for="status" class="control-label col-md-4">Status:</label>
                                                                <?php } ?>
                                                                <div  class="col-md-8">
                                                                    <input class="form-control" id="status" type = "text" placeholder="Status" value="<?php echo$row[24]; ?>" readonly/>
                                                                </div>
                                                            </div>
                                                            <div class="form-group form-group-sm">
                                                                <?php if($cnt > 0 && $row[25] != $rowATZ25) { ?>
                                                                <label data-toggle="tooltip" title="<?php echo $rowATZ25; ?>" for="bnkBranch" class="control-label col-md-4"><a href="#" style="color:red;" onclick="dsplyAuthrzdData('<?php echo getGnrlRecNm("org.org_sites_locations", "location_id",
                                                                                "site_desc||' ('||location_code_name||')'", $row[25]); ?>');">Branch:</a></label>
                                                                <?php } else { ?>
                                                                <label for="bnkBranch" class="control-label col-md-4">Branch</label>
                                                                <?php } ?>
                                                                <div  class="col-md-8">
                                                                    <div class="input-group" style="width:100% !important;">
                                                                        <input type="text" class="form-control" aria-label="..." id="bnkBranch" value="<?php
                                                                        echo getGnrlRecNm("org.org_sites_locations", "location_id",
                                                                                "site_desc||' ('||location_code_name||')'", $row[25]);
                                                                        ?>" readonly="readonly">
                                                                        <input type="hidden" id="gnrlOrgID" value="<?php echo $orgID; ?>">
                                                                        <input type="hidden" id="bnkBranchID" value="<?php echo $row[25]; ?>">
                                                                        <label style="display:none !important;" class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Sites/Locations New', 'gnrlOrgID', '', '', 'radio', true, '<?php echo $row[25]; ?>', 'bnkBranchID', 'bnkBranch', 'clear', 1, '');">
                                                                            <span class="glyphicon glyphicon-th-list"></span>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div>                                                            
                                                            <div class="form-group form-group-sm">
                                                                <?php if($cnt > 0 && $row[26] != $rowATZ26) { ?>
                                                                <label data-toggle="tooltip" title="<?php echo $rowATZ26; ?>" for="startDate" class="control-label col-md-4"><a href="#" style="color:red;" onclick="dsplyAuthrzdData('<?php echo $rowATZ26; ?>');">Start Date:</a></label>
                                                                <?php } else { ?>
                                                                <label for="startDate" class="control-label col-md-4">Start Date:</label>
                                                                <?php } ?>
                                                                <div  class="col-md-8">
                                                                    <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input3" data-link-format="yyyy-mm-dd">
                                                                        <input class="form-control" size="16" type="text" id="startDate" value="<?php echo $row[26]; ?>" readonly="">
                                                                        <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                                                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                                        <span class="input-group-addon" onclick="javascript:unfreezeDialog();"><span class="glyphicon glyphicon-info-sign"></span></span>
                                                                    </div>
                                                                </div>
                                                            </div>      
                                                            <div class="form-group form-group-sm">
                                                                <?php if($cnt > 0 && $row[27] != $rowATZ27) { ?>
                                                                <label data-toggle="tooltip" title="<?php echo $rowATZ27; ?>" for="endDate" class="control-label col-md-4"><a href="#" style="color:red;" onclick="dsplyAuthrzdData('<?php echo $rowATZ27; ?>');">End Date:</a></label>
                                                                <?php } else { ?>
                                                                <label for="endDate" class="control-label col-md-4">End Date:</label>
                                                                <?php } ?>
                                                                <div  class="col-md-8">
                                                                    <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input4" data-link-format="yyyy-mm-dd">
                                                                        <input class="form-control" size="16" type="text" id="endDate" value="<?php echo $row[27]; ?>" readonly="">
                                                                        <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                                                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                                        <span class="input-group-addon" onclick="javascript:unfreezeDialog();"><span class="glyphicon glyphicon-info-sign"></span></span>
                                                                    </div>
                                                                </div>
                                                            </div>  
                                                        </fieldset>                                                
                                                    </div>
                                                </div> 
                                                <div class="row"><!-- ROW 3 -->
                                                    <div class="col-lg-4">
                                                        <fieldset class="basic_person_fs3"><legend class="basic_person_lg">Address</legend> 
                                                            <div class="form-group form-group-sm">
                                                                <?php if($cnt > 0 && $row[14] != $rowATZ14) { ?>
                                                                <label data-toggle="tooltip" title="<?php echo $rowATZ14; ?>" for="postalAddress" class="control-label col-md-4"><a href="#" style="color:red;" onclick="dsplyAuthrzdData('<?php echo $rowATZ14; ?>');">Postal Address:</a></label>
                                                                <?php } else { ?>
                                                                <label for="postalAddress" class="control-label col-md-4">Postal Address:</label>
                                                                <?php } ?>
                                                                <div  class="col-md-8">
                                                                    <textarea <?php echo $mkReadOnly; ?> class="form-control" id="postalAddress" cols="2" placeholder="Postal Address" rows="4"><?php echo $row[14]; ?></textarea>
                                                                </div>
                                                            </div>
                                                            <div class="form-group form-group-sm">
                                                                <?php if($cnt > 0 && $row[13] != $rowATZ13) { ?>
                                                                <label data-toggle="tooltip" title="<?php echo $rowATZ13; ?>" for="residentialAddress" class="control-label col-md-4"><a href="#" style="color:red;" onclick="dsplyAuthrzdData('<?php echo $rowATZ13; ?>');">Residential Address:</a></label>
                                                                <?php } else { ?>
                                                                <label for="residentialAddress" class="control-label col-md-4">Residential Address:</label>
                                                                <?php } ?>
                                                                <div  class="col-md-8">
                                                                    <textarea <?php echo $mkReadOnly; ?> class="form-control rqrdFld" id="residentialAddress" cols="2" placeholder="Residential Address" rows="4"><?php echo $row[13]; ?></textarea>
                                                                </div>
                                                            </div> 
                                                        </fieldset>                                        
                                                    </div>
                                                    <div class="col-lg-8"> 
                                                        <fieldset class="basic_person_fs3"><legend class="basic_person_lg">National ID Cards</legend> 
                                                            <div  class="col-md-12">
                                                                <?php if($vwtypActn != 'VIEW' && $trnsStatus != "Approved" && $trnsStatus != "Unauthorized" && $trnsStatus != "Authorized"){ ?>
                                                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="getIndCustNtnlIDForm('myFormsModalx', 'myFormsModalxBody', 'myFormsModalxTitle', 'ntnlIDCardsForm', '', 'Edit Customer Profile', 11, <?php echo $subPgNo; ?>, 5, '', -1);">
                                                                        <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                        Add National ID Card
                                                                    </button>
                                                                <?php } ?>
                                                                <table id="nationalIDTblEDT" class="table table-striped table-bordered table-responsive" cellspacing="0" width="100%" style="width:100%;">
                                                                    <thead>
                                                                        <tr>
                                                                            <?php if($vwtypActn != 'VIEW' && $trnsStatus != "Approved" && $trnsStatus != "Unauthorized" && $trnsStatus != "Authorized"){ ?>
                                                                            <th>...</th>
                                                                            <th>...</th>
                                                                            <?php } ?>
                                                                            <th>Country</th>
                                                                            <th>ID Type</th>
                                                                            <th>ID No.</th>
                                                                            <th>Date Issued</th>
                                                                            <th>Expiry Date</th>
                                                                            <th>Other Information</th>
                                                                            <th <?php echo $shwHydNtlntySts; ?>>Status</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <?php
                                                                        $result1 = get_PersonAllNtnlty($pkID, "Other Person");
                                                                        $cntr = 0;
                                                                        while ($row1 = loc_db_fetch_array($result1)) {
                                                                            $cntr++;
                                                                            $row1ATZ0 = ""; $row1ATZ1 = ""; $row1ATZ2 = ""; $row1ATZ3 = ""; $row1ATZ4 = ""; $row1ATZ5 = "";
                                                                            $row1ATZ6 = ""; $row1ATZ7 = "";
                                                                            if($row1[8] > 0 && $row1[7] === "Yes"){
                                                                                $result1ATZ = get_PersonAllNtnltyATZ($row1[0]);
                                                                                while ($row1ATZ = loc_db_fetch_array($result1ATZ)) {
                                                                                    $row1ATZ0 = $row1ATZ[0]; $row1ATZ1 = $row1ATZ[1]; $row1ATZ2 = $row1ATZ[2]; $row1ATZ3 = $row1ATZ[3];
                                                                                    $row1ATZ4 = $row1ATZ[4]; $row1ATZ5 = $row1ATZ[5]; $row1ATZ6 = $row1ATZ[6]; $row1ATZ7 = $row1ATZ[7];
                                                                                }
                                                                            }
                                                                             
                                                                            ?>
                                                                            <tr id="ntnlIDCardsRow<?php echo $cntr; ?>">
                                                                                <?php if($vwtypActn != 'VIEW' && $trnsStatus != "Approved" && $trnsStatus != "Unauthorized" && $trnsStatus != "Authorized"){ ?>
                                                                                <td>
                                                                                    <button type="button" class="btn btn-default btn-sm" onclick="getIndCustNtnlIDForm('myFormsModalx', 'myFormsModalxBody', 'myFormsModalxTitle', 'ntnlIDCardsForm', 'ntnlIDCardsRow<?php echo $cntr; ?>', 'Edit National ID', 11, <?php echo $subPgNo; ?>, 5, 'EDIT', <?php echo $row1[0]; ?>);" style="padding:2px !important;">
                                                                                        <!--<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>-->
                                                                                        <img src="cmn_images/edit32.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                                    </button>
                                                                                </td>
                                                                                <td>
                                                                                    <button type="button" class="btn btn-default btn-sm" onclick="deleteNationalID(<?php echo $row1[0]; ?>, '<?php echo $trnsStatus; ?>', <?php echo $subPgNo; ?>, '<?php echo $row1[7]; ?>');" style="padding:2px !important;">
                                                                                        <!--<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>-->
                                                                                        <img src="cmn_images/delete.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                                    </button>
                                                                                </td>
                                                                                <?php } ?>
                                                                                <td>
                                                                                    <?php 
                                                                                        echo dsplyTblData($row1[1], $row1ATZ1, $row1[8], $row1[7]);
                                                                                    ?>
                                                                                </td>
                                                                                <td>
                                                                                    <?php 
                                                                                        echo dsplyTblData($row1[2], $row1ATZ2, $row1[8], $row1[7]);  
                                                                                    ?>
                                                                                </td>
                                                                                <td>
                                                                                    <?php 
                                                                                        echo dsplyTblData($row1[3], $row1ATZ3, $row1[8], $row1[7]); 
                                                                                    ?>
                                                                                </td>
                                                                                <td>
                                                                                    <?php 
                                                                                        echo dsplyTblData($row1[4], $row1ATZ4, $row1[8], $row1[7]);
                                                                                    ?>
                                                                                </td>
                                                                                <td>
                                                                                    <?php 
                                                                                        echo dsplyTblData($row1[5], $row1ATZ5, $row1[8], $row1[7]);
                                                                                    ?>
                                                                                </td>
                                                                                <td>
                                                                                    <?php 
                                                                                        echo dsplyTblData($row1[6], $row1ATZ6, $row1[8], $row1[7]);
                                                                                    ?>
                                                                                </td>
                                                                                <td <?php echo $shwHydNtlntySts; ?>>
                                                                                    <?php 
                                                                                    if($row1[8] < 0){
                                                                                        echo "<span style='color:red;'><b>Deleted</b></span>";
                                                                                    } else  {
                                                                                       if($row1[7] === "No"){
                                                                                            echo "<span style='color:blue;'><b>New</b></span>";
                                                                                       } else {
                                                                                           echo "&nbsp;";
                                                                                       }
                                                                                    }
                                                                                    ?>
                                                                                </td>
                                                                            </tr>
                                                                        <?php } ?>
                                                                    </tbody>
                                                                </table>
                                                            </div> 
                                                        </fieldset>
                                                    </div>
                                                </div>  
                                            </form>
                                        </div>
                                        <div id="prfBCOPAddPrsnDataEDT" class="tab-pane fade" style="border:none !important;"></div>
                                        <div id="prflBCOPAttchmntsEDT" class="tab-pane fade" style="border:none !important;"></div>      
                                    </div>                        
                                </div>                         
                            </div>                
                        </div>          
                    </div>
                    <?php
                }
            } else if ($vwtypActn === "ADD") {
                //$prsnBranchID = get_Person_BranchID($prsnid);
                //$prsnBranch = getGnrlRecNm("org.org_sites_locations", "location_id", "site_desc||' ('||location_code_name||')'","pasn.get_prsn_siteid($prsnid)");
                $trnsStatus = "Incomplete";
                $rqstatusColor = "red";
                
                ?>

                <input class="form-control" id="addOrEditForm" type = "hidden" placeholder="addOrEditForm" value="Add"/>
                <div class="row" style="margin: 0px 0px 10px 0px !important;">
                    <div class="col-md-12" style="padding:0px 0px 0px 0px !important;">
                        <button type="button" class="btn btn-default btn-sm phone-only-btn" onclick="openATab('#allmodules', 'grp=17&typ=1&pg=10&vtyp=0');">Basic Data</button>
                        <button type="button" class="btn btn-default btn-sm phone-only-btn" onclick="openATab('#prfBCOPAddPrsnDataEDT', 'grp=17&typ=1&pg=10&vtyp=1');">Additional Data</button>
                        <!--<button type="button" class="btn btn-default btn-sm phone-only-btn" onclick="openATab('#prflBCOPAttchmntsEDT', 'grp=17&typ=1&pg=10&vtyp=2');">Attachments</button>-->
                    </div>
                </div>
                <div class="">
                    <div class="row">                  
                        <div class="col-md-12">
                            <div class="row" style="margin: 0px 0px 10px 0px !important;">
                                <div class="col-md-12" style="padding:0px 0px 5px 1px !important;">
                                    <div style="float:left;">
                                        <button type="button" class="btn btn-default btn-sm" style="height:30px;" id="myVmsTrnsStatusBtn">
                                                <span style="font-weight:bold;">Status: </span><span style="color:<?php echo $rqstatusColor; ?>;font-weight: bold;"><?php echo $trnsStatus; ?></span>
                                        </button>                                                  
                                    </div>
                                    <div class="" style="float:right;"> 
                                        <button type="button" class="btn btn-default btn-sm" style="" onclick="saveBasicDataOP(<?php echo $pgNo; ?>, <?php echo $subPgNo; ?>, <?php echo $vwtyp; ?>, 0);">
                                            <img src="cmn_images/FloppyDisk.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">
                                            SAVE
                                        </button>
                                    </div>
                                </div>                                          
                            </div>                             
                            <form class="form-horizontal" id="otherPersonsForm">
                                <div class="row">
                                    <div class="col-lg-4">
                                        <fieldset class="basic_person_fs1"><legend class="basic_person_lg">Person's Picture</legend>
                                            <div style="margin-bottom: 10px;">
                                                <img src="cmn_images/no_image.png" alt="..." id="img1Test" class="img-rounded center-block img-responsive" style="height: 195px !important; width: auto !important;">                                            
                                            </div>
                                            <div class="form-group form-group-sm">
                                                <div class="col-md-12">
                                                    <div class="input-group">
                                                        <label class="btn btn-primary btn-file input-group-addon">
                                                            Browse... <input type="file" id="custPicture" name="custPicture" onchange="changeImgSrc(this, '#img1Test', '#img1SrcLoc');" class="btn btn-default"  style="display: none;">
                                                        </label>
                                                        <input type="text" class="form-control" aria-label="..." id="img1SrcLoc" value="">                                                        
                                                    </div>                                                    
                                                </div>                                            
                                            </div>                                        
                                        </fieldset>
                                    </div>                                
                                    <div class="col-lg-4">
                                        <fieldset class="basic_person_fs1"><legend class="basic_person_lg">Names</legend>
                                            <div class="form-group form-group-sm">
                                                <label for="idNo" class="control-label col-md-4">ID No:</label>
                                                <div class="col-md-8">
                                                    <input class="form-control" id="idNo" type = "text" placeholder="ID No" value="" readonly="readonly"/>
                                                    <!--PERSON ID-->
                                                    <input class="form-control" id="personID" type = "hidden" placeholder="Person ID" value=""/>                                                                                                                                         
                                                </div>
                                            </div> 
                                            <div class="form-group form-group-sm">
                                                <label for="title" class="control-label col-md-4">Title:</label>
                                                <div  class="col-md-8">
                                                    <select class="form-control rqrdFld" id="title" >
                                                        <option value="">&nbsp;</option>
                                                        <?php
                                                        $brghtStr = "";
                                                        $isDynmyc = FALSE;
                                                        $titleRslt = getLovValues("%", "Both", 0, 100, $brghtStr,
                                                                getLovID("Person Titles"), $isDynmyc, -1, "", "");
                                                        while ($titleRow = loc_db_fetch_array($titleRslt)) {
                                                            $selectedTxt = "";
                                                            ?>
                                                            <option value="<?php echo $titleRow[0]; ?>" <?php echo $selectedTxt; ?>><?php echo $titleRow[0]; ?></option>
                                                            <?php
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>  
                                            <div class="form-group form-group-sm">
                                                <label for="firstName" class="control-label col-md-4">First Name:</label>
                                                <div  class="col-md-8">
                                                    <input class="form-control rqrdFld" id="firstName" type = "text" placeholder="First Name" value=""/>
                                                </div>
                                            </div> 
                                            <div class="form-group form-group-sm">
                                                <label for="surName" class="control-label col-md-4">Surname:</label>
                                                <div  class="col-md-8">
                                                    <input class="form-control rqrdFld" id="surName" type = "text" placeholder="Surname" value=""/>
                                                </div>
                                            </div>     
                                            <div class="form-group form-group-sm">
                                                <label for="otherNames" class="control-label col-md-4">Other Names:</label>
                                                <div  class="col-md-8">
                                                    <textarea class="form-control" id="otherNames" cols="2" placeholder="Other Names" rows="3"></textarea>
                                                </div>
                                            </div>
                                            <div class="form-group form-group-sm">
                                                <label for="gender" class="control-label col-md-4">Gender:</label>
                                                <div  class="col-md-8">
                                                    <select class="form-control rqrdFld" id="gender" >
                                                        <option value="">&nbsp;</option>
                                                        <?php
                                                        $brghtStr = "";
                                                        $isDynmyc = FALSE;
                                                        $titleRslt = getLovValues("%", "Both", 0, 100, $brghtStr,
                                                                getLovID("Gender"), $isDynmyc, -1, "", "");
                                                        while ($titleRow = loc_db_fetch_array($titleRslt)) {
                                                            $selectedTxt = "";
                                                            ?>
                                                            <option value="<?php echo $titleRow[0]; ?>" <?php echo $selectedTxt; ?>><?php echo $titleRow[0]; ?></option>
                                                            <?php
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div> 
                                        </fieldset>
                                    </div>
                                    <div class="col-lg-4"> 
                                        <fieldset class="basic_person_fs1"><legend class="basic_person_lg">Personal Data</legend>
                                            <div class="form-group form-group-sm">
                                                <label for="maritalStatus" class="control-label col-md-4">Marital Status:</label>
                                                <div  class="col-md-8">
                                                    <select class="form-control rqrdFld" id="maritalStatus" >
                                                        <option value="">&nbsp;</option>
                                                        <?php
                                                        $brghtStr = "";
                                                        $isDynmyc = FALSE;
                                                        $titleRslt = getLovValues("%", "Both", 0, 100, $brghtStr,
                                                                getLovID("Marital Status"), $isDynmyc, -1, "", "");
                                                        while ($titleRow = loc_db_fetch_array($titleRslt)) {
                                                            $selectedTxt = "";
                                                            ?>
                                                            <option value="<?php echo $titleRow[0]; ?>" <?php echo $selectedTxt; ?>><?php echo $titleRow[0]; ?></option>
                                                            <?php
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group form-group-sm">
                                                <label for="dob" class="control-label col-md-4">Date of Birth</label>
                                                <div class="col-md-8">
                                                    <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                                        <input class="form-control rqrdFld" size="16" type="text" id="dob" value="" readonly="">
                                                        <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                    </div>
                                                </div>
                                            </div> 
                                            <div class="form-group form-group-sm">
                                                <label for="pob" class="control-label col-md-4">Place of Birth:</label>
                                                <div  class="col-md-8">
                                                    <textarea class="form-control" id="pob" cols="2" placeholder="Place of Birth" rows="2"></textarea>
                                                </div>
                                            </div> 
                                            <div class="form-group form-group-sm">
                                                <label for="nationality" class="control-label col-md-4">Nationality:</label>
                                                <div class="col-md-8">
                                                    <select class="form-control rqrdFld" id="nationality" >
                                                        <option value="">&nbsp;</option>
                                                        <?php
                                                        $brghtStr = "";
                                                        $isDynmyc = FALSE;
                                                        $titleRslt = getLovValues("%", "Both", 0, 100, $brghtStr,
                                                                getLovID("Nationalities"), $isDynmyc, -1, "", "");
                                                        while ($titleRow = loc_db_fetch_array($titleRslt)) {
                                                            $selectedTxt = "";
                                                            ?>
                                                            <option value="<?php echo $titleRow[0]; ?>" <?php echo $selectedTxt; ?>><?php echo $titleRow[0]; ?></option>
                                                            <?php
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>  
                                            <div class="form-group form-group-sm">
                                                <label for="homeTown" class="control-label col-md-4">Home Town:</label>
                                                <div  class="col-md-8">
                                                    <textarea class="form-control" id="pob" cols="2" placeholder="Home Town" rows="1"></textarea>
                                                </div>
                                            </div> 
                                            <div class="form-group form-group-sm">
                                                <label for="religion" class="control-label col-md-4">Religion:</label>
                                                <div  class="col-md-8">
                                                    <input class="form-control" id="religion" type = "text" placeholder="Religion" value=""/>
                                                </div>
                                            </div>                                              
                                        </fieldset>   
                                    </div>
                                </div>    
                                <div class="row"><!-- ROW 1 -->
                                    <div class="col-lg-4">
                                        <fieldset class="basic_person_fs2"><legend class="basic_person_lg">Signature/Thumbprint</legend>
                                            <div style="margin-bottom: 10px;">
                                                <img src="cmn_images/no_image.png" alt="..." id="img2Test" class="img-rounded center-block img-responsive" style="height: 195px !important; width: auto !important;">                                            
                                            </div>
                                            <div class="form-group form-group-sm">
                                                <div class="col-md-12">
                                                    <div class="input-group">
                                                        <label class="btn btn-primary btn-file input-group-addon">
                                                            Browse... <input type="file" id="signPicture" name="signPicture" onchange="changeImgSrc(this, '#img2Test', '#img2SrcLoc');" class="btn btn-default"  style="display: none;">
                                                        </label>
                                                        <input type="text" class="form-control" aria-label="..." id="img2SrcLoc" value="">                                                        
                                                    </div>                                                    
                                                </div>                                            
                                            </div>                                       
                                        </fieldset>
                                    </div>                                
                                    <div class="col-lg-4">
                                        <fieldset class="basic_person_fs2"><legend class="basic_person_lg">Contact Information</legend>
                                            <div class="form-group form-group-sm">
                                                <label for="linkedFirm" class="control-label col-md-4">Linked Firm/ Workplace</label>
                                                <div  class="col-md-8">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" aria-label="..." id="lnkdFirmName" value="">
                                                        <input type="hidden" id="gnrlOrgID" value="<?php echo $orgID; ?>">
                                                        <input type="hidden" id="lnkdFirmID" value="">
                                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Customers and Suppliers', 'gnrlOrgID', '', '', 'radio', true, '', 'lnkdFirmID', 'lnkdFirmName', 'clear', 1, '');">
                                                            <span class="glyphicon glyphicon-th-list"></span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group form-group-sm">
                                                <label for="branch" class="control-label col-md-4">Site/Branch:</label>
                                                <div  class="col-md-8">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" aria-label="..." id="lnkdFirmLoc" value=""> 
                                                        <input type="hidden" id="lnkdFirmLocID" value="">
                                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Customer/Supplier Sites', 'lnkdFirmID', '', '', 'radio', true, '', 'lnkdFirmLocID', 'lnkdFirmLoc', 'clear', 1, '');">
                                                            <span class="glyphicon glyphicon-th-list"></span>                                                                            
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>  
                                            <div class="form-group form-group-sm">
                                                <label for="email" class="control-label col-md-4">Email:</label>
                                                <div  class="col-md-8">
                                                    <input class="form-control" id="email" type = "email" placeholder="<?php echo $admin_email; ?>" value=""/>
                                                </div>
                                            </div> 
                                            <div class="form-group form-group-sm">
                                                <label for="telephone" class="control-label col-md-4">Contact Nos:</label>
                                                <div  class="col-md-8">
                                                    <input class="form-control" id="telNo" type = "text" placeholder="Telephone" value=""/>
                                                    <input class="form-control rqrdFld" id="mobileNo" type = "text" placeholder="Mobile" value=""/>                                       
                                                </div>
                                            </div>     
                                            <div class="form-group form-group-sm">
                                                <label for="fax" class="control-label col-md-4">Fax:</label>
                                                <div  class="col-md-8">
                                                    <input class="form-control" id="faxNo" type = "text" placeholder="Fax" value=""/>
                                                </div>
                                            </div> 
                                        </fieldset>                                                
                                    </div>
                                    <div class="col-lg-4">
                                        <fieldset class="basic_person_fs2"><legend class="basic_person_lg">Relationship Type</legend>                                    
                                            <div class="form-group form-group-sm" style="display:none !important;">
                                                <label for="relation" class="control-label col-md-4">Person Type:</label>
                                                <div  class="col-md-8">
                                                    <select class="form-control rqrdFld" id="relation" >
                                                        <option value="">&nbsp;</option>
                                                        <?php
                                                        $brghtStr = "";
                                                        $isDynmyc = FALSE;
                                                        $titleRslt = getLovValues("%", "Both", 0, 100, $brghtStr,
                                                                getLovID("MCF Other Person Types"), $isDynmyc, -1, "", "");
                                                        while ($titleRow = loc_db_fetch_array($titleRslt)) {
                                                            $selectedTxt = "";
                                                            if ($otherPrsnType == $titleRow[0]) {
                                                                $selectedTxt = "selected";
                                                            }
                                                            ?>
                                                            <option value="<?php echo $titleRow[0]; ?>" <?php echo $selectedTxt; ?>><?php echo $titleRow[0]; ?></option>
                                                            <?php
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div> 
                                            <div class="form-group form-group-sm" id="meetingDaysDiv">
                                                <label for="relationTyp" class="control-label col-md-4">Person Type:</label>
                                                <div  class="col-md-8">
                                                    <select class="form-control" name="relationTyp[]" id="relationTyp" multiple>
                                                        <!--<option value="">&nbsp;</option>-->
                                                        <?php
                                                        $brghtStr = "";
                                                        $isDynmyc = FALSE;
                                                        $titleRslt = getLovValues("%", "Both", 0, 100, $brghtStr,
                                                                        getLovID("MCF Other Person Types"), $isDynmyc, -1, "", "");
                                                        while ($titleRow = loc_db_fetch_array($titleRslt)) {
                                                                $selectedTxt = "";
                                                                ?>
                                                                <option value="<?php echo $titleRow[0]; ?>" <?php echo $selectedTxt; ?>><?php echo $titleRow[0]; ?></option>
                                                                <?php
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>                                             
                                            <div  class="form-group form-group-sm">
                                                <label for="isSignatory" class="control-label col-md-4">Is Signatory?:</label>
                                                <div  class="col-md-8">
                                                    <?php
                                                    $chkdYes = "";
                                                    $chkdNo = "checked=\"\"";
                                                    ?>
                                                    <label class="radio-inline"><input type="radio" name="isSignatory" value="YES" <?php echo $chkdYes; ?>>YES</label>
                                                    <label class="radio-inline"><input type="radio" name="isSignatory" value="NO" <?php echo $chkdNo; ?>>NO</label>
                                                </div>                                                              
                                            </div>                                                        
                                            <div class="form-group form-group-sm">
                                                <label for="status" class="control-label col-md-4">Status:</label>
                                                <div  class="col-md-8">
                                                    <input class="form-control" id="status" type = "text" placeholder="Status" value="Incomplete" readonly/>
                                                </div>
                                            </div>
                                            <div class="form-group form-group-sm">
                                                <label for="bnkBranch" class="control-label col-md-4">Branch</label>
                                                <div  class="col-md-8">
                                                    <div class="input-group" style="width:100% !important;">
                                                        <input type="text" class="form-control" aria-label="..." id="bnkBranch" value="<?php echo $prsnBranch; ?>" readonly>
                                                        <input type="hidden" id="gnrlOrgID" value="<?php echo $orgID; ?>">
                                                        <input type="hidden" id="bnkBranchID" value="<?php echo $prsnBranchID; ?>">
                                                        <label style="display:none !important;" class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Customers and Suppliers', 'gnrlOrgID', '', '', 'radio', true, '', 'lnkdFirmID', 'lnkdFirmName', 'clear', 1, '');">
                                                            <span class="glyphicon glyphicon-th-list"></span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>                                                         
                                            <div class="form-group form-group-sm">
                                                <label for="startDate" class="control-label col-md-4">Start Date:</label>
                                                <div  class="col-md-8">
                                                    <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input3" data-link-format="yyyy-mm-dd">
                                                        <input class="form-control" size="16" type="text" id="startDate" value="" readonly="">
                                                        <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                        <span class="input-group-addon" onclick="javascript:unfreezeDialog();"><span class="glyphicon glyphicon-info-sign"></span></span>
                                                    </div>
                                                </div>
                                            </div>      
                                            <div class="form-group form-group-sm">
                                                <label for="endDate" class="control-label col-md-4">End Date:</label>
                                                <div  class="col-md-8">
                                                    <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input4" data-link-format="yyyy-mm-dd">
                                                        <input class="form-control" size="16" type="text" id="endDate" value="" readonly="">
                                                        <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                        <span class="input-group-addon" onclick="javascript:unfreezeDialog();"><span class="glyphicon glyphicon-info-sign"></span></span>
                                                    </div>
                                                </div>
                                            </div>  
                                        </fieldset>                                                
                                    </div>
                                </div> 
                                <div class="row"><!-- ROW 3 -->
                                    <div class="col-lg-4">
                                        <fieldset class="basic_person_fs3"><legend class="basic_person_lg">Address</legend> 
                                            <div class="form-group form-group-sm">
                                                <label for="postalAddress" class="control-label col-md-4">Postal Address:</label>
                                                <div  class="col-md-8">
                                                    <textarea class="form-control" id="postalAddress" cols="2" placeholder="Postal Address" rows="4"></textarea>
                                                </div>
                                            </div>
                                            <div class="form-group form-group-sm">
                                                <label for="residentialAddress" class="control-label col-md-4">Residential Address:</label>
                                                <div  class="col-md-8">
                                                    <textarea class="form-control rqrdFld" id="residentialAddress" cols="2" placeholder="Residential Address" rows="4"></textarea>
                                                </div>
                                            </div> 
                                        </fieldset>                                        
                                    </div>
                                    <div class="col-lg-8"> 
                                        <fieldset class="basic_person_fs3"><legend class="basic_person_lg">National ID Cards</legend> 
                                            <div  class="col-md-12">
                                                <table id="nationalIDTblEDT" class="table table-striped table-bordered table-responsive" cellspacing="0" width="100%" style="width:100%;">
                                                    <thead>
                                                        <tr>
                                                            <th>...</th>
                                                            <th>Country</th>
                                                            <th>ID Type</th>
                                                            <th>ID No.</th>
                                                            <th>Date Issued</th>
                                                            <th>Expiry Date</th>
                                                            <th>Other Information</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>

                                                    </tbody>
                                                </table>
                                            </div> 
                                        </fieldset>
                                    </div>
                                </div>  
                            </form>                         
                        </div>                
                    </div>          
                </div>
                <!--  style="min-width: 1000px;left:-35%;"-->
                <?php
            } else if ($vwtypActn == "VIEW") {
                
            }
        } else if ($vwtyp == "1") {
            /* ADDITIONAL PERSON DATA */
        } else if ($vwtyp == "2") {
            /* ATTACHMENTS */
        } else if ($vwtyp == "3") {
            
        } else if ($vwtyp == "4") {
            
        } else if ($vwtyp == "5") {
            /* ADD NATIONAL ID */
            $ntnlIDpKey = isset($_POST['ntnlIDpKey']) ? cleanInputData($_POST['ntnlIDpKey']) : -1;
            $mkReadOnly = "";
            $mkReadOnlyDsbld = "";
            if($vwtypActn == "VIEW"){
                $mkReadOnly = "readonly=\"readonly\"";
                $mkReadOnlyDsbld = "disabled=\"true\""; 
            }
            ?>
            <form class="form-horizontal" id="ntnlIDCardsForm" style="padding:5px 20px 5px 20px;">
                <div class="row">
                    <div class="form-group form-group-sm">
                        <label for="ntnlIDCardsCountry" class="control-label col-md-4">Country:</label>
                        <div class="col-md-8">
                            <input class="form-control" size="16" type="hidden" id="ntnlIDpKey" value="<?php echo $ntnlIDpKey; ?>" readonly="">
                            <select <?php echo $mkReadOnlyDsbld; ?> class="form-control" id="ntnlIDCardsCountry">
                                <option value="" selected disabled>Please Select...</option>
                                <?php
                                $brghtStr = "";
                                $isDynmyc = FALSE;
                                $titleRslt = getLovValues("%", "Both", 0, 100, $brghtStr, getLovID("Countries"), $isDynmyc, -1, "", "");
                                while ($titleRow = loc_db_fetch_array($titleRslt)) {
                                    $selectedTxt = "";
                                    ?>
                                    <option value="<?php echo $titleRow[0]; ?>" <?php echo $selectedTxt; ?>><?php echo $titleRow[0]; ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                    </div> 
                    <div class="form-group form-group-sm">
                        <label for="ntnlIDCardsIDTyp" class="control-label col-md-4">ID Type:</label>
                        <div class="col-md-8">
                            <select <?php echo $mkReadOnlyDsbld; ?> class="form-control selectpicker" id="ntnlIDCardsIDTyp">  
                                <option value="" selected disabled>Please Select...</option>
                                <?php
                                $brghtStr = "";
                                $isDynmyc = FALSE;
                                $titleRslt = getLovValues("%", "Both", 0, 100, $brghtStr, getLovID("National ID Types"), $isDynmyc, -1, "",
                                        "");
                                while ($titleRow = loc_db_fetch_array($titleRslt)) {
                                    $selectedTxt = "";
                                    ?>
                                    <option value="<?php echo $titleRow[0]; ?>" <?php echo $selectedTxt; ?>><?php echo $titleRow[0]; ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group form-group-sm">
                        <label for="ntnlIDCardsIDNo" class="control-label col-md-4">ID No:</label>
                        <div class="col-md-8">
                            <input class="form-control rqrdFld" id="ntnlIDCardsIDNo" type = "text" placeholder="ID No." value=""/>
                        </div>
                    </div>
                    <div class="form-group form-group-sm">
                        <label for="ntnlIDCardsDateIssd" class="control-label col-md-4">Date Issued:</label>
                        <div class="col-md-8">
                            <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                <input class="form-control" size="16" type="text" id="ntnlIDCardsDateIssd" value="" readonly="readonly">
                                <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group form-group-sm">
                        <label for="ntnlIDCardsExpDate" class="control-label col-md-4">Expiry Date:</label>
                        <div class="col-md-8">
                            <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                <input class="form-control" size="16" type="text" id="ntnlIDCardsExpDate" value="" readonly="readonly">
                                <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group form-group-sm">
                        <label for="ntnlIDCardsOtherInfo" class="control-label col-md-4">Other Information:</label>
                        <div class="col-md-8">
                            <textarea <?php echo $mkReadOnly; ?> class="form-control" id="ntnlIDCardsOtherInfo" cols="2" placeholder="Other Information" rows="2"></textarea>
                        </div>
                    </div>
                </div>
                <div class="row" style="float:right;padding-right: 1px;">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <?php if($vwtypActn!="VIEW") { ?>
                        <button type="button" class="btn btn-primary" onclick="saveIndCustNtnlIDForm('myFormsModalx', '<?php echo $ntnlIDpKey; ?>', <?php echo $subPgNo; ?>, '<?php echo $vwtyp; ?>');">Save Changes</button>
                    <?php } ?>
                </div>
            </form>
            <?php
        }
    }
    else if ($subPgNo == 1.5) {//CUSTOMER CORRESPONDENCE
        $pkID =  isset($_POST['PKeyID']) ? cleanInputData($_POST['PKeyID']) : -1;
        $trnsDte =  date('d-M-Y');
        $cstmrCrspndncId = $pkID;
        $trnsctnID = "";
        $requestDate = $trnsDte;
        $branchID = -1;
        $branchNm = "";
        $bnkCustomerID = -1;
        $bnkCustomer = "";
        $acctID = -1;
        $acctNumber = "";
        $custType = ""; 
        $requestType = "";
        $requestTypeID = -1;
        $requestDetails = "";
        $urgency  = "";
        $expectedCloseDate = "";
        $actualCloseDate = "";
        $closedByPersonId = -1;
        $closedByPersonNm = "";
        $closedActionDetails  = "";  
        $requestStatus = "Open";  
        $trnsStatus = "Incomplete";
        $rqstatusColor = "red";        
        $mkReadOnly = "";
        $mkReadOnlyDsbld = "";
        $requestorNm = "";
        $assignedToPrsnID = -1;
        $assignedToPrsnNm = "";

        $result = getCstmrCrspndncDets($pkID);
        while($row = loc_db_fetch_array($result)){
            $bnkCustomerID = $row[1];
            $bnkCustomer = $row[2];
            $custType = $row[3];
            $requestType = $row[4];
            $requestTypeID = $row[4];
            $acctID = $row[5];
            $acctNumber = $row[6];  
            $branchID = $row[7];
            $branchNm = getGnrlRecNm("org.org_sites_locations", "location_id", "site_desc||' ('||location_code_name||')'", $branchID);            
            $requestDetails = $row[8];
            $urgency  = $row[9];
            $requestDate= $row[10];            
            $expectedCloseDate = $row[11];
            $actualCloseDate = $row[12];
            $trnsStatus = $row[13];
            $requestStatus = $row[14];            
            $closedByPersonId = getGnrlRecNm("prs.prsn_names_nos", "person_id", "local_id_no", $row[15]); 
            $closedByPersonNm = $row[16]; 
            $closedActionDetails  = $row[17];
            $trnsctnID = $row[19];            
            $requestorNm = $row[20];
            $assignedToPrsnID = getGnrlRecNm("prs.prsn_names_nos", "person_id", "local_id_no", $row[21]);
            $assignedToPrsnNm = $row[22];

            
            if(($trnsStatus == "Unauthorized") || $vwtypActn == "VIEW"){
                $mkReadOnly = "readonly=\"readonly\"";
                $mkReadOnlyDsbld = "disabled=\"true\"";
            }            
        }


        ?>
        <div class="col-md-12" style="padding:0px 0px 10px 1px !important;">
            <div style="padding:0px 1px 0px 0px !important;float:left;">
                    <button type="button" class="btn btn-default btn-sm" style="height:30px;" id="myVmsTrnsStatusBtn">
                            <input type="text" style="display:none !important;" class="form-control" id="cstmrCrspndncStatus" placeholder="Status" value="<?php echo $trnsStatus; ?>"/>
                            <span style="font-weight:bold;"></span><span style="color:<?php echo $rqstatusColor; ?>;font-weight: bold;"><?php echo $trnsStatus; ?></span>
                    </button>
                    <?php if($vwtypActn != "VIEW") { ?>
                    <button type="button" class="btn btn-default btn-sm" style="height:30px;" onclick="getCstmrCrspndncForm('myFormsModalLg', 'myFormsModalBodyLg', 'myFormsModalTitleLg', 'Edit Customer Correspondence', 11, <?php echo $subPgNo; ?>,0,'EDIT', <?php echo $pkID; ?>,'indCustTable','indCustTableRow1');" data-toggle="tooltip" title="Reload Transaction">
                            <img src="cmn_images/refresh.bmp" style="padding-right: 2px; height:17px; width:auto; position: relative; vertical-align: middle;">
                    </button>
                    <?php } ?>
                    <button type="button" class="btn btn-default" style="height:30px;" onclick="getOneMcfDocsForm_Gnrl(<?php echo $pkID; ?>,'CUSTOMER CORRESPONDENCE', 140, 'Customer Correspondence Form');" data-toggle="tooltip" data-placement="bottom" title = "Attached Documents">
                        <img src="cmn_images/adjunto.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                    </button>                                                    
            </div>
            <div style="padding:0px 1px 0px 1px !important;float:right;">
                    <?php if (($trnsStatus == "Incomplete" || $trnsStatus == "Withdrawn" || $trnsStatus == "Rejected") && $vwtypActn != "VIEW") { ?>                                                    
                            <button type="button" class="btn btn-default btn-sm" style="height:30px;" onclick="saveCstmrCrspndnc(<?php echo $pgNo; ?>, <?php echo $subPgNo; ?>, <?php echo $vwtyp;?>);"><img src="cmn_images/FloppyDisk.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Save&nbsp;</button>
                            <button type="button" class="btn btn-default btn-sm" style="height:30px;" onclick="submitWithdrawCstmrCrspndnc(<?php echo $pgNo; ?>, <?php echo $subPgNo; ?>, 1, 'SUBMIT');"><img src="cmn_images/Emailcon.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Submit&nbsp;</button>   
                    <?php 
                    } else if ($trnsStatus == "Unauthorized" && $vwtypActn != "VIEW") {
                            ?>    
                            <button type="button" class="btn btn-default btn-sm" style="height:30px;" onclick="submitWithdrawCstmrCrspndnc(<?php echo $pgNo; ?>, <?php echo $subPgNo; ?>, 1, 'REJECT');"><img src="cmn_images/withdraw_rqst.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Reject&nbsp;</button>
                            <button type="button" class="btn btn-default btn-sm" style="height:30px;" onclick="submitWithdrawCstmrCrspndnc(<?php echo $pgNo; ?>, <?php echo $subPgNo; ?>, 1, 'WITHDRAW');"><img src="cmn_images/withdraw_rqst.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Withdraw&nbsp;</button>                                      
                            <button type="button" class="btn btn-default btn-sm" style="height:30px;" onclick="submitWithdrawCstmrCrspndnc(<?php echo $pgNo; ?>, <?php echo $subPgNo; ?>, 1, 'AUTHORIZE');"><img src="cmn_images/Stamp-512.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Authorize&nbsp;</button>                                                                                                        
                            <button type="button" class="btn btn-default btn-sm" style="height:30px;" onclick="checkWkfRqstStatus(<?php echo $routingID; ?>, 'Teller Trns. Authorization Progress History');"><img src="cmn_images/workflow.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">&nbsp;</button>                                
                                    <?php
                            } else if (($trnsStatus == "Authorized" && $requestStatus == "Open") && $vwtypActn != "VIEW") {
                                    ?>
                            <button style="display:none !important;" type="button" class="btn btn-default btn-sm" style="height:30px;" onclick="checkWkfRqstStatus(<?php echo $routingID; ?>, 'Teller Trns. Authorization Progress History');"><img src="cmn_images/workflow.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;" data-toggle="tooltip" title="Authorization Progress History">&nbsp;</button>  
                            <button type="button" class="btn btn-default btn-sm" style="height:30px;" onclick="submitWithdrawCstmrCrspndnc(<?php echo $pgNo; ?>, <?php echo $subPgNo; ?>, 1, 'CLOSE');"><img src="cmn_images/90.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">Close&nbsp;</button>                               
                    <?php } ?>
            </div>
        </div>            
        <form class="form-horizontal" id="cstmrCrspndncForm" style="padding:5px 20px 5px 20px;">             
            <div class="row">   
                <div class="col-lg-6">
                    <fieldset class="basic_person_fs5"><legend class="basic_person_lg1">General Information</legend>
                    <input class="form-control" size="16" type="hidden" id="cstmrCrspndncId" value="<?php echo $cstmrCrspndncId; ?>" readonly="">
                    <div class="form-group form-group-sm">
                        <label for="requestDate" class="control-label col-md-4">Account Number:</label>
                        <div  class="col-md-8">
                            <div class="input-group">
                                <input class="form-control rqrdFld" id="acctNoFind" style="font-size: 13px !important;font-weight: bold !important;" placeholder="Enter Account Number" type = "text" min="0" placeholder="" value="<?php echo $acctNumber; ?>"/>
                                <input type="hidden" id="acctNoFindAccId" value="<?php echo $acctID; ?>">
                                <input type="hidden" id="acctNoFindRawTxt" value="<?php echo ''; ?>">
                                <label class="btn btn-primary btn-file input-group-addon" onclick="dsplyAllBankCustsLov();">
                                    <span class="glyphicon glyphicon-th-list"></span>
                                </label>
                                <label class="btn btn-primary btn-file input-group-addon" onclick="getAcctDetails_CustCrspndnce(<?php echo $pgNo; ?>, 1.51)">
                                    <img src="cmn_images/search.png" style="height:17px; width:auto; position: relative; vertical-align: middle;">
                                    FIND
                                </label>
                            </div>
                        </div>
                    </div>   
                        <div class="form-group form-group-sm">
                            <label for="bnkCustomer" class="control-label col-md-4">Customer Name:</label>
                            <div  class="col-md-8">
                                <input type="text" class="form-control" aria-label="..." id="bnkCustomer" value="<?php echo $bnkCustomer; ?>" readonly="readonly">
                                <input type="hidden" id="bnkCustomerID" value="<?php echo $bnkCustomerID; ?>">
                            </div>
                        </div>
                        <div class="form-group form-group-sm">
                            <label for="custType" class="control-label col-md-4">Customer Type:</label>
                            <div  class="col-md-8">
                                <input type="text" class="form-control" aria-label="..." id="custType" value="<?php echo $custType; ?>" readonly>
                            </div>
                        </div>
                        <div class="form-group form-group-sm">
                            <label for="branchNm" class="control-label col-md-4">Branch:</label>
                            <div  class="col-md-8">
                                <input type="text" class="form-control" aria-label="..." id="branchNm" value="<?php echo $branchNm; ?>" readonly>
                                <input type="hidden" id="branchID" value="<?php echo $branchID; ?>">
                            </div>
                        </div>
                        <div class="form-group form-group-sm">
                            <label for="requestDate" class="control-label col-md-4">Request Date:</label>
                            <div  class="col-md-8">
                                <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input3" data-link-format="yyyy-mm-dd">
                                    <input class="form-control" size="16" type="text" id="requestDate" value="<?php echo $requestDate; ?>" readonly="">
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                    <span class="input-group-addon" onclick="javascript:unfreezeDialog();"><span class="glyphicon glyphicon-info-sign"></span></span>
                                </div>
                            </div>
                        </div> 
                        <div class="form-group form-group-sm"> 
                            <label for="requestorNm" class="control-label col-md-4">Requestor:</label>
                            <div  class="col-md-8">
                                <div class="input-group">
                                    <input type="text" <?php echo $mkReadOnly; ?> class="form-control rqrdFld" id="requestorNm" placeholder="Requestor" value="<?php echo $requestorNm; ?>"/>
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'MCF Requestor Types', 'gnrlOrgID', '', '', 'radio', true, '', 'requestorNm', 'requestorNm', 'clear', 1, '');">
                                        <span class="glyphicon glyphicon-th-list"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                </div>
                <div class="col-lg-6">
                    <fieldset class="basic_person_fs5"><legend class="basic_person_lg1">Request Details</legend>                
                    <input type="text" style="display:none !important;" class="form-control" aria-label="..." id="prgrs" value="0" readonly>
                        <div class="form-group form-group-sm">                   
                            <label for="trnsctnID" class="control-label col-md-4">Transaction No.:</label>
                            <div  class="col-md-8">
                                <input type="text" readonly="readonly" class="form-control" id="trnsctnID" placeholder="Transaction No." value="<?php echo $trnsctnID; ?>"/>
                            </div>
                        </div>
                        <div class="form-group form-group-sm">
                            <label for="requestType" class="control-label col-md-4">Request Type:</label>
                            <div  class="col-md-8">
                                <div class="input-group">
                                    <input type="text" class="form-control" aria-label="..." id="requestType" value="<?php echo $requestType; ?>" readonly>
                                    <input type="hidden" id="requestTypeID" value="<?php echo $requestType; ?>"> 
                                    <input type="hidden" id="gnrlOrgID" value="<?php echo $orgID; ?>">
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'MCF Customer Request Types', 'gnrlOrgID', '', '', 'radio', true, '', 'requestTypeID', 'requestType', 'clear', 1, '');">
                                        <span class="glyphicon glyphicon-th-list"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group form-group-sm"> 
                            <label for="requestDetails" class="control-label col-md-4">Request Details:</label>
                            <div  class="col-md-8">
                                <textarea <?php echo $mkReadOnly; ?> class="form-control rqrdFld" id="requestDetails" cols="2" placeholder="Request Details" rows="6"><?php echo $requestDetails; ?></textarea>
                            </div>
                        </div>
                    </fieldset>
                </div>
            </div>
            <div class="row">   
                <div class="col-lg-6">
                    <fieldset class="basic_person_fs5"><legend class="basic_person_lg1">Assignment</legend>
                        <div class="form-group form-group-sm">
                            <label for="assignedToPrsnNm" class="control-label col-md-4">Assigned To:</label>
                            <div  class="col-md-8">
                                <div class="input-group">
                                    <input type="text" class="form-control" aria-label="..." id="assignedToPrsnNm" value="<?php echo $assignedToPrsnNm; ?>" readonly="readonly">
                                    <input type="hidden" id="assignedToPrsnID" value="<?php echo $assignedToPrsnID; ?>">
                                    <input type="hidden" id="gnrlOrgID" value="<?php echo $orgID; ?>">
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Active Persons', 'gnrlOrgID', '', '', 'radio', true, '', 'assignedToPrsnID', 'assignedToPrsnNm', 'clear', 1, '');">
                                        <span class="glyphicon glyphicon-th-list"></span>
                                    </label>
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="javascript:$('#assignedToPrsnID').val(-1);$('#assignedToPrsnNm').val('');">
                                        <span class="glyphicon glyphicon-remove"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group form-group-sm">
                            <label for="urgency" class="control-label col-md-4">Urgency:</label>
                            <div  class="col-md-8">
                                <select <?php echo $mkReadOnlyDsbld; ?> class="form-control" id="urgency" onchange="">
                                    <?php
                                    $sltdCorp = "";
                                    $sltdIndv = "";
                                    $sltdGrp = "";
                                    if ($urgency == "High") {
                                        $sltdCorp = "selected";
                                    } else if ($urgency == "Medium") {
                                        $sltdIndv = "selected";
                                    } else if ($urgency == "Low") {
                                        $sltdGrp = "selected";
                                    }
                                    ?>
                                    <option value="High" <?php echo $sltdCorp; ?>>High</option>
                                    <option value="Medium" <?php echo $sltdIndv; ?>>Medium</option>
                                    <option value="Low" <?php echo $sltdGrp; ?>>Low</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group form-group-sm">
                            <label for="expectedCloseDate" class="control-label col-md-4">Expected Close Date:</label>
                            <div  class="col-md-8">
                                <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input3" data-link-format="yyyy-mm-dd">
                                    <input class="form-control" size="16" type="text" id="expectedCloseDate" value="<?php echo $expectedCloseDate; ?>" readonly="">
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                    <span class="input-group-addon" onclick="javascript:unfreezeDialog();"><span class="glyphicon glyphicon-info-sign"></span></span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group form-group-sm">
                            <label for="requestStatus" class="control-label col-md-4">Request Status:</label>
                            <div  class="col-md-8">
                                <input type="text" style="color:red;font-weight: bold !important;" class="form-control" aria-label="..." id="requestStatus" value="<?php echo $requestStatus; ?>" readonly>
                            </div>
                        </div>
                    </fieldset>
                </div>
                <div class="col-lg-6">
                    <fieldset class="basic_person_fs5"><legend class="basic_person_lg1">Closure Details</legend>
                        <div class="form-group form-group-sm">
                            <label for="actualCloseDate" class="control-label col-md-4">Close Date:</label>
                            <div  class="col-md-8">
                                <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input3" data-link-format="yyyy-mm-dd">
                                    <input class="form-control" size="16" type="text" id="actualCloseDate" value="<?php echo $actualCloseDate; ?>" readonly="">
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                    <span class="input-group-addon" onclick="javascript:unfreezeDialog();"><span class="glyphicon glyphicon-info-sign"></span></span>
                                </div>
                            </div>
                        </div> 
                        <div class="form-group form-group-sm">
                            <label for="closedByPersonNm" class="control-label col-md-4">Actioned By:</label>
                            <div  class="col-md-8">
                                <div class="input-group">
                                    <input type="text" class="form-control" aria-label="..." id="closedByPersonNm" value="<?php echo $closedByPersonNm; ?>" readonly="readonly">
                                    <input type="hidden" id="closedByPersonId" value="<?php echo $closedByPersonId; ?>">
                                    <input type="hidden" id="gnrlOrgID" value="<?php echo $orgID; ?>">
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Active Persons', 'gnrlOrgID', '', '', 'radio', true, '', 'closedByPersonId', 'closedByPersonNm', 'clear', 1, '');;">
                                        <span class="glyphicon glyphicon-th-list"></span>
                                    </label>
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="javascript:$('#closedByPersonId').val(-1);$('#closedByPersonNm').val('');">
                                        <span class="glyphicon glyphicon-remove"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group form-group-sm"> 
                            <label for="closedActionDetails" class="control-label col-md-4">Action Taken:</label>
                            <div  class="col-md-8">
                                <textarea <?php echo $mkReadOnly; ?> class="form-control rqrdFld" id="closedActionDetails" cols="2" placeholder="Action Taken" rows="3"><?php echo $closedActionDetails; ?></textarea>
                            </div>
                        </div>
                    </fieldset>
                </div>
            </div>            
        </form>
        <?php      
    } 
    else if ($subPgNo == 1.51) {

        $acctNo = isset($_POST['PKeyID']) ? cleanInputData($_POST['PKeyID']) : -1;
        $acctDetArray = array();

        //validate account
        $valRslt = validateAccountNo($acctNo);

        if ($valRslt == -1) {
            echo "INVALID ACCOUNT NUMBER";
            exit;
        }

        $result = getAccountDetails($acctNo);

        while ($row = loc_db_fetch_array($result)) {

            $acctDetArray = array('accountID' => $row[0], 'acctNo' => $row[1], 'custNm' => $row[5],
                'custType' => $row[6], 'custID' => $row[14], 'branchID' => $row[8], 'branchNm' => $row[13],
                'acctTitle' => $row[9], 'mandate' => $row[10]);
        }

        $response = array('accountDetails' => $acctDetArray);
        
        echo json_encode($response);
        exit;
    }
    else if ($subPgNo == 1.6){
        //Additional Customer Data Setup
        $formType = isset($_POST['formType']) ? cleanInputData($_POST['formType']) : '';
        $rvsnTtlAPD = isset($_POST['rvsnTtlAPD']) ? cleanInputData($_POST['rvsnTtlAPD']) : '';
        if ($vwtyp == "1") {
            /* ADDITIONAL PERSON DATA */
            $mkReadOnly = "";
            $mkReadOnlyDsbld = "";
            $trnsStatus = "Incomplete";            
            
            if (1 == 1) {//(($canAddPrsn === true && $addOrEdit == "ADD") || ($canEdtPrsn === true && $addOrEdit == "EDIT") || ($canview === true && $addOrEdit == "VIEW")) {
                $dsplyMode = $vwtypActn; //$addOrEdit;
            } else {
                $dsplyMode = "VIEW";
                //$sbmtdPersonID = -1;
            }  

            $dsplyMode = "VIEW";
            if (1 == 1){ //(($canAddPrsn === true && $addOrEdit == "ADD") || ($canEdtPrsn === true && $addOrEdit == "EDIT")) {
                $dsplyMode = $vwtypActn; //$addOrEdit;
            }
            if ($pkID > 0) {
                $result = get_CstmrExtrDataGrps($orgID, $formType);
                ?>               
                <form class="form-horizontal" id="adtnlCstmrDataForm">
                    <input type="text" id="formTypeInpt" value="<?php echo $formType; ?>" style="display:none !important">
                    <?php
                    while ($row = loc_db_fetch_array($result)) {
                        
                        if($formType == "Personal Customer" || $formType == "Corporate Customer" || $formType == "Customer Group"){
                            $trnsStatus = getCustStatus($pkID, $rvsnTtlAPD);
                        } else {
                            $trnsStatus = getPrsnStatus($pkID, $rvsnTtlAPD);
                        }
                        
                        if($vwtypActn == "VIEW" || $trnsStatus == "Initiated" || $trnsStatus == "Approved" || $trnsStatus == "Unauthorized" || $trnsStatus == "Authorized"){
                            $mkReadOnly = "readonly=\"readonly\"";
                            $mkReadOnlyDsbld = "disabled=\"true\"";
                        }                          
                        
                        ?>
                        <div class="row">
                            <div class="col-md-12">
                                <fieldset class="basic_person_fs4">
                                    <legend class="basic_person_lg"><?php echo $row[0]; ?></legend>
                                    <?php
                                    $result1 = get_CstmrExtrDataGrpCols($row[0], $orgID, $formType);
                                    $cntr1 = 0;
                                    $gcntr1 = 0;
                                    $cntr1Ttl = loc_db_num_rows($result1);
                                    while ($row1 = loc_db_fetch_array($result1)) {
                                        /* POSSIBLE FIELDS
                                         * label
                                         * textbox (for now only this)
                                         * textarea (for now only this)
                                         * readonly textbox with button
                                         * readonly textbox with date
                                         * textbox with number validation
                                         */
                                        if ($row1[7] == "Tabular") {
                                            $vrsFieldIDs = "";
                                            for ($i = 0; $i < $row1[9]; $i++) {
                                                if ($i == $row1[9] - 1) {
                                                    $vrsFieldIDs .= "cstmrExtrTblrDtCol_" . $i;
                                                } else {
                                                    $vrsFieldIDs .= "cstmrExtrTblrDtCol_" . $i . "|";
                                                }
                                            }
                                            ?>
                                            <div class="row">
                                                <div  class="col-md-12">
                                                    <?php if($trnsStatus == "Incomplete" || $trnsStatus == "Rejected" || $trnsStatus == "Withdrawn") { ?>
                                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="getCstmrAddtnlDataForm('myFormsModal', 'myFormsModalBody', 'myFormsModalTitle', 'addtnlCstmrTblrDataForm', '', 'Add/Edit Data', 12, 'ADD', -1, '<?php echo $vrsFieldIDs; ?>', <?php echo $row1[1]; ?>, 'extDataTblCol_<?php echo $row1[1]; ?>');">
                                                        <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                        Add Data
                                                    </button>
                                                    <?php } ?>
                                                    <table id="extDataTblCol_<?php echo $row1[1]; ?>" class="table table-striped table-bordered table-responsive extPrsnDataTblEDT"  cellspacing="0" width="100%" style="width:100%;"><thead><th>&nbsp;&nbsp;...</th>
                                                        <?php
                                                        $fieldHdngs = $row1[11];
                                                        $arry1 = explode(",", $fieldHdngs);
                                                        $cntr = count($arry1);
                                                        for ($i = 0; $i < $row1[9]; $i++) {
                                                            if ($i <= $cntr - 1) {
                                                                ?>
                                                                <th><?php echo $arry1[$i]; ?></th>
                                                                <?php
                                                            } else {
                                                                ?>
                                                                <th>&nbsp;</th>
                                                                <?php
                                                            }
                                                        }
                                                        ?>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $fldVal = get_CstmrExtrData($pkID, $formType, $row1[1], $rvsnTtlAPD);

                                                            $arry3 = explode("|", $fldVal);
                                                            $cntr3 = count($arry3);
                                                            $maxsze = (int) 320 / $row1[9];
                                                            if ($maxsze > 100 || $maxsze < 80) {
                                                                $maxsze = 100;
                                                            }
                                                            for ($j = 0; $j < $cntr3; $j++) {
                                                                if (trim(str_replace("~", "", $arry3[$j])) == "") {
                                                                    continue;
                                                                }
                                                                ?>
                                                                <tr id="cstmrExtrTblrDtCol_<?php echo $row1[1]; ?>_Row<?php echo $j; ?>">
                                                                    <td>
                                                                        <button type="button" class="btn btn-default btn-sm" onclick="getCstmrAddtnlDataForm('myFormsModal', 'myFormsModalBody', 'myFormsModalTitle', 'addtnlCstmrTblrDataForm', 'cstmrExtrTblrDtCol_<?php echo $row1[1]; ?>_Row<?php echo $j; ?>', 'Add/Edit Data', 12, 'EDIT', <?php echo $pkID; ?>, '<?php echo $vrsFieldIDs; ?>', <?php echo $row1[1]; ?>, 'extDataTblCol_<?php echo $row1[1]; ?>');" style="padding:2px !important;">
                                                                            <!--<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>-->
                                                                            <img src="cmn_images/edit32.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                        </button>
                                                                    </td>
                                                                    <?php
                                                                    $arry2 = explode("~", $arry3[$j]);
                                                                    $cntr2 = count($arry2);
                                                                    for ($i = 0; $i < $row1[9]; $i++) {
                                                                        if ($i <= $cntr2 - 1) {
                                                                            ?>
                                                                            <td><?php echo $arry2[$i]; ?></td>
                                                                        <?php } else { ?>
                                                                            <td>&nbsp;</td>
                                                                            <?php
                                                                        }
                                                                    }
                                                                    ?>
                                                                </tr>
                                                                <?php
                                                            }
                                                            ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <?php
                                        } else {
                                            if ($gcntr1 == 0) {
                                                $gcntr1 += 1;
                                            }
                                            if (($cntr1 % 2) == 0) {
                                                ?> 
                                                <div class="row"> 
                                                    <?php
                                                }
                                                ?>
                                                <div class="col-md-6"> 
                                                    <div class="form-group form-group-sm"> 
                                                        <?php 
                                                        $prsnDValPulld = get_CstmrExtrData($pkID, $formType, $row1[1], $rvsnTtlAPD);
                                                        
                                                        $tblNm2 = "mcf.mcf_extra_data";

                                                        $cnt = getCustDataChngPndngCount($pkID);
                                                        if($formType === "Other Person"){
                                                            $cnt = getOtherPrsnDataChngPndngCount($pkID);
                                                        }
     
                                                        $prsnDValPulldAuthrzd = get_CstmrExtrDataAuthrzd($pkID, $formType, $row1[1], $rvsnTtlAPD);             
                                                        
                                                        if($cnt > 0 && $prsnDValPulld != $prsnDValPulldAuthrzd) { ?>
                                                        <label data-toggle="tooltip" title="<?php echo $prsnDValPulldAuthrzd; ?>" class="control-label col-md-4"><a href="#" style="color:red;" onclick="dsplyAuthrzdData('<?php echo $prsnDValPulldAuthrzd; ?>');"><?php echo $row1[2]; ?>:</a></label>
                                                        <?php } else { ?>                                                        
                                                        <label class="control-label col-md-4"><?php echo $row1[2]; ?>:</label>
                                                        <?php } ?>
                                                        <div  class="col-md-8">
                                                            <?php
                                                            //$prsnDValPulld = "";
                                                            //$prsnDValPulld = get_CstmrExtrData($pkID, $formType, $row1[1], $rvsnTtlAPD);
                                                            if ($row1[4] == "Date") {
                                                                ?>                                                        
                                                                <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                                                    <input <?php echo $mkReadOnly; ?> class="form-control" size="16" type="text" id="addtnlCstmrDataCol<?php echo $row1[1]; ?>" value="<?php echo $prsnDValPulld; ?>" readonly="">
                                                                    <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                                </div>
                                                                <?php
                                                            } else if ($row1[4] == "Number") {
                                                                ?>
                                                                <input <?php echo $mkReadOnly; ?> class="form-control" id="addtnlCstmrDataCol<?php echo $row1[1]; ?>" type = "text" placeholder="" value="<?php echo $prsnDValPulld; ?>"/>
                                                                <?php
                                                            } else {
                                                                if ($row1[3] == "") {
                                                                    if ($row1[6] < 200) {
                                                                        ?>
                                                                        <input <?php echo $mkReadOnly; ?> class="form-control" id="addtnlCstmrDataCol<?php echo $row1[1]; ?>" type = "text" placeholder="" value="<?php echo $prsnDValPulld; ?>"/>
                                                                        <?php
                                                                    } else {
                                                                        ?>
                                                                        <textarea <?php echo $mkReadOnly; ?> class="form-control" id="addtnlCstmrDataCol<?php echo $row1[1]; ?>" cols="2" placeholder="" rows="2"><?php echo $prsnDValPulld; ?></textarea>
                                                                        <?php
                                                                    }
                                                                } else {
                                                                    if ($row1[6] < 200) {
                                                                        ?>
                                                                        <div class="input-group">
                                                                            <input <?php echo $mkReadOnly; ?> type="text" class="form-control" aria-label="..." id="addtnlCstmrDataCol<?php echo $row1[1]; ?>" value="<?php echo $prsnDValPulld; ?>">  
                                                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', '<?php echo $row1[3]; ?>', '', '', '', 'radio', true, '<?php echo $prsnDValPulld; ?>', 'valueElmntID', 'addtnlCstmrDataCol<?php echo $row1[1]; ?>', 'clear', 1, '');">
                                                                                <span class="glyphicon glyphicon-th-list"></span>                                                                            
                                                                            </label>
                                                                        </div>
                                                                        <?php
                                                                    } else {
                                                                        ?>
                                                                        <div class="input-group">
                                                                            <textarea <?php echo $mkReadOnly; ?> class="form-control" id="addtnlCstmrDataCol<?php echo $row1[1]; ?>" cols="2" placeholder="" rows="2"><?php echo $prsnDValPulld; ?></textarea>
                                                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', '<?php echo $row1[3]; ?>', '', '', '', 'radio', true, '<?php echo $prsnDValPulld; ?>', 'valueElmntID', 'addtnlCstmrDataCol<?php echo $row1[1]; ?>', 'clear', 1, '');">
                                                                                <span class="glyphicon glyphicon-th-list"></span>                                                                            
                                                                            </label>
                                                                        </div>                                                                    
                                                                        <?php
                                                                    }
                                                                }
                                                            }
                                                            ?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php
                                                $cntr1 += 1;
                                                if (($cntr1 % 2) == 0 || $cntr1 == ($cntr1Ttl)) {
                                                    $cntr1 = 0;
                                                    ?>
                                                </div>
                                                <?php
                                            }
                                        }
                                    }
                                    if ($gcntr1 == 1) {
                                        $gcntr1 = 0;
                                    }
                                    ?>
                                </fieldset>
                            </div>
                            <?php ?>
                        </div>
                        <?php
                    }
                }
                ?>
            </form>
            <?php            

        }
        else if ($vwtyp == "12") {
            /* Add Extra Data Form */
            $addtnlCstmrPkey = isset($_POST['addtnlCstmrPkey']) ? cleanInputData($_POST['addtnlCstmrPkey']) : -1;
            $extDtColNum = isset($_POST['extDtColNum']) ? cleanInputData($_POST['extDtColNum']) : -1;
            $pipeSprtdFieldIDs = isset($_POST['pipeSprtdFieldIDs']) ? cleanInputData($_POST['pipeSprtdFieldIDs']) : "";
            $tableElmntID = isset($_POST['tableElmntID']) ? cleanInputData($_POST['tableElmntID']) : "";
            $tRowElementID = isset($_POST['tRowElementID']) ? cleanInputData($_POST['tRowElementID']) : "";
            $addOrEdit = isset($_POST['addOrEdit']) ? cleanInputData($_POST['addOrEdit']) : "";
            $result1 = get_CstmrExtrDataGrpCols1($extDtColNum, $orgID, $formType);
            ?>
            <form class="form-horizontal" id="addtnlCstmrTblrDataForm" style="padding:5px 20px 5px 20px;">
                <div class="row">  
                    <?php
                    while ($row1 = loc_db_fetch_array($result1)) {
                        $fieldHdngs = $row1[11];
                        $arry1 = explode(",", $fieldHdngs);
                        $cntr = count($arry1);
                        for ($i = 0; $i < $row1[9]; $i++) {
                            if ($i <= $cntr - 1) {
                                ?>
                                <div class="form-group form-group-sm">
                                    <label for="cstmrExtrTblrDtCol_<?php echo $i; ?>" class="control-label col-md-4"><?php echo $arry1[$i]; ?>:</label>
                                    <div class="col-md-8">
                                        <input class="form-control" id="cstmrExtrTblrDtCol_<?php echo $i; ?>" type = "text" placeholder="" value=""/>
                                    </div>
                                </div>
                                <?php
                            } else {
                                ?>
                                <div class="form-group form-group-sm">
                                    <label for="cstmrExtrTblrDtCol_<?php echo $i; ?>" class="control-label col-md-4">&nbsp;:</label>
                                    <div class="col-md-8">
                                        <input class="form-control" id="cstmrExtrTblrDtCol_<?php echo $i; ?>" type = "text" placeholder="" value=""/>
                                    </div>
                                </div>
                                <?php
                            }
                        }
                    }
                    ?>
                </div>
                <div class="row" style="float:right;padding-right: 1px;">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="saveCstmrAddtnlDataForm('myFormsModalBody', '<?php echo $addtnlCstmrPkey; ?>', '<?php echo $pipeSprtdFieldIDs; ?>',<?php echo $extDtColNum; ?>, '<?php echo $tableElmntID; ?>', '<?php echo $tRowElementID; ?>', '<?php echo $addOrEdit; ?>');">Save Changes</button>
                </div>
            </form>
            <?php
        } 
    }
}
?>
