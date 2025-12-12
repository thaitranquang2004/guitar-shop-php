<?php
    $arr=array();
    $r=array("id"=>"sp1","name"=>"San pham 1");
    $arr[]=$r;
    $r=array("id"=>"sp2","name"=>"San pham 2");
    $arr[]=$r;
    $r=array("id"=>"sp1","name"=>"San pham 3");
    $arr[]=$r;

    function showArray($arr){
        echo "<table border=1>";
        echo "<tr><td>Stt</td>
            <td>Ma san pham</td>
            <td>Ten san pham</td></tr>";
              
            for ($i=0;$i<count($arr);$i++) {
                echo "<tr><td>" . ($i+1) . "</td>";  
                echo "<td>" . $arr[$i]['id'] . "</td>";  
                echo "<td>" . $arr[$i]['na  me'] . "</td></tr>"; 
            }
        echo "</table>";
    }
    showArray($arr);
?>