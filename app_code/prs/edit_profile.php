<?php
if (array_key_exists('lgn_num', get_defined_vars())) {
    $canAddPrsn = test_prmssns($dfltPrvldgs[7], $mdlNm);
    $canEdtPrsn = test_prmssns($dfltPrvldgs[8], $mdlNm);
    $canDelPrsn = test_prmssns($dfltPrvldgs[9], $mdlNm);
    $canview = test_prmssns($dfltPrvldgs[0], $mdlNm);
    $sbmtdPersonID = isset($_POST['sbmtdPersonID']) ? cleanInputData($_POST['sbmtdPersonID']) : -1;
    $addOrEdit = isset($_POST['addOrEdit']) ? cleanInputData($_POST['addOrEdit']) : 'VIEW';
    $prsnid = $_SESSION['PRSN_ID'];
    $orgID = $_SESSION['ORG_ID'];
    $lnkdFirmID = getGnrlRecNm("prs.prsn_names_nos", "person_id", "lnkd_firm_org_id", $prsnid);
    $lnkdFirmSiteID = getGnrlRecNm("prs.prsn_names_nos", "person_id", "lnkd_firm_site_id", $prsnid);
    if (($canAddPrsn === true && $addOrEdit == "ADD") || ($canEdtPrsn === true && $addOrEdit == "EDIT") || ($canview === true && $addOrEdit == "VIEW")) {
        $dsplyMode = $addOrEdit;
    } else {
        $dsplyMode = "VIEW";
        $sbmtdPersonID = -1;
    }
    if ($vwtyp == "0") {
        /* onclick=\"openATab('#allmodules', 'grp=8&typ=1&pg=$pgNo');\" */
        if ($sbmtdPersonID <= 0 && $addOrEdit == "VIEW") {
            echo $cntent . "<li>
						<span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                                <span style=\"text-decoration:none;\">Data Change Requests</span>
					</li>
                                       </ul>
                                     </div>";
        }
        if ($sbmtdPersonID <= 0) {
            $pkID = $prsnid;
        } else {
            $pkID = $sbmtdPersonID;
        }

        if ($pkID > 0) {
            if ($sbmtdPersonID <= 0) {
                $rcrdExst = prsn_Record_Exist($pkID);
                if ($rcrdExst == true) {
                    $chngRqstExst = prsn_ChngRqst_Exist($pkID);
                    if ($chngRqstExst > 0) {
                        $result = get_SelfPrsnDet($pkID);
                    } else {
                        $result = get_PrsnDet($pkID);
                    }
                } else {
                    $result = get_PrsnDet($pkID);
                }
            } else {
                $result = get_PrsnDet($pkID);
            }

            while ($row = loc_db_fetch_array($result)) {
                $nwFileName = "";
                if ($sbmtdPersonID <= 0) {
                    $nwFileName = $myImgFileName;
                } else {
                    $temp = explode(".", $row[2]);
                    $extension = end($temp);
                    $nwFileName = encrypt1($row[2], $smplTokenWord1) . "." . $extension;
                    $ftp_src = $ftp_base_db_fldr . "/Person/" . $row[2];
                    $fullPemDest = $fldrPrfx . $pemDest . $nwFileName;
                    if (file_exists($ftp_src)) {
                        copy("$ftp_src", "$fullPemDest");
                        //echo $fullPemDest;
                    } else if (!file_exists($fullPemDest)) {
                        $ftp_src = $fldrPrfx . 'cmn_images/image_up.png';
                        copy("$ftp_src", "$fullPemDest");
                        //echo $ftp_src;
                    }
                }
                ?>

                <div class="row" style="margin: 0px 0px 10px 0px !important;">
                    <div class="col-md-7" style="padding:0px 0px 0px 15px !important;">&nbsp;</div>
                    <div class="col-md-5" style="padding:0px 0px 0px 0px">
                        <div class="col-md-5" style="padding:0px 1px 0px 1px !important;"><button type="button" class="btn btn-default btn-sm" style="width:100% !important;"><span style="font-weight:bold;">Status: </span><span style="color:red;font-weight: bold;">Approved</span></button></div>
                        <div class="col-md-3" style="padding:0px 1px 0px 1px !important;"><button type="button" class="btn btn-default btn-sm" style="width:100% !important;"><img src="cmn_images/FloppyDisk.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">SAVE</button></div>
                        <div class="col-md-4" style="padding:0px 1px 0px 1px !important;"><button type="button" class="btn btn-default btn-sm" style="width:100% !important;"><img src="cmn_images/Emailcon.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">SUBMIT</button></div>
                    </div>
                </div>

                <?php if ($addOrEdit != "ADD") {
                    ?>
                    <div class="row" style="margin: 0px 0px 10px 0px !important;">
                        <div class="col-md-12" style="padding:0px 0px 0px 0px !important;">
                            <button type="button" class="btn btn-default btn-sm phone-only-btn" onclick="openATab('#allmodules', 'grp=8&typ=1&pg=2&vtyp=0');">Basic Data</button>
                            <button type="button" class="btn btn-default btn-sm phone-only-btn" onclick="openATab('#prflAddPrsnDataEDT', 'grp=8&typ=1&pg=2&vtyp=1');">Additional Data</button>
                            <button type="button" class="btn btn-default btn-sm phone-only-btn" onclick="openATab('#prflOrgAsgnEDT', 'grp=8&typ=1&pg=2&vtyp=2');">Organisational Assignments</button>
                            <button type="button" class="btn btn-default btn-sm phone-only-btn" onclick="openATab('#prflCVEDT', 'grp=8&typ=1&pg=2&vtyp=3');">Curriculum Vitae</button>
                            <button type="button" class="btn btn-default btn-sm phone-only-btn" onclick="openATab('#prflOthrInfoEDT', 'grp=8&typ=1&pg=2&vtyp=4');">Other Information</button>
                        </div>
                    </div>
                <?php } ?>
                <div class="">
                    <?php if ($addOrEdit != "ADD") {
                        ?>
                        <ul class="nav nav-tabs rho-hideable-tabs" style="margin-top:-10px !important;">
                            <li class="active"><a data-toggle="tab" data-rhodata="&pg=2&vtyp=0&sbmtdPersonID=<?php echo $sbmtdPersonID; ?>" href="#prflHomeEDT" id="prflHomeEDTtab">Basic Data</a></li>
                            <li><a data-toggle="tabajxprfledt" data-rhodata="&pg=2&vtyp=1&sbmtdPersonID=<?php echo $sbmtdPersonID; ?>" href="#prflAddPrsnDataEDT" id="prflAddPrsnDataEDTtab">Additional Data</a></li>
                            <li><a data-toggle="tabajxprfledt" data-rhodata="&pg=2&vtyp=2&sbmtdPersonID=<?php echo $sbmtdPersonID; ?>" href="#prflOrgAsgnEDT" id="prflOrgAsgnEDTtab">Organisational Assignments</a></li>
                            <li><a data-toggle="tabajxprfledt" data-rhodata="&pg=2&vtyp=3&sbmtdPersonID=<?php echo $sbmtdPersonID; ?>" href="#prflCVEDT" id="prflCVEDTtab">CV</a></li>
                            <li><a data-toggle="tabajxprfledt" data-rhodata="&pg=2&vtyp=4&sbmtdPersonID=<?php echo $sbmtdPersonID; ?>" href="#prflOthrInfoEDT" id="prflOthrInfoEDTtab">Other Information</a></li>
                        </ul>
                    <?php } ?>

                    <div class="row">                  
                        <div class="col-md-12">
                            <div class="custDiv"> 
                                <div class="tab-content">
                                    <div id="prflHomeEDT" class="tab-pane fadein active" style="border:none !important;">                          
                                        <form class="form-horizontal" id="bscPrsnPrflForm">
                                            <div class="row">
                                                <div class="col-lg-4">
                                                    <fieldset class="basic_person_fs1"><legend class="basic_person_lg">Person's Picture</legend>
                                                        <div style="margin-bottom: 10px;">
                                                            <img src="<?php echo $pemDest . $nwFileName; ?>" alt="..." id="img1Test" class="img-rounded center-block img-responsive" style="height: 195px !important; width: auto !important;">                                            
                                                        </div>
                                                        <div class="form-group form-group-sm">
                                                            <div class="col-md-12">
                                                                <div class="input-group">
                                                                    <label class="btn btn-primary btn-file input-group-addon">
                                                                        Browse... <input type="file" id="input1Test" name="input1Test" onchange="changeImgSrc(this, '#img1Test', '#img1SrcLoc');" class="btn btn-default"  style="display: none;">
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
                                                                <?php if ($sbmtdPersonID <= 0) { ?>
                                                                    <span><?php echo $row[1]; ?></span>
                                                                <?php } else { ?>
                                                                    <input class="form-control" id="idNumber" type = "text" placeholder="ID No" value="<?php echo $row[1]; ?>"/>
                                                                <?php } ?>
                                                            </div>
                                                        </div> 
                                                        <div class="form-group form-group-sm">
                                                            <label for="title" class="control-label col-md-4">Title:</label>
                                                            <div  class="col-md-8">
                                                                <select class="form-control" id="title" >
                                                                    <?php
                                                                    $brghtStr = "";
                                                                    $isDynmyc = FALSE;
                                                                    $titleRslt = getLovValues("%", "Both", 0, 100, $brghtStr, getLovID("Person Titles"), $isDynmyc, -1, "", "");
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
                                                            <label for="firstName" class="control-label col-md-4">First Name:</label>
                                                            <div  class="col-md-8">
                                                                <input class="form-control" id="firstName" type = "text" placeholder="First Name" value="<?php echo $row[4]; ?>"/>
                                                            </div>
                                                        </div> 
                                                        <div class="form-group form-group-sm">
                                                            <label for="surName" class="control-label col-md-4">Surname:</label>
                                                            <div  class="col-md-8">
                                                                <input class="form-control" id="surName" type = "text" placeholder="Surname" value="<?php echo $row[5]; ?>"/>
                                                            </div>
                                                        </div>     
                                                        <div class="form-group form-group-sm">
                                                            <label for="otherNames" class="control-label col-md-4">Other Names:</label>
                                                            <div  class="col-md-8">
                                                                <textarea class="form-control" id="otherNames" cols="2" placeholder="Other Names" rows="3"><?php echo $row[6]; ?></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-group-sm">
                                                            <label for="gender" class="control-label col-md-4">Gender:</label>
                                                            <div  class="col-md-8">
                                                                <select class="form-control" id="gender" >
                                                                    <?php
                                                                    $brghtStr = "";
                                                                    $isDynmyc = FALSE;
                                                                    $titleRslt = getLovValues("%", "Both", 0, 100, $brghtStr, getLovID("Gender"), $isDynmyc, -1, "", "");
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
                                                            <label for="maritalStatus" class="control-label col-md-4">Marital Status:</label>
                                                            <div  class="col-md-8">
                                                                <select class="form-control" id="maritalStatus" >
                                                                    <?php
                                                                    $brghtStr = "";
                                                                    $isDynmyc = FALSE;
                                                                    $titleRslt = getLovValues("%", "Both", 0, 100, $brghtStr, getLovID("Marital Status"), $isDynmyc, -1, "", "");
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
                                                            <label for="dob" class="control-label col-md-4">Date of Birth</label>
                                                            <div class="col-md-8">
                                                                <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                                                    <input class="form-control" size="16" type="text" id="dob" value="<?php echo $row[10]; ?>" readonly="">
                                                                    <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                                </div>
                                                            </div>
                                                        </div> 
                                                        <div class="form-group form-group-sm">
                                                            <label for="pob" class="control-label col-md-4">Place of Birth:</label>
                                                            <div  class="col-md-8">
                                                                <textarea class="form-control" id="pob" cols="2" placeholder="Place of Birth" rows="2"><?php echo $row[11]; ?></textarea>
                                                            </div>
                                                        </div> 
                                                        <div class="form-group form-group-sm">
                                                            <label for="nationality" class="control-label col-md-4">Nationality:</label>
                                                            <div class="col-md-8">
                                                                <select class="form-control" id="title" >
                                                                    <?php
                                                                    $brghtStr = "";
                                                                    $isDynmyc = FALSE;
                                                                    $titleRslt = getLovValues("%", "Both", 0, 100, $brghtStr, getLovID("Nationalities"), $isDynmyc, -1, "", "");
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
                                                            <label for="homeTown" class="control-label col-md-4">Home Town:</label>
                                                            <div  class="col-md-8">
                                                                <textarea class="form-control" id="pob" cols="2" placeholder="Home Town" rows="1"><?php echo $row[19]; ?></textarea>
                                                            </div>
                                                        </div> 
                                                        <div class="form-group form-group-sm">
                                                            <label for="religion" class="control-label col-md-4">Religion:</label>
                                                            <div  class="col-md-8">
                                                                <input class="form-control" id="religion" type = "text" placeholder="Religion" value="<?php echo $row[12]; ?>"/>
                                                            </div>
                                                        </div>                                              
                                                    </fieldset>   
                                                </div>
                                            </div>    
                                            <div class="row"><!-- ROW 1 -->
                                                <div class="col-lg-4">
                                                    <fieldset class="basic_person_fs2"><legend class="basic_person_lg">QR Code</legend>
                                                        <div>
                                                            <img src="cmn_images/no_image.png" alt="..." id="imgQrCode" class="img-thumbnail center-block img-responsive" style="height: 200px !important; width: auto !important;">                                            
                                                        </div>                                       
                                                    </fieldset>
                                                </div>                                
                                                <div class="col-lg-4">
                                                    <fieldset class="basic_person_fs2"><legend class="basic_person_lg">Contact Information</legend>
                                                        <div class="form-group form-group-sm">
                                                            <label for="linkedFirm" class="control-label col-md-4">Linked Firm/ Workplace</label>
                                                            <div  class="col-md-8">
                                                                <div class="input-group">
                                                                    <input type="text" class="form-control" aria-label="..." id="lnkdFirmName" value="<?php echo $row[21]; ?>">
                                                                    <input type="hidden" id="gnrlOrgID" value="<?php echo $orgID; ?>">
                                                                    <input type="hidden" id="lnkdFirmID" value="<?php echo $row[28]; ?>">
                                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Customers and Suppliers', 'gnrlOrgID', '', '', 'radio', true, '<?php echo $row[21]; ?>', 'lnkdFirmID', 'lnkdFirmName', 'clear', 1, '');">
                                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-group-sm">
                                                            <label for="branch" class="control-label col-md-4">Site/Branch:</label>
                                                            <div  class="col-md-8">
                                                                <div class="input-group">
                                                                    <input type="text" class="form-control" aria-label="..." id="lnkdFirmLoc" value="<?php echo $row[22]; ?>">  
                                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Customer/Supplier Sites', 'lnkdFirmID', '', '', 'radio', true, '<?php echo $row[21]; ?>', 'valueElmntID', 'lnkdFirmLoc', 'clear', 1, '');">
                                                                        <span class="glyphicon glyphicon-th-list"></span>                                                                            
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>  
                                                        <div class="form-group form-group-sm">
                                                            <label for="email" class="control-label col-md-4">Email:</label>
                                                            <div  class="col-md-8">
                                                                <input class="form-control" id="prsEmail" type = "email" placeholder="<?php echo $admin_email; ?>" value="<?php echo $row[15]; ?>"/>
                                                            </div>
                                                        </div> 
                                                        <div class="form-group form-group-sm">
                                                            <label for="telephone" class="control-label col-md-4">Contact Nos:</label>
                                                            <div  class="col-md-8">
                                                                <input class="form-control" id="telNo" type = "text" placeholder="Telephone" value="<?php echo $row[16]; ?>"/>
                                                                <input class="form-control" id="mobileNo" type = "text" placeholder="Mobile" value="<?php echo $row[17]; ?>"/>                                       
                                                            </div>
                                                        </div>     
                                                        <div class="form-group form-group-sm">
                                                            <label for="fax" class="control-label col-md-4">Fax:</label>
                                                            <div  class="col-md-8">
                                                                <input class="form-control" id="faxNo" type = "text" placeholder="Fax" value="<?php echo $row[18]; ?>"/>
                                                            </div>
                                                        </div> 
                                                    </fieldset>                                                
                                                </div>
                                                <div class="col-lg-4">
                                                    <fieldset class="basic_person_fs2"><legend class="basic_person_lg">Relationship Type</legend>                                    
                                                        <div class="form-group form-group-sm">
                                                            <label for="relation" class="control-label col-md-4">Relation:</label>
                                                            <div  class="col-md-8">
                                                                <select class="form-control" id="relation" >
                                                                    <?php
                                                                    $brghtStr = "";
                                                                    $isDynmyc = FALSE;
                                                                    $titleRslt = getLovValues("%", "Both", 0, 100, $brghtStr, getLovID("Person Types"), $isDynmyc, -1, "", "");
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
                                                            <label for="causeOfRelation" class="control-label col-md-4">Cause of Relation:</label>
                                                            <div  class="col-md-8">
                                                                <select class="form-control" id="relationCause" >
                                                                    <?php
                                                                    $brghtStr = "";
                                                                    $isDynmyc = FALSE;
                                                                    $titleRslt = getLovValues("%", "Both", 0, 100, $brghtStr, getLovID("Person Type Change Reasons"), $isDynmyc, -1, "", "");
                                                                    while ($titleRow = loc_db_fetch_array($titleRslt)) {
                                                                        $selectedTxt = "";
                                                                        if ($titleRow[0] == $row[24]) {
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
                                                            <label for="furtherDetails" class="control-label col-md-4">Further Details:</label>
                                                            <div  class="col-md-8">
                                                                <div class="input-group">
                                                                    <textarea class="form-control" aria-label="..." id="relationDetails"><?php echo $row[25]; ?></textarea>
                                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Person Types-Further Details', '', '', '', 'radio', true, '<?php echo $row[25]; ?>', '', 'relationDetails', 'clear', 1, '');">
                                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>  
                                                        <div class="form-group form-group-sm">
                                                            <label for="startDate" class="control-label col-md-4">Start Date:</label>
                                                            <div  class="col-md-8">
                                                                <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input3" data-link-format="yyyy-mm-dd">
                                                                    <input class="form-control" size="16" type="text" id="startDate" value="<?php echo $row[26]; ?>" readonly="">
                                                                    <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                                </div>
                                                            </div>
                                                        </div>      
                                                        <div class="form-group form-group-sm">
                                                            <label for="endDate" class="control-label col-md-4">End Date:</label>
                                                            <div  class="col-md-8">
                                                                <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input4" data-link-format="yyyy-mm-dd">
                                                                    <input class="form-control" size="16" type="text" id="endDate" value="<?php echo $row[27]; ?>" readonly="">
                                                                    <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
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
                                                                <textarea class="form-control" id="pob" cols="2" placeholder="Postal Address" rows="4"><?php echo $row[14]; ?></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-group-sm">
                                                            <label for="residentialAddress" class="control-label col-md-4">Residential Address:</label>
                                                            <div  class="col-md-8">
                                                                <textarea class="form-control" id="pob" cols="2" placeholder="Residential Address" rows="4"><?php echo $row[13]; ?></textarea>
                                                            </div>
                                                        </div> 
                                                    </fieldset>                                        
                                                </div>
                                                <div class="col-lg-8"> 
                                                    <fieldset class="basic_person_fs3" style="padding: 1px !important;"><legend class="basic_person_lg">National ID Cards</legend> 
                                                        <div  class="col-md-12">
                                                            <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="getNtnlIDForm('myFormsModal', 'myFormsModalBody', 'myFormsModalTitle', 'ntnlIDCardsForm', '', 'Add/Edit National ID', 11, 'ADD', -1);">
                                                                <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                Add National ID Card
                                                            </button>
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
                                                                    <?php
                                                                    if ($sbmtdPersonID <= 0) {
                                                                        $rcrdExst = prsn_Record_Exist($pkID);
                                                                        if ($rcrdExst == true) {
                                                                            $chngRqstExst = prsn_ChngRqst_Exist($pkID);
                                                                            if ($chngRqstExst > 0) {
                                                                                $result1 = get_AllNtnlty_Self($pkID);
                                                                            } else {
                                                                                $result1 = get_AllNtnlty($pkID);
                                                                            }
                                                                        } else {
                                                                            $result1 = get_AllNtnlty($pkID);
                                                                        }
                                                                    } else {
                                                                        $result1 = get_AllNtnlty($pkID);
                                                                    }
                                                                    $cntr = 0;
                                                                    while ($row1 = loc_db_fetch_array($result1)) {
                                                                        $cntr++;
                                                                        ?>
                                                                        <tr id="ntnlIDCardsRow<?php echo $cntr; ?>">
                                                                            <td>
                                                                                <button type="button" class="btn btn-default btn-sm" onclick="getNtnlIDForm('myFormsModal', 'myFormsModalBody', 'myFormsModalTitle', 'ntnlIDCardsForm', 'ntnlIDCardsRow<?php echo $cntr; ?>', 'Add/Edit National ID', 11, 'EDIT', <?php echo $row1[0]; ?>);" style="padding:2px !important;">
                                                                                    <!--<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>-->
                                                                                    <img src="cmn_images/edit32.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                                </button>
                                                                            </td>
                                                                            <td>
                                                                                <?php echo $row1[1]; ?>
                                                                            </td>
                                                                            <td>
                                                                                <?php echo $row1[2]; ?>
                                                                            </td>
                                                                            <td><?php echo $row1[3]; ?></td>
                                                                            <td><?php echo $row1[4]; ?></td>
                                                                            <td><?php echo $row1[5]; ?></td>
                                                                            <td><?php echo $row1[6]; ?></td>
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
                                    <div id="prflAddPrsnDataEDT" class="tab-pane fade" style="border:none !important;"></div>
                                    <div id="prflOrgAsgnEDT" class="tab-pane fade" style="border:none !important;"></div>    
                                    <div id="prflCVEDT" class="tab-pane fade" style="border:none !important;"></div>    
                                    <div id="prflOthrInfoEDT" class="tab-pane fade" style="border:none !important;"></div>   
                                </div>                        
                            </div>                         
                        </div>                
                    </div>          
                </div>
                <?php
            }
        }
    } else if ($vwtyp == 1) {
        /* Additional Person Data */
        if ($sbmtdPersonID <= 0) {
            $pkID = $prsnid;
        } else {
            $pkID = $sbmtdPersonID;
        }
        $dsplyMode = "VIEW";
        if (($canAddPrsn === true && $addOrEdit == "ADD") || ($canEdtPrsn === true && $addOrEdit == "EDIT")) {
            $dsplyMode = $addOrEdit;
        }
        if ($pkID > 0) {
            $rcrdExst = prsn_Record_Exist($pkID);
            $result = get_PrsExtrDataGrps($orgID);
            ?>
            <form class="form-horizontal" id="adtnlPrsnDataForm">
                <?php
                while ($row = loc_db_fetch_array($result)) {
                    ?>
                    <div class="row">
                        <div class="col-md-12">
                            <fieldset class="basic_person_fs4">
                                <legend class="basic_person_lg"><?php echo $row[0]; ?></legend>
                                <?php
                                $result1 = get_PrsExtrDataGrpCols($row[0], $orgID);
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
                                                $vrsFieldIDs .= "prsExtrTblrDtCol_" . $i;
                                            } else {
                                                $vrsFieldIDs .= "prsExtrTblrDtCol_" . $i . "|";
                                            }
                                        }
                                        ?>
                                        <div class="row">
                                            <div  class="col-md-12">
                                                <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="getAddtnlDataForm('myFormsModal', 'myFormsModalBody', 'myFormsModalTitle', 'addtnlPrsnTblrDataForm', '', 'Add/Edit Data', 12, 'ADD', -1, '<?php echo $vrsFieldIDs; ?>', <?php echo $row1[1]; ?>, 'extDataTblCol_<?php echo $row1[1]; ?>');">
                                                    <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                                    Add Data
                                                </button>
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
                                                        if ($sbmtdPersonID <= 0) {
                                                            if ($rcrdExst === true) {
                                                                $chngRqstExst = prsn_ChngRqst_Exist($pkID);

                                                                if ($chngRqstExst > 0) {
                                                                    $fldVal = get_PrsExtrData_Self($pkID, $row1[1]);
                                                                } else {
                                                                    $fldVal = get_PrsExtrData($pkID, $row1[1]);
                                                                }
                                                            } else {
                                                                $fldVal = get_PrsExtrData($pkID, $row1[1]);
                                                            }
                                                        } else {
                                                            $fldVal = get_PrsExtrData($pkID, $row1[1]);
                                                        }

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
                                                            <tr id="prsExtrTblrDtCol_<?php echo $row1[1]; ?>_Row<?php echo $j; ?>">
                                                                <td>
                                                                    <button type="button" class="btn btn-default btn-sm" onclick="getAddtnlDataForm('myFormsModal', 'myFormsModalBody', 'myFormsModalTitle', 'addtnlPrsnTblrDataForm', 'prsExtrTblrDtCol_<?php echo $row1[1]; ?>_Row<?php echo $j; ?>', 'Add/Edit Data', 12, 'EDIT', <?php echo $pkID; ?>, '<?php echo $vrsFieldIDs; ?>', <?php echo $row1[1]; ?>, 'extDataTblCol_<?php echo $row1[1]; ?>');" style="padding:2px !important;">
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
                                                    <label class="control-label col-md-4"><?php echo $row1[2]; ?>:</label>
                                                    <div  class="col-md-8">
                                                        <?php
                                                        $prsnDValPulld = "";
                                                        if ($sbmtdPersonID <= 0) {
                                                            if ($rcrdExst == true) {
                                                                $chngRqstExst = prsn_ChngRqst_Exist($pkID);
                                                                if ($chngRqstExst > 0) {
                                                                    $prsnDValPulld = get_PrsExtrData_Self($pkID, $row1[1]);
                                                                } else {
                                                                    $prsnDValPulld = get_PrsExtrData($pkID, $row1[1]);
                                                                }
                                                            } else {
                                                                $prsnDValPulld = get_PrsExtrData($pkID, $row1[1]);
                                                            }
                                                        } else {
                                                            $prsnDValPulld = get_PrsExtrData($pkID, $row1[1]);
                                                        }
                                                        if ($row1[4] == "Date") {
                                                            ?>                                                        
                                                            <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                                                <input class="form-control" size="16" type="text" id="addtnlPrsnDataCol<?php echo $row1[1]; ?>" value="<?php echo $prsnDValPulld; ?>" readonly="">
                                                                <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                            </div>
                                                            <?php
                                                        } else if ($row1[4] == "Number") {
                                                            ?>
                                                            <input class="form-control" id="addtnlPrsnDataCol<?php echo $row1[1]; ?>" type = "text" placeholder="" value="<?php echo $prsnDValPulld; ?>"/>
                                                            <?php
                                                        } else {
                                                            if ($row1[3] == "") {
                                                                if ($row1[6] < 200) {
                                                                    ?>
                                                                    <input class="form-control" id="addtnlPrsnDataCol<?php echo $row1[1]; ?>" type = "text" placeholder="" value="<?php echo $prsnDValPulld; ?>"/>
                                                                    <?php
                                                                } else {
                                                                    ?>
                                                                    <textarea class="form-control" id="addtnlPrsnDataCol<?php echo $row1[1]; ?>" cols="2" placeholder="" rows="2"><?php echo $prsnDValPulld; ?></textarea>
                                                                    <?php
                                                                }
                                                            } else {
                                                                if ($row1[6] < 200) {
                                                                    ?>
                                                                    <div class="input-group">
                                                                        <input type="text" class="form-control" aria-label="..." id="addtnlPrsnDataCol<?php echo $row1[1]; ?>" value="<?php echo $prsnDValPulld; ?>">  
                                                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', '<?php echo $row1[3]; ?>', '', '', '', 'radio', true, '<?php echo $prsnDValPulld; ?>', 'valueElmntID', 'addtnlPrsnDataCol<?php echo $row1[1]; ?>', 'clear', 1, '');">
                                                                            <span class="glyphicon glyphicon-th-list"></span>                                                                            
                                                                        </label>
                                                                    </div>
                                                                    <?php
                                                                } else {
                                                                    ?>
                                                                    <div class="input-group">
                                                                        <textarea class="form-control" id="addtnlPrsnDataCol<?php echo $row1[1]; ?>" cols="2" placeholder="" rows="2"><?php echo $prsnDValPulld; ?></textarea>
                                                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', '<?php echo $row1[3]; ?>', '', '', '', 'radio', true, '<?php echo $prsnDValPulld; ?>', 'valueElmntID', 'addtnlPrsnDataCol<?php echo $row1[1]; ?>', 'clear', 1, '');">
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
    } else if ($vwtyp == "2") {
        /* Org Assignments */
        if ($sbmtdPersonID <= 0) {
            $pkID = $prsnid;
        } else {
            $pkID = $sbmtdPersonID;
        }
        $cntr = 0;
        ?> 
        <div class="row">
            <div class="col-lg-6"> 
                <fieldset class="basic_person_fs4"><legend class="basic_person_lg">DIVISIONS/GROUPS</legend> 
                    <div  class="col-md-12">
                        <table id="divsGroupsEDT" class="table table-striped table-bordered table-responsive orgAsgnmentsTblsEDT" cellspacing="0" width="100%" style="width:100%;">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Group Name</th>
                                    <th>Group Type</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($pkID > 0) {
                                    $result1 = get_DivsGrps($pkID);
                                    while ($row1 = loc_db_fetch_array($result1)) {
                                        $cntr += 1;
                                        ?>
                                        <tr>
                                            <td><?php echo $cntr; ?></td>
                                            <td><?php echo $row1[2]; ?></td>
                                            <td><?php echo $row1[6]; ?></td>
                                            <td><?php echo $row1[3]; ?></td>
                                            <td><?php echo $row1[4]; ?></td>
                                        </tr>
                                        <?php
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                    </div> 
                </fieldset>
            </div>
            <div class="col-lg-6"> 
                <fieldset class="basic_person_fs4"><legend class="basic_person_lg">IMMEDIATE SUPERVISORS</legend> 
                    <div  class="col-md-12">
                        <table id="immdteSprvsrsEDT" class="table table-striped table-bordered table-responsive orgAsgnmentsTblsEDT" cellspacing="0" width="100%" style="width:100%;">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>ID No. of Supervisor</th>
                                    <th>Name of Supervisor</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($pkID > 0) {
                                    $cntr = 0;

                                    $result1 = get_Spvsrs($pkID);
                                    while ($row1 = loc_db_fetch_array($result1)) {
                                        $cntr += 1;
                                        ?>
                                        <tr>
                                            <td><?php echo $cntr; ?></td>
                                            <td><?php echo $row1[2]; ?></td>
                                            <td><?php echo $row1[3]; ?></td>
                                            <td><?php echo $row1[4]; ?></td>
                                            <td><?php echo $row1[5]; ?></td>
                                        </tr>
                                        <?php
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                    </div> 
                </fieldset>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6">
                <fieldset class="basic_person_fs4"><legend class="basic_person_lg">SITES/LOCATIONS</legend> 
                    <div  class="col-md-12">
                        <table id="sitesLocsEDT" class="table table-striped table-bordered table-responsive orgAsgnmentsTblsEDT" cellspacing="0" width="100%" style="width:100%;">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Site/Branch Name</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($pkID > 0) {
                                    $cntr = 0;

                                    $result1 = get_SitesLocs($pkID);
                                    while ($row1 = loc_db_fetch_array($result1)) {
                                        $cntr += 1;
                                        ?>
                                        <tr>
                                            <td><?php echo $cntr; ?></td>
                                            <td><?php echo $row1[2]; ?></td>
                                            <td><?php echo $row1[3]; ?></td>
                                            <td><?php echo $row1[4]; ?></td>
                                        </tr>
                                        <?php
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                    </div> 
                </fieldset>
            </div>
            <div class="col-lg-6">
                <fieldset class="basic_person_fs4"><legend class="basic_person_lg">JOBS</legend> 
                    <div  class="col-md-12">
                        <table id="jobsEDT" class="table table-striped table-bordered table-responsive orgAsgnmentsTblsEDT" cellspacing="0" width="100%" style="width:100%;">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Job Name</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($pkID > 0) {
                                    $cntr = 0;

                                    $result1 = get_Jobs($pkID);
                                    while ($row1 = loc_db_fetch_array($result1)) {
                                        $cntr += 1;
                                        ?>
                                        <tr>
                                            <td><?php echo $cntr; ?></td>
                                            <td><?php echo $row1[2]; ?></td>
                                            <td><?php echo $row1[3]; ?></td>
                                            <td><?php echo $row1[4]; ?></td>
                                        </tr>
                                        <?php
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                    </div> 
                </fieldset>
            </div>                                        
        </div>
        <div class="row">
            <div class="col-lg-6">
                <fieldset class="basic_person_fs4"><legend class="basic_person_lg">GRADES</legend> 
                    <div  class="col-md-12">
                        <table id="gradesEDT" class="table table-striped table-bordered table-responsive orgAsgnmentsTblsEDT" cellspacing="0" width="100%" style="width:100%;">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Grade Name</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($pkID > 0) {
                                    $cntr = 0;

                                    $result1 = get_Grades($pkID);
                                    while ($row1 = loc_db_fetch_array($result1)) {
                                        $cntr += 1;
                                        ?>
                                        <tr>
                                            <td><?php echo $cntr; ?></td>
                                            <td><?php echo $row1[2]; ?></td>
                                            <td><?php echo $row1[3]; ?></td>
                                            <td><?php echo $row1[4]; ?></td>
                                        </tr>
                                        <?php
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                    </div> 
                </fieldset>
            </div>
            <div class="col-lg-6">
                <fieldset class="basic_person_fs4"><legend class="basic_person_lg">POSITIONS</legend> 
                    <div  class="col-md-12">
                        <table id="positionsEDT" class="table table-striped table-bordered table-responsive orgAsgnmentsTblsEDT" cellspacing="0" width="100%" style="width:100%;">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Position Name</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($pkID > 0) {
                                    $cntr = 0;
                                    $result1 = get_Pos($pkID);
                                    while ($row1 = loc_db_fetch_array($result1)) {
                                        $cntr += 1;
                                        ?>
                                        <tr>
                                            <td><?php echo $cntr; ?></td>
                                            <td><?php echo $row1[2]; ?></td>
                                            <td><?php echo $row1[3]; ?></td>
                                            <td><?php echo $row1[4]; ?></td>
                                        </tr>
                                        <?php
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                    </div> 
                </fieldset>
            </div>                                        
        </div>
        <?php
    } else if ($vwtyp == "3") {
        /* Curiculumn Vitae */
        if ($sbmtdPersonID <= 0) {
            $pkID = $prsnid;
        } else {
            $pkID = $sbmtdPersonID;
        }
        $cntr = 0;
        ?> 
        <div class="row">
            <div class="col-md-12"> 
                <fieldset class="basic_person_fs4"><legend class="basic_person_lg">EDUCATIONAL BACKGROUND</legend> 
                    <div  class="col-md-12">
                        <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="getEducBkgrdForm('myFormsModal', 'myFormsModalBody', 'myFormsModalTitle', 'educBkgrdForm', '', 'Add/Edit Educational Background', 20, 'ADD', -1, <?php echo $pkID; ?>);">
                            <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                            Add Educational Background
                        </button>
                        <table class="table table-striped table-bordered table-responsive cvTblsEDT" id="educBkgrdTable" cellspacing="0" width="100%" style="width:100%;">
                            <thead>
                                <tr>
                                    <th>...</th>		
                                    <th>Course Name</th>
                                    <th>School/Institution</th>
                                    <th>School Location</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Certificate Obtained</th>
                                    <th>Certificate Type</th>
                                    <th>Date Awarded</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($pkID > 0) {
                                    $result1 = get_EducBkgrd($pkID);
                                    while ($row1 = loc_db_fetch_array($result1)) {
                                        $cntr += 1;
                                        ?>
                                        <tr id="educBkgrdRow<?php echo $cntr; ?>">
                                            <td>
                                                <button type="button" class="btn btn-default btn-sm" onclick="getEducBkgrdForm('myFormsModal', 'myFormsModalBody', 'myFormsModalTitle', 'educBkgrdForm', 'educBkgrdRow<?php echo $cntr; ?>', 'Add/Edit Educational Background', 20, 'EDIT', <?php echo $row1[0]; ?>, <?php echo $pkID; ?>);" style="padding:2px !important;" style="padding:2px !important;">
                                                    <!--<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>-->
                                                    <img src="cmn_images/edit32.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                </button>
                                            </td>
                                            <td><?php echo $row1[1]; ?></td>
                                            <td><?php echo $row1[2]; ?></td>
                                            <td><?php echo $row1[3]; ?></td>
                                            <td><?php echo $row1[4]; ?></td>
                                            <td><?php echo $row1[5]; ?></td>
                                            <td><?php echo $row1[6]; ?></td>
                                            <td><?php echo $row1[7]; ?></td>
                                            <td><?php echo $row1[8]; ?></td>
                                        </tr>
                                        <?php
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                    </div> 
                </fieldset>
            </div>
            <div class="col-md-12"> 
                <fieldset class="basic_person_fs4"><legend class="basic_person_lg">WORKING EXPERIENCE</legend> 
                    <div  class="col-md-12">                        
                        <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="getWorkBkgrdForm('myFormsModal', 'myFormsModalBody', 'myFormsModalTitle', 'workBkgrdForm', '', 'Add/Edit Work Experience', 21, 'ADD', -1, <?php echo $pkID; ?>);">
                            <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                            Add Work Experience
                        </button>
                        <table class="table table-striped table-bordered table-responsive cvTblsEDT"  id="workBkgrdTable" cellspacing="0" width="100%" style="width:100%;">
                            <thead>
                                <tr>
                                    <th>...</th>		
                                    <th>Job Name/Title</th>
                                    <th>Institution Name</th>
                                    <th>Job Location</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Job Description</th>
                                    <th>Feats/Achievements</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($pkID > 0) {
                                    $result1 = get_WrkBkgrd($pkID);
                                    while ($row1 = loc_db_fetch_array($result1)) {
                                        $cntr += 1;
                                        ?>
                                        <tr id="workBkgrdRow<?php echo $cntr; ?>">
                                            <td>
                                                <button type="button" class="btn btn-default btn-sm" onclick="getWorkBkgrdForm('myFormsModal', 'myFormsModalBody', 'myFormsModalTitle', 'workBkgrdForm', 'workBkgrdRow<?php echo $cntr; ?>', 'Add/Edit Work Experience', 21, 'EDIT', <?php echo $row1[0]; ?>, <?php echo $pkID; ?>);" style="padding:2px !important;" style="padding:2px !important;">
                                                    <!--<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>-->
                                                    <img src="cmn_images/edit32.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                </button>
                                            </td>
                                            <td><?php echo $row1[1]; ?></td>
                                            <td><?php echo $row1[2]; ?></td>
                                            <td><?php echo $row1[3]; ?></td>
                                            <td><?php echo $row1[4]; ?></td>
                                            <td><?php echo $row1[5]; ?></td>
                                            <td><?php echo $row1[6]; ?></td>
                                            <td><?php echo $row1[7]; ?></td>
                                        </tr>
                                        <?php
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                    </div> 
                </fieldset>
            </div>
            <div class="col-md-12"> 
                <fieldset class="basic_person_fs4"><legend class="basic_person_lg">SKILLS/NATURE</legend> 
                    <div  class="col-md-12">                                                
                        <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="getSkillsForm('myFormsModal', 'myFormsModalBody', 'myFormsModalTitle', 'skillsForm', '', 'Add/Edit Skills/Nature', 22, 'ADD', -1, <?php echo $pkID; ?>);">
                            <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                            Add Skills/Nature
                        </button>
                        <table class="table table-striped table-bordered table-responsive cvTblsEDT" id="skillsTable" cellspacing="0" width="100%" style="width:100%;">
                            <thead>
                                <tr>
                                    <th>...</th>		
                                    <th>Languages</th>
                                    <th>Hobbies</th>
                                    <th>Interests</th>
                                    <th>Conduct</th>
                                    <th>Attitude</th>
                                    <th>From Date</th>
                                    <th>To Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($pkID > 0) {
                                    $result1 = get_SkillNature($pkID);
                                    while ($row1 = loc_db_fetch_array($result1)) {
                                        $cntr += 1;
                                        ?>
                                        <tr id="skillsTblRow<?php echo $cntr; ?>">
                                            <td>
                                                <button type="button" class="btn btn-default btn-sm" onclick="getSkillsForm('myFormsModal', 'myFormsModalBody', 'myFormsModalTitle', 'skillsForm', 'skillsTblRow<?php echo $cntr; ?>', 'Add/Edit Skills/Nature', 22, 'EDIT', <?php echo $row1[0]; ?>, <?php echo $pkID; ?>);" style="padding:2px !important;" style="padding:2px !important;">
                                                    <!--<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>-->
                                                    <img src="cmn_images/edit32.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                </button>
                                            </td>
                                            <td><?php echo $row1[1]; ?></td>
                                            <td><?php echo $row1[2]; ?></td>
                                            <td><?php echo $row1[3]; ?></td>
                                            <td><?php echo $row1[4]; ?></td>
                                            <td><?php echo $row1[5]; ?></td>
                                            <td><?php echo $row1[6]; ?></td>
                                            <td><?php echo $row1[7]; ?></td>
                                        </tr>
                                        <?php
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                    </div> 
                </fieldset>
            </div>
        </div>
        <?php
    } else if ($vwtyp == "4") {
        /* Other Information */
        if ($sbmtdPersonID <= 0) {
            $pkID = $prsnid;
        } else {
            $pkID = $sbmtdPersonID;
        }
        $cntr = 0;
        $table_id = getMdlGrpID("Person Data", $mdlNm);
        $ext_inf_tbl_name = "prs.prsn_all_other_info_table";
        $ext_inf_seq_name = "prs.prsn_all_other_info_table_dflt_row_id_seq";
        $row_pk_id = $pkID;
        ?>
        <div class="row">
            <div  class="col-md-12">
                <form class="form-horizontal" id="OtherInfoTblForm">
                    <table class="table table-striped table-bordered table-responsive otherInfoTblsEDT" cellspacing="0" width="100%" style="width:100%;">
                        <thead>
                            <tr>
                                <!--<th>No.</th>-->
                                <th>Category</th>
                                <th>Label</th>
                                <th>Value</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($pkID > 0) {
                                $brghtsqlStr = "";
                                $result1 = getAllwdExtInfosNVals("%", "Extra Info Label", 0, 1000000000, $brghtsqlStr, $table_id, $row_pk_id, $ext_inf_tbl_name, $orgID);
                                while ($row1 = loc_db_fetch_array($result1)) {
                                    $cntr += 1;
                                    ?>
                                    <tr>
                                        <!--<td><?php echo $cntr; ?></td>-->
                                        <td><?php echo $row1[0]; ?></td>
                                        <td><?php echo $row1[1]; ?></td>
                                        <td><input class="form-control" id="otherInfoTblRow_<?php echo $cntr; ?>" type = "text" placeholder="" value="<?php echo $row1[2]; ?>"/></td>
                                    </tr>
                                    <?php
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </form>
            </div>
        </div>
        <?php
    } else if ($vwtyp == "11") {
        /* Add National ID Form */
        $ntnlIDpKey = isset($_POST['ntnlIDpKey']) ? cleanInputData($_POST['ntnlIDpKey']) : -1;
        ?>
        <form class="form-horizontal" id="ntnlIDCardsForm" style="padding:5px 20px 5px 20px;">
            <div class="row">
                <div class="form-group form-group-sm">
                    <label for="ntnlIDCardsCountry" class="control-label col-md-4">Country:</label>
                    <div class="col-md-8">
                        <input class="form-control" size="16" type="hidden" id="ntnlIDCardsDateIssd" value="<?php echo $ntnlIDpKey; ?>" readonly="">
                        <select class="form-control" id="ntnlIDCardsCountry">
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
                        <select class="form-control selectpicker" id="ntnlIDCardsIDTyp">  
                            <option value="" selected disabled>Please Select...</option>
                            <?php
                            $brghtStr = "";
                            $isDynmyc = FALSE;
                            $titleRslt = getLovValues("%", "Both", 0, 100, $brghtStr, getLovID("National ID Types"), $isDynmyc, -1, "", "");
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
                        <input class="form-control" id="ntnlIDCardsIDNo" type = "text" placeholder="ID No." value=""/>
                    </div>
                </div>
                <div class="form-group form-group-sm">
                    <label for="ntnlIDCardsDateIssd" class="control-label col-md-4">Date Issued:</label>
                    <div class="col-md-8">
                        <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                            <input class="form-control" size="16" type="text" id="ntnlIDCardsDateIssd" value="" readonly="">
                            <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                        </div>
                    </div>
                </div>
                <div class="form-group form-group-sm">
                    <label for="ntnlIDCardsExpDate" class="control-label col-md-4">Expiry Date:</label>
                    <div class="col-md-8">
                        <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                            <input class="form-control" size="16" type="text" id="ntnlIDCardsExpDate" value="" readonly="">
                            <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                        </div>
                    </div>
                </div>
                <div class="form-group form-group-sm">
                    <label for="ntnlIDCardsOtherInfo" class="control-label col-md-4">Other Information:</label>
                    <div class="col-md-8">
                        <textarea class="form-control" id="ntnlIDCardsOtherInfo" cols="2" placeholder="Other Information" rows="2"></textarea>
                    </div>
                </div>
            </div>
            <div class="row" style="float:right;padding-right: 1px;">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="saveNtnlIDForm('myFormsModal', '<?php echo $ntnlIDpKey; ?>');">Save Changes</button>
            </div>
        </form>
        <?php
    } else if ($vwtyp == "12") {
        /* Add Extra Data Form */
        $addtnlPrsPkey = isset($_POST['addtnlPrsPkey']) ? cleanInputData($_POST['addtnlPrsPkey']) : -1;
        $extDtColNum = isset($_POST['extDtColNum']) ? cleanInputData($_POST['extDtColNum']) : -1;
        $pipeSprtdFieldIDs = isset($_POST['pipeSprtdFieldIDs']) ? cleanInputData($_POST['pipeSprtdFieldIDs']) : "";
        $tableElmntID = isset($_POST['tableElmntID']) ? cleanInputData($_POST['tableElmntID']) : "";
        $tRowElementID = isset($_POST['tRowElementID']) ? cleanInputData($_POST['tRowElementID']) : "";
        $addOrEdit = isset($_POST['addOrEdit']) ? cleanInputData($_POST['addOrEdit']) : "";
        $result1 = get_PrsExtrDataGrpCols1($extDtColNum, $orgID);
        ?>
        <form class="form-horizontal" id="addtnlPrsnTblrDataForm" style="padding:5px 20px 5px 20px;">
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
                                <label for="prsExtrTblrDtCol_<?php echo $i; ?>" class="control-label col-md-4"><?php echo $arry1[$i]; ?>:</label>
                                <div class="col-md-8">
                                    <input class="form-control" id="prsExtrTblrDtCol_<?php echo $i; ?>" type = "text" placeholder="" value=""/>
                                </div>
                            </div>
                            <?php
                        } else {
                            ?>
                            <div class="form-group form-group-sm">
                                <label for="prsExtrTblrDtCol_<?php echo $i; ?>" class="control-label col-md-4">&nbsp;:</label>
                                <div class="col-md-8">
                                    <input class="form-control" id="prsExtrTblrDtCol_<?php echo $i; ?>" type = "text" placeholder="" value=""/>
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
                <button type="button" class="btn btn-primary" onclick="saveAddtnlDataForm('myFormsModalBody', '<?php echo $addtnlPrsPkey; ?>', '<?php echo $pipeSprtdFieldIDs; ?>',<?php echo $extDtColNum; ?>, '<?php echo $tableElmntID; ?>', '<?php echo $tRowElementID; ?>', '<?php echo $addOrEdit; ?>');">Save Changes</button>
            </div>
        </form>
        <?php
    } else if ($vwtyp == "13") {
        /* Add Divisions/Groups Form */
    } else if ($vwtyp == "14") {
        /* Add Sites/Locations Form */
    } else if ($vwtyp == "15") {
        /* Add Grades Form */
    } else if ($vwtyp == "16") {
        /* Add Supervisors Form */
    } else if ($vwtyp == "17") {
        /* Add Jobs Form */
    } else if ($vwtyp == "18") {
        /* Add Positions Form */
    } else if ($vwtyp == "20") {
        /* Add Educational Background Form */
        $educBkgrdPkeyID = isset($_POST['educBkgrdPkeyID']) ? cleanInputData($_POST['educBkgrdPkeyID']) : -1;
        $sbmtdPersonID = isset($_POST['sbmtdPersonID']) ? cleanInputData($_POST['sbmtdPersonID']) : -1;
        ?>
        <form class="form-horizontal" id="educBkgrdForm" style="padding:5px 20px 5px 20px;">
            <div class="row">
                <div class="form-group form-group-sm">
                    <label for="educBkgrdCourseName" class="control-label col-md-4">Course Name:</label>
                    <div  class="col-md-8">
                        <div class="input-group">
                            <input type="text" class="form-control" aria-label="..." id="educBkgrdCourseName" value="">
                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'CV Courses', '', '', '', 'radio', true, '', 'educBkgrdCourseName', '', 'clear', 1, '');">
                                <span class="glyphicon glyphicon-th-list"></span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group form-group-sm">
                    <label for="educBkgrdSchool" class="control-label col-md-4">School / Institution:</label>
                    <div  class="col-md-8">
                        <div class="input-group">
                            <input type="text" class="form-control" aria-label="..." id="educBkgrdSchool" value="">
                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Schools/Organisations/Institutions', '', '', '', 'radio', true, '', 'educBkgrdSchool', '', 'clear', 1, '');">
                                <span class="glyphicon glyphicon-th-list"></span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group form-group-sm">
                    <label for="educBkgrdLoc" class="control-label col-md-4">Location:</label>
                    <div  class="col-md-8">
                        <div class="input-group">
                            <input type="text" class="form-control" aria-label="..." id="educBkgrdLoc" value="">
                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Other Locations', '', '', '', 'radio', true, '', 'educBkgrdLoc', '', 'clear', 1, '');">
                                <span class="glyphicon glyphicon-th-list"></span>
                            </label>
                        </div>
                    </div>
                </div>                
                <div class="form-group form-group-sm">
                    <label for="educBkgrdStartDate" class="control-label col-md-4">Start Date:</label>
                    <div class="col-md-8">
                        <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                            <input class="form-control" size="16" type="text" id="educBkgrdStartDate" value="" readonly="">
                            <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                        </div>
                    </div>
                </div>
                <div class="form-group form-group-sm">
                    <label for="educBkgrdEndDate" class="control-label col-md-4">End Date:</label>
                    <div class="col-md-8">
                        <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                            <input class="form-control" size="16" type="text" id="educBkgrdEndDate" value="" readonly="">
                            <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                        </div>
                    </div>
                </div>                
                <div class="form-group form-group-sm">
                    <label for="educBkgrdCertObtnd" class="control-label col-md-4">Certificate Obtained:</label>
                    <div class="col-md-8">
                        <select class="form-control selectpicker" id="educBkgrdCertObtnd">  
                            <option value="" selected disabled>Please Select...</option>
                            <?php
                            $brghtStr = "";
                            $isDynmyc = FALSE;
                            $titleRslt = getLovValues("%", "Both", 0, 100, $brghtStr, getLovID("Certificate Names"), $isDynmyc, -1, "", "");
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
                    <label for="educBkgrdCertTyp" class="control-label col-md-4">Certificate Type:</label>
                    <div class="col-md-8">
                        <select class="form-control selectpicker" id="educBkgrdCertTyp">  
                            <option value="" selected disabled>Please Select...</option>
                            <?php
                            $brghtStr = "";
                            $isDynmyc = FALSE;
                            $titleRslt = getLovValues("%", "Both", 0, 100, $brghtStr, getLovID("Qualification Types"), $isDynmyc, -1, "", "");
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
                    <label for="educBkgrdDateAwrded" class="control-label col-md-4">Date Awarded:</label>
                    <div class="col-md-8">
                        <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                            <input class="form-control" size="16" type="text" id="educBkgrdDateAwrded" value="" readonly="">
                            <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row" style="float:right;padding-right: 1px;">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="saveEducBkgrdForm('myFormsModal', '<?php echo $educBkgrdPkeyID; ?>',<?php echo $sbmtdPersonID; ?>, 'educBkgrdTable');">Save Changes</button>
            </div>
        </form>
        <?php
    } else if ($vwtyp == "21") {
        /* Add Work Experience Form */
        $workBkgrdPkeyID = isset($_POST['workBkgrdPkeyID']) ? cleanInputData($_POST['workBkgrdPkeyID']) : -1;
        $sbmtdPersonID = isset($_POST['sbmtdPersonID']) ? cleanInputData($_POST['sbmtdPersonID']) : -1;
        ?>
        <form class="form-horizontal" id="workBkgrdForm" style="padding:5px 20px 5px 20px;">
            <div class="row">
                <div class="form-group form-group-sm">
                    <label for="workBkgrdJobName" class="control-label col-md-4">Job Name / Title:</label>
                    <div  class="col-md-8">
                        <div class="input-group">
                            <input type="text" class="form-control" aria-label="..." id="workBkgrdJobName" value="">
                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Jobs/Professions/Occupations', '', '', '', 'radio', true, '', 'workBkgrdJobName', '', 'clear', 1, '');">
                                <span class="glyphicon glyphicon-th-list"></span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group form-group-sm">
                    <label for="workBkgrdInstitution" class="control-label col-md-4">Institution:</label>
                    <div  class="col-md-8">
                        <div class="input-group">
                            <input type="text" class="form-control" aria-label="..." id="workBkgrdInstitution" value="">
                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Schools/Organisations/Institutions', '', '', '', 'radio', true, '', 'workBkgrdInstitution', '', 'clear', 1, '');">
                                <span class="glyphicon glyphicon-th-list"></span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group form-group-sm">
                    <label for="workBkgrdLoc" class="control-label col-md-4">Location:</label>
                    <div  class="col-md-8">
                        <div class="input-group">
                            <input type="text" class="form-control" aria-label="..." id="workBkgrdLoc" value="">
                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Other Locations', '', '', '', 'radio', true, '', 'workBkgrdLoc', '', 'clear', 1, '');">
                                <span class="glyphicon glyphicon-th-list"></span>
                            </label>
                        </div>
                    </div>
                </div>                
                <div class="form-group form-group-sm">
                    <label for="workBkgrdStartDate" class="control-label col-md-4">Start Date:</label>
                    <div class="col-md-8">
                        <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                            <input class="form-control" size="16" type="text" id="workBkgrdStartDate" value="" readonly="">
                            <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                        </div>
                    </div>
                </div>
                <div class="form-group form-group-sm">
                    <label for="workBkgrdEndDate" class="control-label col-md-4">End Date:</label>
                    <div class="col-md-8">
                        <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                            <input class="form-control" size="16" type="text" id="workBkgrdEndDate" value="" readonly="">
                            <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                        </div>
                    </div>
                </div>                
                <div class="form-group form-group-sm">
                    <label for="workBkgrdJobDesc" class="control-label col-md-4">Job Description:</label>
                    <div class="col-md-8">
                        <textarea class="form-control" id="workBkgrdJobDesc" cols="2" rows="2" placeholder="Other Information" rows="2"></textarea>
                    </div>
                </div>                
                <div class="form-group form-group-sm">
                    <label for="workBkgrdAchvmnts" class="control-label col-md-4">Feats / Achievements:</label>
                    <div class="col-md-8">
                        <textarea class="form-control" id="workBkgrdAchvmnts" cols="2" rows="4" placeholder="Other Information" rows="2"></textarea>
                    </div>
                </div>
            </div>
            <div class="row" style="float:right;padding-right: 1px;">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="saveWorkBkgrdForm('myFormsModal', '<?php echo $workBkgrdPkeyID; ?>',<?php echo $sbmtdPersonID; ?>, 'workBkgrdTable');">Save Changes</button>
            </div>
        </form>
        <?php
    } else if ($vwtyp == "22") {
        /* Add Work Experience Form */
        $skillsPkeyID = isset($_POST['skillsPkeyID']) ? cleanInputData($_POST['skillsPkeyID']) : -1;
        $sbmtdPersonID = isset($_POST['sbmtdPersonID']) ? cleanInputData($_POST['sbmtdPersonID']) : -1;
        ?>
        <form class="form-horizontal" id="skillsForm" style="padding:5px 20px 5px 20px;">
            <div class="row">
                <div class="form-group form-group-sm">
                    <label for="skillsLanguages" class="control-label col-md-4">Languages:</label>
                    <div  class="col-md-8">
                        <div class="input-group">
                            <input type="text" class="form-control" aria-label="..." id="skillsLanguages" value="">
                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Languages', '', '', '', 'check', true, '', 'skillsLanguages', '', 'clear', 1, '');">
                                <span class="glyphicon glyphicon-th-list"></span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group form-group-sm">
                    <label for="skillsHobbies" class="control-label col-md-4">Hobbies:</label>
                    <div  class="col-md-8">
                        <div class="input-group">
                            <input type="text" class="form-control" aria-label="..." id="skillsHobbies" value="">
                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Hobbies', '', '', '', 'check', true, '', 'skillsHobbies', '', 'clear', 1, '');">
                                <span class="glyphicon glyphicon-th-list"></span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group form-group-sm">
                    <label for="skillsInterests" class="control-label col-md-4">Interests:</label>
                    <div  class="col-md-8">
                        <div class="input-group">
                            <input type="text" class="form-control" aria-label="..." id="skillsInterests" value="">
                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Interests', '', '', '', 'check', true, '', 'skillsInterests', '', 'clear', 1, '');">
                                <span class="glyphicon glyphicon-th-list"></span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group form-group-sm">
                    <label for="skillsConduct" class="control-label col-md-4">Conduct:</label>
                    <div  class="col-md-8">
                        <div class="input-group">
                            <input type="text" class="form-control" aria-label="..." id="skillsConduct" value="">
                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Conduct', '', '', '', 'check', true, '', 'skillsConduct', '', 'clear', 1, '');">
                                <span class="glyphicon glyphicon-th-list"></span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group form-group-sm">
                    <label for="skillsAttitudes" class="control-label col-md-4">Attitudes:</label>
                    <div  class="col-md-8">
                        <div class="input-group">
                            <input type="text" class="form-control" aria-label="..." id="skillsAttitudes" value="">
                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Attitudes', '', '', '', 'check', true, '', 'skillsAttitudes', '', 'clear', 1, '');">
                                <span class="glyphicon glyphicon-th-list"></span>
                            </label>
                        </div>
                    </div>
                </div>                
                <div class="form-group form-group-sm">
                    <label for="skillsStartDate" class="control-label col-md-4">Start Date:</label>
                    <div class="col-md-8">
                        <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                            <input class="form-control" size="16" type="text" id="skillsStartDate" value="" readonly="">
                            <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                        </div>
                    </div>
                </div>
                <div class="form-group form-group-sm">
                    <label for="skillsEndDate" class="control-label col-md-4">End Date:</label>
                    <div class="col-md-8">
                        <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                            <input class="form-control" size="16" type="text" id="skillsEndDate" value="" readonly="">
                            <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row" style="float:right;padding-right: 1px;">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="saveSkillsForm('myFormsModal', '<?php echo $skillsPkeyID; ?>',<?php echo $sbmtdPersonID; ?>, 'skillsTable');">Save Changes</button>
            </div>
        </form>
        <?php
    } else if ($vwtyp == 23) {
        /* Add Basic Person Form */
        ?>   
        <div class = "row">
            <div class = "col-md-12">
                <!--<div class = "custDiv"></div>  -->
                <form class = "form-horizontal" id="bscPrsnPrflForm">
                    <div class = "row">
                        <div class = "col-lg-4">
                            <fieldset class = "basic_person_fs1"><legend class = "basic_person_lg">Person's Picture</legend>
                                <div style="margin-bottom: 10px;">
                                    <img src="cmn_images/image_up.png" alt="..." id="img1Test" class="img-rounded center-block img-responsive" style="height: 195px !important; width: auto !important;">                                            
                                </div>
                                <div class="form-group form-group-sm">
                                    <div class="col-md-12">
                                        <div class="input-group">
                                            <label class="btn btn-primary btn-file input-group-addon">
                                                Browse... <input type="file" id="input1Test" name="input1Test" onchange="changeImgSrc(this, '#img1Test', '#img1SrcLoc');" class="btn btn-default"  style="display: none;">
                                            </label>
                                            <input type = "text" class = "form-control" aria-label = "..." id = "img1SrcLoc" value = "">
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                        <div class = "col-lg-4">
                            <fieldset class = "basic_person_fs1"><legend class = "basic_person_lg">Names</legend>
                                <div class = "form-group form-group-sm">
                                    <label for = "idNo" class = "control-label col-md-4">ID No:</label>
                                    <div class = "col-md-8">
                                        <input class="form-control" id="idNumber" type = "text" placeholder="ID No" value=""/>
                                    </div>
                                </div> 
                                <div class="form-group form-group-sm">
                                    <label for="title" class="control-label col-md-4">Title:</label>
                                    <div  class="col-md-8">
                                        <select class="form-control" id="title" >
                                            <option value="">&nbsp;</option>
                                            <?php
                                            $brghtStr = "";
                                            $isDynmyc = FALSE;
                                            $titleRslt = getLovValues("%", "Both", 0, 100, $brghtStr, getLovID("Person Titles"), $isDynmyc, -1, "", "");
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
                                        <input class="form-control" id="firstName" type = "text" placeholder="First Name" value=""/>
                                    </div>
                                </div> 
                                <div class="form-group form-group-sm">
                                    <label for="surName" class="control-label col-md-4">Surname:</label>
                                    <div  class="col-md-8">
                                        <input class="form-control" id="surName" type = "text" placeholder="Surname" value=""/>
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
                                        <select class="form-control" id="gender" >
                                            <option value="">&nbsp;</option>
                                            <?php
                                            $brghtStr = "";
                                            $isDynmyc = FALSE;
                                            $titleRslt = getLovValues("%", "Both", 0, 100, $brghtStr, getLovID("Gender"), $isDynmyc, -1, "", "");
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
                                        <select class="form-control" id="maritalStatus" >
                                            <option value="">&nbsp;</option>
                                            <?php
                                            $brghtStr = "";
                                            $isDynmyc = FALSE;
                                            $titleRslt = getLovValues("%", "Both", 0, 100, $brghtStr, getLovID("Marital Status"), $isDynmyc, -1, "", "");
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
                                            <input class="form-control" size="16" type="text" id="dob" value="" readonly="">
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
                                        <select class="form-control" id="title" >
                                            <option value="">&nbsp;</option>
                                            <?php
                                            $brghtStr = "";
                                            $isDynmyc = FALSE;
                                            $titleRslt = getLovValues("%", "Both", 0, 100, $brghtStr, getLovID("Nationalities"), $isDynmyc, -1, "", "");
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
                            <fieldset class="basic_person_fs2"><legend class="basic_person_lg">Address</legend> 
                                <div class="form-group form-group-sm">
                                    <label for="postalAddress" class="control-label col-md-4">Postal Address:</label>
                                    <div  class="col-md-8">
                                        <textarea class="form-control" id="pob" cols="2" placeholder="Postal Address" rows="5"></textarea>
                                    </div>
                                </div>
                                <div class="form-group form-group-sm">
                                    <label for="residentialAddress" class="control-label col-md-4">Residential Address:</label>
                                    <div  class="col-md-8">
                                        <textarea class="form-control" id="pob" cols="2" placeholder="Residential Address" rows="5"></textarea>
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
                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Customer/Supplier Sites', 'lnkdFirmID', '', '', 'radio', true, '', 'valueElmntID', 'lnkdFirmLoc', 'clear', 1, '');">
                                                <span class="glyphicon glyphicon-th-list"></span>                                                                            
                                            </label>
                                        </div>
                                    </div>
                                </div>  
                                <div class="form-group form-group-sm">
                                    <label for="email" class="control-label col-md-4">Email:</label>
                                    <div  class="col-md-8">
                                        <input class="form-control" id="prsEmail" type = "email" placeholder="<?php echo $admin_email; ?>" value=""/>
                                    </div>
                                </div> 
                                <div class="form-group form-group-sm">
                                    <label for="telephone" class="control-label col-md-4">Contact Nos:</label>
                                    <div  class="col-md-8">
                                        <input class="form-control" id="telNo" type = "text" placeholder="Telephone" value=""/>
                                        <input class="form-control" id="mobileNo" type = "text" placeholder="Mobile" value=""/>                                       
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
                                        <select class="form-control" id="relation" >
                                            <option value="">&nbsp;</option>
                                            <?php
                                            $brghtStr = "";
                                            $isDynmyc = FALSE;
                                            $titleRslt = getLovValues("%", "Both", 0, 100, $brghtStr, getLovID("Person Types"), $isDynmyc, -1, "", "");
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
                                    <label for="causeOfRelation" class="control-label col-md-4">Cause of Relation:</label>
                                    <div  class="col-md-8">
                                        <select class="form-control" id="relationCause" >
                                            <option value="">&nbsp;</option>
                                            <?php
                                            $brghtStr = "";
                                            $isDynmyc = FALSE;
                                            $titleRslt = getLovValues("%", "Both", 0, 100, $brghtStr, getLovID("Person Type Change Reasons"), $isDynmyc, -1, "", "");
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
                                    <label for="furtherDetails" class="control-label col-md-4">Further Details:</label>
                                    <div  class="col-md-8">
                                        <div class="input-group">
                                            <textarea class="form-control" aria-label="..." id="relationDetails"></textarea>
                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Person Types-Further Details', '', '', '', 'radio', true, '', '', 'relationDetails', 'clear', 1, '');">
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
                                        </div>
                                    </div>
                                </div>  
                            </fieldset>                                                
                        </div>
                    </div> 
                </form>
            </div>                
        </div> 
        <?php
    } else if ($vwtyp == 24) {
        /* Add Basic Person Form for My Institution */
        
        $lnkdFirmName = getGnrlRecNm("scm.scm_cstmr_suplr", "cust_sup_id", "cust_sup_name", $lnkdFirmID);
        $lnkdFirmSiteName = getGnrlRecNm("scm.scm_cstmr_suplr_sites", "cust_sup_site_id", "site_name", $lnkdFirmSiteID);
        ?>   
        <div class = "row">
            <div class = "col-md-12">
                <!--<div class = "custDiv"></div>  -->
                <form class = "form-horizontal" id="bscPrsnPrflForm">
                    <div class = "row">
                        <div class = "col-lg-4">
                            <fieldset class = "basic_person_fs1"><legend class = "basic_person_lg">Person's Picture</legend>
                                <div style="margin-bottom: 10px;">
                                    <img src="cmn_images/image_up.png" alt="..." id="img1Test" class="img-rounded center-block img-responsive" style="height: 195px !important; width: auto !important;">                                            
                                </div>
                                <div class="form-group form-group-sm">
                                    <div class="col-md-12">
                                        <div class="input-group">
                                            <label class="btn btn-primary btn-file input-group-addon">
                                                Browse... <input type="file" id="input1Test" name="input1Test" onchange="changeImgSrc(this, '#img1Test', '#img1SrcLoc');" class="btn btn-default"  style="display: none;">
                                            </label>
                                            <input type = "text" class = "form-control" aria-label = "..." id = "img1SrcLoc" value = "">
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                        <div class = "col-lg-4">
                            <fieldset class = "basic_person_fs1"><legend class = "basic_person_lg">Names</legend>
                                <div class = "form-group form-group-sm">
                                    <label for = "idNo" class = "control-label col-md-4">ID No:</label>
                                    <div class = "col-md-8">
                                        <input class="form-control" id="idNumber" type = "text" placeholder="ID No" value=""/>
                                    </div>
                                </div> 
                                <div class="form-group form-group-sm">
                                    <label for="title" class="control-label col-md-4">Title:</label>
                                    <div  class="col-md-8">
                                        <select class="form-control" id="title" >
                                            <option value="">&nbsp;</option>
                                            <?php
                                            $brghtStr = "";
                                            $isDynmyc = FALSE;
                                            $titleRslt = getLovValues("%", "Both", 0, 100, $brghtStr, getLovID("Person Titles"), $isDynmyc, -1, "", "");
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
                                        <input class="form-control" id="firstName" type = "text" placeholder="First Name" value=""/>
                                    </div>
                                </div> 
                                <div class="form-group form-group-sm">
                                    <label for="surName" class="control-label col-md-4">Surname:</label>
                                    <div  class="col-md-8">
                                        <input class="form-control" id="surName" type = "text" placeholder="Surname" value=""/>
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
                                        <select class="form-control" id="gender" >
                                            <option value="">&nbsp;</option>
                                            <?php
                                            $brghtStr = "";
                                            $isDynmyc = FALSE;
                                            $titleRslt = getLovValues("%", "Both", 0, 100, $brghtStr, getLovID("Gender"), $isDynmyc, -1, "", "");
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
                                        <select class="form-control" id="maritalStatus" >
                                            <option value="">&nbsp;</option>
                                            <?php
                                            $brghtStr = "";
                                            $isDynmyc = FALSE;
                                            $titleRslt = getLovValues("%", "Both", 0, 100, $brghtStr, getLovID("Marital Status"), $isDynmyc, -1, "", "");
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
                                            <input class="form-control" size="16" type="text" id="dob" value="" readonly="">
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
                                        <select class="form-control" id="title" >
                                            <option value="">&nbsp;</option>
                                            <?php
                                            $brghtStr = "";
                                            $isDynmyc = FALSE;
                                            $titleRslt = getLovValues("%", "Both", 0, 100, $brghtStr, getLovID("Nationalities"), $isDynmyc, -1, "", "");
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
                            <fieldset class="basic_person_fs2"><legend class="basic_person_lg">Address</legend> 
                                <div class="form-group form-group-sm">
                                    <label for="postalAddress" class="control-label col-md-4">Postal Address:</label>
                                    <div  class="col-md-8">
                                        <textarea class="form-control" id="pob" cols="2" placeholder="Postal Address" rows="5"></textarea>
                                    </div>
                                </div>
                                <div class="form-group form-group-sm">
                                    <label for="residentialAddress" class="control-label col-md-4">Residential Address:</label>
                                    <div  class="col-md-8">
                                        <textarea class="form-control" id="pob" cols="2" placeholder="Residential Address" rows="5"></textarea>
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
                                            <input type="text" class="form-control" aria-label="..." id="lnkdFirmName" value="<?php echo $lnkdFirmName; ?>" readonly="">
                                            <input type="hidden" id="gnrlOrgID" value="<?php echo $orgID; ?>">
                                            <input type="hidden" id="lnkdFirmID" value="<?php echo $lnkdFirmID; ?>">
                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Customers and Suppliers', 'gnrlOrgID', '', '', 'radio', true, '<?php echo $lnkdFirmID; ?>', 'lnkdFirmID', 'lnkdFirmName', 'clear', 1, ' and tbl1.a=\'<?php echo $lnkdFirmID; ?>\'');">
                                                <span class="glyphicon glyphicon-th-list"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group form-group-sm">
                                    <label for="branch" class="control-label col-md-4">Site/Branch:</label>
                                    <div  class="col-md-8">
                                        <div class="input-group">
                                            <input type="text" class="form-control" aria-label="..." id="lnkdFirmLoc" value="<?php echo $lnkdFirmSiteName; ?>">  
                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Customer/Supplier Sites', 'lnkdFirmID', '', '', 'radio', true, '<?php echo $lnkdFirmSiteID; ?>', 'valueElmntID', 'lnkdFirmLoc', 'clear', 1, '');">
                                                <span class="glyphicon glyphicon-th-list"></span>                                                                            
                                            </label>
                                        </div>
                                    </div>
                                </div>  
                                <div class="form-group form-group-sm">
                                    <label for="email" class="control-label col-md-4">Email:</label>
                                    <div  class="col-md-8">
                                        <input class="form-control" id="prsEmail" type = "email" placeholder="<?php echo $admin_email; ?>" value=""/>
                                    </div>
                                </div> 
                                <div class="form-group form-group-sm">
                                    <label for="telephone" class="control-label col-md-4">Contact Nos:</label>
                                    <div  class="col-md-8">
                                        <input class="form-control" id="telNo" type = "text" placeholder="Telephone" value=""/>
                                        <input class="form-control" id="mobileNo" type = "text" placeholder="Mobile" value=""/>                                       
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
                                        <select class="form-control" id="relation" >
                                            <option value="">&nbsp;</option>
                                            <?php
                                            $brghtStr = "";
                                            $isDynmyc = FALSE;
                                            $titleRslt = getLovValues("%", "Both", 0, 100, $brghtStr, getLovID("Person Types"), $isDynmyc, -1, "", "");
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
                                    <label for="causeOfRelation" class="control-label col-md-4">Cause of Relation:</label>
                                    <div  class="col-md-8">
                                        <select class="form-control" id="relationCause" >
                                            <option value="">&nbsp;</option>
                                            <?php
                                            $brghtStr = "";
                                            $isDynmyc = FALSE;
                                            $titleRslt = getLovValues("%", "Both", 0, 100, $brghtStr, getLovID("Person Type Change Reasons"), $isDynmyc, -1, "", "");
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
                                    <label for="furtherDetails" class="control-label col-md-4">Further Details:</label>
                                    <div  class="col-md-8">
                                        <div class="input-group">
                                            <textarea class="form-control" aria-label="..." id="relationDetails"></textarea>
                                            <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Person Types-Further Details', '', '', '', 'radio', true, '', '', 'relationDetails', 'clear', 1, '');">
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
                                        </div>
                                    </div>
                                </div>  
                            </fieldset>                                                
                        </div>
                    </div> 
                </form>
            </div>                
        </div> 
        <?php
    }
}
?>
