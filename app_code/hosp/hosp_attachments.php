<?php
$pageNo = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
$lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 10;
$sortBy = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : "";
$vwtyp = isset($_POST['vtyp']) ? cleanInputData($_POST['vtyp']) : 0;
$prsnid = $_SESSION['PRSN_ID'];
$orgID = $_SESSION['ORG_ID'];
$canAddTrns = true;
$canEdtTrns = true;
$qNotSentToGl = true;
$qUnbalncdOnly = false;
$qUsrGnrtd = false;
$qStrtDte = "01-Jan-1900 00:00:00";
$qEndDte = "31-Dec-4000 23:59:59";
$qNwStrtDte = date('d-M-Y H:i:s');
$qLowVal = 0;
$qHighVal = 0;
$canVwRcHstry = test_prmssns("View Record History", $mdlNm);
if (isset($_POST['qNotSentToGl'])) {
    $qNotSentToGl = cleanInputData($_POST['qNotSentToGl']) === "true" ? true : false;
}

if (isset($_POST['qUnbalncdOnly'])) {
    $qUnbalncdOnly = cleanInputData($_POST['qUnbalncdOnly']) === "true" ? true : false;
}

if (isset($_POST['qUsrGnrtd'])) {
    $qUsrGnrtd = cleanInputData($_POST['qUsrGnrtd']) === "true" ? true : false;
}

if (isset($_POST['qLowVal'])) {
    $qLowVal = (float) cleanInputData($_POST['qLowVal']);
}

if (isset($_POST['qHighVal'])) {
    $qHighVal = (float) cleanInputData($_POST['qHighVal']);
}

if (isset($_POST['qStrtDte'])) {
    $qStrtDte = cleanInputData($_POST['qStrtDte']);
    if (strlen($qStrtDte) == 11) {
        $qStrtDte = substr($qStrtDte, 0, 11) . " 00:00:00";
    } else {
        $qStrtDte = "01-Jan-1900 00:00:00";
    }
}

if (isset($_POST['qNwStrtDte'])) {
    $qNwStrtDte = cleanInputData($_POST['qNwStrtDte']);
    if (strlen($qNwStrtDte) == 11) {
        $qNwStrtDte = substr($qNwStrtDte, 0, 11) . " 00:00:00";
    } else {
        $qNwStrtDte = date('d-M-Y H:i:s');
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

if (array_key_exists('lgn_num', get_defined_vars())) {
    if ($lgn_num > 0 && $canview === true) {
        if ($qstr == "DELETEDOC") {
            if ($actyp == 1) {
                /* Delete Attachment */
                $attchmentID = isset($_POST['attchmentID']) ? cleanInputData($_POST['attchmentID']) : -1;
                $docTrnsNum = isset($_POST['docTrnsNum']) ? cleanInputData($_POST['docTrnsNum']) : -1;
                if ($canEdtTrns) {
                    echo deleteFSCDoc($attchmentID, $docTrnsNum);
                } else {
                    restricted();
                }
            }
        } 
        else if ($qstr == "UPDATEDOC") {
            if ($actyp == 1) {
                //Upload Attachement
                header("content-type:application/json");
                $attchmentID = isset($_POST['attchmentID']) ? cleanInputData($_POST['attchmentID']) : -1;
                $sbmtdHdrID = isset($_POST['sbmtdHdrID']) ? cleanInputData($_POST['sbmtdHdrID']) : -1;
                $pAcctID = isset($_POST['pAcctID']) ? cleanInputData($_POST['pAcctID']) : -1;
                if (!($canEdtTrns || $canAddTrns)) {
                    $arr_content['percent'] = 100;
                    $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>Permission Denied!</span>";
                    echo json_encode($arr_content);
                    exit();
                }

                $docCtrgrName = isset($_POST['docCtrgrName']) ? cleanInputData($_POST['docCtrgrName']) : "";
                $docFileType = isset($_POST['docFileType']) ? cleanInputData($_POST['docFileType']) : "";
                $docTrsType = isset($_POST['docTrsType']) ? cleanInputData($_POST['docTrsType']) : "";
                $nwImgLoc = "";
                $errMsg = "";
                if ($sbmtdHdrID <= 0 && $pAcctID > 0 && $docTrsType != "") {
                    $dateStr = getDB_Date_time();
                    //check existence of account transaction                    
                    createInitAccountTrns($pAcctID, $dateStr, $docTrsType);
                    $sbmtdHdrID = getInitAccountTrnsID($pAcctID, $dateStr);
                }
                $pkID = $sbmtdHdrID;
                if ($sbmtdHdrID > 0 && $docCtrgrName != "" && $docFileType != "" && $docTrsType != "") {
                    if ($attchmentID > 0) {
                        uploadDaFSCTrnsDoc($attchmentID, $docTrsType, $docFileType, $nwImgLoc, $errMsg);
                    } else {
                        $attchmentID = getNewFSCDocID();
                        createFSCDoc($attchmentID, $sbmtdHdrID, $docCtrgrName, "", $docFileType, $docTrsType);
                        uploadDaFSCTrnsDoc($attchmentID, $docTrsType, $docFileType, $nwImgLoc, $errMsg);
                    }
                    $arr_content['attchID'] = $attchmentID;
                    $arr_content['NwTrnsId'] = $sbmtdHdrID;
                    if (strpos($errMsg, "Document Stored Successfully!<br/>") === FALSE) {
                        $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>" . $errMsg;
                    } else {
                        //$doc_src = "";
                        //if ($docTrsType == "Individual Customers") {
                            $doc_src = $ftp_base_db_fldr . "/Mcf/Transactions/" . $nwImgLoc;
                        //}
                        $doc_src_encrpt = encrypt1($doc_src, $smplTokenWord1);
                        if (file_exists($doc_src)) {
                            //file exists!
                        } else {
                            //file does not exist.
                            $doc_src_encrpt = "None";
                        }
                        $arr_content['crptpath'] = $doc_src_encrpt;
                        $arr_content['message'] = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>" . $errMsg;
                    }
                    echo json_encode($arr_content);
                    exit();
                } else {
                    $arr_content['percent'] = 100;
                    $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>Incompleted Data Supplied!</span>";
                    echo json_encode($arr_content);
                    exit();
                }
            }
        } 
        else {
            if ($vwtyp == 140) {
                /* All Attached Documents */
                $sbmtdHdrID = isset($_POST['sbmtdHdrID']) ? cleanInputData($_POST['sbmtdHdrID']) : -1;
                $pAcctID = isset($_POST['pAcctID']) ? cleanInputData($_POST['pAcctID']) : -1;
                $trnsType = isset($_POST['docType']) ? cleanInputData($_POST['docType']) : "";
                if (!$canAddTrns || ($sbmtdHdrID > 0 && !$canEdtTrns)) {
                    restricted();
                    exit();
                }
                $pkID = $sbmtdHdrID;
                $recStatus = "Incomplete";
                                            
                if($trnsType == "FSC RISK INDICATORS"){
                    $recStatus = getGnrlRecNm("fsra.fsra_psb_hdr", "psb_hdr_id", "apprvr_status", $pkID);
                }
                
                $total = get_Total_FSCAttachments($srchFor, $pkID, $trnsType);
                if ($pageNo > ceil($total / $lmtSze)) {
                    $pageNo = 1;
                } else if ($pageNo < 1) {
                    $pageNo = ceil($total / $lmtSze);
                }
                $curIdx = $pageNo - 1;
                $attchSQL = "";
                $result2 = get_FSCAttachments($srchFor, $curIdx, $lmtSze, $pkID, $trnsType, $attchSQL);
                $colClassType1 = "col-lg-2";
                $colClassType2 = "col-lg-3";
                $colClassType3 = "col-lg-4";
                
                if($recStatus == "Approved" || $recStatus == "Reviewed"){
                    $colClassType1 = "col-lg-2";
                    $colClassType2 = "col-lg-5";
                }
                ?>       
                <fieldset class="" style="padding:10px 0px 5px 0px !important;">
                    <form class="" id="attchdFSCDocsTblForm">
                        <div class="row">
                            <?php
                            $nwRowHtml = "<tr id=\"attchdFSCDocsRow__WWW123WWW\">"
                                    . "<td class=\"lovtd\"><span>New</span></td>"
                                    . "<td class=\"lovtd\">
                                              <div class=\"form-group form-group-sm\" style=\"width:100% !important;\">
                                              <div class=\"input-group\" style=\"width:100% !important;\">
                                                <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"attchdFSCDocsRow_WWW123WWW_DocCtgryNm\" value=\"\">
                                                <input class=\"form-control\" aria-label=\"...\" id=\"attchdFSCDocsRow_WWW123WWW_DocFile\" type=\"file\" style=\"visibility:hidden;height:5px !important;display:none;\" />     
                                                <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Attachment Document Categories', '', '', '', 'radio', true, '', 'attchdFSCDocsRow_WWW123WWW_DocCtgryNm', 'attchdFSCDocsRow_WWW123WWW_DocCtgryNm', 'clear', 0, '');\">
                                                    <span class=\"glyphicon glyphicon-th-list\"></span>
                                                </label>
                                              </div>
                                              </div>
                                              <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"attchdFSCDocsRow_WWW123WWW_AttchdFSCDocsID\" value=\"-1\" style=\"\">
                                              <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"attchdFSCDocsRow_WWW123WWW_TrnsType\" value=\"$trnsType\" style=\"\">                                               
                                          </td>
                                          <td class=\"lovtd\">
                                              <div class=\"form-group form-group-sm\" style=\"width:100% !important;\">
                                                <select data-placeholder=\"Select...\" class=\"form-control chosen-select\" id=\"attchdFSCDocsRow_WWW123WWW_FileType\" style=\"min-width:70px !important;\">";
                            $valslctdArry = array("", "", "");
                            $dsplySzeArry = array("Signature", "Memo", "Other");
                            for ($y = 0; $y < count($dsplySzeArry); $y++) {
                                $nwRowHtml .= "<option value=\"$dsplySzeArry[$y]\" $valslctdArry[$y]>$dsplySzeArry[$y]</option>";
                            }
                            $nwRowHtml .= "</select>
                                              </div>
                                          </td>
                                          <td class=\"lovtd\">
                                                <button type=\"button\" class=\"btn btn-default\" style=\"margin: 0px !important;padding:0px 3px 2px 4px !important;\" onclick=\"uploadFileToFscDocs('attchdFSCDocsRow_WWW123WWW_DocFile','attchdFSCDocsRow_WWW123WWW_AttchdFSCDocsID','attchdFSCDocsRow_WWW123WWW_DocCtgryNm','attchdFSCDocsRow_WWW123WWW_FileType','attchdFSCDocsRow_WWW123WWW_TrnsType'," . $pkID . ");\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Download Document\">
                                                    <img src=\"cmn_images/openfileicon.png\" style=\"height:15px; width:auto; position: relative; vertical-align: middle;\"> Upload
                                                </button>
                                          </td>
                                          <td class=\"lovtd\">
                                                <button type=\"button\" class=\"btn btn-default\" style=\"margin: 0px !important;padding:0px 3px 2px 4px !important;\" onclick=\"delAttchdFscDoc('attchdFSCDocsRow__WWW123WWW');\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Delete Document\">
                                                    <img src=\"cmn_images/no.png\" style=\"height:15px; width:auto; position: relative; vertical-align: middle;\">
                                                </button>
                                          </td>
                                            <td class=\"lovtd\"> 
                                               &nbsp;
                                            </td>
                                        </tr>";
                            $nwRowHtml = urlencode($nwRowHtml);
                            if($recStatus != "Approved" && $recStatus != "Reviewed"){
                            ?> 
                            <div class="<?php echo $colClassType3; ?>" style="padding:0px 1px 0px 1px !important;"> 
                                <div class="col-md-12">
                                    <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="insertNewRowBe4('attchdFSCDocsTable', 0, '<?php echo $nwRowHtml; ?>');" style="width:100% !important;">
                                        <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                        New Document
                                    </button>
                                </div>
                            </div>
                            <?php  }  ?>
                            <div class="<?php echo $colClassType2; ?>" style="padding:0px 15px 0px 15px !important;">
                                <div class="input-group">
                                    <input class="form-control" id="attchdFSCDocsSrchFor" type = "text" placeholder="Search For" value="<?php
                                    echo trim(str_replace("%", " ", $srchFor));
                                    ?>" onkeyup="enterKeyFuncAttchdFscDocs(event, '', '#myFormsModalyBody', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&sbmtdHdrID=<?php echo $sbmtdHdrID; ?>&docType=<?php echo $trnsType; ?>&subPgNo=140.1&pAcctID=<?php echo $pAcctID; ?>');">
                                    <input id="attchdFSCDocsPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                                    <input id="attchdFSCDocsNwTrnsId" type = "hidden" value="<?php echo $sbmtdHdrID; ?>">
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getAttchdFscDocs('clear', '#myFormsModalyBody', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&sbmtdHdrID=<?php echo $sbmtdHdrID; ?>&docType=<?php echo $trnsType; ?>&subPgNo=140.1&pAcctID=<?php echo $pAcctID; ?>');">
                                        <span class="glyphicon glyphicon-remove"></span>
                                    </label>
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getAttchdFscDocs('', '#myFormsModalyBody', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&sbmtdHdrID=<?php echo $sbmtdHdrID; ?>&docType=<?php echo $trnsType; ?>&subPgNo=140.1&pAcctID=<?php echo $pAcctID; ?>');">
                                        <span class="glyphicon glyphicon-search"></span>
                                    </label> 
                                </div>
                            </div>
                            <div class="<?php echo $colClassType2; ?>">
                                <div class="input-group">
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>

                                    <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="attchdFSCDocsDsplySze" style="min-width:70px !important;">                            
                                        <?php
                                        $valslctdArry = array("", "", "", "", "",
                                            "", "", "");
                                        $dsplySzeArry = array(1, 5, 10, 15, 30, 50,
                                            100, 500, 1000);
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
                                            <a class="rhopagination" href="javascript:getAttchdFscDocs('previous', '#myFormsModalyBody', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&sbmtdHdrID=<?php echo $sbmtdHdrID; ?>&docType=<?php echo $trnsType; ?>&subPgNo=140.1&pAcctID=<?php echo $pAcctID; ?>');" aria-label="Previous">
                                                <span aria-hidden="true">&laquo;</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a class="rhopagination" href="javascript:getAttchdFscDocs('next', '#myFormsModalyBody', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&sbmtdHdrID=<?php echo $sbmtdHdrID; ?>&docType=<?php echo $trnsType; ?>&subPgNo=140.1&pAcctID=<?php echo $pAcctID; ?>');" aria-label="Next">
                                                <span aria-hidden="true">&raquo;</span>
                                            </a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                        <div class="row"> 
                            <div class="col-md-12">
                                <table class="table table-striped table-bordered table-responsive" id="attchdFSCDocsTable" cellspacing="0" width="100%" style="width:100%;min-width: 400px !important;">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Doc. Name/Description</th>
                                            <th>File Type</th>
                                            <th>&nbsp;</th>
                                            <th>&nbsp;</th>
                                            <?php if ($canVwRcHstry === true) { ?>
                                                <th>...</th>
                                            <?php } ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $cntr = 0;
                                        while ($row2 = loc_db_fetch_array($result2)) {
                                            $cntr += 1;
                                            //$doc_src = $ftp_base_db_fldr . "/Vms/" . $row2[3];
                                            $doc_src = "";
                                            $docTrsType = $row2[4];
                                            $docFileType = $row2[5];
                                            $doc_src = $ftp_base_db_fldr . "/Mcf/Transactions/" . $row2[3];
                                            $doc_src_encrpt = encrypt1($doc_src, $smplTokenWord1);
                                            
                                            
                                            if (file_exists($doc_src)) {
                                                //file exists!
                                            } else {
                                                //file does not exist.
                                                $doc_src_encrpt = "None";
                                            }
                                            ?>
                                            <tr id="attchdFSCDocsRow_<?php echo $cntr; ?>">                                    
                                                <td class="lovtd"><span><?php echo ($curIdx * $lmtSze) + ($cntr); ?></span></td>
                                                <td class="lovtd">                                                                   
                                                    <span><?php echo $row2[2]; ?></span>
                                                    <input type="hidden" class="form-control" aria-label="..." id="attchdFSCDocsRow<?php echo $cntr; ?>_AttchdFSCDocsID" value="<?php echo $row2[0]; ?>" style="width:100% !important;">                                              
                                                </td>
                                                <td class="lovtd">                                                                   
                                                    <span><?php echo $row2[5]; ?></span>                                           
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
                                                <td class="lovtd">
                                                    <?php 
                                                    if($recStatus != "Approved" && $recStatus != "Reviewed"){
                                                    ?>
                                                        <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delAttchdFscDoc('attchdFSCDocsRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Document">
                                                            <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                        </button>
                                                    <?php 
                                                    } else {
                                                    ?>
                                                        <span>&nbsp;</span>
                                                    <?php } ?>
                                                </td>
                                                <?php if ($canVwRcHstry === true) { ?>
                                                    <td class="lovtd">
                                                        <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" onclick="getRecHstry('<?php echo urlencode(encrypt1(($row2[0] . "|mcf.mcf_doc_attchmnts|attchmnt_id"), $smplTokenWord1)); ?>');" style="padding:2px !important;">
                                                            <img src="cmn_images/Information.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
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
                </fieldset>         
                <?php
            }
        }
    }
}
?>
