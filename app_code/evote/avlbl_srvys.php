<?php

$pageNo = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
$lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 10;
$sortBy = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : "";
$curIdx = 0;
$orgID = $_SESSION['ORG_ID'];
$usrID = $_SESSION['USRID'];

if (array_key_exists('lgn_num', get_defined_vars())) {
    if ($lgn_num > 0 && $canview === true) {
        if ($qstr == "DELETE") {
            if ($actyp == 1) {
                
            }
        } else if ($qstr == "UPDATE") {
            if ($actyp == 1) {
                
            } else if ($actyp == 2) {
                
            }
        } else {
            $prsnid = $_SESSION['PRSN_ID'];
            echo $cntent . "<li>
                                    <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                    <span style=\"text-decoration:none;\">Available Elections/Surveys</span>
				</li>
                               </ul>
                            </div>";
            if ($vwtyp == 0) {
                $result = get_MyActvSrvysTblr($prsnid);
                $grpcntr = 0;
                $i = 0;
                $cntent = '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' .
                        'font-weight:normal;">Sorry, there are currently no Elections/Surveys Available.<br/>Thank you for expressing interest in it.<br/>We will alert you as soon as there are Surveys or Elections Available.<br/><br/>Warm Regards...<br/><br/>'.$admin_name.'</span></p>';
                if (loc_db_num_rows($result) > 0) {
                    $cntent = "";
                }
                while ($row = loc_db_fetch_array($result)) {
                    $No = 5;
                    if ($grpcntr == 0) {
                        $cntent .= "<div class=\"row\">";
                    }
                    $cntent .= "<div class=\"col-md-3 colmd3special2\">
        <button type=\"button\" class=\"btn btn-default btn-lg btn-block modulesButton\" onclick=\"openATab('#allmodules', 'grp=$group&typ=$type&pg=$No&vtyp=0&sbmtdSrvyID=$row[0]');\">
            <img src=\"cmn_images/approve1.png\" style=\"margin:5px auto; height:70px; width:auto; position: relative; vertical-align: middle;float:none;\">
                <br/>
            <span class=\"wordwrap3\">" . ($row[1]) . "</span>
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
                $buttons = "";
                $cntent .= "
    </p>
    </div>
    </fieldset>
        " . $buttons . "</div>";
                echo $cntent;
            } else if ($vwtyp == 1) {
                
            }
        }
    }
}