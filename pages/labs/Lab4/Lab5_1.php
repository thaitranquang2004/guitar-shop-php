<?php
    $a=array(1,2,3,4,5);
    var_dump($a);
    function showArray($arr){
        echo "<table border=1 cellspacing=0>
                <tr><th>Index</th> <th>Value</th></tr>";
        foreach ($arr as $key => $value) {
            echo "<tr align=center><td>" . $key . "</td><td>" . $value . "</td></tr>";
        }
        echo "</table>";
    }
    showArray($a);

?>