<?php
    require_once '../../DSN.php';
    require_once('../Smarty-3.1.33/libs/Smarty.class.php');

    //データベースへ接続
    $conn = pg_connect("host=localhost port=5432 dbname=museum user=postgres password=" . $dsn['passwd']);
    if ($conn == FALSE) {
        echo "pg_connect error.";
        die;
    }

    function is_smartphone($ua = null) {
        if (is_null($ua)) {
            $ua = $_SERVER['HTTP_USER_AGENT'];
        }

        // if ( preg_match('/iPhone|iPod|iPad|Android/ui', $ua) ) {
        if ( preg_match('/iPhone|iPod|Android/ui', $ua) ) {
            return true;
        }
        else {
            return false;
        }
    }

    function dbversion($conn) {
        $sqlstr = "SELECT version()";
        $result = pg_query($conn, $sqlstr);
        while (($row = pg_fetch_array($result)) != NULL) {
            return $row[0];
        }
    }

?>