<?php
require 'app_code/cmncde/globals.php';
$brwsr = getBrowser();
$notAllowed = "Browser (Name:" . $brwsr['name'] . " Version: " . $brwsr['version'] . ")";
echo "<html>
        <head>
            <link rel=\"stylesheet\" type=\"text/css\" href=\"cmn_scrpts/rho_form.css?v=12\" />        
            </head>
            <body><div id=\"rho_form\" style=\"width: 500px;position: absolute; top:20%; bottom: 20%; left: 20%; right: 20%; margin: auto;\">
            <div class='rho_form44' style=\"font-family: Tahoma, Arial, sans-serif;font-size: 1.3em;
                    background-color:#e3e3e3;border: 1px solid #999;padding:20px 30px 30px 20px;\"> 
            " . $notAllowed . " not Supported! <br/> Please upgrade to the latest version! 
                    <br/> Alternatively, you can download and Install the ff Recommended Browsers...
                    <ul>
                    <li><a href=\"https://www.google.com/chrome/browser/desktop/index.html\">Google Chrome</a></li>
                    <li><a href=\"https://www.mozilla.org/en-US/firefox/new/\">Firefox</a></li>
                    <li><a href=\"index.php\">Or You can click here to Try Again!</a></li>
                    </ul>
                    </div></div></body><html>";


                
            
?>
