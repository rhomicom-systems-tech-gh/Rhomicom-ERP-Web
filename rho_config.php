<?php
if (1 === 1) {
    $superAdminConfigPswd1 = $_SESSION['SUPERADMINCONFIGPSWD'];
    $superAdminConfigPswd2 = $_SESSION['SUPERADMINCONFIGPSWD2'];
    $actyp = isset($_POST['actyp']) ? (int) ($_POST['actyp']) : 0;

    /*if ($superAdminConfigPswd1 !== "") {
            $superAdminConfigPswd1 = decrypt($superAdminConfigPswd1, $smplTokenWord1);
        }
    echo $superAdminConfigPswd1."<br/>Pwd: 2=><br/>";
	
	
	 if ($superAdminConfigPswd2 !== "") {
            $superAdminConfigPswd2 = decrypt($superAdminConfigPswd2, $smplTokenWord1);
        }
    echo $superAdminConfigPswd2;*/

    if ($actyp == 1) {
        header("content-type:application/json");
        $superAdminConfigPswd2 = isset($_POST['superAdminConfigPswd2']) ? ($_POST['superAdminConfigPswd2']) : "";

        if ($superAdminConfigPswd2 !== "") {
            $superAdminConfigPswd2 = encrypt($superAdminConfigPswd2, $smplTokenWord1);
        }
        $_SESSION['SUPERADMINCONFIGPSWD2'] = $superAdminConfigPswd2;
        if ($superAdminConfigPswd1 == $superAdminConfigPswd2) {
            $exitErrMsg = "<span style=\"color:green;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>Password Verified Successfully!</span>";
            $arr_content['percent'] = 100;
            $arr_content['message'] = $exitErrMsg;
            echo json_encode($arr_content);
            exit();
        } else {
            $exitErrMsg = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>Password Verification Failed!</span>";
            $arr_content['percent'] = 100;
            $arr_content['message'] = $exitErrMsg;
            echo json_encode($arr_content);
            exit();
        }
    } else if ($actyp == 2) {
        header("content-type:application/json");
        try {
            $_SESSION['SUPERADMINCONFIGPSWD2'] = "";
            destroySession();
            $exitErrMsg = "<span style=\"color:blue;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>Logout Successful!</span>";
            $arr_content['percent'] = 100;
            $arr_content['message'] = $exitErrMsg;
            echo json_encode($arr_content);
            exit();
        } catch (\Exception $ex) {
            $exitErrMsg = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>Failed to Log Out!<br/>" . $ex->getMessage() . "</span>";
            $arr_content['percent'] = 100;
            $arr_content['message'] = $exitErrMsg;
            echo json_encode($arr_content);
            exit();
        }
    } else if ($actyp == 3) {
        header("content-type:application/json");
        $page_title = isset($_POST['page_title']) ? cleanInputData($_POST['page_title']) : $page_title;
        $app_url = isset($_POST['app_url']) ? cleanInputData($_POST['app_url']) : $app_url;
        $flxcde_url = isset($_POST['flxcde_url']) ? cleanInputData($_POST['flxcde_url']) : $flxcde_url;
        $app_name = isset($_POST['app_name']) ? cleanInputData($_POST['app_name']) : $app_name;
        $system_name = isset($_POST['system_name']) ? cleanInputData($_POST['system_name']) : $system_name;
        $app_cstmr = isset($_POST['app_cstmr']) ? cleanInputData($_POST['app_cstmr']) : $app_cstmr;
        $app_cstmr_url = isset($_POST['app_cstmr_url']) ? cleanInputData($_POST['app_cstmr_url']) : $app_cstmr_url;
        $app_slogan = isset($_POST['app_slogan']) ? cleanInputData($_POST['app_slogan']) : $app_slogan;
        $admin_email = isset($_POST['admin_email']) ? cleanInputData($_POST['admin_email']) : $admin_email;
        $admin_name = isset($_POST['admin_name']) ? cleanInputData($_POST['admin_name']) : $admin_name;
        $jsCssFileVrsn = isset($_POST['jsCssFileVrsn']) ? cleanInputData($_POST['jsCssFileVrsn']) : $jsCssFileVrsn;
        $noticesElmntNm = isset($_POST['noticesElmntNm']) ? cleanInputData($_POST['noticesElmntNm']) : $noticesElmntNm;
        $smplPwd = isset($_POST['smplPwd']) ? cleanInputData($_POST['smplPwd']) : $smplPwd;
        $host = isset($_POST['host']) ? cleanInputData($_POST['host']) : $host;
        $database = isset($_POST['database']) ? cleanInputData($_POST['database']) : $database;
        $db_usr = isset($_POST['db_usr']) ? cleanInputData($_POST['db_usr']) : $db_usr;
        $db_pwd = isset($_POST['db_pwd']) ? cleanInputData($_POST['db_pwd']) : $db_pwd;
        $port = isset($_POST['port']) ? cleanInputData($_POST['port']) : $port;
        $postgre_db_pwd = isset($_POST['postgre_db_pwd']) ? cleanInputData($_POST['postgre_db_pwd']) : $postgre_db_pwd;
        $db_folder = isset($_POST['db_folder']) ? cleanInputData($_POST['db_folder']) : $db_folder;
        $fldrPrfx = isset($_POST['fldrPrfx']) ? cleanInputData($_POST['fldrPrfx']) : $fldrPrfx;
        $tmpDest = isset($_POST['tmpDest']) ? cleanInputData($_POST['tmpDest']) : $tmpDest;
        $pemDest = isset($_POST['pemDest']) ? cleanInputData($_POST['pemDest']) : $pemDest;
        $ftp_base_db_fldr = isset($_POST['ftp_base_db_fldr']) ? cleanInputData($_POST['ftp_base_db_fldr']) : $ftp_base_db_fldr;
        $mysql_db_name = isset($_POST['mysql_db_name']) ? cleanInputData($_POST['mysql_db_name']) : $mysql_db_name;
        $mysql_db_user = isset($_POST['mysql_db_user']) ? cleanInputData($_POST['mysql_db_user']) : $mysql_db_user;
        $mysql_db_pass = isset($_POST['mysql_db_pass']) ? cleanInputData($_POST['mysql_db_pass']) : $mysql_db_pass;
        $mysql_db_host = isset($_POST['mysql_db_host']) ? cleanInputData($_POST['mysql_db_host']) : $mysql_db_host;
        $mysql_db_port = isset($_POST['mysql_db_port']) ? cleanInputData($_POST['mysql_db_port']) : $mysql_db_port;
        $superAdminConfigPswd = isset($_POST['superAdminConfigPswd']) ? cleanInputData($_POST['superAdminConfigPswd']) : $superAdminConfigPswd;
        $superAdminConfigFilePath = isset($_POST['superAdminConfigFilePath']) ? cleanInputData($_POST['superAdminConfigFilePath']) : $superAdminConfigFilePath;
        $softwareLincenseCode = isset($_POST['softwareLincenseCode']) ? cleanInputData($_POST['softwareLincenseCode']) : $softwareLincenseCode;
        $smplTokenWord1 = isset($_POST['smplTokenWord1']) ? cleanInputData($_POST['smplTokenWord1']) : $smplTokenWord1;
        $app_image1 = isset($_POST['app_image1']) ? cleanInputData($_POST['app_image1']) : $app_image1;
        $app_favicon = isset($_POST['app_favicon']) ? cleanInputData($_POST['app_favicon']) : $app_favicon;
        $bckcolorOnly = isset($_POST['bckcolorOnly']) ? cleanInputData($_POST['bckcolorOnly']) : $bckcolorOnly;
        $bckcolorOnly1 = isset($_POST['bckcolorOnly1']) ? cleanInputData($_POST['bckcolorOnly1']) : $bckcolorOnly1;
        $bckcolorOnly2 = isset($_POST['bckcolorOnly2']) ? cleanInputData($_POST['bckcolorOnly2']) : $bckcolorOnly2;
        $bckcolorshv = isset($_POST['bckcolorshv']) ? cleanInputData($_POST['bckcolorshv']) : $bckcolorshv;
        $forecolors = isset($_POST['forecolors']) ? cleanInputData($_POST['forecolors']) : $forecolors;
        $bckcolors1 = isset($_POST['bckcolors1']) ? cleanInputData($_POST['bckcolors1']) : $bckcolors1;
        $bckcolors2 = isset($_POST['bckcolors2']) ? cleanInputData($_POST['bckcolors2']) : $bckcolors2;
        $goBackButtonMsg = isset($_POST['goBackButtonMsg']) ? cleanInputData($_POST['goBackButtonMsg']) : $goBackButtonMsg;
        $placeHolder1 = isset($_POST['placeHolder1']) ? cleanInputData($_POST['placeHolder1']) : $placeHolder1;
        $loginTitle = isset($_POST['loginTitle']) ? cleanInputData($_POST['loginTitle']) : $loginTitle;
        $website_btn_txt = isset($_POST['website_btn_txt']) ? cleanInputData($_POST['website_btn_txt']) : $website_btn_txt;
        $bckcolors = isset($_POST['bckcolors']) ? cleanInputData($_POST['bckcolors']) : $bckcolors;
        $bckcolorsChngPwd = isset($_POST['bckcolorsChngPwd']) ? cleanInputData($_POST['bckcolorsChngPwd']) : $bckcolorsChngPwd;
        $bckcolors_home = isset($_POST['bckcolors_home']) ? cleanInputData($_POST['bckcolors_home']) : $bckcolors_home;
        $breadCrmbBckclr = isset($_POST['breadCrmbBckclr']) ? cleanInputData($_POST['breadCrmbBckclr']) : $breadCrmbBckclr;
        $loginPgNotice = isset($_POST['loginPgNotice']) ? cleanInputData($_POST['loginPgNotice']) : $loginPgNotice;
        $instructions = isset($_POST['instructions']) ? cleanInputData($_POST['instructions']) : $instructions;
        $introToPrtlArtBody = isset($_POST['introToPrtlArtBody']) ? cleanInputData($_POST['introToPrtlArtBody']) : $introToPrtlArtBody;
        $ltstNewArtBody = isset($_POST['ltstNewArtBody']) ? cleanInputData($_POST['ltstNewArtBody']) : $ltstNewArtBody;
        $usefulLnksArtBody = isset($_POST['usefulLnksArtBody']) ? cleanInputData($_POST['usefulLnksArtBody']) : $usefulLnksArtBody;
        $aboutRho = isset($_POST['aboutRho']) ? cleanInputData($_POST['aboutRho']) : $aboutRho;
        $smplTokenWord = isset($_POST['smplTokenWord']) ? cleanInputData($_POST['smplTokenWord']) : $smplTokenWord;
        $abt_portal = isset($_POST['abt_portal']) ? cleanInputData($_POST['abt_portal']) : $abt_portal;
        $homepgfile = isset($_POST['homepgfile']) ? cleanInputData($_POST['homepgfile']) : $homepgfile;
        $browserPDFCmd = isset($_POST['browserPDFCmd']) ? cleanInputData($_POST['browserPDFCmd']) : $browserPDFCmd;
        $rhoAPIUrl = isset($_POST['rhoAPIUrl']) ? cleanInputData($_POST['rhoAPIUrl']) : $rhoAPIUrl;
        $putInMntnceMode = isset($_POST['putInMntnceMode']) ? cleanInputData($_POST['putInMntnceMode']) : "NO";

        $cnfg_arr_content['page_title'] = $page_title;
        $cnfg_arr_content['app_url'] = $app_url;
        $cnfg_arr_content['flxcde_url'] = $flxcde_url;
        $cnfg_arr_content['app_name'] = $app_name;
        $cnfg_arr_content['system_name'] = $system_name;
        $cnfg_arr_content['app_cstmr'] = $app_cstmr;
        $cnfg_arr_content['app_cstmr_url'] = $app_cstmr_url;
        $cnfg_arr_content['app_slogan'] = $app_slogan;
        $cnfg_arr_content['admin_email'] = $admin_email;
        $cnfg_arr_content['admin_name'] = $admin_name;
        $cnfg_arr_content['jsCssFileVrsn'] = $jsCssFileVrsn;
        $cnfg_arr_content['noticesElmntNm'] = $noticesElmntNm;
        $cnfg_arr_content['smplPwd'] = $smplPwd;
        $cnfg_arr_content['host'] = $host;
        $cnfg_arr_content['database'] = encrypt1($database, $smplTokenWordRhoKey);
        $cnfg_arr_content['db_usr'] = encrypt1($db_usr, $smplTokenWordRhoKey);
        $cnfg_arr_content['db_pwd'] = encrypt1($db_pwd, $smplTokenWordRhoKey);
        $cnfg_arr_content['port'] = $port;
        $cnfg_arr_content['postgre_db_pwd'] = encrypt1($postgre_db_pwd, $smplTokenWordRhoKey);
        $cnfg_arr_content['db_folder'] = $db_folder;
        $cnfg_arr_content['fldrPrfx'] = $fldrPrfx;
        $cnfg_arr_content['tmpDest'] = $tmpDest;
        $cnfg_arr_content['pemDest'] = $pemDest;
        $cnfg_arr_content['ftp_base_db_fldr'] = $ftp_base_db_fldr;
        $cnfg_arr_content['mysql_db_name'] = encrypt1($mysql_db_name, $smplTokenWordRhoKey);
        $cnfg_arr_content['mysql_db_user'] = encrypt1($mysql_db_user, $smplTokenWordRhoKey);
        $cnfg_arr_content['mysql_db_pass'] = encrypt1($mysql_db_pass, $smplTokenWordRhoKey);
        $cnfg_arr_content['mysql_db_host'] = $mysql_db_host;
        $cnfg_arr_content['mysql_db_port'] = $mysql_db_port;
        $cnfg_arr_content['superAdminConfigPswd'] = encrypt1($superAdminConfigPswd, $smplTokenWordRhoKey);
        $cnfg_arr_content['superAdminConfigFilePath'] = $superAdminConfigFilePath;
        $cnfg_arr_content['softwareLincenseCode'] = encrypt1($softwareLincenseCode, $smplTokenWordRhoKey);
        $cnfg_arr_content['smplTokenWord1'] = encrypt1($smplTokenWord1, $smplTokenWordRhoKey);
        $cnfg_arr_content['app_image1'] = $app_image1;
        $cnfg_arr_content['app_favicon'] = $app_favicon;
        $cnfg_arr_content['bckcolorOnly'] = $bckcolorOnly;
        $cnfg_arr_content['bckcolorOnly1'] = $bckcolorOnly1;
        $cnfg_arr_content['bckcolorOnly2'] = $bckcolorOnly2;
        $cnfg_arr_content['bckcolorshv'] = $bckcolorshv;
        $cnfg_arr_content['forecolors'] = $forecolors;
        $cnfg_arr_content['bckcolors1'] = $bckcolors1;
        $cnfg_arr_content['bckcolors2'] = $bckcolors2;
        $cnfg_arr_content['goBackButtonMsg'] = $goBackButtonMsg;
        $cnfg_arr_content['placeHolder1'] = $placeHolder1;
        $cnfg_arr_content['loginTitle'] = $loginTitle;
        $cnfg_arr_content['website_btn_txt'] = $website_btn_txt;
        $cnfg_arr_content['bckcolors'] = $bckcolors;
        $cnfg_arr_content['bckcolorsChngPwd'] = $bckcolorsChngPwd;
        $cnfg_arr_content['bckcolors_home'] = $bckcolors_home;
        $cnfg_arr_content['breadCrmbBckclr'] = $breadCrmbBckclr;
        $cnfg_arr_content['loginPgNotice'] = $loginPgNotice;
        $cnfg_arr_content['instructions'] = $instructions;
        $cnfg_arr_content['introToPrtlArtBody'] = $introToPrtlArtBody;
        $cnfg_arr_content['ltstNewArtBody'] = $ltstNewArtBody;
        $cnfg_arr_content['usefulLnksArtBody'] = $usefulLnksArtBody;
        $cnfg_arr_content['aboutRho'] = $aboutRho;
        $cnfg_arr_content['smplTokenWord'] = encrypt1($smplTokenWord, $smplTokenWordRhoKey);
        $cnfg_arr_content['abt_portal'] = $abt_portal;
        $cnfg_arr_content['homepgfile'] = $homepgfile;
        $cnfg_arr_content['browserPDFCmd'] = $browserPDFCmd;
        $cnfg_arr_content['rhoAPIUrl'] = $rhoAPIUrl;
        $cnfg_arr_content['putInMntnceMode'] = $putInMntnceMode;
        try {
            file_put_contents($superAdminConfigFilePath, json_encode($cnfg_arr_content));
            file_put_contents($superAdminConfigFilePathLoc, encrypt1($superAdminConfigFilePath, $smplTokenWordRhoKey));
            $exitErrMsg = "<span style=\"color:green;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>Config File Successfully Saved!</span>";
            $arr_content['percent'] = 100;
            $arr_content['message'] = $exitErrMsg;
            echo json_encode($arr_content);
            exit();
        } catch (\Exception $ex) {
            $exitErrMsg = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>Failed to Save Config File!<br/>" . $ex->getMessage() . "</span>";
            $arr_content['percent'] = 100;
            $arr_content['message'] = $exitErrMsg;
            echo json_encode($arr_content);
            exit();
        }
    } else {
?>
        <link rel="STYLESHEET" type="text/css" href="cmn_scrpts/loginStyles.css" />
        <script type="text/javascript">
            function enterKeyFunc(e) {
                var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
                if (charCode == 13) {
                    verifyRhoConfigPwd();
                }
                //return false;
            }

            function logoutRhoConfigPwd() {
                var msgsTitle = 'Rhomi Configuration';
                var dialog = bootbox.confirm({
                    title: 'Logout ' + msgsTitle + '?',
                    size: 'small',
                    message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">LOGOUT OF</span> ' + msgsTitle + '?<br/>Action cannot be Undone!</p>',
                    buttons: {
                        confirm: {
                            label: '<i class="fa fa-check"></i> Yes',
                            className: 'btn-success'
                        },
                        cancel: {
                            label: '<i class="fa fa-times"></i> No',
                            className: 'btn-danger'
                        }
                    },
                    callback: function(result) {
                        if (result === true) {
                            var dialog1 = bootbox.alert({
                                title: 'Logout of Rhomi Configuration',
                                size: 'small',
                                message: '<p><i class="fa fa-spin fa-spinner"></i> Loging Out of Rhomi Configuration...Please Wait...</p>',
                                callback: function() {
                                    window.location = 'index.php?cp=2';
                                }
                            });
                            var formData = new FormData();
                            formData.append('shdGoConfig', 1005);
                            formData.append('actyp', 2);
                            dialog1.init(function() {
                                $body = $("body");
                                $body.removeClass("mdlloading");
                                $.ajax({
                                    url: 'index.php',
                                    method: 'POST',
                                    data: formData,
                                    async: true,
                                    cache: false,
                                    contentType: false,
                                    processData: false,
                                    success: function(data) {
                                        console.warn(data);
                                        setTimeout(function() {
                                            dialog1.find('.bootbox-body').html(data.message);
                                        }, 500);
                                    },
                                    error: function(jqXHR, textStatus, errorThrown) {
                                        console.log(textStatus + " " + errorThrown);
                                        console.warn(jqXHR.responseText);
                                    }
                                });
                            });
                        }
                    }
                });

            }

            function verifyRhoConfigPwd() {
                var superAdminConfigPswd = typeof $("#superAdminConfigPswd").val() === 'undefined' ? '' : $("#superAdminConfigPswd").val();
                var dialog = bootbox.alert({
                    title: 'Verify Password',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Verifying Password...Please Wait...</p>',
                    callback: function() {
                        window.location = 'index.php?cp=2';
                    }
                });
                var formData = new FormData();
                formData.append('shdGoConfig', 1005);
                formData.append('actyp', 1);
                formData.append('superAdminConfigPswd2', superAdminConfigPswd);
                dialog.init(function() {
                    $body = $("body");
                    $body.removeClass("mdlloading");
                    $.ajax({
                        url: 'index.php',
                        method: 'POST',
                        data: formData,
                        async: true,
                        cache: false,
                        contentType: false,
                        processData: false,
                        success: function(data) {
                            setTimeout(function() {
                                dialog.find('.bootbox-body').html(data.message);
                            }, 500);
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            console.log(textStatus + " " + errorThrown);
                            console.warn(jqXHR.responseText);
                        }
                    });
                });
            }

            function updateRhoConfigFiles() {
                var page_title = typeof $("#page_title").val() === 'undefined' ? '' : $("#page_title").val();
                var app_url = typeof $("#app_url").val() === 'undefined' ? '' : $("#app_url").val();
                var flxcde_url = typeof $("#flxcde_url").val() === 'undefined' ? '' : $("#flxcde_url").val();
                var app_name = typeof $("#app_name").val() === 'undefined' ? '' : $("#app_name").val();
                var system_name = typeof $("#system_name").val() === 'undefined' ? '' : $("#system_name").val();
                var app_cstmr = typeof $("#app_cstmr").val() === 'undefined' ? '' : $("#app_cstmr").val();
                var app_cstmr_url = typeof $("#app_cstmr_url").val() === 'undefined' ? '' : $("#app_cstmr_url").val();
                var app_slogan = typeof $("#app_slogan").val() === 'undefined' ? '' : $("#app_slogan").val();
                var admin_email = typeof $("#admin_email").val() === 'undefined' ? '' : $("#admin_email").val();
                var admin_name = typeof $("#admin_name").val() === 'undefined' ? '' : $("#admin_name").val();
                var jsCssFileVrsn = typeof $("#jsCssFileVrsn").val() === 'undefined' ? '' : $("#jsCssFileVrsn").val();
                var noticesElmntNm = typeof $("#noticesElmntNm").val() === 'undefined' ? '' : $("#noticesElmntNm").val();
                var smplPwd = typeof $("#smplPwd").val() === 'undefined' ? '' : $("#smplPwd").val();
                var host = typeof $("#host").val() === 'undefined' ? '' : $("#host").val();
                var database = typeof $("#database").val() === 'undefined' ? '' : $("#database").val();
                var db_usr = typeof $("#db_usr").val() === 'undefined' ? '' : $("#db_usr").val();
                var db_pwd = typeof $("#db_pwd").val() === 'undefined' ? '' : $("#db_pwd").val();
                var port = typeof $("#port").val() === 'undefined' ? '' : $("#port").val();
                var postgre_db_pwd = typeof $("#postgre_db_pwd").val() === 'undefined' ? '' : $("#postgre_db_pwd").val();
                var db_folder = typeof $("#db_folder").val() === 'undefined' ? '' : $("#db_folder").val();
                var fldrPrfx = typeof $("#fldrPrfx").val() === 'undefined' ? '' : $("#fldrPrfx").val();
                var tmpDest = typeof $("#tmpDest").val() === 'undefined' ? '' : $("#tmpDest").val();
                var pemDest = typeof $("#pemDest").val() === 'undefined' ? '' : $("#pemDest").val();
                var ftp_base_db_fldr = typeof $("#ftp_base_db_fldr").val() === 'undefined' ? '' : $("#ftp_base_db_fldr").val();
                var mysql_db_name = typeof $("#mysql_db_name").val() === 'undefined' ? '' : $("#mysql_db_name").val();
                var mysql_db_user = typeof $("#mysql_db_user").val() === 'undefined' ? '' : $("#mysql_db_user").val();
                var mysql_db_pass = typeof $("#mysql_db_pass").val() === 'undefined' ? '' : $("#mysql_db_pass").val();
                var mysql_db_host = typeof $("#mysql_db_host").val() === 'undefined' ? '' : $("#mysql_db_host").val();
                var mysql_db_port = typeof $("#mysql_db_port").val() === 'undefined' ? '' : $("#mysql_db_port").val();
                var superAdminConfigPswd = typeof $("#superAdminConfigPswd").val() === 'undefined' ? '' : $("#superAdminConfigPswd").val();
                var superAdminConfigFilePath = typeof $("#superAdminConfigFilePath").val() === 'undefined' ? '' : $("#superAdminConfigFilePath").val();
                var softwareLincenseCode = typeof $("#softwareLincenseCode").val() === 'undefined' ? '' : $("#softwareLincenseCode").val();
                var smplTokenWord1 = typeof $("#smplTokenWord1").val() === 'undefined' ? '' : $("#smplTokenWord1").val();
                var app_image1 = typeof $("#app_image1").val() === 'undefined' ? '' : $("#app_image1").val();
                var app_favicon = typeof $("#app_favicon").val() === 'undefined' ? '' : $("#app_favicon").val();
                var bckcolorOnly = typeof $("#bckcolorOnly").val() === 'undefined' ? '' : $("#bckcolorOnly").val();
                var bckcolorOnly1 = typeof $("#bckcolorOnly1").val() === 'undefined' ? '' : $("#bckcolorOnly1").val();
                var bckcolorOnly2 = typeof $("#bckcolorOnly2").val() === 'undefined' ? '' : $("#bckcolorOnly2").val();
                var bckcolorshv = typeof $("#bckcolorshv").val() === 'undefined' ? '' : $("#bckcolorshv").val();
                var forecolors = typeof $("#forecolors").val() === 'undefined' ? '' : $("#forecolors").val();
                var bckcolors1 = typeof $("#bckcolors1").val() === 'undefined' ? '' : $("#bckcolors1").val();
                var bckcolors2 = typeof $("#bckcolors2").val() === 'undefined' ? '' : $("#bckcolors2").val();
                var goBackButtonMsg = typeof $("#goBackButtonMsg").val() === 'undefined' ? '' : $("#goBackButtonMsg").val();
                var placeHolder1 = typeof $("#placeHolder1").val() === 'undefined' ? '' : $("#placeHolder1").val();
                var loginTitle = typeof $("#loginTitle").val() === 'undefined' ? '' : $("#loginTitle").val();
                var website_btn_txt = typeof $("#website_btn_txt").val() === 'undefined' ? '' : $("#website_btn_txt").val();
                var bckcolors = typeof $("#bckcolors").val() === 'undefined' ? '' : $("#bckcolors").val();
                var bckcolorsChngPwd = typeof $("#bckcolorsChngPwd").val() === 'undefined' ? '' : $("#bckcolorsChngPwd").val();
                var bckcolors_home = typeof $("#bckcolors_home").val() === 'undefined' ? '' : $("#bckcolors_home").val();
                var breadCrmbBckclr = typeof $("#breadCrmbBckclr").val() === 'undefined' ? '' : $("#breadCrmbBckclr").val();
                var loginPgNotice = typeof $("#loginPgNotice").val() === 'undefined' ? '' : $("#loginPgNotice").val();
                var instructions = typeof $("#instructions").val() === 'undefined' ? '' : $("#instructions").val();
                var introToPrtlArtBody = typeof $("#introToPrtlArtBody").val() === 'undefined' ? '' : $("#introToPrtlArtBody").val();
                var ltstNewArtBody = typeof $("#ltstNewArtBody").val() === 'undefined' ? '' : $("#ltstNewArtBody").val();
                var usefulLnksArtBody = typeof $("#usefulLnksArtBody").val() === 'undefined' ? '' : $("#usefulLnksArtBody").val();
                var aboutRho = typeof $("#aboutRho").val() === 'undefined' ? '' : $("#aboutRho").val();
                var smplTokenWord = typeof $("#smplTokenWord").val() === 'undefined' ? '' : $("#smplTokenWord").val();
                var abt_portal = typeof $("#abt_portal").val() === 'undefined' ? '' : $("#abt_portal").val();
                var homepgfile = typeof $("#homepgfile").val() === 'undefined' ? '' : $("#homepgfile").val();
                var browserPDFCmd = typeof $("#browserPDFCmd").val() === 'undefined' ? '' : $("#browserPDFCmd").val();
                var rhoAPIUrl = typeof $("#rhoAPIUrl").val() === 'undefined' ? '' : $("#rhoAPIUrl").val();
                var putInMntnceMode = typeof $("#putInMntnceMode").val() === 'undefined' ? '' : $("#putInMntnceMode").val();

                var dialog = bootbox.alert({
                    title: 'Update Configuration',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Updating Configuration...Please Wait...</p>',
                    callback: function() {
                        window.location = 'index.php?cp=2';
                    }
                });
                var formData = new FormData();
                formData.append('shdGoConfig', 1005);
                formData.append('actyp', 3);
                formData.append('page_title', page_title);
                formData.append('app_url', app_url);
                formData.append('flxcde_url', flxcde_url);
                formData.append('app_name', app_name);
                formData.append('system_name', system_name);
                formData.append('app_cstmr', app_cstmr);
                formData.append('app_cstmr_url', app_cstmr_url);
                formData.append('app_slogan', app_slogan);
                formData.append('admin_email', admin_email);
                formData.append('admin_name', admin_name);
                formData.append('jsCssFileVrsn', jsCssFileVrsn);
                formData.append('noticesElmntNm', noticesElmntNm);
                formData.append('smplPwd', smplPwd);
                formData.append('host', host);
                formData.append('database', database);
                formData.append('db_usr', db_usr);
                formData.append('db_pwd', db_pwd);
                formData.append('port', port);
                formData.append('postgre_db_pwd', postgre_db_pwd);
                formData.append('db_folder', db_folder);
                formData.append('fldrPrfx', fldrPrfx);
                formData.append('tmpDest', tmpDest);
                formData.append('pemDest', pemDest);
                formData.append('ftp_base_db_fldr', ftp_base_db_fldr);
                formData.append('mysql_db_name', mysql_db_name);
                formData.append('mysql_db_user', mysql_db_user);
                formData.append('mysql_db_pass', mysql_db_pass);
                formData.append('mysql_db_host', mysql_db_host);
                formData.append('mysql_db_port', mysql_db_port);
                formData.append('superAdminConfigPswd', superAdminConfigPswd);
                formData.append('superAdminConfigFilePath', superAdminConfigFilePath);
                formData.append('softwareLincenseCode', softwareLincenseCode);
                formData.append('smplTokenWord1', smplTokenWord1);
                formData.append('app_image1', app_image1);
                formData.append('app_favicon', app_favicon);
                formData.append('bckcolorOnly', bckcolorOnly);
                formData.append('bckcolorOnly1', bckcolorOnly1);
                formData.append('bckcolorOnly2', bckcolorOnly2);
                formData.append('bckcolorshv', bckcolorshv);
                formData.append('forecolors', forecolors);
                formData.append('bckcolors1', bckcolors1);
                formData.append('bckcolors2', bckcolors2);
                formData.append('goBackButtonMsg', goBackButtonMsg);
                formData.append('placeHolder1', placeHolder1);
                formData.append('loginTitle', loginTitle);
                formData.append('website_btn_txt', website_btn_txt);
                formData.append('bckcolors', bckcolors);
                formData.append('bckcolorsChngPwd', bckcolorsChngPwd);
                formData.append('bckcolors_home', bckcolors_home);
                formData.append('breadCrmbBckclr', breadCrmbBckclr);
                formData.append('loginPgNotice', loginPgNotice);
                formData.append('instructions', instructions);
                formData.append('introToPrtlArtBody', introToPrtlArtBody);
                formData.append('ltstNewArtBody', ltstNewArtBody);
                formData.append('usefulLnksArtBody', usefulLnksArtBody);
                formData.append('aboutRho', aboutRho);
                formData.append('smplTokenWord', smplTokenWord);
                formData.append('abt_portal', abt_portal);
                formData.append('homepgfile', homepgfile);
                formData.append('browserPDFCmd', browserPDFCmd);
                formData.append('rhoAPIUrl', rhoAPIUrl);
                formData.append('putInMntnceMode', putInMntnceMode);
                dialog.init(function() {
                    $body = $("body");
                    $body.removeClass("mdlloading");
                    $.ajax({
                        url: 'index.php',
                        method: 'POST',
                        data: formData,
                        async: true,
                        cache: false,
                        contentType: false,
                        processData: false,
                        success: function(data) {
                            setTimeout(function() {
                                console.warn(data);
                                dialog.find('.bootbox-body').html(data.message);
                            }, 500);
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            console.log(textStatus + " " + errorThrown);
                            console.warn(jqXHR.responseText);
                        }
                    });
                });
            }
        </script>
        </head>
        <?php /* flush();  -webkit-calc(87vh);height: -moz-calc(87vh);height: calc(87vh) */ ?>

        <body style="<?php echo $bckcolorsChngPwd; ?>min-width:360px;min-height:430px;height:100% !important;width:100% !important;">
            <div class="container-fluid">
                <div class="row" style="min-height:65px;max-height:70px !important;height: 65px;border-bottom:0px solid #FFF;padding:0px;background-color: rgba(0,0,0,0.32);">
                    <div style="max-width:25%;float:left;"><img src="cmn_images/<?php echo $app_image1; ?>" style="left: 0.5%; margin:2px; padding-right: 1em; height:60px; width:auto; position: relative; vertical-align: middle;"></div>
                    <div class="hdrDiv" style="max-width:90%;color:#FFF;text-align:center;float:none;">
                        <span class="h4"><?php echo $app_name; ?></span><br />
                        <span class="h6"><?php echo $app_slogan; ?></span>
                    </div>
                </div>
                <div class="row" style="min-height:215px;height: 100% !important;background-color: rgba(0,0,0,0.22);">
                    <div class="col-md-1">&nbsp;</div>
                    <div class="col-md-10">
                        <div class="center-block" id="loginDiv" style="min-width: 700px !important;max-width: 1400px !important;">
                            <div class="login-panel panel panel-default login" style="min-width: 700px !important;max-width: 1400px !important;">
                                <h3 class="panel-title logintitle">CONFIGURE THE RHOMI ERP/BANKING SYSTEM</h3>
                                <form method="post" action="" style="width:100%;" onSubmit="return false;">
                                    <div class="row">
                                        <?php
                                        if ($superAdminConfigPswd2 !== $superAdminConfigPswd1 || $superAdminConfigPswd1 === "") {
                                        ?>
                                            <div class="col-md-12" style="padding: 5px 20px 5px 20px !important;">
                                                <fieldset class="basic_person_fs2" style="min-height:50px !important;padding: 5px 5px 5px 5px !important;border: 1px solid #eee !important;">
                                                    <div class="form-group">
                                                        <div class="input-group">
                                                            <span class="input-group-addon"><i class="fa fa-key fa-fw fa-border"></i>&nbsp;Super Admin Config Password</span>
                                                            <input class="form-control" placeholder="" id="superAdminConfigPswd" name="superAdminConfigPswd" type="password" value="" onkeyup="enterKeyFunc(event);" autofocus>
                                                        </div>
                                                    </div>
                                                    <p class="others">
                                                        <button type="button" name="commit" class="btn btn-md btn-default btn-block otherButton" onclick="verifyRhoConfigPwd();">Verify Configuration Password</button>
                                                    </p>
                                                    <p class="others">
                                                        <button type="button" name="cancel" class="btn btn-md btn-default btn-block otherButton" onclick="logoutRhoConfigPwd();">Clear Session Data</button>
                                                    </p>
                                                    <p class="others">
                                                        <button type="button" name="cancel" class="btn btn-md btn-default btn-block otherButton" onclick="window.location = 'index.php';">Return to Home Page</button>
                                                    </p>
                                                </fieldset>
                                            </div>
                                        <?php } else { ?>
                                            <div class="col-md-6" style="padding: 5px 20px 5px 20px !important;">
                                                <fieldset class="basic_person_fs2" style="min-height:50px !important;padding: 5px 5px 5px 5px !important;border: 1px solid #eee !important;">
                                                    <div class="form-group">
                                                        <div class="input-group">
                                                            <span class="input-group-addon"><i class="fa fa-gear fa-fw fa-border"></i>&nbsp;Page Title</span>
                                                            <input class="form-control" type="text" id="page_title" name="page_title" value="<?php echo $page_title; ?>" />
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="input-group">
                                                            <span class="input-group-addon"><i class="fa fa-gear fa-fw fa-border"></i>&nbsp;App URL</span>
                                                            <input class="form-control" type="text" id="app_url" name="app_url" value="<?php echo $app_url; ?>" />
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="input-group">
                                                            <span class="input-group-addon"><i class="fa fa-gear fa-fw fa-border"></i>&nbsp;Flexcode URL</span>
                                                            <input class="form-control" type="text" id="flxcde_url" name="flxcde_url" value="<?php echo $flxcde_url; ?>" />
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="input-group">
                                                            <span class="input-group-addon"><i class="fa fa-gear fa-fw fa-border"></i>&nbsp;App Name</span>
                                                            <input class="form-control" type="text" id="app_name" name="app_name" value="<?php echo $app_name; ?>" />
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="input-group">
                                                            <span class="input-group-addon"><i class="fa fa-gear fa-fw fa-border"></i>&nbsp;System Name</span>
                                                            <input class="form-control" type="text" id="system_name" name="system_name" value="<?php echo $system_name; ?>" />
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="input-group">
                                                            <span class="input-group-addon"><i class="fa fa-gear fa-fw fa-border"></i>&nbsp;Client's Name</span>
                                                            <input class="form-control" type="text" id="app_cstmr" name="app_cstmr" value="<?php echo $app_cstmr; ?>" />
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="input-group">
                                                            <span class="input-group-addon"><i class="fa fa-gear fa-fw fa-border"></i>&nbsp;Client's Website</span>
                                                            <input class="form-control" type="text" id="app_cstmr_url" name="app_cstmr_url" value="<?php echo $app_cstmr_url; ?>" />
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="input-group">
                                                            <span class="input-group-addon"><i class="fa fa-gear fa-fw fa-border"></i>&nbsp;Slogan</span>
                                                            <input class="form-control" type="text" id="app_slogan" name="app_slogan" value="<?php echo $app_slogan; ?>" />
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="input-group">
                                                            <span class="input-group-addon"><i class="fa fa-gear fa-fw fa-border"></i>&nbsp;Admin Email</span>
                                                            <input class="form-control" type="text" id="admin_email" name="admin_email" value="<?php echo $admin_email; ?>" />
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="input-group">
                                                            <span class="input-group-addon"><i class="fa fa-gear fa-fw fa-border"></i>&nbsp;Admin's Name</span>
                                                            <input class="form-control" type="text" id="admin_name" name="admin_name" value="<?php echo $admin_name; ?>" />
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="input-group">
                                                            <span class="input-group-addon"><i class="fa fa-gear fa-fw fa-border"></i>&nbsp;JS/CSS File Version</span>
                                                            <input class="form-control" type="text" id="jsCssFileVrsn" name="jsCssFileVrsn" value="<?php echo $jsCssFileVrsn; ?>" />
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="input-group">
                                                            <span class="input-group-addon"><i class="fa fa-gear fa-fw fa-border"></i>&nbsp;Notices Element</span>
                                                            <input class="form-control" type="text" id="noticesElmntNm" name="noticesElmntNm" value="<?php echo $noticesElmntNm; ?>" />
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="input-group">
                                                            <span class="input-group-addon"><i class="fa fa-gear fa-fw fa-border"></i>&nbsp;Sample Password</span>
                                                            <input class="form-control" type="text" id="smplPwd" name="smplPwd" value="<?php echo $smplPwd; ?>" />
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="input-group">
                                                            <span class="input-group-addon"><i class="fa fa-gear fa-fw fa-border"></i>&nbsp;PostgreSQL DB Host:</span>
                                                            <input class="form-control" type="text" id="host" name="host" value="<?php echo $host; ?>" />
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="input-group">
                                                            <span class="input-group-addon"><i class="fa fa-gear fa-fw fa-border"></i>&nbsp;PostgreSQL DB Name</span>
                                                            <input class="form-control" type="text" id="database" name="database" value="<?php echo $database; ?>" />
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="input-group">
                                                            <span class="input-group-addon"><i class="fa fa-gear fa-fw fa-border"></i>&nbsp;PostgreSQL DB User</span>
                                                            <input class="form-control" type="text" id="db_usr" name="db_usr" value="<?php echo $db_usr; ?>" />
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="input-group">
                                                            <span class="input-group-addon"><i class="fa fa-key fa-fw fa-border"></i>&nbsp;PostgreSQL DB User Password</span>
                                                            <input class="form-control" placeholder="" id="db_pwd" name="db_pwd" type="password" value="<?php echo $db_pwd; ?>">
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="input-group">
                                                            <span class="input-group-addon"><i class="fa fa-gear fa-fw fa-border"></i>&nbsp;DB Port No.</span>
                                                            <input class="form-control" type="text" id="port" name="port" value="<?php echo $port; ?>" />
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="input-group">
                                                            <span class="input-group-addon"><i class="fa fa-key fa-fw fa-border"></i>&nbsp;Postgres User Password</span>
                                                            <input class="form-control" placeholder="" id="postgre_db_pwd" name="postgre_db_pwd" type="password" value="<?php echo $postgre_db_pwd; ?>">
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="input-group">
                                                            <span class="input-group-addon"><i class="fa fa-gear fa-fw fa-border"></i>&nbsp;Database Folder</span>
                                                            <input class="form-control" type="text" id="db_folder" name="db_folder" value="<?php echo $db_folder; ?>" />
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="input-group">
                                                            <span class="input-group-addon"><i class="fa fa-gear fa-fw fa-border"></i>&nbsp;Folder Prefix</span>
                                                            <input class="form-control" type="text" id="fldrPrfx" name="fldrPrfx" value="<?php echo $fldrPrfx; ?>" />
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="input-group">
                                                            <span class="input-group-addon"><i class="fa fa-gear fa-fw fa-border"></i>&nbsp;Temporary Destination</span>
                                                            <input class="form-control" type="text" id="tmpDest" name="tmpDest" value="<?php echo $tmpDest; ?>" />
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="input-group">
                                                            <span class="input-group-addon"><i class="fa fa-gear fa-fw fa-border"></i>&nbsp;Permanent Destination</span>
                                                            <input class="form-control" type="text" id="pemDest" name="pemDest" value="<?php echo $pemDest; ?>" />
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="input-group">
                                                            <span class="input-group-addon"><i class="fa fa-gear fa-fw fa-border"></i>&nbsp;FTP Base DB Folder</span>
                                                            <input class="form-control" type="text" id="ftp_base_db_fldr" name="ftp_base_db_fldr" value="<?php echo $ftp_base_db_fldr; ?>" />
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="input-group">
                                                            <span class="input-group-addon"><i class="fa fa-gear fa-fw fa-border"></i>&nbsp;MySQL DB Name</span>
                                                            <input class="form-control" type="text" id="mysql_db_name" name="mysql_db_name" value="<?php echo $mysql_db_name; ?>" />
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="input-group">
                                                            <span class="input-group-addon"><i class="fa fa-gear fa-fw fa-border"></i>&nbsp;MySQL DB User</span>
                                                            <input class="form-control" type="text" id="mysql_db_user" name="mysql_db_user" value="<?php echo $mysql_db_user; ?>" />
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="input-group">
                                                            <span class="input-group-addon"><i class="fa fa-gear fa-fw fa-border"></i>&nbsp;MySQL DB Password</span>
                                                            <input class="form-control" type="password" id="mysql_db_pass" name="mysql_db_pass" value="<?php echo $mysql_db_pass; ?>" />
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="input-group">
                                                            <span class="input-group-addon"><i class="fa fa-gear fa-fw fa-border"></i>&nbsp;MySQL DB Host</span>
                                                            <input class="form-control" type="text" id="mysql_db_host" name="mysql_db_host" value="<?php echo $mysql_db_host; ?>" />
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="input-group">
                                                            <span class="input-group-addon"><i class="fa fa-gear fa-fw fa-border"></i>&nbsp;MySQL DB Port</span>
                                                            <input class="form-control" type="text" id="mysql_db_port" name="mysql_db_port" value="<?php echo $mysql_db_port; ?>" />
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="input-group">
                                                            <span class="input-group-addon"><i class="fa fa-key fa-fw fa-border"></i>&nbsp;Super Admin Config Password</span>
                                                            <input class="form-control" placeholder="" id="superAdminConfigPswd" name="superAdminConfigPswd" type="password" value="<?php echo $superAdminConfigPswd; ?>">
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="input-group">
                                                            <span class="input-group-addon"><i class="fa fa-gear fa-fw fa-border"></i>&nbsp;Home Page File</span>
                                                            <input class="form-control" type="text" id="homepgfile" name="homepgfile" value="<?php echo $homepgfile; ?>" />
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="input-group">
                                                            <span class="input-group-addon"><i class="fa fa-gear fa-fw fa-border"></i>&nbsp;Maintenance Mode? (YES/NO)</span>
                                                            <input class="form-control" type="text" id="putInMntnceMode" name="putInMntnceMode" value="<?php echo $putInMntnceMode; ?>" />
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="input-group">
                                                            <span class="input-group-addon"><i class="fa fa-gear fa-fw fa-border"></i>&nbsp;Config. File Location</span>
                                                            <input class="form-control" type="text" id="superAdminConfigFilePath" name="superAdminConfigFilePath" value="<?php echo $superAdminConfigFilePath; ?>" />
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="input-group">
                                                            <span class="input-group-addon"><i class="fa fa-gear fa-fw fa-border"></i>&nbsp;Software License</span>
                                                            <input class="form-control" type="text" id="softwareLincenseCode" name="softwareLincenseCode" value="<?php echo $softwareLincenseCode; ?>" />
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="input-group" style="width:100%;">
                                                            <span class="input-group-addon"><i class="fa fa-key fa-fw fa-border"></i>&nbsp;Token Word 1:</span>
                                                            <textarea class="form-control rqrdFld" rows="2" cols="20" id="smplTokenWord1" name="smplTokenWord1" style="text-align:left !important;"><?php echo $smplTokenWord1; ?></textarea>
                                                            <label class="btn btn-primary btn-file input-group-addon" onclick="popUpDisplay('smplTokenWord1');" style="max-width:30px;width:30px;">
                                                                <span class="glyphicon glyphicon-th-list"></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </fieldset>
                                            </div>
                                            <div class="col-md-6" style="padding: 5px 20px 5px 20px !important;">
                                                <fieldset class="basic_person_fs2" style="min-height:50px !important;padding: 5px 5px 5px 5px !important;border: 1px solid #ddd !important;">
                                                    <div class="form-group">
                                                        <div class="input-group">
                                                            <span class="input-group-addon"><i class="fa fa-gear fa-fw fa-border"></i>&nbsp;PDF Command (E.g. google-chrome,chromium-browser,chromium)</span>
                                                            <input class="form-control" type="text" id="browserPDFCmd" name="browserPDFCmd" value="<?php echo $browserPDFCmd; ?>" />
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="input-group">
                                                            <span class="input-group-addon"><i class="fa fa-gear fa-fw fa-border"></i>&nbsp;Rhomicom APIs URL</span>
                                                            <input class="form-control" type="text" id="rhoAPIUrl" name="rhoAPIUrl" value="<?php echo $rhoAPIUrl; ?>" />
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="input-group">
                                                            <span class="input-group-addon"><i class="fa fa-gear fa-fw fa-border"></i>&nbsp;Logo 1</span>
                                                            <input class="form-control" type="text" id="app_image1" name="app_image1" value="<?php echo $app_image1; ?>" />
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="input-group">
                                                            <span class="input-group-addon"><i class="fa fa-gear fa-fw fa-border"></i>&nbsp;Favicon</span>
                                                            <input class="form-control" type="text" id="app_favicon" name="app_favicon" value="<?php echo $app_favicon; ?>" />
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="input-group">
                                                            <span class="input-group-addon"><i class="fa fa-gear fa-fw fa-border"></i>&nbsp;Background Color Only</span>
                                                            <input class="form-control" type="text" id="bckcolorOnly" name="bckcolorOnly" value="<?php echo $bckcolorOnly; ?>" />
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="input-group">
                                                            <span class="input-group-addon"><i class="fa fa-gear fa-fw fa-border"></i>&nbsp;Background Color Only 1</span>
                                                            <input class="form-control" type="text" id="bckcolorOnly1" name="bckcolorOnly1" value="<?php echo $bckcolorOnly1; ?>" />
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="input-group">
                                                            <span class="input-group-addon"><i class="fa fa-gear fa-fw fa-border"></i>&nbsp;Background Color Only 2</span>
                                                            <input class="form-control" type="text" id="bckcolorOnly2" name="bckcolorOnly2" value="<?php echo $bckcolorOnly2; ?>" />
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="input-group">
                                                            <span class="input-group-addon"><i class="fa fa-gear fa-fw fa-border"></i>&nbsp;On Hover Background Color</span>
                                                            <input class="form-control" type="text" id="bckcolorshv" name="bckcolorshv" value="<?php echo $bckcolorshv; ?>" />
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="input-group">
                                                            <span class="input-group-addon"><i class="fa fa-gear fa-fw fa-border"></i>&nbsp;Text Fore Colors</span>
                                                            <input class="form-control" type="text" id="forecolors" name="forecolors" value="<?php echo $forecolors; ?>" />
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="input-group">
                                                            <span class="input-group-addon"><i class="fa fa-gear fa-fw fa-border"></i>&nbsp;Back Color 1</span>
                                                            <input class="form-control" type="text" id="bckcolors1" name="bckcolors1" value="<?php echo $bckcolors1; ?>" />
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="input-group">
                                                            <span class="input-group-addon"><i class="fa fa-gear fa-fw fa-border"></i>&nbsp;Back Color 2</span>
                                                            <input class="form-control" type="text" id="bckcolors2" name="bckcolors2" value="<?php echo $bckcolors2; ?>" />
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="input-group">
                                                            <span class="input-group-addon"><i class="fa fa-gear fa-fw fa-border"></i>&nbsp;Go Back Button Msg</span>
                                                            <input class="form-control" type="text" id="goBackButtonMsg" name="goBackButtonMsg" value="<?php echo $goBackButtonMsg; ?>" />
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="input-group">
                                                            <span class="input-group-addon"><i class="fa fa-gear fa-fw fa-border"></i>&nbsp;User Name Placeholder</span>
                                                            <input class="form-control" type="text" id="placeHolder1" name="placeHolder1" value="<?php echo $placeHolder1; ?>" />
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="input-group">
                                                            <span class="input-group-addon"><i class="fa fa-gear fa-fw fa-border"></i>&nbsp;Login Title</span>
                                                            <input class="form-control" type="text" id="loginTitle" name="loginTitle" value="<?php echo $loginTitle; ?>" />
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="input-group">
                                                            <span class="input-group-addon"><i class="fa fa-gear fa-fw fa-border"></i>&nbsp;Website Button Text</span>
                                                            <input class="form-control" type="text" id="website_btn_txt" name="website_btn_txt" value="<?php echo $website_btn_txt; ?>" />
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="input-group" style="width:100%;">
                                                            <span class="input-group-addon"><i class="fa fa-gear fa-fw fa-border"></i>&nbsp;Background Colors:</span>
                                                            <textarea class="form-control rqrdFld" rows="2" cols="20" id="bckcolors" name="bckcolors" style="text-align:left !important;"><?php echo $bckcolors; ?></textarea>
                                                            <label class="btn btn-primary btn-file input-group-addon" onclick="popUpDisplay('bckcolors');" style="max-width:30px;width:30px;">
                                                                <span class="glyphicon glyphicon-th-list"></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="input-group" style="width:100%;">
                                                            <span class="input-group-addon"><i class="fa fa-gear fa-fw fa-border"></i>&nbsp;Change Pwd. Page CSS:</span>
                                                            <textarea class="form-control rqrdFld" rows="2" cols="20" id="bckcolorsChngPwd" name="bckcolorsChngPwd" style="text-align:left !important;"><?php echo $bckcolorsChngPwd; ?></textarea>
                                                            <label class="btn btn-primary btn-file input-group-addon" onclick="popUpDisplay('bckcolorsChngPwd');" style="max-width:30px;width:30px;">
                                                                <span class="glyphicon glyphicon-th-list"></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="input-group" style="width:100%;">
                                                            <span class="input-group-addon"><i class="fa fa-gear fa-fw fa-border"></i>&nbsp;Home Background CSS:</span>
                                                            <textarea class="form-control rqrdFld" rows="2" cols="20" id="bckcolors_home" name="bckcolors_home" style="text-align:left !important;"><?php echo $bckcolors_home; ?></textarea>
                                                            <label class="btn btn-primary btn-file input-group-addon" onclick="popUpDisplay('bckcolors_home');" style="max-width:30px;width:30px;">
                                                                <span class="glyphicon glyphicon-th-list"></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="input-group" style="width:100%;">
                                                            <span class="input-group-addon"><i class="fa fa-gear fa-fw fa-border"></i>&nbsp;Breadcrumb CSS:</span>
                                                            <textarea class="form-control rqrdFld" rows="2" cols="20" id="breadCrmbBckclr" name="breadCrmbBckclr" style="text-align:left !important;"><?php echo $breadCrmbBckclr; ?></textarea>
                                                            <label class="btn btn-primary btn-file input-group-addon" onclick="popUpDisplay('breadCrmbBckclr');" style="max-width:30px;width:30px;">
                                                                <span class="glyphicon glyphicon-th-list"></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="input-group" style="width:100%;">
                                                            <span class="input-group-addon"><i class="fa fa-gear fa-fw fa-border"></i>&nbsp;Login Page Notice</span>
                                                            <textarea class="form-control rqrdFld" rows="2" cols="20" id="loginPgNotice" name="loginPgNotice" style="text-align:left !important;"><?php echo $loginPgNotice; ?></textarea>
                                                            <label class="btn btn-primary btn-file input-group-addon" onclick="popUpDisplay('loginPgNotice');" style="max-width:30px;width:30px;">
                                                                <span class="glyphicon glyphicon-th-list"></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="input-group" style="width:100%;">
                                                            <span class="input-group-addon"><i class="fa fa-gear fa-fw fa-border"></i>&nbsp;Instructions</span>
                                                            <textarea class="form-control rqrdFld" rows="2" cols="20" id="instructions" name="instructions" style="text-align:left !important;"><?php echo $instructions; ?></textarea>
                                                            <label class="btn btn-primary btn-file input-group-addon" onclick="popUpDisplay('instructions');" style="max-width:30px;width:30px;">
                                                                <span class="glyphicon glyphicon-th-list"></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="input-group" style="width:100%;">
                                                            <span class="input-group-addon"><i class="fa fa-gear fa-fw fa-border"></i>&nbsp;About Portal</span>
                                                            <textarea class="form-control rqrdFld" rows="2" cols="20" id="abt_portal" name="abt_portal" style="text-align:left !important;"><?php echo $abt_portal; ?></textarea>
                                                            <label class="btn btn-primary btn-file input-group-addon" onclick="popUpDisplay('abt_portal');" style="max-width:30px;width:30px;">
                                                                <span class="glyphicon glyphicon-th-list"></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="input-group" style="width:100%;">
                                                            <span class="input-group-addon"><i class="fa fa-gear fa-fw fa-border"></i>&nbsp;Intro. to Portal</span>
                                                            <textarea class="form-control rqrdFld" rows="2" cols="20" id="introToPrtlArtBody" name="introToPrtlArtBody" style="text-align:left !important;"><?php echo $introToPrtlArtBody; ?></textarea>
                                                            <label class="btn btn-primary btn-file input-group-addon" onclick="popUpDisplay('introToPrtlArtBody');" style="max-width:30px;width:30px;">
                                                                <span class="glyphicon glyphicon-th-list"></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="input-group" style="width:100%;">
                                                            <span class="input-group-addon"><i class="fa fa-gear fa-fw fa-border"></i>&nbsp;Latest Articles</span>
                                                            <textarea class="form-control rqrdFld" rows="2" cols="20" id="ltstNewArtBody" name="ltstNewArtBody" style="text-align:left !important;"><?php echo $ltstNewArtBody; ?></textarea>
                                                            <label class="btn btn-primary btn-file input-group-addon" onclick="popUpDisplay('ltstNewArtBody');" style="max-width:30px;width:30px;">
                                                                <span class="glyphicon glyphicon-th-list"></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="input-group" style="width:100%;">
                                                            <span class="input-group-addon"><i class="fa fa-gear fa-fw fa-border"></i>&nbsp;Useful Links.</span>
                                                            <textarea class="form-control rqrdFld" rows="2" cols="20" id="usefulLnksArtBody" name="usefulLnksArtBody" style="text-align:left !important;"><?php echo $usefulLnksArtBody; ?></textarea>
                                                            <label class="btn btn-primary btn-file input-group-addon" onclick="popUpDisplay('usefulLnksArtBody');" style="max-width:30px;width:30px;">
                                                                <span class="glyphicon glyphicon-th-list"></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="input-group" style="width:100%;">
                                                            <span class="input-group-addon"><i class="fa fa-gear fa-fw fa-border"></i>&nbsp;About Rhomicom</span>
                                                            <textarea class="form-control rqrdFld" rows="2" cols="20" id="aboutRho" name="aboutRho" style="text-align:left !important;"><?php echo $aboutRho; ?></textarea>
                                                            <label class="btn btn-primary btn-file input-group-addon" onclick="popUpDisplay('aboutRho');" style="max-width:30px;width:30px;">
                                                                <span class="glyphicon glyphicon-th-list"></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="input-group" style="width:100%;">
                                                            <span class="input-group-addon"><i class="fa fa-key fa-fw fa-border"></i>&nbsp;Token Word:</span>
                                                            <textarea class="form-control rqrdFld" rows="2" cols="20" id="smplTokenWord" name="smplTokenWord" style="text-align:left !important;"><?php echo $smplTokenWord; ?></textarea>
                                                            <label class="btn btn-primary btn-file input-group-addon" onclick="popUpDisplay('smplTokenWord');" style="max-width:30px;width:30px;">
                                                                <span class="glyphicon glyphicon-th-list"></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <p class="others">
                                                        <button type="button" name="commit" class="btn btn-md btn-default btn-block otherButton" onclick="updateRhoConfigFiles();">Update Configuration File</button>
                                                    </p>
                                                    <p class="others">
                                                        <button type="button" name="logout" class="btn btn-md btn-default btn-block otherButton" onclick="logoutRhoConfigPwd();">Logout of Rhomi Configuartion</button>
                                                    </p>
                                                    <p class="others">
                                                        <button type="button" name="cancel" class="btn btn-md btn-default btn-block otherButton" onclick="window.location = 'index.php';">Return to Home Page</button>
                                                    </p>
                                                </fieldset>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-1">&nbsp;</div>
                </div>
                <div class="row" style="min-height:25px;height: -webkit-calc(8vh);height: -moz-calc(8vh);height: calc(8vh);background-color: rgba(0,0,0,0.22);">
                    <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                </div>
                <div class="row" style="min-height:25px;height: 25px;background-color: rgba(0,0,0,0.32);">
                    <div class="col-md-12" style="color:#FFF;font-family: Times;font-style: italic;font-size:12px;text-align:center;border-top:1px solid #999;">
                        <p class="rho-page-footer">Copyright &COPY; <?php echo date('Y'); ?> <a style="color:#FFF" href="<?php echo $about_url; ?>" target="_blank"><?php echo $app_org; ?></a>.</p>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="modal-7" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
                <div class="modal-dialog modal-sm">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <span class="modal-title msgtitle">System Alert!</span>
                        </div>
                        <div class="modal-body">
                            Content is loading...
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-white" data-dismiss="modal">OK</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- jQuery -->
            <script src="cmn_scrpts/jquery-1.11.3.min.js"></script>
            <!-- Bootstrap Core JavaScript -->
            <script src="cmn_scrpts/bootstrap337/js/bootstrap.min.js"></script>
        </body>

        </html>
<?php
    }
} else {
    echo "Inside rhoconfig NOTHING TO SHOW";
}
?>