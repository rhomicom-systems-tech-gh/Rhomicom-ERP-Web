<!DOCTYPE html>
<html style="height: 100%;" lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <!--<meta http-equiv="expires" value="Tue, 29 Mar 1983 07:20:55 GMT"/>
        <meta http-equiv="pragma" content="no-cache"/>-->
        <meta http-equiv="content-type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=1.0, minimum-scale=0.1, maximum-scale=10.0">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black">
        <meta name="description" content="Bootstrap 3 Full Blown Rhomicom ERP System">
        <meta name="author" content="Rhomicom Systems Tech. Ltd.">
        <meta name="keyword" content="Rhomicom, ERP, Enterprise Software, Circulars">
        <link rel="shortcut icon" href="<?php echo $app_favicon; ?>" type="image/x-icon" /> 
        <!-- Bootstrap Core CSS -->
        <link href="cmn_scrpts/bootstrap337/css/bootstrap.min.css" rel="stylesheet">
        <?php /* https://stackpath.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css */ ?>
        <link href="cmn_scrpts/fontawesome450/css/font-awesome.min.css" rel="stylesheet" type="text/css">
        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
            <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]--> 
        <link href="cmn_scrpts/bootstrap337/bootstrap3-dialog/css/bootstrap-dialog.min.css" rel="stylesheet" type="text/css" />
        <link href="cmn_scrpts/bootstrap337/datatables/DataTables-1.10.11/css/dataTables.bootstrap.min.css" rel="stylesheet" type="text/css" />  
        <link href="cmn_scrpts/bootstrap337/bootstrap-dtimepckr/css/bootstrap-datetimepicker.min.css"  rel="stylesheet" type="text/css" />
        <link rel="stylesheet" type="text/css" href="cmn_scrpts/jquery-ui-1121/jquery-ui.min.css">
        <link href="cmn_scrpts/summernote081/summernote.css" rel="stylesheet" type="text/css"/> 
        <link href="cmn_scrpts/carousel.css?v=<?php echo $jsCssFileVrsn; ?>" rel="stylesheet">
        <script src="cmn_scrpts/jquery-1.12.3.min.js"></script>
        <script type="text/javascript" src="cmn_scrpts/jquery-ui-1121/jquery-ui.min.js"></script>
        <!-- Bootstrap Core JavaScript -->
        <script src="cmn_scrpts/bootstrap337/js/bootstrap.min.js"></script>
        <script src="cmn_scrpts/bootstrap337/bootstrap3-dialog/js/bootstrap-dialog.min.js"></script>
        <script type="text/javascript" src="cmn_scrpts/bootstrap337/bootbox.min.js"></script>
        <script type="text/javascript" src="cmn_scrpts/pdfobject.min.js"></script>
        <script type="application/javascript" src="cmn_scrpts/header_scripts.js?v=<?php echo $jsCssFileVrsn; ?>"></script>
        <script type="text/javascript">
            if ((typeof window.Worker === "function"))
            {
                //Worker Supported
            } else
            {
                window.location = 'notsupported.php';
            }
        </script>
        <title><?php echo $page_title; ?></title>

        <style type="text/css">            

        </style>