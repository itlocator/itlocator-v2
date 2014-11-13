<?php require_once('classes/tc_pageNav.php'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Untitled Document</title>
        <link href="style.css" rel="stylesheet" type="text/css" />
    </head>

    <body>
        <table width="100%" border="0" cellspacing="0" cellpadding="5">
            <tr>
                <td><h3><b><a href="http://www.triconsole.com/php/page_navigator.php" target="_blank">PHP - Page Navigator</a></b></h3>
                    <table width="100%" border="0" cellspacing="0" cellpadding="10">
                        <tr>
                            <td><h5>Demo:</h5>
                                <p>This demo set total records equal to <b>150</b> and set <b>10</b> items per page</p>
                                <p><b>Page Jump Style</b></p>
                                <p style="padding-left: 15px;">
                                    <?php
                                    $totalRecords = 150;

                                    $page_nav = new tc_pageNav($totalRecords, 7);
                                    $page_nav->setPerPage(10);
                                    $page_nav->calculate();

                                    echo($page_nav->printNavJump());
                                    ?>
                                </p>
                                <br style="line-height: 10px;" />
                                <p><b>Page Navigator Style</b></p>
                                <p style="padding-left: 15px;"><b>&#8226; Simple Navigator</b><br />
                                    This style use javascript to set and post the value. This method can be used in general but not search engine friendly.<br />
                                    <br />
                                    <?php
                                    $page_nav->showInactiveNavigator(false);
                                    $page_nav->setNavType(0);

                                    echo($page_nav->printNavBar());
                                    ?>
                                </p>
                                <br style="line-height: 10px;" />
                                <p style="padding-left: 15px;"><b>&#8226; Friendly Navigator</b><br />
                                    This style require url rewriter to handle valid target url. This method is more likely search engine friendly.</p>
                                <p style="padding-left: 15px;"><font color="#FF0000">*</font>Please note that the below link will not be a valid link because url rewriting is not set<br />
                                    <br />
                                    <?php
                                    $page_nav->showInactiveNavigator(false);
                                    $page_nav->setFriendlyUrl("page_navigator-%page-%var1.html");
                                    $page_nav->setNavType(1);

                                    $page_nav->addSearchOption("var1", "test");
                                    echo($page_nav->printNavBar());
                                    ?>
                                </p>
                                <br style="line-height: 10px;" />
                                <p style="padding-left: 15px;"><b>&#8226; Partly Navigator</b><br />
                                    This style is useful when there are so many pages to navigate. Below the Interval is set to 5. Default interval is 3.<br />
                                    <br />
                                    <?php
                                    $page_nav->showInactiveNavigator(false);
                                    //$page_nav->friendly_url = "page_navigator-%page.html";
                                    $page_nav->setNavType(0);
                                    $page_nav->setPartInterval(5);

                                    echo($page_nav->printNavBarPortion());
                                    ?>
                                </p>
                                <hr />
                        </tr>
                    </table></td>
            </tr>
        </table>
    </body>
</html>
