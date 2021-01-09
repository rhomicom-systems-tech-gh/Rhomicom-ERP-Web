<?php

header("content-type:application/json");
$sbmtdInvoiceID = isset($_POST['pKeyID']) ? cleanInputData($_POST['pKeyID']) : -1;
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

$result = getInvoiceReport($sbmtdInvoiceID);

$docNo = "";
$rcptNo = "";
$dateReceived = "";
$invcDate = "";
$branch = "";
$Cashier = "";
$Customer = "";
$invCurncyNm = "";
$sbmtdInvoiceType = "";
$rcptAmnt = "";
$payMthd = "";
$changeBals = "";
$payRemarks = "";
$site_name = "";
$billing_address = "";
while ($invcRw = loc_db_fetch_array($result)) {
    $docNo = $invcRw[4];
    $rcptNo = $invcRw[15];
    $invcDate = $invcRw[6];
    $site_name = $invcRw[8];
    $billing_address = $invcRw[9];
    $dateReceived = $invcRw[23];
    $branch = $invcRw[27];
    $Cashier = $invcRw[25];
    $Customer = $invcRw[7];
    $invCurncyNm = $invcRw[12];
    $sbmtdInvoiceType = $invcRw[5];
    $rcptAmnt = (float) $invcRw[17];
    $payMthd = $invcRw[16];
    $changeBals = (float) $invcRw[18];
    $payRemarks = $invcRw[11];
    break;
}
if ($payRemarks != "") {
    $payRemarks = "<div><strong>NOTES:</strong></div>
                            <div class=\"notice\">$payRemarks</div>";
}
$dte = date('ymdhis');
$ecnptDFlNm = encrypt1($dte . "_" . $lgn_num, $smplTokenWord1);
$nwPDFFileName = "Customer_Invoice_" . $docNo . ".pdf";
$nwHtmFileName = $ecnptDFlNm . ".html";
$fullPemDest = $fldrPrfx . "dwnlds/amcharts_2100/samples/" . $nwHtmFileName;
$fullPemDest1 = $fldrPrfx . "dwnlds/amcharts_2100/samples/" . $nwPDFFileName;
$file = $fullPemDest;
// Append a new person to the file
/*

  <meta charset=\"UTF-8\">
  <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">
 * 
 *  */
$html = "<!DOCTYPE html>
<html>
    <head>
        <!--<title>TODO supply a title</title>-->
        <link rel=\"stylesheet\" href=\"../amcharts/rpt2.css\" type=\"text/css\">
        <link rel=\"stylesheet\" href=\"style.css\" type=\"text/css\">
        <script src=\"../amcharts/amcharts.js\" type=\"text/javascript\"></script>
    </head>
    <body>
        <style type=\"text/css\">
@page {
    size: auto;   /* auto is the initial value */
    margin: 0;  /* this affects the margin in the printer settings */
}

body {
  font-family: Tahoma, \"Helvetica Neue\", \"Helvetica\", \"Roboto\", \"Arial\", sans-serif;
  color: #000;
}

            #invoice{
                padding: 15px;
            }

            .invoice {
                position: relative;
                background-color: #FFF;
                min-height: 680px;
                padding: 5px
            }

            .invoice header {
                padding: 2px 0;
                /*margin-bottom: 10px;
                border-bottom: 1px solid #ddd*/
            }

            .invoice .company-details {
                text-align: right
            }

            .invoice .company-details .name {
                margin-top: 0;
                margin-bottom: 0
            }

            .invoice .contacts {
                margin-bottom: 5px
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
            .text-right {
                text-align: right !important;
            }
.text-left{
text-align: left !important;
}
            .invoice .invoice-details .invoice-id {
                color: #3989c6
            }
            
        #legalcopy {
          margin-top: 5mm;
          border-bottom: 1px solid #DDD;
          border-top: 1px solid #DDD;
        }
        </style>
        <div id=\"invoice\">
            <div class=\"invoice overflow-auto\">
                <div style=\"min-width: 600px\">
                    <header>
                        <div class=\"row\">
                            <div class=\"col\">
                                <a target=\"_blank\" href=\"$app_url\">
                                    <img src=\"../images/" . $orgLogo . "\" data-holder-rendered=\"true\" style=\"height:80px !important;width:auto;\"/>
                                </a>
                            </div>
                            <div class=\"col company-details\">
                                <h2 class=\"name\">$orgNm</h2>
                                <div>$pstl</div>
                                <div>$cntcts</div>
                                <div>$email</div>
                                <div>$webSite</div>
                            </div>
                        </div>
                    </header>
                    <hr/>
                    <main>
                        <div class=\"row contacts\">
                            <div class=\"col invoice-to\" style=\"float:left;\">
                                <div class=\"text-gray-light\">INVOICE TO:</div>
                                <h2 class=\"to\">$Customer</h2>
                                <div class=\"address\">$site_name</div>
                                <div class=\"address\">$billing_address</div>
                            </div>
                            <div class=\"col invoice-details\">
                                <h1 class=\"invoice-id\">$docNo</h1>
                                <div class=\"date\" style=\"\">Invoice Date: $invcDate</div>
                                <div class=\"date\">Due Date: $invcDate</div>
                            </div>
                        </div>
                        <table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" style=\"border-top:1px solid #ddd !important;width:100% !important;\">
                            <thead>
                                <tr>
                                    <th style=\"border-left:1px solid #3989c6 !important;\">#</th>
                                    <th class=\"text-left\" style=\"width:65% !important;\">ITEM DESCRIPTION</th>
                                    <th class=\"text-right\">QTY</th>
                                    <th class=\"text-right\">UNIT PRICE</th>
                                    <th class=\"text-right\"><strong>TOTAL (" . strtoupper($invCurncyNm) . ")</strong></th>
                                </tr>
                            </thead>
                            <tbody>";
$invcRslt = getInvoiceRptDet($sbmtdInvoiceID);
$rwCntr = 0;
while ($invcRw1 = loc_db_fetch_array($invcRslt)) {
    $rwCntr++;
    $itmDesc = $invcRw1[4];
    $itmQTY = (float) $invcRw1[0];
    $itmUPrce = (float) $invcRw1[1];
    $itmAMnt = (float) $invcRw1[2];
    $html .= "<tr>
                                <td class=\"no\">" . $rwCntr . "</td>
                                <td class=\"text-left\"><p class=\"itemtext\">" . $itmDesc . "</p></td>
                                <td class=\"text-right\" style=\"text-align:right !important;\"><p class=\"itemtext\">" . $itmQTY . "</p></td>
                                <td class=\"text-right\" style=\"text-align:right !important;\"><p class=\"itemtext\">" . number_format($itmUPrce, 2) . "</p></td>
                                <td class=\"text-right\" style=\"border-right:1px solid #ddd;\"><p class=\"itemtext\">" . number_format($itmAMnt, 2) . "</p></td>
                            </tr>";
    /* <tr>
      <td class=\"text-left\"><h3>Youtube channel</h3>
      Useful videos to improve your Javascript skills. Subscribe and stay tuned :)
      </td>
      <td class=\"text-right\">$0.00</td>
      <td class=\"text-right\">100</td>
      <td class=\"text-right\">$0.00</td>
      </tr> */
}
$html .= "</tbody>
                            <tfoot>";

$invcSmmryRslt = getInvoiceRptAllSmmry($sbmtdInvoiceID, $sbmtdInvoiceType);
while ($invcRw2 = loc_db_fetch_array($invcSmmryRslt)) {
    $smmryDesc = $invcRw2[0];
    $smmryAmnt = (float) $invcRw2[1];
    $html .= "<tr>
                                    <td colspan=\"2\" style=\"border:none !important;\"></td>
                                    <td colspan=\"2\" class=\"Rate\" style=\"text-align:right !important;\"><strong>" . $smmryDesc . "</strong></td>
                                    <td class=\"text-right\" style=\"text-align:right !important;border-right:1px solid #ddd;\"><strong>" . number_format($smmryAmnt, 2) . "</strong></td>
                              </tr>";
}
$html .= "</tfoot></table>
                        <div class=\"notices\">$payRemarks</div>
                        <div id=\"legalcopy\" style=\"text-align:center !important;\">
                            <p class=\"legal\" style=\"font-style:italic;font-family: \"Times New Roman\", Times, serif;\"><strong>Thank you for your business!</strong> $payRemarks. </p>
                        </div>
                    </main>
                </div>
                <!--DO NOT DELETE THIS div. IT is responsible for showing footer always at the bottom-->
                <div></div>
            </div>
        </div>
    </body>
</html>";
// Write the contents back to the file
file_put_contents($file, $html, FILE_APPEND);
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
    logSsnErrs($cmd);
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
/* $exitErrMsg = cnvrtHtmlToPDFURL($fullPemDest, $fullPemDest1); */
$arr_content['percent'] = 100;
$arr_content['mailTo'] = urlencode($email);
$arr_content['mailCc'] = urlencode($admin_email);
$arr_content['mailSubject'] = urlencode("Customer Invoice (" . $docNo . ")");
$message = "<p style=\"font-family: Calibri;font-size:18px;\">Dear " . $Customer . ", <br/><br/>"
    . "Please find attached your Invoice for a transaction in our System: " . $app_name .
    "<br/>Please do not hesitate to contact us should any of the information in the attached be inaccurate.<br/><br/>"
    . "Thank you!</p>";
$arr_content['bulkMessageBody'] = urlencode($message);
$arr_content['URL'] = urlencode($app_url . "dwnlds/amcharts_2100/samples/" . $nwPDFFileName);
$arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>" . $exitErrMsg . "</span>";
echo json_encode($arr_content);
exit();
