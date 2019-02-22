<?php
    require_once('../Smarty-3.1.32/libs/Smarty.class.php');
    require 'routine.php';
    // define('URL', 'http://www.hojomasaki.com/museuxm/index.php');

    $smarty = new Smarty();

    $smarty->template_dir = '.';
    $smarty->compile_dir = './smarty';
    $smarty->cache_dir = './smarty';
    $smarty->error_reporting = E_ALL & ~E_NOTICE;

    // $label = array('福岡','九州','中国・四国','関西','東海・中部','東京','関東','北海道・東北','VIEW','PLAN');
    // $label_sp = array('福','九','中','西','海','東','関','北','V','P');

    // $pref = array("PREF=40", "PREF>=41", "PREF>=31 AND PREF<=39", "PREF>=25 AND PREF<=30", "PREF>=15 AND PREF<=24", "PREF=13", "PREF>=8 AND PREF!=13 AND PREF<=14", "PREF>=1 AND PREF<=7");
    $label = array('福岡','九州','中国','四国','関西','東海','北陸','甲信越','東京','多摩','関東','東北','北海道');
    // $label_sp = array('福','九','中','西','海','東','関','北','V','P');
    $pref = array("40", "41,42,43,44,45,46,47", "31,32,33,34,35", "36,37,38,39", "25,26,27,28,29,30", "21,22,23,24", "16,17,18", "15,19,20", "13", "48", "8,9,10,11,12,14", "2,3,4,5,6,7", "1");

    // $i = 0;
    // $labels = array();
    // $labels[$i++] = array('福岡','PREF=40');

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

    $year = 2019;
    // if (isset($_POST["year"])) $year = $_POST["year"];
    if (isset($_GET["year"])) $year = $_GET["year"];

    $mode = 2;

    // $select = 0;

    // if (isset($_POST['fukuoka']) || isset($_POST['kyushu']) || isset($_POST['chugoku']) || isset($_POST['kansai']) || isset($_POST['tokai']) || isset($_POST['tokyo']) || isset($_POST['kanto']) || isset($_POST['tohoku'])) {
    //     $mode = 1;
    // }
    // else $mode = 2;

   //  if (isset($_POST['fukuoka'])) {
   //      // $mode = 1;
   //      $wherestr = $pref[0];
   //      $title = $label[0];
   //      // $select = 0;
   //  }
   //  else if (isset($_POST['kyushu'])) {
   //      // $mode = 1;
   //      $wherestr = $pref[1];
   //      $order = "PREF, NAMEK"; 
   //      $title = $label[1]; 
   //      // $select = 1;
   //  }
   //  else if (isset($_POST['chugoku'])) { 
   //      // $mode = 1;
   //      $wherestr = $pref[2];
   //      $order = "PREF, NAMEK"; 
   //      $title = $label[2]; 
   //      // $select = 2;
   //  }
   //  else if (isset($_POST['kansai'])) {
   //      // $mode = 1;
   //      $wherestr = $pref[3];
   //      $order = "PREF, NAMEK";
   //      $title = $label[3];
   //      // $select = 3;
   //  }
   //  else if (isset($_POST['tokai'])) {
   //      // $mode = 1;
   //      $wherestr = $pref[4];
   //      $title = $label[4];
   //      // $select = 4;
   // }
   //  else if (isset($_POST['tokyo'])) {
   //      // $mode = 1;
   //      $wherestr = $pref[5];
   //      $title = $label[5];
   //      // $select = 5;
   //  }
   //  else if (isset($_POST['kanto'])) {
   //      // $mode = 1;
   //      $wherestr = $pref[6];
   //      $title = $label[6];
   //      // $select = 6;
   //  }
   //  else if (isset($_POST['tohoku'])) {
   //      // $mode = 1;
   //      $wherestr = $pref[7];
   //      $title = $label[7];
   //      // $select = 7;
   //  }
   //  //else if (isset($_POST['hokkaido'])) $wherestr = "PREF=1";
   //  else if (isset($_POST['fukuoka2'])) {
   //      $wherestr2 = $pref[0];
   //      $title = $label[0];
   //      // $mode = 2;
   //  }
   //  else if (isset($_POST['kyushu2'])) {
   //      $wherestr2 = $pref[1];
   //      $title = $label[1];
   //      // $mode = 2;
   //  }
   //  else if (isset($_POST['chugoku2'])) {
   //      $wherestr2 = $pref[2];
   //      $title = $label[2];
   //      // $mode = 2;
   //  }
   //  //else if (isset($_POST['shikoku2'])) $wherestr2 = "PREF>=36 AND PREF<=39";
   //  else if (isset($_POST['kansai2'])) {
   //      $wherestr2 = $pref[3];
   //      $title = $label[3];
   //      // $mode = 2;
   //  }
   //  else if (isset($_POST['tokai2'])) {
   //      $wherestr2 = $pref[4];
   //      $title = $label[4];
   //      // $mode = 2;
   //  }
   //  else if (isset($_POST['tokyo2'])) {
   //      $wherestr2 = $pref[5];
   //      $title = $label[5];
   //      // $mode = 2;
   //  }
   //  else if (isset($_POST['kanto2'])) {
   //      $wherestr2 = $pref[6];
   //      $title = $label[6];
   //      // $mode = 2;
   //  }
   //  else if (isset($_POST['tohoku2'])) {
   //      $wherestr2 = $pref[7];
   //      $title = $label[7];
   //      // $mode = 2;
   //  }
   //  //else if (isset($_POST['hokkaido2'])) $wherestr2 = "PREF=1";
   //  else if (isset($_POST['now'])) {
   //      $title = "PLAN";
   //      // $mode = 2;
   //  }
   //  if (isset($_POST['go'])) {
   //      $title = $year;
   //      // $mode = 2;
   //  }

    if (isset($_GET['mode'])) $mode = $_GET['mode'];
    if (isset($_GET['title'])) $title = $_GET['title'];

    if (isset($_GET['pref'])) {
    	$wherestr = "PREF IN (" . $_GET['pref'] .")";
    	// $title = $_GET['title'];
    	$order = "PREF, NAMEK";
    }
	    // print_r($wherestr);

    if ($mode == 1) {
	    // print_r($wherestr);

    	//美術館一覧
        $sqlstr = "SELECT M.ID, NAMEJ, NAMEE, TO_CHAR(OPEN,'HH24:MI'), TO_CHAR(CLOSE,'HH24:MI'), TO_CHAR(CLOSEEX,'HH24:MI'), E.PATTERN, A.ABSENCE, URL, REDUCT, REMODEL, PAYMENT, NOTE, CHECKED, MAP, SCHEDULE, SHOP, P1.TAG, P2.TAG, P3.TAG, P4.TAG, TO_CHAR(C.START,'YYYY/MM/DD'), TO_CHAR(C.END,'YYYY/MM/DD') FROM MUSEUM M LEFT JOIN ABSENCE A ON M.ABSENCE=A.ID LEFT JOIN EXPATTERN E ON M.EXPATTERN=E.ID LEFT JOIN PAYMENT P1 ON M.PAY1=P1.ID LEFT JOIN PAYMENT P2 ON M.PAY2=P2.ID LEFT JOIN PAYMENT P3 ON M.PAY3=P3.ID LEFT JOIN PAYMENT P4 ON M.REDUCT=P4.NAME LEFT JOIN CLOSE C ON M.ID=C.ID AND C.START<=CURRENT_DATE AND C.END>=CURRENT_DATE WHERE " . $wherestr . " AND M.DISABLE=FALSE ORDER BY " . $order;

        print_r("<!--");
        print_r($sqlstr);
        print_r("-->");
        $result = pg_query($conn, $sqlstr);
        //print_r($result);

        $i = 0;
        while (($row = pg_fetch_array($result, NULL, PGSQL_NUM)) != NULL) {
			for ($j = 0, $k = 0; $j < count($row); $j++, $k++) {
                $data[$i][$j] = $row[$k];
            }

            $i++;
        }
        //print_r($data);
    }
    else if ($mode == 2) {
    	//展覧会一覧
        $select = "SELECT E.NAMEJ";
        // //1
        // if (is_smartphone() && isset($_GET['exhibition'])) {
        //     $select .= " ''";
        // }
        // else {
        //     $select .= " E.NAMEJ";
        // }

        //2
        if (is_smartphone() && isset($_GET['museum'])) {
            $select .= ", ''";
        }
        else {
            // $select .= ", M.NAMEJ";
            $select .= ", COALESCE(MP.NAMEJ, M.NAMEJ)";
        }

        //3,4
        $select .= ", TO_CHAR(P.START, 'YYYY/MM/DD') AS STARTDAY, TO_CHAR(P.END, 'YYYY/MM/DD') AS ENDDAY, ";

        //5
        $select .= "CASE WHEN EXISTS(SELECT NULL FROM VIEW V WHERE V.EXHIBITION=P.EXHIBITION AND V.PLACE=P.PLACE) THEN ";
        $select .= "'🔴' ";
        //$select .= "TO_CHAR(VI.DAY, '<b>FMmm/DD</b>') ";
        //$select .= "CASE ";
        $select .= "WHEN EXISTS(SELECT NULL FROM VIEW V WHERE V.EXHIBITION=P.EXHIBITION AND V.PLACE<>P.PLACE AND P.END>=CURRENT_DATE) THEN '🔶' ELSE '' END, ";

        //6
        $select .= "CASE WHEN 0 < (P.END - CURRENT_DATE) AND (P.END - CURRENT_DATE) < 10 THEN '<font color=\"red\">終了まであと' || (P.END - CURRENT_DATE + 1) || '日</font>' ";
        $select .= "WHEN 0 = (P.END - CURRENT_DATE) THEN '<font color=\"red\">本日最終日</font>' ";
        $select .= "WHEN P.START<=CURRENT_DATE AND P.END >= CURRENT_DATE THEN '<font color=\"red\">開催中</font>' ";
        $select .= "WHEN P.END<CURRENT_DATE THEN '<font color=\"gray\">終了</font>' ";
        $select .= "WHEN (P.START - 14) <= CURRENT_DATE THEN '<font color=\"black\">開幕まであと' || (P.START - CURRENT_DATE) || '日</font>' ";
        $select .= "ELSE '' END AS A, ";

        $select .= "P.URL, P.EXHIBITION, P.PLACE, M.CHECKED, P.SPECIAL, ";

        //11
        $select .= "CASE WHEN P.START <= CURRENT_DATE THEN P.END ELSE P.START END AS SORTDAY, ";

        //12-18
        $select .= "E.CATALOG, FALSE, P.REMARKS, E.REMARKS, EP.DESCRIPTION, E.URL";

        if ($wherestr2 != "") {
            $select .= ", TO_CHAR(VI.DAY, '<b>FMmm/DD</b>') AS VIEWDAY";
            // $select .= ", STRING_AGG(TO_CHAR(VI.DAY, '<b>FMmm/DD</b>'), ', ' ORDER BY VI.DAY) AS VIEWDAY";
        }
        else if (isset($_POST['now'])) {
            $select .= ", VI.DAY AS VIEWDAY";
        }
        else {
            //$select .= ", TO_CHAR(VI.DAY, '<b>YY/MM/DD</b>') AS VIEWDAY";
            $select .= ", STRING_AGG(TO_CHAR(VI.DAY, '<b>YY/MM/DD</b>'), '<br>' ORDER BY VI.DAY) AS VIEWDAY";
        }

        //19
        $select .= ", EP.ID ";

        //20
        $select .= ", CASE WHEN EP.FROM<>EP.TO AND DATE_PART('MONTH',EP.FROM)<>DATE_PART('MONTH',EP.TO) THEN EP.DEST || ' ' || TO_CHAR(EP.FROM, 'FMmm/FMDD') || '～' || TO_CHAR(EP.TO, 'FMmm/FMDD') ";
        $select .= "WHEN EP.FROM<>EP.TO AND DATE_PART('MONTH',EP.FROM)=DATE_PART('MONTH',EP.TO) THEN EP.DEST || ' ' || TO_CHAR(EP.FROM, 'FMmm/FMDD') || '～' || TO_CHAR(EP.TO, 'FMDD') ";
        $select .= "ELSE EP.DEST || ' ' || TO_CHAR(EP.FROM, 'FMmm/FMDD') END ";

        $from = "FROM PERIOD P LEFT JOIN EXHIBITION E ON P.EXHIBITION=E.ID ";
        $from .= "LEFT JOIN MUSEUM M ON P.PLACE=M.ID ";
        $from .= "LEFT JOIN EXPEDITION EP ON P.EXPEDITION=EP.ID ";
        $from .= "LEFT JOIN VIEW VI ON VI.EXHIBITION=P.EXHIBITION AND VI.PLACE=P.PLACE ";
        $from .= "LEFT JOIN MUSEUM_PAST MP ON M.ID=MP.ID AND P.END<=MP.ENDDAY ";

        $groupby = "GROUP BY E.NAMEJ, M.NAMEJ, STARTDAY, ENDDAY, A, P.URL, P.EXHIBITION, P.PLACE, M.CHECKED, P.SPECIAL, SORTDAY, E.CATALOG, FALSE, P.REMARKS, E.REMARKS, EP.DESCRIPTION, E.URL, EP.ID, MP.NAMEJ, E.ID, VI.DAY ";

        // if ($wherestr2 != "") {
            $sqlstr = $select;
            $sqlstr .= $from;
            // $sqlstr .= "WHERE " . $wherestr2 . " ";
            $sqlstr .= "WHERE " . $wherestr . " ";
            $sqlstr .= "AND P.END>=CURRENT_DATE ";
            $sqlstr .= $groupby;
            $sqlstr .= "ORDER BY P.START, P.END, E.ID, VI.DAY";
            //$sqlstr .= "ORDER BY SORTDAY";
        // }

        if (isset($_GET['museum'])) {
        	$museum = array();
            $sqlstr = $select;
            $sqlstr .= $from;
            $sqlstr .= "WHERE M.ID=" . $_GET['museum'] . " ";

            $sqlstr .= "GROUP BY E.NAMEJ, M.NAMEJ, STARTDAY, ENDDAY, A, P.URL, P.EXHIBITION, P.PLACE, M.CHECKED, P.SPECIAL, SORTDAY, E.CATALOG, FALSE, P.REMARKS, E.REMARKS, EP.DESCRIPTION, E.URL, EP.ID, MP.NAMEJ ";

            $sqlstr .= "UNION ALL ";
            $sqlstr .= "SELECT '<font color=red><b>休館</b></font>'";
            if (is_smartphone()) {
                $sqlstr .= ", ''";
            }
            else {
                // $sqlstr .= ", M.NAMEJ";
	            $sqlstr .= ", COALESCE(MP.NAMEJ, M.NAMEJ)";
            }
            $sqlstr .= ", TO_CHAR(C.START, 'YYYY/MM/DD') AS STARTDAY, TO_CHAR(C.END, 'YYYY/MM/DD') AS ENDDAY,NULL,NULL,NULL,NULL,C.ID,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL AS VIEWDAY,NULL FROM CLOSE C INNER JOIN MUSEUM M ON C.ID=M.ID AND C.ID=" . $_GET['museum'] . " ";
	        $sqlstr .= "LEFT JOIN MUSEUM_PAST MP ON M.ID=MP.ID AND C.END<=MP.ENDDAY ";
            $sqlstr .= "ORDER BY STARTDAY, ENDDAY, VIEWDAY";

            $sqlstr2 = "SELECT M.NAMEJ, TO_CHAR(M.OPEN,'HH24:MI'), TO_CHAR(M.CLOSE,'HH24:MI'), A.ABSENCE, M.REDUCT, M.PAYMENT, M.NOTE, M.URL, M.NAMEE, M.SCHEDULE FROM MUSEUM M LEFT JOIN ABSENCE A ON M.ABSENCE=A.ID WHERE M.ID=" . $_GET['museum'] . " ";

	        print_r("<!--");
    	    print_r($sqlstr2);
        	print_r("-->");
            $result = pg_query($conn, $sqlstr2);

            while (($row = pg_fetch_array($result, NULL, PGSQL_NUM)) != NULL) {
                for ($j = 0, $k = 0; $j < count($row); $j++, $k++) {
                    $museum[$j] = $row[$k];
                }
            }
        }

        if (isset($_GET['exhibition'])) {
            $sqlstr = $select;
            $sqlstr .= $from;
            $sqlstr .= "WHERE P.EXHIBITION=" . $_GET['exhibition'] . " ";
            $sqlstr .= "GROUP BY E.NAMEJ, M.NAMEJ, STARTDAY, ENDDAY, A, P.URL, P.EXHIBITION, P.PLACE, M.CHECKED, P.SPECIAL, SORTDAY, E.CATALOG, FALSE, P.REMARKS, E.REMARKS, EP.DESCRIPTION, E.URL, EP.ID, MP.NAMEJ ";
            $sqlstr .= "ORDER BY P.START, P.END, VIEWDAY";
        }

        if (isset($_GET['expedition'])) {
            $sqlstr = $select;
            $sqlstr .= $from;
            $sqlstr .= "WHERE EP.ID=" . $_GET['expedition'] . " ";
            $sqlstr .= "ORDER BY P.START, P.END, VI.DAY";

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

        // if (isset($_POST['go'])) {
        if (isset($_GET['year'])) {
    	   //print $year;
            $select = "SELECT E.NAMEJ, COALESCE(MP.NAMEJ, M.NAMEJ), TO_CHAR(P.START, 'YYYY/MM/DD') AS STARTDAY, TO_CHAR(P.END, 'MM/DD') AS ENDDAY";
            // $select .= ", TO_CHAR(VI.DAY, '<b>FMmm/DD</b>')";
            $select .= ", '' AS F";
            $select .= ", '', P.URL, P.EXHIBITION, P.PLACE, FALSE AS A, FALSE AS B, '' AS C, E.CATALOG, VI.GUIDE ";
            $select .= ", '' AS D, '' AS E, EP.DESCRIPTION, E.URL";

            //18
            $select .= ", STRING_AGG(TO_CHAR(VI.DAY, '<b>FMmm/DD</b>'), '<br>' ORDER BY VI.DAY) AS VIEWDAY";

            //19
            $select .= ", EP.ID ";

	        //20
            $select .= ", CASE WHEN EP.FROM<>EP.TO AND DATE_PART('MONTH',EP.FROM)<>DATE_PART('MONTH',EP.TO) THEN EP.DEST || ' ' || TO_CHAR(EP.FROM, 'FMmm/FMDD') || '～' || TO_CHAR(EP.TO, 'FMmm/FMDD') ";
            $select .= "WHEN EP.FROM<>EP.TO AND DATE_PART('MONTH',EP.FROM)=DATE_PART('MONTH',EP.TO) THEN EP.DEST || ' ' || TO_CHAR(EP.FROM, 'FMmm/FMDD') || '～' || TO_CHAR(EP.TO, 'FMDD') ";
            $select .= "ELSE EP.DEST || ' ' || TO_CHAR(EP.FROM, 'FMmm/FMDD') END AS G ";

            $select .= ", MIN(VI.DAY) AS SORTDAY ";

            $sqlstr = $select;
            $sqlstr .= $from;
            $sqlstr .= "WHERE VI.DAY>='" . $year ."-01-01' AND VI.DAY<='" . $year . "-12-31' ";
            $sqlstr .= "GROUP BY E.NAMEJ, M.NAMEJ, STARTDAY, ENDDAY, P.URL, P.EXHIBITION, P.PLACE, E.CATALOG, VI.GUIDE, EP.DESCRIPTION, E.URL, EP.ID, A, B, C, D, E, F, G, VI.ORDER, MP.NAMEJ ";
            $sqlstr .= "ORDER BY SORTDAY, VI.ORDER";
            // print_r($sqlstr);
        }

        // if (isset($_POST['now'])) {
        if (isset($_GET['plan'])) {
            $sqlstr = $select;
            $sqlstr .= $from;
            $sqlstr .= "WHERE P.END>=CURRENT_DATE ";
            //$sqlstr .= "AND (P.START-30)<=CURRENT_DATE ";
            $sqlstr .= "AND P.SPECIAL=TRUE ";
            $sqlstr .= "AND NOT EXISTS (SELECT NULL FROM VIEW V WHERE P.EXHIBITION=V.EXHIBITION AND P.PLACE=V.PLACE) ";
            //$sqlstr .= "ORDER BY M.PREF DESC, P.END";
            $sqlstr .= $groupby;
            $sqlstr .= "ORDER BY P.START, P.END, E.ID, VI.DAY";
            //error_log(print_r($sqlstr), "3", "D:/WORK/error.log");
        }

        // error_log(print_r($sqlstr));
        print_r("<!--");
        print_r($sqlstr);
        print_r("-->");
        $result = pg_query($conn, $sqlstr);
       	//error_log(print_r($result));

        $i = 0;
        while (($row = pg_fetch_array($result, NULL, PGSQL_NUM)) != NULL) {
            // print_r($row);
            for ($j = 0, $k = 0; $j < count($row); $j++, $k++) {
                $exhibit[$i][$j] = $row[$k];
            }

            //if ($row[19] == 11) print_r($exhibit[$i]);

            $i++;
	    }
	    //print_r($exhibit);
    }
    else print_r($mode);

    $is = is_smartphone();

    $smarty->assign('data', $data);
    $smarty->assign('exhibit', $exhibit);
    $smarty->assign('museum', $museum);
    $smarty->assign('year', $year);
    // $smarty->assign('msg', $msg);
    $smarty->assign('dbver', dbversion($conn));
    $smarty->assign('is_smartphone', $is);
    $smarty->assign('year_options', array(2019=>'2019',2018=>'2018',2017=>'2017',2016=>'2016',2015=>'2015',2014=>'2014',2013=>'2013',2009=>'2009',2006=>'2006',2005=>'2005'));
    //$smarty->assign('museum_options', array("PREF>=21 AND PREF<=24"=>'東海',"PREF=1"=>'北海道',"PREF>=2 AND PREF<=7"=>'東北',"PREF>=8 AND PREF!=13 AND PREF<=14"=>'関東',"PREF>=15 AND PREF<=20"=>'北陸・甲信越',"PREF>=36 AND PREF<=39"=>'四国'));
    $smarty->assign('museum_options', array("PREF=1"=>'北海道',"PREF>=2 AND PREF<=7"=>'東北',"PREF>=15 AND PREF<=20"=>'北陸・甲信越',"PREF>=36 AND PREF<=39"=>'四国'));

 	// if ($is) {
	//     $smarty->assign('label', $label_sp);
	// }
	// else {
		$smarty->assign('label', $label);
	// }
	$smarty->assign('pref', $pref);

    if (isset($_GET['exhibition'])) {
        $title = $exhibit[0][0];
    }
    if (isset($_GET['expedition'])) {
        $title = $exhibit[0][20];
        if ($title == null) $title = $exhibit[0][16];
	    $smarty->assign('expedition', $expedition);
    }

    $smarty->assign('wherestr', $wherestr);
    $smarty->assign('wherestr2', $wherestr2);

    $smarty->assign('title', $title);

    $smarty->display('museum1.tpl');
?>
