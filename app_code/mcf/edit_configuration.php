<?php
if (array_key_exists('lgn_num', get_defined_vars())) {
    $prsnid = $_SESSION['PRSN_ID'];
    $orgID = $_SESSION['ORG_ID'];
    $lnkdFirmID = getGnrlRecNm("prs.prsn_names_nos", "person_id", "lnkd_firm_org_id", $prsnid);
    $pkID = $custID;

    if ($subPgNo == 1.1) {//INDIVIDUAL CUSTOMER
        if ($vwtyp == "0") {
            /* BASIC DATA */
            if ($vwtypActn == "EDIT") {
                /* Read Only */
                $result = get_IndCustDet($pkID);
                while ($row = loc_db_fetch_array($result)) {
                    ?>
                    <div class="row" style="margin: 0px 0px 10px 0px !important;">
                        <div class="col-md-3" style="padding:0px 0px 0px 15px !important;">&nbsp;</div>                        
                        <div class="col-md-9" style="padding:0px 0px 0px 0px">
                            <div class="col-md-6" style="padding:0px 1px 0px 1px !important;"><button type="button" class="btn btn-default btn-sm" style="width:100% !important;"><span style="font-weight:bold;">Status: </span><span style="color:red;font-weight: bold;">Approved</span></button></div>
                            <div class="btn-group col-md-2" style="padding:0px 1px 0px 1px !important;">
                                <button class="btn btn-default btn-sm" onclick="getPersonsForm('myFormsModal', 'myFormsModalBody', 'myFormsModalTitle', 'ntnlIDCardsForm', '', 'NEW PERSON', 0, 'ADD', -1);"><img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:18px; width:auto; position: relative; vertical-align: middle;">
                                    New Person
                                </button>
                                <button class="btn btn-default btn-primary dropdown-toggle btn-sm" type="button" data-toggle="dropdown">
                                    <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu" role="menu" style="margin-left: 15px !important;">
                                    <li><a href="#" onclick="getCustomersForm('myFormsModal', 'myFormsModalBody', 'myFormsModalTitle', 'ntnlIDCardsForm', '', 'NEW CUSTOMER', 0, 'ADD', -1);"><img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                            New Customer</a>
                                    </li>
                                    <li><a href="#"><img src="cmn_images/staffs.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                            Manage Persons</a>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-2" style="padding:0px 1px 0px 1px !important;"><button type="button" class="btn btn-default btn-sm" style="width:100% !important;"><img src="cmn_images/FloppyDisk.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">SAVE</button></div>
                            <div class="col-md-2" style="padding:0px 1px 0px 1px !important;"><button type="button" class="btn btn-default btn-sm" style="width:100% !important;"><img src="cmn_images/Emailcon.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">SUBMIT</button></div>
                        </div>
                    </div>
                    <div class="row" style="margin: 0px 0px 10px 0px !important;">
                        <div class="col-md-12" style="padding:0px 0px 0px 0px !important;">
                            <button type="button" class="btn btn-default btn-sm phone-only-btn" onclick="openATab('#allmodules', 'grp=17&typ=1&pg=10&vtyp=0');">Basic Data</button>
                            <button type="button" class="btn btn-default btn-sm phone-only-btn" onclick="openATab('#prflAddPrsnDataEDT', 'grp=17&typ=1&pg=10&vtyp=1');">Additional Data</button>
                            <button type="button" class="btn btn-default btn-sm phone-only-btn" onclick="openATab('#prflOrgAsgnEDT', 'grp=17&typ=1&pg=10&vtyp=2');">Attachments</button>
                        </div>
                    </div>
                    <div class="">
                        <ul class="nav nav-tabs rho-hideable-tabs" style="margin-top:-10px !important;">
                            <li class="active"><a data-toggle="tab" data-rhodata="&pg=10&vtyp=0" href="#prflHomeEDT" id="prflHomeEDTtab">Basic Data</a></li>
                            <li><a data-toggle="tabajxprfledt" data-rhodata="&pg=10&vtyp=1" href="#prflAddPrsnDataEDT" onclick="openATab('#prflAddPrsnDataEDT', 'grp=17&typ=1&pg=10&vtyp=1');" id="prflAddPrsnDataEDTtab">Additional Data</a></li>
                            <li><a data-toggle="tabajxprfledt" data-rhodata="&pg=10&vtyp=2" href="#prflOrgAsgnEDT" onclick="openATab('#prflOrgAsgnEDT', 'grp=17&typ=1&pg=10&vtyp=2');" id="prflOrgAsgnEDTtab">Attachments</a></li>
                        </ul>
                        <div class="row">                  
                            <div class="col-md-12">
                                <div class="custDiv"> 
                                    <div class="tab-content">
                                        <div id="prflHomeEDT" class="tab-pane fadein active" style="border:none !important;">                          
                                            <form class="form-horizontal">
                                                <div class="row">
                                                    <div class="col-lg-4">
                                                        <fieldset class="basic_person_fs1"><legend class="basic_person_lg">Person's Picture</legend>
                                                            <div style="margin-bottom: 10px;">
                                                                <img src="<?php echo $pemDest . $myImgFileName; ?>" alt="..." id="img1Test" class="img-rounded center-block img-responsive" style="height: 195px !important; width: auto !important;">                                            
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
                                                                    <span><?php echo $row[1]; ?></span>
                                                                </div>
                                                            </div> 
                                                            <div class="form-group form-group-sm">
                                                                <label for="title" class="control-label col-md-4">Title:</label>
                                                                <div  class="col-md-8">
                                                                    <select class="form-control" id="title" >
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
                                                                <label for="maritalStatus" class="control-label col-md-4">Marital Status:</label>
                                                                <div  class="col-md-8">
                                                                    <select class="form-control" id="maritalStatus" >
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
                                                        <fieldset class="basic_person_fs2"><legend class="basic_person_lg">Signature/Thumbprint</legend>
                                                            <div style="margin-bottom: 10px;">
                                                                <img src="<?php echo $pemDest . $myImgFileName; ?>" alt="..." id="img1Test" class="img-rounded center-block img-responsive" style="height: 195px !important; width: auto !important;">                                            
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
                                                        <fieldset class="basic_person_fs2"><legend class="basic_person_lg">Contact Information</legend>
                                                            <div class="form-group form-group-sm">
                                                                <label for="linkedFirm" class="control-label col-md-4">Linked Firm/ Workplace</label>
                                                                <div  class="col-md-8">
                                                                    <div class="input-group">
                                                                        <input type="text" class="form-control" aria-label="..." id="lnkdFirmName" value="<?php echo $row[21]; ?>">
                                                                        <input type="hidden" id="gnrlOrgID" value="<?php echo $orgID; ?>">
                                                                        <input type="hidden" id="lnkdFirmID" value="<?php echo $row[28]; ?>">
                                                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Business/Trade Partners', 'gnrlOrgID', '', '', 'radio', true, '<?php echo $row[21]; ?>', 'lnkdFirmID', 'lnkdFirmName', 'clear', 1, '');">
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
                                                                        $titleRslt = getLovValues("%", "Both", 0, 100, $brghtStr,
                                                                                getLovID("Person Types"), $isDynmyc, -1, "", "");
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
                                                                        $titleRslt = getLovValues("%", "Both", 0, 100, $brghtStr,
                                                                                getLovID("Person Type Change Reasons"), $isDynmyc, -1, "",
                                                                                "");
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
                                                                <label for="furtherDetails" class="control-label col-md-4">Branch:</label>
                                                                <div  class="col-md-8">
                                                                    <div class="input-group">
                                                                        <textarea class="form-control" aria-label="..." id="relationDetails"><?php echo $row[25]; ?></textarea>
                                                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Person Types-Branch', '', '', '', 'radio', true, '<?php echo $row[25]; ?>', '', 'relationDetails', 'clear', 1, '');">
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
                                                                <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="getIndCustNtnlIDForm('myLovModal', 'myLovModalBody', 'myLovModalTitle', 'ntnlIDCardsForm', '', 'Edit Customer Profile', 11, <?php echo $subPgNo; ?>, 5, '', -1);">
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
                                                                        $result1 = get_PersonAllNtnlty($pkID, "Customer");
                                                                        $cntr = 0;
                                                                        while ($row1 = loc_db_fetch_array($result1)) {
                                                                            $cntr++;
                                                                            ?>
                                                                            <tr id="ntnlIDCardsRow<?php echo $cntr; ?>">
                                                                                <td>
                                                                                    <button type="button" class="btn btn-default btn-sm" onclick="getIndCustNtnlIDForm('myLovModal', 'myLovModalBody', 'myLovModalTitle', 'ntnlIDCardsForm', 'ntnlIDCardsRow<?php echo $cntr; ?>', 'Edit National ID', 11, <?php echo $subPgNo; ?>, 5, 'EDIT', <?php echo $row[0]; ?>);" style="padding:2px !important;">
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
                                    </div>                        
                                </div>                         
                            </div>                
                        </div>          
                    </div>
                    <?php
                }
            } else if ($vwtypActn === "ADD") {
                /* Add */
                ?>
                <div class="row" style="margin: 0px 0px 10px 0px !important;">
                    <div class="col-md-3" style="padding:0px 0px 0px 15px !important;">&nbsp;</div>                        
                    <div class="col-md-9" style="padding:0px 0px 0px 0px">
                        <div class="col-md-6" style="padding:0px 1px 0px 1px !important;"><button type="button" class="btn btn-default btn-sm" style="width:100% !important;"><span style="font-weight:bold;">Status: </span><span style="color:red;font-weight: bold;">Approved</span></button></div>
                        <div class="btn-group col-md-2" style="padding:0px 1px 0px 1px !important;">
                            <button class="btn btn-default btn-sm" onclick="getPersonsForm('myFormsModal', 'myFormsModalBody', 'myFormsModalTitle', 'ntnlIDCardsForm', '', 'NEW PERSON', 0, 'ADD', -1);"><img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:18px; width:auto; position: relative; vertical-align: middle;">
                                New Person</button>
                            <button class="btn btn-default btn-primary dropdown-toggle btn-sm" type="button" data-toggle="dropdown">
                                <span class="caret"></span></button>
                            <ul class="dropdown-menu" role="menu" style="margin-left: 15px !important;">
                                <li><a href="#" onclick="getCustomersForm('myFormsModal', 'myFormsModalBody', 'myFormsModalTitle', 'ntnlIDCardsForm', '', 'NEW CUSTOMER', 0, 'ADD', -1);"><img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                        New Customer</a></li>
                                <li><a href="#"><img src="cmn_images/staffs.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                        Manage Persons</a></li>
                            </ul>
                        </div>
                        <div class="col-md-2" style="padding:0px 1px 0px 1px !important;"><button type="button" class="btn btn-default btn-sm" style="width:100% !important;"><img src="cmn_images/FloppyDisk.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">SAVE</button></div>
                        <div class="col-md-2" style="padding:0px 1px 0px 1px !important;"><button type="button" class="btn btn-default btn-sm" style="width:100% !important;"><img src="cmn_images/Emailcon.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">SUBMIT</button></div>
                    </div>
                </div>
                <div class="row" style="margin: 0px 0px 10px 0px !important;">
                    <div class="col-md-12" style="padding:0px 0px 0px 0px !important;">
                        <button type="button" class="btn btn-default btn-sm phone-only-btn" onclick="openATab('#allmodules', 'grp=17&typ=1&pg=10&vtyp=0');">Basic Data</button>
                        <button type="button" class="btn btn-default btn-sm phone-only-btn" onclick="openATab('#prflAddPrsnDataEDT', 'grp=17&typ=1&pg=10&vtyp=1');">Additional Data</button>
                        <button type="button" class="btn btn-default btn-sm phone-only-btn" onclick="openATab('#prflOrgAsgnEDT', 'grp=17&typ=1&pg=10&vtyp=2');">Attachments</button>
                    </div>
                </div>
                <div class="">
                    <ul class="nav nav-tabs rho-hideable-tabs" style="margin-top:-10px !important;">
                        <li class="active"><a data-toggle="tab" data-rhodata="&pg=10&vtyp=0" href="#prflHomeEDT" id="prflHomeEDTtab">Basic Data</a></li>
                        <li><a data-toggle="tabajxprfledt" data-rhodata="&pg=10&vtyp=1" href="#prflAddPrsnDataEDT" onclick="openATab('#prflAddPrsnDataEDT', 'grp=17&typ=1&pg=10&vtyp=1');" id="prflAddPrsnDataEDTtab">Additional Data</a></li>
                        <li><a data-toggle="tabajxprfledt" data-rhodata="&pg=10&vtyp=2" href="#prflOrgAsgnEDT" onclick="openATab('#prflOrgAsgnEDT', 'grp=17&typ=1&pg=10&vtyp=2');" id="prflOrgAsgnEDTtab">Attachments</a></li>
                    </ul>
                    <div class="row">                  
                        <div class="col-md-12">
                            <div class="custDiv"> 
                                <div class="tab-content">
                                    <div id="prflHomeEDT" class="tab-pane fadein active" style="border:none !important;">                          
                                        <form class="form-horizontal">
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
                                                                <span></span>
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
                                                                <select class="form-control" id="maritalStatus" >
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
                                                            <img src="cmn_images/no_image.png" alt="..." id="img1Test" class="img-rounded center-block img-responsive" style="height: 195px !important; width: auto !important;">                                            
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
                                                    <fieldset class="basic_person_fs2"><legend class="basic_person_lg">Contact Information</legend>
                                                        <div class="form-group form-group-sm">
                                                            <label for="linkedFirm" class="control-label col-md-4">Linked Firm/ Workplace</label>
                                                            <div  class="col-md-8">
                                                                <div class="input-group">
                                                                    <input type="text" class="form-control" aria-label="..." id="lnkdFirmName" value="">
                                                                    <input type="hidden" id="gnrlOrgID" value="<?php echo $orgID; ?>">
                                                                    <input type="hidden" id="lnkdFirmID" value="">
                                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Business/Trade Partners', 'gnrlOrgID', '', '', 'radio', true, '', 'lnkdFirmID', 'lnkdFirmName', 'clear', 1, '');">
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
                                                                    $titleRslt = getLovValues("%", "Both", 0, 100, $brghtStr,
                                                                            getLovID("Person Types"), $isDynmyc, -1, "", "");
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
                                                                    $titleRslt = getLovValues("%", "Both", 0, 100, $brghtStr,
                                                                            getLovID("Person Type Change Reasons"), $isDynmyc, -1, "", "");
                                                                    while ($titleRow = loc_db_fetch_array($titleRslt)) {
                                                                        $selectedTxt = "";
                                                                        ?>
                                                                        <option value="<?php echo $titleRow[0]; ?>" ><?php echo $titleRow[0]; ?></option>
                                                                        <?php
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-group-sm">
                                                            <label for="furtherDetails" class="control-label col-md-4">Branch:</label>
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
                                            <div class="row"><!-- ROW 3 -->
                                                <div class="col-lg-4">
                                                    <fieldset class="basic_person_fs3"><legend class="basic_person_lg">Address</legend> 
                                                        <div class="form-group form-group-sm">
                                                            <label for="postalAddress" class="control-label col-md-4">Postal Address:</label>
                                                            <div  class="col-md-8">
                                                                <textarea class="form-control" id="pob" cols="2" placeholder="Postal Address" rows="4"></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-group-sm">
                                                            <label for="residentialAddress" class="control-label col-md-4">Residential Address:</label>
                                                            <div  class="col-md-8">
                                                                <textarea class="form-control" id="pob" cols="2" placeholder="Residential Address" rows="4"></textarea>
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
                                </div>                        
                            </div>                         
                        </div>                
                    </div>          
                </div>
                <!--  style="min-width: 1000px;left:-35%;"-->
                <div class="modal fade" id="myLovModal" tabindex="-1" role="dialog" aria-labelledby="myLovModalTitle">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="myLovModalTitle"></h4>
                            </div>
                            <div class="modal-body" id="myLovModalBody" style="min-height: 100px;border-bottom: none !important;"></div>
                            <div class="modal-footer" style="border-top: none !important;">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="myFormsModal1" tabindex="-1" role="dialog" aria-labelledby="myFormsModalTitle">
                    <div class="modal-dialog" role="document" style="width:90% !important;">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="myFormsModalTitle"></h4>
                            </div>
                            <div class="modal-body" id="myFormsModalBody" style="min-height: 100px;border-bottom: none !important;"></div>
                            <div class="modal-footer" style="border-top: none !important;">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="myFormsModalLg" tabindex="-1" role="dialog" aria-labelledby="myFormsModalTitleLg">
                    <div class="modal-dialog" role="document" style="max-width:800px;">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="myFormsModalTitleLg"></h4>
                            </div>
                            <div class="modal-body" id="myFormsModalBodyLg" style="min-height: 100px;border-bottom: none !important;"></div>
                            <div class="modal-footer" style="border-top: none !important;">
                            </div>
                        </div>
                    </div>
                </div>
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
            ?>
            <form class="form-horizontal" id="ntnlIDCardsForm" style="padding:5px 20px 5px 20px;">
                <div class="row">
                    <div class="form-group form-group-sm">
                        <label for="ntnlIDCardsCountry" class="control-label col-md-4">Country:</label>
                        <div class="col-md-8">
                            <input class="form-control" size="16" type="hidden" id="ntnlIDpKey" value="<?php echo $ntnlIDpKey; ?>" readonly="">
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
                            <input class="form-control" id="ntnlIDCardsIDNo" type = "text" placeholder="ID No." value=""/>
                        </div>
                    </div>
                    <div class="form-group form-group-sm">
                        <label for="ntnlIDCardsDateIssd" class="control-label col-md-4">Date Issued:</label>
                        <div class="col-md-8">
                            <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                <input class="form-control" size="16" type="text" id="ntnlIDCardsDateIssd" value="">
                                <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group form-group-sm">
                        <label for="ntnlIDCardsExpDate" class="control-label col-md-4">Expiry Date:</label>
                        <div class="col-md-8">
                            <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                <input class="form-control" size="16" type="text" id="ntnlIDCardsExpDate" value="">
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
                    <button type="button" class="btn btn-primary" onclick="saveIndCustNtnlIDForm('myFormsModal', '<?php echo $ntnlIDpKey; ?>');">Save Changes</button>
                </div>
            </form>
            <?php
        }
    } else if ($subPgNo == 1.2) {//MTN AGENTS
        ?>
        <form class="form-horizontal" id='excelForm' action='' method='post' enctype="multipart/form-data">
            <div class="row" style="margin-bottom:10px;">
                <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="uploadCSV('myFormsModal');">
                    <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                    Upload File
                </button>
                <div class="col-lg-4">
                    <div class="form-group">
                        <label for="inputDoc">File input</label>
                        <input type="file" class="form-control-file" id="inputDoc" aria-describedby="fileHelp">
                        <small id="fileHelp" class="form-text text-muted">This is some placeholder block-level help text for the above input. It's a bit lighter and easily wraps to a new line.</small>
                    </div>
                </div>
                <div class="col-lg-8">
                    <fieldset class="basic_person_fs1"><legend class="basic_person_lg">Names</legend>
                        <div class="col-lg-12">
                            <div class="form-group form-group-sm">
                                <label for="idNo" class="control-label col-md-4">ID No:</label>
                                <div class="col-md-8">
                                    <span><?php echo "" ?></span>
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
                                        $titleRslt = getLovValues("%", "Both", 0, 100, $brghtStr, getLovID("Person Titles"), $isDynmyc, -1,
                                                "", "");
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
                        </div>
                    </fieldset>
                </div>                    
            </div>
        </form>
        <?php
    } else if ($subPgNo == 1.3) {//AIRTEL AGENTS
        if ($vwtyp == "0") {
            /* BASIC DATA */
            if ($vwtypActn == "EDIT") {
                /* Read Only */
                $result = get_CorpCustDet($pkID);

                while ($row = loc_db_fetch_array($result)) {
                    ?>

                    <div class="row" style="margin: 0px 0px 10px 0px !important;">
                        <div class="col-md-3" style="padding:0px 0px 0px 15px !important;">&nbsp;</div>                        
                        <div class="col-md-9" style="padding:0px 0px 0px 0px">
                            <div class="col-md-7" style="padding:0px 1px 0px 1px !important;"><button type="button" class="btn btn-default btn-sm" style="width:100% !important;"><span style="font-weight:bold;">Status: </span><span style="color:red;font-weight: bold;">Approved</span></button></div>
                            <div class="col-md-1" style="padding:0px 0px 0px 15px !important;">&nbsp;</div>
                            <div class="col-md-2" style="padding:0px 1px 0px 1px !important;"><button type="button" class="btn btn-default btn-sm" style="width:100% !important;"><img src="cmn_images/FloppyDisk.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">SAVE</button></div>
                            <div class="col-md-2" style="padding:0px 1px 0px 1px !important;"><button type="button" class="btn btn-default btn-sm" style="width:100% !important;"><img src="cmn_images/Emailcon.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">SUBMIT</button></div>
                        </div>
                    </div>

                    <div class="row" style="margin: 0px 0px 10px 0px !important;">
                        <div class="col-md-12" style="padding:0px 0px 0px 0px !important;">
                            <button type="button" class="btn btn-default btn-sm phone-only-btn" onclick="openATab('#allmodules', 'grp=17&typ=1&pg=10&vtyp=0');">Basic Data</button>
                            <button type="button" class="btn btn-default btn-sm phone-only-btn" onclick="openATab('#prflAddPrsnDataEDT', 'grp=17&typ=1&pg='<?php echo $pgNo; ?>'&subPgNo='<?php echo $subPgNo; ?>'&vtyp=1&vtypActn=VIEW');">Additional Data</button>
                            <button type="button" class="btn btn-default btn-sm phone-only-btn" onclick="openATab('#prflOrgAsgnEDT', 'grp=17&typ=1&pg=10&vtyp=2');">Attachments</button>
                        </div>
                    </div>
                    <div class="">
                        <ul class="nav nav-tabs rho-hideable-tabs" style="margin-top:-10px !important;">
                            <li class="active"><a data-toggle="tab" data-rhodata="&pg=10&vtyp=0" href="#prflHomeEDT" id="prflHomeEDTtab">Basic Data</a></li>
                            <li><a data-toggle="tabajxprfledt" data-rhodata="&pg=<?php echo $pgNo; ?>&subPgNo=<?php echo $subPgNo; ?>&vtyp=1&vtypActn=VIEW" href="#prflAddPrsnDataEDT" onclick="openATab('#prflAddPrsnDataEDT', 'grp=17&typ=1&pg=<?php echo $pgNo; ?>&subPgNo=<?php echo $subPgNo; ?>&vtyp=1&vtypActn=VIEW');" id="prflAddPrsnDataEDTtab">Group Members</a></li>
                            <li><a data-toggle="tabajxprfledt" data-rhodata="&pg=<?php echo $pgNo; ?>&subPgNo=<?php echo $subPgNo; ?>&vtyp=2&vtypActn=VIEW" href="#prflOrgAsgnEDT" onclick="openATab('#prflOrgAsgnEDT', 'grp=17&typ=1&pg=<?php echo $pgNo; ?>&subPgNo=<?php echo $subPgNo; ?>&vtyp=2&vtypActn=VIEW');" id="prflOrgAsgnEDTtab">Attachments</a></li>
                        </ul>
                        <div class="row">                  
                            <div class="col-md-12">
                                <div class="custDiv"> 
                                    <div class="tab-content">
                                        <div id="prflHomeEDT" class="tab-pane fadein active" style="border:none !important;">                          
                                            <form class="form-horizontal">
                                                <div class="row"><!-- ROW 1 -->
                                                    <div class="col-lg-1">&nbsp;</div>                                
                                                    <div class="col-lg-5">
                                                        <fieldset class="basic_person_fs5"><legend class="basic_person_lg">Basic Information</legend>
                                                            <div class="form-group form-group-sm">
                                                                <label for="idNo" class="control-label col-md-4">ID No:</label>
                                                                <div class="col-md-8">
                                                                    <span><?php echo $row[1]; ?></span>
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
                                                                                getLovID("Customer Classifications"), $isDynmyc, -1, "", "");
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
                                                                <label for="custName" class="control-label col-md-4">Group Name:</label>
                                                                <div  class="col-md-8">
                                                                    <input class="form-control" id="custName" type = "text" placeholder="Group Name" value="<?php echo $row[2]; ?>"/>
                                                                </div>
                                                            </div> 
                                                            <div class="form-group form-group-sm">
                                                                <label for="dateOfEstblshmnt" class="control-label col-md-4">Date of Establishment:</label>
                                                                <div class="col-md-8">
                                                                    <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                                                        <input class="form-control" size="16" type="text" id="dateOfEstblshmnt" value="<?php echo $row[5]; ?>" readonly="">
                                                                        <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                                                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                                    </div>
                                                                </div>
                                                            </div>     
                                                        </fieldset>
                                                    </div>
                                                    <div class="col-lg-5">
                                                        <fieldset class="basic_person_fs5"><legend class="basic_person_lg">Relationship Type</legend>                                    
                                                            <div class="form-group form-group-sm">
                                                                <label for="relation" class="control-label col-md-4">Relation:</label>
                                                                <div  class="col-md-8">
                                                                    <select class="form-control" id="relation" >
                                                                        <?php
                                                                        $brghtStr = "";
                                                                        $isDynmyc = FALSE;
                                                                        $titleRslt = getLovValues("%", "Both", 0, 100, $brghtStr,
                                                                                getLovID("Person Types"), $isDynmyc, -1, "", "");
                                                                        while ($titleRow = loc_db_fetch_array($titleRslt)) {
                                                                            $selectedTxt = "";
                                                                            if ($titleRow[0] == $row[22]) {
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
                                                                <label for="furtherDetails" class="control-label col-md-4">Branch:</label>
                                                                <div  class="col-md-8">
                                                                    <div class="input-group">
                                                                        <input class="form-control" id="relationDetails" type = "text" placeholder="Branch" value="<?php echo $row[24]; ?>"/>
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
                                                                        <input class="form-control" size="16" type="text" id="startDate" value="<?php echo $row[25]; ?>" readonly="">
                                                                        <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                                                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                                    </div>
                                                                </div>
                                                            </div>      
                                                            <div class="form-group form-group-sm">
                                                                <label for="endDate" class="control-label col-md-4">End Date:</label>
                                                                <div  class="col-md-8">
                                                                    <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input4" data-link-format="yyyy-mm-dd">
                                                                        <input class="form-control" size="16" type="text" id="endDate" value="<?php echo $row[26]; ?>" readonly="">
                                                                        <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                                                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                                    </div>
                                                                </div>
                                                            </div>  
                                                        </fieldset>                                                
                                                    </div>                                                                                                      
                                                    <div class="col-lg-1">&nbsp;</div>                                                     
                                                </div>    
                                                <div class="row"><!-- ROW 2 -->
                                                    <div class="col-lg-1">&nbsp;</div>                                
                                                    <div class="col-lg-5">
                                                        <fieldset class="basic_person_fs5"><legend class="basic_person_lg">Contact Information</legend>
                                                            <div class="form-group form-group-sm">
                                                                <label for="pstlAddress" class="control-label col-md-4">Address:</label>
                                                                <div  class="col-md-8">
                                                                    <textarea class="form-control" id="pstlAddress" cols="2" placeholder="Address" rows="5"><?php echo $row[17]; ?></textarea>
                                                                </div>
                                                            </div>
                                                            <!--<div class="form-group form-group-sm">
                                                                <label for="resAddress" class="control-label col-md-4">Residential Address:</label>
                                                                <div  class="col-md-8">
                                                                    <textarea class="form-control" id="resAddress" cols="2" placeholder="Residential Address" rows="5"><?php echo $row[18]; ?></textarea>
                                                                </div>
                                                            </div>--> 
                                                            <div class="form-group form-group-sm">
                                                                <label for="email" class="control-label col-md-4">Email:</label>
                                                                <div  class="col-md-8">
                                                                    <input class="form-control" id="prsEmail" type = "email" placeholder="<?php echo $admin_email; ?>" value="<?php echo $row[16]; ?>"/>
                                                                </div>
                                                            </div> 
                                                            <div class="form-group form-group-sm">
                                                                <label for="contactNos" class="control-label col-md-4">Contact Nos:</label>
                                                                <div  class="col-md-8">
                                                                    <input class="form-control" id="contactNos" type = "text" placeholder="Contact Nos" value="<?php echo $row[14]; ?>"/>                                     
                                                                </div>
                                                            </div>     
                                                            <!--<div class="form-group form-group-sm">
                                                                <label for="fax" class="control-label col-md-4">Fax:</label>
                                                                <div  class="col-md-8">
                                                                    <input class="form-control" id="faxNo" type = "text" placeholder="Fax" value="<?php echo $row[15]; ?>"/>
                                                                </div>
                                                            </div>--> 
                                                        </fieldset>                                                
                                                    </div>
                                                    <div class="col-lg-5"> 
                                                        <fieldset class="basic_person_fs5"><legend class="basic_person_lg">Meeting Details</legend>
                                                            <div class="form-group form-group-sm">
                                                                <label for="meetingPlace" class="control-label col-md-4">Meeting Place:</label>
                                                                <div  class="col-md-8">
                                                                    <textarea class="form-control" id="natureOfBus" cols="2" placeholder="Meeting Place" rows="5"><?php echo $row[3]; ?></textarea>
                                                                </div>
                                                            </div>                                                           
                                                            <div class="form-group form-group-sm">
                                                                <label for="meetingDay" class="control-label col-md-4">Meeting Day:</label>
                                                                <div  class="col-md-8">
                                                                    <select class="form-control" id="meetingDay" >
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
                                                            <div class="form-group form-group-sm">
                                                                <label for="noOfEmp" class="control-label col-md-4">Group Size:</label>
                                                                <div  class="col-md-8">
                                                                    <input class="form-control" id="noOfEmp" type = "text" placeholder="Group Size" value="<?php echo $row[13]; ?>"/>
                                                                </div> 
                                                            </div>                                                             
                                                        </fieldset>   
                                                    </div>
                                                    <div class="col-lg-1">&nbsp;</div>
                                                </div>  
                                            </form>  
                                        </div>
                                        <div id="prflAddPrsnDataEDT" class="tab-pane fade" style="border:none !important;"></div>
                                        <div id="prflOrgAsgnEDT" class="tab-pane fade" style="border:none !important;"></div>      
                                    </div>                        
                                </div>                         
                            </div>                
                        </div>          
                    </div>
                    <?php
                }
            } else if ($vwtypActn === "ADD") {
                /* Add */
                ?>

                <div class="row" style="margin: 0px 0px 10px 0px !important;">
                    <div class="col-md-3" style="padding:0px 0px 0px 15px !important;">&nbsp;</div>                        
                    <div class="col-md-9" style="padding:0px 0px 0px 0px">
                        <div class="col-md-7" style="padding:0px 1px 0px 1px !important;"><button type="button" class="btn btn-default btn-sm" style="width:100% !important;"><span style="font-weight:bold;">Status: </span><span style="color:red;font-weight: bold;">Approved</span></button></div>
                        <div class="col-md-1" style="padding:0px 0px 0px 15px !important;">&nbsp;</div>
                        <div class="col-md-2" style="padding:0px 1px 0px 1px !important;"><button type="button" class="btn btn-default btn-sm" style="width:100% !important;"><img src="cmn_images/FloppyDisk.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">SAVE</button></div>
                        <div class="col-md-2" style="padding:0px 1px 0px 1px !important;"><button type="button" class="btn btn-default btn-sm" style="width:100% !important;"><img src="cmn_images/Emailcon.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">SUBMIT</button></div>
                    </div>
                </div>

                <div class="row" style="margin: 0px 0px 10px 0px !important;">
                    <div class="col-md-12" style="padding:0px 0px 0px 0px !important;">
                        <button type="button" class="btn btn-default btn-sm phone-only-btn" onclick="openATab('#allmodules', 'grp=17&typ=1&pg=10&vtyp=0');">Basic Data</button>
                        <button type="button" class="btn btn-default btn-sm phone-only-btn" onclick="openATab('#prflAddPrsnDataEDT', 'grp=17&typ=1&pg=10&vtyp=1');">Additional Data</button>
                        <button type="button" class="btn btn-default btn-sm phone-only-btn" onclick="openATab('#prflOrgAsgnEDT', 'grp=17&typ=1&pg=10&vtyp=2');">Attachments</button>
                    </div>
                </div>
                <div class="">
                    <ul class="nav nav-tabs rho-hideable-tabs" style="margin-top:-10px !important;">
                        <li class="active"><a data-toggle="tab" data-rhodata="&pg=10&vtyp=0" href="#prflHomeEDT" id="prflHomeEDTtab">Basic Data</a></li>
                        <li><a data-toggle="tabajxprfledt" data-rhodata="&pg=<?php echo $pgNo; ?>&subPgNo=<?php echo $subPgNo; ?>&vtyp=1&vtypActn=VIEW" href="#prflAddPrsnDataEDT" onclick="openATab('#prflAddPrsnDataEDT', 'grp=17&typ=1&pg=<?php echo $pgNo; ?>&subPgNo=<?php echo $subPgNo; ?>&vtyp=1&vtypActn=VIEW');" id="prflAddPrsnDataEDTtab">Group Members</a></li>
                        <li><a data-toggle="tabajxprfledt" data-rhodata="&pg=<?php echo $pgNo; ?>&subPgNo=<?php echo $subPgNo; ?>&vtyp=2&vtypActn=VIEW" href="#prflOrgAsgnEDT" onclick="openATab('#prflOrgAsgnEDT', 'grp=17&typ=1&pg=<?php echo $pgNo; ?>&subPgNo=<?php echo $subPgNo; ?>&vtyp=2&vtypActn=VIEW');" id="prflOrgAsgnEDTtab">Attachments</a></li>
                    </ul>
                    <div class="row">                  
                        <div class="col-md-12">
                            <div class="custDiv"> 
                                <div class="tab-content">
                                    <div id="prflHomeEDT" class="tab-pane fadein active" style="border:none !important;">                          
                                        <form class="form-horizontal">
                                            <div class="row"><!-- ROW 1 -->
                                                <div class="col-lg-1">&nbsp;</div>                                
                                                <div class="col-lg-5">
                                                    <fieldset class="basic_person_fs5"><legend class="basic_person_lg">Basic Information</legend>
                                                        <div class="form-group form-group-sm">
                                                            <label for="idNo" class="control-label col-md-4">ID No:</label>
                                                            <div class="col-md-8">
                                                                <span><?php echo ""; ?></span>
                                                            </div>
                                                        </div> 
                                                        <div class="form-group form-group-sm">
                                                            <label for="clsfctn" class="control-label col-md-4">Classification:</label>
                                                            <div  class="col-md-8">
                                                                <select class="form-control" id="clsfctn" >
                                                                    <option value="">&nbsp;</option>
                                                                    <?php
                                                                    $brghtStr = "";
                                                                    $isDynmyc = FALSE;
                                                                    $titleRslt = getLovValues("%", "Both", 0, 100, $brghtStr,
                                                                            getLovID("Customer Classifications"), $isDynmyc, -1, "", "");
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
                                                            <label for="custName" class="control-label col-md-4">Group Name:</label>
                                                            <div  class="col-md-8">
                                                                <input class="form-control" id="custName" type = "text" placeholder="Group Name" value="<?php echo ""; ?>"/>
                                                            </div>
                                                        </div> 
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
                                                    </fieldset>
                                                </div>
                                                <div class="col-lg-5">
                                                    <fieldset class="basic_person_fs5"><legend class="basic_person_lg">Relationship Type</legend>                                    
                                                        <div class="form-group form-group-sm">
                                                            <label for="relation" class="control-label col-md-4">Relation:</label>
                                                            <div  class="col-md-8">
                                                                <select class="form-control" id="relation" >
                                                                    <option value="">&nbsp;</option>
                                                                    <?php
                                                                    $brghtStr = "";
                                                                    $isDynmyc = FALSE;
                                                                    $titleRslt = getLovValues("%", "Both", 0, 100, $brghtStr,
                                                                            getLovID("Person Types"), $isDynmyc, -1, "", "");
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
                                                            <label for="furtherDetails" class="control-label col-md-4">Branch:</label>
                                                            <div  class="col-md-8">
                                                                <div class="input-group">
                                                                    <input class="form-control" id="relationDetails" type = "text" placeholder="Branch" value="<?php echo ""; ?>"/>
                                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Person Types-Further Details', '', '', '', 'radio', true, '<?php echo ""; ?>', '', 'relationDetails', 'clear', 1, '');">
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
                                                                </div>
                                                            </div>
                                                        </div>  
                                                    </fieldset>                                                
                                                </div>                                                                                                      
                                                <div class="col-lg-1">&nbsp;</div>                                                     
                                            </div>    
                                            <div class="row"><!-- ROW 2 -->
                                                <div class="col-lg-1">&nbsp;</div>                                
                                                <div class="col-lg-5">
                                                    <fieldset class="basic_person_fs5"><legend class="basic_person_lg">Contact Information</legend>
                                                        <div class="form-group form-group-sm">
                                                            <label for="pstlAddress" class="control-label col-md-4">Address:</label>
                                                            <div  class="col-md-8">
                                                                <textarea class="form-control" id="pstlAddress" cols="2" placeholder="Address" rows="5"><?php echo ""; ?></textarea>
                                                            </div>
                                                        </div>
                                                        <!--<div class="form-group form-group-sm">
                                                            <label for="resAddress" class="control-label col-md-4">Residential Address:</label>
                                                            <div  class="col-md-8">
                                                                <textarea class="form-control" id="resAddress" cols="2" placeholder="Residential Address" rows="5"><?php echo ""; ?></textarea>
                                                            </div>
                                                        </div>--> 
                                                        <div class="form-group form-group-sm">
                                                            <label for="email" class="control-label col-md-4">Email:</label>
                                                            <div  class="col-md-8">
                                                                <input class="form-control" id="prsEmail" type = "email" placeholder="<?php echo $admin_email; ?>" value="<?php echo ""; ?>"/>
                                                            </div>
                                                        </div> 
                                                        <div class="form-group form-group-sm">
                                                            <label for="contactNos" class="control-label col-md-4">Contact Nos:</label>
                                                            <div  class="col-md-8">
                                                                <input class="form-control" id="contactNos" type = "text" placeholder="Contact Nos" value="<?php echo ""; ?>"/>                                     
                                                            </div>
                                                        </div>     
                                                        <!--<div class="form-group form-group-sm">
                                                            <label for="fax" class="control-label col-md-4">Fax:</label>
                                                            <div  class="col-md-8">
                                                                <input class="form-control" id="faxNo" type = "text" placeholder="Fax" value="<?php echo ""; ?>"/>
                                                            </div>
                                                        </div>--> 
                                                    </fieldset>                                                
                                                </div>
                                                <div class="col-lg-5"> 
                                                    <fieldset class="basic_person_fs5"><legend class="basic_person_lg">Meeting Details</legend>
                                                        <div class="form-group form-group-sm">
                                                            <label for="meetingPlace" class="control-label col-md-4">Meeting Place:</label>
                                                            <div  class="col-md-8">
                                                                <textarea class="form-control" id="natureOfBus" cols="2" placeholder="Meeting Place" rows="5"><?php echo ""; ?></textarea>
                                                            </div>
                                                        </div>                                                           
                                                        <div class="form-group form-group-sm">
                                                            <label for="meetingDay" class="control-label col-md-4">Meeting Day:</label>
                                                            <div  class="col-md-8">
                                                                <select class="form-control" id="meetingDay" >
                                                                    <option value="">&nbsp;</option>
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
                                                        <div class="form-group form-group-sm">
                                                            <label for="noOfEmp" class="control-label col-md-4">Group Size:</label>
                                                            <div  class="col-md-8">
                                                                <input class="form-control" id="noOfEmp" type = "text" placeholder="Group Size" value="<?php echo ""; ?>"/>
                                                            </div> 
                                                        </div>                                                             
                                                    </fieldset>   
                                                </div>
                                                <div class="col-lg-1">&nbsp;</div>
                                            </div>  
                                        </form>  
                                    </div>
                                    <div id="prflAddPrsnDataEDT" class="tab-pane fade" style="border:none !important;"></div>
                                    <div id="prflOrgAsgnEDT" class="tab-pane fade" style="border:none !important;"></div>      
                                </div>                        
                            </div>                         
                        </div>                
                    </div>          
                </div>
                <?php
            } else if ($vwtypActn === "VIEW") {
                /* Add */
            }
        } else if ($vwtyp == "1") {
            /* GROUP MEMBERS DATA */
            if ($vwtypActn == "VIEW") {
                /* Read Only */
                ?>    
                <form id='groupMembersForm' action='' method='post' accept-charset='UTF-8'>
                    <div class="row"><!-- ROW 3 -->
                        <div class="col-lg-12"> 
                            <fieldset class="basic_person_fs3" style="padding: 1px !important;"><legend class="basic_person_lg">Group Members</legend> 
                                <div  class="col-md-12">
                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="getCustomersForm('myFormsModal', 'myFormsModalBody', 'myFormsModalTitle', 'Add New Individual Customer', 11, 1.1, 0, 'ADD', -1);">
                                        <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                        Create Member
                                    </button>
                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="getCustomersForm('myLovModal', 'myLovModalBody', 'myLovModalTitle', 'New Director', 11, <?php echo $subPgNo; ?>, 10, '', -1);">
                                        <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                        Search Member
                                    </button>                                    
                                    <table id="nationalIDTblEDT" class="table table-striped table-bordered table-responsive" cellspacing="0" width="100%" style="width:100%;">
                                        <thead>
                                            <tr>
                                                <th>...</th>
                                                <th>ID No.</th>
                                                <th>Fullname</th>
                                                <th>Gender</th>
                                                <th>Position</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                <?php
                $result1 = get_OtherPersons($pkID, "Director");
                $cntr = 0;
                while ($row1 = loc_db_fetch_array($result1)) {
                    $cntr++;
                    ?>
                                                <tr id="ntnlIDCardsRow<?php echo $cntr; ?>">
                                                    <td>
                                                        <button type="button" class="btn btn-default btn-sm" onclick="getIndCustNtnlIDForm('myLovModal', 'myLovModalBody', 'myLovModalTitle', 'ntnlIDCardsForm', 'ntnlIDCardsRow<?php echo $cntr; ?>', 'Edit National ID', 11, <?php echo $subPgNo; ?>, 5, 'EDIT', <?php echo $row[0]; ?>);" style="padding:2px !important;">
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
                                                    <td><?php echo $row1[5]; ?></td>
                                                    <td><?php echo $row1[5]; ?></td>
                                                </tr>
                <?php } ?>
                                        </tbody>
                                    </table>
                                </div> 
                            </fieldset>
                        </div>
                    </div>  
                </form>
                <?php
            } else if ($vwtypActn == "ADD") {
                /* Add */
            } else if ($vwtypActn == "EDIT") {
                /* Edit Only */
            }
        } else if ($vwtyp == "2") {
            /* ATTACHMENTS */
            if ($vwtypActn == "VIEW") {
                /* Read Only */
                ?>    
                <form id='groupMembersForm' action='' method='post' accept-charset='UTF-8'>
                    <div class="row"><!-- ROW 3 -->
                        <div class="col-lg-12"> 
                            <fieldset class="basic_person_fs3" style="padding: 1px !important;"><legend class="basic_person_lg">Attachments</legend> 
                                <div  class="col-md-12">
                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="getCustomersForm('myLovModal', 'myLovModalBody', 'myLovModalTitle', 'New Director', 11, <?php echo $subPgNo; ?>, 10, '', -1);">
                                        <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                        Add Attachment
                                    </button>                                    
                                    <table id="nationalIDTblEDT" class="table table-striped table-bordered table-responsive" cellspacing="0" width="100%" style="width:100%;">
                                        <thead>
                                            <tr>
                                                <th>...</th>
                                                <th>Type</th>
                                                <th>Name</th>
                                                <th>Size</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                <?php
                $result1 = get_OtherPersons($pkID, "Director");
                $cntr = 0;
                while ($row1 = loc_db_fetch_array($result1)) {
                    $cntr++;
                    ?>
                                                <tr id="ntnlIDCardsRow<?php echo $cntr; ?>">
                                                    <td>
                                                        <button type="button" class="btn btn-default btn-sm" onclick="getIndCustNtnlIDForm('myLovModal', 'myLovModalBody', 'myLovModalTitle', 'ntnlIDCardsForm', 'ntnlIDCardsRow<?php echo $cntr; ?>', 'Edit National ID', 11, <?php echo $subPgNo; ?>, 5, 'EDIT', <?php echo $row[0]; ?>);" style="padding:2px !important;">
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
                                                </tr>
                <?php } ?>
                                        </tbody>
                                    </table>
                                </div> 
                            </fieldset>
                        </div>
                    </div>  
                </form>
                <?php
            } else if ($vwtypActn == "ADD") {
                /* Add */
            } else if ($vwtypActn == "EDIT") {
                /* Edit Only */
            }
        } else if ($vwtyp == "10") {
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

            $canAddPrsn = test_prmssns($dfltPrvldgs[72], $mdlNm);
            /* echo $cntent . "<li>
              <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
              <span style=\"text-decoration:none;\">Data Administration</span>
              </li>
              </ul>
              </div>"; */
            $total = get_OtherPrsnTtl($srchFor, $srchIn, $orgID, $searchAll, $otherPrsnType);
            if ($pageNo > ceil($total / $lmtSze)) {
                $pageNo = 1;
            } else if ($pageNo < 1) {
                $pageNo = ceil($total / $lmtSze);
            }

            $curIdx = $pageNo - 1;
            $result = get_OtherPrsn($srchFor, $srchIn, $curIdx, $lmtSze, $orgID, $searchAll, $sortBy, $otherPrsnType);
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
                                <input class="form-control" id="dataAdminSrchFor" type = "text" placeholder="Search For" value="<?php echo trim(str_replace("%",
                            " ", $srchFor));
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
    } else if ($subPgNo == 1.4) {//VODAFONE AGENTS
        if ($vwtyp == "0") {
            /* BASIC DATA */
            if ($vwtypActn == "EDIT") {
                /* Read Only */
                $result = get_OtherPrsnDet($pkID);

                while ($row = loc_db_fetch_array($result)) {
                    ?>

                    <div class="row" style="margin: 0px 0px 10px 0px !important;">
                        <div class="col-md-3" style="padding:0px 0px 0px 15px !important;">&nbsp;</div>                        
                        <div class="col-md-9" style="padding:0px 0px 0px 0px">
                            <div class="col-md-7" style="padding:0px 1px 0px 1px !important;"><button type="button" class="btn btn-default btn-sm" style="width:100% !important;"><span style="font-weight:bold;">Status: </span><span style="color:red;font-weight: bold;">Approved</span></button></div>
                            <div class="col-md-1" style="padding:0px 0px 0px 15px !important;">&nbsp;</div>
                            <div class="col-md-2" style="padding:0px 1px 0px 1px !important;"><button type="button" class="btn btn-default btn-sm" style="width:100% !important;"><img src="cmn_images/FloppyDisk.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">SAVE</button></div>
                            <div class="col-md-2" style="padding:0px 1px 0px 1px !important;"><button type="button" class="btn btn-default btn-sm" style="width:100% !important;"><img src="cmn_images/Emailcon.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">SUBMIT</button></div>
                        </div>
                    </div>

                    <div class="row" style="margin: 0px 0px 10px 0px !important;">
                        <div class="col-md-12" style="padding:0px 0px 0px 0px !important;">
                            <button type="button" class="btn btn-default btn-sm phone-only-btn" onclick="openATab('#allmodules', 'grp=17&typ=1&pg=10&vtyp=0');">Basic Data</button>
                            <button type="button" class="btn btn-default btn-sm phone-only-btn" onclick="openATab('#prflAddPrsnDataEDT', 'grp=17&typ=1&pg=10&vtyp=1');">Additional Data</button>
                            <button type="button" class="btn btn-default btn-sm phone-only-btn" onclick="openATab('#prflOrgAsgnEDT', 'grp=17&typ=1&pg=10&vtyp=2');">Attachments</button>
                        </div>
                    </div>
                    <div class="">
                        <ul class="nav nav-tabs rho-hideable-tabs" style="margin-top:-10px !important;">
                            <li class="active"><a data-toggle="tab" data-rhodata="&pg=10&vtyp=0" href="#prflHomeEDT" id="prflHomeEDTtab">Basic Data</a></li>
                            <li><a data-toggle="tabajxprfledt" data-rhodata="&pg=10&vtyp=1" href="#prflAddPrsnDataEDT" onclick="openATab('#prflAddPrsnDataEDT', 'grp=17&typ=1&pg=10&vtyp=1');" id="prflAddPrsnDataEDTtab">Additional Data</a></li>
                            <li><a data-toggle="tabajxprfledt" data-rhodata="&pg=10&vtyp=2" href="#prflOrgAsgnEDT" onclick="openATab('#prflOrgAsgnEDT', 'grp=17&typ=1&pg=10&vtyp=2');" id="prflOrgAsgnEDTtab">Attachments</a></li>
                        </ul>
                        <div class="row">                  
                            <div class="col-md-12">
                                <div class="custDiv"> 
                                    <div class="tab-content">
                                        <div id="prflHomeEDT" class="tab-pane fadein active" style="border:none !important;">                          
                                            <form class="form-horizontal">
                                                <div class="row">
                                                    <div class="col-lg-4">
                                                        <fieldset class="basic_person_fs1"><legend class="basic_person_lg">Person's Picture</legend>
                                                            <div style="margin-bottom: 10px;">
                                                                <img src="<?php echo $pemDest . $myImgFileName; ?>" alt="..." id="img1Test" class="img-rounded center-block img-responsive" style="height: 195px !important; width: auto !important;">                                            
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
                                                                    <span><?php echo $row[1]; ?></span>
                                                                </div>
                                                            </div> 
                                                            <div class="form-group form-group-sm">
                                                                <label for="title" class="control-label col-md-4">Title:</label>
                                                                <div  class="col-md-8">
                                                                    <select class="form-control" id="title" >
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
                                                                <label for="maritalStatus" class="control-label col-md-4">Marital Status:</label>
                                                                <div  class="col-md-8">
                                                                    <select class="form-control" id="maritalStatus" >
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
                                                        <fieldset class="basic_person_fs2"><legend class="basic_person_lg">Signature/Thumbprint</legend>
                                                            <div style="margin-bottom: 10px;">
                                                                <img src="<?php echo $pemDest . $myImgFileName; ?>" alt="..." id="img1Test" class="img-rounded center-block img-responsive" style="height: 195px !important; width: auto !important;">                                            
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
                                                        <fieldset class="basic_person_fs2"><legend class="basic_person_lg">Contact Information</legend>
                                                            <div class="form-group form-group-sm">
                                                                <label for="linkedFirm" class="control-label col-md-4">Linked Firm/ Workplace</label>
                                                                <div  class="col-md-8">
                                                                    <div class="input-group">
                                                                        <input type="text" class="form-control" aria-label="..." id="lnkdFirmName" value="<?php echo $row[21]; ?>">
                                                                        <input type="hidden" id="gnrlOrgID" value="<?php echo $orgID; ?>">
                                                                        <input type="hidden" id="lnkdFirmID" value="<?php echo $row[28]; ?>">
                                                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Business/Trade Partners', 'gnrlOrgID', '', '', 'radio', true, '<?php echo $row[21]; ?>', 'lnkdFirmID', 'lnkdFirmName', 'clear', 1, '');">
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
                                                                <label for="causeOfRelation" class="control-label col-md-4">Cause of Relation:</label>
                                                                <div  class="col-md-8">
                                                                    <select class="form-control" id="relationCause" >
                                                                        <?php
                                                                        $brghtStr = "";
                                                                        $isDynmyc = FALSE;
                                                                        $titleRslt = getLovValues("%", "Both", 0, 100, $brghtStr,
                                                                                getLovID("Person Type Change Reasons"), $isDynmyc, -1, "",
                                                                                "");
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
                                                                <label for="furtherDetails" class="control-label col-md-4">Branch:</label>
                                                                <div  class="col-md-8">
                                                                    <div class="input-group">
                                                                        <textarea class="form-control" aria-label="..." id="relationDetails"><?php echo $row[25]; ?></textarea>
                                                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Person Types-Branch', '', '', '', 'radio', true, '<?php echo $row[25]; ?>', '', 'relationDetails', 'clear', 1, '');">
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
                                                                <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="getIndCustNtnlIDForm('myLovModal', 'myLovModalBody', 'myLovModalTitle', 'ntnlIDCardsForm', '', 'Edit Customer Profile', 11, <?php echo $subPgNo; ?>, 5, '', -1);">
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
                                                                                $result1 = get_PersonAllNtnlty($pkID, "Customer");
                                                                                $cntr = 0;
                                                                                while ($row1 = loc_db_fetch_array($result1)) {
                                                                                    $cntr++;
                                                                                    ?>
                                                                            <tr id="ntnlIDCardsRow<?php echo $cntr; ?>">
                                                                                <td>
                                                                                    <button type="button" class="btn btn-default btn-sm" onclick="getIndCustNtnlIDForm('myLovModal', 'myLovModalBody', 'myLovModalTitle', 'ntnlIDCardsForm', 'ntnlIDCardsRow<?php echo $cntr; ?>', 'Edit National ID', 11, <?php echo $subPgNo; ?>, 5, 'EDIT', <?php echo $row[0]; ?>);" style="padding:2px !important;">
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
                                    </div>                        
                                </div>                         
                            </div>                
                        </div>          
                    </div>
                    <?php
                }
            } else if ($vwtypActn === "ADD") {
                /* Add */
                ?>

                <div class="row" style="margin: 0px 0px 10px 0px !important;">
                    <div class="col-md-3" style="padding:0px 0px 0px 15px !important;">&nbsp;</div>                        
                    <div class="col-md-9" style="padding:0px 0px 0px 0px">
                        <div class="col-md-6" style="padding:0px 1px 0px 1px !important;"><button type="button" class="btn btn-default btn-sm" style="width:100% !important;"><span style="font-weight:bold;">Status: </span><span style="color:red;font-weight: bold;">Approved</span></button></div>
                        <div class="btn-group col-md-2" style="padding:0px 1px 0px 1px !important;">
                            <button class="btn btn-default btn-sm" onclick="getPersonsForm('myFormsModal', 'myFormsModalBody', 'myFormsModalTitle', 'ntnlIDCardsForm', '', 'NEW PERSON', 0, 'ADD', -1);"><img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:18px; width:auto; position: relative; vertical-align: middle;">
                                New Person</button>
                            <button class="btn btn-default btn-primary dropdown-toggle btn-sm" type="button" data-toggle="dropdown">
                                <span class="caret"></span></button>
                            <ul class="dropdown-menu" role="menu" style="margin-left: 15px !important;">
                                <li><a href="#" onclick="getCustomersForm('myFormsModal', 'myFormsModalBody', 'myFormsModalTitle', 'ntnlIDCardsForm', '', 'NEW CUSTOMER', 0, 'ADD', -1);"><img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                        New Customer</a></li>
                                <li><a href="#"><img src="cmn_images/staffs.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                        Manage Persons</a></li>
                            </ul>
                        </div>
                        <div class="col-md-2" style="padding:0px 1px 0px 1px !important;"><button type="button" class="btn btn-default btn-sm" style="width:100% !important;"><img src="cmn_images/FloppyDisk.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">SAVE</button></div>
                        <div class="col-md-2" style="padding:0px 1px 0px 1px !important;"><button type="button" class="btn btn-default btn-sm" style="width:100% !important;"><img src="cmn_images/Emailcon.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">SUBMIT</button></div>
                    </div>
                </div>

                <div class="row" style="margin: 0px 0px 10px 0px !important;">
                    <div class="col-md-12" style="padding:0px 0px 0px 0px !important;">
                        <button type="button" class="btn btn-default btn-sm phone-only-btn" onclick="openATab('#allmodules', 'grp=17&typ=1&pg=10&vtyp=0');">Basic Data</button>
                        <button type="button" class="btn btn-default btn-sm phone-only-btn" onclick="openATab('#prflAddPrsnDataEDT', 'grp=17&typ=1&pg=10&vtyp=1');">Additional Data</button>
                        <button type="button" class="btn btn-default btn-sm phone-only-btn" onclick="openATab('#prflOrgAsgnEDT', 'grp=17&typ=1&pg=10&vtyp=2');">Attachments</button>
                    </div>
                </div>
                <div class="">
                    <ul class="nav nav-tabs rho-hideable-tabs" style="margin-top:-10px !important;">
                        <li class="active"><a data-toggle="tab" data-rhodata="&pg=10&vtyp=0" href="#prflHomeEDT" id="prflHomeEDTtab">Basic Data</a></li>
                        <li><a data-toggle="tabajxprfledt" data-rhodata="&pg=10&vtyp=1" href="#prflAddPrsnDataEDT" onclick="openATab('#prflAddPrsnDataEDT', 'grp=17&typ=1&pg=10&vtyp=1');" id="prflAddPrsnDataEDTtab">Additional Data</a></li>
                        <li><a data-toggle="tabajxprfledt" data-rhodata="&pg=10&vtyp=2" href="#prflOrgAsgnEDT" onclick="openATab('#prflOrgAsgnEDT', 'grp=17&typ=1&pg=10&vtyp=2');" id="prflOrgAsgnEDTtab">Attachments</a></li>
                    </ul>
                    <div class="row">                  
                        <div class="col-md-12">
                            <div class="custDiv"> 
                                <div class="tab-content">
                                    <div id="prflHomeEDT" class="tab-pane fadein active" style="border:none !important;">                          
                                        <form class="form-horizontal">
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
                                                                <span></span>
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
                                                    <fieldset class="basic_person_fs2"><legend class="basic_person_lg">Signature/Thumbprint</legend>
                                                        <div style="margin-bottom: 10px;">
                                                            <img src="cmn_images/no_image.png" alt="..." id="img1Test" class="img-rounded center-block img-responsive" style="height: 195px !important; width: auto !important;">                                            
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
                                                    <fieldset class="basic_person_fs2"><legend class="basic_person_lg">Contact Information</legend>
                                                        <div class="form-group form-group-sm">
                                                            <label for="linkedFirm" class="control-label col-md-4">Linked Firm/ Workplace</label>
                                                            <div  class="col-md-8">
                                                                <div class="input-group">
                                                                    <input type="text" class="form-control" aria-label="..." id="lnkdFirmName" value="">
                                                                    <input type="hidden" id="gnrlOrgID" value="<?php echo $orgID; ?>">
                                                                    <input type="hidden" id="lnkdFirmID" value="">
                                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Business/Trade Partners', 'gnrlOrgID', '', '', 'radio', true, '', 'lnkdFirmID', 'lnkdFirmName', 'clear', 1, '');">
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
                $titleRslt = getLovValues("%", "Both", 0, 100, $brghtStr, getLovID("MCF Other Person Types"), $isDynmyc, -1, "", "");
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
                                                                        <option value="<?php echo $titleRow[0]; ?>" ><?php echo $titleRow[0]; ?></option>
                    <?php
                }
                ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-group-sm">
                                                            <label for="furtherDetails" class="control-label col-md-4">Branch:</label>
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
                                            <div class="row"><!-- ROW 3 -->
                                                <div class="col-lg-4">
                                                    <fieldset class="basic_person_fs3"><legend class="basic_person_lg">Address</legend> 
                                                        <div class="form-group form-group-sm">
                                                            <label for="postalAddress" class="control-label col-md-4">Postal Address:</label>
                                                            <div  class="col-md-8">
                                                                <textarea class="form-control" id="pob" cols="2" placeholder="Postal Address" rows="4"></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-group-sm">
                                                            <label for="residentialAddress" class="control-label col-md-4">Residential Address:</label>
                                                            <div  class="col-md-8">
                                                                <textarea class="form-control" id="pob" cols="2" placeholder="Residential Address" rows="4"></textarea>
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
                                </div>                        
                            </div>                         
                        </div>                
                    </div>          
                </div>
                <!--  style="min-width: 1000px;left:-35%;"-->
                <div class="modal fade" id="myLovModal" tabindex="-1" role="dialog" aria-labelledby="myLovModalTitle">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="myLovModalTitle"></h4>
                            </div>
                            <div class="modal-body" id="myLovModalBody" style="min-height: 100px;border-bottom: none !important;"></div>
                            <div class="modal-footer" style="border-top: none !important;">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="myFormsModal1" tabindex="-1" role="dialog" aria-labelledby="myFormsModalTitle">
                    <div class="modal-dialog" role="document" style="width:90% !important;">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="myFormsModalTitle"></h4>
                            </div>
                            <div class="modal-body" id="myFormsModalBody" style="min-height: 100px;border-bottom: none !important;"></div>
                            <div class="modal-footer" style="border-top: none !important;">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="myFormsModalLg" tabindex="-1" role="dialog" aria-labelledby="myFormsModalTitleLg">
                    <div class="modal-dialog" role="document" style="max-width:800px;">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="myFormsModalTitleLg"></h4>
                            </div>
                            <div class="modal-body" id="myFormsModalBodyLg" style="min-height: 100px;border-bottom: none !important;"></div>
                            <div class="modal-footer" style="border-top: none !important;">
                            </div>
                        </div>
                    </div>
                </div>
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
            ?>
            <form class="form-horizontal" id="ntnlIDCardsForm" style="padding:5px 20px 5px 20px;">
                <div class="row">
                    <div class="form-group form-group-sm">
                        <label for="ntnlIDCardsCountry" class="control-label col-md-4">Country:</label>
                        <div class="col-md-8">
                            <input class="form-control" size="16" type="hidden" id="ntnlIDpKey" value="<?php echo $ntnlIDpKey; ?>" readonly="">
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
                                <input class="form-control" size="16" type="text" id="ntnlIDCardsDateIssd" value="">
                                <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group form-group-sm">
                        <label for="ntnlIDCardsExpDate" class="control-label col-md-4">Expiry Date:</label>
                        <div class="col-md-8">
                            <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                <input class="form-control" size="16" type="text" id="ntnlIDCardsExpDate" value="">
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
                    <button type="button" class="btn btn-primary" onclick="saveIndCustNtnlIDForm('myFormsModal', '<?php echo $ntnlIDpKey; ?>');">Save Changes</button>
                </div>
            </form>
            <?php
        }
    } else if ($subPgNo == 1.5) {//TIGO AGENTS
        if ($vwtyp == "0") {
            /* BASIC DATA */
            if ($vwtypActn == "EDIT") {
                /* Read Only */
                $result = get_OtherPrsnDet($pkID);

                while ($row = loc_db_fetch_array($result)) {
                    ?>

                    <div class="row" style="margin: 0px 0px 10px 0px !important;">
                        <div class="col-md-3" style="padding:0px 0px 0px 15px !important;">&nbsp;</div>                        
                        <div class="col-md-9" style="padding:0px 0px 0px 0px">
                            <div class="col-md-7" style="padding:0px 1px 0px 1px !important;"><button type="button" class="btn btn-default btn-sm" style="width:100% !important;"><span style="font-weight:bold;">Status: </span><span style="color:red;font-weight: bold;">Approved</span></button></div>
                            <div class="col-md-1" style="padding:0px 0px 0px 15px !important;">&nbsp;</div>
                            <div class="col-md-2" style="padding:0px 1px 0px 1px !important;"><button type="button" class="btn btn-default btn-sm" style="width:100% !important;"><img src="cmn_images/FloppyDisk.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">SAVE</button></div>
                            <div class="col-md-2" style="padding:0px 1px 0px 1px !important;"><button type="button" class="btn btn-default btn-sm" style="width:100% !important;"><img src="cmn_images/Emailcon.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">SUBMIT</button></div>
                        </div>
                    </div>

                    <div class="row" style="margin: 0px 0px 10px 0px !important;">
                        <div class="col-md-12" style="padding:0px 0px 0px 0px !important;">
                            <button type="button" class="btn btn-default btn-sm phone-only-btn" onclick="openATab('#allmodules', 'grp=17&typ=1&pg=10&vtyp=0');">Basic Data</button>
                            <button type="button" class="btn btn-default btn-sm phone-only-btn" onclick="openATab('#prflAddPrsnDataEDT', 'grp=17&typ=1&pg=10&vtyp=1');">Additional Data</button>
                            <button type="button" class="btn btn-default btn-sm phone-only-btn" onclick="openATab('#prflOrgAsgnEDT', 'grp=17&typ=1&pg=10&vtyp=2');">Attachments</button>
                        </div>
                    </div>
                    <div class="">
                        <ul class="nav nav-tabs rho-hideable-tabs" style="margin-top:-10px !important;">
                            <li class="active"><a data-toggle="tab" data-rhodata="&pg=10&vtyp=0" href="#prflHomeEDT" id="prflHomeEDTtab">Basic Data</a></li>
                            <li><a data-toggle="tabajxprfledt" data-rhodata="&pg=10&vtyp=1" href="#prflAddPrsnDataEDT" onclick="openATab('#prflAddPrsnDataEDT', 'grp=17&typ=1&pg=10&vtyp=1');" id="prflAddPrsnDataEDTtab">Additional Data</a></li>
                            <li><a data-toggle="tabajxprfledt" data-rhodata="&pg=10&vtyp=2" href="#prflOrgAsgnEDT" onclick="openATab('#prflOrgAsgnEDT', 'grp=17&typ=1&pg=10&vtyp=2');" id="prflOrgAsgnEDTtab">Attachments</a></li>
                        </ul>
                        <div class="row">                  
                            <div class="col-md-12">
                                <div class="custDiv"> 
                                    <div class="tab-content">
                                        <div id="prflHomeEDT" class="tab-pane fadein active" style="border:none !important;">                          
                                            <form class="form-horizontal">
                                                <div class="row">
                                                    <div class="col-lg-4">
                                                        <fieldset class="basic_person_fs1"><legend class="basic_person_lg">Person's Picture</legend>
                                                            <div style="margin-bottom: 10px;">
                                                                <img src="<?php echo $pemDest . $myImgFileName; ?>" alt="..." id="img1Test" class="img-rounded center-block img-responsive" style="height: 195px !important; width: auto !important;">                                            
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
                                                                    <span><?php echo $row[1]; ?></span>
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
                                                        <fieldset class="basic_person_fs2"><legend class="basic_person_lg">Signature/Thumbprint</legend>
                                                            <div style="margin-bottom: 10px;">
                                                                <img src="<?php echo $pemDest . $myImgFileName; ?>" alt="..." id="img1Test" class="img-rounded center-block img-responsive" style="height: 195px !important; width: auto !important;">                                            
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
                                                        <fieldset class="basic_person_fs2"><legend class="basic_person_lg">Contact Information</legend>
                                                            <div class="form-group form-group-sm">
                                                                <label for="linkedFirm" class="control-label col-md-4">Linked Firm/ Workplace</label>
                                                                <div  class="col-md-8">
                                                                    <div class="input-group">
                                                                        <input type="text" class="form-control" aria-label="..." id="lnkdFirmName" value="<?php echo $row[21]; ?>">
                                                                        <input type="hidden" id="gnrlOrgID" value="<?php echo $orgID; ?>">
                                                                        <input type="hidden" id="lnkdFirmID" value="<?php echo $row[28]; ?>">
                                                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Business/Trade Partners', 'gnrlOrgID', '', '', 'radio', true, '<?php echo $row[21]; ?>', 'lnkdFirmID', 'lnkdFirmName', 'clear', 1, '');">
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
                    $titleRslt = getLovValues("%", "Both", 0, 100, $brghtStr, getLovID("MCF Other Person Types"), $isDynmyc, -1, "", "");
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
                                                                <label for="furtherDetails" class="control-label col-md-4">Branch:</label>
                                                                <div  class="col-md-8">
                                                                    <div class="input-group">
                                                                        <textarea class="form-control" aria-label="..." id="relationDetails"><?php echo $row[25]; ?></textarea>
                                                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Person Types-Branch', '', '', '', 'radio', true, '<?php echo $row[25]; ?>', '', 'relationDetails', 'clear', 1, '');">
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
                                                                <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="getIndCustNtnlIDForm('myLovModal', 'myLovModalBody', 'myLovModalTitle', 'ntnlIDCardsForm', '', 'Edit Customer Profile', 11, <?php echo $subPgNo; ?>, 5, '', -1);">
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
                                                                                $result1 = get_PersonAllNtnlty($pkID, "Customer");
                                                                                $cntr = 0;
                                                                                while ($row1 = loc_db_fetch_array($result1)) {
                                                                                    $cntr++;
                                                                                    ?>
                                                                            <tr id="ntnlIDCardsRow<?php echo $cntr; ?>">
                                                                                <td>
                                                                                    <button type="button" class="btn btn-default btn-sm" onclick="getIndCustNtnlIDForm('myLovModal', 'myLovModalBody', 'myLovModalTitle', 'ntnlIDCardsForm', 'ntnlIDCardsRow<?php echo $cntr; ?>', 'Edit National ID', 11, <?php echo $subPgNo; ?>, 5, 'EDIT', <?php echo $row[0]; ?>);" style="padding:2px !important;">
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
                                    </div>                        
                                </div>                         
                            </div>                
                        </div>          
                    </div>
                    <?php
                }
            } else if ($vwtypActn === "ADD") {
                /* Add */
                ?>

                <div class="row" style="margin: 0px 0px 10px 0px !important;">
                    <div class="col-md-3" style="padding:0px 0px 0px 15px !important;">&nbsp;</div>                        
                    <div class="col-md-9" style="padding:0px 0px 0px 0px">
                        <div class="col-md-6" style="padding:0px 1px 0px 1px !important;"><button type="button" class="btn btn-default btn-sm" style="width:100% !important;"><span style="font-weight:bold;">Status: </span><span style="color:red;font-weight: bold;">Approved</span></button></div>
                        <div class="btn-group col-md-2" style="padding:0px 1px 0px 1px !important;">
                            <button class="btn btn-default btn-sm" onclick="getPersonsForm('myFormsModal', 'myFormsModalBody', 'myFormsModalTitle', 'ntnlIDCardsForm', '', 'NEW PERSON', 0, 'ADD', -1);"><img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:18px; width:auto; position: relative; vertical-align: middle;">
                                New Person</button>
                            <button class="btn btn-default btn-primary dropdown-toggle btn-sm" type="button" data-toggle="dropdown">
                                <span class="caret"></span></button>
                            <ul class="dropdown-menu" role="menu" style="margin-left: 15px !important;">
                                <li><a href="#" onclick="getCustomersForm('myFormsModal', 'myFormsModalBody', 'myFormsModalTitle', 'ntnlIDCardsForm', '', 'NEW CUSTOMER', 0, 'ADD', -1);"><img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                        New Customer</a></li>
                                <li><a href="#"><img src="cmn_images/staffs.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                        Manage Persons</a></li>
                            </ul>
                        </div>
                        <div class="col-md-2" style="padding:0px 1px 0px 1px !important;"><button type="button" class="btn btn-default btn-sm" style="width:100% !important;"><img src="cmn_images/FloppyDisk.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">SAVE</button></div>
                        <div class="col-md-2" style="padding:0px 1px 0px 1px !important;"><button type="button" class="btn btn-default btn-sm" style="width:100% !important;"><img src="cmn_images/Emailcon.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;">SUBMIT</button></div>
                    </div>
                </div>

                <div class="row" style="margin: 0px 0px 10px 0px !important;">
                    <div class="col-md-12" style="padding:0px 0px 0px 0px !important;">
                        <button type="button" class="btn btn-default btn-sm phone-only-btn" onclick="openATab('#allmodules', 'grp=17&typ=1&pg=10&vtyp=0');">Basic Data</button>
                        <button type="button" class="btn btn-default btn-sm phone-only-btn" onclick="openATab('#prflAddPrsnDataEDT', 'grp=17&typ=1&pg=10&vtyp=1');">Additional Data</button>
                        <button type="button" class="btn btn-default btn-sm phone-only-btn" onclick="openATab('#prflOrgAsgnEDT', 'grp=17&typ=1&pg=10&vtyp=2');">Attachments</button>
                    </div>
                </div>
                <div class="">
                    <ul class="nav nav-tabs rho-hideable-tabs" style="margin-top:-10px !important;">
                        <li class="active"><a data-toggle="tab" data-rhodata="&pg=10&vtyp=0" href="#prflHomeEDT" id="prflHomeEDTtab">Basic Data</a></li>
                        <li><a data-toggle="tabajxprfledt" data-rhodata="&pg=10&vtyp=1" href="#prflAddPrsnDataEDT" onclick="openATab('#prflAddPrsnDataEDT', 'grp=17&typ=1&pg=10&vtyp=1');" id="prflAddPrsnDataEDTtab">Additional Data</a></li>
                        <li><a data-toggle="tabajxprfledt" data-rhodata="&pg=10&vtyp=2" href="#prflOrgAsgnEDT" onclick="openATab('#prflOrgAsgnEDT', 'grp=17&typ=1&pg=10&vtyp=2');" id="prflOrgAsgnEDTtab">Attachments</a></li>
                    </ul>
                    <div class="row">                  
                        <div class="col-md-12">
                            <div class="custDiv"> 
                                <div class="tab-content">
                                    <div id="prflHomeEDT" class="tab-pane fadein active" style="border:none !important;">                          
                                        <form class="form-horizontal">
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
                                                                <span></span>
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
                                                    <fieldset class="basic_person_fs2"><legend class="basic_person_lg">Signature/Thumbprint</legend>
                                                        <div style="margin-bottom: 10px;">
                                                            <img src="cmn_images/no_image.png" alt="..." id="img1Test" class="img-rounded center-block img-responsive" style="height: 195px !important; width: auto !important;">                                            
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
                                                    <fieldset class="basic_person_fs2"><legend class="basic_person_lg">Contact Information</legend>
                                                        <div class="form-group form-group-sm">
                                                            <label for="linkedFirm" class="control-label col-md-4">Linked Firm/ Workplace</label>
                                                            <div  class="col-md-8">
                                                                <div class="input-group">
                                                                    <input type="text" class="form-control" aria-label="..." id="lnkdFirmName" value="">
                                                                    <input type="hidden" id="gnrlOrgID" value="<?php echo $orgID; ?>">
                                                                    <input type="hidden" id="lnkdFirmID" value="">
                                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'All Business/Trade Partners', 'gnrlOrgID', '', '', 'radio', true, '', 'lnkdFirmID', 'lnkdFirmName', 'clear', 1, '');">
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
                $titleRslt = getLovValues("%", "Both", 0, 100, $brghtStr, getLovID("MCF Other Person Types"), $isDynmyc, -1, "", "");
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
                                                                        <option value="<?php echo $titleRow[0]; ?>" ><?php echo $titleRow[0]; ?></option>
                    <?php
                }
                ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-group-sm">
                                                            <label for="furtherDetails" class="control-label col-md-4">Branch:</label>
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
                                            <div class="row"><!-- ROW 3 -->
                                                <div class="col-lg-4">
                                                    <fieldset class="basic_person_fs3"><legend class="basic_person_lg">Address</legend> 
                                                        <div class="form-group form-group-sm">
                                                            <label for="postalAddress" class="control-label col-md-4">Postal Address:</label>
                                                            <div  class="col-md-8">
                                                                <textarea class="form-control" id="pob" cols="2" placeholder="Postal Address" rows="4"></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-group-sm">
                                                            <label for="residentialAddress" class="control-label col-md-4">Residential Address:</label>
                                                            <div  class="col-md-8">
                                                                <textarea class="form-control" id="pob" cols="2" placeholder="Residential Address" rows="4"></textarea>
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
                                </div>                        
                            </div>                         
                        </div>                
                    </div>          
                </div>
                <!--  style="min-width: 1000px;left:-35%;"-->
                <div class="modal fade" id="myLovModal" tabindex="-1" role="dialog" aria-labelledby="myLovModalTitle">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="myLovModalTitle"></h4>
                            </div>
                            <div class="modal-body" id="myLovModalBody" style="min-height: 100px;border-bottom: none !important;"></div>
                            <div class="modal-footer" style="border-top: none !important;">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="myFormsModal1" tabindex="-1" role="dialog" aria-labelledby="myFormsModalTitle">
                    <div class="modal-dialog" role="document" style="width:90% !important;">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="myFormsModalTitle"></h4>
                            </div>
                            <div class="modal-body" id="myFormsModalBody" style="min-height: 100px;border-bottom: none !important;"></div>
                            <div class="modal-footer" style="border-top: none !important;">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="myFormsModalLg" tabindex="-1" role="dialog" aria-labelledby="myFormsModalTitleLg">
                    <div class="modal-dialog" role="document" style="max-width:800px;">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="myFormsModalTitleLg"></h4>
                            </div>
                            <div class="modal-body" id="myFormsModalBodyLg" style="min-height: 100px;border-bottom: none !important;"></div>
                            <div class="modal-footer" style="border-top: none !important;">
                            </div>
                        </div>
                    </div>
                </div>
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
            ?>
            <form class="form-horizontal" id="ntnlIDCardsForm" style="padding:5px 20px 5px 20px;">
                <div class="row">
                    <div class="form-group form-group-sm">
                        <label for="ntnlIDCardsCountry" class="control-label col-md-4">Country:</label>
                        <div class="col-md-8">
                            <input class="form-control" size="16" type="hidden" id="ntnlIDpKey" value="<?php echo $ntnlIDpKey; ?>" readonly="">
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
                                <input class="form-control" size="16" type="text" id="ntnlIDCardsDateIssd" value="">
                                <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group form-group-sm">
                        <label for="ntnlIDCardsExpDate" class="control-label col-md-4">Expiry Date:</label>
                        <div class="col-md-8">
                            <div class="input-group date form_date" data-date="" data-date-format="dd-M-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                <input class="form-control" size="16" type="text" id="ntnlIDCardsExpDate" value="">
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
                    <button type="button" class="btn btn-primary" onclick="saveIndCustNtnlIDForm('myFormsModal', '<?php echo $ntnlIDpKey; ?>');">Save Changes</button>
                </div>
            </form>
            <?php
        }
    }
    ?>
    <!-- MODAL WINDOWS -->
    <!--  style="min-width: 1000px;left:-35%;"-->
    <div class="modal fade" id="myLovModal" tabindex="-1" role="dialog" aria-labelledby="myLovModalTitle">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myLovModalTitle"></h4>
                </div>
                <div class="modal-body" id="myLovModalBody" style="min-height: 100px;border-bottom: none !important;"></div>
                <div class="modal-footer" style="border-top: none !important;">
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="myFormsModal" tabindex="-1" role="dialog" aria-labelledby="myFormsModalTitle">
        <div class="modal-dialog" role="document" style="width:90% !important;">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myFormsModalTitle"></h4>
                </div>
                <div class="modal-body" id="myFormsModalBody" style="min-height: 100px;border-bottom: none !important;"></div>
                <div class="modal-footer" style="border-top: none !important;">
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="myFormsModalLg" tabindex="-1" role="dialog" aria-labelledby="myFormsModalTitleLg">
        <div class="modal-dialog" role="document" style="max-width:800px;">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myFormsModalTitleLg"></h4>
                </div>
                <div class="modal-body" id="myFormsModalBodyLg" style="min-height: 100px;border-bottom: none !important;"></div>
                <div class="modal-footer" style="border-top: none !important;">
                </div>
            </div>
        </div>
    </div>         

    <script type="text/javascript">
        $(document).ready(function () {

        });
    </script>                

    <?php
}
?>
