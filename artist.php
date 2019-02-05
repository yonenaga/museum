<?php
    require_once('../Smarty-3.1.32/libs/Smarty.class.php');
    require 'routine.php';

    $smarty = new Smarty();

    $smarty->template_dir = '.';
    $smarty->compile_dir = './smarty';
    $smarty->cache_dir = './smarty';

    $artist = array();

    $sql = "SELECT * FROM ARTIST ORDER BY BORN";

    $result = pg_query($conn, $sql);
    // print_r($result);

    $i = 0;
    while (($row = pg_fetch_array($result, NULL, PGSQL_NUM)) != NULL) {
        for ($j = 0, $k = 0; $j < count($row); $j++, $k++) {
            $artist[$i][$j] = $row[$k];
        }
        $i++;
    }
    // print_r($artist);

    $smarty->assign('artist', $artist);
    $smarty->display('artist.tpl');
?>