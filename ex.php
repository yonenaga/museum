<?PHP
    require_once '../../DSN.php';

    if (!pg_ping()) {
        $conn = pg_connect("host=localhost port=5432 dbname=museum user=postgres password=" . $dsn['passwd']);
        if ($conn == FALSE) {
            echo "pg_connect error.";
            die;
        }
    }

    require_once('../Smarty-3.1.30/libs/Smarty.class.php');

    $smarty = new Smarty();

    $smarty->template_dir = '.';
    $smarty->compile_dir = './smarty';
    $smarty->cache_dir = './smarty';

    $sqlstr = "SELECT ID, NAMEJ, TO_CHAR(MIN(P.START),'YYYY/MM/DD') AS STAD, TO_CHAR(MAX(P.END),'YYYY/MM/DD') AS ENDD, E.URL FROM EXHIBITION E INNER JOIN PERIOD P ON E.ID=P.EXHIBITION ";

    if (isset($_POST['keyword'])) {
        $input = $_POST['searchstr'];
        if ($input != "") {
            $kwds = explode(" ", $input);
            $sqlstr .= "WHERE ";
            $flag = false;
            foreach ($kwds as &$kwd) {
                if ($flag) $sqlstr .= "AND ";
                $sqlstr .= "NAMEJ LIKE '%" . $kwd . "%' ";
                $flag = true;
            }
        }
    }

    $sqlstr .= "GROUP BY ID, NAMEJ ";
    // $sqlstr .= "ORDER BY STAD DESC ";
    $sqlstr .= "ORDER BY ID DESC ";
    //print_r($sqlstr);

    $data = array();
    if ($sqlstr != "") {
        $result = pg_query($conn, $sqlstr);
        //print_r($result);

        $i = 0;
        $j = 0;
        $k = 0;
        while (($row = pg_fetch_array($result)) != NULL) {
        	//print_r(count($row));
			//for ($j = 0, $k = 0; $j < 2; $j++, $k++) {
            //print_r($row[$k]);
            while ($j < count($row)) {
                $data[$i][$j++] = $row[$k++];
            }

            $i++;
            $j = 0;
            $k = 0;
        }
    }

    $smarty->assign('data', $data);
    $smarty->assign('input', $input);
    $smarty->display('ex.tpl');
   
?>