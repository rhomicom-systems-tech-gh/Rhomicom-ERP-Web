<?php
if (array_key_exists('lgn_num', get_defined_vars())) {
    $pageNo = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
    $lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 10;
    $sortBy = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : "";
    $curIdx = 0;
    $canAddPrsn = test_prmssns($dfltPrvldgs[7], $mdlNm);
    $canEdtPrsn = test_prmssns($dfltPrvldgs[8], $mdlNm);
    $canDelPrsn = test_prmssns($dfltPrvldgs[9], $mdlNm);
    $canview = test_prmssns($dfltPrvldgs[0], $mdlNm);
    $sbmtdPersonID = isset($_POST['sbmtdPersonID']) ? cleanInputData($_POST['sbmtdPersonID']) : -1;
    $addOrEdit = isset($_POST['addOrEdit']) ? cleanInputData($_POST['addOrEdit']) : 'VIEW';
    $prsnid = $_SESSION['PRSN_ID'];
    $orgID = $_SESSION['ORG_ID'];
    if (($canAddPrsn === true && $addOrEdit == "ADD") || ($canEdtPrsn === true && $addOrEdit == "EDIT") || ($canview === true && $addOrEdit
            == "VIEW")) {
        $dsplyMode = $addOrEdit;
    } else {
        $sbmtdPersonID = -1;
    }
    if ($vwtyp == "0") {
        /* onclick=\"openATab('#allmodules', 'grp=8&typ=1&pg=$pgNo');\" */
        if ($sbmtdPersonID <= 0) {
            echo $cntent . "<li>
						<span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span><span style=\"text-decoration:none;\">Personal Profile</span>
					</li>
                                       </ul>
                                     </div>";
        }
        $lnkdFirmID = getGnrlRecNm("prs.prsn_names_nos", "person_id", "lnkd_firm_org_id", $prsnid);
        if ($sbmtdPersonID <= 0) {
            $pkID = $prsnid;
        } else {
            $pkID = $sbmtdPersonID;
        }
        $rcrdExst = prsn_Record_Exist($pkID);
        $chngRqstExst = prsn_ChngRqst_Exist($pkID);
        if ($pkID > 0) {
            if ($sbmtdPersonID <= 0) {
                $result = get_SelfPrsnDet($pkID);
            } else {
                $result = get_PrsnDet($pkID);
            }
            while ($row = loc_db_fetch_array($result)) {
                $nwFileName = "";
                $temp = explode(".", $row[2]);
                $extension = end($temp);
                if (trim($extension) == "") {
                    $extension = "png";
                }
                $ecnptDFlNm = encrypt1($row[2], $smplTokenWord1);
                $nwFileName = $ecnptDFlNm . "." . $extension;
                $ftp_src = "";
                if ($sbmtdPersonID <= 0) {
                    $ftp_src = $ftp_base_db_fldr . "/Person/Request/" . $pkID . "." . $extension;
                    if (!($rcrdExst == true && $chngRqstExst > 0 && file_exists($ftp_src))) {
                        $ftp_src = $ftp_base_db_fldr . "/Person/" . $pkID . "." . $extension;
                    }
                } else {
                    $ftp_src = $ftp_base_db_fldr . "/Person/" . $pkID . "." . $extension;
                }
                //echo $row[2];
                //echo $ftp_src;
                ///home/rhoportal/ecng/Person/cLSKLnmKKLSmRFTNnYBvuJQBKnALQJKZKXVXucWKmTcRLTc.png
                $fullPemDest = $fldrPrfx . $tmpDest . $nwFileName;
                if (file_exists($ftp_src)) {
                    copy("$ftp_src", "$fullPemDest");
                } else if (!file_exists($fullPemDest)) {
                    $ftp_src = $fldrPrfx . 'cmn_images/image_up.png';
                    copy("$ftp_src", "$fullPemDest");
                }
                $nwFileName = $tmpDest . $nwFileName;

                // QR CODE data 
                $name = str_replace("  ", " ", $row[3] . " " . $row[4] . " " . $row[6] . " " . $row[5]);
                $sortName = $row[5] . ';' . $row[4];
                $phone = $row[16];
                $phonePrivate = '';
                $phoneCell = $row[17];
                $orgName = $row[21];

                $email = $row[15];
                // if not used - leave blank! 
                $addressLabel = 'Address';
                $addressPobox = preg_replace("/[\r\n]+/", " ", $row[14]);
                $addressExt = '';
                $addressStreet = '';
                $addressTown = '';
                $addressRegion = '';
                $addressPostCode = '';
                $addressCountry = 'GH';
                // we building raw data 
                $codeContents = 'BEGIN:VCARD' . "\n";
                $codeContents .= 'VERSION:2.1' . "\n";
                $codeContents .= 'N:' . $sortName . "\n";
                $codeContents .= 'FN:' . $name . "\n";
                $codeContents .= 'ORG:' . $orgName . "\n";
                $codeContents .= 'TEL;WORK;VOICE:' . $phone . "\n";
                $codeContents .= 'TEL;HOME;VOICE:' . $phonePrivate . "\n";
                $codeContents .= 'TEL;TYPE=cell:' . $phoneCell . "\n";
                $codeContents .= 'ADR;TYPE=work;' .
                        'LABEL="' . $addressLabel . '":'
                        . $addressPobox . ';'
                        . $addressExt . ';'
                        . $addressStreet . ';'
                        . $addressTown . ';'
                        . $addressPostCode . ';'
                        . $addressCountry
                        . "\n";
                $codeContents .= 'EMAIL:' . $email . "\n";
                $codeContents .= 'END:VCARD';
                ?>
                <div class="row" style="margin: 0px 0px 10px 0px !important;"> 
                    <div class="col-md-12" style="padding:0px 0px 0px 15px !important;">
                        <div class="" style="padding:0px 0px 0px 0px;float:right !important;">
                            <?php
                            $rqStatus = get_RqstStatus($prsnid);
                            $rqstatusColor = "red";
                            if ($rqStatus == "Approved") {
                                $rqstatusColor = "green";
                            }
                            ?>
                            <button type="button" class="btn btn-default btn-sm" style="margin-bottom: 1px;height:30px;"><span style="font-weight:bold;">Status: </span><span style="color:<?php echo $rqstatusColor; ?>;font-weight: bold;"><?php echo $rqStatus; ?></span></button>                            
                            <?php if ($sbmtdPersonID <= 0) { ?>
                                <button type="button" class="btn btn-default" style="margin-bottom: 1px;height:30px;" onclick="openATab('#allmodules', 'grp=8&typ=1&pg=1&vtyp=0');">
                                    <img src="cmn_images/refresh.bmp" style="left: 0.5%; padding-right: 0px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                </button>
                                <button type="button" class="btn btn-default btn-sm"  style="margin-bottom: 1px;height:30px;" onclick="openATab('#allmodules', 'grp=8&typ=1&pg=2&vtyp=0');">
                                    <img src="cmn_images/edit32.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;"> EDIT</button>
                            <?php } else { ?>
                                <button type="button" class="btn btn-default btn-sm"  style="margin-bottom: 1px;height:30px;" onclick="getBscProfileForm('myFormsModalLg', 'myFormsModalBodyLg', 'myFormsModalTitleLg', 'dtAdmnBscPrsnPrflForm', 'View/Edit Person Basic Profile', <?php echo $sbmtdPersonID; ?>, 0, 2, 'EDIT');"><img src="cmn_images/edit32.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;"> EDIT</button>
                            <?php } ?>
                                <button type="button" class="btn btn-default btn-sm" style="margin-bottom: 1px;height:30px;" onclick="getPrsnProfilePDF(<?php echo $pkID; ?>);"><img src="cmn_images/pdf.png" style="left: 0.5%; padding-right: 5px; height:17px; width:auto; position: relative; vertical-align: middle;"> GET PDF</button>
                        </div>
                    </div>                    
                </div>
                <div class="row" style="margin: 0px 0px 10px 0px !important;">
                    <div class="col-md-12" style="padding:0px 0px 0px 0px !important;">
                        <button type="button" class="btn btn-default btn-sm phone-only-btn" onclick="openATab('#allmodules', 'grp=8&typ=1&pg=1&vtyp=0');">Basic Data</button>
                        <button type="button" class="btn btn-default btn-sm phone-only-btn" onclick="openATab('#prflAddPrsnDataRO', 'grp=8&typ=1&pg=1&vtyp=1');">Additional Data</button>
                        <button type="button" class="btn btn-default btn-sm phone-only-btn" onclick="openATab('#prflOrgAsgnRO', 'grp=8&typ=1&pg=1&vtyp=2');">Organisational Assignments</button>
                        <button type="button" class="btn btn-default btn-sm phone-only-btn" onclick="openATab('#prflCVRO', 'grp=8&typ=1&pg=1&vtyp=3');">Curriculum Vitae</button>
                        <button type="button" class="btn btn-default btn-sm phone-only-btn" onclick="openATab('#prflOthrInfoRO', 'grp=8&typ=1&pg=1&vtyp=4');">Attached Documents</button>
                    </div>
                </div>
                <div class="">
                    <ul class="nav nav-tabs rho-hideable-tabs" style="margin-top:-10px !important;">
                        <li class="active"><a data-toggle="tab" data-rhodata="&pg=1&vtyp=0&sbmtdPersonID=<?php echo $sbmtdPersonID; ?>" href="#prflHomeRO" id="prflHomeROtab">Basic Data</a></li>
                        <li><a data-toggle="tabajxprflro" data-rhodata="&pg=1&vtyp=1&sbmtdPersonID=<?php echo $sbmtdPersonID; ?>" href="#prflAddPrsnDataRO" id="prflAddPrsnDataROtab">Additional Data</a></li>
                        <li><a data-toggle="tabajxprflro" data-rhodata="&pg=1&vtyp=2&sbmtdPersonID=<?php echo $sbmtdPersonID; ?>" href="#prflOrgAsgnRO" id="prflOrgAsgnROtab">Organisational Assignments</a></li>
                        <li><a data-toggle="tabajxprflro" data-rhodata="&pg=1&vtyp=3&sbmtdPersonID=<?php echo $sbmtdPersonID; ?>" href="#prflCVRO" id="prflCVROtab">CV</a></li>
                        <li><a data-toggle="tabajxprflro" data-rhodata="&pg=1&vtyp=4&sbmtdPersonID=<?php echo $sbmtdPersonID; ?>" href="#prflOthrInfoRO" id="prflOthrInfoROtab">Attached Documents</a></li>
                    </ul>
                    <div class="row">                  
                        <div class="col-md-12">
                            <div class="custDiv"> 
                                <div class="tab-content">
                                    <div id="prflAddPrsnDataRO" class="tab-pane fadein" style="border:none !important;"></div>
                                    <div id="prflOrgAsgnRO" class="tab-pane fadein" style="border:none !important;"></div>    
                                    <div id="prflCVRO" class="tab-pane fadein" style="border:none !important;"></div>     
                                    <div id="prflOthrInfoRO" class="tab-pane fadein" style="border:none !important;"></div>   
                                    <div id="prflHomeRO" class="tab-pane fadein active" style="border:none !important;">                          
                                        <form class="form-horizontal">
                                            <div class="row">
                                                <div class="col-lg-4">
                                                    <fieldset class="basic_person_fs1"><legend class="basic_person_lg">Person's Picture</legend>
                                                        <div style="margin-bottom: 10px;">
                                                            <img src="<?php echo $nwFileName; ?>" alt="..." id="img1Test" class="img-rounded center-block img-responsive" style="height: 200px !important; width: auto !important;">                                            
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
                                                                <span><?php echo $row[3]; ?></span>
                                                            </div>
                                                        </div>  
                                                        <div class="form-group form-group-sm">
                                                            <label for="firstName" class="control-label col-md-4">First Name:</label>
                                                            <div  class="col-md-8">
                                                                <span><?php echo $row[4]; ?></span>
                                                            </div>
                                                        </div> 
                                                        <div class="form-group form-group-sm">
                                                            <label for="surName" class="control-label col-md-4">Surname:</label>
                                                            <div  class="col-md-8">
                                                                <span><?php echo $row[5]; ?></span>
                                                            </div>
                                                        </div>     
                                                        <div class="form-group form-group-sm">
                                                            <label for="otherNames" class="control-label col-md-4">Other Names:</label>
                                                            <div  class="col-md-8">
                                                                <span><?php echo $row[6]; ?></span>
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-group-sm">
                                                            <label for="gender" class="control-label col-md-4">Gender:</label>
                                                            <div  class="col-md-8">
                                                                <span><?php echo $row[8]; ?></span>
                                                            </div>
                                                        </div> 
                                                    </fieldset>
                                                </div>
                                                <div class="col-lg-4"> 
                                                    <fieldset class="basic_person_fs1"><legend class="basic_person_lg">Personal Data</legend>
                                                        <div class="form-group form-group-sm">
                                                            <label for="maritalStatus" class="control-label col-md-4">Marital Status:</label>
                                                            <div  class="col-md-8">
                                                                <span><?php echo $row[9]; ?></span>
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-group-sm">
                                                            <label for="dob" class="control-label col-md-4">Date of Birth</label>
                                                            <div class="col-md-8">
                                                                <span><?php echo $row[10]; ?></span>
                                                            </div>
                                                        </div> 
                                                        <div class="form-group form-group-sm">
                                                            <label for="pob" class="control-label col-md-4">Place of Birth:</label>
                                                            <div  class="col-md-8">
                                                                <span><?php echo $row[11]; ?></span>
                                                            </div>
                                                        </div> 
                                                        <div class="form-group form-group-sm">
                                                            <label for="nationality" class="control-label col-md-4">Nationality:</label>
                                                            <div  class="col-md-8">
                                                                <span><?php echo $row[20]; ?></span>
                                                            </div>
                                                        </div>  
                                                        <div class="form-group form-group-sm">
                                                            <label for="homeTown" class="control-label col-md-4">Home Town:</label>
                                                            <div  class="col-md-8">
                                                                <span><?php echo $row[19]; ?></span>
                                                            </div>
                                                        </div> 
                                                        <div class="form-group form-group-sm">
                                                            <label for="religion" class="control-label col-md-4">Religion:</label>
                                                            <div  class="col-md-8">
                                                                <span><?php echo $row[12]; ?></span>
                                                            </div>
                                                        </div>                                              
                                                    </fieldset>   
                                                </div>
                                            </div>    
                                            <div class="row"><!-- ROW 1 -->
                                                <div class="col-lg-4">
                                                    <fieldset class="basic_person_fs2"><legend class="basic_person_lg">QR Code</legend>
                                                        <div>
                                                            <img src="<?php echo getQRCodeUrl($codeContents, $ecnptDFlNm . "_QR"); ?>" alt="..." id="imgQrCode" class="img-thumbnail center-block img-responsive" style="height: 200px !important; width: auto !important;">                                            
                                                        </div>                                       
                                                    </fieldset>
                                                </div>                                
                                                <div class="col-lg-4">
                                                    <fieldset class="basic_person_fs2"><legend class="basic_person_lg">Contact Information</legend>
                                                        <div class="form-group form-group-sm">
                                                            <label for="linkedFirm" class="control-label col-md-4">Linked Firm/ Workplace</label>
                                                            <div  class="col-md-8">
                                                                <span><?php echo $row[21]; ?></span>
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-group-sm">
                                                            <label for="branch" class="control-label col-md-4">Site/Branch:</label>
                                                            <div  class="col-md-8">
                                                                <span><?php echo $row[22]; ?></span>
                                                            </div>
                                                        </div>  
                                                        <div class="form-group form-group-sm">
                                                            <label for="email" class="control-label col-md-4">Email:</label>
                                                            <div  class="col-md-8">
                                                                <span><?php echo $row[15]; ?></span>
                                                            </div>
                                                        </div> 
                                                        <div class="form-group form-group-sm">
                                                            <label for="telephone" class="control-label col-md-4">Contact Nos:</label>
                                                            <div  class="col-md-8">
                                                                <span><?php echo trim($row[16] . ", " . $row[17], ", ") ?></span>
                                                            </div>                                       
                                                        </div>     
                                                        <div class="form-group form-group-sm">
                                                            <label for="fax" class="control-label col-md-4">Fax:</label>
                                                            <div  class="col-md-8">
                                                                <span><?php echo $row[18]; ?></span>
                                                            </div>
                                                        </div> 
                                                    </fieldset>                                                
                                                </div>
                                                <div class="col-lg-4">
                                                    <fieldset class="basic_person_fs2"><legend class="basic_person_lg">Relationship with Organization</legend>                                    
                                                        <div class="form-group form-group-sm">
                                                            <label for="relation" class="control-label col-md-4">Relation:</label>
                                                            <div  class="col-md-8">
                                                                <span><?php echo $row[23]; ?></span>
                                                            </div>
                                                        </div>                                            
                                                        <div class="form-group form-group-sm">
                                                            <label for="causeOfRelation" class="control-label col-md-4">Cause of Relation:</label>
                                                            <div  class="col-md-8">
                                                                <span><?php echo $row[24]; ?></span>
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-group-sm">
                                                            <label for="furtherDetails" class="control-label col-md-4">Further Details:</label>
                                                            <div  class="col-md-8">
                                                                <span><?php echo $row[25]; ?></span>
                                                            </div>
                                                        </div>  
                                                        <div class="form-group form-group-sm">
                                                            <label for="startDate" class="control-label col-md-4">Start Date:</label>
                                                            <div class="col-md-8">
                                                                <span><?php echo $row[26]; ?></span>
                                                            </div>
                                                        </div>      
                                                        <div class="form-group form-group-sm">
                                                            <label for="endDate" class="control-label col-md-4">End Date:</label>
                                                            <div class="col-md-8">
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
                                                            <label for="postalAddress" class="control-label col-md-4">Postal Address:</label>
                                                            <div  class="col-md-8">
                                                                <span><?php echo $row[14]; ?></span>
                                                            </div>
                                                        </div>
                                                        <div class="form-group form-group-sm">
                                                            <label for="residentialAddress" class="control-label col-md-4">Residential Address:</label>
                                                            <div  class="col-md-8">
                                                                <span><?php echo $row[13]; ?></span>
                                                            </div>
                                                        </div> 
                                                    </fieldset>                                        
                                                </div>
                                                <div class="col-lg-8"> 
                                                    <fieldset class="basic_person_fs3"><legend class="basic_person_lg">National ID Cards</legend> 
                                                        <div  class="col-md-12">
                                                            <table id="nationalIDTblRO" class="table table-striped table-bordered table-responsive" cellspacing="0" width="100%" style="width:100%;">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Country</th>
                                                                        <th>ID Type</th>
                                                                        <th>ID No.</th>
                                                                        <th>Date Issued</th>
                                                                        <th>Expiry Date</th>
                                                                        <th>Other Information</th>
                                                                    </tr>
                                                                </thead>
                                                                <!--<tfoot>
                                                                    <tr>
                                                                        <th>Country</th>
                                                                        <th>ID Type</th>
                                                                        <th>ID No.</th>
                                                                        <th>Date Issued</th>
                                                                        <th>Expiry Date</th>
                                                                        <th>Other Information</th>
                                                                    </tr>
                                                                </tfoot>-->
                                                                <tbody>
                                                                    <?php
                                                                    if ($sbmtdPersonID <= 0) {
                                                                        $result1 = get_AllNtnlty_Self($pkID);
                                                                    } else {
                                                                        $result1 = get_AllNtnlty($pkID);
                                                                    }
                                                                    while ($row1 = loc_db_fetch_array($result1)) {
                                                                        ?>
                                                                        <tr>
                                                                            <td class="lovtd"><?php echo $row1[1]; ?></td>
                                                                            <td class="lovtd"><?php echo $row1[2]; ?></td>
                                                                            <td class="lovtd"><?php echo $row1[3]; ?></td>
                                                                            <td class="lovtd"><?php echo $row1[4]; ?></td>
                                                                            <td class="lovtd"><?php echo $row1[5]; ?></td>
                                                                            <td class="lovtd"><?php echo $row1[6]; ?></td>
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
                                </div>                        
                            </div>                         
                        </div>                
                    </div>          
                </div>
                <?php
            }
        }
    } else if ($vwtyp == "1") {
        /* Additional Person Data */
        $lnkdFirmID = getGnrlRecNm("prs.prsn_names_nos", "person_id", "lnkd_firm_org_id", $prsnid);
        if ($sbmtdPersonID <= 0) {
            $pkID = $prsnid;
        } else {
            $pkID = $sbmtdPersonID;
        }
        if ($pkID > 0) {
            $rcrdExst = prsn_Record_Exist($pkID);
            $result = get_PrsExtrDataGrps($orgID);
            ?>
            <form class="form-horizontal">
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
                                        ?>
                                        <div class="row">
                                            <div  class="col-md-12">
                                                <table id="extDataTblCol<?php echo $row1[1]; ?>" class="table table-striped table-bordered table-responsive extPrsnDataTblRO" cellspacing="0" width="100%" style="width:100%;"><thead><th>No.</th>
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
                                                            $fldVal = get_PrsExtrData_Self($pkID, $row1[1]);
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
                                                            ?>
                                                            <tr><td><?php echo ($j + 1); ?></td>
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
                                                        <span>
                                                            <?php
                                                            if ($sbmtdPersonID <= 0) {
                                                                echo get_PrsExtrData_Self($pkID, $row1[1]);
                                                            } else {
                                                                echo get_PrsExtrData($pkID, $row1[1]);
                                                            }
                                                            ?>
                                                        </span>
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
                        <table id="divsGroupsRO" class="table table-striped table-bordered table-responsive orgAsgnmentsTblsRO" cellspacing="0" width="100%" style="width:100%;">
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
                        <table id="immdteSprvsrsRO" class="table table-striped table-bordered table-responsive orgAsgnmentsTblsRO" cellspacing="0" width="100%" style="width:100%;">
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
                        <table id="sitesLocsRO" class="table table-striped table-bordered table-responsive orgAsgnmentsTblsRO" cellspacing="0" width="100%" style="width:100%;">
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
                        <table id="jobsRO" class="table table-striped table-bordered table-responsive orgAsgnmentsTblsRO" cellspacing="0" width="100%" style="width:100%;">
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
                        <table id="gradesRO" class="table table-striped table-bordered table-responsive orgAsgnmentsTblsRO" cellspacing="0" width="100%" style="width:100%;">
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
                        <table id="positionsRO" class="table table-striped table-bordered table-responsive orgAsgnmentsTblsRO" cellspacing="0" width="100%" style="width:100%;">
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
                        <table class="table table-striped table-bordered table-responsive cvTblsRO" cellspacing="0" width="100%" style="width:100%;">
                            <thead>
                                <tr>
                                    <th>No.</th>		
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
                                    $result1 = null;
                                    if ($sbmtdPersonID <= 0) {
                                        $result1 = get_EducBkgrdSelf($pkID);
                                    } else {
                                        $result1 = get_EducBkgrd($pkID);
                                    }
                                    while ($row1 = loc_db_fetch_array($result1)) {
                                        $cntr += 1;
                                        ?>
                                        <tr>
                                            <td><?php echo $cntr; ?></td>
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
                        <table class="table table-striped table-bordered table-responsive cvTblsRO" cellspacing="0" width="100%" style="width:100%;">
                            <thead>
                                <tr>
                                    <th>No.</th>		
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
                                    $result1 = null;
                                    if ($sbmtdPersonID <= 0) {
                                        $result1 = get_WrkBkgrdSelf($pkID);
                                    } else {
                                        $result1 = get_WrkBkgrd($pkID);
                                    }
                                    while ($row1 = loc_db_fetch_array($result1)) {
                                        $cntr += 1;
                                        ?>
                                        <tr>
                                            <td><?php echo $cntr; ?></td>
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
                        <table class="table table-striped table-bordered table-responsive cvTblsRO" cellspacing="0" width="100%" style="width:100%;">
                            <thead>
                                <tr>
                                    <th>No.</th>		
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
                                    $result1 = null;
                                    if ($sbmtdPersonID <= 0) {
                                        $result1 = get_SkillNatureSelf($pkID);
                                    } else {
                                        $result1 = get_SkillNature($pkID);
                                    }
                                    while ($row1 = loc_db_fetch_array($result1)) {
                                        $cntr += 1;
                                        ?>
                                        <tr>
                                            <td><?php echo $cntr; ?></td>
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
        /* All Attached Documents */
        if ($sbmtdPersonID <= 0) {
            $pkID = $prsnid;
        } else {
            $pkID = $sbmtdPersonID;
        }
        $total = 0;
        if ($sbmtdPersonID <= 0) {
            $total = get_Total_AttachmentsSelf($srchFor, $pkID);
        } else {
            $total = get_Total_Attachments($srchFor, $pkID);
        }
        if ($pageNo > ceil($total / $lmtSze)) {
            $pageNo = 1;
        } else if ($pageNo < 1) {
            $pageNo = ceil($total / $lmtSze);
        }

        $curIdx = $pageNo - 1;
        $attchSQL = "";
        $result2 = null;
        if ($sbmtdPersonID <= 0) {
            $result2 = get_AttachmentsSelf($srchFor, $curIdx, $lmtSze, $pkID, $attchSQL);
        } else {
            $result2 = get_Attachments($srchFor, $curIdx, $lmtSze, $pkID, $attchSQL);
        }
        $colClassType1 = "col-lg-2";
        $colClassType2 = "col-lg-3";
        $colClassType3 = "col-lg-4";
        $vwtyp = 5;
        ?>                    
        <div class="row">
            <div class="col-md-12">
                <div  id="attchdDocsList">
                    <fieldset class="" style="padding:10px 0px 5px 0px !important;">
                        <form class="" id="attchdDocsTblForm">
                            <div class="row">
                                <div class="<?php echo $colClassType2; ?>" style="padding:0px 15px 0px 15px !important;">
                                    <div class="input-group">
                                        <input class="form-control" id="attchdDocsSrchFor" type = "text" placeholder="Search For" value="<?php
                                        echo trim(str_replace("%", " ", $srchFor));
                                        ?>" onkeyup="enterKeyFuncAttchdDocs(event, '', '#attchdDocsList', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');">
                                        <input id="attchdDocsPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getAttchdDocs('clear', '#attchdDocsList', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');">
                                            <span class="glyphicon glyphicon-remove"></span>
                                        </label>
                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getAttchdDocs('', '#attchdDocsList', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');">
                                            <span class="glyphicon glyphicon-search"></span>
                                        </label> 
                                    </div>
                                </div>
                                <div class="<?php echo $colClassType2; ?>">
                                    <div class="input-group">
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>

                                        <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                        <select data-placeholder="Select..." class="form-control chosen-select" id="attchdDocsDsplySze" style="min-width:70px !important;">                            
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
                                                <a class="rhopagination" href="javascript:getAttchdDocs('previous', '#attchdDocsList', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');" aria-label="Previous">
                                                    <span aria-hidden="true">&laquo;</span>
                                                </a>
                                            </li>
                                            <li>
                                                <a class="rhopagination" href="javascript:getAttchdDocs('next', '#attchdDocsList', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');" aria-label="Next">
                                                    <span aria-hidden="true">&raquo;</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </nav>
                                </div>
                            </div>
                            <div class="row"> 
                                <div class="col-md-12">
                                    <table class="table table-striped table-bordered table-responsive" id="attchdDocsTable" cellspacing="0" width="100%" style="width:100%;min-width: 400px !important;">
                                        <thead>
                                            <tr>
                                                <th>No.</th>
                                                <th>Doc. Name/Description</th>
                                                <th>&nbsp;</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $cntr = 0;
                                            while ($row2 = loc_db_fetch_array($result2)) {
                                                $cntr += 1;
                                                $doc_src_encrpt = "";
                                                if ($sbmtdPersonID <= 0) {
                                                    $doc_src = $ftp_base_db_fldr . "/PrsnDocs/Request/" . $row2[3];
                                                    $doc_src_encrpt = encrypt1($doc_src, $smplTokenWord1);
                                                    if (file_exists($doc_src)) {
                                                        //file exists!
                                                    } else {
                                                        $doc_src1 = $ftp_base_db_fldr . "/PrsnDocs/" . $row2[3];
                                                        if (file_exists($doc_src1)) {
                                                            $temp = explode(".", $row2[3]);
                                                            $extension = end($temp);
                                                            $doc_src = $ftp_base_db_fldr . "/PrsnDocs/Request/" . $row2[0] . "." . $extension;
                                                            copy($doc_src1, $doc_src);
                                                            updatePrsnDocFlNmSelf($row2[0], $row2[0] . "." . $extension);
                                                            $doc_src_encrpt = encrypt1($doc_src, $smplTokenWord1);
                                                            if (file_exists($doc_src)) {
                                                                //file exists!
                                                            } else {
                                                                //file does not exist.
                                                                $doc_src_encrpt = "None";
                                                            }
                                                        } else {
                                                            //file does not exist.
                                                            $doc_src_encrpt = "None";
                                                        }
                                                    }
                                                } else {
                                                    $doc_src = $ftp_base_db_fldr . "/PrsnDocs/" . $row2[3];
                                                    $doc_src_encrpt = encrypt1($doc_src, $smplTokenWord1);
                                                    if (file_exists($doc_src)) {
                                                        //file exists!
                                                    } else {
                                                        //file does not exist.
                                                        $doc_src_encrpt = "None";
                                                    }
                                                }
                                                ?>
                                                <tr id="attchdDocsRow_<?php echo $cntr; ?>">                                    
                                                    <td class="lovtd"><span><?php echo ($curIdx * $lmtSze) + ($cntr); ?></span></td>
                                                    <td class="lovtd">                                                                   
                                                        <span><?php echo $row2[2]; ?></span>
                                                        <input type="hidden" class="form-control" aria-label="..." id="attchdDocsRow<?php echo $cntr; ?>_AttchdDocsID" value="<?php echo $row2[0]; ?>" style="width:100% !important;">                                              
                                                    </td>
                                                    <td class="lovtd">
                                                        <?php
                                                        if ($doc_src_encrpt == "None") {
                                                            ?>
                                                            <span style="font-weight: bold;color:#FF0000;">
                                                                <?php
                                                                echo "File Not Found!";
                                                                ?>
                                                            </span>
                                                            <?php
                                                        } else {
                                                            ?>
                                                            <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="doAjax('grp=1&typ=11&q=Download&fnm=<?php echo $doc_src_encrpt; ?>', '', 'Redirect', '', '', '');" data-toggle="tooltip" data-placement="bottom" title="Download Document">
                                                                <img src="cmn_images/dwldicon.png" style="height:15px; width:auto; position: relative; vertical-align: middle;"> Download
                                                            </button>
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
                    </fieldset>
                </div>
            </div>
        </div>
        <?php
        /* Other Information */
        $cntr = 0;
        $table_id = getMdlGrpID("Person Data", $mdlNm);
        $ext_inf_tbl_name = "prs.prsn_all_other_info_table";
        $ext_inf_seq_name = "prs.prsn_all_other_info_table_dflt_row_id_seq";
        $row_pk_id = $pkID;
        ?>
        <hr style="border-top:1px dashed #ddd !important;">
        <div class="row" style="margin-top:30px !important;">
            <div  class="col-md-12">
                <fieldset class="" style="padding:10px 0px 5px cccc//px !important;">
                    <legend class="basic_person_lg">Other Information</legend>
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
                                    $result1 = getAllwdExtInfosNVals("%", "Extra Info Label", 0, 1000000000, $brghtsqlStr,
                                            $table_id, $row_pk_id, $ext_inf_tbl_name, $orgID);
                                    while ($row1 = loc_db_fetch_array($result1)) {
                                        $cntr += 1;
                                        ?>
                                        <tr>
                                            <!--<td><?php echo $cntr; ?></td>-->
                                            <td><?php echo $row1[0]; ?></td>
                                            <td><?php echo $row1[1]; ?></td>
                                            <td><?php echo $row1[2]; ?></td>
                                        </tr>
                                        <?php
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                    </form>
                </fieldset>
            </div>
        </div>
        <?php
    } else if ($vwtyp == "5") {
        /* All Attached Documents */
        if ($sbmtdPersonID <= 0) {
            $pkID = $prsnid;
        } else {
            $pkID = $sbmtdPersonID;
        }
        $total = 0;
        if ($sbmtdPersonID <= 0) {
            $total = get_Total_AttachmentsSelf($srchFor, $pkID);
        } else {
            $total = get_Total_Attachments($srchFor, $pkID);
        }
        if ($pageNo > ceil($total / $lmtSze)) {
            $pageNo = 1;
        } else if ($pageNo < 1) {
            $pageNo = ceil($total / $lmtSze);
        }

        $curIdx = $pageNo - 1;
        $attchSQL = "";
        $result2 = null;
        if ($sbmtdPersonID <= 0) {
            $result2 = get_AttachmentsSelf($srchFor, $curIdx, $lmtSze, $pkID, $attchSQL);
        } else {
            $result2 = get_Attachments($srchFor, $curIdx, $lmtSze, $pkID, $attchSQL);
        }
        $colClassType1 = "col-lg-2";
        $colClassType2 = "col-lg-3";
        $colClassType3 = "col-lg-4";
        $vwtyp = 5;
        ?>       
        <fieldset class="" style="padding:10px 0px 5px 0px !important;">
            <form class="" id="attchdDocsTblForm">
                <div class="row">
                    <div class="<?php echo $colClassType2; ?>" style="padding:0px 15px 0px 15px !important;">
                        <div class="input-group">
                            <input class="form-control" id="attchdDocsSrchFor" type = "text" placeholder="Search For" value="<?php
                            echo trim(str_replace("%", " ", $srchFor));
                            ?>" onkeyup="enterKeyFuncAttchdDocs(event, '', '#attchdDocsList', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');">
                            <input id="attchdDocsPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                            <label class="btn btn-primary btn-file input-group-addon" onclick="getAttchdDocs('clear', '#attchdDocsList', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');">
                                <span class="glyphicon glyphicon-remove"></span>
                            </label>
                            <label class="btn btn-primary btn-file input-group-addon" onclick="getAttchdDocs('', '#attchdDocsList', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');">
                                <span class="glyphicon glyphicon-search"></span>
                            </label> 
                        </div>
                    </div>
                    <div class="<?php echo $colClassType2; ?>">
                        <div class="input-group">
                            <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>

                            <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                            <select data-placeholder="Select..." class="form-control chosen-select" id="attchdDocsDsplySze" style="min-width:70px !important;">                            
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
                                    <a class="rhopagination" href="javascript:getAttchdDocs('previous', '#attchdDocsList', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');" aria-label="Previous">
                                        <span aria-hidden="true">&laquo;</span>
                                    </a>
                                </li>
                                <li>
                                    <a class="rhopagination" href="javascript:getAttchdDocs('next', '#attchdDocsList', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');" aria-label="Next">
                                        <span aria-hidden="true">&raquo;</span>
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
                <div class="row"> 
                    <div class="col-md-12">
                        <table class="table table-striped table-bordered table-responsive" id="attchdDocsTable" cellspacing="0" width="100%" style="width:100%;min-width: 400px !important;">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Doc. Name/Description</th>
                                    <th>&nbsp;</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $cntr = 0;
                                while ($row2 = loc_db_fetch_array($result2)) {
                                    $cntr += 1;
                                    $doc_src_encrpt = "";
                                    if ($sbmtdPersonID <= 0) {
                                        $doc_src = $ftp_base_db_fldr . "/PrsnDocs/Request/" . $row2[3];
                                        $doc_src_encrpt = encrypt1($doc_src, $smplTokenWord1);
                                        if (file_exists($doc_src)) {
                                            //file exists!
                                        } else {
                                            $doc_src1 = $ftp_base_db_fldr . "/PrsnDocs/" . $row2[3];
                                            if (file_exists($doc_src1)) {
                                                $temp = explode(".", $row2[3]);
                                                $extension = end($temp);
                                                $doc_src = $ftp_base_db_fldr . "/PrsnDocs/Request/" . $row2[0] . "." . $extension;
                                                copy($doc_src1, $doc_src);
                                                updatePrsnDocFlNmSelf($row2[0], $row2[0] . "." . $extension);
                                                $doc_src_encrpt = encrypt1($doc_src, $smplTokenWord1);
                                                if (file_exists($doc_src)) {
                                                    //file exists!
                                                } else {
                                                    //file does not exist.
                                                    $doc_src_encrpt = "None";
                                                }
                                            } else {
                                                //file does not exist.
                                                $doc_src_encrpt = "None";
                                            }
                                        }
                                    } else {
                                        $doc_src = $ftp_base_db_fldr . "/PrsnDocs/" . $row2[3];
                                        $doc_src_encrpt = encrypt1($doc_src, $smplTokenWord1);
                                        if (file_exists($doc_src)) {
                                            //file exists!
                                        } else {
                                            //file does not exist.
                                            $doc_src_encrpt = "None";
                                        }
                                    }
                                    ?>
                                    <tr id="attchdDocsRow_<?php echo $cntr; ?>">                                    
                                        <td class="lovtd"><span><?php echo ($curIdx * $lmtSze) + ($cntr); ?></span></td>
                                        <td class="lovtd">                                                                   
                                            <span><?php echo $row2[2]; ?></span>
                                            <input type="hidden" class="form-control" aria-label="..." id="attchdDocsRow<?php echo $cntr; ?>_AttchdDocsID" value="<?php echo $row2[0]; ?>" style="width:100% !important;">                                              
                                        </td>
                                        <td class="lovtd">
                                            <?php
                                            if ($doc_src_encrpt == "None") {
                                                ?>
                                                <span style="font-weight: bold;color:#FF0000;">
                                                    <?php
                                                    echo "File Not Found!";
                                                    ?>
                                                </span>
                                                <?php
                                            } else {
                                                ?>
                                                <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="doAjax('grp=1&typ=11&q=Download&fnm=<?php echo $doc_src_encrpt; ?>', '', 'Redirect', '', '', '');" data-toggle="tooltip" data-placement="bottom" title="Download Document">
                                                    <img src="cmn_images/dwldicon.png" style="height:15px; width:auto; position: relative; vertical-align: middle;"> Download
                                                </button>
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
        </fieldset>         
        <?php
    }
}
?>