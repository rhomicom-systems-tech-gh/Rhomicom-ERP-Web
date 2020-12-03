<?php

header("content-type:application/json");
$sbmtdPersonID = isset($_POST['pKeyID']) ? cleanInputData($_POST['pKeyID']) : -1;
$orgNm = getOrgName($orgID);
$pstl = getOrgPstlAddrs($orgID);
$cntcts = getOrgContactNos($orgID);
$email = getOrgEmailAddrs($orgID);
$webSite = getOrgWebsite($orgID);
$slogan = getOrgSlogan($orgID);
$orgType = getPssblValNm((int) (getGnrlRecNm(
    "org.org_details",
    "org_id",
    "org_typ_id",
    $orgID
)));
$daReligionLbl = "Religion:";
if (strtoupper($orgType) == "CHURCH") {
    $daReligionLbl = "Place of Worship / Name of Service:";
}
if ($sbmtdPersonID <= 0) {
    $pkID = $prsnid;
} else {
    $pkID = $sbmtdPersonID;
}
$rcrdExst = prsn_Record_Exist($pkID);
$chngRqstExst = prsn_ChngRqst_Exist($pkID);
$result = null;
if ($sbmtdPersonID <= 0) {
    $result = get_SelfPrsnDet($pkID);
} else {
    $result = get_PrsnDet($pkID);
}
if ($pkID > 0) {
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
        $fullPemDest = $fldrPrfx . $tmpDest . $nwFileName;
        //logSessionErrs("TEST_PATH1:" . $fullPemDest);
        if (file_exists($ftp_src)) {
            copy("$ftp_src", "$fullPemDest");
        } else if (!file_exists($fullPemDest)) {
            $ftp_src = $fldrPrfx . 'cmn_images/image_up.png';
            copy("$ftp_src", "$fullPemDest");
        }
        $nwFileName = $tmpDest . $nwFileName;

        // QR CODE data 
        $name = str_replace("  ", " ", $row[3] . " " . $row[4] . " " . $row[6] . " " . $row[5]);
        $locIDNo = $row[1];
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

        // Append a new person to the file
        $dte = date('ymdhis');
        $ecnptDFlNm1 = encrypt1($dte . "_" . $lgn_num, $smplTokenWord1);
        $docFileNm = str_replace("__", "_", str_replace(" ", "_", str_replace(".", " ", $name))) . "_Profile_" . $dte;
        $nwPDFFileName = $docFileNm . ".pdf";
        $nwHtmFileName = $ecnptDFlNm1 . ".html";
        $fullPemDest = $fldrPrfx . "dwnlds/amcharts_2100/samples/" . $nwHtmFileName;
        $fullPemDest1 = $fldrPrfx . "dwnlds/amcharts_2100/samples/" . $nwPDFFileName;
        $file = $fullPemDest;
        // <link rel=\"stylesheet\" href=\"../amcharts/rpt.css\" type=\"text/css\">

        /* @media print {
          .invoice {
          font-size: 11px!important;
          overflow: hidden!important
          }

          .invoice footer {
          position: absolute;
          bottom: 10px;
          page-break-after: always
          }

          .invoice>div:last-child {
          page-break-before: always
          }
          }
          <style type=\"text/css\">
          @page {
          size: auto;
          margin: 0;
          }
          </style> */
        //logSessionErrs("TEST_PATH2:" . $app_url . $nwFileName);
        $current = "<!DOCTYPE html>
<html>
    <head>
        <meta charset=\"UTF-8\">
        <link rel=\"stylesheet\" href=\"style.css\" type=\"text/css\">
        <script src=\"../amcharts/amcharts.js\" type=\"text/javascript\"></script>
    </head>
    <body>
        <style type=\"text/css\">
@page {
    size: auto;   /* auto is the initial value */
    margin: 0;  /* this affects the margin in the printer settings */
}
fieldset, td {
  break-inside: avoid-page;
}
        * {
  -webkit-box-sizing: border-box;
  -moz-box-sizing: border-box;
  box-sizing: border-box;
}
*:before, *:after {
  -webkit-box-sizing: border-box;
  -moz-box-sizing: border-box;
  box-sizing: border-box;
}

img {
float: left;
padding-right: 1em;
height:75px;
  width:auto;
}

body {
  font-family: Tahoma, \"Helvetica Neue\", \"Helvetica\", \"Roboto\", \"Arial\", sans-serif;
  color: #000;
}

.basic_person_fs1{
    border: none !important;
    padding: 0px !important;
    margin: 0 0.5em 0.5em 0 !important;
    -webkit-box-shadow:  0px 0px 0px 0px #000;
    box-shadow:  0px 0px 0px 0px #000;
    min-height: 270px;
}

.basic_person_fs4{
    border: 1px solid #ddd !important;
    border-radius: 2px;
    padding: 5px !important;
    margin: 0 0.5em 0.5em 0 !important;
    -webkit-box-shadow:  0px 0px 0px 0px #000;
    box-shadow:  0px 0px 0px 0px #000;
}

.basic_person_lg{
    min-width:100%;
    width:100%;
    border: 1px solid #333 !important;
    border-radius: 2px;
    padding: 3px;
    margin: 0 0 5px 0 !important;
    -webkit-box-shadow:  0px 0px 0px 0px #000;
    box-shadow:  0px 0px 0px 0px #000;
    text-align: center; 
    font-weight:bold;  
    background-color:#aeaeae;
}
            #invoice{
                padding: 3px;
                font-size:16px;
            }

            .invoice {
                position: relative;
                background-color: #FFF;
                min-height: 680px;
                padding: 15px
            }

            .invoice header {
                padding: 10px 0;
                margin-bottom: 20px;
                border-bottom: 1px solid #3989c6
            }

            .invoice .company-details {
                text-align: right
            }

            .invoice .company-details .name {
                margin-top: 0;
                margin-bottom: 0
            }

            .invoice .contacts {
                margin-bottom: 20px
            }

            .invoice .invoice-to {
                text-align: left;
            }

            .invoice .invoice-to .to {
                margin-top: 0;
                margin-bottom: 0;
            }

            .invoice .invoice-details {
                text-align: right;
            }

            .invoice .invoice-details .invoice-id {
                margin-top: 0;
                color: #3989c6
            }

            .invoice footer {
                width: 100%;
                text-align: center;
                color: #777;
                border-top: 1px solid #aaa;
                padding: 8px 0
            }

            .col-md-4{
            font-weight:bold;
            float:left;
            }
            .col-lg-4{
            min-width:29%;
            width:29%;
            float:left;
            }
            .col-lg-6{
            min-width:69%;
            width:69%;
            float:left;
            }
          .col-lg-12{ min-width:100%;width:100%;}
          td {
  vertical-align: top;
  text-align: left;
}
th {
font-weight:bold;
}
        </style>
        <div id=\"invoice\">
            <div class=\"invoice overflow-auto\">
                <div style=\"min-width: 600px\">
                    <header>
                        <div class=\"row\">
                            <div class=\"col\">
                                <a target=\"_blank\" href=\"$app_url\">
                                    <img src=\"../images/" . $orgID . ".png\" data-holder-rendered=\"true\" />
                                </a>
                            </div>
                            <div class=\"col company-details\">
                                <h2 class=\"name\">
                                    <a target=\"_blank\" href=\"$app_cstmr_url\">
                                        $orgNm
                                    </a>
                                </h2>
                                <div>$pstl</div>
                                <div>$cntcts</div>
                                <div>$email</div>
                                <div>$webSite</div>
                            </div>
                        </div>
                    </header>
                    <main>
                        <table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" style=\"min-width:100%;width:100%;\">
                            <tbody>
                                <tr>
                                    <td>
                                        <fieldset class=\"basic_person_fs1\"><legend class=\"basic_person_lg\">Person's Picture</legend>
                                            <div style=\"\">
                                                <img src=\"$fldrPrfx" . "$nwFileName\" alt=\"...\" id=\"img1Test\" class=\"img-rounded center-block img-responsive\" style=\"height: auto !important; width: 210px !important;\">                                            
                                            </div>                                   
                                        </fieldset>
                                    </td>                                
                                    <td>
                                        <fieldset class=\"basic_person_fs1\"><legend class=\"basic_person_lg\">Personal Data</legend>
                                            <table border=\"0\" cellspacing=\"1\" cellpadding=\"2\">
                                                <tbody>
                                                    <tr>
                                                        <td class=\"col-md-4\">ID No:&nbsp;&nbsp;</td>
                                                        <td class=\"col-md-8\"><span>$row[1]</span></td>
                                                    </tr>
                                                    <tr>
                                                        <td class=\"col-md-4\">Title:&nbsp;&nbsp;</td>
                                                        <td class=\"col-md-8\"><span>$row[3]</span></td>
                                                    </tr>
                                                    <tr>
                                                        <td class=\"col-md-4\">First Name:&nbsp;&nbsp;</td>
                                                        <td class=\"col-md-8\"><span>$row[4]</span></td>
                                                    </tr>
                                                    <tr>
                                                        <td class=\"col-md-4\">Surname:&nbsp;&nbsp;</td>
                                                        <td class=\"col-md-8\"><span>$row[5]</span></td>
                                                    </tr>
                                                    <tr>
                                                        <td class=\"col-md-4\">Other Names:&nbsp;&nbsp;</td>
                                                        <td class=\"col-md-8\"><span>$row[6]</span></td>
                                                    </tr>
                                                    <tr>
                                                        <td class=\"col-md-4\">Gender:&nbsp;&nbsp;</td>
                                                        <td class=\"col-md-8\"><span>$row[8]</span></td>
                                                    </tr>
                                                    <tr>
                                                        <td class=\"col-md-4\">Marital Status:&nbsp;&nbsp;</td>
                                                        <td class=\"col-md-8\"><span>$row[9]</span></td>
                                                    </tr>
                                                    <tr>
                                                        <td class=\"col-md-4\">Date of Birth:&nbsp;&nbsp;</td>
                                                        <td class=\"col-md-8\"><span>$row[10]</span></td>
                                                    </tr>
                                                    <tr>
                                                        <td class=\"col-md-4\">Place of Birth:&nbsp;&nbsp;</td>
                                                        <td class=\"col-md-8\"><span>$row[11]</span></td>
                                                    </tr>
                                                    <tr>
                                                        <td class=\"col-md-4\">Nationality:&nbsp;&nbsp;</td>
                                                        <td class=\"col-md-8\"><span>$row[20]</span></td>
                                                    </tr>
                                                    <tr>
                                                        <td class=\"col-md-4\">Home Town:&nbsp;&nbsp;</td>
                                                        <td class=\"col-md-8\"><span>$row[19]</span></td>
                                                    </tr>
                                                    <tr>
                                                        <td class=\"col-md-4\">$daReligionLbl&nbsp;&nbsp;</td>
                                                        <td class=\"col-md-8\"><span>$row[12]</span></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </fieldset>
                                    </td>
                                </tr>  
                                <tr>
                                    <td>
                                        <fieldset class=\"basic_person_fs1\"><legend class=\"basic_person_lg\">QR Code</legend>
                                            <div>
                                                <img src=\"$fldrPrfx" . getQRCodeUrl($codeContents, $ecnptDFlNm . "_QR") . "\" alt=\"...\" id=\"imgQrCode\" class=\"img-thumbnail center-block img-responsive\" style=\"height: auto !important; width: 210px !important;\">                                            
                                            </div>                                       
                                        </fieldset>
                                    </td>         
                                    <td> 
                                        <fieldset class=\"basic_person_fs1\"><legend class=\"basic_person_lg\">Contact Information</legend>                                             
                                        <table border=\"0\" cellspacing=\"1\" cellpadding=\"2\">
                                                <tbody>
                                                    <tr>
                                                        <td class=\"col-md-4\">Workplace:&nbsp;&nbsp;</td>
                                                        <td class=\"col-md-8\"><span>$row[21]</span></td>
                                                    </tr>
                                                    <tr>
                                                        <td class=\"col-md-4\">Site/Branch:&nbsp;&nbsp;</td>
                                                        <td class=\"col-md-8\"><span>$row[22]</span></td>
                                                    </tr>
                                                    <tr>
                                                        <td class=\"col-md-4\">Email:&nbsp;&nbsp;</td>
                                                        <td class=\"col-md-8\"><span>$row[15]</span></td>
                                                    </tr>
                                                    <tr>
                                                        <td class=\"col-md-4\">Telephone:&nbsp;&nbsp;</td>
                                                        <td class=\"col-md-8\"><span>$row[16]</span></td>
                                                    </tr>
                                                    <tr>
                                                        <td class=\"col-md-4\">Mobile:&nbsp;&nbsp;</td>
                                                        <td class=\"col-md-8\"><span>$row[17]</span></td>
                                                    </tr>
                                                    <tr>
                                                        <td class=\"col-md-4\">Fax:&nbsp;&nbsp;</td>
                                                        <td class=\"col-md-8\"><span>$row[18]</span></td>
                                                    </tr>
                                                    <tr>
                                                        <td class=\"col-md-4\">Postal Address:&nbsp;&nbsp;</td>
                                                        <td class=\"col-md-8\"><span>$row[14]</span></td>
                                                    </tr>
                                                    <tr>
                                                        <td class=\"col-md-4\">Residential Address:&nbsp;&nbsp;</td>
                                                        <td class=\"col-md-8\"><span>$row[13]</span></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </fieldset>
                                 </td>
                                </tr>";

        $current .= "<tr>
                                 <td colspan=\"2\">
                                        <fieldset class=\"basic_person_fs1\"><legend class=\"basic_person_lg\"> PERSON'S RELATIONSHIP WITH THIS ORGANISATION </legend>                                           
                                        <table border=\"0\" cellspacing=\"1\" cellpadding=\"2\" style=\"min-width:100%;width:100%;\">
                                                <thead>
                                                   <tr>
                                                     <th>Relationship Type</th>
                                                     <th>Relationship Type Reason</th>
                                                     <th>Further Details</th>
                                                     <th>Start Date</th>
                                                     <th>End Date</th>
                                                   </tr>
                                                </thead>
                                                <tbody>";
        $resultPTyp = getAllPrsnTypsRpt($pkID);
        while ($rowPTyp = loc_db_fetch_array($resultPTyp)) {
            $current .= "<tr>
                                    <td><span>$rowPTyp[0]</span></td>
                                    <td><span>$rowPTyp[1]</span></td>
                                    <td><span>$rowPTyp[2]</span></td>
                                    <td><span>$rowPTyp[3]</span></td>
                                    <td><span>$rowPTyp[4]</span></td>
                                </tr>";
        }
        $current .= "
                                        </tbody>
                                      </table>
                                    </fieldset>
                                 </td>
                                </tr>";

        $resultOinf1 = getAllNtnltyRpt($pkID);
        $oinfCnt1 = loc_db_num_rows($resultOinf1);
        if ($oinfCnt1 > 0) {
            $current .= " <tr>
                                 <td colspan=\"2\" style=\"padding-top:10px !important;\">
                                        <fieldset class=\"basic_person_fs4\"><legend class=\"basic_person_lg\"> NATIONAL ID CARDS </legend>                                           
                                        <table border=\"0\" cellspacing=\"1\" cellpadding=\"2\" style=\"min-width:100%;width:100%;\">
                                                <thead>
                                                   <tr>
                                                     <th>Country</th>
                                                     <th>ID Type</th>
                                                     <th>ID Number</th>
                                                     <th>Date Issued</th>
                                                     <th>Expiry Date</th>
                                                     <th>Other Information</th>
                                                   </tr>
                                                </thead>
                                                <tbody>";
            while ($rowPTyp = loc_db_fetch_array($resultOinf1)) {
                $current .= "<tr>
                                    <td><span>$rowPTyp[0]</span></td>
                                    <td><span>$rowPTyp[1]</span></td>
                                    <td><span>$rowPTyp[2]</span></td>
                                    <td><span>$rowPTyp[4]</span></td>
                                    <td><span>$rowPTyp[5]</span></td>
                                    <td><span>$rowPTyp[6]</span></td>
                                </tr>";
            }
            $current .= "
                                        </tbody>
                                      </table>
                                    </fieldset>
                                 </td>
                                </tr>";
        }
        $resultOinf2 = getAllRltvsRpt($pkID);
        $oinfCnt2 = loc_db_num_rows($resultOinf2);
        if ($oinfCnt2 > 0) {
            $current .= " <tr>
                                 <td colspan=\"2\" style=\"padding-top:10px !important;\">
                                        <fieldset class=\"basic_person_fs4\"><legend class=\"basic_person_lg\"> NATIONAL ID CARDS </legend>                                           
                                        <table border=\"0\" cellspacing=\"1\" cellpadding=\"2\" style=\"min-width:100%;width:100%;\">
                                                <thead>
                                                   <tr>
                                                     <th>Relative's ID No.</th>
                                                     <th>Relative's Full Name</th>
                                                     <th>Relation Type</th>
                                                   </tr>
                                                </thead>
                                                <tbody>";
            while ($rowPTyp = loc_db_fetch_array($resultOinf2)) {
                $current .= "<tr>
                                    <td><span>$rowPTyp[0]</span></td>
                                    <td><span>$rowPTyp[1]</span></td>
                                    <td><span>$rowPTyp[2]</span></td>
                                </tr>";
            }
            $current .= "
                                        </tbody>
                                      </table>
                                    </fieldset>
                                 </td>
                                </tr>";
        }
        $resultOinf3 = getAllEducRpt($pkID);
        $oinfCnt3 = loc_db_num_rows($resultOinf3);
        if ($oinfCnt3 > 0) {
            $current .= " <tr>
                                 <td colspan=\"2\" style=\"padding-top:10px !important;\">
                                        <fieldset class=\"basic_person_fs4\"><legend class=\"basic_person_lg\"> EDUCATIONAL BACKGROUND </legend>                                           
                                        <table border=\"0\" cellspacing=\"1\" cellpadding=\"2\" style=\"min-width:100%;width:100%;\">
                                                <thead>
                                                   <tr>
                                                     <th>Course Name</th>
                                                     <th>School/Institution</th>
                                                     <th>Certificate Obtained</th>
                                                     <th>Date Obtained</th>
                                                   </tr>
                                                </thead>
                                                <tbody>";
            while ($rowPTyp = loc_db_fetch_array($resultOinf3)) {
                $current .= "<tr>
                                    <td><span>$rowPTyp[0]</span></td>
                                    <td><span>$rowPTyp[1]</span></td>
                                    <td><span>$rowPTyp[4]</span></td>
                                    <td><span>$rowPTyp[5]</span></td>
                                </tr>";
            }
            $current .= "
                                        </tbody>
                                      </table>
                                    </fieldset>
                                 </td>
                                </tr>";
        }
        $resultOinf4 = getAllWrkExpRpt($pkID);
        $oinfCnt4 = loc_db_num_rows($resultOinf4);
        if ($oinfCnt4 > 0) {
            $current .= " <tr>
                                 <td colspan=\"2\" style=\"padding-top:10px !important;\">
                                        <fieldset class=\"basic_person_fs4\"><legend class=\"basic_person_lg\"> WORK EXPERIENCE </legend>                                           
                                        <table border=\"0\" cellspacing=\"1\" cellpadding=\"2\" style=\"min-width:100%;width:100%;\">
                                                <thead>
                                                   <tr>
                                                     <th>Job Title</th>
                                                     <th>Institution</th>
                                                     <th>Start Date</th>
                                                     <th>End Date</th>
                                                     <th>Remarks</th>
                                                   </tr>
                                                </thead>
                                                <tbody>";
            while ($rowPTyp = loc_db_fetch_array($resultOinf4)) {
                $current .= "<tr>
                                    <td><span>$rowPTyp[0]</span></td>
                                    <td><span>$rowPTyp[1]</span></td>
                                    <td><span>$rowPTyp[2]</span></td>
                                    <td><span>$rowPTyp[3]</span></td>
                                    <td><span>$rowPTyp[4]</span></td>
                                </tr>";
            }
            $current .= "
                                        </tbody>
                                      </table>
                                    </fieldset>
                                 </td>
                                </tr>";
        }
        $resultOinf5 = getAllSkillsRpt($pkID);
        $oinfCnt5 = loc_db_num_rows($resultOinf5);
        if ($oinfCnt5 > 0) {
            $current .= " <tr>
                                 <td colspan=\"2\" style=\"padding-top:10px !important;\">
                                        <fieldset class=\"basic_person_fs4\"><legend class=\"basic_person_lg\"> SKILLS/HOBBIES/ATTITUDE </legend>                                           
                                        <table border=\"0\" cellspacing=\"1\" cellpadding=\"2\" style=\"min-width:100%;width:100%;\">
                                                <thead>
                                                   <tr>
                                                     <th>Languages</th>
                                                     <th>Hobbies/Interests</th>
                                                     <th>Conduct/Attitude</th>
                                                     <th>From</th>
                                                     <th>To</th>
                                                   </tr>
                                                </thead>
                                                <tbody>";
            while ($rowPTyp = loc_db_fetch_array($resultOinf5)) {
                $current .= "<tr>
                                    <td><span>$rowPTyp[0]</span></td>
                                    <td><span>$rowPTyp[1]</span></td>
                                    <td><span>$rowPTyp[2]</span></td>
                                    <td><span>$rowPTyp[3]</span></td>
                                    <td><span>$rowPTyp[4]</span></td>
                                </tr>";
            }
            $current .= "
                                        </tbody>
                                      </table>
                                    </fieldset>
                                 </td>
                                </tr>";
        }
        $resultOinf6 = getAllDivsRpts($pkID);
        $oinfCnt6 = loc_db_num_rows($resultOinf6);
        if ($oinfCnt6 > 0) {
            $current .= " <tr>
                                 <td colspan=\"2\" style=\"padding-top:10px !important;\">
                                        <fieldset class=\"basic_person_fs4\"><legend class=\"basic_person_lg\"> GROUPS/ASSOCIATIONS </legend>                                           
                                        <table border=\"0\" cellspacing=\"1\" cellpadding=\"2\" style=\"min-width:100%;width:100%;\">
                                                <thead>
                                                   <tr>
                                                     <th>Group Name</th>
                                                     <th>Group Type</th>
                                                     <th>From</th>
                                                     <th>To</th>
                                                   </tr>
                                                </thead>
                                                <tbody>";
            while ($rowPTyp = loc_db_fetch_array($resultOinf6)) {
                $current .= "<tr>
                                    <td><span>$rowPTyp[0]</span></td>
                                    <td><span>$rowPTyp[4]</span></td>
                                    <td><span>$rowPTyp[1]</span></td>
                                    <td><span>$rowPTyp[2]</span></td>
                                </tr>";
            }
            $current .= "
                                        </tbody>
                                      </table>
                                    </fieldset>
                                 </td>
                                </tr>";
        }
        $resultOinf7 = getAllPositionsRpt($pkID);
        $oinfCnt7 = loc_db_num_rows($resultOinf7);
        if ($oinfCnt7 > 0) {
            $current .= " <tr>
                                 <td colspan=\"2\" style=\"padding-top:10px !important;\">
                                        <fieldset class=\"basic_person_fs4\"><legend class=\"basic_person_lg\"> POSITIONS </legend>                                           
                                        <table border=\"0\" cellspacing=\"1\" cellpadding=\"2\" style=\"min-width:100%;width:100%;\">
                                                <thead>
                                                   <tr>
                                                     <th>Position</th>
                                                     <th>Group Type</th>
                                                     <th>From</th>
                                                     <th>To</th>
                                                   </tr>
                                                </thead>
                                                <tbody>";
            while ($rowPTyp = loc_db_fetch_array($resultOinf7)) {
                $current .= "<tr>
                                    <td><span>$rowPTyp[1]</span></td>
                                    <td><span>$rowPTyp[6]</span></td>
                                    <td><span>$rowPTyp[2]</span></td>
                                    <td><span>$rowPTyp[3]</span></td>
                                </tr>";
            }
            $current .= "
                                        </tbody>
                                      </table>
                                    </fieldset>
                                 </td>
                                </tr>";
        }
        $resultOinf8 = getAllSitesRpts($pkID);
        $oinfCnt8 = loc_db_num_rows($resultOinf8);
        if ($oinfCnt8 > 0) {
            $current .= " <tr>
                                 <td colspan=\"2\" style=\"padding-top:10px !important;\">
                                        <fieldset class=\"basic_person_fs4\"><legend class=\"basic_person_lg\"> SITES/LOCATIONS </legend>                                           
                                        <table border=\"0\" cellspacing=\"1\" cellpadding=\"2\" style=\"min-width:100%;width:100%;\">
                                                <thead>
                                                   <tr>
                                                     <th>Site/Location</th>
                                                     <th>Type</th>
                                                     <th>From</th>
                                                     <th>To</th>
                                                   </tr>
                                                </thead>
                                                <tbody>";
            while ($rowPTyp = loc_db_fetch_array($resultOinf8)) {
                $current .= "<tr>
                                    <td><span>$rowPTyp[1]</span></td>
                                    <td><span>$rowPTyp[5]</span></td>
                                    <td><span>$rowPTyp[2]</span></td>
                                    <td><span>$rowPTyp[3]</span></td>
                                </tr>";
            }
            $current .= "
                                        </tbody>
                                      </table>
                                    </fieldset>
                                 </td>
                                </tr>";
        }
        $resultOinf9 = getAllJobsRpt($pkID);
        $oinfCnt9 = loc_db_num_rows($resultOinf9);
        if ($oinfCnt9 > 0) {
            $current .= " <tr>
                                 <td colspan=\"2\" style=\"padding-top:10px !important;\">
                                        <fieldset class=\"basic_person_fs4\"><legend class=\"basic_person_lg\"> JOBS </legend>                                           
                                        <table border=\"0\" cellspacing=\"1\" cellpadding=\"2\" style=\"min-width:100%;width:100%;\">
                                                <thead>
                                                   <tr>
                                                     <th>Job</th>
                                                     <th>From</th>
                                                     <th>To</th>
                                                   </tr>
                                                </thead>
                                                <tbody>";
            while ($rowPTyp = loc_db_fetch_array($resultOinf9)) {
                $current .= "<tr>
                                    <td><span>$rowPTyp[1]</span></td>
                                    <td><span>$rowPTyp[2]</span></td>
                                    <td><span>$rowPTyp[3]</span></td>
                                </tr>";
            }
            $current .= "
                                        </tbody>
                                      </table>
                                    </fieldset>
                                 </td>
                                </tr>";
        }
        $resultOinf10 = getAllGradesRpt($pkID);
        $oinfCnt10 = loc_db_num_rows($resultOinf10);
        if ($oinfCnt10 > 0) {
            $current .= " <tr>
                                 <td colspan=\"2\" style=\"padding-top:10px !important;\">
                                        <fieldset class=\"basic_person_fs4\"><legend class=\"basic_person_lg\"> GRADES </legend>                                           
                                        <table border=\"0\" cellspacing=\"1\" cellpadding=\"2\" style=\"min-width:100%;width:100%;\">
                                                <thead>
                                                   <tr>
                                                     <th>Grade</th>
                                                     <th>From</th>
                                                     <th>To</th>
                                                   </tr>
                                                </thead>
                                                <tbody>";
            while ($rowPTyp = loc_db_fetch_array($resultOinf10)) {
                $current .= "<tr>
                                    <td><span>$rowPTyp[1]</span></td>
                                    <td><span>$rowPTyp[2]</span></td>
                                    <td><span>$rowPTyp[3]</span></td>
                                </tr>";
            }
            $current .= "
                                        </tbody>
                                      </table>
                                    </fieldset>
                                 </td>
                                </tr>";
        }
        $addtnlDataExst = prsn_AddtnlRecord_Exist($pkID);
        if ($addtnlDataExst > 0) {
            $current .= "<tr>
                                 <td colspan=\"2\">";
            $rcrdExst = prsn_Record_Exist($pkID);
            $result = get_PrsExtrDataGrps($orgID);
            while ($row = loc_db_fetch_array($result)) {
                $current .= "<div class=\"row\">
                        <div class=\"col-md-12\" style=\"padding-top:10px !important;\">
                            <fieldset class=\"basic_person_fs4\">
                                <legend class=\"basic_person_lg\">" . $row[0] . "</legend>";
                $result1 = get_PrsExtrDataGrpCols($row[0], $orgID);
                $cntr1 = 0;
                $gcntr1 = 0;
                $cntr1Ttl = loc_db_num_rows($result1);
                while ($row1 = loc_db_fetch_array($result1)) {
                    if ($row1[7] == "Tabular") {
                        $current .= "<div class=\"row\">
                                            <div  class=\"col-md-12\">
                                                <table id=\"extDataTblCol" . $row1[1] . "\" class=\"table table-striped table-bordered table-responsive extPrsnDataTblRO\" cellspacing=\"0\" width=\"100%\" style=\"width:100%;\"><thead><th>No.</th>";
                        $fieldHdngs = $row1[11];
                        $arry1 = explode(",", $fieldHdngs);
                        $cntr = count($arry1);
                        for ($i = 0; $i < $row1[9]; $i++) {
                            if ($i <= $cntr - 1) {
                                $current .= "<th>" . $arry1[$i] . "</th>";
                            } else {
                                $current .= "<th>&nbsp;</th>";
                            }
                        }
                        $current .= "</thead>
                                                    <tbody>";
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
                        $jActl = 0;
                        for ($j = 0; $j < $cntr3; $j++) {
                            if (trim($arry3[$j], "~") == "") {
                                continue;
                            }
                            $current .= "<tr><td>" . ($jActl + 1) . "</td>";
                            $arry2 = explode("~", $arry3[$j]);
                            $cntr2 = count($arry2);
                            for ($i = 0; $i < $row1[9]; $i++) {
                                if ($i <= $cntr2 - 1) {
                                    $current .= "<td>" . $arry2[$i] . "</td>";
                                } else {
                                    $current .= "<td>&nbsp;</td>";
                                }
                            }
                            $current .= "</tr>";
                        }
                        $current .= "</tbody>
                                                </table>
                                            </div>
                                        </div>";
                    } else {
                        if ($gcntr1 == 0) {
                            $gcntr1 += 1;
                        }
                        if (($cntr1 % 2) == 0) {
                            $current .= "<div class=\"row\">";
                        }
                        $current .= "<div class=\"col-md-6\"> 
                                                <div class=\"form-group form-group-sm\"> 
                                                    <label class=\"control-label col-md-4\">" . $row1[2] . ":</label>
                                                    <div class=\"col-md-8\">
                                                        <span>";
                        if ($sbmtdPersonID <= 0) {
                            $current .= get_PrsExtrData_Self($pkID, $row1[1]);
                        } else {
                            $current .= get_PrsExtrData($pkID, $row1[1]);
                        }
                        $current .= "</span><br/>
                                                    </div>
                                                </div>
                                            </div>";
                        $cntr1 += 1;
                        if (($cntr1 % 2) == 0 || $cntr1 == ($cntr1Ttl)) {
                            $cntr1 = 0;
                            $current .= "</div>";
                        }
                    }
                }
                if ($gcntr1 == 1) {
                    $gcntr1 = 0;
                }
                $current .= "</fieldset><br/>
                        </div>
                    </div>";
            }
            $current .= "</td>
                                </tr>";
        }
        $current .= "</tbody>
                        </table>
                    </main>
                    <footer>
                        $orgNm &nbsp...   $slogan
                    </footer>
                </div>
                <!--DO NOT DELETE THIS div. IT is responsible for showing footer always at the bottom-->
                <div></div>
            </div>
        </div>
    </body>
</html>";
        // Write the contents back to the file
        file_put_contents($file, $current, FILE_APPEND);
        /* $cmd = '/usr/bin/xvfb-run --server-args="-screen 0, 1920x1080x24" /usr/bin/wkhtmltopdf '. $fullPemDest . " " . $fullPemDest1;
          logSsnErrs($cmd);
          $exitErrMsg = execInBackground($cmd); */
        //$cmd = "bash wkhtmltopdf.sh " . $fullPemDest . " " . $fullPemDest1;
        $cmd = $browserPDFCmd . " --headless --no-sandbox --disable-gpu --print-to-pdf=\"$fullPemDest1\" " . $fullPemDest;

        $exitErrMsg = "";
        $rhoAPIhost = parse_url($rhoAPIUrl, PHP_URL_HOST);
        $rhoAPIport = parse_url($rhoAPIUrl, PHP_URL_PORT);

        $errno = 0;
        $errstr = "";
        set_error_handler("rhoErrorHandler3");
        $rc = @fsockopen($rhoAPIhost, $rhoAPIport, $errno, $errstr, 1);
        if (is_resource($rc)) {
            $rslt = rhoPOSTToAPI(
                $rhoAPIUrl . '/getChromePDF',
                array(
                    'browserPDFCmd' => $browserPDFCmd,
                    'pdfPath' => $fullPemDest1,
                    'htmPath' => $fullPemDest
                )
            );
            $exitErrMsg = $rslt;
        } else {
            //logSsnErrs($cmd);
            $exitErrMsg = shellExecInBackground($ftp_base_db_fldr . "/bin", $cmd);
        }
        /*$waitCntr = 1/0;
        $waitCntr = 0;
        while (!file_exists($fullPemDest1) && $waitCntr < 5) {
            $waitCntr++;
            sleep(2);
        }*/
        if (file_exists($fullPemDest1)) {
            $exitErrMsg = "Success:" . $exitErrMsg;
        }
        /* $exitErrMsg = cnvrtHtmlToPDFURL($fullPemDest, $fullPemDest1); */
        $arr_content['percent'] = 100;
        $arr_content['mailTo'] = urlencode($email);
        $arr_content['mailCc'] = urlencode($admin_email);
        $arr_content['mailSubject'] = urlencode("Profile of " . $name . " (" . $locIDNo . ")");
        $message = "<p style=\"font-family: Calibri;font-size:18px;\">Dear " . $name . ", <br/><br/>"
            . "Please find attached a Summary of your Personal Data in our System: " . $app_name .
            "<br/>Please do not hesitate to contact us should any of the information in the attached be inaccurate.<br/><br/>"
            . "Thank you!</p>";
        $arr_content['bulkMessageBody'] = urlencode($message);
        $arr_content['URL'] = urlencode($app_url . "dwnlds/amcharts_2100/samples/" . $nwPDFFileName);
        $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>" . $exitErrMsg . "</span>";
        echo json_encode($arr_content);
        exit();
    }
} else {
    $exitErrMsg = "Please select a Valid Person First!";
    $arr_content['percent'] = 100;
    $arr_content['mailTo'] = urlencode($admin_email);
    $arr_content['mailCc'] = urlencode($admin_email);
    $arr_content['mailSubject'] = "ERROR Generating Profile";
    $arr_content['bulkMessageBody'] = urlencode($exitErrMsg);
    $arr_content['URL'] = $app_url;
    $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>" . $exitErrMsg . "</span>";
}
