<pre><?php
// $a = array();//mảng rỗng
// $b = array(1, 3, 5); //mảng có 3 phần tử
// /*
// $b[0] = 1;
// $b[1] = 3;
// $b[2] = 5;
// */
// $c = array("a"=>2, "b"=>4, "c"=>6);//mảng có 3 phần tử.Các index của mảng là chuỗi
// /*
// $c['a']= 2;
// $c['b'] = 4;
// $c['c'] = 6
// */

// $na = Count($a);
// $nb = Count($b);
// $nc = Count($c);
// echo "Mảng a có $na phần tử <br> ";
// echo "Mảng b có $nb phần tử <br> ";
// echo "Mảng c có $nc phần tử <br> ";
// print_r($a);
// var_dump($b);
// print_r($c);
// $c[0]='tuan';
// print_r ($c);

$c['c']=599;
print_r($c);

$newArr=array();
$oldKey='c';
$newKey='6x';
foreach($c as $key =>$value){
    if($key==$value){
        $newArr[$newKey]=$value+1;

    }else{
        $newArr [$key]=$value;
    }

}
print_r($newArr);
function update_index($c) {
    $newArr = array();
    foreach ($c as $oldKey => $value) {
        if ($oldKey == 'c') {
            $newArr['6x'] = $value + 1;
        } else {
            $newArr[$oldKey] = $value;
        }
    }
    print_r($newArr); 
}


update_index($c);
    


// $a[] = 3;
// $b[] = 7;
// $c['d'] = 8;
// echo "<hr> Sau khi thêm phần tử, nội dung các mảng  là :";
// print_r($a);
// print_r($b);
// print_r($c);

// $x = 1;
// unset($a[$x]);
// unset($b[$x]);
// unset($c['a']);
// echo "<hr> Sau khi xóa phần tử, nội dung các mảng  là :";
// print_r($a);
// print_r($b);
// print_r($c);

// $value = 2;
// $key = 'b';
// if (isset($c[$key]))
//  $c[$key] += $value;
// else $c[$key] = $value;
// echo "<hr> Kết quả mảng c là:";
// print_r($c);

?>