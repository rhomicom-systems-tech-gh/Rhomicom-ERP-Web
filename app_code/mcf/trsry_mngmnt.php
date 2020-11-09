<?php

$canAddTrns = test_prmssns($dfltPrvldgs[0], $mdlNm);
$canEdtTrns = test_prmssns($dfltPrvldgs[0], $mdlNm);
$canDelTrns = test_prmssns($dfltPrvldgs[0], $mdlNm);
$canAthrz = test_prmssns($dfltPrvldgs[0], $mdlNm);

$pageNo = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
$lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 10;
$sortBy = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : "";

$qInvalidOnly = "false";
$qUnathrzdOnly = "false";
$qStrtDte = "01-Jan-1900 00:00:00";
$qEndDte = "31-Dec-4000 23:59:59";
$srchFor = "";
$srchIn = "";

if (isset($_POST['qInvalidOnly'])) {
    $qInvalidOnly = cleanInputData($_POST['qInvalidOnly']);
}
if (isset($_POST['qUnathrzdOnly'])) {
    $qUnathrzdOnly = cleanInputData($_POST['qUnathrzdOnly']);
}
if (isset($_POST['qStrtDte'])) {
    $qStrtDte = cleanInputData($_POST['qStrtDte']);
    if (strlen($qStrtDte) == 11) {
        $qStrtDte = substr($qStrtDte, 0, 11) . " 00:00:00";
    } else {
        $qStrtDte = "01-Jan-1900 00:00:00";
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

if (isset($_POST['searchfor'])) {
    $srchFor = cleanInputData($_POST['searchfor']);
}

if (isset($_POST['searchIn'])) {
    $srchIn = cleanInputData($_POST['searchIn']);
}
if (strpos($srchFor, "%") === FALSE) {
    $srchFor = " " . $srchFor . " ";
    $srchFor = str_replace(" ", "%", $srchFor);
}

if (array_key_exists('lgn_num', get_defined_vars())) {
    if ($lgn_num > 0 && $canview === true) {
        if ($qstr == "DELETE") {
            
        } else if ($qstr == "UPDATE") {
            
        } else {
            if ($vwtyp == 0) {
                echo $cntent . "
                                <li onclick=\"openATab('#allmodules', 'grp=$group&typ=$type&pg=$pgNo&vtyp=0');\">
                                    <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                    <span style=\"text-decoration:none;\">Treasury Management</span>
				</li>
                               </ul>
                              </div>" . "<div style=\"font-family: Tahoma, Arial, sans-serif;font-size: 1.3em;
                    padding:10px 15px 15px 20px;border:1px solid #ccc;\">                    
      <h4>TREASURY MANAGEMENT MENU ITEMS</h3> 
      <p>";
                $cntent = "";
                $grpcntr = 0;
                for ($i = 0; $i < count($trsryMenuItems); $i++) {
                    $No = $i + 1;
                    if ($i > 1) {
                        continue;
                    }
                    if ($grpcntr == 0) {
                        $cntent .= "<div class=\"row\">";
                    }

                    $cntent .= "<div class=\"col-md-3 colmd3special2\">
        <button type=\"button\" class=\"btn btn-default btn-lg btn-block\" style=\"min-height:145px;height:143px;margin-bottom:5px;\" onclick=\"openATab('#allmodules', 'grp=$group&typ=$type&pg=$pgNo&vtyp=$No');\">
            <img src=\"cmn_images/$trsryMenuImages[$i]\" style=\"margin:5px auto; height:58px; width:auto; position: relative; vertical-align: middle;float:none;\">
            <br/>
            <span class=\"wordwrap3\">" . ($trsryMenuItems[$i]) . "</span>
            <br/>&nbsp;
        </button>
            </div>";
                    if ($grpcntr == 3) {
                        $cntent .= "</div>";
                        $grpcntr = 0;
                    } else {
                        $grpcntr = $grpcntr + 1;
                    }
                }

                $cntent .= "
      </p>
    </div>";
                echo $cntent;
            }
        }
    }
}