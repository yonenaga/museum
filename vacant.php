<?php
    require 'routine.php';

    $sqlstr = "SELECT ID FROM EXHIBITION ORDER BY ID";
    $result = pg_query($conn, $sqlstr);

    $i = 1;
    while (($row = pg_fetch_array($result)) != NULL) {
        $id = $row[0];
        while ($id > $i) {
            print($i . ", ");
            $i++;
        }

        $i++;
    }

    print($i);
    print("<br><br>");


    $sqlstr = "SELECT ID FROM MUSEUM ORDER BY ID";
    $result = pg_query($conn, $sqlstr);

    $i = 1;
    while (($row = pg_fetch_array($result)) != NULL) {
        $id = $row[0];
        while ($id > $i) {
            print($i . ", ");
            $i++;
        }

        $i++;
    }

    print($i);

?>
