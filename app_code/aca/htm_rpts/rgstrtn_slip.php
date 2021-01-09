<?php

header("content-type:application/json");
$acaRgstratnPrsID = isset($_POST['pKeyID']) ? (float)cleanInputData($_POST['pKeyID']) : -1;
$sbmtdAcaSttngsID = isset($_POST['sbmtdAcaSttngsID']) ? (float)cleanInputData($_POST['sbmtdAcaSttngsID']) : -1;
if ($sbmtdAcaSttngsID > 0) {
    $acaRgstratnPrsID = (float) getGnrlRecNm("aca.aca_prsns_acdmc_sttngs", "acdmc_sttngs_id", "person_id", $sbmtdAcaSttngsID);
}
$orgRslt = getOrgRptDetails($orgID);
$orgNm = "";
$pstl = "";
$cntcts = "";
$email = "";
$webSite = "";
$slogan = "";
$orgType = "";
$orgLogo = "";
while ($orgRw = loc_db_fetch_array($orgRslt)) {
    $orgNm = $orgRw[0];
    $pstl = $orgRw[1];
    $cntcts = $orgRw[2];
    $email = $orgRw[3];
    $webSite = $orgRw[4];
    $slogan = $orgRw[5];
    $orgType = $orgRw[7];
    $orgLogo = $orgRw[8];
}

$pkID = $acaRgstratnPrsID;
$acaRgstratnPrsLocID = "";
$acaRgstratnPrsName = "";
$acaRgstratnPrsContacts = "";
$acaRgstratnPrsType = "";
$acaRgstratnPrsEmail = "";
if ($pkID > 0) {
    $acaRgstratnPrsID = $pkID;
    $result1 = getAcaPrsnInfo($pkID);
    while ($row1 = loc_db_fetch_array($result1)) {
        $acaRgstratnPrsID = (float) $row1[0];
        $acaRgstratnPrsLocID = $row1[1];
        $acaRgstratnPrsName = $row1[2];
        $acaRgstratnPrsContacts = trim($row1[6] . ", " . $row1[7], ", ");
        $acaRgstratnPrsType = $row1[10];
        $acaRgstratnPrsEmail = $row1[5];
    }
}
$acaSttngsLineID = -1;
$qShwCrntOnly = true;
$acaSttngsClssNm = "";
$acaSttngsPrdNm = "";
$acaSttngsPrdStrtDte = "";
$acaSttngsCrseNm = "";
$acaSttngsPrdEndDte = "";
//$cntrRndm = getRandomNum(5000, 9999);
$cntr = 0;
$acaRgstratnSttngsID = $sbmtdAcaSttngsID;
$acaRgstratnSbjctID = -1;
$resultRw = null;
if ($sbmtdAcaSttngsID <= 0) {
    $resultRw = get_AcaSttngsClasses($acaRgstratnPrsID, 0, 10000, "Name", "%", $qShwCrntOnly);
} else {
    $resultRw = get_AcaSttngsClasses2($sbmtdAcaSttngsID, 0, 10000, "Name", "%");
}
while ($rowRw = loc_db_fetch_array($resultRw)) {
    $acaSttngsLineID = (float) $rowRw[0];
    $acaSttngsClassID = (float) $rowRw[1];
    $acaSttngsCourseID = (float) $rowRw[2];
    $acaSttngsPeriodID = (float) $rowRw[5];
    if ($cntr == 0) {
        $acaRgstratnSttngsID = (float) $rowRw[0];
    }
    $acaSttngsClssNm = $rowRw[4];
    $acaSttngsCrseNm = $rowRw[3];
    $acaSttngsPrdNm = $rowRw[6];
    $acaSttngsPrdStrtDte = cnvrtYMDToDMY($rowRw[7]);
    $acaSttngsPrdEndDte = cnvrtYMDToDMY($rowRw[8]);
    break;
}

$dte = date('ymdhis');
$ecnptDFlNm = encrypt1("Registration_Slip_" . $dte . "_" . $lgn_num, $smplTokenWord1);
$nwPDFFileName = "Registration_Slip_" . $acaRgstratnSttngsID . ".pdf";
$nwHtmFileName = $ecnptDFlNm . ".html";
$fullPemDest = $fldrPrfx . "dwnlds/amcharts_2100/samples/" . $nwHtmFileName;
$fullPemDest1 = $fldrPrfx . "dwnlds/amcharts_2100/samples/" . $nwPDFFileName;
$file = $fullPemDest;


$html = "<!DOCTYPE html>
<html>
    <head>
        <style type=\"text/css\">
            @page {
                size: auto;   /* auto is the initial value */
                margin: 0;  /* this affects the margin in the printer settings */
            }
            * {
              box-sizing: border-box;
            }
            body {
              background: #fefefe;
              font-family: \"Open Sans\", arial, times, tahoma;
              font-size:12px;
            }
            #mainreport{
                padding: 10px 25px 10px 25px;
            }             
            .orgLogoTblStyle {
                border-collapse: collapse;
                margin-bottom: 10px;
                min-width:100%;
                width:100%;
                border:1px solid #fff;
              }
            .orgLogoDivStyle {
              border: 1px solid #ddd;
              float:right;
              width:80%;
              min-height:100px;
              height:100px;
              border-radius:5px;
            }
             .hdrDivStyle{
                margin-bottom: 10px;
                min-width:100%;
                width:100%;
                border:1px solid #ddd;
                border-radius:3px;
                padding: 3px;
                }                
            .hdrTblStyle {
                border-collapse: collapse;
                min-width:100%;
                width:100%;
              }              
            .hdrTblStyle td, .hdrTblStyle th {
              padding: 1px;
            }
            .detlTblStyle {
                border-collapse: collapse;
                margin-bottom: 10px;
                min-width:100%;
                width:100%;
              }
            .detlTblStyle td, .detlTblStyle th {
              border: 1px solid #333;
              padding: 3px;
            }
            .detlTblStyle caption {
                border-width: 1px;
                padding: 8px;
                border-style: solid;
                border-color: #ccc;
                background-color: #efefef;
                margin-bottom: 1px;
                font-weight: bold;
                font-size: 16px;
                font-style: italic;
                color: #333;
            }
            .ftrTblStyle {
                border-collapse: collapse;
                margin-bottom: 10px;
                min-width:100%;
                width:100%;
              }
            .ftrTblStyle td, .ftrTblStyle th {
              border: 1px solid #ccc;
              padding: 4px;
            } 
            th {
                height: 35px;
              }
        </style>
    </head>
    <body>
        <div id=\"mainreport\">
            <div class=\"row\">
                <table class=\"orgLogoTblStyle\">
                    <tbody>
                        <tr>
                            <td style=\"max-width:25%;width:25%;padding:5px;\">
                                <div class=\"col\">
                                    <a target=\"_blank\" href=\"$app_url\">
                                        <img src=\"../images/" . $orgLogo . "\" data-holder-rendered=\"true\" style=\"height:100px !important;width:auto;\"/>
                                    </a>
                                </div>
                            </td>
                            <td style=\"min-width:50%;width:50%;text-align:center;\">
                                <div class=\"col company-details\">
                                    <h3 class=\"name\">$orgNm</h3>
                                    <div>$pstl</div>
                                    <div>$cntcts</div>
                                    <div>$email</div>
                                    <div>$webSite</div>
                                </div>
                            </td>
                            <td align=\"right\" style=\"max-width:25%;width:25%;padding:5px;\">
                                <div class=\"orgLogoDivStyle\" >
                                    &nbsp;
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class=\"hdrDivStyle\">
                <table class=\"hdrTblStyle\">
                    <tbody>
                        <tr>
                            <td style=\"max-width:20%;width:20%;font-weight:bold;\">ID No.:</td>
                            <td style=\"max-width:30%;width:30%;\">$acaRgstratnPrsLocID</td>
                            <td style=\"max-width:20%;width:20%;font-weight:bold;\">$groupLabel:</td>
                            <td style=\"max-width:30%;width:30%;\">$acaSttngsClssNm</td>
                        </tr>
                        <tr>
                            <td style=\"max-width:20%;width:20%;font-weight:bold;\">Full Name:</td>
                            <td style=\"max-width:30%;width:30%;\">$acaRgstratnPrsName</td>
                            <td style=\"max-width:20%;width:20%;font-weight:bold;\">Assessment Period:</td>
                            <td style=\"max-width:30%;width:30%;\">$acaSttngsPrdNm</td>
                        </tr>
                        <tr>
                            <td style=\"max-width:20%;width:20%;font-weight:bold;\">Person Type:</td>
                            <td style=\"max-width:30%;width:30%;\">$acaRgstratnPrsType</td>
                            <td style=\"max-width:20%;width:20%;font-weight:bold;\">Email:</td>
                            <td style=\"max-width:30%;width:30%;\">$acaRgstratnPrsEmail</td>
                        </tr>
                        <tr>
                            <td style=\"max-width:20%;width:20%;font-weight:bold;\">Contact Nos.:</td>
                            <td style=\"max-width:30%;width:30%;\">$acaRgstratnPrsContacts</td>
                            <td style=\"max-width:20%;width:20%;font-weight:bold;\">Period Begins:</td>
                            <td style=\"max-width:30%;width:30%;\">$acaSttngsPrdStrtDte</td>
                        </tr>
                        <tr>
                            <td style=\"max-width:20%;width:20%;font-weight:bold;\">$courseLabel:</td>
                            <td style=\"max-width:30%;width:30%;\">$acaSttngsCrseNm</td>
                            <td style=\"max-width:20%;width:20%;font-weight:bold;\">Period End Date:</td>
                            <td style=\"max-width:30%;width:30%;\">$acaSttngsPrdEndDte</td>
                        </tr>";
$html .= "</tbody>
                </table>
            </div>
            <div class=\"row\">
                <table class=\"detlTblStyle\">
                    <caption>Registration Slip</caption>
                    <thead>
                        <tr>
                            <th style=\"max-width:30px !important;width:30px !important;text-align:center;\">No.</th>
                            <th style=\"min-width:205px !important;text-align:left;\">$sbjctLabel</th>
                            <th style=\"max-width:90px;width:90px;\">Core/ Elective</th>
                            <th style=\"max-width:90px;width:90px;text-align:right;\">$moduleType2Wght</th>
                        </tr>
                    </thead>
                    <tbody>";

$resultRw2 = get_AcaSttngsSbjcts($acaRgstratnSttngsID, 0, 10000, "Name", "%");
$mkReadOnly = "";
$cntr = 0;
$curIdx = 0;
$ttlWeight = 0;
$colscntr = 0;
while ($rowRw = loc_db_fetch_array($resultRw2)) {
    $sttngsSbjctLnID = (float) $rowRw[0];
    $sttngsSbjctID = (float) $rowRw[1];
    $sttngsSbjctLnNm = $rowRw[4];
    $clssSbjctCoreElect = $rowRw[2];
    $clssSbjctWeight = (float) $rowRw[3];
    $ttlWeight += $clssSbjctWeight;
    $cntr += 1;
    $html .= "<tr>
                <th style=\"max-width:45px !important;width:45px !important;text-align:center;\">$cntr</th>
                <th style=\"min-width:205px !important;text-align:left;\">$sttngsSbjctLnNm</th>
                <th style=\"max-width:90px;width:90px;\">$clssSbjctCoreElect</th>
                <th style=\"max-width:90px;width:90px;text-align:right;\">$clssSbjctWeight</th>
            </tr>";
    $colscntr++;
}
$html .= "</tbody>
          <tfoot>";

$html .= "<tr>
          <th style=\"max-width:45px !important;width:45px !important;text-align:center;\">&nbsp;</th>
          <th style=\"min-width:205px !important;text-align:left;\">Total $moduleType2Wght</th>
          <th style=\"max-width:90px;width:90px;\">&nbsp;</th>
          <th style=\"max-width:90px !important;text-align:right;\">$ttlWeight</th>
      </tr>";
$html .= " </tfoot>
                </table>
            </div>
        </div>
    </body>
</html>";


// Write the contents back to the file
file_put_contents($file, $html, FILE_APPEND);
$exitErrMsg = "";
$cmd = $browserPDFCmd . " --headless --no-sandbox --disable-gpu --print-to-pdf=\"$fullPemDest1\" " . $fullPemDest;
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
/*$waitCntr = 0;
while (!file_exists($fullPemDest1) && $waitCntr < 5) {
    $waitCntr++;
    sleep(2);
}*/
if (file_exists($fullPemDest1)) {
    $exitErrMsg = "Success:" . $exitErrMsg;
}
$arr_content['percent'] = 100;
$arr_content['mailTo'] = urlencode($email);
$arr_content['mailCc'] = urlencode($admin_email);
$arr_content['mailSubject'] = urlencode("Registration Slip (" . $acaSttngsPrdNm . ")");
$message = "<p style=\"font-family: Calibri;font-size:18px;\">Dear " . $acaRgstratnPrsName . ", <br/><br/>"
    . "Please find attached the Slip for a Registration done in our System: " . $app_name .
    "<br/>Please do not hesitate to contact us should any of the information in the attached be inaccurate.<br/><br/>"
    . "Thank you!</p>";
$arr_content['bulkMessageBody'] = urlencode($message);
$arr_content['URL'] = urlencode($app_url . "dwnlds/amcharts_2100/samples/" . $nwPDFFileName);
$arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>" . $exitErrMsg . "</span>";
echo json_encode($arr_content);
exit();
