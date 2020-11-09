<?php

$canRunRpts = test_prmssns($dfltPrvldgs[8], $mdlNm);
$canDelRptRuns = test_prmssns($dfltPrvldgs[9], $mdlNm);
$canVwOthrsRuns = test_prmssns($dfltPrvldgs[10], $mdlNm);

$pageNo = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
$lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 10;
$sortBy = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : "";

$prsnid = $_SESSION['PRSN_ID'];
$orgID = $_SESSION['ORG_ID'];
if (array_key_exists('lgn_num', get_defined_vars())) {
    if ($lgn_num > 0 && $canview === true) {
        if ($qstr == "DELETE") {
            if ($actyp == 1) {
                
            }
        } else if ($qstr == "UPDATE") {
            if ($actyp == 1) {
                
            }
        } else {
            $cntent .= "<li onclick=\"openATab('#allmodules', 'grp=$group&typ=$type&pg=$pgNo&vtyp=0');\">
                            <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                            <span style=\"text-decoration:none;\">Agent Types</span>
                        </li>";
            if ($vwtyp == 0) {
                echo $cntent . "
                               </ul>
                              </div>";
                $vaultTrns = array("Individual Agents", "Coorporate Agents");
                $vaultTrnsImgs = array("customer.jpg", "company.png");
                $cntent = "";
                $grpcntr = 0;
                for ($i = 0; $i < count($vaultTrns); $i++) {
                    $No = $i + 1;
                    if ($grpcntr == 0) {
                        $cntent .= "<div class=\"row\">";
                    }

                    $cntent .= "<div class=\"col-md-3 colmd3special2\">
        <button type=\"button\" class=\"btn btn-default btn-lg btn-block\" style=\"min-height:175px;height:173px;margin-bottom:5px;\" onclick=\"openATab('#allmodules', 'grp=$group&typ=$type&pg=1&vtyp=$No');\">
            <img src=\"cmn_images/$vaultTrnsImgs[$i]\" style=\"margin:5px auto; height:78px; width:auto; position: relative; vertical-align: middle;float:none;\">
            <br/>
            <span class=\"wordwrap3\">" . ($vaultTrns[$i]) . "</span>
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
            } else if ($vwtyp == 1) {
                //Individual Agents                
                echo $cntent . "<li onclick=\"openATab('#allmodules', 'grp=$group&typ=$type&pg=$pgNo&vtyp=1');\">
                                    <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                    <span style=\"text-decoration:none;\">Individual Agents</span>
				</li>
                               </ul>
                              </div>";
            } else if ($vwtyp == 2) {
                //Coorporate Agents               
                echo $cntent . "<li onclick=\"openATab('#allmodules', 'grp=$group&typ=$type&pg=$pgNo&vtyp=2');\">
                                    <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                    <span style=\"text-decoration:none;\">Coorporate Agents</span>
				</li>
                               </ul>
                              </div>";
            }
        }
    }
}