<?php
    require 'routine.php';
    define('URL', 'https://www.hojomasaki.com/museuxm/index.php');

    $smarty = new Smarty();
    $smarty->template_dir = '.';
    $smarty->compile_dir = './smarty';
    $smarty->cache_dir = './smarty';
    $smarty->error_reporting = E_ALL & ~E_NOTICE;

    // $label = array('福岡','九州','中国','四国','関西','東海','北陸','甲信越','東京','関東','東北','北海道');
    // $pref = array("40", "41,42,43,44,45,46,47", "31,32,33,34,35", "36,37,38,39", "25,26,27,28,29,30", "21,22,23,24", "16,17,18", "15,19,20", "13", "8,9,10,11,12,14,48", "2,3,4,5,6,7", "1");
    $label = array('福岡','九州','中国','四国','関西','東海','中部','東京','関東','東北･北海道');
    $pref = array("40", "41,42,43,44,45,46,47", "31,32,33,34,35", "36,37,38,39", "25,26,27,28,29,30", "21,22,23,24", "16,17,18,15,19,20", "13", "8,9,10,11,12,14,48", "1,2,3,4,5,6,7");

    $data = array();
    $exhibit = array();
    $exhibition = "";
    $museum = "";
    $museums = "";
    $expedition = array();

    $sqlstr = "";
    $sqlstr2 = "";
    $wherestr = "";
    $wherestr2 = "";

    $order = "PREF DESC, NAMEK";
    $title = "";
    $header_title = "";

    $year = null;
    if (isset($_GET["year"])) $year = $_GET["year"];

    $mode = 2;
    if (isset($_GET['mode'])) $mode = $_GET['mode'];
    if (isset($_GET['title'])) $title = $_GET['title'];

    if (isset($_GET['pref'])) {
    	$wherestr = "M.PREF IN (" . $_GET['pref'] .")";
    	$order = "PREF, NAMEK";
    }

    if ($mode == 1) {
    	//美術館一覧
        $sqlstr = "SELECT M.ID";
        // $sqlstr .= ", NAMEJ";
        //1
        // $sqlstr .= ", CASE WHEN CI.NAMEJ IS NULL THEN M.NAMEJ ELSE M.NAMEJ || ' (' || CI.NAMEJ || ')' END ";
        $sqlstr .= ", M.NAMEJ";

        //2
        $sqlstr .= ", NAMEE, TO_CHAR(OPEN,'HH24:MI'), TO_CHAR(CLOSE,'HH24:MI'), TO_CHAR(CLOSEEX,'HH24:MI'), E.PATTERN, A.ABSENCE, URL, REDUCT, '', '', NOTE, CHECKED, MAP, SCHEDULE, '', P1.TAG, P2.TAG, P3.TAG, P4.TAG, TO_CHAR(C.START,'YYYY/MM/DD'), TO_CHAR(C.END,'YYYY/MM/DD')";
        $sqlstr .= ", CASE WHEN CI.NAMEJ IS NULL THEN CP.NAMEJ ELSE CP.NAMEJ || '・' || CI.NAMEJ END ";

        $sqlstr .= "FROM MUSEUM M LEFT JOIN ABSENCE A ON M.ABSENCE=A.ID LEFT JOIN EXPATTERN E ON M.EXPATTERN=E.ID LEFT JOIN PAYMENT P1 ON M.PAY1=P1.ID LEFT JOIN PAYMENT P2 ON M.PAY2=P2.ID LEFT JOIN PAYMENT P3 ON M.PAY3=P3.ID LEFT JOIN PAYMENT P4 ON M.REDUCT=P4.NAME LEFT JOIN CLOSE C ON M.ID=C.ID AND C.START<=CURRENT_DATE AND (C.END>=CURRENT_DATE OR C.END IS NULL) ";
        $sqlstr .= "LEFT JOIN CITY CI ON M.CITY=CI.ID ";
        $sqlstr .= "LEFT JOIN CITY CP ON M.PREF=CP.ID ";
        if (isset($_GET['fra'])) {
            $sqlstr .= "WHERE M.NOTE LIKE '%フランス人がときめいた日本の美術館%' ORDER BY M.NOTE";
        }
        else {
            $sqlstr .= "WHERE " . $wherestr . " AND M.DISABLE=FALSE ORDER BY " . $order;
        }

        print_r("<!--\n");
        print_r($sqlstr);
        print_r("\n-->");
        $result = pg_query($conn, $sqlstr);
        //print_r($result);

        $i = 0;
        while (($row = pg_fetch_array($result, NULL, PGSQL_NUM)) != NULL) {
			for ($j = 0, $k = 0; $j < count($row); $j++, $k++) {
                $data[$i][$j] = replace($row[$k]);
            }

            $i++;
        }
        //print_r($data);
    }
    else if ($mode == 2) {
    	//展覧会一覧
        $select = "SELECT COALESCE(P.TITLEJ_PRE, '') || E.NAMEJ || COALESCE(P.TITLEJ_POST, '') ";

        //1
        if (is_smartphone() && isset($_GET['museum'])) {
            $select .= ", ''";
        }
        else {
            $select .= ", COALESCE(MP.NAMEJ, M.NAMEJ)";
            // $select .= ", CASE WHEN CI.NAMEJ IS NULL THEN COALESCE(MP.NAMEJ, M.NAMEJ) ELSE COALESCE(MP.NAMEJ, M.NAMEJ) || ' (' || CI.NAMEJ || ')' END ";
        }

        //2,3
        if (isset($_GET['plan']) || isset($_GET['now'])) {
        	$select .= ", TO_CHAR(P.START, 'MM/DD') AS STARTDAY, TO_CHAR(P.END, 'MM/DD') AS ENDDAY, ";
        }
        else {
        	$select .= ", TO_CHAR(P.START, 'YYYY/MM/DD') AS STARTDAY, TO_CHAR(P.END, 'YYYY/MM/DD') AS ENDDAY, ";
    	}

        //4
        $select .= "CASE WHEN EXISTS(SELECT NULL FROM VIEW V WHERE V.EXHIBITION=P.EXHIBITION AND V.PLACE=P.PLACE) THEN '🔴' ";
        // $select .= "WHEN EXISTS(SELECT NULL FROM VIEW V WHERE V.EXHIBITION=P.EXHIBITION AND V.PLACE<>P.PLACE AND P.END>=CURRENT_DATE) THEN '🔶' ELSE '' END, ";
        $select .= "WHEN EXISTS(SELECT NULL FROM VIEW V WHERE V.EXHIBITION=P.EXHIBITION AND V.PLACE<>P.PLACE) THEN '🔶' ELSE '' END, ";

        //5
        $select .= "CASE WHEN 0 < (P.END - CURRENT_DATE) AND (P.END - CURRENT_DATE) < 10 THEN '<font color=\"red\">終了まであと' || (P.END - CURRENT_DATE + 1) || '日</font>' ";
        $select .= "WHEN 0 = (P.END - CURRENT_DATE) THEN '<font color=\"red\">本日最終日</font>' ";
        if (isset($_GET['now'])) {
	        $select .= "WHEN P.START<=CURRENT_DATE AND P.END >= CURRENT_DATE THEN '' ";
        }
        else {
	        $select .= "WHEN P.START<=CURRENT_DATE AND P.END >= CURRENT_DATE THEN '<font color=\"red\">開催中</font>' ";
    	}
        $select .= "WHEN P.END<CURRENT_DATE THEN '<font color=\"gray\">終了</font>' ";
        $select .= "WHEN (P.START - 14) < CURRENT_DATE THEN '<font color=\"black\">開幕まであと' || (P.START - CURRENT_DATE) || '日</font>' ";
        $select .= "WHEN P.START = CURRENT_DATE THEN '<font color=\"red\">本日開幕</font>' ";
        $select .= "ELSE '' END AS A, ";

        //6,7,8,9,10
        $select .= "P.URL, P.EXHIBITION, P.PLACE, M.CHECKED, P.SPECIAL, ";

        //11
        $select .= "CASE WHEN P.START <= CURRENT_DATE THEN P.END ELSE P.START END AS SORTDAY, ";

        //12-17
        $select .= "E.CATALOG, FALSE, P.REMARKS, E.REMARKS, '', E.URL";

        //18
        if ($wherestr2 != "") {
            $select .= ", TO_CHAR(VI.DAY, '<b>FMmm/DD</b>') AS VIEWDAY";
        }
        else if (isset($_POST['now'])) {
            $select .= ", VI.DAY AS VIEWDAY";
        }
        else {
            // $select .= ", STRING_AGG(TO_CHAR(VI.DAY, '<b>YY/MM/DD</b>'), '<br>' ORDER BY VI.DAY) AS VIEWDAY";
            $select .= ", ARRAY_TO_STRING(ARRAY_AGG(DISTINCT TO_CHAR(VI.DAY, '<b>YY/MM/DD</b>') ORDER BY TO_CHAR(VI.DAY, '<b>YY/MM/DD</b>')), '<BR>') AS VIEWDAY";
        }

        //19
        $select .= ", EP.ID ";

        //20
        $select .= ", CASE WHEN EP.FROM<>EP.TO AND DATE_PART('MONTH',EP.FROM)<>DATE_PART('MONTH',EP.TO) THEN EP.DEST || ' ' || TO_CHAR(EP.FROM, 'FMmm/FMDD') || '～' || TO_CHAR(EP.TO, 'FMmm/FMDD') ";
        $select .= "WHEN EP.FROM<>EP.TO AND DATE_PART('MONTH',EP.FROM)=DATE_PART('MONTH',EP.TO) THEN EP.DEST || ' ' || TO_CHAR(EP.FROM, 'FMmm/FMDD') || '～' || TO_CHAR(EP.TO, 'FMDD') ";
        $select .= "ELSE EP.DEST || ' ' || TO_CHAR(EP.FROM, 'FMmm/FMDD') END ";

        //21
        if (isset($_GET['year']) || isset($_GET['museum'])) {
        	$select .= ", ''";
        }
        else {
	        // $select .= ", STRING_AGG(C.NAMEJ, ',' ORDER BY M2.PREF) ";
            $select .= ", STRING_AGG(C.NAMEJ, ',' ORDER BY P2.END) ";
    	}

        //22
        $select .= ", E.ID AS EXID ";

        $from = "FROM PERIOD P LEFT JOIN EXHIBITION E ON P.EXHIBITION=E.ID ";
        $from .= "LEFT JOIN MUSEUM M ON P.PLACE=M.ID ";
        $from .= "LEFT JOIN EXPEDITION EP ON P.EXPEDITION=EP.ID ";
        $from .= "LEFT JOIN VIEW VI ON VI.EXHIBITION=P.EXHIBITION AND VI.PLACE=P.PLACE ";
        $from .= "LEFT JOIN MUSEUM_PAST MP ON M.ID=MP.ID AND P.END<=MP.ENDDAY ";
        $from .= "LEFT JOIN CITY CI ON M.CITY=CI.ID ";

        if (!isset($_GET['year']) && !isset($_GET['museum'])) {
	        $from .= "LEFT JOIN PERIOD P2 ON E.ID=P2.EXHIBITION AND P.PLACE<>P2.PLACE AND P2.END>=CURRENT_DATE ";
    	    $from .= "LEFT JOIN MUSEUM M2 ON P2.PLACE=M2.ID ";
        	$from .= "LEFT JOIN CITY C ON M2.PREF=C.ID ";
	    }

        $groupby = "GROUP BY E.NAMEJ, M.NAMEJ, STARTDAY, ENDDAY, A, P.URL, P.EXHIBITION, P.PLACE, M.CHECKED, P.SPECIAL, SORTDAY, E.CATALOG, FALSE, P.REMARKS, E.REMARKS, E.URL, EP.ID, MP.NAMEJ, E.ID, P.TITLEJ_PRE, P.TITLEJ_POST, CI.NAMEJ ";

        $sqlstr = $select;
        $sqlstr .= $from;
        $sqlstr .= "WHERE " . $wherestr . " ";
        $sqlstr .= "AND P.END>=CURRENT_DATE ";
        $sqlstr .= $groupby . ", VI.DAY ";
        $sqlstr .= "ORDER BY P.START, P.END, E.ID, VI.DAY";

        //美術館指定
        if (isset($_GET['museum'])) {
        	$museum = array();
            $sqlstr = $select;
            $sqlstr .= $from;
            $sqlstr .= "WHERE M.ID=" . $_GET['museum'] . " ";

            //$sqlstr .= "GROUP BY E.NAMEJ, M.NAMEJ, STARTDAY, ENDDAY, A, P.URL, P.EXHIBITION, P.PLACE, M.CHECKED, P.SPECIAL, SORTDAY, E.CATALOG, FALSE, P.REMARKS, E.REMARKS, EP.DESCRIPTION, E.URL, EP.ID, MP.NAMEJ ";
            $sqlstr .= $groupby;

            //休館
            $sqlstr .= "UNION ALL ";
            // $sqlstr .= "SELECT '<font color=red><b>休館</b></font>'";
            $sqlstr .= "SELECT CASE WHEN C.END IS NULL THEN '<font color=red><b>閉館</b></font>' ELSE '<font color=red><b>休館</b></font>' END ";
            if (is_smartphone()) {
                $sqlstr .= ", ''";
            }
            else {
	            $sqlstr .= ", COALESCE(MP.NAMEJ, M.NAMEJ)";
            }
            $sqlstr .= ", TO_CHAR(C.START, 'YYYY/MM/DD') AS STARTDAY, TO_CHAR(C.END, 'YYYY/MM/DD') AS ENDDAY,NULL,NULL,NULL,NULL,C.ID,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL AS VIEWDAY,NULL,NULL,NULL AS EXID FROM CLOSE C INNER JOIN MUSEUM M ON C.ID=M.ID AND C.ID=" . $_GET['museum'] . " ";
	        $sqlstr .= "LEFT JOIN MUSEUM_PAST MP ON M.ID=MP.ID AND C.END<=MP.ENDDAY ";
            $sqlstr .= "ORDER BY STARTDAY, ENDDAY, VIEWDAY, EXID";

            //美術館概要
            $sqlstr2 = "SELECT M.NAMEJ, TO_CHAR(M.OPEN,'FMHH24:MI'), TO_CHAR(M.CLOSE,'HH24:MI'), COALESCE(A.DESCRIPTION, A.ABSENCE), M.REDUCT, '', M.NOTE, M.URL, M.NAMEE, M.SCHEDULE, COALESCE(P1.NAME, '―'), COALESCE(P2.NAME, '―'), COALESCE(P3.NAME, '―') FROM MUSEUM M LEFT JOIN ABSENCE A ON M.ABSENCE=A.ID LEFT JOIN PAYMENT P1 ON M.PAY1=P1.ID LEFT JOIN PAYMENT P2 ON M.PAY2=P2.ID LEFT JOIN PAYMENT P3 ON M.PAY3=P3.ID WHERE M.ID=" . $_GET['museum'] . " ";

	        print_r("<!--");
    	    print_r($sqlstr2);
        	print_r("-->");
            $result = pg_query($conn, $sqlstr2);

            while (($row = pg_fetch_array($result, NULL, PGSQL_NUM)) != NULL) {
                for ($j = 0, $k = 0; $j < count($row); $j++, $k++) {
                    $museum[$j] = replace($row[$k]);
                }
            }

            $title = $museum[0];
            $header_title = $_GET['museum'] . " " . $museum[0];
        }

        if (isset($_GET['exhibition'])) {
            $sqlstr = $select;
            $sqlstr .= $from;
            $sqlstr .= "WHERE P.EXHIBITION=" . $_GET['exhibition'] . " ";
            // $sqlstr .= "GROUP BY E.NAMEJ, M.NAMEJ, STARTDAY, ENDDAY, A, P.URL, P.EXHIBITION, P.PLACE, M.CHECKED, P.SPECIAL, SORTDAY, E.CATALOG, FALSE, P.REMARKS, E.REMARKS, EP.DESCRIPTION, E.URL, EP.ID, MP.NAMEJ ";
            $sqlstr .= $groupby;
            $sqlstr .= "ORDER BY P.START, P.END, VIEWDAY";
        }

        //遠征
        if (isset($_GET['expedition'])) {
            $sqlstr = $select;
            $sqlstr .= $from;
            $sqlstr .= "WHERE EP.ID=" . $_GET['expedition'] . " ";
            $sqlstr .= $groupby . ", VI.DAY, VI.ORDER ";
            $sqlstr .= "ORDER BY VI.DAY, VI.ORDER, P.START, P.END";

            $sqlstr2 = "SELECT HOTEL FROM EXPEDITION WHERE ID=" . $_GET['expedition'] . " ";

            $result = pg_query($conn, $sqlstr2);
            // print_r($sqlstr2);

            while (($row = pg_fetch_array($result, NULL, PGSQL_NUM)) != NULL) {
                for ($j = 0, $k = 0; $j < count($row); $j++, $k++) {
                    $expedition[$j] = $row[$k];
                }
                // print_r($expedition);
            }
        }

        //年度指定
        if (isset($_GET['year'])) {
    	    // print $year;
            // $select = "SELECT E.NAMEJ";
	        $select = "SELECT COALESCE(P.TITLEJ_PRE, '') || E.NAMEJ || COALESCE(P.TITLEJ_POST, '') ";
            // $select .= ", COALESCE(MP.NAMEJ, M.NAMEJ)";
	        $select .= ", CASE WHEN CI.NAMEJ IS NULL THEN COALESCE(MP.NAMEJ, M.NAMEJ) ELSE COALESCE(MP.NAMEJ, M.NAMEJ) || ' (' || CI.NAMEJ || ')' END ";
            $select .= ", TO_CHAR(P.START, 'YYYY/MM/DD') AS STARTDAY, TO_CHAR(P.END, 'MM/DD') AS ENDDAY";
            $select .= ", ''";
            $select .= ", '', P.URL, P.EXHIBITION, P.PLACE, FALSE, FALSE, '', E.CATALOG, VI.GUIDE ";
            $select .= ", '', '', '', E.URL";

            //18
            $select .= ", STRING_AGG(TO_CHAR(VI.DAY, '<b>FMmm/DD</b>'), '<br>' ORDER BY VI.DAY) AS VIEWDAY";

            //19
            $select .= ", EP.ID ";

	        //20
            $select .= ", CASE WHEN EP.FROM<>EP.TO AND DATE_PART('MONTH',EP.FROM)<>DATE_PART('MONTH',EP.TO) THEN EP.DEST || ' ' || TO_CHAR(EP.FROM, 'FMmm/FMDD') || '～' || TO_CHAR(EP.TO, 'FMmm/FMDD') ";
            $select .= "WHEN EP.FROM<>EP.TO AND DATE_PART('MONTH',EP.FROM)=DATE_PART('MONTH',EP.TO) THEN EP.DEST || ' ' || TO_CHAR(EP.FROM, 'FMmm/FMDD') || '～' || TO_CHAR(EP.TO, 'FMDD') ";
            $select .= "ELSE EP.DEST || ' ' || TO_CHAR(EP.FROM, 'FMmm/FMDD') END";

            //21
            $select .= ", '' ";
            $select .= ", MIN(VI.DAY) AS SORTDAY ";

            $sqlstr = $select;
            $sqlstr .= $from;
            $sqlstr .= "WHERE VI.DAY>='" . $year ."-01-01' AND VI.DAY<='" . $year . "-12-31' ";
            $sqlstr .= "GROUP BY E.NAMEJ, M.NAMEJ, STARTDAY, ENDDAY, P.URL, P.EXHIBITION, P.PLACE, E.CATALOG, VI.GUIDE, E.URL, EP.ID, VI.ORDER, MP.NAMEJ, P.TITLEJ_PRE, P.TITLEJ_POST, CI.NAMEJ ";
            $sqlstr .= "ORDER BY SORTDAY, VI.ORDER";
            // print_r($sqlstr);
        }

        if (isset($_GET['plan'])) {
            $sqlstr = $select;
            $sqlstr .= $from;
            $sqlstr .= "WHERE P.END>=CURRENT_DATE ";
            $sqlstr .= "AND P.SPECIAL=TRUE ";
            $sqlstr .= "AND NOT EXISTS (SELECT NULL FROM VIEW V WHERE P.EXHIBITION=V.EXHIBITION AND P.PLACE=V.PLACE) ";
            $sqlstr .= $groupby . ", VI.DAY ";
            $sqlstr .= "ORDER BY P.START, P.END, E.ID, VI.DAY";
        }

        if (isset($_GET['now'])) {
            $sqlstr = $select;
            $sqlstr .= $from;
            $sqlstr .= "WHERE P.START<=CURRENT_DATE AND P.END>=CURRENT_DATE ";
            // $sqlstr .= "AND NOT EXISTS (SELECT NULL FROM VIEW V WHERE P.EXHIBITION=V.EXHIBITION AND P.PLACE=V.PLACE) ";
            $sqlstr .= $groupby . ", VI.DAY ";
            $sqlstr .= "ORDER BY P.END, P.START, E.ID, VI.DAY";
        }

        print_r("<!--\n");
        print_r($sqlstr);
        print_r("\n-->");
        $result = pg_query($conn, $sqlstr);
       	//error_log(print_r($result));

        $i = 0;
        while (($row = pg_fetch_array($result, NULL, PGSQL_NUM)) != NULL) {
            // print_r($row);
            for ($j = 0, $k = 0; $j < count($row); $j++, $k++) {
                $exhibit[$i][$j] = $row[$k];
            }

            $i++;
	    }
	    //print_r($exhibit);
    }
    else print_r($mode);

    $is = 'FALSE';
    if (is_smartphone()) $is = 'TRUE';

    $smarty->assign('data', $data);
    $smarty->assign('exhibit', $exhibit);
    $smarty->assign('museum', $museum);
    $smarty->assign('year', $year);
    // $smarty->assign('msg', $msg);
    $smarty->assign('dbver', dbversion($conn));
    $smarty->assign('is_smartphone', $is);
    $smarty->assign('year_options', array(2019=>'2019',2018=>'2018',2017=>'2017',2016=>'2016',2015=>'2015',2014=>'2014',2013=>'2013',2012=>'2012',2011=>'2011',2010=>'2010',2009=>'2009',2008=>'2008',2007=>'2007',2006=>'2006',2005=>'2005'));
    // $smarty->assign('museum_options', array("PREF=1"=>'北海道',"PREF>=2 AND PREF<=7"=>'東北',"PREF>=15 AND PREF<=20"=>'北陸・甲信越',"PREF>=36 AND PREF<=39"=>'四国'));

	$smarty->assign('label', $label);
	$smarty->assign('pref', $pref);

    if (isset($_GET['exhibition'])) {
        $title = $exhibit[0][0];
        $header_title = $_GET['exhibition'] . " " . $exhibit[0][0];
    }

    if (isset($_GET['expedition'])) {
        $title = $exhibit[0][20];
        if ($title == null) $title = $exhibit[0][16];
	    $smarty->assign('expedition', $expedition);
    }

    $smarty->assign('wherestr', $wherestr);
    $smarty->assign('wherestr2', $wherestr2);

    $smarty->assign('title', $title);

    if ($header_title == "") $header_title = $title;
    $smarty->assign('header_title', $header_title);

    $smarty->display('museum1.tpl');

    function replace($text) {
        // $text = str_replace("フランス人がときめいた日本の美術館", "<a href='https://www.bs11.jp/education/sp/japanese-museums/' target='_blank'>フランス人がときめいた日本の美術館</a>", $text);
        $text = str_replace("フランス人がときめいた日本の美術館", "<a href='" . URL . "?mode=1&title=フランス人がときめいた日本の美術館&fra=1' target='_blank'>フランス人がときめいた日本の美術館</a>", $text);
        return $text;
    }
?>
