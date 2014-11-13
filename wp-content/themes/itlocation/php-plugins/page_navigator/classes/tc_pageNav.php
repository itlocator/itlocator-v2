<?php

//*********************************************************
// The php page navigator component 
// written by TJ @ triconsole
//
// version 1.1 (12 Apr 2009)
//********************************************************

class tc_pageNav {

    var $d_nav_length = 0; //default show all navigator page
    var $page = 1; //current display page, default 1
    var $ppg = 30; //item per page, default 30
    var $min_nav;
    var $max_nav;
    var $total_records;
    var $page_count;
    var $search_opts;
    var $print_inactive_navigator = true;
    var $nav_type = 0; //0=simple, 1=friendly
    var $nav_form_printed = false;
    var $part_nav_interval = 3;
    //-----------------------------------------------	
    //friendly url, sample format => 'home-%page-%var1-%var2.php'
    //
	// 'page' is no need to be set, navigator will automatically put the current page on this variable
    // 'var1' can be set by 
    //		addSearchOption("var1", "hello1");
    // 'var2' can be set by 
    //		addSearchOption("var2", "test2");
    //-----------------------------------------------	
    var $friendly_url;

    function tc_pageNav($total_records, $page = 1) {
        $pagenum = isset($_REQUEST["cPage"]) ? $_REQUEST["cPage"] : $page;
        $pagesize = isset($_REQUEST["pageSize"]) ? $_REQUEST["pageSize"] : $this->ppg;

        $this->page = $pagenum;
        if ($pagesize > 0)
            $this->ppg = $pagesize;
        $this->total_records = $total_records;
        $this->search_opts = array();
        $this->friendly_url = $_SERVER['PHP_SELF']; //default current page name if it is not set
    }

    function calculate() {
        //set total pages
        if ($this->total_records <= $this->ppg)
            $page_count = 1;
        elseif (($this->total_records % $this->ppg) == 0)
            $page_count = (int) ($this->total_records / $this->ppg);
        else
            $page_count = (int) ($this->total_records / $this->ppg) + 1;

        $this->page_count = $page_count;

        if ($this->page > $page_count)
            $this->page = $page_count;

        //if navigator length is not specified
        if (!$this->d_nav_length)
            $this->d_nav_length = $this->page_count;

        //set minimum nav bar
        $this->min_nav = $this->page - (int) ($this->d_nav_length / 2) + 1;
        if ($this->min_nav < 1)
            $this->min_nav = 1;

        //set maximum nav bar
        $this->max_nav = $this->min_nav + $this->d_nav_length - 1;
        if ($this->max_nav < $this->d_nav_length)
            $this->max_nav = $this->d_nav_length;

        //adjust nav bar
        if ($this->max_nav > $page_count) {
            $this->max_nav = $page_count;
            $this->min_nav = $this->max_nav - ($this->d_nav_length - 1);
            if ($this->min_nav < 1)
                $this->min_nav = 1;
        }
    }

    function setNav($nav_length) {
        $this->d_nav_length = $nav_length;
    }

    function getPageStart() {
        return ($this->ppg * $this->page) - $this->ppg;
    }

    function setPerPage($ppg) {
        $this->ppg = $ppg;
    }

    function getPerPage() {
        return $this->ppg;
    }

    function getMinPage() {
        return $this->min_nav;
    }

    function getMaxPage() {
        return $this->max_nav;
    }

    function setPartInterval($int) {
        $this->part_nav_interval = $int;
    }

    function showInactiveNavigator($show) {
        $this->print_inactive_navigator = $show;
    }

    function setNavType($type) {
        $this->nav_type = $type;
    }

    function setFriendlyUrl($url) {
        $this->friendly_url = $url;
    }

    function getCurentPage() {
        return $this->page;
    }

    function printNavBar() {
        if ($this->nav_type == 0 && !$this->nav_form_printed) {
            echo($this->printNavForm($_SERVER['PHP_SELF']));
            $this->nav_form_printed = true;
        }

        if ($this->min_nav != $this->max_nav) {
            $this->printPrevious();
            for ($i = $this->min_nav; $i <= $this->max_nav; $i++) {
                if ((string) $this->page == (string) $i)
                    echo(" <B CLASS=\"currentpage\">$i</B>");
                else {
                    if ($this->nav_type == 0) { //simple navigator
                        echo(" <A HREF=\"javascript:;\" onclick=\"javascript:document.navForm.cPage.value=$i;document.navForm.submit();\" class=\"pagenav\">$i</A>");
                    } else {
                        //friendly navigator
                        $page = $this->friendly_url;

                        foreach ($this->search_opts as $search_field => $search_value) {
                            $find = '%' . $search_field;
                            $page = str_replace($find, $search_value, $page);
                        }
                        $page = str_replace('%page', $i, $page);
                        echo(" <A HREF=\"$page\" class=\"pagenav\">$i</A> ");
                    }
                }
            }
            $this->printNext();
        }
    }

    function printPrevious() {
        if ($this->page > 1) {
            $prev = (int) ($this->page) - 1;
            if ($this->nav_type == 0) { //simple navigator				
                echo("<A HREF=\"javascript:;\" onclick=\"javascript:document.navForm.cPage.value=$prev;document.navForm.submit();\" class=\"pagenav\">&#171;Previous</A> ");
            } else {
                //friendly navigator
                $page = $this->friendly_url;
                foreach ($this->search_opts as $search_field => $search_value) {
                    $find = '%' . $search_field;
                    $page = str_replace($find, $search_value, $page);
                }
                $page = str_replace('%page', $prev, $page);
                echo(" <A HREF=\"$page\" class=\"pagenav\">&#171;Previous</A> ");
            }
        } else
        if ($this->print_inactive_navigator)
            echo("<font class=\"inactivenav\">&#171;Previous</font> ");
    }

    function printNext() {
        if ($this->page < $this->page_count) {
            $next = (int) ($this->page) + 1;
            if ($this->nav_type == 0) { //simple navigator				
                echo(" <A HREF=\"javascript:;\" onclick=\"javascript:document.navForm.cPage.value=$next;document.navForm.submit();\" class=\"pagenav\">Next&#187;</A>");
            } else {
                //friendly navigator
                $page = $this->friendly_url;
                foreach ($this->search_opts as $search_field => $search_value) {
                    $find = '%' . $search_field;
                    $page = str_replace($find, $search_value, $page);
                }
                $page = str_replace('%page', $next, $page);
                echo(" <A HREF=\"$page\" class=\"pagenav\">Next&#187;</A> ");
            }
        } else
        if ($this->print_inactive_navigator)
            echo(" <font class=\"inactivenav\">Next&#187;</font>");
    }

    function printNavForm($pagename) {
        $saddon = "";
        foreach ($this->search_opts as $field => $value) {
            $saddon .= "<input name=\"$field\" type=\"hidden\" value=\"$value\">";
        }

        $txt = "<form name=\"navForm\" method=\"post\" action=\"$pagename\">
			  <input type=\"hidden\" name=\"cPage\">
              <input type=\"hidden\" name=\"pageSize\" value=\"" . $this->ppg . "\">
              <input type=\"hidden\" name=\"pScope\">        
			  $saddon
            </form>";
        return $txt;
    }

    function addSearchOption($field, $value) {
        $this->search_opts[$field] = $value;
    }

    function printNavBarPortion() {
        $start_interval = 1;
        $end_interval = $this->max_nav;

        //$param = strpos($pagename, "?");

        if ($this->min_nav != $this->max_nav) {
            //$this->printPrevious();
            //echo("(");
            echo '<ul>';

            if (($start_interval + $this->part_nav_interval - 1) >= ($end_interval - $this->part_nav_interval)) {
                //print all page
                for ($i = $start_interval; $i <= $end_interval; $i++) {
                    if (!strcmp($this->page, $i)) {
                        echo(' <li><span class="current">' . $i . '</span></li>');
                    } else {
                        //$pagename .= ($param !== false) ? "&cpage=$i" : "?cpage=$i";
                        //echo(" <A HREF=\"$pagename\" class=\"pagenav\">$i</A>");

                        if ($this->nav_type == 0) { //simple navigator
                            echo(' <li><a href="#">' . $i . '</a></li>');
                        } else {
                            //friendly navigator
                            $page = $this->friendly_url;

                            foreach ($this->search_opts as $search_field => $search_value) {
                                $find = '%' . $search_field;
                                $page = str_replace($find, $search_value, $page);
                            }
                            $page = str_replace('%page', $i, $page);
                            echo(' <li><a href="' . $page . '">$i</a></li> ');
                        }
                    }
                }
            } else {
                //print portion
                $page_to_printed = array();
                //find interval from first page

                $num = ceil(($this->part_nav_interval - 1) / 2);
                for ($i = 1; $i <= ($num + 1); $i++) {
                    $page_to_printed[$i] = "p";
                }
                //find interval from current page
                $start = $this->page - $num;
                $end = $this->page + $num;
                if ($start <= 0)
                    $start = 1;
                if ($end > $this->page_count)
                    $end = $this->page_count;
                for ($i = $start; $i <= $end; $i++) {
                    $page_to_printed[$i] = "p";
                }
                //find interval from last page
                for ($i = ($this->page_count - $num); $i <= $this->page_count; $i++) {
                    $page_to_printed[$i] = "p";
                }

                $prev_page = 0;
                foreach ($page_to_printed as $i => $nothing) {
                    if ($i != ($prev_page + 1))
                        echo('<li><span class="dots">...</span></li>'); //check for continous navigator
                    $prev_page = $i;
                    if (!strcmp($this->page, $i)) {
                        echo(' <li><span class="current">' . $i . '</span></li>');
                    } else {
                        //$pagename .= ($param !== false) ? "&cpage=$i" : "?cpage=$i";
                        //echo(" <A HREF=\"$pagename\" class=\"pagenav\">$i</A>");

                        if ($this->nav_type == 0) { //simple navigator
                            echo(' <li><a href="#">' . $i . '</a></li>');
                        } else {
                            //friendly navigator
                            $page = $this->friendly_url;

                            foreach ($this->search_opts as $search_field => $search_value) {
                                $find = '%' . $search_field;
                                $page = str_replace($find, $search_value, $page);
                            }
                            $page = str_replace('%page', $i, $page);
                            echo(' <a href="' . $page . '">$i</a> ');
                        }
                    }
                }
            }

            //$this->printNext();
            //echo(")");
            echo '</ul>';
        }
    }

    function printNavJump() {
        if (!$this->nav_form_printed) {
            echo($this->printNavForm($_SERVER['PHP_SELF']));
            $this->nav_form_printed = true;
        }
        echo("<form name=\"myPageJump\">");
        echo("Jump to page: ");
        //echo("<input type=\"text\" name=\"page_jump\" value=\"$this->page\">");
        echo("<select name=\"page_jump\"");
        echo(" onchange=\"javascript:document.navForm.cPage.value=this.form.page_jump.value;document.navForm.submit();\"");
        echo(">");
        for ($i = 1; $i <= $this->page_count; $i++) {
            $selected = (!strcmp($this->page, $i)) ? " selected" : "";
            echo("<option value=\"$i\"$selected>$i</option>");
        }
        echo("</select>");
        //echo("<input type=\"button\" name=\"page_jump_btn\" value=\"Go\" onclick=\"document.navForm.cPage.value=this.form.page_jump.value;document.navForm.submit();\">");
        echo("</form>");
    }

}

?>